<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
  // Dashboard
  public function index()
  {
    return view('backend.home');
  }

  // Reports
  public function reports()
  {
    return view('backend.reports.index');
  }

  // Settings
  public function settings()
  {
    return view('backend.settings.index');
  }
}
