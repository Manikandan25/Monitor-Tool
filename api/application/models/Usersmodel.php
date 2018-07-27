<?php
class Usersmodel extends CI_Model {
	public function __construct() {
		parent::__construct ();
		$this->load->database ();
		date_default_timezone_set ( 'Africa/Lagos' );
	}
	public function login($email, $password) {
		$where = "email='" . $email . "' AND password = '" . md5 ( $password ) . "'";
		$this->db->where ( $where );
		$query = $this->db->get ( 'users' );
		if ($query->result ()) {
			return $this->generateToken ( $email );
		} else {
			return null;
		}
	}
	public function generateToken($email) {
		$token = md5 ( (new \DateTime ())->format ( 'Y-m-d H:i:s' ) . $email );
		$data = array (
				'userId' => $email,
				'token' => $token 
		);
		
		$this->db->insert ( 'token', $data );
		return $token;
	}
	public function createUser($email, $password) {
		$this->db->set ('email',$email);
		$this->db->set ('password',md5($password));
		$this->db->set ('isEnabled',TRUE);
		$this->db->insert ( 'users' );
		$userId = $this->db->insert_id ();
		return $userId;
	}
}
?>
