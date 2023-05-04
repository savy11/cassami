jQuery(document).ready(function ($) {
    /* Mobile Navigation */
    function mainMenuMobile() {
        $('.main-menu').each(function () {
            var $mainNav = $(this),
                $menuOverlay = $('.overlay-nav'),
                $menuTrigger = $(this).find('.hamburger a'),
                $eventMarker = $(this).find('.hamburger');
            var eventTrigger = function (event) {
                event.preventDefault();
                if ($eventMarker.is(":visible")) {
                    if (!($mainNav.hasClass('main-menu-opened'))) {
                        $mainNav.addClass('main-menu-opened');
                        $menuOverlay.fadeIn(800);
                    } else {
                        $mainNav.removeClass('main-menu-opened');
                        $menuOverlay.fadeOut(400);
                        setTimeout(function () {
                            $mainNav.find('.main-nav.small-screen .menu-dropdown > a').removeClass('menu-trigger').siblings('ul').hide();
                        }, 400);

                    }
                }
            };
            $menuTrigger.on('click', eventTrigger);
            $menuOverlay.on('click', eventTrigger);

            $(window).on('resize load', function () {
                if ($eventMarker.is(":visible") && ($mainNav.hasClass('main-menu-opened'))) {
                    $mainNav.removeClass('main-menu-opened');
                    $menuOverlay.fadeOut(400);
                }
            });
        });
    }

    mainMenuMobile();

    /* Navigation */
    function mainMenuDesktop() {

        var $nav_MainMenu = $('.main-nav');

        $(window).on('resize load', function () {
            $nav_MainMenu.each(function () {
                var mainNav_height = $(this).filter('.small-screen').parents('nav').outerHeight();
                var mainMenu_height = $(window).height() - mainNav_height;
                $(this).filter('.small-screen').css('height', mainMenu_height);
            });
        });

        $('.main-nav a[href="#"]').on('click', function (event) {
            event.preventDefault();
        });

        $nav_MainMenu.each(function () {
            $(this).find('li:has(ul)').addClass('sub-menu');

            $(this).find('>li li:has(ul)').children('a').on('click', function (event) {
                event.preventDefault();
            });

            $(this).filter('.small-screen').find('li:has(ul)').addClass('menu-dropdown');

            $(this).filter(".small-screen").find(".menu-dropdown > a").each(function () {
                $(this).siblings('ul').hide();
                $(this).on("click", function (event) {
                    event.preventDefault();
                    menu_DropdownAccordion(this);
                });
            });

            function menu_DropdownAccordion(selector) {
                if ($(selector).hasClass('menu-trigger')) {
                    $(selector).parent('li')
                        .find('a')
                        .removeClass('menu-trigger')
                        .parent('li')
                        .children('ul')
                        .slideUp(400);
                } else {
                    $(selector)
                        .addClass('menu-trigger')
                        .parent('li')
                        .siblings()
                        .find('a')
                        .removeClass('menu-trigger')
                        .parent('li')
                        .children('ul')
                        .slideUp(400);

                    $(selector)
                        .siblings('ul').slideDown(400);
                }
            }
        });
    }

    mainMenuDesktop();


    /* Sticky Navigation */
    function stickyMenu() {
        var $elem = $('.main-menu');

        $elem.each(function () {
            var $navWrapper = $(this).parents('.main-menu-wrapper'),
                stickyNavPos = 0;

            $(window).on('resize load', function () {
                stickyNavPos = $navWrapper.offset().top;
            });

            $(window).on('scroll', function () {
                if ($(window).scrollTop() > stickyNavPos) {
                    $navWrapper.addClass('sticky-nav');
                } else {
                    $navWrapper.removeClass('sticky-nav');
                }
            });
        });
    }

    stickyMenu();

    /* Multiple Slider  */
    function multipleSlider() {
        $('.slider-multiple').each(function () {
            $(this).slick({
                autoplay: true,
                slidesToShow: $(this).data('show') || 3,
                swipeToSlide: true,
                speed: 400,
                dots: ($(this).hasClass('control-nav') ? true : false),
                autoplaySpeed: $(this).data('time') || 5000,
                arrows: ($(this).hasClass('dir-nav') ? true : false),
                adaptiveHeight: ($(this).hasClass('height-auto') ? true : false),
                responsive: [

                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2
                        }
                    },

                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1
                        }
                    }

                ]
            });

        });
    }

    multipleSlider();


    /* Modal Gallery */
    function modalGallery() {
        $(document).on('click', '[data-toggle="lightbox"]', function (event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                leftArrow: '<span><i class="fa fa-angle-left"></i></span>',
                rightArrow: '<span><i class="fa fa-angle-right"></i></span>'
            });
        });
    }

    modalGallery();


    /* Grid / List Option */
    function gridListView() {
        var $elem = $('.listing-products');
        var $opts = $('.display-opts');
        var $btnGrid = $opts.find('.btn-grid');
        var $btnList = $opts.find('.btn-list');

        $btnGrid.on('click', function () {
            var listingFOR = $(this).closest($opts).data('listing-for');

            $elem.each(function () {
                var listingID = $(this).data('listing-id');

                if ((listingFOR === listingID) && ($(this).hasClass('list'))) {
                    $(this).toggleClass('grid list');
                }
            });

            return false;
        });

        $btnList.on('click', function () {
            var listingFOR = $(this).closest($opts).data('listing-for');

            $elem.each(function () {
                var listingID = $(this).data('listing-id');

                if ((listingFOR === listingID) && ($(this).hasClass('grid'))) {
                    $(this).toggleClass('list grid');
                }
            });

            return false;
        });

    }

    gridListView();


    function addToFav() {
        var $elem = $('.listing-products').find('.image-wrapper');
        var $btnFav = $elem.find('.add-to-fav');

        $btnFav.on('click', function () {
            var $child = $(this);
            $(this).closest($elem).addClass('adding');
            setTimeout(function () {
                $child.parents('.image-wrapper').removeClass('adding');
            }, 1000);

            return false;
        });

    }

    addToFav();


    /* Modal Product Quick View */
    function quickView() {
        var $elem = $('.quick-view');

        $elem.each(function () {
            $(this).on('shown.bs.modal', function () {
                $(this).find('.slider-single').slick('setPosition', 0);
            });
        });
    }

    quickView();


    /* Loading Page  */
    function loadingPage() {
        $(window).on('load', function () {
            setTimeout(function () {
                $('html, body').removeClass('loading');
                $('.page-loader').fadeOut("slow", function () {
                    $(this).removeClass("loading");
                });
            }, 500);
        });
    }

    loadingPage();

    /* Scroll to TAB */
    $('.nav-tabs a').click(function () {
        $(this).tab('show');
        var href = $(this).attr('href');
        setTimeout(function () {
            $('html, body').animate({
                scrollTop: $(href).offset().top
            }, 'slow');
        }, 250);
    });

    $('.back-to-top').on('click', function () {
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });
});