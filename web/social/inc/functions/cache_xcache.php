<?php
/**
 * php XCACHE cache abstraction layer
 * @package cache_xcache
 * @category cache
 */

	/**
	 * gets a cache value
	 * @param string $var the variable to be retrieved
	 * @return mixed the value
	 */
//	function cacheGet($var){
//		return xcache_get($var);
//	}
	
	/**
	 * caches a variable
	 * @param string $var the name of the variable to store
	 * @param mixed $val the value to store
	 * @param integer $ttl the Time To Live in seconds
	 */
//	function cacheSet($var,$val,$ttl = 600){
//		xcache_set($var, $val, $ttl);
//	}
	
	/**
	 * checks if a variable is stored in the cache
	 * @param string $var the name of the variable to check
	 * @return boolean true|false if set or not
	 */
	function cacheIsSet($var){
		return xcache_isset($var);
	}
	
	/**
	 * unsets a variable from the cache
	 * @param string $var the name of the variable to remove
	 */
	function cacheUnset($var){
		xcache_unset($var);
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