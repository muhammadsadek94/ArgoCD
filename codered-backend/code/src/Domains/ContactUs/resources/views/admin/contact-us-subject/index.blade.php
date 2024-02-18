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
                        <li class="breadcrumb-item active">@lang("lang.Index")</li>
                    </ol>
                </div>
                <h4 class="page-title">{{$module_name}}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    @if(session()->has("success"))
        <div class="alert alert-success">{{session()->get("success")}}</div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-lg-8">
                            <form class="form-inline" >
                                <div class="form-group mb-2">
                                    <label for="inputPassword2" class="sr-only">Search</label>
                                    {!! Form::search('search', old('search'), ['class'=>'form-control', 'placeholder'=>trans('lang.search')]) !!}
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-4">
                            <div class="text-lg-right">
                                <a href="{{ url("$route/create") }}" class="btn btn-danger waves-effect waves-light mb-2 mr-2"><i class="fa fa-plus mr-1"></i> Create</a>
                                {{-- <button type="button" class="btn btn-light waves-effect mb-2">Export</button> --}}
                            </div>
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    @foreach ($columns as $column)
                                        <th>{{ trans("lang.$column") }}</th>
                                    @endforeach
                                    <th>@lang("lang.activation")</th>
                                    <th>@lang('lang.Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include("$view_path.loop")
                            </tbody>
                        </table>
                    </div>

                    <ul class="pagination pagination-rounded justify-content-end my-2">
                        {{$rows->appends(['search' => old('search')])->links()}}
                    </ul>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->

@endsection

