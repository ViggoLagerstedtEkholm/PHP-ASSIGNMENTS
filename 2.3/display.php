<?php
/**
 *  This script takes an uploaded file and checks if no errors occurred and that the file does not exceed the size limit.
 *  If we have jpg, png, text - display those (with the right headers).
 *  Any other formats we just display name, type, size.
 *  @author Viggo Lagerstedt Ekholm
 */

if (isset($_POST["publish"])) {
    //Array of acceptable MIME-types.
    $accepted_types = array("text/plain", "image/jpeg", "image/png");

    //Gather file attributes.
    $file = $_FILES['file'];
    $fileType = $_FILES['file']['type'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileErr = $_FILES['file']['error'];
    $fileTmpName = $_FILES['file']['tmp_name'];

    $fileExt = explode('/', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    //Check if file upload had any errors.
    if ($fileErr === 0) {
        //Enable max file size.
        if ($fileSize < 10000000) {
            //Create file path information.
            $fileNameNew = uniqid('', true) . "." . $fileActualExt;
            $fileDst = 'uploads/' . $fileNameNew;

            //Upload images to server.
            move_uploaded_file($fileTmpName, $fileDst);

            //Show the appropriate file.
            switch ($fileType) {
                case "text/plain":
                    header('Content-type: text/plain');
                    $text = fopen($fileDst, "r") or die("Unable to open file!");
                    echo fread($text, filesize($fileDst));
                    fclose($text);
                    break;
                case "image/jpeg":
                    header('Content-type: image/jpeg');
                    readfile($fileDst);
                    break;
                case "image/png":
                    header('Content-type: image/png');
                    readfile($fileDst);
                    break;
                default:
                    echo "name: " . $fileName . ", ";
                    echo "type: " . $fileType . ", ";
                    echo "size: " . $fileSize . ".";
            }
        } else {
            echo "Whoosh, that's a big file! Choose another one that is smaller!";
        }
    } else {
        echo "Error uploading file!";
    }
}
