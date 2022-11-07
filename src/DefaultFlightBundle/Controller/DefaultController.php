<?php

namespace defaultFlightBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends  \TTBundle\Controller\DefaultController
{
    public function testFlightAction($seotitle, $seodescription, $seokeywords)
    {

     if( $this->show_flights_block == 0 ) return $this->redirectToLangRoute('_welcome');
        //         echo 'testing 2019...';
           $this->data['isindexpage']    = 1;
        $this->data['pageBannerPano'] = 'flight';
        $this->data['flightPageName'] = 'flight-index';
        $this->setHreflangLinks($this->generateLangRoute('_flight_booking'), true, true);

        $mainEntityType_array          = $this->getMainEntityTypeGlobal( $this->container->getParameter('PAGE_TYPE_FLIGHT'), -1 );
        $this->data['mainEntityArray'] = $this->getMainEntityTypeGlobalData( $mainEntityType_array );
        $this->data['flightblocksearchIndex'] = 1;
        $this->data['hideblocksearchButtons'] = 1;
//        $this->data['pageBannerImage']        = $this->get("TTRouteUtils")->generateMediaURL('/media/images/index/book_flight_homepage_image.jpg');
        $this->data['pageBannerH2']           = $this->translator->trans('Book your Flight');

        if ($this->data['aliasseo'] == '') {
            $this->seoKeywordFiller($seotitle, $seodescription, $seokeywords);
        }
        //
        $this->data['PG_FLIGHT_BOOKING_FORM'] = true;

        //
        return $this->render('defaultFlightBundle:Default:flight-booking.html.twig', $this->data);


}
public function flightsSearchAction($seotitle, $seodescription, $seokeywords)
{
    return new Response(
        '<html><body>flightsearch</body></html>'
    );
}


    public function seoKeywordFiller($seotitle, $seodescription, $seokeywords)
    {

        $this->data['seotitle'] = $this->get('app.utils')->htmlEntityDecodeSEO($seotitle);
        $this->data['seodescription'] = $this->get('app.utils')->htmlEntityDecodeSEO($seodescription);
        $this->data['seokeywords'] = $this->get('app.utils')->htmlEntityDecodeSEO($seokeywords);
    }
}
