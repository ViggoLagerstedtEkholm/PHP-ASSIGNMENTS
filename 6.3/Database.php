<?php

/**
 * Abstract database class for managing the connection.
 * @author Viggo Lagerstedt Ekholm
 */
abstract class Database
{
    protected mysqli $conn;

    function __construct()
    {
        $this->connect_database();
    }

    function __destruct()
    {
        $this->conn->close();
    }

    /**
     * Create a mysqli database instance. (I'm using the localhost database and not the DSV database server). look in (./database/user.sql)
     */
    function connect_database()
    {
        $this->conn = new mysqli('localhost', 'root', '', '6.3');
    }
}