<div class="panel-body row">
    @include('admin.components.inputs.select', [
        'name' => 'type',
        'label' => trans("faq::lang.types"),
        'form_options'=> [
            'required'
        ],
        'select_options' =>["1" => trans('faq::lang.pricing'),
                            "2" => trans('faq::lang.account'),
                            "3" => trans('faq::lang.courses'),
                            "4" => trans('faq::lang.certificates')]])
 <div class="col"></div>
    @include('admin.components.inputs.text', ['name' => 'question_en', 'label' => trans("faq::lang.question"), 'form_options'=> ['required']])

{{--    @include('admin.components.inputs.text', ['name' => 'question_ar', 'label' => trans("faq::lang.question_ar"), 'form_options'=> ['required']])--}}
<div class="col"></div>
    @include('admin.components.inputs.textarea', ['name' => 'answer_en', 'label' => trans("faq::lang.answer"), 'form_options'=> ['required']])

{{--    @include('admin.components.inputs.textarea', ['name' => 'answer_ar', 'label' => trans("faq::lang.answer_ar"), 'form_options'=> ['required']])--}}
	<div class="col"></div>

    @include('admin.components.inputs.select', [
        'name' => 'activation',
        'label' => trans("faq::lang.status"),
        'form_options'=> [
            'required'
        ],
        'select_options' =>  ["1" => trans('faq::lang.active'), "0" => trans('faq::lang.suspended') ],
        'value'=>1
    ])

{{--    @include('admin.components.inputs.select', ['name' => 'app_type', 'label' => trans("faq::lang.app_type"), 'form_options'=> ['required'], 'select_options' =>  ["1" => trans('faq::lang.user'), "2" => trans('faq::lang.provider') ]])--}}
	{!! Form::hidden('app_type', App\Domains\Faq\Enum\AppTypes::USER_APP, ['id' => 'id']) !!}
    @include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>
