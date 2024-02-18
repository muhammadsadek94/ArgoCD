@extends('admin.layouts.main')

{{-- @section('title', $module_name . ' - ' . end($breadcrumb)->title) --}}


@section('content')
    {{-- @include('admin.layouts.breadcrumb', [
           'page_title' => end($breadcrumb)->title,
           'crumbs' => $breadcrumb
       ]) --}}
    <div class="row  mt-5">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-12 col-md-6">
                            <h1>
                                lesson: <a target="_blank" href="{{ url("/admin/lesson/{$row->lesson->id}/edit?course_id={$row->lesson->course_id}&chapter_id={$row->lesson->chapter_id}") }}">{{ $row->lesson->name }}</a>
                            </h1>


                            <h4 class="font-weight-bold d-inline-block">
                                <a href="{{ $row->url }}" target="_blank" class="">
                                    <i class="mdi mdi-github-circle mdi-18px"></i>
                                    <span>Link to Repository</span>
                                </a>
                            </h4>

                        </div>

                        <div class="col-12 col-md-6">


                            <div class="d-flex align-items-center" >
                                @if($row->image)
                                    <img src="{{ url($row->image->full_url) }}" class="rounded-circle avatar-lg img-thumbnail"
                                         alt="profile-image">
                                @else
                                    <img src="{{ url('assets/imgs/default-profile.png') }}"
                                         class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">
                                @endif
                                <div class="ml-3">
                                    <h6>
                                        <span class="font-weight-bold">Name</span>
                                        <span class="">{{ $row->user->first_name }}</span>
                                    </h6>
                                    <h6>
                                        <span class="font-weight-bold">Email</span>
                                        <a href="{{ 'mailto:'. $row->user->email }}" class="">{{ $row->user->email }}</a>
                                    </h6>
                                    <a class="btn btn-info" href="{{ url("/admin/user/{$row->user->id}") }}" target="_blank">
                                        View Profile
                                    </a>
                                </div>
                            </div>


                        </div>
                            <div class="col-12 mt-4">
                                {!! Form::close() !!}
                                {!! Form::model($row,['method'=>'POST','url' => [$route,'actions', $row->id, 'change-status'], 'files'=>true,'data-toggle'=> 'ajax' ,'data-refresh-page' => "true"]) !!}

                                @include('admin.components.inputs.select', [
                                    'name' => 'status',
                                    'label' => 'Change Status',
                                    'form_options'=> [
                                        'required',
                                        'placeholder' => 'Status'
                                    ],
                                    'select_options' =>  [
                                      \App\Domains\Course\Enum\ProjectApplicationStatus::UNDER_REVIEW => 'Under Review',
                                        \App\Domains\Course\Enum\ProjectApplicationStatus::REVIEW_COMPLETED => 'Review Completed',
                                    ],
                                    'cols' => 'col-12 col-md-5'
                            
                                ])
                        
                                @include('admin.components.inputs.success-btn', ['button_text' => "Change Status", 'button_extra_class' => ''])
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div> <!-- end row-->


                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    <h3> comments</h3>
    <div class="row justify-content-center">
    @foreach($row->comments as $comment)
    <div class="card col-12 col-md-8 p-4">
        <div class="row">
            <div class="col-lg-10">
                <div class="d-flex">
                    <div class="mr-4">
                        <img src="{{ url($comment->owner->image ? $comment->owner->image->full_url : 'assets/imgs/logo.png') }}" class="rounded-circle" width="60px" height="60px" alt="">
                    </div>
                    <div>
                        {{$comment->comment}}
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <sub>{{ \Carbon\Carbon::parse($comment->created_at)->format('Y-m-d H:i A') }}</sub>
            </div>
        </div>
    </div>
    @endforeach
    </div>


    {!! Form::model($row,['method'=>'POST','url' => [$route,'action','comment', $row->id], 'files'=>true,'data-toggle'=> 'ajax' ,'data-refresh-page' => "true"]) !!}
{{--    {!! Form::hidden('id', $row->id) !!}--}}
    @include('admin.components.inputs.textarea', [
        'name' => 'comment',
        'label' => trans('course::lang.comment'),
        'form_options' => ['required'],
        'cols' => 'col-12 ',
    ])


    @include('admin.components.inputs.success-btn', ['button_text' => "Send", 'button_extra_class' => 'float-right'])
    </div>
    {{-- @include("{$view_path}.subscriptions") --}}
@endsection


    {{-- @include("{$view_path}.subscriptions") --}}
