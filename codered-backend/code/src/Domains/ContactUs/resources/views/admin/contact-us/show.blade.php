@extends('admin.layouts.main')

@section('content')
@if(session()->has("success"))
    <div class="alert alert-success w-100 mt-2">{{session()->get("success")}}</div>
@endif
<!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">  </h3>
                    </div>
                    <div class="card-box text-center">
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
{{--                                        <tr>--}}
{{--                                            <th>@lang("contact_us::lang.subject")</th>--}}
{{--                                            <th>{{$row->subject->subject_en}}</th>--}}
{{--                                        </tr>--}}
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
                                    <span class="m-form__help form-validation-help w-100 text-danger">
                                        {{ $errors->first('reply_subject') }}
                                    </span>
                                </div>
                                <div class="my-2">
                                    <label for="body"> @lang("contact_us::lang.message")</label>
                                    {{Form::textarea("reply_message", null, ["class" => "form-control", "id" => "title"] )}}
                                    <span class="m-form__help form-validation-help w-100 text-danger">
                                        {{ $errors->first('reply_message') }}
                                    </span>
                                </div>
                                <div class="my-2">
                                    <button class="btn btn-primary py-1 px-4">@lang('Send')</button>
                                </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
<!-- END PAGE CONTAINER -->

@stop






