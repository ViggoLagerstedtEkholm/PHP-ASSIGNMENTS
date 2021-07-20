<?php
if (isset($_GET['file'])) {
  $file = $_GET['file'];
  $path = "attachment/" . $file;

  if(!file_exists($path)){ // file does not exist
      die('file not found');
  } else {
      header("Content-Disposition: attachment; filename=$path");
      header("Content-Type: application/octet-stream");
      header("Content-Transfer-Encoding: binary");

      // read the file from disk
      readfile($path);
  }
}
?>
