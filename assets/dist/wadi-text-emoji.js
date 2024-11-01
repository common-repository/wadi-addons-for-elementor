/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************!*\
  !*** ./src/emojionearea.js ***!
  \*****************************/
window.addEventListener('elementor/init', function () {
  var emojioneareaItemView = elementor.modules.controls.BaseData.extend({
    onReady: function onReady() {
      var self = this,
          options = _.extend({
        events: {
          change: function change() {
            return self.saveValue();
          },
          emojibtn_click: function emojibtn_click() {
            return self.saveValue();
          },
          keyup: function keyup() {
            return self.saveValue();
          }
        },
        pickerPosition: 'bottom',
        filtersPosition: 'top',
        searchPosition: 'bottom',
        saveEmojisAs: 'unicode',
        inline: false
      }, this.model.get('emojionearea_options'));

      this.ui.textarea.emojioneArea(options);
    },
    saveValue: function saveValue() {
      this.setValue(this.ui.textarea[0].emojioneArea.getText());
    },
    onBeforeDestroy: function onBeforeDestroy() {
      this.saveValue();
      this.ui.textarea[0].emojioneArea.off();
    }
  });
  elementor.addControlView('wadi-emojionearea', emojioneareaItemView);
});
/******/ })()
;
//# sourceMappingURL=wadi-text-emoji.js.map