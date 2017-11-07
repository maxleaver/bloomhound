@extends('layouts.app')

@section('content')

@component('layouts._hero')
  Account Settings

  @slot('subtitle')
    Some subtitle
  @endslot
@endcomponent

<div class="container">
  {{ Breadcrumbs::render('account_settings') }}
</div>

<section class="section">
	<div class="container">
		<account-settings-tabs
      :account="{{ $account }}"
      :markups="{{ $markups }}"
      :settings="{{ $account->settings }}"
      :type_settings="{{ $account->arrangeable_type_settings->load('type', 'markup') }}"
    ></account-settings-tabs>
	</div>
</section>
@endsection
