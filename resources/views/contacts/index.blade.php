@extends('layouts.app')

@section('content')

@component('layouts._hero')
  Contacts

  @slot('subtitle')
    Some subtitle
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('contacts') }}
</div>

<section class="section">
	<div class="container">
		<contact-list></contact-list>
	</div>
</section>
@endsection
