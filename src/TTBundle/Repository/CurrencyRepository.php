<?php

namespace TTBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TTBundle\Entity\CurrencyRate;

class CurrencyRepository extends EntityRepository {

    public function updateCurrencyRates($newRates) {
        $date = new \DateTime("now");
        
        foreach ($newRates as $index => $rate) {
			
			$qb = $this->createQueryBuilder('u');
			
            $query = $qb->update('TTBundle:CurrencyRate c')
                    ->set('c.currencyRate', $rate)
                    ->set('c.lastUpdate', ':date')
                    ->Where('c.currencyCode = :code')
                    ->setParameter('code', $index)
                    ->setParameter('date', $date)
                    ->getQuery()
                    ->getResult();
        }
        return $qb;
    }
}
