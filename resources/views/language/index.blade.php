@extends('layouts.admin.app')

@section('content')
    @include('language.components.breadcrumb', [
        'title' => __('messages.language.index.title'),
        'title_table' => __('messages.language.index.table'),
    ])
    @include('language.components.filter')
    @can('accessibility', $authorization['canonical'])
        @include('language.components.table')
    @endcan
    @include('layouts.admin.unauthorized_access')
@endsection

@section('script')
    @vite('resources/js/library.js')
@endsection
