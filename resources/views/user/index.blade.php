@extends('layouts.admin.app')

@section('content')
    @include('user.components.breadcrumb', [
        'title' => __('messages.user.index.title'),
        'title_table' => __('messages.user.index.table'),
    ])
    @include('user.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('user.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
