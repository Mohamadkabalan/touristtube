<?php

namespace TTBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CitiesRepository extends EntityRepository
{
    /*
    * @worldcitiespopInfo function return worldcitiespop Info
    */
    public function worldcitiespopInfo( $id )
    {
        $qb    = $this->createQueryBuilder('w')
        ->select('w,co,s,con')
        ->innerJoin('TTBundle:CmsCountries', 'co', 'WITH', 'co.code=w.countryCode')
        ->leftJoin('TTBundle:States', 's', 'WITH', 's.countryCode=w.countryCode AND s.stateCode=w.stateCode')
        ->leftJoin('TTBundle:CmsContinents', 'con', 'WITH', 'con.code=co.continentCode')
        ->where('w.id = :id')
        ->setParameter(':id', $id);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @worldStateInfo function return state Info
    */
    public function worldStateInfo( $country_code, $state_code )
    {
        $qb    = $this->createQueryBuilder('s')
        ->select('s,co')
        ->innerJoin('TTBundle:CmsCountries', 'co', 'WITH', 'co.code=s.countryCode')
        ->where('s.countryCode=:CountryCode AND s.stateCode=:StateCode')
        ->setParameter(':CountryCode', $country_code)
        ->setParameter(':StateCode', $state_code);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    * @worldStateInfo function return state Info
    */
    public function continentGetInfo( $continent_code )
    {
        $qb    = $this->createQueryBuilder('con')
        ->select('con')
        ->where('con.code=:Continent_code')
        ->setParameter(':Continent_code', $continent_code);
        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
    * @getEntityDescription function return Entity Description data
    */
    public function getEntityDescription( $entity_type, $entity_id )
    {
        $qb    = $this->createQueryBuilder('ced')
            ->select('ced')
            ->where('ced.entityType=:entity_type and ced.entityId=:entity_id')
            ->setParameter(':entity_type', $entity_type)
            ->setParameter(':entity_id', $entity_id);
        $query  = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }
}
