<?php
require_once "Database.php";
require_once "User.php";

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
     */
    public function insert(User $user)
    {
        if ($this->conn->connect_error) {
            die('Connection Failed: ' . $this->conn->connect_error);
        } else {
            //Use prepared statements to avoid SQL-injections.
            $stmt = $this->conn->prepare("INSERT INTO USER(Name, Email, Website, Comment, Date) values (?,?,?,?,?)");

            //Gather the date and time.
            date_default_timezone_set("Europe/Stockholm");
            $date = date('Y-m-d H:i:s');

            $name = $user->getName();
            $email = $user->getEmail();
            $website = $user->getWebsite();
            $comment = $user->getComment();

            //Bind the variables to the prepared statement.
            $stmt->bind_param("sssss", $name, $email, $website, $comment, $date);
            //Execute the query.
            $stmt->execute();
            $stmt->close();
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
            $sql = "SELECT * FROM `user`";
            $result = $this->conn->query($sql);

            echo $this->getPieces()[0];

            if ($result->num_rows > 0) {
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    $user = new User();
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