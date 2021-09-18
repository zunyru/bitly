<?php
include_once('included_upload.php');

if (isset($Fileuploader)) {
    // unlink the files
	// !important only for preloaded files
	// you will need to give the array with appendend files in 'files' option of the fileUploader
	foreach($FileUploader->getRemovedFiles('file') as $key=>$value) {
		unlink('../uploads/' . $value['name']);
	}
	
	// call to upload the files
    $data = $FileUploader->upload();
	if($data['hasWarnings']) {
        $warnings = $data['warnings'];
        
   		echo '<pre>';
        print_r($warnings);
		echo '</pre>';
    }
    if (count($data['files']) > 0) {
        echo '<pre>';
        print_r($data['files']);
        echo '</pre>';
    }
}