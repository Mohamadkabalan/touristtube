<?php
// php tmp_run.php &>elastic_imports.log &
// tail -f elastic_imports.log

if (php_sapi_name() != 'cli')
	exit;

$base_scripts_dir = __DIR__;

$include_dir = dirname(dirname(dirname($base_scripts_dir))).'/inc';

include_once($include_dir.'/config.php');

function script_debug($debug_string)
{
	echo "\n".date('c')."\t".$debug_string;
}

function seconds_to_time_string($seconds)
{
	$current_datetime = new DateTime();
	$input_datetime = new DateTime("@$seconds");
	
	// return $current_datetime->diff($input_datetime)->format('%a days, %h hours, %i minutes, and %s seconds');
	
	$date_interval = $current_datetime->diff($input_datetime);
	
	$breakdown_values = array();
	
	$dateItervalProperties = array(
	    'y' => 'year',
	    'm' => 'month',
	    'd' => 'day',
	    'h' => 'hour',
	    'i' => 'minute',
	    's' => 'second'
	);
	
	foreach ($dateItervalProperties as $designator => $dateIntervalProperty) {
	    if ($date_interval->$designator)
	        $breakdown_values[] = array(
	            $designator => $date_interval->$designator
	        );
	}
	
	if (!$breakdown_values)
	    return '0 seconds';
	
    $time_string = '';
    $last_index = count($breakdown_values) - 1;
    
    foreach ($breakdown_values as $indx => $time_info) {
        $designator = array_keys($time_info)[0];
        
        $time_string .= ($indx ? ', ' : '') . ($indx && $indx == $last_index ? 'and ' : '') . $time_info[$designator] . ' ' . $dateItervalProperties[$designator] . ($time_info[$designator] > 1 ? 's' : '');
    }
    
    return $time_string;
}

$scripts = array('import_airport_New.php','import_cities_New.php','import_deals_New.php','import_deals_attractions_New.php','import_hotel_amadeus_New.php','import_hotel_search_dictionary_New.php','import_hotels_cities_New.php','import_channels_New.php','import_media_New.php','import_poi_New.php','import_thingsToDoPages_New.php','import_location_New.php','import_discover_hotel.php','import_hotel_hrs_New.php','import_hotel_hrs_city_New.php'); // , 'import_rest_geolocation.php');

$n_scripts = count($scripts);

$start_time = time();

foreach ($scripts as $script_index => $script)
{
	script_debug('Launching script['.($script_index + 1)." / $n_scripts]:: $script");
	$script_start_time = time();
	
	system("php ${base_scripts_dir}/$script");
	
	script_debug("$script took ".seconds_to_time_string($script_start_time));
}

script_debug("Total execution time:: ".seconds_to_time_string($start_time)."\n\n");

?>