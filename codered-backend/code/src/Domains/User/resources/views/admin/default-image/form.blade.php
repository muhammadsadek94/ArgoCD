@push('head')
    <script></script>
    <link rel="stylesheet" href="{{ asset('/admin/assets/libs/dropify/dropify.min.css') }}">

    <style>
        .dropify-filename {
            display: none;
        }
    </style>
@endpush

<div class="panel-body row">

    <div class="col-lg-6 offset-lg-3">
        <input type="file" name="file" id="image-holder">
    </div>
</div>

@include('admin.components.inputs.success-btn', [
    'button_text' => $submitButton,
    'button_extra_class' => 'float-right',
])

@push('script-bottom')
    <script src="{{ asset('/admin/assets/libs/dropify/dropify.js') }}"></script>
    <script>
        $(document).ready(function() {
            const dropifyEvent = $('#image-holder').dropify({
                messages: {
                    'default': ' ',
                    'replace': ' ',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong happended.'
                }
            });
        })
    </script>
@endpush
