<div id="skills" class="col-12">
	<h2>Skills</h2>
{{--    @include('admin.components.inputs.text', ['name' => 'skills_description', 'label' => trans('payments::lang.skills_description'), 'form_options'=> ['required']])--}}

    <div class="row skills-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'skills[]',
				'label' => trans("Skills"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#skills > .row"
					data-target="#skills-multiple-create"
					data-remove=".skills-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
<div id="skills-multiple-create" class="col-12 px-0">
</div>
