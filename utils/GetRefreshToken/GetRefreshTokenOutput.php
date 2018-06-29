<?php
$code = $_GET['code'];
$state = $_GET['state'];

$clientid = "";
$clientsecret = "";
$remote_url = 'https://login.eveonline.com/oauth/token';

// Create a stream
$opts = array(
  'http' => array(
    'method' => 'POST',
    'header' => array(
	 "Authorization: Basic " . base64_encode("$clientid:$clientsecret"),
         "Content-Type: application/x-www-form-urlencoded",
         "Host: login.eveonline.com"
    ),
    'content' => "grant_type=authorization_code&code=$code"
  )
);

$context = stream_context_create($opts);

// Get the result using the HTTP headers set above
$result = file_get_contents($remote_url, false, $context);

$data = json_decode($result, true);

$refreshtoken = $data['refresh_token'];

echo $refreshtoken;

?>
