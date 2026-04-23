<?php

namespace App\Http\Controllers;

use App\Models\Grave;
use Illuminate\Http\Request;

class GraveController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'block_name' => 'required',
            'grave_number' => 'required',
            'buried_name' => 'nullable',
            'burial_date' => 'nullable|date',
            'heir_name' => 'nullable',
            'heir_contact' => 'nullable',
        ]);

        Grave::create($validated);

        return back()->with('success', 'Makam baru berhasil ditambahkan.');
    }
}
