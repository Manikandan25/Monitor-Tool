<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Users extends CI_Controller {
	
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
		$this->load->view ( 'welcome_message' );
		$this->load->model ( 'usersmodel' );
		echo $this->usersmodel->login ( "m", "1" );
		
		echo $this->usersmodel->createUser ( "1", "1" );
	}
	public function login() {
		$stream_clean = $this->security->xss_clean ( $this->input->raw_input_stream );
		$request = json_decode ( $stream_clean );
		$userName = ($request->userName);
		$password = ($request->password);
		$this->load->model ( 'usersmodel' );
		$token = $this->usersmodel->login ( $userName, $password );
		if ($token == null) {
			echo null;
			return;
		}
		
		$arr = array (
				'token' => $token,
				'status' => 200,
				'userId' => md5 ( $request->userName ) 
		);
		$this->createMasterClientConfig ( md5 ( $request->userName ) );
		echo json_encode ( $arr );
	}
	public function createUser() {
		$stream_clean = $this->security->xss_clean ( $this->input->raw_input_stream );
		$request = json_decode ( $stream_clean );
		$userName = ($request->userName);
		$password = ($request->password);
		$this->load->model ( 'usersmodel' );
		$token = $this->usersmodel->login ( $userName, $password );
		if ($token == null) {
			$userId = $this->usersmodel->createUser ( $userName, $password );
			if ($userId != null) {
				$data ['status'] = 200;
				$data ['message'] = "SUCCESS";
				$data ['configFile'] = $this->createMasterClientConfig ( md5 ( $userName ) );
				echo json_encode ( $data );
			} else {
				echo "FAIL";
			}
		} else {
			echo "USER EXIST";
		}
	}
	public function createMasterClientConfig($apiKey) {
		$filename = "masterFiles/common.config";
		$clientFile = "clientConfig/" . $apiKey . ".conf";
		if (! file_exists ( $clientFile )) {
			$contents = file_get_contents ( $filename );
			$contents = str_replace ( "whirldata-telegraf", $apiKey, $contents );
			$fp1 = fopen ( "clientConfig/" . $apiKey . ".conf", 'a+' );
			fwrite ( $fp1, $contents );
		}
		return $filename;
	}
}
