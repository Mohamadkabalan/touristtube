<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('INDIA_USER', 'india2');

define('SOCIAL_ENTITY_HOTEL', 28);
define('SOCIAL_ENTITY_LANDMARK', 30);
define('SOCIAL_ENTITY_AIRPORT', 63);
define('SOCIAL_ENTITY_CITY', 71);
define('SOCIAL_ENTITY_COUNTRY', 72);
define('SOCIAL_ENTITY_STATE', 73);
define('SOCIAL_ENTITY_DOWNTOWN', 74);
define('SOCIAL_ENTITY_REGION',  78);

//Action types

define('HOTEL_INSERT', 1);
define('HOTEL_UPDATE', 2);
define('HOTEL_DELETE', 3);
define('HOTEL_ROOM_INSERT', 4);
define('HOTEL_ROOM_UPDATE', 5);
define('HOTEL_ROOM_DELETE', 6);
define('HOTEL_REVIEW_INSERT', 7);
define('HOTEL_REVIEW_UPDATE', 8);
define('HOTEL_REVIEW_DELETE', 9);
define('HOTEL_IMAGE_INSERT', 10);
define('HOTEL_IMAGE_DELETE', 11);
define('RESTAURANT_INSERT', 12);
define('RESTAURANT_UPDATE', 13);
define('RESTAURANT_DELETE', 14);
define('RESTAURANT_REVIEW_INSERT', 15);
define('RESTAURANT_REVIEW_UPDATE', 16);
define('RESTAURANT_REVIEW_DELETE', 17);
define('RESTAURANT_IMAGE_INSERT', 18);
define('RESTAURANT_IMAGE_DELETE', 19);
define('POI_INSERT', 20);
define('POI_UPDATE', 21);
define('POI_DELETE', 22);
define('POI_REVIEW_INSERT', 23);
define('POI_REVIEW_UPDATE', 24);
define('POI_REVIEW_DELETE', 25);
define('POI_IMAGE_INSERT', 26);
define('POI_IMAGE_DELETE', 27);
define('USER_LOGIN', 28);
define('USER_LOGOUT', 29);
define('Media_Translate_English', 30);
define('Media_Translate_Hindi', 31);
define('Media_Translate_French', 32);
define('Media_Translate_Chinese', 33);
define('Media_Translate_Spanish', 34);
define('Media_Translate_Portuguese', 35);
define('Media_Translate_Italian', 36);
define('Media_Translate_Deutsch', 37);
define('Media_Translate_Filipino', 38);
define('MEDIA_DELETE', 39);
define('ALBUM_DELETE', 40);

define('THINGSTODOREGION_INSERT', 41);
define('THINGSTODOREGION_UPDATE', 42);
define('THINGSTODOREGION_DELETE', 43);
define('THINGSTODOCOUNTRY_INSERT', 44);
define('THINGSTODOCOUNTRY_UPDATE', 45);
define('THINGSTODOCOUNTRY_DELETE', 46);
define('THINGSTODOCITY_INSERT', 47);
define('THINGSTODOCITY_UPDATE', 48);
define('THINGSTODOCITY_DELETE', 49);
define('THINGSTODOPOI_INSERT', 50);
define('THINGSTODOPOI_UPDATE', 51);
define('THINGSTODOPOI_DELETE', 52);

define('SEO_ALIAS_INSERT', 53);
define('SEO_ALIAS_UPDATE', 54);
define('SEO_ALIAS_DELETE', 55);
define('SEO_ALIAS_TRANSLATION', 56);
/* End of file constants.php */
/* Location: ./application/config/constants.php */