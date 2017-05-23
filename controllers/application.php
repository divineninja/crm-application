<?php
class Application extends Controller{

    function __construct() {
        parent::__construct();
    }
	
    function index(){
		// $this->view->revenue = $this->model->get_revenue();
		$current_date = date('Y-m-d', time());
		$this->view->current_date = $current_date;
        $this->view->to_date = date('Y-m-d', strtotime($current_date . ' + 1 month'));
        $this->view->render('application/index');
    }
	
	function revenue(){
		$from_date = $_GET['from_date'].' '.$_GET['from_time'].':00';
		$to_date = $_GET['to_date'].' '.$_GET['to_time'].':00';
		echo json_encode($this->model->get_user_application_by_datetime($from_date,$to_date));		
	}
	
	function get_user_application(){
		$from_date = $_GET['from_date'].' '.$_GET['from_time'].':00';
		$to_date = $_GET['to_date'].' '.$_GET['to_time'].':00';
		$id = $_GET['id'];
		$this->view->apps = $this->model->get_user_application_by_datetime_and_id($from_date,$to_date,$id);		
		$this->view->render('application/personal',true);
	}
	
}