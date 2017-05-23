<?php
class Questions extends Controller{

    function __construct() {
        parent::__construct();
    }
	
    function index($page = 1){
		$this->view->items = $this->model->get_objects($page);
	
        $this->view->render('questions/index_ajax');
    }	
	
	function get($page = 1){
		
		$search = isset($_GET['key']) ? $_GET['key']: null;
		$status = isset($_GET['status']) ? $_GET['status']: null;
		
		$this->view->items = $this->model->get_objects($page,$search,$status);
	
        $this->view->render('questions/index_table_only', true);
    }	
	
	
    function edit($id){
		$this->view->item = $this->model->get_object_detail($id);
		$choices = $this->model->get_choices();
		$this->view->my_choices = array();
		
		foreach( $choices as $choice ) {
			$this->view->choices[$choice->choices_id] = $choice;
		}
		
		// my choices
		$my_choices = unserialize($this->view->item->choices);
		$choice = array();
		
		foreach( $my_choices as $myC ) {
			if( isset($this->view->choices[$myC]) ) {
				$choice = $this->view->choices[$myC];
				$this->view->my_choices[$choice->choices_id] = $choice->label;	
			}
		}
		
		$this->view->questions = $this->model->get_objects_by_id($id);
		$this->view->groups = $this->model->get_groups();
		$this->view->conditional_answer = $this->model->get_choices_by_id($this->view->item->conditional_answer);
		$this->view->parent_conditions = $this->model->get_question_condition($id);
		$choice_selected = unserialize($this->view->item->choices);
		$this->choice_selected = array();
		
		foreach($choice_selected as $choice){
			$this->choice_selected[] = $this->model->get_choice_by($choice);
		}
		
		$this->view->choice_selected = (object)$this->choice_selected;
		$this->view->render('questions/form/edit');
    }
	
	public function search_choices()
	{
		$key = $_GET['value'];
		
		$choices = $this->model->select("SELECT * FROM choices WHERE label LIKE '%{$key}%' GROUP BY label");
		
		echo json_encode($choices);
		
	}
	
	function save_order(){
		$list = count($_POST['id']);
		for($i = 0;$i < $list; $i++){
			$params = array(
				'id' => $_POST['id'][$i],
				'order' => $_POST['order'][$i]
			);
			$this->model->update_object_order($params);
		}
    }	

	public function register(){
		$this->view->choices = $this->model->get_choices();
		$this->view->questions = $this->model->get_objects_V1();
		$this->view->groups = $this->model->get_groups();
		$this->view->render('questions/form/register', true);
	}

	public function save(){
		$this->model->insert_object($_POST);
	}

	public function edit_item( $id ){
		$this->view->item = $this->model->get_object_detail($id);
		$this->view->choices = $this->model->get_choices();
		$this->view->questions = $this->model->get_objects_by_id($id);
		$this->view->groups = $this->model->get_groups();
		$this->view->conditional_answer = $this->model->get_choices_by_id($this->view->item->conditional_answer);
		$this->view->parent_conditions = $this->model->get_question_condition($id);
		$choice_selected = unserialize($this->view->item->choices);
		$this->choice_selected = array();
		
		if($choice_selected) {			
			foreach($choice_selected as $choice){
				$this->choice_selected[] = $this->model->get_choice_by($choice);
			}
		}
		$this->view->choice_selected = (object)$this->choice_selected;
		$this->view->render('questions/form/edit_type', true);
	}

	public function update_object(){
		$this->model->update_object($_POST);
		
		 echo json_encode(
            array(
             'status' => 'ok',
             'code' => 200,
             'message' => 'Question Successfully Updated.'
            )
        );
	}

	public function update_question(){
		$this->model->update_object($_POST);
		
		header('Location:' . URL.'questions/edit/'. $_POST['id']);
	}

	public function delete_item(){
		$ids = ( is_array( $_POST['ids'] ) ) ? $_POST['ids'] : die();
		$this->model->delete_object($ids);
	}
	
	public function configure($id){
		$this->view->id = $id;
		$this->view->question = $this->model->get_object_detail($id);
		$this->view->valid_questions = $this->model->get_question_by_group('1', $id);
		$this->view->referene_questions = $this->model->get_reference_question($id);
		$this->view->postal = $this->model->postal_codes(0);
		$this->view->post_codes = $this->model->get_postal_codes($id);
		
		$collection = array();
		
		foreach($this->view->valid_questions as $key => $value):
			$collection[$value->code] = $value;
		endforeach;
		$this->view->valid_questions = $collection;
		
		$this->view->render('questions/form/configure');
	}
	
	public function insert_survey_configuration(){
		$this->model->insert_reference_questions($_POST);
	}
	
	public function get_choices_by($id){
		$question = $this->model->get_object_detail($id);
		$choice_selected = unserialize($question->choices);
		$choices = array();
		foreach($choice_selected as $choice){
			$choices[] = $this->model->get_choice_by($choice);
		}
		echo json_encode($choices);
	}
	
	
	function remove_follow_up_question_validation($id){
		$status = $this->model->delete('follow_up_question_validation', "id = '$id'");
		if($status){
			echo json_encode(
			 array(
				'status' => 'ok',
				'code' => 200,
				'message' => 'Successfully Deleted Item.'
			) );
		}else{
			echo json_encode( array(
				'status' => 'error',
				'code' => 400,
				'message' => 'Something went wrong try again later.'
			) );
		}
	}
	
	public function remove_condition($id){
		$this->model->delete('logic_follow_up_questions', "id = '$id'");
		echo json_encode(array(
			'status' => 'ok',
			'code' => 200,
			'message' => 'Successfully deleted item.'
		));
	}

	public function supression($id)
	{	
		$this->view->question_id = $id;
		$this->view->render('questions/form/supression', true);
	}
	
	public function saveSupression()
	{
		// print_r($_FILES);
		// move uploaded file to sever filesystem
		$filename = $_FILES['supression_file']['tmp_name'];
		$file_destination = str_replace(' ', '_', $_FILES['supression_file']['name']);
		$destination = dirname(dirname(__FILE__)).'/uploads/'.$file_destination;

		move_uploaded_file($filename, $destination);

		// move to second page supression
		$question_id = $_POST['question_id'];
		$supressionURI = base64_encode($destination);
		$url = $this->view->set_url("questions/insertSupression/$supressionURI?id=$question_id");

		header("location: $url");
	}

	public function deleteSuppression($id)
	{
		$this->model->deleteSupression($id);
		Session::set('notice','true');
		Session::set('message',"All supression records are successfully removed.");
		header("location: ".$this->view->set_url('questions'));		
	}

	public function insertSupression($supressionDetails)
	{
		$supressionURI = base64_decode($supressionDetails);

		// open supression file.
		$supression = file_get_contents($supressionURI);

		$ex_suppression = explode("\n", $supression);
		
		// pagination code for saving content
		$count = count($ex_suppression);
		$this->show_per_page = 10000;
		$pages = ceil($count/$this->show_per_page);

		if($count >= 1000000) {
			Session::set('notice','true');
			Session::set('message',"<h3>Error! File Upload Limit</h3> Your uploaded file exceeded the limit of 1 million (1000000) phone numbers per upload.");
			header("location: ".$this->view->set_url('questions'));
			exit();
		}

		// before saving new content we will remove all stored data in bind in the question id.
		$question = $_GET['id'];

		if(!$pages) $pages = 1;
		

		for ($i=1; $i <= $pages; $i++) { 
			$item = $this->paganation($ex_suppression, $i);
			$value_statement = array();
			foreach ($item as $phone) {
				$value_statement[] = "($question, $phone)";
			}

			$value_statement = implode(',', $value_statement);
			$insert_query = "INSERT INTO supression(question_id, phone) VALUES $value_statement";

			$this->model->execQuery($insert_query);
		}

		Session::set('notice','true');
		Session::set('message',"<strong>$count</strong> phone numbers successfully inserted to database.");
		header("location: ".$this->view->set_url('questions'));
	}

	public function paganation($display_array, $page) {
       $show_per_page = $this->show_per_page;
       
       $page = $page < 1 ? 1 : $page;

       // start position in the $display_array
       // +1 is to account for total values.
       $start = ($page - 1) * ($show_per_page);
       $offset = $show_per_page;

       $outArray = array_slice($display_array, $start, $offset);
       
       return $outArray;
    }
}