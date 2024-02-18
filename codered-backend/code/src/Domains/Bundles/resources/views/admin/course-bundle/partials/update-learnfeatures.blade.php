@isset($row)
	<h4>Things you'll learn</h4>
	@foreach($row->learn_features as $feature)
		<div class="row learn-feature-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'learn_features[]',
					'label' => trans("Feature"),
					'value' => $feature,
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
				
			</div>
			<div class="col-1 mt-3">
				@if($loop->first)
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
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-learnduplicate="#learn-feature > .row"
							data-learntarget="#learn-feature-multiple"
							data-learnremove=".learn-feature-item"
							data-learntoggledata="<i class='fa fa-minus'></i>"
							data-learntoggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset