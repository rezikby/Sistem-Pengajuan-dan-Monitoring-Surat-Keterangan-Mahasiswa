@extends('dashboard.admin.layout')

@section('title', 'Template Surat')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-4 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Template Surat</h2>
            <p class="text-slate-500 mt-1">Preview dan unggah template surat untuk setiap jenis surat.</p>
        </div>
        <button type="button" onclick="document.getElementById('uploadModal').classList.remove('hidden')"
            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Unggah Template</button>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($templates as $template)
            <div class="overflow-hidden border border-slate-200 bg-white shadow-sm">
                <div class="p-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.25em] text-slate-400">{{ $template->jenis }}</p>
                            <h3 class="mt-1 text-[11px] font-medium text-slate-900 truncate">{{ $template->judul }}</h3>
                        </div>
                    </div>
                    <div class="mt-4 text-slate-600">
                        <div class="flex items-center justify-center border border-slate-200 bg-slate-50 p-6">
                            <div class="flex h-20 w-20 items-center justify-center rounded-xl bg-slate-100 text-blue-600">
                                <i class="bi bi-file-earmark-word-fill text-4xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-t border-slate-200 bg-slate-50 p-4 flex flex-wrap gap-3">
                    <a href="{{ route('admin.surat.template.download', $template) }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-800">Download</a>
                    <form action="{{ route('admin.surat.template.destroy', $template) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Hapus template ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 text-sm font-semibold text-red-600 hover:text-red-800">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">
                Belum ada template. Klik tombol "Unggah Template" untuk menambahkan.
            </div>
        @endforelse
    </div>
</div>

<div id="uploadModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 px-4 py-6">
    <div class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-semibold text-slate-900">Unggah Template Surat</h3>
                <p class="text-sm text-slate-500">Pilih jenis surat dan file template Canva Anda.</p>
            </div>
            <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')"
                class="rounded-full border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 px-3 py-2">×</button>
        </div>

        <div class="mt-6">
            @if(session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="uploadForm" action="{{ route('admin.surat.template.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700">Jenis Surat</label>
                    <select name="jenis" id="uploadJenis" required
                        class="mt-3 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="">Pilih jenis surat</option>
                        @foreach($jenisLabels as $key => $label)
                            <option value="{{ $key }}" {{ old('jenis') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Pilih File Template</label>
                    <input type="file" name="template" id="uploadFile" accept=".docx,.pdf" required
                        class="mt-3 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Upload</button>
                    <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</button>
                </div>
            </form>

            <div id="editFormContainer" class="hidden space-y-5">
                <form id="editForm" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Jenis Surat</label>
                        <select name="jenis" id="editJenis" required
                            class="mt-3 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">Pilih jenis surat</option>
                            @foreach($jenisLabels as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Judul Template</label>
                            <input type="text" name="judul" id="editJudul" maxlength="255"
                                class="mt-3 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Konten (deskripsi)</label>
                            <textarea name="konten" id="editKonten" rows="4"
                                class="mt-3 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"></textarea>
                        </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Ganti File Template (opsional)</label>
                        <input type="file" name="template" id="editFile" accept=".docx,.pdf"
                            class="mt-3 block w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Simpan Perubahan</button>
                        <button type="button" onclick="resetModal()"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</button>
                    </div>
                </form>
            </div>
                        </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, jenis, judul) {
        const uploadModal = document.getElementById('uploadModal');
        const uploadForm = document.getElementById('uploadForm');
        const editFormContainer = document.getElementById('editFormContainer');
        const editForm = document.getElementById('editForm');
        const editJenis = document.getElementById('editJenis');

        uploadForm.classList.add('hidden');
        editFormContainer.classList.remove('hidden');

        editForm.action = `{{ url('admin/surat/template') }}/${id}`;
        editJenis.value = jenis;
        document.getElementById('editJudul').value = judul || '';
        document.getElementById('editKonten').value = '';

        editFormContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function resetModal() {
        const uploadForm = document.getElementById('uploadForm');
        const editFormContainer = document.getElementById('editFormContainer');
        uploadForm.classList.remove('hidden');
        editFormContainer.classList.add('hidden');
        document.getElementById('editForm').reset();
    }
</script>
@endsection
