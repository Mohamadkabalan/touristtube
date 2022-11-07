<?php

namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChannelsController extends DefaultController
{

    public function channelsHomeAction( $seotitle, $seodescription, $seokeywords )
    {
        $this->data['datapagename'] = 'channels';
        $channelItem = $this->get('ChannelServices')->getChannelRandomList( 20 );
        $channelList = array();
        foreach ($channelItem as $item) {
            $dchannelList['id'] = $item['c_id'];
            $dchannelList['name'] = $this->get('app.utils')->htmlEntityDecode($item['c_channelName']);
            $dchannelList['namealt'] = $this->get('app.utils')->cleanTitleDataAlt($item['c_channelName']);
            $dchannelList['link'] = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],"/channel/" . $item['c_channelUrl'], 'channels');

            $dimage = 'coverphoto.jpg';
            $dimagepath = 'media/images/channel/';
            if ($item['c_header']) {
                $dimage = $item['c_header'];
                $dimagepath = 'media/channel/' . $dchannelList['id'] . '/';
                $dchannelList['img'] = $this->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 284, 147, 'discover284147', $dimagepath . 'thumb/');
            } else {
                $dchannelList['img'] = $this->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 284, 147, 'discover284147', $dimagepath);
            }

            $dchannelList['desc'] = $this->get('app.utils')->htmlEntityDecode($item['c_smallDescription']);
            $dchannelList['desc'] = $this->get('app.utils')->getMultiByteSubstr($dchannelList['desc'], 185, NULL, $this->data['LanguageGet']);

            $channelList[] = $dchannelList;
        }

        $this->data['channel_list'] = $channelList;

        $canonicalshlink = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/', 'channels');
        $this->setHreflangLinks($canonicalshlink, true, true);

        $action_text_display = $this->translator->trans(/** @Ignore */$seotitle, array(), 'seo');
        $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

        $action_text_display = $this->translator->trans(/** @Ignore */$seodescription, array(), 'seo');
        $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

        return $this->render('channels/channels-home.twig', $this->data);
    }

    public function channelsAction($page = 1, $search = '', $seotitle, $seodescription, $seokeywords, $term = '', $city_id = 0, $state_code = '', $country_code = '') {
        $this->data['datapagename'] = 'channels';
        $request = Request::createFromGlobals();
        $routepath = $this->getRoutePath($request);

        if ($page == '') {
            $page = 1;
        }
        $limit = 24;
        $orderby = 'createTs';
        $order = 'd';
        $categoryid = 0;
        $channelscount = 0;
        $pagesearchlink = '';
        $realname = '';
        $pagesearch = false;

        $canonicalshlink = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/channels', 'channels');
        if ($search) {
            $categoryid = $this->get('ChannelServices')->channelCategoryGetID($search);

            if ($categoryid > 0) {
                $search = str_replace("-", "+", $search);
                $canonicalshlink = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],"/channels-category-" . $search, 'channels');
            }
            else
            {
                return $this->redirectToLangRoute('_channels', array(), 301);
            }
        }

        $optionsCh = array('orderby' => $orderby, 'order' => $order, 'limit' => $limit, 'page' => ($page - 1));
        $optionsCategory = array('has_channel' => true, 'lang' => $this->data['LanguageGet'], 'orderby' => 'title');
        if ($categoryid > 0) {
            $optionsCh['category'] = $categoryid;
            if ($realname)
                $realname .= ' - ';
            $realname .= str_replace('-', ' ', str_replace('+', ' ', $search));
        }

        if ($term) {
            $pagesearch = true;
            $optionsCh['channel_name'] = $term;
            $optionsCategory['channel_name'] = $term;
            if ($realname)
                $realname .= ' - ';
            $realname .= str_replace('-', ' ', str_replace('+', ' ', $term));
            $canonicalshlink = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],"/channels-search-" . $term, 'channels');
        }

        if ($city_id > 0) {
            $pagesearch = true;
            $pagesearchlink = "CI_" . $city_id;
            $optionsCh['city_id'] = $city_id;
            $optionsCategory['city_id'] = $city_id;
            $cityInfo = $this->get('CitiesServices')->worldcitiespopInfo(intval($city_id));
            if ($realname)
                $realname .= ' - ';
            $realname .= $cityInfo[0]->getName();
        }

        if ($country_code) {
            $pagesearch = true;
            $pagesearchlink = "CO_" . $country_code;
            $optionsCh['country'] = $country_code;
            $optionsCategory['country'] = $country_code;
            $countryInfo = $this->get('CmsCountriesServices')->countryGetInfo($country_code);
            if ($realname)
                $realname .= ' - ';
            $realname .= $countryInfo->getName();
        }

        if ($state_code && $country_code) {
            $state_array = $this->get('CitiesServices')->worldStateInfo($country_code, $state_code);

            if ($state_array && sizeof($state_array)) {
                $pagesearch = true;
                $pagesearchlink = "S_" . $state_code . "_" . $country_code;
                $optionsCh['state_name'] = $this->get('app.utils')->htmlEntityDecode($state_array[0]->getStateName());
                $optionsCategory['state_code'] = $state_code;
                if ($realname)
                    $realname .= ' - ';
                $realname .= $state_array[0]->getStateName();
            }
        }

        if ($term == '' && $pagesearchlink) {
            $canonicalshlink = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],"/channels-search--" . $pagesearchlink, 'channels');
        }
        $this->setHreflangLinks($canonicalshlink, true, true);

        if ($this->data['aliasseo'] == '') {
            $seopage = 'Part ' . $page;
            $action_array = array();
            if ($realname) {
                $action_array[] = $realname;
            }
            $action_array[] = $seopage;

            $action_text_display = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $url_source = 'channelsAction - getChannelsListDiscoverData - URL: ' . $routepath;
        $optionsCh['lang']       = $this->data['LanguageGet'];
        $optionsCh['url_source'] = $url_source;
        $optionsCh['img_width'] = 284;
        $optionsCh['img_height'] = 147;        
        list( $channelList, $channelscount ) = $this->get('ChannelServices')->getChannelsListDiscoverData( $optionsCh );
        $pgcount = ceil($channelscount / $limit);

        $optionsCategory['pagesearch']     = $pagesearch;
        $optionsCategory['pagesearchlink'] = $pagesearchlink;
        $optionsCategory['term']           = $term;
        $optionsCategory['channelscount']  = $channelscount;
        $this->data['category_list'] = $this->get('ChannelServices')->channelcategoryGetHash($optionsCategory);

        if ($pagesearch) {
            $search_paging_output = $this->get('TTRouteUtils')->getRelatedChannelPagination($channelscount, $limit, $page, 'channels-search-' . $term . '-' . $pagesearchlink, '/', 2, 'channels', $this->data['LanguageGet'] );
        } else if ($categoryid > 0) {
            $search_paging_output = $this->get('TTRouteUtils')->getRelatedChannelPagination($channelscount, $limit, $page, 'channels-category-' . $search, '/', 2, 'channels', $this->data['LanguageGet'] );
        } else {
            $search_paging_output = $this->get('TTRouteUtils')->getRelatedChannelPagination($channelscount, $limit, $page, 'channels', '/', 2, 'channels', $this->data['LanguageGet'] );
        }
        $this->data['search_paging_output'] = $search_paging_output;
        $this->data['channel_list'] = $channelList;
        $this->data['categoryid'] = $categoryid;
        $this->data['realname'] = $realname;

        return $this->render('channels/channels.twig', $this->data);
    }

    public function channelsRedirectAction($page = 1, $search = '', $seotitle, $seodescription, $seokeywords, $term = '', $city_id = 0, $state_code = '', $country_code = '') {
        return $this->redirectToLangRoute('_channels', array('page' => $page), 301);
    }

    public function channelsCategoryRedirectAction($search, $page, $seotitle, $seodescription, $seokeywords) {
        return $this->redirectToLangRoute('_channels_category', array('page' => $page, 'search' => $search), 301);
    }

    public function channelsCategoryAction($search, $page, $seotitle, $seodescription, $seokeywords) {
        return $this->channelsAction($page, $search, $seotitle, $seodescription, $seokeywords);
    }

    public function channelsSearchRedirectAction($srch, $page, $seotitle, $seodescription, $seokeywords) {
        return $this->redirectToLangRoute('_channels_search', array('page' => $page, 'srch' => $srch), 301);
    }

    public function channelsSearchAction($srch, $page, $seotitle, $seodescription, $seokeywords) {
        $search_str = urldecode($srch);
        $search_str = explode('-', $search_str);
        $c_id = 0;
        $co_id = "";
        $s_id = "";
        $search = '';
        $last_record = count($search_str) - 1;
        if ($last_record > 0) {
            $rest1 = explode('_', $search_str[$last_record]);
            $i = 0;
            while ($i < sizeof($rest1)) {
                if (strtoupper($rest1[$i]) == "CO") {
                    $co_id = $rest1[$i + 1];
                    $i++;
                } else if (strtoupper($rest1[$i]) == "S") {
                    $s_id = $rest1[$i + 1];
                    $co_id = $rest1[$i + 2];
                    $i += 2;
                } else if (strtoupper($rest1[$i]) == "CI") {
                    $c_id = intval($rest1[$i + 1]);
                    $i++;
                } else if (strtoupper($rest1[$i]) == "C") {
                    $search = $rest1[$i + 1];
                    $search = str_replace('+', '-', $search);
                    $search = str_replace(' ', '-', $search);
                    $i++;
                }
                $i++;
            }
        }
        return $this->channelsAction($page, $search, $seotitle, $seodescription, $seokeywords, $search_str[0], $c_id, $s_id, $co_id);
    }

    public function channelsSearchOldRedirectAction($v1 = '', $v10 = '', $v2 = '', $v20 = '', $v3 = '', $v30 = '', $v4 = '', $v40 = '', $v5 = '', $v50 = '', $v6 = '', $v60 = '', $seotitle, $seodescription, $seokeywords) {
        $srch = $v1 . '/' . $v10 . '/' . $v2 . '/' . $v20 . '/' . $v3 . '/' . $v30 . '/' . $v4 . '/' . $v40 . '/' . $v5 . '/' . $v50 . '/' . $v6 . '/' . $v60;

        $rest1 = explode('/', $srch);
        $search = '';
        $searchstr = '';
        $i = 0;
        $page = 1;
        while ($i < sizeof($rest1)) {
            if ($rest1[$i] == "page") {
                $page = intval($rest1[$i + 1]);
            } else if ($rest1[$i] == "co") {
                if ($search)
                    $search .= '_';
                $search .= 'CO_' . $rest1[$i + 1];
            }else if ($rest1[$i] == "t") {
                $searchstr .= $rest1[$i + 1];
            } else if ($rest1[$i] == "ci") {
                if ($search)
                    $search .= '_';
                $search .= 'CI_' . intval($rest1[$i + 1]);
            }else if ($rest1[$i] == "c") {
                $ss = $rest1[$i + 1];
                //$ss = str_replace('-', '+',$ss);
                //$ss = str_replace(' ', '+',$ss);
                $ss = $this->get('app.utils')->cleanTitleData($ss);
                if ($search)
                    $search .= '_';
                $search .= 'C_' . $ss;
            }
            $i += 2;
        }
        if ($page < 1)
            $page = 1;
        return $this->redirectToLangRoute('_channels_search', array('page' => $page, 'srch' => $searchstr . '-' . $search), 301);
    }

    public function channelsSearchOldAction($v1 = '', $v10 = '', $v2 = '', $v20 = '', $v3 = '', $v30 = '', $v4 = '', $v40 = '', $v5 = '', $v50 = '', $v6 = '', $v60 = '', $seotitle, $seodescription, $seokeywords) {
        $srch = $v1 . '/' . $v10 . '/' . $v2 . '/' . $v20 . '/' . $v3 . '/' . $v30 . '/' . $v4 . '/' . $v40 . '/' . $v5 . '/' . $v50 . '/' . $v6 . '/' . $v60;

        $rest1 = explode('/', $srch);
        $search = '';
        $searchstr = '';
        $i = 0;
        $page = 1;
        while ($i < sizeof($rest1)) {
            if ($rest1[$i] == "page") {
                $page = intval($rest1[$i + 1]);
            } else if ($rest1[$i] == "co") {
                if ($search)
                    $search .= '_';
                $search .= 'CO_' . $rest1[$i + 1];
            }else if ($rest1[$i] == "t") {
                $searchstr .= $rest1[$i + 1];
            } else if ($rest1[$i] == "ci") {
                if ($search)
                    $search .= '_';
                $search .= 'CI_' . intval($rest1[$i + 1]);
            }else if ($rest1[$i] == "c") {
                $ss = $rest1[$i + 1];
                //$ss = str_replace('-', '+',$ss);
                //$ss = str_replace(' ', '+',$ss);
                $ss = $this->get('app.utils')->cleanTitleData($ss);
                if ($search)
                    $search .= '_';
                $search .= 'C_' . $ss;
            }
            $i += 2;
        }
        if ($page < 1)
            $page = 1;
        return $this->channelsSearchAction($searchstr . '-' . $search, $page, $seotitle, $seodescription, $seokeywords);
    }

    public function createChannelAction($id, $seotitle, $seodescription, $seokeywords) {
        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        if (!is_numeric($id)) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        $channelInfo = $this->get('ChannelServices')->channelGetInfo($id, $this->data['LanguageGet']);

        if (!$channelInfo) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        if ($channelInfo['c_ownerId'] != $this->data['USERID']) {
            return $this->redirectToLangRoute('_channel', array('srch' => $channelInfo['c_channelUrl']), 301);
        }

        $this->getChannelCommunData($channelInfo, 'create', true, $seotitle, $seodescription, $seokeywords);
        $this->data['datapagename'] = 'create_channel';

        return $this->render('channels/channel-create.twig', $this->data);
    }

    public function createChannelRedirectAction($id, $seotitle, $seodescription, $seokeywords) {
        return $this->redirectToLangRoute('_create_channel', array('id' => $id), 301);
    }

    public function channelSettingsAction($id, $seotitle, $seodescription, $seokeywords) {
        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        if (!is_numeric($id)) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        $channelInfo = $this->get('ChannelServices')->channelGetInfo($id, $this->data['LanguageGet']);

        if (!$channelInfo) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        if ($channelInfo['c_ownerId'] != $this->data['USERID']) {
            return $this->redirectToLangRoute('_channel', array('srch' => $channelInfo['c_channelUrl']), 301);
        }

        $this->getChannelCommunData($channelInfo, 'create', true, $seotitle, $seodescription, $seokeywords);
        $this->data['datapagename'] = 'create_channel';

        $countries_list = $this->get('CmsCountriesServices')->getCountries();
        $this->data['countries_list'] = $countries_list;

        $category_list = array();
        $optionsCategory = array('lang' => $this->data['LanguageGet'], 'orderby' => 'title');
        $this->data['category_list'] = $this->get('ChannelServices')->channelcategoryGetHash($optionsCategory);

        $reportReasonList = array();
        $link_array = array();
        $link_array[] = array('link' => $this->generateLangRoute('_help'), 'name' => $this->translator->trans('click here to get assistance'), 'linkalt' => $this->translator->trans('help'));
        $link_array[] = array('link' => '', 'name' => '', 'linkalt' => '');
        $link_array[] = array('link' => $this->generateLangRoute('_privacy-policy'), 'name' => $this->translator->trans("click here to manage channel's privacy"), 'linkalt' => $this->translator->trans("privacy policy"));
        $link_array[] = array('link' => '', 'name' => '', 'linkalt' => '');
        $link_array[] = array('link' => $this->generateLangRoute('_help'), 'name' => $this->translator->trans("know more about channels usefulness or try to connect to other active channels or TTubers"), 'linkalt' => $this->translator->trans("help"));
        $link_array[] = array('link' => '', 'name' => '', 'linkalt' => '');

        $options = array('lang' => $this->data['LanguageGet'], 'entity_type' => $this->container->getParameter('SOCIAL_ENTITY_CHANNEL') . '_0');
        $reportReasons = $this->get('UserServices')->getReportReasonList($options);
        $i = 0;
        foreach ($reportReasons as $item) {
            $items_array = array();
            $items_array['reason'] = $this->get('app.utils')->htmlEntityDecode($item['rr_reason']);
            $items_array['id'] = $item['rr_id'];
            $items_array['link'] = $link_array[$i]['link'];
            $items_array['name'] = $link_array[$i]['name'];
            $items_array['linkalt'] = $link_array[$i]['linkalt'];
            if ($item['mlrr_title'] != '') {
                $items_array['reason'] = $this->get('app.utils')->htmlEntityDecode($item['mlrr_title']);
            }
            $reportReasonList[] = $items_array;
            $i++;
        }
        $this->data['report_reason_list'] = $reportReasonList;

        return $this->render('channels/channel-settings.twig', $this->data);
    }

    public function channelSettingsRedirectAction($id, $seotitle, $seodescription, $seokeywords) {
        return $this->redirectToLangRoute('_channel_settings', array('id' => $id), 301);
    }

    public function channelUploadAction($id, $seotitle, $seodescription, $seokeywords) {
        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        if (!is_numeric($id)) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        $channelInfo = $this->get('ChannelServices')->channelGetInfo($id, $this->data['LanguageGet']);

        if (!$channelInfo) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        if ($channelInfo['c_ownerId'] != $this->data['USERID']) {
            return $this->redirectToLangRoute('_channel', array('srch' => $channelInfo['c_channelUrl']), 301);
        }

        $this->setHreflangLinks($this->get('TTRouteUtils')->returnChannelLink($this->data['LanguageGet'], 'upload', $id), true, true);

        $this->data['datapagename'] = 'uploads_page';
        $this->data['upload_max_filesize'] = intval(ini_get('upload_max_filesize'));
        $this->data['channel_id'] = $id;

        $this->data['name'] = $this->get('app.utils')->htmlEntityDecode($channelInfo['c_channelName']);
        $this->data['namealt'] = $this->get('app.utils')->cleanTitleDataAlt($channelInfo['c_channelName']);
        $channel_name_seo = '';
        if ($this->data['aliasseo'] == '') {
            $action_array = array();
            $channel_name_seo = str_replace('channel ', '', $this->data['namealt']);
            $channel_name_seo = str_replace(' channel', '', $channel_name_seo);
            $channel_name_seo = trim($channel_name_seo);
            $action_array[] = $channel_name_seo;
            $action_text_display = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = $this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo');
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $this->data['section_name'] = $this->translator->trans('Channel').' '.$channel_name_seo;

        $srch_options = array(
            'limit' => null,
            'is_owner' => 1,
            'show_empty' => 1,
            'orderby' => 'catalogName',
            'order' => 'a',
            'channel_id' => $id
        );
        $albumlist = $this->get('PhotosVideosServices')->getAlbumSearch($srch_options);

        $album_array = array();
        foreach ($albumlist as $v_item) {
            $varr = array();
            $varr['id'] = $v_item['a_id'];
            $varr['name'] = $this->get('app.utils')->htmlEntityDecode($v_item['a_catalogName']);
            $album_array[] = $varr;
        }
        $this->data['album_array'] = $album_array;

        return $this->render('uploads/uploads.twig', $this->data);
    }

    public function channelUploadRedirectAction($id, $seotitle, $seodescription, $seokeywords) {
        return $this->redirectToLangRoute('_channel_upload', array('id' => $id), 301);
    }

    public function channelDeleteAction(Request $request) {
        $user_id = $this->data['USERID'];
        $Result = array();
        $channel_id = intval($request->request->get('channel_id', 0));
        $uname = $request->request->get('uname', '');
        $password = $request->request->get('password', '');

        if ($user_id == 0 || $channel_id == 0) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $channelInfo = $this->get('ChannelServices')->channelGetInfo($channel_id, $this->data['LanguageGet']);

        if (!$channelInfo) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($channelInfo['c_ownerId'] != $user_id) {
            $Result['msg'] = $this->translator->trans('You are not the owner of this channel');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if (!$this->get('UserServices')->userPasswordCorrect($user_id, $password, $uname)) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $this->get('ChannelServices')->channelDelete($channel_id, $channelInfo);

        $Result['msg'] = $this->translator->trans('Channel deleted!');
        $Result['status'] = 'ok';

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function channelInfoUpdateAction(Request $request) {
        $user_id = $this->data['USERID'];
        $Result = array();
        $channel_id = $request->request->get('channel_id', 0);
        $update_list = json_decode(stripslashes($request->request->get('update_list', '')), true);

        if ($user_id == 0 || $channel_id == 0) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $channelInfo = $this->get('ChannelServices')->channelGetInfo($channel_id, $this->data['LanguageGet']);

        if (!$channelInfo) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($channelInfo['c_ownerId'] != $user_id) {
            $Result['msg'] = $this->translator->trans('You are not the owner of this channel');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if (isset($update_list['channelUrl']) && $update_list['channelUrl'] != $channelInfo['c_channelUrl']) {
            $channelUrl = strtolower($this->get('app.utils')->cleanTitleData($update_list['channelUrl']));
            $channelUrl = str_replace('+', '-', $channelUrl);
            $channelUrl = str_replace('.', '', $channelUrl);
            $url = $this->get('ChannelServices')->channelUrlRename($channelUrl);

            if ($url != $data) {
                $Result['msg'] = $this->translator->trans('Channel URL already used we suggest') . ' ' . $url;
                $Result['status'] = 'error';
                $res = new Response(json_encode($Result));
                $res->headers->set('Content-Type', 'application/json');
                return $res;
            }
        }

        $update_list['id'] = $channel_id;
        $update_list['ownerId'] = $user_id;
        $this->get('ChannelServices')->channelEdit($update_list);

        $Result['msg'] = $this->translator->trans('Channel information saved!');
        $Result['status'] = 'ok';

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function channelLinksUpdateAction(Request $request) {
        $user_id = $this->data['USERID'];
        $Result = array();
        $channel_id = $request->request->get('channel_id', 0);
        $id = $request->request->get('id', 0);
        $update_list = json_decode(stripslashes($request->request->get('update_list', '')), true);

        if ($user_id == 0 || $channel_id == 0 || $id == 0) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $channelInfo = $this->get('ChannelServices')->channelGetInfo($channel_id, $this->data['LanguageGet']);

        if (!$channelInfo) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($channelInfo['c_ownerId'] != $user_id) {
            $Result['msg'] = $this->translator->trans('You are not the owner of this channel');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $update_list['id'] = $id;
        $update_list['channelid'] = $channel_id;

        $this->get('ChannelServices')->updateChannelExternalLinks($update_list);

        $Result['msg'] = $this->translator->trans('Channel information saved!');
        $Result['status'] = 'ok';

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function channelListOfLinksUpdateAction(Request $request) {
        $user_id = $this->data['USERID'];
        $Result = array();
        $channel_id = $request->request->get('channel_id', 0);
        $update_list = json_decode(stripslashes($request->request->get('update_list', '')), true);

        if ($user_id == 0 || $channel_id == 0) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $channelInfo = $this->get('ChannelServices')->channelGetInfo($channel_id, $this->data['LanguageGet']);

        if (!$channelInfo) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($channelInfo['c_ownerId'] != $user_id) {
            $Result['msg'] = $this->translator->trans('You are not the owner of this channel');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        foreach ($update_list as $item) {
            $options = array('channelid' => $channel_id);
            $id = intval($item['id']);

            foreach ($item as $key => $val) {
                if ($key != 'id' && $key != 'channelid') {
                    $options[$key] = $val;
                }
            }

            if ($id != 0) {
                $options['id'] = $id;
                $this->get('ChannelServices')->updateChannelExternalLinks($options);
            } else {
                $options['published'] = 1;
                $this->get('ChannelServices')->addChannelExternalLinks($options);
            }
        }

        $Result['msg'] = $this->translator->trans('Channel information saved!');
        $Result['status'] = 'ok';

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function channelAddAction($seotitle, $seodescription, $seokeywords) {
        $this->data['datapagename'] = 'channel_add';

        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        $this->setHreflangLinks($this->generateLangRoute('_channel_add'), true, true);

        if ($this->data['aliasseo'] == '') {
            $action_text_display = $this->translator->trans(/** @Ignore */$seotitle, array(), 'seo');
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = $this->translator->trans(/** @Ignore */$seodescription, array(), 'seo');
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = $this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo');
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $category_list = array();
        $optionsCategory = array('lang' => $this->data['LanguageGet'], 'orderby' => 'title');
        $this->data['category_list'] = $this->get('ChannelServices')->channelcategoryGetHash($optionsCategory);

        $country_list = array();
        $country_array = $this->get('CmsCountriesServices')->countryGetList();
        foreach ($country_array as $item) {
            $items_array['name'] = $this->get('app.utils')->htmlEntityDecode($item['co_name']);
            $items_array['code'] = $item['co_code'];
            $country_list[] = $items_array;
        }
        $this->data['country_list'] = $country_list;

        return $this->render('channels/channel-add.twig', $this->data);
    }

    public function channelAddRedirectAction($seotitle, $seodescription, $seokeywords) {
        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        } else {
            return $this->redirectToLangRoute('_channel_add', array(), 301);
        }
    }

    public function myChannelsAction(Request $request, $page = 1, $seotitle, $seodescription, $seokeywords) {
        $routepath = $this->getRoutePath($request);

        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        $user_id = $this->data['USERID'];
        $this->setHreflangLinks($this->generateLangRoute('_my_channels'), true, true);

        if ($this->data['aliasseo'] == '') {
            $action_text_display = $this->translator->trans(/** @Ignore */$seotitle, array(), 'seo');
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = $this->translator->trans(/** @Ignore */$seodescription, array(), 'seo');
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = $this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo');
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        if ($page == '' || $page < 1) {
            $page = 1;
        }

        $limit = 24;
        $optionsCh = array('orderby' => 'id', 'order' => 'd', 'limit' => $limit, 'page' => ($page - 1), 'owner_id' => $user_id);
        $url_source = 'myChannelsAction - getChannelsListDiscoverData - URL: ' . $routepath;
        $optionsCh['lang']       = $this->data['LanguageGet'];
        $optionsCh['url_source'] = $url_source;
        $optionsCh['img_width'] = 284;
        $optionsCh['img_height'] = 147;        
        list( $channelList, $channelscount ) = $this->get('ChannelServices')->getChannelsListDiscoverData( $optionsCh );

        $search_paging_output = $this->get('TTRouteUtils')->getRelatedChannelPagination($channelscount, $limit, $page, 'my-channels', '/', 2, 'channels', $this->data['LanguageGet'] );
        $this->data['search_paging_output'] = $search_paging_output;
        $this->data['channel_list'] = $channelList;
        $this->data['user_is_owner'] = 1;

        return $this->render('channels/my-channels.twig', $this->data);
    }

    public function myChannelsRedirectAction($seotitle, $seodescription, $seokeywords) {
        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        } else {
            return $this->redirectToLangRoute('_my_channels', array(), 301);
        }
    }

    public function createChannelFormAction($seotitle, $seodescription, $seokeywords) {
        if ($this->data['isUserLoggedIn'] == 0) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        } else {
            return $this->redirectToLangRoute('_channel_add', array(), 301);
        }
    }

    public function updateChannelAddAction( Request $request ) {
        $user_id = $this->data['USERID'];
        $Result = array();

        if ($user_id == 0) {
            $Result['msg'] = $this->translator->trans('Couldn\'t save your information. Please try again later');
            $Result['status'] = 'error';
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $name = $request->request->get('name', '');
        $category = intval($request->request->get('category', 0));
        $country = $request->request->get('country', '');
        $city = $request->request->get('city', '');
        $city_id = intval($request->request->get('city_id', 0));
        $street = $request->request->get('street', '');
        $phone = $request->request->get('phone', '');
        $url = $request->request->get('url', '');

        $curl = strtolower(str_replace(' ', '-', $name));
        $curl = $this->get('app.utils')->cleanTitleData($curl);
        $curl = str_replace('.', '', $curl);
        $curl = str_replace('+', '-', $curl);

        if (strlen($name) > 60) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('The channel name is too long.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($category == 0) {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please select a category.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($country == '') {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please select a country.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        if ($phone != '' && preg_match("/^[0-9 +-]+$/", $phone) == '0') {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Please enter a valid phone number.');
            $res = new Response(json_encode($Result));
            $res->headers->set('Content-Type', 'application/json');
            return $res;
        }

        $userInfo = $this->get('UserServices')->getUserInfoById($user_id);
        $fullname = $this->get('app.utils')->htmlEntityDecode($userInfo['cu_fullname']);
        $email = $userInfo['cu_youremail'];

        if ($this->get('ChannelServices')->channelAdd($user_id, $name, $curl, '', '', '', $url, '', $country, $city_id, $city, $street, '', $phone, $category, $email, $fullname, $this->data['LanguageGet'])) {
            $Result['status'] = 'ok';
            $Result['msg'] = $this->translator->trans('An email is sent to you to activate your account.');
        } else {
            $Result['status'] = 'error';
            $Result['msg'] = $this->translator->trans('Error adding channel, please try again');
        }

        $res = new Response(json_encode($Result));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function channelRedirectAction($srch = '', $seotitle, $seodescription, $seokeywords) {
        return $this->redirectToLangRoute('_channel', array('srch' => $srch), 301);
    }

    public function channelAction($srch = '', $seotitle, $seodescription, $seokeywords) {
        $channelInfo = $this->get('ChannelServices')->channelInfoFromURL($srch, $this->data['LanguageGet']);

        if (!$channelInfo) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }
        $this->getChannelCommunData($channelInfo, '', false, $seotitle, $seodescription, $seokeywords);
//        $this->debug($channelInfo);
        $channel_id = $channelInfo['c_id'];

        $media_array = array();

        $srch_options = array(
            'limit' => 8,
            'page' => 0,
            'is_public' => 2,
            'channel_id' => $channel_id,
            'owner_id' => $channelInfo['c_ownerId'],
            'type' => 'i',
            'lang' => $this->data['LanguageGet']
        );
        $media_array = $this->get('PhotosVideosServices')->mediaSearchCommonArray($srch_options);
        
        $this->data['media_array'] = $media_array;

        $video_array = array();
        $srch_options = array(
            'limit' => 8,
            'page' => 0,
            'is_public' => 2,
            'channel_id' => $channel_id,
            'owner_id' => $channelInfo['c_ownerId'],
            'type' => 'v',
            'lang' => $this->data['LanguageGet']
        );
        $video_array= $this->get('PhotosVideosServices')->mediaSearchCommonArray($srch_options);
        
        $this->data['video_array'] = $video_array;

        $discoverToChannelList = $this->get('ChannelServices')->getDiscoverToChannelList($channel_id);
        $discover_to_channel_list = array();
        foreach ($discoverToChannelList as $item) {
            $item_array = array();
            $locationText = '';
            $title = '';
            $type = '';
            $entity_id = $item['cr_entityId'];
            $entity_type = $item['cr_entityType'];

            switch ($entity_type) {
                case $this->container->getParameter('SOCIAL_ENTITY_HOTEL'):
                    $type = $this->translator->trans("Hotel");
                    if ($item['dh_hotelname'] == '')
                        continue;
                    $title = $this->get('app.utils')->htmlEntityDecode($item['dh_hotelname']);
                    $titlealt = $this->get('app.utils')->cleanTitleDataAlt($item['dh_hotelname']);

                    $options = array();
                    $options['lang'] = $this->data['LanguageGet'];
                    $options['address'] = $item['dh_address'];
                    $options['location'] = $item['dh_location'];
                    $options['phone'] = $item['dh_phone'];
                    $options['city_name'] = $item['dhw_name'];
                    $options['state_name'] = $item['dhs_stateName'];
                    $options['country_name'] = $item['dhc_name'];
                    $options['country_iso3'] = $item['dhc_iso3'];
                    $options['country_code'] = $item['dhw_countryCode'];
                    $locationArray = $this->get('ChannelServices')->getHotelAdressReview($options);
                    $locationText = $locationArray[0];

                    $nb_votes = $item['dh_nbVotes'];
                    if ($item['dh_rating'] > 0) {
                        $irating_average = ceil(( $item['dh_rating'] / 2) * 10) / 10;
                    } else {
                        $irating_average = $this->get('ReviewsServices')->socialRateAverage($item['dh_id'], array($entity_type));
                    }
                    $linkpoi = $this->get('TTRouteUtils')->returnHotelReviewLink($this->data['LanguageGet'], $item['dh_id'], $title);

                    break;

                case $this->container->getParameter('SOCIAL_ENTITY_LANDMARK'):
                    $type = $this->translator->trans("Point of interest");
                    if ($item['dp_name'] == '')
                        continue;
                    $title = $this->get('app.utils')->htmlEntityDecode($item['dp_name']);
                    $titlealt = $this->get('app.utils')->cleanTitleDataAlt($item['dp_name']);

                    $options = array();
                    $options['lang'] = $this->data['LanguageGet'];
                    $options['address'] = $item['dp_address'];
                    $options['phone'] = $item['dp_phone'];
                    $options['city_name'] = $item['dpw_name'];
                    $options['state_name'] = $item['dps_stateName'];
                    $options['country_name'] = $item['dpc_name'];
                    $options['country_iso3'] = $item['dpc_iso3'];
                    $options['country_code'] = $item['dpw_countryCode'];
                    $locationArray = $this->get('ChannelServices')->getPoiAdressReview($options);
                    $locationText = $locationArray[0];

                    $nb_votes = 0;
                    $irating_average = $this->get('ReviewsServices')->socialRateAverage($item['dp_id'], array($entity_type));
                    $linkpoi = $this->get('TTRouteUtils')->returnThingstodoReviewLink($this->data['LanguageGet'], $item['dp_id'], $title);

                    break;

                case $this->container->getParameter('SOCIAL_ENTITY_AIRPORT'):
                    $type = $this->translator->trans("Airport");
                    if ($item['da_name'] == '')
                        continue;
                    $title = $this->get('app.utils')->htmlEntityDecode($item['da_name']);
                    $titlealt = $this->get('app.utils')->cleanTitleDataAlt($item['da_name']);

                    $options = array();
                    $options['lang'] = $this->data['LanguageGet'];
                    $options['phone'] = $item['da_telephone'];
                    $options['city'] = $item['da_city'];
                    $options['airport_code'] = $item['da_airportCode'];
                    $options['city_name'] = $item['daw_name'];
                    $options['state_name'] = $item['das_stateName'];
                    $options['country_name'] = $item['dac_name'];
                    $options['country_iso3'] = $item['dac_iso3'];
                    $options['country_code'] = $item['daw_countryCode'];
                    $locationArray = $this->get('ChannelServices')->getAirportAdressReview($options);
                    $locationText = $locationArray[0];
                    $nb_votes = 0;
                    $irating_average = $this->get('ReviewsServices')->socialRateAverage($item['da_id'], array($entity_type));
                    $linkpoi = $this->get('TTRouteUtils')->returnAirportReviewLink($this->data['LanguageGet'], $item['da_id'], $title);
                    break;
            }

            $rate_text = '';
            if ($irating_average > 0) {
                $rate_text .= $this->translator->trans('RATING') . ': ' . $irating_average;
                if ($nb_votes > 0) {
                    $rate_text .= ' - ' . $nb_votes . ' ' . $this->translator->trans('VOTES');
                }
            }

            $item_array['link'] = $linkpoi;
            $item_array['rating'] = $rate_text;
            $item_array['title'] = $title;
            $item_array['titlealt'] = $titlealt;
            $item_array['type'] = $type;
            $item_array['address'] = $locationText;
            $discover_to_channel_list[] = $item_array;
        }
        $this->data['discover_to_channel_list'] = $discover_to_channel_list;

        return $this->render('channels/channel.twig', $this->data);
    }

    public function getChannelCommunData($channelInfo, $page_name = '', $use_id = false, $seotitle, $seodescription, $seokeywords) {
        $this->data['datapagename'] = 'channel_page';
        $channel_id = $channelInfo['c_id'];
        $owner_id = $channelInfo['c_ownerId'];
        $channel_url = $channelInfo['c_channelUrl'];

        if ($use_id) {
            $this->setHreflangLinks($this->get('TTRouteUtils')->returnChannelLink($this->data['LanguageGet'], $page_name, $channel_id), true, true);
        } else {
            $this->setHreflangLinks($this->get('TTRouteUtils')->returnChannelLink($this->data['LanguageGet'], $page_name, $channel_url), true, true);
        }

        $this->data['channel_url_text'] = $channel_url;
        $this->data['channel_url'] = $this->get('TTRouteUtils')->returnChannelLink($this->data['LanguageGet'], '', $channel_url);
        $this->data['channel_upload'] = '';
        $this->data['settings'] = '';
        $is_owner = 0;
        if ($this->data['isUserLoggedIn'] == 1 && $owner_id == $this->data['USERID']) {
            $is_owner = 1;
            $this->data['settings'] = $this->get('TTRouteUtils')->returnChannelLink($this->data['LanguageGet'], 'settings', $channel_id);
            $this->data['channel_upload'] = $this->get('TTRouteUtils')->returnChannelLink($this->data['LanguageGet'], 'upload', $channel_id);
        }
        $this->data['is_owner'] = $is_owner;
        $this->data['name'] = $this->get('app.utils')->htmlEntityDecode($channelInfo['c_channelName']);
        $this->data['namealt'] = $this->get('app.utils')->cleanTitleDataAlt($channelInfo['c_channelName']);
        $this->data['slogan'] = $channelInfo['c_slogan'];

        if ($this->data['aliasseo'] == '') {
            $action_array = array();
            $channel_name_seo = str_replace('channel ', '', $this->data['namealt']);
            $channel_name_seo = str_replace(' channel', '', $channel_name_seo);
            $channel_name_seo = trim($channel_name_seo);
            $action_array[] = $channel_name_seo;
            $action_text_display = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display = $this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo');
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        $this->data['channelslogo'] = $this->get("TTRouteUtils")->generateMediaURL("/media/images/channel_new/add_imgex.jpg");
        $this->data['channelslogo_initial'] = $this->get("TTRouteUtils")->generateMediaURL("/media/images/channel_new/add_imgex.jpg");
        $logolink = $channelInfo['c_logo'];
        if ($logolink) {
            $dimagepath = 'media/channel/' . $channel_id . '/';
            $this->data['channelslogo_initial'] = $this->data['channelslogo'] = $this->get("TTMediaUtils")->createItemThumbs($logolink, $dimagepath, 0, 0, 230, 230, 'thumb230230', $dimagepath);
        }


        $imglink = $channelInfo['c_header'];
        $this->data['cover_photo'] = $this->get("TTRouteUtils")->generateMediaURL("/media/images/channel_new/cover_photo.jpg");
        $this->data['cover_photo_initial'] = $this->get("TTRouteUtils")->generateMediaURL("/media/images/channel_new/cover_photo.jpg");
        if ($imglink) {
            $dimage = $imglink;
            $dimagepath = 'media/channel/' . $channel_id . '/';
            $this->data['cover_photo_initial'] = $this->data['cover_photo'] = $this->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 872, 280, 'thmub872280');
        }

        $create_ts = $channelInfo['c_createTs'];
        $this->data['creaton'] = $create_ts->format('m / d / Y');

        $this->data['creatby'] = $this->get('app.utils')->returnUserArrayDisplayName($channelInfo, array('max_length' => 25));

        $channelCatTitle = $channelInfo['ca_title'];
        if ($channelInfo['mlca_title'] != '') {
            $channelCatTitle = $channelInfo['mlca_title'];
        }
        $this->data['channelcateg'] = $channelCatTitle;

        $links_array = array();
        $customized_array = array();
        $options = array(
            'channelid' => $channel_id,
            'is_social' => 0
        );
        $arraychannellinks = $this->get('ChannelServices')->getChannelExternalLinks($options);
        foreach ($arraychannellinks as $items) {
            $larray = array();
            $larray['id']          = $items['cl_id'];
            $larray['link']        = $this->get('TTRouteUtils')->returnExternalLink($items['cl_link']);
            $larray['titlealt']    = $this->get('app.utils')->cleanTitleDataAlt($items['cl_link']);
            $larray['social_type'] = $this->get('TTRouteUtils')->returnSocialTypeFromLink($items['cl_link']);
            $links_array[] = $larray;
            $customized_array[] = $larray;
        }
        $this->data['customized_array'] = $customized_array;

        $social_array = array();
        $options = array(
            'channelid' => $channel_id,
            'is_social' => 1
        );
        $arraychannellinks = $this->get('ChannelServices')->getChannelExternalLinks($options);
        foreach ($arraychannellinks as $items) {
            $larray = array();
            $larray['id']          = $items['cl_id'];
            $larray['link']        = $this->get('TTRouteUtils')->returnExternalLink($items['cl_link']);
            $larray['titlealt']    = $this->get('app.utils')->cleanTitleDataAlt($items['cl_link']);
            $larray['social_type'] = $this->get('TTRouteUtils')->returnSocialTypeFromLink($items['cl_link']);
            $links_array[] = $larray;
            $social_array[] = $larray;
        }
        $this->data['social_array'] = $social_array;

        $this->data['links_array'] = $links_array;

        $this->data['channel_id'] = $channel_id;

        if (isset($channelInfo['count_albums'])) {
            $this->data['count_albums'] = $channelInfo['count_albums'];
            $this->data['channel_albums'] = $this->get('TTRouteUtils')->returnChannelLink($this->data['LanguageGet'], 'albums', $channel_url);
        }

        if (isset($channelInfo['count_videos'])) {
            $this->data['count_videos'] = $channelInfo['count_videos'];
            $this->data['channel_videos'] = $this->get('TTRouteUtils')->returnChannelLink($this->data['LanguageGet'], 'videos', $channel_url);
        }

        if (isset($channelInfo['count_photos'])) {
            $this->data['count_photos'] = $channelInfo['count_photos'];
            $this->data['channel_photos'] = $this->get('TTRouteUtils')->returnChannelLink($this->data['LanguageGet'], 'photos', $channel_url);
        }

        $this->data['about'] = $channelInfo['c_smallDescription'];
        $this->data['phone'] = $channelInfo['c_phone'];
        $this->data['zipcode'] = $channelInfo['c_zipCode'];
        $this->data['city'] = $channelInfo['c_city'];
        $this->data['city_id'] = $channelInfo['c_cityId'];
        $this->data['category'] = $channelInfo['c_category'];
        $this->data['country'] = $channelInfo['c_country'];
        $this->data['country_name'] = $channelInfo['country_name'];
        $this->data['street'] = $channelInfo['c_street'];
        $this->data['defaultLink'] = $channelInfo['c_defaultLink'];
        $this->data['keywords'] = $channelInfo['c_keywords'];
    }

    public function channel301RedirectAction($srch = '', $seotitle, $seodescription, $seokeywords) {
        $srch_array = explode('/', $srch);
        if (sizeof($srch_array) > 1) {
            $srch = $srch_array[1];
        }

        $channelInfo = $this->get('ChannelServices')->channelInfoFromURL($srch, $this->data['LanguageGet']);

        if (!$channelInfo) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }

        return $this->redirectToLangRoute('_channel', array('srch' => $srch), 301);
    }

    public function channelAlbumsRedirectAction($srch = '', $seotitle, $seodescription, $seokeywords) {
        return $this->redirectToLangRoute('_channel_albums', array('srch' => $srch), 301);
    }

    public function channelAlbumsAction($srch = '', $seotitle, $seodescription, $seokeywords) {
        return $this->getChannelPhotosVideosData($srch, 'albums', $seotitle, $seodescription, $seokeywords);
    }

    public function channelPhotosRedirectAction($srch = '', $seotitle, $seodescription, $seokeywords) {
        return $this->redirectToLangRoute('_channel_photos', array('srch' => $srch), 301);
    }

    public function channelPhotosAction($srch = '', $seotitle, $seodescription, $seokeywords) {
        return $this->getChannelPhotosVideosData($srch, 'i', $seotitle, $seodescription, $seokeywords);
    }

    public function channelVideosRedirectAction($srch = '', $seotitle, $seodescription, $seokeywords) {
        return $this->redirectToLangRoute('_channel_videos', array('srch' => $srch), 301);
    }

    public function channelVideosAction($srch = '', $seotitle, $seodescription, $seokeywords) {
        return $this->getChannelPhotosVideosData($srch, 'v', $seotitle, $seodescription, $seokeywords);
    }

    public function getChannelPhotosVideosData($srch = '', $type = 'i', $seotitle, $seodescription, $seokeywords) {
        $channelInfo = $this->get('ChannelServices')->channelInfoFromURL($srch, $this->data['LanguageGet']);

        if (!$channelInfo) {
            return $this->redirectToLangRoute('_channels', array(), 301);
        }
        $limit = 30;

        $page_name = 'photos';
        if ($type == 'v') {
            $page_name = 'videos';
        } else if ($type == 'albums') {
            $page_name = 'albums';
        }

        $this->getChannelCommunData($channelInfo, $page_name, false, $seotitle, $seodescription, $seokeywords);
        $this->data['datapagename'] = 'channel_photos_videos';
        $mediaCount = $this->data['count_photos'];
        if ($type == 'v') {
            $mediaCount = $this->data['count_videos'];
        } else if ($type == 'albums') {
            $mediaCount = $this->data['count_albums'];
        }
        $this->data['page_count'] = ceil($mediaCount / $limit) - 1;
        ;
        $this->data['type'] = $type;
        $channel_id = $channelInfo['c_id'];

        $media_array = array();
        if ($type == 'albums') {
            $srch_options = array(
                'limit' => $limit,
                'page' => 0,
                'channel_id' => $channel_id
            );
            $medialist = $this->get('PhotosVideosServices')->getAlbumSearch($srch_options);

            if (!$medialist || sizeof($medialist) == 0) {
                return $this->redirectToLangRoute('_channel', array('srch' => $srch), 301);
            }

            foreach ($medialist as $v_item) {
                $varr = array();
                $varr['img'] = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($v_item, 'small');
                $varr['id'] = $v_item['a_id'];
                $varr['type'] = $type;
                $varr['link'] = $this->get("TTMediaUtils")->ReturnAlbumUriFromArray($v_item, $this->data['LanguageGet']);
                $varr['title'] = $this->get('app.utils')->htmlEntityDecode($v_item['a_catalogName']);
                $varr['titlealt'] = $this->get('app.utils')->cleanTitleDataAlt($v_item['a_catalogName']);
                $media_array[] = $varr;
            }
        } else {
            $srch_options = array(
                'limit' => $limit,
                'page' => 0,
                'is_public' => 2,
                'channel_id' => $channel_id,
                'owner_id' => $channelInfo['c_ownerId'],
                'type' => $type,
                'lang' => $this->data['LanguageGet']
            );
            $media_array = $this->get('PhotosVideosServices')->mediaSearchCommonArray($srch_options);

            if (!$media_array || sizeof($media_array) == 0) {
                return $this->redirectToLangRoute('_channel', array('srch' => $srch), 301);
            }
        }
        $this->data['media_array'] = $media_array;

        return $this->render('channels/channel-photos-videos.twig', $this->data);
    }

    public function getChannelMediaDataAction()
    {
        $request    = Request::createFromGlobals();
        $user_id    = $this->userGetID();
        $type = $request->request->get('media_type', '');
        $channel_id   = intval($request->request->get('media_id', 0));
        $page       = intval($request->request->get('page', 0));
        $limit      = 30;


        $data_list = array();
        $is_owner = 0;
        $data_list['media_array'] = $media_array;
        $data_list['is_owner'] = $is_owner;

        $channelinfo = $this->get('ChannelServices')->channelGetInfo( $channel_id, $this->data['LanguageGet'] );
        if ( !$channelinfo )
        {
            $all_info['data']  = $this->render('channels/channel-photos-videos_in.twig', $data_list)->getContent();
            $res = new Response(json_encode($all_info));
            $res->headers->set('Content-Type', 'application/json');

            return $res;
        }
        if ( $channelinfo['c_ownerId']==$user_id ) $is_owner = 1;

        $media_array = array();
        if( $type=='albums' )
        {
            $srch_options = array (
                'limit' => $limit,
                'page' => $page,
                'channel_id' => $channel_id
            );
            $medialist = $this->get('PhotosVideosServices')->getAlbumSearch( $srch_options );

            foreach ($medialist as $v_item) {
                $varr             = array();
                $varr['img']      = $this->get("TTMediaUtils")->mediaReturnSrcLinkFromArray($v_item, 'small');
                $varr['id']       = $v_item['a_id'];
                $varr['type']     = $type;
                $varr['link']     = $this->get("TTMediaUtils")->ReturnAlbumUriFromArray( $v_item, $this->data['LanguageGet'] );
                $varr['title']    = $this->get('app.utils')->htmlEntityDecode($v_item['a_catalogName']);
                $varr['titlealt'] = $this->get('app.utils')->cleanTitleDataAlt($v_item['a_catalogName']);
                $media_array[]    = $varr;
            }
        } else {
            $srch_options = array(
                'limit' => $limit,
                'page' => $page,
                'is_public' => 2,
                'channel_id' => $channel_id,
                'owner_id' => $channelinfo['c_ownerId'],
                'type' => $type,
                'lang' => $this->data['LanguageGet']
            );
            $media_array = $this->get('PhotosVideosServices')->mediaSearchCommonArray($srch_options);
        }
        $data_list['media_array'] = $media_array;
        $data_list['is_owner'] = $is_owner;

        $all_info['data']  = $this->render('channels/channel-photos-videos_in.twig', $data_list)->getContent();

        $res = new Response(json_encode($all_info));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

}
