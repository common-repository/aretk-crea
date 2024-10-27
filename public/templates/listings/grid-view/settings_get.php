<?php
/*
 * Listings Grid View Settings - GET
 *
 */
#-------------------------------------------
# Display Settings
$settings_arr_serialized = get_post_meta( $showcase_id, 'showcse_crea_serializable_grid_array', true );
$settings_arr_serialized = ! empty( $settings_arr_serialized ) ? $settings_arr_serialized : '';
$settings_arr            = maybe_unserialize( $settings_arr_serialized );
# Transmux data
$showcase_settings['display_listing_status'] = $settings_arr['gridviewstatus'];
$showcase_settings['display_searchbar']      = $settings_arr['gridviewsearchbar'];
$showcase_settings['display_searchbar_min']  = $settings_arr['grid_view_setiing_status_search_simple_or_datail'];
$showcase_settings['display_max_columns']    = $settings_arr['maxgridviewselectedcolumn'];
if ( ! empty( $settings_arr['Grid_listings_batch_size'] ) ) {
	$showcase_settings['display_items_per_page'] = $settings_arr['Grid_listings_batch_size'];
} else {
	$showcase_settings['display_items_per_page'] = 20;
}

switch ( $showcase_settings['display_max_columns'] ) {
	case "1":
		$grid_view_listing_class = 'grid-view-box pr aret-col-12';
		break;
	case "2":
		$grid_view_listing_class = 'grid-view-box pr aret-col-6';
		break;
	case "3":
		$grid_view_listing_class = 'grid-view-box pr aret-col-4';
		break;
	case "4":
		$grid_view_listing_class = 'grid-view-box pr aret-col-3';
		break;
	default:
		$grid_view_listing_class = 'grid-view-box pr aret-col-3';
		break;
}

switch ( $settings_arr['gridviewshowcasename'] ) {
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
$colors_arr_serialized = get_post_meta( $showcase_id, 'Showcase_crea_grid_view_color_array', true );
$colors_arr_serialized = ! empty( $colors_arr_serialized ) ? $colors_arr_serialized : '';
$colors_arr            = maybe_unserialize( $colors_arr_serialized );

if ( isset( $colors_arr ) && ! empty( $colors_arr ) ) {

	$showcase_settings['gridShowcase_TextColor'] = isset( $colors_arr['gridShowcaseTextColor'] ) ? $colors_arr['gridShowcaseTextColor'] : '000000';

	$showcase_settings['gridShowcase_TextBgColor'] = isset( $colors_arr['gridShowcase_TextBgColor'] ) ? $colors_arr['gridShowcase_TextBgColor'] : 'fff';

	$showcase_settings['openhouse_txt_color'] = isset( $colors_arr['gridShowcase_oh_color_txt'] ) ? $colors_arr['gridShowcase_oh_color_txt'] : 'fff';

	$showcase_settings['openhouse_bg_color'] = isset( $colors_arr['gridShowcase_oh_color_bg'] ) ? $colors_arr['gridShowcase_oh_color_bg'] : '666';

	$showcase_settings['bottom_box_color_txt'] = isset( $colors_arr['gridShowcaseStatusBoxTextColor'] ) ? $colors_arr['gridShowcaseStatusBoxTextColor'] : 'fff';

	$showcase_settings['bottom_box_color_bg'] = isset( $colors_arr['gridShowcaseStatusBoxColor'] ) ? $colors_arr['gridShowcaseStatusBoxColor'] : '666';

}

# END Colour Settings 
#-------------------------------------------
