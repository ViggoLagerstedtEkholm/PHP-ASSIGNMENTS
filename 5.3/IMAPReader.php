<?php
//This class will handler all IMAP API calls and provide help to fetch the email from the mail server.
class IMAPReader{
  public $connection;
  public $inbox;
  public $msg_cnt;
  public $server;
  public $user;
  public $pass;
  public $port;

  public function __construct($username, $password, $server, $port){
    $this->user = $username;
    $this->pass = $password;
    $this->port = $port;
    $this->server = $server;

    //Connect to the database and populate the inbox.
    $this->connect();
    $this->inbox();
  }

  public function connect(){
    //Compose the full server path name.
    $full_server_name = '{' .$this->server. ':'. $this->port .'/imap/ssl/novalidate-cert}';
    //Try to connect, if it fails it will print the error.
    $this->connection = imap_open('{' .$this->server. ':'. $this->port .'/imap/ssl/novalidate-cert}', $this->user, $this->pass) or die('Cannot connect to Mail: ' . imap_last_error());
  }

  public function close(){
    $this->inbox = array();
  }

  public function inbox(){
    $this->msg_cnt = imap_num_msg($this->connection);

    //Create 2D array, populate it with all emails in the server.
    $in = array();
    for($i = 1; $i <= $this->msg_cnt; $i++){
      $in[] = array(
        'index' => $i,
        'header' => imap_headerinfo($this->connection, $i),
        'body_raw' => imap_body($this->connection, $i),
        'body_text' => imap_fetchbody($this->connection, $i, 1),
        'body_html' => imap_fetchbody($this->connection, $i, 2),
        'structure' => imap_fetchstructure($this->connection, $i)
      );
    }
    //Assign the inbox variable.
    $this->inbox = $in;
  }

  //This method will help to get the inbox index value, it will be used in index.php to get all emails!
  public function GetMessageAtIndex($msg_index = NULL){
    if(count($this->inbox) <= 0){
      return array();
    }else if(!is_null($msg_index) && isset($this->inbox[$msg_index])){
      return $this->inbox[$msg_index];
    }
    return $this->inbox[0];
  }

  //Returns the email/message count.
  public function GetMessageCount(){
    return $this->msg_cnt;
  }
}
