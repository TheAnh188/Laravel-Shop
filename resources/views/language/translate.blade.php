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
    @include('post_catalogue.components.breadcrumb', [
        'title' => __('messages.post_catalogue.create.title'),
        'title_table' => __('messages.post_catalogue.create.title'),
    ])
    @can('accessibility', $authorization['canonical'])
        {{-- <div class="grid md:grid-cols-[40%,1fr] md:gap-6 mt-4 text-left"> --}}
        <div class="mb-5">
            <p class="text-xl font-semibold">{{ __('messages.notice.store') }}</p>
            <p class="text-red-400">{{ __('messages.notice.sub_store') }}</p>
        </div>
        <div class="">
            @if ($errors->any())
                <div class="text-red-400 mb-5">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="w-full" method="POST" action="/language/translate/{{ $option['id'] }}">
                @csrf
                <input type="text" name="option[id]" value="{{ $option['id'] }}" hidden>
                <input type="text" name="option[language_id]" value="{{ $option['language_id'] }}" hidden>
                <input type="text" name="option[model_name]" value="{{ $option['model_name'] }}" hidden>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="name" id="name" disabled
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" value="{{ old('name', $model->name ?? '') }}" />
                            <label for="name"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.post_catalogue.index.name') }}
                                <span class="text-red-500">(*)</span></label>
                        </div>
                        <div class="relative w-full mb-5 group">
                            <label for="description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.description') }}</label>
                            <textarea id="description" rows="4" name="description"
                                class="editor--disabled block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('messages.description') }}">{{ old('description', $model->description ?? '') }}</textarea>
                        </div>
                        <div class="relative w-full mb-5 group">
                            <label for="content"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.content') }}</label>
                            <textarea id="content" rows="4" name="content" disabled
                                class="editor--disabled block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('messages.content') }}">{{ old('content', $model->content ?? '') }}</textarea>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <label for="seo"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.seo') }}</label>
                            <div class="bg-white border-2 rounded-lg p-4">
                                <div class="w-full text-[#1a0dab] meta_title-preview">
                                    {{ old('meta_title', $model->meta_title ?? '') ?? __('messages.meta_title-preview') }}
                                </div>
                                <div class="w-full text-green-600 canonical-preview">
                                    {{ env('APP_URL') . '/' . old('canonical', $model->canonical ?? '') ?? __('messages.canonical-preview') }}
                                </div>
                                <div class="w-full text-gray-500 meta_description-preview">
                                    {{ old('meta_description', $model->meta_description ?? '') ?? __('messages.meta_description-preview') }}
                                </div>
                            </div>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="meta_title" id="meta_title" disabled
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" value="{{ old('meta_title', $model->meta_title ?? '') }}" />
                            <label for="meta_title"
                                class="w-full peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.meta_title') }}
                                <span class="text-red-500">(*)</span><span
                                    class="count_meta-title ml-48">{{ __('messages.character') }} :
                                    0</span>
                            </label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="meta_keyword" id="meta_keyword" disabled
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" value="{{ old('meta_keyword', $model->meta_keyword ?? '') }}" />
                            <label for="meta_keyword"
                                class="w-full peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.meta_keyword') }}
                                <span class="text-red-500">(*)</span><span
                                    class="count_meta-title ml-48">{{ __('messages.character') }} :
                                    0</span>
                            </label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <textarea id="meta_description" rows="4" name="meta_description" disabled
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('messages.meta_description') }}">{{ old('meta_description', $model->meta_description ?? '') }}</textarea>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="canonical" id="canonical" disabled
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=""
                                value="{{ env('APP_URL') . '/' . old('canonical', $model->canonical ?? '') ?? env('APP_URL') . '/' }}" />
                            <label for="canonical"
                                class="w-full peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.canonical') }}
                                <span class="text-red-500">(*)</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="translated_name" id="translated_name"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" value="{{ old('translated_name', $translated_model->name ?? '') }}" />
                            <label for="translated_name"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.post_catalogue.index.name') }}
                                <span class="text-red-500">(*)</span></label>
                        </div>
                        <div class="relative w-full mb-5 group">
                            <label for="translated_description"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.description') }}</label>
                            <textarea id="translated_description" rows="4" name="translated_description"
                                class="editor block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('messages.description') }}">{{ old('translated_description', $translated_model->description ?? '') }}</textarea>
                        </div>
                        <div class="relative w-full mb-5 group">
                            <label for="translated_content"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.content') }}</label>
                            <textarea id="translated_content" rows="4" name="translated_content"
                                class="editor block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('messages.content') }}">{{ old('translated_content', $translated_model->content ?? '') }}</textarea>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <label for="seo"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.seo') }}</label>
                            <div class="bg-white border-2 rounded-lg p-4">
                                <div class="w-full text-[#1a0dab] translated_meta_title-preview">
                                    {{ old('translated_meta_title', $translated_model->meta_title ?? '') ?? __('messages.meta_title-preview') }}
                                </div>
                                <div class="w-full text-green-600 translated_canonical-preview">
                                    {{ env('APP_URL') . '/' . old('translated_canonical', $translated_model->canonical ?? '') ?? __('messages.canonical-preview') }}
                                </div>
                                <div class="w-full text-gray-500 translated_meta_description-preview">
                                    {{ old('translated_meta_description', $translated_model->meta_description ?? '') ?? __('messages.meta_description-preview') }}
                                </div>
                            </div>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="translated_meta_title" id="translated_meta_title"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=""
                                value="{{ old('translated_meta_title', $translated_model->meta_title ?? '') }}" />
                            <label for="translated_meta_title"
                                class="w-full peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.meta_title') }}
                                <span class="text-red-500">(*)</span><span
                                    class="count_meta-title ml-48">{{ __('messages.character') }} :
                                    0</span>
                            </label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="translated_meta_keyword" id="translated_meta_keyword"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=""
                                value="{{ old('translated_meta_keyword', $translated_model->meta_keyword ?? '') }}" />
                            <label for="translated_meta_keyword"
                                class="w-full peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.meta_keyword') }}
                                <span class="text-red-500">(*)</span><span
                                    class="count_meta-title ml-48">{{ __('messages.character') }} :
                                    0</span>
                            </label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <textarea id="translated_meta_description" rows="4" name="translated_meta_description"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="{{ __('messages.meta_description') }}">{{ old('translated_meta_description', $translated_model->meta_description ?? '') }}</textarea>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="translated_canonical" id="translated_canonical"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=""
                                value="{{ env('APP_URL') . '/' . old('translated_canonical', $translated_model->canonical ?? '') ?? env('APP_URL') . '/' }}" />
                            <label for="translated_canonical"
                                class="w-full peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.canonical') }}
                                <span class="text-red-500">(*)</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="w-full text-right">
                    <button type="submit" id="clear-selected-image"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('messages.update') }}</button>
                </div>
            </form>
        </div>
        {{-- </div> --}}
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    <script>
        var suffix = '{{ config('shop.general.suffix') }}';
        var base_url = '{{ env('APP_URL') }}' + '/';
    </script>
    @vite('resources/js/select2.js')
    @vite('resources/js/library.js')
    @vite('resources/js/seo.js')
@endsection
