<?php

namespace Bpedroza\LaravelMailAssertions;

use Illuminate\Support\Facades\Mail;
use Bpedroza\LaravelMailAssertions\Mailer;
use Illuminate\Support\Collection;

/**
 * This is the trait to include in your tests.
 * It will mock the Mail facade so we can do assertions later
 */
trait MakesMailAssertions
{

    /**
     * @var Mailer
     */
    private static $emailSender;

    /**
     * See that there is no email sent
     * @param string $message - the message to display when assertion fails
     * @return $this
     */
    public function assertEmailNotSent($message = 'An unexpected email was sent.')
    {
        $this->assertNull(
            $this->lastEmail(),
            $message
        );

        return $this;
    }

    /**
     * See that there is at least one email
     * @param string $message - the message to display when assertion fails
     * @return $this
     */
    public function assertEmailSent($message = 'Unable to find a generated email.')
    {
        $this->assertNotNull(
            $this->lastEmail(),
            $message
        );

        return $this;
    }

    /**
     * Assert an email was sent from the specified address
     * @param string $email
     * @return $this
     */
    public function assertEmailSentFrom($email)
    {
        $message = sprintf('Unable to find an email from [%s].', $email);
        $this->assertEmailSent($message);
        $this->assertTrue(
            $this->getEmailSender()->hasEmailFrom($email),
            $message
            );

        return $this;
    }

    /**
     * See if there is an email for a given address
     * @param string $address
     * @return $this
     */
    public function assertEmailSentTo($address)
    {
        $message = sprintf('Unable to find an email addressed to [%s].', $address);
        $this->assertEmailSent($message);
        $this->assertTrue(
            $this->getEmailSender()->hasEmailFor($address),
            $message
            );

        return $this;
    }

    /**
     * Assert an email got sent with the specified subject line
     * @param  string $subject
     * @return $this
     */
    public function assertEmailSentWithSubject($subject)
    {
        $message = sprintf('Unable to find an email with subject [%s].', $subject);
        $this->assertEmailSent($message);
        $this->assertTrue(
            $this->getEmailSender()->hasEmailWithSubject($subject),
            $message
            );

        return $this;
    }

    /**
     * Check if the last email sent contains a given string
     * @param string $string
     */
    public function assertLastEmailContains($string)
    {
        $this->assertEmailSent();
        $this->assertTrue(
            $this->lastEmail()->contains($string),
            sprintf("%s \n Could not find [%s] in the above message.", $this->lastEmail()->getBody(), $string)
        );
    }

    /**
     * @before
     */
    public function fakeMail()
    {
        if(method_exists($this, 'afterApplicationCreated')) {
            $this->afterApplicationCreated(function () {
                $this->mockMailFacade();
            });
        } else {
            $this->mockMailFacade();
        }
    }

    /**
     * Get all of the emails sent
     * @return \Illuminate\Support\Collection
     */
    public function getEmails(): Collection
    {
        return $this->getEmailSender()->emails;
    }

    /**
     * @param array|string $address
     * @return \Illuminate\Support\Collection
     */
    public function getEmailsFor($address): Collection
    {
        $addresses = collect((array) $address);

        return $this->getEmails()->filter(function (Email $email) use ($addresses) {
            return $addresses->contains(function ($address) use ($email) {
                return $email->hasRecipient($address);
            });
        });
    }

    /**
     * @param array|string $email
     * @return \Bpedroza\MailAssertions\Email|null
     */
    public function getLastEmailFor($email)
    {
        return $this->getEmailsFor($email)->last();
    }

    /**
     * Get the last email sent
     * @return \Tests\MailTheif\Email|null
     */
    public function lastEmail()
    {
        return $this->getEmailSender()->lastEmail();
    }


    /**
     * Gets the sender class
     * @return Mailer
     */
    private function getEmailSender()
    {
        if(static::$emailSender === null) {
            static::$emailSender = new Mailer;
        }

        return static::$emailSender;
    }

    /**
     * Mock the mail facade
     * @return void
     */
    private function mockMailFacade()
    {
        $this->getEmailSender()->empty();
        Mail::shouldReceive('send')->andReturnUsing(function (...$args) {
            $this->getEmailSender()->send(...$args);
        });

        Mail::shouldReceive('raw')->andReturnUsing(function (...$args) {
            $this->getEmailSender()->sendRaw(...$args);
        });

        Mail::shouldReceive('failures');
    }
}
