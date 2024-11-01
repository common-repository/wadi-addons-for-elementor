/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/style/modal.scss":
/*!******************************!*\
  !*** ./src/style/modal.scss ***!
  \******************************/
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
/*!**********************!*\
  !*** ./src/modal.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_modal_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/modal.scss */ "./src/style/modal.scss");
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

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



var WadiModal = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(WadiModal, _elementorModules$fro);

  var _super = _createSuper(WadiModal);

  function WadiModal() {
    _classCallCheck(this, WadiModal);

    return _super.apply(this, arguments);
  }

  _createClass(WadiModal, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          wadiModalTheWrapper: '#wadi_modal_wrapper_' + this.$element[0].getAttribute("data-id"),
          wadiModalWrapper: '.wadi_modal_wrapper'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      var elements = {
        $wadiModalWrapper: this.findElement(selectors.wadiModalWrapper)
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

      var $wadiModalWrapper = this.elements.wadiModalWrapper;
      var elementsSettings = this.getElementSettings();
      var currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
      var selectors = this.getSettings('selectors');

      var wadiModalSettings = _objectSpread(_objectSpread(_objectSpread({
        wadiModalWrapperElement: selectors.wadiModalWrapper,
        wadiModalWrapperElem: selectors.wadiModalWrapper + '_' + this.$element[0].getAttribute("data-id"),
        wadiModalTrigger: '#modal_trigger_' + this.$element[0].getAttribute("data-id"),
        wadiModalTriggerType: elementsSettings.wadi_modal_trigger_type ? elementsSettings.wadi_modal_trigger_type : 'button'
      }, elementsSettings.wadi_modal_trigger_type === 'page_load' && {
        wadiModalTriggerDelay: elementsSettings.wadi_modal_trigger_modal_delay ? elementsSettings.wadi_modal_trigger_modal_delay : 1000
      }), {}, {
        wadiModalAnimationSwitch: 'yes' === elementsSettings.wadi_modal_content_entrance_animation_switch ? true : false
      }, 'yes' === elementsSettings.wadi_modal_content_entrance_animation_switch && {
        wadiModalAnimation: elementsSettings.wadi_modal_content_entrance_animation ? elementsSettings.wadi_modal_content_entrance_animation : 'slide-in-top',
        wadiModalAnimationEntranceDuration: elementsSettings.wadi_modal_content_entrance_animation_duration ? elementsSettings.wadi_modal_content_entrance_animation_duration : '500'
      }), {}, {
        // wadiModalAnimationDuration: elementsSettings.wadi_modal_animation_duration ? elementsSettings.wadi_modal_animation_duration : 300,
        wadiModalAnimationDelay: elementsSettings.wadi_modal_animation_delay ? elementsSettings.wadi_modal_animation_delay : 0,
        wadiModalCloseOnEsc: 'yes' === elementsSettings.wadi_modal_close_on_esc ? true : false,
        wadiModalCloseOnClickOutside: 'yes' === elementsSettings.wadi_modal_close_on_click_outside ? true : false
      });

      _this.wadiModal($wadiModalWrapper, wadiModalSettings);
    }
  }, {
    key: "wadiModal",
    value: function wadiModal(elem, settings) {
      // console.log("settings",settings);
      var modalWrapperItems = document.querySelectorAll(settings.wadiModalWrapperElement);

      var modalWrapperItemsArr = _toConsumableArray(modalWrapperItems); // console.log('modalWrapperItems', modalWrapperItems);
      // const theModalContainers = modalWrapperItems.querySelectorAll('.wadi_modal_container');
      // const theModalContainersArr = [...theModalContainers];
      // console.log('theModalContainers', theModalContainers);


      this.openModals(modalWrapperItemsArr, settings);
      this.closeModals(settings);
      this.triggerModal(settings);
    }
  }, {
    key: "openModals",
    value: function openModals(modals, settings) {
      var _this2 = this;

      var elementTriggerModal;

      switch (settings.wadiModalTriggerType) {
        case 'button':
          elementTriggerModal = document.querySelector("#wadi_modal_wrapper_".concat(this.$element[0].getAttribute("data-id"), " .wadi_modal_button_trigger_container .wadi_modal_trigger_button_text"));
          break;

        case 'page_load':
          elementTriggerModal = document.querySelector("#wadi_modal_wrapper_".concat(this.$element[0].getAttribute("data-id")));
          break;

        case 'icon':
          elementTriggerModal = document.querySelector("#wadi_modal_wrapper_".concat(this.$element[0].getAttribute("data-id"), " .wadi_modal_trigger_icon_container .wadi_modal_trigger_icon_content"));
          break;

        case 'image':
          elementTriggerModal = document.querySelector("#wadi_modal_wrapper_".concat(this.$element[0].getAttribute("data-id"), " .wadi_modal_trigger_image_container img"));
          break;

        case 'text':
          elementTriggerModal = document.querySelector("#wadi_modal_wrapper_".concat(this.$element[0].getAttribute("data-id"), " .wadi_modal_trigger_text_container .wadi_modal_text_trigger"));
      }

      elementTriggerModal.addEventListener('click', function (e) {
        var elementTrigger = e.target.closest("#modal_trigger_".concat(_this2.$element[0].getAttribute("data-id")));

        if (elementTrigger) {
          var getTriggerTargetId = elementTrigger.getAttribute('data-target');
          var currentModal = document.querySelector("#".concat(getTriggerTargetId));

          if (settings.wadiModalAnimationSwitch) {
            var targetModalAnimation = document.querySelector("#".concat(getTriggerTargetId)).querySelector(".modal-content");
            targetModalAnimation.style.animationDuration = "".concat(settings.wadiModalAnimationEntranceDuration, "ms");
          }

          document.querySelector("#".concat(getTriggerTargetId)).classList.add('active');
        }
      });
    }
  }, {
    key: "closeModals",
    value: function closeModals(settings) {
      // Close Modal on Keyboard Escape
      this.closeModalsKeyboard(settings); // Close Modal on Mouse Click Outside or on Close Button

      this.closeModalsMouse(settings);
    }
  }, {
    key: "closeModalsKeyboard",
    value: function closeModalsKeyboard(settings) {
      var _this3 = this;

      window.addEventListener('keydown', function (e) {
        e = e || window.event;
        var isEscape = false;

        if ("key" in e) {
          isEscape = e.key === "Escape" || e.key === "Esc";
        } else {
          isEscape = e.keyCode === 27;
        } // If is Escape key pressed and modal is active And close on esc is true then close the modal


        if (isEscape && settings.wadiModalCloseOnEsc) {
          _this3.hideModalKeyEvents(e);
        }
      });
    }
  }, {
    key: "closeModalsMouse",
    value: function closeModalsMouse(settings) {
      var selectors = this.getSettings('selectors');
      document.querySelector("#wadi_modal_wrapper_".concat(this.$element[0].getAttribute("data-id"))).addEventListener('click', function (e) {
        var checkModal = e.target.closest(selectors.wadiModalWrapper);

        if (checkModal) {
          var _e$target;

          var currentOpenedModal = e.target.closest('.modal-content');

          if ((_e$target = e.target) !== null && _e$target !== void 0 && _e$target.closest('.wadi_modal')) {
            var _e$target2;

            var getClassesList = (_e$target2 = e.target) === null || _e$target2 === void 0 ? void 0 : _e$target2.closest('.wadi_modal').classList; // Close Modal if clicked outside the modal content OR if clicked on the close button with data-dismiss="modal"

            if (settings.wadiModalCloseOnClickOutside) {
              if (currentOpenedModal === null && getClassesList.contains('active')) {
                getClassesList.remove('active');
              }
            }

            if (e.target.getAttribute('data-dismiss') === 'modal') {
              getClassesList.remove('active');
            }
          }
        }
      });
    }
  }, {
    key: "hideModalKeyEvents",
    value: function hideModalKeyEvents(e) {
      var currentModal = e.target.querySelector("#wadi_modal_wrapper_".concat(this.$element[0].getAttribute("data-id"))) || e.target.closest("#wadi_modal_wrapper_".concat(this.$element[0].getAttribute("data-id")));

      if (currentModal) {
        var theCurrentModal = currentModal.querySelector('.wadi_modal');

        if (theCurrentModal.classList.contains('active')) {
          theCurrentModal.classList.remove('active');
        }
      }
    }
  }, {
    key: "triggerModal",
    value: function triggerModal(settings) {
      var _this4 = this;

      if (settings.wadiModalTriggerType === 'page_load') {
        setTimeout(function () {
          var modalTrigger = document.querySelector("#modal_trigger_".concat(_this4.$element[0].getAttribute("data-id")));

          if (modalTrigger) {
            modalTrigger.click();
          }
        }, settings.wadiModalTriggerDelay);
      }
    }
  }]);

  return WadiModal;
}(elementorModules.frontend.handlers.Base);

jQuery(window).on("elementor/frontend/init", function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(WadiModal, {
      $element: $element
    });
  };

  elementorFrontend.hooks.addAction("frontend/element_ready/wadi-modal-addon.default", addHandler);
});
}();
/******/ })()
;
//# sourceMappingURL=wadi-modal.js.map