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
    $this->createInbox();
    $this->generateDataArrays();
  }

  public function connect(){
    //Compose the full server path name.
    $full_server_name = '{' .$this->server. ':'. $this->port .'/imap/ssl/novalidate-cert}';
    //Try to connect, if it fails it will print the error.
    $this->connection = imap_open('{' .$this->server. ':'. $this->port .'/imap/ssl/novalidate-cert}', $this->user, $this->pass) or die('Cannot connect to Mail: ' . imap_last_error());

    $inbox = imap_search($this->connection,'ALL');
  }

  public function close(){
    $this->inbox = array();
  }

  public function createInbox(){
    $this->msg_cnt = imap_num_msg($this->connection);
    $this->inbox = imap_search($this->connection,'ALL');
  }

  //This method will populate arrays and call for serialization of our attachment files.
  public function generateDataArrays(){
    $in = array();
    $index = 1;

    foreach($this->inbox as $email){
      //Get information from the email.
      $header_info = imap_headerinfo($this->connection, $index);
      $imap_raw = imap_body($this->connection, $index);
      $imap_text = imap_fetchbody($this->connection, $index, 1);
      $body_html = imap_fetchbody($this->connection, $index, 2);
      $structure = imap_fetchstructure($this->connection, $index);

      $paths = $this->HandleAttachments($email, $structure);

      //2D array of useful attributes.
      $in[] = array(
        'index' => $index,
        'header' => $header_info,
        'body_raw' => $imap_raw,
        'body_text' => $imap_text,
        'body_html' => $body_html,
        'structure' => $structure,
        'paths' => $paths
      );

      $index++;
    }

    $this->inbox = $in;
  }

  //This method will take any attachment to a email and serialize it to the server with a unique ID. The method returns an array of saved file names.
  public function HandleAttachments($email, $structure){
      $paths = array();
      $attachments = array();
      /* if any attachments found... */
      if(isset($structure->parts) && count($structure->parts))
      {
          for($i = 0; $i < count($structure->parts); $i++)
          {
              $attachments[$i] = array(
                  'is_attachment' => false,
                  'filename' => '',
                  'name' => '',
                  'attachment' => ''
              );

              if($structure->parts[$i]->ifdparameters)
              {
                  foreach($structure->parts[$i]->dparameters as $object)
                  {
                      if(strtolower($object->attribute) == 'filename')
                      {
                          $attachments[$i]['is_attachment'] = true;
                          $attachments[$i]['filename'] = $object->value;
                      }
                  }
              }

              if($structure->parts[$i]->ifparameters)
              {
                  foreach($structure->parts[$i]->parameters as $object)
                  {
                      if(strtolower($object->attribute) == 'name')
                      {
                          $attachments[$i]['is_attachment'] = true;
                          $attachments[$i]['name'] = $object->value;
                      }
                  }
              }

              //Find the appropriate file encoding.
              if($attachments[$i]['is_attachment'])
              {
                  $attachments[$i]['attachment'] = imap_fetchbody($this->connection, $email, $i + 1);

                  /* 3 = BASE64 encoding */
                  if($structure->parts[$i]->encoding == 3)
                  {
                      $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                  }

                  /* 4 = QUOTED-PRINTABLE encoding */
                  elseif($structure->parts[$i]->encoding == 4)
                  {
                      $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                  }
              }
          }
      }

      //This loop will save all attachments to the server attachment folder.
      foreach($attachments as $attachment)
      {
          if($attachment['is_attachment'] == 1)
          {
              $filename = $attachment['name'];
              if(empty($filename)){
                $filename = $attachment['filename'];
              }

              if(empty($filename)){
                $filename = time() . ".dat";
              }

              $folder = "attachment";

              //Create folder if it does not exist.
              if(!is_dir($folder))
              {
                   mkdir($folder);
              }
              $randomID = uniqid('', true);
              //Write file.
              $fp = fopen("./". $folder ."/" . $randomID . $filename, "w+");
              //Save the image path with unique ID.
              $paths[] =  $randomID . $attachment['filename'];
              //Write content into file.
              fwrite($fp, $attachment['attachment']);
              //Close the stream.
              fclose($fp);
          }
      }
      //return the file name of all serialized files.
      return $paths;
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

  //Returns array of emails.
  public function GetInbox(){
    return $this->inbox;
  }
  //Returns the email/message count.
  public function GetMessageCount(){
    return $this->msg_cnt;
  }
}
