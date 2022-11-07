<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoAccount;
use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CorpoAccountRepository extends EntityRepository implements ContainerAwareInterface
{
    protected $utils;
    protected $em;
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }  

    /**
     * This method will retrieve all accounts of corporate
     * @param 
     * @return doctrine object result of accounts or false in case of no data
     * */
    public function getAccountList($slug)
    {
        $query = $this->createQueryBuilder('al')
            ->select('al, cu.code as currencyCode')
            ->leftJoin('CorporateBundle:Currency', 'cu', 'WITH', "cu.code = al.currencyCode");

        if($slug != "") {
            $query->select('al, cu.code as currencyCode, at.name as sectionTitle')
                ->InnerJoin('CorporateBundle:CorpoAccountType', 'at', 'WITH', 'at.id = al.accountTypeId')
                ->andWhere('at.slug = :slug')
                ->setParameter(':slug', $slug);
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }
    
    public function prepareAccountDtQuery($request)
    {
        $addJoin = "";
        if(isset($request['params']['slug'])) {
            $slug = $request['params']['slug'];
            switch($slug) {
                case 'company':
                    $profileId = $this->container->getParameter('COMPANY');
                    break;
                case 'agency':
                    $profileId = $this->container->getParameter('AGENCY');
                    break;
                case 'retail-agency':
                    $profileId = $this->container->getParameter('RETAIL_AGENCY');
                    break;
            }
            $addJoin .= "INNER JOIN cms_users u ON (u.corpo_account_id = ca.id AND u.corpo_user_profile_id = $profileId AND u.published = 1)";
        }
        $query = "SELECT ca.id ca__id, ca.name ca__name, c.name c__name, ca.mobile ca__mobile, ca.credit_limit ca__credit_limit, ca.currency_code ca__currency_code, ca.preferred_currency ca__preferred_currency, ca.email ca__email
            FROM corpo_account ca
            LEFT JOIN webgeocities c ON (c.id = ca.city_id)
            $addJoin
        ";

        $where = "";
        if(!empty($request['params']['salesUserId'])) {
            $salesUserId = $request['params']['salesUserId'];
            $where .= " AND ca.created_by = $salesUserId";
        } elseif($request['userRole'] != $request['systemRole']) {
            $userId = $request['userId'];
            $where .= " AND ca.created_by = $userId";
        }

        $result_arr["all_query"] = $query;
        $result_arr['where'] = $where;
        return Utils::prepareDatatableObj($result_arr);
    }

    /**
     * This method will retrieve all accounts of corporate
     * @param
     * @return doctrine object result of accounts or false in case of no data
     * */
    public function getAccountWithoutIdList($id)
    {
        $query = $this->createQueryBuilder('al')
            ->select('al')
            ->where("al.id != :ID")
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
     * This method will retrieve all accounts of corporate
     * @param
     * @return doctrine object result of accounts or false in case of no data
     * */
    public function getCorpoAdminLikeAccounts($term, $limit)
    {
        $query = $this->createQueryBuilder('al')
            ->select('al.name,al.id');
        if (isset($term) && $term != '') {
            $query->where('al.name LIKE :term')
                ->setParameter('term', '%'.$term.'%');
        }
        $query->setMaxResults($limit);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve an account  of corporate from an id sent
     * @param id of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function getAccountById($id)
    {
        $query = $this->createQueryBuilder('al')
            ->select('al, gc.name as cityName, cu.name as currencyName, ca.name as accountName, pt.name as paymentTypeName, pt.code as paymentTypeCode, pt.id as paymentTypeId, ag.name as agencyName, pcu.code as preferredCurrencyCode, pcu.name as preferredCurrencyName, cat.name as accountTypeName, gc.id as countryId, gc.countryCode as countryCode, cc.name as countryName')
            ->leftJoin('CorporateBundle:Currency', 'cu', 'WITH', "cu.code = al.currencyCode")
            ->leftJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', "ca.id = al.parentAccountId")
            ->leftJoin('CorporateBundle:CorpoPaymentType', 'pt', 'WITH', "pt.id = al.paymentTypeId")
            ->leftJoin('CorporateBundle:CorpoAgencies', 'ag', 'WITH', "ag.id = al.agencyId")
            ->leftJoin('CorporateBundle:Currency', 'pcu', 'WITH', "pcu.code = al.preferredCurrency")
            ->leftJoin('CorporateBundle:CorpoAccountType', 'cat', 'WITH', "cat.id = al.accountTypeId")
            ->leftJoin('TTBundle:Webgeocities', 'gc', 'WITH', "gc.id = al.cityId")
            ->leftJoin('CorporateBundle:CmsCountries', 'cc', 'WITH', "cc.code = gc.countryCode")
            ->where("al.id = :ID")
            ->setParameter(':ID', $id);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will add an account for corporate accounts
     * @param $accountObj
     * @return doctrine result of account id or false in case of no data
     * */
    public function addAccount($accountObj)
    {
        $this->em = $this->getEntityManager();
        $account  = new CorpoAccount();

        $name = $accountObj->getName();
        $cityId = $accountObj->getCity()->getId();
        $address = $accountObj->getAddress();
        $mobile = $accountObj->getMobile();
        $phone1 = $accountObj->getPhone1();
        $phone2 = $accountObj->getPhone2();
        $creditLimit = $accountObj->getCreditLimit();
        $numberOfUsers = $accountObj->getNumberOfUsers();
        $paymentPeriod = $accountObj->getPaymentPeriod();
        $currencyCode = $accountObj->getCurrency()->getCode();
        $preferredCurrencyCode = $accountObj->getPreferredCurrency()->getCode();
        $website = $accountObj->getWebsite();
        $email = $accountObj->getEmail();
        $accountingEmail = $accountObj->getAccountingEmail();
        $parentId = $accountObj->getParentId();
        $isActive = $accountObj->getIsActive();
        $showCurrencyAmount = $accountObj->getShowAccountCurrencyAmount();
        $paymentType = $accountObj->getPaymentType()->getId();
        $agencyId = $accountObj->getAgency()->getId();
        $accountType = $accountObj->getAccountType()->getId();
        $createdBy = $accountObj->getCreatedBy()->getId();

        if (isset($name) && $name != '') {
            $account->setName($name);
        }
        if (isset($cityId) && $cityId != '') {
            $account->setCityId($cityId);
        }
        if (isset($address) && $address != '') {
            $account->setAddress($address);
        }
        if (isset($mobile) && $mobile != '') {
            $account->setMobile($mobile);
        }
        if (isset($phone1) && $phone1 != '') {
            $account->setPhone1($phone1);
        }
        if (isset($phone2) && $phone2 != '') {
            $account->setPhone2($phone2);
        }
        if (isset($creditLimit) && $creditLimit != '') {
            $account->setCreditLimit($creditLimit);
        }
        if (isset($numberOfUsers) && $numberOfUsers != '') {
            $account->setNumberOfUsers($numberOfUsers);
        }
        if (isset($paymentPeriod) && $paymentPeriod != '') {
            $account->setPaymentPeriod($paymentPeriod);
        }
        if (isset($currencyCode) && $currencyCode != '') {
            $account->setCurrencyCode($currencyCode);
        }
        if (isset($website) && $website != '') {
            $account->setWebsite($website);
        }
        if (isset($email) && $email != '') {
            $account->setEmail($email);
        }
        if (isset($accountingEmail) && $accountingEmail != '') {
            $account->setAccountingEmail($accountingEmail);
        }
        if (isset($preferredCurrencyCode) && $preferredCurrencyCode != '') {
            $account->setPreferredCurrency($preferredCurrencyCode);
        }
        $account->setIsActive($isActive);
        $account->setShowAccountCurrencyAmount($showCurrencyAmount);
        if (isset($paymentType) && $paymentType != '') {
            $account->setPaymentTypeId($paymentType);
        }
        if (isset($agencyId) && $agencyId != '') {
            $account->setAgencyId($agencyId);
        }
        if (isset($accountType) && $accountType != '') {
            $account->setAccountTypeId($accountType);
        }
        if (isset($createdBy) && $createdBy != '') {
            $account->setCreatedBy($createdBy);
        }
        if (isset($parentId) && $parentId != '') {
            $account->setParentAccountId($parentId);
        }

        $account->setCreatedAt(new \DateTime("now"));

        $this->em->persist($account);
        $this->em->flush();
        if ($account) {
            return $account->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will update an account 
     * @param $accountObj
     * @return doctrine object result of account or false in case of no data
     * */
    public function updateAccount($accountObj)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->update('CorporateBundle:CorpoAccount', 'ca');

        $id = $accountObj->getId();
        $name = $accountObj->getName();
        $cityId = $accountObj->getCity()->getId();
        $address = $accountObj->getAddress();
        $mobile = $accountObj->getMobile();
        $phone1 = $accountObj->getPhone1();
        $phone2 = $accountObj->getPhone2();
        $creditLimit = $accountObj->getCreditLimit();
        $numberOfUsers = $accountObj->getNumberOfUsers();
        $paymentPeriod = $accountObj->getPaymentPeriod();
        $currencyCode = $accountObj->getCurrency()->getCode();
        $preferredCurrencyCode = $accountObj->getPreferredCurrency()->getCode();
        $website = $accountObj->getWebsite();
        $email = $accountObj->getEmail();
        $accountingEmail = $accountObj->getAccountingEmail();
        $parentId = $accountObj->getParentId();
        $isActive = $accountObj->getIsActive();
        $showCurrencyAmount = $accountObj->getShowAccountCurrencyAmount();
        $paymentType = $accountObj->getPaymentType()->getId();
        $agencyId = $accountObj->getAgency()->getId();
        $accountType = $accountObj->getAccountType()->getId();
        $updatedBy = $accountObj->getUpdatedBy()->getId();

        if (isset($name) && $name != '') {
            $qb->set("ca.name", ":name")
                ->setParameter(':name', $name);
        }
        if (isset($cityId) && $cityId != '') {
            $qb->set("ca.cityId", ":cityId")
                ->setParameter(':cityId', $cityId);
        }
        if (isset($address) && $address != '') {
            $qb->set("ca.address", ":address")
                ->setParameter(':address', $address);
        }
        if (isset($mobile) && $mobile != '') {
            $qb->set("ca.mobile", ":mobile")
                ->setParameter(':mobile', $mobile);
        }
        if (isset($phone1) && $phone1 != '') {
            $qb->set("ca.phone1", ":phone1")
                ->setParameter(':phone1', $phone1);
        }
        if (isset($phone2) && $phone2 != '') {
            $qb->set("ca.phone2", ":phone2")
                ->setParameter(':phone2', $phone2);
        }
        if (isset($creditLimit) && $creditLimit != '') {
            $qb->set("ca.creditLimit", ":creditLimit")
                ->setParameter(':creditLimit', $creditLimit);
        }
        if (isset($numberOfUsers) && $numberOfUsers != '') {
            $qb->set("ca.numberOfUsers", ":numberOfUsers")
                ->setParameter(':numberOfUsers', $numberOfUsers);
        }
        if (isset($paymentPeriod) && $paymentPeriod != '') {
            $qb->set("ca.paymentPeriod", ":paymentPeriod")
                ->setParameter(':paymentPeriod', $paymentPeriod);
        }
        if (isset($currencyCode) && $currencyCode != '') {
            $qb->set("ca.currencyCode", ":currencyCode")
                ->setParameter(':currencyCode', $currencyCode);
        }
        if (isset($preferredCurrencyCode) && $preferredCurrencyCode != '') {
            $qb->set("ca.preferredCurrency", ":preferredCurrency")
                ->setParameter(':preferredCurrency', $preferredCurrencyCode);
        }
        if (isset($website) && $website != '') {
            $qb->set("ca.website", ":website")
                ->setParameter(':website', $website);
        }
        if (isset($email) && $email != '') {
            $qb->set("ca.email", ":email")
                ->setParameter(':email', $email);
        }
        if (isset($accountingEmail) && $accountingEmail != '') {
            $qb->set("ca.accountingEmail", ":accountingEmail")
                ->setParameter(':accountingEmail', $accountingEmail);
        }
        if (isset($parentId) && $parentId != '') {
            $qb->set("ca.parentAccountId", ":parentAccountId")
                ->setParameter(':parentAccountId', $parentId);
        }
        $qb->set("ca.isActive", ":isActive")
                ->setParameter(':isActive', $isActive);
        $qb->set("ca.showAccountCurrencyAmount", ":showAccountCurrencyAmount")
                ->setParameter(':showAccountCurrencyAmount', $showCurrencyAmount);
        if (isset($paymentType) && $paymentType != '') {
            $qb->set("ca.paymentTypeId", ":paymentType")
                ->setParameter(':paymentType', $paymentType);
        }
        if (isset($agencyId) && $agencyId != '') {
            $qb->set("ca.agencyId", ":agencyId")
                ->setParameter(':agencyId', $agencyId);
        }
        if (isset($accountType) && $accountType != '') {
            $qb->set("ca.accountTypeId", ":accountTypeId")
                ->setParameter(':accountTypeId', $accountType);
        }
        if (isset($updatedBy) && $updatedBy != '') {
            $qb->set("ca.updatedBy", ":updatedBy")
                ->setParameter(':updatedBy', $updatedBy);
        }
        if (isset($parameters['accountType']) && $parameters['accountType'] != '') {
            $qb->set("ca.accountTypeId", ":accountTypeId")
                ->setParameter(':accountTypeId', $parameters['accountType']);
        }

        $qb->set("ca.updatedAt", ":updatedAt")
            ->where("ca.id=:Id")
            ->setParameter(':updatedAt', new \DateTime("now"))
            ->setParameter(':Id', $id);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /**
     * This method will delete an account
     * @param id of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function deleteAccount($id)
    {
        try {
            $this->em = $this->getEntityManager();
            $qb       = $this->em->createQueryBuilder('ca')
                ->delete('CorporateBundle:CorpoAccount', 'ca')
                ->where("ca.id = :ID")
                ->setParameter(':ID', $id);
            $query    = $qb->getQuery();
            return $query->getResult();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getAccountCombo($tt_search_critiria_obj, $addWhere)
    {
        $em = $this->getEntityManager();

        $searchTerm = $tt_search_critiria_obj->getTerm();
        $page       = $tt_search_critiria_obj->getPage();
        $limit      = $tt_search_critiria_obj->getLimit();
        $sortOrder  = $tt_search_critiria_obj->getSortOrder();
        $start      = $tt_search_critiria_obj->getStart();
        $excluded   = $tt_search_critiria_obj->getExcluded();

        if(isset($excluded)) $excluded = " AND p.id NOT IN ($excluded)";
        else $excluded = "";

        $query ="SELECT count(p)  FROM CorporateBundle:CorpoAccount p WHERE p.name like :searchterm $excluded $addWhere ";

        $query_exec = $em->createQuery($query)->setParameter('searchterm', "%$searchTerm%");

        $query_res = $query_exec->getResult();

        $count = $query_res[0][1];

        if(!isset($sortOrder)) $sortOrder = " order by p.name ASC";

        $SQL = "SELECT p FROM CorporateBundle:CorpoAccount p WHERE p.name like :searchterm $excluded $addWhere " . $sortOrder;
        $query2_exec = $em->createQuery($SQL)->setParameter('searchterm', "%$searchTerm%")->setFirstResult($start)->setMaxResults($limit);

        $combogrid_cats = $query2_exec->getArrayResult();

        $result_arr["combogrid_cats"] = $combogrid_cats;
        $result_arr["count"] = $count;

        return $result_arr;
    }

    /**
     * This method will retrieve an account parent path
     * @param id of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function getPPath($parentId)
    {
        $query = $this->createQueryBuilder('al')
            ->select('al.path')
            ->where("al.id = :parentId")
            ->setParameter(':parentId', $parentId);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will update an account path
     * @param $accountId, $path
     * @return doctrine object result of account or false in case of no data
     * */
    public function updatePath($accountId, $path)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->update('CorporateBundle:CorpoAccount', 'ca');

        if (isset($path) && $path != '') {
            $qb->set("ca.path", ":path")
                ->setParameter(':path', $path);
        }

        $qb->set("ca.updatedAt", ":updatedAt")
            ->where("ca.id=:accountId")
            ->setParameter(':updatedAt', new \DateTime("now"))
            ->setParameter(':accountId', $accountId);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }
}
