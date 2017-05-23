<?php
class survey_Model extends Model{

    var $questions = 'questions';
    var $groups = 'groups';
    
    function __construct()
    {
        parent::__construct();
    }

    public function get_questions($postal = null)
    {
        $group = $this->get_group_by_postal($postal);
		
        $sql = "SELECT * FROM {$this->questions} WHERE (`group` = '{$group->group_id}' AND ( status = '1' OR status = '3') ) ORDER BY `order` ASC";
		
        $questions = $this->select($sql);
		
        $questions_array = array();
		
        foreach ($questions as $question) {
			
            $questions_array[] = $this->prepare_array($question);
			
        }
		
        return (object)$questions_array;
        
    }
    
    public function get_postal($postal)
    {
        $sql = "SELECT * FROM postal where name = '$postal'";
        return $this->selectSingle($sql);
    }
    
    public function get_questions_with_logic()
    {
        $sql = 'SELECT reference_question FROM follow_up_question_validation GROUP BY reference_question';
        $logic = $this->select($sql);

        $logic_ids = array();

        foreach ($logic as $key => $value) {
            $logic_ids[] = $value->reference_question;
        }

        return $logic_ids;
    }
	
	public function set_agent_login_status($id)
	{
		if($id)
		{
			$ip = $_SERVER['REMOTE_ADDR'];
			// $this->update('answered_group', $lists, "ag_id = {$post['id']}");
			$this->update('agent', array('status' => 1, 'last_login' => date('Y-m-d H:i:s'), 'IP' => $ip ), "agent_id = {$id}");
		}
	}
	
	public function check_agent_login_status($id)
	{
		if($id)
		{
			// $this->update('answered_group', $lists, "ag_id = {$post['id']}");
			$get_agent = "SELECT status FROM agent WHERE agent_id = {$id}";
			
			return $this->selectSingle($get_agent);
		}
	}

    public function get_qualifying_answers($answer_id)
    {
        $qualifying_answers = "SELECT answered_questions.label as manual, 
                                answered_questions.amount,
                                questions.question,
                                choices.label,
                                answered_questions.aq_id,
                                answered_questions.answer
                              FROM answered_questions 
                              LEFT JOIN questions ON answered_questions.question_id = questions.id
                              LEFT JOIN choices ON answered_questions.answer = choices.choices_id
                              WHERE answered_questions.answer_group = '$answer_id'";
        
        return $this->select($qualifying_answers);
    }

    public function get_suppression($suppression_status, $question_id, $phone = 0)
    {
        if($suppression_status == 0) {
            $suppression = array(
                'status' => "success",
                'message' => "This question can be ask.",
                'code' => 200
            );
            return json_encode($suppression);
        }

        $username = $_SESSION['campaign']->database;
        
        $phone = $phone;
        
        $params = array(
                'username' => $username,
                'phone' => $phone,
                'question' => $question_id
            );
        
        $encoded = base64_encode(json_encode($params));

        $url = $this->get_suppression_url().$encoded;
		
        return $this->remote_access_suppression($url);
    }

    public function remote_access_suppression($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function get_questions_by($params, $id, $postal = '', $phone)
    {
        $postal = strtoupper($postal);

        $sql = "SELECT questions.* FROM questions
                where (`{$params['field']}` = '{$params['value']}' AND (status = '1' OR status = '3')) 
                ORDER BY `order` ASC";
    
        $questions = $this->select($sql);

        $this->user_answer_validation_collection();

        $this->answer_group_collection = $this->construct_answer_group($id);

        $questions_array = array();

        $count = 0;

        foreach ($questions as $question) {
            // show the questions forcedly if the status is forced.
           
            $questions_array[] = $this->validate_questions($question,$postal,$id, $phone);
            /*
             * check if the conditions are met on this stage
             * the system check the question group, whether what are the set of answer 
             * based on the previous survey.
             */
        }
		/* print_r($questions_array); */
        return (object)array_filter($questions_array);
    }

    public function get_suppression_url()
    {
        $server = $this->selectSingle('SELECT * FROM site WHERE title = "suppression_server"');
        
        $url = '';

        if($server) {
            $url = "http://{$server->value}/suppression/get_suppression/";
        } else {
            $url = SUPPRESSION;
        }

        return $url;
    }

    public function construct_answer_group($id)
    {
        
        $answer_group_stmt = "SELECT * FROM answered_questions WHERE answer_group =  $id";

        $answer_group = $this->select($answer_group_stmt);

        $answer_group_collection = array();

        foreach ($answer_group as $key => $value) {

            $answer_group_collection[base64_encode($value->question_id.''.$value->answer_group)] = $value;

        }

        return $answer_group_collection;
    }

    public function validate_answer_backup($answers, $ag)
    {
        // Reference_question = '$reference' AND answer = '$answer' AND
        // $sql = "SELECT * FROM answered_questions WHERE answer_group = '$ag' AND question_id = '$id' AND answer IN ($answer)";
        
        if (isset($this->answer_group_collection[base64_encode($id.''.$ag)]->answer)) {
            
            $answer = $this->answer_group_collection[base64_encode($id.''.$ag)]->answer;

            if ($operator == 'equal') {
                if ($answers == $answer) {
                    return true;
                }
                return false;
            }

            return false;
            // if (in_array($answer, explode(',', $answers))) {
            //    return true;
            // }
        }
        return false;
        
    }

    public function user_answer_validation_collection()
    {
		// mutually exclusive question - follow up question validation
        $stmt = "SELECT * FROM follow_up_question_validation";
        
        $statements = $this->select($stmt);

        $validation_collection = array();

        foreach ($statements as $key => $value) {
            $validation_collection[$value->display_question][$value->reference_question][] = $value;
        }

        $this->validation_collection = $validation_collection;
    }
 
    public function exclude_question_by_postal($ex_postal_codes, $postal)
    {
        $codes = explode(",", $ex_postal_codes);

        $rough_postal = explode(" ", $postal);

        $clean_postal = trim(str_replace(range(0, 9), '', $rough_postal[0]));

        $rough_postal = trim($rough_postal[0]);

        if ($ex_postal_codes == "0") {
            return true;
        } else {

            // check rough postal code.
            if (in_array($rough_postal, $codes, true)) {
                return false;

            // check using clean postal code.
            } else if (in_array($clean_postal, $codes, true)) {
                return false;

            // check using full postal code.
            } else if (in_array($postal, $codes, true)) {
                return false;
            }
        }
        return true;
    }

    public function has_int($post_code)
    {
        if (strcspn($post_code, '0123456789') != strlen($post_code)) {
            return true;
        }
        else {
            return false;
        }
        
    }
    
	public function include_question_by_postal($in_postal_codes, $postal){

        $codes = explode(",", $in_postal_codes);

        $rough_postal = explode(" ", $postal);

        $clean_postal = trim(str_replace(range(0, 9), '', $rough_postal[0]));

        $rough_postal = trim($rough_postal[0]);

        if ($in_postal_codes == "*") {
            return true;
        } else {
            // check using clean postal code
            if (in_array($clean_postal, $codes, true)) {
                return true;

            // check using rough postal code
            } else if(in_array($rough_postal,$codes, true)) {
                return true;

            //check using full postal code
            } else if (in_array($postal,$codes, true)) {
                return true;
            }
        }
        return false;
    }
    
    public function prepare_array($question)
    {
        return (object)array(
                'id'               => $question->id,
                'question'         => $question->question,
                'condition'        => $question->condition,
                'max_apps'         => $question->max_apps,
                'expected_answer'  => (isset($question->expected_answer))?$question->expected_answer:'',
                'condition_answer' => (isset($question->condition))?$question->condition:'',
                'choices'          => $this->extract_choices($question->choices),
                'role'             => ($this->check_if_parent($question->id)->count)?'parent':'child',
                'order'            => $question->order,
                'status'           => $question->status,
                'is_required'      => $question->is_required,
                'supression'       => $question->enable_supression,
            );
    }
    
    public function get_children($id, $postal = '', $phone = 0)
    {
       
        $answer = $_GET['answer'];
        $questions_array = array();

         $sql = "SELECT 
                logic_follow_up_questions.condition,
                logic_follow_up_questions.answer as condition_answer, 
                logic_follow_up_questions.answer as expected_answer,
                questions.id as id, 
                questions.choices,
                questions.question,
                questions.max_apps,
                questions.status,
                questions.ex_postal_codes,
                questions.in_postal_codes,
                questions.order,
                questions.is_required,
                questions.enable_supression,
                choices.*  
                FROM `logic_follow_up_questions` 
                LEFT JOIN questions on questions.id = logic_follow_up_questions.display_question
                LEFT JOIN choices on choices.choices_id = logic_follow_up_questions.answer
                WHERE logic_follow_up_questions.parent_question = '$id'";
		
        $questions = $this->select($sql);
		
		$not_equals = $this->not_equal_validation($questions);
	
        foreach ($questions as $question) {
            if ($question->condition == 'equal') {
                if ($question->condition_answer == $answer) {
                    $questions_array[$id] = $this->validate_questions($question,$postal,$id, $phone);
                }
            } else if ($question->condition == 'any') {
					$questions_array[$id] = $this->validate_questions($question,$postal,$id, $phone);

            } else if ($question->condition == 'not_equal') {
				$equals = (isset($not_equals[$question->id])) ? $not_equals[$question->id]: array($answer);
			
                if (!in_array($answer,$equals)) {
                    $questions_array[$id] = $this->validate_questions($question,$postal,$id, $phone);
                }
            }
		
        }
		
		/* 
		if($this->not_equal_validation($questions,$answer)){
			$questions_array[$id] = $this->validate_questions($question,$postal,$id, $phone);
		}
		*/
		
        return array_filter($questions_array);
    }
    
	public function not_equal_validation($questions)
	{
		$result = array();
		
		foreach ($questions as $question):
			if ($question->condition == 'not_equal') {
               $result[$question->id][] = $question->condition_answer;
            }
		endforeach;
		
		return $result;
	}

    public function get_logic_question($id, $group, $postal, $phone)
    {
        $answer = $_GET['answer'];
        
        $questions_array = array();

        $sql = "SELECT 
                follow_up_question_validation.operator as `condition`,
                follow_up_question_validation.answer as condition_answer, 
                follow_up_question_validation.answer as expected_answer,
                questions.id as id, 
                questions.choices,
                questions.status,
                questions.question,
                questions.enable_supression,
                questions.max_apps,
                questions.order,
                questions.ex_postal_codes,
                questions.in_postal_codes,
                questions.is_required,
                choices.*  
                FROM `follow_up_question_validation` 
                LEFT JOIN questions on questions.id = follow_up_question_validation.display_question
                LEFT JOIN choices on choices.choices_id = follow_up_question_validation.answer
                WHERE follow_up_question_validation.reference_question = '$id'";

        $questions = $this->select($sql);

		foreach ($questions as $key => $question) {

            // Get number of answered qualifying questions
			$answered_questions = $this->count_answered_qualifying_questions($question->id, $group);
            $answered_qualifying_questions = count($answered_questions);
			/* 
			print_r($answered_questions);
             */
            // Check if answers from qualifying questions are correct.
            $valid = $this->validate_logic_qualifying_answers($question->id, $group);

            if (count($valid) == $answered_qualifying_questions) {
				
                // prepare array for html output
				/*
				echo 'valid '. $valid;
				echo '| Answered '. $answered_qualifying_questions .'<br />';
				*/
				
                $questions_array[] = $this->validate_questions($question,$postal,$id, $phone);
                // $questions_array[] = $this->prepare_array($question);
            }
        }
        
        return $questions_array;
    }

	public function validate_questions($question,$postal,$id, $phone = 0)
    {   
        $questions_array = array();
       
        $suppression = $this->get_suppression($question->enable_supression, $question->id, $phone);

        $suppression_detail = (object)json_decode($suppression);
		
		/* 
		echo "------------";
		echo "\n". $question->id. "\n";
		print_r($suppression_detail); 
		echo "------------ \n";
		 */
		if ($question->status == '3') {
			$questions_array = $this->prepare_array($question);
		} else if (
			$this->exclude_question_by_postal($question->ex_postal_codes, $postal)
			&& $this->include_question_by_postal($question->in_postal_codes, $postal)
			&& $this->get_user_answer_validation($id, $question->id)
		){
			if(!isset($suppression_detail->code)) {
				$questions_array = $this->prepare_array($question);
				// $questions_array = $this->prepare_array($question);
			} else if(isset($suppression_detail->code)){
				if(	$suppression_detail->code == 200 ) {
					$questions_array = $this->prepare_array($question);
				}
			}
		}
		return $questions_array;
    }
	
	public function get_user_answer_validation($answer_group, $id)
    {
        $answers_collection = (isset($this->validation_collection[$id])) ? $this->validation_collection[$id]: array();
        $collection_count = count($answers_collection);
        $counter = 0;
		/* 
		echo "-------\n";
		echo $id."\n";
		echo $collection_count."\n";
		print_r($answers_collection);
		echo "-------\n"; 
		*/
		
        if ($answers_collection) {
            foreach ($answers_collection as $answers) {
                $response = $this->validate_answer(
                    // List of answers
                    $answers,
                    // Answer group ID
                    $answer_group
                );

                if ($response) {
                    $counter++;
                }
            }
        } else {
            return true;
        }
		
        if ($counter == $collection_count) {
            return true;
        }
		
        return false;
    }
	
    public function validate_answer($answers, $ag)
    {
        foreach ($answers as $key => $value) {

            if (isset($this->answer_group_collection[base64_encode($value->reference_question.''.$ag)]->answer)) {            
                $answer = $this->answer_group_collection[base64_encode($value->reference_question.''.$ag)]->answer;
				
				if (!is_numeric($answer)) {
					
                    // if question is not answered with 0
                    // and leaved as blank,
                    // validate if string has content.
                    if ($answer == '') {
                        return false;
                    } else {
                        return true;
                    }
                } else if ($value->operator === 'equal') {
                    if ($value->answer == $answer) {
                        return true;
                    }//end if
                } else if($value->operator === 'not_equal') {
                    // not equal
                    if ($value->answer != $answer) {
						return true; 
					} else {
						return false;
					}
                } else {
					/* echo 'last'; */
                    // if manual and the condition is any
                    // this will return false value if question is not answered.
                    if ($answer == 0 || $answer == "") {
                        return false;
                    }
                    // any condition for not manual inpput answer.
                    return true;
                }//end if
            }//end if
        }//end foreach
		
		
        return false;
    }//end validate_answer()
	
    public function count_answered_qualifying_questions($display_id, $group)
    {
        $count_conditions = "SELECT fl.* FROM follow_up_question_validation as fl
                             INNER JOIN answered_questions as aq ON fl.reference_question = aq.question_id
                             WHERE fl.display_question = '$display_id' AND aq.answer_group = '$group'
                             GROUP BY fl.reference_question";
	
		return $this->select($count_conditions);           
    }

    public function validate_logic_qualifying_answers($display_id, $group)
    {
       $validate_sql = "SELECT 
							answered_questions.answer as user_answer, 
							follow_up_question_validation.answer as expected_answer, 
							follow_up_question_validation.*,
							answered_questions.*
						FROM follow_up_question_validation, answered_questions
                        WHERE display_question = '$display_id' 
                        AND answered_questions.question_id = follow_up_question_validation.reference_question
                        AND answered_questions.answer_group = '$group' 
                        AND 
							(follow_up_question_validation.answer = answered_questions.answer
							OR follow_up_question_validation.operator =  'any'
							OR follow_up_question_validation.operator =  'not_equal')
                        GROUP BY answered_questions.question_id ORDER BY answered_questions.answer_group" ;
/* 
		echo $validate_sql;
		
 */						
        $validated_query = $this->select($validate_sql);
 		$counter = array();
		
		foreach($validated_query as $key => $value):
			if($value->operator == 'not_equal') {
				if($value->user_answer != $value->expected_answer) {
					$counter[] = $value;
				}
			} else if($value->operator == 'equal') {
				if($value->user_answer == $value->expected_answer) {
					$counter[] = $value;
				}
			} else if($value->operator == 'any') {
				$counter[] = $value;
			}
			
		endforeach;
		
		return $counter;
    }

    public function check_if_parent($id){
        $sql = "SELECT 
                count(*) as count
                FROM `logic_follow_up_questions`, `follow_up_question_validation`
                WHERE 
				logic_follow_up_questions.parent_question = '$id'
				OR
				follow_up_question_validation.reference_question = '$id'";

        return $this->selectSingle($sql);
    }

    public function get_all_parent_rules($id)
    {
        $sql = "SELECT * FROM follow_up_question_validation WHERE display_question = '$id'";

        $parents = $this->select($sql);

        $collection = '';
        
        foreach ($parents as $key => $value) {
            $collection[$value->reference_question][] = array(
                                                        'id'       => $value->id,
                                                        'rq'       => $value->reference_question,
                                                        'operator' => $value->operator,
                                                        'answer'   => $value->answer
                                                     );
        }

        return $collection;
    }
    
    public function extract_choices($choices_str){
        $choices = unserialize($choices_str);
        $choices_list = array();
    
        if ($choices) {
            foreach ($choices as $choice) {
                if ($choice) {
                    $choices_list[] = $this->get_choices_by_id($choice);
                }
            }
        }

        return $choices_list;
    }
    
    public function update_object($params)
    {
        $this->update('answered_group', $params, "ag_id={$params['ag_id']}");
    }
    
    function get_group_by_postal($postal = null)
    {
        if ($postal) {
            $sql = "SELECT * FROM {$this->groups} WHERE group_id = '$postal'";
            return $this->selectSingle($sql);
        }
    }
     
    public function get_choices_by_id($id)
    {
        $sth = "SELECT * FROM  `choices` WHERE choices_id = '$id'";
        return $this->selectSingle($sth);
    }
    
    public function insert_survey_answers($post)
    {
    
        $lists = array(
            'phone'                  => $post['phone'],
            'agent_id'               => $post['agent_id'],
            'status'                 => (isset($post['status']))? $post['status']:0,
            'interview_started_date' => $post['interview_started_date'],
            'interview_started_time' => $post['interview_started_time'],
            'validation_status'      => 'on going survey',
            'details'                => serialize(
                array(
                    'urn_original' => $post['urn_original'],
                    'post_code'    => $post['post_code'],
                    'country'      => $post['country'],
                    'title'        => $post['title'],
                    'first_name'   => $post['first_name'],
                    'last_name'    => $post['last_name'],
                    'address1'     => $post['address1'],
                    'address2'     => $post['address2'],
                    'address3'     => $post['address3'],
                    'gender'       => $post['gender'],
                    'town'         => $post['town'],
                    'email'   	   => $post['email'],
                    'company'      => $post['company'],
                    'position'     => $post['position'],
                    'website'      => $post['website'],
                    'legal'        => (isset($post['legal']))?$post['legal']:null,
                    'confirm'      => (isset($post['confirm']))?$post['confirm']:null
                )
            )
        );

            
        $id = $this->insert('answered_group', $lists);
        

        if (isset($post['manual'])) {
			
            $app_stat = array(
                    'agent_id'  => $post['agent_id'],
                    'app_id'    => $id,
                    'duplicate' => 0
                );
				
            $duplicate = $this->validate_app($post['phone']);
			
            if ($duplicate) {
                if(count($duplicate) > 1) {
                    $app_stat['duplicate'] = 1;
                    $app_stat['duplicate_ids'] = serialize($duplicate);
                }
            } //end if duplicate

            $this->insert('app_stat', $app_stat);
        }// manaula isset

        if (isset($post['question'])) {
            /* $this->insert_questions($post['question'], $post['answer'], $id); */
            $this->insert_questions_validated($post['question'], $post['answer'], $id);
        }
        return $id;
    }
    
    public function update_survey_answers($post)
    {
        $this->migrate_applications($post['id']);
        
        $lists = array(
                'phone'                  => $post['phone'],
                'agent_id'               => $post['agent_id'],
                'status'                 => (isset($post['status']))? $post['status']:0,
                'validation_status'      => $post['validation_status'],
                'validator'              => $post['validator'],
                'remarks'                => $post['remarks'],
                'interview_started_date' => $post['interview_started_date'],
                'interview_started_time' => $post['interview_started_time'],
                'validation_status'      => $post['validation_status'],
                'details'                => serialize(
                    array(
                     'urn_original' => $post['urn_original'],
                     'post_code'    => $post['post_code'],
                     'country'      => $post['country'],
                     'title'        => $post['title'],
                     'first_name'   => $post['first_name'],
                     'last_name'    => $post['last_name'],
                     'address1'     => $post['address1'],
                     'address2'     => $post['address2'],
                     'address3'     => $post['address3'],
                     'gender'       => $post['gender'],
                     'town'         => $post['town'],
					 'email'   	   => $post['email'],
					 'company'      => $post['company'],
					 'position'     => $post['position'],
					 'website'      => $post['website'],
                     'legal'        => (isset($post['legal']))?$post['legal']:null,
                     'confirm'      => (isset($post['confirm']))?$post['confirm']:null
                    )
                )
            );
            
            $this->update('answered_group', $lists, "ag_id = {$post['id']}");
        
        if (isset($post['question'])) {
            $this->delete('answered_questions', "answer_group = '{$post['id']}'");
            $this->insert_questions_validated($post['question'], $post['answer'], $post['id']);
        }
    }
    
    public function migrate_applications($id)
    {
    
        /*application migration*/
        $sql = "SELECT * FROM answered_group WHERE ag_id = '$id'";
        $application = $this->selectSingle($sql);
        unset($application->date);
        $answered_group_history_id = $this->insert('answered_group_history', (array)$application);
        /*function migration*/
        
        /*question migration*/
        $questions = $this->select("SELECT * FROM answered_questions WHERE answer_group = '$id'");
        foreach ($questions as $question) {
            $question->ag_id_history = $answered_group_history_id;
            $this->insert('answered_questions_history', (array)$question);
        }
        /*question migration*/
    }
    
    public function insert_questions($question, $answer, $group)
    {

        $count = count($question);
        $total_amount = 0.00;

        for ($i=0; $i<$count; $i++) {
        
            $question_details = $this->get_question_by_id($question[$i]);

            $params = array('question_id'=>$question[$i],'answer'=>$answer[$i],'answer_group'=>$group,'label'=>$answer[$i]);

            if (in_array($answer[$i], $question_details->paid_response)) {
				
                $params['amount'] = $question_details->amount;
				
                $total_amount = floatval($question_details->amount) + floatval($total_amount);
            }

            $this->insert('answered_questions', $params);
        }
            
        $revenue = $this->get_revenue_by($group);

        $this->update_object(array('ag_id'=>$group,'revenue'=> floatval($total_amount)));
        return $total_amount;
    }
    /*
    public function insert_questions_validated($question,$answer,$group)
    {
        $count = count($question);
        $total_amount = 0.00;
		$params = array();
		
        for ($i=0; $i<$count; $i++) {
        
            $question_details = $this->get_question_by_id($question[$i]);

            $params[$i] = array('question_id'=>$question[$i],'answer'=>$answer[$i],'answer_group'=>$group,'label'=>$answer[$i]);
            
            if (in_array($answer[$i], $question_details->paid_response)) {

                $params[$i]['amount'] = $question_details->amount;

                $total_amount = floatval($question_details->amount) + floatval($total_amount);
            }

		}
        $this->bulk_insert('answered_questions', array('question_id','answer','answer_group','label','amount'), $params);
		
		//save all recorded answers
		//die();
		//$this->insert('answered_questions', $params);
		
        $this->update_object(array('ag_id'=>$group,'revenue'=> floatval($total_amount)));
        return $total_amount;
    }
	*/
	
	public function insert_questions_validated($question,$answer,$group)
    {
        $count = count($question);
        $total_amount = 0.00;

        for ($i=0; $i<$count; $i++) {
        
            $question_details = $this->get_question_by_id($question[$i]);

            $params = array('question_id'=>$question[$i],'answer'=>$answer[$i],'answer_group'=>$group,'label'=>$answer[$i]);
            
            if (in_array($answer[$i], $question_details->paid_response)) {

                $params['amount'] = $question_details->amount;

                $total_amount = floatval($question_details->amount) + floatval($total_amount);
            }

            $this->insert('answered_questions', $params);
        }
        
        $this->update_object(array('ag_id'=>$group,'revenue'=> floatval($total_amount)));
        return $total_amount;
    }
    
    public function get_question_by_id($id)
    {
          $question = $this->selectSingle("SELECT paid_response, amount FROM `questions` WHERE id = '$id'");
          return (object)array(
                "paid_response" => (unserialize($question->paid_response))? unserialize($question->paid_response):array(),
                "amount" => $question->amount
          );
    }
    
    public function get_agents()
    {
        return $this->select("SELECT * FROM `agent`");
    }
    
    public function get_groups()
    {
        return $this->select("SELECT * FROM `groups` WHERE name != 'logical' AND status = 1");
    }

    public function get_answered_group($id)
    {
        $item = $this->selectSingle("SELECT details, phone FROM `answered_group` WHERE ag_id = '$id'");
        if ($item) {
            return array(
                    'phone' => $item->phone,
                    'details' => unserialize($item->details)
                   );
        }
        return false;
        
    }

    public function get_revenue_by($id)
    {
        return $this->selectSingle("SELECT revenue FROM `answered_group` WHERE ag_id = '$id'");
    }
    
    public function getAllCurrentResponses()
    {
        $paidReponses = $this->getQuestionsPaidResponses();

		$start_time_settings = $this->get_settings('cap_start_time');
		$end_time_settings = $this->get_settings('cap_end_time');
		
		// if settings is defined
		if($start_time_settings && $end_time_settings) {
			
			$end_time_settings_comparison = explode(':', $end_time_settings);
			
			$date_trap_settings = explode(':', $start_time_settings);
			$date_trap_settings = $date_trap_settings[0];
			
			$date_trap = date('H');

			$currentDate = date("Y-m-d $start_time_settings");

			if($date_trap < $date_trap_settings) {
				$currentDate = date("Y-m-d $start_time_settings", strtotime($currentDate . "-1 days"));
			}
			
			$startshift = date("Y-m-d $start_time_settings", strtotime($currentDate));
			
			// if night shift
			if($end_time_settings_comparison[0] < $date_trap_settings):
				$endShift = date("Y-m-d $end_time_settings", strtotime($startshift . "+1 days"));
			else:
			// if morning shift
				$endShift = date("Y-m-d $end_time_settings", strtotime($currentDate));
			endif;
			
		}else{ // if settings is not defined
			$date_trap = date('H');

			$currentDate = date('Y-m-d 14:00:00');

			if($date_trap < 14) {
				$currentDate = date('Y-m-d 14:00:00', strtotime($currentDate . "-1 days"));
			}
			
			$startshift = date('Y-m-d 14:00:00', strtotime($currentDate));
			$endShift = date('Y-m-d 06:00:00', strtotime($startshift . "+1 days"));
		}
		
        $getAppsQuery = "SELECT *  FROM answered_group WHERE date BETWEEN '$startshift' AND '$endShift'";
		
        // get all apps in this shift
        $getApps = $this->select($getAppsQuery);
		
		$ids = array();
		
		foreach($getApps as $key => $value) {
			$ids[] = $value->ag_id;
		}
		
		$ids = implode($ids, ',');
		
        $appIds = rtrim($ids, ', ');
		
		
        /*
        $allResponses = "SELECT count(question_id) as apps, question_id, GROUP_CONCAT(answer SEPARATOR ', ') as responses
                         FROM answered_questions
                         LEFT JOIN choices ON answered_questions.answer = choices.choices_id
                         WHERE answer_group IN ($appIds)
                         GROUP BY question_id";
        */
                         
        $allResponses = "SELECT count(question_id) as apps, question_id, GROUP_CONCAT(answer SEPARATOR ', ') as responses
                         FROM answered_questions, choices
                         WHERE answered_questions.answer = choices.choices_id AND answer_group IN ($appIds)
                         GROUP BY question_id";
	
        // get all responses.
        $responses = $this->select($allResponses);

        $questionCollection = array();

		/* 
		print_r($responses);
		print_r($paidReponses);
		*/
        foreach ($responses as $key => $value) {
            if(isset($paidReponses[$value->question_id])) {
				
                $array1 = array_map('trim',explode(',', $value->responses));
                $array2 = ($paidReponses[$value->question_id]) ? $paidReponses[$value->question_id]: array();
                $diff   = array_intersect($array2, $array1);
				
                if ($diff) {
                    $questionCollection[$value->question_id] = $value->apps;
                }
            }
        }
        
        return $questionCollection;
    }

    public function getQuestionsPaidResponses()
    {
        $paidReponsesQuery = "SELECT id, paid_response, amount FROM questions WHERE amount > 0 GROUP BY id";
        
        $paidReponses = $this->select($paidReponsesQuery);

        $collection = array();

        foreach ($paidReponses as $key => $value) {
			$collection[$value->id] = unserialize($value->paid_response);
        }
		
        return $collection;
    }

    public function validate_app($phone)
    {
        $validate_phone = "SELECT answered_group.ag_id, agent.first_name, agent.last_name, answered_group.date, answered_group.validation_status
                            FROM answered_group, agent
                            WHERE answered_group.agent_id = agent.agent_id
                            AND phone = '$phone' AND 
                            date >= NOW() - INTERVAL 2 MONTH
                            AND validation_status != 'Reject'
                            ORDER BY date DESC";
        return $this->select($validate_phone);
    }

    public function supression_checker($phone)
    {
        $supression_check = "SELECT count(*) as result FROM supression WHERE phone = $phone";

        $result = $this->selectSingle($supression_check);

        return $result;
    }
}
