@extends('layouts.admin.app')

@section('content')
    @include('post_catalogue.components.breadcrumb', [
        'title' => __('messages.post_catalogue.index.title'),
        'title_table' => __('messages.post_catalogue.index.table'),
    ])
    @include('post_catalogue.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('post_catalogue.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
