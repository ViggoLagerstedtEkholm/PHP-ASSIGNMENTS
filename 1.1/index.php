<?php
header('Content-type: text/plain');
//File count name.
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
    $file = fopen($path, "r+");

    //Acquire exclusive lock (writer).
    if (flock($file, LOCK_EX)) {

        //Read the content of the file.
        $visitCount = fread($file, filesize($path));

        //Increment count.
        $visitCount += 1;

        //Truncate
        ftruncate($file, 0);

        //Write pointer to beginning of selected file.
        rewind($file);

        //Write updated count to server file.
        fwrite($file, $visitCount);

        // flush output before releasing the lock
        fflush($file);

        //Display the visit count after incrementing it.
        echo $visitCount;

        //Release the exclusive lock.
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

