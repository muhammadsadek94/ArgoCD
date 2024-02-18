<!--For Froala Editor-->
<link href="https://cdn.jsdelivr.net/npm/froala-editor@3.1.0/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.5/css/froala_style.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@3.1.0/js/froala_editor.pkgd.min.js">
</script>
<!--For Froala Editor-->
<div class="panel-body row">
	@include('admin.components.inputs.image', [
		'name' => 'image_id',
		'label' => trans('Main Image'),
		'cols' => 'col-3',
		'value' => $row->image_id ?? null,
		'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null,
		'endpoint' => url(Constants::ADMIN_BASE_URL . '/blog/actions/upload-image'),
		'form_options' => ['required']
	])


    @include('admin.components.inputs.image', [
		'name' => 'internal_image_id',
		'label' => trans('Internal Cover Photo'),
		'cols' => 'col-9',
		'value' => $row->internal_image_id ?? null,
		'placeholder' => isset($row->internal_image) ? asset("{$row->internal_image->full_url}") : null,
		'endpoint' => url(Constants::ADMIN_BASE_URL . '/blog/actions/upload-image'),
		'form_options' => ['required']
	])

    <div class="col-12"></div>

	@include('admin.components.inputs.text', ['name' => 'name', 'label' => trans('blog::lang.article_name'), 'form_options'=> ['required']])

	@include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("blog::lang.status"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Active", "0" => "Suspended" ]])


	@include('admin.components.inputs.textarea', [
    	'name' => 'description',
    	'label' => 'Article Description',
    	'form_options' => [
    	    '',
    	    'rows' => 2
		],
		 'cols' => 'col-12'
	])

	@include('admin.components.inputs.textarea', [
    	'name' => 'content',
    	'label' => 'Article Content',
    	'form_options' => [
    	    'required',
    	    'class' => 'fr-view',
    	    'id' => 'content',
		],
		 'cols' => 'col-12'
	])

	@include('admin.components.inputs.select', [
		'name' => 'article_category_id',
		'label' => trans("blog::lang.article_category"),
		'form_options'=> [
			'required',
			'placeholder' => 'Article Category'
		],
		'select_options' =>  $categories_list,
		'cols' => 'col-12 col-md-5'

	])

	@include('admin.components.inputs.select', [
	   'name' => 'tags[]',
	   'label' => 'Article Tags',
	   'form_options'=> ['multiple'],
	   'select_options' =>  isset($row->tags) && is_array($row->tags) ? array_combine(array_values($row->tags ?? []), array_values($row->tags ?? [])) : [],
	   'value' =>  isset($row) ? array_values($row->tags ?? []) : null,
	   'cols' => 'col-12 col-md-7'
   ])

   @include('admin.components.inputs.checkbox', [
		'name' => 'is_featured',
		'label' => 'Is Featured',
        'value' => 1,
		'isChecked' => isset($row) ? $row->is_featured == 1 : 0,
		'form_options' => [
		]
	])

	<input name="admin_id" type="hidden" value="{{ auth()->guard('admin')->user()->id }}">

	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>

@push('script')
<script>
	$(document).ready(function(){

		$('select[name="tags[]"]').select2({
                'multiple': true,
                'tags': true
        });

        var editor = new FroalaEditor('#content', {key: 'CTD5xB1C2G1G1A16B3wc2DBKSPJ1WKTUCQOd1OURPE1KDc1C-7J2A4D4A3C6E2G2F4E1F1=='});

	});
</script>
@endpush
