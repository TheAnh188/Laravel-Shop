<!DOCTYPE html>
<html lang="en" class="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('head_script')
    @vite(['resources/css/normalize.css', 'resources/css/app.css', 'resources/js/app.js', 'resources/fontawesome-free-6.5.2-web/css/all.min.css'])
    @yield('link')
    @yield('style')
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        @include('layouts.admin.side_bar')
        <div class="w-full flex flex-col h-screen overflow-y-hidden">

            <!-- Desktop Header -->
            @include('layouts.admin.desktop_header')

            <!-- Mobile Header & Nav -->
            @include('layouts.admin.mobile_header')

            <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
                <main class="w-full flex-grow p-6">
                    @yield('content')
                </main>
                @include('layouts.admin.footer')
            </div>
        </div>
        @yield('script')
    </div>
</body>

</html>
