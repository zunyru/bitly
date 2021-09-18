<?php
    include('../../../src/php/class.fileuploader.php');
	
	// initialize FileUploader
    $FileUploader = new FileUploader('files', array(
        'uploadDir' => '../uploads/',
        'title' => 'name'
    ));
	
	// call to upload the files
    $data = $FileUploader->upload();
	
    // print_r $data
	echo '<pre>';
	print_r($data);
	echo '</pre>';