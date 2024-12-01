<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporan = Laporan::with('user')->get();
        return response()->json(['data' => $laporan]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id'    => 'required|integer|exists:users,id',
            'description' => 'required|string',
            'image'      => 'nullable|string|max:255',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'datetime'   => 'required|date',
            'status'     => 'nullable|in:pending,diproses,selesai',
        ]);

        // Simpan data ke database
        $laporan = Laporan::create([
            'user_id'    => $validated['user_id'],
            'description' => $validated['description'],
            'image'      => $validated['image'] ?? null,
            'latitude'   => $validated['latitude'],
            'longitude'  => $validated['longitude'],
            'datetime'   => $validated['datetime'],
            'status'     => $validated['status'] ?? 'pending',
        ]);

        // Kembalikan respons sukses
        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dibuat',
            'data'    => $laporan,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Laporan $laporan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Laporan $laporan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Laporan $laporan, $id)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'nullable|in:pending,diproses,selesai',
            'datetime' => 'nullable|date',
            'image' => 'nullable|string|max:255'
        ]);

        // Cari laporan berdasarkan ID
        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        // Perbarui data laporan
        $laporan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil diperbarui',
            'data' => $laporan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laporan $laporan,$id)
    {
        // Cari laporan berdasarkan ID
        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        // Hapus laporan
        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus'
        ], 200);
    }
}
