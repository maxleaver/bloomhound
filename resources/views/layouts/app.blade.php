@extends('layouts.global')

@section('global_nav')
@include('nav.user')
@endsection

@section('global_content')

@yield('content')

<flash message="{{ session('flash') }}"></flash>

@endsection