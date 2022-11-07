<?php

namespace TTBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TTBundle\Entity\Webgeocities;
use TTBundle\Entity\CmsCountries;
use \TTBundle\Model\ElasticSearchSC;

class AjaxController extends DefaultController
{

    public function getAlbumsDataAction()
    {
        $request    = Request::createFromGlobals();
        $user_id    = $this->userGetID();
        $media_type = $request->request->get('media_type', '');
        $media_id   = intval($request->request->get('media_id', 0));
        $page       = intval($request->request->get('page', 0));
        $limit      = 21;

        $srch_options     = array(
            'limit' => $limit,
            'page' => $page,
            'lang' => $this->data['LanguageGet'],
            'id' => $media_id
        );
        $albumContentlist = $this->get('PhotosVideosServices')->albumContentFromURL($srch_options);

        $data_list     = array();
        $is_owner      = 0;
        $user_album_id = intval($albumContentlist[0]['a_userId']);

        if ($user_id == $user_album_id) $is_owner              = 1;
        $data_list['is_owner'] = $is_owner;

        $media_array = array();
        foreach ($albumContentlist as $media) {
            $varr                = array();
            $title               = $media['v_title'];
            if ($media['mlv_title'] != '') $title               = $media['mlv_title'];
            $description         = $media['v_description'];
            if ($media['mlv_description'] != '') $description         = $media['mlv_description'];
            $varr['title']       = $this->get('app.utils')->htmlEntityDecode($title);
            $varr['titlealt']    = $this->get('app.utils')->cleanTitleDataAlt($title);
            $varr['description'] = $this->get('app.utils')->htmlEntityDecode($description);
            $varr['description'] = $this->get('app.utils')->getMultiByteSubstr($varr['description'], 110, NULL, $this->data['LanguageGet']);

            $realpath     = $media['v_relativepath'];
            $relativepath = str_replace('/', '', $realpath);
            $fullPath     = $media['v_fullpath'];
            $itemsname    = $media['v_name'];

            if ($media['v_imageVideo'] == "v") {
                $varr['img'] = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($media, '');
            } else {
                $varr['img'] = $this->get("TTMediaUtils")->createItemThumbs($itemsname, $fullPath, 0, 0, '284', '162', 'MediaSearch284162', $fullPath, $fullPath);
            }

            if (!$varr['img']) {
                $varr['img'] = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($media, '');
            }

            $varr['id']    = $media['v_id'];
            $varr['type']  = $media['v_imageVideo'];
            $varr['link']  = $this->get("TTMediaUtils")->returnMediaUriHashedFromArray($media, $this->data['LanguageGet']);
            $media_array[] = $varr;
        }
        $data_list['media_array'] = $media_array;

        $all_info['data'] = $this->render('photos_videos/album_data_in.twig', $data_list)->getContent();

        $res = new Response(json_encode($all_info));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    public function searchLocalityAction()
    {
        $request     = Request::createFromGlobals();
        $term        = $request->query->get('term', '');
        $countryCode = $request->query->get('countryCode', '');
        $onlyCity    = $request->query->get('onlyCity', 1);
        $lang        = $this->data['LanguageGet'];
        $limit       = 10;
        $routepath   = $this->getRoutePath($request);
        $ret         = array();
        $i           = 0;

        $srch_options = array
            (
            'term' => $term,
            'onlyCity' => $onlyCity,
            'countryCode' => '',
            'limit' => $limit,
            'lang' => $lang,
            'route' => 'web'
        );
        if (isset($countryCode) && $countryCode != '') {
            $srch_options['countryCode'] = $countryCode;
        }
        $resp = $this->get('ApiAutocompleteServices')->getSearchLocalityQuery($srch_options);

        $res = new Response(json_encode($resp));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    public function searchForChannelAction()
    {
        $request = Request::createFromGlobals();

        $city        = $request->query->get('city', '');
        $cityId      = $request->query->get('cityId', '');
        $type        = $request->query->get('type', '');
        $countryCode = $request->query->get('state', '');
        $stateCode   = $request->query->get('contryC', '');
        $term        = $request->query->get('term', '');

        $options = array(
            'term' => $term,
            'lang' => $this->data['LanguageGet'],
            'city' => $city,
            'contryC' => $countryCode,
            'cityId' => $cityId,
            'type' => $type,
            'state' => $stateCode,
            'route' => 'web'
        );
        $resp    = $this->get('ApiAutocompleteServices')->getChannelAutocompleteQuery($options);

        $res = new Response(json_encode($resp));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    public function reviewAutocompleteAction()
    {
        $request     = Request::createFromGlobals();
        $routepath   = $this->getRoutePath($request);
        $term        = $request->query->get('term', '');
        $entity_type = $request->query->get('t', '');

        $ret     = array();
        $options = array(
            'entity_type' => $entity_type,
            'term' => $term,
            'limit' => 50,
            'lang' => $this->data['LanguageGet'],
            'route' => 'web'
        );
        $resp    = $this->get('ApiAutocompleteServices')->getReviewAutocompleteQuery($options);

        $res = new Response(json_encode($resp));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    public function removeHotelImagesAction()
    {
        $request  = Request::createFromGlobals();
        $id       = intval($request->request->get('id', 0));
        $all_info = array();
        if (!$this->get('HRSServices')->deleteUserAddedImage($id, $this->data['USERID'])) {
            $all_info['status'] = $this->translator->trans('error');
            $all_info['msg']    = $this->translator->trans('Couldn\'t remove this image. Please try again later.');
        }
        $res = new Response(json_encode($all_info));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function verifyCouponApiAction()
    {

        $request = $this->getRequest();

        $couponCode         = trim($request->request->get('coupon_code', ''));
        $targetEntityTypeId = $request->request->get('target_entity_type_id', 0);
        $displayCurrency    = $request->request->get('display_currency', 'USD');
        $displayAmount      = $request->request->get('display_amount', 0);

        $verificationResponse = array('status' => false);

        if (!$couponCode || !$targetEntityTypeId || !$displayAmount) {
            $response = new Response(json_encode($verificationResponse));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        $campaign_info = $this->validUnusedCouponsCampaign($couponCode, $targetEntityTypeId);
        if ($campaign_info !== false) {
            $verificationResponse['status'] = true;

            $discountedAmountInfo = $this->applyDiscount($campaign_info['c_discountId'], $campaign_info['currency_code'], $displayCurrency, $displayAmount);

            $verificationResponse['discounted'] = $discountedAmountInfo['status'];

            if ($verificationResponse['discounted']) {
                $verificationResponse['amount']           = $discountedAmountInfo['amount'];
                $verificationResponse['discount_details'] = $this->getDiscountDisplayableInfo($campaign_info['c_discountId'], $campaign_info['currency_code'], $displayCurrency);
            }
        }

        $response = new Response(json_encode($verificationResponse));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function campaignsBannerInfoAction()
    {
        $campaigns = $this->get('TTServices')->activeCampaigns(null, false);

        $info = array();

        if ($campaigns) {
            foreach ($campaigns as $campaign) {
                $info[] = array('source_entity_type_id' => $campaign['c_sourceEntityTypeId'],
                    'marketing_label' => $this->translator->trans($campaign['c_marketingLabel']));
            }
        }

        $response = new Response(json_encode($info));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function highlightSearchStr($str, $term)
    {
        return preg_replace(array('/'.$term.'/i'), '<b>'.$term.'</b>', $str);
    }

    public function HotelSearchAction(Request $request)
    {
        return $this->HotelSearchForAmadeusAction();
    }

    public function HotelSearchForAmadeusAction()
    {
        $request       = Request::createFromGlobals();
        $routepath     = $this->getRoutePath($request);
        $from_mobile   = 0;
        $term          = $request->query->get('term', '');
        $page_src      = $request->query->get('page_src', $this->container->getParameter('hotels')['page_src']['hrs']);
        $termWordCount = str_word_count($term);
        $cityId        = $request->query->get('cityId', '');
        $language      = $this->LanguageGet();
        $options       = array(
            'term' => $term,
            'limit' => 10,
            'page_src' => $page_src,
            'cityId' => $cityId,
            'lang' => $language,
            'from_mobile' => $from_mobile
        );

        $resp = $this->get('ApiAutocompleteServices')->getHotelAutocompleteQuery($options);


        $res = new Response(json_encode($resp));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function searchAirportCodeAction()
    {
        $request   = Request::createFromGlobals();
        $routepath = $this->getRoutePath($request);
        $term      = strtolower($request->query->get('term', ''));
        //$from_mobile = intval($request->query->get('from_mobile', 0));
        $term      = ltrim($term);
        $term      = rtrim($term);
        $Result    = array();

        $srch_options = array
            (
            'limit' => 10,
            'from' => 0,
            'term' => $term,
            'from_mobile' => 0
        );
        $resp         = $this->get('ApiAutocompleteServices')->getFlightAutocompleteQuery($srch_options);

        $res = new Response(json_encode($resp));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function searchThingsToDoAction()
    {
        $request     = Request::createFromGlobals();
        $routepath   = $this->getRoutePath($request);
        $from_mobile = $request->query->get('from_mobile');
        $term        = $request->query->get('term', '');

        $language = $this->LanguageGet();

        $srch_options = array
            (
            'limit' => 10,
            'from' => 0,
            'term' => $term,
            'lang' => $language,
            'route' => 'web'
        );

        $resp = $this->get('ApiAutocompleteServices')->getThingsToDoAutocompleteQuery($srch_options);


        $res = new Response(json_encode($resp));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function DealSearchAction()
    {
        $request     = Request::createFromGlobals();
        $routepath   = $this->getRoutePath($request);
        $from_mobile = $request->query->get('from_mobile');
        $term        = $request->query->get('term', '');

        $language      = $this->LanguageGet();
        $ret_deal      = array();
        $ret_deal_city = array();

        $srch_options = array
            (
            'term' => $term
        );

        $url_source        = 'DealSearchAction - getDealsSearch - URL: '.$routepath;
        $queryStringResult = $this->get('ElasticServices')->getDealsSearch($srch_options, $url_source);
        $totalDeal         = $queryStringResult[1];
        $ret_deal          = $queryStringResult[0];

        $url_source          = 'DealSearchAction - getDealsCitySearch - URL: '.$routepath;
        $queryStringResult   = $this->get('ElasticServices')->getDealsCitySearch($srch_options, $url_source);
        $ret_doc_aggregation = $queryStringResult[2]['cityName']['buckets'];
        $ret_deal_city       = $queryStringResult[0];
        $totalDealCities     = sizeof($ret_doc_aggregation);

        if ($totalDeal > 5 && $totalDealCities > 5) {
            $countDeal   = 5;
            $countCities = 5;
        } elseif ($totalDeal < 5) {
            $countCities = 10 - $totalDeal;
            $countDeal   = $totalDeal;
        } elseif ($totalDealCities < 5) {
            $countDeal   = 10 - $totalDealCities;
            $countCities = $totalDealCities;
        }

        $ret     = array();
        $ret2    = array();
        $ret_all = array();

        $dealResultCount = 0;
        $cityResultCount = 0;

        foreach ($ret_deal as $document) {
            if ($dealResultCount < $countDeal) {
                $dealResultCount++;
            }
            $retarr             = array();
            $retarr['cityName'] = '';
            $retarr['dealId']   = 0;
            $highlightType      = $document['highlight'];
            if ($document['_source']['type'] == 'deals') {
                $retarr['dealId'] = $document['_source']['id'];
                $links            = '';
                $title            = $this->get('app.utils')->cleanTitleData($document['_source']['name']);
                $links            = '';
                $retarr['label']  = "<a href='".$links."' title='".$document['_source']['name']."'><div class='search_result_container'> <div class='search_result'>"
                    ."<img class='search_result_image1' src='".$this->get("TTRouteUtils")->generateMediaURL('/media/images/search/deals-icon.png')."' alt='search result image'>";
                if (isset($highlightType['translation.name_*'])) {
                    $highlightName = $highlightType['translation.name_'.$language][0];
                } else {
                    if (isset($highlightType['name'][0])) {
                        $highlightName = $highlightType['name'][0];
                    } else {
                        $highlightName = $this->highlightSearchStr($document['_source']['name'], $term);
                    }
                }
                $retarr['label']  .= $highlightName."<br>";
                $retarr['label']  .= "</div></div></a>";
                $retarr['id']     = $retarr['dealId'] = intval($document['_source']['id']);

                $retarr['name']   = $document['_source']['name'];
                $retarr['lkname'] = $links;
                $retarr['value']  = $document['_source']['name'];

                $retarr['links'] = $links;
                $ret[]           = $retarr;
            } elseif ($document['_source']['type'] == 'top_attractions') {
                $links                    = '';
                $title                    = $this->get('app.utils')->cleanTitleData($document['_source']['description']);
                $retarr['attractionName'] = $document['_source']['name'];
                $links                    = '';
                $retarr['label']          = "<a href='".$links."' title='".$document['_source']['description']."'><div class='search_result_container'> <div class='search_result'>"
                    ."<img class='search_result_image1' src='".$this->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static4.jpg')."' alt='search result image'>";
                $retarr['label']          .= $this->highlightSearchStr($document['_source']['description'], $term)."<br>";
                $retarr['label']          .= "</div></div></a>";

                $retarr['name']   = $document['_source']['description'];
                $retarr['lkname'] = $links;
                $retarr['value']  = $document['_source']['description'];

                $retarr['links'] = $links;
                $ret[]           = $retarr;
            }
            if ($dealResultCount == $countDeal) {
                break;
            }
        }

        foreach ($ret_doc_aggregation as $document2) {
            if ($cityResultCount < $countCities) {
                $cityResultCount++;
            }

            $retarr['cityName'] = $document2['key'];
            $retarr['cityId']   = $document2['top_sales_hits']['hits']['hits'][0]['_source']['location']['city']['id'];
            $links              = '';
            $title              = $this->get('app.utils')->cleanTitleData($document2['key']);
            $links              = '';
            $retarr['label']    = "<a href='".$links."' title='".$document2['key']."'><div class='search_result_container'> <div class='search_result'>"
                ."<img class='search_result_image1' src='".$this->get("TTRouteUtils")->generateMediaURL('/media/images/search/searchfor_static1.png')."' alt='search result image'>";

            $retarr['label'] .= $this->highlightSearchStr($document2['key'], $term)."<br>";
            $retarr['label'] .= "</div></div></a>";

            $retarr['name']   = $document2['key'];
            $retarr['lkname'] = $links;
            $retarr['value']  = $document2['key'];
            $retarr['links']  = $links;
            $ret2[]           = $retarr;

            if ($cityResultCount == $countCities) {
                break;
            }
        }
        $ret_all = array_merge($ret2, $ret);

        $res = new Response(json_encode($ret_all));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function HotelSearchFor1Action(Request $request)
    {
        return $this->HotelSearchForAmadeusAction();
    }
}
