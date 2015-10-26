<?php 
if(isset($hostCompany) && $hostCompany != ""){
// print_r($new_associated);
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
	  			<td class="wid-15" scope="row">Number to Display * : </td>
				<td><input type="text" name="morsel_shortcode_count" id="morsel_shortcode_count" value=""/>
					<!-- <span class="attr-info">An integer value , define how much latest morsel you want to show.</span> -->
					<span class="attr-info">How many morsels would you like to display on your page?<br>
					For example, <a href="http://virtuecider.com/home/">please see this page where three morsels are displayed.</a> </span>
				</td>
	  		</tr>

	  		<!-- Use Default Value -->
	  		<tr valign="top" style="display:none">
	  			<td class="wid-15" scope="row">Gap In Morsel * : </td>
				<td><input type="text" name="morsel_shortcode_gap" id="morsel_shortcode_gap" value="5"/>
					<select name="morsel_shortcode_gap_unit" id="morsel_shortcode_gap_unit">
						<option value="px">In Px</option>
						<option value="%">In %</option>
					</select>
					<span class="attr-info">You can set through like 5px or 5% as a string, than it creates gaps between morsel blocks through padding-left and padding right with important,otherwise normal gap is maintained.</span>
				</td>
	  		</tr>
	  		<tr valign="top"  style="display:none">
	  			<td class="wid-15" scope="row">Wrapper Width : </td>
				<td><input type="text" name="morsel_wrapper_width" id="morsel_wrapper_width" value="100"/>
					<span class="attr-info">Set the morsel wrapper width in %, if you want to make morsel window smaller in view, default is 100%.</span>
				</td>
	  		</tr>
	  		<!-- Use Default Value End -->
  		
	  		<tr valign="top">
	  			<td class="wid-15" scope="row">Pick Keyword:</td>
				<td>
				<select id="shortcode_keyword">
					<option id ="none" value = "0">- Please select keyword -</option>
				</select>
				<span class="attr-info">Select which morsels will display by choosing keywords. Every morsel associated to a keyword will display on the page automatically.</span>
				</td>
	  		</tr>
            <tr valign="top">
	  			<td class="wid-15" scope="row">Choose Associated User:</td>
				<td>
				<select name="morsel_shortcode_user" id="morsel_shortcode_user">
					<option value = "0">- Please select User -</option>
				</select>
				<span class="attr-info"></span>
				</td>
	  		</tr>
	  		<!-- <tr valign="top">
	  			<td class="wid-15" scope="row">Topic : </td>
				<td><input type="text" name="morsel_topic_name" id="morsel_topic_name" value=""/>
					<span class="attr-info"></span>
				</td>
	  		</tr> -->
	  		
	  		<tr valign="top" style="display:none;">
	  			<td class="wid-15" scope="row">Center Block : </td>
				<td><input type="checkbox" name="morsel_shortcode_center" id="morsel_shortcode_center" value="1"/>
					<span class="attr-info">It should be 1 or 0, this is for center the blocks of morsel For enable it please check the checkbox</span>
				</td>
	  		</tr>
			<tr valign="top">
	  			<td scope="row">&nbsp;</td>
				<td><?php submit_button("Get Shortcode","primary","save",null,array('id'=>'morsel_shortcode_submit')); ?></td>
	  		</tr>
		</table>
	</form>
</div>
<script type="text/javascript">
	(function($){
		
		$("#morsel-shortcode-form").validate({
		  rules: {
		    morsel_shortcode_count: {
		      required: true,
		      number: true,
		      max:50,
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
		      max:"Please enter value less than 20 .",
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
		    if($("#morsel_wrapper_width").val() != ""){
		    	code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap").val()+$("#morsel_shortcode_gap_unit").val()+"' center_block='"+is_center+"' wrapper_width='"+$("#morsel_wrapper_width").val()+"' keyword_id = '"+keyword_id+"' associated_user='"+$("#morsel_shortcode_user").val()+"]";//
		      	// code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap").val()+$("#morsel_shortcode_gap_unit").val()+"' center_block='"+is_center+"' wrapper_width='"+$("#morsel_wrapper_width").val()+"' keyword_id = '"+keyword_id+"' associated_user='"+$("#morsel_shortcode_user").val()+"' topic_name='"+$("#morsel_topic_name").val()+"']";//
		    } else {
		    	code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap").val()+$("#morsel_shortcode_gap_unit").val()+"' center_block='"+is_center+"' keyword_id = '"+keyword_id+"' associated_user='"+$("#morsel_shortcode_user").val()+"]";
 	            // code += "[morsel_post_display count='"+$("#morsel_shortcode_count").val()+"' gap_in_morsel='"+$("#morsel_shortcode_gap").val()+$("#morsel_shortcode_gap_unit").val()+"' center_block='"+is_center+"' keyword_id = '"+keyword_id+"' associated_user='"+$("#morsel_shortcode_user").val()+"' topic_name='"+$("#morsel_topic_name").val()+"']";
		    }

		    $("#short-code-preview").html("<h3>Here is your shortcode : \n\n"+code+"</h3>");			
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