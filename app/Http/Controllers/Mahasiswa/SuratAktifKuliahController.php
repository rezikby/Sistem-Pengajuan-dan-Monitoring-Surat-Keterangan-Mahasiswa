<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SuratPengajuan;
use App\Models\SuratTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class SuratAktifKuliahController extends Controller
{
    protected $jenis = 'aktif';

    /**
     * Tampilkan landing page mahasiswa
     */
    public function landingPage()
    {
        $userId = Session::get('user_id');
        $user = User::find($userId);

        return view('dashboard.mahasiswa.LandingPage', [
            'user' => $user,
        ]);
    }

    /**
     * Tampilkan dashboard mahasiswa dengan riwayat pengajuan
     */
    public function dashboard()
    {
        $userId = Session::get('user_id');
        $user = User::find($userId);

        $pengajuan = SuratPengajuan::where('user_id', $userId)
            ->where('jenis', $this->jenis)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistik per jenis surat
        $statistik = [
            'total' => SuratPengajuan::where('user_id', $userId)->where('jenis', $this->jenis)->count(),
            'pending' => SuratPengajuan::where('user_id', $userId)->where('jenis', $this->jenis)->where('status', 'pending')->count(),
            'diproses' => SuratPengajuan::where('user_id', $userId)->where('jenis', $this->jenis)->where('status', 'diproses')->count(),
            'diverifikasi' => SuratPengajuan::where('user_id', $userId)->where('jenis', $this->jenis)->where('status', 'diverifikasi')->count(),
            'disetujui' => SuratPengajuan::where('user_id', $userId)->where('jenis', $this->jenis)->where('status', 'disetujui')->count(),
            'ditolak' => SuratPengajuan::where('user_id', $userId)->where('jenis', $this->jenis)->where('status', 'ditolak')->count(),
        ];

        return view('dashboard.mahasiswa.app', [
            'user' => $user,
            'pengajuan' => $pengajuan,
            'statistik' => $statistik,
            'jenis' => $this->jenis,
            'jenis_label' => SuratPengajuan::jenisLabels()[$this->jenis] ?? $this->jenis,
        ]);
    }

    /**
     * Tampilkan semua riwayat pengajuan mahasiswa untuk jenis surat ini
     */
    public function riwayat()
    {
        $user = User::find(Session::get('user_id'));

        $pengajuan = SuratPengajuan::where('user_id', Session::get('user_id'))
            ->where('jenis', $this->jenis)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.mahasiswa.riwayat', [
            'user' => $user,
            'pengajuan' => $pengajuan,
            'jenis' => $this->jenis,
            'jenis_label' => SuratPengajuan::jenisLabels()[$this->jenis] ?? $this->jenis,
        ]);
    }

    /**
     * Tampilkan form isi data (langkah "Isi Form" pada flowchart).
     */
    public function create()
    {
        $user  = User::find(Session::get('user_id'));
        $label = SuratPengajuan::jenisLabels()[$this->jenis] ?? $this->jenis;

        return view('dashboard.mahasiswa.form', [
            'user'  => $user,
            'jenis' => $this->jenis,
            'label' => $label,
        ]);
    }

    /**
     * Simpan pengajuan baru (langkah "Kirim Pengajuan").
     */
    public function store(Request $request)
    {
        $user = User::find(Session::get('user_id'));

        $validated = $request->validate([
            'nama'       => 'nullable|string|max:255',
            'nim'        => 'nullable|string|max:50',
            'semester'   => 'nullable|string|max:10',
            'keterangan' => 'nullable|string|max:1000',
            'lampiran'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $validated['nama'] = $validated['nama'] ?: ($user->name ?? null);
        $validated['nim'] = $validated['nim'] ?: ($user->nim ?? null);
        $validated['semester'] = $validated['semester'] ?: ($user->semester ?? null);

        if (! $validated['nama'] || ! $validated['nim'] || ! $validated['semester']) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['nama' => 'Nama, NIM, dan Semester harus diisi dari profil atau form.']);
        }

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('lampiran-surat', 'public');
        }

        SuratPengajuan::create([
            'user_id'    => Session::get('user_id'),
            'jenis'      => $this->jenis,
            'nama'       => $validated['nama'],
            'nim'        => $validated['nim'],
            'semester'   => $validated['semester'],
            'keterangan' => $validated['keterangan'] ?? null,
            'lampiran'   => $validated['lampiran'] ?? null,
            'status'     => 'pending',
        ]);

        $label = SuratPengajuan::jenisLabels()[$this->jenis] ?? $this->jenis;
        return redirect()->route("mahasiswa.{$this->jenis}.dashboard")
            ->with('success', 'Pengajuan "' . $label . '" berhasil dikirim. Menunggu verifikasi admin.');
    }

    /**
     * Tampilkan form edit pengajuan milik mahasiswa yang sedang login.
     */
    public function edit(SuratPengajuan $pengajuan)
    {
        abort_unless($pengajuan->user_id === Session::get('user_id'), 403);
        abort_unless($pengajuan->jenis === $this->jenis, 404);

        return view('dashboard.mahasiswa.edit', [
            'pengajuan' => $pengajuan,
            'label'     => $pengajuan->jenis_label,
            'jenis'     => $this->jenis,
        ]);
    }

    /**
     * Simpan perubahan pengajuan.
     */
    public function update(Request $request, SuratPengajuan $pengajuan)
    {
        abort_unless($pengajuan->user_id === Session::get('user_id'), 403);
        abort_unless($pengajuan->jenis === $this->jenis, 404);

        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'nim'        => 'required|string|max:50',
            'semester'   => 'required|string|max:10',
            'keterangan' => 'nullable|string|max:1000',
            'lampiran'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('lampiran')) {
            if ($pengajuan->lampiran) {
                Storage::disk('public')->delete($pengajuan->lampiran);
            }
            $validated['lampiran'] = $request->file('lampiran')->store('lampiran-surat', 'public');
        }

        $pengajuan->update($validated);

        return redirect()->route("mahasiswa.{$this->jenis}.dashboard")
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    /**
     * Hapus pengajuan milik mahasiswa yang sedang login.
     */
    public function destroy(SuratPengajuan $pengajuan)
    {
        abort_unless($pengajuan->user_id === Session::get('user_id'), 403);
        abort_unless($pengajuan->jenis === $this->jenis, 404);

        if ($pengajuan->lampiran) {
            Storage::disk('public')->delete($pengajuan->lampiran);
        }

        $pengajuan->delete();

        return redirect()->route("mahasiswa.{$this->jenis}.dashboard")
            ->with('success', 'Pengajuan berhasil dihapus.');
    }

    /**
     * Admin: terima / setujui pengajuan -> otomatis generate .docx.
     * Wire route ini ke tombol "Terima" di dashboard admin, misalnya:
     *   Route::post('/admin/aktif/{id}/terima', [SuratAktifKuliahController::class, 'terima']);
     */
    public function terima(Request $request, $id)
    {
        $pengajuan = SuratPengajuan::findOrFail($id);
        abort_unless($pengajuan->jenis === $this->jenis, 404);

        $request->validate([
            'catatan'     => 'nullable|string|max:500',
            'template_id' => 'nullable|integer|exists:surat_templates,id',
        ]);

        $pengajuan->update([
            'status'        => 'disetujui',
            'catatan_admin' => $request->input('catatan'),
            'verified_at'   => now(),
            'verified_by'   => Session::get('user_id'),
        ]);

        $templateId = $this->resolveTemplateIdForPengajuan($pengajuan, $request->input('template_id'));
        $pengajuan->surat_file = $this->generateDocxSurat($pengajuan, $templateId);
        $pengajuan->save();

        return redirect()->back()
            ->with('success', 'Pengajuan "' . $pengajuan->jenis_label . '" berhasil diterima dan surat sudah digenerate.');
    }

    /**
     * Admin: tolak pengajuan.
     */
    public function tolak(Request $request, $id)
    {
        $pengajuan = SuratPengajuan::findOrFail($id);
        abort_unless($pengajuan->jenis === $this->jenis, 404);

        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        $pengajuan->update([
            'status'        => 'ditolak',
            'catatan_admin' => $request->catatan,
            'verified_at'   => now(),
            'verified_by'   => Session::get('user_id'),
        ]);

        return redirect()->back()
            ->with('error', 'Pengajuan "' . $pengajuan->jenis_label . '" berhasil ditolak.');
    }

    /**
     * Admin: update status via AJAX (dipakai modal admin). Generate ulang
     * surat setiap kali status di-set ke "disetujui".
     */
    public function updateStatus(Request $request, int $id)
    {
        try {
            $pengajuan = SuratPengajuan::findOrFail($id);
            abort_unless($pengajuan->jenis === $this->jenis, 404);

            $validated = $request->validate([
                'status'             => 'required|in:diproses,diverifikasi,disetujui,ditolak',
                'catatan_admin'      => 'nullable|string|max:1000',
                'template_id'        => 'nullable|integer|exists:surat_templates,id',
                'generated_content'  => 'nullable|string',
            ]);

            $pengajuan->update([
                'status'        => $validated['status'],
                'catatan_admin' => $validated['catatan_admin'] ?? $pengajuan->catatan_admin,
                'verified_at'   => now(),
                'verified_by'   => Session::get('user_id'),
            ]);

            if ($validated['status'] === 'disetujui') {
                $templateId = $this->resolveTemplateIdForPengajuan($pengajuan, $validated['template_id'] ?? null);
                $pengajuan->surat_file = $this->generateDocxSurat(
                    $pengajuan,
                    $templateId,
                    $validated['generated_content'] ?? null
                );
                $pengajuan->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Status pengajuan berhasil diperbarui',
                'data' => [
                    'id'           => $pengajuan->id,
                    'status'       => $pengajuan->status,
                    'status_label' => $pengajuan->status_label,
                    'surat_file'   => $pengajuan->surat_file ? asset('storage/' . $pengajuan->surat_file) : null,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->errors() ? array_values($e->errors()[0] ?? []) : []),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download surat hasil generate (admin).
     */
    public function downloadGenerated($id)
    {
        $pengajuan = SuratPengajuan::findOrFail($id);
        abort_unless($pengajuan->jenis === $this->jenis, 404);

        if (!$pengajuan->surat_file) {
            abort(404);
        }

        return Storage::disk('public')->download($pengajuan->surat_file, 'surat_pengajuan_' . $pengajuan->id . '.docx');
    }

    protected function resolveTemplateIdForPengajuan(SuratPengajuan $pengajuan, ?int $requestedTemplateId = null): ?int
    {
        if ($requestedTemplateId) {
            return $requestedTemplateId;
        }

        return SuratTemplate::where('jenis', $pengajuan->jenis)
            ->orderByDesc('created_at')
            ->value('id');
    }

    /**
     * Download surat hasil generate (mahasiswa, hanya pemilik pengajuan).
     */
    public function downloadForStudent($id)
    {
        $pengajuan = SuratPengajuan::findOrFail($id);
        abort_unless($pengajuan->jenis === $this->jenis, 404);

        if ($pengajuan->user_id !== Session::get('user_id')) {
            abort(403);
        }
        if (!$pengajuan->surat_file) {
            abort(404);
        }

        return Storage::disk('public')->download($pengajuan->surat_file, 'surat_pengajuan_' . $pengajuan->id . '.docx');
    }

    /**
     * Generate .docx from template or fallback content.
     *
     * FIX: sekarang otomatis mendeteksi apakah template pakai placeholder
     * ${nama}/{{nama}} atau cuma teks label polos ("Nama :", "NIM :", dst).
     * Sebelumnya, kalau template tidak punya placeholder, TemplateProcessor
     * ->setValue() tidak menemukan apa pun untuk diganti (tidak error, tapi
     * juga tidak mengisi apa pun) -> hasil download jadi kosong.
     */
    protected function generateDocxSurat(SuratPengajuan $pengajuan, ?int $templateId = null, ?string $generatedContent = null): string
    {
        $filename = 'surat_pengajuan_' . $pengajuan->id . '_' . time() . '.docx';
        $generatedDir = Storage::disk('public')->path('generated-surat');
        if (!file_exists($generatedDir)) {
            mkdir($generatedDir, 0755, true);
        }
        $outputPath = $generatedDir . DIRECTORY_SEPARATOR . $filename;

        $nama      = $pengajuan->nama ?: ($pengajuan->user?->name ?? '-');
        $nim       = $pengajuan->nim ?: ($pengajuan->user?->nim ?? '-');
        $semester  = $pengajuan->semester ?: ($pengajuan->user?->semester ?? '-');
        $prodi     = $pengajuan->user?->prodi ?? '-';
        $fakultas  = $pengajuan->user?->fakultas ?? '-';
        $jenis     = $pengajuan->jenis_label ?? '-';
        $keterangan = $pengajuan->keterangan ?: ($generatedContent ?: '-');
        $status    = $pengajuan->status_label ?? '-';
        $tanggal   = $pengajuan->created_at?->format('d M Y H:i') ?? '-';

        $templateGenerated = false;

        if ($templateId) {
            $template = SuratTemplate::find($templateId);
            if ($template && Storage::disk('public')->exists($template->file_path) && strtolower(pathinfo($template->file_path, PATHINFO_EXTENSION)) === 'docx') {
                $templatePath = Storage::disk('public')->path($template->file_path);

                try {
                    if ($this->templateHasPlaceholders($templatePath)) {
                        // Template sudah pakai sintaks ${nama}/{{nama}}
                        $templateProcessor = new TemplateProcessor($templatePath);

                        $replacements = [
                            'nama' => $nama, 'Nama' => $nama, 'NAMA' => $nama,
                            'nim' => $nim, 'NIM' => $nim, 'Nim' => $nim,
                            'semester' => $semester, 'Semester' => $semester,
                            'prodi' => $prodi, 'Prodi' => $prodi, 'PRODI' => $prodi,
                            'program_studi' => $prodi, 'Program Studi' => $prodi,
                            'fakultas' => $fakultas, 'Fakultas' => $fakultas,
                            'jenis' => $jenis, 'Jenis' => $jenis,
                            'keterangan' => $keterangan, 'Keterangan' => $keterangan,
                            'status' => $status, 'Status' => $status,
                            'tanggal' => $tanggal, 'Tanggal' => $tanggal,
                            'tanggal_pengajuan' => $tanggal, 'Tanggal Pengajuan' => $tanggal,
                        ];

                        foreach ($replacements as $placeholder => $value) {
                            try {
                                $templateProcessor->setValue($placeholder, $value);
                            } catch (\Exception $e) {
                                // Silently skip if placeholder doesn't exist
                            }
                        }

                        $templateProcessor->saveAs($outputPath);
                        $templateGenerated = true;
                    } else {
                        // Template polos (cuma teks "Nama :", "NIM :", dst tanpa
                        // placeholder) -> isi otomatis berdasarkan label.
                        $templateGenerated = $this->fillPlainLabelTemplate($templatePath, $outputPath, [
                            'Nama :' => $nama,
                            'Nama:' => $nama,
                            'NIM :' => $nim,
                            'NIM:' => $nim,
                            'Program Studi :' => $prodi,
                            'Program Studi:' => $prodi,
                            'Fakultas :' => $fakultas,
                            'Fakultas:' => $fakultas,
                            'Semester :' => $semester,
                            'Semester:' => $semester,
                        ], [
                            'fakultas' => $fakultas,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::info('Template processing error: ' . $e->getMessage());
                    // fallback ke dokumen polos di bawah kalau gagal total
                }
            }
        }

        if (! $templateGenerated) {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addText($jenis, ['bold' => true, 'size' => 14]);
            $section->addTextBreak(1);
            $section->addText('Nama: ' . $nama);
            $section->addText('NIM: ' . $nim);
            $section->addText('Program Studi: ' . $prodi);
            $section->addText('Fakultas: ' . $fakultas);
            $section->addText('Semester: ' . $semester);
            $section->addTextBreak(1);
            $section->addText('Keterangan:');
            if (!empty($generatedContent)) {
                foreach (explode("\n", $generatedContent) as $line) {
                    $section->addText(trim($line));
                }
            } else {
                $section->addText($pengajuan->keterangan ?: '-');
            }
            $section->addTextBreak(1);
            $section->addText('Status: ' . $status);
            $section->addText('Tanggal Pengajuan: ' . $tanggal);

            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($outputPath);
        }

        return 'generated-surat/' . $filename;
    }

    /**
     * Replace placeholders in DOCX file using ZipArchive (fallback lama,
     * dipertahankan untuk kompatibilitas tapi jarang terpakai sekarang
     * karena fillPlainLabelTemplate() sudah menangani template tanpa
     * placeholder dengan lebih andal).
     */
    protected function replaceDocxPlaceholders(string $templatePath, string $outputPath, array $replace): bool
    {
        if (!copy($templatePath, $outputPath)) {
            return false;
        }

        $zip = new \ZipArchive();
        if ($zip->open($outputPath) !== true) {
            return false;
        }

        $documentXml = $zip->getFromName('word/document.xml');
        if ($documentXml === false) {
            $zip->close();
            return false;
        }

        $updatedXml = $documentXml;
        foreach ($replace as $search => $replacement) {
            $updatedXml = str_replace($search, htmlspecialchars($replacement, ENT_XML1 | ENT_COMPAT, 'UTF-8'), $updatedXml);
        }

        if ($updatedXml === $documentXml) {
            $zip->close();
            return false;
        }

        $zip->addFromString('word/document.xml', $updatedXml);
        $zip->close();

        return true;
    }

    /**
     * Cek apakah file docx mengandung sintaks placeholder ${...} atau {{...}}.
     */
    protected function templateHasPlaceholders(string $templatePath): bool
    {
        $zip = new \ZipArchive();
        if ($zip->open($templatePath) !== true) {
            return false;
        }
        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml === false) {
            return false;
        }

        return (bool) preg_match('/\$\{\s*[a-zA-Z_]+\s*\}|\{\{\s*[a-zA-Z_]+\s*\}\}/', $xml);
    }

    /**
     * Isi otomatis template docx "polos" (tanpa placeholder ${...}), misalnya
     * template hasil upload manual yang isinya cuma label seperti "Nama :".
     *
     * Word/LibreOffice sering memecah satu baris teks jadi beberapa <w:r> (run)
     * terpisah, jadi str_replace teks biasa sering gagal. Fungsi ini bekerja
     * per-paragraf: menggabungkan semua teks dalam satu paragraf, mencocokkan
     * dengan label yang dicari, lalu menambahkan run baru berisi nilainya
     * tepat sebelum paragraf itu ditutup — sehingga hasilnya jadi
     * "Nama : Budi", bukan lagi "Nama :" kosong.
     *
     * $extras (opsional) menangani 2 pola khusus di kalimat naratif template
     * (bukan label "Label :" biasa):
     *  - 'fakultas' => nilai fakultas, untuk mengganti "fakultas ... Politeknik"
     *    menjadi "fakultas <nilai fakultas> Politeknik".
     *  - Nomor surat berpola ".../.../.../...." otomatis diganti angka acak
     *    12 digit, contoh: 613221314313.
     */
    protected function fillPlainLabelTemplate(string $templatePath, string $outputPath, array $labelValues, array $extras = []): bool
    {
        if (!copy($templatePath, $outputPath)) {
            return false;
        }

        $zip = new \ZipArchive();
        if ($zip->open($outputPath) !== true) {
            return false;
        }

        $xml = $zip->getFromName('word/document.xml');
        if ($xml === false) {
            $zip->close();
            return false;
        }

        $changed = false;

        // 1) Isi label "Nama :", "NIM :", dst per-paragraf
        $newXml = preg_replace_callback('/<w:p\b[^>]*>.*?<\/w:p>/su', function ($m) use ($labelValues, &$changed) {
            $paragraphXml = $m[0];

            preg_match_all('/<w:t[^>]*>(.*?)<\/w:t>/su', $paragraphXml, $tMatches);
            $plainText = trim(implode('', $tMatches[1] ?? []));

            foreach ($labelValues as $label => $value) {
                if ($plainText === $label || (str_starts_with($plainText, $label) && trim(substr($plainText, strlen($label))) === '')) {
                    $safeValue = htmlspecialchars(' ' . $value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
                    $newRun = '<w:r><w:t xml:space="preserve">' . $safeValue . '</w:t></w:r>';
                    $paragraphXml = preg_replace('/<\/w:p>$/', $newRun . '</w:p>', $paragraphXml, 1);
                    $changed = true;
                    break;
                }
            }

            return $paragraphXml;
        }, $xml);

        // 2) Fix khusus: dibatasi hanya pada isi <w:t> yang benar-benar tampil,
        // BUKAN atribut internal Word seperti w:name pada bookmark (yang
        // sering menyimpan salinan teks yang sama dan bisa ke-replace duluan
        // kalau kita sembarang preg_replace di seluruh XML).
        $safeFakultas = !empty($extras['fakultas'])
            ? htmlspecialchars($extras['fakultas'], ENT_XML1 | ENT_COMPAT, 'UTF-8')
            : null;

        $newXml = preg_replace_callback('/<w:t([^>]*)>(.*?)<\/w:t>/su', function ($m) use ($safeFakultas, &$changed) {
            $attrs = $m[1];
            $content = $m[2];

            // "... mahasiswa fakultas ... Politeknik ..." -> isi nama fakultas
            if ($safeFakultas !== null) {
                $content = preg_replace_callback('/(fakultas)(\s*)\.{2,}(\s)/iu', function ($mm) use ($safeFakultas, &$changed) {
                    $changed = true;
                    return $mm[1] . $mm[2] . $safeFakultas . $mm[3];
                }, $content, 1);
            }

            // Nomor surat placeholder ".../.../.../...." -> angka acak 12 digit
            $content = preg_replace_callback('/\.{2,}(?:\s*\/\s*\.{2,}){1,}/u', function () use (&$changed) {
                $digits = '';
                for ($i = 0; $i < 12; $i++) {
                    $digits .= random_int(0, 9);
                }
                $changed = true;
                return $digits;
            }, $content, 1);

            return '<w:t' . $attrs . '>' . $content . '</w:t>';
        }, $newXml);

        if (!$changed || $newXml === null) {
            $zip->close();
            return false;
        }

        $zip->addFromString('word/document.xml', $newXml);
        $zip->close();

        return true;
    }

    /**
     * Get data pengajuan untuk API.
     */
    public function get(Request $request)
    {
        $status = $request->query('status');
        $limit = $request->query('limit', 5);

        $query = SuratPengajuan::with('user')
            ->where('jenis', $this->jenis)
            ->orderBy('created_at', 'desc');

        if ($status && in_array($status, ['pending', 'diproses', 'diverifikasi', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        $pengajuan = $query->limit($limit)->get();

        $formattedData = $pengajuan->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'nim' => $item->nim,
                'semester' => $item->semester,
                'keterangan' => $item->keterangan,
                'lampiran' => $item->lampiran ? asset('storage/' . $item->lampiran) : null,
                'surat_file' => $item->surat_file ? asset('storage/' . $item->surat_file) : null,
                'jenis' => $item->jenis,
                'jenis_label' => $item->jenis_label,
                'status' => $item->status,
                'status_label' => $item->status_label,
                'tanggal' => $item->created_at->format('d M Y'),
                'created_at' => $item->created_at->format('d M Y H:i'),
                'updated_at' => $item->updated_at->format('d M Y H:i'),
                'user' => $item->user ? [
                    'id' => $item->user->id,
                    'name' => $item->user->name,
                    'email' => $item->user->email,
                    'prodi' => $item->user->prodi ?? '-',
                ] : null,
            ];
        });

        $statistik = [
            'total' => SuratPengajuan::where('jenis', $this->jenis)->count(),
            'pending' => SuratPengajuan::where('jenis', $this->jenis)->where('status', 'pending')->count(),
            'diproses' => SuratPengajuan::where('jenis', $this->jenis)->where('status', 'diproses')->count(),
            'diverifikasi' => SuratPengajuan::where('jenis', $this->jenis)->where('status', 'diverifikasi')->count(),
            'disetujui' => SuratPengajuan::where('jenis', $this->jenis)->where('status', 'disetujui')->count(),
            'ditolak' => SuratPengajuan::where('jenis', $this->jenis)->where('status', 'ditolak')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'pengajuan' => $formattedData,
                'statistik' => $statistik,
            ]
        ]);
    }

    /**
     * Show detail pengajuan (untuk modal)
     */
    public function show($id)
    {
        $pengajuan = SuratPengajuan::with('user')->findOrFail($id);
        abort_unless($pengajuan->jenis === $this->jenis, 404);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pengajuan->id,
                'nama' => $pengajuan->nama,
                'nim' => $pengajuan->nim,
                'semester' => $pengajuan->semester,
                'prodi' => $pengajuan->user->prodi ?? '-',
                'jenis' => $pengajuan->jenis,
                'jenis_label' => $pengajuan->jenis_label,
                'keterangan' => $pengajuan->keterangan,
                'lampiran' => $pengajuan->lampiran ? asset('storage/' . $pengajuan->lampiran) : null,
                'surat_file' => $pengajuan->surat_file ? asset('storage/' . $pengajuan->surat_file) : null,
                'status' => $pengajuan->status,
                'status_label' => $pengajuan->status_label,
                'catatan_admin' => $pengajuan->catatan_admin,
                'created_at' => $pengajuan->created_at->format('d M Y H:i'),
                'updated_at' => $pengajuan->updated_at->format('d M Y H:i'),
            ]
        ]);
    }
}