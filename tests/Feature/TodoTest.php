<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoTest extends TestCase
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

    public function test_create_todo(): void
    {
        $user = User::factory()->has(Category::factory())->create();
        $category = $user->categories()->first();
        $todo = Todo::factory([
            'category_id' => $category->id,
            'title' => 'test_todo'
        ])->create();

        $data = [
            'title' => 'test_todo',
        ];

        $this->assertInstanceOf(Todo::class, $todo);
        $this->assertEquals($data['title'], $todo->title);
        $this->assertEquals($category->id, $todo->category_id);
    }

    public function test_update_todo(): void
    {
        $user = User::factory()->has(Category::factory())->create();
        $category = $user->categories()->first();
        $todo = Todo::factory([
            'category_id' => $category->id,
        ])->create();

        $data = [
            'title' => 'test_todo',
        ];
        $updated_todo = $todo->fill($data);
        $updated_todo->save();

        $this->assertInstanceOf(Todo::class, $updated_todo);
        $this->assertEquals($data['title'], $updated_todo->title);
        $this->assertEquals($category->id, $todo->category_id);
    }

    public function test_delete_category(): void
    {
        $user = User::factory()->has(Category::factory())->create();
        $category = $user->categories()->first();
        $todo = Todo::factory([
            'category_id' => $category->id,
        ])->create();

        $todo->delete();
        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    public function test_delete_cascade_category(): void
    {
        /**
         * Creat 1 'user' with 2 'categories' each with 10 'todos'
         */
        $user = User::factory()
            ->has(Category::factory(2)
                ->has(Todo::factory(10)))
            ->create();

        $this->assertCount(2, $user->categories);

        // Take first category
        $categories = $user->categories;
        $category = $categories[0];

        // Get all todos of first category
        $todos = $category->todos;
        $this->assertCount(10, $todos);

        $category->delete();
        $this->assertDatabaseMissing('todos', ['category_id' => $category->id]);

        $user->refresh();
        $this->assertCount(1, $user->categories);

        $user->delete();
        $this->assertDatabaseMissing('categories', ['user_id' => $user->id]);

        // Tale second category
        $category = $categories[1];
        // Get all todos of second category *should be none as cascadeOnDelete*
        $todos = $category->todos;

        $this->assertCount(0, $todos);
        $this->assertDatabaseMissing('todos', ['category_id' => $category->id]);
    }
}
