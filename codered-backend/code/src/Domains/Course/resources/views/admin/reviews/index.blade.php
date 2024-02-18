@extends('admin.components.layouts.crud.implementation.index')

@section('tabs-width', 'col-lg-12 order-lg-2')
@section('search')
    <form class="px-3">
        <div class="row align-item-end justify-content-end pr-2">

        @include('admin.components.inputs.select', [
            'name' => 'rate',
            'label' => ' ',
            'cols' => 'col-12 col-xl-3 col-md-9 col-xs-12',
            'select_options' => [
                6 => 'Select Rating',
                0 => 'less Than 1',
                1 => 'between 1 and 2 ',
                2 => 'between 2 and 3 ',
                3 => 'between 3 and 4 ',
                4 => 'between 4 and 5 ',
            ],
        ])



        @include('admin.components.inputs.text', [
            'name' => 'search',
            'label' => ' ',
            'cols' => 'col-12 col-xl-3 col-md-9 col-xs-12',
        ])
            <div class="col-lg-3 col-12 d-flex align-items-center">
                <button type="submit"
                        style="border-radius: 6px; border-color:#3DC47E"
                        class="btn btn-primary waves-effect btn-block waves-light width-sm ">Search
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </form>
@endsection
