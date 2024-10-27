jQuery(document).ready(function () {
    var bar, progressBar, bar, elem, isPause, tick, percentTime;
    var carousel_loop = jQuery('input[name=carousel_loop]').val();
    var carousel_pbar = jQuery('input[name=carousel_pbar]').val();
    var progressbar_color = jQuery('input[name=progressbar_color]').val();
    var display_nav = jQuery('input[name=display_nav]').val();
    var display_dots = jQuery('input[name=display_dots]').val();
    var slide_view_time = jQuery('input[name=slide_view_time]').val(); // time in seconds
    var owl = jQuery('.owl-carousel');
    if (display_nav == 'true') {
        display_nav = true;
    } else {
        display_nav = false;
    }
    if (display_dots == 'true') {
        display_dots = true;
    } else {
        display_dots = false;
    }
    owl.on('initialized.owl.carousel', function (e) {
        if (carousel_pbar === 'progressBar') {
            progressBar();
        }
    });
    owl.owlCarousel({
        items: 1,
        loop: carousel_loop,
        dots: display_dots,
        lazyLoad: true,
        autoHeight: true,
        nav: display_nav,
        responsiveClass: true,
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        smartSpeed: 450
    });
    owl.on('translate.owl.carousel', function (event) {
        isPause = true;
    });
    owl.on('drag.owl.carousel', function (event) {
        isPause = true;
        jQuery("#slider1 a").css({"cursor": "grabbing"});
    });
    owl.on('translated.owl.carousel', function (event) {
        clearTimeout(tick);
        start();
        jQuery("#slider1 a").css({"cursor": "pointer"});
    });
    function progressBar(elem) {
        buildProgressBar();
        start();
    };
    function progressBarNot(elem) {
    }

    function buildProgressBar() {
        progressBar = jQuery("<div>", {
            id: "progressBar"
        });
        bar = jQuery("<div>", {
            id: "bar"
        }).css({'background-color': '#' + progressbar_color});
        progressBar.append(bar).appendTo('#slider1');
    };
    function start() {
        percentTime = 0;
        isPause = false;
        tick = setInterval(interval, 10);
    };
    function interval() {
        if (isPause === false) {
            percentTime += 1 / slide_view_time;
            bar.css({width: percentTime + "%"});
            if (percentTime >= 100) {
                percentTime = 0;
                owl.trigger('next.owl.carousel');
            }
        }
    };
    owl.on('mouseover', function () {
        isPause = true;
    });
    owl.on('mouseout', function () {
        isPause = false;
    });
});