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

    protected $request;

    protected function setUp()
    {
        parent::setUp();

        Mail::fake();

        $this->request = [
            'name' => 'John Doe',
            'company' => 'Company, Inc.',
            'email' => 'john@doe.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ];
    }

    /** @test */
    public function an_unregistered_user_can_register_an_account()
    {
        $this->post(route('register'), $this->request)
        	->assertRedirect(route('home'));

        $account = Account::whereName('Company, Inc.')->first();
        $user = User::whereName('John Doe')->first();

        $this->assertNotNull($account);
        $this->assertNotNull($user);
    }

    /** @test */
    public function arrangeable_type_settings_are_created_when_an_account_is_registered()
    {
        $this->post(route('register'), $this->request);

        $account = Account::whereName('Company, Inc.')->first();

        $this->assertNotNull($account->arrangeable_type_settings);
        $this->assertInstanceOf('App\ArrangeableTypeSetting', $account->arrangeable_type_settings[0]);
    }

    /** @test */
    public function a_user_can_only_register_one_account()
    {
        $user = create('App\User');
        $this->request['email'] = $user->email;

        $this->post(route('register'), $this->request)
        	->assertStatus(302);
    }

    /** @test */
    public function logged_in_users_cannot_register_an_account()
    {
        $user = create('App\User');

        $this->signIn($user)
            ->postJson(route('register'), $this->request)
            ->assertStatus(302);
    }

    /** @test */
    public function a_welcome_email_confirmation_is_sent_when_a_user_registers()
    {
        $this->post(route('register'), $this->request);

    	Mail::assertSent(NewAccountWelcome::class);
    }

    /** @test */
    public function default_arrangeable_type_settings_are_created_after_registration()
    {
        $this->post(route('register'), $this->request);

        $account = \Auth::user()->account;
        $settings = \App\ArrangeableTypeSetting::whereAccountId($account->id);

        $this->assertEquals(\App\ArrangeableType::all()->count(), $settings->count());
    }
}
