<?php
function grid($row_sht,$morsel_page_id,$morsel_in_row='3') {
  $morsel_url = add_query_arg( array('morselid' => $row_sht->id), get_permalink($morsel_page_id));
  $class_morsel_block = array('1' =>'col-sm-12 col-md-12','2' =>'col-sm-6 col-md-6','3' =>'col-sm-4 col-md-4', '4' =>'col-sm-3 col-md-3', '6'=>'col-sm-2 col-md-2');
?>  
    <div class="<?php echo $class_morsel_block[$morsel_in_row];?> shortcode-msl-block">
      <div  class="morsel-block morsel-bg" morsel-url="<?php echo $morsel_url; ?>" >
        <div class="morsel-info">
            <h1 class="h2 morsel-block-title">
              <a class="white-link" href="<?php echo $morsel_url; ?>"><?php echo $row_sht->title;?></a>
            </h1>
            <div class="morsel-info-bottom">
                <h3  class="h6 morsel-block-place ">
                    <?php if($row_sht->creator->photos != ""){ //_144x144?>
                      <img class="morselUserImage" src="<?=$row_sht->creator->photos->_144x144?>"/>
                    <? }?>
                    <a class="white-link overflow-ellipsis" href="<?php echo $morsel_url; ?>">
                      <? echo $row_sht->creator->first_name.' '.$row_sht->creator->last_name;?>
                    </a>
                </h3>
            </div>
        </div>
        <?php               
              if($row_sht->photos->_800x600){
                $img_url = $row_sht->photos->_800x600;
              } else {
                $img_url = MORSEL_PLUGIN_IMG_PATH.'no_image.png';
              }
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
      'center_block' => 0,
      'wrapper_width' => "",
      'keyword_id'=>NULL,
      'associated_user'=>"0",
      'topic_name'=>NULL,
      'morsel_in_row'=>'3'
    ), $atts, 'morsel_post_display' );

  $morsel_page_id = get_option( 'morsel_plugin_page_id');
  $options = get_option( 'morsel_settings');
  $api_key = $options['userid'] . ':' .$options['key'];
  $morselCount = ($atts['count'] > 0)?$atts['count'] : MORSEL_API_COUNT;
  $keywordID = ($atts['keyword_id'] > 0) ? "&keyword_id=".$atts['keyword_id'] : "";
  $topicName = ($atts['topic_name'] != NuLL) ? "&topic_id=".$atts['topic_name'] : "";
  $userID = (isset($atts['associated_user']) && $atts['associated_user'] != "0" && $atts['associated_user'] != "")? $atts['associated_user']:$options['userid'];
  $atts['associated_user'];
  $jsonurl = MORSEL_API_URL."users/".$userID."/morsels.json?api_key=$api_key&count=".$morselCount.$keywordID.$topicName;

  $json = json_decode(file_get_contents($jsonurl));//wp_remote_get

  if(count($json->data)==0){
      $json = json_decode(wp_remote_fopen($jsonurl));
  }

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
        <style type="text/css">
          <?php if($atts['center_block'] == 1){ ?>
                 #morsel-posts-row {
                    font-size: 0;
                    text-align: center;
                  }
                  #morsel-posts-row .shortcode-msl-block {
                    display: inline-block;
                    float: none;
                  }
                  @media screen and (max-width: 767px) {
                    #morsel-posts-row .shortcode-msl-block{
                      display: block;
                    }
                  }
          <?php } ?>
          <?php if(isset($atts['gap_in_morsel'])){ ?>
                  #morsel-posts-row .shortcode-msl-block {
                    padding: 0 <?php echo $atts['gap_in_morsel'];?>!important;
                  }
          <?php } ?>
           <?php if(isset($atts['wrapper_width'])){ ?>
                  .page-wrapper {
                    width: <?php echo $atts['wrapper_width'];?>%;
                    margin: 0 auto;
                  }
          <?php } ?>

        </style>
        <?php  /* Turn on buffering */
            ob_start(); ?>
            <script type="text/javascript">
              jQuery(document).ready(function () {
                  jQuery("iframe").width("100%");
                  jQuery("iframe").height("96%");
                  jQuery("iframe").css("opacity","0.5");
              });
            </script>
           <div class="page-wrapper morsel-iso bootstrap-iso" >
                  <div class="site">
                      <div class="tab-content">
                          <div class="tab-pane active">
                              <div class="row no-gutter" id="morsel-posts-row">
                              <?php foreach ($morsel_post_sht as $row_sht) {
                                  if(in_array($row_sht->id, $post_selected))
                                    continue;
                                  echo grid($row_sht,$morsel_page_id,$atts['morsel_in_row']);
                               } ?>
                              </div>
                          </div>
                      </div>
               </div>
               <div class="col-sm-12 col-md-12 load-more-wrap" >
                <?php if($atts['count'] == 0) { ?>
                 <button style="display:none" class="btn btn-primary morselbtn" type="button" id="load-morsel" morsel-count="<?php echo $atts['count'];?>" >View more morsels</button>
                 <div id="ajaxLoaderFront" style="display:none">
                   <span><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajax-loader.gif"></span>
                 </div>
                <script>
                    jQuery(document).ready(function() {
                      var topOfOthDiv = jQuery(".load-more-wrap").offset().top;
                      jQuery(window).scroll(function() {
                        if (((jQuery(window).scrollTop() + 450) > (topOfOthDiv - 100))) { //scrolled past the other div?
                          loadMorsel();
                        }
                      });
                    });
                    var morselNoMore;
                    function loadMorsel() {
                      var morsePageCount = 1;
                      var count = '20';
                      if (jQuery(this).attr("morsel-count")) {
                        count = jQuery(this).attr("morsel-count");
                      }
                      if (jQuery('#ajaxLoaderFront:visible').length == 0) {
                        if (morselNoMore != true) {
                          jQuery("#ajaxLoaderFront").css("display", "block");
                          jQuery.ajax({
                            url: "index.php?pagename=morsel_ajax&page_id=" + parseInt(++morsePageCount) + "&morsel-count=" + count,
                            success: function(data) {
                              if (data.trim().length > 1)
                                jQuery("#morsel-posts-row").append(data);
                              else {
                                morsePageCount--;
                                morselNoMore = true;
                              }
                              jQuery('[morsel-url]').click(function() {
                                window.location.href = jQuery(this).attr('morsel-url');
                              })
                            },
                            error: function() {
                              morsePageCount--;
                            },
                            complete: function() {
                              console.log("morselView load");
                              jQuery("#ajaxLoaderFront").css("display", "none");
                              // load.html('View more morsels');
                            }
                          });
                        }
                      }
                    }
                </script>
                <?php } ?>
               </div>
          </div>
          <?php
          /* Get the buffered content into a var */
         $sc = ob_get_contents();

         /* Clean buffer */
        ob_end_clean();

           /* Return the content as usual */
          return $sc;
          ?>
    <?php
      } else { //end if
         //echo "You have no morsel!";
      } ?>

  <?php
    }
    add_shortcode('morsel_post_display', 'morsel_post_display');

//shortcode for description
function morsel_post_des(){

  global $morsel_detail;
  global $morsel_user_detail;
  global $morsel_likers;

  // get all users whome are associate with host admin
  $options = get_option( 'morsel_settings');

  //add other settings also
  if(get_option('morsel_other_settings')){
    $options = array_merge($options,get_option('morsel_other_settings'));
  }

  $jsonurl = MORSEL_API_USER_URL.$options['userid']."/association_requests.json?api_key=".$options['userid'].':'.$options['key'];
  $associate_users = json_decode(file_get_contents($jsonurl));
  if(count($associate_users->data)==0){
    $associate_users = json_decode(wp_remote_fopen($jsonurl));
  }

  $is_associated = false;
  if(isset($morsel_user_detail->id)){
    foreach ($associate_users->data as $obj) {
      if($obj->associated_user->id == $morsel_user_detail->id){ //current user is
        $is_associated = true;
        break;
      }
    }
  }

  //end get all users whome are associate with host admin

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
<style type="text/css">
  /*morsel description*/
  
  .videoIframe { width: 992px; height:750px;}
  
  @media screen and (max-width: 420px) {
    .itemDesktop { display: none;}
    .itemMobile {display: block !important;}
    .videoIframe { width: 80%!important; height: 80%!important;}
  }
  .noIdontWant{padding:5px 0; font-size:9px;}
</style>  
<div class="page-wrapper page-wrapper-details center-block morsel-iso bootstrap-iso">

      <div>
        <div class="modal-morsel-full-slide " >
            <div class="morsel-full">
            <?php if(isset($_SESSION['morsel_error'])) { unset($_SESSION['morsel_error']);?>
                 <div class="alert alert-danger text-center" role="alert">Sorry your userid/email or password not matched, please try again.</div>
            <?php } ?>
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

                    <?php //check if hide_login_settings are enabled or not.
                      if((!$options["hide_login_btn"]) && ($options["hide_login_btn"] != 1 )) {
                        if(!empty($_SESSION['morsel_login_userid'])){?>
                      <a href="<?php echo site_url()?>/index.php?pagename=morsel_logout" class="btn btn-danger btn-xs">Logout</a>
                    <?php } else { ?>
                          <a id="open-morsel-login1" class="open-morsel-login btn btn-danger btn-xs clickeventon">SignUp/Login</a>
                    <?php }
                      }
                    ?>
                    </span>
                  </h2>
                  <div class="user ">
                        <span class="profile-pic-link profile-pic-xs">
                            <img class="img-circle"  src="<?php echo $creat_img;?>" width="40">
                        </span>
                        <?php echo $creator = $morsel_detail->creator->first_name." ".$morsel_detail->creator->last_name; ?>
                  </div>
                </div>
        <?php 
        if($morsel_detail->primary_item_photos->_992x992)
          $img_url = $morsel_detail->primary_item_photos->_992x992;
        else
          $img_url = MORSEL_PLUGIN_IMG_PATH.'no_image.png';
        ?>
        <?php $items = $morsel_detail->items; ?>
        <!-- Item start -->
        <?php foreach ($items as $row_item) {?>

        <?php 
              $text = html_entity_decode($row_item->description);
              preg_match('/<iframe.*src=\"(.*)\".*><\/iframe>/isU', $text, $matches);
        ?>
        <div class="slide-item morsel-item " style="padding:0px !important" >
          <hr>
          <div class="item-img-wrap">
            <div class="item-img image-loaded">
              <div>
                <?php
                    if(count($matches)!= 0){
                      echo '<iframe class="videoIframe" src="'.$matches[1].'?modestbranding=1&autohide=1&showinfo=0&controls=0"  allowfullscreen></iframe>';
                      $itemDes = preg_replace('/<iframe.*?\/iframe>/i','', $text);
                    } else {
                      $itemDes = $row_item->description;                 
                        if($row_item->photos->_992x992){
                          $items_url = $row_item->photos->_992x992;
                          $items_urlMobile = $row_item->photos->_320x320;
                        }
                        else{
                          $items_url = MORSEL_PLUGIN_IMG_PATH.'no_image.png';
                          $items_urlMobile = MORSEL_PLUGIN_IMG_PATH.'no_image.png';
                        }
                      echo "<img class='itemDesktop' src='".$items_url."' style='max-width:992px !important;'><img class='itemMobile' style='display:none' src='".$items_urlMobile."' style='max-width:320px !important;'>";
                    }
                ?>
                 </div>
            </div>
          </div>
          <div class="clear"></div>
          <div class="item-info">
            <div class="item-description">
              <p><?php echo nl2br($itemDes);?></p>
            </div>
            <!-- comments area -->
            <div class="item-comments">
              <a class="dark-link comment-popup-link" item-id="<?php echo $row_item->id;?>" comment-count="<?php echo ($row_item->comment_count) ? $row_item->comment_count: 0; ?>">
              <?php if($row_item->comment_count) { ?>
                      <i class="common-comment-filled"></i>
                      <span id="comment-count-<?php echo $row_item->id;?>"><?php echo $row_item->comment_count;?><?php echo ($row_item->comment_count > 1)?' comments':' comment';?></span>
              <?php } else { ?>
                      <i class="common-comment-empty"></i>
                      <span id="comment-count-<?php echo $row_item->id;?>" class="addComment" >Add comment</span>
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

                <span class='st_pinterest_large' displayText='Pinterest' st_url="<?php echo $shtnUrl;?>" st_title="<?php echo htmlspecialchars($morsel_detail->items['0']->description);?>" st_summary="<?php echo htmlspecialchars($pintrestShareSummry);?>" st_image="<?php echo $img_url?>"></span>

                <span id="embed-code-link" data-target="#morsel-embed-modal" data-toggle="modal">
                  <span class="embed-code stButton"><span class="embed-code stLarge" ></span></span>
                </span>
            </div>
            <span class="Unsubscribe_button" style="font-size:18px;"></span>
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
      <?if(!empty($_SESSION['morsel_login_userid'])){?>
      <script type="text/javascript">
      jQuery( document ).ready(function() {
          var ids = [];
          jQuery.ajax({
            url:"<?php echo MORSEL_API_USER_URL.$_SESSION['morsel_user_obj']->id;?>"+"/subscribed_keyword.json",
            type:"GET",
            async:false,
            crossDomain: true,
            dataType: "json",
            data:{
              api_key : "<?php echo $_SESSION['morsel_user_obj']->id.':'.$_SESSION['morsel_user_obj']->auth_token;?>"
            },
            success: function(response) {
              if(response.meta.status == "200" && response.meta.message == "OK"){
                ids = response.data;
                if(jQuery.inArray(<?=$morsel_detail->morsel_keywords[0]->id?>,ids)!=-1){
                  //match
                  jQuery(".Unsubscribe_button").html("<a id='unsubscribe_morsel_info'>Unsubscribe</a>");
                } else {
                  jQuery(".Unsubscribe_button").html("<a id='subscribe_morsel_info'>Subscribe</a>");
                }
                return;
              }
            },
            error:function(e){

            }
          });
        });
        //unsubscribe_morsel_info
        jQuery("#unsubscribe_morsel_info").live("click",function(){
          var selected_ids = new Array();
          selected_ids.push(<?=$morsel_detail->morsel_keywords[0]->id;?>);
          console.log("keyword for unsubscribe : ",<?=$morsel_detail->morsel_keywords[0]->id;?>);
          //unsubscribe the keywords
        jQuery.ajax({
            url:"<?php echo MORSEL_API_USER_URL.$_SESSION['morsel_user_obj']->id;?>"+'/unsubscribe_users_keyword.json',
            type:"DELETE",
            async:false,
            data:{
              user: {keyword_id:selected_ids},
              api_key : "<?php echo $_SESSION['morsel_user_obj']->id.':'.$_SESSION['morsel_user_obj']->auth_token;?>"
            },
            beforeSend: function(xhr) {
              waitingDialog.show('Please wait while loading...');
              xhr.setRequestHeader('host-site',"<?php echo get_site_url(); ?>");
              xhr.setRequestHeader('share-by',"morsel-plugin")
              xhr.setRequestHeader('activity','keyword-unsubscribe');
              xhr.setRequestHeader('user-id',"<?php echo $user->id;?>");
            },
            success: function(response) {
              console.log("new_born_morsel response: ",response);
              if(response.meta.status == "200" && response.meta.message == "OK"){
                alert("You are successfully unsubscribed the keywords");
                jQuery(".Unsubscribe_button").html("<a id='subscribe_morsel_info'>Subscribe</a>");
              }
            },error:function(){},
            complete:function(){
              waitingDialog.hide();
            }
        });
      });
        //subscribe_morsel_info
        jQuery("#subscribe_morsel_info").live("click",function(){
          var selected_ids = new Array();
          selected_ids.push(<?=$morsel_detail->id;?>);
          console.log("keyword for subscribe : ",<?=$morsel_detail->id;?>);
          //unsubscribe the keywords
          jQuery.ajax({
            url:"<?php echo MORSEL_API_USER_URL.'morsel_subscribe'; ?>",
            type:"POST",
            async:false,
            data:{
              user: {subscribed_morsel_ids:selected_ids},
              api_key : "<?php echo $_SESSION['morsel_user_obj']->id.':'.$_SESSION['morsel_user_obj']->auth_token;?>"
            },
            complete: function(){
              waitingDialog.hide();
            },
            beforeSend: function(xhr) {
              waitingDialog.show('Loading...');
            },
            success: function(response, status){
              if(status == 'success'){
                alert("You have been subscribed successfully.");
                jQuery(".Unsubscribe_button").html("<a id='unsubscribe_morsel_info'>Unsubscribe</a>");
              } else {
                alert("Opps Something wrong happend!");
              }
            },
            error:function(response, status, xhr){
                console.log("error response :: ",response);
            }
          });
        });

      </script>
        <?php 
         }
      } ?>   <!-- End else part -->
      </div><!-- end ngIf: morsel && showMorsel -->
    </div><!-- end ngIf: type === 'morsel' -->

  <!-- Share script -->
  <script type="text/javascript">var switchTo5x=true;</script>
  <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
  <script type="text/javascript">stLight.options({publisher: "5f0a497f-b77e-4c5a-a4d1-8f543aa2e9fb", doNotHash: false, doNotCopy: false, hashAddressBar: false,shorten: true});
  </script>
  <!-- Share script -->

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
            </div>

            <div class="modal-body">
                <p>Hungry for more? Sign up to receive an update when stories about <? if($morsel_detail->morsel_keywords[0]->name !=""){echo $morsel_detail->morsel_keywords[0]->name;}?> are posted.</p>
                <form novalidate="" name="addCommentForm" role="form" class="">
                  <div class="checkbox">
                    <label><input type="checkbox" checked="checked" value="1">Yes, let me know when morsels like this are posted.</label>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
              <button id="morsel-subscribe" type="button" class="btn btn-primary orange-btn" data-dismiss="modal">Sign Up</button>
              <br><a href="javascript:void(0)" class="noIdontWant" data-dismiss="modal">No thank you, I do not want to receive email updates.</a> 
            </div>
        </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <!-- End After like modal  -->

  <!-- After like modal -->
  <div class="modal fade" id="associat-request-modal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>For add morsel from here, you have to associate with the administrator account of the host site.</p>
                <form novalidate="" name="addCommentForm" role="form" class="">
                  <div class="checkbox">
                    <label><input type="checkbox" checked="checked" value="1" id="mrsl-associate-checkbox">Yes, let me associate with the administrator account.</label>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">No Thanks</button>
              <button id="morsel-associate-btn" type="button" class="btn btn-primary orange-btn" data-dismiss="modal">Save Changes</button>
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
        jQuery(".open-morsel-login").trigger('click');
        return;
      }
      var subscribeUrl = "<?php echo MORSEL_API_USER_URL.'morsel_subscribe'; ?>";
      var activity = 'morsel-subscribe';
      var key = "<?php echo $_SESSION['morsel_user_obj']->id.':'.$_SESSION['morsel_user_obj']->auth_token;?>";
      var morselId = "<?php echo $_REQUEST['morselid'];?>";
      var post_data = {
                        user:{subscribed_morsel_ids : [morselId] },
                        api_key:key
                      };

      console.log("post_data : ",post_data);
      jQuery.ajax({
          url: subscribeUrl,
          type: 'POST',
          data: post_data,
          complete: function(){
            waitingDialog.hide();
          },
          beforeSend: function(xhr) {
            xhr.setRequestHeader('host-site',"<?php echo get_site_url(); ?>");
            xhr.setRequestHeader('share-by',"morsel-plugin");
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
              alert("You have been subscribed successfully.");
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
        jQuery(".open-morsel-login").trigger('click');
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

      var sessionUserId =  "<?php echo $_SESSION['morsel_user_obj']->id;?>";
      if(sessionUserId == ''){
        jQuery(".open-morsel-login").trigger('click');
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
                jQuery("#morsel-like-others-modal").modal('show');
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
    $morsel_detail = json_decode(wp_remote_retrieve_body(wp_remote_get($jsonurl)))->data;
       
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
    //add validation js http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js
    wp_register_script('jquery-validation-plugin', MORSEL_PLUGIN_WIDGET_ASSEST.'js/jquery.validate.min.js', array('jquery'));
    wp_enqueue_script('jquery-validation-plugin');

    //add bootstrap js http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js
    wp_register_script('bootstrap-js', MORSEL_PLUGIN_WIDGET_ASSEST.'js/bootstrap.min.js',array('jquery'));
    wp_enqueue_script('bootstrap-js');

    //enque js script for shorcode forms
    wp_register_script('morsel-post-des',MORSEL_PLUGIN_WIDGET_ASSEST.'js/morsel_post_des.js', array('jquery','jquery-validation-plugin','bootstrap-js'));
    wp_enqueue_script('morsel-post-des');

    wp_enqueue_script('my-script', MORSEL_PLUGIN_WIDGET_ASSEST.'js/morsel_js_utility.js');
    wp_localize_script('my-script', 'script_data', array(
        "morsel_user" => $_SESSION['morsel_user_obj'],
        "morsel_api_url" => MORSEL_API_URL,
        "morsel_api_user_url" => MORSEL_API_USER_URL,
        "morsel_site" => MORSEL_SITE,
        "current_morsel_id" => $_REQUEST["morselid"]
      )
    );
    //enque stylesheets
    wp_enqueue_style('bootstrap-iso', MORSEL_PLUGIN_WIDGET_ASSEST.'css/bootstrap-iso.css');
    wp_enqueue_style('morsel_list', MORSEL_PLUGIN_WIDGET_ASSEST.'css/morsel_list.css');
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