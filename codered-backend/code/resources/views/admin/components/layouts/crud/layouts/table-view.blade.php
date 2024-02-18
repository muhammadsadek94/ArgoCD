@extends('admin.layouts.main')

@section('content')

    <div class="row mt-4">
        <div class="@yield('tabs-width', 'col') align-self-end">
            @yield('tabs')
        </div>
        <div class="col pr-2">
            @yield('search')
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-lg-8">
                            @yield('breadcrumb')
                        </div>
                        <div class="col-lg-4 d-flex align-self-center justify-content-end">
                           @yield('call-to-actions')
                        </div><!-- end col-->
                    </div> <!-- end row-->


                    <div class="row mb-2">
                        <div class="col-md-12">
                            @if(session()->has('success'))
                                <div class="alert alert-success col-12">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            <div class="table-responsive">
                                @yield('table')
                                @yield('pagination')
                            </div>
                        </div>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>

@endsection
