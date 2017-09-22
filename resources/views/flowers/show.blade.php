@extends('layouts.app')

@section('content')
<div class="hero is-primary">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">{{ $flower->name }}</h1>
      <h2 class="subtitle">Some subtitle</h2>
    </div>
  </div>
</div>

<section class="section">
  <div class="container">
    <h1 class="title">Varieties</h1>
    <variety-list :id="{{ $flower->id }}"></variety-list>
  </div>
</section>

<section class="section">
  <div class="container">
    <h1 class="title">Notes</h1>
    <note-list></note-list>
  </div>
</section>
@endsection