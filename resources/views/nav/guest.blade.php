<nav class="navbar">
  <div class="navbar-brand">
    <a class="navbar-item" href="/">
      Logo
    </a>

    <div class="navbar-burger burger" data-target="navMenubd-example">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>

  <div class="navbar-menu">
    <div class="navbar-start">

    </div>

    <div class="navbar-end">
			<a class="navbar-item " href="{{ route('login') }}">Sign In</a>
			<div class="navbar-item">
				<a class="button is-primary" href="{{ route('register') }}">
          <span>Try it FREE</span>
        </a>
			</div>
    </div>
  </div>
</nav>