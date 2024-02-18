@extends('admin.components.layouts.crud.layouts.form')

@section('title', $module_name . ' - ' . end($breadcrumb)->title)

@section('breadcrumb')
    @include('admin.layouts.breadcrumb', [
        'page_title' => end($breadcrumb)->title,
        'crumbs' => $breadcrumb,
    ])
@endsection


@section('form')

    <div class="row">
        <div class="col-lg-12">
            <div class="row font-weight-bold">
                @if (isset($row->attachment->full_url))
                    <div class="col-12 d-flex align-self-center justify-content-end">
                        <div class="text-right">
                            <a target="_blank" href="/admin/payout/actions/{{ $row->id }}/export"
                                class="btn btn-primary radius-5 px-4 waves-effect waves-light mb-2">
                                Download PDF
                            </a>
                        </div>
                    </div>
                @endif
                <div class="col-6">
                    <div class="d-flex">
                        <span class="col-4 size-18 text-dark ">
                            Payout name
                        </span>
                        <div class=" col-6">
                            {{ $row->name }}
                        </div>

                    </div>
                    <div class=" mt-3 d-flex">
                        <span class="size-18 text-dark  col-4">
                            Course
                        </span>
                        <div class="col-6">
                            {{ $row->course?->internal_name ?? $row->course?->name }}
                        </div>
                    </div>
                    <div class=" mt-3 d-flex">
                        <span class="size-18 text-dark  col-4">
                            Period
                        </span>
                        <div class="col-6">
                            {{ $row->period }}
                        </div>
                    </div>
                    <div class=" mt-3 d-flex">
                        <span class="size-18 text-dark  col-4">
                            Total Royalty
                        </span>
                        <div class="col-3">
                            {{ $row->royalty }}
                        </div>
                    </div>
                    <div class=" mt-3 d-flex">
                        <span class="size-18 text-dark  col-4">
                            Carried out from last quarter
                        </span>
                        <div class="col-3">
                            {{ $row->royalties_carried_out }}
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex">
                        <span class="col-3 size-18 text-dark ">
                            Instructor
                        </span>
                        <div class=" col-6">
                            {{ $row->instructor->first_name }}
                        </div>

                    </div>
                    @if ($row->type == \App\Domains\User\Enum\PayoutType::QUARTER)
                        <div class=" mt-3 d-flex">
                            <span class="size-18 text-dark  col-3">
                                Quarter
                            </span>
                            <div class="col-6">
                                Q{{ $row->quarter . ' ' . $row->year }}
                            </div>
                        </div>
                    @endif
                    <div class=" mt-3 d-flex">
                        <span class="size-18 text-dark  col-3">
                            Status
                        </span>
                        <div class="col-3">
                            {{ \App\Domains\User\Enum\PayoutStatus::getName($row->status) }}
                        </div>
                    </div>
                    <div class=" mt-3 d-flex">
                        <span class="size-18 text-dark  col-3">
                            Outstanding advances
                        </span>
                        <div class="col-3">
                            {{ $row->outstanding_advances }}
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end card-body-->
    </div>
    {{--         
@push('form_section')     
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

@if (isset($row->attachment->full_url))
<div class="col-12 d-flex align-self-center justify-content-end">
    <div class="text-right">
        <a target="_blank" href="/admin/payout/actions/{{ $row->id }}/export" class="btn btn-primary radius-5 px-4 waves-effect waves-light mb-2">
            download PDF
        </a>
    </div>
</div>
@endif

@if ($row->status == \App\Domains\User\Enum\PayoutStatus::PENDING)
        {!! Form::model($row,['method'=>'POST','url' => "$route"."/actions/royalties", 'files'=>true,'data-refresh-page'=>'true', 'data-toggle'=> 'ajax']) !!}
        {!! Form::hidden('id', $row->id) !!}
@if ($royalties->count() > 0)
    @include("{$view_path}.partials.update-royalty")
@else
@include("{$view_path}.partials.create-royalty")
@include('admin.components.inputs.success-btn', [
    'button_text' => "Update and generate PDF",
    'button_extra_class' => 'float-right',
    ])
    
    @endif
    
    {!! Form::close() !!}
    @endif
            </div>
            </div>
            </div>
            </div>

@endpush --}}
@endsection


@push('script')
    <script></script>
    <style>
        .size-18 {
            font-size: 18px;
        }
    </style>
@endpush
