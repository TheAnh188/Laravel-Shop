@extends('layouts.admin.app')

@section('link')
@endsection

@section('content')
    @include('user.components.breadcrumb', [
        'title' => __('messages.user.create.title'),
        'title_table' => __('messages.user.create.title'),
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
                <form class="max-w-md mr-auto" method="POST" action="/user" enctype="multipart/form-data">
                    @csrf
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="text" name="email" id="email"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="" value="{{ old('email') }}" />
                        <label for="email"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.user.index.email') }}
                            <span class="text-red-500">(*)</span></label>
                    </div>

                    <div class="relative z-0 w-full mb-5 group">
                        <input type="text" name="name" id="name"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="" value="{{ old('name') }}" />
                        <label for="name"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.user.index.name') }}
                            <span class="text-red-500">(*)</span></label>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <input type="date" name="birthday" id="birthday" value="{{ old('birthday') }}"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " />
                        <label for="birthday"
                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.user.store.dob') }}<span class="text-red-500">(*)</span></label>
                    </div>
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="password" name="password" id="password"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="password"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.user.store.password') }}<span class="text-red-500">(*)</span></label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="password" name="repeat_password" id="repeat_password"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="repeat_password"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.user.store.retyped_password') }}<span class="text-red-500">(*)</span></label>
                        </div>

                    </div>
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="phone"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.user.index.phone_number') }}<span class="text-red-500">(*)</span></label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="address"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">{{ __('messages.user.index.address') }}</label>
                        </div>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white" for="user_avatar">{{ __('messages.user.index.avatar') }}</label>
                        <input value="{{ old('image') }}" name="image"
                            class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                            aria-describedby="user_avatar_help" id="user_avatar" type="file">
                    </div>
                    @php
                        $userCatalogue = ['Chọn Nhóm Thành Viên', 'Quản trị viên', 'Cộng tác viên'];
                    @endphp
                    <div class="relative z-0 w-full mb-5 group">
                        <select id="countries" name="user_catalogue_id"
                            class="select2 bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                            {{-- <option selected value="0">Chọn Nhóm Thành Viên</option> --}}
                            @foreach (__('messages.user.user_catalogue') as $key => $value)
                                <option @if (old('user_catalogue_id') == $key) selected @endif value="{{ $key }}">
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <select name="province_id" data-target="districts"
                            class="location province select2 bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white ">
                            <option selected>{{ __('messages.user.store.province') }}</option>
                            @if (isset($provinces))
                                @foreach ($provinces as $province)
                                    <option @if (old('province_id') == $province->code) selected @endif value="{{ $province->code }}">
                                        {{ $province->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full mb-5 group">
                            <select name="district_id" data-target="wards"
                                class="location districts select2 bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                                <option selected>{{ __('messages.user.store.district') }}</option>
                            </select>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <select name="ward_id"
                                class="wards select2 bg-white text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white border-r-8 border-solid border-white">
                                <option selected>{{ __('messages.user.store.ward') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="relative z-0 w-full mb-5 group">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('messages.user.store.description') }}</label>
                        <textarea id="description" rows="4" aria-valuetext="{{ old('description') }}" name="description"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="{{ __('messages.user.store.description') }}"></textarea>
                    </div>
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ __('messages.create') }}</button>
                </form>
            </div>
        </div>
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    <script>
        var province_id = '{{ old('province_id') }}'
        var district_id = '{{ old('district_id') }}'
        var ward_id = '{{ old('ward_id') }}'
    </script>
    @vite('resources/js/location.js')
@endsection
