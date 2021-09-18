<?php
if (isset($_POST['file'])) {
    $file = '../uploads/' . str_replace(array('/', '\\'), '', $_POST['file']);
	
    if(file_exists($file))
		unlink($file);
}