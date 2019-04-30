<?php
include(dirname(__FILE__)."/../wp-load.php");
global $wpdb;
$failed = 0;
$errors = array();
$getData = array();

function randomNumber($length) {
		$result = '';
		for($i = 0; $i < $length; $i++) {
		$result .= mt_rand(0, 9);
		}
		return $result;
}
if ($_SERVER['REQUEST_METHOD'] == POST){
	$data = json_decode(file_get_contents('php://input'),TRUE);
		
		if (!isset($data['device_type']) || $data['device_type'] == "") {
			$failed = 1;
			$errors["error"] = "Device type is missing";
		}
		if (!isset($data['user_id']) || $data['user_id'] == "") {
			$failed = 1;
			$errors["error"] = "User ID is missing";
		}
		
		if(!$failed) {
		$unique_id = $data['unique_id'];
		$notify_type = $data['notify_type'];
		$device_type = $data['device_type'];
		$user_id = $data['user_id'];
		if(!$unique_id){
			$unique_id = randomNumber(10);
			$sql = "insert into wp_mobile_notifications set notify_type = $notify_type,device_type = '$device_type',unique_id = $unique_id,user_id = $user_id";
			if($wpdb->query($sql)){
				$dta = array("user_id" => $user_id, "unique_id" => $unique_id);
			}else{
				$failed = 1;
				$errors["error"] = "Please try again";
			}
		}else{
			$sql = "update wp_mobile_notifications set notify_type = $notify_type where unique_id = $unique_id and user_id = $user_id and device_type = '$device_type'";
			$wpdb->query($sql);
			$dta = array("user_id" => $user_id, "unique_id" => $unique_id);
		}
		$getData['message'] = "Subscribed Successfully";
		$getData['data'] = $dta;
		$getData['status'] = true;
		echo json_encode($getData);
		}
}
else {
	$failed = 1;
	$errors['error'] = "Wrong Request";
}
if($failed) {
	$getData['message'] = $errors['error'];
	$getData['data'] = '';
	$getData['status'] = false;
	header("HTTP/1.0 405 Method Not Allowed");
	echo json_encode($getData);
}