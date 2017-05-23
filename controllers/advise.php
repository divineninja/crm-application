<?php

class advise extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->criteria = array(
                                    'opening'    => 'Opening',
                                    'listening'  => 'Listening',
                                    'vocal'      => 'Vocal',
                                    'control'    => 'Control',
                                    'needs'      => 'Needs',
                                    'objections' => 'Objections',
                                    'knowledge'  => 'Knowledge',
                                    'closing'    => 'Closing'
                                );
    }

    public function add()
    {
        $this->view->criteria = $this->criteria;
        $this->view->agents = $this->model->agents();
        $this->view->users = $this->model->users();
        $this->view->render('advise/add', true);
    }

    public function followup($id)
    {
        $this->view->parent = $id;
        $this->view->coaching = $this->model->get_coaching($id);
        $this->view->criteria = $this->criteria;
        $this->view->agents = $this->model->agents();
        $this->view->users = $this->model->users();
        $this->view->render('advise/followup', true);
    }

    public function child_list($id)
    {
        $this->view->id = $id;
        $this->view->children = $this->model->get_coaching_single($id);
        $this->view->render('advise/children', true);
    }

    public function downloadForm()
    {
        $this->view->render('advise/download', true);
    }

    public function download()
    {
        $dateDownload = date('ymdhis');
        
        header('Content-Type: "application/csv"; charset=utf-8; encoding=utf-8');
        header("Content-Disposition: attachement; filename='coaching-{$dateDownload}.csv';");

        $f = fopen('php://output', 'w');

        // header
        fputcsv($f, $this->model->constructHeader());
        
        $date = $_GET;
        // initiate global variables
        $this->model->prepare_report($date);
        
        foreach ($this->model->coaching as $key => $value) {
            $coaching_collection =  $this->prepare_meta($value->id);
            $parent = ($value->parent == 0) ? 'Parent': 'Followup';
            $coaching_data = array(
                    $value->coaching_id,
                    "{$value->user_fname} {$value->user_lname}",
                    "{$value->agent_fname} {$value->agent_lname}",
                    date('Y-m-d h:i:s a', strtotime($value->app_date)),
                    $value->phone,
                    $coaching_collection[0][0],
                    $coaching_collection[0][1],
                    $coaching_collection[0][2],
                    $coaching_collection[1][0],
                    $coaching_collection[1][1],
                    $coaching_collection[1][2],
                    $coaching_collection[2][0],
                    $coaching_collection[2][1],
                    $coaching_collection[2][2],
                    $coaching_collection[3][0],
                    $coaching_collection[3][1],
                    $coaching_collection[3][2],
                    $coaching_collection[4][0],
                    $coaching_collection[4][1],
                    $coaching_collection[4][2],
                    $coaching_collection[5][0],
                    $coaching_collection[5][1],
                    $coaching_collection[5][2],
                    $coaching_collection[6][0],
                    $coaching_collection[6][1],
                    $coaching_collection[6][2],
                    $coaching_collection[7][0],
                    $coaching_collection[7][1],
                    $coaching_collection[7][2],
                    $this->average($coaching_collection),
                    $value->overall_comment,
                    $parent
                );
            
            fputcsv($f, $coaching_data);
        }
    }

    public function average($coaching_collection)
    {
        $average = 0;
        $score = 0;

        foreach ($coaching_collection as $key => $value) {
            $score += $value[2];
        }

        return $score/8;

    }

    public function prepare_meta($id)
    {
        $meta = $this->model->meta[$id];
        $metas = array();

        foreach ($meta as $key => $value) {
            $metas[] = array($this->model->convert_status($value->status), $value->remarks, $value->score); 
        }
        
        return $metas;

    }
    public function update($id)
    {
        $coaching = array(
            'coaching_id'     => $_POST['coaching_id'],
            'phone'           => $_POST['phone'],
            'tl_id'           => $_POST['tl_id'],
            'agent_id'        => $_POST['agent_id'],
            'overall_comment' => $_POST['overall_comment'],
        );
        $this->model->update('coaching', $coaching, "id = '$id'");

        $this->remove_meta($id);
        $this->prepareCoachingMeta($_POST, $id);

        echo json_encode(
            array(
                'code'    => 200,
                'message' => 'Item successfully updated.',
                'status'  => 'ok'
            )
        );
    }

    public function save()
    {   
        // Insert to coaching table.
        $coaching = array(
                'coaching_id'     => $_POST['coaching_id'],
                'phone'           => $_POST['phone'],
                'tl_id'           => $_POST['tl_id'],
                'agent_id'        => $_POST['agent_id'],
                'overall_comment' => $_POST['overall_comment'],
            );

        if (isset($_POST['parent'])) {
            $coaching['parent'] = $_POST['parent'];
        }
        // do insert.
        // get the inserted id.
        $id = $this->model->insert('coaching', $coaching);

        // insert coaching meta
        $this->prepareCoachingMeta($_POST, $id);

        //show message
        echo json_encode(
            array(
                'code'    => 200,
                'message' => 'Item successfully saved.',
                'status'  => 'ok'
            )
        );
        die();
    }

    public function remove_meta($id)
    {
        $this->model->delete('coaching_meta', "coaching_id = '$id'");
    }

    public function prepareCoachingMeta($data, $id)
    {
        $coaching_meta = array();

        foreach ($data['criteria'] as $key => $value) {
            $coaching_meta = array(
                    'status'      => $data['status'][$key],
                    'remarks'     => $data['comment'][$key],
                    'score'       => $data["rangeInput_$key"],
                    'criteria'    => $value,
                    'coaching_id' => $id
                );
            $this->model->insert('coaching_meta', $coaching_meta);
        }
    }

    public function edit($id)
    {
        $this->view->coaching = $this->model->get_coaching($id);
        $this->view->criteria = $this->criteria;
        $this->view->agents = $this->model->agents();
        $this->view->users = $this->model->users();
        $this->view->render('advise/edit', true);
    }

    public function index()
    {
        $this->view->agents = $this->model->agents();
        $this->view->users = $this->model->users();
        $this->view->params = array();

        $filter = (isset($_GET['data'])) ? $_GET['data']: array();

        if ($filter) {
            
            $sanitized = explode('&', base64_decode($filter));

            $filter = array(
                'coaching.app_date'    => $sanitized[0],
                'coaching.agent_id'    => $sanitized[1],
                'coaching.tl_id'       => $sanitized[2],
                'coaching.coaching_id' => $sanitized[3],
            );

            $this->view->params = $filter;
            $this->view->coaching = $this->model->coaching(1000, $filter);

        } else {

            $this->view->coaching = $this->model->coaching(1000);
        }
        
        $this->view->enableUpdate = $this->model->enableUpdateTable();

        $this->view->render('advise/index');
    }

    public function delete($id)
    {
        $this->model->delete('coaching', "id = '$id'");
        $this->model->delete('coaching', "Parent = '$id'");
        $this->model->delete('coaching_meta', "coaching_id = '$id'");

        header('location: '. $this->view->set_url('advise'));
    }
}
