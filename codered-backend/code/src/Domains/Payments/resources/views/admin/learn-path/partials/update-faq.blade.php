@isset($row)
	<h2>FAQ</h2>
	@foreach($row->faq ?? [] as $faq)
		<div class="row faq-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'faq[question][]',
					'label' => trans("Question"),
					'value' => is_array($faq) ? $faq['question'] : $faq->question ?? '',
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
				@include('admin.components.inputs.textarea', [
					'name' => 'faq[answer][]',
					'label' => trans("Answer"),
					'value' => is_array($faq) ? $faq['answer'] : $faq->answer ?? '',
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
							data-duplicate=".faq-item"
							data-target="#faq-multiple-create"
							data-remove=".faq-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#faq > .row"
							data-target="#faq-multiple-create"
							data-remove=".faq-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
<div id="faq-multiple-create" class="col-12 px-0">
</div>
