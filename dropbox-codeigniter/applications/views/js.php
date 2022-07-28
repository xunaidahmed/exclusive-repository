<script src="<?php echo base_url('public_html/assets/js/custom/dropbox.js')?>"></script>
<script>

var dropbox = new DropboxHelper;

$(document).ready(function(){

  $(document).on('click', '.deleteAction', function(event, state) {
  
    var thisParent  =   $(this).parents("tr");
    
    var postdata    =   {
      type: $(this).attr("data-type"),
      rowID: $(this).attr("data-id"),
      delete: 'delete',
      <?php echo csrf_token_field();?>: "<?php echo csrf_token();?>"
    };
   
    // Dropbox Modals
    $(document).on('click', '.const-dropbox', function(){

      var dropboxLink = "<?php echo base_url($controller.'/file/dropbox'); ?>";
      
      dropbox.dropboxInitialManageAccess( dropboxLink, $(this).data('type'), $(this).attr('data-path'), {
        <?php echo csrf_token_field();?>: '<?php echo csrf_token();?>',
      });
    });

  // Dropbox Create Folder
  $(".const-new-folder-button").on('click', function(){

    var filePath    = $('.const-dropbox-sync').attr('data-path');
    var folder_name = $('.const-folder-name').val();
    filePath        = ((filePath != '/') ? filePath + '/' : filePath);
    filePath        = filePath + folder_name;
    
    var dropboxLink = "<?php echo base_url($controller.'/file/dropbox'); ?>";

    dropbox.createNewFolder( dropboxLink, 'new-folder', filePath, {
      <?php echo csrf_token_field();?>: '<?php echo csrf_token();?>',
    });

  });

  $(document).on('click', ".const-rename-folder-modal", function(){

    $(".const-folder-rename").val( $(this).attr('data-path') )
    $("#dropboxRenameFolderModal").modal('show')
  });

  // Dropbox Rename Folder
  $(".const-rename-folder-button").on('click', function(){

    var filePath    = $('.const-dropbox-sync').attr('data-path');
    var folder_name = $('.const-rename-to-folder').val();
    filePath        = ((filePath != '/') ? filePath + '/' : filePath);
    filePath        = filePath + folder_name;
    
    var dropboxLink = "<?php echo base_url($controller.'/file/dropbox'); ?>";
    
    dropbox.renameFolder( dropboxLink, 'rename-folder', filePath, {
      <?php echo csrf_token_field();?>: '<?php echo csrf_token();?>',
      rename_folder: $(".const-folder-rename").val()
    });
  });

  // Dropbox Upload Files
  $('.const-dropbox-upload-button').on('click', function(){

    var dropboxBody = $('.const-dropbox-upload-body');
    var error       = dropboxBody.find('.const-dropbox-error');

    error.html('');

    if( $('.const-upload-file').val() == "" )
    {
      error.html('Please Select File');
      return;
    }      

    var uploadData  = $('.const-upload-file')[0].files[0];
    var filePath    = $('.const-dropbox-sync').attr('data-path');

    var dropboxLink = "<?php echo base_url($controller.'/file/dropbox'); ?>";

    var form_data = new FormData();
    form_data.append('<?php echo csrf_token_field();?>', '<?php echo csrf_token();?>');

    form_data.append('file', uploadData);      
    form_data.append('type', 'upload-file');
    form_data.append('path', filePath);

    dropbox.uploadFiles( dropboxLink, 'upload-file', filePath, form_data);
    
  });
  
});
</script>