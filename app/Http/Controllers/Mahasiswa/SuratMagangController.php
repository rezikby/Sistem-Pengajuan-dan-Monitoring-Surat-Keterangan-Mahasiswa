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

class SuratMagangController extends Controller
{
    protected $jenis = 'magang';

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
            'jenis_label' => SuratPengajuan::jenisLabels()[$this->jenis],
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
            'jenis_label' => SuratPengajuan::jenisLabels()[$this->jenis],
        ]);
    }

    /**
     * Tampilkan form isi data (langkah "Isi Form" pada flowchart).
     */
    public function create()
    {
        $user  = User::find(Session::get('user_id'));
        $label = SuratPengajuan::jenisLabels()[$this->jenis];

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
            'nama'               => 'nullable|string|max:255',
            'nim'                => 'nullable|string|max:50',
            'semester'           => 'nullable|string|max:10',
            'keterangan'         => 'nullable|string|max:1000',
            'lampiran'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pimpinan_instansi'  => 'required|string|max:255',
            'instansi_tujuan'    => 'required|string|max:255',
            'awal_magang'        => 'required|date',
            'akhir_magang'       => 'required|date|after_or_equal:awal_magang',
            'email_mahasiswa'    => 'required|email|max:255',
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
            'user_id'           => Session::get('user_id'),
            'jenis'             => $this->jenis,
            'nama'              => $validated['nama'],
            'nim'               => $validated['nim'],
            'semester'          => $validated['semester'],
            'keterangan'        => $validated['keterangan'] ?? null,
            'lampiran'          => $validated['lampiran'] ?? null,
            'status'            => 'pending',
            'pimpinan_instansi' => $validated['pimpinan_instansi'],
            'instansi_tujuan'   => $validated['instansi_tujuan'],
            'awal_magang'       => $validated['awal_magang'],
            'akhir_magang'      => $validated['akhir_magang'],
            'email_mahasiswa'   => $validated['email_mahasiswa'],
        ]);

        return redirect()->route("mahasiswa.{$this->jenis}.dashboard")
            ->with('success', 'Pengajuan "' . SuratPengajuan::jenisLabels()[$this->jenis] . '" berhasil dikirim. Menunggu verifikasi admin.');
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
            'nama'               => 'required|string|max:255',
            'nim'                => 'required|string|max:50',
            'semester'           => 'required|string|max:10',
            'keterangan'         => 'nullable|string|max:1000',
            'lampiran'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pimpinan_instansi'  => 'required|string|max:255',
            'instansi_tujuan'    => 'required|string|max:255',
            'awal_magang'        => 'required|date',
            'akhir_magang'       => 'required|date|after_or_equal:awal_magang',
            'email_mahasiswa'    => 'required|email|max:255',
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
     * Generate .docx from template or fallback content.
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

        $pimpinanInstansi = $pengajuan->pimpinan_instansi ?: '-';
        $instansi         = $pengajuan->instansi_tujuan ?: '-';
        $email            = $pengajuan->email_mahasiswa ?: ($pengajuan->user?->email ?? '-');
        $tanggalMulai     = $pengajuan->awal_magang ? Carbon::parse($pengajuan->awal_magang)->format('d M Y') : '-';
        $tanggalSelesai   = $pengajuan->akhir_magang ? Carbon::parse($pengajuan->akhir_magang)->format('d M Y') : '-';

        $templateGenerated = false;

        if ($templateId) {
            $template = SuratTemplate::find($templateId);
            if ($template && Storage::disk('public')->exists($template->file_path) && strtolower(pathinfo($template->file_path, PATHINFO_EXTENSION)) === 'docx') {
                $templatePath = Storage::disk('public')->path($template->file_path);

                try {
                    if ($this->templateHasPlaceholders($templatePath)) {
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
                            'pimpinan_instansi' => $pimpinanInstansi, 'Pimpinan Instansi' => $pimpinanInstansi,
                            'instansi' => $instansi, 'Instansi' => $instansi,
                            'email' => $email, 'Email' => $email,
                            'tanggal_mulai' => $tanggalMulai, 'Tanggal Mulai' => $tanggalMulai,
                            'tanggal_selesai' => $tanggalSelesai, 'Tanggal Selesai' => $tanggalSelesai,
                        ];

                        foreach ($replacements as $placeholder => $value) {
                            try {
                                $templateProcessor->setValue($placeholder, $value);
                            } catch (\Exception $e) {
                                // silently skip if placeholder does not exist
                            }
                        }

                        $templateProcessor->saveAs($outputPath);
                        $templateGenerated = true;
                    } else {
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
            $section->addText('Email: ' . $email);
            $section->addTextBreak(1);
            $section->addText('Pimpinan Instansi Tujuan: ' . $pimpinanInstansi);
            $section->addText('Instansi/Perusahaan Tujuan: ' . $instansi);
            $section->addText('Periode Magang: ' . $tanggalMulai . ' s/d ' . $tanggalSelesai);
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

        $newXml = preg_replace_callback('/<w:p\b[^>]*>.*?<\/w:p>/su', function ($m) use ($labelValues, &$changed) {
            $paragraphXml = $m[0];

            preg_match_all('/<w:t[^>]*>(.*?)<\/w:t>/su', $paragraphXml, $tMatches);
            $plainText = trim(implode('', $tMatches[1] ?? []));

            foreach ($labelValues as $label => $value) {
                if ($plainText === $label) {
                    $safeValue = htmlspecialchars(' ' . $value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
                    $newRun = '<w:r><w:t xml:space="preserve">' . $safeValue . '</w:t></w:r>';
                    $paragraphXml = preg_replace('/<\/w:p>$/', $newRun . '</w:p>', $paragraphXml, 1);
                    $changed = true;
                    break;
                }
            }

            return $paragraphXml;
        }, $xml);

        $safeFakultas = !empty($extras['fakultas'])
            ? htmlspecialchars($extras['fakultas'], ENT_XML1 | ENT_COMPAT, 'UTF-8')
            : null;

        $newXml = preg_replace_callback('/<w:t([^>]*)>(.*?)<\/w:t>/su', function ($m) use ($safeFakultas, &$changed) {
            $attrs = $m[1];
            $content = $m[2];

            if ($safeFakultas !== null) {
                $content = preg_replace_callback('/(fakultas)(\s*)\.{2,}(\s)/iu', function ($mm) use ($safeFakultas, &$changed) {
                    $changed = true;
                    return $mm[1] . $mm[2] . $safeFakultas . $mm[3];
                }, $content, 1);
            }

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
                'pimpinan_instansi' => $item->pimpinan_instansi,
                'instansi_tujuan' => $item->instansi_tujuan,
                'awal_magang' => $item->awal_magang,
                'akhir_magang' => $item->akhir_magang,
                'email_mahasiswa' => $item->email_mahasiswa,
                'lampiran' => $item->lampiran ? asset('storage/' . $item->lampiran) : null,
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
                'pimpinan_instansi' => $pengajuan->pimpinan_instansi,
                'instansi_tujuan' => $pengajuan->instansi_tujuan,
                'awal_magang' => $pengajuan->awal_magang,
                'akhir_magang' => $pengajuan->akhir_magang,
                'email_mahasiswa' => $pengajuan->email_mahasiswa,
                'lampiran' => $pengajuan->lampiran ? asset('storage/' . $pengajuan->lampiran) : null,
                'status' => $pengajuan->status,
                'status_label' => $pengajuan->status_label,
                'catatan_admin' => $pengajuan->catatan_admin,
                'created_at' => $pengajuan->created_at->format('d M Y H:i'),
                'updated_at' => $pengajuan->updated_at->format('d M Y H:i'),
            ]
        ]);
    }
}