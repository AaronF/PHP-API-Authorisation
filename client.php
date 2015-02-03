<?php
/**
 * Aaron Fisher
 * http://aaronfisher.co
 */


include_once("inc/funcs.comms.php");
//This is the client side of things

//Setting things up
$public_api_key = "Vowntk00hZeHQLpYtEw5";
$private_api_key = "T6hbDy1YiSaRSFbYcVqe";

//These are the values we will be posting
$value1 = "This is value 1";
$value2 = "This is value 2";
$value3 = "This is value 3";

//Post the current timestamp (replay attacks)
$timestamp = time();

//This is the URL we will be posting the request to
$url = "http://localhost/server.php";

$data = $url.$value1.$value2.$value3.$timestamp;

//Next we will generate the encrypted string that will be sent in the request
$hashed_string = hash_hmac("sha256", $data, $private_api_key);

if(class_exists('comms')){
	//Post the request to server.php and output the response (you may need to change the localhost depending on where you are hosting this)
	$comms = new comms;
	echo($comms->sendPOST("http://localhost/server.php", array("value1" => $value1, "value2" => $value2, "value3" => $value3, "timestamp" => $timestamp,  "public_api_key" => $public_api_key, "hashed_string" => $hashed_string)));
}
?>