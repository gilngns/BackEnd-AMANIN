<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ReportsController extends Controller
{
    public function index()
    {
        $reports = Report::with('user')->get();
        return response()->json(['data' => $reports]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
    $validated = $request->validate([
        'laporan_id' => 'required|integer',
        'user_id' => 'required|integer',
        'description' => 'nullable|string|max:255',
        'datetime' => 'required|date'
    ]);

    // Simpan Reports baru ke database
    $reports = Report::create([
        'laporan_id' => $validated['laporan_id'],
        'user_id' => $validated['user_id'],
        'description' => $validated['description'] ?? null,
        'datetime' => $validated['datetime'] ?? now()
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Laporan berhasil dibuat',
        'data' => $reports
    ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'laporan_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'description' => 'nullable|string|max:255',
            'datetime' => 'nullable|date'
        ]);

        // Cari Reports berdasarkan ID
        $reports = Report::find($id);

        if (!$reports) {
            return response()->json([
                'success' => false,
                'message' => 'Reports tidak ditemukan'
            ], 404);
        }

        // Perbarui data Reports
        $reports->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Reports berhasil diperbarui',
            'data' => $reports
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Cari Reports berdasarkan ID
        $reports = Report::find($id);

        if (!$reports) {
            return response()->json([
                'success' => false,
                'message' => 'Reports tidak ditemukan'
            ], 404);
        }

        $reports->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reports berhasil dihapus'
        ], 200);
    }
}
