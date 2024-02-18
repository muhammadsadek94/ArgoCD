@isset($row)
	<h2>Flags</h2>
	@foreach($row->flags ?? [] as $flag)
		<div class="row flag-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'flags[title][]',
					'label' => trans("Sub Title"),
					'value' => is_array($flag) ? $flag['title'] : $flag->title ?? '',
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
				@include('admin.components.inputs.textarea', [
					'name' => 'flags[description][]',
					'label' => trans("Description"),
					'value' => is_array($flag) ? $flag['description'] : $flag->description ?? '',
					'form_options'=> [
						'required',
						'rows' => 2
					],
					'cols' => 'col-12',
				])
			</div>
			<div class="col-1 mt-3">
				@if($loop->first)
					<button
							type="button"
							class="btn btn-secondary"
							data-toggle="duplicate-input-custom"
							data-duplicate=".flag-item"
							data-target="#flag-multiple"
							data-remove=".flag-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input-custom"
							data-duplicate="#flag > .row"
							data-target="#flag-multiple"
							data-remove=".flag-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
<div id="flag-multiple" class="col-12 px-0">
</div>