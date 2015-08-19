<?php 
function grid($row_sht,$morsel_page_id) {
  $morsel_url = add_query_arg( array('morselid' => $row_sht->id), get_permalink($morsel_page_id));
  ?>
    <div class="col-sm-4 col-md-4 ">
                                      
      <div  class="morsel-block morsel-bg" morsel-url="<?php echo $morsel_url; ?>" >
          <div class="morsel-info">
              <h1 class="h2 morsel-block-title">
                <a class="white-link" href="<?php echo $morsel_url; ?>"><?php echo $row_sht->title;?></a>
              </h1>              
              <div class="morsel-info-bottom">
                  <h3  class="h6 morsel-block-place ">
                      <!-- <a class="white-link overflow-ellipsis" href="<?php echo $morsel_url; ?>"><?php echo $row_sht->title;?></a> -->
                      <a class="white-link overflow-ellipsis" href="<?php echo $morsel_url; ?>"><?php echo $row_sht->creator->first_name.' '.$row_sht->creator->last_name;?></a>
                  </h3>
              </div>
          </div>
          <?php if($row_sht->photos->_800x600)
                  $img_url = $row_sht->photos->_800x600;
                else 
                   $img_url = MORSEL_PLUGIN_IMG_PATH.'no_image.png';
          ?>
          <a class="morsel-img " href="#" style="background-image: url('<?php echo $img_url;?>');"></a>

          <img class="spacer loader " src="<?php echo MORSEL_PLUGIN_IMG_PATH.'spacer.png'?>">
          <!-- end ngIf: spacer -->
      </div>
  </div>
  <?php } 

// WP Shortcode to display Morsel Post list on any page or post.
 function morsel_post_display($atts){  

  $atts = shortcode_atts(
    array(
      'count' => 0,
      'gap_in_morsel' => NULL,
      'center_block' => 0
    ), $atts, 'morsel_post_display' );

  $morsel_page_id = get_option( 'morsel_plugin_page_id');
  $options = get_option( 'morsel_settings');
  $api_key = $options['userid'] . ':' .$options['key'];

  if($atts['count'] > 0){
    $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&count=".$atts['count'];  
  } else {
    $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&count=".MORSEL_API_COUNT;  
  }
  
  $json = get_json($jsonurl); 

  $morsel_post_sht =  $json->data;

  $count_morsel = count($morsel_post_sht);

  if(get_option( 'morsel_post_settings')) {
    $morsel_post_settings = get_option( 'morsel_post_settings');  
  } else {
    $morsel_post_settings = array();
  }  

  if(array_key_exists('posts_id', $morsel_post_settings))
   $post_selected = $morsel_post_settings['posts_id'];
  else
    $post_selected = array();

?> 
     <?php if($count_morsel>0){?>
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css"> -->
        <link rel="stylesheet" href="<?php echo MORSEL_PLUGIN_WIDGET_ASSEST.'css/bootstrap.min.css';?>">
        <link rel="stylesheet" type="text/css" href=<?php echo MORSEL_PLUGIN_WIDGET_ASSEST.'css/morsel_list.css'?>>
        <style type="text/css">
          <?php if($atts['center_block'] == 1){ ?>
                 #morsel-posts-row {
                    font-size: 0;
                    text-align: center;
                  }
                  #morsel-posts-row .col-sm-4.col-md-4 {
                    display: inline-block;
                    float: none;                    
                  }
                  @media screen and (max-width: 767px) {
                      #morsel-posts-row .col-sm-4.col-md-4 {
	                    display: block;	                    
	                  }
                  }
          <?php } ?>
          <?php if(isset($atts['gap_in_morsel'])){ ?>
                  #morsel-posts-row .col-sm-4.col-md-4 {                    
                    padding: 0 <?php echo $atts['gap_in_morsel'];?>!important;
                  }
          <?php } ?>

        </style>
           <div class="page-wrapper" > 
                  <div class="site">
                      <div class="tab-content">

                          <div class="tab-pane  active">

                              <div class="row no-gutter" id="morsel-posts-row">
                                 
                              <?php foreach ($morsel_post_sht as $row_sht) {
                                 
                                  if(in_array($row_sht->id, $post_selected))
                                    continue;
                                  echo grid($row_sht,$morsel_page_id);

                               } ?>
                              </div>
                          </div>
                      </div>
               </div>
               <div class="col-sm-12 col-md-12 load-more-wrap" >
               <!-- previous code
                  <button class="btn  btn-lg btn-block btn-info" type="button" id="load-morsel">Load more!</button>  -->
                <?php if($atts['count'] == 0) { ?>  
                 <button class="btn btn-primary morselbtn" type="button" id="load-morsel" morsel-count="<?php echo $atts['count'];?>" >View more morsels</button>
                <?php } ?> 
               </div>     
          </div>
    <?php
      } else { //end if
         echo "You have no morsel!";
      } ?> 

  <?php  }
    add_shortcode('morsel_post_display', 'morsel_post_display');

//shortcode for description
function morsel_post_des(){

  global $morsel_detail;
  global $morsel_user_detail;
  global $morsel_likers;

  //var_dump($_SESSION['morsel_user_obj']);

  $shtnUrl = getShortenUrl();
  
  $twitterShareTitle = '"'.$morsel_detail->title.'" from ';
    
  if(isset($morsel_user_detail->twitter_username)){
    $twitterShareTitle .=  '@'.$morsel_user_detail->twitter_username; 
  } else {
    $twitterShareTitle .=  $morsel_user_detail->first_name." ".$morsel_user_detail->last_name;
  }
  $twitterShareTitle .= " via @eatmorsel";

  $pintrestShareSummry = '"'.$morsel_detail->title.'" from '.$morsel_user_detail->first_name." ".$morsel_user_detail->last_name." on Morsel";  
  ?>
  <link rel="stylesheet" href="<?php echo MORSEL_PLUGIN_WIDGET_ASSEST.'css/bootstrap.min.css';?>">
  <link rel="stylesheet" type="text/css" href=<?php echo MORSEL_PLUGIN_WIDGET_ASSEST.'css/morsel_list.css'?>>


<div class="page-wrapper page-wrapper-details center-block">
      
      <div>
        <div class="modal-morsel-full-slide " >
            <div class="morsel-full">            
              <?php if(isset($_SESSION['morsel_error'])) { unset($_SESSION['morsel_error']);?> 
                <div class="alert alert-danger text-center" role="alert">Sorry your userid/email or password not matched, please try again.</div>
              <?php }?>
              <?php if(isset($_SESSION['host_morsel_errors'])) { 
                      $errors = $_SESSION['host_morsel_errors'];
                      unset($_SESSION['host_morsel_errors']);
                      foreach($errors as $error) { ?> 
                        <div class="alert alert-danger text-center" role="alert"><?php echo $error;?></div>
                <?php }
                    }?>    
              <?php if(!isset($morsel_detail)) { ?> 
                <!-- morsel exist -->
                  <div class="morsel-mobile-info alert-danger">                                    
                    <div class="alert" role="alert">
                      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                      <span class="sr-only">Error:</span>
                      Sorry no morsel id found.
                    </div>                
                  </div>
              <?php } else { ?>    
                <!-- morsel not exist -->
                <div class="morsel-mobile-info">                  
                  <?php if($morsel_detail->creator->photos->_40x40)
                        $creat_img = $morsel_detail->creator->photos->_40x40;
                      else 
                        $creat_img = MORSEL_PLUGIN_IMG_PATH.'no_image.png';                      
                  ?>
                  <h2 bo-text="morsel.title" class="morsel-title"><?php echo $morsel_detail->title;?>  
                    <span>
                    <?php if(!empty($_SESSION['morsel_login_userid'])){?>                  
                      <a href="<?php echo site_url()?>/index.php?pagename=morsel_logout" class="btn btn-danger btn-xs">Logout</a>
                    <?php } else {?>
                      <a data-toggle="modal" data-target="#morselLoginModal" id="open-morsel-login1" class="btn btn-danger btn-xs">SignUp/Login</a>
                    <?php } ?>
                    </span>
                  </h2>                  
                  <div class="user ">
                        <span class="profile-pic-link profile-pic-xs">
                            <img class="img-circle"  src="<?php echo $creat_img;?>" width="40">
                        </span>
                        <?php echo $creator = $morsel_detail->creator->first_name." ".$morsel_detail->creator->last_name; ?>
                  </div>
                </div>
        <?php if($morsel_detail->primary_item_photos->_992x992)
                $img_url = $morsel_detail->primary_item_photos->_992x992;
              else 
                $img_url = MORSEL_PLUGIN_IMG_PATH.'no_image.png';
        ?>
  <!--  <div class="slide-item morsel-item">
          <hr>
          <div class="item-img-wrap">
            <div  class="item-img image-loaded">
              <div>
                <img src="<?php echo $img_url;?>">
              </div>
            </div>
          </div>
          <div class="clear"></div>
          <div class="item-info">
            <div class="item-description">
              <p bo-html="formatDescription(item.description)"><?php echo $morsel_detail->summary;?></p>
            </div>
          </div>
        </div> -->
     
        <?php $items = $morsel_detail->items; ?>
        <!-- Item start -->
        <?php foreach ($items as $row_item) {?>
        
        <?php if($row_item->photos->_992x992)
                $items_url = $row_item->photos->_992x992;
              else 
                $items_url = MORSEL_PLUGIN_IMG_PATH.'no_image.png';
        ?>
        <div class="slide-item morsel-item " >
          <hr>
          <div class="item-img-wrap">
            <div class="item-img image-loaded">
              <div>
                <img  src="<?php echo $items_url;?>">
              </div>
            </div>
          </div>
          <div class="clear"></div>
          <div class="item-info">
            <div class="item-description">
              <p><?php echo $row_item->description;?></p>
            </div>
            <!-- comments area -->
            <div class="item-comments">              
              <a class="dark-link comment-popup-link" item-id="<?php echo $row_item->id;?>" comment-count="<?php echo ($row_item->comment_count) ? $row_item->comment_count: 0; ?>">
                <!-- <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> -->
              <?php if($row_item->comment_count) { ?>
                      <i class="common-comment-filled"></i>
                      <span id="comment-count-<?php echo $row_item->id;?>"><?php echo $row_item->comment_count;?><?php echo ($row_item->comment_count > 1)?' comments':' comment';?></span>
              <?php } else { ?>
                      <i class="common-comment-empty"></i>
                      <span id="comment-count-<?php echo $row_item->id;?>">Add comment</span>
              <?php } ?>                
              </a>
            </div>          
          </div>
        </div>
        <!-- Item End-->
        <?php }//end foreach ITEM ?>
        <div class="slide-item share-item" id="share-morsel">
          <hr>
          <div class="item-info">
            <h5 class="h2" style="margin:5px;">Share this morsel:</h5>
            <div class="social-sharing ">
                <span class='st_facebook_large' displayText='Facebook' st_title="<?php echo htmlspecialchars($morsel_detail->title);?>" st_summary="<?php echo htmlspecialchars($morsel_detail->items['0']->description);?>" st_image="<?php echo $img_url?>"></span>
                
                <span class='st_twitter_large' displayText='Tweet' st_title='<?php echo htmlspecialchars($twitterShareTitle);?>' st_image="<?php echo $img_url?>" st_via='' st_summary="<?php echo htmlspecialchars($morsel_detail->items['0']->description);?>"></span>
                
                <span class='st_linkedin_large' displayText='LinkedIn' st_url="<?php echo $shtnUrl;?>" st_title="<?php echo htmlspecialchars($morsel_detail->title.' - '.$morsel_user_detail->first_name.' '.$morsel_user_detail->last_name.' | Morsel');?>" st_image="<?php echo $img_url?>" st_summary="<?php echo htmlspecialchars($morsel_detail->items['0']->description);?>"></span>
                
                <!-- <span class='st_googleplus_large' displayText='Google +' st_title="<?php echo $morsel_detail->title.' - '.$morsel_user_detail->first_name.' '.$morsel_user_detail->last_name.' | Morsel';?>" st_image="<?php echo $img_url?>" st_summary="<?php echo $morsel_detail->items['0']->description;?>"></span> -->
                
                <span class='st_pinterest_large' displayText='Pinterest' st_url="<?php echo $shtnUrl;?>" st_title="<?php echo htmlspecialchars($morsel_detail->items['0']->description);?>" st_summary="<?php echo htmlspecialchars($pintrestShareSummry);?>" st_image="<?php echo $img_url?>"></span>
                
                <span id="embed-code-link" data-target="#morsel-embed-modal" data-toggle="modal">
                  <span class="embed-code stButton"><span class="embed-code stLarge" ></span></span>
                </span>                
            </div>
            <!-- <a  href="<?php echo $morsel_detail->url; ?>">View this and follow <?php echo $creator;?> on Morsel</a> -->
          </div>
        </div>
        <!-- Like & share Part -->
        <div class="morsel-actions-wrap fixed">
          <div class="morsel-actions">
            <div class="row">
              <div class="col-xs-6 col-sm-12" data-original-title="" data-toggle="" data-placement="top">
                <button class="btn btn-xs btn-link" id="like-btn-link" type="button" title="<?php echo userIsLike($morsel_likers) ? 'You have already liked this morsel' : 'Like Morsel';?>">
                  <i class="<?php echo userIsLike($morsel_likers) ? "common-like-filled":"common-like-empty";?>" ></i>
                </button>
                <button class="btn btn-link btn-xs morsel-like-count" type="button" id="like-count">
                  <?php if($morsel_detail->like_count > 0) {
                          if($morsel_detail->like_count == 1){
                            echo $morsel_detail->like_count. '<span> like</span>';  
                          } else {
                            echo $morsel_detail->like_count. '<span> likes</span>';  
                          }                        
                        } ?>
                </button>
              </div>
              <div class="col-xs-6 col-sm-12">
                <a class="btn btn-xs btn-link" title="Share morsel" id="share-morsel-focus" href="#"><i class="common-share"></i></a>
              </div>
          </div>
        </div>
      </div>
      <!-- End Like & share Part -->
        <?php } ?>   <!-- End else part -->
      </div><!-- end ngIf: morsel && showMorsel -->
    </div><!-- end ngIf: type === 'morsel' -->
 
  <!-- Share script -->
  <script type="text/javascript">var switchTo5x=true;</script>
  <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
  <script type="text/javascript">stLight.options({publisher: "5f0a497f-b77e-4c5a-a4d1-8f543aa2e9fb", doNotHash: false, doNotCopy: false, hashAddressBar: false,shorten: true});    
  </script>
  <!-- Share script -->

  <!-- Login Modal 
  <div id="morsel-login-content" title="Morsel Login" style="display:none;"> -->
  
  <div class="modal fade" id="morselLoginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         <button id="show-mrsl-login-btn" type="button" class="btn btn-danger btn-xs pull-right">Login</button>
         <h4 class="modal-title" id="myModalLabel"></h4>          
      </div> 
      <div class="modal-body">        
        <!-- <div class="login">
          <div class="login-header">
            <h1 class="text-hide"><a href="/" target="_self" class="morsel-text">Morsel</a></h1>
          </div>
        </div> -->     
        <!-- Sign Up div-->   
        <div class="main-view" >
            <div id="mrsl-signup-section">
              <div class="container-fluid join-page">
                <div class="row">
                  <div class="col-md-12">
                    <h1 class="text-center">Please Sign Up</h1>
                    <div id="mrsl-signup-error-box" class="alert alert-danger" style="display:none"></div>
                    <div class="join-landing" ui-view="landing"></div>
                    <div ui-view="basicInfo" class="">
                      <form novalidate="" class="padded-form" method="post" name="basicInfoForm" id="mrsl-signup-form" enctype="multipart/form-data">
                        <div class="row">
                          <!-- <div class="col-md-10 col-md-offset-1">
                            <div class="alert alert-danger"></div>
                          </div> -->
                        </div>
                        <div class="row">
                          <div class="col-sm-5 col-md-4 col-md-offset-1">
                            <div class="form-group">
                              <div class="avatar-add image-add">
                                <div data-original-title="Click to select or drag and drop a photo from your computer" data-toggle="tooltip" data-placement="bottom" class="img-circle">
                                  <div class="drop-box"></div>
                                  <span class="h1 plus-sign">+</span>
                                  <input type="file" name="user[photo]" id="mrsl_user_photo">
                                  <div class="image-preview" style="display:none"></div>
                                </div>
                              </div>
                              <label for="photo" class="control-label center-block text-center">Profile Photo</label>
                            </div>
                        </div>
                        <div class="col-sm-7 col-md-6">
                        <div class="form-group">
                          <label for="user[first_name]" class="control-label required">First Name</label>                
                          <input type="text" name="user[first_name]" id="mrsl_user_first_name" class="form-control" placeholder="John" required="required">
                          <p class="help-block"></p>                          
                        </div>
                        <div class="form-group">
                          <label for="user[last_name]" class="control-label required">Last Name</label>
                          <input type="text" name="user[last_name]" id="mrsl_user_last_name" class="form-control" placeholder="Smith" required="required">
                          <p class="help-block"></p>                          
                        </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                      <label for="user[username]" class="control-label required">Username</label>
                      <input type="text" name="user[username]" id="mrsl_user_username" class="form-control" placeholder="johnnyboy" required="required">
                      <p class="help-block">Must be alphanumeric and not include spaces</p>
                      <span class="help-block" id="mrsl_username_error"></span>
                    </div>

                    <div class="form-group">
                      <label for="user[email]" class="control-label required">Email</label>
                      <input type="email" name="user[email]" id="mrsl_user_email" class="form-control" placeholder="johnsmith@example.com" required="required">
                      <p class="help-block ng-binding"></p>                      
                    </div>
                    <div class="form-group">
                      <label for="user[password]" class="control-label required">Password</label>
                      <input type="password" name="user[password]" id="mrsl_user_password" class="form-control" placeholder="" required="required">
                      <p class="help-block"></p>                      
                    </div>
                    <div class="form-group">
                      <label for="verification" class="control-label required">Confirm Password</label>
                      <input type="password" name="verification" id="verification" class="form-control" placeholder="" required="required">
                      <p class="help-block"></p>
                    </div>
                    <div class="form-group">
                      <div class="checkbox">
                        <label for="user[professional]" class="control-label">
                          <input type="checkbox" value="true" name="user[professional]" id="mrsl_user_professional" class="">I am a professional chef, sommelier, mixologist, etc.</label>
                      </div>
                      <p class="help-block"></p>
                    </div>
                  <div id="morsel-progress" class="progress" style="display:none;">
                    <div style="width:100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100%" role="progressbar" class="progress-bar progress-bar-striped active">Your request is processing, please wait.</div>
                  </div>
                    <div class="form-group clearfix">
                      <span id="mrsl-signup-submit-btn-span" data-original-title="Please complete all required fields" data-toggle="tooltip" data-placement="top" class="btn-submit-wrap btn-submit-block disabled">
                        <button id="mrsl-signup-submit-btn" class="btn btn-primary btn-lg" type="submit">Sign Up</button>
                      </span>
                    </div>                    
                    <div>By continuing you indicate that you have read and agree to our <a target="_blank" href="http://dev.eatmorsel.com/terms">Terms of Service</a></div>
                  </div>
                </div>                
              </form>
                    </div>
                    <div ui-view="additionalInfo" class=""></div>
                  </div>
                </div>
               <!--  <a class="join-bg" target="_blank" href="https://www.eatmorsel.com/rypfeiffer54/1215-butternut-squash"><i>Butternut Squash</i> by Ryan Pfeiffer</a> -->
              </div>
            </div> <!-- #mrsl-signup-section -->
            <!-- login div-->
            <div id="mrsl-login-section" style="display:none">
              <div class="container-fluid login-page">
                  <h1 class="text-center">Log In to Morsel</h1>
                  <form action="<?php echo site_url()?>/index.php" class="padded-form" method="post" name="loginForm" id="morsel-front-login-form">                      
                      <div class="row">
                        <div class="col-md-12 center-block">
                            <div class="form-group">
                              <label for="login" class="control-label required">Email or Username</label>
                              <input type="text" name="user[login]" id="mrsl-login" class="form-control " placeholder="johnsmith@example.com or johnnyboy" >
                              <p class="help-block"></p>                               
                            </div>
                            <div class="form-group">
                              <label class="control-label required">Password</label>
                              <input type="password" name="user[password]" id="mrsl-password" class="form-control" placeholder="" >
                              <p class="help-block"></p>                              
                          </div>
                          <div class="form-group clearfix" >
                            <span id="mrsl-submit-btn-span" class="btn-submit-wrap btn-submit-block disabled" title="Please complete all required fields" data-toggle="tooltip" data-placement="top">
                              <button id="mrsl-submit-btn" class="btn btn-primary btn-lg" type="submit" >Login</button>
                            </span>
                          </div>
                          <div class="text-center"><a class="open-site-link" data-toggle="modal" data-src="https://www.eatmorsel.com/auth/password-reset" data-height=500 data-width=100% data-target="#forgetPasswordModal" >Forgot your password?</a></div>
                          <div class="have-an-account text-center">Don't have an account? <a target="_blank" href="#">Sign up here.</a></div>
                        </div>                    
                      </div> <!-- End row class -->                      
                      <input type="hidden" name="pagename" value="morsel_user_login">
                    </form>
                </div>
            </div> <!-- #mrsl-login-section -->
        </div>
        <div class="powered-by-morsel">
          <a target="_blank" href="http://www.eatmorsel.com/">Powered by Morsel</a>
        </div>
      </div>
      <!-- <div class="modal-footer">
        <footer class="login-footer">
          <div class="container">
            <div class="footer-inner">&copy; Morsel Labs, Inc. 2014</div>
          </div>
        </footer>        
      </div> -->
    </div>
  </div>
</div>
  <!-- </div>  End # morsel-login-content -->
  <!-- forget password  -->
  <div class="modal fade" id="forgetPasswordModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">            
            <div class="modal-body">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <iframe frameborder="0"></iframe>
            </div>         
        </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- end forget password  -->  
  <!-- End Login Modal -->
  <!-- Embed Code Modal-->
  <div class="modal fade" id="morsel-embed-modal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">        
         <div class="modal-content">            
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>               
              <h4 class="modal-title">Embed Code</h4>
            </div> 
            <div class="modal-body">
              <textarea rows="9" cols="66" name="clipboard-text" id="clipboard-text">
                <?php 
                  $milliseconds = round(microtime(true) * 1000);
                  $phpCode = '<div id="'.$milliseconds.'"><a id="morsel-embed" href="'. get_permalink().'?'.$_SERVER['QUERY_STRING'].'">Morsel</a>
                 </div><script type="text/javascript">(function(d, id, src) {var s = d.getElementById(id);if (!s) {s = d.createElement("script");s.id = id;s.src = src;d.head.appendChild(s);}})(document, "morsel-embed-js", "'.MORSEL_EMBED_JS.'");window.addEventListener("load", function(){loadMorsel('.$milliseconds.',"'.get_permalink().'?'.$_SERVER['QUERY_STRING'].'");}, false);</script>';
                  echo htmlspecialchars($phpCode);
               ?>
              </textarea>               
            </div>  
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>        
        </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- End Embed Code Modal  -->  
  <!-- Comment Modal  -->
  <div class="modal fade" id="morsel-comment-modal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">        
         <div class="modal-content">            
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>               
              <h4 class="modal-title">Comments</h4>
            </div> 
            <div class="modal-body user-list">
              <button class="lato btn btn-link center-block" id="view-more-comments" type="button" page-no="1" style="display:none">View previous comments</button>
              <div class="morsel-loader" style="display:none"></div>
              <ul class="" id="comment-list"></ul>            
              <div class="add-comment">
                <form novalidate="" name="addCommentForm" role="form" class="">
                  <div class="form-group">
                    <textarea required="Please add some comment." placeholder="Write your comment" rows="3" class="form-control" name="comment-text" id="comment-text"></textarea>
                    <input name="form-item-id" id="form-item-id" type="hidden" value=""/>
                  </div>                  
                  <button class="lato btn btn-primary pull-right" id="add-comment-btn" type="submit" disabled="disabled">Add Comment</button>                  
                </form>
              </div>               
            </div>                    
        </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- End Comment Modal  -->

  <!-- After like modal -->
  <div class="modal fade" id="morsel-like-others-modal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">        
         <div class="modal-content">            
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>               
              <!-- <h4 class="modal-title">Comments</h4> -->
            </div> 
            <div class="modal-body">
                <p>Hungry for more? We can let you know when more morsels like this are posted.</p>
                <form novalidate="" name="addCommentForm" role="form" class="">
                  <div class="checkbox">
                    <label><input type="checkbox" checked="checked" value="1">Yes, let me know when morsels like this are posted.</label>
                  </div>                  
                </form>              
            </div>          
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button id="morsel-subscribe" type="button" class="btn btn-primary" data-dismiss="modal">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- End After like modal  -->
  
  <script type="text/javascript">  
  jQuery(function ($) {
    /*add subscription for other morsel like this*/
    $("#morsel-subscribe").click(function(event){
      event.preventDefault();
      var sessionUserId =  "<?php echo $_SESSION['morsel_user_obj']->id;?>"
      if(sessionUserId == ''){
        jQuery("#open-morsel-login1").trigger('click');
        return;
      }
      
      var subscribeUrl = "<?php echo MORSEL_API_USER_URL.'morsel_subscribe'; ?>";
      
      var activity = 'morsel-subscribe';
      var key = "<?php echo $_SESSION['morsel_user_obj']->id.':'.$_SESSION['morsel_user_obj']->auth_token;?>";

      var morselId = "<?php echo $_REQUEST['morselid'];?>";
      var post_data = {
                        user:{subscriptions_attributes : [{morsel_id:morselId}] },
                        api_key:key
                      };
      
      console.log("post_data : ",post_data);

      jQuery.ajax({
          url: subscribeUrl,                     
          type: 'POST',           
          data: post_data,
          complete: function(){
            //alert("Action Complete");
            waitingDialog.hide();
          },
          beforeSend: function(xhr) {
            xhr.setRequestHeader('share-by',"morsel-plugin")
            xhr.setRequestHeader('activity',"Morsel Subscribe");            
            xhr.setRequestHeader('activity-id',"<?php echo $_REQUEST['morselid'];?>");
            xhr.setRequestHeader('activity-type',"Morsel");
            xhr.setRequestHeader('user-id',"<?php echo $_SESSION['morsel_user_obj']->id;?>");
            waitingDialog.show('Loading...');   
          },
          success: function(response, status){
            console.log("response :: ",response);  
            console.log("status :: ",status);
            
            if(status == 'success'){
              alert("Done");               
            } else {                  
              alert("Opps Something wrong happend!"); 
            }
          },
          error:function(response, status, xhr){
              console.log("error response :: ",response);                
          }
      });

    });

    //add comment functionality
    jQuery("#add-comment-btn").click(function(event){
      
      event.preventDefault();
      var creatorId = "<?php echo $_SESSION['morsel_user_obj']->id;?>";
      if(creatorId == ''){
        jQuery("#morsel-comment-modal").modal('hide');
        jQuery("#open-morsel-login1").trigger('click');
        return;
      }
      var morselSite = "<?php echo MORSEL_SITE;?>";
      var avatar_image = "<?php echo MORSEL_PLUGIN_IMG_PATH.'avatar_72x72.jpg'?>";
      var itemId = jQuery("#form-item-id").val();      
      var commentUrl = "<?php echo MORSEL_API_ITEMS_URL;?>"+itemId+"/comments.json";
      var api_key = "<?php echo $_SESSION['morsel_user_obj']->id.':'.$_SESSION['morsel_user_obj']->auth_token;?>";
      commentUrl += '?api_key='+api_key;
      var commentObj = {"comment":{"description":jQuery("#comment-text").val()}};
      
      jQuery.ajax({
          url: commentUrl,                     
          type: "POST",           
          contentType: "application/json; charset=utf-8",
          dataType: "json",
          data: JSON.stringify(commentObj),          
          complete: function(){
            jQuery("#comment-text").val('');                  
          },
          beforeSend: function(xhr) {       
            jQuery("#add-comment-btn").prop("disabled",true);
            //set custome headers
            
            xhr.setRequestHeader('share-by',"morsel-plugin");
            xhr.setRequestHeader('activity','Comment');
            xhr.setRequestHeader('activity-id',itemId);
            xhr.setRequestHeader('activity-type',"Item");
            xhr.setRequestHeader('user-id',"<?php echo $_SESSION['morsel_user_obj']->id;?>");

          },
          success: function(response, status){
            /*console.log("response :: ",response); */
            
            if(status == 'success'){
              //increase link commnet count by 1
              jQuery('[item-id="'+itemId+'"]').attr('comment-count',parseInt(jQuery('[item-id="'+itemId+'"]').attr('comment-count'))+1);              

              var html = creatCommentList(response.data,morselSite,avatar_image);
              jQuery("#comment-list").append(html);
              timeAgo();
              commentsCountText(itemId,true);
            } else {
              alert("Opps Something wrong happend!"); 
              return false;       
            }
          },
          error:function(response, status, xhr){
              alert("Opps Something wrong happend!"); 
              console.log("error response :: ",response);  
              return false;
          }
      });
    });
    
    // on click of comment link show modal
    jQuery(".comment-popup-link").click(function(event){
      event.preventDefault();    
      
      /*var sessionUserId =  "<?php echo $_SESSION['morsel_user_obj']->id;?>"
      if(sessionUserId == ''){
        jQuery("#open-morsel-login1").trigger('click');
        return;
      }*/
      
      //set page no 1 for view more
      jQuery("#view-more-comments").attr("page-no",1);

      //clear comment list 
      jQuery("#comment-list").empty();
      var itemId = jQuery(this).attr('item-id');
      //set item id into hiden input #form-item-id
      jQuery("#form-item-id").val(itemId);
      var commentCount = parseInt(jQuery(this).attr('comment-count'));
      var commentUrl = "<?php echo MORSEL_API_ITEMS_URL;?>"+itemId+"/comments";

      if(commentCount > 0 && commentCount >= 5){        
        commentUrl += '?count=5&page=1';
        jQuery("#view-more-comments").show();
        jQuery("#view-more-comments").attr("page-no",2);
      }

      var morselSite = "<?php echo MORSEL_SITE;?>";
      //var creatorId = "<?php echo $_SESSION['morsel_user_obj']->id;?>";
      var avatar_image = "<?php echo MORSEL_PLUGIN_IMG_PATH.'avatar_72x72.jpg'?>";

      jQuery.ajax({
          url: commentUrl,                     
          type: "GET",           
          complete: function(){
            waitingDialog.hide();
          },
          beforeSend: function(xhr) { 
            waitingDialog.show('Loading...');          
          },
          success: function(response, status){
                        
            if(status == 'success'){
              
              if(response.data.length > 0){
                var html = creatCommentList(response.data,morselSite,avatar_image);                
                jQuery("#comment-list").append(html);
                timeAgo();
              } 
              
              jQuery("#morsel-comment-modal").modal('show');
              
            } else {                  
              alert("Opps Something wrong happend!"); 
              return false;       
            }
          },
          error:function(response, status, xhr){
              console.log("error response :: ",response);  
              alert("Opps Something wrong happend!");
              return false;
          }
      });
    });

    // view more comments click 
    jQuery("#view-more-comments").click(function(event){
      event.preventDefault();    
      
      var itemId = jQuery("#form-item-id").val();      
      var pageNo = parseInt(jQuery("#view-more-comments").attr('page-no'));
      var commentUrl = "<?php echo MORSEL_API_ITEMS_URL;?>"+itemId+"/comments";
      var noMoreComments = false;

      if(pageNo >= 0){
        commentUrl += '?count=5&page='+pageNo;        
      } else {
        commentUrl += '?count=5&page=1';
      }

      var morselSite = "<?php echo MORSEL_SITE;?>";
      var avatar_image = "<?php echo MORSEL_PLUGIN_IMG_PATH.'avatar_72x72.jpg'?>";

      jQuery.ajax({
          url: commentUrl,                     
          type: "GET",           
          complete: function(){    
            jQuery("div.morsel-loader").hide();
            if(!noMoreComments){
              jQuery("#view-more-comments").show();  
            }            
          },
          beforeSend: function(xhr) {                       
            jQuery("div.morsel-loader").show();
            jQuery("#view-more-comments").hide();
          },
          success: function(response, status){
                        
            if(status == 'success'){
              var html = '';
              if(response.data.length > 0){
                html = creatCommentList(response.data,morselSite,avatar_image);                
                $("#comment-list li:first").before( html );       
                timeAgo();
                jQuery("#view-more-comments").attr('page-no',pageNo+1);
              } else {
                noMoreComments = true;
              }
              
            } else {                  
              alert("Opps Something wrong happend!"); 
              return false;       
            }
          },
          error:function(response, status, xhr){
              console.log("error response :: ",response);                
              alert("Opps Something wrong happend!"); 
              return false;
          }
      });
    });
    
    //morsel like function
    jQuery("#like-btn-link").click(function(){

      var sessionUserId =  "<?php echo $_SESSION['morsel_user_obj']->id;?>"
      if(sessionUserId == ''){
        jQuery("#open-morsel-login1").trigger('click');
        return;
      }
      
      var likeUrl = "<?php echo MORSEL_API_MORSELS_URL.$_REQUEST['morselid'].'/like.json?api_key='.$_SESSION['morsel_user_obj']->id.':'.$_SESSION['morsel_user_obj']->auth_token;?>";

      var reqType = 'POST';
      var activity = 'morsel-like';
      //if user already liked than unlike
      if(jQuery("#like-btn-link i").hasClass('common-like-filled')){
        reqType = 'DELETE';
        activity = 'morsel-unlike';
      }

      jQuery.ajax({
          url: likeUrl,                     
          type: reqType,           
          complete: function(){
          },
          beforeSend: function(xhr) {
            // xhr.setRequestHeader('host-site',"<?php echo get_site_url(); ?>");
            xhr.setRequestHeader('share-by',"morsel-plugin")
            if(activity=="morsel-like") {
              xhr.setRequestHeader('activity',"Like");
            } else {
              xhr.setRequestHeader('activity',"Unlike");
            }         
            xhr.setRequestHeader('activity-id',"<?php echo $_REQUEST['morselid'];?>");
            xhr.setRequestHeader('activity-type',"Morsel");
            xhr.setRequestHeader('user-id',"<?php echo $_SESSION['morsel_user_obj']->id;?>");
          },
          success: function(response, status){
            console.log("response :: ",response);  
            console.log("status :: ",status);
            
            if(status == 'success'){
              

              if(reqType == 'POST'){
                jQuery("#like-btn-link i").attr("class","common-like-filled");
                jQuery("#like-btn-link").attr("title","You have already liked this morsel");
                likesCountText(true);
                $("#morsel-like-others-modal").modal('show');
              } else {
                jQuery("#like-btn-link i").attr("class","common-like-empty");
                jQuery("#like-btn-link").attr("title","Like morsel");                
                likesCountText(false);
              }
              
            } else {                  
              alert("Opps Something wrong happend!"); 
              return false;       
            }
          },
          error:function(response, status, xhr){
              console.log("error response :: ",response);  
              console.log("errors :: ",response.responseJSON.errors);                  
              jQuery("#like-btn-link").attr("title","You've "+response.responseJSON.errors.morsel[0]+" this morsel.");              
              return false;
          }
      });
    });

      jQuery("#mrsl-signup-submit-btn").click(function(event){

          event.preventDefault();
          var signupForm = jQuery("#mrsl-signup-form");
          
          console.log("photo file ",document.getElementById("mrsl_user_photo").files[0]);

          var fd = new FormData();
          fd.append("user[email]",jQuery( "#mrsl_user_email" ).val());
          fd.append("user[password]",jQuery( "#mrsl_user_password" ).val());
          fd.append("user[username]",jQuery( "#mrsl_user_username" ).val());
          fd.append("user[first_name]",jQuery( "#mrsl_user_first_name" ).val());
          fd.append("user[last_name]",jQuery( "#mrsl_user_last_name" ).val());
          
          if(document.getElementById("mrsl_user_photo").files[0]){
            fd.append("user[photo]",document.getElementById("mrsl_user_photo").files[0]);  
          } 
          
          fd.append("user[professional]",jQuery( "#mrsl_user_professional" ).val());

          jQuery.ajax({
            url: "<?php echo MORSEL_API_URL.'users.json';?>",                     
            data : fd,
            type:'POST',
            contentType: false,
            cache: false,      
            processData:false, 
            beforeSend: function(xhr){
                /*jQuery("#morselLoginModal").modal('hide');
                waitingDialog.show('Your request is processing, please wait.');*/
                jQuery('#morsel-progress').show();
                jQuery("#mrsl-signup-submit-btn").hide();
                xhr.setRequestHeader('host-site',"<?php echo get_site_url(); ?>");
                xhr.setRequestHeader('host-site',"<?php echo get_site_url(); ?>");
                xhr.setRequestHeader('share-by',"morsel-plugin")
                xhr.setRequestHeader('activity','sign-up');
                xhr.setRequestHeader('morsel-id',"<?php echo $_REQUEST['morselid'];?>");
                xhr.setRequestHeader('user-id',"0");              
            },
            complete: function(){
                //jQuery("#morselLoginModal").modal('show');
                //waitingDialog.hide();
                jQuery('#morsel-progress').hide();
                jQuery("#mrsl-signup-submit-btn").show();
            },
            success: function(response){
              
              if(response.meta.status == 200){
                
                //set for host login
                jQuery("#mrsl-login").val(response.data.username);
                jQuery("#mrsl-password").val(jQuery("#mrsl_user_password").val());
                jQuery("#morsel-front-login-form").submit();

              } else {
                alert("Opps Something wrong happend!"); 
                return false;       
              }
            },
            error:function(response){
                console.log("response :: ",response);                
                var err = response.responseJSON.errors;
                
                if(err.first_name){                    
                    jQuery("#mrsl_user_first_name").parent(".form-group").append('<label for="mrsl_user_first_name" class="error" style="display: inline-block;">First Name '+err.first_name[0]+'</label>');
                    jQuery("#mrsl_user_first_name").parent(".form-group").addClass("has-error");
                  }

                  if(err.last_name){
                    jQuery("#mrsl_user_last_name").parent(".form-group").append('<label for="mrsl_user_last_name" class="error" style="display: inline-block;">Last Name '+err.last_name[0]+'</label>');
                    jQuery("#mrsl_user_last_name").parent(".form-group").addClass("has-error")
                  }

                  if(err.photo){
                    jQuery("#mrsl-signup-error-box").html("Photo "+err.photo[0]);
                    jQuery("#mrsl-signup-error-box").show();
                  }

                  if(err.username){                    
                    jQuery("#mrsl_user_username").parent(".form-group").append('<label for="mrsl_user_username" class="error" style="display: inline-block;">Username '+err.username[0]+'</label>');
                    jQuery("#mrsl_user_username").parent(".form-group").addClass("has-error");
                  }

                  if(err.password){                    
                    jQuery("#mrsl_user_password").parent(".form-group").append('<label for="mrsl_user_password" class="error" style="display: inline-block;">Password '+err.password[0]+'</label>');
                    jQuery("#mrsl_user_password").parent(".form-group").addClass("has-error");
                  }

                  if(err.email){                    
                    jQuery("#mrsl_user_email").parent(".form-group").append('<label for="mrsl_user_email" class="error" style="display: inline-block;">Email '+err.email[0]+'</label>');
                    jQuery("#mrsl_user_email").parent(".form-group").addClass("has-error");
                  }
              }
          });
      });
  });
</script>
<?php    
}
add_shortcode('morsel_post_des', 'morsel_post_des');
add_action('wp_head', 'morsel_metatags',1);

//unset jetpack plugin metas
if(isset($_REQUEST['morselid'])){    
   add_filter('jetpack_enable_open_graph', 'jetpackMetaDisable');
}

function jetpackMetaDisable(){
  return False;
}
//end unset jetpack plugin metas

// Set your Open Graph Meta Tags & get user details & get morsel likers
function morsel_metatags() {
  global $morsel_detail;
  global $morsel_user_detail;
  global $morsel_likers;
  
  if(isset($_REQUEST['morselid'])){
    $options = get_option( 'morsel_settings');
    $api_key = $options['userid'] . ':' .$options['key'];        
    $jsonurl = MORSEL_API_URL."morsels/".$_REQUEST['morselid']."?api_key=".$api_key;
    $morsel_detail = get_json($jsonurl)->data;
    
    $userJsonUrl = MORSEL_API_USER_URL.$morsel_detail->creator->id.".json";
    $morsel_user_detail = get_json($userJsonUrl)->data;  

    $likersUrl = MORSEL_API_MORSELS_URL.$_REQUEST['morselid'].'/likers.json';
    $morsel_likers = get_json($likersUrl)->data;    
    

    if($morsel_detail->primary_item_photos->_992x992)
      $img_url = $morsel_detail->primary_item_photos->_992x992;
    else 
      $img_url = MORSEL_PLUGIN_IMG_PATH.'no_image.png';
    ?>
        <meta name="twitter:card" content="photo">
        <meta name="twitter:site" content="@eatmorsel" />        
        <meta name="twitter:image:src" content="<?php echo $img_url; ?>">
        <!-- <meta name="twitter:title" content="<?php echo htmlspecialchars($morsel_detail->title.' - '.$morsel_user_detail->first_name.' '.$morsel_user_detail->last_name.' | Morsel'); ?>">
        <meta name="twitter:description" content="<?php echo htmlspecialchars($morsel_detail->items['0']->description); ?>"> -->    

        <meta name="description" content="<?php echo htmlspecialchars($morsel_detail->items['0']->description); ?>"/>
        <meta property="og:url" content="<?php echo get_permalink() ?>?<?php echo $_SERVER['QUERY_STRING'] ?>"/>  
        <meta property="og:title" content="<?php echo htmlspecialchars($morsel_detail->title.' - '.$morsel_user_detail->first_name.' '.$morsel_user_detail->last_name.' | Morsel') ; ?>">
        <meta property="og:description" content="<?php echo htmlspecialchars($morsel_detail->items['0']->description); ?>"/>
        <meta property="og:site_name" content="<?php bloginfo(); ?>"/>
        <meta property="og:image" content="<?php echo $img_url; ?>"/>
        <meta property="og:image:secure_url" content="<?php echo $img_url; ?>"/>        
        <meta property="og:type" content="article" />  
<?php 
  }

}

//get shortenurl
function  getShortenUrl(){
  $url = "http://rest.sharethis.com/v1/share/shorten?url=".get_permalink()."?".$_SERVER['QUERY_STRING']."&api_key=5f0a497f-b77e-4c5a-a4d1-8f543aa2e9f";
  $response = file_get_contents($url);
  return json_decode($response)->data->sharURL;
}


add_action('wp_head', 'queue_my_admin_scripts',1);

function queue_my_admin_scripts() {
    
    //add validation js
    

    //add validation js http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js
    wp_register_script('jquery-validation-plugin', MORSEL_PLUGIN_WIDGET_ASSEST.'js/jquery.validate.min.js', array('jquery'));
    wp_enqueue_script('jquery-validation-plugin'); 
    
    //add bootstrap js http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js
    wp_register_script('bootstrap-js', MORSEL_PLUGIN_WIDGET_ASSEST.'js/bootstrap.min.js',array('jquery'));    
        
    wp_enqueue_script('bootstrap-js');

    //enque js script for shorcode forms
    wp_register_script('morsel-post-des',MORSEL_PLUGIN_WIDGET_ASSEST.'js/morsel_post_des.js', array('jquery','jquery-validation-plugin','bootstrap-js'));
    wp_enqueue_script('morsel-post-des');
}

//check is current user likes current morsel
function userIsLike($users){
  $result = false;  
  if(is_array($users) && (count($users) > 0)){    
    foreach ($users as $user) {
      if($_SESSION['morsel_login_userid'] == $user->id ){        
        $result = true;
      }
    }
  }
  return $result;
}

?>