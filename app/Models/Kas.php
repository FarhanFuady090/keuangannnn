<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    // Spesifikasikan nama tabel karena tidak sesuai default Laravel
    protected $table = 'kas';

    // Field yang bisa diisi
    protected $fillable = ['nama_kas', 'kategori', 'status'];

    // Define relationship with TransaksiKas
    public function transaksiKas()
    {
        return $this->hasMany(TransaksiKas::class, 'kas_id');
    }
}
