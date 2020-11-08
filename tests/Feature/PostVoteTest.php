<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\ {
    Category, User, Post, Vote
};

class PostVoteTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function logged_user_can_vote_for_post()
    {
        $this->withoutExceptionHandling();
    
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create([
            'category_id' => Category::create(['title' => 'random'])->id,
            'votes' => 0
        ]);

        $this->actingAs($user)
            ->patch($post->specificResourcePath() . '/vote');

        $this->assertDatabaseHas('votes', [
            'user_id' => 1,
            'post_id' => 1,
            'status' => 1
        ]);
        
        $this->assertSame(1, (int) Post::find(1)->votes);
    }

    /**
     * @test
     */
    public function user_has_to_wait_for_vote_cooldown()
    {
        // $this->withoutExceptionHandling();
    
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create([
            'category_id' => Category::create(['title' => 'random'])->id
        ]);

        $this->actingAs($user)
            ->patch($post->specificResourcePath() . '/vote');

        $this->actingAs($user)
            ->patch($post->specificResourcePath() . '/vote');

        $this->assertDatabaseHas('votes', [
            'user_id' => 1,
            'post_id' => 1,
            'status' => 1
        ]);
    }

    /**
     * @test
     */
    public function unlogged_user_cannot_vote_for_post()
    {
        // $this->withoutExceptionHandling();
    
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create([
            'category_id' => Category::create(['title' => 'random'])->id
        ]);

        $this->patch($post->specificResourcePath() . '/vote')
            ->assertRedirect('/login');
    }
}
