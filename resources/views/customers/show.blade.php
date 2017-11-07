@extends('layouts.app')

@section('content')

@component('layouts._hero')
  {{ $customer->name }}

  @slot('subtitle')
    Last updated {{ $customer->updated_at->diffForHumans() }}
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('customer', $customer) }}
</div>

<section class="section">
  <div class="container">
    <div class="columns">
      <div class="column is-half">
        <customer-profile :customer="{{ $customer }}"></customer-profile>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <event-list :customer_id="{{ $customer->id }}"></event-list>
  </div>
</section>

<section class="section">
	<div class="container">
		<contact-list :customer_id="{{ $customer->id }}"></contact-list>
	</div>
</section>

<section class="section">
  <div class="container">
    <h1 class="title">Notes</h1>
    <note-list></note-list>
  </div>
</section>
@endsection
