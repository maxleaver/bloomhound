@extends('layouts.app')

@section('content')

@component('layouts._hero')
  Items

  @slot('subtitle')
    Some subtitle
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('items') }}
</div>

<section class="section">
	<div class="container">
		<item-list></item-list>
	</div>
</section>
@endsection
