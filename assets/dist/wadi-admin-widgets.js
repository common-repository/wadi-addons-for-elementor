/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./admin/style/admin-widgets.scss":
/*!****************************************!*\
  !*** ./admin/style/admin-widgets.scss ***!
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
  !*** ./admin/admin-widgets.js ***!
  \********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_admin_widgets_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/admin-widgets.scss */ "./admin/style/admin-widgets.scss");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var WadiWidgetsAdmin = /*#__PURE__*/function () {
  function WadiWidgetsAdmin() {
    _classCallCheck(this, WadiWidgetsAdmin);
  }

  _createClass(WadiWidgetsAdmin, [{
    key: "init",
    value: function init() {
      this.handleSaveSettings();
      this.handleBtnsAction();
      this.handleProSwitcher();
    }
  }, {
    key: "handleSaveSettings",
    value: function handleSaveSettings() {
      var _this = this;

      var itemsInput = document.querySelectorAll('.wadi-switcher input');
      itemsInput.forEach(function (item) {
        item.addEventListener('change', function () {
          _this.saveElementsSettings('elements');
        });
      });
    }
  }, {
    key: "handleBtnsAction",
    value: function handleBtnsAction() {
      var _this2 = this;

      var self = this;
      var settings = wadiAddonsSettings.settings;
      /**
       * 
       * Enable or disable widgets on button click
       * 
       */

      jQuery(".wadi_elements_status").on("click", '.wadi_button', function () {
        var $btn = jQuery(this),
            isChecked = $btn.hasClass("wadi_button-enable");

        if (!$btn.hasClass("active")) {
          jQuery(".wadi_elements_status .wadi_button").removeClass("active");
          $btn.addClass("active");
          jQuery.ajax({
            url: settings.ajaxurl,
            type: 'POST',
            data: {
              action: 'wadi_save_elements_btn',
              security: settings.nonce,
              isGlobalOn: isChecked
            },
            success: function success(response) {
              console.log("SUCCESS RESPONSE:", response);
            },
            error: function error(_error) {
              console.log("ERROR:", _error);
            }
          });
        }

        if (isChecked) {
          jQuery(".wadi_elements_status .wadi_button-unused").removeClass("dimmed");
        } else {
          jQuery(".wadi_elements_status .wadi_button-unused").addClass("dimmed");
        }

        jQuery(".wadi-elements .wadi-switchers input").prop("checked", isChecked);
        self.saveElementsSettings('elements');
      });
      /**
       * 
       * Input Switch Slider (iOS switcher) for each element
       * 
       */

      var wadiInputs = document.querySelectorAll(".wadi-elements .wadi-switchers input");
      wadiInputs.forEach(function (input) {
        input.addEventListener('change', function (e) {
          var $this = jQuery(_this2),
              id = e.target.getAttribute('id'),
              isChecked = $this.prop('checked');
          jQuery("input[name='" + id + "']").prop('checked', isChecked);
        });
      });
    }
  }, {
    key: "handleProSwitcher",
    value: function handleProSwitcher() {
      var rootUrl = window.location.host;
      var wadiUpgradePage;

      if (location.protocol === 'https:') {
        // page is secure
        wadiUpgradePage = 'https://' + rootUrl + '/wp-admin/admin.php?page=wadi-addons-pricing';
      } else {
        wadiUpgradePage = 'http://' + rootUrl + '/wp-admin/admin.php?page=wadi-addons-pricing';
      } // Trigger SWAL for PRO elements


      jQuery(".pro_wadi_addon_switcher").on('click', function () {
        var redirectionLink = wadiUpgradePage;
        Swal.fire({
          title: '<span class="wadi_addons_pro_elements">Get Wadi Addons Pro<span>',
          html: 'Invest in high quality PRO addons to take your site(s) to the next level',
          type: 'warning',
          showCloseButton: true,
          showCancelButton: true,
          cancelButtonText: "Upgrade",
          focusConfirm: true,
          customClass: 'wadi_swal_modal',
          backdrop: "\n                        rgba(0,0,0,0.6)\n                        left top\n                        no-repeat\n                    "
        }).then(function (res) {
          // Handle More Info button
          if (res.dismiss === 'cancel') {
            window.open(redirectionLink, '_blank');
          }
        });
      });
    }
  }, {
    key: "saveElementsSettings",
    value: function saveElementsSettings(action) {
      //save elements settings changes
      var settings = wadiAddonsSettings.settings;
      var $form = null;

      if ('elements' === action) {
        $form = jQuery('form#wadi_elements_settings');
        action = 'wadi_elements_settings';
      }

      jQuery.ajax({
        url: settings.ajaxurl,
        type: 'POST',
        data: {
          action: action,
          security: settings.nonce,
          fields: $form.serialize()
        },
        success: function success(response) {
          console.log("settings saved");
        },
        error: function error(err) {
          console.log(err);
        }
      });
    }
  }]);

  return WadiWidgetsAdmin;
}();

var wadiWidgets = new WadiWidgetsAdmin();
wadiWidgets.init();
}();
/******/ })()
;
//# sourceMappingURL=wadi-admin-widgets.js.map