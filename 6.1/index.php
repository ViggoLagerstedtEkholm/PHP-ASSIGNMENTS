<?php

/**
 * This script makes it possible to read and write user visit information into a NoSQL database.
 * My choice was to go with MongoDB.
 * To get this project working with MongoDB you will need the following:
 * 1. Download MongoDB drivers - https://pecl.php.net/package/mongodb/1.10.0/windows (select your PHP version from the DLL List).
 * 2. Download the local server - https://www.mongodb.com/try/download/community
 * 3. Edit your php.ini extensions and add - extension=mongodb
 * 4. Start your local server from the bin folder downloaded from step 2.
 *      You can do this by going into cmd in that bin folder and type "mongod", this will deploy a local server.
 * 5. If the following steps was correctly made you should have a local server on port 27017.
 * 6. Done, refresh the site and you will see Time, User agent User and Address for each page visit.
 * @author Viggo Lagerstedt Ekholm
 */

require 'DbManager.php';
header("Content-Type: text/plain");

//Create DbManager instance.
$mongodb = new DbManager;

//Insert visitor.
$mongodb->insert();

try {
    //Read visitor.
    $mongodb->read();
} catch (\MongoDB\Driver\Exception\Exception $e) {
}
