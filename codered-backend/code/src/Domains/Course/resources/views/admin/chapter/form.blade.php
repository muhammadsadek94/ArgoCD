<div class="panel-body row">


    @include('admin.components.inputs.text', [
        'name' => 'name',
        'label' => trans('course::lang.name'),
        'form_options' => ['required'],
        'cols' => 'col-12',
    ])



    @include('admin.components.inputs.select', [
        'name' => 'activation',
        'label' => trans('course::lang.status'),
        'form_options' => ['required'],
        'select_options' => [
            0 => 'Deactivated',
            1 => 'Active',
        ],
    ])

    @include('admin.components.inputs.number', [
        'name' => 'sort',
        'label' => trans('Sort'),
        'form_options' => ['required', 'min' => 1],
    ])

    {{ Form::hidden('drip_time', @$row->drip_time ?? 0) }}
{{--    @include('admin.components.inputs.number', [--}}
{{--        'name' => 'drip_time',--}}
{{--        'label' => trans('Drip (in days)'),--}}
{{--        'form_options' => ['required', 'min' => 0],--}}
{{--    ])--}}

    @include('admin.components.inputs.textarea', [
        'name' => 'description',
        'label' => trans('Description'),
        'form_options' => ['required', 'rows' => 2],
        'cols' => 'col-12',
    ])

    @include('admin.components.inputs.textarea', [
        'name' => 'course_objective',
        'label' => 'Chapter Objective',
        'form_options' => ['', 'rows' => 2],
        'cols' => 'col-12',
    ])


    {!! Form::hidden('course_id', request('course_id')) !!}


    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])
    <div class="knowledge-assessment col-12" id="knowledge-assessment">

        @if (!isset($row))
            @include("{$view_path}.partials.create-knowledge-assessment")
        @endif

        @include("{$view_path}.partials.update-knowledge-assessment")

    </div>


	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>
