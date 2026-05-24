<x-layouts.app-dash>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User — AgriSys</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body { background: var(--cr); min-height: 100vh; }
        .admin-shell { display: flex; min-height: 100vh; }
        .admin-side { width: 220px; background: var(--g); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; flex-shrink: 0; }
        .admin-side::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--ga), var(--go)); }
        .aside-logo { padding: 24px 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 8px; }
        .aside-logo strong { display: block; font-size: 1.05rem; font-weight: 700; color: #fff; }
        .aside-logo span { font-size: .72rem; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,0.35); }
        .aside-nav { flex: 1; padding: 0 10px; }
        .aside-lbl { font-size: .68rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; color: rgba(255,255,255,0.25); padding: 12px 10px 4px; }
        .aside-link { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; color: rgba(255,255,255,0.5); text-decoration: none; font-size: .84rem; margin-bottom: 2px; transition: all .15s; }
        .aside-link:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }
        .aside-link.active { background: rgba(76,175,96,0.18); color: var(--ga); }
        .aside-foot { padding: 14px 10px; border-top: 1px solid rgba(255,255,255,0.07); }
        .aside-user { display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 8px; background: rgba(255,255,255,0.05); }
        .aside-avatar { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, var(--gm), var(--go)); display: flex; align-items: center; justify-content: center; font-size: .75rem; font-weight: 600; color: #fff; flex-shrink: 0; }
        .aside-uname { font-size: .8rem; color: rgba(255,255,255,0.75); font-weight: 500; }
        .aside-urole { font-size: .68rem; color: rgba(255,255,255,0.3); }
        .aside-logout { margin-left: auto; background: none; border: none; color: rgba(255,255,255,0.3); cursor: pointer; font-size: .8rem; padding: 4px; border-radius: 5px; transition: color .15s; }
        .aside-logout:hover { color: #ff7070; }
        .admin-main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; }
        .admin-topbar { height: 60px; background: var(--w); border-bottom: 1px solid var(--bd); display: flex; align-items: center; padding: 0 28px; position: sticky; top: 0; z-index: 50; }
        .admin-topbar h1 { font-size: 1.1rem; font-weight: 600; color: var(--g); flex: 1; }
        .back-link { font-size: .8rem; color: var(--gm); text-decoration: none; font-weight: 500; }
        .back-link:hover { color: var(--g); }
        .admin-body { padding: 28px 32px; flex: 1; }
    </style>
</head>
<body>

<div class="admin-shell">

    <aside class="admin-side">
        <div class="aside-logo">
            <strong>AgriSys</strong>
            <span>Admin Panel</span>
        </div>
        <nav class="aside-nav">
            <div class="aside-lbl">Admin</div>
            <a href="{{ route('admin') }}" class="aside-link">Overview</a>
            <a href="{{ route('admin.users') }}" class="aside-link active">Manage Users</a>
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

    <div class="admin-main">
        <header class="admin-topbar">
            <h1>Edit User — {{ $user->name }}</h1>
            <a href="{{ route('admin.users') }}" class="back-link">Back to Users</a>
        </header>

        <div class="admin-body">

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div style="max-width:520px;">
                <div class="card">
                    <div class="card-head"><h2>Edit — {{ $user->name }}</h2></div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf @method('PUT')
                            <div class="fg">
                                <label>Full Name</label>
                                <input type="text" name="name"
                                       value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="fg">
                                <label>Email Address</label>
                                <input type="email" name="email"
                                       value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="fg">
                                <label>Role</label>
                                <select name="role" required>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                            </div>
                            <div class="fg">
                                <label>New Password <span class="hint" style="display:inline;">(leave blank to keep current)</span></label>
                                <input type="password" name="password" placeholder="Min. 8 characters">
                            </div>
                            <div class="fg">
                                <label>Confirm New Password</label>
                                <input type="password" name="password_confirmation" placeholder="Repeat password">
                            </div>
                            <div class="btn-row">
                                <button type="submit" class="btn btn-primary">Update User</button>
                                <a href="{{ route('admin.users') }}" class="btn btn-outline">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>

</x-layouts.app-dash>