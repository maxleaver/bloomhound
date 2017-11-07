@extends('layouts.app')

@section('content')

@component('layouts._hero')
  Flowers

  @slot('subtitle')
    Some subtitle
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('flowers') }}
</div>

<section class="section">
	<div class="container">
		<flower-list></flower-list>
	</div>
</section>
@endsection
