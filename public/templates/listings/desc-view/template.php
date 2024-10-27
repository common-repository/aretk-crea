<?php
/**
 * Listings Template - List View
 *
 */

# Register Styles and Java
wp_register_style( 'listings-desc-view-css', ARETK_CREA_PLUGIN_URL . 'public/templates/listings/desc-view/styles.css', false, $this->version );
wp_register_style( 'aretk-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css', array(), $this->version );
wp_enqueue_style( 'listings-desc-view-css' );
wp_enqueue_style( 'aretk-font-awesome' );

if ( ! empty( $total_listing_records ) && ! empty( $showcse_crea_serializable_maxlistingonpage_results ) ) {
	$max_numbers_pagination = ( $total_listing_records / $showcse_crea_serializable_maxlistingonpage_results );
}
$current_date_timestamp = strtotime( "now" );

$html = '<style  type="text/css">';
$html .= 'body.aretk .prop-listing .info-box h2.listing_address {color:#' . $showcase_settings['address_color'] . ';}';
$html .= 'body.aretk .listing_openhouse{color:#' . $showcase_settings['open_house_color_txt'] . ';background:#' . $showcase_settings['open_house_color_bg'] . ';}';
$html .= '.listing_status{color:#' . $showcase_settings['status_color_txt'] . ';/*background:#' . $showcase_settings['status_color_bg'] . ';*/}';
$html .= 'body.aretk .prop-listing .info-box{ color:#' . $showcase_settings['maintxt_color'] . ';}';
$html .= 'body.aretk .listing_price{ color:#' . $showcase_settings['price_color'] . ';}';
$html .= '</style>';

if ( $showcase_settings['display_searchbar'] === 'yes' ) {
	require_once ARETK_CREA_PLUGIN_PATH . 'public/templates/listings-search/template.php';
}
$html .= '<div id="paging_container_listing" class="container">';

if ( ! empty( $allListingFinalArr ) && isset( $allListingFinalArr ) && $total_listing_records > 0 ) {
	$html .= '<span id="listings_resultset">' . $total_listing_records;
	if ( $total_listing_records == '1' ) {
		$html .= ' Result';
	} else {
		$html .= ' Results';
		$html .= ' | Page ' . $showcase_settings['current_page_number'] . ' of ' . $showcase_settings['max_numbers_pagination'] . '</span>';
	}
	$html .= '<ul class="content">';
	foreach ( $allListingFinalArr as $singleListing ) {
		include ARETK_CREA_PLUGIN_PATH . 'public/templates/listings/transmux-listing-data.php';
		$html .= '<li>';
		$html .= '<div class="prop-listing default pr">';
		$html .= '<div class="aret-row">';
		if ( $showcase_settings['display_openhouse_info'] === 'yes' && ! empty( $listing_openhose_datetime ) && ! is_null( $listing_openhose_datetime ) ) {
			if ( $current_date_timestamp < $openhouse_timestamp ) {
				$html .= '<div class="listing_openhouse"><span class="oh_title">Open House</span><span class="oh_sep">, </span>' . $listing_openhose_datetime . '</div>';
			}
		}

		$html .= '<div class="aret-col-3 pr listing-img-con">';

		$html .= '<a class="property_link" href="' . $listing_url . '"><img src="' . $singleListingImage . '" class="imag_listing" alt="' . $property_address . '"></a>';
		$html .= '</div>';
		$html .= '<a class="property_link" href="' . $listing_url . '">';

		$html .= '<div class="aret-col-9 no-mar-right">';
		
		$html .= '<div class="info-box">';
		if ( ! empty( $property_address ) ) {
			$html .= '<h2 class="listing_address">' . $property_address . '</h2>';
		}
		$html .= '<h4 class="default_listing_mlsID">';
		if ( isset( $ListingMls ) && ! empty( $ListingMls ) && $ListingMls !== 'Exclusive' && empty( $is_exclusive_list ) ) {
			$html .= 'MLS&reg;#: ' . trim( $ListingMls );
		} else {
			$html .= 'EXCLUSIVE';
		}
		$html .= '</h4>';
		$html .= '<div class="info-bar">';
		if ( ! empty( $ListingBedRooms ) && $ListingBedRooms > 0 ) {
			$html .= '<span class="bed"><i class="fa fa-bed" aria-hidden="true"></i>' . $ListingBedRooms . ' Bedroom</span>';
		}
		if ( ! empty( $ListingBathrooms ) && $ListingBathrooms > 0 ) {
			$html .= '<span class="bathroom"><i class="fa fa-bath" aria-hidden="true"></i>' . $ListingBathrooms . ' Bathroom</span>';
		}
		$html .= '</div>';
		if ( ! empty( $listing_description ) ) {
			$html .= '<p class="listing_desc">' . strip_tags( substr( $listing_description, 0, 180 ) ) . '...</p>';
		}
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</a>';

		$html .= '<div class="footer-info aret-col-9">';
		
		if ( $showcase_settings['display_listing_status'] === 'yes' && ! empty( $ListingStatus ) ) {
			$html .= '<span class="listing_status">' . $ListingStatus . '</span>';
		}
		if ( $ListingPrice != '' ) {
			$html .= '<span class="listing_price">$' . number_format( $ListingPrice ) . '</span>';
		}
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</li>';
	}
	$html .= '</ul>';
} else {
	$html .= '<p> Sorry no properties found </p>';
}
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