<?php

	/**
	 * make a request to the TouristTube API. options include:<br/>
	 * <b>api</b> string. the api url. required.<br/>
	 * <b>data</b> array. the data to be posted to the api.<br/>
	 * <b>return</b> string. return type. 'json' or 'xml'. default 'json'.<br/>
	 * <b>raw</b> boolean. returns the raw output or the json array/xml document. default false.<br/>
	 * <b>ssid</b> string the session id string. default current session id.<br/>
	 * @param array $options the options of the api
	 */
	function TTCallAPI($options){
		global $request;
		//defaults
		$return = isset($options['return']) ? $options['return'] : 'json';
		$raw = isset($options['raw']) ? $options['raw'] : false;
		$sid = isset($options['ssid']) ? $options['ssid'] : '';
                    $SERVER_NAME_server = $request->server->get('SERVER_NAME', '');
                    $SERVER_PORT_server = $request->server->get('SERVER_PORT', '');
		if( file_exists('inc/dev.config.php') ){
                    $api_url = $SERVER_NAME_server . ":".$SERVER_PORT_server. ReturnLink('uapi/' . trim($options['api'],' /') );
                }else{
                    $api_url = $SERVER_NAME_server . ":91" . ReturnLink('uapi/' . trim($options['api'],' /') );
                }
               
		$postfields = "";
		if(isset($options['data'])){
			$postfields = http_build_query($options['data']);
		}
		if($postfields != '') $postfields .= '&';
		$postfields .= "return={$return}";
		$postfields .= "&sid={$sid}";
                
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$ret = curl_exec($ch);
                
		//return
		if($raw){
			return $ret;
		}else if($return == 'json'){
			return json_decode($ret, true);
		}else{
			$xmlDoc = new DOMDocument();
			$xmlDoc->load($ret);
			return $xmlDoc;
		}
		
	}
	