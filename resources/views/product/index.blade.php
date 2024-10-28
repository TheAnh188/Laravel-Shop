@extends('layouts.admin.app')

@section('content')
    @include('product.components.breadcrumb', [
        'title' => __('messages.product.index.title'),
        'title_table' => __('messages.product.index.table'),
    ])
    @include('product.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('product.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
