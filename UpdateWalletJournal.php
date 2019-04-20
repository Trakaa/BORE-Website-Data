<?php

//Loop through each wallet Division 1-7
for ($i = 1; $i <= 7; $i++) {
	// Lets get the wallet data with a GET
	$remote_url = "https://esi.evetech.net/v4/corporations/$corporationid/wallets/$i/journal";

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

	$walletjournaldata = json_decode($result, true);

	foreach ($walletjournaldata as $row) {

		//Amount
		$amount = $row['amount'];

		//Balance
		$balance = $row['balance'];

		//Date
		$date = new DateTime($row['date']);
		$date = $date->format('Y-m-d H:i');

		//Description
		$description = mysqli_escape_string($conn, $row['description']);

		//First Party ID
		$firstpartyid = mysqli_escape_string($conn, $row['first_party_id']);

		//ID
		$id = $row['id'];

		//Ref Type
		$reftype = mysqli_escape_string($conn, $row['ref_type']);

		//Second Party ID
		$secondpartyid = mysqli_escape_string($conn, $row['second_party_id']);

		//Write the Notif table
		$sql = "INSERT IGNORE INTO data_walletjournal (division,amount,balance,date,description,firstpartyid,walletjournalid,reftype,secondpartyid) VALUES ($i,$amount,$balance,'$date','$description',$firstpartyid,$id,'$reftype',$secondpartyid)";

		$query = mysqli_query($conn, $sql);

		if (!$query) {
		    printf("Errormessage: %s\n", mysqli_error($conn));
		}
	}
}
?>
