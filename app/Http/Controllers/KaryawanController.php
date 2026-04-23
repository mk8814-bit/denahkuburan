<?php

namespace App\Http\Controllers;

use App\Models\Grave;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        return view('dashboards.karyawan.index', [
            'graves' => Grave::all(),
            'maintenance_blocks' => \App\Models\Maintenance::where('status', 'sedang_dikerjakan')->pluck('block_name')->toArray(),
        ]);
    }

    public function graves(Request $request)
    {
        $query = Grave::query();

        if ($request->has('search')) {
            $query->where('buried_name', 'like', '%' . $request->search . '%')
                  ->orWhere('grave_number', 'like', '%' . $request->search . '%');
        }

        return view('dashboards.karyawan.graves', [
            'graves' => $query->latest()->get(),
        ]);
    }

    public function updateStatus(Request $request, Grave $grave)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,occupied,booked',
        ]);

        $grave->update($validated);

        return back()->with('success', 'Status makam berhasil diperbarui.');
    }

    public function storeGrave(Request $request)
    {
        $validated = $request->validate([
            'block_name' => 'required',
            'grave_number' => 'required',
            'buried_name' => 'nullable',
            'burial_date' => 'nullable|date',
            'heir_name' => 'nullable',
            'heir_contact' => 'nullable',
        ]);

        $validated['status'] = $validated['buried_name'] ? 'occupied' : 'available';

        Grave::create($validated);

        return back()->with('success', 'Data makam berhasil ditambahkan.');
    }
}
