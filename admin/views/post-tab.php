<?php
if(isset($hostCompany) && $hostCompany != ""){
?>
<?php
    if(isset($morselSettings['morsel_keywords'])) {
   	 	$old_option = get_option('morsel_settings');
	    $old_option['morsel_keywords'] = str_replace("'","",$old_option['morsel_keywords']);
	    update_option("morsel_settings",$old_option);
    }
   	$options = get_option('morsel_settings');
   	$api_key = $options['userid'] . ':' .$options['key'];
   	$jsonurl = MORSEL_API_URL."users/".$options['userid']."/morsels.json?api_key=$api_key&count=".MORSEL_API_COUNT."&submit=true";
   	unset($jsonPost);
   	$jsonPost = json_decode(file_get_contents($jsonurl));

    if(count($jsonPost->data)==0){
       $jsonPost = json_decode(wp_remote_fopen($jsonurl));
    }

   	$morsel_page_id = get_option( 'morsel_plugin_page_id');

    if(get_option( 'morsel_post_settings')){
   	  $morsel_post_settings = get_option( 'morsel_post_settings');
    } else {
   	  $morsel_post_settings = array();
    }

    if(array_key_exists('posts_id', $morsel_post_settings))
   		$post_selected = $morsel_post_settings['posts_id'];
    else
   	  	$post_selected = array();


// get all updated keyword on post tab
	if(isset($_POST["keyword"]["name"])){
		if($_POST["keyword_id"] != ""){
			$new_settings = get_option("morsel_settings");
	    	$allKeywords = json_decode($new_settings['morsel_keywords']);
	    	foreach($allKeywords as $kwd){
	    		if($kwd->id == $_POST["keyword_id"]){
	    			$kwd->name = $_POST["keyword"]["name"];
	    		}
	    	}
	    	$new_settings['morsel_keywords'] = json_encode($allKeywords);
	    	update_option("morsel_settings",$new_settings);
	    	if(isset($options["morsel_keywords"])) {
	    	 	$options["morsel_keywords"] = $new_settings['morsel_keywords'];
	    	}
		} else {
			$new_keyword = stripslashes($_POST["updated_keywords"]);
		    $new_settings = get_option("morsel_settings");
	    	$new_settings['morsel_keywords'] = ($new_keyword);
	    	update_option("morsel_settings",$new_settings);
	    	if(isset($options["morsel_keywords"])) {
	    	 	$options["morsel_keywords"] = ($new_keyword);
	    	}
		}
	}

//save preview Text
if(isset($_POST["morsel_settings_Preview"])){
	$new_settings = $_POST["morsel_settings_Preview"];
 	update_option("morsel_settings_Preview",$new_settings);
}


if(count($jsonPost->data)>0){?>
<style type="text/css">
/*	.displayNone {display: none;}
	.previewText {float:left; width:100%;}
	.previewText table {margin:0;}
	.previewText td { padding:15px 0px;}
	.previewText input[type="text"] { margin-bottom: 0;}
	.loadNewButton {float: right;}
	.morselPostListTable tr>th:first-child{ padding-left: 10px;}
	.morselPostListTable .unPublished { color: red;}
	.morselPostListTable .unPublished span { font-weight: bold; font-size: 15px;}
	.morselPostListTable .schedualDate {white-space:nowrap;}
	.editAboveImage {margin:0 10px 0 0;}
	.actionButtonMorsel{ margin-bottom: 5px !important;}
	.morselPostListTableDiv {  overflow-x: auto; clear: both;}*/
</style>


<!-- scroll css-->
<link rel="stylesheet" type="text/css" href=<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'infinite-scroll/scroll.css'?>>
<!-- scroll css-->

<span class="postTab postJsonData" id="morselTableList">
	<!-- edit Morsel Start -->
	<? include_once("includeView/editMorsel.php");?>
	<!-- edit Morsel End -->
    <div class="previewText">
		<!-- previewText Start -->
			<? include_once("includeView/morselPreviewText.php");?>
		<!-- previewText End -->	   
	</div>
		<!-- Morsel list Start -->
		<? include_once("includeView/morselList.php");?>
		<!-- Morsel list End -->
	<!-- Showing keywords selection -->
	    <? include_once("includeView/showingKeywords.php");?>
	<!-- End -->

    <!-- Showing Topic selection -->
        <? include_once("includeView/showingTopic.php");?>
    <!-- End -->

	<!-- Showing Meta Preview text -->
	    <? include_once("includeView/metaPost.php");?>
	<!-- End -->
    <div class="clear"><br></div>
    <div>
        <div id="no-more">No More Morsels</div>
	    <div id="ajaxLoader" >
	        <span><img src="<?php echo MORSEL_PLUGIN_IMG_PATH;?>ajax-loader.gif"></span>
	    </div>
	</div>

<script type="text/javascript">
    jQuery('.morselClosePopup').click(function(){
		jQuery( "#TB_closeWindowButton" ).trigger( "click" )
	});

/*Morsel scroll script*/
	var morselNoMore;
	var morsePageCount = 1;
	jQuery(window).scroll(function() {
		if(jQuery('#tabs1-js').css('display') != 'none'){
		    if(morselNoMore != true){
		    	if (jQuery(window).scrollTop() + jQuery(window).height() == jQuery(document).height()) {
				    jQuery('#no-more').hide();
				    jQuery("#ajaxLoader").css("display", "block");
				    jQuery.ajax({
				        url: "<?php echo site_url()?>" + "/index.php?pagename=morsel_ajax_admin&page_id=" + parseInt(++morsePageCount),
				        success: function(data) {
							if (data.trim().length > 1) {
								jQuery('#the-list tr:last').after(data);
							} else {
								morsePageCount--;
								morselNoMore = true;
								jQuery('#no-more').show();
							}
				        }, error: function() {
					        morsePageCount--;
					    }, complete: function() {
					        jQuery("#ajaxLoader").css("display", "none");
					    }
				    });
			    }
		    }
		}
	});	
/*Morsel scroll script*/
</script>
<!-- datetimepicker -->
   <? include_once("includeView/dateTimePicker.php");?>
<!-- datetimepicker -->
<?php } else { ?>
  <p><h3>Oops! You don't have any morsel on your site.</h3></p>
<?php } ?>
</span>
<? } else { ?>
Please Enter Host Details First.
<? } ?>

