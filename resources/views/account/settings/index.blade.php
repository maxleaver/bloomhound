@extends('layouts.app')

@section('content')
<div class="hero is-dark">
  <div class="hero-body">
    <div class="container">
      <h1 class="title">Account Settings</h1>
      <h2 class="subtitle">Some subtitle</h2>
    </div>
  </div>
</div>

<div class="container">
  {{ Breadcrumbs::render('account_settings') }}
</div>

<section class="section">
	<div class="container">
		<account-settings-tabs
      :account="{{ $account }}"
      :markups="{{ $markups }}"
      :settings="{{ $account->arrangeable_type_settings->load('type', 'markup') }}"
    ></account-settings-tabs>
	</div>
</section>
@endsection
