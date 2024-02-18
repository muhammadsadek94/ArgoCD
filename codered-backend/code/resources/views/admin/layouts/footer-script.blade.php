        <!-- Vendor js -->
        <script src="{{ asset('admin/assets/js/vendor.min.js') }}"></script>
        <script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/select2/select2.min.js') }}"></script>
        <script src="{{ asset('admin/assets/libs/select2/select2.min.js') }}"></script>
        @stack('script')

        <!-- App js -->
        <script src="{{ asset('admin/assets/js/app.min.js') }}"></script>
        <script src="{{ asset('admin/assets/js/intcore/settings.js') }}"></script>
        <script src="{{ asset('admin/assets/js/intcore/helpers.js') }}"></script>
        <script src="{{ asset('admin/assets/js/intcore/ajax.js') }}"></script>
        <script src="{{ asset('admin/assets/js/intcore/prototype/string.js') }}"></script>
        @stack('script-bottom')
