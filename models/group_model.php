<?php
class group_Model extends Model {
	function __construct() {
        parent::__construct();
    }
	public function insert_object( $data ){
		$this->insert('groups',$data);
		echo json_encode(array(
				'code' => 200,
				'message' => 'User Successfully Registered.'
			));
		return;
	}	
	public function get_object_detail( $id ){
		return $this->selectSingle("SELECT * FROM groups WHERE group_id = '$id'");
	}
	
	public function update_object($params){
		$this->update('groups', $params, "group_id={$params['group_id']}");
		echo json_encode(array(
			'status' => 'ok',
			'code' => 200,
			'message' => 'Question Successfully Updated.'
		));
	}

	
		return $this->select("SELECT * FROM groups");
	}
	
		// update user
		$this->update('groups', $params, "group_id = {$params['group_id']}");
		echo json_encode(array(
				'status' => 'ok',
				'code' => 200,
				'message' => 'User Successfully Updated.'
			));
	}
		return $this->select("SELECT * FROM groups");
	}
		foreach($ids as $id){
			$this->delete( 'groups', "group_id = $id" );
		}
	}
}