<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPendidikan extends Model
{
    use HasFactory;
    protected $table = 'unitpendidikan';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'kategori', 'namaUnit', 'status'];

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

        // Define relationship with TransaksiKas
        public function transaksiKas()
        {
            return $this->hasMany(TransaksiKas::class);
        }
}