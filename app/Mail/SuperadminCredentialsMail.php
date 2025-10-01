<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuperadminCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $companyName;
    public $email;
    public $password;
    public $expiryDate;

    public function __construct($companyName, $email, $password, $expiryDate)
    {
        $this->companyName = $companyName;
        $this->email = $email;
        $this->password = $password;
        $this->expiryDate = $expiryDate;
    }

    public function build()
    {
        return $this->subject('Your Superadmin Account Credentials')
                    ->view('emails.superadmin_credentials');
    }
}