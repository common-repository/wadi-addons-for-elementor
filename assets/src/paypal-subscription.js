import './style/paypal-subscription.scss';
import { loadScript } from "@paypal/paypal-js";

class WadiPayPalSubscription extends elementorModules.frontend.handlers.Base {
    
    getDefaultSettings() {
        return {
          selectors: {
            wadiPayPalSubscriptionContainer: '.wadi_paypal_subscription_container',
          },
        }
      }
    
    
      getDefaultElements() {
        const selectors = this.getSettings('selectors');
        const elements = {
          $wadiPayPalSubscriptionContainerElem: this.$element.find(selectors.wadiPayPalSubscriptionContainer),
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
        
        const wadiPaypalsubscriptionSettings = {
            wadiPayPalsubscriptionContainers: document.querySelectorAll('.wadi_paypal_subscription_container'),
            wadiPayPalsubscriptionContainer: document.querySelector('.wadi_paypal_subscription_container_'+ this.$element[0].getAttribute("data-id")),
            wadiPaypalButtonStyleLayout: elementsSettings.wadi_paypal_subscription_paypal_buttons_layout ? elementsSettings.wadi_paypal_subscription_paypal_buttons_layout : 'vertical', 
            wadiPaypalButtonStyleColor: elementsSettings.wadi_paypal_subscription_paypal_buttons_color ? elementsSettings.wadi_paypal_subscription_paypal_buttons_color : 'gold', 
            wadiPaypalButtonStyleShape: elementsSettings.wadi_paypal_subscription_paypal_buttons_shape ? elementsSettings.wadi_paypal_subscription_paypal_buttons_shape : 'rect', 
            wadiItemsRepeater: elementsSettings.wadi_paypal_subscription_preset_amount_to_donate ?  elementsSettings.wadi_paypal_subscription_preset_amount_to_donate: [], 
        }
        _this.wadiPayPalCheckout(wadiPaypalsubscriptionSettings);
    
        }

        
    wadiPayPalCheckout(settings) {
       
  
          let paypal;
  
          let wadiPaypalClientID, wadiPaypalCurrency;
            if(settings.wadiPayPalsubscriptionContainers[0].dataset.paypalSettings ) {
              wadiPaypalClientID = JSON.parse(settings.wadiPayPalsubscriptionContainers[0].dataset.paypalSettings).client_id;
            }
            if(settings.wadiPayPalsubscriptionContainers[0].dataset.paypalSettings ) {
              wadiPaypalCurrency = JSON.parse(settings.wadiPayPalsubscriptionContainers[0].dataset.paypalSettings).currency;
            }
  
            try {
                paypal = loadScript({
                  "client-id": wadiPaypalClientID,
                  "currency": wadiPaypalCurrency,
                  'disable-funding': 'card',
                  intent: 'subscription',
                  vault: true,
                });
            } catch (error) {
                console.error("failed to load the PayPal JS SDK script", error);
            }
  
  
            const paypalSubscriptionContainers = [...settings.wadiPayPalsubscriptionContainers];
  
            let _this = this;
            if(paypalSubscriptionContainers) {
              paypal.then((paypal) => {
                if(paypal) {
                  let wadiPaypalButtons;
                  try {
                    paypalSubscriptionContainers.forEach((item,index) => {
                      const paypalButtonSettings = JSON.parse(item.dataset.paypalButtonSettings);
                      const paypalSettings = JSON.parse(item.dataset.paypalSettings);
  
                      let tagline;
                      
                      tagline = 'yes' == paypalButtonSettings.tagline ? true : false;


                    let paypalSubscriptionCurrency;
                    if(wadiPaypalCurrency) {
                      paypalSubscriptionCurrency = wadiPaypalCurrency;
                    } else {
                      paypalSubscriptionCurrency = paypalSettings.widget_currency;
                    }


                    let productShippingAmount;

                    if('yes' === paypalSettings.is_shipping_amount ) {
                      productShippingAmount = paypalSettings.product_shipping_amount;
                    } else {
                      productShippingAmount = 0;
                    }

                    let paypalSubscriptionQuantity;
                    if('yes' === paypalSettings.is_subscription_qty) {

                      if( 'radio' == paypalSettings.subscription_qty_type ) {
                        const radiosLabels =  item.querySelector('.wadi_radio_buttons').querySelectorAll('label');
  
                        radiosLabels.forEach(((radio, index) => {
  
                          let radioSelectedValue;
                          if(radio) {
                            radio.addEventListener('click', (e) => {
                              
                              radioSelectedValue =  e.target.value;
                              paypalSubscriptionQuantity  = radioSelectedValue;
                              // inputValue.value = paypalSubscriptionQuantity
  
                            })
                          }
                        }))
                      }
                      else if( 'select' == paypalSettings.subscription_qty_type ) {
  
                        paypalSubscriptionQuantity = item.querySelector('.wadi_subscription_qty').value;
                        item.querySelector('.wadi_subscription_qty').addEventListener('change', (e) => {
                          let selectValue = e.target.value;
                          paypalSubscriptionQuantity = selectValue;
                          // inputValue.value = paypalSubscriptionQuantity
                        })

                      }
                      else if( 'input' == paypalSettings.subscription_qty_type ) {
                        paypalSubscriptionQuantity = item.querySelector('.wadi_subscription_qty').value;
                        item.querySelector('.wadi_subscription_qty').addEventListener('input',function (evt) {
                          
                          let selectInputVal = evt.target.value
                          paypalSubscriptionQuantity = selectInputVal
                          // inputValue.value = paypalSubscriptionQuantity
  
                        })

                      }
                    }



                    paypal.Buttons(
                      {
                          style: {
                            layout: paypalButtonSettings.layout,
                            color:  paypalButtonSettings.color,
                            shape:  paypalButtonSettings.shape,
                            height: paypalButtonSettings.height,
                            label:  paypalButtonSettings.label,
                            ... 'vertical' != paypalButtonSettings.layout && {
                              tagline: tagline
                            }
                          },
                          createSubscription: function(data, actions) {
                            console.log("subscription data", data); 
                            console.log("subscription actions", actions); 
                            return actions.subscription.create({
                              /* Creates the subscription */
                              plan_id: paypalSettings.plan_id, // One subscription Plan P-20Y64231W3536560MMMXO3EQ || Mulitple Subscription Plan P-3E656054TB2879126MMXQ6PQ
                              auto_renewal: true,
                              ... 'yes' == paypalSettings.is_subscription_qty && {
                                quantity: paypalSubscriptionQuantity || 1,
                              },
                              shipping_amount: {
                                currency_code: paypalSubscriptionCurrency,
                                value: productShippingAmount,
                              },
                            });
                          },
                          onApprove: function(data, actions) {
                            console.log("Subscription ID: ",data.subscriptionID); // You can add optional success message for the subscriber here
                            _this.paymentSuccess(item);
                          },
                      }
                    ).render(item.querySelector('#wadi_paypal_subscription_paypal_button'));
                  })
      
                  } catch(error) {
                    console.error("failed to render the PayPal Buttons", error);
                  }
                }
            })
          }
  
        }

        paymentSuccess(theElement) {

          let paymentSuccessElem = document.createElement('div');
          paymentSuccessElem.textContent = 'Subscription Payment was successful.';
          paymentSuccessElem.classList.add('wadi_subscription_payment_success', 'hidden');
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
      elementorFrontend.elementsHandler.addHandler(WadiPayPalSubscription, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-paypal-subscription-addon.default",
      addHandler
    );
  });
