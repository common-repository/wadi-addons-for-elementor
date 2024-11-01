//https://www.chartjs.org/docs/latest/getting-started/integration.html
import Chart from 'chart.js/auto';

import './style/charts.scss';

class WadiCharts extends elementorModules.frontend.handlers.Base {

    getDefaultSettings() {
        return {
          selectors: {
            wadiChartCanvas: '.wadi_chart_canvas',
          },
        }
      }
    
    
      getDefaultElements() {
        const selectors = this.getSettings('selectors');
        const elements = {
          $wadiChart: this.findElement(selectors.wadiChartCanvas),
        };
    
        return elements;
      }
    
    
  bindEvents() {
    this.initiate();
  }

  initiate() {
    const _this = this;
    const $wadiChart = this.elements.wadiChart;
    const elementsSettings = this.getElementSettings();
    const currentDeviceMode = elementorFrontend.getCurrentDeviceMode();
    const selectors = this.getSettings('selectors');

    const chartsSettings = {
      wadiChartContainer: document.querySelector('.wadi_chart_container_'+ this.$element[0].getAttribute("data-id")),
      wadiChartCanvasElement: document.querySelector('.wadi_chart_canvas_'+ this.$element[0].getAttribute("data-id")),
      wadiChartContentType: !!elementsSettings.wadi_chart_content_type ? elementsSettings.wadi_chart_content_type : 'custom',
      wadiChartDisplayEvent: elementsSettings.wadi_chart_display_event === 'yes' ? true : false,
      wadiChartType: elementsSettings.wadi_chart_type ? elementsSettings.wadi_chart_type : 'bar',
      ... 'line' === elementsSettings.wadi_chart_type && {
        wadiChartFillLine: 'yes' === elementsSettings.wadi_chart_line_fill_switcher ? true: false,
      },
      ... ('line' === elementsSettings.wadi_chart_type || 'radar' ===  elementsSettings.wadi_chart_type) && {
        wadiChartDashedBorder: 'yes' === elementsSettings.wadi_chart_border_dashed_switcher ? true: false,
      },

      wadiChartXLabels: !!elementsSettings.wadi_chart_x_labels ? elementsSettings.wadi_chart_x_labels : 'Januray,February,March,April,May,June',
      // Chart X Axis
      wadiChartXDisplay: elementsSettings.wadi_show_x_switch === 'yes' ? true : false,
      ... elementsSettings.wadi_show_x_switch === 'yes' && {
        wadiShowXTitleSwitch: elementsSettings.wadi_show_x_title_switch === 'yes' ? true : false,
        ... elementsSettings.wadi_show_x_title_switch === 'yes' && {
          wadiChartXDisplayTitleText: elementsSettings.wadi_chart_x_label_scale_title_text ? elementsSettings.wadi_chart_x_label_scale_title_text : 'X-Axis',
        },
        wadiChartXTicksSwitch: elementsSettings.wadi_chart_x_ticks_switch === 'yes' ? true : false,
        wadiChartXTicksBeginAtZero: elementsSettings.wadi_chart_x_ticks_begin_zero === 'yes' ? true : false,
        wadiChartXGridLines: elementsSettings.wadi_chart_x_grid_lines === 'yes' ? true : false,
      },

      // Chart Y Axis
      wadiChartYDisplay: elementsSettings.wadi_show_y_switch === 'yes' ? true : false,
      ... elementsSettings.wadi_show_y_switch === 'yes' && {
        wadiShowYTitleSwitch: elementsSettings.wadi_show_y_title_switch === 'yes' ? true : false,
        ... elementsSettings.wadi_show_x_title_switch === 'yes' && {
          wadiChartYDisplayTitleText: elementsSettings.wadi_chart_y_label_scale_title_text ? elementsSettings.wadi_chart_y_label_scale_title_text : 'Y-Axis',
        },
        wadiChartYTicksSwitch: elementsSettings.wadi_chart_y_ticks_switch === 'yes' ? true : false,
        wadiChartYTicksBeginAtZero: elementsSettings.wadi_chart_y_ticks_begin_zero === 'yes' ? true : false,
        wadiChartYGridLines: elementsSettings.wadi_chart_y_grid_lines === 'yes' ? true : false,
      },

      wadiYDatasetsRepeater: !!elementsSettings.wadi_chart_y_axis_data_repeater ? elementsSettings.wadi_chart_y_axis_data_repeater : [],
      wadiYAxisHoverColor: elementsSettings.wadi_chart_hover_background_color ? elementsSettings.wadi_chart_hover_background_color : '#00d1b2',
      wadiYAxisHoverBorderColor: elementsSettings.wadi_chart_hover_border_color ? elementsSettings.wadi_chart_hover_border_color : '#00d1b233',

      // Chart Bar Advanced Settings
      wadiBarHorizontalSwitcher: elementsSettings.wadi_chart_bar_horizontal_switch === 'yes' ? true : false,
      wadiAdvancedBarSettings: elementsSettings.wadi_chart_bar_advanced_settings_switch === 'yes' ? true : false,
      ...elementsSettings.wadi_chart_bar_advanced_settings_switch === 'yes' && { 
        wadiBarBorderRadius: elementsSettings.wadi_chart_y_axis_bar_border_radius ? elementsSettings.wadi_chart_y_axis_bar_border_radius.size : 0,
        wadiHoverBarBorderRadius: elementsSettings.wadi_chart_y_axis_hover_bar_border_radius ? elementsSettings.wadi_chart_y_axis_hover_bar_border_radius.size : 0,
        wadiBarThickness: elementsSettings.wadi_chart_y_axis_bar_thickness ? elementsSettings.wadi_chart_y_axis_bar_thickness.size : 100,
      },
      wadiAdvancedPieDoughnutSettings: elementsSettings.wadi_chart_pie_doughnut_advanced_settings_switch === 'yes' ? true : false,
      ...elementsSettings.wadi_chart_pie_doughnut_advanced_settings_switch === 'yes' && {
        wadiPieDoughnutSpacing: elementsSettings.wadi_chart_pie_doughnut_spacing ? elementsSettings.wadi_chart_pie_doughnut_spacing : 0, 
        wadiPieDoughnutCircumference: elementsSettings.wadi_chart_pie_doughnut_circumference && elementsSettings.wadi_chart_pie_doughnut_circumference, 
        wadiPieDoughnutRotation: elementsSettings.wadi_chart_pie_doughnut_rotation && elementsSettings.wadi_chart_pie_doughnut_rotation, 
        wadiPieDoughnutHoverOffset: elementsSettings.wadi_chart_pie_doughnut_hover_offset && elementsSettings.wadi_chart_pie_doughnut_hover_offset, 
        wadiPieDoughnutOffset: elementsSettings.wadi_chart_pie_doughnut_offset && elementsSettings.wadi_chart_pie_doughnut_offset, 
      },
      wadiAdvancedLineSettings: elementsSettings.wadi_chart_line_advanced_settings_switch === 'yes' ? true : false,
      ...elementsSettings.wadi_chart_line_advanced_settings_switch === 'yes' && {
        wadiLineTension: elementsSettings.wadi_chart_line_tension && elementsSettings.wadi_chart_line_tension,
        wadiLineStepped: elementsSettings.wadi_chart_line_stepped === 'yes' ? true : false,
        // wadiLinePointStyle: elementsSettings.wadi_chart_line_pointStyle ? elementsSettings.wadi_chart_line_pointStyle : 'circle',
        wadiLineHorizontal: elementsSettings.wadi_chart_line_horizontal === 'yes' && 'y', 
      },
      // Chart Animation Settings
      wadiAnimationType: elementsSettings.wadi_chart_advanced_animation_type ? elementsSettings.wadi_chart_advanced_animation_type : 'easeInOutCubic',
      wadiAnimationDuration: elementsSettings.wadi_chart_animation_duration ? elementsSettings.wadi_chart_animation_duration : 600,
      wadiAnimationDelay: elementsSettings.wadi_chart_animation_delay ? elementsSettings.wadi_chart_animation_delay : 0,
      // Chart Point Configurations
      ...elementsSettings.wadi_chart_advanced_point_configuration_switcher === 'yes' && {
        wadiPointType: elementsSettings.wadi_chart_advanced_point_style ? elementsSettings.wadi_chart_advanced_point_style : 'circle',
        wadiPointRadius: elementsSettings.wadi_chart_advanced_point_radius ? elementsSettings.wadi_chart_advanced_point_radius : 3,
        wadiPointHoverRadius: elementsSettings.wadi_chart_advanced_point_hover_radius ? elementsSettings.wadi_chart_advanced_point_hover_radius : 4,
        wadiPointBorderWidth : elementsSettings.wadi_chart_advanced_point_border_width ? elementsSettings.wadi_chart_advanced_point_border_width : 1,
        wadiPointHoverBorderWidth : elementsSettings.wadi_chart_advanced_point_hover_border_width ? elementsSettings.wadi_chart_advanced_point_hover_border_width : 1,
        wadiPointHitRadius : elementsSettings.wadi_chart_advanced_point_hit_radius ? elementsSettings.wadi_chart_advanced_point_hit_radius : 1,
      },

      // Chart Legend Settings
      wadiChartLegendDisplay: elementsSettings.wadi_chart_advanced_legend_display === 'yes' ? true : false,
      ...elementsSettings.wadi_chart_advanced_legend_display === 'yes' && {
        wadiChartLegendPosition: elementsSettings.wadi_chart_legend_position ? elementsSettings.wadi_chart_legend_position : 'top',
        wadiChartLegendAlignment: elementsSettings.wadi_chart_legend_alignment ? elementsSettings.wadi_chart_legend_alignment : 'center',
        wadiChartLegendRTL: elementsSettings.wadi_chart_legend_rtl === 'yes' ? true : false,
        ...elementsSettings.wadi_chart_legend_rtl === 'yes' && {
          wadiChartLegendRTLTextDirection: elementsSettings.wadi_chart_legend_rtl_textDirection === 'yes' ? 'rtl' : 'ltr',
        },
        wadiChartLengendTitle: elementsSettings.wadi_chart_legend_title === 'yes' ? true : false,
        ... elementsSettings.wadi_chart_legend_title === 'yes' && {
          wadiChartLegenedTitleText: elementsSettings.wadi_chart_legend_title_text ? elementsSettings.wadi_chart_legend_title_text : '',
        },
        wadiChartAspectRatioSwitch: elementsSettings.wadi_chart_aspect_ratio_switch === 'yes' ? true : false,
        ... elementsSettings.wadi_chart_aspect_ratio_switch === 'yes' && {
          wadiChartApectRatio: elementsSettings.wadi_chart_aspect_ratio ? elementsSettings.wadi_chart_aspect_ratio : 2,
        },
        
        // Chart Legend Styling
          // Title Styling
        wadiChartLegendTitleColor: elementsSettings.wadi_chart_legend_title_color ? elementsSettings.wadi_chart_legend_title_color : '#000000',
        wadiChartLegendTitlePadding: elementsSettings.wadi_chart_legend_title_padding && elementsSettings.wadi_chart_legend_title_padding,
        wadiChartLegendTitleFontSize: elementsSettings.wadi_chart_legend_title_font_size ? elementsSettings.wadi_chart_legend_title_font_size : 16,
        wadiChartLegendTitleFontWeight: elementsSettings.wadi_chart_legend_title_font_weight ? elementsSettings.wadi_chart_legend_title_font_weight : 400,
          // Labels Styling
        wadiChartLegendLabelsColor: elementsSettings.wadi_chart_legend_labels_color ? elementsSettings.wadi_chart_legend_labels_color : '#000000',
        wadiChartLegendLabelsPadding: elementsSettings.wadi_chart_legend_labels_padding ? elementsSettings.wadi_chart_legend_labels_padding: 10,
        wadiChartLegendLabelsFontSize: elementsSettings.wadi_chart_legend_labels_font_size ? elementsSettings.wadi_chart_legend_labels_font_size : 16,
        wadiChartLegendLabelsFontWeight: elementsSettings.wadi_chart_legend_labels_font_weight ? elementsSettings.wadi_chart_legend_labels_font_weight : 400,

      },
      
      // Title
      wadiChartTitleSwitcher: 'yes' === elementsSettings.wadi_chart_title_switcher ? true : false,
      ... elementsSettings.wadi_chart_title_switcher === 'yes' && {
        wadiChartTitleText: elementsSettings.wadi_chart_title_text ? elementsSettings.wadi_chart_title_text : '',
        wadiChartTitleAlignment: elementsSettings.wadi_chart_title_alignment ? elementsSettings.wadi_chart_title_alignment : 'center',
        wadiChartTitlePosition: elementsSettings.wadi_chart_title_position ? elementsSettings.wadi_chart_title_position : 'top',

        // Title Styling
          wadiChartTitleColor: elementsSettings.wadi_chart_title_color ? elementsSettings.wadi_chart_title_color : '#000000',
          wadiChartTitlePadding: elementsSettings.wadi_chart_title_padding ? elementsSettings.wadi_chart_title_padding: 10,
          wadiChartTitleFontSize: elementsSettings.wadi_chart_title_font_size ? elementsSettings.wadi_chart_title_font_size : 30,
          wadiChartTitleFontWeight: elementsSettings.wadi_chart_title_font_weight ? elementsSettings.wadi_chart_title_font_weight : 'bold',
          
      },

      // Tooltips
      wadiChartTooltipSwitcher: 'yes' === elementsSettings.wadi_chart_tooltips_switcher ? true : false,
      ... elementsSettings.wadi_chart_tooltips_switcher === 'yes' && {
        wadiChartTooltipMode: elementsSettings.wadi_chart_tooltips_mode ? elementsSettings.wadi_chart_tooltips_mode : 'nearest', 
        wadiChartTooltipRTL: elementsSettings.wadi_chart_tooltips_rtl === 'yes' ? true : false,

        // Tooltips Styling options

        wadiChartTooltipBackgroundColor: elementsSettings.wadi_chart_tooltip_background_color ? elementsSettings.wadi_chart_tooltip_background_color : '#000000',
        wadiChartTooltipCornerRadius: elementsSettings.wadi_chart_tooltip_corner_radius ? elementsSettings.wadi_chart_tooltip_corner_radius : 6,
        wadiChartTooltipPadding: elementsSettings.wadi_chart_tooltip_padding ? elementsSettings.wadi_chart_tooltip_padding : 5,
          // Tooltip Title Styling
        wadiChartTooltipTitleColor: elementsSettings.wadi_chart_tooltip_title_color ? elementsSettings.wadi_chart_tooltip_title_color : '#FFFFFF',
        wadiChartTooltipTitleFontSize: elementsSettings.wadi_chart_tooltip_title_font_size ? elementsSettings.wadi_chart_tooltip_title_font_size : 12,
        wadiChartTooltipTitleFontWeight: elementsSettings.wadi_chart_tooltip_title_font_weight ? elementsSettings.wadi_chart_tooltip_title_font_weight : '',
        wadiChartTooltipTitleAlignment: elementsSettings.wadi_chart_tooltip_title_alignments ? elementsSettings.wadi_chart_tooltip_title_alignments : 'left',
        wadiChartTooltipTitleSpacing: elementsSettings.wadi_chart_tooltip_title_spacing ? elementsSettings.wadi_chart_tooltip_title_spacing : 2,
        wadiChartTooltipTitleMarginBottom: elementsSettings.wadi_chart_tooltip_title_margin_bottom ? elementsSettings.wadi_chart_tooltip_title_margin_bottom : 6,

        // Tooltip Body Styling
         wadiChartTooltipBodyColor: elementsSettings.wadi_chart_tooltip_body_color ? elementsSettings.wadi_chart_tooltip_body_color : '#FFFFFF',
         wadiChartTooltipBodyFontSize: elementsSettings.wadi_chart_tooltip_body_font_size ? elementsSettings.wadi_chart_tooltip_body_font_size : 12,
         wadiChartTooltipBodyFontWeight: elementsSettings.wadi_chart_tooltip_body_font_weight ? elementsSettings.wadi_chart_tooltip_body_font_weight : '',
         wadiChartTooltipBodyAlignment: elementsSettings.wadi_chart_tooltip_body_alignments ? elementsSettings.wadi_chart_tooltip_body_alignments : 'left',
         wadiChartTooltipBodySpacing: elementsSettings.wadi_chart_tooltip_body_spacing ? elementsSettings.wadi_chart_tooltip_body_spacing : 2,

         // Tooltip Caret Styling
         wadiChartTooltipCaretPadding: elementsSettings.wadi_chart_tooltip_caret_padding ? elementsSettings.wadi_chart_tooltip_caret_padding : 2,
         wadiChartTooltipCaretSize: elementsSettings.wadi_chart_tooltip_caret_size ? elementsSettings.wadi_chart_tooltip_caret_size : 5,
      },

      // CSV Logic
      ...elementsSettings.wadi_chart_content_type === 'csv' && {
        wadiChartUrlType: elementsSettings.wadi_chart_url_type ? elementsSettings.wadi_chart_url_type : 'local',
        ...elementsSettings.wadi_chart_url_type === 'local' && {
        wadiChartCSVFile: elementsSettings.wadi_chart_csv_file && elementsSettings.wadi_chart_csv_file,
        },
        ...elementsSettings.wadi_chart_url_type === 'remote' && {
          wadiChartRemoteLink: elementsSettings.wadi_chart_csv_remote_link && elementsSettings.wadi_chart_csv_remote_link,
        },
        wadiChartCSVYRepeater: elementsSettings.wadi_chart_csv_y_axis_data_repeater ? elementsSettings.wadi_chart_csv_y_axis_data_repeater : [],
        wadiChartCSVSeparator: elementsSettings.wadi_chart_csv_separator ? elementsSettings.wadi_chart_csv_separator : ',',
      },

      // CHART STYLING

      // Chart Padding
      wadiChartLayoutPadding: elementsSettings.wadi_chart_layout_padding ? elementsSettings.wadi_chart_layout_padding : 0,

     
    }

    // console.log('chartsSettings', chartsSettings );
    _this.wadiCharts(chartsSettings);
  }

    wadiCharts(settings) {
    const selectors = this.getSettings('selectors');
    const chartSettings = this.getElementSettings();

      // console.log("wadiCharts Settings:",settings);

      const chartXLabels = settings.wadiChartXLabels;

      const YDatasetsRepeater = settings.wadiYDatasetsRepeater;

      const YDatasets = [];

      // Data Object will contain Datasets and Label to be added to Chart Config
      let data = {};

      if('custom' === settings.wadiChartContentType) {

      YDatasetsRepeater.map((item, index) => {
        const theLabel = item.wadi_chart_y_axis_label;

        const YAxisBGColors = []
        if( '' !== item.wadi_chart_y_axis_background_colors) {
          item.wadi_chart_y_axis_background_colors.split(',').map((color, index) => {
            YAxisBGColors.push(color.trim());
          });
        } else {
          YAxisBGColors.push(item.wadi_chart_y_axis_background_color);
        }

        const YAxisBorderColors = []
        if( '' !== item.wadi_chart_y_axis_border_colors) {
          item.wadi_chart_y_axis_border_colors.split(',').map((color, index) => {
            YAxisBorderColors.push(color.trim());
          });
        } else {
          YAxisBorderColors.push(item.wadi_chart_y_axis_border_color);
        }

        const YAxisHoverBGColors = [];
        if('' !== item.wadi_chart_y_axis_hover_background_colors) {
          item.wadi_chart_y_axis_hover_background_colors.split(',').map((color, index)=> {
            YAxisHoverBGColors.push(color.trim());
          })
        } else if('' === item.wadi_chart_y_axis_hover_background_colors && item.wadi_chart_y_axis_hover_background_color) {
          YAxisHoverBGColors.push(item.wadi_chart_y_axis_hover_background_color);
        } else {
          YAxisHoverBGColors.push(settings.wadiYAxisHoverColor)
        }


        let YAxisBorderWidth;
        if(item.wadi_chart_y_axis_border_width.size) {
          YAxisBorderWidth = item.wadi_chart_y_axis_border_width.size && item.wadi_chart_y_axis_border_width.size;
        }

        const YData = [];

        if( '' !== item.wadi_chart_y_axis_data) {
          item.wadi_chart_y_axis_data.split(',').map((dataItem, index) => {
            // console.log("dataItemaaa::",dataItem, typeof(dataItem))
            YData.push(dataItem.trim());
          });
        }
        this.wadiDatasetURLs = [];
        if( '' !== item.wadi_chart_y_axis_urls) {
          item.wadi_chart_y_axis_urls.split(',').map((datasetUrl, index) => {
            this.wadiDatasetURLs.push(datasetUrl.trim());
          });
        }

        YDatasets.push({
          label: theLabel,
          backgroundColor: YAxisBGColors ? YAxisBGColors: '#6ec1e4',
          borderColor: YAxisBorderColors ? YAxisBorderColors : '#000000',
          borderWidth: YAxisBorderWidth,
          hoverBackgroundColor: YAxisHoverBGColors ? YAxisHoverBGColors : '#ccc',
          hoverBorderColor: settings.wadiYAxisHoverBorderColor,
          links: this.wadiDatasetURLs,
          //Advanced Bar Settings
          ...settings.wadiAdvancedBarSettings && {
            borderRadius: settings.wadiBarBorderRadius,
            hoverBorderRadius: settings.wadiHoverBarBorderRadius,
            barThickness: settings.wadiBarThickness,
            barPercentage: 0.5,
          },
          ...settings.wadiAdvancedPieDoughnutSettings && {
            ... !!settings.wadiPieDoughnutHoverOffset?.size && {
              hoverOffset: settings.wadiPieDoughnutHoverOffset?.size
            },
            ... !!settings.wadiPieDoughnutOffset?.size && {
              offset: settings.wadiPieDoughnutOffset?.size
            },
            ... !!settings.wadiPieDoughnutSpacing?.size && {
              spacing: settings.wadiPieDoughnutSpacing?.size,
            },
            ... !!settings.wadiPieDoughnutCircumference?.size && {
            circumference: settings.wadiPieDoughnutCircumference?.size
            },
            ... !!settings.wadiPieDoughnutRotation?.size && {
              rotation: settings.wadiPieDoughnutRotation?.size
            },
          },
          ...settings.wadiAdvancedLineSettings && {
            ... !!settings.wadiLineTension?.size && {
              tension: settings.wadiLineTension?.size,
            },
            ... !!settings.wadiLineStepped && {
              stepped: settings.wadiLineStepped,
            },
            // ... !!settings.wadiLinePointStyle && {
            //   pointStyle: settings.wadiLinePointStyle,
            // },
            ... !!settings.wadiLineHorizontal && { // Horizontal Chart Line
              indexAxis: settings.wadiLineHorizontal,
            }
          },
          // fill: true,
          // indexAxis: 'y',
          // Line Chart Settings
          // stepped: true,
          // pointStyle: 'line',
          // pointBackgroundColor: ['#000', '#fff', '#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#00FFFF'],
          // pointBorderColor: '#fff',
          // pointBorderWidth: 15,
          data: YData,
        })
        // console.log("itemRepeater",item);
      })
      const labels = chartXLabels.split(",")

      console.log("YDatasets", YDatasets)
      console.log("labels", labels)


  
      data = {
        ...data,
        labels: labels,
        // Y Dataset
        datasets: YDatasets,
      };

    }

      let options = {};

      const legendClickHandler = (e, legendItem, legend) => {
        const index = legendItem.datasetIndex;
        const ci = legend.chart;
        if (ci.isDatasetVisible(index)) {
            ci.hide(index);
            legendItem.hidden = true;
        } else {
            ci.show(index);
            legendItem.hidden = false;
        }
      }

      const chartOptions = {
        ...settings.wadiBarHorizontalSwitcher && {indexAxis: 'y'},
        animation: {
          duration: settings.wadiAnimationDuration?.size,
          easing: settings.wadiAnimationType,
          delay: settings.wadiAnimationDelay?.size,
        },
        elements: {
          point: {
            ... !!settings.wadiPointType && {
              pointStyle: settings.wadiPointType,
            },
            ... !!settings.wadiPointRadius && {
              radius: settings.wadiPointRadius?.size,
            },
            ... !!settings.wadiPointHoverRadius && {
              hoverRadius: settings.wadiPointHoverRadius?.size,
            },
            ... !!settings.wadiPointBorderWidth && {
              borderWidth: settings.wadiPointBorderWidth?.size,
            },
            ... !!settings.wadiPointHoverBorderWidth && {
            hoverBorderWidth: settings.wadiPointHoverBorderWidth?.size,
            },
            ... !!settings.wadiPointHitRadius && {
              hitRadius: settings.wadiPointHitRadius?.size,            
            }
          },
          line: {
            ... !!settings.wadiChartFillLine && {
              fill: settings.wadiChartFillLine,
            },
            ...settings.wadiChartDashedBorder && {
              borderDash: [8, 4],
            }
          }
        },
        layout: {
          padding: settings.wadiChartLayoutPadding?.size,
        },
        scales: {
          x: {
            display: settings.wadiChartXDisplay,
              grid: {
                display: settings.wadiChartXGridLines,
                // tickLength: 20,
                // tickColor: '#F05A28',
                // borderWidth: 12,
                // lineWidth: 9,
                // offset: true
              },
              ticks: {
                display: settings.wadiChartXTicksSwitch,
                beginAtZero: settings.wadiChartXTicksBeginAtZero,
              },
              title: {
                display: settings.wadiShowXTitleSwitch,
                text: settings.wadiChartXDisplayTitleText,
              }
          },
          y: {
            display: settings.wadiChartYDisplay,
              grid: {
                display: settings.wadiChartYGridLines,
                // tickLength: 20,
                // tickColor: '#F05A28',
                // lineWidth: 9,
                // color: '#F05A28',
                // color: settings.x_axis_grid_color,
                // lineWidth: settings.x_axis_grid_width?.size,
                // drawBorder: true
              },
            ticks: {
              // callback: function(value, index, values) {
              //     return value + ' Trillion';
              //     // 'junior-dev' will be returned instead and displayed on your chart
              // },
              display: settings.wadiChartYTicksSwitch,
              beginAtZero: settings.wadiChartYTicksBeginAtZero,
              color: '#F05A28',
              font: {
                size: 18,
              },
            },
              // stacked: true,
              // min: 0,
              // max: 100,
              title: {
                display: settings.wadiShowYTitleSwitch,
                text: settings.wadiChartYDisplayTitleText,
              }
          }
        },
        plugins: {
          legend: {
              display: settings.wadiChartLegendDisplay,
              ... !!settings.wadiChartLegendDisplay && {
                ... !!settings.wadiChartLegendPosition && {
                  position: settings.wadiChartLegendPosition,
                },
                ... !!settings.wadiChartLegendAlignment && {
                  align: settings.wadiChartLegendAlignment,
                },
                rtl: settings.wadiChartLegendRTL,
                ... !!settings.wadiChartLegendRTL && {
                  ... !!settings.wadiChartLegendRTLTextDirection && {
                    textDirection: settings.wadiChartLegendRTLTextDirection,
                  }
                } 
              },
              labels: {
                // This more specific font property overrides the global property
                color: settings.wadiChartLegendLabelsColor,
                font: {
                  size: settings.wadiChartLegendLabelsFontSize?.size,
                  weight: settings.wadiChartLegendLabelsFontWeight,
                },
                padding: settings.wadiChartLegendLabelsPadding?.size,
                // pointStyle: 'circle',
                // usePointStyle: true,
                textAlign: 'right',
              },
              title: {
                display: settings.wadiChartLengendTitle,
                ... !!settings.wadiChartLengendTitle && {
                  ... !!settings.wadiChartLegenedTitleText && {
                    text: settings.wadiChartLegenedTitleText,
                  },
                  color: settings.wadiChartLegendTitleColor,
                  font: {
                    size: settings.wadiChartLegendTitleFontSize?.size,
                    weight: settings.wadiChartLegendTitleFontWeight,
                  },
                  padding: {
                    top: settings.wadiChartLegendTitlePadding?.size,
                    bottom: settings.wadiChartLegendTitlePadding?.size
                  }
                }
            },
            onClick: legendClickHandler,
          },
          title: {
            display: settings.wadiChartTitleSwitcher,
            ... !!settings.wadiChartTitleSwitcher && {
              ... !!settings.wadiChartTitleText && {
                text: settings.wadiChartTitleText,
              },
              ... !!settings.wadiChartTitleAlignment && {
              align: settings.wadiChartTitleAlignment,
              },
              ... !!settings.wadiChartTitlePosition && {
                position : settings.wadiChartTitlePosition,
              },
              color: settings.wadiChartTitleColor,
                font: {
                  size: settings.wadiChartTitleFontSize?.size,
                  weight: settings.wadiChartTitleFontWeight,
                },
                padding: settings.wadiChartTitlePadding?.size,
            }
          },
          tooltip: {
            enabled: settings.wadiChartTooltipSwitcher,
            mode: settings.wadiChartTooltipMode,
            intersect: true,
            // position: 'nearest',
            rtl: settings.wadiChartTooltipRTL,
            // callbacks: {
            //   footer: function(context) {
            //     console.log("context[0] Tooltips", context[0]);
            //     return 'Total: ' + context[0].dataset.data[context[0].dataIndex] + ' Trillion';
            //   }
            // },

            backgroundColor: settings.wadiChartTooltipBackgroundColor,
            cornerRadius: settings.wadiChartTooltipCornerRadius?.size,
            padding: settings.wadiChartTooltipPadding?.size,

            // Tooltips Title
            titleColor: settings.wadiChartTooltipTitleColor,
            titleFont: {
              size: settings.wadiChartTooltipTitleFontSize?.size,
              weight: settings.wadiChartTooltipTitleFontWeight,
            },
            titleAlign: settings.wadiChartTooltipTitleAlignment,
            titleSpacing: settings.wadiChartTooltipTitleSpacing?.size,
            titleMarginBottom: settings.wadiChartTooltipTitleMarginBottom?.size,

            // Tooltip Body
            bodyColor: settings.wadiChartTooltipBodyColor,
            bodyFont: {
              size: settings.wadiChartTooltipBodyFontSize?.size,
              weight: settings.wadiChartTooltipBodyFontWeight,
            },
            bodyAlign: settings.wadiChartTooltipBodyAlignment,
            bodySpacing: settings.wadiChartTooltipBodySpacing?.size,

            // Tooltip Caret
            caretPadding: settings.wadiChartTooltipCaretPadding?.size,
            caretSize: settings.wadiChartTooltipCaretSize?.size,

            ... 'polarArea' === settings.wadiChartType && {
              callbacks:{
                label: function(context) {
                  let tooltipText = '';
                  let label = context.dataset.label || '';
                  let polarAreaLabelValue = context.label || '';
  
                          if (polarAreaLabelValue) {
                              polarAreaLabelValue += ': ';
                          }
  
                          tooltipText = polarAreaLabelValue +  label + '\n'+ context.formattedValue;

                          return tooltipText;
                }
              }
            } 
          }
        },
      responsive: true,
      maintainAspectRatio: settings.wadiChartAspectRatioSwitch,
      aspectRatio: settings.wadiChartApectRatio,
      };

      options = { ...chartOptions}

      // console.log('Chart options',options);
    
      const config = {
        type: settings.wadiChartType,
        data: data,
        options: options
      };

      if('custom' === settings.wadiChartContentType) {
        let _this = this;
        if(settings.wadiChartDisplayEvent === true) {
          const waypoint = new Waypoint({
            element: settings.wadiChartCanvasElement,
            offset: Waypoint.viewportHeight() - 100,
            triggerOnce: true,
            handler: function () {
              let wadiCustomChart = new Chart(
                settings.wadiChartCanvasElement,
                config
              );
                _this.wadiChartLinks(settings.wadiChartCanvasElement, wadiCustomChart);
                this.destroy();
              }
          });
        } else {
          let wadiCustomChart = new Chart(
            settings.wadiChartCanvasElement,
            config
          );
          _this.wadiChartLinks(settings.wadiChartCanvasElement, wadiCustomChart);
        }


      } else {

        let _this = this;
        const chartsSettings = this.getElementSettings();
        
        let wadiLoader = document.createElement("div");
        wadiLoader.classList.add('wadi_loader');

        settings.wadiChartContainer.appendChild(wadiLoader);

        const wadiChartURL = chartsSettings.wadi_chart_url_type === 'remote' ? chartsSettings.wadi_chart_csv_remote_link : chartsSettings.wadi_chart_csv_file.url;


        if( wadiChartURL ) {
          jQuery.ajax({
            url: wadiChartURL,
            type: "GET",
            success: function (res) {

                settings.wadiChartContainer.querySelector('.wadi_loader').remove();

                const ctx = settings.wadiChartCanvasElement;

                const rowsData = res.split(/\r?\n|\r/);
                const labels = (rowsData.shift()).split(settings.wadiChartCSVSeparator);


                let CSVRepeaterData = settings.wadiChartCSVYRepeater;

                let wadiChartCSVYTitle, wadiChartCSVYColor, wadiChartCSVYBorderColor, wadiChartCSVYBorderWidth;
                let csvDatasets  = [];

                rowsData.map((item, index) => {


                  let datasetIndex = index + 1;

                  if(CSVRepeaterData[index]) {
                    wadiChartCSVYTitle = CSVRepeaterData[index].wadi_chart_csv_y_axis_title || 'Dataset #' + (datasetIndex + 1);
                  } else {
                    wadiChartCSVYTitle = 'Dataset #' + (datasetIndex + 1);
                  }

                  if(CSVRepeaterData[index]) {
                    wadiChartCSVYColor = CSVRepeaterData[index].wadi_chart_csv_y_axis_background_color || '#6EC1E4';
                  } else {
                    wadiChartCSVYColor = '#6EC1E4';
                  }

                  if(CSVRepeaterData[index]) {
                    wadiChartCSVYBorderColor = CSVRepeaterData[index].wadi_chart_csv_y_axis_border_color || '#000000';
                  } else {
                    wadiChartCSVYBorderColor = '#000000';
                  }

                  if(CSVRepeaterData[index]) {
                    wadiChartCSVYBorderWidth = CSVRepeaterData[index].wadi_chart_csv_y_axis_border_width.size || 0;
                  } else {
                    wadiChartCSVYBorderWidth = 0;
                  }

                  _this.wadiCSVDatasetURLs = [];
                  if(CSVRepeaterData[index]) {
                    if(CSVRepeaterData[index].wadi_chart_csv_y_axis_urls) {
                      CSVRepeaterData[index].wadi_chart_csv_y_axis_urls.split(',').map((dataSetCSVURL, index) => {
                        _this.wadiCSVDatasetURLs.push(dataSetCSVURL.trim());
                      });
                    }
                  }
                  

                  csvDatasets.push({
                    label: wadiChartCSVYTitle,
                    backgroundColor: wadiChartCSVYColor,
                    borderColor: wadiChartCSVYBorderColor,
                    borderWidth: wadiChartCSVYBorderWidth,
                    hoverBackgroundColor: settings.wadiYAxisHoverColor,
                    hoverBorderColor: settings.wadiYAxisHoverBorderColor,
                    links: _this.wadiCSVDatasetURLs,
                    // Repeated Data

                    //Advanced Bar Settings
                    ...settings.wadiAdvancedBarSettings && {
                      borderRadius: settings.wadiBarBorderRadius,
                      hoverBorderRadius: settings.wadiHoverBarBorderRadius,
                      barThickness: settings.wadiBarThickness,
                      barPercentage: 0.5,
                    },
                    ...settings.wadiAdvancedPieDoughnutSettings && {
                      ... !!settings.wadiPieDoughnutHoverOffset?.size && {
                        hoverOffset: settings.wadiPieDoughnutHoverOffset?.size
                      },
                      ... !!settings.wadiPieDoughnutOffset?.size && {
                        offset: settings.wadiPieDoughnutOffset?.size
                      },
                      ... !!settings.wadiPieDoughnutSpacing?.size && {
                        spacing: settings.wadiPieDoughnutSpacing?.size,
                      },
                      ... !!settings.wadiPieDoughnutCircumference?.size && {
                      circumference: settings.wadiPieDoughnutCircumference?.size
                      },
                      ... !!settings.wadiPieDoughnutRotation?.size && {
                        rotation: settings.wadiPieDoughnutRotation?.size
                      },
                    },
                    ...settings.wadiAdvancedLineSettings && {
                      ... !!settings.wadiLineTension?.size && {
                        tension: settings.wadiLineTension?.size,
                      },
                      ... !!settings.wadiLineStepped && {
                        stepped: settings.wadiLineStepped,
                      },
                      // ... !!settings.wadiLinePointStyle && {
                      //   pointStyle: settings.wadiLinePointStyle,
                      // },
                      ... !!settings.wadiLineHorizontal && { // Horizontal Chart Line
                        indexAxis: settings.wadiLineHorizontal,
                      }
                    },
                    data: [],
                  });

                  data = {
                    ...data,
                    labels: labels,
                    datasets: csvDatasets
                  };

                  // console.log('DATA CSV DATA datasets', data.datasets);

                });
                let chartInstance;
                if(settings.wadiChartDisplayEvent === true) {

                  const waypoint = new Waypoint({
                    element: settings.wadiChartCanvasElement,
                    offset: Waypoint.viewportHeight() - 100,
                    triggerOnce: true,
                    handler: function () {
                      chartInstance = new Chart(ctx, {
                        type: settings.wadiChartType,
                        data: data,
                        options: {...chartOptions}
                    });
                    
                    // CSV Rows Data
                    let rowData = [];
                    rowsData.forEach(function (row, index) {


                      if (row.length !== 0 && csvDatasets.length > 0) {
                          // Split the csv file rows by comma
                          rowData = row.split(settings.wadiChartCSVSeparator);
                          // Check if the dataset exists
                          if(csvDatasets[index]) {
                            csvDatasets[index].data = rowData;
                          }
                        }
                    _this.wadiChartLinks(settings.wadiChartCanvasElement, chartInstance);
                      chartInstance.update();

                    });
                        this.destroy();
                      }
                  });

            } else {
              chartInstance = new Chart(ctx, {
                type: settings.wadiChartType,
                data: data,
                options: {...chartOptions}
              });
              
              // CSV Rows Data
              let rowData = [];
              rowsData.forEach(function (row, index) {


                if (row.length !== 0 && csvDatasets.length > 0) {
                    // Split the csv file rows by comma
                    rowData = row.split(settings.wadiChartCSVSeparator);
                    // Check if the dataset exists
                    if(csvDatasets[index]) {
                      csvDatasets[index].data = rowData;
                    }
                }
                _this.wadiChartLinks(settings.wadiChartCanvasElement, chartInstance);
                chartInstance.update();
                
              });
            }




            },
            error: function (err) {
                console.log(err);
            }
        });
      }
    }



    }

    wadiChartLinks(elementChart, chartObject) {
      elementChart.addEventListener('click', (evt) => {
        const activePoint = chartObject.getElementsAtEventForMode(evt, 'point', chartObject.options);
        if (activePoint[0]) {
          var URL = chartObject.data.datasets[activePoint[0].datasetIndex].links[activePoint[0].index];
          if (URL) {
            window.open(URL, '_blank');
          }
      }

      });

    }
      
}


  jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiCharts, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-charts-addon.default",
      addHandler
    );
  });
  