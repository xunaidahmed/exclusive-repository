/**
 * @author Junaid Ahmed
 * @created_at 2019-01
 */
var DropboxHelper = function()
{
	var self        = this;
    var instance    = null;

    this.createOfBackPath = function( filePath ) {

    	if( filePath.length > 1 ) {
			
			newFilePath = filePath.split('/');
			delete newFilePath[newFilePath.length-1];

			return newFilePath.join('/').slice(0, -1);
		}

		return '/';
    },

	this.dropboxInitialManageAccess = function( ajaxURL, typeOfFile, filePath = '/', data = null )
	{
		var ajaxParams = {
			type: typeOfFile,
			path: filePath
		}

		if( data ) {
			ajaxParams = self.addMergeArray( ajaxParams, data);
		}

		self.dropboxRequestOfAjax(ajaxURL, ajaxParams, typeOfFile, filePath)
	},

	this.createNewFolder = function( ajaxURL, typeOfFile, filePath = '/', data = null )
	{
		var ajaxParams = {
			type: typeOfFile,
			path: filePath
		}

		if( data ) {
			ajaxParams = self.addMergeArray( ajaxParams, data);
		}

		self.dropboxRequestOfAjax(ajaxURL, ajaxParams, typeOfFile, filePath)
	},

	this.renameFolder = function( ajaxURL, typeOfFile, filePath = '/', data = null )
	{
		var ajaxParams = {
			type: typeOfFile,
			path: filePath
		}

		if( data ) {
			ajaxParams = self.addMergeArray( ajaxParams, data);
		}

		self.dropboxRequestOfAjax(ajaxURL, ajaxParams, typeOfFile, filePath)
	},

	this.uploadFiles = function(ajaxURL, typeOfFile, filePath = '/', ajaxParams = null)
	{
		
		$.ajax({
			type: "POST",
			url: ajaxURL,
			beforeSend: function (jqXHR, settings){
				settings.data.append('<?php echo $this->security->get_csrf_token_name()?>',getCookie('<?php echo $this->security->get_csrf_cookie_name()?>'));	
			},
			data: ajaxParams,
			dataType: "text",
			contentType: false,
        	processData: false,
			success: function( response )
			{
				self.dropboxAjaxSuccess(typeOfFile, filePath, response);
			}
		});
	},

	this.dropboxRequestOfAjax = function(ajaxURL, ajaxParams, typeOfFile, filePath)
	{
		$.ajax({
			type: "POST",
			url: ajaxURL,
			data: ajaxParams,
			dataType: "html",
			success: function( response )
			{
				self.dropboxAjaxSuccess(typeOfFile, filePath, response);
			},
			complete: function( response )
			{
				// code ...
			}
		});
	},

	this.dropboxAjaxSuccess = function( typeOfFile, filePath, ajaxResponse )
	{
		var backward = self.createOfBackPath(filePath);

		if( typeOfFile == 'folder' || typeOfFile == 'backward' || typeOfFile == 'delete' )
		{
			$(".const-dropbox-sync").attr('data-path', filePath);
			$(".const-dropbox-undo").attr('data-path', backward);
			$(".const-dropbox-body").html( ajaxResponse );
		}

		if( typeOfFile == 'new-folder' )
		{
			$(".const-dropbox-folder-close").trigger('click');
        	$(".const-dropbox-sync").trigger('click');

        	$(".const-folder-name").val('');
		}

		if( typeOfFile == 'rename-folder' )
		{		

			$(".const-dropbox-rename-folder-close").trigger('click');

			$(".const-dropbox-sync").attr('data-path', filePath);
			$(".const-dropbox-undo").attr('data-path', backward);
			$(".const-dropbox-body").html( ajaxResponse );

			$(".const-rename-to-folder").val('');
		}

		if( typeOfFile == 'upload-file' )
		{
			$('.const-upload-file').val('')
			$(".const-dropbox-folder-close").trigger('click');
			$(".const-dropbox-body").html( ajaxResponse );
		}

		if( typeOfFile == 'copy-link' )
		{
			$("#constCopyToClipboard").val( ajaxResponse );
			$(".const-dropbox-close").click();
		}

		if( typeOfFile == 'download' )
		{
			window.open(ajaxResponse, '_blank')
		}
	},	

	this.addMergeArray = function(array1, array2)
	{
		$.each(array2, function(index, value){
			
			array1[index] = value;
		});

		return array1;
	}
}