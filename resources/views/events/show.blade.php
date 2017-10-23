@extends('layouts.app')

@section('content')
<event-profile
	:arrangements="{{ $event->arrangements }}"
	:event="{{ $event }}"
	:settings="{{ $settings }}"
>
  {{ Breadcrumbs::render('event', $event) }}
</event-profile>

<section class="section">
  <div class="container">
    <h1 class="title">Notes</h1>
    <note-list></note-list>
  </div>
</section>
@endsection
