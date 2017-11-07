@extends('layouts.app')

@section('content')

@component('layouts._hero')
  {{ $vendor->name }}

  @slot('subtitle')
    Last updated {{ $vendor->updated_at->diffForHumans() }}
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('vendor', $vendor) }}
</div>

<section class="section">
  <div class="container">
    <div class="columns">
      <div class="column is-half">
        <vendor-profile :vendor="{{ $vendor }}"></vendor-profile>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h1 class="title">Events</h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <h1 class="title">Orders</h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <h1 class="title">Notes</h1>
    <note-list></note-list>
  </div>
</section>
@endsection
