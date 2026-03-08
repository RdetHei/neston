<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoHistory extends Model
{
    use HasFactory;

    protected $table = 'tb_saldo_history';

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
        'reference_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
