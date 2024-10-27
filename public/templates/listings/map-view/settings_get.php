<?php
/*
 * Listings Map View Settings - GET
 *
 */				
	
#------------------------------------------- 
# Display Settings 

//Map section settings array
$settings_arr_serialized = get_post_meta($showcase_id,'showcse_crea_serializable_map_array',true);
$settings_arr_serialized = !empty( $settings_arr_serialized ) ? $settings_arr_serialized : '';
$settings_arr = maybe_unserialize($settings_arr_serialized);


$map_center_lat = !empty( $settings_arr['mapfilterlatitude'] ) ? $settings_arr['mapfilterlatitude'] : '57.67807921815639';
$map_center_long = !empty( $settings_arr['mapfilterlongitude'] ) ? $settings_arr['mapfilterlongitude'] : '-101.80516868749999';
$map_zoom = !empty( $settings_arr['showcasemapimagezoom'] ) ? $settings_arr['showcasemapimagezoom'] : '11';
$map_height = !empty( $settings_arr['mapviewdisplayhight'] ) ? $settings_arr['mapviewdisplayhight'] : '600';
$display_searchbar = !empty( $settings_arr['mapviewdisplaysearch'] ) ? $settings_arr['mapviewdisplaysearch'] : 'yes';
$display_searchbar_closed = !empty( $settings_arr['mapviewdisplaysearch_simple_or_detail'] ) ? $settings_arr['mapviewdisplaysearch_simple_or_detail'] : 'no';

$displaysearchbox = '';
if( $display_searchbar_closed === 'no' ) {  
	$displaysearchbox ='block';
}else{
	$displaysearchbox ='none';	
}

# END Display Settings
#-------------------------------------------

#-------------------------------------------
# Colour Settings 

$colors_arr_serialized =  get_post_meta($showcase_id,'Showcase_crea_map_view_color_array',true); 
$colors_arr_serialized = !empty( $colors_arr_serialized ) ? $colors_arr_serialized : '';
$colors_arr = maybe_unserialize($colors_arr_serialized);

$Showcase_crea_map_view_color_array =  (!empty($listing_id) ) ? get_post_meta($listing_id,'Showcase_crea_map_view_color_array',true) : ''; 
$Showcase_crea_map_view_color_array = !empty( $Showcase_crea_map_view_color_array ) ? $Showcase_crea_map_view_color_array : '';

# END Colour Settings 
#-------------------------------------------