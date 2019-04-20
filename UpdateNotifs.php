<?php

// Lets get the mail data with a GET
$remote_url = 'https://esi.evetech.net/v4/characters/2114745778/notifications/';

$opts = array(
  'http' => array(
    'method' => 'GET',
    'header' => array(
         "User-Agent: ...",
         "Authorization: Bearer $accesstoken ",
         "Host: esi.evetech.net"
    ),
  )
);

$context = stream_context_create($opts);

// Parse the results into an array
$result = file_get_contents($remote_url, false, $context);

$notifdata = json_decode($result, true);

foreach ($notifdata as $row) {

//Notifcation ID
$notificationid = $row['notification_id'];

//Sender ID
$senderid = $row['sender_id'];

//Sender Type
$sendertype = mysqli_escape_string($conn, $row['sender_type']);

//Text
$text = mysqli_escape_string($conn, $row['text']);

//Timestamp
$timestamp = new DateTime($row['timestamp']);
$timestamp = $timestamp->format('Y-m-d H:i');

$type = mysqli_escape_string($conn, $row['type']);

//Write the Notif table
$sql = "INSERT IGNORE INTO data_notifs (notificationid,senderid,sendertype,text,timestamp,type) VALUES ($notificationid,$senderid,'$sendertype','$text','$timestamp','$type')";

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error($conn));
}

}

?>
