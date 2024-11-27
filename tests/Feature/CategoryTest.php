<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    use RefreshDatabase;

    public function test_create_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory([
            'user_id' => $user->id,
            'name' => 'test_category'
        ])->create();

        $data = [
            'name' => 'test_category',
        ];

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals($data['name'], $category->name);
        $this->assertEquals($user->id, $category->user_id);
    }

    public function test_update_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory([
            'user_id' => $user->id,
        ])->create();

        $data = [
            'name' => 'test_category_update',
        ];
        $updated_category = $category->fill($data);
        $updated_category->save();

        $this->assertInstanceOf(Category::class, $updated_category);
        $this->assertEquals($data['name'], $updated_category->name);
        $this->assertEquals($user->id, $updated_category->user_id);
    }

    public function test_delete_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory([
            'user_id' => $user->id
        ])
            ->create();

        $category->delete();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_delete_cascade_category(): void
    {
        $user = User::factory()->has(Category::factory(10))->create();
        $categories = $user->categories;

        $this->assertCount(10, $categories);

        $user->delete();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        $this->assertDatabaseMissing('categories', ['user_id' => $user->id]);
    }
}
