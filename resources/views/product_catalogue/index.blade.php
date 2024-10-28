@extends('layouts.admin.app')
{{-- moduleCanonical = post-catalogue
tableName = post_catalogue --}}
@section('content')
    @include('product_catalogue.components.breadcrumb', [
        'title' => __('messages.product_catalogue.index.title'),
        'title_table' => __('messages.product_catalogue.index.table'),
    ])
    @include('product_catalogue.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('product_catalogue.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
