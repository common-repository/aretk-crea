jQuery(document).ready(function () {
	jQuery('.owl-carousel').each(function(index, currentElement){		
		jQuery(this).addClass( 'aretkcarousel_'+index );		
		var carousel_loop = jQuery(this).parent().find('input[name=carousel_loop]').val();
		var carousel_dots = jQuery(this).parent().find('input[name=carousel_dots]').val();
		var carousel_prevnext = jQuery(this).parent().find('input[name=carousel_prevnext]').val();
		var carousel_speed = jQuery(this).parent().find('input[name=carousel_speed]').val();		
		carousel_loop = (carousel_loop == 'true');
		carousel_dots = (carousel_dots == 'true');
		carousel_prevnext = (carousel_prevnext == 'true');		
		jQuery('.aretkcarousel_'+index).owlCarousel({
			items: 4,
			dots: carousel_dots,
			nav: carousel_prevnext,
			loop: carousel_loop,
			autoplay: true,
			autoplayTimeout: carousel_speed,
			autoplayHoverPause: true,
			margin: 5,
			responsiveBaseElement: ".aretk-wrap.showcase_carousel",
			responsiveClass: true,
			responsive: {
				0: {
					items: 1,
					dots: false
				},
				500: {
					items: 2
				},
				750: {
					items: 3
				},
				1000: {
					items: 4
				},
				1500: {
					items: 5
				}
			}
		});
	}); 
});