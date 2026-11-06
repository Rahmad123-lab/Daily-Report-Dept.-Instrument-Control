<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\DailyReportItem;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number',
        'report_date',
        'shift',
        'division',
        'notes',
        'created_by',
        'status',
        'total_employee',
        'present_employee',
        'leave_employee',
        'sick_employee',
        'permission_employee',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    /**
     * Pembuat Daily Report
     */
    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * Daftar pekerjaan
     */
    public function items()
    {
        return $this->hasMany(
            DailyReportItem::class,
            'daily_report_id'
        );
    }
    /**
 * Petugas piket malam.
 */
public function nightDutyUser()
{
    return $this->belongsTo(
        User::class,
        'night_duty_user_id'
    );
}
public function attendances()
{
    return $this->hasMany(
        DailyReportAttendance::class,
        'daily_report_id'
    );
}
}