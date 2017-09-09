@extends('layouts.guest')

@section('content')
<div class="columns is-centered">
  <div class="column">
    <section class="hero has-text-centered">
      <div class="hero-body">
        <div class="container">
          <h1 class="title">
            Get started!
          </h1>
          <h2 class="subtitle">
            Your free 30 day trial is right around the corner.
          </h2>
        </div>
      </div>
    </section>
  </div>
</div>

<div class="columns is-centered">
  <div class="column is-half">
    <form method="POST" action="{{ route('register') }}">
      {{ csrf_field() }}

      <div class="field">
        <label for="name" class="label is-medium">Your Name</label>
        <div class="control">
          <input
            id="name"
            type="text"
            class="input is-medium{{ $errors->has('name') ? ' is-danger' : '' }}"
            name="name"
            value="{{ old('name') }}"
            placeholder="Jane Doe"
            required
            autofocus
          />

          @if ($errors->has('name'))
            <p class="help is-danger">{{ $errors->first('name') }}</p>
          @endif
        </div>
      </div>

      <div class="field">
        <label for="company" class="label is-medium">Your Company Name</label>
        <div class="control">
          <input
            id="company"
            type="text"
            class="input is-medium{{ $errors->has('company') ? ' is-danger' : '' }}"
            name="company"
            value="{{ old('company') }}"
            placeholder="Clever Florist, Inc."
            required
          />

          @if ($errors->has('company'))
            <p class="help is-danger">{{ $errors->first('company') }}</p>
          @endif
        </div>
      </div>

      <div class="field">
        <label for="email" class="label is-medium">Email Address</label>
        <div class="control">
          <input
            id="email"
            type="email"
            class="input is-medium{{ $errors->has('email') ? ' is-danger' : '' }}"
            name="email"
            value="{{ old('email') }}"
            placeholder="jane@cleverflorist.com"
            required
          />

          @if ($errors->has('email'))
            <p class="help is-danger">{{ $errors->first('email') }}</p>
          @endif
        </div>
      </div>

      <div class="field">
        <label for="password" class="label is-medium">Password (8+ characters)</label>
        <div class="control">
          <input
            id="password"
            type="password"
            class="input is-medium{{ $errors->has('password') ? ' is-danger' : '' }}"
            name="password"
            required
          />

          @if ($errors->has('password'))
            <p class="help is-danger">{{ $errors->first('password') }}</p>
          @endif
        </div>
      </div>

      <div class="field">
        <label for="password-confirm" class="label is-medium">Confirm Your Password</label>
        <div class="control">
          <input
            id="password-confirm"
            type="password"
            class="input is-medium"
            name="password_confirmation"
            required
          />
        </div>
      </div>

      <div class="columns">
        <div class="column">
          <div class="field">
            <div class="control has-text-centered">
              <button class="button is-primary is-large" type="submit">
                <span>Start your free 30 day trial</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="section">
  <div class="container">
    <div class="content">
      <h1>Additional details</h1>

      <p><strong>What happens when the trial’s up? Will we be charged?</strong></p>
      <p>No. We don’t ask for your credit card up front, so we can’t charge you until you decide you want to purchase Bloomhound. You’re always in control. At any time during the trial, or when the trial is up, you can decide what you want to do. If you want to continue, we’ll ask for your credit card then. If not, no problem — you can click a single button to cancel, no questions asked.</p>

      <p><strong>What if we need more than 30 days to evaluate Bloomhound?</strong></p>
      <p>If you haven’t purchased Bloomhound before the trial ends, and you need a little more time, just let us know. We’re happy to extend your trial.</p>
    </div>
  </div>
</div>
@endsection
