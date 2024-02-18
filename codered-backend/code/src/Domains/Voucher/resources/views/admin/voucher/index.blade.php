@extends('admin.components.layouts.crud.implementation.index')

@if (session()->has('success'))
    @push('script-bottom')
        <script>
            $(document).ready(function() {
                toastr['success']("{{ session()->get('success') }}");
            });
        </script>
    @endpush
@endif
