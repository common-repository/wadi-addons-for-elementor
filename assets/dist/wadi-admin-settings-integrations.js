/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************************!*\
  !*** ./admin/admin-settings-integrations.js ***!
  \**********************************************/
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var WadiSettingsIntegrations = /*#__PURE__*/function () {
  function WadiSettingsIntegrations() {
    _classCallCheck(this, WadiSettingsIntegrations);
  }

  _createClass(WadiSettingsIntegrations, [{
    key: "init",
    value: function init() {
      this.handleSaveSettings();
    }
  }, {
    key: "handleSaveSettings",
    value: function handleSaveSettings() {
      var _this = this;

      var thePaypalLabel = document.querySelector(".wadi_paypal_client_id_item");
      var theInput = thePaypalLabel.querySelector('#wadi_paypal_client_id');

      if (theInput) {
        theInput.addEventListener('input', function (e) {
          _this.saveSettingsIntegrations('integration_settings');
        });
      }

      var currencySelect = document.querySelector('.wadi_paypal_amount');

      if (currencySelect) {
        currencySelect.addEventListener('change', function (e) {
          _this.saveSettingsIntegrations('integration_settings');
        });
      }
    }
  }, {
    key: "saveSettingsIntegrations",
    value: function saveSettingsIntegrations(action) {
      //save elements settings changes
      var settings = wadiAddonsIntegrationsSettings.settings;
      console.log('action', action);
      var $form = null;

      if ('integration_settings' === action) {
        $form = jQuery('form.wadi_settings_integrations_form');
        action = 'wadi_integration_settings';
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
          console.log('settings saved');
          console.log('repsonse', response); // this.genButtonDisplay();
        },
        error: function error(err) {
          console.log(err);
        }
      });
    } // genButtonDisplay = function () {
    //     var $form = $('form.wadi_settings_integrations_form'),
    //         searchTerm = 'premium-assets-generator=on',
    //         indexOfFirst = $form.serialize().indexOf(searchTerm);
    //     if (indexOfFirst !== -1) {
    //         $('.pa-btn-generate').show();
    //     } else {
    //         $('.pa-btn-generate').hide();
    //     }
    // };

  }]);

  return WadiSettingsIntegrations;
}();

var wadiIntegrations = new WadiSettingsIntegrations();
wadiIntegrations.init();
/******/ })()
;
//# sourceMappingURL=wadi-admin-settings-integrations.js.map