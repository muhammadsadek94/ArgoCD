
@if(session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
@endif
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <hr>
                <div class="row mb-2">
                    <h2>Product Access</h2>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Expires at</th>
                            <th>Product name</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($row->subscriptions()->latest()->get() as $subscription)
                            <tr>
                                <td>{{ $subscription->subscription_id ?? 'None' }}</td>
                                <td>{{ $subscription->expired_at }}</td>
                                <td>
                                    {{ $subscription->package->name ?? 'N\A' }}
                                </td>
                                <td>
                                    {{ $subscription->created_at ?? 'N\A' }}
                                </td>
                                <td>
                                    @if($subscription->status == \App\Domains\User\Enum\SubscribeStatus::ACTIVE)
                                        Active
                                    @elseif($subscription->status == \App\Domains\User\Enum\SubscribeStatus::ENDED)
                                        Ended
                                    @elseif($subscription->status == \App\Domains\User\Enum\SubscribeStatus::TRIAL)
                                        Trial
                                    @endif
                                </td>
                               <td>
                                    @include('user::admin.user.bundle.edit', [
                                        'subscription' => $subscription
                                    ])

                                    {!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'bundle', $subscription->id], 'class' => 'form-horizontal']) !!}
                                    {!! Form::hidden('id', $subscription->id) !!}

                                    <button type="submit" class="btn btn-delete  "
                                            onclick="return confirm('Confirm Delete operation ?');">
                                        <i class="fa fa-trash"></i> @lang('lang.delete')
                                    </button>

                                    {!! Form::close() !!}


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="30">
                                    <p class="text-center">
                                        No Subscriptions
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="30">
                                <h3>Grant Access</h3>
                                {!! Form::open(['url' => "{$route}/action/{$row->id}/subscription"]) !!}
                                <div class="row">
                                    @include('admin.components.inputs.text', [
                                           'name'           => 'subscription_id',
                                           'label'          => 'Order ID',
                                           'form_options'   => ['placeholder' => 'Add custom Subscription Id', ],
                                       ])

                                    @include('admin.components.inputs.select', [
                                         'name'           => 'package_id',
                                         'label'          => 'Product',
                                         'form_options'   => ['placeholder' => 'Select Product Name', 'required'],
                                         'select_options' => $package_subscriptions_list,
                                     ])
                                    @include('admin.components.inputs.select', [
                                         'name'           => 'status',
                                         'label'          => 'Access type',
                                         'form_options'   => ['placeholder' => 'Select Access Type', 'required'],
                                         'select_options' => [\App\Domains\User\Enum\SubscribeStatus::ACTIVE => 'Active', \App\Domains\User\Enum\SubscribeStatus::TRIAL => 'Trial', ],
                                     ])

                                    @include('admin.components.inputs.date', [
                                        'name'        => 'expired_at',
                                        'label'       => trans('Expires at'),
                                        'form_options'=> ['required']
                                     ])

                                </div>
                                <div class="row">
                                    @include('admin.components.inputs.success-btn', ['button_text' => 'Confirm', 'button_extra_class' => 'float-right'])
                                </div>

                                {!! Form::close() !!}
                            </td>
                        </tr>
                        </tfoot>

                    </table>


                </div>

                <hr>

                {{-- <div class="row mb-2">
                    <h2>Microdegree/Certification Access</h2>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Microdegree/Certification Name</th>
                            <th>Expires at</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($row->microdegree_certifications_enrollments()->latest()->get() as $microdegree)
                            <tr>
                                <td>{{ $microdegree->name ?? 'Microdegree not found' }}</td>
                                <td>{{ $microdegree->pivot->expired_at }}</td>
                                <td>
                                    <a href="{{ url($admin_base_url . '/actions/microdegree/' . $microdegree->id . '/user/'. $row->id .'/'. $microdegree->pivot->id) }}" data-toggle="tooltip" data-placement="top" title="Delete" type="submit"  class="btn btn-icon btn-sm mr-1"
                                            onclick="return confirm(' Confirm Delete operation ?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="30">
                                    <p class="text-center">
                                        No Microdegrees enrollments
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="30">
                                <h3>Grant Microdegree/Certification Access</h3>
                                {!! Form::open(['url' => "{$route}/action/{$row->id}/enroll-microdegree"]) !!}
                               <div class="row">
                                   @include('admin.components.inputs.select', [
                                   'name' => 'course_id',
                                   'label' => trans('Microdegrees/Certifications'),
                                    'form_options'=> ['required', 'placeholder' => 'Select'],
                                    'select_options' => $micro_degrees
                                ])

                                   @include('admin.components.inputs.date', [
                                       'name'=>'expired_at',
                                       'form_options' => ['style' => 'width:100%'],
                                       'label' =>'Expiry Date',
                                       'cols' => 'col-12 col-xl-6 col-md-6 col-xs-12'
                                   ])
                               </div>
                                <div class="row">
                                    @include('admin.components.inputs.success-btn', ['button_text' => 'Grant Access', 'button_extra_class' => 'float-right'])
                                </div>

                                {!! Form::close() !!}
                            </td>
                        </tr>
                        </tfoot>

                    </table>


                </div> --}}

                <hr>

                 <div class="row mb-2">
                    <h2>Enrolled Courses</h2>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Course Enrolled</th>
                            <th>Created  at</th>
                            <th>Completion Rate</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($enrolled_courses_with_percentage as $course_enrol)
                                <tr>
                                    <td>{{ $course_enrol['course_name'] }}</td>
                                    <td>{{ $course_enrol['created_at'] }}</td>
                                    <td>
                                        {{ $course_enrol['percentage'] }} %
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="30">
                                        <p class="text-center">
                                            No Enrolled Courses
                                        </p>
                                    </td>
                                </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row mb-2">
                    <h2>Course Completion Details with Certificates</h2>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Created  at</th>
                            <th>Certificate</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($row->completed_courses()->latest()->get() as $comp_courses)
                            <tr>
                                <td>{{ $comp_courses->course->name ?? 'Course Deleted' }}</td>
                                <td>{{ $comp_courses->created_at}}</td>

                                <td>
                                    @if($comp_courses->certificate)
                                        <a href="{{ $comp_courses->certificate->full_url }}"  target="_blank">View Certificate</a>
                                    @else
                                        Certificate not available
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url($admin_base_url . '/actions/course-certificate/' . $comp_courses->id) }}" data-toggle="tooltip" data-placement="top" title="Delete" type="submit"  class="btn btn-icon btn-sm mr-1"
                                            onclick="return confirm(' Confirm Delete operation ?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="30">
                                    <p class="text-center">
                                        No Course Completion
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="30">
                                <h3>Generate Completion Certificate</h3>
                                {!! Form::open(['url' => "{$route}/action/{$row->id}/generate-certificate"]) !!}
                                 <div class="row col-12">
                                     @include('admin.components.inputs.select', [
                                    'name' => 'course_id',
                                    'label' => trans('Courses'),
                                     'form_options'=> ['required'],
                                     'select_options' => $all_courses
                                     ])
{{--                                    @include('admin.components.inputs.text', ['name' => 'certificate_number', 'label' => trans('user::lang.certificate_number'), 'form_options'=> ['']])--}}

                                </div>

                                <div class="row">
                                    @include('admin.components.inputs.success-btn', ['button_text' => 'Generate Certificate of Completion', 'button_extra_class' => 'float-right'])
                                </div>

                                {!! Form::close() !!}
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- start assignment completed -->

                <hr>

                <div class="row mb-2">
                    <h2>Microdegree/Certification Assessment</h2>
                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Degree</th>

                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actions</th>

                        </tr>
                        </thead>
                        <tbody>

                        @forelse($row->finalAssignments()->get() as $assignment)
                            <tr>
                                <td>{{ $assignment->course->name ?? 'Course Deleted' }}</td>
                                @php
                                $completeCourse = \App\Domains\Course\Models\CompletedCourses::where(['user_id' => $row->id, 'course_id' => $assignment->course_id ])->first();
                                @endphp
                                <td>{{ $completeCourse ? $completeCourse->degree : 'not complete course' }}</td>

                                <td>
                                   {{$assignment->started_at}}
                                </td>
                                <td>
                                    {{$assignment->ended_at}}
                                 </td>
                                 <td>
                                    <a onclick="return confirm('Confirm Delete operation ? This will allow user to re-take the assignment one more time and it will delete the current certificate if exist. ');" class="btn btn-danger" href="{{route('re-assign.assignment', ['userId' => $row->id, 'courseId' =>$assignment->course_id ])}}">Delete</a>
                                 </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="30">
                                    <p class="text-center">
                                        No Assignment Completed
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                </div>
                <!-- end assignment completed -->


            </div>
        </div>
    </div>
</div>
