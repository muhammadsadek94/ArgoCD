<div class="panel-body row">

    @include('admin.components.inputs.image', [
		'name' => 'author_image_id',
		'label' => trans('Author Image'),
		'cols' => 'col-12',
		'value' => $row->author_image_id ?? null,
		'placeholder' => isset($row->author_image) ? asset("{$row->author_image->full_url}") : null,
		'endpoint' => url(Constants::ADMIN_BASE_URL . '/quote/action/upload-author-image'),
		'form_options' => ['required']
	])


    <div class="col-12"></div>

	@include('admin.components.inputs.text', ['name' => 'author_name', 'label' => trans('blog::lang.author_name'), 'form_options'=> ['required']])

	@include('admin.components.inputs.text', ['name' => 'author_position', 'label' => trans('blog::lang.author_position'), 'form_options'=> ['required']])

	@include('admin.components.inputs.textarea', [
    	'name' => 'quote',
    	'label' => trans('blog::lang.quote'),
    	'form_options' => [
    	    'required',
    	    'rows' => 2
		],
		 'cols' => 'col-12'
	])

	@include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("blog::lang.status"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Active", "0" => "Suspended" ]])


	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>

@push('script')
<script>
	$(document).ready(function(){



	});
</script>
@endpush
