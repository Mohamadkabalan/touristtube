<?php

namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestaurantsController extends DefaultController
{

    public function bestRestaurantsAction($seotitle, $seodescription, $seokeywords)
    {
        if ($this->data['aliasseo'] == '')
        {
            $this->data['seotitle']       = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seotitle, array(), 'seo'));
            $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seodescription, array(), 'seo'));
            $this->data['seokeywords']    = $this->get('app.utils')->htmlEntityDecodeSEO($this->translator->trans(/** @Ignore */$seokeywords, array(), 'seo'));
        }

        $this->setHreflangLinks($this->generateLangRoute('_best_restaurants_restaurants'), true, true);

        $options = array(
            'limit' => 8
        );
        $discoverItem = $this->getHotelSelectedCityId($options);
        foreach ($discoverItem as $discover) {
            $adiscoverInfo['name']    = $this->translator->trans('Discover');
            $adiscoverInfo['city']    = $this->get('app.utils')->htmlEntityDecode($discover['hc_name']);
            $adiscoverInfo['namealt'] = $this->translator->trans('Discover').' '.$this->get('app.utils')->cleanTitleDataAlt($discover['hc_name']);
            $cityid                   = $discover['w_id'];
            $statecode                = '';
            $countrycode              = $discover['hc_countryCode'];
            if( $discover['w_countryCode']!='' ){
                $countrycode          = $discover['w_countryCode'];
            }
            $statecode   = $discover['w_stateCode'];
            $adiscoverInfo['link'] = $this->get('app.utils')->returnDiscoverDetailedLink($this->data['LanguageGet'], $discover['hc_name'], $cityid, $statecode, $countrycode);
            $sourcepath            = 'media/hotels/hotelbooking/hotel-main-banner/';
            $adiscoverInfo['img']  = $this->get('app.utils')->createItemThumbs($discover['hc_image'], $sourcepath, 0, 0, 282, 160, 'discovers282160', $sourcepath, $sourcepath, 80);
            $discoverInfo[]        = $adiscoverInfo;
        }
        $this->data['discoverInfo'] = $discoverInfo;

        return $this->render('restaurants/best-restaurants.twig', $this->data);
    }

    public function bestRestaurantsRedirectAction($seotitle, $seodescription, $seokeywords)
    {
        return $this->redirectToLangRoute('_best_restaurants_restaurants', array(), 301);
    }
}
