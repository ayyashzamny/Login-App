<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notice;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Fetch the total number of users
        $totalUsers = User::count();

        // Fetch the total number of notices
        $totalNotices = Notice::count();

        // Return the view with the fetched data
        return view('admin.dashboard', compact('totalUsers', 'totalNotices'));
    }
}
