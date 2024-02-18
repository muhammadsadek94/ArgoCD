<div class="panel-body row">


	 <div class="form-group col-lg-12 col-12">
		 <label for="title">Subscription ID</label>
	     {{Form::text("subscription_id", $row->subscription_id, ["class" => "form-control", "id" => "subscription_id", "disabled"] )}}
	</div>

	{{--Form::text("subscription_id", $row->subscription_id, ["class" => "form-control", "id" => "subscription_id", "readonly"] )--}}


	@include('admin.components.inputs.select', [
        'name' => 'package_id',
        'label' => 'Package Name',
        'form_options' => ['placeholder' => 'Select Package'],
        'select_options' => $package_subscriptions_list,
        'cols' => 'col-12'
    ])

    @include('admin.components.inputs.select', [
         'name'           => 'status',
         'label'          => 'Access type',
         'form_options'   => ['placeholder' => 'Select Access Type', 'required','id' => uniqid(),],
         'cols' => 'col-12',
         'select_options' => [
             \App\Domains\User\Enum\SubscribeStatus::ACTIVE => 'Active',
             \App\Domains\User\Enum\SubscribeStatus::TRIAL => 'Trial',
          ],
     ])


    @include('admin.components.inputs.number', [
         'name'           => 'paid_installment_count',
         'label'          => 'Paid Installment Count',
         'cols' => 'col-12',
         'form_options'   => ['placeholder' => 'Paid Installment Count'],
     ])



	@include('admin.components.inputs.date', [
            'name' => 'expired_at',
            'label' => trans('Expires at'),
             'form_options'=> ['required'],
             'cols' => 'col-12'
    ])


	{{--@include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("user::lang.status"), 'form_options'=> ['required'], 'cols' => 'col-12','select_options' =>  ["1" => "Active", "0" => "Suspended" ]])--}}


</div>
