<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
$totalUsers  = User::count();
    $totalAdmins = User::where('name', 'admin')->exists() ? User::role('admin')->count() : 0;
    $totalStaff  = User::where('name', 'staff')->exists() ? User::role('staff')->count() : 0;

        return view('admin.index', compact(
            'totalUsers',
            'totalAdmins',
            'totalStaff'
        ));
    }
}