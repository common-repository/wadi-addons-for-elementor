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

/***/ "./src/style/paypal-donation.scss":
/*!****************************************!*\
  !*** ./src/style/paypal-donation.scss ***!
  \****************************************/
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
/*!********************************!*\
  !*** ./src/paypal-donation.js ***!
  \********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_paypal_donation_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/paypal-donation.scss */ "./src/style/paypal-donation.scss");
/* harmony import */ var _paypal_paypal_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @paypal/paypal-js */ "../node_modules/@paypal/paypal-js/dist/esm/paypal-js.js");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

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




var WadiPayPalDonation = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(WadiPayPalDonation, _elementorModules$fro);

  var _super = _createSuper(WadiPayPalDonation);

  function WadiPayPalDonation() {
    _classCallCheck(this, WadiPayPalDonation);

    return _super.apply(this, arguments);
  }

  _createClass(WadiPayPalDonation, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          wadiPayPalDonationContainer: '.wadi_paypal_donation_container'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      var elements = {
        $wadiPayPalDonationContainerElem: this.$element.find(selectors.wadiPayPalDonationContainer)
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

      _this.wadiDonationPaypal();
    }
  }, {
    key: "wadiDonationPaypal",
    value: function wadiDonationPaypal() {
      var _this = this;

      var $donationContainers = this.elements.$wadiPayPalDonationContainerElem;
      var wadiPaypalClientID, wadiPaypalCurrency;

      if ($donationContainers[0].dataset.paypalSettings) {
        wadiPaypalClientID = JSON.parse($donationContainers[0].dataset.paypalSettings).client_id;
      }

      if ($donationContainers[0].dataset.paypalSettings) {
        wadiPaypalCurrency = JSON.parse($donationContainers[0].dataset.paypalSettings).currency;
      }

      var paypalButtonSettings = JSON.parse($donationContainers[0].dataset.paypalButtonSettings);
      var paypalSettings = JSON.parse($donationContainers[0].dataset.paypalSettings);
      PayPal.Donation.Button({
        env: paypalSettings.paypal_account_status,
        business: paypalSettings.paypal_account,
        // hosted_button_id: 'YOUR_SANDBOX_HOSTED_BUTTON_ID',
        image: {
          // src: 'https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif',
          src: "".concat(WadiURL.wadi_plugin_url, "assets/images/paypal-donate-buttons/").concat(paypalButtonSettings.paypal_donation_button),
          title: paypalButtonSettings.paypal_donation_button_title,
          alt: 'Donate with PayPal Button'
        },
        onComplete: function onComplete(params) {
          // Your onComplete handler
          console.log("Completed Transaction", params);
          console.log("params", params);

          _this.paymentSuccess($donationContainers[0]);
        }
      }).render('.wadi_paypal_button_' + this.$element[0].getAttribute('data-id'));
    }
  }, {
    key: "paymentSuccess",
    value: function paymentSuccess(theElement) {
      var paymentSuccessElem = document.createElement('div');
      paymentSuccessElem.textContent = 'Donation Payment was successful.';
      paymentSuccessElem.classList.add('wadi_donation_payment_success', 'hidden');
      theElement.append(paymentSuccessElem);

      if (paymentSuccessElem.classList.contains('hidden')) {
        paymentSuccessElem.classList.remove('hidden');
        setTimeout(function () {
          paymentSuccessElem.classList.add('hidden');
        }, 5000);
      }
    }
  }]);

  return WadiPayPalDonation;
}(elementorModules.frontend.handlers.Base);

jQuery(window).on("elementor/frontend/init", function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(WadiPayPalDonation, {
      $element: $element
    });
  };

  elementorFrontend.hooks.addAction("frontend/element_ready/wadi-paypal-donation-addon.default", addHandler);
});
}();
/******/ })()
;
//# sourceMappingURL=wadi-paypal-donation.js.map