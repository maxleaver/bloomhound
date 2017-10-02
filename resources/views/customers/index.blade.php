@extends('layouts.app')

@section('content')
<div class="hero is-dark">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">Customers</h1>
      <h2 class="subtitle">Some subtitle</h2>
    </div>
  </div>
</div>

<section class="section">
	<div class="container">
		<customer-list></customer-list>
	</div>
</section>
@endsection
