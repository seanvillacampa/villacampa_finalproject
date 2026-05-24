<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Farm Supply' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="shell">

  <aside class="sidebar">
    <div class="sidebar-logo">
      
      <div class="logo-text">Farm Supply Management</div>
    </div>

    <nav class="sidebar-nav">

      <a href="/dashboard"
         class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
         Dashboard
      </a>

      <div class="nav-label">Inventory</div>

      <a href="/items"
         class="nav-item {{ request()->is('items*') ? 'active' : '' }}">
         Items
      </a>

      <a href="/categories"
         class="nav-item {{ request()->is('categories*') ? 'active' : '' }}">
         Categories
      </a>

      <a href="/units"
         class="nav-item {{ request()->is('units*') ? 'active' : '' }}">
         Units of Measure
      </a>

      <a href="/stock-outs"
         class="nav-item {{ request()->is('stock-outs*') ? 'active' : '' }}">
         Stock Out
      </a>

      <div class="nav-label">Livestock</div>

      <a href="/animals"
         class="nav-item {{ request()->is('animals*') ? 'active' : '' }}">
         Animals
      </a>

      <a href="/breeds"
         class="nav-item {{ request()->is('breeds*') ? 'active' : '' }}">
         Breeds
      </a>

      <a href="/health-logs"
         class="nav-item {{ request()->is('health-logs*') ? 'active' : '' }}">
         Health Logs
      </a>

      <a href="/feed-logs"
         class="nav-item {{ request()->is('feed-logs*') ? 'active' : '' }}">
         Feed Logs
      </a>

      <div class="nav-label">Suppliers</div>

      <a href="/suppliers"
         class="nav-item {{ request()->is('suppliers*') ? 'active' : '' }}">
         Suppliers
      </a>

      <a href="/purchases"
         class="nav-item {{ request()->is('purchases*') ? 'active' : '' }}">
         Purchases
      </a>

      @if(auth()->user()->role === 'admin')
      <div class="nav-label">Administration</div>
      <a href="/admin/users"
         class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
         Manage Users
      </a>
      <a href="/admin"
         class="nav-item {{ request()->is('admin') ? 'active' : '' }}">
         Admin Panel
        
      </a>
      @endif

    </nav>

    <div class="sidebar-footer">
      <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
      <div class="user-info">
        <strong>{{ auth()->user()->name }}</strong>
        <span>{{ ucfirst(auth()->user()->role) }}</span>
      </div>
      <form method="POST" action="{{ route('logout') }}" style="margin:0 0 0 auto;">
        @csrf
        <button type="submit" class="logout-btn" title="Sign out">⏻</button>
      </form>
    </div>
  </aside>

  <div class="main">
    <header class="topbar">
      <div>
        <div class="topbar-title">{{ $title ?? 'Farm Supply' }}</div>
        
      </div>
    </header>

    <div class="page-body">
      {{ $slot }}
    </div>
  </div>


<script>
function combobox(inputId, dropdownId, selectId, onChange) {
    const input    = document.getElementById(inputId);
    const dropdown = document.getElementById(dropdownId);
    const select   = selectId ? document.getElementById(selectId) : null;

    input.addEventListener('input', () => {
        const term = input.value.toLowerCase();
        dropdown.querySelectorAll('.combobox-option').forEach(opt => {
            const search = (opt.dataset.search || opt.textContent).toLowerCase();
            opt.style.display = (!term || search.includes(term)) ? '' : 'none';
        });
        dropdown.classList.add('open');
    });
    input.addEventListener('focus', () => dropdown.classList.add('open'));
    input.addEventListener('blur', () => {
        setTimeout(() => dropdown.classList.remove('open'), 150);
    });
    dropdown.querySelectorAll('.combobox-option').forEach(opt => {
        opt.addEventListener('mousedown', () => {
            const val   = opt.dataset.value ?? '';
            const label = opt.dataset.label || opt.textContent.trim();
            input.value = val ? label : '';
            if (select) {
                select.value = val;
                select.dispatchEvent(new Event('change')); // fire native change too
            }
            dropdown.classList.remove('open');
            if (onChange) onChange(val, opt); // call the callback
        });
    });
}
</script>

</div>
</body>
</html>