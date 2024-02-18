@extends('admin.components.layouts.crud.implementation.index')

@section('call-to-actions')
	<div class="text-right">
		@if(isset($permitted_actions) && ($permitted_actions['create'] == null || is_permitted($permitted_actions['create'])))
			<a href="{{ url("{$route}/create?course_id=".request('course_id')) }}"
			   style="border-radius: 6px"
			   class="btn btn-primary waves-effect waves-light mb-2 mr-2">
				<i class="fa fa-plus mr-1"></i> @lang('lang.create')
			</a>
		@endif
	</div>
@endsection


@section('search')

    <div class="col-8 pr-2 offset-lg-4">
        <form class="">
            <div class="row align-item-end justify-content-end">

    		{!! Form::hidden('course_id', request('course_id')) !!}
                <div class="col-lg-8 col-12 d-flex align-items-center">
                    @include('admin.components.inputs.text', ['name'=>'search', 'label' =>' ', 'cols' => 'col-12', 'form_options' => [ 'placeholder' => 'Search']])
                </div>
                <div class="col-lg-4 col-12 d-flex align-items-center">
                    <button type="submit"
                            style="border-radius: 6px; border-color:#3DC47E"
                            class="btn btn-primary waves-effect btn-block waves-light width-sm ">Search
                        <i class="fa fa-search"></i>
                    </button>
                </div>

            </div>
        </form>
    </div>

@endsection



