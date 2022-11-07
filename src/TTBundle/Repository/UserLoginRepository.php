<?php

namespace TTBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use TTBundle\Entity\CmsBag;
use TTBundle\Entity\CmsBagitem;
use TTBundle\Entity\CmsTubers;

class UserLoginRepository extends EntityRepository
{

    public function findAll()
    {
        return $this->createQueryBuilder('e')
            ->select('e')
            ->getQuery()
            ->getArrayResult();
    }

    public function LoginUserToken($login_token)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.uid = :Token')
            ->setParameter(':Token', $login_token)
            ->getQuery()
            ->getArrayResult();

        return $query;
    }


    public function userLoggedCheck($login_token)
    {

        $query = $this->createQueryBuilder('v')
            ->where("v.uid = :Login_Token and v.expiryDate>:date")
            ->setParameter(':Login_Token', $login_token)
            ->setParameter(':date', new \DateTime())
            ->setMaxResults(1)
            ->getQuery();
        $row = $query->getResult();
        return $row;
    }

    public function keepMeLooged($id)
    {
        $qb = $this->createQueryBuilder('UP')
            ->update('TTBundle:CmsTubers', 'v')
            ->set("v.expiryDate", ":date")
            ->where("v.id=:Id")
            ->setParameter(':date', new \DateTime('7 day'))
            ->setParameter(':Id', $id);
        $query = $qb->getQuery();
        $query->getResult();
        return $query;
    }

    public function createSession($deviceToken, $userId)
    {

    }

    public function settingSession($userid)
    {
        $qb = $this->createQueryBuilder('u')
            ->where("u.id = :Userid and u.published = 1")
            ->setParameter(':Userid', $userid)
            ->setMaxResults(1);
        $query = $qb->getQuery();
        $row = $query->getArrayResult();

        return $row;
    }

    public function endingSession($uid)
    {

        $qb = $this->createQueryBuilder('UP')
            ->delete('TTBundle:CmsTubers', 'v')
            ->where("v.uid = :Login_Token")
            ->setParameter(':Login_Token', $uid);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    public function checkUserBagItem($user_id, $item_id, $type)
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.userId=:User_id')
            ->AndWhere('u.itemId=:Item_id')
            ->AndWhere('u.type=:Type')
            ->setParameter(':User_id', $user_id)
            ->setParameter(':Item_id', $item_id)
            ->setParameter(':Type', $type)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
        if ($query != 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     *
     * This method handles retrieves user's bags list
     *
     * @param userId
     *
     * @return array()
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getUserBagList($userId)
    {
        $qb = $this->createQueryBuilder('cb')
            ->where('cb.userId = :userId')
            ->setParameter(':userId', $userId)
            ->getQuery();

        $result = $qb->getScalarResult();
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /*
     *
     * This method handles get bag item count
     *
     * @param userId
     * @param $bagId
     *
     * @return integer count
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getUserBagItemsCount($userId, $bagId)
    {
        $qb = $this->createQueryBuilder('cb')
            ->select('count(cb.id) as num')
            ->where('cb.userId = :userId')
            ->andWhere('cb.bagId = :bagId')
            ->setParameter(':userId', $userId)
            ->setParameter(':bagId', $bagId)
            ->getQuery();

        return $qb->getSingleScalarResult();
    }

    /*
     * This method handles get bag item list
     *
     * @param $userId
     * @param $bag_id
     * @param $entity_type
     *
     */
    public function userBagItemsList($user_id, $bag_id = 0, $entity_type = 0, $container)
    {
        $bag_id = intval($bag_id);

        $qb = $this->createQueryBuilder('b')
            ->select(' b, dp.id AS dp_id, dp.name AS dp_name, dp.address AS dp_address, dp.longitude AS dp_longitude, dp.latitude AS dp_latitude, dpi.filename AS dpi_filename, dptd.image AS dptd_image, dpw.name AS dpw_city, dps.stateName AS dps_state, dpc.name AS dpc_country, h.id AS h_id, h.name AS h_name, h.longitude AS h_longitude, h.latitude AS h_latitude, h.address AS h_address, h.stars AS h_stars, h.street AS h_street, hw.name AS hw_city, hws.stateName AS hws_state, hc.name AS hc_country, hi.filename AS hi_filename, hi.location AS hi_location, hhr.fromDate AS hhr_fromDate, hhr.toDate AS hhr_toDate, hhr.reference AS hhr_reference, hrh.id AS hrh_id, hrh.name AS hrh_name, hrh.longitude AS hrh_longitude, hrh.latitude AS hrh_latitude, hrh.address AS hrh_address, hrh.stars AS hrh_stars, hrh.street AS hrh_street, hrhw.name AS hrhw_city, hrhws.stateName AS hrhws_state, hrhc.name AS hrhc_country, hrhi.filename AS hrhi_filename, hrhi.location AS hrhi_location, hr.fromDate AS hr_fromDate, hr.toDate AS hr_toDate, hr.reference AS hr_reference, ar.id AS ar_id, ar.name AS ar_name, ar.city AS ar_city, ar.longitude AS ar_longitude, ar.latitude AS ar_latitude, ari.filename AS ari_filename, arw.name AS arw_city, ars.stateName AS ars_state, arc.name AS arc_country ');

        $qb->leftJoin('TTBundle:DiscoverPoi', 'dp', 'WITH', 'dp.id = b.itemId AND b.type=' . $container->getParameter('SOCIAL_ENTITY_LANDMARK') . '');
        $qb->leftJoin('TTBundle:DiscoverPoiImages', 'dpi', 'WITH', "dpi.poi = dp.id");
        $qb->leftJoin('TTBundle:CmsThingstodoDetails', 'dptd', 'WITH', "dptd.entityId = dp.id");
        $qb->leftJoin('TTBundle:Webgeocities', 'dpw', 'WITH', 'dpw.id = dp.cityId AND dp.cityId != 0 AND dp.cityId IS NOT NULL');
        $qb->leftJoin('TTBundle:CmsCountries', 'dpc', 'WITH', 'dpc.code=dpw.countryCode');
        $qb->leftJoin('TTBundle:States', 'dps', 'WITH', 'dps.countryCode=dpw.countryCode AND dps.stateCode=dpw.stateCode ');

        $qb->leftJoin('HotelBundle:CmsHotel', 'h', 'WITH', 'h.id = b.itemId AND b.type=' . $container->getParameter('SOCIAL_ENTITY_HOTEL') . '');
        $qb->leftJoin('HotelBundle:CmsHotelSource', 'hs', 'WITH', 'hs.hotelId = h.id AND hs.source=' . "'hrs'" . '');
        $qb->leftJoin('HotelBundle:HotelReservation', 'hhr', 'WITH', 'hhr.hotelId = h.id AND hhr.userId = :Userid AND (hhr.reservationStatus=' . "'Confirmed'" . ' OR hhr.reservationStatus=' . "'Modified'" . ')');
        $qb->leftJoin('HotelBundle:CmsHotelImage', 'hi', 'WITH', 'hi.hotelId = h.id AND hi.mediaTypeId =1');
        $qb->leftJoin('HotelBundle:CmsHotelCity', 'hchc', 'WITH', 'hs.locationId = hchc.locationId');
        $qb->leftJoin('TTBundle:Webgeocities', 'hw', 'WITH', 'hw.id = hchc.cityId AND hchc.cityId != 0 AND hchc.cityId IS NOT NULL');
        $qb->leftJoin('TTBundle:CmsCountries', 'hc', 'WITH', 'hc.code=hw.countryCode');
        $qb->leftJoin('TTBundle:States', 'hws', 'WITH', 'hws.countryCode=hw.countryCode AND hws.stateCode=hw.stateCode');

        $qb->leftJoin('HotelBundle:HotelReservation', 'hr', 'WITH', 'hr.id = b.itemId AND b.type=' . $container->getParameter('SOCIAL_ENTITY_HOTEL_RESERVATION') . '');
        $qb->leftJoin('HotelBundle:CmsHotel', 'hrh', 'WITH', 'hrh.id = hr.hotelId');
        $qb->leftJoin('HotelBundle:CmsHotelSource', 'hrhs', 'WITH', 'hrhs.hotelId = hrh.id AND hrhs.source=' . "'hrs'" . '');
        $qb->leftJoin('HotelBundle:CmsHotelCity', 'hrchc', 'WITH', 'hrhs.locationId = hrchc.locationId');

        $qb->leftJoin('HotelBundle:CmsHotelImage', 'hrhi', 'WITH', 'hrhi.hotelId = hrh.id AND hrhi.mediaTypeId =1');
        $qb->leftJoin('TTBundle:Webgeocities', 'hrhw', 'WITH', 'hrhw.id = hrchc.cityId AND hrchc.cityId != 0 AND hrchc.cityId IS NOT NULL');
        $qb->leftJoin('TTBundle:CmsCountries', 'hrhc', 'WITH', 'hrhc.code=hrhw.countryCode');
        $qb->leftJoin('TTBundle:States', 'hrhws', 'WITH', 'hrhws.countryCode=hrhw.countryCode AND hrhws.stateCode=hrhw.stateCode');


        $qb->leftJoin('TTBundle:Airport', 'ar', 'WITH', "ar.id = b.itemId AND b.type=" . $container->getParameter('SOCIAL_ENTITY_AIRPORT') . "")
            ->leftJoin('TTBundle:AirportImages', 'ari', 'WITH', "ari.airportId = ar.id")
            ->leftJoin('TTBundle:Webgeocities', 'arw', 'WITH', 'arw.id=ar.cityId AND ar.cityId>0')
            ->leftJoin('TTBundle:States', 'ars', 'WITH', 'ars.countryCode=arw.countryCode AND ars.stateCode=arw.stateCode')
            ->leftJoin('TTBundle:CmsCountries', 'arc', 'WITH', 'arc.code=arw.countryCode ');

        $qb->where("b.userId = :Userid")
            ->setParameter(':Userid', $user_id);
        if ($bag_id > 0) {
            $qb->andwhere("b.bagId=:Bag_id");
            $qb->setParameter(':Bag_id', $bag_id);
        }
        if ($entity_type > 0) {
            $qb->andwhere("b.type=:Entity_type");
            $qb->setParameter(':Entity_type', $entity_type);
        }
        $qb->orderBy('hi.defaultPic,ari.defaultPic', 'DESC');
        $qb->groupby("b.id");
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
     *
     * This method handles get bag info
     *
     * @param bagId
     *
     * @return baginfo array
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getBagInfo($bagId)
    {
        $qb = $this->createQueryBuilder('cb')
            ->where('cb.id = :bagId')
            ->setParameter(':bagId', $bagId)
            ->getQuery();

        $result = $qb->getScalarResult();
        if (!empty($result)) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
     * Get Bag Item Info
     *
     * @param itemId
     *
     * @return array
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function getBagItemInfo($itemId)
    {
        $qb = $this->createQueryBuilder('cbi')
            ->where('cbi.id = :itemId')
            ->setParameter(':itemId', $itemId)
            ->getQuery();

        $result = $qb->getScalarResult();
        if (!empty($result)) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
     * Adding a new bag
     *
     * @param userId
     * @param name
     *
     * @return bagId
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */
    public function addBagNew($userId, $name, $params = null)
    {
        $em = $this->getEntityManager();
        $createdAt = new \DateTime("now");

        $bag = new CmsBag();
        $bag->setName($name);
        $bag->setUserId($userId);
        $bag->setCreateTs($createdAt);

        if ($params != null && $params['imgpath'] != '' && $params['imgname'] != '') {
            $bag->setImgpath($params['imgpath']);
            $bag->setImgname($params['imgname']);
        }

        $em->persist($bag);
        $em->flush();

        if ($bag->getId()) {
            return $bag->getId();
        } else {
            return false;
        }
    }

    /*
     * Adding a new bag item
     *
     * @param userId
     * @param type
     * @param bag_id
     * @param item_id
     *
     * @return bagitemId
    */
    public function addBagItemNew($params)
    {
        $user_id = $params['user_id'];
        $bag_id = $params['bagId'];
        $item_id = $params['itemId'];
        $type = $params['entityType'];
        $imgpath = $params['imgpath'];
        $imgname = $params['imgname'];

        if ($imgpath != '' && $imgname != '') {
            //updating the CmsBag
            $qb1 = $this->createQueryBuilder('u')
                ->update('TTBundle:CmsBag', 'cb')
                ->set('cb.imgname', ':imgname')
                ->set('cb.imgpath', ':imgpath')
                ->where('cb.id = :bagId')
                ->setParameter(':bagId', $bag_id)
                ->setParameter(':imgname', $imgname)
                ->setParameter(':imgpath', $imgpath)
                ->getQuery()
                ->getResult();
        }

        //checking if item exists
        $select = $this->createQueryBuilder('u')
            ->from('TTBundle:CmsBagitem', 'cbi')
            ->where('cbi.bagId=:bagId')
            ->AndWhere('cbi.itemId=:itemId')
            ->AndWhere('cbi.type=:type')
            ->AndWhere('cbi.userId=:UserId')
            ->setParameter(':bagId', $bag_id)
            ->setParameter(':itemId', $item_id)
            ->setParameter(':type', $type)
            ->setParameter(':UserId', $user_id)
            ->getQuery();

        $result = $select->getScalarResult();

        if (!empty($result)) {
            return true;
        } else {
            $em = $this->getEntityManager();

            $item = new CmsBagitem();
            $item->setType($type);
            $item->setUserId($user_id);
            $item->setBagId($bag_id);
            $item->setItemId($item_id);
            $em->persist($item);
            $em->flush();

            if ($item->getId()) {
                return $item->getId();
            } else {
                return false;
            }
        }
    }

    /*
     * Edit Bag information
     *
     * @param array params
     *
     * @return bool
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function editBagInfo($params = array())
    {
        $qb = $this->createQueryBuilder('u');
        $query = $qb->update('TTBundle:CmsBag cb')
            ->set('cb.name', ':name')
            ->where('cb.id = :bagId AND cb.userId = :user_id')
            ->setParameter(':user_id', $params['user_id'])
            ->setParameter(':name', $params['name'])
            ->setParameter(':bagId', $params['id'])
            ->getQuery()
            ->getResult();
        return $qb;
    }

    /**
     * Update bag item for New Design
     *
     */
    public function updateBagItemInfo($params = array())
    {
        if ($params['imgname'] != '' && $params['imgpath'] != '') {
            //updating the CmsBag
            $qb1 = $this->createQueryBuilder('u')
                ->update('TTBundle:CmsBag', 'cb')
                ->set('cb.imgname', ':imgname')
                ->set('cb.imgpath', ':imgpath')
                ->where('cb.id = :bagId')
                ->setParameter(':bagId', $params['bagId'])
                ->setParameter(':imgname', $params['imgname'])
                ->setParameter(':imgpath', $params['imgpath'])
                ->getQuery()
                ->getResult();
        }

        //checking if item exists
        $select = $this->createQueryBuilder('u')
            ->from('TTBundle:CmsBagitem', 'cbi')
            ->where('cbi.bagId=:bagId')
            ->AndWhere('cbi.itemId=:itemId')
            ->AndWhere('cbi.type=:type')
            ->setParameter(':bagId', $params['bagId'])
            ->setParameter(':itemId', $params['itemId'])
            ->setParameter(':type', $params['entityType'])
            ->getQuery();

        $result = $select->getScalarResult();

        if (!empty($result)) {
            //deleting the cms_bagitem
            $qb3 = $this->createQueryBuilder('u')
                ->delete('TTBundle:CmsBagitem', 'ct')
                ->where('ct.id = :id')
                ->setParameter(':id', $params['id'])
                ->getQuery()
                ->getResult();
        } else {
            $qb4 = $this->createQueryBuilder('u')
                ->update('TTBundle:CmsBagitem', 'cb')
                ->set('cb.bagId', ':bagId')
                ->where('cb.id = :id')
                ->setParameter(':bagId', $params['bagId'])
                ->setParameter(':id', $params['id'])
                ->getQuery()
                ->getResult();
        }
        return true;
    }

    /*
     * Delete Bag Item information
     *
     * @param id
     * @param $userId
     * @return bool
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function deleteBagItem($id, $userId)
    {
        $bagItemInfo = $this->getBagItemInfo($id);

        //deleting the cms_bagitem
        $qb1 = $this->createQueryBuilder('u')
            ->delete('TTBundle:CmsBagitem', 'ct')
            ->where('ct.id = :id')
            ->andWhere('ct.userId = :userId')
            ->setParameter(':id', $id)
            ->setParameter(':userId', $userId)
            ->getQuery()
            ->getResult();

        //if there's no item in the bag left, we need to delete the bag per old implementation (userBagItemDelete: bag.php)
        $bagExist = $this->createQueryBuilder('u')
            ->from('TTBundle:CmsBagitem', 'cbi')
            ->where('cbi.bagId=:bagId')
            ->setParameter(':bagId', $bagItemInfo['cbi_id'])
            ->getQuery();

        $result = $bagExist->getScalarResult();

        if (empty($result)) {
            //deleting the bag itself
            $qb2 = $this->createQueryBuilder('u')
                ->delete('TTBundle:CmsBag', 'cb')
                ->where('cb.id = :id')
                ->setParameter(':id', $bagItemInfo['cbi_id'])
                ->getQuery()
                ->getResult();
        }
        return true;
    }

    /*
     * Delete Bag information
     *
     * @param id
     * @param $entityType
     * @return bool
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function deleteBag($id, $user_id, $entityType)
    {
        //deleting the cms_bagitem
        $qb1 = $this->createQueryBuilder('u')
            ->delete('TTBundle:CmsBagitem', 'ct')
            ->where('ct.bagId = :bagId AND ct.userId = :user_id')
            ->setParameter(':bagId', $id)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //deleting the bag itself
        $qb2 = $this->createQueryBuilder('u')
            ->delete('TTBundle:CmsBag', 'cb')
            ->where('cb.id = :id AND cb.userId = :user_id')
            ->setParameter(':id', $id)
            ->setParameter(':user_id', $user_id);

        $query = $qb2->getQuery();
        return $query->getResult();
    }

    /*
     * This method is used to enter seession to CmsTubers
     * @param array
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userToSession($params = array())
    {
        if (!isset($params['uid']) || empty($params['uid'])) {
            return false;
        }

        $qb = $this->createQueryBuilder('u')
            ->delete('TTBundle:CmsTubers', 'ct')
            ->where('ct.uid = :uid')
            ->setParameter(':uid', $params['uid'])
            ->getQuery()
            ->getResult();

        if (isset($params['keepMeLogged']) && $params['keepMeLogged'] == 1) {
            $expiryDate = new \DateTime("7 day");
        } else {
            $expiryDate = new \DateTime("2 day");
        }

        $em = $this->getEntityManager();
        $tubers = new CmsTubers();

        $tubers->setUid($params['uid']);
        $tubers->setUserId($params['userId']);
        $tubers->setClientType($params['clientType']);
        $tubers->setLogTs(new \DateTime("now"));
        $tubers->setExpiryDate($expiryDate);
        $tubers->setKeepMeLogged($params['keepMeLogged']);
        $tubers->setSocialToken($params['socialToken']);

        $em->persist($tubers);
        $em->flush();

        if ($tubers) {
            return $tubers;
        } else {
            return false;
        }
    }

    /*
     * This method gets deletes entry in cms tubers
     * @param array @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userEndSession($uid)
    {
        $qb = $this->createQueryBuilder('u')
            ->delete('TTBundle:CmsTubers', 'v')
            ->where("v.uid = :Login_Token")
            ->setParameter(':Login_Token', $uid);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
     * This method is used to update users longitude & latitude in CmsUsers
     * @param array
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userSetProfilePosition($params = array())
    {
        $em = $this->getEntityManager();
        $cuObj = $em->getRepository('TTBundle:CmsUsers')->findOneById($params['userId']);

        if (!isset($cuObj) || empty($cuObj)) {
            return false;
        }

        if (isset($params['longitude'])) {
            $cuObj->setLongitude($params['longitude']);
        }
        if (isset($params['latitude'])) {
            $cuObj->setLatitude($params['latitude']);
        }

        $em->flush();

        if ($cuObj) {
            return $cuObj;
        } else {
            return false;
        }
    }
}
