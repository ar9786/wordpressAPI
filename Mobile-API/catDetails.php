<?php
include(dirname(__FILE__)."/../wp-load.php");
global $wpdb;
$failed = 0;
$errors = array();
$data= array();
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
if ($_SERVER['REQUEST_METHOD'] == GET && $_GET['cat_id']){
		$img_url = get_template_directory_uri().'/Event-Notification/assets/images/';
		$catid = $_GET['cat_id'];
		$user_id = $_GET['user_id'];
		$category_query_args = array(
			'cat' => $catid,
			'post_type' => 'post',
			'post_status' => 'publish',
			'order'   => 'DESC',
			'posts_per_page' => 5
		);
		$arr_posts = new WP_Query( $category_query_args );
		if ( $arr_posts->have_posts() ) :
			while ( $arr_posts->have_posts() ) :
			$arr_posts->the_post();
			//$image_post_url = get_icon_image(get_the_ID(),"large");
			$image_post_url = "http://spicaworks.com.md-94.webhostbox.net/weekinchina/wp-content/uploads/2009/10/RTXJ82U.jpg";
			$isBookmark = (int)bookmark(get_the_ID(),$user_id );
			$cat_name = get_cat_name( $catid );
			$postDetail["postDetail"][] = array("post_url"=>get_the_permalink(),"heading"=>get_the_title(),"imageUrl"=>$image_post_url,"description"=>get_the_excerpt(),'id'=>(string)get_the_ID(),'category'=>htmlspecialchars_decode ($cat_name),'isBookmark'=>$isBookmark,'date'=>date("d M Y",strtotime(get_the_date())));
		endwhile;
		endif;
$data['message'] = "Categories all Post";
$data['data']['postDetail'] = $postDetail["postDetail"];
if($failed) {
		header("HTTP/1.0 405 Method Not Allowed");
		$data['message'] = $errors['error'];
		$data['data'] = "";
		$data['status'] = false;
}
}
echo json_encode($data);