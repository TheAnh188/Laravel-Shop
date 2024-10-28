<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6 mb-3">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="w-[5%] px-4 py-3 text-center">
                    <input type="checkbox" value="" id="check-all" class="input-checkbox">
                </th>
                <th scope="col" class="w-[20%] px-4 py-3">{{ __('messages.language.index.name') }}</th>
                <th scope="col" class="w-[20%] px-4 py-3">{{ __('messages.language.index.locale') }}</th>
                <th scope="col" class="w-[15%] px-4 py-3">{{ __('messages.language.index.image') }}</th>
                <th scope="col" class="w-[20%] px-4 py-3 text-center">{{ __('messages.status') }}</th>
                <th scope="col" class="w-[20%] px-4 py-3 text-center">{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($languages) && is_object($languages))
                @foreach ($languages as $language)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td scope="row"
                            class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                            <input type="checkbox" value="{{ $language->id }}" class="input-checkbox checkbox-item">
                        </td>
                        <td class="px-4 py-4">
                            <span>{{ $language->name }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span>{{ $language->canonical }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span>
                                <img src="{{ $language->image }}" alt="Image">
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <label class="inline-flex items-center cursor-pointer">
                                <input data-modelId="{{ $language->id }}" data-model="{{ $model }}" data-field="status" type="checkbox" class="sr-only peer status" value="{{ $language->status }}" {{ ($language->status == 1) ? 'checked' : '' }} >
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <a href="/language/{{ $language->id }}/edit"
                                class="bg-[#1947ee] text-white px-3 py-2.5 rounded-md"><i
                                    class="fa-regular fa-pen-to-square"></i></a>
                            <a href="/language/{{ $language->id }}/delete" class="bg-red-500 text-white px-3 py-2.5 rounded-md"><i
                                    class="fa-regular fa-trash-can"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
{{ $languages->links('vendor.pagination.tailwind') }}
