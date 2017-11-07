<nav class="navbar is-dark">
  <div class="navbar-brand">
    <a class="navbar-item" href="{{ route('home') }}">
      <strong>Bloomhound</strong>
    </a>

    <div class="navbar-burger burger" data-target="navMenubd-example">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>

  <div class="navbar-menu">
    <div class="navbar-start">
      <a class="navbar-item" href="{{ route('customers.index') }}">Customers</a>
      <a class="navbar-item" href="{{ route('contacts.index') }}">Contacts</a>
      <a class="navbar-item" href="{{ route('events.index') }}">Events</a>
      <a class="navbar-item" href="{{ route('vendors.index') }}">Vendors</a>
      <a class="navbar-item" href="{{ route('flowers.index') }}">Flowers</a>
      <a class="navbar-item" href="{{ route('items.index') }}">Items</a>
    </div>

    <div class="navbar-end">
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          {{ Auth::user()->name }}
        </a>

        <div class="navbar-dropdown is-right">
          <a class="navbar-item" href="{{ route('my.profile') }}">Profile</a>
          <a class="navbar-item" href="{{ route('account.settings') }}">Account Settings</a>

          <hr class="navbar-divider">

          <a class="navbar-item" href="{{ url('/logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            Logout
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>

<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
