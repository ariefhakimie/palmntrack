<?php

namespace App\Http\Controllers;

use App\Models\UsageRecord;
use App\Models\User;
use App\Models\Machinery;
use App\Models\Equipment;
use Illuminate\Http\Request;

class UsageRecordController extends Controller
{
    public function index()
    {
        $records = UsageRecord::with(['user', 'machinery', 'equipment'])->latest()->get();
        $users = User::where('role', 'worker')->get();
        $machineries = Machinery::all();
        $equipment = Equipment::all();

        return view('usagerecords.index', compact('records', 'users', 'machineries', 'equipment'));
    }

    public function create()
    {
        $users = User::where('role', 'worker')->get();
        $machineries = Machinery::all();
        $equipment = Equipment::all();

        return view('usagerecords.create', compact('users', 'machineries', 'equipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if (!User::where('id', $value)->where('role', 'worker')->exists()) {
                        $fail('The selected user must have the worker role.');
                    }
                },
            ],
            'item_type' => 'required|in:machinery,equipment',
            'machinery_id' => 'required_if:item_type,machinery|nullable|exists:machineries,id',
            'equipment_id' => 'required_if:item_type,equipment|nullable|exists:equipment,id',
            'usage_timestamps' => 'required|date',
        ]);

        // Set the appropriate ID based on item_type
        $data = [
            'user_id' => $validated['user_id'],
            'machinery_id' => $validated['item_type'] === 'machinery' ? $validated['machinery_id'] : null,
            'equipment_id' => $validated['item_type'] === 'equipment' ? $validated['equipment_id'] : null,
            'usage_timestamps' => date('Y-m-d H:i:s', strtotime($validated['usage_timestamps'])),
        ];

        UsageRecord::create($data);

        return redirect()->route('usagerecords.index')->with('success', 'Usage record created successfully.');
    }

    public function edit(UsageRecord $usagerecord)
    {
        $users = User::where('role', 'worker')->get();
        $machineries = Machinery::all();
        $equipment = Equipment::all();

        return view('usagerecords.edit', compact('usagerecord', 'users', 'machineries', 'equipment'));
    }

    public function update(Request $request, UsageRecord $usagerecord)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if (!User::where('id', $value)->where('role', 'worker')->exists()) {
                        $fail('The selected user must have the worker role.');
                    }
                },
            ],
            'item_type' => 'required|in:machinery,equipment',
            'machinery_id' => 'required_if:item_type,machinery|nullable|exists:machineries,id',
            'equipment_id' => 'required_if:item_type,equipment|nullable|exists:equipment,id',
            'usage_timestamps' => 'required|date',
        ]);

        $data = [
            'user_id' => $validated['user_id'],
            'machinery_id' => $validated['item_type'] === 'machinery' ? $validated['machinery_id'] : null,
            'equipment_id' => $validated['item_type'] === 'equipment' ? $validated['equipment_id'] : null,
            'usage_timestamps' => date('Y-m-d H:i:s', strtotime($validated['usage_timestamps'])),
        ];

        $usagerecord->update($data);

        return redirect()->route('usagerecords.index')->with('success', 'Usage record updated successfully.');
    }

    public function destroy(UsageRecord $usagerecord)
    {
        $usagerecord->delete();

        return redirect()->route('usagerecords.index')->with('success', 'Usage record deleted successfully.');
    }
}