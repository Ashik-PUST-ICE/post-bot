<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class QueueSettingController extends Controller
{
    /**
     * Return live queue stats as JSON (for AJAX polling).
     */
    public function status()
    {
        try {
            $pending = DB::table('jobs')->count();
            $failed  = DB::table('failed_jobs')->count();
        } catch (\Exception $e) {
            // Tables might not exist yet
            $pending = 0;
            $failed  = 0;
        }

        return response()->json([
            'status'     => true,
            'pending'    => $pending,
            'failed'     => $failed,
            'connection' => config('queue.default'),
            'driver'     => env('QUEUE_CONNECTION', 'sync'),
            'worker_cmd' => 'php artisan queue:work --tries='
                . getOption('queue_tries', 3)
                . ' --timeout=' . getOption('queue_timeout', 60)
                . ' --memory=' . getOption('queue_memory', 128),
        ]);
    }

    /**
     * Save queue configuration options.
     * Stored in the app settings table via setOption() helper.
     */
    public function save(Request $request)
    {
        // Mark modal as seen — called by JS immediately on auto-open
        if ($request->has('_mark_seen')) {
            session(['queue_modal_seen' => true]);
            return response()->json(['status' => true]);
        }

        $request->validate([
            'queue_connection' => 'required|in:sync,database,redis',
            'queue_tries'      => 'required|integer|min:1|max:10',
            'queue_timeout'    => 'required|integer|min:30|max:600',
            'queue_memory'     => 'required|integer|min:64|max:512',
            'queue_delay'      => 'nullable|integer|min:0|max:60',
        ]);

        try {
            setOption('queue_connection', $request->queue_connection);
            setOption('queue_tries',      $request->queue_tries);
            setOption('queue_timeout',    $request->queue_timeout);
            setOption('queue_memory',     $request->queue_memory);
            setOption('queue_delay',      $request->queue_delay ?? 0);

            // Also write to .env dynamically using the helper function
            updateEnv(['QUEUE_CONNECTION' => $request->queue_connection]);

            return response()->json([
                'status'  => true,
                'message' => __('Queue settings saved.'),
                'cmd'     => 'php artisan queue:work --tries=' . $request->queue_tries
                    . ' --timeout=' . $request->queue_timeout
                    . ' --memory=' . $request->queue_memory,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Queue settings save failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to save queue settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Retry all failed jobs.
     */
    public function retryFailed()
    {
        try {
            Artisan::call('queue:retry', ['id' => ['all']]);
            return response()->json(['status' => true, 'message' => __('All failed jobs queued for retry.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Flush (delete) all failed jobs.
     */
    public function flushFailed()
    {
        try {
            Artisan::call('queue:flush');
            return response()->json(['status' => true, 'message' => __('Failed jobs cleared.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }



}
