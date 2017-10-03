@extends('layouts.app')

@section('content')
<div class="hero is-dark">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">Vendors</h1>
      <h2 class="subtitle">Some subtitle</h2>
    </div>
  </div>
</div>

<div class="container">
  {{ Breadcrumbs::render('vendors') }}
</div>

<section class="section">
	<div class="container">
		<vendor-list></vendor-list>
	</div>
</section>
@endsection
