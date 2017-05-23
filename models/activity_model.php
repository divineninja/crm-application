<?php
/**
 * Activity Model
 * 
 * logics and database related methods are
 * done here.
 * 
 * @author Rey Lim Jr <junreyjr1029@gmail.com>
 * @since  2.0
 */
class activity_model extends Model
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

    public function insert_activity($data)
    {
    
        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        $sth = $this->prepare("INSERT INTO {$this->activity_table} (`$fieldNames`) VALUES ($fieldValues)");

        foreach ($data as $key => $value):
            $sth->bindValue(":$key", $value);
        endforeach;

        $sth->execute();
        /* return $sth->errorInfo(); */
        return parent::lastInsertId();
    }


    /**
     * Get Activities
     *
     * get all the activities stored in
     * activities table in the database.
     *
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @package TELEQUEST CRM
     * @since   Version 3.0
     * @return  ARRAY
     */
    public function getActivities()
    {
        return $this->select('SELECT * FROM activity ORDER BY id DESC');
    }


    /**
     * Filter Activity Results
     *
     * Get activities filetered by its type.
     *
     * @param String $keyWord Type of activity to filter.
     *
     * @author  Rey Lim Jr <junreyjr1029@gmail.com>
     * @package TELEQUEST CRM
     * @since   Version 3.0
     * @return  ARRAY
     */
    public function getActivitiesBy($keyWord)
    {
        return $this->select("SELECT * FROM activity WHERE action LIKE '%$keyWord%' ORDER BY id DESC");
    }
}
