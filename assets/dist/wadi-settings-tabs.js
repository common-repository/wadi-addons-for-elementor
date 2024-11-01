/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./admin/style/settings-tabs.scss":
/*!****************************************!*\
  !*** ./admin/style/settings-tabs.scss ***!
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
  !*** ./admin/settings-tabs.js ***!
  \********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_settings_tabs_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/settings-tabs.scss */ "./admin/style/settings-tabs.scss");
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }



var wadiSettingsTabs = /*#__PURE__*/function () {
  function wadiSettingsTabs() {
    _classCallCheck(this, wadiSettingsTabs);
  }

  _createClass(wadiSettingsTabs, [{
    key: "init",
    value: function init() {
      this.Tabs();
    }
  }, {
    key: "Tabs",
    value: function Tabs() {
      var tab = document.querySelector('.tabs');
      var tabButtons = tab.querySelectorAll('[role="tab"]');
      var tabPanels = Array.from(tab.querySelectorAll('[role="tabpanel"]'));

      function tabClickHandler(e) {
        // e.preventDefault();
        //Hide All Tabpane
        tabPanels.forEach(function (panel) {
          panel.hidden = 'true';
        }); //Deselect Tab Button

        tabButtons.forEach(function (button) {
          button.setAttribute('aria-selected', 'false');
        }); //Mark New Tab

        e.currentTarget.setAttribute('aria-selected', 'true'); //Show New Tab

        var id = e.currentTarget.id;
        var currentTab = tabPanels.find(function (panel) {
          return panel.getAttribute('aria-labelledby') === id;
        });
        currentTab.hidden = false;
      }

      tabButtons.forEach(function (button) {
        button.addEventListener('click', tabClickHandler);
      });

      function openByHash() {
        var tab = document.querySelector('.tabs');
        var tabButtons = tab.querySelectorAll('[role="tab"]');
        var hash = window.location.hash;

        if (hash) {
          var splittedHash = hash.split('=');

          if (splittedHash) {
            document.querySelector("#".concat(splittedHash[1])).click();
          }
        } else {
          var tabHref = "tab=".concat(tabButtons[0].href);

          if (tabHref) {
            var splittedHref = tabHref.split('#');
            window.location.hash = "#".concat(splittedHref[1]);
          }
        }
      }

      openByHash();
    }
  }]);

  return wadiSettingsTabs;
}();

var wadiTabs = new wadiSettingsTabs();
wadiTabs.init();
}();
/******/ })()
;
//# sourceMappingURL=wadi-settings-tabs.js.map