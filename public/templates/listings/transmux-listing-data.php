<?php
/* 
   Transmux Listing data
*/
$site_image_path           = ARETK_CREA_PLUGIN_URL . 'public/images/preview_img.jpg';
$listing_full_address_slug = null;
$property_address          = null;

if (isset($showcse_crea_serializable_maxlistingonpage_results_counter) && is_numeric($showcse_crea_serializable_maxlistingonpage_results_counter) && !empty($showcse_crea_serializable_maxlistingonpage_results_counter) )
{
	
} else {
	$showcse_crea_serializable_maxlistingonpage_results_counter = 0;
}
if ( isset( $singleListing->post_author ) && ! empty( $singleListing->post_author ) ) {
	# Exclusive Listing
		
	$l_id              = $singleListing->ID;
	$content_post      = get_post( $l_id );
	if ( ! empty( $content_post ) && $content_post != '' ) {
		$listing_description = $content_post->post_content;
	}
	$ListingAddress             = get_post_meta( $l_id, 'listingAddress', true );
	$ListingCity                = get_post_meta( $l_id, 'listingcity', true );
	$listingProvince            = get_post_meta( $l_id, 'listingProvince', true );
	$ListingPrice               = get_post_meta( $l_id, 'listingPrice', true );
	$ListingMls               = get_post_meta( $l_id, 'listingMls', true );
	$mls_id = $ListingMls;
	$is_exclusive_list = 		!empty($ListingMls) ? '' : '/exclusive';
	$ListingBedRooms            = get_post_meta( $l_id, 'listingBedRooms', true );
	$ListingBedRooms            = ! empty( $ListingBedRooms ) ? $ListingBedRooms : '';
	$ListingBathrooms           = get_post_meta( $l_id, 'listingBathrooms', true );
	$ListingBathrooms           = ! empty( $ListingBathrooms ) ? $ListingBathrooms : '';
	$ListingStatus              = get_post_meta( $l_id, 'listingAgentStatus', true );
	$ListingStatus              = ! empty( $ListingStatus ) ? $ListingStatus : '';
	$listing_openhose_datetime  = null;
	$openhouse_timestamp        = null;
	$listingopenhosedatetimeArr = get_post_meta( $l_id, 'listingopenhosedatetimeArr', true );
	$listingopenhosedatetimeArr = json_decode( $listingopenhosedatetimeArr );
	$listingopenhosedatetimeArr = ! empty( $listingopenhosedatetimeArr ) ? $listingopenhosedatetimeArr : '';
	$current_date_timestamp     = strtotime( "now" );
	foreach ( $listingopenhosedatetimeArr as $oh ) {
		if ( ! empty( $oh->date ) && ! empty( $oh->end_time ) ) {
			$openhouse_timestamp_start = strtotime( $oh->date . ' ' . $oh->start_time );
			$openhouse_timestamp_end   = strtotime( $oh->date . ' ' . $oh->end_time );
			if ( $current_date_timestamp < $openhouse_timestamp_end ) {
				$listing_openhose_datetime = date( 'M j \&\#\6\4\; g:ia', $openhouse_timestamp_start );
				break;
			}
		}
	}
	$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
	$sql_select                            = "SELECT `image_url` FROM `$crea_listing_images_detail_table_name` WHERE `image_position`=1 AND `unique_id` = %d";
	$sql_prep                              = $wpdb->prepare( $sql_select, $l_id );
	$resultSet                             = $wpdb->get_results( $sql_prep );

	if ( isset( $resultSet ) && ! empty( $resultSet ) ) {
		$path               = $resultSet[0]->image_url;
		$singleListingImage = $resultSet[0]->image_url;
	} else {
		$path               = $site_image_path;
		$singleListingImage = $site_image_path;
	}
	if ( ! empty( $ListingAddress ) ) {
		$property_address = trim( $ListingAddress );
	}
	if ( ! empty( $ListingCity ) ) {
		if ( ! empty( $property_address ) ) {
			$property_address .= ', ';
		}
		$property_address .= trim( $ListingCity );
	}
	if ( ! empty( $listingProvince ) ) {
		if ( ! empty( $property_address ) ) {
			$property_address .= ' ';
		}
		$property_address .= trim( $listingProvince );
	}
	$listing_full_address_slug = str_replace( ',', '', $property_address );
	$listing_full_address_slug = str_replace( ' ', '-', $listing_full_address_slug );
	$listing_full_address_slug = str_replace( '#', '', $listing_full_address_slug );
	$listing_full_address_slug = str_replace( '&', '-', $listing_full_address_slug );
	$listing_full_address_slug = str_replace( '-----', '-', $listing_full_address_slug );
	$listing_full_address_slug = str_replace( '----', '-', $listing_full_address_slug );
	$listing_full_address_slug = str_replace( '---', '-', $listing_full_address_slug );
	$listing_full_address_slug = strtolower( str_replace( '--', '-', $listing_full_address_slug ) );

	$showcse_crea_serializable_maxlistingonpage_results_counter ++;

} else {
	//print_r($singleListing);
	# CREA Listings		
	$property_address          = null;
	$openhouse_timestamp       = null;
	$oh_arr                    = null;
	$listing_openhose_datetime = null;
	$l_id                      = $singleListing->ID;
	$ListingMls                    = $singleListing->mlsID;
	$mls_id = $ListingMls;
	$listingSizeInterior       = ! empty( $singleListing->SizeInterior ) ? $singleListing->SizeInterior : '';
	$ListingStatus             = $singleListing->TransactionType;
	$ListingBedRooms           = $singleListing->BedroomsTotal;
	$ListingBathrooms          = $singleListing->BathroomTotal;
	$listing_description       = ! empty( $singleListing->PublicRemarks ) ? $singleListing->PublicRemarks : null;
	$oh_arr                    = $singleListing->listing_openHouses;
	$current_date_timestamp    = strtotime( "now" );
	if ( ! empty( $oh_arr ) ) {
		foreach ( $oh_arr as $oh ) {
			$listing_openhose_datetime_start = ! empty( $oh->StartDateTime ) ? $oh->StartDateTime : null;
			if ( ! empty( $listing_openhose_datetime_start ) ) {
				$openhouse_timestamp_start = strtotime( str_replace( '/', '-', $oh->StartDateTime ) );
				$openhouse_timestamp_end   = strtotime( str_replace( '/', '-', $oh->EndDateTime ) );
				if ( $current_date_timestamp < $openhouse_timestamp_end ) {
					$listing_openhose_datetime = date( 'M j \&\#\6\4\; g:ia', $openhouse_timestamp_start );
					break;
				}
			}
		}
	}
	$is_exclusive_list = '';
	if ( ! empty( $l_id ) && $l_id == 'Exclusive' || $listing_Mls === 'Exclusive' ) {
		#$mls_id = 'Exclusive';
		#$is_exclusive_list = '/exclusive';
	}
	if ( $singleListing->Price == '0.00' ) {
		$ListingPrice = $singleListing->Lease;
	} else {
		$ListingPrice = $singleListing->Price;
	}
	if ( ! empty( $singleListing->StreetAddress ) && ! empty( $singleListing->City ) && ! empty( $singleListing->Province ) ) {
		if ( ! empty( $singleListing->StreetAddress ) ) {
			$property_address .= trim( $singleListing->StreetAddress );
		}
		if ( ! empty( $singleListing->City ) ) {
			if ( ! empty( $property_address ) ) {
				$property_address .= ', ';
			}
			$property_address .= trim( $singleListing->City );
		}
		if ( ! empty( $singleListing->Province ) ) {
			if ( ! empty( $property_address ) ) {
				$property_address .= ' ';
			}
			$property_address .= trim( $singleListing->Province );
		}
	} else {
		if ( ! empty( $singleListing->generated_address ) ) {
			$property_address = trim( $singleListing->generated_address );
		}
	}
	$listing_full_address_slug = str_replace( ',', '', $property_address );
	$listing_full_address_slug = str_replace( ' ', '-', $listing_full_address_slug );
	$listing_full_address_slug = str_replace( '#', '', $listing_full_address_slug );
	$listing_full_address_slug = str_replace( '&', '-', $listing_full_address_slug );
	$listing_full_address_slug = str_replace( '-----', '-', $listing_full_address_slug );
	$listing_full_address_slug = str_replace( '----', '-', $listing_full_address_slug );
	$listing_full_address_slug = str_replace( '---', '-', $listing_full_address_slug );
	$listing_full_address_slug = strtolower( str_replace( '--', '-', $listing_full_address_slug ) );
	
	if ( isset( $singleListing->listing_photos ) && ! empty( $singleListing->listing_photos ) && ! is_array( $singleListing->listing_photos ) ) {			
		$singleListingImage = $singleListing->listing_photos->URL;
	} elseif ( isset( $singleListing->listing_photos ) && ! empty( $singleListing->listing_photos ) && is_array( $singleListing->listing_photos ) ) {
			$singleListingImage = $singleListing->listing_photos[0]->URL;
	} else {
		$singleListingImage = $site_image_path;
	}

	$showcse_crea_serializable_maxlistingonpage_results_counter ++;
}
$listing_url = site_url() . '/listing-details/' . $l_id . $is_exclusive_list . '/' . $listing_full_address_slug . '/';