var aretkcaptcha_onLoad_bfform = function () {
    var inheritFromDataAttr = true;
    widgetId = grecaptcha.render('aretk_bfform_captcha', {
        'callback': aretk_bfform_onUserVerified,
    }, inheritFromDataAttr);
};
var aretk_bfform_onUserVerified = function (token) {
    aretk_process_contactform();
};
function aretk_process_contactform() {
    var bf_name = jQuery('#bf_name').val();
    var bf_email = jQuery('#bf_email').val();
    var bf_phone = jQuery('#bf_phone').val();
    var bf_phone_prefer = jQuery("#bf_phone_prefer").is(':checked') ? 1 : 0;
    var bf_email_prefer = jQuery("#bf_email_prefer").is(':checked') ? 1 : 0;
    var bf_address = jQuery('#bf_address').val();
    var bf_description = jQuery('#bf_description').val();
    var bf_description_community = jQuery('#bf_description_community').val();
    var bf_price = jQuery('#bf_price').val();
    var bf_bedroom = jQuery('#bf_bedroom').val();
    var bf_bathroom = jQuery('#bf_bathroom').val();
    var bf_planning_to_buy = jQuery('#bf_planning_to_buy').val();
    var bf_realtor = jQuery("input[name='bf_realtor']:checked").val();
    var bf_discription = jQuery('#bf_discription').val();
    var user_bf_form_captcha = jQuery('#user_bf_form_captcha').val();
    if (jQuery("#aretk_bfform_captcha").length != 0) {
        var token = window.grecaptcha.getResponse(widgetId);
        if (!token) {
            window.grecaptcha.execute(widgetId);
            return;
        }
    }

    jQuery.ajax({
        type: "POST",
        url: adminajaxjs.adminajaxjsurl,
        async: true,
        data: ({
            action: 'buyer_lead_submit_form_front_end',
            'g-recaptcha-response': token,
            bf_name: bf_name,
            bf_email: bf_email,
            bf_phone: bf_phone,
            bf_phone_prefer: bf_phone_prefer,
            bf_email_prefer: bf_email_prefer,
            bf_address: bf_address,
            bf_description: bf_description,
            bf_description_community: bf_description_community,
            bf_price: bf_price,
            bf_bedroom: bf_bedroom,
            bf_bathroom: bf_bathroom,
            bf_planning_to_buy: bf_planning_to_buy,
            bf_realtor: bf_realtor,
            user_bf_form_captcha: user_bf_form_captcha,
            bf_discription: bf_discription
        }),
        success: function (data) {
            jQuery('body.aretk .aretk_bfform img#imageloading').fadeOut('fast');
            jQuery('.msg').html('');

            if (data != '' && data != 'false') {
                jQuery('.msg').html(data);
                jQuery('.msg').fadeIn('fast').delay('10000').fadeOut('slow');
                jQuery('#bf_name').val('');
                jQuery('#bf_email').val('');
                jQuery('#bf_phone').val('');
                jQuery('#bf_phone_prefer').val('');
                jQuery('#bf_email_prefer').val('');
                jQuery('#bf_address').val('');
                jQuery('#bf_description').val('');
                jQuery('#bf_description_community').val('');
                jQuery('#bf_price').val('');
                jQuery('#bf_bedroom').val('');
                jQuery('#bf_bathroom').val('');
                jQuery('#bf_planning_to_buy').val('');
                jQuery('#bf_realtor_yes').val('');
                jQuery('#bf_realtor_no').val('');
                jQuery('#bf_discription').val('');
                jQuery('#user_bf_form_captcha').val();
            }
        }
    });
    return false;
}
(function ($) {
    jQuery(".aretk_bfform").validate({
        rules: {
            bf_name: {
                required: true,
            },
            bf_email: {
                required: true,
                email: true
            },
            bf_Bedroom: {
                required: true,
            },
            bf_Bathroom: {
                required: true,
            },
            user_bf_form_captcha: {
                required: true
            },
            bf_planning_to_buy: {
                required: true,
            },
            'bf_Preferred[]': {required: true},
            'bf_realtor': {required: true},
        },
        // Specify the validation error messages
        messages: {
            bf_email: {
                required: "Email is Required",
                email: "Email is Not valid"
            },
            bf_name: {
                required: "Name is Required",
            },
            bf_Bedroom: {
                required: "Bedroom is Required",
            },
            bf_Bathroom: {
                required: "Bathroom  is Required",
            },
            user_bf_form_captcha: {
                required: "Captcha  is Required"
            },
            bf_planning_to_buy: {
                required: "This feild is Required",
            },
        },
        submitHandler: function (form) {
            jQuery('body.aretk .aretk_bfform img#imageloading').fadeIn('fast');
            aretk_process_contactform();
        }
    });

    //contact form validation script
    jQuery(".aretk_contact_form").validate({
        rules: {
            user_email: {
                required: true,
                email: true
            },
            user_name: {
                required: true,
            },
            user_phone: {
                required: true,
            },
            user_captcha: {
                required: true,
            },
            discription: "required"
        },
        // Specify the validation error messages
        messages: {
            user_email: {
                required: "Email is Required",
                email: "Email is Not valid"
            },
            user_name: {
                required: "Name is Required",
            },
            user_phone: {
                required: "Phone No is Required",
            },
            user_captcha: {
                required: "Captcha is Required",
            },
            discription: "Message is Required"
        },
        submitHandler: function (form) {

            var user_name = $('#user_name').val();
            var user_email = $('#user_email').val();
            var user_phone = $('#user_phone').val();
            var discription = $('#discription').val();
            var user_captcha = $('#user_contact_captcha').val();

            jQuery.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: ({
                    action: 'conatact_submit_form_front_end',
                    user_name: user_name,
                    user_email: user_email,
                    user_phone: user_phone,
                    user_captcha: user_captcha,
                    discription: discription
                }),
                success: function (data) {
                    $('.contact_form_msg').html('');
                    if (data == 'false') {
                        $('.captcha_contact_validation_message').html('Your Captcha code is not correct').css('color', 'red').delay('2000').fadeOut('slow');
                        return false;
                    }
                    if (data != '' && data != 'false') {
                        $('.aretk_contact_form').html(data);
                        //$('.contact_form_msg').html(data).delay('2000').fadeOut('slow');
                        $('#user_name').val('');
                        $('#user_email').val('');
                        $('#user_phone').val('');
                        $('#discription').val('');
                        $('#user_contact_captcha').val('');
                    }
                }
            });
            return false;
        }
    });

    //seller form validation script
    jQuery(".aretk_sfform").validate({
        rules: {
            sfform_name: {
                required: true,
            },
            sfform_email: {
                required: true,
                email: true
            },
            sfform_bedroom: {
                required: true,
            },
            sfform_bathroom: {
                required: true,
            },
            user_seller_captcha: {
                required: true,
            },
            sfform_planning_to_buy: {
                required: true
            },
            'sf_Preferred[]': {required: true},
            'sf_realtor': {required: true},
        },
        // Specify the validation error messages
        messages: {
            sfform_email: {
                required: "Email is Required",
                email: "Email is Not valid"
            },
            sfform_name: {
                required: "Name is Required",
            },
            sfform_bedroom: {
                required: "Bedroom is Required",
            },
            sfform_bathroom: {
                required: "Bathroom  is Required",
            },
            user_seller_captcha: {
                required: "Captcha is Required",
            },
            sfform_planning_to_buy: {
                required: "This feild is Required",
            },
        },
        submitHandler: function (form) {
            var sfform_name = $('#sfform_name').val();
            var sfform_email = $('#sfform_email').val();
            var sfform_phone = $('#sfform_phone').val();
            var sf_preferred_phone = $("#sf_preferred_phone").is(':checked') ? 1 : 0;
            var sf_preferred_email = $("#sf_preferred_email").is(':checked') ? 1 : 0;
            var sfform_address = $('#sfform_address').val();
            var sfform_description = $('#sfform_description').val();
            var sfform_square_feet = $('#sfform_square_feet').val();
            var sfform_bedroom = $('#sfform_bedroom').val();
            var sfform_bathroom = $('#sfform_bathroom').val();
            var sfform_planning_to_buy = $('#sfform_planning_to_buy').val();
            var sf_realtor = $("input[name='sf_realtor']:checked").val();
            var discription = $('#discription').val();
            var user_seller_captcha = $('#user_seller_captcha').val();

            jQuery.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: ({
                    action: 'seller_lead_submit_form_front_end',
                    sfform_name: sfform_name,
                    sfform_email: sfform_email,
                    sfform_phone: sfform_phone,
                    sf_preferred_phone: sf_preferred_phone,
                    sf_preferred_email: sf_preferred_email,
                    sfform_address: sfform_address,
                    sfform_description: sfform_description,
                    sfform_square_feet: sfform_square_feet,
                    sfform_bedroom: sfform_bedroom,
                    sfform_bathroom: sfform_bathroom,
                    sfform_planning_to_buy: sfform_planning_to_buy,
                    sf_realtor: sf_realtor,
                    discription: discription,
                    user_seller_captcha: user_seller_captcha
                }),
                success: function (data) {
                    $('.sellermsg').html('');
                    if (data === 'false') {
                        $('.captcha_seller_validation_message').html('Your Captcha code is not correct').css('color', 'red').delay('2000').fadeOut('slow');
                    }

                    if (data != ' ' && data != 'false') {
                        $('.aretk_sfform').html(data);
                        //$('.sellermsg').html(data).delay('2000').fadeOut('slow');
                        $('#sfform_name').val('');
                        $('#sfform_email').val('');
                        $('#sfform_phone').val('');
                        $('#sf_preferred_phone').val('');
                        $('#sf_preferred_email').val('');
                        $('#sfform_address').val('');
                        $('#sfform_description').val('');
                        $('#sfform_square_feet').val('');
                        $('#sfform_bedroom').val('');
                        $('#sfform_bathroom').val('');
                        $('#sfform_planning_to_buy').val('');
                        $('#sf_realtor_yes').val('');
                        $('#sf_realtor_no').val('');
                        $('#discription').val('');
                        $('#user_seller_captcha').val('');
                    }
                }
            });
            return false;
        }
    });
    // contact form validation listing page
    $('.showmenu_listing').click(function () {
        $(this).parent().find(".menu").slideToggle("fast");
    });

    $('.showmenu').click(function () {
        $(this).parent().find(".menu").slideToggle("fast");
    });

    $("#set_property_showcasetable_id").on("click", ".showmenu_listing", function () {
        $(this).parent().find(".menu").slideToggle("fast");
    });

    $("#custom_search_set_property_showcasetable_id").on("click", ".showmenu_listing", function () {
        $(this).parent().find(".menu").slideToggle("fast");
    });

})(jQuery);