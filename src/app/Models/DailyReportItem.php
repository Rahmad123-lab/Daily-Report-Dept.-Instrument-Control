<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReportItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_report_id',
        'category',
        'title',
        'description',
        'spk_number',
        'status',
    ];

    public function dailyReport()
    {
        return $this->belongsTo(
            DailyReport::class
        );
    }

    public function workers()
    {
        return $this->belongsToMany(
            User::class,
            'daily_report_workers',
            'daily_report_item_id',
            'user_id'
        );
    }
}