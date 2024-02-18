@extends('admin.components.layouts.crud.layouts.form')

@section('title', $module_name . ' - ' . end($breadcrumb)->title)

@section('breadcrumb')
    @include('admin.layouts.breadcrumb', [
        'page_title' => end($breadcrumb)->title,
        'crumbs' => $breadcrumb,
    ])
@endsection

@push('head')
    <style>
        .form-control:disabled {
            background-color: #eee !important;
        }
    </style>
@endpush
@section('form')
    <link href="https://cdn.jsdelivr.net/npm/froala-editor@3.1.0/css/froala_editor.pkgd.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.5/css/froala_style.min.css" rel="stylesheet"
        type="text/css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.1.0/js/froala_editor.pkgd.min.js">
    </script>

    {!! Form::model($row, [
        'method' => 'PATCH',
        'url' => [$route, $row->id],
        'files' => true,
        'data-toggle' => 'ajax',
    ]) !!}
    <div class="panel-body row">
        {!! Form::hidden('course_id', $row->course_id) !!}
        {!! Form::hidden('chapter_id', $row->chapter_id) !!}

        @include('admin.components.inputs.text', [
            'name' => 'name',
            'label' => trans('course::lang.name'),
            'form_options' => ['required'],
        ])


        @if ($row->type != \App\Domains\Course\Enum\LessonType::CHECKPOINT)
            @include('admin.components.inputs.number', [
                'name' => 'sort',
                'label' => trans('Sort'),
                'form_options' => ['required', 'min' => 1],
            ])
        @endif

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
                'form_options' => ['required', 'id' => 'type', 'disabled'],
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
                'form_options' => ['required', 'id' => 'type', 'disabled'],
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

        @if ($row->type == \App\Domains\Course\Enum\LessonType::VIDEO)
            @include('admin.components.inputs.number', [
                'name' => 'time',
                'label' => trans('Time'),
                'form_options' => [],
                'cols' => 'col-md-6 col-12',
                'value' => isset($row->timing) ? $row->timing : null,
            ])
        @endif

        @if ($row->type == \App\Domains\Course\Enum\LessonType::LAB)
            @include('admin.components.inputs.text', [
                'name' => 'ilab_id',
                'label' => trans('LOD Labs ID'),
                'form_options' => ['required', 'id' => 'ilab_id'],
                'cols' => 'col-lg-6 col-12',
            ])
        @endif

        @if ($row->type == \App\Domains\Course\Enum\LessonType::VIDEO)
            @include('admin.components.inputs.textarea', [
                'name' => 'video_info',
                'label' => trans('Video Json Info'),
                'value' => json_encode($row->video),
                'form_options' => [],
            ])
        @endif

        @if (
            $row->type == \App\Domains\Course\Enum\LessonType::CYPER_Q ||
                $row->type == \App\Domains\Course\Enum\LessonType::CHECKPOINT)
            @include('admin.components.inputs.text', [
                'name' => 'cyperq_id',
                'label' => trans('CyberQ ID'),
                'form_options' => ['required', 'id' => 'cyperq_id'],
                'cols' => 'col-lg-6 col-12',
            ])
        @endif
        @if ($row->type == \App\Domains\Course\Enum\LessonType::VITAL_SOURCE)
            @include('admin.components.inputs.text', [
                'name' => 'book_id',
                'label' => 'Book ID',
                'form_options' => ['required', 'id' => 'book_id'],
                'cols' => 'col-lg-6 col-12',
            ])
            @include('admin.components.inputs.text', [
                'name' => 'page_number',
                'label' => 'Page Number',
                'form_options' => [
                    'id' => 'page_number',
                ],
                'cols' => 'col-lg-6 col-12',
            ])
        @endif

        @if ($row->type == \App\Domains\Course\Enum\LessonType::CHECKPOINT)
            @include('admin.components.inputs.select', [
                'name' => 'after_chapter',
                'label' => 'After which chapter',
                'form_options' => ['id' => 'after_chapter', 'placeholder' => 'Select Chapter'],
                'select_options' => $chapters,
            ])

            @include('admin.components.inputs.textarea', [
                'name' => 'outer_overview',
                'label' => trans('Description'),
                'form_options' => ['required'],
                'cols' => 'col-6',
            ])
        @endif

        @include('admin.components.inputs.checkbox', [
            'name' => 'is_free',
            'label' => 'Is Free',
            'value' => 1,
            'isChecked' => isset($row) ? $row->is_free == 1 : 0,
            'form_options' => [],
        ])

        @if ($row->type == \App\Domains\Course\Enum\LessonType::VITAL_SOURCE)
            @include('admin.components.inputs.file', [
                'name' => 'manual',
                'label' => trans('Book Manual'),
                'form_options' => ['accept' => '.xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf'],
                'cols' => 'col-lg-6',
            ])
        @endif

        @if (
            $row->type == \App\Domains\Course\Enum\LessonType::DOCUMENT ||
                $row->type == \App\Domains\Course\Enum\LessonType::PROJECT ||
                $row->type == \App\Domains\Course\Enum\LessonType::VOUCHER ||
                $row->type == \App\Domains\Course\Enum\LessonType::VITAL_SOURCE)
            @include('admin.components.inputs.textarea', [
                'name' => 'overview',
                'label' => trans('Description'),
                'form_options' => [
                    'class' => 'fr-view',
                ],
                'cols' => 'col-12',
            ])
        @endif

        @if ($row->type == \App\Domains\Course\Enum\LessonType::CHECKPOINT)
            @include('admin.components.inputs.textarea', [
                'name' => 'overview',
                'label' => trans('Checkpoint Scenario'),
                'form_options' => [
                    'class' => 'fr-view',
                ],
                'cols' => 'col-12',
            ])
        @endif

        @if (in_array($row->type, [\App\Domains\Course\Enum\LessonType::LAB, \App\Domains\Course\Enum\LessonType::CYPER_Q]))
            @include('admin.components.inputs.image', [
                'name' => 'image_id',
                'label' => trans('Image'),
                'value' => $row->image_id ?? null,
                'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null,
            ])


            @include('admin.components.inputs.file', [
                'name' => 'manual',
                'label' => trans('Lab Manual'),
                'form_options' => ['accept' => '.xlsx,.xls,.doc, .docx,.ppt, .pptx,.txt,.pdf'],
                'cols' => 'col-lg-6',
            ])
        @endif

        @include('admin.components.inputs.success-btn', [
            'button_text' => 'Save',
            'button_extra_class' => 'float-right',
        ])

    </div>

    {!! Form::close() !!}
@endsection

@push('form_section')
    @includeWhen(in_array($row->type, [
            \App\Domains\Course\Enum\LessonType::VIDEO,
            \App\Domains\Course\Enum\LessonType::LAB,
            \App\Domains\Course\Enum\LessonType::CYPER_Q,
        ]),
        'course::admin.lesson.video.create')
@endpush

@push('form_section')
    @includeWhen(in_array($row->type, [\App\Domains\Course\Enum\LessonType::VOUCHER]),
        'course::admin.lesson.voucher.index')
@endpush

{{-- @push('form_section') --}}
{{--    @includeWhen($row->type == \App\Domains\Course\Enum\LessonType::VIDEO,'course::admin.lesson.faq.index', [ --}}
{{--        'lesson' => $row --}}
{{--    ]) --}}
{{-- @endpush --}}


@push('form_section')
    @includeWhen(in_array($row->type, [
            \App\Domains\Course\Enum\LessonType::VIDEO,
            \App\Domains\Course\Enum\LessonType::LAB,
            \App\Domains\Course\Enum\LessonType::CYPER_Q,
            \App\Domains\Course\Enum\LessonType::DOCUMENT,
        ]),
        'course::admin.lesson.resource.index',
        [
            'lesson' => $row,
        ]
    )
@endpush


@push('form_section')
    @includeWhen($row->type == \App\Domains\Course\Enum\LessonType::QUIZ, 'course::admin.lesson.quiz.index', [
        'lesson' => $row,
    ])
@endpush


@push('form_section')
    @includeWhen(in_array($row->type, [
            \App\Domains\Course\Enum\LessonType::LAB,
            \App\Domains\Course\Enum\LessonType::CYPER_Q,
        ]),
        'course::admin.lesson.lesson-objective.index',
        [
            'lesson' => $row,
        ]
    )
@endpush

@push('form_section')
    @includeWhen(in_array($row->type, [
            \App\Domains\Course\Enum\LessonType::LAB,
            \App\Domains\Course\Enum\LessonType::CYPER_Q,
            \App\Domains\Course\Enum\LessonType::VITAL_SOURCE,
        ]),
        'course::admin.lesson.lesson-objective.index',
        [
            'lesson' => $row,
        ]
    )
@endpush

@push('form_section')
    @includeWhen(in_array($row->type, [
            \App\Domains\Course\Enum\LessonType::LAB,
            \App\Domains\Course\Enum\LessonType::CYPER_Q,
        ]),
        'course::admin.lesson.lesson-task.index',
        [
            'lesson' => $row,
        ]
    )
@endpush


@push('script')
    <script>
        $(document).ready(function() {
            var editor = new FroalaEditor('.fr-view', {
                key: 'CTD5xB1C2G1G1A16B3wc2DBKSPJ1WKTUCQOd1OURPE1KDc1C-7J2A4D4A3C6E2G2F4E1F1=='
            });
        });
    </script>
@endpush
