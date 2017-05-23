<?php

class reports extends Controller{

    public function __construct() 
    {
        parent::__construct();
    }

    public function index()
    {
        $current_date = date('Y-m-d', time());
        $this->view->current_date = $current_date;
        $this->view->to_date = date('Y-m-d', strtotime($current_date . ' + 1 day'));
        $this->view->render('reports/index');
    }

    public function raw_applications()
    {
        $from = $_GET['from_date'].' '.$_GET['from_time'].':00';
        $to = $_GET['to_date'].' '.$_GET['to_time'].':00';
        echo json_encode($this->model->get_raw_application_by_date($from, $to));
    }
    
    public function appplication($id)
    {
        $this->view->details = $this->model->get_application_details($id);
		
        $this->view->render('reports/details', true);
    }
    
    public function download()
    {
        $from = $_GET['from_date'].' '.$_GET['from_time'].':00';
        $to = $_GET['to_date'].' '.$_GET['to_time'].':00';
        $file_name = date('YmdH:i:s', time());
        $this->view->current_time = date('H:i', time());
		
		$this->model->get_contents($from, $to);
		
        $this->model->array_to_csv_download(
            array(
                array($this->model->get_headers()),
                    $this->model->get_contents($from, $to)
                ),
            "report_$file_name.csv"
        );
    }
    
    public function get_contents()
    {
        $from = $_GET['from_date'].' '.$_GET['from_time'].':00';
        $to = $_GET['to_date'].' '.$_GET['to_time'].':00';
		
        echo json_encode($this->model->get_contents($from, $to));
    }
    
    public function get_headers()
    {
        echo json_encode($this->model->get_headers());
    }
    
    public function delete_application($id)
    {
		$role = strtolower($_SESSION['role']);
		
		if($this->view->can_delete($role)) {
			$this->model->delete('answered_group', "ag_id = '$id'");
			$this->model->delete('answered_questions', "answer_group = '$id'");
		} else {
			$this->view->render('error/restricted',true);
		}
    }
    
    public function qa_crm($id)
    {
        $processStart = microtime(true);
        
        $this->view->applications = $this->model->get_application($id);
        $this->view->agents = $this->model->get_agents();
        $this->view->users = $this->get_users();

        $this->model->change_status($id, 'Validating', $_SESSION['user_data']->id);

        $processEnd = microtime(true);
        $this->view->processTime = $processEnd - $processStart;
        $this->view->render('reports/qacrm');
    }

    public function agent()
    {
        $this->view->render('reports/agent-template');
    }

    public function get_agent_hourly_monitoring($date)
    {
        $this->view->applications = $this->model->get_application_by_agent($date);
        $this->view->params = $this->model->prepare_query_params($date);
        $this->view->render('reports/agent', true);
    }
    
    public function history($id)
    {
        $this->view->application = $this->model->get_history($id);
        $this->view->render('reports/history', true);
    }
    
    public function get_users()
    {
        return $this->model->select("SELECT * FROM user");
    }
    
    public function hourly()
    {
        $this->view->render('reports/hourly');
    }
    
    public function getReportsByHour()
    {
        // $items = (object)unserialize(base64_decode($date));

        // get the reports per hour between 2 dates
        // starting from $startDate until $endDate
        // $startDate = '2014-05-05'; format: YYYY-MM-DD
        // $endDate = '2014-05-06';   format: YYYY-MM-DD
        
        $items = (object)$_POST;
        
        echo json_encode($this->model->getReportsbyHour($items));
        
    }

    /**
     * Filter 
     *
     * Filter phone numbers by costumer response.
     *
     * @param urlParams serialize array
     * 
     * @return array phoneNumbers
     */
    public function filter()
    {
        $this->view->questions = $this->model->getQuestionsCollection();
        $this->view->render('reports/filter');
    }

    /**
     * Filter 
     *
     * Filter phone numbers by costumer response.
     *
     * @param urlParams serialize array
     * 
     * @return array phoneNumbers
     */
    public function phoneCollectionFilter()
    {
        $page = isset($_GET['page']) ? $_GET['page']: 1;
        // prepare query
        echo json_encode($this->model->constructQuery($_POST, $page));
        // return query

    }

    public function downloadFilter()
    {
        $phones = $this->model->constructQuery($_GET, 0, true);
        
        header('Content-Type: "application/csv"; charset=utf-8; encoding=utf-8');
        header('Content-Disposition: attachement; filename="filteredPhonesNumbers.csv";');
        $f = fopen('php://output', 'w');
        foreach ($phones['phonenumbers'] as $fields) {
            fputcsv($f, array($fields->phone));
        }
    }


    public function positive_response()
    {
        // view for positive response
        $this->view->render('reports/positive_response');
    }

    public function generate()
    {
        $post = $_POST;

        $start = $post['start_date'];
        $end = $post['end_date'];
        
        // get all questions with positive responses.
        $generate_response = $this->model->generate_response($start, $end);

        // get all enabled questions
        $generate_questions = $this->model->get_all_questions();

        $this->view->responses = $this->model->construct_response($generate_response, $generate_questions);

        $this->view->params = $post;
        $this->view->render('reports/response/display', true);
    }

    public function download_response()
    {
        $post = $_POST;

        $start = $post['start_date'];
        $end = $post['end_date'];
        
        // get all questions with positive responses.
        $generate_response = $this->model->generate_response($start, $end);

        // get all enabled questions
        $generate_questions = $this->model->get_all_questions();

        $responses = $this->model->construct_response($generate_response, $generate_questions);

        $name = "positiveResposeReport({$start}-{$end})";
        header('Content-Type: "application/csv"; charset=utf-8; encoding=utf-8');
        header('Content-Disposition: attachement; filename="'.$name.'.csv";');
        $f = fopen('php://output', 'w');

        fputcsv($f, array('Code', 'Question', 'Positive Responses', 'Total Amount'));

        foreach ($responses as $value) {
            $response = (object)$value;
            fputcsv($f, array($response->code, $response->question, $response->total, $response->amount));
        }
    }

    public function download_xfactor()
    {
        $from = $_GET['from_date'].' '.$_GET['from_time'].':00';
        $to = $_GET['to_date'].' '.$_GET['to_time'].':00';
        $file_name = date('x_factorYmdH:i:s', time());
        $this->view->current_time = date('H:i', time());
        
        $this->model->array_to_csv_download(
            array(
                array(
                    $this->model->get_headers_x_factor()),
                    $this->model->get_contents_x_factor($from, $to)
                   ),
            "report_$file_name.csv"
        );
    }
}
