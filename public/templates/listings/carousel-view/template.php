<?php
/**
 * Listings Template - Carousel View
 *
 */
# Register Styles and Java
wp_register_style( 'owl-carousel-css', ARETK_CREA_PLUGIN_URL . 'public/css/owl.carousel.min.css', array(), $this->version, 'all' );
wp_register_style( 'owl-theme-css', ARETK_CREA_PLUGIN_URL . 'public/css/owl.theme.default.min.css', array(), $this->version, 'all' );
wp_register_style( 'listings-carousel-view-css', ARETK_CREA_PLUGIN_URL . 'public/templates/listings/carousel-view/styles.css', array(), $this->version . '8', 'all' );
wp_enqueue_style( 'owl-carousel-css' );
wp_enqueue_style( 'owl-theme-css' );
wp_enqueue_style( 'listings-carousel-view-css' );
wp_register_script( 'owl-carousel-js', ARETK_CREA_PLUGIN_URL . 'public/js/owl.carousel.min.js', array( 'jquery' ), $this->version, true );
wp_enqueue_script( 'owl-carousel-js' );

wp_register_script( 'aretk-listings-carousel', ARETK_CREA_PLUGIN_URL . 'public/templates/listings/carousel-view/scripts.js', array(
	'jquery',
	'owl-carousel-js'
), $this->version, true );
wp_enqueue_script( 'aretk-listings-carousel' );

$html = '<div class="aretk_listings">';
$html .= '<div class="showcase_carousel aretk-wrap">';
if ( ! empty( $allListingFinalArr ) && isset( $allListingFinalArr ) && $total_listing_records > 0 && $RecordsReturned >= $showcase_settings['display_min_listings'] ) {
	$html .= '<div class="owl-carousel owl-theme">';
	foreach ( $allListingFinalArr as $singleListing ) {
		include ARETK_CREA_PLUGIN_PATH . 'public/templates/listings/transmux-listing-data.php';
		$html .= '<a class="slide link" href="' . $listing_url . '" title="Click to View Property Details for ' . strip_tags( str_replace( '-', ' ', $property_address ) ) . '">';
		$html .= '<div class="img_wrap" style="background-image:url(' . $singleListingImage . '); z-index:-1;">';
		$html .= '<img alt="' . $property_address . '" src="' . $singleListingImage . '" />';
		$html .= '</div>';
		if ( $showcase_settings['display_openhouse_info'] === 'yes' && ! empty( $listing_openhose_datetime ) && ! is_null( $listing_openhose_datetime ) ) {
			$html .= '<div class="listing_openhouse" style="background-color:#' . $showcase_settings['crea_carousel_showcase_oh_color_bg'] . ';color:#' . $showcase_settings['crea_carousel_showcase_oh_color_txt'] . ';"><span class="oh_title">Open House</span><span class="oh_sep">, </span>' . $listing_openhose_datetime . '</div>';
		}
		if ( $showcase_settings['display_listing_status'] === 'yes' || $showcase_settings['display_listing_price'] === 'yes' ) {
			$html .= '<div class="listing_info" style="color:#' . $showcase_settings['bottom_banner_text_color'] . ';background-color:#' . $showcase_settings['bottom_banner_bg_color'] . ';">';
			if ( $showcase_settings['display_listing_status'] === 'yes' && ( isset( $ListingStatus ) && ! empty( $ListingStatus ) ) ) {
				$html .= $ListingStatus;
			}
			if ( $showcase_settings['display_listing_price'] === 'yes' && ( isset( $ListingPrice ) && ! empty( $ListingPrice ) ) ) {
				if ( $showcase_settings['display_listing_status'] === 'yes' && ( isset( $ListingStatus ) && ! empty( $ListingStatus ) ) ) {
					$html .= ': ';
				}
				$html .= '$' . number_format( $ListingPrice );
			}
			$html .= '</div>';
		}
		$html .= '</a>';
	}
	$html .= '</div>';

	if ( $RecordsReturned > $showcase_settings['display_min_listings'] ) {
		$carousel_loop = 'true';
	} else {
		$carousel_loop = 'false';
	}
	$html .= '<input type="hidden" name="carousel_loop" value="' . $carousel_loop . '">';
	$html .= '<input type="hidden" name="carousel_dots" value="' . $showcase_settings['listing_carousel_pagination_dots'] . '">';
	$html .= '<input type="hidden" name="carousel_prevnext" value="' . $showcase_settings['listing_carousel_display_prevnext'] . '">';
	$html .= '<input type="hidden" name="carousel_speed" value="' . $showcase_settings['listing_carousel_scroll_speed'] . '">';
	$html .= '</div>';
} 
$html .= '</div>';
$html .= '</div>';