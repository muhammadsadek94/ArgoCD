@extends('admin.components.layouts.crud.implementation.index')

@section('title', $module_name . " - " . end($breadcrumb)->title)

@section('breadcrumb')
	@include('admin.layouts.breadcrumb', [
		'page_title' => end($breadcrumb)->title,
		'crumbs' => $breadcrumb
	])
@endsection

@section('tabs-width', 'col-lg-7')
@section('search')
	@include('admin.components.layouts.crud.components.table-search')
@endsection


@section('call-to-actions')

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
				<th>@lang('lang.Actions')</th>
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



