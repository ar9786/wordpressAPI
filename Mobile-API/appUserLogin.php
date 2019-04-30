<?php
include(dirname(__FILE__)."/../wp-load.php");
global $wpdb;
$failed = 0;
$errors = array();
$getData = array();
if ($_SERVER['REQUEST_METHOD'] == POST){
	$data = json_decode(file_get_contents('php://input'),TRUE);
	if(!isset($data["user_name"]) || $data["user_name"] == ''){
		$failed = 1;
		$errors['error'] = "Username is Required.";
	}
	$user_details = $wpdb->get_results("select * from wp_users where user_login = '$data[user_name]'");
	if($wpdb->num_rows == 1){
		$getData['message'] = "Logged In Successfully";
		$getData['data'] = 	$user_details[0];
		$getData['status'] = true;
		echo json_encode($getData);
	}else{
		$failed = 1;
		$errors['error'] = "Username does not exist.";
	}
	if($failed) {
		$getData['message'] = $errors['error'];
		$getData['data'] = NULL;
		$getData['status'] = false;
		header("HTTP/1.0 405 Method Not Allowed");
		echo json_encode($getData);
	}
}