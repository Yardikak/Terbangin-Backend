<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Menampilkan daftar promo.
     */
    public function index()
    {
        // Mengambil data promo dengan kolom tertentu
        $promos = Promo::select('promo_id', 'promo_code', 'description', 'discount', 'valid_until')->get();

        // Mengembalikan view dengan data promo
        return view('promo.index', ['promo' => $promos]);
    }

    /**
     * Menampilkan form untuk membuat promo baru.
     */
    public function create()
    {
        // Menampilkan form untuk membuat promo
        return view('promo.create');
    }

    /**
     * Menyimpan promo yang baru dibuat.
     */
    public function store(Request $request)
    {
        // Validasi inputan
        $validated = $request->validate([
            'promo_code' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount' => 'required|numeric|min:0',
            'valid_until' => 'required|date',
        ]);

        // Menyimpan data promo
        $promo = Promo::create($validated);

        // Redirect ke halaman index dengan status success
        return redirect()->route('promo.index')->with('status', 'Promo created successfully');
    }

    /**
     * Menampilkan detail promo tertentu.
     */
    public function show(string $id)
    {
        // Mengambil promo berdasarkan ID
        $promo = Promo::select('promo_id', 'promo_code', 'description', 'discount', 'valid_until')->find($id);

        // Jika promo tidak ditemukan, redirect ke halaman index dengan pesan error
        if (!$promo) {
            return redirect()->route('promo.index')->with('error', 'Promo not found');
        }

        // Menampilkan detail promo
        return view('promo.show', ['promo' => $promo]);
    }

    /**
     * Menampilkan form untuk mengedit promo yang ada.
     */
    public function edit(string $id)
    {
        // Mengambil promo berdasarkan ID
        $promo = Promo::find($id);

        // Jika promo tidak ditemukan, redirect ke halaman index dengan pesan error
        if (!$promo) {
            return redirect()->route('promo.index')->with('error', 'Promo not found');
        }

        // Menampilkan form edit promo
        return view('promo.edit', ['promo' => $promo]);
    }

    /**
     * Memperbarui promo yang ada.
     */
    public function update(Request $request, string $id)
    {
        // Mengambil promo berdasarkan ID
        $promo = Promo::find($id);

        // Jika promo tidak ditemukan, redirect ke halaman index dengan pesan error
        if (!$promo) {
            return redirect()->route('promo.index')->with('error', 'Promo not found');
        }

        // Validasi inputan untuk update
        $validated = $request->validate([
            'promo_code' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'discount' => 'sometimes|required|numeric|min:0',
            'valid_until' => 'sometimes|required|date',
        ]);

        // Mengupdate data promo
        $promo->update($validated);

        // Redirect ke halaman index dengan status success
        return redirect()->route('promo.index')->with('status', 'Promo updated successfully');
    }

    /**
     * Menghapus promo yang ada.
     */
    public function destroy(string $id)
    {
        // Mengambil promo berdasarkan ID
        $promo = Promo::find($id);

        // Jika promo tidak ditemukan, redirect ke halaman index dengan pesan error
        if (!$promo) {
            return redirect()->route('promo.index')->with('error', 'Promo not found');
        }

        // Menghapus promo
        $promo->delete();

        // Redirect ke halaman index dengan status success
        return redirect()->route('promo.index')->with('status', 'Promo deleted successfully');
    }
}
