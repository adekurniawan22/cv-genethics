<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function owner()
    {
        return view('menu.dashboard.owner', [
            'title' => 'Owner Dashboard',
        ]);
    }

    public function admin()
    {
        return view('menu.dashboard.admin', [
            'title' => 'Admin Dashboard',
        ]);
    }
}
