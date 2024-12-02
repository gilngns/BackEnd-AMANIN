<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'user_id'    => 'required|integer|exists:users,id',
            'description' => 'required|string',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'datetime'   => 'required|date',
            'status'     => 'nullable|in:pending,diproses,selesai',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $laporan = Laporan::create([
            'user_id'    => $validated['user_id'],
            'description' => $validated['description'],
            'image'      => $imagePath,
            'latitude'   => $validated['latitude'],
            'longitude'  => $validated['longitude'],
            'datetime'   => $validated['datetime'],
            'status'     => $validated['status'] ?? 'pending',
        ]);

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
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,diproses,selesai',
        ]);

        // Temukan laporan berdasarkan ID
        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan',
            ], 404);
        }

        // Update hanya kolom status
        $laporan->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status laporan berhasil diperbarui',
            'data'    => $laporan->refresh(),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Laporan $laporan, $id)
    {
        $laporan = Laporan::find($id);

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dihapus'
        ], 200);
    }
}
