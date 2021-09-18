<?php
	include('../../../../src/php/class.fileuploader.php');

	if (isset($_POST['fileuploader']) && isset($_POST['_file']) && isset($_POST['_editor'])) {
		$file = '../' . $_POST['_file'];
		
		if (is_file($file)) {
			$editor = json_decode($_POST['_editor'], true);

			Fileuploader::resize($file, null, null, null, (isset($editor['crop']) ? $editor['crop'] : null), 100, (isset($editor['rotation']) ? $editor['rotation'] : null));
		}
	}