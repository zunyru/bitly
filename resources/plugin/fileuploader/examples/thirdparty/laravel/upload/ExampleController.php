<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \FileUploader;

class ExampleController extends Controller {
	
	/**
     * show the form
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	public function index() {
		return view('home');
	}
	
	/**
     * submit the form
     *
     * @return void
     */
	public function submit(Request $request) {
		$field = 'files';
		$uploadDir = '';
		
		// initialize FileUploader
		$FileUploader = new FileUploader($field, array(
			'limit' => 100,
			'fileMaxSize' => 100,
			'extensions' => null,
			'uploadDir' => storage_path('app/public/') . $uploadDir,
			'title' => 'auto'
		));
		
		// upload
		$upload = $FileUploader->upload();
		if ($upload['isSuccess']) {
			foreach($upload['files'] as $key=>$item) {
				$upload['files'][$key] = array(
					'extension' => $item['extension'],
					'format' => $item['format'],
					'file' => 'storage/' . $uploadDir . $item['name'],
					'name' => $item['name'],
					'size' => $item['size'],
					'size2' => $item['size2'],
					'title' => $item['title'],
					'type' => $item['type'],
					'url' => asset('storage/' . $uploadDir . $item['name'])
				);
			}
		}
		
		echo json_encode($upload);
		exit;
	}
	
	/**
     * delete a file
     *
     * @return void
     */
	public function removeFile(Request $request) {
		if (isset($_POST['file'])) {
			$uploadDir = '';
			$file = storage_path('app/public/') . $uploadDir . str_replace(array('/', '\\'), '', $_POST['file']);

			if(file_exists($file))
				unlink($file);
		}
		exit;
	}
}