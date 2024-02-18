<div id="subtitles" class="col-12">
	<h2>subtitles</h2>
	<div class="row subtitles-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'subtitles[]',
				'label' => trans("subtitles"),
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input"
					data-duplicate="#subtitles > .row"
					data-target="#subtitles-multiple-create"
					data-remove=".subtitles-item"
					data-toggledata="<i class='fa fa-minus'></i>"
					data-toggleclass="btn-secondary btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
<div id="subtitles-multiple-create" class="col-12 px-0">
</div>
