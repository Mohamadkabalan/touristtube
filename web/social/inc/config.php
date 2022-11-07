<?php
define('RESPONSIVE', true);

global $CONFIG;

$CONFIG['debug_lvl'] = 15; // 15 all, 8 query, 4 error, 2 warn, 1 info
$CONFIG['hash_salt'] = 'B9JeLcvCWyLuCJ4lgQEA0tq72EMZb5S3RduOPAcqsLtu5J2d4i';
$CONFIG['limit']['videos'] = 8;
$CONFIG['subdomain_suffix'] = '';
ini_set('display_errors', 0);

if (!defined('ENVIRONMENT')) 
{
    $_env = getenv('ENVIRONMENT');
    
    if ($_env !== false)
        define('ENVIRONMENT', $_env);
    else
        define('ENVIRONMENT', 'development');
}

if (!isset($CONFIG['server_name'])) 
{
	ini_set('display_errors', 0);
	$CONFIG['db']['host'] = "mdbdev01";
	$CONFIG['db']['port'] = 3306;
	$CONFIG['db']['name'] = "touristtube";
	$CONFIG['db']['user'] = "mysql_root";
	$CONFIG['db']['pwd'] = "Mr4+%FINDZm,:AGL";
	
	$CONFIG['db']['db_checker_user'] = 'db_checker';
	$CONFIG['db']['db_checker_password'] = 'JR4jHxmbQ5Ik#xyqMP!FUcxh+Va(SPLUXdnUW,uE';
	
	$CONFIG['server_name'] = "touristtube.com";
	
	$CONFIG['video']['videoCoverter'] = "/data/utilities/ffmpeg/ffmpeg";
	$CONFIG['video']['videoCoverterFolder'] = '/data/utilities/ffmpeg/';
	$CONFIG['video']['uploadPath'] = "media/videos/uploads/";
	$CONFIG['flash']['uploadPath'] = "media/flash/";
	$CONFIG['cam']['uploadPath'] = "media/cams/";
	$CONFIG['journal']['outputPath'] = "media/journal/";
	$CONFIG['catalog']['outputPath'] = "media/catalog/";
	$CONFIG['elastic']['ip'] = "esdev02:9200";
	$CONFIG['elastic']['index'] = "tt_v2.2";
	$CONFIG['elastic']['clientIp'] = "esdev01:9200";
	$CONFIG['elastic']['clientPORT'] = "9200";
	$CONFIG['elastic']['clientIndex'] = "tt_test";
	$CONFIG['cookie_path'] = "touristtube.com"; // getenv('COOKIE_PATH'); // 'tt.com';
	
	$CONFIG['server']['root'] = getenv('DOCUMENT_ROOT') . '/';
	
	$CONFIG['chat_server'] = 'http://localhost:3000/';
	$CONFIG['facebook_app_id'] = '1045138925510219';
	$CONFIG['facebook_app_secret'] = '87378d17c481361e8f8d526da84b3e50';
	$CONFIG['facebook_default_graph_version'] = 'v2.4';
	$CONFIG['subdomain_suffix'] = '';
	
	$CONFIG['solr_config'] = array(
		'endpoint' => array(
			'localhost' => array(
				'host' => '127.0.0.1',
				'port' => 8983,
				'path' => '/solr/'
			)
		)
	);
	
	// ini_set('display_errors', 1);
	
	$CONFIG['services']['logs']['base_dir'] = '/data/log/services';
	$CONFIG['services']['email']['base_dir'] = '/var/www/web/social/services';
	// DomainKeys Identified Mail
	$CONFIG['services']['email']['DKIM'] = array(
			'domain' => 'touristtube.com', 
			'selector' => 'phpmailer'
		);
	
	$CONFIG['services']['email']['providers']['socket_labs']['hosts'] = array(
		   array(
				'host' => 'smtp.socketlabs.com',
				'port' => '587',
				'username' => 'server12090',
				'password' => 'c6G4Xzf8ZLy2j',
				'is_smtp' => 'true',
				'security_type' => ''
			)
		);
}

if (file_exists('sandbox_config.php'))
{
	// sandbox_config.php should only contain sandbox-specific configurations, such as $CONFIG['subdomain_suffix']
	include_once 'sandbox_config.php';
}
