<?php
include(dirname(__FILE__)."/../wp-load.php");
global $wpdb;
$failed = 0;
$errors = array();
$data= array();


if ($_SERVER['REQUEST_METHOD'] == GET && $_GET['event_id']){
	
	$event_id = $_GET['event_id'];
	$heder_adds = $wpdb->get_results("select * from wp_event_notifmcz where id = '$event_id' and status =1 ");
	if($wpdb->num_rows == 1){
		
	$img_url = get_template_directory_uri().'/Event-Notification/assets/images/';
	$event_type ='';
	if($heder_adds[0]->event_type == 1) {  $event_type =  'Special edition'; }
	if($heder_adds[0]->event_type == 2) {  $event_type =  'WiC news';}
	if($heder_adds[0]->event_type == 3) {  $event_type =  'Editor\'s pick'; }
	$adds = array("id"=>$heder_adds[0]->id,"title"=>$heder_adds[0]->text, "image"=>$img_url.$heder_adds[0]->image,"event_url"=>'',"content"=>$heder_adds[0]->content,"event_type"=>$event_type);
	$data['message'] = "All data";
	$data['data'] = $adds;
	}else{
		$errors['error']="No Data Found";
		$failed = 1;
	}
	if($failed) {
		header("HTTP/1.0 405 Method Not Allowed");
		$data['message'] = $errors['error'];
		$data['data'] = "";
		$data['status'] = false;
	}
}
echo json_encode($data);