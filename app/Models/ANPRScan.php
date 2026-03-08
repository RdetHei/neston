<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ANPRScan extends Model
{
    protected $table = 'tb_anpr_scans';

    protected $fillable = [
        'plat_nomor',
        'confidence',
        'image_path',
        'scan_time',
        'json_response',
        'id_parkir',
    ];

    protected $casts = [
        'json_response' => 'array',
        'scan_time' => 'datetime',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_parkir', 'id_parkir');
    }
}
