<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'nama'      => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'tipe'      => 'required|in:pemasukan,pengeluaran'
            ]);

            Category::create([
                'nama'      => $request->nama,
                'deskripsi' => $request->deskripsi,
                'tipe'      => $request->tipe
            ]);

            self::logActivity('BUAT KATEGORI', "Menambahkan kategori transaksi baru: {$request->nama} (Tipe: {$request->tipe})");

            return response()->json([
                'status'  => 'success',
                'message' => 'Kategori baru berhasil ditambahkan!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = collect($e->errors())->flatten()->first();
            return response()->json([
                'status'  => 'error',
                'message' => $errorMsg
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            $request->validate([
                'id'        => 'required|integer',
                'nama'      => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'tipe'      => 'required|in:pemasukan,pengeluaran'
            ]);

            $category = Category::findOrFail($request->id);
            $category->update([
                'nama'      => $request->nama,
                'deskripsi' => $request->deskripsi,
                'tipe'      => $request->tipe
            ]);

            self::logActivity('UPDATE KATEGORI', "Memperbarui kategori transaksi: {$category->nama} (Tipe: {$request->tipe})");

            return response()->json([
                'status'  => 'success',
                'message' => 'Kategori berhasil diperbarui!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMsg = collect($e->errors())->flatten()->first();
            return response()->json([
                'status'  => 'error',
                'message' => $errorMsg
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        abort_if(!in_array(auth()->user()->role, ['Super Admin', 'Bendahara']), 403, 'Akses Ditolak');
        try {
            $category = Category::findOrFail($id);
            $catName = $category->nama;
            $catType = $category->tipe;
            $category->delete();

            self::logActivity('HAPUS KATEGORI', "Menghapus kategori transaksi: {$catName} (Tipe: {$catType})");

            return response()->json([
                'status'  => 'success',
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menghapus data'
            ], 500);
        }
    }
}
