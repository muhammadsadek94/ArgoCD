<h2>key features</h2>
@foreach ($row->microdegree->key_features ?? [] as $key_features)
    <div class="row key_features-item">
        <div class="col-11">
            @include('admin.components.inputs.text', [
                'name' => 'key_features[]',
                'label' => trans('Skill'),
                'value' => is_array($key_features) ? $key_features : $key_features ?? '',
                'form_options' => ['required'],
                'cols' => 'col-12',
            ])
        </div>
        <div class="col-1 mt-3">
            @if ($loop->first)
                <button type="button" class="btn btn-secondary" data-toggle="duplicate-input"
                    data-duplicate=".key_features-item" data-target="#key_features-multiple"
                    data-remove=".key_features-item" data-toggledata="<i class='fa fa-minus'></i>"
                    data-toggleclass="btn-danger btn-danger">
                    <i class="fa fa-plus"></i>
                </button>
            @else
                <button type="button" class="btn btn-danger" data-toggle="remove-input"
                    data-duplicate="#key_features > .row" data-target="#key_features-multiple"
                    data-remove=".key_features-item" data-toggledata="<i class='fa fa-minus'></i>"
                    data-toggleclass="btn-secondary btn-danger">
                    <i class="fa fa-minus"></i>
                </button>
            @endif
        </div>
    </div>
@endforeach
