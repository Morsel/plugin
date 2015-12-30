<?php
//morsel_user_preference shortcode funcationaliy which get all key words of
function morsel_user_preference($atts){
  $atts = shortcode_atts(
    array(

    ), $atts, 'morsel_user_preference' ); ?>
  <div id="user-prefrence-wrapper" class="morsel-iso bootstrap-iso">
  <?php  /*checked eatmorsel user isn't logged in*/
  if((!isset($_SESSION['morsel_login_userid'])) && (!isset($_SESSION['morsel_user_obj']))){
  ?>
    <div class='row'>
      <div class='col-md-6'>You must be sign/login before create a morsel, click here for <a class='open-morsel-login btn btn-danger btn-xs'>SignUp/Login</a></div>
    </div>
<?php
  } else { //user is logged in
    $morsel_desc_url =  get_permalink(get_option( 'morsel_plugin_page_id'));

    $user = $_SESSION['morsel_user_obj'];
    $user_id = $user->id;
    $api_key = $user->id.':'.$user->auth_token;

    $host_info = get_option('morsel_settings');

    if($host_info){
      $host_id = $host_info["userid"];
      $host_api_key = $host_info["userid"].':'.$host_info["key"];
    }
    ?>
    <div id="user-prefrence-blocks" class='row'>
    </div>
    <div class="center-block text-center col-sm-12 col-md-12">
      <button id="all-keyword-select" class="btn btn-danger select" id="clear-all-select">Select All</button>
      <button class="btn btn-danger" id="keyword-unsubscribe">Unsubscribe</button>
      <button class="btn btn-danger" id ="morsel-subscription">Subscribe</button>
    </div>
  <script type="text/javascript">
   jQuery("#morsel-subscription").click(function(event){
      event.preventDefault();
      //var sessionUserId =  "<?php echo $_SESSION['morsel_user_obj']->id;?>"
      // if(sessionUserId == ''){
      //   jQuery(".open-morsel-login").trigger('click');
      //   return;
      // }

      var subscribeUrl = "<?php echo MORSEL_API_USER_URL.'morsel_subscribe'; ?>";
      var morselId = [];
      jQuery.each(jQuery("input[name='keyword_id[]']:checked"), function() {
          morselId.push(parseInt(jQuery(this).attr('morsel-id')));
        });
      var post_data = {
                        user:{subscribed_morsel_ids : morselId },
                        api_key:"<?php echo $api_key ?>"
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

  function subscribed_keyword()
  {
    var ids = [];
    jQuery.ajax({
      url:"<?php echo MORSEL_API_USER_URL.$user_id;?>"+"/subscribed_keyword.json",
      type:"GET",
      async:false,
      crossDomain: true,
      dataType: "json",
      data:{
        api_key : "<?php echo $api_key;?>" ,
      },
      success: function(response) {
        if(response.meta.status == "200" && response.meta.message == "OK"){
          ids = response.data;
        }
      },
      error:function(e){

      }
    });
    return ids;
  }
  jQuery(document).ready(function($){
      //get all keywords with example of loggedin user
      jQuery.ajax({
        url:"<?php echo MORSEL_API_USER_URL.$host_id;?>"+"/subscribe_morsels.json",
        type:"GET",
        data:{
          api_key : "<?php echo $host_api_key;?>" ,
        },
        beforeSend: function(xhr) {
          waitingDialog.show('Please wait while loading...');
          $("#user-prefrence-blocks").html("");
        },
        success: function(response) {
          console.log("host keyword response: ",response);
          var blocks = "";
          var morsel_desc_url = "<?php echo $morsel_desc_url;?>";
          if(response.meta.status == "200" && response.meta.message == "OK"){
            var result = response.data;
            var keyword_ids = subscribed_keyword();
            $.each(result, function( key, value ) {

              if(value.first_morsel != null){
                 blocks += '<div class="col-sm-4 col-md-4 shortcode-msl-block">';
                blocks += '<div class="morsel-block morsel-bg" morsel-url="'+morsel_desc_url+'?morselid='+value.first_morsel.id+'" >\
                    <div class="morsel-info">\
                    <h1 class="h2 morsel-block-title"><a class="white-link" href="'+morsel_desc_url+'?morselid='+value.first_morsel.id+'">'+value.first_morsel.title+'</a></h1>\
                    <div class="morsel-info-bottom">\
                        <h3 class="h6 morsel-block-place">';
                          if(value.creator_info.photos._144x144){
                            blocks += '<img class="morselUserImage" src="'+value.creator_info.photos._144x144+'"/>';
                          }
                blocks += '<a class="white-link overflow-ellipsis" href="'+morsel_desc_url+'?morselid='+value.first_morsel.id+'">'+value.creator_info.first_name+' '+value.creator_info.last_name+'</a>\
                        </h3>\
                    </div>\
                </div>';

                var mrsl_img_url = "";
                if(value.first_morsel.photos._800x600 != ""){
                 mrsl_img_url = value.first_morsel.photos._800x600;
                }else{
                 mrsl_img_url = "<?php echo MORSEL_PLUGIN_IMG_PATH.'no_image.png'?>";
                }
                blocks +=  '<a class="morsel-img " href="#" style="background-image: url('+mrsl_img_url+');"></a>\
                        <img class="spacer loader " src="'+"<?php echo MORSEL_PLUGIN_IMG_PATH.'spacer.png'?>"+'">\
                        </div>';
                if(jQuery.inArray(value.id,keyword_ids)!=-1){
                  blocks += '<div class="checkbox text-center"><label><input type="checkbox" name="keyword_id[]" morsel-id="'+value.first_morsel.id+'" checked value="'+value.id+'">'+value.name+'</label></div>';
                }
                else{
                  blocks += '<div class="checkbox text-center"><label><input type="checkbox" name="keyword_id[]" morsel-id="'+value.first_morsel.id+'" value="'+value.id+'">'+value.name+'</label></div>';
                }
               blocks += '</div>';
              }else{
               //  blocks += '<div class="col-sm-4 col-md-4 shortcode-msl-block">';
               //  blocks += '<div class="morsel-block morsel-bg">\
               //        <div class="morsel-info">\
               //        <h1 class="h2 morsel-block-title"><a class="white-link">No Morsel Available</a></h1>\
               //        </div>';
               //  blocks += '<a class="morsel-img " href="#" style="background-image: url(http://l7connect.com/wp-content/uploads/2015/06/no-preview.png);"></a>\
               //            <img class="spacer loader " src="'+"<?php echo MORSEL_PLUGIN_IMG_PATH.'spacer.png'?>"+'">\
               //            </div>\
               //            <div class="checkbox text-center"><label style="color: red;font-weight: bold;">'+value.name+'</label></div>';
               // blocks += '</div>';
              }

            }); //end itration of results
          $("#user-prefrence-blocks").append(blocks);
          }
        },error:function(){},
        complete:function(){
          waitingDialog.hide();
        }
      }); //end ajax functionality
      //End get all keywords with example of host user

      //click functionlaity of keyword-unsubscribe btn
      $("#keyword-unsubscribe").click(function(event){
        event.preventDefault();
        jQuery("input[name='keyword_id[]']").val();
        var selected_ids = new Array();
        $.each(jQuery("input[name='keyword_id[]']:not(:checked)"), function() {
          selected_ids.push($(this).val());
        });
        console.log('tttttttt',selected_ids);
        //unsubscribe the keywords
        jQuery.ajax({
            url:"<?php echo MORSEL_API_USER_URL.$user->id;?>"+'/unsubscribe_users_keyword.json',
            type:"DELETE",
            async:false,
            data:{
              user: {keyword_id:selected_ids},
              api_key : "<?php echo $api_key;?>"
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
              }
            },error:function(){},
            complete:function(){
              waitingDialog.hide();
            }
        });
      });

      $("#all-keyword-select").click(function(event){
        if($(this).hasClass("select")){
          $("input[name='keyword_id[]']").prop('checked',"checked");
          $(this).removeClass("select");
          $(this).text("Clear All");
        } else {
          $("input[name='keyword_id[]']").prop('checked',"");
          $(this).addClass("select");
          $(this).text("Select All");
        }
      });
    });
  </script>
<?php
  }?>
</div><!--End div #user-prefrence-wrapper -->
<?php
}
add_shortcode('morsel_user_preference', 'morsel_user_preference');
?>
