<?php
/**
 * php hash cache abstraction layer
 * a trivial hash caching abstraction layer useful for command line long running processes
 * @package cache_hash
 * @category cache
 */

	/**
	 * global hash to store the cache 
	 */
	$tt_hash_cache = array();
	
	/**
	 * enable debugging or not 
	 * @ignore
	 */
	define('TT_HASH_CACHE_DEBUG',1);
	/**
	 * how many seconds should pass before running the garbage collector 
	 * @ignore
	 */
	define('TT_HASH_CACHE_GC_RUN',30);
	
	/**
	 * global value to store the last run time of the garbage collector 
	 */
	$_hash_cache_gc_last_run = 0;
	
	/**
	 * run the garbage collector
	 * @ignore
	 */
	function _gc_run(){
		global $_hash_cache_gc_last_run;
		global $tt_hash_cache;
		$cur_time = time();
		//if( TT_HASH_CACHE_DEBUG ) echo "GC RUN 0\r\n";
		if( $_hash_cache_gc_last_run > $cur_time - TT_HASH_CACHE_GC_RUN) return ;
		//if( TT_HASH_CACHE_DEBUG ) echo "GC RUN 1\r\n";
		$_hash_cache_gc_last_run = $cur_time;
		foreach($tt_hash_cache as $cache_var => $cache_array){
			if($cache_array[0] < time()){
				//if( TT_HASH_CACHE_DEBUG ) echo "GC UNSET $cache_var\r\n";
				unset($tt_hash_cache[$cache_var]);
			}
		}
		//if( TT_HASH_CACHE_DEBUG ) echo cacheInfo() . "\r\n";
	}
	
	/**
	 * gets a string representation of the user cache
	 * @global array $tt_hash_cache
	 * @ignore
	 * @return string 
	 */
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<start>
//	function cacheInfo(){
//		global $tt_hash_cache;
//		return print_r($tt_hash_cache, true);
//	}
//Commented by Anthony Malak as asked from Charbel Khadra to remove because it's not used  27/04/2015
//<end>
	
	/**
	 * gets a cache value
	 * @param string $var the variable to be retrieved
	 * @return mixed the value or null if nothing
	 */
//	function cacheGet($var){
//		_gc_run();
//		global $tt_hash_cache;
//		if( isset($tt_hash_cache[$var]) ){
//			$cache = $tt_hash_cache[$var];
//			if($cache[0] < time() ){
//				//timeout exceeded
//				unset($tt_hash_cache[$var]);
//				return null;
//			}
//			return $cache[1];
//		}
//	}
	
	/**
	 * caches a variable
	 * @param string $var the name of the variable to store
	 * @param mixed $val the value to store
	 * @param integer $ttl the Time To Live in seconds
	 */
//	function cacheSet($var,$val,$ttl = 600){
//		_gc_run();
//		global $tt_hash_cache;
//		$new_cache = array(time() + $ttl, $val);
//		$tt_hash_cache[$var] = $new_cache;		
//	}
	
	/**
	 * checks if a variable is stored in the cache
	 * @param string $var the name of the variable to check
	 * @return boolean true|false if set or not
	 */
	function cacheIsSet($var){
		_gc_run();
		global $tt_hash_cache;
		if( isset($tt_hash_cache[$var]) ){
			$cache = $tt_hash_cache[$var];
			if($cache[0] < time() ){
				//timeout exceeded
				unset($tt_hash_cache[$var]);
				return false;
			}
			return true;
		}
	}
	
	/**
	 * unsets a variable from the cache
	 * @param string $var the name of the variable to remove
	 */
	function cacheUnset($var){
		_gc_run();
		global $tt_hash_cache;
		if( isset($tt_hash_cache[$var]) ){
			unset($tt_hash_cache[$var]);
		}
	}
	
	/**
	* edits a cached variable. typically used for editing a cached array.
	* @param string $var the name of the variable to edit
	* @param array $val the value to store
	* @return boolean true|false if success|fail 
	*/
//	function cacheEdit($var, $val) {
//		if (!cacheIsSet($var))
//			return false;
//		$old = cacheGet($var);
//		foreach ($val as $key => $val) {
//			$old[$key] = $val;
//		}
//		cacheSet($var, $old);
//	}