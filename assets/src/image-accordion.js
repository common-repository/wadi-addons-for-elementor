import "./style/image-accordion.scss";


class wadiImageAccordion extends elementorModules.frontend.handlers.Base {

    getDefaultSettings() {
      return {
        selectors: {
          wadiImageAccordionContainer: '.wadi_image_accordion_container',
        },
      }
    }
  
  
    getDefaultElements() {
        const selectors = this.getSettings('selectors');
      const elements = {
        $wadiImageAccordionContainer:  this.$element.find(selectors.wadiImageAccordionContainer),

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
        const wadiImageAccordionSettings = {
            wadiImageAccordionContainer: `.wadi_image_accordion_container_${ this.$element[0].getAttribute("data-id")}`,
            wadiImageAccordionActivateType: elementsSettings.wadi_image_accordion_activate_type ? elementsSettings.wadi_image_accordion_activate_type : 'click',
            wadiImageAccordionDeactivateOutside: 'yes' === elementsSettings.wadi_image_accordion_deactivate_outside ? true : false,
            wadiImageAccordionMakeActiveStart: 'yes' === elementsSettings.wadi_image_accordion_active_switcher ? true : false,
            ... 'yes' === elementsSettings.wadi_image_accordion_active_switcher && {
              wadiImageAccordionActiveIndex: elementsSettings.wadi_image_accordion_active_index ? elementsSettings.wadi_image_accordion_active_index : 0,
            }
        }
      
      _this.wadiImageAccordion(wadiImageAccordionSettings, jQuery);

    }

    wadiImageAccordion(settings, $) {
        const _this = this;
        
        const wadiImageAccordionContainer = document.querySelector(settings.wadiImageAccordionContainer);
        const allImageAccordionElems = wadiImageAccordionContainer.querySelectorAll('.wadi_image_element_background');
        const allImageAccordionElemsArr = Array.from(allImageAccordionElems);

        // Make Active Accordion Image
        _this.makeActiveAccordionImageElement(allImageAccordionElemsArr, settings);

        allImageAccordionElemsArr.map((elem, index) => {
            if(settings.wadiImageAccordionActivateType === 'click') {
                elem.addEventListener('click', function(e) {
                    _this.removeActiveAccordionImageClasses(allImageAccordionElemsArr)
                    e.target.closest('.wadi_image_element_background').classList.add('active');
                });
                if(settings.wadiImageAccordionDeactivateOutside) {
                    document.addEventListener('click', function(e) {
                        if(!e.target.closest('.wadi_image_element_background')) {
                            _this.removeActiveAccordionImageClasses(allImageAccordionElemsArr)
                        }
                    });
                }
            } else if(settings.wadiImageAccordionActivateType === 'hover') {
                elem.addEventListener('mouseenter', function(e) {
                    _this.removeActiveAccordionImageClasses(allImageAccordionElemsArr)
                    e.target.closest('.wadi_image_element_background').classList.add('active');
                });
                if(settings.wadiImageAccordionDeactivateOutside) {
                    elem.addEventListener('mouseleave', function(e) {
                        _this.removeActiveAccordionImageClasses(allImageAccordionElemsArr)
                    });
                }
            }
        })

    }

    // Image Accordion Array Element Index Active
    makeActiveAccordionImageElement(allImageAccordionElemsArr, settings) {

      if( settings.wadiImageAccordionMakeActiveStart ) {
        allImageAccordionElemsArr.map((elem, index) => {
          if(index === settings.wadiImageAccordionActiveIndex) {
            elem.classList.add('active');
          }
        })
    }

  }

    removeActiveAccordionImageClasses = (allImageAccordionElemsArr) => {
        allImageAccordionElemsArr.map((elem, index) => {
            elem.classList.remove('active');
        })
    }
}


jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(wadiImageAccordion, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-image-accordion-addon.default",
      addHandler
    );
  });
  
  