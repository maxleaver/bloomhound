@extends('layouts.app')

@section('content')
<div class="hero is-dark">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">{{ $user->name }}</h1>
      <h2 class="subtitle">{{ $user->email }}</h2>
    </div>
  </div>
</div>

<div class="container">
  {{ Breadcrumbs::render('profile') }}
</div>

<section class="section">
	<div class="container">
		<div class="columns">
			<div class="column is-two-thirds">
				<update-profile name="{{ $user->name }}" email="{{ $user->email }}"></update-profile>
			</div>

			<div class="column">
				<div class="box">
					<h1 class="title is-5">Change your password</h1>

					<update-password></update-password>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
