<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    protected $table = 'tamus';

    protected $fillable = [
        'hari_tanggal',
        'jam',
        'nama',
        'instansi',
        'alamat',
        'keperluan',
        'tujuan',
        'no_handphone',
        'paraf',
        'gambar',
        'dokumentasi',
    ];

    public function documentationPath(): ?string
    {
        return $this->dokumentasi ?: $this->gambar;
    }
}
