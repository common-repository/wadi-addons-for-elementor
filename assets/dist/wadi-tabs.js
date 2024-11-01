/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/style/tabs.scss":
/*!*****************************!*\
  !*** ./src/style/tabs.scss ***!
  \*****************************/
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
/*!*********************!*\
  !*** ./src/tabs.js ***!
  \*********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_tabs_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/tabs.scss */ "./src/style/tabs.scss");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

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



var WadiTabs = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(WadiTabs, _elementorModules$fro);

  var _super = _createSuper(WadiTabs);

  function WadiTabs() {
    _classCallCheck(this, WadiTabs);

    return _super.apply(this, arguments);
  }

  _createClass(WadiTabs, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          wadiTabsElem: ".wadi-tabs-section",
          navList: ".wadi-tabs-nav-list",
          navListItem: ".wadi-tabs-nav-list-item",
          contentWrap: ".wadi-content-wrap",
          currentTab: ".wadi-tabs-nav-list-item.tab-current",
          currentClass: "tab-current"
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings("selectors");
      var elements = {
        $wadiTabsElem: this.findElement(selectors.wadiTabsElem),
        $navList: this.findElement(selectors.navList),
        $contentWrap: this.findElement(selectors.contentWrap),
        $navListItem: this.findElement(selectors.navListItem)
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

      var $wadiTabsElem = this.elements.$wadiTabsElem;
      var elementsSettings = this.getElementSettings();
      var currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
      var selectors = this.getSettings('selectors');
      this.settings = {
        id: "#wadi-tabs-" + this.$element[0].getAttribute("data-id"),
        start: elementsSettings.default_tab_index ? parseInt(elementsSettings.default_tab_index) : 0
      };

      if (elementsSettings.tabs.length - 1 < this.settings.start) {
        this.settings.start = 0;
      }

      if (-1 !== this.settings.start) {
        $wadiTabsElem.find(".wadi-tabs-nav-list-item:eq(" + this.settings.start + ")").addClass("tab-current");
      }

      _this.WadiTabs($wadiTabsElem, this.settings);
    }
  }, {
    key: "WadiTabs",
    value: function WadiTabs(tabsElems, settings) {
      var $this = this,
          id = settings.id,
          start = settings.start;
      $this.options = {
        start: start
      };

      $this.extend = function (a, b) {
        for (var key in b) {
          if (b.hasOwnProperty(key)) {
            a[key] = b[key];
          }
        }

        return a;
      };

      $this._init = function () {
        var tabsNav = []; // TODO: Replace $this.items jQuery with Vanilla JavaScript

        $this.items = jQuery(id).find(".wadi-content-wrap").first().find("> section");
        var tabsList = document.querySelector("".concat(id)).children;
        var tabsArr = Array.from(tabsList);
        var tabsNew = tabsArr.find(function (elem) {
          if (elem.classList.contains('wadi-tabs-nav')) {
            var allTabsArray = Array.from(elem.querySelectorAll('li.wadi-tabs-nav-list-item'));
            tabsNav = _toConsumableArray(allTabsArray);
          }
        });
        $this.current = -1; //No tabs will be active by default.

        if (-1 !== $this.options.start) {
          $this._show();
        }

        $this._initEvents(tabsNav);
      };

      $this._initEvents = function (tabs) {
        var tabsNav = tabs;
        tabsNav.forEach(function (tab, index) {
          var navlistIndex = tab.getAttribute('data-navlist-index');
          tab.addEventListener('click', function (e) {
            e.preventDefault();

            if (tab.classList.contains('tab-current')) {
              return;
            }

            $this._show(navlistIndex, tab, tabsNav);
          });
        });
      };

      $this._show = function (tabIndex, tab, tabs) {
        if ($this.current >= 0) {
          tabs.map(function (tabItem) {
            if (tabItem.classList.contains('tab-current')) {
              tabItem.classList.remove('tab-current');
            }
          });
          $this.items.removeClass("content-current");
        }

        $this.current = tabIndex; //Activate the first tab if no tab is clicked yet

        if (void 0 == tabIndex) {
          $this.current = $this.options.start >= 0 && $this.options.start < $this.items.length ? $this.options.start : 0;
        }

        jQuery(tab).addClass("tab-current");
        var $activeContent = $this.items.eq($this.current);
        $activeContent.addClass("content-current"); //Make sure videos are paused

        if ($this.items.find("video").length > 0) {
          $this.items.not(".content-current").find("video").each(function (index, elem) {
            jQuery(elem).get(0).pause();
          });
        }

        if ($this.items.find("iframe").length > 0) {
          $this.items.not(".content-current").find("iframe").each(function (index, elem) {
            var source = jQuery(elem).parent().attr("data-src");
            jQuery(elem).attr("src", source);
          });
        }
      };

      $this.options = $this.extend({}, $this.options);
      $this.extend($this.options, start);

      $this._init();
    }
  }]);

  return WadiTabs;
}(elementorModules.frontend.handlers.Base);

jQuery(window).on("elementor/frontend/init", function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(WadiTabs, {
      $element: $element
    });
  };

  elementorFrontend.hooks.addAction("frontend/element_ready/wadi-tabs-addon.default", addHandler);
});
}();
/******/ })()
;
//# sourceMappingURL=wadi-tabs.js.map