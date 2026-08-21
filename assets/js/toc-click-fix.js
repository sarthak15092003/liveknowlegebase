/**
 * TOC Click Fix - Custom implementation for accurate TOC highlighting
 */
(function ($) {
    'use strict';

    $(document).ready(function () {
        var isManualScroll = false;
        var scrollTimeout;

        // Function to update active TOC item
        function setActiveTocItem(targetId) {
            // Remove all active classes
            $('#docy-toc a, .doc-nav a, .nav-sidebar a').removeClass('active');
            $('#docy-toc li, .doc-nav li, .nav-sidebar li').removeClass('active');

            // Add active to the target and all its parent list items
            var $targetLink = $('#docy-toc a[href="#' + targetId + '"], .doc-nav a[href="#' + targetId + '"], .nav-sidebar a[href="#' + targetId + '"]');
            $targetLink.addClass('active');
            $targetLink.parents('li').addClass('active'); // Bubbles active class up to first-level steps
        }

        // Handle TOC link clicks
        $(document).on('click', '#docy-toc a, .doc-nav a, .nav-sidebar a', function (e) {
            var $clickedLink = $(this);
            var href = $clickedLink.attr('href');

            // Only handle anchor links
            if (!href || !href.startsWith('#')) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            // Set flag to prevent scrollspy interference
            isManualScroll = true;

            // Get target ID
            var targetId = href.substring(1);

            // Immediately set active state
            setActiveTocItem(targetId);

            // Get the target element safely
            var target = document.getElementById(targetId);
            if (!target) {
                try {
                    target = document.querySelector('[id="' + CSS.escape(targetId) + '"]');
                } catch(err) {}
            }

            if (target) {
                var adminBarHeight = $('#wpadminbar').length > 0 ? $('#wpadminbar').outerHeight() : 0;
                var scrollOffset = ($(window).width() <= 1024 ? 80 : 120) + adminBarHeight;
                var targetPosition = Math.max(0, $(target).offset().top - scrollOffset);

                $('html, body').stop().animate({
                    scrollTop: targetPosition
                }, 350, function () {
                    setActiveTocItem(targetId);
                    setTimeout(function() {
                        isManualScroll = false;
                    }, 100);
                });
            } else {
                isManualScroll = false;
            }
        });

        // Custom scroll detection for automatic TOC highlighting
        function updateTocOnScroll() {
            if (isManualScroll) return;

            var currentScroll = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            var scrollPos = currentScroll + 150; // Offset for detection
            var currentSection = null;

            // Find all headings with IDs
            $('.blog_single_item h1[id], .blog_single_item h2[id], .blog_single_item h3[id], .blog_single_item h4[id], .blog_single_item h5[id]').each(function () {
                var $heading = $(this);
                var headingTop = $heading.offset().top;

                if (scrollPos >= headingTop) {
                    currentSection = $heading.attr('id');
                }
            });

            if (currentSection) {
                setActiveTocItem(currentSection);
            }
        }

        // Throttled scroll handler
        $(window).add(document.body).on('scroll', function () {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(updateTocOnScroll, 100);
        });

        // Initial check
        updateTocOnScroll();
    });

})(jQuery);
