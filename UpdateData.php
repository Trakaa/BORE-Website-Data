<?php

//Set Variables
$settings = parse_ini_file("Settings.ini");

$dbuser = $settings["Username"];
$dbpass = $settings["Password"];
$dbhost = 'localhost';
$dbname = 'data_bni';

//Grab ESI Info from DB
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    printf("Failed to connect to MySQL: " . mysqli_connect_error());
}

$sql = 'SELECT clientid, clientsecret, refreshtoken FROM data_login WHERE description = "FE"';

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error());
}

$data = mysqli_fetch_array($query,MYSQLI_ASSOC);

$clientid = $data["clientid"];
$clientsecret = $data["clientsecret"];
$refreshtoken = $data["refreshtoken"];

// Lets get our access token with a POST to ESI
$remote_url = 'https://login.eveonline.com/oauth/token';

$opts = array(
  'http' => array(
    'method' => 'POST',
    'header' => array(
         "Authorization: Basic " . base64_encode("$clientid:$clientsecret"),
         "Content-Type: application/x-www-form-urlencoded",
         "Host: login.eveonline.com"
    ),
    'content' => "grant_type=refresh_token&refresh_token=$refreshtoken"
  )
);

$context = stream_context_create($opts);

// Parse the results and pull out the access token
$result = file_get_contents($remote_url, false, $context);

$data = json_decode($result, true);

$accesstoken = $data['access_token'];

//Call Structures
include 'UpdateStructures.php';

//Call Extractions
include 'UpdateExtractions.php';

//Call Observer Data
include 'UpdateObserverData.php';

//Call Notifs
include 'UpdateNotifs.php';

//Call Calendar
include 'UpdateCalendar.php';

//Update Last Update Field---------------------

// Set Variables

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    printf("Failed to connect to MySQL: " . mysqli_connect_error());
}

$sql = 'UPDATE data_login SET lastupdate = NOW() WHERE description = "FE"';

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error());
}


//Grab ESI Info from DB since our expir might be soon.
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    printf("Failed to connect to MySQL: " . mysqli_connect_error());
}

$sql = 'SELECT clientid, clientsecret, refreshtoken FROM data_login WHERE description = "BORE"';

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error());
}

$data = mysqli_fetch_array($query,MYSQLI_ASSOC);

$clientid = $data["clientid"];
$clientsecret = $data["clientsecret"];
$refreshtoken = $data["refreshtoken"];

// Lets get our access token with a POST to ESI
$remote_url = 'https://login.eveonline.com/oauth/token';

$opts = array(
  'http' => array(
    'method' => 'POST',
    'header' => array(
         "Authorization: Basic " . base64_encode("$clientid:$clientsecret"),
         "Content-Type: application/x-www-form-urlencoded",
         "Host: login.eveonline.com"
    ),
    'content' => "grant_type=refresh_token&refresh_token=$refreshtoken"
  )
);

$context = stream_context_create($opts);

// Parse the results and pull out the access token
$result = file_get_contents($remote_url, false, $context);

$data = json_decode($result, true);

$accesstoken = $data['access_token'];

//Call Orders Data
include 'UpdateOrders.php';

//Call Contracts Data
include 'UpdateContracts.php';

//Call Members Data
include 'UpdateMembers.php';

//Update Last Update Field---------------------

// Set Variables

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    printf("Failed to connect to MySQL: " . mysqli_connect_error());
}

$sql = 'UPDATE data_login SET lastupdate = NOW() WHERE description = "BORE"';

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error());
}

?>
