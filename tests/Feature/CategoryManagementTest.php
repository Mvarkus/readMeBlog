<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\{
    User,
    Role,
    Category
};


class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function admin_can_add_category()
    {
        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])->id
        ]);

        $this->addCategory($user);

        $categories = Category::all();
        $this->assertCount(1, $categories);
    }

    /**
     * @test
     */
    public function subscriber_cannot_add_category()
    {
        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        $this->addCategory($user)->assertStatus(401);
        $this->assertCount(0, Category::all());
    }

    /**
     * @test
     */
    public function admin_can_update_category()
    {
        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])->id
        ]);

        $this->addCategory($user)->assertStatus(302);
        $this->assertDatabaseHas('categories', [
            'title' => 'Back-end'
        ]);

        $category = Category::first();

        $this->actingAs($user)->put($category->specificResourcePath(), [
            'title' => 'Front-end'
        ])->assertRedirect($category->specificResourcePath());

        $this->assertDatabaseHas('categories', [
            'title' => 'Front-end'
        ]);

    }

    /**
     * @test
     */
    public function subscriber_cannot_update_category()
    {
    
        $admin = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])
        ]);
        $subscriber = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        $this->addCategory($admin)->assertStatus(302);
        $this->assertDatabaseHas('categories', [
            'title' => 'Back-end'
        ]);

        $this->actingAs($subscriber)->put(Category::first()->specificResourcePath(), [
            'title' => 'Front-end'
        ])->assertStatus(401);

    }

     /**
     * @test
     */
    public function admin_can_delete_category()
    {
        $user = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])
        ]);

        $this->addCategory($user)->assertStatus(302);
        $this->assertDatabaseHas('categories', [
            'title' => 'Back-end'
        ]);

        $category = Category::first();

        $this->actingAs($user)->delete($category->specificResourcePath(), [
            'title' => 'Front-end'
        ])->assertRedirect('admin/categories');

        $this->assertCount(0, Category::all());

    }

    /**
     * @test
     */
    public function subscriber_cannot_delete_category()
    {
        $admin = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'admin'])
        ]);
        $subscriber = factory(User::class)->create([
            'role_id' => Role::create(['title' => 'subscriber'])
        ]);

        $this->addCategory($admin)->assertStatus(302);
        $this->assertDatabaseHas('categories', [
            'title' => 'Back-end'
        ]);

        $this->actingAs($subscriber)->delete(Category::first()->specificResourcePath(), [
            'title' => 'Front-end'
        ])->assertStatus(401);

    }

    private function addCategory(User $user)
    {
        return $this->actingAs($user)->post('admin/categories', [
            'title' => 'Back-end'
        ]); 
    }
}
