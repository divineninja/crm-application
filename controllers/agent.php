<?php
class Agent extends Controller{

    public function __construct() {
        parent::__construct();
    }
	
    public function index(){
		$this->view->items = $this->model->get_objects();
        $this->view->render('agent/index');
    }

	public function register(){
		$this->view->render('agent/form/register', true);
	}

	public function save(){
		$this->model->insert_object($_POST);
	}
	
	public function monitoring(){
		$this->view->monitor = $this->model->get_monitoring_data();
		$this->view->render('agent/monitoring');
	}
	
	public function monitoringv2(){
		$this->view->monitor = $this->model->get_raw_application_by_dateV2();
		$this->view->render('agent/monitoring');
	}
	
	public function live_monitor(){
		
		if(isset($_GET['start_shift']) && isset($_GET['end_shift'])){			
			$from = str_replace('T',' ',$_GET['start_shift']);
			$to  = str_replace('T',' ',$_GET['end_shift']);
		}else{
			$from = date('Y-m-d 00:00', time());
			$to = date('Y-m-d 23:59', strtotime($from . ' + 1 day'));
		}
		
		if($_GET['source'] == 'agent/monitoringv2') {
			$this->view->monitor = $this->model->get_raw_application_by_dateV2($from,$to);
		
		} else {
			$this->view->monitor = $this->model->get_raw_application_by_date($from,$to);
			
		}
		
		$this->view->render('agent/live-monitoring',true);
	}
	
	public function session(){
		$this->view->current_date = date('Y-m-d h:i:s A', time());
		$this->view->end_date = date('Y-m-d h:i:s A', strtotime($this->view->current_date . ' + 1 day'));
		$this->view->render('agent/session',true);
	}

	public function edit_item( $id ){
		$this->view->item = $this->model->get_object_detail($id);
		$this->view->render('agent/form/edit_type', true);
	}

	public function update_object(){
		$this->model->update_object($_POST);
	}

	public function delete_item(){
		$ids = ( is_array( $_POST['ids'] ) ) ? $_POST['ids'] : die();
		$this->model->delete_object($ids);
	}

}