    <h2>Skill Coverd</h2>
    @foreach ($row->microdegree->skills_covered ?? [] as $skills_covered)
        <div class="row skills_covered-item">
            <div class="col-11">
                @include('admin.components.inputs.text', [
                    'name' => 'skills_covered[]',
                    'label' => trans('Skill'),
                    'value' => is_array($skills_covered) ? $skills_covered : $skills_covered ?? '',
                    'form_options' => ['required'],
                    'cols' => 'col-12',
                ])
            </div>
            <div class="col-1 mt-3">
                @if ($loop->first)
                    <button type="button" class="btn btn-secondary" data-toggle="duplicate-input"
                        data-duplicate=".skills_covered-item" data-target="#skills_covered-multiple"
                        data-remove=".skills_covered-item" data-toggledata="<i class='fa fa-minus'></i>"
                        data-toggleclass="btn-danger btn-danger">
                        <i class="fa fa-plus"></i>
                    </button>
                @else
                    <button type="button" class="btn btn-danger" data-toggle="remove-input"
                        data-duplicate="#skills_covered > .row" data-target="#skills_covered-multiple"
                        data-remove=".skills_covered-item" data-toggledata="<i class='fa fa-minus'></i>"
                        data-toggleclass="btn-secondary btn-danger">
                        <i class="fa fa-minus"></i>
                    </button>
                @endif
            </div>
        </div>
    @endforeach
