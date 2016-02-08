<link href="<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'datetimepicker/bootstrap/css/bootstrap.min.css'?>" rel="stylesheet" media="screen">
<link href="<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'datetimepicker/bootstrap/css/bootstrap-datetimepicker.min.css'?>" rel="stylesheet" media="screen">
<div id="modal-datetimepicker-id" class="displayNone">
	    <form action="" class="form-horizontal">
	        <input id="schedualMorsel" value="" type="hidden">
	        <input id="gmt" value="" type="hidden">
	        <fieldset>
     	        <legend>Select Date & Time</legend>
	            <div class="controls input-append date form_datetime" data-date="2015-09-16T05:25:07Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="scheduleDataMorsel">
	                <input size="16" type="text" value="" readonly style="width:240px;">
	                <span class="add-on"><i class="icon-th"></i></span>
	            </div>
					<input type="hidden" id="scheduleDataMorsel" value="" />
				<br/>
	            <br>
	            <a id="morsel_schedule" class="button button-primary"> Schedule </a>&nbsp;&nbsp;
     		</fieldset>
	    </form>
	</div>
<script type="text/javascript" src="<?php echo MORSEL_PLUGIN_ADMIN_ASSEST.'datetimepicker/bootstrap/js/bootstrap-datetimepicker.js'?>" charset="UTF-8"></script>

<script type="text/javascript">
		jQuery("#morsel_schedule").click(function(){
		//alert("save Schedule");
		if(jQuery("#scheduleDataMorsel").val() == ""){
			alert("Please Select Data & Time");
			return;
		}
		// console.log(jQuery("#gmt").val().substring(25, 505));
		jQuery.ajax({
			url:"<?php echo MORSEL_API_URL?>"+"morsels/"+jQuery('#schedualMorsel').val(),
			type:"PUT",
			data:{
	   			morsel:{
	   				schedual_date:jQuery("#scheduleDataMorsel").val(),
	   				gmt : jQuery("#gmt").val().substring(25, 505)
	   			},
	   			api_key:"<?php echo $api_key ?>"
			},
			success: function(response) {
              console.log("response--schduling---------",response.data.schedual_date);
              window.location.reload(true);
            },error:function(){
				alert("Please add Keyword first.");
			},complete:function(){}   
	    });
	})
    jQuery('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1,
        zIndex: 999999999,
        startDate: new Date() 
    });

// for gmt
    var d = new Date();
    k = d.setUTCDate(8);
    document.getElementById("gmt").value = d;
</script>