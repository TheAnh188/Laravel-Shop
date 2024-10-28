<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6 mb-3">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="w-[5%] px-4 py-3 text-center">
                    <input type="checkbox" value="" id="check-all" class="input-checkbox">
                </th>
                <th scope="col" class="w-[30%] px-4 py-3">{{ __('messages.permission.index.name') }}</th>
                <th scope="col" class="w-[25%] px-4 py-3">{{ __('messages.permission.index.canonical') }}</th>
                <th scope="col" class="w-[40%] px-4 py-3 text-center">{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($permissions) && is_object($permissions))
                @foreach ($permissions as $permission)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td scope="row"
                            class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                            <input type="checkbox" value="{{ $permission->id }}" class="input-checkbox checkbox-item">
                        </td>
                        <td class="px-4 py-4">
                            <span>{{ $permission->name }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span>{{ $permission->canonical }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <a href="/permission/{{ $permission->id }}/edit"
                                class="bg-[#1947ee] text-white px-3 py-2.5 rounded-md"><i
                                    class="fa-regular fa-pen-to-square"></i></a>
                            <a href="/permission/{{ $permission->id }}/delete" class="bg-red-500 text-white px-3 py-2.5 rounded-md"><i
                                    class="fa-regular fa-trash-can"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
{{ $permissions->links('vendor.pagination.tailwind') }}
