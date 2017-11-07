@extends('layouts.app')

@section('content')

@component('layouts._hero')
  {{ $item->name }}

  @slot('subtitle')
    {{ $item->description }}
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('item', $item) }}
</div>

<section class="section">
  <div class="container">
    <item-profile
      :item="{{ $item }}"
      :markups="{{ $markups }}"
      :types="{{ $types }}"
    ></item-profile>
  </div>
</section>

<section class="section">
  <div class="container">
    <h1 class="title">Notes</h1>
    <note-list></note-list>
  </div>
</section>
@endsection
