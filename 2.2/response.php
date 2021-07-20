<?php
header('Content-type: text/plain');

//Input names.
$inputs = array("name", "password", "email", "checkbox", "select_items");

//Loop through the input names and echo the method type followed by the input value.
foreach($inputs as $input)
{
  if(isset($_POST[$input]))
  {
    echo "METHOD TYPE: POST \n";
    echo $input . ": " . $_POST[$input] . "\n";
    echo "\n";
  }
  else if(isset($_GET[$input]))
  {
    echo "METHOD TYPE: GET \n";
    echo $input . ": " . $_GET[$input] . "\n";
    echo "\n";
  }
}

?>
