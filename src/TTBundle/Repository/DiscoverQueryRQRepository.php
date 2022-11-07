<?php

namespace TTBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class DiscoverQueryRQRepository extends EntityRepository
{

    public function findAll()
    {
        return $this->createQueryBuilder('e')
                ->select('e')
                ->getQuery()
                ->getArrayResult();
    }

    public function thingstodoRegionEN()
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.published = 1')
            ->orderBy('p.id', 'ASC')
            ->getQuery();

        $products = $query->getArrayResult();
        return $products;
    }

    public function thingstodoRegionNotEN($Lang)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t,ml.title,ml.description')
            ->innerJoin('TTBundle:MlThingstodoRegion', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language')
            ->where("t.published=1")
            ->setParameter(':Language', $Lang)
            ->getQuery();


        $products = $query->getScalarResult();
        return $products;
    }

    public function thingstodoCountryEN($region_id, $Lang, $skip, $limit)
    {
        $query    = $this->createQueryBuilder('c')
            ->where('c.published = 1')
            ->andWhere('c.parentId = :Region_id')
            ->setFirstResult($skip)
            ->setMaxResults($limit)
            ->setParameter(':Region_id', $region_id)
            ->orderBy('c.orderDisplay')
            ->getQuery();
// ->setParameters(['skip'=> $skip,'limit', $limit,'Region_id', $region_id])
        $products = $query->getArrayResult();
        return $products;
    }

    public function thingstodoCountryNotEN($region_id, $Lang, $skip, $limit)
    {
        $query    = $this->createQueryBuilder('t')
            ->select('t,ml.title,ml.description')
            ->innerJoin('TTBundle:MlThingstodoCountry', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language')
            ->where('t.published = 1')
            ->andWhere('t.parentId = :Region_id')
            ->setFirstResult($skip)
            ->setMaxResults($limit)
            ->setParameter(':Region_id', $region_id)
            ->setParameter(':Language', $Lang)
            ->orderBy('t.orderDisplay')
            ->getQuery();
// ->setParameters(['skip'=> $skip,'limit', $limit,'Region_id', $region_id])
        $products = $query->getScalarResult();
        return $products;
    }

    public function thingstodoSearchNotEN($where, $parent_id, $skip, $nlimit, $options)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t,ml.title,ml.description')
            ->innerJoin('TTBundle:MlThingstodoDetails', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language')
            ->where($where)
            ->setFirstResult($skip)
            ->setMaxResults($nlimit)
            ->orderBy('t.orderDisplay')
            ->setParameter(':Language', $options['lang'])
            ->getQuery();

        $products = $query->getScalarResult();
        return $products;
    }

    public function thingstodoSearchEN($where, $parent_id, $skip, $nlimit, $options)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t')
            ->where($where)
            ->setFirstResult($skip)
            ->setMaxResults($nlimit)
            ->getQuery();

        $products = $query->getArrayResult();
        return $products;
    }

    public function thingstodoDetailsBG($parent_id)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t.image')
            ->where('t.parentId = :parentID')
            ->andWhere('t.published = 1')
            ->setMaxResults('1')
            ->setParameter(':parentID', $parent_id)
            ->getQuery();

        $products = $query->getArrayResult();
        return $products;
    }

    public function thingstodoDetailsEn($where, $order, $nlimit, $skip)
    {
        $query = $this->createQueryBuilder('t')
            ->select('t')
            ->where($where)
            ->andWhere('t.published = 1')
            ->setFirstResult($skip)
            ->setMaxResults($nlimit)
            ->orderBy('t.orderDisplay', $order)
            ->getQuery();

        $products = $query->getArrayResult();
        return $products;
    }
    
    /**
     * This method calls the getThingstodoDetailsSlug to get ThingstodoDetails Slug.
     *
     * @param string $slug
     *
     */
    public function getThingstodoDetailsSlug( $slug, $language='en' )
    {
        $query = $this->createQueryBuilder('td')
            ->select('td, tdml, t.title AS t_title, tml.title AS tml_title, ta.alias AS ta_alias, tc.title AS tc_title, tcml.title AS tcml_title, tca.alias AS tca_alias, tr.title AS tr_title, trml.title AS trml_title, tra.alias AS tra_alias')
            ->leftJoin('TTBundle:MlThingstodoDetails', 'tdml', 'WITH', 'tdml.parentId = td.id AND tdml.language=:Language')
            ->innerJoin('TTBundle:CmsThingstodo', 't', 'WITH', 't.id = td.parentId')
            ->leftJoin('TTBundle:MlThingstodo', 'tml', 'WITH', 'tml.parentId = t.id AND tml.language=:Language')
            ->innerJoin('TTBundle:Alias', 'ta', 'WITH', 'ta.id = t.aliasId')
            ->innerJoin('TTBundle:CmsThingstodoCountry', 'tc', 'WITH', 'tc.id = t.parentId')
            ->leftJoin('TTBundle:MlThingstodoCountry', 'tcml', 'WITH', 'tcml.parentId = tc.id AND tcml.language=:Language')
            ->innerJoin('TTBundle:Alias', 'tca', 'WITH', 'tca.id = tc.aliasId')
            ->innerJoin('TTBundle:CmsThingstodoRegion', 'tr', 'WITH', 'tr.id = tc.parentId')
            ->leftJoin('TTBundle:MlThingstodoRegion', 'trml', 'WITH', 'trml.parentId = tr.id AND trml.language=:Language')
            ->innerJoin('TTBundle:Alias', 'tra', 'WITH', 'tra.id = tr.aliasId')
            ->where('td.slug = :Slug')
            ->setMaxResults('1')
            ->setParameter(':Language', $language)
            ->setParameter(':Slug', $slug)
            ->getQuery();

        return $query->getScalarResult();
    }

    /**
     * This method calls the updateThingstodoDetailsSlug to update ThingstodoDetails Slug .
     *
     * @param integer $id
     * @param string $slug
     *
     */
    public function updateThingstodoDetailsSlug( $id, $slug )
    {
        $qb = $this->createQueryBuilder('td')
            ->update('TTBundle:CmsThingstodoDetails', 'td')
            ->set("td.slug", ":slug")
            ->where("td.id=:Id")
            ->setParameter(':slug', $slug)
            ->setParameter(':Id', $id);
        $query = $qb->getQuery();
        return $query->getResult();
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
    public function getThingstodoDivisions( $ttdId, $categoryId = null, $divisionId = null, $withSubDivisions = false )
    {
        if( $withSubDivisions=='true' || $withSubDivisions===true || $withSubDivisions ==1 )
        {
            $withSubDivisions = true;
        }else{
            $withSubDivisions = false;
        }
        
        $qb  = $this->createQueryBuilder('td');
        $qb->select('td, tds, tdc, d')
            ->leftJoin('TTBundle:ThingstodoDivision', 'tds', 'WITH', 'tds.id = td.parentId AND td.parentId IS NOT NULL')
            ->innerJoin('TTBundle:ThingstodoDivisionCategory', 'tdc', 'WITH', 'tdc.id = td.divisionCategoryId')
            ->leftJoin('TTBundle:CmsThingstodoDetails', 'd', 'WITH', 'd.id = td.ttdId');

        if( $ttdId !=NULL )
        {            
            $qb->andWhere('td.ttdId=:TtdId');
            $qb->setParameter(':TtdId', $ttdId);
        }
        
        if( $divisionId !=NULL && $withSubDivisions )
        {
            $qb->andWhere('( td.id=:Id OR td.parentId=:Id )');
            $qb->setParameter(':Id', $divisionId);
        } else if( $divisionId !=NULL )
        {
            $qb->andWhere('td.id=:Id');
            $qb->setParameter(':Id', $divisionId);
        } else if( !$withSubDivisions )
        {
            $qb->andWhere('td.parentId IS NULL');
        }

        if( $categoryId !=NULL )
        {
            $qb->andWhere('td.divisionCategoryId=:CategoryId');
            $qb->setParameter(':CategoryId', $categoryId);
        }

        $qb->orderBy('td.sortOrder', 'ASC');
        $quer   = $qb->getQuery();
        return $quer->getScalarResult();
    }

    public function thingstodoDetailsNotEn($where, $orderby, $options, $nlimit, $skip)
    {

        $query = $this->createQueryBuilder('t')
            ->select('t,ml.title,ml.description')
            ->innerJoin('TTBundle:MlThingstodoDetails', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language')
            ->where($where)
            ->andWhere('t.published = 1')
            ->setFirstResult($skip)
            ->setMaxResults($nlimit)
            ->orderBy($orderby)
            ->setParameter(':Language', $options['lang'])
            ->getQuery();

        $products = $query->getArrayResult();
        $count    = $query->rowCount();
        if ($count && $products > 0) {
            return $products;
        }
        return array();
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
        $qb = $this->createQueryBuilder('t')
        ->select('t, ml, a, c.name AS c_name, w.stateCode AS w_stateCode, s.stateName AS s_stateName, st.stateCode AS st_stateCode, st.stateName AS st_stateName, w.name AS w_name, w.countryCode AS w_countryCode');

        $qb->leftJoin('TTBundle:MlThingstodo', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language');

        $qb->leftJoin('TTBundle:Alias', 'a', 'WITH', 'a.id = t.aliasId');
        $qb->leftJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id=t.cityId')
        ->leftJoin('TTBundle:CmsCountries', 'c', 'WITH', 'c.code=t.countryCode')
        ->leftJoin('TTBundle:States', 's', 'WITH', 's.countryCode=w.countryCode AND s.stateCode=w.stateCode')
        ->leftJoin('TTBundle:States', 'st', 'WITH', 'st.countryCode=t.countryCode AND st.stateCode=t.stateCode');

        $qb->where("t.id = :Id ")
        ->setParameter(':Language', $language)
        ->setParameter(':Id', $id);
        $query = $qb->getQuery();
        
        $result = $query->getScalarResult();
        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
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
        $qb = $this->createQueryBuilder('t')
            ->select('t,ml,a, c.name AS c_name, s.stateName AS s_stateName, w.name AS w_name, w.countryCode AS w_countryCode');

        $qb->leftJoin('TTBundle:MlThingstodoCountry', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language');

        $qb->leftJoin('TTBundle:Alias', 'a', 'WITH', 'a.id = t.aliasId');
        $qb->leftJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id=t.cityId')
            ->leftJoin('TTBundle:CmsCountries', 'c', 'WITH', 'c.code=t.countryCode')
            ->leftJoin('TTBundle:States', 's', 'WITH', 's.countryCode=t.countryCode AND s.stateCode=t.stateCode');
        
        $qb->where("t.id = :Id and t.published=1")
            ->setParameter(':Language', $language)
            ->setParameter(':Id', $id);
        $query = $qb->getQuery();
        
        $result = $query->getScalarResult();
        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
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
        $qb = $this->createQueryBuilder('t')
            ->select('t,ml,a');

        $qb->leftJoin('TTBundle:MlThingstodoRegion', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language');

        $qb->leftJoin('TTBundle:Alias', 'a', 'WITH', 'a.id = t.aliasId');
        $qb->where("t.id = :Id and t.published=1")
            ->setParameter(':Language', $language)
            ->setParameter(':Id', $id);
        $query = $qb->getQuery();

        $result = $query->getScalarResult();
        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
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
        $default_opts = array(
            'limit' => null,
            'page' => 1,
            'show_main' => 1,
            'city_id' => null,
            'country_code' => null,
            'id' => null,
            'lang' => 'en',
            'except_id' => null,
            'parent_id' => null
        );
        $options      = array_merge($default_opts, $srch_options);

        if (intval($options['page']) < 1)
        {
            $options['page'] = 1;
        }
        
        $qb = $this->createQueryBuilder('t')
        ->select('t, ml, a, pt, pa');

        $qb->leftJoin('TTBundle:MlThingstodo', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language');

        $qb->leftJoin('TTBundle:Alias', 'a', 'WITH', 'a.id = t.aliasId');
        $qb->innerJoin('TTBundle:CmsThingstodoCountry', 'pt', 'WITH', 't.parentId = pt.id');
        $qb->leftJoin('TTBundle:Alias', 'pa', 'WITH', 'pa.id = pt.aliasId');
        
        $qb->where("t.published=1");
        $qb->setParameter(':Language', $options['lang']);
        
        $inner_q = $this->getEntityManager()->createQuery("SELECT d.id FROM TTBundle:CmsThingstodoDetails d WHERE d.parentId=t.id")
        ->setMaxResults(1)
        ->getDQL();
        $qb->andwhere("EXISTS($inner_q)");
        
        if (!is_null($options['id']) && intval($options['id']) > 0)
        {
            $qb->andwhere("t.id =:Id");
            $qb->setParameter(':Id', intval($options['id']));
        }
        
        if (!is_null($options['except_id']) && intval($options['except_id']) > 0)
        {
            $qb->andwhere("t.id !=:Except_id");
            $qb->setParameter(':Except_id', intval($options['except_id']));
        }
        
        if (!is_null($options['city_id']) && intval($options['city_id']) > 0)
        {
            $qb->andwhere("t.cityId =:City_id");
            $qb->setParameter(':City_id', intval($options['city_id']));
        }
        
        if (!is_null($options['parent_id']) && intval($options['parent_id']) > 0)
        {
            $qb->andwhere("t.parentId =:Parent_id");
            $qb->setParameter(':Parent_id', intval($options['parent_id']));
        }
        
        if ( !is_null($options['country_code']) && $options['country_code'] !='' )
        {
            $qb->andwhere("(t.countryCode =:Country_code OR pt.countryCode =:Country_code)");
            $qb->setParameter(':Country_code', $options['country_code']);
        }
        
        $nlimit = '';
        if ( !is_null($options['limit']) && intval($options['limit']) > 0) {
            $nlimit = intval($options['limit']);
            $skip   = (intval($options['page']) - 1) * $nlimit;

            $qb->setMaxResults($nlimit)
                ->setFirstResult($skip);
        }
        
        if (!is_null($options['show_main'])) {
            if (intval($options['show_main']) == 0) {
                //for trends section
                $qb->orderBy('t.showMain', 'DESC');
            } else {
                $qb->andwhere("t.showMain =:ShowMain");
                $qb->setParameter(':ShowMain', intval($options['show_main']))
                ->orderBy('t.orderDisplay', 'DESC');
            }
        } else {
            $qb->orderBy('t.orderDisplay', 'DESC');
        }
        $query = $qb->getQuery();
        return $query->getScalarResult();
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
        $default_opts = array(
            'limit' => null,
            'page' => 1,
            'show_main' => null,
            'city_id' => null,
            'id' => null,
            'lang' => 'en',
            'except_id' => null,
            'parent_id' => null
        );
        $options      = array_merge($default_opts, $srch_options);
        
        if (intval($options['page']) < 1)
        {
            $options['page'] = 1;
        }

        $qb = $this->createQueryBuilder('t')
        ->select('t,ml,a');

        $qb->leftJoin('TTBundle:MlThingstodoCountry', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language');

        $qb->leftJoin('TTBundle:Alias', 'a', 'WITH', 'a.id = t.aliasId');

        $qb->where("t.published=1");
        $qb->setParameter(':Language', $options['lang']);

        $inner_q = $this->getEntityManager()->createQuery("SELECT d.id FROM TTBundle:CmsThingstodo d WHERE d.parentId=t.id")
        ->setMaxResults(1)
        ->getDQL();
        $qb->andwhere("EXISTS($inner_q)");
        if (!is_null($options['id']) && intval($options['id']) > 0) {
            $qb->andwhere("t.id =:Id");
            $qb->setParameter(':Id', intval($options['id']));
        }
        if (!is_null($options['except_id']) && intval($options['except_id']) > 0) {
            $qb->andwhere("t.id !=:Except_id");
            $qb->setParameter(':Except_id', intval($options['except_id']));
        }
        if (!is_null($options['city_id']) && intval($options['city_id']) > 0) {
            $qb->andwhere("t.cityId =:City_id");
            $qb->setParameter(':City_id', intval($options['city_id']));
        }
        if (!is_null($options['parent_id']) && intval($options['parent_id']) > 0) {
            $qb->andwhere("t.parentId =:Parent_id");
            $qb->setParameter(':Parent_id', intval($options['parent_id']));
        }

        $nlimit = '';
        if ( !is_null($options['limit']) && intval($options['limit']) > 0) {
            $nlimit = intval($options['limit']);
            $skip   = (intval($options['page']) - 1) * $nlimit;

            $qb->setMaxResults($nlimit)
                ->setFirstResult($skip);
        }

        if (!is_null($options['show_main'])) {
            if (intval($options['show_main']) == 0) {
                //for trends section
                $qb->orderBy('t.showMain', 'DESC');
            } else {
                $qb->andwhere("t.showMain =:ShowMain");
                $qb->setParameter(':ShowMain', intval($options['show_main']))
                ->orderBy('t.orderDisplay', 'DESC');
            }
        } else {
            $qb->orderBy('t.orderDisplay', 'DESC');
        }
        $query = $qb->getQuery();
        return $query->getScalarResult();
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
        $default_opts = array(
            'limit' => null,
            'show_main' => null,
            'lang' => 'en',
            'city_id' => null,
            'id' => null
        );
        $options      = array_merge($default_opts, $srch_options);

        $qb = $this->createQueryBuilder('t')
        ->select('t,ml,a');

        $qb->leftJoin('TTBundle:MlThingstodoRegion', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language');

        $qb->leftJoin('TTBundle:Alias', 'a', 'WITH', 'a.id = t.aliasId');
        $qb->where("t.published=1")
        ->setParameter(':Language', $options['lang']);

        $inner_q = $this->getEntityManager()->createQuery("SELECT d.id FROM TTBundle:CmsThingstodoCountry d WHERE d.parentId=t.id")
        ->setMaxResults(1)
        ->getDQL();
        $qb->andwhere("EXISTS($inner_q)");
        if (!is_null($options['id']) && intval($options['id']) > 0) {
            $qb->andwhere("t.id =:Id");
            $qb->setParameter(':Id', intval($options['id']));
        }
        if (!is_null($options['city_id']) && intval($options['city_id']) > 0) {
            $qb->andwhere("t.cityId =:City_id");
            $qb->setParameter(':City_id', intval($options['city_id']));
        }
        if (!is_null($options['limit']) && intval($options['limit']) > 0) {
            $qb->setMaxResults(intval($options['limit']));
        }
        if (!is_null($options['show_main']) && intval($options['show_main'])>0) {
            $qb->andwhere("t.showMain =:ShowMain");
            $qb->setParameter(':ShowMain', intval($options['show_main']));
        }
        $qb->orderBy('t.id', 'ASC');
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /**
     * This method calls the getPoiTopList to get CmsThingstodoDetails list.
     *
     *
     * @return array
     */
    public function getPoiTopList( $country_code='', $state_code='', $city_id=0, $limit=null, $orderby='', $lang= 'en' )
    {
        $qb = $this->createQueryBuilder('t')
        ->select('t,ml,pt,a,td');
        if ($orderby == 'rand') {
            $qb->addSelect('RAND() as HIDDEN rand');
        }

        $qb->leftJoin('TTBundle:MlThingstodoDetails', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language');
        
        $qb->innerJoin('TTBundle:CmsThingstodo', 'pt', 'WITH', 't.parentId = pt.id');
        $qb->leftJoin('TTBundle:Alias', 'a', 'WITH', 'a.id = pt.aliasId');
        $qb->leftJoin('TTBundle:ThingstodoDivision', 'td', 'WITH', 'td.ttdId = t.id AND td.parentId IS NULL');

        if (intval($city_id) > 0)
        {
            $qb->where("pt.cityId = :city_id")
            ->setParameter(':city_id', $city_id);
        } else if ($state_code != "" && $country_code != "")
        {
            $qb->where("pt.countryCode = :countrycode and pt.stateCode = :stateCode")
            ->setParameter(':countrycode', $country_code)
            ->setParameter(':stateCode', $state_code);
        } else if ( $country_code != "")
        {
            $qb->where("pt.countryCode = :countrycode")
            ->setParameter(':countrycode', $country_code);
        }

        $qb->andwhere("t.published=1 and t.entityType=30");
        $qb->setParameter(':Language', $lang);

        if ($orderby == 'rand')
        {
            $qb->orderBy('rand');
        } else {
            $qb->orderBy('t.orderDisplay', 'DESC');
        }

        if( $limit !=null )
        {
            $qb->setMaxResults($limit);
        }
        
        $query = $qb->getQuery();
        return $query->getScalarResult();
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
        $qb = $this->createQueryBuilder('t')
        ->select('a');
        
        $qb->innerJoin('TTBundle:CmsThingstodo', 'w', 'WITH', 'w.id=t.parentId');
        $qb->innerJoin('TTBundle:Alias', 'a', 'WITH', 'a.id=w.aliasId');
        $qb->where("t.cityId = :city_id and t.published=1")
        ->setParameter(':city_id', $city_id);
        $qb->setMaxResults(1);
        $query = $qb->getQuery();
        return $query->getResult();
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
        $qb = $this->createQueryBuilder('t')
        ->select('a');
        $qb->innerJoin('TTBundle:Alias', 'a', 'WITH', 'a.id=t.aliasId');
        $qb->where("t.cityId = :city_id and t.published=1")
        ->setParameter(':city_id', $city_id);
        $qb->setMaxResults(1);
        $query = $qb->getQuery();
        return $query->getResult();
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
        $qb = $this->createQueryBuilder('t')
        ->select('a');
        
        $qb->innerJoin('TTBundle:Alias', 'a', 'WITH', 'a.id=t.aliasId');
        $qb->where("t.countryCode = :CountryCode and t.stateCode = :StateCode and t.published=1")
        ->setParameter(':CountryCode', $countryCode)
        ->setParameter(':StateCode', $stateCode);
        $qb->setMaxResults(1);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method calls the getRelatedThingsToDoList to get CmsThingstodoDetails list.
     *
     * @param $srch_options
     *
     * @return array
     */
    public function getRelatedThingsToDoList($srch_options)
    {
        $params       = array();
        $default_opts = array(
            'orderby' => 'id',
            'order' => 'a',
            'limit' => null,
            'page' => 0,
            'skip' => 0,
            'id' => null,
            'list_id' => null,
            'has_image' => 0,
            'parent_id' => null,
            'country' => null,
            'city_id' => 0,
            'n_results' => false,
            'lang' => 'en'
        );

        $options = array_merge($default_opts, $srch_options);
        if (intval($options['page']) < 1) {
            $options['page'] = 1;
        }
        $where = '';
        if (!is_null($options['id'])) {
            if ($where != '') $where    .= ' AND ';
            $where    .= " t.id=:Id ";
            $params[] = array("key" => ":Id", "value" => $options['id']);
        }
        if (!is_null($options['list_id'])) {
            if ($where != '') $where .= ' AND ';
            $where .= " t.id IN(".$options['list_id'].")";
        }
        if (!is_null($options['country'])) {
            if ($where != '') $where    .= ' AND ';
            $where    .= " t.country=:Country ";
            $params[] = array("key" => ":Country", "value" => $options['country']);
        }
        if ($options['has_image'] == 1) {
            if ($where != '') $where .= ' AND ';
            $where .= " t.image<>'' ";
        }
        if (!is_null($options['parent_id'])) {
            if ($where != '') $where    .= ' AND ';
            $where    .= " t.parentId=:Parent_id ";
            $params[] = array("key" => ":Parent_id", "value" => $options['parent_id']);
        }
        if ($options['city_id'] > 0) {
            if ($where != '') $where    .= ' AND ';
            $where    .= " t.city_id=:City_id ";
            $params[] = array("key" => ":City_id", "value" => $options['city_id']);
        }

        $orderby = $options['orderby'];
        if ($orderby == 'rand') {
            $orderby = "id";
        }
        $nlimit = '';
        if (!is_null($options['limit'])) {
            $nlimit = intval($options['limit']);
            $skip   = (intval($options['page']) - 1) * $nlimit + intval($options['skip']);
        }
        $params[] = array("key" => ":Language", "value" => $options['lang']);
        if (!$options['n_results']) {
            $orderby = 't.'.$orderby;
            $order   = ($options['order'] == 'a') ? 'ASC' : 'DESC';
            $em      = $this->getEntityManager();
            $params1 = array();
            foreach ($params as $value) {
                $params1[$value['key']] = $value['value'];
            }
            $qb = $em->createQueryBuilder('VI');
            $qb->select('t, ml, td, pt, a')
                ->from("TTBundle:CmsThingstodoDetails", 't');

            $qb->leftJoin('TTBundle:MlThingstodoDetails', 'ml', 'WITH', 'ml.parentId = t.id AND ml.language=:Language')
               ->leftJoin('TTBundle:ThingstodoDivision', 'td', 'WITH', 'td.ttdId = t.id AND td.parentId IS NULL');

            $qb->innerJoin('TTBundle:CmsThingstodo', 'pt', 'WITH', 't.parentId = pt.id');
            $qb->leftJoin('TTBundle:Alias', 'a', 'WITH', 'a.id = pt.aliasId');

            $qb->where("$where AND t.published = 1")
                ->setParameters($params1);

            $qb->orderBy("$orderby", "$order");
            if (!is_null($options['limit'])) {
                $qb->setMaxResults($nlimit)
                    ->setFirstResult($skip);
            }
            $query    = $qb->getQuery();
            $resQuery = $query->getScalarResult();
            if ($resQuery && count($resQuery) > 0) {
                return $resQuery;
            } else {
                return array();
            }
        } else {
            if ($options['lang'] == 'en') {
                $query = "SELECT count(t.id) FROM TTBundle:CmsThingstodoDetails t LEFT JOIN TTBundle:MlThingstodoDetails AS ml WITH ml.parentId = t.id AND ml.language=:Language WHERE $where AND t.published = 1";
            } else {
                $query = "SELECT count(t.id) FROM TTBundle:CmsThingstodoDetails t LEFT JOIN TTBundle:MlThingstodoDetails AS ml WITH ml.parentId = t.id AND ml.language=:Language WHERE $where AND t.published = 1";
            }
            $em    = $this->getEntityManager();
            $query = $em->createQuery($query);
            foreach ($params as $value) {
                $query = $query->setParameter($value['key'], $value['value']);
            }
            $resQuery = $query->getSingleScalarResult();
            return $resQuery;
        }
    }
}
