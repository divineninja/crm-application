<?php
class user_Model extends Model {

    function __construct() {
        parent::__construct();
    }

    function login($params)
    {
        $params['password'] = $this->set_password( $params['password'] );
        $login = $this->login_user( $params );
        if( $login ){
            $_SESSION['logged_in'] = true;
            $_SESSION['role']      = $login->role;
            $_SESSION['user_data'] = $login;
            echo json_encode(array(
                'status' => 'ok',
                'code' => 200,
                'message' => 'successfully Logged In.',
                'details' => $login
            ));
            return;
        } else {
            echo json_encode(array(
                'status' => 'error',
                'code' => 600,
                'message' => 'Username and Password is not Found.'
            ));
            return;
        }
    }


    public function insert_object($data){
        if ($data['password'] != $data['vpassword']) {
            echo json_encode(array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Password did not matched.'
            ));
            return;
        } else {
            if ($data['password'] != null) {
                unset($data['vpassword']);
                $data['password'] = $this->set_password($data['password']);
                $this->insert('user', $data);
                $this->addActivity('added '.$data['first_name']. ' '.$data['last_name'].' as new '.$data['role'].'as new user in this account last');
                echo json_encode(array(
                    'status' => 'ok',
                    'code' => 200,
                    'message' => 'User Successfully Registered.'
                ));
            }
            
        }
        
    }


    public function get_object_detail($id)
    {
        return $this->selectSingle("SELECT * FROM user WHERE id = '$id'");

    }

    public function update_object($params)
    {

        // update user
        if ($params['password'] != $params['vpassword']) {
            echo json_encode(array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Password did not matched.'
            ));
            return;
        } else {
            if ($params['password'] != null) {
                $params['password'] = $this->set_password($params['password']);
            } else {
                unset($params['password']);
            }
            unset($params['vpassword']);
            
            $this->update('user', $params, "id={$params['id']}");
            $this->addActivity('updated user '.$params['first_name']. ' '.$params['last_name'].'('.$params['role'].') last');

            echo json_encode(array(
                'status' => 'ok',
                'code' => 200,
                'message' => 'User Successfully Updated.'
            ));
        }
    }
    
    public function get_objects(){
        return $this->select("SELECT * FROM user");
    }


    public function delete_object($ids = array() ){
        foreach($ids as $id){
        $this->delete( 'user', "id = $id" );
        }
    }
    
    
    /**Start site meta**/
    public function get_site_meta(){
        return $this->select("SELECT * FROM site");
    }
    
    public function delete_site_meta($ids = array() ){
        foreach($ids as $id){
        $this->delete( 'site', "id = $id" );
        }
    }
    
    public function update_site_meta($params){
        $this->update('site', $params, "id={$params['id']}");
        echo json_encode(array(
                'status' => 'ok',
                'code' => 200,
                'message' => 'User Successfully Updated.'
            ));
    }
        
    public function get_site_meta_details($id){
        return $this->selectSingle("SELECT * FROM site WHERE id = '$id'");
    }
    
    public function insert_site_meta($data)
    {
        $this->insert('site',$data);
        echo json_encode(array(
            'status' => 'ok',
            'code' => 200,
            'message' => 'User Successfully Registered.'
        ));
    }

    public function check_if_existing($title)
    {
        $sql = $this->selectSingle("SELECT value FROM site WHERE title = '$title'");
        return ($sql)? true: false;
    }
    
    
    public function save_metas( $data )
    {
        // configure parameter cdn url serialize multiple data
        $data['cdn_url'] = serialize(array_filter($data['cdn_url']));

        // proceed to normal process of saving.
        foreach ($data as $key => $item) {
            if ($this->check_if_existing($key)) {
                $this->update(
                    'site',
                    array(
                     'title' => $key,
                     'value' => $item
                    ),
                    "title = '$key'"
                );
            } else {
                 $this->insert('site', 
                    array(
                     'title' => $key,
                     'value' => $item
                    )
                 );
            }
            
        }
        
        echo json_encode(array(
            'status' => 'ok',
            'code' => 200,
            'message' => 'User Successfully Registered.',
            'redirect' => false
        ));
    }
    
    /***End site meta**/
}
