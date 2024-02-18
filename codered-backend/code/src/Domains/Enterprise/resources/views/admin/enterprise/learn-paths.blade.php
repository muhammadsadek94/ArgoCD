<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <hr>
                <div class=" mb-2">
                    <h2>Packages</h2>

                    <table class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Package name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($learnPaths as $learnPath)
                            <tr>
                                <td>
                                    {{ $learnPath->package->name ?? 'N\A' }}
                                </td>
                                <td>
                                    @if($learnPath->activation == \App\Domains\User\Enum\SubscribeStatus::ACTIVE)
                                        Active
                                    @elseif($learnPath->activation == \App\Domains\User\Enum\SubscribeStatus::ENDED)
                                        Ended
                                    @elseif($learnPath->activation == \App\Domains\User\Enum\SubscribeStatus::TRIAL)
                                        Trial
                                    @endif
                                </td>
                                <td>

                                    {!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'learnPath', $learnPath->id], 'class' => 'form-horizontal','data-toggle'=> 'ajax' , 'data-refresh-page'=>'true']) !!}
                                    {!! Form::hidden('id', $learnPath->id) !!}

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
                                        No Learning paths
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>

                        {{-- <tfoot>
                        <tr>
                            <td colspan="30">
                                <h3>Grant Access</h3>
                                {!! Form::open(['url' => "{$route}/action/{$row->id}/subscription"]) !!}
                                <div class="row">
                                    @include('admin.components.inputs.text', [
                                       'name'           => 'subscription_id',
                                       'label'          => 'Subscription  Id',
                                       'form_options'   => ['placeholder' => 'Add custom Subscription Id', ],
                                   ])

                                    @include('admin.components.inputs.select', [
                                     'name'           => 'package_id',
                                     'label'          => 'Access Package',
                                     'form_options'   => ['placeholder' => 'Select Subscription Package', 'required'],
                                     'select_options' => $package_subscriptions_list,
                                 ])
                                    @include('admin.components.inputs.date', [
                                        'name'        => 'expired_at',
                                        'label'       => trans('Expires at'),
                                        'form_options'=> ['required']
                                     ])

                                </div>
                                @include('admin.components.inputs.success-btn', ['button_text' => 'Confirm', 'button_extra_class' => 'float-right'])

                                {!! Form::close() !!}
                            </td>
                        </tr>
                        </tfoot> --}}
                    </table>
                        {!! Form:: model($row,['method'=>'PATCH','url' => ["{$route}/action", $row->id, "UpdateLearnPath"], 'files'=>true,'data-toggle'=> 'ajax' , 'data-refresh-page'=>'true']) !!}
                        <div class="panel-body row w-100">

                        <div class="col-12  px-2 " id="pathss">
                            <div class="form-group col-12">

                            @include('admin.components.inputs.select', [
                               'name' => 'subscription[]',
                               'label' => 'Packages',
                               'form_options'=> ['multiple'],
                                'select_options' => $package_subscriptions_list,
                               'cols' => ''
                               ])
                            </div>
                            @include('admin.components.inputs.success-btn', ['button_text' => trans('Add'), 'button_extra_class' => 'float-right'])
                        </div>

                            {!! Form::close() !!}

                        </div>
                </div>

                <hr>
            </div>
        </div>
    </div>
</div>

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
            if ({{ $row->type}} == 5) {
                $("#paths").css('display', 'block');

            } else {

                $("#paths").css('display', 'none');
            }

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
