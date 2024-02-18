



<div id="royalties" class="col-12  px-0">
<h2>Royalties</h2>

	@foreach ($row->royalties()->orderBy('created_at', 'asc')->get() as $index => $royalty)
		<div class="row  features-item">
			<div class="col-3">
				@include('admin.components.inputs.number', [
					'name' => 'advances[]',
					'label' => "Outstanding advances",
					'form_options'=> ['readonly'],
					'cols' => 'col-12',
					"value" => $royalty->outstanding_advances
				])
			</div>
			<div class="col-3">
				@include('admin.components.inputs.number', [
					'name' => 'royalties[]',
					'label' => "Royalty",
					'form_options'=> ['readonly'],
					'cols' => 'col-12',
					"value" => $royalty->royalty

				])
			</div>
			<div class="col-3">
			@include('admin.components.inputs.number', [
					'name' => 'royalties_carried_out[]',
					'label' => "Royalties Carried Out From Last Quarter",
					'form_options'=> ['readonly'],
					'cols' => 'col-12',
					"value" => $royalty->royalties_carried_out

				])
			</div>
			<div class="col-5">
			@include('admin.components.inputs.select', [
				'name' => 'courses[]',
				'label' => trans("Courses to be Added to Bundle"),
				'cols' => 'col-lg-6 col-12 courses-input',
				'select_options' =>  $courses,
				'form_options'=> ['disabled '],
				"value" => $royalty->course_id

			])
			</div>
			
		</div>
	@endforeach	
</div>
<div id="features-multiple-create" class="col-12 px-0">
</div>
