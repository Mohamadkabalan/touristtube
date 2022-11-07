<?php

namespace HotelBundle\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HotelLogger
{

    /**
     * This method initializes the container to be used in this class
     *
     * @return
     */
    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->logger    = $logger;
    }

    /**
     * This method writes to message the log file
     *
     * @param string $fileName The log file's name
     * @param string $name
     * @param string $msg
     *
     * @return
     */
    private function add($fileName, $name, $msg)
    {
        // check if directory exists; otherwise create it!
        $pathinfo = pathinfo($fileName);
        if (!$this->container->get("TTFileUtils")->fileExists($pathinfo['dirname'], true)) {
            mkdir($pathinfo['dirname'], 0777, true);
        }

        $logFile = fopen($fileName, "a");

        fwrite($logFile, "\n\n".strtoupper($name).": \n");
        fwrite($logFile, $msg."\n");
        fclose($logFile);
    }

    /**
     * This method add log messages for booking request actions to the hotel_activity rotating log file
     *
     * @param string $src
     * @param string $action
     * @param int $userId
     * @param array $params
     * @param array $results
     *
     * @return
     */
    public function addBookingRequestLog($src, $action, $userId, $params, $results)
    {
        $action  = ucfirst($action);
        $results = json_decode(json_encode($results), true);

        $transactionId = (!empty($results) && isset($results['transactionId'])) ? $results['transactionId'] : ((isset($params['transactionId'])) ? $params['transactionId'] : '');
        $activityInfo  = array('hotelId' => $params['hotelId'], 'name' => $params['hotelName'], 'transactionId' => $transactionId);

        if (isset($results['error'])) {
            $error = json_encode($results['error']);
            $this->addHotelActivityLog($src, "{$action} request FAILED. Error: {$error}. See log file {$results['errorLogFile']} for xml request/response log.", $userId, $activityInfo);
        } else {
            if ($action == 'Cancellation') {
                $activityInfo = array_merge($activityInfo, $results);
            } else {
                $activityInfo['reservationProcessKey']      = (isset($results['reservationProcessKey'])) ? $results['reservationProcessKey'] : '';
                $activityInfo['reservationProcessPassword'] = (isset($results['reservationProcessPassword'])) ? $results['reservationProcessPassword'] : '';
                $activityInfo['controlNumber']              = (isset($params['controlNumber'])) ? $params['controlNumber'] : '';
            }
            $this->addHotelActivityLog($src, "{$action} request SUCCESSFUL", $userId, $activityInfo);
        }
    }

    /**
     * This method add log messages to hotel_activity rotating log file
     *
     * @param string $src
     * @param string $action
     * @param int $userId
     * @param array $activityInfo
     *
     * @return
     */
    public function addHotelActivityLog($src, $action, $userId, $activityInfo = array())
    {
        switch (strtolower($action)) {
            case 'search':
                $message = 'Searched available hotels.';
                break;

            case 'hotel_details':
                $message = "Checked hotel {$activityInfo['hotelName']}.";
                break;

            case 'offers':
                $message = "Checked offers from hotel {$activityInfo['hotelName']}.";
                break;

            case 'offer_selected':
                $message = "Selected {$activityInfo['offersSelectedCount']} room(s) on hotel {$activityInfo['hotelName']} for booking.";
                break;

            case 'reservation':
                $message = "Submitted reservation request for {$activityInfo['offersSelectedCount']} room(s) on hotel {$activityInfo['hotelName']}";
                break;

            case 'modification':
            case 'room_cancellation':
            case 'cancellation':
                $message = "Submitted {$action} request for hotel reservation.";
                break;

            case 'payment':
                $message = "Payment submitted.";
                break;

            case 'booking_details':
                $message = "Viewed booking details.";
                break;

            default:
                $message = $action;
                break;
        }

        $this->cleanParams($activityInfo);

        $params = array('src' => strtoupper($src), 'userId' => $userId, 'info' => json_encode($activityInfo));
        $this->logger->info("User {userId} - {src}: ".$message." More Info: {info}\n\n", $params);
    }

    /**
     * This method cleans the given parameters to prepare it for logging
     *
     * @param array $params
     *
     * @return
     */
    public function cleanParams(&$params)
    {
        $filters = array(
            'ccnumber' => array(
                array('creditCard', 'number'),
                array('criteria', 'creditCard', 'number'),
                array('criteria', 'details', 'cardNumber'),
            ),
            'cvc' => array(
                array('creditCard', 'securityCode'),
                array('criteria', 'creditCard', 'securityCode'),
                array('criteria', 'details', 'securityId'),
            )
        );

        foreach ($filters as $filterType => $filter) {
            foreach ($filter as $keys) {
                $item  = &$params;
                $match = false;

                foreach ($keys as $key) {
                    if (isset($item[$key])) {
                        $item  = &$item[$key];
                        $match = true;
                    } else {
                        $match = false;
                        break;
                    }
                }

                if ($match && !empty($item)) {
                    switch ($filterType) {
                        case 'ccnumber':
                            $item = str_repeat('*', strlen($item) - 4).substr($item, -4);
                            break;
                        case 'cvc':
                            $item = str_repeat('*', strlen($item));
                            break;
                    }
                }
            }
        }

        // if (!empty($params['creditCard']['number'])) {
        //     $params['creditCard']['number'] = str_repeat('*', strlen($params['creditCard']['number']) - 4).substr($params['creditCard']['number'], -4);
        //     if (isset($params['creditCard']['securityCode'])) {
        //         $params['creditCard']['securityCode'] = str_repeat('*', strlen($params['creditCard']['securityCode']));
        //     }
        // } elseif (!empty($params['criteria'])) {
        //     $this->cleanParams($params['criteria']);
        // }
    }

    /**
     * This method adds error log
     *
     * @param string $fileName The log file's name
     * @param array $error
     *
     * @return
     */
    public function error($fileName, $error)
    {
        $error = json_decode(json_encode($error), true);

        $this->add($fileName, 'error', $error['message']);
    }

    /**
     * This method adds info log
     *
     * @param string $fileName The log file's name
     * @param string $name
     * @param string $msg
     * @param boolean $cleanXmlMessage
     *
     * @return
     */
    public function info($fileName, $name, $msg, $cleanXmlMessage = false)
    {
        if ($cleanXmlMessage) {
            $dom = new \DOMDocument();
            $dom->loadXml($msg);

            if (!empty($dom->getElementsByTagName('cardHolder')->item(0))) {
                $dom->getElementsByTagName('cardHolder')->item(0)->nodeValue   = str_repeat('*', strlen($dom->getElementsByTagName('cardHolder')->item(0)->nodeValue));
                $dom->getElementsByTagName('number')->item(0)->nodeValue       = str_repeat('*', strlen($dom->getElementsByTagName('number')->item(0)->nodeValue));
                $dom->getElementsByTagName('organisation')->item(0)->nodeValue = str_repeat('*', strlen($dom->getElementsByTagName('organisation')->item(0)->nodeValue));
                $dom->getElementsByTagName('valid')->item(0)->nodeValue        = str_repeat('*', strlen($dom->getElementsByTagName('valid')->item(0)->nodeValue));
                $dom->getElementsByTagName('securityCode')->item(0)->nodeValue = str_repeat('*', strlen($dom->getElementsByTagName('securityCode')->item(0)->nodeValue));
            }
            $msg = $dom->saveXML($dom->documentElement);
        }

        $this->add($fileName, $name, $msg);
    }

    /**
     * This method prepares and returns the log file to write to
     *
     * @param string $src
     * @param string $logName The name of the file.
     * @param array $params
     *
     * @return string the full path of the log file
     */
    public function getLogFile($src, $logName, $params)
    {
        $hotelId = (isset($params['hotelId']) && !empty($params['hotelId'])) ? $params['hotelId'] : 0;
        $userId  = (isset($params['userId']) && !empty($params['userId'])) ? $params['userId'] : 0;
        if (isset($params['compileSessionLog'])) {
            $dir     = $this->container->get('kernel')->getRootDir()."/logs/hotels/".strtolower($src)."/";
            $pattern = sprintf("%s_%s_%s_%s.log", '*', $hotelId, $userId, $logName);
            $files   = $this->container->get("TTFileUtils")->globFiles($dir, $pattern, GLOB_BRACE);

            if (!empty($files)) {
                $filename = reset($files);
                return $filename;
            }
        }
        $filename = sprintf("%s_%s_%s_%s.log", $params['timeStart'], $hotelId, $userId, $logName);

        if (isset($params['group_dir'])) {
            $filename = $params['group_dir']."/{$filename}";
        }

        return $this->container->get('kernel')->getRootDir()."/logs/hotels/".strtolower($src)."/".$filename;
    }
}
