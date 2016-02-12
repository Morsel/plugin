<?php
/*1] call POST morsels.json  with   Object { template_id=1}
2] call POST item.json with Object { morsel_id=1808,  template_order=99,  sort_order=1}
3] call PUT /morsels/1808.json with   Object { primary_item_id=3537}
4] call GET /morsels/1808.json?api_key=421:vCw4-xiAtHYjzwAxZUUz
5] call GET /authentications.json?api_key=421:vCw4-xiAtHYjzwAxZUUz
6] call https://morsel.s3.amazonaws.com/morsel-templates/data/add-morsel-templates.json?api_key=421:vCw4-xiAtHYjzwAxZUUz&client%5Bdevice%5D=web&client%5Bversion%5D=1.4.10
7] */

/**
 * main function for create morsel shortcode
 */
function create_morsel() {
  ob_start();
  //load scripts and styles
  add_create_morsel_scripts();
?>

  <style type="text/css">
  ::-webkit-input-placeholder { color: inherit; }
:-moz-placeholder { /* Firefox 18- */
  color: #d8d8d8 !important;
}

::-moz-placeholder { /* Firefox 19+ */
  color: #d8d8d8 !important;
}

:-ms-input-placeholder { /* IE 10+ */
  color: #d8d8d8 !important;
}

::-ms-input-placeholder { /* Edge */
  color: #d8d8d8 !important;
}

:placeholder-shown { /* Standard one last! */
  color: #d8d8d8 !important;
}
.col-md-6.col-sm-6.item-description.mrsl-item-part > form {
  text-align: center;
}
/*.lastRowMorsel{
  margin-top: 40px;
}*/
.headingItem{
  margin-bottom: 20px !important;
}
  </style>
  <!-- edit Morsel Start -->
  <input type= "hidden" id="new_mrsl_id"/>
  <input type= "hidden" id="is_morsel_submit"/>
  <input type= "hidden" id="is_morsel_title_saved"/>
  <div class="editMorsels morsel-iso bootstrap-iso">
    <div class="container-fluid">
<?php
/*checked eatmorsel user isn't logged in*/
if((!isset($_SESSION['morsel_login_userid'])) && (!isset($_SESSION['morsel_user_obj']))){
  // echo $options["hide_login_btn"];
  if(get_option('morsel_other_settings')['hide_login_btn'] != 1) {
    ?>
    <div class='row'>
      <div class='col-md-6'>You must be sign/login before create a morsel, click here for <a id='open-morsel-login1' class='open-morsel-login btn btn-danger btn-xs'>SignUp/Login</a></div>
    </div>
<?php
  } //end login hide
    } else { //user is logged in
    //get host admin info
    $host_info = get_option('morsel_settings');
    if($host_info){
      $host_id = $host_info["userid"];
    }
    $user = $_SESSION['morsel_user_obj'];

    $full_name = ucfirst($user->first_name).' '.ucfirst($user->last_name);
    $user_id = $user->id;
    $token = $user->auth_token;
    $api_key = $user_id.':'.$token;
?>
    <div class='row'>
      <div class="col-sm-12" style="text-align:right;">
        <span>Logged in with : <b><?php echo $full_name ?></b></span>
        <!-- <a href="<?php echo site_url()?>/index.php?pagename=morsel_logout" class="label label-danger">Logout</a> -->
      </div>
        <div class="form-table MorselEdit col-md-12 col-sm-12">
          <div class='row'>
              <div class="morsel-title-area col-md-12 col-sm-12 mrsl-top-border">
                <div class="form-group">
                  <label for="morselTitle">Title:</label>
                  <input type="hidden" name="morselId" id="morselId" value=""/>
                  <input type="text" class="form-control" name="morselTitle" id="morselTitle" value=""/>
                  <span id="morselTitleSpan" style="display:none; font-size:16px;">html</span>
                </div>
                <button id="post_title_editbutton" class="btn btn-success  morselSave" style="display:none;">Edit</button>
                <button id="post_title_savebtn" class="btn btn-success  morselSave" onclick="saveMorsel('morselTitle','title',this)">Save</button>
                <img style="display:none;" class="smallAjaxLoader" src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif">
              </div>
              <div class="morsel-item-list-area col-md-12 col-sm-12 mrsl-top-border">
                <h3>Items:</h3>
                <!-- <div style="float:right;"></div> -->
                <div class="wp-list-table widefat posts addItemTable row">
                  <div class="mrsl-top-border headingItem">
                    <div class="manage-column column-date col-md-4 col-sm-4 text-center" scope="col"><b>Item Image</b></div>
                    <div class="manage-column item-description sortable desc col-md-6 col-sm-6 text-center" scope="col"><b>Item Description</b></div>
                    <div class="manage-column column-title sortable asc col-md-2 col-sm-2 text-center" scope="col"><b>Action</b></div>
                  </div>
                  <div id="items-body" class="container-fluid"></div>
                </div>
              </div> <!-- End div.morsel-item-list-area col-md-12 col-sm-12 -->
              <div class="morsel-action-btn-area col-md-12 col-sm-12 mrsl-top-border" style="margin:5px 5px 5px -5px !important;">
                <a class="btn btn-default" onclick="addItemMorsel();"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp;Add Item</a>
                <img class="center-block clearfix" src="<?php echo MORSEL_PLUGIN_WIDGET_ASSEST;?>/images/loader.gif" id="smallAjaxLoaderAddItem" style="display:none;"/>
              </div>
        </div> <!-- End div.row -->
      </div> <!-- End div.form-table MorselEdit -->
    </div>
    <div class="row lastRowMorsel">
      <div class="paddingNone" style="float: left;">
        <a class="btn btn-info" onclick="openDialog();"><i class="glyphicon glyphicon-share"></i>&nbsp;Connect to social</a>
      </div>
      <div class="paddingNone" style="float: right;">
        <a id="submit-mrsl-btn" class="btn btn-danger" onclick="submitMorsel()">Submit Story</a>
        <a id="create-mrsl-btn" class="btn btn-default" style="display:none;" onclick="createNewMorsel()">Create New Story</a>
      </div>
    </div>
   </div> <!-- End div.container-fluid -->

  <div class="modal fade" id="morselSocialId" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
     <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Connect to social</h4>
        </div>
        <div class="modal-body">
          <iframe src="" width="100%" height="100%" style="border: 0px;"></iframe>
        </div>
       <!--  <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
    </div>
  </div>
</div> <!-- End editMorsels morsel-iso bootstrap-iso -->

<style type="text/css">
  .itemContentImage{
    padding: 2px !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
  }
  .spanDescription iframe { display: none;}
</style>
<script type="text/javascript">
  var morselGlobal= jQuery('#new_mrsl_id').val();
  /**
   * main function that call preliminary apis for create morsel shortcode
   */
  function mrsl_call_pre_api(){
    var preliminary_data = [];
    var error = [];
    var api_key = "<?php echo $api_key;?>";
    var new_born_morsel;
    var new_born_item;
    //preliminary_data["new_born_morsel"] = mrsl_call_api("POST",MORSEL_API_MORSELS_URL."?api_key=".$api_key,array("template_id"=>"1"));

    //genrate new morsel
    jQuery.ajax({
        url:"<?php echo MORSEL_API_MORSELS_URL;?>",
        type:"POST",
        async:false,
        data:{
          api_key : "<?php echo $api_key;?>" ,
          template_id : "1"
        },
        beforeSend: function(xhr) {
          console.log("mrsl_call_pre_api start");
          waitingDialog.show('Please wait while loading...');
        },
        success: function(response) {
          console.log("new_born_morsel response: ",response);
          if(response.meta.status == "200" && response.meta.message == "OK"){

            new_born_morsel = response.data;
            morselGlobal = new_born_morsel.id;
            jQuery('#new_mrsl_id').val(morselGlobal);
            //genrate new item
            new_born_item = new addItemMorsel("1",true);
            console.log("new_born_item: ",new_born_item);
          }
        },error:function(){/*waitingDialog.hide();*/},
        complete:function(){
          //console.log("mrsl_call_pre_api complete");
          waitingDialog.hide();
        }
    });
  }//function end call_preliminary_api

  function mrsl_call_get_api() {
     console.log("morsel-call-get-api");
     var morsel_id = jQuery('#new_mrsl_id').val();
    jQuery.ajax({
        url:"<?php echo MORSEL_API_MORSELS_URL;?>"+morsel_id+".json",
        type:"GET",
        async:false,
        data:{
          api_key : "<?php echo $api_key;?>"
        },
        beforeSend: function(xhr) {
          console.log("mrsl_call_get_api start");
          waitingDialog.show('Please wait while loading...');
        },
        success: function(response) {
          console.log("old_morsel response: ",response);

          if(response.meta.status == "200" && response.meta.message == "OK"){

            if(response.data.title!=""){
              jQuery('#submit-mrsl-btn').removeAttr('disabled');

              jQuery('#morselTitle').css("display","none");
              jQuery('#morselTitleSpan').css("display","block");
              jQuery('#morselTitle').val(response.data.title);
              jQuery('#morselTitleSpan').html(response.data.title);
              jQuery("#post_title_savebtn").css("display","none");
              jQuery("#post_title_editbutton").css("display","block");
            }
           var new_item;
           jQuery.each(response.data.items, function( index, new_item ) {
            var html = "";
            html += '<div class="row itemMorsel'+new_item.id+' mrsl-top-border itemContentImage"><div class="col-md-4 col-sm-4 column-date text-center mrsl-item-part">';
            //noImageMorsel.png
            if(new_item.photos){
            html += '<img id="item-thumb-'+new_item.id+'" onClick="uploadMorselItemImage('+new_item.id+')" src = "'+new_item.photos._100x100+'" width="100"/>';
            }else{
            html += '<img id="item-thumb-'+new_item.id+'" onClick="uploadMorselItemImage('+new_item.id+')" src = "<?php echo MORSEL_PLUGIN_IMG_PATH;?>noImageMorsel.png" width="100"/>';
            }
            html += '<input type="file" onchange="saveUploadImageItem('+new_item.id+')" id="imageUpload'+new_item.id+'" style="display:none;"><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderItemImage'+new_item.id+'" style="display:none;margin:0 auto;"/>';
            var des = (new_item.description == null)?'<i>Please enter some description about item.</i>':new_item.description;
            var desText = (new_item.description == null)?'':new_item.description;

            html += '</div><div class="col-md-6 col-sm-6 item-description mrsl-item-part">\
            <form action="submit.php" id="form'+new_item.id+'" onsubmit="saveItemDes('+new_item.id+');return false;">\
            <span id="span'+new_item.id+'" style="word-wrap:break-word"><i>'+des+'</i></span>\
            <textarea id="textareaItem'+new_item.id+'" name="nameTextareaItem'+new_item.id+'" class="widgEditor nothing editor" style="width:100%; display:none;">'+desText+'</textarea>\
            </form></div>\
            <div class="col-md-2 col-sm-2 column-title text-center mrsl-item-part">\
            <a class="btn btn-success" id="saveButton'+new_item.id+'" style="display:none;" onClick = "formSubmitItem('+new_item.id+')"><i class="glyphicon glyphicon-ok"></i></a>\
            <a class="btn btn-default" onClick = "editMorselItem('+new_item.id+')"  id="editButton'+new_item.id+'">\
            <i class="glyphicon glyphicon-pencil"></i></a>&nbsp;&nbsp;\
            <a onClick = "deleteMorselItem('+new_item.id+')" class ="btn btn-danger">\
            <i class="glyphicon glyphicon-trash"></i></a></div></form></div>';
            jQuery("#items-body").append(html);
            });
          }
        },error:function(){/*waitingDialog.hide();*/},
        complete:function(response){
          console.log("mrsl_call_get_api complete");
          waitingDialog.hide();
        }
    });

  }
  function createNewMorsel()
  {
    jQuery('#new_mrsl_id').val('');
    jQuery('#is_morsel_submit').val('');
    window.location.reload();
  }

  function openDialog()
  {

    var frameSrc = '<?php echo MORSEL_SITE ?>auth/loginifrm?id=<?php echo $user_id ?>&token=<?php echo $token ?>&page=iframe&callback='+window.location.href;
    console.log(frameSrc);
    window.location.href = frameSrc;
    // jQuery("#morselSocialId").find('iframe').attr('src',frameSrc);
    // jQuery('#morselSocialId').find('.modal-body').css('height','450px');
    // jQuery("#morselSocialId").modal('show');

    //showDialog(frameSrc);
  }


  jQuery(function($){
    //call mrsl_call_pre_api method
    var morsel_exist = jQuery('#new_mrsl_id').val();
    var is_morsel_submit = jQuery('#is_morsel_submit').val();
    if(is_morsel_submit){
        jQuery("#submit-mrsl-btn").css("pointer-events","none");
        jQuery("#submit-mrsl-btn").text("Already Submitted");
        jQuery('#create-mrsl-btn').show();
    }else{

    }
    if(morsel_exist){
      mrsl_call_get_api();
    }else{
       mrsl_call_pre_api();
    }


  });


  //function that the current submit morsel
  function submitMorsel(){

    var hostid  = "<?php echo isset($host_id)? $host_id : '';?>";

    var img_src = jQuery('#items-body').find('img').map(function() {
              return this.src.indexOf("noImageMorsel") > -1;}).get()
    var in_arry =  jQuery.inArray(true, img_src) ;
    if(in_arry != -1 ){alert("Please upload item's image first."); return; }
    if(morselGlobal && (hostid != '') ){
      //put morsel is_submit=true
      jQuery.ajax({
          url : "<?php echo MORSEL_API_MORSELS_URL;?>"+morselGlobal+'.json',
          type : "PUT",
          beforeSend: function(xhr) {
            console.log("submitMorsel start");
            waitingDialog.show('Please wait, we are processing your request...');
          },
          data : {
            api_key : "<?php echo $api_key;?>" ,
            morsel: { is_submit : true, summary : "" }
          },
          success: function(response) {
            if(response.meta.status == "200" && response.meta.message == "OK"){
              //make submit morsel to host site
              jQuery.ajax({
                  url : "<?php echo MORSEL_API_MORSELS_URL,'associate_morsel_to_user.json';?>",
                  type : "POST",
                  data : {
                    api_key : "<?php echo $api_key;?>" ,
                    morsel_id : morselGlobal,
                    morsel : { morsel_host_ids : [hostid] }
                  },
                  success: function(data) {
                    if(data.meta.status == "200" && data.meta.message == "OK"){
                      alert("Your morsel is successfully submitted.");
                      jQuery("#submit-mrsl-btn").css("pointer-events","none");
                      jQuery("#submit-mrsl-btn").text("Already Submitted");
                      jQuery('#create-mrsl-btn').show();
                      jQuery('#is_morsel_submit').val(true);
                    }
                  },
                  error:function(){
                    alert("Your morsel isn't successfully submitted.");
                  },
                  complete:function(){
                  }
              });
              //End make submit morsel to host site
            }
          },
          error:function(){
            console.log("Your morsel isn't successfully submitted when set is_submit to true.");
          },
          complete:function(){
            console.log("submitMorsel complete");
            waitingDialog.hide();
          }
      });



    } else {
      alert("Opps either host information isn't getting or morsel is not created properly!")
    }
  }

  // set a item to primary item of morsel
  function setPrimaryItem(morsel_id,item_id){
    var result = false;
    //genrate new morsel
    jQuery.ajax({
        url:"<?php echo MORSEL_API_MORSELS_URL;?>"+morsel_id+".json",
        type:"PUT",
        data:{
          api_key : "<?php echo $api_key;?>" ,
          morsel: {primary_item_id : item_id}
        },
        beforeSend: function(xhr) {
          //waitingDialog.show('Please wait while loading...');
        },
        success: function(response) {
          if(response.meta.status == "200" && response.meta.message == "OK"){
            result = true;
            editMorsel(morsel_id);
            console.log("edit morsel end in setPrimaryItem");
            editMorselItem(item_id);
          }
        },error:function(){},
        complete:function(){
          //jQuery("#smallAjaxLoaderAddItem").css("display","none");
        }
    });
    return result;
  }

  function editMorsel(morselid){
      console.log("editMorsel---------------",morselid);
      morselGlobal = morselid;
      jQuery("#morselId").val(morselid);

      jQuery.ajax({
        url:"<?php echo MORSEL_API_URL;?>"+"morsels/"+morselid,
        type:"GET",
        async:false,
        data:{
          api_key:"<?php echo $api_key;?>"
        },
        beforeSend: function(xhr) {
          console.log("editMorsel start");
          waitingDialog.show('Please wait while loading...');
        },
        success: function(response) {

          console.log("morsel_response------------",response);

          var data = response.data;
          var items = data.items;
          //title
          jQuery("#morselTitle").val(data.title);
          jQuery("#morselTitleSpan").html(data.title);
          jQuery('#morselTitle').css("display","none");
          jQuery('#morselTitleSpan').css("display","block");
          jQuery("#post_title_savebtn").css("display","none");
          jQuery("#post_title_editbutton").css("display","block");

          jQuery("#morsel_description_text").val(data.summary);
          jQuery("#items-body").html("");

          for(var i in items){

            var html = '<div class="row itemMorsel'+items[i].id+' mrsl-top-border itemContentImage"><div class="col-md-4 col-sm-4 column-date text-center mrsl-item-part">';
            if(items[i].photos != null && items[i].photos._100x100 != undefined && items[i].photos._100x100 != null && items[i].photos._100x100 != ''){
                html += '<img id="item-thumb-'+items[i].id+'" onClick="uploadMorselItemImage('+items[i].id+')" src = "'+items[i].photos._100x100+'"/>';
            } else {
              //noImageMorsel.png
              html += '<img id="item-thumb-'+items[i].id+'" onClick="uploadMorselItemImage('+items[i].id+')" src = "<?php echo MORSEL_PLUGIN_IMG_PATH;?>noImageMorsel.png" width="100"/>';
            }
            html += '<input type="file" onchange="saveUploadImageItem('+items[i].id+')" id="imageUpload'+items[i].id+'" style="display:none;"><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderItemImage'+items[i].id+'" style="display:none;margin:0 auto;"/>';
            var des = ((items[i].description == null) || (items[i].description == "") )?'<i>Please enter some description about item.</i>':items[i].description;
            var desText = (items[i].description == null)?'':items[i].description;
            html += '</div><div class="col-md-6 col-sm-6 item-description mrsl-item-part"><form action="submit.php" id="form'+items[i].id+'" onsubmit="saveItemDes('+items[i].id+');return false;"><span class="spanDescription" id="span'+items[i].id+'" style="word-wrap:break-word">'+des+'</span><textarea id="textareaItem'+items[i].id+'" name="nameTextareaItem'+items[i].id+'" class="widgEditor nothing editor" style="width:100%; display:none;">'+desText+'</textarea></form></div><div class="col-md-2 col-sm-2 column-title text-center mrsl-item-part"><a class="btn btn-success" id="saveButton'+items[i].id+'" style="display:none;" onClick = "formSubmitItem('+items[i].id+')"><i class="glyphicon glyphicon-ok"></i></a><a class="btn  btn-default" onClick = "editMorselItem('+items[i].id+')"  id="editButton'+items[i].id+'"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;&nbsp;<a onClick = "deleteMorselItem('+items[i].id+')" class ="btn btn-danger " ><i class="glyphicon glyphicon-trash"></i></a></div></form></div>';
            jQuery("#items-body").append(html);
          }
        },error:function(){},
        complete:function(response){
          console.log("editMorsel complete");
          waitingDialog.hide();
        }
      });
  }

  function formSubmitItem(formID){
     jQuery("#form"+formID).submit();
  }
  jQuery("#post_title_editbutton").click(function(){
    jQuery('#morselTitleSpan').css("display","none");
    jQuery('#morselTitle').css("display","block");
    jQuery("#post_title_editbutton").css("display","none");
    jQuery("#post_title_savebtn").css("display","block");
  })

  var acceptedExt = ["jpg","JPG","png","PNG","jpeg","JPEG","gif","GIF"];

  function saveUploadImageItem(itemID){
      var fileObject = {};
      fileObject = document.getElementById("imageUpload"+itemID).files[0];
      var fileName = fileObject.name,
      ext = fileName.split(".")[fileName.split(".").length - 1];
      if(jQuery.inArray(ext, acceptedExt) >= 0){
        var _URL = window.URL || window.webkitURL;
          if (fileObject) {
            var img = new Image();
            img.onload = function () {
                if(this.width <= 1200 && this.height <= 800){
                  alert("Image resolution should be 1200x800");
                  return;
                } else {
                  jQuery("#smallAjaxLoaderItemImage"+itemID).css("display","block");
              var fd = new FormData();
              fd.append("item[photo]", fileObject);
              jQuery.ajax({
                url:"<?php echo MORSEL_API_URL;?>"+"items/"+itemID+".json?api_key=<?php echo $api_key;?>&prepare_presigned_upload=true",
                data: fd,
                type: 'PUT',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(xhr) {},
                complete: function() {},
                success: function(response) {
                  console.log('test response', response);
                  setTimeout(function() {
                    checkItemPhoto(itemID);
                  }, 8000);
                },
                error: function(response) {},
                complete: function(){}
              });
                }
            };
            img.src = _URL.createObjectURL(fileObject);
        }
      } else {
        alert("Please upload valid image, image extension must be jpg, JPG, png, PNG, jpeg,JPEG, gif, GIF");
        return false;
      }
  }


  function uploadMorselItemImage(itemID){
    jQuery("#imageUpload"+itemID).click();
  }

  // Regular function with arguments
  function checkItemPhoto(itemID) {
    //call after 2 second
    setTimeout(function() {
      jQuery.ajax({
        url : "<?php echo MORSEL_API_ITEMS_URL;?>"+itemID+'.json',
        type:"GET",
        async: false,
        data:{
          api_key : "<?php echo $api_key;?>",
        },
        success: function(response) {
          console.log("checkItemPhoto function Item Get------------",response);
          if(response.meta.status == "200" && response.meta.message == "OK"){
            if(response.data.photos == null || response.data.photos._100x100 == undefined ||response.data.photos._100x100 == ''){
              checkItemPhoto(itemID);
            } else {
              console.log("Get item photo");
              jQuery("#item-thumb-"+itemID).attr("src",response.data.photos._100x100);
              jQuery('#item-thumb-'+itemID).css("display",'initial');
              jQuery("#smallAjaxLoaderItemImage"+itemID).css("display","none");

            }
          }
        },error:function(){console.log("Error occure in checkItemPhoto function");},
        complete:function(){}
      });
    },2000);
  }

  jQuery(".add_ItemMorsel").click(function(event) {
    jQuery(this).parents('.form-table').remove();
  });
  jQuery("#post_title").click(function(event){
    jQuery('#post_title_text').val(jQuery('#post_title').text());
    jQuery('#post_title_div').show();
    jQuery('#post_title').hide();
  });
  jQuery("#post_summary").click(function(event){
    jQuery('#post_description_text').val(jQuery('#post_summary').text());
    jQuery('#post_description_div').show();
    jQuery('#post_summary').hide();
  });
  jQuery("#post_title_cancelbtn").click(function(event){
    jQuery('#post_title_div').hide();
    jQuery('#post_title').show();
  });
  jQuery("#post_description_cancelbtn").click(function(event){
    jQuery('#post_description_div').hide();
    jQuery('#post_summary').show();
  });

  function saveMorsel(fieldId,fieldName,element){
    console.log("this element",element);
    if(fieldName == "summary"){
      data = {
        "api_key" : "<?php echo $api_key;?>",
        "summary" : jQuery("#"+fieldId).val()
      }
    } else {
      data = {
        "api_key" : "<?php echo $api_key;?>",
        "morsel" : { "title" : jQuery("#"+fieldId).val() }
      }
    }
    var morsel_title = jQuery("#morselTitle").val();
    if(morsel_title==""){
      alert("Please enter title first");
      jQuery('#submit-mrsl-btn').attr("disabled");
      return;
    }
    jQuery.ajax({
      url:"<?php echo MORSEL_API_URL;?>"+"morsels/"+morselGlobal,
      type:"PUT",
      data:data,
      beforeSend: function(response) {
        jQuery("#"+element.id).next("img.smallAjaxLoader").show();
      },
      success: function(response) {
        console.log("morsel_response add------------",response);
        jQuery('#submit-mrsl-btn').removeAttr("disabled");
      },error:function(){
        jQuery("#"+element.id).next("img.smallAjaxLoader").hide();
      },complete:function(){
        jQuery("#"+element.id).next("img.smallAjaxLoader").hide();
        jQuery("#morselTitleSpan").html(morsel_title);
        jQuery("#morselTitle").css("display","none");
        jQuery("#morselTitleSpan").css("display","block");
        jQuery("#post_title_savebtn").css("display","none");
        jQuery("#post_title_editbutton").css("display","block");
      }
    });
  }

  function addItemMorsel(sort_order, make_item_primary){
    if (typeof(make_item_primary) === "undefined") { make_item_primary = false; }

    if(typeof(sort_order) === "undefined"){
      userData = { api_key : "<?php echo $api_key;?>" ,
        "item":{"morsel_id": morselGlobal},
        "template_order": 99,
        "sort_order": 0
        };
    }else{
      userData = { api_key : "<?php echo $api_key;?>" ,
        "item":{"morsel_id": morselGlobal},
        "template_order": 99,
        "sort_order": sort_order };
    }
    var new_item;
    jQuery.ajax({
      url:"<?php echo MORSEL_API_URL;?>"+"items.json",
      type:"POST",
      beforeSend: function(response) {
         jQuery("#smallAjaxLoaderAddItem").css("display","block");
         console.log("new item added.start")
      },
      data:userData,
      success: function(response) {
        if(response.meta.status == "200" && response.meta.message == "OK"){
          new_item  = response.data;
          if(make_item_primary){
            setPrimaryItem(morselGlobal,new_item.id);
          } else {
            //means its not first time so we directly show in item list of morsel
            var html = '<div class="row itemMorsel'+new_item.id+' mrsl-top-border itemContentImage"><div class="col-md-4 col-sm-4 column-date text-center mrsl-item-part">';
            html += '<img id="item-thumb-'+new_item.id+'" onClick="uploadMorselItemImage('+new_item.id+')" src = "<?php echo MORSEL_PLUGIN_IMG_PATH;?>noImageMorsel.png" width="100"/>';
            html += '<input type="file" onchange="saveUploadImageItem('+new_item.id+')" id="imageUpload'+new_item.id+'" style="display:none;"><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderItemImage'+new_item.id+'" style="display:none;margin:0 auto;"/>';
            var des = (new_item.description == null)?'<i>Please enter some description about item.</i>':new_item.description;
            var desText = (new_item.description == null)?'':new_item.description;
            html += '</div><div class="col-md-6 col-sm-6 item-description mrsl-item-part"><form action="submit.php" id="form'+new_item.id+'" onsubmit="saveItemDes('+new_item.id+');return false;"><span id="span'+new_item.id+'" style="word-wrap:break-word"><i>Please enter some description about item.</i></span><textarea id="textareaItem'+new_item.id+'" name="nameTextareaItem'+new_item.id+'" class="widgEditor nothing editor" style="width:100%; display:none;">'+desText+'</textarea></form></div><div class="col-md-2 col-sm-2 column-title text-center mrsl-item-part"><a class="btn btn-success" id="saveButton'+new_item.id+'" style="display:none;" onClick = "formSubmitItem('+new_item.id+')"><i class="glyphicon glyphicon-ok"></i></a><a class="btn btn-default" onClick = "editMorselItem('+new_item.id+')"  id="editButton'+new_item.id+'"><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;&nbsp;<a onClick = "deleteMorselItem('+new_item.id+')" class ="btn btn-danger" ><i class="glyphicon glyphicon-trash"></i></a></div></form></div>';
            jQuery("#items-body").append(html);
          }
        }
      },error:function(){
        console.log("Error: some error appears in add item")
      },
      complete:function(){
        jQuery("#smallAjaxLoaderAddItem").css("display","none");
        console.log("new item added. complete")
      }
    });
    return new_item;
  }

  //initlize ck editor on an element with id
  function initEditor(id){
    jQuery(id).ckeditor(function() { /* callback code */ },
      { toolbar : [
          { name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat','Source' ] },
          { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
          { name: 'styles', items : [ 'Styles','Format' ] }
        ],
        allowedContent: 'iframe[*]'
      });
  }

  //hide ck editor via element id
  //Note :: do not give id with #
  function destroyEditor(id){
    var result = typeof CKEDITOR.instances[id] != 'undefined';
    console.log("check condition : ",result);
    if(typeof CKEDITOR.instances[id] != 'undefined') {
      CKEDITOR.instances[id].updateElement();
      CKEDITOR.instances[id].destroy();
      jQuery("#"+id).hide();
    }
  }

  // function that save item description
  function saveItemDes(itemID) {
    console.log("check-------------11");
    old_value = jQuery('#span'+itemID).text();
    new_value = jQuery("#textareaItem"+itemID).val();
    new_value = jQuery(new_value).text();

        /*For getting Iframe*/
        var IFRAME = jQuery("#textareaItem"+itemID).val();
          if (IFRAME.indexOf('iframe') > -1){
              iframe_txt = IFRAME.match(/(iframe.+?\/iframe)/g)[0];
              var regex = /iframe.*?src=&quot;(.*?)&quot;/;
              if(regex.exec(iframe_txt) == undefined || regex.exec(iframe_txt)==null){
                var regex = /iframe.*?src="(.*?)"/;
              }
              var src = regex.exec(iframe_txt)[1];
              var data_id = src.split("/").pop();
              if(src.indexOf('youtube') > -1){
                img_src ="http://img.youtube.com/vi/"+data_id+"/hqdefault.jpg";
                callForGetFileObject(img_src,itemID);
              }else{
                jQuery.get("https://crossorigin.me/http://vimeo.com/api/v2/video/"+data_id+".json")
                  .then(function(response) {
                  img_src = (response[0].thumbnail_large).replace("_640","");
                  callForGetFileObject(img_src,itemID);
                });
              }
/*For getting Iframe*/
          } else {
    if (old_value.indexOf(new_value) > -1)
    {
        jQuery("#span"+itemID).html(jQuery("#textareaItem"+itemID).val());
        jQuery("#span"+itemID).css("display","block");
        jQuery("#saveButton"+itemID).css("display","none");
        jQuery("#editButton"+itemID).css("display","inline-block");
        destroyEditor("textareaItem"+itemID);
        jQuery("#smallAjaxLoaderAddItem").css("display","none");
        return;
    }
          jQuery.ajax({
            url:"<?php echo MORSEL_API_URL;?>items/"+itemID+".json",
            type:"PUT",
            data:{
              api_key : "<?php echo $api_key;?>",
              item : {"description": jQuery("#textareaItem"+itemID).val() }
            },
            beforeSend:function(){
              console.log("saveItemDes start");
              waitingDialog.show('Please wait while loading...');
            },
            success: function(response) {
              console.log("morsel_response Item Get------------",response);
              jQuery("#span"+itemID).html(jQuery("#textareaItem"+itemID).val());
              jQuery("#span"+itemID).css("display","block");
              jQuery("#saveButton"+itemID).css("display","none");
              jQuery("#editButton"+itemID).css("display","inline-block");
              destroyEditor("textareaItem"+itemID);
            },error:function(){},
            complete:function(){
              console.log("saveItemDes complete");
              waitingDialog.hide();
            }
      });
    }
  }

  function deleteMorselItem(itemID){
    jQuery.ajax({
      url:"<?php echo MORSEL_API_URL;?>items/"+itemID+".json",
      type:"DELETE",
      data:{ api_key : "<?php echo $api_key?>" },
      beforeSend:function(){
        console.log("deleteMorselItem start");
        waitingDialog.show('Please wait while loading...');
      },
      success: function(response) {
        console.log("morsel_response Item Get------------",response);
        jQuery( ".itemMorsel"+itemID).remove();
        jQuery("#smallAjaxLoaderAddItem").css("display","none");
      },error:function(){},
      complete:function(){
        console.log("deleteMorselItem complete");
        waitingDialog.hide();
      }
    });
  }

  //function that prepare for edit morsel
  function editMorselItem(itemID){
    console.log("editMorselItem called");
    jQuery("#span"+itemID).css("display","none");
    //add editor to text area
    initEditor("#textareaItem"+itemID);
    jQuery("#editButton"+itemID).css("display","none");
    jQuery("#saveButton"+itemID).css("display","inline-block");
  }

  jQuery(".add_ItemMorsel").click(function(event) {
    jQuery(this).parents('.form-table').remove();
  });

        var getFileBlob = function (url, cb) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", url);
            xhr.responseType = "blob";

            xhr.addEventListener('load', function() {
                cb(xhr.response);
            });
            xhr.send();
        };

        var blobToFile = function (blob, name) {
            blob.lastModifiedDate = new Date();
            blob.name = name;
            return blob;
        };

        var getFileObject = function(filePathOrUrl, cb) {
            getFileBlob(filePathOrUrl, function (blob) {
                blob.lastModifiedDate = new Date();
              var file = new File([blob], "maxresdefault.jpg",{ type: "image/jpeg" });
              cb(file);
            });
        };
        var callForGetFileObject = function(img_src,itemID){
        getFileObject("https://crossorigin.me/"+img_src, function (fileObject) {
        jQuery("#smallAjaxLoaderItemImage"+itemID).css("display","block");
        var fd = new FormData();
        fd.append("item[photo]", fileObject);
        jQuery.ajax({
            url:"<?php echo MORSEL_API_URL;?>"+"items/"+itemID+".json?api_key=<?php echo $api_key;?>&prepare_presigned_upload=true",
            data: fd,
            type: 'PUT',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(xhr) {},
            complete: function() {},
            success: function(response) {
              console.log('test response', response);
                    jQuery.ajax({
                url:"<?php echo MORSEL_API_URL;?>items/"+itemID+".json",
                type:"PUT",
                data:{ api_key : "<?php echo $api_key;?>" , item : {"description": jQuery("#textareaItem"+itemID).val() } },
                success: function(response) {
                  editMorsel(morselGlobal);
                  checkItemPhoto(itemID);
                      jQuery("#smallAjaxLoaderAddItem").css("display","none");
                },error:function(){},
                complete:function(){}
                  });
            },
            error: function(response) {},
            complete: function(){}
          });
            });
        }
</script>

<?php
  } //else end
  return ob_get_clean();
} //function end create_morsel
add_shortcode('create_morsel', 'create_morsel');

/**
 * add support files for shortcode
 */
function add_create_morsel_scripts() {
  if(!is_admin()){
    /*scroll css*/
    wp_enqueue_style("infinite-scroll", MORSEL_PLUGIN_ADMIN_ASSEST.'infinite-scroll/scroll.css');
    //add ck editor
    wp_enqueue_script('ck-editor-js', "http://cdn.ckeditor.com/4.5.5/standard-all/ckeditor.js", array('jquery'));
    //add jquery adpters ck editor
    wp_enqueue_script('ck-editor-jquery-js', "https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.4/adapters/jquery.js",array('jquery','ck-editor-js'));
  }
}
