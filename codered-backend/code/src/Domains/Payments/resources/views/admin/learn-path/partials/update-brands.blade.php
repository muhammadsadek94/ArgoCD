@isset($row)
	<h2>brands</h2>
	@foreach($row->brands ?? [] as $brands)
		<div class="row brands-item">
			<div class="col-11">
                @include('admin.components.inputs.image', [
        'name' => 'brands[]',
        'label' => trans('Cover Image'),
        'cols' => 'col-lg-8',
        'value' => $row->brands ?? null,
        'placeholder' => isset($row->cover) ? asset("{$row->cover->full_url}") : null,
        'endpoint' => url(Constants::ADMIN_BASE_URL . '/learn-path/action/upload-image'),
        'form_options' => ['required']
    ])

            </div>
			<div class="col-1 mt-3">
				@if($loop->first)
					<button
							type="button"
							class="btn btn-secondary"
							data-toggle="duplicate-input"
							data-duplicate=".brands-item"
							data-target="#brands-multiple-create"
							data-remove=".brands-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#brands > .row"
							data-target="#brands-multiple-create"
							data-remove=".brands-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset
@if($row->brands == null|| count ($row->brands) ==0 )
    <div class="row brands-item">
        <div class="col-11">
            @include('admin.components.inputs.file-ajax', [
         'name' => 'brands[]',
         'label' => trans('Cover Image'),
         'cols' => 'col-lg-8',
         'value' => $row->cover->id ?? null,
         'placeholder' => isset($row->cover) ? asset("{$row->cover->full_url}") : null,
         'endpoint' => url(Constants::ADMIN_BASE_URL . '/learn-path/action/upload-image'),
         'form_options' => ['required']
     ])

        </div>
        <div class="col-1 mt-3">
            <button
                type="button"
                class="btn btn-secondary"
                data-toggle="duplicate-input"
                data-duplicate="#brands > .row"
                data-target="#brands-multiple-create"
                data-remove=".brands-item"
                data-toggledata="<i class='fa fa-minus'></i>"
                data-toggleclass="btn-secondary btn-danger">
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    </div>
@endif
<div id="brands-multiple-create" class="col-12 px-0">
</div>
