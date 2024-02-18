<div class="panel-body row">

    @include('admin.components.inputs.text', [
        'name' => 'name',
        'label' => 'Name',
        'form_options' => ['required'],
    ])
    @include('admin.components.inputs.date', [
        'name' => 'expired_at',
        'label' => 'Expires at',
        'form_options' => ['required'],
    ])
    @include('admin.components.inputs.number', [
        'name' => 'days',
        'label' => 'Access Duration (In Days)',
        'form_options' => ['required'],
    ])
    @include('admin.components.inputs.number', [
        'name' => 'number_vouchers',
        'label' => 'Number of Vouchers',
        'form_options' => ['required'],
    ])

    @include('admin.components.inputs.select', [
        'name' => 'payable_id',
        'label' => trans('Package'),
        'form_options' => ['required', 'placeholder' => 'Select Package'],
        'select_options' => $package_subscription_lists,
    ])

    @include('admin.components.inputs.select', [
        'name' => 'access_type',
        'label' => trans('Access Type'),
        'form_options' => ['required', 'placeholder' => 'Select Access Type'],
        'select_options' => [
            \App\Domains\User\Enum\SubscribeStatus::TRIAL => 'Trial',
            \App\Domains\User\Enum\SubscribeStatus::ACTIVE => 'Paid',
        ],
    ])


    @include('admin.components.inputs.select', [
        'name' => 'tags[]',
        'label' => trans('Tags to be assigned to users'),
        'form_options' => ['required', 'multiple'],
        'select_options' => $tags_list,
        'value' => isset($row) ? $row->tags : null,
        'cols' => 'col-12 col-md-6',
    ])

    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])

</div>

@push('script')
    <script>
        $(document).ready(function() {
            $('select[name="tags[]"]').select2({
                'multiple': true,
                'tags': true
            });
        });
    </script>
@endpush
