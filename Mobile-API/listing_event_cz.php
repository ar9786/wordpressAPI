<?php
include(dirname(__FILE__)."/../wp-load.php");
global $wpdb;
$data=[];
$select_column = array("id","Title","Content","Image","Date","Status");

$heder_add = $wpdb->get_results("select * from  wp_event_notifmcz where status = 1 ");
$totalData = $wpdb->num_rows;
$totalFilter = $totalData;
$img_url = get_template_directory_uri().'/Event-Noification/assets/images/';$img_url = get_template_directory_uri().'/Event-Noification/assets/images/';

foreach($heder_add as $heder_adds){
	$data[] = array($heder_adds->text,$img_url.$heder_adds->image,$heder_adds->content,$heder_adds->created_at,$heder_adds->status);
}
$json_data = array(
				"draw" => $_REQUEST['draw'],
				"recordsTotal"=>$totalData,
				"recordsFiltered"=>$totalFilter,
				"data" => $data
			);
echo json_encode($json_data);

