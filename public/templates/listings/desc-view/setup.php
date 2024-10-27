<?php
global $post, $wpdb;
$allListingArr      = array();
$allListingFinalArr = array();
$filter_array       = array();
$showcase_settings  = array();
$postmeta_arr       = array();

$result_type     = 'basic';
$subscriptionKey = get_option( 'crea_subscription_key', '' );

$site_image_path                            = ARETK_CREA_PLUGIN_URL . 'public/images/preview_img.jpg';
$showcase_settings['default_listing_image'] = $site_image_path;

$getSubscriptionListing = get_option( 'crea_subscription_status', '' );

$showcase_settings['aretk_subscription_status'] = get_option( 'crea_subscription_status', '' );
$showcse_crea_display_theme_option              = 'Grid View'; # 'Listing View', 'Grid View', 'Map'
$showcase_settings['showcase_display_type']     = $showcse_crea_display_theme_option;
$filter_array['showcase_view']                  = $showcse_crea_display_theme_option;
$filter_array['include_exclusive']              = 'yes';


$page_number_id = (int) basename( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
if ( is_numeric( $page_number_id ) && $page_number_id !== 0 ) {
	$showcase_settings['current_page_number'] = $page_number_id;
} else {
	$page_number_id                           = 1;
	$showcase_settings['current_page_number'] = 1;
}
$showcase_settings['display_items_per_page'] = 20;
$offset                                      = ( $showcase_settings['current_page_number'] - 1 ) * $showcase_settings['display_items_per_page'] + 1;
$filter_array['current_page_number']         = (int) $showcase_settings['current_page_number'];

if ( $getSubscriptionListing === 'valid' ) {
	$crea_user_name_table_name    = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
	$sql_select                   = "SELECT `username` FROM `$crea_user_name_table_name`";
	$sql_prep                     = $wpdb->prepare( $sql_select, null );
	$getAllUsername               = $wpdb->get_results( $sql_prep );
	$userName                     = $getAllUsername[0]->username;
	$showcse_crea_feed_ddf_type   = ! empty( $userName ) ? $userName : '';
	$filter_array['crea_feed_id'] = $showcse_crea_feed_ddf_type;
}

$showcase_order_property_listing_results           = "price-desc";
$showcase_settings['listings_sortby']              = "price-desc";
$showcase_settings['listings_local_orderby']       = "desc";
$showcase_settings['listings_local_orderon']       = "meta_value_num";
$filter_array['showcse_crea_filter_price_sorting'] = $showcase_order_property_listing_results;
$filter_array['listings_sortby']                   = $showcase_order_property_listing_results;

$filter_array = Aretk_Crea_Public::aretk_listing_filters( $filter_array, $postmeta_arr );

$transient_id = implode( '|', $filter_array );
$transient_id = 'aretk_' . md5( $transient_id );

$listing_results = get_transient( $transient_id );
$listing_results = false;
if ( $showcse_crea_display_theme_option !== 'Map' ) {
	if ( false === $listing_results ) {
		if ( isset( $getSubscriptionListing ) && ! empty( $getSubscriptionListing ) && ! empty( $showcse_crea_feed_ddf_type ) && $getSubscriptionListing === 'valid' && $showcse_crea_display_theme_option !== 'Map' ) {
			$listing_results = Aretk_Crea_Public::aretk_get_listings_subsc( $subscriptionKey, $filter_array );
		} else if ( $showcse_crea_display_theme_option !== 'Map' ) {
			$listing_results = Aretk_Crea_Public::aretk_get_listings_localwp( $showcase_id, $filter_array );
		}
		set_transient( $transient_id, $listing_results, 60 * 60 );
	}
	$allListingFinalArr    = $listing_results['listing_data'];
	$total_listing_records = $listing_results['TotalRecords'];
	$RecordsReturned       = $listing_results['RecordsReturned'];
	if ( ! empty( $total_listing_records ) && ( $showcse_crea_display_theme_option === 'Grid View' || $showcse_crea_display_theme_option === 'Listing View' ) ) {
		$showcase_settings['max_numbers_pagination'] = ceil( ( $total_listing_records / $showcase_settings['display_items_per_page'] ) );
	}
}
require_once plugin_dir_path( __FILE__ ) . 'template.php';
echo $html;			