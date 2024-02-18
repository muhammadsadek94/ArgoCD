@extends('admin.components.layouts.crud.implementation.index')





@section('tabs-width', 'col-lg-12 order-lg-2')
@section('search')
    <div class="col-12 pr-2">
        <form class="">
            <div class="row align-item-end justify-content-end pr-2">

                <div class="col-lg-2 col-12">
                    @include('admin.components.inputs.select', [
                        'name' => 'type[]',
                        'label' => ' Select Multiple filters',
                        'cols' => '',
                        'form_options' => ['multiple'],
                        'select_options' => [
                            1 => "Is Editorial Pick",
                            2 => "Is Best Seller",
                            3 => "Is Featured",

                        ],
                    ])
                </div>

                <div class="col-lg-2 col-12">
                    @include('admin.components.inputs.select', [
                        'name' => 'course_sub_category_id',
                        'label' => ' Category ',
                        'form_options' => ['placeholder' => 'Select Category '],
                        'cols' => '',
                        'select_options' => $sub_categories_list,
                    ])
                </div>

                <div class="col-lg-2 col-12">
                    @include('admin.components.inputs.select', [
                        'name' => 'activation',
                        'label' => ' Status ',
                        'cols' => '',
                        'value' => App\Domains\Course\Enum\CourseActivationStatus::ACTIVE,
                        'select_options' => App\Domains\Course\Enum\CourseActivationStatus::getActivationList(),
                    ])
                </div>


                <div class="col-lg-2 col-12">
                    @include('admin.components.inputs.text', [
                        'name' => 'search',
                        'label' => ' Search ',
                        'form_options' => ['placeholder' => 'Search by Course Name'],
                        'cols' => 'col-12 col-xl-12 col-md-12 col-xs-12',
                    ])
                </div>

                <div class="col-lg-2 col-12 d-flex align-items-center">
                    <button type="submit"
                            style="border-radius: 6px; border-color:#3DC47E"
                            class="btn btn-primary waves-effect btn-block waves-light width-sm ">Search
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
