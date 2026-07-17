<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicHomeController extends Controller
{
    /**
     * Render the public landing page with live RT data directories.
     */
    public function index()
    {
        // Fetch public data with safety check
        $announcements = collect();
        $umkms = collect();
        $posyandus = collect();
        $rondas = collect();
        $kegiatans = collect();
        $totalWarga = 0;
        $totalUmkm = 0;
        $totalKegiatan = 0;

        $rt_info = null;
        try {
            $announcements = DB::table('pengumumans')->where('status', 'Aktif')->orderBy('created_at', 'desc')->take(10)->get();
            $umkms = DB::table('umkms')->where('status', 'Aktif')->orderBy('created_at', 'desc')->get();
            $posyandus = DB::table('posyandus')->orderBy('tanggal', 'asc')->take(5)->get();
            $rondas = DB::table('rondas')->get();
            $kegiatans = DB::table('kegiatans')->orderBy('tanggal', 'asc')->take(10)->get();
            $rt_info = DB::table('rt_details')->first();

            $totalWarga = DB::table('wargas')->count();
            $totalUmkm = DB::table('umkms')->where('status', 'Aktif')->count();
            $totalKegiatan = DB::table('kegiatans')->count();
        } catch (\Exception $e) {
            // Degrade gracefully if tables do not exist yet
        }

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'announcements' => $announcements,
                'umkms' => $umkms,
                'posyandus' => $posyandus,
                'rondas' => $rondas,
                'kegiatans' => $kegiatans,
                'totalWarga' => $totalWarga,
                'totalUmkm' => $totalUmkm,
                'totalKegiatan' => $totalKegiatan,
                'rt_info' => $rt_info
            ]);
        }

        return view('welcome', [
            'announcements' => $announcements,
            'umkms' => $umkms,
            'posyandus' => $posyandus,
            'rondas' => $rondas,
            'kegiatans' => $kegiatans,
            'totalWarga' => $totalWarga,
            'totalUmkm' => $totalUmkm,
            'totalKegiatan' => $totalKegiatan,
            'rt_info' => $rt_info
        ]);
    }
}
