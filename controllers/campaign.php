<?php
/**
 * Campaign switcher
 *
 * Enable the application to switch crm 
 * account to another without logging out. 
 * This module gives effeciency for the QA that
 * is using different account at the same time.
 *
 * PHP VERSION 5
 *
 * @category  PHP
 * @package   CRM
 * @author    Rey Lim Jr <junreyjr1029@gmail.com>
 * @copyright 2013 TELEQUEST BPO
 * @license   GPLv2 http://gplv2.org
 * @link      null
 * @since     3.0
 */

class Campaign extends Controller
{
    /**
     * Copy traits from parent class
     *
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @return  void
     * @since   2.0
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

    }//end __construct()

    /**
     * Index
     *
     * Main method used by this framework to 
     * make a display for front-end users.
     *
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @package TELEQUEST CRM
     * @since   Version 3.0
     * @return  String
     */
    public function index()
    {
        die('Access denied!');
    }


    /**
     * Display the drop down for campaign changer.
     *
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @package TELEQUEST CRM
     * @since   Version 3.0
     * @return  void
     */
    public function campaignAccounts()
    {
        // Get campaign lists.
        $this->view->campaigns = $this->model->getAccounts();

        // Generate the drop down HTML template.
        $this->view->render('campaign/dropdown', true);

    }//end campaignAccounts()


    /**
     * Switch campaign
     *
     * @author  Rey Lim Jr <junreyjr1029@gmail.com> 
     * @package TELEQUEST CRM
     * @since   Version 3.0
     * @return  void
     */
    public function switchCampaign()
    {
        // Sanitize POST
        $_SESSION['campaign']   = unserialize(base64_decode($_POST['campaign']));
        $_SESSION['login_stat'] = 1;
        // Bring user back to Home page.
        header('location:'. $this->view->set_url());

    }//end switchCampaign()
}//end class
