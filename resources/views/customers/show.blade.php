@extends('layouts.app')

@section('content')
<div class="hero is-primary">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">{{ $customer->name }}</h1>
      <h2 class="subtitle">Some subtitle</h2>
    </div>
  </div>
</div>
@endsection
