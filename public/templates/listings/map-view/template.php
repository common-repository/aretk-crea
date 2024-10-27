<?php
/**
 * Listings Template - Map View
 *
 */
# Register Styles and Java
$google_map_api                  = get_option( 'google-map-api-name' );
$google_map_script_loaded_or_not = get_option( 'crea_google_map_script_load_or_not' );
$google_map_api_key_pass         = '';
if ( isset( $google_map_api ) && ! empty( $google_map_api ) ) {
	$google_map_api_results  = $google_map_api;
	$google_map_api_key_pass .= "?key=$google_map_api_results";
}
wp_register_style( 'listings-map-view-css', ARETK_CREA_PLUGIN_URL . 'public/templates/listings/map-view/styles.css', array(), $this->version, 'all' );

wp_register_style( 'aretk-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css', array(), $this->version );
wp_enqueue_style( 'listings-map-view-css' );
wp_enqueue_style( 'aretk-font-awesome' );

if ( 'Yes' === $google_map_script_loaded_or_not ) {
	wp_enqueue_script( 'google-map-js', "https://maps.googleapis.com/maps/api/js$google_map_api_key_pass", array( 'jquery' ) );
	wp_enqueue_script( 'overlapping-marker-spiderfier', ARETK_CREA_PLUGIN_URL . 'public/js/oms.min.js', array(
		'jquery',
		'google-map-js'
	), $this->version, false );
	wp_enqueue_script( 'aretk-properties-map', ARETK_CREA_PLUGIN_URL . 'public/templates/listings/map-view/scripts.js', array(
		'jquery',
		'google-map-js',
		'overlapping-marker-spiderfier'
	), $this->version, false );
} else {
	wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aretk-crea-admin.js', array( 'jquery' ), $this->version, false );
}

$html = '<div class="crea_showcase_map_wrap aretk-wrap">';

if ( $display_searchbar === 'yes' ) {
	require_once ARETK_CREA_PLUGIN_PATH . 'public/templates/listings-search/template.php';
}
if ( 'Yes' === $google_map_script_loaded_or_not ) {

	$html .= '<div class="crea_showcase_map test01">';
	$html .= '<div id="gmap_canvas" style="height:' . $map_height . 'px"></div>';
	$html .= '<input type="hidden" id="subscription_feed" value="' . $showcse_crea_feed_ddf_type . '" />';
	$html .= '<input type="hidden" id="include_exclusive" value="' . $showcse_crea_feed_include_exclude . '" />';
	$html .= '<input type="hidden" id="subscription_status" value="' . $getSubscriptionListing . '" />';
	$html .= '<input type="hidden" id="center_lat" value="' . $map_center_lat . '" />';
	$html .= '<input type="hidden" id="center_lon" value="' . $map_center_long . '" />';
	$html .= '<input type="hidden" id="scid" value="' . $showcase_id . '" />';
	$html .= '<input type="hidden" id="aretk_plgn_path" value="' . ARETK_CREA_PLUGIN_URL . '" />';
	$html .= '<input type="hidden" id="map_zoom_level" value="' . $map_zoom . '" name="map_zoom_level">';
	$html .= '<div class="se-pre-con"></div>';
	$html .= '<div class="property_result_count"></div>';
	$html .= '</div>';

} else {
	$html .= '<div>';
	$html .= '<p> Include Valid Google Map Api key in plugin settings and enable load api  </p>';
	$html .= '</div>';
}