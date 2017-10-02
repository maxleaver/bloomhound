@extends('layouts.app')

@section('content')
<div class="hero is-dark">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">{{ $event->name }}</h1>
      <h2 class="subtitle">{{ $event->date->toFormattedDateString() }}</h2>
    </div>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="columns">
      <div class="column is-half">
        <strong>{{ $event->customer->name }}</strong>
      </div>

      <div class="column">
        <strong>{{ $event->account->name }}</strong><br />
        {{ $event->account->address }}<br />
        Tel: {{ $event->account->phone }}<br />
        Email: {{ $event->account->email }}<br />
        {{ $event->account->website }}
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <event-vendor-list :event-id="{{ $event->id }}"></event-vendor-list>
  </div>
</section>

<section class="section">
  <div class="container">
    <event-arrangements :event-id="{{ $event->id }}"></event-arrangements>
  </div>
</section>

<section class="section">
  <div class="container">
    <h1 class="title">Payments</h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <h1 class="title">Notes</h1>
    <note-list></note-list>
  </div>
</section>
@endsection
