<?php

// Lets get the observers data with a GET
$remote_url = 'https://esi.tech.ccp.is/v1/corporation/853746728/mining/observers';

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

$observersdata = json_decode($result, true);

//Observers
foreach ($observersdata as $row) {

//Oberserver ID
$observerid = $row['observer_id'];

//Last Updated
$lastupdated = new DateTime($row['last_updated']);
$lastupdated = $lastupdated->format('Y-m-d H:i');

//Observer Type
$observertype = $row['observer_type'];

//Write the structures table
$sql = "REPLACE INTO data_observers (observerid,lastupdated,observertype) VALUES ($observerid,'$lastupdated','$observertype')";

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error($conn));
}

//Lets get Observer Item data
$remote_url = "https://esi.tech.ccp.is/v1/corporation/853746728/mining/observers/$observerid/";

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

$observeritemsdata = json_decode($result, true);

foreach ($observeritemsdata as $row) {
//Observeritemsid
$observeritemsid = $observerid . $row['character_id'] . $row['last_updated'] . $row['recorded_corporation_id'] . $row['type_id'];

//Character ID
$characterid = $row['character_id'];

//Last Updated
$lastupdated = new DateTime($row['last_updated']);
$lastupdated = $lastupdated->format('Y-m-d H:i');

//Quantity
$quantity = $row['quantity'];

//Recorded Corp ID
$recordedcorporationid = $row['recorded_corporation_id'];

//Type ID
$typeid = $row['type_id'];

//Write the observer items table
$sql = "REPLACE INTO data_observeritems (observeritemsid,observerid,characterid,lastupdated,quantity,recordedcorporationid,typeid) VALUES ($observeritemsid,$observerid,$characterid,'$lastupdated',$quantity,$recordedcorporationid,$typeid)";

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error($conn));
}

}

}

?>
