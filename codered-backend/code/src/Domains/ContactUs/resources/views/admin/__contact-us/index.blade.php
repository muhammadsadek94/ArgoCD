@extends('admin.layouts.master')
@section("title", trans("contact_us::lang.Contact us"))
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{url("/admin/dashboard")}}">@lang("lang.Admin")</a></li>
                    <li class="breadcrumb-item"><a href="{{url("/admin/contact-us")}}">@lang("contact_us::lang.Contact us")</a></li>
                    <li class="breadcrumb-item active">@lang("contact_us::lang.index")</li>
                </ol>
            </div>
            <h4 class="page-title">@lang("lang.Index")</h4>
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
                        {!! Form:: open(['method'=>'get', 'files'=>true,'class' => 'form-inline']) !!}
                        <div class="form-group mb-2">
                            {!! Form::text('search', old('search'), ['class' => 'form-control', "placeholder" => "Search"]) !!}
                            <button class="btn btn-info ml-2" type="submit">Search</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="table-responsive overflow">
                    <table class="table table-centered mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>@lang("contact_us::lang.email")</th>
                            <th>@lang("contact_us::lang.subject")</th>
                            <th>@lang("contact_us::lang.status")</th>
                            <th>@lang("contact_us::lang.created_at")</th>
                            <th style="width: 125px;">@lang("user::lang.Action")</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rows as $row)
                        <tr>
                            <td>{{$row->email}}</td>
                            <td>{{$row->subject->subject_en}}</td>
                            <td>
                                @if($row->status == 0)
                                    <h5><span class="badge badge-danger">@lang("contact_us::lang.new")</span></h5>
                                @elseif($row->status == 1)
                                    <h5><span class="badge badge-info">@lang("contact_us::lang.seen")</span></h5>
                                @else
                                    <h5><span class="badge badge-success">@lang("contact_us::lang.replied")</span></h5>
                                @endif
                            </td>
                            <td>
                                {{$row->created_at}}
                            </td>
                            <td>
                                {!! Form::open(['method' => 'DELETE', 'url' => ["/admin/contact-us",$row->id], 'class' => 'form-horizontal']) !!}
                                <a href="{{url('/admin/contact-us/' . $row->id)}}" class="action-icon"> <i class="mdi mdi-eye"></i></a>
                                <button type="submit" class="btn btn-danger btn-flat" onclick="return confirm('Confirm Delete operation ?');"> <i class="fa fa-trash"></i></button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                        @endforeach
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

