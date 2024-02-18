@isset($row)
	<h2>project</h2>
	@foreach($row->microdegree->project ?? [] as $project)
		<div class="row project-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'project[title][]',
					'label' => trans("Title"),
					'value' => is_array($project) ? $project['title'] : $project->title ?? '',
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
				@include('admin.components.inputs.textarea', [
					'name' => 'project[description][]',
					'label' => trans("Description"),
					'value' => is_array($project) ? $project['description'] : $project->description ?? '',
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
							data-toggle="duplicate-input"
							data-duplicate=".project-item"
							data-target="#project-multiple"
							data-remove=".project-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#project > .row"
							data-target="#project-multiple"
							data-remove=".project-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
