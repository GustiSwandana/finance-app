<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'wallet_id', 
        'category_id', 
        'amount', 
        'description', 
        'type', 
        'status', 
        'date'
    ];

    // --- TAMBAHKAN BAGIAN INI ---
    // Ini memberitahu Laravel: "Tolong anggap kolom 'date' sebagai objek tanggal (Carbon)"
    protected $casts = [
        'date' => 'date',
    ];
    // ----------------------------

    // Relasi ke Dompet
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    // Relasi ke Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}