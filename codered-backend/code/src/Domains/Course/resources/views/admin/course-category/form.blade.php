<div class="panel-body row">
    @include('admin.components.inputs.image', [
        'name' => 'image_id',
        'label' => trans('course::lang.image'),
        'cols' => 'col-lg-4 offset-lg-4 col-12 ',
        'value' => $row->image_id ?? null,
        'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null,
        'endpoint' => url(Constants::ADMIN_BASE_URL . '/course-category/actions/upload'),
    ])
    <div class="col-12"></div>
    @include('admin.components.inputs.text', [
        'name' => 'name',
        'label' => trans('course::lang.name'),
        'form_options' => ['required'],
    ])

    @include('admin.components.inputs.select', [
        'name' => 'activation',
        'label' => trans('course::lang.Activation'),
        'form_options' => ['required'],
        'select_options' => ['1' => 'Activate', '0' => 'Suspend'],
    ])

    @include('admin.components.inputs.select', [
        'name' => 'cat_parent_id',
        'label' => 'Select Parent Category',
        'form_options' => [''],
        'select_options' => $categories,
    ])

    @if(isset($row) && !$row->cat_parent_id)
        @include('admin.components.inputs.number',['name' => 'sort','label' => trans('Sort'), 'form_options'=> ['readonly' => true ]])
    @else
        @include('admin.components.inputs.number',['name' => 'sort','label' => trans('Sort'), 'form_options'=> ['']])
    @endif
    @include('admin.components.inputs.select', [
        'name' => 'label_color',
        'label' => trans('Color'),
        'form_options' => ['required'],
        'select_options' => $colors_lists,
    ])
{{-- 
    @include('admin.components.inputs.text', [
        'name' => 'icon_class_name',
        'label' => trans('course::lang.icon_class_name'),
        'form_options' => [''],
    ]) --}}


    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])


</div>


@push('script-bottom')
<script>
    $(document).ready(function () {
       $('#cat_parent_id').change(function () { 
            if($(this).val() == 0){
                $('#sort').val(null);
                $('#sort').attr('readonly', true);
            } else{
                $('#sort').removeAttr('readonly');
            }
        });
    });
</script>
@endpush