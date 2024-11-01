import Swiper, { EffectFade, EffectFlip, FreeMode, Autoplay, Controller, Mousewheel, Navigation, Pagination, Lazy } from 'swiper';
import './style/testimonial-carousel.scss';


  
class WadiTestimonialCarousel extends elementorModules.frontend.handlers.Base {

        
    getDefaultSettings() {
        return {
        selectors: {
            wadiTestimonialCarousel: '.swiper',
        },
        tableState: {
            isLoaded: false,
        }
        }
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');
        const elements = {
        $wadiTestimonialCarouselElem: this.findElement(selectors.wadiTestimonialCarousel),
        };

        return elements;
    }

    bindEvents() {
        this.initiate();
    }

    initiate(){
        const _this = this;
        const elementsSettings = this.getElementSettings();


        const wadiTestimonialCarousel = {
            WadiTestimonialCarouselContainerWrapper: ".wadi_testimonial_carousel_wrapper_" + this.$element[0].getAttribute("data-id"),
            WadiTestimonialCarouselContainer: ".wadi_testimonial_carousel_container_" + this.$element[0].getAttribute("data-id"),
            wadiTestimonialCarouselSlidesPerView: elementsSettings.wadi_testimonial_carousel_slides_per_view ? elementsSettings.wadi_testimonial_carousel_slides_per_view : 1,
            wadiTestimonialCarouselSlidesPerViewTablet: elementsSettings.wadi_testimonial_carousel_slides_per_view_tablet ? elementsSettings.wadi_testimonial_carousel_slides_per_view_tablet : 1,
            wadiTestimonialCarouselSlidesPerViewMobile: elementsSettings.wadi_testimonial_carousel_slides_per_view_mobile ? elementsSettings.wadi_testimonial_carousel_slides_per_view_mobile : 1,
            wadiTestimonialCarouselSlidesToScroll: elementsSettings.wadi_testimonial_carousel_slides_to_scroll ? elementsSettings.wadi_testimonial_carousel_slides_to_scroll : 1,
            wadiTestimonialCarouselSlidesToScrollTablet: elementsSettings.wadi_testimonial_carousel_slides_to_scroll_tablet ? elementsSettings.wadi_testimonial_carousel_slides_to_scroll_tablet : 1,
            wadiTestimonialCarouselSlidesToScrollMobile: elementsSettings.wadi_testimonial_carousel_slides_to_scroll_mobile ? elementsSettings.wadi_testimonial_carousel_slides_to_scroll_mobile : 1,
            // loop
            wadiTestimonialCarouselLoop: 'yes' === elementsSettings.wadi_testimonial_carousel_loop ? true : false,
            // autoplay
            wadiTestimonialCarouselAutoplay: 'yes' === elementsSettings.wadi_testimonial_carousel_autoplay ? true : false,
            ... 'yes' === elementsSettings.wadi_testimonial_carousel_autoplay && {
                wadiTestimonialCarouselAutoplayDelay: elementsSettings.wadi_testimonial_carousel_autoplay_delay.size || 1000,
                wadiTestimonialCarouselPauseOnHover: 'yes' === elementsSettings.wadi_testimonial_carousel_pause_on_hover ? true : false,
                wadiTestimonialCarouselReverseDirection: 'yes' === elementsSettings.wadi_testimonial_carousel_reverse_direction ? true : false,
            },
            wadiTestimonialCarouselSpeed: elementsSettings.wadi_testimonial_carousel_speed.size ? elementsSettings.wadi_testimonial_carousel_speed.size : 1000,
            wadiTestimonialCarouselMousewheel: 'yes' ===  elementsSettings.wadi_testimonial_carousel_play_on_mousewheel ? true : false,
            wadiTestimonialCarouselPagination: 'yes' === elementsSettings.wadi_testimonial_carousel_pagination ? true: false,
            ... 'yes' === elementsSettings.wadi_testimonial_carousel_pagination && {
                wadiTestimonialCarouselPaginationType: elementsSettings.wadi_testimonial_carousel_pagination_type ? elementsSettings.wadi_testimonial_carousel_pagination_type : 'bullets',
            },

            wadiTestimonialCarouselNavigationArrows: 'yes' === elementsSettings.wadi_testimonial_carousel_arrow_navigation ? true : false,
            wadiTestimonialCarouselLazy: 'yes' === elementsSettings.wadi_testimonial_carousel_lazyload ? true : false,
            
            // Styles
            wadiTestimonialCarouselSpaceBetween: elementsSettings.wadi_testimonial_carousel_slides_space_between.size || 0,
            wadiTestimonialCarouselSpaceBetweenTablet: elementsSettings.wadi_testimonial_carousel_slides_space_between_tablet.size || 0,
            wadiTestimonialCarouselSpaceBetweenMobile: elementsSettings.wadi_testimonial_carousel_slides_space_between_mobile.size || 0,
        }

        _this.wadiTestimonialCarousel(jQuery,wadiTestimonialCarousel);

        
    }

    wadiTestimonialCarousel($,settings){
        console.log("SEttings", settings);
        console.log("settings.wadiTestimonialCarouselPagination" , settings.wadiTestimonialCarouselPagination);
        const wadiTestimonialCarousel = document.querySelector(settings.WadiTestimonialCarouselContainer); 
        console.log("Testimonail CODE JS CAROUSEL");
        new Swiper(wadiTestimonialCarousel, {
            modules: [EffectFade, EffectFlip, FreeMode, Autoplay, Controller, Mousewheel, Navigation, Pagination, Lazy],

            slidesPerView: settings.wadiTestimonialCarouselSlidesPerView,
            slidesPerGroup: settings.wadiTestimonialCarouselSlidesToScroll,
            spaceBetween: settings.wadiTestimonialCarouselSpaceBetween,
            breakpoints: {
                // when window width is >= 280px
                280: {
                    slidesPerView: settings.wadiTestimonialCarouselSlidesPerViewMobile,
                    slidesPerGroup: settings.wadiTestimonialCarouselSlidesToScrollMobile,
                    spaceBetween: settings.wadiTestimonialCarouselSpaceBetweenMobile,
                  },
                  // when window width is >=  768px
                  768: {
                    slidesPerView: settings.wadiTestimonialCarouselSlidesPerViewTablet,
                    slidesPerGroup: settings.wadiTestimonialCarouselSlidesToScrollTablet,
                    spaceBetween: settings.wadiTestimonialCarouselSpaceBetweenTablet,
                  },
                  // when window width is >= 1024px
                    1024: {
                    slidesPerView: settings.wadiTestimonialCarouselSlidesPerView,
                    slidesPerGroup: settings.wadiTestimonialCarouselSlidesToScroll,
                    spaceBetween: settings.wadiTestimonialCarouselSpaceBetween,

                  }
              },

            loop: settings.wadiTestimonialCarouselLoop,
            
            ... settings.wadiTestimonialCarouselAutoplay ? {
                autoplay: {
                    disableOnInteraction: false,
                    delay: settings.wadiTestimonialCarouselAutoplayDelay,
                    pauseOnMouseEnter: settings.wadiTestimonialCarouselPauseOnHover,
                    reverseDirection: settings.wadiTestimonialCarouselReverseDirection,
                }
            }: {
                autoplay: false,
            },
            speed: settings.wadiTestimonialCarouselSpeed,
            // pagination: {
            //     el: '.swiper-pagination',
            //     clickable: true,
            //     type: 'bullets',
            //     hideOnClick: false,
            // },
            // navigation: {
            //     nextEl: '.swiper-button-next',
            //     prevEl: '.swiper-button-prev',
            // },
            // lazy: {
            //     loadPrevNext: true,
            //     loadPrevNextAmount: 2,
            // },
            mousewheel: settings.wadiTestimonialCarouselMousewheel,
            // keyboard: true,
            // grabCursor: true,
            // effect: 'slide',
            // freeMode: true,

            // If we need pagination
            // ...  settings.wadiTestimonialCarouselPagination && {
            // },
            
            pagination: {
                el: `${settings.WadiTestimonialCarouselContainerWrapper} .swiper-pagination`,
                type: settings.wadiTestimonialCarouselPaginationType,
                clickable: true,
                // hideOnClick: settings.carouselDotsNavigationHideOnClick,
                // ... settings.carouselDirection === 'vertical' && settings.carouselPaginationType === 'progressbar' && {progressbarOpposite: settings.carouselProgressbarOpposite},
            },
            // Navigation arrows
            ... settings.wadiTestimonialCarouselNavigationArrows && {
                    navigation: {
                        nextEl: `${settings.WadiTestimonialCarouselContainerWrapper} .wadi-testimonial_carousel_swiper-button-next`,
                        prevEl: `${settings.WadiTestimonialCarouselContainerWrapper} .wadi-testimonial_carousel_swiper-button-prev`,
                    // nextEl: ".wadi-swiper-button-next",
                    // prevEl: ".wadi-swiper-button-prev",
                    }
            },
            ... settings.wadiTestimonialCarouselLazy && {
                preloadImages: false,
                lazy: settings.wadiTestimonialCarouselLazy,
              },
              grabCursor: true,

      
        });
    }
}



jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiTestimonialCarousel, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-testimonial-carousel-addon.default",
      addHandler
    );
  });


console.log("TESTING Testimonial Carousel Wadi Addons");

