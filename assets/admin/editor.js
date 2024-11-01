import './style/wadi-editor-alert-pro.scss';
(function() {

    elementor.hooks.addFilter("panel/elements/regionViews", function (panel) {
    
        // TODO: Remoev the ! from WadiProAlert.wadiPro_installed for it to work only on free Widgets (DONE REMOVED)
        if(WadiProAlert.wadiPro_installed || WadiProAlert.wadiPro_widgets.lenght <= 0) {
            return panel;
        }        

        console.log("WadiProAlert.wadiPro_widgets", WadiProAlert.wadiPro_widgets)
        console.log("Panel", panel)
        var wadiWidgetsPromotionAlert, wadiProCategoryIndex;
        const elementsView = panel.elements.view,
        categoriesView = panel.categories.view,
        widgets = panel.elements.options.collection,
        categories = panel.categories.options.collection,
        WadiCategory = [];


        _.each(WadiProAlert.wadiPro_widgets, function (widget, index) {
            widgets.add({
                name: widget.key,
                title: wp.i18n.__('Wadi ', 'wadi-addons') + widget.title,
                icon: 'wadi-'+widget.widget_icon,
                categories: ["wadi-elements"],
                editable: false
            })
        });

   
        wadiWidgetsPromotionAlert = {
            className: function () {

                var className = 'elementor-element-wrapper';

                if (!this.isEditable()) {
                    className += ' elementor-element--promotion';
                }

                if (this.model.get("name")) {
                    if (0 === this.model.get("name").indexOf("wadi-"))
                        className += ' wadi-promotion-element';
                }

                return className;

            },

            isWadiWidget: function () {
                return 0 === this.model.get("name").indexOf("wadi-");
            },

            getWedgetOption: function (key) {

                var widgetObj = WadiProAlert.wadiPro_widgets.find(function (widget, index) {
                    if (widget.key == key)
                        return true;
                });

                return widgetObj;

            },

            onMouseDown: function () {

                if (!this.isWadiWidget()) {
                    return;
                }
                void this.constructor.__super__.onMouseDown.call(this);

                var widgetObject = this.getWedgetOption(this.model.get("name")),
                    actonURL = widgetObject?.action_url ||  'https://www.wadiweb.com';


                elementor.promotion.dialog.buttons[0].addClass("wadi-promotion-btn").closest('#elementor-element--promotion__dialog').addClass('wadi-promotion-dialog');

                var goProCta = actonURL.substring(actonURL.indexOf('/?utm_source'));
                
                elementor.promotion.showDialog({
                    title: sprintf(wp.i18n.__('%s', 'elementor'), this.model.get("title")),
                    content: sprintf(wp.i18n.__('Use %s widget and dozens more pro features to extend your toolbox and build sites faster and better.', 'elementor'), this.model.get("title")),
                    targetElement: this.el,
                    position: {
                        blockStart: '-7'
                    },
                    actionButton: {
                        url: goProCta,
                        text: 'Go Pro',
                        classes: ['wadi-promotion-pro-btn', 'dialog-button', 'elementor-button',  'elementor-button-success']
                    }
                });
           
            }
        }

        panel.elements.view = elementsView.extend({
            childView: elementsView.prototype.childView.extend(wadiWidgetsPromotionAlert)
        });

        panel.categories.view = categoriesView.extend({
            childView: categoriesView.prototype.childView.extend({
                childView: categoriesView.prototype.childView.prototype.childView.extend(wadiWidgetsPromotionAlert)
            })
        });


        return panel;
    
    
    });
})(jQuery);
