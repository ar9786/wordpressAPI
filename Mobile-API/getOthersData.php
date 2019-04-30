<?php
include(dirname(__FILE__)."/../wp-load.php");
global $wpdb;
//error_reporting(-1);
//ini_set('display_errors', 'On');
$failed = 0;
$errors = array();
$data= array();

// Sart Book Mark Api
if($_SERVER['REQUEST_METHOD'] == GET && $_GET["request_for"] == 'bookMarkPost' && isset($_GET['post_id']) && isset($_GET['user_id'])){
	$post_id = $_GET["post_id"];
	$user_id = $_GET["user_id"];
	$isBookmark = $wpdb->get_results("select * from  wp_post_bookmark where user_id = '$user_id'");
	if($wpdb->num_rows == 1){
		$pst_data = unserialize($isBookmark[0]->post_id);
		if(array_key_exists($post_id, $pst_data)) {
			if($pst_data[$post_id] == 1){
				$pst_data[$post_id] = 0;
				$isBookmark = 0;
				$data['message'] = "Removed from Bookmarks";
				$post_data = serialize($pst_data);
				$wpdb->query("update wp_post_bookmark set  post_id = '$post_data' where user_id = '$user_id'");
			}else{
				$isBookmark = 1;
				$data['message'] = "Added to Bookmarks";
				$pst_data[$post_id] = 1;
				$post_data = serialize($pst_data);
				$wpdb->query("update wp_post_bookmark set  post_id = '$post_data' where user_id = '$user_id'");
			}
		}else{
			$isBookmark = 1;
			$data['message'] = "Added to Bookmarks";
			$pst_data[$post_id] = 1;
			$post_data = serialize($pst_data);
			$wpdb->query("update wp_post_bookmark set  post_id = '$post_data' where user_id = '$user_id'");
		}
	}else{
		$isBookmark = 1;
		$post_data = serialize(array($post_id=>1));
		$wpdb->query("insert into wp_post_bookmark set post_id = '$post_data',user_id = '$user_id'");
		$data['message'] = "Added to Bookmarks";
	}
	$data['data'] = array("post_id" => (string)$post_id,"user_id"=>(string)$user_id,"isBookmark"=>$isBookmark);
}

// End Book Mark Api


// Start fetch location
if ($_SERVER['REQUEST_METHOD'] == GET && $_GET["request_for"] == 'location' ){
	$location = array("Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo, Democratic Republic of the", "Congo, Republic of the", "Costa Rica", "Côte d'Ivoire", "Croatia", "Cuba", "Curaçao", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Faroe Islands", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Greenland", "Grenada", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "North Korea", "South Korea", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestine, State of", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Puerto Rico", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Sint Maarten", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "Spain", "Sri Lanka", "Sudan", "Sudan, South", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Virgin Islands, British", "Virgin Islands, U.S.", "Yemen", "Zambia", "Zimbabwe", "Other location");
	$data['data'] = $location;
	$data['status'] = true;
}
// End fetch location

// Start fetch industry
if ($_SERVER['REQUEST_METHOD'] == GET && $_GET["request_for"] == 'industry' ){
	$industry = array("Corporate","Fund Management","Banking","Other Profession");
	$data['data'] = $industry ;
	$data['status'] = true;
}
// End fetch industry
echo json_encode($data);