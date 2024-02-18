<div class="row">
    <div class="col-lg-12">
        <div class="">
            <h2 class="card-header py-0 pt-3">
                Video
            </h2>

        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


<div class="card  tab-card">
    <div class="card-header tab-card-header px-2">
        <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
            <li class="nav-item ">
                <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="One"
                   aria-selected="true">Brightcove Player</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="Two"
                   aria-selected="false">Vimeo Player</a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active p-3" id="one" role="tabpanel" aria-labelledby="one-tab">

            {!! Form::model($row,['method'=>'POST','url' => [$route, $row->id,'video', 'upload'], 'files'=>true,'data-toggle'=> 'ajax']) !!}
            @include('admin.components.inputs.file', [
                'name' => 'video[file]',
                'label' => trans("Video"),
                'form_options'=> ['required', 'id' => 'video'],
                'cols' => 'col-6',
            ])
{{--            <p class="text-black-50">PS: take up to 5 mins to be updated to brightcove</p>--}}
            <div style="max-width: 960px;">
                <video-js data-account="{{ config('brightcove.brightcove.account_id') }}"
                          data-player="{{ isset($row->video['player_id']) ? $row->video['player_id'] : null }}"
                          data-embed="default"
                          controls=""
                          data-video-id="{{ @$row->video['video_id'] }}"
                          data-playlist-id=""
                          data-application-id=""
                          class="vjs-fluid"></video-js>
            </div>
            <script src="//players.brightcove.net/{{ config('brightcove.brightcove.account_id') }}/{{ isset($row->video['player_id']) ? $row->video['player_id'] : null }}_default/index.min.js">
            </script>
            {!! Form::hidden('course_id', $row->course_id) !!}
            {!! Form::hidden('chapter_id', $row->chapter_id) !!}
            {!! Form::hidden('id', $row->id) !!}
            @include('admin.components.inputs.success-btn', ['button_text' => "Save", 'button_extra_class' => 'float-right mb-4'])
            {!! Form::close() !!}

        </div>

        <div class="tab-pane fade p-3" id="two" role="tabpanel" aria-labelledby="two-tab">

            <div class="position-relative">

                {!! Form::model($row,['method'=>'POST','url' => [$route, $row->id,'vimeo', 'upload'], 'files'=>true,'data-toggle'=> 'ajax','data-refresh-page' => "true"]) !!}
                @include('admin.components.inputs.file', [
                    'name' => 'video[file]',
                    'label' => trans("Video"),
                    'form_options'=> ['required', 'id' => 'video'],
                    'cols' => 'col-6',
                ])
{{--                <p class="text-black-50">PS: take up to 5 mins to be updated to vimeo</p>--}}


                {!! Form::hidden('course_id', $row->course_id) !!}
                {!! Form::hidden('chapter_id', $row->chapter_id) !!}
                {!! Form::hidden('id', $row->id) !!}
                @include('admin.components.inputs.success-btn', ['button_text' => "Save", 'button_extra_class' => 'float-right mb-4'])
                {!! Form::close() !!}
            </div>

            <div class="m-3 mt-4">

                <div id="player-container"></div>
                <script src="https://player.vimeo.com/api/player.js"></script>
                <script>
                    let videoUri = '{!! @$row->video['video_id']  !!}'
                    let videoId = videoUri.split('videos/')[1]; // to get only the id
                    var options = {
                        url: 'https://vimeo.com/' + videoId,
                    };
                    player = new Vimeo.Player('player-container', options);
                </script>

                @include('course::admin.lesson.video.captions.index',
                  [
                      'lesson' => $row,
                      'caption_data' => $caption_data
                  ])


            </div>

        </div>

    </div>
</div>


