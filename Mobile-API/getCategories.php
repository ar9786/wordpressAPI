<?php
include(dirname(__FILE__)."/../wp-load.php");
global $wpdb;
$failed = 0;
$errors = array();
$data= array();

if ($_SERVER['REQUEST_METHOD'] == GET ){
	
$categories = get_categories( array(
'orderby' => 'name',
'order'   => 'ASC'
) );
$jk=1;
foreach($categories as $category) {
	if($jk<10){
  $cat["cat_details"][] =  array("cat_id" => $category->cat_ID ,"cat_name" => htmlspecialchars_decode ($category->name),"cat_link" => get_category_link($category->term_id) );
	}$jk++;
}
$data['message'] = "All Categories";
$data['data']['cat_details'] = $cat["cat_details"];
if($failed) {
		header("HTTP/1.0 405 Method Not Allowed");
		$data['message'] = $errors['error'];
		$data['data'] = "";
		$data['status'] = false;
}
}
echo json_encode($data);