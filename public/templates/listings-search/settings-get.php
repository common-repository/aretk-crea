<?php
#$crea_search_feed_id = get_option('crea_search_feed_id');
#$crea_search_inc_exc_listing_feed = get_option('crea_search_inc_exc_listing_feed');
$crea_search_exclude_field_property_type       = get_option( 'crea_search_exclude_field_property_type' );
$crea_search_exclude_field_ownership_type       = get_option( 'crea_search_exclude_field_ownership_type' );
$crea_search_exclude_field_structure           = get_option( 'crea_search_exclude_field_structure' );
$crea_search_exclude_field_status              = get_option( 'crea_search_exclude_field_status' );
$crea_search_exclude_field_bedrooms            = get_option( 'crea_search_exclude_field_bedrooms' );
$crea_search_exclude_field_bathrooms_full      = get_option( 'crea_search_exclude_field_bathrooms_full' );
$crea_search_exclude_field_select_city         = get_option( 'crea_search_exclude_field_select_city' );
$crea_default_search_max_range_price_slider    = get_option( 'crea_search_max_price_slider_range' );
$crea_search_detail_button_color_id            = get_option( 'crea_search_detail_button_color_id' );
$crea_search_detail_title_color_id             = get_option( 'crea_search_detail_title_color_id' );
$search_list_count                             = get_option( 'select_result_layout_counter' );
$aretkcrea_showcase_search_advancefilterclosed = get_option( 'aretkcrea_showcase_search_advancefilterclosed' );
#$crea_search_exclude_field_Price = get_option('crea_search_exclude_field_Price');
#$crea_search_exclude_field_all = get_option('crea_search_exclude_field_all');

if ( ! empty( $showcase_settings['showcase_display_type'] ) && ( !empty($showcase_settings['display_searchbar_min']) && $showcase_settings['display_searchbar_min'] === 'yes' ) ) {
	$showcase_settings['display_searchbar_min'] = 'yes';
} elseif ( ! empty( $showcase_settings['showcase_display_type'] ) && ( !empty($showcase_settings['display_searchbar_min']) && $showcase_settings['display_searchbar_min'] === 'no' ) ) {
	$showcase_settings['display_searchbar_min'] = 'no';
} elseif ( isset($aretkcrea_showcase_search_advancefilterclosed) && $aretkcrea_showcase_search_advancefilterclosed === 'yes' ) {
	$showcase_settings['display_searchbar_min'] = 'no';
} else {
	$showcase_settings['display_searchbar_min'] = 'yes';
}

$crea_default_search_max_range_price_slider_value = "10000000";
if ( isset( $crea_default_search_max_range_price_slider ) && ! empty( $crea_default_search_max_range_price_slider ) ) {
	$crea_default_search_max_range_price_slider_value = $crea_default_search_max_range_price_slider;
}

$crea_search_detail_title_color = 'ffffff';
if ( isset( $crea_search_detail_title_color_id ) && ! empty( $crea_search_detail_title_color_id ) && $crea_search_detail_title_color_id != '' ) {
	$crea_search_detail_title_color = $crea_search_detail_title_color_id;
}

$crea_search_detail_button = '2012A6';
if ( isset( $crea_search_detail_button_color_id ) && ! empty( $crea_search_detail_button_color_id ) && $crea_search_detail_button_color_id != '' ) {
	$crea_search_detail_button = $crea_search_detail_button_color_id;
}

$crea_search_text_color = '000000';
if ( isset( $crea_search_text_color ) && ! empty( $crea_search_text_color ) && $crea_search_text_color != '' ) {
	$crea_search_text_color = $crea_search_text_color;
}

$select_result_layout_counter = '';
if ( isset( $search_list_count ) && ! empty( $search_list_count ) ) {
	$select_result_layout_counter = $search_list_count;
}

if ( !empty($display_searchbar_closed) && $display_searchbar_closed === 'no' ) {
	$displayserchbox = 'block';
} else {
	$displayserchbox = 'none';
}
$default_text_search_results_data = '';
if ( ! empty( $_GET['keyword'] ) ) {
	$default_text_search_results_data = $_GET['keyword'];
} else if ( ! empty( $default_text_search_results ) ) {
	$default_text_search_results_data = $default_text_search_results;
}

$crea_default_search_mini_range_price_slider_value = "0";
if ( ! empty( $min_amount_results ) ) {
	$crea_default_search_mini_range_price_slider_value = $min_amount_results;
}