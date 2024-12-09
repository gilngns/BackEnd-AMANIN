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
        $laporan = Laporan::with('user:id,name')->get();

        return response()->json([
            'success' => true,
            'data'    => $laporan->map(function ($item) {
                return [
                    'id'             => $item->id ?? 0, // Pastikan ID tidak null
                    'title'          => $item->title ?? 'Tidak ada judul', // Default jika null
                    'description'    => $item->description ?? 'Tidak ada deskripsi', // Default jika null
                    'user_id'        => $item->user ? $item->user->id : null, // Ganti ke user_id, null jika user tidak ada
                    'image_url'      => $item->image && file_exists(storage_path('app/public/' . $item->image))
                        ? asset('storage/' . $item->image)
                        : null,
                    'lokasi_kejadian' => $item->lokasi_kejadian ?? 'Tidak diketahui',
                    'latitude'       => $item->latitude ?? 0, // Default ke 0 jika null
                    'longitude'      => $item->longitude ?? 0, // Default ke 0 jika null
                    'datetime'       => $item->datetime ?? now(), // Default ke waktu saat ini jika null
                    'status'         => $item->status ?? 'Tidak diketahui',
                ];
            }),
        ]);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function getImage($id)
    {
        $laporan = Laporan::find($id);

        if (!$laporan || !$laporan->image) {
            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan',
            ], 404);
        }

        $imagePath = storage_path('app/public/' . $laporan->image); // Ambil path dari penyimpanan lokal

        if (!file_exists($imagePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File gambar tidak ditemukan di server',
            ], 404);
        }

        // Kembalikan file gambar
        return response()->file($imagePath, [
            'Content-Type' => mime_content_type($imagePath), // Pastikan MIME type sesuai
            'Content-Disposition' => 'inline', // Bisa diganti menjadi 'attachment' jika ingin diunduh
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'         => 'required|integer|exists:users,id',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lokasi_kejadian' => 'required|string|max:255',
            'latitude'        => 'required|numeric',
            'longitude'       => 'required|numeric',
            'datetime'        => 'required|date',
            'status'          => 'nullable|in:pending,diproses,selesai',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $laporan = Laporan::create([
            'user_id'         => $validated['user_id'],
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'image'           => $imagePath,
            'lokasi_kejadian' => $validated['lokasi_kejadian'],
            'latitude'        => $validated['latitude'],
            'longitude'       => $validated['longitude'],
            'datetime'        => $validated['datetime'],
            'status'          => $validated['status'] ?? 'pending',
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
    public function update(Request $request, Laporan $laporan, $id)
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
