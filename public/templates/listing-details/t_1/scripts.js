if (document.getElementById("ws-walkscore-tile")) {
    var ws_wsid = document.getElementById("ws_wsid").value;
    var ws_lat = document.getElementById("ws_lat").value;
    var ws_lon = document.getElementById("ws_lon").value;
    var ws_address = document.getElementById("ws_address").value;
    var ws_format = 'wide';
    var ws_height = '525';
    var ws_width = '100%';
}
var aretkcaptcha_onLoad = function () {
    var inheritFromDataAttr = true;
    widgetId = grecaptcha.render('aretk_listcontact_captcha', {
        'callback': aretk_onUserVerified,
    }, inheritFromDataAttr);
};
var aretk_onUserVerified = function (token) {
    aretk_process_contactform();
};

jQuery(document).ready(function () {
    var listingimage_count = jQuery("#lisiting_image_count").val();
    var geocoded_latitude = jQuery("#geocoded_latitude").val();
    var geocoded_longitude = jQuery("#geocoded_longitude").val();
    var property_pov_heading = jQuery("#geocoded_pov_heading").val();
    var property_pov_pitch = jQuery("#geocoded_pov_pitch").val();
    var property_pov_zoom = jQuery("#geocoded_pov_zoom").val();
    if (jQuery("#gallery-1").length != 0) {
        if (listingimage_count > 2) {
            jQuery('#gallery-1').royalSlider({
                fullscreen: {enabled: true, nativeFS: true},
                controlNavigation: 'thumbnails',
                autoScaleSlider: true,
                autoScaleSliderWidth: 960,
                autoScaleSliderHeight: 820,
                loop: false,
                imageScaleMode: 'fit',  // "fill", "fit", "fit-if-smaller" or "none"
                navigateByClick: true,
                numImagesToPreload: 2,
                arrowsNav: true,
                arrowsNavAutoHide: true,
                arrowsNavHideOnTouch: true,
                keyboardNavEnabled: true,
                fadeinLoadedSlide: true,
                globalCaption: false,
                globalCaptionInside: false,
                imageScalePadding: 0,
                thumbs: {appendSpan: true, firstMargin: true, paddingBottom: 4}
            });
        } else {
            jQuery('#gallery-1').royalSlider({
                fullscreen: {enabled: true, nativeFS: true}
            });
        }
    }

    function initialize() {
        var latlongProperty = new google.maps.LatLng(geocoded_latitude, geocoded_longitude);
        var map = new google.maps.Map(document.getElementById('mapPropertyDetail'), {
            center: latlongProperty,
            mapTypeControl: false,
            scrollwheel: false,
            draggable: true,
            zoom: 14
        });
        if (jQuery.isNumeric(property_pov_heading) && jQuery.isNumeric(property_pov_pitch) && jQuery.isNumeric(property_pov_zoom)) {
            var panorama = new google.maps.StreetViewPanorama(document.getElementById('mapPropertyDetailpano'), {
                position: latlongProperty,
                pov: {
                    heading: parseFloat(property_pov_heading),
                    pitch: parseFloat(property_pov_pitch),
                    zoom: parseInt(property_pov_zoom)
                }
            });
            map.setStreetView(panorama);
            document.getElementById("mapPropertyDetailpano").style.display = "block";
        } else {
            var streetViewService = new google.maps.StreetViewService();
            var STREETVIEW_MAX_DISTANCE = 50;
            streetViewService.getPanoramaByLocation(latlongProperty, STREETVIEW_MAX_DISTANCE, function (streetViewPanoramaData, status) {
                if (status === google.maps.StreetViewStatus.OK) {
                    function svcheck() {
                        var panoramaOptions = {
                            position: latlongProperty,
                            pov: {heading: 34, pitch: 10},
                            visible: true
                        }
                        document.getElementById("mapPropertyDetailpano").style.display = "block";
                        var panorama = new google.maps.StreetViewPanorama(document.getElementById('mapPropertyDetailpano'), panoramaOptions);
                        map.setStreetView(panorama);
                    }

                    svcheck();
                }
            });
        }
        var marker = new google.maps.Marker({
            position: latlongProperty,
            map: map,
            scrollwheel: false,
            title: ''
        });
    }

    jQuery("#crea_send_listing_contact_form").click(function (e) {
        e.preventDefault();
        aretk_process_contactform();
    });

    initialize();
});

function aretk_process_contactform() {
    var check_valid_email_name = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
    var get_contact_first_agent_id = jQuery('#crea_listing_contact_first_agent_id').val();
    var get_contact_name = jQuery('#crea_contact_details_agent_name').val();
    var get_contact_email = jQuery('#crea_contact_details_agent_email').val();
    var get_contact_phone = jQuery('#crea_contact_details_agent_phone').val();
    var get_contact_message = jQuery('#crea_contact_details_agent_message').val();
    var get_contact_message2 = jQuery('#crea_contact_details_agent_message2').val();
    var listing_api_url = jQuery('#lisiting_api_contact_product_detail_link').val();
    var captcha_varification_code = jQuery('#captcha_varification_code').val();
    var get_contact_page_url = jQuery('#crea_listing_contact_page_url').val();
    var contact_us_agent_email = jQuery('#contact_us_agents_mail').val();
    if (get_contact_name == '') {
        jQuery('.crea_valid_name_or_not_null').css('display', 'block').delay(4000).fadeOut('slow');
    }
    if (get_contact_email == '') {
        jQuery('.crea_valid_email_or_not_null').css('display', 'block').delay(4000).fadeOut('slow');
    }
    if (get_contact_email != '' && !check_valid_email_name.test(get_contact_email)) {
        jQuery('.crea_valid_email_or_not_null').css('display', 'block').delay(4000).fadeOut('slow');
    }
    if (get_contact_message == '') {
        jQuery('.crea_valid_message_or_not_null').css('display', 'block').delay(4000).fadeOut('slow');
    }
    if (get_contact_name != '' && check_valid_email_name.test(get_contact_email) && get_contact_message != '' && get_contact_message2 == '') {
        jQuery('body.aretk .contact-detail .loader').css({"display": "block"});
        if (jQuery("#aretk_listcontact_captcha").length != 0) {
            var token = window.grecaptcha.getResponse(widgetId);
            if (!token) {
                window.grecaptcha.execute(widgetId);
                return;
            }
        }
        jQuery.ajax({
            type: "POST",
            url: adminajaxjs.adminajaxjsurl,
            async: false,
            data: ({
                action: 'property_listing_contact_form',
                'g-recaptcha-response': token,
                get_contact_first_agent_id: get_contact_first_agent_id,
                get_contact_name: get_contact_name,
                get_contact_email: get_contact_email,
                get_contact_phone: get_contact_phone,
                get_contact_message: get_contact_message,
                get_contact_page_url: get_contact_page_url,
                listing_api_url: listing_api_url,
                contact_us_agent_email: contact_us_agent_email
            }),
            success: function (data) {
                var obj = jQuery.parseJSON(data);
                jQuery('body.aretk .contact-detail .loader').css({"display": "none"});
                if ('sucessfullyadded' === obj.status) {
                    jQuery('#crea_contact_details_agent_name').val('');
                    jQuery('#crea_contact_details_agent_email').val('');
                    jQuery('#crea_contact_details_agent_phone').val('');
                    jQuery('#captcha_varification_code').val('');
                    jQuery('.sucessfullyaddedrecords').css('display', 'inline-block').delay('3000').fadeOut('slow');
                } else {
                    jQuery('.noaddedrecords').text(obj.errors).css('display', 'inline-block').delay('4000').fadeOut('slow');
                }
                grecaptcha.reset(widgetId);
            }
        });
    }
}