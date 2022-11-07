<?php 
/*! \file
 * 
 * \brief This api returns the review on point of interest that was added
 * 
 * \todo <b><i>Change from comma seprated string to Json object</i></b>
 * 
 * @param locid location id
 * @param uid user id
 * 
 * @return comma seprated string:
 * @return <pre> 
 * @return        <b>review</b> point of interest review(comment) added
 * @return        <b>rating</b> point of interest average rating added
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 *
 *  */
	$expath = "../";
	
	
	include($expath."heart.php");

//	$loc_id = $_GET['locid'];
//	$user_id = $_GET['uid'];
	$loc_id  = $request->query->get('locid','');
	$user_id = $request->query->get('uid','');
	
	$data = locationReviewGet($loc_id,$user_id);
	if (sizeof($data) || $data)
	{	
		if($data['review'] != null)
		{
			echo $data['review'].",".$data['rating'];
		}
		else
		{
			echo "No Review,0";
		}
	}