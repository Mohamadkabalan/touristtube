<?php
/*! \file
 * 
 * \brief This api returns xml of fastest city
 * 
 *\todo <b><i>Change from xml to Json object</i></b>
 * 
 * @param c city name
 * @param ccode country code
 * 
 * @return xml:
 * @return <pre> 
 * @return       <b>id</b>  city id
 * @return       <b>country</b> country
 * @return       <b>accent</b> Accent City
 * @return       <b>longitude</b> longitude
 * @return       <b>Latitude</b> latitude
 * @return       <b>Population</b> City
 * @return </pre>
 * @author Anthony Malak <anthony@touristtube.com>
 * 
 *  */
//if (isset($_GET['c']))
header("content-type: application/xml; charset=utf-8");  
include("heart.php");
$c_get = $request->query->get('c','');;
if ($c_get)
{
	//if (is_numeric($_GET['id']))
	//{
		
		
//		if(isset($_GET['ccode']))
                $ccode_get = $request->query->get('ccode','');
		if($ccode_get)
		{
//                    getFastCityWithCoutnry($_GET['ccode'],$_GET['c']);	
                    getFastCityWithCoutnry($ccode_get,$c_get);	
		}else
		{
//                    getFastCity($_GET['c']);
                    getFastCity($c_get);
		}
	//}
}