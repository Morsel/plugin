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
 * GitHub Plugin URI: neelesh_v
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define('MORSEL_PLUGIN_URL_PATH', plugin_dir_path( __FILE__ ) );
define('MORSEL_PLUGIN_IMG_PATH', plugin_dir_url( __FILE__ ).'img/' );
define('MORSEL_PLUGIN_PATH', plugin_dir_url( __FILE__ ));
define('MORSEL_PLUGIN_WIDGET_ASSEST', plugin_dir_url( __FILE__ ).'widget_assests/' );

//for switch to development env set this constant value "dev" 
//and for local env set this constant value "local"
define('MORSEL_PLUGIN_ENV','dev');

if(MORSEL_PLUGIN_ENV == 'prod'){
  define('MORSEL_API_URL', 'https://api.eatmorsel.com/');  
  define('MORSEL_EMBED_JS', 'https://rawgit.com/nishant-n/morsel/morsel-wp-plugin-production/embed.js');
  define('MORSEL_SITE', 'https://www.eatmorsel.com/');
} else if((MORSEL_PLUGIN_ENV == 'local') || (MORSEL_PLUGIN_ENV == 'dev')){
  if(MORSEL_PLUGIN_ENV == 'dev'){
    define('MORSEL_API_URL', 'https://api-staging.eatmorsel.com/');    
  } else {
    define('MORSEL_API_URL', 'http://localhost:3000/');
    //define('MORSEL_API_URL', 'http://301222d4.ngrok.com/');
  }    
  define('MORSEL_EMBED_JS', 'https://rawgit.com/nishant-n/morsel/morsel-wp-plugin-staging/embed.js');
  define('MORSEL_SITE', 'https://dev.eatmorsel.com/');
}

define('MORSEL_API_USER_URL', MORSEL_API_URL.'users/');
define('MORSEL_API_MORSELS_URL', MORSEL_API_URL.'morsels/');
define('MORSEL_API_ITEMS_URL', MORSEL_API_URL.'items/');
define('MORSEL_API_COUNT', 20 );
/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
//session start
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
  //require_once(MORSEL_PLUGIN_URL_PATH. 'widgets.php'); //for widgets
  require_once(MORSEL_PLUGIN_URL_PATH. 'shortcode.php'); //for shortcode
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
	//register_setting('morsel_settings', 'morsel_posts_data');	
} 

add_action( 'admin_init', 'register_my_setting' );

add_action('init', 'morsel_page_plugin_add'); //for add page

add_action( 'init', 'morsel_rewrites_init' );
function morsel_rewrites_init(){
    add_rewrite_rule(
        '/([0-9]+)/?$',
        'index.php?pagename=morsel_ajax',
        'top' );
}

add_filter( 'query_vars', 'morsel_query_vars' );
function morsel_query_vars( $query_vars ){

  if($_REQUEST['pagename']=='morsel_ajax')
    {

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
    //print_r($_POST);
    $postdata = http_build_query($_POST);
    
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );

    $context  = stream_context_create($opts);
    //echo MORSEL_API_URL.'users/sign_in.json';
    
    $result = @file_get_contents(MORSEL_API_URL.'users/sign_in.json', false, $context);
    
    $result = json_decode($result);
    
    
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

          $newUserName = getUniqueUsername($result->data->username.'-'.$result->data->id);

          //if(!username_exists($newUserName) ){ //check username is exist or not
          $random_password = wp_generate_password(6,false);
          $newWpUserID = wp_create_user($newUserName,$random_password,$result->data->email);
          //}

          $message = "Welcome ".$newUserName.",
                        Your new account has been created successfully on ".get_site_url().".
                          your username is ".$newUserName." and password is ".$random_password."
                          Thank you.";
                          
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
      }
      
      header('Location:'.$_SERVER['HTTP_REFERER']); 
      exit(0);
    }
   }

   //logout user 
   if($_REQUEST['pagename']=='morsel_logout') {
      wp_logout();
      unset($_SESSION['morsel_login_userid']);
      header('Location:'.$_SERVER['HTTP_REFERER']); 
      exit(0);
   }

   if($_REQUEST['pagename']=='morsel_ajax_admin')
    {

      $options = get_option( 'morsel_settings');
      $api_key = $options['userid'] . ':' .$options['key'];
      $morsel_post_settings = get_option( 'morsel_post_settings');//gettind excluding id

      if(array_key_exists('posts_id', $morsel_post_settings))
        $post_selected = $morsel_post_settings['posts_id'];
      else
        $post_selected = array();

      $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&page=".$_REQUEST['page_id']."&count=".MORSEL_API_COUNT;
      
      $json = get_json($jsonurl); //getting whole data

      foreach ($json->data as $row) {?>
    

      <tr id="morsel_post-<?php echo $row->id;?>" class="post-<?php echo $k;?> type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">
        <th scope="row" class="check-column">
        <input id="cb-select-"<?php echo $k;?> type="checkbox" name="morsel_post_settings[posts_id][]" value="<?php echo $row->id?>"  
        <?php echo (in_array($row->id, $post_selected))?"checked":""?> />
        <!-- <input <?php echo (in_array($row->id, $post_selected))?"disabled":""?> type="hidden" name="morsel_post_settings[data][]" value='<?php echo json_encode($tmpData);?>'> -->        
      </th>
      <td class="post-title page-title column-title">
          <strong><a href="<?php echo $row->url?>" target="_blank"><?php echo $row->title?></strong>
      </td>
            <td class="author column-author">
              <?php if($row->photos->_800x600 != ''){?>
                <img src="<?php echo $row->photos->_800x600;?>" height="100" width="100">
              <?php } else { 
                 echo "No Image";
              } ?>
            </td>
      <td class="categories column-categories">
        <?php echo substr($row->summary,0,150); echo (strlen($row->summary) > 150 ? "..." :"");?>  
      </td>
      <td class="date column-date">
          <abbr title="<?php echo date("d/m/Y", strtotime($row->created_at));?>"><?php echo date("d/m/Y", strtotime($row->created_at));?></abbr><br />Published
      </td>
    </tr>
    <?php }       
    exit(0);
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
