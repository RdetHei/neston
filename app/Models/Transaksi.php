<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'tb_transaksi';
    protected $primaryKey = 'id_parkit';
    protected $fillable = [
        'id_kendaraan',
        'waktu_masuk',
        'waktu_keluar',
        'id_tarif',
        'durasi_jam',
        'biaya_total',
        'status',
        'id_user',
        'id_area',
        'status_pembayaran',
        'id_pembayaran',
    ];

    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    // Relationships
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan', 'id_kendaraan');
    }

    public function tarif()
    {
        return $this->belongsTo(Tarif::class, 'id_tarif', 'id_tarif');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function area()
    {
        return $this->belongsTo(AreaParkir::class, 'id_area', 'id_area');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }

    // Accessors & Mutators
    public function getDurasiJamAttribute()
    {
        if ($this->waktu_masuk && $this->waktu_keluar) {
            $masuk = \Carbon\Carbon::parse($this->waktu_masuk);
            $keluar = \Carbon\Carbon::parse($this->waktu_keluar);
            return ceil($keluar->diffInMinutes($masuk) / 60);
        }
        return $this->attributes['durasi_jam'] ?? null;
    }

    public function getBiayaTotalAttribute()
    {
        if ($this->waktu_masuk && $this->waktu_keluar && $this->tarif) {
            $durasi = $this->getDurasiJamAttribute();
            $tarif_perjam = $this->tarif->tarif_perjam;
            return $durasi * $tarif_perjam;
        }
        return $this->attributes['biaya_total'] ?? 0;
    }
}
