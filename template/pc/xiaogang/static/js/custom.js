jQuery(document).ready(function (d) {
    var c = {
        body_duration: 1000,
        content_animation: true,
        animation_duration: 500,
        pagination: true,
        navigation: false,
        slide_speed: 500,
        auto_play: false,
        transition_style: false
    };
    d("body").queryLoader2({
        backgroundColor: "#222", completeAnimation: "fade", onLoadComplete: function () {
            d("#page").show().animate({opacity: 1,}, c.body_duration);
            a();
        }
    });
    var b = null;
    if (Modernizr != "undefined") {
        if (Modernizr.touch) {
            d("body").addClass("touch-device");
        }
    }
    (function () {
        d("#menu-trigger").on("click", function (f) {
            d(this).toggleClass("close");
            if (d("body").hasClass("show-menu")) {
                d("#menu-container").fadeOut(400, function () {
                    d("body").removeClass("show-menu");
                });
            } else {
                d("body").addClass("show-menu");
                d("#menu-container").fadeIn(600);
            }
            return false;
            f.stopPropagation();
            f.preventDefault;
        });
        d("#nav a, a.smooth-link").on("click", function (g) {
            var f = d(this).attr("href");
            if (f != undefined && f != "" && f != "#") {
                d("body").append('<div id="exit-layer">');
                d("#exit-layer").show().animate({opacity: 1,}, 500, function () {
                    window.location.href = f;
                });
            }
            g.stopPropagation();
            g.preventDefault();
        });
    })();
    function a() {
        d("#content").waypoint(function () {
            if (!d("body").hasClass("show-menu")) {
                d("#header").removeClass("show-bg");
            }
        });
        d(".intro").waypoint(function () {
            if (!d("body").hasClass("show-menu")) {
                d("#header").addClass("show-bg");
            }
        }, {
            offset: function () {
                return -d(this).outerHeight();
            }
        });
        if (c.content_animation) {
            d("#content .animated").waypoint(function () {
                if (d(this).hasClass("done")) {
                    return false;
                }
                var k = d(this).data("delay");
                if (k == undefined || k == "") {
                    k = 0;
                }
                d(this).css("visibility", "visible").transition({
                    opacity: 1, delay: k, y: 0, duration: c.animation_duration, easing: "ease", complete: function () {
                        d(this).addClass("done");
                    }
                });
            }, {offset: "80%"});
        } else {
            d("#content .animated").removeClass("animated");
        }
        if (d("#latest-tweets").length) {
            d("#latest-tweets").waypoint(function () {
                if (!d(this).hasClass("done")) {
                    d(this).addClass("done");
                    d.waypoints("refresh");
                }
            });
        }
        if (d("#resize-image").length) {
            d("#resize-image").find(".animated").addClass("active");
        }
        if (d("#intro-image").length) {
            d("#intro-image").find(".animated").addClass("active");
        }
        function g() {
            var k = d(this.$elem);
            if (i) {
                d(".owl-item:eq(" + this.owl.currentItem + ")", k).find(".slide-image").addClass("zoom");
            }
            d(".owl-item:eq(" + this.owl.currentItem + ")", k).find(".animated").addClass("active");
        }

        function f() {
            var l = d(this.$elem), k = d(".owl-item:eq(" + this.owl.prevItem + ")", l);
            if (i) {
                d(".owl-item:eq(" + this.owl.currentItem + ")", l).find(".image").addClass("zoom");
            }
            d(".owl-item:eq(" + this.owl.currentItem + ")", l).find(".animated").addClass("active");
            window.setTimeout(function () {
                if (i) {
                    k.find(".image").removeClass("zoom");
                }
                k.find(".animated").removeClass("active");
            }, 1000);
        }

        var j = function () {
            var l = d("#intro-slider .slide"), k = d(window).width(), n = d(window).height(), m = n;
            l.css({height: m + "px"});
        };
        j();
        d(window).on("resize", j);
        var i = false, e = d("#intro-slider");
        e.owlCarousel({
            navigation: c.navigation,
            pagination: c.pagination,
            slideSpeed: c.slide_speed,
            autoPlay: c.auto_play,
            transitionStyle: c.transition_style,
            navigationText: ["<i class='icon icon-arrow-left2'></i>", "<i class='icon icon-arrow-right2'></i>"],
            singleItem: true,
            afterMove: f,
            afterUpdate: g
        });
        if (d("#intro-slider").hasClass("zoom")) {
            i = true;
            d("#intro-slider").find(".owl-item:eq(0) .image").addClass("zoom");
        }
        d("#intro-slider").find(".owl-item:eq(0) .animated").addClass("active");
        if (d("#portfolio-carousel").length) {
            d("#portfolio-carousel").owlCarousel({
                items: 6,
                itemsCustom: false,
                slideSpeed: 200,
                paginationSpeed: 800,
                rewindSpeed: 1000,
                autoPlay: false,
                stopOnHover: false,
                navigation: false,
                navigationText: ["prev", "next"],
                rewindNav: true,
                scrollPerPage: false,
                pagination: false,
                responsive: true,
                responsiveRefreshRate: 200,
                responsiveBaseWidth: window
            });
        }
        d("#portfolio-filter a").on("click", function (l) {
            var k = d(this).data("filterby");
            if (k == undefined || k == "") {
                return false;
            }
            d("#portfolio-filter a").removeClass("active");
            d(this).addClass("active");
            if (k == "*") {
                d("#portfolio-carousel article").find(".overlay").fadeOut(400);
                return false;
            }
            d('#portfolio-carousel article:not([data-filter*="' + k + '"])').find(".overlay").fadeIn(400);
            d('#portfolio-carousel article[data-filter*="' + k + '"]').find(".overlay").fadeOut(400);
            l.stopPropagation();
            l.preventDefault();
        });
        d("#portfolio-filter").addClass("animated");
        d("#portfolio-carousel .portfolio-item").each(function (k, l) {
            setTimeout(function () {
                d(l).addClass("animated");
            }, 100 + (k * 100));
        });
        if (d.fn.ResVid) {
            d("body").ResVid();
        }
        if (d.fn.gMap) {
            var h = [{
                featureType: "water",
                stylers: [{hue: "#222222"}, {saturation: -35}, {color: "#090909"}]
            }, {
                featureType: "administrative",
                elementType: "geometry",
                stylers: [{saturation: -100}, {visibility: "off"}]
            }, {stylers: [{visibility: "on"}, {gamma: 1.33}]}, {
                featureType: "poi",
                stylers: [{visibility: "simplified"}]
            }, {}, {
                featureType: "road",
                stylers: [{visibility: "simplified"}, {saturation: -33}, {color: "#666666"}]
            }, {}, {
                featureType: "road",
                elementType: "labels.text.fill",
                stylers: [{visibility: "on"}, {saturation: -9}, {color: "#999999"}]
            }, {
                featureType: "administrative.neighborhood",
                stylers: [{lightness: 30}, {visibility: "on"}, {saturation: -100}]
            }, {featureType: "administrative.locality", stylers: [{visibility: "off"}]}, {
                featureType: "poi",
                stylers: [{color: "#cccccc"}]
            }, {}, {
                featureType: "administrative.province",
                stylers: [{visibility: "off"}]
            }, {featureType: "transit.line", stylers: [{color: "#808080"}, {lightness: -61}]}, {
                featureType: "transit",
                elementType: "labels.text",
                stylers: [{visibility: "off"}]
            }, {
                featureType: "transit.station",
                elementType: "labels.icon",
                stylers: [{visibility: "simplified"}, {saturation: -100}, {lightness: 32}]
            }, {}, {
                featureType: "road",
                stylers: [{weight: 1.6}, {color: "#dddddd"}]
            }, {featureType: "landscape.man_made", stylers: [{color: "#ffffff"}]}, {}, {
                featureType: "water",
                elementType: "labels",
                stylers: [{visibility: "off"}]
            }, {
                featureType: "road",
                elementType: "labels.text.fill",
                stylers: [{color: "#8d8c8c"}]
            }, {featureType: "road.highway", stylers: [{visibility: "off"}]}];
            d("#gmap").gMap({
                address: "Level 13, 2 Elizabeth St, Melbourne Victoria 3000 Australia",
                zoom: 16,
                zoomControl: false,
                scrollwheel: false,
                styles: h,
                markers: [{address: "Level 13, 2 Elizabeth St, Melbourne Victoria 3000 Australia"}],
                icon: {image: "img/map-marker.png", iconsize: [48, 56], iconanchor: [20, 56]}
            });
        }
    }

    (function () {
        if (d("#resize-image").length == 0) {
            return false;
        }
        var e = function () {
            var g = d("#resize-image, #resize-image .image"), f = d(window).width(), i = d(window).height(), h = i;
            g.css({height: h + "px"});
        };
        e();
        d(window).on("resize", e);
    })();
    (function () {
        function e(i, h, f, g) {
            return '<div id="fancybox-title">' + (i && i.length ? i : "") + "<span>(" + (f + 1) + " / " + h.length + ")</span></div>";
        }

        d(".imagebox").fancybox({overlayOpacity: 0.9, overlayColor: "#000", padding: 0, titleFormat: e});
        d(".mediabox").fancybox({
            type: "iframe",
            centerOnScroll: true,
            autoScale: true,
            overlayOpacity: 0.9,
            padding: 0,
            overlayColor: "#000",
            titleFormat: e,
            onStart: function (g) {
                var f = d(g);
                if (f.data("width") != "auto") {
                    this.width = f.data("width");
                }
                if (f.data("height") != "auto") {
                    this.height = f.data("height");
                }
            }
        });
    })();
    (function () {
        d(".slider").each(function () {
            var i = d(this).attr("id"), f = d(this).data("effect"), h = d(this).data("nav"), g = d(this).data("autoplay"), e = d(this).data("pagination");
            if (i == undefined) {
                return;
            }
            d("#" + i).owlCarousel({
                navigation: h,
                pagination: e,
                navigationText: ["<i class='icon icon-arrow-left2'></i>", "<i class='icon icon-arrow-right2'></i>"],
                singleItem: true,
                autoPlay: g,
                slideSpeed: 400,
                paginationSpeed: 800,
                rewindSpeed: 1000,
                transitionStyle: f
            });
        });
    })();
    (function () {
        if (d(".tweet").length == 0) {
            return false;
        }
        var e = "plugins/tweets.php";
        d.ajax({
            url: e, dataType: "html", timeout: 10000, type: "GET", error: function (h, f, g) {
                d(".tweet").html("An error occured: " + g);
            }, success: function (h, f, i) {
                var g = "<ul>" + h + "</ul>", g = d(g);
                d(".tweet").each(function () {
                    var k = d(this), j = k.data("tweet");
                    if (j == undefined || j == "") {
                        j = 0;
                    }
                    var l = g.find("li:eq(" + j + ")").html();
                    k.html(l);
                });
            }
        });
    })();

    (function () {
        d("body").on("click", ".smooth-scroll", function (g) {
            var f = d(this).data("offset"), h = d(this).attr("href");
            if (f == undefined || f == "") {
                f = 0;
            } else {
                if (f == "auto") {
                    f = -(d("#header").outerHeight());
                }
            }
            if (d(h).length) {
                d.scrollTo(h, 750, {easing: "swing", offset: {top: f, left: 0}});
            }
            g.preventDefault();
        });
    })();
});