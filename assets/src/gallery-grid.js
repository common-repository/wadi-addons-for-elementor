import 'isotope-packery';
import Isotope from 'isotope-layout';
import imagesLoaded from 'imagesloaded';

import './style/gallery-grid.scss';


class WadiGalleryGrid extends elementorModules.frontend.handlers.Base {

    getDefaultSettings() {
      return {
        selectors: {
          wadiGalleryGridContainer: '.wadi_gallery_grid_container',
          wadiGalleryGridFilter: '.wadi_gallery_grid_filter',
          wadiGalleryGridFiltersList: '.wadi_gallery_grid_filters_list',
          wadiGalleryGridFilterItem: '.wadi_gallery_grid_filter_item',
          wadiGalleryGrid: '.wadi_gallery_grid',
          wadiGalleryGridItem: '.wadi_gallery_grid_item',
        },
      }
    }
  
  
    getDefaultElements() {
      const selectors = this.getSettings('selectors');
      const elements = {
        $wadiGalleryGridElementorContainer:  this.$element.find(selectors.galleryElement),
        $wadiGalleryGridFilter: this.findElement(selectors.wadiGalleryGridFilter),
        $wadiGalleryGridFiltersList: this.findElement(selectors.wadiGalleryGridFiltersList),
        $wadiGalleryGridFilterItem: this.findElement(selectors.wadiGalleryGridFilterItem),
        $wadiGalleryGrid: this.findElement(selectors.wadiGalleryGrid),
        $wadiGalleryGridItem: this.findElement(selectors.wadiGalleryGridItem),

      };

      return elements;
    }
  
    bindEvents() {
      this.initiate();
    }
  
    initiate() {
      const _this = this;

      const elementsSettings = this.getElementSettings();
      const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
      const selectors = this.getSettings('selectors');


      const wadiGalleryGridSettings = {
        galleryGridWidth: elementsSettings.wadi_gallery_grid_column_width ?  elementsSettings.wadi_gallery_grid_column_width : '50%',
        galleryGridLayoutMode: elementsSettings.wadi_gallery_grid_layout_mode ?  elementsSettings.wadi_gallery_grid_layout_mode : 'masonry',
        galleryGridFirstTab: elementsSettings.wadi_gallery_grid_categories_first_tab ? elementsSettings.wadi_gallery_grid_categories_first_tab : 'all',
        galleryGridActiveCategory: elementsSettings.wadi_gallery_grid_active_category ? elementsSettings.wadi_gallery_grid_active_category : '', 
        galleryGridParamFilter: elementsSettings.wadi_gallery_grid_filter_param ? elementsSettings.wadi_gallery_grid_filter_param : 'cat', 
        galleryGridShuffle: elementsSettings.wadi_gallery_grid_items_shuffle_switecher === 'yes' ? true : false, 
        galleryGridFilterShuffle: elementsSettings.wadi_gallery_grid_items_filter_shuffle_switecher === 'yes' ? true : false,
        galleryGridRepeater: elementsSettings.wadi_gallery_grid_content_repeater ? elementsSettings.wadi_gallery_grid_content_repeater : [],
      }
  

  
      _this.wadiGalleryGrid(wadiGalleryGridSettings);
  
    }

    wadiGalleryGrid(settings) {

      const _this = this;

        const galleryWidthString = settings.galleryGridWidth;
        const galleryWidth = galleryWidthString.replace('%', "");
        const galleryWidthNum =  parseFloat(galleryWidth);
        
        const wadiGalleryGrid =  this.elements.$wadiGalleryGridElementorContainer;
              
        const wadiGalleryGridElementsContainers = document.querySelectorAll('.wadi_gallery_grid_container');

        wadiGalleryGridElementsContainers.forEach((wadiGalleryGridElement, index) => {

          if(wadiGalleryGridElement.querySelector('.wadi_gallery_grid_'+this.$element[0].getAttribute("data-id"))) {
  
              let layoutSettings = {};
              if(settings.galleryGridLayoutMode === 'masonry') {
                  layoutSettings = { 
                    percentPosition: true,
                    masonry: {
                      columnWidth: galleryWidthNum,
                      gutter: 0,
                      gutterWidth: 0,
                      horizontalOrder: true,
                      fitWidth: true,
                  }
                }
              }
              if(settings.galleryGridLayoutMode === 'packery') {
                  layoutSettings = { 
                    percentPosition: true,
                    packery: {
                      columnWidth: galleryWidthNum,
                      gutter: 0,
                      gutterWidth: 0,
                      horizontalOrder: true,
                      fitWidth: true,
                  }
                }
              }
  
  
  
              const iso = new Isotope( wadiGalleryGridElement.querySelector('.wadi_gallery_grid_'+this.$element[0].getAttribute("data-id")), {
                  // options
                  itemSelector: '.wadi_gallery_grid_item',
                  layoutMode: settings.galleryGridLayoutMode,
                  layoutSettings
  
              }); 
  
              // Check isotope Elements
              const elems = iso.getItemElements()

              // End checking isotope Elements
  
              new imagesLoaded(wadiGalleryGridElement.querySelector('.wadi_gallery_grid_'+this.$element[0].getAttribute("data-id")), iso);
  
              // Shuffle on Page Load
              if(settings.galleryGridShuffle) {
                iso.shuffle();
              }
  
              // filter functions
  
          const filtersWadiElem = wadiGalleryGridElement.querySelector('.wadi_gallery_grid_filters_list');
  
              if(filtersWadiElem) {
                const href = window.location.href;

                var url = new URL(href);
                var filterCat = url.searchParams.get(settings.galleryGridParamFilter);
  
  
                const itemsList = filtersWadiElem.querySelectorAll(`[data-filter]`);
                const itemsListArray = Array.from(itemsList);
                  filtersWadiElem.addEventListener( 'click', (event) => filterWadiGridItems( event ) );
                  const filterWadiGridItems = ( event ) => {
                      // only work with buttons that container data-filter attribute
                      if(!event.target.hasAttribute("data-filter")) {
                          return;
                      }
          
                      var filterValue = event.target.getAttribute('data-filter');
                        
                      itemsListArray.forEach(it => it.classList.remove('filter_active'));
  
                      const item = filtersWadiElem.querySelector(`[data-filter="${filterValue}"]`);
                      item.classList.add('filter_active');
  
                      localStorage.setItem('wadiActiveFilter', filterValue);
  
                      if(settings.galleryGridFilterShuffle) {   
                      // Shuffle on Filter Click
                        iso.shuffle();
                      } 
                        iso.arrange({ filter: filterValue });
          
                  }
  
                  if ( '' !== settings.galleryGridActiveCategory ) {
                    const activeCategory = settings.galleryGridActiveCategory;
                    const activeCategoryTrimed = activeCategory.trim().toLowerCase();
                    const cleanActiveCategory = activeCategoryTrimed.replace(" ", "_");
                    if(cleanActiveCategory) {
                      filtersWadiElem.querySelector(`[data-filter=".${cleanActiveCategory}"]`).click();
                    }
                  } else {
                    filtersWadiElem.querySelector(`[data-filter="*"]`).click();
                  }


                  let filterAvailable = [];
                  // if(itemsListArray > 0) {
                    itemsListArray.forEach((item, index) => {
                     filterAvailable.push(item.dataset.filter);
                    })
                  // }



                  if(filterCat !== null && filterCat !== undefined && filterCat &&  filterAvailable.indexOf(`.${filterCat}`) > -1) {
                    filtersWadiElem.querySelector(`[data-filter=".${filterCat}"]`).click();
  
                  } 

                }
          }
        });

        _this.wadiGalleryGridVideos(settings);
    }

    // Wadi Gallery Gird Self Hosted Suppoert Version 1.0.9
    wadiGalleryGridVideos(settings) {

      const _this = this;

      if(settings.galleryGridRepeater.length > 0) {

        const elements = this.getDefaultElements();

        const wadiGalleryGridContainer = elements.$wadiGalleryGrid;
  
        wadiGalleryGridContainer[0].addEventListener('click',(e) => {
          if(e.target.hasAttribute('data-video-type')) {
  
            if('youtube' == e.target.getAttribute('data-video-type') || 'vimeo' == e.target.getAttribute('data-video-type') || 'dailymotion' == e.target.getAttribute('data-video-type') ) {
  
              if(e.target.hasAttribute('data-video-src')) {
                const videoSrcURL = e.target.getAttribute('data-video-src');
  
                _this.wadiVideoIframe(e.target.parentElement,videoSrcURL );
              }
            } else if('self_hosted' == e.target.getAttribute('data-video-type')) {
              const videoSrcURL = e.target.getAttribute('data-video-src');
              const videoParams = e.target.getAttribute('data-self-hosted-settings');
  
              const jsonVideoParams = JSON.parse( videoParams );
              _this.wadiVideoElement(e.target.parentElement, videoSrcURL,jsonVideoParams );
  
            }
          }
        });
      }
      


    }

    wadiVideoIframe(girdItemContainerVid, videoSrcURL) {
      const _this = this;
      let videoIframe = document.createElement('iframe');
      videoIframe.setAttribute('src', videoSrcURL);
      videoIframe.setAttribute('frameborder', '0');
      videoIframe.setAttribute('allowfullscreen', 'allowfullscreen');
      videoIframe.setAttribute('allow', 'autoplay; encrypted-media');
      videoIframe.setAttribute('class', 'wadi_gallery_grid_video_iframe');

      _this.wadiHideItemGridElemsForVideo(girdItemContainerVid);

      // girdItemContainerVid.innerHTML = '';
      // videoIframe.setAttribute('width', '450');
      // videoIframe.setAttribute('height', '650');
      if(girdItemContainerVid.querySelector('iframe') === undefined || girdItemContainerVid.querySelector('iframe') === null)  {
        girdItemContainerVid.appendChild(videoIframe);
      }
    }
    wadiVideoElement(girdItemContainerVid, videoSrcURL, videoParams) {
      const _this = this;
      let videoElement = document.createElement('video');
      videoElement.setAttribute('src', videoSrcURL);
      videoElement.classList.add('wadi_gallery_grid_self_hosted_video');
      
      for (var key in videoParams) {
        if (videoParams.hasOwnProperty(key)) {
          let value = videoParams[key];

          if('muted' !== videoParams['key']) {
            videoElement.setAttribute(key, value);
          }

          if('muted' == videoParams[key]) {
            videoElement.muted = 'muted';
          }

        }
    }

      _this.wadiHideItemGridElemsForVideo(girdItemContainerVid);
      // girdItemContainerVid.innerHTML = '';

      if(girdItemContainerVid.querySelector('video') === undefined || girdItemContainerVid.querySelector('video') === null)  {
        girdItemContainerVid.appendChild(videoElement);
      }
    }

    wadiHideItemGridElemsForVideo(gridItem) {
      let gridItemChildren = gridItem.children;
      let gridItemChildrenArr = [... gridItemChildren];
      gridItemChildrenArr.forEach((child, index) => {
        child.classList.add('hide_grid_item_thumb');
      });
    }
}


jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiGalleryGrid, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-gallery-grid-addon.default",
      addHandler
    );
  });
  
