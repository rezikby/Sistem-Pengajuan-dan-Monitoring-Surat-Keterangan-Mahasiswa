<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratPengajuan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'user_id',
        'jenis',
        'nama',
        'nim',
        'semester',
        'keterangan',
        'lampiran',
        'surat_file',
        'nomor_surat',
        'status',
        'catatan_admin',
        'verified_at',
        'verified_by',
        'pimpinan_instansi',
        'instansi_tujuan',
        'awal_magang',
        'akhir_magang',
        'email_mahasiswa',
        'nama_dosen',
        'nip_dosen',
        'jabatan_akademik',
        'fakultas',
        'tujuan_rekomendasi',
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
            'pending'   => 'Pending',
            'diproses'  => 'Diproses',
            'diverifikasi' => 'Diverifikasi',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => ucfirst($this->status),
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}