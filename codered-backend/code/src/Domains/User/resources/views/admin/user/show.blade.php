@extends('admin.layouts.main')

@section('title', $module_name . " - " . end($breadcrumb)->title)


@section('content')
    @include('admin.layouts.breadcrumb', [
           'page_title' => end($breadcrumb)->title,
           'crumbs' => $breadcrumb
       ])

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-12">
                            <a class="float-right" href="{{ url("{$route}/{$row->id}/edit") }}">
                                <i class="fas fa-pen"></i>
                            </a>
                        </div>
                    </div> <!-- end row-->


                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div>
    @include("{$view_path}.subscriptions")
@endsection




