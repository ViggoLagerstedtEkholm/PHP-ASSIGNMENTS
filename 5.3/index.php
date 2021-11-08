<?php

/**
 * This script makes it possible to retrieve emails.
 * @author Viggo Lagerstedt Ekholm
 */

$pieces = getPieces();
echo $pieces[0];

/**
 * Get the segments we want to modify.
 * @return array|false
 */
function getPieces(): array|false
{
    $html = file_get_contents("form.html");
    return explode("<!--===entries===-->", $html);
}

/**
 * Check to see if all input fields are entered.
 * @return bool
 */
function hasValues(): bool
{
    //Array of required fields to get emails.
    $required = array('username', 'password', 'mailserver_host', 'mailserver_port');
    //Check all required fields if they are missing values.
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            return false;
        }
    }
    return true;
}

if (hasValues() && isset($_POST["push_button"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $mailserver_host = $_POST["mailserver_host"];
    $mailserver_port = $_POST["mailserver_port"];

    require_once('./IMAPReader.php');
    require_once('./Email.php');

    $ImapReader = new IMAPReader($username, $password, $mailserver_host, $mailserver_port);

    if ($ImapReader->GetMessageCount() === 0) {
        echo "No emails on the server!";
    } else {
        echo "There are " . $ImapReader->GetMessageCount() . " messages \n";

        //Iterate over all messages and print the email content.
        for ($i = 0; $i < $ImapReader->GetMessageCount(); $i++) {
            $email = $ImapReader->GetMessageAtIndex($i);
            $from_address = $email['header']->fromaddress;
            $date = $email['header']->date;
            $subject = $email['header']->subject;
            $body_text = $email['body_text'];

            print_entry($pieces[1], new Email($from_address, $date, $body_text, $subject), $i + 1);
        }
    }
    echo $pieces[2];

    $ImapReader->close();
} else {
    echo "Enter credentials/information in the form to display your emails.";
}

/**
 * Print the email content.
 * @param string $piece
 * @param Email $email
 * @param int $index
 */
function print_entry(string $piece, Email $email, int $index)
{
    $temp = str_replace('---no---', $index, $piece);
    $temp = str_replace('---date---', strip_tags($email->GetDate()), $temp);
    $temp = str_replace('---from---', strip_tags($email->GetFromAddress()), $temp);
    $temp = str_replace('---subject---', strip_tags($email->GetSubject()), $temp);
    $temp = str_replace('---body---', strip_tags($email->GetBodyText()), $temp);
    echo $temp;
}
