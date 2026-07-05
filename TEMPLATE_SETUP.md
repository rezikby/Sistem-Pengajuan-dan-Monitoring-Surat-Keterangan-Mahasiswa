# Panduan Setup Template Word untuk Pengajuan Surat

## Placeholder yang Didukung

Untuk membuat template Word yang berfungsi otomatis mengisi data, gunakan placeholder berikut dalam dokumen Word Anda. PHPWord akan mengganti setiap placeholder dengan data yang sesuai.

### Format Placeholder
Gunakan format `${nama_placeholder}` dalam dokumen Word.

### Daftar Placeholder yang Tersedia

| Placeholder | Alternatif | Deskripsi | Contoh Data |
|------------|-----------|----------|------------|
| `${nama}` | `${Nama}` | Nama Mahasiswa | REZI |
| `${nim}` | `${NIM}` | Nomor Induk Mahasiswa | 1062554 |
| `${semester}` | `${Semester}` | Semester | 5 |
| `${prodi}` | `${Prodi}`, `${program_studi}`, `${Program Studi}` | Program Studi | Teknik Informasi |
| `${fakultas}` | `${Fakultas}` | Fakultas | Teknik |
| `${jenis}` | `${Jenis}` | Jenis Surat | Surat Keterangan Aktif Kuliah |
| `${keterangan}` | `${Keterangan}` | Keterangan/Isi Surat | (Sesuai dengan keterangan pengajuan) |
| `${status}` | `${Status}` | Status Pengajuan | Diterima |
| `${tanggal}` | `${Tanggal}`, `${tanggal_pengajuan}`, `${Tanggal Pengajuan}` | Tanggal Pengajuan | 05 Jul 2026 |

## Cara Membuat Template Word

### Di Microsoft Word:
1. Buka dokumen Word Anda
2. Tempatkan kursor di lokasi yang ingin diisi data
3. Ketik placeholder dengan format `${nama}` misalnya
4. Contoh:
   ```
   Nama: ${nama}
   NIM: ${nim}
   Program Studi: ${prodi}
   Semester: ${semester}
   ```

### Di LibreOffice Writer:
1. Buka dokumen Word
2. Edit → Find & Replace
3. Atau langsung ketik placeholder di teks seperti di Word
4. Simpan sebagai format `.docx`

## Contoh Template Lengkap

```
SURAT KETERANGAN AKTIF KULIAH

Dengan ini kami menyatakan bahwa:
Nama: ${nama}
NIM: ${nim}
Program Studi: ${prodi}
Fakultas: ${fakultas}
Semester: ${semester}

Terdaftar sebagai mahasiswa aktif di Politeknik Manufaktur Negeri Bangka Belitung
pada tahun akademis 2026/2027.

Keterangan: ${keterangan}

Status: ${status}
Tanggal Pengajuan: ${tanggal}
```

## Testing Template

1. Upload template ke sistem melalui halaman admin
2. Buat pengajuan surat baru
3. Pilih template yang sudah di-upload
4. Klik "Generate Isi Otomatis"
5. Download dokumen yang di-generate
6. Buka di Word/LibreOffice - data seharusnya terisi otomatis

## Troubleshooting

### Placeholder tidak terisi
- Pastikan placeholder menggunakan format yang benar: `${nama}` bukan `${Nama:}` atau format lain
- Cek bahwa placeholder berada dalam text, bukan dalam header/footer yang terpisah
- Placeholder harus dalam satu baris/paragraph yang sama

### Huruf besar/kecil
- Sistem mendukung kedua-duanya: `${nama}` dan `${Nama}` akan berfungsi
- Pilih salah satu dan konsisten dalam template

### Format khusus
Jika template memiliki formatting khusus (bold, italic, warna, font besar):
- Letakkan placeholder SETELAH formatting diterapkan
- Atau gunakan MS Word Format > Text Effects untuk placeholder
