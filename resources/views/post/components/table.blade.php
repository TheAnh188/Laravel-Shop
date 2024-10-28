<div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6 mb-3">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="w-[5%] px-4 py-3 text-center">
                    <input type="checkbox" value="" id="check-all" class="input-checkbox">
                </th>
                <th scope="col" class="w-[51%] px-4 py-3">{{ __('messages.post.index.name') }}</th>
                @foreach ($languagess as $language)
                    @if (session('app_locale') === $language->canonical)
                        @continue
                    @endif
                    <th scope="col" class="px-4 py-3">
                        <span class="">
                            <img src="{{ $language->image }}" alt="Image" class="">
                        </span>
                    </th>
                @endforeach
                <th scope="col" class="w-[10%] px-4 py-3 text-center">{{ __('messages.order') }}</th>
                <th scope="col" class="w-[10%] px-4 py-3">{{ __('messages.status') }}</th>
                <th scope="col" class="w-[15%] px-4 py-3">{{ __('messages.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($posts) && is_object($posts))
                @foreach ($posts as $post)
                    <tr id="{{ $post->id }}"
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td scope="row"
                            class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                            <input type="checkbox" value="{{ $post->id }}" class="input-checkbox checkbox-item">
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center">
                                <img src="{{ $post->image ?? asset('images/no_image.jpg') }}" alt="Image" class="w-[10%] object-cover mr-2">
                                <span class="main_title text-blue-500 font-sf-ui font-semibold">
                                    {{ $post->name }}
                                </span>
                            </div>
                            <div>
                                <span class="text-orange-400 ">Nhóm hiển thị: </span>
                                @foreach ($post->post_catalogues as $post_catalogue)
                                    @foreach ($post_catalogue->post_catalogue_language as $value)
                                        <a href="/post-catalogue" class="text-[#333] font-sf-ui font-light hover:underline hover:text-blue-400">{{ $value->name }}</a>
                                    @endforeach
                                @endforeach
                            </div>
                        </td>
                        @foreach ($languagess as $language)
                            @if (session('app_locale') === $language->canonical)
                                @continue
                            @endif
                            @php
                                $isTranslated =  $post->languages->contains('id', $language->id);
                            @endphp
                            <td class="px-4 py-4 text-nowrap text-center">
                                <a href="/language/{{ $post->id }}/{{ $language->id }}/Post/translate" class="{{ $isTranslated ? 'text-blue-500' : 'text-red-500' }}">{{ $isTranslated ? 'Đã dịch' : 'Chưa dịch' }}</a>
                            </td>
                        @endforeach
                        <td class="px-4 py-4">
                            <input type="text" value="{{ $post->order }}" name="order"
                                class="sort-order text-center py-2 px-0 w-full text-sm text-gray-900 bg-transparent border-2 border-gray-300 appearance-none focus:outline-none focus:border-blue-600"
                                data-id="{{ $post->id }}" data-model="{{ $model }}">
                        </td>
                        <td class="px-4 py-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input data-modelId="{{ $post->id }}" data-model="{{ $model }}"
                                    data-field="status" type="checkbox" class="sr-only peer status"
                                    value="{{ $post->status }}" {{ $post->status == 1 ? 'checked' : '' }}>
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </td>
                        <td class="px-4 py-4">
                            <a href="/post/{{ $post->id }}/edit"
                                class="removeSelectedImage bg-[#1947ee] text-white px-3 py-2.5 rounded-md"><i
                                    class="fa-regular fa-pen-to-square"></i></a>
                            <a href="/post/{{ $post->id }}/delete"
                                class="bg-red-500 text-white px-3 py-2.5 rounded-md"><i
                                    class="fa-regular fa-trash-can"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
{{ $posts->links('vendor.pagination.tailwind') }}
