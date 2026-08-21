; (function ($) {
    "use strict";

    // Reading progress bar
    $(window).scroll(function () {
        let w =
            ((document.body.scrollTop || document.documentElement.scrollTop) /
                (document.documentElement.scrollHeight -
                    document.documentElement.clientHeight)) *
            100;
        $("#reading-progress-fill").css({ width: w + "%", display: "block" });
    });

    // Header Navbar Search Form
    $(".right-nav .search-icon").on("click", function () {
        $(".search-input.toggle").toggle(300);
        $(".navbar .search-input.toggle input").focus();
        $(".right-nav .search-icon").toggleClass("show-close");
    });

    $(document).on(
        "click",
        "#docy-search-result .searchbar-tabs .tab-item",
        function (e) {
            $(".searchbar-tabs .tab-item").removeClass("active");
            $(this).addClass("active");
        }
    );

    $(document).ready(function () {
        // wide container header on course pages
        if ($(".single-courses").length) {
            $(".single-courses").addClass("wide-container");
        }

        if ($(".single-lesson").length) {
            $(".single-lesson").addClass("wide-container");
        }

        /**
         * Make the Titles clickable
         * If no selector is provided, it falls back to a default selector of:
         * 'h2, h3, h4, h5, h6'
         */
        if (typeof anchors != "undefined") {
            anchors.add(".anchor-enabled :is(h1, h2, h3, h4, h5)");
            anchors.remove(".cmgalaxy-engagement-block :is(h1, h2, h3, h4, h5)");
        }

        /**
         * Disable enter key press on Forum Topics Filter search input field
         */
        $(".post-header .category-menu .cate-search-form").keypress(function (event) {
            if (event.which == 13) {
                event.preventDefault();
            }
        });

        // Initialize mobile table functionality
        if (typeof mobileTable === 'function') {
            mobileTable();
        }
    });

    function mobileNavbarFixed() {
        if ($("#mobile-sticky").length) {
            $(window).scroll(function () {
                var scroll = $(window).scrollTop();
                if (scroll) {
                    $("#mobile-sticky").addClass("navbar_fixed");
                } else {
                    $("#mobile-sticky").removeClass("navbar_fixed");
                }
            });
        }
    }

    mobileNavbarFixed();

    //*=============menu sticky js =============*//

    //  page scroll
    function bodyFixed() {
        var windowWidth = $(window).width();
        if ($("#sticky_doc").length) {
            if (windowWidth > 576) {
                var tops = $("#sticky_doc");
                var leftOffset = tops.offset().top;

                $(window).on("scroll", function () {
                    var scroll = $(window).scrollTop();
                    if (scroll >= leftOffset) {
                        tops.addClass("body_fixed");
                    } else {
                        tops.removeClass("body_fixed");
                    }
                });
            }
        }
    }

    bodyFixed();

    // Left sidebar TOC area get sticky
    function bodyFixed2() {
        var windowWidth = $(window).width();

        if ($("#toc_stick").length) {
            if (windowWidth > 576) {
                let tops = $("#toc_stick");
                let topOffset = tops.offset().top;
                if ($('.blog_comment_box').length) {
                    let blogForm = $('.blog_comment_box');
                    let blogFormTop = blogForm.offset().top - 300;
                }

                $(window).on("scroll", function () {
                    var scrolls = $(window).scrollTop();
                    if (scrolls >= topOffset) {
                        tops.addClass("stick");
                    } else {
                        tops.removeClass("stick");
                    }
                });

                $('a[href="#hackers"]').click(function () {
                    $("#hackers").css("padding-top", "100px");

                    $(window).on("scroll", function () {
                        var hackersOffset = $("#hackers").offset().top;
                        var scrolls = $(window).scrollTop();
                        if (scrolls < hackersOffset) {
                            $("#hackers").css("padding-top", "0px");
                        }
                    });
                });
            }
        }
    }

    bodyFixed2();


    function mobileTable() {
        let tocVisible = false; // Track visibility of TOC
        let shareModalVisible = false; // Track visibility of Share modal

        // Ensure burger icon exists in .table_content
        function ensureBurgerIcon() {
            $('.table_content').each(function() {
                var $btn = $(this);
                if ($btn.find('svg').length === 0) {
                    $btn.prepend('<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px; flex-shrink: 0; display: inline-block !important;"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>');
                }
            });
        }
        ensureBurgerIcon();
        setTimeout(ensureBurgerIcon, 400);

        // Toggle the visibility of the Table of Contents when the button is clicked (instant, NO opacity animation)
        $('.table_content').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let toc = $('aside.bottom_table_content, #docy-tocs');
            let overlay = $('#toc-overlay');

            if (toc.is(':visible')) {
                toc.hide();
                overlay.hide();
                tocVisible = false;
            } else {
                $('#share-modal').hide();
                shareModalVisible = false;
                toc.css({
                    'background-color': '#ffffff',
                    'color': '#000000',
                    'display': 'flex'
                }).show();
                overlay.show();
                tocVisible = true;
            }
        });

        // Close TOC when the close button is clicked
        $('.close-toc').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $('aside.bottom_table_content, #docy-tocs').hide();
            $('#toc-overlay').hide();
            tocVisible = false;
        });

        // Close TOC and smooth scroll on link click (never jumps to top)
        $(document).on('click', '.bottom_table_content a, #docy-tocs a, #docy-tocs-mobile a', function (e) {
            var href = $(this).attr('href');
            if (href && href.startsWith('#')) {
                var targetId = href.substring(1);
                if (!targetId) return;

                e.preventDefault();
                e.stopPropagation();

                // Close modal and overlay instantly
                $('aside.bottom_table_content, #docy-tocs').hide();
                $('#toc-overlay').hide();
                tocVisible = false;

                // Highlight active link
                $('#docy-tocs a, #docy-tocs-mobile a, .bottom_table_content a').removeClass('active');
                $(this).addClass('active');

                var targetEl = document.getElementById(targetId);
                if (!targetEl) {
                    try {
                        targetEl = document.querySelector('[id="' + CSS.escape(targetId) + '"]');
                    } catch(err) {}
                }

                if (!targetEl) {
                    var linkText = $(this).text().trim();
                    $('.blog_single_item, .main-post, .editor-content, article').find('h1, h2, h3, h4').each(function() {
                        if ($(this).text().trim() === linkText) {
                            targetEl = this;
                            return false;
                        }
                    });
                }

                if (targetEl) {
                    var adminBarHeight = $('#wpadminbar').length ? $('#wpadminbar').outerHeight() : 0;
                    var headerOffset = ($(window).width() <= 1024 ? 75 : 120) + adminBarHeight;
                    var targetPosition = Math.max(0, $(targetEl).offset().top - headerOffset);

                    $('html, body').stop().animate({
                        scrollTop: targetPosition
                    }, 300);
                }
            }
        });

        // Toggle the visibility of the Share modal when the share button is clicked (instant)
        $('.table_share_btn').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let shareModal = $('#share-modal');
            let overlay = $('#toc-overlay');

            if (shareModalVisible) {
                shareModal.hide();
                overlay.hide();
                shareModalVisible = false;
            } else {
                $('aside.bottom_table_content, #docy-tocs').hide();
                tocVisible = false;
                shareModal.show();
                overlay.show();
                shareModalVisible = true;
            }
        });

        // Close Share modal when the close button is clicked
        $('.docy-close').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            let shareModal = $('#share-modal');
            let overlay = $('#toc-overlay');

            shareModal.hide();
            overlay.hide();
            shareModalVisible = false;
        });

        // Close TOC and Share modal when clicking outside (on the overlay)
        $('#toc-overlay').on('click', function (e) {
            e.preventDefault();
            $('aside.bottom_table_content, #docy-tocs').hide();
            $('#share-modal').hide();
            $(this).hide();
            tocVisible = false;
            shareModalVisible = false;
        });

        // Copy link functionality
        $(document).on('click', '.cm-copy-action-btn', function (e) {
            e.preventDefault();
            let url = $(this).attr('data-url') || window.location.href;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url);
            } else {
                let $temp = $('<input>');
                $('body').append($temp);
                $temp.val(url).select();
                document.execCommand("copy");
                $temp.remove();
            }

            let $toast = $('#share-modal .cm-copy-toast');
            if ($toast.length) {
                $toast.stop().fadeIn(200).delay(1800).fadeOut(300);
            }
        });

        // Hide floating bottom bar when reaching footer
        function checkFooterHideBar() {
            var $bar = $('.jrBzsJ, #cm-bottom-action-bar');
            var $footer = $('footer, .footer_area, .doc_footer_top, .footer-content, .footer-copyright').first();
            if (!$bar.length || !$footer.length) return;

            var footerRect = $footer[0].getBoundingClientRect();
            var viewportHeight = window.innerHeight || document.documentElement.clientHeight;

            if (footerRect.top <= viewportHeight + 15) {
                $bar.addClass('hidden-by-footer');
            } else {
                $bar.removeClass('hidden-by-footer');
            }
        }

        $(window).on('scroll resize touchmove orientationchange', checkFooterHideBar);
        $(document).ready(checkFooterHideBar);
        setTimeout(checkFooterHideBar, 300);
    }

    // mobileTable() call removed from here, moved inside $(document).ready above


    /*  Menu Click js  */
    function Menu_js() {
        if ($(".submenu").length) {
            $(".submenu > .dropdown-toggle").click(function () {
                var location = $(this).attr("href");
                window.location.href = location;
                return false;
            });
        }
    }

    Menu_js();

    if ($(".mobile_menu").length > 0) {
        var switchs = true;
        $(".mobile_btn").on("click", function (e) {
            if (switchs) {
                $(".mobile_menu").addClass("open");
            }
        });
    }

    /*--------------- parallaxie js--------*/
    function parallax() {
        if ($(".parallaxie").length) {
            $(".parallaxie").parallaxie({
                speed: 0.5,
            });
        }
    }

    parallax();

    if ($(".tooltips_one").length) {
        $(".tooltips_one").data("tooltip-custom-class", "tooltip_blue").tooltip();
    }
    if ($(".tooltips_two").length) {
        $(".tooltips_two")
            .data("tooltip-custom-class", "tooltip_danger")
            .tooltip();
    }

    /*--------------- mobile dropdown js--------*/
    function menu_dropdown() {
        $(".menu > li .mobile_dropdown_icon").on("click", function (event) {
            console.log('hello');
            $(this).parent().parent().find(".dropdown-menu").first().slideToggle(700);
            $(this).parent().parent().siblings().find(".dropdown-menu").slideUp(700);
            // ToggleClass open added in the direct parent (not all parent)
            $(this).parent().parent().toggleClass("opened");
        });
    }

    menu_dropdown();

    /*--------------- niceSelect js--------*/

    function select() {
        if ($(".custom-select, .nice_select").length) {
            $(".custom-select, .nice_select").niceSelect();
        }
        if ($("#mySelect").length) {
            $("#mySelect").selectpicker();
        }
    }

    select();

    /*--------------- counterUp js--------*/
    function counterUp() {
        if ($(".counter").length) {
            $(".counter").counterUp({
                delay: 1,
                time: 250,
            });
        }
    }

    counterUp();

    /*--------------- popup-js--------*/
    function popupGallery() {
        if ($(".img_popup").length) {
            $(".img_popup").each(function () {
                $(".img_popup").magnificPopup({
                    type: "image",
                    closeOnContentClick: true,
                    closeBtnInside: false,
                    fixedContentPos: true,
                    removalDelay: 300,
                    mainClass: "mfp-no-margins mfp-with-zoom",
                    image: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0, 1], // Will preload 0 - before current, and 1 after the current image,
                    },
                });
            });
        }
    }

    popupGallery();

    /*--------------- video js--------*/
    function video() {
        if ($("#inline-popups").length) {
            $("#inline-popups").magnificPopup({
                delegate: "a",
                removalDelay: 500, //delay removal by X to allow out-animation
                mainClass: "mfp-no-margins mfp-with-zoom",
                preloader: false,
                midClick: true,
            });
        }
    }

    video();

    /*--------- WOW js-----------*/
    function bodyScrollAnimation() {
        var scrollAnimate = $("body").data("scroll-animation");
        if (scrollAnimate === true) {
            new WOW({}).init();
        }
    }

    bodyScrollAnimation();

    // Left sidebar / Mobile menu state
    function setSideMenuState(isOpen) {
        if (!$('.side_menu').length) {
            return;
        }
        if (isOpen) {
            $("body").removeClass("menu-is-closed").addClass("menu-is-opened");
            $(".side_menu").addClass("menu-opened");
        } else {
            $("body").removeClass("menu-is-opened").addClass("menu-is-closed");
            $(".side_menu").removeClass("menu-opened");
        }
    }

    // Always ensure menu starts closed on new page load
    setSideMenuState(false);
    try {
        localStorage.removeItem("docy_side_menu_open");
    } catch (e) {}

    // Global mobile menu open button
    $(".mobile_menu_btn").on("click", function () {
        setSideMenuState(true);
    });

    // Close button
    $(".close_nav").on("click", function (e) {
        setSideMenuState(false);
    });

    // Overlay click
    $(".click_capture").on("click", function () {
        setSideMenuState(false);
    });

    // Close mobile drawer when clicking any link inside it
    $(document).on("click", ".side_menu a", function () {
        if (!$(this).hasClass("expandable-subcat") && !$(this).closest(".section-header.expandable").length) {
            setSideMenuState(false);
        }
    });

    /*--------------- Tab button js--------*/
    $(".next").on("click", function () {
        $(".v_menu .nav-item > .active")
            .parent()
            .next("li")
            .find("a")
            .trigger("click");
    });

    $(".previous").on("click", function () {
        $(".v_menu .nav-item > .active")
            .parent()
            .prev("li")
            .find("a")
            .trigger("click");
    });

    function Click_menu_hover() {
        if ($(".tab-demo").length) {
            $.fn.tab = function (options) {
                var opts = $.extend({}, $.fn.tab.defaults, options);
                return this.each(function () {
                    var obj = $(this);

                    $(obj)
                        .find(".tabHeader li")
                        .on(opts.trigger_event_type, function () {
                            $(obj).find(".tabHeader li").removeClass("active");
                            $(this).addClass("active");

                            $(obj).find(".tabContent .tab-pane").removeClass("active show");
                            $(obj)
                                .find(".tabContent .tab-pane")
                                .eq($(this).index())
                                .addClass("active show");
                        });
                });
            };
            $.fn.tab.defaults = {
                trigger_event_type: "click", //mouseover | click
            };
        }
    }

    Click_menu_hover();

    function Tab_menu_activator() {
        if ($(".tab-demo").length) {
            $(".tab-demo").tab({
                trigger_event_type: "mouseover",
            });
        }
    }

    Tab_menu_activator();

    function fAqactive() {
        $(".doc_faq_info .card").on("click", function () {
            $(".doc_faq_info .card").removeClass("active");
            $(this).addClass("active");
        });
    }

    fAqactive();

    function general() {
        $(".short-by a").click(function () {
            $(this)
                .toggleClass("active-short")
                .siblings()
                .removeClass("active-short");
        });
    }

    general();

    /*-------------------------------------
        Intersection Observer
    -------------------------------------*/
    if (!!window.IntersectionObserver) {
        let observer = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("active-animation");
                        //entry.target.src = entry.target.dataset.src;
                        observer.unobserve(entry.target);
                    }
                });
            },
            {
                rootMargin: "0px 0px -100px 0px",
            }
        );
        document.querySelectorAll(".has-animation").forEach((block) => {
            observer.observe(block);
        });
    } else {
        document.querySelectorAll(".has-animation").forEach((block) => {
            block.classList.remove("has-animation");
        });
    }

    // === Search ("/" to focus)
    if (
        docy_local_object.is_focus_by_slash === "1" &&
        $(".sbnr-global").length
    ) {
        $(document).on("keydown", function (e) {
            if (e.keyCode === 191) {
                e.preventDefault();
                $(".sbnr-global form input[type=search]").focus();
                return;
            }
        });
    }

    if ($(".cheatsheet_item").length) {
        $(".shadow-sm.cheatsheet_item").hover(
            function () {
                $(this).removeClass("shadow-sm");
                $(this).addClass("shadow-lg");
            },
            function () {
                $(this).removeClass("shadow-lg");
                $(this).addClass("shadow-sm");
            }
        );
    }

    if ($(".popup-youtube").length) {
        $(".popup-youtube").magnificPopup({
            type: "iframe",
        });
    }

    // Update cart button
    if ($(".ar_top").length) {
        $(".ar_top").on("click", function () {
            var getID = $(this).next().attr("id");
            var result = document.getElementById(getID);
            var qty = result.value;
            $(".woocommerce-cart .update-cart").removeAttr("disabled");
            if (!isNaN(qty)) {
                result.value++;
                $(".cart_btn.ajax_add_to_cart").attr("data-quantity", result.value);
            } else {
                return false;
            }
        });

        $(".ar_down").on("click", function () {
            var getID = $(this).prev().attr("id");
            var result = document.getElementById(getID);
            var qty = result.value;
            $(".woocommerce-cart .update-cart").removeAttr("disabled");
            if (!isNaN(qty) && qty > 0) {
                result.value--;
                $(".cart_btn.ajax_add_to_cart").attr("data-quantity", result.value);
            } else {
                return false;
            }
        });
    }

    //================  Mega Menu ====================//
    $(".has-docy-mega-menu").click(function () {
        $(this).toggleClass("megamenu-display");
    });

    $(".has-docy-mega-menu > a").click(function () {
        $(this).parent(".has-docy-mega-menu").toggleClass("megamenu-display");
        return false;
    });

    $(".arrow_carrot-right.mobile_dropdown_icon").click(function () {
        $(this).parent().parent(".has-docy-mega-menu").toggleClass("megamenu-display");
    });

    //================ Top Header ====================//
    function docy_top_header() {
        if ($('.top_header').length > 0) {
            $('body').addClass('docy_top_header');
        }
    };

    docy_top_header();

    //================ Share Button ====================//
    // Copy the current page link to clipboard
    if ($(".share-this-doc").length) {
        $(".share-this-doc").on("click", function (e) {
            e.preventDefault();
            let success_message = $(this).data("success-message");
            let $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(location).attr("href")).select();
            document.execCommand("copy");
            $temp.remove();

            setTimeout(function () {
                $(".docy-link-copied-wrap").text(success_message).addClass("copied");
            }, 500);

            setTimeout(function () {
                $(".docy-link-copied-wrap").removeClass("copied");
            }, 3500);
        });
    }
    $(".docy-link-copied-wrap").click(function () {
        $(this).removeClass("copied");
    });

    $.fn.ezd_social_popup = function (
        e,
        intWidth,
        intHeight,
        strResize,
        blnResize
    ) {
        // Prevent default anchor event
        e.preventDefault();

        // Set values for window
        intWidth = intWidth || "500";
        intHeight = intHeight || "400";
        strResize = blnResize ? "yes" : "no";

        // Set title and open popup with focus on it
        var strTitle =
            typeof this.attr("title") !== "undefined"
                ? this.attr("title")
                : "Social Share",
            strParam =
                "width=" +
                intWidth +
                ",height=" +
                intHeight +
                ",resizable=" +
                strResize,
            objWindow = window.open(this.attr("href"), strTitle, strParam).focus();
    };
    $(".social-links a:not(:first)").on("click", function (e) {
        $(this).ezd_social_popup(e);
    });

    // Select the #docy-toc and .doc_footer_area elements


    document.addEventListener("DOMContentLoaded", function () {
        // Add Body Class When active video banner selector found
        let WrapBannerVideo = document.getElementsByClassName("banner-video-container");
        if (WrapBannerVideo.length > 0) {
            document.body.classList.add('banner-video-wrap');
        }
    });

})(jQuery);


// banner feature video js
document.addEventListener("DOMContentLoaded", function () {
    var playButton = document.getElementById("video-playId");
    if (playButton) {
        playButton.addEventListener("click", function () {
            // Get the video iframe and overlay element
            var iframe = document.getElementById("banner-video");
            var overlay = document.querySelector(".play-overlay");
            var imgThumbnail = document.getElementById("videoThumbnail");

            // Update the iframe source to autoplay the video
            iframe.src = iframe.src + "&autoplay=1";

            // Hide the overlay once the play button is clicked
            if (overlay) overlay.style.display = "none";

            // Display the video iframe
            if (iframe) iframe.style.display = "block";
            if (imgThumbnail) imgThumbnail.style.display = "none";

            console.log(imgThumbnail);
        });
    } else {
        console.warn("Play button with ID 'video-playId' not found.");
    }
});

// Function to copy the video link and show a popup message
function copyVideoLink(link) {
    // Get the copy button element
    const copyBtn = document.getElementById('copyLinkBtn');

    // Copy the link to the clipboard
    navigator.clipboard.writeText(link).then(function () {
        // On successful copy, update the button text
        copyBtn.textContent = '✅ Link copied to clipboard!';
        copyBtn.classList.add('copied');

        // Reset the button text after 3 seconds
        setTimeout(() => {
            copyBtn.textContent = '📋 Copy link';
            copyBtn.classList.remove('copied');
        }, 3000);
    }).catch(function (err) {
        console.error('Failed to copy: ', err);
    });
}

// Footer detection for sticky sidebars
jQuery(function ($) {
    const FOOTER_MARGIN = 200;
    let footerObserver = null;

    function toggleFooterClass(inView) {
        if (inView) {
            document.body.classList.add('footer-in-view');
        } else {
            document.body.classList.remove('footer-in-view');
        }
    }

    function setupFooterDetection() {
        const footer = document.querySelector('footer');
        if (!footer) {
            toggleFooterClass(false);
            return;
        }

        if (footerObserver) {
            footerObserver.disconnect();
            footerObserver = null;
        }

        if ('IntersectionObserver' in window) {
            footerObserver = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        toggleFooterClass(entry.isIntersecting);
                    });
                },
                {
                    rootMargin: `0px 0px -${FOOTER_MARGIN}px 0px`,
                    threshold: 0,
                }
            );
            footerObserver.observe(footer);
        } else {
            const onScroll = () => {
                const rect = footer.getBoundingClientRect();
                const windowHeight = window.innerHeight || document.documentElement.clientHeight;
                toggleFooterClass(rect.top <= windowHeight - FOOTER_MARGIN);
            };

            $(window)
                .off('scroll.footerDetection', onScroll)
                .on('scroll.footerDetection', onScroll);

            onScroll();
        }
    }

    setupFooterDetection();
    setTimeout(setupFooterDetection, 1000);
});
