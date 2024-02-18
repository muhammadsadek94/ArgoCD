@extends('admin.components.layouts.crud.implementation.index')

@section('call-to-actions')
@endsection
@section('thead')
    <tr>
        <th>@lang('Name')</th>
        <th>@lang('Status')</th>
        <th>@lang('Email')</th>
        <th>@lang('lang.Actions')</th>
    </tr>
@endsection

@section('tbody')
    @include("{$view_path}.loop")



@endsection
