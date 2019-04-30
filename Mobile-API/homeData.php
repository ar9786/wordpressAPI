<?php
include(dirname(__FILE__)."/../wp-load.php");
global $wpdb;
$failed = 0;
$errors = array();
$data= array();
// Start fetch home data

function bookmark($post_id,$user_id){
	global $wpdb;
	$isBookmark = $wpdb->get_results("select * from  wp_post_bookmark where user_id = '$user_id'");
	if($isBookmark){
		$pst_data = unserialize($isBookmark[0]->post_id);
		if(array_key_exists($post_id, $pst_data)) {
			return $pst_data[$post_id];
		}else{
			return 0;
		}
	}else{
		return 0;	
	}
}

if ($_SERVER['REQUEST_METHOD'] == GET && isset($_GET['user_id'])){
	$user_id = $_GET["user_id"];
	$heder_add = $wpdb->get_results("select * from  wp_event_notifmcz where status =1 ");
	$img_url = get_template_directory_uri().'/Event-Notification/assets/images/';
	$event_type ='';
	foreach($heder_add as $heder_adds){
	if($heder_adds->event_type == 1) {  $event_type =  'Special edition'; }
	if($heder_adds->event_type == 2) {  $event_type =  'WiC news';}
	if($heder_adds->event_type == 3) {  $event_type =  'Editor\'s pick'; }
	$adds[] = array("id"=>$heder_adds->id,"title"=>$heder_adds->text, "image"=>$img_url.$heder_adds->image,"event_url"=>'',"content"=>$heder_adds->content,"event_type"=>$event_type);
	}
	$data['data']['imageDetail'] = $adds;
	
	
	$query = "SELECT `wpt`.`name`,`wpt`.`term_id`,`wptt`.`description` FROM $wpdb->term_taxonomy `wptt` INNER JOIN $wpdb->terms `wpt` ON `wptt`.`term_id` = `wpt`.`term_id` WHERE `wptt`.`taxonomy`='post_tag' AND `wptt`.`count` > 0 AND `wpt`.`name` REGEXP '[0-9]+' ORDER BY CAST(`wpt`.`name` AS UNSIGNED) DESC LIMIT 1";
	$latest_issue = $wpdb->get_row($query);
	$current_issue = $GLOBALS['current_issue'] = $latest_issue->name;
	$top_posts = get_posts("meta_key=frontpage&meta_value=yes&numberposts=20&tag=$current_issue");
	$i=0;
	foreach ($top_posts as $top_post) {
		$i++;
		$catl = get_the_category($top_post->ID);
		$catlll = array();
		foreach($catl as $catll) { 
		$catlll[] = $catll->cat_name;
		}
		$cat_name = implode(", ",$catlll);
		$post_url = get_permalink($top_post->ID);
		$image_post_url = get_icon_image($top_post->ID,"large");
		$isBookmark = (int)bookmark( $top_post->ID,$user_id );
		$postDetail["postDetail"][] = array("post_url"=>$post_url,"heading"=>$top_post->post_title,"imageUrl"=>$image_post_url,"description"=>$top_post->post_excerpt,'id'=>(string)$top_post->ID,'category'=>htmlspecialchars_decode ($cat_name),'isBookmark'=>$isBookmark,'date'=>date("d M Y",strtotime($top_post->post_date)));
	}
	
	$data['message'] = "All data";
	$data['data']['postDetail'] = $postDetail["postDetail"];
	if($failed) {
		header("HTTP/1.0 405 Method Not Allowed");
		$data['message'] = $errors['error'];
		$data['data'] = "";
		$data['status'] = false;
	}
}
// End fetch home data
echo json_encode($data);