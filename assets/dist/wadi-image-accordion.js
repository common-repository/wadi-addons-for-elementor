/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/style/image-accordion.scss":
/*!****************************************!*\
  !*** ./src/style/image-accordion.scss ***!
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
  !*** ./src/image-accordion.js ***!
  \********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_image_accordion_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/image-accordion.scss */ "./src/style/image-accordion.scss");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

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

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }



var wadiImageAccordion = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(wadiImageAccordion, _elementorModules$fro);

  var _super = _createSuper(wadiImageAccordion);

  function wadiImageAccordion() {
    var _this2;

    _classCallCheck(this, wadiImageAccordion);

    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    _this2 = _super.call.apply(_super, [this].concat(args));

    _defineProperty(_assertThisInitialized(_this2), "removeActiveAccordionImageClasses", function (allImageAccordionElemsArr) {
      allImageAccordionElemsArr.map(function (elem, index) {
        elem.classList.remove('active');
      });
    });

    return _this2;
  }

  _createClass(wadiImageAccordion, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          wadiImageAccordionContainer: '.wadi_image_accordion_container'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      var elements = {
        $wadiImageAccordionContainer: this.$element.find(selectors.wadiImageAccordionContainer)
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

      var wadiImageAccordionSettings = _objectSpread({
        wadiImageAccordionContainer: ".wadi_image_accordion_container_".concat(this.$element[0].getAttribute("data-id")),
        wadiImageAccordionActivateType: elementsSettings.wadi_image_accordion_activate_type ? elementsSettings.wadi_image_accordion_activate_type : 'click',
        wadiImageAccordionDeactivateOutside: 'yes' === elementsSettings.wadi_image_accordion_deactivate_outside ? true : false,
        wadiImageAccordionMakeActiveStart: 'yes' === elementsSettings.wadi_image_accordion_active_switcher ? true : false
      }, 'yes' === elementsSettings.wadi_image_accordion_active_switcher && {
        wadiImageAccordionActiveIndex: elementsSettings.wadi_image_accordion_active_index ? elementsSettings.wadi_image_accordion_active_index : 0
      });

      _this.wadiImageAccordion(wadiImageAccordionSettings, jQuery);
    }
  }, {
    key: "wadiImageAccordion",
    value: function wadiImageAccordion(settings, $) {
      var _this = this;

      var wadiImageAccordionContainer = document.querySelector(settings.wadiImageAccordionContainer);
      var allImageAccordionElems = wadiImageAccordionContainer.querySelectorAll('.wadi_image_element_background');
      var allImageAccordionElemsArr = Array.from(allImageAccordionElems); // Make Active Accordion Image

      _this.makeActiveAccordionImageElement(allImageAccordionElemsArr, settings);

      allImageAccordionElemsArr.map(function (elem, index) {
        if (settings.wadiImageAccordionActivateType === 'click') {
          elem.addEventListener('click', function (e) {
            _this.removeActiveAccordionImageClasses(allImageAccordionElemsArr);

            e.target.closest('.wadi_image_element_background').classList.add('active');
          });

          if (settings.wadiImageAccordionDeactivateOutside) {
            document.addEventListener('click', function (e) {
              if (!e.target.closest('.wadi_image_element_background')) {
                _this.removeActiveAccordionImageClasses(allImageAccordionElemsArr);
              }
            });
          }
        } else if (settings.wadiImageAccordionActivateType === 'hover') {
          elem.addEventListener('mouseenter', function (e) {
            _this.removeActiveAccordionImageClasses(allImageAccordionElemsArr);

            e.target.closest('.wadi_image_element_background').classList.add('active');
          });

          if (settings.wadiImageAccordionDeactivateOutside) {
            elem.addEventListener('mouseleave', function (e) {
              _this.removeActiveAccordionImageClasses(allImageAccordionElemsArr);
            });
          }
        }
      });
    } // Image Accordion Array Element Index Active

  }, {
    key: "makeActiveAccordionImageElement",
    value: function makeActiveAccordionImageElement(allImageAccordionElemsArr, settings) {
      if (settings.wadiImageAccordionMakeActiveStart) {
        allImageAccordionElemsArr.map(function (elem, index) {
          if (index === settings.wadiImageAccordionActiveIndex) {
            elem.classList.add('active');
          }
        });
      }
    }
  }]);

  return wadiImageAccordion;
}(elementorModules.frontend.handlers.Base);

jQuery(window).on("elementor/frontend/init", function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(wadiImageAccordion, {
      $element: $element
    });
  };

  elementorFrontend.hooks.addAction("frontend/element_ready/wadi-image-accordion-addon.default", addHandler);
});
}();
/******/ })()
;
//# sourceMappingURL=wadi-image-accordion.js.map