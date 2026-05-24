<x-layouts.app-dash>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — AgriSys</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { background: var(--cr); min-height: 100vh; }

        .admin-shell { display: flex; min-height: 100vh; }

        .admin-side {
            width: 220px;
            background: var(--g);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            flex-shrink: 0;
        }
        .admin-side::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--ga), var(--go));
        }
        .aside-logo {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 8px;
        }
        .aside-logo strong {
            display: block;
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: .01em;
        }
        .aside-logo span {
            font-size: .72rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
        }
        .aside-nav { flex: 1; padding: 0 10px; }
        .aside-lbl {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 12px 10px 4px;
        }
        .aside-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: .84rem;
            margin-bottom: 2px;
            transition: all .15s;
        }
        .aside-link:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }
        .aside-link.active { background: rgba(76,175,96,0.18); color: var(--ga); }
        .aside-foot {
            padding: 14px 10px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }
        .aside-user {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            border-radius: 8px;
            background: rgba(255,255,255,0.05);
        }
        .aside-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gm), var(--go));
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 600; color: #fff;
            flex-shrink: 0;
        }
        .aside-uname { font-size: .8rem; color: rgba(255,255,255,0.75); font-weight: 500; }
        .aside-urole { font-size: .68rem; color: rgba(255,255,255,0.3); }
        .aside-logout {
            margin-left: auto;
            background: none; border: none;
            color: rgba(255,255,255,0.3);
            cursor: pointer; font-size: .8rem;
            padding: 4px; border-radius: 5px;
            transition: color .15s;
        }
        .aside-logout:hover { color: #ff7070; }

        .admin-main {
            margin-left: 220px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .admin-topbar {
            height: 60px;
            background: var(--w);
            border-bottom: 1px solid var(--bd);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .admin-topbar h1 { font-size: 1.1rem; font-weight: 600; color: var(--g); flex: 1; }
        .admin-topbar p  { font-size: .78rem; color: var(--ts); }
        .back-link { font-size: .8rem; color: var(--gm); text-decoration: none; font-weight: 500; }
        .back-link:hover { color: var(--g); }
        .admin-body { padding: 28px 32px; flex: 1; }
    </style>
</head>
<body>

<div class="admin-shell">

    {{-- SIDEBAR --}}
    <aside class="admin-side">
        <div class="aside-logo">
            <strong>AgriSys</strong>
            <span>Admin Panel</span>
        </div>

        <nav class="aside-nav">
            <div class="aside-lbl">Admin</div>
            <a href="{{ route('admin') }}"
               class="aside-link {{ request()->routeIs('admin') ? 'active' : '' }}">
                Overview
            </a>
            <a href="{{ route('admin.users') }}"
               class="aside-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                Manage Users
            </a>

            <div class="aside-lbl" style="margin-top:8px;">System</div>
            <a href="{{ route('dashboard') }}" class="aside-link">Back to Dashboard</a>
        </nav>

        <div class="aside-foot">
            <div class="aside-user">
                <div class="aside-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                <div>
                    <div class="aside-uname">{{ auth()->user()->name }}</div>
                    <div class="aside-urole">{{ auth()->user()->role }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="aside-logout" title="Sign out">&#x23FB;</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="admin-main">
        <header class="admin-topbar">
            <h1>Overview</h1>
            <a href="{{ route('dashboard') }}" class="back-link">Back to Dashboard</a>
        </header>

        <div class="admin-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- KPI CARDS --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:28px;">

                <div class="card" style="padding:20px;">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--ts);margin-bottom:8px;">Total Users</div>
                    <div style="font-size:2rem;font-weight:700;color:var(--g);line-height:1;">{{ $totalUsers }}</div>
                    <div style="font-size:.78rem;color:var(--ts);margin-top:4px;">All registered accounts</div>
                </div>

                <div class="card" style="padding:20px;">
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--ts);margin-bottom:8px;">Administrators</div>
                    <div style="font-size:2rem;font-weight:700;color:var(--g);line-height:1;">{{ $totalAdmins }}</div>
                    <div style="font-size:.78rem;color:var(--ts);margin-top:4px;">Full system access</div>
                </div>

               

            </div>

            {{-- RECENT USERS --}}
            <div class="card">
                <div class="sec-head">
                    <h3>Recent Registrations</h3>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline btn-sm">Manage All</a>
                </div>
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Verified</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                            <tr>
                                <td style="font-weight:600;">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge badge-red">Admin</span>
                                    @else
                                        <span class="badge badge-green">Staff</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge badge-green">Verified</span>
                                    @else
                                        <span class="badge badge-orange">Unverified</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>

</x-layouts.app-dash>