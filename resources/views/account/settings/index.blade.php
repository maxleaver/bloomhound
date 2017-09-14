@extends('layouts.app')

@section('content')
<div class="hero is-light">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">Account Settings</h1>
      <h2 class="subtitle">Some subtitle</h2>
    </div>
  </div>
</div>

<section class="section">
	<div class="container">
		<account-profile :account="{{ $account }}"></account-profile>
	</div>
</section>
@endsection
