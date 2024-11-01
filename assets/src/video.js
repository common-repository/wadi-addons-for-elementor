import Plyr from 'plyr';

// const player = new Plyr('#player');

import './style/video.scss';

class WadiVideo extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
          selectors: {
            wadiVideoContainer: '#wadi_video_container_'+this.$element[0].getAttribute("data-id"),
            wadiVideoWrapper: '.wadi_video_wrapper_'+this.$element[0].getAttribute("data-id"),
          },
        }
      }
    
      getDefaultElements() {
        const selectors = this.getSettings('selectors');
        const elements = {
          wadiVideoWrapper: this.findElement(selectors.wadiVideoWrapper),
        };
    
        return elements;
      }
    
      bindEvents() {
        this.initiate();
      }
    
      initiate() {
        const _this = this;
        const $wadiVideoWrapper = this.elements.wadiVideoWrapper;
        const elementsSettings = this.getElementSettings();
        const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
        const selectors = this.getSettings('selectors');

        const wadiVideoSettings = {
          wadiVideoContainer: '#wadi_video_container_'+this.$element[0].getAttribute("data-id"),
          wadiVideoWrapper: '.wadi_video_wrapper_'+this.$element[0].getAttribute("data-id"),
          wadiVideoType: elementsSettings.wadi_video_type ? elementsSettings.wadi_video_type : 'youtube',
          ...'self_hosted' === elementsSettings.wadi_video_type && {
            wadiSelfHostedVideoURL: elementsSettings.self_hosted_url ? elementsSettings.self_hosted_url : '',
            wadiSelfHostedVideoRemoteURL: elementsSettings.self_hosted_remote_url ? elementsSettings.self_hosted_remote_url : '',
            wadiSelfHostedStart: elementsSettings.wadi_video_self_hosted_start ? elementsSettings.wadi_video_self_hosted_start : 0,
            wadiSelfHostedEnd: elementsSettings.wadi_video_self_hosted_end && elementsSettings.wadi_video_self_hosted_end,
            wadiSelfHostedAutoplay: 'yes' === elementsSettings.wadi_video_self_hosted_autoplay && 'autoplay',
            wadiSelfhostedControls: 'yes' === elementsSettings.wadi_video_self_hosted_controls && 'controls',
            wadiSelfHostedLoop: 'yes' === elementsSettings.wadi_video_self_hosted_loop && 'loop',
            wadiSelfHostedMuted: 'yes' === elementsSettings.wadi_video_self_hosted_muted && 'muted',
            wadiSelfHostedPoster: elementsSettings.wadi_video_self_hosted_poster ? elementsSettings.wadi_video_self_hosted_poster : '',
          },
          wadiVideoLightboxSwitch: 'yes' === elementsSettings.wadi_video_lightbox_switch ? true : false,
          wadiYoutubeVideoAutoplay: 'yes' === elementsSettings.wadi_youtube_video_autoplay ? true : false,
          wadiVimeoVideoAutoplay: 'yes' === elementsSettings.wadi_vimeo_video_autoplay ? true : false,
          wadiDailymotionVideoAutoplay: 'yes' === elementsSettings.wadi_dailymotion_video_autoplay ? true : false,
          wadiVideoSticky: 'yes' === elementsSettings.wadi_video_sticky_switch ? true : false,
          wadiStickyHideOn: elementsSettings.wadi_sticky_hide_on ? elementsSettings.wadi_sticky_hide_on : [],
          wadiVideoCloseSticky: 'yes' === elementsSettings.wadi_video_close_sticky ? true : false,
        };

        _this.wadiVideo(wadiVideoSettings);
      }

      wadiVideo(settings) {
        const _this = this;
        const $wadiVideoWrapper = this.elements.wadiVideoWrapper;
        const elementsSettings = this.getElementSettings();
        const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
        const selectors = this.getSettings('selectors');
        const isEditMode = elementorFrontend.isEditMode();
        let isSticky = false;


        const videoContainer = document.querySelector(settings.wadiVideoContainer);

        const outerContainerVideo = videoContainer.querySelector('.video_outer_container_'+this.$element[0].getAttribute("data-id"));

        let theVideoWrapper = videoContainer.querySelector(settings.wadiVideoWrapper);

        
        let videoIframe;
        let videoElem;
        let wadiVideoWaypoint;
        // let viewport = jQuery(videoContainer).data('vsticky-viewport');

        const getViewport = videoContainer.getAttribute('data-vsticky-viewport');

        if(settings.wadiVideoSticky) {
          isSticky = true;
          
          if( typeof elementorFrontend.waypoint !== 'undefined') {
            // viewport = getViewport ? getViewport : viewport;
            wadiVideoWaypoint = elementorFrontend.waypoint(
              jQuery(settings.wadiVideoContainer),
              function ( direction ) {
                if(direction === 'down') {
                  jQuery(theVideoWrapper).addClass('wadi_video_outer_container_sticky').removeClass('wadi_video_outer_sticky_hidden');
                  
                } else {
                  jQuery(theVideoWrapper).removeClass('wadi_video_outer_container_sticky').addClass('wadi_video_outer_sticky_hidden');
                }
              },
              {
                offset: getViewport + '%',
                triggerOnce: false,
              }
              );
            }
            
            
          _this.wadiVideoStickyDraggable(theVideoWrapper);
          window.addEventListener('scroll', () => _this.wadiVideoStickyDraggable(theVideoWrapper));
          window.addEventListener( 'resize', function( e ) {
            _this.wadiCheckResize( theVideoWrapper,settings,wadiVideoWaypoint );
          } );

          _this.wadiToggleStickyBreakpoints(theVideoWrapper,settings,wadiVideoWaypoint);
          _this.wadiCloseStickyButton(theVideoWrapper,settings,wadiVideoWaypoint);

            
        }
        


        if(!settings.wadiVideoLightboxSwitch) {
          if('youtube' === settings.wadiVideoType || 'vimeo' === settings.wadiVideoType || 'dailymotion' === settings.wadiVideoType ) {
              
            let youtubeVideoLink = theVideoWrapper.dataset.src;

            if('youtube' === settings.wadiVideoType && !settings.wadiYoutubeVideoAutoplay) {

              outerContainerVideo.addEventListener('click',(e) => {
                  if(e.target.closest('.video_outer_container')) {
                  e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_video_thumb').classList.add('hide_video_thumb');
                  this.wadiVideoIframe(outerContainerVideo, theVideoWrapper);
                }
              });
            } else if(settings.wadiYoutubeVideoAutoplay && 'youtube' === settings.wadiVideoType) {
              
                  outerContainerVideo.querySelector('.wadi_video_thumb').classList.add('hide_video_thumb');
                  this.wadiVideoIframe(outerContainerVideo, theVideoWrapper);

            }

            if('vimeo' === settings.wadiVideoType && !settings.wadiVimeoVideoAutoplay) {
                outerContainerVideo.addEventListener('click',(e) => {
                  if(e.target.closest('.video_outer_container')) {
                  e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_video_thumb').classList.add('hide_video_thumb');
                  if(e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_vimeo_wrap')) {
                    e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_vimeo_wrap').classList.add('hide_vimeo_headers_wrap');
                  }
                  this.wadiVideoIframe(outerContainerVideo, theVideoWrapper);
                }
              });
            } else if(settings.wadiVimeoVideoAutoplay && 'vimeo' === settings.wadiVideoType) {
              outerContainerVideo.querySelector('.wadi_video_thumb').classList.add('hide_video_thumb');
              if(outerContainerVideo.querySelector('.wadi_vimeo_wrap')) {
                outerContainerVideo.querySelector('.wadi_vimeo_wrap').classList.add('hide_vimeo_headers_wrap');
              }
              this.wadiVideoIframe(outerContainerVideo, theVideoWrapper);
            }

            if('dailymotion' === settings.wadiVideoType && !settings.wadiDailymotionVideoAutoplay) {
                outerContainerVideo.addEventListener('click',(e) => {
                  if(e.target.closest('.video_outer_container')) {
                  e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_video_thumb').classList.add('hide_video_thumb');
                  this.wadiVideoIframe(outerContainerVideo, theVideoWrapper);
                }
              });
            } else if(settings.wadiDailymotionVideoAutoplay && 'dailymotion' === settings.wadiVideoType) {
              outerContainerVideo.querySelector('.wadi_video_thumb').classList.add('hide_video_thumb');
              this.wadiVideoIframe(outerContainerVideo, theVideoWrapper);
            }

            
  
          } else if('self_hosted' === settings.wadiVideoType) {
            if(settings.wadiSelfHostedVideoURL) {
              if(!settings.wadiSelfHostedAutoplay) {
                outerContainerVideo.addEventListener('click', (e) => {
                  if(e.target.closest('.video_outer_container')) {
                    e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_video_thumb').classList.add('hide_video_thumb');
                    e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_self_hosted_video_container').classList.add('show_video');
                    e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_self_hosted_video_container').querySelector('video').play();
                  }
                })

              } else if(settings.wadiSelfHostedAutoplay) {
                outerContainerVideo.querySelector('.wadi_video_thumb').classList.add('hide_video_thumb');
                outerContainerVideo.querySelector('.wadi_self_hosted_video_container').classList.add('show_video');
                outerContainerVideo.querySelector('.wadi_self_hosted_video_container').querySelector('video').play();
                outerContainerVideo.querySelector('.wadi_self_hosted_video_container').querySelector('video').muted = true;

              }
            }  else if(settings.wadiSelfHostedVideoRemoteURL) {

              if(!settings.wadiSelfHostedAutoplay) {
                outerContainerVideo.addEventListener('click', (e) => {
                  if(e.target.closest('.video_outer_container')) {
                    e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_video_thumb').classList.add('hide_video_thumb');
                    e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_self_hosted_video_container').classList.add('show_video');
                    e.target.closest(settings.wadiVideoContainer).querySelector('.wadi_self_hosted_video_container').querySelector('video').play();
                  }
                })
              } else if(settings.wadiSelfHostedAutoplay) {
                outerContainerVideo.querySelector('.wadi_video_thumb').classList.add('hide_video_thumb');
                outerContainerVideo.querySelector('.wadi_self_hosted_video_container').classList.add('show_video');
                outerContainerVideo.querySelector('.wadi_self_hosted_video_container').querySelector('video').play();
                outerContainerVideo.querySelector('.wadi_self_hosted_video_container').querySelector('video').muted = true;
              }

            }
          }
        }


        if(settings.wadiVideoLightboxSwitch) {

          outerContainerVideo.setAttribute('data-lightbox', 'yes');
          if('youtube' === settings.wadiVideoType || 'vimeo' === settings.wadiVideoType) {

            outerContainerVideo.setAttribute('data-elementor-lightbox-video', theVideoWrapper.dataset.src);
          } else if('self_hosted' === settings.wadiVideoType) {
            const elementorLightboxData = outerContainerVideo.getAttribute('data-elementor-lightbox');
            const elementorLightboxDataSettings = JSON.parse(elementorLightboxData);
            let elementorLightboxSelfHostedVideoParams = elementorLightboxDataSettings.videoParams;
            outerContainerVideo.addEventListener('click', () => {
              setTimeout(() => {
              
                const lightboxContainerSelfHosted = document.querySelector(`#elementor-lightbox-${this.$element[0].getAttribute("data-id")}`);
                lightboxContainerSelfHosted.querySelector('video').muted = elementorLightboxSelfHostedVideoParams?.muted === 'muted' ? true : false;

              }, 50);
            })
            if(settings.wadiSelfHostedVideoURL) {
              outerContainerVideo.setAttribute('data-elementor-lightbox-video', videoElem);

            } else if(settings.wadiSelfHostedVideoRemoteURL) {
              outerContainerVideo.setAttribute('data-elementor-lightbox-video', videoElem);
            }
          }

        }

      }

      wadiVideoIframe(outerContainerVideo, theVideoWrapper) {
        let videoIframe = document.createElement('iframe');
        videoIframe.setAttribute('src', theVideoWrapper.dataset.src);
        videoIframe.setAttribute('frameborder', '0');
        videoIframe.setAttribute('allowfullscreen', 'allowfullscreen');
        videoIframe.setAttribute('allow', 'autoplay; encrypted-media');
        videoIframe.setAttribute('class', 'wadi_video_iframe');

        // videoIframe.setAttribute('width', '450');
        // videoIframe.setAttribute('height', '650');
        if(outerContainerVideo.querySelector('iframe') === undefined || outerContainerVideo.querySelector('iframe') === null)  {
          outerContainerVideo.appendChild(videoIframe);
        }
      }

        wadiVideoStickyDraggable(theVideoWrapper) {
          if( theVideoWrapper.classList.contains('wadi_video_outer_container_sticky') ) {
          jQuery('.wadi_video_inner_wrapper_'+this.$element[0].getAttribute("data-id")).draggable({
            start: function() {
              jQuery( this ).css({ transform: "none", top: jQuery( this ).offset().top + "px", left: jQuery( this ).offset().left + "px" });
            },
            containment: 'window'
          });
        }
      }

      wadiCheckResize( videoWrapper, settings, wadiVideoWaypoint ) {

        this.wadiToggleStickyBreakpoints(videoWrapper, settings, wadiVideoWaypoint);
      }

      wadiDisableSticky( videoWrapper ,wadiVideoWaypoint ) {
        wadiVideoWaypoint[0].disable();
        videoWrapper.classList.remove( 'wadi_video_outer_container_sticky' );
      }

      wadiToggleStickyBreakpoints( videoWrapper, settings, wadiVideoWaypoint ) {
        const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
        const stickyHideOn = settings.wadiStickyHideOn;
        if(stickyHideOn.length > 0 && stickyHideOn.includes(currentDeviceMode)) {
          this.wadiDisableSticky( videoWrapper, wadiVideoWaypoint );
        } else {
          wadiVideoWaypoint[0].enable();
        }
      }

      wadiCloseStickyButton(videoWrapper, settings, wadiVideoWaypoint) {
        let _this = this;
        if(settings.wadiVideoCloseSticky) {
         videoWrapper.querySelector('.wadi_close_sticky_container').addEventListener('click', (e) => {
            if(e.target.closest('.wadi_close_sticky_container')) {
             _this.wadiDisableSticky( videoWrapper, wadiVideoWaypoint );
           }
         })
        }
      }

}



jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiVideo, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-video-addon.default",
      addHandler
    );
  });


console.log("Video Widget Wadi aDdons for elementor")