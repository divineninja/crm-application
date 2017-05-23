<?php

class Controller {

    function __construct()
    {
        $this->view = new View();
    }


    public function loadModel($name)
    {
        $path = 'models/' .$name. '_model.php';
        $path = dirname(dirname(__FILE__)).'/'. $path;
        
        if (file_exists($path)) {

            require 'models/' .$name. '_model.php';

            $modelName = $name .'_Model';
            
            $this->model = new $modelName();
        } else {
            echo "Model Not found! Please check the file name and try again.";
        }

    }
}
