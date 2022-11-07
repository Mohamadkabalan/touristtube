<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;

class CorpoAdminMenuRepository extends EntityRepository
{

    public function getMenusPathBySlug($slug)
    {
        $query = $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.url LIKE :slug')
            ->setParameter(':slug','%' .$slug);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }
    /**
     * This method will retrieve all menu of the corporate admin
     * @param 
     * @return doctrine object result of all menus or false in case of no data
     * */
    public function getCorpoAdminMenu($parameters)
    {
        $query = $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.published=1');

        $query->orderBy('m.order', 'ASC');
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve menus
     * @param $menuObj
     * @return doctrine object result of all menus or false in case of no data
     * */
    public function getMenus($menuObj, $profileMenus = NULL)
    {
        if($menuObj->rootUser) {
            $query = $this->createQueryBuilder('m')
                ->select('m')
                ->where('m.published=1');
        } else {
            $query = $this->createQueryBuilder('m')
                ->select('m')
                ->where('m.published = 1')
                ->andWhere('m.menuKey IN (:profileMenus)')
                ->setParameter(':profileMenus', $profileMenus);
        }

        $id = $menuObj->getId();
        $name = $menuObj->getName();
        $parentId = $menuObj->getParent();
        $path = $menuObj->getPath();

        if (!empty($id)) {
            $query->andwhere('m.id=:id')
                ->setParameter(':id', $id);
        }
        if (!empty($name)) {
            $query->andwhere('m.name=:name')
                ->setParameter(':name', $name);
        }
        if (!empty($parentId)) {
            $query->andwhere('m.parentId=:parentId')
                ->setParameter(':parentId', $parentId);
        }
        if (!empty($path)) {
            $query->andwhere('m.path=:path')
                ->setParameter(':path', $path);
        }

        $query->addOrderBy('m.order', 'ASC')
            ->groupBy('m.id');
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }
}
