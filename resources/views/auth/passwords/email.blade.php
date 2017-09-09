@extends('layouts.guest')

@section('content')
<div class="columns is-centered">
  <div class="column">
    <section class="hero has-text-centered">
      <div class="hero-body">
        <div class="container">
          <h1 class="title">Forgot Your Password?</h1>
          <h2 class="subtitle">Enter your email address below and we'll send an email with a link to reset your password</h2>
        </div>
      </div>
    </section>
  </div>
</div>

<div class="columns is-centered">
  <div class="column is-half">
    @if (session('status'))
      <div class="notification is-success">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      {{ csrf_field() }}

      <div class="field">
        <label for="email" class="label is-medium">Email address</label>
        <div class="control has-icons-left">
          <input
            id="email"
            type="email"
            class="input is-large{{ $errors->has('email') ? ' is-danger' : '' }}"
            name="email"
            value="{{ old('email') }}"
            placeholder="Your email address"
            required
            autofocus
          />
          <span class="icon is-medium is-left">
            <i class="fa fa-envelope"></i>
          </span>

          @if ($errors->has('email'))
            <p class="help is-danger">{{ $errors->first('email') }}</p>
          @endif
        </div>
      </div>

      <div class="columns">
        <div class="column">
          <div class="field">
            <div class="control has-text-centered">
              <button type="submit" class="button is-primary is-large">
                Send Password Reset Link
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="has-text-centered">
  or <a href="{{ route('login') }}">return to login</a>
</div>
@endsection
