import lottie from "lottie-web";
import "./style/lottie-animation.scss";

class WadiLottieAnimation extends elementorModules.frontend.handlers.Base {
  getDefaultSettings() {
    return {
      selectors: {
        wadiLottieAnimationContainer: '.wadi_lottie_animation_container',
        wadiLottieAnimationElement: '.wadi_lottie_animation_element',
      },
    };
  }

  getDefaultElements() {}

  bindEvents() {
    this.initiate();
  }
  initiate() {
    const _this = this;

    const elementsSettings = this.getElementSettings();
    const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
    const selectors = this.getSettings("selectors");

    // Lottie Animation Loop
    const loop =  ('yes' === elementsSettings.lottie_animation_loop && elementsSettings.lottie_animation_loop_times > 0) ? 
    elementsSettings.lottie_animation_loop_times
    : ('yes' === elementsSettings.lottie_animation_loop && !elementsSettings.lottie_animation_loop_times) ? 
        true
       : false;

       const lottieAnimationContainerSelector =  selectors.wadiLottieAnimationContainer + '_'+ this.$element[0].getAttribute("data-id");
       const lottieAnimationElementSelector =  selectors.wadiLottieAnimationElement + '_'+ this.$element[0].getAttribute("data-id");

    const wadiLottieAnimationSettings = {
        wadiLottieAnimationContainer: lottieAnimationContainerSelector,
        wadiLottieAnimationElement: lottieAnimationElementSelector,
        sectionParent: document.querySelector(lottieAnimationContainerSelector).closest('.elementor-section'),
        columnParent: document.querySelector(lottieAnimationContainerSelector).closest('.elementor-column'),
        wadiLottieAnimationFileSource: elementsSettings.lottie_animation_src_type ? elementsSettings.lottie_animation_src_type : 'media_file',
        ... 'media_file' === elementsSettings.lottie_animation_src_type && {
            wadiLottieJSONFileMedia: elementsSettings.lottie_animation_file ?  elementsSettings.lottie_animation_file : '',
        },
        ... 'external_url' === elementsSettings.lottie_animation_src_type && {
            wadiLottieJSONExternalUrl: elementsSettings.lottie_animation_external_url ?  elementsSettings.lottie_animation_external_url : ''
        },
        wadiLottieLink: elementsSettings.lottie_animation_link ? elementsSettings.lottie_animation_link : '',
        wadiLottieAnimationLoop: loop,
        // wadiLottieAnimationAutplay: 'yes' === elementsSettings.lottie_animation_autoplay ? true : false,
        wadiLottieAnimationSpeed: elementsSettings.lottie_animation_speed ?  elementsSettings.lottie_animation_speed : 1,
        wadiLottieAnimationTriggerOn: elementsSettings.wadi_lottie_animation_trigger_on ? elementsSettings.wadi_lottie_animation_trigger_on : 'load',
        ... 'hover' === elementsSettings.wadi_lottie_animation_trigger_on && {
          wadiLottieAnimationHoverOut: elementsSettings.wadi_lottie_animation_hover_out ? elementsSettings.wadi_lottie_animation_hover_out : 'default',
          wadiLottieAnimationHoverArea: elementsSettings.wadi_lottie_animation_hover_area ? elementsSettings.wadi_lottie_animation_hover_area : 'container',
        },
        ... 'viewport' === elementsSettings.wadi_lottie_animation_trigger_on && {
          wadiLottieAnimationViewportScales: elementsSettings.wadi_lottie_animation_viewport  && elementsSettings.wadi_lottie_animation_viewport,
          wadiLottieAnimationViewportKeep: 'yes' === elementsSettings.wadi_lottie_animation_viewport_keep_animation ? true : false,
          wadiLottieAnimationViewportMargin: elementsSettings.wadi_lottie_animation_viewport_margin ? elementsSettings.wadi_lottie_animation_viewport_margin : '0px',
        },
        ... 'scroll' === elementsSettings.wadi_lottie_animation_trigger_on && {
          wadiLottieAnimationRelativeTo: elementsSettings.wadi_lottie_animation_scroll_relative_to ? elementsSettings.wadi_lottie_animation_scroll_relative_to : 'lottie',
          wadiLottieAnimationScrollTriggerHook: elementsSettings.wadi_lottie_animation_scroll_view_trigger && elementsSettings.wadi_lottie_animation_scroll_view_trigger,
        },
        ... 'click' === elementsSettings.wadi_lottie_animation_trigger_on && {
          wadiLottieAnimationLinkTimeout: elementsSettings.wadi_lottie_animation_link_timeout ? elementsSettings.wadi_lottie_animation_link_timeout : 0,
        },

        // Reverse Lottie Animation
        wadiLottieAnimationReverse: 'yes' === elementsSettings.lottie_animation_reverse ? true : false,
        // SVG Render Type
        wadiLottieAnimationRenderType: elementsSettings.wadi_lottie_animation_render_type ? elementsSettings.wadi_lottie_animation_render_type : 'svg',
        wadiLottieAnimationLazyLoading: 'yes' === elementsSettings.lottie_animation_lazy ? true : false,
    };

    _this.createLottieAnimation(wadiLottieAnimationSettings);
  }

  wadiLottieAnimation(settings) {
    const _this = this;

    let lottieItem = null;
    lottieItem = lottie.loadAnimation({
      container: document.querySelector(settings.wadiLottieAnimationElement), // the dom element that will contain the animation
      renderer: settings.wadiLottieAnimationRenderType,
      loop: settings.wadiLottieAnimationLoop,
      autoplay: false,
      // ... settings.wadiLottieAnimationAutplay ? {
      // autoplay: true
      // }: {autoplay: false},
      ... settings.wadiLottieAnimationFileSource === 'media_file' && 
      {
          path: settings.wadiLottieJSONFileMedia.url
      },
      ... settings.wadiLottieAnimationFileSource === 'external_url' &&
      {
          path: settings.wadiLottieJSONExternalUrl
      },
  });

    if(settings.wadiLottieAnimationTriggerOn === 'load') {
      _this.setLottieAnimationReverse(lottieItem, settings);
      _this.setLottieAnimationSpeed(lottieItem, settings);
      lottieItem.play();
    }

    else if(settings.wadiLottieAnimationTriggerOn === 'click') {
      _this.setLottieAnimationReverse(lottieItem, settings);
      _this.setLottieAnimationSpeed(lottieItem, settings);
      document.querySelector(settings.wadiLottieAnimationContainer).addEventListener('click', function(e) {
        lottieItem.play();
        e.preventDefault();
        _this.wadiLottieAnimationClickTimeout(settings);
      });
    }

    else if(settings.wadiLottieAnimationTriggerOn === 'hover') {
      if('container' === settings.wadiLottieAnimationHoverArea) {
        _this.hoverLottieAnimation(lottieItem, settings, document.querySelector(settings.wadiLottieAnimationContainer));
      } else if( 'column'  === settings.wadiLottieAnimationHoverArea ) {
        _this.hoverLottieAnimation(lottieItem, settings, settings.columnParent);
      } else if( 'section'  === settings.wadiLottieAnimationHoverArea ) {
        _this.hoverLottieAnimation(lottieItem, settings, settings.sectionParent);
      }
      
    }

    else if(settings.wadiLottieAnimationTriggerOn === 'viewport') {
      const wadiLottieAnimationContainer = document.querySelector(settings.wadiLottieAnimationContainer);
      const thresholdStart = settings.wadiLottieAnimationViewportScales.sizes.start / 100;
      const thresholdEnd = settings.wadiLottieAnimationViewportScales.sizes.end / 100;

      const offsetRootMargin = settings.wadiLottieAnimationViewportMargin;
      
      const observer = new IntersectionObserver(function(entries) {
        _this.setLottieAnimationReverse(lottieItem, settings);
        _this.setLottieAnimationSpeed(lottieItem, settings);
        entries.forEach(entry => {
          if(entry.isIntersecting) {
            lottieItem.play();
          } else if( settings.wadiLottieAnimationViewportKeep === false ) {
            lottieItem.pause();
          }
        });
      }
      , {
        root: null,
        rootMargin: `${offsetRootMargin.size}px`,
        threshold: [thresholdStart, thresholdEnd]
      });
      observer.observe(wadiLottieAnimationContainer);

    } else if('scroll' === settings.wadiLottieAnimationTriggerOn) {

        if('lottie' === settings.wadiLottieAnimationRelativeTo) {
          _this.animateLottieOnScroll(lottieItem, settings);
        } else if( 'window' === settings.wadiLottieAnimationRelativeTo ) {
          _this.animateLottieOnDocumentScroll(lottieItem, settings);
        } 
        // else if ( 'column' === settings.wadiLottieAnimationRelativeTo ) {
        //   _this.animateLottieOnColumnScroll(lottieItem, settings);
        // } else if ( 'section' === settings.wadiLottieAnimationRelativeTo) {
        //   _this.animateLottieOnSectionScroll(lottieItem, settings);
        // }
      
    }
    
  }

  animateLottieOnScroll(lottieItem, settings) {

    const controller = new ScrollMagic.Controller();

    lottieItem.addEventListener('DOMLoaded', async (e) => {

      const lottieElement = document.querySelector(settings.wadiLottieAnimationElement);

      const parent = lottieElement.parentElement;
      // var rect = parent.getBoundingClientRect();
      // var lottieHeight = rect.height;
      const elementHeight = parent.offsetHeight;

      const lottieTriggerHook = settings.wadiLottieAnimationScrollTriggerHook.size / 100;

      const obj = {
        triggerElement: parent,
        triggerHook: lottieTriggerHook,
        offset: 0,
        duration: elementHeight,
      }

      this.lottieScrollMagic(obj, lottieItem, controller);
    
    });

  }

  animateLottieOnDocumentScroll(lottieItem, settings) {
    const controller = new ScrollMagic.Controller();
    
    lottieItem.addEventListener('DOMLoaded', async (e) => {

      const body = document.body,
      html = document.documentElement;
      const lottieTriggerHook = settings.wadiLottieAnimationScrollTriggerHook.size / 100;

      const documentHeight = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );

      const obj = {
        triggerElement: body.document,
        triggerHook: lottieTriggerHook,
        offset: 0,
        duration: documentHeight,
      }

      this.lottieScrollMagic(obj, lottieItem, controller);

    });

  }

  // // Lottie Animation on Column Scroll

  // animateLottieOnColumnScroll(lottieItem, settings) {
  //   const controller = new ScrollMagic.Controller();
    
  //   lottieItem.addEventListener('DOMLoaded', async (e) => {

  //     const lottieColumn = settings.columnParent;


  //     const lottieColumnHeight = lottieColumn.offsetHeight; // For Duration
  //     const lottieTriggerHook = settings.wadiLottieAnimationScrollTriggerHook.size / 100;

  //     console.log("lottieColumnHeight", lottieColumnHeight);

            
  //     const obj = {
  //       triggerElement: lottieColumn,
  //       triggerHook: lottieTriggerHook,
  //       offset: 0,
  //       duration: lottieColumnHeight,
  //     }

  //     this.lottieScrollMagic(obj, lottieItem, controller);

  //   });

  // }


  // // Lottie Animation on Section Scroll
  // animateLottieOnSectionScroll(lottieItem, settings) {
  //   const controller = new ScrollMagic.Controller();
    
  //   lottieItem.addEventListener('DOMLoaded', async (e) => {

  //     const lottieSection = settings.sectionParent;


  //     const lottieSectionHeight = lottieSection.offsetHeight; // For Duration
  //     const lottieTriggerHook = settings.wadiLottieAnimationScrollTriggerHook.size / 100;



  //     const obj = {
  //       triggerElement: lottieSection,
  //       triggerHook: lottieTriggerHook,
  //       offset: 0,
  //       duration: lottieSectionHeight,
  //     }

  //     this.lottieScrollMagic(obj, lottieItem, controller);


  //   });

  // }

  lottieScrollMagic(obj, lottieItem, controller) {
    const scene = new ScrollMagic.Scene({
      /* Animation will be linked to the scroll progress of its container, starting when the top of container is halfway through viewport and ending when bottom of container is halfway through viewport */
      triggerElement: obj.triggerElement,
      triggerHook: obj.triggerHook,
      offset: obj.offset,
      duration: obj.duration,
    })
    .addTo(controller)
    // .addIndicators() /* Debugging tool to see where an  d when animations occur */
    .on("progress", function (e) {
      const scrollFrame = e.progress * (lottieItem.totalFrames - 1);
      lottieItem.goToAndStop(scrollFrame, true);
    });

  }


  hoverLottieAnimation(lottieItem, settings, element) {
    const _this = this;
    if(settings.wadiLottieAnimationHoverOut === 'default') {
      _this.setLottieAnimationReverse(lottieItem, settings);
      _this.setLottieAnimationSpeed(lottieItem, settings);
      element.addEventListener('mouseenter', function(e) {
        lottieItem.play();
      });
      element.addEventListener('mouseleave', function(e) {
        return;
      });

    }

    if('pause' === settings.wadiLottieAnimationHoverOut) {
      _this.setLottieAnimationSpeed(lottieItem, settings);
      _this.setLottieAnimationReverse(lottieItem, settings);

      element.addEventListener('mouseenter', function(e) {
        // _this.setLottieAnimationReverse(lottieItem, settings);
        // _this.setLottieAnimationSpeed(lottieItem, settings);
        lottieItem.play();
      });
      element.addEventListener('mouseleave', function(e) {
        lottieItem.pause();
      });
    } else if('stop' === settings.wadiLottieAnimationHoverOut) {
      _this.setLottieAnimationReverse(lottieItem, settings);
      _this.setLottieAnimationSpeed(lottieItem, settings);
      element.addEventListener('mouseenter', function(e) {
        lottieItem.play();
      });
      element.addEventListener('mouseleave', function(e) {
        lottieItem.stop();
      });
    }
    else if('reverse' === settings.wadiLottieAnimationHoverOut) {
      _this.setLottieAnimationSpeed(lottieItem, settings);
      element.addEventListener('mouseenter', function(e) {
        // Make it go back to normal direction
        lottieItem.setDirection(1);
        lottieItem.play();
      });
      element.addEventListener('mouseleave', function(e) {
        lottieItem.setDirection(-1);
      });
    }
  }

  createLottieAnimation(settings) {
    const _this = this;
    if(!settings.wadiLottieAnimationLazyLoading) {
      _this.wadiLottieAnimation(settings);
    } else {
      _this.wadiLazyLottieAnimation(settings);
    }
  }


  wadiLazyLottieAnimation(settings) {
    const _this = this;
    const bufferHeightBeforeTriggerLottie = 200;

    const wadiLottieAnimationContainer = document.querySelector(settings.wadiLottieAnimationContainer);
      
      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
          if(entry.isIntersecting) {
            _this.wadiLottieAnimation(settings);
            observer.unobserve(wadiLottieAnimationContainer);

          }
        });
      }
      , {
        root: null,
        rootMargin: `0px 0px ${bufferHeightBeforeTriggerLottie}px`,
      });
      observer.observe(wadiLottieAnimationContainer);

  }

  setLottieAnimationSpeed(lottie, settings) {
    lottie.setSpeed(settings.wadiLottieAnimationSpeed.size);
  }
  setLottieAnimationReverse(lottie, settings) {
    if(settings.wadiLottieAnimationReverse) {
      lottie.goToAndStop(lottie.totalFrames, true);
      lottie.setDirection(-1);
    }
    return;
  }

  wadiLottieAnimationClickTimeout(settings) {
    
    if (!this.isEdit) {
      setTimeout(() => {
        const tabTarget = 'on' === settings.wadiLottieLink.is_external ? '_blank' : '_self';
        window.open(settings.wadiLottieLink.url, tabTarget);
      }, settings.wadiLottieAnimationLinkTimeout * 1000);
    }
  }

}

jQuery(window).on("elementor/frontend/init", () => {
  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(WadiLottieAnimation, {
      $element,
    });
  };

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/wadi-lottie-animation-addon.default",
    addHandler
  );
});
