import './style/table.scss';

class WadiTable extends elementorModules.frontend.handlers.Base {

    
  getDefaultSettings() {
    return {
      selectors: {
        wadiMediaSwiper: '.swiper',
      },
      tableState: {
        isLoaded: false,
      }
    }
  }

  getDefaultElements() {
    const selectors = this.getSettings('selectors');
    const elements = {
      $wadiMediaSwiperElem: this.findElement(selectors.wadiMediaSwiper),
    };

    return elements;
  }

  bindEvents() {
    this.initiate();
  }

  initiate(){
    const _this = this;
    const elementsSettings = this.getElementSettings();


    const wadiTableSettings = {
        wadiTableElement: ".wadi_table_" + this.$element[0].getAttribute("data-id"),
        // waduTableHeadersDisplay: 'yes' === elementsSettings.wadi_table_headers_display_switcher ? true : false,
        // waduTableBodyDisplay: 'yes' === elementsSettings.wadi_table_body_display_switcher ? true : false,
        wadiTableContentType: elementsSettings.wadi_table_data_content_type ? elementsSettings.wadi_table_data_content_type : 'custom',
        wadiTableContentURLType: elementsSettings.wadi_table_url_type ? elementsSettings.wadi_table_url_type : 'local',
        ...'remote' === elementsSettings.wadi_table_url_type && {
          wadiTableContentRemoteURL: elementsSettings.wadi_table_csv_remote_link ? elementsSettings.wadi_table_csv_remote_link : '',
        },
        wadiTableCSVSeparator: elementsSettings.wadi_table_csv_file_content_separator ? elementsSettings.wadi_table_csv_file_content_separator : ',',
        wadiTableSorting: 'yes' === elementsSettings.wadi_table_sorting_switcher ? true : false,
        wadiTablePagination: 'yes' === elementsSettings.wadi_table_pagination_switcher ? true : false,
        ...'yes' === elementsSettings.wadi_table_pagination_switcher && {
          wadiTablePaginationNextText: elementsSettings.wadi_table_pagination_next_text ? elementsSettings.wadi_table_pagination_next_text : 'Next',
          wadiTablePaginationPrevText: elementsSettings.wadi_table_pagination_prev_text ? elementsSettings.wadi_table_pagination_prev_text : 'Previous',
          wadiTablePaginationFirstText: elementsSettings.wadi_table_pagination_first_text ? elementsSettings.wadi_table_pagination_first_text : 'First',
          wadiTablePaginationLastText: elementsSettings.wadi_table_pagination_last_text ? elementsSettings.wadi_table_pagination_last_text : 'Last',
        },
        wadiTableSearch: 'yes' === elementsSettings.wadi_table_search_table_switcher ? true : false,
        ... 'yes' === elementsSettings.wadi_table_search_table_switcher && {
          wadiTableSearchText: elementsSettings.wadi_table_search_table_text ? elementsSettings.wadi_table_search_table_text : '',
          wadiTableSearchInputPlaceholder: elementsSettings.wadi_table_search_input_placeholder ? elementsSettings.wadi_table_search_input_placeholder : '',
          wadiTableNoRecordsText: elementsSettings.wadi_table_no_records_text ? elementsSettings.wadi_table_no_records_text : 'No matching records found',
        },
        wadiTableShowEntries: 'yes' === elementsSettings.wadi_show_entries_switcher ? true : false,
        wadiTableRecordsInfo: 'yes' === elementsSettings.wadi_table_info_switcher ? true : false,
        wadiTableCSVFile: elementsSettings.wadi_table_csv_file ? elementsSettings.wadi_table_csv_file : '',

        // Additional Features
        // Buttons
        wadiTableDownloadButtons: 'yes' === elementsSettings.wadi_table_download_buttons_switcher ? true : false,
        
        // Wadi Table Download Buttons
        ...'yes' === elementsSettings.wadi_table_download_buttons_switcher && {
          wadiTableCopyButton: 'yes' === elementsSettings.wadi_table_copy_button_switcher ? true : false,
          wadiTableCSVButton: 'yes' === elementsSettings.wadi_table_csv_button_switcher ? true : false,
          wadiTableExcelButton: 'yes' === elementsSettings.wadi_table_excel_button_switcher ? true : false,
        },
        // Table Responsive
        wadiTableResponsive: 'yes' === elementsSettings.wadi_table_responsive_switcher ? true : false,
    }

    _this.wadiTable(jQuery,wadiTableSettings);

    
  }

  wadiTable($,settings){
    const _this = this;
    const tableState = this.getSettings('tableState');

    console.log("Settings",settings);

    const wadiTableElement = settings.wadiTableElement;

    const e = document.createElement('div');
    e.classList.add('wadi_loader');
    e.innerHTML = 'Loading...';
    document.querySelector(wadiTableElement).appendChild(e);

    const elementPagination = $('.dataTables_paginate');
    // document.addEventListener('DOMContentLoaded', function(){
    // });

    const wadiTableCSVContentLocalURL = settings.wadiTableCSVFile.url;

    const wadiTableOptions = {
        "paging": settings.wadiTablePagination,
        "responsive": settings.wadiTableResponsive,
        "searching": settings.wadiTableSearch, 
        "ordering": settings.wadiTableSorting,
        "info": settings.wadiTableRecordsInfo,
        "lengthChange": settings.wadiTableShowEntries,
        ... settings.wadiTableDownloadButtons == true ? {
        "dom": 'Blfrtip', // This is shortcut for elements buttons, pagination, info, length change
          "buttons": [
            ...settings.wadiTableCopyButton && ['copy'] || [],
            ...settings.wadiTableCSVButton && ['csv'] || [],
            ...settings.wadiTableExcelButton && ['excel'] || [],
          ]
        } : {
          "dom": 'lfrtip', // This is shortcut for elements buttons, pagination, info, length change
        },
        "lengthMenu": [ 5,10, 25, 50, 75, 100 ],
        "oLanguage": {
          	"sSearch": settings.wadiTableSearchText,
            "sSearchPlaceholder": settings.wadiTableSearchInputPlaceholder,
          	// "sLengthMenu": 'green',
          	"sZeroRecords": settings.wadiTableNoRecordsText,            
            "oPaginate": {
              "sFirst": settings.wadiTablePaginationFirstText,
              "sLast":  settings.wadiTablePaginationLastText,
              "sNext": settings.wadiTablePaginationNextText,
              "sPrevious": settings.wadiTablePaginationPrevText,
            },
          "aria": {
              "sortAscending":  ": activate to sort column ascending",
              "sortDescending": ": activate to sort column descending"
          }
          },
          "createdRow": function( row, data, dataIndex ) {
            // Add class to row
            if(!row.classList.contains('.wadi_table_row')) {
              row.classList.add('wadi_table_row');
            }

            // Add class to columns
            const tds = row.querySelectorAll('td');
            const tdsArr = [...tds];
            tdsArr.forEach((td,index) => {
              if(!td.classList.contains('.wadi_table_col')) {
                td.classList.add('wadi_table_col');
              } 
            })

          },
          ... settings.wadiTablePagination == true && {
            "drawCallback": function () {
              // document.querySelector('.dataTables_paginate').classList.add('wadi_table_pagination');
              document.querySelectorAll('.dataTables_paginate').forEach((elem) => {
                if(_this.hasSomeParentTheClass(elem,'wadi_table_container')) {
                  elem.classList.add('wadi_table_pagination');
                }
              })
            }
          },
    }




    // Hide Table Button if no buttons are selected

    if(settings.wadiTableDownloadButtons === false){
      wadiTableOptions.buttons = [];
    }
    
    // https://datatables.net/reference/option/ Some other options

    if(settings.wadiTableContentType === 'custom'){
      tableState.isLoaded = true;
      document.querySelector(wadiTableElement).querySelector('.wadi_loader').style = 'display:none';
      $(wadiTableElement).DataTable({
          ...wadiTableOptions,
      });
    } else if(settings.wadiTableContentType === 'csv'){

      if(settings.wadiTableContentURLType === 'local'){

        if(wadiTableCSVContentLocalURL) {
          tableState.isLoaded = false;
          if(tableState.isLoaded == false) {
            document.querySelector(wadiTableElement).querySelector('.wadi_loader').style = 'display:block;';
          }

          jQuery.ajax({
            url: wadiTableCSVContentLocalURL,
            type: "GET",
            success: function (res) {

              const rowsData = res.split(/\r?\n|\r/);
              const TableHeader = (rowsData.shift()).split(settings.wadiTableCSVSeparator);

  
              const wadiCSVTableData = [];
              rowsData.map((row, index) => {
                const rowData = row.split(settings.wadiTableCSVSeparator);
                wadiCSVTableData.push(rowData);
              })
    
              const wadiCSVTableColumns = [];
              TableHeader.map((col, index) => {
                wadiCSVTableColumns.push({
                  title: col,
                })
              })
  
              tableState.isLoaded = true;
              document.querySelector(wadiTableElement).querySelector('.wadi_loader').style = 'display:none';
              $(wadiTableElement).DataTable({
                ...wadiTableOptions,
                "data": wadiCSVTableData,
                dataSrc:"",
                columns: wadiCSVTableColumns
              });
  
            }
          });

        }

      } else if(settings.wadiTableContentURLType === 'remote'){
        const wadiCSVTableURL = settings.wadiTableContentRemoteURL;

        if(wadiCSVTableURL){
        tableState.isLoaded = false;
        if(tableState.isLoaded == false) {
          document.querySelector(wadiTableElement).querySelector('.wadi_loader').style = 'display:block;';
        }
        jQuery.ajax({
          url: wadiCSVTableURL,
          type: "GET",
          success: function (res) {

            const rowsData = res.split(/\r?\n|\r/);
            const TableHeader = (rowsData.shift()).split(settings.wadiTableCSVSeparator);


            const wadiCSVTableData = [];
            rowsData.map((row, index) => {
              const rowData = row.split(settings.wadiTableCSVSeparator);
              wadiCSVTableData.push(rowData);
            })


            const wadiCSVTableColumns = [];
            TableHeader.map((col, index) => {
              wadiCSVTableColumns.push({
                title: col,
              })
            })

            tableState.isLoaded = true;
            document.querySelector(wadiTableElement).querySelector('.wadi_loader').style = 'display:none';
            $(wadiTableElement).DataTable({
              ...wadiTableOptions,
              "data": wadiCSVTableData,
              dataSrc:"",
              columns: wadiCSVTableColumns
            });

          }
        });
      }

      }

    }




  }

  // returns true if the element or one of its parents has the class classname
   hasSomeParentTheClass(element, classname) {
    if (element.className.split(' ').indexOf(classname)>=0) return true;
    return element.parentNode && this.hasSomeParentTheClass(element.parentNode, classname);
  }
  
}

jQuery(window).on("elementor/frontend/init", () => {
    const addHandler = ($element) => {
      elementorFrontend.elementsHandler.addHandler(WadiTable, {
        $element,
      });
    };
  
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/wadi-table-addon.default",
      addHandler
    );
  });

