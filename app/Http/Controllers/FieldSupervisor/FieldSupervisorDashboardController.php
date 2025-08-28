<?php

namespace App\Http\Controllers\FieldSupervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FieldSupervisorDashboardController extends Controller
{
    public function index()
    {
        return view('fieldsupervisor.dashboard');
    }
}
