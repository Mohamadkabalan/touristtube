<?php
/**
 * Abstraction layer for mysql database api
 * @package db
 */

	/**
	 * @ignore
	 */
	$_tt_db_link = null;
	/**
	 * connects to the database
	 * @param string $username db username
	 * @param string $password db pssword
	 * @param string $host db host
	 * @return boolean
	 */
	function db_connect($host, $username,$password){
                global $dbConn;
//		global $_tt_db_link;
//		$_tt_db_link = mysql_connect($host, $username, $password );
//		if( $_tt_db_link == false ) return false;
//		else{
//			mysql_query("SET NAMES 'UTF8'");
//			return true;
//		}
                try {   
                        $connection = 'mysql:host='.$host.';dbname='.$dbConfig[ 'name' ];
                        //$connection = 'mysql:host='.$dbConfig[ 'host' ].';port='.$dbConfig[ 'port' ].';dbname='.$dbConfig[ 'name' ];
                        $username   = $username;
                        $password   = $password;
                        $conn       = new PDO($connection, $username, $password);
                        
                        $query = "SET NAMES 'UTF8'";
                        $select = $conn->prepare($query);
                        $select->execute();
                        
                        return true;

                } catch (PDOException $e) {
                            echo "Failed to get DB handle: " . $e->getMessage() . "\n";
                            return false;
                            exit;
                }
	}
	
	/**
	 * selects the database name
	 * @param string $db 
	 */
	function db_select_db($db){
        //  Changed by Anthony Malak 22-04-2015 to PDO database
        //  <start>
                global $dbConn;
//		global $_tt_db_link;
//		return mysql_select_db($db);
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * close the connection to the db 
	 */
	function db_close(){
        //  Changed by Anthony Malak 22-04-2015 to PDO database
        //  <start>
                global $dbConn;
//		global $_tt_db_link;
//		mysql_close($_tt_db_link);
//		$_tt_db_link = null;
                $dbConn = null;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * sanitizes an input string
	 * @param string $in input string to be sanitized
	 * @return string the sanitized string
	 */
	function db_sanitize($in){
            //$in = mysql_real_escape_string($in);
            //$in1= preg_replace(array('/union/i','/\(/','/\)/','/;/','/%40/','/%41/','/%59/'),'', $in1 );
            return $in;
	}
	/**
	 * returns the number of rows in a result set
	 * @param resource $res the result set
	 * @return integer
	 */
	function db_num_rows($res){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
            global $dbConn;
//          return mysql_num_rows($res);
//          $res1 = $res->fetchColumn();
//          return $res1;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * executes a query on the database
	 * @param string $query the query
	 * @return resource|boolean a result set resource if select or false if couldnt execute
	 */
	function db_query($query){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
            global $dbConn;
            TTDebug(DEBUG_TYPE_QUERY, DEBUG_LVL_QUERY, $query);
//          return mysql_query($query);	
//          $res = $dbConn->query($query);
//          return $res;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * gets the last error thatoccurred in the database
	 * @return string the error string 
	 */
	function db_error(){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
            global $dbConn;
//		return mysql_error();
                $res = $dbConn->errorInfo();
		return $res;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * gets the last inserted id
	 * @return integer the inserted id
	 */
	function db_insert_id(){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
            global $dbConn;
            $res =$dbConn->lastInsertId();;
            return  $res;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * fetches a numeric array from the result set
	 * @param resource $res the db result set
	 */
	function db_fetch_row($res){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
            global $dbConn;
//          $res = $dbConn->fetch();
//          return $res;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * fetches a hash from the result set
	 * @param resource $res the db result set
	 */
	function db_fetch_assoc($res){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
            global $dbConn;
//          $res = $dbConn->fetchAll(PDO::FETCH_ASSOC);
//          return $res;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * fetches both a numeric array and hash from the result set
	 * @param resource $res the db result set
	 */
	function db_fetch_array($res){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
            global $dbConn;
//          return mysql_fetch_array($res);
//          $res = $select->fetchAll(PDO::FETCH_ASSOC);
//          return $res;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * returns the number of 
	 * @return type 
	 */
	function db_affected_rows(){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
            global $dbConn;
//          return mysql_affected_rows();
//          $res = $insert->rowCount();
//          return $res;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * gets the connection id
	 * @return integer the connection id or null if error
	 */
	function db_connection_id(){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
	global $dbConn;
		$query = "SELECT CONNECTION_ID()";
//		$res = db_query($query);
                $select = $dbConn->prepare($query);
                $res = $select->execute();

                $ret    = $select->rowCount();
		if(!$res || ($ret ==0) ) return null;
		$row = $select->fetch();
		return $row[0];
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
	
	/**
	 * trys to kill a db thread
	 * @param $conn_id the connection id
	 * @return true|false if sucess|fail
	 */
	function db_connection_kill($conn_id){
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <start>
            global $dbConn;
            $query = "KILL $conn_id";
            $select = $dbConn->prepare($query);
            $res = $select->execute();
            return $res;
//  Changed by Anthony Malak 22-04-2015 to PDO database
//  <end>
	}
        
        /**
	 * take an array of arguments and bind them to the query
	 * @param $select   the object of the query   
         * @param $argument An array of arguments to bind them
	 * @return true|false if sucess|fail
	 */
	function PDO_BIND_PARAM($select,$arguments){
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <start>
        global $dbConn;
            foreach($arguments as $item){
                if( isset($item['type']) && $item['type'] ){
                    if($item['type'] = "::PARAM_INT"){
                        $select ->bindParam($item['key'],$item['value'],$dbConn::PARAM_INT );
                    }else if($item['type'] = "::PARAM_STR"){
                        $select ->bindParam($item['key'],$item['value'],$dbConn::PARAM_STR );
                    }else if($item['type'] = "::PARAM_NULL"){
                        $select ->bindParam($item['key'],$item['value'],$dbConn::PARAM_NULL );
                    }else if($item['type'] = "::PARAM_BOOL"){
                        $select ->bindParam($item['key'],$item['value'],$dbConn::PARAM_BOOL );
                    }else if($item['type'] = "::PARAM_LOB"){
                        $select ->bindParam($item['key'],$item['value'],$dbConn::PARAM_LOB );
                    }else if($item['type'] = "::PARAM_STMT"){
                        $select ->bindParam($item['key'],$item['value'],$dbConn::PARAM_STMT );
                    }else if($item['type'] = "::PARAM_INPUT_OUTPUT"){
                        $select ->bindParam($item['key'],$item['value'],$dbConn::PARAM_INPUT_OUTPUT );
                    }
                }else{
                    $select ->bindParam($item['key'],$item['value'] );
                }
            }
//  Changed by Anthony Malak 24-04-2015 to PDO database
//  <end>
	}
	