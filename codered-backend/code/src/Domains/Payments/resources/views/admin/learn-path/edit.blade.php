@extends('admin.components.layouts.crud.implementation.edit')
@isset($row)
    @push('form_section')
        @include("{$view_path}.package.index", [
            'path' => $row,
            'view_path' => $view_path,
        ])
    @endpush
@endisset
