@extends('admin.components.layouts.crud.layouts.form')

@section('title', $module_name . " - " . end($breadcrumb)->title)

@section('breadcrumb')
    @include('admin.layouts.breadcrumb', [
        'page_title' => end($breadcrumb)->title,
        'crumbs' => $breadcrumb
    ])
@endsection

@section('form')
    {!! Form:: model($row,['method'=>'PATCH','url' => [$route, $row->id], 'files'=>true,'data-toggle'=> 'ajax']) !!}
        @include ("{$view_path}.form",['submitButton' => trans('lang.update')])
    {!! Form::close() !!}
@endsection

