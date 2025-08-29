<?php

namespace App\Exports;

use App\Models\AttendanceLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = AttendanceLog::query();

        if($this->request->filled('from_date')) {
            $query->whereDate('work_date', '>=', $this->request->from_date);
        }
        if($this->request->filled('to_date')) {
            $query->whereDate('work_date', '<=', $this->request->to_date);
        }
        if($this->request->filled('user_id')) {
            $query->where('user_id', $this->request->user_id);
        }

        return $query->get([
            'user_id', 'work_date', 'clock_in_at', 'clock_out_at', 'ip_address', 'device_info', 'clock_in_photo', 'clock_out_photo'
        ]);
    }

    public function headings(): array
    {
        return ['User ID', 'Work Date', 'Clock In', 'Clock Out', 'IP', 'Device Info', 'Clock In Photo', 'Clock Out Photo'];
    }
}
