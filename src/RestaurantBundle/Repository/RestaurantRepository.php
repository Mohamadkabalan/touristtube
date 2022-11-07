<?php

namespace RestaurantBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RestaurantRepository extends EntityRepository
{

    /*
    * @get360FeaturedRestaurants function return list of 360 Featured Restaurants
    */
    public function get360FeaturedRestaurants($srch_options = array())
    {
        $params       = array();
        $default_opts = array(
            'limit' => 10,
            'page' => 0,
            'is_featured' => NULL,
            'city_id' => NULL,
            'country_code' => NULL,
            'hotel_id' => NULL,
            'division_id' => NULL,
            'id' => NULL,
            'published' => 1,
            'lang' => 'en',
            'orderby' => 'sortOrder',
            'order' => 'd',
            'n_results' => false
        );
        $options      = array_merge($default_opts, $srch_options);


        $where        = '';
        $params       = array();
        $nlimit       = '';
        if ($options['limit'] != null) {
            $nlimit = intval($options['limit']);
            $skip   = intval($options['page']) * $nlimit;
        }

        if ($options['published'] != null)
        {
            if ( $where ) $where .= " AND";
            $where .= " r.published=:Published";
            $params['Published'] = $options['published'];
        }

        if ($options['id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where        .= " r.id=:Id";
            $params['Id'] = $options['id'];
        }

        if ($options['hotel_id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where        .= " r.hotelId=:Hotel_id";
            $params['Hotel_id'] = $options['hotel_id'];
        }

        if ($options['city_id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where        .= " r.cityId=:City_id";
            $params['City_id'] = $options['city_id'];
        }

        if ($options['country_code'] != null)
        {
            if ( $where ) $where .= " AND";
            $where        .= " r.countryCode=:Country_code";
            $params['Country_code'] = $options['country_code'];
        }

        if ($options['division_id'] != null)
        {
            if ( $where ) $where .= " AND";
            $where        .= " r.divisionId=:Division_id";
            $params['Division_id'] = $options['division_id'];
        }

        if ($options['is_featured'] != null)
        {
            if ( $where ) $where .= " AND";
            $where        .= " r.isFeatured=:Is_featured";
            $params['Is_featured'] = $options['is_featured'];
        }

        if (!$options['n_results']) {
            $qb = $this->createQueryBuilder('r');
            $qb->select('r, h, hhd, hd, hd2, hdc, hdcg, hi, rw.name AS rw_name, rw.countryCode AS rw_countryCode, rs.stateName AS rs_stateName, rc.name AS rc_name')
                ->innerJoin('HotelBundle:CmsHotel', 'h', 'WITH', 'h.id=r.hotelId')
                ->innerJoin('HotelBundle:HotelToHotelDivisions', 'hhd', 'WITH', 'hhd.hotelId=r.hotelId AND hhd.hotelDivisionId=r.divisionId')
                ->innerJoin('HotelBundle:HotelDivisions', 'hd', 'WITH', 'hd.id=hhd.hotelDivisionId AND hd.parentId IS NULL')
                ->innerJoin('HotelBundle:HotelDivisions', 'hd2', 'WITH', 'hd2.parentId=hd.id')
                ->innerJoin('HotelBundle:CmsHotelImage', 'hi', 'WITH', 'hi.hotelId = r.hotelId AND hi.hotelDivisionId=hd2.id AND hi.mediaTypeId=2')
                ->innerJoin('HotelBundle:HotelDivisionsCategories', 'hdc', 'WITH', 'hdc.id=hd.hotelDivisionCategoryId')
                ->innerJoin('HotelBundle:HotelDivisionsCategoriesGroup', 'hdcg', 'WITH', 'hdcg.id=hdc.hotelDivisionCategoryGroupId AND hdcg.id = 3')
                ->leftJoin('TTBundle:Webgeocities', 'rw', 'WITH', 'rw.id=r.cityId AND r.cityId IS NOT NULL AND r.cityId>0')
                ->leftJoin('TTBundle:States', 'rs', 'WITH', 'rs.countryCode=rw.countryCode AND rs.stateCode=rw.stateCode')
                ->leftJoin('TTBundle:CmsCountries', 'rc', 'WITH', 'rc.code=rw.countryCode ')
                ->where("$where")
                ->setParameters($params);
            
            $qb->addOrderBy("hi.isFeatured", "DESC");

            if ($options['orderby'] == 'rand') {
                $qb->addSelect('RAND() as HIDDEN rand');
                $qb->orderBy('rand');
            }
            else
            {
                $orderby = 'r.'.$options['orderby'];
                $order   = ($options['order'] == 'a') ? 'ASC' : 'DESC';
                $qb->addOrderBy("$orderby", "$order");
            }

            $qb->addOrderBy("hi.sortOrder", "ASC");
            $qb->groupby("r.id");
            if ($options['limit'] != null) {
                $qb->setMaxResults($nlimit)
                ->setFirstResult($skip);
            }
            $query = $qb->getQuery();
            return $query->getScalarResult();
        } else {
            $qr = "SELECT COUNT(r.id) FROM RestaurantBundle:Restaurant r where$where";
            $qb = $this->getEntityManager()->createQuery($qr);
            $qb->setParameters($params);
            return $qb->getSingleScalarResult();
        }
    }

    /**
     * This method calls the getRestaurantInfo to get Restaurant record.
     *
     * @param $id
     *
     * @return array
     */
    public function getRestaurantInfo( $id, $language='en' )
    {
        $qb = $this->createQueryBuilder('r')
        ->select('r, h, hhd, hd, hd2, hdc, hdcg, hi, rw.name AS rw_name, rw.countryCode AS rw_countryCode, rs.stateName AS rs_stateName, rc.name AS rc_name')
            ->innerJoin('HotelBundle:CmsHotel', 'h', 'WITH', 'h.id=r.hotelId')
            ->innerJoin('HotelBundle:HotelToHotelDivisions', 'hhd', 'WITH', 'hhd.hotelId=r.hotelId AND hhd.hotelDivisionId=r.divisionId')
            ->innerJoin('HotelBundle:HotelDivisions', 'hd', 'WITH', 'hd.id=hhd.hotelDivisionId AND hd.parentId IS NULL')
            ->innerJoin('HotelBundle:HotelDivisions', 'hd2', 'WITH', 'hd2.parentId=hd.id')
            ->innerJoin('HotelBundle:CmsHotelImage', 'hi', 'WITH', 'hi.hotelId = r.hotelId AND hi.hotelDivisionId=hd2.id AND hi.mediaTypeId=2')
            ->innerJoin('HotelBundle:HotelDivisionsCategories', 'hdc', 'WITH', 'hdc.id=hd.hotelDivisionCategoryId')
            ->innerJoin('HotelBundle:HotelDivisionsCategoriesGroup', 'hdcg', 'WITH', 'hdcg.id=hdc.hotelDivisionCategoryGroupId AND hdcg.id = 3')
            ->leftJoin('TTBundle:Webgeocities', 'rw', 'WITH', 'rw.id=r.cityId AND r.cityId IS NOT NULL AND r.cityId>0')
            ->leftJoin('TTBundle:States', 'rs', 'WITH', 'rs.countryCode=rw.countryCode AND rs.stateCode=rw.stateCode')
            ->leftJoin('TTBundle:CmsCountries', 'rc', 'WITH', 'rc.code=rw.countryCode ');

        $qb->where("r.id = :Id ")
        ->setParameter(':Id', $id);
        $query = $qb->getQuery();

        $result = $query->getScalarResult();
        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }
}
