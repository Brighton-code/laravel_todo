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

    public function test_root(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_category_root(): void
    {
        $user = User::factory()->has(Category::factory(2))->create();
        $response = $this->actingAs($user)
            ->get(route('category.index'));

        $response->assertStatus(200);
        $response->assertViewIs('category.index');
        $response->assertSee($user->categories()->first()->name);
    }

    public function test_category_create(): void
    {
        $user = User::factory()->create();
        $postData = [
            'name' => 'test_category_create',
        ];

        $response = $this->actingAs($user)
            ->post(route('category.store'), $postData);

        $response->assertStatus(302);
        $response->assertRedirect(route('category.index'));
        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => $postData['name'],
        ]);
    }

    public function test_category_update(): void
    {
        $user = User::factory()->has(Category::factory())->create();
        $category = $user->categories()->first();
        $postData = [
            'name' => 'test_category_update',
        ];
        $response = $this->actingAs($user)
            ->put(route('category.update', $category->id), $postData);

        $response->assertStatus(302);
        $response->assertRedirect(route('category.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'user_id' => $user->id,
            'name' => $postData['name'],
        ]);
    }

    public function test_category_delete(): void
    {
        $user = User::factory()->has(Category::factory())->create();
        $category = $user->categories()->first();
        $response = $this->actingAs($user)
            ->delete(route('category.destroy', $category->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('category.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_user_cannot_edit_other_user_category(): void
    {
        $user1 = User::factory()->has(Category::factory())->create();
        $user2 = User::factory()->create();
        $post = $user1->categories()->first();

        $postData = [
            'name' => 'test_category_update',
        ];

        $response = $this->actingAs($user2)
            ->put(route('category.update', $post->id), $postData);
        $response->assertStatus(403);
        $this->assertDatabaseHas('categories', ['id' => $post->id, 'name' => $post->name, 'user_id' => $user1->id]);
    }

    public function test_user_cannot_delete_other_user_category(): void
    {
        $user1 = User::factory()->has(Category::factory())->create();
        $user2 = User::factory()->create();
        $post = $user1->categories()->first();

        $response = $this->actingAs($user2)
            ->delete(route('category.destroy', $post->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('categories', ['id' => $post->id, 'name' => $post->name, 'user_id' => $user1->id]);
    }
}
