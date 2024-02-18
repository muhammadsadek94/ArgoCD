@extends("admin.layouts.{$layout}")
{{-- Settings --}}
@push('head')
    <style>
        .navbar-custom {
            /* background-color: #3d40c6 !important; */ /** Primary color **/
            background-color: #FFFFFF !important; /** Primary color **/
        }
    </style>

    <script type="text/javascript">
        // define global variable to pass data to js files
        window.app_variables = {!! json_encode([
                    'url'=>url(''),
                    'ADMIN_PATH'=>url($admin_base_url),
                    'csrfToken' => csrf_token(),
                    'auth'=>$auth
                ]);
        !!};
    </script>


@endpush
