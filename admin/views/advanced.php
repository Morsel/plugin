<?php 
if(isset($hostCompany) && $hostCompany != ""){
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
?>
<div id="morsel_post_display-details" class="shorcode-summry">	
	<!-- <h4>[morsel_post_display]</h4>
	<p>Shortcode [morsel_post_display] display your top 20 morsels</p>
	<p>In the shortcode [morsel_post_display] add attribute to it to show no of latest morsel, made them central align, gap between morsel, wrapper_width,pick keyword [morsel_post_display count=4 center_block=1 gap_in_morsel=5px wrapper_width=80 keyword_id = 10 ] like this</p> -->
	<p>If you would like to display one or more morsels on a page of your website, you can grab the code here.</p>
	<div id="short-code-preview_advanced"></div>
	<form method="post" action="" id="morsel-shortcode-form_advanced">
	   <table class="form-table">
	  		<!-- <tr valign="top">
	  			<td class="wid-15" scope="row">Number to Display * : </td>
				<td><input type="text" name="morsel_shortcode_count" id="morsel_shortcode_count" value=""/>
					<span class="attr-info">An integer value , define how much latest morsel you want to show.</span>
					<span class="attr-info">How many morsels would you like to display on your page?<br>
					For example, <a href="http://virtuecider.com/home/">please see this page where three morsels are displayed.</a> </span>
				</td>
	  		</tr> -->

	  		<!-- Use Default Value -->
	  		<tr valign="top">
	  			<td class="wid-15" scope="row">Gap In Morsel * : </td>
				<td><input type="text" name="morsel_shortcode_gap" id="morsel_shortcode_gap_advanced" value="5"/>
					<select name="morsel_shortcode_gap_unit" id="morsel_shortcode_gap_unit_advanced">
						<option value="px">In Px</option>
						<option value="%">In %</option>
					</select>
					<span class="attr-info">You can set through like 5px or 5% as a string, than it creates gaps between morsel blocks through padding-left and padding right with important,otherwise normal gap is maintained.</span>
				</td>
	  		</tr>
	  		<tr valign="top">
	  			<td class="wid-15" scope="row">Wrapper Width : </td>
				<td><input type="text" name="morsel_wrapper_width" id="morsel_wrapper_width_advanced" value="100"/>
					<span class="attr-info">Set the morsel wrapper width in %, if you want to make morsel window smaller in view, default is 100%.</span>
				</td>
	  		</tr>
	  		<!-- Use Default Value End -->
<!-- 
	  		<?php if($options["morsel_keywords"]!="blank") { ?>
	  		<tr valign="top">
	  			<td class="wid-15" scope="row">Pick Keyword:</td>
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
	  			<td class="wid-15" scope="row">Center Block : </td>
				<td><input type="checkbox" name="morsel_shortcode_center" id="morsel_shortcode_center_advanced" value="1"/>
					<span class="attr-info">It should be 1 or 0, this is for center the blocks of morsel For enable it please check the checkbox</span>
				</td>
	  		</tr>
			<tr valign="top">
	  			<td scope="row">&nbsp;</td>
				<td><?php submit_button("Get Shortcode","primary","save",null,array('id'=>'morsel_shortcode_submit_advanced')); ?></td>
	  		</tr>
		</table>
	</form>
	<div class="clear"></div>
</div>
<script type="text/javascript">
	(function($){
		
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
		    	code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap_advanced").val()+$("#morsel_shortcode_gap_unit_advanced").val()+"' center_block='"+is_center+"' wrapper_width='"+$("#morsel_wrapper_width_advanced").val()+"' keyword_id = '"+keyword_id+"']";
		    } else {
		    	code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap").val()+$("#morsel_shortcode_gap_unit_advanced").val()+"' center_block='"+is_center+"' keyword_id = '"+keyword_id+"']";
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
 <? } else { ?>
Please Enter Host Details First.
<? } ?>