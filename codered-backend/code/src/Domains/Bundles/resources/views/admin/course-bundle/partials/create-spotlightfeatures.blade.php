<div id="what-learn" class="col-12">
	<h4>Bundle Spotlight Features</h4>
	<div class="row spot-feature-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'bundle_spotlight[]',
				'label' => trans("Feature"),
				'value' => null,
				'form_options'=> ['required'],
				'cols' => 'col-12',
			])
		</div>
		<div class="col-1 mt-3">
			<button
					type="button"
					class="btn btn-secondary"
					data-toggle="duplicate-input-spot"
					data-spotduplicate=".spot-feature-item"
					data-spottarget="#spot-feature-multiple"
					data-spotremove=".spot-feature-item"
					data-spottoggledata="<i class='fa fa-minus'></i>"
					data-spottoggleclass="btn-danger btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
