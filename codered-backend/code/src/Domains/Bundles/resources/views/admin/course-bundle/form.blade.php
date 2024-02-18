<div class="panel-body row">


	@include('admin.components.inputs.image', [
		'name' => 'cover_image_id',
		'label' => trans('Cover Image'),
		'cols' => 'col-4',
		'value' => $row->cover_image_id ?? null,
		'placeholder' => isset($row->cover_image) ? asset("{$row->cover_image->full_url}") : null,
		'endpoint' => url(Constants::ADMIN_BASE_URL . '/course-bundle/action/upload-image'),
		'form_options' => ['required']
	])


    @include('admin.components.inputs.image', [
		'name' => 'image_id',
		'label' => trans('bundles::lang.image'),
		'cols' => 'col-lg-8 ',
		'value' => $row->image_id ?? null,
		'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null,
		'endpoint' => url(Constants::ADMIN_BASE_URL . '/course-bundle/action/upload-image'),
		'form_options' => ['required']
	])

	@include('admin.components.inputs.text', ['name' => 'name', 'label' => trans('bundles::lang.name'), 'form_options'=> ['required']])

	@include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("bundles::lang.status"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Active", "0" => "Suspended" ]])


	@include('admin.components.inputs.textarea', [
    	'name' => 'description',
    	'label' => 'Description',
    	'form_options' => [
    	    'required',
    	    'rows' => 2
		],
		 'cols' => 'col-12'
	])


	 @include('admin.components.inputs.select', [
        'name' => 'display_status',
         'label' => trans("Display Status"),
         'form_options'=> [],
         'select_options' =>  [
             \App\Domains\Bundles\Enum\DisplayStatus::LATEST   => 'New',
             \App\Domains\Bundles\Enum\DisplayStatus::SALE     => 'Sale',
             \App\Domains\Bundles\Enum\DisplayStatus::FEATURED  => 'Featured',
        ]
    ])



    @include('admin.components.inputs.select', [
        'name' => 'bundle_type',
         'label' => trans("Bundle Type"),
         'form_options'=> [],
         'select_options' =>  [
         \App\Domains\Bundles\Enum\BundleType::JOB      => 'Job Based Bundles',
         \App\Domains\Bundles\Enum\BundleType::CATEGORY => 'Category Based Bundles',
         \App\Domains\Bundles\Enum\BundleType::SKILL    => 'Skill Based Bundles',
        ]
    ])



    {{--  @include('admin.components.inputs.select', [
    	'name' => 'courses[]',
	 	'label' => trans("Courses to be Added to Bundle"),
	 	'cols' => 'col-lg-6 col-12 courses-input',
	 	'form_options'=> [
	 	     'multiple',

        ],
        'select_options' =>  $courses_list,
        'value' => isset($row) ? json_decode($row->course_id, true) : []


	])--}}



	{!! Form::hidden('access_type', null) !!}
    @include('admin.components.inputs.select', [
    	'name' => 'access_type',
	 	'label' => trans("Access Type"),
	 	'form_options'=> [
	 	    'required', 'placeholder' => 'Select Access Type',
	 	    (isset($row) ? 'disabled' : ''),
	 	    'id' => 'access_type_select'
        ],
	 	'select_options' =>  [
	 	    \App\Domains\Payments\Enum\AccessType::COURSES => 'Courses',
	 	    \App\Domains\Payments\Enum\AccessType::COURSE_CATEGORY => 'Course Category',
		]
	])
    @include('admin.components.inputs.select', [
    	'name' => 'categories[]',
	 	'label' => trans("Categories"),
	 	'cols' => 'col-lg-6 col-12 d-none categories-input ',
	 	'form_options'=> [
	 	     'multiple',
        ],
	 	'select_options' =>  $categories_list,
	 	'value' => isset($row) ? $row->access_id : []
	])

    @include('admin.components.inputs.select', [
    	'name' => 'courses[]',
	 	'label' => trans("Courses"),
	 	'cols' => 'col-lg-6 col-12 d-none courses-input',
	 	'form_options'=> [
	 	     'multiple',

        ],
	 	'select_options' =>  $courses_list,
        'value' => isset($row) ? $row->access_id : []

	])



    @include('admin.components.inputs.select', [
	   'name' => 'jobs[]',
	   'label' => 'Jobs',
	   'form_options'=> ['multiple'],
	   'select_options' =>  isset($row->jobs) && is_array($row->jobs) ? array_combine(array_values($row->jobs ?? []), array_values($row->jobs ?? [])) : [],
	   'value' =>  isset($row) ? array_values($row->jobs ?? []) : null,
	   'cols' => 'col-12 col-md-6'
   ])

   @include('admin.components.inputs.select', [
	   'name' => 'topics[]',
	   'label' => 'Topics',
	   'form_options'=> ['multiple'],
	   'select_options' =>  isset($row->topics) && is_array($row->topics) ? array_combine(array_values($row->topics ?? []), array_values($row->topics ?? [])) : [],
	   'value' =>  isset($row) ? array_values($row->topics ?? []) : null,
	   'cols' => 'col-12 col-md-6'
   ])

    @include('admin.components.inputs.select', [
        'name' => 'certifications[]',
        'label' => 'Certifications',
        'form_options'=> ['multiple'],
        'select_options' =>  isset($row->certifications) && is_array($row->certifications) ? array_combine(array_values($row->certifications ?? []), array_values($row->certifications ?? [])) : [],
        'value' =>  isset($row) ? array_values($row->certifications ?? []) : null,
        'cols' => 'col-12 col-md-6'
    ])



    @include('admin.components.inputs.select', [
        'name' => 'package_id',
        'label' => 'Package',
        'select_options' => $packages,
        'value' =>  isset($row) ? $row->package_id : null,
        'cols' => 'col-12 col-md-6'
    ])




    @if(!isset($row))
        @include("{$view_path}.partials.create-features")
    @endif

    <div id="description-feature-multiple" class="col-12">
        @include("{$view_path}.partials.update-features")
    </div>


    @if(!isset($row))
        @include("{$view_path}.partials.create-learnfeatures")
    @endif

    <div id="learn-feature-multiple" class="col-12">
        @include("{$view_path}.partials.update-learnfeatures")
    </div>


   <h2 class="col-12">Special Display Settings</h2>

    {{-- @include('admin.components.inputs.radio', [
				'name' => 'is_bestseller',
				'label' => trans("Bestseller"),
				'value' => 0,
				'isChecked' => isset($row) ? $row->is_bestseller == 1 : 0,
				'form_options'=> ['required'],
				'cols' => 'col-12',
	 ]) --}}
   <h4 class="col-12">Bestseller Details</h4>
    @include('admin.components.inputs.checkbox', [
		'name' => 'is_bestseller',
		'label' => 'BestSeller',
        'value' => 1,
		'isChecked' => isset($row) ? $row->is_bestseller == 1 : 0,
		'form_options' => [
		]
	])

	@include('admin.components.inputs.textarea', [
    	'name' => 'bestseller_brief',
    	'label' => 'Bestseller Description',
    	'form_options' => [
    	    'rows' => 2
		],
		 'cols' => 'col-12'
	])

	<h4 class="col-12">New Arrivals Details</h4>
	 @include('admin.components.inputs.checkbox', [
		'name' => 'is_new_arrival',
		'label' => 'New Arrivals',
        'value' => 1,
		'isChecked' => isset($row) ? $row->is_new_arrival == 1 : 0,
		'form_options' => [
		]
	])

	@include('admin.components.inputs.textarea', [
    	'name' => 'newarrival_brief',
    	'label' => 'New Arrival Description',
    	'form_options' => [
    	    'rows' => 2
		],
		 'cols' => 'col-12'
	])

	<h4 class="col-12">Deal of Week </h4>
	 @include('admin.components.inputs.date', [
        'name' => 'deal_end_date',
        'label' => trans('Select End Date'),
         'form_options'=> ['']
     ])


    <h4 class="col-12">Bundle Spotlight Details</h4>
    @include('admin.components.inputs.checkbox', [
		'name' => 'is_bundle_spotlight',
		'label' => 'Bundle Spotlight',
        'value' => 1,
		'isChecked' => isset($row) ? $row->is_bundle_spotlight == 1 : 0,
		'form_options' => [
		]
	])


    @if(!isset($row))
        @include("{$view_path}.partials.create-spotlightfeatures")
    @endif

    <div id="spot-feature-multiple" class="col-12">
        @include("{$view_path}.partials.update-spotlightfeatures")
    </div>


	<h2 class="col-12">Payment Terms</h2>


     @include('admin.components.inputs.text', [
    	'name' => 'payment_title', 'label' => trans('Payment Title'), 'form_options' => ['required'], 'cols' => 'col-md-6 col-12',
    	'value' => isset($row->payment_title) ? $row->payment_title : null
	])

	 @include('admin.components.inputs.text', [
    	'name' => 'price', 'label' => trans('Price'), 'form_options' => ['required'], 'cols' => 'col-md-6 col-12',
    	'value' => isset($row->price) ? $row->price : null
	])

	@include('admin.components.inputs.text', [
    	'name' => 'sale_price', 'label' => trans('Sale Price'), 'form_options' => [''], 'cols' => 'col-md-6 col-12',
    	'value' => isset($row->sale_price) ? $row->sale_price : null
	])

	@include('admin.components.inputs.select', [
        'name' => 'price_period',
         'label' => trans("Price Period"),
         'form_options'=> [],
         'select_options' =>  [
         \App\Domains\Bundles\Enum\PricePeriod::NONE    => 'None',
         \App\Domains\Bundles\Enum\PricePeriod::YEAR    => 'Per Year',
         \App\Domains\Bundles\Enum\PricePeriod::MONTH   => 'Per Month',
        ]
    ])

	@include('admin.components.inputs.url', ['name' => 'bundle_url', 'label' => trans('Bundle URL'), 'form_options'=> ['required'], 'cols'=> 'col-12'])

	@include('admin.components.inputs.url', ['name' => 'access_pass_url', 'label' => trans('All Access Pass URL'), 'form_options'=> ['required'], 'cols'=> 'col-12'])


	@if(!isset($row))
        @include("{$view_path}.partials.create-paymentfeatures")
    @endif

    <div id="payment-feature-multiple" class="col-12">
        @include("{$view_path}.partials.update-paymentfeatures")
    </div>


	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>

@push('script')
<script>
	$(document).ready(function(){

		$('select[name="jobs[]"]').select2({
                'multiple': true,
                'tags': true
        });

        $('select[name="topics[]"]').select2({
                'multiple': true,
                'tags': true
        });

        $('select[name="certifications[]"]').select2({
                'multiple': true,
                'tags': true
        });


    	//For access type//

        const ACCESS_TYPE_COURSES = "{{ \App\Domains\Payments\Enum\AccessType::COURSES }}";
        const ACCESS_TYPE_COURSE_CATEGORY = "{{ \App\Domains\Payments\Enum\AccessType::COURSE_CATEGORY }}";

        function setupFormInputs() {

            let val = $('#access_type_select').val();

            if (val == ACCESS_TYPE_COURSES) {
                $('.categories-input').addClass('d-none');
                $('.type-input').addClass('d-none');

                $('.courses-input').removeClass('d-none');

                $('[name="categories[]"]').prop('required', false);
                $('[name="type"]').prop('required', false);


                $('[name="courses[]"]').prop('required', true);
            } else if (val == ACCESS_TYPE_COURSE_CATEGORY) {

                $('.courses-input').addClass('d-none');
                $('.categories-input').removeClass('d-none');
                $('.type-input').removeClass('d-none');

                $('[name="courses[]"]').prop('required', false);



                $('[name="type"]').prop('required', true);
                $('[name="categories[]"]').prop('required', true);
            }

            @if(!isset($row))

            $('[name="categories[]"]').select2().select2('val', '0');
            $('[name="courses[]"]').select2().select2('val', '0');
            @endif
        }

        $('[name="access_type"]').on('change', setupFormInputs);

        setupFormInputs();



	});

	//For Payment Feature//
	$(document).on('click','[data-toggle="duplicate-input-pay"]',function(e){

		$item_selector = $(this).data('payduplicate'); // item need to duplicate
		$item = $($item_selector).last().clone(); // clone it

		let countDuplicatedPayElements = $($(this).data('payremove')).length;
		console.log("countDuplicatedPayElements", countDuplicatedPayElements)

		// empty all inputs
		$item.find('input').val('');
		$item.find('input:not([type="checkbox"]) :not([type="radio"])').val('');
		$item.find('textarea').val('');
		$item.find('input[type="checkbox"]').prop('checked',false);
		$item.find('input[type="radio"]').prop('checked',false);
		$item.find('input[type="radio"]').val(countDuplicatedPayElements);

		// target will receive the data
		$target = $(this).data('paytarget'); //get target

		// replace content of button such as icon
		$item.find(`[data-paytarget="${$target}"]`)
		.children().first()
		.replaceWith($(this).data('paytoggledata'));

		// change button functionlity to remove instead of create
		$item.find(`[data-paytarget="${$target}"]`)
		.toggleClass($(this).data('paytoggleclass'))
		.attr('data-toggle','remove-input');

		if ($($target).length == 1) {
		$($target).append($item);
		}
		else if ($($target).length > 1) {
		$(this).parents($item_selector).closest($target).append($item);
		}

	});

	$(document).on('click','[data-toggle="remove-input"]',function(e){
        $item = $(this).data('payremove');
        $(this).closest($item).remove();
    });



	//For Things you will learn feature//

	$(document).on('click','[data-toggle="duplicate-input-learn"]',function(e){

		$item_selector = $(this).data('learnduplicate'); // item need to duplicate
		$item = $($item_selector).last().clone(); // clone it

		let countDuplicatedLearnElements = $($(this).data('learnremove')).length;
		console.log("countDuplicatedLearnElements", countDuplicatedLearnElements)

		// empty all inputs
		$item.find('input').val('');
		$item.find('input:not([type="checkbox"]) :not([type="radio"])').val('');
		$item.find('textarea').val('');
		$item.find('input[type="checkbox"]').prop('checked',false);
		$item.find('input[type="radio"]').prop('checked',false);
		$item.find('input[type="radio"]').val(countDuplicatedLearnElements);

		// target will receive the data
		$target = $(this).data('learntarget'); //get target

		// replace content of button such as icon
		$item.find(`[data-learntarget="${$target}"]`)
		.children().first()
		.replaceWith($(this).data('learntoggledata'));

		// change button functionlity to remove instead of create
		$item.find(`[data-learntarget="${$target}"]`)
		.toggleClass($(this).data('learntoggleclass'))
		.attr('data-toggle','remove-input');

		if ($($target).length == 1) {
		$($target).append($item);
		}
		else if ($($target).length > 1) {
		$(this).parents($item_selector).closest($target).append($item);
		}

	});

	$(document).on('click','[data-toggle="remove-input"]',function(e){
        $item = $(this).data('learnremove');
        $(this).closest($item).remove();
    });



    ///For Bundle Spotlight Features///

    $(document).on('click','[data-toggle="duplicate-input-spot"]',function(e){

		$item_selector = $(this).data('spotduplicate'); // item need to duplicate
		$item = $($item_selector).last().clone(); // clone it

		let countDuplicatedSpotElements = $($(this).data('spotremove')).length;
		console.log("countDuplicatedSpotElements", countDuplicatedSpotElements)

		// empty all inputs
		$item.find('input').val('');
		$item.find('input:not([type="checkbox"]) :not([type="radio"])').val('');
		$item.find('textarea').val('');
		$item.find('input[type="checkbox"]').prop('checked',false);
		$item.find('input[type="radio"]').prop('checked',false);
		$item.find('input[type="radio"]').val(countDuplicatedSpotElements);

		// target will receive the data
		$target = $(this).data('spottarget'); //get target

		// replace content of button such as icon
		$item.find(`[data-spottarget="${$target}"]`)
		.children().first()
		.replaceWith($(this).data('spottoggledata'));

		// change button functionlity to remove instead of create
		$item.find(`[data-spottarget="${$target}"]`)
		.toggleClass($(this).data('spottoggleclass'))
		.attr('data-toggle','remove-input');

		if ($($target).length == 1) {
		$($target).append($item);
		}
		else if ($($target).length > 1) {
		$(this).parents($item_selector).closest($target).append($item);
		}

	});

	$(document).on('click','[data-toggle="remove-input"]',function(e){
        $item = $(this).data('spotremove');
        $(this).closest($item).remove();
    });

</script>
@endpush
