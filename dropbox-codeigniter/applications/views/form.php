

<div id="dropboxModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="margin: -20px 10px 0 0;">&times;</button>
                <a href="javascript:void(0)" class="const-dropbox const-dropbox-sync" data-type="folder" data-path="/" style="float: right; margin-right: 30px;margin-top: 0px;font-size: 18px;" ><i class="fa fa-refresh"></i></a>
                <a href="javascript:void(0)" class="const-dropbox const-dropbox-undo" data-type="backward" data-path="/" style="float: right; margin-right: 15px;margin-top: 0px;font-size: 18px;" ><i class="fa fa-undo"></i></a>
                <a href="<?php echo $dropbox_auth_uri;?>" style="float: right; margin-right: 14px;margin-top: 5px;font-size: 15px;" >Sync Access</a>

                <h4 class="modal-title">Manage of Vault Access</h4>
            </div>
            <div class="modal-body const-dropbox-body">                    
                <?php echo dropbox_template( $list_of_root );?>
            </div>
            <div class="modal-footer">
                <button type="button" data-toggle="modal" data-target="#dropboxCreateFolderModal" class="const-dropbox-create-folder-button btn btn-primary">
                    Create Folder
                </button>
                <button type="button" data-toggle="modal" data-target="#dropboxUploadFileModal" class="const-dropbox-upoad-file-button btn btn-primary">
                    Upload File
                </button>
                <button type="button" class="const-dropbox-close btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="dropboxCreateFolderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">            
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="margin: -20px 10px 0 0;">&times;</button>
                <h4 class="modal-title">Create Folder</h4>
            </div>
            <div class="modal-body">
                
                <div class="form-inline">
                    <div class="form-group">
                        <label>Folder Name:</label>
                        <input type="text" class="form-control const-folder-name">
                    </div>
                    <button type="button" class="btn btn-default const-new-folder-button">Save</button>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="const-dropbox-folder-close btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="dropboxRenameFolderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">            
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="margin: -20px 10px 0 0;">&times;</button>
                <h4 class="modal-title">Rename Folder</h4>
            </div>
            <div class="modal-body">
                
                <div class="form-inline">
                    <input type="hidden" class="form-control const-folder-rename">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" class="form-control const-rename-to-folder">
                    </div>
                    <button type="button" class="btn btn-default const-rename-folder-button">Save</button>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="const-dropbox-rename-folder-close btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="dropboxUploadFileModal" class="modal fade" role="dialog">
    <div class="modal-dialog">            
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="margin: -20px 10px 0 0;">&times;</button>
                <h4 class="modal-title">Upload File</h4>
            </div>
            <div class="modal-body const-dropbox-upload-body">
                <div class="form-inline">
                    <div class="form-group">
                        <label>Chooose:</label>
                        <input type="file" class="form-control const-upload-file">
                    </div>
                    <div class="form-group const-dropbox-error alert-danger"></div>
                    <button type="button" class="btn btn-default const-dropbox-upload-button">Upload</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="const-dropbox-folder-close btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>