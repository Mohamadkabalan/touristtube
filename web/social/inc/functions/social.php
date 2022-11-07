<?php

/**
 * all social functionality that deals with the cms_social tables
 * @package social
 */

//ACTIONS
/**
 * the rate action
 */
define('SOCIAL_ACTION_RATE',1);
/**
 * the comment action
 */
define('SOCIAL_ACTION_COMMENT',2);
/**
 * the like action
 */
define('SOCIAL_ACTION_LIKE',3);
/**
 * the upload action
 */
define('SOCIAL_ACTION_UPLOAD',4);
/**
 * the share action
 */
define('SOCIAL_ACTION_SHARE',5);

/**
 * channel sponsors another channel
 */
define('SOCIAL_ACTION_SPONSOR',6);
/**
 * a user connects to a channel
 */
define('SOCIAL_ACTION_CONNECT',7);
/**
 * a channel canceled an event
 */
define('SOCIAL_ACTION_EVENT_CANCEL',8);
/**
 * a user joined an event
 */
define('SOCIAL_ACTION_EVENT_JOIN',9);
/**
 * a user invited some users to a channel, event
 */
define('SOCIAL_ACTION_INVITE',10);
/**
 * a user reported 
 */
define('SOCIAL_ACTION_REPORT',11);
/**
 * a user update 
 */
define('SOCIAL_ACTION_UPDATE',12);
/**
 * a user follow 
 */
define('SOCIAL_ACTION_FOLLOW',13);
/**
 * a user friend 
 */
define('SOCIAL_ACTION_FRIEND',14);
/**
 * a user remove friend 
 */
define('SOCIAL_ACTION_UNFRIEND',15);
/**
 * a user unfollow 
 */
define('SOCIAL_ACTION_UNFOLLOW',16);
/**
 * the reply action
 */
define('SOCIAL_ACTION_REPLY',17);
/**
 * the reechoe action
 */
define('SOCIAL_ACTION_REECHOE',18);
/**
 * the sub channels relations
 */
define('SOCIAL_ACTION_RELATION_SUB',19);
/**
 * the sub channels relations
 */
define('SOCIAL_ACTION_RELATION_PARENT',20);
/**
 * user sent chat message
 */
define('SOCIAL_ACTION_CHAT',21);
/**
 * user sent a friend request
 */
define('SOCIAL_ACTION_FRIEND_REQUEST',22);
/**
 * user shared a location
 */
define('SOCIAL_ACTION_LOCATION_SHARE',23);
/**
 * user sent a voice message
 */
define('SOCIAL_ACTION_VOICE_MESSAGE',24);
/**
 * user shared a file
 */
define('SOCIAL_ACTION_FILE_SHARE',25);
/**
 * user shared a file
 */
define('SOCIAL_ACTION_CALL',26);
/**
 * user shared a file
 */
define('SOCIAL_ACTION_DISCONNECT_CALL',27);


//ENTITIES ADD TO assets/common/js/utils.js
/**
 * cms_videos entity
 */
define('SOCIAL_ENTITY_MEDIA', 1);
/**
 * cms_users entity
 */
define('SOCIAL_ENTITY_USER', 2);
/**
 * cms_users_catalogs entity
 */
define('SOCIAL_ENTITY_ALBUM', 3);
/**
 * cms_webcams entity
 */
define('SOCIAL_ENTITY_WEBCAM', 4);
/**
 * cms_locations entity
 */
define('SOCIAL_ENTITY_LOCATION', 5);
/**
 * cms_journals entity
 */
define('SOCIAL_ENTITY_JOURNAL', 6);
/**
 * cms_journals_items entity
 */
define('SOCIAL_ENTITY_JOURNAL_ITEM', 7);
/**
 * cms_flash entity
 */
define('SOCIAL_ENTITY_FLASH', 8);
/**
 * cms_social_comments entity
 */
define('SOCIAL_ENTITY_COMMENT', 9);
/**
 * cms_social_shares entity
 */
define('SOCIAL_ENTITY_SHARE', 10);
/**
 * cms_channel_news entity
 */
define('SOCIAL_ENTITY_NEWS', 11);
/**
 * cms_channel_event entity
 */
define('SOCIAL_ENTITY_EVENTS', 12);
/**
 * cms_channel_brochure entity
 */
define('SOCIAL_ENTITY_BROCHURE', 13);
/**
 * cms_channel
 */
define('SOCIAL_ENTITY_CHANNEL',14);
/**
 * cms_social_posts
 */
define('SOCIAL_ENTITY_POST', 15);
/**
 * cms_channel_event location
 */
define('SOCIAL_ENTITY_EVENTS_LOCATION', 16);
/**
 * cms_channel_event date
 */
define('SOCIAL_ENTITY_EVENTS_DATE', 17);
/**
 * cms_channel_event time
 */
define('SOCIAL_ENTITY_EVENTS_TIME', 18);
/**
 * cms_channel cover photo
 */
define('SOCIAL_ENTITY_CHANNEL_COVER', 19);
/**
 * cms_channel profile image
 */
define('SOCIAL_ENTITY_CHANNEL_PROFILE', 20);
/**
 * cms_channel slogan
 */
define('SOCIAL_ENTITY_CHANNEL_SLOGAN', 21);
/**
 * cms_channel info
 */
define('SOCIAL_ENTITY_CHANNEL_INFO', 22);
/**
 * cms_users_detail profile image
 */
define('SOCIAL_ENTITY_USER_PROFILE', 23);
/**
 * cms_users_event 
 */
define('SOCIAL_ENTITY_USER_EVENTS', 24);
/**
 * cms_friends 
 */
define('SOCIAL_ENTITY_PROFILE_FRIENDS', 25);
/**
 * cms_subscriptions 
 */
define('SOCIAL_ENTITY_PROFILE_FOLLOWERS', 26);
/**
 * cms_subscriptions 
 */
define('SOCIAL_ENTITY_PROFILE_FOLLOWINGS', 27);
/**
 * discover_hotels 
 */
define('SOCIAL_ENTITY_HOTEL', 28);
/**
 * discover_restaurants 
 */
define('SOCIAL_ENTITY_RESTAURANT', 29);
/**
 *  discover_poi
 */
define('SOCIAL_ENTITY_LANDMARK', 30);
/**
 *  cms_bag
 */
define('SOCIAL_ENTITY_BAG', 31);
/**
 * cms_users 
 */
define('SOCIAL_ENTITY_PROFILE_ABOUT', 32);
/**
 * cms_users 
 */
define('SOCIAL_ENTITY_PROFILE_ACCOUNT', 33);
/**
 * cms_users 
 */
define('SOCIAL_ENTITY_PROFILE_LOCATION', 34);
/**
 * cms_users_visited_places 
 */
define('SOCIAL_ENTITY_PROFILE_VISITED_PLACES', 35);
/**
 * discover_hotels_reviews 
 */
define('SOCIAL_ENTITY_HOTEL_REVIEWS', 36);
/**
 * discover_restaurants_reviews 
 */
define('SOCIAL_ENTITY_RESTAURANT_REVIEWS', 37);
/**
 * discover_poi_reviews 
 */
define('SOCIAL_ENTITY_LANDMARK_REVIEWS', 38);
/**
 * discover_restaurants 
 */
define('SOCIAL_ENTITY_RESTAURANT_CUISINE', 39);
/**
 * discover_restaurants 
 */
define('SOCIAL_ENTITY_RESTAURANT_SERVICE', 40);
/**
 * discover_restaurants 
 */
define('SOCIAL_ENTITY_RESTAURANT_ATMOSPHERE', 41);
/**
 * discover_restaurants 
 */
define('SOCIAL_ENTITY_RESTAURANT_PRICE', 42);
/**
 * discover_restaurants 
 */
define('SOCIAL_ENTITY_RESTAURANT_NOISE', 43);
/**
 * discover_restaurants 
 */
define('SOCIAL_ENTITY_RESTAURANT_TIME', 44);
/**
 * report a bug 
 */
define('SOCIAL_ENTITY_REPORT_BUG', 45);
/**
 * location 
 */
define('SOCIAL_ENTITY_VISITED_PLACES', 47);
/**
 * discover_hotels 
 */
define('SOCIAL_ENTITY_HOTEL_AIRPOT', 48);
/**
 * discover_hotels 
 */
define('SOCIAL_ENTITY_HOTEL_SERVICE', 49);
/**
 * discover_hotels 
 */
define('SOCIAL_ENTITY_HOTEL_CLEANLINESS', 50);
/**
 * discover_hotels 
 */
define('SOCIAL_ENTITY_HOTEL_INTERIOR', 51);
/**
 * discover_hotels 
 */
define('SOCIAL_ENTITY_HOTEL_PRICE', 52);
/**
 * discover_hotels 
 */
define('SOCIAL_ENTITY_HOTEL_FOODDRINK', 53);
/**
 * discover_hotels 
 */
define('SOCIAL_ENTITY_HOTEL_INTERNET', 54);
/**
 * discover_hotels 
 */
define('SOCIAL_ENTITY_HOTEL_NOISE', 55);
/**
 * discover_poi
 */
define('SOCIAL_ENTITY_LANDMARK_FOODAVAILABLE', 56);
/**
 * discover_poi
 */
define('SOCIAL_ENTITY_LANDMARK_BATHROOMFACILITIES', 57);
/**
 * discover_poi
 */
define('SOCIAL_ENTITY_LANDMARK_STAIRS', 58);
/**
 * discover_poi
 */
define('SOCIAL_ENTITY_LANDMARK_STORAGE', 59);
/**
 * discover_poi
 */
define('SOCIAL_ENTITY_LANDMARK_PARKING', 60);
/**
 * discover_poi
 */
define('SOCIAL_ENTITY_LANDMARK_WHEELCHAIR', 61);
/**
 * CUSTOMER SUPPORT
 */
define('SOCIAL_ENTITY_CUSTOME_SUPPORT', 62);
/**
 *  airport
 */
define('SOCIAL_ENTITY_AIRPORT', 63);
/**
 *  airport_reviews
 */
define('SOCIAL_ENTITY_AIRPORT_REVIEWS', 64);
/**
 *  airport
 */
define('SOCIAL_ENTITY_AIRPORT_LUGGAGE', 65);
/**
 *  airport
 */
define('SOCIAL_ENTITY_AIRPORT_RECEPTION', 66);
/**
 *  airport
 */
define('SOCIAL_ENTITY_AIRPORT_LOUNGE', 67);
/**
 *  airport
 */
define('SOCIAL_ENTITY_AIRPORT_FOOD', 68);
/**
 *  airport
 */
define('SOCIAL_ENTITY_AIRPORT_DUTYFREE', 69);
/**
 *  cms_sosial_story
 */
define('SOCIAL_ENTITY_STORY', 70);
/**
 *  webgeocities
 */
define('SOCIAL_ENTITY_CITY', 71);
/**
 *  cms_countries
 */
define('SOCIAL_ENTITY_COUNTRY', 72);
/**
 *  states
 */
define('SOCIAL_ENTITY_STATE', 73);
/**
 *  states
 */
define('SOCIAL_ENTITY_DOWNTOWN', 74);
/**
 *  HOTEL RESERVATION
 */
define('SOCIAL_ENTITY_HOTEL_RESERVATION', 75);
/**
 *  FLIGHT
 */
define('SOCIAL_ENTITY_FLIGHT', 76);
/**
 *  DEAL
 */
define('SOCIAL_ENTITY_DEAL', 77);
/**
 *  DEAL
 */
define('SOCIAL_ENTITY_REGION', 78);
/**
 *  THINGSTODO_CITY from table cms_thingstodo
 */
define('SOCIAL_ENTITY_THINGSTODO_CITY', 79);
/**
 *  THINGSTODO_360 from table cms_thingstodo_details
 */
define('SOCIAL_ENTITY_THINGSTODO_DETAILS', 80);
/**
 *  DEAL_ATTRACTIONS
 */
define('SOCIAL_ENTITY_DEAL_ATTRACTIONS', 81);
/**
 *  hotel_selected_city
 */
define('SOCIAL_ENTITY_HOTEL_SELECTED_CITY', 82);
/**
 *  cms_hotel
 */
define('SOCIAL_ENTITY_HOTEL_HRS', 83);


/**
 * the share types should be copied to utils.js
 */
/**
 * an actual share
 */
define('SOCIAL_SHARE_TYPE_SHARE',1);
/**
 * an invite share
 */
define('SOCIAL_SHARE_TYPE_INVITE',2);
/**
 * a sponsor share
 */
define('SOCIAL_SHARE_TYPE_SPONSOR',3);

/**
 * an actual post text
 */
define('SOCIAL_POST_TYPE_TEXT',1);
/**
 * an actual post photo
 */
define('SOCIAL_POST_TYPE_PHOTO',2);
/**
 * an actual post video
 */
define('SOCIAL_POST_TYPE_VIDEO',3);
/**
 * an actual post link
 */
define('SOCIAL_POST_TYPE_LINK',4);
/**
 * an actual add location
 */
define('SOCIAL_POST_TYPE_LOCATION',5);

/**
 * privacy settings for connections
 */
define('PRIVACY_EXTAND_TYPE_CONNECTIONS',1);
/**
 * privacy settings for sponsors
 */
define('PRIVACY_EXTAND_TYPE_SPONSORS',2);
/**
 * privacy settings for channel log
 */
define('PRIVACY_EXTAND_TYPE_LOG',3);
/**
 * privacy settings for who can join events
 */
define('PRIVACY_EXTAND_TYPE_EVENTJOIN',4);

/**
 * privacy settings kind: public
 */
define('PRIVACY_EXTAND_KIND_PUBLIC',1);
/**
 * privacy settings kind: connections
 */
define('PRIVACY_EXTAND_KIND_CONNECTIONS',2);
/**
 * privacy settings kind: sponsors
 */
define('PRIVACY_EXTAND_KIND_SPONSORS',3);
/**
 * privacy settings kind: private
 */
define('PRIVACY_EXTAND_KIND_PRIVATE',4);
/**
 * privacy settings kind: custom
 */
define('PRIVACY_EXTAND_KIND_CUSTOM',5);

/**
 * an actual channel cover photo
 */
define('CHANNEL_DETAIL_COVER',1);
/**
 * an actual channel profile photo
 */
define('CHANNEL_DETAIL_PROFILE',2);
/**
 * an actual channel slogan
 */
define('CHANNEL_DETAIL_SLOGAN',3);
/**
 * an actual channel info
 */
define('CHANNEL_DETAIL_INFO',4);
/**
 * an actual user profile photo
 */
define('USER_DETAIL_PROFILE',1);

/**
 * data getted from friends
 */
define('FROM_FRIENDS',1);
/**
 * data getted from followings
 */
define('FROM_FOLLOWINGS',2);
/**
 * data getted from channels
 */
define('FROM_CHANNELS',3);

/**
 * activities on Tchannels
 */
define('ACTIVITIES_ON_TCHANNELS',1);
/**
 * activities on Thotels
 */
define('ACTIVITIES_ON_THOTELS',2);
/**
 * activities on Tretaurants
 */
define('ACTIVITIES_ON_TRESTAURANTS',3);
/**
 * activities on Tplanner
 */
define('ACTIVITIES_ON_TPLANNER',4);
/**
 * activities on TT page
 */
define('ACTIVITIES_ON_TTPAGE',5);
/**
 * activities on others’ entries
 */
define('ACTIVITIES_ON_TTPAGE_OTHER',6);
/**
 * activities on T Echoes
 */
define('ACTIVITIES_ON_TECHOES',7);

/**
 * an actual hotel rate types
 */
define('SOCIAL_HOTEL_RATE_TYPE',1);
define('SOCIAL_HOTEL_RATE_TYPE_CLEANLINESS',2);
define('SOCIAL_HOTEL_RATE_TYPE_CONFORT',3);
define('SOCIAL_HOTEL_RATE_TYPE_LOCATION',4);
define('SOCIAL_HOTEL_RATE_TYPE_FACILITIES',5);
define('SOCIAL_HOTEL_RATE_TYPE_STAFF',6);
define('SOCIAL_HOTEL_RATE_TYPE_MONEY',7);

define('SOCIAL_PERMISSION_MEDIA','perm_media');
define('SOCIAL_PERMISSION_ALBUM','perm_album');

/**
 * google map key
 */
define('MAP_KEY','AIzaSyCL53RGsSAL-vteodkWJJZCaRksk3HB02E');

global $CONFIG_EXEPT_ARRAY;
$CONFIG_EXEPT_ARRAY = array(SOCIAL_ENTITY_HOTEL_AIRPOT,SOCIAL_ENTITY_HOTEL_SERVICE,SOCIAL_ENTITY_HOTEL_CLEANLINESS,SOCIAL_ENTITY_HOTEL_INTERIOR,SOCIAL_ENTITY_HOTEL_PRICE,SOCIAL_ENTITY_HOTEL_FOODDRINK,SOCIAL_ENTITY_HOTEL_INTERNET,SOCIAL_ENTITY_HOTEL_NOISE,SOCIAL_ENTITY_LANDMARK_FOODAVAILABLE,SOCIAL_ENTITY_LANDMARK_BATHROOMFACILITIES,SOCIAL_ENTITY_LANDMARK_STAIRS,SOCIAL_ENTITY_LANDMARK_STORAGE,SOCIAL_ENTITY_LANDMARK_PARKING,SOCIAL_ENTITY_LANDMARK_WHEELCHAIR,SOCIAL_ENTITY_RESTAURANT_TIME,SOCIAL_ENTITY_RESTAURANT_NOISE,SOCIAL_ENTITY_RESTAURANT_PRICE,SOCIAL_ENTITY_RESTAURANT_ATMOSPHERE,SOCIAL_ENTITY_RESTAURANT_SERVICE,SOCIAL_ENTITY_RESTAURANT_CUISINE,SOCIAL_ENTITY_HOTEL,SOCIAL_ENTITY_RESTAURANT,SOCIAL_ENTITY_LANDMARK,SOCIAL_ENTITY_AIRPORT,SOCIAL_ENTITY_AIRPORT_LUGGAGE,SOCIAL_ENTITY_AIRPORT_RECEPTION,SOCIAL_ENTITY_AIRPORT_LOUNGE,SOCIAL_ENTITY_AIRPORT_FOOD,SOCIAL_ENTITY_AIRPORT_DUTYFREE);

///////////////////////////////////////////////////////////////////////////////////////////////////////
//likes

/**
 * takes a type of like a returns the original action
 * @param string $in_like 
 * @return string the original action
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function socialDecomposeLike($in_like){
//	return str_replace('like_', '', $in_like);
//}

/**
 * takes a type of comment a returns the like
 * @param string $in_like 
 * @return string the original action
 */
//function socialExpandCommentToLike($in_comment){
//	return 'like_' . $in_comment;
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>


/**
 * adds a comment to the global comment table
 * @param integer $user_id the user making the comment
 * @param integer $fk the foriegn key
 * @param string $entity_type the type of the comment
 * @param string $comment the comment text
 * @param integer $channel_id the cms_channel id invloved with the action
 * @return integer|false false if failed else the id of the new comment
 */
function socialCommentAdd($user_id,$fk,$entity_type,$comment, $channel_id){
//  Changed by Anthony Malak 05-05-2015 to PDO database

	global $dbConn;
	$params  = array();
	$params2 = array();
	$_channel_id = !$channel_id ? 0 : $channel_id;
	if( !$channel_id ){
            if( $entity_type == SOCIAL_ENTITY_NEWS ){
                $data_info = channelNewsInfo($fk);
                $channel_id = $data_info['channelid'];
            }else if( $entity_type == SOCIAL_ENTITY_EVENTS ){
                $data_info = channelEventInfo($fk,-1);
                $channel_id = $data_info['channelid'];
            }else if( $entity_type == SOCIAL_ENTITY_BROCHURE ){
                $data_info = channelBrochureInfo($fk);
                $channel_id = $data_info['channelid'];
            }else if( $entity_type == SOCIAL_ENTITY_POST ){
                $data_info=socialPostsInfo($fk );
                if( intval($data_info['channel_id']) >0 ){
                    $channel_id = $data_info['channel_id'];
                }
            }else if( $entity_type == SOCIAL_ENTITY_STORY ){
                $data_info=socialStoryInfo($fk );
                if( intval($data_info['channel_id']) >0 ){
                    $channel_id = $data_info['channel_id'];
                }
            }else if( $entity_type == SOCIAL_ENTITY_MEDIA ){
                $data_info=getVideoInfo($fk );
                if( intval($data_info['channelid']) >0 ){
                    $channel_id = $data_info['channelid'];
                }
            }else if( $entity_type == SOCIAL_ENTITY_ALBUM ){
                $data_info=userCatalogGet($fk );
                if( intval($data_info['channelid']) >0 ){
                    $channel_id = $data_info['channelid'];
                }
            }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER || $entity_type == SOCIAL_ENTITY_CHANNEL_INFO || $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE || $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
                $data_info=GetChannelDetailInfo($fk );
                if( intval($data_info['channelid']) >0 ){
                    $channel_id = $data_info['channelid'];
                }
            }else if( $entity_type == SOCIAL_ENTITY_CHANNEL ){
                $channel_id = $fk;
            }
	}
	$channel_id = !$channel_id ? 0 : $channel_id;
        $query = "INSERT INTO cms_social_comments (user_id,entity_id,entity_type,comment_text,channel_id) VALUES (:User_id,:Fk,:Entity_type,:Comment,:Channel_id)";
	$params2[] = array( "key" => ":User_id",
                            "value" =>$user_id);
	$params2[] = array( "key" => ":Fk",
                            "value" =>$fk);
	$params2[] = array( "key" => ":Entity_type",
                            "value" =>$entity_type);
	$params2[] = array( "key" => ":Comment",
                            "value" =>$comment);
	$params2[] = array( "key" => ":Channel_id",
                            "value" =>$channel_id);
	
	$insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params2);
	$res    = $insert->execute();
	if(!$res) return false;
	
        $comment_id = $dbConn->lastInsertId();
	
	/////////////////////////////
	//update quick nb_comments values
	$table='';
	if( $entity_type == SOCIAL_ENTITY_MEDIA ){
            $table = 'cms_videos';
//            $where = " id='$fk' ";
            $where = " id=:Fk1 ";
            $params[] = array( "key" => ":Fk1", "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_WEBCAM ){
		$table = 'cms_webcams';
//		$where = " id='$fk' ";
                $where = " id=:Fk2 ";
                $params[] = array( "key" => ":Fk2", "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_LOCATION){
		$table = 'cms_locations';
//		$where = " id='$fk' ";
                $where = " id=:Fk3 ";
                $params[] = array( "key" => ":Fk3", "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_ALBUM ){
		$table = 'cms_users_catalogs';
//		$where = " id='$fk' ";
                $where = " id=:Fk4 ";
                $params[] = array( "key" => ":Fk4", "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_BROCHURE ){
		$table = 'cms_channel_brochure';
//		$where = " id='$fk' ";
                $where = " id=:Fk5 ";
                $params[] = array( "key" => ":Fk5",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_EVENTS ){
		$table = 'cms_channel_event';
//		$where = " id='$fk' ";
                $where = " id=:Fk6 ";
                $params[] = array( "key" => ":Fk6",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ){
		$table = 'cms_users_event';
//		$where = " id='$fk' ";
                $where = " id=:Fk7 ";
                $params[] = array( "key" => ":Fk7",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_NEWS ){
		$table = 'cms_channel_news';
//		$where = " id='$fk' ";
                $where = " id=:Fk8 ";
                $params[] = array( "key" => ":Fk8",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_POST ){
		$table = 'cms_social_posts';
//		$where = " id='$fk' ";
                $where = " id=:Fk9 ";
                $params[] = array( "key" => ":Fk9",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_USER ){
		//cant comment on user?
		//this could be wall.
		return false;
	}else if($entity_type == SOCIAL_ENTITY_CHANNEL ){
		$table = 'cms_channel';
//		$where = " id='$fk' ";
                $where = " id=:Fk10 ";
                $params[] = array( "key" => ":Fk10",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER ){
		$table = 'cms_channel_detail';
//		$where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_COVER." ";
                $where = " id=:Fk11 AND detail_type=".CHANNEL_DETAIL_COVER." ";
                $params[] = array( "key" => ":Fk11",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE ){
		$table = 'cms_channel_detail';
//		$where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
                $where = " id=:Fk12 AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
                $params[] = array( "key" => ":Fk12",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
		$table = 'cms_channel_detail';
//		$where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
                $where = " id=:Fk13 AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
                $params[] = array( "key" => ":Fk13",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_CHANNEL_INFO ){
		$table = 'cms_channel_detail';
//		$where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_INFO." ";
                $where = " id=:Fk14 AND detail_type=".CHANNEL_DETAIL_INFO." ";
                $params[] = array( "key" => ":Fk14",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_JOURNAL ){
		$table = 'cms_journals';
//		$where = " id='$fk' ";
                $where = " id=:Fk15 ";
                $params[] = array( "key" => ":Fk15",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_USER_PROFILE ){
		$table = 'cms_users_detail';
//		$where = " id='$fk' AND detail_type=".USER_DETAIL_PROFILE." ";
                $where = " id=:Fk16 AND detail_type=".USER_DETAIL_PROFILE." ";
                $params[] = array( "key" => ":Fk16",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_HOTEL_REVIEWS ){
		$table = '';
		$where = "";
	}else if( $entity_type == SOCIAL_ENTITY_RESTAURANT_REVIEWS ){
		$table = '';
		$where = "";
	}else if( $entity_type == SOCIAL_ENTITY_LANDMARK_REVIEWS ){
		$table = '';
		$where = "";
	}else if( $entity_type == SOCIAL_ENTITY_AIRPORT_REVIEWS ){
		$table = '';
		$where = "";
	}else if( $entity_type == SOCIAL_ENTITY_FLASH ){
		$table = 'cms_flash';
//		$where = " id='$fk' ";
		$where = " id=:Fk17 ";
                $params[] = array( "key" => ":Fk17",
                                    "value" =>$fk);
	}else if( $entity_type == SOCIAL_ENTITY_STORY ){
		$table = 'cms_sosial_story';
		$where = " id=:Fks ";
                $params[] = array( "key" => ":Fks", "value" =>$fk);
	}else{
		return true;	
	}
	if($table != ''){
            $query = "UPDATE $table SET nb_comments = nb_comments + 1 WHERE $where";
            
//            db_query($query);
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $res    = $update->execute();
        }
	
	if($entity_type == SOCIAL_ENTITY_USER_PROFILE || $entity_type == SOCIAL_ENTITY_MEDIA || $entity_type == SOCIAL_ENTITY_ALBUM || $entity_type == SOCIAL_ENTITY_USER_EVENTS){
            $owner_id = socialEntityOwner($entity_type, $fk);
            addPushNotification(SOCIAL_ACTION_COMMENT, $owner_id, $user_id, 0, $fk, $entity_type);
        }
	//add the comment to the newsfeed
	newsfeedAdd($user_id, $comment_id, SOCIAL_ACTION_COMMENT, $fk, $entity_type , USER_PRIVACY_PUBLIC , $channel_id);
	if( $channel_id && $channel_id!='NULL' && $channel_id){
		$channelInfo=channelGetInfo($channel_id);
		if($user_id!=intval($channelInfo['owner_id'])){
			newsfeedAdd($user_id, $comment_id, SOCIAL_ACTION_COMMENT, $fk, $entity_type , USER_PRIVACY_PRIVATE , null );
		}
	}
	/////////////////////////////
	
	return $comment_id;


}

/**
 * returns the user id of the owner of a comment
 * @param integer $id the comment id
 * @return boolean|integer the owner id of the comment or false if not found
 */
function socialCommentOwner($id){
    global $dbConn;
    $params = array();
    $query = "SELECT user_id FROM cms_social_comments WHERE id=:Id";
    
    $params[] = array( "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($ret) {
            $row = $select->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['user_id'];
            return $user_id;
    }else{
            return false;
    }
}

/**
 * returns the user_id of the owner of a comment
 * @param integer $id the comment id
 * @return boolean|integer the owner id of the comment or false if not found
 */
function socialCommentEntityId($id){


    global $dbConn;
    $params = array();  
//	$query = "SELECT entity_id FROM cms_social_comments WHERE id='$id'";
//	
//	$ret = db_query($query);
//	if (db_num_rows($ret)) {
//		$row = db_fetch_assoc($ret);
//		$entity_id = $row['entity_id'];
//		return $entity_id;
//	}else{
//		return false;
//	}
    $query = "SELECT entity_id FROM cms_social_comments WHERE id=:Id";
    
    $params[] = array( "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res) {
            $row = $select->fetch(PDO::FETCH_ASSOC);
            $entity_id = $row['entity_id'];
            return $entity_id;
    }else{
            return false;
    }


}

/**
 * Deletes all comments associated with a key,type. typically for a foreign key record delete
 * @param integer $fk 
 * @param string $entity_type 
 */
function socialCommentsDelete($fk,$entity_type){


    global $dbConn;
    $params = array();
//	$query = "SELECT id FROM cms_social_comments WHERE entity_id='$fk' AND entity_type='$entity_type' AND published=1 LIMIT 1000"; //delete 1000 records at a time
    $query = "SELECT id FROM cms_social_comments WHERE entity_id=:Entity_id AND entity_type=:Entity_type AND published=1 LIMIT 1000";
    

//	$res = db_query($query);
    $params[] = array( "key" => ":Entity_id", "value" =>$fk);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
//	if(!$res || (db_num_rows($res) == 0) ) $done = true;
    if( $res && ($ret != 0) ) {
        $row = $select->fetchAll(PDO::FETCH_ASSOC);        
        foreach($row as $row_item){
            $ids = $row_item['id'];
            socialCommentDelete($ids,false);
        }
    }


}

/**
 * delete a comment
 * @param integer $id 
 * @param boolean update the foreign table
 * @return boolean true|false if success fail
 */
function socialCommentDelete($id, $update = true){


    global $dbConn;
    $params  = array(); 
    $cr = socialCommentRow($id);

    $entity_type = $cr['entity_type'];
    $fk = $cr['entity_id'];
    $user_id = $cr['user_id'];
    
    if($update){
        $table = null;
        $where = null;
        /////////////////////////////
        //update quick nb_comments values
        if( $entity_type == SOCIAL_ENTITY_MEDIA ){
            $table = 'cms_videos';
//                    $where = " id='$fk' ";
            $where = " id=:Fk ";
            $params[] = array( "key" => ":Fk", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_WEBCAM ){
            $table = 'cms_webcams';
//                    $where = " id='$fk' ";
            $where = " id=:Fk2 ";
            $params[] = array( "key" => ":Fk2", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_JOURNAL ){
            $table = 'cms_journals';
//                    $where = " id='$entity_id' ";
            $where = " id=:Entity_id ";
            $params[] = array( "key" => ":Entity_id", "value" =>$entity_id);
        }else if( $entity_type == SOCIAL_ENTITY_LOCATION){
            $table = 'cms_locations';
//                    $where = " id='$fk' ";
            $where = " id=:Fk3 ";
            $params[] = array( "key" => ":Fk3", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_ALBUM ){
            $table = 'cms_users_catalogs';
//                    $where = " id='$fk' ";
            $where = " id=:Fk4 ";
            $params[] = array( "key" => ":Fk4", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_BROCHURE ){
            $table = 'cms_channel_brochure';
//                    $where = " id='$fk' ";
            $where = " id=:Fk5 ";
            $params[] = array( "key" => ":Fk5", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_EVENTS ){
            $table = 'cms_channel_event';
//                    $where = " id='$fk' ";
            $where = " id=:Fk6 ";
            $params[] = array( "key" => ":Fk6", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ){
            $table = 'cms_users_event';
//                    $where = " id='$fk' ";
            $where = " id=:Fk7 ";
            $params[] = array( "key" => ":Fk7", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_NEWS ){
            $table = 'cms_channel_news';
//                    $where = " id='$fk' ";
            $where = " id=:Fk8 ";
            $params[] = array( "key" => ":Fk7", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_POST ){
            $table = 'cms_social_posts';
//                    $where = " id='$fk' ";
            $where = " id=:Fk9 ";
            $params[] = array( "key" => ":Fk9", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_USER ){
            //cant comment on user?
            //this could be wall.
        }else if($entity_type == SOCIAL_ENTITY_CHANNEL ){
            $table = 'cms_channel';
//                    $where = " id='$fk' ";
            $where = " id=:Fk0 ";
            $params[] = array( "key" => ":Fk10", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER ){
            $table = 'cms_channel_detail';
//                    $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_COVER." ";
            $where = " id=:Fk11 AND detail_type=".CHANNEL_DETAIL_COVER." ";
            $params[] = array( "key" => ":Fk11", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE ){
            $table = 'cms_channel_detail';
//                    $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
            $where = " id=:Fk12 AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
            $params[] = array( "key" => ":Fk12", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
            $table = 'cms_channel_detail';
//                    $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
            $where = " id=:Fk13 AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
            $params[] = array( "key" => ":Fk13", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_INFO ){
            $table = 'cms_channel_detail';
//                    $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_INFO." ";
            $where = " id=:Fk14 AND detail_type=".CHANNEL_DETAIL_INFO." ";
            $params[] = array( "key" => ":Fk14", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_USER_PROFILE ){
            $table = 'cms_users_detail';
//                    $where = " id='$fk' AND detail_type=".USER_DETAIL_PROFILE." ";
            $where = " id=:Fk15 AND detail_type=".USER_DETAIL_PROFILE." ";
            $params[] = array( "key" => ":Fk15", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_HOTEL_REVIEWS ){
            $table = null;
            $where = "";
        }else if( $entity_type == SOCIAL_ENTITY_RESTAURANT_REVIEWS ){
            $table = null;
            $where = "";
        }else if( $entity_type == SOCIAL_ENTITY_LANDMARK_REVIEWS ){
            $table = null;
            $where = "";
	}else if( $entity_type == SOCIAL_ENTITY_AIRPORT_REVIEWS ){
            $table = null;
            $where = "";
	}else if( $entity_type == SOCIAL_ENTITY_FLASH ){
	    $table = 'cms_flash';
//          $where = " id='$fk' ";
            $where = " id=:Fk16 ";
            $params[] = array( "key" => ":Fk16", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_STORY ){
		$table = 'cms_sosial_story';
		$where = " id=:Fks ";
                $params[] = array( "key" => ":Fks", "value" =>$fk);
	}else{
            return false;
        }

        if($table != null){
                $query = "UPDATE $table SET nb_comments = nb_comments - 1 WHERE $where";
                
//                    db_query($query);
                $update = $dbConn->prepare($query);
                PDO_BIND_PARAM($update,$params);
                $res    = $update->execute();
        }
    }

    //$entity_type = socialExpandCommentToLike($entity_type); dont need this
    socialLikesDelete($id, SOCIAL_ENTITY_COMMENT);

    //delete the comment from the newsfeed
    //newsfeedDeleteAll($id,SOCIAL_ENTITY_COMMENT); the social comments are deleted by action

    //delete the comments by id from the newsfeed
    newsfeedDeleteByAction($id,SOCIAL_ACTION_COMMENT);

    if( deleteMode() == TT_DEL_MODE_PURGE ){
//            $query = "DELETE FROM cms_social_comments WHERE id='$id'";
        $query = "DELETE FROM cms_social_comments WHERE id=:Id";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
//            $query = "UPDATE cms_social_comments SET published=".TT_DEL_MODE_FLAG." WHERE id='$id'";
        $query = "UPDATE cms_social_comments SET published=".TT_DEL_MODE_FLAG." WHERE id=:Id";
    }
    
//    return db_query($query);
    $params2 = array(); 
    $params2[] = array( "key" => ":Id", "value" =>$id);
   
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params2);
    $res    = $delete_update->execute();
    return $res;
}

/**
 * gets the social comment row
 * @param integer $id 
 * @return boolean|array or the cms_social_comments row or false if not found
 */
function socialCommentRow($id){


    global $dbConn;
    $socialCommentRow   = tt_global_get('socialCommentRow');  // Added by Devendra on 25th may 2015
    $params = array();
        if(isset($socialCommentRow[$id]) && $socialCommentRow[$id]!=''){
        return $socialCommentRow[$id];
        }   
    //$query = "SELECT * FROM cms_social_comments WHERE id=:Id"; // comented by devendra on 22 may 2015 as told by rishav for query optimization.
    $query = "SELECT `id`, `user_id`, `entity_id`, `entity_type`, `comment_text`, `comment_date`, `published`, `like_value`, `channel_id`, `is_visible` FROM `cms_social_comments` WHERE id=:Id";    
    $params[] = array( "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res && $ret>0) {
        $row = $select->fetch(PDO::FETCH_ASSOC);
        $socialCommentRow[$id]  =   $row;       
        return $row;
    }else{
        $socialCommentRow[$id]  =   false;
        return false;
    }


}

/**
 * edits a comment
 * @param integer $id the comment id
 * @param string $text the new text of the comment
 * @return boolean true|false if success fail
 */
function socialCommentEdit($id,$text){
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_social_comments SET comment_text=:Text,comment_date=NOW() WHERE id=:Id";
    $params[] = array( "key" => ":Text", "value" =>$text);
    $params[] = array( "key" => ":Id", "value" =>$id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * disables a comment
 * @param integer $id the comment's id
 * @return boolean true|false if success fail
 */
function socialCommentDisable($id,$published = 0,$update= true){
    global $dbConn;
    $params  = array();
    $params2 = array();    
    $cr = socialCommentRow($id);
    $entity_type = $cr['entity_type'];
    $fk = $cr['entity_id'];
    $user_id = $cr['user_id'];
    if($update){
        $table = null;
        $where = null;
        if( $entity_type == SOCIAL_ENTITY_MEDIA ){
            $table = 'cms_videos';
            $where = " id=:Fk ";
            $params[] = array( "key" => ":Fk",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_WEBCAM ){
            $table = 'cms_webcams';
//                    $where = " id='$fk' ";
            $where = " id=:Fk2 ";
            $params[] = array( "key" => ":Fk2",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_JOURNAL ){
            $table = 'cms_journals';
//                    $where = " id='$entity_id' ";
            $where = " id=:Entity_id ";
            $params[] = array( "key" => ":Entity_id",
                                "value" =>$entity_id);
        }else if( $entity_type == SOCIAL_ENTITY_LOCATION){
            $table = 'cms_locations';
//                    $where = " id='$fk' ";
            $where = " id=:Fk3 ";
            $params[] = array( "key" => ":Fk3",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_ALBUM ){
            $table = 'cms_users_catalogs';
//                    $where = " id='$fk' ";
            $where = " id=:Fk4 ";
            $params[] = array( "key" => ":Fk4",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_BROCHURE ){
            $table = 'cms_channel_brochure';
//                    $where = " id='$fk' ";
            $where = " id=:Fk5 ";
            $params[] = array( "key" => ":Fk5",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_EVENTS ){
            $table = 'cms_channel_event';
//                    $where = " id='$fk' ";
            $where = " id=:Fk6 ";
            $params[] = array( "key" => ":Fk6",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ){
            $table = 'cms_users_event';
//                    $where = " id='$fk' ";
            $where = " id=:Fk7 ";
            $params[] = array( "key" => ":Fk7",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_NEWS ){
            $table = 'cms_channel_news';
//                    $where = " id='$fk' ";
            $where = " id=:Fk8 ";
            $params[] = array( "key" => ":Fk8",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_POST ){
            $table = 'cms_social_posts';
//                    $where = " id='$fk' ";
            $where = " id=:Fk9 ";
            $params[] = array( "key" => ":Fk9",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_USER ){
                //cant comment on user?
                //this could be wall.
        }else if($entity_type == SOCIAL_ENTITY_CHANNEL ){
            $table = 'cms_channel';
//                    $where = " id='$fk' ";
            $where = " id=:Fk10 ";
            $params[] = array( "key" => ":Fk10",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER ){
            $table = 'cms_channel_detail';
//                    $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_COVER." ";
            $where = " id=:Fk11 AND detail_type=".CHANNEL_DETAIL_COVER." ";
            $params[] = array( "key" => ":Fk11",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE ){
            $table = 'cms_channel_detail';
//                    $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
            $where = " id=:Fk12 AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
            $params[] = array( "key" => ":Fk12",
                                    "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
            $table = 'cms_channel_detail';
//                    $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
            $where = " id=:Fk13 AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
            $params[] = array( "key" => ":Fk13",
                                    "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_INFO ){
            $table = 'cms_channel_detail';
//                    $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_INFO." ";
            $where = " id=:Fk14 AND detail_type=".CHANNEL_DETAIL_INFO." ";
            $params[] = array( "key" => ":Fk14",
                                    "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_USER_PROFILE ){
            $table = 'cms_users_detail';
//                    $where = " id='$fk' AND detail_type=".USER_DETAIL_PROFILE." ";
            $where = " id=:Fk15 AND detail_type=".USER_DETAIL_PROFILE." ";
            $params[] = array( "key" => ":Fk15",
                                    "value" =>$fk);
				    
        }else if( $entity_type == SOCIAL_ENTITY_HOTEL_REVIEWS ){
            $table = null;
            $where = "";
        }else if( $entity_type == SOCIAL_ENTITY_RESTAURANT_REVIEWS ){
            $table = null;
            $where = "";
        }else if( $entity_type == SOCIAL_ENTITY_LANDMARK_REVIEWS ){
            $table = null;
            $where = "";
}else if( $entity_type == SOCIAL_ENTITY_AIRPORT_REVIEWS ){
                        $table = null;
                        $where = "";
                }else if( $entity_type == SOCIAL_ENTITY_FLASH ){
            $table = 'cms_flash';
//                    $where = " id='$fk' ";
            $where = " id=:Fk16 ";
            $params[] = array( "key" => ":Fk16",
                                    "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_STORY ){
		$table = 'cms_sosial_story';
		$where = " id=:Fks ";
                $params[] = array( "key" => ":Fks", "value" =>$fk);
	}else{
            return false;
        }

        if($table != null){
            $query = "UPDATE $table SET nb_comments = nb_comments + 1 WHERE $where";
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $res    = $update->execute();
        }
    }

    $published=intval($published);
    $query2 = "UPDATE cms_social_comments SET published=:Published WHERE id=:Id";
    $params2[] = array( "key" => ":Id",
                        "value" =>$id);
    $params2[] = array( "key" => ":Published",
                        "value" =>$published);
    
    $update2 = $dbConn->prepare($query2);
    PDO_BIND_PARAM($update2,$params2);
    $res     = $update2->execute();
    return $res;
}
/**
 * disables a comment
 * @param integer $id the comment's id
 * @return boolean true|false if success fail
 */
function socialCommentShowHide($id,$is_visible = 0){
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_social_comments SET is_visible=:Is_visible WHERE id=:Id";

    $params[] = array( "key" => ":Is_visible",
                        "value" =>$is_visible);
    $params[] = array( "key" => ":Id",
                        "value" =>$id);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    return $res;
}

/**
 * gets the comments
 * @param array $options<br/>
 * <b>limit</b>: the maximum number of user records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>entity_id</b>: which entity key. required.<br/>
 * <b>entity_type</b>: what type of comment. required.<br/>
 * <b>from_ts</b>: search for comments after this date. default null.<br/>
 * <b>to_ts</b>: search for comments before this date. default null.<br/>
 * <b>is_visible</b>: 1=> visible, 0=> invisible. -1 => doesnt matter. default -1.<br/>
 * <b>distinct_user</b>: 1=> distinct user, 0=> doesnt matter. default 0.<br/>
 * <b>published</b>: comment is published or not. null => doesnt matter. default 1</br>
 * <b>n_results</b>: return records or number of results. default false.<br/>
 * @return integer|array a set of 'newsfeed records' could either be a comment, of an upload
 */
function socialCommentsGet($srch_options){


	global $dbConn;
	$params = array();
	//$nlimit = 5, $page = 0, $sortby = 'comment_date' , $sort = 'DESC';
	
	$default_opts = array(
		'limit' => 10,
		'page' => 0,
		'orderby' => 'id',
		'order' => 'a',
		'entity_id' => null,
		'entity_type' => null,
		'is_visible' => 1,
		'distinct_user' => 0,
                'escape_user' => null,
                'include_channels' => null,
                'include_ids' => null,
		'from_ts' => null,
		'to_ts' => null,
		'user_id' => null,
		'n_results' => false,
		'published' => 1
	);

	$options = array_merge($default_opts, $srch_options);

	$nlimit = intval($options['limit']);
	$skip = intval($options['page']) * $nlimit;

	$orderby = $options['orderby'];
	$order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
	
	
	$where = '';
	if( $options['user_id'] ){
            if( $where != '') $where .= ' AND ';
//		$where .= " C.user_id='{$options['user_id']}' ";
            $where .= " C.user_id=:User_id ";
            $params[] = array( "key" => ":User_id",
                                "value" =>$options['user_id']);
	}
	if( $options['entity_type'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " C.entity_type='{$options['entity_type']}' ";
            $where .= " C.entity_type=:Entity_type ";
            $params[] = array( "key" => ":Entity_type",
                                "value" =>$options['entity_type']);
	}
	if( $options['entity_id'] ){
            if( $where != '') $where .= ' AND ';
//		$where .= " C.entity_id='{$options['entity_id']}' ";
            $where .= " C.entity_id=:Entity_id ";
            $params[] = array( "key" => ":Entity_id",
                                "value" =>$options['entity_id']);
	}
	
	if( $options['published'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " C.published={$options['published']} ";
            $where .= " C.published=:Published ";
            $params[] = array( "key" => ":Published",
                                "value" =>$options['published']);
	}
	
	if( $options['is_visible'] != -1 ){
            if( $where != '') $where .= ' AND ';
//            $where .= " C.is_visible={$options['is_visible']} ";
            $where .= " C.is_visible=:Is_visible ";
            $params[] = array( "key" => ":Is_visible",
                                "value" =>$options['is_visible']);
	}
	if($options['from_ts']){
            if( $where != '') $where .= " AND ";
//            $where .= " DATE(C.comment_date) >= '{$options['from_ts']}' ";
            $where .= " DATE(C.comment_date) >= :From_ts ";
            $params[] = array( "key" => ":From_ts",
                                "value" =>$options['from_ts']);
	}	
	if($options['to_ts']){
            if( $where != '') $where .= " AND ";
//            $where .= " DATE(C.comment_date) <= '{$options['to_ts']}' ";
            $where .= " DATE(C.comment_date) <= :To_ts ";
            $params[] = array( "key" => ":To_ts",
                                "value" =>$options['to_ts']);
	}
        if($options['escape_user']){
            if( $where != '') $where .= " AND ";
//            $where .= " C.user_id NOT IN({$options['escape_user']}) ";
            $where .= " NOT find_in_set(cast(C.user_id as char), :Escape_user) ";
	$params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
        }
        if( $options['include_ids'] ){
            if( $where != '') $where .= " AND ";
//            $where .= " C.user_id IN({$options['include_ids']}) ";
            $where .= " find_in_set(cast(C.user_id as char), :Include_ids) ";
            $params[] = array( "key" => ":Include_ids", "value" =>$options['include_ids']);
        }
	
	if(!$options['n_results'] ){
            if( $options['distinct_user']==1 ){
    //                    $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic FROM cms_social_comments AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where GROUP BY C.user_id ORDER BY $orderby $order LIMIT $skip,$nlimit";
                $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM cms_social_comments AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where GROUP BY C.user_id ORDER BY $orderby $order LIMIT :Skip,:Nlimit";
            }else{
    //                    $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic FROM cms_social_comments AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where ORDER BY $orderby $order LIMIT $skip,$nlimit";
                $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM cms_social_comments AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where ORDER BY $orderby $order LIMIT :Skip,:Nlimit";
            }
            $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
            $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
            //return $query;
    //		$ret = db_query ( $query );
    //		if ( db_num_rows ( $ret ) ){
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();

            $ret    = $select->rowCount();
            if ( $res && $ret>0 ){
                $ret_arr = array();
                $row = $select->fetchAll(PDO::FETCH_ASSOC);
    //			while($row = db_fetch_assoc ( $ret )){                
                foreach($row as $row_item){
                    if( $row_item['profile_Pic'] == ''){
                        $row_item['profile_Pic'] = 'he.jpg';
                        if ( $row_item['gender'] == 'F') {
                            $row_item['profile_Pic'] = 'she.jpg';
                        }
                    }
                    $ret_arr[] = $row_item;
                }
                return $ret_arr;
            }else{
                return array();
            }
	}else{
            /*
             * TODO REPLACE with the table specific comment count field ex cms_videos(nb_comments)
             */
            if( $options['distinct_user']==1 ){
                $query = "SELECT COUNT( DISTINCT C.user_id ) FROM cms_social_comments AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where";
            }else{
                $query = "SELECT COUNT(C.id) FROM cms_social_comments AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where";
            }
            //return $query;
//            $ret = db_query($query);
//            $row = db_fetch_row($ret);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
            $row = $select->fetch();
            return $row[0];
	}


}


/**
 * gets the comments
 * @param array $options<br/>
 * <b>limit</b>: the maximum number of user records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>media_id</b>: which foreign key. required.<br/>
 * <b>published</b>: status of the comment. default 1. 0 => hidden. null => doesnt matter<br/>
 * <b>n_results</b>: return records or number of results. default false.<br/>
 * @return integer|array a set of 'newsfeed records' could either be a comment, of an upload
 */
function socialMediaCommentsSpecialGet($srch_options){


    global $dbConn;
    $params = array();  

    $default_opts = array(
            'limit' => 5,
            'page' => 0,
            'orderby' => 'id',
            'order' => 'a',
            'entity_type' => SOCIAL_ENTITY_MEDIA,
            'media_id' => null,
            'n_results' => false,
            'published' => 1
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';

    $media_id = $options['media_id'];
    $entity_type = $options['entity_type'];

    //gets the albums 
    /*$query = "SELECT catalog_id FROM cms_videos_catalogs WHERE video_id='$media_id'";
    $res = db_query($query);
    $albums = array();
    if($res && (db_num_rows($res)!=0) ){
            while($row = db_fetch_row($res)){
                    $albums[] = $row[0];
            }
    }*/

    //$entity_type = SOCIAL_ENTITY_MEDIA;
//    $where = "entity_id=$media_id AND entity_type='$entity_type'";
    $where = "entity_id=:Media_id AND entity_type=:Entity_type";
    $params[] = array( "key" => ":Media_id",
                        "value" =>$media_id);
    $params[] = array( "key" => ":Entity_type",
                        "value" =>$entity_type);
    /*if( count($albums)!=0 ){
            $entity_type = SOCIAL_ENTITY_ALBUM;
            $albums = implode(',',$albums);
            $where = "( ( $where ) OR (entity_id IN ($albums) AND entity_type='$entity_type' ) )";
    }*/

    if( $options['published'] ){
        if($where != '') $where = " $where AND ";
//            $where .= " C.published='{$options['published']}' ";
        $where .= " C.published=:Published ";
        $params[] = array( "key" => ":Published",
                            "value" =>$options['published']);
    }

    if(!$options['n_results'] ){
//            $query = "SELECT
//                                    C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic
//                    FROM
//                            cms_social_comments AS C
//                            INNER JOIN cms_users AS U ON C.user_id=U.id
//                    WHERE
//                             $where ORDER BY $orderby $order LIMIT $skip,$nlimit";
        $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic
                FROM cms_social_comments AS C INNER JOIN cms_users AS U ON C.user_id=U.id
                WHERE $where ORDER BY $orderby $order LIMIT :Skip,:Nlimit";
        
//            $ret = db_query ( $query );

        $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res   = $select->execute();

        $ret    = $select->rowCount();
//            if ( db_num_rows ( $ret ) ){
        if ( $res && $ret>0){            
            $row = $select->fetchAll(PDO::FETCH_ASSOC);
            return $row;
        }else{
            return array();
        }
    }else{
        //TODO REPLACE with the table specific comment count field ex cms_videos(nb_comments)
        $query = "SELECT COUNT(id) FROM cms_social_comments AS C WHERE $where";
        
//            $res = db_query ( $query );
//            $row = db_fetch_array($res);
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();
        $row = $select->fetch();
        return $row[0];
    }


	
}

/**
 * the maximum ammount of spam reports before a user is reported as a spammer 
 */
define('MAX_COMMENT_SPAM_COUNT', 10);
define('MAX_COMMENT_SPAM_HOURS', 24);

/**
 * checks if a user already marked a comment as spam
 * @param integer $reporter_id the reporter's user id
 * @param integer $comment_id the comment's id
 * @return boolean true|false if the comment has already been reported
 */
function socialCommentReported($reporter_id,$comment_id){


    global $dbConn;
    $params = array(); 
//    $query = "SELECT reporter_id FROM cms_social_comments_spam WHERE reporter_id='$reporter_id' AND comment_id='$comment_id'";
    $query = "SELECT reporter_id FROM cms_social_comments_spam WHERE reporter_id=:Reporter_id AND comment_id=:Comment_id";
    $params[] = array( "key" => ":Reporter_id",
                        "value" =>$reporter_id);
    $params[] = array( "key" => ":Comment_id",
                        "value" =>$comment_id);
    
//    $ret = db_query($query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
//    if(!$ret || (db_num_rows($ret) == 0) ) return false;
    if(!$res || ($ret == 0) ) return false;
    else return true;


}

/**
 * reports a comment as spam
 * @param type $reporter_id the user id of the reporter
 * @param type $comment_id the comment's id
 * @return boolean true|false if success|fail  
 */
function socialCommentReportSpam($reporter_id,$comment_id){


    global $dbConn;
    $params  = array();
    $params2 = array();
    $commenter_id = commentOwner($comment_id);
    if(!$commenter_id) return false;
//    $query = "INSERT INTO cms_social_comments_spam (commenter_id,comment_id,reporter_id) VALUES ($commenter_id,$comment_id,$reporter_id)";
    $query = "INSERT INTO cms_social_comments_spam (commenter_id,comment_id,reporter_id) VALUES (:Commenter_id,:Comment_id,:Reporter_id)";
	$params[] = array( "key" => ":Commenter_id", "value" =>$commenter_id);
	$params[] = array( "key" => ":Comment_id", "value" =>$comment_id);
	$params[] = array( "key" => ":Reporter_id", "value" =>$reporter_id);
    
//    $ret = db_query($query);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res1    = $insert->execute();
//    $spam_record_id = db_insert_id();
    $spam_record_id = $dbConn->lastInsertId();

//    $query = "SELECT COUNT(report_ts) FROM cms_social_comments_spam WHERE commenter_id='$commenter_id' AND HOUR( TIMEDIFF(report_ts,NOW()) ) > -" . MAX_COMMENT_SPAM_HOURS;
    $query = "SELECT COUNT(report_ts) FROM cms_social_comments_spam WHERE commenter_id=:Commenter_id AND HOUR( TIMEDIFF(report_ts,NOW()) ) > -" . MAX_COMMENT_SPAM_HOURS;
    
//    $ret2 = db_query($query);
//    $row = db_fetch_array($ret2);
    $params2[] = array( "key" => ":Commenter_id",
                        "value" =>$commenter_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params2);
    $res    = $select->execute();
    $row = $select->fetch();
    $n_reports = $row[0];

    if($n_reports >= MAX_COMMENT_SPAM_COUNT){
            userDisable($commenter_id);
            emailSpam($spam_record_id);
    }

    return $res1;


}

/**
 * add the story record 
 * @param integer $entity_goup the entity goup related to cms_social_newsfeed
 * @param integer $user_id the user id
 * @param integer $channel_id the channel id
 * @return integer | false the newly inserted cms_sosial_story id or false if not inserted
 */
function AddStoryData($entity_goup,$user_id,$channel_id) {
    global $dbConn;
    $params = array();
    $channel_id = (!$channel_id || $channel_id=='')?0:$channel_id;
    $query = "SELECT id FROM cms_sosial_story WHERE entity_group = :Entity_group AND user_id = :User_id AND channel_id = :Channel_id AND published = 1 LIMIT 1";
    $params[] = array(  "key" => ":Entity_group", "value" =>$entity_goup);
    $params[] = array(  "key" => ":User_id", "value" =>$user_id);
    $params[] = array(  "key" => ":Channel_id", "value" =>$channel_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    if ($res && $ret > 0) {
        $row = $select->fetch();
        return $row['id'];
    }else{
        $query = "INSERT INTO cms_sosial_story (entity_group,user_id,channel_id) VALUES (:Entity_group,:User_id,:Channel_id)";
        $insert = $dbConn->prepare($query);
        PDO_BIND_PARAM($insert,$params);
        $res    = $insert->execute();
        $ret    = $dbConn->lastInsertId();
        return ( $res ) ? $ret : false;
    }
}
/**
 * gets the story info
 * @param integer $id the story's id, 
 * @return array | false the cms_sosial_story record or null if not found
 */
function socialStoryInfo($id){
	global $dbConn;
        $socialStoryInfo = tt_global_get('socialStoryInfo');
	$params = array(); 
        if(isset($socialStoryInfo[$id]) && $socialStoryInfo[$id]!='')
                return $socialStoryInfo[$id];
	$query = "SELECT * FROM cms_sosial_story WHERE id=:Id";
	$params[] = array( "key" => ":Id", "value" =>$id);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
	if($res && $ret!=0 ){
		$row = $select->fetch(PDO::FETCH_ASSOC);
                $socialStoryInfo[$id]    =   $row;
		return $row;
	}else{
                $socialStoryInfo[$id]    =   false;
		return false;
	}
}
/////////////////////////////////////////////

/**
 * gets the like record
 * @param integer $user_id the user making the like
 * @param integer $entity_id the foriegn key
 * @param integer $entity_type the type of the like
 * @return integer|false false if not liked else the like value
 */
function socialLikeRecordGet($user_id,$entity_id,$entity_type){


    global $dbConn;
    $params = array();  
//    $query = "SELECT * FROM cms_social_likes WHERE user_id='$user_id' AND entity_id='$entity_id' AND entity_type='$entity_type' AND published=1";
//    
//    $res = db_query($query);
//    if(!$res || (db_num_rows($res)==0) ) return false;
//    else{
//            $row = db_fetch_assoc($res);
//            return $row;
//    } 
    $query = "SELECT * FROM cms_social_likes WHERE user_id=:User_id AND entity_id=:Entity_id AND entity_type=:Entity_type AND published=1";
    
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Entity_id",
                        "value" =>$entity_id);
    $params[] = array( "key" => ":Entity_type",
                        "value" =>$entity_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if(!$res || ($ret==0) ) return false;
    else{
            $row = $select->fetch(PDO::FETCH_ASSOC);
            return $row;
    }


}

/**
 * check if a user liked the object
 * @param integer $user_id the user making the like
 * @param integer $entity_id the foriegn key
 * @param integer $entity_type the type of the like
 * @return integer|false false if not liked else the like value
 */
function socialLiked($user_id,$entity_id,$entity_type){
	$rec = socialLikeRecordGet($user_id,$entity_id,$entity_type);
	if($rec === false) return false;
	return intval($rec['like_value']);
}

/**
 * adds a like to the global like table - DOESNT CHECK FOR PREVIOUS LIKE
 * @param integer $user_id the user making the like
 * @param integer $entity_id the foriegn key
 * @param integer $entity_type the type of the like
 * @param integer $like_value the like value
 * @param integer $channel_id the cms_channel id invloved with the action
 * @return integer|false false if failed else the id of the new like
 */
function socialLikeAdd($user_id,$entity_id,$entity_type,$like_value,$channel_id){	


    global $dbConn;
    $params  = array();
    $params2 = array();
    $_channel_id = !$channel_id ? 'NULL' : $channel_id;
    if( !$channel_id ){
        if( $entity_type == SOCIAL_ENTITY_NEWS ){
            $data_info = channelNewsInfo($entity_id);
            $channel_id = $data_info['channelid'];
        }else if( $entity_type == SOCIAL_ENTITY_EVENTS ){
            $data_info = channelEventInfo($entity_id,-1);
            $channel_id = $data_info['channelid'];
        }else if( $entity_type == SOCIAL_ENTITY_BROCHURE ){
            $data_info = channelBrochureInfo($entity_id);
            $channel_id = $data_info['channelid'];
        }else if( $entity_type == SOCIAL_ENTITY_POST ){
            $data_info=socialPostsInfo($entity_id );
            if( intval($data_info['channel_id']) >0 ){
                    $channel_id = $data_info['channel_id'];
            }
        }else if( $entity_type == SOCIAL_ENTITY_STORY ){
            $data_info=socialStoryInfo($entity_id );
            if( intval($data_info['channel_id']) >0 ){
                    $channel_id = $data_info['channel_id'];
            }
        }else if( $entity_type == SOCIAL_ENTITY_MEDIA ){
            $data_info=getVideoInfo($entity_id );
            if( intval($data_info['channelid']) >0 ){
                    $channel_id = $data_info['channelid'];
            }
        }else if( $entity_type == SOCIAL_ENTITY_ALBUM ){
            $data_info=userCatalogGet($entity_id );
            if( intval($data_info['channelid']) >0 ){
                    $channel_id = $data_info['channelid'];
            }
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER || $entity_type == SOCIAL_ENTITY_CHANNEL_INFO || $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE || $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
            $data_info=GetChannelDetailInfo($entity_id );
            if( intval($data_info['channelid']) >0 ){
                    $channel_id = $data_info['channelid'];
            }
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL ){
            $channel_id = $entity_id;
        }
    }
    $channel_id = !$channel_id ? 'NULL' : $channel_id;
    if( !$channel_id || $channel_id=='NULL' ) $channel_id=0;
    $query = "INSERT INTO cms_social_likes (user_id,entity_id,entity_type,like_value,channel_id) VALUES (:User_id,:Entity_id,:Entity_type,:Like_value,:Channel_id)";
    $params2[] = array( "key" => ":User_id", "value" =>$user_id);
    $params2[] = array( "key" => ":Entity_id", "value" =>$entity_id);
    $params2[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $params2[] = array( "key" => ":Like_value", "value" =>$like_value);
    $params2[] = array( "key" => ":Channel_id", "value" =>$channel_id);
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params2);
    $res    = $insert->execute();
    if(!$res) return false;

    $like_id = db_insert_id();
    $channel_id_feeds=$channel_id;
    if( $entity_type == SOCIAL_ENTITY_ALBUM ){
        $table = 'cms_users_catalogs';
        $where = " id=:Entity_id ";
	$params[] = array( "key" => ":Entity_id",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_JOURNAL ){
        $table = 'cms_journals';
        $where = " id=:Entity_id2 ";
	$params[] = array( "key" => ":Entity_id2",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_JOURNAL_ITEM ){
        $table = 'cms_journals_items';
        $where = " id=:Entity_id3 ";
	$params[] = array( "key" => ":Entity_id3",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_MEDIA ){
        $table = 'cms_videos';
        $where = " id=:Entity_id4 ";
	$params[] = array( "key" => ":Entity_id4",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_WEBCAM ){
        $table = 'cms_webcams';
        $where = " id=:Entity_id5 ";
	$params[] = array( "key" => ":Entity_id5",
                            "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_LOCATION){
        $table = 'cms_locations';
        $where = " id=:Entity_id6 ";
	$params[] = array( "key" => ":Entity_id6",
                            "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_FLASH){
        $table = 'cms_flash';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id7 ";
	$params[] = array( "key" => ":Entity_id7",
                            "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_COMMENT ){
        $table = 'cms_social_comments';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id9 ";
	$params[] = array( "key" => ":Entity_id9",
                            "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_NEWS ){
        $table = 'cms_channel_news';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id10 ";
	$params[] = array( "key" => ":Entity_id10",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ){
        $table = 'cms_users_event';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id11 ";
	$params[] = array( "key" => ":Entity_id11",
                            "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_EVENTS ){
        $table = 'cms_channel_event';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id12 ";
	$params[] = array( "key" => ":Entity_id12",
                            "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_BROCHURE ){
        $table = 'cms_channel_brochure';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id13 ";
	$params[] = array( "key" => ":Entity_id13",
                            "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_POST ){
        $table = 'cms_social_posts';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id14 ";
	$params[] = array( "key" => ":Entity_id14",
                            "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_CHANNEL ){
        $table = 'cms_channel';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id15 ";
	$params[] = array( "key" => ":Entity_id15",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER ){
        $table = 'cms_channel_detail';
//        $where = " id='$entity_id' AND detail_type=".CHANNEL_DETAIL_COVER." ";
        $where = " id=:Entity_id16 AND detail_type=".CHANNEL_DETAIL_COVER." ";
	$params[] = array( "key" => ":Entity_id16",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE ){
        $table = 'cms_channel_detail';
//        $where = " id='$entity_id' AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
        $where = " id=:Entity_id17 AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
	$params[] = array( "key" => ":Entity_id17",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
        $table = 'cms_channel_detail';
//        $where = " id='$entity_id' AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
        $where = " id=:Entity_id18 AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
	$params[] = array( "key" => ":Entity_id18",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_INFO ){
        $table = 'cms_channel_detail';
//        $where = " id='$entity_id' AND detail_type=".CHANNEL_DETAIL_INFO." ";
        $where = " id=:Entity_id19 AND detail_type=".CHANNEL_DETAIL_INFO." ";
	$params[] = array( "key" => ":Entity_id19",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_USER_PROFILE ){
        $table = 'cms_users_detail';
//        $where = " id='$entity_id' AND detail_type=".USER_DETAIL_PROFILE." ";
        $where = " id=:Entity_id20 AND detail_type=".USER_DETAIL_PROFILE." ";
	$params[] = array( "key" => ":Entity_id20",
                            "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_STORY ){
        $table = 'cms_sosial_story';
        $where = " id=:Entity_ids ";
	$params[] = array( "key" => ":Entity_ids", "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_HOTEL_REVIEWS ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_RESTAURANT_REVIEWS ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_LANDMARK_REVIEWS ){
		$table = '';
		$where = "";
    }else if( $entity_type == SOCIAL_ENTITY_AIRPORT_REVIEWS ){
        $table = '';
        $where = "";
    }else{
        return false;
    }
    if($table!=''){
        $query = "UPDATE $table SET like_value = like_value + 1 WHERE $where";
	$update = $dbConn->prepare($query);
	PDO_BIND_PARAM($update,$params);
	$res    = $update->execute();
    }
    if( $like_value == 1 ){
        if($entity_type == SOCIAL_ENTITY_USER_PROFILE || $entity_type == SOCIAL_ENTITY_MEDIA || $entity_type == SOCIAL_ENTITY_ALBUM || $entity_type == SOCIAL_ENTITY_USER_EVENTS){
            $owner_id = socialEntityOwner($entity_type, $entity_id);
            addPushNotification(SOCIAL_ACTION_LIKE, $owner_id, $user_id, 0, $entity_id, $entity_type);
        }
        //only add likes to the newsfeed
        newsfeedAdd($user_id, $like_id, SOCIAL_ACTION_LIKE, $entity_id, $entity_type, USER_PRIVACY_PUBLIC , $channel_id_feeds);
        if( $channel_id_feeds && $channel_id_feeds!='NULL' && $channel_id_feeds){
            $channelInfo=channelGetInfo( $channel_id_feeds );
            if($user_id!=intval($channelInfo['owner_id'])){
                newsfeedAdd($user_id, $like_id, SOCIAL_ACTION_LIKE, $entity_id, $entity_type, USER_PRIVACY_PRIVATE , null );
            }
        }
    }
    return $like_id;
}
/**
 * gets the social like row 
 * @param integer $id which row
 * @return array|false the row if found or false if not
 */
function socialLikeRow($id){


    global $dbConn;
    $socialLikeRow  =   tt_global_get('socialLikeRow');  //Added by Devendra on 25th may 2015

    if(isset($socialLikeRow[$id]) && $socialLikeRow[$id]!=''){ 
        return $socialLikeRow[$id];
    }

    $params = array();

    //$query = "SELECT * FROM cms_social_likes WHERE id='$id'"; commented by Devendra on 22 may 2015
    $query = "SELECT `id`, `user_id`, `entity_id`, `entity_type`, `like_date`, `like_value`, `published`, `channel_id` FROM `cms_social_likes` WHERE id=:Id";
    
    $params[] = array( "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if ($res) {
        $row = $select->fetch(PDO::FETCH_ASSOC);       
        $socialLikeRow[$id] =   $row;
        return $row;
    }else{
        $socialLikeRow[$id]  =   false;     
        return false;
    }


}

/**
 * deletes a like
 * @param integer $id
 * @param boolean $update 
 * @return boolean true|false if success|fail
 */
function socialLikeDelete($id, $update = true){


    global $dbConn;
    $params  = array();  
    $params2 = array(); 
    $lr = socialLikeRow($id);
    if( $lr === false ) return false;

    $entity_type = $lr['entity_type'];
    $fk = $lr['entity_id'];
    $like_value = $lr['like_value'];
    $user_id = $lr['user_id'];

    if($update){

        if( $entity_type == SOCIAL_ENTITY_ALBUM ){
            $table = 'cms_users_catalogs';
//            $where = " id='$fk' ";
            $where = " id=:Fk ";
            $params[] = array( "key" => ":Fk",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_JOURNAL){
            $table = 'cms_journals';
//            $where = " id='$fk' ";
            $where = " id=:Fk2 ";
            $params[] = array( "key" => ":Fk2",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_JOURNAL_ITEM ){
            $table = 'cms_journals_items';
//            $where = " id='$fk' ";
            $where = " id=:Fk3 ";
            $params[] = array( "key" => ":Fk3",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_MEDIA ){
            $table = 'cms_videos';
//            $where = " id='$fk' ";
            $where = " id=:Fk4 ";
            $params[] = array( "key" => ":Fk4",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_WEBCAM ){
            $table = 'cms_webcams';
//            $where = " id='$fk' ";
            $where = " id=:Fk5 ";
            $params[] = array( "key" => ":Fk5",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_LOCATION){
            $table = 'cms_locations';
//            $where = " id='$fk' ";
            $where = " id=:Fk6 ";
            $params[] = array( "key" => ":Fk6",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_FLASH){
            $table = 'cms_flash';
//            $where = " id='$fk' ";
            $where = " id=:Fk7 ";
            $params[] = array( "key" => ":Fk7",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_COMMENT ){
            $table = 'cms_social_comments';
//            $where = " id='$fk' ";
            $where = " id=:Fk8 ";
            $params[] = array( "key" => ":Fk8",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ){
            $table = 'cms_users_event';
//            $where = " id='$fk' ";
            $where = " id=:Fk10 ";
            $params[] = array( "key" => ":Fk10",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_NEWS ){
            $table = 'cms_channel_news';
//            $where = " id='$fk' ";
            $where = " id=:Fk11 ";
            $params[] = array( "key" => ":Fk11",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_EVENTS ){
            $table = 'cms_channel_event';
//            $where = " id='$fk' ";
            $where = " id=:Fk12 ";
            $params[] = array( "key" => ":Fk12",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_BROCHURE ){
            $table = 'cms_channel_brochure';
//            $where = " id='$fk' ";
            $where = " id=:Fk13 ";
            $params[] = array( "key" => ":Fk13",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_POST ){
            $table = 'cms_social_posts';
//            $where = " id='$fk' ";
            $where = " id=:Fk14 ";
            $params[] = array( "key" => ":Fk14",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_CHANNEL ){
            $table = 'cms_channel';
//            $where = " id='$fk' ";
            $where = " id=:Fk15 ";
            $params[] = array( "key" => ":Fk15",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER ){
            $table = 'cms_channel_detail';
//            $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_COVER." ";
            $where = " id=:Fk16 AND detail_type=".CHANNEL_DETAIL_COVER." ";
            $params[] = array( "key" => ":Fk16",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE ){
            $table = 'cms_channel_detail';
//            $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_PROFILE." ";/
            $where = " id=:Fk17 AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
            $params[] = array( "key" => ":Fk17",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
            $table = 'cms_channel_detail';
//            $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
            $where = " id=:Fk18 AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
            $params[] = array( "key" => ":Fk18",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_INFO ){
            $table = 'cms_channel_detail';
//            $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_INFO." ";
            $where = " id=:Fk19 AND detail_type=".CHANNEL_DETAIL_INFO." ";
            $params[] = array( "key" => ":Fk19",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_USER_PROFILE ){
            $table = 'cms_users_detail';
//            $where = " id='$fk' AND detail_type=".USER_DETAIL_PROFILE." ";
            $where = " id=:Fk20 AND detail_type=".USER_DETAIL_PROFILE." ";
            $params[] = array( "key" => ":Fk20",
                                "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_STORY ){
            $table = 'cms_sosial_story';
            $where = " id=:Entity_ids ";
            $params[] = array( "key" => ":Entity_ids", "value" =>$fk);
        }else if( $entity_type == SOCIAL_ENTITY_HOTEL_REVIEWS ){
            $table = '';
            $where = "";
        }else if( $entity_type == SOCIAL_ENTITY_RESTAURANT_REVIEWS ){
            $table = '';
            $where = "";
        }else if( $entity_type == SOCIAL_ENTITY_LANDMARK_REVIEWS ){
	    $table = '';
	    $where = "";
        }else if( $entity_type == SOCIAL_ENTITY_AIRPORT_REVIEWS ){
            $table = '';
            $where = "";
        }else{
            return false;
        }
        if($table!=''){
            $query = "UPDATE $table SET like_value = like_value - 1 WHERE $where";
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
        }
    }

    if( deleteMode() == TT_DEL_MODE_PURGE ){
        $query2 = "DELETE FROM cms_social_likes WHERE id=:Id";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
        $query2 = "UPDATE cms_social_likes SET published=".TT_DEL_MODE_FLAG." WHERE id=:Id";
    }
    /*if($entity_type == SOCIAL_ENTITY_ALBUM || $entity_type == SOCIAL_ENTITY_MEDIA){
        socialLikeAddSolr($fk, $entity_type, -1);
    }*/
    newsfeedDelete($user_id, $id, SOCIAL_ACTION_LIKE);

    
    $params2[] = array( "key" => ":Id",
                        "value" =>$id);
    $delete_update = $dbConn->prepare($query2);
    PDO_BIND_PARAM($delete_update,$params2);
    $res    = $delete_update->execute();
    return $res;
}

/**
 * Deletes all the likes associated with a key,type. typically for a foreign key record delete
 * @param integer $fk 
 * @param integer $entity_type 
 */
function socialLikesDelete($fk,$entity_type){


    global $dbConn;
    $params = array();
//    $query = "SELECT id FROM cms_social_likes WHERE entity_id='$fk' AND entity_type='$entity_type' AND published=1 LIMIT 1000"; //delete 1000 records at a time
    $query = "SELECT id FROM cms_social_likes WHERE entity_id=:Entity_id AND entity_type=:Entity_type AND published=1"; //delete 1000 records at a time
    
//    $res = db_query($query);
    $params[] = array( "key" => ":Entity_id", "value" =>$fk);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
//    if(!$res || (db_num_rows($res) == 0) ) $done = true;
    if(!$res || ($ret == 0) ){
        return true;
    }else{
        $row = $select->fetchAll();
        foreach($row as $row_item){
            $id = $row_item[0];
            socialLikeDelete($id,false);
        }
        return true;
    }


}

/**
 * edits a like to the global like table - DOESNT CHECK FOR PREVIOUS LIKE
 * @param integer $user_id the user making the like
 * @param integer $fk the foriegn key
 * @param integer $entity_type the type of the like
 * @param integer $like_value the new like value
 * @return boolean true|false if success|failed
 */
function socialLikeEdit($user_id,$fk,$entity_type,$like_value){
    global $dbConn;
    $params  = array();  
    $params2 = array(); 
    $query = "UPDATE cms_social_likes SET like_value=:Like_value WHERE user_id=:User_id AND entity_id=:Fk AND entity_type=:Entity_type AND published=1";
    $params[] = array( "key" => ":Like_value",
                        "value" =>$like_value);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Fk",
                        "value" =>$fk);
    $params[] = array( "key" => ":Entity_type",
                        "value" =>$entity_type);
    
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
    $ret    = $update->rowCount();

    if(!$res) return false;
    if( $ret == 0 ) return true;
    if( $entity_type == SOCIAL_ENTITY_ALBUM ){
        $table = 'cms_users_catalogs';
//        $where = " id='$fk' ";
        $where = " id=:Fk1 ";
	$params2[] = array( "key" => ":Fk1",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_JOURNAL){
        $table = 'cms_journals';
//        $where = " id='$fk' ";
        $where = " id=:Fk2 ";
	$params2[] = array( "key" => ":Fk2",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_JOURNAL_ITEM ){
        $table = 'cms_journals_items';
//        $where = " id='$fk' ";
        $where = " id=:Fk3 ";
	$params2[] = array( "key" => ":Fk3",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_MEDIA ){
        $table = 'cms_videos';
//        $where = " id='$fk' ";
        $where = " id=:Fk4 ";
	$params2[] = array( "key" => ":Fk4",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_WEBCAM ){
        $table = 'cms_webcams';
//        $where = " id='$fk' ";
        $where = " id=:Fk5 ";
	$params2[] = array( "key" => ":Fk5",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_LOCATION){
        $table = 'cms_locations';
//        $where = " id='$fk' ";
        $where = " id=:Fk6 ";
	$params2[] = array( "key" => ":Fk6",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_FLASH){
        $table = 'cms_flash';
//        $where = " id='$fk' ";
        $where = " id=:Fk7 ";
	$params2[] = array( "key" => ":Fk7",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_COMMENT ){
        $table = 'cms_social_comments';
//        $where = " id='$fk' ";
        $where = " id=:Fk8 ";
	$params2[] = array( "key" => ":Fk8",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_NEWS ){
        $table = 'cms_channel_news';
//        $where = " id='$fk' ";
        $where = " id=:Fk10 ";
	$params2[] = array( "key" => ":Fk10",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_EVENTS ){
        $table = 'cms_channel_event';
//        $where = " id='$fk' ";
        $where = " id=:Fk11 ";
	$params2[] = array( "key" => ":Fk11",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ){
        $table = 'cms_users_event';
//        $where = " id='$fk' ";
        $where = " id=:Fk12 ";
	$params2[] = array( "key" => ":Fk12",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_BROCHURE ){
        $table = 'cms_channel_brochure';
//        $where = " id='$fk' ";
        $where = " id=:Fk13 ";
	$params2[] = array( "key" => ":Fk13",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_POST ){
        $table = 'cms_social_posts';
//        $where = " id='$fk' ";
        $where = " id=:Fk14 ";
	$params2[] = array( "key" => ":Fk14",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_CHANNEL ){
        $table = 'cms_channel';
//        $where = " id='$fk' ";
        $where = " id=:Fk15 ";
	$params2[] = array( "key" => ":Fk15",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER ){
        $table = 'cms_channel_detail';
//        $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_COVER." ";
        $where = " id=:Fk16' AND detail_type=".CHANNEL_DETAIL_COVER." ";
	$params2[] = array( "key" => ":Fk16",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE ){
        $table = 'cms_channel_detail';
//        $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
        $where = " id=:Fk17 AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
	$params2[] = array( "key" => ":Fk17",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
        $table = 'cms_channel_detail';
//        $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
        $where = " id=:Fk18 AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
	$params2[] = array( "key" => ":Fk18",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_INFO ){
        $table = 'cms_channel_detail';
//        $where = " id='$fk' AND detail_type=".CHANNEL_DETAIL_INFO." ";
        $where = " id=:Fk19' AND detail_type=".CHANNEL_DETAIL_INFO." ";
	$params2[] = array( "key" => ":Fk19",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_USER_PROFILE ){
        $table = 'cms_users_detail';
//        $where = " id='$fk' AND detail_type=".USER_DETAIL_PROFILE." ";
        $where = " id=Fk20 AND detail_type=".USER_DETAIL_PROFILE." ";
	$params2[] = array( "key" => ":Fk20",
                            "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_STORY ){
        $table = 'cms_sosial_story';
        $where = " id=:Entity_ids ";
        $params[] = array( "key" => ":Entity_ids", "value" =>$fk);
    }else if( $entity_type == SOCIAL_ENTITY_HOTEL_REVIEWS ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_RESTAURANT_REVIEWS ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_LANDMARK_REVIEWS ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_AIRPORT_REVIEWS ){
	$table = '';
	$where = "";
    }else{
        return false;
    }

    if($table != ''){
        $query2 = "UPDATE $table SET like_value = like_value + 1 WHERE $where";
        
//        db_query($query);
	$update2 = $dbConn->prepare($query2);
	PDO_BIND_PARAM($update2,$params2);
	$res     = $update2->execute();
    }
    return true;
}

/**
 * gets the likes
 * @param array $options<br/>
 * <b>limit</b>: the maximum number of like records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>entity_id</b>: which foreign key. required.<br/>
 * <b>entity_type</b>: what type of like. required.<br/>
 * <b>from_ts</b>: search for likes after this date. default null.<br/>
 * <b>to_ts</b>: search for likes before this date. default null.<br/>
 * <b>distinct_user</b>: 1=> distinct user, 0=> doesnt matter. default 0.<br/>
 * <b>like_value</b>: like (1) or dislike (-1). default null.<br/>
 * <b>published</b>: published status. 1 => active. default null => doesnt matter.<br/>
 * <b>n_results</b>: return records or number of results. default false.<br/>
 * @return integer|array a set of 'newsfeed records' could either be a comment, of an upload
 */
function socialLikesGet($srch_options){


    global $dbConn;
    $params = array();  
    //$nlimit = 5, $page = 0, $sortby = 'comment_date' , $sort = 'DESC';

    $default_opts = array(
            'limit' => 10,
            'page' => 0,
            'orderby' => 'id',
            'order' => 'a',
            'entity_id' => null,
            'entity_type' => null,
            'escape_user' => null,
            'include_ids' => null,
            'user_id' => null,
            'like_value' => null,
            'published' => 1,
            'distinct_user' => 0,
            'from_ts' => null,
            'to_ts' => null,
            'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';

    $where = '';
    if( $options['user_id'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.user_id='{$options['user_id']}' ";
        $where .= " C.user_id=:User_id ";
	$params[] = array( "key" => ":User_id",
                            "value" =>$options['user_id']);
    }

    if( $options['like_value'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.like_value='{$options['like_value']}' ";
        $where .= " C.like_value=:Like_value ";
	$params[] = array( "key" => ":Like_value",
                            "value" =>$options['like_value']);
    }	
    if($options['entity_type']){
        if( $where != '') $where .= " AND ";
//        $where .= " C.entity_type = '{$options['entity_type']}' ";
        $where .= " C.entity_type = :Entity_type ";
	$params[] = array( "key" => ":Entity_type",
                            "value" =>$options['entity_type']);
    }	
    if($options['entity_id']){
        if( $where != '') $where .= " AND ";
//        $where .= " C.entity_id = '{$options['entity_id']}' ";
        $where .= " C.entity_id = :Entity_id ";
	$params[] = array( "key" => ":Entity_id",
                            "value" =>$options['entity_id']);
    }

    if( $options['published'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.published='{$options['published']}' ";
        $where .= " C.published=:Published ";
	$params[] = array( "key" => ":Published",
                            "value" =>$options['published']);
    }
    if($options['from_ts']){
        if( $where != '') $where .= " AND ";
//        $where .= " DATE(C.like_date) >= '{$options['from_ts']}' ";
        $where .= " DATE(C.like_date) >= :From_ts ";
	$params[] = array( "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }	
    if($options['to_ts']){
        if( $where != '') $where .= " AND ";
//        $where .= " DATE(C.like_date) <= '{$options['to_ts']}' ";
        $where .= " DATE(C.like_date) <= :To_ts ";
	$params[] = array( "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
    if($options['escape_user']){
        if( $where != '') $where .= " AND ";
//        $where .= " C.user_id NOT IN({$options['escape_user']}) ";
        $where .= " NOT find_in_set(cast(C.user_id as char), :Escape_user) ";
	$params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    if( $options['include_ids'] ){
        if( $where != '') $where .= " AND ";
//        $where .= " C.user_id IN({$options['include_ids']}) ";
        $where .= " find_in_set(cast(C.user_id as char), :Include_ids) ";
	$params[] = array( "key" => ":Include_ids", "value" =>$options['include_ids']);
    }


    if(!$options['n_results'] ){		
        if( $options['distinct_user']==1 ){
//            $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM cms_social_likes AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where GROUP BY C.user_id ORDER BY $orderby $order LIMIT $skip,$nlimit";
            $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM cms_social_likes AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where GROUP BY C.user_id ORDER BY $orderby $order LIMIT :Skip,:Nlimit";
        }else{
//            $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM cms_social_likes AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where ORDER BY $orderby $order LIMIT $skip,$nlimit";
            $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM cms_social_likes AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where ORDER BY $orderby $order LIMIT :Skip,:Nlimit";
        }
        $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT"); 
        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
        
//        $ret = db_query ( $query );
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if ( db_num_rows ( $ret ) ){
        if ( $res && $ret>0 ){
            $ret_arr = array();
//                while($row = db_fetch_assoc ( $ret )){
            $row = $select->fetchAll(PDO::FETCH_ASSOC);
            foreach($row as $row_item){
                if( $row_item['profile_Pic'] == ''){
                    $row_item['profile_Pic'] = 'he.jpg';
                    if ( $row_item['gender'] == 'F') {
                        $row_item['profile_Pic'] = 'she.jpg';
                    }
                }
                $ret_arr[] = $row_item;
            }
            return $ret_arr;
        }else{
            return array();
        }
    }else{
        //TODO REPLACE with the table specific comment count field ex cms_videos(nb_likes)		
        if( $options['distinct_user']==1 ){
            $query = "SELECT COUNT( DISTINCT C.user_id ) FROM cms_social_likes AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where";
        }else{
            $query = "SELECT COUNT(C.id) FROM cms_social_likes AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where";
        }
//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetch();

        return $row[0];
    }
}

/////////////////////////////////////////////////////////////////
//rate

/**
 * gets the rating of the global rating table
 * @param integer $user_id the user making the like
 * @param integer $entity_id the foriegn key
 * @param integer $entity_type the type of the rating
 * @return false|integer false if not rated or the rating value
 */
function socialRateRecordGet($user_id,$entity_id,$entity_type, $rate_type=0){
    global $dbConn;
    $params = array(); 
    $query = "SELECT * FROM cms_social_ratings WHERE entity_type=:Entity_type AND rate_type=:Rate_type AND user_id=:User_id AND entity_id=:Entity_id AND published=1";
    
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $params[] = array( "key" => ":Rate_type", "value" =>$rate_type);
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Entity_id", "value" =>$entity_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();

    $ret = $select->rowCount();
    if(!$res || ($ret == 0) ){
        return false;
    }else{
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
}
/**
 * check if a user rated the object
 * @param integer $user_id the user making the like
 * @param integer $entity_id the foriegn key
 * @param integer $entity_type the type of the like
 * @return integer|false false if not rated else the like value
 */
function socialRated($user_id,$entity_id,$entity_type, $rate_type=0){
	$rec = socialRateRecordGet($user_id,$entity_id,$entity_type, $rate_type);
	if($rec === false) return false;
	return intval($rec['rating_value']);
}
/**
 * gets the rating average of the global rating table
 * @param integer $entity_id the foriegn key
 * @param array $entity_type the type of the rating
 * @return false|integer 0 if not rated or the rating average value
 */
function socialRateAverage($entity_id,$entity_type){
    global $dbConn;
    $params = array();
    $entity_type_list = implode(',', $entity_type);
    $query = "SELECT avg(`rating_value`) as rating_avg FROM `cms_social_ratings` WHERE `entity_id`=:Entity_id AND `entity_type` IN($entity_type_list) AND rate_type=0 AND published=1";
    $params[] = array( "key" => ":Entity_id", "value" =>$entity_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res = $select->execute();
    $ret = $select->rowCount();
    if(!$res || ($ret == 0) ){
        return 0;
    }else{
        $row = $select->fetch(PDO::FETCH_ASSOC);
        if( is_null($row['rating_avg'] ) || $row['rating_avg']=='' ) return 0;
        return $row['rating_avg'];
    }
}

/**
 * gets the rating of the global rating table
 * @param integer $user_id the user making the like
 * @param integer $fk the foriegn key
 * @param integer $entity_type the type of the rating
 * @return false|integer false if not rated or the rating value
 */
function socialRateGet($user_id,$fk,$entity_type, $rate_type=0){
	$rec = socialRateRecordGet($user_id,$fk,$entity_type, $rate_type);
	if($rec === false) return false;
	return $rec['rating_value'];
}

/**
 * adds a rating to the global rating table
 * @param integer $user_id the user making the like
 * @param integer $fk the foriegn key
 * @param integer $entity_type the type of the rating
 * @param integer $rating the rating
 * @param integer $channel_id the cms_channel id invloved with the action
 * @return array()|false false if failed else the new rating of the object
 */
function socialRateAdd($user_id,$fk,$entity_type,$rating,$channel_id, $rate_type=0){


    global $dbConn;
    $params = array();  
    $params2 = array(); 
    $_channel_id = !$channel_id ? 'NULL' : $channel_id;
    if( $entity_type == SOCIAL_ENTITY_POST ){
        $data_info=socialPostsInfo($fk );
        if( intval($data_info['channel_id']) >0 ){
            $channel_id = $data_info['channel_id'];
        }
    }else if( $entity_type == SOCIAL_ENTITY_STORY ){
        $data_info=socialStoryInfo($fk );
        if( intval($data_info['channel_id']) >0 ){
            $channel_id = $data_info['channel_id'];
        }
    }else if( $entity_type == SOCIAL_ENTITY_MEDIA ){
        $data_info=getVideoInfo($fk );
        if( intval($data_info['channelid']) >0 ){
            $channel_id = $data_info['channelid'];
        }
    }else if( $entity_type == SOCIAL_ENTITY_ALBUM ){
        $data_info=userCatalogGet($fk );
        if( intval($data_info['channelid']) >0 ){
            $channel_id = $data_info['channelid'];
        }
    }
    $_channel_id = intval($_channel_id);
    $query = "INSERT INTO cms_social_ratings (user_id,entity_id,entity_type,rate_type,rating_value,channel_id) VALUES (:User_id,:Fk,:Entity_type,:Rate_type,:Rating,:Channel_id)";
    
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Fk", "value" =>$fk);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $params[] = array( "key" => ":Rate_type", "value" =>$rate_type);
    $params[] = array( "key" => ":Rating", "value" =>$rating);
    $params[] = array( "key" => ":Channel_id", "value" =>$_channel_id);
    
    $insert = $dbConn->prepare($query);
    PDO_BIND_PARAM($insert,$params);
    $res    = $insert->execute();
    if(!$res) return false;

    $rate_id = $dbConn->lastInsertId();
    global $CONFIG_EXEPT_ARRAY;
    $exept_array_sep = $CONFIG_EXEPT_ARRAY;
    ////////////////////////////////
    //update the rate value in the foreign table
    if($entity_type == SOCIAL_ENTITY_ALBUM){
        $table = 'cms_users_catalogs';
//        $where = " id='$fk' ";
        $where = " id=:Fk ";
	$params2[] = array( "key" => ":Fk",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_MEDIA){
        $table = 'cms_videos';
//        $where = " id='$fk' ";
        $where = " id=:Fk2 ";
	$params2[] = array( "key" => ":Fk2",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_WEBCAM){
        $table = 'cms_webcams';
//        $where = " id='$fk' ";
        $where = " id=:Fk3 ";
	$params2[] = array( "key" => ":Fk3",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_LOCATION){
        $table = 'cms_locations';
//        $where = " id='$fk' ";
        $where = " id=:Fk4 ";
	$params2[] = array( "key" => ":Fk4",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_POST){
        $table = 'cms_social_posts';
//        $where = " id='$fk' ";
        $where = " id=:Fk5 ";
	$params2[] = array( "key" => ":Fk5",
                            "value" =>$fk);
    }else if( in_array( $entity_type , $exept_array_sep ) ){
        $table = '';
        $where = "";
    }else{
            return false;
    }
    $new_rating_val =0;
    $nb_rating_new =0;
    if( $table != ''){
        $query2 = "SELECT nb_ratings, rating FROM $table WHERE $where";
        
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
	$select = $dbConn->prepare($query2);
	PDO_BIND_PARAM($select,$params2);
	$res    = $select->execute();
	$row = $select->fetch();


        $nb_rating = intval($row['nb_ratings']);
        $rating_val = doubleval($row['rating']);

        $nb_rating_new = $nb_rating + 1;
        $new_rating_val = ($nb_rating*$rating_val + $rating)/$nb_rating_new;

        $query3 = "UPDATE $table SET rating=:New_rating_val,nb_ratings=:Nb_rating_new WHERE $where";
        
	$params2[] = array( "key" => ":New_rating_val",
                            "value" =>$new_rating_val);
	$params2[] = array( "key" => ":Nb_rating_new",
                            "value" =>$nb_rating_new);
	$update = $dbConn->prepare($query3);
	PDO_BIND_PARAM($update,$params2);
	$res    = $update->execute();
    }
    if($rate_type==0){
        newsfeedAdd($user_id, $rate_id, SOCIAL_ACTION_RATE, $fk, $entity_type, USER_PRIVACY_PUBLIC ,$channel_id);
        if( $channel_id && $channel_id!='NULL' && $_channel_id){
            $channelInfo=channelGetInfo($channel_id);
            if($user_id!=intval($channelInfo['owner_id'])){
                newsfeedAdd($user_id, $rate_id, SOCIAL_ACTION_RATE, $fk, $entity_type, USER_PRIVACY_PRIVATE , null );
            }
        }
    }
    return array('rating' => $new_rating_val, 'nb_ratings' => $nb_rating_new);
}

/**
 * edits a rating in the global like table
 * @param integer $user_id the user making the like
 * @param integer $fk the foriegn key
 * @param integer $entity_type the type of the like
 * @param integer $rating the new rating value
 * @return boolean array|false if success|failed
 */
function socialRateEdit($user_id,$fk,$entity_type,$rating,$rate_type=0){
    global $dbConn;
    $params  = array();  
    $params2 = array();  
    $rec = socialRateRecordGet($user_id,$fk,$entity_type, $rate_type);
    if($rec === false) $old_rating = false;
    $old_rating = $rec['rating_value'];
    if($old_rating === false) return false;
    if($rating==0){
        socialRateDelete($rec['id']);
    }else{
        $query = "UPDATE cms_social_ratings SET rating_value=:Rating WHERE entity_type=:Entity_type AND rate_type=:Rate_type AND user_id=:User_id AND entity_id=:Fk AND published=1";    
        $params[] = array( "key" => ":Rating", "value" =>$rating);
        $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
        $params[] = array( "key" => ":Rate_type", "value" =>$rate_type);
        $params[] = array( "key" => ":User_id", "value" =>$user_id);
        $params[] = array( "key" => ":Fk", "value" =>$fk);
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();
        $ret    = $select->rowCount();
        if(!$res) return false;
        if( $ret == 0 ) return true;
    }
    $old_rating = socialRateGet($user_id, $fk, $entity_type, $rate_type);
    if($old_rating === false) $old_rating = 0;
    global $CONFIG_EXEPT_ARRAY;
    $exept_array_sep = $CONFIG_EXEPT_ARRAY;

    ////////////////////////////////
    //update the rate value in the foreign table
    if($entity_type == SOCIAL_ENTITY_ALBUM){
        $table = 'cms_users_catalogs';
//        $where = " id='$fk' ";
        $where = " id=:Fk ";
	$params2[] = array( "key" => ":Fk",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_MEDIA){
        $table = 'cms_videos';
//        $where = " id='$fk' ";
        $where = " id=:Fk2 ";
	$params2[] = array( "key" => ":Fk2",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_WEBCAM){
        $table = 'cms_webcams';
//        $where = " id='$fk' ";
        $where = " id=:Fk3 ";
	$params2[] = array( "key" => ":Fk3",
                            "value" =>$fk);
    }else if($entity_type == SOCIAL_ENTITY_LOCATION){
        $table = 'cms_locations';
        $where = " id='$fk' ";
    }else if($entity_type == SOCIAL_ENTITY_POST){
        $table = 'cms_social_posts';
//        $where = " id='$fk' ";
        $where = " id=:Fk4 ";
	$params2[] = array( "key" => ":Fk4",
                            "value" =>$fk);
    }else if( in_array( $entity_type , $exept_array_sep ) ){
        $table = '';
        $where = "";
    }else{
        return false;
    }
    if( $table!='' ){
        $query = "SELECT nb_ratings, rating FROM $table WHERE $where";
        
//        $ret = db_query($query);
//        $row = db_fetch_array($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params2);
	$res    = $select->execute();
	$row    = $select->fetch();

        $nb_rating = intval($row['nb_ratings']);
        $rating_val = doubleval($row['rating']);

        $nb_rating_new = $nb_rating;
        $new_rating_val = ($nb_rating*$rating_val - $old_rating + $rating)/$nb_rating_new;

        $query = "UPDATE $table SET rating=:New_rating_val,nb_ratings=:Nb_rating_new WHERE $where";
        
	$params2[] = array( "key" => ":New_rating_val",
                             "value" =>$new_rating_val);
	$params2[] = array( "key" => ":Nb_rating_new",
                             "value" =>$nb_rating_new);
	$update = $dbConn->prepare($query);
	PDO_BIND_PARAM($update,$params2);
	$res    = $update->execute();
    }
    return array('rating' => $new_rating_val, 'nb_ratings' => $nb_rating_new);
}

/**
 * gets the social like row 
 * @param integer $id which row
 * @return array|false the row if found or false if not
 */
function socialRateRow($id){
    global $dbConn;
    $socialRateRow  = tt_global_get('socialRateRow');
    $params = array();
    if(isset($socialRateRow[$id]) && $socialRateRow[$id]!=''){
        return $socialRateRow[$id];
    }
    $query = "SELECT `id`,`user_id`,`entity_id`, `entity_type`, `rating_value`, `rating_ts`, `rating_status`, `published`, `channel_id` FROM `cms_social_ratings` WHERE id=:Id";
    $params[] = array( "key" => ":Id","value" =>$id);
    
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    $ret    = $select->rowCount();
    
    if ($res) {
        $row = $select->fetch(PDO::FETCH_ASSOC);       
        $socialRateRow[$id] =   $row;
        return $row;
    }else{
        $socialRateRow[$id] =   false; 
        return false;
    }


}

/**
 * Deletes all the rates associated with a key,type. typically for a foreign key record delete
 * @param integer $fk 
 * @param integer $entity_type
 * @return boolean 
 */
function socialRatesDelete($fk,$entity_type,$rate_type=0){
    global $dbConn;
    $params = array();
    $query = "SELECT id FROM cms_social_ratings WHERE entity_id=:Fk AND entity_type=:Entity_type AND rate_type=:Rate_type AND published=1"; //delete 1000 records at a time
    
    $params[] = array( "key" => ":Fk", "value" =>$fk);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $params[] = array( "key" => ":Rate_type", "value" =>$rate_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if(!$res || ($ret == 0) ){
        return true;
    }else{
        $row = $select->fetchAll();
        foreach($row as $row_item){
            $id = $row_item[0];
            socialRateDelete($id,false);
        }
        return true;
    }
}

/**
 * deletes a ratings
 * @param integer $id which row to delete
 * @param boolean $update update the foreign key table or not
 * @return boolean 
 */
function socialRateDelete($id,$update = true){


    global $dbConn;
    $params  = array();  
    $params2 = array();  
    $rr = socialRateRow($id);
    newsfeedDelete( $rr['user_id'] , $id , SOCIAL_ACTION_RATE );
    if($update){
        $entity_type = $rr['entity_type'];
        $fk = $rr['entity_id'];
        $old_rating = $rr['rating_value'];
        global $CONFIG_EXEPT_ARRAY;
        $exept_array_sep = $CONFIG_EXEPT_ARRAY;

        if($entity_type == SOCIAL_ENTITY_ALBUM){
            $table = 'cms_users_catalogs';
//            $where = " id='$fk' ";
            $where = " id=:Fk ";
            $params[] = array( "key" => ":Fk",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_MEDIA){
            $table = 'cms_videos';
//            $where = " id='$fk' ";
            $where = " id=:Fk2 ";
            $params[] = array( "key" => ":Fk2",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_WEBCAM){
            $table = 'cms_webcams';
//            $where = " id='$fk' ";
            $where = " id=:Fk3 ";
            $params[] = array( "key" => ":Fk3",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_LOCATION){
            $table = 'cms_locations';
//            $where = " id='$fk' ";
            $where = " id=:Fk4 ";
            $params[] = array( "key" => ":Fk4",
                                "value" =>$fk);
        }else if($entity_type == SOCIAL_ENTITY_POST){
            $table = 'cms_social_posts';
//            $where = " id='$fk' ";
            $where = " id=:Fk5 ";
            $params[] = array( "key" => ":Fk5",
                                "value" =>$fk);
        }else if( in_array( $entity_type , $exept_array_sep ) ){
            $table = '';
            $where = "";
        }else{
                return false;
        }
        if($table!=''){
            $query = "SELECT nb_ratings, rating FROM $table WHERE $where";
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
            $row = $select->fetch();

            $nb_rating = intval($row['nb_ratings']);
            $rating_val = doubleval($row['rating']);

            $nb_rating_new = $nb_rating - 1;
            if($nb_rating_new>0) $new_rating_val = ($nb_rating*$rating_val - $old_rating)/$nb_rating_new;
            else $new_rating_val=0;

            $query = "UPDATE $table SET rating=:New_rating_val,nb_ratings=:Nb_rating_new WHERE $where";            
            $params[] = array( "key" => ":New_rating_val",
                                "value" =>$new_rating_val);
            $params[] = array( "key" => ":Nb_rating_new",
                                "value" =>$nb_rating_new);
            $update = $dbConn->prepare($query);
            PDO_BIND_PARAM($update,$params);
            $res    = $update->execute();
        }
    }
    if( deleteMode() == TT_DEL_MODE_PURGE ){
        $query = "DELETE FROM cms_social_ratings WHERE id=:Id";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
        $query = "UPDATE cms_social_ratings SET published=".TT_DEL_MODE_FLAG." WHERE id=:Id";
    }
    $params2[] = array( "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params2);
    $res    = $select->execute();
    if( !$res ) return false;
    else return true;
}
/**
 * gets the ratings
 * @param array $options<br/>
 * <b>limit</b>: the maximum number of rate records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>entity_id</b>: which foreign key. required.<br/>
 * <b>entity_type</b>: what type of rating. required.<br/>
 * <b>from_ts</b>: search for rates after this date. default null.<br/>
 * <b>to_ts</b>: search for rates before this date. default null.<br/>
 * <b>rating_value</b>: the rating value between 1 and 5. default null.<br/>
 * <b>distinct_user</b>: 1=> distinct user, 0=> doesnt matter. default 0.<br/>
 * <b>published</b>: published status of rating. default 1. null => doesn't matter<br/>
 * <b>n_results</b>: return records or number of results. default false.<br/>
 * @return integer|array a set of 'newsfeed records' could either be a comment, of an upload
 */
function socialRatesGet($srch_options){
//$nlimit = 5, $page = 0, $sortby = 'comment_date' , $sort = 'DESC';


    global $dbConn;
    $params = array(); 
	
    $default_opts = array(
            'limit' => 10,
            'page' => 0,
            'orderby' => 'id',
            'order' => 'a',
            'distinct_user' => 0,
            'rate_type' => 0,
            'escape_user' => null,
            'entity_id' => null,
            'entity_type' => null,
            'rating_value' => null,
            'user_id' => null,
            'from_ts' => null,
            'to_ts' => null,
            'published' => 1,
            'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    $where = '';
    if( $options['user_id'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " user_id=:User_id ";
	$params[] = array( "key" => ":User_id", "value" =>$options['user_id']);
    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " C.entity_type=:Entity_type ";
	$params[] = array( "key" => ":Entity_type", "value" =>$options['entity_type']);
    }
    if( $options['entity_id'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " C.entity_id=:Entity_id ";
	$params[] = array( "key" => ":Entity_id", "value" =>$options['entity_id']);
    }

    if( $options['rating_value'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " C.rating_value=:Rating_value ";
	$params[] = array( "key" => ":Rating_value", "value" =>$options['rating_value']);
    }

    if( $options['published'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " C.published=:Published ";
	$params[] = array( "key" => ":Published", "value" =>$options['published']);
    }
    if($options['from_ts']){
        if( $where != '') $where .= " AND ";
        $where .= " DATE(C.rating_ts) >= :From_ts ";
	$params[] = array( "key" => ":From_ts", "value" =>$options['from_ts']);
    }	
    if($options['to_ts']){
        if( $where != '') $where .= " AND ";
        $where .= " DATE(C.rating_ts) <= :To_ts ";
	$params[] = array( "key" => ":To_ts", "value" =>$options['to_ts']);
    }
    if($options['escape_user']){
        if( $where != '') $where .= " AND ";
        $where .= " NOT find_in_set(cast(C.user_id as char), :Escape_user) ";
	$params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    if( $where != '') $where .= ' AND ';
    $where .= " C.rate_type=:Rate_type ";
    $params[] = array( "key" => ":Rate_type", "value" =>$options['rate_type']);

    if(!$options['n_results'] ){
        if( $options['distinct_user']==1 ){
            $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic FROM cms_social_ratings AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where GROUP BY C.user_id ORDER BY $orderby $order LIMIT :Skip,:Nlimit";
        }else{
            $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic FROM cms_social_ratings AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where ORDER BY $orderby $order LIMIT :Skip,:Nlimit";
        }
        $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");

        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
        if ( $res && $ret>0 ){	
            $ret_arr = array();
//            while($row = db_fetch_assoc ( $ret )){
            $row = $select->fetchAll(PDO::FETCH_ASSOC);            
            foreach($row as $row_item){
                if( $row_item['profile_Pic'] == ''){
                    $row_item['profile_Pic'] = 'he.jpg';
                    if ( $row_item['gender'] == 'F') {
                        $row_item['profile_Pic'] = 'she.jpg';
                    }
                }
                $ret_arr[] = $row_item;
            }
            return $ret_arr;
        }else{
            return array();
        }
    }else{
        //TODO REPLACE with the table specific comment count field ex cms_videos(nb_likes)		
        if( $options['distinct_user']==1 ){
            $query = "SELECT COUNT( DISTINCT C.user_id ) FROM cms_social_ratings AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where";
        }else{
            $query = "SELECT COUNT(C.id) FROM cms_social_ratings AS C INNER JOIN cms_users AS U ON C.user_id=U.id WHERE $where";
        }
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetch();

        return $row[0];
    }
}
////////////////////////////////////////////////////////////////
//share

/**
 * a user perfoms a share
 * @param integer $from_user which user is sharing
 * @param array $to_users_ids array of destination user ids
 * @param array $to_user_emails array of destination user emails
 * @param integer $fk_id the share foreign key
 * @param integer $entity_type what kind of share
 * @param integer $share_type what kind of share {SOCIAL_SHARE_TYPE_SHARE,SOCIAL_SHARE_TYPE_INVITE...}
 * @param integer $channel_id the cms_channel id invloved with the action
 * @param integer $real_user_id the owner id of the entity in case of visited places
 * @return boolean true|false if sucess fail
 */
function socialShareAdd($from_user, $to_user_ids,$to_user_emails, $msg, $fk_id, $entity_type,$share_type, $channel_id,$real_user_id=0,$addToFeeds=1) {


	global $dbConn;
	$params  = array();  
	$params2 = array(); 
	$params3 = array(); 
	$params4 = array(); 
	
	$_channel_id = !$channel_id ? 0 : $channel_id;
	if( !$channel_id || $channel_id== 'NULL' ) $_channel_id = 0;
	if( !$channel_id ){
		if( $entity_type == SOCIAL_ENTITY_NEWS ){
			$data_info = channelNewsInfo($fk_id);
			$channel_id = $data_info['channelid'];
		}else if( $entity_type == SOCIAL_ENTITY_EVENTS ){
			$data_info = channelEventInfo($fk_id,-1);
			$channel_id = $data_info['channelid'];
		}else if( $entity_type == SOCIAL_ENTITY_BROCHURE ){
			$data_info = channelBrochureInfo($fk_id);
			$channel_id = $data_info['channelid'];
		}else if( $entity_type == SOCIAL_ENTITY_POST ){
			$data_info=socialPostsInfo($fk_id );
			if( intval($data_info['channel_id']) >0 ){
				$channel_id = $data_info['channel_id'];
			}
		}else if( $entity_type == SOCIAL_ENTITY_STORY ){
			$data_info=socialStoryInfo($fk_id );
			if( intval($data_info['channel_id']) >0 ){
				$channel_id = $data_info['channel_id'];
			}
		}else if( $entity_type == SOCIAL_ENTITY_MEDIA ){
			$data_info=getVideoInfo($fk_id );
			if( intval($data_info['channelid']) >0 ){
				$channel_id = $data_info['channelid'];
			}
		}else if( $entity_type == SOCIAL_ENTITY_ALBUM ){
			$data_info=userCatalogGet($fk_id );
			if( intval($data_info['channelid']) >0 ){
				$channel_id = $data_info['channelid'];
			}
		}else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER || $entity_type == SOCIAL_ENTITY_CHANNEL_INFO || $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE || $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
			$data_info=GetChannelDetailInfo($fk_id );
			if( intval($data_info['channelid']) >0 ){
				$channel_id = $data_info['channelid'];
			}
		}else if( $entity_type == SOCIAL_ENTITY_CHANNEL ){
			$channel_id = $fk_id;
		}
	}
	$all_users = '';
	$real_users = array();
	$real_users_ids = array();

	foreach ($to_user_ids as $user_id) {
		$userInfo = getUserInfo($user_id);
		
		if ($userInfo === false) {
			//SHOULD NEVER HAPPEN. INTRUSION?
			continue;
		}

		$to_user_id = $userInfo['id'];
		$username = $userInfo['YourUserName'];
/*
		if (!userIsFriend($from_user, $to_user_id)) {
			//SHOULD NEVER HAPPEN. INTRUSION?
			continue;
		}
*/
		$real_users_ids[] = $to_user_id;
		$real_users[] = $username;
		//$all_users .= ' @' . $username;
		$all_users .= ' @' . $to_user_id;
		
	}
	if($entity_type == SOCIAL_ENTITY_ALBUM){
		$table = 'cms_users_catalogs';
		$where = " id=:Fk_id ";
                $params[] = array( "key" => ":Fk_id",
                                    "value" =>$fk_id);
	}else if( $entity_type == SOCIAL_ENTITY_JOURNAL ){
		$table = 'cms_journals';
		$where = " id=:Fk_id2 ";
                $params[] = array( "key" => ":Fk_id2",
                                    "value" =>$fk_id);
	}else if($entity_type == SOCIAL_ENTITY_MEDIA){
		$table = 'cms_videos';
//		$where = " id='$fk_id' ";
		$where = " id=:Fk_id3 ";
                $params[] = array( "key" => ":Fk_id3",
                                    "value" =>$fk_id);
	}else if($entity_type == SOCIAL_ENTITY_WEBCAM){
		$table = 'cms_webcams';
//		$where = " id='$fk_id' ";
		$where = " id=:Fk_id4 ";
                $params[] = array( "key" => ":Fk_id4",
                                    "value" =>$fk_id);
	}else if($entity_type == SOCIAL_ENTITY_NEWS){
		$table = 'cms_channel_news';
//		$where = " id='$fk_id' ";
		$where = " id=:Fk_id5 ";
                $params[] = array( "key" => ":Fk_id5",
                                    "value" =>$fk_id);
	}else if($entity_type == SOCIAL_ENTITY_EVENTS){
		$table = 'cms_channel_event';
//		$where = " id='$fk_id' ";
		$where = " id=:Fk_id6 ";
                $params[] = array( "key" => ":Fk_id6",
                                    "value" =>$fk_id);
	}else if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ){
		$table = 'cms_users_event';
//		$where = " id='$fk_id' ";
		$where = " id=:Fk_id7 ";
                $params[] = array( "key" => ":Fk_id7",
                                    "value" =>$fk_id);
	}else if($entity_type == SOCIAL_ENTITY_BROCHURE){
		$table = 'cms_channel_brochure';
//		$where = " id='$fk_id' ";
		$where = " id=:Fk_id8 ";
                $params[] = array( "key" => ":Fk_id8",
                                    "value" =>$fk_id);
	}else if($entity_type == SOCIAL_ENTITY_POST ){
		$table = 'cms_social_posts';
//		$where = " id='$fk_id' ";
		$where = " id=:Fk_id9 ";
                $params[] = array( "key" => ":Fk_id9",
                                    "value" =>$fk_id);
	}else if($entity_type == SOCIAL_ENTITY_CHANNEL ){
		$table = 'cms_channel';
//		$where = " id='$fk_id' ";
		$where = " id=:Fk_id10 ";
                $params[] = array( "key" => ":Fk_id10",
                                    "value" =>$fk_id);
	}else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER ){
		$table = 'cms_channel_detail';
//		$where = " id='$fk_id' AND detail_type=".CHANNEL_DETAIL_COVER." ";
		$where = " id=:Fk_id11 AND detail_type=".CHANNEL_DETAIL_COVER." ";
                $params[] = array( "key" => ":Fk_id11",
                                    "value" =>$fk_id);
	}else if( $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE ){
		$table = 'cms_channel_detail';
//		$where = " id='$fk_id' AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
		$where = " id=:Fk_id12 AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
                $params[] = array( "key" => ":Fk_id12",
                                    "value" =>$fk_id);
	}else if( $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
		$table = 'cms_channel_detail';
//		$where = " id='$fk_id' AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
		$where = " id=:Fk_id13 AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
                $params[] = array( "key" => ":Fk_id13",
                                    "value" =>$fk_id);
	}else if( $entity_type == SOCIAL_ENTITY_CHANNEL_INFO ){
		$table = 'cms_channel_detail';
//		$where = " id='$fk_id' AND detail_type=".CHANNEL_DETAIL_INFO." ";
		$where = " id=:Fk_id14 AND detail_type=".CHANNEL_DETAIL_INFO." ";
                $params[] = array( "key" => ":Fk_id14",
                                    "value" =>$fk_id);
	}else if( $entity_type == SOCIAL_ENTITY_USER_PROFILE ){
		$table = 'cms_users_detail';
//		$where = " id='$fk_id' AND detail_type=".USER_DETAIL_PROFILE." ";
		$where = " id=:Fk_id15 AND detail_type=".USER_DETAIL_PROFILE." ";
                $params[] = array( "key" => ":Fk_id15",
                                    "value" =>$fk_id);
	}else if( $entity_type == SOCIAL_ENTITY_BAG){
            $table = 'cms_bag';
//            $where = " id='$fk_id' ";
            $where = " id=:Fk_id16 ";
                $params[] = array( "key" => ":Fk_id16",
                                    "value" =>$fk_id);
        }else if( $entity_type == SOCIAL_ENTITY_STORY){
            $table = 'cms_sosial_story';
            $where = " id=:Fk_ids ";
            $params[] = array( "key" => ":Fk_ids", "value" =>$fk_id);
        }else if( $entity_type == SOCIAL_ENTITY_HOTEL_REVIEWS ){
            $table = '';
            $where = "";
	}else if( $entity_type == SOCIAL_ENTITY_HOTEL ){
            $table = '';
            $where = "";
	}else if( $entity_type == SOCIAL_ENTITY_RESTAURANT ){
            $table = '';
            $where = "";
	}else if( $entity_type == SOCIAL_ENTITY_LANDMARK ){
            $table = '';
            $where = "";
	}else if( $entity_type == SOCIAL_ENTITY_AIRPORT ){
            $table = '';
            $where = "";
	}else if( $entity_type == SOCIAL_ENTITY_RESTAURANT_REVIEWS ){
            $table = '';
            $where = "";
	}else if( $entity_type == SOCIAL_ENTITY_LANDMARK_REVIEWS ){
            $table = '';
            $where = "";
	}else if( $entity_type == SOCIAL_ENTITY_AIRPORT_REVIEWS ){
            $table = '';
            $where = "";
	}else if( $entity_type == SOCIAL_ENTITY_VISITED_PLACES ){
            $table = '';
            $where = "";
	} else{
            return false;
	}
	//$n_times = count($real_users_ids);
	$n_times =1;
	if( $table!=''){
            $query = "UPDATE $table SET nb_shares=nb_shares+1 WHERE $where";
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
        }
	$all_users = db_sanitize(trim($all_users));
        $query = "INSERT INTO cms_social_shares (from_user,all_users,msg,entity_type,entity_id,channel_id,share_type) VALUES (:From_user,:All_users,:Msg,:Entity_type,:Fk_id,:Channel_id,:Share_type) ";
	$params2[] = array( "key" => ":From_user", "value" =>$from_user);
	$params2[] = array( "key" => ":All_users", "value" =>$all_users);
	$params2[] = array( "key" => ":Msg", "value" =>$msg);
	$params2[] = array( "key" => ":Entity_type", "value" =>$entity_type);
	$params2[] = array( "key" => ":Fk_id", "value" =>$fk_id);
	$params2[] = array( "key" => ":Channel_id", "value" =>$_channel_id);
	$params2[] = array( "key" => ":Share_type", "value" =>$share_type);
        
	$insert = $dbConn->prepare($query);
	PDO_BIND_PARAM($insert,$params2);
	$res    = $insert->execute();
        if (!$res) return false;
        $share_id=$dbConn->lastInsertId();
        
	$actual_action_type = SOCIAL_ACTION_SHARE;
	
	if( $share_type == SOCIAL_SHARE_TYPE_INVITE) $actual_action_type = SOCIAL_ACTION_INVITE;
	else if( $share_type == SOCIAL_SHARE_TYPE_SPONSOR) $actual_action_type = SOCIAL_ACTION_SPONSOR;
        
        if($addToFeeds==1) newsfeedAdd($from_user, $share_id, $actual_action_type, $fk_id, $entity_type, USER_PRIVACY_PUBLIC , $channel_id , $real_user_id );
        
	$valid_entities = array(SOCIAL_ENTITY_WEBCAM,SOCIAL_ENTITY_VISITED_PLACES,SOCIAL_ENTITY_STORY,SOCIAL_ENTITY_ALBUM,SOCIAL_ENTITY_BAG,SOCIAL_ENTITY_MEDIA,SOCIAL_ENTITY_WEBCAM,SOCIAL_ENTITY_BROCHURE,SOCIAL_ENTITY_NEWS,SOCIAL_ENTITY_USER_EVENTS , SOCIAL_ENTITY_HOTEL_REVIEWS , SOCIAL_ENTITY_LANDMARK_REVIEWS , SOCIAL_ENTITY_LANDMARK , SOCIAL_ENTITY_RESTAURANT_REVIEWS , SOCIAL_ENTITY_RESTAURANT , SOCIAL_ENTITY_HOTEL ,SOCIAL_ENTITY_EVENTS,SOCIAL_ENTITY_POST,SOCIAL_ENTITY_CHANNEL,SOCIAL_ENTITY_JOURNAL,SOCIAL_ENTITY_AIRPORT,SOCIAL_ENTITY_AIRPORT_REVIEWS);
	if( $channel_id && $channel_id!='NULL' && $channel_id){
		$channelInfo=channelGetInfo($channel_id);
		$owner_user_id = $channelInfo['owner_id'];
		if( $from_user!= $owner_user_id ){
			newsfeedAdd($from_user, $share_id, $actual_action_type, $fk_id, $entity_type, USER_PRIVACY_PRIVATE , null , $real_user_id );
		}
	}else if( in_array( $entity_type, $valid_entities) ){
		$owner_user_id = socialEntityOwner($entity_type, $fk_id);
	}
        
        if($actual_action_type == SOCIAL_ACTION_SHARE && ($entity_type == SOCIAL_ENTITY_MEDIA || $entity_type == SOCIAL_ENTITY_ALBUM || $entity_type == SOCIAL_ENTITY_USER_EVENTS || $entity_type == SOCIAL_ENTITY_VISITED_PLACES)){
            addPushNotification($actual_action_type, $owner_user_id, $from_user, 0, $fk_id, $entity_type);
        }
	
        foreach ($real_users_ids as $to_user_id) { 
            if( in_array( $entity_type, $valid_entities) && $owner_user_id!=$to_user_id  && $from_user!=$to_user_id ){
                newsfeedAdd ($to_user_id, $share_id, $actual_action_type, $fk_id, $entity_type, USER_PRIVACY_PRIVATE , null , $real_user_id );
                if($actual_action_type == SOCIAL_ACTION_INVITE && ($entity_type == SOCIAL_ENTITY_EVENTS || $entity_type == SOCIAL_ENTITY_USER_EVENTS || $entity_type == SOCIAL_ENTITY_CHANNEL)){
                    if($entity_type == SOCIAL_ENTITY_EVENTS){
                        $channelInfo=channelGetInfo($channel_id);
                        $owner_user_id = $channelInfo['owner_id'];
                        if( $from_user!= $owner_user_id ){
                             addPushNotification($actual_action_type, $to_user_id, $from_user, 0, $fk_id, $entity_type);
                        }
                        else{
                            addPushNotification($actual_action_type, $to_user_id, $channel_id, 1, $fk_id, $entity_type);
                        }
                    }
                    else{
                        addPushNotification($actual_action_type, $to_user_id, $from_user, 0, $fk_id, $entity_type);
                    }
                }
//                $query = "INSERT INTO cms_social_shares_users (share_id,user_id) VALUES ($share_id,$to_user_id)";		
//                db_query($query);
                $query = "INSERT INTO cms_social_shares_users (share_id,user_id) VALUES (:Share_id,:To_user_id)";
                $params3[] = array( "key" => ":Share_id",
                                    "value" =>$share_id);
                $params3[] = array( "key" => ":To_user_id",
                                    "value" =>$to_user_id);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params3);
                $res    = $select->execute();
                
                
                if( $share_type == SOCIAL_SHARE_TYPE_INVITE){
                   sendUserEmailNotification_Invite( $to_user_id , $from_user , $fk_id , $entity_type ); 
                }              
            }
        }
	foreach($to_user_emails as $to_email){
		/////////////
		//add to the share with table
		if( $channel_id ){                    
                    if( $share_type == SOCIAL_SHARE_TYPE_INVITE || $share_type == SOCIAL_SHARE_TYPE_SPONSOR || $share_type == SOCIAL_SHARE_TYPE_SHARE){
                        sendUserEmailNotification_ShareALL( $to_email , $from_user , $fk_id , $entity_type , $share_type , 1 );				
                    }
		}else{
                    
                    // elie
                    if( $share_type == SOCIAL_SHARE_TYPE_SHARE){
                        global $CONFIG_EXEPT_ARRAY;
                        $exept_array = $CONFIG_EXEPT_ARRAY;
                        $exept_array[]=SOCIAL_ENTITY_WEBCAM;
                        $exept_array[]=SOCIAL_ENTITY_FLASH;
                        
                        if( !in_array( $entity_type,$exept_array ) ){
                            switch($entity_type){
                                case SOCIAL_ENTITY_BAG:
                                    $data = bagItemsToShare( $fk_id);
                                    return shareBrifecaseByEmail($to_email, $data);
                                break;
                                default:                                
                                    sendUserEmailNotification_ShareALL( $to_email , $from_user , $fk_id , $entity_type , $share_type , 0 );
                                break;
                            } 
                        }
                    }else if( $share_type == SOCIAL_SHARE_TYPE_INVITE){
                        sendUserEmailNotification_ShareALL( $to_email , $from_user , $fk_id , $entity_type , $share_type , 0 );				
                    }else if( $share_type == SOCIAL_SHARE_TYPE_SPONSOR){
                        sendUserEmailNotification_ShareALL( $to_email , $from_user , $fk_id , $entity_type , $share_type , 0 );
                    }
                }
//		$query = "INSERT INTO cms_social_shares_emails (share_id,user_email) VALUES ($share_id,'$to_email')";
//		db_query($query);
                $query = "INSERT INTO cms_social_shares_emails (share_id,user_email) VALUES (:Share_id,:To_email)";
                $params4[] = array( "key" => ":Share_id", "value" =>$share_id);
                $params4[] = array( "key" => ":To_email", "value" =>$to_email);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params4);
                $res    = $select->execute();
	}

	return true;
}

/**
 * edits a cms_social_shares info
 * @param array $new_info the new cms_social_shares info
 * @return boolean true|false if success|fail
 */
function socialSharesEdit($new_info){
    global $dbConn;
    $params = array();  	
    $query = "UPDATE cms_social_shares SET ";
    $i = 0;
    foreach( $new_info as $key => $val){
        if( $key != 'id' && $key !='channel_id'){
            $query .= " $key = :Val".$i.",";
            $params[] = array( "key" => ":Val".$i,
                                "value" =>$val);
            $i++;
        }
    }
    $query = trim($query,',');
    $query .= " WHERE id=:Id";// AND channel_id='{$new_info['channel_id']}'";
    $params[] = array( "key" => ":Id",
                        "value" =>$new_info['id']);
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $ret    = $update->execute();	
    return ( $ret ) ? true : false;
}

/**
 * gets the specified share record
 * @param integer $share_id the cms_social_shares record id
 * @return array|false 
 */
function socialShareGet($share_id) {


    global $dbConn;        
    $params = array();
    $socialShareGet = tt_global_get('socialShareGet');  //Added By Devendra on 25th may 2015

    if(isset($socialShareGet[$share_id]) && $socialShareGet[$share_id]!=''){
        return $socialShareGet[$share_id];
    }
    
    //$query = "SELECT * FROM cms_social_shares WHERE id=:Share_id "; commented by devendra on 22 may 2015 as told by rishav for query optimization.
    $query = "SELECT `id`, `from_user`, `all_users`, `entity_id`, `entity_type`, `share_ts`, `msg`, `published`, `channel_id`, `share_type`, `is_visible` FROM `cms_social_shares` WHERE id=:Share_id ";
	
	$params[] = array( "key" => ":Share_id", "value" =>$share_id);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
	if (!$res || ($ret == 0)){        
            $socialShareGet[$share_id]    =   false;    
            return false;
        }
	$row = $select->fetch(PDO::FETCH_ASSOC);
        
        $socialShareGet[$share_id]    =   $row;
	return $row;


}

/**
 * Deletes all the shares associated with a key,type. typically for a foreign key record delete
 * @param integer $fk 
 * @param integer $entity_type 
 */
function socialSharesDelete($fk,$entity_type){


	global $dbConn;
	$params = array();
//	$query = "SELECT id FROM cms_social_shares WHERE entity_id='$fk' AND entity_type='$entity_type' AND published=1 LIMIT 1000"; //delete 1000 records at a time
        $query = "SELECT id FROM cms_social_shares WHERE entity_id=:Fk AND entity_type=:Entity_type AND published=1"; //delete 1000 records at a time
	
//	$res = db_query($query);
	$params[] = array( "key" => ":Fk",
                            "value" =>$fk);
	$params[] = array( "key" => ":Entity_type",
                            "value" =>$entity_type);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
        if(!$res || ($ret == 0) ){
            return true;
        }else{
            $row = $select->fetchAll();
            foreach($row as $row_item){
                $id = $row_item[0];
                socialShareDelete($id);
            }
            return true;
        }


}

/**
 * delete a share
 * @param integer $id 
 * @param boolean update the foreign table
 * @return boolean true|false if success fail
 */
function socialShareDelete($id){


    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $params3 = array(); 
    
//    $query = "SELECT * FROM cms_social_shares WHERE id='$id'";
//    $res = db_query($query);
    $query = "SELECT * FROM cms_social_shares WHERE id=:Id";
    $params[] = array( "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

//    if (!$res || (db_num_rows($res) == 0))
    if (!$res || ($ret == 0))
        return false;

//    $row = db_fetch_assoc($res);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    $ids = $row['all_users'];
    $from_user = $row['from_user'];
    $ids = explode(' ' , str_replace('@',' ',$ids) );
    $entity_type = $row['entity_type'];
    $entity_id = $row['entity_id'];
    $share_type = $row['share_type'];
    if($share_type == SOCIAL_SHARE_TYPE_SHARE ){
        $action_type = SOCIAL_ACTION_SHARE;
    }else if($share_type == SOCIAL_SHARE_TYPE_SPONSOR ){
        $action_type = SOCIAL_ACTION_SPONSOR;
    }else if($share_type == SOCIAL_SHARE_TYPE_INVITE ){
        $action_type = SOCIAL_ACTION_INVITE;
    }

    newsfeedDelete( $from_user , $id , $action_type );

    foreach($ids as $shared_width_user_id){
            newsfeedDelete($shared_width_user_id, $id, $action_type);
    }

    ////////////////////////////////
    //update the nb_shares value in the foreign table

    if($entity_type == SOCIAL_ENTITY_ALBUM){
        $table = 'cms_users_catalogs';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id ";
	$params2[] = array( "key" => ":Entity_id",
                             "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_JOURNAL ){
        $table = 'cms_journals';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id2 ";
	$params2[] = array( "key" => ":Entity_id2",
                             "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_MEDIA){
        $table = 'cms_videos';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id3 ";
	$params2[] = array( "key" => ":Entity_id3",
                             "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_WEBCAM){
        $table = 'cms_webcams';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id4 ";
	$params2[] = array( "key" => ":Entity_id4",
                             "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_NEWS){
        $table = 'cms_channel_news';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id5 ";
	$params2[] = array( "key" => ":Entity_id5",
                             "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_EVENTS){
        $table = 'cms_channel_event';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id6 ";
	$params2[] = array( "key" => ":Entity_id6",
                             "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_USER_EVENTS ){
        $table = 'cms_users_event';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id7 ";
	$params2[] = array( "key" => ":Entity_id7",
                             "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_BROCHURE){
        $table = 'cms_channel_brochure';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id8 ";
	$params2[] = array( "key" => ":Entity_id8",
                             "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_POST ){
        $table = 'cms_social_posts';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id9 ";
	$params2[] = array( "key" => ":Entity_id9",
                             "value" =>$entity_id);
    }else if($entity_type == SOCIAL_ENTITY_CHANNEL ){
        $table = 'cms_channel';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id10 ";
	$params2[] = array( "key" => ":Entity_id10",
                             "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_COVER ){
        $table = 'cms_channel_detail';
//        $where = " id='$entity_id' AND detail_type=".CHANNEL_DETAIL_COVER." ";
        $where = " id=:Entity_id11 AND detail_type=".CHANNEL_DETAIL_COVER." ";
	$params2[] = array( "key" => ":Entity_id11",
                             "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_PROFILE ){
        $table = 'cms_channel_detail';
//        $where = " id='$entity_id' AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
        $where = " id=:Entity_id12 AND detail_type=".CHANNEL_DETAIL_PROFILE." ";
	$params2[] = array( "key" => ":Entity_id12",
                             "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
        $table = 'cms_channel_detail';
//        $where = " id='$entity_id' AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
        $where = " id=:Entity_id13 AND detail_type=".CHANNEL_DETAIL_SLOGAN." ";
	$params2[] = array( "key" => ":Entity_id13",
                             "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_CHANNEL_INFO ){
        $table = 'cms_channel_detail';
//        $where = " id='$entity_id' AND detail_type=".CHANNEL_DETAIL_INFO." ";
        $where = " id=:Entity_id14 AND detail_type=".CHANNEL_DETAIL_INFO." ";
	$params2[] = array( "key" => ":Entity_id14",
                             "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_USER_PROFILE ){
        $table = 'cms_users_detail';
//        $where = " id='$entity_id' AND detail_type=".USER_DETAIL_PROFILE." ";
        $where = " id=:Entity_id15 AND detail_type=".USER_DETAIL_PROFILE." ";
	$params2[] = array( "key" => ":Entity_id15",
                             "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_BAG){
        $table = 'cms_bag';
//        $where = " id='$entity_id' ";
        $where = " id=:Entity_id16 ";
	$params2[] = array( "key" => ":Entity_id16",
                             "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_STORY){
        $table = 'cms_sosial_story';
        $where = " id=:Fk_ids ";
        $params[] = array( "key" => ":Fk_ids", "value" =>$entity_id);
    }else if( $entity_type == SOCIAL_ENTITY_HOTEL_REVIEWS ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_HOTEL ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_RESTAURANT ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_LANDMARK ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_AIRPORT ){
    	$table = '';
    	$where = "";
    }else if( $entity_type == SOCIAL_ENTITY_RESTAURANT_REVIEWS ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_LANDMARK_REVIEWS ){
    	$table = '';
    	$where = "";
    }else if( $entity_type == SOCIAL_ENTITY_AIRPORT_REVIEWS ){
        $table = '';
        $where = "";
    }else if( $entity_type == SOCIAL_ENTITY_VISITED_PLACES ){
        $table = '';
        $where = "";
    } else{
        return false;
    }
    $n_times =1;
    if( $table!=''){
        $query2 = "UPDATE $table SET nb_shares=nb_shares-:N_times WHERE $where";       
	$params2[] = array( "key" => ":N_times", "value" =>$n_times);
	$update = $dbConn->prepare($query2);
	PDO_BIND_PARAM($update,$params2);
	$res    = $update->execute();
    }
    if( deleteMode() == TT_DEL_MODE_PURGE ){
        $query = "DELETE FROM cms_social_shares WHERE id=:Id";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
        $query = "UPDATE cms_social_shares SET published=".TT_DEL_MODE_FLAG." WHERE id=:Id";
    }
    $params3[] = array( "key" => ":Id",
                         "value" =>$id);
    
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params3);
    $res    = $delete_update->execute();
    return  $res;
}
/**
 * delete a invited user
 * @param integer $uid , user id
 * @param integer $entity_id the foriegn key
 * @param integer $entity_type the type of
 * @param integer $share_type what kind of share {SOCIAL_SHARE_TYPE_SHARE,SOCIAL_SHARE_TYPE_INVITE...}
 * @return boolean true|false if success fail
 */
function socialInvitedUserDelete($uid,$entity_id,$entity_type,$share_type){


    global $dbConn;
    $params = array(); 
    $params2 = array(); 
    $action_type=SOCIAL_ACTION_SHARE;
    if($share_type==SOCIAL_SHARE_TYPE_INVITE){
        $action_type=SOCIAL_ACTION_INVITE;
    }else if($share_type==SOCIAL_SHARE_TYPE_SPONSOR){
        $action_type=SOCIAL_ACTION_SPONSOR;
    }
//    $query = "SELECT SU.user_id AS uid , S.id AS sid FROM cms_social_shares_users AS SU INNER JOIN cms_social_shares AS S ON SU.share_id = S.id WHERE SU.user_id='$uid' AND S.entity_id='$entity_id' AND S.entity_type='$entity_type' AND S.share_type='$share_type'";
    $query = "SELECT SU.user_id AS uid , S.id AS sid FROM cms_social_shares_users AS SU INNER JOIN cms_social_shares AS S ON SU.share_id = S.id WHERE SU.user_id=:Uid AND S.entity_id=:Entity_id AND S.entity_type=:Entity_type AND S.share_type=:Share_type";
    
//    $ret = db_query($query);
    $params[] = array( "key" => ":Uid",
                        "value" =>$uid);
    $params[] = array( "key" => ":Entity_id",
                        "value" =>$entity_id);
    $params[] = array( "key" => ":Entity_type",
                        "value" =>$entity_type);
    $params[] = array( "key" => ":Share_type",
                        "value" =>$share_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if(!$res || ($ret == 0) ){
        return false;
    }else{
        $ret_arr = array();
	$row = $select->fetchAll();
        foreach($row as $row_item){
            if( deleteMode() == TT_DEL_MODE_PURGE ){
                $query = "DELETE FROM cms_social_shares_users WHERE user_id=:Uid AND share_id=:Sid";
            }else if( deleteMode() == TT_DEL_MODE_FLAG ){
                $query = "UPDATE cms_social_shares_users SET published=".TT_DEL_MODE_FLAG." WHERE user_id=:Uid AND share_id=:Sid";
            }
            $params = array();
            $params[] = array( "key" => ":Uid",
                                "value" =>$row_item['uid']);
            $params[] = array( "key" => ":Sid",
                                "value" =>$row_item['sid']);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();

            newsfeedDelete($row_item['uid'], $row_item['sid'], $action_type);
        }
        return true;
    }
}
/**
 * undo delete a invited user
 * @param integer $uid , user id
 * @param integer $sid , share id
 * @return boolean true|false if success fail
 */
function socialInvitedUserADD($uid,$sid){
    global $dbConn;
    $params = array(); 
    $query = "UPDATE cms_social_shares_users SET published=1 WHERE user_id=:Uid AND share_id=:Sid";	
    $params[] = array( "key" => ":Uid", "value" =>$uid);
    $params[] = array( "key" => ":Sid", "value" =>$sid);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();	
    return $res;
}
/**
 * sets the visibility of the sponsored channel
 * @param integer $id the cms_social_shares id
 * @param integer $visible visible or not
 * @return boolean true|false if success|fail
 */
function sponseredVisibilitySet($id,$visible){
    global $dbConn;
    $params = array();
    $query = "UPDATE cms_social_shares SET is_visible=:Visible WHERE id=:Id";
    $params[] = array( "key" => ":Id", "value" =>$id);
    $params[] = array( "key" => ":Visible", "value" =>$visible);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;
}

/**
 * Gets the events sponsored by a set channel.<br />
 * @from_user integer the channel id.<br />
 * @limit: the maximum number of like records returned. default 10<br />
 * @page: the number of pages to skip. default 0<br />
 * @orderby: the order to base the result on. values include any column of table cms_users. default 'id'<br />
 * @from_ts: start date default null<br/>
 * @to_ts: end date default null<br/>
 * @order: (a)scending or (d)escending. default 'a'<br />
 * @n_results: return records or number of results. default false.<br />
 * @is_visible: 1=> visible, 0=> invisible. -1 => doesnt matter. default -1.<br />
 * @status: integer. current => 2. upcoming => 1. past => 0. current+upcoming=>3 overwrites date_from and date_to. default null (doesnt matter)<br />
 */
function sponsoredEventsGet($srch_options){


    global $dbConn;
    $params = array(); 
    $default_opts = array(
            'from_user' => null,
            'limit' => 100,
            'page' => 0,
            'skip' => 0,
            'from_ts' => null,
            'to_ts' => null,
            'orderby' => 'share_ts',
            'order' => 'a',
            'published' => 1,
            'is_visible' => -1,
            'n_results' => false,
            'status' => null
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit + intval($options['skip']);

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';

    $where = '';


    if( $options['published'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.published='{$options['published']}' ";
        $where .= " C.published=:Published ";
	$params[] = array( "key" => ":Published",
                            "value" =>$options['published']);
    }
    if( $options['share_type'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.share_type='{$options['share_type']}' ";
        $where .= " C.share_type=:Share_type ";
	$params[] = array( "key" => ":Share_type",
                            "value" =>$options['share_type']);
    }
    if( $options['from_user'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.from_user='{$options['from_user']}' ";
        $where .= " C.from_user=:From_user ";
	$params[] = array( "key" => ":From_user",
                            "value" =>$options['from_user']);
    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.entity_type='{$options['entity_type']}' ";
        $where .= " C.entity_type=:Entity_type ";
	$params[] = array( "key" => ":Entity_type",
                            "value" =>$options['entity_type']);
    }
    if($options['from_ts']){
        if( $where != '') $where .= " AND ";
//        $where .= " DATE(E.todate) >= '{$options['from_ts']}' ";
        $where .= " DATE(E.todate) >= :From_ts ";
	$params[] = array( "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }
    if($options['to_ts']){
        if( $where != '') $where .= " AND ";
//        $where .= " DATE(E.fromdate) <= '{$options['to_ts']}' ";
        $where .= " DATE(E.fromdate) <= :To_ts ";
	$params[] = array( "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
    if( $options['status'] ){

        $cur_date = date('Y-m-d');
        $cur_time = date('H:i:s');

        if($options['status'] == 0){
            //past
            if( $where != '' ) $where .= " AND ";
//            $where .= " (E.todate < '$cur_date') OR (E.todate = '$cur_date' AND E.totime<'$cur_time') ";
            $where .= " (E.todate < :Cur_date) OR (E.todate = :Cur_date AND E.totime<:Cur_time) ";
            $params[] = array( "key" => ":Cur_date",
                                "value" =>$cur_date);
            $params[] = array( "key" => ":Cur_time",
                                "value" =>$cur_time);
        }else if($options['status'] == 1){
            //upcoming
            if( $where != '' ) $where .= " AND ";
//            $where .= " (E.fromdate > '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime>'$cur_time') ";
            $where .= " (E.fromdate > :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime>:Cur_time) ";
            $params[] = array( "key" => ":Cur_date",
                                "value" =>$cur_date);
            $params[] = array( "key" => ":Cur_time",
                                "value" =>$cur_time);
        }else if($options['status'] == 2){
            //current
            if( $where != '' ) $where .= " AND ";
//            $where .= " ( (E.fromdate < '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime<'$cur_time') ) ";
            $where .= " ( (E.fromdate < :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime<:Cur_time) ) ";
            $where .= " AND ";
//            $where .= " ( (E.todate > '$cur_date') OR (E.todate = '$cur_date' AND E.totime>'$cur_time') ) ";
            $where .= " ( (E.todate > :Cur_date) OR (E.todate = :Cur_date AND E.totime>:Cur_time) ) ";
            $params[] = array( "key" => ":Cur_date",
                                "value" =>$cur_date);
            $params[] = array( "key" => ":Cur_time",
                                "value" =>$cur_time);
        }else if($options['status'] == 3){
            //current + upcoming
            if( $where != '' ) $where .= " AND ";
//            $where .= " ( ( (E.fromdate < '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime<'$cur_time') ) ";
            $where .= " ( ( (E.fromdate < :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime<:Cur_time) ) ";
            $where .= " AND ";
//            $where .= " ( (E.todate > '$cur_date') OR (E.todate = '$cur_date' AND E.totime>'$cur_time') ) ";
            $where .= " ( (E.todate > :Cur_date) OR (E.todate = :Cur_date AND E.totime>:Cur_time) ) ";
            $where .= " OR ";
//            $where .= " ( (E.fromdate > '$cur_date') OR (E.fromdate = '$cur_date' AND E.fromtime>'$cur_time') ) ) ";
            $where .= " ( (E.fromdate > :Cur_date) OR (E.fromdate = :Cur_date AND E.fromtime>:Cur_time) ) ) ";
            $params[] = array( "key" => ":Cur_date",
                                "value" =>$cur_date);
            $params[] = array( "key" => ":Cur_time",
                                "value" =>$cur_time);
        }

    }
    if(!$options['n_results'] ){
//        $query = "  SELECT
//                        C.*, E.*, C.is_visible AS is_visible
//                    FROM
//                        `cms_social_shares` AS C
//                    INNER JOIN
//                        cms_channel_event AS E ON C.entity_id = E.id
//                    WHERE " . $where . "
//                    ORDER BY $orderby $order LIMIT $skip,$nlimit";
        $query = "  SELECT
                        C.*, E.*, C.is_visible AS is_visible
                    FROM
                        `cms_social_shares` AS C
                    INNER JOIN
                        cms_channel_event AS E ON C.entity_id = E.id
                    WHERE " . $where . "
                    ORDER BY $orderby $order LIMIT :Skip,:Nlimit";

//        $ret = db_query($query);
        
	$params[] = array( "key" => ":Skip",
                            "value" =>$skip,
                            "type" =>"::PARAM_INT");
	$params[] = array( "key" => ":Nlimit",
                            "value" =>$nlimit,
                            "type" =>"::PARAM_INT");
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if(!$ret || (db_num_rows($ret) == 0) ){
        if(!$res || ($ret == 0) ){
            return false;
        }else{
            $ret_arr = array();
//            while($row = db_fetch_array($ret)){
//                $ret_arr[] = $row;
//            }
            $ret_arr = $select->fetchAll();;
            return $ret_arr;
        }

    } else {
        $query = "  SELECT
                        COUNT(C.id)
                    FROM
                        `cms_social_shares` AS C
                    INNER JOIN
                        cms_channel_event AS E ON C.entity_id = E.id
                    WHERE " . $where;

//        $res = db_query ( $query );
//        $row = db_fetch_array($res);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row    = $select->fetch();
        
        return $row[0];
    }


}


/**
 * gets the shares
 * @param array $options<br/>
 * <b>limit</b>: the maximum number of like records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>skip</b>: integer - hadcoded number of records to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>entity_id</b>: which foreign key. required.<br/>
 * <b>entity_type</b>: what type of share. required.<br/>
 * <b>owner_id</b>: the channel's owner id. default null<br/>
 * <b>from_time</b>: start time default null<br/>
 * <b>to_time</b>: end time default null<br/>
 * <b>search_string</b>: which users's or channel's social share. required.<br/>
 * <b>published</b>: published status of share. default 1. null => doesnt matter<br/>
 * <b>share_type</b>: type of share {SOCIAL_SHARE_TYPE_SHARE,SOCIAL_SHARE_TYPE_INVITE,...}<br/>
 * <b>n_results</b>: return records or number of results. default false.<br/>
 * <b>is_visible</b>: 1=> visible, 0=> invisible. -1 => doesnt matter. default -1.<br/>
 * <b>unique</b>: 1=> unique entity id , default null.<br/>
 * @return array | false an array of 'cms_social_shares' records or false if none found.
 */
function socialSharesGet($srch_options){
    //$nlimit = 5, $page = 0, $sortby = 'comment_date' , $sort = 'DESC';


    global $dbConn;
    $params = array(); 

    $default_opts = array(
            'limit' => 10,
            'page' => 0,
            'skip' => 0,
            'dont_show' => 0,
            'orderby' => 'share_ts',
            'order' => 'a',
            'escape_user' => null,
            'include_channels' => null,
            'from_time' => null,
            'to_time' => null,
            'from_ts' => null,
            'to_ts' => null,
            'owner_id' => null,
            'entity_id' => null,
            'unique' => null,
            'begins' => null,
            'search_string' => null,
            'entity_type' => null,
            'share_type' => SOCIAL_SHARE_TYPE_SHARE,
            'published' => 1,
            'distinct_user' => 0,
            'is_visible' => -1,
            'from_user' => null,
            'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    if( $options['limit'] ){
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit + intval($options['skip']);
    }


    $orderby = $options['orderby'];
    $order='';
    if($orderby == 'rand'){
            $orderby = "RAND()";
    }else{
            $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    $entity_id = $options['entity_id'];
    $entity_type = $options['entity_type'];

    //entity_id is null in case of my_sponsored_channel.php
    if( ( ($options['share_type']!=SOCIAL_SHARE_TYPE_SPONSOR) && !$entity_id ) || !$entity_type ) return array();

    $where = '';

    if( $options['begins'] ){
        if( $options['begins'] == '#' ){
            if( $where != '') $where = " ( $where ) AND ";
            $where .= "( LOWER(Ch.channel_name) REGEXP  '^[1-9]' )";
            //user names cant have numbers
        }else{
            if( $where != '') $where = " ( $where ) AND ";
//            $where .= " LOWER(Ch.channel_name) LIKE '{$options['begins']}%' ";
            $where .= " LOWER(Ch.channel_name) LIKE :Begins ";
            $params[] = array( "key" => ":Begins", "value" =>$options['begins']."%");
        }
    }
    if( $options['search_string'] && (strlen($options['search_string'])!=0) ){
        $search_name_where = array();
        if($options['share_type']==SOCIAL_SHARE_TYPE_SPONSOR){			
            $search_name_where[] = "( LOWER(Ch.channel_name) LIKE :Search_name_where )";	
        }else{
            //check begins on username
            $search_name_where[] = "( U.display_fullname = 0 AND LOWER(YourUserName) LIKE :Search_name_where )";
            //or check inside fullname
            $search_name_where[] = "( U.display_fullname = 1 AND LOWER(FullName) LIKE :Search_name_where )";	
        }        
        if($where != '') $where .= " AND ";
        $where .= '(' . implode(' OR ', $search_name_where) . ')';
        $params[] = array( "key" => ":Search_name_where", "value" =>'%'.$options['search_string'].'%');
    }

    if( $options['published'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.published='{$options['published']}' ";
        $where .= " C.published=:Published ";
        $params[] = array( "key" => ":Published",
                            "value" =>$options['published']);
    }
    if( $options['share_type'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.share_type='{$options['share_type']}' ";
        $where .= " C.share_type=:Share_type ";
        $params[] = array( "key" => ":Share_type",
                            "value" =>$options['share_type']);
    }
    if( $options['from_user'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.from_user='{$options['from_user']}' ";
        $where .= " C.from_user=:From_user ";
        $params[] = array( "key" => ":From_user",
                            "value" =>$options['from_user']);
    }
    if( $options['entity_id'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.entity_id='{$options['entity_id']}' ";
        $where .= " C.entity_id=:Entity_id ";
        $params[] = array( "key" => ":Entity_id",
                            "value" =>$options['entity_id']);
    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.entity_type='{$options['entity_type']}' ";
        $where .= " C.entity_type=:Entity_type ";
        $params[] = array( "key" => ":Entity_type",
                            "value" =>$options['entity_type']);
    }
    if( intval($options['is_visible'])!=-1 ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.is_visible='{$options['is_visible']}' ";
        $where .= " C.is_visible=:Is_visible ";
        $params[] = array( "key" => ":Is_visible",
                            "value" =>$options['is_visible']);
    }
    if( intval($options['dont_show'])!=0 && $options['share_type']==SOCIAL_SHARE_TYPE_SPONSOR ){
        if( $where != '') $where .= ' AND ';
//        $where .= " Ch.id NOT IN (".$options["dont_show"].") ";        
        $where .= " NOT find_in_set(cast(Ch.id as char), :Dont_show) ";
	$params[] = array( "key" => ":Dont_show", "value" =>$options['dont_show']);
    }
    if($options['from_time'] || $options['to_time']){
        if( $where != ''){
                $where = " ( ".$where." ) ";			 
        }
        if($options['from_time']){
            if( $where != '') $where .= " AND ";
//            $where .= " (C.share_ts) >= '{$options['from_time']}' ";
            $where .= " (C.share_ts) >= :From_time ";
            $params[] = array( "key" => ":From_time",
                                "value" =>$options['from_time']);
        }
        if($options['to_time']){
            if( $where != '') $where .= " AND ";
//            $where .= " (C.share_ts) <= '{$options['to_time']}' ";
            $where .= " (C.share_ts) <= :To_time ";
            $params[] = array( "key" => ":To_time",
                                "value" =>$options['to_time']);
        }
    }
    if($options['from_ts']){
        if( $where != '') $where .= " AND ";
//        $where .= " DATE(C.share_ts) >= '{$options['from_ts']}' ";
        $where .= " DATE(C.share_ts) >= :From_ts ";
        $params[] = array( "key" => ":From_ts",
                            "value" =>$options['from_ts']);
    }	
    if($options['to_ts']){
        if( $where != '') $where .= " AND ";
//        $where .= " DATE(C.share_ts) <= '{$options['to_ts']}' ";
        $where .= " DATE(C.share_ts) <= :To_ts ";
        $params[] = array( "key" => ":To_ts",
                            "value" =>$options['to_ts']);
    }
    if($options['escape_user']){
        if( $where != '') $where .= " AND ";
//        $where .= " C.from_user NOT IN({$options['escape_user']}) ";
        $where .= " NOT find_in_set(cast(C.from_user as char), :Escape_user) ";
	$params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    if($options['include_channels']){
        if( $where != '') $where .= " AND ";
//        $where .= " C.from_user IN({$options['include_channels']}) ";        
        $where .= " find_in_set(cast(C.from_user as char), :Include_channels) ";
	$params[] = array( "key" => ":Include_channels", "value" =>$options['include_channels']);
    }

    //in case from user is passed (like in my_sponsored_channel.php) we want reverse
    $join_field="";
    if( $options['entity_id'] ) $join_field = 'from_user';
    else if( $options['from_user'] ) $join_field = 'entity_id';
    
    if(!$options['n_results'] ){
        if($options['share_type']==SOCIAL_SHARE_TYPE_SPONSOR){
            $query = "SELECT C.*,Ch.*,Ch.id AS c_id,C.id AS sp_id
                      FROM
                      cms_social_shares AS C ";
            if( $join_field!="" ){ 
                $query .= "INNER JOIN cms_channel AS Ch ON C.{$join_field}=Ch.id ";
                //$query .= "INNER JOIN cms_channel AS Ch ON C.{:Join_field}=Ch.id ";
                //$params[] = array( "key" => ":Join_field","value" =>$join_field);
            }
            if( $options['owner_id'] ){
                if( $join_field!="" ){ 
                    $query .= "AND ";
                }else{
                    $query .= "INNER JOIN cms_channel AS Ch ON ";
                }
//                $query .= "C.from_user=Ch.id AND Ch.owner_id='{$options['owner_id']}'";
                $query .= "C.from_user=Ch.id AND Ch.owner_id=:Owner_id";
                $params[] = array( "key" => ":Owner_id",
                                    "value" =>$options['owner_id']);
            }
        }else{
            $query = "SELECT C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic FROM cms_social_shares AS C INNER JOIN cms_users AS U ON C.from_user=U.id";
        }
        if( $options['distinct_user']==1 ){                    
            $query .= " WHERE $where GROUP BY C.from_user ORDER BY $orderby $order";
        }else{                    
            if( intval($options['unique'])==1){			
                $query .= " WHERE $where GROUP BY C.entity_id ORDER BY $orderby $order";
            }else{
                $query .= " WHERE $where ORDER BY $orderby $order";			
            }
        }

        if( $options['limit'] ){
//            $query .= " LIMIT $skip,$nlimit";
            $query .= " LIMIT :Skip,:Nlimit";
            $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
            $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
        }   
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if ( db_num_rows ( $ret ) ){
        if ( $ret ){
            $res = array();
//            while($row = db_fetch_assoc ( $ret )){
            $row = $select->fetchAll(PDO::FETCH_ASSOC);
            foreach ($row as $row_item){ 
                if($options['share_type']!=SOCIAL_SHARE_TYPE_SPONSOR){
                    if($row_item['profile_Pic'] == ''){
                        $row_item['profile_Pic'] = 'he.jpg';
                        if($row_item['gender']=='F'){
                            $row_item['profile_Pic'] = 'she.jpg';
                        }
                    }
                }
                $res[] = $row_item;
            }
            return $res;
        }else{
            return array();
        }
    }else{
        //TODO REPLACE with the table specific comment count field ex cms_videos(nb_likes)
        if( $options['distinct_user']==1 ){
            if($options['share_type']==SOCIAL_SHARE_TYPE_SPONSOR){
                $query = "SELECT COUNT( DISTINCT C.from_user ) FROM cms_social_shares AS C ";
                if( $join_field!="" ){ 
                    $query .= "INNER JOIN cms_channel AS Ch ON C.{$join_field}=Ch.id";
                    //$query .= "INNER JOIN cms_channel AS Ch ON C.{:Join_field}=Ch.id";
                    //$params[] = array( "key" => ":Join_field","value" =>$join_field);
                    
                }
                if( $options['owner_id'] ){
                    if( $join_field!="" ){ 
                        $query .= " AND ";
                    }else{
                        $query .= "INNER JOIN cms_channel AS Ch ON ";
                    }
//                    $query .= "C.from_user=Ch.id AND Ch.owner_id='{$options['owner_id']}'";
                    $query .= "C.from_user=Ch.id AND Ch.owner_id=:Owner_id";
                    $params[] = array( "key" => ":Owner_id",
                                        "value" =>$options['owner_id']);
                }
                $query .= " WHERE $where";
            }else{
                $query = "SELECT COUNT( DISTINCT C.from_user ) FROM cms_social_shares AS C WHERE $where";
            }
        }else{
            if($options['share_type']==SOCIAL_SHARE_TYPE_SPONSOR){
                $query = "SELECT COUNT(C.id) FROM cms_social_shares AS C ";
                if( $join_field!="" ){ 
                    $query .= "INNER JOIN cms_channel AS Ch ON C.{$join_field}=Ch.id";
                    //$query .= "INNER JOIN cms_channel AS Ch ON C.{:Join_field}=Ch.id";
                    //$params[] = array( "key" => ":Join_field","value" =>$join_field);
                }
                if( $options['owner_id'] ){
                    if( $join_field!="" ){ 
                        $query .= " AND ";
                    }else{
                                $query .= "INNER JOIN cms_channel AS Ch ON ";
                    }
//                    $query .= "C.from_user=Ch.id AND Ch.owner_id='{$options['owner_id']}'";
                    $query .= "C.from_user=Ch.id AND Ch.owner_id=:Owner_id";
                    $params[] = array( "key" => ":Owner_id",
                                        "value" =>$options['owner_id']);
                }
                $query .= " WHERE $where";
            }else{
                $query = "SELECT COUNT(C.id) FROM cms_social_shares AS C WHERE $where";
            }
        }		
//        $res = db_query ( $query );
//        $row = db_fetch_array($res);
        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetch();        
        return $row[0];
    }


}
/**
 * gets the invited events
 * @param array $options<br/>
 * <b>limit</b>: the maximum number of like records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>skip</b>: integer - hadcoded number of records to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>entity_id</b>: which foreign key. required.<br/>
 * <b>entity_type</b>: what type of share. required.<br/>
 * <b>published</b>: published status of share. default 1. null => doesnt matter<br/>
 * <b>share_type</b>: type of share {SOCIAL_SHARE_TYPE_SHARE,SOCIAL_SHARE_TYPE_INVITE,...}<br/>
 * <b>n_results</b>: return records or number of results. default false.<br/>
 * <b>is_visible</b>: 1=> visible, 0=> invisible. -1 => doesnt matter. default -1.<br/>
 * @return array | false an array of 'cms_social_shares' records or false if none found.
 */
function socialInvitedEventsGet($srch_options){


    global $dbConn;
    $params = array();  
    $default_opts = array(
            'limit' => 10,
            'page' => 0,
            'skip' => 0,
            'orderby' => 'share_ts',
            'order' => 'd',
            'entity_id' => null,
            'entity_type' => null,
            'share_type' => SOCIAL_SHARE_TYPE_INVITE,
            'published' => 1,
            'is_visible' => -1,
            'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit + intval($options['skip']);

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';

    $entity_id = $options['entity_id'];
    $entity_type = $options['entity_type'];

    //entity_id is null in case of my_sponsored_channel.php
    if( ( ($options['share_type']!=SOCIAL_SHARE_TYPE_SPONSOR) && !$entity_id ) || !$entity_type ) return array();

    $where = '';

    if( $options['published'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.published='{$options['published']}' ";
        $where .= " C.published=:Published ";
	$params[] = array( "key" => ":Published",
                            "value" =>$options['published']);
    }
    if( $options['share_type'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.share_type='{$options['share_type']}' ";
        $where .= " C.share_type=:Share_type ";
	$params[] = array( "key" => ":Share_type",
                            "value" =>$options['share_type']);
    }
    if( $options['entity_id'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.entity_id='{$options['entity_id']}' ";
        $where .= " C.entity_id=:Entity_id ";
	$params[] = array( "key" => ":Entity_id",
                            "value" =>$options['entity_id']);

    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.entity_type='{$options['entity_type']}' ";
        $where .= " C.entity_type=:Entity_type ";
	$params[] = array( "key" => ":Entity_type",
                            "value" =>$options['entity_type']);
    }
    if( intval($options['is_visible'])!=-1 ){
        if( $where != '') $where .= ' AND ';
//        $where .= " C.is_visible='{$options['is_visible']}' ";
        $where .= " C.is_visible=:Is_visible ";
	$params[] = array( "key" => ":Is_visible",
                            "value" =>$options['is_visible']);
    }

    if(!$options['n_results'] ){

        $query = "SELECT
                    U.id AS u_id ,C.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender
                  FROM
                    cms_users AS U ,
                    cms_social_shares AS C
                    INNER JOIN cms_social_shares_users AS SU ON C.id=SU.share_id";

//        $query .= " WHERE
//                    $where AND SU.published=1 AND SU.user_id=U.id GROUP BY U.id ORDER BY $orderby $order LIMIT $skip,$nlimit";
        $query .= " WHERE
                    $where AND SU.published=1 AND SU.user_id=U.id GROUP BY U.id ORDER BY $orderby $order LIMIT :Skip,:Nlimit";
	$params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
	$params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");

        
//        $ret = db_query ( $query );
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if ( db_num_rows ( $ret ) ){
        if ( $ret ){
            $res = array();
            $row = $select->fetchAll(PDO::FETCH_ASSOC);            
            foreach($row as $row_item){
                if( $row_item['profile_Pic'] == ''){
                    $row_item['profile_Pic'] = 'he.jpg';
                    if ( $row_item['gender'] == 'F') {
                        $row_item['profile_Pic'] = 'she.jpg';
                    }
                }
                $res[] = $row_item;
            }
            return $res;
        }else{
            return array();
        }
    }else{
        $query = "SELECT COUNT( DISTINCT SU.user_id ) FROM cms_social_shares AS C INNER JOIN cms_social_shares_users AS SU ON C.id=SU.share_id WHERE $where AND SU.published=1";

//        $ret = db_query($query);
        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$ret    = $select->rowCount();
//        if($ret && db_num_rows($ret)!=0 ){
        
        if($res && $ret!=0 ){
//            $row = db_fetch_array($ret);
            $row = $select->fetch();
            return $row[0];
        }else{
            return false;
        }
    }


}
/**
 * gets a list of tubers invited
 * @param integer $entity_id the entity record
 * @return array the invited tuber
 */
function getSocialInvitedTubersList($entity_id,$entity_type) {


    global $dbConn;
    $params = array();
//    $query = "SELECT DISTINCT SU.user_id FROM cms_social_shares AS C INNER JOIN cms_social_shares_users AS SU ON C.id=SU.share_id where C.share_type = '".SOCIAL_SHARE_TYPE_INVITE."' AND C.entity_id = '$entity_id' AND C.entity_type = '$entity_type' AND C.published=1";
//    $ret = db_query($query);
//    if (!$ret || (db_num_rows($ret) == 0)) {
//        return false;
//    } else {
//        $ret_arr = array();
//        while ($row = db_fetch_array($ret)) {
//            $ret_arr[] = $row[0];
//        }
//        return $ret_arr;
//    }
    $query = "SELECT DISTINCT SU.user_id FROM cms_social_shares AS C INNER JOIN cms_social_shares_users AS SU ON C.id=SU.share_id where C.share_type = '".SOCIAL_SHARE_TYPE_INVITE."' AND C.entity_id = :Entity_id AND C.entity_type = :Entity_type AND C.published=1";
    $params[] = array( "key" => ":Entity_id",
                        "value" =>$entity_id);
    $params[] = array( "key" => ":Entity_type",
                        "value" =>$entity_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if (!$res || ($ret == 0)) {
        return false;
    } else {
        $ret_arr = array();
	$row = $select->fetchAll();
        foreach($row as $row_item){ 
            $ret_arr[] = $row_item[0];
        }
        return $ret_arr;
    }


}
/**
 * gets a list of shared users for a given shared id 
 * @param intiger $shared_id the sharedid record
 * @return array the shared users for a given shared id
 */
 function getSharedUsersList($shared_id){


        global $dbConn;
        $params = array();  
//	$query = "SELECT * FROM cms_social_shares_users where share_id = '$shared_id' AND published=1";
//	$ret = db_query($query);
//	if(!$ret || (db_num_rows($ret) == 0) ){
//		return false;
//	}else{
//            $ret_arr = array();
//            while($row = db_fetch_array($ret)){
//                    $ret_arr[] = $row['user_id'];
//            }
//            return $ret_arr;
//	}
	$query = "SELECT * FROM cms_social_shares_users where share_id = :Shared_id AND published=1";
	$params[] = array( "key" => ":Shared_id",
                            "value" =>$shared_id);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
	if(!$res || ($ret == 0) ){
		return false;
	}else{
            $ret_arr = array();
            $row = $select->fetchAll();
            foreach($row as $row_item){
                $ret_arr[] = $row_item['user_id'];
            }
            return $ret_arr;
	}


 }
/**
 * gets a list of channel sponsering a channel
 * @param intiger $channel_id the from_user record
 * @return array the sponsering channel of the channel
 */
 function getSponsoredChannel($channel_id){


	global $dbConn;
	$params = array();
//	$query = "SELECT from_user FROM cms_social_shares where entity_id = '$channel_id' AND share_type='".SOCIAL_SHARE_TYPE_SPONSOR."' AND entity_type='".SOCIAL_ENTITY_CHANNEL."' AND published=1";
//	$ret = db_query($query);
//	if(!$ret || (db_num_rows($ret) == 0) ){
//            return false;
//	}else{
//            $ret_arr = array();
//            while($row = db_fetch_array($ret)){
//                $ret_arr[] = $row[0];
//            }
//            return $ret_arr;
//	}
	$query = "SELECT from_user FROM cms_social_shares where entity_id = :Channel_id AND share_type='".SOCIAL_SHARE_TYPE_SPONSOR."' AND entity_type='".SOCIAL_ENTITY_CHANNEL."' AND published=1";
	$params[] = array( "key" => ":Channel_id",
                            "value" =>$channel_id);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
	if(!$res || ($ret== 0) ){
            return false;
	}else{
            $ret_arr = array();
//            while($row = db_fetch_array($ret)){
            $row = $select->fetchAll();
            foreach($row as $row_item){
                $ret_arr[] = $row_item[0];
            }
            return $ret_arr;
	}


 }

////////////////////////////////////////////////////////////
//favorites

/**
 * checks if an object has been favorited
 * @param integer $user_id
 * @param integer $fk
 * @param integer $entity_type
 * @return boolean true|false 
 */
function socialFavoriteAdded($user_id,$fk,$entity_type){


    global $dbConn;
    $params = array(); 
//	$query = "SELECT * FROM cms_social_favorites WHERE user_id='$user_id' AND entity_id='$fk' AND entity_type='$entity_type' AND published=1";
//	$res = db_query($query);
//	if( !$res || (db_num_rows($res) == 0) ) return false;
//	else return true; 
    $query = "SELECT * FROM cms_social_favorites WHERE user_id=:User_id AND entity_id=:Fk AND entity_type=:Entity_type AND published=1";
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Fk", "value" =>$fk);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if( !$res || ($ret == 0) ) return false;
    else return true;


}

/**
 * adds an object to the list of favorites. DOESN'T CHECK IF FAVORITE ADDED ALREADY
 * @param integer $user_id
 * @param integer $fk
 * @param integer $entity_type
 * @param integer $channel_id the cms_channel id invloved with the action
 * @return boolean true|fasle if sucess|fail
 */
function socialFavoriteAdd($user_id,$fk,$entity_type,$channel_id){


    global $dbConn;
    $params = array();

    $_channel_id = (!$channel_id || $channel_id==0) ? 0 : $channel_id;
    $query = "INSERT INTO cms_social_favorites (user_id,entity_id,entity_type,channel_id) VALUES (:User_id,:Fk,:Entity_type,:Channel_id)";
    
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Fk", "value" =>$fk);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $params[] = array( "key" => ":Channel_id", "value" =>$_channel_id, 'type'=>'::PARAM_STR');
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    if( $res ) return true;
    else return false;


}

/**
 * deletes an object from a user'f favorites list
 * @param integer $user_id
 * @param integer $fk
 * @param integer $entity_type
 * @return boolean true|false 
 */
function socialFavoriteDelete($user_id,$fk,$entity_type, $update = true){
    global $dbConn;
    $params = array();  
    if( deleteMode() == TT_DEL_MODE_PURGE ){
        $query = "DELETE FROM cms_social_favorites WHERE user_id=:User_id AND entity_id=:Fk AND entity_type=:Entity_type";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
        $query = "UPDATE cms_social_favorites SET published=".TT_DEL_MODE_FLAG." WHERE user_id=:User_id AND entity_id=:Fk AND entity_type=:Entity_type";
    }
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $params[] = array( "key" => ":Fk", "value" =>$fk);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    if( $res ) return true;
    else return false;
}

/**
 * deletes an object from a user'f favorites list
 * @param integer $user_id
 * @param integer $fk
 * @param integer $entity_type
 * @return boolean true|false 
 */
function socialFavoritesDelete($fk,$entity_type){


    global $dbConn;
    $params = array();
//    $query = "SELECT user_id FROM cms_social_favorites WHERE entity_id='$fk' AND entity_type='$entity_type' AND published=1 LIMIT 1000"; //delete 1000 records at a time
    $query = "SELECT user_id FROM cms_social_favorites WHERE entity_id=:Fk AND entity_type=:Entity_type AND published=1";
    
//    $res = db_query($query);
    $params[] = array( "key" => ":Fk", "value" =>$fk);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if(!$res || ($ret == 0) ){
        return true;
    }else{
        $row = $select->fetchAll();
        foreach($row as $row_item){
            $user_id = $row_item[0];
            socialFavoriteDelete($user_id,$fk,$entity_type,false);
        }
        return true;
    }


}

/**
 * gets a users's favorites
 * @param array $options<br/>
 * <b>limit</b>: the maximum number of user records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'd'<br/>
 * <b>user_id</b>: which user's favorites. required.<br/>
 * <b>types</b>: what types of entities.{SOCIAL_ENTITY_MEDIA, SOCIAL_ENTITY_WEBCAM, ...}<br/>
 * <b>published</b>: published status of record. default 1. null => doesnt matter<br/>
 * <b>n_results</b>: return records or number of results. default false.<br/>
 * @return integer|array a set of 'favorites records'
 */
function socialFavoritesGet($srch_options){


    global $dbConn;
    $params = array();
    $default_opts = array(
            'limit' => 12,
            'page' => 0,
            'skip' => 0,
            'orderby' => 'id',
            'order' => 'd',
            'user_id' => null,
            'search_string' => null,
            'types' => null,
            'published' => 1,
            'n_results' => false
    );

    $options = array_merge($default_opts, $srch_options);

    $nlimit = intval($options['limit']);
    $skip = (intval($options['page']) * $nlimit) + intval($options['skip']);

    $orderby = $options['orderby'];
    $order='';
    if($orderby == 'rand'){
        $orderby = "RAND()";
    }else{
        $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    }

    $where = " F.user_id='{$options['user_id']}' ";
    if( $options['types'] ){
        if($where != '') $where .= " AND ";
//        $where .= " F.entity_type IN (" . implode(',',$options['types']) . ")";        
        $where .= " find_in_set(cast(F.entity_type as char), :Types) ";
	$params[] = array( "key" => ":Types", "value" =>implode(',',$options['types']));
    }

    if( $options['published'] ){
        if($where != '') $where .= " AND ";
//        $where .= " F.published='{$options['published']}' ";
        $where .= " F.published=:Published ";
	$params[] = array( "key" => ":Published", "value" =>$options['published']);
    }
    if( $options['search_string'] ){
        if($where != '') $where .= " AND ";
        $search_string=strtolower($options['search_string']);
//        $where .= " ( LOWER(V.title) LIKE '%$search_string%' OR LOWER(C.name) LIKE '%$search_string%' )";
        $where .= " ( LOWER(V.title) LIKE :Search_string OR LOWER(C.name) LIKE :Search_string )";
	$params[] = array( "key" => ":Search_string", "value" =>"%".$search_string."%");
    }

    if($where != '') $where = " WHERE $where";

    if(!$options['n_results']){
        //$query = "SELECT * FROM cms_social_favorites AS F  $where ORDER BY $orderby $order LIMIT $skip,$nlimit";
//        $query = "SELECT F.*, LOWER(CONCAT ( COALESCE( V.title,0) , COALESCE( C.name,0))) AS titleA, ( COALESCE( V.rating,0) + COALESCE( C.rating,0)) AS ratingA , ( COALESCE( V.nb_views,0) + COALESCE( C.nb_views,0)) AS viewsA , ( COALESCE( V.nb_shares,0) + COALESCE( C.nb_shares,0)) AS sharesA, ( COALESCE( V.like_value,0) + COALESCE( C.like_value,0)) AS likeA, ( COALESCE( V.nb_comments,0) + COALESCE( C.nb_comments,0)) AS commentsA FROM cms_social_favorites AS F LEFT JOIN cms_videos AS V ON V.id=F.entity_id AND F.entity_type=".SOCIAL_ENTITY_MEDIA." LEFT JOIN cms_webcams AS C ON C.id=F.entity_id AND F.entity_type=".SOCIAL_ENTITY_WEBCAM."  $where ORDER BY $orderby $order LIMIT $skip,$nlimit";
        $query = "SELECT F.*, LOWER(CONCAT ( COALESCE( V.title,0) , COALESCE( C.name,0))) AS titleA, ( COALESCE( V.rating,0) + COALESCE( C.rating,0)) AS ratingA , ( COALESCE( V.nb_views,0) + COALESCE( C.nb_views,0)) AS viewsA , ( COALESCE( V.nb_shares,0) + COALESCE( C.nb_shares,0)) AS sharesA, ( COALESCE( V.like_value,0) + COALESCE( C.like_value,0)) AS likeA, ( COALESCE( V.nb_comments,0) + COALESCE( C.nb_comments,0)) AS commentsA FROM cms_social_favorites AS F LEFT JOIN cms_videos AS V ON V.id=F.entity_id AND F.entity_type=".SOCIAL_ENTITY_MEDIA." LEFT JOIN cms_webcams AS C ON C.id=F.entity_id AND F.entity_type=".SOCIAL_ENTITY_WEBCAM."  $where ORDER BY $orderby $order LIMIT :Skip,:Nlimit";
        //return $query;
//        $res = db_query($query);
	$params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
	$params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//        if(!$res || (db_num_rows($res) == 0) ) return false;
        if(!$res || ($ret == 0) ) return false;
        $favs = array();
//        while($row = db_fetch_array($res)){
//                $favs[] = $row;	
//        }
        $favs = $select->fetchAll();
        return $favs;
    }else{
        $query = "SELECT COUNT(F.id) FROM cms_social_favorites AS F LEFT JOIN cms_videos AS V ON V.id=F.entity_id AND F.entity_type=".SOCIAL_ENTITY_MEDIA." LEFT JOIN cms_webcams AS C ON C.id=F.entity_id AND F.entity_type=".SOCIAL_ENTITY_WEBCAM." $where";
//        $res = db_query($query);
//        if(!$res || (db_num_rows($res) == 0) ) return false;
//        $ret = db_fetch_row($res);
        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
        if(!$res || ($ret == 0) ) return false;
	$row = $select->fetch();
        return $row[0];
    }


}

///////////////////////////////////////////////////////////////
//permission

/**
 * a user grants another user the permission to a reousrce
 * @param integer $from_user the granting user
 * @param integer $to_user the receiving user
 * @param integer $perm_fk the permission foreign key 
 * @param string $perm_type SOCIAL_PERMISSION_MEDIA, SOCIAL_PERMISSION_ALBUM
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function socialPermissionGrant($from_user,$to_user, $perm_fk, $perm_type){
//	$query = "INSERT INTO cms_social_permissions (from_user,to_user,perm_fk,perm_type) VALUES ($from_user,$to_user,$perm_fk,'$perm_type')";
//	
//	return mysqk_query($query);
//}

/**
 * a user revokes another user the permission to a reousrce
 * @param integer $from_user the granting user
 * @param integer $to_user the receiving user
 * @param integer $perm_fk the permission foreign key 
 * @param string $perm_type SOCIAL_PERMISSION_MEDIA, SOCIAL_PERMISSION_ALBUM, etc
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function socialPermissionRevoke($from_user,$to_user, $perm_fk, $perm_type){
//	$query = "DELETE FROM cms_social_permissions WHERE from_user=$from_user AND to_user=$to_user AND perm_fk=$perm_fk AND perm_type='$perm_type'";
//	
//	return mysqk_query($query);
//}

/**
 * a user grants another user the permission to a reousrce
 * @param integer $from_user the granting user
 * @param array(integer) $to_user the receiving user
 * @param integer $perm_fk the permission foreign key 
 * @param string $perm_type SOCIAL_PERMISSION_MEDIA, SOCIAL_PERMISSION_ALBUM
 */
//function socialPermissionGrantMulitple($from_user,$to_users, $perm_fk, $perm_type){
//	foreach($to_users as $to_user){
//		socialPermissionGrant($from_user,$to_user, $perm_fk, $perm_type);
//	}
//}

/**
 * checks if a permission has been granted
 * @param integer $from_user the granting user
 * @param integer $to_user the receiving user
 * @param integer $perm_fk the permission foreign key 
 * @param type $perm_type SOCIAL_PERMISSION_MEDIA, SOCIAL_PERMISSION_ALBUM
 */
//function socialPermissionGranted($from_user,$to_user,$perm_fk,$perm_type){
//	$query = "SELECT * FROM cms_social_permissions WHERE from_user=$from_user AND to_user=$to_user AND perm_fk=$perm_fk AND perm_type='$perm_type'";
//	
//	$res = db_query($query);
//	if( $res && (db_num_rows($res)!=0) ) return true;
//	else return false;
//}

/**
 * a user revokes all users the permission to a reousrce
 * @param integer $from_user the granting user
 * @param integer $to_user the receiving user
 * @param integer $perm_fk the permission foreign key 
 * @param string $perm_type SOCIAL_PERMISSION_MEDIA, SOCIAL_PERMISSION_ALBUM
 */
//function socialPermissionRevokeAll($from_user, $perm_fk, $perm_type){
//	$query = "DELETE FROM cms_social_permissions WHERE from_user=$from_user AND perm_fk=$perm_fk AND perm_type='$perm_type'";
//	
//	return mysqk_query($query);
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>

/**
 * get the action reocord
 * @param integer $action_type {SOCIAL_ACTION_COMMENT, SOCIAL_ACTION_CONNECT, ...}
 * @param integer $action_id
 * @return array the relevant action record
 */
function socialActionInfo($action_type,$action_id , $entity_type ){

    switch($action_type){
        case SOCIAL_ACTION_COMMENT:
            return socialCommentRow($action_id);
            break;
        case SOCIAL_ACTION_CONNECT:
            //todo
            throw new Exception("Invalid action type");
            break;
        case SOCIAL_ACTION_EVENT_CANCEL:
            //todo
            throw new Exception("Invalid action type");
            break;
        case SOCIAL_ACTION_EVENT_JOIN:
            return joinEventInfo($action_id);
            break;
        case SOCIAL_ACTION_INVITE:
            return socialShareGet($action_id);
            break;
        case SOCIAL_ACTION_LIKE:
            return socialLikeRow($action_id);
            break;
        case SOCIAL_ACTION_RATE:
            return socialRateRow($action_id);
            break;
        case SOCIAL_ACTION_REPORT:
            //todo
            throw new Exception("Invalid action type");
            break;
        case SOCIAL_ACTION_SHARE:
            return socialShareGet($action_id);
            break;
        case SOCIAL_ACTION_REECHOE:
            return socialReechoeRow($action_id);
            break;
        case SOCIAL_ACTION_SPONSOR:
            $current_row=socialShareGet($action_id);
            return channelGetInfo($current_row['from_user']);
            break;
        case SOCIAL_ACTION_UPLOAD:
            if($entity_type==SOCIAL_ENTITY_MEDIA){
                return getVideoInfo($action_id);
            }else if($entity_type==SOCIAL_ENTITY_USER){
                return getUserInfo($action_id);
            } else if($entity_type==SOCIAL_ENTITY_USER_PROFILE){
                return getUserDetail($action_id);
            }
            break;
        default:
            throw new Exception("Invalid action type");
            break;
    }
}

/**
 * get the user that performed the action
 * @param integer $action_type {SOCIAL_ACTION_COMMENT, SOCIAL_ACTION_CONNECT, ...}
 * @param integer $action_id
 * @return array the relevant action record
 */
function socialActionOwner($action_type,$action_id , $entity_type){
    $action_row = socialActionInfo($action_type,$action_id , $entity_type);

    if( $action_row === false ) return false;

    switch($action_type){
        case SOCIAL_ACTION_REPLY:
        case SOCIAL_ACTION_COMMENT:
        case SOCIAL_ACTION_CONNECT:
        case SOCIAL_ACTION_EVENT_CANCEL:
        case SOCIAL_ACTION_EVENT_JOIN:
        case SOCIAL_ACTION_LIKE:
        case SOCIAL_ACTION_RATE:
        case SOCIAL_ACTION_REPORT:
        case SOCIAL_ACTION_REECHOE:
            return $action_row['user_id'];
            break;
        case SOCIAL_ACTION_SHARE:
        case SOCIAL_ACTION_INVITE:
            return $action_row['from_user'];
            break;
        case SOCIAL_ACTION_SPONSOR:
            return $action_row['owner_id'];
            break;
        case SOCIAL_ACTION_UPLOAD:
            if($entity_type==SOCIAL_ENTITY_MEDIA){
                return $action_row['userid'];
            }else if($entity_type==SOCIAL_ENTITY_USER){
                return $action_row['id'];
            } else if($entity_type==SOCIAL_ENTITY_USER_PROFILE){
                return $action_row['user_id'];
            }
            break;
        default:
            throw new Exception("Invalid action type");
            break;
    }
}

/**
 * gets the record for an entity
 * @param integer $entity_type {SOCIAL_ENTITY_MEDIA, SOCIAL_ENTITY_USER, SOCIAL_ENTITY_ALBUM, SOCIAL_ENTITY_WEBCAM, ..}
 * @param integer $entity_id
 * @return array the entity record
 */
function socialEntityInfo($entity_type, $entity_id){
    $info = null;

    switch( $entity_type ){
        case SOCIAL_ENTITY_ALBUM:
            $info = userCatalogGet($entity_id);
            break;
        case SOCIAL_ENTITY_JOURNAL:
            $info = getJournalInfo($entity_id);			
            break;
        case SOCIAL_ENTITY_BAG:
            $info = bagInfo($entity_id);			
            break;
        case SOCIAL_ENTITY_MEDIA:
            $info = getVideoInfo($entity_id);
            break;
        case SOCIAL_ENTITY_WEBCAM:
            $info = webcamGetInfo($entity_id);
            break;
        case SOCIAL_ENTITY_USER_PROFILE:
            $info = getUserDetail($entity_id);
            break;
        case SOCIAL_ENTITY_VISITED_PLACES:
            $info = worldcitiespopInfo($entity_id);
            break;
        case SOCIAL_ENTITY_PROFILE_ACCOUNT:
        case SOCIAL_ENTITY_USER:
            $info = getUserInfo($entity_id);
            break;
        case SOCIAL_ENTITY_FLASH:
            $info = flashGetInfo($entity_id);
            break;
        case SOCIAL_ENTITY_COMMENT:
            $info = socialCommentRow($entity_id);
            break;
        case SOCIAL_ENTITY_SHARE:
            $info = socialShareGet($entity_id);
            break;
        case SOCIAL_ENTITY_BROCHURE:
            $info = channelBrochureInfo($entity_id);
            break;
        case SOCIAL_ENTITY_CHANNEL:
            $info = channelGetInfo($entity_id);
            $info['channelid'] = $info['id'];
            break;
        case SOCIAL_ENTITY_CHANNEL_COVER:
        case SOCIAL_ENTITY_CHANNEL_INFO:
        case SOCIAL_ENTITY_CHANNEL_PROFILE:
        case SOCIAL_ENTITY_CHANNEL_SLOGAN:
            $info1 = GetChannelDetailInfo($entity_id);
            $info = channelGetInfo($info1['channelid']);
            $info['channelid'] = $info1['channelid'];
            break;
        case SOCIAL_ENTITY_EVENTS:
        case SOCIAL_ENTITY_EVENTS_DATE:
        case SOCIAL_ENTITY_EVENTS_LOCATION:
        case SOCIAL_ENTITY_EVENTS_TIME:			
            $info = channelEventInfo($entity_id,-1);
            break;
        case SOCIAL_ENTITY_USER_EVENTS:			
            $info = userEventInfo($entity_id,-1);
            break;
        case SOCIAL_ENTITY_NEWS:
            $info = channelNewsInfo($entity_id);
            break;
        case SOCIAL_ENTITY_POST:
            $info = socialPostsInfo($entity_id);
            break;                    
        case SOCIAL_ENTITY_STORY:
            $info = socialStoryInfo($entity_id);
            break;                    
        case SOCIAL_ENTITY_HOTEL:
        case SOCIAL_ENTITY_HOTEL_AIRPOT:
        case SOCIAL_ENTITY_HOTEL_SERVICE:
        case SOCIAL_ENTITY_HOTEL_CLEANLINESS:
        case SOCIAL_ENTITY_HOTEL_INTERIOR:
        case SOCIAL_ENTITY_HOTEL_PRICE:
        case SOCIAL_ENTITY_HOTEL_FOODDRINK:
        case SOCIAL_ENTITY_HOTEL_INTERNET:
        case SOCIAL_ENTITY_HOTEL_NOISE:
            $info = getHotelInfo($entity_id);
            break;
        case SOCIAL_ENTITY_RESTAURANT:
        case SOCIAL_ENTITY_RESTAURANT_ATMOSPHERE:
        case SOCIAL_ENTITY_RESTAURANT_CUISINE:
        case SOCIAL_ENTITY_RESTAURANT_NOISE:
        case SOCIAL_ENTITY_RESTAURANT_PRICE:
        case SOCIAL_ENTITY_RESTAURANT_SERVICE:
        case SOCIAL_ENTITY_RESTAURANT_TIME:
            $info = getRestaurantInfo($entity_id);
            break;
        case SOCIAL_ENTITY_LANDMARK_FOODAVAILABLE:
        case SOCIAL_ENTITY_LANDMARK_BATHROOMFACILITIES:
        case SOCIAL_ENTITY_LANDMARK_STAIRS:
        case SOCIAL_ENTITY_LANDMARK_STORAGE:
        case SOCIAL_ENTITY_LANDMARK_PARKING:
        case SOCIAL_ENTITY_LANDMARK_WHEELCHAIR:
        case SOCIAL_ENTITY_LANDMARK:
            $info = getPoiInfo($entity_id);
            break;
	case SOCIAL_ENTITY_AIRPORT:
	case SOCIAL_ENTITY_AIRPORT_DUTYFREE:
	case SOCIAL_ENTITY_AIRPORT_FOOD:
	case SOCIAL_ENTITY_AIRPORT_LOUNGE:
	case SOCIAL_ENTITY_AIRPORT_LUGGAGE:
	case SOCIAL_ENTITY_AIRPORT_RECEPTION:
		$info = getAirportInfo($entity_id);
            break;
        case SOCIAL_ENTITY_HOTEL_REVIEWS:			
            $info = userReviewsInfo($entity_id,SOCIAL_ENTITY_HOTEL);
            break;
        case SOCIAL_ENTITY_RESTAURANT_REVIEWS:			
            $info = userReviewsInfo($entity_id,SOCIAL_ENTITY_RESTAURANT);
            break;
        case SOCIAL_ENTITY_LANDMARK_REVIEWS:			
            $info = userReviewsInfo($entity_id,SOCIAL_ENTITY_LANDMARK);
            break;
	case SOCIAL_ENTITY_AIRPORT_REVIEWS:			
		$info = userReviewsInfo($entity_id,SOCIAL_ENTITY_AIRPORT);
		break;
    }

    return $info;
}

/**
 * gets the record for an entity
 * @param integer $entity_type {SOCIAL_ENTITY_MEDIA, SOCIAL_ENTITY_USER, SOCIAL_ENTITY_ALBUM, SOCIAL_ENTITY_WEBCAM, ..}
 * @param integer $entity_id
 * @return array the entity record
 */
function socialEntityDelete($entity_type, $entity_id){
    $info = null;

    switch( $entity_type ){
        case SOCIAL_ENTITY_ALBUM:
            $info = userCatalogDelete($entity_id);
            break;
        case SOCIAL_ENTITY_MEDIA:
            $info = videoDeleteFlag($entity_id);
            break;
        case SOCIAL_ENTITY_WEBCAM:
            return false;
            break;
        case SOCIAL_ENTITY_USER:
            return false;
            break;
        case SOCIAL_ENTITY_COMMENT:
            $info = socialCommentDelete($entity_id);
            break;
    }

    return $info;
}

/**
 * getst he owner of an entity
 * @param integer $entity_type {SOCIAL_ENTITY_MEDIA, SOCIAL_ENTITY_USER, SOCIAL_ENTITY_ALBUM, SOCIAL_ENTITY_WEBCAM, ..}
 * @param integer $entity_id
 * @return integer the owner_id or null if invalid/not found
 */
function socialEntityOwner($entity_type, $entity_id){
    $entity_info = socialEntityInfo($entity_type, $entity_id);	
    if( $entity_info == null){
        return null;
    }

    $owner_id = null;
    switch( $entity_type ){
        case SOCIAL_ENTITY_ALBUM:
            $owner_id = $entity_info['user_id'];
            break;
        case SOCIAL_ENTITY_MEDIA:
            $owner_id = $entity_info['userid'];
            break;
        case SOCIAL_ENTITY_BAG:
            $owner_id = $entity_info['user_id'];
            break;
        case SOCIAL_ENTITY_WEBCAM:
            $owner_id = 0;
            break;
        case SOCIAL_ENTITY_COMMENT:
            $owner_id = $entity_info['user_id'];
            break;
        case SOCIAL_ENTITY_SHARE:
            $owner_id = $entity_info['from_user'];
            break;
        case SOCIAL_ENTITY_JOURNAL:
            $owner_id = $entity_info['user_id'];
            break;
        case SOCIAL_ENTITY_FLASH:
            $owner_id = $entity_info['user_id'];
            break;
        case SOCIAL_ENTITY_USER_EVENTS:
            $owner_id = $entity_info['user_id'];
            break;
        case SOCIAL_ENTITY_POST:
            if( intval($entity_info['from_id'])>0){
                $owner_id = $entity_info['from_id'];
            }else{
                $owner_id = $entity_info['user_id'];
            }
            break;
        case SOCIAL_ENTITY_STORY:
            $owner_id = $entity_info['user_id'];
            break;
        case SOCIAL_ENTITY_USER_PROFILE:
            $owner_id = $entity_info['user_id'];
            break;
        case SOCIAL_ENTITY_USER:
        case SOCIAL_ENTITY_PROFILE_ACCOUNT:
            $owner_id = $entity_id;
            break;
        case SOCIAL_ENTITY_NEWS:
        case SOCIAL_ENTITY_EVENTS:
        case SOCIAL_ENTITY_BROCHURE:
            $channelid = $entity_info['channelid'];
            $entity_info = socialEntityInfo(SOCIAL_ENTITY_CHANNEL, $channelid);
            $owner_id = $entity_info['owner_id'];
            break;
        case SOCIAL_ENTITY_CHANNEL:
        case SOCIAL_ENTITY_CHANNEL_COVER:
        case SOCIAL_ENTITY_CHANNEL_INFO:
        case SOCIAL_ENTITY_CHANNEL_PROFILE:
        case SOCIAL_ENTITY_CHANNEL_SLOGAN:
            $owner_id = $entity_info['owner_id'];
            break;
        case SOCIAL_ENTITY_HOTEL_REVIEWS:
        case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
        case SOCIAL_ENTITY_LANDMARK_REVIEWS:			
	case SOCIAL_ENTITY_AIRPORT_REVIEWS:			
		$owner_id = $entity_info['user_id'];
            break;
        default:
            return null;
            break;
    }

    return $owner_id;
}

///////////////////////////////////////////////////////////
//newsfeed

/**
 * function to set social interaction permissions on various social entities
 * @param integer $action {SOCIAL_ACTION_RATE, SOCIAL_ACTION_COMMENT, SOCIAL_ACTION_LIKE,SOCIAL_ACTION_UPLOAD,SOCIAL_ACTION_SHARE, ... }
 * @param integer $entity {SOCIAL_ENTITY_MEDIA, SOCIAL_ENTITY_USER, SOCIAL_ENTITY_ALBUM, SOCIAL_ENTITY_WEBCAM}
 * @param integer $id the id of the record of the entity table cms_videos,cms_webcams, ...
 * @param integer $val the new value action typically 0 or 1
 * @return boolean true | fasle if success | fail
 */
function newsfeedActionSet($action,$entity,$id ,$val){	
    global $dbConn;
    $params  = array(); 
    $col = '';
    switch($action){
        case SOCIAL_ACTION_RATE:
            $col = 'can_rate';
            break;
        case SOCIAL_ACTION_COMMENT:
            $col = 'can_comment';
            break;
        case SOCIAL_ACTION_LIKE:
            $col = 'can_like';
            break;
        case SOCIAL_ACTION_SHARE:
            $col = 'can_share';
            break;
        default:
            return false;
            break;
    }
    $table = '';
    switch ($entity) {
        case SOCIAL_ENTITY_ALBUM:
            $table = 'cms_users_catalogs';
            break;
        case SOCIAL_ENTITY_MEDIA:
            $table = 'cms_videos';
            break;
        case SOCIAL_ENTITY_WEBCAM:
            $table = 'cms_webcams';
            break;
        default:
            return false;
            break;
    }
    $query = "UPDATE $table SET $col=$val WHERE id=:Id";
    
    $params[] = array( "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;
}

/**
 * a user has a new item for his feed page
 * @param integer $user_id the user who made the feed
 * @param integer $action_id the social foreign key involved in the feed
 * @param integer $action_type the type of action
 * @param integer $entity_id the target of the action
 * @param integer $entity_type the type of the target of the action
 * @param inetger $privacy
 * @param integer $channel_id the cms_channel id invloved with the action
 * @param integer $real_user_id the owner id of the entity in case of visited places
 * @return boolean true|false if success|fail
 */
function newsfeedAdd($user_id, $action_id, $action_type, $entity_id, $entity_type, $privacy, $channel_id , $real_user_id=0) {
    /*if($action_type==SOCIAL_ACTION_LIKE){
        //sendEmailNotificationEach5h();
        //sendChannelEmailNotificationEach5h();
        //sendEmailNotification_Weekly();
        //sendChannelEmailNotification_Weekly();
    }*/


    global $dbConn;
    $params  = array(); 
    $params2 = array(); 
    $timequery = $action_type.'_'.$entity_type.'_'.$entity_id;
    if( $action_type==SOCIAL_ACTION_UPLOAD ){
        $owner_id =$user_id;
        $entity_id=$action_id;
    }
    if( intval($channel_id)>0 ){
        if($entity_type != SOCIAL_ENTITY_COMMENT ){
            $channel_info = channelGetInfo($channel_id);
            $owner_id = $channel_info['owner_id'];
        }else{
           $owner_id = socialEntityOwner($entity_type, $entity_id ); 
        }
    }else{
            global $CONFIG_EXEPT_ARRAY;
            $exept_array_sep = $CONFIG_EXEPT_ARRAY;
            if( $action_type==SOCIAL_ACTION_UPLOAD ){
                $owner_id =$user_id;
                $entity_id=$action_id;
            }else if( $entity_type==SOCIAL_ENTITY_VISITED_PLACES ){
                if($real_user_id!=0){
                    $owner_id = $real_user_id;
                }else{
                    $owner_id = socialActionOwner($action_type,$action_id , $entity_type);                    
                }		
            }else if( $entity_type ==SOCIAL_ENTITY_WEBCAM || in_array( $entity_type , $exept_array_sep ) ){
                $owner_id =0;
            }else{
                if( $entity_type==SOCIAL_ENTITY_EVENTS_LOCATION || $entity_type==SOCIAL_ENTITY_EVENTS_DATE || $entity_type==SOCIAL_ENTITY_EVENTS_TIME ){
                    $owner_id = socialEntityOwner(SOCIAL_ENTITY_USER_EVENTS, $entity_id );		
                }else{
                    $owner_id = socialEntityOwner($entity_type, $entity_id );
                }
               
                if($entity_type!=SOCIAL_ENTITY_USER_PROFILE && $entity_type!=SOCIAL_ENTITY_USER && $entity_type!=SOCIAL_ENTITY_CHANNEL && $entity_type!=SOCIAL_ENTITY_HOTEL_REVIEWS && $entity_type!=SOCIAL_ENTITY_RESTAURANT_REVIEWS && $entity_type!=SOCIAL_ENTITY_LANDMARK_REVIEWS && $entity_type!=SOCIAL_ENTITY_AIRPORT_REVIEWS){
                        if($action_type!=SOCIAL_ACTION_EVENT_CANCEL){
                                if( $entity_type==SOCIAL_ENTITY_EVENTS_LOCATION || $entity_type==SOCIAL_ENTITY_EVENTS_DATE || $entity_type==SOCIAL_ENTITY_EVENTS_TIME ){
                                        $event_info = userEventInfo($action_id,-1);	
                                        $action_user_id =$event_info['user_id'];			
                                }else{
                                        $action_user_id = socialActionOwner($action_type,$action_id , $entity_type);
                                }
                        }else{
                                $joinEvent_info=joinEventSearch(array('event_id' =>$action_id));	
                                $action_user_id =$joinEvent_info['user_id'];
                        }
                        
//                        $query = "SELECT notify FROM cms_friends WHERE requester_id='$action_user_id' AND receipient_id='$user_id'";
//                        $res = db_query($query);
                        $query = "SELECT notify,profile_blocked FROM cms_friends WHERE published=1 AND requester_id=:Action_user_id AND receipient_id=:User_id";
                        $params[] = array( "key" => ":Action_user_id", "value" =>$action_user_id);
                        $params[] = array( "key" => ":User_id", "value" =>$user_id);
                        $select = $dbConn->prepare($query);
                        PDO_BIND_PARAM($select,$params);
                        $res    = $select->execute();

                        $ret    = $select->rowCount();
//                        if( $res && db_num_rows($res) != 0){
                        if( $res && $ret != 0){
//                                $row = db_fetch_assoc($res);
                                $row = $select->fetch();
                                $notify = ($row['profile_blocked'] == 1);
                                if( $notify ) return false;
                        }
                        //check on notifications table
                        //if( !socialNotificationGet(array('poster_id'=>$owner_id,'receiver_id'=>$user_id,'is_channel' =>0) ) ) return false;
                }
            }
	}
	
	//$owner_id = socialEntityOwner($entity_type, $entity_id );
	
	$_channel_id = (!$channel_id || $channel_id==0) ? 0 : $channel_id;
	$query = "INSERT INTO cms_social_newsfeed (user_id, owner_id , action_id,action_type,entity_id,entity_type,feed_privacy,channel_id,published,entity_group) VALUES (:User_id,:Owner_id,:Action_id,:Action_type,:Entity_id,:Entity_type,:Privacy,:Channel_id,0,:Timequery)";	
        $params2[] = array( "key" => ":User_id", "value" =>$user_id);
	$params2[] = array( "key" => ":Owner_id", "value" =>$owner_id);
	$params2[] = array( "key" => ":Action_id", "value" =>$action_id);
	$params2[] = array( "key" => ":Action_type", "value" =>$action_type);
	$params2[] = array( "key" => ":Entity_id", "value" =>$entity_id);
	$params2[] = array( "key" => ":Entity_type", "value" =>$entity_type);
	$params2[] = array( "key" => ":Privacy", "value" =>$privacy);
	$params2[] = array( "key" => ":Channel_id", "value" =>$_channel_id,'type'=>'::PARAM_STR');
	$params2[] = array( "key" => ":Timequery", "value" =>$timequery);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params2);
	$res    = $select->execute();
        return $res;


}
/**
 * deletes a news feed.
 * @param integer $user_id the user who made the feed
 * @param integer $action_id the foreign key involved in the feed
 * @param integer $action_type the type of action
 * @return boolean true|false if success|fail
 */
function newsfeedDelete($user_id, $action_id, $action_type) {
    global $dbConn;
    $params = array(); 
    if( deleteMode() == TT_DEL_MODE_PURGE ){
        $query = "DELETE FROM cms_social_newsfeed WHERE user_id=:User_id AND action_id=:Action_id AND action_type=:Action_type";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
        $query = "UPDATE cms_social_newsfeed SET published=".TT_DEL_MODE_FLAG." WHERE user_id=:User_id AND action_id=:Action_id AND action_type=:Action_type";
    }
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $params[] = array( "key" => ":Action_id",
                        "value" =>$action_id);
    $params[] = array( "key" => ":Action_type",
                        "value" =>$action_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;
}
/**
 * gets the news feed info givven its id
 * @param integer $id the newsfeed's id
 * @return array | false the cms_social_newsfeed record or null if not found
 */
function getNewsfeedInfo ( $id ){	


    global $dbConn;
    $params = array();
//    $query = "SELECT * FROM cms_social_newsfeed WHERE id='$id'";
//    $ret = db_query($query);
//    if($ret && db_num_rows($ret)!=0 ){
//            $row = db_fetch_assoc($ret);
//            return $row;
//    }else{
//            return false;
//    }
    $query = "SELECT * FROM cms_social_newsfeed WHERE id=:Id";
    $params[] = array( "key" => ":Id",
                        "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if($res && $ret!=0 ){
        $row = $select->fetch(PDO::FETCH_ASSOC);
        return $row;
    }else{
        return false;
    }


}

/**
 * delete the newsfeed by action instead of by entity
 * @param integer $action_id
 * @param integer $action_type
 */
function newsfeedDeleteByAction($action_id,$action_type){
    global $dbConn;
    $params = array();
    if( deleteMode() == TT_DEL_MODE_PURGE ){
        $query = "DELETE FROM cms_social_newsfeed WHERE action_id=:Action_id AND action_type=:Action_type";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
        $query = "UPDATE cms_social_newsfeed SET published=".TT_DEL_MODE_FLAG." WHERE action_id=:Action_id AND action_type=:Action_type";
    }
    $params[] = array( "key" => ":Action_id",
                        "value" =>$action_id);
    $params[] = array( "key" => ":Action_type",
                        "value" =>$action_type);
    $delete_update = $dbConn->prepare($query);
    PDO_BIND_PARAM($delete_update,$params);
    $res    = $delete_update->execute();
    return $res;
}
/**
 * delete the newsfeed join by action instead of by entity
 * @param integer $action_id
 * @param integer $action_type
 * @param integer $entity_type
 */
function newsfeedDeleteJoinByAction($action_id,$action_type,$entity_type){
    global $dbConn;
    $params = array();  
    if( deleteMode() == TT_DEL_MODE_PURGE ){
        $query = "DELETE FROM cms_social_newsfeed WHERE action_id=:Action_id AND action_type=:Action_type AND entity_type=:Entity_type";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
        $query = "UPDATE cms_social_newsfeed SET published=".TT_DEL_MODE_FLAG." WHERE action_id=:Action_id AND action_type=:Action_type AND entity_type=:Entity_type";
    }
    
    $params[] = array( "key" => ":Action_id", "value" =>$action_id);
    $params[] = array( "key" => ":Action_type", "value" =>$action_type);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;
}

/**
 * deletes all newsfeed associated with an entity
 * @param integer $entity_id
 * @param integer $entity_type
 */
function newsfeedDeleteAll($entity_id,$entity_type){


    global $dbConn;
    $params = array();
    if( deleteMode() == TT_DEL_MODE_PURGE ){
//        $query = "DELETE FROM cms_social_newsfeed WHERE entity_id='$entity_id' AND entity_type='$entity_type'";
        $query = "DELETE FROM cms_social_newsfeed WHERE entity_id=:Entity_id AND entity_type=:Entity_type";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
//        $query = "UPDATE cms_social_newsfeed SET published=".TT_DEL_MODE_FLAG." WHERE entity_id='$entity_id' AND entity_type='$entity_type'";
        $query = "UPDATE cms_social_newsfeed SET published=".TT_DEL_MODE_FLAG." WHERE entity_id=:Entity_id AND entity_type=:Entity_type";
    }
    
    $params[] = array( "key" => ":Entity_id", "value" =>$entity_id);
    $params[] = array( "key" => ":Entity_type", "value" =>$entity_type);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
//    return db_query($query);
    return $res;


}

/**
 * Update individual fields in cms_social_newsfeed.
 * @param integer $id the unique id of the field to update.
 * @param array $edit_arr array of the keys-values combination to update IE: array('is_visible' => 0)
 * @return true on success, false on failure.
 */
function newsfeedUpdate($id, $edit_arr){


    global $dbConn;
    $params = array();  
    // Collapse the edit array into SQL syntax.
    $i = 0;
    foreach($edit_arr as $key => $val):
//        $set = $key . " = '" . $val . "',";
        $set .= " $key = :Val".$i.",";
        $params[] = array( "key" => ":Val".$i, "value" =>$val);
        $i++;
    endforeach;
    // Remove the trailing comma.
    $set = substr($set, 0, strlen($set)-1 );

    // Query the db.
//    $query = "UPDATE cms_social_newsfeed SET " . $set . " WHERE (id = " . $id . ") LIMIT 1";
    $query = "UPDATE cms_social_newsfeed SET " . $set . " WHERE id = :Id LIMIT 1";
    $params[] = array( "key" => ":Id", "value" =>$id);
    
    $update = $dbConn->prepare($query);
    PDO_BIND_PARAM($update,$params);
    $res    = $update->execute();
//    return db_query($query);
    return $res;


}
/**
 * Update individual fields in cms_social_newsfeed_hide.
 * @param integer $feed_id the newsfeed id.
 * @param integer $user_id the user id.
 * @param integer $visible  0 hide 1 unhide.
 * @return true on success, false on failure.
 */
function newsfeedHideUpdate($feed_id, $user_id,$visible){


    global $dbConn;
    $params  = array();
//    $query = "SELECT * FROM cms_social_newsfeed_hide WHERE feed_id='$feed_id' AND user_id='$user_id'";
    $query = "SELECT * FROM cms_social_newsfeed_hide WHERE feed_id=:Feed_id AND user_id=:User_id";
//    $res = db_query($query);
    $params[] = array( "key" => ":Feed_id", "value" =>$feed_id);
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();

//    if( !$res || db_num_rows($res) == 0){
    if( !$res || $ret == 0){
        if($visible==0){
            $query = "INSERT INTO cms_social_newsfeed_hide (feed_id,user_id) VALUES (:Feed_id,:User_id)";
        }else{
            return true;
        }
    }else{
        if($visible==0){
            return true;
        }else{
//            $query = "DELETE FROM `cms_social_newsfeed_hide` WHERE feed_id='$feed_id' AND user_id='$user_id'";
            $query = "DELETE FROM `cms_social_newsfeed_hide` WHERE feed_id=:Feed_id AND user_id=:User_id";            
        }
    }
    $params = array();
    $params[] = array( "key" => ":Feed_id", "value" =>$feed_id);
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;


}
/**
 * check if feeds is hidden or not.
 * @param integer $feed_id the newsfeed id.
 * @param integer $user_id the user id.
 * @return true on hidden feeds else false .
 */
function checkNewsfeedHided($feed_id, $user_id){


    global $dbConn;
    $params = array(); 
//    $query = "SELECT * FROM cms_social_newsfeed_hide WHERE feed_id='$feed_id' AND user_id='$user_id'";
//    $res = db_query($query);	
//    if( !$res || db_num_rows($res) == 0){
//       return false;
//    }else{
//        return true;
//    }
    $query = "SELECT * FROM cms_social_newsfeed_hide WHERE feed_id=:Feed_id AND user_id=:User_id";
    $params[] = array( "key" => ":Feed_id",
                        "value" =>$feed_id);
    $params[] = array( "key" => ":User_id",
                        "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();

    $ret    = $select->rowCount();
    if( !$res || $ret == 0){
       return false;
    }else{
        return true;
    }


}

/**
 * check if comments is reply
 * @param integer $user_id
 * @param integer $action_id
 * @param integer $action_type
 * @return true if replied or false if not
 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<start>
//function commentIsReply($user_id,$action_id,$action_type){
//	$query = "SELECT id FROM cms_social_newsfeed where user_id = '$user_id' AND action_id = '$action_id' AND action_type = '$action_type' AND published IN (0 , 1)";
//	
//	$ret = db_query($query);
//	if(!$ret || (db_num_rows($ret) == 0) ){
//		return false;
//	}else{
//		return true;
//	}
//}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  28/04/2015
//<end>
/**
 * gets the newsfeed of a user
 * @param array $srch_options options to search. options include:<br/>
 * <b>limit</b>: the maximum number of user records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>userid</b>: which users's news feed. required.<br/>
 * <b>search_string</b>: which users's news feed. required.<br/>
 * @from_ts: start date default null<br/>
 * @to_ts: end date default null<br/>
 * @from_time: start time default null<br/>
 * @to_time: end time default null<br/>
 * @from_filter: filter on retrived data related to (FROM_FRIENDS or FROM_FOLLOWINGS or FROM_CHANNELS) default null<br/>
 * @activities_filter: filter on retrived data related to activities (ACTIVITIES_ON_TCHANNELS or ACTIVITIES_ON_THOTELS or ACTIVITIES_ON_TRESTAURANTS or ACTIVITIES_ON_TPLANNER or ACTIVITIES_ON_TTPAGE) default null<br/>
 * <b>media_type</b>: what type of media file (v)ideo or (i)mage or null. default null<br/>
 * @param integer $entity_type the entity type of the feed
 * @param integer $entity_id the entity id of the feed
 * @param integer $action_type the action type of the feed
 * <b>is_visible</b>: 1=> visible, 0=> invisible. -1 => doesnt matter. default -1.<br/>
 * <b>is_notification</b>: 1 in case of tuber notification, -1 for default<br/>
 * <b>channel_id</b> the channel involved, null => no channel involved, -1 => doesn't matter<br/>
 * <b>feed_privacy</b> feed privacy, null => doesn't matter, -1 feed not equal 0<br/>
 * <b>published</b>: published status of record. default '0,1' . 0 for newsfeed not viewed . 1 newsfeed viewed . null => doesnt matter.<br/>
 * @return array a set of 'newsfeed records' could either be a comment, of an upload
 */
function newsfeedNotificationsSearch($srch_options) {
    global $dbConn;
    $params = array();
    $default_opts = array(
            'limit' => 10,
            'page' => 0,
            'orderby' => 'id',
            'order' => 'a',
            'escape_entity_type' => null,
            'before_entity_goup' => null,
            'entity_goup' => null,
            'escape_entity_type' => null,
            'entity_type' => null,
            'feed_id' => null,
            'entity_id' => null,
            'media_type' => null,
            'action_type' => null,
            'from_time' => null,
            'to_time' => null,
            'from_ts' => null,
            'to_ts' => null,
            'userid' => null,
            'owner_id' => null,
            'distinct_user' => 0,
            'is_notification' => -1,
            'is_visible' => 1,
            'channel_id' => null,
            'escape_user' => null,
            'feed_privacy' => null,
            'published' => '0,1',
            'n_results' => false,
    );

    $options = array_merge($default_opts, $srch_options);
    $nlimit ='';
    if( $options['limit'] ){
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit;
    }
    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    
    $ret = getNotificationWhereNew($options);
    $where = $ret['where'];
    $params = $ret['params'];
    // Case of returning usual results.
    if(!$options['n_results']){
            if( $options['distinct_user']==1 ){                
                $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where GROUP BY NF.user_id ORDER BY $orderby $order";
            }else{
                $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where ORDER BY $orderby $order";
            }
            if( $options['limit'] ){
//                    $query .= " LIMIT $skip, $nlimit";
                    $query .= " LIMIT :Skip, :Nlimit";
                    $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
                    $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
            }
           
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();

            $feed = array();
            $row = $select->fetchAll(PDO::FETCH_ASSOC);
            foreach($row as $row_item){ 
                        $feed_row = $row_item;			
                        switch( $feed_row['action_type'] ){
                                case SOCIAL_ACTION_COMMENT:
                                        $feed_row['action_row'] = socialCommentRow($feed_row['action_id']);
                                break;
                                case SOCIAL_ACTION_LIKE:
                                        $feed_row['action_row'] = socialLikeRow($feed_row['action_id']);
                                break;
                                case SOCIAL_ACTION_RATE:
                                        $feed_row['action_row'] = socialRateRow($feed_row['action_id']);
                                break;
                                case SOCIAL_ACTION_RELATION_SUB:
                                case SOCIAL_ACTION_RELATION_PARENT:
                                    $feed_row['action_row'] = channelRelationRow($feed_row['action_id']);
                                    $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                                    $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                                    $channel_id = $feed_row['user_id'];
                                    $channelInfo = channelGetInfo($channel_id);
                                    $feed_row['channel_row'] = $channelInfo;
                                break;
                                case SOCIAL_ACTION_INVITE:
                                case SOCIAL_ACTION_SHARE:
                                                $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                                                break;
                                case SOCIAL_ACTION_REECHOE:
                                                $feed_row['action_row'] = socialReechoeRow($feed_row['action_id']);
                                                break;
                                case SOCIAL_ACTION_UPLOAD:
                                        $feed_row['action_row'] = array();
                                        //$feed_row['action_row']['entity_type'] = SOCIAL_ENTITY_MEDIA;
                                        $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                                        $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                                break;
                                case SOCIAL_ACTION_SPONSOR:
                                        //in case of sponsor newsfeed.user_id is actaully the channel_id
                                        $channel_id = $feed_row['user_id'];
                                        $channelInfo = channelGetInfo($channel_id);
                                        $feed_row['channel_row'] = $channelInfo;
                                        $feed_row['action_row'] = socialShareGet($feed_row['action_id']);

                                        $sp_info = socialShareGet($feed_row['action_id']);
                                        $feed_row['action_row']['msg'] = $sp_info['msg'];

                                        //we dont have user info
                                        unset($feed_row['YourUserName']);
                                        unset($feed_row['FullName']);
                                        unset($feed_row['display_fullname']);
                                        unset($feed_row['profile_Pic']);
                                        unset($feed_row['gender']);
                                break;
                                case SOCIAL_ACTION_EVENT_JOIN:
                                        $feed_row['join_row'] = joinEventInfo($feed_row['action_id']);
                                        $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                                        $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                                break;
                                case SOCIAL_ACTION_FRIEND:
                                case SOCIAL_ACTION_FOLLOW:
                                        $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                                        $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                                        $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];		
                                break;
                                default:
                                        $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                                        $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                                break;
                        }

                        if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM ){
                                //in case album
                                $catalog_row = userCatalogDefaultMediaGet($feed_row['action_row']['entity_id']);
                                if(!$catalog_row) $catalog_row = array();
                                $feed_row['media_row'] = $catalog_row;

                                $feed_row['original_entity_type'] = $feed_row['entity_type'];
                                $feed_row['original_entity_id'] = $feed_row['entity_id'];

                        }else if( ($feed_row['action_type'] == SOCIAL_ACTION_LIKE) && ($feed_row['entity_type'] == SOCIAL_ENTITY_COMMENT) ){
                                //in case like a comment
                                $cr = socialCommentRow($feed_row['entity_id']);
                                $feed_row['media_row'] = socialEntityInfo($cr['entity_type'], $cr['entity_id']);

                                $feed_row['original_media_row'] = $cr;

                                $feed_row['original_entity_type'] = $feed_row['entity_type'];
                                $feed_row['original_entity_id'] = $feed_row['entity_id'];

                                $feed_row['entity_type'] = $cr['entity_type'];
                                $feed_row['entity_id'] = $cr['entity_id'];
                        }else if( ($feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME) &&  !$feed_row['channel_id'] ){
                                        $feed_row['media_row'] = socialEntityInfo(SOCIAL_ENTITY_USER_EVENTS, $feed_row['action_row']['entity_id']);
                        }else if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
                                        //$feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['channel_id']);
                                        if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                                        $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_id']);
                                        }else{
                                                        $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_row']['entity_id']);
                                        }
                        }else {
                            //just get the media row
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                            $feed_row['action_row']['entity_id'] = $feed_row['action_id'];
                            }
                            $feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['entity_id']);
                            if( $feed_row['entity_type'] == SOCIAL_ENTITY_VISITED_PLACES ){
                                $stateinfo = worldStateInfo($feed_row['media_row']['country_code'],$feed_row['media_row']['state_code']);
                                $state_name = (!$stateinfo)? '' : $stateinfo['state_name'];
                                $country_name= countryGetName($feed_row['media_row']['country_code']);
                                $country_name = (!$country_name)? '' : $country_name;
                                $feed_row['media_row']['state_name']=$state_name;
                                $feed_row['media_row']['country_name']=$country_name;
                            }
                        }

                    ///////////////////////
                    //in case no profile pic and not the channel sponsor action
                    if( ( !isset($feed_row['profile_Pic']) || $feed_row['profile_Pic'] == '' ) && $feed_row['action_type'] != SOCIAL_ACTION_SPONSOR && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_SUB && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_PARENT ){
                            $feed_row['profile_Pic'] = 'he.jpg';
                            if($feed_row['gender']=='F'){
                                    $feed_row['profile_Pic'] = 'she.jpg';
                            }
                    }

                    $feed[] = $feed_row;
            }

            return $feed;

    // Case of returning n_results.
    } else {
            if( $options['distinct_user']==1 ){
                $query = "SELECT count(DISTINCT NF.user_id) FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";
            }else{
                $query = "SELECT count(*) FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";
            }
            //return $query;
//            $ret = db_query($query);
//            $row = db_fetch_row($ret);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
            $row    = $select->fetch();

            return $row[0];
    }
}
function getNotificationWhereNew($options){
    $params = array();
    $where ='';
    if( $options['feed_privacy'] ){
        if($where != '') $where .= " AND ";
        if( intval($options['feed_privacy'])==-1){
            $where .= " NF.feed_privacy<>".USER_PRIVACY_PRIVATE."";
        }else{
            $where .= " NF.feed_privacy=:Feed_privacy ";
            $params[] = array( "key" => ":Feed_privacy", "value" =>$options['feed_privacy']);
        }
    }
    if( !is_null($options['published']) ){
            if($where != '') $where .= " AND ";
            $where .= " find_in_set(cast(NF.published as char), :Published) ";
            $params[] = array( "key" => ":Published", "value" =>$options['published']);
    }
    if( isset($options['entity_group']) && $options['entity_group'] ){
            if( $where != '') $where .= ' AND ';
            $where .= " NF.entity_group=:Entity_group ";
            $params[] = array( "key" => ":Entity_group", "value" =>$options['entity_group']);	
    }
    if( $options['is_visible'] != -1 ){
        if($options['is_visible'] == 1 && intval($options['userid']) !=0 ){
            if( $where != '') $where .= ' AND ';
            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid)";
            $params[] = array( "key" => ":Userid", "value" =>$options['userid']);	
        }else if($options['is_visible'] == 1 && $options['channel_id']) {
            $channelInfo=channelGetInfo($options['channel_id']);
            $owner_id=$channelInfo['owner_id'];
            if( $where != '') $where .= ' AND ';
            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id)";
            $params[] = array( "key" => ":Owner_id",
                                "value" =>$owner_id);	
        }else if($options['is_visible'] == 0 && intval($options['userid']) !=0 ){
            if( $where != '') $where .= ' AND ';
            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid2)";
            $params[] = array( "key" => ":Userid2",
                                "value" =>$options['userid']);	
        }else if($options['is_visible'] == 0 && $options['channel_id'] && intval($options['channel_id']) > 0 ){
            $channelInfo=channelGetInfo($options['channel_id']);
            $owner_id=$channelInfo['owner_id'];
            if( $where != '') $where .= ' AND ';
            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id2)";
            $params[] = array( "key" => ":Owner_id2",
                                "value" =>$owner_id);
        }
    }
    if( $options['feed_id'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " NF.id=:Feed_id ";
        $params[] = array( "key" => ":Feed_id",
                            "value" =>$options['feed_id']);		
    }
    if( $options['before_entity_goup'] && $options['before_entity_id']>0 ){
        if($where != '') $where .= " AND ";
        $where .= " MD5(NF.entity_group) <>:Before_entity_goup AND NF.id<:Before_entity_id";
        $params[] = array( "key" => ":Before_entity_goup", "value" =>$options['before_entity_goup']);	
        $params[] = array( "key" => ":Before_entity_id", "value" =>$options['before_entity_id']);	
    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " NF.entity_type=:Entity_type ";
        $params[] = array( "key" => ":Entity_type",
                            "value" =>$options['entity_type']);				
    }
    if( $options['escape_entity_type'] ){
        if( $where != '') $where .= ' AND ';
        $escape_entity_type = $options['escape_entity_type'];
        $where .= " NOT find_in_set(cast(NF.entity_type as char), :Escape_entity_type) ";
        $params[] = array( "key" => ":Escape_entity_type", "value" =>$escape_entity_type);			
    }
    if( $options['entity_id'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " NF.entity_id=:Entity_id ";		
        $params[] = array( "key" => ":Entity_id",
                            "value" =>$options['entity_id']);	
    }
    if( $options['action_type'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " NF.action_type=:Action_type ";
        $params[] = array( "key" => ":Action_type",
                            "value" =>$options['action_type']);	
    }
    if($options['from_ts'] || $options['to_ts']){			
        if($options['from_ts']){
            if( $where != '') $where .= " AND ";
            $where .= " DATE(NF.feed_ts) >= :From_ts ";
            $params[] = array( "key" => ":From_ts",
                                "value" =>$options['from_ts']);	
        }
        if($options['to_ts']){
            if( $where != '') $where .= " AND ";
            $where .= " DATE(NF.feed_ts) <= :To_ts ";
            $params[] = array( "key" => ":To_ts",
                                "value" =>$options['to_ts']);	
        }
    }		
    if($options['from_time'] || $options['to_time']){			
        if($options['from_time']){
            if( $where != '') $where .= " AND ";
            $where .= " (NF.feed_ts) >= :From_time ";
            $params[] = array( "key" => ":From_time",
                                "value" =>$options['from_time']);	
        }
        if($options['to_time']){
            if( $where != '') $where .= " AND ";
            $where .= " (NF.feed_ts) <= :To_time ";
            $params[] = array( "key" => ":To_time",
                                "value" =>$options['to_time']);	
        }
    }	

    if($where != '') $where .= " AND";
	$where .= " (";	
	$where .= " ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." AND EXISTS ( SELECT id FROM cms_videos VV WHERE VV.id=NF.entity_id AND VV.published=1";
    if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
        $where .= " AND image_video=:Media_type )";	
        $params[] = array( "key" => ":Media_type", "value" =>$options['media_type']);								
    }else{
        $where .= " )";
    }
	$where .= " )";
	$where .= " OR";
    $where .= " ( NF.entity_type=".SOCIAL_ENTITY_ALBUM." AND EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) ) )";
	$where .= " OR";
    $where .= " ( NF.entity_type !=".SOCIAL_ENTITY_ALBUM." AND NF.entity_type !=".SOCIAL_ENTITY_MEDIA." )";
	$where .= " )";	
	
    //$where .= " CASE WHEN NF.entity_type=".SOCIAL_ENTITY_MEDIA." THEN EXISTS ( SELECT id FROM cms_videos VV WHERE VV.id=NF.entity_id AND VV.published=1";
    //if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
        //$where .= " AND image_video=:Media_type )";	
        //$params[] = array( "key" => ":Media_type", "value" =>$options['media_type']);								
    //}else{
       // $where .= " )";
    //}
    //$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_ALBUM." THEN EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) )";
    //$where .= " ELSE 1 END ";

    if( $options['channel_id'] && $options['channel_id'] !=-1 ){
            $channelInfo=channelGetInfo($options['channel_id']);
            $owner_id=$channelInfo['owner_id'];
    }else{
            $owner_id=intval($options['userid']);
    }
    
    if($where != '') $where .= " AND";	
    //$where .= " CASE ";
    $friends_notify_ids = array();
    if( !$options['channel_id'] && intval($options['userid']) !=0 ){
			$where .= " (";	
			$where .= " ( NF.user_id<>:Userid AND NF.owner_id=:Userid AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." AND EXISTS ( SELECT CO.id FROM cms_social_comments AS CO WHERE CO.id=NF.entity_id AND CO.published=1 AND ( COALESCE(CO.channel_id, 0) = 0 OR ( COALESCE(CO.channel_id, 0) AND EXISTS (SELECT CH.id FROM cms_channel AS CH WHERE CH.id=CO.channel_id AND CH.published=1 AND CH.owner_id<>:Userid ) ) ) ) )";
            $where .= " OR";
			$where .= " ( NF.user_id=:Userid AND NF.owner_id <> :Userid AND NF.feed_privacy=0 AND NF.action_type=".SOCIAL_ACTION_COMMENT." AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id<>:Userid) )";
            $where .= " OR";
			$where .= " ( NF.user_id<>:Userid AND NF.owner_id<>:Userid AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS (SELECT PS.id FROM cms_social_posts AS PS WHERE PS.user_id =:Userid AND PS.id= NF.entity_id ) )";
            $where .= " OR";
			$where .= " ( NF.user_id=:Userid AND NF.feed_privacy=0 AND NF.owner_id <> :Userid AND ( NF.action_type =".SOCIAL_ACTION_SPONSOR." OR NF.action_type =".SOCIAL_ACTION_INVITE.") AND NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user=:Userid) )";
            //$where .= " OR";
			//$where .= " ( NF.user_id=:Userid AND NF.feed_privacy=0 AND NF.owner_id <> :Userid AND NF.action_type =".SOCIAL_ACTION_SHARE." THEN 0 )";
            $where .= " OR";
			$where .= " ( NF.user_id<>:Userid AND NF.feed_privacy<>0 AND NF.owner_id = :Userid AND ( NF.action_type =".SOCIAL_ACTION_SHARE." OR NF.action_type =".SOCIAL_ACTION_INVITE.") AND NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user=:Userid) )";
            $where .= " OR";
			$where .= " ( NF.user_id<>:Userid AND NF.feed_privacy=0 AND NF.owner_id = :Userid AND NF.action_type =".SOCIAL_ACTION_REECHOE." AND NOT EXISTS (SELECT id FROM cms_flash_reechoe AS SH WHERE SH.id=NF.action_id AND SH.user_id=:Userid) )";
            $where .= " OR";
			$where .= " ( NF.user_id<>:Userid AND NF.owner_id<>:Userid AND NF.feed_privacy<>0 AND NF.action_type =".SOCIAL_ACTION_UPDATE." AND NF.entity_type IN(".SOCIAL_ENTITY_EVENTS_DATE.",".SOCIAL_ENTITY_EVENTS_TIME.",".SOCIAL_ENTITY_EVENTS_LOCATION.") AND COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT id FROM cms_users_event_join AS SH WHERE SH.event_id=NF.entity_id AND SH.user_id=:Userid) )";
            $where .= " OR";
			$where .= " ( NF.user_id<>:Userid AND NF.owner_id<>:Userid AND NF.feed_privacy<>0 AND NF.action_type =".SOCIAL_ACTION_UPDATE." AND NF.entity_type IN(".SOCIAL_ENTITY_EVENTS_DATE.",".SOCIAL_ENTITY_EVENTS_TIME.",".SOCIAL_ENTITY_EVENTS_LOCATION.") AND COALESCE(NF.channel_id, 0) AND EXISTS (SELECT id FROM cms_channel_event_join AS SH WHERE SH.event_id=NF.entity_id AND SH.user_id=:Userid) )";
            $where .= " OR";
			$where .= " ( NF.user_id=:Userid AND NF.feed_privacy=0 AND NF.owner_id <> :Userid AND NF.action_type =".SOCIAL_ACTION_EVENT_CANCEL." )";
            $where .= " OR";
			$where .= " ( NF.user_id<>:Userid AND NF.owner_id = :Userid AND NF.feed_privacy<>0 AND NF.action_type NOT IN(".SOCIAL_ACTION_SPONSOR.",".SOCIAL_ACTION_SHARE.",".SOCIAL_ACTION_RELATION_SUB.",".SOCIAL_ACTION_RELATION_PARENT.",".SOCIAL_ACTION_INVITE.",".SOCIAL_ACTION_REECHOE.") )";
			
			$userid = intval($options['userid']);
            $friends_notify = userGetFreindNotificationList($userid);
            
            foreach($friends_notify as $freind){
                $friends_notify_ids[] = $freind['id'];
            }
            if(count($friends_notify_ids)!=0){ 
				$where .= " OR";			
                $where .= " ( NF.user_id<>'{$options['userid']}' AND NF.owner_id<>'{$options['userid']}' AND NF.feed_privacy<>0 AND COALESCE(NF.channel_id, 0) = 0 AND NF.user_id IN (" . implode(',', $friends_notify_ids) . ") AND ( (EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='".USER_PRIVACY_PUBLIC."' LIMIT 1) OR NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 LIMIT 1)) OR (COALESCE(NF.channel_id, 0) AND NF.owner_id<>NF.user_id)) )";                
            }
			$where .= " )";
	
            //$where .= " WHEN NF.user_id<>:Userid AND NF.owner_id=:Userid AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." THEN EXISTS ( SELECT CO.id FROM cms_social_comments AS CO WHERE CO.id=NF.entity_id AND CO.published=1 AND ( COALESCE(CO.channel_id, 0) = 0 OR ( COALESCE(CO.channel_id, 0) AND EXISTS (SELECT CH.id FROM cms_channel AS CH WHERE CH.id=CO.channel_id AND CH.published=1 AND CH.owner_id<>:Userid ) ) ) )";
            //$where .= " WHEN NF.user_id=:Userid AND NF.owner_id <> :Userid AND NF.feed_privacy=0 AND NF.action_type=".SOCIAL_ACTION_COMMENT." THEN EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id<>:Userid)";
            //$where .= " WHEN NF.user_id<>:Userid AND NF.owner_id<>:Userid AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS (SELECT PS.id FROM cms_social_posts AS PS WHERE PS.user_id =:Userid AND PS.id= NF.entity_id ) THEN 1";
            //$where .= " WHEN NF.user_id=:Userid AND NF.feed_privacy=0 AND NF.owner_id <> :Userid AND ( NF.action_type =".SOCIAL_ACTION_SPONSOR." OR NF.action_type =".SOCIAL_ACTION_INVITE.") THEN NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user=:Userid)";
            //$where .= " WHEN NF.user_id=:Userid AND NF.feed_privacy=0 AND NF.owner_id <> :Userid AND NF.action_type =".SOCIAL_ACTION_SHARE." THEN 0";
            //$where .= " WHEN NF.user_id<>:Userid AND NF.feed_privacy<>0 AND NF.owner_id = :Userid AND ( NF.action_type =".SOCIAL_ACTION_SHARE." OR NF.action_type =".SOCIAL_ACTION_INVITE.") THEN NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user=:Userid)";
            //$where .= " WHEN NF.user_id<>:Userid AND NF.feed_privacy=0 AND NF.owner_id = :Userid AND NF.action_type =".SOCIAL_ACTION_REECHOE." THEN NOT EXISTS (SELECT id FROM cms_flash_reechoe AS SH WHERE SH.id=NF.action_id AND SH.user_id=:Userid)";
            //$where .= " WHEN NF.user_id<>:Userid AND NF.owner_id<>:Userid AND NF.feed_privacy<>0 AND NF.action_type =".SOCIAL_ACTION_UPDATE." AND NF.entity_type IN(".SOCIAL_ENTITY_EVENTS_DATE.",".SOCIAL_ENTITY_EVENTS_TIME.",".SOCIAL_ENTITY_EVENTS_LOCATION.") AND COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT id FROM cms_users_event_join AS SH WHERE SH.event_id=NF.entity_id AND SH.user_id=:Userid) THEN 1";
            //$where .= " WHEN NF.user_id<>:Userid AND NF.owner_id<>:Userid AND NF.feed_privacy<>0 AND NF.action_type =".SOCIAL_ACTION_UPDATE." AND NF.entity_type IN(".SOCIAL_ENTITY_EVENTS_DATE.",".SOCIAL_ENTITY_EVENTS_TIME.",".SOCIAL_ENTITY_EVENTS_LOCATION.") AND COALESCE(NF.channel_id, 0) AND EXISTS (SELECT id FROM cms_channel_event_join AS SH WHERE SH.event_id=NF.entity_id AND SH.user_id=:Userid) THEN 1";
            //$where .= " WHEN NF.user_id=:Userid AND NF.feed_privacy=0 AND NF.owner_id <> :Userid AND NF.action_type =".SOCIAL_ACTION_EVENT_CANCEL." THEN 1";
            //$where .= " WHEN NF.user_id<>:Userid AND NF.owner_id = :Userid AND NF.feed_privacy<>0 AND NF.action_type NOT IN(".SOCIAL_ACTION_SPONSOR.",".SOCIAL_ACTION_SHARE.",".SOCIAL_ACTION_RELATION_SUB.",".SOCIAL_ACTION_RELATION_PARENT.",".SOCIAL_ACTION_INVITE.",".SOCIAL_ACTION_REECHOE.") THEN 1";
            
            //$userid = intval($options['userid']);
            //$friends_notify = userGetFreindNotificationList($userid);
            
            //foreach($friends_notify as $freind){
                //$friends_notify_ids[] = $freind['id'];
            //}
            //if(count($friends_notify_ids)!=0){    
                //$where .= " WHEN NF.user_id<>'{$options['userid']}' AND NF.owner_id<>'{$options['userid']}' AND NF.feed_privacy<>0 AND COALESCE(NF.channel_id, 0) = 0 THEN NF.user_id IN (" . implode(',', $friends_notify_ids) . ") AND ( (EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='".USER_PRIVACY_PUBLIC."' LIMIT 1) OR NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 LIMIT 1)) OR (COALESCE(NF.channel_id, 0) AND NF.owner_id<>NF.user_id))";                
            //}
            $params[] = array( "key" => ":Userid", "value" =>$options['userid']);
    }else{
		$where .= " (";
		if( !$options['channel_id'] &&  intval($options['userid'])==0 ){
			$where .= " ( NF.user_id<>NF.owner_id AND NF.feed_privacy<>0 )";
			$where .= " OR";
			$where .= " ( NF.feed_privacy=0 AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." )";
			$where .= " OR";
			$where .= " ( NF.feed_privacy=0 AND NF.action_type=".SOCIAL_ACTION_REECHOE." )";
		}else{
			$where .= " ( NF.user_id<>:Owner_id3 )";
			$params[] = array( "key" => ":Owner_id3", "value" =>$owner_id);
		}
		$where .= " )";
            //if( !$options['channel_id'] &&  intval($options['userid'])==0 ){
                //$where .= " WHEN NF.user_id<>NF.owner_id AND NF.feed_privacy<>0 THEN 1";
                //$where .= " WHEN NF.feed_privacy=0 AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." THEN 1";
                //$where .= " WHEN NF.feed_privacy=0 AND NF.action_type=".SOCIAL_ACTION_REECHOE." THEN 1";
            //}else{
                //$where .= " WHEN NF.user_id<>:Owner_id3 THEN 1";
                //$params[] = array( "key" => ":Owner_id3", "value" =>$owner_id);
            //}
    }
    //$where .= " ELSE 0 END ";

    if( !$options['channel_id'] ){
        if( $options['is_notification'] ==1 ){            
            if( intval($options['userid']) !=0 ){
                if($where != '') $where .= " AND ";
                $where .= " NF.action_type<>".SOCIAL_ACTION_SPONSOR."";
                if(count($friends_notify_ids)!=0){
                    if($where != '') $where .= " AND ";
                    $where .= " ( COALESCE(NF.channel_id, 0) = 0 OR (NF.user_id<>'{$options['userid']}' AND NF.owner_id<>'{$options['userid']}' AND NF.feed_privacy<>0 AND NF.user_id IN (" . implode(',', $friends_notify_ids) . ") AND (( (EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='".USER_PRIVACY_PUBLIC."' LIMIT 1) OR NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 LIMIT 1 )) AND COALESCE(NF.channel_id, 0) = 0) OR (COALESCE(NF.channel_id, 0) AND NF.owner_id<>NF.user_id)) ) )";                          
                }else{
                    if($where != '') $where .= " AND ";
                    $where .= " COALESCE(NF.channel_id, 0) = 0 ";
                }
                
                if($where != '') $where .= " AND ";
                $check_us_notify0 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT , cms_social_shares AS SH WHERE SH.id=NF.action_id AND NT.poster_id=:Userid AND NT.receiver_id=SH.from_user AND NT.is_channel=0 AND NT.poster_is_channel=0 AND (NT.notify=0 OR NT.show_from_tuber=0) AND NT.published='1')";
                $check_us_notify1 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT , cms_social_shares AS SH WHERE SH.id=NF.action_id AND NT.poster_id=:Userid AND NT.receiver_id=SH.from_user AND NT.is_channel=1 AND NT.poster_is_channel=0 AND (NT.notify=0 OR NT.show_from_tuber=0) AND NT.published='1')";
                $check_us_notify2 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT , cms_social_comments AS CT WHERE CT.id=NF.action_id AND NT.poster_id=:Userid AND NT.receiver_id=CT.user_id AND NT.is_channel=0 AND NT.poster_is_channel=0 AND (NT.notify=0 OR NT.show_from_tuber=0) AND NT.published='1')";
				
                $where .= " (";
				$where .= " ( (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy=0  AND ".$check_us_notify0." )";
				$where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_SPONSOR." AND NF.feed_privacy=0 AND ".$check_us_notify1." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_COMMENT." AND EXISTS (SELECT CT.id FROM cms_social_comments AS CT WHERE CT.id=NF.action_id AND CT.user_id<>NF.user_id AND CT.published='1') AND NF.feed_privacy=0 AND ".$check_us_notify2." )";
                $check_us_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND (notify=0 OR show_from_tuber=0) AND published='1')";
                $where .= " OR";
                $where .= " ( ".$check_us_notify." )";
                $where .= " )";
				
                //$where .= " CASE ";
                //$where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy=0  THEN ".$check_us_notify0."";
                //$where .= " WHEN NF.action_type=".SOCIAL_ACTION_SPONSOR." THEN NF.feed_privacy=0 AND ".$check_us_notify1."";
                //$where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." AND EXISTS (SELECT CT.id FROM cms_social_comments AS CT WHERE CT.id=NF.action_id AND CT.user_id<>NF.user_id AND CT.published='1') AND NF.feed_privacy=0 THEN ".$check_us_notify2."";
                //$check_us_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND (notify=0 OR show_from_tuber=0) AND published='1')";
                //$where .= " ELSE ".$check_us_notify." END ";
                
                $not_user_array = getUserNotifications( intval($options['userid']) );            
                if($not_user_array){
                    $not_user_array = $not_user_array[0];
                    if(!$not_user_array['tuber_notifications']){
                        if($where != '') $where .= " AND";
                        $where .= " 0 ";
                    }else{
                        if($where != '') $where .= " AND";
			$where .= " (";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_SPONSOR." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_CONNECT." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_REPORT." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_EVENT_CANCEL." AND ".$not_user_array['tuber_updatedcanceledevent']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_INVITE." AND NF.entity_type=".SOCIAL_ENTITY_CHANNEL." AND ".$not_user_array['tuber_invitedchannel']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_INVITE." AND NF.entity_type!=".SOCIAL_ENTITY_CHANNEL." AND ".$not_user_array['tuber_invitedevent']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_COMMENT." AND EXISTS (SELECT CM.user_id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id<>NF.user_id) AND ".$not_user_array['tuber_mentionedcomment']." )";                        
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_COMMENT." AND ( NF.entity_type=".SOCIAL_ENTITY_EVENTS." OR NF.entity_type=".SOCIAL_ENTITY_USER_EVENTS." ) AND ".$not_user_array['tuber_commentedevent']." )";                        
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_COMMENT." AND NF.entity_type=".SOCIAL_ENTITY_FLASH." AND ".$not_user_array['tuber_commentedecho']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_COMMENT." AND NF.entity_type!=".SOCIAL_ENTITY_FLASH." AND NF.entity_type!=".SOCIAL_ENTITY_EVENTS." AND NF.entity_type!=".SOCIAL_ENTITY_USER_EVENTS." AND ".$not_user_array['tuber_commentedcontent']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_LIKE." AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." AND ".$not_user_array['tuber_likedcomment']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_LIKE." AND ( NF.entity_type=".SOCIAL_ENTITY_EVENTS." OR NF.entity_type=".SOCIAL_ENTITY_USER_EVENTS." ) AND ".$not_user_array['tuber_likedevent']." )";                        
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_LIKE." AND NF.entity_type=".SOCIAL_ENTITY_FLASH." AND ".$not_user_array['tuber_likedecho']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_LIKE." AND NF.entity_type!=".SOCIAL_ENTITY_COMMENT." AND NF.entity_type!=".SOCIAL_ENTITY_FLASH." AND NF.entity_type!=".SOCIAL_ENTITY_EVENTS." AND NF.entity_type!=".SOCIAL_ENTITY_USER_EVENTS." AND ".$not_user_array['tuber_likedcontent']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_REECHOE." AND NF.entity_type=".SOCIAL_ENTITY_FLASH." AND ".$not_user_array['tuber_reechoedecho']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_SHARE." AND ( NF.entity_type=".SOCIAL_ENTITY_EVENTS." OR NF.entity_type=".SOCIAL_ENTITY_USER_EVENTS." ) AND ".$not_user_array['tuber_sharedevent']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_SHARE." AND NF.entity_type!=".SOCIAL_ENTITY_EVENTS." AND NF.entity_type!=".SOCIAL_ENTITY_USER_EVENTS." AND ".$not_user_array['tuber_sharedcontent']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_UPDATE." AND ( NF.entity_type=".SOCIAL_ENTITY_EVENTS_DATE." OR NF.entity_type=".SOCIAL_ENTITY_EVENTS_TIME." OR NF.entity_type=".SOCIAL_ENTITY_EVENTS_LOCATION." ) AND ".$not_user_array['tuber_updatedcanceledevent']." )";                        
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_UPDATE." AND NF.entity_type!=".SOCIAL_ENTITY_EVENTS_DATE." AND NF.entity_type!=".SOCIAL_ENTITY_EVENTS_TIME." AND NF.entity_type!=".SOCIAL_ENTITY_EVENTS_LOCATION." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_RATE." AND ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." OR NF.entity_type=".SOCIAL_ENTITY_ALBUM." OR NF.entity_type=".SOCIAL_ENTITY_POST." ) AND ".$not_user_array['tuber_ratedmedia']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_RATE." AND NF.entity_type !=".SOCIAL_ENTITY_MEDIA." AND NF.entity_type !=".SOCIAL_ENTITY_ALBUM." AND NF.entity_type !=".SOCIAL_ENTITY_POST." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_EVENT_JOIN." AND ".$not_user_array['tuber_joinedevent']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND ".$not_user_array['tuber_addedfriend']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND ".$not_user_array['tuber_followed']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_UPLOAD." AND ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." OR NF.entity_type=".SOCIAL_ENTITY_ALBUM." ) AND ".$not_user_array['email_updates_media']." )";
                        $where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_UPLOAD." AND ".$not_user_array['email_newsTT']." )";
			$where .= " OR";
			$where .= " ( NF.action_type !=".SOCIAL_ACTION_UPLOAD." AND NF.action_type !=".SOCIAL_ACTION_FOLLOW." AND NF.action_type !=".SOCIAL_ACTION_FRIEND." AND NF.action_type !=".SOCIAL_ACTION_EVENT_JOIN." AND NF.action_type !=".SOCIAL_ACTION_RATE." AND NF.action_type !=".SOCIAL_ACTION_UPDATE." AND NF.action_type !=".SOCIAL_ACTION_SHARE." AND NF.action_type !=".SOCIAL_ACTION_REECHOE." AND NF.action_type !=".SOCIAL_ACTION_LIKE." AND NF.action_type !=".SOCIAL_ACTION_COMMENT." AND NF.action_type !=".SOCIAL_ACTION_INVITE." AND NF.action_type !=".SOCIAL_ACTION_EVENT_CANCEL." )";
			$where .= " )";
                        
                    }
                }
                $params[] = array( "key" => ":Userid", "value" =>$options['userid']);
            }else{
                if($where != '') $where .= " AND ";
                $where .= " COALESCE(NF.channel_id, 0) = 0 ";
            }
        }
    }else if ($options['channel_id'] != -1){
        if($where != '') $where .= " AND ";
        
        $myChannelId = intval($options['channel_id']);
        $channel_sub_array = getSubChannelRelationList($myChannelId,'1');
        if($channel_sub_array!=''){
            $myChannelId.=',';
            $myChannelId.=$channel_sub_array;
        }
        $check_ch_notify0 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT WHERE (NT.receiver_id=NF.channel_id OR (NT.receiver_id=NF.user_id AND (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.")) ) AND NT.poster_id=:Channel_id AND NT.is_channel=1 AND NT.poster_is_channel=1 AND NT.notify=0 AND NT.published='1')";
        $params[] = array( "key" => ":Channel_id", "value" =>$options['channel_id']);
                
        $where .= " NF.channel_id IN($myChannelId) AND $check_ch_notify0 AND ( (NF.feed_privacy=2 AND NF.user_id<>NF.owner_id) OR NF.feed_privacy=0 )";        
        
        if($where != '') $where .= " AND";
        $where .= " (";
        $where .= " ( NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NOT EXISTS (SELECT notify FROM cms_channel_connections WHERE channelid IN($myChannelId) AND userid=NF.user_id AND notify=0 AND ( published=1 OR published=-5 ) LIMIT 1) )";
        $where .= " OR NF.action_type=".SOCIAL_ACTION_SPONSOR."";
        $where .= " )";
	
        $not_channel_array = GetChannelNotifications($options['channel_id']);
        if($not_channel_array){
            $not_channel_array = $not_channel_array[0];
            if(!$not_channel_array['channel_notifications']){
                if($where != '') $where .= " AND";
                $where .= " 0 ";
            }else{
                if($where != '') $where .= " AND";
                $where .= " (";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_SPONSOR." AND NF.entity_type=".SOCIAL_ENTITY_CHANNEL." AND ".$not_channel_array['channel_sponsorschannel']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_SPONSOR." AND NF.entity_type=".SOCIAL_ENTITY_EVENTS." AND ".$not_channel_array['channel_sponsorsevent']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_CONNECT." AND ".$not_channel_array['channel_connects']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT." AND NF.user_id<>:Channel_id AND ".$not_channel_array['channel_addsubchannel']." )";                
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_REPORT." AND ".$not_channel_array['channel_reportedconnections']." )";                
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_EVENT_CANCEL." AND ".$not_channel_array['channel_cancelsponsoring']." )";                
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_INVITE." AND ".$not_channel_array['channel_invites']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_COMMENT." AND NF.entity_type=".SOCIAL_ENTITY_EVENTS." AND ".$not_channel_array['channel_commentedevent']." )";                
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_COMMENT." AND NF.entity_type!=".SOCIAL_ENTITY_EVENTS." AND ".$not_channel_array['channel_commentscontent']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_LIKE." AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." AND ".$not_channel_array['channel_likedcomment']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_LIKE." AND ( NF.entity_type=".SOCIAL_ENTITY_CHANNEL." OR NF.entity_type=".SOCIAL_ENTITY_CHANNEL_COVER." OR NF.entity_type=".SOCIAL_ENTITY_CHANNEL_INFO." OR NF.entity_type=".SOCIAL_ENTITY_CHANNEL_PROFILE." OR NF.entity_type=".SOCIAL_ENTITY_CHANNEL_SLOGAN." ) AND ".$not_channel_array['channel_likescontent']." )";              
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_LIKE." AND NF.entity_type=".SOCIAL_ENTITY_EVENTS." AND ".$not_channel_array['channel_likedevent']." )";
                $where .= " OR";
				$where .= " ( NF.action_type=".SOCIAL_ACTION_LIKE." AND NF.entity_type!=".SOCIAL_ENTITY_CHANNEL." AND NF.entity_type!=".SOCIAL_ENTITY_CHANNEL_COVER." AND NF.entity_type!=".SOCIAL_ENTITY_CHANNEL_INFO." AND NF.entity_type!=".SOCIAL_ENTITY_CHANNEL_PROFILE." AND NF.entity_type!=".SOCIAL_ENTITY_CHANNEL_SLOGAN." AND NF.entity_type!=".SOCIAL_ENTITY_EVENTS." AND NF.entity_type!=".SOCIAL_ENTITY_COMMENT." AND ".$not_channel_array['channel_likescontent']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_SHARE." AND NF.entity_type=".SOCIAL_ENTITY_EVENTS." AND ".$not_channel_array['channel_sharedevent']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_SHARE." AND NF.entity_type=".SOCIAL_ENTITY_CHANNEL." AND ".$not_channel_array['channel_sharedchannel']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_SHARE." AND NF.entity_type!=".SOCIAL_ENTITY_CHANNEL." AND NF.entity_type!=".SOCIAL_ENTITY_EVENTS." AND ".$not_channel_array['channel_sharedcontent']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_RATE." AND ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." OR NF.entity_type=".SOCIAL_ENTITY_ALBUM." OR NF.entity_type=".SOCIAL_ENTITY_POST." ) AND ".$not_channel_array['channel_ratedmedia']." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_RATE." AND NF.entity_type!=".SOCIAL_ENTITY_MEDIA." AND NF.entity_type!=".SOCIAL_ENTITY_ALBUM." AND NF.entity_type!=".SOCIAL_ENTITY_POST." )";
                $where .= " OR";
                $where .= " ( NF.action_type=".SOCIAL_ACTION_EVENT_JOIN." AND ".$not_channel_array['channel_joinsevent']." )";
                $where .= " OR";
                $where .= " ( NF.action_type!=".SOCIAL_ACTION_UPLOAD." AND NF.action_type!=".SOCIAL_ACTION_EVENT_JOIN." AND NF.action_type!=".SOCIAL_ACTION_RATE." AND NF.action_type!=".SOCIAL_ACTION_SHARE." AND NF.action_type!=".SOCIAL_ACTION_LIKE." AND NF.action_type!=".SOCIAL_ACTION_COMMENT." AND NF.action_type!=".SOCIAL_ACTION_INVITE." AND NF.action_type!=".SOCIAL_ACTION_EVENT_CANCEL." AND NF.action_type!=".SOCIAL_ACTION_REPORT." AND NF.action_type!=".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type!=".SOCIAL_ACTION_RELATION_PARENT." AND NF.action_type!=".SOCIAL_ACTION_CONNECT." AND NF.action_type!=".SOCIAL_ACTION_SPONSOR." )";
                $where .= " )";
		
                $params[] = array( "key" => ":Channel_id", "value" =>$options['channel_id']);                
            }
        }
    }else if($options['channel_id'] == -1){
        if( $where != '') $where .= ' AND ';
        $where .= " COALESCE(NF.channel_id, 0) ";
        if( $options['is_notification'] == 1 ){
            if($where != '') $where .= "AND ";
            $where .= "NF.user_id <> NF.owner_id ";
        }
    }
    if(!$options['action_type']){
            if($where != '') $where .= " AND ";
            $where .= " NF.action_type<>".SOCIAL_ACTION_UNFRIEND." AND NF.action_type<>".SOCIAL_ACTION_UNFOLLOW." ";
    }
    if(!$options['entity_type']){
        if($where != '') $where .= " AND ";
        global $CONFIG_EXEPT_ARRAY;
        $exept_array = $CONFIG_EXEPT_ARRAY;
        $where .= " NF.entity_type NOT IN(". implode(',', $exept_array) .") ";        
    }
    if( $options['escape_user'] ){
        if($where != '') $where .= " AND ";
        $where .= " NOT find_in_set(cast(NF.user_id as char), :Escape_user) ";
	$params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    if($where != '') $where = " WHERE $where ";
    $ret = array();
    $ret = array("where"  =>$where,
                 "params" =>$params );
    return $ret;
}
function newsfeedGroupingNotificationsSearch($srch_options) {


    global $dbConn;
    $params = array();  
    $default_opts = array(
            'limit' => 10,
            'page' => 0,
            'orderby' => 'id',
            'order' => 'a',
            'feed_id' => null,
            'escape_entity_type' => null,
            'before_entity_goup' => null,
            'before_entity_id' => 0,
            'entity_type' => null,
            'entity_group' => null,
            'entity_id' => null,
            'media_type' => null,
            'action_type' => null,
            'from_time' => null,
            'to_time' => null,
            'from_ts' => null,
            'to_ts' => null,
            'userid' => null,
            'owner_id' => null,
            'is_notification' => -1,
            'distinct_user' => 0,
            'is_visible' => 1,
            'channel_id' => null,
            'escape_user' => null,
            'feed_privacy' => null,
            'published' => '0,1',
            'n_results' => false,
    );

    $options = array_merge($default_opts, $srch_options);
    $nlimit ='';
    if( $options['limit'] ){
        $nlimit = intval($options['limit']);
        $skip = intval($options['page']) * $nlimit;
    }
    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
    
    $ret = getNotificationWhereNew($options);
    $where = $ret['where'];
    $params = $ret['params'];
    
    // Case of returning usual results.
    if(!$options['n_results']) {
            $query1 = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where ORDER BY $orderby $order";
            
            $query = "select * from ( $query1 LIMIT 0, 50 ) AS X GROUP BY X.action_type,X.entity_type,X.entity_group,X.feed_privacy ORDER BY $orderby $order ";
            if( $options['limit'] ){
//                    $query .= " LIMIT $skip, $nlimit";
                $query .= " LIMIT :Skip, :Nlimit";
                $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
                $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
            }
            //debug($query);
            //debug($params);exit;
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();

            $feed = array();
            $row = $select->fetchAll(PDO::FETCH_ASSOC);
            foreach($row as $row_item){ 
                    $feed_row = $row_item;	
                    $feed_row['action_row_count'] =0;
                    $feed_row['action_row_other'] =array();
                    $from_date = date('Y-m-d', strtotime($feed_row['feed_ts'])-2592000);
                    $to_date = date('Y-m-d', strtotime($feed_row['feed_ts']));
                    $srch_options = array(
                        'entity_id' => $feed_row['entity_id'],
                        'entity_type' => $feed_row['entity_type'],
                        'action_type' => $feed_row['action_type'],
                        'channel_id' => $feed_row['channel_id'],
                        'distinct_user' => 1,
                        'from_ts' => $from_date,
                        'to_ts' => $to_date,
                        'n_results' => true
                    );
                    switch( $feed_row['action_type'] ){
                        case SOCIAL_ACTION_COMMENT:
                            $feed_row['action_row'] = socialCommentRow($feed_row['action_id']);
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['user_id'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options);                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_LIKE:
                            $feed_row['action_row'] = socialLikeRow($feed_row['action_id']);
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['user_id'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options);                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);                            
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_RATE:
                            $feed_row['action_row'] = socialRateRow($feed_row['action_id']);
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['user_id'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);                            
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_INVITE:
                            $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['from_user'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options);
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break; 
                        case SOCIAL_ACTION_REECHOE:
                            $feed_row['action_row'] = socialReechoeRow($feed_row['action_id']);
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['user_id'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options);
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);                            
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_SHARE:
                            $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['from_user'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options);
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);                            
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_UPLOAD:
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
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options);
                        break;
                        case SOCIAL_ACTION_RELATION_SUB:
                        case SOCIAL_ACTION_RELATION_PARENT:
                            $feed_row['action_row'] = channelRelationRow($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['user_id'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options);                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);                            
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_SPONSOR:
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                            $feed_row['action_row'] = socialShareGet($feed_row['action_id']);

                            $sp_info = socialShareGet($feed_row['action_id']);
                            $feed_row['action_row']['msg'] = $sp_info['msg'];

                            unset($feed_row['YourUserName']);
                            unset($feed_row['FullName']);
                            unset($feed_row['display_fullname']);
                            unset($feed_row['profile_Pic']);
                            unset($feed_row['gender']);
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['from_user'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);                            
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_EVENT_JOIN:
                            $feed_row['join_row'] = joinEventInfo($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['user_id'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);                            
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_FRIEND:
                            $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];                            
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['user_id'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);                            
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_FOLLOW:
                            $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['user_id'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);                            
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_CONNECT:
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['user_id'];
                            $feed_row['action_row_count'] =newsfeedNotificationsSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedNotificationsSearch($srch_options);                            
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        default:
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                    }

                                    if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM ){
                                            //in case album
                                            $catalog_row = userCatalogDefaultMediaGet($feed_row['action_row']['entity_id']);
                                            if(!$catalog_row) $catalog_row = array();
                                            $feed_row['media_row'] = $catalog_row;

                                            $feed_row['original_entity_type'] = $feed_row['entity_type'];
                                            $feed_row['original_entity_id'] = $feed_row['entity_id'];

                                    }else if( ($feed_row['action_type'] == SOCIAL_ACTION_LIKE) && ($feed_row['entity_type'] == SOCIAL_ENTITY_COMMENT) ){
                                            //in case like a comment
                                            $cr = socialCommentRow($feed_row['entity_id']);
                                            $feed_row['media_row'] = socialEntityInfo($cr['entity_type'], $cr['entity_id']);

                                            $feed_row['original_media_row'] = $cr;

                                            $feed_row['original_entity_type'] = $feed_row['entity_type'];
                                            $feed_row['original_entity_id'] = $feed_row['entity_id'];

                                            $feed_row['entity_type'] = $cr['entity_type'];
                                            $feed_row['entity_id'] = $cr['entity_id'];
                                    }else if( ($feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME) &&  !$feed_row['channel_id'] ){
                                                    $feed_row['media_row'] = socialEntityInfo(SOCIAL_ENTITY_USER_EVENTS, $feed_row['action_row']['entity_id']);
                                    }else if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
                                                    if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_id']);
                                                    }else{
                                                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_row']['entity_id']);
                                                    }
                                    }else {
                                        //just get the media row
                                        if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                                        $feed_row['action_row']['entity_id'] = $feed_row['action_id'];
                                        }
                                        $feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['entity_id']);
                                        if( $feed_row['entity_type'] == SOCIAL_ENTITY_VISITED_PLACES ){
                                            $stateinfo = worldStateInfo($feed_row['media_row']['country_code'],$feed_row['media_row']['state_code']);
                                            $state_name = (!$stateinfo)? '' : $stateinfo['state_name'];
                                            $country_name= countryGetName($feed_row['media_row']['country_code']);
                                            $country_name = (!$country_name)? '' : $country_name;
                                            $feed_row['media_row']['state_name']=$state_name;
                                            $feed_row['media_row']['country_name']=$country_name;
                                        }
                                    }

                    ///////////////////////
                    //in case no profile pic and not the channel sponsor action
                    if( ( !isset($feed_row['profile_Pic']) || $feed_row['profile_Pic'] == '' ) && $feed_row['action_type'] != SOCIAL_ACTION_SPONSOR && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_SUB && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_PARENT ){
                            $feed_row['profile_Pic'] = 'he.jpg';
                            if($feed_row['gender']=='F'){
                                    $feed_row['profile_Pic'] = 'she.jpg';
                            }
                    }

                    $feed[] = $feed_row;
            }

            return $feed;

    // Case of returning n_results.
    } else {            
        $query1 = "SELECT NF.id FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where GROUP BY NF.action_type,NF.entity_type,NF.entity_group,NF.channel_id,NF.feed_privacy";
                
	$query = "SELECT count(X.id) FROM ($query1) AS X";
        //return $query;
//        $ret = db_query($query);
//        $row = db_fetch_row($ret);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	$row = $select->fetch();

        return $row[0];
    }


}
function getNotificationWhere($options){
//  Changed by Anthony Malak 08-05-2015 to PDO database
     
    $params = array();
    $where ='';
    if( $options['feed_privacy'] ){
        if($where != '') $where .= " AND ";
        if( intval($options['feed_privacy'])==-1){
            $where .= " NF.feed_privacy<>".USER_PRIVACY_PRIVATE."";
        }else{
//            $where .= " NF.feed_privacy='{$options['feed_privacy']}' ";
            $where .= " NF.feed_privacy=:Feed_privacy ";
            $params[] = array( "key" => ":Feed_privacy", "value" =>$options['feed_privacy']);
        }
    }
    if( !is_null($options['published']) ){
            if($where != '') $where .= " AND ";
//            $where .= " NF.published IN ( {$options['published']} ) ";
            $where .= " find_in_set(cast(NF.published as char), :Published) ";
            $params[] = array( "key" => ":Published", "value" =>$options['published']);
    }
    if( isset($options['entity_group']) && $options['entity_group'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NF.entity_group='{$options['entity_group']}' ";	
            $where .= " NF.entity_group=:Entity_group ";	
            $params[] = array( "key" => ":Entity_group", "value" =>$options['entity_group']);	
    }
    if( $options['is_visible'] != -1 ){
        if($options['is_visible'] == 1 && intval($options['userid']) !=0 ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid)";
            $params[] = array( "key" => ":Userid", "value" =>$options['userid']);	
        }else if($options['is_visible'] == 1 && $options['channel_id']) {
            $channelInfo=channelGetInfo($options['channel_id']);
            $owner_id=$channelInfo['owner_id'];
            if( $where != '') $where .= ' AND ';
//            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='$owner_id')";
            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id)";
            $params[] = array( "key" => ":Owner_id",
                                "value" =>$owner_id);	
        }else if($options['is_visible'] == 0 && intval($options['userid']) !=0 ){
            if( $where != '') $where .= ' AND ';
//            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid2)";
            $params[] = array( "key" => ":Userid2",
                                "value" =>$options['userid']);	
        }else if($options['is_visible'] == 0 && $options['channel_id'] && intval($options['channel_id']) > 0 ){
            $channelInfo=channelGetInfo($options['channel_id']);
            $owner_id=$channelInfo['owner_id'];
            if( $where != '') $where .= ' AND ';
//            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='$owner_id')";
            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id2)";
            $params[] = array( "key" => ":Owner_id2",
                                "value" =>$owner_id);
        }
    }
    if( $options['feed_id'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " NF.id='{$options['feed_id']}' ";	
        $where .= " NF.id=:Feed_id ";
        $params[] = array( "key" => ":Feed_id",
                            "value" =>$options['feed_id']);		
    }
    if( $options['before_entity_goup'] && $options['before_entity_id']>0 ){
        if($where != '') $where .= " AND ";
        $where .= " MD5(NF.entity_group) <>:Before_entity_goup AND NF.id<:Before_entity_id";
        $params[] = array( "key" => ":Before_entity_goup", "value" =>$options['before_entity_goup']);	
        $params[] = array( "key" => ":Before_entity_id", "value" =>$options['before_entity_id']);	
    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " NF.entity_type='{$options['entity_type']}' ";
        $where .= " NF.entity_type=:Entity_type ";
        $params[] = array( "key" => ":Entity_type",
                            "value" =>$options['entity_type']);				
    }
    if( $options['escape_entity_type'] ){
        if( $where != '') $where .= ' AND ';
        $escape_entity_type = $options['escape_entity_type'];
//        $where .= " NF.entity_type NOT IN ($escape_entity_type) ";
        $where .= " NOT find_in_set(cast(NF.entity_type as char), :Escape_entity_type) ";
        $params[] = array( "key" => ":Escape_entity_type", "value" =>$escape_entity_type);			
    }
    if( $options['entity_id'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " NF.entity_id='{$options['entity_id']}' ";	
        $where .= " NF.entity_id=:Entity_id ";		
        $params[] = array( "key" => ":Entity_id",
                            "value" =>$options['entity_id']);	
    }
    if( $options['action_type'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " NF.action_type='{$options['action_type']}' ";
        $where .= " NF.action_type=:Action_type ";
        $params[] = array( "key" => ":Action_type",
                            "value" =>$options['action_type']);	
    }
    if($options['from_ts'] || $options['to_ts']){			
        if($options['from_ts']){
            if( $where != '') $where .= " AND ";
//            $where .= " DATE(NF.feed_ts) >= '{$options['from_ts']}' ";
            $where .= " DATE(NF.feed_ts) >= :From_ts ";
            $params[] = array( "key" => ":From_ts",
                                "value" =>$options['from_ts']);	
        }
        if($options['to_ts']){
            if( $where != '') $where .= " AND ";
//            $where .= " DATE(NF.feed_ts) <= '{$options['to_ts']}' ";
            $where .= " DATE(NF.feed_ts) <= :To_ts ";
            $params[] = array( "key" => ":To_ts",
                                "value" =>$options['to_ts']);	
        }
    }		
    if($options['from_time'] || $options['to_time']){			
        if($options['from_time']){
            if( $where != '') $where .= " AND ";
//            $where .= " (NF.feed_ts) >= '{$options['from_time']}' ";
            $where .= " (NF.feed_ts) >= :From_time ";
            $params[] = array( "key" => ":From_time",
                                "value" =>$options['from_time']);	
        }
        if($options['to_time']){
            if( $where != '') $where .= " AND ";
            $where .= " (NF.feed_ts) <= :To_time ";
            $params[] = array( "key" => ":To_time",
                                "value" =>$options['to_time']);	
        }
    }	

    if($where != '') $where .= " AND ";		
    $where .= " CASE WHEN NF.entity_type=".SOCIAL_ENTITY_MEDIA." THEN EXISTS ( SELECT id FROM cms_videos VV WHERE VV.id=NF.entity_id AND VV.published=1";
    if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
//        $where .= " AND image_video='{$options['media_type']}' )";	
        $where .= " AND image_video=:Media_type )";	
        $params[] = array( "key" => ":Media_type",
                            "value" =>$options['media_type']);								
    }else{
        $where .= " )";
    }
    $where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_ALBUM." THEN EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) )";

    if( $options['channel_id'] && $options['channel_id'] !=-1 ){
            $channelInfo=channelGetInfo($options['channel_id']);
            $owner_id=$channelInfo['owner_id'];
    }else{
            $owner_id=intval($options['userid']);
    }
    $where .= " ELSE 1 END ";
    
    if($where != '') $where .= " AND ";
    $where .= " CASE ";
    $friends_notify_ids = array();
    if( !$options['channel_id'] && intval($options['userid']) !=0 ){
//            $where .= " WHEN NF.user_id<>'{$options['userid']}' AND NF.owner_id='{$options['userid']}' AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." THEN EXISTS ( SELECT CO.id FROM cms_social_comments AS CO WHERE CO.id=NF.entity_id AND CO.published=1 AND ( COALESCE(CO.channel_id, 0) = 0 OR ( COALESCE(CO.channel_id, 0) AND EXISTS (SELECT CH.id FROM cms_channel AS CH WHERE CH.id=CO.channel_id AND CH.published=1 AND CH.owner_id<>'{$options['userid']}' ) ) ) )";
            $where .= " WHEN NF.user_id<>:Userid AND NF.owner_id=:Userid AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." THEN EXISTS ( SELECT CO.id FROM cms_social_comments AS CO WHERE CO.id=NF.entity_id AND CO.published=1 AND ( COALESCE(CO.channel_id, 0) = 0 OR ( COALESCE(CO.channel_id, 0) AND EXISTS (SELECT CH.id FROM cms_channel AS CH WHERE CH.id=CO.channel_id AND CH.published=1 AND CH.owner_id<>:Userid ) ) ) )";
//            $where .= " WHEN NF.user_id='{$options['userid']}' AND NF.owner_id <> '{$options['userid']}' AND NF.feed_privacy=0 AND NF.action_type=".SOCIAL_ACTION_COMMENT." THEN EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id<>'{$options['userid']}')";
            $where .= " WHEN NF.user_id=:Userid AND NF.owner_id <> :Userid AND NF.feed_privacy=0 AND NF.action_type=".SOCIAL_ACTION_COMMENT." THEN EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id<>:Userid)";
//            $where .= " WHEN NF.user_id<>'{$options['userid']}' AND NF.owner_id<>'{$options['userid']}' AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS (SELECT PS.id FROM cms_social_posts AS PS WHERE PS.user_id ='{$options['userid']}' AND PS.id= NF.entity_id ) THEN 1";
            $where .= " WHEN NF.user_id<>:Userid AND NF.owner_id<>:Userid AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS (SELECT PS.id FROM cms_social_posts AS PS WHERE PS.user_id =:Userid AND PS.id= NF.entity_id ) THEN 1";
            //$where .= " WHEN NF.user_id='{$options['userid']}' AND NF.feed_privacy=0 AND NF.owner_id <> '{$options['userid']}' AND ( NF.action_type =".SOCIAL_ACTION_SPONSOR." OR NF.action_type =".SOCIAL_ACTION_SHARE." OR NF.action_type =".SOCIAL_ACTION_INVITE.") THEN NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user='{$options['userid']}')";
//            $where .= " WHEN NF.user_id='{$options['userid']}' AND NF.feed_privacy=0 AND NF.owner_id <> '{$options['userid']}' AND ( NF.action_type =".SOCIAL_ACTION_SPONSOR." OR NF.action_type =".SOCIAL_ACTION_INVITE.") THEN NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user='{$options['userid']}')";
            $where .= " WHEN NF.user_id=:Userid AND NF.feed_privacy=0 AND NF.owner_id <> :Userid AND ( NF.action_type =".SOCIAL_ACTION_SPONSOR." OR NF.action_type =".SOCIAL_ACTION_INVITE.") THEN NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user=:Userid)";
//            $where .= " WHEN NF.user_id='{$options['userid']}' AND NF.feed_privacy=0 AND NF.owner_id <> '{$options['userid']}' AND NF.action_type =".SOCIAL_ACTION_SHARE." THEN 0";
            $where .= " WHEN NF.user_id=:Userid AND NF.feed_privacy=0 AND NF.owner_id <> :Userid AND NF.action_type =".SOCIAL_ACTION_SHARE." THEN 0";
            
//            $where .= " WHEN NF.user_id<>'{$options['userid']}' AND NF.feed_privacy<>0 AND NF.owner_id = '{$options['userid']}' AND ( NF.action_type =".SOCIAL_ACTION_SHARE." OR NF.action_type =".SOCIAL_ACTION_INVITE.") THEN NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user='{$options['userid']}')";
            $where .= " WHEN NF.user_id<>:Userid AND NF.feed_privacy<>0 AND NF.owner_id = :Userid AND ( NF.action_type =".SOCIAL_ACTION_SHARE." OR NF.action_type =".SOCIAL_ACTION_INVITE.") THEN NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user=:Userid)";
//            $where .= " WHEN NF.user_id<>'{$options['userid']}' AND NF.feed_privacy=0 AND NF.owner_id = '{$options['userid']}' AND NF.action_type =".SOCIAL_ACTION_REECHOE." THEN NOT EXISTS (SELECT id FROM cms_flash_reechoe AS SH WHERE SH.id=NF.action_id AND SH.user_id='{$options['userid']}')";
            $where .= " WHEN NF.user_id<>:Userid AND NF.feed_privacy=0 AND NF.owner_id = :Userid AND NF.action_type =".SOCIAL_ACTION_REECHOE." THEN NOT EXISTS (SELECT id FROM cms_flash_reechoe AS SH WHERE SH.id=NF.action_id AND SH.user_id=:Userid)";
//            $where .= " WHEN NF.user_id<>'{$options['userid']}' AND NF.owner_id<>'{$options['userid']}' AND NF.feed_privacy<>0 AND NF.action_type =".SOCIAL_ACTION_UPDATE." AND NF.entity_type IN(".SOCIAL_ENTITY_EVENTS_DATE.",".SOCIAL_ENTITY_EVENTS_TIME.",".SOCIAL_ENTITY_EVENTS_LOCATION.") AND COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT id FROM cms_users_event_join AS SH WHERE SH.event_id=NF.entity_id AND SH.user_id='{$options['userid']}') THEN 1";
            $where .= " WHEN NF.user_id<>:Userid AND NF.owner_id<>:Userid AND NF.feed_privacy<>0 AND NF.action_type =".SOCIAL_ACTION_UPDATE." AND NF.entity_type IN(".SOCIAL_ENTITY_EVENTS_DATE.",".SOCIAL_ENTITY_EVENTS_TIME.",".SOCIAL_ENTITY_EVENTS_LOCATION.") AND COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT id FROM cms_users_event_join AS SH WHERE SH.event_id=NF.entity_id AND SH.user_id=:Userid) THEN 1";
//            $where .= " WHEN NF.user_id<>'{$options['userid']}' AND NF.owner_id<>'{$options['userid']}' AND NF.feed_privacy<>0 AND NF.action_type =".SOCIAL_ACTION_UPDATE." AND NF.entity_type IN(".SOCIAL_ENTITY_EVENTS_DATE.",".SOCIAL_ENTITY_EVENTS_TIME.",".SOCIAL_ENTITY_EVENTS_LOCATION.") AND COALESCE(NF.channel_id, 0) AND EXISTS (SELECT id FROM cms_channel_event_join AS SH WHERE SH.event_id=NF.entity_id AND SH.user_id='{$options['userid']}') THEN 1";
            $where .= " WHEN NF.user_id<>:Userid AND NF.owner_id<>:Userid AND NF.feed_privacy<>0 AND NF.action_type =".SOCIAL_ACTION_UPDATE." AND NF.entity_type IN(".SOCIAL_ENTITY_EVENTS_DATE.",".SOCIAL_ENTITY_EVENTS_TIME.",".SOCIAL_ENTITY_EVENTS_LOCATION.") AND COALESCE(NF.channel_id, 0) AND EXISTS (SELECT id FROM cms_channel_event_join AS SH WHERE SH.event_id=NF.entity_id AND SH.user_id=:Userid) THEN 1";
//            $where .= " WHEN NF.user_id='{$options['userid']}' AND NF.feed_privacy=0 AND NF.owner_id <> '{$options['userid']}' AND NF.action_type =".SOCIAL_ACTION_EVENT_CANCEL." THEN 1";
            $where .= " WHEN NF.user_id=:Userid AND NF.feed_privacy=0 AND NF.owner_id <> :Userid AND NF.action_type =".SOCIAL_ACTION_EVENT_CANCEL." THEN 1";
//            $where .= " WHEN NF.user_id<>'{$options['userid']}' AND NF.owner_id = '{$options['userid']}' AND NF.feed_privacy<>0 AND NF.action_type NOT IN(".SOCIAL_ACTION_SPONSOR.",".SOCIAL_ACTION_SHARE.",".SOCIAL_ACTION_RELATION_SUB.",".SOCIAL_ACTION_RELATION_PARENT.",".SOCIAL_ACTION_INVITE.",".SOCIAL_ACTION_REECHOE.") THEN 1";
            $where .= " WHEN NF.user_id<>:Userid AND NF.owner_id = :Userid AND NF.feed_privacy<>0 AND NF.action_type NOT IN(".SOCIAL_ACTION_SPONSOR.",".SOCIAL_ACTION_SHARE.",".SOCIAL_ACTION_RELATION_SUB.",".SOCIAL_ACTION_RELATION_PARENT.",".SOCIAL_ACTION_INVITE.",".SOCIAL_ACTION_REECHOE.") THEN 1";
            
            $userid = intval($options['userid']);
            $friends_notify = userGetFreindNotificationList($userid);
            
            foreach($friends_notify as $freind){
                $friends_notify_ids[] = $freind['id'];
            }
            if(count($friends_notify_ids)!=0){    
                $where .= " WHEN NF.user_id<>'{$options['userid']}' AND NF.owner_id<>'{$options['userid']}' AND NF.feed_privacy<>0 AND COALESCE(NF.channel_id, 0) = 0 THEN NF.user_id IN (" . implode(',', $friends_notify_ids) . ") AND ( (EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='".USER_PRIVACY_PUBLIC."' LIMIT 1) OR NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 LIMIT 1)) OR (COALESCE(NF.channel_id, 0) AND NF.owner_id<>NF.user_id))";                
            }
            $params[] = array( "key" => ":Userid",
                                "value" =>$options['userid']);
    }else{
            if( !$options['channel_id'] &&  intval($options['userid'])==0 ){
                $where .= " WHEN NF.user_id<>NF.owner_id AND NF.feed_privacy<>0 THEN 1";
                $where .= " WHEN NF.feed_privacy=0 AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." THEN 1";
                $where .= " WHEN NF.feed_privacy=0 AND NF.action_type=".SOCIAL_ACTION_REECHOE." THEN 1";
            }else{
//                $where .= " WHEN NF.user_id<>'$owner_id' THEN 1";
                $where .= " WHEN NF.user_id<>:Owner_id3 THEN 1";
                $params[] = array( "key" => ":Owner_id3",
                                    "value" =>$owner_id);
            }
    }
    $where .= " ELSE 0 END ";

    if( !$options['channel_id'] ){
        if( $options['is_notification'] ==1 ){            
            if( intval($options['userid']) !=0 ){
                if($where != '') $where .= " AND ";
                $where .= " NF.action_type<>".SOCIAL_ACTION_SPONSOR."";
                if(count($friends_notify_ids)!=0){
                    if($where != '') $where .= " AND ";
                    $where .= " ( COALESCE(NF.channel_id, 0) = 0 OR (NF.user_id<>'{$options['userid']}' AND NF.owner_id<>'{$options['userid']}' AND NF.feed_privacy<>0 AND NF.user_id IN (" . implode(',', $friends_notify_ids) . ") AND (( (EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='".USER_PRIVACY_PUBLIC."' LIMIT 1) OR NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 LIMIT 1 )) AND COALESCE(NF.channel_id, 0) = 0) OR (COALESCE(NF.channel_id, 0) AND NF.owner_id<>NF.user_id)) ) )";                          
                }else{
                    if($where != '') $where .= " AND ";
                    $where .= " COALESCE(NF.channel_id, 0) = 0 ";
                }
                
                if($where != '') $where .= " AND ";
//                $check_us_notify0 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT , cms_social_shares AS SH WHERE SH.id=NF.action_id AND NT.poster_id='{$options['userid']}' AND NT.receiver_id=SH.from_user AND NT.is_channel=0 AND NT.poster_is_channel=0 AND (NT.notify=0 OR NT.show_from_tuber=0) AND NT.published='1')";
                $check_us_notify0 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT , cms_social_shares AS SH WHERE SH.id=NF.action_id AND NT.poster_id=:Userid AND NT.receiver_id=SH.from_user AND NT.is_channel=0 AND NT.poster_is_channel=0 AND (NT.notify=0 OR NT.show_from_tuber=0) AND NT.published='1')";
//                $check_us_notify1 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT , cms_social_shares AS SH WHERE SH.id=NF.action_id AND NT.poster_id='{$options['userid']}' AND NT.receiver_id=SH.from_user AND NT.is_channel=1 AND NT.poster_is_channel=0 AND (NT.notify=0 OR NT.show_from_tuber=0) AND NT.published='1')";
                $check_us_notify1 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT , cms_social_shares AS SH WHERE SH.id=NF.action_id AND NT.poster_id=:Userid AND NT.receiver_id=SH.from_user AND NT.is_channel=1 AND NT.poster_is_channel=0 AND (NT.notify=0 OR NT.show_from_tuber=0) AND NT.published='1')";
//                $check_us_notify2 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT , cms_social_comments AS CT WHERE CT.id=NF.action_id AND NT.poster_id='{$options['userid']}' AND NT.receiver_id=CT.user_id AND NT.is_channel=0 AND NT.poster_is_channel=0 AND (NT.notify=0 OR NT.show_from_tuber=0) AND NT.published='1')";
                $check_us_notify2 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT , cms_social_comments AS CT WHERE CT.id=NF.action_id AND NT.poster_id=:Userid AND NT.receiver_id=CT.user_id AND NT.is_channel=0 AND NT.poster_is_channel=0 AND (NT.notify=0 OR NT.show_from_tuber=0) AND NT.published='1')";
                
                $where .= " CASE ";
                $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy=0  THEN ".$check_us_notify0."";
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SPONSOR." THEN NF.feed_privacy=0 AND ".$check_us_notify1."";
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." AND EXISTS (SELECT CT.id FROM cms_social_comments AS CT WHERE CT.id=NF.action_id AND CT.user_id<>NF.user_id AND CT.published='1') AND NF.feed_privacy=0 THEN ".$check_us_notify2."";
//                $check_us_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id='{$options['userid']}' AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND (notify=0 OR show_from_tuber=0) AND published='1')";
                $check_us_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND (notify=0 OR show_from_tuber=0) AND published='1')";
                $where .= " ELSE ".$check_us_notify." END ";
                
                $not_user_array = getUserNotifications( intval($options['userid']) );            
                if($not_user_array){
                    $not_user_array = $not_user_array[0];
                    if(!$not_user_array['tuber_notifications']){
                        if($where != '') $where .= " AND";
                        $where .= " 0 ";
                    }else{
                        if($where != '') $where .= " AND ";
                        $where .= " CASE ";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SPONSOR." THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_CONNECT." THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_REPORT." THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_EVENT_CANCEL." THEN ".$not_user_array['tuber_updatedcanceledevent'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_INVITE." AND NF.entity_type=".SOCIAL_ENTITY_CHANNEL." THEN ".$not_user_array['tuber_invitedchannel'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_INVITE." THEN ".$not_user_array['tuber_invitedevent'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." AND EXISTS (SELECT CM.user_id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id<>NF.user_id) THEN ".$not_user_array['tuber_mentionedcomment'];
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." AND ( NF.entity_type=".SOCIAL_ENTITY_EVENTS." OR NF.entity_type=".SOCIAL_ENTITY_USER_EVENTS." ) THEN ".$not_user_array['tuber_commentedevent'];
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." AND NF.entity_type=".SOCIAL_ENTITY_FLASH." THEN ".$not_user_array['tuber_commentedecho'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." THEN ".$not_user_array['tuber_commentedcontent'];
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_LIKE." AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." THEN ".$not_user_array['tuber_likedcomment'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_LIKE." AND ( NF.entity_type=".SOCIAL_ENTITY_EVENTS." OR NF.entity_type=".SOCIAL_ENTITY_USER_EVENTS." ) THEN ".$not_user_array['tuber_likedevent'];                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_LIKE." AND NF.entity_type=".SOCIAL_ENTITY_FLASH." THEN ".$not_user_array['tuber_likedecho'];
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_LIKE." THEN ".$not_user_array['tuber_likedcontent'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_REECHOE." AND NF.entity_type=".SOCIAL_ENTITY_FLASH." THEN ".$not_user_array['tuber_reechoedecho'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SHARE." AND ( NF.entity_type=".SOCIAL_ENTITY_EVENTS." OR NF.entity_type=".SOCIAL_ENTITY_USER_EVENTS." ) THEN ".$not_user_array['tuber_sharedevent'];
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SHARE." THEN ".$not_user_array['tuber_sharedcontent'];
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_UPDATE." AND ( NF.entity_type=".SOCIAL_ENTITY_EVENTS_DATE." OR NF.entity_type=".SOCIAL_ENTITY_EVENTS_TIME." OR NF.entity_type=".SOCIAL_ENTITY_EVENTS_LOCATION." ) THEN ".$not_user_array['tuber_updatedcanceledevent'];                        
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_UPDATE." THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_RATE." AND ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." OR NF.entity_type=".SOCIAL_ENTITY_ALBUM." OR NF.entity_type=".SOCIAL_ENTITY_POST." ) THEN ".$not_user_array['tuber_ratedmedia'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_RATE." THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_EVENT_JOIN." THEN ".$not_user_array['tuber_joinedevent'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." THEN ".$not_user_array['tuber_addedfriend'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." THEN ".$not_user_array['tuber_followed'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_UPLOAD." AND ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." OR NF.entity_type=".SOCIAL_ENTITY_ALBUM." ) THEN ".$not_user_array['email_updates_media'];
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_UPLOAD." THEN ".$not_user_array['email_newsTT'];
                        $where .= " ELSE 1 END ";
                        
                    }
                }
                $params[] = array( "key" => ":Userid", "value" =>$options['userid']);
            }else{
                if($where != '') $where .= " AND ";
                $where .= " COALESCE(NF.channel_id, 0) = 0 ";
            }
        }
    }else if ($options['channel_id'] != -1){
        if($where != '') $where .= " AND ";
        
        $myChannelId = intval($options['channel_id']);
        $channel_sub_array = getSubChannelRelationList($myChannelId,'1');
        if($channel_sub_array!=''){
            $myChannelId.=',';
            $myChannelId.=$channel_sub_array;
        }
//        $check_ch_notify0 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT WHERE (NT.receiver_id=NF.channel_id OR (NT.receiver_id=NF.user_id AND (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.")) ) AND NT.poster_id='{$options['channel_id']}' AND NT.is_channel=1 AND NT.poster_is_channel=1 AND NT.notify=0 AND NT.published='1')";
        $check_ch_notify0 = "NOT EXISTS (SELECT NT.poster_id FROM cms_social_notifications AS NT WHERE (NT.receiver_id=NF.channel_id OR (NT.receiver_id=NF.user_id AND (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.")) ) AND NT.poster_id=:Channel_id AND NT.is_channel=1 AND NT.poster_is_channel=1 AND NT.notify=0 AND NT.published='1')";
        $params[] = array( "key" => ":Channel_id",
                            "value" =>$options['channel_id']);
                
        $where .= " NF.channel_id IN($myChannelId) AND $check_ch_notify0 AND ( (NF.feed_privacy=2 AND NF.user_id<>NF.owner_id) OR NF.feed_privacy=0 )";        
        
        if($where != '') $where .= " AND ";
        $where .= " CASE ";
        $where .= " WHEN NF.action_type<>".SOCIAL_ACTION_SPONSOR." THEN NOT EXISTS (SELECT notify FROM cms_channel_connections WHERE channelid IN($myChannelId) AND userid=NF.user_id AND notify=0 AND ( published=1 OR published=-5 ) LIMIT 1) ";
        
        $where .= " ELSE 1 END ";
        $not_channel_array = GetChannelNotifications($options['channel_id']);
        if($not_channel_array){
            $not_channel_array = $not_channel_array[0];
            if(!$not_channel_array['channel_notifications']){
                if($where != '') $where .= " AND";
                $where .= " 0 ";
            }else{
                if($where != '') $where .= " AND ";
                $where .= " CASE ";
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SPONSOR." AND NF.entity_type=".SOCIAL_ENTITY_CHANNEL." THEN ".$not_channel_array['channel_sponsorschannel'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SPONSOR." AND NF.entity_type=".SOCIAL_ENTITY_EVENTS." THEN ".$not_channel_array['channel_sponsorsevent'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_CONNECT." THEN ".$not_channel_array['channel_connects'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT." THEN NF.user_id<>:Channel_id AND ".$not_channel_array['channel_addsubchannel'];                
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_REPORT." THEN ".$not_channel_array['channel_reportedconnections'];                
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_EVENT_CANCEL." THEN ".$not_channel_array['channel_cancelsponsoring'];                
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_INVITE." THEN ".$not_channel_array['channel_invites'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." AND NF.entity_type=".SOCIAL_ENTITY_EVENTS." THEN ".$not_channel_array['channel_commentedevent'];                
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." THEN ".$not_channel_array['channel_commentscontent'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_LIKE." AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." THEN ".$not_channel_array['channel_likedcomment'];
                
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_LIKE." AND ( NF.entity_type=".SOCIAL_ENTITY_CHANNEL." OR NF.entity_type=".SOCIAL_ENTITY_CHANNEL_COVER." OR NF.entity_type=".SOCIAL_ENTITY_CHANNEL_INFO." OR NF.entity_type=".SOCIAL_ENTITY_CHANNEL_PROFILE." OR NF.entity_type=".SOCIAL_ENTITY_CHANNEL_SLOGAN." ) THEN ".$not_channel_array['channel_likescontent'];              
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_LIKE." AND NF.entity_type=".SOCIAL_ENTITY_EVENTS." THEN ".$not_channel_array['channel_likedevent'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_LIKE." THEN ".$not_channel_array['channel_likescontent'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SHARE." AND NF.entity_type=".SOCIAL_ENTITY_EVENTS." THEN ".$not_channel_array['channel_sharedevent'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SHARE." AND NF.entity_type=".SOCIAL_ENTITY_CHANNEL." THEN ".$not_channel_array['channel_sharedchannel'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SHARE." THEN ".$not_channel_array['channel_sharedcontent'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_RATE." AND ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." OR NF.entity_type=".SOCIAL_ENTITY_ALBUM." OR NF.entity_type=".SOCIAL_ENTITY_POST." ) THEN ".$not_channel_array['channel_ratedmedia'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_EVENT_JOIN." THEN ".$not_channel_array['channel_joinsevent'];
                
                //$where .= " WHEN NF.action_type=".SOCIAL_ACTION_UPLOAD." AND ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." OR NF.entity_type=".SOCIAL_ENTITY_ALBUM." ) THEN ".$not_channel_array['email_updates_media'];
                //$where .= " WHEN NF.action_type=".SOCIAL_ACTION_UPLOAD." THEN ".$not_channel_array['email_newsTT'];
                $where .= " WHEN NF.action_type=".SOCIAL_ACTION_UPLOAD." THEN 0";
                $where .= " ELSE 1 END ";
                $params[] = array( "key" => ":Channel_id", "value" =>$options['channel_id']);                
            }
        }
    }else if($options['channel_id'] == -1){
        if( $where != '') $where .= ' AND ';
        $where .= " COALESCE(NF.channel_id, 0) ";
        if( $options['is_notification'] == 1 ){
            if($where != '') $where .= "AND ";
            $where .= "NF.user_id <> NF.owner_id ";
        }
    }
    if(!$options['action_type']){
            if($where != '') $where .= " AND ";
            $where .= " NF.action_type<>".SOCIAL_ACTION_UNFRIEND." AND NF.action_type<>".SOCIAL_ACTION_UNFOLLOW." ";
    }
    if(!$options['entity_type']){
        if($where != '') $where .= " AND ";
        global $CONFIG_EXEPT_ARRAY;
        $exept_array = $CONFIG_EXEPT_ARRAY;
        $where .= " NF.entity_type NOT IN(". implode(',', $exept_array) .") ";        
    }
    if( $options['escape_user'] ){
        if($where != '') $where .= " AND ";
//        $where .= " NF.user_id NOT IN( {$options['escape_user']} ) ";
        $where .= " NOT find_in_set(cast(NF.user_id as char), :Escape_user) ";
	$params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    if($where != '') $where = " WHERE $where ";
    $ret = array();
    $ret = array("where"  =>$where,
                 "params" =>$params ); 
//    return $where;
    return $ret;
//  Changed by Anthony Malak 09-05-2015 to PDO database

}
/**
 * gets the newsfeed of a user
 * @param array $srch_options options to search. options include:<br/>
 * <b>limit</b>: the maximum number of user records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>userid</b>: which users's news feed. required.<br/>
 * <b>search_string</b>: which users's news feed. required.<br/>
 * @from_ts: start date default null<br/>
 * @to_ts: end date default null<br/>
 * @from_time: start time default null<br/>
 * @to_time: end time default null<br/>
 * @from_filter: filter on retrived data related to (FROM_FRIENDS or FROM_FOLLOWINGS or FROM_CHANNELS) default null<br/>
 * @activities_filter: filter on retrived data related to activities (ACTIVITIES_ON_TCHANNELS or ACTIVITIES_ON_THOTELS or ACTIVITIES_ON_TRESTAURANTS or ACTIVITIES_ON_TPLANNER or ACTIVITIES_ON_TTPAGE) default null<br/>
 * <b>media_type</b>: what type of media file (v)ideo or (i)mage or null. default null<br/>
 * @param integer $entity_type the entity type of the feed
 * @param integer $entity_id the entity id of the feed
 * @param integer $action_type the action type of the feed
 * <b>is_visible</b>: 1=> visible, 0=> invisible. -1 => doesnt matter. default -1.<br/>
 * <b>channel_id</b> the channel involved, null => no channel involved, -1 => doesn't matter<br/>
 * <b>feed_privacy</b> feed privacy, null => doesn't matter, -1 feed not equal 0<br/>
 * <b>published</b>: published status of record. default '0,1' . 0 for newsfeed not viewed . 1 newsfeed viewed . null => doesnt matter.<br/>
 * @return array a set of 'newsfeed records' could either be a comment, of an upload
 */
function getNewsfeedPageSearchWhereNew($options){
	global $dbConn;
	$params  = array();
	$params2 = array();
        $where = '';
	$followers_users = array();
	$friends_ids = array();
        if($options['userid']){
            $userid = $options['userid'];
        }
        else{
            $userid =userGetID();
        }
	if( isset($userid) && $userid>0 ){
                
                $friends = userGetFreindList($userid);
                
                
		$ids = array($userid);
		$friends_ids = array($userid);
		foreach($friends as $freind){
			$ids[] = $freind['id'];
			$friends_ids[] = $freind['id'];
		}
		
		if(count($ids)!=0){
                    if($where != '') $where .= " AND";
                    $public = USER_PRIVACY_PUBLIC;
                    $private = USER_PRIVACY_PRIVATE;
                    $selected = USER_PRIVACY_SELECTED;
                    $community = USER_PRIVACY_COMMUNITY;
                    $privacy_where = '';

					
                    $where .= " (";
                    $where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1  LIMIT 1 ) )";
                    $where .= " OR";
					$where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) )";
                    $where .= " OR";
                    $where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) )";
                    $where .= " OR";
                    $where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) )";
                    $where .= " OR";
					$where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) )";
                    $where .= " OR";
					$where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) )";
                    $where .= " OR";
					$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1  LIMIT 1 ) )";                    
                    $where .= " OR";
					$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) )";
                    $where .= " OR";
					$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) )";
                    $where .= " OR";
					$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) )";                    
                    $where .= " OR";
					$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) )";
					$where .= " OR";
					$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) )";
					
					$where .= " OR";
					$where .= " ( EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) )";
                    $where .= " OR";
					$where .= " ( NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1  LIMIT 1 ) )";
                    $where .= " OR";
					$where .= " ( EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) )";
                    $where .= " OR";
					$where .= " ( EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) )";
                    
                    $where .= " OR";
					$where .= " ( EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) )";
					$where .= " OR";
					$where .= " ( EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) )";
					$where .= " )";
			
                    $params[] = array( "key" => ":Userid", "value" =>$userid);
                    $params[] = array( "key" => ":Public", "value" =>$public);
                    $params[] = array( "key" => ":Private", "value" => $private);
		}
	}
	
	if( $options['search_string'] && (strlen($options['search_string'])!=0) ){
		if($where != ''){
                    $where .= " AND ( ( U.display_fullname = 0 AND LOWER(YourUserName) LIKE :Search_string ) OR ( U.display_fullname = 1 AND LOWER(FullName) LIKE :Search_string2 ) )";	
                    $params[] = array( "key" => ":Search_string",
                                        "value" =>$options['search_string']."%");	
                    $params[] = array( "key" => ":Search_string2",
                                        "value" =>"%".$options['search_string']."%");	
                }
	}
	if( $options['from_id']!=0 ){
		if($where != ''){ 
                    $where .= " AND ";
                }
                $where .= " NF.id > :From_id";
                $params[] = array( "key" => ":From_id",
                                    "value" =>$options['from_id']);	
                
	}
    if( $options['feed_id'] ){
        if($where != '') $where .= " AND ";
        $where .= " NF.id=:Feed_id";
        $params[] = array( "key" => ":Feed_id",
                            "value" =>$options['feed_id']);	
    }
    if( $options['before_entity_goup'] && $options['before_entity_id']>0 ){
        if($where != '') $where .= " AND ";
        $where .= " MD5(NF.entity_group) <>:Before_entity_goup AND NF.id<:Before_entity_id";
        $params[] = array( "key" => ":Before_entity_goup", "value" =>$options['before_entity_goup']);	
        $params[] = array( "key" => ":Before_entity_id", "value" =>$options['before_entity_id']);	
    }
    if( !$options['userid'] ){
        if($where != '') $where .= " AND ";
        $where .= " NF.feed_privacy=".USER_PRIVACY_PUBLIC."";
    }
    if( $options['channel_id'] ){
        if($where != '') $where .= " AND ";
        if( $options['channel_id'] ==-1 ){
            $where .= " COALESCE(NF.channel_id, 0) ";            
        }else{   
            $where .= " NF.channel_id = :Channel_id ";   
            $params[] = array( "key" => ":Channel_id",
                                "value" =>$options['channel_id']);         
        }
    }
    if( !is_null($options['published']) ){
        if($where != '') $where .= " AND ";       
        $where .= " find_in_set(cast(NF.published as char), :Published) ";
	$params[] = array( "key" => ":Published", "value" =>$options['published']);
    }
    if( $options['escape_user'] ){
        if($where != '') $where .= " AND ";      
        $where .= " NOT find_in_set(cast(NF.user_id as char), :Escape_user) ";
	$params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " NF.entity_type=:Entity_type ";
        $params[] = array( "key" => ":Entity_type",
                            "value" =>$options['entity_type']); 		
    }
    if( $options['entity_id'] ){
            if( $where != '') $where .= ' AND ';
            $where .= " NF.entity_id=:Entity_id ";
            $params[] = array( "key" => ":Entity_id",
                                "value" =>$options['entity_id']); 		
    }
    if( $options['entity_group'] ){
            if( $where != '') $where .= ' AND ';	
            $where .= " NF.entity_group=:Entity_group ";	
            $params[] = array( "key" => ":Entity_group",
                                "value" =>$options['entity_group']); 	
    }
    if( $options['action_type'] ){
            if( $where != '') $where .= ' AND ';
            $where .= " NF.action_type='{$options['action_type']}' ";
            $params[] = array( "key" => ":Action_type",
                                "value" =>$options['action_type']); 	
    }
    if($options['from_ts'] || $options['to_ts']){			
        if($options['from_ts']){
            if( $where != '') $where .= " AND ";
            $where .= " DATE(NF.feed_ts) >= :From_ts ";
            $params[] = array( "key" => ":From_ts",
                                "value" =>$options['from_ts']); 
        }
        if($options['to_ts']){
            if( $where != '') $where .= " AND ";
            $where .= " DATE(NF.feed_ts) <= :To_ts ";
            $params[] = array( "key" => ":To_ts",
                                "value" =>$options['to_ts']); 
        }
    }		
    if($options['from_time'] || $options['to_time']){			
        if($options['from_time']){
            if( $where != '') $where .= " AND ";
            $where .= " (NF.feed_ts) >= :From_time ";
            $params[] = array( "key" => ":From_time",
                                "value" =>$options['from_time']); 
        }
        if($options['to_time']){
            if( $where != '') $where .= " AND ";
            $where .= " (NF.feed_ts) <= :To_time ";
            $params[] = array( "key" => ":To_time",
                                "value" =>$options['to_time']); 
        }
    }
    if($where != '') $where .= " AND ";
    $where .= " NF.action_type<>".SOCIAL_ACTION_INVITE." ";	
    if(!$options['action_type']){
		if($where != '') $where .= " AND ";
		$where .= " NF.action_type<>".SOCIAL_ACTION_UNFRIEND." AND NF.action_type<>".SOCIAL_ACTION_UNFOLLOW." ";
    }
    if(!$options['entity_type']){
        if($where != '') $where .= " AND ";
        global $CONFIG_EXEPT_ARRAY;
        $exept_array = $CONFIG_EXEPT_ARRAY;
        $where .= " NF.entity_type NOT IN(". implode(',', $exept_array) .") ";       
    }
	if( !$options['from_filter'] ){
            if($where != '') $where .= " AND";
            $where .= " (";
			$where .= " ( COALESCE(NF.channel_id, 0) = 0 AND ( (EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND  requester_id=:Userid2 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT.")) OR (EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid2)) OR ( NF.user_id=:User_id AND NF.feed_privacy=0 AND NF.owner_id <> :User_id AND NF.action_type =".SOCIAL_ACTION_SHARE." ) ) )";
            $where .= " OR";
			$where .= " ( COALESCE(NF.channel_id, 0) AND ( ( EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid2 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." )) OR (EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid2)) OR (EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE ( (CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid2 LIMIT 1 )) OR (CHA.id=NF.user_id AND CHA.owner_id<>NF.user_id AND (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.")) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.user_id AND F.published=1 AND F.userid=:Userid2 LIMIT 1 ) ) ) ) ) )";
            
            $where .= " )";
            
			//$where .= "CASE";
            //$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN (EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND  requester_id=:Userid2 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT.")) OR (EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid2)) OR ( NF.user_id=:User_id AND NF.feed_privacy=0 AND NF.owner_id <> :User_id AND NF.action_type =".SOCIAL_ACTION_SHARE." )";
            //$where .= " WHEN COALESCE(NF.channel_id, 0) THEN ( EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid2 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." )) OR (EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid2)) OR (EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE ( (CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid2 LIMIT 1 )) OR (CHA.id=NF.user_id AND CHA.owner_id<>NF.user_id AND (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.")) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.user_id AND F.published=1 AND F.userid=:Userid2 LIMIT 1 ) ) ) )";
            //$where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Userid2", "value" =>$userid); 
            $params[] = array( "key" => ":User_id", "value" =>$options['userid']); 
        }else if( $options['from_filter'] && intval($options['from_filter'])== FROM_FRIENDS ){
            if($where != '') $where .= " AND";
            $where .= " (";
			$where .= " ( COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." ) )";
            $where .= " OR";
			$where .= " ( COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." ) )";
			
            $where .= " )";
			
            //$where .= "CASE";
            //$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." )";
            //$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." )";
            //$where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Userid3", "value" =>$userid); 
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_FOLLOWINGS ){
            if($where != '') $where .= " AND";
            $where .= " (";
			$where .= " ( COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4) )";
            $where .= " OR";
			$where .= " ( COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4) )";
			$where .= " )";
			
            //$where .= "CASE";
            //$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4)";
            //$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4)";
            //$where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Userid4", "value" =>$userid); 
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_CHANNELS ){
            if($where != '') $where .= " AND";
            $where .= " (";
			$where .= " COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE ( (CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid5 LIMIT 1 )) OR (CHA.id=NF.user_id AND CHA.owner_id<>NF.user_id AND (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.") ) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.user_id AND F.published=1 AND F.userid=:Userid5 LIMIT 1 ) ) )";
			$where .= " )";
			
            //$where .= "CASE";
            //$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE ( (CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid5 LIMIT 1 )) OR (CHA.id=NF.user_id AND CHA.owner_id<>NF.user_id AND (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.") ) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.user_id AND F.published=1 AND F.userid=:Userid5 LIMIT 1 ) ) )";
            //$where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Userid5", "value" =>$userid); 
	}
	if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TCHANNELS ){
		if($where != '') $where .= " AND ";
		$where .= "COALESCE(NF.channel_id, 0) ";
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TTPAGE ){
		if($where != '') $where .= " AND ";
		$where .= "COALESCE(NF.channel_id, 0) = 0 ";
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_THOTELS ){
		if($where != '') $where .= " AND ";
		$where .= "NF.entity_type=".SOCIAL_ENTITY_BAG." ";
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TECHOES ){
		if($where != '') $where .= " AND ";
		$where .= "NF.entity_type=".SOCIAL_ENTITY_FLASH." ";
	}
	
	if($where != '') $where .= " AND";	
	$where .= " (";	
	$where .= " ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." AND EXISTS ( SELECT id FROM cms_videos VV WHERE VV.id=NF.entity_id AND VV.published=1";
	if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
		$where .= " AND image_video=:Media_type )";
		$params[] = array( "key" => ":Media_type", "value" =>$options['media_type']); 								
	}else{
		$where .= " )";
	}
	$where .= " )";
	$where .= " OR";	
	$where .= " ( NF.entity_type=".SOCIAL_ENTITY_ALBUM." AND EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) ) )";
	$where .= " OR";	
	$where .= " ( NF.entity_type !=".SOCIAL_ENTITY_COMMENT." )";
	$where .= " )";
	
	//$where .= " CASE";
	//$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_MEDIA." THEN EXISTS ( SELECT id FROM cms_videos VV WHERE VV.id=NF.entity_id AND VV.published=1";
	//if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
		//$where .= " AND image_video=:Media_type )";
		//$params[] = array( "key" => ":Media_type", "value" =>$options['media_type']); 								
	//}else{
		//$where .= " )";
	//}
	//$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_ALBUM." THEN EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) )";
	//$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_COMMENT." THEN 0";
	//$where .= " ELSE 1 END ";
	
	if( intval($options['userid']) !=0 ){	
		if($where != '') $where .= " AND";	
		$where .= " (";	
		$where .= " ( NF.user_id=:Userid6 AND NF.feed_privacy=0 AND NF.owner_id <> :Userid6 AND NF.action_type =".SOCIAL_ACTION_SHARE." AND NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user=:Userid6) )";	    
		$where .= " OR";	
		$where .= " ( ( NF.feed_privacy !=0 AND NF.action_type !=".SOCIAL_ACTION_SHARE." ) AND ( ( NF.user_id<>:Userid6 ) OR ( NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 AND ((PO.from_id<>0 AND PO.from_id<>:Userid6) OR (PO.from_id=0 AND PO.user_id<>:Userid6)) ) ) ) )";
		$where .= " )";
		
		//$where .= " CASE";
		//$where .= " WHEN NF.user_id=:Userid6 AND NF.feed_privacy=0 AND NF.owner_id <> :Userid6 AND NF.action_type =".SOCIAL_ACTION_SHARE." THEN NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user=:Userid6)";
		//$where .= " WHEN NF.feed_privacy=0 THEN 0";
		//$where .= " WHEN NF.action_type =".SOCIAL_ACTION_SHARE." THEN 0";    
		//$where .= " WHEN NF.user_id<>:Userid6 THEN 1";
		//$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." THEN EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 AND ((PO.from_id<>0 AND PO.from_id<>:Userid6) OR (PO.from_id=0 AND PO.user_id<>:Userid6)) )";
		//$where .= " ELSE 0 END ";     
        $params[] = array( "key" => ":Userid6", "value" =>$options['userid']);            
	}
	
	if( intval($options['userid']) !=0 ){
		$check_us_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid7 AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND show_from_tuber=0 AND published='1')";
		$check_ch_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid7 AND receiver_id=NF.channel_id AND is_channel=1 AND poster_is_channel=0 AND show_from_tuber=0 AND published='1')";
		$check_sp_ch_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid7 AND receiver_id=NF.user_id AND is_channel=1 AND poster_is_channel=0 AND show_from_tuber=0 AND published='1')";
		
		if($where != '') $where .= " AND";	
		$where .= " (";	
		$where .= " ( COALESCE(NF.channel_id, 0) = 0 AND ".$check_us_notify." )";
		$where .= " OR";
		$where .= " ( COALESCE(NF.channel_id, 0) AND (NF.action_type =".SOCIAL_ACTION_SPONSOR." OR NF.action_type =".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type =".SOCIAL_ACTION_RELATION_PARENT.") AND ".$check_sp_ch_notify." )";
		$where .= " OR";
		$where .= " ( COALESCE(NF.channel_id, 0) AND NF.action_type <>".SOCIAL_ACTION_SPONSOR." AND NF.action_type <>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type <>".SOCIAL_ACTION_RELATION_PARENT." AND ( (EXISTS (SELECT id FROM cms_channel AS CH WHERE CH.id=NF.channel_id AND CH.owner_id<>NF.user_id AND CH.published='1') AND ".$check_us_notify." ) OR (EXISTS (SELECT id FROM cms_channel AS CH WHERE CH.id=NF.channel_id AND CH.owner_id=NF.user_id AND CH.published='1') AND ".$check_ch_notify." ) ) )";
		$where .= " )";	
		
		//$where .= " CASE";
		//$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN ".$check_us_notify;
		//$where .= " WHEN COALESCE(NF.channel_id, 0) AND (NF.action_type =".SOCIAL_ACTION_SPONSOR." OR NF.action_type =".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type =".SOCIAL_ACTION_RELATION_PARENT.") THEN ".$check_sp_ch_notify;
		//$where .= " WHEN COALESCE(NF.channel_id, 0) AND NF.action_type <>".SOCIAL_ACTION_SPONSOR." AND NF.action_type <>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type <>".SOCIAL_ACTION_RELATION_PARENT." THEN ( (EXISTS (SELECT id FROM cms_channel AS CH WHERE CH.id=NF.channel_id AND CH.owner_id<>NF.user_id AND CH.published='1') AND ".$check_us_notify." ) OR (EXISTS (SELECT id FROM cms_channel AS CH WHERE CH.id=NF.channel_id AND CH.owner_id=NF.user_id AND CH.published='1') AND ".$check_ch_notify." ) )";
		//$where .= " ELSE 0 END ";
        $params[] = array( "key" => ":Userid7", "value" =>$options['userid']); 
	}
	
	if($where != '') $where = " WHERE $where ";
        $ret = array();
        $ret = array("where"  =>$where, "params" =>$params );
        return $ret;
}
function newsfeedPageSearch($srch_options) {
//  Changed by Anthony Malak 09-05-2015 to PDO database

	global $dbConn;
	$params = array(); 
        
	$default_opts = array(
            'limit' => 10,
            'page' => 0,
            'orderby' => 'id',
            'order' => 'a',
            'entity_type' => null,
            'entity_group' => null,
            'before_entity_goup' => null,
            'before_entity_id' => 0,
            'feed_id' => null,
            'entity_id' => null,
            'media_type' => null,
            'action_type' => null,
            'from_time' => null,
            'to_time' => null,
            'from_ts' => null,
            'to_ts' => null,
            'distinct_user' => 0,
            'from_filter' => null,
            'activities_filter' => null,
            'userid' => null,
            'owner_id' => null,
            'is_visible' => 1,
            'escape_user' => null,
            'search_string' => null,
            'feed_privacy' => null,
            'channel_id' => null,
            'published' => '0,1',
            'n_results' => false,
            'from_id' => 0
	);

	$options = array_merge($default_opts, $srch_options);
    
    $userId =   $options['userid'] ;
    
	$nlimit ='';	
	if( $options['limit'] ){
		$nlimit = intval($options['limit']);
		$skip = intval($options['page']) * $nlimit;
	}


	$orderby = $options['orderby'];
	$order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
	
        $ret = getNewsfeedPageSearchWhereNew($options);
        $where = $ret['where'];
        $params = $ret['params'];
        
	if(!$options['n_results']){
		if($orderby=='most_liked'){
			$query1 = "SELECT
						NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender, ( SELECT COUNT(SL.id) FROM cms_social_likes AS SL WHERE SL.entity_id=NF.entity_id AND SL.entity_type=NF.entity_type AND SL.like_value=1 AND SL.published=1 ) AS counter
					FROM
						`cms_social_newsfeed` AS NF
						LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT."
                       
					$where ORDER BY counter $order";                        
		}else{
			$query1 = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where ORDER BY $orderby $order";
		}
                
                $query = "select * from ($query1 LIMIT 0, 50) AS X GROUP BY X.action_type,X.entity_type,X.entity_group ORDER BY $orderby $order ";
                //$query = "$query1 GROUP BY NF.action_type,NF.entity_type,NF.entity_group ORDER BY $orderby $order ";
		if( $options['limit'] ){
//			$query .= " LIMIT $skip, $nlimit";
                        $query .= " LIMIT :Skip, :Nlimit";
                        $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
                        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
		}
//                debug($query);
//                debug($params); exit;
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();

                $ret    = $select->rowCount();
		
		$feed = array();
                $row = $select->fetchAll(PDO::FETCH_ASSOC);
                foreach($row as $row_item){
                    $feed_row = $row_item;	
                    $feed_row['action_row_count'] =0;
                    $feed_row['action_row_other'] =array();                    
                    $from_date = date('Y-m-d', strtotime($feed_row['feed_ts'])-2592000);
                    $to_date = date('Y-m-d', strtotime($feed_row['feed_ts']));
                    $srch_options = array(
                        'entity_id' => $feed_row['entity_id'],
                        'entity_type' => $feed_row['entity_type'],
                        'action_type' => $feed_row['action_type'],
                        'channel_id' => $feed_row['channel_id'],
                        'distinct_user' => 1,
                        'from_ts' => $from_date,
                        'to_ts' => $to_date,
                        'n_results' => true
                    );
                    switch( $feed_row['action_type'] ){
                        case SOCIAL_ACTION_COMMENT:
                            $feed_row['action_row'] = socialCommentRow($feed_row['action_id']);                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['user_id'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_LIKE:
                            $feed_row['action_row'] = socialLikeRow($feed_row['action_id']);
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['user_id'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_RATE:
                            $feed_row['action_row'] = socialRateRow($feed_row['action_id']);
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['user_id'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_INVITE:
                                $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                        break; 
                        case SOCIAL_ACTION_REECHOE:
                            $feed_row['action_row'] = socialReechoeRow($feed_row['action_id']);
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['user_id'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_SHARE:
                            $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['from_user'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_UPLOAD:
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
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']>1){
                                $srch_options['n_results']=false;
                                $srch_options['order']='d';
                                $srch_options['limit']=4;
                                $srch_options['orderby']='NF.id';
                                $oth_lst = newsfeedOtherPageSearch($srch_options);
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_RELATION_SUB:
                        case SOCIAL_ACTION_RELATION_PARENT:
                            $feed_row['action_row'] = channelRelationRow($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['user_id'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_SPONSOR:
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                            $sp_info = socialShareGet($feed_row['action_id']);
                            $feed_row['action_row'] = $sp_info;
                            
                            $feed_row['action_row']['msg'] = $sp_info['msg'];

                            unset($feed_row['YourUserName']);
                            unset($feed_row['FullName']);
                            unset($feed_row['display_fullname']);
                            unset($feed_row['profile_Pic']);
                            unset($feed_row['gender']);
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['action_row']['from_user'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                                $srch_options['n_results']=false;
                                $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                                $srch_options['orderby']='NF.id';
                                $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_EVENT_JOIN:
                            $feed_row['join_row'] = joinEventInfo($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['user_id'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_FRIEND:
                            $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];                            
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['user_id'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_FOLLOW:
                            $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['user_id'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                            $srch_options['n_results']=false;
                            $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                            $srch_options['orderby']='NF.id';
                            $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_CONNECT:
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            
                            $srch_options['escape_user']='';  
                            if( intval($options['userid']) !=0 ){
                                $srch_options['escape_user']= $options['userid'];
                            }                              
                            if($srch_options['escape_user']!=''){
                                $srch_options['escape_user'] .=',';
                            }
                            $srch_options['escape_user'] .=$feed_row['user_id'];
                            $feed_row['action_row_count'] =newsfeedOtherPageSearch($srch_options); 
                            
                                $srch_options['n_results']=false;
                                $srch_options['order']='d';
                            if( !$options['feed_id'] ) $srch_options['limit']=10;
                                $srch_options['orderby']='NF.id';
                                $oth_lst = newsfeedOtherPageSearch($srch_options);
                            if($feed_row['action_row_count']==1){                                
                                $feed_row['action_row_other'] =$oth_lst[0];
                            }else{
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        default:
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                    }
			
                    if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM ){
                        $catalog_row = userCatalogDefaultMediaGet($feed_row['action_row']['entity_id']);
                        if(!$catalog_row) $catalog_row = array();
                        $feed_row['media_row'] = $catalog_row;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                    }else if( ($feed_row['action_type'] == SOCIAL_ACTION_LIKE) && ($feed_row['entity_type'] == SOCIAL_ENTITY_COMMENT) ){
                        $cr = socialCommentRow($feed_row['entity_id']);
                        $feed_row['media_row'] = socialEntityInfo($cr['entity_type'], $cr['entity_id']);

                        $feed_row['original_media_row'] = $cr;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                        $feed_row['entity_type'] = $cr['entity_type'];
                        $feed_row['entity_id'] = $cr['entity_id'];
                    }else if( ($feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME) &&  !$feed_row['channel_id'] ){
                            $feed_row['media_row'] = socialEntityInfo(SOCIAL_ENTITY_USER_EVENTS, $feed_row['action_row']['entity_id']);
                    }else if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_id']);
                            }else{
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_row']['entity_id']);
                            }
                    }else {
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['action_row']['entity_id'] = $feed_row['action_id'];
                            }
                            $feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['entity_id']);
                            if( $feed_row['entity_type'] == SOCIAL_ENTITY_VISITED_PLACES ){
                                $stateinfo = worldStateInfo($feed_row['media_row']['country_code'],$feed_row['media_row']['state_code']);
                                $state_name = (!$stateinfo)? '' : $stateinfo['state_name'];
                                $country_name= countryGetName($feed_row['media_row']['country_code']);
                                $country_name = (!$country_name)? '' : $country_name;
                                $feed_row['media_row']['state_name']=$state_name;
                                $feed_row['media_row']['country_name']=$country_name;
                            }
                    }
			
			if( ( !isset($feed_row['profile_Pic']) || $feed_row['profile_Pic'] == '' ) && $feed_row['action_type'] != SOCIAL_ACTION_SPONSOR && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_SUB && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_PARENT ){
				$feed_row['profile_Pic'] = 'he.jpg';
				if($feed_row['gender']=='F'){
					$feed_row['profile_Pic'] = 'she.jpg';
				}
			}
			
			$feed[] = $feed_row;
		}
		
		return $feed;
		
	} else {
		//$query = "SELECT count(*) FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";
		$query1 = "SELECT NF.id FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where GROUP BY NF.action_type,NF.entity_type,NF.entity_group,NF.channel_id";
                
		$query = "SELECT count(X.id) FROM ($query1) AS X";
		
//		$ret = db_query($query);
//		$row = db_fetch_row($ret);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
                $row    = $select->fetch();
		
		return $row[0];
	}
//  Changed by Anthony Malak 09-05-2015 to PDO database

}
function newsfeedOtherPageSearch($srch_options) {
//  Changed by Anthony Malak 09-05-2015 to PDO database

	global $dbConn;
	$params = array();  
	$default_opts = array(
            'limit' => 10,
            'page' => 0,
            'orderby' => 'id',
            'order' => 'a',
            'entity_type' => null,
            'entity_group' => null,
            'entity_id' => null,
            'before_entity_goup' => null,
            'before_entity_id' => 0,
            'media_type' => null,
            'feed_id' => null,
            'action_type' => null,
            'from_time' => null,
            'to_time' => null,
            'from_ts' => null,
            'to_ts' => null,
            'distinct_user' => 0,
            'from_filter' => null,
            'activities_filter' => null,
            'userid' => null,
            'owner_id' => null,
            'is_visible' => 1,
            'escape_user' => null,
            'search_string' => null,
            'feed_privacy' => null,
            'channel_id' => null,
            'published' => '0,1',
            'n_results' => false,
            'from_id' => 0
	);

	$options = array_merge($default_opts, $srch_options);	
	$nlimit ='';
	if( $options['limit'] ){
		$nlimit = intval($options['limit']);
		$skip = intval($options['page']) * $nlimit;
	}

        $orderby = $options['orderby'];
        $order='';
	if($orderby == 'rand'){
            $orderby = "RAND()";
	}else{
            $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
	}
	
        $ret = getNewsfeedPageSearchWhereNew($options);
        $where = $ret['where'];
        $params = $ret['params'];
        
	if(!$options['n_results']){
		 
                if( $options['distinct_user']==1 ){                    
                    if($orderby=='most_liked'){
			$query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender, ( SELECT COUNT(SL.id) FROM cms_social_likes AS SL WHERE SL.entity_id=NF.entity_id AND SL.entity_type=NF.entity_type AND SL.like_value=1 AND SL.published=1 ) AS counter FROM `cms_social_newsfeed` AS NF
                                                    LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where GROUP BY NF.user_id ORDER BY counter $order";                        
                    }else{			           
                        $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where GROUP BY NF.user_id ORDER BY $orderby $order";
                    } 
                }else{
                    if($orderby=='most_liked'){
			$query = "SELECT
						NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender, ( SELECT COUNT(SL.id) FROM cms_social_likes AS SL WHERE SL.entity_id=NF.entity_id AND SL.entity_type=NF.entity_type AND SL.like_value=1 AND SL.published=1 ) AS counter
					FROM
						`cms_social_newsfeed` AS NF
						LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT."
                       
					$where ORDER BY counter $order";                        
                    }else{			           
                        $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where ORDER BY $orderby $order";
                    } 
                }
		if( $options['limit'] ){
//			$query .= " LIMIT $skip, $nlimit";
			$query .= " LIMIT :Skip, :Nlimit";
                        $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
                        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
		}
		//return $query;
//		$ret = db_query($query);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();

                $ret    = $select->rowCount();
		
		$feed = array();                
//		while($row = db_fetch_assoc($ret)){
                $row = $select->fetchAll(PDO::FETCH_ASSOC);
                foreach($row as $row_item){
                    $feed_row = $row_item;
                    switch( $feed_row['action_type'] ){
                        case SOCIAL_ACTION_COMMENT:
                            $feed_row['action_row'] = socialCommentRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_LIKE:
                            $feed_row['action_row'] = socialLikeRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_RATE:
                            $feed_row['action_row'] = socialRateRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_INVITE:
                                $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                        break; 
                        case SOCIAL_ACTION_SHARE:
                                $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                                break;
                        case SOCIAL_ACTION_REECHOE:
                            $feed_row['action_row'] = socialReechoeRow($feed_row['action_id']);                           
                        break;
                        case SOCIAL_ACTION_UPLOAD:
                            $feed_row['action_row'] = array();
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                        break;
                        case SOCIAL_ACTION_RELATION_SUB:
                        case SOCIAL_ACTION_RELATION_PARENT:
                            $feed_row['action_row'] = channelRelationRow($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;                            
                        break;
                        case SOCIAL_ACTION_SPONSOR:
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                            $feed_row['action_row'] = socialShareGet($feed_row['action_id']);

                            $sp_info = socialShareGet($feed_row['action_id']);
                            $feed_row['action_row']['msg'] = $sp_info['msg'];

                            unset($feed_row['YourUserName']);
                            unset($feed_row['FullName']);
                            unset($feed_row['display_fullname']);
                            unset($feed_row['profile_Pic']);
                            unset($feed_row['gender']);
                        break;
                        case SOCIAL_ACTION_EVENT_JOIN:
                            $feed_row['join_row'] = joinEventInfo($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                        break;
                        case SOCIAL_ACTION_FRIEND:
                            $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id']; 
                        break;
                        case SOCIAL_ACTION_FOLLOW:
                            $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];                            
                        break;
                        case SOCIAL_ACTION_CONNECT:
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];                            
                        break;
                        default:
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                    }
			
                    if( ( isset($feed_row['action_row']['entity_type']) && $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM ) || $feed_row['entity_type'] == SOCIAL_ENTITY_ALBUM ){
                        $catalog_row = userCatalogDefaultMediaGet($feed_row['action_row']['entity_id']);
                        if(!$catalog_row) $catalog_row = array();
                        $feed_row['media_row'] = $catalog_row;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                    }else if( ($feed_row['action_type'] == SOCIAL_ACTION_LIKE) && ($feed_row['entity_type'] == SOCIAL_ENTITY_COMMENT) ){
                        $cr = socialCommentRow($feed_row['entity_id']);
                        $feed_row['media_row'] = socialEntityInfo($cr['entity_type'], $cr['entity_id']);

                        $feed_row['original_media_row'] = $cr;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                        $feed_row['entity_type'] = $cr['entity_type'];
                        $feed_row['entity_id'] = $cr['entity_id'];
                    }else if( ($feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME) &&  !$feed_row['channel_id'] ){
                            $feed_row['media_row'] = socialEntityInfo(SOCIAL_ENTITY_USER_EVENTS, $feed_row['action_row']['entity_id']);
                    }else if( isset($feed_row['action_row']['entity_type']) && ($feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN) ){
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_id']);
                            }else{
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_row']['entity_id']);
                            }
                    }else {
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['action_row']['entity_id'] = $feed_row['action_id'];
                            }
                            $feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['entity_id']);
                            if( $feed_row['entity_type'] == SOCIAL_ENTITY_VISITED_PLACES ){
                                $stateinfo = worldStateInfo($feed_row['media_row']['country_code'],$feed_row['media_row']['state_code']);
                                $state_name = (!$stateinfo)? '' : $stateinfo['state_name'];
                                $country_name= countryGetName($feed_row['media_row']['country_code']);
                                $country_name = (!$country_name)? '' : $country_name;
                                $feed_row['media_row']['state_name']=$state_name;
                                $feed_row['media_row']['country_name']=$country_name;
                            }
                    }
			
			if( ( !isset($feed_row['profile_Pic']) || $feed_row['profile_Pic'] == '' ) && $feed_row['action_type'] != SOCIAL_ACTION_SPONSOR && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_SUB && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_PARENT ){
				$feed_row['profile_Pic'] = 'he.jpg';
				if($feed_row['gender']=='F'){
					$feed_row['profile_Pic'] = 'she.jpg';
				}
			}
			
			$feed[] = $feed_row;
		}
		
		return $feed;
		
	} else {
                if( $options['distinct_user']==1 ){
                    $query = "SELECT count(DISTINCT NF.user_id) FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";
                }else{
                    $query = "SELECT count(*) FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";
                }
		//return $query;
//		$ret = db_query($query);
//		$row = db_fetch_row($ret);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();

                $ret    = $select->rowCount();
                $row = $select->fetch();
		
		return $row[0];
	}
//  Changed by Anthony Malak 09-05-2015 to PDO database

}
function getNewsfeedPageSearchWhere($options){
	global $dbConn;
	$params  = array();
	$params2 = array();
        
        $where = '';
	$followers_users = array();
	$friends_ids = array();
        if($options['userid']){
            $userid = $options['userid'];
        }
        else{
            $userid =userGetID();
        }
	if( isset($userid) && $userid>0 ){
		$friends = userGetFreindList($userid);
                
		$friends_ids = array($userid);
		foreach($friends as $freind){
			$friends_ids[] = $freind['id'];
		}
		if(count($friends_ids)!=0){
                    if($where != '') $where .= " AND ";
                    $public = USER_PRIVACY_PUBLIC;
                    $private = USER_PRIVACY_PRIVATE;
                    $selected = USER_PRIVACY_SELECTED;
                    $community = USER_PRIVACY_COMMUNITY;
                    $privacy_where = '';

                    $where .= "CASE";
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1  LIMIT 1 ) THEN 1";
//                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.user_id = '$userid' LIMIT 1 ) THEN 1";
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) THEN 1";
//                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
                    
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) THEN 1";
		    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
		    
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." THEN 0";
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1  LIMIT 1 ) THEN 1";
//                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.user_id = '$userid' LIMIT 1 ) THEN 1";
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) THEN 1";
//                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
                    
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) THEN 1";
		    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
		    
                    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." THEN 0";
//                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                    $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1  LIMIT 1 ) THEN 1";
//                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.user_id = '$userid' LIMIT 1 ) THEN 1";
                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) THEN 1";
                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
                    
//                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$userid' LIMIT 1 ) THEN 1";
                    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) THEN 1";
		    $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
		    
                    $where .= " ELSE 0 END ";
                    $params[] = array( "key" => ":Userid", "value" =>$userid);
                    $params[] = array( "key" => ":Public", "value" =>$public);
                    $params[] = array( "key" => ":Private", "value" => $private);
		}
	}
	
	if( $options['search_string'] && (strlen($options['search_string'])!=0) ){
		if($where != ''){
                    $where .= " AND ( ( U.display_fullname = 0 AND LOWER(YourUserName) LIKE :Search_string ) OR ( U.display_fullname = 1 AND LOWER(FullName) LIKE :Search_string2 ) )";	
                    $params[] = array( "key" => ":Search_string",
                                        "value" =>$options['search_string']."%");	
                    $params[] = array( "key" => ":Search_string2",
                                        "value" =>"%".$options['search_string']."%");	
                }
	}
	if( $options['from_id']!=0 ){
		if($where != ''){ 
                    $where .= " AND ";
                }
//                $where .= " NF.id > ".$options['from_id'];
                $where .= " NF.id > :From_id";
                $params[] = array( "key" => ":From_id",
                                    "value" =>$options['from_id']);	
                
	}
    if( $options['feed_id'] ){
        if($where != '') $where .= " AND ";
//        $where .= " NF.id='{$options['feed_id']}'";
        $where .= " NF.id=:Feed_id";
        $params[] = array( "key" => ":Feed_id",
                            "value" =>$options['feed_id']);	
    }
    if( $options['before_entity_goup'] && $options['before_entity_id']>0 ){
        if($where != '') $where .= " AND ";
        $where .= " MD5(NF.entity_group) <>:Before_entity_goup AND NF.id<:Before_entity_id";
        $params[] = array( "key" => ":Before_entity_goup", "value" =>$options['before_entity_goup']);	
        $params[] = array( "key" => ":Before_entity_id", "value" =>$options['before_entity_id']);	
    }
    if( !$options['userid'] ){
        if($where != '') $where .= " AND ";
        $where .= " NF.feed_privacy=".USER_PRIVACY_PUBLIC."";
    }
    if( $options['channel_id'] ){
        if($where != '') $where .= " AND ";
        if( $options['channel_id'] ==-1 ){
            $where .= " COALESCE(NF.channel_id, 0) ";            
        }else{
//            $where .= " NF.channel_id = '{$options['channel_id']}' ";   
            $where .= " NF.channel_id = :Channel_id ";   
            $params[] = array( "key" => ":Channel_id",
                                "value" =>$options['channel_id']);         
        }
    }
    if( !is_null($options['published']) ){
        if($where != '') $where .= " AND ";
//                $where .= " NF.published IN ( {$options['published']} ) ";        
        $where .= " find_in_set(cast(NF.published as char), :Published) ";
	$params[] = array( "key" => ":Published", "value" =>$options['published']);
    }
    if( $options['escape_user'] ){
        if($where != '') $where .= " AND ";
//        $where .= " NF.user_id NOT IN( {$options['escape_user']} ) ";        
        $where .= " NOT find_in_set(cast(NF.user_id as char), :Escape_user) ";
	$params[] = array( "key" => ":Escape_user", "value" =>$options['escape_user']);
    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " NF.entity_type='{$options['entity_type']}' ";
        $where .= " NF.entity_type=:Entity_type ";
        $params[] = array( "key" => ":Entity_type",
                            "value" =>$options['entity_type']); 		
    }
    if( $options['entity_id'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NF.entity_id='{$options['entity_id']}' ";
            $where .= " NF.entity_id=:Entity_id ";
            $params[] = array( "key" => ":Entity_id",
                                "value" =>$options['entity_id']); 		
    }
    if( $options['entity_group'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NF.entity_group='{$options['entity_group']}' ";	
            $where .= " NF.entity_group=:Entity_group ";	
            $params[] = array( "key" => ":Entity_group",
                                "value" =>$options['entity_group']); 	
    }
    if( $options['action_type'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NF.action_type='{$options['action_type']}' ";
            $where .= " NF.action_type='{$options['action_type']}' ";
            $params[] = array( "key" => ":Action_type",
                                "value" =>$options['action_type']); 	
    }
    if($options['from_ts'] || $options['to_ts']){			
        if($options['from_ts']){
            if( $where != '') $where .= " AND ";
//            $where .= " DATE(NF.feed_ts) >= '{$options['from_ts']}' ";
            $where .= " DATE(NF.feed_ts) >= :From_ts ";
            $params[] = array( "key" => ":From_ts",
                                "value" =>$options['from_ts']); 
        }
        if($options['to_ts']){
            if( $where != '') $where .= " AND ";
//            $where .= " DATE(NF.feed_ts) <= '{$options['to_ts']}' ";
            $where .= " DATE(NF.feed_ts) <= :To_ts ";
            $params[] = array( "key" => ":To_ts",
                                "value" =>$options['to_ts']); 
        }
    }		
    if($options['from_time'] || $options['to_time']){			
        if($options['from_time']){
            if( $where != '') $where .= " AND ";
//            $where .= " (NF.feed_ts) >= '{$options['from_time']}' ";
            $where .= " (NF.feed_ts) >= :From_time ";
            $params[] = array( "key" => ":From_time",
                                "value" =>$options['from_time']); 
        }
        if($options['to_time']){
            if( $where != '') $where .= " AND ";
//            $where .= " (NF.feed_ts) <= '{$options['to_time']}' ";
            $where .= " (NF.feed_ts) <= :To_time ";
            $params[] = array( "key" => ":To_time",
                                "value" =>$options['to_time']); 
        }
    }
    if($where != '') $where .= " AND ";
    $where .= " NF.action_type<>".SOCIAL_ACTION_INVITE." ";	
    if(!$options['action_type']){
		if($where != '') $where .= " AND ";
		$where .= " NF.action_type<>".SOCIAL_ACTION_UNFRIEND." AND NF.action_type<>".SOCIAL_ACTION_UNFOLLOW." ";
    }
    if(!$options['entity_type']){
        if($where != '') $where .= " AND ";
        global $CONFIG_EXEPT_ARRAY;
        $exept_array = $CONFIG_EXEPT_ARRAY;
        $where .= " NF.entity_type NOT IN(". implode(',', $exept_array) .") ";       
    }
	if( !$options['from_filter'] ){
            if($where != '') $where .= " AND ";
            $where .= "CASE";
            $where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN (EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND  requester_id=:Userid2 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT.")) OR (EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid2)) OR ( NF.user_id=:User_id AND NF.feed_privacy=0 AND NF.owner_id <> :User_id AND NF.action_type =".SOCIAL_ACTION_SHARE." )";
            $where .= " WHEN COALESCE(NF.channel_id, 0) THEN ( EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid2 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." )) OR (EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid2)) OR (EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE ( (CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid2 LIMIT 1 )) OR (CHA.id=NF.user_id AND CHA.owner_id<>NF.user_id AND (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.")) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.user_id AND F.published=1 AND F.userid=:Userid2 LIMIT 1 ) ) ) )";
            $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Userid2",
                                "value" =>$userid); 
            $params[] = array( "key" => ":User_id",
                                "value" =>$options['userid']); 
        }else if( $options['from_filter'] && intval($options['from_filter'])== FROM_FRIENDS ){
            if($where != '') $where .= " AND ";
            $where .= "CASE";
//            $where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." )";
            $where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." )";
//            $where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." )";
            $where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." )";
            $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Userid3",
                                "value" =>$userid); 
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_FOLLOWINGS ){
            if($where != '') $where .= " AND ";
            $where .= "CASE";
//            $where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.user_id=NF.user_id AND FL.subscriber_id='$userid')";
            $where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4)";
//            $where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.user_id=NF.user_id AND FL.subscriber_id='$userid')";
            $where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4)";
            $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Userid4",
                                "value" =>$userid); 
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_CHANNELS ){
            if($where != '') $where .= " AND ";
            $where .= "CASE";
//            $where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE ( (CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid='$userid' LIMIT 1 )) OR (CHA.id=NF.user_id AND CHA.owner_id<>NF.user_id AND (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.") ) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.user_id AND F.published=1 AND F.userid='$userid' LIMIT 1 ) ) )";
            $where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE ( (CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid5 LIMIT 1 )) OR (CHA.id=NF.user_id AND CHA.owner_id<>NF.user_id AND (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.") ) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.user_id AND F.published=1 AND F.userid=:Userid5 LIMIT 1 ) ) )";
            $where .= " ELSE 0 END ";
            $params[] = array( "key" => ":Userid5",
                                "value" =>$userid); 
	}
	if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TCHANNELS ){
		if($where != '') $where .= " AND ";
		$where .= "COALESCE(NF.channel_id, 0) ";
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TTPAGE ){
		if($where != '') $where .= " AND ";
		$where .= "COALESCE(NF.channel_id, 0) = 0 ";
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_THOTELS ){
		if($where != '') $where .= " AND ";
		$where .= "NF.entity_type=".SOCIAL_ENTITY_BAG." ";
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TECHOES ){
		if($where != '') $where .= " AND ";
		$where .= "NF.entity_type=".SOCIAL_ENTITY_FLASH." ";
	}
	
	if($where != '') $where .= " AND";	
	$where .= " CASE";
	$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_MEDIA." THEN EXISTS ( SELECT id FROM cms_videos VV WHERE VV.id=NF.entity_id AND VV.published=1";
	if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
		$where .= " AND image_video=:Media_type )";
                $params[] = array( "key" => ":Media_type",
                                    "value" =>$options['media_type']); 								
	}else{
		$where .= " )";
	}
	$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_ALBUM." THEN EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) )";
	$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_COMMENT." THEN 0";
	$where .= " ELSE 1 END ";
	
	if( intval($options['userid']) !=0 ){		
		if($where != '') $where .= " AND ";
		$where .= " CASE";
//                $where .= " WHEN NF.user_id='{$options['userid']}' AND NF.feed_privacy=0 AND NF.owner_id <> '{$options['userid']}' AND NF.action_type =".SOCIAL_ACTION_SHARE." THEN NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user='{$options['userid']}')";
                $where .= " WHEN NF.user_id=:Userid6 AND NF.feed_privacy=0 AND NF.owner_id <> :Userid6 AND NF.action_type =".SOCIAL_ACTION_SHARE." THEN NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user=:Userid6)";
                $where .= " WHEN NF.feed_privacy=0 THEN 0";
                $where .= " WHEN NF.action_type =".SOCIAL_ACTION_SHARE." THEN 0";
//		$where .= " WHEN NF.user_id<>'{$options['userid']}' THEN 1";     
		$where .= " WHEN NF.user_id<>:Userid6 THEN 1";              
//		$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." THEN EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 AND ((PO.from_id<>0 AND PO.from_id<>".$options['userid'].") OR (PO.from_id=0 AND PO.user_id<>".$options['userid'].")) )";
		$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." THEN EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 AND ((PO.from_id<>0 AND PO.from_id<>:Userid6) OR (PO.from_id=0 AND PO.user_id<>:Userid6)) )";
		$where .= " ELSE 0 END ";     
                $params[] = array( "key" => ":Userid6",
                                    "value" =>$options['userid']);            
	}
	
	if( intval($options['userid']) !=0 ){
//		$check_us_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id='{$options['userid']}' AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND show_from_tuber=0 AND published='1')";
		$check_us_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid7 AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND show_from_tuber=0 AND published='1')";
//		$check_ch_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id='{$options['userid']}' AND receiver_id=NF.channel_id AND is_channel=1 AND poster_is_channel=0 AND show_from_tuber=0 AND published='1')";
		$check_ch_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid7 AND receiver_id=NF.channel_id AND is_channel=1 AND poster_is_channel=0 AND show_from_tuber=0 AND published='1')";
//		$check_sp_ch_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id='{$options['userid']}' AND receiver_id=NF.user_id AND is_channel=1 AND poster_is_channel=0 AND show_from_tuber=0 AND published='1')";	
		$check_sp_ch_notify = "NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid7 AND receiver_id=NF.user_id AND is_channel=1 AND poster_is_channel=0 AND show_from_tuber=0 AND published='1')";		
		if($where != '') $where .= " AND ";
		$where .= " CASE";
		$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN ".$check_us_notify;
		$where .= " WHEN COALESCE(NF.channel_id, 0) AND (NF.action_type =".SOCIAL_ACTION_SPONSOR." OR NF.action_type =".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type =".SOCIAL_ACTION_RELATION_PARENT.") THEN ".$check_sp_ch_notify;
		$where .= " WHEN COALESCE(NF.channel_id, 0) AND NF.action_type <>".SOCIAL_ACTION_SPONSOR." AND NF.action_type <>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type <>".SOCIAL_ACTION_RELATION_PARENT." THEN ( (EXISTS (SELECT id FROM cms_channel AS CH WHERE CH.id=NF.channel_id AND CH.owner_id<>NF.user_id AND CH.published='1') AND ".$check_us_notify." ) OR (EXISTS (SELECT id FROM cms_channel AS CH WHERE CH.id=NF.channel_id AND CH.owner_id=NF.user_id AND CH.published='1') AND ".$check_ch_notify." ) )";
		$where .= " ELSE 0 END ";
                $params[] = array( "key" => ":Userid7",
                                    "value" =>$options['userid']); 
	}
	
	if($where != '') $where = " WHERE $where ";
        $ret = array();
        $ret = array("where"  =>$where,
                     "params" =>$params );
//        return $where;
        return $ret;
//  Changed by Anthony Malak 09-05-2015 to PDO database

}
function newsfeedLogSearch($srch_options) {
//  Changed by Anthony Malak 09-05-2015 to PDO database

	global $dbConn;
	$params  = array();  
	$params2 = array();  

	$default_opts = array(
		'limit' => 10,
		'page' => 0,
		'orderby' => 'id',
		'order' => 'a',
		'entity_type' => null,
		'entity_group' => null,
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
		'n_results' => false,
	);

	$options = array_merge($default_opts, $srch_options);
	
	//in case user_id is set we want the user news feed
	//in case channel_id is set we want the channel notifications
	$nlimit ='';
	$where ='';
	if( $options['limit'] ){
		$nlimit = intval($options['limit']);
		$skip = intval($options['page']) * $nlimit;
	}

	$orderby = $options['orderby'];
	$order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
	
	$userid = userGetID();
	if( isset($userid) && $userid>0 && !$options['channel_id'] ){
		$friends = userGetFreindList($userid);
		$friends_ids = array($userid);
		foreach($friends as $freind){
		    $friends_ids[] = $freind['id'];
		}
		if(count($friends_ids)!=0){
			if($where != '') $where .= " AND ";
			$public = USER_PRIVACY_PUBLIC;
			$private = USER_PRIVACY_PRIVATE;
			$selected = USER_PRIVACY_SELECTED;
			$community = USER_PRIVACY_COMMUNITY;
			$privacy_where = '';

			$where .= "CASE";
			$where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1  LIMIT 1 ) THEN 1";
//                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.user_id = '$userid' LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) THEN 1";
//                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
                        
//                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$userid' LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." THEN 0";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1  LIMIT 1 ) THEN 1";
//                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.user_id = '$userid' LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) THEN 1";
//                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." THEN 0";
			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1  LIMIT 1 ) THEN 1";
			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) THEN 1";
			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
			
//			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$userid' LIMIT 1 ) THEN 1";
			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) THEN 1";
                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
                        
			$where .= " ELSE 0 END ";
                        $params[] = array( "key" => ":Userid", "value" =>$userid);
                        $params[] = array( "key" => ":Public", "value" =>$public);                        
                        $params[] = array( "key" => ":Private", "value" =>$private);
		}	
	}
	if( $options['userid'] ){
		if($where != '') $where .= " AND ";
//		$where .= " NF.user_id = {$options['userid']} ";
		$where .= " NF.user_id = :Userid2 ";
                $params[] = array( "key" => ":Userid2",
                                    "value" =>$options['userid']);
	}
	if( $options['from_filter'] && intval($options['from_filter'])== FROM_FRIENDS ){        
		if($where != '') $where .= " AND ";
		$where .= "CASE";
//		$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1)";
		$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1)";
		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1)";
		$where .= " ELSE 0 END ";
                $params[] = array( "key" => ":Userid3",
                                    "value" =>$options['userid']);
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_FOLLOWINGS ){
        if($where != '') $where .= " AND ";
		$where .= "CASE";
//		$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.user_id=NF.user_id AND FL.subscriber_id='$userid')";
		$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4)";
//		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.user_id=NF.user_id AND FL.subscriber_id='$userid')";
		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4)";
		$where .= " ELSE 0 END ";
                $params[] = array( "key" => ":Userid4",
                                    "value" =>$options['userid']);
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_CHANNELS ){
        if($where != '') $where .= " AND ";
		$where .= "CASE";
//		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid='$userid' LIMIT 1 )";
		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid5 LIMIT 1 )";
		$where .= " ELSE 0 END ";
                $params[] = array( "key" => ":Userid5",
                                    "value" =>$options['userid']);
	}
        if( $options['channel_id'] && $options['channel_id'] !=-1 ){
            $channelInfo=channelGetInfo($options['channel_id']);
            $owner_id= intval($channelInfo['owner_id']);
        }else{
            $owner_id=intval($options['userid']);
        }
	if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TCHANNELS ){
            if($where != '') $where .= " AND ";
            if( $options['channel_id'] ){
//                $where .= "NF.channel_id =$options['channel_id'] AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = ':Owner_id' ";
                $where .= "NF.channel_id =:Channel_id AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Owner_id ";
                $params[] = array( "key" => ":Channel_id",
                                    "value" =>$options['channel_id']);
                $params[] = array( "key" => ":Owner_id",
                                    "value" =>$owner_id);
            }else if( $options['userid'] ){
                $where .= "( COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy =".USER_PRIVACY_PRIVATE." ) ";
            }else{
                $where .= "COALESCE(NF.channel_id, 0) ";
            }
            if( $options['action_type'] ){
                if( $where != '') $where .= ' AND ';
                $where .= " NF.entity_type<>".SOCIAL_ENTITY_COMMENT." ";		
            }		
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TTPAGE ){
            if($where != '') $where .= " AND ";
            $where .= "NF.entity_type<>".SOCIAL_ENTITY_BAG." AND ";
            if( $options['owner_id'] ){
//                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = '{$options['owner_id']}' ";
                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Owner_id2 ";
                $params[] = array( "key" => ":Owner_id2",
                                    "value" =>$options['owner_id']);
            }else if( $options['userid'] ){
//                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = '{$options['userid']}' ";
                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Userid6 ";
                $params[] = array( "key" => ":Userid6",
                                    "value" =>$options['userid']);
            }else{
                $where .= "COALESCE(NF.channel_id, 0) = 0 ";
            }
            if( $options['action_type'] ){
                if( $where != '') $where .= ' AND ';
                $where .= " NF.entity_type<>".SOCIAL_ENTITY_COMMENT." ";		
            }
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TTPAGE_OTHER ){
            if($where != '') $where .= " AND ";
            if( $options['userid'] ){
//                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id <> '{$options['userid']}'";
                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id <> :Userid7";
                $params[] = array( "key" => ":Userid7",
                                    "value" =>$options['userid']);
            }
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_THOTELS ){
            if($where != '') $where .= " AND ";
            $where .= "NF.entity_type=".SOCIAL_ENTITY_BAG." ";
	}
	if( $options['search_string'] && (strlen($options['search_string'])!=0) ){
//		if($where != '') $where .= " AND ( ( U.display_fullname = 0 AND LOWER(YourUserName) LIKE '{$options['search_string']}%' ) OR ( U.display_fullname = 1 AND LOWER(FullName) LIKE '%{$options['search_string']}%' ) )";	
		if($where != '') $where .= " AND ( ( U.display_fullname = 0 AND LOWER(YourUserName) LIKE :Search_string ) OR ( U.display_fullname = 1 AND LOWER(FullName) LIKE :Search_string2 ) )";	
                $params[] = array( "key" => ":Search_string",
                                    "value" =>$options['search_string']."%");
                $params[] = array( "key" => ":Search_string2",
                                    "value" =>"%".$options['search_string']."%");
                
	}
    if( $options['feed_privacy'] ){
        if($where != '') $where .= " AND ";
        if( intval($options['feed_privacy'])==-1){
            $where .= " NF.feed_privacy<>".USER_PRIVACY_PRIVATE."";
        }else{
//            $where .= " NF.feed_privacy='{$options['feed_privacy']}' ";
            $where .= " NF.feed_privacy=:Feed_privacy ";
            $params[] = array( "key" => ":Feed_privacy",
                                "value" =>$options['feed_privacy']);
        }
    }
    if(!is_null($options['published'])){
        if($where != '') $where .= " AND ";
//        $where .= " NF.published IN ( {$options['published']} ) ";
        $where .= " find_in_set(cast(NF.published as char), :Published) ";
        $params[] = array( "key" => ":Published", "value" =>$options['published']);
    }
    if( $options['is_visible'] != -1 ){
        if($options['is_visible'] == 1 && intval($options['userid']) !=0 ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid8)";
            $params[] = array( "key" => ":Userid8",
                                "value" =>$options['userid']);
        }else if($options['is_visible'] == 1 && $options['channel_id'] && intval($options['channel_id']) > 0 ){            
            if( $where != '') $where .= ' AND ';
//            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='$owner_id')";
            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id3)";
            $params[] = array( "key" => ":Owner_id3",
                                "value" =>$owner_id);
        }else if($options['is_visible'] == 0 && intval($options['userid']) !=0 ){
            if( $where != '') $where .= ' AND ';
//            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid9)";
            $params[] = array( "key" => ":Userid9",
                                "value" =>$options['userid']);
        }else if($options['is_visible'] == 0 && $options['channel_id'] && intval($options['channel_id']) > 0 ){            
            if( $where != '') $where .= ' AND ';
//            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='$owner_id')";
            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id4)";
            $params[] = array( "key" => ":Owner_id4",
                                "value" =>$owner_id);
        }
    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
//        $where .= " NF.entity_type='{$options['entity_type']}' ";
        $where .= " NF.entity_type=:Entity_type ";	
        $params[] = array( "key" => ":Entity_type",
                            "value" =>$options['entity_type']);	
    }else{
        if($where != '') $where .= " AND ";
        global $CONFIG_EXEPT_ARRAY;
        $exept_array = $CONFIG_EXEPT_ARRAY;
        $where .= " NF.entity_type NOT IN(". implode(',', $exept_array) .") ";        
    }
    if( $options['entity_id'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NF.entity_id='{$options['entity_id']}' ";
            $where .= " NF.entity_id=:Entity_id ";
            $params[] = array( "key" => ":Entity_id",
                                "value" =>$options['entity_id'] );		
    }
    if( $options['entity_group'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NF.entity_group='{$options['entity_group']}' ";	
            $where .= " NF.entity_group=:Entity_group ";	
            $params[] = array( "key" => ":Entity_group",
                                "value" =>$options['entity_group'] );	
    }
    if( $options['action_type'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NF.action_type='{$options['action_type']}' ";
            $where .= " NF.action_type=:Action_type ";
            $params[] = array( "key" => ":Action_type",
                                "value" =>$options['action_type'] );	
    }else {
            if($where != '') $where .= " AND ";
            $where .= " NF.action_type<>".SOCIAL_ACTION_UNFRIEND." AND NF.action_type<>".SOCIAL_ACTION_UNFOLLOW." ";
    }
    if($options['from_ts'] || $options['to_ts']){			
        if($options['from_ts']){
            if( $where != '') $where .= " AND ";
//            $where .= " DATE(NF.feed_ts) >= '{$options['from_ts']}' ";
            $where .= " DATE(NF.feed_ts) >= :From_ts ";
            $params[] = array( "key" => ":From_ts",
                                "value" =>$options['from_ts'] );	
        }
        if($options['to_ts']){
            if( $where != '') $where .= " AND ";
//            $where .= " DATE(NF.feed_ts) <= '{$options['to_ts']}' ";
            $where .= " DATE(NF.feed_ts) <= :To_ts ";
            $params[] = array( "key" => ":To_ts",
                                "value" =>$options['to_ts'] );
        }
    }		
    if($options['from_time'] || $options['to_time']){			
        if($options['from_time']){
            if( $where != '') $where .= " AND ";
//            $where .= " (NF.feed_ts) >= '{$options['from_time']}' ";
            $where .= " (NF.feed_ts) >= :From_time ";
            $params[] = array( "key" => ":From_time",
                                "value" =>$options['from_time'] );
        }
        if($options['to_time']){
            if( $where != '') $where .= " AND ";
//            $where .= " (NF.feed_ts) <= '{$options['to_time']}' ";
            $where .= " (NF.feed_ts) <= :To_time ";
            $params[] = array( "key" => ":To_time",
                                "value" =>$options['to_time'] );
        }
    }
	
    if($where != '') $where .= " AND ";
    $where .= "CASE";
    $where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_MEDIA." THEN EXISTS ( SELECT id FROM cms_videos AS VV WHERE VV.id=NF.entity_id AND VV.published=1";
    if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
//            $where .= " AND image_video='{$options['media_type']}' )";	
            $where .= " AND image_video=:Media_type )";	
            $params[] = array( "key" => ":Media_type",
                                "value" =>$options['media_type'] );							
    }else{
            $where .= " )";
    }
    $where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_POST." THEN EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 )";
    $where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_ALBUM." THEN EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) )";
    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_EVENT_CANCEL." THEN NF.feed_privacy<>".USER_PRIVACY_PRIVATE."";
    $where .= " ELSE 1 END ";
    
    if( intval($options['userid']) !=0 ){
        if($where != '') $where .= " AND ";
        $where .= " NF.user_id=:Owner_id5";  
        $params[] = array( "key" => ":Owner_id5",
                            "value" =>$owner_id );	
    }		
	
    if( !$options['channel_id'] ){
        if($where != '') $where .= " AND ";
        $where .= " COALESCE(NF.channel_id, 0) = 0 ";
        if( intval($options['userid']) !=0){
//            $where .= "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id='{$options['userid']}' AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND notify=0 AND published='1') ";
            $where .= "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid10 AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND notify=0 AND published='1') ";
        $params[] = array( "key" => ":Userid10",
                            "value" =>$options['userid'] );
        }
        if($where != '') $where .= " AND ";                
        $where .= "CASE";
        $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." THEN 1";
        $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy=".USER_PRIVACY_PRIVATE." THEN EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id)";
        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT." THEN 0";
        $where .= " ELSE 1 END ";
    }else if ($options['channel_id'] != -1){
        if($where != '') $where .= " AND "; 
        $myChannelId = intval($options['channel_id']);
        $channel_sub_array = getSubChannelRelationList($myChannelId,'1');
        $channel_parent_array = getParentChannelRelationList($myChannelId,'1');
        if($channel_sub_array!=''){
            $myChannelId.=',';
            $myChannelId.=$channel_sub_array;
        } 
        if($channel_parent_array!=''){
            $myChannelId.=',';
            $myChannelId.=$channel_parent_array;
        }
        $where .= "CASE";
        $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." AND NF.user_id IN($myChannelId)  THEN 1";
        
        $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." AND NF.channel_id IN($myChannelId) AND NF.user_id=:Owner_id THEN 1";
        
        $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.channel_id IN($myChannelId) AND NF.user_id=:Owner_id AND NF.feed_privacy=".USER_PRIVACY_PRIVATE." THEN EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id)";
        
        $where .= " WHEN NF.channel_id IN($myChannelId) THEN EXISTS (SELECT id FROM cms_channel AS CM WHERE CM.id=NF.channel_id AND CM.owner_id=NF.user_id)";
        
        $params[] = array( "key" => ":Owner_id", "value" =>$owner_id );
        if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TCHANNELS ){
            $where .= " ELSE 1 END ";
        }else{
            $where .= " ELSE 0 END "; 
        }
    }else{
        if( $where != '') $where .= ' AND ';
        $where .= " COALESCE(NF.channel_id, 0) ";
    }
	
    if( intval($options['userid']) !=0 ){
            if( $where != '') $where .= " AND ";
            $where .= "CASE";
//            $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." THEN NF.user_id='{$options['userid']}' AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id='{$options['userid']}') ";
            $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." THEN NF.user_id=:Userid11 AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id=:Userid11) ";
            $where .= " ELSE 1 END";
            $params[] = array( "key" => ":Userid11",
                                "value" =>$options['userid'] );
    }
    $where .= " AND NF.action_type <>".SOCIAL_ACTION_REECHOE." ";
    if($where != '') $where = " WHERE $where ";
	
	if(!$options['n_results']){
		
                $query = "SELECT
                        NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender
                FROM
                        `cms_social_newsfeed` AS NF
                        LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT."
                $where ORDER BY $orderby $order";
		if( $options['limit'] ){
                    $query .= " LIMIT :Skip, :Nlimit";
                    $params[] = array( "key" => ":Skip", "value" =>$skip , "type" =>"::PARAM_INT" );
                    $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT" );
		}
                
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
		
		///////////////////////////////
		//get arrays of all the entities to fetch them one.
		//TODO repalace with the cached get functions such as getVideoInfo(), userCatalogGet(), etc ...
		
		$feed = array();
//		while($row = db_fetch_assoc($ret)){
                $row = $select->fetchAll(PDO::FETCH_ASSOC);                
                foreach($row as $row_item){ 
                    $feed_row = $row_item;			
                    switch( $feed_row['action_type'] ){
                        case SOCIAL_ACTION_COMMENT:
                            $feed_row['action_row'] = socialCommentRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_LIKE:
                            $feed_row['action_row'] = socialLikeRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_RATE:
                            $feed_row['action_row'] = socialRateRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_INVITE:
                        case SOCIAL_ACTION_SHARE:
                                $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                                break;
                        case SOCIAL_ACTION_REECHOE:
                            $feed_row['action_row'] = socialReechoeRow($feed_row['action_id']);                            
                        break;
                        case SOCIAL_ACTION_RELATION_PARENT:
                        case SOCIAL_ACTION_RELATION_SUB:
                            $feed_row['action_row'] = channelRelationRow($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                        break;
                        case SOCIAL_ACTION_UPLOAD:
                            $feed_row['action_row'] = array();
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];                            
                        break;
                        case SOCIAL_ACTION_SPONSOR:
                            //in case of sponsor newsfeed.user_id is actaully the channel_id
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                            $feed_row['action_row'] = socialShareGet($feed_row['action_id']);

                            $sp_info = socialShareGet($feed_row['action_id']);
                            $feed_row['action_row']['msg'] = $sp_info['msg'];

                            //we dont have user info
                            unset($feed_row['YourUserName']);
                            unset($feed_row['FullName']);
                            unset($feed_row['display_fullname']);
                            unset($feed_row['profile_Pic']);
                            unset($feed_row['gender']);
                        break;
                        case SOCIAL_ACTION_EVENT_JOIN:
                            $feed_row['join_row'] = joinEventInfo($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                        case SOCIAL_ACTION_FRIEND:
                        case SOCIAL_ACTION_FOLLOW:
                            $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];		
                        break;
                        default:
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                    }
			
                    if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM ){
                        //in case album
                        $catalog_row = userCatalogDefaultMediaGet($feed_row['action_row']['entity_id']);
                        if(!$catalog_row) $catalog_row = array();
                        $feed_row['media_row'] = $catalog_row;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                    }else if( $feed_row['action_type'] == SOCIAL_ACTION_LIKE && $feed_row['entity_type'] == SOCIAL_ENTITY_COMMENT ){
                        //in case like a comment
                        $cr = socialCommentRow($feed_row['entity_id']);
                        $feed_row['media_row'] = socialEntityInfo($cr['entity_type'], $cr['entity_id']);

                        $feed_row['original_media_row'] = $cr;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                        $feed_row['entity_type'] = $cr['entity_type'];
                        $feed_row['entity_id'] = $cr['entity_id'];
                    }else if( ($feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME) &&  !$feed_row['channel_id'] ){
                            $feed_row['media_row'] = socialEntityInfo(SOCIAL_ENTITY_USER_EVENTS, $feed_row['action_row']['entity_id']);
                    }else if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
                            //$feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['channel_id']);
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_id']);
                            }else{
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_row']['entity_id']);
                            }
                    }else {
                            //just get the media row
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['action_row']['entity_id'] = $feed_row['action_id'];
                            }
                            $feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['entity_id']);
                            if( $feed_row['entity_type'] == SOCIAL_ENTITY_VISITED_PLACES ){
                                $stateinfo = worldStateInfo($feed_row['media_row']['country_code'],$feed_row['media_row']['state_code']);
                                $state_name = (!$stateinfo)? '' : $stateinfo['state_name'];
                                $country_name= countryGetName($feed_row['media_row']['country_code']);
                                $country_name = (!$country_name)? '' : $country_name;
                                $feed_row['media_row']['state_name']=$state_name;
                                $feed_row['media_row']['country_name']=$country_name;
                            }
                    }
			
                    ///////////////////////
                    //in case no profile pic and not the channel sponsor action
                    if( ( !isset($feed_row['profile_Pic']) || $feed_row['profile_Pic'] == '' ) && $feed_row['action_type'] != SOCIAL_ACTION_SPONSOR && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_SUB && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_PARENT ){
                        $feed_row['profile_Pic'] = 'he.jpg';
                        if($feed_row['gender']=='F'){
                            $feed_row['profile_Pic'] = 'she.jpg';
                        }
                    }
			
			$feed[] = $feed_row;
		}
		
		return $feed;
		
	// Case of returning n_results.
	} else {
		$query = "SELECT count(NF.id) FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";
		
                //return $query;
//		$ret = db_query($query);
//		$row = db_fetch_row($ret);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();
                $row = $select->fetch();
		
		return $row[0];
	}
//  Changed by Anthony Malak 09-05-2015 to PDO database

}
function newsfeedGroupingLogSearch($srch_options) {
    return newsfeedGroupingLogSearchNew($srch_options);

	global $dbConn;
	$params  = array();  
	$params2 = array();  

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
	  
        
	//in case user_id is set we want the user news feed
	//in case channel_id is set we want the channel notifications
	$nlimit ='';
	$where ='';
	if( $options['limit'] ){
		$nlimit = intval($options['limit']);
		$skip = intval($options['page']) * $nlimit;
	}

	$orderby = $options['orderby'];
	$order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
	if( $options['owner_id'] ){
           $userid = intval($options['owner_id']); 
        }else{
            $userid = userGetID();
        }	
	if( isset($userid) && $userid>0 && !$options['channel_id'] ){			
		$friends = userGetFreindList($userid);
		$friends_ids = array($userid);
		foreach($friends as $freind){
			$friends_ids[] = $freind['id'];
		}
		if(count($friends_ids)!=0){
			if($where != '') $where .= " AND ";
			$public = USER_PRIVACY_PUBLIC;
			$private = USER_PRIVACY_PRIVATE;
			$selected = USER_PRIVACY_SELECTED;
			$community = USER_PRIVACY_COMMUNITY;
			$privacy_where = '';

			$where .= "CASE";
			$where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1  LIMIT 1 ) THEN 1";
			
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) THEN 1";
			
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FRIEND." THEN 0";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1  LIMIT 1 ) THEN 1";
			
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) THEN 1";
			
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
                        			
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) THEN 1";
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
                        
                        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_FOLLOW." THEN 0";
//			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='$public' LIMIT 1 ) THEN 1";
			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) THEN 1";
                        $where .= " WHEN NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1  LIMIT 1 ) THEN 1";
//			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.user_id = '$userid' LIMIT 1 ) THEN 1";
			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) THEN 1";
			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) THEN 1";
			
//			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='$private' AND PR.user_id='$userid' LIMIT 1 ) THEN 1";
			$where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) THEN 1";
                        $where .= " WHEN EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) THEN 1";
                        
			$where .= " ELSE 0 END ";
                        $params[] = array( "key" => ":Userid", "value" =>$userid);
                        $params[] = array( "key" => ":Public", "value" =>$public);                        
                        $params[] = array( "key" => ":Private", "value" =>$private);
		}	
	}
	if( $options['userid'] ){
		if($where != '') $where .= " AND ";
		$where .= " CASE";
//                $where .= " WHEN NF.user_id = '{$options['userid'])}' THEN 1";
                $where .= " WHEN NF.user_id = :Userid2 THEN 1";
//                $where .= " WHEN NF.user_id<>'{$options['userid']}' AND COALESCE(NF.channel_id, 0) = 0 AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.user_id=NF.owner_id AND EXISTS (SELECT p.id FROM cms_social_posts as p WHERE p.from_id=NF.user_id AND p.user_id = '{$options['userid']}' AND p.id=NF.entity_id) THEN 1";
                $where .= " WHEN NF.user_id<>:Userid2 AND COALESCE(NF.channel_id, 0) = 0 AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.user_id=NF.owner_id AND EXISTS (SELECT p.id FROM cms_social_posts as p WHERE p.from_id=NF.user_id AND p.user_id = :Userid2 AND p.id=NF.entity_id) AND action_type=".SOCIAL_ACTION_UPLOAD." THEN 1";
		$where .= " ELSE 0 END "; 
                $params[] = array( "key" => ":Userid2", "value" =>$options['userid']);
	}
	if( $options['from_filter'] && intval($options['from_filter'])== FROM_FRIENDS ){
		if($where != '') $where .= " AND ";
		$where .= "CASE";
//		$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1)";
		$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1)";
//		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1)";
		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1)";
		$where .= " ELSE 0 END ";
                $params[] = array( "key" => ":Userid3",
                                    "value" =>$userid);
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_FOLLOWINGS ){
        if($where != '') $where .= " AND ";
		$where .= "CASE";
//		$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.user_id=NF.user_id AND FL.subscriber_id='$userid')";
		$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4)";
//		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.user_id=NF.user_id AND FL.subscriber_id='$userid')";
		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4)";
		$where .= " ELSE 0 END ";
                $params[] = array( "key" => ":Userid4",
                                    "value" =>$userid);
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_CHANNELS ){
        if($where != '') $where .= " AND ";
		$where .= "CASE";
//		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid='$userid' LIMIT 1 )";
		$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid5 LIMIT 1 )";
		$where .= " ELSE 0 END ";
                $params[] = array( "key" => ":Userid5",
                                    "value" =>$options['userid']);
	}else if( !$options['from_filter'] ){
            
        }
        if( $options['channel_id'] && $options['channel_id'] !=-1 ){
            $channelInfo=channelGetInfo($options['channel_id']);
            $owner_id= intval($channelInfo['owner_id']);
        }else{
            $owner_id=intval($options['userid']);
        }
	if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TCHANNELS ){
            if($where != '') $where .= " AND ";
            if( $options['channel_id'] ){
//                $where .= "NF.channel_id ='{$options['channel_id']}' AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = '$owner_id' ";
                $where .= "NF.channel_id ='{$options['channel_id']}' AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Owner_id ";
                $params[] = array( "key" => ":Owner_id",
                                    "value" =>$owner_id);
            }else if( $options['userid'] ){
                $where .= "( COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy =".USER_PRIVACY_PRIVATE." ) ";
            }else{
                $where .= "COALESCE(NF.channel_id, 0) ";
            }
            if( $options['action_type'] ){
                if( $where != '') $where .= ' AND ';
                $where .= " NF.entity_type<>".SOCIAL_ENTITY_COMMENT." ";		
            }		
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TTPAGE ){
            if($where != '') $where .= " AND ";
            $where .= "NF.entity_type<>".SOCIAL_ENTITY_BAG." AND ";
            if( $options['owner_id'] ){
                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Owner_id2 ";
                $params[] = array( "key" => ":Owner_id2", "value" =>$options['owner_id']);
            }else if( $options['userid'] ){
//                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = '{$options['userid']}' ";
                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Userid6 ";
                $params[] = array( "key" => ":Userid6",
                                    "value" =>$options['userid']);
            }else{
                $where .= "COALESCE(NF.channel_id, 0) = 0 ";
            }
            if( $options['action_type'] ){
                if( $where != '') $where .= ' AND ';
                $where .= " NF.entity_type<>".SOCIAL_ENTITY_COMMENT." ";		
            }
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TTPAGE_OTHER ){
            if($where != '') $where .= " AND ";
            if( $options['userid'] ){
//                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id <> '{$options['userid']}'";
                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id <> :Userid7";
                $params[] = array( "key" => ":Userid7",
                                    "value" =>$options['userid']);
            }
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_THOTELS ){
            if($where != '') $where .= " AND ";
            $where .= "NF.entity_type=".SOCIAL_ENTITY_BAG." ";
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TECHOES ){
		if($where != '') $where .= " AND ";
		$where .= "NF.entity_type=".SOCIAL_ENTITY_FLASH." ";
	}
	if( $options['search_string'] && (strlen($options['search_string'])!=0) ){
//		if($where != '') $where .= " AND ( ( U.display_fullname = 0 AND LOWER(YourUserName) LIKE '{$options['search_string']}%' ) OR ( U.display_fullname = 1 AND LOWER(FullName) LIKE '%{$options['search_string']}%' ) )";		
		if($where != '') $where .= " AND ( ( U.display_fullname = 0 AND LOWER(YourUserName) LIKE :Search_string ) OR ( U.display_fullname = 1 AND LOWER(FullName) LIKE :Search_string2 ) )";		
                $params[] = array( "key" => ":Search_string",
                                    "value" =>$options['search_string']."%");
                $params[] = array( "key" => ":Search_string2",
                                    "value" =>"%".$options['search_string']."%");
	}
    if( $options['feed_privacy'] ){
        if($where != '') $where .= " AND ";
        if( intval($options['feed_privacy'])==-1){
            $where .= " NF.feed_privacy<>".USER_PRIVACY_PRIVATE."";
        }else{
//            $where .= " NF.feed_privacy='{$options['feed_privacy']}' ";
            $where .= " NF.feed_privacy=:Feed_privacy ";
            $params[] = array( "key" => ":Feed_privacy",
                                "value" =>$options['feed_privacy']);
        }
    }
    if(!is_null($options['published'])){
            if($where != '') $where .= " AND ";
//            $where .= " NF.published IN ( {$options['published']} ) ";
            $where .= " find_in_set(cast(NF.published as char), :Published) ";
            $params[] = array( "key" => ":Published", "value" =>$options['published']);
    }
    if( $options['is_visible'] != -1 ){
        if($options['is_visible'] == 1 && intval($options['userid']) !=0 ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid8)";
            $params[] = array( "key" => ":Userid8",
                                "value" =>$options['userid']);
        }else if($options['is_visible'] == 1 && $options['channel_id'] && intval($options['channel_id']) > 0 ){            
            if( $where != '') $where .= ' AND ';
//            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='$owner_id')";
            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id4)";
            $params[] = array( "key" => ":Owner_id4",
                                "value" =>$owner_id);
        }else if($options['is_visible'] == 0 && intval($options['userid']) !=0 ){
            if( $where != '') $where .= ' AND ';
            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
        }else if($options['is_visible'] == 0 && $options['channel_id'] && intval($options['channel_id']) > 0 ){            
            if( $where != '') $where .= ' AND ';
//            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='$owner_id')";
            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id5)";
            $params[] = array( "key" => ":Owner_id5",
                                "value" =>$owner_id);
        }
    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " NF.entity_type='{$options['entity_type']}' ";		
    }else{
        if($where != '') $where .= " AND ";
        global $CONFIG_EXEPT_ARRAY;
        $exept_array = $CONFIG_EXEPT_ARRAY;
       $where .= " NF.entity_type NOT IN(". implode(',', $exept_array) .") ";        
    }
    if( $options['entity_id'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NF.entity_id='{$options['entity_id']}' ";
            $where .= " NF.entity_id=:Entity_id ";
            $params[] = array( "key" => ":Entity_id",
                                "value" =>$options['entity_id']);		
    }
    if( $options['action_type'] ){
            if( $where != '') $where .= ' AND ';
//            $where .= " NF.action_type='{$options['action_type']}' ";
            $where .= " NF.action_type=:Action_type ";
            $params[] = array( "key" => ":Action_type",
                                "value" =>$options['action_type']);
    }else {
            if($where != '') $where .= " AND ";
            $where .= " NF.action_type<>".SOCIAL_ACTION_UNFRIEND." AND NF.action_type<>".SOCIAL_ACTION_UNFOLLOW." ";
    }
    if($options['from_ts'] || $options['to_ts']){			
        if($options['from_ts']){
            if( $where != '') $where .= " AND ";
//            $where .= " DATE(NF.feed_ts) >= '{$options['from_ts']}' ";
            $where .= " DATE(NF.feed_ts) >= :From_ts ";
            $params[] = array( "key" => ":From_ts",
                                "value" =>$options['from_ts']);
        }
        if($options['to_ts']){
            if( $where != '') $where .= " AND ";
//            $where .= " DATE(NF.feed_ts) <= '{$options['to_ts']}' ";
            $where .= " DATE(NF.feed_ts) <= :To_ts ";
            $params[] = array( "key" => ":To_ts",
                                "value" =>$options['to_ts']);
        }
    }		
    if($options['from_time'] || $options['to_time']){			
        if($options['from_time']){
            if( $where != '') $where .= " AND ";
//            $where .= " (NF.feed_ts) >= '{$options['from_time']}' ";
            $where .= " (NF.feed_ts) >= :From_time ";
            $params[] = array( "key" => ":From_time",
                                "value" =>$options['from_time']);
        }
        if($options['to_time']){
            if( $where != '') $where .= " AND ";
//            $where .= " (NF.feed_ts) <= '{$options['to_time']}' ";
            $where .= " (NF.feed_ts) <= :To_time ";
            $params[] = array( "key" => ":To_time",
                                "value" =>$options['to_time']);
        }
    }
	
    if($where != '') $where .= " AND ";
    $where .= "CASE";
    $where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_MEDIA." THEN EXISTS ( SELECT id FROM cms_videos AS VV WHERE VV.id=NF.entity_id AND VV.published=1";
    if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
//            $where .= " AND image_video='{$options['media_type']}' )";
            $where .= " AND image_video=:Media_type )";
            $params[] = array( "key" => ":Media_type",
                                "value" =>$options['media_type']);								
    }else{
            $where .= " )";
    }
    $where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_POST." THEN EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 )";
    $where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_ALBUM." THEN EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) )";
    $where .= " WHEN NF.action_type=".SOCIAL_ACTION_EVENT_CANCEL." THEN NF.feed_privacy<>".USER_PRIVACY_PRIVATE."";
    $where .= " ELSE 1 END ";
    
    if( !$options['channel_id'] ){
        if($where != '') $where .= " AND ";
        $where .= " COALESCE(NF.channel_id, 0) = 0 ";
        if( intval($options['userid']) !=0){
//            $where .= "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id='{$options['userid']}' AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND notify=0 AND published='1') ";
            $where .= "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid8 AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND notify=0 AND published='1') ";
            $params[] = array( "key" => ":Userid8",
                                "value" =>$options['userid']);
        }
        if($where != '') $where .= " AND ";                
        $where .= "CASE";
        $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." THEN EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id AND SHR.from_user=:Userid)";
        $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy=".USER_PRIVACY_PRIVATE." THEN EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id AND SHR.from_user=:Userid)";
        $where .= " WHEN NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT." THEN 0";
        $where .= " ELSE 1 END ";
    }else if ($options['channel_id'] != -1){
        if($where != '') $where .= " AND "; 
        $myChannelId = intval($options['channel_id']);
        $channel_sub_array = getSubChannelRelationList($myChannelId,'1');
        $channel_parent_array = getParentChannelRelationList($myChannelId,'1');
        if($channel_sub_array!=''){
            $myChannelId.=',';
            $myChannelId.=$channel_sub_array;
        } 
        if($channel_parent_array!=''){
            $myChannelId.=',';
            $myChannelId.=$channel_parent_array;
        }
        $where .= "CASE";
        $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." AND NF.user_id IN($myChannelId)  THEN 1";
        
        $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." AND NF.channel_id IN($myChannelId) AND NF.user_id=:Owner_id6 THEN 1";
        
        $where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.channel_id IN($myChannelId) AND NF.user_id=:Owner_id6 AND NF.feed_privacy=".USER_PRIVACY_PRIVATE." THEN EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id)";
        
        $where .= " WHEN NF.channel_id IN($myChannelId) THEN EXISTS (SELECT id FROM cms_channel AS CM WHERE CM.id=NF.channel_id AND CM.owner_id=NF.user_id)";
        
        $params[] = array( "key" => ":Owner_id6","value" =>$owner_id);
        if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TCHANNELS ){
            $where .= " ELSE 1 END ";
        }else{
            $where .= " ELSE 0 END "; 
        }
    }else{
        if( $where != '') $where .= ' AND ';
        $where .= " COALESCE(NF.channel_id, 0) ";
    }
	
    if( intval($options['userid']) !=0 ){
            if( $where != '') $where .= " AND ";
            $where .= "CASE";
//            $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." THEN NF.user_id='{$options['userid']}' AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id='{$options['userid']}') ";
            $where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." THEN NF.user_id=:Userid10 AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id=:Userid10) ";
            $params[] = array( "key" => ":Userid10",
                                "value" =>$options['userid']);
            $where .= " ELSE 1 END";
    }
    $where .= " AND NF.action_type <>".SOCIAL_ACTION_REECHOE." ";
    if($where != '') $where = " WHERE $where ";
	
	if(!$options['n_results']){
		if($orderby=='most_liked'){			
                   $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender, ( SELECT COUNT(SL.id) FROM cms_social_likes AS SL WHERE SL.entity_id=NF.entity_id AND SL.entity_type=NF.entity_type AND SL.like_value=1 AND SL.published=1 ) AS counter
                         FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";
                   $query1 = "( $query AND NF.action_type<>".SOCIAL_ACTION_UPLOAD." ) UNION ( select * from ($query AND NF.action_type=".SOCIAL_ACTION_UPLOAD." ORDER BY NF.id DESC) AS X GROUP BY X.action_type,X.entity_type,X.entity_group,X.feed_privacy)";
                    $query = "select * from ($query1) AS X1 ORDER BY X1.counter $order";
                }else{
                    $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender
                         FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";
                    
                    $query1 = "( $query AND NF.action_type<>".SOCIAL_ACTION_UPLOAD." ) UNION ( select * from ($query AND NF.action_type=".SOCIAL_ACTION_UPLOAD." ORDER BY NF.$orderby $order) AS X GROUP BY X.action_type,X.entity_type,X.entity_group,X.feed_privacy)";
                    $query = "select * from ($query1) AS X1 ORDER BY X1.$orderby $order";                    
                }
		if( $options['limit'] ){
//			$query .= " LIMIT $skip, $nlimit"; 
			$query .= " LIMIT :Skip, :Nlimit"; 
                        $params[] = array( "key" => ":Skip", "value" => $skip, "type" =>"::PARAM_INT");
                        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
		}
                //debug($query);
                //debug($params);exit;
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();

                $ret    = $select->rowCount();
		
		///////////////////////////////
		//get arrays of all the entities to fetch them one.
		//TODO repalace with the cached get functions such as getVideoInfo(), userCatalogGet(), etc ...
		
		$feed = array();
//		while($row = db_fetch_assoc($ret)){
                $row = $select->fetchAll(PDO::FETCH_ASSOC);
                foreach($row as $row_item){ 
                    $feed_row = $row_item;	
                    $feed_row['action_row_count'] =0;
                    $feed_row['action_row_other'] =array(); 
                    switch( $feed_row['action_type'] ){
                        case SOCIAL_ACTION_COMMENT:
                            $feed_row['action_row'] = socialCommentRow($feed_row['action_id']);
                            $channel_entity_type = $feed_row['action_row']['entity_type'];
                            if($channel_entity_type==SOCIAL_ENTITY_CHANNEL){
                                $channel_id = $feed_row['channel_id'];
                                $channelInfo = channelGetInfo($channel_id);
                                $feed_row['channel_row'] = $channelInfo;
                            }
                        break;
                        case SOCIAL_ACTION_LIKE:
                            $feed_row['action_row'] = socialLikeRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_RATE:
                            $feed_row['action_row'] = socialRateRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_INVITE:
                        case SOCIAL_ACTION_SHARE:
                                $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                                $channel_entity_type = $feed_row['action_row']['entity_type'];
                                if($channel_entity_type==SOCIAL_ENTITY_CHANNEL){
                                    $channel_id = $feed_row['channel_id'];
                                    $channelInfo = channelGetInfo($channel_id);
                                    $feed_row['channel_row'] = $channelInfo;
                                }
                                break;
                        case SOCIAL_ACTION_REECHOE:
                            $feed_row['action_row'] = socialReechoeRow($feed_row['action_id']);                            
                        break;
                        case SOCIAL_ACTION_RELATION_PARENT:
                        case SOCIAL_ACTION_RELATION_SUB:
                            $feed_row['action_row'] = channelRelationRow($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                        break;
                        case SOCIAL_ACTION_UPLOAD:
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
                            $feed_row['action_row_count'] =newsfeedLogSearch($srch_options);
                            
                            if($feed_row['action_row_count']>1){
                                $srch_options['n_results']=false;
                                $srch_options['order']='d';
                                $srch_options['limit']=4;
                                $srch_options['orderby']='id';
                                $oth_lst = newsfeedLogSearch($srch_options);
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_SPONSOR:
                            //in case of sponsor newsfeed.user_id is actaully the channel_id
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                            $feed_row['action_row'] = socialShareGet($feed_row['action_id']);

                            $sp_info = socialShareGet($feed_row['action_id']);
                            $feed_row['action_row']['msg'] = $sp_info['msg'];

                            //we dont have user info
                            unset($feed_row['YourUserName']);
                            unset($feed_row['FullName']);
                            unset($feed_row['display_fullname']);
                            unset($feed_row['profile_Pic']);
                            unset($feed_row['gender']);
                        break;
                        case SOCIAL_ACTION_EVENT_JOIN:
                            $feed_row['join_row'] = joinEventInfo($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                        case SOCIAL_ACTION_FRIEND:
                        case SOCIAL_ACTION_FOLLOW:
                            $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];		
                        break;
                        default:
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                    }
			
                    if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM ){
                        //in case album
                        $catalog_row = userCatalogDefaultMediaGet($feed_row['action_row']['entity_id']);
                        if(!$catalog_row) $catalog_row = array();
                        $feed_row['media_row'] = $catalog_row;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                    }else if( $feed_row['action_type'] == SOCIAL_ACTION_LIKE && $feed_row['entity_type'] == SOCIAL_ENTITY_COMMENT ){
                        //in case like a comment
                        $cr = socialCommentRow($feed_row['entity_id']);
                        $feed_row['media_row'] = socialEntityInfo($cr['entity_type'], $cr['entity_id']);

                        $feed_row['original_media_row'] = $cr;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                        $feed_row['entity_type'] = $cr['entity_type'];
                        $feed_row['entity_id'] = $cr['entity_id'];
                    }else if( ($feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME) &&  !$feed_row['channel_id'] ){
                            $feed_row['media_row'] = socialEntityInfo(SOCIAL_ENTITY_USER_EVENTS, $feed_row['action_row']['entity_id']);
                    }else if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
                            //$feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['channel_id']);
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_id']);
                            }else{
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_row']['entity_id']);
                            }
                    }else {
                            //just get the media row
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['action_row']['entity_id'] = $feed_row['action_id'];
                            }
                            $feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['entity_id']);
                            if( $feed_row['entity_type'] == SOCIAL_ENTITY_VISITED_PLACES ){
                                $stateinfo = worldStateInfo($feed_row['media_row']['country_code'],$feed_row['media_row']['state_code']);
                                $state_name = (!$stateinfo)? '' : $stateinfo['state_name'];
                                $country_name= countryGetName($feed_row['media_row']['country_code']);
                                $country_name = (!$country_name)? '' : $country_name;
                                $feed_row['media_row']['state_name']=$state_name;
                                $feed_row['media_row']['country_name']=$country_name;
                            }
                    }
			
                    ///////////////////////
                    //in case no profile pic and not the channel sponsor action
                    if( ( !isset($feed_row['profile_Pic']) || $feed_row['profile_Pic'] == '' ) && $feed_row['action_type'] != SOCIAL_ACTION_SPONSOR && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_SUB && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_PARENT ){
                        $feed_row['profile_Pic'] = 'he.jpg';
                        if($feed_row['gender']=='F'){
                            $feed_row['profile_Pic'] = 'she.jpg';
                        }
                    }
			
			$feed[] = $feed_row;
		}
		
		return $feed;
		
	// Case of returning n_results.
	} else {		
            $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";

            $query1 = "( $query AND NF.action_type<>".SOCIAL_ACTION_UPLOAD." ) UNION ( select * from ($query AND NF.action_type=".SOCIAL_ACTION_UPLOAD." ORDER BY NF.$orderby $order) AS X GROUP BY X.action_type,X.entity_type,X.entity_group,X.feed_privacy)";
           $query = "select count(X1.id) from ($query1) AS X1 ";		

            //return $query;
//            $ret = db_query($query);
//            $row = db_fetch_row($ret);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
            $row = $select->fetch();

            return $row[0];
	}
//  Changed by Anthony Malak 09-05-2015 to PDO database

}
function newsfeedGroupingLogSearchNew($srch_options) {
	global $dbConn;
	$params  = array();  
	$params2 = array();  

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
	  
        
	//in case user_id is set we want the user news feed
	//in case channel_id is set we want the channel notifications
	$nlimit ='';
	$where ='';
	if( $options['limit'] ){
		$nlimit = intval($options['limit']);
		$skip = intval($options['page']) * $nlimit;
	}

	$orderby = $options['orderby'];
	$order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
	if( $options['owner_id'] ){
           $userid = intval($options['owner_id']); 
        }else{
            $userid = userGetID();
        }	
	if( isset($userid) && $userid>0 && !$options['channel_id'] ){
		$friends = userGetFreindList($userid);
		$friends_ids = array($userid);
		foreach($friends as $freind){
			$friends_ids[] = $freind['id'];
		}
		if(count($friends_ids)!=0){
			if($where != '') $where .= " AND";
			$public = USER_PRIVACY_PUBLIC;
			$private = USER_PRIVACY_PRIVATE;
			$selected = USER_PRIVACY_SELECTED;
			$community = USER_PRIVACY_COMMUNITY;
			$privacy_where = '';
			$where .= " (";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1  LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FRIEND." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FRIENDS." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1  LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NF.action_type=".SOCIAL_ACTION_FOLLOW." AND EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.user_id=NF.user_id AND PR.entity_type=".SOCIAL_ENTITY_PROFILE_FOLLOWINGS." AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type=:Public LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( NOT EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1  LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.user_id = :Userid LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type='$community' AND PR.user_id IN (" . implode(',', $friends_ids) . ") LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND PR.kind_type=:Private AND PR.user_id=:Userid LIMIT 1 ) )";
			$where .= " OR";
			$where .= " ( EXISTS ( SELECT PR.id FROM cms_users_privacy_extand PR WHERE PR.entity_id=NF.entity_id AND PR.entity_type=NF.entity_type AND PR.published=1 AND ( ( FIND_IN_SET( '$community' , CONCAT( PR.kind_type ) ) AND PR.user_id IN (" . implode(',', $friends_ids) . ") ) OR ( FIND_IN_SET( '$userid' , CONCAT( PR.users ) ) )  )   LIMIT 1 ) )";
			$where .= " )";
                        
			//$where .= " ELSE 0 END ";
			$params[] = array( "key" => ":Userid", "value" =>$userid);
			$params[] = array( "key" => ":Public", "value" =>$public);                        
			$params[] = array( "key" => ":Private", "value" =>$private);
		}	
	}
	if( $options['userid'] ){
		if($where != '') $where .= " AND";
		$where .= " (";
		$where .= " ( NF.user_id = :Userid2 )";
		$where .= " OR";
		$where .= " ( NF.user_id<>:Userid2 AND COALESCE(NF.channel_id, 0) = 0 AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.user_id=NF.owner_id AND EXISTS (SELECT p.id FROM cms_social_posts as p WHERE p.from_id=NF.user_id AND p.user_id = :Userid2 AND p.id=NF.entity_id) AND action_type=".SOCIAL_ACTION_UPLOAD." )";
		$where .= " )";
		
		//$where .= " CASE";
		//$where .= " WHEN NF.user_id = :Userid2 THEN 1";
		//$where .= " WHEN NF.user_id<>:Userid2 AND COALESCE(NF.channel_id, 0) = 0 AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.user_id=NF.owner_id AND EXISTS (SELECT p.id FROM cms_social_posts as p WHERE p.from_id=NF.user_id AND p.user_id = :Userid2 AND p.id=NF.entity_id) AND action_type=".SOCIAL_ACTION_UPLOAD." THEN 1";
		//$where .= " ELSE 0 END "; 
		$params[] = array( "key" => ":Userid2", "value" =>$options['userid']);
	}
	if( $options['from_filter'] && intval($options['from_filter'])== FROM_FRIENDS ){
		if($where != '') $where .= " AND";
		$where .= " (";
		$where .= " ( COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1) )";
		$where .= " OR";
		$where .= " ( COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1) )";
		$where .= " )";
		
		//$where .= "CASE";
		//$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1)";
		//$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid3 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1)";
		//$where .= " ELSE 0 END ";
		$params[] = array( "key" => ":Userid3", "value" =>$userid);
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_FOLLOWINGS ){
		if($where != '') $where .= " AND";
		$where .= " (";
		$where .= " ( COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4) )";
		$where .= " OR";
		$where .= " ( COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4) )";
		$where .= " )";
		
		//$where .= "CASE";
		//$where .= " WHEN COALESCE(NF.channel_id, 0) = 0 THEN EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4)";
		//$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid4)";
		//$where .= " ELSE 0 END ";
        $params[] = array( "key" => ":Userid4", "value" =>$userid);
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_CHANNELS ){
		if($where != '') $where .= " AND";
		$where .= " COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid5 LIMIT 1 ) ";
		
		//$where .= "CASE";
		//$where .= " WHEN COALESCE(NF.channel_id, 0) THEN EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid5 LIMIT 1 )";
		//$where .= " ELSE 0 END ";
        $params[] = array( "key" => ":Userid5", "value" =>$options['userid']);
	}else if( !$options['from_filter'] ){
            
	}
	if( $options['channel_id'] && $options['channel_id'] !=-1 ){
		$channelInfo=channelGetInfo($options['channel_id']);
		$owner_id= intval($channelInfo['owner_id']);
	}else{
		$owner_id=intval($options['userid']);
	}
	if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TCHANNELS ){
            if($where != '') $where .= " AND ";
            if( $options['channel_id'] ){
                $where .= "NF.channel_id ='{$options['channel_id']}' AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Owner_id ";
                $params[] = array( "key" => ":Owner_id",
                                    "value" =>$owner_id);
            }else if( $options['userid'] ){
                $where .= "( COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy =".USER_PRIVACY_PRIVATE." ) ";
            }else{
                $where .= "COALESCE(NF.channel_id, 0) ";
            }
            if( $options['action_type'] ){
                if( $where != '') $where .= ' AND ';
                $where .= " NF.entity_type<>".SOCIAL_ENTITY_COMMENT." ";		
            }		
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TTPAGE ){
            if($where != '') $where .= " AND ";
            $where .= "NF.entity_type<>".SOCIAL_ENTITY_BAG." AND ";
            if( $options['owner_id'] ){
                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Owner_id2 ";
                $params[] = array( "key" => ":Owner_id2", "value" =>$options['owner_id']);
            }else if( $options['userid'] ){
                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Userid6 ";
                $params[] = array( "key" => ":Userid6",
                                    "value" =>$options['userid']);
            }else{
                $where .= "COALESCE(NF.channel_id, 0) = 0 ";
            }
            if( $options['action_type'] ){
                if( $where != '') $where .= ' AND ';
                $where .= " NF.entity_type<>".SOCIAL_ENTITY_COMMENT." ";		
            }
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TTPAGE_OTHER ){
            if($where != '') $where .= " AND ";
            if( $options['userid'] ){
                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id <> :Userid7";
                $params[] = array( "key" => ":Userid7",
                                    "value" =>$options['userid']);
            }
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_THOTELS ){
            if($where != '') $where .= " AND ";
            $where .= "NF.entity_type=".SOCIAL_ENTITY_BAG." ";
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TECHOES ){
		if($where != '') $where .= " AND ";
		$where .= "NF.entity_type=".SOCIAL_ENTITY_FLASH." ";
	}
	if( $options['search_string'] && (strlen($options['search_string'])!=0) ){	
		if($where != '') $where .= " AND ( ( U.display_fullname = 0 AND LOWER(YourUserName) LIKE :Search_string ) OR ( U.display_fullname = 1 AND LOWER(FullName) LIKE :Search_string2 ) )";		
                $params[] = array( "key" => ":Search_string",
                                    "value" =>$options['search_string']."%");
                $params[] = array( "key" => ":Search_string2",
                                    "value" =>"%".$options['search_string']."%");
	}
    if( $options['feed_privacy'] ){
        if($where != '') $where .= " AND ";
        if( intval($options['feed_privacy'])==-1){
            $where .= " NF.feed_privacy<>".USER_PRIVACY_PRIVATE."";
        }else{
            $where .= " NF.feed_privacy=:Feed_privacy ";
            $params[] = array( "key" => ":Feed_privacy",
                                "value" =>$options['feed_privacy']);
        }
    }
    if(!is_null($options['published'])){
            if($where != '') $where .= " AND ";
            $where .= " find_in_set(cast(NF.published as char), :Published) ";
            $params[] = array( "key" => ":Published", "value" =>$options['published']);
    }
    if( $options['is_visible'] != -1 ){
        if($options['is_visible'] == 1 && intval($options['userid']) !=0 ){
            if( $where != '') $where .= ' AND ';
            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid8)";
            $params[] = array( "key" => ":Userid8",
                                "value" =>$options['userid']);
        }else if($options['is_visible'] == 1 && $options['channel_id'] && intval($options['channel_id']) > 0 ){            
            if( $where != '') $where .= ' AND ';
            $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id4)";
            $params[] = array( "key" => ":Owner_id4",
                                "value" =>$owner_id);
        }else if($options['is_visible'] == 0 && intval($options['userid']) !=0 ){
            if( $where != '') $where .= ' AND ';
            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
        }else if($options['is_visible'] == 0 && $options['channel_id'] && intval($options['channel_id']) > 0 ){            
            if( $where != '') $where .= ' AND ';
            $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id5)";
            $params[] = array( "key" => ":Owner_id5",
                                "value" =>$owner_id);
        }
    }
    if( $options['entity_type'] ){
        if( $where != '') $where .= ' AND ';
        $where .= " NF.entity_type='{$options['entity_type']}' ";		
    }else{
        if($where != '') $where .= " AND ";
        global $CONFIG_EXEPT_ARRAY;
        $exept_array = $CONFIG_EXEPT_ARRAY;
       $where .= " NF.entity_type NOT IN(". implode(',', $exept_array) .") ";        
    }
    if( $options['entity_id'] ){
            if( $where != '') $where .= ' AND ';
            $where .= " NF.entity_id=:Entity_id ";
            $params[] = array( "key" => ":Entity_id",
                                "value" =>$options['entity_id']);		
    }
    if( $options['action_type'] ){
            if( $where != '') $where .= ' AND ';
            $where .= " NF.action_type=:Action_type ";
            $params[] = array( "key" => ":Action_type",
                                "value" =>$options['action_type']);
    }else {
            if($where != '') $where .= " AND ";
            $where .= " NF.action_type<>".SOCIAL_ACTION_UNFRIEND." AND NF.action_type<>".SOCIAL_ACTION_UNFOLLOW." ";
    }
    if($options['from_ts'] || $options['to_ts']){			
        if($options['from_ts']){
            if( $where != '') $where .= " AND ";
            $where .= " DATE(NF.feed_ts) >= :From_ts ";
            $params[] = array( "key" => ":From_ts",
                                "value" =>$options['from_ts']);
        }
        if($options['to_ts']){
            if( $where != '') $where .= " AND ";
            $where .= " DATE(NF.feed_ts) <= :To_ts ";
            $params[] = array( "key" => ":To_ts",
                                "value" =>$options['to_ts']);
        }
    }		
    if($options['from_time'] || $options['to_time']){			
        if($options['from_time']){
            if( $where != '') $where .= " AND ";
            $where .= " (NF.feed_ts) >= :From_time ";
            $params[] = array( "key" => ":From_time",
                                "value" =>$options['from_time']);
        }
        if($options['to_time']){
            if( $where != '') $where .= " AND ";
            $where .= " (NF.feed_ts) <= :To_time ";
            $params[] = array( "key" => ":To_time",
                                "value" =>$options['to_time']);
        }
    }
	
	if($where != '') $where .= " AND";
	$where .= " (";
	$where .= " ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." AND EXISTS ( SELECT id FROM cms_videos AS VV WHERE VV.id=NF.entity_id AND VV.published=1";
    if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
            $where .= " AND image_video=:Media_type )";
            $params[] = array( "key" => ":Media_type", "value" =>$options['media_type']);								
    }else{
            $where .= " )";
    }
	$where .= " )";
    $where .= " OR";
	$where .= " ( NF.entity_type=".SOCIAL_ENTITY_POST." AND EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 ) )";
    $where .= " OR";
	$where .= " ( NF.entity_type=".SOCIAL_ENTITY_ALBUM." AND EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) ) )";
    $where .= " OR";
	$where .= " ( NF.action_type=".SOCIAL_ACTION_EVENT_CANCEL." AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE.")";
	$where .= " OR";
	$where .= " ( NF.action_type!=".SOCIAL_ACTION_EVENT_CANCEL." AND NF.entity_type!=".SOCIAL_ENTITY_ALBUM." AND NF.entity_type!=".SOCIAL_ENTITY_POST." AND NF.entity_type!=".SOCIAL_ENTITY_MEDIA." )";
	$where .= " )";
		
    //$where .= "CASE";
    //$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_MEDIA." THEN EXISTS ( SELECT id FROM cms_videos AS VV WHERE VV.id=NF.entity_id AND VV.published=1";
    //if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
            //$where .= " AND image_video=:Media_type )";
            //$params[] = array( "key" => ":Media_type", "value" =>$options['media_type']);								
    //}else{
            //$where .= " )";
    //}
    //$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_POST." THEN EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 )";
    //$where .= " WHEN NF.entity_type=".SOCIAL_ENTITY_ALBUM." THEN EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) )";
    //$where .= " WHEN NF.action_type=".SOCIAL_ACTION_EVENT_CANCEL." THEN NF.feed_privacy<>".USER_PRIVACY_PRIVATE."";
    //$where .= " ELSE 1 END ";
    
    if( !$options['channel_id'] ){
        if($where != '') $where .= " AND ";
        $where .= " COALESCE(NF.channel_id, 0) = 0 ";
        if( intval($options['userid']) !=0){
            $where .= "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid8 AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND notify=0 AND published='1') ";
            $params[] = array( "key" => ":Userid8",
                                "value" =>$options['userid']);
        }
		if($where != '') $where .= " AND";
		$where .= " (";
		$where .= " ( (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." AND EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id AND SHR.from_user=:Userid) )";
        $where .= " OR";
		$where .= " ( (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy=".USER_PRIVACY_PRIVATE." AND EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id AND SHR.from_user=:Userid) )";
        $where .= " OR";
		$where .= " ( NF.action_type!=".SOCIAL_ACTION_SPONSOR." AND NF.action_type!=".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type!=".SOCIAL_ACTION_RELATION_PARENT." AND NF.action_type!=".SOCIAL_ACTION_SHARE." AND NF.action_type!=".SOCIAL_ACTION_INVITE." )";
		$where .= " )";
		
        //$where .= "CASE";
        //$where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." THEN EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id AND SHR.from_user=:Userid)";
        //$where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy=".USER_PRIVACY_PRIVATE." THEN EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id AND SHR.from_user=:Userid)";
        //$where .= " WHEN NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT." THEN 0";
        //$where .= " ELSE 1 END ";
    }else if ($options['channel_id'] != -1){
        $myChannelId = intval($options['channel_id']);
        $channel_sub_array = getSubChannelRelationList($myChannelId,'1');
        $channel_parent_array = getParentChannelRelationList($myChannelId,'1');
        if($channel_sub_array!=''){
            $myChannelId.=',';
            $myChannelId.=$channel_sub_array;
        } 
        if($channel_parent_array!=''){
            $myChannelId.=',';
            $myChannelId.=$channel_parent_array;
        }
		if($where != '') $where .= " AND";
		$where .= " (";
		$where .= " ( (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." AND NF.user_id IN($myChannelId) )";        
        $where .= " OR";
		$where .= " ( (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." AND NF.channel_id IN($myChannelId) AND NF.user_id=:Owner_id6 )";
		$where .= " OR";
		$where .= " ( (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.channel_id IN($myChannelId) AND NF.user_id=:Owner_id6 AND NF.feed_privacy=".USER_PRIVACY_PRIVATE." AND EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id) )";        
        $where .= " OR";
		$where .= " ( NF.channel_id IN($myChannelId) AND EXISTS (SELECT id FROM cms_channel AS CM WHERE CM.id=NF.channel_id AND CM.owner_id=NF.user_id) )"; 
        if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TCHANNELS ){
            $where .= " OR";
			$where .= " ( NF.channel_id NOT IN($myChannelId) AND NF.action_type!=".SOCIAL_ACTION_SPONSOR." AND NF.action_type!=".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type!=".SOCIAL_ACTION_RELATION_PARENT." AND NF.action_type!=".SOCIAL_ACTION_SHARE." AND NF.action_type!=".SOCIAL_ACTION_INVITE.")"; 
        }
		$where .= " )";
		
        //$where .= "CASE";
        //$where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_RELATION_SUB." OR NF.action_type=".SOCIAL_ACTION_RELATION_PARENT.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." AND NF.user_id IN($myChannelId)  THEN 1";        
        //$where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." AND NF.channel_id IN($myChannelId) AND NF.user_id=:Owner_id6 THEN 1";
		//$where .= " WHEN (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND NF.channel_id IN($myChannelId) AND NF.user_id=:Owner_id6 AND NF.feed_privacy=".USER_PRIVACY_PRIVATE." THEN EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id)";        
        //$where .= " WHEN NF.channel_id IN($myChannelId) THEN EXISTS (SELECT id FROM cms_channel AS CM WHERE CM.id=NF.channel_id AND CM.owner_id=NF.user_id)"; 
        //if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TCHANNELS ){
            //$where .= " ELSE 1 END ";
        //}else{
            //$where .= " ELSE 0 END "; 
        //}
        $params[] = array( "key" => ":Owner_id6","value" =>$owner_id);
    }else{
        if( $where != '') $where .= ' AND ';
        $where .= " COALESCE(NF.channel_id, 0) ";
    }
	
    if( intval($options['userid']) !=0 ){
		if($where != '') $where .= " AND";
		$where .= " (";
		$where .= " ( NF.action_type=".SOCIAL_ACTION_COMMENT." AND NF.user_id=:Userid10 AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id=:Userid10) )";
		$where .= " OR";
		$where .= " NF.action_type!=".SOCIAL_ACTION_COMMENT."";
		$where .= " )";
		
		//$where .= "CASE";
		//$where .= " WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." THEN NF.user_id=:Userid10 AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id=:Userid10) ";
		//$where .= " ELSE 1 END";
		$params[] = array( "key" => ":Userid10", "value" =>$options['userid']);
    }
    $where .= " AND NF.action_type <>".SOCIAL_ACTION_REECHOE." ";
    if($where != '') $where = " WHERE $where ";
	
	if(!$options['n_results']){
		if($orderby=='most_liked'){			
                   $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender, ( SELECT COUNT(SL.id) FROM cms_social_likes AS SL WHERE SL.entity_id=NF.entity_id AND SL.entity_type=NF.entity_type AND SL.like_value=1 AND SL.published=1 ) AS counter
                         FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";
                   $query1 = "( $query AND NF.action_type<>".SOCIAL_ACTION_UPLOAD." ) UNION ( select * from ($query AND NF.action_type=".SOCIAL_ACTION_UPLOAD." ORDER BY NF.id DESC) AS X GROUP BY X.action_type,X.entity_type,X.entity_group,X.feed_privacy)";
                    $query = "select * from ($query1) AS X1 ORDER BY X1.counter $order";
                }else{
                    $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender
                         FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";
                    
                    $query1 = "( $query AND NF.action_type<>".SOCIAL_ACTION_UPLOAD." ORDER BY NF.$orderby $order LIMIT 0, 100) UNION ( select * from ($query AND NF.action_type=".SOCIAL_ACTION_UPLOAD." ORDER BY NF.$orderby $order LIMIT 0, 100) AS X GROUP BY X.action_type,X.entity_type,X.entity_group,X.feed_privacy)";
                    $query = "select * from ($query1) AS X1 ORDER BY X1.$orderby $order";                    
                }
		if( $options['limit'] ){
			$query .= " LIMIT :Skip, :Nlimit"; 
                        $params[] = array( "key" => ":Skip", "value" => $skip, "type" =>"::PARAM_INT");
                        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
		}
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();

                $ret    = $select->rowCount();
		
		$feed = array();
                $row = $select->fetchAll(PDO::FETCH_ASSOC);
                foreach($row as $row_item){ 
                    $feed_row = $row_item;	
                    $feed_row['action_row_count'] =0;
                    $feed_row['action_row_other'] =array(); 
                    switch( $feed_row['action_type'] ){
                        case SOCIAL_ACTION_COMMENT:
                            $feed_row['action_row'] = socialCommentRow($feed_row['action_id']);
                            $channel_entity_type = $feed_row['action_row']['entity_type'];
                            if($channel_entity_type==SOCIAL_ENTITY_CHANNEL){
                                $channel_id = $feed_row['channel_id'];
                                $channelInfo = channelGetInfo($channel_id);
                                $feed_row['channel_row'] = $channelInfo;
                            }
                        break;
                        case SOCIAL_ACTION_LIKE:
                            $feed_row['action_row'] = socialLikeRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_RATE:
                            $feed_row['action_row'] = socialRateRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_INVITE:
                        case SOCIAL_ACTION_SHARE:
                                $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                                $channel_entity_type = $feed_row['action_row']['entity_type'];
                                if($channel_entity_type==SOCIAL_ENTITY_CHANNEL){
                                    $channel_id = $feed_row['channel_id'];
                                    $channelInfo = channelGetInfo($channel_id);
                                    $feed_row['channel_row'] = $channelInfo;
                                }
                                break;
                        case SOCIAL_ACTION_REECHOE:
                            $feed_row['action_row'] = socialReechoeRow($feed_row['action_id']);                            
                        break;
                        case SOCIAL_ACTION_RELATION_PARENT:
                        case SOCIAL_ACTION_RELATION_SUB:
                            $feed_row['action_row'] = channelRelationRow($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                        break;
                        case SOCIAL_ACTION_UPLOAD:
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
                            $feed_row['action_row_count'] =newsfeedLogSearch($srch_options);
                            
                            if($feed_row['action_row_count']>1){
                                $srch_options['n_results']=false;
                                $srch_options['order']='d';
                                $srch_options['limit']=4;
                                $srch_options['orderby']='id';
                                $oth_lst = newsfeedLogSearch($srch_options);
                                $feed_row['action_row_other'] =$oth_lst;
                            }
                        break;
                        case SOCIAL_ACTION_SPONSOR:
                            //in case of sponsor newsfeed.user_id is actaully the channel_id
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                            $feed_row['action_row'] = socialShareGet($feed_row['action_id']);

                            $sp_info = socialShareGet($feed_row['action_id']);
                            $feed_row['action_row']['msg'] = $sp_info['msg'];

                            //we dont have user info
                            unset($feed_row['YourUserName']);
                            unset($feed_row['FullName']);
                            unset($feed_row['display_fullname']);
                            unset($feed_row['profile_Pic']);
                            unset($feed_row['gender']);
                        break;
                        case SOCIAL_ACTION_EVENT_JOIN:
                            $feed_row['join_row'] = joinEventInfo($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                        case SOCIAL_ACTION_FRIEND:
                        case SOCIAL_ACTION_FOLLOW:
                            $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];		
                        break;
                        default:
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                    }
			
                    if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM ){
                        //in case album
                        $catalog_row = userCatalogDefaultMediaGet($feed_row['action_row']['entity_id']);
                        if(!$catalog_row) $catalog_row = array();
                        $feed_row['media_row'] = $catalog_row;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                    }else if( $feed_row['action_type'] == SOCIAL_ACTION_LIKE && $feed_row['entity_type'] == SOCIAL_ENTITY_COMMENT ){
                        //in case like a comment
                        $cr = socialCommentRow($feed_row['entity_id']);
                        $feed_row['media_row'] = socialEntityInfo($cr['entity_type'], $cr['entity_id']);

                        $feed_row['original_media_row'] = $cr;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                        $feed_row['entity_type'] = $cr['entity_type'];
                        $feed_row['entity_id'] = $cr['entity_id'];
                    }else if( ($feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME) &&  !$feed_row['channel_id'] ){
                            $feed_row['media_row'] = socialEntityInfo(SOCIAL_ENTITY_USER_EVENTS, $feed_row['action_row']['entity_id']);
                    }else if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_id']);
                            }else{
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_row']['entity_id']);
                            }
                    }else {
                            //just get the media row
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['action_row']['entity_id'] = $feed_row['action_id'];
                            }
                            $feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['entity_id']);
                            if( $feed_row['entity_type'] == SOCIAL_ENTITY_VISITED_PLACES ){
                                $stateinfo = worldStateInfo($feed_row['media_row']['country_code'],$feed_row['media_row']['state_code']);
                                $state_name = (!$stateinfo)? '' : $stateinfo['state_name'];
                                $country_name= countryGetName($feed_row['media_row']['country_code']);
                                $country_name = (!$country_name)? '' : $country_name;
                                $feed_row['media_row']['state_name']=$state_name;
                                $feed_row['media_row']['country_name']=$country_name;
                            }
                    }
			
                    ///////////////////////
                    //in case no profile pic and not the channel sponsor action
                    if( ( !isset($feed_row['profile_Pic']) || $feed_row['profile_Pic'] == '' ) && $feed_row['action_type'] != SOCIAL_ACTION_SPONSOR && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_SUB && $feed_row['action_type'] != SOCIAL_ACTION_RELATION_PARENT ){
                        $feed_row['profile_Pic'] = 'he.jpg';
                        if($feed_row['gender']=='F'){
                            $feed_row['profile_Pic'] = 'she.jpg';
                        }
                    }
			
			$feed[] = $feed_row;
		}
		
		return $feed;
		
	// Case of returning n_results.
	} else {		
            $query = "SELECT NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender FROM `cms_social_newsfeed` AS NF LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_RELATION_SUB." AND NF.action_type<>".SOCIAL_ACTION_RELATION_PARENT." $where";

            $query1 = "( $query AND NF.action_type<>".SOCIAL_ACTION_UPLOAD." ) UNION ( select * from ($query AND NF.action_type=".SOCIAL_ACTION_UPLOAD." ORDER BY NF.$orderby $order) AS X GROUP BY X.action_type,X.entity_type,X.entity_group,X.feed_privacy)";
            $query = "select count(X1.id) from ($query1) AS X1 ";
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
            $row = $select->fetch();

            return $row[0];
	}
}
////////////////////////////////
//TODO this function needs everything work to finish the album
/**
 * gets the newsfeed of a user
 * @param array $srch_options options to search. options include:<br/>
 * <b>limit</b>: the maximum number of user records returned. default 10<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table cms_users. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * <b>userid</b>: which users's news feed. required.<br/>
 * <b>search_string</b>: which users's news feed. required.<br/>
 * @from_ts: start date default null<br/>
 * @to_ts: end date default null<br/>
 * @from_time: start time default null<br/>
 * @to_time: end time default null<br/>
 * @from_filter: filter on retrived data related to (FROM_FRIENDS or FROM_FOLLOWINGS or FROM_CHANNELS) default null<br/>
 * @activities_filter: filter on retrived data related to activities (ACTIVITIES_ON_TCHANNELS or ACTIVITIES_ON_THOTELS or ACTIVITIES_ON_TRESTAURANTS or ACTIVITIES_ON_TPLANNER or ACTIVITIES_ON_TTPAGE) default null<br/>
 * <b>media_type</b>: what type of media file (v)ideo or (i)mage or null. default null<br/>
 * @param integer $entity_type the entity type of the feed
 * @param integer $entity_id the entity id of the feed
 * @param integer $action_type the action type of the feed
 * <b>is_visible</b>: 1=> visible, 0=> invisible. -1 => doesnt matter. default -1.<br/>
 * <b>show_owner</b>: 0 in case of notification to hide the owner channel newsfeed and 1 in case of channel log to show the owner channel newsfeed and -1 for default<br/>
 * <b>is_notification</b>: 1 in case of tuber notification, -1 for default<br/>
 * <b>channel_id</b> the channel involved, null => no channel involved, -1 => doesn't matter<br/>
 * <b>feed_privacy</b> feed privacy, null => doesn't matter, -1 feed not equal 0<br/>
 * <b>published</b>: published status of record. default '0,1' . 0 for newsfeed not viewed . 1 newsfeed viewed . null => doesnt matter.<br/>
 * @return array a set of 'newsfeed records' could either be a comment, of an upload
 */
function newsfeedSearch($srch_options) {
//  Changed by Anthony Malak 09-05-2015 to PDO database

	global $dbConn;
	$params = array(); 

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
		'is_notification' => -1,
		'userid' => null,
                'owner_id' => null,
		'is_visible' => 1,
		'search_string' => null,
		'channel_id' => null,
                'feed_privacy' => null,
		'show_owner' => -1,
		'published' => '0,1',
		'n_results' => false,
	);

	$options = array_merge($default_opts, $srch_options);
	
	//in case user_id is set we want the user news feed
	//in case channel_id is set we want the channel notifications
	$nlimit ='';
	$where ='';
	if( $options['limit'] ){
		$nlimit = intval($options['limit']);
		$skip = intval($options['page']) * $nlimit;
	}

	$orderby = $options['orderby'];
	$order = ($options['order'] == 'a') ? 'ASC' : 'DESC';
	
	if( $options['userid'] ){
		if( intval($options['show_owner'])==1 ){
                    if($where != '') $where .= " AND ";
//                    $where .= " NF.user_id = $options['userid'] ";
                    $where .= " NF.user_id = :Userid ";
                    $params[] = array( "key" => ":Userid",
                                        "value" =>$options['userid']);
		}else if( intval($options['is_notification'])==1 &&  intval($options['show_owner'])==0){
			
		}else{
			$userid = intval($options['userid']);
	
			$where = '';
			$friends = userGetFreindList($userid);
			$friends_ids = array($userid);
			foreach($freinds as $freind){
				$friends_ids[] = $freind['id'];
			}			
			////////////////////////////	
			if(count($friends_ids)!=0){
				if($where != '') $where .= " AND ";
				$public = USER_PRIVACY_PUBLIC;
				$private = USER_PRIVACY_PRIVATE;
				$selected = USER_PRIVACY_SELECTED;
				$community = USER_PRIVACY_COMMUNITY;
				$privacy_where = '';
				
				if( !$options['channel_id'] && $options['show_owner'] && $options['show_owner']==0 && intval($options['userid']) !=0 && $options['is_notification'] !=1 ){
					if( $privacy_where != '') $privacy_where .= ' OR ';
//					$privacy_where .= " ( feed_privacy=$public ".$privacy_where_newsfeeds_public." AND ( NOT EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$userid AND receipient_id=NF.user_id AND status=1 AND notify=0)".$privacy_where_newsfeeds." ) )";
					$privacy_where .= " ( feed_privacy=:Public ".$privacy_where_newsfeeds_public." AND ( NOT EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid4 AND receipient_id=NF.user_id AND status=1 AND notify=0)".$privacy_where_newsfeeds." ) )";
                                        $params[] = array( "key" => ":Public",
                                                            "value" =>$public);
                                        $params[] = array( "key" => ":Userid4",
                                                            "value" =>$userid);
				}
					
				//selected user feeds. the user must be subscribed to the feed AND the user must have the permission to view it
				if( count($subscribed_to_ids) != 0 ){
	
					$permission_where = array();
//					$permission_where[] = " (
//												entity_type=" . SOCIAL_ENTITY_MEDIA  . "
//											AND
//												EXISTS (SELECT to_user FROM cms_social_permissions WHERE from_user=NF.user_id AND to_user='$userid' AND perm_fk=entity_id AND perm_type='".SOCIAL_PERMISSION_MEDIA."')
//											)";
					$permission_where[] = " (
												entity_type=" . SOCIAL_ENTITY_MEDIA  . "
											AND
												EXISTS (SELECT to_user FROM cms_social_permissions WHERE from_user=NF.user_id AND to_user=:Userid5 AND perm_fk=entity_id AND perm_type='".SOCIAL_PERMISSION_MEDIA."')
											)";
//					$permission_where[] .= " (
//												entity_type=" . SOCIAL_ENTITY_ALBUM . "
//											AND
//												EXISTS (SELECT to_user FROM cms_social_permissions WHERE from_user=NF.user_id AND to_user='$userid' AND perm_fk=entity_id AND perm_type='".SOCIAL_PERMISSION_ALBUM."')
////											)";
                                        $permission_where[] .= " (
												entity_type=" . SOCIAL_ENTITY_ALBUM . "
											AND
												EXISTS (SELECT to_user FROM cms_social_permissions WHERE from_user=NF.user_id AND to_user=:Userid5 AND perm_fk=entity_id AND perm_type='".SOCIAL_PERMISSION_ALBUM."')
											)";
                                        $params[] = array( "key" => ":Userid5", "value" =>$userid);
					//$permission_where[] .= " ( COALESCE(NF.channel_id, 0) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid='{$options['userid']}' LIMIT 1 ) ) ";
					//add more $permission_where cases here
	
					$permission_where = '(' . implode(' OR ', $permission_where) . ')';					
	
					if( $privacy_where != '') $privacy_where .= ' OR ';
					$privacy_where .= " ( feed_privacy=$selected AND user_id IN (" . implode(',', $subscribed_to_ids) . ") AND $permission_where ) ";					
				}
	
				//friends user feeds. the user must be subscribed to the feed AND the user must be a friend
				if( count($friends_ids) != 0 ){
					$friend_where = array();
					//I can view my community feeds
					$friend_where[] = " user_id = '$userid' ";
					//my friends can view my community feeds
//					$friend_where[] = " EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1) ";
					$friend_where[] = " EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid6 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1) ";
                                        $params[] = array( "key" => ":Userid6", "value" =>$userid);
	
					$friend_where = '(' . implode(' OR ', $friend_where) . ')';
	
					if( $privacy_where != '') $privacy_where .= ' OR ';
					$privacy_where .= " ( feed_privacy=$community AND user_id IN (" . implode(',', $friends_ids) . ") AND $friend_where ) ";
				}
	
				//extended community
				//TODO when its known what this actually is
	
				//////////////////////////////
				//private feeds
	
				if( $privacy_where != '') $privacy_where .= ' OR ';
				//private shared feed from a friend for which notifications are not disabled
//				$private_share_blocked_friend = " ( action_type = " . SOCIAL_ACTION_SHARE ." AND EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1) ) ";
                                $private_share_blocked_friend = " ( action_type = " . SOCIAL_ACTION_SHARE ." AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid7 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1) ) ";
                                
				//private feeds. only the original user can view them and it must not be a shared feed from a currently blocked friend
//				$private_notshare = " ( action_type <> " . SOCIAL_ACTION_SHARE .  " AND user_id='$userid' )";
				$private_notshare = " ( action_type <> " . SOCIAL_ACTION_SHARE .  " AND user_id=:Userid7 )";
				$private_share_condition = " ( $private_notshare OR $private_share_blocked_friend ) ";
				//$privacy_where .= " ( feed_privacy=$private AND (user_id='$userid' OR $private_share_condition ) ) ";
//				$privacy_where .= " ( feed_privacy=$private AND user_id='$userid' ) ";
				$privacy_where .= " ( feed_privacy=:Private AND user_id=:Userid7 ) ";
	
				$where .= " ( $privacy_where ) ";
                                $params[] = array( "key" => ":Private",
                                                    "value" =>$private);
                                $params[] = array( "key" => ":Userid7",
                                                    "value" =>$userid);
				
			}
		}
			
	}
	
	if( $options['from_filter'] && intval($options['from_filter'])== FROM_FRIENDS ){
            if($where != '') $where .= " AND ";
//		$where .= "( ( COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1) ) OR ( COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1) ) ) ";
		$where .= "( ( COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=$userid AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1) ) OR ( COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR.") AND EXISTS (SELECT requester_id FROM cms_friends WHERE published=1 AND requester_id=:Userid8 AND receipient_id=NF.user_id AND status=".FRND_STAT_ACPT." AND notify=1) ) ) ";
                $params[] = array( "key" => ":Userid8",
                                    "value" =>$userid);
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_FOLLOWINGS ){
            if($where != '') $where .= " AND ";
		$where .= "( (COALESCE(NF.channel_id, 0) = 0 AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id='$userid')) OR (COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id<>NF.user_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR.") AND EXISTS (SELECT FL.subscription_id FROM cms_subscriptions AS FL WHERE FL.published = 1 AND FL.user_id=NF.user_id AND FL.subscriber_id=:Userid9) ) ) ";
                $params[] = array( "key" => ":Userid9",
                                    "value" =>$userid);
	}else if( $options['from_filter'] && intval($options['from_filter'])== FROM_CHANNELS ){
            if($where != '') $where .= " AND ";
//            $where .= "( COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid='$userid' LIMIT 1 ) ) ";
            $where .= "( COALESCE(NF.channel_id, 0) AND EXISTS (SELECT CHA.id FROM cms_channel AS CHA WHERE CHA.id=NF.channel_id AND CHA.owner_id=NF.user_id) AND EXISTS ( SELECT F.userid FROM cms_channel_connections AS F WHERE F.channelid=NF.channel_id AND F.published=1 AND F.userid=:Userid10 LIMIT 1 ) ) ";
            $params[] = array( "key" => ":Userid10",
                                "value" =>$userid);
	}
	if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TCHANNELS ){
            if($where != '') $where .= " AND ";
            if( intval($options['show_owner'])==1 && $options['userid'] ){
                $where .= "( COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy =".USER_PRIVACY_PRIVATE." ) ";
            }else{
                $where .= "COALESCE(NF.channel_id, 0) ";
            }		
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TTPAGE ){
            if($where != '') $where .= " AND ";
            $where .= "NF.entity_type<>".SOCIAL_ENTITY_BAG." AND ";
            if( intval($options['show_owner'])==1 && $options['owner_id'] ){
//                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = '{$options['owner_id']}' ";
                $where .= "COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Owner_id ";
                $params[] = array( "key" => ":Owner_id",
                                    "value" =>$options['owner_id']);
            }else if( intval($options['show_owner'])==1 && $options['userid'] ){
//                $where .= "( COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = '{$options['userid']}' ) ";
                $where .= "( COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id = :Userid11 ) ";
                $params[] = array( "key" => ":Userid11",
                                    "value" =>$options['userid']);
            }else{
                $where .= "COALESCE(NF.channel_id, 0) = 0 ";
            }
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TTPAGE_OTHER ){
            if($where != '') $where .= " AND ";
            if( intval($options['show_owner'])==1 && $options['userid'] ){
//                $where .= "( COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id <> '{$options['userid']}' ) ";
                $where .= "( COALESCE(NF.channel_id, 0) = 0 AND NF.feed_privacy <>".USER_PRIVACY_PRIVATE." AND NF.owner_id <> :Userid12 ) ";
                $params[] = array( "key" => ":Userid12",
                                    "value" =>$options['userid']);
            }
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_THOTELS ){
            if($where != '') $where .= " AND ";
            $where .= "NF.entity_type=".SOCIAL_ENTITY_BAG." ";
	}else if( $options['activities_filter'] && intval($options['activities_filter'])== ACTIVITIES_ON_TECHOES ){
		if($where != '') $where .= " AND ";
		$where .= "NF.entity_type=".SOCIAL_ENTITY_FLASH." ";
	}
	
	//////////////////////////////////////
	//if its an image it musnt be in the process of being deleted or processing
	if($where != '') $where .= " AND ";
	$media_status_where = array();
	if( intval($options['show_owner'])==1 && $options['userid'] ){
		
		$media_status_where[] = " ( ( NF.entity_type<>".SOCIAL_ENTITY_MEDIA." AND NF.entity_type<>".SOCIAL_ENTITY_ALBUM." AND NF.entity_type<>".SOCIAL_ENTITY_POST." AND NF.action_type<>".SOCIAL_ACTION_INVITE." AND NF.action_type<>".SOCIAL_ACTION_EVENT_CANCEL." AND NF.action_type<>".SOCIAL_ACTION_SHARE." ) OR ( ( NF.entity_type<>".SOCIAL_ENTITY_MEDIA." AND NF.entity_type<>".SOCIAL_ENTITY_ALBUM." AND NF.entity_type<>".SOCIAL_ENTITY_POST." AND ( NF.action_type=".SOCIAL_ACTION_INVITE." OR NF.action_type=".SOCIAL_ACTION_SHARE." ) ) AND ( NF.entity_type<>".SOCIAL_ENTITY_POST." ) ) ) ";
		$media_status_where[] =" ( NF.entity_type=".SOCIAL_ENTITY_ALBUM." AND EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) ) ) ";
		$media_status_where[] =" ( NF.entity_type=".SOCIAL_ENTITY_POST." AND EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 ) ) ";
		$media_status_where[] =" ( NF.action_type=".SOCIAL_ACTION_EVENT_CANCEL." AND NF.feed_privacy<>".USER_PRIVACY_PRIVATE." ) ";
		$media_status_where_string =" ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." AND EXISTS ( SELECT id FROM cms_videos AS VV WHERE VV.id=NF.entity_id AND VV.published=1 ";
		
	}else{
		if( intval($options['show_owner'])==0 && $options['userid'] && $options['is_notification'] !=1 ){
			$media_status_where[] = " ( NF.entity_type<>".SOCIAL_ENTITY_MEDIA." AND NF.entity_type<>".SOCIAL_ENTITY_ALBUM." AND NF.entity_type<>".SOCIAL_ENTITY_POST." AND NF.entity_type<>".SOCIAL_ENTITY_COMMENT." ) ";
			$media_status_where[] = " ( NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type<>".SOCIAL_ACTION_UPLOAD." ) ";
//			$media_status_where[] = " ( NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS ( SELECT PO.id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 AND ((PO.from_id<>0 AND PO.from_id<>".$options['userid'].") OR (PO.from_id=0 AND PO.user_id<>".$options['userid'].")) ) ) ";			
			$media_status_where[] = " ( NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS ( SELECT PO.id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 AND ((PO.from_id<>0 AND PO.from_id<>:Userid13) OR (PO.from_id=0 AND PO.user_id<>:Userid13)) ) ) ";			
                $params[] = array( "key" => ":Userid13",
                                    "value" =>$options['userid']);
		}else{
			$media_status_where[] = " NF.entity_type<>".SOCIAL_ENTITY_MEDIA." AND NF.entity_type<>".SOCIAL_ENTITY_ALBUM;
		}
                $media_status_where[] =" ( NF.entity_type=".SOCIAL_ENTITY_ALBUM." AND EXISTS ( SELECT id FROM cms_users_catalogs AS CAT WHERE CAT.id=NF.entity_id AND CAT.published=1 AND EXISTS ( SELECT id FROM cms_videos_catalogs AS VC WHERE VC.catalog_id=NF.entity_id LIMIT 1 ) ) ) ";
		$media_status_where_string=" ( NF.entity_type=".SOCIAL_ENTITY_MEDIA." AND EXISTS ( SELECT id FROM cms_videos VV WHERE VV.id=NF.entity_id AND VV.published=1 ";
	}
		
	
	if( $options['media_type'] && $options['media_type'] !='a' && $options['entity_type'] && $options['entity_type']==SOCIAL_ENTITY_MEDIA ){
		$media_status_where_string .= ' AND ';
		$media_status_where_string .= " image_video='{$options['media_type']}' ";
	}
	$media_status_where_string .= " ) ) ";
	$media_status_where[] .= $media_status_where_string;
	$where .= "(" . implode(' OR ', $media_status_where) . ")";
	//////////////////////////////
	
	if( $options['search_string'] && (strlen($options['search_string'])!=0) ){
		$search_name_where = array();
		//check begins on username
//		$search_name_where[] = "( U.display_fullname = 0 AND LOWER(YourUserName) LIKE '{$options['search_string']}%' )";
		$search_name_where[] = "( U.display_fullname = 0 AND LOWER(YourUserName) LIKE :Search_string)";
                $params[] = array( "key" => ":Search_string",
                                    "value" =>$options['search_string']."%" );
		//or check inside fullname
//		$search_name_where[] = "( U.display_fullname = 1 AND LOWER(FullName) LIKE '%{$options['search_string']}%' )";
		$search_name_where[] = "( U.display_fullname = 1 AND LOWER(FullName) LIKE :Search_string2 )";
                $params[] = array( "key" => ":Search_string2",
                                    "value" =>"%".$options['search_string']."%" );
		
		if($where != '') $where .= " AND ";
		if($where != '') $where .= '(' . implode(' OR ', $search_name_where) . ')';
	}
	
	//cant just show uploads without checking privacy. TODO: remove entirely
	/*if($where != '') $where .= " AND ";
	$published_where = " ( action_type <> " . SOCIAL_ACTION_UPLOAD . " ) ";
	$published_where .= " OR ( action_type = " . SOCIAL_ACTION_UPLOAD . " AND EXISTS (SELECT * FROM cms_videos WHERE id=action_id AND published=".MEDIA_READY.") ) ";
	$where .= " ( $published_where ) ";
	*/
	
	if( $options['feed_privacy'] ){
            if($where != '') $where .= " AND ";
            if( intval($options['feed_privacy'])==-1){
                $where .= " NF.feed_privacy<>".USER_PRIVACY_PRIVATE."";
            }else{
//                $where .= " NF.feed_privacy='{$options['feed_privacy']}' ";
                $where .= " NF.feed_privacy=:Feed_privacy ";
                $params[] = array( "key" => ":Feed_privacy",
                                    "value" =>$options['feed_privacy'] );
            }
	}
        if( !is_null($options['published']) ){
		if($where != '') $where .= " AND ";
//		$where .= " NF.published IN ( {$options['published']} ) ";
                $where .= " find_in_set(cast(NF.published as char), :Published) ";
                $params[] = array( "key" => ":Published", "value" =>$options['published'] );
	}
	if( $options['is_visible'] != -1 ){
            if($options['is_visible'] == 1 && intval($options['userid']) !=0 ){
                if( $where != '') $where .= ' AND ';
//                $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
                $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid14)";
                $params[] = array( "key" => ":Userid14",
                                    "value" =>$options['userid'] );
            }else if($options['is_visible'] == 1 && $options['channel_id'] && intval($options['channel_id']) > 0 ){
                $channelInfo=channelGetInfo($options['channel_id']);
		$owner_id=$channelInfo['owner_id'];
                if( $where != '') $where .= ' AND ';
//                $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='$owner_id')";
                $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id)";
                $params[] = array( "key" => ":Owner_id",
                                    "value" =>$owner_id);
            }else if($options['is_visible'] == 0 && intval($options['userid']) !=0 ){
                if( $where != '') $where .= ' AND ';
//                $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
                $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid15)";
                $params[] = array( "key" => ":Userid15",
                                    "value" =>$options['userid'] );
            }else if($options['is_visible'] == 0 && $options['channel_id'] && intval($options['channel_id']) > 0 ){
                $channelInfo=channelGetInfo($options['channel_id']);
		$owner_id=$channelInfo['owner_id'];
                if( $where != '') $where .= ' AND ';
//                $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='$owner_id')";
                $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id2)";
                $params[] = array( "key" => ":Owner_id2",
                                    "value" =>$owner_id);
            }
	}
	if( $options['entity_type'] ){
		if( $where != '') $where .= ' AND ';
//		$where .= " NF.entity_type='{$options['entity_type']}' ";
		$where .= " NF.entity_type=:Entity_type ";
                $params[] = array( "key" => ":Entity_type",
                                    "value" =>$options['entity_type'] );		
	}
	if( $options['entity_id'] ){
		if( $where != '') $where .= ' AND ';
//		$where .= " NF.entity_id='{$options['entity_id']}' ";	
		$where .= " NF.entity_id=:Entity_id ";	
                $params[] = array( "key" => ":Entity_id",
                                    "value" =>$options['entity_id'] );	
	}
	if( $options['action_type'] ){
		if( $where != '') $where .= ' AND ';
//		$where .= " NF.action_type='{$options['action_type']}' ";
		$where .= " NF.action_type=:Action_type ";
                $params[] = array( "key" => ":Action_type",
                                    "value" =>$options['action_type'] );	
	}
	
	if( $options['show_owner'] && $options['show_owner']==0 ){
		if( $options['channel_id'] && $options['channel_id'] !=-1 ){
			$channelInfo=channelGetInfo($options['channel_id']);
			$owner_id=$channelInfo['owner_id'];
		}else{
			$owner_id=intval($options['userid']);
		}
		if( intval($options['userid']) !=0 && $options['is_notification'] !=1 ){			
			if($where != '') $where .= " AND ";
//			$where .= " NF.entity_type<>".SOCIAL_ENTITY_BAG." AND ( NF.user_id<>'{$options['userid']}' OR ( NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 AND ((PO.from_id<>0 AND PO.from_id<>".$options['userid'].") OR (PO.from_id=0 AND PO.user_id<>".$options['userid'].")) ) ) ) ";
			$where .= " NF.entity_type<>".SOCIAL_ENTITY_BAG." AND ( NF.user_id<>:Userid16 OR ( NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS ( SELECT id FROM cms_social_posts AS PO WHERE PO.id=NF.entity_id AND PO.published=1 AND ((PO.from_id<>0 AND PO.from_id<>:Userid16) OR (PO.from_id=0 AND PO.user_id<>:Userid16)) ) ) ) ";
                        $params[] = array( "key" => ":Userid16",
                                            "value" =>$options['userid'] );
		}else{
			if( !$options['channel_id'] && $options['show_owner'] && $options['show_owner']==0 && intval($options['userid']) !=0 && $options['is_notification'] ==1 ){
				if($where != '') $where .= " AND ";
				$where .= " ( (NF.user_id<>:Owner_id4 AND NF.feed_privacy<>0) OR (NF.user_id<>:Userid17 AND NF.owner_id=:Userid17 AND NF.feed_privacy=0 AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." AND EXISTS ( SELECT CO.id FROM cms_social_comments AS CO WHERE CO.id=NF.entity_id AND CO.published=1 AND COALESCE(CO.channel_id, 0) = 0 ) ) OR (NF.user_id=:Userid17 AND NF.feed_privacy=0) OR (NF.user_id<>:Userid17 AND NF.owner_id<>:Userid17 AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS (SELECT PS.id FROM cms_social_posts AS PS WHERE PS.user_id =:Userid17 AND PS.id= NF.entity_id ) ) ) ";
                                $params[] = array( "key" => ":Owner_id4",
                                            "value" =>$owner_id );
                                $params[] = array( "key" => ":Userid17",
                                                    "value" =>$options['userid'] );
			}else{
				if( !$options['channel_id'] && $options['show_owner'] && $options['show_owner']==0 && intval($options['userid'])==0 && $options['is_notification'] == 1 ){
					if($where != '') $where .= " AND ";
					$where .= " ( (NF.user_id<>NF.owner_id AND NF.feed_privacy<>0) OR ( NF.feed_privacy=0 AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." ) ) ";
				}else{
					if($where != '') $where .= " AND ";
//					$where .= " NF.user_id<>'$owner_id' ";
					$where .= " NF.user_id<>:Owner_id5 ";
                                        $params[] = array( "key" => ":Owner_id5",
                                                            "value" =>$owner_id );
				}
			}
		}
		
	}else if( $options['show_owner'] && $options['show_owner']==1 ){
            if( $options['channel_id'] && $options['channel_id'] !=-1 ){
                $channelInfo=channelGetInfo($options['channel_id']);
                $owner_id= intval($channelInfo['owner_id']);
            }else{

                $owner_id=intval($options['userid']);
            }
            if( intval($options['userid']) !=0 ){			
                if($where != '') $where .= " AND ";
                $where .= " ( (NF.action_type<>".SOCIAL_ACTION_SHARE." AND NF.action_type<>".SOCIAL_ACTION_SPONSOR." AND NF.action_type<>".SOCIAL_ACTION_INVITE.") OR ( (NF.action_type=".SOCIAL_ACTION_SHARE." OR NF.action_type=".SOCIAL_ACTION_SPONSOR." OR NF.action_type=".SOCIAL_ACTION_INVITE.") AND ( NF.feed_privacy<>".USER_PRIVACY_PRIVATE." OR (NF.feed_privacy=".USER_PRIVACY_PRIVATE." AND EXISTS (select SHR.id from cms_social_shares AS SHR where SHR.id=NF.action_id AND SHR.from_user=NF.user_id)) ) ) ) ";
            }
            if($owner_id!=0){
                if($where != '') $where .= " AND ";
                $where .= " NF.user_id='$owner_id'";  
            }		
	}
	
	if( !$options['channel_id'] ){
            if( $options['show_owner'] && $options['show_owner']==0 && intval($options['userid']) !=0 && $options['is_notification'] !=1 ){
//                $check_us_notify = "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id='{$options['userid']}' AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND notify=0 AND published='1')";
                $check_us_notify = "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid18 AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND notify=0 AND published='1')";
//                $check_ch_notify = "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id='{$options['userid']}' AND receiver_id=NF.channel_id AND is_channel=1 AND poster_is_channel=0 AND notify=0 AND published='1')";
                $check_ch_notify = "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid18 AND receiver_id=NF.channel_id AND is_channel=1 AND poster_is_channel=0 AND notify=0 AND published='1')";
//                $check_sp_ch_notify = "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id='{$options['userid']}' AND receiver_id=NF.user_id AND is_channel=1 AND poster_is_channel=0 AND notify=0 AND published='1')";
                $check_sp_ch_notify = "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid18 AND receiver_id=NF.user_id AND is_channel=1 AND poster_is_channel=0 AND notify=0 AND published='1')";
                $where .= "AND ( (COALESCE(NF.channel_id, 0) = 0 ".$check_us_notify." ) OR (COALESCE(NF.channel_id, 0) AND NF.action_type <>".SOCIAL_ACTION_SPONSOR." AND ( (EXISTS (SELECT id FROM cms_channel AS CH WHERE CH.id=NF.channel_id AND CH.owner_id<>NF.user_id AND CH.published='1') ".$check_us_notify." ) OR (EXISTS (SELECT id FROM cms_channel AS CH WHERE CH.id=NF.channel_id AND CH.owner_id=NF.user_id AND CH.published='1') ".$check_ch_notify." ) ) ) OR (COALESCE(NF.channel_id, 0) AND NF.action_type =".SOCIAL_ACTION_SPONSOR." ".$check_sp_ch_notify." ) ) ";
                $params[] = array( "key" => ":Userid18",
                                    "value" =>$options['userid'] );

            }else{
                if($where != '') $where .= " AND ";
                $where .= " COALESCE(NF.channel_id, 0) = 0 ";
                if( $options['is_notification'] !=1 && intval($options['userid']) !=0){
//                    $where .= "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id='{$options['userid']}' AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND notify=0 AND published='1') ";
                    $where .= "AND NOT EXISTS (SELECT poster_id FROM cms_social_notifications WHERE poster_id=:Userid19 AND receiver_id=NF.user_id AND is_channel=0 AND poster_is_channel=0 AND notify=0 AND published='1') ";
                    $params[] = array( "key" => ":Userid19",
                                    "value" =>$options['userid'] );
                }
            }
		
	}else if ($options['channel_id'] != -1){
		if($where != '') $where .= " AND ";
//		$where .= " NF.channel_id='{$options['channel_id']}' ";	
		$where .= " NF.channel_id=:Channel_id ";	
                $params[] = array( "key" => ":Channel_id",
                                    "value" =>$options['channel_id'] );
		
		if( $options['show_owner'] && $options['show_owner']==1 && $options['channel_id'] && !$options['action_type'] ){
			if($where != '') $where .= " AND ";
			$where .= " NF.action_type<>".SOCIAL_ACTION_INVITE." ";
			
			$where = ' ( '.$where.' ) ';
//			$where .= " OR ( NF.user_id='{$options['channel_id']}' AND COALESCE(NF.channel_id, 0) AND NF.action_type= ".SOCIAL_ACTION_SPONSOR." AND NF.published IN ( {$options['published']} ) ";
                        $where .= " OR ( NF.user_id=:Channel_id2 AND COALESCE(NF.channel_id, 0) AND NF.action_type= ".SOCIAL_ACTION_SPONSOR." AND find_in_set(cast(NF.published as char), :Published) ";
                        $params[] = array( "key" => ":Channel_id2",
                                            "value" =>$options['channel_id'] );
                        $params[] = array( "key" => ":Published",
                                            "value" =>$options['published'] );
			if( $options['entity_type'] ){
				if( $where != '') $where .= ' AND ';
//				$where .= " NF.entity_type='{$options['entity_type']}' ";
				$where .= " NF.entity_type=:Entity_type ";
                                $params[] = array( "key" => ":Entity_type",
                                                    "value" =>$options['entity_type'] );				
			}
			if( $options['entity_id'] ){
				if( $where != '') $where .= ' AND ';
//				$where .= " NF.entity_id='{$options['entity_id']}' ";	
				$where .= " NF.entity_id=:Entity_id ";	
                                $params[] = array( "key" => ":Entity_id",
                                                    "value" =>$options['entity_id'] );			
			}
			if( $options['is_visible'] != -1 ){
                            if($options['is_visible'] == 1 && intval($options['userid']) !=0 ){
                                if( $where != '') $where .= ' AND ';
//                                $where .= " NOT EXISTS (SELECTfeed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
                                $where .= " NOT EXISTS (SELECTfeed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid20)";
                                $params[] = array( "key" => ":Userid20",
                                                    "value" =>$options['userid'] );	
                            }else if($options['is_visible'] == 1 && $options['channel_id'] && intval($options['channel_id']) > 0 ){
                                $channelInfo=channelGetInfo($options['channel_id']);
                                $owner_id=$channelInfo['owner_id'];
                                if( $where != '') $where .= ' AND ';
                                $where .= " NOT EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id8)";
                                $params[] = array( "key" => ":Owner_id8",
                                                    "value" =>$owner_id);	
                            }else if($options['is_visible'] == 0 && intval($options['userid']) !=0 ){
                                if( $where != '') $where .= ' AND ';
//                                $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='{$options['userid']}')";
                                $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Userid21)";
                                $params[] = array( "key" => ":Userid21",
                                                    "value" =>$options['userid'] );
                            }else if($options['is_visible'] == 0 && $options['channel_id'] && intval($options['channel_id']) > 0 ){
                                $channelInfo=channelGetInfo($options['channel_id']);
                                $owner_id=$channelInfo['owner_id'];
                                if( $where != '') $where .= ' AND ';
//                                $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id='$owner_id')";
                                $where .= " EXISTS (SELECT feed_id FROM cms_social_newsfeed_hide WHERE feed_id=NF.id AND user_id=:Owner_id9)";
                                $params[] = array( "key" => ":Owner_id9",
                                                    "value" =>$owner_id);
                            }                         
			}
			$where .= ') ';
		}
	}
	if($options['channel_id'] == -1){
		if( $where != '') $where .= ' AND ';
		$where .= " COALESCE(NF.channel_id, 0) ";
		if( $options['is_notification'] == 1 ){
                    if($where != '') $where .= " AND ";
                    $where .= " NF.user_id <> NF.owner_id ";
		}
	}
	
	
	if($options['from_ts'] || $options['to_ts']){
		if( $where != ''){
			 $where = " ( ".$where." ) ";			 
		}
		if($options['from_ts']){
			if( $where != '') $where .= " AND ";
//			$where .= " DATE(NF.feed_ts) >= '{$options['from_ts']}' ";
			$where .= " DATE(NF.feed_ts) >= :From_ts ";
                        $params[] = array( "key" => ":From_ts",
                                            "value" =>$options['from_ts']);
		}
		if($options['to_ts']){
			if( $where != '') $where .= " AND ";
//			$where .= " DATE(NF.feed_ts) <= '{$options['to_ts']}' ";
			$where .= " DATE(NF.feed_ts) <= :To_ts ";
                        $params[] = array( "key" => ":To_ts",
                                            "value" =>$options['to_ts']);
		}
	}
	
	if($options['from_time'] || $options['to_time']){
		if( $where != ''){
			 $where = " ( ".$where." ) ";			 
		}
		if($options['from_time']){
			if( $where != '') $where .= " AND ";
//			$where .= " (NF.feed_ts) >= '{$options['from_time']}' ";
			$where .= " (NF.feed_ts) >= :From_time ";
                        $params[] = array( "key" => ":From_time",
                                            "value" =>$options['from_time']);
		}
		if($options['to_time']){
			if( $where != '') $where .= " AND ";
//			$where .= " (NF.feed_ts) <= '{$options['to_time']}' ";
			$where .= " (NF.feed_ts) <= :To_time ";
                        $params[] = array( "key" => ":To_time",
                                            "value" =>$options['to_time']);
		}
	}
	
	if(!$options['channel_id']){
		if( $where != ''){
			 $where = " ( ".$where." ) ";			 
		}
		if( $options['show_owner'] && $options['show_owner']==0 && intval($options['userid']) !=0 && $options['is_notification'] !=1 ){
			if($where != '') $where .= " AND ";
			$where .= " NF.action_type<>".SOCIAL_ACTION_INVITE." ";			
		}else{
			if( $options['is_notification'] !=1 ){
				if($where != '') $where .= " AND ";
				$where .= " NF.action_type<>".SOCIAL_ACTION_SPONSOR." ";
			}
		}		
	}
	if(!$options['action_type']){
		if($where != '') $where .= " AND ";
		$where .= " NF.action_type<>".SOCIAL_ACTION_UNFRIEND." AND NF.action_type<>".SOCIAL_ACTION_UNFOLLOW." ";
	}
	
	if( $options['show_owner'] && $options['show_owner']==0 && intval($options['userid']) !=0 && $options['is_notification'] ==1 ){
		if( $where != '') $where .= " AND ";
//		$where .= " ( (NF.owner_id = '{$options['userid']}' AND NF.user_id<>'{$options['userid']}') OR (NF.user_id<>'{$options['userid']}' AND NF.owner_id='{$options['userid']}' AND NF.feed_privacy=0 AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." ) OR (NF.user_id='{$options['userid']}' AND NF.feed_privacy=0 AND NF.owner_id <> '{$options['userid']}' AND NF.action_type=".SOCIAL_ACTION_COMMENT." AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id<>'{$options['userid']}') ) OR (NF.user_id='{$options['userid']}' AND NF.feed_privacy=0 AND NF.owner_id <> '{$options['userid']}' AND ( NF.action_type =".SOCIAL_ACTION_SPONSOR." OR NF.action_type =".SOCIAL_ACTION_SHARE." OR NF.action_type =".SOCIAL_ACTION_INVITE." ) AND NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user='{$options['userid']}') ) OR (NF.user_id<>'{$options['userid']}' AND NF.owner_id<>'{$options['userid']}' AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS (SELECT PS.id FROM cms_social_posts AS PS WHERE PS.user_id ='{$options['userid']}' AND PS.id= NF.entity_id ) ) ) ";
                $where .= " ( (NF.owner_id = :Userid22 AND NF.user_id <>:Userid22) OR (NF.user_id<>:Userid22 AND NF.owner_id=:Userid22 AND NF.feed_privacy=0 AND NF.entity_type=".SOCIAL_ENTITY_COMMENT." ) OR (NF.user_id=:Userid22 AND NF.feed_privacy=0 AND NF.owner_id <> :Userid22 AND NF.action_type=".SOCIAL_ACTION_COMMENT." AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id<>:Userid22) ) OR (NF.user_id=:Userid22 AND NF.feed_privacy=0 AND NF.owner_id <> :Userid22 AND ( NF.action_type =".SOCIAL_ACTION_SPONSOR." OR NF.action_type =".SOCIAL_ACTION_SHARE." OR NF.action_type =".SOCIAL_ACTION_INVITE." ) AND NOT EXISTS (SELECT id FROM cms_social_shares AS SH WHERE SH.id=NF.action_id AND SH.from_user=:Userid22) ) OR (NF.user_id<>:Userid22 AND NF.owner_id<>:Userid22 AND NF.entity_type=".SOCIAL_ENTITY_POST." AND NF.action_type=".SOCIAL_ACTION_UPLOAD." AND EXISTS (SELECT PS.id FROM cms_social_posts AS PS WHERE PS.user_id =:Userid22 AND PS.id= NF.entity_id ) ) ) ";
                $params[] = array( "key" => ":Userid22",
                                    "value" =>$options['userid']);
	}else if( $options['show_owner'] && $options['show_owner']==1 && intval($options['userid']) !=0 && $options['is_notification'] !=1 ){
		if( $where != '') $where .= " AND ";
//		$where .= " ( CASE WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." THEN (NF.user_id='{$options['userid']}' AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id='{$options['userid']}') ) ELSE 1 END ) ";
		$where .= " ( CASE WHEN NF.action_type=".SOCIAL_ACTION_COMMENT." THEN (NF.user_id=:Userid23 AND EXISTS (SELECT id FROM cms_social_comments AS CM WHERE CM.id=NF.action_id AND CM.user_id=:Userid23) ) ELSE 1 END ) ";
                $params[] = array( "key" => ":Userid23",
                                    "value" =>$options['userid']);
	}
	if($where != '') $where = " WHERE $where ";
        global $CONFIG_EXEPT_ARRAY;
        $exept_array = $CONFIG_EXEPT_ARRAY;
        $where .= " NF.entity_type NOT IN(". implode(',', $exept_array) .") ";
        
	// Case of returning usual results.
	if(!$options['n_results']){
		if($orderby=='most_liked'){
			$query = "SELECT
						NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender, ( SELECT COUNT(SL.id) FROM cms_social_likes AS SL WHERE SL.entity_id=NF.entity_id AND SL.entity_type=NF.entity_type AND SL.like_value=1 AND SL.published=1 ) AS counter
					FROM
						`cms_social_newsfeed` AS NF
						LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR."
                       
					$where ORDER BY counter $order";
		}else{
			$query = "SELECT
						NF.*,U.YourUserName,U.FullName,U.display_fullname,U.profile_Pic,U.gender
					FROM
						`cms_social_newsfeed` AS NF
						LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR."
					$where ORDER BY $orderby $order";
		}
		if( $options['limit'] ){
//			$query .= " LIMIT $skip, $nlimit";
			$query .= " LIMIT :Skip, :Nlimit";
                        $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
                        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
		}
                
		//return $query;
		
		
		
//		$ret = db_query($query);
                $select = $dbConn->prepare($query);
                PDO_BIND_PARAM($select,$params);
                $res    = $select->execute();

                $ret    = $select->rowCount();
		
		///////////////////////////////
		//get arrays of all the entities to fetch them one.
		//TODO repalace with the cached get functions such as getVideoInfo(), userCatalogGet(), etc ...
		
		$feed = array();
//		while($row = db_fetch_assoc($ret)){
                
                $row = $select->fetchAll(PDO::FETCH_ASSOC);
                foreach($row as $row_item){
                    $feed_row = $row_item;			
                    switch( $feed_row['action_type'] ){
                        case SOCIAL_ACTION_COMMENT:
                            $feed_row['action_row'] = socialCommentRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_LIKE:
                            $feed_row['action_row'] = socialLikeRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_RATE:
                            $feed_row['action_row'] = socialRateRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_INVITE:
                        case SOCIAL_ACTION_SHARE:
                                $feed_row['action_row'] = socialShareGet($feed_row['action_id']);
                                break;
                        case SOCIAL_ACTION_REECHOE:
                            $feed_row['action_row'] = socialReechoeRow($feed_row['action_id']);
                        break;
                        case SOCIAL_ACTION_UPLOAD:
                            $feed_row['action_row'] = array();
                            //$feed_row['action_row']['entity_type'] = SOCIAL_ENTITY_MEDIA;
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                        break;
                        case SOCIAL_ACTION_RELATION_PARENT:
                        case SOCIAL_ACTION_RELATION_SUB:
                            $feed_row['action_row'] = channelRelationRow($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                        break;
                        case SOCIAL_ACTION_SPONSOR:
                            //in case of sponsor newsfeed.user_id is actaully the channel_id
                            $channel_id = $feed_row['user_id'];
                            $channelInfo = channelGetInfo($channel_id);
                            $feed_row['channel_row'] = $channelInfo;
                            $feed_row['action_row'] = socialShareGet($feed_row['action_id']);

                            $sp_info = socialShareGet($feed_row['action_id']);
                            $feed_row['action_row']['msg'] = $sp_info['msg'];

                            //we dont have user info
                            unset($feed_row['YourUserName']);
                            unset($feed_row['FullName']);
                            unset($feed_row['display_fullname']);
                            unset($feed_row['profile_Pic']);
                            unset($feed_row['gender']);
                        break;
                        case SOCIAL_ACTION_EVENT_JOIN:
                            $feed_row['join_row'] = joinEventInfo($feed_row['action_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                        case SOCIAL_ACTION_FRIEND:
                        case SOCIAL_ACTION_FOLLOW:
                            $feed_row['action_row'] = getUserInfo($feed_row['user_id']);
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];		
                        break;
                        default:
                            $feed_row['action_row']['entity_type'] = $feed_row['entity_type'];
                            $feed_row['action_row']['entity_id'] = $feed_row['entity_id'];			
                        break;
                    }
			
                    if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_ALBUM ){
                        //in case album
                        $catalog_row = userCatalogDefaultMediaGet($feed_row['action_row']['entity_id']);
                        if(!$catalog_row) $catalog_row = array();
                        $feed_row['media_row'] = $catalog_row;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                    }else if( ($feed_row['action_type'] == SOCIAL_ACTION_LIKE) && ($feed_row['entity_type'] == SOCIAL_ENTITY_COMMENT) ){
                        //in case like a comment
                        $cr = socialCommentRow($feed_row['entity_id']);
                        $feed_row['media_row'] = socialEntityInfo($cr['entity_type'], $cr['entity_id']);

                        $feed_row['original_media_row'] = $cr;

                        $feed_row['original_entity_type'] = $feed_row['entity_type'];
                        $feed_row['original_entity_id'] = $feed_row['entity_id'];

                        $feed_row['entity_type'] = $cr['entity_type'];
                        $feed_row['entity_id'] = $cr['entity_id'];
                    }else if( ($feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_LOCATION || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_DATE || $feed_row['entity_type'] == SOCIAL_ENTITY_EVENTS_TIME) &&  !$feed_row['channel_id'] ){
                            $feed_row['media_row'] = socialEntityInfo(SOCIAL_ENTITY_USER_EVENTS, $feed_row['action_row']['entity_id']);
                    }else if( $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_COVER || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_INFO || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_PROFILE || $feed_row['action_row']['entity_type'] == SOCIAL_ENTITY_CHANNEL_SLOGAN ){
                            //$feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['channel_id']);
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_id']);
                            }else{
                                    $feed_row['media_row'] = GetChannelDetailInfo($feed_row['action_row']['entity_id']);
                            }
                    }else {
                            //just get the media row
                            if( $feed_row['action_type'] == SOCIAL_ACTION_UPDATE ){
                                    $feed_row['action_row']['entity_id'] = $feed_row['action_id'];
                            }
                            $feed_row['media_row'] = socialEntityInfo($feed_row['action_row']['entity_type'], $feed_row['action_row']['entity_id']);
                            if( $feed_row['entity_type'] == SOCIAL_ENTITY_VISITED_PLACES ){
                                $stateinfo = worldStateInfo($feed_row['media_row']['country_code'],$feed_row['media_row']['state_code']);
                                $state_name = (!$stateinfo)? '' : $stateinfo['state_name'];
                                $country_name= countryGetName($feed_row['media_row']['country_code']);
                                $country_name = (!$country_name)? '' : $country_name;
                                $feed_row['media_row']['state_name']=$state_name;
                                $feed_row['media_row']['country_name']=$country_name;
                            }
                    }
			
			///////////////////////
			//in case no profile pic and not the channel sponsor action
			if( ( !isset($feed_row['profile_Pic']) || $feed_row['profile_Pic'] == '' ) && ($feed_row['action_type'] != SOCIAL_ACTION_SPONSOR) ){
				$feed_row['profile_Pic'] = 'he.jpg';
				if($feed_row['gender']=='F'){
					$feed_row['profile_Pic'] = 'she.jpg';
				}
			}
			
			$feed[] = $feed_row;
		}
		
		return $feed;
		
	// Case of returning n_results.
	} else {
		$query = "SELECT
					count(*)
				FROM
					`cms_social_newsfeed` AS NF
					LEFT JOIN cms_users AS U ON U.id=NF.owner_id AND NF.action_type<>".SOCIAL_ACTION_SPONSOR."
				$where";
		
                //return $query;
//		$ret = db_query($query);
//		$row = db_fetch_row($ret);
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
            $row = $select->fetch();
		
		return $row[0];
	}
//  Changed by Anthony Malak 09-05-2015 to PDO database

}

/**
 * Flags the new newsfeed items as viewed changing their published flag from 0 to 1.
 * @param integer $channel_id the channel id.
 * @param integer $user_id the user id.
 * @return boolean true on success, false on failure.
 */
function newsfeedMarkAsViewed($channel_id, $user_id){
    global $dbConn;
    $params = array();  
    $query = "UPDATE cms_social_newsfeed SET published = 1
                    WHERE channel_id = :Channel_id AND user_id <> :User_id AND published = 0";
    $params[] = array( "key" => ":Channel_id", "value" =>$channel_id);
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;
}
/**
 * Flags the new newsfeed items as viewed changing their published flag from 0 to 1.
 * @param integer $id the feed id.
 * @return boolean true on success, false on failure.
 */
function newsfeedMarkAsViewedTT($id){
    global $dbConn;
    $params = array();  
    $query = "UPDATE cms_social_newsfeed SET published = 1 WHERE id = :Id AND published = 0";
    $params[] = array( "key" => ":Id", "value" =>$id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;
}



/**
 * Flags the new newsfeed items as viewed changing their published flag from 0 to 1.
 * @param integer $user_id the user id.
 * @param boolean $echoes the user id.
 * @return boolean true on success, false on failure.
 */
function newsfeedMarkAsViewedAllTT($user_id, $echoes = false){
    global $dbConn;
    $params = array();  
    if($echoes){
        $query = "UPDATE cms_social_newsfeed SET published = 1 WHERE owner_id = :User_id AND user_id <> owner_id AND entity_type = 8 AND COALESCE(channel_id, 0) = 0 AND published = 0";
    }
    else{
        $query = "UPDATE cms_social_newsfeed SET published = 1 WHERE owner_id = :User_id AND user_id <> owner_id AND entity_type <> 8 AND COALESCE(channel_id, 0) = 0 AND published = 0";
    }
    $params[] = array( "key" => ":User_id", "value" =>$user_id);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;
}


/**
 * set the notification status between a user/channel and a poster
 * @param array $set_options options to search. options include:<br/>
 * <b>poster_id</b>: the cms_users id of the poster<br/>
 * <b>receiver_id</b>: the cms_users/cms_channel id of the receiver of the post<br/>
 * <b>is_channel</b>: specifies what tbale receiver_id points to. 0 => cms_users. 1=> cms_channel<br/>
 * <b>notify</b>: the value to set the notification to.<br/>
 * @return boolean true | false
 */
function socialNotificationSet($set_options){


	global $dbConn;
	$params  = array(); 
	$params2 = array();
	
	$default_opts = array(
		'poster_id' => 10,
		'receiver_id' => 0,
		'is_channel' => 1,
		'notify' => 1,
		'show_from_tuber' => 1,
                'poster_is_channel' => 0,
		'published' => 1
	);

	$options = array_merge($default_opts, $set_options);
        $query = "SELECT * FROM cms_social_notifications WHERE poster_id=:Poster_id AND receiver_id=:Receiver_id AND poster_is_channel=:Poster_is_channel AND is_channel=:Is_channel AND published='1'";
	$params[] = array( "key" => ":Poster_id", "value" =>$options['poster_id']);
	$params[] = array( "key" => ":Receiver_id", "value" =>$options['receiver_id']);
	$params[] = array( "key" => ":Poster_is_channel", "value" =>$options['poster_is_channel']);
	$params[] = array( "key" => ":Is_channel", "value" =>$options['is_channel']);
//	$res = db_query($query);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
	
//	if( !$res || db_num_rows($res) == 0){
	if( !$res || $ret == 0){
            $query = "INSERT INTO cms_social_notifications (poster_id,receiver_id,is_channel,poster_is_channel,notify,show_from_tuber,published) VALUES (:Poster_id,:Receiver_id,:Is_channel,:Poster_is_channel,:Notify,:Show_from_tuber,:Published)";
            $params2[] = array( "key" => ":Poster_id","value" =>$options['poster_id']);
            $params2[] = array( "key" => ":Receiver_id","value" =>$options['receiver_id']);
            $params2[] = array( "key" => ":Poster_is_channel","value" =>$options['poster_is_channel']);
            $params2[] = array( "key" => ":Is_channel","value" =>$options['is_channel']);
            $params2[] = array( "key" => ":Notify","value" =>$options['notify']);
            $params2[] = array( "key" => ":Show_from_tuber","value" =>$options['show_from_tuber']);
            $params2[] = array( "key" => ":Published","value" =>$options['published']);
	}else{
            $toset='';
            if( isset($set_options['notify']) ) {
//                    $toset .=" notify='{$set_options['notify']}'";
                    $toset .=" notify=:Notify";
                    $params2[] = array( "key" => ":Notify", "value" =>$set_options['notify']);
            }
            if( isset($set_options['show_from_tuber']) ){
                if( $toset!='' ) $toset .=", ";
//                $toset .=" show_from_tuber='{$set_options['show_from_tuber']}'";
                $toset .=" show_from_tuber=:Show_from_tuber";
                $params2[] = array( "key" => ":Show_from_tuber", "value" =>$set_options['show_from_tuber']);
            }
            $query = "UPDATE cms_social_notifications SET ".$toset."
                            WHERE
                                poster_id=:Poster_id
                                AND receiver_id=:Receiver_id
                                AND is_channel=:Is_channel
                                AND poster_is_channel=:Poster_is_channel  
                                AND published=:Published";
                $params2[] = array( "key" => ":Poster_id", "value" =>$options['poster_id']);
                $params2[] = array( "key" => ":Receiver_id", "value" =>$options['receiver_id']);
                $params2[] = array( "key" => ":Poster_is_channel", "value" =>$options['poster_is_channel']);
                $params2[] = array( "key" => ":Is_channel", "value" =>$options['is_channel']);
                $params2[] = array( "key" => ":Published", "value" =>$options['published']);
	}		 
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params2);
	$res    = $select->execute();
        return $res;
}

/**
 * get the notification status between a user/channel and a poster
 * @param array $get_options options to search. options include:<br/>
 * <b>poster_id</b>: the cms_users id of the poster<br/>
 * <b>receiver_id</b>: the cms_users/cms_channel id of the receiver of the post<br/>
 * <b>is_channel</b>: specifies what tbale receiver_id points to. 0 => cms_users. 1=> cms_channel<br/>
 * <b>notify</b>: the value to set the notification to.<br/>
 * @return boolean true | false if can receive notifications or not 
 */
function socialNotificationGet($get_options){


	global $dbConn;
	$params = array();
	
	$default_opts = array(
		'poster_id' => null,
		'receiver_id' => null,
		'is_channel' => 1,
		'notify' => 1,
                'poster_is_channel' => 0,
		'published' => 1
	);

	$options = array_merge($default_opts, $get_options);
//	$query = "SELECT notify FROM cms_social_notifications WHERE poster_id='{$options['poster_id']}' AND receiver_id='{$options['receiver_id']}' AND is_channel='{$options['is_channel']}' AND poster_is_channel='{$options['poster_is_channel']}' AND published='1'";
//	$res = db_query($query);
//	if( !$res || db_num_rows($res) == 0){
//		return true;
//	}else{
//		$row = db_fetch_assoc($res);
//		return ($row['notify'] == 1);
//	}
	$query = "SELECT notify FROM cms_social_notifications WHERE poster_id=:Poster_id AND receiver_id=:Receiver_id AND is_channel=:Is_channel AND poster_is_channel=:Poster_is_channel AND published='1'";
	$params[] = array( "key" => ":Poster_id",
                            "value" =>$options['poster_id']);
	$params[] = array( "key" => ":Receiver_id",
                            "value" =>$options['receiver_id']);
        $params[] = array( "key" => ":Is_channel",
                            "value" =>$options['is_channel']);
	$params[] = array( "key" => ":Poster_is_channel",
                            "value" =>$options['poster_is_channel']);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
	if( !$res || $ret == 0){
            return true;
	}else{
		$row = $select->fetch(PDO::FETCH_ASSOC);
            return ($row['notify'] == 1);
	}


	
}
/**
 * get the notification status between a user/channel and a poster
 * @param array $get_options options to search. options include:<br/>
 * <b>poster_id</b>: the cms_users id of the poster<br/>
 * <b>receiver_id</b>: the cms_users/cms_channel id of the receiver of the post<br/>
 * <b>is_channel</b>: specifies what tbale receiver_id points to. 0 => cms_users. 1=> cms_channel<br/>
 * <b>notify</b>: the value to set the notification to.<br/>
 * @return array or false
 */
function socialNotificationSearch($get_options){


	global $dbConn;
	$params = array(); 
	
	$default_opts = array(
		'poster_id' => null,
		'receiver_id' => null,
		'is_channel' => null,
		'notify' => null,
                'show_from_tuber' => null,
                'poster_is_channel' => 0,
		'published' => 1
	);

	$options = array_merge($default_opts, $get_options);
	$where ='';
        if( $options['poster_id'] ){
            if($where != '') $where .= " AND";
//            $where .= " poster_id='{$options['poster_id']}'";
            $where .= " poster_id=:Poster_id";
            $params[] = array( "key" => ":Poster_id",
                                "value" =>$options['poster_id']);
        }
        if( $options['receiver_id'] ){
            if($where != '') $where .= " AND";
//            $where .= " receiver_id='{$options['receiver_id']}'";
            $where .= " receiver_id=:Receiver_id";
            $params[] = array( "key" => ":Receiver_id",
                                "value" =>$options['receiver_id']);
        }
        if( $options['is_channel'] ){
            if($where != '') $where .= " AND";
//            $where .= " is_channel='{$options['is_channel']}'";
            $where .= " is_channel=:Is_channel";
            $params[] = array( "key" => ":Is_channel",
                                "value" =>$options['is_channel']);
        }
        if($where != '') $where .= " AND";
//        $where .= " poster_is_channel='{$options['poster_is_channel']}'";
        $where .= " poster_is_channel=:Poster_is_channel";
        $params[] = array( "key" => ":Poster_is_channel",
                            "value" =>$options['poster_is_channel']);
        if( $options['notify'] ){
            if($where != '') $where .= " AND";
//            $where .= " notify='{$options['notify']}'";
            $where .= " notify=:Notify";
            $params[] = array( "key" => ":Notify",
                                "value" =>$options['notify']);
        }
        if( $options['show_from_tuber'] ){
            if($where != '') $where .= " AND";
//            $where .= " show_from_tuber='{$options['show_from_tuber']}'";
            $where .= " show_from_tuber=:Show_from_tuber";
            $params[] = array( "key" => ":Show_from_tuber",
                                "value" =>$options['show_from_tuber']);
        }
        if($where != '') $where .= " AND";
        $where .= " published='1'";
	$query = "SELECT * FROM cms_social_notifications WHERE ".$where." ORDER BY create_ts DESC";
//        $res = db_query($query);
        
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
	
//	if( !$res || db_num_rows($res) == 0){
	if( !$res || $ret == 0){
		return false;
	}else{
            $ret_arr = array();
//            while($row = db_fetch_array($res)){
//                    $ret_arr[] = $row;
//            }
            $ret_arr = $select->fetchAll();
            return $ret_arr;
	}


	
}
/**
 * gets the email notification for a given user 
 * @param integer $user_id the user id
 * @param integer $action_type {SOCIAL_ACTION_COMMENT, SOCIAL_ACTION_CONNECT, ...}
 * @param string $entity_type the type of the entity
 * @return true | false
 */
function socialUsersEmailNotificationGet($user_id,$action_type,$entity_type){


	global $dbConn;
	$params = array();
	$data_notification=getUserNotifications($user_id);
	$data_notification=$data_notification[0];
	if(!$data_notification['email_notifications']){
		return false;
	}	
	$userInfo = getUserInfo($user_id);
	if( $userInfo['otherEmail'] !=''){
            $to_email = $userInfo['otherEmail'];
        }else{
            $to_email = $userInfo['YourEmail'];
        }
//	$query = "SELECT notify FROM cms_notifications_emails WHERE email='".$to_email."' LIMIT 1";
        $query = "SELECT notify FROM cms_notifications_emails WHERE email=:To_email LIMIT 1";
//	$res = db_query($query);
	$params[] = array( "key" => ":To_email",
                            "value" =>$to_email);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//	if( $res && db_num_rows($res) != 0){
	if( $res && $ret != 0){
//            $row = db_fetch_assoc($res);
            $row = $select->fetch(PDO::FETCH_ASSOC);
            $notify = ($row['notify'] == 1);
            if( !$notify ) return false;
	}
	switch($action_type){
            case SOCIAL_ACTION_SPONSOR:
                return true;
                break;
            case SOCIAL_ACTION_EVENT_CANCEL:
                return $data_notification['email_updatedcanceledevent'];
                break;
            case SOCIAL_ACTION_CONNECT:
                return true;
                break;
            case SOCIAL_ACTION_INVITE:
                if($entity_type==SOCIAL_ENTITY_CHANNEL){
                    return $data_notification['email_invitedchannel'];
                }else{
                    return $data_notification['email_invitedevent']; 
                }			
                break;
            case SOCIAL_ACTION_COMMENT:                        
                if($entity_type==SOCIAL_ENTITY_EVENTS || $entity_type==SOCIAL_ENTITY_USER_EVENTS || $entity_type==SOCIAL_ENTITY_EVENTS_DATE || $entity_type==SOCIAL_ENTITY_EVENTS_LOCATION || $entity_type==SOCIAL_ENTITY_EVENTS_TIME){
                    return $data_notification['email_commentedevent'];
                }else if($entity_type==SOCIAL_ENTITY_FLASH ){
                    return $data_notification['email_commentedecho'];
                }else {
                    return $data_notification['email_commentedcontent'];
                }
                break;
            case SOCIAL_ACTION_UPDATE:
                if($entity_type==SOCIAL_ENTITY_USER_EVENTS || $entity_type==SOCIAL_ENTITY_EVENTS || $entity_type==SOCIAL_ENTITY_EVENTS_DATE || $entity_type==SOCIAL_ENTITY_EVENTS_LOCATION || $entity_type==SOCIAL_ENTITY_EVENTS_TIME){
                    return $data_notification['email_updatedcanceledevent'];
                }else{
                    return true;
                }
                break;
            case SOCIAL_ACTION_REPORT:
                return true;
                break;
            case SOCIAL_ACTION_EVENT_JOIN:
                return $data_notification['email_joinedevent'];
                break;
            case SOCIAL_ACTION_UPLOAD:
                if($entity_type==SOCIAL_ENTITY_MEDIA || $entity_type==SOCIAL_ENTITY_ALBUM){
                    return $data_notification['email_updates_media'];
                }else{
                    return $data_notification['email_newsTT'];
                }			
                break;
            default:
                return true;
                break;
	}


}
/**
 * gets the email notification for a given channel 
 * @param integer $channelid the channel id
 * @param integer $action_type {SOCIAL_ACTION_COMMENT, SOCIAL_ACTION_CONNECT, ...}
 * @param string $entity_type the type of the entity
 * @return true | false
 */
function socialEmailNotificationGet($channel_id,$action_type,$entity_type){


	global $dbConn;
	$params = array();  
	$email_notification=GetChannelNotifications($channel_id);
	$email_notification=$email_notification[0];
	if(!$email_notification['email_notifications']){
		return false;
	}
	$channelInfo=channelGetInfo($channel_id);
	$userInfo = getUserInfo($channelInfo['owner_id']);
	
//	$query = "SELECT notify FROM cms_notifications_emails WHERE email='".$userInfo['YourEmail']."' LIMIT 1";
        $query = "SELECT notify FROM cms_notifications_emails WHERE email=:YourEmail LIMIT 1";
	$params[] = array( "key" => ":YourEmail",
                            "value" =>$userInfo['YourEmail']);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
//	if( $res && db_num_rows($res) != 0){
	if( $res && $ret != 0){
//		$row = db_fetch_assoc($res);
            $row = $select->fetch(PDO::FETCH_ASSOC);
            $notify = ($row['notify'] == 1);
            if( !$notify ) return false;
	}
	
	switch($action_type){
            case SOCIAL_ACTION_SPONSOR:
                if($entity_type==SOCIAL_ENTITY_CHANNEL){
                    return $email_notification['email_sponsorschannel'];
                }else if($entity_type==SOCIAL_ENTITY_EVENTS){
                    return $email_notification['email_sponsorsevent'];
                }else{
                    return true;
                }
                break;
            case SOCIAL_ACTION_EVENT_CANCEL:
                return $email_notification['email_cancelsponsoring'];
                break;
            case SOCIAL_ACTION_CONNECT:
                return $email_notification['email_connects'];
                break;
            case SOCIAL_ACTION_INVITE:
                return $email_notification['email_invites'];
                break;
            case SOCIAL_ACTION_COMMENT:
                // todo reply on a comment
                if($entity_type==SOCIAL_ENTITY_EVENTS || $entity_type==SOCIAL_ENTITY_EVENTS_DATE || $entity_type==SOCIAL_ENTITY_EVENTS_LOCATION || $entity_type==SOCIAL_ENTITY_EVENTS_TIME){
                    return $email_notification['email_commentedevent'];
                }else{
                    return $email_notification['email_commentscontent'];
                }
                break;
            case SOCIAL_ACTION_LIKE:
                // todo liked my comment or someone's comment
                if($entity_type==SOCIAL_ENTITY_CHANNEL || $entity_type==SOCIAL_ENTITY_CHANNEL_COVER || $entity_type==SOCIAL_ENTITY_CHANNEL_INFO || $entity_type==SOCIAL_ENTITY_CHANNEL_PROFILE || $entity_type==SOCIAL_ENTITY_CHANNEL_SLOGAN){
                    return $email_notification['email_likescontent'];
                }else if($entity_type==SOCIAL_ENTITY_COMMENT){
                    return $email_notification['email_likedcomment'];
                }else if($entity_type==SOCIAL_ENTITY_EVENTS || $entity_type==SOCIAL_ENTITY_EVENTS_DATE || $entity_type==SOCIAL_ENTITY_EVENTS_LOCATION || $entity_type==SOCIAL_ENTITY_EVENTS_TIME){
                    return $email_notification['email_likedevent'];
                }else{
                    return $email_notification['email_likescontent'];
                }			
                break;
            case SOCIAL_ACTION_SHARE:
                if($entity_type==SOCIAL_ENTITY_CHANNEL){
                    return $email_notification['email_sharedchannel'];
                }else if($entity_type==SOCIAL_ENTITY_EVENTS || $entity_type==SOCIAL_ENTITY_EVENTS_DATE || $entity_type==SOCIAL_ENTITY_EVENTS_LOCATION || $entity_type==SOCIAL_ENTITY_EVENTS_TIME){
                    return $email_notification['email_sharedevent'];
                }else{
                    // channel's content
                    return $email_notification['email_sharedcontent'];
                }			
                break;
            case SOCIAL_ACTION_RATE:
                if($entity_type==SOCIAL_ENTITY_MEDIA || $entity_type==SOCIAL_ENTITY_ALBUM){
                    return $email_notification['email_ratedmedia'];
                }else{
                    return true;
                }
                break;
            case SOCIAL_ACTION_REPORT:
                return $email_notification['email_reportedconnections'];
                break;
            case SOCIAL_ACTION_RELATION_SUB:
            case SOCIAL_ACTION_RELATION_PARENT:
                    return $channel_notification['email_subchannelrequest'];
                    break;
            case SOCIAL_ACTION_EVENT_JOIN:
                return $email_notification['email_joinsevent'];
                break;
            case SOCIAL_ACTION_UPLOAD:
                return false;
                break;
            default:
                return true;
                break;
    }


	
}

/**
 * edits a posts info
 * @param array $news_info the new cms_social_posts info
 * @return boolean true|false if success|fail
 */
function socialPostsEdit($news_info){
    global $dbConn;
    $params = array();  	
    $query = "UPDATE cms_social_posts SET ";
    $i = 0;
    foreach( $news_info as $key => $val){
        if( $key != 'id' && $key !='channel_id' && $key !='user_id'){
            $query .= " $key = :Val".$i.",";
            $params[] = array( "key" => ":Val".$i,
                                "value" =>$val);
            $i++;
        }
    }
    $query = trim($query,',');
    if( $news_info['channel_id'] >0 ){
        $query .= " WHERE id=:Id AND channel_id=:Channel_id AND user_id=:User_id";
    }else{
        $query .= " WHERE id=:Id AND channel_id=:Channel_id AND from_id=:User_id";
    }	
    $params[] = array( "key" => ":Id", "value" =>$news_info['id']);
    $params[] = array( "key" => ":Channel_id", "value" =>$news_info['channel_id']);
    $params[] = array( "key" => ":User_id", "value" =>$news_info['user_id']);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $ret    = $select->execute();	
    return ( $ret ) ? true : false;
}
/**
 * add the posts for a given channel 
 * @param integer $user_id the user id
 * @param integer $channelid the channel id
 * @param integer $from_id the user id posted
 * @param string $post_text the post text
 * @param double $longitude the location longitude
 * @param double $lattitude the location lattitude
 * @param integer $post_type the post type (text or photo or video or link)
 * @return integer | false the newly inserted cms_social_posts id or false if not inserted
 */
function socialPostsAdd($user_id,$channel_id,$from_id,$post_text='',$post_type=1,$longitude,$lattitude,$linkPost='',$locationPost='',$filename='',$data_isvideo=0){


	global $dbConn;
	$params = array();  
	$published=1;
	if($post_type==SOCIAL_POST_TYPE_VIDEO || $data_isvideo==1){
		$published=0;	
	}
	$relativepath = date('Y').'/'.date('W').'/';
        $query = "  INSERT INTO cms_social_posts (user_id,channel_id,from_id,post_text,post_type,post_link,post_location,media_file,is_video,create_ts,relativepath,longitude,lattitude,published) VALUES (:User_id,:Channel_id,:From_id,:Post_text,:Post_type,:LinkPost,:LocationPost,:Filename,:Data_isvideo,NOW(),:Relativepath,:Longitude,:Lattitude,:Published)";
					
//	$ret = db_query($query);
	$params[] = array( "key" => ":User_id", "value" =>$user_id);
	$params[] = array( "key" => ":Channel_id", "value" =>$channel_id);
	$params[] = array( "key" => ":From_id", "value" =>$from_id);
	$params[] = array( "key" => ":Post_text", "value" =>$post_text);
	$params[] = array( "key" => ":Post_type", "value" =>$post_type);
	$params[] = array( "key" => ":LinkPost", "value" =>$linkPost);
	$params[] = array( "key" => ":LocationPost", "value" =>$locationPost);
	$params[] = array( "key" => ":Filename", "value" =>$filename);
	$params[] = array( "key" => ":Data_isvideo", "value" =>$data_isvideo);
	$params[] = array( "key" => ":Relativepath", "value" =>$relativepath);
	$params[] = array( "key" => ":Longitude", "value" =>$longitude);
	$params[] = array( "key" => ":Lattitude", "value" =>$lattitude);
	$params[] = array( "key" => ":Published", "value" =>$published);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();
	
        $post_id = $dbConn->lastInsertId();
	
	if( $post_id ){
            $post_user = $from_id;
            if($from_id ==0){
                $post_user = $user_id;
            }
            newsfeedAdd($post_user, $post_id , SOCIAL_ACTION_UPLOAD, $post_id, SOCIAL_ENTITY_POST , USER_PRIVACY_PUBLIC, $channel_id);		
            return $post_id;
	}else{
            return false;
	}


}
/**
 * gets the posts info
 * @param integer $id the post's id, 
 * @return array | false the cms_social_posts record or null if not found
 */
function socialPostsInfo($id){


	global $dbConn;
        $socialPostsInfo = tt_global_get('socialPostsInfo');
	$params = array(); 
        if(isset($socialPostsInfo[$id]) && $socialPostsInfo[$id]!='')
                return $socialPostsInfo[$id];
//	$query = "SELECT * FROM cms_social_posts WHERE id='$id'";
//	$ret = db_query($query);
//	if($ret && db_num_rows($ret)!=0 ){
//		$row = db_fetch_assoc($ret);
//		return $row;
//	}else{
//		return false;
//	}
	$query = "SELECT * FROM cms_social_posts WHERE id=:Id";
	$params[] = array( "key" => ":Id", "value" =>$id);
	$select = $dbConn->prepare($query);
	PDO_BIND_PARAM($select,$params);
	$res    = $select->execute();

	$ret    = $select->rowCount();
	if($res && $ret!=0 ){
		$row = $select->fetch(PDO::FETCH_ASSOC);
                $socialPostsInfo[$id]    =   $row;
		return $row;
	}else{
                $socialPostsInfo[$id]    =   false;
		return false;
	}


}
/**
 * delete the posts for a given channel 
 * @param integer $id posts id
 * @return boolean true|false depending on the success of the operation
 */
function socialPostsDelete($id){
    global $dbConn;
    $params = array(); 
    if( deleteMode() == TT_DEL_MODE_PURGE ){
        $query = "DELETE FROM cms_social_posts where id=:Id AND published=1";
    }else if( deleteMode() == TT_DEL_MODE_FLAG ){
        $query = "UPDATE cms_social_posts SET published=".TT_DEL_MODE_FLAG." WHERE id=:Id AND published=1";
    }
    $params[] = array(  "key" => ":Id", "value" =>$id);
    newsfeedDeleteAll($id, SOCIAL_ENTITY_POST);

    //delete comments
    socialCommentsDelete($id, SOCIAL_ENTITY_POST);

    //delete likes
    socialLikesDelete($id, SOCIAL_ENTITY_POST);

    //delete shares
    socialSharesDelete($id, SOCIAL_ENTITY_POST);

    //delete ratings
    socialRatesDelete($id, SOCIAL_ENTITY_POST);

    TTDebug(DEBUG_TYPE_USER, DEBUG_LVL_INFO, $query);
    $select = $dbConn->prepare($query);
    PDO_BIND_PARAM($select,$params);
    $res    = $select->execute();
    return $res;
}
/**
 * gets the posts info of a given channel . options include:<br/>
 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
 * <b>page</b>: the number of pages to skip. default 0<br/>
 * <b>id</b>: post id<br/>
 * <b>channel_id</b>: the channel's id. default null<br/>
 * <b>user_id</b>: the user's id. default null<br/>
 * <b>from_id</b>: the from user's id. default null<br/>
 * <b>post_type</b>: the post type it can be SOCIAL_POST_TYPE_TEXT or SOCIAL_POST_TYPE_LINK or SOCIAL_POST_TYPE_PHOTO or SOCIAL_POST_TYPE_VIDEO . default null<br/>
 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
 * @return array | false an array of 'cms_social_posts' records or false if none found.
 */
function socialPostsSearch($srch_options){


    global $dbConn;
    $params = array();  
    $default_opts = array(
            'limit' => 6,
            'page' => 0,
            'id' => null,
            'channel_id' => null,
            'user_id' => null,
	    'from_ts' => null,
	    'to_ts' => null,
            'from_id' => null,
            'post_type' => null,
            'orderby' => 'id',
            'n_results' => false,
            'order' => 'a'
    );

    $options = array_merge($default_opts, $srch_options);

    $where = '';

    if( $options['id'] ){
        if($where != '') $where .= ' AND ';
//            $where .= " id='{$options['id']}' ";
        $where .= " id=:Id ";
        $params[] = array( "key" => ":Id",
                            "value" =>$options['id']);
    }
    if( $options['channel_id'] ){
        if($where != '') $where .= ' AND ';
//            $where .= " channel_id='{$options['channel_id']}' ";
        $where .= " channel_id=:Channel_id ";
        $params[] = array( "key" => ":Channel_id",
                            "value" =>$options['channel_id']);
    }
    if( $options['user_id'] ){
        if($where != '') $where .= ' AND ';
//            $where .= " user_id='{$options['user_id']}' ";
        $where .= " user_id=:User_id ";
        $params[] = array( "key" => ":User_id",
                            "value" =>$options['user_id']);
    }
    if( $options['from_id'] ){
        if($where != '') $where .= ' AND ';
//            $where .= " from_id='{$options['from_id']}' ";
        $where .= " from_id=:From_id ";
        $params[] = array( "key" => ":From_id",
                            "value" =>$options['from_id']);
    }
    if( $options['post_type'] ){
        if($where != '') $where .= ' AND ';
//            $where .= " user_id='{$options['post_type']}' ";
        $where .= " user_id=:Post_type ";
	$params[] = array( "key" => ":Post_type",
        "value" =>$options['post_type']);
    }
    if ($options['from_ts']) {
     	if ($where != '') $where .= " AND ";
     	//$where .= " DATE(create_ts) >= '{$options['from_ts']}' ";
	$where .= " DATE(create_ts) >= :From_ts ";
        $params[] = array( "key" => ":From_ts", "value" =>$options['from_ts']);
    }
    if ($options['to_ts']) {
	    if ($where != '') $where .= " AND ";
	    //$where .= " DATE(create_ts) <= '{$options['to_ts']}' ";
	    $where .= " DATE(create_ts) <= :To_ts ";
            $params[] = array( "key" => ":To_ts", "value" =>$options['to_ts']);
	}

    if($where != '') $where .= " AND ";
    $where .= " published=1 ";

    if($where != ''){
        $where = "WHERE $where";
    }

    $orderby = $options['orderby'];
    $order = ($options['order'] == 'a') ? 'ASC' : 'DESC';

    $nlimit = intval($options['limit']);
    $skip = intval($options['page']) * $nlimit;


    if(!$options['n_results']){
//            $query = "SELECT * FROM cms_social_posts $where ORDER BY $orderby $order LIMIT $skip, $nlimit";
        $query = "SELECT * FROM cms_social_posts $where ORDER BY $orderby $order LIMIT :Skip, :Nlimit";
//            $ret = db_query($query);
        $params[] = array( "key" => ":Skip", "value" =>$skip, "type" =>"::PARAM_INT");
        $params[] = array( "key" => ":Nlimit", "value" =>$nlimit, "type" =>"::PARAM_INT");
        $select = $dbConn->prepare($query);
        PDO_BIND_PARAM($select,$params);
        $res    = $select->execute();

        $ret    = $select->rowCount();
//            if(!$ret || (db_num_rows($ret) == 0) ){
        if(!$res || ($ret == 0) ){
                return false;
        }else{
            $ret_arr = array();
//                    while($row = db_fetch_array($ret)){
//                            $ret_arr[] = $row;
//                    }
            $ret_arr = $select->fetchAll();
            return $ret_arr;
        }
    }else{
            $query = "SELECT COUNT(id) FROM cms_social_posts $where";
            
            $select = $dbConn->prepare($query);
            PDO_BIND_PARAM($select,$params);
            $res    = $select->execute();
            $row = $select->fetch();

            return $row[0];
    }


}

function sendUserEmailNotification_ShareALL( $to_email , $from_user , $entity_id , $entity_type , $share_type , $is_channel){    
    $global_link= currentServerURL().'';
     
    $from_user_userInfo = getUserInfo($from_user);
    $share_txt='';
    $subject = "";
    $desc_link = '';
    if( $share_type == SOCIAL_SHARE_TYPE_INVITE || $share_type == SOCIAL_SHARE_TYPE_SPONSOR ){
        if($entity_type == SOCIAL_ENTITY_CHANNEL){            
            $channelInfo=channelGetInfo($entity_id);
            $channel_lnk= channelMainLink($channelInfo);
            $desc_link = '<a href="'.$channel_lnk.'" target="_BLANK"><font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">'.htmlEntityDecode($channelInfo['channel_name']).'</font></a>';
        }else{
            $entity_info = socialEntityInfo($entity_type, $entity_id);
            if($entity_type == SOCIAL_ENTITY_EVENTS){
                $entity_lnk= ReturnLink('channel-events-detailed/'.$entity_info['id']);
            }else{
                $entity_lnk= ReturnLink('events-detailed/'.$entity_info['id']);
            }
            $desc_link = '<a href="'.$entity_lnk.'" target="_BLANK"><font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">'.htmlEntityDecode($entity_info['name']).'</font></a>';
        }
    }
    if( $share_type == SOCIAL_SHARE_TYPE_INVITE){
        if($entity_type == SOCIAL_ENTITY_CHANNEL){
            $subject_data = "connect to a channel";
            $desc_txt = 'invited you to connect to the channel ';
        }else{
            $subject_data = "join an event";
            $desc_txt = 'invited you to join the event ';
        }
        $share_txt='invited';
        $subject = 'Someone invited you to '.$subject_data;
    }else if( $share_type == SOCIAL_SHARE_TYPE_SPONSOR){
        if($entity_type == SOCIAL_ENTITY_CHANNEL){
            $subject_data = "a channel";
            $desc_txt = 'sponsored the channel ';
        }else{
            $subject_data = "an event";
            $desc_txt = 'sponsored the event ';
        }
        $share_txt='sponsored';
        $subject = 'Someone sponsored '.$subject_data;
    }else if( $share_type == SOCIAL_SHARE_TYPE_SHARE){
        switch($entity_type){
            case SOCIAL_ENTITY_MEDIA:
                $entity_info_oject=getVideoInfo( $entity_id );
                if($entity_info_oject['image_video'] == "v"){                    
                    $subject_data = "a video";
                    $desc_txt = 'shared the video ';
                    $entity_lnk = ReturnVideoUriHashed($entity_info_oject);
                }else{
                    $subject_data = "a photo";
                    $desc_txt = 'shared the photo ';
                    $entity_lnk = ReturnPhotoUri($entity_info_oject);
                }
                $entity_name = htmlEntityDecode($entity_info_oject['title']);                
            break;
            case SOCIAL_ENTITY_ALBUM:
                $entity_info_oject=userCatalogGet($entity_id );
                $subject_data = "an album";
                $desc_txt = 'shared the album ';
                $entity_lnk = ReturnAlbumUri($entity_info_oject);
                $entity_name = htmlEntityDecode($entity_info_oject['catalog_name']);                 
            break;
            case SOCIAL_ENTITY_CHANNEL:
                $entity_info_oject=channelGetInfo($entity_id );
                $subject_data = "a channel";
                $desc_txt = 'shared the channel ';
                $entity_lnk = channelMainLink($entity_info_oject);
                $entity_name = htmlEntityDecode($entity_info_oject['channel_name']);
            break;
            case SOCIAL_ENTITY_EVENTS:
                $entity_info_oject=channelEventInfo($entity_id,-1);
                $subject_data = "an event";
                $desc_txt = 'shared the event ';
                $entity_lnk = ReturnLink('channel-events-detailed/'.$entity_info_oject['id']);
                $entity_name = htmlEntityDecode($entity_info_oject['name']);
            break;
            case SOCIAL_ENTITY_USER_EVENTS:
                $entity_info_oject=userEventInfo($entity_id,-1);
                $subject_data = "an event";
                $desc_txt = 'shared the event ';
                $entity_lnk = ReturnLink('events-detailed/'.$entity_info_oject['id']);
                $entity_name = htmlEntityDecode($entity_info_oject['name']);
            break;
            case SOCIAL_ENTITY_BROCHURE:
                $entity_info_oject=channelBrochureInfo($entity_id );
                $channelInfo=channelGetInfo($entity_info_oject['channelid']);
                $subject_data = "a brochure";
                $desc_txt = 'shared the brochure ';
                $entity_lnk = ReturnLink('channel-brochures/'.$channelInfo['channel_url'].'/id/'.$entity_info_oject['id']);
                $entity_name = htmlEntityDecode($entity_info_oject['name']);
            break;
            case SOCIAL_ENTITY_NEWS:
                $entity_info_oject=channelNewsInfo($entity_id );
                $channelInfo=channelGetInfo($entity_info_oject['channelid']);
                $subject_data = "a news";
                $desc_txt = 'shared the news ';
                $entity_lnk = ReturnLink('channel-news/'.$channelInfo['channel_url'].'/id/'.$entity_info_oject['id']);
                $entity_name = htmlEntityDecode($entity_info_oject['description']);
            break;
            case SOCIAL_ENTITY_POST:
                $entity_info_oject=socialPostsInfo($entity_id );
                $subject_data = "a post";
                $desc_txt = 'shared the ';
                if($is_channel==1){
                    $channelInfo=channelGetInfo($entity_info_oject['channel_id']);
//                    $entity_lnk = ReturnLink('channel-log/'.$channelInfo['channel_url'].'/post/'.$entity_info_oject['id']);
                    $entity_lnk = ReturnLink('channel/'.$channelInfo['channel_url']);
                }else{
                    $userInfo = getUserInfo($entity_info_oject['user_id']);
                    $entity_lnk = ReturnLink('profile/'.$userInfo['YourUserName'].'/TTpage/post/'.$entity_info_oject['id']);
                }                
                $entity_name = 'post';
            break;
            case SOCIAL_ENTITY_HOTEL_REVIEWS:
            case SOCIAL_ENTITY_RESTAURANT_REVIEWS:
            case SOCIAL_ENTITY_LANDMARK_REVIEWS:
            case SOCIAL_ENTITY_AIRPORT_REVIEWS:
                $entity_info_oject=userReviewsInfo($entity_id, $entity_type );
                $subject_data = "a review";
                $desc_txt = 'shared the ';                
                $userInfo = getUserInfo($entity_info_oject['user_id']);
                if($entity_type==SOCIAL_ENTITY_HOTEL_REVIEWS){
                    $item_data = getHotelInfo($entity_info_oject['hotel_id']);
                    $linkpoi_name = htmlEntityDecode($item_data['hotelName']);
                    $entity_lnk = returnHotelReviewLink($item_data['id'],$linkpoi_name);
                }else if($entity_type==SOCIAL_ENTITY_RESTAURANT_REVIEWS){
                    $item_data = getRestaurantInfo($entity_info_oject['restaurant_id']);                    
                    $linkpoi_name = htmlEntityDecode($item_data['name']);
                    $entity_lnk = returnRestaurantReviewLink($item_data['id'],$linkpoi_name);
                }else if($entity_type==SOCIAL_ENTITY_LANDMARK_REVIEWS){
                    $item_data = getPoiInfo($entity_info_oject['poi_id']);
                    $linkpoi_name = htmlEntityDecode($item_data['name']);
                    $entity_lnk = returnThingstodoReviewLink($item_data['id'],$linkpoi_name);
                }else if($entity_type==SOCIAL_ENTITY_AIRPORT_REVIEWS){
                    $item_data = getAirportInfo($entity_info_oject['poi_id']);
                    $linkpoi_name = htmlEntityDecode($item_data['name']);
                    $entity_lnk = returnAirportReviewLink($item_data['id'],$linkpoi_name);
                }
                $entity_name = 'review';
            break;
            case SOCIAL_ENTITY_JOURNAL:
                $entity_info_oject=getJournalInfo($entity_id);
                $userInfo = getUserInfo($entity_info_oject['user_id']);
                $subject_data = "a journal";
                $desc_txt = 'shared the journal ';
                $entity_lnk = ReturnLink('profile/'.$userInfo['YourUserName'].'/journal/id/'.$entity_info_oject['id']);
                $entity_name = $entity_info_oject['journal_name'];
            break;
        }
        $desc_link = '<a href="'.$entity_lnk.'" target="_BLANK"><font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">'.$entity_name.'</font></a>';
        $share_txt='shared';
        $subject = 'Someone shared '.$subject_data;
    }
    
    $FullName = $to_email;
    $suser_link = userProfileLink($from_user_userInfo,1);
    $tubarray = array("0"=>array(ReturnLink('media/tubers/' . $from_user_userInfo['profile_Pic']), htmlEntityDecode($from_user_userInfo['FullName']) ,"","","",$suser_link) );
    if($is_channel==1){
        if( $share_type == SOCIAL_SHARE_TYPE_SPONSOR){
            $channelInfo=channelGetInfo($from_user);
            $suser_link = channelMainLink($channelInfo);
            $tubarray = array("0"=>array(photoReturnchannelLogo($channelInfo), htmlEntityDecode($channelInfo['channel_name']) ,"","","",$suser_link) );
        }else{
            $owner_id = socialEntityOwner($entity_type, $entity_id );
            
            if($owner_id==$from_user){
                if($entity_type != SOCIAL_ENTITY_CHANNEL){
                    $entity_data = socialEntityInfo($entity_type, $entity_id);
                    $channelInfo=channelGetInfo($entity_data['channelid']);
                }else{
                    $channelInfo=channelGetInfo($entity_id);
                }
                $suser_link = channelMainLink($channelInfo);
                $tubarray = array("0"=>array(photoReturnchannelLogo($channelInfo), htmlEntityDecode($channelInfo['channel_name']) ,"","","",$suser_link) );
            }
        }
    }
    $globArray = array();
    $case_val_array = array();
    $globArray['invite'] = array();
    $globArray['ownerName'] = $FullName;
    $case_val_array['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000">'.$desc_txt.'</font>'.$desc_link.'';
    $case_val_array['friends'] = $tubarray;

    $globArray['invite'][] = $case_val_array;
    $not_settings= '';        
    $unsubscribe_lnk='';    
    displayEmailShareTypes( $to_email , $subject , $globArray , $unsubscribe_lnk , $not_settings , '' , $is_channel );
}
function sendUserEmailNotification_Invite( $user_id , $from_user , $entity_id , $entity_type ){
    if( socialUsersEmailNotificationGet($user_id,SOCIAL_ACTION_INVITE,$entity_type) ){
        $is_channel=0;
        $global_link= currentServerURL().'';

        $userInfo = getUserInfo($user_id);
      
        $from_user_userInfo = getUserInfo($from_user);        
        $subject = "Someone invited you to join an event";
        $desc_txt = 'join the event ';
        $desc_link = '';
        if($entity_type == SOCIAL_ENTITY_CHANNEL){
            $is_channel=1;
            $subject = "Someone invited you to connect to a channel";
            $desc_txt = 'connect to the channel ';            
            $channelInfo=channelGetInfo($entity_id);
            $channel_lnk= channelMainLink($channelInfo);
            $desc_link = '<a href="'.$channel_lnk.'" target="_BLANK"><font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">'.htmlEntityDecode($channelInfo['channel_name']).'</font></a>';
        }else{
            $entity_info = socialEntityInfo($entity_type, $entity_id);
            if($entity_type == SOCIAL_ENTITY_EVENTS){
                $is_channel=1;
                $entity_lnk= ReturnLink('channel-events-detailed/'.$entity_info['id']);
            }else{
                $entity_lnk= ReturnLink('events-detailed/'.$entity_info['id']);
            }
            $desc_link = '<a href="'.$entity_lnk.'" target="_BLANK"><font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">'.htmlEntityDecode($entity_info['name']).'</font></a>';
        }
        $FullName = htmlEntityDecode($userInfo['FullName']);
        
        $globArray = array();
        $case_val_array = array();
        $globArray['invite'] = array();
        $globArray['ownerName'] = $FullName;
        $case_val_array['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> invited you to '.$desc_txt.'</font>'.$desc_link.'';
        $suser_link = userProfileLink($from_user_userInfo,1);
        $case_val_array['friends'] = array("0"=>array(ReturnLink('media/tubers/' . $from_user_userInfo['profile_Pic']), htmlEntityDecode($from_user_userInfo['FullName']) ,"","","",$suser_link) );
        
	$globArray['invite'][] = $case_val_array;
        $not_settings= ReturnLink('TT-confirmation/TSettings/'.md5($userInfo['id'].''.$userInfo['YourEmail']));
        $unsubscribe_lnk=ReturnLink('unsubscribe/emails/'.md5($userInfo['id'].''.$userInfo['YourEmail']));
        
        if( $userInfo['otherEmail'] !=''){
            $to_email = $userInfo['otherEmail'];
        }else{
            $to_email = $userInfo['YourEmail'];
        }
        displayEmailShareTypes( $to_email , $subject , $globArray , $unsubscribe_lnk , $not_settings , '' , $is_channel );
    }
}
function sendUserEmailNotification_Cancel_Event_Join( $user_id , $event_id , $entity_type ){
    if( socialUsersEmailNotificationGet($user_id,SOCIAL_ACTION_EVENT_JOIN,SOCIAL_ENTITY_USER_EVENTS) ){
        $global_link= currentServerURL().'';

        $userInfo = getUserInfo($user_id);

        $eventInfo = userEventInfo($event_id,-1);        
        $event_userInfo = getUserInfo($eventInfo['user_id']);
        $desc = '';
        $desc_txt ='';
        $subject = "Someone updated his own event";
        $entity_lnk= ReturnLink('events-detailed/'.$eventInfo['id']);
        if($entity_type == SOCIAL_ACTION_EVENT_CANCEL ){
            $entity_lnk ='';
            $subject = "Someone cancelled his own event";
            $desc = '"We are sorry to inform you that our event is canceled."';
            $desc_txt = 'cancelled';
        }else if($entity_type == SOCIAL_ENTITY_EVENTS_LOCATION ){
            $desc_txt = 'changed location of';
        }else if($entity_type == SOCIAL_ENTITY_EVENTS_DATE ){
            $desc_txt = 'changed date of';
        }else if($entity_type == SOCIAL_ENTITY_EVENTS_TIME ){
            $desc_txt = 'changed time of';
        }
        $photo_this = getEventThumbPath($eventInfo,$global_link);

        $from_date = date( 'd/m/Y', strtotime($eventInfo['fromdate']) );
        $fromtime=substr($eventInfo['fromtime'],0,5);
        $Evtlocation = htmlEntityDecode($eventInfo['location']);
        $Evtname = htmlEntityDecode($eventInfo['name']);
        
        $options = array(
            'event_id' => $eventInfo['id'],
            'is_visible' => 1,
            'n_results' => true
        );
        $joins_num = joinUserEventSearch($options);
        
        $FullName = htmlEntityDecode($userInfo['FullName']);
        
        $globArray = array();
        $case_val_array = array();
        $globArray['event'] = array();
        $globArray['ownerName'] = $FullName;
        $case_val_array['case'] = '1';
        $case_val_array['friendName'] = htmlEntityDecode($event_userInfo['FullName']);
        $case_val_array['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">'.htmlEntityDecode($event_userInfo['FullName']).'</font><font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> '.$desc_txt.' the event you were joining.</font>';
        $case_val_array['partDec'] = '';
        $case_val_array['eventLink'] = $entity_lnk;
        $case_val_array['eventImg'] = array($photo_this, $Evtname , $entity_lnk );
        $case_val_array['friendComment'] = $desc;
        $case_val_array['eventDetails'] = array($from_date,$fromtime, $Evtlocation,$Evtname,$joins_num);
        
       
	$globArray['event'][] = $case_val_array;
        $not_settings= ReturnLink('TT-confirmation/TSettings/'.md5($userInfo['id'].''.$userInfo['YourEmail']));
        $unsubscribe_lnk=ReturnLink('unsubscribe/emails/'.md5($userInfo['id'].''.$userInfo['YourEmail']));
        
        if( $userInfo['otherEmail'] !=''){
            $to_email = $userInfo['otherEmail'];
        }else{
            $to_email = $userInfo['YourEmail'];
        }
        displayEmailCanceledUpdateEvent( $to_email , $subject , $globArray , array() , 0 , 0 , $unsubscribe_lnk , $not_settings , '' );
    }
}
function sendChannelEmailNotification_Cancel_Event_Join( $user_id , $event_id , $entity_type ){
    if( socialUsersEmailNotificationGet($user_id,SOCIAL_ACTION_EVENT_JOIN,SOCIAL_ENTITY_EVENTS) ){
        $global_link= currentServerURL().'';

        $userInfo = getUserInfo($user_id);

        $eventInfo = channelEventInfo($event_id,-1);
        $event_channelInfo=channelGetInfo($eventInfo['channelid']);
        $channel_lnk= channelMainLink($event_channelInfo);
        $desc = '';
        $desc_txt ='';
        $subject = "Someone updated their event";
        $entity_lnk= ReturnLink('channel-events-detailed/'.$event_channelInfo['id']);
        if($entity_type == SOCIAL_ACTION_EVENT_CANCEL ){
            $entity_lnk ='';
            $subject = "Someone cancelled their event";
            $desc = '"We are sorry to inform you that our event is canceled."';
            $desc_txt = 'cancelled';
        }else if($entity_type == SOCIAL_ENTITY_EVENTS_LOCATION ){
            $desc_txt = 'changed location of';
        }else if($entity_type == SOCIAL_ENTITY_EVENTS_DATE ){
            $desc_txt = 'changed date of';
        }else if($entity_type == SOCIAL_ENTITY_EVENTS_TIME ){
            $desc_txt = 'changed time of';
        }
        
        if($eventInfo['photo'] != '')
            $photo_this = $global_link.''.ReturnLink('media/channel/' . $eventInfo['channelid'] . '/event/thumb/' . $eventInfo['photo']);
        else
            $photo_this = ReturnLink('media/images/channel/eventthemephoto.jpg');

        $from_date = date( 'd/m/Y', strtotime($eventInfo['fromdate']) );
        $fromtime=substr($eventInfo['fromtime'],0,5);
        $Evtlocation = htmlEntityDecode($eventInfo['location']);
        $Evtname = htmlEntityDecode($eventInfo['name']);
        
        $options = array(
            'event_id' => $eventInfo['id'],
            'is_visible' => 1,
            'n_results' => true
        );
        $joins_num = joinEventSearch($options);
        
        $FullName = htmlEntityDecode($userInfo['FullName']);
                        
        $globArray = array();
        $case_val_array = array();
        $globArray['event'] = array();
        $globArray['ownerName'] = $FullName;
        $case_val_array['case'] = '1';
        $case_val_array['friendName'] = htmlEntityDecode($event_channelInfo['channel_name']);
        $case_val_array['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">'.htmlEntityDecode($event_channelInfo['channel_name']).'</font><font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> '.$desc_txt.' the event you were joining.</font>';
        $case_val_array['partDec'] = '';
        $case_val_array['eventLink'] = $entity_lnk;
        $case_val_array['eventImg'] = array($photo_this, $Evtname , $entity_lnk );
        $case_val_array['friendComment'] = $desc;
        $case_val_array['eventDetails'] = array($from_date,$fromtime, $Evtlocation,$Evtname,$joins_num);
        
       
	$globArray['event'][] = $case_val_array;
        $not_settings= ReturnLink('TT-confirmation/TSettings/'.md5($userInfo['id'].''.$userInfo['YourEmail']));
        $unsubscribe_lnk=ReturnLink('unsubscribe/emails/'.md5($userInfo['id'].''.$userInfo['YourEmail']));
        
        if( $userInfo['otherEmail'] !=''){
            $to_email = $userInfo['otherEmail'];
        }else{
            $to_email = $userInfo['YourEmail'];
        }
        displayEmailCanceledUpdateEvent( $to_email , $subject , $globArray , array() , 1 , 0 , $unsubscribe_lnk , $not_settings , '' );
    }
}
function sendChannelEmailNotification_Cancel_Event_Sponsor( $channel_id , $event_id , $entity_type ){
    if( socialEmailNotificationGet( $channel_id , SOCIAL_ACTION_EVENT_CANCEL , SOCIAL_ENTITY_EVENTS ) ){
        $global_link= currentServerURL().'';

        $channel_array_action=channelGetInfo($channel_id);
        $userInfo = getUserInfo($channel_array_action['owner_id']);

        $eventInfo = channelEventInfo($event_id,-1);
        $event_channelInfo=channelGetInfo($eventInfo['channelid']);
        $channel_lnk= channelMainLink($event_channelInfo);

        $desc = '';
        $desc_txt ='';
        $subject = "Someone updated their event";
        $entity_lnk= ReturnLink('channel-events-detailed/'.$event_channelInfo['id']);
        if($entity_type == SOCIAL_ACTION_EVENT_CANCEL ){
            $entity_lnk ='';
            $subject = "Someone cancelled their event";
            $desc = '"We are sorry to inform you that our event is canceled."';
            $desc_txt = 'cancelled';
        }else if($entity_type == SOCIAL_ENTITY_EVENTS_LOCATION ){
            $desc_txt = 'changed location of';
        }else if($entity_type == SOCIAL_ENTITY_EVENTS_DATE ){
            $desc_txt = 'changed date of';
        }else if($entity_type == SOCIAL_ENTITY_EVENTS_TIME ){
            $desc_txt = 'changed time of';
        }
        
        if($eventInfo['photo'] != '')
            $photo_this = $global_link.''.ReturnLink('media/channel/' . $eventInfo['channelid'] . '/event/thumb/' . $eventInfo['photo']);
        else
            $photo_this = ReturnLink('media/images/channel/eventthemephoto.jpg');

        $from_date = date( 'd/m/Y', strtotime($eventInfo['fromdate']) );
        $fromtime=substr($eventInfo['fromtime'],0,5);
        $Evtlocation = htmlEntityDecode($eventInfo['location']);
        $Evtname = htmlEntityDecode($eventInfo['name']);
        
        $options = array(
            'event_id' => $eventInfo['id'],
            'is_visible' => 1,
            'n_results' => true
        );
        $joins_num = joinEventSearch($options);
        
        $globArray = array();
        $case_val_array = array();
        $globArray['event'] = array();
        $globArray['ownerName'] = htmlEntityDecode($channel_array_action['channel_name']);
        $case_val_array['case'] = '1';
        $case_val_array['friendName'] = htmlEntityDecode($event_channelInfo['channel_name']);
        $case_val_array['partTitle'] = '<font face="Arial, Helvetica, sans-serif" size="2" color="#e9c21b">'.htmlEntityDecode($event_channelInfo['channel_name']).'</font><font face="Arial, Helvetica, sans-serif" size="2" color="#000000"> '.$desc_txt.' the event you were sponsoring.</font>';
        $case_val_array['partDec'] = '';
        $case_val_array['eventLink'] = $entity_lnk;
        $case_val_array['eventImg'] = array($photo_this, $Evtname , $entity_lnk );
        $case_val_array['friendComment'] = $desc;
        $case_val_array['eventDetails'] = array($from_date,$fromtime, $Evtlocation,$Evtname,$joins_num);
        
       
	$globArray['event'][] = $case_val_array;
        $not_settings= ReturnLink('TT-confirmation/channelSettings/'.md5($channel_id));
        $createchannel_lnk='';
        $unsubscribe_lnk=ReturnLink('unsubscribe/emails/'.md5($userInfo['id'].''.$userInfo['YourEmail']));
        
        if( $channel_array_action['notification_email'] !=''){
            $to_email = $channel_array_action['notification_email'];
        }else{
            $to_email = $userInfo['YourEmail'];
        }
        displayEmailCanceledUpdateEvent( $to_email , $subject , $globArray , array() , 1 , 1 , $unsubscribe_lnk , $not_settings , $createchannel_lnk );
    }
}
function sendChannelEmailNotification_Weekly(){
    return true;
}
function sendEmailNotification_Weekly(){
    return true;
}

function sendEmailNotificationEach5h(){
    return true;
}
function sendChannelEmailNotificationEach5h(){
    return true;
}

function getSocialCount($owner_id,$action_type,$entity_type=null,$media_type=null){
	$social_count = 0;
	$social_count = newsfeedLogSearch( array(
            'entity_type' => $entity_type,
            'media_type' => $media_type,
            'action_type' => $action_type,
            'activities_filter' => ACTIVITIES_ON_TTPAGE,
            'feed_privacy' => -1,
            'is_visible' => -1,
            'show_owner' => 1,
            'owner_id' => $owner_id,
            'n_results' => true
        ) );
	return $social_count;
}
function getChannelSocialCount($channel_id,$action_type,$entity_type=null,$media_type=null){
	$social_count = 0;        
	$social_count = newsfeedLogSearch( array(
            'entity_type' => $entity_type,
            'media_type' => $media_type,
            'action_type' => $action_type,
            'activities_filter' => ACTIVITIES_ON_TCHANNELS,
            'feed_privacy' => -1,
            'is_visible' => -1,
            'show_owner' => 1,
            'channel_id' => $channel_id,
            'n_results' => true
        ) );
	return $social_count;
}
function displayViewsCount($num,$add_num=1){    
    if( intval($num)>1 || intval($num)==0){
        $data_val = _('views');
    }else{
        $data_val = _('view');
    }
    if($add_num){
        return displayValueNum($num) .' '.$data_val;
    }else{
        return $data_val;
    }    
}
function displayPlaysCount($num,$add_num=1){    
    if( intval($num)>1 || intval($num)==0){
        $data_val = _('plays');
    }else{
        $data_val = _('play');
    }
    if($add_num){
        return displayValueNum($num) .' '.$data_val;
    }else{
        return $data_val;
    }
}
function displayLikesCount($num,$add_num=1){    
    if( intval($num)>1 || intval($num)==0){
        $data_val = _('likes');
    }else{
        $data_val = _('like');
    }
    if($add_num){
        return displayValueNum($num) .' '.$data_val;
    }else{
        return $data_val;
    }
}
function displayReviewsCount($num,$add_num=1){    
    if( intval($num)>1 || intval($num)==0){
        $data_val = _('reviews');
    }else{
        $data_val = _('review');
    }
    if($add_num){
        return displayValueNum($num) .' '.$data_val;
    }else{
        return $data_val;
    }
}
function displayCommentsCount($num,$add_num=1){    
    if( intval($num)>1 || intval($num)==0){
        $data_val = _('comments');
    }else{
        $data_val = _('comment');
    }
    if($add_num){
        return displayValueNum($num) .' '.$data_val;
    }else{
        return $data_val;
    }
}
function displaySharesCount($num,$add_num=1){    
    if( intval($num)>1 || intval($num)==0){
        $data_val = _('shares');
    }else{
        $data_val = _('share');
    }
    if($add_num){
        return displayValueNum($num) .' '.$data_val;
    }else{
        return $data_val;
    }
}
function displayRatingsCount($num,$add_num=1){    
    if( intval($num)>1 || intval($num)==0){
        $data_val = _('ratings');
    }else{
        $data_val = _('rating');
    }
    if($add_num){
        return displayValueNum($num) .' '.$data_val;
    }else{
        return $data_val;
    }
}
function displayJoiningCount($num,$add_num=1){    
    if( intval($num)>1 || intval($num)==0){
        $data_val = _('joining guests');
    }else{
        $data_val = _('joining guest');
    }
    if($add_num){
        return displayValueNum($num) .' '.$data_val;
    }else{
        return $data_val;
    }
}
function displaySponsorsCount($num,$add_num=1){    
    if( intval($num)>1 || intval($num)==0){
        $data_val = _('sponsors');
    }else{
        $data_val = _('sponsor');
    }
    if($add_num){
        return displayValueNum($num) .' '.$data_val;
    }else{
        return $data_val;
    }
}

function displayValueNum($num){    
    if( intval($num)<0){
        $num = 0;
    }
    return tt_number_format($num);
}
function debug($var){
	$bt = debug_backtrace();
	$caller = array_shift($bt);
	
	echo '<div style="background:#FF0"><pre>';
	echo 'File: ' . $caller['file'] . ' line: ' . $caller['line'] . '<br />';
	print_r($var);
	echo "</pre></div>";
}