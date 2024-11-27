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
                                <a href="{{ route('category.edit', $category) }}" onclick="showModal(this, {{ $category->id }})">Edit</a>
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

    <div id="edit-modal"></div>
    <button id="popup-button" class="hidden" onclick="document.getElementById('popup-modal').classList.remove('hidden')" data-modal-target="popup-modal" data-modal-toggle="popup-modal" type="button">
        Toggle modal
    </button>
    <script>
        async function showModal(el, id) {
            event.preventDefault();
            const button = document.getElementById('popup-button');
            const popup_id_el = document.getElementById('popup-modal-id');
            if (popup_id_el) {
                const popup_id = popup_id_el.innerText;
                if (popup_id == id) {
                    button.click();
                    return
                }
            }
            console.log("fetching data")
            await axios.get(el.href)
                .then((res) => {
                    const modal = document.getElementById('edit-modal');
                    modal.innerHTML = res.data;
                    button.click();
                })
                .catch((error) => console.error(error))

            return false;
        }
    </script>
</x-app-layout>
