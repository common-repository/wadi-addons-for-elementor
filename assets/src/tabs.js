import "./style/tabs.scss";

class WadiTabs extends elementorModules.frontend.handlers.Base {
  getDefaultSettings() {
    return {
      selectors: {
        wadiTabsElem: ".wadi-tabs-section",
        navList: ".wadi-tabs-nav-list",
        navListItem: ".wadi-tabs-nav-list-item",
        contentWrap: ".wadi-content-wrap",
        currentTab: ".wadi-tabs-nav-list-item.tab-current",
        currentClass: "tab-current",
      },
    };
  }

  getDefaultElements() {
    const selectors = this.getSettings("selectors");
    const elements = {
      $wadiTabsElem: this.findElement(selectors.wadiTabsElem),
      $navList: this.findElement(selectors.navList),
      $contentWrap: this.findElement(selectors.contentWrap),
      $navListItem: this.findElement(selectors.navListItem),
    };

    return elements;
  }

  bindEvents() {
    this.initiate();
  }

  initiate() {
    var _this = this;
    const $wadiTabsElem = this.elements.$wadiTabsElem;
    const elementsSettings = this.getElementSettings();
    const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
    const selectors = this.getSettings('selectors');

    this.settings = {
      id: "#wadi-tabs-" + this.$element[0].getAttribute("data-id"),
      start: elementsSettings.default_tab_index ? parseInt(elementsSettings.default_tab_index) : 0,
    };

    
    if (elementsSettings.tabs.length - 1 < this.settings.start ) {
      this.settings.start = 0
    }
    
    if (-1 !== this.settings.start) {
      $wadiTabsElem.find(".wadi-tabs-nav-list-item:eq(" + this.settings.start + ")").addClass("tab-current");
    }

    _this.WadiTabs($wadiTabsElem, this.settings);
  }

  WadiTabs(tabsElems, settings) {

    var $this = this,
      id = settings.id,
      start = settings.start;

    $this.options = {
      start,
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

      let tabsNav = [];

      // TODO: Replace $this.items jQuery with Vanilla JavaScript

        $this.items = jQuery(id).find(".wadi-content-wrap").first().find("> section");

        const tabsList = document.querySelector(`${id}`).children;
        const tabsArr = Array.from(tabsList);
        const tabsNew = tabsArr.find((elem) => {
          if(elem.classList.contains('wadi-tabs-nav')) {
            const allTabsArray = Array.from(elem.querySelectorAll('li.wadi-tabs-nav-list-item'));
            tabsNav = [...allTabsArray];
          } 
        });

        $this.current = -1;

        //No tabs will be active by default.
        if (-1 !== $this.options.start) {
            $this._show();
        }

        $this._initEvents(tabsNav);
    };

    $this._initEvents = function (tabs) {
      const tabsNav = tabs;
      tabsNav.forEach((tab, index) => {

        const navlistIndex = tab.getAttribute('data-navlist-index');

        tab.addEventListener('click', (e) => {
          e.preventDefault();
          if(tab.classList.contains('tab-current')) {
            return;
          }

          $this._show(navlistIndex,tab, tabsNav)
        })
      })
      
    };


    $this._show = function (tabIndex, tab, tabs) {

        if ($this.current >= 0) {
          tabs.map(tabItem => {
            if(tabItem.classList.contains('tab-current')) {
              tabItem.classList.remove('tab-current');
            }
          });


            $this.items.removeClass("content-current");
        }

        $this.current = tabIndex;

        //Activate the first tab if no tab is clicked yet
        if (void 0 == tabIndex) {
            $this.current = $this.options.start >= 0 && $this.options.start < $this.items.length ? $this.options.start : 0;
        }
        jQuery(tab).addClass("tab-current");


        var $activeContent = $this.items.eq($this.current);

        $activeContent.addClass("content-current");


        //Make sure videos are paused
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
}

jQuery(window).on("elementor/frontend/init", () => {
  const addHandler = ($element) => {
    elementorFrontend.elementsHandler.addHandler(WadiTabs, {
      $element,
    });
  };

  elementorFrontend.hooks.addAction(
    "frontend/element_ready/wadi-tabs-addon.default",
    addHandler
  );
});
