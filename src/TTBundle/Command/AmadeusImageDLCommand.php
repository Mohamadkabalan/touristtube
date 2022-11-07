<?php

namespace TTBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TTBundle\Entity\AmadeusHotelImage;

class AmadeusImageDLCommand extends ContainerAwareCommand
{
    private $em;
    private $hotelRepo;
    private $imgRepo;
    private $logFile;
    private $logMessages;

    protected function configure()
    {
        $this->setName('amadeus:amadeus-image-download')
            ->setDescription("Downloads an image")
            ->setHelp('This command allows you to download an image per given url')
            ->addArgument('url', InputArgument::REQUIRED, "image url")
            ->addArgument('filename', InputArgument::REQUIRED, "image path (path + filename)")
            ->addOption('logFile', null, InputOption::VALUE_OPTIONAL, 'log file path', '')
            ->addOption('traceNum', null, InputOption::VALUE_OPTIONAL, 'trace number', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url      = $input->getArgument('url');
        $filename = $input->getArgument('filename');

        $traceNum          = $input->getOption('traceNum');
        $this->logFile     = $input->getOption('logFile');
        $this->logMessages = array();

        try {
            $this->em        = $this->getContainer()->get('doctrine')->getManager();
            $this->hotelRepo = $this->em->getRepository('TTBundle:AmadeusHotel');
            $this->imgRepo   = $this->em->getRepository('TTBundle:AmadeusHotelImage');

            $dir_created = true;
            $pathinfo    = pathinfo($filename);
            if (isset($pathinfo['dirname'])) {
                if (!file_exists($pathinfo['dirname'])) {
                    $dir_created = mkdir($pathinfo['dirname'], 0777, true);
                }
            }

            if (file_exists($filename)) {
                unlink($filename);
            }

            //$this->logMessages[] = "DOWNLOADING ({$traceNum}): {$filename}";
            $start = microtime(true);

            $ch = curl_init($url);
            $fp = fopen($filename, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            $end = microtime(true);

            if (file_exists($filename)) {
                $dirs = explode('/', $pathinfo['dirname']);

                $category   = end($dirs);
                $dupePoolID = substr(prev($dirs), 1);

                //$this->logMessages[] = "   --> category: {$category} | dupePoolID: {$dupePoolID}";
                $this->addImageToDB($filename, $dupePoolID, $category, $output);
                unset($dirs, $category, $dupePoolID);
            } else {
                //$this->logMessages[] = "<fg=red>ERROR: issue downloading image '{$url}'</>";
            }

            //$this->logMessages[] = "   -- " . round(($end - $start), 2) . " seconds --";
            unset($dir_created, $pathinfo, $ch, $fp, $start, $end);
        } catch (\Exception $ex) {
            $this->logMessages[] = "<fg=red>ERROR DOWNLOADING IMAGE: ".$ex->getMessage().'</>';
            $this->logMessages[] = "<fg=red>Image: ".$filename.'</>';
            unset($ex);
        }

        //$this->logMessages[] = "   DONE";
        if (count($this->logMessages)) {
            $this->log(implode(PHP_EOL, $this->logMessages), $output);
        }

        unset($input, $output, $url, $filename, $traceNum, $this->em, $this->hotelRepo, $this->imgRepo, $traceNum, $this->logFile, $this->logMessages);
    }

    private function addImageToDB($path, $dupePoolID, $category, &$output)
    {
        $result = TRUE;
        try {
            // retrieve hotelID
            $hotel = $this->hotelRepo->findOneBy(array('dupePoolId' => $dupePoolID));
            if ($hotel) {
                $hotelId  = $hotel->getId();
                $filename = basename($path);

                $img = $this->imgRepo->findOneBy(array(
                    'hotelId' => $hotelId,
                    'location' => $category,
                    'filename' => $filename
                ));

                if (!$img) {
                    $img = new AmadeusHotelImage();
                    $img->setFilename($filename);
                    $img->setHotelId($hotelId);
                    $img->setLocation($category);

                    $this->em->persist($img);
                    $this->em->flush();

                    //$this->logMessages[] = "   --> image tracked";
                } else {
                    //$this->logMessages[] = "   --> image already tracked";
                }

                unset($hotelId, $filename, $img);
            } else {
                $this->logMessages[] = "<fg=yellow>WARNING: hotel for dupePoolID={$dupePoolID} not found!.</>";
            }

            unset($hotel);
        } catch (\Exception $ex) {
            $this->logMessages[] = "<fg=red>ERROR: {$ex->getMessage()}.</>";
            $result              = FALSE;
            unset($ex);
        }

        unset($path, $dupePoolID, $category);
        return $result;
    }

    private function log($message, &$output)
    {
        //$output->writeln($message);
        if ($this->logFile) {
            $fw = fopen($this->logFile, 'a+');
            fwrite($fw, $message.PHP_EOL);
            fclose($fw);
            unset($fw);
        }

        unset($message);
    }
}