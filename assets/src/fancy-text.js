import Typed from "typed.js";
import { annotate } from "rough-notation";
import "../extLib/vTicker";
import "./style/fancy-text.scss";

class WadiFancyText extends elementorModules.frontend.handlers.Base {
  getDefaultSettings() {
    return {
      selectors: {
        wadiFancyTextContainer: ".wadi_fancy_text_container",
      },
    };
  }

  getDefaultElements() {
    const selectors = this.getSettings("selectors");
    const elements = {
      $wadiFancyTextContainer: this.$element.find(
        selectors.wadiFancyTextContainer
      ),
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
    const selectors = this.getSettings("selectors");

    const wadiFancyTextSettings = {
      wadiFancyTextElementWrapper: `.wadi_fancy_text_wrapper_${this.$element[0].getAttribute( "data-id" )}`,
      wadiFancyTextElementContainer: `.wadi_fancy_text_container_${this.$element[0].getAttribute( "data-id" )}`,
      wadiFancyTextStyle: elementsSettings.wadi_fancy_text_style ? elementsSettings.wadi_fancy_text_style : "animate_text",
        ... 'animate_text' === elementsSettings.wadi_fancy_text_style && {
          wadiFancyTextAnimateEffect: elementsSettings.wadi_fancy_text_animate_effect ? elementsSettings.wadi_fancy_text_animate_effect  : "typed",
        },
      ...("highlighted" === elementsSettings.wadi_fancy_text_style && {
        wadiFancyTextHighlightShape: elementsSettings.wadi_fancy_text_hightlight_shape ? elementsSettings.wadi_fancy_text_hightlight_shape : "circle",
        wadiFancyTextHighlightContent: elementsSettings.wadi_fancy_text_highlighted_content ? elementsSettings.wadi_fancy_text_highlighted_content : "",
      }),
      ...("typed" === elementsSettings.wadi_fancy_text_animate_effect && {
        wadiFancyTextTypedContent: elementsSettings.wadi_fancy_text_animated_content ? elementsSettings.wadi_fancy_text_animated_content  : "",
        wadiFancyTextTypedSpeedTyping: elementsSettings.wadi_fancy_text_type_speed && elementsSettings.wadi_fancy_text_type_speed,
        wadiFancyTextTypedSpeedBack: elementsSettings.wadi_fancy_text_back_speed && elementsSettings.wadi_fancy_text_back_speed,
        wadiFancyTextTypedLoop: elementsSettings.wadi_fancy_text_loop ? elementsSettings.wadi_fancy_text_loop : true,
        wadiFancyTextTypedLoopCountSwitcher: elementsSettings.wadi_fancy_text_loop_count_switcher ? elementsSettings.wadi_fancy_text_loop_count_switcher : true,
        ...("yes" === elementsSettings.wadi_fancy_text_loop_count_switcher && {
          wadiFancyTextTypedLoopTimes: elementsSettings.wadi_fancy_text_loop_times &&  elementsSettings.wadi_fancy_text_loop_times,
        }),
        wadiFancyTextTypedShowCursor: "yes" === elementsSettings.wadi_fancy_text_show_cursor ? true : false,
        ...("yes" === elementsSettings.wadi_fancy_text_show_cursor && {
          wadiFancyTextTypedCursor: elementsSettings.wadi_fancy_text_cursor ? elementsSettings.wadi_fancy_text_cursor : "|",
        }),
        wadiFancyTextTypedStartDelay: elementsSettings.wadi_fancy_text_start_delay && elementsSettings.wadi_fancy_text_start_delay,
        wadiFancyTextTypedBackDelay: elementsSettings.wadi_fancy_text_back_delay &&  elementsSettings.wadi_fancy_text_back_delay,
        wadiFancyTextTypedFadeout: "yes" === elementsSettings.wadi_fancy_text_fadeout ? true : false,
        wadiFancyTextTypedShuffle: "yes" === elementsSettings.wadi_fancy_text_shuffle ? true : false,
        wadiFancyTextTypedSmartBackspace: "yes" === elementsSettings.wadi_fancy_text_smart_backspace ? true : false,
      }),
      ...("slideup" === elementsSettings.wadi_fancy_text_animate_effect && {
        wadiFancyTextSlideUpSpeed: elementsSettings.wadi_fancy_text_speed_slide_up && elementsSettings.wadi_fancy_text_speed_slide_up,
        wadiFancyTextSlideUpPause: elementsSettings.wadi_fancy_text_pause_animation && elementsSettings.wadi_fancy_text_pause_animation,
        wadiFancyTextSlideUpPauseOnHover:  "yes" === elementsSettings.wadi_fancy_text_pause_on_hover_slideup ? true: false,
      }),
      ... ('highlighted' === elementsSettings.wadi_fancy_text_style && {
        wadiFancyTextHighlightColor: elementsSettings.wadi_fancy_text_highlighted_color ? elementsSettings.wadi_fancy_text_highlighted_color : '#ff0000',
        watiFancyTextHighlightAnimate: 'yes' === elementsSettings.wadi_fancy_text_highlighted_animate ? true : false,
        wadiFancyTextHighlightAnimateDuration: elementsSettings.wadi_fancy_text_highlighted_animate_duration && elementsSettings.wadi_fancy_text_highlighted_animate_duration,
        wadiFancyTextHighlightAnimateDelay: elementsSettings.wadi_fancy_text_highlighted_animate_delay && elementsSettings.wadi_fancy_text_highlighted_animate_delay,
        wadiFancyTextHighlightStrokeWidth: elementsSettings.wadi_fancy_text_highlighted_stroke_width && elementsSettings.wadi_fancy_text_highlighted_stroke_width,
        ...('bracket' === elementsSettings.wadi_fancy_text_hightlight_shape && {
          wadiFancyTextHighlightBracketsPosition: 'left_right' === elementsSettings.wadi_fancy_text_highlighted_brackets_positions ? ["right", "left"] : ["top", "bottom"] ,
        }),
        wadiFancyTextHighlightMultiline: 'yes' === elementsSettings.wadi_fancy_text_highlighted_multiline ? true : false,
        wadiFancyTextHighlightRTL: 'yes' === elementsSettings.wadi_fancy_text_highlighted_rtl ? true : false,
        wadiFancyTextHighlightAnimationRepeat: 'yes' === elementsSettings.wadi_fancy_text_highlighted_animate_repeat ? true : false,
      }),
      wadiFancyTextAnimationSpeed: elementsSettings.wadi_fancy_text_speed_animation && elementsSettings.wadi_fancy_text_speed_animation,
      ... 'clip' === elementsSettings.wadi_fancy_text_animate_effect && {
        wadiFancyTextClipPause: elementsSettings.wadi_fancy_text_pause_animation && elementsSettings.wadi_fancy_text_pause_animation,  
      }
    };

    _this.wadiFancyTextInit(wadiFancyTextSettings, jQuery);
  }

  wadiFancyTextInit(settings, $) {
    
    const _this = this;
    if ( this.isEdit ) {
      _this.wadiFancyText(settings, $);

    } else {
      const wadiFanyTextWrapper = document.querySelector(settings.wadiFancyTextElementWrapper);
      // const thresholdStart = -50/ 100;
      // const thresholdEnd = 100/ 100;

      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
          if(entry.isIntersecting) {
              _this.wadiFancyText(settings, $);

            observer.unobserve(wadiFanyTextWrapper.parentElement);
          }
      });
    },
    {
      root: null,
      rootMargin: "200px",
      // threshold: [thresholdStart, thresholdEnd],
    } );
      observer.observe(wadiFanyTextWrapper.parentElement);
  }
  }

  wadiFancyText(settings, $) {
    const _this = this;
    if ("animate_text" === settings.wadiFancyTextStyle) {
      if ("typed" === settings.wadiFancyTextAnimateEffect) {
        const lines = settings.wadiFancyTextTypedContent.replace(/\r\n/g, "\n").split("\n");

        var typed = new Typed(
          `${settings.wadiFancyTextElementContainer} .wadi_type_text_element`,
          {
            strings: lines,
            typeSpeed: settings.wadiFancyTextTypedSpeedTyping.size || 50,
            backSpeed: settings.wadiFancyTextTypedSpeedBack.size || 100,
            loop: settings.wadiFancyTextTypedLoop,
            ...(settings.wadiFancyTextTypedLoop &&
              settings.wadiFancyTextTypedLoopCountSwitcher &&
              settings.wadiFancyTextTypedLoopTimes && {
                loopCount: settings.wadiFancyTextTypedLoopTimes.size || 5,
              }),
            showCursor: settings.wadiFancyTextTypedShowCursor,
            ...(settings.wadiFancyTextTypedShowCursor && {
              cursorChar: settings.wadiFancyTextTypedCursor,
            }),
            startDelay: settings.wadiFancyTextTypedStartDelay.size || 50,
            backDelay: settings.wadiFancyTextTypedBackDelay.size || 50,
            fadeOut: settings.wadiFancyTextTypedFadeout,
            shuffle: settings.wadiFancyTextTypedShuffle,
            smartBackspace: settings.wadiFancyTextTypedSmartBackspace,
          }
        );
      } else if ("slideup" === settings.wadiFancyTextAnimateEffect) {
        $(`${settings.wadiFancyTextElementWrapper}.wadi_fancy_text_wrapper_slideup`).css('opacity', '1');
        $(`${settings.wadiFancyTextElementWrapper} ${settings.wadiFancyTextElementContainer}`).wadiTicker("init", {
          speed: settings.wadiFancyTextSlideUpSpeed.size || 400,
          pause: settings.wadiFancyTextSlideUpPause.size || 500,
          mousePause: settings.wadiFancyTextSlideUpPauseOnHover,
          showItems: 1,
        });
      } else {
        _this._wadiAnimationFancyText(settings, $);
      }
    } else if ("highlighted" === settings.wadiFancyTextStyle) {

      const textNotation = document.querySelector(`${settings.wadiFancyTextElementContainer} .text_notation`);
      if ("" !== settings.wadiFancyTextHighlightContent) {
        const annotation = annotate(textNotation, {
          type: settings.wadiFancyTextHighlightShape,
          color: settings.wadiFancyTextHighlightColor,
          animate: settings.watiFancyTextHighlightAnimate,
          ... (settings.watiFancyTextHighlightAnimate && {
            animationDuration: settings.wadiFancyTextHighlightAnimateDuration.size || 1500,
          }),
          padding: 5,
          iterations: 5,
          strokeWidth: settings.wadiFancyTextHighlightStrokeWidth.size || 3,
          ... 'bracket' === settings.wadiFancyTextHighlightShape && {
            brackets: settings.wadiFancyTextHighlightBracketsPosition,
          },
          multiline: settings.wadiFancyTextHighlightMultiline,
          rtl: settings.wadiFancyTextHighlightRTL,
        });

        annotation.show();

        if(settings.wadiFancyTextHighlightAnimationRepeat) {
          _this._animation_repeat(annotation, $);
        }

      }
    }
  }

  _animation_repeat(object, $) {
    setInterval(function () {
      
      // Hide SetTimeout
      $(".rough-annotation").toggleClass("wadi_hide_rough_notation");
      setTimeout(() => {
        object.hide();
      }, 2000);


      // Show SetTimeout
      setTimeout(function () {
        $(".rough-annotation").toggleClass("wadi_hide_rough_notation");
        if($(".rought-annotation").hasClass('wadi_hide_rough_notation') === false) {
          object.show();
        }
      }, 4000);

    }, 10000);
  }



  _wadiAnimationFancyText(settings, $) {
    const _this = this;
    const animationSpeed = settings?.wadiFancyTextAnimationSpeed?.size || 1000;

    const $wadiFancyTextWrapper = $(settings.wadiFancyTextElementWrapper);
    const $wadiFancyTextHeadlines = $wadiFancyTextWrapper.find('.wadi_ticker_wrapper');

    $wadiFancyTextHeadlines.each(function() {
      const $headline = $(this);

      setTimeout(function() {
        _this._wadiHideWord($headline.find('.wadi_slide_active'), settings);
      }, animationSpeed);
    })

  }


  _wadiHideWord($word, settings) {
    const _this = this;
    const animationSpeed = settings?.wadiFancyTextAnimationSpeed?.size || 1000;

    let $nextWord = _this._wadiNext($word);

      if('clip' == settings.wadiFancyTextAnimateEffect) {
        const animationSpeed = settings.wadiFancyTextAnimationSpeed.size || 1000;
        const clipPauseAnimation = settings.wadiFancyTextClipPause.size || 1000;
        $word.parents( '.wadi_ticker_wrapper' ).animate(
          { width : '0px' },
          animationSpeed, function(){
            setTimeout( function(){

              _this._wadiSwitchWord( $word, $nextWord );
              _this._wadiShowWord( $nextWord, settings );

            }, clipPauseAnimation);
          }
        );

      } else {

        _this._wadiSwitchWord($word, $nextWord);
        
        setTimeout(function () {
          _this._wadiHideWord($nextWord, settings);
        }, animationSpeed);

      }

  }

  _wadiShowWord($word, settings) {
    const _this = this;

    const animationSpeed = settings.wadiFancyTextAnimationSpeed.size || 1000;
    const clipPauseAnimation = settings.wadiFancyTextClipPause.size || 1000;
    if('clip' == settings.wadiFancyTextAnimateEffect) {
    
      $word.parents( '.wadi_ticker_wrapper' ).animate(
        { 'width' : $word.width() + 3 },
        animationSpeed,
        function(){
          setTimeout( function()
            {
              _this._wadiHideWord( $word, settings )
            },
            clipPauseAnimation);
        }
      );
    }
  }

  _wadiNext($word) {
    return ( !$word.is( ':last-child' ) ) ? $word.next() : $word.parent().children().eq( 0 );
  }

  _wadiSwitchWord($oldWord, $newWord) {
    $oldWord.removeClass( 'wadi_slide_active' ).addClass( 'wadi_slide_inacitve' );
		$newWord.removeClass( 'wadi_slide_inacitve' ).addClass( 'wadi_slide_active' );
  }

}

jQuery(window).on("elementor/frontend/init", () => {
  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(WadiFancyText, {
      $element,
    });
  };

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/wadi-fancy-text-addon.default",
    addHandler
  );
});
