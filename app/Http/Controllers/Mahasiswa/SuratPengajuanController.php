<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\SuratPengajuan;
use App\Models\User;    
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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
        $user = User::find(Session::get('user_id'));
        
        $pengajuan = SuratPengajuan::where('user_id', Session::get('user_id'))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.mahasiswa.app', [
            'user' => $user,
            'pengajuan' => $pengajuan,
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
}