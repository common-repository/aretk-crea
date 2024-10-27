<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.aretk.com
 * @since      1.0.0
 *
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/public
 * @author     ARETK <inquiry@aretk.com>
 */
class Aretk_Crea_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	private $property_details_arr;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param    string $plugin_name The name of the plugin.
	 * @param    string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Return property Address
	 *
	 * @param unknown_type $numpages
	 * @param unknown_type $pagerange
	 * @param unknown_type $paged
	 */
	public static function get_property_meta() {
		global $wpdb, $posts;
		$property_details_arr = null;
		if ( strpos( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), 'listing-detail' ) !== false || strpos( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), 'listing-details' ) !== false ) {
			if ( strpos( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), 'listing-details' ) !== false ) {
				$page_slug = 'listing-details';
			} else {
				$page_slug = 'listing-detail';
			}

			$real_url = rtrim( parse_url(rtrim( $_SERVER['REQUEST_URI'], "/" ), PHP_URL_PATH), "/" );
			$listing_explode_array_results = explode( '/', $real_url );
			$listing_explode_array         = array_reverse( $listing_explode_array_results );
			$listing_id                    = $listing_explode_array[1];
			$is_exclusive                  = null;
			
			if ( $listing_id == 'exclusive' ) {
				$is_exclusive = 'exclusive';
				$listing_id   = $listing_explode_array[2];
			}

			if ( isset( $listing_id ) && ! empty( $listing_id ) && is_numeric( $listing_id ) ) {
				$propertyListId             = $listing_id;
				$getSubscriptionListingFeed = get_option( 'crea_subscription_status', true );
				if ( isset( $getSubscriptionListingFeed ) && $getSubscriptionListingFeed === 'valid' && $is_exclusive !== 'exclusive' ) {
					$crea_user_name_table_name = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
					$sql_select                = "SELECT `username` FROM `$crea_user_name_table_name`";
					$sql_prep                  = $wpdb->prepare( $sql_select, null );
					$getAllUsername            = $wpdb->get_results( $sql_prep );
					$userNameList              = '';
					if ( isset( $getAllUsername ) && ! empty( $getAllUsername ) ) {
						foreach ( $getAllUsername as $singleUsername ) {
							$userName = $singleUsername->username;
							if ( ! empty( $userName ) ) {
								$userNameList .= $userName . ',';
							}
							unset( $singleUsername );
						}
						unset( $getAllUsername );
						$userNameList = rtrim( $userNameList, ',' );
						$result_type  = 'full';
						$listings     = Aretk_Crea_Admin::aretkcrea_get_property_detail_page_result( $userNameList, $result_type, $propertyListId );
						if ( isset( $listings ) && ! empty( $listings ) ) {
							foreach ( $listings as $listing_key => $listing ) {
								if ( ! isset( $listing['TotalRecords'] ) && empty( $listing['TotalRecords'] ) ) {
									$allListingArr[ $listing['ID'] ] = $listing;
								}
							}
						}
					}
					
					$property_details_arr = $allListingArr[ $propertyListId ];
					if ( isset( $property_details_arr ) && ! empty( $property_details_arr ) ) {
						$property_exists           = true;
						$listing_full_address_path = '';
						$listing_full_address      = '';
						if ( ! empty( $property_details_arr['StreetAddress'] ) ) {
							$listing_full_address .= $property_details_arr['StreetAddress'];
						}
						if ( ! empty( $property_details_arr['City'] ) ) {
							if ( ! empty( $listing_full_address ) ) {
								$listing_full_address .= ', ';
							}
							$listing_full_address .= $property_details_arr['City'];
						}
						if ( ! empty( $property_details_arr['Province'] ) ) {
							if ( ! empty( $listing_full_address ) ) {
								$listing_full_address .= ' ';
							}
							$listing_full_address .= $property_details_arr['Province'];
						}
						if ( empty( $listing_full_address ) ) {
							$listing_full_address      = 'Address not available';
							$listing_full_address_path = $property_details_arr['generated_address'];
						} else {
							$listing_full_address_path = $listing_full_address;
						}
						$property_description = $property_details_arr['PublicRemarks'];
						$listing_full_address = ! empty( $listing_full_address ) ? $listing_full_address : 'Address not available';
						# Property Images
						$site_image_path = ARETK_CREA_PLUGIN_URL . 'public/images/preview_img.jpg';
						if ( $property_details_arr['listing_photos'] != null ) {
							$property_photos = $property_details_arr['listing_photos'];
							$domain_name     = $_SERVER['SERVER_NAME'];
							# Check if primary image is_valid
							if ( isset( $property_photos ) && ! empty( $property_photos ) && ! is_array( $property_photos ) ) {
								$singleListingimag_array = explode( '/', $property_photos['URL'] );
								if ( ! empty( $singleListingimag_array ) ) {
									$singleListingimage_domain = $singleListingimag_array[2];
									if ( $domain_name == $singleListingimage_domain ) {
										list( $width, $height, $type, $attr ) = getimagesize( $property_photos['URL'] );
										if ( ! empty( $width ) && $width != '0' ) {
											$property_image_path = $property_photos['URL'];
										} else {
											$property_image_path = $site_image_path;
										}
									} else if ( $domain_name != $singleListingimage_domain && $singleListingimage_domain != 'static.aretk.com' ) {
										$property_image_path = $site_image_path;
									} else {
										$property_image_path = $property_photos['URL'];
									}
								}
							} else {
								# Get primary image for social sharing
								$singleListingimag_array = explode( '/', $property_photos[0]['URL'] );
								if ( ! empty( $singleListingimag_array ) ) {
									$singleListingimage_domain = $singleListingimag_array[2];
									if ( $domain_name == $singleListingimage_domain ) {
										# Image stored locally
										list( $width, $height, $type, $attr ) = getimagesize( $property_photos[0]['URL'] );
										if ( ! empty( $width ) && $width != '0' ) {
											$property_image_path = $property_photos[0]['URL'];
										} else {
											$property_image_path = $site_image_path;
										}
									} else if ( $domain_name != $singleListingimage_domain && $singleListingimage_domain != 'static.aretk.com' ) {
										$property_image_path = $site_image_path;
									} else {
										$property_image_path = $property_photos[0]['URL'];
									}
								}
							}
						} else {
							$property_image_path = $site_image_path;
						}
						$is_exclusive_list = '';
						$property_link_url = '';
						$propertyListId    = $property_details_arr['ID'];
						if ( ! empty( $property_details_arr ) && $property_details_arr != '' ) {
							$property_link_url = site_url() . '/' . $page_slug . '/' . $propertyListId . '/' . sanitize_title( $listing_full_address_path );
						}
					} else {
						$property_exists = false;
					}

				} else {
					/*
					 * No valid ARETK Subscription
					*/
					$property_details_arr = array();
					$data                 = array();
					$post_tble            = $wpdb->postmeta;
					$sql_select           = "SELECT `meta_key`, `meta_value` FROM `$post_tble` WHERE `post_id`= %d";
					$sql_prep             = $wpdb->prepare( $sql_select, $listing_id );
					$wpdb->query( $sql_prep );

					foreach ( $wpdb->last_result as $k => $v ) {
						$data[ $v->meta_key ] = $v->meta_value;
					};
					if ( empty( $data ) ) {
						$property_exists      = false;
						$listing_full_address = 'Property Not Found';
					} else {
						$property_exists = true;
						$listing_type    = get_post_meta( $propertyListId, 'listing_type', true );
						$listingAddress  = get_post_meta( $propertyListId, 'listingAddress', true );
						$ListingCity     = get_post_meta( $propertyListId, 'listingcity', true );
						$listingProvince = get_post_meta( $propertyListId, 'listingProvince', true );
						$listingMls = get_post_meta( $propertyListId, 'listingMls', true );

						// get external document
						$agentDocumentArr      = array();
						$agentDocumentFinalArr = '';
						$agentDocumentTable    = $wpdb->prefix . 'crea_listing_document_detail';
						$sql_select            = "SELECT * FROM `$agentDocumentTable` WHERE `unique_id`= %d ORDER BY `id`";
						

						$sql_prep              = $wpdb->prepare( $sql_select, $listing_id );
						$agentDocumentResults  = $wpdb->get_results( $sql_prep );

						if ( $agentDocumentResults != '' && ! empty( $agentDocumentResults ) ) {
							foreach ( $agentDocumentResults as $agentDocumentResultsValue ) {
								$agentDocumentArr[] = $agentDocumentResultsValue->document_url;
							}
							$agentDocumentFinalArr = implode( ",", $agentDocumentArr );
						}

						# build $property_details_arr to match aretk array.
						$property_details_arr["ID"]                        = $listing_id;
						$property_details_arr["LID"]                       = null;
						$property_details_arr["LastUpdated"]               = null;
						$property_details_arr["mlsID"]                     = !empty($listingMls) ? $listingMls : 'Exclusive';
						$property_details_arr["AmmenitiesNearBy"]          = $listing_id;
						$property_details_arr["Board"]                     = $listing_id;
						$property_details_arr["CommunityFeatures"]         = $listing_id;
						$property_details_arr["EquipmentType"]             = $listing_id;
						$property_details_arr["Features"]                  = implode( ', ', json_decode( get_post_meta( $propertyListId, 'listingFeatureArr', true ) ) );
						$property_details_arr["Lease"]                     = null;
						$property_details_arr["LeasePerTime"]              = null;
						$property_details_arr["LeasePerUnit"]              = null;
						$property_details_arr["ListingContractDate"]       = null;
						$property_details_arr["LocationDescription"]       = null;
						$property_details_arr["MaintenanceFee"]            = null;
						$property_details_arr["MaintenanceFeePaymentUnit"] = null;
						$property_details_arr["MaintenanceFeeType"]        = null;
						$property_details_arr["ManagementCompany"]         = null;
						$property_details_arr["MoreInformationLink"]       = null;
						$property_details_arr["MunicipalId"]               = null;
						$property_details_arr["OwnershipType"]             = null;
						$property_details_arr["ParkingSpaceTotal"]         = get_post_meta( $propertyListId, 'listingParkingSlot', true );
						$property_details_arr["PoolType"]                  = null;
						$property_details_arr["Price"]                     = get_post_meta( $propertyListId, 'listingPrice', true );
						$property_details_arr["PropertyType"]              = get_post_meta( $propertyListId, 'listingPropertyType', true );
						$property_content_post                             = get_post( $propertyListId );
						if ( ! empty( $property_content_post ) && $property_content_post != '' ) {
							$property_description                  = $property_content_post->post_content;
							$property_details_arr["PublicRemarks"] = $property_content_post->post_content;
						} else {
							$property_details_arr["PublicRemarks"] = null;
						}
						$property_details_arr["RentalEquipmentType"]         = null;
						$property_details_arr["SignType"]                    = null;
						$property_details_arr["Structure"]                   = get_post_meta( $propertyListId, 'listingStructureType', true );
						$property_details_arr["TransactionType"]             = get_post_meta( $propertyListId, 'listingAgentStatus', true );
						$property_details_arr["AnalyticsClick"]              = null;
						$property_details_arr["AnalyticsView"]               = null;
						$property_details_arr["StreetAddress"]               = get_post_meta( $propertyListId, 'listingAddress', true );
						$property_details_arr["City"]                        = get_post_meta( $propertyListId, 'listingcity', true );
						$property_details_arr["AddressLine1"]                = null;
						$property_details_arr["StreetNumber"]                = null;
						$property_details_arr["StreetName"]                  = null;
						$property_details_arr["StreetSuffix"]                = null;
						$property_details_arr["StreetDirectionSuffix"]       = null;
						$property_details_arr["Province"]                    = get_post_meta( $propertyListId, 'listingProvince', true );
						$property_details_arr["PostalCode"]                  = null;
						$property_details_arr["Country"]                     = null;
						$property_details_arr["CommunityName"]               = null;
						$property_details_arr["generated_address"]           = null;
						$property_details_arr["geocoded_latitude"]           = get_post_meta( $propertyListId, 'crea_google_map_latitude', true );
						$property_details_arr["geocoded_longitude"]          = get_post_meta( $propertyListId, 'crea_google_map_longitude', true );
						$property_details_arr["geocoded_status"]             = null;
						$property_details_arr["geocoded_provider"]           = null;
						$property_details_arr["geocoded_date"]               = null;
						$property_details_arr["PhotoLink"]                   = null;
						$property_details_arr["VideoLink"]                   = get_post_meta( $propertyListId, 'listingTourUrl', true );
						$property_details_arr["BrochureLink"]                = $agentDocumentFinalArr;
						$property_details_arr["MapLink"]                     = null;
						$property_details_arr["SoundLink"]                   = null;
						$property_details_arr["Age"]                         = null;
						$property_details_arr["Amenities"]                   = null;
						$property_details_arr["Appliances"]                  = null;
						$property_details_arr["ArchitecturalStyle"]          = null;
						$property_details_arr["BasementType"]                = get_post_meta( $propertyListId, 'listingFinishedBasement', true );
						$property_details_arr["BathroomTotal"]               = get_post_meta( $propertyListId, 'listingBathrooms', true );
						$property_details_arr["BedroomsTotal"]               = get_post_meta( $propertyListId, 'listingBedRooms', true );
						$property_details_arr["BedroomsAboveGround"]         = null;
						$property_details_arr["BedroomsBelowGround"]         = null;
						$property_details_arr["ConstructedDate"]             = null;
						$property_details_arr["ConstructionMaterial"]        = null;
						$property_details_arr["CoolingType"]                 = null;
						$property_details_arr["ConstructionStyleAttachment"] = null;
						$property_details_arr["ExteriorFinish"]              = null;
						$property_details_arr["FireplaceFuel"]               = null;
						$property_details_arr["FireplacePresent"]            = null;
						$property_details_arr["FireplaceType"]               = null;
						$property_details_arr["FlooringType"]                = null;
						$property_details_arr["FoundationType"]              = null;
						$property_details_arr["HalfBathTotal"]               = get_post_meta( $propertyListId, 'listingBathroomsPartial', true );
						$property_details_arr["HeatingFuel"]                 = null;
						$property_details_arr["HeatingType"]                 = null;
						$property_details_arr["SizeInterior"]                = null;
						$property_details_arr["StoriesTotal"]                = null;
						$property_details_arr["Type"]                        = null;
						$property_details_arr["Utilities"]                   = implode( ', ', json_decode( get_post_meta( $propertyListId, 'listingUtilityArr', true ) ) );
						$property_details_arr["UtilityPower"]                = null;
						$property_details_arr["UtilityWater"]                = null;
						$property_details_arr["BusinessType"]                = null;
						$property_details_arr["BusinessSubType"]             = null;
						$property_details_arr["SizeTotalText"]               = null;
						$property_details_arr["AccessType"]                  = null;
						$property_details_arr["Acreage"]                     = null;
						$property_details_arr["FenceType"]                   = null;
						$property_details_arr["LandDisposition"]             = null;
						$property_details_arr["LandscapeFeatures"]           = null;
						$property_details_arr["Sewer"]                       = null;
						$property_details_arr["ViewCount"]                   = get_post_meta( $propertyListId, 'crea_aretk_db_listing_page_count', true );
						$property_details_arr["Feedtype"]                    = null;
						$property_details_arr["listing_rooms"]               = null;

						// Open Houses
						$OpenHouseArr       = array();
						$OpenHouseDecodeArr = json_decode( get_post_meta( $propertyListId, 'listingopenhosedatetimeArr', true ) );
						if ( $OpenHouseDecodeArr != '' && ! empty( $OpenHouseDecodeArr ) ) {
							$openHouse_Counter = 0;
							foreach ( $OpenHouseDecodeArr as $OpenHouseDecodeArrKey => $OpenHouseDecodeArrValue ) {
								$openHouse_Counter ++;
								$startDate   = strtotime( $OpenHouseDecodeArrValue->date );
								$startTime   = $OpenHouseDecodeArrValue->start_time;
								$endTime     = $OpenHouseDecodeArrValue->end_time;
								$ohStartDate = date( 'Y-m-d', $startDate ) . " " . date( 'h:i:s A', strtotime( $startTime ) );
								$ohEndDate   = date( 'Y-m-d', $startDate ) . " " . date( 'h:i:s A', strtotime( $endTime ) );

								if ( $ohStartDate != "01/01/1970 12:00:00 AM" && $ohEndDate != "01/01/1970 12:00:00 AM" ) {
									$OpenHouseArr[] = array(
										"sequence_id"   => $openHouse_Counter,
										"StartDateTime" => $ohStartDate,
										"EndDateTime"   => $ohEndDate,
										'Comments'      => null
									);
								} else {
									$agentOpenHouseArr = "";
								}
							}
						}
						$property_details_arr["listing_openHouses"] = $OpenHouseArr;
						#Parking
						$parking_spaces = array();
						$parking_name   = get_post_meta( $propertyListId, 'listingParkinggarage', true );
						$parking_spaces = get_post_meta( $propertyListId, 'listingParkingSlot', true );
						if ( ! empty( $parking_name ) && ! empty( $parking_spaces ) ) {
							$parking_spaces                                = array();
							$parking_spaces[]                              = array(
								'SequenceID' => '1',
								'Name'       => get_post_meta( $propertyListId, 'listingParkinggarage', true ),
								'Spaces'     => get_post_meta( $propertyListId, 'listingParkingSlot', true )
							);
							$property_details_arr["listing_parkingSpaces"] = $parking_spaces;
						} else {
							$property_details_arr["listing_parkingSpaces"] = null;
						}
						#Photos
						$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
						$sql_select                            = "SELECT `image_url` FROM `$crea_listing_images_detail_table_name` WHERE `unique_id`= %d ORDER BY `image_position` ASC";
						$sql_prep                              = $wpdb->prepare( $sql_select, $propertyListId );
						$propertyimages                        = $wpdb->get_results( $sql_prep );

						$site_image_path = ARETK_CREA_PLUGIN_URL . 'public/images/preview_img.jpg';
						$property_photos = array();
						if ( isset( $propertyimages ) && ! empty( $propertyimages ) && is_array( $propertyimages ) ) {
							foreach ( $propertyimages as $propertyimage ) {
								if ( ! empty( $propertyimage->image_url ) ) {
									$property_photos[] = array(
										'URL'         => $propertyimage->image_url,
										'description' => null
									);
								}
							}
						} else {
							$property_photos[] = array( 'URL' => $site_image_path, 'description' => null );
						}
						$property_image_path                    = $property_photos[0]['URL'];
						$property_details_arr["listing_photos"] = $property_photos;

						$listing_full_address_path = '';
						$listing_full_address      = '';
						if ( ! empty( $listingAddress ) ) {
							$listingAddress = trim( $listingAddress );
							$listing_full_address .= trim( $listingAddress );
							$listingAddress = str_replace( ' ', '-', $listingAddress );
							$listingAddress = str_replace( '#', '', $listingAddress );
							$listingAddress = str_replace( '-', '-', $listingAddress );
							$listingAddress = str_replace( '--', '-', $listingAddress );
							$listingAddress = str_replace( '---', '-', $listingAddress );
							$listingAddress = str_replace( '----', '-', $listingAddress );
							$listing_full_address_path .= $listingAddress;
						}

						if ( ! empty( $ListingCity ) ) {
							$ListingCity = trim( $ListingCity );
							if ( ! empty( $listing_full_address ) ) {
								$listing_full_address .= ', ';
							}
							$listing_full_address .= $ListingCity;
							$ListingCity = str_replace( ' ', '-', $ListingCity );
							$ListingCity = str_replace( '#', '', $ListingCity );
							$ListingCity = str_replace( '-', '-', $ListingCity );
							$ListingCity = str_replace( '--', '-', $ListingCity );
							$ListingCity = str_replace( '---', '-', $ListingCity );
							$ListingCity = str_replace( '----', '-', $ListingCity );
							$listing_full_address_path .= '-' . $ListingCity;
						}

						if ( ! empty( $listingProvince ) ) {
							$listingProvince = trim( $listingProvince );
							if ( ! empty( $listing_full_address ) ) {
								$listing_full_address .= ' ';
							}
							$listing_full_address .= $listingProvince;
							$listingProvince = str_replace( ' ', '-', $listingProvince );
							$listingProvince = str_replace( '#', '', $listingProvince );
							$listingProvince = str_replace( '-', '-', $listingProvince );
							$listingProvince = str_replace( '--', '-', $listingProvince );
							$listingProvince = str_replace( '---', '-', $listingProvince );
							$listingProvince = str_replace( '----', '-', $listingProvince );
							$listing_full_address_path .= '-' . $listingProvince;
						}
						$property_link_url = site_url() . '/' . $page_slug . '/' . $propertyListId . '/' . sanitize_title( $listing_full_address_path );
					}
				}
				$property_detail_meta = array(
					"property_exists"    => $property_exists,
					"is_exclusive"       => $is_exclusive,
					"aretk_subscription" => $getSubscriptionListingFeed,
					"address_full"       => $listing_full_address,
					"slug"               => sanitize_title( $listing_full_address_path ),
					#"listing_type" => $listing_type,
					"url_canonical"      => $property_link_url,
					"image_primary"      => $property_image_path,
				);
				$new_url              = sanitize_title( 'This Long Title is what My Post or Page might be' );
				if ( ! is_array( $property_details_arr ) ) {
					$property_details_arr = array();
				}
				//Agents Array
				$listingAgents = ! empty( $property_details_arr['listing_agents'] ) ? $property_details_arr['listing_agents'] : '';
				$listingAgents = ! empty( $listingAgents ) ? $listingAgents : array();

				$property_details_arr                   = $property_detail_meta + $property_details_arr;
				$property_details_arr['listing_agents'] = $listingAgents;

				return $property_details_arr;
			}
		}
	}

	/**
	 * Custom pagination function
	 *
	 * @param unknown_type $numpages
	 * @param unknown_type $pagerange
	 * @param unknown_type $paged
	 */
	public static function custom_pagination( $numpages = '', $pagerange = '', $paged = '' ) {
		if ( empty( $pagerange ) ) {
			$pagerange = 2;
		}

		/**
		 * This first part of our function is a fallback
		 * for custom pagination inside a regular loop that
		 * uses the global $paged and global $wp_query variables.
		 */
		if ( $paged == '' ) {
			global $paged;
			if ( empty( $paged ) ) {
				$paged = 1;
			}
		}
		if ( $numpages == '' ) {
			global $wp_query;
			$numpages = $wp_query->max_num_pages;
			if ( ! $numpages ) {
				$numpages = 1;
			}
		}
		$pagenumber_id = basename( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
		if ( is_numeric( $pagenumber_id ) ) {
			$page_base_link = get_permalink() . '%_%';
		} else {
			$page_base_link = preg_replace( '/\?.*/', '', get_pagenum_link( 1 ) ) . '%_%';
		}

		$pagination_args = array(
			'base'               => $page_base_link,
			'format'             => 'page/%#%/',
			'total'              => $numpages,
			'current'            => $paged,
			'show_all'           => false,
			'end_size'           => 1,
			'mid_size'           => $pagerange,
			'prev_next'          => false,
			'prev_text'          => __( ' &laquo;' ),
			'next_text'          => __( ' &raquo;' ),
			'type'               => 'plain',
			'add_fragment'       => '',
			'after_page_number'  => '',
			'before_page_number' => '',
		);
		$paginate_links  = paginate_links( $pagination_args );
		if ( $paginate_links ) {
			$html = '';
			$html .= '<nav class="custom-pagination">';
			$html .= '<span class="page-numbers page-num pagination_label">Page ' . $paged . ' of  ' . $numpages . '</span>';
			$html .= $paginate_links;
			$html .= '</nav>';

			return $html;
		}
	}

	/**
	 * Update view count call API
	 *
	 * @param unknown_type $postId
	 */
	public static function update_view_count( $postId, $listing_count ) {
		$postId                = (int) $postId;
		$listing_count         = (int) $listing_count;
		$subscriptionKey       = Aretk_Crea_Public::aretkcrea_getsSubscriptionKey();
		$domainName            = Aretk_Crea_Public::aretk_domain();
		$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );

		if ( empty( $subscriptionKey ) || empty( $domainName ) || empty( $postId ) ) {
			return false;
		}

		if ( $getSubscriptionStatus === 'valid' ) {
			$update_view_count_settinges_array = array(
				"property_counts" => array(
					array(
						"id"    => (int) $postId,
						"count" => (int) $listing_count
					)
				)
			);
			$post_string                       = http_build_query( $update_view_count_settinges_array );
			$updateCount                       = curl_init();
			curl_setopt( $updateCount, CURLOPT_HEADER, 0 );
			curl_setopt( $updateCount, CURLOPT_VERBOSE, 0 );
			curl_setopt( $updateCount, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=update_view_count" );
			curl_setopt( $updateCount, CURLOPT_POST, true );
			curl_setopt( $updateCount, CURLOPT_POSTFIELDS, $post_string );
			curl_setopt( $updateCount, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $updateCount, CURLOPT_REFERER, $domainName );
			$updateCountCurlExecute = curl_exec( $updateCount );
			curl_close( $updateCount );
		}
	}

	/**
	 * This function will return array with selected listing data
	 *
	 * @param  SubscriptionKey , $filter_array
	 *
	 * @return array
	 */
	public static function aretk_get_listings_subsc( $subscriptionKey = null, $filter_array = null ) {
		if ( empty( $subscriptionKey ) ) {
			return false;
		}
		$allListingArr  = array();
		$filter_qry_str = Aretk_Crea_Public::aretk_listings_filter_qry_str( $filter_array );
		$ccurl          = "https://api.aretk.com/?key=$subscriptionKey&request=listings$filter_qry_str";
		$listings       = Aretk_Crea_Public::aretk_get_curl_results( $ccurl );
		if ( is_null( $listings ) ) {
			$listings = '[{"TotalRecords":0,"RecordsReturned":0}]';
		}
		$listings        = json_decode( $listings );
		$listing_results = array(
			'TotalRecords'    => $listings[0]->TotalRecords,
			'RecordsReturned' => $listings[0]->RecordsReturned
		);
		foreach ( $listings as $listing_key => $listing ) {
			if ( ! isset( $listing->TotalRecords ) && empty( $listing->TotalRecords ) ) {
				$allListingArr[ $listing->ID ] = $listing;
			}
		}
		$listing_results['listing_data'] = $allListingArr;

		return $listing_results;
	}

	/**
	 * This function will return array with selected listing data
	 *
	 * @param  SubscriptionKey , $filter_array
	 *
	 * @return array
	 */
	public static function aretk_get_listings_subsc_json( $subscriptionKey = null, $filter_array = null ) {
		if ( empty( $subscriptionKey ) ) {
			return false;
		}
		$allListingArr  = array();
		$filter_qry_str = Aretk_Crea_Public::aretk_listings_filter_qry_str( $filter_array );
		$ccurl          = "https://api.aretk.com/?key=$subscriptionKey&request=listings$filter_qry_str";
		$listings       = Aretk_Crea_Public::aretk_get_curl_results( $ccurl );
		if ( is_null( $listings ) ) {
			$listings = '[{"TotalRecords":0,"RecordsReturned":0}]';
		}

		return $listings;
	}

	public static function aretk_listing_filters( $filter_array = null, $postmeta_arr = null ) {
		if ( ! isset( $filter_array ) || ! is_array( $filter_array ) ) {
			$filter_array = array();
		}

		// filter - showcase filter board, comma delimited list of integers
		if ( ! empty( $postmeta_arr['showcse_crea_filter_brokerage'][0] ) ) {
			if ( ctype_digit( str_replace( ',', '', $postmeta_arr['showcse_crea_filter_brokerage'][0] ) ) ) {
				$filter_array['showcse_crea_filter_board_id'] = implode( ',', (array) $postmeta_arr['showcse_crea_filter_brokerage'][0] );
			}
		}
		// filter - showcase filter office, comma delimited list of integers
		if ( ! empty( $postmeta_arr['showcse_crea_filter_office'][0] ) ) {
			if ( ctype_digit( str_replace( ',', '', $postmeta_arr['showcse_crea_filter_office'][0] ) ) ) {
				$filter_array['showcse_crea_filter_office_id'] = implode( ',', (array) $postmeta_arr['showcse_crea_filter_office'][0] );
			}
		}
		// filter - showcase filter agent IDs, comma delimited list of integers
		if ( ! empty( $postmeta_arr['showcse_crea_filter_agent_name'][0] ) ) {
			if ( ctype_digit( str_replace( ',', '', $postmeta_arr['showcse_crea_filter_agent_name'][0] ) ) ) {
				$filter_array['showcse_crea_filter_agent_id'] = implode( ',', (array) $postmeta_arr['showcse_crea_filter_agent_name'][0] );
			}
		}
		// filter - listing ID, comma delimited list of integers
		if ( ! empty( $postmeta_arr['showcse_crea_filter_listing'][0] ) ) {
			if ( ctype_digit( str_replace( ',', '', $postmeta_arr['showcse_crea_filter_listing'][0] ) ) ) {
				$filter_array['showcse_crea_filter_listing_id'] = implode( ',', (array) $postmeta_arr['showcse_crea_filter_listing'][0] );
			}
		}
		// filter - by map area
		if ( ! empty( $postmeta_arr['showcse_crea_filter_by_map_km'][0] ) && is_numeric( $postmeta_arr['showcse_crea_filter_by_map_km'][0] ) ) {
			// filter - map radius, type float
			$filter_array['showcse_crea_filter_by_map_km'] = (float) $postmeta_arr['showcse_crea_filter_by_map_km'][0];
			// filter - latitude, type float
			$showcse_crea_filter_google_map_latitude = '';
			if ( ! empty( $postmeta_arr['showcse_crea_filter_google_map_latitude'][0] ) && is_numeric( $postmeta_arr['showcse_crea_filter_google_map_latitude'][0] ) ) {
				$filter_array['showcse_crea_filter_google_map_latitude'] = (float) $postmeta_arr['showcse_crea_filter_google_map_latitude'][0];
			}
			// filter - longitude
			$showcse_crea_filter_google_map_longitude = '';
			if ( ! empty( $postmeta_arr['showcse_crea_filter_google_map_longitude'][0] ) && is_numeric( $postmeta_arr['showcse_crea_filter_google_map_latitude'][0] ) ) {
				$filter_array['showcse_crea_filter_google_map_longitude'] = (float) $postmeta_arr['showcse_crea_filter_google_map_longitude'][0];
			}
		}
		// filter - by days added, type integer
		$showcse_crea_filter_by_other_day = '';
		if ( ! empty( $postmeta_arr['showcse_crea_filter_by_other_day'][0] ) && is_numeric( $postmeta_arr['showcse_crea_filter_by_other_day'][0] ) ) {
			$filter_array['showcse_crea_filter_by_other_day'] = (int) $postmeta_arr['showcse_crea_filter_by_other_day'][0];
		}
		// filter - open house, type string yes/no
		if ( ! empty( $postmeta_arr['showcse_crea_filter_inclue_open_house'][0] ) && $postmeta_arr['showcse_crea_filter_inclue_open_house'][0] === 'yes' ) {
			$filter_array['showcse_crea_filter_inclue_open_house'] = 'yes';
		}
		// filter - Price Min, type integer
		if ( isset( $_REQUEST['min_amount'] ) && is_numeric( $_REQUEST['min_amount'] ) ) {
			$filter_array['min_amount'] = (int) $_REQUEST['min_amount'];
		} elseif ( ! empty( $postmeta_arr['showcse_filter_price_min'][0] ) ) {
			$filter_array['min_amount'] = (int) $postmeta_arr['showcse_filter_price_min'][0];
		}
		// filter - Price Max, type integer
		if ( isset( $_REQUEST['max_amount'] ) && is_numeric( $_REQUEST['max_amount'] ) ) {
			$filter_array['max_amount'] = (int) $_REQUEST['max_amount'];
		} else if ( ! empty( $postmeta_arr['showcse_filter_price_max'][0] ) ) {
			$filter_array['max_amount'] = (int) $postmeta_arr['showcse_filter_price_max'][0];
		}
		// filter - mapbound_lat_sw, type float
		if ( ! empty( $_POST['mapbound_lat_sw'] ) && is_numeric( $_POST['mapbound_lat_sw'] ) ) {
			$filter_array['mapbound_lat_sw'] = floatval( $_POST['mapbound_lat_sw'] );
		} elseif ( ! empty( $_GET['mapbound_lat_sw'] ) && is_numeric( $_GET['mapbound_lat_sw'] ) ) {
			$filter_array['mapbound_lat_sw'] = floatval( $_GET['mapbound_lat_sw'] );
		}
		// filter - mapbound_lng_sw, type float
		if ( ! empty( $_POST['mapbound_lng_sw'] ) && is_numeric( $_POST['mapbound_lng_sw'] ) ) {
			$filter_array['mapbound_lng_sw'] = floatval( $_POST['mapbound_lng_sw'] );
		} elseif ( ! empty( $_GET['mapbound_lng_sw'] ) && is_numeric( $_GET['mapbound_lng_sw'] ) ) {
			$filter_array['mapbound_lng_sw'] = floatval( $_GET['mapbound_lng_sw'] );
		}
		// filter - mapbound_lat_ne, type float
		if ( ! empty( $_POST['mapbound_lat_ne'] ) && is_numeric( $_POST['mapbound_lat_ne'] ) ) {
			$filter_array['mapbound_lat_ne'] = floatval( $_POST['mapbound_lat_ne'] );
		} elseif ( ! empty( $_GET['mapbound_lat_ne'] ) && is_numeric( $_GET['mapbound_lat_ne'] ) ) {
			$filter_array['mapbound_lat_ne'] = floatval( $_GET['mapbound_lat_ne'] );
		}
		// filter - mapbound_lng_ne, type float
		if ( ! empty( $_POST['mapbound_lng_ne'] ) && is_numeric( $_POST['mapbound_lng_ne'] ) ) {
			$filter_array['mapbound_lng_ne'] = floatval( $_POST['mapbound_lng_ne'] );
		} elseif ( ! empty( $_GET['mapbound_lng_ne'] ) && is_numeric( $_GET['mapbound_lng_ne'] ) ) {
			$filter_array['mapbound_lng_ne'] = floatval( $_GET['mapbound_lng_ne'] );
		}
		// filter - keyword, type string
		if ( ! empty( $_POST['keyword'] ) ) {
			$filter_array['keyword'] = sanitize_text_field( $_POST['keyword'] );
		} else if ( ! empty( $_GET['keyword'] ) ) {
			$filter_array['keyword'] = sanitize_text_field( $_GET['keyword'] );
		}
		// filter - property types, string
		if ( isset( $_POST['property_types'] ) ) {
			$filter_array['property_types'] = sanitize_text_field( implode( ',', (array) $_POST['property_types'] ) );
		} elseif ( isset( $_GET['property_types'] ) ) {
			$filter_array['property_types'] = sanitize_text_field( implode( ',', (array) $_GET['property_types'] ) );
		} else if ( ! empty( $postmeta_arr['showcase_filter_property_types'][0] ) && empty( $_GET ) && empty( $_POST ) ) {
			$filter_array['property_types'] = sanitize_text_field( implode( ',', (array) $postmeta_arr['showcase_filter_property_types'] ) );
		}
		
		// filter - ownership types, string
		if ( isset( $_POST['ownership_types'] ) ) {
			$filter_array['ownership_types'] = sanitize_text_field( implode( ',', (array) $_POST['ownership_types'] ) );
		} elseif ( isset( $_GET['ownership_types'] ) ) {
			$filter_array['ownership_types'] = sanitize_text_field( implode( ',', (array) $_GET['ownership_types'] ) );
		} else if ( ! empty( $postmeta_arr['showcase_filter_ownership_types'][0] ) ) {
			$filter_array['ownership_types'] = sanitize_text_field( implode( ',', (array) $postmeta_arr['showcase_filter_ownership_types'] ) );
		}
		
		// filter - property status, string
		if ( isset( $_POST['property_status'] ) ) {
			$filter_array['transaction_type'] = sanitize_text_field( implode( ',', (array) $_POST['property_status'] ) );
		} else if ( isset( $_GET['property_status'] ) ) {
			$filter_array['transaction_type'] = sanitize_text_field( implode( ',', (array) $_GET['property_status'] ) );
		} else if ( ! empty( $postmeta_arr['showcase_filter_property_status'][0] ) ) {
			$filter_array['transaction_type'] = sanitize_text_field( implode( ',', (array) $postmeta_arr['showcase_filter_property_status'] ) );
		}
		// filter - agent IDs, comma delimited list of integers
		if ( isset( $_POST['agent_ids'] ) ) {
			if ( ctype_digit( str_replace( ',', '', $_POST['agent_ids'] ) ) ) {
				$filter_array['agent_ids'] = implode( ',', (array) $_POST['agent_ids'] );
			}
		} elseif ( isset( $_GET['agent_ids'] ) ) {
			if ( ctype_digit( str_replace( ',', '', $_GET['agent_ids'] ) ) ) {
				$filter_array['agent_ids'] = implode( ',', (array) $_GET['agent_ids'] );
			}
		} else if ( ! empty( $postmeta_arr['showcase_filter_listing_agent_ids'][0] ) ) {
			if ( ctype_digit( str_replace( ',', '', $postmeta_arr['showcase_filter_listing_agent_ids'][0] ) ) ) {
				$filter_array['agent_ids'] = implode( ',', (array) $postmeta_arr['showcase_filter_listing_agent_ids'] );
			}
		}
		// filter - Province, type string
		if ( isset( $_POST['province'] ) ) {
			$filter_array['province'] = sanitize_text_field( implode( ',', (array) $_POST['province'] ) );
		} elseif ( isset( $_GET['province'] ) ) {
			$filter_array['province'] = sanitize_text_field( implode( ',', (array) $_GET['province'] ) );
		} elseif ( ! empty( $postmeta_arr['showcase_filter_listing_province'][0] ) ) {
			$filter_array['province'] = sanitize_text_field( implode( ',', (array) $postmeta_arr['showcase_filter_listing_province'] ) );
		}
		// filter - Structures, type string
		if ( ! empty( $_POST['structure_types'] ) ) {
			$filter_array['structure_types'] = sanitize_text_field( implode( ',', (array) $_POST['structure_types'] ) );
		} elseif ( ! empty( $_GET['structure_types'] ) ) {
			$filter_array['structure_types'] = sanitize_text_field( implode( ',', (array) $_GET['structure_types'] ) );
		}
		// filter - Bedrooms, type integer
		if ( ! empty( $_POST['bedrooms'] ) && is_numeric( $_POST['bedrooms'] ) ) {
			$filter_array['bedrooms'] = (int) $_POST['bedrooms'];
		} elseif ( ! empty( $_GET['bedrooms'] ) && is_numeric( $_GET['bedrooms'] ) ) {
			$filter_array['bedrooms'] = (int) $_GET['bedrooms'];
		}
		// filter - Bathrooms, type integer
		if ( ! empty( $_POST['bathrooms'] ) && is_numeric( $_POST['bathrooms'] ) ) {
			$filter_array['bathrooms'] = (int) $_POST['bathrooms'];
		} elseif ( ! empty( $_GET['bathrooms'] ) && is_numeric( $_GET['bathrooms'] ) ) {
			$filter_array['bathrooms'] = (int) $_GET['bathrooms'];
		}
		// Add frontend Sorting ability
		if ( ! empty( $_POST['order'] ) ) {
			$filter_array['listings_sortby'] = sanitize_text_field($_POST['order']);
		} elseif ( ! empty( $_GET['order'] ) ) {
			$filter_array['listings_sortby'] = sanitize_text_field($_GET['order']);
		}
		return $filter_array;
	}

	public static function aretk_listings_filter_qry_str( $filter_array ) {
		if ( empty( $filter_array ) ) {
			return null;
		}
		$filter_str = null;
		if ( !empty($filter_array['showcse_crea_filter_inclue_open_house']) && $filter_array['showcse_crea_filter_inclue_open_house'] === 'yes' ) {
			$filter_str .= '&openhouse=true';
		}
		if ( ! empty( $filter_array['property_ids'] ) ) {
			$filter_str .= '&ids=' . $filter_array['property_ids'];
		}
		if ( ! empty( $filter_array['showcse_crea_filter_by_other_day'] ) ) {
			$filter_str .= '&daysactive=' . $filter_array['showcse_crea_filter_by_other_day'];
		}
		if ( ! empty( $filter_array['showcse_crea_filter_by_map_km'] ) && ! empty( $filter_array['showcse_crea_filter_google_map_latitude'] ) && ! empty( $filter_array['showcse_crea_filter_google_map_longitude'] ) ) {
			$filter_str .= '&area=' . $filter_array['showcse_crea_filter_by_map_km'] . '(' . $filter_array['showcse_crea_filter_google_map_latitude'] . ',' . $filter_array['showcse_crea_filter_google_map_longitude'] . ')';
		}
		if ( ! empty( $filter_array['listings_sortby'] ) ) {
			$filter_str .= '&order=' . $filter_array['listings_sortby'];
		}
		if ( ! empty( $filter_array['showcse_crea_filter_office_id'] ) ) {
			$filter_str .= '&office_ids=' . $filter_array['showcse_crea_filter_office_id'];
		}
		if ( ! empty( $filter_array['showcse_crea_filter_board_id'] ) ) {
			$filter_str .= '&board=' . $filter_array['showcse_crea_filter_board_id'];
		}
		if ( ! empty( $filter_array['showcse_crea_filter_agent_id'] ) ) {
			$filter_str .= '&agent_ids=' . $filter_array['showcse_crea_filter_agent_id'];
		}
		if ( ! empty( $filter_array['agent_ids'] ) ) {
			$filter_str .= '&agent_ids=' . $filter_array['agent_ids'];
		}
		if ( ! empty( $filter_array['keyword'] ) ) {
			$filter_str .= '&keyword=' . rawurlencode( $filter_array['keyword'] );
		}
		if ( ! empty( $filter_array['property_types'] ) ) {
			$filter_str .= '&property_types=' . rawurlencode( $filter_array['property_types'] );
		}
		if ( ! empty( $filter_array['ownership_types'] ) ) {
			$filter_str .= '&ownership_types=' . rawurlencode( $filter_array['ownership_types'] );
		}
		if ( ! empty( $filter_array['structure_types'] ) ) {
			$filter_str .= '&structure_types=' . rawurlencode( $filter_array['structure_types'] );
		}
		if ( ! empty( $filter_array['transaction_type'] ) ) {
			$filter_str .= '&transaction_type=' . rawurlencode( $filter_array['transaction_type'] );
		}
		if ( ! empty( $filter_array['bedrooms'] ) ) {
			$filter_str .= '&bed_min=' . $filter_array['bedrooms'];
		}
		if ( ! empty( $filter_array['bathrooms'] ) ) {
			$filter_str .= '&bath_min=' . $filter_array['bathrooms'];
		}
		if ( ! empty( $filter_array['city'] ) ) {
			$filter_str .= '&city=' . rawurlencode( $filter_array['city'] );
		}
		if ( ! empty( $filter_array['province'] ) ) {
			$filter_str .= '&province=' . rawurlencode( $filter_array['province'] );
		}
		if ( ! empty( $filter_array['min_amount'] ) ) {
			$filter_str .= '&price_min=' . $filter_array['min_amount'];
		}
		if ( ! empty( $filter_array['max_amount'] ) ) {
			$filter_str .= '&price_max=' . $filter_array['max_amount'];
		}
		if ( ! empty( $filter_array['include_exclusive'] ) && $filter_array['include_exclusive'] === 'yes' || $filter_array['include_exclusive'] === 'true' ) {
			$filter_str .= '&exclusive=true';
		}
		if ( ! empty( $filter_array['record_limit'] ) ) {
			$filter_str .= '&limit=' . $filter_array['record_limit'];
		}
		if ( ! empty( $filter_array['record_offset'] ) ) {
			$filter_str .= '&offset=' . $filter_array['record_offset'];
		}
		if (!empty($filter_array['result_type'])){
			switch ( $filter_array['result_type'] ) {
				case 'full':
				case 'basic':
				case 'mapmarkers':
					$filter_str .= '&result_type=' . $filter_array['result_type'];
					break;
			}
		}
		if ( ! empty( $filter_array['mapbound_lat_ne'] ) ) {
			$filter_str .= '&lat_ne=' . $filter_array['mapbound_lat_ne'];
		}
		if ( ! empty( $filter_array['mapbound_lat_sw'] ) ) {
			$filter_str .= '&lat_sw=' . $filter_array['mapbound_lat_sw'];
		}
		if ( ! empty( $filter_array['mapbound_lng_ne'] ) ) {
			$filter_str .= '&lng_ne=' . $filter_array['mapbound_lng_ne'];
		}
		if ( ! empty( $filter_array['mapbound_lng_sw'] ) ) {
			$filter_str .= '&lng_sw=' . $filter_array['mapbound_lng_sw'];
		}
		if ( ! empty( $filter_array['crea_feed_id'] ) && $filter_array['crea_feed_id'] !== 'Exclusive Listing' ) {
			$filter_str .= '&feed=' . $filter_array['crea_feed_id'];
		}

		return $filter_str;
	}

	public static function aretk_domain() {
		$domainName = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
		if ( ! empty( $domainName && $domainName !== 'localhost') ) {
			$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
		} else {
			$domainName = get_site_url();
			$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
		}

		return $domainName;
	}

	public static function aretkcrea_getsSubscriptionKey() {
		$getSubscriptionKey = get_option( 'crea_subscription_key', '' );
		$subscriptionKey    = preg_replace( "/[^a-z0-9-]+/i", "", $getSubscriptionKey );
		$subscriptionKey    = ! empty( $subscriptionKey ) ? $subscriptionKey : '';

		return $subscriptionKey;
	}

	public static function aretk_get_curl_results( $ccurl ) {
		if ( empty( $ccurl ) ) {
			return false;
		}
		$domainName = Aretk_Crea_Public::aretk_domain();
		$ch         = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $ccurl );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_REFERER, $domainName );
		$data = curl_exec( $ch );
		curl_close( $ch );

		return $data;
	}

	public static function aretk_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			$ip = null;
		}

		return $ip;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function aretkcrea_enqueue_styles() {
		wp_enqueue_style( 'jQuery-public-ui-style', plugin_dir_url( __FILE__ ) . 'css/jquery-public-ui.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/aretk-crea-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery.bxslider-css', plugin_dir_url( __FILE__ ) . 'css/jquery.bxslider.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'nouislider-min-css', plugin_dir_url( __FILE__ ) . 'css/nouislider.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function aretkcrea_enqueue_scripts() {
		global $post, $wpdb;
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-slider' );
		$google_map_api                  = get_option( 'google-map-api-name' );
		$google_map_script_loaded_or_not = get_option( 'crea_google_map_script_load_or_not' );
		$google_map_api_key_pass         = '';
		if ( isset( $google_map_api ) && ! empty( $google_map_api ) ) {
			$google_map_api_results = $google_map_api;
			$google_map_api_key_pass .= "?key=$google_map_api_results";
		}
		if ( isset( $google_map_script_loaded_or_not ) && empty( $google_map_script_loaded_or_not ) ) {
			if ( $google_map_script_loaded_or_not === 'Yes' ) {
				wp_enqueue_script( 'google-map-js', "https://maps.googleapis.com/maps/api/js$google_map_api_key_pass", array( 'jquery' ), $this->version, true );
				wp_enqueue_script( 'markerclusterer_compiled-js', plugin_dir_url( __FILE__ ) . 'js/markerclusterer_compiled.js', array( 'jquery','google-map-js' ), $this->version, true );	
			} 
		}

		if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'ARTEK-BF' ) && ! empty( esc_attr( get_option( 'aretk_googleCaptchaKey_public' ) ) ) && ! empty( esc_attr( get_option( 'aretk_googleCaptchaKey_private' ) ) ) ) {
			wp_register_script( 'aretk_bfform_recaptcha', 'https://www.google.com/recaptcha/api.js?onload=aretkcaptcha_onLoad_bfform&render=explicit', null, null, true );
			wp_enqueue_script( 'aretk_bfform_recaptcha' );

			function aretk_add_asyncdeffer_attribute_bfform( $tag, $handle ) {
				if ( 'aretk_bfform_recaptcha' !== $handle ) {
					return $tag;
				}

				return str_replace( ' src', ' async defer src', $tag );
			}

			add_filter( 'script_loader_tag', 'aretk_add_asyncdeffer_attribute_bfform', 10, 2 );
		}
		
		wp_enqueue_script( 'markerclusterer_compiled-js', plugin_dir_url( __FILE__ ) . 'js/markerclusterer_compiled.js', array( 'jquery','google-map-js' ), $this->version, true );	
		
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aretk-crea-public.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->plugin_name, 'adminajaxjs', array( 'adminajaxjsurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( 'accordion-public', plugin_dir_url( __FILE__ ) . 'js/accordion-public.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'bxlsider-public', plugin_dir_url( __FILE__ ) . 'js/jquery.bxslider.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'nouislider-min-js', plugin_dir_url( __FILE__ ) . 'js/nouislider.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'loader-js-one', plugin_dir_url( __FILE__ ) . 'js/modernizr.js', array( 'jquery' ), $this->version, true );
		if ( isset( $post->post_content ) ) {
			if ( has_shortcode( $post->post_content, 'ARTEK-BF' ) || has_shortcode( $post->post_content, 'ARTEK-SF' ) || has_shortcode( $post->post_content, 'ARTEK-CF' ) ) {
				wp_enqueue_script( 'crealead-js', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', array( 'jquery' ), false, $this->version );
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/shortcose_validation.js', array( 'jquery' ), $this->version, false );
			}
		}
	}


	/**
	 * Replace the default page title on the property details page
	 *
	 */
	function replace_title_propertydetails( $title, $id ) {
		global $property_details_arr;
		$item = get_post( $id );
		if ( !empty($item) && $item->post_type === 'nav_menu_item' ) {
			return $title;
		}
		if ( ( strpos( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), 'listing-detail' ) !== false || strpos( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), 'listing-details' ) !== false ) && in_the_loop() && $item->post_name === 'listing-details') {
			if ( empty( $property_details_arr ) ) {
				$property_details_arr = Aretk_Crea_Public::get_property_meta();
			}
			$title = $property_details_arr['address_full'];
		}
		return $title;
	}

	/**
	 * Reminder Cron Schedule
	 *
	 */
	function reminder_cron_schedule( $schedules ) {
		$schedules['reminder_minute']                      = array(
			'interval' => 900,  # 15 minutes
			'display'  => __( 'Reminder Every Minute' )
		);
		$schedules['every_one_minutes_check_subscription'] = array(
			'interval' => 3600, # 60 minutes
			'display'  => __( 'Every 60 minutes' )
		);
		$schedules['every_one_hour_expiration_event']      = array(
			'interval' => 1800, # 30 minutes
			'display'  => __( 'Every 30 minutes' )
		);

		return $schedules;
	}

	/**
	 * Cron function for get data
	 *
	 */
	function aretk_subscription_cron_function_to_run() {
		global $wpdb;
		$subscriptionKey = Aretk_Crea_Public::aretkcrea_getsSubscriptionKey();
		$domainName      = Aretk_Crea_Public::aretk_domain();
		if ( empty( $subscriptionKey ) || empty( $domainName ) ) {
			return false;
		}
		$time         = time();
		$current_date = date_i18n( 'Y-m-d H:i' );
		update_option( 'cron_subscription', $time . $current_date );
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, ARETKCREA_SUBSCRIPTIONENDPOINT . "?api-key=$subscriptionKey&domain_name=$domainName" );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_REFERER, $domainName );
		$data = curl_exec( $ch );
		curl_close( $ch );
		$resultSet = json_decode( $data );
		if ( isset( $resultSet ) && ! empty( $resultSet ) ) {
			if ( $resultSet->code === 'success' ) {
				update_option( 'crea_subscription_status', 'valid' );
				update_option( 'crea_subscription_key', "$subscriptionKey" );

				return 'valid';
			} else {
				update_option( 'crea_subscription_status', 'not-valid' );

				#update_option('crea_subscription_key',""); # keep
				return 'not-valid';
			}
		} else {
			update_option( 'crea_subscription_status', 'not-valid' );
			#update_option('crea_subscription_key',""); #keep
		}

		#-------------------------------------

		//Cron Check if any property needs to be added
		//code for subscription status in-active stored data record switch in aretk-api server
		$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );
		if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
			$user_ID                        = get_current_user_id();
			$exclusive_stored_add_id_option = get_option( "exclusive_stored_add_id" );
			if ( ! empty( $exclusive_stored_add_id_option ) && $exclusive_stored_add_id_option != '' ) {
				$exclusive_stored_add_id_results = '';
				$exclusive_stored_add_id_results = json_decode( get_option( "exclusive_stored_add_id" ) );
				if ( isset( $exclusive_stored_add_id_results ) && ! empty( $exclusive_stored_add_id_results ) ) {
					foreach ( $exclusive_stored_add_id_results as $exclusive_stored_add_id ) {
						$postId     = $exclusive_stored_add_id;
						$get_option = json_decode( get_option( 'crea_subscription_active_stored_Id' ) );
						$action     = 'add';
						if ( $action === 'add' || $action === 'edit' ) {
							$listingAgentID             = get_post_meta( $postId, 'listingAgentId', true );
							$listingAddress             = get_post_meta( $postId, 'listingAddress', true );
							$listingcity                = get_post_meta( $postId, 'listingcity', true );
							$listingProvince            = get_post_meta( $postId, 'listingProvince', true );
							$listingAgentStatus         = get_post_meta( $postId, 'listingAgentStatus', true );
							$listingPrice               = get_post_meta( $postId, 'listingPrice', true );
							$listingPropertyType        = get_post_meta( $postId, 'listingPropertyType', true );
							$listingStructureType       = get_post_meta( $postId, 'listingStructureType', true );
							$listingBedRooms            = get_post_meta( $postId, 'listingBedRooms', true );
							$listingBathrooms           = get_post_meta( $postId, 'listingBathrooms', true );
							$listingBathroomsPartial    = get_post_meta( $postId, 'listingBathroomsPartial', true );
							$listingFinishedBasement    = get_post_meta( $postId, 'listingFinishedBasement', true );
							$listingFeatureArr          = get_post_meta( $postId, 'listingFeatureArr', true );
							$listingParkinggarage       = get_post_meta( $postId, 'listingParkinggarage', true );
							$listingParkingSlot         = get_post_meta( $postId, 'listingParkingSlot', true );
							$listingTourUrl             = get_post_meta( $postId, 'listingTourUrl', true );
							$listingUtilityArr          = get_post_meta( $postId, 'listingUtilityArr', true );
							$listingopenhosedatetimeArr = get_post_meta( $postId, 'listingopenhosedatetimeArr', true );
							$listingGoogleMapLatitude   = get_post_meta( $postId, 'crea_google_map_latitude', true );
							$listingGoogleMapLongitude  = get_post_meta( $postId, 'crea_google_map_longitude', true );
							$listing_full_address_path  = '';
							if ( ! empty( $listingAddress ) ) {
								$listing_full_address_path .= sanitize_title( $listingAddress );
							}
							if ( ! empty( $listingcity ) ) {
								$listing_full_address_path .= '-' . sanitize_title( $listingcity );
							}
							if ( ! empty( $listingProvince ) ) {
								$listing_full_address_path .= '-' . sanitize_title( $listingProvince );
							}
							$content_post                = get_post( $postId );
							$content                     = $content_post->post_content;
							$listing_public_remarks      = $content;
							$agentFeaturesDecodeArrValue = '';
							//get agent ids array
							$agentsIDArr      = array();
							$agentsDecodeArr  = json_decode( $listingAgentID );
							$implode_agent_id = '';
							if ( $agentsDecodeArr != '' && ! empty( $agentsDecodeArr ) ) {
								$agent_id_counter = 1;
								foreach ( $agentsDecodeArr as $agentsDecodekey => $agentsDecodeArrValue ) {
									$agentFeaturesDecodeArrValue = trim( $agentFeaturesDecodeArrValue );
									if ( ! empty( $agentsDecodeArrValue ) ) {
										$agentsIDArr[] = array(
											"sequence_id" => $agent_id_counter,
											"agent_id"    => $agentsDecodeArrValue
										);
									}
									$agent_id_counter = $agent_id_counter + 1;
								}
							}
							// get agent features
							$agentFeaturesArr       = array();
							$agentFeaturesFinaleArr = '';
							$agentFeaturesDecodeArr = json_decode( $listingFeatureArr );
							if ( $agentFeaturesDecodeArr != '' && ! empty( $agentFeaturesDecodeArr ) ) {
								foreach ( $agentFeaturesDecodeArr as $agentFeaturesDecodeArrValue ) {
									$agentFeaturesDecodeArrValue = trim( $agentFeaturesDecodeArrValue );
									if ( ! empty( $agentFeaturesDecodeArrValue ) ) {
										$agentFeaturesArr[] = trim( $agentFeaturesDecodeArrValue );
									}
								}
								$agentFeaturesFinaleArr = implode( ",", $agentFeaturesArr );
							}
							// get agents utilitees
							$agentUtilitiesArr       = array();
							$agentUtilitiesDecodeArr = json_decode( $listingUtilityArr );
							if ( $agentUtilitiesDecodeArr != '' && ! empty( $agentUtilitiesDecodeArr ) ) {
								$utitlity_counter = 1;
								foreach ( $agentUtilitiesDecodeArr as $agentUtilitiesDecodeArrValue ) {
									$agentUtilitiesDecodeArrValue = trim( $agentUtilitiesDecodeArrValue );
									if ( ! empty( $agentUtilitiesDecodeArrValue ) ) {
										$agentUtilitiesArr[] = array(
											"sequence_id" => $utitlity_counter,
											"type"        => $agentUtilitiesDecodeArrValue
										);
									}
									$utitlity_counter = $utitlity_counter + 1;
								}
							}
							// get agent images
							$agentPhotoArr     = array();
							$photoGelleryTable = $wpdb->prefix . 'crea_listing_images_detail';
							$sql_select        = "SELECT * FROM `$photoGelleryTable` WHERE `unique_id`= %d ORDER BY `image_position` ASC";
							$sql_prep          = $wpdb->prepare( $sql_select, $postId );
							$photoResultsArr   = $wpdb->get_results( $sql_prep );

							if ( $photoResultsArr != '' && ! empty( $photoResultsArr ) ) {
								$photo_counter = 1;
								foreach ( $photoResultsArr as $photoResultsArrValue ) {
									$agentPhotoArr[] = array(
										"sequence_id" => $photo_counter,
										"url"         => $photoResultsArrValue->image_url
									);
									$photo_counter   = $photo_counter + 1;
								}
							}
							// get external document
							$agentDocumentArr      = array();
							$agentDocumentFinalArr = '';
							$agentDocumentTable    = $wpdb->prefix . 'crea_listing_document_detail';
							$sql_select            = "SELECT * FROM `$agentDocumentTable` WHERE `unique_id`= %d ORDER BY `id`";
							

							$sql_prep              = $wpdb->prepare( $sql_select, $postId );
							$agentDocumentResults  = $wpdb->get_results( $sql_prep );

							if ( $agentDocumentResults != '' && ! empty( $agentDocumentResults ) ) {
								foreach ( $agentDocumentResults as $agentDocumentResultsValue ) {
									$agentDocumentArr[] = $agentDocumentResultsValue->document_url;
								}
								$agentDocumentFinalArr = implode( ",", $agentDocumentArr );
							}
							
							// agent Open House date and time
							$agentOpenHouseArr       = array();
							$agentOpenHouseDecodeArr = json_decode( $listingopenhosedatetimeArr );
							if ( $agentOpenHouseDecodeArr != '' && ! empty( $agentOpenHouseDecodeArr ) ) {
								$openHouse_Counter = 1;
								foreach ( $agentOpenHouseDecodeArr as $agentOpenHouseDecodeArrKey => $agentOpenHouseDecodeArrValue ) {
									$startDate            = strtotime( $agentOpenHouseDecodeArrValue->date );
									$startTime            = $agentOpenHouseDecodeArrValue->start_time;
									$endTime              = $agentOpenHouseDecodeArrValue->end_time;
									$agentStartDate       = date( 'm/d/Y', $startDate ) . " " . date( 'h:i:s A', strtotime( $startTime ) );
									$agentEndDate         = date( 'm/d/Y', $startDate ) . " " . date( 'h:i:s A', strtotime( $endTime ) );
									$agentFormatStartDate = $agentStartDate;
									$agentFormatEndDate   = $agentEndDate;
									if ( $agentFormatStartDate != "01/01/1970 12:00:00 AM" && $agentFormatEndDate != "01/01/1970 12:00:00 AM" ) {
										$agentOpenHouseArr[] = array(
											"sequence_id" => $openHouse_Counter,
											"start_date"  => $agentFormatStartDate,
											"end_date"    => $agentFormatEndDate
										);
									} else {
										$agentOpenHouseArr = "";
									}
									$openHouse_Counter = $openHouse_Counter + 1;
								}
							}
							if ( $postId != '' ) {
								if ( $listingGoogleMapLatitude == '57.678079218156' ) {
									$listingGoogleMapLatitude = '';
								}
								if ( $listingGoogleMapLongitude == '-101.8051686875' ) {
									$listingGoogleMapLongitude = '';
								}
								$add_listing_settinges_array = array();
								$add_listing_settinges_array = array(
									"agent_id"             => $agentsIDArr,
									"street_address"       => $listingAddress,
									"city"                 => $listingcity,
									"province"             => $listingProvince,
									"transaction_type"     => $listingAgentStatus,
									"price"                => $listingPrice,
									"property_type"        => $listingPropertyType,
									"structure"            => $listingStructureType,
									"bedrooms_total"       => $listingBedRooms,
									"bathroom_total"       => $listingBathrooms,
									"halfbath_total"       => $listingBathroomsPartial,
									"basement_type"        => $listingFinishedBasement,
									"public_remarks"       => $listing_public_remarks,
									"features"             => $agentFeaturesFinaleArr,
									"garage"               => $listingParkinggarage,
									"no_of_parking_spot"   => $listingParkingSlot,
									"moreInformation_link" => $listingTourUrl,
									"utilities"            => $agentUtilitiesArr,
									"photo"                => $agentPhotoArr,
									"external_document"    => $agentDocumentFinalArr,
									"open_house"           => $agentOpenHouseArr,
									"generated_address"    => $listing_full_address_path,
									"geocoded_latitude"    => $listingGoogleMapLatitude,
									"geocoded_longitude"   => $listingGoogleMapLongitude,
								);
								$post_string                 = http_build_query( $add_listing_settinges_array );
								$exclusive_property_id       = array();
								$exclusive_property_id[]     = (string) $postId;
								if ( $action === 'add' ) {
									if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
										$addListing = curl_init();
										curl_setopt( $addListing, CURLOPT_HEADER, 0 );
										curl_setopt( $addListing, CURLOPT_VERBOSE, 0 );
										curl_setopt( $addListing, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=insert_listing" );
										curl_setopt( $addListing, CURLOPT_POST, true );
										curl_setopt( $addListing, CURLOPT_POSTFIELDS, $post_string );
										curl_setopt( $addListing, CURLOPT_RETURNTRANSFER, true );
										curl_setopt( $addListing, CURLOPT_REFERER, $domainName );
										$addListingCurlExecute = curl_exec( $addListing );
										curl_close( $addListing );
										$addListingCurlExecuteResponse = ( $addListingCurlExecute ) . PHP_EOL;
										$responseDecode                = json_decode( $addListingCurlExecuteResponse );
										if ( isset( $responseDecode->code ) && ! empty( $responseDecode->code ) ) {
											if ( $responseDecode->code === 200 && $responseDecode->status === 'success' ) {
												if ( isset( $responseDecode->data->insert_id ) ) {
													update_post_meta( $postId, 'aretk_server_listing_id', (int) $responseDecode->data->insert_id );
													$exclusive_old_property_id_array = array();
													$mergerd_property_id_array       = array();
													$exclusive_stored_add_id_result  = get_option( "exclusive_stored_add_id" );
													if ( ! empty( $exclusive_stored_add_id_result ) ) {
														$exclusive_old_property_id_array = json_decode( $exclusive_stored_add_id_result );
													}
													$mergerd_property_id_array = array_diff( $exclusive_old_property_id_array, $exclusive_property_id );
													$mergerd_property_id_array = json_encode( $mergerd_property_id_array );
													update_option( "exclusive_stored_add_id", $mergerd_property_id_array );
												}
											} else {
												$exclusive_old_property_id_array = array();
												$mergerd_property_id_array       = array();
												$exclusive_stored_add_id_result  = get_option( "exclusive_stored_add_id" );
												if ( ! empty( $exclusive_stored_add_id_result ) ) {
													$exclusive_old_property_id_array = json_decode( $exclusive_stored_add_id_result );
												}
												$mergerd_property_id_array = array_merge( $exclusive_old_property_id_array, $exclusive_property_id );
												$mergerd_property_id_array = array_unique( $mergerd_property_id_array );
												$mergerd_property_id_array = json_encode( $mergerd_property_id_array );
												update_option( "exclusive_stored_add_id", $mergerd_property_id_array );
											}
										} else {
											$exclusive_old_property_id_array = array();
											$mergerd_property_id_array       = array();
											$exclusive_stored_add_id_result  = get_option( "exclusive_stored_add_id" );
											if ( ! empty( $exclusive_stored_add_id_result ) ) {
												$exclusive_old_property_id_array = json_decode( $exclusive_stored_add_id_result );
											}
											$mergerd_property_id_array = array_merge( $exclusive_old_property_id_array, $exclusive_property_id );
											$mergerd_property_id_array = array_unique( $mergerd_property_id_array );
											$mergerd_property_id_array = json_encode( $mergerd_property_id_array );
											update_option( "exclusive_stored_add_id", $mergerd_property_id_array );
										}
									}
								}
							}
						}
					}
				}
			}
		}

		//Delete Exclusive property from server
		$exclusive_deleted_id_result = get_option( "exclusive_deleted_ids" );
		if ( ! empty( $exclusive_deleted_id_result ) && $exclusive_deleted_id_result != 'null' ) {
			$exclusive_old_property_id_array = json_decode( $exclusive_deleted_id_result );
			if ( isset( $exclusive_old_property_id_array ) && ! empty( $exclusive_old_property_id_array ) ) {
				foreach ( $exclusive_old_property_id_array as $exclusive_old_property_id ) {
					if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
						$delete_listing = array(
							"id" => (int) $exclusive_old_property_id
						);
						$post_string    = http_build_query( $delete_listing );
						$deleteListing  = curl_init();
						curl_setopt( $deleteListing, CURLOPT_HEADER, 0 );
						curl_setopt( $deleteListing, CURLOPT_VERBOSE, 0 );
						curl_setopt( $deleteListing, CURLOPT_RETURNTRANSFER, true );
						curl_setopt( $deleteListing, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=delete_listing" );
						curl_setopt( $deleteListing, CURLOPT_POST, true );
						curl_setopt( $deleteListing, CURLOPT_POSTFIELDS, $post_string );
						curl_setopt( $deleteListing, CURLOPT_REFERER, $domainName );
						$deleteListingCurlExecute = curl_exec( $deleteListing );
						curl_close( $deleteListing );
						$deleteListingCurlExecuteResponse = ( $deleteListingCurlExecute ) . PHP_EOL;
						$responseDecode                   = json_decode( $deleteListingCurlExecuteResponse );
					}
				}
			}
		}
	}

	public function call_wp_schedule_event() {
		// for reminder functionlity check every minute
		if ( ! wp_next_scheduled( 'content_scheduler_reminder_every_minute' ) ) {
			wp_schedule_event( time(), 'reminder_minute', 'content_scheduler_reminder_every_minute' );
		}
		// for expirations subscription call, run evey 60 minutes
		if ( ! wp_next_scheduled( 'content_scheduler_subscription' ) ) {
			wp_schedule_event( time(), 'every_one_minutes_check_subscription', 'content_scheduler_subscription' );
		}
		// run evey 30 minutes
		if ( ! wp_next_scheduled( 'content_scheduler_expiration_event' ) ) {
			wp_schedule_event( time(), 'every_one_hour_expiration_event', 'content_scheduler_expiration_event' );
		}
	}

	function aretkcrea_answer_expiration_event() {
		global $wpdb;
		$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );
		$current_date          = date_i18n( 'Y-m-d H:i' );
		update_option( 'cron_run', "" );
		update_option( 'cron_run_time', "" );
		update_option( 'cron_run_time', $current_date );
		if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
			$crea_user_name_table_name = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
			$sql_select                = "SELECT `username` FROM `$crea_user_name_table_name`";
			$sql_prep                  = $wpdb->prepare( $sql_select, null );
			$getAllUsername            = $wpdb->get_results( $sql_prep );
			$allListingArr             = array();
			//get all agent ids from database
			$crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
			$sql_select            = "SELECT `crea_agent_id` FROM `$crea_agent_table_name`";
			$sql_prep              = $wpdb->prepare( $sql_select, null );
			$getAllAgentIds        = $wpdb->get_results( $sql_prep );
			$getAllAgentIdArray    = array();
			if ( isset( $getAllAgentIds ) && ! empty( $getAllAgentIds ) ) {
				foreach ( $getAllAgentIds as $agent_key => $agent ) {
					$getAllAgentIdArray[] = $agent->crea_agent_id;
				}
			}
			if ( isset( $getAllAgentIdArray ) && ! empty( $getAllAgentIdArray ) ) {
				$agent_ids = implode( ',', $getAllAgentIdArray );
			} else {
				$agent_ids = null;
			}
			$allListingArr = array();
			$userNameList  = '';
			if ( isset( $getAllUsername ) && ! empty( $getAllUsername ) ) {
				foreach ( $getAllUsername as $singleUsername ) {
					$userName = $singleUsername->username;
					if ( ! empty( $userName ) ) {
						$userNameList .= $userName . ',';
					}
					unset( $singleUsername );
				}
				unset( $getAllUsername );
				$userNameList = rtrim( $userNameList, ',' );
				$result_type  = 'full';
				if ( $agent_ids != null ) {
					$listings = Aretk_Crea_Admin::aretkcrea_get_listing_records_based_on_agents( $userNameList, $result_type, $agent_ids );
					if ( isset( $listings ) && ! empty( $listings ) ) {
						foreach ( $listings as $listing_key => $listing ) {
							if ( ! isset( $listing->TotalRecords ) && empty( $listing->TotalRecords ) ) {
								$allListingArr[ $listing->mlsID ] = $listing;
							}
						}
					}
				}
			}
			$args         = array(
				'posts_per_page' => - 1,
				'post_type'      => 'aretk_listing',
				'post_status'    => 'publish'
			);
			$posts_array  = (array) get_posts( $args );
			$exclusiveArr = array();
			foreach ( $posts_array as $singlePost ) {
				$singlePost1    = (array) $singlePost;
				$singlePost2    = (object) $singlePost1;
				$exclusiveArr[] = $singlePost2;
			}
			$allListingFinalArr = array();
			$allListingFinalArr = array_merge( $allListingArr, $exclusiveArr );
			$data               = json_encode( $allListingFinalArr );
			update_option( 'cron_run', "" );
			update_option( 'cron_run', "$data" );
			$crea_user_listing_detail_table_name = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
			$crea_agent_table_name               = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
			$sql_select                          = "SELECT `username`, `ddf_type` FROM `$crea_user_listing_detail_table_name`";
			$sql_prep                            = $wpdb->prepare( $sql_select, null );
			$resultUsernameSetArr                = $wpdb->get_results( $sql_prep );
			$firstUserName                       = isset( $resultUsernameSetArr[0]->username ) ? $resultUsernameSetArr[0]->username : '';
			$secondUserName                      = isset( $resultUsernameSetArr[1]->username ) ? $resultUsernameSetArr[1]->username : '';
			$thirdUserName                       = isset( $resultUsernameSetArr[2]->username ) ? $resultUsernameSetArr[2]->username : '';
			$fourthUserName                      = isset( $resultUsernameSetArr[3]->username ) ? $resultUsernameSetArr[3]->username : '';
			$fifthUserName                       = isset( $resultUsernameSetArr[4]->username ) ? $resultUsernameSetArr[4]->username : '';
			if ( $firstUserName != '' ) {
				$firstUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $firstUserName );
				update_option( 'firstUserNameresultSet', "" );
				update_option( 'firstUserNameresultSet', "$firstUserNameresultSet" );
			}
			if ( $secondUserName != '' ) {
				$secondUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $secondUserName );
				update_option( 'secondUserNameresultSet', "" );
				update_option( 'secondUserNameresultSet', "$secondUserNameresultSet" );
			}
			if ( $thirdUserName != '' ) {
				$thirdUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $thirdUserName );
				update_option( 'thirdUserNameresultSet', "" );
				update_option( 'thirdUserNameresultSet', "$thirdUserNameresultSet" );
			}
			if ( $fourthUserName != '' ) {
				$fourthUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $fourthUserName );
				update_option( 'fourthUserNameresultSet', "" );
				update_option( 'fourthUserNameresultSet', "$fourthUserNameresultSet" );
			}
			if ( $fifthUserName != '' ) {
				$fifthUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $fifthUserName );
				update_option( 'fifthUserNameresultSet', "" );
				update_option( 'fifthUserNameresultSet', "$fifthUserNameresultSet" );
			}
		} else {
			$args         = array(
				'posts_per_page' => - 1,
				'post_type'      => 'aretk_listing',
				'post_status'    => 'publish'
			);
			$posts_array  = (array) get_posts( $args );
			$exclusiveArr = array();
			foreach ( $posts_array as $singlePost ) {
				$singlePost1    = (array) $singlePost;
				$singlePost2    = (object) $singlePost1;
				$exclusiveArr[] = $singlePost2;
			}
			$allListingFinalArr = array();
			$allListingFinalArr = $exclusiveArr;
			$data               = json_encode( $allListingFinalArr );
			update_option( 'cron_run', "" );
			update_option( 'cron_run', "$data" );
		}
	}

	/**
	 * function for add class in body
	 *
	 *
	 */
	function aretk_body_classes( $classes ) {
		$classes[] = 'aretk';

		return $classes;

	}

	/**
	 * function to remove default canonical URLs from listing details pages
	 *
	 *
	 */
	function aretk_remove_listingdetails_default_canonical() {
		remove_action( 'wp_head', 'rel_canonical' );
		remove_action( 'wp_head', 'genesis_canonical', 5 );
		add_filter( 'wpseo_canonical', '__return_false' );  // remove YEOST canonical url
		add_filter( 'wds_process_canonical', '__return_false' );  // remove Infinite SEO canonical url
		remove_action( 'wp_head', 'rsd_link' ); //removes EditURI/RSD (Really Simple Discovery) link.
		remove_action( 'wp_head', 'wlwmanifest_link' ); //removes wlwmanifest (Windows Live Writer) link.
		remove_action( 'wp_head', 'wp_generator' ); //removes meta name generator.
		remove_action( 'wp_head', 'wp_shortlink_wp_head' ); //removes shortlink.
		remove_action( 'wp_head', 'feed_links', 2 ); //removes feed links.
		remove_action( 'wp_head', 'feed_links_extra', 3 );  //removes comments feed.
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' ); // Removes prev and next article links
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		//add_filter('the_title','aretk_custom_page_title');
	}

	/*
	 * Function to setup page meta title
    */

	/**
	 * function for reminder cron schudule every 15 minutes
	 * @package aretk crea
	 * @return  send reminder mail by date and minute
	 */
	function content_scheduler_reminder_minute_send_email() {
		global $wpdb;
		$TableName              = $wpdb->prefix . 'crea_lead_reminder_detail';
		$sql_select             = "SELECT * FROM `$TableName`";
		$sql_prep               = $wpdb->prepare( $sql_select, null );
		$reminderEveryMinReults = $wpdb->get_results( $sql_prep );
		$site_admin_email       = get_option( 'admin_email' );
		$time                   = time();
		$current_date           = date_i18n( 'Y-m-d H:i' );
		update_option( 'content_scheduler_reminder_minute_send_email', time() . $current_date );
		if ( $reminderEveryMinReults != '' && ! empty( $reminderEveryMinReults ) ) {
			foreach ( $reminderEveryMinReults as $reminderEveryMinValues ) {
				$reminder_id       = $reminderEveryMinValues->id;
				$reminder_email    = $reminderEveryMinValues->reminder_email;
				$reminder_subjects = $reminderEveryMinValues->reminder_subject;
				$reminder_name     = $reminderEveryMinValues->reminder_name;
				$reminder_content  = stripslashes( $reminderEveryMinValues->reminder_comment );
				$reminder_time     = $reminderEveryMinValues->reminder_time;
				$reminder_repeat   = $reminderEveryMinValues->reminder_repeat;
				if ( $reminder_email != '' && ! empty( $reminder_email ) ) {
					if ( $current_date >= $reminder_time ) {
						$messageStart = '';
						$messageStart .= '
						<div style="width:100%;">
							<div style="background-color: ' . ARETKCREA_MAIL_HEADER_COLOR . ';width: 99.8%;height: 100px;border-radius: 10px 10px 0 0;">
								<h1 style="color:#ffffff;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:100px;margin:0;text-align:center">' . ARETKCREA_REMINDER_MAIL_HEADER . '</h1>
							</div>
							<div style="background: ' . ARETKCREA_MAIL_CONTENT_COLOR . ';padding: 30px;">						
								<p style="margin: 0px;padding:0px;">' . $reminder_content . '</p>
							</div>
						</div><br>';
						$FinalMesage = $messageStart;
						$Mesaage     = '';
						$To          = $reminder_email;
						$Subject     = stripslashes( $reminder_subjects );
						$Mesaage .= $FinalMesage . '<br>';
						$headers = "From: " . get_bloginfo( 'name' ) . " <" . $site_admin_email . "> \r\n";
						$headers .= 'MIME-Version: 1.0' . "\n";
						$headers .= 'content-type: text/html; charset=utf-8' . "\r\n";
						wp_mail( $reminder_email, $Subject, $Mesaage, $headers );

						update_option( 'crea_reminder_mail_sucessfully', 'Reminder Mail sent sucessfully(' . $reminder_email . ')(' . $reminder_time . ')' );

						$future_date = null;
						if ( $reminder_repeat === 'no-repeat' ) {
							$sql_select    = "DELETE FROM `$TableName` WHERE `id`= %d LIMIT 1";
							$sql_prep      = $wpdb->prepare( $sql_select, $reminder_id );
							$delete_record = $wpdb->query( $sql_prep );
						} else if ( $reminder_repeat === 'daily' ) {
							$future_date = date( 'Y-m-d H:i:s', strtotime( $reminder_time . ' +1 day' ) );
						} else if ( $reminder_repeat === 'weekly' ) {
							$future_date = date( 'Y-m-d H:i:s', strtotime( $reminder_time . ' +1 week' ) );
						} else if ( $reminder_repeat === 'monthly' ) {
							$future_date = date( 'Y-m-d H:i:s', strtotime( $reminder_time . ' +1 month' ) );
						} else if ( $reminder_repeat === 'yearly' ) {
							$future_date = date( 'Y-m-d H:i:s', strtotime( $reminder_time . ' +1 year' ) );
						}
						if ( ! empty( $future_date ) ) {
							$wpdb->update( "$TableName",
								array(
									'reminder_time' => "$future_date",
									'updated_time'  => current_time( 'mysql', 1 )
								),
								array( 'id' => $reminder_id ),
								array( '%s', '%s' ),
								array( '%d' )
							);
						}
					}
				}
			}
		}
	}

	public function view_project_template( $template ) {
		return $template;
	}

	// hook add_query_vars function into query_vars

	public function my_theme_redirect() {
		if ( strpos( $_SERVER['REQUEST_URI'], 'listing-details' ) !== false ) {
			$serverArr = explode( '/', $_SERVER['REQUEST_URI'] );
			$id        = basename( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
			
			if ( ! is_numeric( $id ) ) {
				if ( $serverArr[1] == 'listing-details' && is_numeric( $serverArr[2] ) ) {
					wp_safe_redirect( site_url( '/listing-details/' . $serverArr[2] ) );
					exit;
				}
			}
		}
		if ( strpos( $_SERVER['REQUEST_URI'], 'listing-detail' ) !== false ) {
			$serverArr = explode( '/', $_SERVER['REQUEST_URI'] );
			$id        = basename( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
			if ( ! is_numeric( $id ) ) {
				if ( $serverArr[1] == 'listing-detail' && is_numeric( $serverArr[2] ) ) {
					wp_safe_redirect( site_url( '/listing-detail/' . $serverArr[2] ) );
					exit;
				}
			}
		}
	}

	/**
	 * Create Genaral Contact Form
	 *
	 * @return return html for the Display the Genaral Contact Form
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 * @author ARETK
	 *
	 * @param
	 */
	public function crea_genaral_contactform() {
		$url = ARETK_CREA_PLUGIN_URL . 'includes/captcha_code.php';
		$html = '<div class="contact_form_msg"></div>';
		$html .= '<form method="POST" class="aretk_contact_form">';
		$html .= '<table>';
		$html .= '<tr><td class="crea_contact_heading">' . __( ARETKCREA_CONTACT_FORM_NAME, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br />
		  <input id="user_name" type="text" name="user_name"></td></tr>
		  <tr><td class="crea_contact_heading">' . __( ARETKCREA_CONTACT_FORM_EMAIL, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br />
		  <input id="user_email" type="text" name="user_email"></td></tr>
		  <tr><td class="crea_contact_heading">' . __( ARETKCREA_CONTACT_FORM_PHONE, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br />
		  <input id="user_phone" type="text" name="user_phone" maxlength="10" crea_contact_heading></td></tr>		  		
		  <tr><td class="crea_contact_heading">' . __( ARETKCREA_CONTACT_FORM_MESSAGE, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br />
		  <textarea id="discription" name="discription" class="aretkcf-message" placeholder="' . __( ARETKCREA_CONTACT_FORM_MESSAGE_PLACEHOLDER, ARETKCREA_PLUGIN_SLUG ) . '" ></textarea></td></tr>
		  <tr><td class="crea_contact_heading">' . __( ARETKCREA_CONTACT_FORM_CAPTCHA, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br />
		  <img id="captcha_code" src=' . $url . '><br />
		  <input id="user_contact_captcha" type="text" name="user_captcha">
		  <div class="captcha_contact_validation_message"></div></td></tr>
		  <tr><td><input type ="submit" class="crea_submit" name="submit" value ="Submit"></td></tr>';
		$html .= '</table>';
		$html .= '</form>';

		return $html;
	}

	/**
	 * Create Buyer form with shortcode
	 *
	 * @return return html for display the buyer form
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 * @author ARETK
	 *
	 * @param
	 */
	public function crea_aretk_bfform() {
		$html = '<form method="POST" class="aretk_bfform">';
		$html .= '<div class="msg"></div>';
		$html .= '<table style="border:none;">';
		$html .= '
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_NAME, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br /><input id="bf_name" type="text" name="bf_name"></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_EMAIL, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br /><input id="bf_email" type="text" name="bf_email"></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_PHONE, ARETKCREA_PLUGIN_SLUG ) . '<br />
			<input id="bf_phone" maxlength="10" type="text" name="bf_phone"></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_PREFERRED_METHOD, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br />
			<input id="bf_phone_prefer" type="checkbox" name = "bf_Preferred[]"> Phone &nbsp;&nbsp;<input id="bf_email_prefer" type="checkbox" name = "bf_Preferred[]"> Email 
			<label for="bf_Preferred[]" generated="true" class="error" style="margin-left:10px;"></label></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_ADDRESS, ARETKCREA_PLUGIN_SLUG ) . '<br />
			<input id="bf_address" type="text" name="bf_Address"></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_DESCRIPTION, ARETKCREA_PLUGIN_SLUG ) . '<br />
			<input type="text" id="bf_description" name="bf_Description"></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_DESIRABLE_COMMUNITIES, ARETKCREA_PLUGIN_SLUG ) . '<br />
			<input type="text" id="bf_description_community" name="bf_description_community"></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_PRICE_RANGE, ARETKCREA_PLUGIN_SLUG ) . '<br />
			<input type="text" id="bf_price" name="bf_Price"></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_MINIMUM_BEDROOM, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br />
			<input type="text" id="bf_bedroom" name="bf_Bedroom"></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_MINIMUM_BATHROOM, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br />
			<input type="text" id="bf_bathroom" name="bf_Bathroom"></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_PLANNING, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br />
			<input type="text" id="bf_planning_to_buy" name="bf_planning_to_buy"></td></tr>
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_REALTOR, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span><br />
			<input type="radio" id="bf_realtor_yes" name="bf_realtor" value="Yes">Yes &nbsp;&nbsp;<input type="radio" id="bf_realtor_no" name="bf_realtor" value="No"> No
			<label for="bf_realtor" generated="true" class="error" style="margin-left:10px;"></label></td></tr>				
			<tr><td class="crea_bf_heading">' . __( ARETKCREA_BUYER_FORM_COMMENT, ARETKCREA_PLUGIN_SLUG ) . '<br />
			<textarea id="bf_discription" name="discription" class="aretkcf-message" placeholder="' . __( ARETKCREA_BUYER_FORM_COMMENT_PLACEHOLDER, ARETKCREA_PLUGIN_SLUG ) . '" ></textarea></td></tr>';
		$html .= '<tr><td><input type ="submit" class="crea_submit" name="submit" value ="Submit"><img id="imageloading" src="' . ARETK_CREA_PLUGIN_URL . 'admin/images/bx_loader.gif" alt="loading" height="25" width="25" /><div class="msg bfformbottom"></div></td></tr>';
		if ( ! empty( esc_attr( get_option( 'aretk_googleCaptchaKey_public' ) ) ) && ! empty( esc_attr( get_option( 'aretk_googleCaptchaKey_private' ) ) ) ) {
			$html .= '<tr><td class="crea_bf_heading"><div id="aretk_bfform_captcha" class="g-recaptcha" data-sitekey="' . esc_attr( get_option( 'aretk_googleCaptchaKey_public' ) ) . '" data-badge="inline" data-size="invisible"></div></td></tr>';
		}
		$html .= '</table>';
		$html .= '</form>';

		return $html;
	}

	/**
	 * Create Seller form Functinality
	 *
	 * @return return html for display the Seller  form
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 * @author ARETK
	 *
	 * @param
	 */
	public function crea_aretk_sfform() {
		$url  = ARETK_CREA_PLUGIN_URL . 'includes/captcha_code.php';
		$html = '<h3>' . __( ARETKCREA_SELLER_FORM_TITLE, ARETKCREA_PLUGIN_SLUG ) . '</h3>';
		$html .= '<h4>' . __( ARETKCREA_SELLER_FORM_TITLE_ONE, ARETKCREA_PLUGIN_SLUG ) . '</h4>';
		$html .= '<div class="sellermsg"></div>';
		$html .= '<form method="POST" class="aretk_sfform">';
		$html .= '<table>';
		$html .= '<tr><td><label for="sfform_name">' . __( ARETKCREA_SELLER_FORM_NAME, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span></label><input id="sfform_name" type="text" name="sfform_name"></td></tr>
			<tr><td><label for="sfform_email">' . __( ARETKCREA_SELLER_FORM_EMAIL, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span></label><input id="sfform_email" type="text" name="sfform_email"></td></tr>
			<tr><td><label for="sfform_phone">' . __( ARETKCREA_SELLER_FORM_PHONE, ARETKCREA_PLUGIN_SLUG ) . '</label><input id="sfform_phone"  maxlength="10" type="text" name="sfform_phone"></td></tr>
			<tr><td><label for="sf_preferred_phone">' . __( ARETKCREA_SELLER_FORM_PREFERRED_METHOD, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span></label><br /><input id="sf_preferred_phone" type="checkbox" name="sf_Preferred[]"> Phone &nbsp;&nbsp;&nbsp;<input id="sf_preferred_email" type="checkbox" name="sf_Preferred[]"> Email &nbsp;&nbsp;&nbsp;
			<label for="sf_Preferred[]" generated="true" class="error"></label></td></tr>
			<tr><td><label for="sfform_planning_to_buy">' . __( ARETKCREA_SELLER_FORM_PLANNING, ARETKCREA_PLUGIN_SLUG ) . '<span class="validate_sign">*</span></label><input id="sfform_planning_to_buy" type="text" name="sfform_planning_to_buy"></td></tr>
			<tr><td><label for="sf_realtor_yes">' . __( ARETKCREA_SELLER_FORM_REALTOR, ARETKCREA_PLUGIN_SLUG ) . '<span class="validate_sign">*</span></label><br /><input id="sf_realtor_yes" type="radio" name="sf_realtor" value="Yes"> Yes &nbsp;&nbsp;<input id="sf_realtor_no" type="radio" name="sf_realtor" value="No"> No
			&nbsp;&nbsp;&nbsp;<label for="sf_realtor" generated="true" class="error"></label></td></tr>
			<tr><td><label for="sfform_address">' . __( ARETKCREA_SELLER_FORM_ADDRESS, ARETKCREA_PLUGIN_SLUG ) . '</label><input id="sfform_address" type="text" name="sfform_address"></td></tr>
			<tr><td><label for="sfform_description">' . __( ARETKCREA_SELLER_FORM_DESCRIPTION, ARETKCREA_PLUGIN_SLUG ) . '</label><input id="sfform_description" type="text" name="sfform_description"></td></tr>
			<tr><td><label for="sfform_square_feet">' . __( ARETKCREA_SELLER_FORM_APPROXIMATE_SQUARE_FEET, ARETKCREA_PLUGIN_SLUG ) . '</label><input id="sfform_square_feet" type="text" name="sfform_square_feet"></td></tr>
			<tr><td><label for="sfform_bedroom">' . __( ARETKCREA_SELLER_FORM_BEDROOM, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span></label><input id="sfform_bedroom" type="text" name="sfform_bedroom"></td></tr>
			<tr><td><label for="sfform_bathroom">' . __( ARETKCREA_SELLER_FORM_BATHROOM, ARETKCREA_PLUGIN_SLUG ) . ' <span class="validate_sign">*</span></label><input id="sfform_bathroom" type="text" name="sfform_bathroom"></td></tr>
			<tr><td><label for="discription">' . __( ARETKCREA_SELLER_FORM_COMMENT, ARETKCREA_PLUGIN_SLUG ) . '</label><textarea id="discription" name="discription" class="aretkcf-message" placeholder="' . __( ARETKCREA_SELLER_FORM_COMMENT_PLACEHOLDER, ARETKCREA_PLUGIN_SLUG ) . '" ></textarea></tr>
			<tr><td><label for="user_seller_captcha">' . __( ARETKCREA_SELLER_FORM_CAPTCHA, ARETKCREA_PLUGIN_SLUG ) . '</label><br /><img id="captcha_seller_code" src=' . $url . '><input id="user_seller_captcha" type="text" name="user_seller_captcha"></td></tr>
			<tr><td><div class="captcha_seller_validation_message"></div></td></tr>			  
			<tr><td><input type ="submit" class="crea_submit" name="submit" value ="Submit"></td></tr>';
		$html .= '</table>';
		$html .= '</form>';

		return $html;
	}

	/**
	 * Function is responsible for shortcode [ARTEK-DLS], Default - Listings Showcase
	 */
	function create_artekdls_shortcode() {
		global $post, $wpdb;
		$allListingArr                                  = array();
		$allListingFinalArr                             = array();
		$filter_array                                   = array();
		$showcase_settings                              = array();
		$postmeta_arr                                   = array();
		$result_type                                    = 'basic';
		$subscriptionKey                                = Aretk_Crea_Public::aretkcrea_getsSubscriptionKey();
		$site_image_path                                = ARETK_CREA_PLUGIN_URL . 'public/images/preview_img.jpg';
		$showcase_settings['default_listing_image']     = $site_image_path;
		$getSubscriptionListing                         = get_option( 'crea_subscription_status', '' );
		$showcase_settings['aretk_subscription_status'] = get_option( 'crea_subscription_status', '' );
		$showcse_crea_display_theme_option              = 'Listing View';
		$showcase_settings['showcase_display_type']     = $showcse_crea_display_theme_option;
		$filter_array['showcase_view']                  = $showcse_crea_display_theme_option;
		$filter_array['include_exclusive']              = 'yes';
		$page_number_id                                 = (int) basename( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
		
		$showcase_id = '';
		
		if ( is_numeric( $page_number_id ) && $page_number_id !== 0 ) {
			$showcase_settings['current_page_number'] = $page_number_id;
		} else {
			$page_number_id                           = 1;
			$showcase_settings['current_page_number'] = 1;
		}
		$showcase_settings['display_items_per_page'] = 20;
		if ( is_numeric( $showcase_settings['current_page_number'] ) && empty( $_POST ) ) {
			$offset                              = ( $showcase_settings['current_page_number'] - 1 ) * $showcase_settings['display_items_per_page'] + 1;
			$filter_array['current_page_number'] = (int) $showcase_settings['current_page_number'];
		} else {
			$offset                              = 0;
			$filter_array['current_page_number'] = 1;
		}
		$filter_array['record_offset'] = $offset;
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
		$filter_array                                      = Aretk_Crea_Public::aretk_listing_filters( $filter_array, $postmeta_arr );
		$transient_id                                      = implode( '|', $filter_array );
		$transient_id                                      = 'aretk_' . md5( $transient_id );
		if ( !empty($_GET['cache']) && $_GET['cache'] === 'false' ) {
			delete_transient( $transient_id );
		}
		$listing_results = get_transient( $transient_id );
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
		$showcase_settings['display_searchbar']     = 'yes';
		$showcase_settings['display_searchbar_min'] = 'no';
		switch ( $showcse_crea_display_theme_option ) {
			case 'Listing View':
				$showcase_settings['display_openhouse_info'] = 'yes';
				$showcase_settings['display_listing_status'] = 'yes';
				$showcase_settings['status_color_bg']        = '#FF9898';
				$showcase_settings['status_color_txt']       = '#000';
				$showcase_settings['open_house_color_bg']    = '111';
				$showcase_settings['open_house_color_txt']   = 'fff';
				$showcase_settings['maintxt_color']          = '000';
				$showcase_settings['address_color']          = '000';
				$showcase_settings['price_color']            = '000';
				$showcase_settings['pagination_color_bg']    = '000';
				$showcase_settings['pagination_color_txt']   = 'fff';
				require_once plugin_dir_path( __FILE__ ) . '/templates/listings/desc-view/template.php';
				break;
			case 'Grid View':
				$showcase_settings['display_listing_status'] = 'yes';
				$showcase_settings['display_max_columns']    = 4;
				$grid_view_listing_class                     = 'grid-view-box pr aret-col-3';
				require_once plugin_dir_path( __FILE__ ) . '/templates/listings/grid-view/template.php';
				break;
			case 'Map':
				$map_center_lat           = ! empty( $settings_arr['mapfilterlatitude'] ) ? $settings_arr['mapfilterlatitude'] : '57.67807921815639';
				$map_center_long          = ! empty( $settings_arr['mapfilterlongitude'] ) ? $settings_arr['mapfilterlongitude'] : '-101.80516868749999';
				$map_zoom                 = ! empty( $settings_arr['showcasemapimagezoom'] ) ? $settings_arr['showcasemapimagezoom'] : '11';
				$map_height               = ! empty( $settings_arr['mapviewdisplayhight'] ) ? $settings_arr['mapviewdisplayhight'] : '600';
				$display_searchbar        = 'yes';
				$display_searchbar_closed = 'no';
				require_once plugin_dir_path( __FILE__ ) . '/templates/listings/map-view/template.php';
				break;
		}

		return $html;
	}

	/**
	 * Function is responsible for add shortcode for ARTEK-LDS
	 */
	function create_arteklds_shortcode( $atts ) {
		global $wpdb;
		global $property_details_arr;
		if ( $property_details_arr['is_exclusive'] === 'exclusive' && ! empty( $property_details_arr['ID'] ) ) {
			$aretk_listing_view_count = get_post_meta( $property_details_arr['ID'], 'crea_aretk_db_listing_page_count', true );
			if ( is_numeric( $aretk_listing_view_count ) && ! empty( $aretk_listing_view_count ) ) {
				$aretk_listing_view_count = $aretk_listing_view_count + 1;
			} else {
				$aretk_listing_view_count = 1;
			}
			update_post_meta( (int) $property_details_arr['ID'], 'crea_aretk_db_listing_page_count', (int) $aretk_listing_view_count );
		} else if ( $property_details_arr['aretk_subscription'] === 'valid' ) {
			Aretk_Crea_Public::update_view_count( $property_details_arr['ID'], 1 );
		}
		// Property Details from settings
		$property_detail_options = array(
			'include_contact_form' => get_option( 'crea_listing_include_contact_form' ),
			'include_map'          => get_option( 'crea_listing_include_map' ),
			'include_walk_score'   => get_option( 'crea_listing_include_walk_score' ),
			'include_print_btn'    => get_option( 'crea_listing_include_print_btn' ),
			'send_btn_color_txt'   => get_option( 'crea_listing_include_price_color' ),
			'send_btn_color_bg'    => get_option( 'crea_listing_include_send_btn_color' ),
			'include_agents_info'  => get_option( 'crea_listing_include_information' ),
			'include_agent_email'  => get_option( 'crea_listing_include_email_address_of_agent' ),
			'google-map-api-key'   => get_option( 'google-map-api-name' ),
			'walk-score-api-key'   => get_option( 'walk-score-api-name' ),
			'disclaimer'           => get_option( 'aretk_crea_disclaimer1' ),
		);

		# Set defaults
		if ( empty( $property_detail_options['include_contact_form'] ) ) {
			$property_detail_options['include_contact_form'] = 'Yes';
		}
		if ( empty( $property_detail_options['include_map'] ) ) {
			$property_detail_options['include_map'] = 'Yes';
		}
		if ( empty( $property_detail_options['include_walk_score'] ) ) {
			$property_detail_options['include_walk_score'] = 'Yes';
		}
		if ( empty( $property_detail_options['include_print_btn'] ) ) {
			$property_detail_options['include_print_btn'] = 'Yes';
		}
		if ( empty( $property_detail_options['include_agents_info'] ) ) {
			$property_detail_options['include_agents_info'] = 'Yes';
		}
		if ( empty( $property_detail_options['include_agent_email'] ) ) {
			$property_detail_options['include_agent_email'] = 'Yes';
		}
		if ( empty( $property_detail_options['send_btn_color_txt'] ) ) {
			$property_detail_options['send_btn_color_txt'] = 'ffffff';
		}
		if ( empty( $property_detail_options['send_btn_color_bg'] ) ) {
			$property_detail_options['send_btn_color_bg'] = '0001C8';
		}
		if ( empty( $property_detail_options['disclaimer'] ) ) {
			$property_detail_options['disclaimer'] = 'I am an agent licensed to trade residential and commercial real estate. The out of province listing content on this website is not intended to solicit a trade in real estate.  Any consumers interested in out of province listings must contact a person who is licensed to trade in real estate in that province.';
		}

		require_once plugin_dir_path( __FILE__ ) . '/templates/listing-details/t_1/template.php';
	}

	/**
	 * Set Canonical Url for custom template
	 *
	 */
	public function wp_head_custom() {
		global $wpdb;
		global $property_details_arr;
		if ( strpos( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), 'listing-detail' ) !== false || strpos( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), 'listing-details' ) !== false ) {
			if ( empty( $property_details_arr ) ) {
				$property_details_arr = Aretk_Crea_Public::get_property_meta();
			}
			echo '<link rel="canonical" href="' . $property_details_arr['url_canonical'] . '/" />
			<meta property="og:type" content="website" />
			<meta property="og:title" content="' . $property_details_arr['address_full'] . '" />
			<meta property="og:description" content="' . htmlentities($property_details_arr['PublicRemarks'], ENT_QUOTES) . '"/>
			<meta property="og:url" content="' . $property_details_arr['url_canonical'] . '/"/>';
			if ( ! empty( $property_details_arr['listing_photos'][0]['URL'] ) ) {
				echo "\n" . '<meta property="og:image" content="' . $property_details_arr['listing_photos'][0]['URL'] . '" />';
				list( $width, $height, $type, $attr ) = getimagesize( $property_details_arr['listing_photos'][0]['URL'] );
				if ( ! empty( $width ) && $width != '0' ) {
					echo "\n" . '<meta property="og:image:width" content="' . $width . '" />';
				}
				if ( ! empty( $height ) && $height != '0' ) {
					echo "\n" . '<meta property="og:image:height" content="' . $height . '" />';
				}
				echo "\n" . '<meta property="og:image:alt" content="Picture of ' . $property_details_arr['address_full'] . '" />';
			} else {
				echo '<meta property="og:image" content="' . ARETK_CREA_PLUGIN_URL . 'public/images/preview_img.jpg" />';
			}
			if ( ! empty( $property_details_arr['VideoLink'] ) ) {
				echo "\n" . '<meta property="og:video" content="' . $property_details_arr['VideoLink'] . '" />';
			}
			echo "\n";
		}
	}

	public function assignPageTitle( $title, $sep = '|' ) {
		global $wpdb;
		global $property_details_arr;
		$site_description = get_bloginfo( 'name', 'display' );
		if ( is_feed() ) {
			return $title;
		}
		if ( strpos( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), 'listing-detail' ) !== false || strpos( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), 'listing-details' ) !== false ) {
			Aretk_Crea_Public::aretk_remove_listingdetails_default_canonical();
			if ( empty( $property_details_arr ) ) {
				$property_details_arr = Aretk_Crea_Public::get_property_meta();
			}
			if ( ! empty( $property_details_arr['address_full'] ) ) {
				return $property_details_arr['address_full'] . ' ' . $sep . ' ' . $site_description;
			}
		}

		return $title;
	}

	function add_query_vars( $aVars ) {
		$aVars[] = "msds_pif_cat"; // represents the name of the product category as shown in the URL
		$aVars[] = "id"; // represents the name of the product category as shown in the URL
		
		return $aVars;
	}

	function aretkcrea_add_rewrite_rules( $aRules ) {
		$aNewRules = array( 'listing-details/(.?.+?)?(:/([0-9]+))?/?$' => 'index.php?pagename=listing-details&msds_pif_cat=$matches[1]&id=$matches[2]' );		
		$aRules = $aNewRules + $aRules;
		return $aRules;
	}

	/**
	 * Function is responsible for add shortcode for ARTEK-DSS
	 */
	function create_artekdss_shortcode() {
		$dss_shorctcode = true;
		require_once plugin_dir_path( __FILE__ ) . 'templates/listings-search/template.php';

		return $html;
	}

	function check_terms_and_condition_accept() {
		$time = time();
		if ( empty( $_COOKIE["aretk_crea_terms_of_use"] ) ) {
			setcookie( "aretk_crea_terms_of_use", 'true', time() + ( 3600 * 60 ), COOKIEPATH, COOKIE_DOMAIN );
		}
		die();
	}

	function check_terms_and_condition_decline() {
		wp_safe_redirect( site_url() );
		die();
	}

	/**
	 * Buyer Contact form
	 *
	 */
	public function buyer_lead_submit_form_front_end() {
		global $wpdb;
		$bf_name                  = ! empty( $_POST['bf_name'] ) ? sanitize_text_field( $_POST['bf_name'] ) : '';
		$bf_email                 = ! empty( $_POST['bf_email'] ) ? sanitize_email( $_POST['bf_email'] ) : '';
		$bf_phone                 = ! empty( $_POST['bf_phone'] ) ? (int) $_POST['bf_phone'] : '';
		$bf_phone_prefer          = ! empty( $_POST['bf_phone_prefer'] ) ? (int) $_POST['bf_phone_prefer'] : '';
		$bf_email_prefer          = ! empty( $_POST['bf_email_prefer'] ) ? (int) $_POST['bf_email_prefer'] : '';
		$bf_address               = ! empty( $_POST['bf_address'] ) ? sanitize_text_field( $_POST['bf_address'] ) : '';
		$bf_description           = ! empty( $_POST['bf_description'] ) ? sanitize_text_field( $_POST['bf_description'] ) : '';
		$bf_description_community = ! empty( $_POST['bf_description_community'] ) ? sanitize_text_field( $_POST['bf_description_community'] ) : '';
		$bf_price                 = ! empty( $_POST['bf_price'] ) ? sanitize_text_field( $_POST['bf_price'] ) : '';
		$bf_bedroom               = ! empty( $_POST['bf_bedroom'] ) ? sanitize_text_field( $_POST['bf_bedroom'] ) : '';
		$bf_bathroom              = ! empty( $_POST['bf_bathroom'] ) ? sanitize_text_field( $_POST['bf_bathroom'] ) : '';
		$bf_planning_to_buy       = ! empty( $_POST['bf_planning_to_buy'] ) ? sanitize_text_field( $_POST['bf_planning_to_buy'] ) : '';
		$bf_realtor               = ! empty( $_POST['bf_realtor'] ) ? $_POST['bf_realtor'] : '';
		if ( strtolower( $bf_realtor ) === 'yes' ) {
			$bf_realtor = 'Yes';
		} else {
			$bf_realtor = 'No';
		}
		$bf_discription       = ! empty( $_POST['bf_discription'] ) ? sanitize_textarea_field( $_POST['bf_discription'] ) : '';
		$user_bf_form_captcha = ! empty( $_POST['user_bf_form_captcha'] ) ? sanitize_text_field( $_POST['user_bf_form_captcha'] ) : '';
		$secret               = esc_attr( get_option( 'aretk_googleCaptchaKey_private' ) );
		$response             = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( $_POST['g-recaptcha-response'] ) : '';

		$requuired_error = array();
		if ( ! empty( $secret ) && ! empty( $response ) ) {
			$remoteip = $_SERVER["REMOTE_ADDR"];
			$url      = "https://www.google.com/recaptcha/api/siteverify";
			$curl     = curl_init();
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, array(
				'secret'   => $secret,
				'response' => $response,
				'remoteip' => $remoteip
			) );
			$curlData = curl_exec( $curl );
			curl_close( $curl );
			$recaptcha = json_decode( $curlData, true );
			if ( ! $recaptcha["success"] ) {
				$required_error[] = 'Form security failed';
			}
		}
		if ( empty( $bf_name ) ) {
			$requuired_error[] = 'Name field is Required';
		}
		if ( empty( $bf_email ) ) {
			$requuired_error[] = 'Email field is Required';
		}
		if ( empty( $bf_bedroom ) ) {
			$requuired_error[] = 'Bedroom field is Required';
		}
		if ( empty( $bf_bathroom ) ) {
			$requuired_error[] = 'Bathroom field is Required';
		}
		if ( empty( $bf_planning_to_buy ) ) {
			$requuired_error[] = 'Planning to buy field is Required';
		}
		if ( empty( $bf_realtor ) ) {
			$requuired_error[] = 'Working with a REALTOR&reg; field is Required';
		}

		if ( empty( $requuired_error ) ) {
			$data_submit_html = 'The following information has been submitted from the Buyers Form on your website:<br />';
			$data_submit_html .= '<table id="buyer_form_results">';
			$data_submit_html .= '<tr><td>Name:</td><td>' . $bf_name . '</td></tr>';
			$data_submit_html .= '<tr><td>Email:</td><td>' . $bf_email . '</td></tr>';
			$data_submit_html .= '<tr><td>Phone:</td><td>' . $bf_phone . '</td></tr>';
			$data_submit_html .= '<tr><td>Preferred method of contact:</td><td>';
			if ( ! empty( $bf_phone_prefer ) && ! empty( $bf_email_prefer ) ) {
				$data_submit_html .= 'Phone or Email';
			} else if ( ! empty( $bf_phone_prefer ) ) {
				$data_submit_html .= 'Phone';
			} else if ( ! empty( $bf_email_prefer ) ) {
				$data_submit_html .= 'Email';
			}
			$data_submit_html . '<td></td></tr>';
			$data_submit_html .= '<tr><td>Address:</td><td>' . $bf_address . '</td></tr>';
			$data_submit_html .= '<tr><td>Description of the desired home:</td><td>' . $bf_description . '</td></tr>';
			$data_submit_html .= '<tr><td>Price range:</td><td>' . $bf_price . '</td></tr>';
			$data_submit_html .= '<tr><td>Minimum number of bedrooms:</td><td>' . $bf_bedroom . '</td></tr>';
			$data_submit_html .= '<tr><td>Minimum number of bathrooms:</td><td>' . $bf_bathroom . '</td></tr>';
			$data_submit_html .= '<tr><td>How soon are you planning to buy:</td><td>' . $bf_planning_to_buy . '</td></tr>';
			$data_submit_html .= '<tr><td>working with a real estate agent:</td><td>' . $bf_realtor . '</td></tr>';
			$data_submit_html .= '<tr><td>Comments:</td><td>' . $bf_discription . '</td></tr>';
			$data_submit_html .= '</table>';
			$send_email_text = stripslashes( $data_submit_html );
			$send_email_text = str_replace( '\\', '\\\\', $send_email_text );
			$send_email_text = str_replace( '"', '\"', $send_email_text );
			$send_email_text = json_encode( $send_email_text );
			$send_email_text = str_replace( '\r\n', '', $send_email_text );
			$send_email_text = str_replace( '\n', '', $send_email_text );
			$send_email_text = json_decode( $send_email_text );
			$post_table      = $wpdb->prefix . 'posts';
			$post_meta_table = $wpdb->prefix . 'postmeta';

			$sql_select             = "SELECT * FROM `$post_meta_table` WHERE `meta_key`='lead_primary_email'";
			$sql_prep               = $wpdb->prepare( $sql_select, null );
			$getAgentidResultsarray = $wpdb->get_results( $sql_prep );

			$sql_select                = "SELECT * FROM `$post_meta_table` WHERE `meta_key`='lead_phone_email'";
			$sql_prep                  = $wpdb->prepare( $sql_select, null );
			$getAgentemailResultsarray = $wpdb->get_results( $sql_prep );

			$add_primary_email_array = array();
			foreach ( $getAgentidResultsarray as $getAgentidResultskey => $getAgentidResultsvalue ) {
				if ( $getAgentidResultsvalue->meta_value != '' ) {
					$add_primary_email_array [ $getAgentidResultsvalue->post_id ] = $getAgentidResultsvalue->meta_value;
				}
			}
			$add_non_primary_email_array = array();
			foreach ( $getAgentemailResultsarray as $getAgentemailResultkey => $getAgentemailResultsvalue ) {
				$implode_array     = maybe_unserialize( $getAgentemailResultsvalue->meta_value );
				$unserialize_array = maybe_unserialize( $implode_array );
				$lead_phone_email  = '';
				if ( is_array( $unserialize_array ) ) {
					$lead_phone_email = $unserialize_array[0];
				} else {
					$lead_phone_email = $unserialize_array;
				}
				$add_non_primary_email_array[ $getAgentemailResultsvalue->post_id ] = $lead_phone_email;
			}
			$merge_email_array_duplicate = $add_primary_email_array + $add_non_primary_email_array;
			$current_date                = date_i18n( 'Y-m-d H:i' );

			if ( ! empty( $merge_email_array_duplicate ) && $merge_email_array_duplicate != '' ) {

				foreach ( $merge_email_array_duplicate as $merge_email_array_key => $merge_email_array_value ) {
					if ( $merge_email_array_value == $bf_email ) {
						$new_corrsponding_key     = 'crea_lead_corrsponding_text' . rand( 100, 999 );
						$new_corrsponding_array   = array();
						$new_corrsponding_array[] = '';
						$new_corrsponding_array[] = $send_email_text;
						$new_corrsponding_array[] = $current_date;
						$new_corrsponding_array[] = 'Buyer Lead Mail';
						$new_corrsponding_array[] = 'buyer inquiry';

						$testvar = update_post_meta( $merge_email_array_key, $new_corrsponding_key, json_encode( $new_corrsponding_array ) );

						$update_post = array( 'ID' => $merge_email_array_key, 'post_type' => 'aretk_lead' );
						wp_update_post( $update_post );
					}
				}
			}

			if ( ! in_array( $bf_email, $merge_email_array_duplicate ) ) {
				$new_lead            = array(
					'post_title'   => $bf_name,
					'post_content' => 'Lead created from website Buyers form submission',
					'post_status'  => 'publish',
					'post_type'    => 'aretk_lead'
				);
				$import_lead_post_id = wp_insert_post( $new_lead );
				update_post_meta( $import_lead_post_id, 'lead_primary_email', $bf_email );
				update_post_meta( $import_lead_post_id, 'lead_phone_email', maybe_serialize( $bf_email ) );
				update_post_meta( $import_lead_post_id, 'lead_phone_no', maybe_serialize( $bf_phone ) );
				update_post_meta( $import_lead_post_id, 'bf_phone_prefer', $bf_phone_prefer );
				update_post_meta( $import_lead_post_id, 'bf_email_prefer', $bf_email_prefer );
				update_post_meta( $import_lead_post_id, 'lead_address_line', $bf_address );
				update_post_meta( $import_lead_post_id, 'bf_description', $bf_description );
				update_post_meta( $import_lead_post_id, 'bf_description_community', $bf_description_community );
				update_post_meta( $import_lead_post_id, 'bf_price', $bf_price );
				update_post_meta( $import_lead_post_id, 'bf_bedroom', $bf_bedroom );
				update_post_meta( $import_lead_post_id, 'bf_bathroom', $bf_bathroom );
				update_post_meta( $import_lead_post_id, 'bf_planning_to_buy', $bf_planning_to_buy );
				update_post_meta( $import_lead_post_id, 'bf_realtor', $bf_realtor );
				update_post_meta( $import_lead_post_id, 'lead_form_type', 'buyer' );

				$new_corrsponding_key     = 'crea_lead_corrsponding_text' . rand( 100, 999 );
				$new_corrsponding_array   = array();
				$new_corrsponding_array[] = '';
				$new_corrsponding_array[] = $send_email_text;
				$new_corrsponding_array[] = $current_date;
				$new_corrsponding_array[] = 'Buyer Lead Mail';    # message subject
				$new_corrsponding_array[] = 'buyer inquiry';
				update_post_meta( $import_lead_post_id, $new_corrsponding_key, json_encode( $new_corrsponding_array ) );
			}
			$admin_email_address = get_option( 'admin_email' );
			$to                  = $admin_email_address;
			$subject             = "Buyer Lead Mail";
			$txt                 = '
				<div style="width:100%;">
					<div style="background-color: ' . ARETKCREA_MAIL_HEADER_COLOR . ';width: 99.8%;height: 100px;border-radius: 10px 10px 0 0;">
						<h1 style="color:#ffffff;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:100px;margin:0;text-align:center">' . ARETKCREA_BUYER_FORM_MAIL_HEADER . '</h1>
					</div>
					<div style="background: ' . ARETKCREA_MAIL_CONTENT_COLOR . ';padding: 30px 0;">' . str_replace( '\"', '"', $send_email_text ) . '<p></p></div>
				</div>';
			$headers             = "From: " . get_bloginfo( 'name' ) . " <" . $bf_email . "> \r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			wp_mail( $to, $subject, $txt, $headers );
			echo '<div class="aretk_msg_success">' . __( ARETKCREA_BUYER_FORM_SUCESS ) . '</div>';
		} else {
			echo '<div class="aretk_msg_error">There was an error processing the form:<br />' . implode( ', ', $requuired_error ) . '</div>';
		}
		die();
	}

	/**
	 * Seller Contact Form
	 *
	 */
	public function seller_lead_submit_form_front_end() {
		global $wpdb;
		session_start();
		$sfform_name            = ! empty( $_POST['sfform_name'] ) ? sanitize_text_field( $_POST['sfform_name'] ) : '';
		$sfform_email           = ! empty( $_POST['sfform_email'] ) ? sanitize_email( $_POST['sfform_email'] ) : '';
		$sfform_phone           = ! empty( $_POST['sfform_phone'] ) ? (int) $_POST['sfform_phone'] : '';
		$sf_preferred_phone     = ! empty( $_POST['sf_preferred_phone'] ) ? (int) $_POST['sf_preferred_phone'] : '';
		$sf_preferred_email     = ! empty( $_POST['sf_preferred_email'] ) ? (int) $_POST['sf_preferred_email'] : '';
		$sfform_address         = ! empty( $_POST['sfform_address'] ) ? sanitize_text_field( $_POST['sfform_address'] ) : '';
		$sfform_description     = ! empty( $_POST['sfform_description'] ) ? sanitize_text_field( $_POST['sfform_description'] ) : '';
		$sfform_square_feet     = ! empty( $_POST['sfform_square_feet'] ) ? sanitize_text_field( $_POST['sfform_square_feet'] ) : '';
		$sfform_bedroom         = ! empty( $_POST['sfform_bedroom'] ) ? sanitize_text_field( $_POST['sfform_bedroom'] ) : '';
		$sfform_bathroom        = ! empty( $_POST['sfform_bathroom'] ) ? sanitize_text_field( $_POST['sfform_bathroom'] ) : '';
		$sfform_planning_to_buy = ! empty( $_POST['sfform_planning_to_buy'] ) ? sanitize_text_field( $_POST['sfform_planning_to_buy'] ) : '';
		$sf_realtor             = ! empty( $_POST['sf_realtor'] ) ? $_POST['sf_realtor'] : '';
		if ( strtolower( $sf_realtor ) === 'yes' ) {
			$sf_realtor = 'Yes';
		} else {
			$sf_realtor = 'No';
		}
		$discription         = ! empty( $_POST['discription'] ) ? sanitize_textarea_field( $_POST['discription'] ) : '';
		$user_seller_captcha = ! empty( $_POST['user_seller_captcha'] ) ? sanitize_text_field( $_POST['user_seller_captcha'] ) : '';

		$requuired_error = array();
		if ( empty( $sfform_name ) ) {
			$requuired_error[] = 'Name field is Required';
		}
		if ( empty( $sfform_email ) ) {
			$requuired_error[] = 'Email field is Required';
		}
		if ( empty( $sf_preferred_phone ) && empty( $sf_preferred_email ) ) {
			$requuired_error[] = 'Method of contact field is Required';
		}
		if ( empty( $sfform_planning_to_buy ) ) {
			$requuired_error[] = 'This field is required';
		}
		if ( empty( $sf_realtor ) ) {
			$requuired_error[] = 'Working with a REALTOR&reg; field is Required';
		}
		if ( empty( $user_seller_captcha ) ) {
			$requuired_error[] = 'Captcha field is Required';
		}

		if ( $_SESSION["captcha_code"] === $user_seller_captcha && empty( $requuired_error ) ) {

			$data_submit_html = 'The following information has been submitted from the Sellers Form on your website:<br />';
			$data_submit_html .= '<table id="sellers_form_results">';
			$data_submit_html .= '<tr><td>Name:</td><td>' . $sfform_name . '</td></tr>';
			$data_submit_html .= '<tr><td>Email:</td><td>' . $sfform_email . '</td></tr>';
			$data_submit_html .= '<tr><td>Phone:</td><td>' . $sfform_phone . '</td></tr>';
			$data_submit_html .= '<tr><td>Preferred method of contact:</td><td>';
			if ( ! empty( $sf_preferred_phone ) && ! empty( $sf_preferred_email ) ) {
				$data_submit_html .= 'Phone or Email';
			} else if ( ! empty( $sf_preferred_phone ) ) {
				$data_submit_html .= 'Phone';
			} else if ( ! empty( $sf_preferred_email ) ) {
				$data_submit_html .= 'Email';
			}
			$data_submit_html . '<td></td></tr>';
			$data_submit_html .= '<tr><td>Property Address:</td><td>' . $sfform_address . '</td></tr>';
			$data_submit_html .= '<tr><td>Description of your property:</td><td>' . $sfform_description . '</td></tr>';
			$data_submit_html .= '<tr><td>Approximate square feet:</td><td>' . $sfform_square_feet . '</td></tr>';
			$data_submit_html .= '<tr><td>Number of bedrooms:</td><td>' . $sfform_bedroom . '</td></tr>';
			$data_submit_html .= '<tr><td>Number of bathrooms:</td><td>' . $sfform_bathroom . '</td></tr>';
			$data_submit_html .= '<tr><td>How soon are you planning to sell:</td><td>' . $sfform_planning_to_buy . '</td></tr>';
			$data_submit_html .= '<tr><td>working with a real estate agent:</td><td>' . $sf_realtor . '</td></tr>';
			$data_submit_html .= '<tr><td>Addition information regarding property:</td><td>' . $discription . '</td></tr>';
			$data_submit_html .= '</table>';

			$send_email_text = stripslashes( $data_submit_html );
			$send_email_text = str_replace( '\\', '\\\\', $send_email_text );
			$send_email_text = str_replace( '"', '\"', $send_email_text );
			$send_email_text = json_encode( $send_email_text );
			$send_email_text = str_replace( '\r\n', '', $send_email_text );
			$send_email_text = json_decode( $send_email_text );

			$post_table             = $wpdb->prefix . 'posts';
			$post_meta_table        = $wpdb->prefix . 'postmeta';
			$sql_select             = "SELECT * FROM `$post_meta_table` WHERE `meta_key`='lead_primary_email'";
			$sql_prep               = $wpdb->prepare( $sql_select, null );
			$getAgentidResultsarray = $wpdb->get_results( $sql_prep );

			$sql_select                = "SELECT * FROM `$post_meta_table` WHERE `meta_key`='lead_phone_email'";
			$sql_prep                  = $wpdb->prepare( $sql_select, null );
			$getAgentemailResultsarray = $wpdb->get_results( $sql_prep );

			$add_primary_email_array = array();
			foreach ( $getAgentidResultsarray as $getAgentidResultskey => $getAgentidResultsvalue ) {
				if ( $getAgentidResultsvalue->meta_value != '' ) {
					$add_primary_email_array [ $getAgentidResultsvalue->post_id ] = $getAgentidResultsvalue->meta_value;
				}
			}
			$add_non_primary_email_array = array();
			foreach ( $getAgentemailResultsarray as $getAgentemailResultkey => $getAgentemailResultsvalue ) {
				$implode_array     = maybe_unserialize( $getAgentemailResultsvalue->meta_value );
				$unserialize_array = maybe_unserialize( $implode_array );
				$lead_phone_email  = '';
				if ( is_array( $unserialize_array ) ) {
					$lead_phone_email = $unserialize_array[0];
				} else {
					$lead_phone_email = $unserialize_array;
				}
				$add_non_primary_email_array[ $getAgentemailResultsvalue->post_id ] = $lead_phone_email;
			}
			$merge_email_array_duplicate = $add_primary_email_array + $add_non_primary_email_array;
			$current_date                = date_i18n( 'Y-m-d H:i' );
			if ( ! empty( $merge_email_array_duplicate ) && $merge_email_array_duplicate != '' ) {
				foreach ( $merge_email_array_duplicate as $merge_email_array_key => $merge_email_array_value ) {
					if ( $merge_email_array_value == $sfform_email ) {
						$new_corrsponding_key     = 'crea_lead_corrsponding_text' . rand( 100, 999 );
						$new_corrsponding_array   = array();
						$new_corrsponding_array[] = '';
						$new_corrsponding_array[] = $send_email_text;
						$new_corrsponding_array[] = $current_date;
						$new_corrsponding_array[] = 'Seller Lead Mail';    # message subject
						$new_corrsponding_array[] = 'seller inquiry';
						update_post_meta( $merge_email_array_key, $new_corrsponding_key, json_encode( $new_corrsponding_array ) );

						// Update modified date/time
						$update_post = array( 'ID' => $merge_email_array_key, 'post_type' => 'aretk_lead' );
						wp_update_post( $update_post );
					}
				}
			}
			if ( ! in_array( $sfform_email, $merge_email_array_duplicate ) ) {
				$new_lead            = array(
					'post_title'   => $sfform_name,
					'post_content' => 'Lead created from website, sellers form submission',
					'post_status'  => 'publish',
					'post_type'    => 'aretk_lead'
				);
				$import_lead_post_id = wp_insert_post( $new_lead );
				update_post_meta( $import_lead_post_id, 'lead_primary_email', $sfform_email );
				update_post_meta( $import_lead_post_id, 'lead_phone_email', maybe_serialize( $sfform_email ) );
				update_post_meta( $import_lead_post_id, 'lead_phone_no', maybe_serialize( $sfform_phone ) );
				update_post_meta( $import_lead_post_id, 'sf_preferred_phone', $sf_preferred_phone );
				update_post_meta( $import_lead_post_id, 'sf_preferred_email', $sf_preferred_email );
				update_post_meta( $import_lead_post_id, 'lead_address_line', $sfform_address );
				update_post_meta( $import_lead_post_id, 'sfform_description', $sfform_description );
				update_post_meta( $import_lead_post_id, 'sfform_square_feet', $sfform_square_feet );
				update_post_meta( $import_lead_post_id, 'sfform_bedroom', $sfform_bedroom );
				update_post_meta( $import_lead_post_id, 'sfform_bathroom', $sfform_bathroom );
				update_post_meta( $import_lead_post_id, 'sfform_planning_to_buy', $sfform_planning_to_buy );
				update_post_meta( $import_lead_post_id, 'sf_realtor', $sf_realtor );
				update_post_meta( $import_lead_post_id, 'lead_form_type', 'seller' );

				$new_corrsponding_key     = 'crea_lead_corrsponding_text' . rand( 100, 999 );
				$new_corrsponding_array   = array();
				$new_corrsponding_array[] = '';
				$new_corrsponding_array[] = $send_email_text;
				$new_corrsponding_array[] = $current_date;
				$new_corrsponding_array[] = 'Seller Lead Mail';    # message subject
				$new_corrsponding_array[] = 'seller inquiry';
				update_post_meta( $import_lead_post_id, $new_corrsponding_key, json_encode( $new_corrsponding_array ) );
			}
			$admin_email_address = get_option( 'admin_email' );
			$to                  = $admin_email_address;
			$subject             = "Seller Lead Mail";
			$txt                 = '
				<div style="width:100%;">
					<div style="background-color: ' . ARETKCREA_MAIL_HEADER_COLOR . ';width: 99.8%;height: 100px;border-radius: 10px 10px 0 0;">
						<h1 style="color:#ffffff;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:100px;margin:0;text-align:center">' . ARETKCREA_SELLER_FORM_MAIL_HEADER . '</h1>
					</div>
					<div style="background: ' . ARETKCREA_MAIL_CONTENT_COLOR . ';padding: 30px 0;">' . str_replace( '\"', '"', $send_email_text ) . '<p></p></div>
				</div>';
			$headers             = "From: " . get_bloginfo( 'name' ) . " <" . $sfform_email . "> \r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			wp_mail( $to, $subject, $txt, $headers );
			echo '<div style="float: left;width: 100%;border: 2px solid green;padding: 0;margin: 0;text-align: center;">' . __( ARETKCREA_BUYER_FORM_SUCESS ) . '</div>';
		} else {
			echo "false";
		}
		die();
	}

	/**
	 * General Contact Form
	 *
	 */
	public function conatact_submit_form_front_end() {
		global $wpdb;
		session_start();
		$user_name    = ! empty( $_POST['user_name'] ) ? sanitize_text_field( $_POST['user_name'] ) : '';
		$user_email   = ! empty( $_POST['user_email'] ) ? sanitize_email( $_POST['user_email'] ) : '';
		$user_phone   = ! empty( $_POST['user_phone'] ) ? (int) $_POST['user_phone'] : '';
		$discription  = ! empty( $_POST['discription'] ) ? sanitize_textarea_field( $_POST['discription'] ) : '';
		$user_captcha = ! empty( $_POST['user_captcha'] ) ? sanitize_text_field( $_POST['user_captcha'] ) : '';

		$requuired_error = array();
		if ( empty( $user_name ) ) {
			$requuired_error[] = 'Name field is Required';
		}
		if ( empty( $user_email ) ) {
			$requuired_error[] = 'Email field is Required';
		}
		if ( empty( $user_phone ) ) {
			$requuired_error[] = 'Phone field is Required';
		}
		if ( empty( $discription ) ) {
			$requuired_error[] = 'Message field is Required';
		}

		if ( $_SESSION["captcha_code"] === $user_captcha && empty( $requuired_error ) ) {
			$data_submit_html = 'The following information has been submitted from the Contact Form on your website:<br />';
			$data_submit_html .= '<table id="generalcontact_form_results">';
			$data_submit_html .= '<tr><td>Name:</td><td>' . $user_name . '</td></tr>';
			$data_submit_html .= '<tr><td>Email:</td><td>' . $user_email . '</td></tr>';
			$data_submit_html .= '<tr><td>Phone:</td><td>' . $user_phone . '</td></tr>';
			$data_submit_html .= '<tr><td>Message:</td><td>' . $discription . '</td></tr>';
			$data_submit_html .= '</table>';
			$send_email_text        = stripslashes( $data_submit_html );
			$send_email_text        = str_replace( '"', '\"', $send_email_text );
			$send_email_text        = json_encode( $send_email_text );
			$send_email_text        = str_replace( '\r\n', '', $send_email_text );
			$send_email_text        = json_decode( $send_email_text );
			$post_table             = $wpdb->prefix . 'posts';
			$post_meta_table        = $wpdb->prefix . 'postmeta';
			$sql_select             = "SELECT * FROM `$post_meta_table` WHERE `meta_key`='lead_primary_email'";
			$sql_prep               = $wpdb->prepare( $sql_select, null );
			$getAgentidResultsarray = $wpdb->get_results( $sql_prep );

			$sql_select                = "SELECT * FROM `$post_meta_table` WHERE `meta_key`='lead_phone_email'";
			$sql_prep                  = $wpdb->prepare( $sql_select, null );
			$getAgentemailResultsarray = $wpdb->get_results( $sql_prep );

			$add_primary_email_array = array();
			foreach ( $getAgentidResultsarray as $getAgentidResultskey => $getAgentidResultsvalue ) {
				if ( $getAgentidResultsvalue->meta_value != '' ) {
					$add_primary_email_array [ $getAgentidResultsvalue->post_id ] = $getAgentidResultsvalue->meta_value;
				}
			}
			$add_non_primary_email_array = array();
			foreach ( $getAgentemailResultsarray as $getAgentemailResultkey => $getAgentemailResultsvalue ) {
				$implode_array     = maybe_unserialize( $getAgentemailResultsvalue->meta_value );
				$unserialize_array = maybe_unserialize( $implode_array );
				$lead_phone_email  = '';
				if ( is_array( $unserialize_array ) ) {
					$lead_phone_email = $unserialize_array[0];
				} else {
					$lead_phone_email = $unserialize_array;
				}
				$add_non_primary_email_array[ $getAgentemailResultsvalue->post_id ] = $lead_phone_email;
			}
			$merge_email_array_duplicate = $add_primary_email_array + $add_non_primary_email_array;
			$current_date                = date_i18n( 'Y-m-d H:i' );
			if ( ! empty( $merge_email_array_duplicate ) && $merge_email_array_duplicate != '' ) {
				foreach ( $merge_email_array_duplicate as $merge_email_array_key => $merge_email_array_value ) {
					if ( $merge_email_array_value == $user_email ) {
						$new_corrsponding_key     = 'crea_lead_corrsponding_text' . rand( 100, 999 );
						$new_corrsponding_array   = array();
						$new_corrsponding_array[] = '';
						$new_corrsponding_array[] = $send_email_text;
						$new_corrsponding_array[] = $current_date;
						$new_corrsponding_array[] = 'Contact Lead Mail';
						$new_corrsponding_array[] = 'general inquiry';
						update_post_meta( $merge_email_array_key, $new_corrsponding_key, json_encode( $new_corrsponding_array ) );

						// Update modified date/time
						$update_post = array( 'ID' => $merge_email_array_key, 'post_type' => 'aretk_lead' );
						wp_update_post( $update_post );
					}
				}
			}
			if ( ! in_array( $user_email, $merge_email_array_duplicate ) ) {
				$new_lead            = array(
					'post_title'   => $user_name,
					'post_content' => 'Lead created from website Contact form submission',
					'post_status'  => 'publish',
					'post_type'    => 'aretk_lead'
				);
				$import_lead_post_id = wp_insert_post( $new_lead );
				update_post_meta( $import_lead_post_id, 'lead_primary_email', $user_email );
				update_post_meta( $import_lead_post_id, 'lead_phone_email', maybe_serialize( $user_email ) );
				update_post_meta( $import_lead_post_id, 'lead_phone_no', maybe_serialize( $user_phone ) );
				update_post_meta( $import_lead_post_id, 'user_subject', $user_subject );
				update_post_meta( $import_lead_post_id, 'lead_form_type', 'general' );

				$new_corrsponding_key     = 'crea_lead_corrsponding_text' . rand( 100, 999 );
				$new_corrsponding_array   = array();
				$new_corrsponding_array[] = '';
				$new_corrsponding_array[] = $send_email_text;
				$new_corrsponding_array[] = $current_date;
				$new_corrsponding_array[] = 'Contact Lead Mail';    # message subject
				$new_corrsponding_array[] = 'general inquiry';
				update_post_meta( $import_lead_post_id, $new_corrsponding_key, json_encode( $new_corrsponding_array ) );
			}

			$admin_email_address = get_option( 'admin_email' );
			$to                  = $admin_email_address;
			$subject             = "Contact Lead Mail";
			$txt                 = '
				<div style="width:100%;">
					<div style="background-color: ' . ARETKCREA_MAIL_HEADER_COLOR . ';width: 99.8%;height: 100px;border-radius: 10px 10px 0 0;">
						<h1 style="color:#ffffff;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:100px;margin:0;text-align:center">' . ARETKCREA_CONTACT_FORM_MAIL_HEADER . '</h1>
					</div>
					<div style="background: ' . ARETKCREA_MAIL_CONTENT_COLOR . ';padding: 30px 0;">' . str_replace( '\"', '"', $send_email_text ) . '</div>
				</div>';
			$headers             = "From: " . get_bloginfo( 'name' ) . " <" . $user_email . "> \r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			wp_mail( $to, $subject, $txt, $headers );

			echo '<div style="float: left;width: 100%;border: 2px solid green;padding: 0;margin: 0 0 100px 0;text-align: center;">' . __( ARETKCREA_BUYER_FORM_SUCESS ) . '</div>';
		} else {
			echo "false";
		}
		die();
	}

	/**
	 * Contact Form on Listing Details
	 *
	 */
	public function property_listing_contact_form() {
		global $wpdb;
		$agent_table             = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
		$required_error          = array();
		$agent_email_address_arr = array();
		$secret                  = esc_attr( get_option( 'aretk_googleCaptchaKey_private' ) );
		if ( ! empty( $secret ) ) {
			$remoteip = $_SERVER["REMOTE_ADDR"];
			$url      = "https://www.google.com/recaptcha/api/siteverify";
			$response = isset( $_POST['g-recaptcha-response'] ) ? $_POST['g-recaptcha-response'] : '';
			$curl     = curl_init();
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_POST, true );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, array(
				'secret'   => $secret,
				'response' => $response,
				'remoteip' => $remoteip
			) );
			$curlData = curl_exec( $curl );
			curl_close( $curl );
			$recaptcha = json_decode( $curlData, true );
			if ( ! $recaptcha["success"] ) {
				$required_error[] = 'Form security failed';
			}
		}
		$get_contact_first_agent_id    = isset( $_POST['get_contact_first_agent_id'] ) ? (int) $_POST['get_contact_first_agent_id'] : '';
		$get_contact_name              = isset( $_POST['get_contact_name'] ) ? sanitize_text_field( $_POST['get_contact_name'] ) : '';
		$get_contact_email             = isset( $_POST['get_contact_email'] ) ? sanitize_email( $_POST['get_contact_email'] ) : '';
		$get_contact_phone             = isset( $_POST['get_contact_phone'] ) ? (int) $_POST['get_contact_phone'] : '';
		$get_contact_message           = ! empty( $_POST['get_contact_message'] ) ? sanitize_textarea_field( $_POST['get_contact_message'] ) : '';
		$get_contact_page_url          = isset( $_POST['get_contact_page_url'] ) ? (INT) $_POST['get_contact_page_url'] : ''; # Listing ID
		$get_captcha_varification_code = isset( $_POST['captcha_varification_code'] ) ? sanitize_text_field( $_POST['captcha_varification_code'] ) : '';
		$get_listing_api_url           = isset( $_POST['listing_api_url'] ) ? esc_url_raw( $_POST['listing_api_url'] ) : '';    # Page URL
		$contact_us_agent_email        = ! empty( $_POST['contact_us_agent_email'] ) ? $_POST['contact_us_agent_email'] : '';
		if ( ! empty( $contact_us_agent_email ) ) {
			$contact_us_agent_email_resulst = explode( ",", $contact_us_agent_email );
		}    # this gets sanitized further down.
		if ( empty( $get_contact_name ) ) {
			$required_error[] = 'Name is required';
		}
		if ( empty( $get_contact_email ) ) {
			$required_error[] = 'Email is required';
		}
		if ( empty( $get_contact_message ) ) {
			$required_error[] = 'Message is Required';
		}
		if ( empty( $required_error ) ) {
			$data_submit_html = 'The following information has been submitted from a property inquiry form on your website:<br />';
			$data_submit_html .= '<table id="propertyinquiry_form_results">';
			$data_submit_html .= '<tr><td>Name:</td><td>' . $get_contact_name . '</td></tr>';
			$data_submit_html .= '<tr><td>Email:</td><td>' . $get_contact_email . '</td></tr>';
			$data_submit_html .= '<tr><td>Phone:</td><td>' . $get_contact_phone . '</td></tr>';
			$data_submit_html .= '<tr><td>Listing URL:</td><td>' . $get_listing_api_url . '</td></tr>';
			$data_submit_html .= '<tr><td>Message:</td><td>&nbsp;</td></tr>';
			$data_submit_html .= '<tr><td colspan="2">' . $get_contact_message . '</td></tr>';
			$data_submit_html .= '</table>';
			$send_email_text = stripslashes( $data_submit_html );
			$send_email_text = str_replace( '"', '\"', $send_email_text );
			$send_email_text = json_encode( $send_email_text );
			$send_email_text = str_replace( '\r\n', ' ', $send_email_text );
			$send_email_text = str_replace( '\n', ' ', $send_email_text );
			$send_email_text = json_decode( $send_email_text );
			foreach ( $contact_us_agent_email_resulst as $contact_us_agent_email_name ) {
				$contact_us_agent_email_name    = (int) $contact_us_agent_email_name;
				$sql_select                     = "SELECT `crea_agent_email` FROM `$agent_table` WHERE `crea_agent_id` = %d";
				$sql_prep                       = $wpdb->prepare( $sql_select, $contact_us_agent_email_name );
				$get_select_agents_mail_results = $wpdb->get_results( $sql_prep );
				foreach ( $get_select_agents_mail_results as $get_select_agents_mail_result ) {
					$agent_email_address_arr[] = $get_select_agents_mail_results[0]->crea_agent_email;
				}
			}
			if ( empty( $agent_email_address_arr ) ) {
				$crea_listingAgentId = get_post_meta( $get_contact_page_url, 'listingAgentId', true );
				$agents_id           = maybe_serialize( $crea_listingAgentId );
				$agents_name         = json_decode( $agents_id, true );
				if ( isset( $agents_name ) && ! empty( $agents_name ) ) {
					foreach ( $agents_name as $agents_name_key => $agents_name_value ) {
						$listing_agent_ids = (int) $agents_name_value;
						if ( isset( $listing_agent_ids ) && ! empty( $listing_agent_ids ) ) {
							$sql_select    = "SELECT `crea_agent_id`, `crea_agent_name`, `crea_agent_email` FROM `$agent_table` WHERE `crea_agent_id` = %d";
							$sql_prep      = $wpdb->prepare( $sql_select, $listing_agent_ids );
							$get_agent_ids = $wpdb->get_results( $sql_prep );
							foreach ( $get_agent_ids as $listing_get_agents_name ) {
								$agent_email_address_arr[] = $listing_get_agents_name->crea_agent_email;
							}
						}
					}
				}
			}
			$post_table                = $wpdb->prefix . 'posts';
			$post_meta_table           = $wpdb->prefix . 'postmeta';
			$sql_select                = "SELECT * FROM `$post_meta_table` WHERE `meta_key`='lead_primary_email'";
			$sql_prep                  = $wpdb->prepare( $sql_select, null );
			$getAgentidResultsarray    = $wpdb->get_results( $sql_prep );
			$sql_select                = "SELECT * FROM `$post_meta_table` WHERE `meta_key`='lead_phone_email'";
			$sql_prep                  = $wpdb->prepare( $sql_select, null );
			$getAgentemailResultsarray = $wpdb->get_results( $sql_prep );
			$add_primary_email_array   = array();
			foreach ( $getAgentidResultsarray as $getAgentidResultskey => $getAgentidResultsvalue ) {
				if ( $getAgentidResultsvalue->meta_value != '' ) {
					$add_primary_email_array [ $getAgentidResultsvalue->post_id ] = $getAgentidResultsvalue->meta_value;
				}
			}
			$add_non_primary_email_array = array();
			foreach ( $getAgentemailResultsarray as $getAgentemailResultkey => $getAgentemailResultsvalue ) {
				$implode_array     = maybe_unserialize( $getAgentemailResultsvalue->meta_value );
				$unserialize_array = maybe_unserialize( $implode_array );
				$lead_phone_email  = '';
				if ( is_array( $unserialize_array ) ) {
					$lead_phone_email = $unserialize_array[0];
				} else {
					$lead_phone_email = $unserialize_array;
				}
				$add_non_primary_email_array[ $getAgentemailResultsvalue->post_id ] = $lead_phone_email;
			}
			$merge_email_array_duplicate = $add_primary_email_array + $add_non_primary_email_array;
			$current_date                = date_i18n( 'Y-m-d H:i' );
			if ( ! empty( $merge_email_array_duplicate ) && $merge_email_array_duplicate != '' ) {
				foreach ( $merge_email_array_duplicate as $merge_email_array_key => $merge_email_array_value ) {
					if ( $merge_email_array_value == $get_contact_email ) {
						$new_corrsponding_key     = 'crea_lead_corrsponding_text' . rand( 100, 999 );
						$new_corrsponding_array   = array();
						$new_corrsponding_array[] = $get_contact_page_url;
						$new_corrsponding_array[] = $send_email_text;
						$new_corrsponding_array[] = $current_date;
						$new_corrsponding_array[] = 'Listing Lead Mail';# message subject
						$new_corrsponding_array[] = 'listing inquiry';    # message type
						update_post_meta( $merge_email_array_key, $new_corrsponding_key, json_encode( $new_corrsponding_array ) );
					}
				}
			}
			if ( ! in_array( $get_contact_email, $merge_email_array_duplicate ) ) {
				$new_lead            = array(
					'post_title'   => $get_contact_name,
					'post_content' => 'Lead created from Listing Inquiry form submission',
					'post_status'  => 'publish',
					'post_type'    => 'aretk_lead'
				);
				$import_lead_post_id = wp_insert_post( $new_lead );
				$current_date        = date_i18n( 'Y-m-d H:i' );
				update_post_meta( $import_lead_post_id, 'lead_primary_email', $get_contact_email );
				update_post_meta( $import_lead_post_id, 'lead_contact_date', $current_date );
				update_post_meta( $import_lead_post_id, 'lead_detail_page_link', $get_listing_api_url );
				update_post_meta( $import_lead_post_id, 'lead_phone_email', maybe_serialize( $get_contact_email ) );
				update_post_meta( $import_lead_post_id, 'lead_phone_no', maybe_serialize( $get_contact_phone ) );
				$new_corrsponding_key     = 'crea_lead_corrsponding_text' . rand( 100, 999 );
				$new_corrsponding_array   = array();
				$new_corrsponding_array[] = '';
				$new_corrsponding_array[] = $send_email_text;
				$new_corrsponding_array[] = $current_date;
				$new_corrsponding_array[] = 'Listing Lead Mail';# message subject
				$new_corrsponding_array[] = 'listing inquiry';    # message type
				update_post_meta( $import_lead_post_id, $new_corrsponding_key, json_encode( $new_corrsponding_array ) );
			}
			if ( empty( $agent_email_address_arr ) ) {
				$agent_email_address_arr[] = get_option( 'admin_email' );
			}
			if ( isset( $agent_email_address_arr ) && ! empty( $agent_email_address_arr ) ) {
				foreach ( $agent_email_address_arr as $agent_email_address_arr ) {
					$to      = $agent_email_address_arr;
					$subject = "Property Inquiry";
					$txt     = '
						<div style="width:100%;">
							<div style="background-color: ' . ARETKCREA_MAIL_HEADER_COLOR . ';width: 99.8%;height: 100px;border-radius: 10px 10px 0 0;">
								<h1 style="color:#ffffff;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:100px;margin:0;text-align:center">' . ARETKCREA_CONTACT_FORM_MAIL_HEADER . '</h1>
							</div>
							<div style="background: ' . ARETKCREA_MAIL_CONTENT_COLOR . ';padding: 30px 0;">' . str_replace( '\"', '"', $send_email_text ) . '</div>
						</div>';
					$headers = "From: " . get_bloginfo( 'name' ) . " <" . $get_contact_email . "> \r\n";
					$headers .= 'MIME-Version: 1.0' . "\n";
					$headers .= 'content-type: text/html; charset=utf-8' . "\r\n";
					wp_mail( $to, $subject, $txt, $headers );
				}
			}
			$results = array( 'status' => 'sucessfullyadded', 'errors' => null );
		} else {
			$results = array( 'status' => 'fail', 'errors' => implode( ", ", $required_error ) );
		}
		$results = json_encode( $results );
		echo $results;
		die();
	}

	/**
	 * Aretk Listing showcase
	 *
	 */
	public function aretk_showcase_listing( $atts ) {
		global $post, $wpdb;

		$showcase_id = (int) $atts['ls_id'];

		if ( is_numeric( $showcase_id ) ) {
			$allListingArr      = array();
			$allListingFinalArr = array();
			$filter_array       = array();
			$showcase_settings  = array();

			$result_type                                    = 'basic';
			$subscriptionKey                                = Aretk_Crea_Public::aretkcrea_getsSubscriptionKey();
			$site_image_path                                = ARETK_CREA_PLUGIN_URL . 'public/images/preview_img.jpg';
			$getSubscriptionListing                         = get_option( 'crea_subscription_status', '' );
			$showcase_settings['showcase_id']               = $showcase_id;
			$showcase_settings['default_listing_image']     = $site_image_path;
			$showcase_settings['aretk_subscription_status'] = $getSubscriptionListing;
			$page_number_id                                 = (int) basename( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );

			if ( is_numeric( $page_number_id ) && $page_number_id !== 0 ) {
				$showcase_settings['current_page_number'] = (int) $page_number_id;
			} else {
				$page_number_id                           = 1;
				$showcase_settings['current_page_number'] = 1;
			}
			$postmeta_arr               = get_post_meta( $showcase_id, '', false );
			$showcse_crea_feed_ddf_type = ! empty( $postmeta_arr['showcse_crea_feed_ddf_type'][0] ) ? $postmeta_arr['showcse_crea_feed_ddf_type'][0] : '';
			if ( $getSubscriptionListing === 'not-valid' ) {
				$filter_array['crea_feed_id'] = 'Exclusive Listing';
			} else {
				$filter_array['crea_feed_id'] = sanitize_text_field( $showcse_crea_feed_ddf_type );
			}
			$showcse_crea_feed_include_exclude = '';
			if ( isset( $postmeta_arr['showcse_crea_feed_include_exclude'][0] ) && $postmeta_arr['showcse_crea_feed_include_exclude'][0] === 'yes' ) {
				$showcse_crea_feed_include_exclude = 'yes';
				$filter_array['include_exclusive'] = 'yes';
			}

			$showcse_crea_display_theme_option          = ! empty( $postmeta_arr['showcse_crea_display_theams_option'][0] ) ? sanitize_text_field( $postmeta_arr['showcse_crea_display_theams_option'][0] ) : '';
			$showcase_settings['showcase_display_type'] = $showcse_crea_display_theme_option;
			$filter_array['showcase_view']              = $showcse_crea_display_theme_option;
			switch ( $showcse_crea_display_theme_option ) {
				case 'Listing View':
					require_once plugin_dir_path( __FILE__ ) . '/templates/listings/desc-view/settings_get.php';
					break;
				case 'Grid View':
					require_once plugin_dir_path( __FILE__ ) . '/templates/listings/grid-view/settings_get.php';
					break;
				case 'Carousel':
					require plugin_dir_path( __FILE__ ) . '/templates/listings/carousel-view/settings_get.php';
					break;
				case 'Map':
					require_once plugin_dir_path( __FILE__ ) . '/templates/listings/map-view/settings_get.php';
					break;
				case 'Slider':
					require_once plugin_dir_path( __FILE__ ) . '/templates/listings/slider-view/settings_get.php';
					break;
			}

			// If search form posted then the offset should be 0 regardless of what page the user was on when they submitted the form.
			
			$showcase_settings['display_items_per_page'] = ( isset($showcase_settings['display_items_per_page']) ) ? (int) $showcase_settings['display_items_per_page'] : '';
			
			if ( $showcse_crea_display_theme_option !== 'Map' && is_numeric( $showcase_settings['current_page_number'] ) && empty( $_POST ) ) {
				$offset                              = ( $showcase_settings['current_page_number'] - 1 ) * $showcase_settings['display_items_per_page'] + 1;
				$filter_array['current_page_number'] = (int) $showcase_settings['current_page_number'];
			} else {
				$offset                              = 0;
				$filter_array['current_page_number'] = 1;
			}
			$filter_array['record_offset'] = (int) $offset;
			$filter_array['record_limit']  = $showcase_settings['display_items_per_page'];
			$filter_array                  = Aretk_Crea_Public::aretk_listing_filters( $filter_array, $postmeta_arr );
			$transient_id                  = implode( '|', $filter_array );
			$transient_id                  = 'aretk_' . md5( $transient_id );
			if ( isset($_GET['cache']) && $_GET['cache'] === 'false' ) {
				delete_transient( $transient_id );
			}
			$listing_results = get_transient( $transient_id );
			if ( $showcse_crea_display_theme_option !== 'Map' ) {
				if ( false === $listing_results ) {
					if ( ! empty( $showcse_crea_feed_ddf_type ) && $getSubscriptionListing === 'valid' ) {
						$listing_results = Aretk_Crea_Public::aretk_get_listings_subsc( $subscriptionKey, $filter_array );
					} else {
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
			switch ( $showcse_crea_display_theme_option ) {
				case 'Listing View':
					require_once plugin_dir_path( __FILE__ ) . '/templates/listings/desc-view/template.php';
					break;
				case 'Grid View':
					require_once plugin_dir_path( __FILE__ ) . '/templates/listings/grid-view/template.php';
					break;
				case 'Carousel':
					require plugin_dir_path( __FILE__ ) . '/templates/listings/carousel-view/template.php';
					break;
				case 'Map':
					require_once plugin_dir_path( __FILE__ ) . '/templates/listings/map-view/template.php';
					break;
				case 'Slider':
					require_once plugin_dir_path( __FILE__ ) . '/templates/listings/slider-view/template.php';
					break;
			}
		} else {
			$html = 'Showcase id has not been specified';
		}
		return $html;
	}

	function aretk_get_listings_localwp( $showcase_id, $filter_array = null ) {
		global $wpdb;
		$searchArray        = array();
		$searh_posts_where  = array();
		$search_filter_vals = array();
		$propertyDataArr    = array();
		$SQL_where          = null;
		$SQL_joins          = null;
		$price_join_set     = false;
		$Table_posts        = $wpdb->prefix . 'posts';
		$Table_postmeta     = $wpdb->prefix . 'postmeta';
		$property_array     = array();
		switch ( $filter_array['listings_sortby'] ) {
			case 'price-desc':
				$sort_order_dir = "desc";
				$sort_orderby   = "meta_value_num";
				break;
			case 'price-asc':
				$sort_order_dir = "asc";
				$sort_orderby   = "meta_value_num";
				break;
			case 'new2old':
				$sort_order_dir = "desc";
				$sort_orderby   = "date";
				break;
			case 'old2new':
				$sort_order_dir = "asc";
				$sort_orderby   = "date";
				break;
			case 'rand':
				$sort_order_dir = "rand";
				$sort_orderby   = "rand";
				break;
			default:
				$sort_order_dir = "desc";
				$sort_orderby   = "meta_value_num";
				break;
		}
		if ( ! empty( $filter_array['property_ids'] ) && preg_match( '/^[0-9,]+$/', $filter_array['property_ids'] ) ) {
			$property_ids = explode( ',', $filter_array['property_ids'] );
			$bind_pids    = implode( ',', array_fill( 0, count( $property_ids ), '%d' ) );
			foreach ( $property_ids as $pid ) {
				$search_filter_vals[] = (int) $pid;
			}
			$SQL_where .= " AND `$Table_posts`.`ID` IN (" . $bind_pids . ")";

		}
		if ( ! empty( $filter_array['agent_ids'] ) && preg_match( '/^[0-9,]+$/', $filter_array['agent_ids'] ) ) {
			$agent_ids = explode( ',', $filter_array['agent_ids'] );
			$bind_aids = implode( ',', array_fill( 0, count( $agent_ids ), '%d' ) );
			foreach ( $agent_ids as $aid ) {
				$search_filter_vals[] = (int) $aid;
			}
			$SQL_joins .= " LEFT JOIN `$Table_postmeta` agent ON agent.`post_id` = `$Table_posts`.`ID` AND agent.`meta_key` = 'listingAgentId'";
			$SQL_where .= " AND agent.`meta_value` IN (" . $bind_pids . ")";

		}
		if ( ! empty( $filter_array['property_types'] ) ) {
			$SQL_joins .= " LEFT JOIN `$Table_postmeta` propertyType ON propertyType.`post_id` = `$Table_posts`.`ID` AND propertyType.`meta_key` = 'listingPropertyType'";
			$SQL_where .= " AND propertyType.`meta_value` LIKE %s";
			$search_filter_vals[] = $wpdb->esc_like( $filter_array['property_types'] );
			$searchArray[]        = array(
				'key'     => 'listingPropertyType',
				'value'   => $filter_array['property_types'],
				'compare' => 'LIKE'
			);
		}
		if ( ! empty( $filter_array['crea_advance_search_structure_type'] ) ) {
			$SQL_joins .= " LEFT JOIN `$Table_postmeta` structureType ON structureType.`post_id` = `$Table_posts`.`ID` AND structureType.`meta_key` = 'listingStructureType'";
			$SQL_where .= " AND structureType.`meta_value` LIKE %s";
			$search_filter_vals[] = $wpdb->esc_like( $filter_array['crea_advance_search_structure_type'] );
			$searchArray[]        = array(
				'key'     => 'listingStructureType',
				'value'   => $filter_array['crea_advance_search_structure_type'],
				'compare' => 'LIKE'
			);
		}
		if ( ! empty( $filter_array['transaction_type'] ) ) {
			$SQL_joins .= " LEFT JOIN `$Table_postmeta` status ON status.`post_id` = `$Table_posts`.`ID` AND status.`meta_key` = 'listingAgentStatus'";
			$SQL_where .= " AND status.`meta_value` LIKE %s";
			$search_filter_vals[] = $wpdb->esc_like( $filter_array['transaction_type'] );
			$searchArray[]        = array(
				'key'     => 'listingAgentStatus',
				'value'   => $filter_array['transaction_type'],
				'compare' => '='
			);
		}
		if ( ! empty( $filter_array['bedrooms'] ) ) {
			$SQL_joins .= " LEFT JOIN `$Table_postmeta` bedrooms ON bedrooms.`post_id` = `$Table_posts`.`ID` AND bedrooms.`meta_key` = 'listingBedRooms'";
			$SQL_where .= " AND CAST( bedrooms.`meta_value` AS SIGNED ) >= %d";
			$search_filter_vals[] = (int) $filter_array['bedrooms'];
			$searchArray[]        = array(
				'key'     => 'listingBedRooms',
				'value'   => $filter_array['bedrooms'],
				'compare' => '>=',
				'type'    => 'numeric'
			);
		}
		if ( ! empty( $filter_array['bathrooms'] ) ) {
			$SQL_joins .= " LEFT JOIN `$Table_postmeta` bathrooms ON bathrooms.`post_id` = `$Table_posts`.`ID` AND bathrooms.`meta_key` = 'listingBathrooms'";
			$SQL_where .= " AND CAST( bathrooms.`meta_value` AS SIGNED ) >= %d";
			$search_filter_vals[] = (int) $filter_array['bathrooms'];
			$searchArray[]        = array(
				'key'     => 'listingBathrooms',
				'value'   => $filter_array['bathrooms'],
				'compare' => '>=',
				'type'    => 'numeric'
			);
		}

		if ( isset($filter_array['min_amount']) && $filter_array['min_amount'] != '' ) {
			$price_join_set = true;
			$SQL_joins .= " LEFT JOIN `$Table_postmeta` price ON price.`post_id` = `$Table_posts`.`ID` AND price.`meta_key` = 'listingPrice'";
			$SQL_where .= " AND CAST( price.`meta_value` AS SIGNED ) >= %d";
			$search_filter_vals[] = (int) str_replace( '$', '', $filter_array['min_amount'] );
			$searchArray[]        = array(
				'key'     => 'listingPrice',
				'value'   => (int) str_replace( '$', '', $filter_array['min_amount'] ),
				'compare' => '>=',
				'type'    => 'numeric',
			);
		}
		if ( isset( $filter_array['max_amount'] ) && $filter_array['max_amount'] != '' ) {
			if ( true !== $price_join_set ) {
				$SQL_joins .= " LEFT JOIN `$Table_postmeta` price ON price.`post_id` = `$Table_posts`.`ID` AND price.`meta_key` = 'listingPrice'";
			}
			$SQL_where .= " AND CAST( price.`meta_value` AS SIGNED ) <= %d";
			$search_filter_vals[] = (int) str_replace( '$', '', $filter_array['max_amount'] );
			$searchArray[]        = array(
				'key'     => 'listingPrice',
				'value'   => (int) str_replace( '$', '', $filter_array['max_amount'] ),
				'compare' => '<=',
				'type'    => 'numeric',
			);
		}
		if ( ! empty( $filter_array['mapbound_lat_sw'] ) && ! empty( $filter_array['mapbound_lng_sw'] ) && ! empty( $filter_array['mapbound_lat_ne'] ) && ! empty( $filter_array['mapbound_lng_ne'] ) ) {
			$SQL_joins .= " LEFT JOIN `$Table_postmeta` latitude 
				ON latitude.`post_id` = `$Table_posts`.`ID` AND latitude.`meta_key` = 'crea_google_map_latitude'";
			$SQL_joins .= " LEFT JOIN `$Table_postmeta` longitude ON longitude.`post_id` = `$Table_posts`.`ID` AND longitude.`meta_key` = 'crea_google_map_longitude'";
			$SQL_where .= " AND ( 
				  ( CAST(latitude.`meta_value` AS DECIMAL(20,10)) BETWEEN %f AND %f ) AND 
				  ( CAST(longitude.`meta_value` AS DECIMAL(20,10)) BETWEEN %f AND %f ) 
				)";

			$search_filter_vals[] = floatval( $filter_array['mapbound_lat_sw'] );
			$search_filter_vals[] = floatval( $filter_array['mapbound_lat_ne'] );
			$search_filter_vals[] = floatval( $filter_array['mapbound_lng_sw'] );
			$search_filter_vals[] = floatval( $filter_array['mapbound_lng_ne'] );
		}
		if ( ! empty( $filter_array['keyword'] ) ) {
			$SQL_joins .= " 
				LEFT JOIN `$Table_postmeta` address ON address.`post_id` = `$Table_posts`.`ID` AND address.`meta_key` = 'listingAddress' 
				LEFT JOIN `$Table_postmeta` city ON city.`post_id` = `$Table_posts`.`ID` AND city.`meta_key` = 'listingcity' 
				LEFT JOIN `$Table_postmeta` mls ON mls.`post_id` = `$Table_posts`.`ID` AND mls.`meta_key` = 'listingMls'";
			$SQL_where .= " AND (
				`$post_table`.`post_content` LIKE %s
				OR address.`meta_value` LIKE %s 
				OR city.`meta_value` LIKE %s
				OR mls.`meta_value` LIKE %s
			)";
			$keyword_escapded     = $wpdb->esc_like( $filter_array['keyword'] );
			$search_filter_vals[] = '%' . $keyword_escapded . '%';
			$search_filter_vals[] = '%' . $keyword_escapded . '%';
			$search_filter_vals[] = '%' . $keyword_escapded . '%';
			$search_filter_vals[] = '%' . $keyword_escapded . '%';
			$searchArray[]        = array(
				'key'     => 'listingAddress',
				'value'   => $filter_array['keyword'],
				'compare' => 'LIKE'
			);
			$searchArray[]        = array(
				'key'     => 'listingMls',
				'value'   => $filter_array['keyword'],
				'compare' => 'LIKE'
			);
		}	

	 	$SQL = "SELECT `ID` FROM `$Table_posts` $SQL_joins 
				WHERE `$Table_posts`.`post_type`='aretk_listing' 
				AND `$Table_posts`.`post_status`='publish'
				$SQL_where 
				GROUP BY `$Table_posts`.`ID`";

		if ( empty($search_filter_vals) )
		{
			$results = $wpdb->get_results( $SQL );
		} else {
			$results = $wpdb->get_results( $wpdb->prepare( $SQL, $search_filter_vals ) );
		}
		foreach ( $results as $result ) {
			$propertyDataArr[] = $result->ID;
		}
		if ( ! empty( $propertyDataArr ) ) {

			if ( empty( $filter_array['record_limit'] ) ) {
				$filter_array['record_limit'] = 20;
			}
			if ( empty( $filter_array['record_limit'] ) ) {
				$filter_array['current_page_number'] = 1;
			}
			$args_property = array(
				'post_type'      => 'aretk_listing',
				'posts_per_page' => $filter_array['record_limit'],
				'paged'          => $filter_array['current_page_number'],
				'post_status'    => 'publish',
				'post__in'       => $propertyDataArr,
				'meta_key'       => 'listingPrice',
				'orderby'        => $sort_orderby,
				'order'          => $sort_order_dir,
			);

			if ( $filter_array['showcase_view'] === 'Map View' ) {
				# Set 'posts_per_page' => -1,
			}
			$property_array = (array) get_posts( $args_property );
		}
		$exclusiveArr = array();
		foreach ( $property_array as $singlePost ) {
			$singlePost1    = (array) $singlePost;
			$singlePost2    = (object) $singlePost1;
			$exclusiveArr[] = $singlePost2;
		}
		$allListingArr         = array();
		$allListingArr         = $exclusiveArr;
		$total_listing_records = count( $propertyDataArr );
		$listing_results       = array(
			'TotalRecords'    => $total_listing_records,
			'RecordsReturned' => count( $allListingArr ),
			'listing_data'    => $allListingArr
		);

		return $listing_results;
	}

	/**
	 * custom ajax call marker click property
	 *
	 */
	function custom_ajax_for_map_view_infobox( $result_type = 'basic' ) {
		global $wpdb;

		if ( is_numeric( $_POST['property_id'] ) && is_numeric( $_POST['scid'] ) ) {
			$property_id                   = (int) $_POST['property_id'];
			$showcase_id                   = (int) $_POST['scid'];
			$allListingArr                 = array();
			$showcase_meta_arr             = get_post_meta( $showcase_id, '', false );
			$subscriptionKey               = get_option( 'crea_subscription_key', '' );
			$SubscriptionStatus            = get_option( 'crea_subscription_status', '' );
			$crea_feed                     = ! empty( $showcase_meta_arr['showcse_crea_feed_ddf_type'][0] ) ? $showcase_meta_arr['showcse_crea_feed_ddf_type'][0] : '';
			$include_exclusives            = ! empty( $showcase_meta_arr['showcse_crea_feed_include_exclude'][0] ) ? $showcase_meta_arr['showcse_crea_feed_include_exclude'][0] : '';
			$filter_array['showcase_id']   = $showcase_id;
			$filter_array['showcase_view'] = 'Info Window';
			$filter_array['property_ids']  = $property_id;
			$filter_array['result_type']   = $result_type;
			$filter_array['crea_feed_id']  = $crea_feed;
			if ( $include_exclusives === 'yes' ) {
				$filter_array['include_exclusive'] = 'true';
			}
			$transient_id = 'aretk_infowindow_pid_' . $property_id;
			if ( $_GET['cache'] === 'false' ) {
				delete_transient( $transient_id );
			}
			$html = get_transient( $transient_id );
			if ( false === $html ) {
				if ( isset( $SubscriptionStatus ) && ! empty( $SubscriptionStatus ) && $SubscriptionStatus === 'valid' && ! empty( $crea_feed ) ) {
					$listing_results = Aretk_Crea_Public::aretk_get_listings_subsc( $subscriptionKey, $filter_array );
				} else {
					$listing_results = Aretk_Crea_Public::aretk_get_listings_localwp( $showcase_id, $filter_array );
				}
				$allListingArr = $listing_results['listing_data'];
				require_once plugin_dir_path( __FILE__ ) . '/templates/listings/map-infowindow/template.php';
				set_transient( $transient_id, $html, 60 * 60 );
			}
		} else {
			$html .= '<div class="propertyinfobox">Error.</div>';
		}
		echo $html;
		die();
	}

	/**
	 * Custom ajax call for map showcase
	 *
	 */
	function custom_ajax_for_map_view_dragend() {
		if ( isset( $_POST['scid'] ) && ! empty( $_POST['scid'] ) ) {
			$showcase_id = (int) $_POST['scid'];
		} else {
			die();
		}
		$showcase_id        = $_POST['scid'];
		$property_map_array = array();
		$propety_counter    = 0;

		if ( is_numeric( $showcase_id ) ) {
			$subscriptionKey    = get_option( 'crea_subscription_key', '' );
			$showcase_meta_arr  = get_post_meta( $showcase_id, '', false );
			$SubscriptionStatus = get_option( 'crea_subscription_status', '' );
			$filter_array       = Aretk_Crea_Public::aretk_listing_filters( $filter_array );

			if ( $showcase_meta_arr['showcse_crea_feed_include_exclude'][0] === 'yes' ) {
				$filter_array['include_exclusive'] = 'true';
			}
			$filter_array['crea_feed_id'] = ! empty( $showcase_meta_arr['showcse_crea_feed_ddf_type'][0] ) ? $showcase_meta_arr['showcse_crea_feed_ddf_type'][0] : null;
			$filter_array['result_type']  = 'mapmarkers';
			$transient_id                 = implode( '_', $filter_array );
			$transient_id                 = 'aretk_' . $transient_id;
			if ( $_GET['cache'] === 'false' ) {
				delete_transient( $transient_id );
			}
			$property_map_json = get_transient( $transient_id );
			if ( false === $property_map_json ) {
				if ( ! empty( $SubscriptionStatus ) && $SubscriptionStatus === 'valid' && ! empty( $filter_array['crea_feed_id'] ) ) {
					$filter_qry_str    = Aretk_Crea_Public::aretk_listings_filter_qry_str( $filter_array );
					$listing_results   = Aretk_Crea_Public::aretk_get_listings_subsc_json( $subscriptionKey, $filter_array );
					$property_map_json = $listing_results;
				} else {
					$listing_results = Aretk_Crea_Public::aretk_get_listings_localwp( $showcase_id, $filter_array );
					$allListingArr   = $listing_results['listing_data'];
					foreach ( $allListingArr as $singleListing ) {
						if ( isset( $singleListing->post_author ) && ! empty( $singleListing->post_author ) ) {
							$propety_lat  = get_post_meta( $singleListing->ID, 'crea_google_map_latitude', true );
							$propety_long = get_post_meta( $singleListing->ID, 'crea_google_map_longitude', true );
							if ( ! empty( $propety_lat ) && ! empty( $propety_long ) ) {
								if ( $propety_lat != '57.678079218156' && $propety_long != '-101.8051686875' ) {
									$property_map_array['property'][ $propety_counter ] = array(
										'property_id' => $singleListing->ID,
										'longitude'   => $propety_long,
										'latitude'    => $propety_lat
									);
									$propety_counter ++;
								}
							}
						}
					}
					$property_map_array['count'] = $propety_counter;
					$property_map_json           = json_encode( $property_map_array );
				}
				set_transient( $transient_id, $property_map_json, 60 * 60 );
			}
		}
		echo $property_map_json;
		die();
	}
}