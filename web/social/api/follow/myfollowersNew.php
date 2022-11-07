<?php

//if (isset($_REQUEST['S'])) {
//    session_id($_REQUEST['S']);
$submit_post_get = array_merge($request->query->all(),$request->request->all());
if (isset($submit_post_get['S'])) {
    session_id($submit_post_get['S']);

    $expath = "../";

    include("../heart.php");
    $userId = $_SESSION['id'];



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
        'order' => 'a',
        'n_results' => false
    );

    $myfollowers = userSubscriberSearch($default_opts);

    $res = '<followers>';

    foreach ($myfollowers as $afollower) {
        $res = '<follower>';
        foreach ($afollower as $k => $v) {
            $res .= "<$k>$v</$k>";
        }
        $res = '</follower>';
    }

    $res .= '</followers>';

    header("content-type: application/json; charset=utf-8");
    //echo $res;
    $xml_cnt = str_replace(array("\n", "\r", "\t"), '', $res);    // removes newlines, returns and tabs
// replace double quotes with single quotes, to ensure the simple XML function can parse the XML
    $xml_cnt = trim(str_replace('"', "'", $xml_cnt));
    $simpleXml = simplexml_load_string($xml_cnt);

    echo json_encode($simpleXml);    // returns a string wit
}