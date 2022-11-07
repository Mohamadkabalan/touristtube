<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use TTBundle\Utils\Utils;

class CorpoProfilePermissionsRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    public function prepareProfilesPermissionDtQuery()
    {
        $query = "SELECT pp.id pp__id, up.name up__name, GROUP_CONCAT(m.name SEPARATOR ' | ') menu_access, FullName, pp.updated_on pp__updated_on
            FROM corpo_user_profile_menus_permission pp
            LEFT JOIN corpo_user_profiles up ON (up.id = pp.user_profile_id)
            LEFT JOIN corpo_admin_menu m ON (m.id = pp.corpo_menu_id)
            LEFT JOIN cms_users cu ON (cu.id = pp.updated_by)
            GROUP BY up.id
        ";
        $result_arr["all_query"] = $query;
        return Utils::prepareDatatableObj($result_arr);
    }
}