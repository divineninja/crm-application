<?php
class survey extends Controller{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        date_default_timezone_set('Asia/Hong_Kong');
		
        $this->view->current_date = date('Y-m-d', time());
        $this->view->current_time = date('H:i', time());
        $this->view->agents = $this->model->get_agents();

        if(!isset($_SESSION['agent_account'])) {
            $this->view->render('survey/set_agent', true);
        }else {
            $this->view->agent_id = $_SESSION['agent_account']['id'];
            $this->view->agent_name = $_SESSION['agent_account']['name'];
			
			$agent = $this->model->check_agent_login_status($this->view->agent_id);
			
			if($agent->status == 0)
			{
				unset($_SESSION['agent_account']);
				$message = base64_encode('You are logged out by the Administrator.');
				header('location: '. $this->view->set_url("survey?message={$message}"));
				die();
			}
        }
		
        $this->view->responses = $this->model->getAllCurrentResponses();

        $this->view->questions = $this->model->get_questions(1);
        $this->view->render('survey/index');
        
        if (isset($_GET['phone'])) {
            $this->view->phone = $_GET['phone'];
            $this->view->render('survey/script', true);
        }
    }

    public function set_agent()
    {
        $agent = explode('|', $_POST['agent_id']);

		$check_status = $this->model->check_agent_login_status($agent[0]);
		
		if($check_status->status == 0) {
			
			$this->model->set_agent_login_status($agent[0]);
			
			$_SESSION['agent_account'] = array(
                'id' => $agent[0],
                'name' => $agent[1]
            );
			
			header('location: '. $this->view->set_url('survey'));
			die();
			
		} else if ($check_status->status == 1) {
			
			$message = base64_encode('Duplicate login entry please clear your session, contact your system administrator.');
			
		} else if ($check_status->status == 2) {
			
			$message = base64_encode('Your account is disabled by the administrator.');
			
		}
		
        header('location: '. $this->view->set_url("survey?message={$message}"));
    }

    public function insert()
    {
        $id = $this->model->insert_survey_answers($_POST);
        
        echo json_encode(array(
            'status'   => 'ok',
            'code'     => 200,
            'id'       => $id,
            'redirect' => (isset($_POST['redirect']))?$_POST['redirect']."/$id": false,
            'last'     => 0,
            'message'  => 'Customer successfully saved.'
        ));
        
    }
    
    public function qa_update()
    {
        $id = $this->model->update_survey_answers($_POST);
        
        echo json_encode(array(
            'status' => 'ok',
            'code' => 200,
            'id' => $id,
            'redirect' => (isset($_POST['redirect']))?$_POST['redirect']."/$id": false,
            'last' => 1,
            'message' => 'Customer successfully saved.'
        ));
    }
    
    public function validate()
    {
        $phone = $_POST['phone'];
        $valid = $this->model->validate_app($phone);

        if ($valid) {
            echo json_encode(array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Phone Number is already registered. try another number',
                'apps' => $valid
            ));
        } else {
            echo json_encode(array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Passed the validation'
            ));
        }
    }

    public function disposition()
    {
        $_POST['redirect'] = URL.'survey';
        $_POST['type'] = 'disposition';
        $id = $this->model->insert_survey_answers($_POST);
        echo json_encode(array(
            'status' => 'ok',
            'code' => 200,
            'id' => $id,
            'last' => 1,
            'redirect' => (isset($_POST['redirect']))?$_POST['redirect']: false,
            'message' => 'Customer successfully saved.'
        ));
    }
    
    public function next($id)
    {
        $this->model->get_suppression_url();
        $this->view->group_id = $id;
        
        $get_answered_group = (object)$this->model->get_answered_group($id);

        $this->view->get_answered_group = (object)$get_answered_group->details;

        $this->view->phone = $get_answered_group->phone;
        $this->model->phone = $get_answered_group->phone;
       
        if (!isset($this->view->get_answered_group->scalar)) {
           
           	$this->view->responses = $this->model->getAllCurrentResponses();
			
			/* print_r($this->view->responses); */
			
            $this->view->parentWlogic = $this->model->get_questions_with_logic();
            $this->view->questions = $this->model->get_questions_by(array('field'=>'group', 'value'=>'2'), $id, $this->view->get_answered_group->post_code, $get_answered_group->phone);
					
            $this->view->groups = $this->model->get_groups();
            $this->view->qualifying = $this->model->get_qualifying_answers($id);
            $this->view->render('survey/crm/next');
            return;
        }

        $this->view->render('error/index');
        return;
    }
    
    public function group($param)
    {
        $ids = explode('|', $param);
        $this->view->questions = $this->model->get_questions_by(array('field'=>'group', 'value'=>$ids[1]), $ids[0]);
        $this->view->group_id = $ids[0];
        $this->view->render('survey/crm/groups', true);
    }
    
    public function save_customer_survey()
    {
    
        if (isset($_POST['question'])) {
            $this->model->insert_questions($_POST['question'], $_POST['answer'], $_POST['answer_group']);
        }

        $this->model->update_object(array('ag_id'=>$_POST['answer_group'], 'status'=> 1, 'validation_status' => ''));

        echo json_encode(array(
            'status' => 'ok',
            'code' => 200,
            'redirect' => $_POST['redirect'],
            'message' => 'Customer successfully saved.'
        ));
    }

    public function dispose_customer_survey()
    {
        if (isset($_POST['question'])) {
            $this->model->insert_questions($_POST['question'], $_POST['answer'], $_POST['answer_group']);
        }

        $this->model->update_object(array('ag_id'=>$_POST['answer_group'], 'status'=> 0, 'validation_status' => ''));

        echo json_encode(array(
            'status' => 'ok',
            'code' => 200,
            'redirect' => $_POST['redirect'],
            'message' => 'Customer successfully saved.'
        ));
    }
    
    public function get_child($id)
    {   
		
        $postal = $this->model->get_answered_group($_GET['gid']);
		
		$this->view->responses = $this->model->getAllCurrentResponses();
		
		$this->view->group_id = $_GET['gid'];
        $this->view->questions = $this->model->get_children($id, $postal['details']['post_code'], $postal['phone']);
		
		// $this->view->questions = $this->sanitize_array_followup_questions($questions);
		
		// $this->view->questions = $questions;
		
        $this->view->parentWlogic = $this->model->get_questions_with_logic();

        $this->view->render('survey/crm/followup', true);
    }
	
	public function sanitize_array_followup_questions($questions)
	{
		$questions_array = array();
		
		foreach($questions as $question):
			$questions_array[$question->id] = $question;
		endforeach;
		
		return $questions_array;
	}

    public function get_logic_questions($id)
    {
        $group = $_GET['group'];

        $postal = $this->model->get_answered_group($group);

        $questions = $this->model->get_logic_question($id, $group, $postal['details']['post_code'], $postal['phone']);
      
		$questions = array_filter($questions);
		
        $this->view->parentWlogic = $this->model->get_questions_with_logic();

        $questions_collection = array();

        $html = ''; 

        if ($questions) {
            foreach ($questions as $key => $value) {

                if($value->status == 2 || $value->status == 3) {

                }else {
                    // echo the content
                    $html = '';
                    $this->view->responses = $this->model->getAllCurrentResponses();
                    $this->view->question = $value;
					$this->view->group_id = $group;
                    $this->view->parentRule = $this->model->get_all_parent_rules($value->id);
                    $this->view->id = $id;
                    ob_start();
                        $this->view->render('survey/crm/logic_page', true);
                        $html = base64_encode(ob_get_contents());
                        // clear ob
                    ob_end_clean();
                    // catch the content
                    $questions_collection[$value->id]['order'] = $value->order;
                    $questions_collection[$value->id]['html']  = $html;
                    $questions_collection[$value->id]['rules'] = $this->view->parentRule;
                }
                
            }
        }
        echo json_encode($questions_collection);
    }

    public function check_supression($phone)
    {
        // model supression checker

        echo json_encode($this->model->supression_checker($phone));
    }
}
