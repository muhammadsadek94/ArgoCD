<div class="panel-body row">

        @include('admin.components.inputs.select', [
                             'name'           => 'caption_id',
                             'label'          => 'Captions Language',
                             'form_options'   => ['placeholder' => 'Select Caption', 'required'],
                             'select_options' => $caption_data,
                             'cols' => 'col-12',

                         ])
        @include('admin.components.inputs.file', [
                          'name' => 'video[file]',
                          'label' => trans("Caption"),
                          'form_options'=> ['required', 'id' => 'video'],
                          'cols' => 'col-12',
                      ])


</div>
