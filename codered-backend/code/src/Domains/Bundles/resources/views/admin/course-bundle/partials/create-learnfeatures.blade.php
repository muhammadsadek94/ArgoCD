<div id="what-learn" class="col-12">
	<h4>Things you'll learn Features</h4>
	<div class="row learn-feature-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'learn_features[]',
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
					data-toggle="duplicate-input-learn"
					data-learnduplicate=".learn-feature-item"
					data-learntarget="#learn-feature-multiple"
					data-learnremove=".learn-feature-item"
					data-learntoggledata="<i class='fa fa-minus'></i>"
					data-learntoggleclass="btn-danger btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
