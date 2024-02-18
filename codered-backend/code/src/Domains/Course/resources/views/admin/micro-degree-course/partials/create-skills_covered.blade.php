<div id="skills_covered" class="col-12">
	<h2>Skill Coverd</h2>
	<div class="row skills_covered-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'skills_covered[]',
				'label' => trans("Skill"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#skills_covered > .row"
					data-target="#skills_covered-multiple"
					data-remove=".skills_covered-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
