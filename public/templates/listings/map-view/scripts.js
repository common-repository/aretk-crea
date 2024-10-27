(function( $ ) {
	var map;
	var scid;
	var oms;
	var markers = [];
	var listings_max = 29999;
	var markerClusterer;
	var plugin_path = jQuery('#aretk_plgn_path').val();
	var imageUrl = plugin_path + '/public/images/map_marker_home.png';	
	var markerImage = new google.maps.MarkerImage(imageUrl, new google.maps.Size(32, 37));
	var infoWindow = new google.maps.InfoWindow({content: "Loading..."}); 
	var include_exclusive = jQuery('#include_exclusive').val();
	var init_c = 0;
	var map_zoomLevel;
	var map_center;
	var map_bounds;
	
	jQuery('.se-pre-con').show();
	
	jQuery('.property_result_count').html('Loading map...');

	google.maps.event.addDomListener(window, 'load', initialize);

	jQuery( document ).ready(function() {
		jQuery("#property_search #aretk_listing_searching_btn").click(function(e) {
			get_property_markers();   
			return false;
		});
	});

	function initialize() {
		var center_lat = jQuery("#center_lat").val();
		var center_lon = jQuery("#center_lon").val();	
		var zoomLevel_int = jQuery("#map_zoom_level").val();		
		var latlng = new google.maps.LatLng(center_lat, center_lon);
		scid = jQuery("#scid").val();		
		var mapOptions = {
			zoom: parseInt(zoomLevel_int),
			scrollwheel: false,
			center: latlng,
			mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
		};

		map = new google.maps.Map(document.getElementById('gmap_canvas'), mapOptions);

		map_zoomLevel = map.getZoom();	
		map_center = map.getCenter();			
		google.maps.event.addListener(map, 'bounds_changed', function(ev) {
			get_property_markers();
			google.maps.event.clearListeners(map, 'bounds_changed');
		});

		oms = new OverlappingMarkerSpiderfier(map, {
		  markersWontMove: true,
		  markersWontHide: false,
		  basicFormatEvents: true,
		  keepSpiderfied: true
		});

		google.maps.event.addListener(map, 'dragend', function(ev){
			var map_bounds_new_ne = map.getBounds().getNorthEast();
			var map_bounds_new_sw = map.getBounds().getSouthWest();			
			var withinBounds_ne = map_bounds.contains(map_bounds_new_ne);
			var withinBounds_sw = map_bounds.contains(map_bounds_new_sw);
			if ( withinBounds_ne != true || withinBounds_sw != true ){
				get_property_markers();
			} else {
				var i_cnt = 0;
				for(var i = markers.length, bounds = map.getBounds(); i--;) {
					if( bounds.contains(markers[i].getPosition()) ){
						i_cnt++;
					}
				}
				jQuery('.property_result_count').html(i_cnt +' properties');
				markerClusterer.redraw();
			}	
		});			
		google.maps.event.addListener(map, 'zoom_changed', function(ev){
			setTimeout(function() {				
				var zoomLevel_new = map.getZoom();
				var mapCenter_new = map.getCenter();				
				
				if ( (map_zoomLevel > zoomLevel_new) || (map_zoomLevel == zoomLevel_new && mapCenter_new != map_center) ){
					map_center = mapCenter_new;
					get_property_markers();
				} else {
					var i_cnt = 0;
					for(var i = markers.length, bounds = map.getBounds(); i--;) {
						if( bounds.contains(markers[i].getPosition()) ){
							i_cnt++;
						}
					}
					jQuery('.property_result_count').html(i_cnt +' properties');	
					markerClusterer.redraw();
				}
			}, 1000);	
		});
	}	
	function get_property_markers( ev ) {
		map_bounds = map.getBounds();
		jQuery('.se-pre-con').show();
		jQuery('.property_result_count').html('Getting markers...');
		map_zoomLevel = map.getZoom();
		var mapbound_lat_sw = map.getBounds().getSouthWest().lat();
		var mapbound_lng_sw = map.getBounds().getSouthWest().lng();
		var mapbound_lat_ne = map.getBounds().getNorthEast().lat();
		var mapbound_lng_ne = map.getBounds().getNorthEast().lng();								
		var data_obj = { 
			action: 'custom_ajax_for_map_view_dragend', 
			scid: scid,
			mapbound_lat_sw : mapbound_lat_sw, 
			mapbound_lng_sw : mapbound_lng_sw, 
			mapbound_lat_ne : mapbound_lat_ne, 
			mapbound_lng_ne : mapbound_lng_ne  
		}
		if (jQuery('#aretk_listing_keyword_search').length){
			data_obj.keyword = jQuery("#aretk_listing_keyword_search").val();
		}
		if (jQuery('#crea_multiple_showcase_searching').length){
			data_obj.keywords = jQuery("#crea_multiple_showcase_searching").val();
		}
		if (jQuery('#aretk_listings_filter_property_type').length){
			data_obj.property_types = jQuery("#aretk_listings_filter_property_type").val();
		}
		if (jQuery('#crea_advance_search_structure_type').length){
			data_obj.structure_types = jQuery("#crea_advance_search_structure_type").val();
		}
		if (jQuery('#aretk_listings_filter_status').length){
			data_obj.property_status = jQuery("#aretk_listings_filter_status").val();
		}
		if (jQuery('#aretk_listings_filter_bedrooms').length){
			data_obj.bedrooms = jQuery("#aretk_listings_filter_bedrooms").val();
		}
		if (jQuery('#aretk_listings_filter_bathrooms').length){
			data_obj.bathrooms = jQuery("#aretk_listings_filter_bathrooms").val();
		}
		if (jQuery('#min_amount').length){
			data_obj.min_amount = jQuery("#min_amount").val();
		}
		if (jQuery('#max_amount').length){
			data_obj.max_amount = jQuery("#max_amount").val();
		}
		jQuery.ajax({ 
			type: "POST",
			dataType:'json',
			url:adminajaxjs.adminajaxjsurl,
			data: data_obj,
			success: function(data) {
				jQuery('.property_result_count').html('Loading markers...');
				if( data != '' ){ 
					var property_count = data.count;
				}else { 
					var property_count = 0;
				}	
				jQuery('.property_result_count').html(property_count +' properties found');
				if ( property_count >= 0 && init_c !== 0) {
					remove_markers();
				}
				if ( property_count < 1 ){
					jQuery('.se-pre-con').hide();
				} else if (property_count > 0 && property_count <= listings_max ){
					setMarkers(map, data, property_count);
				} else if (property_count >= listings_max){
					var maxnumber = listings_max + 1;
					setMarkers(map, data, property_count);
				}
			},
			error: function(objAJAXRequest, strError) {
				jQuery('.property_result_count').html('Error getting markers...');
				jQuery('.se-pre-con').hide();
			}
		});		
	}	
	function setMarkers(map, data, property_count){
		jQuery('.property_result_count').html('Placing '+ property_count +' markers...');
		init_c = 1;
		for (var i = 0; i < property_count; i++)  {
			var latLng = new google.maps.LatLng(data.property[i].latitude, data.property[i].longitude);				
			var marker = new google.maps.Marker({
				'position': latLng,
				'icon': markerImage,
				'pid': data.property[i].property_id,
				map: map
			});
			markers.push(marker);	
			
			//google.maps.event.addListener(marker, 'click', function (data) {
			//	markerClickFunction(this);
			//});
			
			// 'spider_click', not plain 'click'
			google.maps.event.addListener(marker, 'spider_click', function(e) {  
			  markerClickFunction_spider(this);
			  //iw.setContent(markerData.text);
			  //iw.open(map, marker);
			});
			oms.addMarker(marker);  // adds the marker to the spiderfier _and_ the map
		}
		jQuery('.property_result_count').html('Grouping markers...');
		markerClusterer = new MarkerClusterer(map, markers, {imagePath: plugin_path+'public/images/m', maxZoom: 16});
		minClusterZoom = 15;
		markerClusterer.setMaxZoom(minClusterZoom);
		
		google.maps.event.addListener(markerClusterer, 'clusterclick', function(cluster) {
			//alert(map.getZoom());
			//alert(minClusterZoom);
			//map.fitBounds(cluster.getBounds()); // Fit the bounds of the cluster clicked on
			// If zoomed in past minClusterZoom (first level without clustering), zoom out to minClusterZoom+1
			if( map.getZoom() > minClusterZoom+1 ) {
				map.setZoom(minClusterZoom+1);
			} 
		});
	
		if (property_count == 1){ map.panTo(marker.getPosition()); }				
		jQuery('.se-pre-con').hide();
		jQuery('.property_result_count').html(property_count +' properties');
	}	
 
	function remove_markers() {		
		for (var i = 0; i < markers.length; i++ ) { markers[i].setMap(null); }
		markerClusterer.clearMarkers();
		markers = [];
	}
	function markerClickFunction_spider( marker ) { 
		infoWindow.open(map, marker);	
		infoWindow.setContent('Loading...');		
		jQuery.ajax({
			type: "POST",
			url:adminajaxjs.adminajaxjsurl,
			data: ({
				action: 'custom_ajax_for_map_view_infobox',
				property_id:marker.pid,
				scid: scid,
				showcse_crea_feed_include_exclude:include_exclusive
			}),
			success: function (response) { 
				infoWindow.setContent(response);
				infoWindow.setPosition(marker.position);
				infoWindow.open(map, marker);				
			}
		});
	}
	
	function markerClickFunction( marker ) { 
		infoWindow.open(map, marker);	
		infoWindow.setContent('Loading...');		
		jQuery.ajax({
			type: "POST",
			url:adminajaxjs.adminajaxjsurl,
			data: ({
				action: 'custom_ajax_for_map_view_infobox',
				property_id:marker.pid,
				scid: scid,
				showcse_crea_feed_include_exclude:include_exclusive
			}),
			success: function (response) { 
				infoWindow.setContent(response);
				infoWindow.setPosition(marker.position);
				infoWindow.open(map, marker);				
			}
		});
	}
})( jQuery );