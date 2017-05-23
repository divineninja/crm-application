<?php

class Database extends PDO
{
 
    public function __construct()
    {
		if (DB_USER == '') {
            include 'views/error/authentication.php';
            exit;
        }
		
        try {
            parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
        } catch (Exception $e) {
            include 'views/error/authentication.php';
            exit;
        }
    }

    public function set_url($uri = '')
    {
        return URL.$uri;
    }
    
    public function set_password($string = '')
    {
        return md5(md5($string));
    }
}