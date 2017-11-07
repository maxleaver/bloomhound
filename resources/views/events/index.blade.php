@extends('layouts.app')

@section('content')

@component('layouts._hero')
  Events

  @slot('subtitle')
    Some subtitle
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('events') }}
</div>

<section class="section">
	<div class="container">
		<event-list></event-list>
	</div>
</section>
@endsection
