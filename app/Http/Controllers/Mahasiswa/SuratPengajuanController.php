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

class SuratPengajuanController extends Controller
{
    /**
     * Daftar jenis surat yang valid beserta labelnya.
     */
    protected function jenisValid(): array
    {
        return array_keys(SuratPengajuan::jenisLabels());
    }

    /**
     * Tampilkan dashboard mahasiswa dengan riwayat pengajuan
     */
    public function dashboard()
    {
        $userId = Session::get('user_id');
        $user = User::find($userId);
        
        $pengajuan = SuratPengajuan::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistik per user
        $statistik = [
            'total' => SuratPengajuan::where('user_id', $userId)->count(),
            'pending' => SuratPengajuan::where('user_id', $userId)->where('status', 'pending')->count(),
            'diproses' => SuratPengajuan::where('user_id', $userId)->where('status', 'diproses')->count(),
            'diverifikasi' => SuratPengajuan::where('user_id', $userId)->where('status', 'diverifikasi')->count(),
            'disetujui' => SuratPengajuan::where('user_id', $userId)->where('status', 'disetujui')->count(),
            'ditolak' => SuratPengajuan::where('user_id', $userId)->where('status', 'ditolak')->count(),
        ];

        return view('dashboard.mahasiswa.app', [
            'user' => $user,
            'pengajuan' => $pengajuan,
            'statistik' => $statistik,
        ]);
    }

    /**
     * Tampilkan semua riwayat pengajuan mahasiswa
     */
    public function riwayat()
    {
        $user = User::find(Session::get('user_id'));
        
        $pengajuan = SuratPengajuan::where('user_id', Session::get('user_id'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.mahasiswa.riwayat', [
            'user' => $user,
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Tampilkan admin dashboard dengan data pengajuan
     */
    public function adminDashboard()
    {
        $pengajuan = SuratPengajuan::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'nim' => $item->nim,
                    'semester' => $item->semester,
                    'prodi' => $item->user->prodi ?? '-',
                    'jenis' => $item->jenis,
                    'jenis_label' => $item->jenis_label,
                    'keterangan' => $item->keterangan,
                    'lampiran' => $item->lampiran ? asset('storage/' . $item->lampiran) : null,
                    'surat_file' => $item->surat_file ? asset('storage/' . $item->surat_file) : null,
                    'status' => $item->status,
                    'status_label' => $item->status_label,
                    'created_at' => $item->created_at->format('d M Y H:i'),
                    'updated_at' => $item->updated_at->format('d M Y H:i'),
                    'tanggal' => $item->created_at->format('d M Y'),
                    'catatan_admin' => $item->catatan_admin,
                ];
            });

        $statistik = [
            'total' => SuratPengajuan::count(),
            'pending' => SuratPengajuan::where('status', 'pending')->count(),
            'diproses' => SuratPengajuan::where('status', 'diproses')->count(),
            'diverifikasi' => SuratPengajuan::where('status', 'diverifikasi')->count(),
            'disetujui' => SuratPengajuan::where('status', 'disetujui')->count(),
            'ditolak' => SuratPengajuan::where('status', 'ditolak')->count(),
        ];

        return view('dashboard.admin.app', [
            'pengajuan' => $pengajuan,
            'statistik' => $statistik,
        ]);
    }

    /**
     * Tampilkan form isi data (langkah "Isi Form" pada flowchart).
     */
    public function create(string $jenis)
    {
        abort_unless(in_array($jenis, $this->jenisValid()), 404);

        $user  = User::find(Session::get('user_id'));
        $label = SuratPengajuan::jenisLabels()[$jenis];

        return view('dashboard.mahasiswa.form', [
            'user'  => $user,
            'jenis' => $jenis,
            'label' => $label,
        ]);
    }

    /**
     * Simpan pengajuan baru (langkah "Kirim Pengajuan").
     */
    public function store(Request $request, string $jenis)
    {
        abort_unless(in_array($jenis, $this->jenisValid()), 404);

        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'nim'        => 'required|string|max:50',
            'semester'   => 'required|string|max:10',
            'keterangan' => 'nullable|string|max:1000',
            'lampiran'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('lampiran-surat', 'public');
        }

        SuratPengajuan::create([
            'user_id'    => Session::get('user_id'),
            'jenis'      => $jenis,
            'nama'       => $validated['nama'],
            'nim'        => $validated['nim'],
            'semester'   => $validated['semester'],
            'keterangan' => $validated['keterangan'] ?? null,
            'lampiran'   => $validated['lampiran'] ?? null,
            'status'     => 'pending',
        ]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Pengajuan "' . SuratPengajuan::jenisLabels()[$jenis] . '" berhasil dikirim. Menunggu verifikasi admin.');
    }

    /**
     * Tampilkan form edit pengajuan milik mahasiswa yang sedang login.
     */
    public function edit(SuratPengajuan $pengajuan)
    {
        abort_unless($pengajuan->user_id === Session::get('user_id'), 403);

        return view('dashboard.mahasiswa.edit', [
            'pengajuan' => $pengajuan,
            'label'     => $pengajuan->jenis_label,
        ]);
    }

    /**
     * Simpan perubahan pengajuan.
     */
    public function update(Request $request, SuratPengajuan $pengajuan)
    {
        abort_unless($pengajuan->user_id === Session::get('user_id'), 403);

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

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Pengajuan berhasil diperbarui.');
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

        $placeholders = [
            '${nama}' => $pengajuan->nama,
            '{{nama}}' => $pengajuan->nama,
            '${nim}' => $pengajuan->nim,
            '{{nim}}' => $pengajuan->nim,
            '${semester}' => $pengajuan->semester,
            '{{semester}}' => $pengajuan->semester,
            '${prodi}' => $pengajuan->user?->prodi ?? '-',
            '{{prodi}}' => $pengajuan->user?->prodi ?? '-',
            '${program_studi}' => $pengajuan->user?->prodi ?? '-',
            '{{program_studi}}' => $pengajuan->user?->prodi ?? '-',
            '${fakultas}' => $pengajuan->user?->fakultas ?? '-',
            '{{fakultas}}' => $pengajuan->user?->fakultas ?? '-',
            '${jenis}' => $pengajuan->jenis_label,
            '{{jenis}}' => $pengajuan->jenis_label,
            '${keterangan}' => $pengajuan->keterangan ?? '-',
            '{{keterangan}}' => $pengajuan->keterangan ?? '-',
            '${status}' => $pengajuan->status_label,
            '{{status}}' => $pengajuan->status_label,
            '${tanggal}' => $pengajuan->created_at?->format('d M Y H:i') ?? '-',
            '{{tanggal}}' => $pengajuan->created_at?->format('d M Y H:i') ?? '-',
            'Nama :' => 'Nama : ' . $pengajuan->nama,
            'NIM :' => 'NIM : ' . $pengajuan->nim,
            'Program Studi :' => 'Program Studi : ' . ($pengajuan->user?->prodi ?? '-'),
            'Fakultas :' => 'Fakultas : ' . ($pengajuan->user?->fakultas ?? '-'),
            'Semester :' => 'Semester : ' . $pengajuan->semester,
            'Nama:' => 'Nama: ' . $pengajuan->nama,
            'NIM:' => 'NIM: ' . $pengajuan->nim,
            'Program Studi:' => 'Program Studi: ' . ($pengajuan->user?->prodi ?? '-'),
            'Fakultas:' => 'Fakultas: ' . ($pengajuan->user?->fakultas ?? '-'),
            'Semester:' => 'Semester: ' . $pengajuan->semester,
        ];

        $templateGenerated = false;
        if ($templateId) {
            $template = SuratTemplate::find($templateId);
            if ($template && Storage::disk('public')->exists($template->file_path) && strtolower(pathinfo($template->file_path, PATHINFO_EXTENSION)) === 'docx') {
                $templatePath = Storage::disk('public')->path($template->file_path);
                try {
                    $templateProcessor = new TemplateProcessor($templatePath);
                    foreach ($placeholders as $key => $value) {
                        if (str_starts_with($key, '${') || str_starts_with($key, '{{')) {
                            $templateProcessor->setValue(trim($key, '${}'), $value);
                        }
                    }
                    $templateProcessor->saveAs($outputPath);
                    $templateGenerated = true;
                } catch (\Exception $e) {
                    // fallback below
                }

                if (! $templateGenerated) {
                    $templateGenerated = $this->replaceDocxPlaceholders($templatePath, $outputPath, $placeholders);
                }
            }
        }

        if (! $templateGenerated) {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addText($pengajuan->jenis_label, ['bold' => true, 'size' => 14]);
            $section->addTextBreak(1);
            $section->addText('Nama: ' . $pengajuan->nama);
            $section->addText('NIM: ' . $pengajuan->nim);
            $section->addText('Program Studi: ' . ($pengajuan->user?->prodi ?? '-'));
            $section->addText('Fakultas: ' . ($pengajuan->user?->fakultas ?? '-'));
            $section->addText('Semester: ' . $pengajuan->semester);
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
            $section->addText('Status: ' . $pengajuan->status_label);
            $section->addText('Tanggal Pengajuan: ' . ($pengajuan->created_at?->format('d M Y H:i') ?? '-'));

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

    /**
     * Admin update pengajuan (edit semua field)
     */
    public function adminUpdate(Request $request, $id)
    {
        $pengajuan = SuratPengajuan::findOrFail($id);

        // Allow partial updates from inline editors: use 'sometimes' so single-field JSON updates pass validation
        $validated = $request->validate([
            'nama'       => 'sometimes|required|string|max:255',
            'nim'        => 'sometimes|required|string|max:50',
            'semester'   => 'sometimes|required|string|max:10',
            'keterangan' => 'nullable|string|max:1000',
            'lampiran'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status'     => 'nullable|in:pending,diproses,diverifikasi,disetujui,ditolak',
            'catatan_admin' => 'nullable|string|max:1000',
            'template_id' => 'nullable|integer|exists:surat_templates,id',
            'generated_content' => 'nullable|string',
        ]);

        if ($request->hasFile('lampiran')) {
            if ($pengajuan->lampiran) {
                // keep old file by default, but delete to replace
                Storage::disk('public')->delete($pengajuan->lampiran);
            }
            $validated['lampiran'] = $request->file('lampiran')->store('lampiran-surat', 'public');
        }

        if ($request->filled('generated_content') || ($validated['status'] ?? null) === 'disetujui') {
            $templateId = $validated['template_id'] ?? null;
            $content = $request->input('generated_content', null);
            $filename = 'surat_pengajuan_' . $pengajuan->id . '_' . time() . '.docx';
            $generatedDir = Storage::disk('public')->path('generated-surat');
            if (!file_exists($generatedDir)) {
                mkdir($generatedDir, 0755, true);
            }
            $outputPath = $generatedDir . DIRECTORY_SEPARATOR . $filename;

            $templateGenerated = false;
            if ($templateId) {
                $template = SuratTemplate::find($templateId);
                if ($template && Storage::disk('public')->exists($template->file_path) && strtolower(pathinfo($template->file_path, PATHINFO_EXTENSION)) === 'docx') {
                    try {
                        $templatePath = Storage::disk('public')->path($template->file_path);
                        $templateProcessor = new TemplateProcessor($templatePath);
                        $templateProcessor->setValue('nama', $pengajuan->nama);
                        $templateProcessor->setValue('nim', $pengajuan->nim);
                        $templateProcessor->setValue('semester', $pengajuan->semester);
                        $templateProcessor->setValue('prodi', $pengajuan->user?->prodi ?? '-');
                        $templateProcessor->setValue('jenis', $pengajuan->jenis_label);
                        $templateProcessor->setValue('keterangan', $pengajuan->keterangan ?? '-');
                        $templateProcessor->setValue('status', $pengajuan->status_label);
                        $templateProcessor->setValue('tanggal', $pengajuan->created_at?->format('d M Y H:i') ?? '-');
                        $templateProcessor->saveAs($outputPath);
                        $templateGenerated = true;
                    } catch (\Exception $e) {
                        // fallback to plain document if template processing fails
                    }
                }
            }

            if (! $templateGenerated) {
                $phpWord = new \PhpOffice\PhpWord\PhpWord();
                $section = $phpWord->addSection();
                $section->addText($pengajuan->jenis_label, ['bold' => true, 'size' => 14]);
                $section->addTextBreak(1);
                $section->addText('Nama: ' . $pengajuan->nama);
                $section->addText('NIM: ' . $pengajuan->nim);
                $section->addText('Program Studi: ' . ($pengajuan->user?->prodi ?? '-'));
                $section->addText('Semester: ' . $pengajuan->semester);
                $section->addTextBreak(1);
                $section->addText('Keterangan:');
                if ($content) {
                    foreach (explode("\n", $content) as $line) {
                        $section->addText($line);
                    }
                } else {
                    $section->addText($pengajuan->keterangan ?: '-');
                }
                $section->addTextBreak(1);
                $section->addText('Status: ' . $pengajuan->status_label);
                $section->addText('Tanggal Pengajuan: ' . ($pengajuan->created_at?->format('d M Y H:i') ?? '-'));

                $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                $writer->save($outputPath);
            }

            $validated['surat_file'] = 'generated-surat/' . $filename;
        }

        $pengajuan->update($validated);

        // prepare formatted response data for frontend table update
        $responseData = [
            'id' => $pengajuan->id,
            'nama' => $pengajuan->nama,
            'nim' => $pengajuan->nim,
            'semester' => $pengajuan->semester,
            'keterangan' => $pengajuan->keterangan,
            'lampiran' => $pengajuan->lampiran ? asset('storage/' . $pengajuan->lampiran) : null,
            'jenis' => $pengajuan->jenis,
            'jenis_label' => $pengajuan->jenis_label,
            'status' => $pengajuan->status,
            'status_label' => $pengajuan->status_label,
            'created_at' => $pengajuan->created_at ? $pengajuan->created_at->format('d M Y H:i') : null,
            'updated_at' => $pengajuan->updated_at ? $pengajuan->updated_at->format('d M Y H:i') : null,
            'tanggal' => $pengajuan->created_at ? $pengajuan->created_at->format('d M Y') : null,
            'catatan_admin' => $pengajuan->catatan_admin,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil diperbarui oleh admin.',
            'data' => $responseData,
        ]);
    }

    /**
     * Admin soft-delete pengajuan
     */
    public function adminDestroy($id)
    {
        $pengajuan = SuratPengajuan::findOrFail($id);

        // Soft delete — keep lampiran file in storage for recovery
        $pengajuan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil diarsipkan (soft delete).',
        ]);
    }

    /**
     * Hapus pengajuan milik mahasiswa yang sedang login.
     */
    public function destroy(SuratPengajuan $pengajuan)
    {
        abort_unless($pengajuan->user_id === Session::get('user_id'), 403);

        if ($pengajuan->lampiran) {
            Storage::disk('public')->delete($pengajuan->lampiran);
        }

        $pengajuan->delete();

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }

    /**
     * Terima pengajuan (admin) - menggunakan redirect dengan session
     */
    public function terima(Request $request, $id)
    {
        $pengajuan = SuratPengajuan::findOrFail($id);
        
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $pengajuan->update([
            'status' => 'disetujui',
            'catatan_admin' => $request->catatan ?? null,
            'verified_at' => now(),
            'verified_by' => Session::get('user_id'),
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Pengajuan "' . $pengajuan->jenis_label . '" berhasil diterima!');
    }

    /**
     * Tolak pengajuan (admin) - menggunakan redirect dengan session
     */
    public function tolak(Request $request, $id)
    {
        $pengajuan = SuratPengajuan::findOrFail($id);
        
        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan,
            'verified_at' => now(),
            'verified_by' => Session::get('user_id'),
        ]);

        return redirect()->route('admin.dashboard')
            ->with('error', 'Pengajuan "' . $pengajuan->jenis_label . '" berhasil ditolak.');
    }

    /**
     * Get data pengajuan untuk API.
     */
    public function get(Request $request)
    {
        $status = $request->query('status');
        $limit = $request->query('limit', 5);
        $jenis = $request->query('jenis');

        $query = SuratPengajuan::with('user')
            ->orderBy('created_at', 'desc');

        if ($status && in_array($status, ['pending', 'diproses', 'diverifikasi', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        if ($jenis && in_array($jenis, $this->jenisValid())) {
            $query->where('jenis', $jenis);
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
            'total' => SuratPengajuan::count(),
            'pending' => SuratPengajuan::where('status', 'pending')->count(),
            'diproses' => SuratPengajuan::where('status', 'diproses')->count(),
            'diverifikasi' => SuratPengajuan::where('status', 'diverifikasi')->count(),
            'disetujui' => SuratPengajuan::where('status', 'disetujui')->count(),
            'ditolak' => SuratPengajuan::where('status', 'ditolak')->count(),
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
                'status' => $pengajuan->status,
                'status_label' => $pengajuan->status_label,
                'catatan_admin' => $pengajuan->catatan_admin,
                'created_at' => $pengajuan->created_at->format('d M Y H:i'),
                'updated_at' => $pengajuan->updated_at->format('d M Y H:i'),
            ]
        ]);
    }

    /**
     * Return chart data for last 6 months (labels + counts)
     */
    public function chartData(Request $request)
    {
        $months = [];
        $counts = [];

        $period = $request->query('period', '6months');

        if ($period === 'year') {
            $year = Carbon::now()->year;
            for ($m = 1; $m <= 12; $m++) {
                $dt = Carbon::create($year, $m, 1);
                $label = $dt->format('M');
                $start = $dt->copy()->startOfMonth()->toDateString();
                $end = $dt->copy()->endOfMonth()->toDateString();

                $months[] = $label;
                $counts[] = SuratPengajuan::whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])->count();
            }
        } else {
            // Last 6 months including current
            for ($i = 5; $i >= 0; $i--) {
                $dt = Carbon::now()->subMonths($i);
                $label = $dt->format('M Y');
                $start = $dt->copy()->startOfMonth()->toDateString();
                $end = $dt->copy()->endOfMonth()->toDateString();

                $months[] = $label;
                $counts[] = SuratPengajuan::whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])->count();
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $months,
                'counts' => $counts,
            ]
        ]);
    }

    /**
     * Update status pengajuan (untuk modal admin)
     */
    public function updateStatus(Request $request, int $id)
    {
        try {
            $pengajuan = SuratPengajuan::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:diproses,diverifikasi,disetujui,ditolak',
                'catatan_admin' => 'nullable|string|max:1000',
                'template_id' => 'nullable|integer|exists:surat_templates,id',
                'generated_content' => 'nullable|string',
            ]);

            $pengajuan->update([
                'status' => $validated['status'],
                'catatan_admin' => $validated['catatan_admin'] ?? $pengajuan->catatan_admin,
                'verified_at' => now(),
                'verified_by' => Session::get('user_id'),
            ]);

            // if approved, save generated file as .docx from selected template or fallback document
            if (($validated['status'] ?? '') === 'disetujui') {
                $templateId = $validated['template_id'] ?? null;
                $filename = 'surat_pengajuan_' . $pengajuan->id . '_' . time() . '.docx';
                $generatedDir = Storage::disk('public')->path('generated-surat');
                if (!file_exists($generatedDir)) {
                    mkdir($generatedDir, 0755, true);
                }
                $outputPath = $generatedDir . DIRECTORY_SEPARATOR . $filename;

                $templateGenerated = false;
                if ($templateId) {
                    $template = SuratTemplate::find($templateId);
                    if ($template && Storage::disk('public')->exists($template->file_path) && strtolower(pathinfo($template->file_path, PATHINFO_EXTENSION)) === 'docx') {
                        try {
                            $templatePath = Storage::disk('public')->path($template->file_path);
                            $templateProcessor = new TemplateProcessor($templatePath);
                            $templateProcessor->setValue('nama', $pengajuan->nama);
                            $templateProcessor->setValue('nim', $pengajuan->nim);
                            $templateProcessor->setValue('semester', $pengajuan->semester);
                            $templateProcessor->setValue('prodi', $pengajuan->user?->prodi ?? '-');
                            $templateProcessor->setValue('jenis', $pengajuan->jenis_label);
                            $templateProcessor->setValue('keterangan', $pengajuan->keterangan ?? '-');
                            $templateProcessor->setValue('status', $pengajuan->status_label);
                            $templateProcessor->setValue('tanggal', $pengajuan->created_at?->format('d M Y H:i') ?? '-');
                            $templateProcessor->saveAs($outputPath);
                            $templateGenerated = true;
                        } catch (\Exception $e) {
                            // fallback to plain document if template processing fails
                        }
                    }
                }

                if (! $templateGenerated) {
                    $phpWord = new \PhpOffice\PhpWord\PhpWord();
                    $section = $phpWord->addSection();
                    $section->addText($pengajuan->jenis_label, ['bold' => true, 'size' => 14]);
                    $section->addTextBreak(1);
                    $section->addText('Nama: ' . $pengajuan->nama);
                    $section->addText('NIM: ' . $pengajuan->nim);
                    $section->addText('Program Studi: ' . ($pengajuan->user?->prodi ?? '-'));
                    $section->addText('Semester: ' . $pengajuan->semester);
                    $section->addTextBreak(1);
                    $section->addText('Keterangan:');
                    if (!empty($validated['generated_content'])) {
                        foreach (explode("\n", $validated['generated_content']) as $line) {
                            $section->addText(trim($line));
                        }
                    } else {
                        $section->addText($pengajuan->keterangan ?: '-');
                    }
                    $section->addTextBreak(1);
                    $section->addText('Status: ' . $pengajuan->status_label);
                    $section->addText('Tanggal Pengajuan: ' . ($pengajuan->created_at?->format('d M Y H:i') ?? '-'));

                    $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
                    $writer->save($outputPath);
                }

                $pengajuan->surat_file = 'generated-surat/' . $filename;
                $pengajuan->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Status pengajuan berhasil diperbarui',
                'data' => [
                    'id' => $pengajuan->id,
                    'status' => $pengajuan->status,
                    'status_label' => $pengajuan->status_label,
                ]
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
     * Download generated surat file (admin)
     */
    public function downloadGenerated($id)
    {
        $pengajuan = SuratPengajuan::findOrFail($id);
        if (!$pengajuan->surat_file) {
            abort(404);
        }

        return Storage::disk('public')->download($pengajuan->surat_file, 'surat_pengajuan_' . $pengajuan->id . '.docx');
    }

    /**
     * Student download — only owner can download generated surat
     */
    public function downloadForStudent($id)
    {
        $pengajuan = SuratPengajuan::findOrFail($id);
        $userId = Session::get('user_id');
        if ($pengajuan->user_id !== $userId) {
            abort(403);
        }
        if (!$pengajuan->surat_file) {
            abort(404);
        }

        return Storage::disk('public')->download($pengajuan->surat_file, 'surat_pengajuan_' . $pengajuan->id . '.docx');
    }
}