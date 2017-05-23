<?php

class question_stat extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
    	$this->view->render('error/index');
    }

    public function app()
    {
    	$apps = $this->model->get_all_app();
    	echo json_encode($apps);
    }
}