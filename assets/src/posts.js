import Isotope from 'isotope-layout';
import imagesLoaded from 'imagesloaded';
import Swiper, { EffectFade, EffectCube, EffectFlip, EffectCoverflow, EffectCards, FreeMode, Autoplay, Controller, Mousewheel, Navigation, Pagination, Scrollbar } from '../../node_modules/swiper'

import './style/posts.scss';

class WadiPosts extends elementorModules.frontend.handlers.Base {

    getDefaultSettings() {
      return {
        selectors: {
          wadiPostsWrapper: '.wadi_posts_content_wrapper',
          wadiPostsContainer: '.wadi_posts_container',
          wadiPostsFilterContainer: '.wadi_posts_filters_container',
          wadiActiveCategory: '.data_filter.filter_active',
          currentPage: '.wadi_posts_pagination_container .page-numbers.current',

        },
        settings: {
          pageNumber: 1,
          count: 2,
          isLoaded: false,
          filterTabsExist: false,
          loadingStatus: false,
        }
      }
    }


    getDefaultElements() {
        const selectors = this.getSettings('selectors');
        const elements = {
          $wadiPostsElementorWrapper:  this.$element.find(selectors.wadiPostsWrapper),
          $wadiPostsElementorContainer:  this.$element.find(selectors.wadiPostsContainer),
          $wadiPostsFilterContainer: this.$element.find(selectors.wadiPostsFilterContainer),
          $wadiActiveCategory:  this.$element.find(selectors.wadiActiveCategory),
        };
  
  
        return elements;
    }

    bindEvents() {
        this.initiate();
    }

    initiate() {
        const _this = this;

      // const $wadiGalleryGridElementorContainer = this.elements.$wadiGalleryGridElementorContainer;
      const elementsSettings = this.getElementSettings();
      const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
      const selectors = this.getSettings('selectors');
      const settings = this.getSettings('settings');


      const wadiPostsSettings = {
        wadiPostsWrapper: selectors.wadiPostsWrapper,
        wadiPostsSwiperElement: selectors.wadiPostsWrapper + '_' + this.$element[0].getAttribute("data-id"),
        wadiPostsContainerElem: selectors.wadiPostsContainer + '_' + this.$element[0].getAttribute("data-id"),
        wadiPageNumber: 1,
        postsColumnsWidth: elementsSettings.wadi_posts_columns ?  elementsSettings.wadi_posts_columns : '50%',
        postsLayout: elementsSettings.wadi_posts_layout ?  elementsSettings.wadi_posts_layout : 'grid',
        postsPaginate: elementsSettings.wadi_posts_enable_pagination ? true : false,
        postsInfinite: elementsSettings.wadi_posts_pagination_select === 'infinite' ? true : false,
        pageNumber: 1,
        /**
         * Posts Carousel Settings
         */
        postsCarouselLayout: elementsSettings.wadi_posts_layout === 'carousel' ?  true : false,
        carouselSpeedTransition: elementsSettings.wadi_posts_carousel_speed_transition || 300,
        // Slides Per View
        postsSlidesPerView: elementsSettings.wadi_posts_carousel_slides_per_view ? elementsSettings.wadi_posts_carousel_slides_per_view : 1,
        postsSlidesPerViewTablet: elementsSettings.wadi_posts_carousel_slides_per_view_tablet ? elementsSettings.wadi_posts_carousel_slides_per_view_tablet : 1,
        postsSlidesPerViewMobile: elementsSettings.wadi_posts_carousel_slides_per_view_mobile ? elementsSettings.wadi_posts_carousel_slides_per_view_mobile : 1,
         // Slides Per Group
        postsCarouselSlidesPerGroup: elementsSettings.wadi_posts_carousel_slides_per_group ? elementsSettings.wadi_posts_carousel_slides_per_group : 1,
        postsCarouselSlidesPerGroupTablet: elementsSettings.wadi_posts_carousel_slides_per_group_tablet ? elementsSettings.wadi_posts_carousel_slides_per_group_tablet : 1,
        postsCarouselSlidesPerGroupMobile: elementsSettings.wadi_posts_carousel_slides_per_group_mobile ? elementsSettings.wadi_posts_carousel_slides_per_group_mobile : 1,

        carouselSpaceBetween: elementsSettings.wadi_posts_carousel_space_between ? elementsSettings.wadi_posts_carousel_space_between : 0,
        carouselSpaceBetweenTablet: elementsSettings.wadi_posts_carousel_space_between_tablet ? elementsSettings.wadi_posts_carousel_space_between_tablet : 0,
        carouselSpaceBetweenMobile: elementsSettings.wadi_posts_carousel_space_between_mobile ? elementsSettings.wadi_posts_carousel_space_between_mobile : 0,
        postsCarouselBreakPoints: {
          desktop: elementsSettings.wadi_posts_carousel_breakpoints || 1024,
          tablet: elementsSettings.wadi_posts_carousel_breakpoints_tablet || 768,
          mobile: elementsSettings.wadi_posts_carousel_breakpoints_mobile || 320,
        },
        postsCarouselAutoHeight:  elementsSettings.wadi_posts_carousel_auto_height === 'yes' ? true : false,
        postsCarouselLoop: elementsSettings.wadi_posts_carousel_loop === 'yes' ? true : false,
        postsCarouselMousewheel: elementsSettings.wadi_posts_carousel_mousewheel === 'yes' ? true : false,
        postsCarouselAutoplay: elementsSettings.wadi_posts_carousel_autoplay === 'yes' ? {
          delay: elementsSettings.wadi_posts_carousel_autoplay_delay,
        } || true : false,

        // Posts Carousel Pagination
        postsCarouselDotsNavigation: elementsSettings.wadi_posts_carousel_dots_navigation === 'yes' ? true: false,
        postsCarouselDotsClickable : elementsSettings.wadi_posts_carousel_dots_clickable === 'yes' ? true : false,
        postsCarouselPaginationType: elementsSettings.wadi_posts_carousel_pagination_type ? elementsSettings.wadi_posts_carousel_pagination_type : 'bullets',
        postsCarouselDotsNavigationHideOnClick: elementsSettings.wadi_posts_carousel_dots_pagination_hide_on_click === 'yes' ? true : false,
        // Posts Arrow Carousel Navigation
        postsCarouselNavigationArrows: elementsSettings.wadi_posts_carousel_arrow_navigation === 'yes' ? true : false,

      }

      this.wadiPosts(wadiPostsSettings, jQuery);
    }

    wadiPosts(settings,$) {
        const selectors = this.getSettings('selectors');
        const theSettings = this.getSettings('settings');
        const WadiSettings = this.getElementSettings();

        const postsColumnsWidthString = settings.postsColumnsWidth;
        const postsColumnsWidth = postsColumnsWidthString.replace('%', "");
        const postsColumnsWidthNum =  parseFloat(postsColumnsWidth);
        const pagination = settings.postsPaginate;
        const infinite = settings.postsInfinite;
        const carousel = settings.postsCarouselLayout;
        const postsFilterContainer = this.$element.find(selectors.wadiPostsFilterContainer);
        const wadiActiveCategoryClass = selectors.wadiActiveCategory;
        const wadiPostsActiveCategory = postsFilterContainer.find('.data_filter.filter_active');

        const _this = this;

        const wadiPostsContainer = document.querySelector(settings.wadiPostsContainerElem);
        const wadiPostsWrapper = document.querySelector(settings.wadiPostsWrapper);

        
        const wadiPostsWrapperArray = document.querySelectorAll('.wadi_posts_content_wrapper');

        wadiPostsWrapperArray.forEach((postsElem, index) => {

          if(postsElem.querySelector('.wadi_posts_container_'+this.$element[0].getAttribute("data-id"))) {
            let layoutSettings = {};
              if(settings.postsLayout === 'masonry') {
                  layoutSettings = { 
                    percentPosition: true,
                    masonry: {
                      columnWidth: postsColumnsWidthNum,
                      gutter: 0,
                      gutterWidth: 0,
                      horizontalOrder: true,
                      fitWidth: true,
                  }
                }
              }


              if(settings.postsLayout === 'masonry' || settings.postsLayout === 'fitRows') {

              const iso = new Isotope( postsElem.querySelector('.wadi_posts_container_'+this.$element[0].getAttribute("data-id")), {
                // options
                itemSelector: '.wadi_single_post_container',
                layoutMode: settings.postsLayout,
                filter: wadiPostsActiveCategory.attr('data-filter'),
                layoutSettings

            }); 
            new imagesLoaded(postsElem.querySelector('.wadi_posts_container_'+this.$element[0].getAttribute("data-id")), iso);

            // this.getWadiPostsFilter(postsElem); // One is enough in the next lines!.
          }

          if( settings.postsLayout === 'grid' || settings.postsLayout === 'masonry' || settings.postsLayout === 'fitRows' ) {
            this.getWadiPostsFilter(postsElem, settings);
          }

          }
        });

        

        if (pagination) {
          _this.wadiPaginatiePosts();
        }

        if (infinite) {
          _this.wadiInfinitePosts();
        }
        if (carousel) {
          console.log("Carousel is Set!");
          _this.WadiCarouselPosts(settings);
        }

        

    }

    getWadiPostsFilter(postsElem, settings) {
      const _this = this;
      const theSettings = this.getSettings('settings');
      const selectors = this.getSettings('selectors');
      const filtersPostsWadiElem = postsElem.querySelector('.wadi_tax_filters');
      // const settings = this.getElementSettings();
      let pageNumber = theSettings.pageNumber;

      // console.log(postsElem);


      const $postsContainer = this.elements.$wadiPostsElementorContainer;
      // const $wadiPostsElementorWrapper = this.elements.$wadiPostsElementorWrapper;
      const $paginationContainer = _this.$element.find('.wadi_posts_pagination_container');


      const activeCategory = this.$element[0].querySelector(selectors.wadiActiveCategory);
      let theActiveCategory;
      if(activeCategory) {
        theActiveCategory = activeCategory.getAttribute('data-filter');
      }
      if(theActiveCategory) {
        // Remove first Character (should be [.] Dot.)
        if(theActiveCategory.includes('.')) {
          theActiveCategory = theActiveCategory.substring(1);
        }
      }


      if(filtersPostsWadiElem) {

        const itemsList = filtersPostsWadiElem.querySelectorAll(`[data-filter]`);
          const itemsListArray = Array.from(itemsList);
          filtersPostsWadiElem.addEventListener( 'click', (event) => filterPostsItems( event ) );

            const filterPostsItems = (event) => {

              if(!event.target.hasAttribute("data-filter")) {
                return;
            }

            const $scope = jQuery( event.target ).closest( '.elementor-widget-wadi-posts-addon' );
            const $postsContainer = $scope.find('.wadi_posts_container');
            const $paginationContainer = $scope.find('.wadi_posts_pagination_container');

            const getWidgetAttributes = $scope.find('.wadi_posts_content_wrapper');


            const args = {
              page_id: getWidgetAttributes[0]?.getAttribute('data-page'),
              widget_id: getWidgetAttributes[0]?.getAttribute("data-id"),
              page_number: theSettings?.pageNumber,
              category: theActiveCategory ? theActiveCategory : '',
            }
            var filterValue = event.target.getAttribute('data-filter');

            
            itemsListArray.forEach(it => it.classList.remove('filter_active'));

            const item = filtersPostsWadiElem.querySelector(`[data-filter="${filterValue}"]`);
            item.classList.add('filter_active');
            
            localStorage.setItem('wadiPostActiveFilter', filterValue);


            // Make Sure Page Number is Set to first one when click on Category Tab
            // pageNumber = 1;
            args.category = filterValue;
            args.page_number = 1;
            pageNumber = args.page_number;
            theSettings.pageNumber = 1;
              // Check if infinite is active
              if(settings?.postsInfinite) {
                theSettings.count = 2;
                // theSettings.pageNumber = theSettings.count;
                theSettings.filterTabsExist = true;
                this.getWadiPostsAjax(args, $postsContainer,$paginationContainer);
              } else {
                this.getWadiPostsAjax(args, $postsContainer,$paginationContainer);
              }

            // if(settings.galleryGridFilterShuffle) {   
            // // Shuffle on Filter Click
            //   iso.shuffle();
            // } 
              // iso.arrange({ filter: filterValue });
            }

      }
    }

      getWadiPostsAjax(args, $postsContainer, $paginationContainer) {
      var _this = this;
      const settings = this.getElementSettings();
      const selectors = this.getSettings('selectors');
      const theSettings = this.getSettings('settings');
      const infinitePosts = settings.wadi_posts_pagination_select === "infinite" ? true : false;
      const paginationPosts = settings.wadi_posts_pagination_select === "numbers" ? true : false;
      const infiniteButtonLoadMore = settings.wadi_posts_pagination_infinite_select === 'wadi_infinite_button' ? true : false;
      const infiniteScroll = settings.wadi_posts_pagination_infinite_select === 'wadi_infinite_scroll' ? true : false;

      
      const activeCategory = this.$element[0].querySelector(selectors.wadiActiveCategory);
      let theActiveCategory;
      if(activeCategory) {
        theActiveCategory = activeCategory.getAttribute('data-filter');
      }
      if(theActiveCategory) {
        // Remove first Character (should be [.] Dot.)
        if(theActiveCategory.includes('.')) {
          theActiveCategory = theActiveCategory.substring(1);
        }
      }

      jQuery.ajax({
          url: WadiAddons.ajaxurl,
          dataType: "json",
          type: "POST",
          data: {
              action: 'wadi_get_posts_query',
              ...args,
              nonce: WadiAddons.nonce,
          },
          success: function (response) {


            // const currentPage = $paginationContainer.attr('data-current');


            var posts = response?.data?.posts,
                paging = response?.data?.paging;


                if(infinitePosts) {
                  theSettings.loadingStatus = true;
                  theSettings.isLoaded = true;
                  if ( theSettings.filterTabsExist && theSettings.pageNumber === 1 ) {

                    $postsContainer.html(posts);
                      $paginationContainer.replaceWith(paging);
                  } else {

                      $postsContainer.append(posts);
                      $paginationContainer.replaceWith(paging);

                  }
                  theSettings.loadingStatus = false;
                } else if(paginationPosts) {
                  $postsContainer.html(posts);
                  $paginationContainer.replaceWith(paging);
                } else {
                  $postsContainer.html(posts);
                }

                
              if($postsContainer) {

                if( 'masonry' === settings.wadi_posts_layout  ||  'fitRows' === settings.wadi_posts_layout ) {

                $postsContainer.imagesLoaded(function () {

                  $postsContainer.isotope('reloadItems');
                  $postsContainer.isotope({
                      itemSelector: '.wadi_single_post_container',
                      layoutMode:  settings.wadi_posts_layout,
                      animate: false
                  });
              });
              }
            }

          // Hide Load More if posts when current page equals total number pages if current page === total pages (One Page only)

            const total = _this.$element.find( '.wadi_posts_pagination_container' ).data( 'max-pages' );
            const current = _this.$element.find( '.wadi_posts_pagination_container' ).data( 'current' );
      
            const infiniteLoadMoreElem = _this.$element.find( `.wadi_posts_pagination_container_${_this?.$element[0]?.getAttribute("data-id")} .wadi_loadmore_infinite` );
      
            if(current === total && infiniteLoadMoreElem && infiniteButtonLoadMore) {
              infiniteLoadMoreElem.hide();
            }

          },
          error: function(error) {
            console.log("ERROR :",error);
          }
        });

    }

    wadiPaginatiePosts() {
      var _this = this;
      let selectors = this.getSettings('selectors');
      const theSettings = this.getSettings('settings');

      const settings = this.getElementSettings();

          jQuery(document).on('click', `.wadi_posts_pagination_container_${this?.$element[0]?.getAttribute("data-id")} .page-numbers`, function (e) {
            const $scope = jQuery( e.target ).closest( '.elementor-widget-wadi-posts-addon' );


            const getWidgetAttributes = $scope.find('.wadi_posts_content_wrapper');

            
            const $postsContainer = $scope.find('.wadi_posts_container_'+getWidgetAttributes[0]?.getAttribute("data-id"));
            const $paginationContainer = $scope.find('.wadi_posts_pagination_container_'+getWidgetAttributes[0]?.getAttribute("data-id"));
            const args = {
              page_id: getWidgetAttributes[0].getAttribute('data-page'),
              widget_id: getWidgetAttributes[0].getAttribute("data-id"),
            } 
      
            e.preventDefault();

            if (jQuery(this).hasClass("current")) return;

            
          const activeCategory = $scope[0].querySelector(selectors.wadiActiveCategory);
          let theActiveCategory;
          if(activeCategory) {
            theActiveCategory = activeCategory.getAttribute('data-filter');
          }
          if(theActiveCategory) {
            // Remove first Character (should be [.] Dot.)
            if(theActiveCategory.includes('.')) {
              theActiveCategory = theActiveCategory.substring(1);
            }
          }


            var currentPage = parseInt($scope.find(selectors.currentPage).html());

            if (jQuery(this).hasClass('next')) {
                theSettings.pageNumber = currentPage + 1;
            } else if (jQuery(this).hasClass('prev')) {
                theSettings.pageNumber = currentPage - 1;
            } else {
                theSettings.pageNumber = jQuery(this).html();
            }

            args.page_number = theSettings.pageNumber;
            args.category = theActiveCategory;

            _this.getWadiPostsAjax(args, $postsContainer, $paginationContainer);

        })
    }

    wadiInfinitePosts() {
      var _this = this,
      selectors = this.getSettings('selectors');
      const theSettings = this.getSettings('settings');
      let pageNumber = theSettings.pageNumber;
      const settings = this.getElementSettings();
      let loadMore = false;
      const loadMoreScroll = settings?.wadi_posts_pagination_infinite_select === 'wadi_infinite_scroll' ? true:false;

      jQuery(document).on('click', `.wadi_posts_pagination_container_${this?.$element[0]?.getAttribute("data-id")} .wadi_loadmore_infinite`, function (e) {
        const $scope = jQuery( e.target ).closest( '.elementor-widget-wadi-posts-addon' );
        const getWidgetAttributes = $scope.find('.wadi_posts_content_wrapper');
     
        const $postsContainer = $scope.find('.wadi_posts_container_'+getWidgetAttributes[0].getAttribute("data-id"));
        const $paginationContainer = $scope.find('.wadi_posts_pagination_container_'+getWidgetAttributes[0].getAttribute("data-id"));
        const args = {
          page_id: getWidgetAttributes[0].getAttribute('data-page'),
          widget_id: getWidgetAttributes[0].getAttribute("data-id"),
        } 

        e.preventDefault();

        const activeCategory = $scope[0].querySelector(selectors.wadiActiveCategory);
          let theActiveCategory;
          if(activeCategory) {
            theActiveCategory = activeCategory.getAttribute('data-filter');
          }
          if(theActiveCategory) {
            // Remove first Character (should be [.] Dot.)
            if(theActiveCategory.includes('.')) {
              theActiveCategory = theActiveCategory.substring(1);
            }
          }

          theSettings.loadingStatus = true;
        // Hide Load More if posts in the begining is less than the total if current page === total pages (One Page only)
        const total = $scope.find( '.wadi_posts_pagination_container' ).data( 'max-pages' );

        const current = $scope.find( '.wadi_posts_pagination_container' ).data( 'current' );

        const infiniteLoadMoreElem = $scope.find( `.wadi_posts_pagination_container_${_this?.$element[0]?.getAttribute("data-id")} .wadi_loadmore_infinite` );

        if(current === total && infiniteLoadMoreElem) {
          infiniteLoadMoreElem.hide();
        }

        if(theSettings.loadingStatus) {
          if( false === loadMoreScroll ) {
            infiniteLoadMoreElem.parent().append("<div class='loader'>Loading...</div>");
          }

          if ( pageNumber < total ) {
            jQuery( this ).hide();
            args.category = theActiveCategory;
            args.page_number = theSettings.count,
    
            _this.getWadiPostsAjax(args,$postsContainer, $paginationContainer);
            theSettings.count++;
            theSettings.pageNumber = theSettings.count;
            theSettings.loadingStatus = false;
          }
        }

      });

      const theWindow = window;
      var windowHeight = jQuery( window ).outerHeight() / 1.25;
      var loading = false;
      var scrollHandling = {
        allow: true,
        reallow: function() {
            scrollHandling.allow = true;
        },
        delay: 600 //(milliseconds) adjust to the highest acceptable value
      };
      
      jQuery(theWindow).on('scroll', function (e) {
        if(loadMoreScroll) {
            if( ( jQuery( window ).scrollTop() + windowHeight ) >= ( _this.$element.find( '.wadi_single_post_container:last' )?.offset()?.top ) ) {
              theSettings.loadingStatus = false;
              
        const activeCategory = _this?.$element[0].querySelector(selectors.wadiActiveCategory);

        let theActiveCategory;
        if(activeCategory) {
          theActiveCategory = activeCategory.getAttribute('data-filter');
        }
        if(theActiveCategory) {
          // Remove first Character (should be [.] Dot.)
          if(theActiveCategory.includes('.')) {
            theActiveCategory = theActiveCategory.substring(1);
          }
        }
        const getWidgetAttributes = _this?.$element.find('.wadi_posts_content_wrapper');
        const wadiSpinner = _this?.$element.find('.wadi_infinite_loader');

        const $postsContainer =_this?.$element.find('.wadi_posts_container_'+getWidgetAttributes[0].getAttribute("data-id"));
        const $paginationContainer =_this?.$element.find('.wadi_infinite_scroll_posts_'+getWidgetAttributes[0].getAttribute("data-id"));
        const loader = _this?.$element.find('.wadi_infinite_loader');
        const wadiPostsLoaderElement = `<div class='wadi_infinite_loader'>Loading...</div>`;
        const args = {
          page_id: getWidgetAttributes[0].getAttribute('data-page'),
          widget_id: getWidgetAttributes[0].getAttribute("data-id"),
        } 

        // Hide Load More if posts in the begining is less than the total if current page === total pages (One Page only)
          const total =_this?.$element.find( '.wadi_posts_pagination_container' ).data( 'max-pages' );

          const current =_this?.$element.find( '.wadi_posts_pagination_container' ).data( 'current' );
          
          const infiniteLoadMoreElem = _this?.$element.find( `.wadi_infinite_scroll_posts_${_this?.$element[0]?.getAttribute("data-id")}` );
          if( ! loading && scrollHandling.allow ) {
            wadiSpinner.hide();
            scrollHandling.allow = false;
			      setTimeout(scrollHandling.reallow, scrollHandling.delay);
          if (  theSettings.count <= total ) {
            args.category = theActiveCategory;
            args.page_number = theSettings.count,
            theSettings.loadingStatus = true;
            _this.getWadiPostsAjax(args,$postsContainer, $paginationContainer);
            wadiSpinner.show();
            theSettings.count++;
            theSettings.pageNumber = theSettings.count;
          }

        }

        
          }
        }
        })


    }

    WadiCarouselPosts(settings) {
      console.log("Posts Swiper Carousel Logic");

      const wadiPostsSwiperElement = settings.wadiPostsSwiperElement;

      let postsCarouselBreakpointMobile = settings.postsCarouselBreakPoints.mobile;
      let postsCarouselBreakpointTablet = settings.postsCarouselBreakPoints.tablet;
      let postsCarouselBreakpointDesktop = settings.postsCarouselBreakPoints.desktop;
      console.log("Posts Settings", settings);

      console.log("wadiPostsSwiperElement", wadiPostsSwiperElement);
        new Swiper(wadiPostsSwiperElement, {
          modules: [EffectFade, EffectCube, EffectFlip, EffectCoverflow, EffectCards, FreeMode, Autoplay, Controller, Mousewheel, Navigation, Pagination, Scrollbar],
          direction: 'horizontal',
          loop: settings.postsCarouselLoop,
          speed: settings.carouselSpeedTransition,
          slidesPerView: settings.postsSlidesPerView, //Columns Number
          autoHeight: settings.postsCarouselAutoHeight,
          mousewheel: settings.postsCarouselMousewheel,
          autoplay: settings.postsCarouselAutoplay,
          slidesPerGroup: settings.postsCarouselSlidesPerGroup,

          breakpoints: {
            // when window width is >= 280px
            [postsCarouselBreakpointMobile]: {
                slidesPerView: settings.postsSlidesPerViewMobile,
                spaceBetween: settings.carouselSpaceBetweenMobile,
              },
              // when window width is >=  768px
              [postsCarouselBreakpointTablet]: {
                slidesPerView: settings.postsSlidesPerViewTablet,
                spaceBetween: settings.carouselSpaceBetweenTablet,
              },
              // when window width is >= 1024px
              [postsCarouselBreakpointDesktop]: {
                slidesPerView: settings.postsSlidesPerView,
                spaceBetween: settings.carouselSpaceBetween,
              }
          },

          /**
           * Posts Carousel Pagination
           */
          ...(settings.postsCarouselDotsNavigation && {
              pagination: {
              el: ".swiper-pagination",
              clickable: settings.postsCarouselDotsClickable,
              type: settings.postsCarouselPaginationType,
              hideOnClick: settings.postsCarouselDotsNavigationHideOnClick,
            },
          }),

          // Navigation arrows
            ... settings.postsCarouselNavigationArrows && {
              navigation: {
              nextEl: ".wadi-posts_carousel_swiper-button-next",
              prevEl: ".wadi-posts_carousel_swiper-button-prev",
            }
          },
          // navigation: {
          //   nextEl: '.swiper-button-next',
          //   prevEl: '.swiper-button-prev',
          // },
        
          // And if we need scrollbar
          // scrollbar: {
          //   el: '.swiper-scrollbar',
          // },

        }
      );

    }


}
  

jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiPosts, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-posts-addon.default",
      addHandler
    );
  });