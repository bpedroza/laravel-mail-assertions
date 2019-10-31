<?php

namespace Tests;

use Bpedroza\LaravelMailAssertions\Email;
use Bpedroza\LaravelMailAssertions\MakesMailAssertions;

use PHPUnit\Framework\TestCase;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class MailUtilitiesTest extends TestCase
{
    use MakesMailAssertions;

    public function test_get_emails()
    {
        Mail::raw('', function($mail) {
            $mail->to('test@example.org');
        });

        $this->assertInstanceOf(Collection::class, $this->getEmails());
        $this->assertEquals(1, $this->getEmails()->count());
    }

    public function test_get_emails_for_address()
    {
        Mail::raw('', function($mail) {
            $mail->to('test@example.org');
        });

        $all = $this->getEmailsFor('test@example.org');

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertEquals(1, $all->count());
    }

    public function test_get_last_email_for_address()
    {
        Mail::raw('email 1', function($mail) {
            $mail->to('test@example.org');
        });

        Mail::raw('email 2', function($mail) {
            $mail->to('test@example.org');
        });

        $lastEmail = $this->getLastEmailFor('test@example.org');

        $this->assertInstanceOf(Email::class, $lastEmail);
        $this->assertTrue($lastEmail->contains('email 2'));
    }

    public function test_get_last_email()
    {
        Mail::raw('', function($mail) {
            $mail->to('test@example.org');
        });

        $lastEmail = $this->getLastEmail();

        $this->assertInstanceOf(Email::class, $lastEmail);
    }

}
