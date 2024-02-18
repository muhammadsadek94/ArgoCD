<div class="panel-body row">

    <div class="col-lg-8">
        <div class="row">
            @include('admin.components.inputs.text', ['name' => 'first_name', 'label' => trans('user::lang.first_name'), 'form_options'=> ['required'] , 'cols' => 'col-lg-6'])

            @include('admin.components.inputs.text', ['name' => 'last_name', 'label' => trans('user::lang.last_name'), 'form_options'=> [''], 'cols' => 'col-lg-6'])


            @include('admin.components.inputs.email', ['name' => 'email', 'label' => trans('user::lang.email'), 'form_options'=> ['required'], 'cols' => 'col-lg-6'])

            @include('admin.components.inputs.select', ['name' => 'country_id', 'label' => trans('geography::lang.country'), 'form_options'=> ['placeholder'=> 'Select Country'], 'select_options'=> $country_lists])



            @include('admin.components.inputs.select', ['name' => 'activation', 'label' => trans("user::lang.status"), 'form_options'=> ['required'], 'select_options' =>  ["1" => "Active", "0" => "Suspended" ]])
            @include('admin.components.inputs.select', [
               'name' => 'user_tag_id[]',
               'label' => 'Tags',
               'form_options'=> ['multiple'],
                'select_options' => isset($row) ? $row->usertags->pluck('name', 'id')->toArray() : [],
               'value' =>  isset($row) ? $row->usertags->pluck('id')->toArray() : null,
               'cols' => 'col-12 col-md-6'
               ])


            @if(isset($row))
                @include('admin.components.inputs.select', ['name' => 'gender', 'label' => trans('user::lang.gender'), 'form_options'=> [''], 'select_options'=> [
                    \App\Domains\User\Enum\UserGender::MALE => trans('user::lang.male'),
                    \App\Domains\User\Enum\UserGender::FEMALE => trans('user::lang.female'),
                ]])
            @endif

            @if(isset($row))
                @include('admin.components.inputs.date', ['name' => 'birth_date', 'label' => trans('user::lang.birth_date'), 'form_options'=> ['']])
            @endif



            @if(!isset($row))
                @include('admin.components.inputs.password', ['name' => 'password', 'label' => trans('user::lang.password'), 'form_options' => [(isset($row) ? '' : 'required'),'placeholder'=>'Click the button to generate a password','id'=>'psswd']])
            @endif

        </div>

        @if(!isset($row))
            <div class="form-group col-lg-6 col-12">
                <input type="button" id="generatebtn" class="btn btn-secondary " value="Generate Password">
            </div>
        @endif


    </div>

    <div class="col-lg-4">
        @include('admin.components.inputs.image', [
        'name' => 'image_id',
         'label' => trans('user::lang.image'),
         'cols' => 'col-lg-12 offset-lg-12 col-12 ',
          'value' => $row->image_id ?? null,
           'placeholder' => isset($row->image) ? asset("{$row->image->full_url}") : null])
    </div>

    @include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])

    {!! Form::hidden('type', App\Domains\User\Enum\UserType::USER) !!}
</div>


@isset($row)
    @push('form_section')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                    {!! Form:: model($row,['method'=>'PATCH','url' => ["{$route}/action", $row->id, "phone"], 'files'=>true,'data-toggle'=> 'ajax']) !!}
                    <div class="panel-body row">
                        @include('admin.components.inputs.phone', ['name' => 'phone', 'label' => trans('user::lang.phone'), 'form_options'=> ['required'], 'cols' => 'col-12 px-0'])
                    </div>
                    @include('admin.components.inputs.success-btn', ['button_text' => $submitButton, 'button_extra_class' => 'float-right'])
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
                        {!! Form:: model($row,['method'=>'PATCH','url' => ["{$route}/action", $row->id, "password"], 'files'=>true,'data-toggle'=> 'ajax']) !!}
                        <div class="panel-body row">
                            {{--                            @if((empty($row->social_type) && empty($row->social_id)))--}}
                            @include('admin.components.inputs.password', ['name' => 'password', 'label' => trans('user::lang.password'), 'form_options' => [(isset($row) ? '' : 'required')]])
                            <div class="form-group col-lg-6 col-12 d-flex align-items-end">
                                <input type="button" id="generatebtn" class="btn btn-secondary "
                                       value="Generate Password">
                            </div>
                            @include('admin.components.inputs.success-btn', ['button_text' => trans('user::lang.update_password'), 'button_extra_class' => 'float-right'])
                            {{--                            @else--}}
                            {{--                                <div class="alert alert-info col-12">--}}
                            {{--                                    <p>@lang('user::lang.update_password_forbidden_due_social_media_registration') </p>--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}

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
        @include("{$view_path}.subscriptions")
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


            $('select[name="user_tag_id[]"]').select2({
                /*'multiple': true,
               'tags': true,*/
                ajax: {
                    url: `{{ url(Constants::ADMIN_BASE_URL) }}/user-tag/actions/get-tags`,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            id: $('[name="user_tag_id"]').val()
                        }

                        return query;
                    },
                    processResults: function (data) {
                        //console.log(data);
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data
                        };
                    }
                }
            });
        });
    </script>
@endpush
