<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;

class AdminController extends Controller
{
    public function dashboard()
    {
        $notifikasis = Notifikasi::with('user', 'petugas')->latest()->get();
        return view('admin.dashboard', compact('notifikasis'));
    }

    public function status()
    {
        return view('admin.status');
    }

    public function location()
    {
        return view('admin.location');
    }

    public function history()
    {
        return view('admin.history');
    }
}
