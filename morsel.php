<?php
/**
 * Morsel
 *
 * Share eatmorsel's content
 *
 * @package   Morsel
 * @author    Nishant <nishant.n@cisinlabs.com>
 * @license   GPL-2.0+
 * @link      eatmorsel.com
 * @copyright 2014 Nishant
 *
 * @wordpress-plugin
 * Plugin Name:       Morsel
 * Plugin URI:        eatmorsel.com
 * Description:       Share eatmorsel's content
 * Version:           2.1
 * Author:            Nishant
 * Author URI:
 * Text Domain:       morsel
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: nishant_n
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define('MORSEL_PLUGIN_URL_PATH', plugin_dir_path( __FILE__ ) );
define('MORSEL_PLUGIN_IMG_PATH', plugin_dir_url( __FILE__ ).'img/' );
define('MORSEL_PLUGIN_PATH', plugin_dir_url( __FILE__ ));
define('MORSEL_PLUGIN_WIDGET_ASSEST', plugin_dir_url( __FILE__ ).'widget_assests/' );
define('MORSEL_PLUGIN_ADMIN_ASSEST', plugin_dir_url( __FILE__ ).'admin/assets/' );

@ini_set('display_errors', 0);

//for switch to development env set this constant value "dev"
//and for local env set this constant value "local"

define('MORSEL_PLUGIN_ENV','prod');

if(MORSEL_PLUGIN_ENV == 'prod'){
  define('MORSEL_API_URL', 'https://api.eatmorsel.com/');
  define('MORSEL_EMBED_JS', 'https://rawgit.com/nishant-n/morsel/morsel-wp-plugin-production/embed.js');
  define('MORSEL_SITE', 'https://www.eatmorsel.com/');
  define('MORSEL_PLUGIN_IFRAME_PATH','https://www.eatmorsel.com/addnewmorsel');
  define('MORSEL_AMAZON_IMAGE_URL','https://morsel.s3.amazonaws.com/');
} else if((MORSEL_PLUGIN_ENV == 'local') || (MORSEL_PLUGIN_ENV == 'dev')){
  if(MORSEL_PLUGIN_ENV == 'dev'){
    define('MORSEL_API_URL', 'https://api-staging.eatmorsel.com/');
    define('MORSEL_PLUGIN_IFRAME_PATH','http://dev.eatmorsel.com/addnewmorsel');
    define('MORSEL_SITE', 'http://dev.eatmorsel.com/');
  } else {
    define('MORSEL_API_URL', 'http://localhost:3000/');
    //define('MORSEL_API_URL', 'https://a4b175f4.ngrok.io/');
    define('MORSEL_PLUGIN_IFRAME_PATH','http://localhost:5000/addnewmorsel');
    define('MORSEL_SITE', 'http://localhost:5000/');
  }
  define('MORSEL_AMAZON_IMAGE_URL','https://morsel-staging.s3.amazonaws.com/');
  define('MORSEL_EMBED_JS', 'https://rawgit.com/nishant-n/morsel/morsel-wp-plugin-staging/embed.js');

}

define('MORSEL_API_USER_URL', MORSEL_API_URL.'users/');
define('MORSEL_API_MORSELS_URL', MORSEL_API_URL.'morsels/');
define('MORSEL_API_ITEMS_URL', MORSEL_API_URL.'items/');
define('MORSEL_API_KEYWORDS_URL', MORSEL_API_URL.'keywords/');
define('MORSEL_API_COUNT', 20 );
/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

session_start();

// clear session
function clear_morsel_session() {
  if(isset($_SESSION['morsel_login_userid'])){
    unset($_SESSION['morsel_login_userid']);
  }
  if(isset($_SESSION['morsel_user_obj'])){
    unset($_SESSION['morsel_user_obj']);
  }
}

add_action('wp_logout', 'clear_morsel_session');

require_once(MORSEL_PLUGIN_URL_PATH. 'public/class-morsel.php' );
require_once(MORSEL_PLUGIN_URL_PATH. 'public/morsel-utility-functions.php' );
//require_once(MORSEL_PLUGIN_URL_PATH. 'widgets.php'); //for widgets
require_once(MORSEL_PLUGIN_URL_PATH. 'shortcode.php'); //for shortcode
require_once(MORSEL_PLUGIN_URL_PATH. 'slider_shortcode.php'); //for Slider Shortcode
require_once(MORSEL_PLUGIN_URL_PATH. 'includes/create_morsel_shortcode.php');//Create Morsel
require_once(MORSEL_PLUGIN_URL_PATH. 'includes/user-preference-shortcode.php');//For showing user preference of morsel
require_once(MORSEL_PLUGIN_URL_PATH. 'page/page_create.php'); //for page

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Morsel', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Morsel', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Morsel', 'get_instance' ) );

function register_my_setting() {
	register_setting('morsel_settings', 'morsel_settings');
	register_setting('morsel_post_settings', 'morsel_post_settings');
  register_setting('morsel_host_details', 'morsel_host_details');
  register_setting('morsel_keywords', 'morsel_keywords');
  register_setting('morsel_associated_user', 'morsel_associated_user');
	register_setting('morsel_advanced_tab', 'morsel_advanced_tab');
  register_setting('morsel_settings_Preview', 'morsel_settings_Preview');
  register_setting('morsel_other_settings', 'morsel_other_settings');
}

add_action( 'admin_init', 'register_my_setting' );

add_action('init', 'morsel_page_plugin_add'); //for add page morsel description
add_action('init', 'morsel_page_add_user_preference'); //for add page morsel user preference

function morsel_rewrites_init(){
    add_rewrite_rule(
        '/([0-9]+)/?$',
        'index.php?pagename=morsel_ajax',
        'top' );
}

add_filter( 'query_vars', 'morsel_query_vars' );
function morsel_query_vars( $query_vars ){

  if($_REQUEST['pagename']=='morsel_ajax'){
      $options = get_option( 'morsel_settings');
      $api_key = $options['userid'] . ':' .$options['key'];
      $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&page=".$_REQUEST['page_id']."&count=".MORSEL_API_COUNT;
      $json = get_json($jsonurl); //getting whole data

      //gettind excluding id
      if(get_option( 'morsel_post_settings')) {
        $morsel_post_settings = get_option( 'morsel_post_settings');
      } else {
        $morsel_post_settings = array();
      }
      $morsel_page_id = get_option( 'morsel_plugin_page_id'); //gettting discription page id
      if(array_key_exists('posts_id', $morsel_post_settings))
       $post_selected = $morsel_post_settings['posts_id'];
      else
        $post_selected = array();
        foreach ($json->data as $row_sht) {
          if(in_array($row_sht->id, $post_selected))continue;
          echo grid($row_sht,$morsel_page_id);
        }
    exit(0);
  }
  if($_REQUEST['pagename']=='morsel_user_login'){
    unset($_POST['pagename']);
    $postdata = http_build_query($_POST);
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context  = stream_context_create($opts);
    $result = @file_get_contents(MORSEL_API_URL.'users/sign_in.json', false, $context);
    //$result = json_decode($result);
    if(empty($result)) { //result not found by eatmorsel
      $_SESSION['morsel_error'] = true;
      header('Location:'.$_SERVER['HTTP_REFERER']);
      exit(0);
    }else{
      //get user by email
      $userByEmail = get_user_by_email($result->data->email);
      if($userByEmail){ //if found
        $wpUser =  $userByEmail->data;
        if(is_user_logged_in()) { //if anyother user logged in logg them off
          $currentUserId = get_current_user_id( );
          if($currentUserId != $wpUser->ID){ //current user and login user not matched
            wp_logout();
            // login user
            if ( !is_wp_error($wpUser) ) {
              getUserLoggedIn($wpUser->ID);
            } else {
              $_SESSION['host_morsel_errors'] = $wpUser->get_error_messages();
            }
          }
        } else { // if no one is logged in
            // login user
            if ( !is_wp_error($wpUser) ) {
              getUserLoggedIn($wpUser->ID);
            } else {
              $_SESSION['host_morsel_errors'] = $wpUser->get_error_messages();
            }
        }
      } else { //not found create user
          $newUserName = getUniqueUsername($result->data->username);
          $random_password = wp_generate_password(6,false);
          $newWpUserID = wp_create_user($newUserName,$random_password,$result->data->email);
          $newlyCreatedUser = new WP_User($newWpUserID);
          $newlyCreatedUser->set_role('subscriber');

            $message = '<body><center><table width="600" background="#FFFFFF" style="text-align:left;" cellpadding="0" cellspacing="0"><tr><td colspan="3"><!--CONTENT STARTS HERE--><table cellpadding="0" cellspacing="0"><tr><td width="15"><div style="line-height: 0px; font-size: 1px; position: absolute;">&nbsp;</div></td><td width="325" style="padding-right:10px; font-family:Trebuchet MS, Verdana, Arial; font-size:12px;" valign="top"><span style="font-family:Trebuchet MS, Verdana, Arial; font-size:17px; font-weight:bold;"> Welcome '.$newUserName.'</span><br /><p>Your new account has been created successfully on '.get_site_url().'.</p><p>Your username is '.$newUserName.'. If you forget your password, you can retrieve it from the site.</p><br />   Best Regards,<br/>'.get_bloginfo( 'name').'</td></tr></table></td></tr></table><br /><table cellpadding="0" style="border-top:1px solid #e4e4e4; text-align:center; font-family:Trebuchet MS, Verdana, Arial; font-size:12px;" cellspacing="0" width="600"><tr><td style="font-family:Trebuchet MS, Verdana, Arial; font-size:12px;"><br />'.get_bloginfo( 'name').'<br /><!-- <a href="{!remove_web}">Unsubscribe </a> --></td></tr></table></center></body>';


          //send email to new user
            wp_mail($result->data->email,'New Registration',$message);
          // login user
          if ( !is_wp_error($newWpUserID) ) {
            if(is_user_logged_in()) { //if anyother user logged in logg them off
              wp_logout();
            }
            getUserLoggedIn($newWpUserID);
          } else { //if error
            $_SESSION['host_morsel_errors'] = $newWpUserID->get_error_messages();
          }
      }
      if(!isset($_SESSION['host_morsel_errors'])){ //if no error set morsel session
        $_SESSION['morsel_login_userid'] = $result->data->id;
        $_SESSION['morsel_user_obj'] = $result->data;
        //$_SESSION['current_morsel_user_psd'] = base64_encode($_POST['user']['password']);
      }
      header('Location:'.$_SERVER['HTTP_REFERER']);
      exit(0);
    }
   }


   //logout user
   if($_REQUEST['pagename']=='morsel_logout') {
      wp_logout();
      unset($_SESSION['morsel_login_userid']);
      unset($_SESSION['current_morsel_user_psd']);
      header('Location:'.$_SERVER['HTTP_REFERER']);
      // header('Location:'.$_SERVER['HTTP_REFERER'].'?morselid='.$_REQUEST["morselid"]);
      exit(0);
   }

   //make ajax morsel login functionality
   if($_REQUEST['pagename']=='morsel_ajax_user_login'){
    $login_result = array("status"=>false,"msg"=>"");
    unset($_POST['pagename']);
    $users = $_REQUEST["user"];
    $postdata = http_build_query($_POST);
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context  = stream_context_create($opts);

    $result = @file_get_contents(MORSEL_API_URL.'users/sign_in.json', false, $context);
    //$result = @wp_remote_retrieve_body(wp_remote_get(MORSEL_API_URL.'users/sign_in.json', false, $context));

    $result = json_decode($result);
    //$login_result["api_result"] = $result;
    if(empty($result)) { //result not found by eatmorsel
      $login_result["msg"] = array("Sorry your userid/email or password not matched, please try again.");
    } else {
      //get user by email
      $userByEmail = get_user_by_email($result->data->email);

      if($userByEmail){ //if found
        $wpUser =  $userByEmail->data;
        if(is_user_logged_in()) { //if anyother user logged in logg them off
          $currentUserId = get_current_user_id( );

          if($currentUserId != $wpUser->ID){ //current user and login user not matched
            wp_logout();

            // login user
            if ( !is_wp_error($wpUser) ) {
              getUserLoggedIn($wpUser->ID);
              $login_result["msg"] = array("You are successfully logged in.");
              $login_result["status"] = true;
            } else {
              $login_result["msg"] = $wpUser->get_error_messages();
            }
          }
        } else { // if no one is logged in

            // login user
            if ( !is_wp_error($wpUser) ) {
              getUserLoggedIn($wpUser->ID);
              $login_result["msg"] = array("You are successfully logged in.");
              $login_result["status"] = true;
            } else {
              $login_result["msg"] = $wpUser->get_error_messages();
            }
        }

      } else { //not found create user

          $newUserName = getUniqueUsername($result->data->username);

          $random_password = wp_generate_password(6,false);
          $newWpUserID = wp_create_user($newUserName,$random_password,$result->data->email);
          $newlyCreatedUser = new WP_User($newWpUserID);
          $newlyCreatedUser->set_role('subscriber');

          $message = '<body><center><table width="600" background="#FFFFFF" style="text-align:left;" cellpadding="0" cellspacing="0"><tr><td colspan="3"><!--CONTENT STARTS HERE--><table cellpadding="0" cellspacing="0"><tr><td width="15"><div style="line-height: 0px; font-size: 1px; position: absolute;">&nbsp;</div></td><td width="325" style="padding-right:10px; font-family:Trebuchet MS, Verdana, Arial; font-size:12px;" valign="top"><span style="font-family:Trebuchet MS, Verdana, Arial; font-size:17px; font-weight:bold;"> Welcome '.$newUserName.'</span><br /><p>Your new account has been created successfully on '.get_site_url().'.</p><p>Your username is '.$newUserName.'. If you forget your password, you can retrieve it from the site.</p><br />   Best Regards,<br/>'.get_bloginfo( 'name').'</td></tr></table></td></tr></table><br /><table cellpadding="0" style="border-top:1px solid #e4e4e4; text-align:center; font-family:Trebuchet MS, Verdana, Arial; font-size:12px;" cellspacing="0" width="600"><tr><td style="font-family:Trebuchet MS, Verdana, Arial; font-size:12px;"><br />'.get_bloginfo( 'name').'<br /><!-- <a href="{!remove_web}">Unsubscribe </a> --></td></tr></table></center></body>';

           add_filter( "wp_mail_content_type", "set_html_content_type" );
          //send email to new user
            wp_mail($result->data->email,'New Registration',$message);

          // login user
          if ( !is_wp_error($newWpUserID) ) {
            if(is_user_logged_in()) { //if anyother user logged in logg them off
              wp_logout();
            }
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
    echo json_encode($login_result);
    exit(0);
   }
   //End ajax morsel login functionality

   // get all users whome are associate with host admin
   if($_REQUEST['pagename']=='morsel_get_associated_user'){
     $options = get_option( 'morsel_settings');
     $jsonurl = MORSEL_API_USER_URL.$options['userid']."/association_requests.json?api_key=".$options['userid'].':'.$options['key'];
     $result = @file_get_contents($jsonurl);
     echo $result;
     exit(0);
   }





   if($_REQUEST['pagename']=='morsel_ajax_admin_slider')
    {
      $sliderId = mysql_escape_string($_REQUEST["sliderId"]);
      $page_id = mysql_escape_string($_REQUEST["page_id"]);
      $morsel_page_id = get_option( 'morsel_plugin_page_id');
      $options = get_option( 'morsel_settings');
      $api_key = $options['userid'] . ':' .$options['key'];
      $morsel_post_settings = get_option('morsel_post_settings');//getting excluding id
      $contents = get_option('shs_slider_contents');
      $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&page=".$page_id;
      unset($sliderContent);
      if($sliderId != undefined && $sliderId != ""){
         $sliderContent = $contents[$sliderId-1];
      }
      //$json = get_json($jsonurl); //getting whole data
      $json = json_decode(file_get_contents($jsonurl)); //json_decode(wp_remote_retrieve_body(wp_remote_get($jsonurl)));

       if(!$page_id){
        echo "Slider Name: &nbsp;&nbsp;&nbsp;<input type='text' name='sliderName' id='sliderNameSlider' value='".$sliderContent['name']."' placeholder='Slider Name'>";
        echo ' <div style="margin-left: 18%; padding-bottom: 5px;"><input type="button" name="joptsv" class="button-primary saveSlides" value="SAVE SLIDES" onclick="saveMorselSlider()" /></div>';
      ?>


      <table id="sliding-table" class="widefat posts">
              <thead>
                <tr>
                  <th scope='col' class='manage-column column-title sortable desc' style="padding-left: 10px;">Morsel Title</th>
                  <th scope='col' class='manage-column column-author'>Image</th>
                  <th scope='col' class='manage-column column-categories'><input type="checkbox" name="main" class="checkAllCheckbox" value="main" style="margin-right: 13.6px;"></th>
                </tr>
              </thead>
              <!-- <tfoot>
                <tr>
                  <th scope='col' class='manage-column column-title sortable desc' style="padding-left: 10px;">Morsel Title</th>
                  <th scope='col' class='manage-column column-author'>Image</th>
                  <th scope='col' class='manage-column column-categories'><input type="checkbox" name="main" class="checkAllCheckbox" value="main"  style="margin-left:4px"></th>
                </tr>
              </tfoot> -->
              <tbody id = "sliding_body">

      <?php }
      foreach ($json->data as $row) {?>
              <tr>
                 <td><a href="<?php echo $morsel_url?>" target="_blank"><?php echo $row->title?></a></td>
                 <?php $imageUrl = "";
                  if($row->primary_item_photos->_992x992 != ''){
                        $imageUrl = str_replace("_992x992_", "", $row->primary_item_photos->_992x992);
                        $imageUrlAdmin = $row->primary_item_photos->_100x100;
                        $mainValue = str_replace(MORSEL_AMAZON_IMAGE_URL,"",$imageUrl.'@@$@@'.$row->title.'@@$@@'.$row->creator->photos->_72x72);
                  ?>
                  <td>
                    <a href="<?php echo $imageUrl;?>" target="_blank" ><img src="<?php echo $imageUrlAdmin;?>" height="100" width="100"><a>
                  </td>
                  <td>
                    <input type="checkbox" class="sliderCheckbox" <? if(array_key_exists($row->id, $sliderContent['slider'])){?>checked<? } ?> name="cnt[<?=$row->id;?>]" value="<?=$mainValue?>">
                  </td>
                  <?php } else if($row->photos->_800x600 != '')
                  { ?>
                   <td>
                    <a href="<?php echo $row->photos->_800x600;?>" target="_blank" >
                      <img src="<?php echo $row->photos->_800x600;?>" height="100" width="100">
                    <a>
                  </td>
                  <td>
                    <input type="checkbox" class="sliderCheckbox" <? if(array_key_exists($row->id, $sliderContent['slider'])){?>checked<? } ?> name="cnt[<?=$row->id;?>]" value="<?=$mainValue?>">
                  </td>
                 <?php }
                  else  {
                    echo '<td colspan="2">No Image</td>';
                    }
                  ?>
                  </tr>
          <? }
          if(!$page_id)
          {
          ?>
            </tbody>
          </table>
      <? }
   exit;
  }

  if($_REQUEST['pagename'] == "getSliderListing"){
  $contents = get_option('shs_slider_contents');
    if(count($contents)>0){
      for($s=0;$s<count($contents);$s++){?>
    <tr>
      <td>Slider <?=$contents[$s]["name"];?></td>
      <td>[morseldisplayslider slider="<?=$contents[$s]["name"];?>"]</td>
      <td><a href="javascript:void(0);" onclick="editSliderById(<?=$s+1?>);">Edit</a> || <a href="javascript:void(0);" onclick="deleteSliderById(<?=$s+1?>);">Delete</a>&nbsp;<img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" class="loaderImageSlider" id="loaderImageSlider<?=$s+1?>" style="display:none;"/></td>
    </tr>
    <?}
    } else {
      echo "<tr><td colspan='3'><b>No Slider</b></td></tr>";
    }
    exit;
  }
  if($_REQUEST['pagename'] == "sliderSave"){
      $sliderID = $_REQUEST["sliderID"];
      $contents = get_option('shs_slider_contents');
      $checkboxValue = $_REQUEST['cnt'];
      if($sliderID){
        $contents[$sliderID-1]['slider'] = $checkboxValue;
        $contents[$sliderID-1]['name'] = $_REQUEST['sliderName'];
      } else {
        if(count($contents) > 0 ) { $count = count($contents);} else {$count = 0;}
          $contents[$count]['slider'] = $checkboxValue;
          $contents[$count]['name'] = $_REQUEST['sliderName'];
          echo $count+1;
      }
      update_option('shs_slider_contents',$contents);
      exit;
   }
  if($_REQUEST['pagename'] == "sliderDelete"){
      $sliderID = $_REQUEST["sliderID"];
      $contents = get_option('shs_slider_contents');
      $new = [];
      for($s=0; $s<count($contents);$s++){
          if ($s+1 != $sliderID) {
             $new[] = $contents[$s];
          }
      }
      $contents = $new;
      update_option('shs_slider_contents',$contents);
      exit;
   }
  if($_REQUEST['pagename']=='morsel_ajax_admin')
    {
      $morsel_page_id = get_option( 'morsel_plugin_page_id');
      $options = get_option( 'morsel_settings');
      $api_key = $options['userid'] . ':' .$options['key'];
      $morsel_post_settings = get_option('morsel_post_settings');//getting excluding id
      $contents = get_option('shs_slider_contents');
      $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&page=".$_REQUEST['page_id']."&count=".MORSEL_API_COUNT."&submit=true";

      $json = get_json($jsonurl); //getting whole data
      //$json = json_decode(wp_remote_retrieve_body(wp_remote_get($jsonurl)));

      {
      foreach ($json->data as $row) {
        $morsel_url = add_query_arg( array('morselid' => $row->id), get_permalink($morsel_page_id));?>
        ?>
                <tr id="morsel_post-<?php echo $row->id;?>" class="post-<?php echo $k;?> type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">
          <td>
            <strong>
              <? $morsel_url = add_query_arg( array('morselid' => $row->id), get_permalink($morsel_page_id));?>
              <a href="<?php echo $morsel_url?>" target="_blank">
                  <?php if($row->is_submit) { ?>
                      <font class="unPublished">
                         <?php echo $row->title;?>
                         <span>&nbsp;(UNPUBLISHED)</span>
                      </font>
                  <? } else { echo $row->title; } ?>
              </a>
            </strong>
          </td>
          <td>
                <?php if($row->primary_item_photos->_320x320 != ''){?>
                  <a href="<?php echo $row->primary_item_photos->_320x320;?>" target="_blank" >
                    <img src="<?php echo $row->primary_item_photos->_320x320;?>" height="100" width="100">
                  </a>
                <?php } else if($row->photos->_800x600 != '') { ?>
                  <a href="<?php echo $row->photos->_800x600;?>" target="_blank" >
                    <img src="<?php echo $row->photos->_800x600;?>" height="100" width="100">
                  </a>
                <?php } else { echo "No Image Found";} ?>
              </td>
        <td>
          <?php echo substr($row->summary,0,150); echo (strlen($row->summary) > 150 ? "..." :"");?>
        </td>
        <td>
            <?php if(!$row->is_submit) { ?>
              <abbr title="<?php echo date("m/d/Y", strtotime($row->published_at));?>"><?php echo date("m/d/Y", strtotime($row->published_at));?></abbr>
            <br />PUBLISHED
            <?php } else if($row->local_schedual_date){
                echo "Schedualed at <span class='schedualDate'>".date('d-m-Y H:i', strtotime($row->local_schedual_date))."<span><br>";?>
                        <a morsel-id="<?php echo $row->id ?>" morsel-schedualdate="<?php echo $row->schedual_date;?>" class="all_unpublish_morsel_scheduled button">Reschedule</a>
            <? } else { echo "-";} ?>
        </td>
        <td>
           <?php foreach ($row->morsel_keywords as $tag_keyword){?>
            <code>
               <?php echo $tag_keyword->name;?>
               <a href="javascript:void(0);" id="keyword-<?php echo $tag_keyword->id ?><?php echo $row->id ?>" class="dashicons dashicons-no mrsl-remove-keyword" onclick="removeKeyword(<?php echo $row->id ?>,<?php echo $tag_keyword->id ?>); return false;"></a>
            </code>
          <?php } ?>
        </td>
        <td>
           <?php foreach ($row->morsel_topics as $tag_topic){?>
            <code>
               <?php echo $tag_topic->name;?>
               <a href="javascript:void(0);" id="topic-<?php echo $tag_topic->id.$row->id;?>" class="dashicons dashicons-no mrsl-remove-keyword" onclick="removeKeyword(<?php echo $row->id ?>,<?php echo $tag_topic->id ?>); return false;"></a>
            </code>
          <?php } ?>
        </td>
        <td>
            <img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoader<?php echo $row->id;?>" class="displayNone editAboveImage"/>
            <a href="javascript:void(0);" onclick="editMorsel(<?php echo $row->id;?>)" class="button actionButtonMorsel">Edit</a>
              <?php add_thickbox(); ?>
            <a morsel-id = "<?php echo $row->id ?>" class="all_morsel_keyowrd_id button actionButtonMorsel" id="keywordButton<?php echo $row->id ?>"><?=(count($row->morsel_keywords)>0)?"Update":"Pick";?> Keyword</a>
          <?php if($row->is_submit || count($row->morsel_topics) == 0) { ?>
              <?php add_thickbox(); ?>
            <a morsel-id = "<?php echo $row->id ?>" class="all_morsel_TopicId button actionButtonMorsel">Pick Topics</a>
          <?php } else { ?>
            <?php add_thickbox(); ?>
            <a morsel-id = "<?php echo $row->id ?>" class="all_morsel_TopicId button actionButtonMorsel">Update Topics</a>
          <?php } ?>
          <?php if($row->is_submit) { ?>
            <a morsel-id = "<?php echo $row->id ?>" class="all_unpublish_morsel_id button actionButtonMorsel">Publish Morsel</a>
            <? if($row->schedual_date == ""){?>
              <a morsel-id = "<?php echo $row->id ?>" morsel-schedualdate="<?php echo $row->schedual_date;?>" class="all_unpublish_morsel_scheduled button actionButtonMorsel">Scheduled</a>
              <? }
          } ?>
        </td>
      </tr>
    <?php }
    }
    exit(0);
  }
  if($_REQUEST['pagename'] == 'morselSocialLogin'){
    $_SESSION["backToPage"] = $_REQUEST["url"];
    if($_REQUEST['provider'] == "facebook"){
      header("location:".MORSEL_SITE."auth/joinsocial?provider=facebook&pagename=morselReturnSocial&callbackurl=".site_url());
    } else if($_REQUEST['provider'] == "twitter"){
      header("location:".MORSEL_SITE."auth/joinsocial?provider=twitter&pagename=morselReturnSocial&callbackurl=".site_url());
    }
    exit;
  }
  if($_REQUEST['pagename'] == "morselReturnSocial"){
    include_once("socialLoginHandler.php");
    $current_url = $_SERVER["REQUEST_URI"];
    exit;
  }
  //check for morsel like at time of login
  if($_REQUEST['pagename'] == 'saveSessionforLike'){
     unset($_SESSION["likeMorsel"]);
          $_SESSION["likeMorsel"]['morselid'] = $_REQUEST["morselId"];
          $_SESSION["likeMorsel"]['reqType'] = $_REQUEST["reqType"];
          $_SESSION["likeMorsel"]['activity'] = $_REQUEST["activity"];
     exit(0);
  }
  //check for morsel comment at time of login
  if($_REQUEST['pagename'] == 'saveSessionforComment'){
     unset($_SESSION["commentMorsel"]);
          $_SESSION["commentMorsel"]['morselid'] = $_REQUEST["morselId"];
          $_SESSION["commentMorsel"]['itemId'] = $_REQUEST["itemId"];
          $_SESSION["commentMorsel"]['comment'] = $_REQUEST["comment"];
     exit(0);
  }

  //saveSessionforComment End

  //morselMetaPreview
  if($_REQUEST['pagename'] == 'morselMetaPreview'){
    $url = $_REQUEST["url"];
    $tags = get_meta_tags($url);
    $res = file_get_contents($url);
    preg_match("~(.*?)~", $res, $match);//fetching title
    $title = $match[1];
    $description = $tags['description'];
    $image = $tags['image'];
    // print_r($tags);

    if($title == ""){ //for title
      $title = ($tags['twitter:title'])?$tags['twitter:title']:$tags['og:title'];
    }
    if($description == ""){ //for description
      $description = ($tags['twitter:description'])?$tags['twitter:description']:$tags['og:description'];
    }
    if($image == ""){ //for image src
      $image = ($tags['twitter:image:src'])?$tags['twitter:image:src']:$tags['og:image'];
    }
    $meta = [];
    $meta["title"] = $title;
    $meta["description"] = $description;
    $meta["image"] = $image;
    // $imagedata = @wp_remote_retrieve_body(wp_remote_get($meta["image"]));
    // // alternatively specify an URL, if PHP settings allow
    // echo $base64 = base64_encode($imagedata);
    echo json_encode($meta);
    exit();
  }
  return $query_vars;
}

function get_json($url){
  return json_decode(@file_get_contents($url));
}

// logged in user by id
function getUserLoggedIn($userId){
  wp_clear_auth_cookie();
  wp_set_current_user($userId);
  wp_set_auth_cookie($userId);
}

//check and provide unique username of host site
function getUniqueUsername($userName) {
  if(!username_exists($userName)) { // our base case
    return $userName;
  } else {
    $arr = explode($userName,'-');
    if(count($arr) == 2){
      $userName .= '-1';
    } else {
      $arr[count($arr)-1] = $arr[count($arr)-1]+1;
      $userName .= implode('-',$arr);
    }
    return getUniqueUsername($userName); // <--calling itself.
  }
}

// This will enqueue the Media Uploader script
function wp_morsel_manager_admin_scripts () {
  wp_enqueue_script('jquery');
  wp_enqueue_media();
}

add_action('admin_print_styles', 'wp_morsel_manager_admin_scripts');


/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-morsel-admin.php' );
	add_action( 'plugins_loaded', array( 'Morsel_Admin', 'get_instance' ) );
}
function my_custom_submenu_page_callback() {

  echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
    echo '<h2>My Custom Submenu Page</h2>';
  echo '</div>';

}

/**
* Disable admin bar on the frontend of your website
* for subscribers.
*/
function themeblvd_disable_admin_bar() {
  if ( ! current_user_can('edit_posts') ) {
    add_filter('show_admin_bar', '__return_false');
  }
}
add_action( 'after_setup_theme', 'themeblvd_disable_admin_bar' );
/**
* Redirect back to homepage and not allow access to
* WP admin for Subscribers.
*/
function themeblvd_redirect_admin(){
  if ( ! defined('DOING_AJAX') && ! current_user_can('edit_posts') ) {
    wp_redirect( site_url() );
    exit;
  }
}

add_action( 'admin_init', 'themeblvd_redirect_admin' );

// add login signup for whole site
add_action('wp_footer', 'add_morsel_signup_login');
function add_morsel_signup_login() {
  echo "<div class='morsel-iso bootstrap-iso'>";
  require_once(MORSEL_PLUGIN_URL_PATH.'includes/authentication.php');
  echo "</div>";
}

function set_html_content_type() {
  return "text/html";
}
