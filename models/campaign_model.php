<?php
/**
 * Campaign Model
 * 
 * logics and database related methods are
 * done here.
 * 
 * @category  PHP
 * @package   CRM
 * @author    Rey Lim Jr <junreyjr1029@gmail.com>
 * @copyright 2013 TELEQUEST BPO
 * @license   GPLv2 http://gplv2.org
 * @link      null
 * @since     2.0
 */


class campaign_model extends Model
{
    
    /**
     * Copy traits from parent class
     *
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @return  void
     * @since   2.0
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get account lists.
     *
     * Fetch information from main-crm list of accounts and display 
     * here in this account. The reason I'm using this, because main-crm
     * is using a different database.
     *
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @package TELEQUEST CRM
     * @since   Version 3.0
     * @return  JSON
     */
    public function getAccounts()
    {
        // Create the request URL.
        $this->mainCrm = URL.'../main-crm/campaign/getAccounts';

        // Get the result from the request url.
        return json_decode(file_get_contents($this->mainCrm));
    }
}
