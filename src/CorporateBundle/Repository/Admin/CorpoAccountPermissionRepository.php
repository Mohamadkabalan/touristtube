<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoAccountPermission;
use CorporateBundle\Entity\CorpoAdminMenu;

class CorpoAccountPermissionRepository extends EntityRepository
{

    /**
     * This method will add an Menu list Permission for corporate accounts
     * @param account info list
     * @return doctrine result of account id or false in case of no data
     * */
    public function addMenulistPermission($id, $menu)
    {
        $this->em          = $this->getEntityManager();
        $accountPermission = new CorpoAccountPermission();
        $accountPermission->setAccountId($id);
        $accountPermission->setMenuId($menu);
        $this->em->persist($accountPermission);
        $this->em->flush();
        if (isset($accountPermission)) {
            return $accountPermission->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will get menu list for users
     * @param account info list
     * @return doctrine result of account id or false in case of no data
     * */
    public function getAccountUserMenuList($id)
    {
        $query = $this->createQueryBuilder('a')
            ->select('a', 'm')
            ->innerJoin('CorporateBundle:CorpoAdminMenu', 'm', 'WITH', "a.menuId = m.id")
            ->where('a.accountId = :ID')
            ->setParameter(':ID', $id);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will add an Menu list Permission for corporate accounts
     * @param account info list
     * @return doctrine result of account id or false in case of no data
     * */
    public function getAccountMenuByIdList($id)
    {
        $query = $this->createQueryBuilder('a')
            ->select('a')
            ->where('a.accountId = :ID')
            ->setParameter(':ID', $id);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will delete a Menu list Permission for corporate accounts
     * @param account info list
     * @return doctrine result of Menu list Permission or false in case of no data
     * */
    public function deleteMenulistPermission($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ds')
            ->delete('CorporateBundle:CorpoAccountPermission', 'ds')
            ->where("ds.accountId = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }
}
