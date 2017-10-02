@extends('layouts.app')

@section('content')
<div class="hero is-dark">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">{{ $item->name }}</h1>
      <h2 class="subtitle">Last updated {{ $item->updated_at->diffForHumans() }}</h2>
    </div>
  </div>
</div>

<section class="section">
  <div class="container">
    <h1 class="title">Notes</h1>
    <note-list></note-list>
  </div>
</section>
@endsection
