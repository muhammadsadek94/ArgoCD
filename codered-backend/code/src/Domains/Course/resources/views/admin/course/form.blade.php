<!--For Froala Editor-->
<link href="https://cdn.jsdelivr.net/npm/froala-editor@3.1.0/css/froala_editor.pkgd.min.css" rel="stylesheet"
    type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.5/css/froala_style.min.css" rel="stylesheet"
    type="text/css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.1.0/js/froala_editor.pkgd.min.js">
</script>
<!--For Froala Editor-->

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



    @include('admin.components.inputs.url', [
        'name' => 'intro_video',
        'label' => 'Trailer Video URL (Youtube)',
        'form_options' => ['required'],
        'cols' => 'col-12',
    ])

    <div class="col-12"></div>

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
        'name' => 'level',
        'label' => 'Level',
        'form_options' => ['required'],
        'select_options' => $level_list,
        'cols' => 'col-12 col-md-3',
    ])



    {!! Form::hidden('course_type', \App\Domains\Course\Enum\CourseType::COURSE) !!}



    @include('admin.components.inputs.select', [
        'name' => 'course_category_id',
        'label' => trans('course::lang.Course Category'),
        'form_options' => ['required', 'placeholder' => 'Course Category'],
        'select_options' => $categories_list,
        'cols' => 'col-12 col-md-3',
    ])
    <div class="col-12 col-md-3 px-0">

        @include('admin.components.inputs.select', [
            'name' => 'course_sub_category_id',
            'label' => 'Course SubCategory',
            'form_options' => ['placeholder' => 'Course Sub Category', 'required'],
            'select_options' => $sub_categories_list,
            'cols' => 'col-12',
        ])

    </div>

    @include('admin.components.inputs.text', [
        'name' => 'price',
        'label' => trans('Price'),
        'form_options' => ['max' => 9999, 'min' => 0],
        'cols' => 'col-md-3 col-12',
    ])


    @include('admin.components.inputs.text', [
        'name' => 'discount_price',
        'label' => trans('Discount price'),
        'form_options' => ['max' => 9999, 'min' => 0],
        'cols' => 'col-md-3 col-12',
    ])

    @include('admin.components.inputs.text', [
        'name' => 'advances',
        'label' => trans('Advances'),
        'cols' => 'col-md-3 col-12',
    ])

    @include('admin.components.inputs.select', [
        'name' => 'course_tags_id[]',
        'label' => trans('Course Tags'),
        'form_options' => ['required', 'multiple'],
        'select_options' => isset($row) ? $row->tags->pluck('name', 'id')->toArray() : [],
        'value' => isset($row) ? $row->tags->pluck('id')->toArray() : null,
        'cols' => 'col-12 col-md-6',
    ])

    @include('admin.components.inputs.select', [
        'name' => 'job_role_id[]',
        'label' => trans('Job Role'),
        'form_options' => ['required', 'multiple'],
        'select_options' => isset($row) ? $row->jobRoles->pluck('name', 'id')->toArray() : [],
        'value' => isset($row) ? $row->jobRoles->pluck('id')->toArray() : null,
        'cols' => 'col-12 col-md-6',
    ])

    @include('admin.components.inputs.select', [
        'name' => 'specialty_area_id[]',
        'label' => trans('Specialty area'),
        'form_options' => ['required', 'multiple'],
        'select_options' => isset($row) ? $row->specialtyAreas->pluck('name', 'id')->toArray() : [],
        'value' => isset($row) ? $row->specialtyAreas->pluck('id')->toArray() : null,
        'cols' => 'col-12 col-md-6',
    ])

    @include('admin.components.inputs.select', [
        'name' => 'user_id',
        'label' => 'Trainer',
        'form_options' => ['required'],
        'select_options' => isset($row)
            ? [
                $row->user()->get()->pluck('full_name', 'id'),
            ]
            : [],
        'cols' => 'col-12 col-md-6',
    ])


    @include('admin.components.inputs.number', [
        'name' => 'commission_percentage',
        'label' => trans('Commission Percentage'),
        'form_options' => ['max' => 100, 'min' => 0],
        'cols' => 'col-md-3 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->commission_percentage : null,
        'help' => 'This is percentage of instructor commission [it will be used in payouts calculations]',
    ])


    @include('admin.components.inputs.text', [
        'name' => 'sku',
        'label' => trans('SKU'),
        'cols' => 'col-md-3 col-12',
        'form_options' => ['required'],
    ])


    @include('admin.components.inputs.textarea', [
        'name' => 'brief',
        'label' => 'Course Brief',
        'form_options' => ['required'],
        'cols' => 'col-12',
    ])



    @include('admin.components.inputs.textarea', [
        'name' => 'description',
        'label' => 'Course Overview',
        'form_options' => ['required', 'class' => 'fr-view', 'id' => 'description'],
        'cols' => 'col-12',
    ])


    {{--    @include('admin.components.inputs.select', [ --}}
    {{--        'name' => 'tools[]', --}}
    {{--        'label' => 'Tools', --}}
    {{--        'form_options' => ['required', 'multiple'], --}}
    {{--        'select_options' => $tools, --}}
    {{--        'cols' => 'col-12', --}}
    {{--    ]) --}}
    {{-- @include('admin.components.inputs.select', [
	   'name' => 'learn[]',
	   'label' => 'What will you learn?',
	   'form_options'=> ['required', 'multiple'],
	   'select_options' =>  isset($row->learn) && is_array($row->learn) ? array_combine(array_values($row->learn ?? []), array_values($row->learn ?? [])) : [],
	   'value' =>  isset($row) ? array_values($row->learn ?? []) : null,
	   'cols' => 'col-12 col-md-6'
   ]) --}}

    @if (!isset($row))
        @include("{$view_path}.partials.create-learn")
    @endif

    <div id="description-feature-multiple" class="col-12">
        @include("{$view_path}.partials.update-learn")
    </div>

    <div id="prerequisites-multiple" class="col-12">
        @include("{$view_path}.partials.prerequisites")
    </div>

    <div id="subtitles-multiple" class="col-12">
        @include("{$view_path}.partials.subtitles")
    </div>





    <div class="col-12">
        @if (!isset($row->metadata))
            @include("{$view_path}.partials.create-metadata")
        @else
            @include("{$view_path}.partials.update-metadata")
        @endif
    </div>
    @include('admin.components.inputs.checkbox', [
        'name' => 'is_featured',
        'label' => 'Is Featured',
        'value' => 1,
        'form_options' => [
            'checked' => isset($row) ? $row->is_featured == 1 : false
],
    ])


    @include('admin.components.inputs.checkbox', [
        'name' => 'is_free',
        'label' => 'Is Free',
        'value' => 1,
        'form_options' => [
            'id' => 'is_free',
            'checked' => isset($row) ? $row->is_free == 1 : false
            ],
    ])

    @include('admin.components.inputs.checkbox', [
        'name' => 'is_essential',
        'label' => 'Is Essential',
        'value' => 1,
        'form_options' => [
            'checked' => isset($row) ? $row->is_essential == 1 : false
],
    ])

    @include('admin.components.inputs.checkbox', [
        'name' => 'is_best_seller',
        'label' => 'Is Best Seller',
        'value' => 1,
        'form_options' => [
            'checked' => isset($row) ? $row->is_best_seller == 1 : false
        ],
    ])

    @include('admin.components.inputs.checkbox', [
        'name' => 'is_editorial_pick',
        'label' => 'Is Editorial Pick',
        'value' => 1,
        'form_options' => [
            'checked' => isset($row) ? $row->is_editorial_pick == 1 : false
        ],
    ])


    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])

</div>



@isset($row)
    @push('form_section')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::model($row, [
                            'method' => 'PATCH',
                            'url' => ["{$route}/action", $row->id, 'timing'],
                            'files' => true,
                            'data-toggle' => 'ajax',
                        ]) !!}
                        <div class="panel-body row">
                            @include('admin.components.inputs.text', [
                                'name' => 'timing',
                                'label' => trans('Course Duration'),
                                'form_options' => ['required'],
                                'help' => 'In Minutes',
                            ])
                            @include('admin.components.inputs.success-btn', [
                                'button_text' => trans('Update Course Duration'),
                                'button_extra_class' => 'float-right',
                            ])
                        </div>
                        {!! Form::close() !!}
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    @endpush
@endisset


@push('script')
    <script>
        $(document).ready(function() {

            $(document).on('change', 'select[name="course_category_id"]', () => {
                $('select[name="course_tags_id[]"]').val(null).trigger('change');
                $('select[name="course_sub_category_id"]').val(null).trigger('change');
                // getSubCategories();
            })

            $('#is_free').on('change', (event) => {
                let checked = event.target.checked
                if (checked) {
                    console.log($('#package-form'));
                    $('#package-form').removeClass('d-block')
                    $('#package-form').addClass('d-none')
                } else {
                    $('#package-form').addClass('d-block')
                    $('#package-form').removeClass('d-none')
                }
            })


            $('select[name="course_sub_category_id"]').select2({
                ajax: {
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/course-category/get_categories/actions`,
                    data: function(params) {
                        var query = {
                            category_id: $('[name="course_category_id"]').val()
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        console.log(data);
                        return {
                            results: data
                        };
                    }
                }
            });


            $('select[name="course_tags_id[]"]').select2({
                ajax: {
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/course-tag/actions/get-tags`,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            category_id: $('[name="course_category_id"]').val()
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data
                        };
                    }
                }
            });


            $('select[name="job_role_id[]"]').select2({
                ajax: {
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/job-role/actions/get-job-roles`,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data
                        };
                    }
                }
            });

            $('select[name="specialty_area_id[]"]').select2({
                ajax: {
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/specialty-area/actions/get-specialty-areas`,
                    data: function(params) {
                        var query = {
                            search: params.term,
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data
                        };
                    }
                }
            });

            $(document).on('change', 'select[name="course_category_id"]', () => {
                $('select[name="course_tags_id[]"]').val(null).trigger('change');
            })

            $('select[name="course_tags_id[]"]').select2({
                ajax: {
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/course-tag/actions/get-tags`,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            category_id: $('[name="course_category_id"]').val()
                        }
                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function(data) {
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data
                        };
                    }
                }
            });


            $('#user_id').select2({
                ajax: {
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/course/actions/get-trainers`,
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

            $('select[name="learn[]"]').select2({
                'multiple': true,
                'tags': true
            });


            $('select[name="prerequisites[]"]').select2({
                'multiple': true,
                'tags': true
            });


            let setupFeesInput = function() {
                let selectedCourseType = $('select[name="course_type"]').val();

                const COURSE = "{{ \App\Domains\Course\Enum\CourseType::COURSE }}";
                const MICRODEGREE = "{{ \App\Domains\Course\Enum\CourseType::MICRODEGREE }}";

                if (selectedCourseType == MICRODEGREE) {
                    $('input[name="fees"]').parent().removeClass('d-none');
                    $('input[name="fees"]').prop('required', true);
                } else {
                    $('input[name="fees"]').parent().addClass('d-none');
                    $('input[name="fees"]').prop('required', false);
                }

            }

            $(document).on('change', 'select[name="course_type"]', setupFeesInput);
            $(document).ready(setupFeesInput)

            // setupFeesInput();

            //For Froala Editor//
            var editor = new FroalaEditor('#description', {
                key: 'CTD5xB1C2G1G1A16B3wc2DBKSPJ1WKTUCQOd1OURPE1KDc1C-7J2A4D4A3C6E2G2F4E1F1=='
            });


        });
    </script>
@endpush
