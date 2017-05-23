<?php

class question_stat_model extends Model
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_app()
    {
        $sql = "SELECT 
                app_stat.id as stat_id,
                agent.first_name as a_fname,
                agent.last_name as a_lname,
                answered_group.phone as phone,
                answered_group.date as app_date,
                answered_group.ag_id as ag_id,
                app_stat.duplicate,
                app_stat.duplicate_ids
                FROM app_stat, agent, answered_group
                WHERE app_stat.agent_id = agent.agent_id 
                AND app_stat.app_id = answered_group.ag_id
                ORDER BY app_stat.id DESC
                LIMIT 1000";
                
        $apps = $this->select($sql);
        $app_collection = array();
        foreach ($apps as $key => $value) {

            $ids = unserialize($value->duplicate_ids); 
            $value->duplicate_ids = $ids;
            $app_collection[] = $value;
        }

        return $app_collection;
    }
}