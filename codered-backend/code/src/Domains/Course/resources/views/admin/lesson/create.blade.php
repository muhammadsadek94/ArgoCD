@extends('admin.components.layouts.crud.layouts.form')

@section('title', $module_name . ' - ' . end($breadcrumb)->title)

@section('breadcrumb')
    @include('admin.layouts.breadcrumb', [
        'page_title' => end($breadcrumb)->title,
        'crumbs' => $breadcrumb,
    ])
@endsection


@section('form')
    {!! Form::open(['method' => 'POST', 'url' => "$route", 'files' => true, 'data-toggle' => 'ajax']) !!}
    <div class="panel-body row">


        @include('admin.components.inputs.text', [
            'name' => 'name',
            'label' => trans('course::lang.name'),
            'form_options' => ['required'],
        ])

        @include('admin.components.inputs.number', [
            'name' => 'sort',
            'label' => trans('Sort'),
            'form_options' => ['required', 'min' => 1, 'id' => 'sort'],
            'cols' => 'col-md-6 col-12 sort-div',
        ])


        @include('admin.components.inputs.select', [
            'name' => 'activation',
            'label' => trans('course::lang.Activation'),
            'form_options' => ['required'],
            'select_options' => [
                \App\Domains\Course\Enum\LessonActivationStatus::PENDING => 'Pending',
                \App\Domains\Course\Enum\LessonActivationStatus::DEACTIVATED => 'Deactivated',
                \App\Domains\Course\Enum\LessonActivationStatus::ACTIVE => 'Active',
            ],
        ])

        @if ($course_type == \App\Domains\Course\Enum\CourseType::COURSE_CERTIFICATION)
            @include('admin.components.inputs.select', [
                'name' => 'type',
                'label' => trans('Type'),
                'form_options' => ['required', 'id' => 'type'],
                'select_options' => [
                    \App\Domains\Course\Enum\LessonType::VIDEO => 'Video',
                    \App\Domains\Course\Enum\LessonType::QUIZ => 'Quiz',
                    \App\Domains\Course\Enum\LessonType::LAB => 'LOD Labs',
                    \App\Domains\Course\Enum\LessonType::CYPER_Q => 'CyberQ Labs',
                    \App\Domains\Course\Enum\LessonType::DOCUMENT => 'Rich Text',
                    \App\Domains\Course\Enum\LessonType::VOUCHER => 'Exam Voucher',
                    \App\Domains\Course\Enum\LessonType::PROJECT => 'Project',
                    \App\Domains\Course\Enum\LessonType::VITAL_SOURCE => 'Vital Source eBook',
                    \App\Domains\Course\Enum\LessonType::CHECKPOINT => 'Checkpoint',
                ],
            ])
        @else
            @include('admin.components.inputs.select', [
                'name' => 'type',
                'label' => trans('Type'),
                'form_options' => ['required', 'id' => 'type'],
                'select_options' => [
                    \App\Domains\Course\Enum\LessonType::VIDEO => 'Video',
                    \App\Domains\Course\Enum\LessonType::QUIZ => 'Quiz',
                    \App\Domains\Course\Enum\LessonType::LAB => 'LOD Labs',
                    \App\Domains\Course\Enum\LessonType::CYPER_Q => 'CyberQ Labs',
                    \App\Domains\Course\Enum\LessonType::DOCUMENT => 'Rich Text',
                    \App\Domains\Course\Enum\LessonType::VOUCHER => 'Exam Voucher',
                    \App\Domains\Course\Enum\LessonType::PROJECT => 'Project',
                    \App\Domains\Course\Enum\LessonType::VITAL_SOURCE => 'Vital Source eBook',
                ],
            ])
        @endif



        @include('admin.components.inputs.text', [
            'name' => 'ilab_id',
            'label' => trans('LOD Labs ID'),
            'form_options' => [
                'id' => 'ilab_id',
            ],
            'cols' => 'col-lg-6 col-12 d-none ilabIdDiv',
        ])

        @include('admin.components.inputs.text', [
            'name' => 'cyperq_id',
            'label' => trans('CyberQ ID'),
            'form_options' => [
                'id' => 'cyperq_id',
            ],
            'cols' => 'col-lg-6 col-12 d-none cyperqDiv',
        ])

        @include('admin.components.inputs.select', [
            'name' => 'after_chapter',
            'label' => 'After which chapter',
            'form_options' => [
                'id' => 'after_chapter',
                'placeholder' => 'Select Chapter',
            ],
            'select_options' => $chapters,
            'cols' => 'col-lg-6 col-12 d-none chapterDiv',
        ])

        @include('admin.components.inputs.text', [
            'name' => 'book_id',
            'label' => 'Book ID',
            'form_options' => [
                'id' => 'book_id',
            ],
            'cols' => 'col-lg-6 col-12 d-none bookDiv',
        ])

        @include('admin.components.inputs.text', [
            'name' => 'page_number',
            'label' => 'Chapter Page Number',
            'form_options' => [
                'id' => 'page_number',
            ],
            'cols' => 'col-lg-6 col-12 d-none bookDiv',
        ])

        @include('admin.components.inputs.number', [
            'name' => 'time',
            'label' => trans('Lesson Duration'),
            'form_options' => [],
            'cols' => 'col-md-6 col-12 lesson_time',
            'help' => 'to quickly override video duration (applies on the video type only) ',
        ])

        @include('admin.components.inputs.textarea', [
            'name' => 'overview',
            'label' => trans('Description'),
            'form_options' => [],
            'cols' => 'col-6 d-none description',
        ])

        @include('admin.components.inputs.textarea', [
            'name' => 'outer_overview',
            'label' => trans('Description'),
            'form_options' => [],
            'cols' => 'col-6 d-none description_for_checkpoint',
        ])

        @include('admin.components.inputs.textarea', [
            'name' => 'video_info',
            'label' => trans('Video Json Info'),
            'form_options' => [],
            'help' => 'to quickly get videos already uploaded form vimeo/brightcove ',
            'cols' => 'video_id col-md-6 col-12',
        ])


        @include('admin.components.inputs.checkbox', [
            'name' => 'is_free',
            'label' => 'Is Free',
            'value' => 1,
            'cols' => 'col-md-6 col-12 mt-3',
            'form_options' => [],
        ])


        {!! Form::hidden('course_id', request('course_id')) !!}
        {!! Form::hidden('chapter_id', request('chapter_id')) !!}


        @include('admin.components.inputs.success-btn', [
            'button_text' => 'Create',
            'button_extra_class' => 'float-right',
        ])

    </div>

    {!! Form::close() !!}
@endsection

@push('script-bottom')
    <script>
        $(document).ready(function() {
            $('select[name="type"]').on('change', function() {
                const LAB = "{{ \App\Domains\Course\Enum\LessonType::LAB }}"
                const CYPERQ = "{{ \App\Domains\Course\Enum\LessonType::CYPER_Q }}"
                const VITAL_SOURCE = "{{ \App\Domains\Course\Enum\LessonType::VITAL_SOURCE }}"
                const DOCUMENT = "{{ \App\Domains\Course\Enum\LessonType::DOCUMENT }}"
                const CHECKPOINT = "{{ \App\Domains\Course\Enum\LessonType::CHECKPOINT }}"
                const PROJECT = "{{ \App\Domains\Course\Enum\LessonType::PROJECT }}"
                const VOUCHER = "{{ \App\Domains\Course\Enum\LessonType::VOUCHER }}"
                if ($(this).val() == LAB) {
                    $('.ilabIdDiv').removeClass('d-none');
                    $('#ilab_id').prop('required', true);
                    $('.video_id').removeClass('d-none');
                    $('.lesson_time').removeClass('d-none');
                    $('.cyperqDiv').addClass('d-none');
                    $('#cyperq_id').prop('required', false);
                    $('.bookDiv').addClass('d-none');
                    $('#book_id').prop('required', false);
                    $('.chapterDiv').addClass('d-none');
                    $('#after_chapter').prop('required', false);
                } else if ($(this).val() == CYPERQ) {
                    $('.cyperqDiv').removeClass('d-none');
                    $('#cyperq_id').prop('required', true);
                    $('.video_id').removeClass('d-none');
                    $('.lesson_time').removeClass('d-none');
                    $('.ilabIdDiv').addClass('d-none');
                    $('#ilab_id').prop('required', false);
                    $('.bookDiv').addClass('d-none');
                    $('.chapterDiv').addClass('d-none');
                    $('#after_chapter').prop('required', false);
                    $('#book_id').prop('required', false);
                } else if ($(this).val() == VITAL_SOURCE) {
                    $('.bookDiv').removeClass('d-none');
                    $('.description').removeClass('d-none');
                    $('#book_id').prop('required', true);
                    $('.video_id').addClass('d-none');
                    $('.lesson_time').addClass('d-none');
                    $('.cyperqDiv').addClass('d-none');
                    $('#cyperq_id').prop('required', false);
                    $('.ilabIdDiv').addClass('d-none');
                    $('.chapterDiv').addClass('d-none');
                    $('#after_chapter').prop('required', false);
                    $('#ilab_id').prop('required', false);
                } else if ($(this).val() == DOCUMENT) {
                    $('.cyperqDiv').addClass('d-none');
                    $('.description').removeClass('d-none');
                    $('#cyperq_id').prop('required', false);
                    $('#ilab_id').prop('required', false);
                    $('.ilabIdDiv').addClass('d-none');
                    $('.bookDiv').addClass('d-none');
                    $('.video_id').addClass('d-none');
                    $('.lesson_time').addClass('d-none');
                    $('#book_id').prop('required', false);
                    $('.chapterDiv').addClass('d-none');
                    $('#after_chapter').prop('required', false);
                } else if ($(this).val() == CHECKPOINT) {
                    $('.cyperqDiv').removeClass('d-none');
                    $('.description_for_checkpoint').removeClass('d-none');
                    $('#cyperq_id').prop('required', true);
                    $('#ilab_id').prop('required', false);
                    $('.ilabIdDiv').addClass('d-none');
                    $('#sort').prop('required', false);
                    $('.sort-div').addClass('d-none');
                    $('.bookDiv').addClass('d-none');
                    $('.video_id').addClass('d-none');
                    $('.lesson_time').addClass('d-none');
                    $('#book_id').prop('required', false);
                    $('.chapterDiv').removeClass('d-none');
                } else if ($(this).val() == PROJECT) {
                    $('.cyperqDiv').addClass('d-none');
                    $('.description').removeClass('d-none');
                    $('.video_id').removeClass('d-none');
                    $('.lesson_time').removeClass('d-none');
                    $('#cyperq_id').prop('required', false);
                    $('#ilab_id').prop('requird', false);
                    $('.ilabIdDiv').addClass('d-none');
                    $('.bookDiv').addClass('d-none');
                    $('#book_id').prop('required', false);
                    $('.chapterDiv').addClass('d-none');
                    $('#after_chapter').prop('required', false);
                } else if ($(this).val() == VOUCHER) {
                    $('.cyperqDiv').addClass('d-none');
                    $('.description').removeClass('d-none');
                    $('.video_id').removeClass('d-none');
                    $('.lesson_time').removeClass('d-none');
                    $('#cyperq_id').prop('required', false);
                    $('#ilab_id').prop('requird', false);
                    $('.ilabIdDiv').addClass('d-none');
                    $('.bookDiv').addClass('d-none');
                    $('#book_id').prop('required', false);
                    $('.chapterDiv').addClass('d-none');
                    $('#after_chapter').prop('required', false);
                } else {
                    $('.cyperqDiv').addClass('d-none');
                    $('.video_id').removeClass('d-none');
                    $('.lesson_time').removeClass('d-none');
                    $('#cyperq_id').prop('required', false);
                    $('#ilab_id').prop('requird', false);
                    $('.ilabIdDiv').addClass('d-none');
                    $('.bookDiv').addClass('d-none');
                    $('#book_id').prop('required', false);
                    $('.chapterDiv').addClass('d-none');
                    $('#after_chapter').prop('required', false);
                }
            });
        })
    </script>
@endpush
