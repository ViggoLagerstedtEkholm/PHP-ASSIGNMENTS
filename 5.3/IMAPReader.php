<?php

/**
 * This class will handle all IMAP API calls and provide help to fetch the email from the mail server.
 * @author Viggo Lagestedt Ekholm
 */
class IMAPReader
{
    public mixed $connection;
    public array $inbox;
    public int $msg_cnt;
    public string $server;
    public string $user;
    public string $pass;
    public int $port;

    public function __construct(string $username, string $password, string $server, int $port)
    {
        $this->user = $username;
        $this->pass = $password;
        $this->port = $port;
        $this->server = $server;

        //Connect to the database and populate the inbox.
        $this->connect();
        $this->inbox();
    }

    /**
     * This method connects to the database using the server-name/username/password/port.
     */
    public function connect()
    {
        $full_server_name = '{' . $this->server . ':' . $this->port . '/imap/ssl/novalidate-cert}';
        $this->connection = imap_open($full_server_name, $this->user, $this->pass) or die('Cannot connect to Mail: ' . imap_last_error());
    }

    /**
     * Empty the inbox.
     */
    public function close()
    {
        $this->inbox = array();
    }

    /**
     * This method will iterate over all the messages the user has in the mail box and add them to the 2D array.
     */
    public function inbox()
    {
        $this->msg_cnt = imap_num_msg($this->connection);

        //Create 2D array, populate it with all emails in the server. Using the IMAP api we can fetch different information.
        $in = array();
        for ($i = 1; $i <= $this->msg_cnt; $i++) {
            $in[] = array(
                'index' => $i,
                'header' => imap_headerinfo($this->connection, $i),
                'body_raw' => imap_body($this->connection, $i),
                'body_text' => imap_fetchbody($this->connection, $i, 1),
                'body_html' => imap_fetchbody($this->connection, $i, 2),
                'structure' => imap_fetchstructure($this->connection, $i)
            );
        }

        $this->inbox = $in;
    }

    /**
     * This method will help to get the inbox index value.
     * @param int|null $msg_index
     * @return array
     */
    public function GetMessageAtIndex(int $msg_index = NULL): array
    {
        if (count($this->inbox) <= 0) {
            return array();
        } else if (!is_null($msg_index) && isset($this->inbox[$msg_index])) {
            return $this->inbox[$msg_index];
        }
        return $this->inbox[0];
    }

    /**
     * Get the total amount of messages in the inbox.
     * @return int
     */
    public function GetMessageCount(): int
    {
        return $this->msg_cnt;
    }
}
