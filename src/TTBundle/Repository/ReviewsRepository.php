<?php

namespace TTBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TTBundle\Entity\DiscoverPoiReviews;
use TTBundle\Entity\DiscoverHotelsReviews;
use TTBundle\Entity\AirportReviews;
use TTBundle\Entity\DiscoverHotelsImages;
use TTBundle\Entity\DiscoverPoiImages;
use TTBundle\Entity\DiscoverPoi;
use TTBundle\Entity\AirportImages;
use TTBundle\Entity\CmsHotelReviews;
use TTBundle\Entity\CmsSocialRatings;

class ReviewsRepository extends EntityRepository
{

    public function getUSA()
    {
     $query = $this->getEntityManager()->createQuery("SELECT a FROM TTBundle:Airport a WHERE a.country = 'US'");
      return $query->getResult();
    }
    
    /**
     * add reviews entity for a given user
     * @param integer $user_id the user id
     * @param integer $entity_value (hotel, poi, airport)
     * @param integer $entity_id the entity id
     * @param string $description the reviews description
     * @return integer | false the reviews id or false if not inserted
     */
    public function addReviews( $user_id, $entity_id, $description, $hideUser=0, $entity_value, $table )
    {
        $em   = $this->getEntityManager();
        $createTs = new \DateTime("now");

        if( $entity_value == 'hotel' )
        {
            $item = new DiscoverHotelsReviews();
            $item->setHotelId( $entity_id );
        }
        else if( $entity_value == 'poi' )
        {
            $item = new DiscoverPoiReviews();
            $item->setPoiId( $entity_id );
        }
        else if( $entity_value == 'airport' )
        {
            $item = new AirportReviews();
            $item->setAirportId( $entity_id );
        }
        else
        {
            $item = new CmsHotelReviews();
            $item->setHotelId( $entity_id );
        }
        
        $item->setUserId( $user_id );
        $item->setDescription( $description );
        $item->setCreateTs( $createTs );
        $item->setPublished(1);

        if( $entity_value != 'hotel_hrs' )
        {
            $item->setHideUser( $hideUser );
            $item->setTitle('');
        }
        
        $em->persist($item);
        $em->flush();

        if( $item )
        {
            return $item->getId();
        }
        return false;
    }
    
    /**
     * add reviews entity for a given user
     * @param integer $user_id the user id
     * @param integer $entity_type the entity type
     * @param integer $entity_id the entity id
     * @param string $filename the image name
     * @return integer | false the reviews id or false if not inserted
     */
    public function addReviewPageImage( $user_id, $entity_id, $filename, $entity_value, $table )
    {
        $em   = $this->getEntityManager();
        
        if( $entity_value == 'hotel' )
        {
            $item = new DiscoverHotelsImages();
            $item->setHotelId( $entity_id );
        }
        else if( $entity_value == 'poi' )
        {
            $item = new DiscoverPoiImages();
            $item->setPoi( $em->getReference('\TTBundle\Entity\DiscoverPoi', $entity_id) );
        }
        else
        {
            $item = new AirportImages();
            $item->setAirportId( $entity_id );
        }

        $item->setUserId( $user_id );
        $item->setFilename( $filename );
        $item->setDefaultPic(0);

        $em->persist($item);
        $em->flush();

        if( $item )
        {
            return $item->getId();
        }
        return false;
    }

    /*
    * @getReviewsList
    */
    public function getReviewsList( $entity_id, $entity_value, $table, $limit = 6, $page = 0, $n_results = false)
    {
        $start        = $page * $limit;
        
        if ( !$n_results )
        {
            $qb    = $this->createQueryBuilder('r')
            ->select('r, u.id AS u_id, u.displayFullname AS u_displayFullname, u.fullname AS u_fullname, u.yourusername AS u_yourusername')
            ->leftJoin('TTBundle:CmsUsers', 'u', 'WITH', 'u.id=r.userId')
            ->where("r.$entity_value=:Entity_id AND r.published=1")
            ->setParameter(':Entity_id', $entity_id);

            $qb->setMaxResults($limit)
            ->setFirstResult($start)
            ->orderBy('r.id', 'DESC');

            $query = $qb->getQuery();
            return $query->getScalarResult();
        } 
        else
        {
            $qr    = "SELECT count(r.id) FROM TTBundle:$table r where r.$entity_value = :Entity_id and r.published=1";
            $query = $this->getEntityManager()->createQuery($qr);
            $query->setParameter(':Entity_id', $entity_id);
            return $query->getSingleScalarResult();
        }
    }

    /*
    * @getReviewsInfo
    */
    public function getReviewsInfo( $id )
    {
        $qb    = $this->createQueryBuilder('r')
        ->select('r, u.id AS u_id, u.displayFullname AS u_displayFullname, u.fullname AS u_fullname, u.yourusername AS u_yourusername')
        ->leftJoin('TTBundle:CmsUsers', 'u', 'WITH', 'u.id=r.userId')
        ->where('r.id=:Id AND r.published=1')
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
    * @getDiscoverImages
    */
    public function getDiscoverImages( $entity_id, $entity_value, $user_id = 0, $exclude_user = 0 )
    {
        $qb  = $this->createQueryBuilder('di')
            ->select('di, di.'.$entity_value.' as obj_id')
            ->where("di.".$entity_value."=:Obj_id")
            ->setParameter(':Obj_id', $entity_id);
        
        if ( $user_id != 0 && $exclude_user == 0 )
        {
            $qb->andwhere("di.userId=:User_id");
            $qb->setParameter(':User_id', $user_id);
        } 
        else if ( $user_id != 0 && $exclude_user == 1 )
        {
            $qb->andwhere("di.userId<>:User_id");
            $qb->setParameter(':User_id', $user_id);
        }

        $qb->orderBy('di.id', 'DESC');

        $query  = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getDiscoverImages
    */
    public function getCityDiscoverHotelsCount( $country_code, $state_code, $city_id )
    {
        if (intval($city_id) > 0)
        {
            $qr = "SELECT count(h.id) FROM TTBundle:DiscoverHotels h where h.cityId = :city_id and h.published=1";
        } 
        else if ($state_code != "")
        {
            $qr = "SELECT count(h.id) FROM TTBundle:DiscoverHotels h INNER JOIN TTBundle:Webgeocities w WITH w.id=h.cityId where h.countrycode = :countrycode and w.stateCode = :stateCode and h.published=1";
        }
        else
        {
            $qr = "SELECT count(h.id) FROM TTBundle:DiscoverHotels h where h.countrycode = :countrycode and h.published=1";
        }

        $query = $this->getEntityManager()->createQuery($qr);
        if ( intval($city_id) > 0 )
        {
            $query->setParameter(':city_id', $city_id);
        } 
        else if ( $state_code != "" )
        {
            $query->setParameter(':countrycode', $country_code);
            $query->setParameter(':stateCode', $state_code);
        } 
        else
        {
            $query->setParameter(':countrycode', $country_code);
        }

        return $query->getSingleScalarResult();
    }

    /*
    * @getCityDiscoverPoiCount
    */
    public function getCityDiscoverPoiCount( $country_code, $state_code, $city_id )
    {
        if (intval($city_id) > 0)
        {
            $qr = "SELECT count(h.id) FROM TTBundle:DiscoverPoi h where h.cityId = :city_id and h.published=1";
        }
        else if ($state_code != "")
        {
            $qr = "SELECT count(h.id) FROM TTBundle:DiscoverPoi h INNER JOIN TTBundle:Webgeocities w WITH w.id=h.cityId where h.country = :countrycode and w.stateCode = :stateCode and h.published=1";
        }
        else
        {
            $qr = "SELECT count(h.id) FROM TTBundle:DiscoverPoi h where h.country = :countrycode and h.published=1";
        }

        $query = $this->getEntityManager()->createQuery($qr);
        if ( intval($city_id) > 0 )
        {
            $query->setParameter(':city_id', $city_id);
        }
        else if ( $state_code != "" )
        {
            $query->setParameter(':countrycode', $country_code);
            $query->setParameter(':stateCode', $state_code);
        }
        else
        {
            $query->setParameter(':countrycode', $country_code);
        }

        return $query->getSingleScalarResult();
    }

    /*
    * @getCityDiscoverHotelsList
    */
    public function getCityDiscoverHotelsList( $country_code='', $state_code='', $city_id=0, $limit = 4 )
    {
        $qb = $this->createQueryBuilder('h')
        ->select('h');

        if (intval($city_id) > 0)
        {
            $qb->where("h.cityId = :city_id and h.published=1")
            ->setParameter(':city_id', $city_id);
        } 
        else if ($state_code != "")
        {
            $qb->innerJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id=h.cityId')
            ->where("h.countrycode = :countrycode and w.stateCode = :stateCode and h.published=1")
            ->setParameter(':countrycode', $country_code)
            ->setParameter(':stateCode', $state_code);
        }
        else
        {
            $qb->where("h.countrycode = :countrycode and h.published=1")
            ->setParameter(':countrycode', $country_code);
        }

        $qb->setMaxResults($limit);
        
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getCityDiscoverHotelsListDiscover
    */
    public function getCityDiscoverHotelsListDiscover( $country_code='', $state_code='', $city_id=0, $limit = 4 )
    {
        $em = $this->getEntityManager();

        $inner_q = $em->createQuery("SELECT count(PR.id) FROM TTBundle:DiscoverHotelsImages PR WHERE PR.hotelId=h.id")->setFirstResult(0)->setMaxResults(1)->getDQL();

        $qb = $em->createQueryBuilder('h')
        ->select("h,($inner_q) as image ");

        if (intval($city_id) > 0)
        {
            $qb->where("h.cityId = :city_id and h.published=1")
            ->setParameter(':city_id', $city_id);
        }
        else if ($state_code != "")
        {
            $qb->innerJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id=h.cityId')
            ->where("h.countrycode = :countrycode and w.stateCode = :stateCode and h.published=1")
            ->setParameter(':countrycode', $country_code)
            ->setParameter(':stateCode', $state_code);
        }
        else
        {
            $qb->where("h.countrycode = :countrycode and h.published=1")
            ->setParameter(':countrycode', $country_code);
        }

        $qb->setMaxResults($limit);
        $qb->orderBy('image', 'DESC');
        
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getCityDiscoverPoiListDiscover
    */
    public function getCityDiscoverPoiListDiscover( $country_code='', $state_code='', $city_id=0, $limit = 4 )
    {
        $em      = $this->getEntityManager();
        $inner_q = $em->createQuery("SELECT count(PR.id) FROM TTBundle:DiscoverPoiImages PR WHERE PR.poi=p.id")->setFirstResult(0)->setMaxResults(1)->getDQL();

        $qb = $em->createQueryBuilder('p')
        ->select("p,($inner_q) as image");

        if (intval($city_id) > 0)
        {
            $qb->where("p.cityId = :city_id and p.published=1")
            ->setParameter(':city_id', $city_id);
        } 
        else if ($state_code != "" && $country_code != "")
        {
            $qb->innerJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id=p.cityId')
            ->where("p.country = :countrycode and w.stateCode = :stateCode and p.published=1")
            ->setParameter(':countrycode', $country_code)
            ->setParameter(':stateCode', $state_code);
        } 
        else
        {
            $qb->where("p.country = :countrycode and p.published=1")
            ->setParameter(':countrycode', $country_code);
        }

        $qb->setMaxResults($limit);
        $qb->orderBy('image', 'DESC');
        
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getCityDiscoverPoiList
    */
    public function getCityDiscoverPoiList( $country_code='', $state_code='', $city_id=0, $limit = 4 )
    {
        $qb = $this->createQueryBuilder('p')
        ->select('p');
        if (intval($city_id) > 0)
        {
            $qb->where("p.cityId = :city_id and p.published=1")
            ->setParameter(':city_id', $city_id);
        } 
        else if ($state_code != "" && $country_code != "")
        {
            $qb->innerJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id=p.cityId')
            ->where("p.country = :countrycode and w.stateCode = :stateCode and p.published=1")
            ->setParameter(':countrycode', $country_code)
            ->setParameter(':stateCode', $state_code);
        } 
        else
        {
            $qb->where("p.country = :countrycode and p.published=1")
            ->setParameter(':countrycode', $country_code);
        }
        $qb->setMaxResults($limit);

        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getDiscoverPoiNearByList
    */
    public function getDiscoverPoiNearByList( $limit = null, $orderby = null )
    {
        $qb = $this->createQueryBuilder('p')
        ->select("p");
        $qb->where("p.nearbyIncludes = 'h' and p.published=1");
        
        if ( $limit != null )
        {
            $qb->setMaxResults($limit);
        }

        if( $orderby == 'rand' )
        {
            $qb->addSelect('RAND() as HIDDEN rand');
            $qb->orderBy('rand');
        }

        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getHotelsDefaultPic
    */
    public function getHotelsDefaultPic( $item_id )
    {
        $qb = $this->createQueryBuilder('u')
        ->select('u')
        ->where("u.hotelId = :Item_id and u.filename != ''")
        ->setParameter(':Item_id', $item_id)
        ->orderBy('u.defaultPic', 'DESC')
        ->setMaxResults(1);
        
        $query    = $qb->getQuery();
        $rows_res = $query->getResult();
        if ($rows_res)
        {
            return $rows_res[0];
        } else {
            return '';
        }
    }

    /*
    * @getAirportInfoList
    */
    public function getAirportInfoList( $list_id )
    {
        $qb    = $this->createQueryBuilder('u')
        ->select('u,i')
        ->orderBy('i.defaultPic', 'DESC')
        ->leftJoin('TTBundle:AirportImages', 'i', 'WITH', "i.airportId = u.id and i.filename<>''");
        $qb->add('where', $qb->expr()->in('u.id', ':List_id'))
        ->setParameter(':List_id', explode(',',$list_id));
        $qb->orderBy('i.defaultPic,i.id', 'DESC');
        
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getAirportInfo
    */
    public function getAirportInfo( $item_id )
    {
        $qb    = $this->createQueryBuilder('u')
        ->select('u,i')
        ->orderBy('i.defaultPic', 'DESC')
        ->leftJoin('TTBundle:AirportImages', 'i', 'WITH', "i.airportId = u.id and i.filename<>''")
        ->where("u.id = :Item_id")
        ->setParameter(':Item_id', $item_id)
        ->orderBy('i.defaultPic,i.id', 'DESC')
        ->setMaxResults(1);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @getAirportByCodeInfo
    */
    public function getAirportByCodeInfo( $airport_code )
    {
        $qb    = $this->createQueryBuilder('u')
        ->select('u')
        ->where("u.airportCode = :Airport_code")
        ->setParameter(':Airport_code', $airport_code)
        ->setMaxResults(1);

        $query = $qb->getQuery();
        $row   = $query->getResult();
        if (sizeof($row))
        {
            return $row[0];
        } else {
            return '';
        }
    }

    /*
    * @getPoiDefaultPic
    */
    public function getPoiDefaultPic( $item_id )
    {
        $qb = $this->createQueryBuilder('u')
        ->select('u')
        ->where("u.poi = :Item_id and u.filename != ''")
        ->setParameter(':Item_id', $item_id)
        ->orderBy('u.defaultPic', 'DESC')
        ->setMaxResults(1);

        $query    = $qb->getQuery();
        $rows_res = $query->getResult();
        if ($rows_res)
        {
            return $rows_res[0];
        } else {
            return '';
        }
    }

    /*
    * @getAirportDefaultPic
    */
    public function getAirportDefaultPic( $item_id )
    {
        $qb = $this->createQueryBuilder('u')
        ->select('u')
        ->where("u.airportId = :Item_id and u.filename != ''")
        ->setParameter(':Item_id', $item_id)
        ->orderBy('u.defaultPic', 'DESC')
        ->setMaxResults(1);

        $query    = $qb->getQuery();
        $rows_res = $query->getResult();
        if ($rows_res)
        {
            return $rows_res[0];
        } else {
            return '';
        }
    }

    /*
    * @getHotelInfo
    */
    public function getHotelInfo( $item_id, $SOCIAL_ENTITY_HOTEL )
    {
        $qb    = $this->createQueryBuilder('dh')
        ->select('dh, ttd, dht, dhi')
        ->leftJoin('TTBundle:CmsThingstodoDetails', 'ttd', 'WITH', "ttd.entityId = dh.id and ttd.entityType=".$SOCIAL_ENTITY_HOTEL."")
        ->innerJoin('TTBundle:DiscoverHotelsType', 'dht', 'WITH', "dht.id=dh.propertytype ")
        ->leftJoin('TTBundle:DiscoverHotelsImages', 'dhi', 'WITH', "dhi.hotelId = dh.id and dhi.filename<>''")
        ->where("dh.id = :Item_id")
        ->orderBy('dhi.defaultPic,dhi.id', 'DESC')
        ->setParameter(':Item_id', $item_id)
        ->setMaxResults(1);

        $query  = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @getPoiInfo
    */
    public function getPoiInfo( $item_id, $SOCIAL_ENTITY_LANDMARK )
    {
        $qb    = $this->createQueryBuilder('u')
        ->select('u,v')
        ->leftJoin('TTBundle:CmsThingstodoDetails', 'v', 'WITH', "v.entityId = u.id and v.entityType=".$SOCIAL_ENTITY_LANDMARK."")
        ->where("u.id = :Item_id")
        ->setParameter(':Item_id', $item_id)
        ->setMaxResults(1);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @removeReviews
    */
    public function removeReviews( $user_id, $id, $table )
    {
        $qb = $this->createQueryBuilder('r')
            ->update('TTBundle:'.$table.' r')
            ->set('r.published', ':published')
            ->where('r.id =:Id AND r.userId =:UserId')
            ->setParameter(':published', -2)
            ->setParameter(':UserId', $user_id)
            ->setParameter(':Id', $id);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @removeReviewPageImage
    */
    public function removeReviewPageImage( $user_id, $id, $table )
    {
        $qb = $this->createQueryBuilder('r')
            ->delete('TTBundle:'.$table.' r')
            ->where('r.id =:Id AND r.userId =:UserId')
            ->setParameter(':UserId', $user_id)
            ->setParameter(':Id', $id);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @socialRateRecordGet
    */
    public function socialRateRecordGet( $user_id, $entity_id, $entity_type, $rate_type = 0 )
    {
        $qb = $this->createQueryBuilder('sr')
        ->select('sr')
        ->where("sr.entityId=:Entity_id and sr.entityType=:Entity_type AND sr.rateType=:Rate_type AND sr.userId=:User_id AND sr.published=1")
        ->setParameter(':Entity_id', $entity_id)
        ->setParameter(':Rate_type', $rate_type)
        ->setParameter(':User_id', $user_id)
        ->setParameter(':Entity_type', $entity_type);

        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
    * @socialRateAverage
    */
    public function socialRateAverage( $entity_id, $entity_type, $rate_type = 0 )
    {
        $entity_type_list = implode(',', $entity_type);
        $qb = $this->createQueryBuilder('sr')
        ->select('avg(sr.ratingValue) as rat')
        ->where("sr.entityId=:Entity_id and sr.rateType=:Rate_type and sr.entityType IN($entity_type_list) AND sr.published=1")
        ->setParameter(':Entity_id', $entity_id)
        ->setParameter(':Rate_type', $rate_type);
        $query            = $qb->getQuery();
        $row              = $query->getResult();
        if (!$row[0]['rat']) return 0;

        return ceil( $row[0]['rat'] * 10) / 10;
    }

    /**
     * add rate entity for a given user
     * @param integer $user_id the user id
     * @param integer $entity_type the entity type
     * @param integer $entity_id the entity id
     * @return integer | false the rate id or false if not inserted
     */
    public function addRate( $user_id, $entity_id, $entity_type, $rating, $rate_type = 0 )
    {
        $em   = $this->getEntityManager();
        $createTs = new \DateTime("now");
        $item = new CmsSocialRatings();

        $item->setUserId( $user_id );
        $item->setEntityId( $entity_id );
        $item->setEntityType( $entity_type );
        $item->setRatingValue( $rating );
        $item->setRateType( $rate_type );
        $item->setRatingTs( $createTs );
        $item->setRatingStatus(1);
        $item->setPublished(1);
        $item->setChannelId(0);

        $em->persist($item);
        $em->flush();

        if( $item )
        {
            return $item->getId();
        }
        return false;
    }

    /*
    * @updateRate
    */
    public function updateRate( $user_id, $entity_id, $entity_type, $rating, $rate_type = 0 )
    {
        $qb = $this->createQueryBuilder('sr')
            ->update('TTBundle:CmsSocialRatings sr')
            ->set('sr.ratingValue', ':rating_value')
            ->where('sr.entityId =:entity_id AND sr.userId =:UserId AND sr.entityType =:entity_type AND sr.rateType =:rate_type')
            ->setParameter(':rating_value', $rating)
            ->setParameter(':entity_id', $entity_id)
            ->setParameter(':UserId', $user_id)
            ->setParameter(':entity_type', $entity_type)
            ->setParameter(':rate_type', $rate_type);

        $query = $qb->getQuery();
        return $query->getResult();
    }
}
