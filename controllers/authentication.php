<?php

class authentication extends Controller {
	
	var $user_url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=';

	public function __construct(){
		parent::__construct();
		$this->require_item();
	}
	
	public function login(){
		if (isset($_GET['code'])) {
			$this->client->authenticate();
			$user_data =  json_decode($this->client->getAccessToken());
			$_SESSION['token'] = $user_data;				
			header('Location: http://' . $_SERVER['HTTP_HOST'] . '/authentication/verify');
		}else if( isset($_GET['error'])){
			header('Location: '.URL);
		}
	}

	public function verify(){

		$user_detail = $this->file_get_data( $this->user_url.$_SESSION['token']->access_token );

		$user = json_decode($user_detail);
		
		if($user){			
			$data = array(
			 'google_id' => $user->id,
			 'email' => $user->email,
			 'picture' => $user->picture,
			 'display_name' => $user->name,
			 'first_name' => $user->given_name,
			 'last_name' => $user->family_name,
			 'link' => $user->link,
			 'gender' => $user->gender,
			 'role' => 2,
			 'username' => $user->email,
			 'password' => $this->model->set_password( $user->email )
			);

			$this->model->insert_google_user($data);
		}

	}

	public function require_item(){
		require('src/Google_Client.php');
		$this->client = new Google_Client();
		$this->client->setClientId('174495117957-68rdrgkac326kfaqskr3cpbu8uge5cg5.apps.googleusercontent.com'); // client ID
		$this->client->setClientSecret('hENmey9fk9bVbo2m_tyVxpM8'); // client Secret
		$this->client->setRedirectUri('http://asset.nextopics.com/authentication/login/'); // redirect URI
		$this->client->setDeveloperKey('AIzaSyCi1bUPRiqIwi9zCHZeNDXqMCLMXm6sMdw'); // API KEY
	}
	

    public function file_get_data($url) {
        $curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$url);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
		$buffer = curl_exec($curl_handle);
		curl_close($curl_handle);

		return $buffer;
    }
}