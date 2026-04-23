<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('dashboards.customer.index', [
            'maintenance_blocks' => \App\Models\Maintenance::where('status', 'sedang_dikerjakan')->pluck('block_name')->toArray(),
        ]);
    }

    public function createGrave()
    {
        return view('dashboards.customer.create_grave');
    }

    public function storeGrave(Request $request)
    {
        $validated = $request->validate([
            'block_name' => 'required',
            'grave_number' => 'required',
            'buried_name' => 'nullable',
            'notes' => 'nullable'
        ]);

        // Mapping block to religion
        $blockReligions = [
            'Blok A' => 'islam', 'Blok B' => 'islam',
            'Blok C' => 'protestan', 'Blok D' => 'protestan',
            'Blok E' => 'katolik', 'Blok F' => 'katolik',
            'Blok G' => 'hindu', 'Blok H' => 'hindu',
            'Blok I' => 'budha', 'Blok J' => 'budha',
            'Blok K' => 'konghucu', 'Blok L' => 'konghucu',
            'Blok M' => 'umum', 'Blok N' => 'umum'
        ];

        $validated['religion'] = $blockReligions[$request->block_name] ?? 'umum';
        $validated['heir_name'] = auth()->user()->name;
        $validated['status'] = 'booked'; 

        $grave = \App\Models\Grave::create($validated);

        return redirect()->route('customer.order.detail', $grave->id);
    }

    public function orderDetail(\App\Models\Grave $grave)
    {
        return view('dashboards.customer.order_detail', compact('grave'));
    }

    public function uploadProof(Request $request, \App\Models\Grave $grave)
    {
        $request->validate([
            'proof' => 'required|image|max:2048'
        ]);

        $path = $request->file('proof')->store('proofs', 'public');

        \App\Models\Payment::create([
            'grave_id' => $grave->id,
            'user_id' => auth()->id(),
            'amount' => 2500000,
            'payment_date' => now(),
            'proof' => $path,
            'status' => 'pending',
            'notes' => 'Pembayaran awal pemesanan makam'
        ]);

        return redirect()->route('customer.order.thank_you');
    }

    public function thankYou()
    {
        return view('dashboards.customer.thank_you');
    }

    public function payments()
    {
        $payments = \App\Models\Payment::where('user_id', auth()->id())
            ->with('grave')
            ->latest()
            ->get();
            
        return view('dashboards.customer.payments', compact('payments'));
    }
}
