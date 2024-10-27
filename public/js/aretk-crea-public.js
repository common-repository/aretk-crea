(function ($) {

    $(document).ready(function () {

        $('body').on('click', '#print_showcase_property', function () {
            var w = window.open('', '', 'width=1500,height=1000,resizeable,scrollbars');
            w.document.write($("#main").html());
            w.document.close(); // needed for chrome and safari
            javascript:w.print();
            w.close();
            return false;
        });

        function remove_script_input_tag(str, classname, classval) {
            var reg = /<(.|\n)*?>/g;
            if (classname == 'class') {
                var result = reg.test(str);
                if (!result == false) {
                    $('.' + classval).attr('value', '');
                }
            }
            if (classname == 'id') {
                var result = reg.test(str);
                if (!result == false) {
                    $('#' + classval).attr('value', '');
                }
            }
        }

        $('body').on('keyup', '#aretk_listing_keyword_search', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        window.fbAsyncInit = function () {
            FB.init({
                appId: 'xxxxx', status: true, cookie: true, xfbml: true
            });
        };

        (function (d, debug) {
            var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement('script');
            js.id = id;
            js.async = true;
            js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";
            ref.parentNode.insertBefore(js, ref);
        }(document, /*debug*/ false));
        function postToFeed(title, desc, url, image) {
            var obj = {
                method: 'feed',
                link: url,
                picture: 'http://www.url.com/images/' + image,
                name: title,
                description: desc
            };

            function callback(response) {
            }

            FB.ui(obj, callback);
        }

        $('.btnShare').click(function () {
            elem = $(this);
            postToFeed(elem.data('title'), elem.data('desc'), elem.prop('href'), elem.data('image'));
            return false;
        });

        //for mobile view contact form script
        $('body').on('click', '#crea_send_listing_contact_form_mobile', function () {
            var check_valid_email_name = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            var get_contact_first_agent_id = $('#crea_listing_contact_first_agent_id').val();
            var get_contact_name = $('#crea_contact_details_agent_name_mobile').val();
            var get_contact_email = $('#crea_contact_details_agent_email_mobile').val();
            var get_contact_phone = $('#crea_contact_details_agent_phone_mobile').val();
            var get_contact_message = $('#crea_contact_details_agent_message_mobile').val();
            var listing_api_url = $('#lisiting_api_contact_product_detail_link_mobile').val();
            var captcha_varification_code = $('#captcha_varification_code_for_mobile').val();
            var get_contact_page_url = $('#crea_listing_contact_page_url_mobile').val();
            if (get_contact_name == '') {
                $('.crea_valid_name_or_not_null_mobile').css('display', 'inline-block').delay(2000).fadeOut('slow');
                $('.crea_valid_name_or_not_null_mobile').css('color', 'red');
            }
            if (get_contact_email == '') {
                $('.crea_valid_email_or_not_null_mobile').css('display', 'inline-block').delay(2000).fadeOut('slow');
                $('.crea_valid_email_or_not_null_mobile').css('color', 'red');
            }
            if (get_contact_email != '' && !check_valid_email_name.test(get_contact_email)) {
                $('.crea_enter_valid_email_mobile').css('display', 'inline-block').delay(2000).fadeOut('slow');
                $('.crea_enter_valid_email_mobile').css('color', 'red');
            }
            if (get_contact_phone == '') {
                $('.crea_valid_phone_or_not_null_mobile').css('display', 'inline-block').delay(2000).fadeOut('slow');
                $('.crea_valid_phone_or_not_null_mobile').css('color', 'red');
            }
            if (get_contact_message == '') {
                $('.crea_valid_message_or_not_null_mobile').css('display', 'inline-block').delay(2000).fadeOut('slow');
                $('.crea_valid_message_or_not_null_mobile').css('color', 'red');
            }
            if (captcha_varification_code == '') {
                $('.crea_listing_captcha_for_mobile_textvalid_td').html('Recaptcha is Required').css('display', 'inline-block').delay('2000').fadeOut('slow');
                $('.crea_listing_captcha_for_mobile_textvalid_td').css('color', 'red');
            }
            if (get_contact_name != '' && check_valid_email_name.test(get_contact_email) && get_contact_phone != '' && get_contact_message != '' && captcha_varification_code != '') {

                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    async: false,
                    data: ({
                        action: 'property_listing_contact_form',
                        get_contact_first_agent_id: get_contact_first_agent_id,
                        get_contact_name: get_contact_name,
                        get_contact_email: get_contact_email,
                        get_contact_phone: get_contact_phone,
                        get_contact_message: get_contact_message,
                        get_contact_page_url: get_contact_page_url,
                        listing_api_url: listing_api_url,
                        captcha_varification_code: captcha_varification_code
                    }),
                    success: function (data) {
                        if (data === 'Your captcha code is not correct') {
                            $('.crea_listing_captcha_for_mobile_textvalid_td').html('');
                            $('.crea_listing_captcha_for_mobile_textvalid_td').html('Recaptcha is not correct').css('display', 'inline-block').delay('2000').fadeOut('slow');
                        } else {
                            $('#crea_contact_details_agent_name_mobile').val('');
                            $('#crea_contact_details_agent_email_mobile').val('');
                            $('#crea_contact_details_agent_phone_mobile').val('');
                            $('#crea_contact_details_agent_message_mobile').val('');
                            $('#captcha_varification_code_mobile').val('');
                            $('.sucessfullyaddedrecords').css('display', 'inline-block').delay('2000').fadeOut('slow');
                        }
                    }
                });
            }
        });

        $('body').on('click', '#aretk_crea_disclaimer_accept', function () {
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: ({
                    action: 'check_terms_and_condition_accept',
                }),
                success: function (data) {
                    $('#aretk_crea_disclaimer_container').css('display', 'none');
                    $('#aretk_crea_disclaimer_background').css('display', 'none');
                }
            });
        });

        $('body').on('click', '#aretk_crea_disclaimer_decline', function () {
            location.reload();
        });

        $("#crea_contact_details_agent_phone").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $(".allow_numeric").html("Allow only numeric").show().fadeOut("slow");
                return false;
            }
        });

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
        $(".se-pre-con").fadeOut("slow");


        //---------------------------
        // Listings range slider

        var hiddenMinPrice = parseInt($('#listings_search_min_price').val());
        if (isNaN(hiddenMinPrice)) {
            hiddenMinPrice = 0;
        }

        var hiddenMaxPrice = parseInt($('#listings_search_max_price').val());
        if (isNaN(hiddenMaxPrice)) {
            hiddenMaxPrice = 10000000;
        }

        var min_amount_filter = parseInt($('#min_amount').val());
        if (isNaN(min_amount_filter)) {
            min_amount_filter = hiddenMinPrice;
        }

        var max_amount_filter = parseInt($('#max_amount').val());
        if (isNaN(max_amount_filter)) {
            max_amount_filter = hiddenMaxPrice;
        }

        $("#display_amount_range").html("$" + commaSeparateNumber(min_amount_filter) + "- $" + commaSeparateNumber(max_amount_filter));

        $("#searching-slider-range").slider({
            range: true,
            min: hiddenMinPrice,
            max: hiddenMaxPrice,
            step: 1000,
            values: [min_amount_filter, max_amount_filter],
            slide: function (event, ui) {
                $("#display_amount_range").html("$" + commaSeparateNumber(ui.values[0]) + " - $" + commaSeparateNumber(ui.values[1]));
                $("#min_amount").val(ui.values[0]);
                $("#max_amount").val(ui.values[1]);
            }
        });

        if ($('body.aretk .aretk-wrap.aretk_property_search_wrap #aretk_listings_filter_property_type').length) {
            $('body.aretk .aretk-wrap.aretk_property_search_wrap #aretk_listings_filter_property_type').chosen({disable_search_threshold: 10});
            $('.aretk-wrap.aretk_property_search_wrap .chosen-container').css({'width': '100%'});
        }
		
		if ($('body.aretk .aretk-wrap.aretk_property_search_wrap #aretk_listings_filter_ownership_type').length) {
            $('body.aretk .aretk-wrap.aretk_property_search_wrap #aretk_listings_filter_ownership_type').chosen({disable_search_threshold: 10});
            $('.aretk-wrap.aretk_property_search_wrap .chosen-container').css({'width': '100%'});
        }


        // Carousel
        var minlistingcarouselshowcasename = jQuery('#minlistingcarouselshowcasename').val();
        if (minlistingcarouselshowcasename == '' || minlistingcarouselshowcasename == 'Select Min Value') {
            minlistingcarouselshowcasename = 4;
        }

        var settings = function () {
            var settings1 = {
                captions: true,
                minSlides: minlistingcarouselshowcasename,
                maxSlides: minlistingcarouselshowcasename,
                slideMargin: 10,
                //auto:true,
                //autoStart:true,
                adaptiveHeight: true,
                slideWidth: 300,
                pager: false
            };

            var settings2 = {
                captions: true,
                minSlides: 2,
                maxSlides: 2,
                slideMargin: 10,
                //auto:true,
                //autoStart:true,
                adaptiveHeight: true,
                slideWidth: 300,
                pager: false
            };

            var settings3 = {
                captions: true,
                minSlides: 1,
                maxSlides: 1,
                //auto:true,
                //autoStart:true,
                adaptiveHeight: true,
                slideWidth: 300,
                pager: false
            };

            var default_settings = {
                minSlides: 4,
                captions: true,
                maxSlides: 4,
                adaptiveHeight: true,
                slideWidth: 300,
                pager: false
            };

            var returnSizeSettings;
            var windowWidth = $(window).width();

            if (windowWidth >= 768) {
                returnSizeSettings = settings1;
            } else if (windowWidth < 768 && windowWidth >= 520) {
                returnSizeSettings = settings2;
            } else if (windowWidth < 520) {
                returnSizeSettings = settings3;
            } else {
                returnSizeSettings = default_settings;
            }
            return returnSizeSettings;
        }

        var mySlider;

        $('.bxslider').bxSlider({
            captions: true,
            auto: true,
            autoStart: true,
            mode: 'fade',
            minSlides: 2,
            maxSlides: 2,
            adaptiveHeight: true,
            slideWidth: 600,
            slideMargin: 10
        });
        $('.bxslider_custom_search').bxSlider({
            captions: true,
            auto: true,
            autoStart: true,
            mode: 'fade',
            minSlides: 2,
            maxSlides: 2,
            slideWidth: 600,
        });
        $('body').on('click', '.advance_search_title_inline', function (e) {
            e.preventDefault();
            $('#advance_search_content').slideToggle("fast");
            return false;
        });
    });


    function ajaxindicatorstart(text) {
        if ($('body').find('#resultLoading').attr('id') != 'resultLoading') {
            var site_path_aretk = $("#areatk_plugin_url").val();
            var loder_icon = site_path_aretk + 'public/images/ajax-loader.gif';
            $('body').append('<div id="resultLoading" style="display:none"><div><img src="' + loder_icon + '"><div>' + text + '</div></div><div class="bg"></div></div>');
        }

        $('#resultLoading').css({
            'width': '100%',
            'height': '100%',
            'position': 'fixed',
            'z-index': '10000000',
            'top': '0',
            'left': '0',
            'right': '0',
            'bottom': '0',
            'margin': 'auto'
        });

        $('#resultLoading .bg').css({
            'background': '#000000',
            'opacity': '0.7',
            'width': '100%',
            'height': '100%',
            'position': 'absolute',
            'top': '0'
        });

        $('#resultLoading>div:first').css({
            'width': '250px',
            'height': '75px',
            'text-align': 'center',
            'position': 'fixed',
            'top': '0',
            'left': '0',
            'right': '0',
            'bottom': '0',
            'margin': 'auto',
            'font-size': '16px',
            'z-index': '10',
            'color': '#ffffff'
        });

        $('#resultLoading .bg').height('100%');
        $('#resultLoading').fadeIn(300);
        $('body').css('cursor', 'wait');
    }

    function ajaxindicatorstop() {
        $('#resultLoading .bg').height('100%');
        $('#resultLoading').fadeOut(300);
        $('#resultLoading ').remove();
        $('body').css('cursor', 'default');
    }

    function find_page_number(element) {
        element.find('span').remove();
        return parseInt(element.html());
    }

    function commaSeparateNumber(val) {
        while (/(\d+)(\d{3})/.test(val.toString())) {
            val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
        }
        return val;
    }
})(jQuery);
