<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'surat_templates';

    protected $fillable = [
        'jenis',
        'judul',
        'konten',
        'file_path',
        'original_name',
    ];

    public static function jenisLabels(): array
    {
        return [
            'aktif'       => 'Surat Keterangan Aktif Kuliah',
            'magang'      => 'Surat Keterangan Magang / PKL',
            'rekomendasi' => 'Surat Rekomendasi',
        ];
    }

    public function getJenisLabelAttribute(): string
    {
        return self::jenisLabels()[$this->jenis] ?? $this->jenis;
    }
}
