<?php 
require_once("curlApiCallInterface.php");
// How to call the function 

$apidata['address'] = "192.168.100.100"; 
$apidata['port'] = "9443"; 
$apidata['apikey'] = "eP68pvSMM8dvn051LL4d35569d438ue0"; 
$apidata['username'] = "apiuser" 
$apidata['password']= "apipassword"; 

$apidata['json'] = "{"lbcli":[{\"action\":\"add-vip\",\"vip\":\"vipname\",\"... more options .....\"}]}";
$results =  curlApiCallv2 ( $apidata );

print_r($results); 
