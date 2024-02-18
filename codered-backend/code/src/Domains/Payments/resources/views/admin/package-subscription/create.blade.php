@extends('admin.components.layouts.crud.layouts.form')

@section('title', $module_name . " - " . end($breadcrumb)->title)

@section('breadcrumb')
    @include('admin.layouts.breadcrumb', [
        'page_title' => end($breadcrumb)->title,
        'crumbs' => $breadcrumb
    ])
@endsection


    @section('form')
        {!! Form::open(['method'=>'POST','url' => "$route", 'files'=>true,'data-toggle'=> 'ajax', 'reset'=>"true", 'data-refresh-page' => "true"]) !!}
            @include ("{$view_path}.form",['submitButton' => trans('lang.create')])
        {!! Form::close() !!}
    @endsection
