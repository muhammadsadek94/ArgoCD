<div class="panel-body row">

    <div class="col-12"></div>
    <div class="row col-12">
        @include('admin.components.inputs.text', ['name' => 'first_name', 'label' => trans('enterprise::lang.enterprise_name'), 'form_options'=> ['required']])

        @include('admin.components.inputs.email', ['name' => 'email', 'label' => trans('enterprise::lang.email'), 'form_options'=> ['required']])

    </div>

    <div class="row col-12">
        <input type="hidden" name="type" value="{{ \App\Domains\User\Enum\UserType::PRO_ENTERPRISE_ADMIN }}">
        @include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("enterprise::lang.status"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Active", "0" => "Suspended" ]])
    </div>

    @if(!isset($row))
        @include('admin.components.inputs.text', ['name' => 'License_number_active', 'label' => trans(" Number of Licenses"), 'form_options'=> [''], ])
    @endif

    @include('admin.components.inputs.text', ['name' => 'retake_licenses', 'label' => trans("Re-licensing Limit"), 'form_options'=> ['required'],'value'=> isset($row->enterpriseInfo) ? $row->enterpriseInfo->licenses_reuse_number : 1 ])

    <div class="row col-12">
        @if(!isset($row))


            @include('admin.components.inputs.select', [
                'name' => 'subscription[]',
                'label' => 'Packages',
                'form_options'=> ['multiple'],
                 'select_options' => $package_subscriptions_list,
                'cols' => 'col-12'
                ])
            @include('admin.components.inputs.password', ['name' => 'password', 'label' => trans('enterprise::lang.password'), 'form_options' => [(isset($row) ? '' : 'required'),'placeholder'=>'Click the button to generate a password','id'=>'psswd']])
            <div class="form-group col-lg-6 col-12 d-flex">
                <input type="button" id="generatebtn" class="btn btn-secondary mt-auto"
                       value="Generate Password">
            </div>
            <div class="col-12 col-md-6 px-2 " id="paths">


            </div>
            {{-- <div class="col-12 col-md-6 px-2 "  id="paths">
               @include('admin.components.inputs.select', [
                  'name' => 'subscription[]',
                  'label' => 'Learning path',
                  'form_options'=> ['multiple'],
                   'select_options' => isset($row) ? $row->learnoath->pluck('name', 'id')->toArray() : $package_subscriptions_list,
                  'value' =>  isset($row) ? $row->usertags->pluck('id')->toArray() : null,
                  'cols' => ''
                  ])

            </div> --}}
    </div>
    @endif
</div>


@include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

</div>


@if(isset($row))
    @push('form_section')
        @include("{$view_path}.learn-paths")
    @endpush
@endif
@if(isset($row))


    @push('form_section')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">
                        <h2>Users</h2>
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>name</th>
                                <th>Enrolled Courses</th>
                                <th>Completed Courses</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->all_course_enrollments()->count() }}</td>
                                    <td>{{ $user->completed_courses()->count() }}</td>
                                    <td>
                                        @permitted(\App\Domains\User\Rules\UserPermission::USER_SHOW)
                                            <a data-toggle="tooltip" data-placement="top" title="View" href="{{ url("/admin/user/{$user->id}") }}" target="_blank" class="btn btn-icon btn-sm mr-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endpermitted
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <ul class="pagination pagination-rounded justify-content-end my-2">
                            {{  $users->appends(request()->all() ??[])->links('pagination::bootstrap-4') }}
                        </ul>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    @endpush
@endif



{{-- subaccounts --}}

@if(isset($row))


    @push('form_section')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">
                        <h2>Subaccounts</h2>
                        <table class="table table-hover table-bordered">
                            <thead>
                            <tr>
                                <th>name</th>
                                <th>Department name</th>
                                <th>Licenses</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($subaccounts as $subaccount)
                                <tr>
                                    <td id="{{$subaccount->id}}">{{ $subaccount->full_name }}</td>
                                    <td id="{{$subaccount->id}}">{{ $subaccount->company_name }}</td>
                                    <td>
                                        {!! Form:: model($row,['method'=>'PATCH','url' => ["{$route}/action", $row->id, "subaccount-license"], 'files'=>true,'data-toggle'=> 'ajax']) !!}
                                        <div class="panel-body ">
                                            <div class="row">
                                                @php
                                                    $subAccountAvailableLicensesCount =  App\Domains\Enterprise\Models\License::where('enterprise_id', $row->id)
                                                            ->where('user_id', '=', null)
                                                            ->where('subaccount_id', '=', $subaccount->id)
                                                            ->where('license_type', '=', App\Domains\Enterprise\Enum\LicneseType::PREMIUM)
                                                            ->where('activation', '=', 1)->count();
                                                @endphp
                                                <div class="col-6">
                                                    <input type="hidden" name="subaccount_id" value="{{ $subaccount->id }}">
                                                    @include('admin.components.inputs.text', ['name' => 'number_licenses', 'label' => trans("Remaining Licenses"), 'cols'=>'col-12 mt-3', 'form_options'=> ['required'],'value'=> $subAccountAvailableLicensesCount ])

                                                </div>


                                            </div>
                                        </div>
                                        @include('admin.components.inputs.success-btn', ['button_text' => trans('Update Licenses'), 'button_extra_class' => 'float-right'])

                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    @endpush
@endif

@if(isset($row))
    @push('form_section')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body">
                        {!! Form:: model($row,['method'=>'PATCH','url' => ["{$route}/action", $row->id, "license"], 'files'=>true,'data-toggle'=> 'ajax']) !!}
                        <div class="panel-body ">
                            <div class="row">

                                <div class="col-6">
                                    @include('admin.components.inputs.card', [
                                        'icon_class' => 'ti-user text-white bg-primary',
                                        'value' =>count($used_license_active),
                                        'label' => 'Total Active Licenses',
                                        'bg_color' => 'bg-white',
                                        'text_color' => 'darkblue',
                                        'size' => 'fa-3x'
                                    ])
                                    @include('admin.components.inputs.text', ['name' => 'unused_license_active', 'label' => trans("Remaining Licenses"), 'cols'=>'col-12 mt-3', 'form_options'=> ['required'],'value'=> count($unused_license_active) ])

                                </div>


                            </div>
                        </div>
                        @include('admin.components.inputs.success-btn', ['button_text' => trans('Update Licenses'), 'button_extra_class' => 'float-right'])

                        {!! Form::close() !!}
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    @endpush
@endif
@isset($row)
    @push('form_section')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form:: model($row,['method'=>'PATCH','url' => ["{$route}/action", $row->id, "password"], 'files'=>true,'data-toggle'=> 'ajax']) !!}
                        <div class="panel-body row">
                            @if((empty($row->social_type) && empty($row->social_id)))
                                @include('admin.components.inputs.password', ['name' => 'password', 'label' => trans('enterprise::lang.password'), 'form_options' => [(isset($row) ? '' : 'required')]])
                                <div class="form-group col-lg-6 col-12 d-flex">
                                    <input type="button" id="generatebtn" class="btn btn-secondary mt-auto"
                                           value="Generate Password">
                                </div>
                                @include('admin.components.inputs.success-btn', ['button_text' => trans('enterprise::lang.update_password'), 'button_extra_class' => 'float-right'])
                            @else
                                <div class="alert alert-info col-12">
                                    <p>@lang('enterprise::lang.update_password_forbidden_due_social_media_registration') </p>
                                </div>
                            @endif

                        </div>
                        {!! Form::close() !!}
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    @endpush
@endisset

@push('script')
    <script>

        var specials = '!$#%';
        var lowercase = 'abcdefghijklmnopqrstuvwxyz';
        var uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var numbers = '0123456789';

        var all = specials + lowercase + uppercase + numbers;

        String.prototype.pick = function (min, max) {
            var n, chars = '';

            if (typeof max === 'undefined') {
                n = min;
            } else {
                n = min + Math.floor(Math.random() * (max - min));
            }

            for (var i = 0; i < n; i++) {
                chars += this.charAt(Math.floor(Math.random() * this.length));
            }

            return chars;
        };

        String.prototype.shuffle = function () {
            var array = this.split('');
            var tmp, current, top = array.length;

            if (top) while (--top) {
                current = Math.floor(Math.random() * (top + 1));
                tmp = array[current];
                array[current] = array[top];
                array[top] = tmp;
            }

            return array.join('');
        };

        $(document).ready(function () {

            //generating random password//
            $("#generatebtn").click(function () {

                var password = (specials.pick(2) + lowercase.pick(2) + uppercase.pick(2) + numbers.pick(2) + all.pick(5, 10)).shuffle();

                $('[name="password"]').val(password);

            });


        });
    </script>
@endpush



@push('script')
    <script>
        jQuery(document).ready(function () {
            $("#paths").css('display', 'none');
            $("#type").change(function () {
                if ($(this).val() == 5) {

                    $("#paths").css('display', 'block');

                } else {
                    $("#paths").css('display', 'none');
                }
            });
        });


    </script>
@endpush


@push('script')
    <script>
        jQuery(document).ready(function () {


            $('.select2').on("select2:select", function (e) {
                var optionText = e.params.data.text;
                var notSelectedLength = $(this).find('option:not(:selected)').length
                if(optionText=='Select All'){
                    if(notSelectedLength == 0){
                        $(".select2 > option").prop("selected",false);
                    }
                    else{
                        $(".select2 > option").prop("selected","selected");
                        $('.select2 option[value="all"]').prop('selected', false);
                    }

                    $(".select2").trigger("change");

                }
            });

        });


    </script>
@endpush

