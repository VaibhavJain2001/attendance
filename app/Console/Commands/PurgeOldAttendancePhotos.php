<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\AttendanceLog;
use Carbon\Carbon;

class PurgeOldAttendancePhotos extends Command
{
    protected $signature = 'attendance:purge-photos';
    protected $description = 'Delete old attendance photos (older than 90 days)';

    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays(90); // adjust as needed

        $logs = AttendanceLog::where('created_at', '<', $cutoffDate)
                    ->where(function($q){
                        $q->whereNotNull('clock_in_photo')
                          ->orWhereNotNull('clock_out_photo');
                    })
                    ->get();

        foreach ($logs as $log) {
            // Delete clock-in photo
            if ($log->clock_in_photo && Storage::exists($log->clock_in_photo)) {
                Storage::delete($log->clock_in_photo);
                $this->info("Deleted clock-in photo: {$log->clock_in_photo}");
                $log->clock_in_photo = null;
            }

            // Delete clock-out photo
            if ($log->clock_out_photo && Storage::exists($log->clock_out_photo)) {
                Storage::delete($log->clock_out_photo);
                $this->info("Deleted clock-out photo: {$log->clock_out_photo}");
                $log->clock_out_photo = null;
            }

            $log->save();
        }

        $this->info('Old attendance photos purged successfully!');
    }
}
