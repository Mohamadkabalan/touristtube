<?php
//	$connectionsType = intval($_POST['connectionsType']);
//	$connectionsValue = intval($_POST['connectionsValue']);
//	$connectionsArray = $_POST['connectionsArray'];
//	
//	$channel_id = isset($_POST['channel_id']) ? intval($_POST['channel_id']) : null;
	$connectionsType = intval($request->request->get('connectionsType', 0));
	$connectionsValue = intval($request->request->get('connectionsValue', 0));
	$connectionsArray = $request->request->get('connectionsArray', '');
	
	$channel_id = intval($request->request->get('channel_id', 0));
	
	$ret_arr['status'] = 'ok';
	
	$connection_ids = array();
	$sponsors_ids = array();
	$connection_kind = array();
	if($connectionsValue==PRIVACY_EXTAND_KIND_CUSTOM){
		foreach($connectionsArray as $connection_with){
			
			if( isset($connection_with['connections']) ){
				$connection_kind[] = PRIVACY_EXTAND_KIND_CONNECTIONS;
			}else if( isset($connection_with['sponsors']) ){
				$connection_kind[] = PRIVACY_EXTAND_KIND_SPONSORS;			
			}else if( isset($connection_with['channelid']) ){			
				$sponsors_id = intval( $connection_with['channelid'] );
				if (!in_array($sponsors_id, $sponsors_ids)) {
					$sponsors_ids[] = $sponsors_id;
				}	
				
			}else if( isset($connection_with['id']) ){			
				$connection_id = intval( $connection_with['id'] );
				if (!in_array($connection_id, $connection_ids)) {
					$connection_ids[] = $connection_id;
				}	
				
			}
			
		}
	}else{
		$connection_kind[] = $connectionsValue;
	}
	if(sizeof($connection_kind)>=2){
		$connection_ids = array();
		$sponsors_ids = array();	
	}
	$connection_ids_str=join(",",$connection_ids);
	$sponsors_ids_str=join(",",$sponsors_ids);
	$connection_kind_str=join(",",$connection_kind);
	if($connectionsValue!=0){
		if($connection_kind_str==''){
			$connection_kind_str=PRIVACY_EXTAND_KIND_CUSTOM;
		}
		channelPrivacyExtandEdit(array('channelid'=>$channel_id,'privacy_type'=>$connectionsType,'kind_type'=>$connection_kind_str,'connections'=>$connection_ids_str,'sponsors'=>$sponsors_ids_str));	
	}
?>
