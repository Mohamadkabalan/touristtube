<?php

namespace TTBundle\Services;

use TTBundle\Utils\Utils;
use Doctrine\ORM\EntityManager;

class ThingsToDoServices
{
    protected $em;
    protected $utils;
    
    public function __construct(Utils $utils, EntityManager $em)
    {
        $this->container                 = $utils->container;
        $this->utils                     = $utils;
        $this->em                        = $em;
        $this->DiscoverQueryRQRepository = $this->em->getRepository('TTBundle:CmsThingstodoDetails');
    }

    public function getRelatedThingsToDoList($srch_options)
    {
        return $this->DiscoverQueryRQRepository->getRelatedThingsToDoList($srch_options);
    }

    /**
     * This method calls the DiscoverQueryRQRepository to get hotel division categories.
     *
     * @param integer $ttdId
     * @param integer $categoryId
     * @param integer $divisionId
     * @param boolean $withSubDivisions
     *
     * @return list
     */
    public function getThingstodoDivisions($ttdId = null, $categoryId = null, $divisionId = null, $withSubDivisions = false, $addParentDivisionName = false)
    {
        $thingstodoDivisions = $this->em->getRepository('TTBundle:ThingstodoDivision')->getThingstodoDivisions($ttdId, $categoryId, $divisionId, $withSubDivisions );
//        return $thingstodoDivisions;
        // Prepare divisions data
        $divisionsData = array();
        $main_division_name = '';
        foreach ($thingstodoDivisions as $item) 
        {
            $imglink = '';
            $name = $item['td_name'];
            if( $item['td_parentId'] !='' )
            {
                $imglink = 'thumb.jpg';
            }
            else 
            {
                $main_division_name = $name;
            }
            $divisionsData[] = array(
                "group_id" => '',
                "group_name" => '',
                "category_id" => $item['tdc_id'],
                "category_name" => $item['tdc_name'],
                "id" => $item['td_id'],
                "name" => $name,
                "parent_id" => $item['td_parentId'],
                "parent_name" => $item['tds_name'],
                "image" => $imglink,
                "media_type" => '',
                "is_main_image" => '',
                "settings" => json_decode($item['td_mediaSettings'], true)
            );
        }
        
        if( $addParentDivisionName && $main_division_name!='' )
        {
            $divisionsDataNew = $divisionsData;
            $divisionsData = array();
            foreach ($divisionsDataNew as $item) 
            {
                $name = $item["name"];
                $parent_name = $item["parent_name"];
                if( $item['parent_id'] !='' )
                {
                    $name = $main_division_name.'-'.$name;
                    $parent_name = '';
                }
                $item["name"] = $name;
                $item["parent_name"] = $parent_name;
                $divisionsData[] = $item;
            }
        }
//        return $divisionsData;

        // Convert it to a custom array
        if ($thingstodoDivisions) {
            $divisions = array(
                'type' => 'thingstodo',
                'data' => array(
                    'id' => $thingstodoDivisions[0]['d_id'],
                    'name' => $thingstodoDivisions[0]['d_title'],
                    'main_division_name' => $main_division_name,
                    'logo' => $thingstodoDivisions[0]['d_image'],
                    'country_code' => $thingstodoDivisions[0]['d_country'],
                    'divisions' => $divisionsData
                )
            );

            return $divisions;
        }
        return array();
    }

    /**
     * This method calls the setThingstodoDetailsSlug to set ThingstodoDetails Slug.
     *
     * @param integer $id
     * @param string $title
     *
     * @return boolean
     */
    public function setThingstodoDetailsSlug( $id, $title )
    {
        $title = $this->utils->cleanTitleData( trim($title) );
        $title = trim($title,'-');
        $title1 = trim($title,'+');
        $title = str_replace('+', '-', $title1).'-360';
        $titleSlug = $title;
        $i=0;
        $thingstodo_slug = true;
        do
        {
            if( $i>0 )
            {
                $titleSlug = $title.'-'.$i;
            }
            $thingstodo_slug = $this->em->getRepository('TTBundle:CmsThingstodoDetails')->getThingstodoDetailsSlug( $titleSlug );
			
			if ($thingstodo_slug && $thingstodo_slug[0]['td_id'] == $id)
				$thingstodo_slug = false;
			
            $i++;
        } while( $thingstodo_slug );

        return $this->em->getRepository('TTBundle:CmsThingstodoDetails')->updateThingstodoDetailsSlug( $id, $titleSlug );
    }

    /**
     * This method calls the getThingstodoDetailsSlug to get ThingstodoDetails record.
     *
     * @param string $titleSlug
     *
     * @return array
     */
    public function getThingstodoDetailsSlug( $titleSlug, $language='en' )
    {
        return $this->em->getRepository('TTBundle:CmsThingstodoDetails')->getThingstodoDetailsSlug( $titleSlug, $language );
    }

    /**
     * This method calls the getThingstodoInfo to get Thingstodo record.
     *
     * @param $id
     *
     * @return array
     */
    public function getThingstodoInfo( $id, $language='en' )
    {
        return $this->em->getRepository('TTBundle:CmsThingstodo')->getThingstodoInfo( $id, $language );
    }

    /**
     * This method calls the getThingstodoInfoCountry to get ThingstodoCountry record.
     *
     * @param $id
     *
     * @return array
     */
    public function getThingstodoInfoCountry( $id, $language='en' )
    {
        return $this->em->getRepository('TTBundle:CmsThingstodoCountry')->getThingstodoInfoCountry( $id, $language );
    }

    /**
     * This method calls the getThingstodoInfoRegion to get ThingstodoRegion record.
     *
     * @param $id
     *
     * @return array
     */
    public function getThingstodoInfoRegion( $id, $language='en' )
    {
        return $this->em->getRepository('TTBundle:CmsThingstodoRegion')->getThingstodoInfoRegion( $id, $language );
    }

    /**
     * This method calls the getThingstodoList to get CmsThingstodo list.
     *
     * @param $srch_options
     *
     * @return array
     */
    public function getThingstodoList( $srch_options )
    {
        return $this->em->getRepository('TTBundle:CmsThingstodo')->getThingstodoList( $srch_options );
    }

    /**
     * This method calls the getThingstodoCountryList to get CmsThingstodoCountry list.
     *
     * @param $srch_options
     *
     * @return array
     */
    public function getThingstodoCountryList( $srch_options )
    {
        return $this->em->getRepository('TTBundle:CmsThingstodoCountry')->getThingstodoCountryList( $srch_options );
    }

    /**
     * This method calls the getThingstodoRegionList to get CmsThingstodoRegion list.
     *
     * @param $srch_options
     *
     * @return array
     */
    public function getThingstodoRegionList( $srch_options )
    {
        return $this->em->getRepository('TTBundle:CmsThingstodoRegion')->getThingstodoRegionList( $srch_options );
    }

    /**
     * This method calls the getPoiTopList to get CmsThingstodoDetails list.
     *
     *
     * @return array
     */
    public function getPoiTopList( $country_code='', $state_code='', $city_id=0, $limit=null, $orderby='', $lang= 'en', $isRest = false)
    {
        $pois_array = array();
        $Results = array();
        $todoLink = $todoLinkName = '';
        
        $pois_list = $this->em->getRepository('TTBundle:CmsThingstodoDetails')->getPoiTopList( $country_code, $state_code, $city_id, $limit, $orderby, $lang );
        if ($pois_list) {
            foreach ($pois_list as $item) {
                $varr            = array();
                $titles          = $item['t_title'];
                if ($item['ml_title'])
                {
                    $titles      = $item['ml_title'];
                }
                $varr['title']    = $this->utils->htmlEntityDecode($titles);
                $varr['titlealt'] = $this->utils->cleanTitleDataAlt($titles);

                if ($todoLink == '') {
                    $todoLink     = $this->utils->generateLangURL($lang, $item['a_alias']);
                    $todoLinkName = $item['pt_title'];
                }

                if ($item['t_image']) {
                    $dimagepath  = 'media/thingstodo/';
                    $dimage      = $item['t_image'];
                    $varr['img'] = $this->container->get("TTMediaUtils")->createItemThumbs($dimage, $dimagepath, 0, 0, 284, 162, 'where-is-284162', null, null, null, $isRest);
                } else {
                    $varr['img'] = $this->container->get("TTRouteUtils")->generateMediaURL('/media/images/landmark-icon1.jpg', null, $isRest);
                }
                $descriptions = $item['t_description'];
                if ($item['ml_description']) $descriptions = $item['ml_description'];
                $descriptions = $this->utils->getMultiByteSubstr($descriptions, 250, NULL, $lang);

                $varr['description'] = $this->utils->htmlEntityDecode($descriptions);
                if ($item['td_id'] != '') {
                    $varr['link'] = $this->utils->generateLangURL($lang, '/'.$item['a_alias'].'/'.$item['t_slug']);
                } else {
                    $link                    = '';
                    $topthingstodoList_array = array();
                    if (!isset($topthingstodoList[$item['t_parentId']])) {
                        $topthingstodoList[$item['t_parentId']] = $this->getRelatedThingsToDoList(array(
                            'parent_id' => $item['t_parentId'],
                            'lang' => $lang,
                            'orderby' => 'orderDisplay',
                            'order' => 'd'
                        ));
                    }
                    foreach ($topthingstodoList[$item['t_parentId']] as $item_data) {
                        $topthingstodoList_array[] = $item_data['t_id'];
                    }
                    $itemposition = array_search($item['t_id'], $topthingstodoList_array) + 1;
                    $itempage     = ceil($itemposition / 10);
                    if ($item['a_alias'] != '') {
                        $link = $this->utils->generateLangURL($lang, '/'.$item['a_alias']);
                    }
                    if ($itempage > 1) {
                        $link .= '/'.$itempage;
                    }
                    $varr['link'] = $link.'#'.$itemposition;
                }
                $pois_array[] = $varr;
            }
        }
        $Results['pois_array']   = $pois_array;
        $Results['todoLink']     = $todoLink;
        $Results['todoLinkName'] = $todoLinkName;
        return $Results;
    }
    
    /**
     * This method calls the getThingstodoCityAliasLink to get CmsThingstodo link.
     *
     * @param $city_id
     *
     * @return array
     */
    public function getThingstodoCityAliasLink( $city_id )
    {
        return $this->em->getRepository('TTBundle:CmsThingstodoDetails')->getThingstodoCityAliasLink( $city_id );
    }

    /**
     * This method calls the getAliasLinkCmsThingstodoCountry to get CmsThingstodoCountry link.
     *
     * @param $city_id
     *
     * @return array
     */
    public function getAliasLinkCmsThingstodoCountry( $city_id )
    {
        return $this->em->getRepository('TTBundle:CmsThingstodoCountry')->getAliasLinkCmsThingstodoCountry( $city_id );
    }

    /**
     * This method calls the getThingstodoCountryAliasLink to get CmsThingstodoCountry link.
     *
     * @param $countryCode, $stateCode
     *
     * @return array
     */
    public function getThingstodoCountryAliasLink( $countryCode, $stateCode )
    {
        return $this->em->getRepository('TTBundle:CmsThingstodoCountry')->getThingstodoCountryAliasLink( $countryCode, $stateCode );
    }
}
