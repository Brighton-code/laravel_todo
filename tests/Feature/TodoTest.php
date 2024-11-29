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
        // check for deleted on id of table not link
        // Tale second category
        $category = $categories[1];
        // Get all todos of second category *should be none as cascadeOnDelete*
        $todos = $category->todos;

        $this->assertCount(0, $todos);
        $this->assertDatabaseMissing('todos', ['category_id' => $category->id]);
    }

    public function test_root(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_todo_root(): void
    {
        $user = User::factory()->has(Category::factory()->has(Todo::factory()))->create();
        $category = $user->categories()->first();

        $response = $this->actingAs($user)->get(route('todos.index', $category));

        $response->assertStatus(200);
        $response->assertViewIs('todos.index');
        $response->assertSee($category->first()->title);
    }

    public function test_todo_create(): void
    {
        $user = User::factory()->has(Category::factory())->create();
        $category = $user->categories()->first();
        $postData = [
            'title' => 'test_todo_create'
        ];

        $response = $this->actingAs($user)
            ->post(route('todos.store', $category), $postData);

        $response->assertStatus(302);
        $response->assertRedirect(route('todos.index', $category));
        $this->assertDatabaseHas('todos', [
            'category_id' => $category->id,
            'title' => $postData['title']
        ]);
    }

    public function test_todo_update(): void
    {
        $user = User::factory()->has(Category::factory()->has(Todo::factory(['completed' => 1])))->create();
        $category = $user->categories()->first();
        $todo = $category->todos()->first();

        $postData = [
            '_completed' => '1',
        ];

        $response = $this->actingAs($user)
            ->put(route('todos.update', [$category, $todo]), $postData);

        $response->assertStatus(302);
        $response->assertRedirect(route('todos.index', $category));
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'category_id' => $category->id,
            'completed' => 0
        ]);
    }

    public function test_todo_delete(): void
    {
        $user = User::factory()->has(Category::factory()->has(Todo::factory()))->create();
        $category = $user->categories()->first();
        $todo = $category->todos()->first();

        $response = $this->actingAs($user)->delete(route('todos.destroy', [$category, $todo]));

        $response->assertStatus(302);
        $response->assertRedirect(route('todos.index', $category));
        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id
        ]);
    }

    public function test_user_cannot_create_todo_on_other_user_category(): void
    {
        $user1 = User::factory()->has(Category::factory())->create();
        $user2 = User::factory()->create();
        $category = $user1->categories()->first();

        $postData = [
            'title' => 'test_todo_store_other_user'
        ];
        $response = $this->actingAs($user2)->post(route('todos.store', $category), $postData);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('todos', [
            'category_id' => $category->id,
            'title' => $postData['title']
        ]);
    }

    public function test_user_cannot_update_other_user_todo(): void
    {
        $user1 = User::factory()->has(Category::factory()->has(Todo::factory(['completed' => 0])))->create();
        $user2 = User::factory()->create();
        $category = $user1->categories()->first();
        $todo = $category->todos()->first();

        $postData = [
            '_completed' => '1',
            'completed' => 1
        ];

        $response = $this->actingAs($user2)
            ->put(route('todos.update', [$category, $todo]), $postData);

        $response->assertStatus(403);
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'category_id' => $category->id,
            'completed' => 0
        ]);
    }

    public function test_user_cannot_see_other_users_todos(): void
    {
        $user1 = User::factory()->has(Category::factory()->has(Todo::factory()))->create();
        $user2 = User::factory()->create();
        $category = $user1->categories()->first();

        $response = $this->actingAs($user2)
            ->get(route('todos.index', $category));

        $response->assertStatus(403);
    }

    public function test_user_cannot_delete_other_user_todo(): void
    {
        $user1 = User::factory()->has(Category::factory()->has(Todo::factory()))->create();
        $user2 = User::factory()->create();
        $category = $user1->categories()->first();
        $todo = $category->todos()->first();

        $response = $this->actingAs($user2)->delete(route('todos.destroy', [$category, $todo]));

        $response->assertStatus(403);
        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'category_id' => $category->id,
        ]);
    }
}
