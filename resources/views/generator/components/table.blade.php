<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6 mb-3">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="w-[5%] px-4 py-3 text-center">
                    <input type="checkbox" value="" id="check-all" class="input-checkbox">
                </th>
                <th scope="col" class="w-[50%] px-4 py-3">{{ __('messages.generator.index.name') }}</th>
                <th scope="col" class="w-[45%] px-4 py-3 text-center">{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($generators) && is_object($generators))
                @foreach ($generators as $generator)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td scope="row"
                            class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                            <input type="checkbox" value="{{ $generator->id }}" class="input-checkbox checkbox-item">
                        </td>
                        <td class="px-4 py-4">
                            <span>{{ $generator->name }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <a href="/generator/{{ $generator->id }}/edit"
                                class="bg-[#1947ee] text-white px-3 py-2.5 rounded-md"><i
                                    class="fa-regular fa-pen-to-square"></i></a>
                            <a href="/generator/{{ $generator->id }}/delete" class="bg-red-500 text-white px-3 py-2.5 rounded-md"><i
                                    class="fa-regular fa-trash-can"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
{{ $generators->links('vendor.pagination.tailwind') }}
