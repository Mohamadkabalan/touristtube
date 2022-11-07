<?php

namespace TTBundle\Services;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use TTBundle\vendors\google\GMapsHandler;

class LocationImageServices
{
    private $container;
    private $path;
    private $fileName;

    public function __construct(Utils $utils, EntityManager $em)
    {
        $this->container = $utils->container;
        $this->em        = $em;
        $this->path      = $this->container->getParameter('GMAP_BASE_PATH');
        $this->fileName  = $this->container->getParameter('GMAP_FILENAME');

        $this->gMapsHandler = new GMapsHandler($utils);
    }

    /**
     * This method returns the map location image if it exists.
     * If not, it first calls getGMapLocationImage(), then saveMapLocationImage(), finally returns it.
     *
     * @param Int $type         The entity type constant i.e $this->container->getParameter('SOCIAL_ENTITY_HOTEL')
     * @param $entityObject     The entity object i.e AmadeusHotel
     * @param $zoom
     * @param $size
     *
     * @return
     */
    public function returnMapLocationImage($type, $entityObject, $zoom = 12, $size = '278x204')
    {
        // If DB field is populated (i.e. there is already a downloaded image), return it
        $mapImage = $entityObject->getMapImage();
        if (!empty($mapImage)) {

            if ($this->container->hasParameter('STORAGE_ENGINE') && $this->container->getParameter('STORAGE_ENGINE') == 'aws_s3') {
                $mapImageURL = $this->getMapURL($mapImage);
                return $mapImageURL.'?d='.$size;
            }

            return $this->getMapURL($mapImage);
        }

        // If STORAGE_ENGINE is set, return the file from S3
        if ($this->container->hasParameter('STORAGE_ENGINE') && $this->container->getParameter('STORAGE_ENGINE') == 'aws_s3') {
            // check if image exists on our aws s3 storage
            list($imagePath, $fileName) = $this->getMapURLParts($type, $entityObject);

            $service = $this->container->get('AwsS3Services');

            $file = new \TTBundle\Model\AwsS3File();
            $file->setKey("media/gmap/{$imagePath}/{$fileName}");

            $rsp = $service->getFile($file);
            $rsp = json_decode($rsp, true);

            if (isset($rsp['url'])) {
                $mapImageURL = $this->getMapURL($imagePath.$fileName);
                return $mapImageURL.'?d='.$size;
            }
        }

        // get map image
        $pinImage = $this->getPinImage($type);
        $raw      = $this->gMapsHandler->getMapLocationImage($entityObject->getLatitude(), $entityObject->getLongitude(), $zoom, $size, $pinImage);

        if (!empty($raw)) {
            // save image to disk
            $mapImage = $this->saveMapLocationImage($type, $entityObject, $raw);
        }
        return ($mapImage) ? $mapImage : '';
    }

    /**
     * This method saves the GoogleMaps API map image to disk
     *
     * @param $type id
     * @param $object
     * @param $raw
     *
     * @return
     */
    public function saveMapLocationImage($type, $object, $raw)
    {
        list($imagePath, $fileName) = $this->getMapURLParts($type, $object);

        // If STORAGE_ENGINE is set, save to S3
        if ($this->container->hasParameter('STORAGE_ENGINE') && $this->container->getParameter('STORAGE_ENGINE') == 'aws_s3') {
            $imageBaseDir = 'media/gmap/'.$imagePath;

            $service = $this->container->get('AwsS3Services');
            $rsp     = $service->uploadFileBinary($raw, $imageBaseDir, $fileName, 'image/png', false);

            $rsp = json_decode($rsp, true);
            if (!isset($rsp['error'])) {
                $mapImage = $imagePath.$fileName;

                $object->setMapImage($mapImage);
                $this->em->persist($object);
                $this->em->flush();

                return $this->getMapURL($mapImage);
            }
        } else { // save to disk
            $imageSystemDir = $this->container->getParameter('CONFIG_SERVER_ROOT').$this->path.$imagePath;
            @mkdir($imageSystemDir, 0755, true);

            $fp = fopen($imageSystemDir.$fileName, 'wx');

            if ($fp !== false) {
                fwrite($fp, $raw);
                fclose($fp);

                $mapImage = $imagePath.$fileName;

                $object->setMapImage($mapImage);
                $this->em->persist($object);
                $this->em->flush();

                return $this->getMapURL($mapImage);
            }
        }

        return '';
    }

    /**
     * This method returns the map image path
     *
     * @param Int $type         The entity type constant i.e $this->container->getParameter('SOCIAL_ENTITY_HOTEL')
     * @param $entityObject     The entity object i.e AmadeusHotel
     *
     * @return Array            Array containing the image path and file name
     */
    private function getMapURLParts($type, $entityObject)
    {
        switch ($type) {
            case $this->container->getParameter('SOCIAL_ENTITY_HOTEL'):
                $countryIso3 = strtolower($this->em->getRepository('HotelBundle:CmsHotel')->getHotelIso3Country($entityObject->getId()));
                $imagePath   = "hotels/{$countryIso3}/{$entityObject->getId()}/";
                break;

            default:
                $imagePath = '';
                break;
        }
        return array($imagePath, $this->fileName);
    }

    /**
     * This method returns the map image URL
     *
     * @param String $mapImage      The map image path inside the GMAP base path
     *
     * @return String               The map image URL
     */
    private function getMapURL($mapImage)
    {
        return $this->container->get("TTRouteUtils")->generateMediaURL('/'.$this->path.$mapImage);
    }

    /**
     * This method returns pin image to use on the map
     *
     * @param Int $type     The entity type constant i.e $this->container->getParameter('SOCIAL_ENTITY_HOTEL')
     *
     * @return String       The path to pin image
     */
    private function getPinImage($type)
    {
        switch ($type) {
            case $this->container->getParameter('SOCIAL_ENTITY_HOTEL'):
                return $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_hot.png');
            case $this->container->getParameter('SOCIAL_ENTITY_RESTAURANT'):
                return $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_rest.png');
            case $this->container->getParameter('SOCIAL_ENTITY_LANDMARK'):
                return $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_lmk.png');
            case $this->container->getParameter('SOCIAL_ENTITY_AIRPORT'):
                return $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/pin_empty.png');
            default:
                return '';
        }
    }
}
