/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/style/content-toggle.scss":
/*!***************************************!*\
  !*** ./src/style/content-toggle.scss ***!
  \***************************************/
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
/*!*******************************!*\
  !*** ./src/content-toggle.js ***!
  \*******************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_content_toggle_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/content-toggle.scss */ "./src/style/content-toggle.scss");
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



var WadiContentToggle = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(WadiContentToggle, _elementorModules$fro);

  var _super = _createSuper(WadiContentToggle);

  function WadiContentToggle() {
    _classCallCheck(this, WadiContentToggle);

    return _super.apply(this, arguments);
  }

  _createClass(WadiContentToggle, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
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
          WadiContentToggleSection2: '.wadi_content_toggle_section_2'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      var elements = {
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
        WadiContentToggleSection2: this.findElement(selectors.WadiContentToggleSection2)
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
      var wadiContentToggleContainer = this.elements.wadiContentToggleContainer;
      var WadiContentToggleSwitcher = this.elements.WadiContentToggleSwitcher;
      var WadiContentToggleSwitcherHeading1 = this.elements.WadiContentToggleSwitcherHeading1;
      var WadiContentToggleSwitcherButton = this.elements.WadiContentToggleSwitcherButton;
      var WadiContentToggleSwitchLabel = this.elements.WadiContentToggleSwitchLabel;
      var WadiContentToggleSwitchInput = this.elements.WadiContentToggleSwitchInput;
      var WadiContentToggleSwitchSlider = this.elements.WadiContentToggleSwitchSlider;
      var WadiContentToggleSwitcherHeading2 = this.elements.WadiContentToggleSwitcherHeading2;
      var WadiContentToggleSections = this.elements.WadiContentToggleSections;
      var WadiContentToggleSection1 = this.elements.WadiContentToggleSection1;
      var WadiContentToggleSection2 = this.elements.WadiContentToggleSection2;
      var elementsSettings = this.getElementSettings();
      var content_1_id = elementsSettings.wadi_content_toggle_1_content_id ? elementsSettings.wadi_content_toggle_1_content_id : 'content-1';
      var content_2_id = elementsSettings.wadi_content_toggle_2_content_id ? elementsSettings.wadi_content_toggle_2_content_id : 'content-2';
      /**
       * 
       * Content Toggle Remotely using ID Hash from link
       *  you can set content ID and use it to open specific content section remotely
       * 
       * #content-1 if ID is set to content-1 will open content section 1
       */

      var href = window.location.href;
      var url = new URL(href);
      var contentParam = url.searchParams.get(elementsSettings.wadi_conetnt_toggle_content_section_param);
      var pattern = new RegExp("^[\\w\\-]+$");
      var sanitize_input = pattern.test(contentParam);

      if ('' !== contentParam & sanitize_input) {
        if (contentParam === content_1_id || contentParam === content_2_id) {
          this.contentToggleRemote(contentParam, WadiContentToggleSwitchInput, WadiContentToggleSection1, WadiContentToggleSection2);
        }
      }

      this.sectionsShowHide(WadiContentToggleSwitchInput, WadiContentToggleSection1, WadiContentToggleSection2);
      this.toggleOnSwitch(WadiContentToggleSwitchInput, WadiContentToggleSection1, WadiContentToggleSection2);
      this.labelOne(WadiContentToggleSwitcherHeading1, WadiContentToggleSwitchInput, WadiContentToggleSection1, WadiContentToggleSection2);
      this.labelTwo(WadiContentToggleSwitcherHeading2, WadiContentToggleSwitchInput, WadiContentToggleSection1, WadiContentToggleSection2);
    }
  }, {
    key: "sectionsShowHide",
    value: function sectionsShowHide(switchBtn, section_1, section_2) {
      setTimeout(function () {
        if (switchBtn.is(':checked')) {
          section_1.hide();
          section_2.show();
        } else {
          section_1.show();
          section_2.hide();
        }
      }, 100);
    }
  }, {
    key: "toggleOnSwitch",
    value: function toggleOnSwitch(switchBtn, section_1, section_2) {
      switchBtn.on('click', function (e) {
        section_1.toggle();
        section_2.toggle();
      });
    }
    /* Label 1 Click */

  }, {
    key: "labelOne",
    value: function labelOne(_labelOne, switchBtn, section_1, section_2) {
      _labelOne.on('click', function (e) {
        // Uncheck
        switchBtn.prop("checked", false);
        section_1.show();
        section_2.hide();
      });
    }
  }, {
    key: "labelTwo",
    value: function labelTwo(_labelTwo, switchBtn, section_1, section_2) {
      _labelTwo.on('click', function (e) {
        // Check
        switchBtn.prop("checked", true);
        section_1.hide();
        section_2.show();
      });
    }
  }, {
    key: "contentToggleRemote",
    value: function contentToggleRemote(contentParam, switchBtn, section_1, section_2) {
      var elementsSettings = this.getElementSettings();
      var content_1_id = elementsSettings.wadi_content_toggle_1_content_id ? elementsSettings.wadi_content_toggle_1_content_id : 'content-1';
      var content_2_id = elementsSettings.wadi_content_toggle_2_content_id ? elementsSettings.wadi_content_toggle_2_content_id : 'content-2'; // jQuery( 'html, body' ).animate( {
      //   scrollTop: jQuery( '#wadi-toggle-init' ).find( '.wadi_content_toggle_container' ).offset().top
      // }, 500 );

      if (contentParam === content_1_id) {
        section_1.show();
        section_2.hide();
        switchBtn.prop("checked", false);
      } else if (contentParam === content_2_id) {
        section_1.hide();
        section_2.show();
        switchBtn.prop("checked", true);
      }
    }
  }]);

  return WadiContentToggle;
}(elementorModules.frontend.handlers.Base);

jQuery(window).on("elementor/frontend/init", function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(WadiContentToggle, {
      $element: $element
    });
  };

  elementorFrontend.hooks.addAction("frontend/element_ready/wadi-content-toggle-addon.default", addHandler);
});
}();
/******/ })()
;
//# sourceMappingURL=wadi-content-toggle.js.map