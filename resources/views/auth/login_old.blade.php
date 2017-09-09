@extends('layouts.app')

@section('nav')
    @include('nav.guest')
@endsection

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

    <div v-if="hasErrors" class="notification is-danger">
      Your email address or password was incorrect
    </div>

    <form
      method="POST"
      action="/api/login"
      @submit.prevent="login"
      @keydown="form.errors.clear($event.target.name)"
    >
      <div class="field">
        <label for="email" class="label is-medium">Email address</label>
        <div class="control has-icons-left has-icons-right">
          <input
            name="email"
            class="input is-large"
            v-model="form.email"
            type="email"
            placeholder="Your email address"
          />
          <span class="icon is-medium is-left">
            <i class="fa fa-envelope"></i>
          </span>
        </div>
      </div>

      <div class="field">
        <label for="password" class="label is-medium">Password</label>
        <div class="control has-icons-left has-icons-right">
          <input
            name="password"
            class="input is-large"
            v-model="form.password"
            type="password"
          />
          <span class="icon is-medium is-left">
            <i class="fa fa-lock"></i>
          </span>
        </div>
      </div>

      <div class="columns">
        <div class="column">
          <a class="is-pulled-right" v-on:click.prevent="toggleForm">Forgot your password?</a>
        </div>
      </div>

      <div class="columns">
        <div class="column">
          <div class="field">
            <div class="control has-text-centered">
              <button
                type="submit"
                class="button is-primary is-large"
                :disabled="hasErrors"
              >Login</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<login-form></login-form>

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
