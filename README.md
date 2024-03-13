# Laravel Mail Assertions
Make assertions on emails in Laravel 6 - 11

This is a simple package to allow you to make assertions on emails sent with laravel.

This package is inspired by tightenco/mailthief which I used pre laravel 6. Now that
package is no longer supported, so I made this one to allow me to migrate without too
much hassle.

## Installation

`composer require bpedroza/laravel-mail-assertions --dev`

## Usage

First you will need to include the `Bpedroza\LaravelMailAssertions\MakesMailAssertions` trait
in your test.

Next you can start making assertions on emails:

| Method | Description |
| ------ | ----------- |
| `assertEmailNotSent` | Assert no emails were sent |
| `assertEmailSent` | Assert an email was sent |
| `assertEmailSentFrom` | Assert an email was sent from a given email address |
| `assertEmailSentTo` | Assert an email was sent to a given address |
| `assertEmailSentWithSubject` | Assert an email was sent with a given subject |
| `assertLastEmailContains` | Assert the last email sent contains a given string |
