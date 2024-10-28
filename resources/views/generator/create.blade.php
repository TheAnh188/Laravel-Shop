@extends('layouts.admin.app')

@section('link')
@endsection

@section('content')
    @include('generator.components.breadcrumb', [
        'title' => __('messages.generator.create.title'),
        'title_table' => __('messages.generator.create.title'),
    ])
    @can('accessibility', $authorization['canonical'])
        <div class="grid md:grid-cols-[40%,1fr] md:gap-6 mt-4 text-left">
            <div class="">
                <p class="text-xl font-semibold">{{ __('messages.notice.store') }}</p>
                <p class="text-red-400">{{ __('messages.notice.sub_store') }}</p>
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
                <form class="w-full" method="POST" action="/generator" enctype="multipart/form-data">
                    @csrf
                    <div class="relative z-0 w-full mb-5 group">
                        <select name="module_type"
                            class="select2 bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                            <option value="0">Chọn Loại Module</option>
                            <option value="1">Module Danh Mục</option>
                            <option value="2">Module Chi Tiết</option>
                            <option value="3">Module Khác</option>
                        </select>
                    </div>
                    <div class="grid md:grid-cols-3 md:gap-6">
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="name" id="name"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" value="{{ old('name') }}" />
                            <label for="name"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.generator.index.name') }}
                                <span class="text-red-500">(*)</span></label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="display_name" id="display_name"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" value="{{ old('display_name') }}" />
                            <label for="display_name"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.generator.index.display_name') }}
                                <span class="text-red-500">(*)</span></label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="canonical" id="canonical"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" value="{{ old('canonical') }}" />
                            <label for="canonical"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.canonical') }}
                                <span class="text-red-500">(*)</span></label>
                        </div>
                    </div>
                    <div class="relative w-full mb-5 group">
                        <label for="schema" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.generator.index.schema') }}</label>
                        <textarea id="schema" rows="4" name="schema"
                            class="block p-2.5 h-80 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="{{ __('messages.generator.index.schema') }}">{{ old('schema') }}</textarea>
                    </div>
                    <div class="w-full text-right">
                        <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('messages.create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/select2.js')
    @vite('resources/js/library.js')
    @vite('resources/js/seo.js')
@endsection

