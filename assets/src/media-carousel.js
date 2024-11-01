import Swiper, { EffectFade, EffectCube, EffectFlip, EffectCoverflow, EffectCards, FreeMode, Autoplay, Controller, Mousewheel, Navigation, Pagination, Scrollbar, Thumbs, Lazy } from 'swiper'
import './style/media-carousel.scss';

class WadiMediaCarousel extends elementorModules.frontend.handlers.Base {

    
  getDefaultSettings() {
    return {
      selectors: {
        wadiMediaSwiper: '.swiper',
      },
    }
  }

  getDefaultElements() {
    const selectors = this.getSettings('selectors');
    const elements = {
      $wadiMediaSwiperElem: this.findElement(selectors.wadiMediaSwiper),
    };

    return elements;
  }

  bindEvents() {
    this.initiate();
  }

  initiate() {
    const _this = this;
    const $wadiMediaSwiperElem = this.elements.wadiMediaSwiperElem;
    const elementsSettings = this.getElementSettings();
    const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
    const selectors = this.getSettings('selectors');

    const wadiMediaCarouselSwiperSettings = {
      wadiMediaSwiperElement: ".wadi_media_carousel_swiper_" + this.$element[0].getAttribute("data-id"),
      wadiMediaSwiperThumbsElement: ".wadi_media_carousel_swiper_thumbs_" + this.$element[0].getAttribute("data-id"),
      carouselMediaCarouselSkin: elementsSettings.wadi_media_carousel_skin ? elementsSettings.wadi_media_carousel_skin : 'carousel',
      carouselMediaInitialSlide: elementsSettings.wadi_media_carousel_initial_slide || 0,
      carouselMediaSpeedTransition: elementsSettings.wadi_media_carousel_speed_transition || 300,
      carouselMediaDirection: elementsSettings.wadi_media_carousel_direction || 'horizontal',
      carouselMediaDotsNavigation: elementsSettings.wadi_media_carousel_dots_navigation === 'yes' ? true: false,
      carouselMediaDotsClickable : elementsSettings.wadi_media_carousel_dots_clickable === 'yes' ? true : false,
      carouselMediaPaginationType: elementsSettings.wadi_media_carousel_pagination_type || 'bullets',
      carouselMediaDotsNavigationHideOnClick: elementsSettings.wadi_media_carousel_dots_pagination_hide_on_click === 'yes' ? true : false,
      carouselMediaProgressbarOpposite: elementsSettings.wadi_media_carousel_progress_bar_opposite === 'yes' ? false: true,
      carouselMediaNavigationArrows: elementsSettings.wadi_media_carousel_arrow_navigation === 'yes' ? true : false,
      // Scrollbar
      carouselMediaScrollbar: elementsSettings.wadi_media_carousel_scrollbar === 'yes' ? true : false,
      carouselMediaScrollbarDraggable: elementsSettings.wadi_media_carousel_scrollbar_draggable === 'yes' ? true: false,
      carouselMediaScrollbarHide: elementsSettings.wadi_media_carousel_scrollbar_hide === 'yes' ? true : false,
      carouselMediaScrollbarSnap: elementsSettings.wadi_media_carousel_scrollbar_snap === 'yes' ? true : false,
      // Slides Per View
      carouselMediaSlidesPerView: elementsSettings.wadi_media_carousel_slides_per_view ? elementsSettings.wadi_media_carousel_slides_per_view : 1,
      carouselMediaSlidesPerViewTablet: elementsSettings.wadi_media_carousel_slides_per_view_tablet ? elementsSettings.wadi_media_carousel_slides_per_view_tablet : 1,
      carouselMediaSlidesPerViewMobile: elementsSettings.wadi_media_carousel_slides_per_view_mobile ? elementsSettings.wadi_media_carousel_slides_per_view_mobile : 1,
      
      // Slides Per Group
      carouselMediaSlidesPerGroup: elementsSettings.wadi_media_carousel_slides_per_group ? elementsSettings.wadi_media_carousel_slides_per_group : 1,
      carouselMediaSlidesPerGroupTablet: elementsSettings.wadi_media_carousel_slides_per_group_tablet ? elementsSettings.wadi_media_carousel_slides_per_group_tablet : 1,
      carouselMediaSlidesPerGroupMobile: elementsSettings.wadi_media_carousel_slides_per_group_mobile ? elementsSettings.wadi_media_carousel_slides_per_group_mobile : 1,

      carouselMediaSpaceBetween: elementsSettings.wadi_media_carousel_space_between ? elementsSettings.wadi_media_carousel_space_between : 0,
      carouselMediaSpaceBetweenTablet: elementsSettings.wadi_media_carousel_space_between_tablet ? elementsSettings.wadi_media_carousel_space_between_tablet : 0,
      carouselMediaSpaceBetweenMobile: elementsSettings.wadi_media_carousel_space_between_mobile ? elementsSettings.wadi_media_carousel_space_between_mobile : 0,

      carouselMediaBreakPoints: {
        desktop: elementsSettings.wadi_media_carousel_breakpoints || 1024,
        tablet: elementsSettings.wadi_media_carousel_breakpoints_tablet || 768,
        mobile: elementsSettings.wadi_media_carousel_breakpoints_mobile || 320,
      },

      carouselMediaCentered: elementsSettings.wadi_media_carousel_centered === 'yes' ? true : false,
      carouselMediaGrabCursor: elementsSettings.wadi_media_carousel_grab_cursor === 'yes' ? true : false,
      // Some Options
      carouselLoop: elementsSettings.wadi_media_carousel_loop === 'yes' ? true : false,
      carouselMousewheel: elementsSettings.wadi_media_carousel_mousewheel === 'yes' ? true : false,
      carouselAutoplay: elementsSettings.wadi_media_carousel_autoplay === 'yes' ? {
        delay: elementsSettings.wadi_media_carousel_autoplay_delay,
        disableOnInteraction: elementsSettings.wadi_media_carousel_autoplay_disable_on_interaction === 'yes' ? true : false,
        pauseOnMouseEnter: elementsSettings.wadi_media_carousel_autoplay_pause_on_mouseenter === 'yes' ? true : false,
        reverseDirection: elementsSettings.wadi_media_carousel_autoplay_reverse_direction === 'yes' ? true : false,
        stopOnLastSlide: (elementsSettings.wadi_media_carousel_autoplay_stop_last_slide === 'yes' &&  elementsSettings.wadi_media_carousel_loop !== 'yes') ? true : false,
      } || true : false,
      // Effects

      carouselSlideEffect: elementsSettings.wadi_media_carousel_slide_effects || 'slide',
      ... elementsSettings.wadi_media_carousel_slide_effects === 'coverflow' && {
        carouselCoverflowEffect: {
          rotate: elementsSettings.wadi_media_carousel_coverflow_effect_rotate || 50,
          slideShadows: elementsSettings.wadi_media_carousel_coverflow_effect_slide_shadows === 'yes' ? true : false,
          depth: elementsSettings.wadi_media_carousel_coverflow_effect_depth || 100,
          modifier: elementsSettings.wadi_media_carousel_coverflow_effect_modifier || 1,
          scale: elementsSettings.wadi_media_carousel_coverflow_effect_scale || 1,
          stretch: elementsSettings.wadi_media_carousel_coverflow_effect_stretch || 0,
        }
      },
      ... elementsSettings.wadi_media_carousel_slide_effects === 'flip' && {
        carouselFlipEffect: {
          slideShadows: elementsSettings.wadi_media_carousel_flip_effect_slide_shadows === 'yes' ? true : false,
          limitRotation: elementsSettings.wadi_media_carousel_flip_effect_limit_rotation === 'yes' ? true : false,
        }
      },
      ... elementsSettings.wadi_media_carousel_slide_effects === 'cube' && {
        carouselCubeEffect: {
          shadow: elementsSettings.wadi_media_carousel_cube_effect_shadow === 'yes' ? true : false,
          ... elementsSettings.wadi_media_carousel_cube_effect_shadow && {
            shadowOffset: elementsSettings.wadi_media_carousel_cube_effect_shadow_offset || 20,
            shadowScale: elementsSettings.wadi_media_carousel_cube_effect_shadow_scale || 0.94,
            slideShadows: elementsSettings.wadi_media_carousel_cube_effect_slide_shadows === 'yes' ? true : false,
          }
        }
      },
      ... elementsSettings.wadi_media_carousel_slide_effects === 'cards' && {
        carouselCardsEffect: {
          slideShadows: elementsSettings.wadi_media_carousel_cards_effect_slide_shadows === 'yes' ? true : false,
        }
      },

      // Slideshow Options
      ... 'slideshow' === elementsSettings.wadi_media_carousel_skin && {
        carouselThumbsSlidesPerView: elementsSettings.wadi_media_carousel_thumb_slides_per_view ? elementsSettings.wadi_media_carousel_thumb_slides_per_view : 5,
        carouselThumbSpacebetween: elementsSettings.wadi_media_carousel_thumb_spacebetween_slideshow ? elementsSettings.wadi_media_carousel_thumb_spacebetween_slideshow : 10,
      },
      //Lazyload
      wadiMediaCarouselLazyLoad: elementsSettings.wadi_media_carousel_lazy_load === 'yes' ? true : false,
    }

    _this.wadiMediaSwiper($wadiMediaSwiperElem, wadiMediaCarouselSwiperSettings);

  }

  wadiMediaSwiper(wadiMediaSwiperElem, settings) {
    let carouselMediaBreakpointMobile = settings.carouselMediaBreakPoints.mobile;
    let carouselMediaBreakpointTablet = settings.carouselMediaBreakPoints.tablet;
    let carouselMediaBreakpointDesktop = settings.carouselMediaBreakPoints.desktop;

    const $this = this;

     // Swiper Thumbs
     const wadiMediaSwiperThumbsElement = settings.wadiMediaSwiperThumbsElement;
     
     let swiperThumbs;
     // Check if Carousel Thumbs (Slideshow) is enabled
     if('slideshow' === settings.carouselMediaCarouselSkin) {
        // if yes create new Swiper instance        
        swiperThumbs = new Swiper(wadiMediaSwiperThumbsElement, {
          modules: [EffectFade, EffectCube, EffectFlip, EffectCoverflow, EffectCards, FreeMode, Autoplay, Controller, Mousewheel, Navigation, Pagination, Scrollbar, Thumbs, Lazy],
          spaceBetween: 10,
          initialSlide: settings.carouselMediaInitialSlide,
          slidesPerView: settings.carouselThumbsSlidesPerView,
          spaceBetween: settings.carouselThumbSpacebetween.size,
          loop: true,
          slideToClickedSlide: true,
          freeMode: true,
          watchSlidesProgress: true,
          lazy: {
            loadPrevNext: true,
          }
      });
      }


    const wadiMediaSwiperElement = settings.wadiMediaSwiperElement;

      var swiper = new Swiper(wadiMediaSwiperElement, {
        modules: [EffectFade, EffectCube, EffectFlip, EffectCoverflow, EffectCards, FreeMode, Autoplay, Controller, Mousewheel, Navigation, Pagination, Scrollbar, Thumbs, Lazy],
        initialSlide: settings.carouselMediaInitialSlide,
        direction: settings.carouselMediaDirection,
        speed: settings.carouselMediaSpeedTransition,
        slidesPerView: settings.carouselMediaSlidesPerView,
        slidesPerGroup: settings.carouselMediaSlidesPerGroup,

        ... settings.wadiMediaCarouselLazyLoad && {
          preloadImages: false,
          lazy: settings.wadiMediaCarouselLazyLoad,
        },

        breakpoints: {
          // when window width is >= 280px
          [carouselMediaBreakpointMobile]: {
              slidesPerView: settings.carouselMediaSlidesPerViewMobile,
              spaceBetween: settings.carouselMediaSpaceBetweenMobile,
            },
            // when window width is >=  768px
            [carouselMediaBreakpointTablet]: {
              slidesPerView: settings.carouselMediaSlidesPerViewTablet,
              spaceBetween: settings.carouselMediaSpaceBetweenTablet,
            },
            // when window width is >= 1024px
            [carouselMediaBreakpointDesktop]: {
              slidesPerView: settings.carouselMediaSlidesPerView,
              spaceBetween: settings.carouselMediaSpaceBetween,
            }
        },


        ...(settings.carouselMediaDotsNavigation && {
          pagination: {
          el: `.wadi_media_carousel_container .swiper-pagination`,
          clickable: settings.carouselMediaDotsClickable,
          type: settings.carouselMediaPaginationType,
          hideOnClick: settings.carouselMediaDotsNavigationHideOnClick,
          ... settings.carouselMediaDirection === 'vertical' && settings.carouselMediaPaginationType === 'progressbar' && {progressbarOpposite: settings.carouselMediaProgressbarOpposite},
        },
        }),
      
        // Navigation arrows
        ... settings.carouselMediaNavigationArrows && {
            navigation: {
            nextEl: ".wadi-media_carousel_swiper-button-next",
            prevEl: ".wadi-media_carousel_swiper-button-prev",
          }
        },
        
        // And if we need scrollbar
        ... settings.carouselMediaScrollbar && {
          scrollbar: {
          el: `.wadi_media_carousel_container .swiper-scrollbar`,
          draggable: settings.carouselMediaScrollbarDraggable,
          hide: settings.carouselMediaScrollbarHide,
          snapOnRelease: settings.carouselMediaScrollbarSnap, 
          }
        },

        // Some Options
        loop: settings.carouselLoop,
        // autoHeight: settings.carouselAutoHeight,
        mousewheel: settings.carouselMousewheel,
        autoplay: settings.carouselAutoplay,
        // Centered Active Slide
        centeredSlides: settings.carouselMediaCentered,
        // Grab Cursor
        grabCursor: settings.carouselMediaGrabCursor,
        // Effects
        effect: settings.carouselSlideEffect,
        ... settings.carouselSlideEffect === 'fade' && {fadeEffect: {
          crossFade: true
        }},
        ... settings.carouselSlideEffect === 'coverflow' && {coverflowEffect: settings.carouselCoverflowEffect},
        ... settings.carouselSlideEffect === 'flip' && {flipEffect: settings.carouselFlipEffect},
        ... settings.carouselSlideEffect === 'cube' && {cubeEffect: settings.carouselCubeEffect},
        ... settings.carouselSlideEffect === 'cards' && {cardsEffect: settings.carouselCardsEffect},
        ... settings.carouselMediaCarouselSkin === 'slideshow' && {
          thumbs: {
            swiper: swiperThumbs,
            multipleActiveThumbs: false // Disable multiple thumbs active on slideshow https://swiperjs.com/swiper-api#param-thumbs-multipleActiveThumbs
          },
        }
      });
  }

}

jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiMediaCarousel, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-media-carousel-addon.default",
      addHandler
    );
  });

