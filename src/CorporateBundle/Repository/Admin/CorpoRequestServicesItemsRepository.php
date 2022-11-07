<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoRequestServicesItems;

class CorpoRequestServicesItemsRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will add a Request Item
     * @param Request Item info list
     * @return doctrine result of Request Item id or false in case of no data
     * */
    public function addRequestServicesItems($id, $serviceId)
    {
        $this->em             = $this->getEntityManager();
        $requestServicesItems = new CorpoRequestServicesItems();
        $requestServicesItems->setServiceId($serviceId);
        $requestServicesItems->setRequestId($id);
        $this->em->persist($requestServicesItems);
        $this->em->flush();
        if ($requestServicesItems) {
            return $requestServicesItems->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will delete a Request Item
     * @param id of Request Item
     * @return doctrine object result of Request Item or false in case of no data
     * */
    public function deleteRequestServicesItems($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->delete('CorporateBundle:CorpoRequestServicesItems', 'p')
            ->where("p.requestId = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will retrieve a Request Item of corporate
     * @param id of Request Item
     * @return doctrine object result of Request Item or false in case of no data
     * */
    public function getRequestItemsList($id)
    {
        $query = $this->createQueryBuilder('i')
            ->select('i.serviceId')
            ->where("i.requestId = :ID")
            ->setParameter(':ID', $id);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }
}