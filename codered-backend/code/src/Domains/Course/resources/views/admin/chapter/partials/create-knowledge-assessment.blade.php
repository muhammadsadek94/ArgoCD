<div id="knowledge-assessment-tags" class="col-12 px-0">

    @if(!isset($row))
        <div class="w-100">
            <div id="create-knowledge-assessment-tags" class="px-0">
                <h2>Add Tagging</h2>
                <div class="row create-knowledge-assessment px-2">
                    <div class="col-11">

                        <div class="row">
                            @include('admin.components.inputs.select', [
                               'name' => 'competency_id[]',
                                'label' => trans("Competencies"),
                                'cols' => ' col-md-4 ',
                                'form_options'=> [
                                    'required'
                               ],
                               'select_options' =>  $competencies_list,
                               'value' => null
                           ])
                            @include('admin.components.inputs.select', [
                                'name' => 'speciality_area_id[]',
                                 'label' => trans("Speciality Areas"),
                                 'cols' => ' col-md-4 ',
                                 'form_options'=> [
                                    'required'
                                ],
                                'select_options' =>  $speciality_areas_list,
                                'value' => null
                            ])
                            @include('admin.components.inputs.select', [
                                'name' => 'ksa_id[]',
                                 'label' => trans("Ksa"),
                                 'cols' => ' col-md-4 ',
                                 'form_options'=> [
                                    'required'
                                ],
                                'select_options' => $ksa_list,
                                'value' => null,
                            ])
                        </div>

                    </div>
                    <div class="col-1 mt-3 pt-1">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-toggle="duplicate-input"
                            data-duplicate="#create-knowledge-assessment-tags > .row"
                            data-target="#knowledge-asssessment-multiple-create"
                            data-remove=".create-knowledge-assessment"
                            data-toggledata="<i class='fa fa-minus'></i>"
                            data-toggleclass="btn-secondary btn-danger">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    @endif


</div>

</div>
<div id="knowledge-asssessment-multiple-create" class="col-12 px-0 px-2">
</div>
