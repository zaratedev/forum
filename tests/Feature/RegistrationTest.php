<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmedYourEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();
        
        event(new Registered(create('App\User')));

        Mail::assertSent(PleaseConfirmedYourEmail::class);
    }

    /** @test */
    function user_can_fully_confirm_their_email_address()
    {
        $this->post('/register', [
            'name' => 'John',
            'email' => 'john@mail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        // Let the user confirm their account.
        $this->get('/register/confirm?token='. $user->confirmation_token);
        $this->assertTrue($user->fresh()->confirmed);
    }

}
