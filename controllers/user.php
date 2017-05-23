<?php

class user extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        if ( !isset( $_SESSION['logged_in'] ) ) {
            $this->model->login($_POST);
        } else {
             header( 'LOCATION: '. URL );
        }
    }

    public function logout()
    {
        session_destroy();
        header( 'LOCATION: '. URL.'../main-crm' );
    }

    public function show_login_form()
    {
        $this->view->render('administrator/login', true);
    }

    public function index()
    {
        $this->view->items = $this->model->get_objects();
        $this->view->render('user/index');
    }

    public function register()
    {
        $this->view->render('user/form/register', true);
    }

    public function save()
    {
        $this->model->insert_object($_POST);
    }

    public function edit_item($id)
    {
        $this->view->item = $this->model->get_object_detail($id);
        $this->view->render('user/form/edit_type', true);
    }

    public function update_object()
    {
        $this->model->update_object($_POST);
    }

    public function delete_item()
    {
        $ids = ( is_array( $_POST['ids'] ) ) ? $_POST['ids'] : die();
        $this->model->delete_object($ids);
    }

    /***Account***/
    public function account()
    {
        $this->view->items = $this->model->get_site_meta();
        $this->view->render('user/account');
    }

    public function update_site()
    {
        $this->model->update_site_meta($_POST);
    }

    public function delete_site()
    {
        $ids = ( is_array( $_POST['ids'] ) ) ? $_POST['ids'] : die();
        $this->model->delete_site_meta($ids);
    }

    public function save_site()
    {
        $this->model->insert_site_meta($_POST);
    }

    public function register_site()
    {
        $this->view->render('user/account/register', true);
    }

    public function edit_site($id)
    {
        $this->view->item = $this->model->get_site_meta_details($id);
        $this->view->render('user/account/edit_type', true);
    }
    
    public function configure_account()
    {
        $this->view->render('user/configure');
    }
    
    public function account_save()
    {
        $this->model->save_metas($_POST);
    }
    /***Account***/
}
