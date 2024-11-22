<x-app-layout>
{{--    <div class="text-white">{{ $todos }}</div>--}}
    <div class="py-16">
        <div class="max-w-md mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="px-4 py-2">
                <h1 class="text-gray-800 dark:text-white font-bold text-2xl uppercase">Category List</h1>
            </div>
            <form class="w-full max-w-sm mx-auto px-4 py-2" action="{{ route('category.store') }}" method="post">
                @csrf
                <div class="flex items-center border-b-2 border-teal-500 py-2">
                    <input
                        class="appearance-none bg-transparent border-none w-full text-gray-700 dark:text-gray-100 mr-3 py-1 px-2 leading-tight focus:outline-none"
                        type="text" name="name" placeholder="Add a category">
                    <button
                        class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                        type="submit">
                        Add
                    </button>
                </div>
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="text-black dark:text-white">{{$error}}</div>
                    @endforeach
                @endif
            </form>
            <ul class="divide-y divide-gray-200 px-4">
                @forelse($categories as $category)
                    <li class="py-4">
                        <a href="{{ route('todos.index', $category) }}">
                        <div class="flex justify-between">
                            <div class="flex items-center">
                                <label for="todo1" class="ml-3 block text-gray-900 dark:text-gray-100 cursor-pointer">
                                    <span class="text-lg font-medium cursor-pointer">{{ $category->name }}</span>
                                </label>
                            </div>
                        </div>
                        </a>
                    </li>
                @empty
                    <li class="py-4">
                        <div class="flex items-center">
                            <label for="todo1" class="ml-3 block text-gray-900 dark:text-gray-100">
                                <span class="text-lg font-medium">No Categories</span>
                            </label>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>
