(function ($) {
    $(window).load(function () {
        var ajaxurl = adminajaxjs.adminajaxjsurl;

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

        // Create/Edit Listing - map location
        $('body.real-estate_page_create_new_listings').on('click', '.google-map-open', function () {
            var infoWindow;
            var latlngbounds;
            var geocoder;
            var mapProp;
            var myLatlng;
            var marker;
            var lat, lng, address;
            var map;
            var latitude = $('body.real-estate_page_create_new_listings #crea_google_map_latitude').val();
            var longitude = $('body.real-estate_page_create_new_listings #crea_google_map_longitude').val();

            //if(latitude){
                initialize_map_createnewlisting(latitude, longitude);    
            //}

            
        });

        // Map settings for create/edit map showcase
        $('body.real-estate_page_create_new_showcase').on('click', 'a.crea_showcase_setting_tabs', function () {
            aretkcrea_initialize_map_showcase_map();
        });

        // Map Filter for create/edit map showcase
        $('body.real-estate_page_create_new_showcase').on('click', 'a#crea_showcase_filter_button_tab', function () {
            aretkcrea_initialize_showcase_filter_map();
        });
        /*
         $('body.real-estate_page_create_new_showcase').on('change', '#crea_filter_by_map_km', function () {
         radius_km = $(this).val();
         radius_m = radius_km * 1000;
         circle.setOptions({ radius: radius_m });
         });
         */
        function initialize_map_createnewlisting(latitude, longitude) {
            var latit = latitude;
            var longitudec = longitude;

            infoWindow = new google.maps.InfoWindow();
            latlngbounds = new google.maps.LatLngBounds();
            geocoder = new google.maps.Geocoder();

            mapProp = {
                center: new google.maps.LatLng(latit, longitudec),
                zoom: 5,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false
            };

            map = new google.maps.Map(document.getElementById("crea_location_google_maps"), mapProp);

            myLatlng = new google.maps.LatLng(latit, longitudec);
            marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                scrollwheel: false
            });

            data = '';
            (function (marker, data) {
                google.maps.event.addListener(marker, "dragend", function (e) {
                    geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            lat = marker.getPosition().lat();
                            lng = marker.getPosition().lng();
                            address = results[0].formatted_address;
                            $('#crea_google_map_latitude').val('');
                            $('#crea_google_map_latitude').val(lat);
                            $('#crea_google_map_longitude').val('');
                            $('#crea_google_map_longitude').val(lng);
                            $('#crea_google_map_geo_address').val('');
                            $('#crea_google_map_geo_address').val(address);
                            $('#crea_listing_google_map_location_txt').val('');
                            $('#crea_listing_google_map_location_txt').val(address);
                            latitude = '';
                            latitude = $('#crea_google_map_latitude').val();
                            longitude = '';
                            longitude = $('#crea_google_map_longitude').val();
                        }
                    });
                });
            })(marker, data);
            marker.setMap(map);
        }


        function aretkcrea_initialize_showcase_filter_map() {

            if ($("#crea_location_google_maps_feed").length == 0) {
                // map container does not exist
            } else {
                var mapProp;
                var Latlng;
                var marker;
                var latitude, longitude;
                var map;
                var circle;
                var radius_km;
                var radius_m;
                var map_zoom;
                latitude = $('#crea_googlemap_filter_latitude').val();
                longitude = $('#crea_googlemap_filter_longitude').val();
                map_zoom = parseInt($('#showcase_filter_google_map_zoom').val());
                radius_km = $('#crea_filter_by_map_km').val();

                Latlng = new google.maps.LatLng(latitude, longitude);
                mapProp = {
                    center: Latlng,
                    zoom: map_zoom,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    scrollwheel: false
                };
                map = new google.maps.Map(document.getElementById("crea_location_google_maps_feed"), mapProp);
                marker = new google.maps.Marker({
                    map: map,
                    position: Latlng,
                    draggable: true
                });
                radius_m = radius_km * 1000;
                circle = new google.maps.Circle({
                    map: map,
                    radius: radius_m,
                    fillColor: '#0000FF',
                    fillOpacity: 0.4,
                    strokeColor: "#0000FF",
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    center: Latlng
                });
                marker.addListener('drag', function (event) {
                    circle.setOptions({
                        center: {
                            lat: event.latLng.lat(),
                            lng: event.latLng.lng()
                        }
                    });
                    $('#crea_googlemap_filter_latitude').val(event.latLng.lat());
                    $('#crea_googlemap_filter_longitude').val(event.latLng.lng());
                });
                map.addListener('zoom_changed', function () {
                    zoomLevel = map.getZoom();
                    $('#showcase_filter_google_map_zoom').attr('value', zoomLevel);
                });
                $('body').on('change', '#crea_filter_by_map_km', function () {
                    radius_km = $(this).val();
                    radius_m = radius_km * 1000;
                    circle.setOptions({radius: radius_m});
                });
            }
        }

        function aretkcrea_initialize_map_showcase_map() {
            var latitude_results = $('#crea_googlemap_showcase_latitude_results').val();
            var longitude_results = $('#crea_googlemap_showcase_longitude_results').val();
            var zoom_level = parseInt(jQuery('#crea_googlemap_showcase_zoom_results').val());
            var myLatlng = new google.maps.LatLng(latitude_results, longitude_results);
            var mapProp = {
                center: myLatlng,
                zoom: zoom_level,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false
            };
            var map = new google.maps.Map(document.getElementById("crea_location_google_maps_showcase"), mapProp);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                scrollwheel: false
            });
            marker.setMap(map);
            map.addListener('zoom_changed', function () {
                zoomLevel = map.getZoom();
                $('#crea_googlemap_showcase_zoom_results').val(zoomLevel);
            });
            marker.addListener('drag', function (event) {
                $('#crea_googlemap_showcase_latitude_results').val(event.latLng.lat());
                $('#crea_googlemap_showcase_longitude_results').val(event.latLng.lng());
            });
        }


        //remove tag for subscription settings page
        $('body').on('keyup', '.crea-api-key-values', function () {
            var string = $(this).val();
            var classname = 'class';
            var classval = 'crea-api-key-values';
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove tag for ddf setting page
        $('body').on('keyup', '#aretk_crea_user_name_one', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#aretk_crea_user_name_two', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#aretk_crea_user_name_three', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#aretk_crea_user_name_four', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#aretk_crea_user_name_five', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove tag for create new user input
        $('body').on('keyup', '.crea_set_input', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });


        //remove script tag in listing address
        $('body').on('keyup', '#agent_listing_tab_address', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            var string = string.replace('/', '-');
            $(this).val(string);
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove script tag for city
        $('body').on('keyup', '#agent_listing_tab_city', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            var string = string.replace('/', '-');
            $(this).val(string);
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove script tag for province
        $('body').on('keyup', '#agent_listing_tab_Province', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            var string = string.replace('/', '-');
            $(this).val(string);
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove script tag for price
        $('body').on('keyup', '#agent_listing_tab_price', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove script tag for price
        $('body').on('keyup', '#crea_listing_agent_discription', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove script tag for virtual tour
        $('body').on('keyup', '#crea_listing_virtual_tour_url', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove script tag for utility
        $('body').on('keyup', '.check_utitlity_values', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove script tag for features
        $('body').on('keyup', '.crea_listing_feature_input', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove script tag for map location address
        $('body').on('keyup', '#crea_listing_google_map_location_txt', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove tag for showcase page
        $('body').on('keyup', '#crea_showcase_post_title', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#crea_googlemap_filter_latitude', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#crea_googlemap_filter_longitude', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove tags for settings page
        $('body').on('keyup', '#walk-score-api-name', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#google-map-api-name', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //remove tags for lead page
        $('body').on('keyup', '#create_lead_name', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '.crea_multiple_email_p_tag .email_add', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#crea_lead_company_id', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#crea_lead_address_id', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#crea_lead_province_id', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#crea_lead_city_id', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#crea_lead_country_id', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '.create-new-lead-social-url', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#crea_lead_textare_comment', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '#crea_new_corrsponding_area_content_box', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            return false;
        });

        //reminder remove script tags
        $('body').on('keyup', '.crea_lead_reminder_title_text', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '.crea_lead_reminder_subject_text', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '.crea_lead_reminder_email_text', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '.crea_lead_reminder_comment_text', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        $('body').on('keyup', '.crea_lead_reminder_datetime_text', function () {
            var string = $(this).val();
            var classname = 'id';
            var classval = $(this).attr('id');
            remove_script_input_tag(string, classname, classval);
            return false;
        });

        //listing details preview settings
        $('body').on('click', '#crea_listing_showcase_preview', function () {
            var incAgentInfo = $('input[name="include_information"][type="radio"]:checked').val();
            var incContactForm = $('input[name="include_contact_form"][type="radio"]:checked').val();
            var incMap = $('input[name="include_map"][type="radio"]:checked').val();
            var incWalkScore = $('input[name="include_walk_score"][type="radio"]:checked').val();
            var incPrintBtn = $('input[name="include_print_button"][type="radio"]:checked').val();
            var incEmailAddressofAgents = $('input[name="include_email_address_of_agent"][type="radio"]:checked').val();
            
            var priceTextColor = $('#crea_listing_price_color_id').val();
            var sendBtnColor = $('#crea_listing_send_btn_color_id').val();
            
            if (incEmailAddressofAgents === 'Yes') {
                $('.agents_email_display_or_not').css('display', 'block');
            } else {
                $('.agents_email_display_or_not').css('display', 'none');
            }
            if (incAgentInfo == 'Yes') {
                $('.author-detail-block').css('display', 'block');
            } else {
                $('.author-detail-block').css('display', 'none');
            }
            if (incContactForm == 'Yes') {
                $('.contact-block').css('display', 'block');
            } else {
                $('.contact-block').css('display', 'none');
            }
            if (incMap == 'Yes') {
                $('.slider-image').css('display', 'block');
            } else {
                $('.slider-image').css('display', 'none');
            }
            if (incWalkScore == 'Yes') {
                $('.custom_walkscore_bar_acco').css('display', 'block');
            } else {
                $('.custom_walkscore_bar_acco').css('display', 'none');
            }
            if (incPrintBtn == 'Yes') {
                $('.print').css('display', 'inline-block');
            } else {
                $('.print').css('display', 'none');
            }
            
            $('.listing_details_price').css('color', '#' + priceTextColor);
            $('#crea_send_listing_contact_form').css('background-color', '#' + sendBtnColor);
        });


        // copy to clipboard set
        var clipboard = new Clipboard('.btn');
        clipboard.on('success', function (e) {
            console.info('Action:', e.action);
            console.info('Text:', e.text);
            console.info('Trigger:', e.trigger);
            e.clearSelection();
        });
        clipboard.on('error', function (e) {
            console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);
        });

        $('body').on('click', '#showcase_searching_preview_tab', function () {
            // get showcase searching preview settings values
            var searchExcludeFieldAll = $('input[name="search_exclude_field_all"][type="checkbox"]:checked').val();
            var search_exclude_field_property_type = $('input[name="search_exclude_field_property_type"][type="checkbox"]:checked').val();
			var search_exclude_field_ownership_type = $('input[name="search_exclude_field_ownership_type"][type="checkbox"]:checked').val();
            var search_exclude_field_structure = $('input[name="search_exclude_field_structure"][type="checkbox"]:checked').val();
            var search_exclude_field_status = $('input[name="search_exclude_field_status"][type="checkbox"]:checked').val();
            var search_exclude_field_Price = $('input[name="search_exclude_field_Price"][type="checkbox"]:checked').val();
            var search_exclude_field_bedrooms = $('input[name="search_exclude_field_bedrooms"][type="checkbox"]:checked').val();
            var search_exclude_field_bathrooms_full = $('input[name="search_exclude_field_bathrooms_full"][type="checkbox"]:checked').val();
            var search_exclude_field_bathrooms_partial = $('input[name="search_exclude_field_bathrooms_partial"][type="checkbox"]:checked').val();
            var search_exclude_field_finished_basement = $('input[name="search_exclude_field_finished_basement"][type="checkbox"]:checked').val();
            var search_exclude_field_select_city = $('input[name="search_exclude_field_select_city"][type="checkbox"]:checked').val();
            var max_price_search_field_results = '';
            var max_price_search_field = $('#search_max_price_slider_range').val();
            if (max_price_search_field != '') {
                max_price_search_field_results = max_price_search_field;
            }
            $('#listing_searching_max_hidden_price').val(max_price_search_field_results);
            var hiddenMaxPrice = $('#listing_searching_max_hidden_price').val();
            $("#display_amount_range").html("$0 - $" + hiddenMaxPrice);
            $("#searching-slider-range").slider({
                range: true,
                min: 0,
                max: hiddenMaxPrice,
                values: [0, hiddenMaxPrice],
                slide: function (event, ui) {
                    $("#display_amount_range").html("$" + ui.values[0] + " - $" + ui.values[1]);
                    $("#min_amount").val(ui.values[0]);
                    $("#max_amount").val(ui.values[1]);
                }
            });

            //get search color code
            var search_btn_color = $('#crea_search_detail_button_color_id').val();
            var advance_search_title_color = $('#crea_search_detail_title_color_id').val();
            $('#crea_showcase_searching_btn').css('background-color', '#' + search_btn_color);
            $('.advance_search_title_inline').css('color', '#' + advance_search_title_color);
            $('#listing_searching_max_hidden_price').val(max_price_search_field_results);
            if (searchExcludeFieldAll == 'All') {
                $('.advance_search_title_inline').css('display', 'none');
                //property type option
                $('#crea_advance_search_property_type').css('display', 'none');
                //structure type option
                $('#crea_advance_search_structure_type').css('display', 'none');
                //status type option
                $('#crea_advance_search_status').css('display', 'none');
                //price type option
                $('.crea_searching_price_slider_main').css('display', 'none');
                //bedrooms option
                $('#crea_advance_search_bedrooms').css('display', 'none');
                //full bathroom option
                $('#crea_advance_search_bathrooms').css('display', 'none');
                //bathroom partial
                $('#crea_advance_search_bathrooms_partial').css('display', 'none');
                //finished basement option
                $('#crea_advance_search_finsished_basement').css('display', 'none');
                //city option
                $('#crea_advance_search_city').css('display', 'none');
            } else {
                $('.advance_search_title_inline').css('display', 'block');
                //property type option
                $('#crea_advance_search_property_type').css('display', 'inline-block');
                //structure type option
                $('#crea_advance_search_structure_type').css('display', 'inline-block');
                //status type option
                $('#crea_advance_search_status').css('display', 'inline-block');
                //price type option
                $('.crea_searching_price_slider_main').css('display', 'block');
                //bedrooms option
                $('#crea_advance_search_bedrooms').css('display', 'inline-block');
                //full bathroom option
                $('#crea_advance_search_bathrooms').css('display', 'inline-block');
                //bathroom partial
                $('#crea_advance_search_bathrooms_partial').css('display', 'inline-block');
                //finished basement option
                $('#crea_advance_search_finsished_basement').css('display', 'inline-block');
                //city option
                $('#crea_advance_search_city').css('display', 'inline-block');
            }

            // set property type excludes
            if (search_exclude_field_property_type == 'Property Type') {
                $('#crea_advance_search_property_type').css('display', 'none');
            } else {
                $('#crea_advance_search_property_type').css('display', 'inline-block');
            }
			
			// set ownership type excludes
            if (search_exclude_field_ownership_type == 'Ownership Type') {
                $('#crea_advance_search_ownership_type').css('display', 'none');
            } else {
                $('#crea_advance_search_ownership_type').css('display', 'inline-block');
            }

            // set structure type excludes
            if (search_exclude_field_structure == 'Structure Type') {
                $('#crea_advance_search_structure_type').css('display', 'none');
            } else {
                $('#crea_advance_search_structure_type').css('display', 'inline-block');
            }

            // set status type excludes
            if (search_exclude_field_status == 'Status') {
                $('#crea_advance_search_status').css('display', 'none');
            } else {
                $('#crea_advance_search_status').css('display', 'inline-block');
            }

            // set price type excludes
            if (search_exclude_field_Price == 'Price') {
                $('.crea_searching_price_slider_main').css('display', 'none');
            } else {
                $('.crea_searching_price_slider_main').css('display', 'block');
            }

            // set price type excludes
            if (search_exclude_field_bedrooms == 'Bedrooms') {
                $('#crea_advance_search_bedrooms').css('display', 'none');
            } else {
                $('#crea_advance_search_bedrooms').css('display', 'inline-block');
            }

            // set full bathrooms type excludes
            if (search_exclude_field_bathrooms_full == 'Bathrooms Full') {
                $('#crea_advance_search_bathrooms').css('display', 'none');
            } else {
                $('#crea_advance_search_bathrooms').css('display', 'inline-block');
            }

            // set partial bathrooms type excludes
            if (search_exclude_field_bathrooms_partial == 'Bathrooms Partial') {
                $('#crea_advance_search_bathrooms_partial').css('display', 'none');
            } else {
                $('#crea_advance_search_bathrooms_partial').css('display', 'inline-block');
            }

            // set finished basement type exculdes
            if (search_exclude_field_finished_basement == 'Finished Basebent') {
                $('#crea_advance_search_finsished_basement').css('display', 'none');
            } else {
                $('#crea_advance_search_finsished_basement').css('display', 'inline-block');
            }

            // set finished basement type excludes
            if (search_exclude_field_select_city == 'City') {
                $('#crea_advance_search_city').css('display', 'none');
            } else {
                $('#crea_advance_search_city').css('display', 'inline-block');
            }
        });

        var max_price_search_field_results = '100000';
        var max_price_search_field = $('#listing_searching_max_hidden_price').val();
        if (max_price_search_field != '') {
            max_price_search_field_results = max_price_search_field;
        }
        var hiddenMaxPrice = $('#listing_searching_max_hidden_price').val();
        $("#searching-slider-range").slider({
            range: true,
            min: 0,
            max: max_price_search_field_results,
            values: [0, hiddenMaxPrice],
            slide: function (event, ui) {
                $("#display_amount_range").html("$" + ui.values[0] + " - $" + ui.values[1]);
                $("#min_amount").val(ui.values[0]);
                $("#max_amount").val(ui.values[1]);
            }
        });

        $("#display_amount_range").html("$" + $("#searching-slider-range").slider("values", 0) + " - $" + $("#searching-slider-range").slider("values", 1));

        $('body').on('click', '#showcase_inc_exc_listing_feed', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });
        $('body').on('click', '#crea_checkbox_filter_open_house_id', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });

        $('body').on('click', '#listing_view_showcase_simple', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });

        $('body').on('click', '#listing_view_showcase_detailed', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });

        $('body').on('click', '#listing_carousel_show_price', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });

        $('body').on('click', '#listing_carousel_show_status', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });

        $('body').on('click', '#listing_carousel_show_open_house_info', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });

        $('body').on('click', '#crea_filter_by_map_zoom_option', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });

        $('body').on('click', '#listing_slider_show_price', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });

        $('body').on('click', '#listing_slider_show_status', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });

        $('body').on('click', '#listing_slider_show_open_house_info', function () {
            if ($(this).attr('checked')) {
                $(this).attr('value', 'yes');
            } else {
                $(this).attr('value', 'no');
            }
        });
        $('body').on('click', '.crea_listing_showcase_delete_action', function () {
            var showcase_id = (this).id;
            var confirm_remove = confirm('Are you sure you want to delete this record?');
            if (confirm_remove == true) {
                ajaxindicatorstart('Please wait, deleting the data..');
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    data: ({
                        action: 'aretkcrea_delete_showcase_custom_post_records',
                        showcase_id: showcase_id
                    }),
                    success: function (data) {
                        if ($.trim(data) == 'sucessfullydelete') {
                            window.location.reload();
                            ajaxindicatorstop();
                        }
                    }
                });
            } else {
                return false;
            }
        });

        $("#crea_showcase_form_validate").validate({
            rules: {
                crea_showcase_title: {
                    required: true
                }
            },
            messages: {
                crea_showcase_title: {
                    required: "Title needs to be added before the showcase can be saved"
                }
            },
            invalidHandler: function (event, validator) {
                console.log(event);
                console.log(validator);
            },
            ignore: [],
            submitHandler: function (form) {
                var showase_id = $("#showcase_ids").val();

                if (showase_id != '') {
                    ajaxindicatorstart('Please wait we are updating the data..');
                } else {
                    ajaxindicatorstart('Please wait we are inserting the data..');
                }
                form.submit();
            }
        });


        jQuery.extend(jQuery.fn.dataTableExt.oSort, {
            "alt-string-pre": function (a) {
                return a.match(/alt="(.*?)"/)[1].toLowerCase();
            },
            "alt-string-asc": function (a, b) {
                return ((a < b) ? -1 : ((a > b) ? 1 : 0));
            },
            "alt-string-desc": function (a, b) {
                return ((a < b) ? 1 : ((a > b) ? -1 : 0));
            }
        });

        $('#crea_setting_listting_content').DataTable({
            "iDisplayLength": 10,
            "sPaginationType": "full_numbers",
            "bLengthChange": false,
            "bPaginate": true,
            "bFilter": false,
            "bInfo": false,
            "sDom": 'lfrtip',
            "bSortable": true,
            "columnDefs": [{type: 'alt-string', targets: 0}],
            "oLanguage": {"sEmptyTable": "No Listings Found"}
        });

        // Reminder Functionality
        var globalReminderCount = $('#reminder_list').find('.crea_reminder_display').length;
        globalReminderCount = globalReminderCount + 1;

        $('body').on('click', '#add_lead_reminder', function () {
            var reminderHtml = '';
            reminderHtml += '<p style="margin-bottom:0;">Email - The address of the person receiving the reminder</p>';
            reminderHtml += '<div id="addNewReminerMain' + globalReminderCount + '" class="crea_reminder_display">';
            reminderHtml += '<table width="100%" class="create-new-lead-table"><tbody>';
            reminderHtml += '<tr>';
            reminderHtml += '<td><p class="set_reminder_text reminder_text_email">Email<span class="required_fields">*</span></p></td>';
            reminderHtml += '<td><input class="set_text_fields crea_lead_reminder_email_text" type="text" value="" name="crea_lead_reminder_email" id="crea_lead_reminder_text_email' + globalReminderCount + '"><p id="crea_reminder_email_error' + globalReminderCount + '" style="display:none;" class="setErrmsg reminderemailError"></p><p id="crea_reminder_valid_email_error' + globalReminderCount + '" style="display:none;" class="setErrmsg reminderemailErrorvalid"></p></td>';
            reminderHtml += '</tr>';
            reminderHtml += '<tr>';
            reminderHtml += '<td><p class="set_reminder_text reminder_text_subject">Subject<span class="required_fields">*</span></p></td>';
            reminderHtml += '<td><input class="set_text_fields crea_lead_reminder_subject_text" type="text" value="" name="crea_lead_reminder_subject" id="crea_lead_reminder_text_subject' + globalReminderCount + '"><p id="crea_reminder_subject_error' + globalReminderCount + '" style="display:none;" class="setErrmsg reminderSubjectsError"></p></td>';
            reminderHtml += '</tr>';
            reminderHtml += '<tr>';
            reminderHtml += '<td><p class="set_reminder_text reminder_text_comment">Comment</p></td>';
            reminderHtml += '<td><textarea class="set_text_fields crea_lead_reminder_comment_text" name="crea_lead_reminder_comment" id="crea_lead_reminder_text_comment' + globalReminderCount + '"></textarea></td>';
            reminderHtml += '</tr>';
            reminderHtml += '<tr>';
            reminderHtml += '<td><p class="set_reminder_text reminder_text_datetime">Date and Time<span class="required_fields">*</span></p></td>';
            reminderHtml += '<td><input class="set_text_fields crea_lead_reminder_datetime_text" type="text" value="" name="crea_lead_reminder_datetime" id="crea_lead_reminder_text_datetime' + globalReminderCount + '"><p id="crea_reminder_datetime_error' + globalReminderCount + '" style="display:none;" class="setErrmsg reminderdatetimeError"></p></td>';
            reminderHtml += '</tr>';
            reminderHtml += '<tr>';
            reminderHtml += '<td><p class="set_reminder_text reminder_text_repeat">Repeat</p></td>';
            reminderHtml += '<td><input id="crea_lead_no_repeat_remider_id' + globalReminderCount + '" type="radio" checked class="repeat_reminder_value crea_lead_reminder_no_repeat_text" name="crea_lead_reminder_repeat' + globalReminderCount + '" value="no-repeat">No Repeat<br/><input id="crea_lead_daily_repeat_remider_id' + globalReminderCount + '" type="radio" class="repeat_reminder_value crea_lead_reminder_daily_repeat_text" name="crea_lead_reminder_repeat' + globalReminderCount + '" value="daily">Daily<br/><input id="crea_lead_weekly_repeat_remider_id' + globalReminderCount + '" type="radio" class="repeat_reminder_value crea_lead_reminder_weekly_repeat_text" name="crea_lead_reminder_repeat' + globalReminderCount + '" value="weekly">Weekly<br/><input id="crea_lead_monthly_repeat_remider_id' + globalReminderCount + '" type="radio" class="repeat_reminder_value crea_lead_reminder_monthly_repeat_text" name="crea_lead_reminder_repeat' + globalReminderCount + '" value="monthly">Monthly<br/><input id="crea_lead_yearly_repeat_remider_id' + globalReminderCount + '" type="radio" class="repeat_reminder_value crea_lead_reminder_yearly_repeat_text" name="crea_lead_reminder_repeat' + globalReminderCount + '" value="yearly">Yearly<br/></td>';
            reminderHtml += '</tr>';
            reminderHtml += '</tbody></table>';
            reminderHtml += '<div class="submit_block"><a href="javascript:void(0);" id="add_lead_reminder_ajax' + globalReminderCount + '" class="button button-primary crea_lead_save_reminder">Save</a>';
            reminderHtml += '<input type="hidden" class="crea_lead_reminder_unique_id" name="crea_lead_reminder_hiiden_id" value="new" id="lead_reminder_hidden_id' + globalReminderCount + '"><input class="crea_lead_reminder_table_id" type="hidden" name="crea_lead_reminder_Table_hiiden_id" class="crea_lead_reminder_table_id" value="' + globalReminderCount + '" id="lead_reminder_table_hidden_id' + globalReminderCount + '"><div id="aretk_update_lead_reminder_cron_disclaimer">Note: email delivery times are approximate</div></div>';
            reminderHtml += '</div>';
            globalReminderCount = globalReminderCount + 1;
            $('body .lead_reminder .reminder_list').prepend(reminderHtml);
        });

        $('body').on('click', '.crea_lead_save_reminder', function () {
            var reminderSaveID = $(this).attr('id');
            var removeExtravalue = reminderSaveID.substring(22);
            var get_lead_id = $('#aretk_lead_id').val();
            var reminderName = $('#crea_lead_reminder_text_title' + removeExtravalue).val();
            var reminderSubject = $('#crea_lead_reminder_text_subject' + removeExtravalue).val();
            var reminderEmail = $('#crea_lead_reminder_text_email' + removeExtravalue).val();
            var reminderComment = $('#crea_lead_reminder_text_comment' + removeExtravalue).val();
            var reminderDateTime = $('#crea_lead_reminder_text_datetime' + removeExtravalue).val();
            var reminderRepeat = $('input[name="crea_lead_reminder_repeat' + removeExtravalue + '"]:checked').val();

            if (reminderName == '') {
                $('#crea_reminder_title_error' + removeExtravalue).html('');
                $('#crea_reminder_title_error' + removeExtravalue).css('display', 'block').append('This field is required.').delay(2000).fadeOut('slow');
            }
            if (reminderSubject == '') {
                $('#crea_reminder_subject_error' + removeExtravalue).html('');
                $('#crea_reminder_subject_error' + removeExtravalue).css('display', 'block').append('This field is required.').delay(2000).fadeOut('slow');
            }
            if (reminderEmail == '') {
                $('#crea_reminder_email_error' + removeExtravalue).html('');
                $('#crea_reminder_email_error' + removeExtravalue).css('display', 'block').append('This field is required.').delay(2000).fadeOut('slow');
            }
            var check_valid_email = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            var valid_email_address = '';
            if (reminderEmail != '') {
                if (check_valid_email.test(reminderEmail)) {
                    valid_email_address = reminderEmail;
                } else {
                    $('#crea_reminder_valid_email_error' + removeExtravalue).html('');
                    $('#crea_reminder_valid_email_error' + removeExtravalue).css('display', 'block').append('Please enter valid email.').delay(2000).fadeOut('slow');
                }
            }
            if (reminderDateTime == '') {
                $('#crea_reminder_datetime_error' + removeExtravalue).html('');
                $('#crea_reminder_datetime_error' + removeExtravalue).css('display', 'block').append('This field is required.').delay(2000).fadeOut('slow');
            }
            if (reminderName != '' && reminderSubject != '' && valid_email_address != '' && reminderDateTime != '') {
                ajaxindicatorstart('Please wait, adding reminder...');
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    data: ({
                        action: 'aretkcrea_add_new_lead_reminder',
                        get_lead_id: get_lead_id,
                        reminderName: reminderName,
                        reminderSubject: reminderSubject,
                        reminderEmail: valid_email_address,
                        reminderComment: reminderComment,
                        reminderDateTime: reminderDateTime,
                        reminderRepeat: reminderRepeat
                    }),
                    success: function (data) {
                        if (data != '') {
                            $('#reminder_list').empty();
                            $('#reminder_list').append(data);
                            setTimeIntervalreminderinsert = setInterval(function () {
                                ajaxindicatorstop();
                                clearInterval(setTimeIntervalreminderinsert);
                            }, 5000);
                        }
                    }
                });
            }
        });

        $("body").on('focus', ".crea_lead_reminder_datetime_text", function () {
            $(this).appendDtpicker({"futureOnly": true});
        });

        $('body').on('click', '.crea_lead_update_reminder', function () {
            var update_id = $(this).attr('id');
            var explodeuploadid = update_id.substring(25);
            var hiddenReminderValue = $('#lead_reminder_hidden_id' + explodeuploadid).val();
            var hiddenReminderTableValue = $('#lead_reminder_table_hidden_id' + explodeuploadid).val();
            var reminderName = $('#crea_lead_reminder_text_title' + explodeuploadid).val();
            var reminderSubject = $('#crea_lead_reminder_text_subject' + explodeuploadid).val();
            var reminderEmail = $('#crea_lead_reminder_text_email' + explodeuploadid).val();
            var reminderComment = $('#crea_lead_reminder_text_comment' + explodeuploadid).val();
            var reminderDateTime = $('#crea_lead_reminder_text_datetime' + explodeuploadid).val();
            var reminderRepeat = $('input[name="crea_lead_reminder_repeat' + explodeuploadid + '"]:checked').val();

            //check validation
            if (reminderName == '') {
                $('#crea_reminder_title_error' + explodeuploadid).html('');
                $('#crea_reminder_title_error' + explodeuploadid).css('display', 'block').append('This field is required.').delay(2000).fadeOut('slow');
            }
            if (reminderSubject == '') {
                $('#crea_reminder_subject_error' + explodeuploadid).html('');
                $('#crea_reminder_subject_error' + explodeuploadid).css('display', 'block').append('This field is required.').delay(2000).fadeOut('slow');
            }
            if (reminderEmail == '') {
                $('#crea_reminder_email_error' + explodeuploadid).html('');
                $('#crea_reminder_email_error' + explodeuploadid).css('display', 'block').append('This field is required.').delay(2000).fadeOut('slow');
            }
            var check_valid_email = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            var valid_email_address = '';
            if (reminderEmail != '') {
                if (check_valid_email.test(reminderEmail)) {
                    valid_email_address = reminderEmail;
                } else {
                    $('#crea_reminder_valid_email_error' + explodeuploadid).html('');
                    $('#crea_reminder_valid_email_error' + explodeuploadid).css('display', 'block').append('Please enter valid email.').delay(2000).fadeOut('slow');
                }
            }
            if (reminderDateTime == '') {
                $('#crea_reminder_datetime_error' + explodeuploadid).html('');
                $('#crea_reminder_datetime_error' + explodeuploadid).css('display', 'block').append('This field is required.').delay(2000).fadeOut('slow');
            }
            if (reminderName != '' && reminderSubject != '' && valid_email_address != '' && reminderDateTime != '') {
                ajaxindicatorstart('Please wait, updating reminder...');
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    data: ({
                        action: 'aretkcrea_update_crea_lead_reminder',
                        hiddenReminderValue: hiddenReminderValue,
                        hiddenReminderTableValue: hiddenReminderTableValue,
                        reminderName: reminderName,
                        reminderSubject: reminderSubject,
                        reminderEmail: valid_email_address,
                        reminderComment: reminderComment,
                        reminderDateTime: reminderDateTime,
                        reminderRepeat: reminderRepeat
                    }),
                    success: function (data) {
                        setTimeIntervalreminderupdate = setInterval(function () {
                            ajaxindicatorstop();
                            clearInterval(setTimeIntervalreminderupdate);
                        }, 2000);
                    }
                });
            }
        });

        $('body').on('click', '.crea_lead_remove_reminder', function () {
            ajaxindicatorstart('Please wait, removing reminder...');
            var remove_id = $(this).attr('id');
            var explodestring = remove_id.substring(20);
            var hiddenReminderValue = $('#lead_reminder_hidden_id' + explodestring).val();
            var hiddenReminderTableValue = $('#lead_reminder_table_hidden_id' + explodestring).val();
            if (hiddenReminderValue == 'new') {
                $('#addNewReminerMain' + explodestring).remove();
                globalReminderCount = globalReminderCount - 1;
                setTimeIntervalreminderremove = setInterval(function () {
                    ajaxindicatorstop();
                    clearInterval(setTimeIntervalreminderremove);
                }, 2000);
            } else {
                var hiddenLeadID = hiddenReminderValue;
                var removeReminderID = hiddenReminderTableValue;
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    data: ({
                        action: 'aretkcrea_remove_crea_lead_reminder',
                        hiddenLeadID: hiddenLeadID,
                        removeReminderID: removeReminderID
                    }),
                    success: function (data) {
                        $('#reminder_list').empty();
                        $('#reminder_list').append(data);
                        setTimeIntervalreminderremove = setInterval(function () {
                            ajaxindicatorstop();
                            clearInterval(setTimeIntervalreminderremove);
                        }, 2000);
                        globalReminderCount = globalReminderCount - 1;
                    }
                });
            }

            $("#reminder_list .crea_reminder_display").each(function (index, content) {
                index = index + 1;
                $(this).prop('id', 'addNewReminerMain' + index);
                var reminder_main_id = $(this).attr('id');
                $('#' + reminder_main_id + ' .create-new-lead-table .crea_lead_reminder_title_text').prop('id', 'crea_lead_reminder_text_title' + index);
                $('#' + reminder_main_id + ' .create-new-lead-table .crea_lead_reminder_subject_text').prop('id', 'crea_lead_reminder_text_subject' + index);
                $('#' + reminder_main_id + ' .create-new-lead-table .crea_lead_reminder_email_text').prop('id', 'crea_lead_reminder_text_email' + index);
                $('#' + reminder_main_id + ' .create-new-lead-table .crea_lead_reminder_comment_text').prop('id', 'crea_lead_reminder_text_comment' + index);
                $('#' + reminder_main_id + ' .create-new-lead-table .crea_lead_reminder_datetime_text').prop('id', 'crea_lead_reminder_text_datetime' + index);
                $('#' + reminder_main_id + ' .create-new-lead-table .crea_lead_reminder_daily_repeat_text').prop('id', 'crea_lead_daily_repeat_remider_id' + index);
                $('#' + reminder_main_id + ' .create-new-lead-table .crea_lead_reminder_weekly_repeat_text').prop('id', 'crea_lead_weekly_repeat_remider_id' + index);
                $('#' + reminder_main_id + ' .create-new-lead-table .crea_lead_reminder_monthly_repeat_text').prop('id', 'crea_lead_monthly_repeat_remider_id' + index);
                $('#' + reminder_main_id + ' .create-new-lead-table .crea_lead_reminder_yearly_repeat_text').prop('id', 'crea_lead_yearly_repeat_remider_id' + index);
                $('#' + reminder_main_id + ' .submit_block .crea_lead_save_reminder').prop('id', 'add_lead_reminder_ajax' + index);
                $('#' + reminder_main_id + ' .submit_block .crea_lead_update_reminder').prop('id', 'update_lead_reminder_ajax' + index);
                $('#' + reminder_main_id + ' .submit_block .crea_lead_cancel_reminder').prop('id', 'cancel_lead_reminder' + index);
                $('#' + reminder_main_id + ' .submit_block .crea_lead_remove_reminder').prop('id', 'remove_lead_reminder' + index);
                $('#' + reminder_main_id + ' .submit_block .crea_lead_reminder_unique_id').prop('id', 'lead_reminder_hidden_id' + index);
                $('#' + reminder_main_id + ' .submit_block .crea_lead_reminder_table_id').prop('id', 'lead_reminder_table_hidden_id' + index);
            });
        });


        $('body').on('click', '#listing_admin_search', function () {
            var agentName = $('#filter_by_agent_name').val();
            var mlsId = $('#filter_by_mlsid').val();
            var reg = /<(.|\n)*?>/g;
            var result = reg.test(mlsId);
            if (!result == false) {
                $('#filter_by_mlsid').attr('value', '');
                return false;
            }

            ajaxindicatorstart('loading data.. please wait..');
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                data: ({
                    action: 'aretkcrea_listing_filter_based_on_agent_or_mlsid',
                    agentName: agentName,
                    mlsId: mlsId
                }),
                success: function (data) {
                    if (data != '') {
                        $('.crea_plugin_listing_content').empty();
                        $('.crea_plugin_listing_content').append(data);
                        $('#crea_setting_listting_content').DataTable({
                            "iDisplayLength": 20,
                            "sPaginationType": "full_numbers",
                            "bLengthChange": false,
                            "bPaginate": true,
                            "bFilter": false,
                            "bInfo": false,
                            "sDom": 'lfprtip',
                            "oLanguage": {"sEmptyTable": "No Listings Found"}
                        });
                    }
                    ajaxindicatorstop();
                }
            });
        });

        $('body').on('click', '.trash_listing', function () {
            var mlsId = this.id;
            var message = $('#hidden_message_of_delete_listing').text();
            if (confirm(message)) {
                ajaxindicatorstart('Updating the data.. This can take a minute, please wait..');
                setTimeout(function () {
                    $.ajax({
                        type: "POST",
                        url: adminajaxjs.adminajaxjsurl,
                        //async: false,
                        data: ({
                            action: 'aretkcrea_delete_excusive_listing',
                            mlsId: mlsId
                        }),
                        success: function (data) {
                            if (data != '') {
                                $('.crea_plugin_listing_content').empty();
                                $('.crea_plugin_listing_content').append(data);
                                $('#crea_setting_listting_content').DataTable({
                                    "iDisplayLength": 20,
                                    "sPaginationType": "full_numbers",
                                    "bLengthChange": false,
                                    "bPaginate": true,
                                    "bFilter": false,
                                    "bInfo": false,
                                    "sDom": 'lfprtip',
                                    "oLanguage": {"sEmptyTable": "No Listings Found"}
                                });
                            }
                            ajaxindicatorstop();
                        }
                    });
                }, 2000);
            } else {
                return false;
            }
        });


        var option_tag_size = $('#listing_view_agent_id option').size() - 1;
        var add_new_agent_counter = $("#crea_select_option_reoder").find('.multiple_agent_add_default').length;

        $('body').on('click', '#add_new_agent_ids', function () {
            if (option_tag_size != add_new_agent_counter) {
                var check_agent_val = add_new_agent_counter - 1;
                var new_agent_counter = '';
                if (check_agent_val == 0) {
                    new_agent_counter = '';
                } else {
                    new_agent_counter = check_agent_val;
                }
                var option_agent_value = $('#listing_view_agent_id' + new_agent_counter).val();
                if (option_agent_value != '') {
                    var option_array = [];
                    $(".multiple_agent_add_default").each(function (index) {
                        var counter_index = '';
                        if (index == 0) {
                            counter_index = '';
                        } else {
                            counter_index = index;
                        }
                        var check_option_val = $('#listing_view_agent_id' + counter_index).val();
                        option_array.push(check_option_val);
                    });
                    if ($.inArray("", option_array) != '-1') {
                        alert('please fill out every field.');
                    } else {
                        var agent_option_ids = $('#get_select_option_values').html();
                        var agent_delete_btn_html = $('.crea_general_agnet_ids_delete_action').html();
                        var agent_sorting_btn_html = $('.crea_general_agnet_ids_sorting').html();
                        var newFeatureTextBoxDiv = $(document.createElement('div')).attr("id", 'crea_multiple_agent_add' + add_new_agent_counter);
                        newFeatureTextBoxDiv.after().html('<a class="crea_general_agnet_ids_sorting" href="javascript:void(0);">' + agent_sorting_btn_html + '</a><select class="crea_check_agent_option_value required" id="listing_view_agent_id' + add_new_agent_counter + '" name="listing_view_agent_id[]">' + agent_option_ids + '</select><a  id="crea_general_agnet_ids_delete' + add_new_agent_counter + '" class="crea_general_agnet_ids_delete_action" href="javascript:void(0);">' + agent_delete_btn_html + '</a>');
                        newFeatureTextBoxDiv.appendTo("#crea_select_option_reoder");
                        add_new_agent_counter++;
                        $('#crea_select_option_reoder div').addClass("multiple_agent_add_default");
                        if (option_tag_size == add_new_agent_counter) {
                            $('#add_new_agent_btn').css('display', 'none');
                        }
                    }
                } else {
                    alert('please select option');
                }
            } else {
                $('#add_new_agent_btn').css('display', 'none');
            }
        });

        $('body').on('click', '.crea_general_agnet_ids_delete_action', function () {
            var remove_id = $(this).attr('id');

            remove_id = remove_id.substring(29);
            if (add_new_agent_counter == 1) {
                alert("No more textbox to remove");
                return false;
            }
            add_new_agent_counter--;
            $(this).parent().remove();
            $('#add_new_agent_btn').css('display', 'inline-block');
            $("#crea_select_option_reoder .multiple_agent_add_default").each(function (index, content) {
                var reoder_id = '';
                if (index == 0) {
                    reoder_id = '';
                } else {
                    reoder_id = index;
                }
                $(this).prop('id', 'crea_multiple_agent_add' + reoder_id);
                var get_agents_ids = $(this).attr('id');

                $('#' + get_agents_ids + ' .crea_check_agent_option_value').prop('id', 'listing_view_agent_id' + reoder_id);
                $('#' + get_agents_ids + ' .crea_general_agnet_ids_delete_action').prop('id', 'crea_general_agnet_ids_delete' + reoder_id);
            });
        });

        $("#crea_select_option_reoder").sortable();

        $('body').on('change', '.crea_check_agent_option_value', function () {
            var current_selected_ids = $(this).attr('id');
            var current_select_value = $("#" + current_selected_ids).attr('value');
            if (current_select_value == '') {
                alert('Please select option');
            } else {
                var option_array = [];
                $(".multiple_agent_add_default").each(function (index) {
                    var counter_index = '';
                    if (index == 0) {
                        counter_index = '';
                    } else {
                        counter_index = index;
                    }
                    var check_option_val = $('#listing_view_agent_id' + counter_index).val();
                    option_array.push(check_option_val);

                    var sorted_arr = option_array.sort();
                    var results = [];
                    for (var i = 0; i < option_array.length - 1; i++) {
                        if (sorted_arr[i + 1] == sorted_arr[i]) {
                            results.push(sorted_arr[i]);
                            $('.display_alerdy_exist_agent_name').css('display', 'block');
                            $('.display_alerdy_exist_agent_name').html("Agent Name is already selected").delay(1200).fadeOut('slow');
                            $('#' + current_selected_ids).val('');
                            return false;
                        }
                    }
                });
            }
        });

        $('body').on('click', '#crea_add_new_correspondence_body_content', function () {
            $('.crea_add_corrsponding_body_content').css('display', 'block');
        });

        $('body').on('click', '#crea_save_lead_correspondence_btn', function () {
            var aretk_lead_id = $('#aretk_lead_id').val();
            var crea_corrsponding_content = $('#crea_new_corrsponding_area_content_box').val();
            if (crea_corrsponding_content == '') {
                $('.succesful_msg_lead_csv_correspond').html('Correspondence is Required').css('display', 'inline-block').delay(1200).fadeOut('slow');
            }
            if (crea_corrsponding_content != '') {
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    async: false,
                    data: ({
                        action: 'aretkcrea_add_new_correspondence_content',
                        crea_corrsponding_content: crea_corrsponding_content,
                        aretk_lead_id: aretk_lead_id
                    }),
                    success: function (data) {
                        if (data == 'Please add correspondence text') {
                            $('.succesful_msg_lead_csv_correspond').html('');
                            $('.succesful_msg_lead_csv_correspond').html('Succesfully Add Correspondence').css('display', 'inline-block');
                            $('.succesful_msg_lead_csv_correspond').html(data).delay(1200).fadeOut('slow');
                        }
                        if (data != 'Please add correspondence text') {
                            $('.succesful_msg_lead_csv_correspond').html('Succesfully added correspondence note').css('color', 'green');
                            $('.succesful_msg_lead_csv_correspond').css('display', 'inline-block').delay(1200).fadeOut('slow');
                            $('.crea_all_corresponding_listing_contnent').empty();
                            $('.crea_all_corresponding_listing_contnent').html(data);
                            $('.crea_add_corrsponding_body_content').css('display', 'none');
                            $('#crea_new_corrsponding_area_content_box').val('');
                        }
                    }
                });
            }
        });

        $('body').on('click', '#crea_cancel_lead_correspondence_btn', function () {
            $('.crea_add_corrsponding_body_content').css('display', 'none');
        });

        $('body').on('click', '.crea_add_corrsponding_delete_action', function () {
            var get_delete_id = $(this).attr('id');
            var remove_id = get_delete_id.substring(28);
            //var crea_lead_post_id = $('#edit_page_upload_image_ajax_get_id').val();
            var crea_lead_post_id = $('#aretk_lead_id').val();
            var prompt = confirm("Delete correspondence record?");
            if (prompt) {
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    async: false,
                    data: ({
                        action: 'aretkcrea_remove_correspondence_content',
                        remove_id: remove_id,
                        crea_lead_post_id: crea_lead_post_id
                    }),
                    success: function (data) {
                        $('.succesful_msg_lead_csv_correspond').html('Succesfully deleted correspondence').css('color', 'green');
                        $('.succesful_msg_lead_csv_correspond').css('display', 'inline-block').delay(1200).fadeOut('slow');
                        $('.crea_all_corresponding_listing_contnent').empty();
                        $('.crea_all_corresponding_listing_contnent').html(data);
                    }
                });
            }
        });

        $("#crea_inport_lead_form_main").dialog({
            modal: true,
            autoOpen: false,
            title: "Import Lead",
            width: 500,
            height: 250
        });

        $('body').on('click', '#import-lead', function () {
            $('#crea_inport_lead_form_main').dialog('open');
        });

        $('body').on('click', '#crea_import_lead_btn', function () {
            var import_lead_csv_file = $('#crea_add_new_import_lead').val();
            var check_file_format = import_lead_csv_file.split('.');
            if (check_file_format[1] == 'csv') {
                var send_email_attechment = $('#crea_add_new_import_lead').val();
                var file = $('#crea_add_new_import_lead')[0].files[0];
                var fd = new FormData();
                var file = jQuery(document).find('input[type="file"]');
                var individual_file = file[0].files[0];
                fd.append("crea_import_lead", individual_file);
                fd.append('action', 'aretkcrea_new_import_lead_user');
                var formData = new FormData();
                ajaxindicatorstart('Importing leads.. please wait..');
                jQuery.ajax({
                    type: 'POST',
                    url: adminajaxjs.adminajaxjsurl,
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log(response);
                        ajaxindicatorstop();
                        $('.succesful_msg_lead_csv').css('display', 'inline-block');
                        $('.succesful_msg_lead_csv').html('Your leads import succesfully');
                    }
                });
            } else {
                $('#import_csv_error_msg').css('display', 'inline-block').delay(2000).fadeOut('slow');
            }
        });

        $('body').on('click', '#btn_save_default_listing_button', function () {
            var DefaultlistingTextColor = $("#crea_default_listing_title_color_id").val();
            var DefaultlistingAddressbarColor = $("#crea_default_listing_address_color_id").val();
            var DefaultlistingPriceColor = $("#crea_default_listing_prise_color_id").val();
            var DefaultlistingStatusColor = $("#crea_default_listing_status_color_id").val();
            var DefaultlistingopenhouseColor = $("#crea_search_detail_button_color_id").val();
            var DefaultlistingStatusTextColor = $("#default_listing_status_text_color_id").val();
            var DefaultlistingopenhouseTextColor = $("#crea_default_listing_openhouse_text_color_id").val();
            var DefaultlistingpaginationColor = $("#crea_default_listing_pagination_color_id").val();
            var DefaultlistingpaginationtextColor = $("#crea_default_listing_pagination_text_color_id").val();
            var get_openHouse = $("input[type='radio'][name='default_listing_view_setiing_open_house']:checked").val();
            var get_status = $("input[type='radio'][name='default_listing_view_setiing_open_house']:checked").val();
            var get_search = $("input[type='radio'][name='default_listing_view_setiing']:checked").val();

            ajaxindicatorstart('Please wait, saving your listing showcase data...');
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: ({
                    action: 'aretkcrea_add_default_listing_setting',
                    DefaultlistingTextColor: DefaultlistingTextColor,
                    DefaultlistingAddressbarColor: DefaultlistingAddressbarColor,
                    DefaultlistingPriceColor: DefaultlistingPriceColor,
                    DefaultlistingStatusColor: DefaultlistingStatusColor,
                    DefaultlistingopenhouseColor: DefaultlistingopenhouseColor,
                    DefaultlistingStatusTextColor: DefaultlistingStatusTextColor,
                    DefaultlistingopenhouseTextColor: DefaultlistingopenhouseTextColor,
                    DefaultlistingpaginationColor: DefaultlistingpaginationColor,
                    DefaultlistingpaginationtextColor: DefaultlistingpaginationtextColor,
                    get_openHouse: get_openHouse,
                    get_status: get_status,
                    get_search: get_search
                }),
                success: function (data) {
                    listingShowcaseDetailsTimeint = setInterval(function () {
                        ajaxindicatorstop();
                        clearInterval(listingShowcaseDetailsTimeint);
                    }, 5000);
                }
            });
        });

        $('body').on('click', '#crea_showcase_listting_save_btn', function () {
            var get_include_information = $("input[type='radio'][name='include_information']:checked").val();
            var get_include_contact_form = $("input[type='radio'][name='include_contact_form']:checked").val();
            var get_include_map = $("input[type='radio'][name='include_map']:checked").val();
            var get_include_walk_score = $("input[type='radio'][name='include_walk_score']:checked").val();
            var get_include_print_button = $("input[type='radio'][name='include_print_button']:checked").val();
            var get_include_email_address_of_agent = $("input[type='radio'][name='include_email_address_of_agent']:checked").val();
            var get_crea_listing_price_color_id = $("#crea_listing_price_color_id").val();
            var get_crea_listing_send_btn_color_id = $("#crea_listing_send_btn_color_id").val();
            ajaxindicatorstart('Please wait, saving showcase data...');
            var ajaxdata = {
                action: 'aretkcrea_add_listing_showcase_changes',
                get_include_information: get_include_information,
                get_include_contact_form: get_include_contact_form,
                get_include_map: get_include_map,
                get_include_walk_score: get_include_walk_score,
                get_include_print_button: get_include_print_button,
                get_include_email_address_of_agent: get_include_email_address_of_agent,
                get_crea_listing_price_color_id: get_crea_listing_price_color_id,
                get_crea_listing_send_btn_color_id: get_crea_listing_send_btn_color_id
            };
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: ajaxdata,
                success: function (data) {
                    listingShowcaseDetailsTimeint = setInterval(function () {
                        ajaxindicatorstop();
                        clearInterval(listingShowcaseDetailsTimeint);
                    }, 3000);
                }
            });

        });
        /********
         Create / Edit Listing
         ********/
        // To Preview Image
        function imageIsLoaded(e) {
            jQuery('#previewimg' + abc).attr('src', e.target.result);
        };

        $('body').on('click', '.imgdelete', function (e) {
            $(this).parent().remove();
        });

        // Following function will executes on change event of file input to select different file.
        $('body').on('change', '#file', function (e) {
            var filedive = $('#filediv').children('.abcd');

            if (filedive != '' && filedive != null) {
                $('input#add_more').css('display', 'block');
            } else {
                $('input#add_more').css('display', 'none');
            }
            var files = e.target.files,
                filesLength = files.length;

            if (filesLength > 20) {
                $("#file").val("");
                alert('You can only upload a maximum 20 images at a time');
                return false;
            }

            $("#filediv .abcd").remove();
            for (var i = 0; i < filesLength; i++) {
                var f = files[i]
                if (f.type == 'image/png' || f.type == 'image/jpg' || f.type == 'image/jpeg') {
                    var fileReader = new FileReader();
                    fileReader.onload = (function (e) {
                        var file = e.target;
                        $("<div class='abcd'><img src='" + e.target.result + "'></img></div>").insertBefore("#file");
                    });
                    fileReader.readAsDataURL(f);
                }
            }
            var listingId = $('#edit_page_upload_image_ajax_get_id').val();
            if ($.isNumeric(listingId)) {
                $('#edit_page_upload_image_ajax').css('display', 'inline-block');
            }
        });

        //  To add new input file field dynamically, on click of "Add More Files" button below function will be executed.
        $('body').on('click', 'input#add_more', function () {
            $(this).before(jQuery("<div/>", {
                id: 'filediv'
            }).fadeIn('slow').append($("<input name='file[]' type='file' accept='.jpg,.jpeg,.png' class='filesinput' multiple>"), $("<br/><br/>")));
        });


        $('body').on('click', '#edit_page_upload_image_ajax', function (event) {
            event.preventDefault();
            var form_data = new FormData();
            var listingId = $('#edit_page_upload_image_ajax_get_id').val();
            if (!$.isNumeric(listingId)) {
                return false;
            }
            $('#filediv #imageloading').css('display', 'block');
            var arrImages = [];
            $.each($("#file").prop("files"), function (i, file) {
                form_data.append(i, file);
            });

            form_data.append('action', 'aretkcrea_update_crea_listing_images_order_with_upload');
            form_data.append('listingId', listingId);

            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: form_data,
                contentType: false,
                processData: false,
                success: function (data) {
                    arrImages = [];
                    $("#filediv .abcd").remove();
                    $("#file").replaceWith($("#file").val('').clone(true));
                    $('#edit_page_upload_image_ajax').css('display', 'none');
                    $('input#add_more').css('display', 'inline-block');
                    $.getScript(refreshimagejs.refreshimagejsurl);
                    $('.test-images').html('');
                    $('.test-images').html(data);
                    $('#filediv #imageloading').css('display', 'none');
                }
            });
        });

        $('body').on('click', '.gallery .reorder-photos-list .delete-showcase-photo-listing', function () {
            var arr = this.id.split('_');
            var id = arr[arr.length - 1];
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: ({
                    action: 'aretkcrea_delete_listing_image_edit_page_from_listing_ajax',
                    id: btoa(id)
                }),
                success: function (data) {
                    if (data != '') {
                        jQuery.getScript(refreshimagejs.refreshimagejsurl);
                        jQuery('.test-images').html('');
                        jQuery('.test-images').html(data);
                        var gallery_image_length = jQuery("#listting-photos-tab .gallery ul li").length;
                        if (gallery_image_length <= 0) {
                            jQuery('a#save_reorder').css('display', 'none');
                        }
                    }
                }
            });
        });

        $("body").on('click', "#crea_listing_map_button", function () {
            var google_map_address = $('#crea_listing_google_map_location_txt').val();
            var reg = /<(.|\n)*?>/g;
            var result = reg.test(google_map_address);
            if (!result == false) {
                $('#crea_listing_google_map_location_txt').attr('value', '');
                return false;
            }
            if (google_map_address == '') {
                $('#crea_listing_google_map_location_txt').css('border', '1px solid red');
                setTimeMapAddress = setInterval(function () {
                    $("#crea_listing_google_map_location_txt").css('border', '1px solid #ddd');
                    clearInterval(setTimeMapAddress);
                }, 2000);
                return false;
            }
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: ({
                    action: 'aretkcrea_get_google_map_address_lat_long',
                    google_map_address: btoa(google_map_address)
                }),
                success: function (data) {
                    get_lat_long = atob(data);
                    var after_decode_lat_long = get_lat_long.split('|');
                    var data_message = after_decode_lat_long[0];
                    var address_latitude = after_decode_lat_long[1];
                    var address_longitude = after_decode_lat_long[2];
                    var address = after_decode_lat_long[3];
                    if (data_message == 'sucessfully') {
                        $('#crea_google_map_latitude').val(address_latitude);
                        $('#crea_google_map_longitude').val(address_longitude);
                        $('#crea_google_map_geo_address').val(address);
                        //infoWindow = new google.maps.InfoWindow();
                        latlngbounds = new google.maps.LatLngBounds();
                        geocoder = geocoder = new google.maps.Geocoder();

                        mapProp = {
                            center: new google.maps.LatLng(address_latitude, address_longitude),
                            zoom: 14,
                            mapTypeId: google.maps.MapTypeId.ROADMAP,
                            scrollwheel: false
                        };
                        myLatlng = new google.maps.LatLng(address_latitude, address_longitude);

                        marker = new google.maps.Marker({
                            position: myLatlng,
                            map: map,
                            title: address,
                            draggable: true,
                            animation: google.maps.Animation.DROP,
                            scrollwheel: false
                        });
                        data = '';
                        (function (marker, data) {
                            google.maps.event.addListener(marker, "dragend", function (e) {
                                var lat, lng, address;
                                geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                                    if (status == google.maps.GeocoderStatus.OK) {
                                        lat = marker.getPosition().lat();
                                        lng = marker.getPosition().lng();
                                        address = results[0].formatted_address;
                                        $('#crea_google_map_latitude').val('');
                                        $('#crea_google_map_latitude').val(lat);
                                        $('#crea_google_map_longitude').val('');
                                        $('#crea_google_map_longitude').val(lng);
                                        $('#crea_google_map_geo_address').val('');
                                        $('#crea_google_map_geo_address').val(address);
                                        $('#crea_listing_google_map_location_txt').val('');
                                        $('#crea_listing_google_map_location_txt').val(address);
                                        latitude = lat;
                                        longitude = lng;
                                    }
                                });
                            });
                        })(marker, data);
                        marker.addListener('click', toggleBounce);
                        var map = new google.maps.Map(document.getElementById("crea_location_google_maps"), mapProp);
                        marker.setMap(map);
                    }
                }
            });
        });

        function toggleBounce() {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }

        $("#create_listing_form").validate({
            rules: {
                listing_view_agent_id: {
                    required: true,
                    message: "Please select Agent"
                },
                agent_listing_tab_address: {
                    required: true
                },
                agent_listing_tab_city: {
                    required: true
                },
                agent_listing_tab_Province: {
                    required: true
                },
                listing_view_agent_status: {
                    required: true
                },
                listing_virtual_tor_add_url: {
                    required: false,
                    url: true
                },
                agent_listing_tab_price: {
                    required: true,
                    number: true
                },
                file: {
                    required: true,
                },
                crea_home_date_picker: {
                    required: true,
                },
            },

            invalidHandler: function (event, validator) {
                var check_agent_name = $('.crea_check_agent_option_value').val();
                var check_street_address = $('.agent_listing_tab_address').val();
                var check_city = $('.agent_listing_tab_city').val();
                var check_province = $('.agent_listing_tab_Province').val();
                var check_status = $('.listing_view_agent_status').val();
                var check_price = $('.agent_listing_tab_price').val();
                var check_photo = $('.filesinput').val();
                var check_virtual_url = $('.listing_virtual_tor_add_url error').val();
                var check_open_date = $('.datepicker_popup').val();
                $('.crea_validate_message_display').css('display', 'block');
                if (check_agent_name == '') {
                    $('.check_agent_name_required').css('display', 'block').delay(4000).fadeOut('slow');
                }
                if (check_street_address == '') {
                    $('.check_agent_address_required').css('display', 'block').delay(4000).fadeOut('slow');
                }
                if (check_city == '') {
                    $('.check_agent_city_required').css('display', 'block').delay(4000).fadeOut('slow');
                }
                if (check_province == '') {
                    $('.check_agent_province_required').css('display', 'block').delay(4000).fadeOut('slow');
                }
                if (check_status == '') {
                    $('.check_agent_status_required').css('display', 'block').delay(4000).fadeOut('slow');
                }
                if (check_price == '') {
                    $('.check_agent_price_required').css('display', 'block').delay(4000).fadeOut('slow');
                }
                if (check_photo == '') {
                    $('.check_agent_photo_required').css('display', 'block').delay(4000).fadeOut('slow');
                }
                var validErrorUrl = $('.listing_virtual_tor_add_url').attr('class');
                var listingVitualUrl = validErrorUrl.split(' ');
                if (listingVitualUrl[1] == 'error') {
                    $('.check_agent_virtual_url_required').css('display', 'block').delay(4000).fadeOut('slow');
                }
                if (check_open_date == '') {
                    $('.check_agent_date_required').css('display', 'block').delay(4000).fadeOut('slow');
                }
            },
            ignore: [],
            submitHandler: function (form) {
                var listing_ids = $("#edit_page_upload_image_ajax_get_id").val();
                if (listing_ids != '') {
                    ajaxindicatorstart('Please wait we are updating the listing..');
                } else {
                    ajaxindicatorstart('Please wait we are inserting the listing..');
                }
                form.submit();
            }
        });

        /*************
         * Create Listing Page Jquery Start
         **************/

        /*************
         * Subscription Settings Page Jquery Start
         **************/


        /****************************************************
         Start ajax call to check subscription key is valid or not
         *****************************************************/

        $("body.real-estate_page_create_new_showcase #crea_showcase_menu_tab").tabs().addClass('ui-tabs-vertical');
        $("body.admin_page_listing_details_settings #crea_showcase_menu_tab").tabs().addClass('ui-tabs-vertical');
        $("body.admin_page_search_listing_settings_showcase #crea_showcase_search_menu_tab").tabs().addClass('ui-tabs-vertical');

        $('#crea_check_subscription_button').click(function () {
            var subscriptionKey = $('.crea-api-key-values').val();
            var domain = window.location.href;
            $('.not_valid_msg').css('display', 'none');
            $('.suceess_msg').css('display', 'none');
            if (subscriptionKey == '') {
                subscriptionKey = '0';
            }
            ajaxindicatorstart('Vailidating ARETK API key, please wait..');
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                data: ({
                    action: 'aretkcrea_check_subscription_key_valid_ajax',
                    subscriptionKey: btoa(subscriptionKey),
                    domain: btoa(domain)
                }),
                success: function (data) {
                    data = $.trim(data);
                    if (data === '' || data === "not-valid") {
                        $('.not_valid_msg').css('display', 'block');
                        $(".crea-plugin-main-content p.crea-bottom-line b").attr('class', 'status_inactive').text('Inactive');
                    } else if (data === 'valid') {
                        $('.suceess_msg').css('display', 'block');
                        $(".crea-plugin-main-content p.crea-bottom-line b").attr('class', 'status_active').text('Active');
                        ;
                    }
                    ajaxindicatorstop();
                }
            });
        });

        /******************************************************
         End ajax call to check subscription key is valid or not
         *******************************************************/


        /*************
         * Subscription Settings Page Jquery End
         **************/

        /*************
         * start listing tab open house date picker
         **************/

        $("body").on('focus', ".datepicker_popup", function () {
            $(this).datepicker( { dateFormat: 'yy-mm-dd', minDate: 0 } );
        });

        /*************
         * END listing tab open house date picker
         **************/

        /*************
         *  Plugin Settings Page Jquery Start
         **************/


        /*************
         * Start ajax call for plugin settings
         **************/
        $(document).on('click', '#crea_plugin_setting_save_keys', function () {
            var flagsettingvaluegoogleMapApiKey = 0;
            var flagsettingvaluewalkScoreApiKey = 0;
            var googleMapApiKey = $('#google-map-api-name').val();
            var walkScoreApiKey = $('#walk-score-api-name').val();
            var googleCaptchaKey_public = $('#google-recaptcha-api-public').val();
            var googleCaptchaKey_private = $('#google-recaptcha-api-private').val();
            var googlemapscriptloadornot = '';
            if ($('#crea_google_api_enable_disable').is(":checked")) {
                googlemapscriptloadornot = 1;
            } else {
                googlemapscriptloadornot = 0;
            }
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: true,
                data: ({
                    action: 'aretkcrea_save_plugin_settings_tab_data_ajax',
                    googleMapApiKey: btoa(googleMapApiKey),
                    walkScoreApiKey: btoa(walkScoreApiKey),
                    googleCaptchaKey_public: btoa(googleCaptchaKey_public),
                    googleCaptchaKey_private: btoa(googleCaptchaKey_private),
                    googlemapscriptloadornot: btoa(googlemapscriptloadornot)
                }),
                success: function (data) {
                    if ('Settings Saved' === data) {
                        $('.suceess_msg').css('display', 'inline-block').delay(3000).fadeOut('slow');
                    } else {
                        $('.error_msg').css('display', 'inline-block').delay(3000).fadeOut('slow');
                    }
                }
            });
        });
        /*************
         * End ajax call for plugin settings
         **************/


        /*************
         * Plugin Settings Page Jquery End
         **************/


        /*************
         * START Crea Listings tab add more utility's
         **************/

        var utility_counter = $("#utilities-new-tabe-add-section").find('.crea-utilities-html').length;

        $('body').on('click', '#crea-utilitiy-add-more-input', function () {
            var site_path_aretk = $("#areatk_plugin_url").val();
            var utility_img_path = site_path_aretk + 'admin/images/delete-icon.png';
            if (utility_counter > 9) {
                alert("Only 10 textboxes allowed");
                return false;
            }
            var newUtilityTextBoxDiv = $(document.createElement('div')).attr("id", 'crea-utility-textbox' + utility_counter);
            newUtilityTextBoxDiv.after().html('<input type="text" id="crea-utilities-box' + utility_counter + '" class="check_utitlity_values" name="crea-utilities-input[]"><a href="javascript:void(0);" class="crea_utilities_delete_action" id="crea_utility_delete' + utility_counter + '"><img width="20" height="20" alt="delete" src="' + utility_img_path + '"></a>');
            newUtilityTextBoxDiv.appendTo("#utilities-new-tabe-add-section");
            utility_counter++;
            $('#utilities-new-tabe-add-section div').addClass("crea-utilities-html");
        });

        /*************
         * END Crea Listings tab add more utilitys
         **************/


        /*************
         * START Crea Listings tab REMOVE utilitys textbox
         **************/

        $('body').on('click', '.crea_utilities_delete_action', function () {
            if (utility_counter == 1) {
                alert("No more textbox to remove");
                return false;
            }
            utility_counter--;
            $("#crea-utility-textbox" + utility_counter).remove();
        });

        /*************
         * END Crea Listings tab REMOVE utilitys textbox
         **************/


        /*************
         * START Crea FEATURES tab add more utilitys textbox
         **************/

        var feature_counter = $("#features-new-tabe-add-section").find('.crea-features-html').length;
        $('body').on('click', '#crea-features-add-more-input', function () {
            var site_path_aretk = $("#areatk_plugin_url").val();
            var feature_img_path = site_path_aretk + 'admin/images/delete-icon.png';
            if (feature_counter > 9) {
                alert("Only 10 textboxes allowed");
                return false;
            }
            var newFeatureTextBoxDiv = $(document.createElement('div')).attr("id", 'crea-features-textbox' + feature_counter);
            newFeatureTextBoxDiv.after().html('<input type="text" class="crea_listing_feature_input" id="crea-features-box' + feature_counter + '" name="crea-features-input[]"><a href="javascript:void(0);" class="crea_features_delete_action" id="crea_features_delete' + feature_counter + '"><img width="20" height="20" alt="delete" src="' + feature_img_path + '"></a>');
            newFeatureTextBoxDiv.appendTo("#features-new-tabe-add-section");
            feature_counter++;
            $('#features-new-tabe-add-section div').addClass("crea-features-html");
        });

        /*************
         * END Crea FEATURES tab add more utilitys textbox
         **************/

        /*************
         * START Crea Listings tab REMOVE Features textbox
         **************/

        $('body').on('click', '.crea_features_delete_action', function () {
            if (feature_counter == 1) {
                alert("No more textbox to remove");
                return false;
            }
            feature_counter--;
            $("#crea-features-textbox" + feature_counter).remove();
        });

        /*************
         * END Crea Listings tab REMOVE Features textbox
         **************/


        /*************
         * START Crea Listings tab add more open house date time html
         **************/

        var date_timecounter = $("tbody.find_tbody").find('.open-house-date-time-html').length;
        $('body').on('click', '#crea_add_new_date_time', function () {
            var tempCount = '';
            var dateValArr = [];
            var nullOpenHouseDateID = [];
            $(".open-house-date-time-html").each(function (index) {
                tempCount = index;
                var openHouseDate = $('#datepicker' + tempCount).val();
                if (openHouseDate == '') {
                    nullOpenHouseDateID.push(tempCount);
                }
                dateValArr.push(openHouseDate);
            });
            if ($.inArray("", dateValArr) != '-1') {
                alert('Please select an open house date');
            } else {
                var date_timeimg_path = $("#listing-open-house-date-time-html table tbody.find_tbody tr td a img").attr('src');
                if(0 !== $('select#crea_open_house_start_time_id0').length) {
                    var select_time_html_start_selector = "select#crea_open_house_start_time_id0";
                    var select_time_html_end_selector = "select#crea_open_house_start_time_id0";
                } else {
                    var select_time_html_start_selector = "select#crea_open_house_start_time_id";
                    var select_time_html_end_selector = "select#crea_open_house_start_time_id";
                }
                var select_time_html_start = $("#listing-open-house-date-time-html table tbody.find_tbody tr td "+select_time_html_start_selector).html();
                var select_time_html_end = $("#listing-open-house-date-time-html table tbody.find_tbody tr td "+select_time_html_end_selector).html();
                var newDateTimeTextBoxDiv = $(document.createElement('tr')).attr("id", 'date-time-html' + date_timecounter);
                newDateTimeTextBoxDiv.after().html('<td><div class="input-box"><input type="text" name="crea_home_date_picker[]" class="datepicker_popup required" id="datepicker' + date_timecounter + '"/></div></td><td><select id="crea_open_house_start_time_id' + date_timecounter + '" name="crea-open-house-start-time[]">' + select_time_html_start + '</select></td><td><select id="crea_open_house_end_time_id' + date_timecounter + '" name="crea-open-house-end-time[]">' + select_time_html_end + '</td><td><a  id="crea_date_time_delete' + date_timecounter + '" class="crea_date_time_delete_action" href="javascript:void(0);"><img src="' + date_timeimg_path + '" alt="delete" width="20" height="20"></a></td>');
                newDateTimeTextBoxDiv.appendTo("tbody.find_tbody");
                date_timecounter++;
                $('tbody.find_tbody tr').addClass("open-house-date-time-html");
            }
        });

        /*************
         * END Crea Listings tab add more open house date time html
         **************/


        /*************
         * Start Crea Listings tab remove open house date time html
         **************/

        $('body').on('click', '.crea_date_time_delete_action', function () {
            if (date_timecounter == 1) {
                $("#date-time-html0 #datepicker0").val('');
                return false;
            }
            date_timecounter--;
            $("#date-time-html" + date_timecounter).remove();
        });

        /*************
         * END Crea Listings tab remove open house date time html
         **************/

        /*************
         * Create / Edit Listing - ADD external document
         **************/

        var countMoreFiles = $('#extdocmaindiv').find('.filesinputextdoc').length;
        $('body').on('click', '#addMoreExtDocument', function () {
            $("#extdocmaindiv").append('<input class="filesinputextdoc" name="extdocfileinput[]" type="file"  multiple/>');
            $('#addMoreExtDocument').css("display", "none");
        });
        $('body').on('change', '.filesinputextdoc', function (e) {
            var files = e.target.files,
                filesLength = files.length;
            var document_image_length = jQuery(".crea_listing_display_select_files").length;
            var total_image_upload = filesLength + document_image_length;

            if (total_image_upload > 10) {
                $(this).val("");
                $(".check_validation_for_multiple_files.checkmaxTenfileallowed").css({
                    "font-weight": "bold",
                    "font-size": "16px"
                });
                setTimeIntervalDocument = setInterval(function () {
                    $(".check_validation_for_multiple_files.checkmaxTenfileallowed").css({
                        "font-weight": "normal",
                        "font-size": "13px"
                    });
                    clearInterval(setTimeIntervalDocument);
                }, 2000);
                return false;
            }
            if (document_image_length < 10) {
                $('#addMoreExtDocument').css("display", "block");
            }
            var site_path_aretk = $("#areatk_plugin_url").val();
            for (var x = 0; x < filesLength; x++) {
                var filemultiplename = files[x];
                InputmultipleFile = filemultiplename.name;
                InputFileSize = filemultiplename.size;
                file_format = InputmultipleFile.split('.');
                file_formats = file_format[file_format.length - 1];

                if (file_formats == 'png' || file_formats == 'jpg' || file_formats == 'jpeg' || file_formats == 'doc' || file_formats == 'docx' || file_formats == 'csv' || file_formats == 'pdf' || file_formats == 'txt') {
                    if (InputFileSize <= '5000000') {
                        $('#crea_listing_multiplefile_display').append('<div class="crea_listing_display_select_files"><img class="crea_document_files_img" width="50px" src="' + site_path_aretk + 'admin/images/document_icon.png" alt="Document Icon"><p>' + InputmultipleFile + '</p><input type="hidden" name="crea_listing_multiplefile_document_array[]" value="' + InputmultipleFile + '" ></div>');
                    } else {
                        $('.listing_not_allowed_documents_list_failed').append('<p class="failed">' + InputmultipleFile + ' file size gretter 5MB</p>').delay(6000).fadeOut('slow');
                    }
                } else {
                    $('.listing_not_allowed_documents_list_failed').append('<p class="failed"><b><u>' + InputmultipleFile + '</u></b> not allowed</p>').delay(6000).fadeOut('slow');
                    $(".check_validation_for_multiple_files_format.checkformatfileallowed").css({
                        "font-weight": "bold",
                        "font-size": "16px"
                    });
                    $(".listing_not_allowed_documents_list_failed").css({"display": "block"});
                    if (file_format[file_format.length - 1] != 'png' || file_format[file_format.length - 1] != 'jpg' || file_format[file_format.length - 1] != 'jpeg' || file_format[file_format.length - 1] != 'doc' || file_format[file_format.length - 1] != 'docx' || file_format[file_format.length - 1] != 'csv' || file_format[file_format.length - 1] != 'pdf' || file_format[file_format.length - 1] != 'txt') {
                        $('#extdocmaindiv .filesinputextdoc').val('');
                    }
                    setTimeIntervalDocument = setInterval(function () {
                        $(".check_validation_for_multiple_files_format.checkformatfileallowed").css({
                            "font-weight": "normal",
                            "font-size": "13px"
                        });
                        clearInterval(setTimeIntervalDocument);
                    }, 2000);
                }
            }
            jQuery(this).css('display', 'none');
        });

        $('body').on('click', '.crea_delete_documents', function (e) {
            var documentID = this.id;
            var inputfilename = $(this).attr('title');
            if ($.isNumeric(documentID)) {
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    async: false,
                    data: ({
                        action: 'aretkcrea_delete_listing_document_edit_page_from_listing_ajax',
                        documentID: btoa(documentID)
                    }),
                    success: function (data) {
                        jQuery('#crea_listing_multiplefile_display').html('');
                        jQuery('#crea_listing_multiplefile_display').html(data);
                    }
                });
            }
        });

        /*************
         * END Crea Listings tab ADD more external document html
         **************/


        /*************
         * Start Crea Listings tab remove open house date time html
         **************/

        $('body').on('click', '.crea_external_document_delete_action', function () {
            if (ext_doc_counter == 1) {
                alert("No more textbox to remove");
                $("#crea_add_external_document").val('');
                return false;
            }
            ext_doc_counter--;
            $("#crea_external_document_html" + ext_doc_counter).remove();
        });

        /*************
         * END Crea Listings tab remove open house date time html
         **************/


        /*************
         * Start Crea Listings tab browse new external document file
         **************/


        /*************
         * add bwose value in text box external document file
         **************/

        /*************
         * END Crea Listings tab browse new external document file
         **************/

        var image_counter = 1;
        $('body').on('click', '#crea-new-add-more-image', function () {
            var image_path = $("#listting-photos-tab a.crea_image_delete_action img").attr('src');
            var sorting_path = $("#listting-photos-tab a.listing_photo_sorting img").attr('src');
            var newimageBoxDiv = $(document.createElement('div')).attr("id", 'crea-new-listing-tab-images' + image_counter);
            newimageBoxDiv.after().html('<a class="listing_photo_sorting" href="javascript:void(0);"><img src="' + sorting_path + '" alt="sorting_icon" width="20" height="20"></a><input type="file" style="display:none;" accept="image/*" name="crea_listing_new_image_input" id="crea-listing-new-photo-browse' + image_counter + '"><input type="text" readonly="true" id="crea_add_new_photo' + image_counter + '" name="crea_add_new_photo[' + image_counter + ']"><input type="button" class="crea_photo_click_btn button button-primary" id="crea_listing_photo_browse_btn' + image_counter + '" value="Browse"><a href="javascript:void(0);" class="crea_image_delete_action" id="crea_image_document_delete' + image_counter + '"><img width="20" height="20" alt="delete" src="' + image_path + '">');
            newimageBoxDiv.appendTo("#listting-photos-tab .crea-image-arrange-sequence");
            image_counter++;
            $("#listting-photos-tab .crea-image-arrange-sequence div").addClass("crea_listing_new_photo");
        });

        $('body').on('click', '.crea_image_delete_action', function () {
            if (image_counter == 1) {
                alert("No more textbox to remove");
                $("#crea_add_new_photo").val('');
                return false;
            }

            image_counter--;
            $("#crea-new-listing-tab-images" + image_counter).remove();
        });

        $('body').on('click', '.crea_photo_click_btn', function () {
            var current_photo_browse_id = $(this).attr('id');
            var current_selected_photo_btn_number = current_photo_browse_id.substring(29);
            var fileinput = document.getElementById("crea-listing-new-photo-browse" + current_selected_photo_btn_number);
            fileinput.click();
            $('body').on('change', '#crea-listing-new-photo-browse' + current_selected_photo_btn_number, function () {
                var fileinput = document.getElementById("crea-listing-new-photo-browse" + current_selected_photo_btn_number);
                var textinput = document.getElementById("crea_add_new_photo" + current_selected_photo_btn_number);
                textinput.value = fileinput.value;
            });
        });


        /*************
         * START Crea setting tab EDIT AGENT DETAISLS
         **************/

        $('body').on('click', '.crea_agent_edit_action', function () {
            var crea_update_id = $(this).attr('id');
            var crea_agent_id = crea_update_id.substring(16);
            $("#crea_update_agent_p_tag_" + crea_agent_id).css("display", "none");
            $("#crea_update_agent_name_p_tag_" + crea_agent_id).css("display", "none");
            $("#crea_update_agent_email_p_tag_" + crea_agent_id).css("display", "none");

            $("#crea_setting_update_agent_name_" + crea_agent_id).css("display", "block");

            $("#crea_setting_update_agent_id_" + crea_agent_id).css("display", "inline-block");
            $("#crea_setting_update_agent_name_" + crea_agent_id).css("display", "inline-block");
            $("#crea_setting_update_agent_email_" + crea_agent_id).css("display", "inline-block");
            $("#crea_agent_setting_update_button_" + crea_agent_id).css("display", "inline-block");
        });

        /*************
         * END Crea setting tab EDIT AGENT DETAISLS
         **************/


        /*************
		* START Crea setting tab UPDATE AGENT RECORDS 
		**************/
        
        $('body').on('click', '.crea_agent_record_update', function () {
            var crea_update_button_id = $(this).attr('id');
            var crea_agent_update_id = crea_update_button_id.substring(33);
            var crea_agent_id = $("#crea_setting_update_agent_id_" + crea_agent_update_id).val();
            var crea_agent_name = $("#crea_setting_update_agent_name_" + crea_agent_update_id).val();
            var crea_agent_email = $("#crea_setting_update_agent_email_" + crea_agent_update_id).val();
            var check_valid_email = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            var crea_agent_auto_id = crea_agent_update_id;
            var not_nul_email = '';
            var not_nul_agent_id = '';
            var not_nul_agent_name = '';
            if (crea_agent_email != '') {
                if (check_valid_email.test(crea_agent_email)) {
                    not_nul_email = crea_agent_email;
                } else {
                    $("#crea_agent_email_valid_" + crea_agent_update_id).css('display', 'block').delay(1200).fadeOut('slow');
                }
            } else {
                $("#crea_agen_email_not_blank_" + crea_agent_update_id).css('display', 'block').delay(1200).fadeOut('slow');
            }
            if (crea_agent_id != '') {
                not_nul_agent_id = crea_agent_id;
            } else {
                $("#crea_agen_id_not_blank_" + crea_agent_update_id).css('display', 'block').delay(1200).fadeOut('slow');
            }
            if (crea_agent_name != '') {
                not_nul_agent_name = crea_agent_name;
            } else {
                $("#crea_agen_name_not_blank_" + crea_agent_update_id).css('display', 'block').delay(1200).fadeOut('slow');
            }
            if (not_nul_agent_id != '' && not_nul_email != '' && not_nul_agent_name != '') {
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    async: false,
                    data: ({
                        action: 'aretkcrea_update_crea_agents_records',
                        crea_agent_auto_id: btoa(crea_agent_auto_id),
                        crea_agent_id: btoa(not_nul_agent_id),
                        crea_agent_email: btoa(not_nul_email),
                        crea_agent_name: btoa(not_nul_agent_name)
                    }),
                    success: function (data) {
                        var after_decode_data = data.split(',');
                        agents_decode_id = after_decode_data[0];
                        agents_decode_msg = after_decode_data[1];
                        agents_decode_auto_id = after_decode_data[2];
                        agents_decode_email = after_decode_data[3];
                        agents_decode_update_date = after_decode_data[4];
                        agents_decode_name = after_decode_data[5];
                        currnet_select_value = '';
                        if (agents_decode_auto_id != '' && agents_decode_msg == 'agent_id_already_exsits') {
                            var currnet_select_value = $("#crea_setting_update_agent_id_" + agents_decode_auto_id).val();
                        }
                        if (currnet_select_value == agents_decode_id) {
                            $("#crea_update_agent_p_tag_" + agents_decode_auto_id).css("display", "inline-block");
                            $("#crea_update_agent_name_p_tag_" + agents_decode_auto_id).css("display", "inline-block");
                            $("#crea_update_agent_email_p_tag_" + agents_decode_auto_id).css("display", "inline-block");
                            $("#crea_setting_update_agent_id_" + agents_decode_auto_id).css("display", "none");
                            $("#crea_setting_update_agent_name_" + agents_decode_auto_id).css("display", "none");
                            $("#crea_setting_update_agent_email_" + agents_decode_auto_id).css("display", "none");
                            $("#crea_agent_setting_update_button_" + agents_decode_auto_id).css("display", "none");
                            $("#agent_modified_date_" + agents_decode_auto_id).html(agents_decode_update_date);
                            $("#crea_update_agent_email_p_tag_" + agents_decode_auto_id).html(agents_decode_email);
                            $("#crea_update_agent_name_p_tag_" + agents_decode_auto_id).html(agents_decode_name);
                        } else if (agents_decode_msg == 'agent_id_already_exsits') {
                            $('.crea-agent-settings .crea-agent-add-msg p.agent_id_exsits').css('display', 'inline-block').delay(1200).fadeOut('slow');
                            $("#crea_setting_update_agent_id_" + agents_decode_auto_id).val(agents_decode_id);
                        } else {
                            var after_update_date_split = data.split(',');
                            crea_agent_update_ids = after_update_date_split[0];
                            crea_agent_update_agent_ids = after_update_date_split[1];
                            crea_agent_update_agent_email = after_update_date_split[2];
                            crea_agent_update_date = after_update_date_split[3];
                            crea_agent_update_agent_name = after_update_date_split[4];
                            $("#crea_update_agent_p_tag_" + crea_agent_update_ids).css("display", "inline-block");
                            $("#crea_update_agent_name_p_tag_" + crea_agent_update_ids).css("display", "inline-block");
                            $("#crea_update_agent_email_p_tag_" + crea_agent_update_ids).css("display", "inline-block");
                            $("#crea_setting_update_agent_id_" + crea_agent_update_ids).css("display", "none");
                            $("#crea_setting_update_agent_name_" + crea_agent_update_ids).css("display", "none");
                            $("#crea_setting_update_agent_email_" + crea_agent_update_ids).css("display", "none");
                            $("#crea_agent_setting_update_button_" + crea_agent_update_ids).css("display", "none");
                            $("#crea_update_agent_p_tag_" + crea_agent_update_ids).html(crea_agent_update_agent_ids);
                            $("#crea_update_agent_name_p_tag_" + crea_agent_update_ids).html(crea_agent_update_agent_name);
                            $("#crea_update_agent_email_p_tag_" + crea_agent_update_ids).html(crea_agent_update_agent_email);
                            $("#agent_modified_date_" + crea_agent_update_ids).html(crea_agent_update_date);
                        }
                    }
                });
            } else {
                return false;
            }
        });

        /*************
		* END Crea setting tab UPDATE AGENT RECORDS
		**************/


        /*************
         * START Crea setting tab DELETE AGENT RECORDS
         **************/

        $('body').on('click', '.crea_agent_delete_action', function () {
            var crea_delete_id = $(this).attr('id');
            var crea_agent_id = btoa(crea_delete_id.substring(18));
            var msg = $('#record_delete_confirmation').html();
            var confirm_remove = confirm(msg);
            if (confirm_remove == true) {
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    async: false,
                    data: ({
                        action: 'aretkcrea_delete_selected_agent_records',
                        crea_agent_id: crea_agent_id,
                    }),
                    success: function (data) {
                        $('table.crea_table_setting_agent tbody.set_table_records').empty();
                        $('table.crea_table_setting_agent tbody.set_table_records').append(data);
                    }
                });
            } else {
                return false;
            }

        });

        /*************
         * END Crea setting tab DELETE AGENT RECORDS
         **************/


        /*************
         * START Crea setting tab Listing values RECORDS
         **************/

        $('body').on('click', '#aretk_crea_ddf_update_btn', function () {
            ajaxindicatorstart('Updating the data.. This can take a couple of minutes, please wait..');
            var usernameAndDdfTypeArr = [];
            var userName = '';
            var ddfType = '';
            $('.crea_table_setting tbody tr').each(function (i, selected) {
                userName = $(this).find('input').val();
                ddfType = $(this).find('select').val();

                if (userName != '' && ddfType != '') {
                    usernameAndDdfTypeArr[i] = [userName, ddfType];
                }
            });
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                data: ({
                    action: 'new_aretkcrea_fetch_total_records_of_username_ajax',
                    usernameAndDdfTypeArr: usernameAndDdfTypeArr

                }),
                success: function (data) {
                    //window.location.reload();
                    ajaxindicatorstop();
                }

            });

        });

        /*************
         * END Crea setting tab Listing values RECORDS
         **************/


        /*************
         * START Crea create lead from multiple add phone number script
         **************/

        var add_more_phone_number_counter = $("#add_more_phone_type").find('p.crea_main_phone_p_class').length;

        $('body').on('click', '#new-lead-phone', function () {
            var get_last_element = add_more_phone_number_counter - 1;
            var phone_counter_val = '';
            if (get_last_element == 0) {
                phone_counter_val = '';
            } else {
                phone_counter_val = get_last_element;
            }
            var select_box_val = $('#add_more_phone_type #aretk_crea_agent_phone_type' + phone_counter_val).attr('value');
            var phone_number_val = $('#add_more_phone_type #add_more_phone_number_id' + phone_counter_val).attr('value');
            if (select_box_val == '' || phone_number_val == '') {
                if (select_box_val == '') {
                    $('td.select-phone-type-error').text("Please Select Phone Type");
                    $('td.select-phone-type-error').css('display', 'inline-block').delay(1200).fadeOut('slow');
                }
                if (phone_number_val == '') {
                    $('td.crea-phone-error-message').text("Please Enter Number");
                    $('td.crea-phone-error-message').css('display', 'inline-block').delay(1200).fadeOut('slow');
                }
            } else {
                if (phone_number_val.match(/^\d+$/)) {
                    var not_null_box = '';
                    var crea_phone_type_array = [];
                    var crea_phone_type_number = [];
                    $("p.crea_main_phone_p_class").each(function (index) {
                        if (index == 0) {
                            not_null_box = '';
                        } else {
                            not_null_box = index;
                        }
                        var phone_type_value = $('#aretk_crea_agent_phone_type' + not_null_box).val();
                        var phone_number_value = $('#add_more_phone_number_id' + not_null_box).val();
                        if (phone_type_value == '' || phone_number_value == '') {
                            $('td .crea_phone_or_type_not_null').css('display', 'inline-block').delay(1200).fadeOut('slow');
                            ;
                        }
                        crea_phone_type_array.push(phone_type_value);
                        crea_phone_type_number.push(phone_number_value);
                    });
                    var site_path_aretk = $("#areatk_plugin_url").val();
                    if ($.inArray("", crea_phone_type_array) != '-1' || $.inArray("", crea_phone_type_number) != '-1') {
                        $(".crea_phone_or_type_not_null").css('display', 'inline-block').delay(2000).fadeOut('slow');
                        return false;
                    } else {
                        var newimageBoxDiv = $(document.createElement('p')).attr("id", 'crea_phone_p_id' + add_more_phone_number_counter);
                        newimageBoxDiv.after().html('<select id="aretk_crea_agent_phone_type' + add_more_phone_number_counter + '" class="phone_type" name="crea_agent_phone_type[]"><option value="" >Select Type</option><option value="Home" >Home</option><option value="Mobile" >Mobile</option><option value="Fax" >Fax</option></select><input type ="text" maxlength="10" value="" class ="create-new-lead-phone-no-list" id="add_more_phone_number_id' + add_more_phone_number_counter + '" name ="create_lead_phone_no[]"><a id="crea_add_more_phone_delete' + add_more_phone_number_counter + '" class="crea_add_more_phone_delete_action" href="javascript:void(0);"><img src="' + site_path_aretk + 'admin/images/delete-icon.png" class ="add_more_phone_delete_icon" alt="delete" width="20" height="20"></a>');
                        newimageBoxDiv.appendTo("#add_more_phone_type");
                        add_more_phone_number_counter++;
                        $("#add_more_phone_type p").addClass("crea_main_phone_p_class");
                    }
                } else {
                    $('td.crea-phone-error-message').text("Please Enter Number");
                    $('td.crea-phone-error-message').css('display', 'inline-block').delay(1200).fadeOut('slow');
                }
            }
        });
        $('body').on('click', '.crea_add_more_phone_delete_action', function () {
            var delete_record_id = $(this).attr('id');
            var crea_delete_record_id = delete_record_id.substring(27);
            if (add_more_phone_number_counter == 1) {
                alert("No more textbox to remove");
                return false;
            }
            add_more_phone_number_counter--;
            $(this).parent().remove();
            $("#add_more_phone_type .crea_main_phone_p_class").each(function (index, content) {
                var reoder_id = '';
                if (index == 0) {
                    reoder_id = '';
                } else {
                    reoder_id = index;
                }
                $(this).prop('id', 'crea_phone_p_id' + reoder_id);
                var get_phone_number_id = $(this).attr('id');
                $('#' + get_phone_number_id + ' .phone_type').prop('id', 'aretk_crea_agent_phone_type' + reoder_id);
                $('#' + get_phone_number_id + ' .create-new-lead-phone-no-list').prop('id', 'add_more_phone_number_id' + reoder_id);
                $('#' + get_phone_number_id + ' .crea_add_more_phone_delete_action').prop('id', 'crea_add_more_phone_delete' + reoder_id);
            });
        });

        $('body').on('change', '.phone_type', function () {
            var select_phone_id = $(this).attr('id');
            var get_select_box_value = $('#' + select_phone_id).attr('value');
            if (get_select_box_value == '') {
                $('#' + select_phone_id).css('border', '1px solid red');
                setInterval(function () {
                    $('#' + select_phone_id).css('border', '1px solid #ddd');
                }, 3000);
            }
        });

        $('body').on('keyup', '.create-new-lead-phone-no-list', function () {
            var select_phone_id = $(this).attr('id');
            var get_select_box_value = $('#' + select_phone_id).attr('value');
            if (get_select_box_value == '') {
                $('#' + select_phone_id).css('border', '1px solid red');
                setInterval(function () {
                    $('#' + select_phone_id).css('border', '1px solid #ddd');
                }, 3000);
            } else {
                if (get_select_box_value.match(/^\d+$/)) {
                    $('#' + select_phone_id).css('border', '1px solid #ddd');
                } else {
                    $('#' + select_phone_id).css('border', '1px solid red');
                    $('#' + select_phone_id).val('');
                }

            }
        });

        /*************
         * END Crea create lead from multiple add phone number script
         **************/


        /*************
         * START Crea create lead from multiple add email script
         **************/

        var scntDiv1 = $('#add_more_email');
        var add_more_email_counter = $(".crea_multiple_email_add_feature").find('p.crea_multiple_email_p_tag').length;
        var add_more_email_temp = 0;
        var add_more_email_temp_conter = 0;
        $('body').on('click', '#create-new-lead-email', function () {
            add_more_email_temp = 0;
            if (add_more_email_counter != 1) {
                var email_name = $('#email_name' + add_more_email_counter).val();
            } else {
                var email_name = $('#email_name').val();
            }
            var check_valid_email_name = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            var all_email_counter = 0;
            $('#add_more_email p').each(function () {
                var value = $(this).find('input.email_add').val();
                if (typeof value != "undefined") {
                    if (value != '' && check_valid_email_name.test(value)) {
                        $('#all_validation_check').val('');
                    } else {
                        var valied_error_id = $(this).find('input.email_add').attr('id');
                        add_more_email_temp = 1;
                    }
                }
            });
            var arrCheckNull = new Array();
            $('#add_more_email p input[type=text]').each(function () {
                var value = $(this).val();
                if (!check_valid_email_name.test(value)) {
                    if (value == '') {
                        $('td .crea-email-error-message').html("Please Fill Above Email Address").css('display', 'inline-block').delay(1200).fadeOut('slow');
                        return false;
                    } else {
                        $('td .crea-email-error-message').html("Please Enter Valid Email").css('display', 'inline-block').delay(1200).fadeOut('slow');
                        return false;
                    }
                } else {
                    arrCheckNull.push($(this).val());
                }
            });

            if ((email_name != '' && typeof email_name != "undefined") || add_more_email_temp == 0) {
                var incremneted_email = add_more_email_counter + 1;
                if (check_valid_email_name.test(email_name) || add_more_email_temp == 0) {
                    $('#add_more_email').css('display', 'block');
                    var arrText = new Array();
                    $('#add_more_email p input[type=text]').each(function () {
                        var value = $(this).val();
                        arrText.push($(this).val());
                    });
                    var data = arrText.slice(0, -1);
                    if (add_more_email_counter != 1) {
                        var sorted_email_arr = arrText.sort();
                        var results = [];
                        var emailRemoveCounter = add_more_email_counter - 1;
                        for (var i = 0; i < arrText.length - 1; i++) {
                            if (sorted_email_arr[i + 1] == sorted_email_arr[i]) {
                                results.push(sorted_email_arr[i]);
                                $('tr.view-error-message').css('display', 'inline-block');
                                $('td .crea-email-error-message').html("Your Email Already Exits").css('display', 'inline-block').delay(1200).fadeOut('slow');
                                $('#email_name' + emailRemoveCounter).val('');
                                return false;
                            }
                        }
                    }
                    var site_path_aretk = $("#areatk_plugin_url").val();
                    var all_check_email = $('input#all_validation_check').val();
                    if (add_more_email_temp == 0) {
                        add_more_email_temp_conter = 1;
                        $('.new-lead-email-list').css('display', 'inline-block');
                        $('<p class="crea_multiple_email_p_tag" id="crea_more_email_add_p_tag' + add_more_email_counter + '"><input class="email_add" type ="text" id="email_name' + add_more_email_counter + '" name ="create_lead_phone_email[]"><input type="radio" name="PrimaryEmail" id="create_new_lead_primart_mail' + add_more_email_counter + '" class ="create-new-lead-phone-no-list primery_mail_check" value="">Make Primary Email <a id="crea_add_more_email_delete' + add_more_email_counter + '" class="crea_add_more_email_delete_action" href="javascript:void(0);"><img src="' + site_path_aretk + 'admin/images/delete-icon.png" class = "delet_icon" alt="delete" width="20" height="20"></a><label class="validation_email_name"></label></p>').appendTo(scntDiv1);
                        add_more_email_counter++;
                    } else {
                        add_more_email_temp = 0;
                        $('tr.view-error-message').css('display', 'inline-block');
                        $('td .crea-email-error-message').html("Your Email is not valid Above Text");
                        $('td .crea-email-error-message').css('display', 'block').delay(1200).fadeOut('slow');
                    }
                } else {
                    $('.email-validation-error').css('display', 'inline-block');
                    $('tr.email-validation-error').css('display', 'inline-block');
                    $('td .crea-email-error-message').text("Please Enter Valid Email");
                    $('td .crea-email-error-message').css('display', 'inline-block').delay(1200).fadeOut('slow');
                }
            } else {
                if (add_more_email_temp_conter == 0) {
                    $('tr.view-error-message').css('display', 'inline-block');
                    $('td .crea-email-error-message').html("Please Enter Email");
                    $('td .crea-email-error-message').css('display', 'inline-block').delay(1200).fadeOut('slow');
                }
            }
        });

        $('body').on('click', '.crea_add_more_email_delete_action', function () {
            if (add_more_email_counter == 1) {
                alert("No more textbox to remove");
                return false;
            }
            add_more_email_counter--;
            $(this).parents('p').remove();
            add_more_email_temp = 0;
            $("#add_more_email.crea_multiple_email_add_feature .crea_multiple_email_p_tag").each(function (index, content) {
                var reoder_id = '';
                if (index == 0) {
                    reoder_id = '';
                } else {
                    reoder_id = index;
                }
                $(this).prop('id', 'crea_more_email_add_p_tag' + reoder_id);
                var get_multiple_email_ids = $(this).attr('id');
                $('#' + get_multiple_email_ids + ' .email_add').prop('id', 'email_name' + reoder_id);
                $('#' + get_multiple_email_ids + ' .create-new-lead-phone-no-list').prop('id', 'create_new_lead_primart_mail' + reoder_id);
                $('#' + get_multiple_email_ids + ' .crea_add_more_email_delete_action').prop('id', 'crea_add_more_email_delete' + reoder_id);
            });

        });

        //Make Primary Email Validation and set email
        $('body').on('change', '.primery_mail_check', function () {
            $('.primery_mail_check').val('');
            if ($(this).is(':checked')) {
                var value_primary = $(this).prev().val();
                $(this).val(value_primary);
            }
        });

        /*************
         * END Crea create lead from multiple add email script
         **************/


        /*************
         * START create lead from multiple social url script
         **************/

        var socialDiv = $('#add_more_social_type');
        var social_type_add_more_link_counter = $("#add_more_social_type").find('p.crea_add_more_social_link_main').length;
        var add_more_social_temp = 0;
        $('body').on('click', '#new-lead-social-link', function () {
            add_more_social_temp = 0;
            var site_path_aretk = $("#areatk_plugin_url").val();
            if (social_type_add_more_link_counter != 0) {
                var social_link = $('#social_url_new' + social_type_add_more_link_counter).val();
                var social_type = jQuery('#aretk_crea_new_lead_social_link' + social_type_add_more_link_counter + ' option:selected').val();
            } else {
                var social_link = $('#social_url_new').val();
                var social_type = $("#aretk_crea_new_lead_social_link option:selected").val();
            }
            $('#add_more_social_type p').each(function () {
                var all_add_more_social_type_value = $(this).find('select option:selected').val();
                var all_add_more_social_link_value = $(this).find('input.create-new-lead-social-url').val();
                if (typeof all_add_more_social_type_value != "undefined" && typeof all_add_more_social_link_value != "undefined") {
                    if (all_add_more_social_type_value != '' && all_add_more_social_link_value != '') {
                    } else {
                        add_more_social_temp = 1;
                    }
                }
            });
            if (social_link != '' && social_type != '') {
                if (add_more_social_temp == 0) {
                    var incremneted_social_type = social_type_add_more_link_counter + 1;
                    $('<p class="crea_add_more_social_link_main" id="add_more_crea_social_link_id' + social_type_add_more_link_counter + '"><input type ="text" id="social_url_new' + social_type_add_more_link_counter + '" class ="create-new-lead-social-url" name ="create_lead_social_url[]"><select id="aretk_crea_new_lead_social_link' + social_type_add_more_link_counter + '" class="social_type" name="crea_agent_social_type[]"><option value="">Select Type</option><option value="Facebook">Facebook</option><option value="Twitter">Twitter</option><option value="LinkedIn">LinkedIn</option><option value="Pinterest">Pinterest</option></select><a id="crea_add_more_social_delete' + social_type_add_more_link_counter + '" class="crea_add_more_social_delete_action" href="javascript:void(0);"><img src="' + site_path_aretk + 'admin/images/delete-icon.png" alt="delete" class="social-link-delete-icon" width="20" height="20"></a></p>').appendTo(socialDiv);
                    social_type_add_more_link_counter++;
                } else {
                    $('td .crea-all-social-validation-error-message').text("Please Fill the above box detail");
                    $('td .crea-all-social-validation-error-message').css('display', 'inline-block').delay(1200).fadeOut('slow');
                }
            }
            else if (social_link == '' && social_type != '') {
                $('td .crea-social-error-message').text("Please Enter social link");
                $('td .crea-social-error-message').css('display', 'inline-block').delay(1200).fadeOut('slow');

            } else if (social_type == '' && social_link != '') {
                $('td .select-social-type-error').text("Please select social type");
                $('td .select-social-type-error').css('display', 'inline-block').delay(1200).fadeOut('slow');
            } else if (social_type == '' && social_link == '') {
                $('td .crea-social-error-message').text("Please Enter social link");
                $('td .crea-social-error-message').css('display', 'inline-block').delay(1200).fadeOut('slow');
                $('td .select-social-type-error').text("Please select social type");
                $('td .select-social-type-error').css('display', 'inline-block').delay(1200).fadeOut('slow');
            }
        });
        $('body').on('click', '.crea_add_more_social_delete_action', function () {
            if (social_type_add_more_link_counter == 1) {
                alert("No more textbox to remove");
                return false;
            } else {
                social_type_add_more_link_counter--;
                $(this).parents('p').remove();
                add_more_social_temp = 0;
            }
            $("#add_more_social_type .crea_add_more_social_link_main").each(function (index, content) {
                var reoder_id = '';
                if (index == 0) {
                    reoder_id = '';
                } else {
                    reoder_id = index;
                }
                $(this).prop('id', 'add_more_crea_social_link_id' + reoder_id);
                var get_multiple_social_link_id = $(this).attr('id');
                $('#' + get_multiple_social_link_id + ' .create-new-lead-social-url').prop('id', 'social_url_new' + reoder_id);
                $('#' + get_multiple_social_link_id + ' .social_type').prop('id', 'aretk_crea_new_lead_social_link' + reoder_id);
                $('#' + get_multiple_social_link_id + ' .crea_add_more_social_delete_action').prop('id', 'crea_add_more_social_delete' + reoder_id);
            });

        });

        /*************
         * END create lead from multiple social url script
         **************/


        /*************
         * START Crea create lead from multiple add phone number script
         **************/
        $('body').on('click', '.correspondence_toggle', function (event) {
            event.preventDefault();
            $(this).parent().next('.correspondence_detwrap').slideToggle("slow");
        });

        /*************
         * END create lead from multiple social url script
         **************/


        /*************
         * START Crea Send email script
         **************/

        jQuery(".lead_send_email").validate({
            rules: {
                send_email_subject: {
                    required: true,
                },
                //send_email_to: {
                //     required: true
                //},
            },
            messages: {
                send_email_subject: {
                    required: "Please Enter Subject"
                },
                //send_email_to: {
                //    required: "Please Enter Email",
                //    email: "Please Enter Valid Email"
                //},
            },
            submitHandler: function (form) {
                //tinyMCE.triggerSave();
                var send_email_subject = $('#crea_send_email_lead_subject').val();
                var send_email_to = $('#crea_send_email_lead_to_email').val();
                var send_email_cc = $('#crea_send_email_lead_cc_email').val();
                var send_email_bcc = $("#crea_send_email_lead_bcc_email").val();
                var send_email_text = $('#crea_send_email_body').val();
                var check_valid_email = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
                var send_email_attechment = $('#crea_leads_send_email_browse').val();
                var file = $('#crea_leads_send_email_browse')[0].files[0];
                var fd = new FormData();
                var file = jQuery(document).find('input[type="file"]');
                var individual_file = file[0].files[0];
                var send_email_lead_id = null;
                if ($('#send_email_lead_id').length) {
                    var send_email_lead_id = $('#send_email_lead_id').val();
                    fd.append('send_email_lead_id', send_email_lead_id);
                }
                fd.append("file", individual_file);
                fd.append('action', 'aretkcrea_lead_email_send');
                fd.append('send_email_subject', send_email_subject);
                fd.append('send_email_to', send_email_to);
                fd.append('send_email_cc', send_email_cc);
                fd.append('send_email_bcc', send_email_bcc);
                fd.append('send_email_text', send_email_text);
                fd.append('send_email_lead_id', send_email_lead_id);
                var formData = new FormData();
                jQuery.ajax({
                        type: 'POST',
                        url: adminajaxjs.adminajaxjsurl,
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            switch (response) {
                                case 'sent-leads':
                                    window.location = '/wp-admin/edit.php?post_type=aretk_lead';
                                    break;
                                case 'sent-lead':
                                    window.location = '/wp-admin/admin.php?page=create_new_leads&ID=' + send_email_lead_id + '&action=edit';
                                    break;
                                case 'sent-mail':
                                    $('.succes_message').text("Your email has been sent successfully");
                                    $('.succes_message').delay(8000).fadeOut('slow');
                                    $('#crea_send_email_lead_bcc_email').val('');
                                    $('#crea_send_email_lead_subject').val('');
                                    $('#crea_send_email_lead_to_email').val('');
                                    $('#crea_send_email_lead_cc_email').val('');
                                    $('#crea_send_email_body').val('');
                                    $('#crea_leads_send_email_browse').val('');
                                    $('.succes_message').css('display', 'block');
                                    tinyMCE.activeEditor.setContent('');
                                    break;
                                default:
                                    alert('There was a problem sending your email');
                                    break;
                            }
                        }
                    });

/*                if (tinyMCE.activeEditor.getContent() != '') {
                    jQuery.ajax({
                        type: 'POST',
                        url: adminajaxjs.adminajaxjsurl,
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            switch (response) {
                                case 'sent-leads':
                                    window.location = '/wp-admin/edit.php?post_type=aretk_lead';
                                    break;
                                case 'sent-lead':
                                    window.location = '/wp-admin/admin.php?page=create_new_leads&ID=' + send_email_lead_id + '&action=edit';
                                    break;
                                case 'sent-mail':
                                    $('.succes_message').text("Your email has been sent successfully");
                                    $('.succes_message').delay(8000).fadeOut('slow');
                                    $('#crea_send_email_lead_bcc_email').val('');
                                    $('#crea_send_email_lead_subject').val('');
                                    $('#crea_send_email_lead_to_email').val('');
                                    $('#crea_send_email_lead_cc_email').val('');
                                    $('#crea_send_email_body').val('');
                                    $('#crea_leads_send_email_browse').val('');
                                    $('.succes_message').css('display', 'block');
                                    tinyMCE.activeEditor.setContent('');
                                    break;
                                default:
                                    alert('There was a problem sending your email');
                                    break;
                            }
                        }
                    });
                } else {
                    $('label.lead_send_email_editor_error_msg').css('display', 'block');
                    $('.lead_send_email_editor_error_msg').html('Please Enter Message').delay(1200).fadeOut('slow');
                }
*/            }
        });


        /*************
         *  Start new lead form script
         **************/

        $(".newleadForm").validate({
            rules: {
                create_lead_name: {required: true},
                'create_lead_phone_email[]': {required: true, email: true},
            },
            messages: {
                create_lead_name: {required: "Please Enter Name"},
                'create_lead_phone_email[]': {required: "Please Enter Email Address"},
            }
        });


        /*************
         *  START Crea setting tab INSERT AGENT RECORDS
         **************/

        $('body').on('click', '#aretk_crea_add_new_agent', function () {
            var get_agent_id = $('#aretk_crea_input_new_agent_id').val();
            var get_agent_email = $('#aretk_crea_input_new_agent_email').val();
            var get_agent_name = $('#aretk_crea_input_new_agent_name').val();
            var encoded_agent_id = '';
            var encoded_agent_email = '';
            var encoded_agent_name = '';
            var check_valid_email = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
            // check crea agent email not null
            if (get_agent_email != '') {
                //check crea email address valid or not
                if (check_valid_email.test(get_agent_email)) {
                    encoded_agent_email = btoa(get_agent_email);
                } else {
                    //set validation messages
                    $('#aretk-crea-valid-msg').css('display', 'block');
                    $('#aretk-crea-valid-msg .aretk-crea-agent-email-msg .aretk-agent-email-valid').css('display', 'block').delay(1200).fadeOut('slow');
                    $('#aretk_crea_input_new_agent_email').val('');
                }
            } else {
                //set validation messages
                $('#aretk-crea-valid-msg').css('display', 'block');
                $('#aretk-crea-valid-msg .aretk-crea-agent-email-msg .aretk-agent-email-not-empty').css('display', 'block').delay(1200).fadeOut('slow');
            }
            // check crea agent id not null
            if (get_agent_id != '') {
                encoded_agent_id = btoa(get_agent_id);
            } else {
                //set validation messages
                $('#aretk-crea-valid-msg').css('display', 'block');
                $('#aretk-crea-valid-msg .aretk-crea-agent-id-msg .aretk-agent-id-not-empty').css('display', 'block').delay(1200).fadeOut('slow');
            }

            if (get_agent_name != '') {
                encoded_agent_name = btoa(get_agent_name);
            } else {
                $('#aretk-crea-valid-msg').css('display', 'block');
                $('#aretk-crea-valid-msg .aretk-crea-agent-name-msg .aretk-agent-name-not-empty').css('display', 'block').delay(1200).fadeOut('slow');
            }
            if (get_agent_name != '' && get_agent_id != '' && get_agent_email != '' && check_valid_email.test(get_agent_email)) {
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    async: false,
                    data: ({
                        action: 'aretk_crea_add_new_agents',
                        encoded_agent_id: encoded_agent_id,
                        encoded_agent_email: encoded_agent_email,
                        encoded_agent_name: encoded_agent_name
                    }),
                    success: function (data) {
                        if (data != '' && data != 'already_exsits') {
                            $('#aretk_crea_input_new_agent_id').val('');
                            $('#aretk_crea_input_new_agent_email').val('');
                            $('#aretk_crea_input_new_agent_name').val('');
                            $('.crea-agent-settings .crea-agent-add-msg p.add-agent-sucessfully').css('display', 'inline-block').delay(1200).fadeOut('slow');
                            $('table.crea_table_setting_agent tbody.set_table_records').empty();
                            $('table.crea_table_setting_agent tbody.set_table_records').append(data);
                        }
                        if (data == 'already_exsits') {
                            $('#aretk-crea-valid-msg').css('display', 'block');
                            $('#aretk-crea-valid-msg .aretk-crea-agent-id-msg .aretk-agent-id-exsits').css('display', 'block').delay(1200).fadeOut('slow');
                        }
                    }
                });
            }
        });

        /*************
         *  END Crea setting tab INSERT AGENT RECORDS
         **************/

        /*************
         *  START Crea setting tab UPDATE Disclaimers
         **************/

        $('body').on('click', '#crea-disclaimer-update', function (e) {
            var disclaimer_pluralize = btoa($('#disclaimer_pluralize').val());
            var disclaimer_salestype = btoa($('#disclaimer_salestype').val());
            var disclaimer_licensetype = btoa($('#disclaimer_licensetype').val());
            var disclaimer_province = btoa($('#disclaimer_province').val());
            $.ajax({
                type: "POST",
                url: ajaxurl,
                async: false,
                data: {
                    action: 'aretk_crea_disclaimer_update',
                    disclaimer_pluralize: disclaimer_pluralize,
                    disclaimer_salestype: disclaimer_salestype,
                    disclaimer_licensetype: disclaimer_licensetype,
                    disclaimer_province: disclaimer_province
                },
                success: function (data) {
                    $('.crea-disclaimer-settings .suceess_msg').css('display', 'inline-block').delay(3000).fadeOut('slow');
                }
            });
        });

        /*************
         *  END Crea setting tab INSERT AGENT RECORDS
         **************/

        /*************
         *  START Map listing
         **************/
        if ($('body.aretk-geocode-listing .crea-maplisting-settings #property_address').length) {
            var geo_map;
            var geo_panorama;
            var geo_marker;
            var geo_svs;
            var geo_coder
            init_geo_map();

            function init_geo_map() {
                var property_address = document.getElementById('property_address').value;
                var property_latlng = document.getElementById('property_latlng').value;
                var property_latitude = document.getElementById('property_latitude').value;
                var property_longitude = document.getElementById('property_longitude').value;
                geo_coder = new google.maps.Geocoder();
                geo_svs = new google.maps.StreetViewService();
                geo_panorama = new google.maps.StreetViewPanorama(document.getElementById("map_pano"), {
                    //panControl: false
                    //addressControl: false
                    //linksControl: false
                    //zoomControlOptions: false
                });
                geo_map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    streetViewControl: true
                });
                geo_marker = new google.maps.Marker({
                    map: geo_map,
                    draggable: true,
                    animation: google.maps.Animation.DROP
                });
                if (!property_latitude || !property_longitude) {
                    geo_coder.geocode({'address': property_address}, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            geo_map.setCenter(results[0].geometry.location);
                            geo_marker.setPosition(results[0].geometry.location);
                            geo_svs.getPanorama({location: results[0].geometry.location, radius: 50}, processSVData);
                            document.getElementById('property_latlng').value = results[0].geometry.location;
                        }
                    });
                } else if (property_latitude || property_longitude) {
                    var geo_Latlng = new google.maps.LatLng(property_latitude, property_longitude);
                    document.getElementById('property_latlng').value = geo_Latlng;
                    geo_map.setCenter(geo_Latlng);
                    geo_marker.setPosition(geo_Latlng);
                    geo_svs.getPanorama({location: geo_Latlng, radius: 50}, processSVData);
                }
                google.maps.event.addListener(geo_marker, 'dragend', function (event) {
                    geo_svs.getPanoramaByLocation(event.latLng, 50, processSVData);
                    document.getElementById('property_latlng').value = event.latLng;
                    document.getElementById('property_latitude').value = event.latLng.lat();
                    document.getElementById('property_longitude').value = event.latLng.lng();
                });
                google.maps.event.addListener(geo_map, 'click', function (event) {
                    geo_marker.setPosition(event.latLng);
                    geo_svs.getPanoramaByLocation(event.latLng, 50, processSVData);
                    document.getElementById('property_latlng').value = event.latLng;
                    document.getElementById('property_latitude').value = event.latLng.lat();
                    document.getElementById('property_longitude').value = event.latLng.lng();
                });
                google.maps.event.addListener(geo_panorama, 'position_changed', function () {
                    geo_marker.setPosition(geo_panorama.getPosition());
                    document.getElementById('property_latlng').value = geo_panorama.getPosition();
                    document.getElementById('property_latitude').value = geo_panorama.getPosition().lat();
                    document.getElementById('property_longitude').value = geo_panorama.getPosition().lng();
                });
                google.maps.event.addListener(geo_panorama, 'pov_changed', function () {
                    document.getElementById('property_pov_heading').value = geo_panorama.getPov().heading;
                    if (!geo_panorama.getPov().zoom) {
                        document.getElementById('property_pov_zoom').value = 0;
                    } else {
                        document.getElementById('property_pov_zoom').value = Math.round(geo_panorama.getPov().zoom);
                    }
                    document.getElementById('property_pov_pitch').value = geo_panorama.getPov().pitch;
                });
            }

            function processSVData(data, status) {
                if (status === 'OK') {
                    var geo_heading = Number(document.getElementById('property_pov_heading').value);
                    if (!geo_heading) {
                        geo_heading = 270;
                    }
                    var geo_pitch = Number(document.getElementById('property_pov_pitch').value);
                    if (!geo_pitch) {
                        geo_pitch = 0;
                    }
                    var geo_zoom = Number(document.getElementById('property_pov_zoom').value);
                    if (!geo_zoom) {
                        geo_zoom = 0;
                    }
                    geo_panorama.setPano(data.location.pano);
                    geo_panorama.setPov({
                        heading: geo_heading,
                        pitch: geo_pitch,
                        zoom: geo_zoom
                    });
                    geo_map.setStreetView(geo_panorama);
                    geo_panorama.setVisible(true);
                } else {
                    $('#map_pano .map_status').text('Street View data not found for this location.');
                    $('#property_pov_heading').val('');
                    $('#property_pov_pitch').val('');
                    $('#property_pov_zoom').val('');
                    geo_panorama.setVisible(false);
                }
            }

            $('.crea-save-map-location').click(function (evt) {
                evt.preventDefault();
                $('body.aretk-geocode-listing .crea-maplisting-settings img.ajax_loading').show();
                var property_id = $('#property_id').val();
                var property_latitude = $('#property_latitude').val();
                var property_longitude = $('#property_longitude').val();
                var property_pov_heading = $('#property_pov_heading').val();
                var property_pov_pitch = $('#property_pov_pitch').val();
                var property_pov_zoom = $('#property_pov_zoom').val();
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    async: true,
                    data: ({
                        action: 'aretkcrea_map_listing',
                        property_id: btoa(property_id),
                        property_latitude: btoa(property_latitude),
                        property_longitude: btoa(property_longitude),
                        property_pov_heading: btoa(property_pov_heading),
                        property_pov_pitch: btoa(property_pov_pitch),
                        property_pov_zoom: btoa(property_pov_zoom)
                    }),
                    success: function (data) {
                        $('body.aretk-geocode-listing .crea-maplisting-settings img.ajax_loading').hide();
                        if ('pass' === data) {
                            $('body.aretk-geocode-listing .crea-maplisting-settings img.ajax_pass').fadeIn('', function () {
                                $('body.aretk-geocode-listing .crea-maplisting-settings img.ajax_pass').delay(5000).fadeOut();
                            });
                        } else {
                            alert(data);
                            $('body.aretk-geocode-listing .crea-maplisting-settings img.ajax_pass').fadeIn();
                        }
                    }
                });
            });
        }
        /*************
         *  END Map listing
         **************/

        /*************
         *  START search listing detail page multiple check box script
         **************/

        $("#selectall").click(function () {
            $('.case').attr('checked', this.checked);
        });
        $(".case").click(function () {

            if ($(".case").length == $(".case:checked").length) {
                $("#selectall").attr("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }
        });

        /*********************************************************
         END search listing detail page multiple check box script
         ******************************************************/

        $('body').on('click', '#btn_save_search_listing_showcase_button', function () {
            ajaxindicatorstart('Saving the default listings search showcase settings...');
            var search_feed_id = $("#set_feed_option :selected").val();
            var search_inc_exc_listing_feed = $("input[type='checkbox'][name='crea_showcase_inc_exc_listing_feed']:checked").val();
            var select_result_layout = $("#select_search_result_layout :selected").val();
            var search_exclude_field_all = $("input[type='checkbox'][name='search_exclude_field_all']:checked").val();
            var search_exclude_field_property_type = $("input[type='checkbox'][name='search_exclude_field_property_type']:checked").val();
			var search_exclude_field_ownership_type = $("input[type='checkbox'][name='search_exclude_field_ownership_type']:checked").val();
            var search_exclude_field_structure = $("input[type='checkbox'][name='search_exclude_field_structure']:checked").val();
            var search_exclude_field_status = $("input[type='checkbox'][name='search_exclude_field_status']:checked").val();
            
			var search_list_count = 5 - $(".search_list_checkbox_div input[type='checkbox']:checked").length;
			
            var search_exclude_field_bedrooms = $("input[type='checkbox'][name='search_exclude_field_bedrooms']:checked").val();
            var search_exclude_field_bathrooms_full = $("input[type='checkbox'][name='search_exclude_field_bathrooms_full']:checked").val();
            var search_exclude_field_finished_basement = $("input[type='checkbox'][name='search_exclude_field_finished_basement']:checked").val();
            var search_exclude_field_select_city = $("input[type='checkbox'][name='search_exclude_field_select_city']:checked").val();
            var search_max_price_ranger_value = $('#search_max_price_slider_range').val();
            var crea_search_detail_title_color_id = $("#crea_search_detail_title_color_id").val();
            var crea_search_detail_button_color_id = $("#crea_search_detail_button_color_id").val();
            var aretkcrea_showcase_search_advancefilterclosed = $('input[name="aretkcrea_showcase_search_advancefilterclosed"][type="radio"]:checked').val();
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: ({
                    action: 'aretkcrea_add_search_listing_detail_showcase_changes',
                    search_feed_id: search_feed_id,
                    search_inc_exc_listing_feed: search_inc_exc_listing_feed,
                    select_result_layout: select_result_layout,
                    search_list_count: search_list_count,
                    search_exclude_field_property_type: search_exclude_field_property_type,
					search_exclude_field_ownership_type: search_exclude_field_ownership_type,
                    search_exclude_field_structure: search_exclude_field_structure,
                    search_exclude_field_status: search_exclude_field_status,
                    search_exclude_field_bedrooms: search_exclude_field_bedrooms,
                    search_exclude_field_bathrooms_full: search_exclude_field_bathrooms_full,
                    search_exclude_field_finished_basement: search_exclude_field_finished_basement,
                    search_exclude_field_select_city: search_exclude_field_select_city,
                    crea_search_detail_title_color_id: crea_search_detail_title_color_id,
                    crea_search_detail_button_color_id: crea_search_detail_button_color_id,
                    aretkcrea_showcase_search_advancefilterclosed: aretkcrea_showcase_search_advancefilterclosed,
                    search_max_price_ranger_value: search_max_price_ranger_value
                }),
                success: function (data) {
                    setSearchShowcaseTimeInterval = setInterval(function () {
                        ajaxindicatorstop();
                        clearInterval(setSearchShowcaseTimeInterval);
                    }, 2000);
                }
            });
        });

        /************************************************
         End search showcase detail script
         ******************************************************************************/

        /************************************************
         Start  new showcase display setting  script ******************************************************************************/

        var get_onload_theam_option = $("input[type=radio][name=crea_showcase_theams_option]:checked").val();
        if (get_onload_theam_option == 'Listing View') {
            $('#crea_showcase_setting_listing_view_display').css('display', 'block');
            $('#crea_showcase_setting_grid_view_display').css('display', 'none');
            $('#crea_showcase_setting_carousel_view_display').css('display', 'none');
            $('#crea_showcase_setting_slider_view_display').css('display', 'none');
            $('#crea_showcase_setting_map_view_display').css('display', 'none');
            //preview profile page script
            $('.crea_showcase_preview_property_content').css('display', 'block');
            $('.crea_showcase_preview_grid_property_content').css('display', 'none');
            $('.crea_showcase_Map_preview_property_content').css('display', 'none');
            $('.crea_showcase_preview_carosel_property_content').css('display', 'none');
            $('.crea_showcase_preview_slider_property_content').css('display', 'none');
            //showcase color view
            $('.crea_showcase_listing_color_view').css('display', 'block');
            $('.crea_showcase_grid_color_view').css('display', 'none');
            $('.crea_showcase_carousel_color_view').css('display', 'none');
            $('.crea_showcase_map_color_view').css('display', 'none');
            $('.crea_showcase_slider_color_view').css('display', 'none');
        }
        if (get_onload_theam_option == 'Grid View') {
            $('#crea_showcase_setting_listing_view_display').css('display', 'none');
            $('#crea_showcase_setting_grid_view_display').css('display', 'block');
            $('#crea_showcase_setting_carousel_view_display').css('display', 'none');
            $('#crea_showcase_setting_slider_view_display').css('display', 'none');
            $('#crea_showcase_setting_map_view_display').css('display', 'none');
            //preview profile page script
            $('.crea_showcase_preview_property_content').css('display', 'none');
            $('.crea_showcase_preview_grid_property_content').css('display', 'block');
            $('.crea_showcase_Map_preview_property_content').css('display', 'none');
            $('.crea_showcase_preview_carosel_property_content').css('display', 'none');
            $('.crea_showcase_preview_slider_property_content').css('display', 'none');
            //showcase color view
            $('.crea_showcase_listing_color_view').css('display', 'none');
            $('.crea_showcase_grid_color_view').css('display', 'block');
            $('.crea_showcase_carousel_color_view').css('display', 'none');
            $('.crea_showcase_map_color_view').css('display', 'none');
            $('.crea_showcase_slider_color_view').css('display', 'none');
        }
        if (get_onload_theam_option == 'Carousel') {
            $('#crea_showcase_setting_listing_view_display').css('display', 'none');
            $('#crea_showcase_setting_grid_view_display').css('display', 'none');
            $('#crea_showcase_setting_carousel_view_display').css('display', 'block');
            $('#crea_showcase_setting_slider_view_display').css('display', 'none');
            $('#crea_showcase_setting_map_view_display').css('display', 'none');
            //preview profile page script
            $('.crea_showcase_preview_property_content').css('display', 'none');
            $('.crea_showcase_preview_grid_property_content').css('display', 'none');
            $('.crea_showcase_Map_preview_property_content').css('display', 'none');
            $('.crea_showcase_preview_carosel_property_content').css('display', 'block');
            $('.crea_showcase_preview_slider_property_content').css('display', 'none');
            //showcase color view
            $('.crea_showcase_listing_color_view').css('display', 'none');
            $('.crea_showcase_grid_color_view').css('display', 'none');
            $('.crea_showcase_carousel_color_view').css('display', 'block');
            $('.crea_showcase_map_color_view').css('display', 'none');
            $('.crea_showcase_slider_color_view').css('display', 'none');
        }
        if (get_onload_theam_option == 'Map') {
            $('#crea_showcase_setting_listing_view_display').css('display', 'none');
            $('#crea_showcase_setting_grid_view_display').css('display', 'none');
            $('#crea_showcase_setting_carousel_view_display').css('display', 'none');
            $('#crea_showcase_setting_slider_view_display').css('display', 'none');
            $('#crea_showcase_setting_map_view_display').css('display', 'block');
            //preview profile page script
            $('.crea_showcase_preview_property_content').css('display', 'none');
            $('.crea_showcase_preview_grid_property_content').css('display', 'none');
            $('.crea_showcase_Map_preview_property_content').css('display', 'block');
            $('.crea_showcase_preview_carosel_property_content').css('display', 'none');
            $('.crea_showcase_preview_slider_property_content').css('display', 'none');
            //showcase color view
            $('.crea_showcase_listing_color_view').css('display', 'none');
            $('.crea_showcase_grid_color_view').css('display', 'none');
            $('.crea_showcase_carousel_color_view').css('display', 'none');
            $('.crea_showcase_map_color_view').css('display', 'block');
            $('.crea_showcase_slider_color_view').css('display', 'none');
        }
        if (get_onload_theam_option == 'Slider') {
            $('#crea_showcase_setting_listing_view_display').css('display', 'none');
            $('#crea_showcase_setting_grid_view_display').css('display', 'none');
            $('#crea_showcase_setting_carousel_view_display').css('display', 'none');
            $('#crea_showcase_setting_slider_view_display').css('display', 'block');
            $('#crea_showcase_setting_map_view_display').css('display', 'none');
            //preview profile page script
            $('.crea_showcase_preview_property_content').css('display', 'none');
            $('.crea_showcase_preview_grid_property_content').css('display', 'none');
            $('.crea_showcase_Map_preview_property_content').css('display', 'none');
            $('.crea_showcase_preview_carosel_property_content').css('display', 'none');
            $('.crea_showcase_preview_slider_property_content').css('display', 'block');
            //showcase color view
            $('.crea_showcase_listing_color_view').css('display', 'none');
            $('.crea_showcase_grid_color_view').css('display', 'none');
            $('.crea_showcase_carousel_color_view').css('display', 'none');
            $('.crea_showcase_map_color_view').css('display', 'none');
            $('.crea_showcase_slider_color_view').css('display', 'block');
        }
        // display the search bar in preview yes or no script
		$('body').on('click', '.crea_showcase_setting_tab', function(){ 
            var display_search_bar_preview = $('input[name=listing_view_setiing]:checked').val();
            var display_grid_search_bar_preview = $('input[name=grid_search_view_setiing]:checked').val();
            var display_map_search_bar_preview = $('input[name=map_search_view_setiing]:checked').val();
            if (display_search_bar_preview == 'yes') {
                $('.enable_search_position').css('display', 'block');
            } else {
                $('.enable_search_position').css('display', 'none');
            }
            if (display_grid_search_bar_preview == 'yes') {
                $('.enable_grid_search_position').css('display', 'block');
            } else {
                $('.enable_grid_search_position').css('display', 'none');
            }
            if (display_map_search_bar_preview == 'yes') {
                $('.enable_map_search_position').css('display', 'block');
            } else {
                $('.enable_map_search_position').css('display', 'none');
            }
        });

        $('body').on('click', '.grid_view_search_option_selection', function () {
            var display_search_bar_preview = $('input[name=grid_search_view_setiing]:checked').val();
            if (display_search_bar_preview == 'yes') {
                $('.enable_grid_search_position').css('display', 'block');
            } else {
                $('.enable_grid_search_position').css('display', 'none');
            }
        });

        $('body').on('click', '.map_view_search_option_selection', function () {
            var display_search_bar_preview = $('input[name=map_search_view_setiing]:checked').val();

            if (display_search_bar_preview == 'yes') {
                $('.enable_map_search_position').css('display', 'block');
            } else {
                $('.enable_map_search_position').css('display', 'none');
            }
        });

        $('body').on('click', '.listing_view_search_option_selection', function () {
            var display_search_bar_preview = $('input[name=listing_view_setiing]:checked').val();
            if (display_search_bar_preview == 'yes') {
                $('.enable_search_position').css('display', 'block');
            } else {
                $('.enable_search_position').css('display', 'none');
            }
        });

        // Display the Grid view by selected column preview
        $('body').on('change', '#max_grid_selectd_column', function () {
            max_grid_selected_value == '';
            var max_grid_selected_value = $('select#max_grid_selectd_column option:selected').val();
            if (max_grid_selected_value == '1') {
                $('.selected_profile_view_by_setting').addClass('aret-col-12 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-6 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-3 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-4 grid-view-box pr');
            }
            if (max_grid_selected_value == '2') {
                $('.selected_profile_view_by_setting').addClass('grid-view-box pr aret-col-6');
                $('.selected_profile_view_by_setting').removeClass('aret-col-12 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-3 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-4 grid-view-box pr');
            }
            if (max_grid_selected_value == '3') {
                $('.selected_profile_view_by_setting').addClass('aret-col-4 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-12 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-3 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-6 grid-view-box pr');
            }
            if (max_grid_selected_value == '4') {
                $('.selected_profile_view_by_setting').addClass('aret-col-3 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-12 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-4 grid-view-box pr');
                $('.selected_profile_view_by_setting').removeClass('aret-col-6 grid-view-box pr');
            }
        });
        //Display the map view by showcase setting script
        $('body').on('change', 'input[name=filter_option_map]', function () {
            setting_map_preview_option == '';
            var setting_map_preview_option = $("input[name=filter_option_map]:checked").val();
            if (setting_map_preview_option == 'Right side of map') {
                $('.property-listing.map-view.pr .map-iframe-main').css('float', 'right');
            }
            if (setting_map_preview_option == 'Left side of map') {
                $('.property-listing.map-view.pr .map-iframe-main').css('float', 'left');
            }
        });

        $('body').on('change', '.search_crea_display_theme_option', function () {
            var display_setting = $(this).val();
            if (display_setting == 'Listing View') {
                $('#crea_showcase_setting_listing_view_display').css('display', 'block');
                $('#crea_showcase_setting_grid_view_display').css('display', 'none');
                $('#crea_showcase_setting_carousel_view_display').css('display', 'none');
                $('#crea_showcase_setting_slider_view_display').css('display', 'none');
                $('#crea_showcase_setting_map_view_display').css('display', 'none');
                //preview profile page script
                $('.crea_showcase_preview_property_content').css('display', 'block');
                $('.crea_showcase_preview_grid_property_content').css('display', 'none');
                $('.crea_showcase_Map_preview_property_content').css('display', 'none');
                $('.crea_showcase_preview_carosel_property_content').css('display', 'none');
                $('.crea_showcase_preview_slider_property_content').css('display', 'none');
                //showcase color view
                $('.crea_showcase_listing_color_view').css('display', 'block');
                $('.crea_showcase_grid_color_view').css('display', 'none');
                $('.crea_showcase_carousel_color_view').css('display', 'none');
                $('.crea_showcase_map_color_view').css('display', 'none');
                $('.crea_showcase_slider_color_view').css('display', 'none');
                $('#crea_showcase_color_tab_hidden_for_map_view').css('display', 'block');
            }
            if (display_setting == 'Grid View') {
                $('#crea_showcase_setting_listing_view_display').css('display', 'none');
                $('#crea_showcase_setting_grid_view_display').css('display', 'block');
                $('#crea_showcase_setting_carousel_view_display').css('display', 'none');
                $('#crea_showcase_setting_slider_view_display').css('display', 'none');
                $('#crea_showcase_setting_map_view_display').css('display', 'none');
                //preview profile page script
                $('.crea_showcase_preview_property_content').css('display', 'none');
                $('.crea_showcase_preview_grid_property_content').css('display', 'block');
                $('.crea_showcase_Map_preview_property_content').css('display', 'none');
                $('.crea_showcase_preview_carosel_property_content').css('display', 'none');
                $('.crea_showcase_preview_slider_property_content').css('display', 'none');
                //showcase color view
                $('.crea_showcase_listing_color_view').css('display', 'none');
                $('.crea_showcase_grid_color_view').css('display', 'block');
                $('.crea_showcase_carousel_color_view').css('display', 'none');
                $('.crea_showcase_map_color_view').css('display', 'none');
                $('.crea_showcase_slider_color_view').css('display', 'none');
                $('#crea_showcase_color_tab_hidden_for_map_view').css('display', 'block');
            }
            if (display_setting == 'Carousel') {
                $('#crea_showcase_setting_listing_view_display').css('display', 'none');
                $('#crea_showcase_setting_grid_view_display').css('display', 'none');
                $('#crea_showcase_setting_carousel_view_display').css('display', 'block');
                $('#crea_showcase_setting_slider_view_display').css('display', 'none');
                $('#crea_showcase_setting_map_view_display').css('display', 'none');
                //preview profile page script
                $('.crea_showcase_preview_property_content').css('display', 'none');
                $('.crea_showcase_preview_grid_property_content').css('display', 'none');
                $('.crea_showcase_Map_preview_property_content').css('display', 'none');
                $('.crea_showcase_preview_carosel_property_content').css('display', 'block');
                $('.crea_showcase_preview_slider_property_content').css('display', 'none');
                //showcase color view
                $('.crea_showcase_listing_color_view').css('display', 'none');
                $('.crea_showcase_grid_color_view').css('display', 'none');
                $('.crea_showcase_carousel_color_view').css('display', 'block');
                $('.crea_showcase_map_color_view').css('display', 'none');
                $('.crea_showcase_slider_color_view').css('display', 'none');
                $('#crea_showcase_color_tab_hidden_for_map_view').css('display', 'block');
            }
            if (display_setting == 'Map') {
                $('#crea_showcase_setting_listing_view_display').css('display', 'none');
                $('#crea_showcase_setting_grid_view_display').css('display', 'none');
                $('#crea_showcase_setting_carousel_view_display').css('display', 'none');
                $('#crea_showcase_setting_slider_view_display').css('display', 'none');
                $('#crea_showcase_setting_map_view_display').css('display', 'block');
                //preview profile page script
                $('.crea_showcase_preview_property_content').css('display', 'none');
                $('.crea_showcase_preview_grid_property_content').css('display', 'none');
                $('.crea_showcase_Map_preview_property_content').css('display', 'block');
                $('.crea_showcase_preview_carosel_property_content').css('display', 'none');
                $('.crea_showcase_preview_slider_property_content').css('display', 'none');
                //showcase color view
                $('.crea_showcase_listing_color_view').css('display', 'none');
                $('.crea_showcase_grid_color_view').css('display', 'none');
                $('.crea_showcase_carousel_color_view').css('display', 'none');
                $('.crea_showcase_map_color_view').css('display', 'block');
                $('.crea_showcase_slider_color_view').css('display', 'none');
                $('#crea_showcase_color_tab_hidden_for_map_view').css('display', 'none');
            }
            if (display_setting == 'Slider') {
                $('#crea_showcase_setting_listing_view_display').css('display', 'none');
                $('#crea_showcase_setting_grid_view_display').css('display', 'none');
                $('#crea_showcase_setting_carousel_view_display').css('display', 'none');
                $('#crea_showcase_setting_slider_view_display').css('display', 'block');
                $('#crea_showcase_setting_map_view_display').css('display', 'none');
                $('#crea_showcase_setting_listing_view_display').css('visibility', 'visible');
                //preview profile page script
                $('.crea_showcase_preview_property_content').css('display', 'none');
                $('.crea_showcase_preview_grid_property_content').css('display', 'none');
                $('.crea_showcase_Map_preview_property_content').css('display', 'none');
                $('.crea_showcase_preview_carosel_property_content').css('display', 'none');
                $('.crea_showcase_preview_slider_property_content').css('display', 'block');
                //showcase color view
                $('.crea_showcase_listing_color_view').css('display', 'none');
                $('.crea_showcase_grid_color_view').css('display', 'none');
                $('.crea_showcase_carousel_color_view').css('display', 'none');
                $('.crea_showcase_map_color_view').css('display', 'none');
                $('.crea_showcase_slider_color_view').css('display', 'block');
                $('#crea_showcase_color_tab_hidden_for_map_view').css('display', 'block');
            }
        });

        //Display search position based on selection
        $('body').on('change', '.listing_view_search_option_selection', function () {
            var search_setting = $(this).val();
            if (search_setting == 'yes') {
                $('.enable_search_position').css('display', 'block');
            } else {
                $('.enable_search_position').css('display', 'none');
            }
        });

        //Display Open house based on selection
        $('body').on('change', '.is_enable_open_house', function () {
            var open_house_setting = $(this).val();
            if (open_house_setting == 'yes') {
                $('p.listing_openhouse').css('display', 'block');
            } else {
                $('p.listing_openhouse').css('display', 'none');
            }
        });

        //Display Status based on selection
        $('body').on('change', '.is_enable_status', function () {
            var status_setting = $(this).val();
            if (status_setting == 'yes') {
                $('p.listing_status').css('display', 'block');
            } else {
                $('p.listing_status').css('display', 'none');
            }
        });

        /*========================================================================================*/
        // Showcase Filter Tab
        /*----------------------------------------------------------------------------------------*/

        $('body').on('click', '#crea_showcase_filter_button_tab', function () {
            var default_choosen_filter_option = $('.crea_default_choosen_option_showcse_filter_hidden_name').val();
            $(".crea_showcse_brokerage_filter").chosen({});
            $("#crea_showcse_office_filter").chosen({});
            $("#crea_showcse_agent_name_filter").chosen({});
            $("#showcase_filter_property_types").chosen({/*max_selected_options: 1*/});
			$("#showcase_filter_ownership_types").chosen({/*max_selected_options: 1*/});
            $("#showcase_filter_listing_status").chosen({max_selected_options: 1});
            $("#showcase_filter_listing_agent_ids").chosen({});
            $("#showcase_filter_listing_province").chosen({});
            if ($('.crea_showcse_office_filter_hidden_name').val()) {
                $('#crea_filter_office_wrap').css('visibility', 'visible');
            }
            if ($('.crea_showcse_agent_name_filter_hidden_name').val()) {
                $('#crea_filter_agent_wrap').css('visibility', 'visible');
            }
            var creafeed_id = $("#set_feed_option").val();
            if (creafeed_id) {
                $('.showcase_filter_by_listing').css('display', 'block');
            } else {
                $('.showcase_filter_by_listing').css('display', 'none');
            }
        });

        $('body').on('keyup', '#crea_showcase_filter_days', function () {
            var string = $(this).val();
            if (string == '') {
                $(this).attr('value', '');
            }
            return false;
        });

        // Only allow numbers
        $("#crea_showcase_filter_days").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
        // Only allow numbers
        $("#showcase_filter_price_min").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
        // Only allow numbers
        $("#showcase_filter_price_max").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
        // Only allow numbers and deciaml
        $("#crea_filter_by_map_km").keypress(function (e) {
            if (e.which != 8 && e.which != 46 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        // Update filter button for boards/office/agents selected
		$('body').on('click', '.select_board_option', function(){
            ajaxindicatorstart('Loading Board data ..');
            var getSubscriptionKey = $("#get_api_key_by_subscription").val();
            var getFeed = $('#set_feed_option option:selected').val();
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: ({
                    action: 'aretkcrea_get_the_select_board_name',
                    getSubscriptionKey: getSubscriptionKey,
                    getFeed: getFeed
                }),
                success: function (response) {
                    $('#crea_showcse_brokerage_filter').empty().append(response);
                    // Loop through all previously selected boards and re-select them
                    boards_result = $('.crea_filter_brokerage_hidden_name').val().split(';');
                    $('#crea_showcse_brokerage_filter').trigger('chosen:updated');
                    /*
                     $.each( boards_result, function( key, value ) {
                     var board = value.split(':');
                     $('#crea_showcse_brokerage_filter option[value='+board[0]+']').attr('selected','selected');
                     });
                     $('#crea_showcse_brokerage_filter').trigger('chosen:updated');
                     $('p.select_board_option').css('display','none');
                     if ( $('#crea_showcse_brokerage_filter').val() ){
                     $('#crea_filter_office_wrap').css('visibility','visible');
                     // trigger a change event to reload office data based on board IDs selected
                     $('.crea_showcse_brokerage_filter').trigger('change');
                     }
                     */
                }, complete: function () {
                    ajaxindicatorstop();
                }
            });
        });

        //  Boards data changed, load offices
		$('body').on('change', '.crea_showcse_brokerage_filter', function(){ 
            $('#crea_showcse_office_filter').val('').trigger('chosen:updated');
            $('#crea_showcse_office_filter').html('');
            $('#crea_showcse_office_filter').trigger('chosen:updated');
            var board_id = $(this).val();
            if (board_id != null) {
                var getSubscriptionKey = $("#get_api_key_by_subscription").val();
                var getFeed = $('#set_feed_option option:selected').val();
                // return list of selected boards and update hidden input
                var option_all = $(".crea_showcse_brokerage_filter option:selected").map(function (val) {
                    var board_name = $(this).text();
                    var board_id2 = $(this).val();
                    //$('#crea_showcse_office_filter option[value='+board[0]+']').attr('selected','selected');
                    return board_id2 + ':' + board_name;
                }).get().join(';');
                $('.crea_filter_brokerage_hidden_name').val(option_all);
                ajaxindicatorstart('Loading office data ..');
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    async: false,
                    data: ({
                        action: 'aretkcrea_get_the_select_board_office',
                        getSubscriptionKey: getSubscriptionKey,
                        getFeed: getFeed,
                        board_id: board_id
                    }),
                    success: function (response) {
                        $('#crea_showcse_office_filter').append(response);
                        $('#crea_showcse_office_filter').trigger('chosen:updated');
                        /*
                         // Loop through all previously selected boards and re-select them
                         offices_result = $('.crea_showcse_office_filter_hidden_name').val().split(';');
                         $.each( offices_result, function( key, value ) {
                         var office_id = value.split(':');
                         $('#crea_showcse_office_filter option[value='+office_id[0]+']').attr('selected','selected');
                         });
                         $("#crea_showcse_office_filter").chosen();
                         $('#crea_showcse_office_filter').trigger('chosen:updated');
                         setTimeIntervalreminderinsert = setInterval(function(){ ajaxindicatorstop(); clearInterval(setTimeIntervalreminderinsert);},500);
                         $('#crea_filter_office_wrap').css('visibility','visible');
                         // trigger a change event to reload office data based on board IDs selected
                         $('#crea_showcse_office_filter').trigger('change');
                         */
                    }, complete: function () {
                        $('#crea_filter_office_wrap').css('visibility', 'visible');
                        ajaxindicatorstop();
                    }
                });
            } else {
                $('.crea_filter_brokerage_hidden_name').val("");
                $('#crea_filter_office_wrap').css('visibility', 'hidden');
            }
        });

        // office filter changed
		$('body').on('change', '#crea_showcse_office_filter', function(){
            $('#crea_showcse_agent_name_filter').val('').trigger('chosen:updated');
            $('#crea_showcse_agent_name_filter').html('');
            $('#crea_showcse_agent_name_filter').trigger('chosen:updated');
            var office_id = $(this).val();
            var getSubscriptionKey = $("#get_api_key_by_subscription").val();
            var getFeed = $('#set_feed_option option:selected').val();
            var board_id = $(".crea_showcse_brokerage_filter").val();
            if (office_id != null) {
                // return list of selected offices and update hidden input and hidden select
                var option_all = $("#crea_showcse_office_filter option:selected").map(function (val) {
                    var office_name = $(this).text();
                    var office_id2 = $(this).val();
                    $('#crea_showcse_office_filter option[value=' + office_id2 + ']').attr('selected', 'selected');
                    return office_id2 + ':' + office_name;
                }).get().join(';');
                $('.crea_showcse_office_filter_hidden_name').val(option_all);

                ajaxindicatorstart('loading agent data ..');
                $.ajax({
                    type: "POST",
                    url: adminajaxjs.adminajaxjsurl,
                    async: false,
                    data: ({
                        action: 'aretkcrea_get_the_select_board_agent_name',
                        getSubscriptionKey: getSubscriptionKey,
                        getFeed: getFeed,
                        board_id: board_id,
                        office_id: office_id,
                    }),
                    success: function (response) {
                        $('#crea_showcse_agent_name_filter').append(response);
                        $('#crea_showcse_agent_name_filter').trigger('chosen:updated');
                        /*
                         // Loop through all previously selected agents and re-select them
                         agents_result = $('.crea_showcse_agent_name_filter_hidden_name').val().split(';');
                         $.each( agents_result, function( key, value ) {
                         var agent_id = value.split(':');
                         $('#crea_showcse_agent_name_filter option[value='+agent_id[0]+']').attr('selected','selected');
                         });
                         $("#crea_showcse_agent_name_filter").chosen();
                         $('#crea_showcse_agent_name_filter').trigger('chosen:updated');
                         setTimeIntervalreminderinsert = setInterval(function(){ ajaxindicatorstop(); clearInterval(setTimeIntervalreminderinsert);},500);
                         $('#crea_filter_agent_wrap').css('visibility','visible');
                         */
                    }, complete: function () {
                        $('#crea_filter_agent_wrap').css('visibility', 'visible');
                        ajaxindicatorstop();
                    }
                });
            } else {
                $('.crea_showcse_office_filter_hidden_name').val("");
                $('#crea_filter_agent_wrap').css('visibility', 'hidden');
            }
        });

        // Agent filter changed
		$('body').on('change', '#crea_showcse_agent_name_filter', function(){
            var agent_id = $(this).val();
            if (agent_id != null) {
                // return list of selected agents and update hidden input and hidden select
                var option_all = $("#crea_showcse_agent_name_filter option:selected").map(function (val) {
                    agent_name = $(this).text();
                    agent_id = $(this).val();
                    $('#crea_showcse_agent_name_filter option[value=' + agent_id + ']').attr('selected', 'selected');
                    return agent_id + ':' + agent_name;
                }).get().join(';');
                $('.crea_showcse_agent_name_filter_hidden_name').val(option_all);
            } else {
                $('.crea_showcse_agent_name_filter_hidden_name').val("");
            }
        });

        //change default listing preview color
        $('body').on('click', '#showcase_defalut_listing_preview_tab', function () {
            var DefaultlistingTextColor = $("#crea_default_listing_title_color_id").val();
            var DefaultlistingAddressbarColor = $("#crea_default_listing_address_color_id").val();
            var DefaultlistingPriceColor = $("#crea_default_listing_prise_color_id").val();
            var DefaultlistingStatusColor = $("#crea_default_listing_status_color_id").val();
            var DefaultlistingStatusTextColor = $("#default_listing_status_text_color_id").val();
            var DefaultlistingopenhouseColor = $("#crea_search_detail_button_color_id").val();
            var DefaultlistingopenhouseTextColor = $("#crea_default_listing_openhouse_text_color_id").val();
            var OpenhouseOption = $("input[type=radio][name=default_listing_view_setiing_open_house]:checked").val();
            var StatusOption = $("input[type=radio][name=default_listing_view_setiing_status]:checked").val();
            if (OpenhouseOption == 'no') {
                $('p.defalut_listing_openhouse').css('display', 'none');
            } else {
                $('p.defalut_listing_openhouse').css('display', 'block');
            }
            if (StatusOption == 'no') {
                $('p.listing_status').css('display', 'none');
            } else {
                $('p.listing_status').css('display', 'block');
            }
            $('.set_defalut_listing_address_color').css('color', '#' + DefaultlistingAddressbarColor);
            $('.crea_default_listing_price').css('color', '#' + DefaultlistingPriceColor);
            $('.defalut_listing_view_text_color').css('color', '#' + DefaultlistingTextColor);
            $('.defalut_listing_openhouse').css('background', '#' + DefaultlistingopenhouseColor);
            $('.defalut_listing_openhouse').css('color', '#' + DefaultlistingopenhouseTextColor);
            $('.listing_status').css('background', '#' + DefaultlistingStatusColor);
            $('.listing_status').css('color', '#' + DefaultlistingStatusTextColor);
        });

        // change listing preview color
        $('body').on('click', '#showcase_preview_button', function () {
            //color combination
            var getTheamOption = $("input[type=radio][name=crea_showcase_theams_option]:checked").val();
            //showcase listing view preview change
            if (getTheamOption == 'Listing View') {
                // showcase listing color option
                var listingShowcaseTextColor = $("input[type=text][name=crea_listing_showcase_text_color]").val();
                var listingShowcaseAddressbarColor = $("input[type=text][name=crea_listing_showcase_address_bar_color]").val();
                var listingShowcasePriceColor = $("input[type=text][name=crea_listing_showcase_price_color]").val();
                var listingShowcaseStatusColor = $("input[type=text][name=crea_listing_showcase_status_color]").val();
                var listingShowcaseopenhouseColor = $("input[type=text][name=crea_listing_showcase_open_house_color]").val();
                var listingShowcaseStatusTextColor = $("input[type=text][name=crea_listing_showcase_status_text_color]").val();
                var listingShowcaseopenhouseTextColor = $("input[type=text][name=crea_listing_showcase_open_house_text_color]").val();
                var ListviewopenhouseCheck = $("input[type=radio][name=listing_view_setiing_open_house]:checked").val();
                var ListviewstatusCheck = $("input[type=radio][name=listing_view_setiing_status]:checked").val();
                if (ListviewopenhouseCheck === 'yes') {
                    $('.listing_openhouse').css('display', 'block');
                } else {
                    $('.listing_openhouse').css('display', 'none');
                }
                if (ListviewstatusCheck === 'yes') {
                    $('.listing_status').css('display', 'block');
                } else {
                    $('.listing_status').css('display', 'none');
                }
                $('.set_showcase_address_color').css('color', '#' + listingShowcaseAddressbarColor);
                $('.listing_openhouse').css('background', '#' + listingShowcaseopenhouseColor);
                $('.listing_status').css('background', '#' + listingShowcaseStatusColor);
                $('.listing_openhouse').css('color', '#' + listingShowcaseopenhouseTextColor);
                $('.listing_status').css('color', '#' + listingShowcaseStatusTextColor);
                $('.crea_showcase_listing_price').css('color', '#' + listingShowcasePriceColor);
                $('.listing_view_text_color').css('color', '#' + listingShowcaseTextColor);
            }
            //showcase listing view preview change
            if (getTheamOption == 'Grid View') {
                var GridShowcaseStausColor = $("input[type=text][name=crea_showcase_status_box_color]").val();
                var GridShowcaseStausTextColor = $("input[type=text][name=crea_showcase_status_box_text_color]").val();
                var GridShowcaseAddressBarColor = $("input[type=text][name=crea_showcase_address_bar_color]").val();
                var GridShowcaseTextColor = $("input[type=text][name=crea_grid_showcase_text_color]").val();
                var GridViewStatusCheck = $("input[type=radio][name=grid_view_setiing_status]:checked").val();
                if (GridViewStatusCheck === 'yes') {
                    $('a.sky-btn.Grid_view_sky_button_color').css('display', 'block');
                } else {
                    $('a.sky-btn.Grid_view_sky_button_color').css('display', 'none');
                }
                $('a.sky-btn.Grid_view_sky_button_color').css('background', '#' + GridShowcaseStausColor);
                $('a.sky-btn.Grid_view_sky_button_color').css('color', '#' + GridShowcaseStausTextColor);
                $('.property-gried-main .heading').css('background', '#' + GridShowcaseAddressBarColor);
                $('div#triangle-topleft grid_view_star_img_preview').css('background', '#' + GridShowcaseAddressBarColor);
                $('span.bad.Grid_view_text_color').css('color', '#' + GridShowcaseTextColor);
                $('span.bathroom.Grid_view_text_color').css('color', '#' + GridShowcaseTextColor);
                $('span.Grid_view_text_color').css('color', '#' + GridShowcaseTextColor);
            }
            //showcase listing view preview change
            if (getTheamOption == 'Carousel') {
                var Carouselviewtextbacgroung = $("input[type=text][name=crea_carousel_showcase_text_color]").val();
                var crea_Carousel_showcase_tab_button_color = $("input[type=text][name=crea_carousel_showcase_background_color]").val();
                $('.carousle_view_admin ul li p').css('background', '#' + crea_Carousel_showcase_tab_button_color);
                $('.carousle_view_admin ul li p span').css('color', '#' + Carouselviewtextbacgroung);
                $('.carousel_view_openhouse_preview_show_simple').css('background', '#' + crea_Carousel_showcase_tab_button_color);
                $('.carousel_view_openhouse_preview_show_simple').css('color', '#' + Carouselviewtextbacgroung);
                var listing_carousel_show_price = $("#listing_carousel_show_price").val();
                var listing_carousel_show_status = $("#listing_carousel_show_status").val();
                var listing_carousel_show_open_house_info = $("#listing_carousel_show_open_house_info").val();
                if (( listing_carousel_show_price == 'no' || listing_carousel_show_price == '' ) && ( listing_carousel_show_status == '' || listing_carousel_show_status == 'no' )) {
                    $('.img_carosel_plugin ul li p').css('display', 'none');
                } else {
                    $('.img_carosel_plugin ul li p').css('display', 'block');
                }
                if (listing_carousel_show_price == 'yes') {
                    $('span.carousel_view_price_preview_show').css('display', 'block');
                } else {
                    $('span.carousel_view_price_preview_show').css('display', 'none');
                }
                if (listing_carousel_show_status == 'yes') {
                    $('span.carousel_view_status_preview_show').css('display', 'block');
                } else {
                    $('span.carousel_view_status_preview_show').css('display', 'none');
                }
                if (listing_carousel_show_open_house_info == 'yes') {
                    $('.carousel_view_openhouse_preview_show_simple').css('display', 'block');
                } else {
                    $('.carousel_view_openhouse_preview_show_simple').css('display', 'none');
                }
            }
            //showcase listing view preview change
            if (getTheamOption == 'Map') {
                var MapShowcaseResetButtonColor = $("input[type=text][name=crea_map_showcase_reset_button_color]").val();
                var MapShowcaseAdvanceFilterButtonColor = $("input[type=text][name=crea_map_showcase_button_color]").val();
                var MapShowcaseStatusColor = $("input[type=text][name=crea_map_showcase_top_picture_color]").val();
                var MapShowcaseStatusTextColor = $("input[type=text][name=crea_map_showcase_top_picture_text_color]").val();
                var MapShowcaseTextColor = $("input[type=text][name=crea_map_showcase_text_color]").val();
                var getmapviewdisplay_or_not = $("input[type=radio][name=only_map_view_display]:checked").val();
                var only_map_view_display_width = $("#only_map_view_display_width").val();
                var only_map_view_display_hight = $("#only_map_view_display_hight").val();
                var map_width = "";
                if (only_map_view_display_width != '') {
                    var map_width = only_map_view_display_width;
                }
                var map_height = "400";
                if (only_map_view_display_hight != '') {
                    var map_height = only_map_view_display_hight;
                }
                var only_map_view_display_hight = $("#only_map_view_display_hight").val();
                if (getmapviewdisplay_or_not == 'yes') {
                    $('.map-view-right').css('display', 'none');
                    $('.map-view .map-iframe-main').css('width', '320%');
                    $('.property-listing map-view pr').css('width', '100%');
                    $('.map_view_preview_hight_and_width_option').css('width', map_width + '%');
                    $('.map_view_preview_hight_and_width_option').css('height', map_height + 'px');

                }
                if (getmapviewdisplay_or_not == 'no') {
                    $('.map-iframe-main').css('width', '%');
                }
                $('a.map_preview_advance_filter_button').css('background', '#' + MapShowcaseAdvanceFilterButtonColor);
                $('a.map_preview_reset_button').css('background', '#' + MapShowcaseResetButtonColor);
                $('span.map_view_data_staus_preiview').css('background', '#' + MapShowcaseStatusColor);
                $('span.map_view_data_staus_preiview').css('color', '#' + MapShowcaseStatusTextColor);
                $('.map_view_text_color_preview').css('color', '#' + MapShowcaseTextColor);
            }
            //showcase listing view preview change
            if (getTheamOption == 'Slider') {
                var Sliderviewtextbacgroung = $("input[type=text][name=crea_slider_showcase_text_color]").val();
                var crea_slider_showcase_tab_button_color = $("input[type=text][name=crea_slider_showcase_tab_button_color]").val();
                $('.one-list ul li p').css('background', '#' + crea_slider_showcase_tab_button_color);
                $('.one-list ul li p span').css('color', '#' + Sliderviewtextbacgroung);
                $('.open_house_info_slider_preview').css('background', '#' + crea_slider_showcase_tab_button_color);
                $('.open_house_info_slider_preview').css('color', '#' + Sliderviewtextbacgroung);
                var listing_slider_show_price = $("#listing_slider_show_price").val();
                var listing_slider_show_status = $("#listing_slider_show_status").val();
                var listing_slider_show_open_house_info = $("#listing_slider_show_open_house_info").val();
                if (listing_slider_show_price == 'no' && listing_slider_show_status == 'no') {
                    $('.status_and_preice_display_or_not').css('display', 'none');
                } else {
                    $('.status_and_preice_display_or_not').css('display', 'block');
                }
                if (listing_slider_show_price == 'yes') {
                    $('span.slider_preview_showcase_price_display_or_not').css('display', 'block');
                } else {
                    $('span.slider_preview_showcase_price_display_or_not').css('display', 'none');
                }
                if (listing_slider_show_status == 'yes') {
                    $('span.slider_preview_showcase_status_display_or_not').css('display', 'block');
                } else {
                    $('span.slider_preview_showcase_status_display_or_not').css('display', 'none');
                }
                if (listing_slider_show_open_house_info == 'yes') {
                    $('.open_house_info_slider_preview').css('display', 'block');
                } else {
                    $('.open_house_info_slider_preview').css('display', 'none');
                }
            }
        });
        $('body').one('click', 'input#export-lead-csv', function (event) {
            event.preventDefault();
            $('.download-export-csv').css('display', 'none');
            $.ajax({
                type: "POST",
                url: adminajaxjs.adminajaxjsurl,
                async: false,
                data: ({
                    action: 'aretkcrea_emport_lead_download',
                }),
                success: function (response) {
                    $('.download-export-csv').css('display', 'block');
                    $('.download-export-csv').append(response);
                }
            });
        });
        $(".chzn-select").chosen();
        $(".se-pre-con").fadeOut("slow");

        $('a[data-rel^=lightcase]').lightcase({
            transition: 'fade'
        });

        // prevent backspace and only allow numeric keycodes
        $("#listing_view_showcase_max_listing").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
        $("#Max_of_listings_for_Carousel").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
        $("#max_of_listings_for_slider").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
		$('body').on('click', 'input[name="only_map_view_display"][type="radio"]', function(){ 
            var map_view_option_value = $('input[name="only_map_view_display"][type="radio"]:checked').val();
            if (map_view_option_value == 'yes') {
                $('p.only_map_view_widht_and_hight_display').css('display', 'block');
            }
            if (map_view_option_value == 'no') {
                $('p.only_map_view_widht_and_hight_display').css('display', 'none');
            }
        });

        $("#only_map_view_display_width").keypress(function (e) {
            if (e.which != 5 && e.which != 0 && (e.which < 48 || e.which > 57)) {

                return false;
            }
        });

        $("#only_map_view_display_hight").keypress(function (e) {
            if (e.which != 5 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        var allPanels1 = $('.menu > .page');
        $('.bxslider').bxSlider({
            auto: true,
            autoStart: true,
            autoControls: true,
            minSlides: 3,
            maxSlides: 4,
            slideWidth: 500,
            slideMargin: 10,
            auto: true,
            infiniteLoop: true,
            hideControlOnEnd: true,
            useCSS: true,
            onSliderLoad: function () {
                allPanels1.hide();
            }
        });

        $('.title > h3').click(function () {
            allPanels1.slideUp("1000");
            if ($(this).parent().next().is(':hidden')) {
                $(this).parent().next().fadeIn("500");
            }
            return false;
        });

        var allPanels = $('.menu > .page');

        $('.bxsliderAdminOne').bxSlider({
            captions: true,
            auto: true,
            autoStart: true,
            autoControls: true,
            mode: 'fade',
            minSlides: 3,
            maxSlides: 2,
            adaptiveHeight: true,
            slideWidth: 700,
            onSliderLoad: function () {
                allPanels.hide();
            }
        });

        $('.title > h3').click(function () {
            allPanels.slideUp("1000");
            if ($(this).parent().next().is(':hidden')) {
                $(this).parent().next().fadeIn("500");
            }
            return false;
        });
    });


    $(function () {
        /* search_listing_settings_html */
        //$("#tab_search_listing").tabs().addClass("ui-tabs-vertical ui-helper-clearfix");
        $("#tab_search_listing li").removeClass("ui-corner-top").addClass("ui-corner-left");

        /* Menu Styles for specific pages */
        /* aretk_lead */
        $('body.post-type-aretk_lead li#menu-posts').addClass('wp-not-current-submenu').removeClass('wp-has-current-submenu wp-menu-open open-if-no-js');
        $('body.post-type-aretk_lead li#menu-posts > a').addClass('wp-not-current-submenu').removeClass('wp-has-current-submenu wp-menu-open open-if-no-js');
        //$('body.post-type-aretk_lead #toplevel_page_crea_plugins > a').addClass('wp-has-current-submenu current').removeClass('wp-not-current-submenu');
        $('body.post-type-aretk_lead #toplevel_page_listings_settings').addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
        $('body.post-type-aretk_lead #toplevel_page_listings_settings > a').addClass('wp-has-current-submenu current').removeClass('wp-not-current-submenu');
        $('body.post-type-aretk_lead a[href="admin.php?page=leads_settings"]').parent().addClass('current');
        $('body.post-type-aretk_lead a[href="admin.php?page=leads_settings"]').addClass('current');

        /*LEAD CATEGORIES*/
        $('body.taxonomy-lead-category li#menu-posts').addClass('wp-not-current-submenu').removeClass('wp-has-current-submenu wp-menu-open open-if-no-js');
        $('body.taxonomy-lead-category li#menu-posts > a').addClass('wp-not-current-submenu').removeClass('wp-has-current-submenu wp-menu-open open-if-no-js');
        //$('body.taxonomy-lead-category #toplevel_page_crea_plugins > a').addClass('wp-has-current-submenu current').removeClass('wp-not-current-submenu');
        $('body.taxonomy-lead-category #toplevel_page_listings_settings').addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
        $('body.taxonomy-lead-category #toplevel_page_listings_settings > a').addClass('wp-has-current-submenu current').removeClass('wp-not-current-submenu');
        $('body.taxonomy-lead-category a[href="admin.php?page=create_new_lead_category"]').parent().addClass('current');
        $('body.taxonomy-lead-category a[href="admin.php?page=create_new_lead_category"]').addClass('current');

        /* Showcase Settings - Listing Details */
        $('body.admin_page_listing_details_settings #toplevel_page_listings_settings').addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
        $('body.admin_page_listing_details_settings #toplevel_page_listings_settings > a').addClass('wp-has-current-submenu current').removeClass('wp-not-current-submenu');
        $('body.admin_page_listing_details_settings #toplevel_page_listings_settings ul.wp-submenu li:nth-child(3)').addClass('current');

        /* Showcase Settings - Listings Search */
        $('body.admin_page_search_listing_settings_showcase #toplevel_page_listings_settings').addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
        $('body.admin_page_search_listing_settings_showcase #toplevel_page_listings_settings > a').addClass('wp-has-current-submenu current').removeClass('wp-not-current-submenu');
        $('body.admin_page_search_listing_settings_showcase #toplevel_page_listings_settings ul.wp-submenu li:nth-child(3)').addClass('current');

        /* Showcase Settings - Edit Showcase */
        //$('body.real-estate_page_create_new_showcase #toplevel_page_listings_settings').addClass('wp-has-current-submenu wp-menu-open menu-top menu-top-first').removeClass('wp-not-current-submenu');
        //$('body.real-estate_page_create_new_showcase #toplevel_page_listings_settings > a').addClass('wp-has-current-submenu current').removeClass('wp-not-current-submenu');
        if (!isNaN($('body.real-estate_page_create_new_showcase #showcase_ids').val())) {
            $('body.real-estate_page_create_new_showcase #toplevel_page_listings_settings ul.wp-submenu li:nth-child(3)').addClass('current');
            $('body.real-estate_page_create_new_showcase #toplevel_page_listings_settings ul.wp-submenu li:nth-child(4)').removeClass('current');
        }
    });

    /* search_listing_settings_html */
    function ajaxindicatorstart(text) {
        if ($('body').find('#resultLoading').attr('id') != 'resultLoading') {
            $('body').append('<div id="resultLoading" style="display:none"><div><img src="' + ajaxicon.loderurl + '"><div class="loading_txt">' + text + '</div></div><div class="bg"></div></div>');
        } else {
            $("#resultLoading .loading_txt").text(text);
        }
        $('#resultLoading').show();
        $('body').css('cursor', 'wait');
    }

    function ajaxindicatorstop() {
        $('#resultLoading .bg').height('100%');
        $('#resultLoading').fadeOut(300);
        $('body').css('cursor', 'default');
    }

})(jQuery);