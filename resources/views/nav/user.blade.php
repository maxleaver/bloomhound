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
      <a href="{{ route('customers.index') }}"
        class="navbar-item {{ request()->is('customers*') ? 'is-active' : '' }}">
        Customers
      </a>
      <a href="{{ route('contacts.index') }}"
        class="navbar-item {{ request()->is('contacts*') ? 'is-active' : '' }}" >
        Contacts
      </a>
      <a href="{{ route('events.index') }}"
        class="navbar-item {{ request()->is('events*') ? 'is-active' : '' }}" >
        Events
      </a>
      <a href="{{ route('vendors.index') }}"
        class="navbar-item {{ request()->is('vendors*') ? 'is-active' : '' }}" >
        Vendors
      </a>
      <a href="{{ route('flowers.index') }}"
        class="navbar-item {{ request()->is('flowers*') ? 'is-active' : '' }}" >
        Flowers
      </a>
      <a href="{{ route('items.index') }}"
        class="navbar-item {{ request()->is('items*') ? 'is-active' : '' }}" >
        Items
      </a>
    </div>

    <div class="navbar-end">
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          {{ Auth::user()->name }}
        </a>

        <div class="navbar-dropdown is-right">
          <a href="{{ route('my.profile') }}" class="navbar-item" >Profile</a>
          <a href="{{ route('account.settings') }}" class="navbar-item" >Account Settings</a>

          <hr class="navbar-divider">

          <a href="{{ url('/logout') }}"
            class="navbar-item"
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
