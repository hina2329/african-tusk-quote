jQuery(document).ready(function() {

    // Image upload field for products
    jQuery('.upload_image_button').live('click', function() {
        formfield = jQuery(this).prev('.fab_img');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });
    
    window.send_to_editor = function (html) {
        imgurl = jQuery('img', html).attr('src');
        jQuery(formfield).val(imgurl);
        tb_remove();
    };

    // Add multi fabric fields
    jQuery('.atq-plus-fields').click(function () {
        var fieldset = jQuery('.atq-multi-fields-container .screen-reader-text').clone();
        var cloned_fieldset = fieldset.removeClass('screen-reader-text');
        jQuery('.atq-multi-fields-container').prepend(cloned_fieldset);

        return false;
    });
    
    // Remove fields
    jQuery('.atq-no-fields').live('click', function(){
        jQuery(this).parent('.fab-color').remove();
    });
    
});