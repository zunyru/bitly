<?php
    
    require '../../../../../src/thirdparty/s3/php/vendor/autoload.php';
    require '../../../../../src/php/class.fileuploader.php';
    require '../../../../../src/thirdparty/s3/php/fileuploader.s3.php';

    // initialize FileUploader
    $FileUploader = new FileUploader::$S3('files', array(
		'limit' => null,
		'maxSize' => null,
		'extensions' => null,
        'uploadDir' => '../uploads/',
        'title' => 'name',
        
        'auth' => (include('include.credentials.php'))
    ));

    // unlink the preloaded removed files
	foreach($FileUploader->getRemovedFiles() as $key=>$item) {
        if (isset($item['data']['key']))
            $FileUploader->deleteFile($item['data']['key']);
	}

	// call to upload the files
    $data = $FileUploader->upload();
    
    // if uploaded and success
    if($data['isSuccess']) {
        echo '<pre>';
        print_r($FileUploader->getFileList());
        echo '</pre>';
    }