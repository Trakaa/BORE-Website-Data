<?php

// Lets get the extractions data with a GET
$remote_url = 'https://esi.tech.ccp.is/latest/corporation/853746728/mining/extractions/';

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

$extractiondata = json_decode($result, true);

foreach ($extractiondata as $row) {
$chunkarive = new DateTime($row['chunk_arrival_time']);
$chunkarive = $chunkarive->format('Y-m-d H:i');

//extraction_start_time
$extractstart = new DateTime($row['extraction_start_time']);
$extractstart =  $extractstart ->format('Y-m-d H:i');

//Moon ID
$moonid = $row['moon_id'];

// Lets get the moons data with a GET
$remote_url = "https://esi.tech.ccp.is/v1/universe/moons/$moonid/";

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

$moondata = json_decode($result, true);

$moonname = mysqli_escape_string($conn, $moondata['name']);

//Natural Decay Time
$natdecay = new DateTime($row['natural_decay_time']);
$natdecay = $natdecay ->format('Y-m-d H:i');

//Structure ID
$structureid = $row['structure_id'];

//Extraction ID
$extractionid = $structureid . "-" . $moonid;

//Write the extractions table
$sql = "REPLACE INTO data_extractions (extractionid,chunkarrivaltime,extractionstarttime,moonid,naturaldecaytime,structureid) VALUES ('$extractionid','$chunkarive','$extractstart',$moonid,'$natdecay',$structureid)";

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error($conn));
}

//Write the moons table
$sql = "REPLACE INTO data_moons (moonid,moonname) VALUES ($moonid,'$moonname')";

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error($conn));
}

}

?>
