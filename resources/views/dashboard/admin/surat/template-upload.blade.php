@extends('dashboard.admin.layout')

@section('title', 'Upload Template Surat')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Upload Template Surat</h2>
        <p class="text-slate-500 mt-1 text-sm">Unggah file template surat hasil desain Canva Anda (.docx atau .pdf).</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 max-w-3xl">
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

        <form action="{{ route('admin.surat.template.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700">Jenis Surat</label>
                <select name="jenis" required
                    class="mt-3 block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="">Pilih jenis surat</option>
                    @foreach($jenisLabels as $key => $label)
                        <option value="{{ $key }}" {{ old('jenis') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="mt-2 text-sm text-slate-500">Pilih jenis surat agar template ini dapat digunakan untuk surat yang sesuai.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Pilih File Template</label>
                <input type="file" name="template" accept=".docx,.pdf" required
                    class="mt-3 block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" />
                <p class="mt-2 text-sm text-slate-500">Upload file .docx atau .pdf yang telah dibuat di Canva.</p>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Upload</button>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
