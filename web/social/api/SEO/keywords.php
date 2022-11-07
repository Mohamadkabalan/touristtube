<?php
$sql_host = "localhost";
$sql_user = "tourist";
$sql_pass = "touristMysqlP@ssw0rd";
$sql_dbna = "touristtube";

$conn = mysqli_connect($sql_host,$sql_user,$sql_pass);
$db_conn = mysqli_select_db($conn , $sql_dbna);
mysqli_set_charset($conn,"utf8");


$error = array();
$res = array();

$videosincountryFormSubmit = $request->request->get('videosincountryFormSubmit', '');
//if (isset($_POST['videosincountryFormSubmit']))
if (isset($_POST['$videosincountryFormSubmit']))
{
    $country_code = $request->request->get('country_code', '');
//	if (isset($_POST['country_code']))
	if ($country_code)
	{
//		$country_code = mysqli_real_escape_string($conn,$_POST['country_code']);
		$country_code = mysqli_real_escape_string($conn,$country_code);
		$sql = "SELECT id, title, image_video FROM cms_videos WHERE country = '".$country_code."' and `is_public`='2' ";
		$submit_sql = mysqli_query($conn,$sql);
		
		if ($submit_sql)
		{
			if (mysqli_num_rows($submit_sql)==0)
			{
				$error[] = "No Results for country code";	
			}else
			{
				$temp_res = "Videos Title for country Code '".$country_code."'<br/>";
				$titles = array();
				while($data = mysqli_fetch_array($submit_sql))
				{		
					$temp_res .= "".htmlEntityDecode($data['title'])."<br>";
					$titles [] = htmlEntityDecode($data['title']);
				}
				//$res[] = $temp_res;
				$res = array_unique($titles);
			}
		}else
		{
			$error[] = mysqli_error($conn);

		}
	}
}

$sql_query = "";


mysqli_close($conn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="Alphonse Semaan" />
<title>SEO Tool for TouristTube</title>
<style>
html
{
	margin:0;
	padding:0;	
	color:#eee; 
}
body
{
	margin:0;
	padding:0;
	width:100%;
	background-color:#000;
}
h1,h2,h3,h4,h5,h6,h7
{
	margin:5px;	
	text-align:center;
}
#Options
{
	padding:5px 0 15px 0;
	border-bottom:5px white solid;
}
.optionBox
{
	width:24%;
	margin:5px;
	border:1px white solid;	
	background-color:#CCC;
	color:#000;
	overflow:hidden;
}
#Results
{
	padding:5px;
	border:1px solid #FFF;
	color:#333;
	background-color:#CCC;
}
#Errors
{
	padding:5px;
	border:1px solid #900;
	color:#600;
	font-weight:bold;
}
#Result
{
	padding:5px;
	border:1px slid #030;
}
</style>
</head>

<body>
	<h2>what do you want to do?</h2>
	<div id="Options">
    	<div class="optionBox">
        	<h4>Get Videos Title In Country</h4>
            <p>
            	Country Code  
                <span>
                	<form name="videosincountry" action="" method="post" >
	                   	<input type="text" name="country_code" id="country_code" />
                        <input name="videosincountryFormSubmit" type="submit" value="ok!" />
                    </form>
                </span>
            </p>
            <h5>expand</h5>
        </div>		
    </div>
    <div id="Results">
    	<div id="Errors">
        	<?php
				foreach($error as $v)
				{
					echo $v;	
				}
			?>
        </div>
        <div id="Result">
        	<?php
				foreach($res as $v)
				{
					echo $v."<br>";	
				}
			?>
        </div>
    </div>
</body>
</html>
