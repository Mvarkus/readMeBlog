<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\ {
    Category, User, Role, Post
};

class PostQueringTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function three_recent_posts_can_be_received()
    {
        // $this->withoutExceptionHandling();
    
        $user = factory(User::class)->create([
            'role_id' => Role::create([
                'title' => 'subscriber'
            ])
        ]);

        Category::create([
            'title' => 'smithing'
        ]);

        factory(Post::class, 4)->create([
            'status' => 1
        ]);

        $posts = Post::recentPosts(3)->all();

        $this->assertCount(3, $posts);
        $this->assertTrue($posts[0]->created_at->greaterThan($posts[1]->created_at));
        $this->assertTrue($posts[1]->created_at->greaterThan($posts[2]->created_at));

    }

    /**
     * @test
     */
    public function three_most_popular_posts_can_be_received()
    {
        // $this->withoutExceptionHandling();
    
        $user = factory(User::class)->create([
            'role_id' => Role::create([
                'title' => 'subscriber'
            ])
        ]);

        Category::create([
            'title' => 'smithing'
        ]);

        factory(Post::class, 4)->create([
            'status' => 1
        ]);

        $posts = Post::mostPopular(3)->all();

        $this->assertCount(3, $posts);
        $this->assertTrue($posts[0]->votes >= $posts[1]->votes);
        $this->assertTrue($posts[1]->votes >= $posts[2]->votes);

    }

    /**
     * @test
     */
    public function posts_can_be_filtered_by_category()
    {
        $this->withoutExceptionHandling();
    
        $user = factory(User::class)->create([
            'role_id' => Role::create([
                'title' => 'subscriber'
            ])
        ]);

        factory(Post::class, 2)->create([
            'status' => 1,
            'category_id' => Category::create([
                'title' => 'Fishing'
            ])->id
        ]);

        factory(Post::class, 2)->create([
            'status' => 1,
            'category_id' => Category::create([
                'title' => 'Smithing'
            ])->id
        ]);

        factory(Post::class, 2)->create([
            'status' => 1,
            'category_id' => Category::create([
                'title' => 'Diving'
            ])->id
        ]);

        // Posts can be categorised

        $posts = Post::getFilteredPosts([
            'categories' => [1]
        ])->get();

        $this->assertCount(2, $posts);
        
        // Posts can be ordered desc

        $posts = Post::getFilteredPosts([
            'categories' => [1, 2],
            'order' => [
                'by' => 'votes',
                'type' => 'desc'
            ]
        ])->get();

        $this->assertTrue($posts[0]->votes > $posts[1]->votes);
        $this->assertTrue($posts[1]->votes > $posts[2]->votes);
        $this->assertTrue($posts[2]->votes > $posts[3]->votes);

        $this->assertCount(4, $posts);

        // Posts can be ordered asc

        $posts = Post::getFilteredPosts([
            'categories' => [1, 2],
            'order' => [
                'by' => 'votes',
                'type' => 'asc'
            ]
        ])->get();

        $this->assertTrue($posts[0]->votes < $posts[1]->votes);
        $this->assertTrue($posts[1]->votes < $posts[2]->votes);
        $this->assertTrue($posts[2]->votes < $posts[3]->votes);

        $this->assertCount(4, $posts);

    }

}
