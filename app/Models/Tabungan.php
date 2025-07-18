<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class Tabungan extends Model
{
    use HasFactory, SoftDeletes; // Menambahkan SoftDeletes

    protected $fillable = [
        'siswa_id',
        'saldo_awal',
        'saldo_akhir',  // Jika kamu ingin menghitung saldo akhir secara otomatis
        'status',
        'created_by',
        'deleted_by',
        'updated_by',
        'information',
    ];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    // Relasi ke transaksi tabungan
    public function transaksi()
    {
        return $this->hasMany(TransaksiTabungan::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($transaksi_tabungans) {
            // Simpan user yang menghapus sebelum soft delete
            $transaksi_tabungans->deleted_by = Auth::user()->username;
            $transaksi_tabungans->save();
        });

        static::updating(function ($model) {
            $original = $model->getOriginal();

            $changes = [];

            if ($model->saldo_awal != $original['saldo_awal']) {
                $changes[] = "Saldo Awal dari Rp " . number_format($original['saldo_awal'], 0, ',', '.') . " menjadi Rp " . number_format($model->saldo_awal, 0, ',', '.');
            }

            if ($model->information !== $original['information']) {
                $from = $original['information'] ?: '-';
                $to = $model->information ?: '-';
                $changes[] = "information dari \"$from\" menjadi \"$to\"";
            }

            if (!empty($changes)) {
                $model->information = [
                    'perubahan' => $changes,
                    'oleh' => Auth::user()->username,
                    'waktu' => now()->toDateTimeString(),
                ];
            }
        });
    }

    // Optional: hitung saldo terakhir (dari saldo awal + transaksi)
    public function getSaldoAkhirAttribute()
    {
        $totalSetoran = $this->transaksi()->where('jenis_transaksi', 'Setoran')->sum('jumlah');
        $totalPenarikan = $this->transaksi()->where('jenis_transaksi', 'Penarikan')->sum('jumlah');

        return $this->saldo_awal + $totalSetoran - $totalPenarikan;
    }
}
