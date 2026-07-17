<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Log user activity to the database.
     */
    public static function logActivity($action, $description = null, $foto = null)
    {
        if (auth()->check()) {
            try {
                \Illuminate\Support\Facades\DB::table('activity_logs')->insert([
                    'user_id'     => auth()->id(),
                    'action'      => strtoupper($action),
                    'description' => $description,
                    'foto'        => $foto,
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
            } catch (\Exception $e) {
                // Fail silently to avoid interrupting the main flow
            }
        }
    }
}
