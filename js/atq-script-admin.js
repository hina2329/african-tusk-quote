jQuery(document).ready(function () {

    // Image upload field for fabric and products
    jQuery('.upload_image_button').live('click', function () {
        formfield = jQuery(this).prev('.fab_img');
        formfield_product = jQuery(this).prev('.prod_image');

        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    window.send_to_editor = function (html) {
        imgurl = jQuery('img', html).attr('src');
        jQuery(formfield).val(imgurl);
        jQuery(formfield_product).val(imgurl);
        tb_remove();
    };

    // Add multi fabric fields
    jQuery('.add-color').click(function () {
        var fieldset = jQuery('#atq-wrap .screen-reader-text').clone();
        var cloned_fieldset = fieldset.removeClass('screen-reader-text');
        jQuery('.atq-multi-fields-container').append(cloned_fieldset);

        return false;
    });

    // Remove fields
    jQuery('.remove-color').live('click', function () {
        jQuery(this).parent('.fab-color').remove();

        return false;
    });

});