<?php
/*! \file
* 
* \brief This api returns xml of catalogue 
* 
*\todo <b><i>Change from xml to Json object</i></b>
* 
* @param S session id
* 
* @return xml list:
* @return <pre> 
* @return       <b>user_id</b>  category id
* @return       <b>catalog_name</b>  category title
* @return </pre>
* @author Anthony Malak <anthony@touristtube.com>

* 
*  */

/*
function userCatalogSearch($srch_options)
{

$default_opts = array (
	'limit' => 1000,
	'page' => 0,
	'user_id' => null,
	'search_string' => null,
	'orderby' => 'id',
	'order' => 'a',
	'n_results' => false
	Asia/Beirut
	);
*/
	
//	session_id($_REQUEST['S']); 
// session_start();
$expath = "../";
header('Content-type: application/json');
include($expath."heart.php");

// $userID = $_SESSION['id'];
$submit_post_get = array_merge($request->query->all(), $request->request->all());

$uid = 0;
if (isset($submit_post_get['S']))
$uid = $submit_post_get['S'];

$userID = mobileIsLogged($uid);

$res = array();

if ($userID)
{
	//	$default_opts = array(
	//		'limit' => 10000000000,
	//		'page' => 0, 
	//		'user_id' => $userID,
	//		'search_string' => null,
	//		'orderby' => 'catalog_name',
	//		'order' => 'a',
	//		'n_results' => false
	//		);


	$default_opts = array (
		'user_id' => $userID,
		'orderby' => 'catalog_name',
		'order' => 'a'
	);

	$default_opts = array('is_owner' => 1, 'user_id' => $userID, 'order' => 'a', 'orderby' => 'catalog_name');
	$catalogues = userCatalogSearch($default_opts);

	// header("content-type: application/xml") ;  
	// echo '<pre>';
	// print_r($catalogues);

	$mod_catalogues = array();

	if ($catalogues)
	{
		foreach($catalogues as $cat)
		{
			$mod_catalogues[strtolower(trim($cat['catalog_name']))] = $cat;		
		}

		ksort($mod_catalogues);
	}

	/*
	$res = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>";
	$res .= "<catalogues>";

	foreach($mod_catalogues as $cat)
	{
		$res .= '<catalogue>';
		$res .= '<user_id>'.safeXML($cat['user_id']).'</user_id>';
		$res .= '<catalog_name>'.safeXML($cat['catalog_name']).'</catalog_name>';
		$res .= '</catalogue>';
	}

	$res .= "</catalogues>";	
	*/

	if ($mod_catalogues)
		foreach($mod_catalogues as $cat)
		{
			$res[] = array (
				'user_id' => $cat['user_id'],
				'catalog_id' => $cat['id'],
				'catalog_name' => $cat['catalog_name'],
			);    
		}

	/*
	$res= array();

	foreach($catalogues as $k => $cat)
	{
		$res[] = array('user_id' => $cat['user_id'], 'catalog_name' => $cat['catalog_name']);
	}


	header("Content-type: application/json; charset=utf-8");
	echo json_encode($res);
	*/
}

echo json_encode($res);