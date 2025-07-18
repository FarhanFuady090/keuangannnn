<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;
    
    protected $table = "tahunajaran"; // Nama tabel yang sesuai
    protected $primaryKey = 'id'; // Primary key pada tabel

    protected $fillable = ['tahun_ajaran','awal','akhir', 'semester','status']; // Kolom yang dapat diisi

    // Relasi ke JenisPembayaran
    public function jenisPembayaran()
    {
        return $this->hasMany(JenisPembayaran::class, 'id_tahunajaran', 'id'); // Menyesuaikan nama kolom foreign key
    }
}