<?php
/*
 * Listings Carousel View Settings - GET
 *
 */
#-------------------------------------------
# Display Settings
$settings_arr_serialized = get_post_meta( $showcase_id, 'showcse_crea_serializable_carousel_array', true );
$settings_arr_serialized = ! empty( $settings_arr_serialized ) ? $settings_arr_serialized : '';
$settings_arr            = maybe_unserialize( $settings_arr_serialized );

$showcase_settings['display_min_listings'] = isset( $settings_arr['minlistingcarouselshowcasename'] ) ? (int) $settings_arr['minlistingcarouselshowcasename'] : 4;

$showcase_settings['display_items_per_page'] = isset( $settings_arr['maxlistingcarouselshowcasename'] ) ? (int) $settings_arr['maxlistingcarouselshowcasename'] : 20;

$showcase_settings['display_openhouse_info'] = isset( $settings_arr['carouselshowcasenameopenhouseinfo'] ) ? $settings_arr['carouselshowcasenameopenhouseinfo'] : 'yes';

$showcase_settings['display_listing_price'] = isset( $settings_arr['carouselshowcasenameprice'] ) ? $settings_arr['carouselshowcasenameprice'] : 'yes';

$showcase_settings['display_listing_status'] = isset( $settings_arr['carouselshowcasenamestatus'] ) ? $settings_arr['carouselshowcasenamestatus'] : 'yes';

$showcase_settings['listing_carousel_display_prevnext'] = isset( $settings_arr['listing_carousel_display_prevnext'] ) ? $settings_arr['listing_carousel_display_prevnext'] : 'false';

$showcase_settings['listing_carousel_scroll_speed'] = isset( $settings_arr['listing_carousel_scroll_speed'] ) ? $settings_arr['listing_carousel_scroll_speed'] : '3000';

$showcase_settings['listing_carousel_pagination_dots'] = isset( $settings_arr['listing_carousel_pagination_dots'] ) ? $settings_arr['listing_carousel_pagination_dots'] : 'true';

switch ( $settings_arr['carouselshowcasename'] ) {
	case 'Price descending':
		$showcase_order_property_listing_results     = "price-desc";
		$showcase_settings['listings_sortby']        = "price-desc";
		$showcase_settings['listings_local_orderby'] = "desc";
		$showcase_settings['listings_local_orderon'] = "meta_value_num";
		break;
	case 'Price ascending':
		$showcase_order_property_listing_results     = "price-asc";
		$showcase_settings['listings_sortby']        = "price-asc";
		$showcase_settings['listings_local_orderby'] = "asc";
		$showcase_settings['listings_local_orderon'] = "meta_value_num";
		break;
	case 'Listing date - newest to oldest':
		$showcase_order_property_listing_results     = "new2old";
		$showcase_settings['listings_sortby']        = "new2old";
		$showcase_settings['listings_local_orderby'] = "desc";
		$showcase_settings['listings_local_orderon'] = "date";
		break;
	case 'Listing date - oldest to newest':
		$showcase_order_property_listing_results     = "old2new";
		$showcase_settings['listings_sortby']        = "old2new";
		$showcase_settings['listings_local_orderby'] = "acs";
		$showcase_settings['listings_local_orderon'] = "date";
		break;
	case 'Random':
		$showcase_order_property_listing_results     = "rand";
		$showcase_settings['sort_listings_by']       = "rand";
		$showcase_settings['listings_local_orderby'] = "rand";
		$showcase_settings['listings_local_orderon'] = "rand";
		break;
	default:
		$showcase_order_property_listing_results     = "price-desc";
		$showcase_settings['listings_sortby']        = "price-desc";
		$showcase_settings['listings_local_orderby'] = "desc";
		$showcase_settings['listings_local_orderon'] = "meta_value_num";
		break;
}

$filter_array['listings_sortby'] = $showcase_order_property_listing_results;

# END Display Settings
#-------------------------------------------

#-------------------------------------------
# Colour Settings
$colors_arr_serialized = get_post_meta( $showcase_id, 'Showcase_crea_carousel_view_color_array', true );
$colors_arr_serialized = ! empty( $colors_arr_serialized ) ? $colors_arr_serialized : '';
$colors_arr            = maybe_unserialize( $colors_arr_serialized );


if ( isset( $colors_arr ) && ! empty( $colors_arr ) ) {

	$showcase_settings['bottom_banner_text_color'] = isset( $colors_arr['carouselShowcaseTextColor'] ) ? $colors_arr['carouselShowcaseTextColor'] : '#000';

	$showcase_settings['bottom_banner_bg_color'] = isset( $colors_arr['carouselShowcaseBackgroundColor'] ) ? $colors_arr['carouselShowcaseBackgroundColor'] : '#dadada';

	$showcase_settings['crea_carousel_showcase_oh_color_txt'] = isset( $colors_arr['crea_carousel_showcase_oh_color_txt'] ) ? $colors_arr['crea_carousel_showcase_oh_color_txt'] : '#fff';

	$showcase_settings['crea_carousel_showcase_oh_color_bg'] = isset( $colors_arr['crea_carousel_showcase_oh_color_bg'] ) ? $colors_arr['crea_carousel_showcase_oh_color_bg'] : '#000';

}

# END Colour Settings 
#-------------------------------------------