@extends('admin.components.layouts.crud.implementation.index')


@section('search')
    <form class="px-3">
        <div class="row align-item-end justify-content-end pr-2">
            <div class="col-lg-3">
            @include('admin.components.inputs.select', ['name'=>'typesearch',
            'label' =>' ',
            'cols' => ' col-12 px-0  align-items-center d-flex',
            'select_options' =>  [
                    \App\Domains\Faq\Enum\FaqTypes::PRICE => 'Pricing',
                     \App\Domains\Faq\Enum\FaqTypes::ACCOUNT => 'Account',
                      \App\Domains\Faq\Enum\FaqTypes::COURSES => 'Courses',
                       \App\Domains\Faq\Enum\FaqTypes::CERTIFICATES => 'Certificates',
                ]

            ])
        </div>

        <div class="col-lg-3">
            @include('admin.components.inputs.text', ['name'=>'search', 'label' =>' ', 'cols' => ' col-12 px-0  align-items-center d-flex'])
        </div>

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





