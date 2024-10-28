@extends('layouts.admin.app')

@section('content')
    @include('permission.components.breadcrumb', [
        'title' => __('messages.permission.index.title'),
        'title_table' => __('messages.permission.index.table'),
    ])
    @include('permission.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('permission.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
