@extends('layouts.app')

@section('content')

@component('layouts._hero')
  {{ $user->name }}

  @slot('subtitle')
    {{ $user->email }}
  @endslot
@endcomponent

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
