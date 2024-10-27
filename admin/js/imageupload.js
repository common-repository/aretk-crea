var abc = 0;
jQuery(document).ready(function () {
    /*
     jQuery('#edit_page_upload_image_ajax').click(function(e)
     {
     var name = jQuery(":file").val();
     if (!name) {
     alert("First Image Must Be Selected");
     e.preventDefault();
     }
     });
     */
    jQuery('.reorder_link').on('click', function () {
        jQuery('.gallery .reorder-photos-list .ui-sortable-handle').removeClass('delete-icon');
        jQuery("ul.reorder-photos-list").sortable({tolerance: 'pointer'});
        jQuery('.reorder_link').html('save reordering');
        jQuery('.reorder_link').attr("id", "save_reorder");
        jQuery('#reorder-helper').slideDown('slow');
        jQuery('.image_link').attr("href", "javascript:void(0);");
        jQuery('.image_link').css("cursor", "move");
        jQuery("#save_reorder").click(function (e) {
            if (!jQuery("#save_reorder i").length) {
                jQuery(this).html('').prepend('<img src="' + refreshicon.refreshurl + '"/>');
                jQuery("ul.reorder-photos-list").sortable('destroy');
                jQuery("#reorder-helper").html("Reordering Photos - This could take a moment. Please don't navigate away from this page.").removeClass('light_box').addClass('notice notice_error');
                var h = [];

                jQuery("ul.reorder-photos-list li").each(function () {
                    h.push(jQuery(this).attr('id').substr(9));
                });

                var pageId = jQuery('.test-images').attr('id');
                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    async: false,
                    data: ({
                        action: 'aretkcrea_update_crea_listing_images_order',
                        ids: " " + h + "",
                        pageId: pageId
                    }),
                    success: function (data) {
                        jQuery.getScript(refreshimagejs.refreshimagejsurl);
                        jQuery('.test-images').html('');
                        jQuery('.test-images').html(data);
                        //alert(data);
                    }
                });
            }
            jQuery('.gallery .reorder-photos-list .ui-sortable-handle').addClass('delete-icon');
        });
    });
});