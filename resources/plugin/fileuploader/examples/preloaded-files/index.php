<!DOCTYPE html>
<html lang="en">

	<head>
		<title>Preloaded files example - fileuploader - Innostudio.de</title>
		
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Preloaded files example - fileuploader - Innostudio.de">
        
        <link rel="shortcut icon" href="https://innostudio.de/fileuploader/images/favicon.ico">

		<!-- fonts -->
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
        <link href="../../dist/font/font-fileuploader.css" rel="stylesheet">
		
		<!-- styles -->
		<link href="../../dist/jquery.fileuploader.min.css" media="all" rel="stylesheet">
		
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
				padding: 20px;
			}
            
            form {
                margin: 15px;
            }
            
            .fileuploader {
                max-width: 560px;
            }
		</style>
	</head>

	<body>
		<form action="php/form_upload.php" method="post" enctype="multipart/form-data">
            <!-- file input -->
            <?php
				// we are inclunding it only for using FileUploader::mime_content_type method
				include('../../src/php/class.fileuploader.php');
            
                // define uploads path
                $uploadDir = 'uploads/';
				
				// create an empty array
                // we will add to this array the files from directory below
                // here you can also add files from MySQL database
				$preloadedFiles = array();
			
				// scan uploads directory
				$uploadsFiles = array_diff(scandir($uploadDir), array('.', '..'));
			
				// add files to our array with
				// made to use the correct structure of a file
				foreach($uploadsFiles as $file) {
					// skip if directory
					if(is_dir($uploadDir . $file))
						continue;
					
					// add file to our array
					// !important please follow the structure below
					$preloadedFiles[] = array(
						"name" => $file,
						"type" => FileUploader::mime_content_type($uploadDir . $file),
						"size" => filesize($uploadDir . $file),
						"file" => $uploadDir . $file,
						"local" => '../' . $uploadDir . $file, // same as in form_upload.php
						"data" => array(
							"url" => '/fileuploader/examples/preloaded-files/uploads/' . $file, // (optional)
                            "thumbnail" => file_exists($uploadDir . 'thumbs/' . $file) ? $uploadDir . 'thumbs/' . $file : null, // (optional)
							"readerForce" => true // (optional) prevent browser cache
						),
					);
				}
				
				// convert our array into json string
				$preloadedFiles = json_encode($preloadedFiles);
			?>
			<input type="file" name="files" data-fileuploader-files='<?php echo $preloadedFiles;?>'>
            
			<input type="submit">
		</form>
    </body>
</html>