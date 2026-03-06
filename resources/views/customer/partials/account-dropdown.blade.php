<div class="account-menu">
  <button type="button" class="nav-pill solid account-trigger" aria-label="Profile menu" title="Profile menu">
    <i class="bi bi-person-circle"></i>
  </button>
  <div class="account-dropdown">
    <a href="{{ route('customer.account.profile') }}" class="account-link">Profile Settings</a>
    <a href="{{ route('customer.account.addresses') }}" class="account-link">Manage Addresses</a>
    <form action="{{ route('logout') }}" method="post" class="account-signout-form">
      @csrf
      <button type="submit" class="account-signout">Sign Out</button>
    </form>
  </div>
</div>
