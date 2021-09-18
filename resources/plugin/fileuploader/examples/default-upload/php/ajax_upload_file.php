<?php
    include('../../../src/php/class.fileuploader.php');
	
	// get custom name field
	$customName = isset($_POST['custom_name']) && !empty($_POST['custom_name']) ? $_POST['custom_name'] : null;
	
	// initialize FileUploader
    $FileUploader = new FileUploader('files', array(
        'limit' => 1,
        'maxSize' => null,
		'fileMaxSize' => null,
        'extensions' => null,
        'required' => false,
        'uploadDir' => '../uploads/',
        'title' => $customName ? $customName : 'name',
		'replace' => false,
        'listInput' => true,
        'files' => null
    ));
	
	// call to upload the files
    $data = $FileUploader->upload();

	// change file's public data
    if (!empty($data['files'])) {
        $item = $data['files'][0];
        
        $data['files'][0] = array(
            'title' => $item['title'],
            'name' => $item['name'],
            'size' => $item['size'],
            'size2' => $item['size2']
        );
    }

	// export to js
    header('Content-Type: application/json');
	echo json_encode($data);
	exit;