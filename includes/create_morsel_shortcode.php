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
  //load scripts and styles
  add_create_morsel_scripts();
?>
  <!-- edit Morsel Start -->
  <div class="editMorsels morsel-iso bootstrap-iso">
    <div class="container-fluid">
    <!-- show error fo login -->
  <?php if(isset($_SESSION['morsel_error'])) { unset($_SESSION['morsel_error']);?>
      <div class="alert alert-danger text-center" role="alert">Sorry your userid/email or password not matched, please try again.</div>
    <?php }?>
    <?php if(isset($_SESSION['host_morsel_errors'])) {
            $errors = $_SESSION['host_morsel_errors'];
            unset($_SESSION['host_morsel_errors']);
            foreach($errors as $error) { ?>
              <div class="alert alert-danger text-center" role="alert"><?php echo $error;?></div>
    <?php   }
        }?>
    <!-- End show error fo login -->

<?php
/*checked eatmorsel user isn't logged in*/
if((!isset($_SESSION['morsel_login_userid'])) && (!isset($_SESSION['morsel_user_obj']))){
    ?>
    <div class='row'>
      <div class='col-md-6'>You must be sign/login before create a morsel, click here for <a data-toggle='modal' data-target='#morselLoginModal' id='open-morsel-login1' class='btn btn-danger btn-xs clickeventon'>SignUp/Login</a></div>
    </div>
<?php
    } else { //user is logged in
    //get host admin info
    $host_info = get_option('morsel_settings');
    if($host_info){
      $host_id = $host_info["userid"];
    }
    $user = $_SESSION['morsel_user_obj'];
    $api_key = $user->id.':'.$user->auth_token;
?>
    <div class='row'>
      <h3>New Morsel</h3>
        <div class="form-table MorselEdit col-md-12 col-sm-12">
          <div class='row'>
              <div class="morsel-title-area col-md-12 col-sm-12 mrsl-top-border">
                <div class="form-group">
                  <label for="morselTitle">Title:</label>
                  <input type="hidden" name="morselId" id="morselId" value=""/>
                  <input type="text" class="form-control" name="morselTitle" id="morselTitle" value=""/>
                </div>
                <button id="post_title_savebtn" class=" btn btn-danger  morselSave" onclick="saveMorsel('morselTitle','title',this)">Save</button>
                <img style="display:none;" class="smallAjaxLoader" src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif">
              </div> <!-- End morsel-title-area col-md-12 col-sm-12 -->
              <div class="morsel-item-list-area col-md-12 col-sm-12 mrsl-top-border">
                <h3>Items:</h3>
                <!-- <div style="float:right;"></div> -->
                <div class="wp-list-table widefat posts addItemTable row">
                  <div class="mrsl-top-border">
                    <div class="manage-column column-date col-md-2 col-sm-2 text-center" scope="col">Item Image</div>
                    <div class="manage-column item-description sortable desc col-md-6 col-sm-6 text-center" scope="col">Item Description</div>
                    <div class="manage-column column-title sortable asc col-md-4 col-sm-4 text-center" scope="col">Action</div>
                  </div>
                  <div id="items-body" class="container-fluid"></div>
                </div>
              </div> <!-- End div.morsel-item-list-area col-md-12 col-sm-12 -->
              <div class="col-md-12 col-sm-12 mrsl-top-border">
                <a id="submit-mrsl-btn" class="btn btn-danger pull-left" onclick="submitMorsel()">Submit Morsel</a>
                <img class="center-block" src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderAddItem" style="display:none;"/>
                <a class="btn btn-danger pull-right" onclick="addItemMorsel();">Add Item</a>
              </div>
        </div> <!-- End div.row -->
      </div> <!-- End div.form-table MorselEdit -->
    </div>
  </div> <!-- End div.container-fluid -->
</div> <!-- End editMorsels morsel-iso bootstrap-iso -->
<script type="text/javascript">
  var morselGlobal;
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
          waitingDialog.show('Please wait while loading...');
        },
        success: function(response) {
          console.log("new_born_morsel response: ",response);
          if(response.meta.status == "200" && response.meta.message == "OK"){

            new_born_morsel = response.data;
            morselGlobal = new_born_morsel.id;

            //genrate new item
            new_born_item = new addItemMorsel("1",true);
            console.log("new_born_item: ",new_born_item);
          }
        },error:function(){waitingDialog.hide();},
        complete:function(){
          waitingDialog.hide();
        }
    });
  }//function end call_preliminary_api

  jQuery(function($){
    //call mrsl_call_pre_api method
    mrsl_call_pre_api();
  });

  //function that the current submit morsel
  function submitMorsel(){

    var hostid  = "<?php echo isset($host_id)? $host_id : '';?>";

    if(morselGlobal && (hostid != '') ){

      //put morsel is_submit=true
      jQuery.ajax({
          url : "<?php echo MORSEL_API_MORSELS_URL;?>"+morselGlobal+'.json',
          type : "PUT",
          beforeSend: function(xhr) {
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
            waitingDialog.hide();
          }
      });



    } else {
      alert("Opps either host information isn't getting or morsel ins't created properly!")
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
          waitingDialog.show('Please wait while loading...');
        },
        success: function(response) {

          console.log("morsel_response------------",response);

          var data = response.data;
          var items = data.items;
          //title
          jQuery("#morselTitle").val(data.title);
          jQuery("#morsel_description_text").val(data.summary);
          jQuery("#items-body").html("");
          for(var i in items){

            var html = '<div class="row itemMorsel'+items[i].id+' mrsl-top-border"><div class="col-md-2 col-sm-2 column-date text-center mrsl-item-part">';
            if(items[i].photos != null && items[i].photos._100x100 != undefined && items[i].photos._100x100 != null && items[i].photos._100x100 != ''){
                html += '<img id="item-thumb-'+items[i].id+'" onClick="uploadMorselItemImage('+items[i].id+')" src = "'+items[i].photos._100x100+'"/>';
            } else {
              //noImageMorsel.png
              html += '<img id="item-thumb-'+items[i].id+'" onClick="uploadMorselItemImage('+items[i].id+')" src = "<?php echo MORSEL_PLUGIN_IMG_PATH;?>noImageMorsel.png" width="100"/>';
            }
            html += '<input type="file" id="imageUpload'+items[i].id+'" style="display:none;"><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderItemImage'+items[i].id+'" style="display:none;"/>';

            var des = ((items[i].description == null) || (items[i].description == "") )?'<i>Please enter some description about item.</i>':items[i].description;
            var desText = (items[i].description == null)?'':items[i].description;

            console.log("des:",des);
            console.log("items[i].description:",items[i].description);

            html += '</div><div class="col-md-6 col-sm-6 item-description mrsl-item-part"><form action="submit.php" id="form'+items[i].id+'" onsubmit="saveItemDes('+items[i].id+');return false;"><span id="span'+items[i].id+'">'+des+'</span><textarea id="textareaItem'+items[i].id+'" name="nameTextareaItem'+items[i].id+'" class="widgEditor nothing editor" style="width:100%; display:none;">'+desText+'</textarea></form></div><div class="col-md-4 col-sm-4 column-title text-center mrsl-item-part"><a class="btn  btn-danger" id="saveButton'+items[i].id+'" style="display:none;" onClick = "formSubmitItem('+items[i].id+')">Save</a><a class="btn  btn-danger" onClick = "editMorselItem('+items[i].id+')"  id="editButton'+items[i].id+'">Edit Item</a>&nbsp;&nbsp;<a onClick = "deleteMorselItem('+items[i].id+')" class ="btn btn-danger " >Delete Item</a></div></form></div>';
            jQuery("#items-body").append(html);
          }
          //jQuery(".editMorsels").css("display","block");
        },error:function(){},
        complete:function(){
          waitingDialog.hide();
        }
      });
  }

  function formSubmitItem(formID){
     jQuery("#form"+formID).submit();
  }

  var acceptedExt = ["jpg","JPG","png","PNG","jpeg","JPEG","gif","GIF"];

  function uploadMorselItemImage(itemID){
    //alert("image Item");
      jQuery("#imageUpload"+itemID).click();
      jQuery("#imageUpload"+itemID).change(function(){
      var fileObject = {};
      fileObject = document.getElementById("imageUpload"+itemID).files[0];
      var fileName = fileObject.name,
      ext = fileName.split(".")[fileName.split(".").length - 1];
      if(jQuery.inArray(ext, acceptedExt) == 0){
        jQuery("#smallAjaxLoaderItemImage"+itemID).css("display","block");
        //submit the form here
        //event.preventDefault();
        var fd = new FormData();
        //fd.append("user[email]",jQuery( "#mrsl_user_email" ).val());
        if (fileObject) {
          fd.append("item[photo]", fileObject);
        }
        jQuery.ajax({
          url:"<?php echo MORSEL_API_URL;?>"+"items/"+itemID+".json?api_key=<?php echo $api_key;?>&prepare_presigned_upload=true",
          data: fd,
          type: 'PUT',
          contentType: false,
          cache: false,
          processData: false,
          beforeSend: function(xhr) {
            jQuery("#smallAjaxLoaderItemImage"+itemID).css("display","block");
          },
          complete: function() {},
          success: function(response) {
            console.log('test response', response);
            //alert("uploadMorselItemImage is success");
            setTimeout(function() {
              //called the click of save button for perticular item
              //jQuery("#saveButton"+itemID).click();
              //editMorsel(morselGlobal);
              checkItemPhoto(itemID);
            },2000);
          },
          error: function(response) {},
          complete: function(){
            /*setTimeout(function() {
              jQuery("#smallAjaxLoaderItemImage"+itemID).css("display","none");
              },7000);*/
          }
        });
      } else {
        alert("Please upload valid image, image extension must be jpg, JPG, png, PNG, jpeg,JPEG, gif, GIF");
        return false;
      }
    });
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
    jQuery.ajax({
      url:"<?php echo MORSEL_API_URL;?>"+"morsels/"+morselGlobal,
      type:"PUT",
      data:data,
      beforeSend: function(response) {
        jQuery("#"+element.id).next("img.smallAjaxLoader").show();
      },
      success: function(response) {
        console.log("morsel_response add------------",response);
      },error:function(){
        jQuery("#"+element.id).next("img.smallAjaxLoader").hide();
      },complete:function(){
        jQuery("#"+element.id).next("img.smallAjaxLoader").hide();
      }
    });
  }

  function addItemMorsel(sort_order, make_item_primary){
    if (typeof(sort_order) === "undefined") { sort_order = ""; }
    if (typeof(make_item_primary) === "undefined") { make_item_primary = false; }
    //jQuery("#smallAjaxLoaderAddItem").css("display","inline-block");
    var new_item;
    jQuery.ajax({
      url:"<?php echo MORSEL_API_URL;?>"+"items.json",
      type:"POST",
      beforeSend: function(response) {
         jQuery("#smallAjaxLoaderAddItem").css("display","block");
      },
      data:{
        api_key : "<?php echo $api_key;?>" ,
        "item":{"morsel_id": morselGlobal},
        "template_order": 99,
        "sort_order": sort_order
      },
      success: function(response) {
        if(response.meta.status == "200" && response.meta.message == "OK"){
          //editMorsel(morselGlobal);
          new_item  = response.data;
          if(make_item_primary){
            setPrimaryItem(morselGlobal,new_item.id);
          } else {
            //means its not first time so we directly show in item list of morsel
            var html = '<div class="row itemMorsel'+new_item.id+' mrsl-top-border"><div class="col-md-2 col-sm-2 column-date text-center mrsl-item-part">';
            //noImageMorsel.png
            html += '<img id="item-thumb-'+new_item.id+'" onClick="uploadMorselItemImage('+new_item.id+')" src = "<?php echo MORSEL_PLUGIN_IMG_PATH;?>noImageMorsel.png" width="100"/>';

            html += '<input type="file" id="imageUpload'+new_item.id+'" style="display:none;"><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderItemImage'+new_item.id+'" style="display:none;"/>';

            var des = (new_item.description == null)?'<i>Please enter some description about item.</i>':new_item.description;
            var desText = (new_item.description == null)?'':new_item.description;

            html += '</div><div class="col-md-6 col-sm-6 item-description mrsl-item-part"><form action="submit.php" id="form'+new_item.id+'" onsubmit="saveItemDes('+new_item.id+');return false;"><span id="span'+new_item.id+'"><i>Please enter some description about item.</i></span><textarea id="textareaItem'+new_item.id+'" name="nameTextareaItem'+new_item.id+'" class="widgEditor nothing editor" style="width:100%; display:none;">'+desText+'</textarea></form></div><div class="col-md-4 col-sm-4 column-title text-center mrsl-item-part"><a class="btn btn-danger " id="saveButton'+new_item.id+'" style="display:none;" onClick = "formSubmitItem('+new_item.id+')">Save</a><a class="btn btn-danger " onClick = "editMorselItem('+new_item.id+')"  id="editButton'+new_item.id+'">Edit Item</a>&nbsp;&nbsp;<a onClick = "deleteMorselItem('+new_item.id+')" class ="btn btn-danger " >Delete Item</a></div></form></div>';
            jQuery("#items-body").append(html);
          }
        }
      },error:function(){
        console.log("Error: some error appears in add item")
      },
      complete:function(){
        jQuery("#smallAjaxLoaderAddItem").css("display","none");
      }
    });
    return new_item;
  }

  //initlize ck editor on an element with id
  function initEditor(id){
    jQuery(id).ckeditor(function() { /* callback code */ },
                        { toolbar : [
                                      { name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
                                      { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
                                      { name: 'styles', items : [ 'Styles','Format' ] }
                                    ]
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
    jQuery("#smallAjaxLoaderAddItem").css("display","inline-block");
    jQuery.ajax({
      url:"<?php echo MORSEL_API_URL;?>items/"+itemID+".json",
      type:"PUT",
      data:{
        api_key : "<?php echo $api_key;?>",
        item : {"description": jQuery("#textareaItem"+itemID).val() }
      },
      success: function(response) {
        console.log("morsel_response Item Get------------",response);
        jQuery("#span"+itemID).html(jQuery("#textareaItem"+itemID).val());
        jQuery("#span"+itemID).css("display","block");
        jQuery("#saveButton"+itemID).css("display","none");
        jQuery("#editButton"+itemID).css("display","inline-block");

        destroyEditor("textareaItem"+itemID);
        //editMorsel(morselGlobal);
        jQuery("#smallAjaxLoaderAddItem").css("display","none");
      },error:function(){},
      complete:function(){}
    });
  }

  function deleteMorselItem(itemID){
    jQuery("#smallAjaxLoaderAddItem").css("display","inline-block");
    jQuery.ajax({
      url:"<?php echo MORSEL_API_URL;?>items/"+itemID+".json",
      type:"DELETE",
      data:{ api_key : "<?php echo $api_key?>" },
      success: function(response) {
        console.log("morsel_response Item Get------------",response);
        jQuery( ".itemMorsel"+itemID).remove();
        jQuery("#smallAjaxLoaderAddItem").css("display","none");
      },error:function(){},
      complete:function(){}
    });
  }

  //function that prepare for edit morsel
  function editMorselItem(itemID){
    console.log("editMorselItem called");
    jQuery("#span"+itemID).css("display","none");
    //jQuery("#textareaItem"+itemID+"WidgContainer").css("display","block");

    //add editor to text area
    initEditor("#textareaItem"+itemID);

    jQuery("#editButton"+itemID).css("display","none");
    jQuery("#saveButton"+itemID).css("display","inline-block");
  }

  jQuery(".add_ItemMorsel").click(function(event) {
      jQuery(this).parents('.form-table').remove();
  });
</script>

<?php
  } //else end
  //include authentication
  require_once(MORSEL_PLUGIN_URL_PATH. 'includes/authentication.php');
} //function end create_morsel
add_shortcode('create_morsel', 'create_morsel');

/**
 * add support files for shortcode
 */
//add_action('wp_enqueue_scripts', 'add_create_morsel_scripts');
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
