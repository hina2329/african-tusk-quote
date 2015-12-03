jQuery(document).ready(function ($) {
    // Instantiates the variable that holds the media library frame.
    var b29_media_library;

    // Runs when the image button is clicked.
    $('.upload_image_button').live('click', function (e) {

        // Get previous element
        formfield = $(this).prev('.img_field');

        // Prevents the default action from occuring.
        e.preventDefault();

        // If the frame already exists, re-open it.
        if (b29_media_library) {
            b29_media_library.open();
            return;
        }

        // Sets up the media library frame
        b29_media_library = wp.media.frames.b29_media_library = wp.media({
            title: b29_lib.title,
            button: {text: b29_lib.button},
            library: {type: 'image'}
        });

        // Runs when an image is selected.
        b29_media_library.on('select', function () {

            // Grabs the attachment selection and creates a JSON representation of the model.
            var media_attachment = b29_media_library.state().get('selection').first().toJSON();

            // Sends the attachment URL to our custom image input field.
            $(formfield).val(media_attachment.url);
        });

        // Opens the media library frame.
        b29_media_library.open();
    });

    // Add multi fabric fields
    $('.add-fields').click(function () {
        var fieldset = $('.cloner .multi-fields').clone();
        var cloned_fieldset = fieldset.removeClass('screen-reader-text');
        $('.atq-multi-fields-container').append(cloned_fieldset);

        return false;
    });
    $('.add-fabric').click(function () {
        var fieldset = $('.cloner .multi-fields-fab-price').clone();
        var cloned_fieldset = fieldset.removeClass('screen-reader-text');
        $('.fabric-list').append(cloned_fieldset);

        return false;
    });
    

    // Remove fields
    $('.remove-fields').live('click', function () {
        $(this).parent('.multi-fields').remove();

        return false;
    });

    // Search
    $(".quote-item-search input.prod-name").keyup(function () {
        var filter = $(this).val();
        if (!filter) {
            $(".quote-item-search ul li").hide();
            return;
        }
        var regex = new RegExp(filter, "i");
        $(".quote-item-search ul li").each(function () {

            if ($(this).text().search(regex) < 0) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
    
    $(".quote-item-search ul li").click(function () {
        var prod_text = $(this).text();
        var prod_id = $(this).data('prod-id');
        $(".quote-item-search input.prod-name").val(prod_text);
        $(".quote-item-search input.prod-id").val(prod_id);
        $(this).hide();
        return false;
    });
    
    $(".client-holder").keyup(function () {
        var filter = $(this).val();
        if (!filter) {
            $(".client-list ul li").hide();
            return;
        }
        var regex = new RegExp(filter, "i");
        $(".client-list ul li").each(function () {

            if ($(this).text().search(regex) < 0) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });
    
    $(".client-list ul li").click(function () {
        var client_text = $(this).text();
        var client_id = $(this).data('client-id');
        $(".client-holder").val(client_text);
        $(".quote-client").val(client_id);
        $(this).hide();
        return false;
    });

    // Remove fabric and price from product
    $('.remove-fab').live('click', function () {
        $(this).parents('.multi-fields-fab-price').remove();

        return false;
    });

    // Quote items calculation
    $('.fabric-type').on('change', function () {
        var fab = $(this).val();
        var qty = $(this).parents('tr').find('.item-qty').val();
        var colors = $('option:selected', this).data('fab-id');
        $(this).parents('tr').find('.unit-price').val(fab);
        var unit = $(this).parents('tr').find('.unit-price').val();
        $(this).parents('tr').find('.sub-total').val(qty * unit);
        $(this).parents('tr').find('.item-fab-colors').hide();
        $(this).parents('tr').find(colors).show();
    });
    $('.item-qty').on('keyup', function () {
        var qty = $(this).val();
        var unit = $(this).parents('tr').find('.unit-price').val();
        $(this).parents('tr').find('.sub-total').val(qty * unit);
    });
    
    // Add heading quote
    $('.add-heading').click(function(){
        var quote_id = $('#quote_id').val();
        var heading = $('#add_heading').val();
        var data = {
          'action' : 'add_heading',
          'add_heading' : heading,
          'quote_id' : quote_id
        };
        
        $('#update-msg').show();
        
        $.post(ajaxurl, data, function(result){
            $('#add_heading').val('');
            $('#the-list').append(result);
            $('#update-msg').hide();
        });
    });
    

});
