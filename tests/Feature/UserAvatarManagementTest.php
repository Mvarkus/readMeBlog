<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\User;

class UserAvatarManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function avatar_can_be_uploaded()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->uploadAvatar($user);
        $user->fresh();

        $this->assertNotEquals('images/avatars/default.jpg', $user->avatar);
        $this->assertCount(1, Storage::disk('images/avatars')->files());
    }

    /**
     * @test
     */
    public function redirect_if_not_logged_in()
    {
        factory(User::class)->create();
        Storage::fake('images/avatars');
        $this->post('/user/avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ])->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function avatar_can_be_set_to_default()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $this->uploadAvatar($user);
        $this->actingAs($user)->patch('/user/avatar/default')->assertRedirect('/user');
        $user->fresh();

        $this->assertEquals('images/avatars/default.png', $user->avatar);
    }

    public function uploadAvatar(User $user)
    {
        Storage::fake('images/avatars');

        return $this->actingAs($user)->post('/user/avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg')
        ])->assertRedirect('/user');
    }
}
