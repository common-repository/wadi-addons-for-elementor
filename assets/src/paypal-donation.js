import './style/paypal-donation.scss';
import { loadScript } from "@paypal/paypal-js";

class WadiPayPalDonation extends elementorModules.frontend.handlers.Base {

    getDefaultSettings() {
      return {
        selectors: {
          wadiPayPalDonationContainer: '.wadi_paypal_donation_container',
        },
      }
    }
  
  
    getDefaultElements() {
      const selectors = this.getSettings('selectors');
      const elements = {
        $wadiPayPalDonationContainerElem: this.$element.find(selectors.wadiPayPalDonationContainer),
      };
  
      return elements;
    }
  
    bindEvents() {
      this.initiate();
    }
  
    initiate() {
    const _this = this;

    _this.wadiDonationPaypal();

    }

    wadiDonationPaypal() {

      let _this = this;
      
      let $donationContainers = this.elements.$wadiPayPalDonationContainerElem;
      let wadiPaypalClientID, wadiPaypalCurrency;
        if($donationContainers[0].dataset.paypalSettings ) {
          wadiPaypalClientID = JSON.parse($donationContainers[0].dataset.paypalSettings).client_id;
        }
        if($donationContainers[0].dataset.paypalSettings ) {
          wadiPaypalCurrency = JSON.parse($donationContainers[0].dataset.paypalSettings).currency;
        }

          const paypalButtonSettings = JSON.parse($donationContainers[0].dataset.paypalButtonSettings);
          const paypalSettings = JSON.parse($donationContainers[0].dataset.paypalSettings);

        PayPal.Donation.Button({
          env: paypalSettings.paypal_account_status,
          business: paypalSettings.paypal_account,
          // hosted_button_id: 'YOUR_SANDBOX_HOSTED_BUTTON_ID',
          image: {
              // src: 'https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif',
              src: `${WadiURL.wadi_plugin_url}assets/images/paypal-donate-buttons/${paypalButtonSettings.paypal_donation_button}`,
              title: paypalButtonSettings.paypal_donation_button_title,
              alt: 'Donate with PayPal Button'
          },
          onComplete: function (params) {
              // Your onComplete handler
              console.log("Completed Transaction", params);
              console.log("params", params)
              _this.paymentSuccess($donationContainers[0]);
          },
      }).render('.wadi_paypal_button_'+this.$element[0].getAttribute('data-id'));


    }

    paymentSuccess(theElement) {

      let paymentSuccessElem = document.createElement('div');
      paymentSuccessElem.textContent = 'Donation Payment was successful.';
      paymentSuccessElem.classList.add('wadi_donation_payment_success', 'hidden');
      theElement.append(paymentSuccessElem);

      if(paymentSuccessElem.classList.contains('hidden')) {
          paymentSuccessElem.classList.remove('hidden');
          setTimeout(() => {
            paymentSuccessElem.classList.add('hidden');
          },  5000);
      }

    }

}



jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiPayPalDonation, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-paypal-donation-addon.default",
      addHandler
    );
  });

