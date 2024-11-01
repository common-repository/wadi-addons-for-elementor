import './style/paypal-checkout.scss';
import { loadScript } from "@paypal/paypal-js";

class WadiPayPalCheckout extends elementorModules.frontend.handlers.Base {
    
    getDefaultSettings() {
        return {
          selectors: {
            wadiPayPalCheckoutContainer: '.wadi_paypal_checkout_container',
          },
        }
      }
    
    
      getDefaultElements() {
        const selectors = this.getSettings('selectors');
        const elements = {
          $wadiPayPalCheckoutContainerElem: this.findElement(selectors.wadiPayPalCheckoutContainer),
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
        
        const wadiPaypalCheckoutSettings = {
            wadiPayPalCheckoutContainers: document.querySelectorAll('.wadi_paypal_checkout_container'),
            wadiPayPalCheckoutContainer: document.querySelector('.wadi_paypal_checkout_container_'+ this.$element[0].getAttribute("data-id")),
            wadiPaypalButtonStyleLayout: elementsSettings.wadi_paypal_checkout_paypal_buttons_layout ? elementsSettings.wadi_paypal_checkout_paypal_buttons_layout : 'vertical', 
            wadiPaypalButtonStyleColor: elementsSettings.wadi_paypal_checkout_paypal_buttons_color ? elementsSettings.wadi_paypal_checkout_paypal_buttons_color : 'gold', 
            wadiPaypalButtonStyleShape: elementsSettings.wadi_paypal_checkout_paypal_buttons_shape ? elementsSettings.wadi_paypal_checkout_paypal_buttons_shape : 'rect', 
            wadiItemsRepeater: elementsSettings.wadi_paypal_checkout_preset_amount_to_donate ?  elementsSettings.wadi_paypal_checkout_preset_amount_to_donate: [], 
        }
        _this.wadiPayPalCheckout(wadiPaypalCheckoutSettings);
    
        }

        
    wadiPayPalCheckout(settings) {
       
  
          let paypal;
  
          let wadiPaypalClientID, wadiPaypalCurrency;
            if(settings.wadiPayPalCheckoutContainers[0].dataset.paypalSettings ) {
              wadiPaypalClientID = JSON.parse(settings.wadiPayPalCheckoutContainers[0].dataset.paypalSettings).client_id;
            }
            if(settings.wadiPayPalCheckoutContainers[0].dataset.paypalSettings ) {
              wadiPaypalCurrency = JSON.parse(settings.wadiPayPalCheckoutContainers[0].dataset.paypalSettings).currency;
            }
  
            try {
                paypal = loadScript({
                  "client-id": wadiPaypalClientID,
                  "currency": wadiPaypalCurrency,
                  'disable-funding': 'card',
                });
            } catch (error) {
                console.error("failed to load the PayPal JS SDK script", error);
            }
  
  
            const paypalCheckoutContainers = [...settings.wadiPayPalCheckoutContainers];
  
            let _this = this;
            if(paypalCheckoutContainers) {
              paypal.then((paypal) => {
                if(paypal) {
                  try {
                    paypalCheckoutContainers.forEach((item,index) => {
                      const paypalButtonSettings = JSON.parse(item.dataset.paypalButtonSettings);
                      const paypalSettings = JSON.parse(item.dataset.paypalSettings);
  
                      let itemsQuantity;
                      let paypalCheckoutQuantity;
  
                      let tagline;
                      
                      tagline = 'yes' == paypalButtonSettings.tagline ? true : false


                    let paypalCheckoutCurrency;
                    if(wadiPaypalCurrency) {
                      paypalCheckoutCurrency = wadiPaypalCurrency;
                    } else {
                      paypalCheckoutCurrency = paypalSettings.widget_currency;
                    }

                    let productShipping;

                    if('yes' === paypalSettings.shipping_available ) {
                      productShipping = Number((paypalSettings.product_shipping).toFixed(2));
                    } else {
                      productShipping = 0;
                    }

                    let productHandling;

                    if('yes' === paypalSettings.handling_available ){
                      productHandling = Number((paypalSettings.product_handling).toFixed(2));
                    } else {
                      productHandling = 0;
                    }

                    let productInsurance;

                    if('yes' === paypalSettings.insurance_available) {
                      productInsurance = Number((paypalSettings.product_insurance).toFixed(2));
                    } else {
                      productInsurance = 0;
                    }

                    let productShippingDiscount;
                    if('yes' === paypalSettings.discount_shipping_available) {
                      productShippingDiscount = Number((paypalSettings.shipping_discount_amount).toFixed(2));
                    } else {
                      productShippingDiscount = 0;
                    }

                    if(paypalSettings.preset_quantity) {
                      itemsQuantity = paypalSettings.preset_quantity;
                    } else {

                      if('DONATION' === paypalSettings.item_category && 'yes' !== paypalSettings.is_checkout_qty  ) {
                        itemsQuantity = 1;
                      }
  
                                            
                      if('yes' === paypalSettings.is_checkout_qty) {
  
                        if( 'radio' == paypalSettings.checkout_qty_type ) {
                          const radiosLabels =  item.querySelector('.wadi_radio_buttons').querySelectorAll('label');
    
                          radiosLabels.forEach(((radio, index) => {
                            if(radiosLabels[0]) {
                              radiosLabels[0].querySelector('input').checked = true;
                              const radioQuantity = radiosLabels[0].querySelector('input:checked').value
                              itemsQuantity = Number(radioQuantity)
                            }
                            let radioSelectedValue;
                            if(radio) {
                              radio.addEventListener('click', (e) => {
                                
                                radioSelectedValue =  e.target.value;
                                itemsQuantity = Number(radioSelectedValue);
                              })
                            }
                          }))
                        }
                        else if( 'select' == paypalSettings.checkout_qty_type ) {
    
                          paypalCheckoutQuantity = item.querySelector('.wadi_checkout_qty').value;
                          itemsQuantity = paypalCheckoutQuantity;
  
                          item.querySelector('.wadi_checkout_qty').addEventListener('change', (e) => {
                            let selectValue = e.target.value;
                            itemsQuantity = Number(selectValue);
  
                          })
  
                        }
                        else if( 'input' == paypalSettings.checkout_qty_type ) {
                          paypalCheckoutQuantity = item.querySelector('.wadi_checkout_qty').value;
                          itemsQuantity = paypalCheckoutQuantity;
                          item.querySelector('.wadi_checkout_qty').addEventListener('input',function (evt) {
                            let selectInputVal = evt.target.value
                            itemsQuantity = Number(selectInputVal);
  
    
                          })
  
                        }
                      }
                    }

                    let itemsTaxes;
                    if('tax_percentage' == paypalSettings.is_taxed) {
                      itemsTaxes = Number(paypalSettings.item_price * paypalSettings.item_tax_percentage) / 100;
                    } else if('no_tax' == paypalSettings.is_taxed ){
                      itemsTaxes = 0;
                    }
                    const itemPrice = Number((paypalSettings.item_price).toFixed(2))
                    const itemTax = Number((itemsTaxes).toFixed(2));

                    const totalPrice = Number((itemsQuantity * itemPrice).toFixed(2))
                    const totalTax = Number((itemsQuantity * itemTax).toFixed(2));
                    
                    let productDiscount;
                    if('yes' === paypalSettings.offer_discount) {
                      productDiscount = Number((( totalPrice * paypalSettings.discount_percentage ) / 100 ).toFixed(2))
                    } else {
                      productDiscount = 0;
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
                          createOrder: function(data, actions) {
                            const itemTax = Number((itemsTaxes).toFixed(2));
                            const itemPrice = Number((paypalSettings.item_price).toFixed(2))
                            const theTotalPrice = Number((itemsQuantity * itemPrice).toFixed(2))
                            const theTotalTax = Number((itemsQuantity * itemTax).toFixed(2));
                            const Value = Number((theTotalPrice + theTotalTax+ productShipping + productHandling  + productInsurance  - productShippingDiscount - productDiscount).toFixed(2))

                          // This function sets up the details of the transaction, including the amount and line item details.
                            return actions.order.create({
                                  purchase_units: [{
                                    amount: {
                                        value:Value,
                                        breakdown: {
                                          item_total : {
                                            currency_code: paypalCheckoutCurrency,
                                            value: theTotalPrice,
                                          },
                                          ... 'yes' === paypalSettings.offer_discount && {
                                            discount : {
                                              currency_code: paypalCheckoutCurrency,
                                              value: productDiscount,
                                            },
                                          },
                                            handling : {
                                              currency_code: paypalCheckoutCurrency,
                                              value: productHandling,
                                            },
                                            insurance : {
                                              currency_code: paypalCheckoutCurrency,
                                              value: productInsurance,
                                            },
                                            shipping : {
                                              currency_code: paypalCheckoutCurrency,
                                              value: productShipping,
                                            },
                                            shipping_discount : {
                                              currency_code: paypalCheckoutCurrency,
                                              value: productShippingDiscount,
                                            },
                                          ... 'tax_percentage' == paypalSettings.is_taxed && {
                                            tax_total: {
                                              currency_code: paypalCheckoutCurrency,
                                              ... (itemsTaxes) && {
                                                value: theTotalTax,
                                              }
                                            }
                                          }
                                        }
                                    },
                                    items: [
                                      {
                                        name: paypalSettings.item_name || 'Wadi Product',
                                        unit_amount: {
                                          currency_code: paypalCheckoutCurrency,
                                          value: itemPrice || 5,
                                        },
                                        quantity: itemsQuantity || 1,
                                        ... 'tax_percentage' == paypalSettings.is_taxed  && {
                                          tax: {
                                            currency_code: paypalCheckoutCurrency,
                                            ... (itemsTaxes) && {
                                              value: itemTax,
                                            }
                                          },
                                        },
                                         description: paypalSettings.item_description || 'Widget for Elementor',
                                         sku: paypalSettings.item_name || 'Digital Product From Wadiweb',
                                         category: paypalSettings.item_category || "DIGITAL_GOODS",
                                      } 
                                    ],
                                    payee: {
                                      email_address: paypalSettings.paypal_account,
                                    },
                                    // description?: string;
                                    // custom_id?: string;
                                    // invoice_id?: string;
                                    // soft_descriptor?: string;
                                    
                                  }]
                              });
                          },
                            onApprove: function(data, actions) {
                            // This function captures the funds from the transaction.
                            return actions.order.capture().then(function(details) {

                                _this.paymentSuccess(item);
                                console.log('on Approve details', details);
                                console.log('Transaction completed by ' + details.payer.name.given_name);
                            });
                          },
                          onCancel: function(data, actions) {
                            console.log("cancel data",data)
                            console.log(`Order [${data.orderID}] was canncelled.`);
                            console.log("Cancel actions ",actions)
                          }
                      }
                    ).render(item.querySelector('#wadi_paypal_checkout_paypal_button'));
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
          paymentSuccessElem.textContent = 'Payment was successful.';
          paymentSuccessElem.classList.add('wadi_payment_success', 'hidden');
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
      elementorFrontend.elementsHandler.addHandler(WadiPayPalCheckout, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-paypal-checkout-addon.default",
      addHandler
    );
  });
