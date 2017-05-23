<?php	

class backup extends Controller {
	
	private $db;
	private $_start_date;
	private $_end_date;
	
	public function __construct(){
		parent::__construct();
		
	}
	
	public function index(){
		
		// $database = Session::get('campaign')->database;
		// $this->db = $this->model->connect_new_database($database);
		
		$this->view->query = $this->model->backup_answered_group('2015-03-01','2015-10-30');
		
		//if($this->db) {
			$this->view->render('backup/index');
		//}else {
			// $this->view->render('backup/error');
		//}
	}
	
	public function get_answered_group($page = 1)
	{
		$this->_start_date = $_GET['start_date'];
		$this->_end_date = $_GET['end_date'];
		
		$this->view->query = $this->model->backup_answered_group($this->_start_date,$this->_end_date,$page);
		
		echo 'prepare_result('.json_encode($this->view->query).')';
	}
	
	public function get_answered_question($id = 1)
	{
		$response = $this->model->get_answer_questions($id);
		
		echo 'parepare_questions('.json_encode($response).')';
	}
}