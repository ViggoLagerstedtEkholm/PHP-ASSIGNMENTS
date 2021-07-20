<?php
$filePath = "visitCount.txt";

if(file_exists($filePath)){
    readAndWriteFile($filePath);
  }else{
   createFile($filePath);
}

function readAndWriteFile($path){
   //Open the specified file and enable
   $file = fopen($path, "a+");

   //Aquire exclusive lock (writer).
   if(flock($file, LOCK_EX)){

     //Read the content of the file.
     $visitCount = fread($file, filesize($path));

     //Increment count.
     $visitCount = $visitCount + 1;

     //Display the visit count after incrementing it.
     $html = file_get_contents("counter.html");
     $html = str_replace('---$hits---', $visitCount, $html);
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
   }else{
     echo "Could not get the lock!";
   }
   fclose($file);
}

function createFile($path){
    $createFile = fopen("visitCount.txt", "w");
    file_put_contents($path, "0");
    //Refresh site after file is created.
    header('Refresh:0');
}
?>
