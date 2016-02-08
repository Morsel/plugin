<span class="editMorsels displayNone">
	<div style="background: none repeat scroll 0 0 #fff;padding: 10px;">
	  <h3>Edit Morsel</h3>
		<div style="width:100%">
			<table class="form-table MorselEdit">
		  		<tr valign="top">
		  			<td width="100%">
						<div>
							<div><b>Title:</b></div>
							<div class="clearMorsel"></div>
							<input type="hidden" style="width:50%" name="morselId" id="morselId" value=""/>
							<input type="text" style="width:50%" name="morselTitle" id="morselTitle" value=""/>
							<div class="clearMorsel"></div>
							<a id="post_title_savebtn" class="button button-primary morselSave" onclick="saveMorsel('morselTitle','title',this)">Save</a>
							<img class="smallAjaxLoader displayNone" src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif">
					    </div>
						 <div class="clearMorselWithMargin"></div>
					    <div>
						    <div class="addItemClass">
						    	<div style="float: left; width: 100px; margin: 10px 0 0 0;"><b>Items:</b></div>
						    	<div style="float:right;">
						    	   <!-- <img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderAddItem displayNone"/>
						    	   <a style=" margin-bottom: 5px;" class="button" onclick="addItemMorsel();">Add Item</a> -->
						    </div>
					        <table class="widefat addItemTable">
								<thead>
									<tr>
										<th style="" class="manage-column column-date" scope="col">Item Image</th>
										<th style="" class="manage-column item-description sortable desc" scope="col">Item Description</th>
										<th style="" class="manage-column column-title sortable asc" scope="col">Action</th>
							  	    </tr>
								</thead>
								<tbody id = "items-body"></tbody>
							</table>
	                    </div>
					    </div>
					     <div class="clearMorselWithMargin"></div>
					    <div>
							<div style="float:left;">
			                    <input id="" class="button button-primary" type="button" value="Save" name="morsel_update_form" onclick="morsel_back_key()">&nbsp;&nbsp;
					  		    <input id="" class="button button-primary" type="button" value="Cancel" name="morsel_update_form" onclick="morsel_back_key()">
				  		    </div>
				  		    <div style="float:right;">
							   <img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" style="display:none;" id="smallAjaxLoaderAddItem"/>&nbsp;
							   <a style=" margin-bottom: 5px;" class="button" onclick="addItemMorsel();">Add Item</a>
							</div>
					    </div>
					</td>
		  		</tr>
		  	</table>
		</div>
	</div>
</span>
<style type="text/css">
	/*.widgContainer{border: 1px solid; display: none;}*/
	/*.widgIframe { height: 100px !important;}*/
	/*.videoDesc iframe {width:100px; height: 100px;}*/
	.spanDescription iframe { display: none;}	
</style>
<script type="text/javascript">
    function editMorselItem(itemID){
		jQuery("#span"+itemID).css("display","none");
		initEditor("#textareaItem"+itemID);
		jQuery(".metaPreviewIput"+itemID).css("display","block");
		jQuery("#editButton"+itemID).css("display","none");
		jQuery("#saveButton"+itemID).css("display","inline-block");
	}
	
	// function editVideoItem(){
	// 	jQuery("#editMorselVideoButton").css("display","none");
	// 	jQuery("#saveMorselVideoButton").css("display","table-caption");
	// 	jQuery(".videoDesc").css("display","none");
	// 	jQuery(".videoIframeText").css("display","block");
	// 	jQuery("#video_textWidgContainer").css("display","block");
	// }

	function morsel_back_key(){
	    jQuery(".postJsonData").css("display","block");
        jQuery(".editMorsels").css("display","none");//ajaxLoaderSmall.gif
	}
    jQuery("#post_title").click(function(event){
		jQuery('#post_title_text').val(jQuery('#post_title').text());
		jQuery('#post_title_div').show();
		jQuery('#post_title').hide();
	});
	jQuery("#post_title_cancelbtn").click(function(event){
		jQuery('#post_title_div').hide();
		jQuery('#post_title').show();
	});

	// function previewMetaItem(itemID){
	// 	// alert("ItemID"+itemID);
	// 	tb_remove();
	// 	jQuery("#smallAjaxLoaderAddItem").css("display","inline-block");
	//     jQuery.ajax({
	// 		url:"<?php echo site_url()?>/index.php?pagename=morselMetaPreview&url="+jQuery("#metaUrl"+itemID).val(),
	// 		type:"get",
	// 		dataType: "json",
	// 		success: function(response) {
	// 			console.log("previewMetaItem-----------------",response);
	// 			jQuery("#metaPostHtml").html("");
	// 		    var html = '<div class="article_container">';
 //                if(response.image != ""){                         
	// 		        html += '<div class="metaarticle_image"><img class="img-responsive" src="'+response.image+'"><div>';
	// 		    }
	// 		    html += '<div class="metaarticle_inner"><div>'+response.title+'</div><div class="metaarticle_excerpt"><p>'+response.description+'</p></div></div></div>';
	// 		    jQuery("#metaPostHtml").append(html);
	// 		},error:function(){},
	// 		complete:function(){
	// 			var url = "#TB_inline?width=320&inlineId=modalWidowMetaPreview";
	// 		    tb_show("Item Meta Preview", url);
	// 			jQuery("#smallAjaxLoaderAddItem").css("display","none");
	// 		}
 //    	});	  	
	// }
	// function saveMetaItem(itemID){
    //  	alert("SaveMetaItem");
    // }
	
    var morselGlobal;
	function editMorsel(morselid){
      morselGlobal = morselid;
      jQuery("#morselId").val(morselid);
      jQuery("#smallAjaxLoader"+morselid).css("display","inline");
      jQuery.ajax({
				url:"<?php echo MORSEL_API_URL;?>"+"morsels/"+morselid,
				type:"GET",
				data:{
	    			api_key:"<?php echo $api_key;?>"
	  			},
				success: function(response) {
					var data = response.data;
					var items = data.items;
					jQuery("#morselTitle").val(data.title);
					jQuery("#morsel_description_text").val(data.summary);
					jQuery("#items-body").html("");
					// var morsel_video = (data.morsel_video != null)?data.morsel_video:'',
					//     video_text = (data.video_text != null)?data.video_text:'',
					//     editMorselButtonText = (data.morsel_video != null)? "Edit video item":"Add video item";
     //                var placeholder = "<iframe src='https://www.youtube.com/embed/-a8O5oJehTk?controls=0'>";
					// vhtml = '<tr><td class="column-date"><b>Video Item</b><br><span class="videoDesc">'+morsel_video+'</span><span style="display:none;" class="videoIframeText"><textarea id="morsel_video" style="width:270px; height:157px" name="morsel_video" placeholder="'+placeholder+'">'+morsel_video+'</textarea></span></td>';
					// vhtml +='<td class="item-description"><b>Video Text</b><br><span class="videoDesc">'+video_text+'</span><form action="submit.php" id="formvidoeTextForm" onsubmit="saveMorselVideo();return false;"><textarea class=" nothing editor displayNone" style="width:100%;" id="video_text" name="video_text" placeholder="Some text about video">'+video_text+'</textarea></td>';
					// vhtml +='<td class="item-description"><a class="button button-primary morselSave" onclick="editVideoItem();" id="editMorselVideoButton">'+editMorselButtonText+'</a><a id="saveMorselVideoButton" style="display:none" onClick="formVideoSubmit()" class="button button-primary morselSave" >Save</a></td></tr>';
					// jQuery("#items-body").append(vhtml);  
					for(var i in items){
		                html = '<tr class="itemMorsel'+items[i].id+'"><td class="column-date">';
		                if(items[i].photos != null && items[i].photos._100x100 != undefined && items[i].photos._100x100 != null && items[i].photos._100x100 != ''){
  		                  html += '<img id="item-thumb-'+items[i].id+'" onClick="uploadMorselItemImage('+items[i].id+')" src = "'+items[i].photos._100x100+'"/>';
		                } else {
		                	//noImageMorsel.png
		                  html += '<img id="item-thumb-'+items[i].id+'" onClick="uploadMorselItemImage('+items[i].id+')" src = "<?=MORSEL_PLUGIN_IMG_PATH;?>noImageMorsel.png" width="100"/>';
		                }
		                  html += '<input type="file" onchange="saveUploadImageItem('+items[i].id+')" id="imageUpload'+items[i].id+'" class="displayNone"><img src="<?=MORSEL_PLUGIN_IMG_PATH;?>ajaxLoaderSmall.gif" id="smallAjaxLoaderItemImage'+items[i].id+'" class="displayNone"/>';
		                  var des = (items[i].description == null)?'Null':items[i].description;
		                  var desText = (items[i].description == null)?'':items[i].description;

		                  html += '</td><td class="item-description"><form class="formItem" action="submit.php" id="form'+items[i].id+'" onsubmit="saveItemDes('+items[i].id+');return false;"><span class="spanDescription" id="span'+items[i].id+'">'+des+'</span><textarea id="textareaItem'+items[i].id+'" name="nameTextareaItem'+items[i].id+'" class="nothing editor displayNone" style="width:100%;">'+desText+'</textarea></form>';
                          html += '</td>';
                          html += '<td class="column-title"><a style="display:none;" class="button" id="saveButton'+items[i].id+'" onClick = "formSubmitItem('+items[i].id+')">Save</a><a class="button" onClick = "editMorselItem('+items[i].id+')"  id="editButton'+items[i].id+'">Edit Item</a>&nbsp;&nbsp;<a onClick = "deleteMorselItem('+items[i].id+')" class ="button button-primary" >Delete Item</a></td></form></tr>';
		            jQuery("#items-body").append(html);    
		            }
		            
					jQuery("#smallAjaxLoader"+morselid).css("display","none");
					jQuery(".editMorsels").css("display","block");
				},error:function(){},
				complete:function(){
					//widgInit();
		        }
	    });
	}
	
function formSubmitItem(formID){
	jQuery("#form"+formID).submit();
}

var acceptedExt = ["jpg","JPG","png","PNG","jpeg","JPEG","gif","GIF"];

function saveUploadImageItem(itemID){
    	 var fileObject = {};
    	 fileObject = document.getElementById("imageUpload"+itemID).files[0];
    	 console.log("fileObject------------",fileObject);
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

function checkItemPhoto(itemID) {
    //call after 2 second
    setTimeout(function() {
    	jQuery("#smallAjaxLoaderItemImage"+itemID).css("display","block");
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
    },1000);
  }

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

	function addItemMorsel(){
		jQuery("#smallAjaxLoaderAddItem").css("display","inline-block");
	    jQuery.ajax({
			url:"<?php echo MORSEL_API_URL;?>"+"items.json",
			type:"POST",
			data:{ api_key : "<?php echo $api_key;?>" , "item":{"morsel_id": morselGlobal} },
			success: function(response) {
				editMorsel(morselGlobal);
			},error:function(){},
			complete:function(){
				jQuery("#smallAjaxLoaderAddItem").css("display","none");
			}
        });
	}
	function saveItemDes(itemID){
		jQuery("#smallAjaxLoaderAddItem").css("display","inline-block");
		/*For getting Iframe*/
        var IFRAME = jQuery("#textareaItem"+itemID).val();
          if (IFRAME.indexOf('iframe') > -1){
              console.log('tetetet',IFRAME);
              iframe_txt = IFRAME.match(/(iframe.+?\/iframe)/g)[0];
              console.log('tetetet',iframe_txt);
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
          } else {
		/*For getting Iframe*/
    	jQuery.ajax({
			url:"<?php echo MORSEL_API_URL;?>items/"+itemID+".json",
			type:"PUT",
			data:{ api_key : "<?php echo $api_key;?>" , item : {"description": jQuery("#textareaItem"+itemID).val() } },
			success: function(response) {
				console.log("morsel_response Item Get------------",response);
				editMorsel(morselGlobal);
				jQuery("#smallAjaxLoaderAddItem").css("display","none");
			},error:function(){},
			complete:function(){}
        });
    }
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
									console.log("morsel_response Item Get------------",response);
									editMorsel(morselGlobal);
									checkItemPhoto(itemID);
									//jQuery("#item-thumb-"+itemID).attr("src",img_src);
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
</script>
