<?php

/**
 * Email model containing fields for an email.
 * @author Viggo Lagestedt Ekholm
 */
class Email
{
    private string $from_address;
    private string $date;
    private string $body_text;
    private string $subject;
    private array $attachments;

    public function __construct(string $from_address, string $date, string $body_text, string $subject, array $attachments)
    {
        $this->from_address = $from_address;
        $this->date = $date;
        $this->body_text = $body_text;
        $this->subject = $subject;
        $this->attachments = $attachments;
    }

    public function GetAttachments(): array
    {
        return $this->attachments;
    }

    public function GetFromAddress(): string
    {
        return $this->from_address;
    }

    public function GetDate(): string
    {
        return $this->date;
    }

    public function GetBodyText(): string
    {
        return $this->body_text;
    }

    public function GetSubject(): string
    {
        return $this->subject;
    }
}

