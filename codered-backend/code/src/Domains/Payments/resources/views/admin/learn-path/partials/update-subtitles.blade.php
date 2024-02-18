@isset($row)
	<h2>subtitles</h2>
	@foreach($row->subtitles ?? [] as $subtitles)
		<div class="row subtitles-item">
			<div class="col-11">
				@include('admin.components.inputs.text', [
					'name' => 'subtitles[]',
					'label' => trans("subtitles"),
					'value' => $subtitles ?? '',
					'form_options'=> ['required'],
					'cols' => 'col-12',
				])
			</div>
			<div class="col-1 mt-3">
				@if($loop->first)
					<button
							type="button"
							class="btn btn-secondary"
							data-toggle="duplicate-input"
							data-duplicate=".subtitles-item"
							data-target="#subtitles-multiple-create"
							data-remove=".subtitles-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-danger btn-danger">
						<i class="fa fa-plus"></i>
					</button>
				@else
					<button
							type="button"
							class="btn btn-danger"
							data-toggle="remove-input"
							data-duplicate="#subtitles > .row"
							data-target="#subtitles-multiple-create"
							data-remove=".subtitles-item"
							data-toggledata="<i class='fa fa-minus'></i>"
							data-toggleclass="btn-secondary btn-danger">
						<i class="fa fa-minus"></i>
					</button>
				@endif
			</div>
		</div>
	@endforeach
@endisset

{{-- @if($row->subtitles == null|| count ($row->subtitles) == 0 )
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
@endif --}}
<div id="subtitles-multiple-create" class="col-12 px-0">
</div>
