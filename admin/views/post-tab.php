<?php
   $options = get_option( 'morsel_settings');
   $api_key = $options['userid'] . ':' .$options['key'];
      
   $jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&count=".MORSEL_API_COUNT;
   $json = json_decode(file_get_contents($jsonurl));
   
   if(get_option( 'morsel_post_settings')){
   		$morsel_post_settings = get_option( 'morsel_post_settings');	   		
   } else {
   		$morsel_post_settings = array();
   }
   
   if(array_key_exists('posts_id', $morsel_post_settings))
   	$post_selected = $morsel_post_settings['posts_id'];
   else
   	$post_selected = array();
?>

<?php if(count($json->data)>0){?>   
 <form method="post" action="options.php" id="morsel-form">
      <?php settings_fields( 'morsel_post_settings' ); ?>
      <?php do_settings_sections( 'morsel_post_settings' ); ?>
   
      <p>
      	<h3>Selected posts will not be display on your site.</h3>
      </p>
      
<table class="wp-list-table widefat fixed posts">
	<thead>
	<tr>
		<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
		  <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
		  <input id="cb-select-all-1" type="checkbox" />
		</th>
		<th scope='col' id='title' class='manage-column column-title sortable desc'  style="">
		  <span>Title</span>
		</th>
		<th scope='col' id='author' class='manage-column column-author'  style="">Image</th>
		<th scope='col' id='categories' class='manage-column column-categories'  style="">
		Description</th>
		<th scope='col' id='date' class='manage-column column-date sortable asc'  style="">
  		     <span>Date</span>
  		</th>
  	</tr>
	</thead>

	<tfoot>
	<tr>
		<th scope='col'  class='manage-column column-cb check-column'  style="">
		  <label class="screen-reader-text" for="cb-select-all-2">Selecsdt All</label>
		  <input id="cb-select-all-2" type="checkbox" />
		</th>
		<th scope='col'  class='manage-column column-title sortable desc'  style="">
		  <span>Title</span>
		</th>
		<th scope='col'  class='manage-column column-author'  style="">Image</th>
		<th scope='col'  class='manage-column column-categories'  style="">Description</th>
		<th scope='col'  class='manage-column column-date sortable asc'  style="">
		  <span>Date</span>
		</th>
	</tr>
	</tfoot>

	<tbody id="the-list">
	 <?php foreach ($json->data as $row) {      ?>

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
              }	?>
            </td>
			<td class="categories column-categories">
			  <?php echo substr($row->summary,0,150); echo (strlen($row->summary) > 150 ? "..." :"");?>  
			</td>
			<td class="date column-date">
			    <abbr title="<?php echo date("d/m/Y", strtotime($row->created_at));?>"><?php echo date("d/m/Y", strtotime($row->created_at));?></abbr><br />Published
			</td>
		</tr>
	  <?php } ?>	
	</tbody>
</table>
<div class="clear"><br></div>
<div>
<?php submit_button("Save","primary","Select",null,array( 'id' => 'morsel_post_selection' ) ); ?>
<input type="button" value="Load more!" class="button" id="admin_loadmore" name="load" style="margin-left:20px"></div>
</form>

<?php } else { ?>
  <p><h3>Oops! You don't have any post on your site.</h3></p>
<?php } ?>
