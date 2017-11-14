@extends('layouts.guest')

@section('content')
<div class="columns is-centered">
  <div class="column">
    <section class="hero has-text-centered">
      <div class="hero-body">
        <div class="container">
          <h1 class="title">Login to Bloomhound</h1>
        </div>
      </div>
    </section>
  </div>
</div>

<div class="columns is-centered">
  <div class="column is-half">

    @if ($errors->any())
      <div class="notification is-danger">
        Your email address or password was incorrect
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
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
            <i class="mdi mdi-email"></i>
          </span>

          @if ($errors->has('email'))
            <p class="help is-danger">{{ $errors->first('email') }}</p>
          @endif
        </div>
      </div>

      <div class="field">
        <label for="password" class="label is-medium">Password</label>
        <div class="control has-icons-left">
          <input
            id="password"
            type="password"
            class="input is-large{{ $errors->has('password') ? ' is-danger' : '' }}"
            name="password"
            value="{{ old('password') }}"
            placeholder="Your email address"
            required
            autofocus
          />
          <span class="icon is-medium is-left">
            <i class="mdi mdi-lock"></i>
          </span>

          @if ($errors->has('password'))
            <p class="help is-danger">{{ $errors->first('password') }}</p>
          @endif
        </div>
      </div>

      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <div class="field">
              <div class="control">
                <label class="checkbox">
                  <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                  Remember Me
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="level-right">
          <div class="level-item">
            <div class="columns">
              <div class="column">
                <a class="is-pulled-right" href="{{ route('password.request') }}">Forgot your password?</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="columns">
        <div class="column">
          <div class="field">
            <div class="control has-text-centered">
              <button type="submit" class="button is-primary is-large">
                  Login
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="columns is-centered">
  <div class="column is-half">
    <section class="section">
      <div class="has-text-centered">
        Don't have an account? <a href="{{ route('register') }}">Create an Account</a>
      </div>
    </section>
  </div>
</div>
@endsection
