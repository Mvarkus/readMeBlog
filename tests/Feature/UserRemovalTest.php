<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_be_removed()
    {
        $user = factory(User::class)->create();
        $this->assertCount(1, User::all());
        
        $this->actingAs($user)->delete('/user');
        $this->assertCount(0, User::all());
    }

    /**
     * @test
     */
    public function user_cannot_be_removed_without_logged_in_state()
    {
        $user = factory(User::class)->create();
        $this->assertCount(1, User::all());
        
        $this->delete('/user')->assertRedirect('/login');
        $this->assertCount(1, User::all());
    }

}
