<?php
	include_once '../config/Database.php';
	include_once '../class/Bookings.php';

	//to get db connection
	$database = new Database();
	$db = $database->getConnection();

	$error = [];
	
	$booking = new Bookings($db);

	// paramter validations
	if(!isset($_POST['start_date']) || empty($_POST['start_date'])){
		$error['start_date'] = 'Please enter Start Date';
	}
	
	if(!isset($_POST['end_date']) || empty($_POST['end_date'])){
		$error['end_date'] = 'Please enter End Date';
	}
	
	if(!isset($_POST['prop_id']) || empty($_POST['prop_id'])){
		$error['prop_id'] = 'Please enter Property Ref.';
	}

	// print validation errors
	if(count($error) > 0){
		echo json_encode(['success'=>0,"errors"=>$error]);
		die();
	}

	// check function to check the availability
	$data = $booking->check();

	//response from property-portal
	if(count($data) > 0){
		$response = array(
			"available"=>0,
			"start_date"=>$_POST['start_date'],
			"end_date"=>$_POST['end_date']
		);
	}
	else{
		$response = array(
			"available"=>1,
			"start_date"=>$_POST['start_date'],
			"end_date"=>$_POST['end_date']
		);

	}

	// get availability from veeve of the same property
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://ms.veeve.com/api/v2/properties/'.$_POST['prop_id'].'/availability.json?startdate='.$_POST['start_date'].'&enddate='.$_POST['end_date'],
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	));

	$veev_response = curl_exec($curl);

	curl_close($curl);
	$veevdata = (json_decode($veev_response,true));

	//mix response of avail.
	$veevdata['portal-response'] = $response;


	echo json_encode(['success'=>1,'response'=>$veevdata]);
?>