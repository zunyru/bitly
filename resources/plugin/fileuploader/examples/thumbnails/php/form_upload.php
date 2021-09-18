<?php
    include('../../../src/php/class.fileuploader.php');
	
	// initialize FileUploader
    $FileUploader = new FileUploader('files', array(
        'limit' => null,
        'maxSize' => null,
		'fileMaxSize' => null,
        'extensions' => null,
        'required' => false,
        'uploadDir' => '../uploads/',
        'title' => 'name',
		'replace' => false,
		'editor' => array(
			'maxWidth' => 640,
			'maxHeight' => 480,
			'quality' => 90
		),
        'listInput' => true,
        'files' => null
    ));

	// unlink the files
	// !important only for preloaded files
	// you will need to give the array with appendend files in 'files' option of the fileUploader
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
        $warnings = $data['warnings'];
        
   		echo '<pre>';
        print_r($warnings);
		echo '</pre>';
    }
	
	// get the fileList
	$fileList = $FileUploader->getFileList();
	
	// show
	echo '<pre>';
	print_r($fileList);
	echo '</pre>';