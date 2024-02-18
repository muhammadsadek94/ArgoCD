@extends('admin.layouts.master')
@section("title", $row->title)
@section('content')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url("/admin/dashboard")}}">@lang("lang.Admin")</a></li>
                        <li class="breadcrumb-item"><a href="{{url("/admin/contact-us")}}">@lang("contact_us::lang.Contact us")</a></li>
                        <li class="breadcrumb-item active">{{$row->title}}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{$row->title}}</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box text-center">
                @if(session()->has("success"))
                    <div class="alert alert-success w-100 mt-2">{{session()->get("success")}}</div>
                @endif
                <!-- Story Box-->
                <div class=" border-light p-2 mb-3">
                    <div class="table">
                        <table class="table table-centered mb-0">
                            <tbody>
                                <tr>
                                    <th>@lang("contact_us::lang.status")</th>
                                    @if($row->status == 0)
                                        <td><span class="badge badge-danger">@lang("contact_us::lang.new")</span></td>
                                    @elseif($row->status == 1)
                                        <td><span class="badge badge-info">@lang("contact_us::lang.seen")</span></td>
                                    @else
                                        <td><span class="badge badge-success">@lang("contact_us::lang.replied")</span></td>
                                    @endif
                                </tr>
                                <tr>
                                    <th>@lang("contact_us::lang.subject")</th>
                                    <th>{{$row->subject->subject_en}}</th>
                                </tr>
                                <tr>
                                    <th>@lang("contact_us::lang.message")</th>
                                    <td class="text-center">{{$row->body}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h2 class="text-center">@lang("contact_us::lang.mail_reply")</h2>
                {{Form::open(["method" => "post", "class" => "form-group text-left"])}}
                    <div class="my-2">
                        <label for="title"> @lang("contact_us::lang.email")</label>
                        {{Form::text("email", $row->email, ["class" => "form-control", "id" => "title", "readonly"] )}}
                    </div>
                    <div class="my-2">
                        <label for="title"> @lang("contact_us::lang.subject")</label>
                        {{Form::text("reply_subject", null, ["class" => "form-control", "id" => "title"] )}}
                        <span class="m-form__help form-validation-help w-100">
                            {{ $errors->first('reply_subject') }}
                        </span>
                    </div>
                    <div class="my-2">
                        <label for="body"> @lang("contact_us::lang.message")</label>
                        {{Form::textarea("reply_message", null, ["class" => "form-control", "id" => "title"] )}}
                        <span class="m-form__help form-validation-help w-100">
                            {{ $errors->first('reply_message') }}
                        </span>
                    </div>
                    <div class="my-2">
                        <button class="btn btn-info py-1 px-4">Send</button>
                    </div>
                {{Form::close()}}
            </div>
        </div>
    </div> <!-- end col-->

@endsection

