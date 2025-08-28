<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // ✅ Pastikan hanya user yang login boleh akses
    }

    // ✅ Display all equipment (with pagination)
    public function index()
    {
        $equipment = Equipment::paginate(10);
        return view('equipment.index', compact('equipment'));
    }

    // ✅ Display equipment for field supervisor (fsindex)
    public function fsindex()
    {
        $equipment = Equipment::all();
        return view('equipment.fsindex', compact('equipment'));
    }

    // ✅ Show the form to create a new equipment
    public function create()
    {
        return view('equipment.create');
    }

    // ✅ Store a new equipment (with image upload)
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'model'    => 'required|string|max:255',
            'status'   => 'required|string|max:255',
        ]);

        Equipment::create([
            'name'     => $request->name,
            'model'    => $request->model,
            'status'   => $request->status,
        ]);

        return redirect()->route('equipment.fsindex')->with('success', 'Equipment added successfully!');
    }

    // ✅ Show a single equipment
    public function show($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('equipment.show', compact('equipment'));
    }

    // ✅ Show the form to edit an existing equipment
    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('equipment.edit', compact('equipment'));
    }

    // ✅ Update an existing equipment
    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'model'    => 'required|string|max:255',
            'status'   => 'required|string|max:255',
        ]);

        $equipment->update([
            'name'     => $request->name,
            'model'    => $request->model,
            'status'   => $request->status,
        ]);

        return redirect()->route('equipment.fsindex')->with('success', 'Equipment updated successfully!');
    }

    // ✅ Delete an equipment
    public function destroy(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return redirect()->route('equipment.fsindex')->with('success', 'Equipment deleted successfully!');
    }
}
