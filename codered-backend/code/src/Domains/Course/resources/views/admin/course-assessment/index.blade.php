@extends('admin.components.layouts.crud.implementation.index')

@section('call-to-actions')
	<div class="text-right">
		@if(isset($permitted_actions) && ($permitted_actions['create'] == null || is_permitted($permitted_actions['create'])))
			<a href="{{ url("{$route}/create?course_id=".request('course_id')) }}"
				style="background-color:#E83C30; border-radius: 6px; border-color:#E83C30"
			   class="btn btn-danger waves-effect waves-light mb-2 mr-2">
				<i class="fa fa-plus mr-1"></i> @lang('lang.create')
			</a>
		@endif
	</div>
@endsection


@section('search')

	<form class="form-inline row">
		{!! Form::hidden('course_id', request('course_id')) !!}
		@include('admin.components.inputs.text', ['name'=>'search', 'label' => ' ', 'cols' => 'col-12 col-xl-3 col-md-9 col-xs-12'])
		@include('admin.components.inputs.success-btn', ['button_text' => trans('lang.search'), 'cols' => 'col-12 col-xl-3 col-md-3', 'icon' => 'fa fa-search'])
	</form>

@endsection



