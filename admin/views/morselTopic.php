<?php
if(isset($hostCompany) && $hostCompany != ""){
?>
<script type="text/javascript">
	jQuery( document ).ready(function() {
		getTopicData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>")
	});
	function getTopicData(userid,auth_key){
		jQuery("#morselTopicsList").html("");
		jQuery("#ajaxLoaderPostForTopic").css("display","block");
		jQuery.ajax({
			url:  "<?php echo MORSEL_API_URL;?>"+"topics/show_morsel_topic",
			type: "POST",
			data: {
				topic:{user_id:userid},
				api_key:auth_key
			},
			success: function(response) {
				if(response.data!="blank"){
			        var data = response.data;
		            jQuery('#post_topic_id').val(JSON.stringify(data));
                    for(var k in data){
		                jQuery('#shortcode_topic').append(jQuery("<option>").attr('value',data[k].id).text(data[k].name));

					    var html = '<tr id="morsel_topic-'+data[k].id+'" class="post-'+data[k].id+' type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0 topicMorselList">';
		            	html +='<td class="post-title page-title column-title topicMorselList"><strong>'+data[k].id+'</strong></td>';
						html +='<td class="categories column-categories topicMorselList" id="topic-name-'+data[k].id+'">'+data[k].name+'</td>';
		                html +='<td class="date column-date topicMorselList"><abbr title="">'+data[k].created_at.slice(0,10)+'</abbr><br />Created</td>';
						html +='<td class="edit-btn column-categories topicMorselList"><button onclick="updateTopics('+"'"+data[k].id+"'"+',1,'+"'"+escape(data[k].name)+"'"+')">Edit</button> &nbsp;&nbsp; <button onclick="deleteTopics('+"'"+data[k].id+"'"+')">Delete</button></td>';
			            html +='</tr>';
		                jQuery("#morselTopicsList").append(html);
		            }
		        } else {
				    var html = '<tr><td></td><td><b>NO RESULT FOUND</b></td><td></td><td></td></tr>';
				    jQuery("#morselTopicsList").prepend(html);
		        }
			}, error:function(){
				//alert('Error in getting morsel Topics');
			    var html = '<tr><td></td><td><b>NO RESULT FOUND</b></td><td></td><td></td></tr>';
			    jQuery("#morselTopicsList").append(html);
			    jQuery("#ajaxLoaderPostForTopic").css("display","none");
			}, complete:function(){
				console.log('Getting morsel Topics is complete');
				jQuery("#ajaxLoaderPostForTopic").css("display","none");
			}
		});
    return true;
}
function updateTopics(keyData, Update, keyName){
	jQuery("#topic_id").val(keyData);
	jQuery("#updateTopicsValue").val("1");
	jQuery("#topic_name").val(unescape(keyName));
}
function deleteTopics(topicId){
    jQuery.ajax({
		url: "<?php echo MORSEL_API_URL;?>"+"topics/delete_morsel_topic",
		type: "DELETE",
		data: {
		    topic:{
		    	id:topicId
		    },
		    api_key:"<?php echo $options['userid'].':'.$options['key'] ?>"
		},
		success: function(response) {
			alert("Topic succssfully deleted.");
			jQuery("#morselTopicsList").html("");
			getTopicData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>");
		}, error:function(){
			console.log('Error in delete morsel topics');
		}, complete:function(){
			// $("#morsel-topics-submit").val('Connect');
			console.log('delete morsel topics is complete');
		}
	});
}
</script>
<style type="text/css">
/*	.topicAddMorsel td { padding: 0;}
	.topicAddMorsel td input[type="text"] { margin: 0; width: 300px;}
	.topicAddMorsel td input[type="button"] { margin: 10px 0 ;}
	.topicAddMorsel .textTd { width: 285px; margin-bottom:8px; vertical-align: top; margin-top: 5px;}
	.topicMorselList { text-align: center !important;}
	.clearTopic{ clear: both;}
    .clearTopicWithMargin{ clear: both;margin-bottom: 5px;}
    .topicListDiv { width: 100%; overflow: auto;}*/
</style>
<!-- Edit Form -->
<!-- <form method="post" action="" id="morsel-host-topics-form">-->
	<table class="form-table keywordAddMorsel">
  		<tr>  			
  			<td class="textTd">Topic Name : </td>
			<td>
				<input type="hidden" name="post_topic_id" id="post_topic_id" value=""/>
				<input type="hidden" name="updateTopicsValue" id="updateTopicsValue" value="0"/>
				<input type="hidden" name="topic_id" id="topic_id" value=""/>
				<input type="text" style="width:50%" name="topic[name]" id="topic_name" value=""/>
				<div class="clearTopicWithMargin"></div>
				<input id="morsel-topics-submitKey" class="button button-primary" type="button" value="Save" name="morsel-topics-form"></td>
  		</tr>  		
	</table>

<!-- </form> -->
<!-- Edit Form -->
<div id="ajaxLoaderPostForTopic" style="display:none; text-align:center;">
    <span><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajax-loader.gif"></span>
</div>
<div class="topicListDiv">
<table class="widefat posts">
	<thead>
		<tr>
			<th class='manage-column column-categories topicMorselList'><span>Id</span></th>
			<th class='manage-column column-title sortable desc topicMorselList'><span>Topic Name</span></th>
			<th class='manage-column column-date sortable asc topicMorselList'><span>Date</span></th>
			<th class='manage-column column-date sortable asc topicMorselList'>Actions</th>
	  	</tr>
	</thead>
	<tfoot>
		<tr>
			<th class='manage-column column-categories topicMorselList'>Id</th>
			<th class='manage-column column-title sortable desc topicMorselList'><span>Topic Name</span></th>
			<th class='manage-column column-date sortable asc topicMorselList'><span>Date</span></th>
			<th class='manage-column column-date sortable asc topicMorselList'>Actions</th>
		</tr>
	</tfoot>
	<tbody id="morselTopicsList"></tbody>
</table>
</div>

<div class="clearTopic"></div>

<script type="text/javascript">
	(function($){
		$("#morsel-topics-submitKey").click(function(){
			if($("#topic_name").val() != ""){
				var topics_name = $("#topic_name").val();
				$("#morsel-topics-submit").val('Please wait!');
				if($("#topic_id").val() != ""){ //for edit topic
					$.ajax({
						url: "<?php echo MORSEL_API_URL;?>"+"topics/edit_morsel_topic",
						type: "POST",
						data: {
		    				topic:{
		    					id:$("#topic_id").val(),
		    					name:topics_name
		    				},
		    				api_key:"<?php echo $options['userid'].':'.$options['key'] ?>"
		  				},
						success: function(response) {
							alert("Topic Updated succssfully.");
							jQuery("#morselTopicsList").html("");
							getTopicData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>");
							$("#topic_id").val("");//topics_name
							$("#topic_name").val("");
						}, error:function(){
							console.log('Error in edit morsel topics');
						}, complete:function(){
							$("#morsel-topics-submit").val('Connect');
							console.log('Edit morsel topics is complete');
						}
		        	});
				} else { //for add topic
					$.ajax({
						url: "<?php echo MORSEL_API_URL;?>"+"topics/add_morsel_topic",
						type: "POST",
						data: {
		    				topic: {
		    					user_id:<?php echo $options['userid'] ?>,
		    				    name:topics_name
		    			    },
		    				api_key:$("#kwd-morsel-key").val()
		  				},
						success: function(response) {
					    	alert("topic saved succssfully.")
					    	jQuery("#morsel-list_data").html("");
							getTopicData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>");
     						$("#topic_id").val("");
							$("#topic_name").val("");
						}, error:function(){
							console.log('Error in add morsel topics');
						}, complete:function(){
							$("#morsel-topics-submit").val('Connect');
							console.log('Add morsel topics is complete');
						}
		        	});
				}
			} else {
				alert("Please fill the topic text");
				$("#topic_name").focus()
			}
		});
	}(jQuery));
</script>
<? } else { ?>
Please Enter Host Details First.
<? } ?>
