<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Files extends CI_Controller {
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * http://example.com/index.php/welcome
	 * - or -
	 * http://example.com/index.php/welcome/index
	 * - or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 *
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
		echo "HI";
	}
	public function read($uid) {
		$path = "/var/www/html/aeoalbum/api/uploads/" . $uid;
		
		$files = scandir ( $path );
		$files = array_diff ( scandir ( $path ), array (
				'.',
				'..' 
		) );
		echo JSON_ENCODE ( $files );
		return;
	}
	public function upload() {
		$stream_clean = $this->security->xss_clean ( $this->input->raw_input_stream );
		$request = json_decode ( $stream_clean );
		echo ($request);
		$currentDir = getcwd ();
		$uploadDirectory = "/uploads/" . $_POST ['folder'] . '/';
		
		if (! file_exists ( $currentDir . $uploadDirectory )) {
			mkdir ( $currentDir . $uploadDirectory, 0777, true );
		}
		
		$path = $currentDir . $uploadDirectory;
		
		$errors = [ ]; // Store all foreseen and unforseen errors here
		
		$fileExtensions = [ 
				'jpeg',
				'jpg',
				'png' 
		]; // Get all the file extensions
		
		$fileName = $_FILES ['myfile'] ['name'];
		$fileSize = $_FILES ['myfile'] ['size'];
		$fileTmpName = $_FILES ['myfile'] ['tmp_name'];
		$fileType = $_FILES ['myfile'] ['type'];
		$fileExtension = strtolower ( end ( explode ( '.', $fileName ) ) );
		
		$uploadPath = $currentDir . $uploadDirectory . basename ( $fileName );
		
		echo $uploadPath;
		
		if (isset ( $fileName )) {
			
			if (! in_array ( $fileExtension, $fileExtensions )) {
				$errors [] = "This file extension is not allowed. Please upload a JPEG or PNG file";
			}
			
			if ($fileSize > 2000000) {
				$errors [] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
			}
			
			if (empty ( $errors )) {
				$didUpload = move_uploaded_file ( $fileTmpName, $uploadPath );
				
				if ($didUpload) {
					echo "The file " . basename ( $fileName ) . " has been uploaded";
				} else {
					echo "An error occurred somewhere. Try again or contact the admin";
				}
			} else {
				foreach ( $errors as $error ) {
					echo $error . "These are the errors" . "\n";
				}
			}
		}
	}
	
	
}
