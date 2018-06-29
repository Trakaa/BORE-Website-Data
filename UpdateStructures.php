<?php

// Lets get the extractions data with a GET
$remote_url = 'https://esi.tech.ccp.is/v2/corporations/853746728/structures/';

$opts = array(
  'http' => array(
    'method' => 'GET',
    'header' => array(
         "User-Agent: ...",
         "Authorization: Bearer $accesstoken ",
         "Host: esi.tech.ccp.is"
    ),
  )
);

$context = stream_context_create($opts);

// Parse the results into an array
$result = file_get_contents($remote_url, false, $context);

$structuresdata = json_decode($result, true);

//Structure Name
foreach ($structuresdata as $row) {
$structureid = $row['structure_id'];

// Get name from ID
$remote_url = "https://esi.tech.ccp.is/v1/universe/structures/$structureid/";

$opts = array(
  'http' => array(
    'method' => 'GET',
    'header' => array(
         "User-Agent: ...",
         "Authorization: Bearer $accesstoken ",
         "Host: esi.tech.ccp.is"
    ),
  )
);

$context = stream_context_create($opts);

// Parse the results into an array
$result = file_get_contents($remote_url, false, $context);

$structuredata = json_decode($result, true);

$structurename = mysqli_escape_string($conn, $structuredata['name']);

//Fuel Expire
$fuelexpires = new DateTime($row['fuel_expires']);
$fuelexpires = $fuelexpires->format('Y-m-d H:i');

//System Name
$systemid = mysqli_escape_string($conn, $row['system_id']);

$remote_url = "https://esi.tech.ccp.is/v3/universe/systems/$systemid/";

$opts = array(
  'http' => array(
    'method' => 'GET',
    'header' => array(
         "User-Agent: ...",
         "Authorization: Bearer $accesstoken ",
         "Host: esi.tech.ccp.is"
    ),
  )
);

$context = stream_context_create($opts);

// Parse the results into an array
$result = file_get_contents($remote_url, false, $context);

$systemdata = json_decode($result, true);

$systemname = mysqli_escape_string($conn, $systemdata['name']);

//Write the structures table
$sql = "REPLACE INTO data_structures (structureid,structurename,fuelexpires,systemid) VALUES ($structureid,'$structurename','$fuelexpires',$systemid)";

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error($conn));
}

//Write the systems table
$sql = "REPLACE INTO data_systems (systemid,systemname) VALUES ($systemid,'$systemname')";

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error($conn));
}

}

?>
