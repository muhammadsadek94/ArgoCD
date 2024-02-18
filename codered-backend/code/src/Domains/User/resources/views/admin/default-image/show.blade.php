@extends('admin.layouts.main')

@section('title', $module_name . " - " . end($breadcrumb)->title)


@section('content')
<img src="{{ url($row->full_url) }}" class="img-thumbnail"
alt="profile-image">
@endsection




