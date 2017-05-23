<?php
/**
 * Get the difference between two leads
 *
 * Additional functions in filtering the leads,
 * this not really included in the crm function.
 *
 * PHP VERSION 5
 *
 * @package TeleQuest BPO
 * @author  Rey Lim Jr <junreyjr1029@gmail.com>
 * @version 3.0
 */

/**
 * Leads Difference
 *
 * Get the unique phone numbers from the 2 leads
 * that a user will load on this module.
 *
 * @package TeleQuest BPO
 * @author  Rey Lim Jr <junreyjr1029@gmail.com>
 * @version 3.0
 */
class leadsDiff extends Controller
{

    /**
     * Load the parent attributes
     *
     * @package TeleQuest BPO
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @version 3.0
     */
    public function __consctruct()
    {
        parent::__construct();
    }

    /**
     * Display user front-end
     *
     * This will display the form or the
     * user interface for the module.
     *
     * @param string $notice base64 encoded text that determine the notice from the server.
     *
     * @package TeleQuest BPO
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @version 3.0
     * @return  html
     */
    public function index($notice = '')
    {
        // Check if there is a notice set in the url.
        if (empty($notice) === true) {
            $this->view->notice = $notice;
        }//end if

        $leads_destination = dirname(dirname(__FILE__)).'/public/leads/masterLists.csv';
        if (!file_exists($leads_destination)) {
            $this->view->notice = 'Master list is not set, Please contact the developer to manually upload the master lists in the server.';
        }
        // Leads form template
        $this->view->render('leads/index');
    }

    /**
     * Validate the user uploaded file
     *
     * Accept files, validate and processs
     *
     * @package TeleQuest BPO
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @version 3.0
     * @return  html
     */
    public function validateLeads()
    {
        // Convert file to array.
        if (empty($_FILES)) {
            return;
        }//end if

        // Define the file name of the master leads.
        $leads_name = $_FILES['leads']['tmp_name'];

        // Move leads file.
        // Define the location where file will be moved.
        $leads_destination = dirname(dirname(__FILE__)).'/public/leads/'.md5($_FILES['leads']['name']).'.csv';
        if (file_exists($leads_destination)) {
            $leads_destination = dirname(dirname(__FILE__)).'/public/leads/'.md5($_FILES['leads']['name'].' '.date('ymdhis')).'.csv';
        }
        move_uploaded_file($leads_name, $leads_destination);

        // Convert the 2 leads to array.
        $leads = $this->leadsCsv2Array($leads_destination);
        $masterLeads = $this->getMasterLeads();

        // Get the difference between 2 leads.
        // array_intersect();
        if ($_POST['display_options'] == 1) {
            $difference = array_diff($masterLeads, $leads);
        } else {
            $difference = array_intersect($masterLeads, $leads);
        }//end if
        
        // Download lead differential
        $this->downloadCsv($difference, $_POST['filename'].'.csv', $_POST['display_options']);

        // remove the files from the folder to save hard disk space.
        unlink($leads_destination);
    }//end validateLeads()

    /**
     * Convert csv to array
     *
     * Get the csv from the upload folder and get the
     * convert them to array to filter the difference.
     *
     * @param String $leads File location of the csv file.
     *
     * @package TeleQuest BPO
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @version 3.0
     * @return  array
     */
    public function leadsCsv2Array($leads)
    {
        $leadsArray = array_map('str_getcsv', file($leads));

        $leadsArrayCollection = array();

        foreach ($leadsArray as $key => $value) {
            $leadsArrayCollection[] = $value[0];
        }

        return $leadsArrayCollection;
    }

    /**
     * Download CSV
     *
     * Automatically generate a differential and download the csv file.
     *
     * @param Array $leads Unique phone numbers from leads.
     *
     * @package TeleQuest BPO
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @version 3.0
     * @return  array
     */
    public function downloadCsv($leads, $filename = 'leads_differential.csv', $display_options)
    {
        header('Content-Type: "application/csv"; charset=utf-8; encoding=utf-8');
        header('Content-Disposition: attachement; filename="'.$filename.'";');

        $f = fopen('php://output', 'w');
        if ($display_options == 1) {
            fputcsv($f, array('Unique Phone Numbers'));
        } else {
            fputcsv($f, array('Duplicate Phone Numbers'));
        }
        foreach ($leads as $key => $value) {
            $newLeads = array($value);
            fputcsv($f, $newLeads);
        }//end foreach()
        
    }//end downloadCsv()


    /**
     * Get master lists leads
     *
     * @package TeleQuest BPO
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @version 3.0
     * @return  array
     */
    public function getMasterLeads()
    {
        // Construct leads file location.
        $leads_destination = dirname(dirname(__FILE__)).'/public/leads/masterLists.csv';

        if (!file_exists($leads_destination)) {
            die('Master file not found.');
        }
        // Convert file to array.
        return $this->leadsCsv2Array($leads_destination);

    }//end getMasterLeads()
}//end class()
