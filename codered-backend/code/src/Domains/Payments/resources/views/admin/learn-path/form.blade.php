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
        'name' => 'cover_id',
        'label' => trans('Cover Image'),
        'cols' => 'col-lg-8',
        'value' => $row->cover->id ?? null,
        'placeholder' => isset($row->cover) ? asset("{$row->cover->full_url}") : null,
        'endpoint' => url(Constants::ADMIN_BASE_URL . '/learn-path/action/upload-image'),
        'form_options' => ['required'],
    ])


    @include('admin.components.inputs.image', [
        'name' => 'image_id',
        'label' => trans('payments::lang.image'),
        'cols' => 'col-4',
        'value' => $row->image->id ?? null,
        'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null,
        'endpoint' => url(Constants::ADMIN_BASE_URL . '/learn-path/action/upload-image'),
        'form_options' => ['required'],
    ])

    @include('admin.components.inputs.text', [
        'name' => 'name',
        'label' => trans('payments::lang.name'),
        'form_options' => ['required'],
    ])

@include('admin.components.inputs.textarea', [
    'name' => 'description[]',
    'label' => 'Description',
    'form_options' => ['required', 'rows' => 2],
    'value' => isset($row) ? $row->description[0] : '',
    'cols' => 'col-12',
])




@include('admin.components.inputs.textarea', [
    'name' => 'overview',
    'label' => trans('payments::lang.overview'),
    'form_options' => ['required', 'rows' => 2],
    'cols' => 'col-12',
])



    @include('admin.components.inputs.text', [
        'name' => 'slug_url',
        'label' => trans('Slug Url (Prefer \'-\' as separator)'),
        'form_options' => ['required', 'placeholder' => 'learnpath-security'],
        'cols' => 'col-12 col-md-6',
    ])
    @include('admin.components.inputs.text', [
        'name' => 'price',
        'label' => trans('payments::lang.price'),
        'form_options' => ['required'],
    ])
   {{-- @include('admin.components.inputs.text', [
        'name' => 'price_description',
        'label' => trans('payments::lang.average salary description'),
        'form_options' => ['required'],
    ]) --}}
    @include('admin.components.inputs.text', [
        'name' => 'payment_url',
        'label' => trans('payments::lang.payment_url'),
        'form_options' => ['required'],
    ])
    <div class="col-12"></div>
    @include('admin.components.inputs.select', [
        'name' => 'category_id',
        'label' => trans('payments::lang.category'),
        'cols' => 'col-md-6 col-12  ',
        'form_options' => ['required'],
        'select_options' => $categories_list,
        'value' => isset($row) ? $row->access_id : [],
    ])

    @include('admin.components.inputs.select', [
        'name' => 'sub_category_id',
        'label' => 'Learning Path Sub Category',
        'form_options' => ['required','placeholder' => 'Learning Path Sub Category'],
        'select_options' => $sub_categories_list,
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
        'name' => 'activation',
        'label' => trans('payments::lang.status'),
        'form_options' => ['required'],
        'select_options' => ['1' => 'Active', '0' => 'Suspended'],
    ])

    @include('admin.components.inputs.select', [
        'name' => 'type',
        'label' => trans('Type'),
        'form_options' => ['placeholder' => 'Choose Type'],
        'value' => isset($row) ? $row->type : null,
        'id' => 'type_select',

        'select_options' => [
            \App\Domains\Payments\Enum\LearnPathType::CAREER => 'Career Path',
            \App\Domains\Payments\Enum\LearnPathType::SKILL => 'Skill Path',
            \App\Domains\Payments\Enum\LearnPathType::BUNDLE_COURSES => 'Course Bundle',
        ],
    ])

{{--    \App\Domains\Payments\Enum\LearnPathType::CERTIFICATE => 'Certificate Path',--}}
{{--    \App\Domains\Payments\Enum\LearnPathType::BUNDLE_CATEGORY => 'Bundle Category',--}}
    {{-- @include('admin.components.inputs.select', [
        'name' => 'categories[]',
        'label' => trans('Categories'),
        'cols' => 'col-md-6 col-12  categories ',
        'form_options' => ['multiple'],
        'select_options' => $categories_list,
        'value' => isset($row) ? $row->categories()->pluck('category_id') : [],
    ]) --}}

    @include('admin.components.inputs.select', [
        'name' => 'level',
        'label' => 'Level',
        'form_options' => ['required'],
        'select_options' => $level_list,
        'cols' => 'col-12 col-md-3',
    ])



    <div class="col-lg-12">
        <div class="card">
            <h2 class="card-header">
                Learning Path Info
            </h2>
        </div>
    </div>


    @include('admin.components.inputs.text', [
        'name' => 'avg_salary',
        'label' => trans('payments::lang.avg_salary'),
        'form_options' => ['required'],
    ])



    @include('admin.components.inputs.textarea', [
        'name' => 'for_who',
        'label' => trans('payments::lang.who-is-this-for'),
        'form_options' => ['required', 'rows' => 2 , 'class' => 'fr-view' , 'id'=>'for_whos'],
        'cols' => 'col-12',
    ])

    <div class="col-12">

        @if (!isset($row))
            @include("{$view_path}.partials.create-prerequisites")
        @endif

        @include("{$view_path}.partials.update-prerequisites")
    </div>



    {{-- @include('admin.components.inputs.select', [
        'name' => 'tools[]',
        'label' => 'Tools',
        'form_options' => ['required', 'multiple'],
        'select_options' => $tools,
        'cols' => 'col-12',
    ])

 --}}

    <div class="d-none courses col-12" id="courses">

        @if (!isset($row))
            @include("{$view_path}.partials.create-courses")
        @endif

        @include("{$view_path}.partials.update-courses")
    </div>


    @if (!isset($row))
        @include("{$view_path}.partials.create-learn")
    @endif

    @include("{$view_path}.partials.update-learn")


    @if (!isset($row))
        @include("{$view_path}.partials.create-feature")
    @endif

    @include("{$view_path}.partials.update-feature")


    @if (!isset($row))
        @include("{$view_path}.partials.create-subtitles")
    @endif

    @include("{$view_path}.partials.update-subtitles")


    {{-- @if (!isset($row)) --}}
    {{-- @include("{$view_path}.partials.create-brands") --}}
    {{-- @endif --}}

    {{-- @include("{$view_path}.partials.update-brands") --}}




    @if (!isset($row))
        @include("{$view_path}.partials.create-skills")
    @endif

    @include("{$view_path}.partials.update-skills")


    {{-- @if (!isset($row))
        @include("{$view_path}.partials.create-jobs")
    @endif

    @include("{$view_path}.partials.update-jobs") --}}


   @if(!isset($row->faq))
        @include("{$view_path}.partials.create-faq")
        @else
            @include("{$view_path}.partials.update-faq")

    @endif

    @if(!isset($row->metadata))
        @include("{$view_path}.partials.create-metadata")
        @else
            @include("{$view_path}.partials.update-metadata")
    @endif



    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])


    @push('script')
        <script>
            $(document).ready(function() {

                $(document).on('change', 'select[name="category_id"]', () => {
                    $('select[name="course_tags_id[]"]').val(null).trigger('change');
                    $('select[name="sub_category_id"]').val(null).trigger('change');
                })


                $('select[name="sub_category_id"]').select2({
                    ajax: {
                        url: `{{ url(Constants::ADMIN_BASE_URL) }}/course-category/get_categories/actions`,
                        data: function(params) {
                            console.log($('[name="category_id"]').val());
                            var query = {
                                category_id: $('[name="category_id"]').val()
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


                const BundleCategory = "{{ \App\Domains\Payments\Enum\LearnPathType::BUNDLE_CATEGORY }}";

                function setupFormInputs() {
                    let val = $('#type').val();
                    if (val == BundleCategory) {
                        $('.courses').addClass('d-none');
                        $('.categories').removeClass('d-none');
                        //
                        // $('[name="categories[]"]').prop('required', false);
                        // $('[name="courses[]"]').prop('required', false);
                        // $('[name="duration"]').prop('required', false);
                        //
                        // $('[name="type"]').prop('required', true);


                    } else {
                        $('.courses').removeClass('d-none');
                        $('.categories').addClass('d-none');
                    }


                }

                $('[name="type"]').on('change', setupFormInputs);

                setupFormInputs();


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


                      //For Froala Editor//
            var editor = new FroalaEditor('#for_whos', {
                key: 'CTD5xB1C2G1G1A16B3wc2DBKSPJ1WKTUCQOd1OURPE1KDc1C-7J2A4D4A3C6E2G2F4E1F1=='
            });
            });
        </script>
    @endpush
