<div class="panel-body row">

    @include('admin.components.inputs.text', ['name' => 'heading', 'label' => trans('bundles::lang.heading'), 'form_options'=> ['required']])

    @include('admin.components.inputs.text', ['name' => 'coupon_code', 'label' => trans('bundles::lang.coupon_code'), 'form_options'=> ['required']])


    {{--@include('admin.components.inputs.select', ['name' => 'course_bundle_id', 'label' => trans("bundles::lang.course_bundle"), 'form_options'=> ['required', 'placeholder' => 'Select Any Bundle'], 'select_options' =>  $course_bundle_list]) --}}


    @include('admin.components.inputs.textarea', [
        'name' => 'sub_heading',
        'label' => trans('bundles::lang.sub_heading'),
        'form_options' => [
            '',
            'rows' => 2
        ],
         'cols' => 'col-12'
    ])

    @include('admin.components.inputs.text', ['name' => 'background_color', 'label' => trans('bundles::lang.background_color') . '(Hex Color)', 'form_options'=> ['required']])

    @include('admin.components.inputs.text', ['name' => 'button_color', 'label' => trans('bundles::lang.button_color') . '(Hex Color)', 'form_options'=> ['required']])

    @include('admin.components.inputs.text', ['name' => 'button_text_color', 'label' => trans('bundles::lang.button_text_color') . '(Hex Color)', 'form_options'=> ['required']])

    @include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("bundles::lang.status"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Active", "0" => "Suspended" ]])




    @include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>

@push('script')
    <script>
        $(document).ready(function () {


        });
    </script>
@endpush
