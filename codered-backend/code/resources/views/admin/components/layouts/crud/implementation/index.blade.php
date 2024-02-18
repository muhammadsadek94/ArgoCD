@extends('admin.components.layouts.crud.layouts.table-view')

@section('title', $module_name . " - " . end($breadcrumb)->title)


@section('tabs')
@if(\View::exists("{$view_path}.navtabs"))
    @include("{$view_path}.navtabs")
@endif
@endsection

@section('breadcrumb')
    @include('admin.layouts.breadcrumb', [
        'page_title' => end($breadcrumb)->title,
        'crumbs' => $breadcrumb
    ])
@endsection

@section('search')
    @include('admin.components.layouts.crud.components.table-search')
@endsection


@section('call-to-actions')
    <div class="text-right">
        @if(isset($permitted_actions) && ($permitted_actions['create'] == null || is_permitted($permitted_actions['create'])))
            <a href="{{ url("{$route}/create") }}"
               class="btn btn-primary radius-5 px-4 waves-effect waves-light mb-2">
                @lang('lang.create')
            </a>
        @endif
    </div>
@endsection


@section('table')
    <table class="table custom-table table-centered mb-0">
        <thead class="thead-light">
        @hasSection('thead')
            @yield('thead')
        @else
            <tr>
                @foreach ($select_columns as $index => $column)
                    <th class="{{ $index == 0 ? 'ps-4 rounded-start border-0' : 'border-0'  }}">{{ $column['name'] }}</th>
                @endforeach
                <th class="rounded-end">@lang('lang.Actions')</th>
            </tr>
        @endif
        </thead>
        <tbody>
        @hasSection('tbody')
            @yield('tbody')
        @else
            @include("admin.components.layouts.crud.components.loop")
        @endif
        </tbody>
    </table>
@endsection

@section('pagination')
    @include('admin.components.layouts.crud.components.pagination', ['appends' => ['search' => old('search')]])
@endsection



