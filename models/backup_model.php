<?php

class backup_model extends Model
{
	protected $ip = '210.213.242.92';
	protected $userName = 'remote';
	protected $password = 'PP9dQF4vJNwKw5e4';
	protected $dbName = 'branson';
	
	private $_limit;
	private $_page;
	private $_pages;
	private $_query;
	private $_total;
	
	
	public function __construct()
    {
        parent::__construct();
    }
	
	/**
	 * external connection for tranfer
	 */
	public function connect_new_database($database = '')
	{
		try {
				
			$this->main_db = new PDO("mysql:host={$this->ip}; dbname=$database", "{$this->userName}", "{$this->password}");
		
			return $this->main_db;
			
		} catch (Exception $e) {
			// die('Error : ' . $e->getMessage());
			
			return false;
		}
	}
	
	public function select_manual($sql, $array = array(), $fetchMode = PDO::FETCH_OBJ)
	{

        $sth = $this->main_db->prepare($sql);

        foreach ($array as $key => $value):
            $sth->bindValue("$key", $value);
        endforeach;

        $sth->execute();

        return $sth->fetchAll($fetchMode);
    }
	
	public function backup_answered_group($date1,$date2,$page)
	{
		
		$limit = 100;
		
		$this->_query = "SELECT SQL_CALC_FOUND_ROWS answered_group.* FROM answered_group WHERE answered_group.date BETWEEN '{$date1}' AND '{$date2}'";
		
		return $this->getData(100, $page);
	}
	
	/**
	 * external connection for tranfer
	 */
	
	public function getData( $limit = 10, $page = 1 ) {
		
		$start = microtime(true);
		
		$this->_limit   = $limit;
		$this->_page    = $page;
	 
		if ( $this->_limit == 'all' ) {
			
			$query      = $this->_query;
			
		} else {
			
			$query      = $this->_query . " LIMIT " . ( ( $this->_page - 1 ) * $this->_limit ) . ", $this->_limit";
		}
		
		$results = $this->select($query);
		
		$this->_total = $this->selectSingle('SELECT FOUND_ROWS() as total')->total;
		
		$result = new stdClass();
		
		$result->query  = $query;
		$result->page   = (int)$this->_page;
		$result->pages  = ceil($this->_total/$this->_limit);
		$result->limit  = $this->_limit;
		$result->total  = (int)$this->_total;
		
		$result->data   = $results;
		
		$time_elapsed_secs = microtime(true) - $start;
		$result->query_time = $time_elapsed_secs;
		
		return $result;
	}
	/**
	 * private $_questions;
	 * private $_time;
	 * private $_question_query;
	 *
	 */ 
	
	public function get_answer_questions($id)
	{
		$start = microtime(true);
		$result = new stdClass();
		
		$result->question_id = $id;
		$questions = $this->get_questions_array($id);
		
		$time_elapsed_secs = microtime(true) - $start;
		$result->query_time = $time_elapsed_secs;
		$result->questions = $questions;
		
		return $result;
	}
	
	public function extract_results($results)
	{
		$output = array();
	
		
		foreach($results as $key => $value)
		{
			$output[] = array(
				'answered_group' => $value,
				'answered_questions' => $this->get_questions_array($value->ag_id)
			);
		}
		
		return $output;
	}
    
	public function get_questions_array($id)
	{
		$question_query = "SELECT * FROM answered_questions WHERE answer_group = $id";
		
		return $this->select($question_query);
	}
}