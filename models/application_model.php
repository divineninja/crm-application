<?php
class application_Model extends Model {

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
	
	
	
	public function get_user_application_by_datetime($from,$to){	
		$sql = "SELECT COUNT(ag_id) as number_of_applications,SUM(revenue) as revenue, `agent`.`first_name`, `agent`.`last_name`,`answered_group`.agent_id FROM  `answered_group` 
				LEFT JOIN `agent` ON `agent`.`agent_id`  = `answered_group`.`agent_id`
				WHERE `date` BETWEEN '$from' AND '$to' GROUP BY  `answered_group`.agent_id";
	
	public function get_user_application_by_datetime_and_id($from,$to,$id){	
		$sql = "SELECT * FROM  `answered_group` 
				WHERE `date` BETWEEN '$from' AND '$to' AND `answered_group`.agent_id = '$id' ORDER BY ag_id";
		return $this->select($sql);
	}
}