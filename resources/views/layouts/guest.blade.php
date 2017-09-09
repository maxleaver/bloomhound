@extends('layouts.global')

@section('global_nav')
    @include('nav.guest')
@endsection

@section('global_content')
    @yield('content')
@endsection