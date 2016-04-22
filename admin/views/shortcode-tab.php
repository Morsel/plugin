<?php
if(isset($hostCompany) && $hostCompany != ""){
?>
<div id="morsel_post_display-details" class="shorcode-summry">
	<!-- <h4>[morsel_post_display]</h4>
	<p>Shortcode [morsel_post_display] display your top 20 morsels</p>
	<p>In the shortcode [morsel_post_display] add attribute to it to show no of latest morsel, made them central align, gap between morsel, wrapper_width,pick keyword [morsel_post_display count=4 center_block=1 gap_in_morsel=5px wrapper_width=80 keyword_id = 10 ] like this</p> -->
	<p>If you would like to display one or more morsels on a page of your website, you can grab the code here.</p>
	<div id="short-code-preview"></div>
	<form method="post" action="" id="morsel-shortcode-form">
	   <table class="form-table">
	  		<tr valign="top">
	  			<td scope="row" class="verticalTop">Number of morsels to display  : </td>
				<td>
                    <span class="attr-info">How many morsels would you like to display on your page?<br></span>
					<input type="text" name="morsel_shortcode_count" id="morsel_shortcode_count" placeholder="required" value=""/>
					<span class="attr-info">For example, <a href="http://virtuecider.com/home/">please see this page where three morsels are displayed.</a> </span>
					<!-- <span class="attr-info">An integer value , define how much latest morsel you want to show.</span> -->
					</td>
	  		</tr>
	  		<!-- Use Default Value -->
	  		<tr valign="top" style="display:none">
	  			<td scope="row" class="verticalTop" >Gap In Morsel *: </td>
				<td>
					<span class="attr-info">You can set through like 5px or 5% as a string, than it creates gaps between morsel blocks through padding-left and padding right with important,otherwise normal gap is maintained.<br></span>
				    <input type="text" name="morsel_shortcode_gap" id="morsel_shortcode_gap" value="5"/>
					<select name="morsel_shortcode_gap_unit" id="morsel_shortcode_gap_unit">
						<option value="px">In Px</option>
						<option value="%">In %</option>
					</select>
				</td>
	  		</tr>
	  		<tr valign="top"  style="display:none">
	  			<td scope="row" class="verticalTop" >Wrapper Width: </td>
				<td>
					<span class="attr-info">Set the morsel wrapper width in %, if you want to make morsel window smaller in view, default is 100%.<br></span>
				    <input type="text" name="morsel_wrapper_width" id="morsel_wrapper_width" value="100"/>
				</td>
	  		</tr>
	  		<!-- Use Default Value End -->

	  		<tr valign="top">
	  			<td scope="row" class="verticalTop">Keyword to display: </td>
				<td>
				<span class="attr-info">Select which morsels will display by choosing keywords. Every morsel associated to a keyword will display on the page automatically.<br></span>
				<select id="shortcode_keyword">
					<option id ="none" value = "0">- Please select keyword -</option>
				</select>
				</td>
	  		</tr>
            <tr valign="top">
	  			<td scope="row" class="verticalTop" >Associated user to display: </td>
				<td>
					<span class="attr-info">If you are displaying morsels from one user on page, please select the user here.<br></span>
					<select name="morsel_shortcode_user" id="morsel_shortcode_user" multiple="multiple" placeholder="Please select User:"  class="" >
						<!-- <option value = "0">- Please select User -</option> -->
					</select>
					<!-- <button id="show-associate-user" onclick="show_tb_popup_box('morsel_shortcode_asso_user',570,110,'Please select the user from below.')">Select User</button> -->
				</td>
	  		</tr>
	  		<tr valign="top">
	  			<td scope="row" class="verticalTop" >Topic to display: </td>
				<td>
				<span class="attr-info">Select which morsels will display by choosing topic. Every morsel associated to a topic will display on the page automatically.<br></span>
				<select id="shortcode_topic">
					<option id ="none" value = "">- Please select Topic -</option>
				</select>
				</td>
	  		</tr>
	  		<tr valign="top" style="display:none;">
	  			<td scope="row" class="verticalTop" >Center Block: </td>
					<td><input type="checkbox" name="morsel_shortcode_center" id="morsel_shortcode_center" value="1"/>
						<span class="attr-info">It should be 1 or 0, this is for center the blocks of morsel For enable it please check the checkbox</span>
					</td>
	  		</tr>
				<tr valign="top">
	  			<td scope="row">&nbsp;</td>
					<td><?php submit_button("Get Shortcode","primary","save",null,array('id'=>'morsel_shortcode_submit')); ?>&nbsp; &nbsp;<input type="button" value="Community Shortcode" onclick="other_shortcode();" class="button button-primary">&nbsp; &nbsp;<input type="button" value="Show Advanced" onclick="checkAdvancedTab('showAdvancedTab')" id="showAdvancedTab" class="button button-primary">					</td>
	  		</tr>
		</table>
	</form>


<?php // get all updated keyword on post tab
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
?>
<div id="advancedData" class="shorcode-summry" style="display:none;">
	<!-- <h4>[morsel_post_display]</h4>
	<p>Shortcode [morsel_post_display] display your top 20 morsels</p>
	<p>In the shortcode [morsel_post_display] add attribute to it to show no of latest morsel, made them central align, gap between morsel, wrapper_width,pick keyword [morsel_post_display count=4 center_block=1 gap_in_morsel=5px wrapper_width=80 keyword_id = 10 ] like this</p> -->
	<!-- <p>If you would like to display one or more morsels on a page of your website, you can grab the code here.</p>
	 --><div id="short-code-preview_advanced"></div>
	<form method="post" action="" id="morsel-shortcode-form_advanced">
	   <table class="form-table">
	  		<!-- <tr valign="top">
	  			<td scope="row">Number to Display * : </td>
				<td><input type="text" name="morsel_shortcode_count" id="morsel_shortcode_count" value=""/>
					<span class="attr-info">An integer value , define how much latest morsel you want to show.</span>
					<span class="attr-info">How many morsels would you like to display on your page?<br>
					For example, <a href="http://virtuecider.com/home/">please see this page where three morsels are displayed.</a> </span>
				</td>
	  		</tr> -->

	  		<!-- Use Default Value -->
	  		<tr valign="top">
	  			<td scope="row" class="verticalTop" >Gap In Morsel * : </td>
				<td>
					<span class="attr-info">You can set through like 5px or 5% as a string, than it creates gaps between morsel blocks through padding-left and padding right with important,otherwise normal gap is maintained.<br></span>
				    <input type="text" name="morsel_shortcode_gap" id="morsel_shortcode_gap_advanced" value="5"/>
					<select name="morsel_shortcode_gap_unit" id="morsel_shortcode_gap_unit_advanced">
						<option value="px">In Px</option>
						<option value="%">In %</option>
					</select>
				</td>
	  		</tr>
	  		<tr valign="top">
	  			<td scope="row" class="verticalTop" >Wrapper Width : </td>
				<td>
					<span class="attr-info">Set the morsel wrapper width in %, if you want to make morsel window smaller in view, default is 100%.<br></span>
				    <input type="text" name="morsel_wrapper_width" id="morsel_wrapper_width_advanced" value="100"/>
				</td>
	  		</tr>
	  		<!-- Use Default Value End -->
<!--
	  		<?php if($options["morsel_keywords"]!="blank") { ?>
	  		<tr valign="top">
	  			<td scope="row">Pick Keyword:</td>
				<td>
				<select id="shortcode_keyword">
					<option id ="none" value = "0">- Please select keyword -</option>
					<?php foreach(json_decode($options["morsel_keywords"]) as $row){ ?>
					<option value="<?php echo $row->id;?>" ><?php echo $row->name;?></option>
					<?php } ?>
				</select>
				<span class="attr-info">Select which morsels will display by choosing keywords. Every morsel associated to a keyword will display on the page automatically.</span>
				</td>
	  		</tr>
	  		<?php } ?> -->
	  		<tr valign="top">
	  			<td scope="row" class="verticalTop" >Center Block : </td>
				<td>
					<span class="attr-info">It should be 1 or 0, this is for center the blocks of morsel For enable it please check the checkbox.<br></span>
				    <input type="checkbox" name="morsel_shortcode_center" id="morsel_shortcode_center_advanced" value="1"/>
				</td>
	  		</tr>
	  		<!--Select No of morsel in a row, if you want a morsel in a row, select 1 it append class col-md-12 means a morsel -->
	  		<tr valign="top">
	  			<td scope="row" class="verticalTop" >Morsel In A Row: </td>
					<td>
						<span class="attr-info">Here we can select how many morsel in a row will be shown by shortcode. 3 is a default value.<br></span>
						<select id="morsel-in-row">
							<option value="2"> Select no of morsel shown in a row</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="4">4</option>
							<option value="6">6</option>
						</select>
					</td>
	  		</tr>
			<tr valign="top">
	  			<td scope="row" class="verticalTop" >&nbsp;</td>
				<td><?php submit_button("Get Shortcode","primary","save",null,array('id'=>'morsel_shortcode_submit_advanced')); ?>&nbsp;&nbsp;<input type="button" value="Hide Advanced
				" onclick="hideAdvancedTabFunction('hideAdvancedTab')" id="hideAdvancedTab" class="button button-primary"></td>
	  		</tr>
		</table>
	</form>
	<div class="clear"></div>
</div>
<script type="text/javascript">
function hideAdvancedTabFunction(buttonId){
  jQuery("#advancedData").css("display","none");
  jQuery("#showAdvancedTab").css("display","inline-block");
}
	(function($){
		jQuery('.sumo-select').SumoSelect({okCancelInMulti:true, selectAll:true });
		$("#morsel-shortcode-form_advanced").validate({
		  rules: {
		    // morsel_shortcode_count: {
		    //   required: true,
		    //   number: true,
		    //   max:20,
		    //   min:0
		    // },
		    morsel_shortcode_gap_advanced: {
		      required: true,
		      number: true
		    },
		    morsel_wrapper_width_advanced: {
		      number: true,
		      max: 100,
		      min: 0
		    }
		  },
		  messages: {
		  	morsel_shortcode_count: {
		      required: "Please enter no of latest morsel you want.",
		      number: "Please enter only numaric value in the count.",
		      max:"Please enter value less than 20 .",
		      min:"Please enter positive value ."
		    },
		    morsel_shortcode_gap_advanced: {
		      required: "Please enter numeric gap value.",
		      number: "Please enter only numaric value in the gap value."
		    },
		    morsel_wrapper_width_advanced: {
		      number: "Please enter only numaric value in the wrapper width.",
		      max: "Please enter value upto 100 in the wrapper width",
		      min:"Please enter positive value in the wrapper width."
		    }
		  },errorPlacement: function (error, element) {
			    if ((element.attr("id") == "morsel_shortcode_gap_advanced")) {
			    	console.log("here");
			        error.insertAfter($(element).next().next($('span.attr-info')));
			    } else {
			        error.insertAfter($(element).next($('span.attr-info')));
			    }
		  },
		  submitHandler: function(form) {
		    var is_center = $("#morsel_shortcode_center_advanced").prop('checked') ? 1 : 0;
		    var keyword_id = $("#shortcode_keyword").val();
		    var code = "";
		    if($("#morsel_wrapper_width_advanced").val() != ""){
		    	code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap_advanced").val()+$("#morsel_shortcode_gap_unit_advanced").val()+"' center_block='"+is_center+"' wrapper_width='"+$("#morsel_wrapper_width_advanced").val()+"' keyword_id = '"+keyword_id+"' morsel_in_row='"+$("#morsel-in-row").val()+"']";
		    } else {
		    	code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap").val()+$("#morsel_shortcode_gap_unit_advanced").val()+"' center_block='"+is_center+"' keyword_id = '"+keyword_id+"' morsel_in_row='"+$("#morsel-in-row").val()+"']";
		    }
		    $("#short-code-preview_advanced").html("<h3>Here is your shortcode : \n\n"+code+"</h3>");
		  }
		});
		/*$('#morsel_shortcode_submit').click(function(event){
			event.preventDefault();
			$("#morsel-shortcode-form_advanced").validate();
			var code = "[morsel_post_display count='"+$("morsel_shortcode_count").val()+"' gap_in_morsel='"+$("morsel_shortcode_gap").val()+"' gap_in_morsel='"+$("morsel_shortcode_center_advanced").val()+"']"
			alert(code);
		})*/
	}(jQuery))
</script>




</div>
<div id="shortcode_model_id" style="display:none;">
	 <span>Create a new page on your website where you would like to direct users to share their stories. Then copy and paste the shortcode below onto your new page. Anyone with the link can create a morsel and support your brand.<br><br><h5 class="mrsl-action-btn">[create_morsel]</h5></span>
</div>
<script type="text/javascript">
 	function other_shortcode(){
   var url = "#TB_inline?width=570&height=110&inlineId=shortcode_model_id";
	 tb_show("Shortcode for community", url);
	}

	function show_tb_popup_box(box_id,width,height,title){
		var url = "#TB_inline?width="+width+"&height="+height+"&inlineId="+box_id;
	 	tb_show(title, url);
	}

function checkAdvancedTab(buttonID){
  jQuery("#"+buttonID).css("display","none");
  jQuery("#advancedData").css("display","inline-block");
}
	(function($){
		$("#morsel-shortcode-form").validate({
		  rules: {
		    morsel_shortcode_count: {
		      required: true,
		      number: true,
		      min:1
		    },
		    morsel_shortcode_gap: {
		      required: true,
		      number: true
		    },
		    morsel_wrapper_width: {
		      number: true,
		      max: 100,
		      min: 0
		    }
		  },
		  messages: {
		  	morsel_shortcode_count: {
		      required: "Please enter number of latest morsel you want.",
		      number: "Please enter only numaric value in the count.",
		      min:"Please enter positive value ."
		    },
		    morsel_shortcode_gap: {
		      required: "Please enter numeric gap value.",
		      number: "Please enter only numaric value in the gap value."
		    },
		    morsel_wrapper_width: {
		      number: "Please enter only numaric value in the wrapper width.",
		      max: "Please enter value upto 100 in the wrapper width",
		      min:"Please enter positive value in the wrapper width."
		    }
		  },errorPlacement: function (error, element) {
			    if ((element.attr("id") == "morsel_shortcode_gap")) {
			    	console.log("here");
			        error.insertAfter($(element).next().next($('span.attr-info')));
			    } else {
			        error.insertAfter($(element).next($('span.attr-info')));
			    }
		  },
		  submitHandler: function(form) {
		    var is_center = $("#morsel_shortcode_center").prop('checked') ? 1 : 0;
		    var keyword_id = $("#shortcode_keyword").val();
		    var code = "";
		    var associated_user = $("#morsel_shortcode_user").val();
		    if(!associated_user){
		    	associated_user = 0;
		    }
		    if($("#morsel_wrapper_width").val() != ""){
		    	//code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap").val()+$("#morsel_shortcode_gap_unit").val()+"' center_block='"+is_center+"' wrapper_width='"+$("#morsel_wrapper_width").val()+"' keyword_id = '"+keyword_id+"' associated_user='"+$("#morsel_shortcode_user").val()+"']";//
		      	code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap").val()+$("#morsel_shortcode_gap_unit").val()+"' center_block='"+is_center+"' wrapper_width='"+$("#morsel_wrapper_width").val()+"' keyword_id = '"+keyword_id+"' associated_user='"+associated_user+"' topic_name='"+$("#shortcode_topic").val()+"' ]";//
		    } else {
		    	//code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap").val()+$("#morsel_shortcode_gap_unit").val()+"' center_block='"+is_center+"' keyword_id = '"+keyword_id+"' associated_user='"+$("#morsel_shortcode_user").val()+"']";
 	            code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap").val()+$("#morsel_shortcode_gap_unit").val()+"' center_block='"+is_center+"' keyword_id = '"+keyword_id+"' associated_user='"+associated_user+"' topic_name='"+$("#shortcode_topic").val()+"']";
		    }
		    $("#short-code-preview").html("<h3>Here is your shortcode &nbsp;: \n\n"+code+"</h3>");
		  }
		});

		/*$('#morsel_shortcode_submit').click(function(event){
			event.preventDefault();
			$("#morsel-shortcode-form").validate();
			var code = "[morsel_post_display count='"+$("morsel_shortcode_count").val()+"' gap_in_morsel='"+$("morsel_shortcode_gap").val()+"' gap_in_morsel='"+$("morsel_shortcode_center").val()+"']"
			alert(code);
		})*/
	}(jQuery))
</script>
<? } else { ?>
Please Enter Host Details First.
<? } ?>
