<?php

namespace TTBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class CitiesServices
{
    protected $em;
    protected $utils;
    protected $container;
    
    public function __construct(EntityManager $em, Utils $utils, ContainerInterface $container)
    {
        $this->em            = $em;
        $this->utils         = $utils;
        $this->container     = $container;
        $this->translator    = $this->container->get('translator');
    }

    /**
     * This method calls the AjaxController::searchLocality for a certain search term.
     *
     * @param String $term  The search term.
     * @return JSON
     */
    public function getSearchSuggestions($term)
    {
        $path['_controller'] = 'TTBundle:Ajax:searchLocality';
        $query               = array(
            'term' => $term
        );

        $subRequest = $this->container->get('request_stack')->getCurrentRequest()->duplicate($query, null, $path);
        $response   = $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);

        $retArr = array();
        if (!empty($response->getContent())) {
            $response = json_decode($response->getContent(), true);

            if (is_array($response)) {
                $retArr = array_map(function ($item) {
                    return array(
                        'id' => (isset($item['id'])) ? $item['id'] : 0,
                        'name' => (isset($item['name'])) ? $item['name'] : '',
                        'address' => (isset($item['value'])) ? $item['value'] : '',
                        'countryId' => (isset($item['countryCode'])) ? $item['countryId'] : '',
                        'countryCode' => (isset($item['countryCode'])) ? $item['countryCode'] : ''
                    );
                }, $response);
            }
        }

        return $retArr;
    }

    /*
    * @worldcitiespopInfo function return worldcitiespop Info
    */
    public function worldcitiespopInfo( $id )
    {
        return $this->em->getRepository('TTBundle:Webgeocities')->worldcitiespopInfo( $id );
    }

    /*
    * @worldStateInfo function return state Info
    */
    public function worldStateInfo( $country_code, $state_code )
    {
        return $this->em->getRepository('TTBundle:States')->worldStateInfo( $country_code, $state_code );
    }

    /*
    * @worldStateInfo function return state Info
    */
    public function continentGetInfo( $continent_code )
    {
        return $this->em->getRepository('TTBundle:CmsContinents')->continentGetInfo( $continent_code );
    }

    /*
    * @getEntityDescription function return Entity Description data
    */
    public function getEntityDescription( $entity_type, $entity_id )
    {
        return $this->em->getRepository('TTBundle:CmsEntityDescription')->getEntityDescription( $entity_type, $entity_id );
    }

    /*
    * @getCountryInfoData function return country info data
    */
    public function getCountryInfoData( $country_code, $lang )
    {
        $countryInfo        = $this->container->get('CmsCountriesServices')->countryGetInfo( $country_code );
        if (!$countryInfo) return [false,false,false,false,false,false,false,false];
        $where_longitude    = $countryInfo->getLongitude();
        $where_latitude     = $countryInfo->getLatitude();
        $name               = $realname = $this->utils->htmlEntityDecode($countryInfo->getName());
        $realname           = $this->utils->cleanTitleData($realname);
        $medialink          = $this->container->get("TTMediaUtils")->returnSearchMediaLink( $lang, $realname, '', 'a', 1, 0);
        $realname           = str_replace('-', ' ', $realname);
        $realname           = str_replace('+', ' ', $realname);
        $realnameseo        = $realname." ".$countryInfo->getIso3();
        $realnameseoTitle   = $realnameseo;
        $realnameseoTitle   = $this->utils->getMultiByteSubstr($realnameseoTitle, 30, NULL, $lang, false);

        $entity_type = $this->container->getParameter('SOCIAL_ENTITY_COUNTRY');
        $description = $this->getEntityDescription( $entity_type, $countryInfo->getId() );
        if ( $description ) {
            $descriptions = $this->utils->htmlEntityDecode( $description['ced_description'] );
        } else {
            $descriptions = '';
        }

        return [$name,$realname,$realnameseo,$realnameseoTitle,$where_latitude,$where_longitude,$descriptions,$medialink];
    }

    /*
    * @getCityInfoData function return city info data
    */
    public function getCityInfoData( $city_id, $lang )
    {
        $city_info          = $this->container->get('CitiesServices')->worldcitiespopInfo( $city_id );
        if (!$city_info) return [false,false,false,false,false,false,false,false,false,false,false,false];
        $where_longitude    = $city_info[0]->getLongitude();
        $where_latitude     = $city_info[0]->getLatitude();
        $name               = $realname = $this->utils->htmlEntityDecode($city_info[0]->getName());
        $realname           = $this->utils->cleanTitleData($realname);
        $co_id              = $city_info[0]->getCountryCode();
        $s_id               = $city_info[0]->getStateCode();
        $medialink          = $this->container->get("TTMediaUtils")->returnSearchMediaLink( $lang, $realname, '', 'a', 1, 0);
        $realname           = str_replace('-', ' ', $realname);
        $realnameseo        = $realname = str_replace('+', ' ', $realname);
        if (sizeof($city_info) > 2 && $city_info[2]) {
            $stateInfo = $city_info[2];
            $sttname   = $this->utils->htmlEntityDecode($stateInfo->getStateName());
            $sttname   = $this->utils->cleanTitleData($sttname);
            $sttname   = str_replace('+', ' ', $sttname);
            $sttname   = str_replace('-', ' ', $sttname);
            if (strtolower($sttname) != strtolower($realname)) {
                $realnameseo .= " ".$sttname;
            }
        }
        $realnameseoTitle = $realnameseo;
        $realnameseoTitle = $this->utils->getMultiByteSubstr($realnameseoTitle, 26, NULL, $lang, false);

        $realnameseoTitle .= ' '.$city_info[1]->getIso3();
        $realnameseo      .= "-".$city_info[1]->getIso3();
        $parent_name      = $this->utils->htmlEntityDecode($city_info[1]->getName());
        $entity_type      = $this->container->getParameter('SOCIAL_ENTITY_CITY');
        $description      = $this->getEntityDescription( $entity_type, $city_id );
        if ( $description ) {
            $descriptions = $this->utils->htmlEntityDecode( $description['ced_description'] );
        } else {
            $descriptions = '';
        }

        return [$name,$realname,$realnameseo,$realnameseoTitle,$where_latitude,$where_longitude,$descriptions,$medialink,$city_info[0]->getName(),$co_id,$s_id,$parent_name];
    }

    /*
    * @getStatesInfoData function return states info data
    */
    public function getStatesInfoData( $country_code, $state_code, $lang )
    {
        $entity_type = $this->container->getParameter('SOCIAL_ENTITY_STATE');
        $parent_name = '';
        $stateInfo = $this->container->get('CitiesServices')->worldStateInfo( $country_code, $state_code );
        if ($stateInfo && sizeof($stateInfo)) {
            $where_longitude  = $stateInfo[1]->getLongitude();
            $where_latitude   = $stateInfo[1]->getLatitude();
            $name             = $realname = $this->utils->htmlEntityDecode($stateInfo[0]->getStateName());
            $realname         = $this->utils->cleanTitleData($realname);
            $medialink        = $this->container->get("TTMediaUtils")->returnSearchMediaLink( $lang, $realname, '', 'a', 1, 0);
            $realname         = str_replace('-', ' ', $realname);
            $realnameseo      = $realname         = str_replace('+', ' ', $realname);
            $realnameseoTitle = $realnameseo;
            $realnameseoTitle = $this->utils->getMultiByteSubstr($realnameseoTitle, 26, NULL, $lang, false);

            $realnameseoTitle .= ' '.$stateInfo[1]->getIso3();
            $realnameseo      .= "-".$stateInfo[1]->getIso3();
            $parent_name      = $this->utils->htmlEntityDecode($stateInfo[1]->getName());
            $description = $this->getEntityDescription($entity_type, $stateInfo[0]->getId());
        } else {
            $countryInfo      = $this->container->get('CmsCountriesServices')->countryGetInfo( $country_code );
            if (!$countryInfo) return [false,false,false,false,false,false,false,false,false];
            $where_longitude  = $countryInfo->getLongitude();
            $where_latitude   = $countryInfo->getLatitude();
            $name             = $realname         = $this->utils->htmlEntityDecode($countryInfo->getName());
            $realname         = $this->utils->cleanTitleData($realname);
            $medialink          = $this->container->get("TTMediaUtils")->returnSearchMediaLink( $lang, $realname, '', 'a', 1, 0);
            $realname         = str_replace('-', ' ', $realname);
            $realname         = str_replace('+', ' ', $realname);
            $realnameseo      = $realname." ".$countryInfo->getIso3();
            $realnameseoTitle = $realnameseo;
            $realnameseoTitle = $this->utils->getMultiByteSubstr($realnameseoTitle, 30, NULL, $lang, false);
            $description = $this->getEntityDescription( $entity_type, $countryInfo->getId() );
        }

        if ( $description ) {
            $descriptions = $this->utils->htmlEntityDecode( $description['ced_description'] );
        } else {
            $descriptions = '';
        }

        return [$name,$realname,$realnameseo,$realnameseoTitle,$where_latitude,$where_longitude,$descriptions,$medialink,$parent_name];
    }
}
