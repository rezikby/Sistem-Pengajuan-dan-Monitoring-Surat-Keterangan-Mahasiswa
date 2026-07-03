<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'user_id',
        'jenis',
        'nama',
        'nim',
        'semester',
        'keterangan',
        'lampiran',
        'status',
    ];

    /**
     * Label jenis surat yang mudah dibaca (dipakai di tampilan).
     */
    public static function jenisLabels(): array
    {
        return [
            'aktif'        => 'Surat Keterangan Aktif Kuliah',
            'magang'       => 'Surat Keterangan Magang / PKL',
            'rekomendasi'  => 'Surat Rekomendasi',
        ];
    }

    public function getJenisLabelAttribute(): string
    {
        return self::jenisLabels()[$this->jenis] ?? $this->jenis;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu Verifikasi',
            'diproses'  => 'Diproses',
            'selesai'   => 'Selesai',
            'ditolak'   => 'Ditolak / Revisi',
            default     => ucfirst($this->status),
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}