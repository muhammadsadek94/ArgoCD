@extends('admin.layouts.main')

@section('content')
    @yield('breadcrumb')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @yield('form')
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>

    @stack('form_section')


@endsection

