<?php
class postal_Model extends Model {
	function __construct() {
        parent::__construct();
    }
	public function insert_object( $data ){
		$this->insert('postal',$data);
		echo json_encode(array(
				'code' => 200,
				'message' => 'User Successfully Registered.'
			));
		return;
	}	
	
	public function insert_bulk_upload( $data ){
	
		$postcode = (OBJECT)explode(",",$data['name']);
		
		foreach($postcode as $code){
			$post_code = array(
				'status' => 1,
				'name' => str_replace(' ', '', $code),
				'postalCode' => strtolower(str_replace(' ', '', $code)),
				'parent' => $data['parent']
			);
			
			$this->insert('postal',$post_code);
		}
		echo json_encode(array(
				'code' => 200,
				'message' => 'User Successfully Registered.'
			));
		return;
	}	
	public function get_object_detail( $id ){
		return $this->selectSingle("SELECT * FROM postal WHERE postal_id = '$id'");
	}
	
	public function update_object($params){
		$this->update('postal', $params, "postal_id={$params['postal_id']}");
		echo json_encode(array(
			'status' => 'ok',
			'code' => 200,
			'message' => 'Question Successfully Updated.'
		));
	}

	
		$sql = "SELECT post.name as parent_name,postal.* FROM postal LEFT JOIN postal as post ON postal.parent = post.postal_id";
		return $this->select($sql);
	}
	
		// update user
		$this->update('postal', $params, "postal_id = {$params['postal_id']}");
		echo json_encode(array(
				'status' => 'ok',
				'code' => 200,
				'message' => 'User Successfully Updated.'
			));
	}
		return $this->select("SELECT * FROM postal");
	}
		$items = $this->select("SELECT * FROM postal WHERE parent = '$parent'");
		$postal = array();
		if($items){
			foreach($items as $item){
				$postal[] = array(
					'postal_id' => $item->postal_id,
					'name' => $item->name,
					'postalCode' => $item->postalCode,
					'children' => $this->postal_codes($item->postal_id)
				);
			}
		}
	}
		foreach($ids as $id){
			$this->delete( 'postal', "postal_id = $id" );
		}
	}
}