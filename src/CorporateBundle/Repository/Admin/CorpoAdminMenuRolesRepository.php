<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;

class CorpoAdminMenuRolesRepository extends EntityRepository
{

    /**
     * This method will retrieve all menu items visible by a certain roleId
     *
     * @param $roleId
     * */
    public function getMenuItemsByRole($roleId)
    {
        $query  = $this->createQueryBuilder('m')
            ->select('c.id AS id, c.name AS name, c.parentId AS parent, c.mobileTriggerMethod AS triggerMethod, cp.name AS parentName')
            ->leftJoin('CorporateBundle:CorpoAdminMenu', 'c', 'WITH', 'c.id = m.corpoAdminMenuId')
            ->leftJoin('CorporateBundle:CorpoAdminMenu', 'cp', 'WITH', 'cp.id = c.parentId')
            ->where('m.cmsUserGroupId=:cmsUserGroupId')
            ->setParameter(':cmsUserGroupId', $roleId)
            ->andwhere('c.enableForMobile=:enableForMobile')
            ->setParameter('enableForMobile', 1);
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            $toreturn = array();
            foreach ($result as $menuItem) {
                $item = new \CorporateBundle\Model\Menu();
                $item->setId($menuItem['id']);
                $item->setName($menuItem['name']);

                if ($menuItem['parent']) {
                    $parent = new \CorporateBundle\Model\Menu();
                    $parent->setId($menuItem['parent']);
                    $parent->setName($menuItem['parentName']);
                    $parent->removeAttributes();

                    $item->setParent($parent);
                }

                $item->setMethod($menuItem['triggerMethod']);

                $toreturn[] = $item;
            }

            return $toreturn;
        } else {
            return array();
        }
    }
}
