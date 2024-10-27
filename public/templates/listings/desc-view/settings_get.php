<?php
/*
 * Listings Description View Settings - GET
 *
 */
# Display Settings
$settings_arr_serialized = get_post_meta( $showcase_id, 'showcse_crea_serializable_listing_array', true );
$settings_arr_serialized = ! empty( $settings_arr_serialized ) ? $settings_arr_serialized : '';
$settings_arr            = maybe_unserialize( $settings_arr_serialized );

$showcase_settings['display_searchbar']      = $settings_arr['listingviewsearchbar'];
$showcase_settings['display_searchbar_min']  = $settings_arr['Listing_search_simple_enable_or_disable'];
$showcase_settings['display_openhouse_info'] = $settings_arr['listingopenhouse'];
$showcase_settings['display_listing_status'] = $settings_arr['listingstatus'];

if ( ! empty( $settings_arr['maxlistingonpage'] ) ) {
	$showcase_settings['display_items_per_page'] = $settings_arr['maxlistingonpage'];
} else {
	$showcase_settings['display_items_per_page'] = 20;
}

switch ( $settings_arr['listingshowcasename'] ) {
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
	case 'Listing date - oldest to newest':
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
$filter_array['showcse_crea_filter_price_sorting'] = $showcase_order_property_listing_results;
$filter_array['listings_sortby']                   = $showcase_order_property_listing_results;

# END Display Settings
#-------------------------------------------

#-------------------------------------------
# Colour Settings
$colors_arr_serialized = get_post_meta( $showcase_id, 'Showcase_crea_listing_view_color_array', true );
$colors_arr_serialized = ! empty( $colors_arr_serialized ) ? $colors_arr_serialized : '';
$colors_arr            = maybe_unserialize( $colors_arr_serialized );

if ( isset( $colors_arr ) && ! empty( $colors_arr ) ) {

	$showcase_settings['status_color_bg'] = isset( $colors_arr['listingShowcaseStatusColor'] ) ? $colors_arr['listingShowcaseStatusColor'] : '#FF9898';

	$showcase_settings['status_color_txt'] = isset( $colors_arr['listingShowcaseStatusTextColor'] ) ? $colors_arr['listingShowcaseStatusTextColor'] : '#000';

	$showcase_settings['open_house_color_bg'] = isset( $colors_arr['listingShowcaseOpenHouseColor'] ) ? $colors_arr['listingShowcaseOpenHouseColor'] : '111';

	$showcase_settings['open_house_color_txt'] = isset( $colors_arr['listingShowcaseOpenHouseTextColor'] ) ? $colors_arr['listingShowcaseOpenHouseTextColor'] : 'fff';

	$showcase_settings['maintxt_color'] = isset( $colors_arr['listingShowcaseTextColor'] ) ? $colors_arr['listingShowcaseTextColor'] : '000';

	$showcase_settings['address_color'] = isset( $colors_arr['listingShowcaseAddressBarColor'] ) ? $colors_arr['listingShowcaseAddressBarColor'] : '000000';

	$showcase_settings['price_color'] = isset( $colors_arr['listingShowcasePriceColor'] ) ? $colors_arr['listingShowcasePriceColor'] : $txt_color;

	$showcase_settings['pagination_color_bg'] = isset( $colors_arr['listingShowcasepaginationColor'] ) ? $colors_arr['listingShowcasepaginationColor'] : '000';

	$showcase_settings['pagination_color_txt'] = isset( $colors_arr['listingShowcasepaginationtextColor'] ) ? $colors_arr['listingShowcasepaginationtextColor'] : 'fff';
}	