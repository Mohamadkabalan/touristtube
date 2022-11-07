<?php

namespace TTBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CmsCountriesRepository extends EntityRepository
{

    public function countriesSelect()
    {
        $query = $this->getEntityManager()->createQueryBuilder('d')
            ->select('d')
            ->from('TTBundle:CmsCountries', 'd')
            ->orderBy('d.name', 'ASC');
        return $query;
    }

    public function getIso3CountryByCode($code)
    {
        if ($code) {
            $query  = $this->createQueryBuilder('cc')
                ->select('cc.iso3')
                ->where("cc.code = :code")
                ->setParameter(':code', $code);
            $query  = $query->getQuery();
            $result = $query->getResult();

            if (!empty($result) && isset($result[0])) {
                return $result[0]['iso3'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getNameByIso3Code($code)
    {
        if ($code) {
            $query  = $this->createQueryBuilder('cc')
                ->select('cc.name')
                ->where("cc.iso3 = :code")
                ->setParameter(':code', $code);
            $query  = $query->getQuery();
            $result = $query->getResult();

            if (!empty($result) && isset($result[0])) {
                return $result[0]['name'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getCountryList()
    {
        $qb     = $this->createQueryBuilder('cc')
            ->select('cc.name, cc.iso3, cc.dialingCode')
            ->orderBy('cc.name', 'ASC');
        $query  = $qb->getQuery();
        $result = $query->getScalarResult();
        return $result;
    }

    public function getMobileCountryCodeList()
    {
        $qb     = $this->createQueryBuilder('cc')
            ->select('cc')
            ->where('cc.dialingCode != 0')
            ->orderBy('cc.name', 'ASC');
        $query  = $qb->getQuery();
        $result = $query->getResult();

        $mobiles = array();
        foreach ($result as $key => $value) {
            $mobiles[] = array(
                'iso3' => $value->getIso3(),
                'dialingCode' => '+'.$value->getDialingCode(),
                'dialingCodeToString' => $value->getDialingCodeToString(),
            );
        }
        return $mobiles;
    }

    public function getIso3CountryByIp($ip)
    {
        $xml = file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip);
        if (!empty($xml)) {
            $clientGeoInfo = json_decode($xml, true);
            if (!empty($clientGeoInfo['geoplugin_countryCode'])) {
                return $this->getIso3CountryByCode($clientGeoInfo['geoplugin_countryCode']);
            }
        }
        return null;
    }

    /**
     * This method returns a list of all countries sorted by name
     *
     * @return An array that contains the id, the ISO-2 code and the name of the country
     */
    public function getCountries()
    {
        $query  = $this->createQueryBuilder('CmsCountries')
            ->select('CmsCountries.id, CmsCountries.code, CmsCountries.name')
            ->orderBy('CmsCountries.name', 'ASC');
        $query  = $query->getQuery();
        $result = $query->getScalarResult();

        return $result;
    }

    /**
     * This method returns a list of dialing codes of all countries sorted by name
     *
     * @return An array that contains the id, code(ISO-2), code(ISO-3), name, dialing_code and flag_icon of all countries
     */
    public function getCountriesDialingCodes()
    {
        $query  = $this->createQueryBuilder('CmsCountries')
            ->select('CmsCountries.id, CmsCountries.code, CmsCountries.iso3, CmsCountries.name, concat(\'+\', CmsCountries.dialingCode) as dialing_code, concat(\'/media/images/flag-icons/\', CmsCountries.flagIcon) as flag_icon')
            ->orderBy('CmsCountries.name', 'ASC');
        $query  = $query->getQuery();
        $result = $query->getScalarResult();

        return $result;
    }

    public function getCountryCombo($tt_search_critiria_obj)
    {
        $em = $this->getEntityManager();

        $searchTerm = $tt_search_critiria_obj->getTerm();
        $page       = $tt_search_critiria_obj->getPage();
        $limit      = $tt_search_critiria_obj->getLimit();
        $sortOrder  = $tt_search_critiria_obj->getSortOrder();
        $start      = $tt_search_critiria_obj->getStart();

        $query ="SELECT count(p)  FROM TTBundle:CmsCountries p WHERE CONCAT_WS(p.code, p.name, ' ') like :searchterm ";
        $query_exec = $em->createQuery($query)->setParameter('searchterm', "%$searchTerm%");

        $query_res = $query_exec->getResult();

        $count = $query_res[0][1];

        if(!isset($sortOrder)) $sortOrder = " order by p.name ASC";

        $SQL = "SELECT p FROM TTBundle:CmsCountries p WHERE CONCAT_WS(p.code, p.name, ' ') like :searchterm " . $sortOrder;
        $query2_exec = $em->createQuery($SQL)->setParameter('searchterm', "%$searchTerm%")->setFirstResult($start)->setMaxResults($limit);

        $combogrid_cats = $query2_exec->getArrayResult();

        $result_arr["combogrid_cats"] = $combogrid_cats;
        $result_arr["count"] = $count;

        return $result_arr;
    }

    /*
    * @countryGetInfo function return countryGet Info
    */
    public function countryGetInfo( $code )
    {
        $qb    = $this->createQueryBuilder('co')
        ->select('co')
        ->where('co.code=:CountryCode')
        ->setParameter(':CountryCode', $code);
        $query = $qb->getQuery();
        $row   = $query->getResult();
        if (sizeof($row) > 0) {
            return $row[0];
        } else {
            return array();
        }
    }

    /*
    * @countryGetList function return countryGet list
    */
    public function countryGetList()
    {
        $qb    = $this->createQueryBuilder('co')
        ->select('co')
        ->orderBy("co.name", "ASC");
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

}