<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'amount', 'due_date', 'last_paid_at'];

    // Agar last_paid_at otomatis jadi format Tanggal
    protected $casts = [
        'last_paid_at' => 'date',
    ];
    
    // Helper untuk mengecek apakah bulan ini sudah lunas
    public function isPaidThisMonth()
    {
        if (!$this->last_paid_at) return false;
        return $this->last_paid_at->format('m-Y') === date('m-Y');
    }
}