<?php
class Choices extends Controller{

    public function __construct()
    {
        parent::__construct();
    }
    
    public function index($page = 1)
    {
		$search = isset($_GET['key']) ? $_GET['key']: null;
        $this->view->items = $this->model->get_objects($page,$search);
        $this->view->render('choices/index');
    }

    public function register()
    {
        $this->view->render('choices/form/register', true);
    }

    public function save()
    {
        $this->model->insert_object($_POST);
    }
    
    public function bulk()
    {
        $this->view->items = $this->model->get_objects();
        $this->view->render('choices/form/bulk', true);
    }
    public function bulk_upload()
    {
        $this->model->insert_bulk_upload($_POST);
    }

    public function edit_item($id)
    {
        $this->view->item = $this->model->get_object_detail($id);
        $this->view->render('choices/form/edit_type', true);
    }

    public function update_object()
    {
        $this->model->update_object($_POST);
    }

    public function delete_item()
    {
        $ids = (is_array($_POST['ids'])) ? $_POST['ids'] : die();
        $this->model->delete_object($ids);
    }
}
