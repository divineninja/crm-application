<?php
/**
 * Activity Clas
 *
 * Give crm the ability to track the 
 * activities happening in the account.
 *
 * PHP VERSION 5
 *
 * @category  PHP
 * @package   CRM
 * @author    Rey Lim Jr <junreyjr1029@gmail.com>
 * @copyright 2013 TELEQUEST BPO
 * @license   GPLv2 http://gplv2.org
 * @link      null
 * @since     2.0
 */

/**
 * Activity
 *
 * Enable the crm to record all activities in the application,
 * This module records, Login, questions, choices, users, agents.
 *
 * @category  PHP
 * @package   CRM
 * @author    Rey Lim Jr <junreyjr1029@gmail.com>
 * @copyright 2013 TELEQUEST BPO
 * @license   GPLv2 http://gplv2.org
 * @link      null
 * @since     2.0
 */
class Activity extends Controller
{

    public function __construct()
    {
        parent::__construct();

    }//end __construct()


    public function index()
    {
        $this->view->types = $this->activityTypes();
        $this->view->activities = $this->model->getActivities();
        $this->view->render('activity/index');

    }//end index()

    /**
     * Filter Activity
     *
     * Get activities filtered by its type
     *
     * @param String $keyWord Type of activity to filter.
     *
     * @author Rey Lim Jr <junreyjr1029@gmail.com>
     * @return void
     */
    public function filterActivity($keyWord)
    {
        $this->view->types = $this->activityTypes($keyWord);
        $keyWord = base64_decode($keyWord);
        $this->view->keyWord = $keyWord;
        $this->view->activities = $this->model->getActivitiesBy($keyWord);
        $this->view->render('activity/index');

    }

    /**
     * Create Activity types collection
     *
     * Create a collection of array consist 
     * of types used in activity log.
     *
     * @param String $type Active type(Default: all).
     *
     * @author Rey Lim Jr <junreyjr1029@gmail.com>
     * @return ARRAY
     */
    public function activityTypes($type = 'all')
    {
        // Create login key.
        $logged_in = base64_encode('logged in');
        // Create question key.
        $question = base64_encode('question');
        // Create choices key.
        $choices = base64_encode('choices');
        // Create choices key.
        $agent = base64_encode('agent');
        // Create user key.
        $user = base64_encode('user');

        // Return collection of navigations.
        return array(
                'all' => (object)array(
                           'icon'   => 'fa-home',
                           'url'    => $this->view->set_url('activity'),
                           'title'  => 'All',
                           'active' => ($type === 'all')? 'active': false
                          ),
                'loggedIn' => (object)array(
                           'icon'   => 'fa-key',
                           'url'    => $this->view->set_url('activity/filterActivity/'.$logged_in),
                           'title'  => 'Logged In',
                           'active' => ($type === $logged_in)? 'active': false
                          ),
                'question' => (object)array(
                           'icon'   => 'fa-question',
                           'url'    => $this->view->set_url('activity/filterActivity/'.$question),
                           'title'  => 'Questions',
                           'active' => ($type === $question)? 'active': false
                          ),
                'choices' => (object)array(
                           'icon'   => 'fa-pencil',
                           'url'    => $this->view->set_url('activity/filterActivity/'.$choices),
                           'title'  => 'Choices',
                           'active' => ($type === $choices)? 'active': false
                          ),
                'agent' => (object)array(
                           'icon'   => 'fa-eye-slash',
                           'url'    => $this->view->set_url('activity/filterActivity/'.$agent),
                           'title'  => 'Agent',
                           'active' => ($type === $agent)? 'active': false
                          ),
                'user' => (object)array(
                           'icon'   => 'fa-users',
                           'url'    => $this->view->set_url('activity/filterActivity/'.$user),
                           'title'  => 'Users',
                           'active' => ($type === $user)? 'active': false
                          ),
               );

    }//end activityTypes()


    /**
     * Activity Login
     *
     * Records the user login in the crm account.
     * 
     * @author Rey Lim Jr <junreyjr1029@gmail.com>
     * @return void
     */
    public function activityLogin()
    {
        if (isset($_SESSION['login_stat']) === true) {
            $userData = $_SESSION['user_data'];

            $date = date('D, F d Y, h:i:s a');

            $this->model->insert_activity(
                array(
                 'user_id' => $userData->id,
                 'action'  => sprintf($userData->first_name.' '.$userData->last_name.' Logged In last '. $date),
                )
            );
            unset($_SESSION['login_stat']);
            header('Location: '. $this->view->set_url());
        }//end if

    }
}//end class
