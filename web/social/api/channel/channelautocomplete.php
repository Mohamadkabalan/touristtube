<?php
	
	$expath = "../";			
	header('Content-type: application/json');
	include("../heart.php");
	$res  = array();
	
	/**
	 * gets the channel info depending on search criteria. options include:<br/>
	 * <b>limit</b>: the maximum number of media records returned. default 6<br/>
	 * <b>page</b>: the number of pages to skip. default 0<br/>
	 * <b>public</b>: wheather the channel is public or not. default 1<br/>
	 * <b>owner_id</b>: the channel's owner's id. default null<br/>
	 * <b>orderby</b>: the order to base the result on. values include any column of table. default 'id'<br/>
	 * <b>order</b>: (a)scending or (d)escending. default 'a'<br/>
	 * <b>name</b>: the channel's name default null<br/>
	 * <b>url</b>: the channel's url default null<br/>
	 * <b>id</b>: the channel's id default null<br/>
	 * <b>strict_search</b>: search for the channel's name exactly default 1. 2 for start match expression<br/>
	 * @param array $srch_options 
	 * @return array | false an array of 'cms_channel' records or false if none found.
	 */
	$limit = 10;
$submit_post_get = array_merge($request->query->all(),$request->request->all());
//	if (isset($_REQUEST['limit']))
//	{
//		$limit = intval($_REQUEST['limit']);
	if (isset($submit_post_get['limit']))
	{
		$limit = intval($submit_post_get['limit']);
	}
	
	$page = 0;
//	if (isset($_REQUEST['page']))
//	{
//		$page = intval($_REQUEST['page']);
	if (isset($submit_post_get['page']))
	{
		$page = intval($submit_post_get['page']);
	}
	
	$owner_id = null;
//	if (isset($_REQUEST['owner_id']))
//	{
//		$owner_id = intval($_REQUEST['owner_id']);
	if (isset($submit_post_get['owner_id']))
	{
		$owner_id = intval($submit_post_get['owner_id']);
	}
	
	$category_id = null;
//	if (isset($_REQUEST['category_id']))
//	{
//		$category_id = intval($_REQUEST['category_id']);
	if (isset($submit_post_get['category_id']))
	{
		$category_id = intval($submit_post_get['category_id']);
	} 
	
	$name = null;
//	if (isset($_REQUEST['name']))
//	{
//		$name = $_REQUEST['name'];
	if (isset($submit_post_get['name']))
	{
		$name = $submit_post_get['name'];
	} 
	
	$search_opts = array(
			'limit' => $limit,
			'page' => $page,
			'field' => 'channel_name',
			'owner_id' => $owner_id,
			'category' => $category_id,
			'orderby' => 'id',
			'order' => 'a',
			'name' => $name,
			'url' => null,
			'id' => null,
			'strict_search' => 0
		);
	
	$search = channelSearch($search_opts);
	if ($search)
	{
		foreach ($search as $chan)
		{
                        $item = array();
				
                        foreach($chan as $k=>$v)
                        {
                                if (is_string($k))
                                {
                                        switch ($k)
                                        {
                                            case 'channel_name' :
                                                $item['name'] = safeXML($v);
                                                break;
                                            case 'id':
                                                $item['id'] = $v;
                                                break;
                                        }
                                }
                        }
                        if ($chan['logo'] == '') {
                            $item['logo'] = 'media/tubers/tuber.jpg';
                        } else {
                            $item['logo'] = 'media/channel/' . $chan['id'] . '/thumb/' . $chan['logo'];
                        }
                        $res[] = $item;
		}
	}
	
	echo json_encode($res);