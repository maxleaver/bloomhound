@extends('layouts.app')

@section('content')

@component('layouts._hero')
  {{ $contact->name }}

  @slot('subtitle')
    Last updated {{ $contact->updated_at->diffForHumans() }}
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('contact', $contact) }}
</div>

<section class="section">
  <div class="container">
    <contact-profile :contact="{{ $contact }}"></contact-profile>
  </div>
</section>

<section class="section">
  <div class="container">
    <h1 class="title">Notes</h1>
    <note-list></note-list>
  </div>
</section>
@endsection
