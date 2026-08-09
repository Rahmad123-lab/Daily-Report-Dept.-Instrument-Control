<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\DailyReport;
use App\Models\DailyReportItem;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
 protected $fillable = [
    'nik',
    'name',
    'department',
    'position',
    'role',
    'status',
    'password',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
 * Daily Report yang dibuat user
 */
public function dailyReports()
{
    return $this->hasMany(DailyReport::class, 'created_by');
}

/**
 * Pekerjaan yang dikerjakan user
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
