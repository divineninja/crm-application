<?php
class reports_Model extends Model {

    /**
     * Number of total revenue per agent application
     * 
     */
    var $loop_total_revenue = 0;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_raw_application_by_date($from,$to)
    {
        $sql = "SELECT agent.*,user.first_name as agent_f_name,answered_group.*,
                user.last_name as agent_l_name, DATE_FORMAT(`date`,'%d %M, %Y %h:%i %p') as app_date 
                FROM  `answered_group`
                LEFT JOIN `agent` ON `agent`.`agent_id`  = `answered_group`.`agent_id`
                LEFT JOIN user ON answered_group.validator = user.id
                WHERE `date` BETWEEN '$from' AND '$to' ORDER BY ag_id";
		
        return $this->select($sql);
    }
    
    public function get_application_details($id)
    {
        $sql = "SELECT *, DATE_FORMAT(`date`,'%d %M, %Y %h:%i %p') as date 
                FROM `answered_group` 
				LEFT JOIN user ON answered_group.validator = user.id 
				LEFT JOIN answered_questions ON answered_group.ag_id = answered_questions.answer_group 
				WHERE ag_id = '$id' ORDER BY ag_id";
		
        $output = array();
        $application = $this->selectSingle($sql);
        $application->questions = $this->get_questions($application->ag_id);
        $application->details = unserialize($application->details);
		
        return $application;
    }
    
    public function get_questions($id)
    {
        $responsesStart = microtime(true);
        
		// get all questions
        $questions = $this->select(
            "SELECT * FROM questions WHERE status <> 0
            ORDER BY questions.`order` ASC, code ASC"
        );
	
		// get answered questions
        $answered = $this->asnwered_questions_list($id);
	
        $questions_list = array();
    
        foreach ($questions as $question) {
            
            if (isset($answered[$question->code])) {
                $label  = $answered[$question->code]->label;
                $manual = $answered[$question->code]->manual;
                $answer = $answered[$question->code]->answer;
            } else {
                $label  = "";
                $manual = "";
                $answer = "";
            }
			
			$paid_response = unserialize($question->paid_response);
			
			$amount = $question->amount;
			
			if($paid_response)
			{
				$amount = in_array($answer, $paid_response) ? $amount: 0;
			}
			
            $questions_list[] = (object)array(
                "label"    => $label,
                "amount"   => $amount,
                "code"     => $question->code,
                "question" => $question->question,
                "answer"   => $answer,
                "id"       => $question->id,
                "choices"  => $question->choices,
                "manual"   => $manual
            );
            
        }
        return (object)$questions_list;
    }
    
    public function asnwered_questions_list($id)
    {
         $questionsQuery = "SELECT 
                            q.amount, q.code, q.question, q.id, q.choices,
                            aq.label as manual, aq.answer,
                            c.label
                           FROM questions as q
                           LEFT JOIN answered_questions as aq ON q.id = aq.question_id
                           LEFT JOIN choices as c on c.choices_id = aq.answer
                           WHERE aq.answer_group ='$id'";

        $questions = $this->select($questionsQuery);

        $items = array();

        foreach ($questions as $key => $value) {
            $items[$value->code] = (object)array(
                    'label'  => $value->label,
                    'manual' => $value->manual,
                    'answer' => $value->answer,
                );
        }

        return $items;
    }

    public function get_single_questions($gid, $id){
        $sql = "SELECT
                    choices.label,
                    choices.abs(number)mount,
                    answered_questions.label as manual,
                    answered_questions.answer
                FROM `answered_questions`
                LEFT JOIN choices ON choices.choices_id = answered_questions.answer
                WHERE answer_group ='$gid' AND  answered_questions.question_id = '$id'";
        $questions = $this->selectSingle($sql);
        return $questions;
    }
    
    public function array_to_csv_download($array, $filename = 'export.csv'){
        header('Content-Type: "application/csv"; charset=utf-8; encoding=utf-8');
        header('Content-Disposition: attachement; filename="'.$filename.'";');
        $f = fopen('php://output', 'w');
        foreach ($array[0] as $fields) {
            fputcsv($f, $fields);
        }
        foreach ($array[1] as $fields) {
            fputcsv($f, $fields);
        }
        
        exit;
    }
    
    public function get_contents($from, $to)
    {
        $sql = "SELECT user.first_name as v_fname,user.last_name as v_lastname,agent.*,answered_group.*
                FROM answered_group 
                LEFT JOIN agent ON agent.agent_id = answered_group.agent_id
                LEFT JOIN user ON user.id = answered_group.validator
                WHERE `date` BETWEEN '$from' AND '$to' GROUP BY ag_id ORDER BY ag_id";

        $applications = $this->select($sql);
		
        $aq_ids = array();

        foreach ($applications as $aq_id) {
            $aq_ids[] = $aq_id->ag_id;
        }
        
        $this->answered_questions_collections = $this->get_answered_question_collection(implode(',',$aq_ids));

        $this->codes = $this->questions();
        
        $all_apps = array();
		$status = '';
		
        foreach ($applications as $apps) {
			
            $customer = (OBJECT)unserialize($apps->details);
			
			if ($apps->status == 0) {
				$status = 'Incomplete';
			}else if($apps->status == 1) {
				$status = 'Complete';
			} else {
				$status = 'No Status';
			}
			 
            $last_part = array(
                'agent_name' => $apps->first_name. ' '.$apps->last_name,
                'agent_number' => $apps->agent_number,
                'revenue' => $apps->revenue,
                'remarks' => $apps->remarks,
                'status' => $apps->validation_status,
                'disposition' => $status,
                'validator' => $apps->v_fname.' '.$apps->v_lastname
            );

            $datetime = new DateTime($apps->date);
			
            $date = new DateTime($apps->date, new DateTimeZone('Asia/Hong_Kong'));
			
            $date->setTimezone(new DateTimeZone('Europe/Amsterdam'));
            
            $first_part = array_merge(array(
                'date' => (string)$date->format('d/m/Y h:i:s a'),
                'urn' => (isset($customer->urn_original))?$customer->urn_original:'Not Defined',
                'phone' => "$apps->phone",
                'title' => $customer->title,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'address1' => $customer->address1,
                'address2' => $customer->address2,
                'address3' => $customer->address3,
                'town' => $customer->town,
                'country' => $customer->country,
                'post_code' => $customer->post_code,
				'email' => ( isset($customer->email )) ? $customer->email: '',
				'company' => (isset($customer->company)) ? $customer->company: '',
				'position' => (isset($customer->position)) ? $customer->position: '',
				'website' => (isset($customer->website)) ? $customer->website: '',
                'legal' => $customer->legal,
                'confirm' => $customer->confirm
            ), $this->answered_questions($apps->ag_id));
            
            $all_apps[] = array_merge($first_part, $last_part);
        }
        return $all_apps;
    }
	
	public function get_exact_revenue($questions)
	{
		$total = floatval(0.00);
		
		foreach($questions as $key => $value)
		{
			$total = floatval($total) + floatval($value->amount);
		}
		
		return $total;
	}

    /**
     * X-factor download
     *
     * Download csv file with 20% revenue per agent based on selected time
     * 
     * @since 3.4.2
     **/

    public function get_contents_x_factor($from, $to)
    {
        $sql = "SELECT sum(ag.revenue) as total_rev, count(ag.ag_id) as total, a.agent_id, a.first_name, a.last_name, a.agent_number
                FROM answered_group as ag, agent as a
                WHERE ag.agent_id = a.agent_id AND ag.`date` BETWEEN '$from' AND '$to'
                GROUP BY ag.agent_id";

        $applications = $this->select($sql);
      
            foreach ($applications as $apps) {
            
            $percent_content = array(
                'agent_code'      => $apps->agent_number,
                'agent_name'      => $apps->first_name.' '.$apps->last_name,
                'no_applications' => $apps->total,
                'total_revenue'   => $apps->total_rev,
                'percentage'      => ($apps->total_rev * 0.25),
            );
            
            $all_apps[] = $percent_content;
        }
            $all_apps[] = array('');
            $all_apps[] = array('', 'This is system generated report.');
            $all_apps[] = array('', 'If you see any flaws of information kindly report to CRM developer.');
        return $all_apps;
    }

    public function get_answered_question_collection($ids)
    {
        $sql = 'SELECT questions.question,questions.code,choices.label,answered_questions.label as manual, answered_questions.answer_group
                FROM answered_questions
                LEFT JOIN choices ON choices.choices_id = answered_questions.answer
                LEFT JOIN questions ON questions.id = answered_questions.question_id
                WHERE questions.status <> 0 AND answered_questions.answer_group IN ('.$ids.') 
                ORDER BY questions.order ASC, questions.code ASC';

        $answered_questions = $this->select($sql);

        $answered_questions_collections = array();

        foreach ($answered_questions as $key => $value) {
            $answered_questions_collections[$value->answer_group][] = $value;
        }

        return $answered_questions_collections;
    }
    
    public function answered_questions($ag_id)
    {

        $questions = (isset($this->answered_questions_collections[$ag_id])) ? $this->answered_questions_collections[$ag_id]: array();

        $answers = array();

        $i = 0;
        foreach ($this->codes as $s_q) {
						
            $selected = "";
            $i++;
			
            foreach ($questions as $q) {
                $code = explode(' ', $s_q);
				
                if ($code[0] == $q->code) {
                    $selected = ($q->label)?$q->label:$q->manual;
                }	
            }
			
            $answers["question_".$s_q] = ($selected)?utf8_decode($selected):"Not Answered";
        }
       return $answers;
    }
    
    public function get_headers()
    {
        $orig = array(
                 'date'       => 'Date',
                 'urn'        => 'URN',
                 'phone'      => 'Phone',
                 'title'      => 'Title',
                 'first_name' => 'First Name',
                 'last_name'  => 'Last Name',
                 'address1'   => 'Address 1',
                 'address2'   => 'Address 2',
                 'address3'   => 'Address 3',
                 'town'       => 'Town',
                 'county'     => 'County',
                 'post_code'  => 'Post Code',
                 'email'  	  => 'Email Address',
                 'company'    => 'Company',
                 'position'   => 'Position',
                 'website'    => 'Website',
                 'legal'      => 'Legal',
                 'confirm'    => 'Confirm'
        );
        $last_part = array(
            'agent_name' => 'Agent Name',
            'agent_number' => 'Agent ID',
            'revenue' => 'Revenue',
            'remarks' => 'Remarks',
            'status' => 'Status',
            'disposition' => 'Disposition',
            'validator' => 'Validator'
        );

        $output = array_merge($orig, $this->questions());

		return array_merge($output, $last_part);

	}

     public function get_headers_x_factor()
    {
        $orig = array(
                 'agent_code'      => 'Code',
                 'agent_name'      => 'Agent Name',
                 'no_applications' => 'Number of Applications',
                 'total_revenue'   => 'Total Revenue',
                 'percentage'      => '20% revenue for agent'
        );
        
        return $orig;
    }
    
    public function questions()
    {
        $sql = "SELECT code,type FROM questions 
                WHERE status <> 0 ORDER BY questions.order ASC, questions.code ASC";
        $questions =  $this->select($sql);
        $output = array();
        $type = '';
        $count = 0;
        $count_display = '';
        foreach ($questions as $question) {
            if ($question->type) {
                if ($type == $question->type) {
                    $count++;
                } else {
                    $count = 0;
                }
            }
            $count_display = ($count)?$count:'';
            $type = $question->type;
            // $output["question_{$question->code}"] = $question->code.' '. $type . ' '. $count_display;
            $output["question_{$question->code}"] = $question->code;
        }
        return $output;
    }
    
    public function get_user_questions()
    {
        $sql = "SELECT code FROM questions WHERE status = 1 LIMIT";
        $questions =  $this->select($sql);
        return $questions;
    }
    
    public function get_application($id)
    {
        $sql = "SELECT * FROM answered_group where ag_id = '$id' LIMIT 1";

        $questionsStart = microtime(true);
        $questions =  $this->selectSingle($sql);
        $questionsEnd = microtime(true);
        
        $responsesStart = microtime(true);
        $responses = $this->get_questions($questions->ag_id);
        $responsesEnd = microtime(true);
    
        $questions->details = (object)unserialize($questions->details);
        return (object)array(
            'application'   => $questions,
            'responses'     => $this->prepare_questions($responses),
            'questionsTime' => $questionsEnd - $questionsStart,
            'responseTime'  => $responsesEnd - $responsesStart,
            'history'       => $this->get_applications_history($questions->ag_id)
        );
    }
    
    public function prepare_questions($applications)
    {
        $questions = array();
        foreach ($applications as $question) {
            
            $questions[] = (object)array(
                    'id' => $question->id,
                    'amount' => $question->amount,
                    'code' => $question->code,
                    'question' => $question->question,
                    'answer' => $question->answer,
                    'label' => $question->label,
                    'manual' => $question->manual,
                    'choices' => $this->extract_choices($question->choices)
                );
        }
        return $questions;
    }
    
    public function get_applications_history($id)
    {
        $sql = "SELECT * FROM answered_group_history 
                where ag_id = '$id' ORDER by `date` DESC";
        $questions =  $this->select($sql);
        return $questions;
    }
    
    public function extract_choices($choices_str)
    {
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
    
    public function get_choices_by_id($id)
    {
        $sth = "SELECT * FROM  `choices` WHERE choices_id = '$id'";
        return $this->selectSingle($sth);
    }
    
    public function get_agents()
    {
        return $this->select("SELECT * FROM `agent`");
    }
    
    public function get_history($id)
    {
    
        $get_history_stmt = "SELECT * FROM `answered_group_history` 

                             LEFT JOIN user 
                             ON answered_group_history.validator = user.id
                            
                             WHERE ag_id_history = '$id'";
                             
        $application = $this->selectSingle($get_history_stmt);
    
        $responses = $this->get_questions_history($application->ag_id_history);
        
        $application->details = (object)unserialize($application->details);
        
        return (object)array(
            "application" => $application,
            "responses" => $this->prepare_questions($responses)
        );
        
    }
    
    public function get_questions_history($id){
        $sql = "SELECT 
        
                    choices.label,
                    choices.amount,
                    questions.code,
                    questions.question,
                    answered_questions_history.label as manual,
                    answered_questions_history.answer, 
                    questions.id, 
                    questions.choices
                    
                    FROM `answered_questions_history`
                    
                    LEFT JOIN questions ON
                    questions.id = answered_questions_history.question_id
                    
                    LEFT JOIN choices ON 
                    choices.choices_id = answered_questions_history.answer
                    
                    WHERE answered_questions_history.ag_id_history ='$id' 
                    
                    ORDER BY questions.order ASC,
                    questions.code ASC";
        return $this->select($sql);
    }
    
    public function change_status($id, $condition, $validator)
    {
        $status = array(
            'validation_status' => $condition,
            'validator' => $validator
        );
        
        $this->update("answered_group", $status, "ag_id = '$id'");
    }
    
    public function getReportsbyHour($items)
    {
        // query to get the exact data
        $sql = "SELECT date,COUNT(*) num_applications
                FROM `answered_group` 
                WHERE DATE
                BETWEEN DATE_ADD('{$items->startDate}', INTERVAL 15 HOUR) 
                AND DATE_ADD('{$items->endDate}', INTERVAL 5 HOUR) 
                GROUP BY HOUR(date)
                ORDER BY date ASC";

        $items = array();

        $collections = $this->select($sql);

        foreach ($collections as $collection) {
            $items[] = array(
                $collection->date,
                (int)$collection->num_applications
            );
        }
        
        return array(
                'display' => $collections,
                'plot' => $items
            );
    }

    public function get_application_by_agent($date)
    {
        
        $agents = $this->get_agents();

        $agent_details = array();

        foreach ($agents as $key => $value) {
            $agent_details[] = array(
                    'name'    => $value->first_name. ' '.  $value->last_name,
                    'code'    => $value->agent_number,
                    'query'   => $this->prepare_per_agent_query($value->agent_id, $date),
                    'revenue' => $this->loop_total_revenue,
                );
        }
        return $agent_details;

    }


    public function prepare_per_agent_query($agent_id, $date)
    {
        // Reset the global variable.
        $this->loop_total_revenue = 0;

        $params = $this->prepare_query_params($date);

        $start_shift_hours_end = 9;

        $end_shift_hours_end = 5;

        $individual_app_status = array();

        $counter = 0;

        foreach ($params['start_shift_hours'] as $key => $value) {

            if ($counter <= $start_shift_hours_end) {

                $end_shift_hours = (isset($params['start_shift_hours'][$key])) ? $params['start_shift_hours'][$key]: date('h:0:0', strtotime($value . "+1 hours"));

                $start_shift_hours = $value;

                $date = $params['start_shift_date'];

                $individual_query = "SELECT SUM(revenue) as revenue, count(*) as applications 
                                     FROM     answered_group 
                                     where    `interview_started_date` = '$date' 
                                     AND      interview_started_time BETWEEN '$start_shift_hours' 
                                     AND      '$end_shift_hours' 
                                     AND      agent_id = '$agent_id'
                                     AND      revenue <> 0
                                     GROUP BY agent_id";
                
                $first_shift = $this->selectSingle($individual_query);

                if ($first_shift) {
                    $this->loop_total_revenue = $this->loop_total_revenue + $first_shift->revenue;
                }

                $individual_app_status[] = $first_shift;
                
                $counter++;
            }
            
        }
        
        $counter = 0;

        foreach ($params['end_shift_hours'] as $key => $value) {

            if ($key <= $end_shift_hours_end) {

                $interview_started_date = $params['end_shift_date'];

                // $end_shift_hours = $params['end_shift_hours'][$key+1];

                $end_shift_hours = (isset($params['end_shift_hours'][$key+1])) ? $params['end_shift_hours'][$key+1]: date('h:0:0', strtotime($value . "+1 hours"));
                
                $individual_query = "SELECT SUM(revenue) as revenue, count(*) as applications 
                                     FROM       answered_group 
                                     WHERE      `interview_started_date` = '$interview_started_date' 
                                     AND        interview_started_time BETWEEN '$value' 
                                     AND        '$end_shift_hours' 
                                     AND        agent_id = '$agent_id' 
                                     AND        revenue <> 0
                                     GROUP BY   agent_id";
               
                $end_shift = $this->selectSingle($individual_query);

                if ($end_shift) {
                    $this->loop_total_revenue = $this->loop_total_revenue + $end_shift->revenue;
                }

                $individual_app_status[] = $end_shift;
                
                $counter++;
            }
            
        }
        return $individual_app_status;
    }

    public function prepare_query_params($date)
    {

        $begin_date = $date;

        $start_shift_hours = array();
        $end_shift_hours = array();

        $begin = $begin_date;

        $end_date = date('Y-m-d', strtotime($begin_date . "+1 days"));

        
        for ($i = 15; $i <= 24; $i++) {
            $start_shift_hours[] = $i.':00:00';
        }

        for ($i = 1; $i <= 5; $i++) {
            $end_shift_hours[] = $i.':00:00';
        }

        $dates = array(
                'start_shift_date' => $begin,
                'end_shift_date' => $end_date,
                'start_shift_hours' => $start_shift_hours,
                'end_shift_hours' => $end_shift_hours
            );

        return $dates;
        
    }

    /**
     * Construct query for getting the phonenumbers
     * based on the customers response on the survey
     *
     * @param $params array
     *
     * return String
     */
    public function constructQuery($params, $page, $limitation = false)
    {
        $filterQuery = '';

        $limit = 1000;

        // calculate start
        $start = ($page - 1) * $limit;

        $is_limited = ($limitation) ? '': "LIMIT $start, $limit";
        
        $filterQuery .= 'SELECT SQL_CALC_FOUND_ROWS answered_group.ag_id, answered_group.phone, answered_questions.question_id, answered_questions.answer FROM `answered_group`, `answered_questions` WHERE
                          answered_group.ag_id = answered_questions.answer_group';
        

        // Check if the questions and choices have values.
        if (!empty($params['questions']) && !empty($params['choices'])) {
            $count =count($params['questions']);

            $filterQuery .= ' AND (';

            for ($i = 0; $i < $count; $i++) {
                // craate filter in the query.
                if ($i > 0) {
                    $filterQuery .= ' AND ';
                }
                $filterQuery .= '(answered_questions.question_id = '.$params['questions'][$i].' AND answered_questions.answer = '.$params['choices'][$i].')';
            }
            $filterQuery .= ')';
        }

        if (!empty($params['post-code'])) {
            $filterQuery .= ' AND (';
                $filterQuery .= "answered_group.details like '%{$params['post-code']}%'";
            $filterQuery .= ')';
        }

        $filterQuery   .= " GROUP BY answered_group.phone $is_limited";

        $applicaions = $this->select($filterQuery);
        $totalNumbers = $this->selectSingle('SELECT FOUND_ROWS() as total');

        return array(
                'phonenumbers'  => $applicaions,
                'totalResult'   => $totalNumbers->total,
                'NumberOfPages' => ceil($totalNumbers->total/1000),
                'query'         => $filterQuery
               );
    }

    public function getQuestionsCollection()
    {
        /* Questions */
        $sql = 'SELECT questions.*
                FROM questions
                LEFT JOIN questions AS parent_question ON parent_question.id = questions.parent
                ORDER BY `order`';
        
        $questions = $this->select($sql);
        
        $QuestionsCollection = array();
        $choicesCollectionRaw = array();
        $choices = $this->prepareChoices();

        foreach ($questions as $key => $value) {
            $choicesCollectionRaw = array();
            $choicesItems = unserialize($value->choices);

            foreach ($choicesItems as $choiceskey => $item) {
                $choicesCollectionRaw[] = $choices[$item];
            }

            $QuestionsCollection[] = array(
                    'choices'   => base64_encode(json_encode($choicesCollectionRaw)),
                    'questions' => $value->question,
                    'code'      => $value->code,
                    'id'        => $value->id
                );
        }

        return $QuestionsCollection;
    }

    public function prepareChoices()
    {
        /* Choices */
        $sql = 'SELECT * FROM choices';

        $choices = $this->select($sql);

        $choicesList = array();

        foreach ($choices as $key => $value) {
           $choicesList[$value->choices_id] = $value;
        }
        return $choicesList;
    }

    public function generate_response($start, $end)
    {   
        $answered_group = "SELECT ag_id
                           FROM answered_group
                           WHERE answered_group.interview_started_date BETWEEN '$start' AND '$end'";

        $answers = $this->select($answered_group);


        $return = array();
        
        foreach ($answers as $value) {
            $return[] = $value->ag_id;
        }

        $ids = implode(',', $return);


        // get all answers with positive responses
        $get_answers = "SELECT  question_id, count(aq_id) as total, SUM(amount) as amount FROM answered_questions WHERE answer_group IN ($ids) AND amount <> 0 GROUP BY question_id";

        $answers = $this->select($get_answers);

        $answered_questions = array();

        foreach ($answers as $key => $value) {
            
            $answered_questions[$value->question_id] = $value;    

        }

        return $answered_questions;
    }

    public function get_all_questions()
    {
        $get_questions = "SELECT question, code, id FROM questions WHERE status <> 0";

        return $this->select($get_questions);   
    }

    public function construct_response($response, $questions)
    {
        $constructed_response = array();

        foreach ($questions as $key => $value) {
            
            if(isset($response[$value->id])){

                $constructed_response[] = array_merge((array)$response[$value->id], (array)$value);
            }
                
        }

        return $constructed_response;
    }
}
