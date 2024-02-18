<div id="what-learn" class="col-12">
	<h4>Payment Features</h4>
	<div class="row payment-feature-item">
		<div class="col-11">
			@include('admin.components.inputs.text', [
				'name' => 'price_features[]',
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
					data-toggle="duplicate-input-pay"
					data-payduplicate=".payment-feature-item"
					data-paytarget="#payment-feature-multiple"
					data-payremove=".payment-feature-item"
					data-paytoggledata="<i class='fa fa-minus'></i>"
					data-paytoggleclass="btn-danger btn-danger">
				<i class="fa fa-plus"></i>
			</button>
		</div>
	</div>
</div>
