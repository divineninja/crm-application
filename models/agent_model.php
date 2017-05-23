<?php

class agent_Model extends Model {

    function __construct()
    {
        parent::__construct();
    }

    public function insert_object($data)
    {
        $this->insert('agent', $data);
        $this->addActivity('added '.$data['first_name']. ' '.$data['last_name'].'('.$data['agent_number'].') as new agent last');
        echo json_encode(array(
            'status' => 'ok',
            'code' => 200,
            'message' => 'User Successfully Registered.',
        ));
    }


    public function get_object_detail($id)
    {
        return $this->selectSingle("SELECT * FROM agent WHERE agent_id = '$id'");

    }



    public function update_object($params)
    {
        // update user
        $this->update('agent', $params, "agent_id = {$params['agent_id']}");
        $this->addActivity('updated information of '.$params['first_name']. ' '.$params['last_name'].'('.$params['agent_number'].') last');
        echo json_encode(
            array(
                'status' => 'ok',
                'code' => 200,
                'message' => 'User Successfully Updated.',
            )
        );

    }

    public function get_objects()
    {
        return $this->select("SELECT * FROM agent");
    }



    public function delete_object($ids = array())
    {
        foreach ($ids as $id) {
            $this->delete('agent', "agent_id = $id");
        }
    }
	
    public function get_raw_application_by_date($current_date = "",$to_date =""){
		
        if ($current_date == "" && $to_date == "") {
            $current_date = date('Y-m-d 00:00', time());
            $to_date = date('Y-m-d 23:59', strtotime($current_date . ' + 1 day'));
            
        }
		
		$hourdiff = round((strtotime($to_date) - strtotime($current_date))/3600, 1);
            
        $sql = "SELECT count(AG.ag_id) as app_count, SUM(AG.revenue) as revenue, SUM(AG.status) as finished_app,
        (count(AG.agent_id)-SUM(AG.status)) as unfinished_app,
        (SUM(AG.status)/count(AG.ag_id))*100 as completion_rate,
        agent.first_name, agent.last_name, agent.agent_number,`agent`.`agent_id`
        FROM `answered_group` as AG
        LEFT JOIN `agent` ON `agent`.`agent_id` = AG.agent_id
        WHERE `AG`.`date` BETWEEN '$current_date' AND '$to_date' AND AG.revenue > 0
        GROUP BY AG.agent_id ORDER BY revenue DESC,completion_rate DESC,finished_app DESC";
    
        $monitor = $this->select($sql);
        
        $list = array();
		
        foreach ($monitor as $agent) {
            $list[] = (object)array(
                'agent_number' => $agent->agent_number,
                'first_name' => $agent->first_name,
                'last_name' => $agent->last_name,
                'finished_app' => $agent->finished_app,
                'unfinished_app' => $agent->unfinished_app,
                'app_count' => $agent->app_count,
                'sph' => ($agent->app_count/$hourdiff),
                'revenue' => $agent->revenue,
                'revenue_average' => ($agent->revenue/$hourdiff),
                'hour' => $hourdiff,
                'completion_rate' => $agent->completion_rate
            );
        }
        return $list;
    }
	
	
    public function get_raw_application_by_dateV2($current_date = "",$to_date =""){
		
        if ($current_date == "" && $to_date == "") {
            $current_date = date('Y-m-d 00:00', time());
            $to_date = date('Y-m-d 23:59', strtotime($current_date . ' + 1 day'));
            
        }
		
		$hourdiff = round((strtotime($to_date) - strtotime($current_date))/3600, 1);
		
		 $sql = "SELECT count(AG.ag_id) as app_count, SUM(AG.revenue) as revenue, SUM(AG.status) as finished_app,
				(count(AG.agent_id)-SUM(AG.status)) as unfinished_app,
				(SUM(AG.status)/count(AG.ag_id))*100 as completion_rate,
				agent.first_name, agent.last_name, agent.agent_number,`agent`.`agent_id`
				FROM `answered_group` as AG
				LEFT JOIN `agent` ON `agent`.`agent_id` = AG.agent_id
				WHERE `AG`.`date` BETWEEN '$current_date' AND '$to_date'
				GROUP BY AG.agent_id ORDER BY revenue DESC,completion_rate DESC,finished_app DESC";
		
        $monitor = $this->select($sql);
       
		
        $list = array();
		
        foreach ($monitor as $agent) {
            $list[] = (object)array(
                'agent_number' => $agent->agent_number,
                'first_name' => $agent->first_name,
                'last_name' => $agent->last_name,
                'finished_app' => $agent->finished_app,
                'unfinished_app' => $agent->unfinished_app,
                'app_count' => $agent->app_count,
                'sph' => ($agent->app_count/$hourdiff),
                'revenue' => $agent->revenue,
                'revenue_average' => ($agent->revenue/$hourdiff),
                'hour' => $hourdiff,
                'completion_rate' => $agent->completion_rate
            );
        }
		
        return $list;
    }
	
	public function get_monitoring_data($current_date = "",$to_date = "")
    {
		if ($current_date == "" && $to_date == "") {
            $current_date = date('Y-m-d 00:00', time());
            $to_date = date('Y-m-d 23:59', strtotime($current_date . ' + 1 day'));
            
        }
		
		$hourdiff = round((strtotime($to_date) - strtotime($current_date))/3600, 1);
		
			$sql = "SELECT 
					agent.last_name,
					agent.first_name,
					agent.agent_number,
					sum(answered_group.revenue) as revenue ,
					count(answered_group.ag_id) as app_count,
					count(CASE WHEN answered_group.status = 1 then 1 ELSE NULL END ) as finished_app,
					count(CASE WHEN answered_group.status = 0 then 0 ELSE NULL END ) as unfinished_app				
					FROM `answered_group` 
					LEFT JOIN `agent` ON `agent`.`agent_id` = `answered_group`.`agent_id`
					LEFT JOIN user ON answered_group.validator = user.id
					WHERE `date` BETWEEN '$current_date' AND '$to_date'
					GROUP BY answered_group.agent_id
					ORDER BY revenue DESC";
		
        $monitor = $this->select($sql);
        
        $list = array();
		
        foreach ($monitor as $agent) {
            $list[] = (object)array(
                'agent_number' => $agent->agent_number,
                'first_name' => $agent->first_name,
                'last_name' => $agent->last_name,
                'finished_app' => $agent->finished_app,
                'unfinished_app' => $agent->unfinished_app,
                'app_count' => $agent->app_count,
                'sph' => ($agent->app_count/$hourdiff),
                'revenue' => $agent->revenue,
                'revenue_average' => ($agent->revenue/$hourdiff),
                'hour' => $hourdiff,
                'completion_rate' => sprintf("%0.1f",($agent->finished_app/$agent->app_count)*100),
            );
        }
		
        return $list;
		
		
    }
    
    public function get_revenue_by_id($group)
    {
        $sql = "SELECT answered_questions.* , choices.amount, SUM( choices.amount ) AS revenue
                FROM  `answered_group` 
                LEFT JOIN answered_questions ON answered_questions.answer_group = answered_group.ag_id
                LEFT JOIN choices ON choices.choices_id = answered_questions.answer
                WHERE answered_questions.answer_group = '$group'
                GROUP BY answered_questions.answer_group";
        return $this->selectSingle($sql);
    }
}
