@extends('layouts.app')

@section('content')
<div class="hero is-dark">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">Home</h1>
      <h2 class="subtitle">You are logged in!</h2>
    </div>
  </div>
</div>

<section class="section">
	<div class="container">
		<h1 class="title">Your Upcoming Events</h1>
		<event-list></event-list>
	</div>
</section>
@endsection
