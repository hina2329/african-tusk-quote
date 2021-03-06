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
    $('.fabric-type').live('change', function () {
        var fab = $(this).find(':selected').data('fab-price');
        var qty = $(this).parents('tr').find('.item-qty').val();
        var colors = $('option:selected', this).val();
        $(this).parents('tr').find('.unit-price').val(fab);
        var unit = $(this).parents('tr').find('.unit-price').val();
        $(this).parents('tr').find('.sub-total').val(qty * unit);
        $(this).parents('tr').find('.item-fab-colors').hide();
        $(this).parents('tr').find('#color-set-' + colors).show();

        // Grand total
        grand_total();

    });
    $('.item-qty').live('keyup', function () {
        var qty = $(this).val();
        var unit = $(this).parents('tr').find('.unit-price').val();
        $(this).parents('tr').find('.sub-total').val(qty * unit);

        // Grand total
        grand_total();
    });

    // Grand Total
    function grand_total() {
        // Update grand total
        var grand_total = 0;

        $('.sub-total').each(function () {
            grand_total += Number($(this).val());
        });

        $('.grand-total').val(grand_total);
    }


    // Add heading quote
    $('.add-heading').click(function () {
        var quote_id = $('#quote_id').val();
        var heading = $('#add_heading').val();
        var data = {
            'action': 'add_heading',
            'add_heading': heading,
            'quote_id': quote_id
        };

        $('#update-msg').show();

        $.post(ajaxurl, data, function (result) {
            $('#add_heading').val('');
            $('#the-list').append(result);
            $('#update-msg').hide();
        });
    });


    // Delete item row
    $('.del-item-row').live('click', function () {
        var quote_id = $(this).data('quote-id');
        var item_id = $(this).data('item-id');

        var data = {
            action: 'del_item',
            qid: quote_id,
            iid: item_id
        };


        $.post(ajaxurl, data);

        var sub_total = $(this).parents('tr').find('.sub-total').val();
        var grand_total = $('.grand-total').val();

        $('.grand-total').val(grand_total - sub_total);


        $(this).parents('tr').remove();

        return false;
    });


    $('.add-sep').live('click', function () {
        var sep_id = $(this).data('sep-id');

        var data = {
            action: 'add_sep',
            sid: sep_id
        };

        $('#update-msg').show();

        $.post(ajaxurl, data, function (result) {
            $('#the-list').append(result);
            $('#update-msg').hide();
        });

        return false;
    });

    $('.add-prod').live('click', function () {

        var quote_id = $('.quote-id').val();
        var prod_id = $('.prod-id').val();
        var text_id = 'desc' + prod_id;
        var data = {
            action: 'add_prod',
            qid: quote_id,
            pid: prod_id
        };
        $('#update-msg').show();

        $.post(ajaxurl, data, function (result) {
            $('#the-list').prepend(result);
            $('#update-msg').hide();

            //var eid = 'text_id';
            //switchEditors.go(eid, 'tmce')
            //quicktags({id: eid});
            ////init tinymce
            //tinyMCEPreInit.mceInit[eid]['elements'] = eid;
            //tinyMCEPreInit.mceInit[eid]['body_class'] = eid;
            //tinyMCEPreInit.mceInit[eid]['succesful'] = false;
            //tinymce.init(tinyMCEPreInit.mceInit[eid]);

        });

        return false;
    });

    // Find Categories
    $('.add-cat').live('click', function () {
        var quote_id = $('.quote-id').val();
        var item_cat = $('.item-cat').val();
        var data = {
            action: 'find_prod',
            qid: quote_id,
            icat: item_cat
        };
        $('#the-selective-list').empty();
        $('#update-msg').show();

        $.post(ajaxurl, data, function (result) {
            $('#the-selective-list').prepend(result);
            $('#update-msg').hide();
        });

    });
    // products

    // Add products from categories to quote
    $('.add-selective').click(function () {

        var quote_id = $('.quote-id').val();

        var ids = $('.selective-prod:checked').serialize();


        var data = {
            action: 'add_sel_prod',
            prod_ids: ids,
            qid: quote_id

        };

        $('#update-msg').show();

        $.post(ajaxurl, data, function (result) {
            $('#the-list').prepend(result);
            $('#update-msg').hide();


        });

        return false;
    });

    // If changes in client section were made
    var client_change = 'saved';

    $('.client_save_ctrl input').change(function () {
        client_change = 'unsaved';
    });

    $(document).click(function () {
        if (client_change == 'unsaved') {
            alert('You have unsaved changes in client section. Please update that section before continuing!');
        }
    });
    $('.client_save_ctrl').click(function (e) {
        if (client_change == 'unsaved') {
            e.stopPropagation();
        }
    });


    // If changes in subject section were made
    var subject_change = 'saved';

    $('.subject_save_ctrl input').change(function () {
        subject_change = 'unsaved';
    });

    $(document).click(function () {
        if (subject_change == 'unsaved') {
            alert('You have unsaved changes in subject section. Please update that section before continuing!');
        }
    });
    $('.subject_save_ctrl').click(function (e) {
        if (subject_change == 'unsaved') {
            e.stopPropagation();
        }
    });


    // If changes in member section were made
    var member_change = 'saved';

    $('.member_save_ctrl select').change(function () {
        member_change = 'unsaved';
    });

    $(document).click(function () {
        if (member_change == 'unsaved') {
            alert('You have unsaved changes in member section. Please update that section before continuing!');
        }
    });
    $('.member_save_ctrl').click(function (e) {
        if (member_change == 'unsaved') {
            e.stopPropagation();
        }
    });


});
