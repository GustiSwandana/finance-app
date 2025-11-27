<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi
    protected $fillable = [
        'user_id', 
        'bank_name', 
        'type', 
        'balance'
    ];

    // Relasi: Satu dompet punya banyak transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}