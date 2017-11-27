<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadAccountLogoTest extends TestCase
{
    use RefreshDatabase;

    protected $account;
    protected $original;
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        Storage::fake('local');

        $originalLogo = UploadedFile::fake()->image('original_logo.jpg', 150, 150);
        $newLogo = UploadedFile::fake()->image('new_logo.jpg', 150, 150);

        $this->original = Storage::putFile('local', $originalLogo);
        $this->account = create('App\Account', ['logo' => $this->original]);
        $this->request = ['logo' => $newLogo];
    }

    /** @test */
    public function a_user_can_upload_a_logo_for_their_account()
    {
        Storage::disk('local')->assertExists($this->account->logo);

        $this->uploadLogo()
            ->assertSuccessful();

        Storage::disk('local')->assertExists($this->account->fresh()->logo);
        Storage::disk('local')->assertMissing($this->original);
    }

    /** @test */
    public function a_user_can_only_upload_image_files()
    {
        $this->request['logo'] = UploadedFile::fake()->create('anInvalidFileType.pdf');
        $this->uploadLogo()
            ->assertStatus(422);
    }

    /** @test */
    public function a_user_can_only_upload_images_that_meet_the_minimum_size_requirements()
    {
        $this->request['logo'] = UploadedFile::fake()->image('new_logo.jpg', 50, 50);
        $this->uploadLogo()
            ->assertStatus(422);
    }

    /** @test */
    public function unauthenticated_users_cannot_upload_a_logo()
    {
        $this->uploadLogo(false)
            ->assertStatus(401);
    }

    protected function uploadLogo($signIn = true)
    {
        $url = 'api/account/logo';

        if ($signIn) {
            $this->signIn(create('App\User', [
                'account_id' => $this->account->id,
            ]));
        }

        return $this->postJson($url, $this->request);
    }
}
