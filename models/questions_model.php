<?php
class questions_Model extends Model {

    function __construct() {
        parent::__construct();

    }

    public function insert_object( $data ){
    
        if ($this->validate_code($data['code'])) {
            echo json_encode(
                array(
                 'status' => 'error',
                 'code' => 400,
                 'message' => 'Error: Question code is already Registered. Please try another code.'
                )
            );
            return;
        }
        
        $data['choices'] = serialize($data['choices']);
        $condition = $data['condition'];
        $conditional_answer = $data['conditional_answer'];
        $child = $data['child'];
        
        unset($data['condition']);
        unset($data['conditional_answer']);
        unset($data['child']);
        
        if (isset($data['paid_response'])) {
            $data['paid_response'] = serialize($data['paid_response']);
        }
        
        $id = $this->insert('questions', $data);
        $this->insert_questions($condition, $conditional_answer, $child, $id);
        echo json_encode(
            array(
             'status' => 'ok',
             'code' => 200,
             'message' => 'Questions Successfully Registered.'
            )
        );
        
        $this->addActivity('added new question '.$data['code']);
    }
    
    public function validate_code($code)
    {
        return $this->selectSingle("SELECT * FROM questions WHERE code = '$code'");
    }
    
    public function get_object_detail($id)
    {
        return $this->selectSingle("SELECT * FROM questions WHERE id = '$id'");
    }
    
    public function insert_questions($condition, $conditional_answer, $child, $id)
    {
    
        $item_count = count($condition);
        for ($i=0; $i<$item_count; $i++) {
            if ($condition[$i]) {
                $item = $this->insert(
                    'logic_follow_up_questions',
                    array(
                     'parent_question' => $id,
                     'display_question' => $child[$i],
                     'condition' => $condition[$i],
                     'answer' => $conditional_answer[$i]
                    )
                );
            }
        }
    }

    public function update_object_order($params)
    {
        $this->update('questions', $params, "id={$params['id']}");   

        $this->addActivity('change the order of question '.$params['code']); 
    } 

    public function update_object($params)
    {
		
		if(isset($params['choices'])) {
			$params['choices'] = serialize($params['choices']);
		} else {
			$params['choices'] = '';
		}
		
        $condition = $params['condition'];
        $conditional_answer = $params['conditional_answer'];
        $child = $params['child'];
        if (isset($params['paid_response'])) {
            $params['paid_response'] = serialize($params['paid_response']);
        }
        unset($params['condition']);
        unset($params['conditional_answer']);
        unset($params['child']);
        
        $this->update('questions', $params, "id={$params['id']}");      
        $this->insert_questions($condition, $conditional_answer, $child, $params['id']);
        
        $this->addActivity('updated question '.$params['code']);
    }
    
    public function get_question_condition($id)
    {
        $sql = "SELECT logic_follow_up_questions.*, display_questions.question as display_question_statement , choices_list.label
                FROM `logic_follow_up_questions` 
                LEFT JOIN questions as parent_questions ON parent_questions.id = logic_follow_up_questions.parent_question
                LEFT JOIN questions as display_questions ON display_questions.id = logic_follow_up_questions.display_question
                LEFT JOIN choices as choices_list ON choices_list.choices_id = logic_follow_up_questions.answer
                WHERE logic_follow_up_questions.parent_question = '$id'";
        return $this->select($sql); 
    }
    
    public function postal_codes($parent)
    {
        $items = $this->select("SELECT * FROM postal WHERE parent = '$parent' ORDER BY postal_id ASC");
        $postal = array();
        if ($items) {
            foreach ($items as $item) {
                $postal[] = (object)array(
                    'postal_id' => $item->postal_id,
                    'name' => $item->name,
                    'postalCode' => $item->postalCode,
                    'children' => $this->postal_codes($item->postal_id)
                );
            }
        }
        return $postal;
    }
    
    public function get_objects($page, $search = null, $status = null)
    {
		$limit = 50;
		$current_page = $page;
		
		$search_query = '';
		
		$offset = (1 - 1) * $limit;
		
		$offset = ($current_page - 1) * $limit;
		
		if($search) {
			/* $search_query = "AND (questions.question LIKE '%{$search}%' OR questions.code LIKE '%{$search}%')"; */
			// convert basae64 to array
			
			$item_value = array();
			$keys = explode('&',base64_decode($search));

			foreach($keys as $key=>$value):
				if($value)
				{
					$item_value = explode('=',$value);
					if($item_value[1]) {
						$item_str = str_replace('+',' ',$item_value[1]);
						$search_query .= " AND {$item_value[0]} LIKE '%{$item_str}%'";
					}
				}
			endforeach;
		}
			
		
		if($status != '')
		{
			$search_query .= "AND questions.status = {$status}";
		}
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS questions.* , groups.name
				FROM questions, groups
				WHERE groups.group_id = questions.group {$search_query}
				GROUP BY questions.id 
				ORDER BY questions.`order`, questions.id asc
				LIMIT {$offset}, {$limit}";
		
		$questions = $this->select($sql);
		
		$total = $this->selectSingle('SELECT FOUND_ROWS() as total')->total;
		
		$result = new stdClass();
		
		$result->questions = $questions;
		$result->total = $total;
		$result->pages = ceil($total/$limit);
		$result->sql = $sql;
		$result->page = $page;
		
        return $result;
    }
	
    public function get_objects_V1()
    { 
		$sql = "SELECT questions . * , groups.name
				FROM questions, groups
				WHERE groups.group_id = questions.group
				GROUP BY questions.id 
				ORDER BY questions.`order`, questions.id asc";
				
        return $this->select($sql);
    }
    
    public function get_postal_codes($id)
    {
        $sql = "SELECT * FROM question_postal WHERE question='$id'";                
        $postCodes = $this->select($sql);
        $codes = array();
        foreach ($postCodes as $code) {
            $codes[] = $code->postal;
        }
        return $codes;
    }
    public function get_objects_by_id($id)
    {
        $sql = "SELECT questions.* , groups.name, parent_question.question AS parent_question
                FROM questions
                LEFT JOIN groups ON groups.group_id = questions.group
                LEFT JOIN questions AS parent_question ON parent_question.id = questions.parent
                WHERE questions.id != '$id'";
                
        return $this->select($sql);
    }


    public function delete_object($ids = array())
    {
        foreach ($ids as $id) {
            $this->delete('questions', "id = $id");
        }
    }
    
    public function insert_reference_questions($post)
    {
        $entry_count = count($post['reference_question']);
        $list = array();
        for ( $i=0; $i < $entry_count; $i++) {
            $list = array(
                "display_question" => $post['display_question'],
                "reference_question" => $post['reference_question'][$i],
                "operator" => $post['operator'][$i],
                "answer" => $post['answer'][$i]
            );
            $this->insert('follow_up_question_validation', $list);
        }
        echo json_encode(
            array(
             'status' => 'ok',
             'code' => 200,
             'message' => 'Process Complete',
             'redirect' => URL.'questions/configure/'.$post['display_question']
            )
        );
    }
    
    /****/
    public function get_choices()
    {
        $sql = "SELECT * FROM choices";
        return $this->select($sql);
    }   
    
    public function get_choices_by_id($id)
    {
        $sql = "SELECT * FROM choices WHERE choices_id = '$id'";
        return $this->select($sql);
    }   
    
    public function get_groups()
    {
        $sql = "SELECT * FROM groups WHERE status = 1";
        return $this->select($sql);
    }
    
    public function get_choice_by($id)
    {
        $sql = "SELECT * FROM choices WHERE choices_id = '$id'";
        return $this->selectSingle($sql);
    }
    
    public function get_question_by_group($id, $q_id='')
    {
        $sql = "SELECT * FROM questions WHERE id != '$q_id'";
        return $this->select($sql);
    }
    
    public function get_reference_question($id)
    {
        $sql = "SELECT 
                follow_up_question_validation.id, 
                reference.question as reference_statement,

                display.question as display_statement,
                follow_up_question_validation.answer,
                choices.label as answer_label,
                follow_up_question_validation.operator

                FROM `follow_up_question_validation` 

                LEFT JOIN questions as reference on reference.id = follow_up_question_validation.reference_question
                LEFT JOIN questions as display on display.id = follow_up_question_validation.display_question
                LEFT JOIN choices as choices on choices.choices_id = follow_up_question_validation.answer
                WHERE follow_up_question_validation.display_question = '$id'";
        return $this->select($sql);
    }

    public function execQuery($statement)
    {
        $sth = $this->prepare($statement);
        $sth->execute();
    }
    
    public function deleteSupression($question)
    {
        $this->delete('supression', "question_id = $question");
    }
}

