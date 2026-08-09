<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReportWorker extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_report_item_id',
        'user_id',
    ];
}