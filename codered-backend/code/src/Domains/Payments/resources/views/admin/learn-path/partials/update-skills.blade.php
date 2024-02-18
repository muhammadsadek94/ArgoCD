@isset($row)
	<h2>skills</h2>
{{--    @include('admin.components.inputs.text', ['name' => 'skills_description', 'label' => trans('payments::lang.skills_description'), 'form_options'=> ['required']])--}}

    @foreach($row->skills ?? [] as $skills)
		<div class="row what-skills-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'skills[]',
					'label' => trans("Title"),
					'value' =>$skills,
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
							data-duplicate=".what-skills-item"
							data-target="#what-skills-multiple-create"
							data-remove=".what-skills-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#what-skills > .row"
							data-target="#what-skills-multiple-create"
							data-remove=".what-skills-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
<div id="what-skills-multiple-create" class="col-12 px-0">
</div>
