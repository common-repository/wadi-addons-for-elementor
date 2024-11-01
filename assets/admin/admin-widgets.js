import './style/admin-widgets.scss';

class WadiWidgetsAdmin {

    init() {
        this.handleSaveSettings();
        this.handleBtnsAction();
        this.handleProSwitcher();
    }

    handleSaveSettings () {

        const itemsInput = document.querySelectorAll('.wadi-switcher input');
        itemsInput.forEach(item => {
            item.addEventListener('change', () => {
                this.saveElementsSettings('elements')
            })
        })
    }


    handleBtnsAction() {

        const self = this;
        const settings = wadiAddonsSettings.settings;

        
            /**
             * 
             * Enable or disable widgets on button click
             * 
             */
            jQuery(".wadi_elements_status").on(
                "click",
                '.wadi_button',
                function () {

                    var $btn = jQuery(this),
                        isChecked = $btn.hasClass("wadi_button-enable");

                    if (!$btn.hasClass("active")) {
                        jQuery(".wadi_elements_status .wadi_button").removeClass("active");
                        $btn.addClass("active");

                        jQuery.ajax(
                            {
                                url: settings.ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'wadi_save_elements_btn',
                                    security: settings.nonce,
                                    isGlobalOn: isChecked
                                },
                                success: function(response) {
                                    console.log("SUCCESS RESPONSE:",response);
                                },
                                error: function(error) {
                                    console.log("ERROR:",error);
                                }
                            }
                        );

                    }

                    if (isChecked) {
                        jQuery(".wadi_elements_status .wadi_button-unused").removeClass("dimmed");
                    } else {
                        jQuery(".wadi_elements_status .wadi_button-unused").addClass("dimmed");
                    }

                    jQuery(".wadi-elements .wadi-switchers input").prop("checked", isChecked);

                    self.saveElementsSettings('elements');

                }
            );


        /**
         * 
         * Input Switch Slider (iOS switcher) for each element
         * 
         */

        const wadiInputs = document.querySelectorAll(".wadi-elements .wadi-switchers input");

        wadiInputs.forEach(input => {
            input.addEventListener('change', (e) => {
                    var $this = jQuery(this),
                    id = e.target.getAttribute('id'),
                    isChecked = $this.prop('checked');


                jQuery("input[name='" + id + "']").prop('checked', isChecked);
            })
        })


    }

    handleProSwitcher() {
        const rootUrl = window.location.host;

        let wadiUpgradePage;
        if (location.protocol === 'https:') {
            // page is secure
            wadiUpgradePage = 'https://' +rootUrl + '/wp-admin/admin.php?page=wadi-addons-pricing';
        } else {
            wadiUpgradePage = 'http://' +rootUrl + '/wp-admin/admin.php?page=wadi-addons-pricing';
        }

        // Trigger SWAL for PRO elements
        jQuery(".pro_wadi_addon_switcher").on(
            'click',
            function () {                

                var redirectionLink = wadiUpgradePage;

                Swal.fire(
                    {
                        title: '<span class="wadi_addons_pro_elements">Get Wadi Addons Pro<span>',
                        html: 'Invest in high quality PRO addons to take your site(s) to the next level',
                        type: 'warning',
                        showCloseButton: true,
                        showCancelButton: true,
                        cancelButtonText: "Upgrade",
                        focusConfirm: true,
                        customClass: 'wadi_swal_modal',
                        backdrop: `
                        rgba(0,0,0,0.6)
                        left top
                        no-repeat
                    `
                    }
                ).then(
                    function (res) {
                        // Handle More Info button
                        if (res.dismiss === 'cancel') {
                            window.open(redirectionLink, '_blank');
                        }

                    }
                );
            }
        );

    }


    
    saveElementsSettings(action) { //save elements settings changes
        const settings = wadiAddonsSettings.settings;


        var $form = null;

        if ('elements' === action) {
            $form = jQuery('form#wadi_elements_settings');
            action = 'wadi_elements_settings';
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

                    console.log("settings saved");
                },
                error: function (err) {

                    console.log(err);

                }
            }
        );

    }





}

const wadiWidgets = new WadiWidgetsAdmin;

wadiWidgets.init();
