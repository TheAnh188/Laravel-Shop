@extends('layouts.admin.app')

@section('content')
    @include('attribute_catalogue.components.breadcrumb', [
        'title' => __('messages.attribute_catalogue.index.title'),
        'title_table' => __('messages.attribute_catalogue.index.table'),
    ])
    @include('attribute_catalogue.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('attribute_catalogue.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
