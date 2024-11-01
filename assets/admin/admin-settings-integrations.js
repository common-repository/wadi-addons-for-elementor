
class WadiSettingsIntegrations {
    init() {

        this.handleSaveSettings()

    }
    handleSaveSettings () {
        const _this = this;

       const thePaypalLabel = document.querySelector(".wadi_paypal_client_id_item")
       const theInput = thePaypalLabel.querySelector('#wadi_paypal_client_id');
       if(theInput) {
           theInput.addEventListener('input', (e) => {
                _this.saveSettingsIntegrations('integration_settings');
           });
       }

       const currencySelect = document.querySelector('.wadi_paypal_amount');
       if(currencySelect) {
           currencySelect.addEventListener('change', (e) => {
            _this.saveSettingsIntegrations('integration_settings');
           })
       }

       
    }



    saveSettingsIntegrations(action) { //save elements settings changes
    const settings = wadiAddonsIntegrationsSettings.settings;

    console.log('action', action)
        var $form = null;
    
        if ('integration_settings' === action) {
            $form = jQuery('form.wadi_settings_integrations_form');
            action = 'wadi_integration_settings';
        }

    
        jQuery.ajax(
            {
                url: settings.ajaxurl,
                type: 'POST',
                data: {
                    action: action,
                    security: settings.nonce,
                    fields: $form.serialize(),
                },
                success: function (response) {
                    console.log('settings saved');
    
                    console.log('repsonse', response);
                    // this.genButtonDisplay();
                },
                error: function (err) {
                    console.log(err);
                }
            }
        );
    }


    // genButtonDisplay = function () {
    //     var $form = $('form.wadi_settings_integrations_form'),
    //         searchTerm = 'premium-assets-generator=on',
    //         indexOfFirst = $form.serialize().indexOf(searchTerm);

    //     if (indexOfFirst !== -1) {
    //         $('.pa-btn-generate').show();
    //     } else {
    //         $('.pa-btn-generate').hide();
    //     }
    // };

}
    
const wadiIntegrations = new WadiSettingsIntegrations;

wadiIntegrations.init();
