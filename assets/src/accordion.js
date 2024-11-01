import "./style/accordion.scss";

class WadiAccordion extends elementorModules.frontend.handlers.Base {

      getDefaultSettings(){
          return {
              selectors: {
                  wadiAccordionElem: ".wadi-accordion-section",
                  wadiAccordionWrap: ".wadi-accordion-wrap",
                  wadiAccordionTitle: ".accordion__title",
                  wadiAccordionCurrentTitle: ".accordion__title.current-item",
                  wadiAccordionContent: ".accordion__content",
                  wadiAccordionCurrentContent: ".accordion__content.current-item",
              }
          }
      }
      
      getDefaultElements() {
        const selectors = this.getSettings('selectors');

        const elements = {
            $wadiAccordionElem: document.querySelector(selectors.wadiAccordionElem),
            $wadiAccordionWrap: document.querySelector(selectors.wadiAccordionWrap),
            $wadiAccordionTitle: document.querySelectorAll(selectors.wadiAccordionTitle),
            $wadiAccordionContent: document.querySelectorAll(selectors.wadiAccordionContent),
        }

        return elements;
      }

      bindEvents() {
        this.initiate();
      }

      initiate() {
          var _this = this;

          const $wadiAccordionWrap = this.elements.$wadiAccordionWrap;
          const $wadiAccordionCurrent = 'current-item';
          const elementsSettings = this.getElementSettings();

          const wadiAccordionSettings = {
              id: "#wadi-accordion-"+ this.$element[0].getAttribute("data-id"),
              current: $wadiAccordionCurrent,
              toggle: elementsSettings.accordion_toggle ? "show" : "hide",
              toggleOnLoad: 'yes' === elementsSettings.wadi_accordion_on_load_toggle ? true : false,
              ... 'yes' === elementsSettings.wadi_accordion_on_load_toggle && {
                toggleAccordionIndex: elementsSettings.wadi_accordion_set_on_load_toggle_index ? elementsSettings.wadi_accordion_set_on_load_toggle_index : 0,
              }
          }

          _this.WadiAccordion($wadiAccordionWrap, wadiAccordionSettings)

      }

      WadiAccordion(accordionElem, settings) {

        const $this = this;
        const id = settings.id;
        const toggle = settings.toggle;
        const currentClass = settings.current;
        const accordion = accordionElem;

        const accordionItems = document.querySelector(`${id}`);
        
        const accordionTitles = [...accordionItems.querySelectorAll('.accordion__title')];
        const accordionContents = [...accordionItems.querySelectorAll('.accordion__content')];


        // Handle active accordion item
        for (const title of accordionTitles) {
            if (!title.classList.contains(currentClass)) continue
            title.setAttribute('aria-expanded', 'true')
            const content = title.nextElementSibling
            content.style.display = 'block'
            content.style.maxHeight = 'none'
          }

          
         // Hide all bodies except the active
        for (const content of accordionContents) {
            if (content.previousElementSibling.classList.contains(currentClass)) continue
            content.style.display = 'none'
            content.style.maxHeight = '0px'
        }
  

        this.addEvents(accordion, accordionTitles, accordionContents, settings);
      }

      closeOthers(elException, titles, currentClass) {

        for (const title of titles) {
          if (title === elException) continue
          title.classList.remove(currentClass)
          title.setAttribute('aria-expanded', 'false')
          const content = title.nextElementSibling
          content.style.maxHeight = `${content.scrollHeight}px`
          setTimeout(() => void (content.style.maxHeight = '0px'), 0)
        }
      }

      handleClick(event, titles, settings) {

          const title = event.currentTarget;
          const content = title.nextElementSibling;

          if(settings.toggle === 'hide') {
              this.closeOthers(title, titles, settings.current)
          }

          // Set height to the active body
            if (content.style.maxHeight === 'none') {
                content.style.maxHeight = `${content.scrollHeight}px`
            }

            if (title.classList.contains(settings.current)) {
                // Close accordion item
                title.classList.remove(settings.current)
                title.setAttribute('aria-expanded', 'false')
                setTimeout(() => void (content.style.maxHeight = '0px'), 0)
              } else {
                // Open accordion item
                title.classList.add(settings.current)
                title.setAttribute('aria-expanded', 'true')
                content.style.display = 'block'
                content.style.maxHeight = `${content.scrollHeight}px`
              }
  

      }

      
  handleKeydown(event, titles) {
    const target = event.target
    const key = event.which.toString()


    if (target.classList.contains('accordion__title') && key.match(/35|36|38|40/)) {
      event.preventDefault()
    } else {
      return false
    }

    switch (key) {
      case '36':
        // "Home" key
        titles[0].focus()
        break
      case '35':
        // "End" key
        titles[titles.length - 1].focus()
        break
      case '38':
        // "Up Arrow" key
        const prevIndex = titles.indexOf(target) - 1
        if (titles[prevIndex]) {
          titles[prevIndex].focus()
        } else {
          titles[titles.length - 1].focus()
        }
        break
      case '40':
        // "Down Arrow" key
        const nextIndex = titles.indexOf(target) + 1
        if (titles[nextIndex]) {
          titles[nextIndex].focus()
        } else {
          titles[0].focus()
        }
        break
    }
  }

      handleTransitionend(event) {
        const content = event.currentTarget
        if (content.style.maxHeight !== '0px') {
        // Remove the height from the active body
        content.style.maxHeight = 'none'
        } else {
        // Hide the active body
        content.style.display = 'none'
        }
      }

      addEvents(accordion, accordionTitles, accordionContents, settings) {
        if (accordion && accordionTitles) {
          
          accordion.addEventListener('keydown', (e) => this.handleKeydown(e, accordionTitles));
          for (const title of accordionTitles) {
              title.addEventListener('click', (e) => this.handleClick(e, accordionTitles, settings))
          }
        }

        if(accordionContents) {
          for (const content of accordionContents) {
            content.addEventListener('transitionend', (e) => this.handleTransitionend(e))
          }
        }
        if(settings.toggleOnLoad) {
          this.activeAccordion(accordionTitles, settings);
        }

      }

      activeAccordion(accordionTitles, settings) {

        accordionTitles[settings.toggleAccordionIndex].classList.add(settings.current)
        accordionTitles[settings.toggleAccordionIndex].setAttribute('aria-expanded', 'true')
        accordionTitles[settings.toggleAccordionIndex].nextElementSibling.style.display = 'block'
        accordionTitles[settings.toggleAccordionIndex].nextElementSibling.style.maxHeight = `${accordionTitles[settings.toggleAccordionIndex].nextElementSibling.scrollHeight}px`

      }

    //   destroy() {
    //     this.accordionTitles.removeEventListener('keydown', this.handleKeydown)
    //     for (const button of this.buttons) {
    //       button.removeEventListener('click', this.handleClick)
    //     }
    //     for (const body of this.bodies) {
    //       body.addEventListener('transitionend', this.handleTransitionend)
    //     }
    
    //     this.buttons = null
    //     this.bodies = null
    
    //     this.accordion.classList.remove('is-init-accordion')
    //   }


}

jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiAccordion, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-accordion-addon.default",
      addHandler
    );
  });