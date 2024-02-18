<div class="panel-body row">
    @include('admin.components.inputs.image', [
        'name' => 'image_id',
        'label' => trans('user::lang.image'),
        'cols' => 'col-lg-4 offset-lg-4 col-12 ',
        'value' => $row->image_id ?? null,
        'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null,
    ])
    <div class="col-12"></div>
    <div class="row col-12">
        @include('admin.components.inputs.text', [
            'name' => 'first_name',
            'label' => trans('Full Name'),
            'form_options' => ['required'],
        ])
        {{--	@include('admin.components.inputs.text', ['name' => 'last_name', 'label' => trans('user::lang.last_name'), 'form_options'=> ['required']]) --}}

        @include('admin.components.inputs.email', [
            'name' => 'email',
            'label' => trans('user::lang.email'),
            'form_options' => ['required'],
        ])
    </div>
    <div class="row col-12">
        @if (!isset($row))
            @include('admin.components.inputs.phone', [
                'name' => 'phone',
                'label' => trans('user::lang.phone'),
                'form_options' => ['class' => 'col-12'],
            ])
        @endif

        @if (isset($row))
            @include('admin.components.inputs.text', [
                'name' => 'job',
                'label' => trans('user::lang.job'),
                'form_options' => ['class' => 'col-12'],
                'value' => isset($row->instructor_profile) ? $row->instructor_profile->job : null,
            ])
        @endif


        @include('admin.components.inputs.select', [
            'name' => 'activation',
            'label' => trans('user::lang.status'),
            'form_options' => ['required'],
            'select_options' => ['1' => 'Active', '0' => 'Suspended'],
        ])
    </div>

    <div class="row col-12">
        @if (!isset($row))
            @include('admin.components.inputs.password', [
                'name' => 'password',
                'label' => trans('user::lang.password'),
                'form_options' => [
                    isset($row) ? '' : 'required',
                    'placeholder' => 'Click the button to generate a password',
                    'id' => 'psswd',
                ],
            ])
        @endif
    </div>
    @if (!isset($row))
        <div class="form-group col-lg-6 col-12">
            <input type="button" id="generatebtn" class="btn btn-secondary " value="Generate Password">
        </div>
    @endif

    <div class="col-12"></div>

    <h2>Profile Information</h2>
    <div class="col-12"></div>

    @include('admin.components.inputs.textarea', [
        'name' => 'profile_summary',
        'label' => trans('Profile Summary'),
        'form_options' => [''],
        'cols' => 'col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->profile_summary : null,
    ])
    <div class="col-12"></div>

    <h2>Social Media</h2>
    <div class="col-12"></div>
    @include('admin.components.inputs.url', [
        'name' => 'facebook_url',
        'label' => trans('Facebook url'),
        'form_options' => [''],
        'cols' => 'col-md-4 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->facebook_url : null,
    ])
    @include('admin.components.inputs.url', [
        'name' => 'twitter_url',
        'label' => trans('Twitter url'),
        'form_options' => [''],
        'cols' => 'col-md-4 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->twitter_url : null,
    ])
    @include('admin.components.inputs.url', [
        'name' => 'instagram_url',
        'label' => trans('Instagram url'),
        'form_options' => [''],
        'cols' => 'col-md-4 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->instagram_url : null,
    ])
    @include('admin.components.inputs.url', [
        'name' => 'github_url',
        'label' => trans('Github url'),
        'form_options' => [''],
        'cols' => 'col-md-4 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->github_url : null,
    ])
    @include('admin.components.inputs.url', [
        'name' => 'linkedin_url',
        'label' => trans('Linkedin url'),
        'form_options' => [''],
        'cols' => 'col-md-4 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->linkedin_url : null,
    ])
    @include('admin.components.inputs.url', [
        'name' => 'blog_url',
        'label' => trans('Blog url'),
        'form_options' => [''],
        'cols' => 'col-md-4 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->blog_url : null,
    ])
    @include('admin.components.inputs.url', [
        'name' => 'article_url',
        'label' => trans('Article url'),
        'form_options' => [''],
        'cols' => 'col-md-4 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->article_url : null,
    ])




    <div class="col-12"></div>

    <h2>Payout Details</h2>
    <div class="col-12"></div>
    @include('admin.components.inputs.text', [
        'name' => 'bank_name',
        'label' => trans('Bank Name'),
        'form_options' => [''],
        'cols' => 'col-md-3 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->bank_name : null,
    ])
    @include('admin.components.inputs.text', [
        'name' => 'account_number',
        'label' => trans('Account number'),
        'form_options' => [''],
        'cols' => 'col-md-3 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->account_number : null,
    ])

    @include('admin.components.inputs.text', [
        'name' => 'swift_code',
        'label' => trans('Swift code'),
        'form_options' => [''],
        'cols' => 'col-md-3 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->swift_code : null,
    ])

    @include('admin.components.inputs.text', [
        'name' => 'iban',
        'label' => trans('Iban'),
        'form_options' => [''],
        'cols' => 'col-md-3 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->iban : null,
    ])

    @include('admin.components.inputs.text', [
        'name' => 'billing_address',
        'label' => trans('Billing Address'),
        'form_options' => [''],
        'cols' => 'col-md-3 col-12',
        'value' => isset($row->instructor_profile) ? $row->instructor_profile->billing_address : null,
    ])





    @include('admin.components.inputs.success-btn', [
        'button_text' => $submitButton,
        'button_extra_class' => 'float-right',
    ])

    {!! Form::hidden('type', App\Domains\User\Enum\UserType::PROVIDER) !!}
</div>


@isset($row)
    @push('form_section')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        {!! Form::model($row, [
                            'method' => 'PATCH',
                            'url' => ["{$route}/action", $row->id, 'phone'],
                            'files' => true,
                            'data-toggle' => 'ajax',
                        ]) !!}
                        <div class="panel-body row">
                            @include('admin.components.inputs.phone', [
                                'name' => 'phone',
                                'label' => trans('user::lang.phone'),
                                'form_options' => ['required'],
                            ])
                            @include('admin.components.inputs.success-btn', [
                                'button_text' => trans('user::lang.update_phone'),
                                'button_extra_class' => 'float-right',
                            ])
                        </div>
                        {!! Form::close() !!}
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    @endpush
@endisset

@isset($row)
    @push('form_section')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        {!! Form::model($row, [
                            'method' => 'PATCH',
                            'url' => ["{$route}/action", $row->id, 'password'],
                            'files' => true,
                            'data-toggle' => 'ajax',
                        ]) !!}
                        <div class="panel-body row">
                            @if (empty($row->social_type) && empty($row->social_id))
                                @include('admin.components.inputs.password', [
                                    'name' => 'password',
                                    'label' => trans('user::lang.password'),
                                    'form_options' => [isset($row) ? '' : 'required'],
                                ])
                                <div class="col-12"></div>
                                <div class="col-12">
                                    <div class="col-2 float-right">
                                        <a href="{{ url("{$route}/action/{$row->id}/generate-password") }}"
                                            onclick="return confirm('Do you wanna to generate new password and send to instructor email?')"
                                            class="btn btn-primary float-right btn-block">
                                            Generate Password
                                        </a>
                                    </div>
                                    @include('admin.components.inputs.success-btn', [
                                        'button_text' => trans('user::lang.update_password'),
                                        'button_extra_class' => 'float-right',
                                    ])

                                </div>
                            @else
                                <div class="alert alert-info col-12">
                                    <p>@lang('user::lang.update_password_forbidden_due_social_media_registration') </p>
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
        $(document).ready(function() {
            let fetchCities = function() {
                let country_id = $('[name="country_id"]').val();
                let selected_city_id = '{{ isset($row) ? $row->city_id : null }}'
                $.ajax({
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/user/action/${country_id}/cities`,
                    type: 'GET',
                    dataType: 'html',
                    data: {
                        country_id: country_id,
                        selected_city_id: selected_city_id,
                    },
                    success: function(data) {
                        $('#select-container-city_id').html(data);
                    },
                    error: function(e) {
                        // console.log('e', e);
                    }
                });
                return false;
            };
            fetchCities();
            $('[name="country_id"]').change(fetchCities);


            //generating random password//
            $("#generatebtn").click(function() {
                var rand_psswd = randomPassword(10);
                $('#psswd').val(rand_psswd);
            });

            function randomPassword(length) {
                var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
                var pass = "";
                for (var x = 0; x < length; x++) {
                    var i = Math.floor(Math.random() * chars.length);
                    pass += chars.charAt(i);
                }
                return pass;
            }

        });
    </script>
@endpush
