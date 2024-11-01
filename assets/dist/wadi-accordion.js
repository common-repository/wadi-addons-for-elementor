/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/style/accordion.scss":
/*!**********************************!*\
  !*** ./src/style/accordion.scss ***!
  \**********************************/
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
/*!**************************!*\
  !*** ./src/accordion.js ***!
  \**************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_accordion_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/accordion.scss */ "./src/style/accordion.scss");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

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



var WadiAccordion = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(WadiAccordion, _elementorModules$fro);

  var _super = _createSuper(WadiAccordion);

  function WadiAccordion() {
    _classCallCheck(this, WadiAccordion);

    return _super.apply(this, arguments);
  }

  _createClass(WadiAccordion, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          wadiAccordionElem: ".wadi-accordion-section",
          wadiAccordionWrap: ".wadi-accordion-wrap",
          wadiAccordionTitle: ".accordion__title",
          wadiAccordionCurrentTitle: ".accordion__title.current-item",
          wadiAccordionContent: ".accordion__content",
          wadiAccordionCurrentContent: ".accordion__content.current-item"
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      var elements = {
        $wadiAccordionElem: document.querySelector(selectors.wadiAccordionElem),
        $wadiAccordionWrap: document.querySelector(selectors.wadiAccordionWrap),
        $wadiAccordionTitle: document.querySelectorAll(selectors.wadiAccordionTitle),
        $wadiAccordionContent: document.querySelectorAll(selectors.wadiAccordionContent)
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

      var $wadiAccordionWrap = this.elements.$wadiAccordionWrap;
      var $wadiAccordionCurrent = 'current-item';
      var elementsSettings = this.getElementSettings();

      var wadiAccordionSettings = _objectSpread({
        id: "#wadi-accordion-" + this.$element[0].getAttribute("data-id"),
        current: $wadiAccordionCurrent,
        toggle: elementsSettings.accordion_toggle ? "show" : "hide",
        toggleOnLoad: 'yes' === elementsSettings.wadi_accordion_on_load_toggle ? true : false
      }, 'yes' === elementsSettings.wadi_accordion_on_load_toggle && {
        toggleAccordionIndex: elementsSettings.wadi_accordion_set_on_load_toggle_index ? elementsSettings.wadi_accordion_set_on_load_toggle_index : 0
      });

      _this.WadiAccordion($wadiAccordionWrap, wadiAccordionSettings);
    }
  }, {
    key: "WadiAccordion",
    value: function WadiAccordion(accordionElem, settings) {
      var $this = this;
      var id = settings.id;
      var toggle = settings.toggle;
      var currentClass = settings.current;
      var accordion = accordionElem;
      var accordionItems = document.querySelector("".concat(id));

      var accordionTitles = _toConsumableArray(accordionItems.querySelectorAll('.accordion__title'));

      var accordionContents = _toConsumableArray(accordionItems.querySelectorAll('.accordion__content')); // Handle active accordion item


      var _iterator = _createForOfIteratorHelper(accordionTitles),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var title = _step.value;
          if (!title.classList.contains(currentClass)) continue;
          title.setAttribute('aria-expanded', 'true');
          var content = title.nextElementSibling;
          content.style.display = 'block';
          content.style.maxHeight = 'none';
        } // Hide all bodies except the active

      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      var _iterator2 = _createForOfIteratorHelper(accordionContents),
          _step2;

      try {
        for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
          var _content = _step2.value;
          if (_content.previousElementSibling.classList.contains(currentClass)) continue;
          _content.style.display = 'none';
          _content.style.maxHeight = '0px';
        }
      } catch (err) {
        _iterator2.e(err);
      } finally {
        _iterator2.f();
      }

      this.addEvents(accordion, accordionTitles, accordionContents, settings);
    }
  }, {
    key: "closeOthers",
    value: function closeOthers(elException, titles, currentClass) {
      var _iterator3 = _createForOfIteratorHelper(titles),
          _step3;

      try {
        var _loop = function _loop() {
          var title = _step3.value;
          if (title === elException) return "continue";
          title.classList.remove(currentClass);
          title.setAttribute('aria-expanded', 'false');
          var content = title.nextElementSibling;
          content.style.maxHeight = "".concat(content.scrollHeight, "px");
          setTimeout(function () {
            return void (content.style.maxHeight = '0px');
          }, 0);
        };

        for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
          var _ret = _loop();

          if (_ret === "continue") continue;
        }
      } catch (err) {
        _iterator3.e(err);
      } finally {
        _iterator3.f();
      }
    }
  }, {
    key: "handleClick",
    value: function handleClick(event, titles, settings) {
      var title = event.currentTarget;
      var content = title.nextElementSibling;

      if (settings.toggle === 'hide') {
        this.closeOthers(title, titles, settings.current);
      } // Set height to the active body


      if (content.style.maxHeight === 'none') {
        content.style.maxHeight = "".concat(content.scrollHeight, "px");
      }

      if (title.classList.contains(settings.current)) {
        // Close accordion item
        title.classList.remove(settings.current);
        title.setAttribute('aria-expanded', 'false');
        setTimeout(function () {
          return void (content.style.maxHeight = '0px');
        }, 0);
      } else {
        // Open accordion item
        title.classList.add(settings.current);
        title.setAttribute('aria-expanded', 'true');
        content.style.display = 'block';
        content.style.maxHeight = "".concat(content.scrollHeight, "px");
      }
    }
  }, {
    key: "handleKeydown",
    value: function handleKeydown(event, titles) {
      var target = event.target;
      var key = event.which.toString();

      if (target.classList.contains('accordion__title') && key.match(/35|36|38|40/)) {
        event.preventDefault();
      } else {
        return false;
      }

      switch (key) {
        case '36':
          // "Home" key
          titles[0].focus();
          break;

        case '35':
          // "End" key
          titles[titles.length - 1].focus();
          break;

        case '38':
          // "Up Arrow" key
          var prevIndex = titles.indexOf(target) - 1;

          if (titles[prevIndex]) {
            titles[prevIndex].focus();
          } else {
            titles[titles.length - 1].focus();
          }

          break;

        case '40':
          // "Down Arrow" key
          var nextIndex = titles.indexOf(target) + 1;

          if (titles[nextIndex]) {
            titles[nextIndex].focus();
          } else {
            titles[0].focus();
          }

          break;
      }
    }
  }, {
    key: "handleTransitionend",
    value: function handleTransitionend(event) {
      var content = event.currentTarget;

      if (content.style.maxHeight !== '0px') {
        // Remove the height from the active body
        content.style.maxHeight = 'none';
      } else {
        // Hide the active body
        content.style.display = 'none';
      }
    }
  }, {
    key: "addEvents",
    value: function addEvents(accordion, accordionTitles, accordionContents, settings) {
      var _this2 = this;

      if (accordion && accordionTitles) {
        accordion.addEventListener('keydown', function (e) {
          return _this2.handleKeydown(e, accordionTitles);
        });

        var _iterator4 = _createForOfIteratorHelper(accordionTitles),
            _step4;

        try {
          for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
            var title = _step4.value;
            title.addEventListener('click', function (e) {
              return _this2.handleClick(e, accordionTitles, settings);
            });
          }
        } catch (err) {
          _iterator4.e(err);
        } finally {
          _iterator4.f();
        }
      }

      if (accordionContents) {
        var _iterator5 = _createForOfIteratorHelper(accordionContents),
            _step5;

        try {
          for (_iterator5.s(); !(_step5 = _iterator5.n()).done;) {
            var content = _step5.value;
            content.addEventListener('transitionend', function (e) {
              return _this2.handleTransitionend(e);
            });
          }
        } catch (err) {
          _iterator5.e(err);
        } finally {
          _iterator5.f();
        }
      }

      if (settings.toggleOnLoad) {
        this.activeAccordion(accordionTitles, settings);
      }
    }
  }, {
    key: "activeAccordion",
    value: function activeAccordion(accordionTitles, settings) {
      accordionTitles[settings.toggleAccordionIndex].classList.add(settings.current);
      accordionTitles[settings.toggleAccordionIndex].setAttribute('aria-expanded', 'true');
      accordionTitles[settings.toggleAccordionIndex].nextElementSibling.style.display = 'block';
      accordionTitles[settings.toggleAccordionIndex].nextElementSibling.style.maxHeight = "".concat(accordionTitles[settings.toggleAccordionIndex].nextElementSibling.scrollHeight, "px");
    } //   destroy() {
    //     this.accordionTitles.removeEventListener('keydown', this.handleKeydown)
    //     for (const button of this.buttons) {
    //       button.removeEventListener('click', this.handleClick)
    //     }
    //     for (const body of this.bodies) {
    //       body.addEventListener('transitionend', this.handleTransitionend)
    //     }
    //     this.buttons = null
    //     this.bodies = null
    //     this.accordion.classList.remove('is-init-accordion')
    //   }

  }]);

  return WadiAccordion;
}(elementorModules.frontend.handlers.Base);

jQuery(window).on("elementor/frontend/init", function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(WadiAccordion, {
      $element: $element
    });
  };

  elementorFrontend.hooks.addAction("frontend/element_ready/wadi-accordion-addon.default", addHandler);
});
}();
/******/ })()
;
//# sourceMappingURL=wadi-accordion.js.map