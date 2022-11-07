<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoAccountTypeMenu;

class CorpoAccountTypeMenuRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will add menus for an account type for corporate accounts
     * @param $menuObj
     * @return doctrine result of account id or false in case of no data
     * */
    public function addAccountTypeMenus($menuObj)
    {
        $this->em = $this->getEntityManager();
        $typeId = $menuObj->getType()->getId();
        $menus = $menuObj->getMenus();

        foreach($menus as $menu) {
            $accountTypeMenu  = new CorpoAccountTypeMenu();
            $accountTypeMenu->setAccountTypeId($typeId);
            $accountTypeMenu->setMenuId($menu);
            $this->em->persist($accountTypeMenu);
        }
        $this->em->flush(); 
        
        if ($accountTypeMenu) {
            return $accountTypeMenu->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will delete an account type menu list for corporate account type
     * @param $id
     * @return doctrine result of account type menu list or false in case of no data
     * */
    public function deleteAccountTypeMenuList($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('datm')
            ->delete('CorporateBundle:CorpoAccountTypeMenu', 'datm')
            ->where("datm.accountTypeId = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }
}