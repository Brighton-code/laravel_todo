<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Todo;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        //
        Gate::authorize('view', [Todo::class, $category]);

        return view('todos.index', ['todos' => $category->todos()->get(), 'category' => $category]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Category $category)
    {
        //
        Gate::authorize('create', [Todo::class, $category]);

        $validated = $request->validate([
            'title' => 'required|string|max:50',
        ]);
        $category->todos()->create($validated);

        return redirect()->route('todos.index', $category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category, Todo $todo)
    {
        //
        Gate::authorize('update', $todo);

        if ($request->has('_completed')) {
            $todo->update([
                'completed' => $request->has('completed')
            ]);
        }
        return redirect()->route('todos.index', $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Todo $todo)
    {
        //
        Gate::authorize('delete', $todo);

        $todo->delete();
        return redirect()->route('todos.index', $category);
    }
}
