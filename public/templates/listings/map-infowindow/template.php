<?php
/**
 * Listings Template - Map Info Window
 *
 */
foreach ( $allListingArr as $singleListing ) {
	include ARETK_CREA_PLUGIN_PATH . 'public/templates/listings/transmux-listing-data.php';
	$html .= '<div class=propertyinfobox>';
	$html .= '<a class="property_link" href="' . $listing_url . '" title="Click to View Property Details for ' . strip_tags( str_replace( '-', ' ', $property_address ) ) . '" target="_blank">';
	if ( ! empty( $listing_openhose_datetime ) && ! is_null( $listing_openhose_datetime ) ) {
		$html .= '<div class="listing_openhouse" style="color:#' . $color_txt . ';background-color:#' . $color_bg . ';"><span class="oh_title">Open House</span><span class="oh_sep">, </span>' . $listing_openhose_datetime . '</div>';
	}
	$html .= '<div class="img_wrap" style="background-image:url(' . $singleListingImage . ')">';
	$html .= '<img alt="' . $property_address . '" src="' . $singleListingImage . '" />';
	$html .= '</div>';
	$html .= '<div class="listing_info">';
	$html .= '<span class="info_box info_address">' . $property_address . '</span>';
	$html .= '<span class="info_box info_status">';
	if ( isset( $ListingStatus ) && ! empty( $ListingStatus ) ) {
		$html .= $ListingStatus;
	}
	if ( isset( $ListingPrice ) && ! empty( $ListingPrice ) ) {
		if ( isset( $ListingStatus ) && ! empty( $ListingStatus ) ) {
			$html .= ': ';
		}
		$html .= '$' . number_format( $ListingPrice );
	}
	$html .= '</span>';
	$html .= '</div>';
	$html .= '<div class="info-bar">';
	if ( ! empty( $ListingBedRooms ) && $ListingBedRooms > 0 ) {
		$html .= '<span class="bed"><i class="fa fa-bed" aria-hidden="true"></i>' . $ListingBedRooms . ' Beds</span>';
	}
	if ( ! empty( $ListingBathrooms ) && $ListingBathrooms > 0 ) {
		$html .= '<span class="bathroom"><i class="fa fa-bath" aria-hidden="true"></i>' . $ListingBathrooms . ' Baths</span>';
	}
	$html .= '</div>';
	$html .= '</a>';
	$html .= '</div>';
}