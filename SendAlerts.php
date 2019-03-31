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

$sql = "SELECT text,type FROM data_notifs WHERE notified IS NULL AND type IN('StructureUnderAttack','CorpWarDeclaredMsg','MoonminingLaserFired','MoonminingAutomaticFracture')";

$data = mysqli_query($conn, $sql);

if (!$data) {
	printf("Errormessage: %s\n", mysqli_error($conn));
}

//Iterate through rows
foreach($data as $row) {

	//Format Message and create variables
	$text = $row['text'];
	$type = $row['type'];
	$orecounter = 1;

	//Structure Attack
	if($type == "StructureUnderAttack") {
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
	if($type == "CorpWarDeclaredMsg") {
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
                        }
		}
		//Make the Body
		if($againstid = "98583004") {
			$body = "War declaration by ".$corpname.$alliancename."!\r\n";
			if(substr($declaredbyid,0,2)=="98") {
				$body = "War declaration by ".$corpname."!\r\n";
				$body = $body. "https://zkillboard.com/corporation/".$declaredbyid;
			}
			if(substr($declaredbyid,0,2)=="99") {
				$body = "War declaration by ".$alliancename."!\r\n";
				$body = $body."https://zkillboard.com/alliance/".$declaredbyid;
			}
		}
	}

	//Moon Mining Laser Fired
	if($type == "MoonminingLaserFired" || $type == "MoonminingAutomaticFracture") {
		$lines = explode(PHP_EOL, $text);
                foreach($lines as $line) {
			if(strpos($line, "firedBy:") === 0) {
                                $firedbyid = explode(": ",$line)[1];

				// Lets get the corp data with a GET
                                $remote_url = "https://esi.evetech.net/v4/characters/$firedbyid/";

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
                                $firedbydata = json_decode($result, true);
                                $firedbyname = $firedbydata['name'];

                        }
			if(strpos($line, "structureName") === 0) {
                                $structurename = explode(": ",$line)[1];
                        }
			//Ore Data
			if(strpos($line, "  4") === 0) {
				$oreamount = explode(" ",$line)[3];
                                $oreid = str_replace(":", "", explode(" ",$line)[2]);

				//Get Ore Name
                                $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
                                if (!$conn) {
                                        printf("Failed to connect to MySQL: " . mysqli_connect_error());
                                }

                                $sql = "SELECT typename FROM data_types WHERE typeid = ".$oreid." LIMIT 1";

                                $oredata = mysqli_fetch_assoc(mysqli_query($conn, $sql));

                                if (!$oredata) {
                                        printf("Errormessage: %s\n", mysqli_error($conn));
                                }
                                $orename = $oredata['typename'];

				if($orecounter == 1) {
					$ore1body = $orename.": ".number_format($oreamount,0)." m3\r\n";
					$oretotal = $oreamount;
				}
				if($orecounter == 2) {
                                        $ore2body = $orename.": ".number_format($oreamount,0)." m3\r\n";
                                }
				if($orecounter == 3) {
                                        $ore3body = $orename.": ".number_format($oreamount,0)." m3\r\n";
                                }
				if($orecounter == 4) {
                                        $ore4body = $orename.": ".number_format($oreamount,0)." m3\r\n";
                                }

				if($orecounter > 1) {
					$oretotal = $oretotal + $oreamount;
				}
				$orecounter = $orecounter + 1;
                        }
		}
		if($type == "MoonminingAutomaticFracture") {
			$body = $structurename." has fracked! **AUTOMATIC**"."\r\n";
		} else {
			$body = $structurename." has fracked! Thank you ".$firedbyname."!\r\n";
		}
		if($ore1body != "") {
			$body = $body.$ore1body;
		}
		if($ore2body != "") {
                        $body = $body.$ore2body;
                }
		if($ore3body != "") {
                        $body = $body.$ore3body;
                }
		if($orecounter == 5) {
                        $body = $body.$ore4body;
                }
		$body = $body."\r\nTotal: ".number_format($oretotal,0)." m3";
	}

	//Format Discord Notif
	$message = "@here\r\n".$body;
	$data = ['content' => $message];
	$options = [
	    'http' => [
	        'method' => 'POST',
	        'header' => 'Content-Type: application/json',
	        'content' => json_encode($data)
	    ]
	];
	$context = stream_context_create($options);

	if($type == "CorpWarDeclaredMsg" || $type == "StructureUnderAttack") {
	//Send Leadership Discord Notif
		$result = file_get_contents($leadershipwebhook, false, $context);
	}

	if($type == "MoonminingLaserFired" || $type == "MoonminingAutomaticFracture") {
	//Send Moons Discord Notif
		$result = file_get_contents($moonswebhook, false, $context);
	}

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
