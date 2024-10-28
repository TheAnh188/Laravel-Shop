@extends('layouts.admin.app')

@section('link')
@endsection

@section('content')
    @include('user_catalogue.components.breadcrumb', [
        'title' => __('messages.user_catalogue.delete.title'),
        'title_table' => __('messages.user_catalogue.delete.title'),
    ])
    @can('accessibility', $authorization['canonical'])
        <div class="grid md:grid-cols-[40%,1fr] md:gap-6 mt-4 text-left">
            <div class="">
                <p class="text-gray-600">{{ __('messages.notice.delete') }} <span
                        class="text-red-400">{{ $user_catalogue->name }}</span></p>
                <p class="text-gray-600">{{ __('messages.notice.sub_delete') }}</p>
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
                <form class="max-w-md mr-auto" method="POST" action="/user-catalogue/{{ $user_catalogue->id }}">
                    @csrf
                    @method('DELETE')
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="text" name="name" id="name"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="" value="{{ old('name', $user_catalogue->name ?? '') }}" readonly />
                        <label for="name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.user_catalogue.index.name') }}
                            <span class="text-red-500">(*)</span></label>
                    </div>
                    <button type="submit"
                        class="text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-300">{{ __('messages.delete') }}</button>
                </form>
            </div>
        </div>
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection
