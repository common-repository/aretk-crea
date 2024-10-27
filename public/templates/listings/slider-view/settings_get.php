<?php
/*
 * Listings Slider View Settings - GET
 *
 */

#------------------------------------------- 
# Display Settings 
$settings_arr_serialized = get_post_meta( $showcase_id, 'showcse_crea_serializable_slider_array', true );
$settings_arr_serialized = ! empty( $settings_arr_serialized ) ? $settings_arr_serialized : '';
$settings_arr            = maybe_unserialize( $settings_arr_serialized );

$showcase_settings['display_listing_price'] = ! empty( $settings_arr['slidershowcaseshowprice'] ) ? $settings_arr['slidershowcaseshowprice'] : null;

$showcase_settings['display_listing_status'] = ! empty( $settings_arr['slidershowcaseshowstatus'] ) ? $settings_arr['slidershowcaseshowstatus'] : null;

$showcase_settings['display_openhouse_info'] = ! empty( $settings_arr['slidershowcaseshowprice'] ) ? $settings_arr['slidershowcaseshowprice'] : null;

$showcase_settings['display_min_listings'] = isset( $settings_arr['minslidershowcaselisting'] ) ? $settings_arr['minslidershowcaselisting'] : 1;

$showcase_settings['display_items_per_page'] = isset( $settings_arr['maxslidershowcaselisting'] ) ? $settings_arr['maxslidershowcaselisting'] : 20;

//Slider View Sorting 
switch ( $settings_arr['sildersortingshowcasename'] ) {
	case 'Price descending':
		$showcase_order_property_listing_results = "price-desc";
		break;
	case 'Price ascending':
		$showcase_order_property_listing_results = "price-asc";
		break;
	case 'Listing date - newest to oldest':
		$showcase_order_property_listing_results = "new2old";
		break;
	case 'Listing date - oldest to newest':
		$showcase_order_property_listing_results = "old2new";
		break;
	case 'Random':
		$showcase_order_property_listing_results = "rand";
		break;
	default:
		$showcase_order_property_listing_results = "price-desc";
		break;
}

$filter_array['listings_sortby'] = $showcase_order_property_listing_results;

# END Display Settings
#-------------------------------------------

#-------------------------------------------
# Colour Settings 

// Color Setting
$colors_arr_serialized = get_post_meta( $showcase_id, 'Showcase_crea_slider_view_color_array', true );
$colors_arr_serialized = ! empty( $colors_arr_serialized ) ? $colors_arr_serialized : '';
$colors_arr            = maybe_unserialize( $colors_arr_serialized );

if ( isset( $colors_arr ) && ! empty( $colors_arr ) ) {
	$color_txt = isset( $colors_arr['sliderShowcaseTextColor'] ) ? $colors_arr['sliderShowcaseTextColor'] : 'fff';
	$color_bg  = isset( $colors_arr['sliderShowcaseTabBtnColor'] ) ? $colors_arr['sliderShowcaseTabBtnColor'] : '000';

	$oh_color_txt = isset( $colors_arr['crea_slider_showcase_oh_color_txt'] ) ? $colors_arr['crea_slider_showcase_oh_color_txt'] : 'fff';
	$oh_color_bg  = isset( $colors_arr['crea_slider_showcase_oh_color_bg'] ) ? $colors_arr['crea_slider_showcase_oh_color_bg'] : 'ff0000';

	$progressbar_color_bg = isset( $colors_arr['sliderShowcaseMoreInfoBtnColor'] ) ? $colors_arr['sliderShowcaseMoreInfoBtnColor'] : '#777';
}

# END Colour Settings 
#-------------------------------------------
