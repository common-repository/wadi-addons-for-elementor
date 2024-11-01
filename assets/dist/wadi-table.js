/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/style/table.scss":
/*!******************************!*\
  !*** ./src/style/table.scss ***!
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
  !*** ./src/table.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_table_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/table.scss */ "./src/style/table.scss");
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



var WadiTable = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(WadiTable, _elementorModules$fro);

  var _super = _createSuper(WadiTable);

  function WadiTable() {
    _classCallCheck(this, WadiTable);

    return _super.apply(this, arguments);
  }

  _createClass(WadiTable, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          wadiMediaSwiper: '.swiper'
        },
        tableState: {
          isLoaded: false
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      var elements = {
        $wadiMediaSwiperElem: this.findElement(selectors.wadiMediaSwiper)
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

      var wadiTableSettings = _objectSpread(_objectSpread(_objectSpread(_objectSpread(_objectSpread({
        wadiTableElement: ".wadi_table_" + this.$element[0].getAttribute("data-id"),
        // waduTableHeadersDisplay: 'yes' === elementsSettings.wadi_table_headers_display_switcher ? true : false,
        // waduTableBodyDisplay: 'yes' === elementsSettings.wadi_table_body_display_switcher ? true : false,
        wadiTableContentType: elementsSettings.wadi_table_data_content_type ? elementsSettings.wadi_table_data_content_type : 'custom',
        wadiTableContentURLType: elementsSettings.wadi_table_url_type ? elementsSettings.wadi_table_url_type : 'local'
      }, 'remote' === elementsSettings.wadi_table_url_type && {
        wadiTableContentRemoteURL: elementsSettings.wadi_table_csv_remote_link ? elementsSettings.wadi_table_csv_remote_link : ''
      }), {}, {
        wadiTableCSVSeparator: elementsSettings.wadi_table_csv_file_content_separator ? elementsSettings.wadi_table_csv_file_content_separator : ',',
        wadiTableSorting: 'yes' === elementsSettings.wadi_table_sorting_switcher ? true : false,
        wadiTablePagination: 'yes' === elementsSettings.wadi_table_pagination_switcher ? true : false
      }, 'yes' === elementsSettings.wadi_table_pagination_switcher && {
        wadiTablePaginationNextText: elementsSettings.wadi_table_pagination_next_text ? elementsSettings.wadi_table_pagination_next_text : 'Next',
        wadiTablePaginationPrevText: elementsSettings.wadi_table_pagination_prev_text ? elementsSettings.wadi_table_pagination_prev_text : 'Previous',
        wadiTablePaginationFirstText: elementsSettings.wadi_table_pagination_first_text ? elementsSettings.wadi_table_pagination_first_text : 'First',
        wadiTablePaginationLastText: elementsSettings.wadi_table_pagination_last_text ? elementsSettings.wadi_table_pagination_last_text : 'Last'
      }), {}, {
        wadiTableSearch: 'yes' === elementsSettings.wadi_table_search_table_switcher ? true : false
      }, 'yes' === elementsSettings.wadi_table_search_table_switcher && {
        wadiTableSearchText: elementsSettings.wadi_table_search_table_text ? elementsSettings.wadi_table_search_table_text : '',
        wadiTableSearchInputPlaceholder: elementsSettings.wadi_table_search_input_placeholder ? elementsSettings.wadi_table_search_input_placeholder : '',
        wadiTableNoRecordsText: elementsSettings.wadi_table_no_records_text ? elementsSettings.wadi_table_no_records_text : 'No matching records found'
      }), {}, {
        wadiTableShowEntries: 'yes' === elementsSettings.wadi_show_entries_switcher ? true : false,
        wadiTableRecordsInfo: 'yes' === elementsSettings.wadi_table_info_switcher ? true : false,
        wadiTableCSVFile: elementsSettings.wadi_table_csv_file ? elementsSettings.wadi_table_csv_file : '',
        // Additional Features
        // Buttons
        wadiTableDownloadButtons: 'yes' === elementsSettings.wadi_table_download_buttons_switcher ? true : false
      }, 'yes' === elementsSettings.wadi_table_download_buttons_switcher && {
        wadiTableCopyButton: 'yes' === elementsSettings.wadi_table_copy_button_switcher ? true : false,
        wadiTableCSVButton: 'yes' === elementsSettings.wadi_table_csv_button_switcher ? true : false,
        wadiTableExcelButton: 'yes' === elementsSettings.wadi_table_excel_button_switcher ? true : false
      }), {}, {
        // Table Responsive
        wadiTableResponsive: 'yes' === elementsSettings.wadi_table_responsive_switcher ? true : false
      });

      _this.wadiTable(jQuery, wadiTableSettings);
    }
  }, {
    key: "wadiTable",
    value: function wadiTable($, settings) {
      var _this = this;

      var tableState = this.getSettings('tableState');
      console.log("Settings", settings);
      var wadiTableElement = settings.wadiTableElement;
      var e = document.createElement('div');
      e.classList.add('wadi_loader');
      e.innerHTML = 'Loading...';
      document.querySelector(wadiTableElement).appendChild(e);
      var elementPagination = $('.dataTables_paginate'); // document.addEventListener('DOMContentLoaded', function(){
      // });

      var wadiTableCSVContentLocalURL = settings.wadiTableCSVFile.url;

      var wadiTableOptions = _objectSpread(_objectSpread({
        "paging": settings.wadiTablePagination,
        "responsive": settings.wadiTableResponsive,
        "searching": settings.wadiTableSearch,
        "ordering": settings.wadiTableSorting,
        "info": settings.wadiTableRecordsInfo,
        "lengthChange": settings.wadiTableShowEntries
      }, settings.wadiTableDownloadButtons == true ? {
        "dom": 'Blfrtip',
        // This is shortcut for elements buttons, pagination, info, length change
        "buttons": [].concat(_toConsumableArray(settings.wadiTableCopyButton && ['copy'] || []), _toConsumableArray(settings.wadiTableCSVButton && ['csv'] || []), _toConsumableArray(settings.wadiTableExcelButton && ['excel'] || []))
      } : {
        "dom": 'lfrtip' // This is shortcut for elements buttons, pagination, info, length change

      }), {}, {
        "lengthMenu": [5, 10, 25, 50, 75, 100],
        "oLanguage": {
          "sSearch": settings.wadiTableSearchText,
          "sSearchPlaceholder": settings.wadiTableSearchInputPlaceholder,
          // "sLengthMenu": 'green',
          "sZeroRecords": settings.wadiTableNoRecordsText,
          "oPaginate": {
            "sFirst": settings.wadiTablePaginationFirstText,
            "sLast": settings.wadiTablePaginationLastText,
            "sNext": settings.wadiTablePaginationNextText,
            "sPrevious": settings.wadiTablePaginationPrevText
          },
          "aria": {
            "sortAscending": ": activate to sort column ascending",
            "sortDescending": ": activate to sort column descending"
          }
        },
        "createdRow": function createdRow(row, data, dataIndex) {
          // Add class to row
          if (!row.classList.contains('.wadi_table_row')) {
            row.classList.add('wadi_table_row');
          } // Add class to columns


          var tds = row.querySelectorAll('td');

          var tdsArr = _toConsumableArray(tds);

          tdsArr.forEach(function (td, index) {
            if (!td.classList.contains('.wadi_table_col')) {
              td.classList.add('wadi_table_col');
            }
          });
        }
      }, settings.wadiTablePagination == true && {
        "drawCallback": function drawCallback() {
          // document.querySelector('.dataTables_paginate').classList.add('wadi_table_pagination');
          document.querySelectorAll('.dataTables_paginate').forEach(function (elem) {
            if (_this.hasSomeParentTheClass(elem, 'wadi_table_container')) {
              elem.classList.add('wadi_table_pagination');
            }
          });
        }
      }); // Hide Table Button if no buttons are selected


      if (settings.wadiTableDownloadButtons === false) {
        wadiTableOptions.buttons = [];
      } // https://datatables.net/reference/option/ Some other options


      if (settings.wadiTableContentType === 'custom') {
        tableState.isLoaded = true;
        document.querySelector(wadiTableElement).querySelector('.wadi_loader').style = 'display:none';
        $(wadiTableElement).DataTable(_objectSpread({}, wadiTableOptions));
      } else if (settings.wadiTableContentType === 'csv') {
        if (settings.wadiTableContentURLType === 'local') {
          if (wadiTableCSVContentLocalURL) {
            tableState.isLoaded = false;

            if (tableState.isLoaded == false) {
              document.querySelector(wadiTableElement).querySelector('.wadi_loader').style = 'display:block;';
            }

            jQuery.ajax({
              url: wadiTableCSVContentLocalURL,
              type: "GET",
              success: function success(res) {
                var rowsData = res.split(/\r?\n|\r/);
                var TableHeader = rowsData.shift().split(settings.wadiTableCSVSeparator);
                var wadiCSVTableData = [];
                rowsData.map(function (row, index) {
                  var rowData = row.split(settings.wadiTableCSVSeparator);
                  wadiCSVTableData.push(rowData);
                });
                var wadiCSVTableColumns = [];
                TableHeader.map(function (col, index) {
                  wadiCSVTableColumns.push({
                    title: col
                  });
                });
                tableState.isLoaded = true;
                document.querySelector(wadiTableElement).querySelector('.wadi_loader').style = 'display:none';
                $(wadiTableElement).DataTable(_objectSpread(_objectSpread({}, wadiTableOptions), {}, {
                  "data": wadiCSVTableData,
                  dataSrc: "",
                  columns: wadiCSVTableColumns
                }));
              }
            });
          }
        } else if (settings.wadiTableContentURLType === 'remote') {
          var wadiCSVTableURL = settings.wadiTableContentRemoteURL;

          if (wadiCSVTableURL) {
            tableState.isLoaded = false;

            if (tableState.isLoaded == false) {
              document.querySelector(wadiTableElement).querySelector('.wadi_loader').style = 'display:block;';
            }

            jQuery.ajax({
              url: wadiCSVTableURL,
              type: "GET",
              success: function success(res) {
                var rowsData = res.split(/\r?\n|\r/);
                var TableHeader = rowsData.shift().split(settings.wadiTableCSVSeparator);
                var wadiCSVTableData = [];
                rowsData.map(function (row, index) {
                  var rowData = row.split(settings.wadiTableCSVSeparator);
                  wadiCSVTableData.push(rowData);
                });
                var wadiCSVTableColumns = [];
                TableHeader.map(function (col, index) {
                  wadiCSVTableColumns.push({
                    title: col
                  });
                });
                tableState.isLoaded = true;
                document.querySelector(wadiTableElement).querySelector('.wadi_loader').style = 'display:none';
                $(wadiTableElement).DataTable(_objectSpread(_objectSpread({}, wadiTableOptions), {}, {
                  "data": wadiCSVTableData,
                  dataSrc: "",
                  columns: wadiCSVTableColumns
                }));
              }
            });
          }
        }
      }
    } // returns true if the element or one of its parents has the class classname

  }, {
    key: "hasSomeParentTheClass",
    value: function hasSomeParentTheClass(element, classname) {
      if (element.className.split(' ').indexOf(classname) >= 0) return true;
      return element.parentNode && this.hasSomeParentTheClass(element.parentNode, classname);
    }
  }]);

  return WadiTable;
}(elementorModules.frontend.handlers.Base);

jQuery(window).on("elementor/frontend/init", function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(WadiTable, {
      $element: $element
    });
  };

  elementorFrontend.hooks.addAction("frontend/element_ready/wadi-table-addon.default", addHandler);
});
}();
/******/ })()
;
//# sourceMappingURL=wadi-table.js.map