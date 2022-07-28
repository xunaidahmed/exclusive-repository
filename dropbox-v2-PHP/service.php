<?php
include "Dropbox.php";

$base_url           = 'http://localhost/devops/dropbox-core/service.php';
$client_id           = 'xxxxxxxxxxxxxxxx';
$client_secret       = 'xxxxxxxxxxxxxxxx';
$client_access_token = 'xxxxxxxxxxxxxxxx';


$dropbox = new Dropbox($client_id, $client_secret, $client_access_token);
//$dropbox->isDebug(true);

$folders = $dropbox->getStorageLists('Storage-1', ['.tag', 'name', 'path_display']);

//Open Folder
if( isset($_GET['folder-open']) && $_GET['folder-open'] ) {
	$folders = $dropbox->getListByStorage($_GET['folder-open'], ['.tag', 'name', 'path_display']);
}

//Open Folder
if( isset($_GET['folder-share']) && $_GET['folder-share'] ) {

    $folder = str_replace('/'.$_GET['file'], '', $_GET['folder-share']);
	$dropbox->doShareLinks($folder, $_GET['file']);

    $location = ltrim($_SERVER['HTTP_REFERER'], '/');
   	header("Location: " . $location ); die();
}

//Create Folder
if( isset($_POST['submitfolder']) && $_POST['folder_name']) {
	$disk_storage   = $_GET['folder-open'];
	$create_storage = $dropbox->createFolder( $disk_storage . '/' . $_POST['folder_name']);
	$location = ltrim($_SERVER['HTTP_REFERER'], '/') . $create_storage['path'];
	header("Location: " . $location ); die();
}

//Upload Files
if( isset($_POST['submitupload']) && $_FILES['upload']) {

	$disk_storage   = $_GET['folder-open'];
	$upload_file = $_FILES['upload'];

	$upload = $dropbox->doContentUpload($disk_storage, $upload_file);
	header("Location: " . $_SERVER['HTTP_REFERER'] ); die();
}
?>

<html lang="en">
<head>
  <title>Dropbox API</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>

	<div class="container">
		<h2>Dropbox</h2>

		<div class="row">
			<div class="col-md-6">
				<h2>Create Folder</h2>
				<form method="POST">
					<div class="form-group">
						<label for="usr">Name:</label>
						<input type="text" name="folder_name" class="form-control">
					</div>
					<button type="submit" name="submitfolder" class="btn btn-default">Create</button>
				</form>
			</div>
			<div class="col-md-6">
				<h2>Upload Files</h2>
				<form method="POST" enctype="multipart/form-data">
					<div class="form-group">
						<label for="usr">Upload:</label>
						<input type="file" name="upload" class="form-control">
					</div>
					<button type="submit" name="submitupload" class="btn btn-default">Upload</button>
				</form>
			</div>
		</div>

		<div class="row">
			<div class="col-md-10"></div>
			<div class="col-md-2" style="padding: 50px 0;">
				<?php if( isset($_GET['folder-open']) && $_GET['folder-open'] ):?>
				<a href="<?php echo $base_url . '?folder-open=' . Dropbox::backToStorage($_GET['folder-open']);?>" style="float: right"><i class="fa fa-undo"></i></a>
				<?php endif;?>
			</div>
		</div>

		<div class="row">
			<table class="table">
				<tr>
					<th width="5%"></th>
					<th>Name</th>
					<th width="10%">Action</th>
				</tr>
				<?php if( count($folders) ): ?>
					<?php
					foreach( $folders as $folder ):

						$is_folder      = Dropbox::isCheckFolder($folder['path_display']);
					    $share_links    = $dropbox->getShareLinks($folder['path_display'], $folder['.tag']);
					?>
					<tr>
						<td><i class="fa fa-book"></td>
						<td><?php echo $folder['name'];?></td>
						<td>
							<?php
							if( $is_folder ) {
							?>
								<a href="<?php echo $base_url;?>?folder-open=<?php echo $folder['path_display'];?>" style="margin-right: 10px;">
									<i class="fa fa-folder-open"></i>
								</a>
							<?php
							} else {
							?>
								<a target="_blank" href="<?php echo $dropbox->downloadFile($folder['path_display'], $folder['name']);?>"><i class="fa fa-download"></i></a>
							<?php
							}
							?>

                            <?php if( is_null($share_links) ){ ?>
							<a href="<?php echo $base_url;?>?folder-share=<?php echo $folder['path_display'];?>&file=<?php echo $folder['name'];?>" style="margin-right: 10px;">
								<i class="fa fa-link"></i>
							</a>
                            <?php } else { ?>
                                <a href="javascript:void(0)" onclick="javascript:navigator.clipboard.writeText('<?php echo $share_links;?>')" style="margin-right: 10px;">
                                    Copy Link
                                </a>
                            <?php } ?>
						</td>
					</tr>
					<?php endforeach;?>
				<?php else: ?>
				<tr>
					<td colspan="3">No available</td>
				</tr>
				<?php endif;?>
			</table>
		</div>

	</div>

</body>
</html>
