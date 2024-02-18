@isset($row)
	<h2>METADATA</h2>
	@foreach($row->metadata ?? [] as $metadata)
		<div class="row metadata-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'metadata[name][]',
					'label' => trans("Name"),
					'value' => is_array($metadata) ? $metadata['name'] : $metadata->name ?? '',
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
				@include('admin.components.inputs.textarea', [
					'name' => 'metadata[content][]',
					'label' => trans("Content"),
					'value' => is_array($metadata) ? $metadata['content'] : $metadata->content ?? '',
					'form_options'=> [
						'required',
						'rows' => 5
					],
					'cols' => 'col-12',
				])
			</div>
			<div class="col-1 mt-3">
				@if($loop->first)
					<button
							type="button"
							class="btn btn-secondary"
							data-toggle="duplicate-input"
							data-duplicate=".metadata-item"
							data-target="#metadata-multiple-create"
							data-remove=".metadata-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#metadata > .row"
							data-target="#metadata-multiple-create"
							data-remove=".metadata-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
<div id="metadata-multiple-create" class="col-12 px-0">
</div>
