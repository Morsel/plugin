<?php
$userid = $_REQUEST["userid"];
$key = $_REQUEST["auth_token"];

unset($_SESSION["morselSocialLoginFail"]);

if($userid != "" && $key != ""){
  $login_result = array("status"=>false,"msg"=>"");

  //get user of morsel web by userid and apikey
  $jsonurl = MORSEL_API_URL."users/me.json?api_key=".$userid.':'.$key;
  $result = get_json($jsonurl);
  //$result = json_decode(@wp_remote_retrieve_body(wp_remote_get($jsonurl)));

  //check if user exist on eatmorsel
  if(empty($result)) { //result not found by eatmorsel
    $login_result["msg"] = array("Sorry your account isn't found, please signup.");
  } else {
    //get user by email
    $userByEmail = get_user_by_email($result->data->email);
    if($userByEmail){ //if found
      $wpUser = $userByEmail->data;
      // login user
      if ( !is_wp_error($wpUser) ) {
        getUserLoggedIn($wpUser->ID);
        $login_result["msg"] = array("Congrats you are successfully logged in.");
        $login_result["status"] = true;
      } else {
        $login_result["msg"] = $wpUser->get_error_messages();
      }
    } else { //not found create user
      if(username_exists($result->data->username)){
        $newUserName = getUniqueUsername($result->data->username.'-'.$result->data->id);
      } else {
        $newUserName = getUniqueUsername($result->data->username);
      }
      $random_password = wp_generate_password(6,false);
      $newWpUserID = wp_create_user($newUserName,$random_password,$result->data->email);
      $newlyCreatedUser = new WP_User($newWpUserID);
      $newlyCreatedUser->set_role('subscriber');
      // $message = "Welcome ".$newUserName.",
      //               Your new account has been created successfully on ".get_site_url().".
      //                 your username is ".$newUserName." and password is ".$random_password."
      //                 Thank you.";

      $message = '<body><center><table width="600" background="#FFFFFF" style="text-align:left;" cellpadding="0" cellspacing="0"><tr><td colspan="3"><!--CONTENT STARTS HERE--><table cellpadding="0" cellspacing="0"><tr><td width="15"><div style="line-height: 0px; font-size: 1px; position: absolute;">&nbsp;</div></td><td width="325" style="padding-right:10px; font-family:Trebuchet MS, Verdana, Arial; font-size:12px;" valign="top"><span style="font-family:Trebuchet MS, Verdana, Arial; font-size:17px; font-weight:bold;"> Welcome '.$newUserName.'</span><br /><p>Your new account has been created successfully on '.get_site_url().'.</p><p>Your username is '.$newUserName.'. If you forget your password, you can retrieve it from the site.</p><br />   Best Regards,<br/>'.get_bloginfo( 'name').'</td></tr></table></td></tr></table><br /><table cellpadding="0" style="border-top:1px solid #e4e4e4; text-align:center; font-family:Trebuchet MS, Verdana, Arial; font-size:12px;" cellspacing="0" width="600"><tr><td style="font-family:Trebuchet MS, Verdana, Arial; font-size:12px;"><br />'.get_bloginfo( 'name').'<br /><!-- <a href="{!remove_web}">Unsubscribe </a> --></td></tr></table></center></body>';                
      //send email to new user
      //wp_mail($result->data->email,'New Registration',$message);
      add_filter( "wp_mail_content_type", "set_html_content_type" );
      wp_mail( $result->data->email, "New Registration", $message);

      function set_html_content_type() {
        return "text/html";
      }
      // getting user
      if ( !is_wp_error($newWpUserID) ) {
        //add first name and last name by eatmorsel info
        wp_update_user(array( 'ID' => $newWpUserID,
                            'first_name' => $result->data->first_name,
                            'last_name' => $result->data->last_name
                            ));

        $login_result["msg"] = array("You are successfully logged in.");
        $login_result["status"] = true;
        getUserLoggedIn($newWpUserID);
      } else { //if error
        $login_result["msg"] = $newWpUserID->get_error_messages();
      }
    }
    if($login_result["status"]){ //if no error set morsel session
      $_SESSION['morsel_login_userid'] = $result->data->id;
      $_SESSION['morsel_user_obj'] = $result->data;
    }
  }
  $_SESSION['morsel_social_login_result'] = $login_result;
  header("location:".$_SESSION['backToPage']);
  exit;
} else {
  $_SESSION["morselSocialLoginFail"] = $_REQUEST["status"];
  header("location:".$_SESSION['backToPage']);
  exit();
}

?>