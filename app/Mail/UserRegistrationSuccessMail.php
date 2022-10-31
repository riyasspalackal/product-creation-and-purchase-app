<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class UserRegistrationSuccessMail extends Mailable
{
  public $user;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct()
  {
  }
  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this->subject('Success')->markdown(
      'mail.UserRegistrationSuccessMail'
    );
  }
}
