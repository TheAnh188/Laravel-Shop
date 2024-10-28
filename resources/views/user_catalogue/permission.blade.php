@extends('layouts.admin.app')

@section('content')
    @include('user_catalogue.components.breadcrumb', [
        'title' => __('messages.user_catalogue.permission.title'),
        'title_table' => __('messages.permission.index.title'),
    ])
    @can('accessibility', $authorization['canonical'])
        <form class="w-full" method="POST" action="/user-catalogue/grant-permission">
            @csrf
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-6 mb-3">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="w-[40%] px-4 py-3">{{ __('messages.permission.index.table') }}</th>
                            @foreach ($user_catalogues as $user_catalogue)
                                <th class="w-[calc(60%/{{ $user_catalogues->count() }})] px-4 py-3 text-center">
                                    {{ $user_catalogue->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($permissions) && is_object($permissions))
                            @foreach ($permissions as $permission)
                                <tr
                                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td scope="row" class="px-4 py-4 font-medium  whitespace-nowrap dark:text-white">
                                        <div class="flex justify-between">
                                            <span class="text-blue-800">{{ $permission->name }}</span>
                                            <span class="text-red-600">{{ $permission->canonical }}</span>
                                        </div>
                                    </td>
                                    @foreach ($user_catalogues as $user_catalogue)
                                        <td scope="row"
                                            class="px-4 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">
                                            <input type="checkbox" value="{{ $permission->id }}"
                                                name="permissions[{{ $user_catalogue->id }}][]"
                                                class="checkbox-permission w-4 h-4"
                                                {{ collect($user_catalogue->permissions)->contains('id', $permission->id) ? 'checked' : '' }}>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="w-full flex justify-end">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('messages.user_catalogue.permission.title') }}</button>
            </div>

        </form>
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
