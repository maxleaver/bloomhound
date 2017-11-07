@extends('layouts.app')

@section('content')

@component('layouts._hero')
  Vendors

  @slot('subtitle')
    Some subtitle
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('vendors') }}
</div>

<section class="section">
	<div class="container">
		<vendor-list></vendor-list>
	</div>
</section>
@endsection
