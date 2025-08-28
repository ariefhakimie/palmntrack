<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commodity;
use App\Models\Machinery;
use App\Models\Equipment;

class DashboardController extends Controller
{
    // 🛠️ ADMIN DASHBOARD
    public function adminDashboard()
    {
        $commodities = Commodity::latest()->take(4)->get();
        $machineries = Machinery::latest()->take(4)->get();
        $equipments = Equipment::latest()->take(4)->get();

        return view('admin.users.dashboard', compact('commodities', 'machineries', 'equipments'));
    }

    // 🔧 SUPERVISOR DASHBOARD
    public function supervisorDashboard()
    {
        $commodities = Commodity::latest()->take(4)->get();
        $machineries = Machinery::latest()->take(4)->get();
        $equipments = Equipment::latest()->take(4)->get();

        return view('fieldsupervisor.dashboard', compact('commodities', 'machineries', 'equipments'));
    }


    // Public dashboard (no login required)
    public function publicDashboard()
    {
        // Dapatkan latest 4 juga untuk paparan awam
        $commodities = Commodity::latest()->take(4)->get();
        $machineries = Machinery::latest()->take(4)->get();
        $equipments = Equipment::latest()->take(4)->get();

        return view('public.dashboard', compact('commodities', 'machineries', 'equipments'));
    }
}
