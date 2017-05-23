<?php

class advise_model extends Model
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function enableUpdateTable()
    {
        $columns = $this->select("SHOW COLUMNS FROM coaching");
        $numberOfColumns = count($columns);
        return ($numberOfColumns)? true: false;
    }

    public function users()
    {
        return $this->select("SELECT * FROM user GROUP BY id ORDER BY last_name");;
    }

    public function agents()
    {
        return $this->select("SELECT * FROM agent GROUP BY agent_id ORDER BY last_name");
    }

    public function get_coaching($id)
    {
        $coaching = $this->selectSingle("SELECT * FROM coaching WHERE id = '$id'");
        $coaching_meta = $this->select("SELECT * FROM coaching_meta WHERE coaching_id = '$id'");
        $coaching->meta = $coaching_meta;
        return $coaching;
    }

    public function coaching( $limit = "200", $filter = array())
    {   
        $filterQuery = '';
        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($value) {
                    if ($key == 'coaching.app_date') {
                        $filterQuery .= " AND $key >= '$value' ";
                    }else {
                        $filterQuery .= " AND $key = '$value' ";
                    }
                    }
                }
        }

        $coaching_query = "SELECT
                           coaching.*,
                           user.id as user_id,
                           agent.agent_id as agent_id,
                           `agent`.first_name as agent_fname,
                           `agent`.last_name as agent_lname,
                           `user`.first_name as user_fname,
                           `user`.last_name as user_lname
                           FROM coaching, user, agent 
                           WHERE 1 = 1
                           $filterQuery
                           AND agent.agent_id = coaching.agent_id
                           AND coaching.tl_id = user.id
                           AND coaching.parent = 0
                           ORDER BY coaching.id DESC LIMIT $limit";

        return $this->select($coaching_query);

    }

    public function get_coaching_single($id)
    {
        $child = "SELECT * FROM coaching WHERE parent = '$id'";

        return $this->select($child);
    }

    public function constructHeader()
    {
        $criteria = array(
                    'Coaching ID',
                    'TL',
                    'Agent',
                    'Date',
                    'Phone',
                    'Opening - status',
                    'Comment',
                    'Rating',
                    'Listening - status',
                    'Comment',
                    'Rating',
                    'Vocal - status',
                    'Comment',
                    'Rating',
                    'Control - status',
                    'Comment',
                    'Rating',
                    'Needs - status',
                    'Comment',
                    'Rating',
                    'Objections  - status',
                    'Comment',
                    'Rating',
                    'Knowledge  - status',
                    'Comment',
                    'Rating',
                    'Closing - status',
                    'Comment',
                    'Rating',
                    'Average',
                    'Overall Comment',
                    'Parent'
                );
        return $criteria;

    }

    public function prepare_report($date)
    {       
        $coaching_query = "SELECT
                           coaching.*,
                           user.id as user_id,
                           agent.agent_id as agent_id,
                           `agent`.first_name as agent_fname,
                           `agent`.last_name as agent_lname,
                           `user`.first_name as user_fname,
                           `user`.last_name as user_lname
                           FROM coaching, user, agent 
                           WHERE
                           coaching.app_date BETWEEN '{$date['dateStart']}' AND '{$date['dateEnd']}'
                           AND agent.agent_id = coaching.agent_id
                           AND coaching.tl_id = user.id
                           ORDER BY coaching.phone, coaching.app_date";

        $coaching_data = $this->select($coaching_query);

        // Prepare ids for getting meta
        $coaching_ids = array();

        foreach ($coaching_data as $key => $value) {
            $coaching_ids[] = $value->id; 
        }

        $ids = implode(',', $coaching_ids);

        // meta data
        $this->coaching = $coaching_data;
        $this->meta = $this->get_meta_by_ids($ids);
    }

    public function get_meta_by_ids($ids)
    {
        $metas = "SELECT * FROM coaching_meta WHERE coaching_id in ($ids)";

        $meta_data = $this->select($metas);

        $meta = array();

        foreach ($meta_data as $key => $value) {
            $meta[$value->coaching_id][] = $value;
        }

        return $meta;
    }

    public function convert_status($int)
    {
        $status = array(
                    '1' => 'Unacceptable',
                    '2' => 'Needs Improvement',
                    '3' => 'Meets Expectations',
                    '4' => 'Exceeds Expectations',
                    '5' => 'Outstanding'
                );

        return $status[$int];
    }

}