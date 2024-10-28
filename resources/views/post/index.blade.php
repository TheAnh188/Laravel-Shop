@extends('layouts.admin.app')

@section('content')
    @include('post.components.breadcrumb', [
        'title' => __('messages.post.index.title'),
        'title_table' => __('messages.post.index.table'),
    ])
    @include('post.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('post.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
