<?php
    include('../../../../src/php/class.fileuploader.php');
    
    // define uploads path
    $uploadDir = '../uploads/';
    $thumbsDir = $uploadDir . 'thumbs/';

	// initialize FileUploader
    $FileUploader = new FileUploader('files', array(
		'limit' => null,
		'maxSize' => null,
		'extensions' => ['image/*'],
        'uploadDir' => $uploadDir,
        'title' => 'name',
		'editor' => array(
			'maxWidth' => 1280,
			'maxHeight' => 720,
			'crop' => false,
			'quality' => 90
		)
    ));

	// unlink the files
	// !important only for preloaded files
	// you will need to give the array with appendend files in 'files' option of the FileUploader
	foreach($FileUploader->getRemovedFiles('file') as $key=>$value) {
		$file = $uploadDir . $value['name']; 
        $thumb = $thumbsDir . $value['name'];
        
        if (is_file($file))
            unlink($file);
        if (is_file($thumb))
            unlink($thumb);
	}
	
	// call to upload the files
    $data = $FileUploader->upload();
    
    // if uploaded and success
    if ($data['isSuccess'] && count($data['files']) > 0) {
        // get uploaded files
        $uploadedFiles = $data['files'];
		
		// create thumbnails
        if (!is_dir($thumbsDir))
            mkdir($thumbsDir);
		foreach($uploadedFiles as $item) {
			FileUploader::resize($filename = $item['file'], $width = 64, $height = 64, $destination = $thumbsDir . $item['name'], $crop = false, $quality = 80);
		}
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