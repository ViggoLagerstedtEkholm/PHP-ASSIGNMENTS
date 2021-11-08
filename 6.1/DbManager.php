<?php

/**
 *  This class handles read and write functionality for MongoDB.
 *  @author Viggo Lagerstedt Ekholm
 */

class DbManager
{
    private $conn;
    private $dbname = '6.1';
    private $collection = 'visits';

    function __construct(){
        //Connect to MongoDB server (localhost with port 27017).
        $this->conn = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    }

    /**
     * Insert a visitor into the database.
     */
    function insert(){
        //Get current date and time and device information.
        $date = date('Y-m-d H:i:s');
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $remote_addr = $_SERVER['REMOTE_ADDR'];

        //Define the user array.
        $user1 = array(
            'Tid' => $date,
            'REMOTE_ADDR' => $remote_addr,
            'REMOTE_USER_AGENT' => $user_agent
        );

        //Create new BulkWrite instance.
        $single_insert = new MongoDB\Driver\BulkWrite();

        //Insert user.
        $single_insert->insert($user1);

        //Execute to collection.
        $this->conn->executeBulkWrite("$this->dbname.$this->collection", $single_insert);
    }

    /**
     * Read all users from the collection.
     * @throws \MongoDB\Driver\Exception\Exception
     */
    function read(){
        //No filters, no options.
        $filter = [];
        $option = [];

        //New query instance.
        $read = new MongoDB\Driver\Query($filter, $option);

        //Execute this query.
        $all_users = $this->conn->executeQuery("$this->dbname.$this->collection", $read);

        //Echo all fetched users.
        foreach ($all_users as $user) {
            echo 'Tid: ' . $user->Tid . "\n";
            echo 'REMOTE_ADDR: ' . $user->REMOTE_ADDR . "\n";
            echo 'REMOTE_USER_AGENT: ' . $user->REMOTE_USER_AGENT . "\n";
            echo "\n";
        }
    }
}