<?php

    include('../../../src/php/class.fileuploader.php');

    // define uploads path
    $uploadDir = '../uploads/';

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
        
        // skip if thumbnail
        if (substr($uploadDir . $file, 0, 6) == 'thumb_')
            continue;

		// add file to our array
		// !important please follow the structure below
		$preloadedFiles[] = array(
			"name" => $file,
			"type" => FileUploader::mime_content_type($uploadDir . $file),
			"size" => filesize($uploadDir . $file),
			"file" => $uploadDir . $file,
            "relative_file" => $uploadDir . $file
		);
	}

	// initialize FileUploader
    $FileUploader = new FileUploader('files', array(
		'limit' => null,
		'maxSize' => null,
		'extensions' => null,
        'uploadDir' => $uploadDir,
        'title' => 'name',
        'files' => $preloadedFiles
    ));

	// unlink the files
	// !important only for preloaded files
	// you will need to give the array with the preloaded files in 'files' option of the FileUploader
	foreach($FileUploader->getRemovedFiles('file') as $key=>$value) {
        $file = $uploadDir . $value['name']; 
        $thumb = $uploadDir . 'thumbs/' . $value['name'];
        
        if (is_file($file))
            unlink($file);
        if (is_file($thumb))
            unlink($thumb);
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