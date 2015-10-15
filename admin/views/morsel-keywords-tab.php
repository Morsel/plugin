<?php
if(isset($hostCompany) && $hostCompany != ""){
?>
<script type="text/javascript">
jQuery( document ).ready(function() {
	getKeywordsData("<?php echo $options['userid'] ?>","<?php echo $options['userid'].':'.$options['key'] ?>")
});

	function getKeywordsData(userid,auth_key){
	
	jQuery.ajax({
		url:  "<?php echo MORSEL_API_URL;?>"+"keywords/show_morsel_keyword",
		type: "POST",
		data: {
				keyword:{user_id:userid},
				api_key:auth_key
			},
		success: function(response) {
			
			if(response.data!="blank")
			{
		         
                var data = response.data;
                jQuery('#post_keyword_id').val(JSON.stringify(data));

				/*create option for shortcode tab*/ 
      			var sel = jQuery('#shortcode_keyword');
					jQuery(data).each(function() {
					 sel.append(jQuery("<option>").attr('value',this.id).text(this.name));
					});
				
                
              
                for(var k in data){
                  var html = '<tr id="morsel_keyword-'+data[k].id+'" class="post-'+data[k].id+' type-post status-publish format-standard hentry category-uncategorized alternate iedit author-self level-0">';
                	html +='<td class="post-title page-title column-title"><strong>'+data[k].id+'</strong></td>';            
					html +='<td class="categories column-categories" id="keyword-name-'+data[k].id+'">'+data[k].name+'</td>';
                                    
					html +='<td class="date column-date"><abbr title="">'+data[k].created_at.slice(0,10)+'</abbr><br />Created</td>';
					html +='<td class="edit-btn column-categories"><button onclick="updateKeywords('+"'"+data[k].id+"'"+',1,'+"'"+data[k].name+"'"+')">Edit</button></td>';
		           html +='</tr>';
                jQuery("#morsel-keyword-list_data").append(html);
                }
            } else {            	
			    var html = '<tr><td></td><td><b>NO RESULT FOUND</b></td><td></td><td></td></tr>';
			    jQuery("#morsel-keyword-list_data").prepend(html);
            }
		},error:function(){
			alert('Error in getting morsel keywords of user');
		    var html = '<tr><td></td><td><b>NO RESULT FOUND</b></td><td></td><td></td></tr>';
		    jQuery("#morsel-keyword-list_data").append(html);
		},complete:function(){
			console.log('Getting morsel keywords is complete');
		}
    });
    return true;
}
function updateKeywords(keyData, Update, keyName){
	jQuery("#keyword_id").val(keyData);
	jQuery("#updated_keywords").val("1");
	jQuery("#keyword_name").val(keyName);
}
</script>

<!-- Edit Form -->
<!-- <form method="post" action="" id="morsel-host-keywords-form"> 	        -->  	
   	<table class="form-table">
  		<tr valign="top">  			
  			<td scope="row">Keyword Name:</td>
			<td>
				<input type="hidden" name="post_keyword_id" id="post_keyword_id" value=""/> 	
				<input type="hidden" name="updated_keywords" id="updated_keywords" value="0"/>
				<input type="hidden" name="keyword_id" id="keyword_id" value=""/>
				<input type="text" style="width:50%" name="keyword[name]" id="keyword_name" value=""/>
			</td>
  		</tr>  		
		<tr valign="top">
  			<td scope="row">&nbsp;</td>
			<td><input id="morsel-keywords-submitKey" class="button button-primary" type="button" value="Save" name="morsel-keywords-form"></td>
  		</tr>
	</table>
<!-- </form> -->
<!-- Edit Form -->


<table class="wp-list-table widefat posts">
	<thead>
		<tr>
			<th scope='col' id='keyword-id' class='manage-column column-categories'  style=""><span>Keyword ID</span></th>
			<th scope='col' id='keyword-name' class='manage-column column-title sortable desc'  style=""><span>Keyword Name</span></th>
			<!-- Description</th> -->
			<th scope='col' id='date' class='manage-column column-date sortable asc'  style=""><span>Date</span></th>
			<th scope='col' class='manage-column column-date sortable asc' style="">Actions</th>
	  	</tr>
	</thead>
	<tfoot>
		<tr>			
			<th scope='col' id='keyword-id' class='manage-column column-categories'  style="">Keyword ID</th>
			<th scope='col' id='keyword-name' class='manage-column column-title sortable desc'  style=""><span>Keyword Name</span></th>
			<th scope='col' class='manage-column column-date sortable asc' style=""><span>Date</span></th>
			<th scope='col' class='manage-column column-date sortable asc' style="">Actions</th>	
		</tr>
	</tfoot>

	<tbody id="morsel-keyword-list_data">
		
	</tbody>
</table>
<div class="clear"><br></div>

<script type="text/javascript">
	(function($){
		$("#morsel-keywords-submitKey").click(function(){

			if($("#keyword_name").val() != ""){
				var keywords_name = $("#keyword_name").val();
				$("#morsel-keywords-submit").val('Please wait!');
				if($("#keyword_id").val() != ""){ //for edit keyword
					var keywords_name = $("#keyword_name").val();
				
					var regex = /[^\w\s]/gi;

					if(regex.test(keywords_name) == true) {
					    alert('Your keyword string contains illegal characters.');
					    return;
					}
					
					$.ajax({
						url:  "<?php echo MORSEL_API_URL;?>"+"keywords/edit_morsel_keyword",
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
							
						},error:function(){
							console.log('Error in edit morsel keywords');
						},complete:function(){
							$("#morsel-keywords-submit").val('Connect');
							console.log('Edit morsel keywords is complete');
						}
		        	});
				} else { //for add keyword
					
				
					var regex = /[^\w\s]/gi;

					if(regex.test(keywords_name) == true) {
					    alert('Your keyword string contains illegal characters.');
					    return;
					}




					$.ajax({
						url:  "<?php echo MORSEL_API_URL;?>"+"keywords/add_morsel_keyword",
						type: "POST",
						data: {
		    				keyword:{user_id:<?php echo $options['userid'] ?>,
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
						},error:function(){
							console.log('Error in add morsel keywords');
						},complete:function(){
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
 <? } else { ?>
Please Enter Host Details First.
<? } ?>
