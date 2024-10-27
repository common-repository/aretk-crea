<?php
/**
 * Listings Template - Slider View
 *
 */
# Register Styles and Java
wp_register_style( 'owl-carousel-css', ARETK_CREA_PLUGIN_URL . 'public/css/owl.carousel.min.css', array(), $this->version, 'all' );
wp_register_style( 'owl-theme-css', ARETK_CREA_PLUGIN_URL . 'public/css/owl.theme.default.min.css', array(), $this->version, 'all' );
wp_register_style( 'animate-css', ARETK_CREA_PLUGIN_URL . 'public/css/animate.min.css', array(), $this->version, 'all' );
wp_register_style( 'aretk-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css', array(), $this->version );
wp_register_style( 'listings-slider-view-css', ARETK_CREA_PLUGIN_URL . 'public/templates/listings/slider-view/styles.css', array(), $this->version . '8', 'all' );
wp_enqueue_style( 'owl-carousel-css' );
wp_enqueue_style( 'owl-theme-css' );
wp_enqueue_style( 'animate-css' );
wp_enqueue_style( 'aretk-font-awesome' );
wp_enqueue_style( 'listings-slider-view-css' );
wp_register_script( 'owl-carousel-js', ARETK_CREA_PLUGIN_URL . 'public/js/owl.carousel.min.js', array( 'jquery' ), $this->version, true );
wp_enqueue_script( 'owl-carousel-js' );
wp_register_script( 'aretk-listings-slider', ARETK_CREA_PLUGIN_URL . 'public/templates/listings/slider-view/scripts.js', array(
	'jquery',
	'owl-carousel-js'
), $this->version, true );
wp_enqueue_script( 'aretk-listings-slider' );

$html = '<style  type="text/css">';
$html .= 'body.aretk .crea_showcase_slider .listing_info{color:#' . $color_txt . ';background-color:#' . $color_bg . ';}';
$html .= 'body.aretk .crea_showcase_slider .listing_info .listing_openhouse{ color:#' . $oh_color_txt . ';background-color:#' . $oh_color_bg . ';}';
$html .= '</style>';
$html .= '<div class="crea_showcase_slider aretk-wrap">';
if ( ! empty( $allListingFinalArr ) && isset( $allListingFinalArr ) && $total_listing_records > 0 ) {
	$html .= '<div id="slider1" class="owl-carousel owl-theme">';
	foreach ( $allListingFinalArr as $singleListing ) {
		include ARETK_CREA_PLUGIN_PATH . 'public/templates/listings/transmux-listing-data.php';
		$html .= '<a class="slide link" href="' . $listing_url . '" title="Click to View Property Details for ' . strip_tags( str_replace( '-', ' ', $property_address ) ) . '">';
		$html .= '<figure style="background-image:url(' . $singleListingImage . ')">';
		$html .= '<img alt="' . $property_address . '" src="' . $singleListingImage . '" />';
		$html .= '<figcaption>' . $property_address . '</figcaption>';
		$html .= '</figure>';
		$html .= '<div class="listing_info">';
		$html .= '<h3 class="listing_address">' . $property_address . '</h3>';
		$html .= '<div class="listing_statusprice">';
		if ( $showcase_settings['display_listing_status'] === 'yes' && ( isset( $ListingStatus ) && ! empty( $ListingStatus ) ) ) {
			$html .= $ListingStatus;
		}
		if ( $showcase_settings['display_listing_price'] === 'yes' && ( isset( $ListingPrice ) && ! empty( $ListingPrice ) ) ) {
			if ( $showcase_settings['display_listing_status'] === 'yes' && ( isset( $ListingStatus ) && ! empty( $ListingStatus ) ) ) {
				$html .= ': ';
			}
			$html .= '<span>$' . number_format( $ListingPrice ) . '</span>';
		}
		$html .= '</div>';
		$html .= '<div class="info-bar">';
		if ( ! empty( $ListingBedRooms ) && $ListingBedRooms > 0 ) {
			$html .= '<span class="bed"><i class="fa fa-bed" aria-hidden="true"></i>' . $ListingBedRooms . ' Beds</span>';
		}
		if ( ! empty( $ListingBathrooms ) && $ListingBathrooms > 0 ) {
			$html .= '<span class="bathroom"><i class="fa fa-bath" aria-hidden="true"></i>' . $ListingBathrooms . ' Baths</span>';
		}
		$html .= '</div>';
		if ( $showcase_settings['display_openhouse_info'] === 'yes' && ! empty( $listing_openhose_datetime ) && ! is_null( $listing_openhose_datetime ) ) {
			$html .= '<div class="listing_openhouse"><span class="oh_title">Open House</span><span class="oh_sep">, </span>' . $listing_openhose_datetime . '</div>';
		}
		$html .= '</div>';
		$html .= '</a>';
	}
	$html .= '</div>';

	if ( $RecordsReturned > 2 ) {
		$carousel_loop = 'true';
		$progressBar   = 'progressBar';
	} else {
		$carousel_loop = 'false';
		$progressBar   = 'progressBarNot';
	}
	$html .= '<input type="hidden" name="carousel_loop" value="' . $carousel_loop . '">';
	$html .= '<input type="hidden" name="carousel_pbar" value="' . $progressBar . '">';
	$html .= '<input type="hidden" name="progressbar_color" value="' . $progressbar_color_bg . '">';
	$html .= '<input type="hidden" name="display_nav" value="false">';
	$html .= '<input type="hidden" name="display_dots" value="false">';
	$html .= '<input type="hidden" name="slide_view_time" value="10">';

}
$html .= '</div>';