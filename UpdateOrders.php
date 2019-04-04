<?php

//Lets's truncate the Orders table and set our selection array
$sql = 'TRUNCATE data_orders';

$query = mysqli_query($conn, $sql);

if (!$query) {
    printf("Errormessage: %s\n", mysqli_error());
}

$itemarray = array(34,35,36,37,38,39,40,16272,16273,16274,16275,16633,16634,16635,16636,17887,17888,17889,28435,28436,28437,28439,28434,45490,45491,45492,45493,46280,46281,46282,46283,46284,46285,46286,46287,46675,46676,46677,46678,46679,46680,46681,46682,46683,46684,46685,46686,46687,46688,46689,46691,46692,46693,46694,46695,46696,46697,46698,46699,46700,46701,46702,46703,46704,46705);

//---------------------------------------------JITA-------------------------------------
foreach ($itemarray as &$value) {
// Lets get the market data for each item in array
$remote_url = "https://esi.evetech.net/latest/markets/10000002/orders/?datasource=tranquility&order_type=buy&page=1&type_id=$value";

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

$jitagneissdata = json_decode($result, true);

//Loop through each row and grab Jita 4-4
foreach ($jitagneissdata as $row) {
	if ($row['location_id'] == 60003760) {

		//Order ID
		$orderid = $row['order_id'];

		//Location ID
		$locationid = $row['location_id'];

		//Price
		$price = $row['price'];

		//Type ID
		$typeid = $row['type_id'];

		//Volume Remain
		$volumeremain = $row['volume_remain'];

		//Volume Total
		$volumetotal = $row['volume_total'];

		//Write the structures table
		$sql = "REPLACE INTO data_orders (orderid,locationid,price,typeid,volumeremain,volumetotal) VALUES ($orderid,$locationid,$price,$typeid,$volumeremain,$volumetotal)";

		$query = mysqli_query($conn, $sql);

		if (!$query) {
		    printf("Errormessage: %s\n", mysqli_error($conn));
		}
	}
}

//------------------------------AMARR-----------------------------------

// Lets get the market data for Amarr
$remote_url = "https://esi.evetech.net/latest/markets/10000043/orders/?datasource=tranquility&order_type=buy&page=1&type_id=$value";

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

$jitagneissdata = json_decode($result, true);

//Loop through each row and grab Amarr
foreach ($jitagneissdata as $row) {
	if ($row['location_id'] == 60008494) {

		//Order ID
		$orderid = $row['order_id'];

		//Location ID
		$locationid = $row['location_id'];

		//Price
		$price = $row['price'];

		//Type ID
		$typeid = $row['type_id'];

		//Volume Remain
		$volumeremain = $row['volume_remain'];

		//Volume Total
		$volumetotal = $row['volume_total'];

		//Write the structures table
		$sql = "REPLACE INTO data_orders (orderid,locationid,price,typeid,volumeremain,volumetotal) VALUES ($orderid,$locationid,$price,$typeid,$volumeremain,$volumetotal)";

		$query = mysqli_query($conn, $sql);

		if (!$query) {
		    printf("Errormessage: %s\n", mysqli_error($conn));
		}
	}
}

}


?>
