<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadAccountLogoTest extends TestCase
{
    use RefreshDatabase;

    protected $original;
    protected $request;
    protected $url;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        Storage::fake('local');

        $this->original = Storage::putFile('local', UploadedFile::fake()->image('original_logo.jpg', 150, 150));
        $this->account = create('App\Account', [
        	'logo' => $this->original
        ]);
        $this->user = create('App\User', ['account_id' => $this->account->id]);
        $this->request = ['logo' => UploadedFile::fake()->image('new_logo.jpg', 150, 150)];
        $this->url = 'api/account/logo';
    }

    /** @test */
    public function a_user_can_upload_a_logo_for_their_account()
    {
    	$this->withoutExceptionHandling();

        Storage::disk('local')->assertExists($this->account->logo);

    	$response = $this->signIn($this->user)
    		->postJson($this->url, $this->request)
    		->assertSuccessful();

        Storage::disk('local')->assertExists($this->account->fresh()->logo);
    	Storage::disk('local')->assertMissing($this->original);
    }

    /** @test */
    public function a_user_can_only_upload_image_files()
    {
    	$this->request['logo'] = UploadedFile::fake()->create('anInvalidFileType.pdf');

    	$this->signIn($this->user)
    		->postJson($this->url, $this->request)
    		->assertStatus(422);
    }

    /** @test */
    public function a_user_can_only_upload_images_that_meet_the_minimum_size_requirements()
    {
        $tinyImage = UploadedFile::fake()->image('new_logo.jpg', 50, 50);

        $this->signIn($this->user)
    		->postJson($this->url, ['logo' => $tinyImage])
    		->assertStatus(422);
    }

    /** @test */
    public function unauthenticated_users_cannot_upload_a_logo()
    {
    	$this->postJson($this->url, $this->request)
    		->assertStatus(401);
    }
}
