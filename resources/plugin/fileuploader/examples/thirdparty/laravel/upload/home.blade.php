<!DOCTYPE html>
<html lang="en">

	<head>
		<title>Laravel upload example - fileuploader - Innostudio.de</title>
		
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Default example - fileuploader - Innostudio.de">
        
        <link rel="shortcut icon" href="https://innostudio.de/fileuploader/images/favicon.ico">

		<!-- fonts -->
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
        <link href="./css/font/font-fileuploader.css" rel="stylesheet">
        
		<!-- styles -->
		<link href="./css/jquery.fileuploader.min.css" media="all" rel="stylesheet">
		<link href="./css/jquery.fileuploader.min.css" media="all" rel="stylesheet">
		<link href="./css/drag-drop/css/jquery.fileuploader-theme-dragdrop.css" media="all" rel="stylesheet">
		
		<!-- js -->
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
		<script src="./js/jquery.fileuploader.min.js" type="text/javascript"></script>
		<script src="./js/custom.js" type="text/javascript"></script>

		<style>
			body {
				font-family: 'Roboto', sans-serif;
				font-size: 14px;
                line-height: normal;
				background-color: #fff;

				margin: 0;
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
		<input type="file" name="files" data-upload-url="{{ route('example.upload') }}" data-upload-token="{{ csrf_token() }}">
    </body>
</html>