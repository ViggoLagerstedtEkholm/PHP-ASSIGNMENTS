<?php
class Email{
  private $from_address;
  private $date;
  private $body_text;
  private $subject;

  public function __construct($from_address, $date, $body_text, $subject){
    $this->from_address = $from_address;
    $this->date = $date;
    $this->body_text = $body_text;
    $this->subject = $subject;
  }

  public function GetFromAddress(){
    return $this->from_address;
  }

  public function GetDate(){
    return $this->date;
  }

  public function GetBodyText(){
    return $this->body_text;
  }

  public function GetSubject(){
    return $this->subject;
  }
}
?>
