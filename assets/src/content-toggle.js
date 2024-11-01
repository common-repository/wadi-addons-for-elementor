import './style/content-toggle.scss';

class WadiContentToggle extends elementorModules.frontend.handlers.Base {

    getDefaultSettings() {
        return {
          selectors: {
            WadiContentToggleContainer: '.wadi_content_toggle_container',
            WadiContentToggleSwitcher: '.wadi_content_toggle_switcher',
            WadiContentToggleSwitcherHeading1: '.wadi_content_toggle_heading_1',
            WadiContentToggleSwitcherButton: '.wadi_content_toggle_switcher_button',
            WadiContentToggleSwitchLabel: '.wadi_content_toggle_switch_label',
            WadiContentToggleSwitchInput: '.wadi_content_toggle_switch',
            WadiContentToggleSwitchSlider: '.wadi_content_toggle_switch_slider',
            WadiContentToggleSwitcherHeading2: '.wadi_content_toggle_heading_2',
            WadiContentToggleSections: '.wadi_content_toggle_sections',
            WadiContentToggleSection1: '.wadi_content_toggle_section_1',
            WadiContentToggleSection2: '.wadi_content_toggle_section_2',
          },
        }
      }
    
      getDefaultElements() {
        const selectors = this.getSettings('selectors');
        const elements = {
          wadiContentToggleContainer: this.findElement(selectors.WadiContentToggleContainer),
          WadiContentToggleSwitcher: this.findElement(selectors.WadiContentToggleSwitcher),
          WadiContentToggleSwitcherHeading1: this.findElement(selectors.WadiContentToggleSwitcherHeading1),
          WadiContentToggleSwitcherButton: this.findElement(selectors.WadiContentToggleSwitcherButton),
          WadiContentToggleSwitchLabel: this.findElement(selectors.WadiContentToggleSwitchLabel),
          WadiContentToggleSwitchInput: this.findElement(selectors.WadiContentToggleSwitchInput),
          WadiContentToggleSwitchSlider: this.findElement(selectors.WadiContentToggleSwitchSlider),
          WadiContentToggleSwitcherHeading2: this.findElement(selectors.WadiContentToggleSwitcherHeading2),
          WadiContentToggleSections: this.findElement(selectors.WadiContentToggleSections),
          WadiContentToggleSection1: this.findElement(selectors.WadiContentToggleSection1),
          WadiContentToggleSection2: this.findElement(selectors.WadiContentToggleSection2),
        };
    
        return elements;
      }
    
      bindEvents() {
        this.initiate();
      }

      initiate() {
        const wadiContentToggleContainer = this.elements.wadiContentToggleContainer;
        const WadiContentToggleSwitcher = this.elements.WadiContentToggleSwitcher;
        const WadiContentToggleSwitcherHeading1 = this.elements.WadiContentToggleSwitcherHeading1;
        const WadiContentToggleSwitcherButton = this.elements.WadiContentToggleSwitcherButton;
        const WadiContentToggleSwitchLabel = this.elements.WadiContentToggleSwitchLabel;
        const WadiContentToggleSwitchInput = this.elements.WadiContentToggleSwitchInput;
        const WadiContentToggleSwitchSlider = this.elements.WadiContentToggleSwitchSlider;
        const WadiContentToggleSwitcherHeading2 = this.elements.WadiContentToggleSwitcherHeading2;
        const WadiContentToggleSections = this.elements.WadiContentToggleSections;
        const WadiContentToggleSection1 = this.elements.WadiContentToggleSection1;
        const WadiContentToggleSection2 = this.elements.WadiContentToggleSection2;


        const elementsSettings = this.getElementSettings();

        const content_1_id = elementsSettings.wadi_content_toggle_1_content_id ? elementsSettings.wadi_content_toggle_1_content_id : 'content-1';
        const content_2_id = elementsSettings.wadi_content_toggle_2_content_id ? elementsSettings.wadi_content_toggle_2_content_id : 'content-2';

        /**
         * 
         * Content Toggle Remotely using ID Hash from link
         *  you can set content ID and use it to open specific content section remotely
         * 
         * #content-1 if ID is set to content-1 will open content section 1
         */
        
         const href = window.location.href;

         const url = new URL(href);
         const contentParam = url.searchParams.get(elementsSettings.wadi_conetnt_toggle_content_section_param);

	      const pattern = new RegExp( "^[\\w\\-]+$" );
        const sanitize_input = pattern.test( contentParam );

        if( '' !== contentParam & sanitize_input ){
          if ( contentParam === content_1_id || contentParam === content_2_id ) {
            this.contentToggleRemote( contentParam , WadiContentToggleSwitchInput, WadiContentToggleSection1, WadiContentToggleSection2 );
          }
        }
    

        this.sectionsShowHide(WadiContentToggleSwitchInput, WadiContentToggleSection1, WadiContentToggleSection2)
        this.toggleOnSwitch(WadiContentToggleSwitchInput, WadiContentToggleSection1, WadiContentToggleSection2)
        this.labelOne(WadiContentToggleSwitcherHeading1, WadiContentToggleSwitchInput, WadiContentToggleSection1, WadiContentToggleSection2)
        this.labelTwo(WadiContentToggleSwitcherHeading2, WadiContentToggleSwitchInput, WadiContentToggleSection1, WadiContentToggleSection2)
    }

      sectionsShowHide(switchBtn, section_1, section_2) {

        setTimeout( function(){

          if( switchBtn.is( ':checked' ) ) {
            section_1.hide();
            section_2.show();
          } else {
            section_1.show();
            section_2.hide();
          }
        }, 100 );

      }

      toggleOnSwitch(switchBtn, section_1, section_2) {
        switchBtn.on( 'click', function(e){
        section_1.toggle();
        section_2.toggle();
		});
      }

      /* Label 1 Click */
      labelOne(labelOne,switchBtn, section_1, section_2) {
        labelOne.on( 'click', function(e){

              // Uncheck
              switchBtn.prop( "checked", false);
              section_1.show();
              section_2.hide();
  
          });
      }

      labelTwo(labelTwo,switchBtn, section_1, section_2) {
        labelTwo.on( 'click', function(e){

              // Check
              switchBtn.prop( "checked", true);
              section_1.hide();
              section_2.show();
          });
      }


    
        contentToggleRemote( contentParam, switchBtn, section_1, section_2 ){

          const elementsSettings = this.getElementSettings();

          const content_1_id = elementsSettings.wadi_content_toggle_1_content_id ? elementsSettings.wadi_content_toggle_1_content_id : 'content-1';
          const content_2_id = elementsSettings.wadi_content_toggle_2_content_id ? elementsSettings.wadi_content_toggle_2_content_id : 'content-2';

          // jQuery( 'html, body' ).animate( {
          //   scrollTop: jQuery( '#wadi-toggle-init' ).find( '.wadi_content_toggle_container' ).offset().top
          // }, 500 );
    
          if( contentParam === content_1_id ) {
    
            section_1.show();
            section_2.hide();
            switchBtn.prop( "checked", false );
          } else if ( contentParam === content_2_id ) {
    
            section_1.hide();
            section_2.show();
            switchBtn.prop( "checked", true );
          }
        }

}

jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiContentToggle, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-content-toggle-addon.default",
      addHandler
    );
  });
