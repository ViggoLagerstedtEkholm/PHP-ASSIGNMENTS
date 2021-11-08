<?php
require_once "Database.php";

/**
 * Image handler class.
 * @author Viggo Lagertedt Ekholm
 */
class ImageHandler extends Database
{
    /**
     * Read the GET parameter.
     */
    function loadID()
    {
        if (isset($_GET["ID"])) {
            $this->showImage($_GET["ID"]);
        } else {
            echo "ID not set!";
        }
    }

    /**
     * Print the image to the screen.
     * @param int $ID
     */
    function showImage(int $ID)
    {
        //Select the image row from the current user.
        $sql = "SELECT * FROM image WHERE userID = ?;";

        $stmt = $this->conn->prepare($sql);
        //Bind the variables to the prepared statement.
        $stmt->bind_param("i", $ID);
        //Execute the query.
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result !== false && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $Image = $row["Image"];
                $Mime = $row["MimeType"];

                //Set the header.
                header('Content-Type: ' . $Mime);

                //Display image.
                echo $Image;
            }
        } else {
            //Update HTML document with empty text.
            echo "No entries found in database.";
        }
    }
}