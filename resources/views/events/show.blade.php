@extends('layouts.app')

@section('content')
<event-profile
	:event="{{ $event }}"
	:proposal="{{ $proposal }}"
	:settings="{{ $settings }}"
	:vendors="{{ $vendors }}"
></event-profile>

<note-list></note-list>
@endsection
