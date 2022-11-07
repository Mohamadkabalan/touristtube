<?php

namespace TTBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TTBundle\Entity\PageNotFound;
use TTBundle\Entity\Coupon;
use TTBundle\Entity\PassengerTypeQuote;
use Doctrine\ORM\NoResultException;

class TTRepository extends EntityRepository
{

    /*
    * @getLanguagesList function return Languages List
    */
    public function getLanguagesList( $isUserLoggedIn )
    {
        $qb = $this->createQueryBuilder('l')
            ->select('l')
            ->where('l.published=1');
        
        if (!$isUserLoggedIn) {
            $qb->andwhere('l.needsLogin=0');
        }
        $qb->orderBy("l.id", "ASC");
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @cityHomeWhereIs function return cityHomeWhereIs List
    */
    public function cityHomeWhereIs( $lang, $limit = 15 )
    {
        $qb = $this->createQueryBuilder('h')
            ->select('h,vl.name')
            ->leftJoin('TTBundle:MlHomeWhereis', 'vl', 'WITH', "vl.parentId = h.id and vl.langCode=:Lang")
            ->setParameter(':Lang', $lang)
            ->setMaxResults($limit);
        $query     = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @aliasContentInfo function return alias info for selected id
    */
    public function aliasContentInfo( $id )
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->where("a.id=:Id")
            ->setParameter(':Id', $id);
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getAliasInfo function return alias info for selected entityId
    */
    public function getAliasInfo( $entity_id )
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->where("a.entityId=:EntityId")
            ->setParameter(':EntityId', $entity_id);
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @getAliasSeo function return alias seo info for selected url
    */
    public function getAliasSeo($url, $lang)
    {
        $urlClean = str_replace(' ', '+', $url);
        
        $qb = $this->createQueryBuilder('t')
            ->select('t,ml');
        $qb->leftJoin('TTBundle:MlAliasSeo', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language');
        $qb->setParameter(':Language', $lang);
        $qb->where('t.url=:Url OR t.url=:UrlClean')
            ->setParameter(':Url', $url)
            ->setParameter(':UrlClean', $urlClean)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(1);
        $query    = $qb->getQuery();
        $res      = $query->getScalarResult();
        $ret      = count($res);
        if ($res && $ret != 0) {
            if ( $lang == 'en') {
                $res[0]['ml_title']       = '';
                $res[0]['ml_description'] = '';
                $res[0]['ml_keywords']    = '';
            }
            return $res[0];
        } else {
            return '';
        }
    }

    /*
    * @addPageNotFound function add PageNotFound url
    */
    public function addPageNotFound($url)
    {
        $em          = $this->getEntityManager();
        $query = $em->createQuery("SELECT pnf FROM TTBundle:PageNotFound pnf WHERE pnf.url = :url");
        $query->setParameter(':url', $url);

        try {
            $pnf = $query->getSingleResult();

            $pnf->justSeen();
        } catch (NoResultException $e) {
            $pnf = new PageNotFound;
            $pnf->setUrl($url);
        }

        $em->persist($pnf);
        $em->flush();

        return $pnf->getN(); // returns the number of times this URL was seen.
    }

    /*
    * @getMainEntityTypeGlobal function return MainEntityType list
    */
    public function getMainEntityTypeGlobal( $lang, $home_type = -1, $show_on_home = 1, $SOCIAL_ENTITY_THINGSTODO_CITY, $SOCIAL_ENTITY_THINGSTODO_DETAILS, $SOCIAL_ENTITY_HOTEL, $SOCIAL_ENTITY_HOTEL_SELECTED_CITY, $SOCIAL_ENTITY_RESTAURANT, $SOCIAL_ENTITY_AIRPORT, $SOCIAL_ENTITY_FLIGHT, $SOCIAL_ENTITY_DEAL, $SOCIAL_ENTITY_DEAL_ATTRACTIONS )
    {
        $qb = $this->createQueryBuilder('m')
            ->select("m, e, l, mlm.name AS mlm_name, mll.name AS mll_name, t.title AS t_title, t.image AS t_image, mlt.title AS mlt_title, tw.name AS tw_city, a.alias AS a_alias, h.name AS h_name, hw.name AS h_city, hw.id AS h_cityId, hi.hotelId, hi.filename as imageSource, hi.location as imageLocation, hs.trustyouId AS hs_trustyouId, hw.name AS hw_city, r.id AS r_id, r.hotelId AS r_hotelId, r.name AS r_name, rh.name AS rh_name, dc.cityName AS dc_cityName, dc.image AS dc_image, dc.cityId AS dc_cityId, dd.dealName AS dd_dealName, ddc.cityName AS ddc_cityName, ddt.category AS dealType, mld.dealName AS mld_dealName, hc.name AS hc_name, hc.image AS hc_image, hc.cityId AS hc_cityId, hc.locationId AS hc_locationId, dta.name AS dta_name, dta.imageUrl AS dta_imageUrl, dtaw.name AS dtaw_city, ai.name AS ai_name, ai.airportCode AS ai_airportCode, ai.image AS ai_image, td.title AS td_title, td.slug AS td_slug, tdd.id AS tdd_id, td.image AS td_image, td.image360 AS td_image360, td.parentId AS td_parentId,mltd.title AS mltd_title, tdta.alias AS tdta_alias, tdw.name AS tdw_city");

        $qb->leftJoin('TTBundle:MlMainEntityType', 'mlm', 'WITH', 'mlm.parentId = m.id AND mlm.language=:Language')
            ->innerJoin('TTBundle:EntityType', 'e', 'WITH', 'e.id=m.entityTypeId AND e.published=1')
            ->leftJoin('TTBundle:MainEntityTypeList', 'l', 'WITH', 'l.mainEntityTypeId = m.id AND l.published=1')
            ->leftJoin('TTBundle:MlMainEntityTypeList', 'mll', 'WITH', 'mll.parentId = l.id AND mll.language=:Language');

        
        $qb->leftJoin('TTBundle:CmsThingstodo', 't', 'WITH', '(t.id = l.entityId AND l.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_CITY.') OR (t.id = m.entityId AND m.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_CITY.')')
            ->leftJoin('TTBundle:MlThingstodo', 'mlt', 'WITH', 'mlt.parentId = t.id AND mlt.language=:Language AND (l.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_CITY.' OR m.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_CITY.')');
        $qb->leftJoin('TTBundle:Webgeocities', 'tw', 'WITH', 'tw.id = t.cityId AND t.cityId != 0 AND t.cityId IS NOT NULL AND (l.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_CITY.' OR m.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_CITY.')');
        $qb->leftJoin('TTBundle:Alias', 'a', 'WITH', 'a.id = t.aliasId AND (l.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_CITY.' OR m.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_CITY.')');


        $qb->leftJoin('TTBundle:CmsThingstodoDetails', 'td', 'WITH', 'td.id = l.entityId AND l.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_DETAILS.'');
        $qb->leftJoin('TTBundle:ThingstodoDivision', 'tdd', 'WITH', 'tdd.ttdId = td.id AND tdd.parentId IS NULL AND l.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_DETAILS.'');
        $qb->leftJoin('TTBundle:MlThingstodoDetails', 'mltd', 'WITH', 'mltd.parentId = td.id AND mltd.language=:Language AND l.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_DETAILS.'');
        $qb->leftJoin('TTBundle:CmsThingstodo', 'tdt', 'WITH', 'tdt.id = td.parentId AND l.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_DETAILS.'');
        $qb->leftJoin('TTBundle:Alias', 'tdta', 'WITH', 'tdta.id = tdt.aliasId AND l.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_DETAILS.'');
        $qb->leftJoin('TTBundle:Webgeocities', 'tdw', 'WITH', 'tdw.id = tdt.cityId AND tdt.cityId != 0 AND tdt.cityId IS NOT NULL AND l.entityTypeId='.$SOCIAL_ENTITY_THINGSTODO_DETAILS.'');

        $qb->leftJoin('HotelBundle:CmsHotel', 'h', 'WITH', 'h.id = l.entityId AND l.entityTypeId='.$SOCIAL_ENTITY_HOTEL.'');
        $qb->leftJoin('HotelBundle:CmsHotelSource', 'hs', 'WITH', 'hs.hotelId = h.id AND l.entityTypeId='.$SOCIAL_ENTITY_HOTEL.'');
        $qb->leftJoin('HotelBundle:CmsHotelCity', 'hsc', 'WITH', 'hsc.locationId = hs.locationId');
        $qb->leftJoin('HotelBundle:CmsHotelImage', 'hi', 'WITH', 'hi.hotelId = h.id AND hi.mediaTypeId = 1 AND hi.defaultPic = 1');
        $qb->leftJoin('TTBundle:Webgeocities', 'hw', 'WITH', 'hw.id = hsc.cityId AND hsc.cityId != 0 AND hsc.cityId IS NOT NULL');

        $qb->leftJoin('HotelBundle:HotelSelectedCity', 'hc', 'WITH', 'hc.id = l.entityId AND l.entityTypeId='.$SOCIAL_ENTITY_HOTEL_SELECTED_CITY.'');

        $qb->leftJoin('RestaurantBundle:Restaurant', 'r', 'WITH', 'r.id = l.entityId AND l.entityTypeId='.$SOCIAL_ENTITY_RESTAURANT.'')
            ->leftJoin('HotelBundle:CmsHotel', 'rh', 'WITH', 'rh.id=r.hotelId');

        $qb->leftJoin('DealBundle:DealCity', 'dc', 'WITH', 'dc.id = l.entityId AND l.entityTypeId='.$SOCIAL_ENTITY_DEAL.'');
        $qb->leftJoin('DealBundle:DealDetails', 'dd', 'WITH', 'dd.id = l.entityId AND l.entityTypeId='.$SOCIAL_ENTITY_DEAL.'');
        $qb->leftJoin('DealBundle:DealCity', 'ddc', 'WITH', 'ddc.id = dd.dealCityId AND l.entityTypeId='.$SOCIAL_ENTITY_DEAL.'');
        $qb->leftJoin('DealBundle:DealType', 'ddt', 'WITH', 'ddt.id = dd.dealTypeId AND l.entityTypeId='.$SOCIAL_ENTITY_DEAL.'');
        $qb->leftJoin('TTBundle:MlDealTexts', 'mld', 'WITH', 'mld.dealCode = dd.id AND mld.langCode=:Language AND l.entityTypeId='.$SOCIAL_ENTITY_DEAL.'');


        $qb->leftJoin('DealBundle:DealTopAttractions', 'dta', 'WITH', 'dta.id = l.entityId AND l.entityTypeId='.$SOCIAL_ENTITY_DEAL_ATTRACTIONS.'');
        $qb->leftJoin('TTBundle:Webgeocities', 'dtaw', 'WITH', 'dtaw.id = dta.cityId AND l.entityTypeId='.$SOCIAL_ENTITY_DEAL_ATTRACTIONS.'');


        $qb->leftJoin('TTBundle:Airport', 'ai', 'WITH', '(ai.id = l.entityId AND l.entityTypeId='.$SOCIAL_ENTITY_AIRPORT.') OR (ai.id = m.entityId AND m.entityTypeId='.$SOCIAL_ENTITY_FLIGHT.')');


        $qb->where("m.published=1");

        if ($show_on_home >= 0) {
            $qb->andwhere("l.showOnHome = :ShowOnHome")
                ->setParameter(':ShowOnHome', $show_on_home);
        }

        if ($home_type >= 0) {
            $qb->andwhere("m.showOnHome = :Home_type")
                ->setParameter(':Home_type', $home_type);
        }

        $qb->addOrderBy('m.displayOrder', 'DESC')
            ->addOrderBy('l.displayOrder', 'DESC');

        $qb->setParameter(':Language', $lang);
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @activeCampaigns function return active Campaigns
    */
    public function activeCampaigns($campaignSpecs, $detailedInfo = true)
    {
        if (!$campaignSpecs || !is_array($campaignSpecs)) $campaignSpecs = array();

        $queryBuilder = $this->createQueryBuilder('c')
            ->select('c, s_et.name AS source_entity_type, t_et.name AS target_entity_type, '.($detailedInfo ? 'd, dt, ' : '').'cu.code AS currency_code, s_cs.helperText AS target_helper_text')
            ->innerJoin('TTBundle:EntityType', 's_et', 'WITH', 's_et.id = c.sourceEntityTypeId')
            ->innerJoin('TTBundle:EntityType', 't_et', 'WITH', 't_et.id = c.targetEntityTypeId')
            ->innerJoin('TTBundle:CouponSource', 's_cs', 'WITH', 's_cs.entityTypeId = c.sourceEntityTypeId');

        if ($detailedInfo) {
            $queryBuilder->innerJoin('TTBundle:Discount', 'd', 'WITH', 'd.id = c.discountId');
            $queryBuilder->innerJoin('TTBundle:DiscountType', 'dt', 'WITH', 'dt.id = d.discountTypeId');
        }

        $queryBuilder->leftJoin('TTBundle:Currency', 'cu', 'WITH', 'cu.id = c.currencyId');
        $queryBuilder->where('c.isActive = :active');

        if (isset($campaignSpecs['source_entity_type_id'])) {
            $queryBuilder = $queryBuilder->andwhere('c.sourceEntityTypeId = :sourceEntityTypeId');
        }

        if (isset($campaignSpecs['target_entity_type_id'])) {
            $queryBuilder = $queryBuilder->andwhere('c.targetEntityTypeId = :targetEntityTypeId');
        }

        $queryBuilder = $queryBuilder->andwhere('((c.endDate IS NOT NULL AND CURRENT_DATE() BETWEEN DATE(c.startDate) AND DATE(c.endDate)) OR (c.endDate IS NULL AND CURRENT_DATE() >= DATE(c.startDate)))')
            ->setParameter(':active', true);

        if (isset($campaignSpecs['source_entity_type_id'])) {
            $queryBuilder->setParameter(':sourceEntityTypeId', $campaignSpecs['source_entity_type_id']);
        }

        if (isset($campaignSpecs['target_entity_type_id'])) {
            $queryBuilder->setParameter(':targetEntityTypeId', $campaignSpecs['target_entity_type_id']);
        }

        $query = $queryBuilder->getQuery();
        $rows  = $query->getScalarResult();

        if ($rows) return $rows;
        else return array();
    }

    /*
    * @activeCampaignMarketingLabels function return active Campaign Marketing Labels
    */
    public function activeCampaignMarketingLabels($campaignSpecs)
    {
        if (!$campaignSpecs || !is_array($campaignSpecs)) return array();

        if (!isset($campaignSpecs['source_entity_type_id']) && !isset($campaignSpecs['target_entity_type_id'])) return array();

        $queryBuilder = $this->createQueryBuilder('c')
            ->select('c.marketingLabel')
            ->where('c.isActive = :active');

        if (isset($campaignSpecs['source_entity_type_id'])) {
            $queryBuilder = $queryBuilder->andwhere('c.sourceEntityTypeId = :sourceEntityTypeId');
        }

        if (isset($campaignSpecs['target_entity_type_id'])) {
            $queryBuilder = $queryBuilder->andwhere('c.targetEntityTypeId = :targetEntityTypeId');
        }

        $queryBuilder = $queryBuilder->andwhere('((c.endDate IS NOT NULL AND CURRENT_DATE() BETWEEN DATE(c.startDate) AND DATE(c.endDate)) OR (c.endDate IS NULL AND CURRENT_DATE() >= DATE(c.startDate)))')
            ->setParameter(':active', true);

        if (isset($campaignSpecs['source_entity_type_id'])) {
            $queryBuilder->setParameter(':sourceEntityTypeId', $campaignSpecs['source_entity_type_id']);
        }

        if (isset($campaignSpecs['target_entity_type_id'])) {
            $queryBuilder->setParameter(':targetEntityTypeId', $campaignSpecs['target_entity_type_id']);
        }

        $query = $queryBuilder->getQuery();
        $rows  = $query->getScalarResult();

        if ($rows) return $rows;
        else return array();
    }

    /*
    * @isCouponUsed function check if coupon is used
    */
    public function isCouponUsed($campaign_id, $coupon_code)
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.campaignId = :campaign_id')
            ->andwhere('c.couponCode = :coupon_code')
            ->setParameter(':campaign_id', $campaign_id)
            ->setParameter(':coupon_code', $coupon_code);

        $query = $queryBuilder->getQuery();
        $used  = $query->getSingleScalarResult();

        return ($used == 1);
    }

    /*
    * @getCouponSource function coupon source info
    */
    public function getCouponSource($entity_type)
    {
        $queryBuilder = $this->createQueryBuilder('cs')
            ->select('cs, et')
            ->innerJoin('TTBundle:EntityType', 'et', 'WITH', 'et.id = cs.entityTypeId')
            ->where('cs.entityTypeId = :entityType')
            ->setParameter(':entityType', $entity_type);

        $query = $queryBuilder->getQuery();
        $rows  = $query->getScalarResult();

        if ($rows) {
            $couponSource                   = $rows[0];
            $couponSource['cs_sourceSpecs'] = json_decode($couponSource['cs_sourceSpecs'], true);

            return $couponSource;
        } else return array();
    }

    /*
    * @getDiscountDetails function return Discount Details info
    */
    public function getDiscountDetails($discount_id)
    {

        if (!$discount_id) return array();

        $queryBuilder = $this->createQueryBuilder('d')
            ->select('d, dt')
            ->innerJoin('TTBundle:DiscountType', 'dt', 'WITH', 'dt.id = d.discountTypeId')
            ->where('d.id = :discountId');

        $queryBuilder->setParameter(':discountId', $discount_id);

        $query = $queryBuilder->getQuery();
        $rows  = $query->getScalarResult();

        if ($rows) return $rows[0];
        else return array();
    }

    /*
    * @addNewCoupon function add New Coupon
    */
    public function addNewCoupon($campaign_id, $coupon_code, $entity_id, $entity_type_id)
    {
        $em = $this->getEntityManager();

        $coupon = new Coupon();
        $coupon->setCampaignId($campaign_id);
        $coupon->setCouponCode($coupon_code);
        $coupon->setEntityId($entity_id);
        $coupon->setEntityTypeId($entity_type_id);
        $coupon->setCreationDate(new \DateTime("now"));

        $em->persist($coupon);
        $em->flush();

        if ($coupon) {
            return $coupon->getId();
        } else {
            return false;
        }
    }

    /*
     * Adding a new Passenger Type Quote
     *
     * @param $options
     *
     * @return passengerTypeQuoteId
    */
    public function addPassengerTypeQuote( $options )
    {
        $em          = $this->getEntityManager();
        $item = new PassengerTypeQuote();
        $item->setModuleId( $options['module_id'] );
        $item->setModuleTransactionId( $options['module_transaction_id'] );
        $item->setPassengerType( $options['passenger_type'] );
		
		$priceQuote = $options['price_quote'];
		
		if (is_array($priceQuote))
			$priceQuote = json_encode($priceQuote);
		
        $item->setPriceQuote( $priceQuote );
        $em->persist($item);
        $em->flush();

        if ($item->getId()) {
            return $item->getId();
        } else {
            return false;
        }
    }
}
