import Swiper, { EffectFade, EffectCube, EffectFlip, EffectCoverflow, EffectCards, FreeMode, Autoplay, Controller, Mousewheel, Navigation, Pagination, Scrollbar } from '../../node_modules/swiper'

import "./style/carousel.scss";

class WadiCarousel extends elementorModules.frontend.handlers.Base {

  getDefaultSettings() {
    return {
      selectors: {
        wadiSwiper: '.swiper',
      },
    }
  }


  getDefaultElements() {
    const selectors = this.getSettings('selectors');
    const elements = {
      $wadiSwiperElem: this.findElement(selectors.wadiSwiper),
    };

    return elements;
  }

  bindEvents() {
    this.initiate();
  }

  initiate() {
    const _this = this;
    const $wadiSwiperElem = this.elements.wadiSwiperElem;
    const elementsSettings = this.getElementSettings();
    const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
    const selectors = this.getSettings('selectors');

    const wadiSwiperSettings = {
      wadiSwiperElement: ".wadi_carousel_swiper_" + this.$element[0].getAttribute("data-id"),
      carouselDirection: elementsSettings.wadi_carousel_direction,
      carouselDotsNavigation: elementsSettings.wadi_carousel_dots_navigation === 'yes' ? true: false,
      carouselDotsClickable : (elementsSettings.wadi_carousel_dots_navigation === 'yes' && elementsSettings.wadi_carousel_dots_clickable === 'yes') ? true : false,
      carouselNavigationArrows: elementsSettings.wadi_carousel_arrow_navigation === 'yes' ? true : false,
      carouselSlidesPerView: elementsSettings.wadi_carousel_slides_per_view ? elementsSettings.wadi_carousel_slides_per_view : 1,
      carouselSlidesPerViewTablet: elementsSettings.wadi_carousel_slides_per_view_tablet ? elementsSettings.wadi_carousel_slides_per_view_tablet : 1,
      carouselSlidesPerViewMobile: elementsSettings.wadi_carousel_slides_per_view_mobile ? elementsSettings.wadi_carousel_slides_per_view_mobile : 1,
      carouselSpaceBetween: elementsSettings.wadi_carousel_space_between ? elementsSettings.wadi_carousel_space_between : 0,
      carouselSpaceBetweenTablet: elementsSettings.wadi_carousel_space_between_tablet ? elementsSettings.wadi_carousel_space_between_tablet : 0,
      carouselSpaceBetweenMobile: elementsSettings.wadi_carousel_space_between_mobile ? elementsSettings.wadi_carousel_space_between_mobile : 0,
      carouselBreakPoints: {
        desktop: elementsSettings.wadi_carousel_breakpoints || 1024,
        tablet: elementsSettings.wadi_carousel_breakpoints_tablet || 768,
        mobile: elementsSettings.wadi_carousel_breakpoints_mobile || 320,
      },
      carouselAutoHeight: (elementsSettings.wadi_carousel_direction === 'horizontal' && elementsSettings.wadi_carousel_auto_height === 'yes') ? true : false,
      carouselLoop: elementsSettings.wadi_carousel_loop === 'yes' ? true : false,
      carouselMousewheel: elementsSettings.wadi_carousel_mousewheel === 'yes' ? true : false,
      carouselAutoplay: elementsSettings.wadi_carousel_autoplay === 'yes' ? {
        delay: elementsSettings.wadi_carousel_autoplay_delay,
        disableOnInteraction: elementsSettings.wadi_carousel_autoplay_disable_on_interaction === 'yes' ? true : false,
        pauseOnMouseEnter: elementsSettings.wadi_carousel_autoplay_pause_on_mouseenter === 'yes' ? true : false,
        reverseDirection: elementsSettings.wadi_carousel_autoplay_reverse_direction === 'yes' ? true : false,
        stopOnLastSlide: (elementsSettings.wadi_carousel_autoplay_stop_last_slide === 'yes' &&  elementsSettings.wadi_carousel_loop !== 'yes') ? true : false,
      } || true : false,
      carouselSlideEffect: elementsSettings.wadi_carousel_slide_effects || 'slide',
      ... elementsSettings.wadi_carousel_slide_effects === 'coverflow' && {
        carouselCoverflowEffect: {
          rotate: elementsSettings.wadi_carousel_coverflow_effect_rotate || 50,
          slideShadows: elementsSettings.wadi_carousel_coverflow_effect_slide_shadows === 'yes' ? true : false,
          depth: elementsSettings.wadi_carousel_coverflow_effect_depth || 100,
          modifier: elementsSettings.wadi_carousel_coverflow_effect_modifier || 1,
          scale: elementsSettings.wadi_carousel_coverflow_effect_scale || 1,
          stretch: elementsSettings.wadi_carousel_coverflow_effect_stretch || 0,
        }
      },
      ... elementsSettings.wadi_carousel_slide_effects === 'flip' && {
        carouselFlipEffect: {
          slideShadows: elementsSettings.wadi_carousel_flip_effect_slide_shadows === 'yes' ? true : false,
          limitRotation: elementsSettings.wadi_carousel_flip_effect_limit_rotation === 'yes' ? true : false,
        }
      },
      ... elementsSettings.wadi_carousel_slide_effects === 'cube' && {
        carouselCubeEffect: {
          shadow: elementsSettings.wadi_carousel_cube_effect_shadow === 'yes' ? true : false,
          ... elementsSettings.wadi_carousel_cube_effect_shadow && {
            shadowOffset: elementsSettings.wadi_carousel_cube_effect_shadow_offset || 20,
            shadowScale: elementsSettings.wadi_carousel_cube_effect_shadow_scale || 0.94,
            slideShadows: elementsSettings.wadi_carousel_cube_effect_slide_shadows === 'yes' ? true : false,
          }
        }
      },
      ... elementsSettings.wadi_carousel_slide_effects === 'cards' && {
        carouselCardsEffect: {
          slideShadows: elementsSettings.wadi_carousel_cards_effect_slide_shadows === 'yes' ? true : false,
        }
      },
      carouselInitialSlide: elementsSettings.wadi_carousel_initial_slide || 0,
      carouselCentered: elementsSettings.wadi_carousel_centered === 'yes' ? true : false,
      carouselGrabCursor: elementsSettings.wadi_carousel_grab_cursor === 'yes' ? true : false,
      carouselSpeedTransition: elementsSettings.wadi_carousel_speed_transition || 300,
      carouselFreemode: elementsSettings.wadi_carousel_freemode === 'yes' ? {
        enabled: elementsSettings.wadi_carousel_freemode === 'yes' ? true : false,
        minimumVelocity: elementsSettings.wadi_carousel_freemode_minimum_velocity && 0.02,
        momentum: elementsSettings.wadi_carousel_free_mode_momentum === 'yes' ? true : false,
        momentumBounce: elementsSettings.wadi_carousel_freemode_momentum_bounce === 'yes' ? true : false,
        momentumBounceRatio: elementsSettings.wadi_carousel_freemode_momentum_bounce_ratio && 1,
        momentumRatio: elementsSettings.wadi_carousel_freemode_momentum_ratio && 1,
        momentumVelocityRatio: elementsSettings.wadi_carousel_freemode_minimum_velocity_ratio && 1,
        sticky: elementsSettings.wadi_carousel_freemode_sticky === 'yes' ? true : false,
      } || true : false,
      // Pagination and Navigation and Scrollbar
      carouselPaginationType: elementsSettings.wadi_carousel_pagination_type || 'bullets',
      carouselDotsNavigationHideOnClick: elementsSettings.wadi_carousel_dots_pagination_hide_on_click === 'yes' ? true : false,
      carouselProgressbarOpposite: elementsSettings.wadi_carousel_progress_bar_opposite === 'yes' ? false: true,
      // Scrollbar
      carouselScrollbar: elementsSettings.wadi_carousel_scrollbar === 'yes' ? true : false,
      carouselScrollbarDraggable: elementsSettings.wadi_carousel_scrollbar_draggable === 'yes' ? true: false,
      carouselScrollbarHide: elementsSettings.wadi_carousel_scrollbar_hide === 'yes' ? true : false,
      carouselScrollbarSnap: elementsSettings.wadi_carousel_scrollbar_snap === 'yes' ? true : false,
    }

    // console.log(elementsSettings);

    // console.log(elementsSettings.wadi_carousel_slide_effects);
    // console.log(wadiSwiperSettings);

    _this.wadiSwiper($wadiSwiperElem, wadiSwiperSettings);

  }

  wadiSwiper(wadiSwiperElem, settings) {
    let carouselBreakpointMobile = settings.carouselBreakPoints.mobile;
    let carouselBreakpointTablet = settings.carouselBreakPoints.tablet;
    let carouselBreakpointDesktop = settings.carouselBreakPoints.desktop;

    // console.log("THE SETTINGS:", settings);
    const $this = this;
    const wadiSwiperElement = settings.wadiSwiperElement;

    // const elementSwiper = document.querySelector(`${wadiSwiperElement}`);
    // console.log(elementSwiper);


    new Swiper(wadiSwiperElement, {
      modules: [EffectFade, EffectCube, EffectFlip, EffectCoverflow, EffectCards, FreeMode, Autoplay, Controller, Mousewheel, Navigation, Pagination, Scrollbar],
      // Optional parameters
      direction: settings.carouselDirection,
      loop: settings.carouselLoop,
      autoHeight: settings.carouselAutoHeight,
      mousewheel: settings.carouselMousewheel,
      slidesPerView: settings.carouselSlidesPerView,
      autoplay: settings.carouselAutoplay,
      effect: settings.carouselSlideEffect,
      ... settings.carouselSlideEffect === 'fade' && {fadeEffect: {
        crossFade: true
      }},
      ... settings.carouselSlideEffect === 'coverflow' && {coverflowEffect: settings.carouselCoverflowEffect},
      ... settings.carouselSlideEffect === 'flip' && {flipEffect: settings.carouselFlipEffect},
      ... settings.carouselSlideEffect === 'cube' && {cubeEffect: settings.carouselCubeEffect},
      ... settings.carouselSlideEffect === 'cards' && {cardsEffect: settings.carouselCardsEffect},
      initialSlide: settings.carouselInitialSlide,
      centeredSlides: settings.carouselCentered,
      grabCursor: settings.carouselGrabCursor,
      speed: settings.carouselSpeedTransition,
      freeMode: settings.carouselFreemode,

      breakpoints: {
        // when window width is >= 280px
        [carouselBreakpointMobile]: {
            slidesPerView: settings.carouselSlidesPerViewMobile,
            spaceBetween: settings.carouselSpaceBetweenMobile,
          },
          // when window width is >=  768px
          [carouselBreakpointTablet]: {
            slidesPerView: settings.carouselSlidesPerViewTablet,
            spaceBetween: settings.carouselSpaceBetweenTablet,
          },
          // when window width is >= 1024px
          [carouselBreakpointDesktop]: {
            slidesPerView: settings.carouselSlidesPerView,
            spaceBetween: settings.carouselSpaceBetween,
          }
      },
      // If we need pagination
      ... settings.carouselDotsNavigation && {
        pagination: {
        el: `.wadi_carousel_container .swiper-pagination`,
        clickable: settings.carouselDotsClickable,
        type: settings.carouselPaginationType,
        hideOnClick: settings.carouselDotsNavigationHideOnClick,
        ... settings.carouselDirection === 'vertical' && settings.carouselPaginationType === 'progressbar' && {progressbarOpposite: settings.carouselProgressbarOpposite},
      }
    },
    
      // Navigation arrows
      ... settings.carouselNavigationArrows && {
        navigation: {
        nextEl: ".wadi-swiper-button-next",
        prevEl: ".wadi-swiper-button-prev",
      }
    },
    
      // And if we need scrollbar
      ... settings.carouselScrollbar && {
        scrollbar: {
        el: `.wadi_carousel_container .swiper-scrollbar`,
        draggable: settings.carouselScrollbarDraggable,
        hide: settings.carouselScrollbarHide,
        snapOnRelease: settings.carouselScrollbarSnap, 
      }
    },
    });
  }

}

jQuery(window).on("elementor/frontend/init", () => {
  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(WadiCarousel, {
      $element,
    });
  };

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/wadi-carousel-addon.default",
    addHandler
  );
});
