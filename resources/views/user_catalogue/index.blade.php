@extends('layouts.admin.app')

@section('content')
    @include('user_catalogue.components.breadcrumb', [
        'title' => __('messages.user_catalogue.index.title'),
        'title_table' => __('messages.user_catalogue.index.table'),
    ])
    @include('user_catalogue.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('user_catalogue.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
