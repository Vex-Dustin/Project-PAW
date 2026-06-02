<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'type',
        'subject',
        'message',
        'status',
        'evidence_image',
    ];

    /**
     * Relasi ke User: Satu laporan dimiliki oleh satu User (Pelapor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
