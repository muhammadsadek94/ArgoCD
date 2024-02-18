@extends('admin.components.layouts.crud.layouts.form')

@section('title', $module_name . " - " . end($breadcrumb)->title)

@section('breadcrumb')
    @include('admin.layouts.breadcrumb', [
        'page_title' => end($breadcrumb)->title,
        'crumbs' => $breadcrumb
    ])
@endsection


@section('form')
    {!! Form::open(['method'=>'POST','url' => "$route", 'files'=>true,'class'=> 'voucherDownloadForm']) !!}
    @include ("{$view_path}.form",['submitButton' => trans('lang.create')])
    {!! Form::close() !!}
@endsection

{{-- @push('script-bottom')
    <script>
        $(document).ready(function() {
            <!--$('.voucherDownloadForm').submit(() => setTimeout(() => window.location.href = "{{ url("$admin_base_url/voucher") }}", 5000));-->
        })
    </script>
@endpush --}}
