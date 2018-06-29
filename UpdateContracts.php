<?php

// Lets get the contracts data with a GET
$remote_url = 'https://esi.tech.ccp.is/v1/corporations/853746728/contracts/?datasource=tranquility&assignee_id=853746728&page=1&type=item_exchange&price=0&reward=0';

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

$contractsdata = json_decode($result, true);

foreach ($contractsdata as $row) {
        //Order ID
        $contractid = $row['contract_id'];

        //Location ID
        $issuerid = $row['issuer_id'];

	//Date Issued
	$dateissued = new DateTime($row['date_issued']);
	$dateissued =  $dateissued ->format('Y-m-d');

        //Write the contracts table
        $sql = "REPLACE INTO data_contracts (contractid,issuerid,dateissued) VALUES ($contractid,$issuerid,'$dateissued')";

        $query = mysqli_query($conn, $sql);

        if (!$query) {
            printf("Errormessage: %s\n", mysqli_error($conn));
        }

	if ($row['volume'] > 0) {
	// Lets get the contract item data with a GET
	$remote_url = "https://esi.tech.ccp.is/v1/corporations/853746728/contracts/$contractid/items/";

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

	$contractitemsdata = json_decode($result, true);

	foreach ($contractitemsdata as $row) {

		//Record ID
		$recordid = $row['record_id'];

		//Type ID
        	$typeid = $row['type_id'];

		//Quantity
        	$quantity = $row['quantity'];

		//Write the contractitem table
	        $sql = "REPLACE INTO data_contractitems (recordid,contractid,typeid,quantity) VALUES ($recordid,$contractid,$typeid,$quantity)";

        	$query = mysqli_query($conn, $sql);

	        if (!$query) {
        	    printf("Errormessage: %s\n", mysqli_error($conn));
	        }

	}

}

}


?>
