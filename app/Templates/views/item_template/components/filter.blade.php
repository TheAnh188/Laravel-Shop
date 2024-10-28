{{-- moduleCanonical = post
    Module = Post
tableName = post --}}
<div>
    <form class="w-full grid md:grid-cols-5 md:gap-6 mt-2" action="/{moduleCanonical}">
        @php
            $perpage = request('perpage') ?: old('perpage');
        @endphp
        <select id="countries" name="perpage"
            class="bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
            @for ($i = 20; $i <= 200; $i += 20)
                <option value="{{ $i }}" {{ $perpage == $i ? 'selected' : '' }}>{{ $i }} {{ __('messages.perpage') }}
                </option>
            @endfor
        </select>
        @php
            ${tableName}_catalogue_id = request('{tableName}_catalogue_id') ?: old('{tableName}_catalogue_id');
        @endphp
        <select id="countries" name="{tableName}_catalogue_id"
            class="block bg-white text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
            @foreach ($dropdown as $key => $value)
                <option value="{{ $key }}" {{ ${tableName}_catalogue_id == $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
        @php
            $status = request('status') ?: old('status');
        @endphp
        <select id="countries" name="status"
            class="block bg-white text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
            @foreach (__('messages.status_input') as $key => $value)
                <option value="{{ $key }}" {{ $status == $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
        <div class="flex">
            <label for="simple-search" class="sr-only">Search</label>
            <div class="relative w-full my-auto h-11">
                <div class="h-11 absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5v10M3 5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm0 10a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm12 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0V6a3 3 0 0 0-3-3H9m1.5-2-2 2 2 2" />
                    </svg>
                </div>
                <input type="text" id="simple-search" name="keyword"
                    value="{{ request('keyword') ?: old('keyword') }}"
                    class="h-11 bg-white text-gray-900 text-sm rounded-lg focus:ring-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 "
                    placeholder="{{ __('messages.search') }}" />
            </div>
            <button type="submit"
                class="my-auto h-10 p-2.5 ms-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
                <span class="sr-only">Search</span>
            </button>
        </div>
        <div class="relative">
            <a href="/{moduleCanonical}/create"
                class="removeSelectedImage h-10 inline-block bg-red-500 text-white px-3 py-2.5 rounded-md relative top-[2px] -left-4"><i
                    class="fa-solid fa-plus"></i></a>
            @include('layouts.admin.status_button', ['model' => '{Module}'])
        </div>
    </form>
</div>
