<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machinery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MachineryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display all machinery (with pagination and grouped by name)
    public function index()
    {
        $machineries = Machinery::select('name', 'model', 'image')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->groupBy('name', 'model', 'image')
            ->with('allItems') // Eager load all items if you set up a relationship
            ->paginate(10);

        // Collect all individual items for grouping in the view
        $allMachineries = Machinery::all();

        return view('machinery.index', compact('machineries', 'allMachineries'));
    }

    // Display machinery for field supervisor
    public function fsindex()
    {
        $machineries = Machinery::all();
        return view('machinery.fsindex', compact('machineries'));
    }

    // Show the form to create a new machinery record
    public function create()
    {
        return view('machinery.create');
    }

    // Store a new machinery record
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'model'       => 'required|string|max:255',
            'reg_num'     => 'nullable|string|max:50|unique:machineries,reg_num',
            'status'      => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Machinery::create([
            'name'        => $request->name,
            'model'       => $request->model,
            'reg_num'     => $request->reg_num,
            'status'      => $request->status,
        ]);

        if ($request->wantsJson()) {
            session()->flash('success', 'Machinery added successfully!');
            return response()->json(['success' => true]);
        }

        return redirect()->route('machinery.fsindex')->with('success', 'Machinery added successfully!');
    }

    // Show a single machinery record
    public function show($id)
    {
        $machinery = Machinery::findOrFail($id);
        return view('machinery.show', compact('machinery'));
    }

    // Show details of all machinery records grouped by the same name
    public function details($id)
    {
        $machinery = Machinery::findOrFail($id);
        $machineryGroup = Machinery::where('name', $machinery->name)
            ->select('id', 'name', 'model', 'quantity', 'status', 'image', 'reg_num')
            ->get()
            ->groupBy('name')
            ->map(function ($group) {
                $totalQuantity = $group->sum('quantity');
                $statuses = $group->pluck('status', 'status')->countBy();
                $primaryStatus = $statuses->sortDesc()->keys()->first();
                return [
                    'item' => $group->first(),
                    'total_quantity' => $totalQuantity,
                    'all_items' => $group->all(),
                    'statuses' => $statuses->all(),
                    'primary_status' => $primaryStatus
                ];
            })->first();

        return view('machinery.details', [
            'machinery' => $machinery,
            'machineryGroup' => $machineryGroup
        ]);
    }

    // Show the form to edit an existing machinery record
    public function edit($id)
    {
        $machinery = Machinery::findOrFail($id);
        return view('machinery.edit', compact('machinery'));
    }

    // Update an existing machinery record
    public function update(Request $request, $id)
    {
        $machinery = Machinery::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'model'    => 'required|string|max:255',
            'reg_num'  => 'nullable|string|max:50|unique:machineries,reg_num,' . $id,
            'status'   => 'required|string|max:255',
        ]);

        $machinery->update([
            'name'     => $request->name,
            'model'    => $request->model,
            'reg_num'  => $request->reg_num,
            'status'   => $request->status,
        ]);

        return redirect()->route('machinery.fsindex')->with('success', 'Machinery updated successfully!');
    }

    // Delete a machinery record
    public function destroy(Request $request, Machinery $machinery)
    {
        $machinery->delete();

        $redirectRoute = str_starts_with($request->path(), 'fs/') 
            ? 'machinery.fsindex' 
            : 'machinery.fsindex';

        return redirect()->route($redirectRoute)->with('success', 'Machinery deleted successfully!');
    }
}