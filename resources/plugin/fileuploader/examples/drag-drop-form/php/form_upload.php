<?php
    include('../../../src/php/class.fileuploader.php');
	
	// initialize FileUploader
    $fileInput = 'files';
    $FileUploader = new FileUploader($fileInput, array(
        'uploadDir' => '../uploads/',
        'title' => 'auto'
    ));
	
	// call to upload the files
    $data = $FileUploader->upload();

	// export new values to js
	echo json_encode(array(
        $fileInput => $data
    ));
	exit;