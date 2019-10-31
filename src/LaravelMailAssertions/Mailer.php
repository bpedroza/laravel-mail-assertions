<?php

namespace Bpedroza\LaravelMailAssertions;

use Illuminate\Support\Collection;

/**
 * Mailer instance to fake the functionality of the Mail facade
 */
class Mailer
{
    /**
     * @var Collection
     */
    private $emails;

    /**
     * Initialize the messages collection
     */
    public function __construct()
    {
        $this->empty();
    }

    /**
     * Clear out the emails
     * @return void
     */
    public function empty()
    {
        $this->emails = new Collection();
    }

    /**
     * Get the emails collection
     * @return Collection
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    /**
     * Check if there is an email sent for a given address
     * @param  string  $address
     * @return bool
     */
    public function hasEmailFor($address): bool
    {
        return $this->emails->filter(function (Email $email) use ($address) {
            return $email->hasRecipient($address);
        })->count() > 0;
    }

    /**
     * Check if there is an email from a given address
     * @param  string  $email
     * @return bool
     */
    public function hasEmailFrom($address): bool
    {
        return $this->emails->filter(function ($email) use ($address) {
            return $email->isFrom($address);
        })->count() > 0;
    }

    /**
     * Check if there is an email with a given subject
     * @param  string  $subject
     * @return bool
     */
    public function hasEmailWithSubject(string $subject): bool
    {
        return $this->emails->filter(function ($email) use ($subject) {
            return $email->hasSubject($subject);
        })->count() > 0;
    }

    /**
     * Get the last email sent
     *
     * @return Email|null
     */
    public function lastEmail()
    {
        return $this->emails->last();
    }

    /**
     * Fake sending an email from a view
     *
     * @param string $template
     * @param string $data
     * @param mixed $func
     *
     * @return void
     */
    public function send($template, $data, $func = null)
    {
        $this->sendRaw(view($template, $data)->render(), $func);
    }

    /**
     * Fake sending an email from raw body
     *
     * @param string $body
     * @param mixed $func
     *
     * @return void
     */
    public function sendRaw(string $body, $func = null)
    {
        $email = new Email($body);
        if (is_callable($func)) {
            $func($email);
        }
        $this->emails->push($email);
    }
}
