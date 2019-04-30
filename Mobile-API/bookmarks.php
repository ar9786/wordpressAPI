<?php
include(dirname(__FILE__)."/../wp-load.php");
global $wpdb;
$failed = 0;
$errors = array();
$data= array();
// Start fetch post data

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


if ($_SERVER['REQUEST_METHOD'] == GET   && isset($_GET['user_id']) ){

	$user_id = $_GET["user_id"];
	$fetch_bookmark = $wpdb->get_results("select * from wp_post_bookmark where user_id=$user_id");
	if($wpdb->num_rows > 0){
	$post_ids = unserialize($fetch_bookmark[0]->post_id);
	foreach($post_ids as $post_key=>$kval){
		if($kval == 1){
		$fetch_post = $wpdb->get_results("select wp.* from  wp_posts wp where wp.post_status = 'publish' and wp.post_type = 'post' and wp.ID = $post_key");
			$post_url = get_permalink($fetch_post[0]->ID);
			$postdate = date("d M Y",strtotime($fetch_post[0]->post_date));
			$description = $fetch_post[0]->post_excerpt;
			$category_detail=get_the_category( $fetch_post[0]->ID );
			$category_id = $category_detail[0]->term_id;
			$cat_name = $category_detail[0]->cat_name;
			$image_post_url = "http://spicaworks.com.md-94.webhostbox.net/weekinchina/wp-content/uploads/2009/10/RTXJ82U.jpg";
			$image_post_url = get_icon_image($top_post->ID,"post-large");
			$isBookmark = (int)bookmark($fetch_post[0]->ID,$user_id );
			$postDetail["postDetail"][] = array("post_url"=>$post_url,"description"=>$description,"heading"=>$fetch_post[0]->post_title,"imageUrl"=>$image_post_url,"content"=>strip_tags(htmlspecialchars_decode($fetch_post[0]->post_content)),'id'=>(string)$fetch_post[0]->ID,'category'=>htmlspecialchars_decode($cat_name),'isBookmark'=>$isBookmark,'date'=>$postdate);
		}
	}
	//nl2br

	
	// End Corporate articles 
		$data['message'] = "All data";
		$data['data']['postDetail'] = $postDetail["postDetail"];
		$data['status'] = true;
	}else{
	
		header("HTTP/1.0 400 Method Not Allowed");
		$data['message'] = "No Data Found";
		$data['data']['postDetail'] = [];
		$data['status'] = false;
	}
	
	if($failed) {
		header("HTTP/1.0 405 Method Not Allowed");
		$data['message'] = $errors;
		$data['data'] = "";
		$data['status'] = false;
	}
}

// End fetch post data
echo json_encode($data);