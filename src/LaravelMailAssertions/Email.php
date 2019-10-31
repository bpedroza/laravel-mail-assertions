<?php

namespace Bpedroza\LaravelMailAssertions;

/**
 * This class represents a sent email.
 * Some helper methods here to use with assertions
 */
class Email
{
    protected $message = '';
    protected $to = '';
    protected $subject = '';
    protected $from = '';

    /**
     * Build the email and set the message
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * Check if the email message contains a string
     * @param  string $needle
     * @return bool
     */
    public function contains(string $needle): bool
    {
        return $needle && strpos($this->message, (string)$needle) !== false;
    }

    /**
     * Get the email body
     * @return string
     */
    public function getBody(): string
    {
        return $this->message;
    }

    /**
     * Check if the email has a given recpient
     * @param  string  $email
     * @return bool
     */
    public function hasRecipient(string $email): bool
    {
        return $this->to == $email;
    }

    /**
     * Check if the email has a given subject
     * @param  string  $subject
     * @return bool
     */
    public function hasSubject(string $subject): bool
    {
        return $this->subject == $subject;
    }

    /**
     * Check if the email is from a given email
     * @param  string $email
     * @return bool
     */
    public function isFrom(string $email): bool
    {
        return $this->from == $email;
    }

    /**
     * use magic __call to set properties we use for testing
     * @param  string $method
     * @param  array $args
     * @return Email
     */
    public function __call($method, $args)
    {
        if (property_exists($this, $method) && isset($args[0])) {
            $this->{$method} = $args[0];
        }

        return $this;
    }
}
