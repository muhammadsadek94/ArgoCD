@extends('admin.components.layouts.crud.implementation.index')


@section('search')
    <form class="px-3">
        <div class="row align-item-end justify-content-end pr-2 mb-3">
            {!! Form::hidden('video_id', request('video_id')) !!}
            @include('admin.components.inputs.text', ['name'=>'search', 'label' =>' ', 'cols' => 'col-12 col-xl-3 col-md-9 col-xs-12 d-flex align-items-center m-0'])
            <button id="Search"  type="submit"  class="btn btn-primary radius-5 waves-effect waves-light width-sm">
                Search
                <i class="fa fa-search"></i>
            </button>
        </div>
    </form>
@endsection

@section('call-to-actions')
	<div class="text-right">
		
	</div>
@endsection


@section('table')
    <table class="table table-centered table-hover table-striped mb-0">
        <thead class="thead-light">
        @hasSection('thead')
            @yield('thead')
        @else
            <tr>
                @foreach ($select_columns as $column)
                    <th>{{ $column['name'] }}</th>
                @endforeach
               
            </tr>
        @endif
        </thead>
        <tbody>
        @hasSection('tbody')
            @yield('tbody')
        @else
             @include("{$view_path}.loop")
        @endif
        </tbody>
    </table>
@endsection

@section('pagination')
    @include('admin.components.layouts.crud.components.pagination', ['appends' => ['search' => old('search')]])
@endsection
