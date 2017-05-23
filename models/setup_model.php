<?php
class setup_Model extends Model {

    function __construct()
    {
        parent::__construct();
    }
    
    public function setup_starting()
    {
        $this->create_activity();                           // create activity table
        $this->create_agent();                              // create agent table
        $this->create_ag();                                 // create answer group table
        $this->create_answered_group_history();             // create answered group history table
        $this->create_answered_questions();                 // create answered group questions table
        $this->create_answered_questions_history();         // create answered group questions history table
        $this->create_choices();                            // create choices table
        $this->create_follow_up_question_validation();      // create follow up questions validation table
        $this->create_groups();                             // create groups table
        $this->create_logic_follow_up_questions();          // create logic follow up questions table
        $this->create_questions();                          // create questions table
        $this->create_site();                               // create site table
        $this->create_user();                               // create user table (deprecated)
        $this->alter_qcode();                               // create user table (deprecated)
        $this->alter_is_required();							// alter table add is_required field
        
        // Insert data
        $this->insert_groups();                             // insert groups
        $this->insert_site_informations();                  // insert site information
    }
    
    public function create_activity()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `activity` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `action` text NOT NULL,
          `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
        $sth->execute();
    }
    
    public function create_agent()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `agent` (
          `agent_id` int(11) NOT NULL AUTO_INCREMENT,
          `first_name` varchar(25) NOT NULL,
          `last_name` varchar(25) NOT NULL,
          `agent_number` varchar(11) NOT NULL,
          `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`agent_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;");
        $sth->execute();
    }
    
    public function create_ag()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `answered_group` (
          `ag_id` int(11) NOT NULL AUTO_INCREMENT,
          `status` varchar(30) NOT NULL,
          `agent_id` int(11) NOT NULL,
          `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `phone` varchar(20) NOT NULL,
          `details` text NOT NULL,
          `interview_started_date` date NOT NULL,
          `interview_started_time` time NOT NULL,
          `revenue` float(10,2) NOT NULL,
          `remarks` text NOT NULL,
          `validation_status` varchar(25) NOT NULL,
          `validator` int(11) NOT NULL,
          PRIMARY KEY (`ag_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;");
        $sth->execute();
    }
    
    public function create_answered_group_history()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `answered_group_history` (
          `ag_id_history` int(11) NOT NULL AUTO_INCREMENT,
          `ag_id` int(11) NOT NULL,
          `status` varchar(30) NOT NULL,
          `agent_id` int(11) NOT NULL,
          `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `phone` varchar(20) NOT NULL,
          `details` text NOT NULL,
          `interview_started_date` date NOT NULL,
          `interview_started_time` time NOT NULL,
          `revenue` float(10,2) NOT NULL,
          `remarks` text NOT NULL,
          `validation_status` varchar(25) NOT NULL,
          `validator` int(11) NOT NULL,
          PRIMARY KEY (`ag_id_history`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;");
        $sth->execute();
    }
    
    
    public function create_answered_questions()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `answered_questions` (
          `aq_id` int(11) NOT NULL AUTO_INCREMENT,
          `question_id` int(11) NOT NULL,
          `answer` varchar(50) NOT NULL,
          `answer_group` int(11) NOT NULL,
          `amount` float(11,2) NOT NULL,
          `label` varchar(50) NOT NULL,
          PRIMARY KEY (`aq_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=536 ;");
        $sth->execute();
    }
    
    public function create_answered_questions_history()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `answered_questions_history` (
              `aq_id_history` int(11) NOT NULL AUTO_INCREMENT,
              `aq_id` int(11) NOT NULL,
              `question_id` int(11) NOT NULL,
              `answer` varchar(50) NOT NULL,
              `answer_group` int(11) NOT NULL,
              `amount` float(11,2) NOT NULL,
              `label` varchar(50) NOT NULL,
              `ag_id_history` int(11) NOT NULL,
              PRIMARY KEY (`aq_id_history`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=283 ;");
        $sth->execute();
    }
    
    public function create_choices()
    {
        $sth = $this->prepare("
            CREATE TABLE IF NOT EXISTS `choices` (
              `choices_id` int(11) NOT NULL AUTO_INCREMENT,
              `label` text NOT NULL,
              `amount` float NOT NULL,
              PRIMARY KEY (`choices_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=153 ;
            ");
        $sth->execute();
    }
    
    public function create_follow_up_question_validation()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `follow_up_question_validation` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `reference_question` int(11) NOT NULL,
              `display_question` int(11) NOT NULL,
              `answer` int(11) NOT NULL,
              `operator` varchar(20) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;");
        $sth->execute();
    }
    
    public function create_groups()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `groups` (
              `group_id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(25) NOT NULL,
              `status` int(11) NOT NULL,
              `author` varchar(25) NOT NULL,
              `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `parent` int(11) NOT NULL,
              `postalCode` varchar(20) NOT NULL,
              PRIMARY KEY (`group_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;");
        $sth->execute();
    }
    
    public function create_logic_follow_up_questions()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `logic_follow_up_questions` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `parent_question` int(11) NOT NULL,
              `display_question` int(11) NOT NULL,
              `answer` int(11) NOT NULL,
              `condition` varchar(15) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;");
        $sth->execute();
    }
    
    public function create_questions()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `questions` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `question` text NOT NULL,
          `choices` text NOT NULL,
          `type` varchar(20) NOT NULL,
          `group` int(11) NOT NULL,
          `parent` int(11) NOT NULL,
          `status` varchar(3) NOT NULL,
          `order` int(11) NOT NULL,
          `condition` text NOT NULL,
          `child` text NOT NULL,
          `conditional_answer` text NOT NULL,
          `code` int(11) NOT NULL,
          `paid_response` text NOT NULL,
          `amount` float(10,2) NOT NULL,
          `ex_postal_codes` text NOT NULL,
          `in_postal_codes` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;");
        $sth->execute();
    }
        
    public function create_site()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `site` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(30) NOT NULL,
          `value` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;");
        $sth->execute();
        
    }
    
    public function create_user()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `user` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `first_name` varchar(32) NOT NULL,
          `last_name` varchar(25) NOT NULL,
          `username` varchar(25) NOT NULL,
          `password` varchar(32) NOT NULL,
          `role` varchar(20) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3;");
        $sth->execute();
    }

    public function notification()
    {
        $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `app_stat` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `agent_id` int(11) NOT NULL,
              `app_id` int(11) NOT NULL,
              `duplicate` int(11) NOT NULL,
              `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `duplicate_ids` text NOT NULL,
              PRIMARY KEY (`id`)
              ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4;");

        $sth->execute();
    }

    public function create_advise()
    {
        $coaching = $this->prepare("CREATE TABLE IF NOT EXISTS `coaching` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `coaching_id` char(20) NOT NULL,
              `tl_id` int(11) NOT NULL,
              `agent_id` int(11) NOT NULL,
              `app_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `phone` char(15) NOT NULL,
              `parent` int(11) NOT NULL,
              `overall_comment` text NOT NULL,
              PRIMARY KEY (`id`),
              KEY `coaching_id` (`coaching_id`,`phone`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2;");

        $coaching->execute();

       $meta = $this->prepare("CREATE TABLE IF NOT EXISTS `coaching_meta` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `status` int(1) NOT NULL,
        `remarks` text NOT NULL,
        `score` int(11) NOT NULL,
        `criteria` int(11) NOT NULL,
        `coaching_id` int(11) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

        $meta->execute();
    }
    
       
    public function insert_groups()
    {
        $item1 = array(
            'group_id' => 1,
            'name' => 'Primary', 
            'status' => 1, 
            'author' => 'reylimjr',
            'postalCode' => 'logical'
        );
        
        $item2 = array(
            'group_id' => 2,
            'name' => 'Secondary', 
            'status' => 1, 
            'author' => 'reylimjr',
            'postalCode' => 'secondary'
        );
        
        $this->insert('groups', $item1);
        $this->insert('groups', $item2);
        
    } 
    
    public function insert_site_informations()
    {
        
        // enable insert text option
        $this->insert('site', array(
            'title' => 'opening_text_option',
            'value' => 1
        ));
        // enable insert text option
        $this->insert('site', array(
            'title' => 'legal_text_option',
            'value' => 1
        ));
        // enable insert text option
        $this->insert('site', array(
            'title' => 'optin_text_option',
            'value' => 1
        ));
        // enable insert text option
        $this->insert('site', array(
            'title' => 'closing_text_option',
            'value' => 1
        ));
        
    }

    public function updateDatabase()
    {
        $sth = $this->prepare("ALTER TABLE  `questions` ADD  `max_apps` INT NOT NULL");

        $sth->execute();
    }
       
    public function indexSite()
    {
      // sample index code
      
      // index answer_group
      $sth = $this->prepare("ALTER TABLE  `answered_group` ADD INDEX (  `status` ,  `agent_id` ,  `date` ,  `phone` ,  `interview_started_date` , `interview_started_time` ,  `revenue` ,  `validation_status` ,  `validator` );");
      $sth->execute();

      // index answered_questions
      $sth = $this->prepare("ALTER TABLE  `answered_questions` ADD INDEX (  `aq_id` ,  `question_id` ,  `answer` ,  `answer_group` , `amount` ,  `label` )");
      $sth->execute();

      //index logic follow up questions
      $sth = $this->prepare("ALTER TABLE  `logic_follow_up_questions` ADD INDEX (  `parent_question` ,  `display_question` ,  `answer` ,  `condition` )");
      $sth->execute();

      // index follow up question validation
      $sth = $this->prepare("ALTER TABLE  `follow_up_question_validation` ADD INDEX (  `reference_question` ,  `display_question` ,  `answer` ,  `operator` )");
      $sth->execute();

      // index supression
      $sth = $this->prepare("ALTER TABLE  `supression` ADD INDEX (  `phone` ,  `question_id`)");
      $sth->execute();

      // index supression
      $sth = $this->prepare("ALTER TABLE  `activity` ADD INDEX (  `user_id` ,  `action` ,  `date` )");
      $sth->execute();

    } 

    public function registerSupression()
    {
      // register suppression
       $sth = $this->prepare("CREATE TABLE IF NOT EXISTS `supression` (
                              `suppression_id` int(11) NOT NULL AUTO_INCREMENT,
                              `phone` varchar(15) DEFAULT NULL,
                              `question_id` int(11) NOT NULL,
                              PRIMARY KEY (`suppression_id`),
                              KEY `phone` (`phone`,`question_id`)
                            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

        $sth->execute();

        $question = $this->prepare("ALTER TABLE  `questions` ADD  `enable_supression` INT NOT NULL");

        $question->execute();
    }
	
    public function alter_qcode()
    {
		// Alter Qcode
        $question = $this->prepare("ALTER TABLE  `questions` CHANGE  `code`  `code` VARCHAR( 11 ) NOT NULL");

        $question->execute();
    }
	
    public function alter_is_required()
    {
		// Alter Qcode
        $question = $this->prepare("ALTER TABLE  `questions` ADD  `is_required` INT NOT NULL");

        $question->execute();
    }
	
    public function updateAgent()
    {
		// Alter Qcode
        $question = $this->prepare("ALTER TABLE `agent`  ADD `status` INT NOT NULL,  ADD `last_login` TIMESTAMP NOT NULL,  ADD `ip` VARCHAR(20) NOT NULL");

        $question->execute();
    }
}