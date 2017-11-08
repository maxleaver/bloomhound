@extends('layouts.app')

@section('content')
<event-profile
	:event="{{ $event }}"
	:proposal="{{ $proposal }}"
	:settings="{{ $settings }}"
	:vendors="{{ $vendors }}"
></event-profile>

<section class="section">
  <div class="container">
    <h1 class="title">Notes</h1>
    <note-list></note-list>
  </div>
</section>
@endsection
