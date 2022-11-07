<?php

namespace TTBundle\Repository;
use Doctrine\ORM\EntityRepository;
 
class AirportRepository extends EntityRepository
{
  public function getUSA()
  {
   $query = $this->getEntityManager()->createQuery("SELECT a FROM TTBundle:Airport a WHERE a.country = 'US'");
    return $query->getResult();
  }
}
