<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoAccountPayment;
use TTBundle\Utils\Utils;

class CorpoAccountPaymentRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all accounts  Payment of corporate
     * @param
     * @return doctrine object result of accounts  Payment or false in case of no data
     * */
    public function getAccountPaymentList($parameters)
    {
        $query = $this->createQueryBuilder('al')
            ->select('al, cu.code as currencyCode, a.name as accountName, u.fullname as userName,a.preferredCurrency as preferredAccountCurrency')
            ->leftJoin('CorporateBundle:CorpoAccount', 'a', 'WITH', "a.id = al.accountId")
            ->leftJoin('CorporateBundle:Currency', 'cu', 'WITH', "cu.code = al.currencyCode")
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = al.userId");

        if(isset($parameters['sessionAccountId'])) {
            $sessionAccId = $parameters['sessionAccountId'];
            $query->andwhere('a.path LIKE :sessAccountId')
                ->setParameter(':sessAccountId', "%,$sessionAccId,%");
        }

        if (isset($parameters['accountId']) && $parameters['accountId'] != '') {
            $query->andwhere("al.accountId = :accountId")
                ->setParameter(':accountId', $parameters['accountId']);
        }
        if (isset($parameters['firstDay']) && $parameters['firstDay'] != '') {
            $query->andwhere("al.paymentDate >= :firstDay")
                ->setParameter(':firstDay', $parameters['firstDay']);
        }

        if (isset($parameters['lastDay']) && $parameters['lastDay'] != '') {
            $query->andwhere("al.paymentDate <= :lastDay")
                ->setParameter(':lastDay', $parameters['lastDay']);
        }
        if (isset($parameters['moduleId']) && $parameters['moduleId'] != 0 && $parameters['moduleId'] != '') {
            $query->andwhere("al.moduleId = :moduleId")
                ->setParameter(':moduleId', $parameters['moduleId']);
        }
        if (isset($parameters['currencyCode']) && $parameters['currencyCode'] != '') {
            $query->andwhere("al.currencyCode = :currencyCode")
                ->setParameter(':currencyCode', $parameters['currencyCode']);
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();
        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    public function preparePaymentDtQuery($params)
    {
        $query = "
            SELECT cap.id cap__id, DATE(payment_date) payment_date, ca.name ca__name, FullName, cap.amount cap__amount, cap.currency_code cap__currency_code, cap.amount_fbc cap__amount_fbc, cap.amount_sbc cap__amount_sbc, ca.preferred_currency ca__preferred_currency, cap.account_currency_amount cap__account_currency_amount, description 
            FROM corpo_account_payment cap
            LEFT JOIN corpo_account ca ON (ca.id = cap.account_id)
            LEFT JOIN cms_users cu ON (cu.id = cap.user_id)
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
     * This method will get the sum of all amount of account Payment by account
     * @param accountId
     * @return doctrine object result of account or false in case of no data
     * */
    public function getAccountPaymentTotals($parameters)
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
            $query->andwhere("p.paymentDate >= :firstDay")
                ->setParameter(':firstDay', $parameters['firstDay']);
        }
        if (isset($parameters['lastDay']) && $parameters['lastDay'] != '') {
            $query->andwhere("p.paymentDate <= :lastDay")
                ->setParameter(':lastDay', $parameters['lastDay']);
        }
        if (isset($parameters['moduleId']) && $parameters['moduleId'] != 0 && $parameters['moduleId'] != '') {
            $query->andwhere("p.moduleId = :moduleId")
                ->setParameter(':moduleId', $parameters['moduleId']);
        }
        if (isset($parameters['currencyCode']) && $parameters['currencyCode'] != '' ) {
            $query->andwhere("p.currencyCode = :currencyCode")
                ->setParameter(':currencyCode', $parameters['currencyCode']);
        }

        $query  = $query->getQuery();
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
    public function getAccountPaymentById($id)
    {
        $query = $this->createQueryBuilder('al')
            ->select('al, cu.code as currencyCode, a.name as accountName, u.fullname as userName')
            ->leftJoin('CorporateBundle:CorpoAccount', 'a', 'WITH', "a.id = al.accountId")
            ->leftJoin('CorporateBundle:Currency', 'cu', 'WITH', "cu.code = al.currencyCode")
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = al.userId")
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
     * This method will add account Payment
     * @param account info list
     * @return doctrine result of account Payment id or false in case of no data
     * */
    public function addAccountPayment($parameters)
    {
        $this->em = $this->getEntityManager();
        $account  = new CorpoAccountPayment();
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
            $account->setPaymentDate(new \DateTime($parameters['paymentDate']));
        }
        $account->setCreatedAt(new \DateTime("now"));
        $account->setCreatedBy($parameters['createdBy']);
        $this->em->persist($account);
        $this->em->flush();
        if ($account) {
            return $account->getId();
        } else {
            return false;
        }
    }

    /**
     * This method will add account Payment
     * @param $paymentObj
     * @return doctrine result of account Payment id or false in case of no data
     * */
    public function addAccountPaymentNew($paymentObj)
    {
        $this->em = $this->getEntityManager();
        $account  = new CorpoAccountPayment();

        $accountId = $paymentObj->getAccount()->getId();
        $userId = $paymentObj->getUser()->getId();
        $moduleId = $paymentObj->getModuleId();
        $currencyCode = $paymentObj->getCurrency()->getCode();
        $amount = $paymentObj->getAmount();
        $amountFBC = $paymentObj->getAmountFBC();
        $amountSBC = $paymentObj->getAmountSBC();
        $amountAccountCurrency = $paymentObj->getAmountAccountCurrency();
        $description = $paymentObj->getDescription();
        $date = $paymentObj->getPaymentDate();
        $createdBy = $paymentObj->getCreatedBy()->getId();

        if (isset($accountId)) {
            $account->setAccountId($accountId);
        }
        if (isset($userId)) {
            $account->setUserId($userId);
        }
        if (isset($moduleId)) {
            $account->setModuleId($moduleId);
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
        if (isset($date)) {
            $account->setPaymentDate($date);
        } else {
            $account->setCreatedAt(new \DateTime("now"));
        }
        $account->setCreatedAt(new \DateTime("now"));
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
    public function updateAccountPayment($parameters)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->update('CorporateBundle:CorpoAccountPayment', 'ca');
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
        if (isset($parameters['moduleId'])) {
            $qb->set("ca.moduleId", ":moduleId")
                ->setParameter(':moduleId', $parameters['moduleId']);
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
            $qb->set("ca.paymentDate", ":paymentDate")
                ->setParameter(':paymentDate',new \DateTime($parameters['paymentDate']));
        }
        $qb->where("ca.id=:Id")
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
     * This method will update an account payment
     * @param $paymentObj
     * @return doctrine object result of account or false in case of no data
     * */
    public function updateAccountPaymentNew($paymentObj)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->update('CorporateBundle:CorpoAccountPayment', 'ca');

        $id = $paymentObj->getId();
        $accountId = $paymentObj->getAccount()->getId();
        $userId = $paymentObj->getUser()->getId();
        $moduleId = $paymentObj->getModuleId();
        $currencyCode = $paymentObj->getCurrency()->getCode();
        $amount = $paymentObj->getAmount();
        $amountFBC = $paymentObj->getAmountFBC();
        $amountSBC = $paymentObj->getAmountSBC();
        $amountAccountCurrency = $paymentObj->getAmountAccountCurrency();
        $description = $paymentObj->getDescription();
        $date = $paymentObj->getPaymentDate();

        if (isset($accountId)) {
            $qb->set("ca.accountId", ":accountId")
                ->setParameter(':accountId', $accountId);
        }
        if (isset($userId)) {
            $qb->set("ca.userId", ":userId")
                ->setParameter(':userId', $userId);
        }
        if (isset($moduleId)) {
            $qb->set("ca.moduleId", ":moduleId")
                ->setParameter(':moduleId', $moduleId);
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
        if (isset($date)) {
            $qb->set("ca.paymentDate", ":paymentDate")
                ->setParameter(':paymentDate',$date);
        }

        $qb->where("ca.id=:Id")
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
     * This method will delete an account Payment by id
     * @param id of account
     * @return doctrine object result of account Payment or false in case of no data
     * */
    public function deleteAccountPaymentById($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('ca')
            ->delete('CorporateBundle:CorpoAccountPayment', 'ca')
            ->where("ca.id = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * This method will get Account Transaction All Hotel Info
     * @param id of account transaction
     * @return doctrine object result of account Transactions or false in case of no data
     * */
    public function getAccountTransactionHotelAllInfo($id,$moduleId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql  = 'SELECT
                        '.$moduleId.' AS moduleId,
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
            return false;
        }
    }

    /**
     * This method will get Account Transaction All Flight Info
     * @param id of account transaction
     * @return doctrine object result of account Transactions or false in case of no data
     * */
    public function getAccountTransactionFlightAllInfo($id,$moduleId)
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
                            JSON_OBJECT("reference", pnr.pnr, "airline", fd.airline, "firstName", pd.first_name, "surname", pd.surname, "ticketNumber", pd.ticket_number, "segmentNumber", fd.segment_number, "departureAirport", fd.departure_airport, "arrivalAirport", fd.arrival_airport,"operatingAirline", fd.operating_airline, "flightNumber", fd.flight_number, "cabin", fd.cabin, "flightDuration", fd.flight_duration, "type", fd.type, "amount", fi.price, "currency", fi.currency, "baseFare", fi.base_fare, "displayBaseFare", fi.display_base_fare, "taxes", fi.taxes, "displayTaxes", fi.display_taxes, "refundable", fi.refundable, "oneWay", fi.one_way, "multiDestination", fi.multi_destination) AS "details"
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
    public function getAccountTransactionDealAllInfo($id,$moduleId)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql  = 'SELECT
                        '.$moduleId.' AS "moduleId",
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

}