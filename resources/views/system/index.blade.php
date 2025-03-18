@extends('layouts.admin.app')

@section('link')
    @vite('public/ckeditor/ckeditor5-premium-features-43.0.0/ckeditor5/ckeditor5.css')
@endsection

@section('head_script')
    <script type="importmap">
    {
        "imports": {
            "ckeditor5": "/ckeditor/ckeditor5-premium-features-43.0.0/ckeditor5/ckeditor5.js",
            "ckeditor5/": "/ckeditor/ckeditor5-premium-features-43.0.0/ckeditor5/"
        }
    }
</script>

@section('content')
    @include('system.components.breadcrumb', [
        'title' => __('messages.system.index.title'),
        'title_table' => __('messages.system.index.table'),
    ])
    {{-- @include('product.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('product.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access') --}}
    <div class="grid md:grid-cols-[40%,1fr] md:gap-6 mt-4 text-left">
            <div class="">
                @foreach ($system as $key => $value)
                    <div class="mb-6">
                        <p class="text-xl font-semibold">{{ $value['label'] }}</p>
                        <p class="text-red-400">{{ $value['description'] }}</p>
                    </div>
                @endforeach
            </div>
            <div class="">
                @if ($errors->any())
                    <div class="text-red-400">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="max-w-md mr-auto" method="POST" action="/system" enctype="multipart/form-data">
                    @csrf
                    @foreach ($system as $key => $value)
                    @if (count($value['value']))
                        @foreach ($value['value'] as $keyy => $item)
                            @php
                                $name = $key.'_'.$keyy;
                            @endphp
                            <div class="relative z-0 w-full mb-5 group">

                                @switch($item['type'])
                                    @case('text')
                                        {{-- {!! renderSystemInput($name) !!} --}}
                                        <input type="text" name="config[{{ $name }}]"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder="" value="{{ old($name, $systemm[$name]) }}" />
                                        {{-- placeholder="" value="{{ old($name) }}" /> --}}
                                        @break
                                    @case('images')
                                        {{-- {!! renderSystemImages($name) !!} --}}
                                        @php
                                            // dd($systemm[$name]);
                                        @endphp
                                        <div class="relative z-0 w-full mb-5 group">
                                            <label class="block mb-1 text-sm font-medium text-gray-500 dark:text-white"
                                                for="user_avatar">{{ $item['label'] }}</label>
                                            <img class="image-targett cursor-pointer w-[157px] h-[148px] object-cover"
                                                src="{{ $systemm[$name] != null ? $systemm[$name] : asset('images/no_image.jpg') }}"
                                                {{-- src="{{ asset('images/no_image.jpg') }}" --}}
                                                alt="">
                                            <input name="image-filee"
                                                class="absolute top-5 opacity-0 block w-[157px] h-[148px] p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                aria-describedby="user_avatar_help" id="image-filee" type="file">
                                            {{-- <input name='config[{{ $name }}]' type="text" value="{{ old($name) }}" hidden> --}}
                                            <input name='config[{{ $name }}]' type="text" value="{{ old($name, $systemm[$name]) }}" hidden>
                                        </div>
                                        @break
                                    @case('textarea')
                                        <textarea id="description" rows="4" name="config[{{ $name }}]"
                                        class=" block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="{{ $item['label'] }}">{{ $systemm[$name] }}</textarea>
                                    @break
                                    @case('select')
                                        <select name="config[{{ $name }}]"
                                        class="select2 bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                                        <option value="-1">Chọn trạng thái</option>
                                        @foreach ($item['option'] as $key => $value)
                                            <option {{ $key == $systemm[$name] ? 'selected' : '' }} value="{{ $key }}">
                                                {{ $value }}</option>
                                        @endforeach
                                        </select>
                                    @break
                                    @case('favicon')
                                    <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white" for="user_avatar">{{ $item['label'] }}</label>
                                        <input value="{{ old('image') }}" name="config[{{ $name }}]"
                                        class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="user_avatar_help" id="user_avatar" type="file">
                                    @break
                                    @case('editor')
                                    <div class="relative w-full mb-5 group">
                                        <label for="description"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.description') }}</label>
                                        <textarea id="description" rows="4" name="config[{{ $name }}]"
                                            class="editor block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="{{ $item['label'] }}">{{ ($systemm[$name]) ?? '' }}</textarea>
                                    </div>
                                    @break
                                    @default
                                @endswitch
                                @if ($item['type'] == 'text')
                                <label for="email"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ $item['label'] }}</label>
                                @endif
                                </div>
                        @endforeach
                    @endif
                    @endforeach
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('messages.create') }}</button>
                </form>
            </div>
    </div>
@endsection

@section('script')
    @vite('resources/js/select2.js')
    @vite('resources/js/library.js')
    @vite('resources/js/seo.js')
    @vite('resources/js/variant.js')
@endsection
