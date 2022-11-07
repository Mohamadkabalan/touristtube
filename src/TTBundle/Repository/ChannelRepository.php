<?php

namespace TTBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TTBundle\Entity\CmsChannel;
use TTBundle\Entity\CmsChannelDetail;
use TTBundle\Entity\CmsChannelLinks;

class ChannelRepository extends EntityRepository
{

    /*
     * This method gets CmsChannel data of a user limit 1
     * @param array @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userDefaultChannelGet($userId)
    {
        $query = $this->createQueryBuilder('cc')
            ->select('cc')
            ->where('cc.ownerId = :ownerId')
            ->setParameter(':ownerId', $userId)
            ->orderBy('cc.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getScalarResult();
        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
    * @getChannelExternalLinks function return channel external links From channel id
    */
    public function getChannelExternalLinks( $in_options )
    {
        $default_options = array(
            'channelid' => 0,
            'is_social' => -1
        );
        $options = array_merge($default_options, $in_options);

        $qb = $this->createQueryBuilder('cl')
            ->select('cl')
            ->where('cl.published=1 AND cl.channelid = :Channelid')
            ->setParameter(':Channelid', $options['channelid']);
            $qb->orderBy("cl.id", "ASC");

        if ( $options['is_social'] != -1) {
            $qb->andwhere('cl.isSocial=:Is_social');
            $qb->setParameter(':Is_social', $options['is_social']);
        }

        $query  = $qb->getQuery();
        return $query->getScalarResult();
    }

    /**
    * edits a channel links
    * @param array $options the new CmsChannelLinks info
    * @return boolean true|false if success or fail
    */
    public function updateChannelExternalLinks( $options )
    {
        $i=0;
        $qb = $this->createQueryBuilder('cl')
            ->update('TTBundle:CmsChannelLinks', 'cl');
        foreach ($options as $key => $val) {
            if ($key != 'id' && $key != 'channelid')
            {
                $qb->set('cl.'.$key, ':Param'.$i)
                ->setParameter(':Param'.$i, $val);
                $i++;
            }
        }
        $qb->where('cl.id = :Id')
        ->setParameter(':Id', $options['id']);

        if ( isset($options['channelid']) )
        {
            $qb->andwhere('cl.channelid = :Channelid')
            ->setParameter(':Channelid', $options['channelid']);
        }

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
     * Adding a new channel links
     *
     * @param $options
     *
     * @return channelLinksId
    */
    public function addChannelExternalLinks( $srch_options )
    {
        $default_opts = array(
            'channelid' => NULL,
            'link' => NULL,
            'isSocial' => 0,
            'published' => 1
        );
        $options = array_merge($default_opts, $srch_options);

        $em          = $this->getEntityManager();

        $channelLinks = new CmsChannelLinks();
        $channelLinks->setChannelid( $options['channelid'] );
        $channelLinks->setLink( $options['link'] );
        $channelLinks->setIsSocial( $options['isSocial'] );
        $channelLinks->setPublished( $options['published'] );
        $em->persist($channelLinks);
        $em->flush();

        if ($channelLinks->getId()) {
            return $channelLinks->getId();
        } else {
            return false;
        }
    }

    /*
    * @getCityChannelCount function return channel count for a location
    */
    public function getCityChannelCount( $county_code='', $state_code='', $city_id=0 )
    {
        if ($city_id > 0) {
            $qr = "SELECT count(h.id) FROM TTBundle:CmsChannel h where h.cityId = :city_id and h.published=1";
        } else if ($state_code != "") {
            $qr = "SELECT count(h.id) FROM TTBundle:CmsChannel h INNER JOIN TTBundle:Webgeocities w WITH w.id=h.cityId where h.country = :countrycode and w.stateCode = :stateCode and h.published=1";
        } else {
            $qr = "SELECT count(h.id) FROM TTBundle:CmsChannel h where h.country = :countrycode and h.published=1";
        }
        $query = $this->getEntityManager()->createQuery($qr);
        if ($city_id > 0) {
            $query->setParameter(':city_id', $city_id);
        } else if ($state_code != "") {
            $query->setParameter(':countrycode', $county_code);
            $query->setParameter(':stateCode', $state_code);
        } else {
            $query->setParameter(':countrycode', $county_code);
        }
        return $query->getSingleScalarResult();
    }

    /*
    * @channelGetInfo function return channel Info From id
    */
    public function channelGetInfo( $id, $lang_code='en' )
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c, u.id AS u_id, u.displayFullname AS u_displayFullname, u.fullname AS u_fullname, u.yourusername AS u_yourusername, ca.title AS ca_title, mlca.title AS mlca_title, co.name AS country_name')
            ->leftJoin('TTBundle:CmsUsers', 'u', 'WITH', 'u.id=c.ownerId')
            ->leftJoin('TTBundle:CmsChannelCategory', 'ca', 'WITH', 'ca.id=c.category')
            ->leftJoin('TTBundle:MlChannelCategory', 'mlca', 'WITH', 'mlca.entityId = ca.id AND mlca.langCode=:Language')
            ->leftJoin('TTBundle:CmsCountries', 'co', 'WITH', 'co.code=c.country')
            ->where('c.published=1 AND c.id =:Id')
            ->setParameter(':Id', $id)
            ->setParameter(':Language', $lang_code)
            ->setMaxResults(1);

        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
    * @channelInfoFromURL function return channel Info From URL
    */
    public function channelInfoFromURL( $channel_url, $lang_code='en' )
    {
        
        $qb = $this->createQueryBuilder('c')
            ->select('c, u.id AS u_id, u.displayFullname AS u_displayFullname, u.fullname AS u_fullname, u.yourusername AS u_yourusername, ca.title AS ca_title, mlca.title AS mlca_title, co.name AS country_name, ( SELECT count(cv.id) from TTBundle:CmsVideos cv where cv.channelid = c.id AND cv.published=1 AND cv.imageVideo=:Photos) as count_photos, ( SELECT count(v.id) from TTBundle:CmsVideos v where v.channelid = c.id AND v.published=1 AND v.imageVideo=:Videos) as count_videos, ( SELECT count(a.id) FROM TTBundle:CmsUsersCatalogs a where a.userId=c.ownerId AND a.published=1 AND a.channelid=c.id AND EXISTS(SELECT VC.id FROM TTBundle:CmsVideosCatalogs VC WHERE VC.catalogId=a.id) AND a.isPublic=2 ) as count_albums')
            ->leftJoin('TTBundle:CmsUsers', 'u', 'WITH', 'u.id=c.ownerId')
            ->leftJoin('TTBundle:CmsChannelCategory', 'ca', 'WITH', 'ca.id=c.category')
            ->leftJoin('TTBundle:MlChannelCategory', 'mlca', 'WITH', 'mlca.entityId = ca.id AND mlca.langCode=:Language')
            ->leftJoin('TTBundle:CmsCountries', 'co', 'WITH', 'co.code=c.country')
            ->where('c.published=1 AND LOWER(c.channelUrl) LIKE LOWER(:Channel_url)')
            ->setParameter(':Channel_url', $channel_url)
            ->setParameter(':Photos', 'i')
            ->setParameter(':Videos', 'v')
            ->setParameter(':Language', $lang_code)
            ->setMaxResults(1);
            $qb->orderBy("c.id", "ASC");
        
        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
    * check the channel owner
    * @param integer $id the channel's id
    * @param integer $user_id the user's id
    * @return array | false the cms_channel record or null if not found
    */
    public function checkChannelOwner( $id, $user_id )
    {
        $qb = $this->createQueryBuilder('c')
        ->select('c')
        ->where('MD5(c.id)=:Id AND c.ownerId =:Userid')
        ->setParameter(':Id', $id)
        ->setParameter(':Userid', $user_id)
        ->setMaxResults(1);

        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
    * @channelCategoryGetID function return channel category ID related to a selected channel category name
    */
    public function channelCategoryGetID($cat_name)
    {
        $cat_name = str_replace("-", " ", $cat_name);
        $cat_name = str_replace("+", " ", $cat_name);

        $qb = $this->createQueryBuilder('cca')
        ->select('cca.id AS id')
        ->leftJoin('TTBundle:MlChannelCategory', 'mlcca', 'WITH', 'mlcca.entityId = cca.id')
        ->where('(cca.title=:Cat_name or mlcca.title=:Cat_name) AND cca.published = 1')
        ->setParameter(':Cat_name', $cat_name)
        ->setMaxResults(1);

        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        return ( (!empty($result) && isset($result[0])) ? $result[0]['id'] : 0);
    }

    /*
    * @channelCategoryInfo function return channel category Info related to a selected channel category id
    */
    public function channelCategoryInfo($id, $lang='en')
    {
        $qb = $this->createQueryBuilder('cca')
        ->select('cca,ml')
        ->leftJoin('TTBundle:MlChannelCategory', 'mlcca', 'WITH', 'mlcca.entityId = cca.id AND mlcca.langCode=:Language')
        ->where('cca.id=:Id AND cca.published = 1')
        ->setParameter(':Id', $id)
        ->setParameter(':Language', $lang)
        ->setMaxResults(1);

        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            if ( $lang == 'en') {
                $result[0]['ml_title'] = '';
            }
            return $result[0];
        } else {
            return false;
        }
    }

    /*
    * @channelcategoryGetHash function return list of channel categories
    */
    public function channelcategoryGetHash($srch_options = array())
    {
        $params       = array();
        $default_opts = array(
            'has_channel' => false,
            'city_id' => NULL,
            'channel_name' => NULL,
            'country' => NULL,
            'state_code' => NULL,
            'lang' => 'en',
            'orderby' => 'title',
            'order' => 'a',
        );
        $options      = array_merge($default_opts, $srch_options);

        $orderby = 'cca.'.$options['orderby'];
        $order   = ($options['order'] == 'a') ? 'ASC' : 'DESC';

        $qb = $this->createQueryBuilder('cca');
        $qb->select('cca, mlcca');
        $qb->leftJoin('TTBundle:MlChannelCategory', 'mlcca', 'WITH', 'mlcca.entityId = cca.id AND mlcca.langCode=:Language');
        $params['Language'] = $options['lang'];

        $qb->where("cca.published = 1");

        if( $options['has_channel'] )
        {
            $where = '';
            if ($options['city_id'] != null) {
                $city_id = intval($options['city_id']);
                $where .= " AND ch.cityId=$city_id";
            }
            if ($options['country'] != null) {
                $country = $options['country'];
                $where .= " AND ch.country='$country'";
            }
            if ($options['channel_name'] != null) {
                $channel_name = strtolower ($options['channel_name']);
                $where .= " AND LOWER(ch.channelName) LIKE '%$channel_name%'";
            }
            $query1 = "SELECT ch.id FROM TTBundle:CmsChannel ch";
            if ($options['state_code'] != null) {
                $state_code = $options['state_code'];
                $query1 .= " INNER JOIN TTBundle:Webgeocities w WITH w.id=ch.cityId AND ch.cityId>0 AND w.stateCode='$state_code'";
            }
            $query1 .= " WHERE ch.category=cca.id AND ch.published=1$where";

            $inner_q = $this->getEntityManager()->createQuery($query1)
            ->setMaxResults(1)
            ->getDQL();
            $qb->andwhere("EXISTS($inner_q)");
        }

        $qb->setParameters($params);
        $qb->orderBy("$orderby", "$order");
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getDiscoverToChannelList function return discover review pages related to a selected channel id
    */
    public function getDiscoverToChannelList( $options )
    {
        $qb = $this->createQueryBuilder('cr')
        ->select('cr, dh.id AS dh_id, dh.hotelname AS dh_hotelname, dh.nbVotes AS dh_nbVotes, dh.rating AS dh_rating, dh.address AS dh_address, dh.location AS dh_location, dh.phone AS dh_phone, dhw.name AS dhw_name, dhw.countryCode AS dhw_countryCode, dhs.stateName AS dhs_stateName, dhc.name AS dhc_name, dhc.iso3 AS dhc_iso3, dp.id AS dp_id, dp.name AS dp_name, dp.address AS dp_address, dp.phone AS dp_phone, dpw.name AS dpw_name, dpw.countryCode AS dpw_countryCode, dps.stateName AS dps_stateName, dpc.name AS dpc_name, dpc.iso3 AS dpc_iso3, da.id AS da_id, da.name AS da_name, da.city AS da_city, da.telephone AS da_telephone, da.airportCode AS da_airportCode, daw.name AS daw_name, daw.countryCode AS daw_countryCode, das.stateName AS das_stateName, dac.name AS dac_name, dac.iso3 AS dac_iso3');

        $qb->leftJoin('TTBundle:DiscoverHotels', 'dh', 'WITH', "cr.entityId = dh.id and cr.entityType=".$options['hotel']."")
        ->leftJoin('TTBundle:Webgeocities', 'dhw', 'WITH', 'dhw.id=dh.cityId AND dh.cityId>0')
        ->leftJoin('TTBundle:States', 'dhs', 'WITH', 'dhs.countryCode=dhw.countryCode AND dhs.stateCode=dhw.stateCode')
        ->leftJoin('TTBundle:CmsCountries', 'dhc', 'WITH', 'dhc.code=dhw.countryCode ');

        $qb->leftJoin('TTBundle:DiscoverPoi', 'dp', 'WITH', "cr.entityId = dp.id and cr.entityType=".$options['landmark']."")
        ->leftJoin('TTBundle:Webgeocities', 'dpw', 'WITH', 'dpw.id=dp.cityId AND dp.cityId>0')
        ->leftJoin('TTBundle:States', 'dps', 'WITH', 'dps.countryCode=dpw.countryCode AND dps.stateCode=dpw.stateCode')
        ->leftJoin('TTBundle:CmsCountries', 'dpc', 'WITH', 'dpc.code=dpw.countryCode ');

        $qb->leftJoin('TTBundle:Airport', 'da', 'WITH', "cr.entityId = da.id and cr.entityType=".$options['airport']."")
        ->leftJoin('TTBundle:Webgeocities', 'daw', 'WITH', 'daw.id=da.cityId AND da.cityId>0')
        ->leftJoin('TTBundle:States', 'das', 'WITH', 'das.countryCode=daw.countryCode AND das.stateCode=daw.stateCode')
        ->leftJoin('TTBundle:CmsCountries', 'dac', 'WITH', 'dac.code=daw.countryCode ');
        
        $qb->where('cr.channelId=:Channel_id AND cr.published=1')
        ->setParameter(':Channel_id', $options['channel_id']);
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /**
    * adds a channel
    * @param string $name the channel's name
    * @param string $url the channel's url
    * @param string $desc the channels description
    * @param string $header the channels description
    * @param string $bg the the background image
    * @param string $default_link a link to the channel website
    * @param string $slogan slogan
    * @param char(2) $country country code
    * @param integer $city webgeocities name
    * @param integer $city_id webgeocities id
    * @param string $street street address
    * @param string $zip_code zipcode
    * @param string $phone phoner number
    * @param string $category pointer to cms_channel_category record
    * @return integer | false the newly inserted cms_channel id or false if not inserted
    */
    public function channelAdd( $user_id, $name='', $url='', $desc='', $header='', $bg='', $default_link='', $slogan='', $country='', $city_id=0, $city='', $street='', $zip_code='', $phone='', $category=0 )
    {
        $url = $this->channelUrlRename($url);

        if( !$url )
        {
            return false;
        }

        $em   = $this->getEntityManager();
        $lastModified = new \DateTime("now");
        $item = new CmsChannel();

        $item->setCreateTs($lastModified);
        $item->setLastModified($lastModified);
        $item->setDeactivatedTs(NULL);
        $item->setLogo('');
        $item->setHeader('');
        $item->setSlogan('');
        $item->setBgcolor('');
        $item->setCoverlink('');
        $item->setCoverId(0);
        $item->setProfileId(0);
        $item->setSloganId(0);
        $item->setInfoId(0);
        $item->setLikeValue(0);
        $item->setUpVotes(0);
        $item->setDownVotes(0);
        $item->setNbShares(0);
        $item->setNbComments(0);
        $item->setNotificationEmail('');
        $item->setOwnerId($user_id);
        $item->setChannelName($name);
        $item->setChannelUrl($url);
        $item->setBg($bg);
        $item->setDefaultLink($default_link);
        $item->setCountry($country);
        $item->setCityId($city_id);
        $item->setCity($city);
        $item->setStreet($street);
        $item->setZipCode($zip_code);
        $item->setPhone($phone);
        $item->setCategory($category);
        $item->setPublished(0);
        $em->persist($item);
        $em->flush();

        if( $item )
        {
            $channelid = $item->getId();
            return $channelid;
        }

        return false;
    }

    /**
    * Check if the channel URL is unique and provide a new, unique one if the one provided is already taken.
    * @param string $url the provided URL.
    * @return the same channel URL if it's unique, a new one if it is not unique.
    */
    public function channelUrlRename($url)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('LOWER(c.channelUrl) AS channel_url')
            ->where('( LOWER(c.channelUrl) LIKE :Url1) OR (LOWER(c.channelUrl) = :Url2 )')
            ->setParameter(':Url1', $url."-%")
            ->setParameter(':Url2', $url)
            ->getQuery();

        $result = $qb->getScalarResult();

        $db_urls_arr = array();
        foreach($result as $row_item){
            array_push($db_urls_arr, $row_item['channel_url']);
        }

        // If the original url is unique, return it.
        if (!in_array($url, $db_urls_arr))
        {
            return $url;
        } else {
           $i = 1;
           while ($i < 1000000) {
               $new_url = $url . '-' . $i;
               if (!in_array($new_url, $db_urls_arr))
                   return $new_url;
               $i++;
           }
       }
       return false;
   }

    /**
    * gets channel detail for a given channel id
    * @param integer $channel_id the channel record
    * @return array the channel detail
    */
    public function getChannelDetailInfo( $channel_id, $id )
    {
        $qb = $this->createQueryBuilder('cd')
            ->select('cd')
            ->where('cd.channelid =:Channel_id) AND cd.id=:Id')
            ->setParameter(':Channel_id', $channel_id)
            ->setParameter(':Id', $id);

        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
     * Adding a new channel detail
     *
     * @param $channel_id
     * @param $detail_text
     * @param $detail_type (CHANNEL_DETAIL_COVER: 1, CHANNEL_DETAIL_PROFILE: 2, CHANNEL_DETAIL_SLOGAN: 3, CHANNEL_DETAIL_INFO: 4)
     *
     * @return channelDetailId
    */
    public function addChannelDetail($channel_id, $detail_text, $detail_type)
    {
        $em          = $this->getEntityManager();

        $channelDetail = new CmsChannelDetail();
        $channelDetail->setChannelid($channel_id);
        $channelDetail->setDetailText($detail_text);
        $channelDetail->setDetailType($detail_type);
        $channelDetail->setCreateTs(new \DateTime("now"));
        $em->persist($channelDetail);
        $em->flush();

        if ($channelDetail->getId()) {
            return $channelDetail->getId();
        } else {
            return false;
        }
    }

    /**
    * edits a channel
    * @param array $options the new CmsChannel info
    * @return boolean true|false if success or fail
    */
    public function channelEdit( $options )
    {
        $i=0;
        $qb = $this->createQueryBuilder('c')
            ->update('TTBundle:CmsChannel', 'c');
        foreach ($options as $key => $val) {
            if ($key != 'id' && $key != 'ownerId')
            {
                if ($key == 'profileId')
                {
                    $profile_id = $this->addChannelDetail($options['id'], $val, 2);
                    $qb->set('c.logo', ':Logo')
                    ->setParameter(':Logo', $val);
                    $val = $profile_id;
                }
                else if ($key == 'coverId')
                {
                    $cover_id = $this->addChannelDetail($options['id'], $val, 1);
                    $qb->set('c.header', ':Header')
                    ->setParameter(':Header', $val);
                    $val = $cover_id;
                }
                else if ($key == 'sloganId')
                {
//                    $slogan_id = $this->addChannelDetail($options['id'], $val, 3);
                    $qb->set('c.slogan', ':Slogan')
                    ->setParameter(':Slogan', $val);
                    $val = 0;
                }
                else if ($key == 'infoId')
                {
//                    $info_id = $this->addChannelDetail($options['id'], $val, 4);
                    $qb->set('c.smallDescription', ':SmallDescription')
                    ->setParameter(':SmallDescription', $val);
                    $val = 0;
                }
                 else if ($key == 'deactivatedTs' && $val == 0)
                {
                    $val = NULL;
                    $qb->set('c.'.$key, NULL);
                    continue;
                }
                $qb->set('c.'.$key, ':Param'.$i)
                ->setParameter(':Param'.$i, $val);
                $i++;
            }
        }
        $qb->where('c.id = :Id')
        ->setParameter(':Id', $options['id']);

        if ( isset($options['ownerId']) )
        {
            $qb->andwhere('c.ownerId = :OwnerId')
            ->setParameter(':OwnerId', $options['ownerId']);
        }
        $query = $qb->getQuery();
        return $query->getResult();
    }


    /*
    * @channelDelete
    */
    public function channelDelete( $id )
    {
        $qb = $this->createQueryBuilder('c')
            ->update('TTBundle:CmsChannel c')
            ->set('c.published', ':published')
            ->where('c.id =:Channel_id')
            ->setParameter(':published', -2)
            ->setParameter(':Channel_id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * Channel Random List.
     * 
     * @return array
     */
    public function getChannelRandomList( $limit=null )
    {
        $qb = $this->createQueryBuilder('c')
        ->select('c');
        $qb->addSelect('RAND() as HIDDEN rand');

        $qb->andwhere("c.published=1");
        $qb->orderBy('rand');

        if( $limit !=null )
        {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        return $query->getScalarResult();
    }
}
