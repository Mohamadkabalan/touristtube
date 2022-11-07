<?php 
/*! \file
 * 
 * \brief This api returns if the review added on point of interest was successfully added or not
 * 
 * \todo <b><i>Change from String to Json object</i></b>
 * 
 * @param locid location id
 * @param uid user id
 * @param review comment added 
 * @param rating rating added
 * 
 * @return String success or not
 * @author Anthony Malak <anthony@touristtube.com>
 *
 * 
 *  */
	$expath = "../";
	
	
	include($expath."heart.php");

//	$loc_id = $_POST['locid'];
//	$user_id = $_POST['uid'];
//	$review = $_POST['review'];
//	$rating = $_POST['rating'];
	$loc_id = $request->request->get('locid', '');
	$user_id = $request->request->get('uid', '');
	$review = $request->request->get('review', '');
	$rating = $request->request->get('rating', '');
	if(locationReviewSet($loc_id, $user_id, $review, $rating) == true)
	{
		echo "Success";
	}
	else
	{
		echo "Fail";
	}