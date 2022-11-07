<?php

namespace HotelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use HotelBundle\Entity\AmadeusHotelImage;
use HotelBundle\vendors\Amadeus\AmadeusHandler;

class AmadeusImagesDLCommand extends ContainerAwareCommand
{
    private $util;
    private $service;
    private $em;
    private $hotelRepo;
    private $hsRepo;
    private $imgRepo;
    private $otaRepo;
    private $categoryOTACodes;
    private $destinationDIR;
    private $logName;
    private $logParams;
    private $logMessages;
    private $errors;
    private $dlImageLogFile;
    private $resourceLimit;

    protected function configure()
    {
        $this->setName('amadeus:amadeus-images-download')
            ->setDescription("Downloads all amadeus hotel images or certain amadeus hotel images per provided HotelCode using Amadeus's Hotel_DescriptionInfo API")
            ->setHelp('This command allows you to download amadeus hotel images')
            ->addArgument('hotelCode', InputArgument::REQUIRED, "'all'/<hotelCode> to download all amadeus hotel images on DB; OR specific hotel images respectively.")
            ->addOption('dir', null, InputOption::VALUE_REQUIRED, 'the destination directory of the downloaded images.', '/data/media/hotels')
            ->addOption('offset', null, InputOption::VALUE_REQUIRED, 'offset of the query to get the hotels to download the image when hotelCode="all"', 0)
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'limit of the query to get the hotels to download the image when hotelCode="all"', 100)
            ->addOption('iterate', null, InputOption::VALUE_REQUIRED, 'triggers iteration starting from the provided offset', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);

        $this->util    = $this->getContainer()->get('app.utils');
        $this->em      = $this->getContainer()->get('doctrine')->getManager();
        $this->logger  = $this->getContainer()->get('HotelLogger');
        $this->service = new AmadeusHandler($this->util, $this->getContainer(), $this->em);

        $this->hotelRepo = $this->em->getRepository('HotelBundle:AmadeusHotel');
        $this->hsRepo    = $this->em->getRepository('HotelBundle:AmadeusHotelSource');
        $this->imgRepo   = $this->em->getRepository('HotelBundle:AmadeusHotelImage');
        $this->otaRepo   = $this->em->getRepository('HotelBundle:OtaCodes');

        $this->logName                = pathinfo(__FILE__)['filename'];
        $this->logParams['timeStart'] = date('YmdHis', time());
        $this->logParams['group_dir'] = "AmadeusDLCommand";

        $this->logMessages    = array();
        $this->errors         = array();
        $this->dlImageLogFile = '';


        $this->destinationDIR = $input->getOption('dir');
        $hotelCode            = $input->getArgument('hotelCode');

        //$output->writeln(" ");
        //$this->log('Destination Directory: ' . $this->destinationDIR, $output);
        // create directory if not existing
        if (!$this->getContainer()->get("TTFileUtils")->fileExists($this->destinationDIR)) {
            mkdir($this->destinationDIR, 0777, true);
        }

        /**
         * since our image download on a background process...
         * this limit will make our script to sleep/wait for all previous images to be
         * completely downloaded before downloading process continues
         */
        $this->resourceLimit = 5;

        $hotelCodes = array();
        if (trim(strtolower($hotelCode)) == 'all') {
            $con = $this->em->getConnection();

            $offset  = $input->getOption('offset');
            $limit   = $input->getOption('limit');
            $iterate = $input->getOption('iterate');

            $this->initializeOffsetsAndLimits($offset, $limit, $iterate);

            $flag = 1;
            $ctr  = $offset;

            while ($flag == 1) {
                $hotelCodes = array();

                try {
                    $sql = "
			SELECT
			    ahs.hotel_code,
			    ah.dupe_pool_id
			FROM amadeus_hotel_source ahs
			INNER JOIN amadeus_hotel ah
			    ON ahs.hotel_id = ah.id
			LIMIT {$offset}, {$limit}";

                    $stmt = $con->prepare($sql);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch()) {
                            $hotelCodes = array("{$row['hotel_code']}" => $row['dupe_pool_id']);

                            $ctr++;
                            $this->downloadImage($hotelCodes, $this->destinationDIR, $output, $ctr);
                            unset($row);
                        }
                    } else {
                        $flag = 0;
                    }
                } catch (\Exception $ex) {
                    $this->log("<fg=red>ERROR: {$ex->getMessage()}", $output);
                    unset($ex);
                }


                $stmt->closeCursor();
                $offset += $limit;

                unset($sql, $stmt);

                if (!$iterate) {
                    break;
                }
            }

            unset($con, $offset, $limit, $iterate, $flag, $ctr);
        } else {
            $DupePoolID = $this->getDupePoolID($hotelCode);
            $hotelCodes = array("{$hotelCode}" => $DupePoolID);

            $this->downloadImage($hotelCodes, $this->destinationDIR, $output);

            unset($DupePoolID);
        }

        /**
         * # find processes of our script being run/executed
         * ps aux -u <username> | grep "php app/console amadeus:amadeus-image"
         *
         * # find and get pid of our script being run/executed
         * ps aux -u <username> | grep "php app/console amadeus:amadeus-image" | awk '{print $2}'
         *
         * # kill process of our script being run/executed
         * kill $(ps aux -u <username> | grep "php app/console amadeus:amadeus-image" | awk '{print $2}')
         */
        $this->wait($output);

        // resolving some issues
        $this->resolveSomeIssues($output);
        if (count($this->errors) > 0) {
            $this->log("<fg=red>ERRORS:</>", $output);
            $this->log(print_r($this->errors, true), $output);
        }

        $end = microtime(true);

        $this->log('DONE!', $output);
        $this->log(round(($end - $start), 2)." seconds", $output, true);

        unset($start, $end, $hotelCodes, $input, $output, $this->destinationDIR, $hotelCode, $this->util, $this->service, $this->em, $this->hotelRepo, $this->hsRepo, $this->imgRepo, $this->otaRepo, $this->categoryOTACodes, $this->logName, $this->logParams, $this->logMessages, $this->errors, $this->dlImageLogFile);
    }

    private function downloadImage(array $hotelCodes, $destinationDIR, &$output, $ctr = 0)
    {
        try {
            if (count($hotelCodes) > 0) {
                // images for specifi hotel
                $params = array(
                    'minimalData' => true,
                    'hotelCodes' => array_keys($hotelCodes),
                    'stateful' => false,
                    'group_dir' => $this->logParams['group_dir']
                );

                $serviceStart = microtime(true);
                $result       = $this->service->hotelDescriptiveInfo($params, false);
                $serviceEnd   = microtime(true);

                /* // custom errors
                  if ($ctr == 2 || $ctr == 3) {
                  $result['error'] = array('message' => 'could not connect to host');
                  } */

                //$output->writeln(" ");
                //$this->log("Service call took: " . round(($serviceEnd - $serviceStart), 2) . " seconds", $output);
                // get images
                if (isset($result['error'])) {
                    //$this->log("<fg=red>ERROR: " . print_r($result['error'], true) . "</>", $output);
                    //$this->log("<fg=red>HotelCodes requested: " . print_r(array_keys($hotelCodes), true) . "</>", $output);

                    $this->trackError($result['error'], $hotelCodes, 'soap');

                    // clear all background running process before continuing
                    $this->wait($output);
                } elseif (isset($result['lastResponse'])) {
                    $xml = $result['lastResponse'];

                    $domDoc = new \DOMDocument('1.0', 'UTF-8');
                    $domDoc->loadXML($xml);

                    $domXpath = new \DOMXPath($domDoc);
                    $domXpath->registerNamespace('soap', "http://schemas.xmlsoap.org/soap/envelope/");
                    $domXpath->registerNamespace('ota', 'http://www.opentravel.org/OTA/2003/05');

                    $xpath_string = '//soap:Body/ota:OTA_HotelDescriptiveInfoRS/ota:HotelDescriptiveContents/ota:HotelDescriptiveContent';
                    $hotels       = $domXpath->evaluate($xpath_string);

                    $img_ctr = 0;
                    if ($hotels->length > 0) {
                        foreach ($hotels as $num => $hotel) {
                            $HotelCode  = $domXpath->evaluate('string(./@HotelCode)', $hotel);
                            $DupePoolID = $hotelCodes[$HotelCode];

                            // let's create the directory even if there will be no images stored later on.
                            $path       = "{$destinationDIR}/g{$DupePoolID}";
                            $dirCreated = true;
                            if (!$this->getContainer()->get("TTFileUtils")->fileExists($path)) {
                                $dirCreated = mkdir($path, 0777, true);
                            }

                            /* if (!$dirCreated) {
                              $this->log("<fg=yellow>WARNING: directory '{$path}' not created.</>", $output);
                              } */

                            //$this->log("{$ctr}. HotelCode: {$HotelCode} ({$DupePoolID})", $output);

                            $xpath_string       = "./ota:HotelInfo/ota:Descriptions/ota:MultimediaDescriptions/ota:MultimediaDescription/ota:ImageItems/ota:ImageItem[ota:ImageFormat/@IsOriginalIndicator='true']";
                            $hotel_images_count = $domXpath->evaluate("count(./ota:HotelInfo/ota:Descriptions/ota:MultimediaDescriptions/ota:MultimediaDescription/ota:ImageItems/ota:ImageItem/ota:ImageFormat[@IsOriginalIndicator='true'])", $hotel);

                            if (!$hotel_images_count) {
                                $xpath_string       = "./ota:HotelInfo/ota:CategoryCodes/ota:GuestRoomInfo/ota:MultimediaDescriptions/ota:MultimediaDescription/ota:ImageItems/ota:ImageItem[ota:ImageFormat/@IsOriginalIndicator='true']";
                                $hotel_images_count = $domXpath->evaluate("count(./ota:HotelInfo/ota:CategoryCodes/ota:GuestRoomInfo/ota:MultimediaDescriptions/ota:MultimediaDescription/ota:ImageItems/ota:ImageItem/ota:ImageFormat[@IsOriginalIndicator='true'])", $hotel);
                            }

                            if ($hotel_images_count > 0) {
                                /* $progress = new ProgressBar($output, $hotel_images_count);
                                  $progress->setFormatDefinition('custom', ' %current%/%max% (%percent%%) -- %message%');
                                  $progress->setFormat('custom');

                                  $progress->setOverwrite(true);

                                  $progress->setMessage('Start');
                                  $progress->start(); */

                                $imageItems = $domXpath->evaluate($xpath_string, $hotel);
                                if ($imageItems->length > 0) {
                                    foreach ($imageItems as $imageItem) {
                                        $categoryCode = $domXpath->evaluate('string(./@Category)', $imageItem);
                                        $category     = $this->util->cleanTitle($this->formatCategory($this->getCategory($categoryCode)));

                                        $imgNodes = $domXpath->evaluate("./ota:ImageFormat[@IsOriginalIndicator='true']", $imageItem);
                                        if ($imgNodes->length > 0) {
                                            foreach ($imgNodes as $imgNode) {
                                                $filenameInfo = pathinfo($domXpath->evaluate('string(@FileName)', $imgNode));

                                                // sometimes filename from the response does not reflect extension
                                                if (!isset($filenameInfo['extension'])) {
                                                    $filenameInfo['extension'] = $this->otaRepo->getOTAValue('CFC', $domXpath->evaluate('string(@Format)', $imgNode));
                                                }

                                                $filename = $this->util->cleanTitle($filenameInfo['filename']).".{$filenameInfo['extension']}";
                                                $url      = $domXpath->evaluate('string(./ota:URL)', $imgNode);

                                                $path       = "{$destinationDIR}/g{$DupePoolID}/{$category}";
                                                $dirCreated = true;
                                                if (!$this->getContainer()->get("TTFileUtils")->fileExists($path)) {
                                                    $dirCreated = mkdir($path, 0777, true);
                                                }

                                                if ($dirCreated) {
                                                    $path .= "/{$filename}";

                                                    // clean existing file
                                                    if ($this->getContainer()->get("TTFileUtils")->fileExists($path)) {
                                                        $this->getContainer()->get("TTFileUtils")->unlinkFile($path);
                                                    }

                                                    $curDIR  = getcwd();
                                                    $baseDIR = dirname(dirname(dirname(dirname(__FILE__))));

                                                    chdir($baseDIR);
                                                    $cmd = "php app/console amadeus:amadeus-image-download \"{$url}\" \"{$path}\" --logFile=\"{$this->dlImageLogFile}\" --traceNum=\"{$ctr}\"> /dev/null 2>&1 &";

                                                    exec($cmd);
                                                    chdir($curDIR);

                                                    // put limits so not it won't be so resource extensive
                                                    $img_ctr++;

                                                    $mod = $img_ctr % $this->resourceLimit;
                                                    if ($mod == 0) {
                                                        $this->wait($output);
                                                    }

                                                    unset($mod, $curDIR, $baseDIR, $cmd);
                                                } else {
                                                    $this->log("<fg=red>ERROR: directory '{$path}' not created.</>", $output);
                                                }

                                                /* $progress->setMessage("{$category}/{$filename}");
                                                  $progress->advance(); */

                                                unset($imgNode, $filenameInfo, $filename, $url);
                                            }
                                        }

                                        unset($imageItem, $categoryCode, $category, $imgNodes);
                                    }
                                }

                                /* $progress->setMessage('Finish');
                                  $progress->finish();
                                  $this->log(' ', $output);
                                  unset($imageItems, $progress); */
                                unset($imageItems);
                            } else {
                                //$this->log("<fg=yellow> -- no images found.</>", $output);
                            }

                            unset($hotel, $HotelCode, $DupePoolID, $hotel_images_count, $path, $dirCreated);
                        }
                    }

                    unset($xml, $domDoc, $domXpath, $xpath_string, $hotels, $img_ctr);
                }

                unset($params, $result, $serviceStart, $serviceEnd);
            } else {
                //$this->log('no specified hotel(s) to download image(s) from', $output);
            }
        } catch (\Exception $ex) {
            $this->log("<fg=red>ERROR: ".$ex->getMessage()."</>", $output);
            $this->log("<fg=red>HotelCodes requested: ".print_r(array_keys($hotelCodes), true)."</>", $output);
            unset($ex);
        }

        unset($hotelCodes, $destinationDIR, $ctr);
    }

    private function formatCategory($category)
    {
        return preg_replace('/(\s*\/)+/', '-', $category);
    }

    private function getCategory($code)
    {
        if (count($this->categoryOTACodes) < 1) {
            $this->initCategoryOTACodes();
        }

        return $this->categoryOTACodes[$code];
    }

    private function getDupePoolID($hotelCode)
    {
        $dupePoolID = 0;

        // retrieve hotelID
        $hotelSource = $this->hsRepo->findOneByHotelCode($hotelCode);
        $hotelId     = $hotelSource->getHotelId();

        // retrieve the dupePoolID
        if ($hotelId) {
            $hotel      = $this->hotelRepo->findOneById($hotelId);
            $dupePoolID = $hotel->getDupePoolId();
            unset($hotel);
        }

        unset($hotelCode, $hotelSource, $hotelId);
        return $dupePoolID;
    }

    private function initializeOffsetsAndLimits(&$offset, &$limit, &$iterate)
    {
        // validate inputs and initialize default values
        if (!is_numeric($offset)) {
            $offset = -1;
        }

        if (!is_numeric($limit)) {
            $limit = -1;
        }

        if (!is_numeric($iterate)) {
            $iterate = 0;
        }
    }

    private function initCategoryOTACodes()
    {
        $ota = $this->otaRepo->findBy(array('category' => 'PIC'));

        $this->categoryOTACodes = array();
        if (!empty($ota)) {
            foreach ($ota as $item) {
                $this->categoryOTACodes[$item->getCode()] = $item->getValue();
                unset($item);
            }
        }

        unset($ota);
    }

    private function log($message, &$output, $end = false)
    {
        //$output->writeln($message);
        $this->logMessages[] = $message;

        if (empty($this->dlImageLogFile)) {
            $this->dlImageLogFile = $this->logger->getLogFile('amadeus', $this->logName, $this->logParams);
            $this->dlImageLogFile = str_replace($this->logName, 'AmadeusDownloadedImage', $this->dlImageLogFile);

            $this->logParams['log'] = "START";
            $this->logger->info($this->dlImageLogFile, '', $this->logParams['log']);
        }

        if (count($this->logMessages) > 500 || $end) {
            $this->logParams['log'] = implode(PHP_EOL, $this->logMessages);
            $this->logger->info($this->dlImageLogFile, '', $this->logParams['log']);

            $this->logMessages = array();
        }


        unset($message);
    }

    private function resolveSomeIssues(&$output)
    {
        try {
            //$this->log("Trying to resolve some issues...", $output);
            $errors = $this->errors;
            foreach ($errors as $key => $value) {
                //$this->log("{$key}: ", $output);
                foreach ($value as $msg => $items) {
                    if (!is_array($items)) {
                        $items = array($items);
                    }

                    //$this->log("=== [{$msg}] ===", $output);
                    if ($msg == 'could_not_connect_to_host') {
                        // clear tracked issues to be resolved
                        unset($this->errors[$key][$msg]);

                        foreach ($items as $hotel) {
                            $this->downloadImage($hotel, $this->destinationDIR, $output);
                            unset($hotel);
                        }
                    }

                    unset($msg, $items);
                }

                if (count($this->errors[$key]) == 0) {
                    unset($this->errors[$key]);
                }

                //$this->log("---------", $output);
                //$this->log(" ", $output);

                unset($key, $value);
            }

            unset($errors);
        } catch (\Exception $ex) {
            $this->log("<fg=red>ERROR:{$ex->getMessage()}</>", $output);
        }
    }

    private function trackError($message, $hotelCode, $type)
    {
        if (!isset($this->errors[$type])) {
            $this->errors[$type] = array();
        }

        if (is_array($message)) {
            if (isset($message['message'])) {
                $message = $message['message'];
            }
        }

        if (!is_array($message)) {
            $message = array($message);
        }

        foreach ($message as $item) {
            $item = preg_replace('/\s+/m', '_', $item);
            $item = strtolower($item);
            if (!isset($this->errors[$type][$item])) {
                $this->errors[$type][$item] = array();
            }

            $this->errors[$type][$item][] = $hotelCode;
            unset($item);
        }

        unset($message, $hotelCode, $type);
    }

    private function wait(&$output)
    {
        $start = microtime(true);

        //$output->writeln(" ");
        //$this->log("<fg=blue>  Waiting for all the images downloaded on background process to finish.</>", $output);

        $flag   = 0;
        $result = array();

        $cmd = "whoami";
        exec($cmd, $result);

        $username = $result[0];
        if ($username) {
            $cmd = 'ps aux -u '.$username.' | grep "php app/console amadeus:amadeus-image" | awk \'BEGIN {S="|"; C="-"; B=" ";} {print $2S$12S$13B$14B$15B$16}\'';
            while ($flag == 0) {
                $result = array();
                exec($cmd, $result);

                if (!$result) {
                    $result = array();
                }

                //$output->writeln("wait result: " . print_r($result, true));
                if (count($result) > 3) {
                    $flag = 0;
                    sleep(rand(2, 5));
                } else {
                    $flag = 1;
                    sleep(1);
                    break;
                }
            }

            $end = microtime(true);

            //$this->log("<fg=blue>  -- " . round(($end - $start), 2) . " seconds --</>", $output);
            unset($end);
        } else {
            $this->log('<fg=red>ERROR: whoami does not return anything...</>', $output);
        }

        //$output->writeln('');
        unset($start, $flag, $result, $username, $cmd);
    }
}
