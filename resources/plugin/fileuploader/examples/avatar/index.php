<!DOCTYPE html>
<html lang="en">

	<head>
		<title>Avatar example - fileuploader - Innostudio.de</title>
		
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Avatar example - fileuploader - Innostudio.de">
        
        <link rel="shortcut icon" href="https://innostudio.de/fileuploader/images/favicon.ico">

		<!-- fonts -->
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
        <link href="../../dist/font/font-fileuploader.css" rel="stylesheet">
        
		<!-- styles -->
		<link href="../../dist/jquery.fileuploader.min.css" media="all" rel="stylesheet">
		<link href="./css/jquery.fileuploader-theme-avatar.css" media="all" rel="stylesheet">
		
		<!-- js -->
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
		<script src="../../dist/jquery.fileuploader.min.js" type="text/javascript"></script>
		<script src="./js/custom.js" type="text/javascript"></script>

		<style>
			body {
				font-family: 'Roboto', sans-serif;
				font-size: 14px;
                line-height: normal;
				background-color: #fff;

				margin: 0;
			}
			
			.fileuploader {
				width: 160px;
				height: 160px;
				margin: 15px;
			}
		</style>
	</head>

	<body>
		<?php
			include('../../src/php/class.fileuploader.php');
			
			$enabled = true;
			$default_avatar = 'images/default-avatar.png';
//			$avatar = array(
//				"name" => 'avatar.png',
//				"type" => FileUploader::mime_content_type('uploads/avatar.png'),
//				"size" => filesize('uploads/avatar.png'),
//				"file" => 'uploads/avatar.png',
//				"data" => array(
//					"readerForce" => true
//				)
//			);
		?>
		<input type="file" name="files" data-fileuploader-default="<?php echo $default_avatar;?>" data-fileuploader-files='<?php echo isset($avatar) ? json_encode(array($avatar)) : '';?>'<?php echo !$enabled ? ' disabled' : ''?>>
    </body>
</html>