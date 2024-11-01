import './style/modal.scss';

class WadiModal extends elementorModules.frontend.handlers.Base {

    
    getDefaultSettings() {
      return {
        selectors: {
        wadiModalTheWrapper: '#wadi_modal_wrapper_'+this.$element[0].getAttribute("data-id"),
          wadiModalWrapper: '.wadi_modal_wrapper',
        },
      }
    }
  
    getDefaultElements() {
      const selectors = this.getSettings('selectors');
      const elements = {
        $wadiModalWrapper: this.findElement(selectors.wadiModalWrapper),
      };
  
      return elements;
    }
  
    bindEvents() {
      this.initiate();
    }
  
    initiate() {
        const _this = this;
        const $wadiModalWrapper = this.elements.wadiModalWrapper;
        const elementsSettings = this.getElementSettings();
        const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
        const selectors = this.getSettings('selectors');

        const wadiModalSettings = {
            wadiModalWrapperElement: selectors.wadiModalWrapper,
            wadiModalWrapperElem: selectors.wadiModalWrapper + '_' + this.$element[0].getAttribute("data-id"),
            wadiModalTrigger: '#modal_trigger_' + this.$element[0].getAttribute("data-id"),
            wadiModalTriggerType: elementsSettings.wadi_modal_trigger_type ? elementsSettings.wadi_modal_trigger_type : 'button',
            ...elementsSettings.wadi_modal_trigger_type === 'page_load' && {
              wadiModalTriggerDelay: elementsSettings.wadi_modal_trigger_modal_delay ? elementsSettings.wadi_modal_trigger_modal_delay : 1000,
            },
            wadiModalAnimationSwitch: 'yes' === elementsSettings.wadi_modal_content_entrance_animation_switch ? true: false,
            ...'yes' === elementsSettings.wadi_modal_content_entrance_animation_switch && {
              wadiModalAnimation: elementsSettings.wadi_modal_content_entrance_animation ? elementsSettings.wadi_modal_content_entrance_animation : 'slide-in-top',
              wadiModalAnimationEntranceDuration: elementsSettings.wadi_modal_content_entrance_animation_duration ? elementsSettings.wadi_modal_content_entrance_animation_duration : '500',
            },
            // wadiModalAnimationDuration: elementsSettings.wadi_modal_animation_duration ? elementsSettings.wadi_modal_animation_duration : 300,
            wadiModalAnimationDelay: elementsSettings.wadi_modal_animation_delay ? elementsSettings.wadi_modal_animation_delay : 0,
            wadiModalCloseOnEsc: 'yes' === elementsSettings.wadi_modal_close_on_esc ? true: false,
            wadiModalCloseOnClickOutside: 'yes' === elementsSettings.wadi_modal_close_on_click_outside ? true: false,
        }

        _this.wadiModal($wadiModalWrapper, wadiModalSettings);

    }

    wadiModal(elem, settings) {

        // console.log("settings",settings);
        const modalWrapperItems = document.querySelectorAll(settings.wadiModalWrapperElement);
        const modalWrapperItemsArr = [...modalWrapperItems];
        // console.log('modalWrapperItems', modalWrapperItems);
        // const theModalContainers = modalWrapperItems.querySelectorAll('.wadi_modal_container');
        // const theModalContainersArr = [...theModalContainers];
        // console.log('theModalContainers', theModalContainers);

        this.openModals(modalWrapperItemsArr, settings);
        this.closeModals(settings);
        this.triggerModal(settings);
    }

    openModals(modals, settings) {

        
      let elementTriggerModal;
      switch(settings.wadiModalTriggerType) {
        case 'button':
          elementTriggerModal = document.querySelector(`#wadi_modal_wrapper_${this.$element[0].getAttribute("data-id")} .wadi_modal_button_trigger_container .wadi_modal_trigger_button_text`);
          break;
        case 'page_load':
          elementTriggerModal = document.querySelector(`#wadi_modal_wrapper_${this.$element[0].getAttribute("data-id")}`);
          break;
        case 'icon':
          elementTriggerModal = document.querySelector(`#wadi_modal_wrapper_${this.$element[0].getAttribute("data-id")} .wadi_modal_trigger_icon_container .wadi_modal_trigger_icon_content`);
          break;
        case 'image':
          elementTriggerModal = document.querySelector(`#wadi_modal_wrapper_${this.$element[0].getAttribute("data-id")} .wadi_modal_trigger_image_container img`);
          break;
        case 'text':
          elementTriggerModal = document.querySelector(`#wadi_modal_wrapper_${this.$element[0].getAttribute("data-id")} .wadi_modal_trigger_text_container .wadi_modal_text_trigger`);
      }

      elementTriggerModal.addEventListener('click', (e) => {
        const elementTrigger = e.target.closest(`#modal_trigger_${this.$element[0].getAttribute("data-id")}`);
            

        if(elementTrigger) {
          const getTriggerTargetId = elementTrigger.getAttribute('data-target');
          const currentModal = document.querySelector(`#${getTriggerTargetId}`);
          if(settings.wadiModalAnimationSwitch) {
            const targetModalAnimation = document.querySelector(`#${getTriggerTargetId}`).querySelector(`.modal-content`)
            targetModalAnimation.style.animationDuration = `${settings.wadiModalAnimationEntranceDuration}ms`;
          }
          document.querySelector(`#${getTriggerTargetId}`).classList.add('active');
        }
      });

    }

    
    closeModals(settings) {

      // Close Modal on Keyboard Escape
      this.closeModalsKeyboard(settings);

      // Close Modal on Mouse Click Outside or on Close Button
      this.closeModalsMouse(settings);
    }


    closeModalsKeyboard(settings) {
      
      window.addEventListener('keydown',(e)=> {


        e = e || window.event;
        var isEscape = false;
        if ("key" in e) {
            isEscape = (e.key === "Escape" || e.key === "Esc");
        } else {
            isEscape = (e.keyCode === 27);
        }
        
        // If is Escape key pressed and modal is active And close on esc is true then close the modal
        if(isEscape && settings.wadiModalCloseOnEsc) {

          this.hideModalKeyEvents(e);
          
        }
      })

    }

    closeModalsMouse(settings) {
      const selectors = this.getSettings('selectors');

        document.querySelector(`#wadi_modal_wrapper_${this.$element[0].getAttribute("data-id")}`).addEventListener('click', (e) => {
          const checkModal = e.target.closest(selectors.wadiModalWrapper);

          if(checkModal) {
              const currentOpenedModal = e.target.closest('.modal-content');
              if(e.target?.closest('.wadi_modal')) {
                  const getClassesList = e.target?.closest('.wadi_modal').classList;
                  // Close Modal if clicked outside the modal content OR if clicked on the close button with data-dismiss="modal"
                  if(settings.wadiModalCloseOnClickOutside) {
                      if(currentOpenedModal === null && getClassesList.contains('active')) {
                        getClassesList.remove('active');
                      }
                  }
                  if(e.target.getAttribute('data-dismiss') === 'modal') {
                      getClassesList.remove('active');
                  }
              }
          }
      });
    }

    hideModalKeyEvents(e) {
      const currentModal = e.target.querySelector(`#wadi_modal_wrapper_${this.$element[0].getAttribute("data-id")}`) || e.target.closest(`#wadi_modal_wrapper_${this.$element[0].getAttribute("data-id")}`);

      if(currentModal) {
        let theCurrentModal = currentModal.querySelector('.wadi_modal');
        if(theCurrentModal.classList.contains('active')) {
          theCurrentModal.classList.remove('active');
        }
      }
    }

    triggerModal(settings) {
      if(settings.wadiModalTriggerType === 'page_load') {
        setTimeout(() => {
            const modalTrigger = document.querySelector(`#modal_trigger_${this.$element[0].getAttribute("data-id")}`);
            if(modalTrigger) {
                modalTrigger.click();
            }
        }, settings.wadiModalTriggerDelay);
      }
    }
}

jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiModal, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-modal-addon.default",
      addHandler
    );
  });
