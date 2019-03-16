<?php

require_once("wp-load.php");

//Set Variables
$settings = parse_ini_file("Settings.ini");

$dbuser = $settings["Username"];
$dbpass = $settings["Password"];
$dbhost = 'localhost';
$dbname = 'data_bni';

//Get Data
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
	printf("Failed to connect to MySQL: " . mysqli_connect_error());
}

$sql = "SELECT date,text FROM data_calendar WHERE notified IS NULL";

$data = mysqli_query($conn, $sql);

if (!$data) {
	printf("Errormessage: %s\n", mysqli_error($conn));
}

//Iterate through rows
foreach($data as $row) {

	//Format Message and create variables
	$text = $row['text'];
	$date = $row['date'];

	if(strpos($text, "The moon chunk extraction for ") === 0) {
		$moonname = str_replace("</a","",explode(">",$text)[1]);
	}

	if (explode("-",$date)[3] == 00) {
		$hour = 12;
	} elseif (explode("-",$date)[3] > 12) {
		$hour = (int)explode("-",$date)[3] - 12;
	} else {
		$hour = (int)explode("-",$date)[3];
	}

        if (explode("-",$date)[3] + 3 == 00) {
                $endhour = 12;
        } elseif (explode("-",$date)[3] + 3 > 12) {
                $endhour = (int)explode("-",$date)[3] - 9;
        } else {
                $endhour = (int)explode("-",$date)[3] + 3;
        }

	$minute = (int)explode("-",$date)[4];

	if($hour > 12) {
		$meridian = "pm";
	} else {
		$meridian = "am";
	}

        if($endhour > 12) {
                $endmeridian = "pm";
        } else {
                $endmeridian = "am";
        }

	$date = explode("-",$date)[0]."-".explode("-",$date)[1]."-".explode("-",$date)[2];

	// Create post object
	$postData = array(
	   'post_title' => 'Moon frack.',
	   'post_content' => $moonname,
	   'post_status' => 'publish',
	   'post_author' => 1,
	   'EventStartDate' => $date,
	   'EventEndDate' => $date,
	   'EventStartHour' => $hour,
	   'EventStartMinute' => $minute,
	   'EventStartMeridian' => $meridian,
	   'EventEndHour' => $endhour,
	   'EventEndMinute' => $minute,
	   'EventEndMeridian' => $endmeridian,
	   'EventVenueID' => 467,
	   'EventOrganizerID' => 462
	);

	// Insert the post into the database
	tribe_create_event( $postData );

}

//Set notif flag to 1.
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    printf("Failed to connect to MySQL: " . mysqli_connect_error());
}

$sql = "UPDATE data_calendar SET notified = 1";

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error());
}

?>
