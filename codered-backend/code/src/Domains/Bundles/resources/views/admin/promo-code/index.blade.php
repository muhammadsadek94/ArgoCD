@extends('admin.components.layouts.crud.implementation.index')

@section('call-to-actions')
@if($total_promo_codes_count< 1) 
    <div class="text-right">
        @if(isset($permitted_actions) && ($permitted_actions['create'] == null || is_permitted($permitted_actions['create'])))
            <a href="{{ url("{$route}/create") }}"
               class="btn btn-danger waves-effect waves-light mb-2 mr-2">
                <i class="fa fa-plus mr-1"></i> @lang('lang.create')
            </a>
        @endif
    </div>
 @endif
@endsection