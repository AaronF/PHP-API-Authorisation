<?php
/**
 * Aaron Fisher
 * http://aaronfisher.co
 */


//This is the server side of things

//Setting things up
//These two keys would usually be retrieved from a list in a database, but to save time I have stored them in two variables
$accepted_public_api_key = "Vowntk00hZeHQLpYtEw5";
$accepted_private_api_key = "T6hbDy1YiSaRSFbYcVqe";

//This is used to help precent MITM attacks where an attacker could change the end URI request
$this_url = "http://localhost/server.php";

if($_POST){
	/**
	 * Pulling the values out of POST
	 */
	$value1 = $_POST["value1"];
	$value2 = $_POST["value2"];
	$value3 = $_POST["value3"];

	$recieved_timestamp = $_POST["timestamp"];

	$recieved_public_api_key = $_POST["public_api_key"];

	$recieved_hashed_string = $_POST["hashed_string"];

	/**
	 * Processing request
	 */
	$current_server_timestamp = time();

	//Check to see if the timestamp that was provided is within reasonable terms (5 minutes of the server time)
	if($current_server_timestamp - 300 <= $recieved_timestamp && $recieved_timestamp <= $current_server_timestamp){
		//Timestamp is good!

		//Next we want to check whether the API key (public) they provided is in the system. or in our case matches the one we stored on this page
		if($recieved_public_api_key === $accepted_public_api_key){
			//Key is a match

			//Here we want to do exactly the same thing we did on the client side and see if we can generate the same hash as the one provided
			//We use the private key (which would be retrieved from a database using the public key)
			$data = $this_url.$value1.$value2.$value3.$recieved_timestamp;
			$server_hashed_string = hash_hmac("sha256", $data, $accepted_private_api_key);

			if($server_hashed_string === $recieved_hashed_string){
				//Success! The strings matched and all is good.
				echo "Hey! You are authenticated to be here";
			} else {
				//Failure :( This means that we could not generate the same hash from the provided data (it may have been tampered with during transit)
				echo "Uh oh, looks like someones been tampering with the wires!";
			}
		} else {
			//Key is not a match
			echo "Api key error";
		}
	} else {
		//Timestamp does not meet the requirements
		echo "Timestamp error";
	}
	
}
?>