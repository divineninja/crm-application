<?php

class Index extends Controller
{
   
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->model->check_database()) {
            header("Location: setup");
        }
        $this->view->updateButton = $this->model->enableUpdateTable();
        $this->view->setupNotif = $this->model->checkNotifTable();

        if ($this->check_user()) {
            $this->view->render('index/index');
        } else {
            $this->view->render('index/import');
        }
    }

    public function check_user()
    {
        $user = $this->model->select("SELECT * FROM user LIMIT 1");
        if ($user) {
            return true;
        } else {
            return false;
        }
    }
    
}
