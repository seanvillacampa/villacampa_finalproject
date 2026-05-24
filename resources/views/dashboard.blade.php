<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="shell">

  {{-- ── SIDEBAR ── --}}
  <aside class="sidebar">

    <div class="sidebar-logo">
  
      <div class="logo-text">
        Farm Supply Management

      </div>
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

  {{-- ── MAIN ── --}}
  <div class="main">

    {{-- Topbar --}}
    <header class="topbar">
      <div>
        <h1 class="topbar-title">Dashboard</h1>
        <p class="topbar-sub">{{ now()->format('l, F j, Y') }}</p>
      </div>
    
    </header>

    <div class="page-body">

      {{-- ── KPI CARDS ── --}}
      <div class="kpi-grid">

        <div class="kpi-card">
          <div class="kpi-top">
            
            
          </div>
          <div class="kpi-val">{{ $totalItems }}</div>
          <div class="kpi-lbl">Total Items</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-top">
            
            <span class="kpi-badge badge-orange">Low</span>
          </div>
          <div class="kpi-val">{{ $lowStockItems }}</div>
          <div class="kpi-lbl">Low Stock</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-top">
            
            <span class="kpi-badge badge-red">Critical</span>
          </div>
          <div class="kpi-val">{{ $noStockItems }}</div>
          <div class="kpi-lbl">No Stock</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-top">
            
            
          </div>
          <div class="kpi-val">{{ $totalAnimals }}</div>
          <div class="kpi-lbl">Active Animals</div>
        </div>

        <div class="kpi-card">
          <div class="kpi-top">
            
           
          </div>
          <div class="kpi-val">{{ $totalSuppliers }}</div>
          <div class="kpi-lbl">Suppliers</div>
        </div>

      </div>

      {{-- ── CONTENT GRID ── --}}
      <div class="content-grid">

        {{-- LEFT --}}
        <div class="col">

        {{-- Recent Stock Outs --}}
<div class="card">
  <div class="card-head">
    <div>
      <h2>Recent Stock Outs</h2>
      <p>Latest supply usage records</p>
    </div>
  </div>
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Item</th>
          <th>Reason</th>
          <th>Animal</th>
          <th>Qty</th>
          <th>By</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentStockOuts as $so)
        <tr>
          <td>{{ $so->item->name ?? '—' }}</td>
          <td><span class="badge badge-blue">{{ $so->reason }}</span></td>
          <td>
            @if($so->animal)
              {{ $so->animal->tag_number }}
            @else
              —
            @endif
          </td>
          <td>{{ number_format($so->quantity, 1) }} {{ $so->item->unit->abbreviation ?? '' }}</td>
          <td>{{ $so->user->name ?? '—' }}</td>
          <td>{{ $so->date->format('M d') }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="tbl-empty">No stock-out records yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

          {{-- Expiring Items --}}
          <div class="card">
            <div class="card-head">
              <div>
                <h2>Expiring Within 30 Days</h2>
                <p>Items approaching expiry</p>
              </div>
              
            </div>
            <div class="tbl-wrap">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th>Expiry Date</th>
                    <th>Days Left</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($expiringItems as $sm)
                  @php $days = now()->diffInDays($sm->expiry_date, false); @endphp
                  <tr>
                    <td>{{ $sm->item->name ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($sm->expiry_date)->format('M d, Y') }}</td>
                    <td>
                      <span class="badge {{ $days <= 7 ? 'badge-red' : 'badge-orange' }}">
                        {{ $days }}d
                      </span>
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="3" class="tbl-empty">No items expiring soon.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

        </div>

        {{-- RIGHT --}}
        <div class="col">

          {{-- Low Stock Alerts --}}
          <div class="card">
            <div class="card-head">
              <div>
                <h2>Low Stock Alerts</h2>
                <p>Items at or below reorder level</p>
              </div>
             
            </div>
            <div class="tbl-wrap">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th>Stock</th>
                    <th>Unit</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($lowStockAlerts as $item)
                  <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->current_stock }}</td>
                    <td>{{ $item->unit->abbreviation ?? '—' }}</td>
                    <td>
                      @if($item->current_stock == 0)
                        <span class="badge badge-red">No Stock</span>
                      @else
                        <span class="badge badge-orange">Low</span>
                      @endif
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="4" class="tbl-empty">All stock levels healthy. ✓</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          {{-- Upcoming Health Schedules --}}
          <div class="card">
            <div class="card-head">
              <div>
                <h2>Health Schedules</h2>
                <p>Due within the next 7 days</p>
              </div>
              
            </div>
            <div class="tbl-wrap">
              <table class="tbl">
                <thead>
                  <tr>
                    <th>Animal</th>
                    <th>Type</th>
                    <th>Scheduled</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($upcomingHealth as $log)
                  <tr>
                    <td>{{ $log->animal->name ?? 'Tag #' . ($log->animal->tag_number ?? '—') }}</td>
                    <td><span class="badge badge-blue">{{ ucfirst($log->type) }}</span></td>
                    <td>{{ \Carbon\Carbon::parse($log->next_schedule_date)->format('M d, Y') }}</td>
                  </tr>
                  @empty
                  <tr><td colspan="3" class="tbl-empty">No upcoming schedules.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>
</div>

</body>
</html>