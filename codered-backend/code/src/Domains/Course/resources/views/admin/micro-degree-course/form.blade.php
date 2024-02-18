<div class="panel-body row">

    @include('admin.components.inputs.image', [
        'name' => 'image_id',
        'label' => trans('Thumbnail'),
        'cols' => 'col-3',
        'value' => $row->image_id ?? null,
        'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null,
        'endpoint' => url(Constants::ADMIN_BASE_URL . '/course/actions/upload-image'),
        'form_options' => ['required'],
    ])

    @include('admin.components.inputs.image', [
        'name' => 'cover_id',
        'label' => trans('Internal Cover Photo'),
        'cols' => 'col-9',
        'value' => $row->cover_id ?? null,
        'placeholder' => isset($row->cover) ? asset("{$row->cover->full_url}") : null,
        'endpoint' => url(Constants::ADMIN_BASE_URL . '/course/actions/upload-image'),
        'form_options' => ['required'],
    ])

    @include('admin.components.inputs.image', [
        'name' => 'menu_cover_id',
        'label' => trans('Menu Cover'),
        'cols' => 'col-6',
        'value' => $row->menu_cover_id ?? null,
        'placeholder' => isset($row->menu_cover) ? asset("{$row->menu_cover->full_url}") : null,
        'endpoint' => url(Constants::ADMIN_BASE_URL . '/course/actions/upload-image'),
        'form_options' => ['required'],
    ])

    @include('admin.components.inputs.url', [
        'name' => 'intro_video',
        'label' => 'Trailer Video URL (Youtube)',
        'form_options' => ['required'],
        'cols' => 'col-12',
    ])
    <div class="col-12"></div>

    @include('admin.components.inputs.url', [
        'name' => 'slack_url',
        'value' => $row->microdegree->slack_url ?? null,
        'label' => 'Slack URL',
        'form_options' => [''],
        'cols' => 'col-12',
    ])

    <div class="col-12"></div>
    {!! Form::hidden('course_type', \App\Domains\Course\Enum\CourseType::MICRODEGREE) !!}

    @include('admin.components.inputs.text', [
        'name' => 'name',
        'label' => trans('course::lang.course_name'),
        'form_options' => ['required'],
        'cols' => 'col-12 col-md-3',
    ])

    @include('admin.components.inputs.text', [
        'name' => 'internal_name',
        'label' => 'Internal Name',
        'form_options' => ['required'],
        'cols' => 'col-12 col-md-3',
    ])

{{--    @include('admin.components.inputs.text', [--}}
{{--        'name' => 'cyberq_course_id',--}}
{{--        'label' => 'CyberQ Course ID',--}}
{{--        'form_options' => ['required'],--}}
{{--        'cols' => 'col-12 col-md-3',--}}
{{--    ])--}}

    @include('admin.components.inputs.textarea', [
        'name' => 'description',
        'label' => 'Description',
        'form_options' => ['required', 'rows' => 2],
        'cols' => 'col-md-6 col-12',
    ])

    @include('admin.components.inputs.text', [
        'name' => 'slug_url',
        'label' => trans('Slug Url (Prefer \'-\' as separator)'),
        'form_options' => ['required', 'placeholder' => 'php-microdegree-security'],
        'cols' => 'col-12 col-md-3',
    ])


    @include('admin.components.inputs.select', [
        'name' => 'activation',
        'label' => trans('course::lang.status'),
        'form_options' => ['required'],
        'select_options' => $activation_list,
        'cols' => 'col-12 col-md-3',
    ])


    @include('admin.components.inputs.select', [
        'name' => 'user_id[]',
        'label' => 'Trainer',
        'form_options' => ['required', 'multiple', 'id' => 'user_id'],
        'value' => isset($row)
            ? $row->instructors()->get()->pluck('id')
            : null,
        'select_options' => isset($row)
            ? [
                $row->instructors()->get()->pluck('full_name', 'id'),
            ]
            : [],
        'cols' => 'col-12 col-md-3',
    ])

    @include('admin.components.inputs.url', [
        'name' => 'syllabus_url',
        'label' => 'Syllabus Url',
        'cols' => 'col-12 col-md-3',
        'value' => isset($row->microdegree) ? $row->microdegree->syllabus_url : null,
    ])

    @include('admin.components.inputs.text', [
        'name' => 'price',
        'label' => trans('Price'),
        'form_options' => ['max' => 9999, 'min' => 0, 'required'],
        'cols' => 'col-md-3 col-12',
    ])

    <div class="col-12"></div>

    @include('admin.components.inputs.textarea', [
        'name' => 'brief',
        'label' => 'Course Brief',
        'form_options' => ['required', 'rows' => 2],
        'cols' => 'col-md-6 col-12',
    ])
    @include('admin.components.inputs.textarea', [
        'name' => 'prerequisites',
        'label' => 'Prerequisites',
        'value' => isset($row->microdegree) ? $row->microdegree->prerequisites : null,
        'form_options' => ['required', 'rows' => 2],
        'cols' => 'col-md-6 col-12',
    ])
    @include('admin.components.inputs.checkbox', [
        'name' => 'is_featured',
        'label' => 'Is Featured',
        'cols' => 'col-md-6 col-12 mt-3',
        'value' => 1,
        'isChecked' => isset($row) ? $row->is_featured == 1 : 0,
        'form_options' => [],
    ])
    @include('admin.components.inputs.text', [
        'name' => 'average_salary',
        'label' => 'Average Salary',
        'value' => isset($row->microdegree) ? $row->microdegree->average_salary : null,
        'form_options' => ['required'],
        'cols' => 'col-md-6 col-12',
    ])
    <div class="col"></div>
    @include('admin.components.inputs.text', [
        'name' => 'estimated_time',
        'label' => 'Estimated Time',
        'value' => isset($row->microdegree) ? $row->microdegree->estimated_time : null,
        'form_options' => ['required'],
        'cols' => 'col-md-6 col-12',
    ])
    <div class="col-12"></div>



    <hr>

    @if (!isset($row))
        @include("{$view_path}.partials.create-faq")
    @endif

    <div id="faq-multiple" class="col-12">
        @include("{$view_path}.partials.update-faq")
    </div>



    @if (!isset($row) || !$row->microdegree->skills_covered)
        @include("{$view_path}.partials.create-skills_covered")
    @else
        <div id="skills_covered-multiple" class="col-12">
            @include("{$view_path}.partials.update-skills_covered")
        </div>
    @endif




    @if (!isset($row) || !$row->microdegree->key_features)
        @include("{$view_path}.partials.create-key_features")
    @else
        <div id="key_features-multiple" class="col-12">
            @include("{$view_path}.partials.update-key_features")
        </div>
    @endif


    @if (!isset($row) || !$row->microdegree->project)
        @include("{$view_path}.partials.create-project")
    @else
        <div id="project-multiple" class="col-12">
            @include("{$view_path}.partials.update-project")
        </div>
    @endif





    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])

</div>

@isset($row)
    @push('form_section')
        @include("{$view_path}.what-to-learn.index", [
            'course' => $row,
            'view_path' => $view_path,
        ])
    @endpush
@endisset








@push('script')
    <script>
        $(document).ready(function() {

            $('#user_id').select2({
                ajax: {
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/micro-degree-course/actions/get-trainers`,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            });


        });
    </script>
@endpush
