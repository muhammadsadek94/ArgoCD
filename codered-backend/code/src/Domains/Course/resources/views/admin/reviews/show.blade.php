@extends('admin.layouts.main')

@section('title', 'Show Review')

@section('content')

@push('head')
<style>
    .wrapping{
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow-wrap: break-word;
    }
</style>
@endpush

    <!-- Page-Title -->
@section('breadcrumb')

    <li><a href="/reviews">Reviews </a></li>
    <li><a href="{{url('/reviews')}}">Show Review </a></li>

@stop
<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Show Review </h3>
                </div>
                <div class="row">
                    <div class="col-lg-6 col-xl-6">
                        <div class="card-box text-center">

                            <h4 class="pb-3">Course Name: {{ $row->course->name }}</h4>


                            <div class="text-left mt-3">

                                <h4 class="font-13 text-uppercase">Review Name:
                                    <span class="text-muted font-13 mb-3">
                                    {{ $row->name }}
                                </span>
                                </h4>

                                <p class=" mb-2 font-13 ">
                                    <strong>Review Recommendation :</strong>
                                    <p class="bg-soft-secondary p-3 wrapping">{{ $row->recommendation }}</p>
                                </p>

                                <p class=" mb-2 font-13 ">
                                    <strong>Review :</strong>
                                    <p class="bg-soft-secondary p-3 wrapping">{{ $row->user_goals }}</p>
                                </p>

                                <p class=" mb-2 font-13">
                                    <strong>Review Rate :</strong>
                                    @foreach(range(1, $row->rate) as $value)
                                        <i class="fa fa-star" style="color: gold"></i>
                                    @endforeach
                                    @if(5 - $row->rate)
                                        @foreach(range(1, (5 - $row->rate)) as $value)
                                            <i class="fa fa-star"></i>
                                        @endforeach
                                    @endif


                                </p>

                                <p class=" mb-2 font-13 ">
                                    <strong>Review Actions :</strong>
                                <span class="ml-2 ">
                                    @if(!$row->activation)
                                        <a href="{{url("/admin/reviews/change-status/{$row->id}/1")}}" type="button"
                                           class="btn btn-success btn-xs waves-effect mb-2 waves-light">
                                            Publish</a>
                                    @else
                                        <a href="{{url("/admin/reviews/change-status/{$row->id}/0")}}" type="button"
                                           class="btn btn-danger btn-xs waves-effect mb-2 waves-light">
                                            Suspend</a>
                                    @endif
                                </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END PAGE CONTENT WRAPPER -->
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->
</div>

@stop
