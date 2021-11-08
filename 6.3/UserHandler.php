<?php
require_once "Database.php";
require_once "User.php";
require_once "Image.php";

/**
 * User handler class.
 * @author Viggo Lagertedt Ekholm
 */
class UserHandler extends Database
{
    /**
     * Get the segments we want to modify.
     * @return false|string[]
     */
    function getPieces()
    {
        //Get the segments from the html file we want to modify.
        $html = file_get_contents("form.html");
        return explode("<!--===entries===-->", $html);
    }

    /**
     * Print users.
     * @param string $piece
     * @param User $user
     * @param int $index
     */
    function print_entry(string $piece, User $user, int $index)
    {
        $temp = str_replace('---no---', $index, $piece);
        $temp = str_replace('---image_src---', 'LoadImage.php?ID=' . $user->getID(), $temp);
        $temp = str_replace('---time---', $user->getDate(), $temp);
        $temp = str_replace('---name---', $user->getName(), $temp);
        $temp = str_replace('---homepage---', $user->getWebsite(), $temp);
        $temp = str_replace('---email---', $user->getEmail(), $temp);
        $temp = str_replace('---comment---', $user->getComment(), $temp);
        echo $temp;
    }

    /**
     * Insert user into database.
     * @param User $user
     * @param Image $image
     * @throws Throwable
     */
    function insert(User $user, Image $image)
    {
        //Check for upload error.
        if ($image->getImage()['error'] === 0) {
            //Limit the image size since I won't allow huge images into my database.
            if ($image->getImage()['size'] < 1000000) {
                if ($this->conn->connect_error) {
                    die('Connection Failed: ' . $this->conn->connect_error);
                } else {
                    //Begin transaction.
                    $this->conn->begin_transaction();

                    try {
                        $stmtUser = $this->conn->prepare("insert into user(Name, Email, Website, Comment, Date) values (?,?,?,?,?)");

                        //Gather the date and time.
                        date_default_timezone_set("Europe/Stockholm");
                        $date = date('Y-m-d H:i:s');

                        $name = $user->getName();
                        $email = $user->getEmail();
                        $website = $user->getWebsite();
                        $comment = $user->getComment();

                        //Bind the variables to the prepared statement.
                        $stmtUser->bind_param("sssss", $name, $email, $website, $comment, $date);

                        //Execute the query. If this fails, rollback!
                        if (!$stmtUser->execute()) {
                            $this->conn->rollback();
                            echo "Failed to insert name/email/website/comment/date! " . $stmtUser->error;;
                        }

                        //Get the user inserted ID, this will be used to insert image for this user.
                        $UserID = $this->conn->insert_id;

                        $stmtImage = $this->conn->prepare("insert into image(Image, MimeType, userID) values(?,?,?)");

                        $fileType = $image->getMime();
                        $image_data = file_get_contents($image->getImage()['tmp_name']);

                        $stmtImage->bind_param("ssi", $image_data, $fileType, $UserID);

                        //Execute the query. If this fails, rollback!
                        if (!$stmtImage->execute()) {
                            $this->conn->rollback();
                            echo "Failed to insert image! " . $stmtImage->error;
                        }

                        //No errors, commit the transaction.
                        $this->conn->commit();
                    } catch (\Throwable $e) {
                        // An exception has been thrown
                        // We must rollback the transaction
                        $this->conn->rollback();
                        throw $e; // but the error must be handled anyway
                    }
                    //Close the streams.
                    $stmtImage->close();
                    $stmtUser->close();
                }
            } else {
                echo "File size is too big!";
            }
        } else {
            echo "Error uploading file!";
        }
    }

    /**
     * Get users from the database.
     */
    public function get()
    {
        if ($this->conn->connect_error) {
            die('Connection Failed: ' . $this->conn->connect_error);
        } else {
            $sql = "SELECT * FROM user ";
            $result = $this->conn->query($sql);

            echo $this->getPieces()[0];

            if ($result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    $user = new User();
                    $user->setID($row["ID"]);
                    $user->setName($row["Name"]);
                    $user->setEmail($row["Email"]);
                    $user->setWebsite($row["Website"]);
                    $user->setComment($row["Comment"]);
                    $user->setDate($row["Date"]);

                    $this->print_entry($this->getPieces()[1], $user, $i++);
                }
            } else {
                echo "No entries found in database.";
            }

            echo $this->getPieces()[2];
        }
    }
}