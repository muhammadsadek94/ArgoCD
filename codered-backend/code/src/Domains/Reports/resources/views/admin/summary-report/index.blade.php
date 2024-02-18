@extends("admin.layouts.main")

@section('title', 'Summary Report')



@section('content')

<div class="container-fluid">

    @include('admin.layouts.breadcrumb', [
         'page_title' => trans('Summary Report'),
         'crumbs' => [
             [
                 'title' => trans('Summary Report'),
                 'active' => true,
                 'url' => null
             ]
         ]
    ])

    @include('reports::admin.summary-report.navtabs')

 <form class="row">
      @include('admin.components.inputs.date', [
            'name'=>'from_date',
            'form_options' => ['style' => 'width:100%', 'required'],
            'label' =>'FROM DATE',
            'value' =>  isset($_GET['from_date']) ? $_GET['from_date'] : null,
            'cols' => 'col-12 col-xl-3 col-md-3 col-xs-12'
        ])


        @include('admin.components.inputs.date', [
            'name'=>'to_date',
            'form_options' => ['style' => 'width:100%',  'required',],
            'label' =>'TO DATE',
            'value' =>  isset($_GET['to_date']) ? $_GET['to_date'] : null,
            'cols' => 'col-12 col-xl-3 col-md-3 col-xs-12'
        ])

        <div class="form-group col-12 col-xl-3 col-md-3 ">

            @if(isset($_GET['from_date']))
                <input type="button" style="margin: 29px 20px 0 -10px;background-color:#E83C30; border-radius: 6px; border-color:#E83C30"
                   class="btn btn-danger"
                   value = "Reset" onclick="location.replace('{{route('getReports')}}')"
                />
            @endif
            <button type="submit"
                    style="margin: 29px 0 0 -10px;background-color:#3DC47E; border-radius: 6px; border-color:#3DC47E"
                    class="btn btn-success waves-effect waves-light width-sm ">FILTER
                    <i class="fa fa-search"></i>
            </button>
        </div>
 </form>




    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Free Users</h5>
                    <h3 data-plugin="counterup" class="card-text text-warning">{{ $free_users_count }}</h3>
                </div>
            </div> <!-- end card-box-->
        </div>


        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Monthly Users</h5>
                    <h3 data-plugin="counterup" class="card-text text-success">{{$monthly_users_count}}</h3>
                </div>
            </div> <!-- end card-box-->
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Annual Users</h5>
                    <h3 data-plugin="counterup" class="card-text text-danger">{{$annual_users_count}}</h3>
                </div>
            </div> <!-- end card-box-->
        </div>


        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Users who set goals</h5>
                    <h3 data-plugin="counterup" class="card-text text-warning">{{$goal_user_count}}</h3>
                </div>
            </div> <!-- end card-box-->
        </div>

         <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Users who set notes</h5>
                    <h3 data-plugin="counterup" class="card-text text-success">{{$lesson_user_count}}</h3>
                </div>
            </div> <!-- end card-box-->
        </div>


        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Certificates Issued </h5>
                    <h3 data-plugin="counterup" class="card-text text-danger">{{$certificates}}</h3>
                </div>
            </div> <!-- end card-box-->
        </div>

         <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Users from old platform who reset their passwords</h5>
                    <h3 data-plugin="counterup" class="card-text text-warning">{{$users_reset_psswd}}</h3>
                     <h6  class="card-text text-info">Note : This number will not change by filteration dates</h6>
                </div>
            </div> <!-- end card-box-->
        </div>

         <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total lessons watched</h5>
                    <h3 data-plugin="counterup" class="card-text text-success">{{$count_watched_lessons}}</h3>
                </div>
            </div> <!-- end card-box-->
        </div>

         <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Users registered on website</h5>
                    <h3 data-plugin="counterup" class="card-text text-danger">{{$total_users_registered}}</h3>
                </div>
            </div> <!-- end card-box-->
        </div>


    </div>
</div>

@endsection


