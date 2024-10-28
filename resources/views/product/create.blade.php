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
    @include('product.components.breadcrumb', [
        'title' => __('messages.product.create.title'),
        'title_table' => __('messages.product.create.title'),
    ])
    @can('accessibility', $authorization['canonical'])
        <div class="text-left">
            <div class="mb-2">
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
                <form class="w-full mr-auto" method="POST" action="/product" enctype="multipart/form-data">
                    @csrf
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full mb-5 group">
                            <select name="product_catalogue_id"
                                class="select2 bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                                @foreach ($dropdown as $key => $value)
                                    <option @if (old('product_catalogue_id') == $key) selected @endif value="{{ $key }}">
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <select multiple name="catalogue[]"
                                class="select2 bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                                @foreach ($dropdown as $key => $value)
                                    <option @if (is_array(old('catalogue', isset($product->catalogue) ? $product->catalogue : [])) &&
                                            in_array($key, old('catalogue', isset($product->catalogue) ? $product->catalogue : []))) selected @endif value="{{ $key }}">
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full mb-5 group">
                            <select name="follow"
                                class="select2 bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                                @foreach (__('messages.follow_input') as $key => $value)
                                    <option @if (old('follow') == $key) selected @endif value="{{ $key }}">
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <select name="status"
                                class="select2 bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                                @foreach (__('messages.status_input') as $key => $value)
                                    <option @if (old('status') == $key) selected @endif value="{{ $key }}">
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="name" id="name"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" value="{{ old('name') }}" />
                            <label for="name"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.product.index.name') }}
                                <span class="text-red-500">(*)</span></label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="code" id="code"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" value="{{ old('code', $product->code ?? time()) }}" />
                            <label for="code"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.product.index.code') }}
                                <span class="text-red-500">(*)</span></label>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="made_in" id="made_in"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" value="{{ old('made_in') }}" />
                            <label for="made_in"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.product.index.made_in') }}
                                <span class="text-red-500">(*)</span></label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="price" id="price"
                                class="int block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=""
                                value="{{ old('price', isset($product) ? number_format($product->price, 0, ',', '.') : '') }}" />
                            <label for="price"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.product.index.price') }}
                                <span class="text-red-500">(*)</span></label>
                        </div>
                    </div>
                    <div class="relative w-full mb-5 group">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.description') }}</label>
                        <textarea id="description" rows="4" name="description"
                            class="editor block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="{{ __('messages.description') }}">{{ old('description') }}</textarea>
                    </div>
                    <div class="relative w-full mb-5 group">
                        <label for="content"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.content') }}</label>
                        <textarea id="content" rows="4" name="content"
                            class="editor block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="{{ __('messages.content') }}">{{ old('content') }}</textarea>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <label for="seo"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.seo') }}</label>
                        <div class="bg-white border-2 rounded-lg p-4">
                            <div class="w-full text-[#1a0dab] meta_title-preview">
                                {{ old('meta_title') ?? __('messages.meta_title-preview') }}
                            </div>
                            <div class="w-full text-green-600 canonical-preview">
                                {{ env('APP_URL') . '/' . old('canonical') ?? __('messages.canonical-preview') }}</div>
                            <div class="w-full text-gray-500 meta_description-preview">
                                {{ old('meta_description') ?? __('messages.meta_description-preview') }}</div>
                        </div>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="text" name="meta_title" id="meta_title"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="" value="{{ old('meta_title') }}" />
                        <label for="meta_title"
                            class="w-full peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.meta_title') }}
                            <span class="text-red-500">(*)</span><span
                                class="count_meta-title ml-48">{{ __('messages.character') }} :
                                0</span>
                        </label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="text" name="meta_keyword" id="meta_keyword"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="" value="{{ old('meta_keyword') }}" />
                        <label for="meta_keyword"
                            class="w-full peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.meta_keyword') }}
                            <span class="text-red-500">(*)</span><span
                                class="count_meta-title ml-48">{{ __('messages.character') }} :
                                0</span>
                        </label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <textarea id="meta_description" rows="4" name="meta_description"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="{{ __('messages.meta_description') }}">{{ old('meta_description') }}</textarea>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="text" name="canonical" id="canonical"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="" value="{{ env('APP_URL') . '/' . old('canonical') ?? env('APP_URL') }}" />
                        <label for="canonical"
                            class="w-full peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.canonical') }}
                            <span class="text-red-500">(*)</span>
                        </label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                            for="user_avatar">{{ __('messages.image') }}</label>
                        <img class="image-target cursor-pointer w-[157px] h-[148px] object-cover"
                            src="{{ old('image') == '' || old('image') == null ? asset('images/no_image.jpg') : old('image') }}"
                            alt="">
                        <input name="image-file"
                            class="absolute top-5 opacity-0 block w-[157px] p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            aria-describedby="user_avatar_help" id="image-file" type="file">
                        <input name='image' type="text" value="{{ old('image') }}" hidden>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <div class="flex justify-between">
                            <label for="album-file"
                                class="inline-block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.album') }}</label>
                            <div>
                                <input type="file" multiple name="album-file" id="album-file"
                                    class="album-file hidden inline-block text-blue-700 mb-2 text-sm font-medium dark:text-white">
                                <span
                                    class="album-target cursor-pointer inline-block text-blue-700 mb-2 text-sm font-medium dark:text-white">{{ __('messages.choose_album') }}</span>
                            </div>
                        </div>
                        <div
                            class="no-album {{ old('album') ? 'hidden' : '' }} border-2 border-dashed border-blue-200 flex justify-center">
                            <img class="album-target cursor-pointer w-[157px] h-[148px] object-cover"
                                src="{{ asset('images/no_image-removebg.png') }}" alt="">
                        </div>
                        <div class="selectedAlbum {{ old('album') ? '' : 'hidden' }} w-full flex flex-wrap border-2 border-dashed border-blue-200"
                            id="sortable">
                            @if (old('album'))
                                @foreach (old('album') as $key => $value)
                                    <div class="image-in-album cursor-pointer w-[157px] h-[157px] bg-cover bg-center mx-0.5 my-0.5 text-right"
                                        style="background-image: url({{ $value }})">
                                        <span
                                            class="delete-image-in-album inline-block bg-red-500 text-white px-1 py-0.5 rounded-md"><i
                                                class="fa-solid fa-xmark"></i></span>
                                        <input hidden type="text" name="album[]" value="{{ $value }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="w-full bg-white rounded-lg p-2 mb-5">
                        <h5 class="uppercase text-gray-500 mb-2">
                            {{ __('messages.product.create.variant.product_with_multiple_versions') }}</h5>
                        <span
                            class="text-gray-500 text-sm">{{ __('messages.product.create.variant.variant_description') }}{{ __('messages.product.create.variant.example') }}{{ __('messages.product.create.variant.clothing') }}<strong
                                class="text-red-500">{{ __('messages.product.create.variant.color') }}</strong>{{ __('messages.product.create.variant.or') }}<strong
                                class="text-red-500">{{ __('messages.product.create.variant.size') }}</strong>{{ __('messages.product.create.variant.variant_list_description') }}</span>
                        <div class="flex justify-start items-center mt-4">
                            <input type="checkbox" value="1" id="variant-checkbox" class="w-5 h-5 setup-variant"
                                name="accept" {{ old('accept') == 1 ? 'checked' : '' }}>
                            <label for="variant-checkbox"
                                class="text-gray-500 text-sm ml-2 select-none">{{ __('messages.product.create.variant.allow_variants') }}</label>
                        </div>
                        <div class="{{ old('accept') == 1 ? '' : 'hidden' }} variant-info">
                            <div class="variant-info-body">
                                @if (old('attributeCatalogue'))
                                    @foreach (old('attributeCatalogue') as $attrKey => $attrValue)
                                        <div class="variant-item">
                                            <div class="relative z-0 w-full mb-2 group mt-5 ">
                                                <select name="attributeCatalogue[]"
                                                    class="select2 choose-attribute-catalogue bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                                                    <option class="" value="0">
                                                        {{ __('messages.product.create.variant.choose_attribute') }}</option>
                                                    @foreach ($attribute_catalogues as $key => $value)
                                                        <option {{ $attrValue == $value->id ? 'selected' : '' }}
                                                            value="{{ $value->id }}">
                                                            {{ $value->attribute_catalogue_language->first()->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="relative z-0 w-full group grid grid-cols-[90%,1fr] gap-6 mb-8">
                                                <div class="w-full">
                                                    <label for="attribute"
                                                        class="text-sm text-[#1791f2]">{{ __('messages.product.create.variant.choose_attribute_value') }}</label>
                                                    <div class="disabled-attribute-input">
                                                        <select multiple data-catid="{{ $attrValue }}"
                                                            name="attribute[{{ $attrValue }}][]"
                                                            class="select2 selectVarinant variant-{{ $attrValue }} bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="w-full text-center flex items-end">
                                                    <button type="button"
                                                        class="bg-red-500 text-white px-3 py-2.5 rounded-md mr-2 remove-attribute-catalogue">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="relative z-0 w-full group mt-5 variant-button--wrapper">
                                <button type="button"
                                    class="add-variant border-2 px-5 py-2 rounded-md border-dashed border-[#1791f2] text-[#1791f2] transition-all hover:bg-gray-100">{{ __('messages.product.create.variant.add_new_variant') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="w-full bg-white rounded-lg p-2 mb-5">
                        <h5 class="uppercase text-gray-500 mb-2">
                            {{ __('messages.product.create.variant.variants') }}</h5>
                        <table class="variantTable w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <button type="submit"
                        class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('messages.create') }}</button>
                </form>
            </div>
        </div>
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    <script>
        var suffix = '{{ config('shop.general.suffix') }}';
        var base_url = '{{ env('APP_URL') }}' + '/';
        var choose_attribute = '{{ __('messages.product.create.variant.choose_attribute') }}';
        var choose_attribute_value = '{{ __('messages.product.create.variant.choose_attribute_value') }}';
        var add_new_variant = '{{ __('messages.product.create.variant.add_new_variant') }}';
        var attribute_catalogues = @json(
            $attribute_catalogues->filter(function ($item) {
                    return $item->attribute_catalogue_language->first() !== null;
                })->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->attribute_catalogue_language->first()->name,
                    ];
                })->values());
        var update_variant_info = '{{ __('messages.product.create.variant.update_variant_info') }}';
        var cancel = '{{ __('messages.product.create.variant.cancel') }}';
        var save = '{{ __('messages.product.create.variant.save') }}';
        var album = '{{ __('messages.album') }}';
        var choose_album = '{{ __('messages.choose_album') }}';
        var attribute = @json(old('attribute', []));
        var variant = '{{ base64_encode(json_encode(old('variant'))) }}';
    </script>
    @vite('resources/js/select2.js')
    @vite('resources/js/library.js')
    @vite('resources/js/seo.js')
    @vite('resources/js/variant.js')
@endsection
