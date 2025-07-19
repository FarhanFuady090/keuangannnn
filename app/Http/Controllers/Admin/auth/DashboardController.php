<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Models\Kelas;
use App\Models\User;
use App\Models\Siswa;
use App\Models\UnitPendidikan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{


    public function showUser()
    {
        // Total User & Siswa
        $users = User::count();
        $siswa = Siswa::count();
    
        // Role user
        $roles = ['admin', 'tuunit', 'tupusat', 'yayasan'];
        $listOfAllRole = [];
        foreach ($roles as $role) {
            $listOfAllRole[strtolower($role)] = User::where('role', $role)->count();
        }
    
        // User berdasarkan unit
        $unitMap = [
            2 => 'TK',
            3 => 'SD',
            4 => 'SMP',
            5 => 'SMA',
            6 => 'MADIN',
            7 => 'TPQ',
            8 => 'PONDOK',
        ];
        $listOfAllUnit = [];
        foreach ($unitMap as $id => $label) {
            $listOfAllUnit[$label] = User::where('namaUnit', $id)->count();
        }
    
        // Siswa berdasarkan unit
        $listOfAllSiswa = [
            2 => Siswa::where('unitpendidikan_id', 2)->count(),
            3 => Siswa::where('unitpendidikan_id', 3)->count(),
            4 => Siswa::where('unitpendidikan_id', 4)->count(),
            5 => Siswa::where('unitpendidikan_id', 5)->count(),
            6 => Siswa::where('unitpendidikan_idInformal', 6)->count(), // pastikan field ini benar
            7 => Siswa::where('unitpendidikan_idInformal', 7)->count(),
            8 => Siswa::where('unitpendidikan_idPondok', 8)->count(),
        ];
    
        return view('admin.dashboard', compact(
            'users',
            'siswa',
            'listOfAllRole',
            'listOfAllUnit',
            'listOfAllSiswa'
        ));
    }
    }
