<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use App\ {
    Category,
    User,
    Role,
    Post
};

class PostManagementTest extends TestCase
{

    use RefreshDatabase;


    /**
     * @test
     */
    public function subscriber_cannot_access_admin_post_area()
    {
        // $this->withoutExceptionHandling();
    
        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])->id
        ]);

        $this->actingAs($user)->get('/admin/posts')->assertStatus(401);
    }

    /**
     * @test
     */
    public function post_with_image_can_be_craeted_by_admin()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])->id
        ]);

        $this->createPost($user)->assertRedirect('admin/posts/create');
        $this->assertDatabaseHas('posts', [
            'title' => 'Great Post Title',
            'content' => '<h2>Greetings!</h2><p>Hello world</p>',
            'status' => 1,
            'user_id' => $user->id
        ]);

        $this->assertCount(1, Storage::disk('images/blog')->files());
    }

    /**
     * @test
     */
    public function post_without_image_can_be_craeted_by_admin()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])->id
        ]);

        $this->createPost($user, false)->assertRedirect('admin/posts/create');
        $this->assertDatabaseHas('posts', [
            'title' => 'Great Post Title',
            'content' => '<h2>Greetings!</h2><p>Hello world</p>',
            'status' => 1,
            'user_id' => $user->id
        ]);

        $this->assertCount(0, Storage::disk('images/blog')->files());
    }

    /**
     * @test
     */
    public function post_can_be_updated_by_admin()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])->id
        ]);

        $this->createPost($user)->assertRedirect('admin/posts/create');
        $this->assertDatabaseHas('posts', [
            'title' => 'Great Post Title',
            'content' => '<h2>Greetings!</h2><p>Hello world</p>',
            'status' => 1,
            'user_id' => $user->id
        ]);
        $this->assertCount(1, Storage::disk('images/blog')->files());

        $post = Post::first();

        // Leave old image
        $this->put($post->specificResourcePath('/admin'), [
            'title' => 'Not Great Post Title',
            'content' => '<h2>Not Greetings!</h2><p>Hello world</p>',
            'excerpt' => 'asdasd',
            'category_id' => Category::create(['title' => 'Films'])->id,
            'status' => 0,
            'image' => null,
            'leave_old_image' => true
        ])->assertRedirect($post->specificResourcePath('/admin'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Not Great Post Title',
            'content' => '<h2>Not Greetings!</h2><p>Hello world</p>',
            'category_id' => 2,
            'status' => 0,
            'image' => $post->image
        ]);

        $this->assertCount(1, Storage::disk('images/blog')->files());

        // Remove image from post
        $this->put($post->specificResourcePath('/admin'), [
            'title' => '2 Not Great Post Title',
            'content' => '<h2>Not Greetings!</h2><p>Hello world</p>',
            'excerpt' => 'asdasd',
            'category_id' => Category::create(['title' => 'Keyboard'])->id,
            'status' => 0,
            'image' => null,
            'leave_old_image' => false
        ])->assertRedirect($post->specificResourcePath('/admin'));

        $this->assertDatabaseHas('posts', [
            'title' => '2 Not Great Post Title',
            'content' => '<h2>Not Greetings!</h2><p>Hello world</p>',
            'category_id' => 3,
            'status' => 0,
            'image' => null
        ]);

        $this->assertCount(0, Storage::disk('images/blog')->files());

        // New image
        $this->put($post->specificResourcePath('/admin'), [
            'title' => '3 Not Great Post Title',
            'content' => '<h2>Not Greetings!</h2><p>Hello world</p>',
            'excerpt' => 'asdasd',
            'category_id' => Category::create(['title' => 'World'])->id,
            'status' => 0,
            'image' => UploadedFile::fake()->image('post.jpeg'),
            'leave_old_image' => false
        ])->assertRedirect($post->specificResourcePath('/admin'));

        $this->assertDatabaseHas('posts', [
            'title' => '3 Not Great Post Title',
            'content' => '<h2>Not Greetings!</h2><p>Hello world</p>',
            'category_id' => 4,
            'status' => 0
        ]);

        $this->assertNotSame($post->image, $post->fresh()->image);
        $this->assertCount(1, Storage::disk('images/blog')->files());

    }

    /**
     * @test
     */
    public function post_can_be_deleted()
    {
        // $this->withoutExceptionHandling();
    
        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])->id
        ]);

        $this->createPost($user)->assertRedirect('admin/posts/create');
        $this->assertDatabaseHas('posts', [
            'title' => 'Great Post Title',
            'content' => '<h2>Greetings!</h2><p>Hello world</p>',
            'status' => 1,
            'user_id' => $user->id
        ]);
        $this->assertCount(1, Storage::disk('images/blog')->files());

        $post = Post::first();

        $this->actingAs($user)->delete($post->specificResourcePath('/admin'))->assertRedirect('admin/posts');
        $this->assertCount(0, Storage::disk('images/blog')->files());

    }

    private function createPost(
        User $user,
        bool $withImage = true
    ) {

        Storage::fake('images/blog');

        $image = $withImage ?
            UploadedFile::fake()->image('post.jpeg') :
            null;

        return $this->actingAs($user)->post('admin/posts', [
            'title' => 'Great Post Title',
            'content' => '<h2>Greetings!</h2><p>Hello world</p>',
            'excerpt' => 'asdasdasdasdasd',
            'user_id' => $user->id,
            'category_id' => Category::create(['title' => 'World'])->id,
            'status' => 1,
            'image' => $image
        ]);
    }
}
