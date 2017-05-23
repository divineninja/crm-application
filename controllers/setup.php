<?php
class setup extends Controller{
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $this->view->render('setup/start');
    }
    
    public function start_setup()
    {
        $this->model->setup_starting();
        $this->supression();
        $this->notification();
        $this->alter_qcode();
        $this->indexSite();
        $this->updateDatabase();
        $this->updateAgent();
        
        echo json_encode(array(
            'message' => 'success',
            'code' => 400,
            'status' => 'ok'
        ));
    }

    public function advise()
    {
        $this->model->create_advise();
        //header('location: '. URL.'advise');
    }

    public function updateDatabase()
    {
        $this->model->updateDatabase();
        //header('location: '. URL);
    }

    public function import()
    {
        $fetch_uri = file_get_contents($this->view->set_url('../main-crm/user/get'));   

        $users = json_decode($fetch_uri);
        
        foreach ($users as $value) {

            $value->username = $value->phone;

            unset($value->phone);
            unset($value->status);

            $user = (array)$value;

            $this->model->insert('user', $user);
        }

        header('location: '. $this->view->set_url());
    }

    public function supression()
    {
        $this->model->registerSupression();
        
        //header('location: '. $this->view->set_url());
    }

    public function notification()
    {
        $this->model->notification();
        //header('location: '. URL);
    }

    public function indexSite()
    {
        $this->model->indexSite();
        //header('location: '. URL);
    }
	
    public function updateAgent()
    {
        $this->model->updateAgent();
        //header('location: '. URL);
    }
	
	public function alter_qcode()
	{
		$this->model->alter_qcode();
		
		//echo "<h1>Successfully updated question table.</h1> \n <a href='{$this->view->set_url()}'><< Back</a>";
	}
}
