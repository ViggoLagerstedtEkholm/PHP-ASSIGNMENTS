<?php
/**
 * This script makes it possible to retrieve emails and download attachments.
 * A possible optimization would be to not save each image on the server on each search.
 * @author Viggo Lagerstedt Ekholm
 */

//print the form
echo getPieces()[0];

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

    //Create IMAPReader instance that we will be using to get email data for printing.
    $ImapReader = new IMAPReader($username, $password, $mailserver_host, $mailserver_port);

    if ($ImapReader->GetMessageCount() === 0) {
        echo "No emails on the server!";
    } else {
        echo "There are " . $ImapReader->GetMessageCount() . " messages \n\n\n";

        //Iterate over all messages and print the email content.
        for ($i = 0; $i < $ImapReader->GetMessageCount(); $i++) {
            $email = $ImapReader->GetMessageAtIndex($i);
            $from_address = $email['header']->fromaddress;
            $date = $email['header']->date;
            $subject = $email['header']->subject;
            $body_text = $email['body_text'];
            $paths = $email['paths'];

            //Print.
            print_entry(new Email($from_address, $date, $body_text, $subject, $paths), $i + 1);
        }
    }

    $ImapReader->close();
} else {
    echo "Enter credentials/information in the form to display your emails.";
}

/**
 * Print the email content using the html fragments.
 * @param Email $email
 * @param int $index
 */
function print_entry(Email $email, int $index)
{
    $temp = str_replace('---no---', $index, getPieces()[1]);
    $temp = str_replace('---date---', strip_tags($email->GetDate()), $temp);
    $temp = str_replace('---from---', strip_tags($email->GetFromAddress()), $temp);
    $temp = str_replace('---subject---', strip_tags($email->GetSubject()), $temp);
    $temp = str_replace('---body---', strip_tags($email->GetBodyText()), $temp);
    echo $temp;

    //Print attachments.
    if(empty($email->GetAttachments())){
        echo "No attachments in the email!";
    }else{
        foreach ($email->GetAttachments() as $attachment) {
            echo str_replace('---LINK---', $attachment, getPieces()[2]);
        }
    }

    echo getPieces()[3];
    echo getPieces()[4];
}

