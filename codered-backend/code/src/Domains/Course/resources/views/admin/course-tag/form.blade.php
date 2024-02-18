<div class="panel-body row">
    {{-- @include('admin.components.inputs.image', [ --}}
    {{-- 'name' => 'image_id', --}}
    {{-- 'label' => trans('course::lang.image'), --}}
    {{-- 'cols' => 'col-lg-4 offset-lg-4 col-12 ', --}}
    {{-- 'value' => $row->image_id ?? null, --}}
    {{-- 'placeholder' => isset($row->image) ? asset("{$row->image->path}") : null, --}}
    {{-- 'endpoint' => url(Constants::ADMIN_BASE_URL . '/course-tag/actions/upload') --}}
    {{-- ]) --}}
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
        'name' => 'course_category_id',
        'label' => trans('course::lang.Course Category'),
        'form_options' => ['required', 'placeholder' => 'Course Category'],
        'select_options' => $categories_list,
    ])

    <div class="col-12"></div>
    @include('admin.components.inputs.checkbox', [
        'name' => 'is_featured',
        'label' => 'Is Featured',
        'value' => 1,
        'isChecked' => isset($row) ? $row->is_featured == 1 : 0,
        'form_options' => [],
    ])

    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])
</div>
