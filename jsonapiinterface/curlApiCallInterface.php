<?php  	
// lbcli php curl api/v2 interface 	
// loadbalancer.org (c) 2019 - by Andruw Smalley.     
// the api/v2 php function interface example.    
function curlApiCallv2 ( $apidata ) {
    $endpoint="https://".$apidata['address'].":".$apidata['port']."/api/v2/";		
    $ch = curl_init();		
    curl_setopt($ch, CURLOPT_URL, $endpoint);		
    curl_setopt($ch, CURLOPT_TCP_NODELAY,1);		
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);		
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);		
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch,CURLOPT_ENCODING , "");		
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X_LB_APIKEY:'.base64_encode($apidata['apikey']),
'Accept-Encoding: gzip, deflate','Connection: Close'));	
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_USERPWD, $apidata['username'].":".$apidata['password']);
    curl_setopt($ch, CURLOPT_POST, 1);		
    curl_setopt($ch, CURLOPT_POSTFIELDS, $apidata['json'] );		
    $result = curl_exec ($ch);		
    $ttime = curl_getinfo($ch,  CURLINFO_TOTAL_TIME);  
    //get status code		
    curl_close ($ch);		
    $result=str_replace("[]","\"\"",$result); 
    // clean empty arrays from the result. 
    $result=json_decode($result,true);		
    if(is_array($result) && isset($result['lbapi'])) {
        return $result['lbapi'][0]['itteration'][0]; 
        // we dont want all the itteration stuff yet, 			
        // for now we only need one itteration as we dont run multiple lbcli itterations yet!		
    } else {
    return $result;		
    }	
}
