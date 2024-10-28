@extends('layouts.admin.app')
{{-- moduleCanonical = post-catalogue
tableName = post_catalogue --}}
@section('content')
    @include('{tableName}.components.breadcrumb', [
        'title' => __('messages.{tableName}.index.title'),
        'title_table' => __('messages.{tableName}.index.table'),
    ])
    @include('{tableName}.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('{tableName}.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
