<?php
//	if (isset($_REQUEST['S']))
//	{
$submit_post_get = array_merge($request->query->all(),$request->request->all());
	if (isset($submit_post_get['S']))
	{
//		session_id($_REQUEST['S']); 
//        session_start();
		
		$expath = "../";			
		
		include("../heart.php");
//		$userId = $_SESSION['id'];
//		$userId = mobileIsLogged($_REQUEST['S']);
		$userId = mobileIsLogged($submit_post_get['S']);
                if( !$userId ) die();
		
		
		/**
		 * searchs for tubers a tuber is subscribed to (following). options include:<br/>
		 * <b>limit</b>: integer - limit of record to return. default 6<br/>
		 * <b>page</b>: integer - how many pages of result to skip. default 0<br/>
		 * <b>userid</b>: integer - the user to search for. required<br/>
		 * <b>begins</b>: user names begin with this letter <br/>
		 * <b>orderby</b>: string - the cms_friends column to order the results by. default request_ts<br/>
		 * <b>order</b>: char - either (a)scending or (d)esceniding. default (a)<br/>
		 * <b>n_results</b>: returns the results or the number of results. default false
		 * @param array $srch_options search options
		 * @return array of result records
		 */
		
		$default_opts = array(
			'limit' => 30,
			'page' => 0,
			'userid' => $userId,
			'begins' => null,
			'search_string' => null,
			'orderby' => 'subscription_date',
			'order' => 'd',
			'n_results' => false
		);
		
		$myfollowers = userSubscriberSearch($default_opts);
		
		$res = '<followers>';
		
		foreach($myfollowers as $afollower)
		{
			$res = '<follower>';
			foreach($afollower as $k=>$v)
			{
				$res .= "<$k>$v</$k>";		
			}
			$res = '</follower>';
		}
				
		$res .= '</followers>';
		
		header("content-type: application/xml; charset=utf-8");  
		echo $res;
	}