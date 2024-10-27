<?php
/**
 * Listings Template - Search
 *
 *
 */
wp_register_style( 'aretk-search-view-css', ARETK_CREA_PLUGIN_URL . 'public/templates/listings-search/styles.css', false, $this->version );
wp_enqueue_style( 'aretk-search-view-css' );
wp_register_script( 'jquery-ui-touch-punch-js', ARETK_CREA_PLUGIN_URL . 'public/js/jquery.ui.touch-punch.min.js', array( 'jquery' ), $this->version, true );
wp_enqueue_script( 'jquery-ui-touch-punch-js' );
wp_register_style( 'chosen-css', ARETK_CREA_PLUGIN_URL . 'admin/css/chosen.css', false, $this->version );
wp_enqueue_style( 'chosen-css' );
wp_register_script( 'chosen-js', ARETK_CREA_PLUGIN_URL . 'admin/js/chosen.jquery.js', array( 'jquery' ), $this->version, true );
wp_enqueue_script( 'chosen-js' );

require_once dirname( plugin_dir_path( __FILE__ ) ) . '/listings-search/settings-get.php';

$showcase_id = (!empty($showcase_id) && is_numeric($showcase_id)) ? (int) $showcase_id : '';

$html = '';
$html .= '<div class="aretk-wrap aretk_property_search_wrap">';
$html .= '<form method="GET" id="property_search"';
if ( isset($dss_shorctcode) && $dss_shorctcode === true ) {
	$html .= ' action="' . site_url() . '/search-results/"';
} else {
	$html .= ' action="' . get_permalink( (int) get_the_ID() ).'"';
}
$html .= '>';
$html .= '<div class="div_search_box">';
$html .= '<div class="aretklistings_search_r1">';
$html .= '<div class="aretk_col1">';
$html .= '<input type="text" value="' . $default_text_search_results_data . '" name="keyword" placeholder="MLS&reg;#, City, Address, Keyword" id="aretk_listing_keyword_search">';
$html .= '</div>';
$html .= '<div class="aretk_col2">';
$html .= '<input type="submit" class="button button-primary" value="SEARCH" style="background:#' . $crea_search_detail_button . '; color: #' . $crea_search_detail_title_color . ';" id="aretk_listing_searching_btn">';
$html .= '</div>';
$html .= '</div>';
/*if( 'no' === $aretkcrea_showcase_search_advancefilterclosed ) {*/
/*if( 'yes' === $aretkcrea_showcase_search_advancefilterclosed ) {*/
	$html .= '<div class="advance_exculed_searchbox">';
	$html .= '<div class="advance_search_title">';
	$html .= '<a href="#" class="advance_search_title_inline" style="color:#' . $crea_search_text_color . ';">' . __( ARETKCREA_SEARCH_NEW_ADVANCE_SEARCH_TITLE, ARETKCREA_PLUGIN_SLUG ) . '</a>';
	$html .= '</div>';
	$html .= '<div id="advance_search_content" class="advance_search_content advance_search_counter' . $select_result_layout_counter . '" style="display:';
	
	if ( !empty($displaysearchbox) && $displaysearchbox === 'block'){
		$html .= 'block;';
	} else if ( (!empty($showcase_settings['display_searchbar_min']) && $showcase_settings['display_searchbar_min'] === 'yes') || ( !empty($displaysearchbox) && $displaysearchbox === 'none' ) || ( !empty($aretkcrea_showcase_search_advancefilterclosed) && 'no' === $aretkcrea_showcase_search_advancefilterclosed && '' == $showcase_id ) ) {
		$html .= 'none;';
	} else {
		$html .= 'block;';
	}
	
	$html .= '">';
	if ( isset( $crea_search_exclude_field_status ) && $crea_search_exclude_field_status == '' ) {
		$html .= '<div class="aretk_listings_filter_wrap">';
		$html .= '<div class="aretk_listings_filter_con">';
		$html .= '<select id="aretk_listings_filter_status" name="property_status">';
		$html .= '<option value="" >Status</option>';
		$html .= '<option value="for sale"';
		if ( !empty($filter_array['transaction_type']) && strtolower( $filter_array['transaction_type'] ) === "for sale" ) {
			$html .= ' selected="selected"';
		}
		$html .= '>For Sale</option>';
		$html .= '<option value="for rent"';
		if ( !empty($filter_array['transaction_type']) && strtolower( $filter_array['transaction_type'] ) === "for rent" ) {
			$html .= ' selected="selected"';
		}
		$html .= '>For Rent</option>';
		$html .= '<option value="for lease"';
		if ( !empty($filter_array['transaction_type']) && strtolower( $filter_array['transaction_type'] ) === "for lease" ) {
			$html .= ' selected="selected"';
		}
		$html .= '>For Lease</option>';
		$html .= '<option value="Sold"';
		if ( !empty($filter_array['transaction_type']) && strtolower( $filter_array['transaction_type'] ) === "sold" ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Sold</option>';
		$html .= '</select>';
		$html .= '</div>';
		$html .= '</div>';
	}
	if ( isset( $crea_search_exclude_field_bedrooms ) && $crea_search_exclude_field_bedrooms == '' ) {
		$html .= '<div class="aretk_listings_filter_wrap">';
		$html .= '<div class="aretk_listings_filter_con">';
		$html .= '<select id="aretk_listings_filter_bedrooms" name="bedrooms">';
		$html .= '<option value="">Min Bedrooms</option>';
		for ( $bedrooms = 1; $bedrooms <= 10; $bedrooms ++ ) {
			$html .= '<option value="' . $bedrooms . '"';
			if ( ! empty( $bedrooms ) && ! empty( $filter_array['bedrooms'] ) && $bedrooms == $filter_array['bedrooms'] ) {
				$html .= ' selected="selected"';
			}
			$html .= '>' . $bedrooms . '</option>';
		}
		$html .= '</select>';
		$html .= '</div>';
		$html .= '</div>';
	}
	if ( isset( $crea_search_exclude_field_bathrooms_full ) && $crea_search_exclude_field_bathrooms_full == '' ) {
		$html .= '<div class="aretk_listings_filter_wrap">';
		$html .= '<div class="aretk_listings_filter_con">';
		$html .= '<select id="aretk_listings_filter_bathrooms" name="bathrooms">';
		$html .= '<option value="">Min Bathrooms</option>';
		for ( $bathroom = 1; $bathroom <= 10; $bathroom ++ ) {
			$html .= '<option value="' . $bathroom . '"';
			if ( ! empty( $filter_array['bathrooms'] ) && $bathroom == $filter_array['bathrooms'] ) {
				$html .= ' selected="selected"';
			}
			$html .= '>' . $bathroom . '</option>';
		}
		$html .= '</select>';
		$html .= '</div>';
		$html .= '</div>';
	}
	if ( isset( $crea_search_exclude_field_property_type ) && $crea_search_exclude_field_property_type == '' ) {
		$property_types_array = array();
		if ( ! empty( $filter_array['property_types'] ) && ! is_array( $filter_array['property_types'] ) ) {
			$property_types_array = explode( ',', $filter_array['property_types'] );
		} else if ( !empty($filter_array['property_types']) && is_array( $filter_array['property_types'] ) ) {
			$property_types_array = $filter_array['property_types'];
		}
		$html .= '<div class="aretk_listings_filter_wrap">';
		$html .= '<div class="aretk_listings_filter_con">';
		$html .= '<select data-placeholder="Property Type" id="aretk_listings_filter_property_type" name="property_types[]" multiple="true" class="chosen-select">';
		$html .= '<option value="agriculture"';
		if ( in_array( "agriculture", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Agriculture</option>';
		$html .= '<option value="business"';
		if ( in_array( "business", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Business</option>';
		$html .= '<option value="commercial"';
		if ( in_array( "commercial", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Commercial</option>';
		$html .= '<option value="hospitality"';
		if ( in_array( "hospitality", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Hospitality</option>';
		$html .= '<option value="industrial"';
		if ( in_array( "industrial", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Industrial</option>';
		$html .= '<option value="institutional"';
		if ( in_array( "institutional", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Institutional</option>';
		$html .= '<option value="multi-family"';
		if ( in_array( "multi-family", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Multi Family</option>';
		$html .= '<option value="office"';
		if ( in_array( "office", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Office</option>';
		$html .= '<option value="parking"';
		if ( in_array( "parking", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Parking</option>';
		$html .= '<option value="recreational"';
		if ( in_array( "recreational", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Recreational</option>';
		$html .= '<option value="residential"';
		if ( in_array( "residential", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Residential</option>';
		$html .= '<option value="retail"';
		if ( in_array( "retail", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Retail</option>';
		$html .= '<option value="single family"';
		if ( in_array( "single family", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>single family</option>';
		$html .= '<option value="vacant land"';
		if ( in_array( "vacant land", $property_types_array ) ) {
			$html .= ' selected="selected"';
		}
		$html .= '>Vacant Land</option>';
		$html .= '</select>';
		$html .= '</div>';
		$html .= '</div>';
	}
	
	// Ownership Type Filter
	if ( isset( $crea_search_exclude_field_ownership_type ) && ($crea_search_exclude_field_ownership_type === 'false' ||  ($crea_search_exclude_field_ownership_type !== 'Ownership Type' && $crea_search_exclude_field_ownership_type !== '') ) ) {
		$ownership_types_array = array();
		if ( ! empty( $filter_array['ownership_types'] ) && ! is_array( $filter_array['ownership_types'] ) ) {
			$ownership_types_array = explode( ',', $filter_array['ownership_types'] );
		} else if ( !empty($filter_array['ownership_types']) && is_array( $filter_array['ownership_types'] ) ) {
			$ownership_types_array = $filter_array['ownership_types'];
		}
		$html .= '<div class="aretk_listings_filter_wrap">';
		$html .= '<div class="aretk_listings_filter_con">';
		$html .= '<select data-placeholder="Ownership Type" id="aretk_listings_filter_ownership_type" name="ownership_types[]" multiple="true" class="chosen-select">';
		
		$html .= '<option value="condo"';
		if (in_array("condo", $ownership_types_array)){	$html .= ' selected="selected"'; }
		$html .= '>Condo</option>';
		
		$html .= '<option value="cooperative"';
		if (in_array("cooperative", $ownership_types_array)){$html .= ' selected="selected"'; }
		$html .= '>Cooperative</option>';
		
		$html .= '<option value="freehold"';
		if (in_array("freehold", $ownership_types_array)){	$html .= ' selected="selected"'; }
		$html .= '>Freehold</option>';
		
		$html .= '<option value="lease"';
		if ( in_array("lease", $ownership_types_array)){$html .= ' selected="selected"'; }
		$html .= '>Lease</option>';
		
		$html .= '<option value="strata"';
		if ( in_array("strata", $ownership_types_array)){$html .= ' selected="selected"'; }
		$html .= '>Strata</option>';
		
		$html .= '<option value="timeshare"';
		if ( in_array("timeshare", $ownership_types_array)){$html .= ' selected="selected"'; }
		$html .= '>Timeshare</option>';
		
		$html .= '<option value="other"';
		if ( in_array("other", $ownership_types_array)){$html .= ' selected="selected"'; }
		$html .= '>Other</option>';

		$html .= '</select>';
		$html .= '</div>';
		$html .= '</div>';
	}
	// End Ownership Type Filter
	$html .= '<div class="crea_searching_price_slider_main" style="color:#' . $crea_search_text_color . ';">';
	$html .= '<p class="searching_price_range">Price Range:</p><p id="display_amount_range"></p>';
	$html .= '<div id="searching-slider-range_wrap">';
	$html .= '<div id="searching-slider-range"></div>';
	$html .= '</div>';
	$html .= '<input type="hidden" value="' . $crea_default_search_max_range_price_slider_value . '" id="listings_search_max_price">';
	$html .= '<input type="hidden" id="listings_search_min_price" value="' . $crea_default_search_mini_range_price_slider_value . '">';
	$html .= '<input type="hidden" name="min_amount" id="min_amount"';
	if ( ! empty( $filter_array['min_amount'] ) ) {
		$html .= ' value="' . $filter_array['min_amount'] . '"';
	}
	$html .= '>';
	$html .= '<input type="hidden" name="max_amount" id="max_amount"';
	if ( ! empty( $filter_array['max_amount'] ) ) {
		$html .= ' value="' . $filter_array['max_amount'] . '"';
	}
	$html .= '>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '</div>'; // advance_exculed_searchbox end
/*}*/
$html .= '<input type="hidden" value="' . $showcase_id . '" id="showcaseCustomID">';
$html .= '</form>';
$html .= '</div>';
