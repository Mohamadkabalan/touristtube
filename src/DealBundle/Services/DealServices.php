<?php
/*
 * Services to connect to CityDiscovery Web Service
 * Version 1.1.3
 *
 * @author: Anna Lou H. Parejo <anna.parejo@touristtube.com>
 *
 */

namespace DealBundle\Services;

use TTBundle\Utils\Utils;
use Symfony\Component\HttpFoundation\Response;
use DealBundle\Entity\DealDetails;
use DealBundle\Entity\DealCancelPolicies;
use DealBundle\Entity\DealDetailsTmp;
use DealBundle\Entity\DealCountry;
use DealBundle\Entity\DealCity;
use DealBundle\Entity\DealImage;
use DealBundle\Entity\DealTemp;
use DealBundle\Entity\DealCategory;
use DealBundle\Entity\DealDetailToCategory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DealBundle\Entity\DealTextsTemp;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Services\EmailServices;
use TTBundle\Services\PayFortServices;
use TTBundle\Services\UserServices;
use PaymentBundle\Model\Payment as PaymentObj;
use PaymentBundle\Services\impl\PaymentServiceImpl;
use TTBundle\Services\CurrencyService;
use CorporateBundle\Services\Admin\CorpoApprovalFlowServices;
use CorporateBundle\Services\Admin\CorpoAccountServices;
use CorporateBundle\Services\Admin\CorpoAccountTransactionsServices;
use DealBundle\vendors\citydiscovery\v1\CityDiscoveryHandler;
use DealBundle\Model\DealSC;
use DealBundle\Model\DealCancellationPolicy;
use DealBundle\Model\DealImages;
use DealBundle\Model\DealPriceOption;
use DealBundle\Model\DealQuote;
use DealBundle\Model\DealUnit;
use DealBundle\Model\DealBookingResponse;
use DealBundle\Model\DealMandatoryFields;
use DealBundle\Model\DealTransferVehiclesListing;
use DealBundle\Model\DealTransferAirportListing;
use DealBundle\Model\DealTransferBooking;
use Symfony\Component\HttpFoundation\Request;

if (!defined('RESPONSE_SUCCESS')) define('RESPONSE_SUCCESS', 0);

if (!defined('RESPONSE_ERROR')) define('RESPONSE_ERROR', 1);

class DealServices
{
    protected $utils;
    protected $em;
    protected $emailServices;
    protected $payFortServices;
    protected $paymentServiceImpl;
    protected $cityDiscoveryHandler;
    protected $currencyService;
    protected $corpoApprovalFlowServices;
    protected $corpoAccountServices;
    protected $corpoAccountTransactionsServices;
    protected $userServices;
    private $logger;
    
    public function __construct(Utils $utils, EntityManager $em, $templating, EmailServices $emailservices, PayFortServices $payFortServices, LoggerInterface $logger, ContainerInterface $container,
        PaymentServiceImpl $paymentServiceImpl, CurrencyService $currencyService, CorpoApprovalFlowServices $corpoApprovalFlowServices,
        CorpoAccountServices $corpoAccountServices, UserServices $userServices, CorpoAccountTransactionsServices $corpoAccountTransactionsServices)
    {
        $this->utils                            = $utils;
        $this->em                               = $em;
        $this->emailServices                    = $emailservices;
        $this->templating                       = $templating;
        $this->payFortServices                  = $payFortServices;
        $this->logger                           = $logger;
        $this->container                        = $container;
        $this->paymentServiceImpl               = $paymentServiceImpl;
        $this->currencyService                  = $currencyService;
        $this->corpoApprovalFlowServices        = $corpoApprovalFlowServices;
        $this->corpoAccountServices             = $corpoAccountServices;
        $this->corpoAccountTransactionsServices = $corpoAccountTransactionsServices;
        $this->userServices                     = $userServices;
        $this->translator                       = $this->container->get('translator');
        $this->cityDiscoveryHandler             = new CityDiscoveryHandler($utils, $em, $templating, $emailservices, $payFortServices, $logger, $container, $currencyService);
        $this->request                          = Request::createFromGlobals();
    }
    /*
     * This method send a ActivityDetails request to citydiscovery.
     * Then it will format the result base on how we handle it in deal_details template.
     *
     * @param $activityId
     *
     * @return array of deal details information.
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getDetails($activityId, $langCode = 'en', $currency = 'USD')
    {
        $translator     = $this->container->get('translator');
        $getDetails     = $this->cityDiscoveryHandler->getDetails($activityId);
        $results        = $getDetails->toArray();
        $conversionRate = $this->currencyService->getConversionRate('USD', $currency);
        
        //getting the translation for dealDescription, dealName, dealHighlights
        $packageEncoded = $this->getPackageByDealCode($activityId);
        $packageDecoded = json_decode($packageEncoded, true);
        $packageResult  = $packageDecoded['data'];
        
        if ($packageDecoded['success']) {
            $results['dealDescription'] = (isset($packageResult['descriptionTrans']) && $packageResult['descriptionTrans']) ?
            nl2br($packageResult['descriptionTrans']) : nl2br($results['dealDescription']);
            $results['dealName']        = (isset($packageResult['dealNameTrans']) && $packageResult['dealNameTrans']) ?
            html_entity_decode($packageResult['dealNameTrans']) : html_entity_decode($results['dealName']);
            $results['dealHighlights']  = (isset($packageResult['dealHighlightsTrans']) && $packageResult['dealHighlightsTrans']) ?
            $packageResult['dealHighlightsTrans'] : html_entity_decode($results['dealHighlights']);
            
            // Check for Cancellation Policy
            if ($cancelPolicyData = $getDetails->getCancellationPolicy()) {
                $cpData = array();
                foreach ($cancelPolicyData as $cpKey => $cpVal) {
                    $cpData[] = $cpVal->toArray();
                }
                $results['cancellationData'] = json_encode($cpData);
                $cancellationPolicyObj       = $this->getDealCancellationPolicy($cpData);
                $cancellationPolicyEncoded   = $this->parseCancellationPolicy($cancellationPolicyObj);
                $decoded_cancellationPolicy  = json_decode($cancellationPolicyEncoded, true);
                $cancellationPolicy          = $decoded_cancellationPolicy;
                
                $results = array_merge($results, $cancellationPolicy['data']);
            }
            
            //@TODO - Temp fix for starting place cause havent found evidence yet that a multiple startingplace tags are possible.
            //All I encountered were 1 block only but there format I believe is intended for mutliple.
            $results['startingPlace'] = '';
            if ($startingPlaceData        = $getDetails->getStartingPlace()) {
                foreach ($startingPlaceData as $spKey => $spVal) {
                    $results['startingPlace'] = $spVal->getAddress();
                    $results['latitude']      = $spVal->getLat();
                    $results['longitude']     = $spVal->getLong();
                }
            }
            
            if ($notesData = $getDetails->getNotes()) {
                $nData = array();
                foreach ($notesData as $nKey => $nVal) {
                    $nData[$nVal->getGroupId()][] = array('title' => $nVal->getTitle(),
                        'info' => $nVal->getInfo(),
                        'note' => $nVal->getNote(),
                        'pdfPath' => $nVal->getPdfPath());
                }
                $results['notes'] = $nData;
            }
            
            if ($faqData = $getDetails->getFaq()) {
                $fData = array();
                foreach ($faqData as $fKey => $fVal) {
                    $fData[] = array('question' => $fVal->getQuestion(),
                        'answer' => $fVal->getAnswer()
                    );
                }
                $results['faq'] = $fData;
            }
            
            if ($schedulesData = $getDetails->getDealSchedules()) {
                $sData = array();
                foreach ($schedulesData as $sKey => $sVal) {
                    $sData[$sVal->getGroupId()][] = array('title' => $sVal->getTitle(),
                        'time' => $sVal->getTime(),
                        'description' => $sVal->getDescription(),
                        'order' => $sVal->getOrder());
                }
                $results['dealSchedules'] = $sData;
            }
            
            if ($directionData = $getDetails->getDirections()) {
                $dData = array();
                foreach ($directionData as $dKey => $dVal) {
                    $dData[] = $dVal->toArray();
                }
                $results['directions'] = $dData;
            }
            
            //Build PriceOptions
            if ($results['priceOptions']) {
                $priceOptionsObj   = $this->getDealPriceOptionCriteria(array('priceOptions' => $results['priceOptions']));
                $priceOptionsArray = $this->buildPriceOptionsArray($priceOptionsObj, $currency);
                $results           = array_merge($results, $priceOptionsArray);
            }
            
            $specifications                 = array();
            $specifications['type']         = ucfirst($packageResult['dt_category']).' '.$translator->trans('in').' '.ucfirst($packageResult['dc_cityName']);
            $specifications['departsFrom']  = ucfirst($packageResult['dc_cityName']);
            $specifications['duration']     = $results['dealDuration'];
            $specifications['productCode']  = $results['productCode'];
            $specifications['meetingPoint'] = $results['startingPlace'];
            $specifications['voucherinfo']  = $translator->trans('Paper voucher printout not required for this activity. You may show e-voucher from your mobile device.');
            $specifications['language']     = $langCode;
            $specifications['languageFlag'] = $this->container->get("TTRouteUtils")->generateMediaURL('media/images/deals-attractive/enflag_dat.png');
            $results['specifications']      = $specifications;
            
            $reviewsEncoded     = $this->getActivityReviews($activityId);
            $decoded_reviews    = json_decode($reviewsEncoded, true);
            $results['reviews'] = $decoded_reviews['data'];
            
            $dealDetailObj = $this->em->getRepository('DealBundle:DealDetails')->findOneBy([
                'dealCode' => $activityId,
                'published' => 1,
            ]);
            
            if ($dealDetailObj) {
                $dealDetailId = $dealDetailObj->getId();
                
                $dealImagesEncoded          = $this->getDealImages($dealDetailId);
                $dealImages                 = json_decode($dealImagesEncoded, true);
                $results['dealMainImages']  = $dealImages['data']['mainImages'];
                $results['dealThumbImages'] = $dealImages['data']['thumbImages'];
                
                $topDealsEncoded     = $this->getTopDeals($dealDetailId, $langCode, $currency);
                $topDeals            = json_decode($topDealsEncoded, true);
                $results['topDeals'] = $topDeals['data'];
            } else {
                // to generate error
                $results = array();
            }
        } else {
            $results = array();
        }
        
        
        return $this->createJsonResponse($results);
    }
    /*
     * This method build Price Options
     *
     * @param $options: objects
     *
     * @return array of deal details information.
     */
    
    private function buildPriceOptionsArray($priceOptionsObj, $currency = 'USD')
    {
        $conversionRate    = $this->currencyService->getConversionRate('USD', $currency);
        $return            = array();
        $priceOptionsArray = array();
        $getLowestPrice    = array();
        $days              = array();
        
        if ($priceOptionsObj->getPriceOptions()) {
            foreach ($priceOptionsObj->getPriceOptions() as $key => $val) {
                $optionDateBegins  = $val->getOptionDateBegins();
                $optionDateEnd     = $val->getOptionDateEnd();
                $availableDaysText = $optionDateBegins.' to '.$optionDateEnd;
                
                $daysArray = $val->getPriceDays();
                if (!in_array('False', $daysArray[0], true)) {
                    $availableDaysText .= ' (Daily)';
                } else {
                    $availableDaysText .= ' ('.implode(", ", array_keys($daysArray[0])).')';
                }
                
                // build array
                $tmpArr1 = array('activityPriceId' => $val->getActivityPriceId(),
                    'priceId' => $val->getPriceId(),
                    'optionLabel' => $val->getOptionLabel(),
                    'inclusions' => $val->getInclusions(),
                    'optionDepartureTime' => '',
                    'optionDateBegins' => $optionDateBegins,
                    'optionDateEnd' => $optionDateEnd,
                    'optionGroupMinPax' => '',
                    'optionGroupMaxPax' => '',
                    'optionAvailableDays' => $availableDaysText
                );
                
                foreach ($val->getSchedules() as $schedKey => $schedVal) {
                    $tmpSchedules = array();
                    foreach ($schedVal as $schedKey2 => $schedVal2) {
                        $tmpSchedules[] = array(
                            'order' => $schedVal2->getOrder(),
                            'title' => $schedVal2->getTitle(),
                            'time' => $schedVal2->getTime(),
                            'description' => $schedVal2->getDescription(),
                            'groupId' => $schedVal2->getGroupId()
                        );
                    }
                    $tmpArr1['Schedules'][] = $tmpSchedules;
                }
                
                foreach ($val->getUnits() as $unitKey => $unitVal) {
                    $minimumAge           = (int) $unitVal->getMinimum();
                    $maximumAge           = (int) $unitVal->getMaximum();
                    $newConvertedPrice    = $this->currencyService->currencyConvert($unitVal->getChargePrice(), $conversionRate);
                    $newConvertedPriceNet = $this->currencyService->currencyConvert($unitVal->getNetPrice(), $conversionRate);
                    
                    $tmpArr1['Units'][] = array(
                        'unitId' => $unitVal->getUnitId(),
                        'unitLabel' => $unitVal->getLabel(),
                        'minimumAge' => $minimumAge,
                        'maximumAge' => $maximumAge,
                        'ageText' => (($minimumAge == 0 && $maximumAge > 99) ? 'All Ages' : $minimumAge.'-'.$maximumAge),
                        'optionPrice' => $newConvertedPrice,
                        'optionPriceNet' => $newConvertedPriceNet,
                        'optionPriceFormatted' => number_format($newConvertedPrice, 2, '.', ','),
                        'optionPriceNetFormatted' => number_format($newConvertedPriceNet, 2, '.', ','),
                        'optionPriceCurrency' => $currency
                    );
                    
                    // save each value of adult
                    if (0 === $unitKey) {
                        $getLowestPrice[] = $newConvertedPrice;
                    }
                }
                
                $priceOptionsArray[$optionDateBegins.'_'.$optionDateEnd][] = $tmpArr1;
                unset($tmpArr1);
            }
        }
        
        $return['availabiltyDays']    = $days;
        $return['dealPrice']          = min($getLowestPrice);
        $return['formattedDealPrice'] = number_format($return['dealPrice'], 2, '.', ',');
        $return['dealCurrency']       = $currency;
        $return['priceOptions']       = $priceOptionsArray;
        return $return;
    }
    /*
     * Function that gets top deals using dealDetailId
     *
     * @param $dealDetailId
     * @param $langCode
     *
     * @return array of top deals
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */
    
    public function getTopDeals($dealDetailId, $langCode = 'en', $toCurrency = 'USD')
    {
        $repo   = $this->em->getRepository('DealBundle:DealDetails');
        $result = $repo->getPackageById($dealDetailId, 'en');
        
        //static of USD cause in db we only have USD
        $conversionRate = $this->currencyService->getConversionRate('USD', $toCurrency);
        $topDealsRepo   = $repo->getTopDeals($dealDetailId, $result['dd_dealCityId'], $result['dd_countryId'], $langCode);
        $topDealsArr    = array();
        if ($topDealsRepo) {
            foreach ($topDealsRepo as $topDeal) {
                $newConvertedPrice    = $this->currencyService->currencyConvert($topDeal['price'], $conversionRate);
                $newPBPConvertedPrice = $this->currencyService->currencyConvert($topDeal['priceBeforePromo'], $conversionRate);
                
                $topDeal['currency']                  = $toCurrency;
                $topDeal['price']                     = number_format($newConvertedPrice, 2, '.', ',');
                $topDeal['priceBeforePromo']          = $newPBPConvertedPrice;
                $topDeal['formattedPriceBeforePromo'] = number_format($newPBPConvertedPrice, 2, '.', ',');
                $topDeal['durationText']              = $this->utils->convertMinToDaysHrsMin($topDeal['duration']);
                $topDeal['imagePath']                 = $this->getDealDefaultImage($topDeal['packageId']);
                $topDeal['urlPath']                   = $this->container->get('TTRouteUtils')->returnDealDetailsLink($topDeal['packageId'], $topDeal['dealName'], $topDeal['cityName'], $topDeal['dealType'], $langCode);
                $topDealsArr[]                        = $topDeal;
            }
        }
        
        return $this->createJsonResponse($topDealsArr);
    }
    /*
     * Function that gets the images of a specific deal using deal_details_id
     *
     * @param $dealDetailId
     *
     * @return array of images paths
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getDealImages($dealDetailId)
    {
        // get the deal_images objects for this specific deal by its id
        $result         = $this->em->getRepository('DealBundle:DealDetails')->getPackageById($dealDetailId, 'en');
        $dealImagesObjs = $this->em->getRepository('DealBundle:DealImage')->findByDealDetailId($result['dd_id']);
        $dir            = $this->container->getParameter('CONFIG_SERVER_ROOT');
        
        $return = array();
        if (isset($dealImagesObjs) && !empty($dealImagesObjs)) {
            foreach ($dealImagesObjs as $dealImageObj) {
                $params = array('directory' => $dir,
                    'apiId' => $result['dd_dealApiId'],
                    'dealCode' => $result['dd_dealCode'],
                    'categoryName' => $result['dcat_name'],
                    'cityName' => $result['dc_cityName'],
                    'imageId' => $dealImageObj->getId(),
                    'isThumbnail' => true
                );
                
                $thumbImgObj  = $this->getDealImageCriteria($params);
                $thumbImgPath = $this->returnDealsImageLink($thumbImgObj);
                if ($thumbImgPath) {
                    $return['thumbImages'][] = $thumbImgPath;
                }
                
                $params['imgWidth']  = 764;
                $params['imgHeight'] = 500;
                $bigImgObj           = $this->getDealImageCriteria($params);
                $bigImgPath          = $this->returnDealsImageLink($bigImgObj);
                if ($bigImgPath) {
                    $return['mainImages'][] = $bigImgPath;
                }
            }
        }
        
        //no image is being uploaded in the server
        if (empty($return)) {
            $return['mainImages'][]  = $this->container->get("TTRouteUtils")->generateMediaURL("/media/images/picex1.png");
            $return['thumbImages'][] = $this->container->get("TTRouteUtils")->generateMediaURL("/media/images/picex1.png");
        }
        return $this->createJsonResponse($return);
    }
    /*
     * Function in getting cancellation policy
     *
     * @param $cancelObj - Cancellation Policy Objects
     *
     * @return array of cancellation policy
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function parseCancellationPolicy($cancelObj)
    {
        $translator = $this->container->get('translator');
        $results    = array();
        $length     = count($cancelObj);
        $cnt        = 0;
        
        foreach ($cancelObj as $key => $val) {
            $cancellationDay      = (int) $val->getCancellationDay();
            $cancellationDiscount = (int) $val->getCancellationDiscount();
            $cnt ++;
            
            if ($cancellationDiscount > 0) {
                if ($cnt < $length) {
                    $betweenDays = ((int) $cancelObj[$key + 1]->getCancellationDay()) - 1;
                    $policy      = $cancellationDiscount.'% '.$translator->trans('Cancellation Fee').' ';
                    if ($cancellationDiscount != 100) {
                        $refund = 100 - $cancellationDiscount;
                        $policy .= '('.$refund.'% reimbursement): ';
                        $policy .= ($cancellationDay != $betweenDays) ? $translator->trans('between').' '.$cancellationDay.' '.$translator->trans('to').' '.$betweenDays : $cancellationDay;
                        $policy .= " ".$translator->trans('day(s) prior to date of activity.');
                    } else {
                        $policy .= " ".$translator->trans('(no reimbursement):').' '.$betweenDays.' '.$translator->trans(' day(s) or less prior to date of activity.');
                    }
                    $results['cancellationPolicy'][] = $policy;
                } else {
                    $policy = '';
                    if ($cancellationDay > 0) {
                        $policy .= ': '.$cancellationDay.$translator->trans(' days or less prior to date of activity.');
                    }
                    $results['cancellationPolicy'][] = $cancellationDiscount.'% '.$translator->trans('Cancellation Fee (no reimbursement)').$policy;
                }
            } else {
                $results['cancellationPolicy'][] = $translator->trans('Free cancellation:').' '.$cancellationDay.' '.$translator->trans(' days or more prior to start date of activity.');
            }
        }
        
        return $this->createJsonResponse($results);
    }
    /*
     * This method sends a PriceDetails request to cityDiscory for a specific tourcode
     *
     * @param $priceOptionObj
     *
     * @return array of price details
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getPriceDetails($priceOptionObj)
    {
        $results      = array();
        $priceDetails = $this->cityDiscoveryHandler->getPriceDetails($priceOptionObj);
        
        if (is_array($priceDetails) && isset($priceDetails["errorAPIMessage"])) {
            $results['errorCode']    = '404';
            $results['errorMessage'] = $priceDetails["errorAPIMessage"];
        } elseif ($priceDetails->getErrorMessage()) {
            $results['errorCode']    = $priceDetails->getErrorCode();
            $results['errorMessage'] = $priceDetails->getErrorMessage();
        } else {
            $priceOptionObj->setPriceOptions($priceDetails->getPriceOptions());
            $priceOptionObj->getCommonSC()->getPackage()->setName($priceDetails->getDealName());
            $results = $this->buildPriceArrayForBookingBox($priceOptionObj);
        }
        
        return $this->createJsonResponse($results);
    }
    /*
     * Building Price options for Tour box
     *
     * @param $priceOptionObj
     *
     * @return array of price details
     */
    
    private function buildPriceArrayForBookingBox($priceOptionObj)
    {
        $return            = array();
        $getLowestPrice    = array();
        $priceOptionsArray = array();
        $bookingDate       = $priceOptionObj->getCommonSC()->getPackage()->getStartDate();
        $currency          = ($priceOptionObj->getCommonSC()->getPackage()->getCurrency()) ? $priceOptionObj->getCommonSC()->getPackage()->getCurrency() : 'USD';
        $conversionRate    = $this->currencyService->getConversionRate('USD', $currency);
        
        if ($priceOptionObj->getPriceOptions()) {
            foreach ($priceOptionObj->getPriceOptions() as $key => $val) {
                $optionDateBegins = $val->getOptionDateBegins();
                $optionDateEnd    = $val->getOptionDateEnd();
                
                if ($bookingDate >= $optionDateBegins && $bookingDate <= $optionDateEnd) {
                    $tmpArr1 = array('activityPriceId' => $val->getactivityPriceId(),
                        'priceId' => $val->getPriceId(),
                        'optionLabel' => ($val->getOptionLabel() != '' ? $val->getOptionLabel() : $priceOptionObj->getCommonSC()->getPackage()->getName()),
                        'optionPriceCurrency' => 'USD'
                    );
                    
                    foreach ($val->getUnits() as $unitKey => $unitVal) {
                        $minimumAge           = (int) $unitVal->getMinimum();
                        $maximumAge           = (int) $unitVal->getmaximum();
                        $newConvertedPrice    = $this->currencyService->currencyConvert($unitVal->getChargePrice(), $conversionRate);
                        $newConvertedPriceNet = $this->currencyService->currencyConvert($unitVal->getNetPrice(), $conversionRate);
                        $label                = $unitVal->getLabel();
                        $price                = number_format($newConvertedPrice, 2, '.', ',');
                        
                        if ($minimumAge == 0 && $maximumAge > 99) {
                            $ageText = 'All Ages';
                        } elseif ($minimumAge > 0 && $maximumAge > 99) {
                            $ageText = $minimumAge.'+';
                        } else {
                            $ageText = $minimumAge.'-'.$maximumAge;
                        }
                        
                        $tmpArr1['Units'][] = array(
                            'unitId' => $unitVal->getUnitId(),
                            'unitLabel' => $label,
                            'ageText' => $ageText,
                            'optionPrice' => $price,
                            'optionPriceNet' => number_format($newConvertedPriceNet, 2, '.', ','),
                            'requiredOtherUnits' => $unitVal->getRequiredOtherUnits()
                        );
                        
                        // save each value of adult
                        if (0 === $unitKey) {
                            $getLowestPrice[] = $unitVal->getChargePrice();
                        }
                    }
                    
                    $priceOptionsArray[] = $tmpArr1;
                }
            }
        }
        
        //There are scenarios which CD returns an old date causing some issues
        if (!$priceOptionsArray) {
            $return = array('errorCode' => '404', 'errorMessage' => 'Tour is not available on this date.');
            return $return;
        }
        
        $lowestPrice                  = min($getLowestPrice);
        $lowestPriceConverted         = $this->currencyService->currencyConvert($lowestPrice, $conversionRate);
        $return['formattedDealPrice'] = number_format($lowestPriceConverted, 2, '.', ',');
        $return['convertedDealPrice'] = $lowestPriceConverted;
        $return['dealPrice']          = $lowestPrice;
        $return['dealCurrency']       = $currency;
        $return['ActivityPriceId']    = $priceOptionsArray;
        
        return $return;
    }
    /*
     * This functions will get a quotation
     *
     * @param $quoteObj
     *
     * @return results of quotation
     */
    
    public function getQuotation($quoteObj)
    {
        $results = array();
        $quote   = $this->cityDiscoveryHandler->getQuotation($quoteObj);
        
        $currency       = $quoteObj->getCommonSC()->getPackage()->getCurrency();
        $conversionRate = $this->currencyService->getConversionRate('USD', $currency);
        
        if ((is_array($quote) && isset($quote["errorAPIMessage"]))) {
            $results['errorCode']    = '404';
            $results['errorMessage'] = $quote["errorAPIMessage"];
        } elseif ($quote->getErrorMessage()) {
            $results['errorCode']    = $quote->getErrorCode();
            $results['errorMessage'] = $quote->getErrorMessage();
        } else {
            $quoteObj->setQuote($quote->getQuote());
            $quoteObj->setMandatoryFields($quote->getMandatoryFields());
            
            $results                   = $this->em->getRepository('DealBundle:DealBookingQuote')->saveBookingQuote($quoteObj);
            $newConvertedPrice         = $this->currencyService->currencyConvert($results['total'], $conversionRate);
            $results['totalFormatted'] = number_format($newConvertedPrice, 2, '.', ',');
            $results['totalConverted'] = $newConvertedPrice;
            $results['currency']       = $currency;
        }
        
        return $this->createJsonResponse($results);
    }
    /*
     * This functions will give you the Reviews/Ratings of a specific activity.
     * Used at the Tour Reviews section of tourDetails.
     *
     * @param $activityId
     * @param $order Order on how reviews are sorted
     *
     * @return array of customer reviews
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getActivityReviews($activityId = 0, $order = 'rating_desc')
    {
        $review = $this->cityDiscoveryHandler->getActivityReviews($activityId, $order);
        if (empty($review) || !$review) {
            return $results;
        }
        
        switch ($order) {
            case "rating_asc":
                $sortedColumn = 'rating';
                $sortedOrder  = SORT_ASC;
                break;
            case "date_desc":
                $sortedColumn = 'date';
                $sortedOrder  = SORT_DESC;
                break;
            case "date_asc":
                $sortedColumn = 'date';
                $sortedOrder  = SORT_ASC;
                break;
            default:
                $sortedColumn = 'rating';
                $sortedOrder  = SORT_DESC;
                break;
        }
        
        //getting all country codes and country names
        $query            = $this->em->createQueryBuilder();
        $dealCountryCodes = $query->select('c.countryCode, c.countryName')
        ->from('DealBundle:DealCountry', 'c')->getQuery();
        $dealCountryList  = $dealCountryCodes->getResult();
        
        $countryList = array();
        if (isset($dealCountryList) && !empty($dealCountryList)) {
            foreach ($dealCountryList as $dealCountry) {
                $countryList[$dealCountry['countryCode']] = $dealCountry['countryName'];
            }
        }
        
        $sorted  = $results = array();
        foreach ($review->getReviews() as $key => $value) {
            $currrentCountry                = $value->getCountry();
            $country                        = isset($countryList[$currrentCountry]) ? $countryList[$currrentCountry] : $currrentCountry;
            $results[$value->getReviewId()] = [
                'comment' => $value->getComment(),
                'rating' => $value->getRating(),
                'country' => $country,
                'owner' => $value->getOwner(),
                'date' => date("m/d/Y", strtotime($value->getDate()))
            ];
            
            switch ($sortedColumn) {
                case 'date':
                    $sorted[] = $value->getDate();
                    break;
                default:
                    $sorted[] = $value->getRating();
            }
        }
        
        array_multisort($sorted, $sortedOrder, $results);
        return $this->createJsonResponse($results);
    }
    /*
     * Used in transport to retrieve the list of Countries.
     *
     * @return array of countries
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getTransferCountryListing()
    {
        $getCountries = $this->cityDiscoveryHandler->getTransferCountryListing();
        $result       = array();
        foreach ($getCountries->getTransferCountries() as $cKey => $cVal) {
            $result[] = $cVal->toArray();
        }
        
        return $this->createJsonResponse($result);
    }
    /*
     * Used in transport to return the list of Cities depending on the Country.
     *
     * @param $country
     *
     * @return array of cities
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getTransferCityListingByCountry($country)
    {
        $getCities = $this->cityDiscoveryHandler->getTransferCityListingByCountry($country);
        $result    = array();
        
        if ($getCities->getTransferCities()) {
            foreach ($getCities->getTransferCities() as $cKey => $cVal) {
                $result['cityName'][] = $cVal->getName();
            }
            
            $result['count'] = count($result['cityName']);
            return $this->createJsonResponse($result);
        } else {
            return $this->createJsonResponse($result);
        }
    }
    /*
     * The getTransferVehicles Function retrieves the list of vehicles per country and city
     *
     * @param dealTransferObj
     *
     * @return array of vehicles
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getTransferVehicles($dealTransferObj)
    {
        $selectedCurrency = ($dealTransferObj->getSelectedCurrency()) ? $dealTransferObj->getSelectedCurrency() : 'USD';
        $conversionRate   = $this->currencyService->getConversionRate('USD', $selectedCurrency);
        
        $getDealResponseObj = $this->cityDiscoveryHandler->getTransferVehicles($dealTransferObj);
        $result             = array();
        
        foreach ($getDealResponseObj->getTransferVehicles() as $vKey => $vVal) {
            $tmpArr                               = array();
            $tmpArr['country']                    = $vVal->getCountry();
            $tmpArr['serviceType']                = $vVal->getServiceType();
            $tmpArr['numOfPersons']               = $vVal->getNumOfPersons();
            $tmpArr['transferVehicle']            = $vVal->getTransferVehicle();
            $tmpArr['transferMinimumHourBooking'] = $vVal->getTransferMinimumHourBooking();
            $tmpArr['transferPickupHour']         = $vVal->getTransferPickupHour();
            
            $getAirport                                     = $vVal->getAirport();
            $getDealArrivalDeparture                        = $vVal->getDealArrivalDeparture();
            $getDealTransferAirportPrice                    = $vVal->getDealTransferAirportPrice();
            $airportToArray                                 = $getAirport[0]->toArray();
            $airportArr                                     = array('airportName' => $airportToArray['name'], 'airportCode' => $airportToArray['code']);
            $tmpArr                                         = array_merge($tmpArr, $airportArr, $getDealArrivalDeparture[0]->toArray(), $getDealTransferAirportPrice[0]->toArray());
            $tmpArr['convertedPriceTotal']                  = $this->currencyService->currencyConvert($tmpArr['priceTotal'], $conversionRate);
            $tmpArr['formattedPriceTotal']                  = number_format($tmpArr['convertedPriceTotal'], 2, '.', ',');
            $tmpArr['selectedCurrency']                     = $selectedCurrency;
            $result['TransferInformationItems']['Quotes'][] = $tmpArr;
        }
        
        $result['TransferInformationItems']['count'] = isset($result['TransferInformationItems']['Quotes']) ? count($result['TransferInformationItems']['Quotes']) : 0;
        return $this->createJsonResponse($result['TransferInformationItems']);
    }
    /*
     * Retrieves the list of Airport depending on the Country & City.
     *
     * @param $airportObj
     *
     * @return array of airports
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getTransferAirportListing($airportObj)
    {
        $getAirport = $this->cityDiscoveryHandler->getTransferAirportListing($airportObj);
        $result     = array();
        
        if ($getAirport->getTransferAirports()) {
            foreach ($getAirport->getTransferAirports() as $aKey => $aVal) {
                $result['Airports']['Airport'][] = $aVal->toArray();
            }
            
            $result['Airports']['count'] = count($result['Airports']['Airport']);
            if ($result['Airports']['count']) {
                $result['Airports']['firstRecord'] = $result['Airports']['Airport'][0];
            }
            
            return $this->createJsonResponse($result['Airports']);
        } else {
            return $this->createJsonResponse($result);
        }
    }
    /*
     * This method save process booking data to database
     *
     * @param $bookingObj of criteria
     *
     * @return dealBookingId
     * @author Anna Lou H Parejo <anna.parejo@touristtube.com>
     */
    
    public function saveBookingData($bookingObj)
    {
        if (!$bookingObj) {
            $result = false;
            return $this->createJsonResponse($result);
        }
        
        // This is a testing Tourist Tube booking reference to be sent in City Discovery Request <firas.boukarroum@touristtube.com>
        $bookingReference = md5(uniqid(time()));
        $dealTypeObj      = $this->em->getRepository('DealBundle:DealType')->findOneById($bookingObj->getCommonSC()->getPackage()->getTypeId());
        $dealTypeCategory = $dealTypeObj->getCategory();
        
        switch ($dealTypeCategory) {
            case 'transfers':
                $cityParams = array('countryCode' => $bookingObj->getCommonSC()->getCountry()->getCode(), 'cityName' => $bookingObj->getCommonSC()->getCity()->getName());
                $countryObj = $this->em->getRepository('DealBundle:DealCountry')->findOneByCountryCode($bookingObj->getCommonSC()->getCountry()->getCode());
                $bookingObj->getCommonSC()->getCountry()->setId($countryObj->getId());
                $bookingObj->getCommonSC()->getCity()->setId($this->em->getRepository('DealBundle:DealCity')->getCityIdByParams($cityParams));
                $bookingObj->getCommonSC()->getPackage()->setName('Transfers for '.$countryObj->getCountryName());
                $bookingObj->getCommonSC()->getPackage()->setDescription('Transfers for '.$bookingObj->getCommonSC()->getCity()->getName().', '.$countryObj->getCountryName());
                $bookingObj->setNumOfAdults($bookingObj->getNumOfpassengers());
                $bookingObj->setBookingTime($bookingObj->getArrivalDeparture()->getDepartureHour().':'.$bookingObj->getArrivalDeparture()->getDepartureMinute());
                break;
            default:
                $ddRepo     = $this->em->getRepository('DealBundle:DealDetails')->getPackageById($bookingObj->getCommonSC()->getPackage()->getId());
                
                $bookingObj->getCommonSC()->getPackage()->setName($ddRepo['dd_dealName']);
                $bookingObj->getCommonSC()->getPackage()->setDescription($ddRepo['dd_description']);
                $bookingObj->setStartTime($ddRepo['dd_startTime']);
                $bookingObj->setEndTime($ddRepo['dd_endTime']);
                $bookingObj->getCommonSC()->getCountry()->setId($ddRepo['dd_countryId']);
                $bookingObj->getCommonSC()->getCity()->setId($ddRepo['dd_dealCityId']);
                $bookingObj->getCommonSC()->getPackage()->setTypeId($ddRepo['dd_dealTypeId']);
                $bookingObj->getCommonSC()->getPackage()->setCurrency($ddRepo['dd_currency']);
                $bookingObj->setDuration($ddRepo['dd_duration']);
                $bookingObj->getCommonSC()->getPackage()->setCode($ddRepo['dd_dealCode']);
                $bookingObj->getCommonSC()->getPackage()->setApiId($ddRepo['dd_dealApiId']);
        }
        
        // This is a testing Tourist Tube booking reference to be sent in City Discovery Request <firas.boukarroum@touristtube.com>
        $address = $bookingObj->getAddress().', '.$bookingObj->getCommonSC()->getCountry()->getCode();
        $bookingObj->setAddress($address);
        $bookingObj->setBookingReference($bookingReference);
        $bookingObj->setBookingVoucherInformation('');
        $bookingObj->setBookingStatus('Pending');
        $bookingObj->setUuid($this->utils->GUID());
        $bookingObj->setCustomerfullName($bookingObj->getFirstName().' '.$bookingObj->getLastName());
        
        $bookingCurrency = $bookingObj->getCommonSC()->getPackage()->getCurrency();
        if ($bookingCurrency && $bookingObj->getTotalPrice()) {
            $amountFBC             = $this->currencyService->exchangeAmount($bookingObj->getTotalPrice(), $bookingCurrency, $this->container->getParameter('FBC_CODE'));
            $amountSBC             = $this->currencyService->exchangeAmount($bookingObj->getTotalPrice(), $bookingCurrency, $this->container->getParameter('SBC_CODE'));
            $accountCurrencyAmount = ($bookingObj->getPreferredCurrency()) ? $this->currencyService->exchangeAmount($bookingObj->getTotalPrice(), $bookingCurrency, $bookingObj->getPreferredCurrency())
            : '0.00000';
            if ($amountFBC) {
                $bookingObj->setAmountFBC($amountFBC);
            }
            if ($amountSBC) {
                $bookingObj->setAmountSBC($amountSBC);
            }
            if ($accountCurrencyAmount) {
                $bookingObj->setAccountCurrencyAmount($accountCurrencyAmount);
            }
        }
        
        $dbRepo = $this->em->getRepository('DealBundle:DealBooking')->saveBookingData($bookingObj);
        
        $bookingObj->setBookingId($dbRepo->getId());
        
        $param['dealBookingId'] = $dbRepo->getId();
        $dbpRepo                = $this->em->getRepository('DealBundle:DealBookingPassengers')->saveBookingPassengersData($bookingObj);
        
        switch ($dealTypeCategory) {
            case 'transfers':
                $dtbdRepo = $this->em->getRepository('DealBundle:DealTransferBookingDetails')->saveTransferBookingDetailsData($bookingObj);
                break;
            default:
                // for the scenario where we truncated deal_details, deal_details_itinerary tables
                $ddiRepo  = $this->em->getRepository('DealBundle:DealDetailsItinerary')->getItineraryByDetailsId($bookingObj->getCommonSC()->getPackage()->getId());
                if ($ddiRepo) {
                    $dddRepo = $this->em->getRepository('DealBundle:DealBookingDetails')->saveBookingDetailsData($bookingObj->getBookingId(), $ddiRepo, 'ddi_');
                }
        }
        
        if (isset($param['dealBookingId']) && !empty($param['dealBookingId'])) {
            $result = $param['dealBookingId'];
        } else {
            $result = false;
        }
        return $this->createJsonResponse($result);
    }
    /*
     * This method initialize payment and  save payment data to database
     *
     * @param $bookingObj
     *
     * @return payment object
     * @author Anna Lou H Parejo <anna.parejo@touristtube.com>
     */
    
    public function savePaymentData($bookingObj)
    {
        $customerfullName = $bookingObj->getFirstName().' '.$bookingObj->getLastName();
        $paymentObj       = new PaymentObj;
        
        $paymentObj->setAmount($bookingObj->getTotalPrice());
        $paymentObj->setDisplayOriginalAmount($bookingObj->getTotalPrice());
        $paymentObj->setDisplayedCurrency($bookingObj->getCommonSC()->getPackage()->getCurrency());
        $paymentObj->setCurrency($bookingObj->getCommonSC()->getPackage()->getCurrency());
        $paymentObj->setCustomerEmail($bookingObj->getBookingEmail());
        $paymentObj->setModuleTransactionId($bookingObj->getBookingId());
        $paymentObj->setCustomerFullName($customerfullName);
        $paymentObj->setCommand(PaymentObj::CMD_HOLD_PAYMENT);
        $paymentObj->setModuleName('deals');
        $paymentObj->setTrxTypeId(PaymentObj::TRX_TYPE_DEALS);
        $paymentObj->setPaymentType($bookingObj->getOnAccountCCType());
        $paymentObj->setCustomerIp($bookingObj->getCustomerIP());
        $paymentObj->setUserAgent($bookingObj->getUserAgent());
        
        return $paymentObj;
    }
    /*
     * This method updates the booking record with the payment id returned
     *
     * @param bookingId
     * @param paymentId
     *
     * @return array of booking details
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function updateBookingWithPayment($bookingId, $paymentId)
    {
        if (empty($bookingId) || empty($paymentId)) {
            return false;
        }
        
        $dbRepo = $this->em->getRepository('DealBundle:DealBooking')->updateBookingUUID($bookingId, $paymentId);
        return $dbRepo;
    }
    /*
     * This method process expired booking approval
     *
     * @param $bookingApprovalObj
     *
     * @return array of booking details
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */
    
    public function processExpiredBookingApproval($bookingApprovalObj)
    {
        if (!$bookingApprovalObj) return false;
        
        $reqSerParams = array(
            "requestStatus" => $this->container->getParameter('CORPO_APPROVAL_EXPIRED'),
            "id" => $bookingApprovalObj->getRequestServicesDetailsId(),
        );
        
        $crsResult = $this->corpoApprovalFlowServices->updatePendingRequestServices($reqSerParams);
        
        $urlParams            = array();
        $urlParams['type']    = 'all';
        $urlParams['success'] = false;
        $urlParams['message'] = $this->translator->trans('Activity is expired and not available anymore in the selected date');
        
        $return                   = array();
        $return['redirectParams'] = $urlParams;
        $return['redirectUrl']    = '_corporate_dealSearch';
        return $return;
    }
    /*
     * Process approve booking transaction for API RestBundle Call
     * @TODO: Anna, finalize this once approval and payment team made their changes
     * We need to work on the following:
     * 1. Payfort
     * 2. Centralized On account payment
     *
     * For now, I made dummy function so I can proceed with development
     *
     * @param $params array
     *
     * @return json response
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */
    
    public function approveTransaction($params = array())
    {
        if (empty($params)) return false;
        
        $result = array();
        if (isset($params['reservationId']) && !empty($params['reservationId'])) {
            $resultEncoded  = $this->checkAvailability($params['reservationId']);
            $decoded_result = json_decode($resultEncoded, true);
            
            if ($decoded_result['success']) {
                if ($this->corpoApprovalFlowServices->allowedToApproveForUser($params['userId'], $params['transactionUserId'], $params['accountId'])) {
                    $bookingEncoded    = $this->findBookingById($params['reservationId']);
                    $bookingDecoded    = json_decode($bookingEncoded, true);
                    $bookingDetailsObj = $bookingDecoded['data'];
                    
                    $params['userAgent']         = $this->request->headers->get('User-Agent');
                    $params['customerIP']        = $this->utils->getUserIP();
                    $params['preferredCurrency'] = $this->corpoAccountServices->getAccountPreferredCurrency($params['accountId']);
                    $params['currency']          = $bookingDetailsObj['currency'];
                    $params['email']             = $bookingDetailsObj['email'];
                    $params['firstName']         = $bookingDetailsObj['firstName'];
                    $params['lastName']          = $bookingDetailsObj['lastName'];
                    $params['totalPrice']        = $bookingDetailsObj['totalPrice'];
                    $params['dealBookingId']     = $params['reservationId'];
                    
                    $onAccountOrCC             = $this->corpoAccountServices->getCorpoAccountPaymentType($params['accountId']);
                    $onAccountCCType           = $onAccountOrCC['code'];
                    $params['onAccountCCType'] = $onAccountCCType;
                    $params['paymentType']     = $onAccountCCType;
                    
                    $paymentObj = $this->savePaymentData($params);
                    $payInit    = $this->paymentServiceImpl->initializePayment($paymentObj);
                    
                    $transactionId = $payInit->getTransactionId();
                    $callback_url  = $payInit->getCallBackUrl();
                    
                    if (isset($transactionId) && !empty($transactionId)) {
                        $updateBookingWithPayment = $this->updateBookingWithPayment($params['reservationId'], $transactionId);
                        $params['transactionId']  = $transactionId;
                        //@TODO: payfort will be handled differently. we will delete it once approval and payment team made their changes
                        if ($params['onAccountCCType'] == 'coa') {
                            $this->corpoAccountPaymentProcessing($params);
                        }
                        $params['bookingId']   = $params['reservationId'];
                        $bookingResults        = $this->processBooking($params);
                        $bookingResultsDecoded = json_decode($bookingResults, true);
                        
                        if ($bookingResultsDecoded['success']) {
                            $result['status']     = true;
                            $result['statusCode'] = 200;
                            $result['message']    = $this->translator->trans('Transaction is approved successfully.');
                        } else {
                            $result['status']     = false;
                            $result['statusCode'] = 433;
                            $result['message']    = $this->translator->trans('Booking has encountered an error!');
                        }
                        return $this->createJsonResponse($result);
                    }
                } else {
                    $result['status']     = false;
                    $result['statusCode'] = 433;
                    $result['message']    = $this->translator->trans('User is not allowed to approve this request');
                }
            } else {
                $result['status']     = false;
                $result['statusCode'] = 433;
                $result['message']    = $this->translator->trans('User is not allowed to approve this request');
            }
        } else {
            $requestStatus = $this->container->getParameter('CORPO_APPROVAL_EXPIRED');
            $reqSerParams  = array(
                "requestStatus" => $requestStatus,
                "id" => $params['requestServicesDetailsId'],
            );
            $crsResult     = $this->corpoApprovalFlowServices->updatePendingRequestServices($reqSerParams);
            
            $result['status']     = false;
            $result['statusCode'] = 434;
            $result['message']    = $this->translator->trans('The transaction is already expired.');
        }
        return $this->createJsonResponse($result);
    }
    /*
     * DUMMY FUNCTION
     * @TODO: Delete this function once approval team centralized this function
     * This method process booking approval for payment_type: coa
     * This is from corpoOnAccountAction at PaymentController
     *
     * @param $params array
     *
     * @return response
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */
    
    public function corpoAccountPaymentProcessing($params = array())
    {
        $userArray          = $this->userServices->getUserDetails(array('id' => $params['userId']));
        $userCorpoAccountId = $userArray[0]['cu_corpoAccountId'];
        
        $userInfo              = array();
        $userInfo['userId']    = $params['userId'];
        $userInfo['accountId'] = $userCorpoAccountId;
        
        $proccess = $this->paymentServiceImpl->processPayment($params['transactionId'], NULL);
        
        $paymentInfo = $this->paymentServiceImpl->getPaymentInformation($params['transactionId']);
        
        $parameters = array(
            'accountId' => $userInfo['accountId'],
            'userId' => $params['userId'],
            'paymentId' => $params['transactionId'],
            'moduleId' => $paymentInfo->getTrxTypeId(),
            'reservationId' => $paymentInfo->getModuleTransactionId(),
            'currencyCode' => $paymentInfo->getCurrency(),
            'amount' => $paymentInfo->getAmount()
        );
        
        $addAccountTrx = $this->corpoAccountTransactionsServices->addAccountTransactions($parameters);
        return $this->createJsonResponse($addAccountTrx);
    }
    /*
     * This method process pending booking approval
     *
     * @param $bookingObj
     *
     * @return array of booking details
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */
    
    public function processPendingBookingApproval($bookingObj)
    {
        if (!$bookingObj) return false;
        
        $bookingEncoded    = $this->findBookingById($bookingObj->getBookingId());
        $bookingDecoded    = json_decode($bookingEncoded, true);
        $bookingDetailsObj = $bookingDecoded['data'];
        
        $return = array();
        if ($bookingObj->getIsAvailable()) {
            if ($this->corpoApprovalFlowServices->allowedToApproveForUser($bookingObj->getUserId(), $bookingObj->getTransactionUserId(), $bookingObj->getAccountId())) {
                $requestStatus = $this->container->getParameter('CORPO_APPROVAL_APPROVED');
                $reqSerParams  = array(
                    "requestStatus" => $requestStatus,
                    "id" => $bookingObj->getRequestServicesDetailsId(),
                );
                
                $crsResult = $this->corpoApprovalFlowServices->updatePendingRequestServices($reqSerParams);
                
                $bookingObj->setCustomerIP($this->utils->getUserIP());
                $bookingObj->setPreferredCurrency($this->corpoAccountServices->getAccountPreferredCurrency($bookingObj->getAccountId()));
                $bookingObj->getCommonSC()->getPackage()->setCurrency($bookingDetailsObj['currency']);
                $bookingObj->setBookingEmail($bookingDetailsObj['email']);
                $bookingObj->setFirstName($bookingDetailsObj['firstName']);
                $bookingObj->setLastName($bookingDetailsObj['lastName']);
                $bookingObj->setTotalPrice($bookingDetailsObj['totalPrice']);
                
                $onAccountOrCC                     = $this->corpoAccountServices->getCorpoAccountPaymentType($bookingObj->getAccountId());
                $onAccountCCType                   = $onAccountOrCC['code'];
                $bookingDetails['onAccountCCType'] = $onAccountCCType;
                
                $bookingObj->setOnAccountCCType($onAccountOrCC['code']);
                $paymentObj = $this->savePaymentData($bookingObj); // getting payment object
                $payInit    = $this->paymentServiceImpl->initializePayment($paymentObj);
                
                $result["callback_url"] = $payInit->getCallBackUrl();
                $payment_id             = $payInit->getTransactionId();
                if (isset($payment_id) && !empty($payment_id)) {
                    $updateBookingWithPayment = $this->updateBookingWithPayment($bookingObj->getBookingId(), $payment_id);
                }
                $return["callback_url"]   = $result["callback_url"];
                $return["transaction_id"] = $payment_id;
                return $return;
            } else {
                $errorMsg                 = $this->translator->trans('You are not allowed to approve this request');
                $return['redirectParams'] = array('bookingId' => $bookingObj->getBookingId(), 'success' => false, 'message' => $errorMsg);
                $return['redirectUrl']    = '_corporate_deals_booking_details';
                
                return $return;
            }
        } else {
            $requestStatus = $this->container->getParameter('CORPO_APPROVAL_EXPIRED');
            $reqSerParams  = array(
                "requestStatus" => $requestStatus,
                "id" => $bookingObj->getRequestServicesDetailsId(),
            );
            $crsResult     = $this->corpoApprovalFlowServices->updatePendingRequestServices($reqSerParams);
            
            $urlParams         = array();
            $urlParams['type'] = 'all';
            
            //get city param
            $cityDecoded = $this->getDealCityById($bookingDetailsObj['dealCityId']);
            $cityDecoded = json_decode($cityDecoded, true);
            $cityDetails = $cityDecoded['data'];
            
            if ($cityDetails) {
                $urlParams['city'] = $cityDetails['cityName'];
            }
            
            $urlParams['success'] = false;
            $urlParams['message'] = $this->translator->trans('Activity is expired and not available anymore in the selected date');
            
            $return['redirectParams'] = $urlParams;
            $return['redirectUrl']    = '_corporate_dealSearch';
            
            return $return;
        }
        return $return;
    }
    /*
     * This method sends a ActivityBooking request to cityDiscovery which is used to book and activity.
     *
     * @param $bookingObj
     *
     * @return array of booking details
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function processBooking($bookingObj)
    {
        if (!$bookingObj) return false;
        $params['bookingObj'] = $this->em->getRepository('DealBundle:DealBooking')->findOneById($bookingObj->getBookingId());
        $params['quoteObj']   = $this->em->getRepository('DealBundle:DealBookingQuote')->findOneById($params['bookingObj']->getDealBookingQuoteId());
        $dealTypeObj          = $this->em->getRepository('DealBundle:DealType')->findOneById($params['bookingObj']->getDealTypeId());
        $dealTypeCategory     = $dealTypeObj->getCategory();
        
        switch ($dealTypeCategory) {
            case 'transfers':
                $transportParam       = $this->em->getRepository('DealBundle:DealTransferBookingDetails')->getTransferDetailsByBookingId($params['bookingObj']->getId());
                $transportObj         = $this->getDealTransferBookingCriteria($transportParam);
                $dealResponseObj      = $this->cityDiscoveryHandler->processTransportBooking($transportObj);
                $getDealBooking       = $dealResponseObj->getTransferBooking();
                $bookingResults       = $getDealBooking[0]->getBookingResponse()->toArray();
                $bookingResults['id'] = $params['bookingObj']->getId();
                break;
            default:
                $dealResponseObj      = $this->cityDiscoveryHandler->processBooking($params['bookingObj'], $params['quoteObj']);
                $getDealBooking       = $dealResponseObj->getDealBooking();
                $bookingResults       = $getDealBooking[0]->toArray();
        }
        
        
        $getCancellationPolicy = $dealResponseObj->getCancellationPolicy();
        $isCorporate           = $this->utils->isCorporateSite();
        $paymentRequired       = ($bookingObj->getPaymentRequired()) ? true : false;
        $paymentType           = ($bookingObj->getPaymentType()) ? $bookingObj->getPaymentType() : 'cc';
        $results               = array();
        
        if (!$getDealBooking) {
            //release the amount of money
            if ($paymentType == 'cc') {
                $this->paymentServiceImpl->voidOnHoldPayment($params['bookingObj']->getPaymentUuid());
            }
            
            if ($paymentRequired) {
                $params['requestStatus'] = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
                $this->updatePendingRequestServices($params);
            }
            
            //sending off notification
            $this->sendNotificationEmail($params['bookingObj']->getId(), 'error');
            
            $results['redirectUrl']    = '_deals';
            $results['redirectParams'] = array('success' => false, 'message' => $dealResponseObj->getErrorMessage());
            $results['success']        = false;
            $results['statusCode']     = 422;
            $results['message']        = $this->translator->trans($dealResponseObj->getErrorMessage());
            return $this->createJsonResponse($results);
        } else {
            $bookingStatus               = strtolower($bookingResults['bookingStatus']);
            $bookingResults['bookingId'] = $params['bookingObj']->getId();
            if ($bookingStatus == 'error' || $bookingStatus == 'not available') {
                if ($paymentRequired) {
                    $params['requestStatus'] = $this->container->getParameter('CORPO_APPROVAL_CANCELED');
                    $this->updatePendingRequestServices($params);
                }
                
                //release the amount of money
                if ($paymentType == 'cc') {
                    $this->paymentServiceImpl->voidOnHoldPayment($params['bookingObj']->getPaymentUuid());
                }
                
                //sending off notification
                $this->sendNotificationEmail($params['bookingObj']->getId(), $bookingStatus);
                
                $results['redirectUrl']    = '_deals';
                $results['redirectParams'] = array('success' => false, 'message' => $this->translator->trans('Booking has encountered an error!'));
                $results['success']        = false;
                $results['statusCode']     = 422;
                $results['message']        = $this->translator->trans('Booking has encountered an error!');
                
                return $this->createJsonResponse($results);
            }
            // in this case of confirmed and completed booking we shall capture the amount of money onhold, else for OnRequest, we do nothing
            elseif ($bookingStatus == 'confirmed' || $bookingStatus == 'onrequest') {
                if ($paymentRequired) {
                    $params['requestStatus'] = $this->container->getParameter('CORPO_APPROVAL_APPROVED');
                    $this->updatePendingRequestServices($params);
                }
                
                //capture the amount of money for confirmed booking status
                if ($bookingStatus == 'confirmed' && $paymentType == 'cc') {
                    $this->paymentServiceImpl->captureOnHoldPayment($params['bookingObj']->getPaymentUuid());
                }
                
                if ($getCancellationPolicy) {
                    $cancellationPolicy = array();
                    foreach ($getCancellationPolicy as $cpKey => $cpVal) {
                        $cancellationPolicy[] = $cpVal->toArray();
                    }
                    
                    $bookingResults['cancellationPolicy'] = json_encode($cancellationPolicy);
                }
                
                $bookingResultsObj = $this->getDealBookingCriteria($bookingResults);
                $this->em->getRepository('DealBundle:DealBooking')->updateBookingData($bookingResultsObj);
                
                //sending off notification
                $this->sendNotificationEmail($params['bookingObj']->getId(), $bookingStatus);
                
                $results['redirectParams'] = array('bookingId' => $params['bookingObj']->getId());
                $results['redirectUrl']    = ($isCorporate) ? '_corporate_deals_booking_details' : '_dealBookingDetails';
                $results['success']        = false;
                $results['statusCode']     = 422;
                $results['message']        = $this->translator->trans('Booking has been successful!!');
                
                return $this->createJsonResponse($results);
            }
        }
        
        return $this->createJsonResponse($results);
    }
    
    /**
     *  Function is used update corpo approval flow services
     *
     * @param criteria
     *
     * @return bool
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     */
    public function updatePendingRequestServices($params = array())
    {
        if (empty($params)) return false;
        
        return $this->corpoApprovalFlowServices->updatePendingRequestServices($params);
    }
    
    /**
     *  Function is used to cancel booking
     *
     * @param bookingReference the booking reference
     * @param email of booking
     *
     * @return array response
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     */
    public function cancelBooking($bookingReference, $email, $dealType)
    {
        $getDealResponseObj = $this->cityDiscoveryHandler->cancelBooking($bookingReference, $email, $dealType);
        $getCancelObj       = $getDealResponseObj->getDealBookingCancellation();
        $errorArr           = array('errorCode' => $getDealResponseObj->getErrorCode(), 'errorMessage' => $getDealResponseObj->getErrorMessage());
        $cancelResults      = $getCancelObj[0]->toArray();
        $cancelResults      = array_merge($cancelResults, $errorArr);
        $return             = array();
        
        if (!isset($cancelResults['errorAPIMessage'])) {
            if ($cancelResults['errorCode']) {
                $return['errorCode']    = $cancelResults['errorCode'];
                $return['errorMessage'] = $cancelResults['errorMessage'];
            } else {
                $return['bookingReference'] = $cancelResults['cancelBookingReference'];
                $return['bookingStatus']    = $cancelResults['cancelBookingStatus'];
                //xml response for tours doesnt give off price anymore, so this is just for display purporses.
                $return['price']            = '';
                $return['price']                = $cancelResults['cancelBookingPrice'];
                $return['cancellationDay']      = $cancelResults['cancelBookingDay'];
                $return['cancellationDiscount'] = $cancelResults['cancelBookingDiscount'];
                
                $deal_booking = $this->em->getRepository('DealBundle:DealBooking')->findOneByBookingReference($bookingReference);
                
                $dealBookingId = $deal_booking->getId();
                $bookParams    = array("bookingId" => $dealBookingId, "bookingStatus" => $return['bookingStatus']);
                $bookingObj    = $this->getDealBookingCriteria($bookParams);
                $this->em->getRepository('DealBundle:DealBooking')->updateBookingStatus($bookingObj);
                
                // this only happens fro transfers cause its api is using old code
                if ($dealType == 'transfers' && isset($cancelResults['cancelBookingPrice'])) {
                    $return['price'] = $cancelResults['cancelBookingPrice'];
                    $this->em->getRepository('DealBundle:DealCancelPolicies')->saveCancelPolicies($dealBookingId, $cancelResults['cancelBookingPrice']);
                }
                
                //saving to cancellation policy
                //                $dealCancelPolicies = new DealCancelPolicies();
                //                $dealCancelPolicies->setDealBookingId($dealBookingId);
                //                $dealCancelPolicies->setPenaltyAmount($return['price']);
                //                $dealCancelPolicies->setCreatedAt(new \DateTime("now"));
                //                $this->em->persist($dealCancelPolicies);
                //                $this->em->flush();
                //
                //                //Refund amount calculation based on cancellation fees on date of cancellation
                $transactionId = $deal_booking->getPaymentUuid();
                $payment       = $this->findPaymentByUuid($transactionId);
                //
                //                // If Pending status do not execute this
                //                if ($payment) {
                //                    $payment->setOriginalAmount($cancelResults['cancelBookingPrice']);
                //                    $payment->setDisplayOriginalAmount($cancelResults['cancelBookingPrice']);
                //                    $payment->setAmount($cancelResults['cancelBookingPrice']);
                //                    $payment->setDisplayAmount($cancelResults['cancelBookingPrice']);
                //
                //                    $params = array("uuid" => $transactionId, "operation" => "REFUND", "type" => "Package");
                //                    $this->payFortServices->refundCaptureService($payment, $params);
                //                }
                $cancellationFee = $cancelResults['cancelBookingPrice'];
                if ($payment->getPaymentType() == PaymentObj::CORPO_ON_ACCOUNT) {
                    $accountTransaction = new \CorporateBundle\Model\AccountTransaction;
                    $accountTransaction->setReservationId($dealBookingId);
                    $accountTransaction->setModuleId($this->container->getParameter('MODULE_DEALS'));
                    //                    if (!empty($cancellationFee) && floatval($cancellationFee['amount']) > 0) {
                    //                        $accountTransaction->setCurrency($cancellationFee['currencyCode']);
                    //                        $accountTransaction->setcancellationFees($cancellationFee['amount']);
                    //                    }
                    
                    $refundData["success"] = $this->container->get('CorpoAccountTransactionsServices')->refundAccountTransactions($accountTransaction);
                } else {
                    for ($attemptNumber = 1; $attemptNumber <= $this->container->getParameter('MAX_API_CALL_ATTEMPTS'); $attemptNumber++) {
                        $refundData = $this->container->get('PaymentServiceImpl')->refund($transactionId);
                        if ($refundData["success"]) {
                            break;
                        }
                        
                        if ($attemptNumber != $this->container->getParameter('MAX_API_CALL_ATTEMPTS')) {
                            usleep($this->container->getParameter('PAUSE_BETWEEN_RETRIES_US'));
                        }
                    }
                }
                
                // Send Cancellation Notification
                if ($this->sendNotificationEmail($dealBookingId, 'cancelled')) {
                    $this->logger->info(" CANCEL EMAIL SENT SUCCESSFULLY FOR ".$dealBookingId);
                } else {
                    $this->logger->info(" CANCEL EMAIL SENDING FAILED FOR ".$dealBookingId);
                }
            }
        } else {
            $return['cancelResponse'] = false;
        }
        
        return $this->createJsonResponse($return);
    }
    /*
     * This method sends a notification to the user with his booking status update
     *
     * @param $bookingId id of the record in deal_booking table
     * @param $bookingStatus the new status of the booking confirmed, cancelled
     * @param $autoSend boolean
     *
     * @return boolean true or false
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function sendNotificationEmail($bookingId, $bookingStatus = 'confirmed', $autoSend = false)
    {
        $status          = false;
        $notifcationTwig = '';
        $subject         = '';
        
        $bookingObj = $this->em->getRepository('DealBundle:DealBooking')->findOneById($bookingId);
        
        if (isset($bookingObj) && !empty($bookingObj)) {
            $emailVars                     = array();
            $emailVars['dealName']         = $bookingObj->getDealName();
            $emailVars['bookingStatus']    = $bookingObj->getBookingStatus();
            $emailVars['bookingDate']      = date("F j, Y", strtotime($bookingObj->getBookingDate()));
            $emailVars['totalPrice']       = $bookingObj->getTotalPrice();
            $emailVars['voucherInfo']      = $bookingObj->getVoucherInformation();
            $emailVars['bookingReference'] = $bookingObj->getBookingReference();
            $emailVars['numOfAdults']      = $bookingObj->getNumOfAdults();
            $emailVars['numOfChildren']    = $bookingObj->getNumOfChildren();
            $emailVars['departureTime']    = $bookingObj->getDepartureTime();
            $emailVars['currency']         = $bookingObj->getCurrency();
            $emailVars['fullName']         = $bookingObj->getTitle().' '.$bookingObj->getFirstName().' '.$bookingObj->getLastName();
            
            switch ($bookingStatus) {
                case 'onrequest':
                    $subject         = 'Your Booking';
                    $notifcationTwig = 'emails/deals_booking_onrequest_email.twig';
                    break;
                case 'confirmed':
                    $subject         = 'Your Booking';
                    $notifcationTwig = 'emails/deals_booking_confirmation_email.twig';
                    break;
                case 'cancelled':
                    $subject         = 'Booking Canceled';
                    $notifcationTwig = 'emails/deals_booking_cancellation_email.twig';
                    
                    $cancelPolicyObj              = $this->em->getRepository('DealBundle:DealCancelPolicies')->findByDealBookingId($bookingId);
                    $emailVars['refund']          = (isset($cancelPolicyObj) && !empty($cancelPolicyObj)) ? $cancelPolicyObj[0]->getPenaltyAmount() : '00:00';
                    $emailVars['autoSend']        = ($autoSend) ? 1 : 0;
                    break;
                case 'error':
                    $subject                      = 'Booking Canceled';
                    $emailVars['cancellationFee'] = (isset($cancelPolicyObj) && !empty($cancelPolicyObj)) ? $cancelPolicyObj[0]->getPenaltyAmount() : '00:00';
                    $notifcationTwig              = 'emails/deals_booking_cancellation_email.twig';
                    $emailVars['error']           = 1;
                    break;
                default:
                    break;
            }
            
            $msg    = $this->templating->render($notifcationTwig, $emailVars);
            $status = $this->emailServices->addEmailData($bookingObj->getEmail(), $msg, 'TouristTube Deals - '.$subject, 'TouristTube.com', 0);
        }
        
        return $status;
    }
    /*
     * This method gets all the attractions of a city and a country
     *
     * @param $cityCode
     * @param $countryCode
     *
     * @return array of attractions per selected city and country
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getDiscoveryCityAttractions($cityCode, $countryCode)
    {
        return $this->cityDiscoveryHandler->getDiscoveryCityAttractions($cityCode, $countryCode);
    }
    /*
     * This is a testing method to get packages response  for all deal cities and insert them in deal details table
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getCityPackages($closeEntityManagerOnCompletion = false)
    {
        return;
    }
    /*
     * This is a testing method for American Tours Internationals to get activities response  for all deal cities and insert them in deal details table
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getCityActivities($closeEntityManagerOnCompletion = false)
    {
        return;
    }
    
    /**
     * This method gets the list of countries from City Discovery API and insert it to our db table deal_country
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    public function getDiscoveryCityCountries()
    {
        return $this->cityDiscoveryHandler->getDiscoveryCityCountries();
    }
    
    /**
     *  Function is used to check the status of booking
     * from the API call of get ActivityBookingStatus from city discovery services
     *
     * @param bookingReference the booking reference for city discovery
     * @param email email-address for city discovery
     *
     * @return array response
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    public function getBookingStatus($bookingReference, $email)
    {
        $getBookingStatusObj  = $this->cityDiscoveryHandler->getBookingStatus($bookingReference, $email);
        $getBookingStatus     = $getBookingStatusObj->getDealBooking();
        $bookingStatusResults = array_merge($getBookingStatus[0]->toArray(), array('errorCode' => $getBookingStatusObj->getErrorCode(), 'errorMessage' => $getBookingStatusObj->getErrorMessage()));
        
        $response = array();
        if (!isset($bookingStatusResults['errorAPIMessage'])) {
            if (isset($bookingStatusResults['errorCode']) && !empty($bookingStatusResults['errorCode'])) {
                $response['errorCode']    = $bookingStatusResults['errorCode'];
                $response['errorMessage'] = $bookingStatusResults['errorMessage'];
            } else {
                foreach ($bookingStatusResults as $key => $val) {
                    if (isset($bookingStatusResults[$key]) && $bookingStatusResults[$key]) {
                        $response[$key] = $val;
                    }
                }
            }
        } else {
            $response['bookingStatusResponse'] = true;
        }
        
        return $this->createJsonResponse($response);
    }
    /*
     *
     * This method checks the availability of a deal from city discovery by checking if any error returned
     * otherwise the activity will be available
     *
     * @param $bookingId
     *
     * @return bool
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function checkAvailability($bookingId)
    {
        $dbParams          = array('bookingId' => $bookingId, 'leftJoinTransfers' => true,
            'dbFields' => array('db.dealCode as dealCode', 'db.numOfAdults as numOfAdults', 'db.departureTime as departureTime', 'dc.cityName as cityName', 'dcn.countryCode as countryCode', 'dt.category as category',
                'dtbd'));
        $bookingDetailsObj = $this->getDealBookingCriteria($dbParams);
        $bookingEncoded    = $this->getBookingDetails($bookingDetailsObj);
        $bookingDecoded    = json_decode($bookingEncoded, true);
        $bookingDetailsObj = $bookingDecoded['data'];
        $response          = true;
        
        switch ($bookingDetailsObj['category']) {
            case 'transfers':
                $arr = array('arrivalPickupDate' => $bookingDetailsObj['dtbd_arrivalDate']['date'],
                'arrivalPickupTime' => $bookingDetailsObj['dtbd_arrivalTime'],
                'departurePickupDate' => $bookingDetailsObj['dtbd_departureDate']['date'],
                'departurePickupTime' => $bookingDetailsObj['departureTime'],
                'destinationCity' => $bookingDetailsObj['cityName'],
                'destinationCountry' => $bookingDetailsObj['countryCode'],
                'airportCode' => $bookingDetailsObj['dtbd_airportCode'],
                'numOfPassengers' => $bookingDetailsObj['numOfAdults'],
                'serviceCode' => $bookingDetailsObj['dtbd_serviceCode'],
                'checkAvailability' => true
                );
                
                //Check if priceId is still available
                $transferAvailable = false;
                $results           = $this->cityDiscoveryHandler->getTransferVehicles($arr);
                foreach ($results->getTransferVehicles() as $tKey => $tVal) {
                    foreach ($tVal->getDealTransferAirportPrice() as $dKey => $dVal) {
                        if ($transferAvailable) {
                            $results->setErrorMessage('Transport not available.');
                            break;
                        }
                        switch ($bookingDetailsObj['dtbd_serviceCode']) {
                            case '0':
                                if ($bookingDetailsObj['dtbd_arrivalPriceId'] && $bookingDetailsObj['dtbd_arrivalPriceId'] == $dVal->getArrivalPriceId()) {
                                    $transferAvailable = true;
                                }
                                break;
                            case '1':
                                if ($bookingDetailsObj['dtbd_departurePriceId'] && $bookingDetailsObj['dtbd_departurePriceId'] == $dVal->getDeparturePriceId()) {
                                    $transferAvailable = true;
                                }
                                break;
                            default:
                                if (($bookingDetailsObj['dtbd_arrivalPriceId'] && $bookingDetailsObj['dtbd_arrivalPriceId'] == $dVal->getArrivalPriceId()) && ($bookingDetailsObj['dtbd_departurePriceId']
                                && $bookingDetailsObj['dtbd_departurePriceId'] == $dVal->getDeparturePriceId())) {
                                    $transferAvailable = true;
                                }
                        }
                    }
                }
                $results = $results->toArray();
                break;
            default:
                $results = $this->cityDiscoveryHandler->checkAvailability($bookingDetailsObj['dealCode'])->toArray();
        }
        
        if ((isset($results['errorCode']) && !empty($results['errorCode'])) || (isset($results['errorAPIMessage']) && !empty($results['errorAPIMessage']) )) {
            $response = false;
        }
        
        return $this->createJsonResponse($response);
    }
    /*
     * This method checks if transfer or deal is still available
     *
     * @param bookingId
     *
     * @return bool
     * @author Anna Lou Parejo <firas.boukarroum@touristtube.com>
     */
    
    public function checkDealAvailability($bookingId)
    {
        $bookingEncoded = $this->findBookingById($bookingId);
        $bookingDecoded = json_decode($bookingEncoded, true);
        $dealBookingObj = $bookingDecoded['data'];
        
        if (empty($dealBookingObj)) return false;
        
        $dealTypeObj      = $this->em->getRepository('DealBundle:DealType')->findOneById($dealBookingObj['dealTypeId']);
        $dealTypeCategory = $dealTypeObj->getCategory();
        
        $isAvailable = false;
        switch ($dealTypeCategory) {
            case 'transfers':
                $isAvailable = $this->checkTransferAvailability($dealBookingObj);
                break;
            default:
                $isAvailable = $this->checkQuoteAvailability($dealBookingObj);
        }
        return $this->createJsonResponse($isAvailable);
    }
    /*
     * This method checks quote availability of a deal from city discovery
     * if less than 15 mins we use the already quote have in our db we have and send to booking
     * if more we shall use same fields to send a new quote request to aqcuire new quotekey with same criteria
     * check price returned for the new quote request sent if the same then we proceed
     * if different price we inform the approver that the deal is expired or somthing like that ( this is already implemented if check availability returned false )
     *
     * @param $dealBookingObj
     *
     * @return bool
     * @author Anna Lou Parejo <firas.boukarroum@touristtube.com>
     */
    
    public function checkQuoteAvailability($dealBookingObj)
    {
        $quoteId = $dealBookingObj['dealBookingQuoteId'];
        
        if (empty($quoteId)) return false;
        
        $quoteEncoded = $this->getBookingQuotesById($quoteId);
        $quoteDecoded = json_decode($quoteEncoded, true);
        $dealQuoteObj = $quoteDecoded['data'][0];
        
        if (empty($dealQuoteObj)) return false;
        
        $units    = json_decode($dealQuoteObj['dynamicFields'], TRUE);
        $createAt = $dealQuoteObj['createdAt']['date'];
        $quote    = date_create($createAt);
        $now      = date_create(); // Current time and date
        $diff     = date_diff($quote, $now);
        
        $response = true;
        if ($diff->h > 0 || $diff->i > 15) {
            $params                    = array();
            $params['tourCode']        = $dealQuoteObj['tourCode'];
            $params['packageId']       = $dealQuoteObj['packageId'];
            $params['bookingDate']     = $dealBookingObj['bookingDate'];
            $params['activityPriceId'] = $dealQuoteObj['activityPriceId'];
            $params['priceId']         = $dealQuoteObj['priceId'];
            $params['currency']        = $dealBookingObj['currency'];
            
            $unitArr = array();
            foreach ($units["Units"] as $unitKey => $unitVal) {
                $item = array();
                if (isset($unitVal['unitId'])) {
                    $item['unitId'] = $unitVal['unitId'];
                }
                if (isset($unitVal['quantity'])) {
                    $item['qty'] = $unitVal['quantity'];
                }
                $unitArr[] = $item;
            }
            
            $params['units'] = $unitArr;
            
            $quoteObj     = $this->getDealQuotationCriteria($params);
            $quoteEncoded = $this->getQuotation($quoteObj);
            $quoteDecoded = json_decode($quoteEncoded, true);
            $results      = $quoteDecoded['data'];
            
            if ($quoteDecoded['success'] == false) {
                $response = false;
            } else {
                if ($quoteDecoded['success'] == true && $results['total'] == $dealQuoteObj['total']) {
                    $bookingResultsObj = $this->getDealBookingCriteria(array('bookingId' => $dealBookingObj['id'], 'bookingQuoteId' => $results['quoteId'][0]));
                    $this->em->getRepository('DealBundle:DealBooking')->updateBookingData($bookingResultsObj);
                    
                    //updating mandatory field values
                    foreach ($results['quoteId'] as $quoteId) {
                        $mandatoryFields                    = array();
                        $mandatoryFields['mandatoryFields'] = $dealQuoteObj['dynamicFieldsValues'];
                        $mandatoryFields['bookingQuoteId']  = $quoteId;
                        $mandatoryObj                       = $this->getDealMandatoryFieldsCriteria($mandatoryFields);
                        $this->saveMandatoryFieldAnswers($mandatoryObj);
                    }
                    $response = true;
                } else {
                    $response = false;
                }
            }
        }
        return $response;
    }
    /*
     * This method checks if transfer still available
     *
     * @param $dealBookingObj
     *
     * @return bool
     * @author Anna Lou Parejo <firas.boukarroum@touristtube.com>
     */
    
    public function checkTransferAvailability($dealBookingObj)
    {
        $bookingId       = $dealBookingObj['id'];
        $transferDetails = $this->em->getRepository('DealBundle:DealTransferBookingDetails')->getTransferDetailsByBookingId($bookingId);
        
        if (!$transferDetails) return false;
        
        $params                       = array();
        $params['destinationCountry'] = $transferDetails['country'];
        $params['destinationCity']    = $transferDetails['city'];
        $params['airportCode']        = $transferDetails['airportCode'];
        $params['typeOfTransferCode'] = $transferDetails['serviceCode'];
        $params['numOfPassengers']    = $transferDetails['numOfpassengers'];
        $params['arrivalInput']       = $transferDetails['arrivalInput'];
        $params['arrivalTime']        = $transferDetails['arrivalTime'];
        $params['departureInput']     = $transferDetails['departureInput'];
        $params['departureTime']      = $transferDetails['departureTime'];
        $params['checkAvailability']  = true;
        
        //        echo '<pre>';
        //        var_dump($params);
        //        echo '</pre>';
        $dealTransferObj    = $this->getDealTransferVehiclesListingCriteria($params);
        //        echo '<pre>';
        //        var_dump($dealTransferObj);
        //        echo '</pre>';
        //        //exit;
        $getDealResponseObj = $this->cityDiscoveryHandler->getTransferVehicles($dealTransferObj);
        //        echo '<pre>';
        //        var_dump($getDealResponseObj);
        //        echo '</pre>';
        //        exit;
        if ($getDealResponseObj->getErrorCode() && $getDealResponseObj->getErrorMessage()) {
            return false;
        }
        return true;
    }
    /*
     *  This handles the checking of availability on activity
     *
     * @params $activityId
     *
     * @return array
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */
    
    public function checkActivityAvailability($activityId)
    {
        $results = $this->cityDiscoveryHandler->checkAvailability($activityId)->toArray();
        
        $response = true;
        if ((isset($results['errorCode']) && !empty($results['errorCode'])) || (isset($results['errorAPIMessage']) && !empty($results['errorAPIMessage']) )) {
            $response = false;
        }
        return $this->createJsonResponse($response);
    }
    /*
     *  This handles the lading page for tourSearch. This will display list of avaiable packages.
     *  It is also in that when you search a location/destination we handle the request.
     *
     * @params array of deals criteria
     *
     * @return array of search results
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function dealSearch($dealSC)
    {
        $langCode         = $dealSC->getLangCode();
        $category         = $dealSC->getCategory();
        $priority         = $dealSC->getPriority();
        $selectedCurrency = $dealSC->getSelectedCurrency();
        
        
        if ($selectedCurrency != 'USD') {
            $conversionRate = $this->currencyService->getConversionRate($selectedCurrency, 'USD');
            $lowestPrice    = floor($this->currencyService->currencyConvert($dealSC->getMinPrice(), $conversionRate));
            $highestPrice   = ceil($this->currencyService->currencyConvert($dealSC->getMaxPrice(), $conversionRate));
            $dealSC->setMinPrice($lowestPrice);
            $dealSC->setMaxPrice($highestPrice);
        }
        
        $repo = $this->em->getRepository('DealBundle:DealDetails');
        
        if ($dealSC->getSearchAll()) {
            $result = $repo->getDeals($category, $priority, $langCode);
        } elseif ($dealSC->getDealNameSearch()) {
            $result = $repo->getCitySearchByDealNames($dealSC, $langCode);
        } else {
            $result = $repo->getDealsBySearchCriteria($dealSC, $category, $priority, $langCode);
        }
        
        $searchResults   = array();
        $searchObject    = array();
        //always usd cause were coming from db
        $conversionRate2 = $this->currencyService->getConversionRate('USD', $selectedCurrency);
        if (!empty($result)) {
            //setting min and max values based from the results
            $lowestPrice  = min(array_column($result, 'dd_priceBeforePromo'));
            $lowestPrice  = floor($this->currencyService->currencyConvert($lowestPrice, $conversionRate2));
            $highestPrice = max(array_column($result, 'dd_priceBeforePromo'));
            $highestPrice = ceil($this->currencyService->currencyConvert($highestPrice, $conversionRate2));
            
            $categoryIds = array();
            foreach ($result as $deal) {
                $newConvertedPrice                    = $this->currencyService->currencyConvert($deal['dd_priceBeforePromo'], $conversionRate2);
                $deal['dd_priceBeforePromo']          = $newConvertedPrice;
                $deal['dd_formattedPriceBeforePromo'] = number_format($newConvertedPrice, 2, '.', ',');
                $deal['dd_currency']                  = $selectedCurrency;
                $deal['imagePath']                    = $this->getDealDefaultImage($deal['dd_id']);
                $dealName                             = (isset($deal['dealNameTrans']) && $deal['dealNameTrans']) ? $deal['dealNameTrans'] : $deal['dealName'];
                $deal['urlPath']                      = $this->container->get('TTRouteUtils')->returnDealDetailsLink($deal['dd_id'], $dealName, $deal['cityName'], $deal['dealType'], $langCode);
                
                if ($deal['categoryId'] && $deal['categoryId'] > 0) {
                    $categoryIds[$deal['categoryId']] = $deal['categoryName'];
                } else {
                    $categoryIds[0] = 'No Category';
                }
                
                $searchResults[$deal['dd_id']] = $deal;
            }
            $searchObject['minPrice']     = $lowestPrice;
            $searchObject['maxPrice']     = $highestPrice;
            $searchObject['priceRange']   = $lowestPrice.','.$highestPrice;
            $searchObject['categoryList'] = count($categoryIds) ? array_unique($categoryIds) : array();
            asort($searchObject['categoryList']);
        }
        
        $searchObject['result']       = $searchResults;
        $searchObject['numRowsCnt']   = count($result);
        $searchObject['categoryList'] = (isset($searchObject['categoryList']) && $searchObject['categoryList']) ? $searchObject['categoryList'] : array();
        
        return $this->createJsonResponse($searchObject);
    }
    /*
     * Retrieve the Top Attractions for deals page
     *
     * @param DealSC $dealSC The search criteria object.
     *
     * @return array
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     */
    
    public function getTopAttractions($dealSC)
    {
        $attractions = array();
        if (empty($dealSC)) {
            return $attractions;
        }
        $data = $dealSC->getAttractions();
        foreach ($data as $list) {
            $item         = array();
            $item['name'] = $list['name'];
            $item['img']  = $list['img'];
            $item['link'] = $this->container->get('TTRouteUtils')->returnDealNameSearchLink($dealSC->getLangCode(), $list['name']);
            
            $dealSC->getCommonSC()->getPackage()->setName($list['name']);
            $dealsNameRepo        = $this->em->getRepository('DealBundle:DealDetails')->getDealsBySearchCriteria($dealSC, 'attractions');
            $item['tours_number'] = ($dealsNameRepo && !empty($dealsNameRepo)) ? count($dealsNameRepo) : 0;
            
            $attractions[] = $item;
        }
        
        return $this->createJsonResponse($attractions);
    }
    /*
     * Retrieve tours number for a specific deal type
     *
     * @param Deal type Name $name .
     *
     * @return count
     */
    
    public function getDealTypeToursNumber($params = array(), $type = 'all')
    {
        $dealsNameRepo = $this->em->getRepository('DealBundle:DealDetails')->getDealsSearchCount($params, $type);
        return ($dealsNameRepo && !empty($dealsNameRepo)) ? $dealsNameRepo : 0;
    }
    /*
     * Retrieve the Top Desitnations for deals page
     *
     * @params: array of deals criteria
     *
     * @return array
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     */
    
    public function getTopDestinations($dealSC)
    {
        $destinations = array();
        if (empty($dealSC)) {
            return $destinations;
        }
        
        $destinationsRepo = $this->em->getRepository('DealBundle:DealDetails')->getLandingPageTopDestinations($dealSC);
        foreach ($destinationsRepo as $list) {
            $list['link']   = $this->container->get('TTRouteUtils')->returnDealsSearchLink($dealSC->getLangCode(), $list['name']);
            $destinations[] = $list;
        }
        
        return $this->createJsonResponse($destinations);
    }
    /*
     *  Retrieve the Top Tours for deals page
     *
     * @params: array of deals criteria
     *
     * @return array
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     */
    
    public function getLandingPageTopTours($dealSC)
    {
        $tours = array();
        if (empty($dealSC)) {
            return $tours;
        }
        
        //static of USD cause in db we only have USD
        $selectedCurrency = $dealSC->getSelectedCurrency();
        $conversionRate   = $this->currencyService->getConversionRate('USD', $selectedCurrency);
        $topToursRepo     = $this->em->getRepository('DealBundle:DealDetails')->getLandingPageTopTours($dealSC, 'tours', $dealSC->getLangCode());
        if ($topToursRepo) {
            foreach ($topToursRepo as $topTour) {
                $newConvertedPrice    = $this->currencyService->currencyConvert($topTour['price'], $conversionRate);
                $newPBPConvertedPrice = $this->currencyService->currencyConvert($topTour['priceBeforePromo'], $conversionRate);
                
                $topTour['currency']                  = $selectedCurrency;
                $topTour['price']                     = number_format($newConvertedPrice, 2, '.', ',');
                $topTour['priceBeforePromo']          = $newPBPConvertedPrice;
                $topTour['formattedPriceBeforePromo'] = number_format($newPBPConvertedPrice, 2, '.', ',');
                $topTour['durationText']              = $this->utils->convertMinToDaysHrsMin($topTour['duration']);
                $topTour['imagePath']                 = $this->getDealDefaultImage($topTour['packageId']);
                $topTour['urlPath']                   = $this->container->get('TTRouteUtils')->returnDealDetailsLink($topTour['packageId'], $topTour['dealName'], $topTour['cityName'], $topTour['dealType'], $dealSC->getLangCode());
                $tours[]                              = $topTour;
            }
        }
        
        return $this->createJsonResponse($tours);
    }
    /*
     * Retrieving default image path for deal
     *
     * @param $dealDetailsId
     *
     * @return imagePath
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     */
    
    public function getDealDefaultImage($packageId = 0, $isThumbnail = true, $imgWidth = 255, $imgHeight = 148)
    {
        $repo         = $this->em->getRepository('DealBundle:DealDetails');
        $result       = $repo->getPackageById($packageId);
        $defaultImage = $this->container->get("TTRouteUtils")->generateMediaURL("/media/images/deals/default.jpg");
        
        if (empty($result)) {
            $sourcepath   = "media/images/deals/";
            $sourcename   = "default.jpg";
            $defaultImage = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, $imgWidth, $imgHeight, 'deals'.$imgWidth.$imgHeight, $sourcepath, $sourcepath, 65);
            return $defaultImage;
        }
        
        $imagePath = '';
        $dir       = $this->container->getParameter('CONFIG_SERVER_ROOT');
        $params    = array('directory' => $dir,
            'apiId' => $result['dd_dealApiId'],
            'dealCode' => $result['dd_dealCode'],
            'categoryName' => $result['dcat_name'],
            'cityName' => $result['dc_cityName'],
            'imageId' => $result['di_id'],
            'isThumbnail' => $isThumbnail,
            'imgWidth' => $imgWidth,
            'imgHeight' => $imgHeight,
        );
        
        $imgObj        = $this->getDealImageCriteria($params);
        $dealImagePath = $this->returnDealsImageLink($imgObj);
        
        if ($dealImagePath) {
            $imagePath = $dealImagePath;
        } else {
            $nextImageArr = $repo->getNextDealImage($result['dd_id']);
            $nextImageObj = array();
            foreach ($nextImageArr as $imgArr) {
                $nextImageObj[] = $this->getDealImageCriteria($imgArr);
            }
            $nextImage = $this->returnNextDealImage($imgObj, $nextImageObj);
            $imagePath = ($nextImage) ? $nextImage : $defaultImage;
        }
        return $imagePath;
    }
    /*
     * This is the method is used to sync images with its corresponding dealDetailsId
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function syncDealImages()
    {
        return $this->em->getRepository('DealBundle:DealDetails')->syncDealImages();
    }
    /*
     * This is the render method for each Format we may have like Text, date, radio, select box ...
     *
     * @param $fieldObject
     *
     * @return layout of a specific field
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function renderDynamicField($fieldObject)
    {
        $params                      = array();
        $params['fieldId']           = $fieldObject->getFieldId();
        $params['data']              = $fieldObject->getData();
        $params['label']             = $fieldObject->getLabel();
        $params['fieldName']         = $fieldObject->getFieldName();
        $params['format']            = strtolower($fieldObject->getFormat());
        $params['containerDiv']      = $fieldObject->getContainerDiv();
        $params['containerDivClass'] = $fieldObject->getContainerDivClass();
        $params['options']           = $fieldObject->getOptions();
        $params['placeholder']       = $fieldObject->getPlaceholder();
        
        $return = '';
        switch ($params['format']) {
            case 'text':
                $return = $this->renderText($params);
                break;
            case 'text_alphanumeric':
                $return = $this->renderText($params);
                break;
            case 'radio':
                $return = $this->renderRadio($params);
                break;
            case 'switch':
                // since we dont have switch layout
                $return = $this->renderRadio($params);
                break;
            case 'checkbox':
                $return = $this->renderCheckbox($params);
                break;
            case 'date_select':
                $return = $this->renderDate($params);
                break;
            case 'date_text':
                $return = $this->renderDate($params);
                break;
            case 'date':
                $return = $this->renderDate($params);
                break;
            case 'select':
                $return = $this->renderSelect($params);
                break;
            default:
                break;
        }
        return $return;
    }
    /*
     * This is the render method for Text Format
     *
     * @param $fieldObject
     *
     * @return text input layout
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function renderText($fieldObject)
    {
        $template = 'dynamicFields/field_text.twig';
        return $this->templating->render($template, $fieldObject);
    }
    /*
     * This is the render method for Text Format
     *
     * @param $fieldObject
     *
     * @return text input layout
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function renderSelect($fieldObject)
    {
        $template = 'dynamicFields/field_select.twig';
        return $this->templating->render($template, $fieldObject);
    }
    /*
     * This is the render method for Radio Format
     *
     * @param $fieldObject
     *
     * @return radio input layout
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function renderRadio($fieldObject)
    {
        $template = 'dynamicFields/field_radio.twig';
        return $this->templating->render($template, $fieldObject);
    }
    /*
     * This is the render method for CheckBox Format
     *
     * @param $fieldObject
     *
     * @return checkbox input layout
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function renderCheckbox($fieldObject)
    {
        $template = 'dynamicFields/field_checkbox.twig';
        return $this->templating->render($template, $fieldObject);
    }
    /*
     * This is the render method for Date Format
     *
     * @param $fieldObject
     *
     * @return date input layout
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function renderDate($fieldObject)
    {
        $template = 'dynamicFields/field_date.twig';
        return $this->templating->render($template, $fieldObject);
    }
    /*
     * This method render mandatory fields
     *
     * @param $dynamicFieldsObj
     *
     * @return array
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */
    
    public function renderMandatoryFields($dynamicFieldsObj)
    {
        $return = array();
        if (property_exists($dynamicFieldsObj, 'Mandatory')) {
            $perPerson = array();
            foreach ($dynamicFieldsObj->Mandatory as $dfKey => $dfVal) {
                switch (strtolower($dfVal->per)) {
                    case 'person':
                        $perPerson[]            = $dfVal;
                        break;
                    default:
                        $arr                    = array('label' => $dfVal->label,
                        'fieldId' => $dfVal->fieldId,
                        'format' => $dfVal->format,
                        'data' => $dfVal->data,
                        'fieldName' => 'mandatory_'.$dfVal->fieldId
                        );
                        $mandatoryFieldsObj     = $this->getDealMandatoryFieldsCriteria($arr);
                        $fieldsObj              = $this->setParamsForMandatoryFields($mandatoryFieldsObj);
                        $return['perBooking'][] = $this->renderDynamicField($fieldsObj);
                }
            }
            
            foreach ($dynamicFieldsObj->Units as $uKey => $uVal) {
                for ($i = 1; $i <= (int) $uVal->quantity; $i++) {
                    $unitId = $uVal->unitId;
                    foreach ($perPerson as $pKey => $pVal) {
                        $arr                                               = array('label' => $pVal->label,
                            'fieldId' => $unitId.'_'.$i.'_'.$pVal->fieldId,
                            'format' => $pVal->format,
                            'data' => $pVal->data,
                            'fieldName' => 'mandatory_'.$pVal->fieldId.'_'.$unitId.'_'.$i
                        );
                        $mandatoryFieldsObj                                = $this->getDealMandatoryFieldsCriteria($arr);
                        $fieldsObj                                         = $this->setParamsForMandatoryFields($mandatoryFieldsObj);
                        $return['perPerson'][$unitId.'_'.$i]['blockLabel'] = $uVal->unitLabel;
                        
                        $return['perPerson'][$unitId.'_'.$i]['display'][] = $this->renderDynamicField($fieldsObj);
                    }
                }
            }
        }
        return $return;
    }
    /*
     * This method set params for mandatory fields
     *
     * @param $mandatoryFieldsObj
     *
     * @return mandatory Fields Object
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */
    
    public function setParamsForMandatoryFields($mandatoryFieldsObj)
    {
        $params                      = array();
        $format                      = strtolower($mandatoryFieldsObj->getFormat());
        $params['data']              = $mandatoryFieldsObj->getData();
        $params['containerDiv']      = true;
        $params['containerDivClass'] = 'col-lg-12 col-md-12 col-sm-12 col-xs-12 nopad';
        
        switch ($format) {
            case 'radio':
                $params['containerDivClass'] = $params['containerDivClass'].' pinfspbulletmargin_atf';
                $params['options']           = explode(';', $params['data']);
                break;
            case 'switch':
                $params['containerDivClass'] = $params['containerDivClass'].' pinfspbulletmargin_atf';
                $params['options']           = explode(';', $params['data']);
                break;
            case 'checkbox':
                $params['containerDivClass'] = $params['containerDivClass'].' pinfspbulletmargin_atf';
                $params['options']           = explode(';', $params['data']);
                break;
            case 'date_select':
                $params['containerDivClass'] = $params['containerDivClass'].' marbutfift_atf';
                $params['placeholder']       = 'Please select a date';
                break;
            case 'date_text':
                $params['containerDivClass'] = $params['containerDivClass'].' marbutfift_atf';
                $params['placeholder']       = 'Please select a date';
                break;
            case 'date':
                $params['containerDivClass'] = $params['containerDivClass'].' marbutfift_atf';
                $params['placeholder']       = 'Please select a date';
                break;
            case 'select':
                $params['containerDivClass'] = $params['containerDivClass'].' marbutfift_atf';
                $params['options']           = explode(';', $params['data']);
                break;
            default:
                $params['containerDivClass'] = $params['containerDivClass'].' marbutfift_atf';
        }
        
        $mandatoryFieldsObj->setContainerDivClass($params['containerDivClass']);
        $mandatoryFieldsObj->setContainerDiv($params['containerDiv']);
        if (isset($params['options'])) {
            $mandatoryFieldsObj->setOptions($params['options']);
        }
        if (isset($params['placeholder'])) {
            $mandatoryFieldsObj->setPlaceholder($params['placeholder']);
        }
        
        return $mandatoryFieldsObj;
    }
    /*
     * This is the render method for each Format we may have like Text, date, radio, select box ...
     *
     * @param $fieldObject
     *
     * @return layout of a specific field
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function renderDynamicFieldNew($fieldObject)
    {
        $params                      = array();
        $params['fieldId']           = $fieldObject->getFieldId();
        $params['data']              = $fieldObject->getData();
        $params['label']             = $fieldObject->getLabel();
        $params['fieldName']         = $fieldObject->getFieldName();
        $params['format']            = strtolower($fieldObject->getFormat());
        $params['containerDiv']      = $fieldObject->getContainerDiv();
        $params['containerDivClass'] = $fieldObject->getContainerDivClass();
        $params['options']           = $fieldObject->getOptions();
        $params['placeholder']       = $fieldObject->getPlaceholder();
        
        $return = '';
        switch ($params['format']) {
            case 'text':
                $return = $this->renderTextNew($params);
                break;
            case 'text_alphanumeric':
                $return = $this->renderTextNew($params);
                break;
            case 'radio':
                $return = $this->renderRadioNew($params);
                break;
            case 'switch':
                // since we dont have switch layout
                $return = $this->renderRadioNew($params);
                break;
            case 'checkbox':
                $return = $this->renderCheckboxNew($params);
                break;
            case 'date_select':
                $return = $this->renderDateNew($params);
                break;
            case 'date_text':
                $return = $this->renderDateNew($params);
                break;
            case 'date':
                $return = $this->renderDateNew($params);
                break;
            case 'select':
                $return = $this->renderSelectNew($params);
                break;
            default:
                break;
        }
        return $return;
    }
    /*
     * This is the render method for Text Format
     *
     * @param $fieldObject
     *
     * @return text input layout
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function renderTextNew($fieldObject)
    {
        $template = 'dynamicFields/field_text_new.twig';
        return $this->templating->render($template, $fieldObject);
    }
    /*
     * This is the render method for Text Format
     *
     * @param $fieldObject
     *
     * @return text input layout
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function renderSelectNew($fieldObject)
    {
        $template = 'dynamicFields/field_select_new.twig';
        return $this->templating->render($template, $fieldObject);
    }
    /*
     * This is the render method for Radio Format
     *
     * @param $fieldObject
     *
     * @return radio input layout
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function renderRadioNew($fieldObject)
    {
        $template = 'dynamicFields/field_radio_new.twig';
        return $this->templating->render($template, $fieldObject);
    }
    /*
     * This is the render method for CheckBox Format
     *
     * @param $fieldObject
     *
     * @return checkbox input layout
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function renderCheckboxNew($fieldObject)
    {
        $template = 'dynamicFields/field_checkbox_new.twig';
        return $this->templating->render($template, $fieldObject);
    }
    /*
     * This is the render method for Date Format
     *
     * @param $fieldObject
     *
     * @return date input layout
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    private function renderDateNew($fieldObject)
    {
        $template = 'dynamicFields/field_date_new.twig';
        return $this->templating->render($template, $fieldObject);
    }
    /*
     * This method render mandatory fields
     *
     * @param $dynamicFieldsObj
     *
     * @return array
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */
    
    public function renderMandatoryFieldsNew($dynamicFieldsObj)
    {
        $return = array();
        if (property_exists($dynamicFieldsObj, 'Mandatory')) {
            $perPerson = array();
            foreach ($dynamicFieldsObj->Mandatory as $dfKey => $dfVal) {
                switch (strtolower($dfVal->per)) {
                    case 'person':
                        $perPerson[]            = $dfVal;
                        break;
                    default:
                        $arr                    = array('label' => $dfVal->label,
                        'fieldId' => $dfVal->fieldId,
                        'format' => $dfVal->format,
                        'data' => $dfVal->data,
                        'fieldName' => 'mandatory_'.$dfVal->fieldId
                        );
                        $mandatoryFieldsObj     = $this->getDealMandatoryFieldsCriteria($arr);
                        $fieldsObj              = $this->setParamsForMandatoryFieldsNew($mandatoryFieldsObj);
                        $return['perBooking'][] = $this->renderDynamicFieldNew($fieldsObj);
                }
            }
            
            foreach ($dynamicFieldsObj->Units as $uKey => $uVal) {
                for ($i = 1; $i <= (int) $uVal->quantity; $i++) {
                    $unitId = $uVal->unitId;
                    foreach ($perPerson as $pKey => $pVal) {
                        $arr                                               = array('label' => $pVal->label,
                            'fieldId' => $unitId.'_'.$i.'_'.$pVal->fieldId,
                            'format' => $pVal->format,
                            'data' => $pVal->data,
                            'fieldName' => 'mandatory_'.$pVal->fieldId.'_'.$unitId.'_'.$i
                        );
                        $mandatoryFieldsObj                                = $this->getDealMandatoryFieldsCriteria($arr);
                        $fieldsObj                                         = $this->setParamsForMandatoryFieldsNew($mandatoryFieldsObj);
                        $return['perPerson'][$unitId.'_'.$i]['blockLabel'] = $uVal->unitLabel;
                        
                        $return['perPerson'][$unitId.'_'.$i]['display'][] = $this->renderDynamicFieldNew($fieldsObj);
                    }
                }
            }
        }
        return $return;
    }
    /*
     * This method set params for mandatory fields
     *
     * @param $mandatoryFieldsObj
     *
     * @return mandatory Fields Object
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */
    
    public function setParamsForMandatoryFieldsNew($mandatoryFieldsObj)
    {
        $params                      = array();
        $format                      = strtolower($mandatoryFieldsObj->getFormat());
        $params['data']              = $mandatoryFieldsObj->getData();
        $params['containerDiv']      = true;
        $params['containerDivClass'] = 'row no-margin';
        
        switch ($format) {
            case 'radio':
                $params['options']     = explode(';', $params['data']);
                break;
            case 'switch':
                $params['options']     = explode(';', $params['data']);
                break;
            case 'checkbox':
                $params['options']     = explode(';', $params['data']);
                break;
            case 'date_select':
                $params['placeholder'] = 'Please select a date';
                break;
            case 'date_text':
                $params['placeholder'] = 'Please select a date';
                break;
            case 'date':
                $params['placeholder'] = 'Please select a date';
                break;
            case 'select':
                $params['options']     = explode(';', $params['data']);
                break;
            default:
        }
        
        $mandatoryFieldsObj->setContainerDivClass($params['containerDivClass']);
        $mandatoryFieldsObj->setContainerDiv($params['containerDiv']);
        if (isset($params['options'])) {
            $mandatoryFieldsObj->setOptions($params['options']);
        }
        if (isset($params['placeholder'])) {
            $mandatoryFieldsObj->setPlaceholder($params['placeholder']);
        }
        
        return $mandatoryFieldsObj;
    }
    /*
     * This method checks if there is a manadatory field and save its answers to deal_booking_quote table
     *
     * @param $fieldsObj
     *
     * @return uuid
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */
    
    public function saveMandatoryFieldAnswers($fieldsObj)
    {
        $return = array();
        if ($fieldsObj->getFieldAnswers()) {
            $return = $this->em->getRepository('DealBundle:DealBookingQuote')->saveMandatoryFieldAnswers($fieldsObj);
        }
        
        if ($return) {
            $result = true;
        } else {
            $result = false;
        }
        
        return $this->createJsonResponse($result);
    }
    
    /**
     * This method is used to format all the methods responses with standard json
     *
     * param array of results
     * @return json return with standard formatting: success, message, code, data
     */
    private function createJsonResponse($result = array())
    {
        $translator = $this->container->get('translator');
        if (empty($result)) {
            $responseArray['success'] = false;
            $responseArray['message'] = $translator->trans('No data returned from API');
            $responseArray['code']    = '';
            $responseArray['data']    = '';
        } else {
            if ((isset($result['errorAPIMessage']) && !empty($result['errorAPIMessage']))) {
                $responseArray['success'] = false;
                $responseArray['message'] = $translator->trans($result['errorAPIMessage']);
                $responseArray['code']    = '';
                $responseArray['data']    = '';
            } elseif (isset($result['errorCode']) && !empty($result['errorCode'])) {
                $responseArray['success'] = false;
                $responseArray['message'] = $translator->trans($result['errorMessage']);
                $responseArray['code']    = $result['errorCode'];
                $responseArray['data']    = '';
            } else {
                $responseArray['success'] = true;
                $responseArray['message'] = '';
                $responseArray['code']    = '';
                $responseArray['data']    = $result;
            }
        }
        $response = new Response(json_encode($responseArray));
        $response->headers->set('Content-Type', 'application/json');
        
        return json_encode($responseArray);
    }
    /*
     * This calls getPackageById in packagesRepository
     *
     * @param $packageId
     * @param $langCode defaults 'en'
     *
     * @return array of DealDetails
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getPackageById($packageId, $langCode = 'en')
    {
        $response = $this->em->getRepository('DealBundle:DealDetails')->getPackageById($packageId, $langCode);
        return $this->createJsonResponse($response);
    }
    /*
     * This calls getPackageById in packagesRepository
     *
     * @param $packageId
     * @param $langCode defaults 'en'
     *
     * @return array of DealDetails
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getPackageByDealCode($dealCode)
    {
        $response = array();
        $details  = $this->em->getRepository('DealBundle:DealDetails')->findOneByDealCode($dealCode);
        if ($details) {
            $result   = $details->toArray();
            $response = $this->em->getRepository('DealBundle:DealDetails')->getPackageById($result['id']);
        }
        return $this->createJsonResponse($response);
    }
    /*
     * This gets all countries in CmsCountries
     *
     * @return array of Countries
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getCountryList()
    {
        $response = $this->container->get('CmsCountriesServices')->getCountryList();
        return $this->createJsonResponse($response);
    }
    /*
     * This gets all countries in CmsCountries
     *
     * @return array of Countries
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getMobileCountryCodeList()
    {
        $response = $this->container->get('CmsCountriesServices')->getMobileCountryCodeList();
        return $this->createJsonResponse($response);
    }
    /*
     * This get Booking Quote data by id
     *
     * @param $id
     *
     * @return array of DealBooking quote data
     * @author Firas Bou Karroum <anna.parejo@touristtube.com>
     */
    
    public function findBookingQuoteById($id)
    {
        $response = array();
        $quote    = $this->em->getRepository('DealBundle:DealBookingQuote')->findOneById($id);
        if ($quote) {
            $response = $quote->toArray();
        }
        return $this->createJsonResponse($response);
    }
    /*
     * This get Booking data by id
     *
     * @param $id
     *
     * @return array of DealBooking data
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function findBookingById($id)
    {
        $response = array();
        $booking  = $this->em->getRepository('DealBundle:DealBooking')->findOneById($id);
        if ($booking) {
            $response = $booking->toArray();
        }
        return $this->createJsonResponse($response);
    }
    /*
     * This get Booking data by uuid
     *
     * @param $uuid
     *
     * @return array of DealBooking data
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function findBookingByUuid($uuid)
    {
        $dealBookingObj = $this->em->getRepository('DealBundle:DealBooking')->findOneByPaymentUuid($uuid);
        
        $response = array();
        if ($dealBookingObj) {
            $response = $dealBookingObj->toArray();
        }
        
        return $this->createJsonResponse($response);
    }
    /*
     * This get Payment data by uuid
     *
     * @param $uuid
     *
     * @return array of DealBooking data
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function findPaymentByUuid($uuid)
    {
        return $this->paymentServiceImpl->getPaymentByUUID($uuid);
    }
    /*
     * This get Booking Details depending on the passed parameters
     *
     * @param $params
     * @param $langCode defaults 'en'
     *
     * @return array of DealBooking
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    /*
     * This get Booking data by booking reference
     *
     * @param $bookingReference
     *
     * @return array of DealBooking data
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */
    
    public function findBookingByReference($bookingReference)
    {
        $response = array();
        $booking  = $this->em->getRepository('DealBundle:DealBooking')->findOneByBookingReference($bookingReference);
        if ($booking) {
            $response = $booking->toArray();
        }
        return $this->createJsonResponse($response);
    }
    /*
     * This get Booking details
     *
     * @param $bookingObj
     *
     * @return array of DealBooking data
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */
    
    public function getBookingDetails($bookingObj, $langCode = 'en')
    {
        $response = $this->em->getRepository('DealBundle:DealBooking')->getBookingDetails($bookingObj, $langCode);
        
        $selectedCurrency                = ($bookingObj->getSelectedCurrency()) ? $bookingObj->getSelectedCurrency() : 'USD';
        $conversionRate                  = $this->currencyService->getConversionRate('USD', $selectedCurrency);
        $newConvertedPrice               = $this->currencyService->currencyConvert($response['db_totalPrice'], $conversionRate);
        $response['convertedTotalPrice'] = $newConvertedPrice;
        $response['formattedTotalPrice'] = number_format($newConvertedPrice, 2, '.', ',');
        $response['selectedCurrency']    = $selectedCurrency;
        
        return $this->createJsonResponse($response);
    }
    /*
     * This get Booking Details depending on the passed parameters
     *
     * @param $id
     *
     * @return array of DealCity data
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getDealCityById($id)
    {
        $response = array();
        $city     = $this->em->getRepository('DealBundle:DealCity')->findOneById($id);
        if ($city) {
            $response = $city->toArray();
        }
        return $this->createJsonResponse($response);
    }
    /*
     * This get all records from tt_transfer_type table
     *
     * @return array of transferType
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getAllTtTransferType()
    {
        $response     = array();
        $transferType = $this->em->getRepository('DealBundle:TTTransferType')->findAll();
        
        foreach ($transferType as $key => $val) {
            $name                           = strtolower($val->getName());
            $response[$name]['description'] = $val->getDescription();
            $response[$name]['image']       = $val->getImage();
        }
        return $this->createJsonResponse($response);
    }
    /*
     * This get Booking Details depending on the passed parameters
     *
     * @return array of DealBooking
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function updateBookingData($bookingObj)
    {
        if (!$bookingObj->getBookingId()) {
            return false;
        }
        
        $response = $this->em->getRepository('DealBundle:DealBooking')->updateBookingData($bookingObj);
        return $this->createJsonResponse($response);
    }
    /*
     * This will get ApiSupllier data per param being passed
     *
     * @param $value
     * @param $field ('id', 'name')
     *
     * @return array of ApiSupllier
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getDealApiSupplierByParam($value, $field = 'id')
    {
        $return = array();
        if ($value) {
            switch (strtolower($field)) {
                case 'name':
                    $return = $this->em->getRepository('DealBundle:DealApiSupplier')->findOneByName($value)->toArray();
                    break;
                default:
                    $return = $this->em->getRepository('DealBundle:DealApiSupplier')->findOneById($value)->toArray();
            }
        }
        
        return $this->createJsonResponse($return);
    }
    /*
     * This will get DealType data per param being passed
     *
     * @param $value
     * @param $field ('id', 'name')
     *
     * @return array of DealType
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getDealTypeByParam($value, $field = 'id')
    {
        $return = array();
        if ($value) {
            switch (strtolower($field)) {
                case 'name':
                    $return = $this->em->getRepository('DealBundle:DealType')->findOneByName($value)->toArray();
                    break;
                default:
                    $return = $this->em->getRepository('DealBundle:DealType')->findOneById($value)->toArray();
            }
        }
        
        return $this->createJsonResponse($return);
    }
    /*
     * This will get DealBookingQuote data per param being passed
     *
     * @param $value
     * @param $field ('id', 'name')
     *
     * @return array of DealBookingQuote
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getBookingQuotesById($id = array())
    {
        $return = array();
        if ($id) {
            $quote = $this->em->getRepository('DealBundle:DealBookingQuote')->findBy(array('id' => $id));
            foreach ($quote as $key => $val) {
                $return[] = $val->toArray();
            }
        }
        
        return $this->createJsonResponse($return);
    }
    
    /**
     * This method creates a DealSC object
     *
     * @param array $criteria
     *
     * @return object DealSC instance
     */
    public function getDealSearchCriteria($criteria)
    {
        $dealSC = new DealSC();
        if (!empty($criteria)) {
            $dealSC->setIsCorpo(isset($criteria['isCorpo']) ? $criteria['isCorpo'] : 0);
            $dealSC->setAttractions(isset($criteria['attractions']) ? $criteria['attractions'] : array());
            $dealSC->setApiSupplierId(isset($criteria['apiSupplierId']) ? $criteria['apiSupplierId'] : array());
            $dealSC->setLangCode(isset($criteria['langCode']) ? $criteria['langCode'] : 'en');
            $dealSC->getCommonSC()->getCity()->setId(isset($criteria['city']) ? $criteria['city'] : '');
            $dealSC->getCommonSC()->getCity()->setName(isset($criteria['cityName']) ? $criteria['cityName'] : '');
            $dealSC->getCommonSC()->getPackage()->setStartDate(isset($criteria['startDate']) ? $criteria['startDate'] : '');
            $dealSC->getCommonSC()->getPackage()->setEndDate(isset($criteria['endDate']) ? $criteria['endDate'] : '');
            $dealSC->setDynamicSorting(isset($criteria['dynamicSorting']) ? $criteria['dynamicSorting'] : false);
            $dealSC->setLimit(isset($criteria['limit']) ? $criteria['limit'] : 0);
            $dealSC->setPriority(isset($criteria['priority']) ? $criteria['priority'] : 0);
            $dealSC->setCategory(isset($criteria['category']) ? $criteria['category'] : 'all');
            $dealSC->setSearchAll(isset($criteria['searchAll']) ? $criteria['searchAll'] : false);
            $dealSC->getCommonSC()->getPackage()->setName(isset($criteria['dealName']) ? $criteria['dealName'] : '');
            $dealSC->setDealNameSearch(isset($criteria['dealNameSearch']) ? $criteria['dealNameSearch'] : '');
            $dealSC->setMinPrice(isset($criteria['minPrice']) ? $criteria['minPrice'] : '');
            $dealSC->setMaxPrice(isset($criteria['maxPrice']) ? $criteria['maxPrice'] : '');
            $dealSC->setAllTypes(isset($criteria['allTypes']) ? $criteria['allTypes'] : false);
            $dealSC->setOffSet(isset($criteria['offSet']) ? $criteria['offSet'] : '');
            $dealSC->setHasSearch(isset($criteria['hasSearch']) ? $criteria['hasSearch'] : false);
            $dealSC->setMaxResults(isset($criteria['maxResults']) ? $criteria['maxResults'] : 0);
            $dealSC->setSelectedCurrency(isset($criteria['selectedCurrency']) ? $criteria['selectedCurrency'] : 'USD');
            $dealSC->setCategoryIds(isset($criteria['categoryIds']) ? $criteria['categoryIds'] : array());
        }
        return $dealSC;
    }
    
    /**
     * This method creates a DealCancellationPolicy object
     *
     * @param array $criteria
     *
     * @return array of objects DealCancellationPolicy instance
     */
    public function getDealCancellationPolicy($criteria)
    {
        $cancellationPolicy = array();
        if (!empty($criteria)) {
            foreach ($criteria as $key => $val) {
                $dealCancellationPolicy = new DealCancellationPolicy();
                $dealCancellationPolicy->setCancellationDay(isset($val['cancellationDay']) ? $val['cancellationDay'] : '');
                $dealCancellationPolicy->setCancellationDiscount(isset($val['cancellationDiscount']) ? $val['cancellationDiscount'] : '');
                $cancellationPolicy[]   = $dealCancellationPolicy;
            }
        }
        return $cancellationPolicy;
    }
    
    /**
     * This method get Deal Image
     *
     * @param array $criteria
     *
     * @return array of objects DealImage instance
     */
    public function getDealImageCriteria($criteria)
    {
        $imageObj = new DealImages();
        if (!empty($criteria)) {
            $imageObj->setDirectory(isset($criteria['directory']) ? $criteria['directory'] : '');
            $imageObj->getCommonSC()->getPackage()->setApiId(isset($criteria['apiId']) ? $criteria['apiId'] : '');
            $imageObj->getCommonSC()->getPackage()->setCode(isset($criteria['dealCode']) ? $criteria['dealCode'] : '');
            $imageObj->getCommonSC()->getPackage()->setTypeName(isset($criteria['categoryName']) ? $criteria['categoryName'] : '');
            $imageObj->getCommonSC()->getCity()->setName(isset($criteria['cityName']) ? $criteria['cityName'] : '');
            $imageObj->setImageId(isset($criteria['imageId']) ? $criteria['imageId'] : '');
            $imageObj->setIsThumbnail(isset($criteria['isThumbnail']) ? $criteria['isThumbnail'] : '');
            $imageObj->setImgWidth(isset($criteria['imgWidth']) ? $criteria['imgWidth'] : '');
            $imageObj->setImgHeight(isset($criteria['imgHeight']) ? $criteria['imgHeight'] : '');
            $imageObj->setImage(isset($criteria['image']) ? $criteria['image'] : '');
        }
        return $imageObj;
    }
    
    /**
     * This method creates a DealPriceOption object
     *
     * @param array $criteria
     *
     * @return of objects DealPriceOption instance
     */
    public function getDealPriceOptionCriteria($criteria)
    {
        $priceOption = new DealPriceOption();
        if (!empty($criteria)) {
            $priceOption->getCommonSC()->getPackage()->setId(isset($criteria['packageId']) ? $criteria['packageId'] : '');
            $priceOption->getCommonSC()->getPackage()->setCode(isset($criteria['tourCode']) ? $criteria['tourCode'] : '');
            $priceOption->getCommonSC()->getPackage()->setStartDate(isset($criteria['startDate']) ? $criteria['startDate'] : '');
            $priceOption->getCommonSC()->getPackage()->setCurrency(isset($criteria['currency']) ? $criteria['currency'] : '');
            $priceOption->getCommonSC()->getPackage()->setName(isset($criteria['dealName']) ? $criteria['dealName'] : '');
            $priceOption->setPriceOptions(isset($criteria['priceOptions']) ? $criteria['priceOptions'] : '');
        }
        return $priceOption;
    }
    
    /**
     * This method creates a DealPriceOption object
     *
     * @param array $criteria
     *
     * @return of objects DealBooking instance
     */
    public function getDealBookingCriteria($criteria)
    {
        $booking = new DealBookingResponse();
        if (!empty($criteria)) {
            $booking->getCommonSC()->getPackage()->setCurrency(isset($criteria['currency']) ? $criteria['currency'] : '');
            $booking->getCommonSC()->getPackage()->setName(isset($criteria['dealName']) ? $criteria['dealName'] : '');
            $booking->getCommonSC()->getPackage()->setCode(isset($criteria['tourCode']) ? $criteria['tourCode'] : '');
            $booking->getCommonSC()->getPackage()->setApiId(isset($criteria['apiId']) ? $criteria['apiId'] : '');
            $booking->getCommonSC()->getPackage()->setId(isset($criteria['packageId']) ? $criteria['packageId'] : 0);
            $booking->getCommonSC()->getPackage()->setDescription(isset($criteria['dealDescription']) ? $criteria['dealDescription'] : '');
            $booking->getCommonSC()->getPackage()->setHighlights(isset($criteria['dealHighlights']) ? $criteria['dealHighlights'] : '');
            $booking->getCommonSC()->getPackage()->setTypeId(isset($criteria['dealTypeId']) ? $criteria['dealTypeId'] : 0);
            $booking->getCommonSC()->getCountry()->setCode(isset($criteria['country']) ? $criteria['country'] : '');
            $booking->getCommonSC()->getCountry()->setId(isset($criteria['countryId']) ? $criteria['countryId'] : 0);
            $booking->getCommonSC()->getCity()->setId(isset($criteria['dealCityId']) ? $criteria['dealCityId'] : 0);
            $booking->setBookingDate(isset($criteria['bookingDate']) ? $criteria['bookingDate'] : '');
            $booking->setStartTime(isset($criteria['startTime']) ? $criteria['startTime'] : '');
            $booking->setEndTime(isset($criteria['endTime']) ? $criteria['endTime'] : '');
            $booking->setBookingReference(isset($criteria['bookingReference']) ? $criteria['bookingReference'] : '');
            $booking->setBookingStatus(isset($criteria['bookingStatus']) ? $criteria['bookingStatus'] : '');
            $booking->setBookingEmail(isset($criteria['email']) ? $criteria['email'] : '');
            $booking->setPriceId(isset($criteria['priceId']) ? $criteria['priceId'] : '');
            $booking->setFirstName(isset($criteria['firstName']) ? $criteria['firstName'] : '');
            $booking->setLastName(isset($criteria['lastName']) ? $criteria['lastName'] : '');
            $booking->setBookingVoucherInformation(isset($criteria['bookingVoucherInformation']) ? $criteria['bookingVoucherInformation'] : '');
            $booking->setSelectedCurrency(isset($criteria['selectedCurrency']) ? $criteria['selectedCurrency'] : '');
            $booking->setBookingId(isset($criteria['bookingId']) ? $criteria['bookingId'] : '');
            $booking->setDbFields(isset($criteria['dbFields']) ? $criteria['dbFields'] : '');
            $booking->setLeftJoinTransfers(isset($criteria['leftJoinTransfers']) ? true : false);
            $booking->setUuid(isset($criteria['uuid']) ? $criteria['uuid'] : '');
            $booking->setAddress(isset($criteria['ccBillingAddress']) ? $criteria['ccBillingAddress'] : '');
            $booking->setNumOfAdults(isset($criteria['numOfAdults']) ? $criteria['numOfAdults'] : 0);
            $booking->setTotalPrice(isset($criteria['totalPrice']) ? $criteria['totalPrice'] : 00.00);
            $booking->setUserId(isset($criteria['userId']) ? $criteria['userId'] : 0);
            $booking->setTransactionSourceId(isset($criteria['transactionSourceId']) ? $criteria['transactionSourceId'] : 0);
            $booking->setBookingQuoteId(isset($criteria['bookingQuoteId']) ? $criteria['bookingQuoteId'] : 0);
            $booking->setBookingNotes(isset($criteria['bookingNotes']) ? $criteria['bookingNotes'] : '');
            $booking->setCancellationPolicy(isset($criteria['cancellationPolicy']) ? $criteria['cancellationPolicy'] : '');
            $booking->setTitle(isset($criteria['title']) ? $criteria['title'] : '');
            $booking->setDialingCode(isset($criteria['dialingCode']) ? $criteria['dialingCode'] : '');
            $booking->setMobile(isset($criteria['mobile']) ? $criteria['mobile'] : '');
            $booking->setPostalCode(isset($criteria['postalCode']) ? $criteria['postalCode'] : '');
            $booking->setNumOfChildren(isset($criteria['numOfChildren']) ? $criteria['numOfChildren'] : 0);
            $booking->setNumOfInfants(isset($criteria['numOfInfants']) ? $criteria['numOfInfants'] : 0);
            $booking->setDuration(isset($criteria['duration']) ? $criteria['duration'] : '');
            $booking->setStartingPlace(isset($criteria['startingPlace']) ? $criteria['startingPlace'] : '');
            $booking->setBookingTime(isset($criteria['bookingTime']) ? $criteria['bookingTime'] : '');
            $booking->setAmountFBC(isset($criteria['amountFBC']) ? $criteria['amountFBC'] : 00.00);
            $booking->setAmountSBC(isset($criteria['amountSBC']) ? $criteria['amountSBC'] : 00.00);
            $booking->setAccountCurrencyAmount(isset($criteria['accountCurrencyAmount']) ? $criteria['accountCurrencyAmount'] : 00.00);
            $booking->setOnAccountCCType(isset($criteria['onAccountCCType']) ? $criteria['onAccountCCType'] : '');
            $booking->setUserAgent(isset($criteria['userAgent']) ? $criteria['userAgent'] : '');
            $booking->setCustomerIP(isset($criteria['customerIP']) ? $criteria['customerIP'] : '');
            $booking->setLangCode(isset($criteria['langCode']) ? $criteria['langCode'] : 'en');
            $booking->setPreferredCurrency(isset($criteria['preferredCurrency']) ? $criteria['preferredCurrency'] : '');
            $booking->setAge(isset($criteria['age']) ? $criteria['age'] : '');
            $booking->setPaymentRequired(isset($criteria['paymentRequired']) ? $criteria['paymentRequired'] : false);
            $booking->setAccountId(isset($criteria['accountId']) ? $criteria['accountId'] : '');
            $booking->setTransactionUserId(isset($criteria['transactionUserId']) ? $criteria['transactionUserId'] : '');
            $booking->setRequestServicesDetailsId(isset($criteria['requestServicesDetailsId']) ? $criteria['requestServicesDetailsId'] : '');
            $booking->setIsAvailable(isset($criteria['isAvailable']) ? $criteria['isAvailable'] : false);
            $booking->setReservationId(isset($criteria['reservationId']) ? $criteria['reservationId'] : 0);
            
            //this is for transfer
            if (isset($criteria['dealTypeId']) && $criteria['dealTypeId'] == $this->container->getParameter('DEAL_TYPE_TRANSFERS')) {
                $booking->getCommonSC()->getCountry()->setCode(isset($criteria['destinationCountry']) ? $criteria['destinationCountry'] : '');
                $booking->getCommonSC()->getCity()->setName(isset($criteria['destinationCity']) ? $criteria['destinationCity'] : '');
                $booking->setNumOfpassengers(isset($criteria['numOfpassengers']) ? $criteria['numOfpassengers'] : 0);
                $booking->getArrivalDeparture()->setDepartureHour(isset($criteria['departureHour']) ? $criteria['departureHour'] : 0);
                $booking->getArrivalDeparture()->setDepartureMinute(isset($criteria['departureMinute']) ? $criteria['departureMinute'] : 0);
                $booking->getArrivalDeparture()->setArrivalPriceId(isset($criteria['arrivalPriceId']) ? $criteria['arrivalPriceId'] : 0);
                $booking->getArrivalDeparture()->setDeparturePriceId(isset($criteria['departurePriceId']) ? $criteria['departurePriceId'] : 0);
                $booking->getAirport()->setName(isset($criteria['transferAirportName']) ? $criteria['transferAirportName'] : '');
                $booking->getAirport()->setCode(isset($criteria['transferAirportCode']) ? $criteria['transferAirportCode'] : '');
                $booking->setDestinationAddress(isset($criteria['destinationAddress']) ? $criteria['destinationAddress'] : '');
                $booking->getTypeOfTransfer()->setName(isset($criteria['typeOfTransfer']) ? $criteria['typeOfTransfer'] : '');
                $booking->getArrivalDeparture()->setArrivalCompany(isset($criteria['arrivalCompany']) ? $criteria['arrivalCompany'] : '');
                $booking->getArrivalDeparture()->setArrivalFrom(isset($criteria['arrivingFrom']) ? $criteria['arrivingFrom'] : '');
                $booking->getArrivalDeparture()->setArrivalDestinationAddress(isset($criteria['arrivalCompleteAddress']) ? $criteria['arrivalCompleteAddress'] : '');
                $booking->setGoingTo(isset($criteria['goingTo']) ? $criteria['goingTo'] : '');
                $booking->getArrivalDeparture()->setDepartureDestinationAddress(isset($criteria['departureCompleteAddress']) ? $criteria['departureCompleteAddress'] : '');
                $booking->setServiceType(isset($criteria['serviceType']) ? $criteria['serviceType'] : '');
                $booking->setCarModel(isset($criteria['carModel']) ? $criteria['carModel'] : '');
                $booking->getArrivalDeparture()->setDepartureDate(isset($criteria['departureInput']) ? $criteria['departureInput'] : '');
                $booking->getArrivalDeparture()->setArrivalDate(isset($criteria['arrivalInput']) ? $criteria['arrivalInput'] : '');
                $booking->getArrivalDeparture()->setDepartureCompany(isset($criteria['departureCompany']) ? $criteria['departureCompany'] : '');
                $booking->getArrivalDeparture()->setArrivalHour(isset($criteria['arrivalHour']) ? $criteria['arrivalHour'] : '');
                $booking->getArrivalDeparture()->setArrivalMinute(isset($criteria['arrivalMinute']) ? $criteria['arrivalMinute'] : '');
            }
        }
        return $booking;
    }
    
    /**
     * This method getQuotation
     *
     * @param array $criteria
     *
     * @return array of objects DealQuotation instance
     */
    public function getDealQuotationCriteria($criteria)
    {
        $quoteObj = new DealQuote();
        
        if (!empty($criteria)) {
            $quoteObj->getCommonSC()->getPackage()->setId(isset($criteria['packageId']) ? $criteria['packageId'] : '');
            $quoteObj->getCommonSC()->getPackage()->setCode(isset($criteria['tourCode']) ? $criteria['tourCode'] : '');
            $quoteObj->setActivityPriceId(isset($criteria['activityPriceId']) ? $criteria['activityPriceId'] : '');
            $quoteObj->getCommonSC()->getPackage()->setStartDate(isset($criteria['bookingDate']) ? $criteria['bookingDate'] : '');
            $quoteObj->setPriceId(isset($criteria['priceId']) ? $criteria['priceId'] : '');
            $quoteObj->getCommonSC()->getPackage()->setCurrency(isset($criteria['currency']) ? $criteria['currency'] : '');
            
            $units = array();
            if (isset($criteria['units'])) {
                foreach ($criteria['units'] as $key => $val) {
                    $unitsObj = new DealUnit();
                    $unitsObj->setUnitId(isset($val['unitId']) ? $val['unitId'] : '');
                    $unitsObj->setQuantity(isset($val['qty']) ? $val['qty'] : '');
                    
                    $units[] = $unitsObj;
                }
                $quoteObj->setUnits($units);
            }
        }
        return $quoteObj;
    }
    
    /**
     * This method DealMandatoryFields
     *
     * @param array $criteria
     *
     * @return array of objects DealMandatoryFields instance
     */
    public function getDealMandatoryFieldsCriteria($criteria)
    {
        $mandatoryFields = new DealMandatoryFields();
        if (!empty($criteria)) {
            $mandatoryFields->setLabel(isset($criteria['label']) ? $criteria['label'] : '');
            $mandatoryFields->setFieldId(isset($criteria['fieldId']) ? $criteria['fieldId'] : '');
            $mandatoryFields->setFormat(isset($criteria['format']) ? $criteria['format'] : '');
            $mandatoryFields->setData(isset($criteria['data']) ? $criteria['data'] : '');
            $mandatoryFields->setFieldName(isset($criteria['fieldName']) ? $criteria['fieldName'] : '');
            $mandatoryFields->setContainerDiv(isset($criteria['containerDiv']) ? $criteria['containerDiv'] : '');
            $mandatoryFields->setContainerDivClass(isset($criteria['containerDivClass']) ? $criteria['containerDivClass'] : '');
            $mandatoryFields->setOptions(isset($criteria['options']) ? $criteria['options'] : '');
            $mandatoryFields->setPlaceholder(isset($criteria['placeholder']) ? $criteria['placeholder'] : '');
            $mandatoryFields->setBookingQuoteId(isset($criteria['bookingQuoteId']) ? $criteria['bookingQuoteId'] : '');
            
            foreach ($criteria as $key => $val) {
                $nameKey = explode('_', $key);
                
                switch ($nameKey[0]) {
                    case 'mandatory':
                        $counter = count($nameKey);
                        switch ($counter) {
                            case 4:
                                $criteria['mandatoryFields']['perPerson'][$nameKey[1]][$nameKey[2]][] = $val;
                                break;
                            default:
                                $criteria['mandatoryFields']['perBooking'][$nameKey[1]]               = $val;
                        }
                        break;
                    default:
                        break;
                }
            }
            $mandatoryFields->setFieldAnswers(isset($criteria['mandatoryFields']) ? json_encode(array('Mandatory' => $criteria['mandatoryFields'])) : '');
        }
        
        return $mandatoryFields;
    }
    
    /**
     * This method getDealTransferVehiclesListing
     *
     * @param array $criteria
     *
     * @return array of objects DealTransferVehiclesListing instance
     */
    public function getDealTransferVehiclesListingCriteria($criteria)
    {
        $transferObj = new DealTransferVehiclesListing();
        if (!empty($criteria)) {
            $transferObj->getCountry()->setCode(isset($criteria['destinationCountry']) ? $criteria['destinationCountry'] : '');
            $transferObj->getCity()->setName(isset($criteria['destinationCity']) ? $criteria['destinationCity'] : '');
            $transferObj->getDealAirport()->setCode(isset($criteria['airportCode']) ? $criteria['airportCode'] : '');
            $transferObj->getTypeOfTransfer()->setName(isset($criteria['typeOfTransfer']) ? $criteria['typeOfTransfer'] : '');
            $transferObj->getTypeOfTransfer()->setCode(isset($criteria['typeOfTransferCode']) ? $criteria['typeOfTransferCode'] : '');
            $transferObj->setNumOfPersons(isset($criteria['numOfPassengers']) ? $criteria['numOfPassengers'] : '');
            $transferObj->getArrivalDeparture()->setArrivalDate(isset($criteria['arrivalInput']) ? $criteria['arrivalInput'] : '');
            $transferObj->getArrivalDeparture()->setArrivalHour(isset($criteria['arrivalHour']) ? $criteria['arrivalHour'] : '');
            $transferObj->getArrivalDeparture()->setArrivalMinute(isset($criteria['arrivalMinute']) ? $criteria['arrivalMinute'] : '');
            $transferObj->getArrivalDeparture()->setDepartureDate(isset($criteria['departureInput']) ? $criteria['departureInput'] : '');
            $transferObj->getArrivalDeparture()->setDepartureHour(isset($criteria['departureHour']) ? $criteria['departureHour'] : '');
            $transferObj->getArrivalDeparture()->setDepartureMinute(isset($criteria['departureMinute']) ? $criteria['departureMinute'] : '');
            $transferObj->setCheckAvailability(isset($criteria['checkAvailability']) ? $criteria['checkAvailability'] : false);
            $transferObj->setSelectedCurrency(isset($criteria['selectedCurrency']) ? $criteria['selectedCurrency'] : '');
            $transferObj->getArrivalDeparture()->setArrivalTime(isset($criteria['arrivalTime']) ? $criteria['arrivalTime'] : '');
            $transferObj->getArrivalDeparture()->setDepartureTime(isset($criteria['departureTime']) ? $criteria['departureTime'] : '');
        }
        return $transferObj;
    }
    
    /**
     * This method DealTransferAirportListing
     *
     * @param array $criteria
     *
     * @return array of objects DealTransferAirportListing instance
     */
    public function getDealTransferAirportListingCriteria($criteria)
    {
        $airportObj = new DealTransferAirportListing();
        if (!empty($criteria)) {
            $airportObj->getCountry()->setCode(isset($criteria['country']) ? $criteria['country'] : '');
            $airportObj->getCity()->setName(isset($criteria['city']) ? $criteria['city'] : '');
            $airportObj->setCode(isset($criteria['code']) ? $criteria['code'] : '');
            $airportObj->setName(isset($criteria['name']) ? $criteria['name'] : '');
            $airportObj->setType(isset($criteria['type']) ? $criteria['type'] : '');
        }
        return $airportObj;
    }
    
    /**
     * This method creates a DealPriceOption object
     *
     * @param array $criteria
     *
     * @return of objects DealBooking instance
     */
    public function getDealTransferBookingCriteria($criteria)
    {
        $transport = new DealTransferBooking();
        if (!empty($criteria)) {
            $transport->getBookingResponse()->getCommonSC()->getPackage()->setCode(isset($criteria['productCode']) ? $criteria['productCode'] : '');
            $transport->setServiceType(isset($criteria['serviceType']) ? $criteria['serviceType'] : '');
            $transport->setServiceCode(isset($criteria['serviceCode']) ? $criteria['serviceCode'] : '');
            $transport->getBookingResponse()->setBookingReference(isset($criteria['bookingReference']) ? $criteria['bookingReference'] : '');
            $transport->getBookingResponse()->setBookingStatus(isset($criteria['bookingStatus']) ? $criteria['bookingStatus'] : '');
            $transport->getBookingResponse()->setFirstName(isset($criteria['firstName']) ? $criteria['firstName'] : '');
            $transport->getBookingResponse()->setLastName(isset($criteria['lastName']) ? $criteria['lastName'] : '');
            $transport->getBookingResponse()->setBookingEmail(isset($criteria['email']) ? $criteria['email'] : '');
            $transport->getBookingResponse()->setBookingVoucherInformation(isset($criteria['bookingVoucherInformation']) ? $criteria['bookingVoucherInformation'] : '');
            $transport->getBookingResponse()->setTotalPrice(isset($criteria['bookingTotal']) ? $criteria['bookingTotal'] : '');
            $transport->getBookingResponse()->getCommonSC()->getPackage()->setCurrency(isset($criteria['currency']) ? $criteria['currency'] : '');
            $transport->setCreditCardTransactionType(isset($criteria['creditCardTransactionType']) ? $criteria['creditCardTransactionType'] : '');
            $transport->setCreditCardTransactionID(isset($criteria['creditCardTransactionID']) ? $criteria['creditCardTransactionID'] : '');
            $transport->getBookingResponse()->setAmountFBC(isset($criteria['amountFBC']) ? $criteria['amountFBC'] : '');
            $transport->getBookingResponse()->setAmountSBC(isset($criteria['amountSBC']) ? $criteria['amountSBC'] : '');
            $transport->getBookingResponse()->setAccountCurrencyAmount(isset($criteria['amountACCurrency']) ? $criteria['amountACCurrency'] : '');
            $transport->getArrivalDeparture()->setArrivalPriceId(isset($criteria['arrivalPriceId']) ? $criteria['arrivalPriceId'] : '');
            $transport->getArrivalDeparture()->setArrivalTime(isset($criteria['arrivalTime']) ? $criteria['arrivalTime'] : '');
            $transport->getArrivalDeparture()->setArrivalDate(isset($criteria['arrivalInput']) ? $criteria['arrivalInput'] : '');
            $transport->getArrivalDeparture()->setArrivalFlightDetails(isset($criteria['arrivalFlightDetails']) ? $criteria['arrivalFlightDetails'] : '');
            $transport->getArrivalDeparture()->setArrivalDestinationAddress(isset($criteria['arrivalCompleteAddress']) ? $criteria['arrivalCompleteAddress'] : '');
            $transport->getArrivalDeparture()->setArrivalFrom(isset($criteria['arrivingFrom']) ? $criteria['arrivingFrom'] : '');
            $transport->getArrivalDeparture()->setDeparturePriceId(isset($criteria['departurePriceId']) ? $criteria['departurePriceId'] : '');
            $transport->getArrivalDeparture()->setDepartureTime(isset($criteria['departureTime']) ? $criteria['departureTime'] : '');
            $transport->getArrivalDeparture()->setDepartureDate(isset($criteria['departureInput']) ? $criteria['departureInput'] : '');
            $transport->getArrivalDeparture()->setDepartureFlightDetails(isset($criteria['departureFlightDetails']) ? $criteria['departureFlightDetails'] : '');
            $transport->getArrivalDeparture()->setDepartureDestinationAddress(isset($criteria['departureCompleteAddress']) ? $criteria['departureCompleteAddress'] : '');
            $transport->setGoingTo(isset($criteria['goingTo']) ? $criteria['goingTo'] : '');
            $transport->getBookingResponse()->setPostalCode(isset($criteria['postalCode']) ? $criteria['postalCode'] : '');
            $transport->getBookingResponse()->setMobile(isset($criteria['mobile']) ? $criteria['mobile'] : '');
            $transport->setCcBillingAddress(isset($criteria['ccBillingAddress']) ? $criteria['ccBillingAddress'] : '');
            $transport->setNumOfpassengers(isset($criteria['numOfpassengers']) ? $criteria['numOfpassengers'] : '');
            $transport->getBookingResponse()->setBookingNotes(isset($criteria['bookingNotes']) ? $criteria['bookingNotes'] : '');
            $transport->getBookingResponse()->getCommonSC()->getCity()->setName(isset($criteria['city']) ? $criteria['city'] : '');
            $transport->getBookingResponse()->getCommonSC()->getCountry()->setCode(isset($criteria['country']) ? $criteria['country'] : '');
            $transport->getArrivalDeparture()->setArrivalPickupDate(isset($criteria['arrivalPickupDate']) ? $criteria['arrivalPickupDate'] : '');
            $transport->getArrivalDeparture()->setDeparturePickupDate(isset($criteria['departurePickupDate']) ? $criteria['departurePickupDate'] : '');
        }
        return $transport;
    }
    
    /**
     * This method will get category list per API
     *
     * @param $api name
     *
     * @return category list
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */
    public function getCategoryList()
    {
        $return = $this->em->getRepository('DealBundle:DealCategory')->getCategoryList();
        return $this->createJsonResponse($return);
    }
    
    /**
     * This method will return an image link of a deal based on the preferred size of user
     *
     * @param $imageObj
     *
     * @return imagelink
     */
    public function returnDealsImageLink($imageObj)
    {
        $category = $this->utils->cleanTitleData($imageObj->getCommonSC()->getPackage()->getTypeName());
        $category = str_replace('+', '-', $category);
        $city     = $this->utils->cleanTitleData($imageObj->getCommonSC()->getCity()->getName());
        $city     = str_replace('+', '-', $city);
        
        $sourcename = $category."-".$city."-".$imageObj->getImageId().".jpg";
        $sourcepath = "media/deals/".$imageObj->getCommonSC()->getPackage()->getApiId()."/".$imageObj->getCommonSC()->getPackage()->getCode()."/";
        
        if ($imageObj->getIsThumbnail()) {
            $imgWidth  = ($imageObj->getImgWidth()) ? $imageObj->getImgWidth() : 255;
            $imgHeight = ($imageObj->getImgHeight()) ? $imageObj->getImgHeight() : 148;
            
            $dealImagePath = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, $imgWidth, $imgHeight, 'deals'.$imgWidth.$imgHeight, $sourcepath, $sourcepath, 65);
        } else {
            $dealImagePath = $this->container->get("TTRouteUtils")->generateMediaURL("/".$sourcepath.$sourcename);
        }
        
        if ($this->container->get("TTFileUtils")->fileExists($imageObj->getDirectory().$dealImagePath)) {
            return $dealImagePath;
        }
        
        return false;
    }
    
    /**
     * This method will return the next image link of a deal based on the preferred size of user
     *
     * @param array $imageObj - image object like directory
     * @param array $nextImgObj - next image object
     *
     * @return imagelink
     */
    public function returnNextDealImage($imageObj, $nextImgObj)
    {
        foreach ($nextImgObj as $val) {
            $category = $this->utils->cleanTitleData($val->getCommonSC()->getPackage()->getTypeName());
            $category = str_replace('+', '-', $category);
            
            $city = $this->utils->cleanTitleData($val->getCommonSC()->getCity()->getName());
            $city = str_replace('+', '-', $city);
            
            $sourcename = $category."-".$city."-".$val->getImage().'.jpg';
            $sourcepath = "media/deals/".$val->getCommonSC()->getPackage()->getApiId()."/".$val->getCommonSC()->getPackage()->getCode()."/";
            
            if ($imageObj->getIsThumbnail()) {
                $imgWidth  = $imageObj->getImgWidth() ? $imageObj->getImgWidth() : 255;
                $imgHeight = $imageObj->getImgHeight() ? $imageObj->getImgHeight() : 148;
                
                $nxtImagePath = $this->container->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, $imgWidth, $imgHeight, 'deals'.$imgWidth.$imgHeight, $sourcepath, $sourcepath, 65);
            } else {
                $dealImagePath = $this->container->get("TTRouteUtils")->generateMediaURL("/".$sourcepath.$sourcename);
            }
            
            if ($this->container->get("TTFileUtils")->fileExists($imageObj->getDirectory().$nxtImagePath)) {
                return $nxtImagePath;
            }
        }
        return false;
    }
    /*
     *
     * This method loops through all the deals from deal details and for each deal it checks
     * if this deal is still available from city discovery api response or not
     * if it is not available anymore then it sets the field published to -2
     *
     * @param closeEntityManagerOnCompletion bool to close the em manages connection or not
     *
     * @return
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function updateActivitiesAvailability($closeEntityManagerOnCompletion = false)
    {
        $activeDeals = $this->em->getRepository('DealBundle:DealDetails')->getPublishedActivities();
        foreach ($activeDeals as $activeDeal) {
            echo "\nChecking activity id:: ".$activeDeal["dd_id"];
            $availableDeal = $this->cityDiscoveryHandler->checkAvailability($activeDeal['dd_dealCode'])->toArray();
            //            $availableDeal = $this->checkAvailability($activeDeal["dd_dealCode"]);
            if ((isset($availableDeal['errorCode']) && !empty($availableDeal['errorCode'])) || (isset($availableDeal['errorAPIMessage']) && !empty($availableDeal['errorAPIMessage']) )) {
                
                if (isset($availableDeal['responseText'])) {
                    file_put_contents('/data/log/deals/scripts/activities/activity_availability_'.$activeDeal["dd_id"].'.log', $availableDeal['responseText']);
                    
                    unset($availableDeal['responseText']);
                }
                
                echo "\n This activity is not available anymore with id ".$activeDeal["dd_dealCode"].", availableDeal:: ".print_r($availableDeal, true);
                $this->logger->info(" This activity is not available anymore with id ".$activeDeal["dd_id"]);
                $this->em->getRepository('DealBundle:DealDetails')->updatePublished($activeDeal["dd_id"], -2);
            } else {
                echo "\n This activity is still available with id ".$activeDeal["dd_id"];
                $this->em->getRepository('DealBundle:DealDetails')->updatePublished($activeDeal["dd_id"], 1);
                $this->logger->info("This activity is still available with id ".$activeDeal["dd_id"]);
            }
        }
        
        if ($closeEntityManagerOnCompletion) $this->em->close();
    }
    
    /**
     *  Function is used to cancel booking and return cancellation fees
     *
     * @param bookingReference the booking reference
     *
     * @return array response
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    public function cancelBookingJson($reservationId)
    {
        $translator = $this->container->get('translator');
        if (!$reservationId) {
            $responseArray['success'] = false;
            $responseArray['message'] = $translator->trans('Reservation Id is required.');
            $responseArray['code']    = '421';
            $response                 = new Response(json_encode($responseArray));
            $response->headers->set('Content-Type', 'application/json');
            
            return json_encode($responseArray);
        } else {
            $bookingEncoded    = $this->getBookingDataForCancellation($reservationId, 'bookingReference');
            $bookingDecoded    = json_decode($bookingEncoded, true);
            $bookingDetailsObj = $bookingDecoded['data'];
            $bookingReference  = $bookingDetailsObj['bookingReference'];
            
            $cancelBookingOriginalEncoded = $this->cancelBooking($bookingReference, $bookingDetailsObj['email'], $bookingDetailsObj['dealType']);
            $cancelResults                = json_decode($cancelBookingOriginalEncoded, true);
            
            
            if ($cancelResults['success']) {
                $cancelResultsData = $cancelResults['data'];
                
                $cancelResultsData['cancellationFee']           = array();
                $cancelResultsData['cancellationFee']['amount'] = $cancelResultsData['price'];
                
                $cancelBookingRep                                 = $this->em->getRepository('DealBundle:DealBooking')->findOneByBookingReference($bookingReference);
                $cancelCurrency                                   = $cancelBookingRep->getCurrency();
                $cancelResultsData['cancellationFee']['currency'] = $cancelCurrency;
                
                $responseArray['success'] = true;
                $responseArray['message'] = $translator->trans($cancelResults['message']);
                $responseArray['code']    = '200';
                $responseArray['data']    = $cancelResultsData;
            } else {
                $responseArray['success'] = false;
                $responseArray['message'] = $translator->trans($cancelResults['message']);
                $responseArray['code']    = '422';
            }
            
            $response = new Response(json_encode($responseArray));
            $response->headers->set('Content-Type', 'application/json');
            
            return json_encode($responseArray);
        }
    }
    /*
     * Function that gets the deals details info of a specific deal using deal_details_id and lang
     *
     * @param $id
     * @param $langCode
     *
     * @return DealDetails json object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getDealDetailsInfo($id, $langCode = 'en')
    {
        $results = $this->em->getRepository('DealBundle:DealDetails')->getDealDetailsInfo($id, $langCode);
        return $this->createJsonResponse($results);
    }
    /*
     * Function that gets the deals details info of list deal_details_id and lang
     *
     * @param $list_id
     * @param $langCode
     *
     * @return DealDetails json object
     */
    
    public function getDealDetailsInfoList($list_id, $langCode = 'en')
    {
        $results = $this->em->getRepository('DealBundle:DealDetails')->getDealDetailsInfoList($list_id, $langCode);
        return $this->createJsonResponse($results);
    }
    
    /*
     * Function that gets the deals city info of a specific city by id
     *
     * @param $id
     *
     * @return DealCity json object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getDealCityInfo($id)
    {
        $results = $this->em->getRepository('DealBundle:DealCity')->getDealCityInfo($id);
        return $this->createJsonResponse($results);
    }
    /*
     * Function that gets the deals city info list id
     *
     * @param $list_id
     *
     * @return DealCity json object
     */
    
    public function getDealCityInfoList($list_id)
    {
        $results = $this->em->getRepository('DealBundle:DealCity')->getDealCityInfoList($list_id);
        return $this->createJsonResponse($results);
    }
    /*
     * Function that gets the top attractions info of a specific id
     *
     * @param $id
     *
     * @return DealTopAttractions json object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getDealTopAttractionsInfo($id)
    {
        $results = $this->em->getRepository('DealBundle:DealTopAttractions')->getDealTopAttractionsInfo($id);
        return $this->createJsonResponse($results);
    }
    /*
     * Function that gets the top attractions info of list id
     *
     * @param $list_id
     *
     * @return DealTopAttractions json object
     */
    
    public function getDealTopAttractionsInfoList($list_id)
    {
        $results = $this->em->getRepository('DealBundle:DealTopAttractions')->getDealTopAttractionsInfoList($list_id);
        return $this->createJsonResponse($results);
    }
    /*
     *  This handles the checking of existence of a published activity within a specifc category
     *
     * @params $activityId
     * @params $categoryId
     *
     * @return array
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */
    
    public function checkActivityCategoryExistence($activityId, $categoryId)
    {
        $dealDetailObj = $this->em->getRepository('DealBundle:DealDetails')->findOneBy([
            'dealCode' => $activityId,
            'published' => 1,
        ]);
    }
    /*
     * This method gets all the activities for all cities with all different deal types
     * We should loop through each city to get its activities
     *
     *
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getCityDiscoveryActivities($closeEntityManagerOnCompletion = false)
    {
        $results = $this->cityDiscoveryHandler->getCityDiscoveryActivities($closeEntityManagerOnCompletion);
    }
    /*
     *
     * This method loops through all the deals from deal details and for each deal get the relative images
     * from the api call of get activity details from city discovery services
     * then insert those into the table deal_image
     *
     * @param closeEntityManagerOnCompletion bool to close the em manages connection or not
     *
     * @return
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */
    
    public function getCityDiscoveryImages($closeEntityManagerOnCompletion = false)
    {
        $results = $this->cityDiscoveryHandler->getCityDiscoveryImages($closeEntityManagerOnCompletion);
    }
    
    /**
     * This method will return the list of top attractions per active api
     *
     * @param string $dealType
     *
     * @return topAttractions array
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */
    public function getTopAttractionsPerApi()
    {
        $getApiSupplier          = $this->em->getRepository('DealBundle:DealApiSupplier')->getActiveApiPerDealType();
        $params['apiSupplierId'] = ($getApiSupplier) ? array_column($getApiSupplier, 'das_id') : array();
        $params['limit']         = $this->container->getParameter('TOP_ATTRACTIONS_LIMIT');
        $dealSC                  = $this->getDealSearchCriteria($params);
        $return                  = $this->em->getRepository('DealBundle:DealTopAttractions')->getTopAttractionsPerApi($dealSC);
        
        return $this->createJsonResponse($return);
    }
    /*
     * Getting list of deals with lowest price deal that match deal name, limit and cityid
     *
     * @param $dealName
     * @param $city
     * @param $limit
     *
     * @return list of data
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getEnhancedSearchByDealName($dealName, $cityName, $limit)
    {
        $criteria             = array();
        $criteria['dealName'] = ($dealName) ? $dealName : '';
        $criteria['cityName'] = ($cityName) ? $cityName : '';
        $criteria['limit']    = ($limit) ? $limit : 1;

        $dealSC = $this->getDealSearchCriteria($criteria);
        $result = $this->em->getRepository('DealBundle:DealDetails')->getEnhancedSearchByDealName($dealSC);

        $searchResults = array();
        if ($result) {
            if ($dealName) {
                $link = $this->container->get('TTRouteUtils')->returnDealNameSearchLink($dealSC->getLangCode(), $dealName);
            } else {
                $link = $this->container->get('TTRouteUtils')->returnDealsSearchLink($dealSC->getLangCode(), $cityName);
            }
            foreach ($result as $deal) {
                $item                = array();
                $item['dealName']    = (isset($deal['dealNameTrans']) && $deal['dealNameTrans']) ? $deal['dealNameTrans'] : $deal['dealName'];
                $item['description'] = (isset($deal['descriptionTrans']) && $deal['descriptionTrans']) ? $deal['descriptionTrans'] : $deal['description'];
                $item['link']        = $link;
                $item['price']       = $deal['dd_priceBeforePromo'];

                $params                 = array();
                $params['apiId']        = $deal['dd_dealApiId'];
                $params['dealCode']     = $deal['dd_dealCode'];
                $params['categoryName'] = $deal['categoryName'];
                $params['cityName']     = $deal['cityName'];
                $params['imageId']      = $deal['imageId'];
                $params['packageId']    = $deal['dd_id'];

                $imgObj            = $this->getDealImageCriteria($params);
                $item['imagePath'] = $this->getDefaultImage($imgObj);

                $searchResults[$deal['dd_id']] = $item;
            }
        }
        return $this->createJsonResponse($searchResults);
    }
    /*
     * Getting list of deals by cityId
     *
     * @param $cityId
     * @param $limit
     *
     * @return list of data
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getDealSearchByCityId($cityId, $limit)
    {
        if (!$cityId || $cityId == 0) return array();

        $criteria          = $result            = array();
        $criteria['city']  = $cityId;
        $criteria['limit'] = ($limit) ? $limit : $this->container->getParameter('AVERAGE_QUERY_LIMIT');

        $dealSC = $this->getDealSearchCriteria($criteria);
        $result = $this->em->getRepository('DealBundle:DealDetails')->getEnhancedSearchByDealName($dealSC);

        $searchResults = array();
        if ($result) {
            foreach ($result as $deal) {
                $item                = array();
                $item['dealName']    = (isset($deal['dealNameTrans']) && $deal['dealNameTrans']) ? $deal['dealNameTrans'] : $deal['dealName'];
                $item['description'] = (isset($deal['descriptionTrans']) && $deal['descriptionTrans']) ? $deal['descriptionTrans'] : $deal['description'];
                $item['cityName']    = $deal['cityName'];
                $item['link']        = $this->container->get('TTRouteUtils')->returnDealNameSearchLink($dealSC->getLangCode(), $deal['dd_dealName']);
                $item['price']       = $deal['dd_priceBeforePromo'];

                $params                 = array();
                $params['apiId']        = $deal['dd_dealApiId'];
                $params['dealCode']     = $deal['dd_dealCode'];
                $params['categoryName'] = $deal['categoryName'];
                $params['cityName']     = $deal['cityName'];
                $params['imageId']      = $deal['imageId'];
                $params['packageId']    = $deal['dd_id'];

                $imgObj            = $this->getDealImageCriteria($params);
                $item['imagePath'] = $this->getDefaultImage($imgObj);

                $searchResults[$deal['dd_id']] = $item;
            }
        }

        return $this->createJsonResponse($searchResults);
    }
    /*
     * Retrieving default image path for deal
     *
     * @param $dealDetailsId
     *
     * @return imagePath
     * @author Anna Lou H. Parejo <anna.parejo@touristtube.com>
     */

    public function getDefaultImage($defaultImgObj = 0, $isThumbnail = true, $imgWidth = 255, $imgHeight = 148)
    {
        $defaultImage = $this->container->get("TTRouteUtils")->generateMediaURL("/media/images/deals/default.jpg");
        $imagePath    = '';
        $dir          = $this->container->getParameter('CONFIG_SERVER_ROOT');

        $params = array('directory' => $dir,
            'apiId' => $defaultImgObj->getCommonSC()->getPackage()->getApiId(),
            'dealCode' => $defaultImgObj->getCommonSC()->getPackage()->getCode(),
            'categoryName' => $defaultImgObj->getCommonSC()->getPackage()->getTypeName(),
            'cityName' => $defaultImgObj->getCommonSC()->getCity()->getName(),
            'imageId' => $defaultImgObj->getImageId(),
            'isThumbnail' => $isThumbnail,
            'imgWidth' => $imgWidth,
            'imgHeight' => $imgHeight,
        );

        $imgObj        = $this->getDealImageCriteria($params);
        $dealImagePath = $this->returnDealsImageLink($imgObj);

        if ($dealImagePath) {
            $imagePath = $dealImagePath;
        } else {
            $nextImageArr = $this->em->getRepository('DealBundle:DealDetails')->getNextDealImage($defaultImgObj->getCommonSC()->getPackage()->getId());
            $nextImageObj = array();
            foreach ($nextImageArr as $imgArr) {
                $nextImageObj[] = $this->getDealImageCriteria($imgArr);
            }
            $nextImage = $this->returnNextDealImage($imgObj, $nextImageObj);
            $imagePath = ($nextImage) ? $nextImage : $defaultImage;
        }
        return $imagePath;
    }
    /*
     * Function that gets the booking details of an item for cancellation
     *
     * @param $fieldValue
     * @param $dbField - this could be id/bookingReference
     *
     * @return DealBooking json object
     * @author Ramil Mangapis <ramil.mangapis@touristtube.com>
     */
    
    public function getBookingDataForCancellation($fieldValue, $dbField = 'id')
    {
        $results = $this->em->getRepository('DealBundle:DealBooking')->getBookingDataForCancellation($fieldValue, $dbField);
        return $this->createJsonResponse($results);
    }
    /*
     * Function that gets the deals city info of a specific city by cityId
     *
     * @param $cityId
     *
     * @return DealCity json object
     * @author Anna Lou Parejo <anna.parejo@touristtube.com>
     */
    
    public function getDealCityInfoByCityId($cityId)
    {
        $response    = array();
        $cityResults = $this->em->getRepository('DealBundle:DealCity')->findOneByCityId($cityId);
        if ($cityResults) {
            $response = $cityResults->toArray();
        }
        
        return $this->createJsonResponse($response);
    }
}
