<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PeraturanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nomor_dokumen' => 'nullable|string|max:100',
            'kategori' => 'required|string',
            'tanggal_berlaku' => 'required|date',
            'status' => 'required|string|in:Aktif,Arsip',
        ]);

        $filePath = null;
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/dokumen'), $filename);
            $filePath = 'uploads/dokumen/' . $filename;
        }

        DB::table('peraturan_sks')->insert([
            'judul' => $request->judul,
            'nomor_dokumen' => $request->nomor_dokumen,
            'kategori' => $request->kategori,
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'keterangan' => $request->keterangan,
            'file_path' => $filePath,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Dokumen Peraturan / SK berhasil ditambahkan!'
            ]);
        }

        return back()->with('success_message', 'Dokumen Peraturan / SK berhasil ditambahkan!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:peraturan_sks,id',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string',
            'tanggal_berlaku' => 'required|date',
            'status' => 'required|string|in:Aktif,Arsip',
        ]);

        $updateData = [
            'judul' => $request->judul,
            'nomor_dokumen' => $request->nomor_dokumen,
            'kategori' => $request->kategori,
            'tanggal_berlaku' => $request->tanggal_berlaku,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'updated_at' => now(),
        ];

        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.]/', '', $file->getClientOriginalName());
            $file->move(public_path('uploads/dokumen'), $filename);
            $updateData['file_path'] = 'uploads/dokumen/' . $filename;
        }

        DB::table('peraturan_sks')->where('id', $request->id)->update($updateData);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data Peraturan / SK berhasil diperbarui!'
            ]);
        }

        return back()->with('success_message', 'Data Peraturan / SK berhasil diperbarui!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:peraturan_sks,id',
        ]);

        DB::table('peraturan_sks')->where('id', $request->id)->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Dokumen Peraturan / SK berhasil dihapus!'
            ]);
        }

        return back()->with('success_message', 'Dokumen Peraturan / SK berhasil dihapus!');
    }
}
