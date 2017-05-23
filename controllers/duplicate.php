<?php

class duplicate extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->view->render('duplicate/index');
    }

    public function dl_record()
    {
        $dateDownload = date('ymdhis');
        
        header('Content-Type: "application/csv"; charset=utf-8; encoding=utf-8');
        header("Content-Disposition: attachement; filename='coaching-{$dateDownload}.csv';");

        $f = fopen('php://output', 'w');

        // header
        fputcsv($f, $this->model->constructHeader());

        $records = $this->model->get_records();

        $record_data = array();

        foreach ($records as $key => $value) {
            $record_data = array(
                    $value->first_name.' '.$value->last_name,
                    $value->phone,
                    date('F d Y h:i a', strtotime($value->date)),
                );
            fputcsv($f, $record_data);
        }
        die();
    }

    /**
     * Migrate Iddle applications
     *
     * Migrate incomplete applications based on date
     *
     * @param $dateRange base64_encoded generated from form using ajax
     *
     * @package CRM 3.3
     * @author  Reylimjr <junreyjr1029@gmail.com>
     * @version 3.3
     */
    public function migrate()
    {
        if($this->model->checkMigrationTable()) {
            $this->model->do_migration();
        } else {
            $this->model->create_migration_table();
            $this->model->do_migration();
        }

    }

    public function front_migrate()
    {
        $current_date = date('Y-m-d', time());
        $this->view->current_date = date('Y-m-d', strtotime($current_date . ' - 1 month'));
        $this->view->to_date = $current_date;
        $this->view->render('duplicate/migrate_form', true);
    }

    public function duplicate_report_dl()
    {
        echo '<pre>';
        print_r($this->model->generate_duplication_apps());
    }
}
