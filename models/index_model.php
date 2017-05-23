<?php
class index_Model extends Model
{

    function __construct()
    {
        parent::__construct();
    }
    
    public function check_database()
    {
        $sth = $this->prepare("show tables");
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_OBJ);
    }

    public function enableUpdateTable()
    {
        $columns = $this->select("SHOW COLUMNS FROM questions");
        $numberOfColumns = count($columns);
        if ($numberOfColumns == 16) {
            return true;
        } else {
            return false;
        }
    }

    public function checkNotifTable()
    {
        $columns = $this->select("SHOW COLUMNS FROM app_stat");

        if ($columns) {
            return false;
        } else {
            return true;
        }
    }

}
