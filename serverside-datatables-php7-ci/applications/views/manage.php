<div class="page-content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-xs-12">
                
                <?php alertMessage();?>

                <div class="row">
                    <div class="col-xs-12">
                        
                        <div class="table-header" style="margin: 10px 0;">
                            Results for Projects
                            <div class="clearfix"></div>
                            <div class="p-5"></div>
                        </div>

                        <div class="white-box">
                            <div class="table-responsive">
                                <table id="ajaxServerSideDatatable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Urdu</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th><input type="text" name="title" class="datatable-search"></th>
                                            <th><input type="text" name="title_ur" class="datatable-search"></th>
                                            <th>
                                                <select name="is_enable" class="datatable-search">
                                                    <option value=""></option>
                                                    <option value="1">Active</option>
                                                    <option value="0">De-Active</option>
                                                </select>
                                            </th>
                                            <th><input type="text" name="created_date" class="datatable-search"></th>
                                            <th>
                                                <button type="button" id="handlerSeachButton" class="btn btn-info btn-xs">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                                <button type="button" id="handlerresetButton" class="btn btn-info btn-xs">
                                                    <i class="fa fa-refresh"></i>
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    
                </div>

            </div>
        </div>

    </div>
</div>