<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name', 
        'shoe_id',
        'durasi',
        'tgl_pinjam', 
        'status'
    ];

    // Menambahkan relasi agar bisa menampilkan merk sepatu di tabel rental
    public function shoe()
    {
        return $this->belongsTo(Shoe::class);
    }
}