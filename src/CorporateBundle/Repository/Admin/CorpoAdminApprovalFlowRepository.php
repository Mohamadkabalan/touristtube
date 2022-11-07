<?php

namespace CorporateBundle\Repository\Admin;

use CorporateBundle\Entity\CorpoApprovalFlow;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CorpoAdminApprovalFlowRepository extends EntityRepository implements ContainerAwareInterface
{
    protected $utils;
    protected $em;
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * This method will retrieve all Approval Flows of corporate
     * @param
     * @return doctrine object result of Approval Flows or false in case of no data
     * */
    public function getApprovalFlowList($accountId)
    {
        $query = $this->createQueryBuilder('ap')
            ->select('ap,a.name as accountName,u.fullname as userName, us.fullname as parentName')
            ->leftJoin('CorporateBundle:CorpoAccount', 'a', 'WITH', "a.id = ap.accountId")
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = ap.userId")
            ->leftJoin('CorporateBundle:CmsUsers', 'us', 'WITH', "us.id = ap.parentId");
        if (isset($accountId)) {
            $query->where("ap.accountId = :accountId")
                ->setParameter(':accountId', $accountId);
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve an Approval Flow of corporate
     * @param
     * @return doctrine object result an Approval Flows or false in case of no data
     * */
    public function getApprovalFlowById($accountId, $userId)
    {
        $query = $this->createQueryBuilder('p')
            ->select("p, a.name as accountName,u.fullname as userName, us.fullname as parentName,uss.fullname as otherUserName")
            ->leftJoin('CorporateBundle:CorpoAccount', 'a', 'WITH', "a.id = p.accountId")
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = p.userId")
            ->leftJoin('CorporateBundle:CmsUsers', 'us', 'WITH', "us.id = p.parentId")
            ->leftJoin('CorporateBundle:CmsUsers', 'uss', 'WITH', "us.id = p.otherUserId")
            ->where("p.accountId = :accountId")
            ->andwhere("p.userId = :userId")
            ->setParameter(':accountId', $accountId)
            ->setParameter(':userId', $userId);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve an Approval Flow of corporate
     * @param
     * @return doctrine object result an Approval Flows or false in case of no data
     * */
    public function getApprovalFlowByUserId($id, $accountId)
    {
        $query = $this->createQueryBuilder('p')
            ->select("p, a.name as accountName, u.fullname as userName")
            ->leftJoin('CorporateBundle:CorpoAccount', 'a', 'WITH', "a.id = p.accountId")
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = p.userId")
            ->where("p.userId = :ID")
            ->andwhere("p.accountId = :accountId")
            ->setParameter(':ID', $id)
            ->setParameter(':accountId', $accountId);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will get the path of Adjacent Parent Menu
     * @param parentId
     * @return array of parentIds
     * */
    public function getApprovalFlowPath($parentId)
    {
        $query = $this->createQueryBuilder('p')
            ->select("p")
            ->where("p.userId = :parentId")
            ->setParameter(':parentId', $parentId);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            $path = [];
            if ($result[0]['p_path']) {
                $path = substr($result[0]['p_path'], 1, strlen($result[0]['p_path']) - 2);
                $path = explode(',', $path);
            }
            return $path;
        } else {
            return array();
        }
    }

    /**
     * This method will get the path of selected user
     * @param $accountId, $userId
     * @return string path
     * */
    public function getUserPath($accountId, $userId)
    {
        $query = $this->createQueryBuilder('p')
            ->select("p.path")
            ->where("p.accountId = :acctId")
            ->andWhere("p.userId = :userId")
            ->setParameter(':acctId', $accountId)
            ->setParameter(':userId', $userId);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0]['path'];
        } else {
            return false;
        }
    }

    /**
     * This method will get the user info
     * @param $accountId, $userId
     * @return int parentId
     * */
    public function getUserInfo($accountId, $userId)
    {
        $query = $this->createQueryBuilder('caf')
            ->select("caf.path, caf.parentId, COUNT(cafh.id) as childCnt, cafp.path as newPath")
            ->leftJoin('CorporateBundle:CorpoApprovalFlow', 'cafh', 'WITH', "cafh.parentId = caf.userId")
            ->leftJoin('CorporateBundle:CorpoApprovalFlow', 'cafp', 'WITH', "cafp.userId = caf.parentId")
            ->where("caf.accountId = :acctId")
            ->andWhere("caf.userId = :userId")
            ->setParameter(':acctId', $accountId)
            ->setParameter(':userId', $userId);

        $quer   = $query->getQuery();
        $result = $quer->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * This method will update a child path
     * @param $path $newPath
     * @return doctrine object result of Approval Flow or false in case of no data
     * */
    public function updateUserPath($pPath, $newPath)
    {   
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("
            UPDATE CorporateBundle:CorpoApprovalFlow caf
            SET caf.path = REPLACE(caf.path, :pPath, :newPath)
            WHERE caf.path LIKE :like
        ")->setParameter('pPath', $pPath)
        ->setParameter('newPath', $newPath)
        ->setParameter('like', $pPath . '%');

        $result = $query->getResult();
        
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will get the path of first found main parent user in the same account
     * @param $accountId
     * @return array
     * */
    public function getAccountPath($accountId)
    {
        $query = $this->createQueryBuilder('p')
            ->select("p.path, p.userId")
            ->where("p.accountId = :acctId")
            ->andWhere("p.parentId IS NULL")
            ->andWhere("p.path IS NOT NULL")
            ->setParameter(':acctId', $accountId)
            ->orderBy('p.id');

        $quer   = $query->getQuery();
        $result = $quer->getResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * This method will update a child path
     * @param $newParentId, $curParentId
     * @return doctrine object result of Approval Flow or false in case of no data
     * */
    public function setNewParent($newParentId, $curParentId)
    {   
        $qb  = $this->em->createQueryBuilder('p')
            ->update('CorporateBundle:CorpoApprovalFlow', 'p');
        $qb->set("p.parentId", ":newParentId")
            ->setParameter(':newParentId', $newParentId);
        $qb->where("p.parentId = :curParentId")
            ->setParameter(':curParentId', $curParentId);

        $query    = $qb->getQuery();
        $result = $query->getResult();

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * This method will add an Approval Flow for corporate Approval Flow
     * @param Approval Flow info list
     * @return doctrine result of Approval Flow id or false in case of no data
     * */
    public function addApprovalFlow($parameters, $approvalFlowParent, $approvalFlowRoot, $approvalFlowUser, $approveAllUsers)
    {
        $this->em     = $this->getEntityManager();
        $approvalFlow = new CorpoApprovalFlow();

        if (isset($parameters['accountId']) && $parameters['accountId'] != '') {
            $approvalFlow->setAccountId($parameters['accountId']);
        }
        if (isset($parameters['parentId']) && $parameters['parentId'] != '') {
            $approvalFlow->setParentId($parameters['parentId']);
            $path = $this->getApprovalFlowPath($parameters['parentId']);
            $path = ','.(count($path) ? (implode(',', $path).',') : '').$parameters['userId'].',';
        } else {
            $path = $parameters['userId'];
        }

        if (isset($parameters['userId']) && $parameters['userId'] != '') {
            $approvalFlow->setUserId($parameters['userId']);
        }
        if ($approvalFlowParent == 1 || $approvalFlowParent == 0) {
            $approvalFlow->setApprovalFlowParent($approvalFlowParent);
        }
        if ($approvalFlowRoot == 1 || $approvalFlowRoot == 0) {
            $approvalFlow->setApprovalFlowRoot($approvalFlowRoot);
        }
        if ($approvalFlowUser == 1 || $approvalFlowUser == 0) {
            $approvalFlow->setApprovalFlowUser($approvalFlowUser);
        }
        if ($approveAllUsers == 1 || $approveAllUsers == 0) {
            $approvalFlow->setApproveAllUsers($approveAllUsers);
        }
        if (isset($parameters['mainUserId']) && $parameters['mainUserId'] != '' ) {
            $approvalFlow->setMainUserId($parameters['mainUserId']);
        }
        if (isset($parameters['otherUserId']) && $parameters['otherUserId'] != '' ) {
            $approvalFlow->setOtherUserId($parameters['otherUserId']);
        }

        $approvalFlow->setPath($path);

        $this->em->persist($approvalFlow);
        $this->em->flush();
        if ($approvalFlow) {
            return $approvalFlow->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will update an Approval Flow
     * @param id of Approval Flow
     * @return doctrine object result of Approval Flow or false in case of no data
     * */
    public function updateApprovalFlow($parameters, $approvalFlowParent, $approvalFlowRoot, $approvalFlowUser, $approveAllUsers)
    {
        $this->em = $this->getEntityManager();

        $path = $this->getApprovalFlowPath($parameters['parentId']);
        $path = ','.(count($path) ? (implode(',', $path).',') : '').$parameters['userId'].',';

        $qb       = $this->em->createQueryBuilder('p')
            ->update('CorporateBundle:CorpoApprovalFlow', 'p');
        if (isset($parameters['accountId']) && $parameters['accountId'] != '') {
            $qb->set("p.accountId", ":accountId")
                ->setParameter(':accountId', $parameters['accountId']);
        }
        if (isset($parameters['parentId']) && $parameters['parentId'] != '') {
            $qb->set("p.parentId", ":parentId")
                ->setParameter(':parentId', $parameters['parentId']);
        }
        if (isset($parameters['userId']) && $parameters['userId'] != '') {
            $qb->set("p.userId", ":userId")
                ->setParameter(':userId', $parameters['userId']);
        }
        if ($approvalFlowParent == 1 || $approvalFlowParent == 0) {
            $qb->set("p.approvalFlowParent", ":approvalFlowParent")
                ->setParameter(':approvalFlowParent', $approvalFlowParent);
        }
        if ($approvalFlowRoot == 1 || $approvalFlowRoot == 0) {
            $qb->set("p.approvalFlowRoot", ":approvalFlowRoot")
                ->setParameter(':approvalFlowRoot', $approvalFlowRoot);
        }
        if ($approvalFlowUser == 1 || $approvalFlowUser == 0) {
            $qb->set("p.approvalFlowUser", ":approvalFlowUser")
                ->setParameter(':approvalFlowUser', $approvalFlowUser);
        }
        if ($approveAllUsers == 1 || $approveAllUsers == 0) {
            $qb->set("p.approveAllUsers", ":approveAllUsers")
                ->setParameter(':approveAllUsers', $approveAllUsers);
        }
        if (isset($parameters['mainUserId']) && $parameters['mainUserId'] != '') {
            $qb->set("p.mainUserId", ":mainUserId")
                ->setParameter(':mainUserId', $parameters['mainUserId']);
        }
        if (isset($parameters['otherUserId']) && $parameters['otherUserId'] != '') {
            $qb->set("p.otherUserId", ":otherUserId")
                ->setParameter(':otherUserId', $parameters['otherUserId']);
        }

        $qb->set("p.path", ":path")
                ->setParameter(':path', $path);
        
        $qb->where("p.id=:Id")
            ->setParameter(':Id', $parameters['approvalFlowId']);
        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /**
     * This method will update an Approval Flow by the parent ID
     * @param id of Approval Flow
     * @return doctrine object result of Approval Flow or false in case of no data
     * */
    public function updateParentApprovalFlow($parameters)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->update('CorporateBundle:CorpoApprovalFlow', 'p');
        $qb->set("p.parentId", ":parentId")
            ->setParameter(':parentId', $parameters['parentId']);
        $qb->where("p.parentId=:Id")
            ->setParameter(':Id', $parameters['id']);
        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /**
     * This method will delete an Approval Flow
     * @param id of Approval Flow
     * @return doctrine object result of Approval Flow or false in case of no data
     * */
    public function deleteApprovalFlow($accountId, $userId)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->delete('CorporateBundle:CorpoApprovalFlow', 'p')
            ->where("p.accountId = :accountId")
            ->andwhere("p.userId = :userId")
            ->setParameter(':accountId', $accountId)
            ->setParameter(':userId', intval($userId));
        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will delete user and all its children
     * @param $pPath - parent path
     * @return doctrine object result of Approval Flow or false in case of no data
     * */
    public function deleteApprovalUser($pPath)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p');
        $query = $qb->delete('CorporateBundle:CorpoApprovalFlow', 'p')
            ->where(
                $qb->expr()->like('p.path', ':pPath')
            )
            ->setParameter(':pPath', $pPath . '%')
            ->getQuery();
            
        return $query->getResult();
    }

    /**
     * This method will retrieve Approval Flow Status of corporate
     * @param
     * @return doctrine object result of Approval Flow Status or false in case of no data
     * */
    public function getApprovalFlowAllStatus()
    {
        $query = $this->createQueryBuilder('ap')
            ->select('ap')
            ->orderBy('ap.sortOrder');
        
        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve Approval Flow Status of corporate
     * @param
     * @return doctrine object result of Approval Flow Status or false in case of no data
     * */
    public function getApprovalFlowStatus()
    {
        $query = $this->createQueryBuilder('ap')
            ->select('ap.id')
            ->orderBy('ap.sortOrder')
            ->setMaxResults(1);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will Check User Permision in approval flow
     * @param
     * @return doctrine object result of Approval Flow id or false in case of no data
     * */
    public function userAllowedToApprove($userId, $accountId)
    {

        $query = $this->createQueryBuilder('ap')
            ->select('ap.id,ap.approvalFlowUser')
            ->where("ap.accountId = :accountId")
            ->andwhere("ap.userId = :userId")
            ->setParameter(':accountId', $accountId)
            ->setParameter(':userId', $userId);

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * getting filtered approval flow list
     *
     * @return array $results
     */
    public function getALlApprovalFlowList($parameters)
    {
        $sql               = '';
        $userIdSql         = '';
        $currencySql       = '';
        $dateFromHotelSql  = '';
        $dateFromDealSql   = '';
        $dateFromFlightSql = '';
        $dateToHotelSql    = '';
        $dateToDealSql     = '';
        $dateToFlightSql   = '';
        $dateToSql         = '';
        $hotelSql          = '';
        $dealSql           = '';
        $flightSql         = '';
        $addFilter         = '';
        $accountTypeSQL    = '';
        $accountNameSQL    = '';
        $statusList        = '';
        $accPathSql        = '';
        $addWhere          = '';
        $orderOfDisplay    = 'DESC';

        if (isset($parameters['statuses'])){
            $statusList = implode(",", $parameters['statuses']);
        }
        
        if (isset($parameters['statusPendingValue']) && isset($parameters['statuses']) && in_array($parameters['statusPendingValue'], $parameters['statuses'])) {
            $orderOfDisplay = 'ASC';
        }

        $conn = $this->getEntityManager()->getConnection();

        if (isset($parameters['userId']) && $parameters['userId'] != '') {
            $userIdSql = " AND rqd.user_id = '".$parameters['userId']."'";
        }

        if (isset($parameters['currencyCode']) && $parameters['currencyCode'] != '') {
            $currencySql = " AND rqd.currency_code = '".$parameters['currencyCode']."'";
        }

        if (isset($parameters['typeOfAccount']) && $parameters['typeOfAccount'] != '') {
            $accountTypeSQL = " AND ca.account_type_id = '".$parameters['typeOfAccount']."'";
        }

        if (isset($parameters['nameOfAccount']) && $parameters['nameOfAccount'] != '') {
            $accountNameSQL = " AND ca.name = '".$parameters['nameOfAccount']."'";
        }

        if (isset($parameters['fromDate']) && $parameters['fromDate'] != '') {
            $dateFromHotelSql = " AND date(hr.from_date) >= date('".$parameters['fromDate']."')";
            if ($this->container->getParameter('SHOW_DEALS_BLOCK') == 1) {
                $dateFromDealSql = " AND date(db.booking_date) >= date('".$parameters['fromDate']."')";
            }
            //flights has datetime Type, need to reset the time to zero time/hour
            $flightFromDate = sprintf("%s %s",
                $parameters['fromDate'],
                ' 00:00:00'
            );
            $dateFromFlightSql = " AND date(q.departure_datetime) >= date('".$flightFromDate."')";
        }

        if (isset($parameters['toDate']) && $parameters['toDate'] != '') {
            $dateToHotelSql = " AND date(hr.to_date) <= date('".$parameters['toDate']."')";
            if ($this->container->getParameter('SHOW_DEALS_BLOCK') == 1) {
                $dateToDealSql = " AND date(db.booking_date) <= date('".$parameters['toDate']."')";
            }
            //flights has datetime Type, need to reset the time to last hour/minute of the day
            $flightToDate = sprintf("%s %s",
                $parameters['toDate'],
                ' 23:59:59'
            );
            $dateToFlightSql = " AND date(fd_last.arrival_datetime) <= date('".$flightToDate."')";
        }

        if(isset($parameters['createdFrom']) && $parameters['createdFrom'] != '') {
            $addWhere .= " AND date(rqd.created_at) >= date('".$parameters['createdFrom']."')";
        }
        if(isset($parameters['createdTo']) && $parameters['createdTo'] != '') {
            $addWhere .= " AND date(rqd.created_at) <= date('".$parameters['createdTo']."')";
        }

        if(isset($parameters['sessionAccountId']) && $parameters['sessionAccountId'] != '') {
            $sessAccountId = $parameters['sessionAccountId'];
            $accPathSql .= " AND ca.path LIKE '%,$sessAccountId,%'";
        }

        $hotelSql = 'SELECT
                        '.$this->container->getParameter('MODULE_HOTELS').' AS moduleId,
                        md.name AS "moduleName",
                        rqd.id AS "requestServicesDetailsId",
                        cu.FullName AS "userName",
                        cu.id AS "userId",
                        rqd.status AS status,
                        st.name AS statusName,
                        rqd.user_id AS "transactionUserId",
                        rqd.account_id AS "accountId",
                        rqd.created_at AS "transactionDate",
                        ca.name AS accountName,
                        hr.id AS "reservationId",
                        hr.control_number AS "controlNumber",
                        hr.reservation_process_password as "reservationProcessPassword",
                        hr.reservation_process_key as "reservationProcessKey",
                        ah.property_name AS "name",
                        ahc.city_name AS "city",
                        ahc.country_name AS "country",
                        CONCAT_WS(", ", ah.address_line_1, ah.address_line_2) AS "address",
                        hr.from_date AS "fromdate",
                        hr.to_date AS "todate",
                        null AS "starttime",
                        null AS "endtime",
                        ca.preferred_currency AS "currency",
                        rqd.amount_fbc AS "amount_fbc",
                        rqd.amount_sbc AS "amount_sbc",
                        rqd.account_currency_amount AS "amount",
                        ahi.dupe_pool_id AS dupePoolId,
                        ahi.location AS location,
                        ahi.filename AS filename,
                        "" AS "apiId",
                        "" AS "dealCode",
                        "" AS "categoryName",
                        "" AS "cityName",
                        "" AS "imageId",
                        JSON_OBJECT("reference", hr.reference) AS "details"
                FROM corpo_request_services_details rqd
                LEFT JOIN hotel_reservation hr ON (rqd.reservation_id = hr.id AND rqd.module_id = '.$this->container->getParameter('MODULE_HOTELS').')
                INNER JOIN amadeus_hotel ah ON hr.hotel_id = ah.id
                INNER JOIN amadeus_hotel_city ahc ON ah.amadeus_city_id = ahc.id
                LEFT JOIN amadeus_hotel_image ahi ON ahi.hotel_id = hr.hotel_id AND ahi.tt_media_type_id = 1 AND ahi.default_pic = 1 AND ahi.id = (SELECT MIN(id) FROM amadeus_hotel_image sahi WHERE sahi.hotel_id = hr.hotel_id AND sahi.tt_media_type_id = 1 AND sahi.default_pic = 1)
                LEFT JOIN cms_users cu ON cu.id = rqd.user_id
                LEFT JOIN corpo_account ca ON ca.id = rqd.account_id
                LEFT JOIN corpo_account_type ct ON ca.account_type_id = ct.id
                LEFT JOIN corpo_approval_flow_status st ON rqd.status = st.id
                LEFT JOIN tt_modules md ON rqd.module_id = md.id
                WHERE rqd.module_id = '.$this->container->getParameter('MODULE_HOTELS').'
                  AND rqd.account_id = '.$parameters['accountId'] . " $accPathSql $addWhere ";

        if (isset($parameters['statuses'])) {
            $hotelSql .= 'AND rqd.status IN ('.$statusList.')';
        }

        if ($this->container->getParameter('SHOW_DEALS_BLOCK') == 1) {
            $dealSql = 'SELECT
                        '.$this->container->getParameter('MODULE_DEALS').' AS "moduleId",
                        md.name AS "moduleName",
                        rqd.id AS "requestServicesDetailsId",
                        cu.FullName AS "userName",
                        cu.id AS "userId",
                        rqd.status AS "status",
                        st.name AS statusName,
                        rqd.user_id AS "transactionUserId",
                        rqd.account_id AS "accountId",
                        rqd.created_at AS "transactionDate",
                        ca.name AS accountName,
                        db.id AS "reservationId",
                        "" AS "controlNumber",
                        "" AS "reservationProcessPassword",
                        "" AS "reservationProcessKey",
                        db.deal_name AS "name",
                        dc.city_name AS "city",
                        dct.country_name AS "country",
                        db.address AS "address",
                        db.booking_date AS "fromdate",
                        db.booking_date AS "todate",
                        db.start_time AS "starttime",
                        db.end_time AS "endtime",
                        ca.preferred_currency AS "currency",
                        rqd.amount_fbc AS "amount_fbc",
                        rqd.amount_sbc AS "amount_sbc",
                        rqd.account_currency_amount AS "amount",
                        "" AS dupePoolId,
                        "" AS location,
                        "" AS filename,
                        dd.deal_api_id AS "apiId",
                        dd.deal_code AS "dealCode",
                        dcat.name AS "categoryName",
                        dc.city_name AS "cityName",
                        di.id AS "imageId",
                        ""  AS "details"
                FROM corpo_request_services_details rqd
                        INNER JOIN deal_booking db ON (rqd.reservation_id = db.id AND rqd.module_id = '.$this->container->getParameter('MODULE_DEALS').')
                        INNER JOIN deal_city dc ON (db.deal_city_id = dc.id)
                        INNER JOIN deal_country dct ON (db.country_id = dct.id)
                        LEFT JOIN deal_details dd ON db.deal_details_id = dd.id
                        LEFT JOIN deal_detail_to_category ddtc ON ddtc.deal_details_id = dd.id
                        LEFT JOIN deal_category dcat ON  dcat.api_category_id = ddtc.deal_category_id
                        LEFT JOIN deal_image di ON di.deal_detail_id = dd.id
                        LEFT JOIN cms_users cu ON cu.id = rqd.user_id
                        LEFT JOIN corpo_account ca ON ca.id = rqd.account_id
                        LEFT JOIN corpo_account_type ct ON ca.account_type_id = ct.id
                        LEFT JOIN corpo_approval_flow_status st ON rqd.status = st.id
                        LEFT JOIN tt_modules md ON rqd.module_id = md.id
                WHERE rqd.module_id = '.$this->container->getParameter('MODULE_DEALS').'
                  AND rqd.account_id = '.$parameters['accountId'] . " $accPathSql $addWhere ";

            if (isset($parameters['statuses'])) {
                $dealSql .= 'AND rqd.status IN ('.$statusList.')';
            }
        }

        $flightSql = 'SELECT
                        '.$this->container->getParameter('MODULE_FLIGHTS').' AS "moduleId",
                        md.name AS "moduleName",
                        rqd.id AS "requestServicesDetailsId",
                        cu.FullName AS "userName",
                        cu.id AS "userId",
                        rqd.status AS "status",
                        st.name AS statusName,
                        rqd.user_id AS transactionUserId,
                        rqd.account_id AS accountId,
                        rqd.created_at AS "transactionDate",
                        ca.name AS accountName,
                        q.pnr_id AS "reservationId",
                        "" AS "controlNumber",
                        "" AS "reservationProcessPassword",
                        "" AS "reservationProcessKey",
                        al.name AS "name",
                        "" AS "city",
                        q.country_of_residence AS "country",
                        "" AS "address",
                        q.departure_datetime AS "fromdate",
                         fd_last.arrival_datetime AS "todate",
                        "" AS "starttime",
                        "" AS "endtime",
                        ca.preferred_currency AS "currency",
                        rqd.amount_fbc AS "amount_fbc",
                        rqd.amount_sbc AS "amount_sbc",
                        rqd.account_currency_amount AS "amount",
                        "" AS dupePoolId,
                        "" AS location,
                        "" AS filename,
                        "" AS "apiId",
                        "" AS "dealCode",
                        "" AS "categoryName",
                        "" AS "cityName",
                        "" AS "imageId",
                        JSON_OBJECT(
                                "logo", al.logo,
                                "departureAirport" ,q.departure_airport,
                                "arrivalAirport" ,( IF((fi.one_way=1 and fi.multi_destination=0), fd_last.arrival_airport, fd_last.departure_airport) ),
                                "departureAirportName" ,dar.name,
                                "arrivalAirportName" ,aar.name,
                                "airline", q.airline,
                                "flightNumber", q.flight_number,
                                "nPassengers", q.n_passengers,
                                "nStops", q.n_stops,
                                "refundable",  if(fi.multi_destination = 1,"refundable","non-refundable"),
                                "flightType", if(fi.multi_destination = 1,"multiple destination",if(fi.one_way=1,"one way","round trip"))
                            ) AS "details"
                FROM (SELECT
                    pnr.id AS pnr_id,
                    fd_first.airline,
                    fd_first.flight_number,
                    fd_first.departure_datetime,
                    fd_first.departure_airport,
                    ( IF((fi.one_way=1 and fi.multi_destination=0), fd_last.arrival_airport, fd_last.departure_airport) )as arrival_airport,
                    MAX(fd_last.segment_number) AS last_sn,
                    COUNT(DISTINCT pd.id) AS n_passengers, COUNT(DISTINCT fd.id) AS n_stops,
                    pnr.country_of_residence as country_of_residence
                        FROM passenger_name_record pnr
                        INNER JOIN flight_detail fd_first ON (fd_first.pnr_id = pnr.id  AND fd_first.segment_number = 1)
                        INNER JOIN flight_detail fd_last ON (fd_last.pnr_id = pnr.id)
                        INNER JOIN flight_info fi ON (fi.pnr_id = pnr.id)
                        LEFT JOIN flight_detail fd ON (fd.pnr_id = pnr.id AND fd.stop_indicator = 1)
                        INNER JOIN passenger_detail pd ON (pd.pnr_id = pnr.id)
                        WHERE pnr.is_corporate_site = 1 AND pnr.status = "SUCCESS"
                        GROUP BY pnr.id) q
                INNER JOIN corpo_request_services_details rqd  ON (q.pnr_id=rqd.reservation_id AND rqd.module_id = '.$this->container->getParameter('MODULE_FLIGHTS').')
                INNER JOIN flight_detail fd_last ON (fd_last.pnr_id = q.pnr_id AND fd_last.segment_number = q.last_sn)
                INNER JOIN flight_info fi ON (fi.pnr_id = q.pnr_id)
                INNER JOIN airline AS al ON q.airline = al.code
                INNER JOIN airport AS dar ON q.departure_airport = dar.airport_code
                INNER JOIN airport AS aar ON q.arrival_airport = aar.airport_code
                LEFT JOIN cms_users cu ON cu.id = rqd.user_id
                LEFT JOIN corpo_account ca ON ca.id = rqd.account_id
                LEFT JOIN corpo_account_type ct ON ca.account_type_id = ct.id
                LEFT JOIN corpo_approval_flow_status st ON rqd.status = st.id
                LEFT JOIN tt_modules md ON rqd.module_id = md.id
                WHERE  rqd.module_id = '.$this->container->getParameter('MODULE_FLIGHTS').'
                  AND rqd.account_id = '.$parameters['accountId'] . " $accPathSql $addWhere ";

        if (isset($parameters['statuses'])) {
            $flightSql .= 'AND rqd.status IN ('.$statusList.')';
        }

        if (isset($parameters['count']) && $parameters['count'] != '') {
            $sql .= 'SELECT count(1) FROM( ';
        } elseif(isset($parameters['sumAmount']) && $parameters['sumAmount'] != '') {
            $sql .= 'SELECT SUM(sumApp.amount) totalAmt FROM( ';
        }

        /* additional filter */
        if (!empty($parameters['created_by'])) {
            $addFilter .= " AND LOWER(cu.FullName) LIKE '%".strtolower($parameters['created_by'])."%' ";
        }

        if (!empty($parameters['created_at'])) {
            $addFilter .= " AND DATE(rqd.created_at) = '".$parameters['created_at']."'";
        }

        if (isset($parameters['types']) && $parameters['types'] != 0) {
            if ($parameters['types'] == $this->container->getParameter('MODULE_HOTELS')) {
                $sql .= $hotelSql.$userIdSql.$currencySql.$accountTypeSQL.$accountNameSQL.$dateFromHotelSql.$dateToHotelSql;
            } elseif ($parameters['types'] == $this->container->getParameter('MODULE_DEALS') && $this->container->getParameter('SHOW_DEALS_BLOCK') == 1) {
                $sql .= $dealSql.$userIdSql.$currencySql.$accountTypeSQL.$accountNameSQL.$dateFromDealSql.$dateToDealSql;
            } elseif ($parameters['types'] == $this->container->getParameter('MODULE_FLIGHTS') ) {
                $sql .= $flightSql.$userIdSql.$currencySql.$accountTypeSQL.$accountNameSQL.$dateFromFlightSql.$dateToFlightSql.$addFilter;
            }
        } else {
            $sql .= $hotelSql.$userIdSql.$currencySql.$dateFromHotelSql.$dateToHotelSql;
            if ($this->container->getParameter('SHOW_DEALS_BLOCK') == 1) {
                $sql .= ' UNION '.$dealSql.$userIdSql.$currencySql.$accountTypeSQL.$accountNameSQL.$dateFromDealSql.$dateToDealSql;
            }
            
            $sql .= ' UNION '.$flightSql.$userIdSql.$currencySql.$accountTypeSQL.$accountNameSQL.$dateFromFlightSql.$dateToFlightSql;
        }

        if (isset($parameters['count']) && $parameters['count'] != '') {
            $sql .= ') as countApp ORDER BY transactionDate '.$orderOfDisplay;
        } elseif(isset($parameters['sumAmount']) && $parameters['sumAmount'] != '') {
            $sql .= ') as sumApp ORDER BY transactionDate '.$orderOfDisplay;
        } else {
            if (!isset($parameters['start']) && !isset($parameters['limit'])) {
                $sql .= ' ORDER BY transactionDate '.$orderOfDisplay;
            } else {
                $sql .= ' ORDER BY transactionDate '.$orderOfDisplay.'
                          LIMIT '.$parameters['start'].','.$parameters['limit'];
            }
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();

        if (!empty($result) && isset($result)) {
            if (isset($parameters['count']) && $parameters['count'] != '') {
                return $result[0]['count(1)'];
            } else {
                return $result;
            }
        } else {
            return array();
        }
    }

    /**
     * This method will allow user approval
     * @param id of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function allow($id, $allowed)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('caf')
            ->update('CorporateBundle:CorpoApprovalFlow', 'caf')
            ->set("caf.approvalFlowUser", ":allowed")
            ->set("caf.approveAllUsers", ":allowed")
            ->where("caf.userId = :ID")
            ->setParameter(':allowed', $allowed)
            ->setParameter(':ID', $id);

        $query    = $qb->getQuery();
        return $query->getResult();
    }
}
