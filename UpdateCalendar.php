<?php

// Lets get the calendar data with a GET
$remote_url = 'https://esi.evetech.net/v1/characters/2114745778/calendar/';

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

$calendardata = json_decode($result, true);

foreach ($calendardata as $row) {
	$eventid = $row['event_id'];

	// Get Event Data from Calendar ID
	$remote_url = "https://esi.evetech.net/v3/characters/2114745778/calendar/$eventid/";

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

	$eventdata = json_decode($result, true);

	//Event ID
	$contractid = $eventdata['event_id'];

	//Date
	$date = new DateTime($eventdata['date']);
	$date = $date ->format('Y-m-d-H-i-s');

	//Text
	$text = mysqli_escape_string($conn, $eventdata['text']);

	//Write the calendar table
	$sql = "INSERT IGNORE INTO data_calendar (eventid,date,text) VALUES ($eventid,'$date','$text')";

	$query = mysqli_query($conn, $sql);

	if (!$query) {
	    printf("Errormessage: %s\n", mysqli_error($conn));
	}
}

?>
