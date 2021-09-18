<?php
    chdir(dirname(__FILE__));
    include('../../../src/php/class.fileuploader.php');

	// create an empty array
	$preloadedFiles = array();

	// scan uploads directory
	$uploadsFiles = array_diff(scandir('../uploads/'), array('.', '..'));

	// add files to our array with
	// made to use the correct structure of a file
	foreach($uploadsFiles as $file) {
		// skip if directory
		if(is_dir($file))
			continue;
        
        // skip "." files
        if (strpos($file, '.') === 0)
            continue;

		// add file to our array
		// !important please follow the structure below
		$preloadedFiles[] = array(
			"name" => $file,
			"type" => FileUploader::mime_content_type('../uploads/' . $file),
			"size" => filesize('../uploads/' . $file),
			"file" => 'uploads/' . $file,
			"data" => array(
				"url" => 'http://localhost/fileuploader/examples/php-generated-input/uploads/' . $file
			)
		);
	}
	
	// initialize FileUploader
    $FileUploader = new FileUploader('files', array(
        'limit' => 4,
        'maxSize' => 4,
		'fileMaxSize' => 4,
        'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'bmp'],
        'required' => false,
        'uploadDir' => '../uploads/',
        'title' => '{random}',
		'replace' => false,
        'listInput' => true,
        'files' => $preloadedFiles
    ));