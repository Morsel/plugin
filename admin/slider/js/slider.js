//-- SHS Admin Script --------------
//----------------------------------
function shs_add_to_field(field){
	var unique = new Date().valueOf(),
	    field_html = '<input name="cnt[]" id="addSlidesImageNew'+unique+'" style="width:70%;" class="imageUploadAdd" ></textarea>';
		field_html += "<input type=\"button\" value=\"Upload\" onclick=\"addSlidesImageNew('addSlidesImageNew"+unique+"')\">";
		field_html += "<input type=\"button\" class=\"shs_del\" title=\"Delete\" value=\"\" onClick=\"shs_delete_field(this);\"  /><input type=\"button\" class=\"shs_add\" title=\"Add new\" vlaue=\"\" onClick=\"shs_add_to_field(this);\"  />";
		jQuery(field).parent().after("<li style='display:none;'>" + field_html + "</li>");
		jQuery(field).parent().next().slideDown();
}
function shs_delete_field( field ) {
	jQuery(field).parent().slideUp('fast', function(e){ jQuery(this).parent('li').remove(); });
}
jQuery(document).ready(function() {
	jQuery( "#joptions" ).sortable();
	jQuery( "#joptions li" ).css({'cursor':'move'});
});

jQuery(document).ready(function(){
	jQuery('.shs_admin_wrapper .handlediv,.shs_admin_wrapper .hndle').click(function(){
		jQuery(this).parent().find('.inside').slideToggle("fast");
	});
});

function addSlidesImageNew(idNo){
var image = wp.media({ 
        title: 'Upload Image',
        // mutiple: true if you want to upload multiple files at once
        multiple: false
    }).open()
    .on('select', function(e){
        // This will return the selected image from the Media Uploader, the result is an object
        var uploaded_image = image.state().get('selection').first();
        // We convert uploaded_image to a JSON object to make accessing it easier
        // Output to the console uploaded_image
        //console.log(uploaded_image);
        var image_url = uploaded_image.toJSON().url;
        // Let's assign the url value to the input field
        jQuery('#'+idNo).val(image_url);
	});
}