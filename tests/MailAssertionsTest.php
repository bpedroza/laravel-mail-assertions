<?php

namespace Tests;

use Bpedroza\LaravelMailAssertions\MakesMailAssertions;

use PHPUnit\Framework\TestCase;

use Illuminate\Support\Facades\Mail;

class MailAssertionsTest extends TestCase
{
    use MakesMailAssertions;

    public function test_no_email()
    {
        $this->assertEmailNotSent();
    }

    public function test_email_sent()
    {
        Mail::raw('');

        $this->assertEmailSent();
    }

    public function test_email_sent_from()
    {
        Mail::raw('', function ($mail) {
            $mail->from('from@example.org');
        });

        $this->assertEmailSentFrom('from@example.org');
    }

    public function test_email_sent_to()
    {
        Mail::raw('', function ($mail) {
            $mail->to('to@example.org');
        });

        $this->assertEmailSentTo('to@example.org');
    }

    public function test_email_sent_with_subject()
    {
        Mail::raw('', function ($mail) {
            $mail->subject('Test Subject');
        });

        $this->assertEmailSentWithSubject('Test Subject');
    }

    public function test_last_email_contains()
    {
        Mail::raw('Test Text');

        $this->assertLastEmailContains('Test Text');
    }

    public function test_chained()
    {
        Mail::raw('A Test', function ($mail) {
            $mail->subject('Test Subject')
                ->from('from@example.com')
                ->to('to@example.com');
        });

        $this->assertEmailSentFrom('from@example.com')
            ->assertEmailSentTo('to@example.com')
            ->assertEmailSentWithSubject('Test Subject')
            ->assertLastEmailContains('A Test');
    }
}
