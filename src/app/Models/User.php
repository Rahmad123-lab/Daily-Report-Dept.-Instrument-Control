<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'nik',
        'name',
        'email',
        'department',
        'position',
        'role',
        'status',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Daily Report yang dibuat user.
     */
    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class, 'created_by');
    }

    /**
     * Pekerjaan yang dikerjakan user.
     */
    public function dailyReportItems()
    {
        return $this->belongsToMany(
            DailyReportItem::class,
            'daily_report_workers',
            'user_id',
            'daily_report_item_id'
        );
    }
}
