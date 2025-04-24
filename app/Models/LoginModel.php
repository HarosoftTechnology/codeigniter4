<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends BaseModel
{
	protected $users_model;

	function __construct()
    {
        parent::__construct();
        // Initialize the property using CodeIgniter's Factories class to load the UserModel
        $this->users_model = \CodeIgniter\Config\Factories::models('UserModel');
    }

	/**
	 * function to verify login credentials and login the user if successful
	 * @param string $username The username
	 * @param string $password The password
	 * @return boolean
	 */
	public function login(string $username, string $password, bool $remember = false): bool {
		$user = $this->users_model->userdata($username);
		if(!$user) return false;
		
		$makePassword = $this->users_model->makePassword($password, $user->salt);		
		$query = $this->db->query("SELECT id, email, `password` FROM users WHERE email='$username' AND `password` = '$makePassword' ");
		if($query->getNumRows() == 1) {
			$result = $query->getRow();
			$this->session->set("sv_loggin_username", $result->email);
			$this->session->set("sv_loggin_password", $result->password);
			$this->login_to_session($username); //create login sessions
			return true;
		} 
		return false;
	}

	/**
	 * This function put login in session
	 * @param string $email
	 */
	function login_to_session(string $email): void
	{
		$userdata = $this->users_model->userdata($email);
		$this->session->set([
			'username'  => $userdata->email,
			'userid'  	=> $userdata->id,
			'timestamp' => time(),
			'email'		=> $userdata->email,
			'logged_in' => TRUE,
		]);
	}

}