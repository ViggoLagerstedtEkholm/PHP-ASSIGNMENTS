<?php
/**
 * This script builds upon the 1.1 assignment, we search and replace the ---visits--- text.
 * @author Viggo Lagerstedt Ekholm
 */

$filePath = "visitCount.txt";

//Check existence of file, else try to create the file starting at count 0.
if (file_exists($filePath)) {
    readAndWriteFile($filePath);
} else {
    createFile($filePath);
}

/**
 * Reads the file and increments the view count and then writes to the file.
 * @param string $path
 */
function readAndWriteFile(string $path)
{
    //Open the specified file and enable
    $file = fopen($path, "a+");

    //Acquire exclusive lock (writer).
    if (flock($file, LOCK_EX)) {

        //Read the content of the file.
        $visitCount = fread($file, filesize($path));

        //Increment count.
        $visitCount += 1;

        //Display the visit count after incrementing it.
        $html = file_get_contents("counter.html");
        $html = str_replace('---visits---', $visitCount, $html);
        echo $html;

        //Truncate
        ftruncate($file, 0);

        //Write pointer to beginning of selected file
        rewind($file);

        //Write updated count to server file.
        fwrite($file, $visitCount);

        // flush output before releasing the lock
        fflush($file);

        //Release a lock(shared or exclusive).
        flock($file, LOCK_UN);
    } else {
        echo "Could not get the lock!";
    }
    fclose($file);
}

/**
 * Creates a text file and sets the view count to 0.
 * @param string $path
 */
function createFile(string $path)
{
    file_put_contents($path, 0);
    //Refresh site after file is created.
    header('Refresh:0');
}

