<?php

class authentication_Model extends Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function insert_google_user($user){

		$params = array( 'username' => $user['username'], 'password' => $user['password'] );

		$user_login = $this->login_user( $params ); 

		if( !$user_login ){
			$this->insert('users', $user);	
			// insert and register session			
				$_SESSION['logged_in'] = true;
				$_SESSION['role'] = 2;
				unset($user['passowrd']);
				$_SESSION['user_data'] = (object)$user;			
		}else{
			// register to session
			$_SESSION['logged_in'] = true;
			$_SESSION['role'] = $user_login->role;
			unset($user_login->passowrd);
			$_SESSION['user_data'] = $user_login;
		}
		
		header('Location: http://' . $_SERVER['HTTP_HOST'] . '/asset');
	}

}