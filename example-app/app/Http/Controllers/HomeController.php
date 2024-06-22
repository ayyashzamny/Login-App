<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function getNotices()
    {
        $notices = Notice::all();
        return view('home', compact('notices'));
    }

}
