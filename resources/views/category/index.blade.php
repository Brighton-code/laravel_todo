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
                        <div class="flex justify-between">
                            <div class="flex items-center">
                                <a href="{{ route('todos.index', $category) }}">

                                <label for="todo1" class="ml-3 block text-gray-900 dark:text-gray-100 cursor-pointer">
                                    <span class="text-lg font-medium cursor-pointer">{{ $category->name }}</span>
                                </label>
                                </a>
                            </div>
                            <div class="ml-auto flex items-center">
                                <a href="{{ route('todos.edit', $category) }}">Edit</a>
                                <form action="{{ route('category.destroy', $category) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                             width="24px"
                                             fill="#bb3f3f">
                                            <path
                                                d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
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


    <button onclick="document.getElementById('popup-modal').classList.remove('hidden')" data-modal-target="popup-modal" data-modal-toggle="popup-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
        Toggle modal
    </button>

    <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button onclick="document.getElementById('popup-modal').classList.add('hidden')" type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this product?</h3>
                    <button data-modal-hide="popup-modal" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Yes, I'm sure
                    </button>
                    <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No, cancel</button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
