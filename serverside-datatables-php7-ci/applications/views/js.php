<?php
$controller = $this->uri->segment(1);
?>
<script type="text/javascript" src="<?php  echo base_url('assets/js/datatables.js')?>"></script>
<script type="text/javascript">
    
    const module_url    = "<?php echo base_url($controller);?>";

    $("input[name=created_date]").datepicker({format: 'yyyy-mm-dd', autoclose: true});

    $('.datatable-search').bind('focus click', function (e) {
        e.stopPropagation();
    });

    let xGrid = new DataTableHelper( "#ajaxServerSideDatatable", module_url + '/ajaxPostManage');
    xGrid.setSortColumn(1, 'desc');
    xGrid.setOrderableColumnList([0, 5], false);
    xGrid.init();

    //Advance Search
    $("#handlerSeachButton").on('click', function(){
        xGrid.handleClickSearch();
    });
    
    // Reset Search
    $("#handlerresetButton").on('click', function(){
        xGrid.handleClickRest();
    });
</script>