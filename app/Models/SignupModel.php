<?php

namespace App\Models;

use CodeIgniter\Model;

class SignupModel extends BaseModel
{
	
	function __construct()
	{
		parent::__construct();
		$this->users_model = \CodeIgniter\Config\Factories::models('UserModel');
		$this->login_model = \CodeIgniter\Config\Factories::models('LoginModel');
	}

	/**
	 * Function to register a user
	 * @return boolean
	 */
	public function add_user(): bool {
		extract($_POST);
		$salt = $this->users_model->salt(32);
		$password = $this->users_model->makePassword($password, $salt);		
		$query = $this->db->query("INSERT INTO `users`(firstname, lastname, email, `password`, salt) VALUES('$firstname', '$lastname', '$email', '$password', '$salt')");
		if($query) {
			// Automatically login the user
			$this->login_model->login_to_session($email); //create login sessions
			return true;
		}
		return false;
	}

}