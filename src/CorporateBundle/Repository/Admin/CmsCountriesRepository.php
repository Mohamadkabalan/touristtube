<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CmsCountries;

class CmsCountriesRepository extends EntityRepository
{

    /**
     * This method will retrieve list of countries like term
     * @param term
     * @return doctrine object result of countries or false in case of no data
     * */
    public function getCountryCombo($term, $limit)
    {
        $query = $this->createQueryBuilder('c')
            ->select('c.name,c.id,c.code');
        if (isset($term)) {
            $query->where('CONCAT_WS(c.code, c.name, " ") LIKE :term')
                ->setParameter('term', '%'.$term.'%');
        }
        $query->setMaxResults($limit);

        $quer   = $query->getQuery();
        $result = $quer->getResult();
        
        if (!empty($result) && isset($result[0])) {
            return $result;
        }else{
            return array();
        }
    }
}