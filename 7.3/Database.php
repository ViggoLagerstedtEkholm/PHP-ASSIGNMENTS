<?php

/**
 * Database class.
 * @author Viggo Lagertedt Ekholm
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
        $this->conn = new mysqli('localhost', 'root', '', '7.3');
    }
}