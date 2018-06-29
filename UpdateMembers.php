<?php

// Lets get the members data with a GET
$remote_url = 'https://esi.tech.ccp.is/v3/corporations/853746728/members/';

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

$membersdata = json_decode($result, true);

foreach ($membersdata as $row) {
//Character ID
$characterid = $row;

// Lets get the members data with a GET
$remote_url = "https://esi.tech.ccp.is/v4/characters/$characterid/";

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

$memberdetailsdata = json_decode($result, true);

//Character Name
$charactername = mysqli_escape_string($conn, $memberdetailsdata['name']);

//Write the Members table
$sql = "REPLACE INTO data_members (characterid,charactername) VALUES ($characterid,'$charactername')";

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error($conn));
}

}

?>
