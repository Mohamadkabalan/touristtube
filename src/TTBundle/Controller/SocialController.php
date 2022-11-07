<?php

namespace TTBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TTBundle\Entity\CmsReport;
use TTBundle\Form\Type\CmsReportType;

class SocialController extends DefaultController
{

    public function faqAction($seotitle, $seodescription, $seokeywords)
    {
        $request = $this->get('request');
        $lang    = $request->getLocale();
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        $this->setHreflangLinks("/faq");
        
        return $this->render('social/'.$lang.'_faq.html.twig', $this->data);
    }

    public function termsAndConditionsAction($web = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['webview'] = $web;
        $request               = $this->get('request');
        $lang                  = $request->getLocale();
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        $this->setHreflangLinks("/terms-and-conditions");
        
        return $this->render('social/'.$lang.'_terms-and-conditions.html.twig', $this->data);
    }

    public function cookiePolicyAction($web = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['webview'] = $web;
        $request               = $this->get('request');
        $lang                  = $request->getLocale();
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        $this->setHreflangLinks("/cookie-policy");
        
        return $this->render('social/'.$lang.'_cookie-policy.twig', $this->data);
    }

    public function aboutUsAction($seotitle, $seodescription, $seokeywords)
    {
        $request = $this->get('request');
        $lang    = $request->getLocale();
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        $this->setHreflangLinks("/about-us");
        
        return $this->render('social/'.$lang.'_about_us.html.twig', $this->data);
    }
    
    public function helpAction($web = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['webview'] = $web;
        $request               = $this->get('request');
        $lang                  = $request->getLocale();
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->setHreflangLinks("/help");
        
        return $this->render('social/'.$lang.'_help.html.twig', $this->data);
    }

    public function privacyPolicyAction($web = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['webview'] = $web;
        $request               = $this->get('request');
        $lang                  = $request->getLocale();
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->setHreflangLinks("/privacy-policy");
        
        return $this->render('social/'.$lang.'_privacy_policy.html.twig', $this->data);
    }
    
     public function contactAction($seotitle, $seodescription, $seokeywords)
    {
        $request = $this->get('request');
        $lang    = $request->getLocale();
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->setHreflangLinks("/contact");
        
        return $this->render('social/'.$lang.'_contact.html.twig', $this->data);
    }

    public function disclaimerAction($web = '', $seotitle, $seodescription, $seokeywords)
    {
        $this->data['webview'] = $web;
        $request               = $this->get('request');
        $lang                  = $request->getLocale();
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->setHreflangLinks("/disclaimer");
        
        return $this->render('social/'.$lang.'_disclaimer.html.twig', $this->data);
    }

    public function supportAction(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        $lang = $request->getLocale();
        if ($this->data['aliasseo'] == '') {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }
        $this->data['flashMessage']  = '';
        $this->data['flashMessage2'] = '';
        $this->setHreflangLinks("/support");
        

        $form = $this->createForm(new CmsReportType());
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $first_name = $form->get('first_name')->getData();
            $last_name = $form->get('last_name')->getData();
            $email = $form->get('email')->getData();
            $msg = $form->get('msg')->getData();

            if (!$this->get('app.utils')->check_email_address($email))
            {
                $this->data['flashMessage2'] = $this->translator->trans('Please enter a valid email');
            } else if ( $first_name =='')
            {
                $this->data['flashMessage2'] = $this->translator->trans('First name should not be blank.');
            } else if ( $last_name =='')
            {
                $this->data['flashMessage2'] = $this->translator->trans('Last name should not be blank.');
            } else if ( $msg =='')
            {
                $this->data['flashMessage2'] = $this->translator->trans('Message should not be blank.');
            } else {
                try {
                    $options['msg'] = $form->get('msg')->getData();
                    $options['title'] = $form->get('first_name')->getData().' '.$form->get('last_name')->getData();
                    $options['email'] = $form->get('email')->getData();

                    $report_id = $this->get('UserServices')->addReportData( $options );
                    
                    $this->data['flashMessage'] = $this->translator->trans('Your message has been submitted! Thanks!');
                } catch (Exception $e) {
                    $this->data['flashMessage2'] = $this->translator->trans('Message not Inserted!');
                }
            }
        }
        
        return $this->render('social/'.$lang.'_support.html.twig', $this->data);
    }

    public function addRestaurantRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_best_restaurants_restaurants', array(), 301);
    }
    
    public function thingsToDo2Action()
    {
        return $this->redirectToLangRoute('_thingsToDo', array(), 301);
    }

    public function thingsToDoWrongAction()
    {
        return $this->redirectToLangRoute('_thingsToDo', array(), 301);
    }

    public function showOnMapAction(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        $user_id = $this->data['USERID'];

        

        $requestData = $request->query->all();
        $twigData = $this->get('ReviewsServices')->showOnMap( $requestData['type'], $requestData['id'], $user_id, $this->data['LanguageGet'] );
        if (!$twigData) return $this->pageNotFoundAction();
        return $this->render('gmaps\GMaps.twig', $twigData);
    }

    public function thingsToDoAction(Request $request, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['datapagename'] = 'things_to_do';
        $this->setHreflangLinks($this->generateLangRoute('_thingsToDo'), true, true);
        
        if ($this->data['aliasseo'] == '') {
            $action_text_display    = $this->translator->trans(/** @Ignore */$seotitle, array(), 'seo');
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display          = $this->translator->trans(/** @Ignore */$seodescription, array(), 'seo');
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_text_display       = $this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo');
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        
        $options = array(            
            'show_main' => null,
            'lang' => $this->data['LanguageGet'],
            'from_mobile' => 0
        );
        $thingstodoRegion_All = $this->get('ApiDiscoverServices')->thingsTodoRegionQuery( $options );
        
        $this->data['search_array']  = array();
        $this->data['mainEntityArray'] = $thingstodoRegion_All;
        $this->data['firstbreadtitle'] = '';
        $this->data['itemType'] = 'Continent';
        $this->data['t_h3']            = '';
        $this->data['t_p3']            = '';
        $this->data['t_h4']            = '';
        $this->data['t_p4']            = '';
        $this->data['description']   = '';
        return $this->render('social/things_to_do.twig', $this->data);
    }
    
    public function thingsToDoRegionAction(Request $request, $aliasRes, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['datapagename'] = 'things_to_do';
        $routepath = $this->getRoutePath($request);
        $region    = str_replace('Things-to-do-in-', '', $routepath);

        $aliasid          = explode("/", $aliasRes['0']['entityId']);
        $txt_id_init      = $aliasid[1];
        $aliastype        = $aliasRes['0']['entityType'];
        $secondbreadlink  = '';
        $secondbreadtitle = '';
        $this->setHreflangLinks("/".$aliasRes['0']['alias']);
        $search_array = array();
        if ($aliastype == "things-to-do-country") {
            $thingstodoInfo   = $this->container->get('ThingsToDoServices')->getThingstodoInfoCountry( $txt_id_init, $this->data['LanguageGet'] );
            $thingstodoInfo1  = $this->container->get('ThingsToDoServices')->getThingstodoInfoRegion( $thingstodoInfo['t_parentId'], $this->data['LanguageGet'] );
            $secondbreadlink  = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$thingstodoInfo1['a_alias']);
            $secondbreadtitle = $thingstodoInfo1['t_title'];
            if ($thingstodoInfo1['ml_title'] != '') $secondbreadtitle = $thingstodoInfo1['ml_title'];
            
            $cityId = intval($thingstodoInfo['t_cityId']);
            $countryCode = $thingstodoInfo['t_countryCode'];
            $stateCode = $thingstodoInfo['t_stateCode'];
            $stateName = $thingstodoInfo['s_stateName'];
            $countryName = $thingstodoInfo['c_name'];
            $cityName = $thingstodoInfo['w_name'];            
            
            $link_name = '';
            if( $cityName!='' ){
                $link_name = $cityName;
            }else if( $stateName!='' ){
                $link_name = $stateName;
            }else if( $countryName!='' ){
                $link_name = $countryName;
            }
            if( $thingstodoInfo['w_countryCode']!='' ) $countryCode = $thingstodoInfo['w_countryCode'];
            if( $countryCode=='' )
            {
                $link_name = '';
            }

            $link_options = array(
                'link_name' => $link_name,
                'city_id' => $cityId,
                'state_code' => $stateCode,
                'country_code' => $countryCode,
                'state_name' => $stateName,
                'country_name' => $countryName,
                'city_name' => $cityName,
                'route_path' => $routepath,
                'type' => array( 'where_is', 'discover', 'hotels_in' )
            );
            $search_array = $this->returnSearchLinksArray( $link_options );
        }else {
            $thingstodoInfo = $this->container->get('ThingsToDoServices')->getThingstodoInfoRegion( $txt_id_init, $this->data['LanguageGet'] );
        }

        $this->data['search_array']  = $search_array;

        $t_h3 = '';
        $t_p3 = '';
        $t_h4 = '';
        $t_p4 = '';
        if ($thingstodoInfo['t_h3'] || $thingstodoInfo['ml_h3']) {
            $t_h3 = $thingstodoInfo['t_h3'];
            if ($thingstodoInfo['ml_h3'] != '') $t_h3 = $thingstodoInfo['ml_h3'];            
        }
        if ($thingstodoInfo['t_p3'] || $thingstodoInfo['ml_p3']) {
            $t_p3            = $thingstodoInfo['t_p3'];
            if ($thingstodoInfo['ml_p3'] != '') $t_p3 = $thingstodoInfo['ml_p3'];
        }
        if ($thingstodoInfo['t_h4'] || $thingstodoInfo['ml_h4']) {
            $t_h4            = $thingstodoInfo['t_h4'];
            if ($thingstodoInfo['ml_h4'] != '') $t_h4 = $thingstodoInfo['ml_h4'];
        }
        if ($thingstodoInfo['t_p4'] || $thingstodoInfo['ml_p4']) {
            $t_p4            = $thingstodoInfo['t_p4'];
            if ($thingstodoInfo['ml_p4'] != '') $t_p4 = $thingstodoInfo['ml_p4'];
        }
        $t_description = '';
        if ($thingstodoInfo['t_description'] || $thingstodoInfo['ml_description']) {
            $t_description = $thingstodoInfo['t_description'];
            if ($thingstodoInfo['ml_description'] != '') $t_description = $thingstodoInfo['ml_description'];
        }
        $firstbreadlink           = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$thingstodoInfo['a_alias']);
        $firstbreadtitle          = $thingstodoInfo['t_title'];
        if ($thingstodoInfo['ml_title'] != '') $firstbreadtitle          = $thingstodoInfo['ml_title'];
        $this->data['secondbreadlink']  = $secondbreadlink;
        $this->data['secondbreadtitle'] = $secondbreadtitle;
        $this->data['firstbreadlink']   = $firstbreadlink;
        $this->data['firstbreadtitle']  = $firstbreadtitle;
        $this->data['t_h3']          = $t_h3;
        $this->data['t_p3']          = $t_p3;
        $this->data['t_h4']          = $t_h4;
        $this->data['t_p4']          = $t_p4;
        $this->data['description'] = $t_description;
        if ($this->data['aliasseo'] == '') {
            $action_array           = array();
            $action_array[]         = $firstbreadtitle;
            $action_text_display    = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array                 = array();
            $action_array[]               = $region;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);

            $action_array              = array();
            $action_array[]            = $region;
            $action_text_display       = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        
        $itemType                 = '';
        $options = array(
            'show_main' => null,
            'lang' => $this->data['LanguageGet'],
            'from_mobile' => 0,
            'parent_id' => $txt_id_init
        );

        if ($aliastype == "things-to-do-country") {
            $thingstodoRegion_All = $this->get('ApiDiscoverServices')->thingsTodoSearchQuery( $options );
            $itemType              = 'City';
        } else {
            $thingstodoRegion_All = $this->get('ApiDiscoverServices')->thingsTodoCountryQuery( $options );
            $itemType              = 'Country';
        }

        $this->data['mainEntityArray'] = $thingstodoRegion_All;
        $this->data['itemType'] = $itemType;
        return $this->render('social/things_to_do.twig', $this->data);
    }
    
    public function topthingsToDo2Action($target)
    {
        return $this->redirectToLangRoute('_things_to_do_general', array('target' => $target), 301);
    }

    public function getAliasResultFromRoute($routepath)
    {
        $em    = $this->getDoctrine()->getManager();
        $qb    = $em->createQueryBuilder('DS')
            ->select('a.id,a.alias,a.entityId,a.entityType')
            ->from('TTBundle:Alias', 'a')
            ->where("a.alias = :Alias AND a.entityType IN('top-things-to-do', 'things-to-do-country', 'things-to-do-region')")
            ->setParameter(':Alias', $routepath)
            ->setMaxResults(1);
        $query = $qb->getQuery();
        return $query->getArrayResult();
    }

    public function topthingsToDoAction(Request $request, $page = 1, $seotitle, $seodescription, $seokeywords)
    {
        if( !is_numeric( $page ) )
        {
            $thingstodoDetail = $this->container->get('ThingsToDoServices')->getThingstodoDetailsSlug( $page, $this->data['LanguageGet'] );
            
            if( $thingstodoDetail )
            {
                $thingstodoDetail = $thingstodoDetail[0];
                $thingstodoDetailDivisions = $this->container->get('ThingsToDoServices')->getThingstodoDivisions( $thingstodoDetail['td_id'], NULL, NULL, true );

                if( $thingstodoDetailDivisions )
                {
                    $seotitle = "%s | Tourist Tube";
                    $seodescription = 'Check the best places to visit in %1$s and see the %2$s in a very unique experience. %2$s 360 View only on Tourist Tube';
                    $seokeywords = '%1$s 360, 360 photo, Top Things To Do In %2$s, Activities To Do %2$s, Visit %2$s, %2$s 360, visit %2$s 360, %2$s points of interest, %1$s, unique experience, places to visit in %2$s';
                    return $this->thingsToDoDetails360Action($request, $thingstodoDetail, $thingstodoDetailDivisions, $seotitle, $seodescription, $seokeywords);
                }                
            }
        }
        
        if ($page == '' || $page < 1) $page         = 1;
        
        $routepath    = $this->getRoutePath($request);
        $aliasRes     = $this->getAliasResultFromRoute($routepath);
        $lc_routepath = strtolower($routepath);
        if (sizeof($aliasRes) <= 0) {
            $routepath1 = substr($routepath, 0, -1);
            $aliasRes   = $this->getAliasResultFromRoute($routepath1);
            if (sizeof($aliasRes) > 0) {
                $routepath = $routepath1;
            } else {
                $routepath = $this->getRoutePath($request, true, $page);
                $aliasRes  = $this->getAliasResultFromRoute($routepath);
            }
        }
        if (sizeof($aliasRes) <= 0) {
            if ($lc_routepath == strtolower('top-five-things-to-do-in-Paris')) {
                $routepath = 'Things-to-do-in-Paris';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Ho-Chi-Minh-Vietnam')) {
                $routepath = 'Things-to-do-in-Ho-Chi-Minh';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Hanoi-Vietnam')) {
                $routepath = 'Things-to-do-in-Hanoi';
            } else if ($lc_routepath == strtolower('Things-to-do-in-America')) {
                $routepath = 'Things-to-do-in-North-America';
            } else if ($lc_routepath == strtolower('Things-to-do-San-Diego')) {
                $routepath = 'Things-to-do-in-San-Diego';
            } else if ($lc_routepath == strtolower('Things-to-do-in- Rome') || $lc_routepath == strtolower('Things-to-do-in-%20Rome')) {
                $routepath = 'Things-to-do-in-Rome';
            } else if ($lc_routepath == strtolower('Things-to-do-in-BrusselsBrussels')) {
                $routepath = 'Things-to-do-in-Brussels';
            } else if ($lc_routepath == strtolower('Things-to-do-Middle-East')) {
                $routepath = 'Things-to-do-in-Middle-East';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Bsharre') || $lc_routepath == strtolower('Things-to-do-in-Bsharri')) {
                $routepath = 'Things-to-do-in-Bcharre';
            } else if ($lc_routepath == strtolower('Things-to-do-in-London.')) {
                $routepath = 'Things-to-do-in-London';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Sucre-Bolivia')) {
                $routepath = 'Things-to-do-in-Sucre';
            } else if ($lc_routepath == strtolower('Things-to-do-in-The-Vatican')) {
                $routepath = 'Things-to-do-in-Vatican-City';
            } else if ($lc_routepath == strtolower('Things-to-do-in-IstanbulThe')) {
                $routepath = 'Things-to-do-in-Istanbul';
            } else if ($lc_routepath == strtolower('Things-to-do-in-PragueThe')) {
                $routepath = 'Things-to-do-in-Prague';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Beiteddine')) {
                $routepath = 'Things-to-do-in-El-Chouf';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Nawaka')) {
                $routepath = 'Things-to-do-in-Yasawa-Islands';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Andqet')) {
                $routepath = 'Things-to-do-in-Lebanon';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Nasinu')) {
                $routepath = 'Things-to-do-in-Viti-Levu';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Medenine')) {
                $routepath = 'Things-to-do-in-Tunisia';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Monaco-City')) {
                $routepath = 'Things-to-do-in-Monaco';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Monacos-Surroundings')) {
                $routepath = 'Things-to-do-in-Monaco-and-Monte-Carlo';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Monte-Carlo')) {
                $routepath = 'Things-to-do-in-Monaco-and-Monte-Carlo';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Geneve')) {
                $routepath = 'Things-to-do-in-Geneva';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Taipei-Capital-of-Taiwan')) {
                $routepath = 'Things-to-do-in-Taipei';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Boon-Lay')) {
                $routepath = 'Things-to-do-in-Singapore';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Victoria-')) {
                $routepath = 'Things-to-do-in-Victoria';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Libreville-Gabon')) {
                $routepath = 'Things-to-do-in-Libreville';
            } else if ($lc_routepath == strtolower('Things-to-do-in-the-Carribean')) {
                $routepath = 'Things-to-do-in-the-Caribbean';
            } else if ($lc_routepath == strtolower('Things-to-do-in-South-Caribbean-Coast-Autonomous-Region--RACCS')) {
                $routepath = 'Things-to-do-in-South-Caribbean-Coast-Autonomous-Region--RACS';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Hattathings%20to%20do%20in%20Hatta')) {
                $routepath = 'Things-to-do-in-Hatta';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Madrid%C2%A0%C2%A0%20%C2%A0%C2%A0') || $lc_routepath == strtolower('Things-to-do-in-Madrid  %20  ')) {
                $routepath = 'Things-to-do-in-Madrid';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Macau">what') || $lc_routepath == strtolower('Things-to-do-in-Macau%22%3Ewhat')) {
                $routepath = 'Things-to-do-in-Macau';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Madrid%C2%A0%C2%A0%20%C2%A0%C2%A0%C2%A0') || $lc_routepath == strtolower('Things-to-do-in-Madrid  %20   ')) {
                $routepath = 'Things-to-do-in-Madrid';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Illinois-')) {
                $routepath = 'Things-to-do-in-Illinois';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Bonaire-Saint-Eustatius-and-Saba')) {
                $routepath = 'Things-to-do-in-Bonaire';
            } else if ($lc_routepath == strtolower('Things-to-do-in-St-Kitts-and-Nevis')) {
                $routepath = 'Things-to-do-in-Saint-Kitts-and-Nevis';
            } else if ($lc_routepath == strtolower('Things-to-do-in-Azuai')) {
                $routepath = 'Things-to-do-in-Azuay';
            } else {
                return $this->pageNotFoundAction();
            }
            return $this->redirect($this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],$routepath), 301);
//            $aliasRes = $this->getAliasResultFromRoute($routepath);
        }
        $aliastype = $aliasRes['0']['entityType'];
        if ($aliastype == "things-to-do-country" || $aliastype == "things-to-do-region") {
            return $this->thingsToDoRegionAction($request, $aliasRes, $seotitle, $seodescription, $seokeywords);
        } else {
            return $this->topthingsToDoCityAction($request, $page, $aliasRes, $seotitle, $seodescription, $seokeywords);
        }
    }

    public function thingsToDo360FullTourAction( $slug, $seotitle, $seodescription, $seokeywords )
    {
        $thingstodoDetail = $this->container->get('ThingsToDoServices')->getThingstodoDetailsSlug( $slug, $this->data['LanguageGet'] );

        if( $thingstodoDetail )
        {
            $thingstodoDetail = $thingstodoDetail[0];
            $thingstodoDetailDivisions = $this->container->get('ThingsToDoServices')->getThingstodoDivisions( $thingstodoDetail['td_id'], NULL, NULL, true, true );

            $mainData  = $thingstodoDetailDivisions['data'];
            $divisions = $mainData['divisions'];

            $menu360 = array();
            foreach ($divisions as $item)
            {
                $item_array = array();
                $divId = $item['id'];
                $subDivId = null;
                if( $item['parent_id'] == '' ) continue;
                if( $item['parent_id'] != '' )
                {
                    $subDivId = $divId;
                    $divId    = $item['parent_id'];
                }
                

                $item_array['name']      = $this->get('app.utils')->htmlEntityDecode($item['name']);
                $item_array['type'] = 'thingstodo';
                $item_array['entityName']      = $this->get('app.utils')->htmlEntityDecode($mainData['name']);
                $item_array['country'] = strtolower($mainData['country_code']);
                $item_array['entityId'] = $mainData['id'];
                $item_array['divisionId'] = $subDivId;
                $item_array['groupId'] = $item['group_id'];
                $item_array['groupName'] = $item['group_name'];
                $item_array['catgId'] = $item['category_id'];
                $item_array['subDivisionId'] = '';
                $item_array['data_icon'] = false;
                    
                $menu360[] = $item_array;
            }
            $this->data['menu360'] = $menu360;

            $homeTT               = array();
            $homeTT[]             = array('name' => '', 'img' => $this->get("TTRouteUtils")->generateMediaURL('/media/images/360-photos/en-logo256.png'), 'link' => '/', 'title' => 'Tourist Tube');
            $this->data['homeTT'] = $homeTT;

            $menuTT = array();
            $title = $this->translator->trans('Things to do');
            $titlealt   = $this->get('app.utils')->cleanTitleDataAlt($title);
            $title      = $this->get('app.utils')->htmlEntityDecode($title);
            $link   = 'things-to-do';
            $menuTT[] = array('name' => "$title", 'img' => '', 'link' => "/$link", 'title' => "$titlealt");
            
            $title = $thingstodoDetail['tr_title'];
            if ($thingstodoDetail['trml_title'] != '') $title = $thingstodoDetail['trml_title'];
            $titlealt   = $this->get('app.utils')->cleanTitleDataAlt($title);
            $title      = $this->get('app.utils')->htmlEntityDecode($title);
            $link   = $thingstodoDetail['tra_alias']; 
            $menuTT[] = array('name' => "$title", 'img' => '', 'link' => "/$link", 'title' => "$titlealt");

            $title = $thingstodoDetail['tc_title'];
            if ($thingstodoDetail['tcml_title'] != '') $title = $thingstodoDetail['tcml_title'];
            $titlealt   = $this->get('app.utils')->cleanTitleDataAlt($title);
            $title      = $this->get('app.utils')->htmlEntityDecode($title);
            $link   = $thingstodoDetail['tca_alias'];
            $menuTT[] = array('name' => "$title", 'img' => '', 'link' => "/$link", 'title' => "$titlealt");

            $title = $thingstodoDetail['t_title'];
            if ($thingstodoDetail['tml_title'] != '') $title = $thingstodoDetail['tml_title'];
            $titlealt   = $this->get('app.utils')->cleanTitleDataAlt($title);
            $title      = $this->get('app.utils')->htmlEntityDecode($title);
            $link   = $thingstodoDetail['ta_alias'];
            $menuTT[] = array('name' => "$title", 'img' => '', 'link' => "/$link", 'title' => "$titlealt");

            $title = $thingstodoDetail['td_title'];
            if ($thingstodoDetail['tdml_title'] != '') $title = $thingstodoDetail['tdml_title'];
            $titlealt   = $this->get('app.utils')->cleanTitleDataAlt($title).' '.$this->translator->trans('360');
            $title      = $this->get('app.utils')->htmlEntityDecode($title).' '.$this->translator->trans('360');
            $link   = $thingstodoDetail['ta_alias'].'/'.$thingstodoDetail['td_slug'];
            $menuTT[] = array('name' => "$title", 'img' => '', 'link' => "/$link", 'title' => "$titlealt");

            $this->data['menuTT'] = $menuTT;

            // Page title
            $this->data['pageTitle'] = "360-photos ".$thingstodoDetail['td_title'];

            $image  = "media/thingstodo/";
            $sourcename = 'default_big.jpg';
            if ($thingstodoDetail['td_image'] != "")
            {
                $sourcepath = 'media/thingstodo/';
                $sourcename = $thingstodoDetail['td_image'];
            }
            $image = $this->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 180, 135, 'thumb180135', $sourcepath, $sourcepath, 50);
            if (substr($image, 0, 1) == '/') {
                $image = substr( $image, 1, strlen($image) );
            }
            $this->data['logo']  = $image;
            return $this->render('media_360/photos-360.twig', $this->data);

        } else {
            return $this->redirectToLangRoute('_thingsToDo', array(), 301);
        }
    }
    public function thingsToDoDetails360Action(Request $request, $thingstodoDetail, $thingstodoDetailDivisions, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['datapagename'] = 'things_to_do_360';
        
        $thirdbreadtitle = $thingstodoDetail['tr_title'];
        if ($thingstodoDetail['trml_title'] != '') $thirdbreadtitle = $thingstodoDetail['trml_title'];
        $thirdbreadtitlealt   = $this->get('app.utils')->cleanTitleDataAlt($thirdbreadtitle);
        $thirdbreadtitle      = $this->get('app.utils')->htmlEntityDecode($thirdbreadtitle);
        $thirdbreadlink   = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$thingstodoDetail['tra_alias']);

        $secondbreadtitle = $thingstodoDetail['tc_title'];
        if ($thingstodoDetail['tcml_title'] != '') $secondbreadtitle = $thingstodoDetail['tcml_title'];
        $secondbreadtitlealt   = $this->get('app.utils')->cleanTitleDataAlt($secondbreadtitle);
        $secondbreadtitle      = $this->get('app.utils')->htmlEntityDecode($secondbreadtitle);
        $secondbreadlink   = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$thingstodoDetail['tca_alias']);

        $firstbreadtitle = $thingstodoDetail['t_title'];
        $cityname = str_replace('Things to do in ', '', $firstbreadtitle);
        $cityname = str_replace('Top Bridges to visit in ', '', $cityname);
        $cityname = str_replace('top five things to do in ', '', $cityname);
        $cityname = str_replace('Top Destinations in ', '', $cityname);
        $cityname = str_replace('Top Ski Destinations in ', '', $cityname);
        if ($thingstodoDetail['tml_title'] != '') $firstbreadtitle = $thingstodoDetail['tml_title'];
        $firstbreadtitlealt   = $this->get('app.utils')->cleanTitleDataAlt($firstbreadtitle);
        $firstbreadtitle      = $this->get('app.utils')->htmlEntityDecode($firstbreadtitle);
        $firstbreadlink   = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$thingstodoDetail['ta_alias']);

        $this->data['cityname']   = $cityname;
        $this->data['firstbreadalias']   = $thingstodoDetail['ta_alias'];
        $this->data['firstbreadlink']   = $firstbreadlink;
        $this->data['firstbreadtitle']  = $firstbreadtitle;
        $this->data['firstbreadtitlealt']  = $firstbreadtitlealt;
        $this->data['secondbreadlink']  = $secondbreadlink;
        $this->data['secondbreadtitle'] = $secondbreadtitle;
        $this->data['secondbreadtitlealt'] = $secondbreadtitlealt;
        $this->data['thirdbreadlink']   = $thirdbreadlink;
        $this->data['thirdbreadtitle']  = $thirdbreadtitle;
        $this->data['thirdbreadtitlealt']  = $thirdbreadtitlealt;
        $this->data['fullTourURL']  = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.'360-photos-vr/'.$thingstodoDetail['ta_alias'].'/'.$thingstodoDetail['td_slug']);

        $title = $thingstodoDetail['td_title'];
        if ($thingstodoDetail['tdml_title'] != '') $title = $thingstodoDetail['tdml_title'];
        $titlealtSEO = $titlealt   = $this->get('app.utils')->cleanTitleDataAlt($title);
        $titlealt    = $this->translator->trans('View').' '.$titlealt.' '.$this->translator->trans('in 360 virtual tour');
        $title       = $this->translator->trans('View').' '.$this->get('app.utils')->htmlEntityDecode($title).' '.$this->translator->trans('in 360 virtual tour');
        $this->data['title']  = $title;
        $this->data['titlealt']  = $titlealt;
        $this->data['pagelink']   = $firstbreadlink.'/'.$thingstodoDetail['td_slug'];
        $this->data['slug']   = $thingstodoDetail['td_slug'];
        $this->data['entity_type']   = 'thingstodo';
        
        $this->setHreflangLinks( $this->data['pagelink'], true, true );

        $description = $thingstodoDetail['td_description'];
        if ($thingstodoDetail['tdml_description'] != '') $description = $thingstodoDetail['tdml_description'];
        $description = $this->get('app.utils')->htmlEntityDecode($description);
        $description = nl2br($description);
        $description = stripslashes($description);
        $this->data['desc']  = $description;

        $this->data['latitude']  = $thingstodoDetail['td_latitude'];
        $this->data['longitude']  = $thingstodoDetail['td_longitude'];

        $image  = $this->get("TTRouteUtils")->generateMediaURL("/media/thingstodo/default_big.jpg");
        if ($thingstodoDetail['td_image'] != "")
        {
            $sourcepath = 'media/thingstodo/';
            $sourcename = $thingstodoDetail['td_image'];
            $image = $this->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 599, 280, 'thumb599280', $sourcepath, $sourcepath, 80);
        }
        $this->data['img']  = $image;
        
        $main_division_name = ( isset($thingstodoDetailDivisions['data']['main_division_name']) && $thingstodoDetailDivisions['data']['main_division_name']!='' )? $this->get('app.utils')->htmlEntityDecode($thingstodoDetailDivisions['data']['main_division_name']).'-':'';
        
        $divisions_list =array(); 
        foreach ($thingstodoDetailDivisions['data']['divisions'] as $item)
        {
            $item_array = array();

            if( $item['parent_id'] == '' ) continue;

            $item_array['id'] = $thingstodoDetailDivisions['data']['id'];
            $item_array['country'] = strtolower($thingstodoDetailDivisions['data']['country_code']);
            $item_array['category_id'] = $item['category_id'];
            $item_array['parentdiv_id'] = $item['parent_id'];
            $item_array['division_id'] = $item['id'];
            $item_array['namealt']   = $main_division_name.$this->get('app.utils')->cleanTitleDataAlt($item['name']);
            $item_array['name']      = $main_division_name.$this->get('app.utils')->htmlEntityDecode($item['name']);

            $sourcepath = $this->container->getParameter('TTD_MEDIA_360_BASE_PATH').''.$item_array['country'].'/'.$item_array['id'].'/'.$item_array['category_id'].'/'.$item['parent_id'].'/'.$item_array['division_id'].'/';
            $sourcename = $item['image'];            
            $image = $this->get("TTMediaUtils")->createItemThumbs($sourcename, $sourcepath, 0, 0, 181, 104, 'thumb181104', $sourcepath, $sourcepath, 50);
            $item_array['img'] = $image;
            $divisions_list[] = $item_array;
        }
        $this->data['divisions_list']  = $divisions_list;

        if ($this->data['aliasseo'] == '') {
            $action_array                 = array();
            $action_array[]               = $titlealt;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            $action_array                 = array();
            $action_array[]               = $cityname;
            $action_array[]               = $titlealtSEO;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            $action_array                 = array();
            $action_array[]               = $titlealtSEO;
            $action_array[]               = $cityname;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }

        return $this->render('social/things_to_do_details_360.twig', $this->data);
    }
    
    public function topthingsToDoCityAction(Request $request, $page, $aliasRes, $seotitle, $seodescription, $seokeywords)
    {
        $this->data['datapagename'] = 'things_to_do';
        $aliasid     = explode("/", $aliasRes['0']['entityId']);
        $txt_id_init = $aliasid[1];
        if ($txt_id_init == 0) {
            return $this->redirect($this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],"things-to-do"), 301);
        }
        $aliastype       = $aliasRes['0']['entityType'];
        $txn_id          = ($txt_id_init == 0) ? null : $txt_id_init;
        $thirdbreadlink  = '';
        $thirdbreadtitle = '';
        $thingstodoInfo  = $this->container->get('ThingsToDoServices')->getThingstodoInfo( $txn_id, $this->data['LanguageGet'] );
        $thingstodoInfo1 = $this->container->get('ThingsToDoServices')->getThingstodoInfoCountry( $thingstodoInfo['t_parentId'], $this->data['LanguageGet'] );
        $thingstodoInfo2 = $this->container->get('ThingsToDoServices')->getThingstodoInfoRegion( $thingstodoInfo1['t_parentId'], $this->data['LanguageGet'] );
        $thirdbreadlink  = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$thingstodoInfo2['a_alias']);
        $thirdbreadtitle = $thingstodoInfo2['t_title'];
        if ($thingstodoInfo2['ml_title'] != '') $thirdbreadtitle = $thingstodoInfo2['ml_title'];
        $thirdbreadtitlealt   = $this->get('app.utils')->cleanTitleDataAlt($thirdbreadtitle);
        $thirdbreadtitle      = $this->get('app.utils')->htmlEntityDecode($thirdbreadtitle);
        
        $firstbreadlink   = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$thingstodoInfo['a_alias']);
        $secondbreadlink  = $this->get('app.utils')->generateLangURL( $this->data['LanguageGet'],'/'.$thingstodoInfo1['a_alias']);
        $secondbreadtitle = $thingstodoInfo1['t_title'];
        if ($thingstodoInfo1['ml_title'] != '') $secondbreadtitle = $thingstodoInfo1['ml_title'];
        $secondbreadtitlealt   = $this->get('app.utils')->cleanTitleDataAlt($secondbreadtitle);
        $secondbreadtitle      = $this->get('app.utils')->htmlEntityDecode($secondbreadtitle);
        
        $firstbreadtitle = $thingstodoInfo['t_title'];
        $cityname = str_replace('Things to do in ', '', $firstbreadtitle);
        $cityname = str_replace('Top Bridges to visit in ', '', $cityname);
        $cityname = str_replace('top five things to do in ', '', $cityname);
        $cityname = str_replace('Top Destinations in ', '', $cityname);
        $cityname = str_replace('Top Ski Destinations in ', '', $cityname);
        if ($thingstodoInfo['ml_title'] != '') $firstbreadtitle = $thingstodoInfo['ml_title'];
        $firstbreadtitlealt   = $this->get('app.utils')->cleanTitleDataAlt($firstbreadtitle);
        $firstbreadtitle      = $this->get('app.utils')->htmlEntityDecode($firstbreadtitle);
        
        $this->setHreflangLinks($firstbreadlink,true,true);

        $search_array = array();
        $cityId = intval($thingstodoInfo['t_cityId']);
        $countryCode = $thingstodoInfo['t_countryCode'];
        $stateCode = $thingstodoInfo['t_stateCode'];
        if( $thingstodoInfo['w_stateCode']!='' ) $stateCode = $thingstodoInfo['w_stateCode'];
        if( $stateCode =='' ) $stateCode = $thingstodoInfo['st_stateCode'];
        if( $thingstodoInfo['w_countryCode']!='' ) $countryCode = $thingstodoInfo['w_countryCode'];
        $stateName = $thingstodoInfo['s_stateName'];
        if( $stateName =='' ) $stateName = $thingstodoInfo['st_stateName'];
        $countryName = $thingstodoInfo['c_name'];
        $cityName = $thingstodoInfo['w_name'];
        $link_name = '';
        if( $cityName!='' ){
                $link_name = $cityName;
        }else if( $stateName!='' ){
                $link_name = $stateName;
        }else if( $countryName!='' ){
                $link_name = $countryName;
        }
        if( $countryCode=='' )
        {
            $link_name = '';
        }
        $routepath = $this->getRoutePath($request);
        
        $link_options = array(
                'link_name' => $link_name,
                'city_id' => $cityId,
                'state_code' => $stateCode,
                'country_code' => $countryCode,
                'state_name' => $stateName,
                'country_name' => $countryName,
                'city_name' => $cityName,
                'route_path' => $routepath,
                'type' => array( 'where_is', 'discover', 'hotels_in' )
            );

        $search_array = $this->returnSearchLinksArray( $link_options );

        $this->data['search_array']  = $search_array;

        $t_h3 = '';
        $t_p3 = '';
        $t_h4 = '';
        $t_p4 = '';
        if ($thingstodoInfo['t_h3'] || $thingstodoInfo['ml_h3']) {
            $t_h3 = $thingstodoInfo['t_h3'];
            if ($thingstodoInfo['ml_h3'] != '') $t_h3 = $thingstodoInfo['ml_h3'];
        }
        if ($thingstodoInfo['t_p3'] || $thingstodoInfo['ml_p3']) {
            $t_p3 = $thingstodoInfo['t_p3'];
            if ($thingstodoInfo['ml_p3'] != '') $t_p3 = $thingstodoInfo['ml_p3'];
        }
        if ($thingstodoInfo['t_h4'] || $thingstodoInfo['ml_h4']) {
            $t_h4 = $thingstodoInfo['t_h4'];
            if ($thingstodoInfo['ml_h4'] != '') $t_h4 = $thingstodoInfo['ml_h4'];
        }
        if ($thingstodoInfo['t_p4'] || $thingstodoInfo['ml_p4']) {
            $t_p4 = $thingstodoInfo['t_p4'];
            if ($thingstodoInfo['ml_p4'] != '') $t_p4 = $thingstodoInfo['ml_p4'];
        }
        $description   = $thingstodoInfo['t_description'];
        if ($thingstodoInfo['ml_description'] != '') $description   = $thingstodoInfo['ml_description'];
        $this->data['description'] = $description;
        $this->data['t_h3'] = $t_h3;
        $this->data['t_p3'] = $t_p3;
        $this->data['t_h4'] = $t_h4;
        $this->data['t_p4'] = $t_p4;
        $this->data['secondbreadlink']  = $secondbreadlink;
        $this->data['firstbreadlink']   = $firstbreadlink;
        $this->data['thirdbreadlink']   = $thirdbreadlink;
        $this->data['firstbreadtitle']  = $firstbreadtitle;
        $this->data['firstbreadtitlealt']  = $firstbreadtitlealt;
        $this->data['secondbreadtitle'] = $secondbreadtitle;
        $this->data['secondbreadtitlealt'] = $secondbreadtitlealt;
        $this->data['thirdbreadtitle']  = $thirdbreadtitle;
        $this->data['thirdbreadtitlealt']  = $thirdbreadtitlealt;
        $topthingstodoList_count            = $this->container->get('ThingsToDoServices')->getRelatedThingsToDoList(array(
            'parent_id' => $txn_id,
            'lang' => $this->data['LanguageGet'],
            'n_results' => true
        ));
        $count_per_page                     = 10;
        
        $search_paging_output               = $this->get('TTRouteUtils')->getRelatedChannelPagination($topthingstodoList_count, $count_per_page, $page, $aliasRes['0']['alias'], '/', 2, '', $this->data['LanguageGet'] );
        $this->data['search_paging_output'] = $search_paging_output;
        $this->data['count_per_page']       = $count_per_page;
        $this->data['current_page']         = $page;
        
        $has_360 = 0;
        $options = array(
            'lang' => $this->data['LanguageGet'],
            'from_mobile' => 0,
            'parent_id' => $txn_id,
            'limit' => $count_per_page,
            'page' => $page,
            'orderby' => 'orderDisplay',
            'order' => 'd'
        );

        $thingstodoRegion_All = $this->get('ApiDiscoverServices')->thingsTodoDetailsQuery( $options );

        foreach ($thingstodoRegion_All as $itemData) {
            if ( $itemData['exists_360'] == 1 ) {
                $has_360 = 1;
                break;
            }
        }

        $this->data['mainEntityArray'] = $thingstodoRegion_All;
        $this->data['has_360'] = $has_360;

        $options = array(
            'show_main' => null,
            'limit' => 4,
            'lang' => $this->data['LanguageGet'],
            'from_mobile' => 0,
            'parent_id' => $thingstodoInfo['t_parentId'],
            'except_id' => $thingstodoInfo['t_id']
        );
        $thingstodocityList_All = $this->get('ApiDiscoverServices')->thingsTodoSearchQuery( $options );
        $this->data['thingstodocityList'] = $thingstodocityList_All;
        
        $discoverLink = '';
        $entity_name = '';
        $cityInfoid            = intval($thingstodoInfo['t_cityId']);
        $cityInfostateCode     = $thingstodoInfo['t_stateCode'];
        $cityInfocountryCode   = $thingstodoInfo['t_countryCode'];
        if ($cityInfoid > 0) {
            $cityInfo            = $this->get('CitiesServices')->worldcitiespopInfo($cityInfoid);
            $entity_name = $this->get('app.utils')->htmlEntityDecode($cityInfo[0]->getName());
            $discoverLink = $this->get('TTRouteUtils')->returnDiscoverDetailedLink($this->data['LanguageGet'], $entity_name, $cityInfoid, $cityInfo[0]->getStateCode(), $cityInfo[0]->getCountryCode());
        } else if ($cityInfostateCode != '' && $cityInfocountryCode != '') {
            $states = $this->get('CitiesServices')->worldStateInfo($cityInfocountryCode, $cityInfostateCode);
            if ($states && sizeof($states) > 0) {
                $entity_name = $this->get('app.utils')->htmlEntityDecode($states[0]->getStateName());
                $discoverLink = $this->get('TTRouteUtils')->returnDiscoverDetailedLink($this->data['LanguageGet'], $entity_name, 0, $cityInfostateCode, $cityInfocountryCode);
            }
        } else if ($cityInfocountryCode != '') {
            $country_array       = $this->get('CmsCountriesServices')->countryGetInfo($cityInfocountryCode);
            $entity_name = $this->get('app.utils')->htmlEntityDecode($country_array->getName());
            $discoverLink = $this->get('TTRouteUtils')->returnDiscoverDetailedLink($this->data['LanguageGet'], $entity_name, 0, '', $cityInfocountryCode);
        }

        $descThingstodoTitle = $descThingstodo   = '';
        if( isset($thingstodoInfo['t_descThingstodo']) && $thingstodoInfo['t_descThingstodo'] ){
            if ($thingstodoInfo['ml_descThingstodo'] != '') $descThingstodo   = $thingstodoInfo['ml_descThingstodo'];
            else $descThingstodo   = $thingstodoInfo['t_descThingstodo'];
            if($descThingstodo!='') $descThingstodoTitle = /*$firstbreadtitle.' - '.*/$this->translator->trans('Discover').' '.$entity_name;
        }
        $this->data['descThingstodo']   = $descThingstodo;
        $this->data['descThingstodoTitle']   = $descThingstodoTitle;
        $this->data['discoverLink']   = $discoverLink;
        $this->data['cityid']   = $cityInfoid;
        $this->data['cityname']   = $cityname;
        $moreDealslist = array();
        $this->data['moreDealslink'] = '';
        $this->data['toursNumber'] = 0;
        if ( $this->show_deals_block == 1 ) {
            $this->data['moreDealslink'] = $this->get('TTRouteUtils')->returnDealsSearchLink($this->data['LanguageGet'], $cityname);
            $this->data['toursNumber'] = $this->get('DealServices')->getDealTypeToursNumber(array('cityName' => $cityname),'all');
            $dealEnhancedSearchByDealNameEncoded = $this->get('DealServices')->getDealSearchByCityId($this->data['cityid'],8);
            $dealEnhancedSearchByDealNameDecoded = json_decode($dealEnhancedSearchByDealNameEncoded, true);
            $dealEnhancedSearchByDealNameList = $dealEnhancedSearchByDealNameDecoded['data'];
            if($dealEnhancedSearchByDealNameList){
                foreach ($dealEnhancedSearchByDealNameList as $itemDeal) {
                    $itemDealslist = array();
                    $itemDealslist['link'] = $itemDeal['link'];
                    $itemDealslist['name'] = $this->get('app.utils')->htmlEntityDecode($itemDeal['dealName']);;
                    $itemDealslist['namealt'] = $this->get('app.utils')->cleanTitleDataAlt($itemDeal['dealName']);;
                    $itemDealslist['img'] = $itemDeal['imagePath'];
                    $itemDealslist['price'] = number_format($itemDeal['price'], 2, '.', ',');
                    $itemDealslist['dataprice'] = $itemDeal['price'];
                    $moreDealslist[] = $itemDealslist;
                }
            }
        }
        $this->data['moreDealslist']   = $moreDealslist;
        if ($this->data['aliasseo'] == '') {
            $action_array                 = array();
            if( $this->data['has_360'] == 1 ) $firstbreadtitle .= ' '.$this->translator->trans('360 Photo');
            $action_array[]               = $firstbreadtitle;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'), $action_array);
            
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            
            $action_array                 = array();
            $action_array[]               = $cityname;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'), $action_array);
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        $descNew = array( $this->translator->trans('things to do in %1$s, places to visit in %1$s, everything you need to know about %1$s attractions on tourist tube'),
$this->translator->trans('searching for activities to do in %1$s? And best locations in %1$s? Come visit us on tourist tube web'),
$this->translator->trans('find the best tourist attractions, destinations and places to visit in %1$s, things to do in %1$s'),
$this->translator->trans('Are you searching for the top things to do in %1$s Want to know about the activities to do in %1$s Tourist tube is the solution'),
$this->translator->trans('If your planing on visiting %1$s and want to know about the things to do in %1$s, visit our web page Tourist Tube'),
$this->translator->trans('Tourist Tube provides the information needed about the things to do in %1$s, tourist attractions and activities to do in %1$s') );
        if ($page > 1 && $page < 7) {
            $this->data['seotitle'] = $this->get('app.utils')->getMultiByteSubstr( $this->data['seotitle'], 52, NULL, $this->data['LanguageGet'], false );
            $this->data['seotitle'] .= ' '.$this->translator->trans('Part').' '.$page;
            $action_array           = array();
            $action_array[]         = $cityname;
            
            $action_text_display    = vsprintf( $descNew[$page - 1], $action_array);
            if ($action_text_display != '') {
                $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
            }
            $this->data['seodescription'] = $this->translator->trans('Part').' '.$page.' '.$this->data['seodescription'];
        } else if ($page >= 7) {
            $action_array                 = array();
            $action_array[]               = $cityname.' - '.$this->translator->trans('Part').' '.$page;
            $action_text_display          = vsprintf($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'), $action_array);
            $this->data['seodescription'] = $this->translator->trans('Part').' '.$page.' '.$this->get('app.utils')->htmlEntityDecodeSEO($action_text_display);
        }
        $this->data['seotitle'] = $this->get('app.utils')->getMultiByteSubstr( $this->data['seotitle'], 60, NULL, $this->data['LanguageGet'], false );
        return $this->render('social/things_to_do_details.twig', $this->data);
    }

    public function topThingsToDoWrongAction()
    {
        return $this->redirectToLangRoute('_things_to_do_general', array('target' => 'San-Francisco'), 301);
    }

    public function topThingsToDoWrong2Action()
    {
        return $this->redirectToLangRoute('_things_to_do_general', array('target' => 'San-Diego'), 301);
    }
}