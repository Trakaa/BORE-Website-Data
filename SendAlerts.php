<?php

//Set Variables
$settings = parse_ini_file("Settings.ini");

$dbuser = $settings["Username"];
$dbpass = $settings["Password"];
$dbhost = 'localhost';
$dbname = 'data_bni';

$leadershipwebhook = $settings["Leadership"];
$moonswebhook = $settings["Moons"];

$t1addresses = $settings["Tier1Addresses"];
$t2addresses = $settings["Tier2Addresses"];
$subject = "BORE Alliance Alert";

//Get Data
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
	printf("Failed to connect to MySQL: " . mysqli_connect_error());
}

$sql = "SELECT text,type FROM data_notifs WHERE notified IS NULL AND type IN('StructureUnderAttack','CorpWarDeclaredMsg')";

$data = mysqli_query($conn, $sql);

if (!$data) {
	printf("Errormessage: %s\n", mysqli_error($conn));
}

//Iterate through rows
foreach($data as $row) {

	//Format Message
	$text = $row['text'];
	$type = $row['type'];

	//Structure Attack
	if($type = "StructureUnderAttack") {
		$lines = explode(PHP_EOL, $text);
		foreach($lines as $line) {
			if(strpos($line, "allianceName") === 0) {
				$alliancename = ", a member of ".explode(": ",$line)[1].",";
			}
			if(strpos($line, "corpName") === 0) {
                                $corpname = explode(": ",$line)[1];
                        }
			if(strpos($line, "shieldPercentage") === 0) {
                                $shieldpercentage = explode(": ",$line)[1];
                        }
			if(strpos($line, "armorPercentage") === 0) {
                                $armorpercentage = explode(": ",$line)[1];
                        }
			if(strpos($line, "hullPercentage") === 0) {
                                $hullpercentage = explode(": ",$line)[1];
                        }
                        if(strpos($line, "structureID") === 0) {
                                $structureid = explode(" ",$line)[2];

				//Get Structure Name
				$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
				if (!$conn) {
				        printf("Failed to connect to MySQL: " . mysqli_connect_error());
				}

				$sql = "SELECT structurename FROM data_structures WHERE structureid = ".$structureid." LIMIT 1";

				$structuredata = mysqli_fetch_assoc(mysqli_query($conn, $sql));

				if (!$structuredata) {
				        printf("Errormessage: %s\n", mysqli_error($conn));
				}
				$structurename = $structuredata['structurename'];
                        }
		}
		//Make the Body
	        $body = $corpname.$alliancename." has attacked ".$structurename."!\r\n";
        	$body = $body."Shields: ".number_format($shieldpercentage,0)."%\r\n";
	        $body = $body."Armor: ".number_format($armorpercentage,0)."%\r\n";
	        $body = $body."Hull: ".number_format($hullpercentage,0)."%\r\n";
	}

	//Corp War Dec
	if($type = "CorpWarDeclaredMsg") {
                $lines = explode(PHP_EOL, $text);
                foreach($lines as $line) {
			if(strpos($line, "againstID") === 0) {
                                $againstid = explode(": ",$line)[1].",";
                        }
			if(strpos($line, "declaredByID") === 0) {
                                $declaredbyid = explode(": ",$line)[1];

				if(substr($declaredbyid,0,2)=="98"){
					// Lets get the corp data with a GET
					$remote_url = "https://esi.evetech.net/v4/corporations/$declaredbyid/";

					$opts = array(
					  'http' => array(
					    'method' => 'GET',
					    'header' => array(
					         "User-Agent: ...",
					         "Host: esi.evetech.net"
					    ),
					  )
					);

					$context = stream_context_create($opts);

					// Parse the results into an array
					$result = file_get_contents($remote_url, false, $context);
					$corpdata = json_decode($result, true);
					$corpname = $corpdata['name'];
				}
				if(substr($declaredbyid,0,2)=="99"){
                                        // Lets get the alliance data with a GET
                                        $remote_url = "https://esi.evetech.net/v3/alliances/$declaredbyid/";

                                        $opts = array(
                                          'http' => array(
                                            'method' => 'GET',
                                            'header' => array(
                                                 "User-Agent: ...",
                                                 "Host: esi.evetech.net"
                                            ),
                                          )
                                        );

					$context = stream_context_create($opts);

					// Parse the results into an array
					$result = file_get_contents($remote_url, false, $context);
					$alliancedata = json_decode($result, true);
					$alliancename = $alliancedata['name'];
                                }


$context = stream_context_create($opts);

// Parse the results into an array
$result = file_get_contents($remote_url, false, $context);

$moondata = json_decode($result, true);
                        }
		}
		//Make the Body
		if($againstid = "98583004") {
			$body = "War declaration by ".$corpname.$alliancename."!\r\n";
			if(substr($declaredbyid,0,2)=="98") {
				$body = $body. "https://zkillboard.com/corporation/".$declaredbyid;
			}
			if(substr($declaredbyid,0,2)=="99") {
				$body = $body."https://zkillboard.com/alliance/".$declaredbyid;
			}
		}
	}

	//Format Discord Notif
	$message = $body;
	$data = ['content' => $message];
	$options = [
	    'http' => [
	        'method' => 'POST',
	        'header' => 'Content-Type: application/json',
	        'content' => json_encode($data)
	    ]
	];
	$context = stream_context_create($options);

	//Send Discord Notif
	$result = file_get_contents('$leadershipwebhook', false, $context);

	//Send Text
	//mail($t1addresses . "," . $t2addresses,$subject,$text);

}

//Set notif flag to 1.
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    printf("Failed to connect to MySQL: " . mysqli_connect_error());
}

$sql = "UPDATE data_notifs SET notified = 1";

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error());
}

?>
