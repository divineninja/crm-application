<?php
class Model extends Database{

    var $activity_table = 'activity';

    function __construct() {	
        try{
            parent::__construct();
        } catch (Exception $e) {
            require 'views/error/database.php';
            die();
        }
    }

	public function toJson($array, $callback = ''){
        if ($callback) {
            return $callback.'('. json_encode($array). ')';
        } else {
            return json_encode($array);	
        }
	}
	/**
     * System default functions
     * 
     * ADD, EDIT, DELETE, SELECT
     *      
     */

    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_OBJ) {

        $sth = $this->prepare($sql);

        foreach ($array as $key => $value):
            $sth->bindValue("$key", $value);
        endforeach;

        $sth->execute();

        return $sth->fetchAll($fetchMode);
    }

    public function selectSingle($sql, $array = array(), $fetchMode = PDO::FETCH_OBJ)
    {
        $sth = $this->prepare($sql);

        foreach ($array as $key => $value):

                $sth->bindValue("$key", $value);

        endforeach;

        $sth->execute();

        return $sth->fetch($fetchMode);
    }

    public function insert($table, $data) {

        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");

        foreach ($data as $key => $value):
            $sth->bindValue(":$key", $value);
        endforeach;
        
        $sth->execute();
        
        $id = $_SESSION['user_data']->id;
        
		/* return $sth->errorInfo(); */
		return parent::lastInsertId();
    }
	
	public function bulk_insert($table, $fields, $data) {
		ksort($data);
		ksort($fields);
		
		$bulk_data = '';
		
		$fieldNames = implode('`, `', $fields);
		
		foreach($data as $key => $value):
			
			$bulk_array = array();
			
			foreach($fields as $field_key):
				if($field_key == 'label') {
					$bulk_array[] = (isset($value[$field_key])) ? '"'.$value[$field_key].'"': 0;
				} else {
					$bulk_array[] = (isset($value[$field_key])) ? $value[$field_key]: 0;
				}
				
			endforeach;
			
			$bulk_data .= '('.implode(',', $bulk_array) . '),';
			
		endforeach;
		
		$bulk_data = rtrim($bulk_data,',');
		
		$sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES $bulk_data");
		
		$sth->execute();
		
		return true;
	}

    public function update($table, $data, $where) {
        ksort($data);

        $fieldDetails = null;

        foreach ($data as $key => $value):
            $fieldDetails .= "`$key` = :$key, ";
        endforeach;

        $fieldDetails = rtrim($fieldDetails, ", ");

        $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");

        foreach ($data as $key => $value):
            $sth->bindValue(":$key", $value);
        endforeach;

        $sth->execute();
        
        
        $id = $_SESSION['user_data']->id;

		return $sth->errorInfo();

    }


    public function delete($table, $where, $limit = 0) {
    
        $id = $_SESSION['user_data']->id;
        
        if ($limit == 0):

            return $this->exec("DELETE FROM $table WHERE $where");
        else:
            return $this->exec("DELETE FROM $table WHERE $where LIMIT $limit");
        endif;
        
    }

	function login_user($params)
    {
        $username = $params['username'];
        $password = $params['password'];

        return $this->selectSingle("SELECT * FROM user WHERE username = '$username' AND password = '$password'");
	}

	public function insert_activity($data)
	{

        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $sth = $this->prepare("INSERT INTO {$this->activity_table} (`$fieldNames`) VALUES ($fieldValues)");

        foreach ($data as $key => $value):
            $sth->bindValue(":$key", $value);
        endforeach;

        $sth->execute();
		/* return $sth->errorInfo(); */
		return parent::lastInsertId();
	}
	
	public function get_settings($title){
        $sql = $this->prepare("SELECT value FROM site WHERE title = '$title'");
        $sql->execute();
        $title = $sql->fetch(PDO::FETCH_OBJ);       
        return ($title) ? $title->value: false;
    }

    public function addActivity($title)
    {
        $userData = $_SESSION['user_data'];

        $date = date('D, F d Y, h:i:s a');

        $this->insert_activity(
            array(
             'user_id' => $userData->id,
             'action'  => sprintf($userData->first_name.' '.$userData->last_name.' '.$title.' '. $date),
            )
        );
    }

}
