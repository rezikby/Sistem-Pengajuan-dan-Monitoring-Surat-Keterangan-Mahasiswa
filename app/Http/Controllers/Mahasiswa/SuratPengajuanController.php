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
}