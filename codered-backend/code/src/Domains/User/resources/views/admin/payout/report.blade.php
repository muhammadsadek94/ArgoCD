@extends('admin.components.layouts.crud.layouts.form')

@section('title', $module_name . " - " . end($breadcrumb)->title)

@section('breadcrumb')
    @include('admin.layouts.breadcrumb', [
        'page_title' => end($breadcrumb)->title,
        'crumbs' => $breadcrumb
    ])
@endsection






@section('form')

<div class="panel-body row">
   

        {!! Form::open(['method' => 'POST', 'url' => ["{$route}/export-reports"], 'files' => true,'data-toggle' => '','class'=>'row']) !!}
	    <div class="col-12"></div>

        @include('admin.components.inputs.number', ['name' => 'year', 'label' => trans('Year'),'cols'=>"col-6", 'form_options'=> ['required'], 'value' => date('Y')])
        
        @include('admin.components.inputs.select', [
            'name' => 'quarter',
            'label' => trans('Quarter'),
            'form_options'=> ['required'],
            'select_options' => [
                1 => 'Quarter 1 [01/01 - 03/31]',
                2 => 'Quarter 2 [04/01 - 06/30]',
                3 => 'Quarter 3 [07/01 - 09/30]',
                4 => 'Quarter 4 [10/01 - 12/31]',
                ]
                ])
                @include('admin.components.inputs.number', ['name' => 'billable_revenue', 'label' => trans('Billable Revenue'),'cols'=>"col-6", 'form_options'=> ['required'], 'value' => date('Y')])
                @include('admin.components.inputs.success-btn', [ 'name'=>'submit','button_text' => "Export", 'button_extra_class' => 'float-left'])

        {!! Form::close() !!}

</div>

@endsection
