<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoRequestServices;
use CorporateBundle\Entity\CorpoRequestServicesDetails;

class CorpoRequestServicesRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all Request Services of corporate
     * @param
     * @return doctrine object result of Request Services or false in case of no data
     * */
    public function getRequestServicesList($accountId)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p,ca.name as accountName, cc.name as countryName, w.name as destinationCityName, w2.name as departureCityName')
            ->leftJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', "ca.id = p.accountId")
            ->leftJoin('CorporateBundle:CmsCountries', 'cc', 'WITH', "cc.id = p.countryId")
            ->leftJoin('CorporateBundle:Webgeocities', 'w', 'WITH', "w.id = p.destinationCityId")
            ->leftJoin('CorporateBundle:Webgeocities', 'w2', 'WITH', "w2.id = p.departureCityId");
        if ($accountId) {
            $query->where("p.id=:accountId")
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
     * This method will retrieve all Request Service of corporate
     * @param id of Request Service
     * @return doctrine object result of Request Service or false in case of no data
     * */
    public function getRequestServiceList($id)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p, ca.name as accountName, cc.name as countryName, w.name as destinationCityName, w2.name as departureCityName')
            ->leftJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', "ca.id = p.accountId")
            ->leftJoin('CorporateBundle:CmsCountries', 'cc', 'WITH', "cc.id = p.countryId")
            ->leftJoin('CorporateBundle:Webgeocities', 'w', 'WITH', "w.id = p.destinationCityId")
            ->leftJoin('CorporateBundle:Webgeocities', 'w2', 'WITH', "w2.id = p.departureCityId")
            ->where("p.id = :ID")
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
     * This method will add a Request Service
     * @param Request Service info list
     * @return doctrine result of Request Service id or false in case of no data
     * */
    public function addRequestServices($parameters, $isApproved)
    {
        $this->em        = $this->getEntityManager();
        $requestServices = new CorpoRequestServices();
        if (isset($parameters['title']) && $parameters['title'] != '') {
            $requestServices->setTitle($parameters['title']);
        }
        if (isset($parameters['accountId']) && $parameters['accountId'] != '') {
            $requestServices->setAccountId($parameters['accountId']);
        }
        if (isset($parameters['fromDate']) && $parameters['fromDate'] != '') {
            $requestServices->setFromDate(new \DateTime($parameters['fromDate']));
        }
        if (isset($parameters['toDate']) && $parameters['toDate'] != '') {
            $requestServices->setToDate(new \DateTime($parameters['toDate']));
        }
        if (isset($parameters['description']) && $parameters['description'] != '') {
            $requestServices->setDescription($parameters['description']);
        }
        if (isset($parameters['destinationCityNameCode']) && $parameters['destinationCityNameCode'] != '') {
            $requestServices->setDestinationCityId($parameters['destinationCityNameCode']);
        }
        if (isset($parameters['departureCityNameCode']) && $parameters['departureCityNameCode'] != '') {
            $requestServices->setDepartureCityId($parameters['departureCityNameCode']);
        }
        if (isset($parameters['countryNameCode']) && $parameters['countryNameCode'] != '') {
            $requestServices->setCountryId($parameters['countryNameCode']);
        }
        if ($isApproved) {
            $requestServices->setIsApproved($isApproved);
        }
        $requestServices->setApprovedBy($parameters['userId']);
        $requestServices->setCreatedBy($parameters['userId']);
        $requestServices->setCreatedAt(new \DateTime("now"));
        $requestServices->setApprovedAt(new \DateTime("now"));
        $this->em->persist($requestServices);
        $this->em->flush();
        if ($requestServices) {
            $id        = $requestServices->getId();
            $employees = $parameters['employees'];
            $services  = $parameters['services'];
            for ($i = 0; $i < count($employees); ++$i) {
                $employeeId = $employees[$i];
                $this->em->getRepository('CorporateBundle:CorpoRequestServicesEmployee')->AddRequestServicesEmployee($id, $employeeId);
            }
            for ($j = 0; $j < count($services); ++$j) {
                $serviceId = $services[$j];
                $this->em->getRepository('CorporateBundle:CorpoRequestServicesItems')->AddRequestServicesItems($id, $serviceId);
            }
            return $id;
        } else {
            return false;
        }
    }

    /**
     * This method will update a Request Service
     * @param id of Request Service
     * @return doctrine object result of Request Service or false in case of no data
     * */
    public function updateRequestServices($parameters, $isApproved)
    {
        $this->em = $this->getEntityManager();

        $qb = $this->em->createQueryBuilder('p')
            ->update('CorporateBundle:CorpoRequestServices', 'p');
        if (isset($parameters['title'])) {
            $qb->set("p.title", ":title")
                ->setParameter(':title', $parameters['title']);
        }
        if (isset($parameters['accountId'])) {
            $qb->set("p.accountId", ":accountId")
                ->setParameter(':accountId', $parameters['accountId']);
        }
        if (isset($parameters['fromDate'])) {
            $qb->set("p.fromDate", ":fromDate")
                ->setParameter(':fromDate', new \DateTime($parameters['fromDate']));
        }
        if (isset($parameters['toDate'])) {
            $qb->set("p.toDate", ":toDate")
                ->setParameter(':toDate', new \DateTime($parameters['toDate']));
        }
        if (isset($parameters['description'])) {
            $qb->set("p.description", ":description")
                ->setParameter(':description', $parameters['description']);
        }
        if (isset($parameters['destinationCityId'])) {
            $qb->set("p.destinationCityId", ":destinationCityId")
                ->setParameter(':destinationCityId', $parameters['destinationCityId']);
        }
        if (isset($parameters['departureCityId'])) {
            $qb->set("p.departureCityId", ":departureCityId")
                ->setParameter(':departureCityId', $parameters['departureCityId']);
        }
        if (isset($parameters['countryId'])) {
            $qb->set("p.countryId", ":countryId")
                ->setParameter(':countryId', $parameters['countryId']);
        }
        if (isset($parameters['isApproved'])) {
            $qb->set("p.isApproved", ":isApproved")
                ->setParameter(':isApproved', $isApproved);
        }
        $qb->set("p.approvedBy", ":approvedBy")
            ->set("p.approvedAt", ":approvedAt")
            ->where("p.id=:Id")
            ->setParameter(':approvedBy', 1)
            ->setParameter(':approvedAt', new \DateTime("now"))
            ->setParameter(':Id', $parameters['id']);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();

        if ($queryRes) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    /**
     * This method will delete a Request Service
     * @param id of Request Service
     * @return doctrine object result of Request Service or false in case of no data
     * */
    public function deleteRequestServices($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->delete('CorporateBundle:CorpoRequestServices', 'p')
            ->where("p.id = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will add Pending Request Service
     * @param parameters
     * @return doctrine result of Pending Request Service id or false in case of no data
     * */
    public function addPendingRequestServices($parameters, $status)
    {
        $this->em               = $this->getEntityManager();
        $requestServicesDetails = new CorpoRequestServicesDetails();
        if (isset($parameters['requestId'])) {
            $requestServicesDetails->setRequestId($parameters['requestId']);
        }
        if (isset($parameters['userId'])) {
            $requestServicesDetails->setUserId($parameters['userId']);
        }
        if (isset($parameters['accountId'])) {
            $requestServicesDetails->setAccountId($parameters['accountId']);
        }
        if (isset($parameters['reservationId'])) {
            $requestServicesDetails->setReservationId($parameters['reservationId']);
        }
        if (isset($parameters['moduleId'])) {
            $requestServicesDetails->setModuleId($parameters['moduleId']);
        }
        if (isset($parameters['currencyCode'])) {
            $requestServicesDetails->setCurrencyCode($parameters['currencyCode']);
        }
        if (isset($parameters['amount'])) {
            $requestServicesDetails->setAmount($parameters['amount']);
        }
        if (isset($parameters['amountFBC'])) {
            $requestServicesDetails->setAmountFBC($parameters['amountFBC']);
        }
        if (isset($parameters['amountSBC'])) {
            $requestServicesDetails->setAmountSBC($parameters['amountSBC']);
        }
        if (isset($parameters['amountAccountCurrency'])) {
            $requestServicesDetails->setAmountAccountCurrency($parameters['amountAccountCurrency']);
        }
        if (isset($status)) {
            $requestServicesDetails->setStatus($status);
        }
        $requestServicesDetails->setCreatedBy($parameters['userId']);
        $requestServicesDetails->setCreatedAt(new \DateTime("now"));
        $this->em->persist($requestServicesDetails);
        $this->em->flush();
        if ($requestServicesDetails) {
            return $requestServicesDetails;
        } else {
            return false;
        }
    }

    /**
     * This method will update the status of the pending Request
     * @param parameters
     * @return doctrine result of Pending Request Service id or false in case of no data
     * */
    public function updatePendingRequestServices($parameters)
    {
        $this->em = $this->getEntityManager();
        $qb = $this->em->createQueryBuilder('p')
            ->update('CorporateBundle:CorpoRequestServicesDetails', 'p');
        if (isset($parameters['requestStatus'])) {
            $qb->set("p.status", ":requestStatus")
                ->setParameter(':requestStatus', $parameters['requestStatus']);
        }
        if (isset($parameters['amount'])) {
            $qb->set("p.amount", ":amount")
                ->setParameter(':amount', $parameters['amount']);
        }
        if (isset($parameters['amountFBC'])) {
            $qb->set("p.amountFBC", ":amountFBC")
                ->setParameter(':amountFBC', $parameters['amountFBC']);
        }
        if (isset($parameters['amountSBC'])) {
            $qb->set("p.amountSBC", ":amountSBC")
                ->setParameter(':amountSBC', $parameters['amountSBC']);
        }
        if (isset($parameters['amountAccountCurrency'])) {
            $qb->set("p.amountAccountCurrency", ":amountAccountCurrency")
                ->setParameter(':amountAccountCurrency', $parameters['amountAccountCurrency']);
        }
        if (isset($parameters['reservationId']) && isset($parameters['moduleId'])) {
            $qb->where("p.reservationId=:reservationId")
                ->andwhere("p.moduleId=:moduleId")
                ->setParameter(':reservationId', $parameters['reservationId'])
                ->setParameter(':moduleId', $parameters['moduleId']);
        } elseif (isset($parameters['id'])) {
            $qb->where("p.id=:Id")
                ->setParameter(':Id', $parameters['id']);
        }

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();

        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }
    
    

    /**
     * This method will change To Expiry
     * @param parameters
     * @return doctrine result of Request Service id or false in case of no data
     * */
    public function updateExpiredRecords($parameters)
    {
        
        $conn = $this->getEntityManager()->getConnection();
        $sql  ='UPDATE `corpo_request_services_details` SET `status` = '.$parameters['CORPO_APPROVAL_EXPIRED'].'
                WHERE  `account_id` = '.$parameters['accountId'].'
                AND    `module_id` != '.$parameters['MODULE_FINANCE'].'
                AND    `status` = '.$parameters['CORPO_APPROVAL_PENDING'].'
                AND(
                    ('.$parameters['currentDate'].' - UNIX_TIMESTAMP(`created_at`) >= '.$parameters['FLIGHT_EXPIRY_TIME'].' AND `module_id` = '.$parameters['MODULE_FLIGHTS'].')
                    OR
                    ('.$parameters['currentDate'].' - UNIX_TIMESTAMP(`created_at`) >= '.$parameters['HOTEL_EXPIRY_TIME'].' AND `module_id` = '.$parameters['MODULE_HOTELS'].')
                    OR
                    ('.$parameters['currentDate'].' - UNIX_TIMESTAMP(`created_at`) >= '.$parameters['DEAL_EXPIRY_TIME'].' AND `module_id` = '.$parameters['MODULE_DEALS'].')
                );';

        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();
        if (!empty($result) && isset($result)) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will get Approval Dteails Services
     * @param parameters
     * @return doctrine result of Approval Dteails id or false in case of no data
     * */
    public function getApprovalDetailsServices($parameters)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p,');
        if (isset($parameters['requestStatus'])) {
            $qb->set("p.status", ":requestStatus")
                ->setParameter(':requestStatus', $parameters['requestStatus']);
        }
        $qb->where("p.id=:Id")
            ->setParameter(':Id', $parameters['id']);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();

        if ($queryRes) {
            return $query->getResult();
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve all Request Service of corporate
     * @param id of Request Service
     * @return doctrine object result of Request Service or false in case of no data
     * */
    public function getPendingRequestDetailsId($reservationId, $moduleId)
    {
        $query = $this->createQueryBuilder('p')
            ->where("p.reservationId=:reservationId")
            ->andwhere("p.moduleId=:moduleId")
            ->setParameter(':reservationId', $reservationId)
            ->setParameter(':moduleId', $moduleId);

        $query    = $query->getQuery();
        $queryRes = $query->getScalarResult();

        if ($queryRes) {
            return $queryRes[0];
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve all Request Service of corporate
     * @param id of Request Service
     * @return doctrine object result of Request Service or false in case of no data
     * */
    public function getPendingRequest($parameter)
    {
        $query = $this->createQueryBuilder('p')
            ->where("p.accountId=:accountId")
            ->setParameter(':accountId', $parameter['accountId']);
        if(isset($parameter['moduleId']) && $parameter['moduleId'] != 0){
            $query->andwhere("p.moduleId=:moduleId")
            ->setParameter(':moduleId', $parameter['moduleId']);
        }
        if(isset($parameter['status']) && $parameter['status'] != 0){
            $query->andwhere("p.status=:status")
            ->setParameter(':status', $parameter['status']);
        }

        $quer     = $query->getQuery();
        $queryRes = $quer->getResult();

        if ($queryRes) {
            return $queryRes;
        } else {
            return array();
        }
    }

    /**
     * This method will get the sum of all amount of a request details by account
     * 
     * @return mixed|array
     * */
    public function getRequestDetailSumOfAmount($sessionAccountId, $status, $params = array())
    {
        $query = $this->createQueryBuilder('p');
        $query->select(
                'SUM(p.amountFBC) as sumAmountFBC, 
                SUM(p.amountSBC) as sumAmountSBC, 
                SUM(p.amount) as sumAmount, 
                SUM(p.amountAccountCurrency) as sumAccountAmount'
            );

            $query->leftJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', 'ca.id = p.accountId');

            $query->where($query->expr()->like('ca.path', ':sessionAccountId'))->setParameter('sessionAccountId','%'.$sessionAccountId.'%');

            $query->andwhere("p.accountId = :accountId")->setParameter('accountId', $params['accountId']);

            if(isset($params['userId'])){
                $query->andwhere("p.userId = :userId")->setParameter('userId', $params['userId']);
            }

            if(isset($params['types'])){
                $query->andwhere("p.moduleId IN(:type)")->setParameter('type', array_values($params['types']));
            }

            if($status){
                $query->andwhere("p.status = :status")->setParameter('status', $status);
            }

        $query    = $query->getQuery();
        $queryRes = $query->getScalarResult();

        if ($queryRes) {
            return $queryRes[0];
        } else {
            return array();
        }
    }
}
