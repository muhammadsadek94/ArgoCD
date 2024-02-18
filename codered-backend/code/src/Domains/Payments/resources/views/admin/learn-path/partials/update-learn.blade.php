@isset($row)
	<h2>What you will learn ?</h2>
	@foreach($row->learn ?? [] as $learn)
		<div class="row what-learn-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'learn[]',
					'label' => trans("Title"),
					'value' =>$learn,
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
			</div>
			<div class="col-1 mt-3">
				@if($loop->first)
					<button
							type="button"
							class="btn btn-secondary"
							data-toggle="duplicate-input"
							data-duplicate=".what-learn-item"
							data-target="#what-learn-multiple-create"
							data-remove=".what-learn-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#what-learn > .row"
							data-target="#what-learn-multiple-create"
							data-remove=".what-learn-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
<div id="what-learn-multiple-create" class="col-12 px-0">
</div>

