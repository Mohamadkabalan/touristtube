<?php
namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AccountController extends DefaultController
{

    public function newsfeedGroupingLogSearch($srch_options)
    {
        $default_opts = array(
            'limit' => 10,
            'page' => 0,
            'orderby' => 'id',
            'order' => 'a',
            'entity_type' => null,
            'entity_id' => null,
            'media_type' => null,
            'action_type' => null,
            'from_time' => null,
            'to_time' => null,
            'from_ts' => null,
            'to_ts' => null,
            'from_filter' => null,
            'activities_filter' => null,
            'userid' => null,
            'owner_id' => null,
            'is_visible' => 1,
            'search_string' => null,
            'channel_id' => null,
            'feed_privacy' => null,
            'published' => '0,1',
            'n_results' => false
        );
        $options = array_merge($default_opts, $srch_options);
        $where = '';
        $nlimit = '';
        $em = $this->getDoctrine()->getManager();
        $params = array();
        if (! is_null($options['limit'])) {
            $nlimit = intval($options['limit']);
            $skip = intval($options['page']) * $nlimit;
        }
        $orderby = $options['orderby'];
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
        if ($options['owner_id']) {
            $userid = intval($options['owner_id']);
        } else {
            $userid = $this->data['USERID'];
        }
        if (isset($userid) && $userid > 0 && ! $options['channel_id']) {
            $friends = userGetFreindList($userid);
            $friends_ids = array(
                $userid
            );
            foreach ($friends as $freind) {
                $friends_ids[] = $freind['id'];
            }
            if (count($friends_ids) != 0) {
                if ($where != '')
                    $where .= " AND ";
                $public = $this->container->getParameter('USER_PRIVACY_PUBLIC');
                $private = $this->container->getParameter('USER_PRIVACY_PRIVATE');
                $selected = $this->container->getParameter('USER_PRIVACY_SELECTED');
                $community = $this->container->getParameter('USER_PRIVACY_COMMUNITY');
                $privacy_where = '';

                $inner_q = $em->createQuery("SELECT PR.id FROM TTBundle:CmsUsersPrivacyExtand PR WHERE PR.userId=NF.userId AND PR.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FRIENDS') . " AND PR.published=1")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q1 = $em->createQuery("SELECT PR1.id FROM TTBundle:CmsUsersPrivacyExtand PR1 WHERE PR1.userId=NF.userId AND PR1.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FRIENDS') . " AND PR1.published=1 AND PR1.userId = :Userid")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q2 = $em->createQuery("SELECT PR2.id FROM TTBundle:CmsUsersPrivacyExtand PR2 WHERE PR2.userId=NF.userId AND PR2.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FRIENDS') . " AND PR2.published=1 AND PR2.kindType=:Public")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q3 = $em->createQuery("SELECT PR3.id FROM TTBundle:CmsUsersPrivacyExtand PR3 WHERE PR3.userId=NF.userId AND PR3.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FRIENDS') . " AND PR3.published=1 AND PR3.kindType=:Community AND PR3.userId IN (" . implode(',', $friends_ids) . ")")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q6 = $em->createQuery("SELECT PR6.id FROM TTBundle:CmsUsersPrivacyExtand PR6 WHERE PR6.userId=NF.userId AND PR6.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FRIENDS') . " AND PR6.published=1 AND PR6.kindType=:Private AND PR6.userId=:Userid")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q7 = $em->createQuery("SELECT PR7.id FROM TTBundle:CmsUsersPrivacyExtand PR7 WHERE PR7.userId=NF.userId AND PR7.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FRIENDS') . " AND PR7.published=1 AND ( ( FIND_IN_SET( :Community , PR7.kindType )<>0 AND PR7.userId IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( :Userid , PR7.users )<>0 )  )")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q8 = $em->createQuery("SELECT PR8.id FROM TTBundle:CmsUsersPrivacyExtand PR8 WHERE PR8.userId=NF.userId AND PR8.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FOLLOWINGS') . " AND PR8.published=1")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q9 = $em->createQuery("SELECT PR9.id FROM TTBundle:CmsUsersPrivacyExtand PR9 WHERE PR9.userId=NF.userId AND PR9.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FOLLOWINGS') . " AND PR9.published=1 AND PR9.userId = :Userid")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q10 = $em->createQuery("SELECT PR10.id FROM TTBundle:CmsUsersPrivacyExtand PR10 WHERE PR10.userId=NF.userId AND PR10.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FOLLOWINGS') . " AND PR10.published=1 AND PR10.kindType=:Public")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q11 = $em->createQuery("SELECT PR11.id FROM TTBundle:CmsUsersPrivacyExtand PR11 WHERE PR11.userId=NF.userId AND PR11.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FOLLOWINGS') . " AND PR11.published=1 AND PR11.kindType=:Community AND PR11.userId IN (" . implode(',', $friends_ids) . ")")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q14 = $em->createQuery("SELECT PR14.id FROM TTBundle:CmsUsersPrivacyExtand PR14 WHERE PR14.userId=NF.userId AND PR14.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FOLLOWINGS') . " AND PR14.published=1 AND PR14.kindType=:Private AND PR14.userId=:Userid")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q15 = $em->createQuery("SELECT PR15.id FROM TTBundle:CmsUsersPrivacyExtand PR15 WHERE PR15.userId=NF.userId AND PR15.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_PROFILE_FOLLOWINGS') . " AND PR15.published=1 AND ( ( FIND_IN_SET( :Community , PR15.kindType )<>0 AND PR15.userId IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( :Userid , PR15.users )<>0 )  )")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q16 = $em->createQuery("SELECT PR16.id FROM TTBundle:CmsUsersPrivacyExtand PR16 WHERE PR16.entityId=NF.entityId AND PR16.entityType=NF.entityType AND PR16.published=1 AND PR16.kindType=:Public")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q17 = $em->createQuery("SELECT PR17.id FROM TTBundle:CmsUsersPrivacyExtand PR17 WHERE PR17.entityId=NF.entityId AND PR17.entityType=NF.entityType AND PR17.published=1")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q18 = $em->createQuery("SELECT PR18.id FROM TTBundle:CmsUsersPrivacyExtand PR18 WHERE PR18.entityId=NF.entityId AND PR18.entityType=NF.entityType AND PR18.published=1 AND PR18.userId = :Userid")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q19 = $em->createQuery("SELECT PR19.id FROM TTBundle:CmsUsersPrivacyExtand PR19 WHERE PR19.entityId=NF.entityId AND PR19.entityType=NF.entityType AND PR19.published=1 AND PR19.kindType=:Community AND PR19.userId IN (" . implode(',', $friends_ids) . ")")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q22 = $em->createQuery("SELECT PR22.id FROM TTBundle:CmsUsersPrivacyExtand PR22 WHERE PR22.entityId=NF.entityId AND PR22.entityType=NF.entityType AND PR22.published=1 AND PR22.kindType=:Private AND PR22.userId=:Userid")
                    ->setMaxResults(1)
                    ->getDQL();

                $inner_q23 = $em->createQuery("SELECT PR23.id FROM TTBundle:CmsUsersPrivacyExtand PR23 WHERE PR23.entityId=NF.entityId AND PR23.entityType=NF.entityType AND PR23.published=1 AND ( ( FIND_IN_SET( :Community , PR23.kindType )<>0 AND PR23.userId IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( :Userid , PR23.users )<>0 )  )")
                    ->setMaxResults(1)
                    ->getDQL();

                $where .= "(";
                $where .= " ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FRIEND') . " AND NOT EXISTS ( $inner_q ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FRIEND') . " AND  EXISTS ( $inner_q1 ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FRIEND') . " AND  EXISTS ( $inner_q2 ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FRIEND') . " AND  EXISTS ( $inner_q3 ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FRIEND') . " AND  EXISTS ( $inner_q6 ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FRIEND') . " AND  EXISTS ( $inner_q7 ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FOLLOW') . " AND NOT EXISTS ( $inner_q8 ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FOLLOW') . " AND  EXISTS ( $inner_q9 ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FOLLOW') . " AND  EXISTS ( $inner_q10 ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FOLLOW') . " AND  EXISTS ( $inner_q11 ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FOLLOW') . " AND  EXISTS ( $inner_q14 ) )";
                $where .= " OR ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_FOLLOW') . " AND  EXISTS ( $inner_q15 ) )";
                $where .= " EXISTS ( $inner_q16 )";
                $where .= " OR NOT EXISTS ( $inner_q17 )";
                $where .= " OR EXISTS ( $inner_q18 )";
                $where .= " OR EXISTS ( $inner_q19 )";
                $where .= " OR EXISTS ( $inner_q22 )";
                $where .= " OR EXISTS ( $inner_q23 )";
                $where .= " )";

                $params['Public'] = $public;
                $params['Userid'] = $userid;
                $params['Community'] = $community;
                $params['Private'] = $private;
            }
        }
        if ($options['userid']) {
            if ($where != '')
                $where .= " AND";

            $inner_qr1 = $em->createQuery("SELECT p.id FROM TTBundle:CmsSocialPosts as p WHERE p.fromId=NF.userId AND p.userId = :Userid AND p.id=NF.entityId")
                ->setMaxResults(1)
                ->getDQL();

            $where .= " (";
            $where .= " ( NF.userId = :Userid )";
            $where .= " OR";
            $where .= " ( NF.userId<>:Userid AND COALESCE(NF.channelId, 0) = 0 AND NF.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_POST') . " AND NF.userId=NF.ownerId AND EXISTS ($inner_qr1) AND action_type=" . $this->container->getParameter('SOCIAL_ACTION_UPLOAD') . " )";
            $where .= " )";
        }
        if ($options['from_filter'] && intval($options['from_filter']) == FROM_FRIENDS) {
            if ($where != '')
                $where .= " AND";
            $inner_qr2 = $em->createQuery("SELECT F.requesterId FROM TTBundle:CmsFriends F WHERE F.published=1 AND F.requesterId=:Userid3 AND F.receipientId=NF.userId AND status=" . $this->container->getParameter('FRND_STAT_ACPT') . " AND notify=1")
                ->setMaxResults(1)
                ->getDQL();

            $inner_qr3 = $em->createQuery("SELECT CHA.id FROM TTBundle:CmsChannel CHA WHERE CHA.id=NF.channelId AND CHA.ownerId<>NF.userId AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_SPONSOR') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_RELATION_SUB') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_RELATION_PARENT') . "")
                ->setMaxResults(1)
                ->getDQL();

            $inner_qr4 = $em->createQuery("SELECT F1.requesterId FROM TTBundle:CmsFriends F1 WHERE F1.published=1 AND F1.requesterId=:Userid3 AND F1.receipientId=NF.userId AND F1.status=" . $this->container->getParameter('FRND_STAT_ACPT') . " AND F1.notify=1")
                ->setMaxResults(1)
                ->getDQL();

            $where .= " (";
            $where .= " ( COALESCE(NF.channelId, 0) = 0 AND EXISTS ($inner_qr2) )";
            $where .= " OR";
            $where .= " ( COALESCE(NF.channelId, 0) AND EXISTS ($inner_qr3) AND EXISTS ($inner_qr4) )";
            $where .= " )";

            $params['Userid3'] = $userid;
        } else if ($options['from_filter'] && intval($options['from_filter']) == FROM_FOLLOWINGS) {
            if ($where != '')
                $where .= " AND";

            $inner_qr5 = $em->createQuery("SELECT FL.subscriptionId FROM TTBundle:CmsSubscriptions FL WHERE FL.published = 1 AND FL.userId=NF.userId AND FL.subscriberId=:Userid4")
                ->setMaxResults(1)
                ->getDQL();

            $inner_qr6 = $em->createQuery("SELECT CHA.id FROM TTBundle:CmsChannel CHA WHERE CHA.id=NF.channelId AND CHA.ownerId<>NF.userId AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_SPONSOR') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_RELATION_SUB') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_RELATION_PARENT') . "")
                ->setMaxResults(1)
                ->getDQL();

            $inner_qr7 = $em->createQuery("SELECT FL.subscriptionId FROM TTBundle:CmsSubscriptions FL WHERE FL.published = 1 AND FL.userId=NF.userId AND FL.subscriberId=:Userid4")
                ->setMaxResults(1)
                ->getDQL();

            $where .= " (";
            $where .= " ( COALESCE(NF.channelId, 0) = 0 AND EXISTS ($inner_qr5) )";
            $where .= " OR";
            $where .= " ( COALESCE(NF.channelId, 0) AND EXISTS ($inner_qr6) AND EXISTS ($inner_qr7) )";
            $where .= " )";
            $params['Userid4'] = $userid;
        } else if ($options['from_filter'] && intval($options['from_filter']) == FROM_CHANNELS) {
            if ($where != '')
                $where .= " AND";

            $inner_qr8 = $em->createQuery("SELECT CHA.id FROM TTBundle:CmsChannel CHA WHERE CHA.id=NF.channelId AND CHA.ownerId=NF.userId")
                ->setMaxResults(1)
                ->getDQL();

            $inner_qr9 = $em->createQuery("SELECT F.userid FROM TTBundle:CmsChannelConnections F WHERE F.channelid=NF.channelId AND F.published=1 AND F.userid=:Userid5 LIMIT 1")
                ->setMaxResults(1)
                ->getDQL();

            $where .= " COALESCE(NF.channelId, 0) AND EXISTS ($inner_qr8) AND EXISTS ($inner_qr9) ";
            $params['Userid5'] = $options['userid'];
        }
        if ($options['channel_id'] && $options['channel_id'] != - 1) {
            $channelInfo = channelGetInfo($options['channel_id']);
            $owner_id = intval($channelInfo['owner_id']);
        } else {
            $owner_id = intval($options['userid']);
        }
        if ($options['activities_filter'] && intval($options['activities_filter']) == $this->container->getParameter('ACTIVITIES_ON_TCHANNELS')) {
            if ($where != '')
                $where .= " AND ";
            if ($options['channel_id']) {
                $where .= "NF.channelId ='{$options['channel_id']}' AND NF.feedPrivacy <>" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . " AND NF.ownerId = :Owner_id ";
                $params['Owner_id'] = $owner_id;
            } else if ($options['userid']) {
                $where .= "( COALESCE(NF.channelId, 0) = 0 AND NF.feedPrivacy =" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . " ) ";
            } else {
                $where .= "COALESCE(NF.channelId, 0) ";
            }
            if ($options['action_type']) {
                if ($where != '')
                    $where .= ' AND ';
                $where .= " NF.entityType<>" . $this->container->getParameter('SOCIAL_ENTITY_COMMENT') . " ";
            }
        } else if ($options['activities_filter'] && intval($options['activities_filter']) == $this->container->getParameter('ACTIVITIES_ON_TTPAGE')) {
            if ($where != '')
                $where .= " AND ";
            $where .= "NF.entityType<>" . SOCIAL_ENTITY_BAG . " AND ";
            if ($options['owner_id']) {
                $where .= "COALESCE(NF.channelId, 0) = 0 AND NF.feedPrivacy <>" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . " AND NF.ownerId = :Owner_id2 ";
                $params['Owner_id2'] = $options['owner_id'];
            } else if ($options['userid']) {
                $where .= "COALESCE(NF.channelId, 0) = 0 AND NF.feedPrivacy <>" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . " AND NF.ownerId = :Userid6 ";
                $params['Userid6'] = $options['userid'];
            } else {
                $where .= "COALESCE(NF.channelId, 0) = 0 ";
            }
            if ($options['action_type']) {
                if ($where != '')
                    $where .= ' AND ';
                $where .= " NF.entityType<>" . $this->container->getParameter('SOCIAL_ENTITY_COMMENT') . " ";
            }
        } else if ($options['activities_filter'] && intval($options['activities_filter']) == $this->container->getParameter('ACTIVITIES_ON_TTPAGE_OTHER')) {
            if ($where != '')
                $where .= " AND ";
            if ($options['userid']) {
                $where .= "COALESCE(NF.channelId, 0) = 0 AND NF.feedPrivacy <>" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . " AND NF.ownerId <> :Userid7";
                $params['Userid7'] = $options['userid'];
            }
        } else if ($options['activities_filter'] && intval($options['activities_filter']) == $this->container->getParameter('ACTIVITIES_ON_THOTELS')) {
            if ($where != '')
                $where .= " AND ";
            $where .= "NF.entityType=" . SOCIAL_ENTITY_BAG . " ";
        } else if ($options['activities_filter'] && intval($options['activities_filter']) == $this->container->getParameter('ACTIVITIES_ON_TECHOES')) {
            if ($where != '')
                $where .= " AND ";
            $where .= "NF.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_FLASH') . " ";
        }
        if ($options['search_string'] && (strlen($options['search_string']) != 0)) {
            if ($where != '')
                $where .= " AND ( ( U.displayFullname = 0 AND LOWER(YourUserName) LIKE :Search_string ) OR ( U.displayFullname = 1 AND LOWER(FullName) LIKE :Search_string2 ) )";
            $params['Search_string'] = $options['search_string'] . "%";
            $params['Search_string2'] = "%" . $options['search_string'] . "%";
        }
        if ($options['feed_privacy']) {
            if ($where != '')
                $where .= " AND ";
            if (intval($options['feed_privacy']) == - 1) {
                $where .= " NF.feedPrivacy<>" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . "";
            } else {
                $where .= " NF.feedPrivacy=:Feed_privacy ";
                $params['Feed_privacy'] = $options['feed_privacy'];
            }
        }
        if (! is_null($options['published'])) {
            if ($where != '')
                $where .= " AND ";
            $where .= " find_in_set(cast(NF.published as char), :Published)<>0 ";
            $params['Published'] = $options['published'];
        }
        if ($options['is_visible'] != - 1) {
            if ($options['is_visible'] == 1 && intval($options['userid']) != 0) {
                if ($where != '')
                    $where .= ' AND ';

                $inner_qr10 = $em->createQuery("SELECT H.feedId FROM TTBundle:CmsSocialNewsfeedHide H WHERE H.feedId=NF.id AND H.userId=:Userid8")
                    ->setMaxResults(1)
                    ->getDQL();

                $where .= " NOT EXISTS ($inner_qr10)";
                $params['Userid8'] = $options['userid'];
            } else if ($options['is_visible'] == 1 && $options['channel_id'] && intval($options['channel_id']) > 0) {
                if ($where != '')
                    $where .= ' AND ';

                $inner_qr11 = $em->createQuery("SELECT H.feedId FROM TTBundle:CmsSocialNewsfeedHide H WHERE H.feedId=NF.id AND H.userId=:Owner_id4")
                    ->setMaxResults(1)
                    ->getDQL();

                $where .= " NOT EXISTS ($inner_qr11)";
                $params['Owner_id4'] = $owner_id;
            } else if ($options['is_visible'] == 0 && intval($options['userid']) != 0) {
                if ($where != '')
                    $where .= ' AND ';

                $inner_qr12 = $em->createQuery("SELECT H.feedId FROM TTBundle:CmsSocialNewsfeedHide H WHERE H.feedId=NF.id AND H.userId='{$options['userid']}'")
                    ->setMaxResults(1)
                    ->getDQL();

                $where .= " EXISTS ($inner_qr12)";
            } else if ($options['is_visible'] == 0 && $options['channel_id'] && intval($options['channel_id']) > 0) {
                if ($where != '')
                    $where .= ' AND ';

                $inner_qr13 = $em->createQuery("SELECT H.feedId FROM TTBundle:CmsSocialNewsfeedHide H WHERE H.feedId=NF.id AND H.userId=:Owner_id5")
                    ->setMaxResults(1)
                    ->getDQL();

                $where .= " EXISTS ($inner_qr13)";
                $params['Owner_id5'] = $owner_id;
            }
        }
        if ($options['entity_type']) {
            if ($where != '')
                $where .= ' AND ';
            $where .= " NF.entityType='{$options['entity_type']}' ";
        } else {
            if ($where != '')
                $where .= " AND ";
            global $CONFIG_EXEPT_ARRAY;
            $exept_array = $CONFIG_EXEPT_ARRAY;
            $where .= " NF.entityType NOT IN(" . implode(',', $exept_array) . ") ";
        }
        if ($options['entity_id']) {
            if ($where != '')
                $where .= ' AND ';
            $where .= " NF.entityId=:Entity_id ";
            $params['Entity_id'] = $options['entity_id'];
        }
        if ($options['action_type']) {
            if ($where != '')
                $where .= ' AND ';
            $where .= " NF.actionType=:Action_type ";
            $params['Action_type'] = $options['action_type'];
        } else {
            if ($where != '')
                $where .= " AND ";
            $where .= " NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_UNFRIEND') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_UNFOLLOW') . " ";
        }
        if ($options['from_ts'] || $options['to_ts']) {
            if ($options['from_ts']) {
                if ($where != '')
                    $where .= " AND ";
                $where .= " DATE(NF.feedTs) >= :From_ts ";
                $params['From_ts'] = $options['from_ts'];
            }
            if ($options['to_ts']) {
                if ($where != '')
                    $where .= " AND ";
                $where .= " DATE(NF.feedTs) <= :To_ts ";
                $params['To_ts'] = $options['to_ts'];
            }
        }
        if ($options['from_time'] || $options['to_time']) {
            if ($options['from_time']) {
                if ($where != '')
                    $where .= " AND ";
                $where .= " (NF.feedTs) >= :From_time ";
                $params['From_time'] = $options['from_time'];
            }
            if ($options['to_time']) {
                if ($where != '')
                    $where .= " AND ";
                $where .= " (NF.feedTs) <= :To_time ";
                $params['To_time'] = $options['to_time'];
            }
        }

        if ($where != '')
            $where .= " AND";

        $iqr14 = "SELECT VV.id FROM TTBundle:CmsVideos VV WHERE VV.id=NF.entityId AND VV.published=1";
        if ($options['media_type'] && $options['media_type'] != 'a' && $options['entity_type'] && $options['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_MEDIA')) {
            $iqr14 .= " AND image_video=:Media_type";
            $params['Media_type'] = $options['media_type'];
        }
        $inner_qr14 = $em->createQuery($iqr14)
            ->setMaxResults(1)
            ->getDQL();

        $where .= " (";
        $where .= " ( NF.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_MEDIA') . " AND EXISTS ($inner_qr14)";

        $where .= " )";
        $where .= " OR";

        $inner_qr15 = $em->createQuery("SELECT PO.id FROM TTBundle:CmsSocialPosts PO WHERE PO.id=NF.entityId AND PO.published=1")
            ->setMaxResults(1)
            ->getDQL();

        $where .= " ( NF.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_POST') . " AND EXISTS ($inner_qr15) )";
        $where .= " OR";

        $inner_qr16 = $em->createQuery("SELECT VC.id FROM TTBundle:CmsVideosCatalogs VC WHERE VC.catalogId=NF.entityId LIMIT 1")
            ->setMaxResults(1)
            ->getDQL();

        $inner_qr17 = $em->createQuery("SELECT CAT.id FROM TTBundle:CmsUsersCatalogs CAT WHERE CAT.id=NF.entityId AND CAT.published=1 AND EXISTS ($inner_qr16)")
            ->setMaxResults(1)
            ->getDQL();

        $where .= " ( NF.entityType=" . $this->container->getParameter('SOCIAL_ENTITY_ALBUM') . " AND EXISTS ($inner_qr17) )";
        $where .= " OR";
        $where .= " ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_EVENT_CANCEL') . " AND NF.feedPrivacy<>" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . ")";
        $where .= " OR";
        $where .= " ( NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_EVENT_CANCEL') . " AND NF.entityType!=" . $this->container->getParameter('SOCIAL_ENTITY_ALBUM') . " AND NF.entityType!=" . $this->container->getParameter('SOCIAL_ENTITY_POST') . " AND NF.entityType!=" . $this->container->getParameter('SOCIAL_ENTITY_MEDIA') . " )";
        $where .= " )";

        if (! $options['channel_id']) {
            if ($where != '')
                $where .= " AND ";
            $where .= " COALESCE(NF.channelId, 0) = 0 ";
            if (intval($options['userid']) != 0) {
                $inner_qr18 = $em->createQuery("SELECT SN.posterId FROM TTBundle:CmsSocialNotifications SN WHERE SN.posterId=:Userid8 AND SN.receiverId=NF.userId AND SN.isChannel=0 AND SN.posterIsChannel=0 AND SN.notify=0 AND SN.published='1'")
                    ->setMaxResults(1)
                    ->getDQL();

                $where .= "AND NOT EXISTS ($inner_qr18) ";
                $params['Userid8'] = $options['userid'];
            }
            if ($where != '')
                $where .= " AND";
            $inner_qr19 = $em->createQuery("select SHR.id from TTBundle:CmsSocialShares SHR where SHR.id=NF.actionId AND SHR.fromUser=NF.userId AND SHR.fromUser=:Userid")
                ->setMaxResults(1)
                ->getDQL();
            $where .= " (";
            $where .= " ( (NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_SHARE') . " OR NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_INVITE') . ") AND NF.feedPrivacy<>" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . " AND EXISTS ($inner_qr19) )";
            $where .= " OR";

            $inner_qr20 = $em->createQuery("select SHR.id from TTBundle:CmsSocialShares AS SHR where SHR.id=NF.actionId AND SHR.fromUser=NF.userId AND SHR.fromUser=:Userid")
                ->setMaxResults(1)
                ->getDQL();

            $where .= " ( (NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_SHARE') . " OR NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_INVITE') . ") AND NF.feedPrivacy=" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . " AND EXISTS ($inner_qr20) )";
            $where .= " OR";
            $where .= " ( NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_SPONSOR') . " AND NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_RELATION_SUB') . " AND NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_RELATION_PARENT') . " AND NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_SHARE') . " AND NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_INVITE') . " )";
            $where .= " )";
        } else if ($options['channel_id'] != - 1) {
            $myChannelId = intval($options['channel_id']);
            $channel_sub_array = getSubChannelRelationList($myChannelId, '1');
            $channel_parent_array = getParentChannelRelationList($myChannelId, '1');
            if ($channel_sub_array != '') {
                $myChannelId .= ',';
                $myChannelId .= $channel_sub_array;
            }
            if ($channel_parent_array != '') {
                $myChannelId .= ',';
                $myChannelId .= $channel_parent_array;
            }
            if ($where != '')
                $where .= " AND";
            $where .= " (";
            $where .= " ( (NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_SPONSOR') . " OR NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_RELATION_SUB') . " OR NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_RELATION_PARENT') . ") AND NF.feedPrivacy<>" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . " AND NF.userId IN($myChannelId) )";
            $where .= " OR";
            $where .= " ( (NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_SHARE') . " OR NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_INVITE') . ") AND NF.feedPrivacy<>" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . " AND NF.channelId IN($myChannelId) AND NF.userId=:Owner_id6 )";
            $where .= " OR";

            $inner_qr21 = $em->createQuery("select SHR.id from TTBundle:CmsSocialShares SHR where SHR.id=NF.actionId AND SHR.fromUser=NF.userId")
                ->setMaxResults(1)
                ->getDQL();

            $where .= " ( (NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_SHARE') . " OR NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_INVITE') . ") AND NF.channelId IN($myChannelId) AND NF.userId=:Owner_id6 AND NF.feedPrivacy=" . $this->container->getParameter('USER_PRIVACY_PRIVATE') . " AND EXISTS ($inner_qr21) )";
            $where .= " OR";

            $inner_qr22 = $em->createQuery("SELECT CM.id FROM TTBundle:CmsChannel CM WHERE CM.id=NF.channelId AND CM.ownerId=NF.userId")
                ->setMaxResults(1)
                ->getDQL();

            $where .= " ( NF.channelId IN($myChannelId) AND EXISTS ($inner_qr22) )";
            if ($options['activities_filter'] && intval($options['activities_filter']) == $this->container->getParameter('ACTIVITIES_ON_TCHANNELS')) {
                $where .= " OR";
                $where .= " ( NF.channelId NOT IN($myChannelId) AND NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_SPONSOR') . " AND NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_RELATION_SUB') . " AND NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_RELATION_PARENT') . " AND NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_SHARE') . " AND NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_INVITE') . ")";
            }
            $where .= " )";
            $params['Owner_id6'] = $owner_id;
        } else {
            if ($where != '')
                $where .= ' AND ';
            $where .= " COALESCE(NF.channelId, 0) ";
        }

        if (intval($options['userid']) != 0) {
            if ($where != '')
                $where .= " AND";

            $inner_qr23 = $em->createQuery("SELECT CM.id FROM TTBundle:CmsSocialComments CM WHERE CM.id=NF.actionId AND CM.userId=:Userid10")
                ->setMaxResults(1)
                ->getDQL();

            $where .= " (";
            $where .= " ( NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_COMMENT') . " AND NF.userId=:Userid10 AND EXISTS ($inner_qr23) )";
            $where .= " OR";
            $where .= " NF.actionType!=" . $this->container->getParameter('SOCIAL_ACTION_COMMENT') . "";
            $where .= " )";
            $params['Userid10'] = $options['userid'];
        }
        $where .= " AND NF.actionType <>" . $this->container->getParameter('SOCIAL_ACTION_REECHOE') . " ";
        if ($where != '')
            $where = " WHERE $where ";
        if ($options['n_results'] == false) {
            if ($orderby == 'most_liked') {
                $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender, ( SELECT COUNT(SL.id) FROM cms_social_likes AS SL WHERE SL.entityId=NF.entityId AND SL.entityType=NF.entityType AND SL.like_value=1 AND SL.published=1 ) AS counter FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.ownerId AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_SPONSOR') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_RELATION_SUB') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_RELATION_PARENT') . " $where";
                $query1 = "( $query AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_UPLOAD') . " ) UNION ( select * from ($query AND NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_UPLOAD') . " ORDER BY NF.id DESC) AS X GROUP BY X.actionType,X.entityType,X.entityGroup,X.feedPrivacy)";
                $query = "select * from ($query1) AS X1 ORDER BY X1.counter $order";
            } else {
                $query = "SELECT NF.*,U.YourUserName,U.FullName,U.displayFullname,U.profilePic,U.gender
                         FROM TTBundle:CmsSocialNewsfeed NF LEFT JOIN TTBundle:CmsUsers U ON U.id=NF.ownerId AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_SPONSOR') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_RELATION_SUB') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_RELATION_PARENT') . " $where";

                $query1 = "( $query AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_UPLOAD') . " ORDER BY NF.$orderby $order LIMIT 0, 100) UNION ( select * from ($query AND NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_UPLOAD') . " ORDER BY NF.$orderby $order LIMIT 0, 100) AS X GROUP BY X.actionType,X.entityType,X.entityGroup,X.feedPrivacy)";
                $query = "select * from ($query1) AS X1 ORDER BY X1.$orderby $order";
            }

            // $qb = $em->createQueryBuilder('VI');
            // $qb->select('C')
            // ->from("TTBundle:CmsChannel", 'C');
            // if ($options['privacy_subchannel'] == 2) {
            // $qb->innerJoin('TTBundle:CmsChannelPrivacy', 'P', 'WITH', "P.channelid=C.id AND P.privacyBeparentchannel=1 ");
            // } else if ($options['privacy_subchannel'] == 1) {
            // $qb->innerJoin('TTBundle:CmsChannelPrivacy', 'P', 'WITH', "P.channelid=C.id AND P.privacyBesubchannel=1 ");
            // }
            // if (!is_null($options['state_code']) && $options['state_code'] != '') {
            // $qb->innerJoin('TTBundle:Webgeocities', 'W', 'WITH', "W.id=C.cityId AND C.cityId>0 AND W.stateCode=:StateCode ");
            // $params['StateCode'] = $options['state_code'];
            // }
            // $qb->where("$where")->setParameters($params);
            // if (!is_null($options['limit'])) {
            // $qb->setMaxResults($nlimit)
            // ->setFirstResult($skip);
            // }
            // $qb->orderBy("$orderby", "$order");
            // $query = $qb->getQuery();
            // return $query->getResult();

            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select, $params);
            $res = $select->execute();

            $ret = $select->rowCount();

            $feed = array();
            $row = $select->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $row_item) {
                $feed_row = $row_item;
                $feed_row['action_row_count'] = 0;
                $feed_row['action_row_other'] = array();
                switch ($feed_row['action_type']) {
                    case $this->container->getParameter('SOCIAL_ACTION_COMMENT'):
                        $feed_row['action_row'] = socialCommentRow($feed_row['action_id']);
                        $channel_entity_type = $feed_row['action_row']['entity_type'];
                        if ($channel_entity_type == SOCIAL_ENTITY_CHANNEL) {
                            $channel_id = $feed_row['channel_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                        }
                        break;
                    case $this->container->getParameter('SOCIAL_ACTION_LIKE'):
                        $feed_row['action_row'] = socialLikeRow($feed_row['action_id']);
                        break;
                    case $this->container->getParameter('SOCIAL_ACTION_RATE'):
                        $feed_row['action_row'] = socialRateRow($feed_row['action_id']);
                        break;
                    case $this->container->getParameter('SOCIAL_ACTION_INVITE'):
                    case $this->container->getParameter('SOCIAL_ACTION_SHARE'):
                        $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                        $channel_entity_type = $feed_row['action_row']['entity_type'];
                        if ($channel_entity_type == SOCIAL_ENTITY_CHANNEL) {
                            $channel_id = $feed_row['channel_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                        }
                        break;
                    case $this->container->getParameter('SOCIAL_ACTION_REECHOE'):
                        $feed_row['action_row'] = socialReechoeRow($feed_row['action_id']);
                        break;
                    case $this->container->getParameter('SOCIAL_ACTION_RELATION_PARENT'):
                    case $this->container->getParameter('SOCIAL_ACTION_RELATION_SUB'):
                        $feed_row['action_row'] = channelRelationRow($feed_row['action_id']);
                        $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                        $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                        $channel_id = $feed_row['user_id'];
                        $channelInfo = channelGetInfo($channel_id);
                        $feed_row['channel_row'] = $channelInfo;
                        break;
                    case $this->container->getParameter('SOCIAL_ACTION_UPLOAD'):
                        $feed_row['action_row'] = array();
                        $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                        $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                        $srch_options = array(
                            'entity_group' => $feed_row['entity_group'],
                            'entity_type' => $feed_row['entity_type'],
                            'action_type' => $feed_row['action_type'],
                            'channel_id' => $feed_row['channel_id'],
                            'n_results' => true
                        );
                        $feed_row['action_row_count'] = newsfeedLogSearch($srch_options);

                        if ($feed_row['action_row_count'] > 1) {
                            $srch_options['n_results'] = false;
                            $srch_options['order'] = 'd';
                            $srch_options['limit'] = 4;
                            $srch_options['orderby'] = 'id';
                            $oth_lst = newsfeedLogSearch($srch_options);
                            $feed_row['action_row_other'] = $oth_lst;
                        }
                        break;
                    case $this->container->getParameter('SOCIAL_ACTION_SPONSOR'):
                        // in case of sponsor newsfeed.userId is actaully the channel_id
                        $channel_id = $feed_row['user_id'];
                        $channelInfo = channelGetInfo($channel_id);
                        $feed_row['channel_row'] = $channelInfo;
                        $feed_row['action_row'] = socialShareGet($feed_row['action_id']);

                        $sp_info = socialShareGet($feed_row['action_id']);
                        $feed_row['action_row']['msg'] = $sp_info['msg'];

                        // we dont have user info
                        unset($feed_row['YourUserName']);
                        unset($feed_row['FullName']);
                        unset($feed_row['display_fullname']);
                        unset($feed_row['profile_Pic']);
                        unset($feed_row['gender']);
                        break;
                    case $this->container->getParameter('SOCIAL_ACTION_EVENT_JOIN'):
                        $feed_row['join_row'] = joinEventInfo($feed_row['action_id']);
                        $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                        $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                        break;
                    case $this->container->getParameter('SOCIAL_ACTION_FRIEND'):
                    case $this->container->getParameter('SOCIAL_ACTION_FOLLOW'):
                        $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                        $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                        $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                        break;
                    default:
                        $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                        $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                        break;
                }

                if ($feed_row['action_row']['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_ALBUM')) {
                    // in case album
                    $catalog_row = userCatalogDefaultMediaGet($feed_row['action_row']['entity_id']);
                    if (! $catalog_row)
                        $catalog_row = array();
                    $feed_row['media_row'] = $catalog_row;

                    $feed_row['original_entity_type'] = $feed_row['entity_type'];
                    $feed_row['original_entity_id'] = $feed_row['entity_id'];
                } else if ($feed_row['action_type'] == $this->container->getParameter('SOCIAL_ACTION_LIKE') && $feed_row['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_COMMENT')) {
                    // in case like a comment
                    $cr = socialCommentRow($feed_row['entity_id']);
                    $feed_row['media_row'] = socialEntityInfo($cr['entity_type'], $cr['entity_id']);

                    $feed_row['original_media_row'] = $cr;

                    $feed_row['original_entity_type'] = $feed_row['entity_type'];
                    $feed_row['original_entity_id'] = $feed_row['entity_id'];

                    $feed_row['entity_type'] = $cr['entity_type'];
                    $feed_row['entity_id'] = $cr['entity_id'];
                } else if (($feed_row['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_EVENTS_LOCATION') || $feed_row['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_EVENTS_DATE') || $feed_row['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_EVENTS_TIME')) && ! $feed_row['channel_id']) {
                    $feed_row['media_row'] = socialEntityInfo($this->container->getParameter('SOCIAL_ENTITY_USER_EVENTS'), $feed_row['action_row']['entity_id']);
                } else if ($feed_row['action_row']['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_CHANNEL_COVER') || $feed_row['action_row']['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_CHANNEL_INFO') || $feed_row['action_row']['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_CHANNEL_PROFILE') || $feed_row['action_row']['entity_type'] == $this->container->getParameter('SOCIAL_ENTITY_CHANNEL_SLOGAN')) {
                    if ($feed_row['action_type'] == $this->container->getParameter('SOCIAL_ACTION_UPDATE')) {
                        $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_id']);
                    } else {
                        $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_row']['entity_id']);
                    }
                } else {
                    // just get the media row
                    if ($feed_row['action_type'] == $this->container->getParameter('SOCIAL_ACTION_UPDATE')) {
                        $feed_row['action_row']['entity_id'] = $feed_row['action_id'];
                    }
                    $feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['entity_id']);
                    if ($feed_row['entity_type'] == SOCIAL_ENTITY_VISITED_PLACES) {
                        $stateinfo = worldStateInfo($feed_row['media_row']['country_code'], $feed_row['media_row']['state_code']);
                        $state_name = (! $stateinfo) ? '' : $stateinfo['state_name'];
                        $country_name = countryGetName($feed_row['media_row']['country_code']);
                        $country_name = (! $country_name) ? '' : $country_name;
                        $feed_row['media_row']['state_name'] = $state_name;
                        $feed_row['media_row']['country_name'] = $country_name;
                    }
                }

                // /////////////////////
                // in case no profile pic and not the channel sponsor action
                if ((! isset($feed_row['profile_Pic']) || $feed_row['profile_Pic'] == '') && $feed_row['action_type'] != $this->container->getParameter('SOCIAL_ACTION_SPONSOR') && $feed_row['action_type'] != $this->container->getParameter('SOCIAL_ACTION_RELATION_SUB') && $feed_row['action_type'] != $this->container->getParameter('SOCIAL_ACTION_RELATION_PARENT')) {
                    $feed_row['profile_Pic'] = 'he.jpg';
                    if ($feed_row['gender'] == 'F') {
                        $feed_row['profile_Pic'] = 'she.jpg';
                    }
                }

                $feed[] = $feed_row;
            }

            return $feed;

            // Case of returning n_results.
        } else {
            $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.ownerId AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_SPONSOR') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_RELATION_SUB') . " AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_RELATION_PARENT') . " $where";

            $query1 = "( $query AND NF.actionType<>" . $this->container->getParameter('SOCIAL_ACTION_UPLOAD') . " ) UNION ( select * from ($query AND NF.actionType=" . $this->container->getParameter('SOCIAL_ACTION_UPLOAD') . " ORDER BY NF.$orderby $order) AS X GROUP BY X.actionType,X.entityType,X.entityGroup,X.feedPrivacy)";
            $query = "select count(X1.id) from ($query1) AS X1 ";
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select, $params);
            $res = $select->execute();
            $row = $select->fetch();

            return $row[0];
        }
    }
}