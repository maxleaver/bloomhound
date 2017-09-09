<?php

namespace Tests\Feature;

use App\Account;
use App\User;
use App\Events\AccountRegistered;
use App\Mail\NewAccountWelcome;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

class RegisterAccountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        Mail::fake();
    }

    /** @test */
    public function an_unregistered_user_can_register_an_account()
    {
        $request = [
        	'name' => 'John Doe',
        	'company' => 'Company, Inc.',
        	'email' => 'john@doe.com',
        	'password' => 'foobar',
        	'password_confirmation' => 'foobar'
        ];

        $this->post(route('register'), $request)
        	->assertRedirect(route('home'));

        $account = Account::whereName('Company, Inc.')->first();
        $user = User::whereName('John Doe')->first();

        $this->assertNotNull($account);
        $this->assertNotNull($user);
    }

    /** @test */
    public function a_user_can_only_register_one_account()
    {
        $user = create('App\User');

        $request = [
        	'name' => $user->name,
        	'company' => 'Company, Inc.',
        	'email' => $user->email,
        	'password' => 'foobar',
        	'password_confirmation' => 'foobar'
        ];

        $this->post(route('register'), $request)
        	->assertStatus(302);
    }

    /** @test */
    public function a_welcome_email_confirmation_is_sent_when_a_user_registers()
    {
    	$request = [
        	'name' => 'John Doe',
        	'company' => 'Company, Inc.',
        	'email' => 'john@doe.com',
        	'password' => 'foobar',
        	'password_confirmation' => 'foobar'
        ];

        $this->post(route('register'), $request);

    	Mail::assertSent(NewAccountWelcome::class);
    }
}