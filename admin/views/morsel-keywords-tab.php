<?php
if(isset($hostCompany) && $hostCompany != ""){
?>
<script type="text/javascript">
	jQuery( document ).ready(function() {
		getKeywordsData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>")
	});

	function getKeywordsData(userid,auth_key){
		jQuery("#ajaxLoaderPostForKeyword").css("display","block");
		jQuery.ajax({
			url:  "<?php echo MORSEL_API_URL;?>"+"keywords/show_morsel_keyword",
			type: "POST",
			data: {
				keyword:{user_id:userid},
				api_key:auth_key
			},
			success: function(response) {
				if(response.data!="blank"){
			        var data = response.data;
		            jQuery('#post_keyword_id').val(JSON.stringify(data));
						jQuery('#shortcode_keyword').html("");
						jQuery('#shortcode_keyword').append(jQuery("<option>").attr('value',0).text("- Please select keyword -"));
		            for(var k in data){
		                jQuery('#shortcode_keyword').append(jQuery("<option>").attr('value',data[k].id).text(data[k].name));
					    var html = '<tr id="morsel_keyword-'+data[k].id+'" class="post-'+data[k].id+' type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0 keywordMorselList">';
			            	html +='<td class="post-title page-title column-title keywordMorselList"><strong>'+data[k].id+'</strong></td>';
							html +='<td class="categories column-categories keywordMorselList" id="keyword-name-'+data[k].id+'">'+data[k].name+'</td>';
  		                    html +='<td class="date column-date keywordMorselList"><abbr title="">'+data[k].created_at.slice(0,10)+'</abbr><br />Created</td>';
							html +='<td class="edit-btn column-categories keywordMorselList"><button onclick="updateKeywords('+"'"+data[k].id+"'"+',1,'+"'"+escape(data[k].name)+"'"+')">Edit</button> &nbsp;&nbsp; <button onclick="deleteKeywords('+"'"+data[k].id+"'"+')">Delete</button></td>';
				            html +='</tr>';
                        jQuery("#morsel-keyword-list_data").append(html);
		            }
		        } else {
				    var html = '<tr><td></td><td><b>NO RESULT FOUND</b></td><td></td><td></td></tr>';
				    jQuery("#morsel-keyword-list_data").prepend(html);
		        }
			}, error:function(){
			    var html = '<tr><td></td><td><b>NO RESULT FOUND</b></td><td></td><td></td></tr>';
			    jQuery("#morsel-keyword-list_data").append(html);
			    jQuery("#ajaxLoaderPostForKeyword").css("display","none");
			}, complete:function(){
				console.log('Getting morsel keywords is complete');
				jQuery("#ajaxLoaderPostForKeyword").css("display","none");
			}
		});
    return true;
}
function updateKeywords(keyData, Update, keyName){
	jQuery("#keyword_id").val(keyData);
	jQuery("#updated_keywords").val("1");
	jQuery("#keyword_name").val(unescape(keyName));
}
function deleteKeywords(keywordID){
    jQuery.ajax({
		url: "<?php echo MORSEL_API_URL;?>"+"keywords/delete_morsel_keyword",
		type: "DELETE",
		data: {
		    keyword:{
		    	id:keywordID
		    },
		    api_key:"<?php echo $options['userid'].':'.$options['key'] ?>"
		},
		success: function(response) {
			alert("Keyword succssfully deleted.");
			jQuery("#morsel-keyword-list_data").html("");
			getKeywordsData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>");
		}, error:function(){
			console.log('Error in delete morsel keywords');
		}, complete:function(){
			console.log('delete morsel keywords is complete');
		}
	});
}
</script>
<!-- Keyword Edit Form -->
	<table class="form-table keywordAddMorsel">
  		<tr>  			
  			<td class="textTd">Keyword Name : </td>
			<td>
				<input type="hidden" name="post_keyword_id" id="post_keyword_id" value=""/>
				<input type="hidden" name="updated_keywords" id="updated_keywords" value="0"/>
				<input type="hidden" name="keyword_id" id="keyword_id" value=""/>
				<input type="text" style="width:50%" name="keyword[name]" id="keyword_name" value=""/>
				<div class="clearkeyboardWithMargin"></div>
				<input type="button" class="button button-primary" name="morsel-keywords-form" id="morsel-keywords-submitKey" value="Save">
			</td>
  		</tr>  		
	</table>
<!-- </form> -->
<!-- Edit Form -->
<div id="ajaxLoaderPostForKeyword" style="display:none; text-align:center;">
    <span><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajax-loader.gif"></span>
</div>
<div class="keywordListDiv">
	<table class="widefat posts">
		<thead>
			<tr>
				<th id='keyword-id' class='manage-column column-categories keywordMorselList'><span>Keyword ID</span></th>
				<th id='keyword-name' class='manage-column column-title sortable desc keywordMorselList'><span>Keyword Name</span></th>
				<th id='date' class='manage-column column-date sortable asc keywordMorselList'><span>Date</span></th>
				<th class='manage-column column-date sortable asc keywordMorselList'>Actions</th>
		  	</tr>
		</thead>
		<tfoot>
			<tr>
				<th id='keyword-id' class='manage-column column-categories keywordMorselList'>Keyword ID</th>
				<th id='keyword-name' class='manage-column column-title sortable desc keywordMorselList'><span>Keyword Name</span></th>
				<th class='manage-column column-date sortable asc keywordMorselList'><span>Date</span></th>
				<th class='manage-column column-date sortable asc keywordMorselList'>Actions</th>
			</tr>
		</tfoot>
		<tbody id="morsel-keyword-list_data"></tbody>
	</table>
</div>	
<div class="clearkeyboard"></div>

<script type="text/javascript">
	(function($){
		$("#morsel-keywords-submitKey").click(function(){
			if($("#keyword_name").val() != ""){
				var keywords_name = $("#keyword_name").val();
				$("#morsel-keywords-submit").val('Please wait!');
				if($("#keyword_id").val() != ""){ //for edit keyword
					$.ajax({
						url: "<?php echo MORSEL_API_URL;?>"+"keywords/edit_morsel_keyword",
						type: "POST",
						data: {
		    				keyword:{
		    					id:$("#keyword_id").val(),
		    					name:keywords_name
		    				},
		    				api_key:"<?php echo $options['userid'].':'.$options['key'] ?>"
		  				},
						success: function(response) {
							alert("Keyword Updated succssfully.");
							jQuery("#morsel-keyword-list_data").html("");
							getKeywordsData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>");
							$("#keyword_id").val("");//keywords_name
							$("#keyword_name").val("");
						}, error:function(){
							console.log('Error in edit morsel keywords');
						}, complete:function(){
							$("#morsel-keywords-submit").val('Connect');
							console.log('Edit morsel keywords is complete');
						}
		        	});
				} else { //for add keyword
					$.ajax({
						url: "<?php echo MORSEL_API_URL;?>"+"keywords/add_morsel_keyword",
						type: "POST",
						data: {
		    				keyword: {
		    					user_id:<?php echo $options['userid'] ?>,
		    				    name:keywords_name
		    			    },
		    				api_key:$("#kwd-morsel-key").val()
		  				},
						success: function(response) {
					    	alert("Keyword saved succssfully.")
					    	jQuery("#morsel-keyword-list_data").html("");
							getKeywordsData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>");
     						$("#keyword_id").val("");
							$("#keyword_name").val("");
						}, error:function(){
							console.log('Error in add morsel keywords');
						}, complete:function(){
							$("#morsel-keywords-submit").val('Connect');
							console.log('Add morsel keywords is complete');
						}
		        	});
				}
			} else {
				alert("Please fill the keyword text");
				$("#keyword_name").focus()
			}
		});
	}(jQuery));
</script>
<?} else { ?>
Please Enter Host Details First.
<?} ?>
