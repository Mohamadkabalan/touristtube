<?php

namespace TTBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TTBundle\Entity\CmsVideosTemp;
use TTBundle\Entity\CmsVideos;
use TTBundle\Entity\CmsUsersCatalogs;
use TTBundle\Entity\CmsVideosCatalogs;

class PhotosVideosRepository extends EntityRepository
{

    /*
    * @get mediaFromURLId function return media info video url
    */
    public function mediaFromURLId( $url, $lang = 'en' )
    {
        if (strlen($url) > 0) 
        {
            $qb = $this->createQueryBuilder('v')
            ->select('v,mlv,u')
            ->leftJoin('TTBundle:MlVideos', 'mlv', 'WITH', "mlv.videoId = v.id and mlv.langCode=:Lang")
            ->leftJoin('TTBundle:CmsUsers', 'u', 'WITH', "u.id = v.userid")
            ->where('v.videoUrl=:Id and ( v.published=1 OR v.published=-2 OR v.published=-1)')
            ->setParameter(':Id', $url)
            ->setParameter(':Lang', $lang);

            $query  = $qb->getQuery();
            $result = $query->getScalarResult();

            if (!empty($result) && isset($result[0])) {
                return $result[0];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
    * @get mediaFromURLHashed function return media info from hashed id
    */
    public function mediaFromURLHashed( $url, $lang = 'en' )
    {
        if (strlen($url) > 0)
        {
            $qb = $this->createQueryBuilder('v')
            ->select('v,mlv,u')
            ->leftJoin('TTBundle:MlVideos', 'mlv', 'WITH', "mlv.videoId = v.id and mlv.langCode=:Lang")
            ->leftJoin('TTBundle:CmsUsers', 'u', 'WITH', "u.id = v.userid")
            ->where('v.hashId=:Id and ( v.published=1 OR v.published=-2 OR v.published=-1)')
            ->setParameter(':Id', $url)
            ->setParameter(':Lang', $lang);

            $query  = $qb->getQuery();
            $result = $query->getScalarResult();

            if (!empty($result) && isset($result[0])) {
                return $result[0];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
    * @getMediaInfo function return media Info From id
    */
    public function getMediaInfo( $id, $lang='en' )
    {
        $qb = $this->createQueryBuilder('v')
        ->select('v,mlv,va')
        ->leftJoin('TTBundle:MlVideos', 'mlv', 'WITH', "mlv.videoId = v.id and mlv.langCode=:Language")
        ->leftJoin('TTBundle:CmsVideosCatalogs', 'va', 'WITH', "va.videoId = v.id ")
        ->where("v.id=:Id")
        ->setParameter(':Id', $id)
        ->setParameter(':Language', $lang)
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
    * @albumDelete
    */
    public function albumDelete( $id )
    {        
        $this->albumCatalogsDelete( $id );

        $qb = $this->createQueryBuilder('a')
            ->update('TTBundle:CmsUsersCatalogs a')
            ->set('a.published', ':published')
            ->where('a.id =:Album_id')
            ->setParameter(':published', -2)
            ->setParameter(':Album_id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @photosVideosDelete
    */
    public function photosVideosDelete( $id )
    {
        $this->videosCatalogsDelete( $id );

        $qb = $this->createQueryBuilder('v')
            ->update('TTBundle:CmsVideos v')
            ->set('v.published', ':published')
            ->where('v.id =:Video_id')
            ->setParameter(':published', -2)
            ->setParameter(':Video_id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @photosVideosTempDelete
    */
    public function photosVideosTempDelete( $id, $user_id )
    {
        $qb = $this->createQueryBuilder('vt')
            ->delete('TTBundle:CmsVideosTemp', 'vt')
            ->where('vt.id = :Id AND vt.userId = :User_id')
            ->setParameter(':Id', $id)
            ->setParameter(':User_id', $user_id);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
    * Increent a photo videos number of views
    * @param integer $id the photo videos being viewed
    */
    public function photosVideosIncrementViews( $id )
    {
        $qb = $this->createQueryBuilder('v')
            ->update('TTBundle:CmsVideos v')
            ->set('v.nbViews', 'v.nbViews + 1')
            ->where('v.id =:Video_id')
            ->setParameter(':Video_id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @videosCatalogsDelete
    */
    public function videosCatalogsDelete( $id )
    {
        $qb = $this->createQueryBuilder('vc')
            ->delete('TTBundle:CmsVideosCatalogs', 'vc')
            ->where('vc.videoId = :Video_id')
            ->setParameter(':Video_id', $id);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @videosCatalogsDelete
    */
    public function albumCatalogsDelete( $id )
    {
        $qb = $this->createQueryBuilder('vc')
            ->delete('TTBundle:CmsVideosCatalogs', 'vc')
            ->where('vc.catalogId = :Catalog_id')
            ->setParameter(':Catalog_id', $id);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @getAlbumSearch function return album list
    */
    public function getAlbumSearch( $srch_options, $container )
    {
        $default_opts = array(
            'limit' => 10,
            'page' => 0,
            'is_owner' => 0,
            'show_empty' => 0,
            'user_id' => null,
            'id' => null,
            'date_from' => null,
            'date_to' => null,
            'orderby' => 'id',
            'order' => 'a',
            'n_results' => false,
            'published' => 1,
            'channel_id' => null
        );
        $options      = array_merge($default_opts, $srch_options);

        $where        = '';
        $params       = array();
        $nlimit       = '';
        if ($options['limit'] != null) {
            $nlimit = intval($options['limit']);
            $skip   = intval($options['page']) * $nlimit;
        }
        if ( $options['user_id']!=null && $options['user_id']>0 )
        {
            if ( $where ) $where .= " AND";
            $where .= " a.userId=:User_id ";
            $params['User_id'] = $options['user_id'];
        }

        if ($options['published'] != null)
        {
            if ( $where ) $where .= " AND";
            $where               .= " a.published=:Published ";
            $params['Published'] = $options['published'];
        }

        if ($options['id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where        .= " a.id=:Id ";
            $params['Id'] = $options['id'];
        }

        if ($options['date_from'] != null)
        {
            if ( $where ) $where .= " AND";
            $where               .= " DATE(a.catalogTs) >= :Date_from ";
            $params['Date_from'] = $options['date_from'];
        }

        if ( $options['date_to'] != null )
        {
            if ( $where ) $where .= " AND";
            $where             .= " DATE(catalogTs) <= :Date_to ";
            $params['Date_to'] = $options['date_to'];
        }

        if ( !$options['channel_id'] )
        {
            if ( $where ) $where .= " AND";
            $where .= " a.channelid='0' ";
        }else {
            if ($where) if ( $where ) $where .= " AND";
            $where               .= " a.channelid=:Channelid ";
            $params['Channelid'] = $options['channel_id'];
        }
        
        if ( intval($options['is_owner']) == 0 ) {
            if ($where) $where     .= " AND";
            $inner_q15 = $this->getEntityManager()->createQuery("SELECT VC.id FROM TTBundle:CmsVideosCatalogs VC WHERE VC.catalogId=a.id")
            ->setMaxResults(1)
            ->getDQL();
            $where     .= " EXISTS($inner_q15) ";
        }

        $public  = $container->getParameter('USER_PRIVACY_PUBLIC');
        if ($where) $where   .= ' AND ';
        $where .= " a.isPublic='$public'";

        $orderby = 'a.'.$options['orderby'];
        $order   = ($options['order'] == 'a') ? 'ASC' : 'DESC';

        if (!$options['n_results']) {
            $qb = $this->createQueryBuilder('a');
            $qb->select('a')
                ->where("$where")
                ->setParameters($params);

//            $qb->select('a, v')
//                ->innerJoin('TTBundle:CmsVideosCatalogs', 'va', 'WITH', "va.catalogId = a.id")
//                ->innerJoin('TTBundle:CmsVideos', 'v', 'WITH', "v.id = va.videoId AND v.published=1 AND v.isPublic=2")
//                ->where("$where")
//                ->setParameters($params);

//            $qb->orderBy("va.defaultPic", "DESC");
            $qb->addOrderBy("$orderby", "$order");
            if ($options['limit'] != null) {
                $qb->setMaxResults($nlimit)
                ->setFirstResult($skip);
            }
            $query = $qb->getQuery();
            $results = $query->getScalarResult();

            if ( sizeof($results)>0)
            {
                $albums_id = array();
                foreach($results as $item)
                {
                    $albums_id[] = $item['a_id'];
                }

                $albums_id_str = implode(',', $albums_id);
                $qb = $this->createQueryBuilder('a');
                $qb->select(' a, v.id as v_id, v.name as v_name, v.fullpath as v_fullpath, v.code as v_code, v.relativepath as v_relativepath, v.title as v_title, v.description as v_description, v.category as v_category, v.country as v_country, v.userid as v_userid, v.published as v_published, v.cityid as v_cityid, v.cityname as v_cityname, v.isPublic as v_isPublic, v.imageVideo as v_imageVideo, v.channelid as v_channelid, v.hashId as v_hashId, c.name AS c_name, w.name AS w_name ')
                ->leftJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id=a.cityid')
                ->leftJoin('TTBundle:CmsCountries', 'c', 'WITH', 'c.code=a.country');

                if ( intval($options['show_empty']) == 1 )
                {
                    $qb->leftJoin('TTBundle:CmsVideosCatalogs', 'va', 'WITH', "va.catalogId = a.id")
                    ->leftJoin('TTBundle:CmsVideos', 'v', 'WITH', "v.id = va.videoId AND v.published=1 AND v.isPublic=2")
                    ->where("a.id IN($albums_id_str)");
                    $qb->addOrderBy("$orderby", "$order");
                } else {
                    $qb->innerJoin('TTBundle:CmsVideosCatalogs', 'va', 'WITH', "va.catalogId = a.id")
                    ->innerJoin('TTBundle:CmsVideos', 'v', 'WITH', "v.id = va.videoId AND v.published=1 AND v.isPublic=2")
                    ->where("a.id IN($albums_id_str)");
                    $qb->orderBy("va.defaultPic", "DESC");
                }

                $query = $qb->getQuery();
                $results1 = $query->getScalarResult();

                $albums = array();
                $albums_id = array();

                foreach($results1 as $item)
                {
                    if( !in_array($item['a_id'], $albums_id ) )
                    {
                        $albums[] = $item;
                        $albums_id[] = $item['a_id'];
                    }
                }

                return $albums;
            } else {
                return array();
            }
        } else {
            $qr = "SELECT COUNT(a.id) FROM TTBundle:CmsUsersCatalogs a where$where";
            $qb = $this->getEntityManager()->createQuery($qr);
            $qb->setParameters($params);
            return $qb->getSingleScalarResult();
        }
    }

    /*
    * @albumContentFromURL function return album Info From URL
    */
    public function albumContentFromURL( $srch_options )
    {
        $default_opts = array(
            'limit' => null,
            'page' => 0,
            'id' => null,
            'url' => null,
            'lang' => 'en'
        );
        $options      = array_merge($default_opts, $srch_options);

        $qb = $this->createQueryBuilder('a')
            ->select(' a, v, mlv ')
            ->innerJoin('TTBundle:CmsVideosCatalogs', 'p', 'WITH', "p.catalogId = a.id ")
            ->innerJoin('TTBundle:CmsVideos', 'v', 'WITH', "v.id = p.videoId and v.published=1 and v.isPublic=2")
            ->leftJoin('TTBundle:MlVideos', 'mlv', 'WITH', "mlv.videoId = v.id and mlv.langCode=:Lang")
            ->where('a.published=1');

        if ($options['id'] != null)
        {
            $qb->andWhere('a.id = :ID')
            ->setParameter(':ID', $options['id']);
        }

        if ($options['url'] != null)
        {
            $qb->andWhere('a.albumUrl = :Url')
            ->setParameter(':Url', $options['url']);
        }

        $qb->setParameter(':Lang', $options['lang']);

        $nlimit       = '';
        if ($options['limit'] != null)
        {
            $nlimit = intval($options['limit']);
            $skip   = intval($options['page']) * $nlimit;

            $qb->setMaxResults($nlimit)
            ->setFirstResult($skip);
        }

        $qb->orderBy('p.defaultPic', 'DESC');

        $query  = $qb->getQuery();
        return $query->getScalarResult();
    }

    public function mediaSearch( $srch_options, $container )
    {
        $default_opts = array
        (
            'limit' => 6,
            'page' => 0,
            'skip' => 0,
            'lang' => 'en',
            'is_public' => 2,
            'user_id' => null,
            'type' => null,
            'orderby' => 'id',
            'order' => 'a',
            'catalog_id' => null,
            'city_id' => null,
            'statename' => null,
            'country' => null,
            'n_results' => false,
            'media_id' => null,
            'escape_id' => null,
            'hash_id' => null,
            'date_from' => null,
            'date_to' => null,
            'channel_id' => null,
            'owner_id' => null,
            'catalog_status' => -1,
            'featured' => 0
        );
        $options      = array_merge($default_opts, $srch_options);
        
        $where        = '';        
        $params       = array();

        $nlimit = '';
        if ($options['limit'] != null)
        {
            $nlimit = intval($options['limit']);
            $skip   = intval($options['page']) * $nlimit;
        }

        if ( $where ) $where .= " AND";
        $where .= " v.published=1";
        
        if ($options['city_id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where             .= " v.cityid=:City_id ";
            $params['City_id'] = $options['city_id'];
        }

        if ($options['featured'] != 0)
        {
            if ( $where ) $where .= " AND";
            $where              .= " v.featured=:Featured ";
            $params['Featured'] = $options['featured'];
        }

        if ($options['country'] != null)
        {
            if ( $where ) $where .= " AND";
            $where             .= " v.country=:Country ";
            $params['Country'] = $options['country'];
        }
        
        if ($options['statename'] != null && $options['statename'] && !$options['city_id'])
        {
            if ( $where ) $where .= " AND";
            $where               .= " v.cityname LIKE :Statename ";
            $params['Statename'] = "%".$options['statename'];
        }
        
        if ( $options['owner_id'] != null )
        {
            if ( $where ) $where .= " AND";
            $where .= " v.userid=:Owner_id ";
            $params['Owner_id'] = $options['owner_id'];
        }
        else if ( isset($options['channel_id']) && $options['channel_id']!=null && intval($options['channel_id'])>0 )
        {
            $ch_id       = $options['channel_id'];
            $channelinfo = $container->get('ChannelServices')->channelGetInfo( $ch_id, $options['lang'] );
            if ( $channelinfo ) {
                if ( $where ) $where .= " AND";
                $where              .= " v.userid=:Owner_id ";
                $params['Owner_id'] = $channelinfo['c_ownerId'];
            }
        }

        if ($options['channel_id'] != null)
        {
            if ($options['channel_id'] == -2)
            {
                if ( $where ) $where .= " AND";
                $where .= " v.channelid<>0 ";
            }else if ($options['channel_id'] != -1)
            {
                if ( $where ) $where .= " AND";
                $where                .= " v.channelid=:Channel_id ";
                $params['Channel_id'] = $options['channel_id'];
            }
        }else {
            if ($where) $where .= " AND";
            $where .= " v.channelid=0";
        }
        
        if ($options['type'] != 'a' && $options['type'])
        {
            if ($where) $where .= " AND";
            $where          .= " v.imageVideo=:Type ";
            $params['Type'] = $options['type'];
        }

        if ($options['media_id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where              .= " v.id=:Media_id ";
            $params['Media_id'] = $options['media_id'];
        }

        if ($options['escape_id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where              .= " v.id NOT IN (:Escape_id) ";
            $params['Escape_id'] = $options['escape_id'];
        }

        if ($options['hash_id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where .= " v.hashId=:Hash_id ";
            $params['Hash_id'] = $options['hash_id'];
        }

        if ($options['user_id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where            .= " v.userid=:Userid ";
            $params['Userid'] = $options['user_id'];
        }

        if ($options['catalog_id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where                .= " EXISTS (SELECT alb.catalogId FROM TTBundle:CmsVideosCatalogs alb WHERE alb.videoId=v.id AND alb.catalogId=:Catalog_id)";
            $params['Catalog_id'] = $options['catalog_id'];
        }

        if ($options['date_from'] != null)
        {
            if ( $where ) $where .= " AND";
            $where               .= " DATE(v.pdate) >= :Date_from ";
            $params['Date_from'] = $options['date_from'];
        }

        if ($options['date_to'] != null)
        {
            if ( $where ) $where .= " AND";
            $where             .= " DATE(v.pdate) <= :Date_to ";
            $params['Date_to'] = $options['date_to'];
        }

        if ($options['is_public'] != null)
        {
            if ( $where ) $where .= " AND";
            $where               .= " v.isPublic = :Is_public ";
            $params['Is_public'] = $options['is_public'];
        }

        //-1 we dont care.
        //0 must not be part of a catalog
        //1 must be part of a catalog
        if ($options['catalog_status'] == 0)
        {
            if ( $where ) $where .= " AND";
            $inner_q8 = $this->getEntityManager()->createQuery("SELECT ca.videoId FROM TTBundle:CmsVideosCatalogs ca WHERE ca.videoId=v.id")
            ->setMaxResults(1)
            ->getDQL();
            $where    .= " NOT EXISTS ( $inner_q8 ) ";
        }
        else if ($options['catalog_status'] == 1)
        {
            if ( $where ) $where .= " AND";
            $inner_q8 = $this->getEntityManager()->createQuery("SELECT ca.videoId FROM TTBundle:CmsVideosCatalogs ca WHERE ca.videoId=v.id")
            ->setMaxResults(1)
            ->getDQL();
            $where    .= " EXISTS ( $inner_q8 ) ";
        }

        $orderby = 'v.'.$options['orderby'];
        $order   = ($options['order'] == 'a') ? 'ASC' : 'DESC';


        if (!$options['n_results']) {
            $qb = $this->createQueryBuilder('v');
            $qb->select('v, mlv, u, c.name AS c_name, w.name AS w_name')
                ->leftJoin('TTBundle:MlVideos', 'mlv', 'WITH', "mlv.videoId = v.id and mlv.langCode=:Lang")
                ->leftJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id=v.cityid')
                ->leftJoin('TTBundle:CmsCountries', 'c', 'WITH', 'c.code=v.country')
                ->leftJoin('TTBundle:CmsUsers', 'u', 'WITH', 'u.id=v.userid')
                ->where("$where")
                ->setParameters($params);

            $qb->setParameter(':Lang', $options['lang']);
            $qb->orderBy("$orderby", "$order");
            if ($options['limit'] != null) {
                $qb->setMaxResults($nlimit)
                ->setFirstResult($skip);
            }
            $query = $qb->getQuery();
            return $query->getScalarResult();
        } else {
            $qr = "SELECT COUNT(v.id) FROM TTBundle:CmsVideos v where$where";
            $qb = $this->getEntityManager()->createQuery($qr);
            $qb->setParameters($params);

            return $qb->getSingleScalarResult();
        }
    }

    /*
    * @categoryGetHash function return list of media categories
    */
    public function categoryGetHash( $options = array() )
    {
        $lang_code    = $options['lang'];
        
        $order_by     = 'ac.'.$options['orderby'];
        $order        = ($options['order'] == 'a') ? 'ASC' : 'DESC';
        $where  = "";

        if ($options['id'] != null)
        {
            $where = " AND ac.id = ".$options['id']."";
        }

        if (!empty($options['in']))
        {
            $where = " AND ac.id IN (".$options['in'].")";
        }

        if ($options['showHome'])
        {
            $where .= " AND ac.showHome = 1";
        }

        $qb = $this->createQueryBuilder('ac')
        ->select('ac,mlac,a')
            ->leftJoin('TTBundle:MlAllcategories', 'mlac', 'WITH', 'ac.id = mlac.entityId AND mlac.langCode=:Lang_code')
            ->innerJoin('TTBundle:Alias', 'a', 'WITH', "a.entityId =concat('c/',ac.id)");
        
        $qb->where("ac.published = 1 $where");

        $qb->setParameter(':Lang_code', $lang_code);
        $qb->orderBy($order_by, $order);

        if (!$options['showHome'] && $options['in'] == '')
        {
            $query1 = "SELECT v.id FROM TTBundle:CmsVideos v WHERE v.category=ac.id AND v.published=1";
            $inner_q = $this->getEntityManager()->createQuery($query1)
            ->setMaxResults(1)
            ->getDQL();
            $qb->andwhere("EXISTS($inner_q)");
        }
        
        $query  = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getCityMediaList function return list of media per location
    */
    public function getCityMediaList( $county_code, $state_code, $city_id, $type, $limit )
    {
        $qb = $this->createQueryBuilder('v')
        ->select('v')
        ->addSelect('RAND() as HIDDEN rand');
        
        if (intval($city_id) > 0)
        {
            $qb->where("v.cityid = :city_id and v.published=1 and v.isPublic=2 and v.imageVideo='$type'")
            ->setParameter(':city_id', $city_id);
            
        } 
        else if ($state_code != "")
        {
            $qb->innerJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id=v.cityid')
            ->where("v.country = :countrycode and w.stateCode = :stateCode and v.published=1 and v.isPublic=2 and v.imageVideo='$type'")
            ->setParameter(':countrycode', $county_code)
            ->setParameter(':stateCode', $state_code);
        } 
        else if ($county_code != "")
        {
            $qb->where("v.country = :countrycode and v.published=1 and v.isPublic=2 and v.imageVideo='$type'")
            ->setParameter(':countrycode', $county_code);
        } else {
            $qb->where("v.published=1 and v.isPublic=2 and v.imageVideo='$type'");
        }

        $qb->setMaxResults($limit)
        ->orderBy('rand');
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getVideoFeatured function return list of featured media
    */
    public function getVideoFeatured( $featured, $type='a', $limit )
    {
        $qb    = $this->createQueryBuilder('v')
        ->select('v')
        ->where("v.featured=:Featured")
        ->setParameter(':Featured', $featured);

        if ( $type != 'a' )
        {
            $qb->andWhere('v.imageVideo=:ImageVideo')
            ->setParameter(':ImageVideo', $type);
        }

        $qb->setMaxResults($limit)
        ->orderBy('v.id', 'DESC');
        
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getAlbumInfo function return album Info From id
    */
    public function getAlbumInfo( $id )
    {
        $qb    = $this->createQueryBuilder('a')
        ->select('a')
        ->where('a.id=:Id')
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
    * @mediaGetCatalog function return album info
    */
    public function mediaGetCatalog( $id )
    {
        $qb    = $this->createQueryBuilder('a')
        ->select('a,vl')
        ->innerJoin('TTBundle:CmsVideosCatalogs', 'vl', 'WITH', "vl.catalogId = a.id")
        ->where('vl.videoId=:Id and a.published=1')
        ->setParameter(':Id', $id)
        ->orderBy('a.id', 'DESC');

        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
    * @categoryGetName function return category info
    */
    public function categoryGetName( $id, $lang )
    {
        $qb = $this->createQueryBuilder('ac')
        ->select('ac,mlac')
        ->leftJoin('TTBundle:MlAllcategories', 'mlac', 'WITH', "mlac.entityId = ac.id and mlac.langCode=:Lang")
        ->where('ac.id=:Cat_id and ac.published=1')
        ->setParameter(':Cat_id', $id)
        ->setParameter(':Lang', $lang);
        
        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
    * @getMediaTempInfo
    */
    public function getMediaTempInfo( $id )
    {
        $qb    = $this->createQueryBuilder('vt')
        ->select('vt')
        ->where('vt.id=:Id')
        ->setParameter(':Id', $id);

        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
    * insert a temporary video
    * @param integer $user_id the user id
    * @param string $filename the filename of the media file
    * @param string $vpath the path to the uplaod
    * @param string $thumb the saved thumb
    * @param integer $album_id the album of the uploaded file
    * @param char $image_video 'i' => image, 'v' => video
    * @return boolean true|false if success fail
    */
    public function addVideoTemporary( $user_id, $filename, $vpath, $thumb, $album_id, $image_video, $channel_id = 0)
    {
        $em   = $this->getEntityManager();
        $createTs = new \DateTime("now");

        $item = new CmsVideosTemp();

        $item->setUserId( $user_id );
        $item->setFilename( $filename );
        $item->setUploadTs( $createTs );
        $item->setVpath( $vpath );
        $item->setThumb( $thumb );
        $item->setCatalogId( $album_id );
        $item->setImageVideo( $image_video );
        $item->setChannelid( $channel_id );
        $item->setPendingData('');
        $item->setPrivacyValue('');
        $item->setPrivacyArray('');

        $em->persist($item);
        $em->flush();

        if( $item )
        {
            return $item->getId();
        }
        return false;
    }

    /**
    * insert a media to album
    * @return boolean true|false if success fail
    */
    public function addVideoAlbum( $media_id, $album_id )
    {
        $em   = $this->getEntityManager();
        
        $item = new CmsVideosCatalogs();

        $item->setDefaultPic( 0 );
        $item->setVideoId( $media_id );
        $item->setCatalogId( $album_id );

        $em->persist($item);
        $em->flush();

        if( $item )
        {
            return $item->getId();
        }
        return false;
    }

    /**
    * insert a media
    * @return boolean true|false if success fail
    */
    public function addMedia( $user_id, $code, $filename, $fullpath, $relativepath, $type, $title, $description, $category, $country, $dimension, $duration, $published, $cityid, $cityname, $image_video, $videoUrl, $descriptionLinked, $hashId, $channel_id )
    {
        $em   = $this->getEntityManager();
        $createTs = new \DateTime("now");

        $item = new CmsVideos();

        $item->setUserid( $user_id );
        $item->setCode( $code );
        $item->setName( $filename );
        $item->setLastModified( $createTs );
        $item->setPdate( $createTs );
        $item->setLinkTs( $createTs );
        $item->setFullpath( $fullpath );
        $item->setRelativepath( $relativepath );
        $item->setType( $type );
        $item->setTitle( $title );
        $item->setDescription( $description );
        $item->setCategory( $category );
        $item->setCountry( $country );
        $item->setDimension( $dimension );
        $item->setDuration( $duration );
        $item->setCityid( $cityid );
        $item->setCityname( $cityname );
        $item->setImageVideo( $image_video );
        $item->setVideoUrl( $videoUrl );
        $item->setMediaServers( null );
        $item->setChannelid( $channel_id );
        $item->setDescriptionLinked( $descriptionLinked );
        $item->setHashId( $hashId );
        $item->setPlacetakenat('');
        $item->setKeywords('');
        $item->setAllowedUsers('');
        $item->setIsPublic(2);
        $item->setTripId(null);
        $item->setLocationId(null);
        $item->setLocation(0);
        $item->setNbViews(0);
        $item->setNbComments(0);
        $item->setNbRatings(0);
        $item->setRating(0);
        $item->setNbShares(0);
        $item->setUpVotes(0);
        $item->setDownVotes(0);
        $item->setLikeValue(0);
        $item->setThumbTop(0);
        $item->setThumbLeft(0);
        $item->setFeatured(0);
        $item->setCanComment(1);
        $item->setCanShare(1);
        $item->setCanRate(1);
        $item->setCanLike(1);
        $item->setLattitude(null);
        $item->setLongitude(null);
        $item->setOld( '' );
        $item->setPublished($published);

        $em->persist($item);
        $em->flush();

        if( $item )
        {
            return $item->getId();
        }
        return false;
    }

    /**
    * insert an album
    * @return boolean true|false if success fail
    */
    public function addUserAlbum( $user_id, $album_url, $name, $city_id, $city, $country, $description, $category, $channel_id = 0)
    {
        $em   = $this->getEntityManager();
        $createTs = new \DateTime("now");

        $item = new CmsUsersCatalogs();

        $item->setUserId( $user_id );
        $item->setCatalogName( $name );
        $item->setNMedia(0);
        $item->setPlacetakenat('');
        $item->setCatalogTs( $createTs );
        $item->setUpVotes(0);
        $item->setDownVotes(0);
        $item->setLikeValue(0);
        $item->setNbComments(0);
        $item->setNbViews(0);
        $item->setNbRatings(0);
        $item->setRating(0);
        $item->setNbShares(0);
        $item->setVpath('');
        $item->setLocationId(null);
        $item->setCityid( $city_id );
        $item->setCityname( $city );
        $item->setCountry( $country );
        $item->setDescription( $description );
        $item->setLatitude(null);
        $item->setLongitude(null);
        $item->setIsPublic(2);
        $item->setKeywords('');
        $item->setCategory( $category );
        $item->setChannelid( $channel_id );
        $item->setAlbumUrl( $album_url );
        $item->setCanComment(1);
        $item->setCanShare(1);
        $item->setCanRate(1);
        $item->setCanLike(1);
        $item->setPublished(1);
        $item->setLastModified( $createTs );

        $em->persist($item);
        $em->flush();

        if( $item )
        {
            return $item->getId();
        }
        return false;
    }

    /*
    * @setAlbumUrl
    */
    public function setAlbumUrl( $id, $url )
    {
        $qb = $this->createQueryBuilder('v')
            ->update('TTBundle:CmsUsersCatalogs v')
            ->set('v.albumUrl', ':Album_url')
            ->where('v.id =:Id')
            ->setParameter(':Album_url', $url)
            ->setParameter(':Id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @setMediaUrl
    */
    public function setMediaUrl( $id, $url )
    {
        $qb = $this->createQueryBuilder('v')
            ->update('TTBundle:CmsVideos v')
            ->set('v.videoUrl', ':Video_url')
            ->where('v.id =:Id')
            ->setParameter(':Video_url', $url)
            ->setParameter(':Id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @setMediaHashId
    */
    public function setMediaHashId( $id, $hash_id, $url='' )
    {
        $qb = $this->createQueryBuilder('v')
            ->update('TTBundle:CmsVideos v')
            ->set('v.hashId', ':Hash_id')
            ->where('v.id =:Id');

        if( $url!='' )
        {
            $qb->set('v.videoUrl', ':Video_url');
            $qb->setParameter(':Video_url', $url);
        }
        
        $qb->setParameter(':Hash_id', $hash_id)
            ->setParameter(':Id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @setMediaName
    */
    public function setMediaName( $id, $name )
    {
        $qb = $this->createQueryBuilder('v')
            ->update('TTBundle:CmsVideos v')
            ->set('v.name', ':Name')
            ->where('v.id =:Id')
            ->setParameter(':Name', $name)
            ->setParameter(':Id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @setMediaPublished
    */
    public function setMediaPublished( $id, $published, $relativepath='', $fullpath='' )
    {
        $qb = $this->createQueryBuilder('v')
            ->update('TTBundle:CmsVideos v')
            ->set('v.published', ':Published')
            ->where('v.id =:Id AND v.published = 0');

        if( $relativepath!='' )
        {
            $qb->set('v.relativepath', ':Relativepath');
            $qb->setParameter(':Relativepath', $relativepath);
        }

        if( $fullpath!='' )
        {
            $qb->set('v.fullpath', ':Fullpath');
            $qb->setParameter(':Fullpath', $fullpath);
        }
        
        $qb->setParameter(':Published', $published)
            ->setParameter(':Id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @mediaEdit
    */
    public function mediaEdit( $options )
    {
        $qb = $this->createQueryBuilder('v')
            ->update('TTBundle:CmsVideos v')
            ->where('v.id =:Id AND v.userid =:User_id AND v.channelid =:Channelid');

        if( $options['title']!='' )
        {
            $qb->set('v.title', ':Title');
            $qb->setParameter(':Title', $options['title'] );
        }

        if( $options['category']!=0 )
        {
            $qb->set('v.category', ':Category');
            $qb->setParameter(':Category', $options['category']);
        }

        if( $options['country']!='' )
        {
            $qb->set('v.country', ':Country');
            $qb->setParameter(':Country', $options['country'] );
        }

        if( $options['city']!='' )
        {
            $qb->set('v.cityname', ':City');
            $qb->setParameter(':City', $options['city'] );
        }

        if( $options['city_id']!=0 )
        {
            $qb->set('v.cityid', ':City_id');
            $qb->setParameter(':City_id', $options['city_id']);
        }

        $qb->set('v.description', ':Description');
        $qb->setParameter(':Description', $options['description'] );

        $qb->setParameter(':User_id', $options['user_id'])
            ->setParameter(':Id', $options['id'])
            ->setParameter(':Channelid', $options['channel_id']);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @albumEdit
    */
    public function albumEdit( $options )
    {
        $qb = $this->createQueryBuilder('a')
            ->update('TTBundle:CmsUsersCatalogs a')
            ->where('a.id =:Id AND a.userId =:User_id AND a.channelid =:Channelid');

        if( $options['title']!='' )
        {
            $qb->set('a.catalogName', ':Title');
            $qb->setParameter(':Title', $options['title'] );
        }

        if( $options['category']!=0 )
        {
            $qb->set('a.category', ':Category');
            $qb->setParameter(':Category', $options['category']);
        }

        if( $options['country']!='' )
        {
            $qb->set('a.country', ':Country');
            $qb->setParameter(':Country', $options['country'] );
        }

        if( $options['city']!='' )
        {
            $qb->set('a.cityname', ':City');
            $qb->setParameter(':City', $options['city'] );
        }

        if( $options['city_id']!=0 )
        {
            $qb->set('a.cityid', ':City_id');
            $qb->setParameter(':City_id', $options['city_id']);
        }

        $qb->set('a.description', ':Description');
        $qb->setParameter(':Description', $options['description'] );

        $qb->setParameter(':User_id', $options['user_id'])
            ->setParameter(':Id', $options['id'])
            ->setParameter(':Channelid', $options['channel_id']);

        $query = $qb->getQuery();
        return $query->getResult();
    }
}
