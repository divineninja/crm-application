<?php
class application_Model extends Model {
	public function __construct(){		parent::__construct();	}	
	public function get_revenue(){
		$output = array();
		$agents = $this->get_agent();
		foreach($agents as $agent){
			$groups = $this->get_user_application($agent->agent_id);
			$revenue = 0;
			foreach($groups as $group){
				$revenue += $group->revenue;
			}
			$output[] = (object)array(
				'agent_name' => $agent->first_name. ' '.$agent->last_name,
				'revenue' => $revenue,
				'number_of_applications' => count($groups)
			);
		}
		return $output;
	}
		public function get_group_details($group){		return $this->select("SELECT answered_questions.answer_group, questions.amount,questions.id,questions.question,answered_questions.answer,choices.label,agent.* FROM `answered_questions` 				LEFT JOIN questions ON answered_questions.question_id = questions.id 				LEFT JOIN choices ON choices.choices_id = answered_questions.answer 				LEFT JOIN answered_group ON answered_group.ag_id = answered_questions.answer_group				LEFT JOIN agent ON `answered_group`.`agent_id` = agent.agent_id 				WHERE answer_group = '7'");	}	
		public function get_group_revenue($group,$id){		$sql = "SELECT SUM(answered_questions.amount) as total_revenue, answered_questions.answer_group				FROM `answered_questions` 				LEFT JOIN questions ON answered_questions.question_id = questions.id				LEFT JOIN answered_group ON answered_group.ag_id = answered_questions.aq_id				WHERE answered_questions.answer_group = '$group'";		return $this->select($sql);			}		public function get_agent(){		return $this->select("SELECT * FROM agent");	}		public function get_user_application($user){		return $this->select("SELECT * FROM  `answered_group` WHERE agent_id = '$user' ");	}
	
	public function get_user_application_by_datetime($from,$to){	
		$sql = "SELECT COUNT(ag_id) as number_of_applications,SUM(revenue) as revenue, `agent`.`first_name`, `agent`.`last_name`,`answered_group`.agent_id FROM  `answered_group` 
				LEFT JOIN `agent` ON `agent`.`agent_id`  = `answered_group`.`agent_id`
				WHERE `date` BETWEEN '$from' AND '$to' GROUP BY  `answered_group`.agent_id";		return $this->select($sql);	}
	
	public function get_user_application_by_datetime_and_id($from,$to,$id){	
		$sql = "SELECT * FROM  `answered_group` 
				WHERE `date` BETWEEN '$from' AND '$to' AND `answered_group`.agent_id = '$id' ORDER BY ag_id";
		return $this->select($sql);
	}
}