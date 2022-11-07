<?php

namespace TTBundle\vendors\google;

use TTBundle\Utils\Utils;

class GMapsHandler
{
    private $container;
    private $utils;

    public function __construct(Utils $utils)
    {
        $this->utils     = $utils;
        $this->container = $this->utils->container;
    }

    /**
     * This method calls the GoogleMaps API and gets an image of the location
     *
     * @param $latitude
     * @param $longitude
     * @param $zoom
     * @param $size
     * @param $pinImage
     *
     * @return
     */
    public function getMapLocationImage($latitude, $longitude, $zoom = 12, $size = '278x204', $pinImage = '/media/images/pin_hot.png')
    {
        list($prefix, $SERVER_NAME_server) = $this->container->get('TTRouteUtils')->UriCurrentServerURL();

        $map_key_index = 0;

        $use_online_key = ($this->container->hasParameter('ENVIRONMENT') && $this->container->getParameter('ENVIRONMENT') == 'production');
        $use_online_key = true; // temporarily use online key for testing

        $MAP_STATIC_KEY = $this->container->getParameter('MAP_STATIC_KEY'.($use_online_key ? '' : '_LOCAL'))[$map_key_index];

        $pin = (($this->container->hasParameter('STORAGE_ENGINE') && $this->container->getParameter('STORAGE_ENGINE') == 'aws_s3')?'':$prefix.$SERVER_NAME_server).$pinImage.'|'.$latitude.','.$longitude; //marker position
        $url = "https://maps.googleapis.com/maps/api/staticmap?key=$MAP_STATIC_KEY&center=$latitude,$longitude&markers=icon:$pin&zoom=$zoom&size=$size";

        if ($use_online_key) {
            /*
              If you have enabled pay-as-you-go billing, the digital signature is required.
              Billable map loads that do not include a digital signature will fail.
             */
            $url_signing_key = ($this->container->hasParameter('MAP_STATIC_URL_SIG_KEY') && $this->container->getParameter('MAP_STATIC_URL_SIG_KEY') ? $this->container->getParameter('MAP_STATIC_URL_SIG_KEY')[$map_key_index]
                    : '');

            if ($url_signing_key) {
                // calling the signing function as provided by http://googlemaps.github.io/url-signing/UrlSigner.php-source
                $url = $this->utils->signUrl($url, $url_signing_key);
            }
        }

        $map_error = false;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $raw = curl_exec($ch);

        if (curl_errno($ch) != CURLE_OK || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
            $map_error = true;
        }

        curl_close($ch);

        if ($map_error) {
            return '';
        } else {
            return $raw;
        }
    }
}
