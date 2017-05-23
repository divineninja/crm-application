<?php

class duplicate_model extends Model
{
    
    public function __construct()
    {
        parent::__construct();
    }

    public function constructHeader()
    {
        $criteria = array(
                    'Name',
                    'Phone',
                    'Date'
                );
        return $criteria;
    }

    public function get_records()
    {
        $records = "SELECT app_stat . * , agent.first_name, agent.last_name, answered_group.phone
                    FROM app_stat, agent, answered_group
                    WHERE app_stat.agent_id = agent.agent_id
                    AND app_stat.app_id = answered_group.ag_id";

        return $this->select($records);
    }

    public function checkMigrationTable()
    {
        $columns = $this->select("SHOW COLUMNS FROM migration");

        if ($columns) {
            return true;
        } else {
            return false;
        }
    }

    public function do_migration()
    {
        // echo base64_encode('2014-10-1|2014-10-16');

        if(!isset($_POST)) {
            return false;
        }

        $date1 = $_POST['start'];
        $date2 = $_POST['end'];

        $apps_query = "SELECT * FROM answered_group 
                       WHERE status = 0 
                       AND answered_group.date BETWEEN '$date1' AND '$date2'";

        $apps = $this->select($apps_query);

        $ids = array();

        foreach ($apps as $key => $value) {
            $ids[] = $value->ag_id;
        }

        $ids = implode(',', $ids);

        $questions = $this->get_questions($ids);

        $migration_content = array();

        foreach ($apps as $key => $value) {
            $migration_content = array(
                    'questions' => serialize($questions[$value->ag_id]),
                    'app'       => serialize($value),
                    'date'      => $value->date
                );

            $this->insert("migration", $migration_content);
        }

        $this->delete('answered_group', "status = 0 AND answered_group.date BETWEEN '$date1' AND '$date2'");

        echo json_encode(
            array(
                'status'  => 'ok',
                'code'    => 400,
                'message' => 'All incomplete apps has been removed.'
                )
            );
    }

    public function get_questions($ids)
    {
        $questions_query = "SELECT * FROM answered_questions 
                       WHERE answer_group IN ($ids)";

        $questions = $this->select($questions_query);

        $questions_item = array();

        foreach ($questions as $key => $value) {
            $questions_item[$value->answer_group][] = serialize($value);
        }

        return $questions_item;

    }

    public function generate_duplication_apps()
    {
        /*
        SELECT ag . * 
        FROM  `answered_group` AS ag,  `answered_group` AS new_ag
        WHERE ag.phone = new_ag.phone
        LIMIT 1000
        */

        /*
        SELECT ag.*
        FROM `answered_group` as ag 
        LEFT JOIN `answered_group` as new_ag on ag.phone = new_ag.phone
        WHERE ag.phone = new_ag.phone LIMIT 1000
        */

        $duplicate_report = "SELECT ag . * 
                             FROM  `answered_group` AS ag, `answered_group` AS new_ag
                             WHERE ag.phone = new_ag.phone AND ag.ag_id <> new_ag.ag_id
                             ORDER BY ag.Phone
                             LIMIT 500";

        return $this->select($duplicate_report);
    }

    public function create_migration_table()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `migration` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `app` text NOT NULL,
              `questions` text NOT NULL,
              `date` date NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;");
        $sth->execute();
    }


}