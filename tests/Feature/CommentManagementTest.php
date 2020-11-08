<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\ {
    Post, Comment, User, Role, Category
};

class CommentManagementTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function comment_can_be_added()
    {
        $this->withoutExceptionHandling();
    
        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        Category::create([
            'title' => 'Swimming'
        ]);

        $post = factory(Post::class)->create();

        $this->actingAs($user)->post($post->specificResourcePath() . '/comments', [
            'content' => 'My great comment'
        ])->assertRedirect($post->specificResourcePath());

        $this->assertDatabaseHas('comments', [
            'content' => 'My great comment',
            'user_id' => $user->id,
            'post_id' => $post->id,
            'approved' => 0,
            'reply_to' => null
        ]);
    }

    /**
     * @test
     */
    public function comment_can_be_updated_by_owner()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        Category::create([
            'title' => 'Swimming'
        ]);

        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create([
            'content' => 'Hi',
            'approved' => 1
        ]);

        $this->actingAs($user)->patch($comment->specificResourcePath(), [
            'content' => 'Hello, world.'
        ])->assertRedirect($post->specificResourcePath());

        $this->assertDatabaseHas('comments', [
            'content' => 'Hello, world.',
            'user_id' => 1,
            'post_id' => 1,
            'reply_to' => null,
            'approved' => 0
        ]);

    }

    /**
     * @test
     */
    public function comment_cannot_be_updated_by_not_owner()
    {

        factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        Category::create([
            'title' => 'Swimming'
        ]);

        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create([
            'content' => 'Hi',
            'approved' => 1
        ]);

        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        $this->actingAs($user)->patch($comment->specificResourcePath(), [
            'content' => 'Hello, world.'
        ])->assertStatus(401);

        $this->assertDatabaseHas('comments', [
            'content' => 'Hi',
            'approved' => 1
        ]);

    }

    /**
     * @test
     */
    public function comment_can_be_deleted_by_owner()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        Category::create([
            'title' => 'Swimming'
        ]);

        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create([
            'content' => 'Hi',
            'approved' => 1
        ]);

        $this->actingAs($user)->delete($comment->specificResourcePath())
        ->assertRedirect($post->specificResourcePath());

        $this->assertCount(0, Comment::all());
    }

    /**
     * @test
     */
    public function comment_cannot_be_deleted_by_not_owner()
    {

        factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        Category::create([
            'title' => 'Swimming'
        ]);

        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create([
            'content' => 'Hi',
            'approved' => 1
        ]);

        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        $this->actingAs($user)->delete($comment->specificResourcePath())
            ->assertStatus(401);

        $this->assertDatabaseHas('comments', [
            'content' => 'Hi',
            'approved' => 1
        ]);

    }

    /**
     * @test
     */
    public function comment_cannot_be_approved_by_subscriber()
    {

        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        Category::create([
            'title' => 'Swimming'
        ]);

        $post = factory(Post::class)->create();
        
        $comment = factory(Comment::class)->create([
            'content' => 'Hi',
            'approved' => 0
        ]);

        $this->actingAs($user)->patch($comment->specificResourcePath() . '/approve')
            ->assertStatus(401);

        $this->assertDatabaseHas('comments', [
            'content' => 'Hi',
            'approved' => 0
        ]);

    }

    /**
     * @test
     */
    public function comment_can_be_approved_by_admin()
    {

        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])
        ]);

        Category::create([
            'title' => 'Swimming'
        ]);

        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create([
            'content' => 'Hi',
        ]);

        $this->actingAs($user)->patch($comment->specificResourcePath() . '/approve')
            ->assertOk();

        $this->assertDatabaseHas('comments', [
            'content' => 'Hi',
            'approved' => 1
        ]);

    }

    /**
     * @test
     */
    public function comment_approval_can_be_undone_by_admin()
    {

        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])
        ]);

        Category::create([
            'title' => 'Swimming'
        ]);

        $post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create([
            'content' => 'Hi',
            'approved' => 1
        ]);

        $this->actingAs($user)->patch($comment->specificResourcePath() . '/undo')
            ->assertOk();

        $this->assertDatabaseHas('comments', [
            'content' => 'Hi',
            'approved' => 0
        ]);

    }

    /**
     * @test
     */
    public function comment_approval_cannot_be_undone_by_subscriber()
    {

        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        Category::create([
            'title' => 'Swimming'
        ]);

        $post = factory(Post::class)->create();
        
        $comment = factory(Comment::class)->create([
            'content' => 'Hi',
            'approved' => 1
        ]);

        $this->actingAs($user)->patch($comment->specificResourcePath() . '/undo')
            ->assertStatus(401);

        $this->assertDatabaseHas('comments', [
            'content' => 'Hi',
            'approved' => 1
        ]);

    }
    
}
