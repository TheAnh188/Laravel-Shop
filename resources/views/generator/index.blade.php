@extends('layouts.admin.app')

@section('content')
    @include('generator.components.breadcrumb', [
        'title' => __('messages.generator.index.title'),
        'title_table' => __('messages.generator.index.table'),
    ])
    @include('generator.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('generator.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
