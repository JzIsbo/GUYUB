<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Backup the MySQL database and download as a ZIP file.
     */
    public function backup()
    {
        abort_if(auth()->user()->role !== 'Super Admin', 403, 'Akses Ditolak');
        $fileName = 'backup_' . date('Y-m-d_His') . '.sql';
        $zipName = 'backup_' . date('Y-m-d_His') . '.zip';
        $path = storage_path('app/' . $fileName);
        $zipPath = storage_path('app/' . $zipName);

        // Resolve mysqldump path, prioritizing XAMPP default paths on Windows
        $dumpPath = "mysqldump";
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            if (file_exists('C:\\xampp\\mysql\\bin\\mysqldump.exe')) {
                $dumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
            }
        }

        $command = sprintf(
            '%s -u%s -p%s %s > %s',
            $dumpPath,
            env('DB_USERNAME', 'root'),
            env('DB_PASSWORD', ''),
            env('DB_DATABASE', 'db_kas_rt'),
            escapeshellarg($path)
        );

        exec($command);

        if (file_exists($path)) {
            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($path, $fileName);
                $zip->close();
            }
            unlink($path);

            self::logActivity('BACKUP DATABASE', "Mengunduh cadangan (backup) database RT.");

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Gagal membackup database. Pastikan mysqldump terinstall atau XAMPP berjalan.');
    }

    /**
     * Restore database from an uploaded SQL file.
     */
    public function restore(Request $request)
    {
        abort_if(auth()->user()->role !== 'Super Admin', 403, 'Akses Ditolak');
        $request->validate(['sql_file' => 'required|file']);

        $filePath = $request->file('sql_file')->getRealPath();

        // Resolve mysql path, prioritizing XAMPP default paths on Windows
        $mysqlPath = "mysql";
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            if (file_exists('C:\\xampp\\mysql\\bin\\mysql.exe')) {
                $mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe';
            }
        }

        $command = sprintf(
            '%s -u%s -p%s %s < %s',
            $mysqlPath,
            config('database.connections.mysql.username', 'root'),
            config('database.connections.mysql.password', ''),
            config('database.connections.mysql.database', 'db_kas_rt'),
            escapeshellarg($filePath)
        );

        exec($command, $output, $returnCode);

        if ($returnCode === 0) {
            self::logActivity('RESTORE DATABASE', "Memulihkan database dari file cadangan yang diunggah.");
            return back()->with('success', 'Database berhasil dipulihkan!');
        } else {
            return back()->with('error', 'Gagal memulihkan database. Cek konfigurasi MySQL Anda.');
        }
    }
}
