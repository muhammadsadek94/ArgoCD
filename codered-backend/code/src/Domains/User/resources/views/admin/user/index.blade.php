@extends('admin.components.layouts.crud.implementation.index')

@section('tabs')
    @include ("{$view_path}.navtabs")
@endsection

@section('tabs-width', 'col-lg-7')
@section('search')

    <form class="px-3">
      <div class="row align-item-end justify-content-end pr-2">
        <div class="col-lg-8">
            @include('admin.components.inputs.text-placeholder', [
                'name'=>'search',
                'form_options' => ['style' => 'width:100%'],
                'label' =>'',
                'cols' => ' col-12 px-0  align-items-center d-flex'
            ])
        </div>
{{--        <div class="col-lg-3">--}}
{{--            @include('admin.components.inputs.select', [--}}
{{--            'name'=>'country_id',--}}
{{--            'label' => ' ',--}}
{{--            'form_options' => ['placeholder'=>'Select country', 'style' => 'width:100%'],--}}
{{--            'select_options' => $country_lists,--}}
{{--            'cols' => ' col-12 px-0  align-items-center d-flex'--}}

{{--            ])--}}
{{--        </div>--}}

        <div class="col-lg-2">
            <button type="submit"
                style="border-radius: 6px; border-color:#3DC47E"
                class="btn btn-primary waves-effect btn-block waves-light width-sm ">Search
                <i class="fa fa-search"></i>
            </button>
        </div>

      </div>
    </form>

@endsection

@section('call-to-actions')
    <div class="d-flex justify-content-end">
        <div class="text-right">
            @if(isset($permitted_actions) && ($permitted_actions['create'] == null || is_permitted($permitted_actions['create'])))
                <a href="{{ url("admin/user-tag") }}"
                style="margin: 20px 0 0 0; border-radius: 6px; border-color:#E83C30" class="btn btn-secondary waves-effect px-4 waves-light mb-2 mr-2">
                    User Tags
                </a>
            @endif
        </div>
        <div class="text-right">
            @if(isset($permitted_actions) && ($permitted_actions['create'] == null || is_permitted($permitted_actions['create'])))
                <a href="{{ url("{$route}/create") }}"
                style="margin: 20px 0 0 0; border-radius: 6px; border-color:#E83C30" class="btn btn-primary waves-effect px-4 waves-light mb-2 mr-2">
                    @lang('lang.create')
                </a>
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('select[name="user_tag_id[]"]').select2({
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
