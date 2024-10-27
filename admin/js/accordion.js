jQuery(document).ready(function () {
    jQuery('.custom-accordian .aco-title').click(function () {
        jQuery(this).siblings().slideToggle(400);
        jQuery(this).toggleClass('open');
    });

    function close_accordion_section() {
        jQuery('.accordion .accordion-section-title').removeClass('active');
        jQuery('.accordion .accordion-section-content').slideUp(300).removeClass('open');
    }

    function close_accordion_preview_section() {
        jQuery('.accordion_preview .accordion_preview-section-title').removeClass('active');
        jQuery('.accordion_preview .accordion_preview-section-content').slideUp(300).removeClass('open');
    }

    //function for crea setting form
    function creae_setting_close_accordion_section() {
        jQuery('.accordion-crea-setting .accordion-crea-setting-sections-titles').removeClass('active');
        jQuery('.accordion-crea-setting .accordion-section-content').slideUp(300).removeClass('open');
    }

    jQuery('.accordion-section-title').click(function (e) {
        // Grab current anchor value
        var currentAttrValue = jQuery(this).attr('href');

        if (jQuery(e.target).is('.active')) {
            close_accordion_section();
        } else {
            close_accordion_section();

            // Add active class to section title
            jQuery(this).addClass('active');
            // Open up the hidden content panel
            jQuery('.accordion ' + currentAttrValue).slideDown(300).addClass('open');
        }
        e.preventDefault();
    });

    jQuery('.accordion_preview-section-title').click(function (e) {
        // Grab current anchor value
        var currentAttrValue = jQuery(this).attr('href');

        if (jQuery(e.target).is('.active')) {
            close_accordion_preview_section();
        } else {
            close_accordion_preview_section();

            // Add active class to section title
            jQuery(this).addClass('active');
            // Open up the hidden content panel
            jQuery('.accordion_preview ' + currentAttrValue).slideDown(300).addClass('open');
        }
        e.preventDefault();
    });

    //script for crea setting form
    jQuery('.accordion-crea-setting-sections-titles').click(function (e) {
        // Grab current anchor value
        var currentAttrValue = jQuery(this).attr('href');

        if (jQuery(e.target).is('.active')) {
            creae_setting_close_accordion_section();
        } else {
            creae_setting_close_accordion_section();

            // Add active class to section title
            jQuery(this).addClass('active');
            // Open up the hidden content panel
            jQuery('.accordion-crea-setting ' + currentAttrValue).slideDown(300).addClass('open');
        }
        e.preventDefault();
    });
});