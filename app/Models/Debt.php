<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Debt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'wallet_id', 'name', 'type', 'amount', 'due_date', 'description', 'is_paid'
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_paid' => 'boolean',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
