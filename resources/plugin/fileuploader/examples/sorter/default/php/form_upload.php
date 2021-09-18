<?php
    include('../../../../src/php/class.fileuploader.php');
	
	// initialize FileUploader
    $FileUploader = new FileUploader('files', array(
		'limit' => null,
		'maxSize' => null,
		'extensions' => null,
        'uploadDir' => '../uploads/',
        'title' => 'name'
    ));

	// unlink the files
	// !important only for preloaded files
	// you will need to give the array with appendend files in 'files' option of the FileUploader
	foreach($FileUploader->getRemovedFiles('file') as $key=>$value) {
		unlink('../uploads/' . $value['name']);
	}
	
	// call to upload the files
    $data = $FileUploader->upload();
    
    // if uploaded and success
    if($data['isSuccess'] && count($data['files']) > 0) {
        // get uploaded files
        $uploadedFiles = $data['files'];
    }
    // if warnings
	if($data['hasWarnings']) {
        // get warnings
        $warnings = $data['warnings'];
        
   		echo '<pre>';
        print_r($warnings);
		echo '</pre>';
        exit;
    }
	
	// get the fileList
	$fileList = $FileUploader->getFileList();
	
	// show
	echo '<pre>';
	print_r($fileList);
	echo '</pre>';