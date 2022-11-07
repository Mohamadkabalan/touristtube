<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoAccountTransactions;
use CorporateBundle\Entity\CorpoApprovalFlowStatus;
use TTBundle\Entity\TTModules;
use TTBundle\Utils\Utils;

class CorpoAccountTransactionsRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all accounts  Transactions of corporate
     * @param
     * @return doctrine object result of accounts  Transactions or false in case of no data
     * */
    public function getAccountTransactionsList($parameters)
    {
        $query = $this->createQueryBuilder('al')
            ->select('al, cu.code as currencyCode, a.preferredCurrency as userPreferredCurrency, a.name as accountName, u.fullname as userName, cr.status as status, m.name as moduleName, st.name as statusName')
            ->leftJoin('CorporateBundle:CorpoAccount', 'a', 'WITH', "a.id = al.accountId")
            ->leftJoin('CorporateBundle:Currency', 'cu', 'WITH', "cu.code = al.currencyCode")
            ->leftJoin('CorporateBundle:CorpoRequestServicesDetails', 'cr', 'WITH', "cr.id = al.requestDetailId")
            ->leftJoin('CorporateBundle:CorpoApprovalFlowStatus', 'st', 'WITH', "cr.status = st.id")
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = al.userId")
            ->leftJoin('TTBundle:TTModules', 'm', 'WITH', "al.moduleId = m.id");

        if (isset($parameters['accountId'])) {
            $query->where("al.accountId = :accountId")
                ->setParameter(':accountId', $parameters['accountId']);
        }
        if (isset($parameters['firstDay']) && $parameters['firstDay'] != '') {
            $firstDay = new \DateTime($parameters['firstDay']);
            $firstDay = $firstDay->format('Y-m-d');
            $query->andwhere("date(al.createdAt) >= date(:firstDay)")
                ->setParameter(':firstDay', $firstDay);
        }

        if (isset($parameters['lastDay']) && $parameters['lastDay'] != '') {
            $lastDay = new \DateTime($parameters['lastDay']);
            $lastDay = $lastDay->format('Y-m-d');
            $query->andwhere("date(al.createdAt) <= date(:lastDay)")
                ->setParameter(':lastDay', $lastDay);
        }
        if (isset($parameters['moduleId']) && $parameters['moduleId'] != 0 && $parameters['moduleId'] != '') {
            $query->andwhere("al.moduleId = :moduleId")
                ->setParameter(':moduleId', $parameters['moduleId']);
        }
        if (isset($parameters['currencyCode']) && $parameters['currencyCode'] != '') {
            $query->andwhere("al.currencyCode = :currencyCode")
                ->setParameter(':currencyCode', $parameters['currencyCode']);
        }
        if (isset($parameters['userId']) && $parameters['userId'] != '') {
            $query->andwhere("al.userId = :userId")
                ->setParameter(':userId', $parameters['userId']);
        }
        $query->orderBy('al.createdAt', 'DESC');

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    public function prepareTransactionsDtQuery($params)
    {
        $query = "SELECT 
                cat.id cat__id, DATE_FORMAT(cat.created_at, '%Y-%m-%d') cat__created_at, ca.name ca__name, cu.FullName cu__FullName,
                tt.name tt__name, cat.currency_code as cat__currency_code, ca.preferred_currency ca__preferred_currency, cat.amount cat__amount, cat.amount_fbc cat__amount_fbc, cat.amount_sbc cat__amount_sbc,
                cat.account_currency_amount cat__account_currency_amount, description, cafs.id cafs__id, cafs.name cafs__name, cat.module_id cat__module_id
            FROM corpo_account_transactions cat
            LEFT JOIN corpo_account ca ON (ca.id = cat.account_id)
            LEFT JOIN corpo_request_services_details crsd ON (crsd.id = cat.request_detail_id)
            LEFT JOIN corpo_approval_flow_status cafs ON (cafs.id = crsd.status)
            LEFT JOIN cms_users cu ON (cu.id = cat.user_id)
            LEFT JOIN tt_modules tt ON (tt.id = cat.module_id)
        ";

        $where = "";
        if(isset($params['accountId'])) {
            $accountId = $params['accountId'];
            $where .= " AND ca.path LIKE '%,$accountId,%'";
        }

        $result_arr["all_query"] = $query;
        $result_arr['where'] = $where;
        return Utils::prepareDatatableObj($result_arr);
    }

    /**
     * This method will get the sum of all amount of account transactions by account
     * @param accountId
     * @return doctrine object result of account or false in case of no data
     * */
    public function getAccountTransactionTotals($parameters)
    {
        $query = $this->createQueryBuilder('p')
            ->select('SUM(p.amountFBC) as sumAmountFBC, SUM(p.amountSBC) as sumAmountSBC, SUM(p.amount) as sumAmount, SUM(p.amountAccountCurrency) as sumAccountAmount')
                ->innerJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', "ca.id = p.accountId");
            
        if(isset($parameters['sessionAccountId'])) {
            $sessionAccId = $parameters['sessionAccountId'];
            $query->andwhere('ca.path LIKE :sessAccountId')
                ->setParameter(':sessAccountId', "%,$sessionAccId,%");
        }

        if (isset($parameters['accountId']) && $parameters['accountId'] != '') {
            $query->andwhere("p.accountId = :accountId")
                ->setParameter(':accountId', $parameters['accountId']);
        }
        if (isset($parameters['firstDay']) && $parameters['firstDay'] != '') {
            $query->andwhere("date(p.createdAt) >= date(:firstDay)")
                ->setParameter(':firstDay', $parameters['firstDay']);
        }
        if (isset($parameters['lastDay']) && $parameters['lastDay'] != '') {
            $query->andwhere("date(p.createdAt) <= date(:lastDay)")
                ->setParameter(':lastDay', $parameters['lastDay']);
        }
        if (isset($parameters['moduleId']) && $parameters['moduleId'] != 0 && $parameters['moduleId'] != '') {
            $query->andwhere("p.moduleId = :moduleId")
                ->setParameter(':moduleId', $parameters['moduleId']);
        }
        if (isset($parameters['currencyCode']) && $parameters['currencyCode'] != '') {
            $query->andwhere("p.currencyCode = :currencyCode")
                ->setParameter(':currencyCode', $parameters['currencyCode']);
        }
        if (isset($parameters['dueDate']) && $parameters['dueDate'] != '') {
            $query->andwhere("date(p.createdAt) >= date(:dueDate)")
                ->setParameter(':dueDate', $parameters['dueDate']);
        }
        if (isset($parameters['userId']) && $parameters['userId'] != '') {
            $query->andwhere("p.createdBy = :userId")
                ->setParameter(':userId', $parameters['userId']);
        }

        $query = $query->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve an account  of corporate from an id sent
     * @param id of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function getAccountTransactionsById($id)
    {
        $query = $this->createQueryBuilder('al')
            ->select('al')
            ->where("al.id = :ID")
            ->setParameter(':ID', $id);

        $query  = $query->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve an account of corporate
     * @param reservationId of account
     * @param moduleId of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function getAccountTransactionsByReservationId($reservationId, $moduleId)
    {
        $query = $this->createQueryBuilder('al')
            ->select('al')
            ->where("al.reservationId = :reservationId")
            ->andwhere("al.moduleId = :moduleId")
            ->setParameter(':reservationId', $reservationId)
            ->setParameter(':moduleId', $moduleId);

        $query  = $query->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will add an account for corporate accounts Transactions
     * @param account info list
     * @return doctrine result of account Transactions id or false in case of no data
     * */
    public function addAccountTransactions($parameters)
    {
        $this->em = $this->getEntityManager();
        $account  = new CorpoAccountTransactions();
        if (isset($parameters['accountId'])) {
            $account->setAccountId($parameters['accountId']);
        }
        if (isset($parameters['userId'])) {
            $account->setUserId($parameters['userId']);
        }
        if (isset($parameters['requestDetailId'])) {
            $account->setRequestDetailId($parameters['requestDetailId']);
        }
        if (isset($parameters['moduleId'])) {
            $account->setModuleId($parameters['moduleId']);
        }
        if (isset($parameters['reservationId'])) {
            $account->setReservationId($parameters['reservationId']);
        }
        if (isset($parameters['currencyCode'])) {
            $account->setCurrencyCode($parameters['currencyCode']);
        }
        if (isset($parameters['amount'])) {
            $account->setAmount($parameters['amount']);
        }
        if (isset($parameters['amountFBC'])) {
            $account->setAmountFBC($parameters['amountFBC']);
        }
        if (isset($parameters['amountSBC'])) {
            $account->setAmountSBC($parameters['amountSBC']);
        }
        if (isset($parameters['amountAccountCurrency'])) {
            $account->setAmountAccountCurrency($parameters['amountAccountCurrency']);
        }
        if (isset($parameters['description'])) {
            $account->setDescription($parameters['description']);
        }
        if (isset($parameters['paymentDate'])) {
            $account->setCreatedAt(new \DateTime($parameters['paymentDate']));
        } else {
            $account->setCreatedAt(new \DateTime("now"));
        }

        $account->setCreatedBy($parameters['userId']);
        $this->em->persist($account);
        $this->em->flush();
        if ($account) {
            return $account->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will add an account for corporate accounts Transactions
     * @param $transactionsObj
     * @return doctrine result of account Transactions id or false in case of no data
     * */
    public function addAccountTransactionsNew($transactionsObj)
    {
        $this->em = $this->getEntityManager();
        $account  = new CorpoAccountTransactions();

        $accountId = $transactionsObj->getAccount()->getId();
        $userId = $transactionsObj->getUser()->getId();
        $requestDetailId = $transactionsObj->getRequestDetail()->getId();
        $moduleId = $transactionsObj->getModuleId();
        $reservationId = $transactionsObj->getReservation()->getId();
        $currencyCode = $transactionsObj->getCurrency()->getCode();
        $amount = $transactionsObj->getAmount();
        $amountFBC = $transactionsObj->getAmountFBC();
        $amountSBC = $transactionsObj->getAmountSBC();
        $amountAccountCurrency = $transactionsObj->getAmountAccountCurrency();
        $description = $transactionsObj->getDescription();
        $paymentDate = $transactionsObj->getPaymentDate();
        $createdBy = $transactionsObj->getCreatedBy()->getId();

        if (isset($accountId)) {
            $account->setAccountId($accountId);
        }
        if (isset($userId)) {
            $account->setUserId($userId);
        }
        if (isset($requestDetailId)) {
            $account->setRequestDetailId($requestDetailId);
        }
        if (isset($moduleId)) {
            $account->setModuleId($moduleId);
        }
        if (isset($reservationId)) {
            $account->setReservationId($reservationId);
        }
        if (isset($currencyCode)) {
            $account->setCurrencyCode($currencyCode);
        }
        if (isset($amount)) {
            $account->setAmount($amount);
        }
        if (isset($amountFBC)) {
            $account->setAmountFBC($amountFBC);
        }
        if (isset($amountSBC)) {
            $account->setAmountSBC($amountSBC);
        }
        if (isset($amountAccountCurrency)) {
            $account->setAmountAccountCurrency($amountAccountCurrency);
        }
        if (isset($description)) {
            $account->setDescription($description);
        }
        if (isset($paymentDate)) {
            $account->setCreatedAt($paymentDate);
        } else {
            $account->setCreatedAt(new \DateTime("now"));
        }
        $account->setCreatedBy($createdBy);

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
     * @param id of account
     * @return doctrine object result of account or false in case of no data
     * */
    public function updateAccountTransactions($parameters)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->update('CorporateBundle:CorpoAccountTransactions', 'ca');
        if (isset($parameters['accountId'])) {
            $qb->set("ca.accountId", ":accountId")
                ->setParameter(':accountId', $parameters['accountId']);
        }
        if (isset($parameters['userId'])) {
            $qb->set("ca.userId", ":userId")
                ->setParameter(':userId', $parameters['userId']);
        }
        if (isset($parameters['requestDetailId'])) {
            $qb->set("ca.requestDetailId", ":requestDetailId")
                ->setParameter(':requestDetailId', $parameters['requestDetailId']);
        }
        if (isset($parameters['currencyCode'])) {
            $qb->set("ca.currencyCode", ":currencyCode")
                ->setParameter(':currencyCode', $parameters['currencyCode']);
        }
        if (isset($parameters['amount'])) {
            $qb->set("ca.amount", ":amount")
                ->setParameter(':amount', $parameters['amount']);
        }
        if (isset($parameters['amountFBC'])) {
            $qb->set("ca.amountFBC", ":amountFBC")
                ->setParameter(':amountFBC', $parameters['amountFBC']);
        }
        if (isset($parameters['amountSBC'])) {
            $qb->set("ca.amountSBC", ":amountSBC")
                ->setParameter(':amountSBC', $parameters['amountSBC']);
        }
        if (isset($parameters['amountAccountCurrency'])) {
            $qb->set("ca.amountAccountCurrency", ":amountAccountCurrency")
                ->setParameter(':amountAccountCurrency', $parameters['amountAccountCurrency']);
        }
        if (isset($parameters['description'])) {
            $qb->set("ca.description", ":description")
                ->setParameter(':description', $parameters['description']);
        }
        if (isset($parameters['paymentDate'])) {
            $qb->set("ca.createdAt", ":paymentDate")
                ->setParameter(':paymentDate', new \DateTime($parameters['paymentDate']));
        }
        $qb->where("ca.reservationId=:reservationId")
            ->andwhere("ca.moduleId=:moduleId")
            ->setParameter(':reservationId', $parameters['reservationId'])
            ->setParameter(':moduleId', $parameters['moduleId']);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    /**
     * This method will update an account transaction
     * @param $transactionsObj
     * @return doctrine object result of account or false in case of no data
     * */
    public function updateAccountTransactionsNew($transactionsObj)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->update('CorporateBundle:CorpoAccountTransactions', 'ca');

        $accountId = $transactionsObj->getAccount()->getId();
        $userId = $transactionsObj->getUser()->getId();
        $requestDetailId = $transactionsObj->getRequestDetail()->getId();
        $moduleId = $transactionsObj->getModuleId();
        $reservationId = $transactionsObj->getReservation()->getId();
        $currencyCode = $transactionsObj->getCurrency()->getCode();
        $amount = $transactionsObj->getAmount();
        $amountFBC = $transactionsObj->getAmountFBC();
        $amountSBC = $transactionsObj->getAmountSBC();
        $amountAccountCurrency = $transactionsObj->getAmountAccountCurrency();
        $description = $transactionsObj->getDescription();
        $paymentDate = $transactionsObj->getPaymentDate();
        $createdBy = $transactionsObj->getCreatedBy()->getId();

        if (isset($accountId)) {
            $qb->set("ca.accountId", ":accountId")
                ->setParameter(':accountId', $accountId);
        }
        if (isset($userId)) {
            $qb->set("ca.userId", ":userId")
                ->setParameter(':userId', $userId);
        }
        if (isset($requestDetailId)) {
            $qb->set("ca.requestDetailId", ":requestDetailId")
                ->setParameter(':requestDetailId', $requestDetailId);
        }
        if (isset($currencyCode)) {
            $qb->set("ca.currencyCode", ":currencyCode")
                ->setParameter(':currencyCode', $currencyCode);
        }
        if (isset($amount)) {
            $qb->set("ca.amount", ":amount")
                ->setParameter(':amount', $amount);
        }
        if (isset($amountFBC)) {
            $qb->set("ca.amountFBC", ":amountFBC")
                ->setParameter(':amountFBC', $amountFBC);
        }
        if (isset($amountSBC)) {
            $qb->set("ca.amountSBC", ":amountSBC")
                ->setParameter(':amountSBC', $amountSBC);
        }
        if (isset($amountAccountCurrency)) {
            $qb->set("ca.amountAccountCurrency", ":amountAccountCurrency")
                ->setParameter(':amountAccountCurrency', $amountAccountCurrency);
        }
        if (isset($description)) {
            $qb->set("ca.description", ":description")
                ->setParameter(':description', $description);
        }
        if (isset($paymentDate)) {
            $qb->set("ca.createdAt", ":paymentDate")
                ->setParameter(':paymentDate', $paymentDate);
        }
        
        $qb->where("ca.reservationId=:reservationId")
            ->andwhere("ca.moduleId=:moduleId")
            ->setParameter(':reservationId', $reservationId)
            ->setParameter(':moduleId', $moduleId);

        $query    = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /**
     * This method will delete an account Transactions by id
     * @param id of account
     * @return doctrine object result of account Transactions or false in case of no data
     * */
    public function deleteAccountTransactionsById($parameters)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->delete('CorporateBundle:CorpoAccountTransactions', 'ca')
            ->where("ca.reservationId = :reservationId AND ca.moduleId = :moduleId")
            ->setParameter(':reservationId', $parameters['reservationId'])
            ->setParameter(':moduleId', $parameters['moduleId']);
        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will get Account Transaction All Hotel Info
     * @param id of account transaction
     * @return doctrine object result of account Transactions or false in case of no data
     * */
    public function getAccountTransactionHotelAllInfo($id, $moduleId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql  = 'SELECT
                        '.$moduleId.' AS moduleId,
                        CONCAT_WS (" ", hr.first_name, hr.last_name) AS "customer",
                        hr.creation_date AS "date",
                        rqd.id AS "accountTransactionId",
                        rqd.user_id AS transactionUserId,
                        rqd.account_id AS accountId,
                        hr.id AS reservationId,
                        ah.property_name AS "name",
                        ahc.city_name AS "city",
                        ahc.country_name AS "country",
                        CONCAT_WS(", ", ah.address_line_1, ah.address_line_2) AS "address",
                        hr.from_date AS "fromdate",
                        hr.to_date AS "todate",
                        null AS "starttime",
                        null AS "endtime",
                        hr.hotel_currency AS "currency",
                        hr.hotel_grand_total AS "amount",
                        ""  AS "details"
                FROM corpo_account_transactions rqd
                        INNER JOIN hotel_reservation hr ON (rqd.reservation_id = hr.id AND rqd.module_id = '.$moduleId.')
                        INNER JOIN amadeus_hotel ah ON hr.hotel_id = ah.id
                        INNER JOIN amadeus_hotel_city ahc ON ah.amadeus_city_id = ahc.id
                WHERE rqd.module_id = '.$moduleId.'
                AND   rqd.id = '.$id;
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will get Account Transaction All Flight Info
     * @param id of account transaction
     * @return doctrine object result of account Transactions or false in case of no data
     * */
    public function getAccountTransactionFlightAllInfo($id, $moduleId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql  = '
                    SELECT
                            '.$moduleId.' AS "moduleId",
                            rqd.id AS "accountTransactionId",
                            rqd.user_id AS transactionUserId,
                            rqd.account_id AS accountId,
                            pnr.id AS "reservationId",
                            fd.airline AS "name",
                            "" AS "city",
                            pnr.country_of_residence AS "country",
                            "" AS "address",
                            fd.departure_datetime AS "fromdate",
                            fd.arrival_datetime AS "todate",
                            "" AS "starttime",
                            "" AS "endtime",
                            fi.display_currency AS "currency",
                            fi.display_price AS "amount",
                            CONCAT_WS(" ", pnr.first_name, pnr.surname) AS "customer",
                            pnr.creation_date AS "date",
                            JSON_OBJECT(
                                "reference", pnr.pnr, 
                                "airline", fd.airline, 
                                "firstName", pd.first_name, 
                                "surname", pd.surname, 
                                "ticketNumber", pd.ticket_number, 
                                "segmentNumber", fd.segment_number, 
                                "departureAirport", fd.departure_airport, 
                                "arrivalAirport", fd.arrival_airport,
                                "operatingAirline", fd.operating_airline, 
                                "flightNumber", fd.flight_number, 
                                "cabin", fd.cabin, 
                                "flightDuration", fd.flight_duration, 
                                "type", fd.type, 
                                "amount", fi.price, 
                                "currency", fi.currency, 
                                "baseFare", fi.base_fare, 
                                "displayBaseFare", fi.display_base_fare, 
                                "taxes", fi.taxes, 
                                "displayTaxes", fi.display_taxes, 
                                "refundable", fi.refundable, 
                                "oneWay", fi.one_way, 
                                "multiDestination", fi.multi_destination,
                                "passengerType", pd.type,
                                "stopIndicator", fd.stop_indicator,
                                "stopDuration", fd.stop_duration,
                                "departureDatetime", fd.departure_datetime,
                                "arrivalDatetime", fd.arrival_datetime
                            ) AS "details"
                    FROM   corpo_account_transactions rqd
                            INNER JOIN passenger_name_record AS pnr
                                ON (rqd.reservation_id = pnr.id AND rqd.module_id = '.$moduleId.')
                            INNER JOIN passenger_detail AS pd
                                ON pnr.id = pd.pnr_id
                            INNER JOIN flight_detail AS fd
                                ON pnr.id = fd.pnr_id
                            INNER JOIN flight_info AS fi
                                ON pnr.id = fi.pnr_id
                    WHERE  rqd.module_id = '.$moduleId.'
                    AND    rqd.id = '.$id;

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will get Account Transaction All Deal Info
     * @param id of account transaction
     * @return doctrine object result of account Transactions or false in case of no data
     * */
    public function getAccountTransactionDealAllInfo($id, $moduleId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql  = 'SELECT
                        '.$moduleId.' AS "moduleId",
                        CONCAT_WS(" ", db.first_name, db.last_name) AS "customer",
                        db.updated_at AS "date",
                        rqd.id AS "accountTransactionId",
                        rqd.user_id AS transactionUserId,
                        rqd.account_id AS accountId,
                        db.id AS "reservationId",
                        db.deal_name AS "name",
                        dc.city_name AS "city",
                        dct.country_name AS "country",
                    db.address AS "address",
                        db.booking_date AS "fromdate",
                        db.booking_date AS "todate",
                        db.start_time AS "starttime",
                        db.end_time AS "endtime",
                        db.currency AS "currency",
                        db.total_price AS "amount",
                        ""  AS "details"
                FROM corpo_account_transactions rqd
                        INNER JOIN deal_booking db ON (rqd.reservation_id = db.id AND rqd.module_id = '.$moduleId.')
                        INNER JOIN deal_city dc ON (db.deal_city_id = dc.id)
                        INNER JOIN deal_country dct ON (db.country_id = dct.id)
                WHERE rqd.module_id = '.$moduleId.'
                AND rqd.id = '.$id;
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will get Account Transaction All Deal Info
     * @param id of account transaction
     * @return doctrine object result of account Transactions or false in case of no data
     * */
    public function getAccountTransactionPaymentAllInfo($id, $accountId, $moduleId)
    {
        $conn = $this->getEntityManager()->getConnection();
        if(!empty($accountId)) {
            $sql  = 'SELECT
                        SUM(cp.amount) sumAmt, cp.currency_code, cp.description,';    
        } else {
            $sql  = 'SELECT
                        cp.amount sumAmt, cp.currency_code, cp.description,';
        }
        $sql  .= 'ca.name as accountName,cu.fullname as userName
                FROM corpo_account_transactions rqd
                        INNER JOIN corpo_account_payment as cp ON rqd.reservation_id = cp.id
                        INNER JOIN corpo_account as ca ON cp.account_id = ca.id
                        INNER JOIN cms_users as cu ON cp.user_id = cu.id
                WHERE rqd.module_id = '.$moduleId;
                if(!empty($id)) {
                    $sql .= ' AND rqd.id = '.$id;
                }
                if(!empty($accountId)) {
                    $sql .= ' AND rqd.account_id = '.$accountId;
                }
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }
}
