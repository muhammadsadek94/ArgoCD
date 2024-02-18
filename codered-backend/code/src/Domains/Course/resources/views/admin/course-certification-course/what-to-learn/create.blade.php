<!-- Button trigger modal -->
<button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addWhatToLEarn">
    Create
</button>

<!-- Modal -->
<div class="modal fade" id="addWhatToLEarn" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create What to Learn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['method' => 'POST', 'url' => "{$route}/actions/what-to-learn", 'files' => true, 'data-toggle' => 'ajax', 'data-refresh-page' => 'true', 'reset' => 'true']) !!}

            <div class="modal-body">
                <div class="container-fluid">
                    @include('admin.components.inputs.image', [
                        'name' => 'what_image_id',
                        'label' => trans('Image'),
                        'cols' => 'col-12',
                        'endpoint' => url(Constants::ADMIN_BASE_URL . '/course/actions/upload-image'),
                        'form_options' => ['required']
                    ])
                    @include('admin.components.inputs.text', [
                        'name' => 'title',
                        'label' => trans("Title"),
                        'form_options'=> ['required'],
                        'cols' => 'col-12',
                    ])
                    @include('admin.components.inputs.textarea', [
                        'name' => 'description',
                        'label' => trans("Description"),
                        'form_options'=> [
                            'required',
                            'rows' => 2
                        ],
                        'cols' => 'col-12',
                    ])
                    {!! Form::hidden('course_id', $course->id) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save</button>
            </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>

@push('script-bottom')
    <script>
        $('.inc-feature').on('click', function() {
            const total = getCountFeatures();

            if (total > 4) {
                $(this).attr('disabled', true);
            }
        });

        $(document).on('.dec-feature', 'click', function() {
            const total = getCountFeatures();
            console.log('hi', total)
            if (total < 5) {
                $('.inc-feature').attr('disabled', false);
            }
        });

        function getCountFeatures() {
            const features = $('.create-feature-item');
            if (features > 4) {
                $(this).attr('disabled', true);
            }
            return features.length;
        }
    </script>
@endpush
