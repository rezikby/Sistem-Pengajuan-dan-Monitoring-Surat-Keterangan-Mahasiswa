<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratTemplateController extends Controller
{
    public function index()
    {
        $templates = SuratTemplate::orderBy('created_at', 'desc')->get()->map(function (SuratTemplate $template) {
            $template->preview = $this->extractDocxPreview($template->file_path);
            return $template;
        });

        return view('dashboard.admin.surat.template', [
            'jenisLabels' => SuratTemplate::jenisLabels(),
            'templates' => $templates,
        ]);
    }

    public function extractDocxPreview(string $filePath): ?string
    {
        $path = Storage::disk('public')->path($filePath);

        if (!file_exists($path) || strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'docx') {
            return null;
        }

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return null;
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if (! $xml) {
            return null;
        }

        $xml = preg_replace('/<w:p[^>]*>/', '<p>', $xml);
        $xml = preg_replace('/<w:tab[^>]*\/>/', '\t', $xml);
        $xml = preg_replace('/<w:br\s*\/?\s*>/', '<br/>', $xml);
        $xml = preg_replace('/<w:t[^>]*>(.*?)<\/w:t>/', '$1', $xml);
        $xml = preg_replace('/<[^>]+>/', '', $xml);
        $xml = str_replace(['\r\n', '\r', '\n'], '', $xml);

        return trim($xml);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:aktif,magang,rekomendasi',
            'template' => 'required|file|mimes:docx,pdf|max:10240',
        ]);

        $path = $request->file('template')->store('templates', 'public');

        SuratTemplate::create([
            'jenis' => $request->input('jenis'),
            'judul' => $request->file('template')->getClientOriginalName(),
            'original_name' => $request->file('template')->getClientOriginalName(),
            'konten' => 'Template untuk ' . SuratTemplate::jenisLabels()[$request->input('jenis')] . ' diunggah.',
            'file_path' => $path,
        ]);

        return back()->with('success', 'Template untuk ' . SuratTemplate::jenisLabels()[$request->input('jenis')] . ' berhasil diunggah.');
    }

    public function update(Request $request, SuratTemplate $template)
    {
        $request->validate([
            'jenis' => 'required|in:aktif,magang,rekomendasi',
            'template' => 'nullable|file|mimes:docx,pdf|max:10240',
            'judul' => 'nullable|string|max:255',
            'konten' => 'nullable|string',
        ]);

        $template->jenis = $request->input('jenis');
        if ($request->filled('konten')) {
            $template->konten = $request->input('konten');
        } else {
            $template->konten = 'Template untuk ' . SuratTemplate::jenisLabels()[$request->input('jenis')] . ' diperbarui.';
        }

        if ($request->filled('judul')) {
            $template->judul = $request->input('judul');
        }

        if ($request->hasFile('template')) {
            if ($template->file_path && Storage::disk('public')->exists($template->file_path)) {
                Storage::disk('public')->delete($template->file_path);
            }
            $path = $request->file('template')->store('templates', 'public');
            $template->file_path = $path;
            $template->judul = $request->file('template')->getClientOriginalName();
        }

        $template->save();

        return back()->with('success', 'Template berhasil diperbarui.');
    }

    public function destroy(SuratTemplate $template)
    {
        // Soft delete the template. Keep file in storage for possible restore.
        $template->delete();

        return back()->with('success', 'Template berhasil dihapus (soft delete).');
    }

    public function listJson()
    {
        $templates = SuratTemplate::orderBy('created_at', 'desc')
            ->get(['id', 'jenis', 'judul', 'file_path']);

        return response()->json([
            'success' => true,
            'data' => $templates,
        ]);
    }

    public function download(SuratTemplate $template)
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        return $disk->download($template->file_path, $template->judul);
    }
}
