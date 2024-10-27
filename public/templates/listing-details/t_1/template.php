<?php
/**
 * Template Name: Default Listing Details Showcase
 *
 * A template used to display the full property details
 *
 * @package Aretk Crea.
 * @since    1.0.0
 * @version    1.0.0
 */

if ( ! empty( esc_attr( get_option( 'aretk_googleCaptchaKey_public' ) ) ) && ! empty( esc_attr( get_option( 'aretk_googleCaptchaKey_private' ) ) ) ) {
	wp_register_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js?onload=aretkcaptcha_onLoad&render=explicit', null, null, false );
	wp_enqueue_script( 'recaptcha' );

	function aretk_add_asyncdeffer_attribute( $tag, $handle ) {
		if ( 'recaptcha' !== $handle ) {
			return $tag;
		}

		return str_replace( ' src', ' async defer src', $tag );
	}

	add_filter( 'script_loader_tag', 'aretk_add_asyncdeffer_attribute', 10, 2 );
}
wp_enqueue_style( 'aretk_listingdetails_css', plugin_dir_url( __FILE__ ) . 'styles.css', array(), $this->version, 'all' );
wp_register_script( 'aretk-listingdetails-js', ARETK_CREA_PLUGIN_URL . 'public/templates/listing-details/t_1/scripts.js', array( 'jquery' ), $this->version, true );
wp_enqueue_script( 'aretk-listingdetails-js' );

wp_register_script( 'google-map-script', 'https://maps.googleapis.com/maps/api/js?key='.$property_detail_options['google-map-api-key']);
wp_enqueue_script( 'google-map-script' );

wp_enqueue_style( 'jquery.royalslider', ARETK_CREA_PLUGIN_URL . 'public/css/royalslider.css', array(), $this->version, 'all' );
wp_enqueue_style( 'jquery.rs-default', ARETK_CREA_PLUGIN_URL . 'public/css/rs-default.css', array(), $this->version, 'all' );
wp_enqueue_script( 'jquery.royalslider', ARETK_CREA_PLUGIN_URL . 'public/js/jquery.royalslider.min.js', array( 'jquery' ), $this->version );
if ( $property_detail_options['include_walk_score'] === "Yes" && ( ! empty( $property_detail_options['walk-score-api-key'] ) ) && ( ! empty( $property_details_arr['geocoded_latitude'] ) && ! empty( $property_details_arr['geocoded_longitude'] ) && ( $property_details_arr['geocoded_latitude'] != '57.678079218156' && $property_details_arr['geocoded_longitude'] != '-101.8051686875' ) ) ) {
	wp_register_script( 'aretk-walkscore-js', ARETK_CREA_PLUGIN_URL . 'public/js/walkscore.js', array(
		'jquery',
		'aretk-listingdetails-js'
	), $this->version, true );
	wp_enqueue_script( 'aretk-walkscore-js' );
}
# Page Specific Colour formatting:
$crea_listing_include_price_color    = get_option( 'crea_listing_include_price_color' );
$crea_listing_include_send_btn_color = get_option( 'crea_listing_include_send_btn_color' );

if ( ! empty( $crea_listing_include_price_color ) ) {
	$send_btn_color_txt = $crea_listing_include_price_color;
} else {
	$send_btn_color_txt = 'fff';
}
if ( ! empty( $crea_listing_include_send_btn_color ) ) {
	$send_btn_color_bg = $crea_listing_include_send_btn_color;
} else {
	$send_btn_color_bg = '0001C8';
}
if ( $property_details_arr['property_exists'] === true ) {
	?>
    <div class="aretk-wrap">
    <div class="listing-detail-main">
        <div class="list-subheading">
            <div class="list-bottom-head">
                <div class="list-bottom-left">
                    <div id="propid_wrap"></div>
                    <div id="propstatus_wrap"><?php
						if ( ! empty( $property_details_arr['TransactionType'] ) ) {
							echo '<span id="propstatus">' . $property_details_arr['TransactionType'] . '</span>';
						}
						if ( ! empty( $property_details_arr['Price'] ) ) { ?>
                            : <span id="proprice">
                            $<?php echo number_format( $property_details_arr['Price'] ); ?></span><?php
						} ?>
                    </div>
                </div>
                <div class="list-bottom-right"><?php
					$listing_openhouse_datetime = null;
					$current_date_timestamp     = strtotime( "now" );
					if ( ! empty( $property_details_arr['listing_openHouses'] ) ) {
						foreach ( $property_details_arr['listing_openHouses'] as $oh ) {
							if ( ! empty( $oh["StartDateTime"] ) ) {
								$oh["StartDateTime"] = str_replace('/','-', $oh["StartDateTime"]);
								$oh["EndDateTime"] = str_replace('/','-', $oh["EndDateTime"]);
								if ( $property_details_arr['aretk_subscription'] !== 'valid' ) {
									$openhouse_timestamp_start = strtotime( $oh["StartDateTime"] );
									$openhouse_timestamp_end   = strtotime( $oh["EndDateTime"] );
								} else {
									$openhouse_timestamp_start = strtotime( str_replace( '/', '-', $oh["StartDateTime"] ) );
									$openhouse_timestamp_end   = strtotime( str_replace( '/', '-', $oh["EndDateTime"] ) );
								}
								if ( $current_date_timestamp < $openhouse_timestamp_end ) {
									$listing_openhouse_datetime = date( 'M j \&\#\6\4\; g:ia', $openhouse_timestamp_start );
									break;
								}
							}
						}
						/*if ( ! empty( $listing_openhouse_datetime ) ) {
							$openHouseString_top = '<div class="openhouse_alert">';
							$openHouseString_top .= '<span id="ohlabel">Open House</span><span class="aretkcomma">, </span>';
							$openHouseString_top .= '<span id="ohdate">' . $listing_openhouse_datetime . '</span>';
							$openHouseString_top .= '</div>';
							echo $openHouseString_top;
						}*/
					} ?>
                    <ul class="social_icns">
						<?php if ( $property_detail_options['include_print_btn'] === "Yes" ) { ?>
                            <li>
                                <a href="javascript:void(0);" class="printfriendly"
                                   onclick="window.print();return false;" title="Print"></a>
                            </li>
						<?php } ?>
                        <li>
                            <a class="fb" target="_blank" title="Share on Facebook"
                               href="https://www.facebook.com/sharer.php?u=<?php echo $property_details_arr['url_canonical']; ?>/&amp;t=<?php echo $property_details_arr['slug']; ?>"></a>
                        </li>
                        <li>
                            <a class="twit" target="_blank" title="Share on Twitter"
                               href="https://twitter.com/home?status=<?php echo $property_details_arr['slug'] ?>+<?php echo $property_details_arr['url_canonical']; ?>"></a>
                        </li>
                        <li>
                            <a class="printerest" target="_blank" title="Share on Pinterest"
                               href="https://pinterest.com/pin/create/button/?url=<?php echo $property_details_arr['url_canonical']; ?>/&media=<?php echo $property_details_arr['image_primary']; ?>&description=<?php echo $property_details_arr['slug']; ?>"></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="listing-detail-left <?php
		if ( $property_detail_options['include_contact_form'] === 'Yes' ) {
			echo ' contactform_yes';
		} else {
			echo ' contactform_no';
		}
		if ( 'Yes' === $property_detail_options['include_agents_info'] && count( $property_details_arr['listing_agents'] ) !== 0 ) {
			echo ' agentinfo_yes';
		} else {
			echo ' agentinfo_no';
		}
		?>">

            <div class="propety_image"><?php
				$property_images = $property_details_arr['listing_photos'];
				if ( isset( $property_images ) && ! empty( $property_images ) && is_array( $property_images ) ) 
				{ ?>
                    <div id="gallery-1" class="royalSlider rsDefault"><?php
						$image_counter = 1;
						foreach ( $property_images as $property_image ) 
						{
							if ( ! empty( $property_image['URL'] ) ) 
							{ ?>
                            <a class="rsImg bugaga" data-rsBigImg="<?php echo $property_image['URL']; ?>" href="<?php echo $property_image['URL']; ?>"><img width="96" height="72" class="rsTmb" alt="<?php echo str_replace( '-', ' ', $property_details_arr['address_full'] ) ?>" src="<?php echo $property_image['URL']; ?>"/></a><?php
								$image_counter ++;
							}
						} ?>
                    </div>
                    <input type="hidden" id="lisiting_image_count" value=<?php echo $image_counter; ?> /><?php
				} else {
					echo '<img src="' . ARETK_CREA_PLUGIN_URL . 'public/images/preview_img.jpg" alt="' . str_replace( '-', ' ', $property_details_arr['address_full'] ) . '" />';
				} ?>
            </div><?php

			if ( ! empty( $property_details_arr['listing_openHouses'] ) ) {
				$listing_openhouse_datetime = null;
				$openHouses_Str             = '';
				$current_date_timestamp     = strtotime( "now" );

				foreach ( $property_details_arr['listing_openHouses'] as $oh ) {
					if ( ! empty( $oh["StartDateTime"] ) ) {
						$oh["StartDateTime"] = str_replace('/','-', $oh["StartDateTime"]);
						$oh["EndDateTime"] = str_replace('/','-', $oh["EndDateTime"]);
						$openhouse_timestamp_start_string = strtotime( $oh["StartDateTime"] );
						if ( $property_details_arr['aretk_subscription'] !== 'valid' ) {
							$openhouse_timestamp_start = strtotime( $oh["StartDateTime"] );
							$openhouse_timestamp_end   = strtotime( $oh["EndDateTime"] );
						} else {
							$openhouse_timestamp_start = $oh["StartDateTime"];
							$openhouse_timestamp_end   = $oh["EndDateTime"];
						}
						if ( $current_date_timestamp < $openhouse_timestamp_start_string ) {

							//$startYMD = date( "F jS, Y, g:i A", strtotime( $openhouse_timestamp_start ) );

							$startYMD_day = date( "l, F j", strtotime( $openhouse_timestamp_start ) );
							$startYMD_time = date( "g:i A", strtotime( $openhouse_timestamp_start ) );

							//$endYMD   = date( "F jS, Y, g:i A", strtotime( $openhouse_timestamp_end ) );
							$endYMD_day = date( "l, F j", strtotime( $openhouse_timestamp_end ) );
							$endYMD_time = date( "g:i A", strtotime( $openhouse_timestamp_end ) );


							if ( $startYMD_day === $endYMD_day ) {
								$openHouseString = $startYMD_day;
								$openHouseString .= ' from ';
								$openHouseString .= $startYMD_time. ' to ' .$endYMD_time;
							} else {
								$openHouseString = "From {$startYMD_day} $startYMD_time to {$endYMD_day} $endYMD_time";
								/*$openHouseString .= ' - ';
								$openHouseString .= date('l, F j g:i a', strtotime(str_replace('/', '-', $oh["EndDateTime"])));*/
							}
							$openHouses_Str .= '<li>' . $openHouseString . '</li>';
						}
					}
				}

				if ( strlen( $openHouses_Str ) > 0 ) {
					?>
                    <div class="custom-accordian">
                    <div class="aco-title">
                        <span>Open House<?php if ( count( $property_details['listing_openHouses'] ) > 1 ) {
		                        echo 's';
	                        } ?><em></em></span>
                    </div>
                    <div class="aco-con">
                        <ul><?php echo $openHouses_Str; ?></ul>
                    </div>
                    </div><?php
				}
			}
			if ( isset( $property_details_arr['PublicRemarks'] ) ) {
				echo "<p class=\"property_description\">" . $property_details_arr['PublicRemarks'] . "</p>";
			}
			if ( isset( $property_details_arr['VideoLink'] ) && $property_details_arr['VideoLink'] != '' ) {
				$vt_id   = null;
				$vt_type = null;
				if ( strpos( $property_details_arr['VideoLink'], 'youtube.com/watch?v=' ) !== false ) {
					$vt_arr = explode( '?v=', $property_details_arr['VideoLink'] );
					$vt_arr = explode( '&', $vt_arr[1] );
					$vt_id  = $vt_arr[0];
					$vt_src = '//www.youtube.com/embed/' . $vt_id;
				} else if ( strpos( $property_details_arr['VideoLink'], 'youtube.com/embed/' ) !== false ) {
					$vt_arr = explode( 'embed/', $property_details_arr['VideoLink'] );
					$vt_id  = $vt_arr[1];
					$vt_src = '//www.youtube.com/embed/' . $vt_id;
				} else if ( strpos( $property_details_arr['VideoLink'], 'youtu.be' ) !== false ) {
					$vt_arr = explode( 'youtu.be/', $property_details_arr['VideoLink'] );
					$vt_id  = $vt_arr[1];
					$vt_src = '//www.youtube.com/embed/' . $vt_id;
				} else if ( strpos( $property_details_arr['VideoLink'], 'player.vimeo.com/video/' ) !== false ) {
					$vt_arr = explode( 'video/', $property_details_arr['VideoLink'] );
					$vt_id  = $vt_arr[1];
					$vt_src = 'https://player.vimeo.com/video/' . $vt_id;
				} else if ( strpos( $property_details_arr['VideoLink'], 'vimeo.com/' ) !== false ) {
					$vt_arr = explode( 'vimeo.com/', $property_details_arr['VideoLink'] );
					$vt_id  = $vt_arr[1];
					$vt_src = 'https://player.vimeo.com/video/' . $vt_id;
				} else if ( strpos( $property_details_arr['VideoLink'], 'https://drive.google.com/file/' ) !== false ) {
					$vt_arr = explode( 'file/', $property_details_arr['VideoLink'] );
					$vt_id  = $vt_arr[1];
					$vt_src = str_replace( "view", "preview", $property_details_arr['VideoLink'] );
				}
				if ( ! empty( $vt_id ) ) {
					echo '<div id="virtualtour"><div class="aretk-embedvideo">';
					echo '<iframe frameborder="0" allowfullscreen="1" webkitallowfullscreen mozallowfullscreen src="' . $vt_src . '"></iframe>';
					echo '</div></div>';
				} else {
					echo '<div id="virtualtour_link"><a class="ldvtbtn" href="' . $property_details_arr['VideoLink'] . '" target="_blank">View Virtual Tour</a></div>';
				}
			}
			
			$geocoded_pov_heading = ( !empty($property_details_arr['geocoded_pov_heading']) ) ? htmlspecialchars( $property_details_arr['geocoded_pov_heading'] ) : '';
			$geocoded_pov_pitch = ( !empty($property_details_arr['geocoded_pov_pitch']) ) ? htmlspecialchars( $property_details_arr['geocoded_pov_pitch'] ) : '';
			$geocoded_pov_zoom = ( !empty($property_details_arr['geocoded_pov_zoom']) ) ? htmlspecialchars( $property_details_arr['geocoded_pov_zoom'] ) : '';
			
			if ( $property_detail_options['include_map'] === "Yes" ) {
				if ( ( !empty($property_detail_options['google-map-api-key']) && $property_detail_options['google-map-api-key'] != "" ) && ( !empty((float)$property_details_arr['geocoded_latitude']) && !empty((float)$property_details_arr['geocoded_longitude']) ) && ( $property_details_arr['geocoded_latitude'] != '57.678079218156' && $property_details_arr['geocoded_longitude'] != '-101.8051686875' ) ) { ?>
                    <div id="mapPropertyDetail"></div>
                    <div id="mapPropertyDetailpano" style="display:none"></div>
                    <input type="hidden" id="geocoded_latitude"
                           value="<?php echo htmlspecialchars( $property_details_arr['geocoded_latitude'] ); ?>"/>
                    <input type="hidden" id="geocoded_longitude"
                           value="<?php echo htmlspecialchars( $property_details_arr['geocoded_longitude'] ); ?>"/>
                    <input type="hidden" id="geocoded_pov_heading" value="<?php echo $geocoded_pov_heading; ?>"/>
                    <input type="hidden" id="geocoded_pov_pitch" value="<?php echo $geocoded_pov_pitch; ?>"/>
                    <input type="hidden" id="geocoded_pov_zoom" value="<?php echo $geocoded_pov_zoom; ?>" /><?php
				}
			}
			if ( ! empty( $property_details_arr['StreetAddress'] ) || ! empty( $property_details_arr['City'] ) || ! empty( $property_details_arr['Province'] ) ) { ?>
                <div class="custom-accordian">
                <div class="aco-title">
                    <span>Address <em></em></span>
                </div>
                <div class="aco-con">
                    <ul><?php
						if ( ! empty( $property_details_arr['StreetAddress'] ) ) { ?>
                            <li>
                            <strong>Street Address :</strong>
                            <span><?php echo $property_details_arr['StreetAddress']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['City'] ) ) { ?>
                            <li>
                            <strong>City :</strong>
                            <span><?php echo $property_details_arr['City']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['Province'] ) ) { ?>
                            <li>
                            <strong>Province :</strong>
                            <span><?php echo $property_details_arr['Province']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['PostalCode'] ) ) { ?>
                            <li>
                            <strong>Postal Code :</strong>
                            <span><?php echo $property_details_arr['PostalCode']; ?></span>
                            </li><?php
						} ?>
                    </ul>
                </div>
                </div><?php
			}
			if ( ! empty( $property_details_arr['mlsID'] ) || ! empty( $crea_listing_price ) || ! empty( $listingBedroomsTotal ) || ! empty( $listingBathroomTotal ) || ! empty( $property_details_arr['SizeInterior'] ) ) { ?>
                <div class="custom-accordian">
                <div class="aco-title">
                    <span>Details<em></em></span>
                </div>
                <div class="aco-con">
                    <ul>
						<?php if ( ! empty( $property_details_arr['mlsID'] ) ) { ?>
                            <li>
                            <strong>MLS&reg;# :</strong>
                            <span><?php echo $property_details_arr['mlsID']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['TransactionType'] ) ) { ?>
                            <li>
                            <strong>Status :</strong>
                            <span><?php echo $property_details_arr['TransactionType']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['OwnershipType'] ) ) { ?>
                            <li>
                            <strong>Ownership Type :</strong>
                            <span><?php echo $property_details_arr['OwnershipType']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['Price'] ) ) { ?>
                            <li>
                            <strong>Price :</strong>
                            <span><?php echo "$" . number_format( $property_details_arr['Price'] ); ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['PropertyType'] ) ) { ?>
                            <li>
                            <strong>Property Type :</strong>
                            <span><?php echo $property_details_arr['PropertyType']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['Structure'] ) ) { ?>
                            <li>
                            <strong>Structure Type :</strong>
                            <span><?php echo $property_details_arr['Structure']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['BedroomsTotal'] ) && $property_details_arr['BedroomsTotal'] > 0 ) { ?>
                            <li>
                            <strong>Bedrooms :</strong>
                            <span><?php echo $property_details_arr['BedroomsTotal']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['BathroomTotal'] ) && $property_details_arr['BathroomTotal'] > 0 ) { ?>
                            <li>
                            <strong>Bathrooms Full :</strong>
                            <span><?php echo $property_details_arr['BathroomTotal']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['HalfBathTotal'] ) && $property_details_arr['HalfBathTotal'] > 0 ) { ?>
                            <li>
                            <strong>Bathrooms Partial :</strong>
                            <span><?php echo $property_details_arr['HalfBathTotal']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['BasementType'] ) ) { ?>
                            <li>
                            <strong>Finished Basement :</strong>
                            <span><?php echo $property_details_arr['BasementType']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['SizeInterior'] ) && $property_details_arr['SizeInterior'] > 0 ) { ?>
                            <li>
                            <strong>Area :</strong>
                            <span><?php echo $property_details_arr['SizeInterior']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr['SizeIrregular'] ) && $property_details_arr['SizeIrregular'] > 0 ) { ?>
                            <li>
                            <strong>Property Lot Size :</strong>
                            <span><?php echo $property_details_arr['SizeIrregular']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr["listing_parkingSpaces"][0]['Name'] ) ) { ?>
                            <li>
                            <strong>Garage :</strong>
                            <span><?php echo $property_details_arr["listing_parkingSpaces"][0]['Name']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr["listing_parkingSpaces"][0]['Spaces'] ) ) { ?>
                            <li>
                            <strong>Parking Spaces :</strong>
                            <span><?php echo $property_details_arr["listing_parkingSpaces"][0]['Spaces']; ?></span>
                            </li><?php
						}
						if ( ! empty( $property_details_arr["Utilities"] ) ) {
							$property_details_arr["Utilities"] = str_replace(',', ', ', $property_details_arr["Utilities"]);
						    ?>
                            <li>
                            <strong>Utilities :</strong>
                            <span><?php echo $property_details_arr["Utilities"]; ?></span>
                            </li><?php
						} ?>
                    </ul>
                </div>
                </div><?php
			}
			if ( ! empty( $property_details_arr['Features'] ) && $property_details_arr['Features'] != '' ) {
				$listingFeatures = explode( ',', $property_details_arr['Features'] ); ?>
                <div class="custom-accordian">
                <div class="aco-title">
                    <span>Features <em></em></span>
                </div>
                <div class="aco-con">
                    <ul><?php
						if ( ! empty( $listingFeatures ) && isset( $listingFeatures ) ) {
							foreach ( $listingFeatures as $listingFeature ) { ?>
                                <li class="list_feature">
                                <span><?php echo $listingFeature; ?></span>
                                </li><?php
							}
						} ?>
                    </ul>
                </div>
                </div><?php
			}


			if ( isset( $property_details_arr['BrochureLink'] ) && $property_details_arr['BrochureLink'] != '' ) {

				$externaldocs = explode( ',', $property_details_arr['BrochureLink'] ); ?>
                <div class="custom-accordian">
                <div class="aco-title">
                    <span>Additional Resources <em></em></span>
                </div>
                <div class="aco-con">
                    <ul><?php
						foreach ( $externaldocs as $doc ) {
							echo '<li><a href="' . $doc . '" target="_blank">' . basename( $doc ) . '</a></li>';
						} ?>
                    </ul>
                </div>
                </div><?php

			}
			if ( $property_detail_options['include_walk_score'] === "Yes" && ( ! empty( $property_detail_options['walk-score-api-key'] ) && $property_detail_options['walk-score-api-key'] != "" ) && ( ! empty( (float)$property_details_arr['geocoded_latitude'] ) && ! empty( (float)$property_details_arr['geocoded_longitude'] ) && ( $property_details_arr['geocoded_latitude'] != '57.678079218156' && $property_details_arr['geocoded_longitude'] != '-101.8051686875' ) ) ) { ?>
                <div class="custom-accordian">
                <div class="aco-title">
                    <span>WalkScore <em></em></span>
                </div>
                <div class="aco-con">
                    <div id='ws-walkscore-tile'></div>
                    <input type="hidden" id="ws_wsid"
                           value="<?php echo $property_detail_options['walk-score-api-key']; ?>"/>
                    <input type="hidden" id="ws_lat" value="<?php echo $property_details_arr['geocoded_latitude']; ?>"/>
                    <input type="hidden" id="ws_lon"
                           value="<?php echo $property_details_arr['geocoded_longitude']; ?>"/>
                    <input type="hidden" id="ws_address" value="<?php echo $property_details_arr['address_full']; ?>"/>
                    <span class="disclaimer walkscore_disclaimer">Walk Score®, where available, is a service provided by WalkScore.com. The Walk Score® ratings are not guaranteed to be accurate.</span>
                </div>
                </div><?php
			}
			if ( isset( $property_details_arr['listing_rooms'] ) && ! empty( $property_details_arr['listing_rooms'] ) ) { ?>
                <div class="custom-accordian">
                <div class="aco-title Listing_room_title">
                    <span>Rooms <em></em></span>
                </div>
                <div class="aco-con Listing_room_title_con">
                    <p>
                        <span class="Listing_room_title_con_room"><strong>Room</strong></span>
                        <span class="Listing_room_title_con_dimension"><strong>Dimension</strong></span>
                    </p><?php
					foreach ( $property_details_arr['listing_rooms'] as $listingPropertyListingRoom_Results ) { ?>
                        <p>
                        <span class="Listing_room_title_con_results_type"><?php echo $listingPropertyListingRoom_Results['Type']; ?></span>
                        <span class="Listing_room_title_con_results_dimension"><?php echo $listingPropertyListingRoom_Results['Dimension']; ?></span>
                        </p><?php
					} ?>
                </div>
                </div><?php
			} ?>
        </div><?php // end listing-detail-left
		?>
        <div class="listing-detail-right"><?php
		    $listing_contact_us_agent_email_id = array();
		    $emailtxt = "I would like to inquire about " .$property_details_arr['address_full'];
		    if ( !empty( $property_details_arr['mlsID'] ) ) {
		    $emailtxt .= " (MLS#: ".$property_details_arr['mlsID'].")"; ?></span><?php
		    }
		    if ( $property_detail_options['include_agents_info'] == "Yes" ) {
			    foreach ( $property_details_arr['listing_agents'] as $listingPropertyListingAgent ){
				    $post_meta_table = $wpdb->prefix.'crea_agent';
				    $sql_select = "SELECT * FROM `$post_meta_table` WHERE `crea_agent_id`= %d";
				    $sql_prep = $wpdb->prepare( $sql_select, $listingPropertyListingAgent['ID'] );
				    $getAgentidResultsarray = $wpdb->get_results($sql_prep);

				    if (!empty( $getAgentidResultsarray ) ){
					    $listing_contact_us_agent_email_id[] = $getAgentidResultsarray[0]->crea_agent_id;
					    $listing_agent_email = $getAgentidResultsarray[0]->crea_agent_email; ?>
                        <div class="author-detail-block" itemscope itemtype="https://schema.org/Person"><?php
					    $agent_photo_url = $listingPropertyListingAgent['photo_url'];
					    if(isset( $agent_photo_url ) && !empty( $agent_photo_url ) && $agent_photo_url != "no photo"){ ?>
                            <img src="<?php echo $agent_photo_url;  ?>" alt="<?php echo $listingPropertyListingAgent['Name']; ?> portrait" width="100%"><?php
					    } ?>
                        <div class="author-inner-block">
                            <h4 itemprop="name"><?php echo $listingPropertyListingAgent['Name']; ?></h4>
                            <em itemprop="jobTitle"><?php echo $listingPropertyListingAgent['Position']; ?></em><?php
						    if( $property_detail_options['include_agent_email'] == 'Yes' && isset( $listing_agent_email ) && !empty( $listing_agent_email )){  ?>
                                <em itemprop="email" class="agentemail"><a href="mailto:<?php echo $listing_agent_email; ?>?Subject=Property Inquiry&amp;body=<?php echo rawurlencode($emailtxt); ?>" target="_top"><?php echo $listing_agent_email; ?></a></em><?php
						    }
						    $listingPropertyListingAgentPhones = $listingPropertyListingAgent['agentPhones'];
						    foreach ( $listingPropertyListingAgentPhones as $listingPropertyListingAgentPhone ) { ?>
                                <span itemprop="telephone" class="agentphone"><a href="tel:<?php echo $listingPropertyListingAgentPhone['PhoneNumber']; ?>"><?php echo $listingPropertyListingAgentPhone['PhoneNumber']; ?></a></span><?php
						    } ?>
                            <div class="listing_detail_office">
                                <span><?php echo $listingPropertyListingAgent['office']; ?></span>
                            </div>
                        </div>
                        </div><?php
				    }
			    }
		    }
		    if ( $property_detail_options['include_contact_form'] === "Yes" ) { ?>
                <div class="contact-block">
                <div class="contact-detail">
                    <h4>Request More Information</h4>
                    <form id="listingcontact" method="post">
                        <div  id="crea_listing_contact_table" >
                            <div class="crea_listing_contact_tr">
                                <div class="crea_listing_contact_td">
                                    <input type="hidden" id="lisiting_api_contact_product_detail_link" value=<?php echo $property_details_arr['url_canonical']; ?>>
                                    <input type="hidden" name="crea_listing_contact_first_agent_ids" id="crea_listing_contact_first_agent_id" value="<?php echo isset( $property_details_arr['listing_agents'][0]['ID'] ) ? $property_details_arr['listing_agents'][0]['ID'] :''; ?>">
                                    <input type="hidden" name="proprty_deatils_page_url" id="crea_listing_contact_page_url" value="<?php echo isset( $property_details_arr['ID'] ) ? $property_details_arr['ID'] :''; ?>" >
                                </div>
                            </div>
                            <div class="crea_listing_contact_tr">
                                <div class="crea_listing_contact_td">
                                    <input placeholder="Your Name *" type="text" name="contact_agent_name" id="crea_contact_details_agent_name">
                                    <div style="display:none;" class="crea_valid_name_or_not_null set_contact_form_valid">Name is Required</div>
                                </div>
                            </div>
                            <div class="crea_listing_contact_tr">
                                <div class="crea_listing_contact_td">
                                    <input placeholder="Your Email *" type="text" name="contact_agent_email" id="crea_contact_details_agent_email">
                                    <div style="display:none;" class="crea_valid_email_or_not_null set_contact_form_valid">Valid Email is Required</div>
                                </div>
                            </div>
                            <div class="crea_listing_contact_tr">
                                <div class="crea_listing_contact_td"><input placeholder="Your Phone" type="text" name="contact_agent_phone" maxlength="10" id="crea_contact_details_agent_phone"></div>
                            </div>
                            <div class="crea_listing_contact_tr">
                                <div class="crea_listing_contact_td"><textarea name="contact_agent_message" id="crea_contact_details_agent_message" ><?php echo $emailtxt; ?></textarea></div>
                                <div style="display:none;" class="crea_valid_message_or_not_null set_contact_form_valid">Message is Required</div>
                                <textarea name="crea_contact_details_agent_message2" id="crea_contact_details_agent_message2" ></textarea>
                            </div>
                            <div class="loader">Processing</div>
                            <div class="sucessfullyaddedrecords" style="display:none;">Form Submitted Successfully</div>
                            <div class="noaddedrecords" style="display:none;"></div>
                            <div class="crea_listing_contact_tr<?Php
						    if ( empty(esc_attr(get_option('aretk_googleCaptchaKey_public'))) || empty(esc_attr(get_option('aretk_googleCaptchaKey_private')))){ echo ' last'; } ?>">
                                <div class="crea_listing_contact_td"><a style="color: #<?php echo $send_btn_color_txt; ?>; background-color:#<?php echo $send_btn_color_bg; ?>;" class="send-btn" name="crea_send_contacat_form" id="crea_send_listing_contact_form" href="javascript:void(0);">SEND MESSAGE</a></div>
                            </div>
						    <?php
						    if ( !empty(esc_attr(get_option('aretk_googleCaptchaKey_public'))) && !empty(esc_attr(get_option('aretk_googleCaptchaKey_private')))){ ?>
                                <div class="crea_listing_contact_tr last">
                                <div id="aretk_listcontact_captcha" class="g-recaptcha" data-sitekey="<?php echo esc_attr(get_option('aretk_googleCaptchaKey_public')); ?>" data-badge="inline" data-size="invisible"></div>
                                </div><?php
						    } ?>
                        </div>
                        <input type="hidden" value="<?php echo implode(",",$listing_contact_us_agent_email_id); ?>" id="contact_us_agents_mail">
                    </form>
                </div>
                </div><?php
		    } ?>
        </div><?php // end listing-detail-right

		$listingPropertyListingAgent_listed_by = array();
		foreach ( $property_details_arr['listing_agents'] as $listingPropertyListingAgent ) {
			$listingPropertyListingAgent_listed_by[] = $listingPropertyListingAgent['office'];
		}
		$listinged_by_companyname = array_unique( $listingPropertyListingAgent_listed_by );

		if('Exclusive' !== $property_details_arr['mlsID']
		   && ''          !== $property_details_arr['mlsID']
        ) {
			if ( $property_details_arr['aretk_subscription'] === 'valid' && ( empty( $property_details_arr['is_exclusive'] ) || $property_details_arr['is_exclusive'] !== 'exclusive' ) ) { ?>
            <div class="listing-detail-left disclaimers<?php
			if ( $property_detail_options['include_contact_form'] === 'Yes' ) {
				echo ' contactform_yes';
			} else {
				echo ' contactform_no';
			}
			if ( $property_detail_options['include_agents_info'] === 'Yes' && count( $property_details_arr['listing_agents'] ) !== 0 ) {
				echo ' agentinfo_yes';
			} else {
				echo ' agentinfo_no';
			}
			?>">
                <div class="term_condition"><?php
					if ( isset( $listinged_by_companyname ) && ! empty( $listinged_by_companyname ) ) { ?>
                        <div class="listedby">
                        <span class="listedby">Listing Office
							<?php
							$i = 0;
							foreach ( $listinged_by_companyname as $company_results ) {
								if ( $i > 0 ) {
									echo ' &amp; ';
								} ?>
                                <em><?php echo $company_results; ?></em><?php
								$i ++;
							} ?>
							</span>
                        </div><?php
					} ?>
                    <div class="realtor-mls-logos">
						<img src="<?php echo ARETK_CREA_PLUGIN_URL ?>public/images/realtor-and-mls-logos.jpg" alt="REALTOR and MLS logos" width="201" height="75"/>
						<img id="poweredbyrealtorimg" src="<?php echo ARETK_CREA_PLUGIN_URL ?>public/images/powered-by-realtor.svg" alt="Powered by REALTOR" width="120" />
					</div>
					
					<span class="disclaimer aretk_fd1">This <a href="<?php echo $property_details_arr['MoreInformationLink']; ?>" rel="nofollow" target="_blank">REALTOR.ca</a> listing content is owned and licensed by REALTOR® members of the <a href="https://www.crea.ca/" rel="nofollow" target="_blank">Canadian Real Estate Association.</a></span>
					
                    <span class="disclaimer aretk_fd1">REALTOR&reg;, MLS&reg; and the associated logos are trademarks of The Canadian Real Estate Association.</span>
					
                    <span class="disclaimer aretk_fd2"><?php
						if ( is_array( $property_detail_options['disclaimer'] ) ) {
							echo $property_detail_options['disclaimer']['disclaimer'];
						} else {
							echo $property_detail_options['disclaimer'];
						} ?></span>
                    <span class="disclaimer aretk_fd2">
					All information displayed on this website is believed to be accurate but is not guaranteed and should be independently verified. No warranties or representations are made of any kind.</span>
                </div>
                </div><?php
			} else { ?>
                <div class="listing-detail-left disclaimers">
                <div class="term_condition">
                    <span class="disclaimer aretk_fd1">REALTOR&reg;, MLS&reg; and the associated logos are trademarks of The Canadian Real Estate Association.</span>
                    <span class="disclaimer aretk_fd2"><?php
						if ( is_array( $property_detail_options['disclaimer'] ) ) {
							echo $property_detail_options['disclaimer']['disclaimer'];
						} else {
							echo $property_detail_options['disclaimer'];
						} ?></span>
                    <span class="disclaimer aretk_fd2">
					All information displayed on this website is believed to be accurate but is not guaranteed and should be independently verified. No warranties or representations are made of any kind.</span>
                </div>
                </div><?php
			}
        }

		if ( empty( $_COOKIE["aretk_crea_terms_of_use"] )
             && $property_details_arr['aretk_subscription'] === 'valid'
             && 'Exclusive' !== $property_details_arr['mlsID']
		     && ''          !== $property_details_arr['mlsID']
        ) {
			?>
            <div id="aretk_crea_disclaimer_container">
                <div id="aretk_crea_disclaimer_content">
                    <h2>Terms &amp; Conditions Agreement</h2>
                    <p>The listing content on this website is protected by copyright and other laws, and is intended
                        solely for the private, non-commercial use by individuals. Any other reproduction, distribution
                        or use of the content, in whole or in part, is specifically forbidden. The prohibited uses
                        include commercial use, "screen scraping", "database scraping", and any other activity intended
                        to collect, store, reorganize or manipulate data on the pages produced by or displayed on this
                        website.</p>
                    <p>REALTOR&reg;, REALTORS&reg;, and the REALTOR&reg; logo are certification marks that are owned by
                        REALTOR&reg; Canada Inc. and licensed exclusively to The Canadian Real Estate Association
                        (CREA). These certification marks identify real estate professionals who are members of CREA and
                        who must abide by CREA's By-Laws, Rules, and the REALTOR&reg; Code. The MLS&reg; trademark and
                        the MLS&reg; logo are owned by CREA and identify the quality of services provided by real estate
                        professionals who are members of CREA.</p>
                    <p>The information contained on this site is based in whole or in part on information that is
                        provided by members of The Canadian Real Estate Association, who are responsible for its
                        accuracy. CREA reproduces and distributes this information as a service for its members and
                        assumes no responsibility for its accuracy.</p>
                    <div id="aretk_crea_disclaimer_btns">
                        <input type="button" value="Accept" id="aretk_crea_disclaimer_accept"/>
                        <input type="button" value="Decline" id="aretk_crea_disclaimer_decline"/>
                    </div>
                </div>
            </div>
            <div id="aretk_crea_disclaimer_background"></div><?php
		} ?>
    </div>
    </div><?php
} else {
	?>
    <p>
		<?php _e( 'Sorry the property you are looking for no longer exists.', 'aretk-crea' ); ?>
    </p>
	<?php
}
