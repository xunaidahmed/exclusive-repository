/**
 * @created_at 2018-Jan-25
 * @param element
 * @param url
 * @param data
 */
 var DataTableHelper = function (element, url, ajaxParams, options ) {

    var self        = this;
    var instance    = null;
    var ajaxParams  = ajaxParams || {};
    var sortColumn  = [
        [0, "asc"]
    ];

    // define columns sorting options(by default all columns are sortable except the first checkbox column)
    var orderableColumnList = [{
        'orderable': true,
        'targets': [0]
    }];

    this.init = function ()
    {
        $(element).DataTable( self.api() );
    }

    this.handleClickSearch = function()
    {
        self.setFilterSearch();

        $(element).DataTable( self.api() );
    }
    
    this.handleClickRest = function()
    {
        if(  $('input.datatable-search').length )
        {
            $('input.datatable-search').val('');
            self.setFilterSearch();

            $(element).DataTable( self.api() );
        }
    }

    this.setSortColumn = function( colIndex, direction) {

        sortColumn = [];
        direction = typeof direction == 'undefined' ? 'asc' : direction;
        sortColumn.push ( [ colIndex, direction ] );
    }

    this.setOrderableColumnList = function( colIndex, isOrderable ) {

        orderableColumnList = [{
            'orderable': isOrderable,
            'targets': colIndex
        }];
    }
    this.setFilterSearch = function()
    {
        //Inputs
        if(  $('input.datatable-search').length )
        {
            $('input.datatable-search').each(function(index, row){

                var name    = row.name;
                var value   = row.value;

                ajaxParams[name] = value;
            });
        }

        //Select-DropDown
        if( $('select.datatable-search').length )
        {
            $('select.datatable-search').each(function(index, row){

                var name    = row.name;
                var value   = row.value;

                ajaxParams[name] = value;
            });
        }
    }

    this.api = function()
    {
        // This is the easiest way to have default options.
        var settings = $.extend({

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' servermside processing mode.

            "lengthMenu": [
            [ 10, 25, 50, 100, 250, 500, 'All'],
                [ 10, 25, 50, 100, 250, 500, 'All'] // change per page values here
                ],

                "pageLength" : 10,

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": url,
                "type": "POST",
                "data": ajaxParams,
                "dataType": "json",
                "dataSrc": function (jsonData) {
                    return jsonData.data;
                }
            },

            "destroy": true,

            //Set column definition initialisation properties.
            "columnDefs": orderableColumnList,

            "order": sortColumn,

            "searching": false

        }, options );

        return settings;
    }
}