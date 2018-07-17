<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function only_members_can_add_avatar()
    {
        $this->withExceptionHandling();
        
        $this->json('POST', '/api/users/1/avatar')
            ->assertStatus(401);
    }

    /** @test */
    function a_valid_avatar_must_be_provided()
    {
        $this->withExceptionHandling()->singIn();

        $this->json('POST', '/api/users/'. auth()->id() .'/avatar', [
            'avatar' => 'not-at-image'
        ])->assertStatus(422);
    }

    /** @test */
    function a_user_may_add_an_avatar_to_their_profile()
    {
        $this->singIn();

        Storage::fake('public');
        $this->json('POST', '/api/users/'. auth()->id() .'/avatar', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals('avatars/'. $file->hashName(), auth()->user()->avatar_path);
        Storage::disk('public')->assertExists('avatars/avatar.jpg');
    }
}