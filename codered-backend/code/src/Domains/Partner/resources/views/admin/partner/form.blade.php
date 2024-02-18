<div class="panel-body row">




	@include('admin.components.inputs.text', [
        'name' => 'name',
        'label' => trans('Name'),
        'form_options'=> [
          'required'
        ],
        'cols' => 'col-12 col-md-6'
    ])

    @include('admin.components.inputs.select', [
    'name' => 'activation',
    'label' => trans("course::lang.status"),
    'form_options'=> ['required'],
    'select_options' => [
                1 => trans('lang.active'),
                0 => trans('Deactivated'),
    ],
    'cols' => 'col-12 col-md-6'
])


    @include('admin.components.inputs.text', [
        'name' => 'partner_name',
        'label' => trans('Partner id'),
        'form_options'=> [
          'required',
        ],
        'cols' => 'col-12 col-md-6'
    ])


    <div class="col-lg-6">
         <div class="row">
             @include('admin.components.inputs.text', [
                 'name' => 'partner_secret',
                 'label' => trans('Partner Secret'),
                 'form_options'=> [
                   !isset($row) ? 'required' : '',
                             'placeholder'=>'***********'

                 ],
                 'value' => '',
                 'cols' => 'col-12 col-lg-10 col-md-9 mb-0'
             ])
             <div class="col-lg-2 d-flex align-items-end pl-0 my-3 my-lg-0">
                 <button type="button" class="btn btn-secondary radius-0 w-100 px-1" id="generateValue">
                     Re-generate
                     <i class="fas fa-sync-alt"></i>
                 </button>
             </div>
         </div>
    </div>


	@include('admin.components.inputs.select', [
		'name' => 'course_ids[]',
		'label' => trans("Courses"),
		'form_options'=> ['required', 'multiple'],
		'select_options' =>  $courses_list,
		'value' =>  isset($row) ? $row->courses()->pluck('course_id') : null,
        'cols' => 'col-12 col-md-12'

	])



	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>


@push('script')
	<script>


        $(document).ready(function () {

            $('select[name="course_ids[]"]').select2({
                ajax: {
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/course/actions/get-course`,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            id: $('[name="course_ids"]').val()
                        }

                        return query;
                    },
                    processResults: function (data) {
                        console.log(data);
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data
                        };
                    }
                }});
            // $('select[name="course_ids[]"]').select2({
            //     'multiple': true,
            //     'tags': true
            //
            // });

            $('#generateValue').click(function (){
                generateSecret()
                alert('please make sure to copy the partner secret as this is the only time you can see it?')
            })
            @if(!isset($row))
                generateSecret()
            @else
                $('#partner_secret').val('')
            @endif
        });

        function generateSecret(){
            var randomstring = Math.random().toString(36) + Math.random().toString(36);
            $('#partner_secret').val(randomstring)
        }

	</script>
@endpush

