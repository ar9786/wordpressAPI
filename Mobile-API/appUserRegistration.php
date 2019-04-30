<?php
include(dirname(__FILE__)."/../wp-load.php");
global $wpdb;
$failed = 0;
$errors = array();
$getData = array();
if ($_SERVER['REQUEST_METHOD'] == POST){
	$data = json_decode(file_get_contents('php://input'),TRUE);

	$new_user = preg_replace('|[^a-z0-9 _.\-@]|i', '', $data['user_name']);
	
	if ($new_user != $data['user_name']) {
		$failed = 1;
		$errors["error"] = "Username contains illegal characters.";
	}
	else if (!isset($data['user_email']) || $data['user_email'] == "") {
		$failed = 1;
		$errors["error"] = "Please enter your email address.";
	}
	else if (email_exists($data['user_email'])) {
		$failed = 1;
		$errors["error"] = "User already exists with this email address";
	}
	else if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/",trim($data['user_email']))) {
		$failed = 1;
		$errors["error"] = "Enter valid email address";
	}
	
	if (!isset($data['user_name']) || $data['user_name'] == "") {
		$failed = 1;
		$errors["error create username"] = "Please create a username.";
	}
	else if (username_exists($data['user_name'])) {
		$failed = 1;
		$errors["error"] = "Username is already taken.";
	}

	if (!isset($data['first_name']) || $data['first_name'] == "") {
		$failed = 1;
		$errors["error"] = "Please enter your first name.";
	}
	if (!isset($data['last_name']) || $data['last_name'] == "") {
		$failed = 1;
		$errors["error"] = "Please enter your last name.";
	}
	if (!isset($data['industry']) || $data['industry'] == "") {
		$failed = 1;
		$errors["error"] = "Please enter your industry.";
	}
	if (!isset($data['location']) || $data['location'] == "") {
		$failed = 1;
		$errors["error"] = "Please enter your location.";
	}
	
	if(!$failed) {
		$random_password = wp_generate_password( 12, false );  
		$user_id = wp_create_user( $data['user_name'], $random_password, $data['user_email'] );
		update_usermeta($user_id, 'location', $data['location']);
		update_usermeta($user_id, 'company', $data['industry']);
		update_usermeta($user_id, 'first_name', $data['first_name']);
		update_usermeta($user_id, 'last_name', $data['last_name']);
		//$jsonwebtoken = jsonwebtoken($user_id);
		
		
		// Mail SMTP configuration
		$from1 = "signup@weekinchina.com";
		$from2 = "Week in China";
		$addAddress = $data['user_email'];
		$subject = "Week in China username";
		$content = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>WeekInChina</title>
</head>
<body>
	<table border="0" cellpadding="0" cellspacing="0" style="background-color: #0862a9; font: 14px arial,sans-serif; padding: 20px; color: #333333; border: 1px solid #DDDDDD; text-transform: none; text-indent: 0px; letter-spacing: normal; word-spacing: 0px; white-space: normal;font-size-adjust: none; font-stretch: normal;width:100%;max-width: 660px;">
	  <tbody>
	    <tr>
	    	<td>
	    		<table style="border-spacing: 0;"  width="100%">
	    			<tbody>
	    				<tr>
	    				  <td align="center" style="margin: 0px; font-family: arial,sans-serif;" valign="top">
	    				    <table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%;padding: 30px;background-color: #ffffff;">
	    				      <tbody>
	    				        <tr>
	    				          <td align="left" style="margin: 0px; padding: 5px 0px 10px; font-size: 14px" valign="top">
	    				            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	    				              <tbody>
	    				                <tr>
	    				                  <td align="left" colspan="12" style="text-align: center;" valign="top"><img alt="bidinline Logo" src="https://www.weekinchina.com/app/themes/wic/images/img_WIC-logo-2.png"></td>
	    				                </tr>
	    				              </tbody>
	    				            </table>
	    				          </td>
	    				        </tr>
	    				        <tr>
	    				          <td align="left" style="margin: 0px; font-size: 14px" valign="top" width="100%">
	    				            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	    				              <tbody>
	    				                <tr>
	    				                  <td align="left" colspan="12" valign="top" width="100%">
	    				                    <p style="margin: 0;margin-bottom: 20px;">Hi <span><strong> '.$data['user_name'].'</strong></span>,</p>
	    				                  </td>
	    				                </tr>
	    				                <tr>
	    				                  <td align="left" colspan="12" valign="top" width="100%">
	    				                    <p style="margin: 0;margin-bottom: 20px;">You have successfully registered for the Week in China website.</p>
											<p><span>Username:<span><strong> '.$data['user_name'].'</strong></span></p>
											<p><a href="https://www.weekinchina.com/login/">Click here to login now</a></p>
	    				                  </td>
	    				                </tr>
	    				              </tbody>
	    				            </table>
	    				          </td>
	    				        </tr>
	    				        <tr>
	    				          <td>
	    				            <table style="margin-top: 20px;">
	    				              <tbody>
	    				                <tr>
	    				                  <td>
	    				                    <p style="margin: 0;margin-bottom: 5px;font-size: 14px;">Thank You,</p>
	    				                  </td>
	    				                </tr>
	    				                <tr>
	    				                  <td>
	    				                    <p style="margin: 0;font-size: 14px;">Team WeekInChina</p>
											<p><strong>Exclusively sponsored by HSBC</p>
<p>The Week in China website and the weekly magazine publications are owned and maintained by ChinTell Limited, Hong Kong. Neither HSBC nor any member of the HSBC group of companies ("HSBC") endorses the contents and/or is involved in selecting, creating or editing the contents of the Week in China website or the Week in China magazine. The views expressed in these publications are solely the views of ChinTell Limited and do not necessarily reflect the views or investment ideas of HSBC. No responsibility will therefore be assumed by HSBC for the contents of these publications or for the errors or omissions therein.</strong></p>
	    				                  </td>
	    				                </tr>
	    				              </tbody>
	    				            </table>
	    				          </td>
	    				        </tr>
	    				      </tbody>
	    				    </table>
	    				  </td>
	    				</tr>
	    			</tbody>
	    		</table>
	    	</td>
	    </tr>
	    <tr>
	      <td>
	        <table style="margin-top: 20px;padding: 30px;width: 100%; background-color: #ffffff;">
	          <tbody>
	          	<tr>
	          		<td>
	          			<table style="width: 100%;text-align: center;">
	          				<tbody>
	          					<tr>
	          						<td style="color: #ffffff;">
	          							<ul style="text-align:center;list-style-type: none;padding-left: 0;margin-bottom: 0;">
	          								<li style="display: inline-block;margin-right: 15px;">
	          									<a href="https://itunes.apple.com/app/week-in-china/id441530574?mt=8"><img src="https://www.weekinchina.com/app/themes/wic/images/bu_app-store.png" alt="Facebook" style="border-radius: 8px;"></a>
	          								</li>
	          								
	          							</ul>
	          						</td>
	          					</tr>
	          					<!--<tr>
	          						<td>
	          							<ul style="text-align:center;list-style-type: none;padding-left: 0;margin-bottom: 0;">
	          								<li style="display: inline-block;margin-right: 15px;">
	          									<a href="javascript:void(0);"><img src="http://hireswiftdeveloper.com/alexamedia/wp-content/themes/alexa/images/mailer/google_play.png" alt=""></a>
	          								</li>
	          								<li style="display: inline-block;">
	          									<a href="javascript:void(0);"><img src="http://hireswiftdeveloper.com/alexamedia/wp-content/themes/alexa/images/mailer/app_store.png" alt=""></a>
	          								</li>
	          							</ul>
	          						</td>-->
	          					</tr>
	          				</tbody>
	          			</table>
	          		</td>
	          	</tr>
	          </tbody>
	        </table>
	      </td>
	    </tr>
	  </tbody>
	</table>
</body>
</html>';
		//include(dirname(__FILE__)."/PHPMailer/sendMail.php");
		//sendMail($mail,$from1,$from2,$addAddress,$subject,$content);
		
		$to = $data['user_email'];
		$from = "Week in China<signup@weekinchina.com>";
		$headers = 'From: '. $from . "\r\n" .
		'Reply-To: ' . $from . "\r\n";
		$headers .= "Content-type: text/html\r\n";
		$sent = wp_mail($to, $subject, $content, $headers);
		
		
		
		$getData['message'] = "Registered Successfully";
		$getData['data'] = array("ID"=>(string)$user_id,"user_name"=>$data['user_name'],"first_name"=>$data['first_name'],"last_name"=>$data['last_name'],"user_email"=>$data['user_email'],"location"=>$data['location']);
		$getData['status'] = true;
		echo json_encode($getData);
	}
}
else {
	$failed = 1;
}

if($failed) {
	$getData['message'] = $errors['error'];
	$getData['data'] = NULL;
	$getData['status'] = false;
	header("HTTP/1.0 405 Method Not Allowed");
	echo json_encode($getData);
}
/*
// json web token

function jsonwebtoken($user_id){
// Create token header as a JSON string
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

// Create token payload as a JSON string
$payload = json_encode(['user_id' => $user_id]);

// Encode Header to Base64Url String
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

// Encode Payload to Base64Url String
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

// Create Signature Hash
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);

// Encode Signature to Base64Url String
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

// Create JWT
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

return $jwt;
}
*/
?>