@extends('layouts.admin.app')

@section('content')
    @include('attribute.components.breadcrumb', [
        'title' => __('messages.attribute.index.title'),
        'title_table' => __('messages.attribute.index.table'),
    ])
    @include('attribute.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('attribute.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
