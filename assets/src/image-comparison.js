import './style/image-comparison.scss';
import '../extLib/jquery.event.move';
import '../extLib/imageComarison';


class wadiImageComparison extends elementorModules.frontend.handlers.Base {

    getDefaultSettings() {
      return {
        selectors: {
          wadiImageComparisonContainer: '.wadi_image_comparison_container_' +  this.$element[0].getAttribute("data-id"),
        },
      }
    }
  
  
    getDefaultElements() {
        const selectors = this.getSettings('selectors');
      const elements = {
        $wadiImageComparisonContainer:  this.$element.find(selectors.wadiImageComparisonContainer),

      };

      return elements;
    }

    bindEvents() {
        this.initiate();
    }

    initiate() {
        const _this = this;
  
        const elementsSettings = this.getElementSettings();
        const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
        const selectors = this.getSettings('selectors');
        const wadiImageComparisonSettings = {
          wadiImageComparisonContainer: this.elements.$wadiImageComparisonContainer,
          wadiImageBeforeLabelSwitcher: 'yes' === elementsSettings.wadi_image_comparison_before_label_switcher ? true : false,
          wadiImageAfterLabelSwitcher: 'yes' === elementsSettings.wadi_image_comparison_after_label_switcher ? true : false,
          wadiImageBeforeLabel: elementsSettings.wadi_image_comparison_before_label || 'Before',
          wadiImageAfterLabel: elementsSettings.wadi_image_comparison_after_label || 'After',
          wadiImageOrientaiton: elementsSettings.wadi_image_comparison_orientation || 'horizontal',
          wadiOffsetPercentage: elementsSettings.wadi_image_comparison_slider_offset_ratio,
          wadiDragCompassHandle: 'yes' === elementsSettings.wadi_image_comparison_drag_compass_handle_switcher ? true : false,
          ... 'yes' === elementsSettings.wadi_image_comparison_drag_compass_handle_switcher && {
            wadiSliderHandle: 'yes' === elementsSettings.wadi_image_comparison_slider_handle_switcher ? true : false,
            wadiCompassHandle: 'yes' === elementsSettings.wadi_image_comparison_compass_handle_switcher ? true : false,
          },
          wadiImageComparisonOverlayColor: 'yes' === elementsSettings.wadi_image_comparison_overlay_color_switcher ? true : false,
          wadiImageComparisonLabelOnHover: 'yes' === elementsSettings.wadi_image_comparison_labels_show_only_on_hover_switcher ? true : false,
          wadiImageComparisonSliderMove: elementsSettings.wadi_image_comparison_slide_move_on ? elementsSettings.wadi_image_comparison_slide_move_on : 'drag',
        }

        // console.log("wadiImageComparisonSettings:",wadiImageComparisonSettings);

      _this.wadiImageComparison(wadiImageComparisonSettings, jQuery);

    }

    wadiImageComparison(settings, $) {
        console.log("HELLIo Image Comparison");

        const element = settings.wadiImageComparisonContainer;

      console.log("settings", settings);
        element.wa_image_comparison({
          default_offset_pct: settings.wadiOffsetPercentage.size / 100 || 0.5,
          orientation: settings.wadiImageOrientaiton,
          ... settings.wadiImageBeforeLabelSwitcher && {
            before_label: settings.wadiImageBeforeLabel,
          },
          ... settings.wadiImageAfterLabelSwitcher && {
            after_label: settings.wadiImageAfterLabel,
          },
          no_overlay: false,
          ... 'mousemove' === settings.wadiImageComparisonSliderMove ? {
            move_slider_on_hover: true,
          } : {
            move_slider_on_hover: false,
          },
          ... 'drag' === settings.wadiImageComparisonSliderMove ? {
            move_with_handle_only: true,
          } : {
            move_with_handle_only: false,
          },
          ... 'click' === settings.wadiImageComparisonSliderMove ? {
            click_to_move: true,
            move_slider_on_click: true,
          } : {
            click_to_move: false,
            move_slider_on_click: false,
          },
          show_labels_on_hover: settings.wadiImageComparisonLabelOnHover,

          show_drag_compass: settings.wadiDragCompassHandle,
          ... settings.wadiDragCompassHandle && {
            show_slider: settings.wadiSliderHandle,
            show_compass: settings.wadiCompassHandle,
          }
        });
    }
}

jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(wadiImageComparison, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-image-comparison-addon.default",
      addHandler
    );
  });
  
  

console.log("Image Comparison Widget for Elementor");