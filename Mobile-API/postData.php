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


if ($_SERVER['REQUEST_METHOD'] == GET  && isset($_GET["post_id"]) && isset($_GET['user_id']) ){

	$user_id = $_GET["user_id"];
	$post_id = $_GET["post_id"];
	$fetch_post = $wpdb->get_results("select wp.* from  wp_posts wp where wp.post_status = 'publish' and wp.post_type = 'post' and wp.ID = $post_id");
	$postdate = date("d M Y",strtotime($fetch_post[0]->post_date));
	$description = $fetch_post[0]->post_excerpt;
	$category_detail=get_the_category( $fetch_post[0]->ID );
	$category_id = $category_detail[0]->term_id;
	$cat_name = $category_detail[0]->cat_name;
	$image_post_url = "http://spicaworks.com.md-94.webhostbox.net/weekinchina/wp-content/uploads/2009/10/RTXJ82U.jpg";
	//$image_post_url = get_icon_image($top_post->ID,"post-large");
	$isBookmark = (int)bookmark($fetch_post[0]->ID,$user_id );
	// Corporate Starts
	$meta_values = get_post_meta($post_id, 'company', $single);
	// End Corporate Data
	//nl2br
	
	$content = str_replace("&lt;&lt;ad&gt;&gt;","",$fetch_post[0]->post_content);
	$postDetail["postDetail"] = array("description"=>$description,"heading"=>$fetch_post[0]->post_title,"imageUrl"=>$image_post_url,"content"=>nl2br($content),'id'=>(string)$fetch_post[0]->ID,'category'=>htmlspecialchars_decode($cat_name,ENT_QUOTES),'isBookmark'=>$isBookmark,'date'=>$postdate,"moreOn"=>$meta_values[0]);
	
	//strip_tags(htmlspecialchars_decode($fetch_post[0]->post_content))
	// Start More stories from this issues

	$moreStories = get_posts("numberposts=3&exclude=$post_id&category=$category_id&orderby=ASC");
	$more_stories_issue = array();
	foreach ($moreStories as $m) {
		$post_url = get_permalink($m->ID);
		$post_idd = (string)$m->ID;
		$post_title = trim($m->post_title); 
		$date =  date("d M Y",strtotime($m->post_date));
		$isBookmark = (int)bookmark($m->ID,$user_id );
		$post_excerpt = $m->post_excerpt; 
		//$post_image = get_icon_image($m->ID,"post-large");
		$post_image = "http://spicaworks.com.md-94.webhostbox.net/weekinchina/wp-content/uploads/2009/10/RTXJ82U.jpg";
		$more_stories_issue["more_stories_issue"][] = array("post_url"=>$post_url,'category'=>htmlspecialchars_decode($cat_name),'category_link'=>get_permalink($m->ID),'post_title'=>$post_title,'post_image'=>$post_image,'post_description'=>$post_excerpt,"isBookmark"=>$isBookmark,"date"=>$date,'post_id'=>$post_idd);
	}
	// End More stories from this issue
	
	// Start Corporate Post
	
	$top_posts = get_posts("meta_key=company&exclude=$post_id&meta_value=$meta_values[0]&orderby=ASC&numberposts=3");
	//$recent = get_posts("numberposts=3&exclude=".$post_id."&category=25&orderby=rand"); 
	if ($category->cat_name != 'Section') {
			foreach ($top_posts as $r) {
				$post_url = get_permalink( $r->ID );
				$category_detail=get_the_category( $r->ID );
				//$category_id = $category_detail[0]->term_id;
				$category_name = $category_detail[0]->cat_name;
				$post_excerpt = $r->post_excerpt;
				$post_id = (string)$r->ID;
				$permalink = get_permalink($r->ID);
				$recnt_image = "http://spicaworks.com.md-94.webhostbox.net/weekinchina/wp-content/uploads/2009/10/RTXJ82U.jpg";
				//$recnt_image = get_icon_image($r->ID,"post-large");
				$recnt_title = $r->post_title;
				$recnt_excerpt = $r->post_excerpt;
				$isBookmark = (int)bookmark($r->ID,$user_id );
				$date =  date("d M Y",strtotime($r->post_date));
			$more_stories_issue["recent_articles"][] = array("post_url"=>$post_url,'category'=>htmlspecialchars_decode($category_name),'post_id'=>$post_id,'permalink'=>$permalink,'recnt_title'=>$recnt_title,'recnt_image'=>$recnt_image,"isBookmark"=>$isBookmark,'post_description'=>$post_excerpt,"date"=>$date);
		}
	}
	
	// End Corporate articles 
	$data['data']['more_stories_issue'] = $more_stories_issue["more_stories_issue"];
	$data['data']['recent_articles'] = $more_stories_issue["recent_articles"];
	
	$data['message'] = "All data";
	$data['data']['postDetail'] = $postDetail["postDetail"];
	if($failed) {
		header("HTTP/1.0 405 Method Not Allowed");
		$data['message'] = $errors['error'];
		$data['data'] = "";
		$data['status'] = false;
	}
}

// End fetch post data
echo json_encode($data);