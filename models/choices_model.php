<?php
class choices_Model extends Model {

    function __construct() {
        parent::__construct();
    }

    public function insert_object($data)
    {
        $this->insert('choices', $data);
        $this->addActivity('added '.$data['label'].' as new choices');
        echo json_encode(array(
                'status' => 'ok',
                'code' => 200,
                'message' => 'User Successfully Registered.'
            ));
        return;
    }
    
    public function get_object_detail($id)
    {
        return $this->selectSingle("SELECT * FROM choices WHERE choices_id = '$id'");
    }
    
    public function insert_bulk_upload($data)
    {
    
        $postcode = (OBJECT)explode(",",$data['name']);
        
        foreach ($postcode as $code) {
            $post_code = array(
                'label' => $code
            );
            
            $this->insert('choices', $post_code);
            $this->addActivity('added '.$post_code['label'].' as new choices');
        }
        echo json_encode(array(
                'status' => 'ok',
                'code' => 200,
                'message' => 'User Successfully Registered.'
            ));
        return;
    }
    
    public function update_object($params)
    {
        $this->update('choices', $params, "choices_id={$params['choices_id']}");
        $this->addActivity('updated '.$params['label']. ' in choices.');
        echo json_encode(array(
            'status' => 'ok',
            'code' => 200,
            'message' => 'Question Successfully Updated.'
        ));
    }

    
    /*
	public function get_objects()
    {
        return $this->select("SELECT * FROM choices");
    }
	*/
	
	public function get_objects($page, $search = null)
    {
		$limit = 500;
		$current_page = $page;
		
		$search_query = '';
		$offset = ($current_page - 1) * $limit;
		
		if($search) {
			$search_query = "AND (choices.label LIKE '%{$search}%')";
			$offset = (1 - 1) * $limit;
		}
		
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM choices
				WHERE 1 = 1 {$search_query}
				GROUP BY choices.choices_id
				LIMIT {$offset}, {$limit}";
				
		$choices = $this->select($sql);
		$total = $this->selectSingle('SELECT FOUND_ROWS() as total')->total;
		
		$result = new stdClass();
		
		$result->choices = $choices;
		$result->total = $total;
		$result->pages = ceil($total/$limit);
		$result->sql = $sql;
		$result->page = $page;
		
        return $result;
    }
    
    public function update_group($params)
    {
        // update user
        $this->update('choices', $params, "choices_id = {$params['choices_id']}");
		
        echo json_encode(array(
                'status' => 'ok',
                'code' => 200,
                'message' => 'User Successfully Updated.'
            ));
    }
    
    public function get_groups()
    {
        return $this->select("SELECT * FROM choices");
    }

    public function delete_object($ids = array() ){
        foreach ($ids as $id) {
            $this->addActivity('deleted '.$id. ' in choices.');
            $this->delete('choices', "choices_id = $id");
        }
    }
    
}