<?php

namespace TTBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TTBundle\Entity\CmsReport;
use TTBundle\Entity\CmsUsers;
use TTBundle\Entity\CmsNotificationsEmails;
use TTBundle\Entity\CmsUserGroup;
use TTBundle\Entity\CmsFriends;
use TTBundle\Entity\CmsSubscriptions;
use TTBundle\Entity\CmsUsersPrivacy;
use TTBundle\Entity\CmsUsersPrivacyExtand;
use TTBundle\Entity\CmsVideosTemp;
use TTBundle\Entity\CmsVideos;
use TTBundle\Entity\CmsUsersCatalogs;
use TTBundle\Entity\CmsUsersEvent;
use TTBundle\Entity\CmsJournals;
use TTBundle\Entity\CmsSocialShares;
use TTBundle\Entity\CmsSocialRatings;
use TTBundle\Entity\CmsSocialPosts;
use TTBundle\Entity\CmsSocialLikes;
use TTBundle\Entity\CmsSocialComments;
use TTBundle\Entity\CmsUsersEventJoin;
use TTBundle\Entity\CmsUsersVisitedPlaces;
use TTBundle\Entity\DiscoverHotelsReviews;
use TTBundle\Entity\DiscoverPoiReviews;
use TTBundle\Entity\AirportReviews;
use TTBundle\Entity\CmsSocialNewsfeed;
use TTBundle\Entity\CmsTubersLoginTracking;
use TTBundle\Entity\CmsMobileToken;
use TTBundle\Entity\CmsTubers;
use TTBundle\Entity\CmsChannel;
use RestBundle\Entity\OauthAccessToken;
use RestBundle\Entity\OauthRefreshToken;
use CorporateBundle\Entity\CorpoUserProfiles;

class UserRepository extends EntityRepository
{


    /**
     * Generate Password
     * @param array (password)
     * @return array $results
     */
    public function generatePassword($params)
    {
        if (empty($params)) {
            return;
        }

        try {
            $conn = $this->getEntityManager()->getConnection();
            $sql = "SELECT password(?) as yourPass FROM cms_users";
            $stmt = $conn->prepare($sql);
            $stmt->execute(array($params));

            $result = $stmt->fetchAll();
        } catch (\Exception $e) {
            return false;
        }
        return $result;
    }

    /**
     * Generate users list
     * @param array
     * @return doctrine object
     */
    public function generateUser($params = array())
    {
        $em = $this->getEntityManager();
        $lastModified = new \DateTime("now");
        $from_activate = (isset($params['from_activate']) && $params['from_activate'] != '') ? $params['from_activate'] : 0;
        $user = new CmsUsers();

        $user->setLastModified($lastModified);
        $user->setRegistereddate($lastModified);

        if (isset($params['password']) && !empty($params['password'])) {
            $user->setPassword($params['password']);
        }
        if (isset($params['yourPassword']) && !empty($params['yourPassword'])) {
            $user->setYourpassword($params['yourPassword']);
        }
        if (isset($params['fullName']) && !empty($params['fullName'])) {
            $user->setFullname($params['fullName']);
        }
        if (isset($params['fname']) && !empty($params['fname'])) {
            $user->setFname($params['fname']);
        }
        if (isset($params['lname']) && !empty($params['lname'])) {
            $user->setLname($params['lname']);
        }
        if (isset($params['gender']) && !empty($params['gender'])) {
            $user->setGender($params['gender']);
        }
        if (isset($params['fb_token']) && !empty($params['fb_token'])) {
            $user->setFbToken($params['fb_token']);
        }
        if (isset($params['fb_user']) && !empty($params['fb_user'])) {
            $user->setFbUser($params['fb_user']);
        }
        if (isset($params['yourEmail']) && !empty($params['yourEmail'])) {
            $user->setYouremail($params['yourEmail']);
        }
        if (isset($params['yourIP']) && !empty($params['yourIP'])) {
            $user->setYourIP($params['yourIP']);
        }
        if (isset($params['yourBday']) && !empty($params['yourBday'])) {
            $user->setYourbday(new \DateTime($params['yourBday']));
        }
        if (isset($params['yourUserName']) && !empty($params['yourUserName'])) {
            $user->setYourUserName($params['yourUserName']);
        }
        if (isset($params['defaultPublished'])) {
            $user->setPublished($params['defaultPublished']);
        }
        if (isset($params['isChannel']) && !empty($params['isChannel'])) {
            $user->setIsChannel($params['isChannel']);
        }
        if (isset($params['yourCountry']) && !empty($params['yourCountry'])) {
            $user->setYourCountry($params['yourCountry']);
        }
        if (isset($params['accountId']) && !empty($params['accountId'])) {
            $user->setCorpoAccountId($params['accountId']);
        }
        if (isset($params['salt']) && !empty($params['salt'])) {
            $user->setSalt($params['salt']);
        }
        if (isset($params['isCorporateAccount']) && !empty($params['isCorporateAccount'])) {
            $user->setIsCorporateAccount($params['isCorporateAccount']);
        }
        if (isset($params['cmsUserGroupId']) && !empty($params['cmsUserGroupId'])) {
            $groupObj = $this->getEntityManager()->getRepository('TTBundle:CmsUserGroup')->findOneById($params['cmsUserGroupId']);
            $user->setCmsUserGroup($groupObj);
        }
        if (isset($params['corpoUserProfileId']) && !empty($params['corpoUserProfileId'])) {
            $user->setCorpoUserProfileId($params['corpoUserProfileId']);
        }
        if (isset($params['accessToSubAccount']) && !empty($params['accessToSubAccount'])) {
            $user->setAllowAccessSubAcc(1);
        }
        if (isset($params['parentUserId']) && !empty($params['parentUserId'])) {
            $user->setParentUserId($params['parentUserId']);
        }
        if (isset($params['allowApprove']) && !empty($params['allowApprove'])) {
            $user->setAllowApproval(1);
        } else {
            $user->setAllowApproval(0);
        }
        $em->persist($user);
        $em->flush();
        if ($user) {
            $usid = $user->getId();
            $this->AddUserPrivacy($usid);
            if ($from_activate == 0)
                return $user;
        } else {
            return false;
        }
    }

    /**
     * add report for a givent entity type
     * @return integer | false the newly inserted cms_report id or false if not inserted
     */
    public function addReportData($options)
    {
        $em = $this->getEntityManager();
        $createTs = new \DateTime("now");
        $report = new CmsReport();

        $report->setCreateTs($createTs);

        $report->setUserId(0);
        $report->setOwnerId(0);
        $report->setEntityId(0);
        $report->setEntityType(0);
        $report->setChannelId(0);
        $report->setMsg('');
        $report->setReason('');
        $report->setTitle('');
        $report->setEmail('');

        if (isset($options['user_id']) && !empty($options['user_id'])) {
            $report->setUserId($options['user_id']);
        }

        if (isset($options['owner_id']) && !empty($options['owner_id'])) {
            $report->setOwnerId($options['owner_id']);
        }

        if (isset($options['entity_id']) && !empty($options['entity_id'])) {
            $report->setEntityId($options['entity_id']);
        }

        if (isset($options['entity_type']) && !empty($options['entity_type'])) {
            $report->setEntityType($options['entity_type']);
        }

        if (isset($options['channel_id']) && !empty($options['channel_id'])) {
            $report->setChannelId($options['channel_id']);
        }

        if (isset($options['msg']) && !empty($options['msg'])) {
            $report->setMsg($options['msg']);
        }

        if (isset($options['reason']) && !empty($options['reason'])) {
            $report->setReason($options['reason']);
        }

        if (isset($options['title']) && !empty($options['title'])) {
            $report->setTitle($options['title']);
        }

        if (isset($options['email']) && !empty($options['email'])) {
            $report->setEmail($options['email']);
        }

        $em->persist($report);
        $em->flush();
        if ($report) {
            return $report->getId();
        } else {
            return false;
        }
    }

    /**
     * insert the default user privacy given the user id
     * @param integer $user_id the desired user id
     * @return true the cms_users_privacy record or false if not found
     */
    public function AddUserPrivacy($user_id)
    {
        $qb = $this->createQueryBuilder('dd')
            ->from('TTBundle:CmsUsersPrivacy', 'cup')
            ->where('cup.userId = :user_id')
            ->setParameter(':user_id', $user_id)
            ->getQuery();


        $result = $qb->getScalarResult();

        if (empty($result)) {
            $em = $this->getEntityManager();
            $cup = new CmsUsersPrivacy();

            $cup->setUserId($user_id);
            $em->persist($cup);
            $em->flush();

            return $cup;
        } else {
            return false;
        }
    }

    /*
     * This method should return the list of users using the service from the database.
     * This should get a general list of user or even by specific criteria to be sent as param using filter param like : filter=role value=corp...
     *
     * @param $criteria array('account','role')
     * @param $values   Associative array('accountsIds' => array(1,7,5) , 'rolesNames' => array('UserRole', 'AdminRole' , 'CorporateRole', ... ) )
     */

    public function getUsersList($criteria = array(), $values = array())
    {
        $query = $this->createQueryBuilder('cu')
            ->select('cu, ca.name as accountName')
            ->leftJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', "ca.id = cu.corpoAccountId")
            ->where('cu.published =1');

        if (in_array('account', $criteria)) {
            $query->andWhere("ca.path LIKE :accountIds")
                ->setParameter(':accountIds', '%,' . $values['accountId'] . ',%');
        }

        if (in_array('role', $criteria)) {
            $query->InnerJoin('TTBundle:CmsUserGroup', 'cug', 'WITH', 'cu.cmsUserGroupId = cug.id AND cug.role IN (:roles)')->setParameter(':roles', $values['roleNames']);
        }

        if (in_array('userProfile', $criteria)) {
            $query->select('cu, ca.name as accountName, up.sectionTitle, up.level')
                ->InnerJoin('CorporateBundle:CorpoUserProfiles', 'up', 'WITH', 'up.id = cu.corpoUserProfileId');
        }

        if ($values['slug'] != "") {
            $query->andWhere('up.slug = :slug')
                ->setParameter(':slug', $values['slug']);
        }

        $quer = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * delete a user and all his media
     * @param integer $id the cms_users id
     * @return boolean success or fail
     */
    public function deleteUser($id)
    {
        $published = -2;
        $lastModified = new \DateTime("now");
        $qb = $this->createQueryBuilder('u');
        $query = $qb->update('TTBundle:CmsUsers cu')
            ->set('cu.published', ':published')
            ->set('cu.lastModified', ':lastModified')
            ->Where('cu.id = :id')
            ->setParameter('published', $published)
            ->setParameter('lastModified', $lastModified)
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        if ($query) {// if user is removed
            $this->userDeleteDeactivateEntities($id, 'delete');
            return true;
        } else {// if user is not removed
            return false;
        }
    }

    /**
     * delete all user's entities
     * @param integer $user_id the cms_users id
     */
    public function userDeleteDeactivateEntities($user_id, $action)
    {
        //temp value PLS CHANGE THESE VALUE
        $social_share_sponsor = 0;
        if ($action == 'delete') $published = -2; else if ($action == 'deactivate') $published = -1; else $published = 1;

        //$query1   = "UPDATE `cms_friends` SET published = -2 WHERE published = 1 AND `requester_id`=:User_id OR `receipient_id`=:User_id";
        $query1 = $this->createQueryBuilder('u')->update('TTBundle:CmsFriends cf')
            ->set('cf.published', ':published')
            ->where('cf.published = 1')
            ->andWhere('(cf.requesterId = :user_id OR cf.receipientId = :user_id)')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query2   = "UPDATE `cms_subscriptions` SET published = -2 WHERE published = 1 `user_id`=:User_id OR `subscriber_id`=:User_id";
        $query2 = $this->createQueryBuilder('u')->update('TTBundle:CmsSubscriptions cs')
            ->set('cs.published', ':published')
            ->where('cs.published = 1')
            ->andWhere('(cs.userId = :user_id OR cs.subscriberId = :user_id)')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        if ($action == 'delete') {
            //$query3   = "DELETE FROM `cms_users_privacy_extand` WHERE `user_id`=:User_id"
            $qb3 = $this->createQueryBuilder('u');
            $qb3->delete('TTBundle:CmsUsersPrivacyExtand', 'cupe');
            $qb3->where('cupe.userId = :user_id');
            $qb3->setParameter(':user_id', $user_id);

            //$query4   = "DELETE FROM `cms_videos_temp` WHERE `user_id`=:User_id";
            $qb4 = $this->createQueryBuilder('u');
            $qb4->delete('TTBundle:CmsVideosTemp', 'cvt');
            $qb4->where('cvt.userId = :user_id');
            $qb4->setParameter(':user_id', $user_id);
        }

        //$query5   = "UPDATE cms_videos SET published=".MEDIA_DELETE." WHERE userid=:User_id";
        $query5 = $this->createQueryBuilder('u')->update('TTBundle:CmsVideos cv')
            ->set('cv.published', ':published')
            ->where('cv.userid = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query6   = "UPDATE cms_users_catalogs SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query6 = $this->createQueryBuilder('u')->update('TTBundle:CmsUsersCatalogs cuc')
            ->set('cuc.published', ':published')
            ->where('cuc.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query7   = "UPDATE cms_users_event SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query7 = $this->createQueryBuilder('u')->update('TTBundle:CmsUsersEvent cue')
            ->set('cue.published', ':published')
            ->where('cue.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query8   = "UPDATE cms_journals SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query8 = $this->createQueryBuilder('u')->update('TTBundle:CmsJournals cj')
            ->set('cj.published', ':published')
            ->where('cj.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query9   = "UPDATE cms_social_shares SET published=".MEDIA_DELETE." WHERE from_user=:User_id AND share_type<>".SOCIAL_SHARE_TYPE_SPONSOR."";
        $query9 = $this->createQueryBuilder('u')->update('TTBundle:CmsSocialShares css')
            ->set('css.published', ':published')
            ->where('css.fromUser = :user_id')
            ->andWhere('css.shareType = :shareType')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->setParameter(':shareType', $social_share_sponsor)
            ->getQuery()
            ->getResult();

        //$query10  = "UPDATE cms_social_ratings SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query10 = $this->createQueryBuilder('u')->update('TTBundle:CmsSocialRatings csr')
            ->set('csr.published', ':published')
            ->where('csr.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query11  = "UPDATE cms_social_posts SET published=".MEDIA_DELETE." WHERE (user_id=:User_id' OR from_id=:User_id) AND channel_id=0";
        $query11 = $this->createQueryBuilder('u')->update('TTBundle:CmsSocialPosts csp')
            ->set('csp.published', ':published')
            ->where('(csp.userId = :user_id OR csp.fromId = :user_id) AND csp.channelId = 0')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query12 = "UPDATE cms_social_likes SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query12 = $this->createQueryBuilder('u')->update('TTBundle:CmsSocialLikes csl')
            ->set('csl.published', ':published')
            ->where('csl.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query13 = "UPDATE cms_social_comments SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query13 = $this->createQueryBuilder('u')->update('TTBundle:CmsSocialComments csc')
            ->set('csc.published', ':published')
            ->where('csc.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query17 = "UPDATE cms_users_event_join SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query17 = $this->createQueryBuilder('u')->update('TTBundle:CmsUsersEventJoin cuej')
            ->set('cuej.published', ':published')
            ->where('cuej.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query18  = "UPDATE cms_users_visited_places SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query18 = $this->createQueryBuilder('u')->update('TTBundle:CmsUsersVisitedPlaces cuvp')
            ->set('cuvp.published', ':published')
            ->where('cuvp.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query19  = "UPDATE discover_hotels_reviews SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query19 = $this->createQueryBuilder('u')->update('TTBundle:DiscoverHotelsReviews dhr')
            ->set('dhr.published', ':published')
            ->where('dhr.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query20  = "UPDATE discover_poi_reviews SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query20 = $this->createQueryBuilder('u')->update('TTBundle:DiscoverPoiReviews dpr')
            ->set('dpr.published', ':published')
            ->where('dpr.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query22 = "UPDATE airport_reviews SET published=".MEDIA_DELETE." WHERE user_id=:User_id";
        $query22 = $this->createQueryBuilder('u')->update('TTBundle:AirportReviews ar')
            ->set('ar.published', ':published')
            ->where('ar.userId = :user_id')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        //$query23 = "UPDATE cms_social_newsfeed SET published=".MEDIA_DELETE." WHERE ( (user_id=:User_id AND owner_id<>:User_id AND action_type<>".SOCIAL_SHARE_TYPE_SPONSOR.") OR (owner_id=:User_id AND COALESCE( channel_id,0)=0) )";
        $query23 = $this->createQueryBuilder('u')->update('TTBundle:CmsSocialNewsfeed snf')
            ->set('snf.published', ':published')
            ->where('(snf.userId = :user_id AND snf.ownerId <> :user_id AND snf.actionType <> :social_share_sponsor)')
            ->Orwhere('(snf.ownerId = :user_id AND COALESCE( snf.channelId,0)=0)')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->setParameter(':social_share_sponsor', $social_share_sponsor)
            ->getQuery()
            ->getResult();

        //$query24 = "UPDATE cms_social_newsfeed SET published=".MEDIA_DELETE." WHERE (user_id=:User_id OR owner_id=:User_id) AND COALESCE( channel_id,0)=0";
        $query23 = $this->createQueryBuilder('u')->update('TTBundle:CmsSocialNewsfeed snf')
            ->set('snf.published', ':published')
            ->where('(snf.userId = :user_id OR snf.ownerId=:user_id)')
            ->Andwhere('COALESCE( snf.channelId,0)=0')
            ->setParameter(':published', $published)
            ->setParameter(':user_id', $user_id)
            ->getQuery()
            ->getResult();

        return true;
    }

    public function updateUserPassword($params = array())
    {
        $qb = $this->createQueryBuilder('u')
            ->update('TTBundle:CmsUsers cu')
            ->set('cu.password', ':password')
            ->set('cu.yourpassword', ':yourpassword')
            ->set('cu.salt', ':salt')
            ->Where('cu.id = :id')
            ->setParameter(':yourpassword', $params['yourpassword'])
            ->setParameter(':password', $params['password'])
            ->setParameter(':salt', $params['salt'])
            ->setParameter(':id', $params['userId']);
        $query = $qb->getQuery();
        $query->getResult();
        return $query;
    }

    /**
     * add the emails notifications
     * @param string $email the email
     * @param integer $notify 1 or 0 if false
     * @return true | false
     */
    public function addNotificationsEmails($email, $notify)
    {

        $row = $this->getNotificationsEmails($email);

        if ($row) {
            $qb = $this->createQueryBuilder('n')
                ->update('TTBundle:CmsNotificationsEmails n')
                ->set('n.notify', ':Notify')
                ->Where('n.id = :id')
                ->setParameter(':Notify', $notify)
                ->setParameter(':id', $row['n_id']);
            $query = $qb->getQuery();
            $query->getResult();
            return $query;
        } else {
            $em = $this->getEntityManager();
            $items = new CmsNotificationsEmails();
            $items->setEmail($email);
            $items->setNotify($notify);
            $em->persist($items);
            $em->flush();
            if ($items->getId()) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*
     * This method is used to return user info from loging credentials
     * @param string username
     * @param string password
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getUserInfo($username = '', $password = '')
    {
        if (empty($username) || empty($password)) {
            return false;
        }
        $qb = $this->createQueryBuilder('u')
            ->where('u.youremail = :Username OR u.yourusername = :Username')
            ->andWhere('u.yourpassword = :Pswd')
            ->andWhere('u.published = 1')
            ->setParameter(':Username', $username)
            ->setParameter(':Pswd', $password);
        $query = $qb->getQuery();
        $result = $query->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
     * This method is used to return user info from loging credentials
     * @param string username
     * @param string password
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getUserInfoById($id)
    {
        $query = $this->createQueryBuilder('cu')
            ->select('cu, w.name AS w_name, c.name AS c_name')
            ->leftJoin('TTBundle:Webgeocities', 'w', 'WITH', 'w.id=cu.cityId')
            ->leftJoin('TTBundle:CmsCountries', 'c', 'WITH', 'c.code=cu.yourcountry')
            ->where('cu.id = :ID')
            ->andWhere('cu.published = 1')
            ->setParameter(':ID', $id);
        $quer = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * get notification by email
     * @param string $email the email
     * @return record | false
     */
    public function getNotificationsEmails($email)
    {
        $query = $this->createQueryBuilder('ne')
            ->select('n')
            ->from("TTBundle:CmsNotificationsEmails", 'n')
            ->where('n.email like :Email')
            ->setParameter(':Email', $email);

        $quer = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
     * This method is used to edit user info
     * @param array
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function modifyUser($params = array())
    {
        $em = $this->getEntityManager();
        $cuObj = $em->getRepository('TTBundle:CmsUsers')->findOneById($params['id']);
        $datetime = new \DateTime();

        if (!isset($cuObj) || empty($cuObj)) {
            return false;
        }
        if (isset($params['yourEmail']) && !empty($params['yourEmail'])) {
            $cuObj->setYouremail($params['yourEmail']);
        }

        if (isset($params['yourUserName']) && !empty($params['yourUserName'])) {
            $cuObj->setYourusername($params['yourUserName']);
        }
        if (isset($params['fname']) || isset($params['lname'])) {
            $fullname = $params['fname'] . ' ' . $params['lname'];
            $cuObj->setFullname($fullname);
        }
        if (isset($params['fname'])) {
            $cuObj->setFname($params['fname']);
        }
        if (isset($params['lname'])) {
            $cuObj->setLname($params['lname']);
        }
        if (isset($params['websiteUrl'])) {
            $cuObj->setWebsiteUrl($params['websiteUrl']);
        }
        if (isset($params['profilePic'])) {
            $cuObj->setProfilePic($params['profilePic']);
        }
        if (isset($params['profileId'])) {
            $cuObj->setProfileId($params['profileId']);
        }
        if (isset($params['smallDescription'])) {
            $cuObj->setSmallDescription($params['smallDescription']);
        }
        if (isset($params['employment'])) {
            $cuObj->setEmployment($params['employment']);
        }
        if (isset($params['high_education'])) {
            $cuObj->setHighEducation($params['high_education']);
        }
        if (isset($params['intrested_in'])) {
            $cuObj->setIntrestedIn($params['intrested_in']);
        }
        if (isset($params['gender'])) {
            $cuObj->setGender($params['gender']);
        }
        if (isset($params['yourBday'])) {
            $cuObj->setYourbday($datetime->createFromFormat('Y-m-d', $params['yourBday']));
        }
        if (isset($params['displayAge'])) {
            $cuObj->setDisplayAge($params['displayAge']);
        }
        if (isset($params['displayYearAge'])) {
            $cuObj->setDisplayYearage($params['displayYearAge']);
        }
        if (isset($params['cityId'])) {
            $cuObj->setCityId($params['cityId']);
        }
        if (isset($params['city'])) {
            $cuObj->setCity($params['city']);
        }
        if (isset($params['hometown'])) {
            $cuObj->setHometown($params['hometown']);
        }
        if (isset($params['yourCountry'])) {
            $cuObj->setYourcountry($params['yourCountry']);
        }
        if (isset($params['displayGender'])) {
            $cuObj->setDisplayGender($params['displayGender']);
        }
        if (isset($params['isCorporateAccount']) && !empty($params['isCorporateAccount'])) {
            $cuObj->setIsCorporateAccount($params['isCorporateAccount']);
        }
        if (isset($params['accountId']) && !empty($params['accountId'])) {
            $cuObj->setCorpoAccountId($params['accountId']);
        }
        if (isset($params['cmsUserGroupId']) && !empty($params['cmsUserGroupId'])) {
            $groupObj = $this->getEntityManager()->getRepository('TTBundle:CmsUserGroup')->findOneById($params['cmsUserGroupId']);
            $cuObj->setCmsUserGroup($groupObj);
        }
        if (isset($params['published'])) {
            $cuObj->setPublished($params['published']);
        }
        if (isset($params['corpoUserProfileId']) && is_numeric($params['corpoUserProfileId'])) {
            $cuObj->setCorpoUserProfileId($params['corpoUserProfileId']);
        }
        if (isset($params['accessToSubAccount'])) {
            $cuObj->setAllowAccessSubAcc($params['accessToSubAccount']);
        }
        if (isset($params['accessToSubAccountUsers'])) {
            $cuObj->setAllowAccessSubAccUsers($params['accessToSubAccountUsers']);
        }
        if (isset($params['allowApprove']) && $params['allowApprove']) {
            $cuObj->setAllowApproval(1);
        } else {
            $cuObj->setAllowApproval(0);
        }
        $em->persist($cuObj);
        $em->flush();

        if ($cuObj) {
            return $cuObj;
        } else {
            return false;
        }
    }

    /*
     * This method is used to edit user info
     * @param array
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function modifyUserByEmail($params = array())
    {
        $this->em = $this->getEntityManager();
        $qb = $this->em->createQueryBuilder('ca')
            ->update('TTBundle:CmsUsers', 'ca');
        if (isset($parameters['accountId'])) {
            $qb->set("ca.accountId", ":accountId")
                ->setParameter(':accountId', $parameters['accountId']);
        }
        if (isset($params['isCorporateAccount']) && !empty($params['isCorporateAccount'])) {
            $qb->set("ca.isCorporateAccount", ":isCorporateAccount")
                ->setParameter(':isCorporateAccount', $parameters['isCorporateAccount']);
        }
        $qb->where("ca.youremail=:yourEmail")
            ->andwhere("ca.published=1")
            ->setParameter(':yourEmail', $parameters['yourEmail']);

        $query = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    /*
     * This method is used to enter session to CmsMobileToken
     * @param array
     *
     * @return CmsMobileToken object
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function userToMobileToken($params = array())
    {
        if (!isset($params['uid']) || empty($params['uid'])) {
            return false;
        }

        $em = $this->getEntityManager();
        $mobileToken = new CmsMobileToken();

        $mobileToken->setSsid($params['uid']);
        $mobileToken->setTokenid($params['tokenId']);
        $mobileToken->setUserid($params['userId']);
        $mobileToken->setPlatform($params['platform']);
        $mobileToken->setApType($params['aptType']);

        $em->persist($mobileToken);
        $em->flush();

        if ($mobileToken) {
            return $mobileToken;
        } else {
            return false;
        }
    }

    /*
     * Deleting user CmsMobileToken when signing out
     * @param array
     *
     * @return object
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function deleteMobileToken($params = array())
    {
        $qb = $this->createQueryBuilder('UP')
            ->delete('TTBundle:CmsMobileToken', 'v')
            ->where('v.ssid = :ssid')
            ->andWhere('v.tokenid = :tokenid')
            ->setParameter(':tokenid', $params['tokenid'])
            ->setParameter(':ssid', $params['ssid']);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
     * Deleting user OauthAccessToken when signing out
     * @param array
     *
     * @return object
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function deleteAccessToken($token)
    {
        $qb = $this->createQueryBuilder('UP')
            ->delete('RestBundle:OauthAccessToken', 'v')
            ->where('v.token = :token')
            ->setParameter(':token', $token);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
     * This method is used to edit oauth access info
     * @param array
     *
     * @return oauth token object
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function updateOauthAccessToken($params = array())
    {
        $this->em = $this->getEntityManager();
        $qb = $this->em->createQueryBuilder('oa')
            ->update('RestBundle:OauthAccessToken', 'oa');

        if (isset($params['deviceInformation']) && !empty($params['deviceInformation'])) {
            $qb->set("oa.deviceInformation", ":deviceInformation")
                ->setParameter(':deviceInformation', $params['deviceInformation']);
        }
        $qb->where("oa.token=:token")
            ->setParameter(':token', $params['accessToken']);

        $query = $qb->getQuery();
        $queryRes = $query->getResult();
        if ($queryRes) {
            return $queryRes;
        } else {
            return false;
        }
    }

    /*
     * Deleting user OauthRefreshToken when signing out
     * @param array
     *
     * @return object
     * @author Anna Lou Parejo<anna.parejo@touristtube.com>
     */

    public function deleteRefreshToken($token)
    {
        $qb = $this->createQueryBuilder('UP')
            ->delete('RestBundle:OauthRefreshToken', 'v')
            ->where('v.token = :token')
            ->setParameter(':token', $token);
        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
     *
     * This method checks for duplicate entry email
     * Can be used both in modify and adding user
     *
     * @param $userId()
     * @param $email()
     *
     * @return boolean true or false
     * @author Anna lou Parejo <anna.parejo@touristtube.com>
     */

    public function checkDuplicateUserEmail($userId = 0, $email = '')
    {
        $query = $this->createQueryBuilder('u');

        if ($userId > 0) {
            $query->where('u.id <> :id')->setParameter(':id', $userId);
        }
        $query->andwhere('u.published != -2');
        $query->andWhere('u.youremail = :yourEmail')->setParameter(':yourEmail', $email);


        $quer = $query->getQuery();
        $result = $quer->getScalarResult();
        return (!empty($result)) ? 1 : 0;
    }

    /*
     *
     * This method checks for duplicate entry for username
     * Can be used both in modify and adding user
     *
     * @param $userId()
     * @param $userName()
     *
     * @return boolean true or false
     * @author Anna lou Parejo <anna.parejo@touristtube.com>
     */

    public function checkDuplicateUserName($userId = 0, $userName = '')
    {
        $query = $this->createQueryBuilder('cu')
            ->select('cu');

        if ($userId > 0) {
            $query->where('cu.id <> :id')->setParameter(':id', $userId);
        }

        $query->andwhere('cu.published != -2');
        $query->andWhere('cu.yourusername = :yourUserName')->setParameter(':yourUserName', $userName);

        $quer = $query->getQuery();
        $result = $quer->getScalarResult();

        $duplicate = (!empty($result)) ? true : false;
        return $duplicate;
    }

    /**
     * checks if a user's password is correct
     * @param integer $user_id
     * @param string $old_pass
     * @param string $username
     * @return boolean true|false
     */
    public function userPasswordCorrect($user_id, $old_pass, $username = '')
    {
        $qb = $this->createQueryBuilder('u')
            ->where("u.id=:User_id AND u.yourpassword=:Yourpassword");
        if ($username != '') {
            $qb->andwhere('u.youremail = :YourUserName OR u.yourusername=:YourUserName')
                ->setParameter(':YourUserName', $username);
        }
        $qb->setParameter(':User_id', $user_id)
            ->setParameter(':Yourpassword', $old_pass);
        $query = $qb->getQuery();
        $result = $query->getArrayResult();

        if (sizeof($result) == 1)
            return true;
        else
            return false;
    }

    /*
     * userAlreadyAuthorized function will check weather the user is already authorized with twitter or any other social network
     */
    function userAlreadyAuthorized($user_id = null, $account_type = '', $status = '1')
    {
        $qb = $this->createQueryBuilder('t')
            ->where("t.userId=:User_id AND t.accountType=:Account_type AND t.status=:Status");

        $qb->setParameter(':User_id', $user_id)
            ->setParameter(':Account_type', $account_type)
            ->setParameter(':Status', $status);
        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    /*
    * @updateUserSocialCredential function will update the user credential from status 0 to 1 for respective social media
    */
    public function updateUserSocialCredential($user_id = null, $account_type = 'twitter', $status = '1')
    {
        $qb = $this->createQueryBuilder('t')
            ->update('TTBundle:CmsUsersSocialTokens t')
            ->set('t.status', ':Status')
            ->Where('t.userId=:User_id AND t.accountType=:Account_type')
            ->setParameter(':User_id', $user_id)
            ->setParameter(':Status', $status)
            ->setParameter(':Account_type', $account_type);
        $query = $qb->getQuery();
        $query->getResult();
        return $query;
    }

    /*
    * @userUpdateFbUser
    */
    public function userUpdateFbUser($user_id = null, $fb_user, $salt, $password, $yourPassword)
    {
        $qb = $this->createQueryBuilder('u')
            ->update('TTBundle:CmsUsers u')
            ->set('u.fbUser', ':Fb_user')
            ->set('u.salt', ':Salt')
            ->set('u.yourpassword', ':Yourpassword')
            ->set('u.password', ':Password')
            ->Where('u.id=:User_id')
            ->setParameter(':User_id', $user_id)
            ->setParameter(':Salt', $salt)
            ->setParameter(':Yourpassword', $yourPassword)
            ->setParameter(':Password', $password)
            ->setParameter(':Fb_user', $fb_user);
        $query = $qb->getQuery();
        $query->getResult();
        return $query;
    }

    /*
    * @setUserSocialCredential function will store the user credential for respective social network
    */
    public function setUserSocialCredential($user_id = null, $access_token, $account_type = 'twitter')
    {
        $social_id = $access_token['user_id'];
        $oauth_token = $access_token['oauth_token'];
        $oauth_token_secret = $access_token['oauth_token_secret'];

        $qb = $this->createQueryBuilder('t')
            ->delete('TTBundle:CmsUsersSocialTokens', 't')
            ->where('t.userId=:User_id AND t.accountType=:Account_type')
            ->setParameter(':User_id', $user_id)
            ->setParameter(':Account_type', $account_type);
        $query = $qb->getQuery();
        $query->getResult();

        $em = $this->getEntityManager();
        $items = new CmsUsersSocialTokens();
        $items->setUserId($user_id);
        $items->setAccountType($account_type);
        $items->setSocialId($social_id);
        $items->setOauthToken($oauth_token);
        $items->setOauthTokenSecret($oauth_token_secret);
        $items->setStatus(1);
        $em->persist($items);
        $em->flush();
        if ($items->getId()) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * This method is used to set a user session
     * @param userId
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function settingSession($userId)
    {
        $qb = $this->createQueryBuilder('u')
            ->where("u.id = :userId and u.published = 1")
            ->setParameter(':userId', $userId)
            ->setMaxResults(1)
            ->getQuery();
        return $qb->getArrayResult();
    }

    /*
     * used to store user ip address and browser/device info
     * @param array()
     *
     * @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function insertUserLoginTrack($params)
    {
        $em = $this->getEntityManager();

        $tuberLogin = new CmsTubersLoginTracking();
        $tuberLogin->setIpAddress($params['ipAddress']);
        $tuberLogin->setForwardedIpAddress($params['forwardedIpAddress']);
        $tuberLogin->setUserAgent($params['userAgent']);
        $tuberLogin->setUserId($params['userId']);
        $tuberLogin->setLogTs(new \DateTime("now"));

        $em->persist($tuberLogin);
        $em->flush();

        return $tuberLogin;
    }

    /*
     * This method gets CmsChannel data of a user limit 1
     * @param array @return user object
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function userDefaultChannelGet($userId)
    {
        $query = $this->createQueryBuilder('cc')
            ->select('cc')
            ->where('cc.ownerId = :ownerId')
            ->setParameter(':ownerId', $userId)
            ->orderBy('cc.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getScalarResult();
        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /*
     * This method gets user data per array being passed
     * @param array
     *
     * @return user array
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getUserDetails($params = array())
    {
        $query = $this->createQueryBuilder('cu')
            ->select('cu,cc.name as yourcountryName,up.id as profileId,up.name as profileName,up.level as profileLevel,ca.id as accountId,ca.name as accountName, ca.accountTypeId as acctTypeId, ca.email as corporateEmail, ca.accountingEmail as accountingEmail') 
            ->leftJoin('CorporateBundle:CmsCountries', 'cc', 'WITH', "cc.code = cu.yourcountry")
            ->leftJoin('CorporateBundle:CorpoUserProfiles', 'up', 'WITH', "up.id = cu.corpoUserProfileId")
            ->leftJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', "ca.id = cu.corpoAccountId");

        if (isset($params['id']) && !empty($params['id'])) {
            $query->andWhere('cu.id = :id')->setParameter(':id', $params['id']);
        }

        if (isset($params['accountId']) && !empty($params['accountId'])) {
            $query->andWhere('cu.corpoAccountId = :accountId')->setParameter(':accountId', $params['accountId']);
        }

        if (isset($params['yourUserName']) && !empty($params['yourUserName'])) {
            $query->andWhere('cu.yourusername = :yourUserName')->setParameter(':yourUserName', $params['yourUserName']);
        }
        if (isset($params['yourEmail']) && !empty($params['yourEmail'])) {
            $query->andWhere('cu.youremail = :yourEmail')->setParameter(':yourEmail', $params['yourEmail']);
        }


        $quer = $query->getQuery();

        $result = $quer->getScalarResult();

        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /*
     *
     * This method gets user account status is_active is active or not
     *
     * @param array()
     *
     * @return boolean true or false
     * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
     */

    public function getUserAccountStatus($params = array())
    {
        $query = $this->createQueryBuilder('cu')
            ->select('cu, ca.isActive as is_active')
            ->leftJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', "ca.id = cu.corpoAccountId");

        if (isset($params['id']) && !empty($params['id'])) {
            $query->andWhere('cu.id = :id')->setParameter(':id', $params['id']);
        }
        if (isset($params['yourUserName']) && !empty($params['yourUserName'])) {
            $query->andWhere('cu.yourusername = :yourUserName')->setParameter(':yourUserName', $params['yourUserName']);
        }
        if (isset($params['yourEmail']) && !empty($params['yourEmail'])) {
            $query->andWhere('cu.youremail = :yourEmail')->setParameter(':yourEmail', $params['yourEmail']);
        }


        $quer = $query->getQuery();
        $result = $quer->getScalarResult();

        if (isset($result[0]['is_active']) && !empty($result[0]['is_active'])) {
            return $result[0]['is_active'];
        } else {
            return false;
        }
    }

    /*
     * This method gets roles
     * @param array
     *
     * @return roles
     * @author Anthony Malak <anthony@touristtube.com>
     */

    public function getUserRoles($params = array())
    {
        $query = $this->createQueryBuilder('cu')
            ->select('cu');

        if (isset($params['id']) && !empty($params['id'])) {
            $query->andWhere('cu.id = :id')->setParameter(':id', $params['id']);
        }
        if (isset($params['name']) && !empty($params['name'])) {
            $query->andWhere('cu.name = :name')->setParameter(':name', $params['name']);
        }
        if (isset($params['role']) && !empty($params['role'])) {
            $query->andWhere('cu.role = :role')->setParameter(':role', $params['role']);
        }

        $quer = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /*
     * This method gets user data per array being passed
    */
    public function checkUserEmailMD5($emails = '')
    {
        if (!empty($emails)) {
            $query = $this->createQueryBuilder('cu')
                ->select('cu')
                ->where('MD5(concat(cu.id,cu.youremail))=:Emails')
                ->setParameter(':Emails', $emails)
                ->getQuery();

            $result = $query->getScalarResult();
            if (!empty($result) && isset($result[0])) {
                return $result[0];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
     * This method delete user
     */
    public function deleteUserEmailMD5($emails = '')
    {
        if (!empty($emails)) {
            $qb = $this->createQueryBuilder('cu')
                ->delete('TTBundle:CmsUsers', 'cu')
                ->where('MD5(concat(cu.id,cu.youremail))=:Emails')
                ->setParameter(':Emails', $emails);

            $query = $qb->getQuery();
            return $query->getResult();
        } else {
            return false;
        }
    }

    public function getUserByEmailYourUserName($userCredential = '')
    {
        if (!empty($userCredential)) {
            $query = $this->createQueryBuilder('cc')
                ->select('cc', 'ca')
                ->leftJoin('CorporateBundle:CorpoAccount', 'ca', 'WITH', "ca.id = cc.corpoAccountId")
                ->where('cc.youremail = :userCredential OR cc.yourusername = :userCredential')
                ->andWhere('cc.published = 1')
                ->setParameter(':userCredential', $userCredential)
                ->orderBy('cc.id', 'ASC')
                ->setMaxResults(1)
                ->getQuery();

            $result = $query->getScalarResult();
            if (!empty($result) && isset($result[0])) {
                return $result[0];
            } else {
                return false;
            }
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

    public function getInterestedInList($lang = 'en')
    {
        $qb = $this->createQueryBuilder('i')
            ->select('i, mli ')
            ->leftJoin('TTBundle:MlIntrestedin', 'mli', 'WITH', "mli.parentId = i.id and mli.language=:Lang")
            ->where('i.published = 1')
            ->setParameter(':Lang', $lang)
            ->orderBy('i.title', 'ASC')
            ->getQuery();

        return $qb->getScalarResult();
    }

    /**
     * Gets report reason list for a givent entity type
     * @param integer $entity_type the desired entity type
     * 14_0 delete channel reason
     * 2_0 report a tuber
     * 2_1 report a tuber content
     * @return array
     */
    public function getReportReasonList($srch_options)
    {
        $default_opts = array(
            'limit' => null,
            'page' => 0,
            'id' => null,
            'entity_type' => null,
            'lang' => 'en'
        );
        $options = array_merge($default_opts, $srch_options);

        $qb = $this->createQueryBuilder('rr')
            ->select('rr, mlrr ')
            ->leftJoin('TTBundle:MlReportReason', 'mlrr', 'WITH', "mlrr.entityId = rr.id and mlrr.langCode=:Lang");

        if ($options['id'] != null) {
            $qb->andWhere('rr.id = :ID')
                ->setParameter(':ID', $options['id']);
        }

        if ($options['entity_type'] != null) {
            $qb->andWhere('FIND_IN_SET(rr.entityType, :Entity_type)!=0')
                ->setParameter(':Entity_type', $options['entity_type']);
        }

        $qb->setParameter(':Lang', $options['lang']);

        $nlimit = '';
        if ($options['limit'] != null) {
            $nlimit = intval($options['limit']);
            $skip = intval($options['page']) * $nlimit;

            $qb->setMaxResults($nlimit)
                ->setFirstResult($skip);
        }

        $query = $qb->getQuery();
        return $query->getScalarResult();
    }

    public function userLoginFacebook($fb_user)
    {
        try {

            $con = $this->getEntityManager()->getConnection();
            $sql = "SELECT * FROM cms_users where fb_user='$fb_user'";
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll();
            if ($res) {
                return $res;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
