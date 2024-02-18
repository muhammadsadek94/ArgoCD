<div class="row">
    <div class="col-lg-12">
        <div class="">
            <div class="">
                <div class="row mb-2">
                    <div class="col-12 mt-4">
                        <h2 class="d-inline"> Captions</h2>

                        @include('course::admin.lesson.video.captions.create', [
                            'lesson' => $lesson
                        ])
                    </div><!-- end col-->
                </div> <!-- end row-->

                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-centered table-hover table-striped mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>status</th>
                                    <th>@lang('lang.Actions')</th>
                                </tr>
                                </thead>
                                <tbody id="table">
                                {{--                                <tr>--}}
                                {{--                                    <td>{{ $caption->code }}</td>--}}
                                {{--                                    <td>{{ $caption->name }}</td>--}}
                                {{--                                    <td>--}}
                                {{--                                        @include('course::admin.lesson.caption.edit', [--}}
                                {{--                                            'lesson' => $lesson,--}}
                                {{--                                            'caption' => $caption--}}
                                {{--                                        ])--}}
                                {{--                                        {!! Form::open(['method' => 'DELETE', 'url' => [$route, 'actions', 'caption', $caption->id], 'class' => 'form-horizontal']) !!}--}}
                                {{--                                        {!! Form::hidden('id', $caption->id) !!}--}}
                                {{--                                        <button type="submit" class="btn btn-danger "--}}
                                {{--                                                onclick="return confirm('Confirm Delete operation ?');">--}}
                                {{--                                            <i class="fa fa-trash"></i> @lang('lang.delete')--}}
                                {{--                                        </button>--}}

                                {{--                                        {!! Form::close() !!}--}}

                                {{--                                    </td>--}}
                                {{--                                </tr>--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->

    @push('script-bottom')
        <script>
            var captionData

            $(document).ready(function () {
                getVideoCaption();
            });

             function getVideoCaption(){
                $.ajax({
                    url: '{!! url("{$route}/{$row->id}/vimeo/lesson-caption") !!}',
                    type: "POST",
                    success: function (response) {
                        if (response) {
                            captionData = response
                            drawTable();

                        }
                    },
                });

                }
            function drawTable (){
                $('#table').empty();
                captionData.forEach(element => {
                            let row ='';
                                let activeRow = `<tr>
                                            <td>  ${element.name}  </td>
                                            <td>  ${element.active} </td>
                                            <td>
                                    <button class="btn btn-uview btn-success text-capitalize"  onclick="updateCaption( '${element.uri}' ,true)">active </button>
                                    </td>
                                            </tr> `;

                                let deActiveRow = `<tr>
                                            <td> ${element.name}</td>
                                            <td> ${element.active} </td>
                                            <td>
                                    <button class="btn  btn-udelete text-capitalize"  onclick="updateCaption( '${element.uri}' ,false)" > deactivate</button>
                                    </td>
                                            </tr> `;
                        if(element.active.toString() === 'true'){
                            row = deActiveRow;
                        }
                        else {
                            row = activeRow;

                        }
                                $('#table').append(row);
                            })
            }
            function updateCaption(uri, status){
                $.ajax({
                    url: '{!! url("{$route}/{$row->id}/vimeo/update-caption") !!}',
                    method: "POST",
                    data: JSON.stringify({
                        uri:uri,
                        status:status,
                        _token:"{{ @csrf_token()}}"
                    }),
                    'contentType': 'application/json',
                    success: function (response) {
                        if (response) {
                            getVideoCaption();
                        }
                    },
                });

            }


        </script>
    @endpush

</div>
