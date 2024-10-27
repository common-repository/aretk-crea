<?php
/**
 * Listings Template - Grid View
 *
 */
# Register Styles and Java
wp_register_style( 'listings-grid-view-css', ARETK_CREA_PLUGIN_URL . 'public/templates/listings/grid-view/styles.css', false, $this->version . '19' );
wp_register_style( 'aretk-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css', array(), $this->version );
wp_enqueue_style( 'listings-grid-view-css' );
wp_enqueue_style( 'aretk-font-awesome' );

$html = '<style  type="text/css">';
$html .= 'body.aretk .aretk_listings .grid-view .info-bar,body.aretk .aretk_listings .grid-view .info-box h2,body.aretk .aretk_listings .grid-view .info-box h3{color:#' . $showcase_settings['gridShowcase_TextColor'] . ';}';
$html .= 'body.aretk .aretk_listings .grid-view .info-box{background-color:#' . $showcase_settings['gridShowcase_TextBgColor'] . ';}';
$html .= 'body.aretk .aretk_listings .grid-view .listing_openhouse{color:#' . $showcase_settings['openhouse_txt_color'] . '; background-color:#' . $showcase_settings['openhouse_bg_color'] . ';}';
$html .= 'body.aretk .aretk_listings .grid-view .footer-info{color:#' . $showcase_settings['bottom_box_color_txt'] . '; background-color:#' . $showcase_settings['bottom_box_color_bg'] . ';}';
$html .= '</style>';
if ( $showcase_settings['display_searchbar'] === 'yes' ) {
	require_once ARETK_CREA_PLUGIN_PATH . 'public/templates/listings-search/template.php';
}
$html .= '<div class="container aretk_listings" id="pagingi_container6">';
$html .= '<div class="property-listing grid-view pr">';
$html .= '<div class="aret-row">';
if ( ! empty( $allListingFinalArr ) && isset( $allListingFinalArr ) && $total_listing_records > 0 ) {
	$html .= '<span id="listings_resultset">' . $total_listing_records . ' Results | Page ' . $showcase_settings['current_page_number'] . ' of ' . $showcase_settings['max_numbers_pagination'] . '</span>';
	$html .= '<ul class="content">';
	$current_date_timestamp = strtotime( "now" );
	foreach ( $allListingFinalArr as $singleListing ) {
		include ARETK_CREA_PLUGIN_PATH . 'public/templates/listings/transmux-listing-data.php';
		$html .= '<li  class="' . $grid_view_listing_class . '" ><div class="listing_wrap">';
		$html .= '<div class="property-grid-main">';
		$html .= '<div class="property-image">';
		$html .= '<a class="property_link" style="background-image:url(' . $singleListingImage . ')" href="' . $listing_url . '" title="Click to View Property Details for ' . strip_tags( str_replace( '-', ' ', $property_address ) ) . '"><img src="' . $singleListingImage . '" alt="' . strip_tags( str_replace( '-', ' ', $property_address ) ) . '"></a>';
		if ( ! empty( $listing_openhose_datetime ) && ! is_null( $listing_openhose_datetime ) ) {
			if ( $current_date_timestamp < $openhouse_timestamp_end ) {
				$html .= '<div class="listing_openhouse"><span class="oh_title">Open House</span><span class="oh_sep">, </span>' . $listing_openhose_datetime . '</div>';
			}
		}
		$html .= '</div>';
		$html .= '<div class="info-box">';
		$html .= '<a class="property_link" href="' . $listing_url . '">';
		$html .= '<h2 class="listing_address">' . strip_tags( $property_address ) . '</h2>';
		$html .= '</a>';
		$html .= '<h3 class="listing_mlsID">';
		if ( isset( $mls_id ) && ! empty( $mls_id ) && $mls_id !== 'Exclusive' && empty( $is_exclusive_list ) ) {
			$html .= 'MLS&reg;#: ' . trim( $mls_id );
		} else {
			$html .= 'EXCLUSIVE';
		}
		$html .= '</h3>';
		$html .= '<div class="info-bar">';
		if ( ! empty( $ListingBedRooms ) && $ListingBedRooms > 0 ) {
			$html .= '<span class="bed"><i class="fa fa-bed" aria-hidden="true"></i>' . $ListingBedRooms . ' Beds</span>';
		}
		if ( ! empty( $ListingBathrooms ) && $ListingBathrooms > 0 ) {
			$html .= '<span class="bathroom"><i class="fa fa-bath" aria-hidden="true"></i>' . $ListingBathrooms . ' Baths</span>';
		}
		$html .= '</div>';
		$html .= '</div>';
		
		$html .= '<div class="footer-info"><div class="footer-info-in">';
		if ( $showcase_settings['display_listing_status'] === 'yes' && ! empty( $ListingStatus ) ) {
			$html .= '<span class="listing_status">' . $ListingStatus . '</span>';
		}
		if ( $ListingPrice != '' ) {
			$html .= '<span class="listing_price">$' . number_format( $ListingPrice ) . '</span>';
		}
		$html .= '<div class="menu">';
		$html .= '<a class="social_facebook" target="_blank" href="https://www.facebook.com/sharer.php?u=' . $listing_url . '/&amp;t=' . $listing_full_address_slug . '"></a>';
		$html .= '<a class="social_tweet" target="_blank" href="https://twitter.com/home?status=' . $listing_full_address_slug . '+' . $listing_url . '"  ></a>';
		$html .= '<a class="social_google"  target="_blank" href="https://plus.google.com/share?url=' . $listing_url . '/" ></a>';
		$html .= '<a class="social_pinterest" target="_blank" href="https://pinterest.com/pin/create/button/?url=' . $listing_url . '/&media=' . $singleListingImage . '&description=' . $listing_full_address_slug . '" ></a>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		
		$html .= '</div></li>';
	}
	$html .= '</ul>';
} else {
	$html .= '<p>Sorry no properties found</p>';
}
$html .= '</div>';

if ( $total_listing_records > 0 ) {
	if ( ! empty( $showcase_settings['max_numbers_pagination'] ) ) {
		$html .= '<div id="showcase_list_view_pagination" class="pagination">';
		$html .= Aretk_Crea_Public::custom_pagination( ceil( $showcase_settings['max_numbers_pagination'] ), "", $showcase_settings['current_page_number'] );
		$html .= '</div>';
	}
}
if ( ! empty( $showcase_settings['crea_feed_id'] ) && $showcase_settings['aretk_subscription_status'] === 'valid' ) {
	$html .= '<div class="custom_listing_content_subscription_active listings_disclaimer"><p>MLS&reg;, REALTOR&reg;, and the associated logos are trademarks of The Canadian Real Estate Association.</p></div>';
}
$html .= '</div>';
$html .= '</div>';