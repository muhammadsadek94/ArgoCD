<div class="panel-body row">
    @include('admin.components.inputs.text', [
        'name' => 'name',
        'label' => trans('course::lang.name'),
        'form_options' => ['required'],
    ])
    @include('admin.components.inputs.text', [
        'name' => 'product_id',
        'label' => trans('Product ID'),
        'form_options' => ['required'],
    ])

    <input type="hidden" name="payable_type" value="{{ \App\Domains\Payments\Enum\PayableType::SUBSCRIPTION }}">

  {{-- @include('admin.components.inputs.select', [
        'name' => 'payable_type',
        'label' => trans('Type'),
        'form_options' => [
            'required',
            'placeholder' => 'Select Integration Type',

        ],
        'select_options' => [
            \App\Domains\Payments\Enum\PayableType::SUBSCRIPTION =>
                'Bundle / Pro Subscription / Learning Path / Individual Course / Certification',
            \App\Domains\Payments\Enum\PayableType::MICRODEGREE => 'Micro Degree & Certifications',
        ],
    ]) --}}

    @include('admin.components.inputs.select', [
        'name' => 'package_id',
        'label' => trans('Offer Name'),
        'select_options' => $packages_list,
        'value' => isset($row) ? $row->payable_id : null,
        'form_options' => ['required', "placeholder" => "Select Product"],
        'cols' => 'col-12',
    ])

    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])

</div>


@push('script-bottom')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
    <script>
        $(document).ready(function() {
            const PRO = {!! json_encode(\App\Domains\Payments\Enum\PayableType::SUBSCRIPTION) !!};
            const MICRODEGREE = {!! json_encode(\App\Domains\Payments\Enum\PayableType::MICRODEGREE) !!};

            const payableTypeHandler = function() {
                let val = $('#payable_type').val();
                if (val == PRO) {
                    $('#package_id').parent().removeClass('d-none');
                    $('#micro_degree_id').parent().addClass('d-none');
                }
                if (val == MICRODEGREE) {
                    $('#micro_degree_id').parent().removeClass('d-none');
                    $('#package_id').parent().addClass('d-none');

                }
            }

            $(document).on('change', '#payable_type', payableTypeHandler);
            $(document).ready(payableTypeHandler)
        });
    </script>
@endpush
