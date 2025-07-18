<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class TransaksiTabungan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tabungan_id',
        'jenis_transaksi',
        'jumlah',
        'keterangan',
        'created_at',
        'petugas',
        'saldo_berjalan',
        'created_at',
        'information',
        'updated_by',
    ];

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

            if ($model->jenis_transaksi !== $original['jenis_transaksi']) {
                $changes[] = "Jenis Transaksi dari {$original['jenis_transaksi']} menjadi {$model->jenis_transaksi}";
            }

            if ($model->jumlah != $original['jumlah']) {
                $changes[] = "Jumlah dari Rp " . number_format($original['jumlah'], 0, ',', '.') . " menjadi Rp " . number_format($model->jumlah, 0, ',', '.');
            }

            if ($model->keterangan !== $original['keterangan']) {
                $from = $original['keterangan'] ?: '-';
                $to = $model->keterangan ?: '-';
                $changes[] = "Keterangan dari \"$from\" menjadi \"$to\"";
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

    public function getSaldoBerjalanAttribute()
    {
        // Ambil semua transaksi dari tabungan yang sama, urutkan berdasarkan created_at dan ID (untuk menghindari urutan duplikat)
        $transaksiSebelumnya = self::where('tabungan_id', $this->tabungan_id)
            ->where(function ($query) {
                $query->where('created_at', '<', $this->created_at)
                    ->orWhere(function ($query) {
                        $query->where('created_at', $this->created_at)
                            ->where('id', '<=', $this->id);
                    });
            })
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $saldo = $this->tabungan->saldo_awal;

        foreach ($transaksiSebelumnya as $trx) {
            if ($trx->jenis_transaksi === 'Setoran') {
                $saldo += $trx->jumlah;
            } elseif ($trx->jenis_transaksi === 'Penarikan') {
                $saldo -= $trx->jumlah;
            }
        }

        return $saldo;
    }


    public function tabungan()
    {
        return $this->belongsTo(Tabungan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
