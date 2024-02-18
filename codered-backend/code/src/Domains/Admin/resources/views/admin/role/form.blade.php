<div class="panel-body row">

	@include('admin.components.inputs.text', ['name' => 'name', 'label' => trans('admin::lang.name'), 'form_options'=> ['required'], 'cols' => 'col-12'])

	@foreach($module_roles as $index => $module)
		@if(!in_array($module->name, ["Goals", "Article Categories", "Article", "Quote", "Course Bundles", "PROMO CODE", "Lesson Report", "Summary Report Permission"]))
            <div class="col-6 mb-3">
                <div class="card h-100">
                    <div class="card-header active-tab py-3 text-white">
                        <div class="card-widgets">
                            <a data-toggle="collapse" href="#{{ $module->name }}" role="button" aria-expanded="true"
                               aria-controls="cardCollpase2" class=""><i class="mdi mdi-minus"></i></a>
                        </div>
                        @if($module->name == "Course Categories")
                            <h5 class="card-title mb-0 text-white">Content Structure</h5>
                        @elseif($module->name == "Admin")
                            <h5 class="card-title mb-0 text-white">Admin Management</h5>
                        @elseif($module->name == "Roles")
                            <h5 class="card-title mb-0 text-white">Roles Management</h5>
                        @elseif($module->name == "Payment Integration")
                            <h5 class="card-title mb-0 text-white">Payment Integrations</h5>
                        @elseif($module->name == "Payouts")
                            <h5 class="card-title mb-0 text-white">Instructor Payouts</h5>
                        @elseif($module->name == "User")
                            <h5 class="card-title mb-0 text-white">User Management</h5>
                        @elseif($module->name == "Packages And Plans")
                            <h5 class="card-title mb-0 text-white">Offers</h5>
                        @elseif($module->name == "User Activities")
                            <h5 class="card-title mb-0 text-white">Admin Activities</h5>
                        @elseif($module->name == "Learnpaths ")
                            <h5 class="card-title mb-0 text-white">Learning Paths</h5>
                        @else
                            <h5 class="card-title mb-0 text-white">{{ $module->name }}</h5>
                        @endif
                    </div>
                    <div id="{{ $module->name }}" class="collapse show" style="">
                        <div class="card-body bg-white">
                            @foreach($module->permissions as $permission)
                                <div class="checkbox checkbox-blue ">
                                    <input id="{{ $permission->id }}"
                                           {{ (isset($row) && $row->abilities->contains($permission->id)) ? 'checked' : ''}} parent-id="1"
                                           type="checkbox" name="ability[]" value="{{ $permission->id }}">
                                    @if($permission->name == "Show Plans")
                                        <label for="{{ $permission->id }}">
                                            Show Offer
                                        </label>
                                    @elseif($permission->name == "Create Plan")
                                        <label for="{{ $permission->id }}">
                                            Create Offer
                                        </label>

                                    @elseif($permission->name == "Show Learnpaths")
                                        <label for="{{ $permission->id }}">
                                            Show Learning Paths
                                        </label>
                                    @elseif($permission->name == "Create Learnpath")
                                        <label for="{{ $permission->id }}">
                                            Create Learning Paths
                                        </label>
                                    @else
                                        <label for="{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
	@endforeach
	@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>


