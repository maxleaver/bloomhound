@extends('layouts.app')

@section('content')

@component('layouts._hero')
  Home

  @slot('subtitle')
    You are logged in!
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('home') }}
</div>

<section class="section">
	<div class="container">
		<h1 class="title">Your Upcoming Events</h1>
		<event-list></event-list>
	</div>
</section>
@endsection
