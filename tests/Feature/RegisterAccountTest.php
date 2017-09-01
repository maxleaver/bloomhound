<?php

namespace Tests\Feature;

use App\Account;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_unregistered_user_can_register_an_account()
    {
        $this->withoutExceptionHandling();

        $request = $this->makeRequest();

        $this->assertEquals(User::count(), 0);
        $this->assertEquals(Account::count(), 0);

        $response = $this->json('POST', 'api/register', $request)
        	->assertStatus(200)
            ->assertJsonStructure(['token']);

        $this->assertEquals(User::count(), 1);
        $this->assertEquals(Account::count(), 1);
    }

    /** @test */
    public function a_user_can_only_register_one_account()
    {
        $user = factory(User::class)->create();

        $request = $this->makeRequest($user->email);

        $response = $this->json('POST', 'api/register', $request)
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'email' => [
                        ['message']
                    ]
                ]
            ]);
    }

    /** @test */
    public function a_welcome_email_confirmation_is_sent_when_a_user_registers()
    {

    }

    protected function makeRequest($email = 'johndoe@gmail.com')
    {
        return [
            'name' => 'John Doe',
            'company' => 'Some Company, Inc.',
            'email' => $email,
            'password' => 'my-password',
            'scope' => '*',
        ];
    }
}