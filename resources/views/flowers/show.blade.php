@extends('layouts.app')

@section('content')

@component('layouts._hero')
  {{ $flower->name }}

  @slot('subtitle')
    Some subtitle
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('flower', $flower) }}
</div>

<section class="section">
  <div class="container">
    <h1 class="title">Varieties</h1>
    <variety-list :id="{{ $flower->id }}" :markups="{{ $markups }}"></variety-list>
  </div>
</section>

<section class="section">
  <div class="container">
    <h1 class="title">Notes</h1>
    <note-list></note-list>
  </div>
</section>
@endsection
