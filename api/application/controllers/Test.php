<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
class Test extends CI_Controller {
	public function me() {
		echo "This is me";
	}
	public function us($id, $name) {
		$data ['id'] = $id;
		$data ['name'] = $name;
		echo json_encode ( $data );
	}
}