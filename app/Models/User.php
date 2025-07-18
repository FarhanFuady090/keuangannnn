<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable

{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $table = 'users'; // Nama tabel di database
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'status',
        'no_telp',
        'namaUnit',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke tabel `unitpendidikan` jika diperlukan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unitpendidikan()
    {
        return $this->belongsTo(UnitPendidikan::class, 'unitpendidikan_id', 'id');
    }
    // Di model User
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id'); // BUKAN 'users_id'
    }

    // Di model User
    public function tupusat()
    {
        return $this->hasOne(Tupusat::class, 'user_id'); // BUKAN 'users_id'
    }


    // Di model User
    public function tuunit()
    {
        return $this->hasOne(Tuunit::class, 'user_id'); // BUKAN 'users_id'
    }

    public function sendPasswordResetNotification($token)
    {
        $role = $this->role; // Pastikan field 'role' tersedia di tabel user
        $this->notify(new ResetPasswordNotification($token, $role));
    }
}
