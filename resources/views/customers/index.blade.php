@extends('layouts.app')

@section('content')

@component('layouts._hero')
  Customers

  @slot('subtitle')
    Some subtitle
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('customers') }}
</div>

<section class="section">
	<div class="container">
		<customer-list></customer-list>
	</div>
</section>
@endsection
