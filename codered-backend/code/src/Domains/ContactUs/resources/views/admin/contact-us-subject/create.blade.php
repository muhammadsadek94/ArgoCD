@extends('admin.layouts.master')

@section('title',trans("contact_us::lang.Contact Us Subjects"))


@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url("/admin/dashboard") }}">@lang("geography::lang.admin")</a></li>
                        <li class="breadcrumb-item ">@lang("admin::lang.lookups and configuration")</li>
                        <li class="breadcrumb-item ">@lang("lang.setting")</li>
                        <li class="breadcrumb-item "><a href="{{ url("/admin/contact-us/contact-us-subject") }}">@lang("admin::lang.contact us subject")</a></li>
                        <li class="breadcrumb-item active">@lang("lang.Create")</li>
                    </ol>
                </div>
                <h4 class="page-title">@lang("lang.".end($breadcrumb))</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {!! Form:: open(['method'=>'POST','url' => "/$route", 'files'=>true,'class' => 'ajax-form-request']) !!}
                        @include ("$view_path.form",['submitButton' => Lang::get('lang.create')])
                    {!! Form::close() !!}
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>

@stop
