<?php
function hasValues($required){
  //Check all required fields if they are missing values.
  foreach($required as $field){
    if(empty($_POST[$field])){
      return false;
    }
  }
  return true;
}
?>
