/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "../node_modules/@paypal/paypal-js/dist/esm/paypal-js.js":
/*!***************************************************************!*\
  !*** ../node_modules/@paypal/paypal-js/dist/esm/paypal-js.js ***!
  \***************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "loadCustomScript": function() { return /* binding */ loadCustomScript; },
/* harmony export */   "loadScript": function() { return /* binding */ loadScript; },
/* harmony export */   "version": function() { return /* binding */ version; }
/* harmony export */ });
/*!
 * paypal-js v5.1.1 (2022-08-03T17:21:59.218Z)
 * Copyright 2020-present, PayPal, Inc. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
function findScript(url, attributes) {
    var currentScript = document.querySelector("script[src=\"".concat(url, "\"]"));
    if (currentScript === null)
        return null;
    var nextScript = createScriptElement(url, attributes);
    // ignore the data-uid-auto attribute that gets auto-assigned to every script tag
    var currentScriptClone = currentScript.cloneNode();
    delete currentScriptClone.dataset.uidAuto;
    // check if the new script has the same number of data attributes
    if (Object.keys(currentScriptClone.dataset).length !==
        Object.keys(nextScript.dataset).length) {
        return null;
    }
    var isExactMatch = true;
    // check if the data attribute values are the same
    Object.keys(currentScriptClone.dataset).forEach(function (key) {
        if (currentScriptClone.dataset[key] !== nextScript.dataset[key]) {
            isExactMatch = false;
        }
    });
    return isExactMatch ? currentScript : null;
}
function insertScriptElement(_a) {
    var url = _a.url, attributes = _a.attributes, onSuccess = _a.onSuccess, onError = _a.onError;
    var newScript = createScriptElement(url, attributes);
    newScript.onerror = onError;
    newScript.onload = onSuccess;
    document.head.insertBefore(newScript, document.head.firstElementChild);
}
function processOptions(options) {
    var sdkBaseURL = "https://www.paypal.com/sdk/js";
    if (options.sdkBaseURL) {
        sdkBaseURL = options.sdkBaseURL;
        delete options.sdkBaseURL;
    }
    processMerchantID(options);
    var _a = Object.keys(options)
        .filter(function (key) {
        return (typeof options[key] !== "undefined" &&
            options[key] !== null &&
            options[key] !== "");
    })
        .reduce(function (accumulator, key) {
        var value = options[key].toString();
        if (key.substring(0, 5) === "data-") {
            accumulator.dataAttributes[key] = value;
        }
        else {
            accumulator.queryParams[key] = value;
        }
        return accumulator;
    }, {
        queryParams: {},
        dataAttributes: {},
    }), queryParams = _a.queryParams, dataAttributes = _a.dataAttributes;
    return {
        url: "".concat(sdkBaseURL, "?").concat(objectToQueryString(queryParams)),
        dataAttributes: dataAttributes,
    };
}
function objectToQueryString(params) {
    var queryString = "";
    Object.keys(params).forEach(function (key) {
        if (queryString.length !== 0)
            queryString += "&";
        queryString += key + "=" + params[key];
    });
    return queryString;
}
/**
 * Parse the error message code received from the server during the script load.
 * This function search for the occurrence of this specific string "/* Original Error:".
 *
 * @param message the received error response from the server
 * @returns the content of the message if the string string was found.
 *          The whole message otherwise
 */
function parseErrorMessage(message) {
    var originalErrorText = message.split("/* Original Error:")[1];
    return originalErrorText
        ? originalErrorText.replace(/\n/g, "").replace("*/", "").trim()
        : message;
}
function createScriptElement(url, attributes) {
    if (attributes === void 0) { attributes = {}; }
    var newScript = document.createElement("script");
    newScript.src = url;
    Object.keys(attributes).forEach(function (key) {
        newScript.setAttribute(key, attributes[key]);
        if (key === "data-csp-nonce") {
            newScript.setAttribute("nonce", attributes["data-csp-nonce"]);
        }
    });
    return newScript;
}
function processMerchantID(options) {
    var merchantID = options["merchant-id"], dataMerchantID = options["data-merchant-id"];
    var newMerchantID = "";
    var newDataMerchantID = "";
    if (Array.isArray(merchantID)) {
        if (merchantID.length > 1) {
            newMerchantID = "*";
            newDataMerchantID = merchantID.toString();
        }
        else {
            newMerchantID = merchantID.toString();
        }
    }
    else if (typeof merchantID === "string" && merchantID.length > 0) {
        newMerchantID = merchantID;
    }
    else if (typeof dataMerchantID === "string" &&
        dataMerchantID.length > 0) {
        newMerchantID = "*";
        newDataMerchantID = dataMerchantID;
    }
    options["merchant-id"] = newMerchantID;
    options["data-merchant-id"] = newDataMerchantID;
    return options;
}

/**
 * Load the Paypal JS SDK script asynchronously.
 *
 * @param {Object} options - used to configure query parameters and data attributes for the JS SDK.
 * @param {PromiseConstructor} [PromisePonyfill=window.Promise] - optional Promise Constructor ponyfill.
 * @return {Promise<Object>} paypalObject - reference to the global window PayPal object.
 */
function loadScript(options, PromisePonyfill) {
    if (PromisePonyfill === void 0) { PromisePonyfill = getDefaultPromiseImplementation(); }
    validateArguments(options, PromisePonyfill);
    // resolve with null when running in Node
    if (typeof window === "undefined")
        return PromisePonyfill.resolve(null);
    var _a = processOptions(options), url = _a.url, dataAttributes = _a.dataAttributes;
    var namespace = dataAttributes["data-namespace"] || "paypal";
    var existingWindowNamespace = getPayPalWindowNamespace(namespace);
    // resolve with the existing global paypal namespace when a script with the same params already exists
    if (findScript(url, dataAttributes) && existingWindowNamespace) {
        return PromisePonyfill.resolve(existingWindowNamespace);
    }
    return loadCustomScript({
        url: url,
        attributes: dataAttributes,
    }, PromisePonyfill).then(function () {
        var newWindowNamespace = getPayPalWindowNamespace(namespace);
        if (newWindowNamespace) {
            return newWindowNamespace;
        }
        throw new Error("The window.".concat(namespace, " global variable is not available."));
    });
}
/**
 * Load a custom script asynchronously.
 *
 * @param {Object} options - used to set the script url and attributes.
 * @param {PromiseConstructor} [PromisePonyfill=window.Promise] - optional Promise Constructor ponyfill.
 * @return {Promise<void>} returns a promise to indicate if the script was successfully loaded.
 */
function loadCustomScript(options, PromisePonyfill) {
    if (PromisePonyfill === void 0) { PromisePonyfill = getDefaultPromiseImplementation(); }
    validateArguments(options, PromisePonyfill);
    var url = options.url, attributes = options.attributes;
    if (typeof url !== "string" || url.length === 0) {
        throw new Error("Invalid url.");
    }
    if (typeof attributes !== "undefined" && typeof attributes !== "object") {
        throw new Error("Expected attributes to be an object.");
    }
    return new PromisePonyfill(function (resolve, reject) {
        // resolve with undefined when running in Node
        if (typeof window === "undefined")
            return resolve();
        insertScriptElement({
            url: url,
            attributes: attributes,
            onSuccess: function () { return resolve(); },
            onError: function () {
                var defaultError = new Error("The script \"".concat(url, "\" failed to load."));
                if (!window.fetch) {
                    return reject(defaultError);
                }
                // Fetch the error reason from the response body for validation errors
                return fetch(url)
                    .then(function (response) {
                    if (response.status === 200) {
                        reject(defaultError);
                    }
                    return response.text();
                })
                    .then(function (message) {
                    var parseMessage = parseErrorMessage(message);
                    reject(new Error(parseMessage));
                })
                    .catch(function (err) {
                    reject(err);
                });
            },
        });
    });
}
function getDefaultPromiseImplementation() {
    if (typeof Promise === "undefined") {
        throw new Error("Promise is undefined. To resolve the issue, use a Promise polyfill.");
    }
    return Promise;
}
function getPayPalWindowNamespace(namespace) {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    return window[namespace];
}
function validateArguments(options, PromisePonyfill) {
    if (typeof options !== "object" || options === null) {
        throw new Error("Expected an options object.");
    }
    if (typeof PromisePonyfill !== "undefined" &&
        typeof PromisePonyfill !== "function") {
        throw new Error("Expected PromisePonyfill to be a function.");
    }
}

// replaced with the package.json version at build time
var version = "5.1.1";




/***/ }),

/***/ "./src/style/paypal-subscription.scss":
/*!********************************************!*\
  !*** ./src/style/paypal-subscription.scss ***!
  \********************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!************************************!*\
  !*** ./src/paypal-subscription.js ***!
  \************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_paypal_subscription_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/paypal-subscription.scss */ "./src/style/paypal-subscription.scss");
/* harmony import */ var _paypal_paypal_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @paypal/paypal-js */ "../node_modules/@paypal/paypal-js/dist/esm/paypal-js.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }




var WadiPayPalSubscription = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(WadiPayPalSubscription, _elementorModules$fro);

  var _super = _createSuper(WadiPayPalSubscription);

  function WadiPayPalSubscription() {
    _classCallCheck(this, WadiPayPalSubscription);

    return _super.apply(this, arguments);
  }

  _createClass(WadiPayPalSubscription, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          wadiPayPalSubscriptionContainer: '.wadi_paypal_subscription_container'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      var elements = {
        $wadiPayPalSubscriptionContainerElem: this.$element.find(selectors.wadiPayPalSubscriptionContainer)
      };
      return elements;
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      this.initiate();
    }
  }, {
    key: "initiate",
    value: function initiate() {
      var _this = this;

      var elementsSettings = this.getElementSettings();
      var currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
      var selectors = this.getSettings('selectors');
      var wadiPaypalsubscriptionSettings = {
        wadiPayPalsubscriptionContainers: document.querySelectorAll('.wadi_paypal_subscription_container'),
        wadiPayPalsubscriptionContainer: document.querySelector('.wadi_paypal_subscription_container_' + this.$element[0].getAttribute("data-id")),
        wadiPaypalButtonStyleLayout: elementsSettings.wadi_paypal_subscription_paypal_buttons_layout ? elementsSettings.wadi_paypal_subscription_paypal_buttons_layout : 'vertical',
        wadiPaypalButtonStyleColor: elementsSettings.wadi_paypal_subscription_paypal_buttons_color ? elementsSettings.wadi_paypal_subscription_paypal_buttons_color : 'gold',
        wadiPaypalButtonStyleShape: elementsSettings.wadi_paypal_subscription_paypal_buttons_shape ? elementsSettings.wadi_paypal_subscription_paypal_buttons_shape : 'rect',
        wadiItemsRepeater: elementsSettings.wadi_paypal_subscription_preset_amount_to_donate ? elementsSettings.wadi_paypal_subscription_preset_amount_to_donate : []
      };

      _this.wadiPayPalCheckout(wadiPaypalsubscriptionSettings);
    }
  }, {
    key: "wadiPayPalCheckout",
    value: function wadiPayPalCheckout(settings) {
      var paypal;
      var wadiPaypalClientID, wadiPaypalCurrency;

      if (settings.wadiPayPalsubscriptionContainers[0].dataset.paypalSettings) {
        wadiPaypalClientID = JSON.parse(settings.wadiPayPalsubscriptionContainers[0].dataset.paypalSettings).client_id;
      }

      if (settings.wadiPayPalsubscriptionContainers[0].dataset.paypalSettings) {
        wadiPaypalCurrency = JSON.parse(settings.wadiPayPalsubscriptionContainers[0].dataset.paypalSettings).currency;
      }

      try {
        paypal = (0,_paypal_paypal_js__WEBPACK_IMPORTED_MODULE_1__.loadScript)({
          "client-id": wadiPaypalClientID,
          "currency": wadiPaypalCurrency,
          'disable-funding': 'card',
          intent: 'subscription',
          vault: true
        });
      } catch (error) {
        console.error("failed to load the PayPal JS SDK script", error);
      }

      var paypalSubscriptionContainers = _toConsumableArray(settings.wadiPayPalsubscriptionContainers);

      var _this = this;

      if (paypalSubscriptionContainers) {
        paypal.then(function (paypal) {
          if (paypal) {
            var wadiPaypalButtons;

            try {
              paypalSubscriptionContainers.forEach(function (item, index) {
                var paypalButtonSettings = JSON.parse(item.dataset.paypalButtonSettings);
                var paypalSettings = JSON.parse(item.dataset.paypalSettings);
                var tagline;
                tagline = 'yes' == paypalButtonSettings.tagline ? true : false;
                var paypalSubscriptionCurrency;

                if (wadiPaypalCurrency) {
                  paypalSubscriptionCurrency = wadiPaypalCurrency;
                } else {
                  paypalSubscriptionCurrency = paypalSettings.widget_currency;
                }

                var productShippingAmount;

                if ('yes' === paypalSettings.is_shipping_amount) {
                  productShippingAmount = paypalSettings.product_shipping_amount;
                } else {
                  productShippingAmount = 0;
                }

                var paypalSubscriptionQuantity;

                if ('yes' === paypalSettings.is_subscription_qty) {
                  if ('radio' == paypalSettings.subscription_qty_type) {
                    var radiosLabels = item.querySelector('.wadi_radio_buttons').querySelectorAll('label');
                    radiosLabels.forEach(function (radio, index) {
                      var radioSelectedValue;

                      if (radio) {
                        radio.addEventListener('click', function (e) {
                          radioSelectedValue = e.target.value;
                          paypalSubscriptionQuantity = radioSelectedValue; // inputValue.value = paypalSubscriptionQuantity
                        });
                      }
                    });
                  } else if ('select' == paypalSettings.subscription_qty_type) {
                    paypalSubscriptionQuantity = item.querySelector('.wadi_subscription_qty').value;
                    item.querySelector('.wadi_subscription_qty').addEventListener('change', function (e) {
                      var selectValue = e.target.value;
                      paypalSubscriptionQuantity = selectValue; // inputValue.value = paypalSubscriptionQuantity
                    });
                  } else if ('input' == paypalSettings.subscription_qty_type) {
                    paypalSubscriptionQuantity = item.querySelector('.wadi_subscription_qty').value;
                    item.querySelector('.wadi_subscription_qty').addEventListener('input', function (evt) {
                      var selectInputVal = evt.target.value;
                      paypalSubscriptionQuantity = selectInputVal; // inputValue.value = paypalSubscriptionQuantity
                    });
                  }
                }

                paypal.Buttons({
                  style: _objectSpread({
                    layout: paypalButtonSettings.layout,
                    color: paypalButtonSettings.color,
                    shape: paypalButtonSettings.shape,
                    height: paypalButtonSettings.height,
                    label: paypalButtonSettings.label
                  }, 'vertical' != paypalButtonSettings.layout && {
                    tagline: tagline
                  }),
                  createSubscription: function createSubscription(data, actions) {
                    console.log("subscription data", data);
                    console.log("subscription actions", actions);
                    return actions.subscription.create(_objectSpread(_objectSpread({
                      /* Creates the subscription */
                      plan_id: paypalSettings.plan_id,
                      // One subscription Plan P-20Y64231W3536560MMMXO3EQ || Mulitple Subscription Plan P-3E656054TB2879126MMXQ6PQ
                      auto_renewal: true
                    }, 'yes' == paypalSettings.is_subscription_qty && {
                      quantity: paypalSubscriptionQuantity || 1
                    }), {}, {
                      shipping_amount: {
                        currency_code: paypalSubscriptionCurrency,
                        value: productShippingAmount
                      }
                    }));
                  },
                  onApprove: function onApprove(data, actions) {
                    console.log("Subscription ID: ", data.subscriptionID); // You can add optional success message for the subscriber here

                    _this.paymentSuccess(item);
                  }
                }).render(item.querySelector('#wadi_paypal_subscription_paypal_button'));
              });
            } catch (error) {
              console.error("failed to render the PayPal Buttons", error);
            }
          }
        });
      }
    }
  }, {
    key: "paymentSuccess",
    value: function paymentSuccess(theElement) {
      var paymentSuccessElem = document.createElement('div');
      paymentSuccessElem.textContent = 'Subscription Payment was successful.';
      paymentSuccessElem.classList.add('wadi_subscription_payment_success', 'hidden');
      theElement.append(paymentSuccessElem);

      if (paymentSuccessElem.classList.contains('hidden')) {
        paymentSuccessElem.classList.remove('hidden');
        setTimeout(function () {
          paymentSuccessElem.classList.add('hidden');
        }, 5000);
      }
    }
  }]);

  return WadiPayPalSubscription;
}(elementorModules.frontend.handlers.Base);

jQuery(window).on("elementor/frontend/init", function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(WadiPayPalSubscription, {
      $element: $element
    });
  };

  elementorFrontend.hooks.addAction("frontend/element_ready/wadi-paypal-subscription-addon.default", addHandler);
});
}();
/******/ })()
;
//# sourceMappingURL=wadi-paypal-subscription.js.map