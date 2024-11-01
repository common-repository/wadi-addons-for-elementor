/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./extLib/imageComarison.js":
/*!**********************************!*\
  !*** ./extLib/imageComarison.js ***!
  \**********************************/
/***/ (function() {

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

(function ($) {
  $.fn.wa_image_comparison = function (options) {
    var options = $.extend(_objectSpread(_objectSpread({
      default_offset_pct: 0.5,
      orientation: 'horizontal',
      before_label: 'Before',
      after_label: 'After',
      no_overlay: false,
      move_slider_on_hover: false,
      move_with_handle_only: true,
      click_to_move: false,
      show_drag_compass: true
    }, options.show_drag_compass && {
      show_slider: true,
      show_compass: false
    }), {}, {
      show_labels_on_hover: false
    }), options);
    return this.each(function () {
      var sliderPct = options.default_offset_pct;
      var container = $(this);
      var sliderOrientation = options.orientation;
      var beforeDirection = sliderOrientation === 'vertical' ? 'down' : 'left';
      var afterDirection = sliderOrientation === 'vertical' ? 'up' : 'right';
      container.wrap("<div class='wa_image_comparison-wrapper wa_image_comparison-" + sliderOrientation + "'></div>");

      if (!options.no_overlay) {
        container.append("<div class='wa_image_comparison-overlay ".concat(options.show_labels_on_hover ? 'wa_image_comparison_label_show_on_hover' : '', "'></div>"));
        var overlay = container.find(".wa_image_comparison-overlay");
        overlay.append("<div class='wa_image_comparison-before-label' data-content='".concat(options.before_label, "'></div>"));
        overlay.append("<div class='wa_image_comparison-after-label' data-content='".concat(options.after_label, "'></div>"));
      }

      var beforeImg = container.find("img:first");
      var afterImg = container.find("img:last");
      container.append("<div class='wa_image_comparison-handle'></div>");
      var slider = container.find(".wa_image_comparison-handle");
      var slider = container.find(".wa_image_comparison-handle");
      !options.show_slider && slider.addClass('wa_image_comparison-hide-drag-compass');
      !options.show_compass && slider.addClass('wa_hide_compass');
      !options.show_drag_compass && slider.css("opacity", '0');
      slider.append("<span class='wa_image_comparison-" + beforeDirection + "-arrow'></span>");
      slider.append("<span class='wa_image_comparison-" + afterDirection + "-arrow'></span>");
      container.addClass("wa_image_comparison-container");
      beforeImg.addClass("wa_image_comparison-before");
      afterImg.addClass("wa_image_comparison-after");

      var calcOffset = function calcOffset(dimensionPct) {
        var w = beforeImg.width();
        var h = beforeImg.height();
        return {
          w: w + "px",
          h: h + "px",
          cw: dimensionPct * w + "px",
          ch: dimensionPct * h + "px"
        };
      };

      var adjustContainer = function adjustContainer(offset) {
        if (sliderOrientation === 'vertical') {
          beforeImg.css("clip", "rect(0," + offset.w + "," + offset.ch + ",0)");
          afterImg.css("clip", "rect(" + offset.ch + "," + offset.w + "," + offset.h + ",0)");
        } else {
          beforeImg.css("clip", "rect(0," + offset.cw + "," + offset.h + ",0)");
          afterImg.css("clip", "rect(0," + offset.w + "," + offset.h + "," + offset.cw + ")");
        }

        container.css("height", offset.h);
      };

      var adjustSlider = function adjustSlider(pct) {
        var offset = calcOffset(pct);
        slider.css(sliderOrientation === "vertical" ? "top" : "left", sliderOrientation === "vertical" ? offset.ch : offset.cw);
        adjustContainer(offset);
      }; // Return the number specified or the min/max number if it outside the range given.


      var minMaxNumber = function minMaxNumber(num, min, max) {
        return Math.max(min, Math.min(max, num));
      }; // Calculate the slider percentage based on the position.


      var getSliderPercentage = function getSliderPercentage(positionX, positionY) {
        var sliderPercentage = sliderOrientation === 'vertical' ? (positionY - offsetY) / imgHeight : (positionX - offsetX) / imgWidth;
        return minMaxNumber(sliderPercentage, 0, 1);
      };

      $(window).on("resize.wa_image_comparison", function (e) {
        adjustSlider(sliderPct);
      });
      var offsetX = 0;
      var offsetY = 0;
      var imgWidth = 0;
      var imgHeight = 0;

      var onMoveStart = function onMoveStart(e) {
        if ((e.distX > e.distY && e.distX < -e.distY || e.distX < e.distY && e.distX > -e.distY) && sliderOrientation !== 'vertical') {
          e.preventDefault();
        } else if ((e.distX < e.distY && e.distX < -e.distY || e.distX > e.distY && e.distX > -e.distY) && sliderOrientation === 'vertical') {
          e.preventDefault();
        }

        container.addClass("active");
        offsetX = container.offset().left;
        offsetY = container.offset().top;
        imgWidth = beforeImg.width();
        imgHeight = beforeImg.height();
      };

      var onMove = function onMove(e) {
        if (container.hasClass("active")) {
          sliderPct = getSliderPercentage(e.pageX, e.pageY);
          adjustSlider(sliderPct);
        }
      };

      var onMoveEnd = function onMoveEnd() {
        container.removeClass("active");
      };

      var moveTarget = options.move_with_handle_only ? slider : container;
      moveTarget.on("movestart", onMoveStart);
      moveTarget.on("move", onMove);
      moveTarget.on("moveend", onMoveEnd);

      if (options.move_slider_on_hover) {
        container.on("mouseenter", onMoveStart);
        container.on("mousemove", onMove);
        container.on("mouseleave", onMoveEnd);
      }

      slider.on("touchmove", function (e) {
        e.preventDefault();
      });
      container.find("img").on("mousedown", function (event) {
        event.preventDefault();
      });

      if (options.click_to_move) {
        container.on('click', function (e) {
          offsetX = container.offset().left;
          offsetY = container.offset().top;
          imgWidth = beforeImg.width();
          imgHeight = beforeImg.height();
          sliderPct = getSliderPercentage(e.pageX, e.pageY);
          adjustSlider(sliderPct);
        });
      }

      $(window).trigger("resize.wa_image_comparison");
    });
  };
})(jQuery);

/***/ }),

/***/ "./extLib/jquery.event.move.js":
/*!*************************************!*\
  !*** ./extLib/jquery.event.move.js ***!
  \*************************************/
/***/ (function(module, exports) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;// DOM.event.move
//
// 2.0.0
//
// Stephen Band
//
// Triggers 'movestart', 'move' and 'moveend' events after
// mousemoves following a mousedown cross a distance threshold,
// similar to the native 'dragstart', 'drag' and 'dragend' events.
// Move events are throttled to animation frames. Move event objects
// have the properties:
//
// pageX:
// pageY:     Page coordinates of pointer.
// startX:
// startY:    Page coordinates of pointer at movestart.
// distX:
// distY:     Distance the pointer has moved since movestart.
// deltaX:
// deltaY:    Distance the finger has moved since last event.
// velocityX:
// velocityY: Average velocity over last few events.
(function (fn) {
  if (true) {
    !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_FACTORY__ = (fn),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
  } else {}
})(function () {
  var assign = Object.assign || window.jQuery && jQuery.extend; // Number of pixels a pressed pointer travels before movestart
  // event is fired.

  var threshold = 8; // Shim for requestAnimationFrame, falling back to timer. See:
  // see http://paulirish.com/2011/requestanimationframe-for-smart-animating/

  var requestFrame = function () {
    return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (fn, element) {
      return window.setTimeout(function () {
        fn();
      }, 25);
    };
  }(); // Shim for customEvent
  // see https://developer.mozilla.org/en-US/docs/Web/API/CustomEvent/CustomEvent#Polyfill


  (function () {
    if (typeof window.CustomEvent === "function") return false;

    function CustomEvent(event, params) {
      params = params || {
        bubbles: false,
        cancelable: false,
        detail: undefined
      };
      var evt = document.createEvent('CustomEvent');
      evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
      return evt;
    }

    CustomEvent.prototype = window.Event.prototype;
    window.CustomEvent = CustomEvent;
  })();

  var ignoreTags = {
    textarea: true,
    input: true,
    select: true,
    button: true
  };
  var mouseevents = {
    move: 'mousemove',
    cancel: 'mouseup dragstart',
    end: 'mouseup'
  };
  var touchevents = {
    move: 'touchmove',
    cancel: 'touchend',
    end: 'touchend'
  };
  var rspaces = /\s+/; // DOM Events

  var eventOptions = {
    bubbles: true,
    cancelable: true
  };
  var eventsSymbol = typeof Symbol === "function" ? Symbol('events') : {};

  function createEvent(type) {
    return new CustomEvent(type, eventOptions);
  }

  function getEvents(node) {
    return node[eventsSymbol] || (node[eventsSymbol] = {});
  }

  function on(node, types, fn, data, selector) {
    types = types.split(rspaces);
    var events = getEvents(node);
    var i = types.length;
    var handlers, type;

    function handler(e) {
      fn(e, data);
    }

    while (i--) {
      type = types[i];
      handlers = events[type] || (events[type] = []);
      handlers.push([fn, handler]);
      node.addEventListener(type, handler);
    }
  }

  function off(node, types, fn, selector) {
    types = types.split(rspaces);
    var events = getEvents(node);
    var i = types.length;
    var type, handlers, k;

    if (!events) {
      return;
    }

    while (i--) {
      type = types[i];
      handlers = events[type];

      if (!handlers) {
        continue;
      }

      k = handlers.length;

      while (k--) {
        if (handlers[k][0] === fn) {
          node.removeEventListener(type, handlers[k][1]);
          handlers.splice(k, 1);
        }
      }
    }
  }

  function trigger(node, type, properties) {
    // Don't cache events. It prevents you from triggering an event of a
    // given type from inside the handler of another event of that type.
    var event = createEvent(type);

    if (properties) {
      assign(event, properties);
    }

    node.dispatchEvent(event);
  } // Constructors


  function Timer(fn) {
    var callback = fn,
        active = false,
        running = false;

    function trigger(time) {
      if (active) {
        callback();
        requestFrame(trigger);
        running = true;
        active = false;
      } else {
        running = false;
      }
    }

    this.kick = function (fn) {
      active = true;

      if (!running) {
        trigger();
      }
    };

    this.end = function (fn) {
      var cb = callback;

      if (!fn) {
        return;
      } // If the timer is not running, simply call the end callback.


      if (!running) {
        fn();
      } // If the timer is running, and has been kicked lately, then
      // queue up the current callback and the end callback, otherwise
      // just the end callback.
      else {
        callback = active ? function () {
          cb();
          fn();
        } : fn;
        active = true;
      }
    };
  } // Functions


  function noop() {}

  function preventDefault(e) {
    e.preventDefault();
  }

  function isIgnoreTag(e) {
    return !!ignoreTags[e.target.tagName.toLowerCase()];
  }

  function isPrimaryButton(e) {
    // Ignore mousedowns on any button other than the left (or primary)
    // mouse button, or when a modifier key is pressed.
    return e.which === 1 && !e.ctrlKey && !e.altKey;
  }

  function identifiedTouch(touchList, id) {
    var i, l;

    if (touchList.identifiedTouch) {
      return touchList.identifiedTouch(id);
    } // touchList.identifiedTouch() does not exist in
    // webkit yet… we must do the search ourselves...


    i = -1;
    l = touchList.length;

    while (++i < l) {
      if (touchList[i].identifier === id) {
        return touchList[i];
      }
    }
  }

  function changedTouch(e, data) {
    var touch = identifiedTouch(e.changedTouches, data.identifier); // This isn't the touch you're looking for.

    if (!touch) {
      return;
    } // Chrome Android (at least) includes touches that have not
    // changed in e.changedTouches. That's a bit annoying. Check
    // that this touch has changed.


    if (touch.pageX === data.pageX && touch.pageY === data.pageY) {
      return;
    }

    return touch;
  } // Handlers that decide when the first movestart is triggered


  function mousedown(e) {
    // Ignore non-primary buttons
    if (!isPrimaryButton(e)) {
      return;
    } // Ignore form and interactive elements


    if (isIgnoreTag(e)) {
      return;
    }

    on(document, mouseevents.move, mousemove, e);
    on(document, mouseevents.cancel, mouseend, e);
  }

  function mousemove(e, data) {
    checkThreshold(e, data, e, removeMouse);
  }

  function mouseend(e, data) {
    removeMouse();
  }

  function removeMouse() {
    off(document, mouseevents.move, mousemove);
    off(document, mouseevents.cancel, mouseend);
  }

  function touchstart(e) {
    // Don't get in the way of interaction with form elements
    if (ignoreTags[e.target.tagName.toLowerCase()]) {
      return;
    }

    var touch = e.changedTouches[0]; // iOS live updates the touch objects whereas Android gives us copies.
    // That means we can't trust the touchstart object to stay the same,
    // so we must copy the data. This object acts as a template for
    // movestart, move and moveend event objects.

    var data = {
      target: touch.target,
      pageX: touch.pageX,
      pageY: touch.pageY,
      identifier: touch.identifier,
      // The only way to make handlers individually unbindable is by
      // making them unique.
      touchmove: function touchmove(e, data) {
        _touchmove(e, data);
      },
      touchend: function touchend(e, data) {
        _touchend(e, data);
      }
    };
    on(document, touchevents.move, data.touchmove, data);
    on(document, touchevents.cancel, data.touchend, data);
  }

  function _touchmove(e, data) {
    var touch = changedTouch(e, data);

    if (!touch) {
      return;
    }

    checkThreshold(e, data, touch, removeTouch);
  }

  function _touchend(e, data) {
    var touch = identifiedTouch(e.changedTouches, data.identifier);

    if (!touch) {
      return;
    }

    removeTouch(data);
  }

  function removeTouch(data) {
    off(document, touchevents.move, data.touchmove);
    off(document, touchevents.cancel, data.touchend);
  }

  function checkThreshold(e, data, touch, fn) {
    var distX = touch.pageX - data.pageX;
    var distY = touch.pageY - data.pageY; // Do nothing if the threshold has not been crossed.

    if (distX * distX + distY * distY < threshold * threshold) {
      return;
    }

    triggerStart(e, data, touch, distX, distY, fn);
  }

  function triggerStart(e, data, touch, distX, distY, fn) {
    var touches = e.targetTouches;
    var time = e.timeStamp - data.timeStamp; // Create a movestart object with some special properties that
    // are passed only to the movestart handlers.

    var template = {
      altKey: e.altKey,
      ctrlKey: e.ctrlKey,
      shiftKey: e.shiftKey,
      startX: data.pageX,
      startY: data.pageY,
      distX: distX,
      distY: distY,
      deltaX: distX,
      deltaY: distY,
      pageX: touch.pageX,
      pageY: touch.pageY,
      velocityX: distX / time,
      velocityY: distY / time,
      identifier: data.identifier,
      targetTouches: touches,
      finger: touches ? touches.length : 1,
      enableMove: function enableMove() {
        this.moveEnabled = true;
        this.enableMove = noop;
        e.preventDefault();
      }
    }; // Trigger the movestart event.

    trigger(data.target, 'movestart', template); // Unbind handlers that tracked the touch or mouse up till now.

    fn(data);
  } // Handlers that control what happens following a movestart


  function activeMousemove(e, data) {
    var timer = data.timer;
    data.touch = e;
    data.timeStamp = e.timeStamp;
    timer.kick();
  }

  function activeMouseend(e, data) {
    var target = data.target;
    var event = data.event;
    var timer = data.timer;
    removeActiveMouse();
    endEvent(target, event, timer, function () {
      // Unbind the click suppressor, waiting until after mouseup
      // has been handled.
      setTimeout(function () {
        off(target, 'click', preventDefault);
      }, 0);
    });
  }

  function removeActiveMouse() {
    off(document, mouseevents.move, activeMousemove);
    off(document, mouseevents.end, activeMouseend);
  }

  function activeTouchmove(e, data) {
    var event = data.event;
    var timer = data.timer;
    var touch = changedTouch(e, event);

    if (!touch) {
      return;
    } // Stop the interface from gesturing


    e.preventDefault();
    event.targetTouches = e.targetTouches;
    data.touch = touch;
    data.timeStamp = e.timeStamp;
    timer.kick();
  }

  function activeTouchend(e, data) {
    var target = data.target;
    var event = data.event;
    var timer = data.timer;
    var touch = identifiedTouch(e.changedTouches, event.identifier); // This isn't the touch you're looking for.

    if (!touch) {
      return;
    }

    removeActiveTouch(data);
    endEvent(target, event, timer);
  }

  function removeActiveTouch(data) {
    off(document, touchevents.move, data.activeTouchmove);
    off(document, touchevents.end, data.activeTouchend);
  } // Logic for triggering move and moveend events


  function updateEvent(event, touch, timeStamp) {
    var time = timeStamp - event.timeStamp;
    event.distX = touch.pageX - event.startX;
    event.distY = touch.pageY - event.startY;
    event.deltaX = touch.pageX - event.pageX;
    event.deltaY = touch.pageY - event.pageY; // Average the velocity of the last few events using a decay
    // curve to even out spurious jumps in values.

    event.velocityX = 0.3 * event.velocityX + 0.7 * event.deltaX / time;
    event.velocityY = 0.3 * event.velocityY + 0.7 * event.deltaY / time;
    event.pageX = touch.pageX;
    event.pageY = touch.pageY;
  }

  function endEvent(target, event, timer, fn) {
    timer.end(function () {
      trigger(target, 'moveend', event);
      return fn && fn();
    });
  } // Set up the DOM


  function movestart(e) {
    if (e.defaultPrevented) {
      return;
    }

    if (!e.moveEnabled) {
      return;
    }

    var event = {
      startX: e.startX,
      startY: e.startY,
      pageX: e.pageX,
      pageY: e.pageY,
      distX: e.distX,
      distY: e.distY,
      deltaX: e.deltaX,
      deltaY: e.deltaY,
      velocityX: e.velocityX,
      velocityY: e.velocityY,
      identifier: e.identifier,
      targetTouches: e.targetTouches,
      finger: e.finger
    };
    var data = {
      target: e.target,
      event: event,
      timer: new Timer(update),
      touch: undefined,
      timeStamp: e.timeStamp
    };

    function update(time) {
      updateEvent(event, data.touch, data.timeStamp);
      trigger(data.target, 'move', event);
    }

    if (e.identifier === undefined) {
      // We're dealing with a mouse event.
      // Stop clicks from propagating during a move
      on(e.target, 'click', preventDefault);
      on(document, mouseevents.move, activeMousemove, data);
      on(document, mouseevents.end, activeMouseend, data);
    } else {
      // In order to unbind correct handlers they have to be unique
      data.activeTouchmove = function (e, data) {
        activeTouchmove(e, data);
      };

      data.activeTouchend = function (e, data) {
        activeTouchend(e, data);
      }; // We're dealing with a touch.


      on(document, touchevents.move, data.activeTouchmove, data);
      on(document, touchevents.end, data.activeTouchend, data);
    }
  }

  on(document, 'mousedown', mousedown);
  on(document, 'touchstart', touchstart);
  on(document, 'movestart', movestart); // jQuery special events
  //
  // jQuery event objects are copies of DOM event objects. They need
  // a little help copying the move properties across.

  if (!window.jQuery) {
    return;
  }

  var properties = "startX startY pageX pageY distX distY deltaX deltaY velocityX velocityY".split(' ');

  function enableMove1(e) {
    e.enableMove();
  }

  function enableMove2(e) {
    e.enableMove();
  }

  function enableMove3(e) {
    e.enableMove();
  }

  function add(handleObj) {
    var handler = handleObj.handler;

    handleObj.handler = function (e) {
      // Copy move properties across from originalEvent
      var i = properties.length;
      var property;

      while (i--) {
        property = properties[i];
        e[property] = e.originalEvent[property];
      }

      handler.apply(this, arguments);
    };
  }

  jQuery.event.special.movestart = {
    setup: function setup() {
      // Movestart must be enabled to allow other move events
      on(this, 'movestart', enableMove1); // Do listen to DOM events

      return false;
    },
    teardown: function teardown() {
      off(this, 'movestart', enableMove1);
      return false;
    },
    add: add
  };
  jQuery.event.special.move = {
    setup: function setup() {
      on(this, 'movestart', enableMove2);
      return false;
    },
    teardown: function teardown() {
      off(this, 'movestart', enableMove2);
      return false;
    },
    add: add
  };
  jQuery.event.special.moveend = {
    setup: function setup() {
      on(this, 'movestart', enableMove3);
      return false;
    },
    teardown: function teardown() {
      off(this, 'movestart', enableMove3);
      return false;
    },
    add: add
  };
});

/***/ }),

/***/ "./src/style/image-comparison.scss":
/*!*****************************************!*\
  !*** ./src/style/image-comparison.scss ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
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
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/*!*********************************!*\
  !*** ./src/image-comparison.js ***!
  \*********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _style_image_comparison_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style/image-comparison.scss */ "./src/style/image-comparison.scss");
/* harmony import */ var _extLib_jquery_event_move__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../extLib/jquery.event.move */ "./extLib/jquery.event.move.js");
/* harmony import */ var _extLib_jquery_event_move__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_extLib_jquery_event_move__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _extLib_imageComarison__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../extLib/imageComarison */ "./extLib/imageComarison.js");
/* harmony import */ var _extLib_imageComarison__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_extLib_imageComarison__WEBPACK_IMPORTED_MODULE_2__);
function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

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





var wadiImageComparison = /*#__PURE__*/function (_elementorModules$fro) {
  _inherits(wadiImageComparison, _elementorModules$fro);

  var _super = _createSuper(wadiImageComparison);

  function wadiImageComparison() {
    _classCallCheck(this, wadiImageComparison);

    return _super.apply(this, arguments);
  }

  _createClass(wadiImageComparison, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          wadiImageComparisonContainer: '.wadi_image_comparison_container_' + this.$element[0].getAttribute("data-id")
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      var elements = {
        $wadiImageComparisonContainer: this.$element.find(selectors.wadiImageComparisonContainer)
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

      var wadiImageComparisonSettings = _objectSpread(_objectSpread({
        wadiImageComparisonContainer: this.elements.$wadiImageComparisonContainer,
        wadiImageBeforeLabelSwitcher: 'yes' === elementsSettings.wadi_image_comparison_before_label_switcher ? true : false,
        wadiImageAfterLabelSwitcher: 'yes' === elementsSettings.wadi_image_comparison_after_label_switcher ? true : false,
        wadiImageBeforeLabel: elementsSettings.wadi_image_comparison_before_label || 'Before',
        wadiImageAfterLabel: elementsSettings.wadi_image_comparison_after_label || 'After',
        wadiImageOrientaiton: elementsSettings.wadi_image_comparison_orientation || 'horizontal',
        wadiOffsetPercentage: elementsSettings.wadi_image_comparison_slider_offset_ratio,
        wadiDragCompassHandle: 'yes' === elementsSettings.wadi_image_comparison_drag_compass_handle_switcher ? true : false
      }, 'yes' === elementsSettings.wadi_image_comparison_drag_compass_handle_switcher && {
        wadiSliderHandle: 'yes' === elementsSettings.wadi_image_comparison_slider_handle_switcher ? true : false,
        wadiCompassHandle: 'yes' === elementsSettings.wadi_image_comparison_compass_handle_switcher ? true : false
      }), {}, {
        wadiImageComparisonOverlayColor: 'yes' === elementsSettings.wadi_image_comparison_overlay_color_switcher ? true : false,
        wadiImageComparisonLabelOnHover: 'yes' === elementsSettings.wadi_image_comparison_labels_show_only_on_hover_switcher ? true : false,
        wadiImageComparisonSliderMove: elementsSettings.wadi_image_comparison_slide_move_on ? elementsSettings.wadi_image_comparison_slide_move_on : 'drag'
      }); // console.log("wadiImageComparisonSettings:",wadiImageComparisonSettings);


      _this.wadiImageComparison(wadiImageComparisonSettings, jQuery);
    }
  }, {
    key: "wadiImageComparison",
    value: function wadiImageComparison(settings, $) {
      console.log("HELLIo Image Comparison");
      var element = settings.wadiImageComparisonContainer;
      console.log("settings", settings);
      element.wa_image_comparison(_objectSpread(_objectSpread(_objectSpread(_objectSpread(_objectSpread(_objectSpread({
        default_offset_pct: settings.wadiOffsetPercentage.size / 100 || 0.5,
        orientation: settings.wadiImageOrientaiton
      }, settings.wadiImageBeforeLabelSwitcher && {
        before_label: settings.wadiImageBeforeLabel
      }), settings.wadiImageAfterLabelSwitcher && {
        after_label: settings.wadiImageAfterLabel
      }), {}, {
        no_overlay: false
      }, 'mousemove' === settings.wadiImageComparisonSliderMove ? {
        move_slider_on_hover: true
      } : {
        move_slider_on_hover: false
      }), 'drag' === settings.wadiImageComparisonSliderMove ? {
        move_with_handle_only: true
      } : {
        move_with_handle_only: false
      }), 'click' === settings.wadiImageComparisonSliderMove ? {
        click_to_move: true,
        move_slider_on_click: true
      } : {
        click_to_move: false,
        move_slider_on_click: false
      }), {}, {
        show_labels_on_hover: settings.wadiImageComparisonLabelOnHover,
        show_drag_compass: settings.wadiDragCompassHandle
      }, settings.wadiDragCompassHandle && {
        show_slider: settings.wadiSliderHandle,
        show_compass: settings.wadiCompassHandle
      }));
    }
  }]);

  return wadiImageComparison;
}(elementorModules.frontend.handlers.Base);

jQuery(window).on("elementor/frontend/init", function () {
  var addHandler = function addHandler($element) {
    elementorFrontend.elementsHandler.addHandler(wadiImageComparison, {
      $element: $element
    });
  };

  elementorFrontend.hooks.addAction("frontend/element_ready/wadi-image-comparison-addon.default", addHandler);
});
console.log("Image Comparison Widget for Elementor");
}();
/******/ })()
;
//# sourceMappingURL=wadi-image-comparison.js.map