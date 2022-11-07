<?php
/*! \file
 * 
 * \brief This api returns array of category 
 * 
 * 
 * @return JSON list with the following keys:
 * @return <pre> 
 * @return       <b>id</b>  category id
 * @return       <b>value</b>  category title
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>

 * 
 *  */
    // fixing session issue

    

    $expath    = "../";
    require_once($expath."heart.php");
    header('Content-type: application/json');
    $submit_post_get = array_merge($request->query->all(),$request->request->all());
    $hide_all = (isset($submit_post_get['hide_all']) && intval($submit_post_get['hide_all']));
    $cats = categoryGetHash(array('hide_all' => $hide_all));

   /* $rs = "<categories>";
    foreach($cats as $key=>$cat){
            $rs .= "<category id='".$key."'>".safeXML($cat)."</category>";		
    }
    $rs .= "</categories>";
    header("content-type: application/xml; charset=utf-8");  
    echo $rs; */
    
    /* Change xml to json by Mukesh*/
    $rs = array();
    foreach($cats as $key=>$cat){
            $rs[] = array('id' => $key, 'value' => $cat);
    }
    echo json_encode($rs);