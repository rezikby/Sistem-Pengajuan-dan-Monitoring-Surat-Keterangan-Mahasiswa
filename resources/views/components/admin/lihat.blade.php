<!-- Modal Detail Pengajuan -->
<div id="modalLihatPengajuan" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="sticky top-0 bg-white border-b border-slate-100 px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Detail Pengajuan Surat</h3>
            <div class="flex items-center gap-3">
                <button onclick="closeModalLihat()" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">
                    ×
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-6">
            
            <!-- Informasi Pengajuan -->
            <div>
                <h4 class="font-semibold text-slate-800 mb-4">Informasi Pengajuan</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase mb-1">Jenis Surat</p>
                        <p class="text-sm font-medium text-slate-800" id="jenisSurat" data-editable="true" data-field="jenis" data-type="select" tabindex="0">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase mb-1">Status</p>
                        <p class="text-sm font-medium" id="statusSurat" data-editable="true" data-field="status" data-type="select" tabindex="0">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase mb-1">Tanggal Pengajuan</p>
                        <p class="text-sm font-medium text-slate-800" id="tanggalPengajuan" data-editable="true" data-field="tanggal_pengajuan" data-type="datetime" tabindex="0">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase mb-1">Terakhir Diupdate</p>
                        <p class="text-sm font-medium text-slate-800" id="tanggalUpdate" data-editable="true" data-field="tanggal_update" data-type="datetime" tabindex="0">-</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Mahasiswa -->
            <div>
                <h4 class="font-semibold text-slate-800 mb-4">Informasi Mahasiswa</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase mb-1">Nama</p>
                        <p class="text-sm font-medium text-slate-800" id="namaMahasiswa" data-editable="true" data-field="nama" tabindex="0">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase mb-1">NIM</p>
                        <p class="text-sm font-mono text-slate-800" id="nimMahasiswa" data-editable="true" data-field="nim" tabindex="0">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase mb-1">Semester</p>
                        <p class="text-sm font-medium text-slate-800" id="semesterMahasiswa" data-editable="true" data-field="semester" tabindex="0">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium uppercase mb-1">Program Studi</p>
                        <p class="text-sm font-medium text-slate-800" id="prodiMahasiswa" data-editable="true" data-field="prodi" data-type="text" tabindex="0">-</p>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <h4 class="font-semibold text-slate-800 mb-4">Keterangan</h4>
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-100 min-h-20">
                    <p class="text-sm text-slate-700" id="keterangan" data-editable="true" data-field="keterangan" data-type="textarea" tabindex="0">-</p>
                </div>
            </div>

            <!-- Lampiran -->
            <div>
                <h4 class="font-semibold text-slate-800 mb-4">Lampiran</h4>
                <div id="lampiranContainer" class="bg-slate-50 rounded-lg p-4 border border-slate-100 text-center" data-editable="true" data-field="lampiran" data-type="file">
                    <p class="text-sm text-slate-500">Tidak ada lampiran</p>
                </div>
            </div>

            <!-- Catatan Admin -->
            <div>
                <h4 class="font-semibold text-slate-800 mb-4">Catatan Admin</h4>
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-100 min-h-20">
                    <p class="text-sm text-slate-700" id="catatanAdmin" data-editable="true" data-field="catatan_admin" data-type="textarea" tabindex="0">-</p>
                </div>
            </div>

            <!-- Form Processing (jika status pending) -->
            <div id="formProcessing" class="hidden space-y-4 pt-4 border-t border-slate-100">
                <h4 class="font-semibold text-slate-800">Proses Pengajuan</h4>
                
                <form id="formProsesData" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Status Baru</label>
                        <select name="status" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="diproses">Diproses</option>
                            <option value="diverifikasi">Diverifikasi</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Pilih Template Surat</label>
                        <select id="templateSelect" name="template_id" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600">
                            <option value="">-- Pilih Template --</option>
                        </select>
                    </div>

                    <div>
                        <button type="button" id="generateTemplateBtn" class="inline-flex items-center justify-center rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">Generate Isi Otomatis</button>
                    </div>

                    <div id="generatedContentWrapper" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Isi Otomatis</label>
                        <textarea id="generatedContent" name="generated_content" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600" rows="5" readonly></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Catatan Admin</label>
                        <textarea name="catatan_admin" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:border-blue-600" rows="3" placeholder="Masukkan catatan atau alasan..."></textarea>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition">
                            <i class="bi bi-check-circle me-2"></i>Proses Pengajuan
                        </button>
                        <button type="button" onclick="closeModalLihat()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-800 font-medium py-2.5 rounded-lg transition">
                            Batal
                        </button>
                    </div>
                </form>

                <!-- edit form moved below so it's always available to admin regardless of status -->
            </div>

            <!-- Action Buttons (jika sudah diproses) -->
            <div id="viewOnlyButtons" class="flex gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModalLihat()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-800 font-medium py-2.5 rounded-lg transition">
                    Tutup
                </button>
            </div>

            <!-- Edit form removed as requested: modal is view-only -->

        </div>
    </div>
</div>

<script>
function openModalLihat(pengajuanId) {
    // Fetch data pengajuan
    fetch(`/admin/pengajuan/${pengajuanId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateModalLihat(data.data);
                document.getElementById('modalLihatPengajuan').classList.remove('hidden');
            } else {
                alert('Gagal mengambil data pengajuan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil data');
        });
}

function closeModalLihat() {
    document.getElementById('modalLihatPengajuan').classList.add('hidden');
}

function populateModalLihat(data) {
    // Isi data pengajuan
    const jenisEl = document.getElementById('jenisSurat');
    jenisEl.textContent = data.jenis_label || '-';
    jenisEl.dataset.value = data.jenis || '';

    const statusEl = document.getElementById('statusSurat');
    statusEl.innerHTML = getStatusBadge(data.status);
    statusEl.dataset.value = data.status || '';

    const tanggalEl = document.getElementById('tanggalPengajuan');
    tanggalEl.textContent = data.created_at || '-';
    tanggalEl.dataset.raw = data.created_at || '';

    const updateEl = document.getElementById('tanggalUpdate');
    updateEl.textContent = data.updated_at || '-';
    updateEl.dataset.raw = data.updated_at || '';
    
    // Isi data mahasiswa
    document.getElementById('namaMahasiswa').textContent = data.nama || '-';
    document.getElementById('nimMahasiswa').textContent = data.nim || '-';
    document.getElementById('semesterMahasiswa').textContent = data.semester || '-';
    document.getElementById('prodiMahasiswa').textContent = data.prodi || '-';
    
    // Isi keterangan
    document.getElementById('keterangan').textContent = data.keterangan || '-';
    
    // Isi lampiran
    const lampiranContainer = document.getElementById('lampiranContainer');
    lampiranContainer.dataset.lampiran = data.lampiran || '';
    if (data.lampiran) {
        lampiranContainer.innerHTML = `
            <div class="flex items-center justify-center gap-3">
                <i class="bi bi-file-earmark-text text-slate-700 text-2xl"></i>
                <div class="text-left">
                    <p class="text-sm font-medium text-slate-800">File Lampiran</p>
                    <a href="${data.lampiran}" target="_blank" class="text-xs text-blue-600 hover:underline">
                        <i class="bi bi-download me-1"></i>Download
                    </a>
                </div>
            </div>
        `;
    } else {
        lampiranContainer.innerHTML = '<p class="text-sm text-slate-500">Tidak ada lampiran</p>';
    }
    
    // Isi catatan admin
    document.getElementById('catatanAdmin').textContent = data.catatan_admin || '-';
    
    // Tampilkan form processing jika status pending
    const formProcessing = document.getElementById('formProcessing');
    const viewOnlyButtons = document.getElementById('viewOnlyButtons');
    
    // Show or hide processing form depending on status
    if (data.status === 'pending') {
        formProcessing.classList.remove('hidden');
        viewOnlyButtons.classList.add('hidden');

        // Set action form for processing
        const form = document.getElementById('formProsesData');
        form.action = `/admin/pengajuan/${data.id}/status`;
        form.dataset.pengajuanId = data.id;

        loadTemplateOptions(data.jenis);
    } else {
        formProcessing.classList.add('hidden');
        viewOnlyButtons.classList.remove('hidden');
    }

    // set current id for inline edits and attach handlers
    currentPengajuanId = data.id;
    attachInlineEditHandlers();
}

// Inline edit removed: modal is view-only now

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-600 border border-slate-100"><i class="bi bi-clock"></i>Pending</span>',
        'diproses': '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-100"><i class="bi bi-hourglass-split"></i>Diproses</span>',
        'diverifikasi': '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600 border border-blue-100"><i class="bi bi-check-circle"></i>Diverifikasi</span>',
        'disetujui': '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600 border border-green-100"><i class="bi bi-check2-circle"></i>Disetujui</span>',
        'ditolak': '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-100"><i class="bi bi-x-circle"></i>Ditolak</span>',
    };
    return badges[status] || '<span class="text-sm text-slate-600">-</span>';
}

let availableTemplates = [];

function loadTemplateOptions(jenisSurat) {
    const select = document.getElementById('templateSelect');
    const generatedWrapper = document.getElementById('generatedContentWrapper');
    const generatedContent = document.getElementById('generatedContent');

    select.innerHTML = '<option value="">-- Pilih Template --</option>';
    generatedWrapper.classList.add('hidden');
    generatedContent.value = '';

    if (availableTemplates.length === 0) {
        fetch('/admin/surat/template/list')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    availableTemplates = result.data;
                    populateTemplateSelect(jenisSurat);
                }
            });
    } else {
        populateTemplateSelect(jenisSurat);
    }
}

function populateTemplateSelect(jenisSurat) {
    const select = document.getElementById('templateSelect');
    const filtered = availableTemplates.filter(t => t.jenis === jenisSurat);

    if (filtered.length === 0) {
        select.innerHTML = '<option value="">-- Tidak ada template untuk jenis ini --</option>';
        return;
    }

    select.innerHTML = '<option value="">-- Pilih Template --</option>';
    filtered.forEach(t => {
        const option = document.createElement('option');
        option.value = t.id;
        option.textContent = t.judul;
        select.appendChild(option);
    });
}

// Inline small-edit via double-click or double-Tab
let currentPengajuanId = null;
let tabPressTimestamps = [];

function attachInlineEditHandlers() {
    document.querySelectorAll('[data-editable="true"]').forEach(el => {
        const type = el.dataset.type || 'text';
        // for select types allow single click, others use dblclick
        if (type === 'select' || type === 'file') {
            el.addEventListener('click', (e) => {
                e.stopPropagation();
                startInlineEdit(el);
            });
        } else {
            el.addEventListener('dblclick', () => startInlineEdit(el));
        }
        // focusable: listen for keydown to detect double-Tab
        el.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                const now = Date.now();
                tabPressTimestamps.push(now);
                // keep only last 3
                tabPressTimestamps = tabPressTimestamps.slice(-3);
                if (tabPressTimestamps.length >= 2 && (tabPressTimestamps[tabPressTimestamps.length-1] - tabPressTimestamps[tabPressTimestamps.length-2]) < 400) {
                    e.preventDefault();
                    startInlineEdit(el);
                }
            }
        });
    });
}

function startInlineEdit(el) {
    if (!el || !el.dataset.field) return;
    const field = el.dataset.field;
    const type = el.dataset.type || 'text';
    const original = el.textContent.trim() === '-' ? '' : el.textContent.trim();
    if (el.dataset.editing === '1') return;
    el.dataset.editing = '1';

    // create editor based on type
    let editor;
    if (type === 'textarea') {
        editor = document.createElement('textarea');
        editor.rows = 4;
    } else if (type === 'select') {
        editor = document.createElement('select');
        // populate options
        if (field === 'status') {
            const opts = [ ['pending','Pending'], ['diproses','Diproses'], ['diverifikasi','Diverifikasi'], ['disetujui','Disetujui'], ['ditolak','Ditolak'] ];
            opts.forEach(o => { const op = document.createElement('option'); op.value = o[0]; op.textContent = o[1]; editor.appendChild(op); });
            // set current
            try { editor.value = el.dataset.value || original || '' } catch(e){}
        } else if (field === 'jenis') {
            // derive unique jenis from availableTemplates
            const jenisSet = Array.from(new Set(availableTemplates.map(t => t.jenis).filter(Boolean)));
            if (jenisSet.length === 0) {
                const op = document.createElement('option'); op.value = original; op.textContent = original; editor.appendChild(op);
            } else {
                jenisSet.forEach(j => { const op = document.createElement('option'); op.value = j; op.textContent = j; editor.appendChild(op); });
                try { editor.value = el.dataset.value || original || '' } catch(e){}
            }
        }
    } else if (type === 'datetime') {
        editor = document.createElement('input');
        editor.type = 'datetime-local';
        // try parse raw
        const raw = el.dataset.raw || original;
        const dt = new Date(raw);
        if (!isNaN(dt.getTime())) {
            const pad = n => n.toString().padStart(2,'0');
            const val = `${dt.getFullYear()}-${pad(dt.getMonth()+1)}-${pad(dt.getDate())}T${pad(dt.getHours())}:${pad(dt.getMinutes())}`;
            editor.value = val;
        }
    } else if (type === 'file') {
        editor = document.createElement('input');
        editor.type = 'file';
    } else {
        editor = document.createElement('input');
        editor.type = 'text';
    }

    editor.className = 'w-full px-3 py-2 border rounded';
    if (type !== 'file' && type !== 'select' && !editor.value) editor.value = original;

    el.innerHTML = '';
    el.appendChild(editor);
    editor.focus();

    function finish(save) {
        el.dataset.editing = '0';
        if (save) {
            if (type === 'file') {
                const file = editor.files && editor.files[0];
                if (file) {
                    // upload file
                    const fd = new FormData();
                    fd.append('lampiran', file);
                    fd.append('_method', 'PUT');
                    fetch(`/admin/pengajuan/${currentPengajuanId}/admin-update`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: fd
                    }).then(r => r.json()).then(json => {
                        if (json.success) {
                            // update lampiran container
                            const url = json.data?.lampiran || '';
                            const container = document.getElementById('lampiranContainer');
                            container.dataset.lampiran = url;
                            if (url) {
                                container.innerHTML = `<div class="flex items-center justify-center gap-3"><i class="bi bi-file-earmark-text text-slate-700 text-2xl"></i><div class="text-left"><p class="text-sm font-medium text-slate-800">File Lampiran</p><a href="${url}" target="_blank" class="text-xs text-blue-600 hover:underline"><i class="bi bi-download me-1"></i>Download</a></div></div>`;
                            }
                        } else {
                            alert(json.message || 'Gagal mengunggah lampiran');
                            el.textContent = original || '-';
                        }
                        setTimeout(attachInlineEditHandlers,50);
                    }).catch(err=>{console.error(err); alert('Error upload'); el.textContent = original || '-'; setTimeout(attachInlineEditHandlers,50);});
                    return;
                }
            } else if (type === 'select' && editor.value) {
                // save selected value
                const label = editor.options[editor.selectedIndex]?.textContent || editor.value;
                el.textContent = label || '-';
                saveField(field, editor.value, type);
            } else {
                const newVal = editor.value.trim();
                el.textContent = newVal || '-';
                saveField(field, newVal, type);
            }
        } else {
            el.textContent = original || '-';
        }
        setTimeout(attachInlineEditHandlers,50);
    }

    if (type === 'select') {
        // save on change or blur
        editor.addEventListener('change', () => finish(true));
        editor.addEventListener('blur', () => finish(true));
    } else if (type === 'file') {
        editor.addEventListener('change', () => finish(true));
    } else {
        editor.addEventListener('blur', () => finish(true));
        editor.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && type !== 'textarea') { e.preventDefault(); editor.blur(); }
            else if (e.key === 'Escape') { finish(false); }
        });
    }
}

function saveField(field, value) {
    if (!currentPengajuanId) return alert('ID pengajuan tidak tersedia');
    const payload = {};
    payload[field] = value;

    fetch(`/admin/pengajuan/${currentPengajuanId}/admin-update`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(json => {
        if (!json.success) {
            alert(json.message || 'Gagal menyimpan perubahan');
            return;
        }
        // update table row if present
        try {
            const d = json.data || {};
            const row = document.querySelector(`tr[data-id="${d.id}"]`);
            if (row) {
                // columns: 0=no,1=nim,2=nama,3=tanggal,4=status
                const cells = row.querySelectorAll('td');
                if (cells[1]) cells[1].textContent = d.nim || cells[1].textContent;
                if (cells[2]) cells[2].textContent = d.nama || cells[2].textContent;
                if (cells[3]) cells[3].textContent = d.tanggal || d.updated_at || cells[3].textContent;
                if (cells[4]) cells[4].innerHTML = getStatusBadge(d.status || 'pending');
            }
        } catch (e) {
            console.error('Failed update table row', e);
        }
    })
    .catch(err => { console.error(err); alert('Terjadi kesalahan saat menyimpan'); });
}

function generateTemplate() {
    const select = document.getElementById('templateSelect');
    const generatedWrapper = document.getElementById('generatedContentWrapper');
    const generatedContent = document.getElementById('generatedContent');
    const jenisSurat = document.getElementById('jenisSurat').textContent;
    const nama = document.getElementById('namaMahasiswa').textContent;
    const nim = document.getElementById('nimMahasiswa').textContent;
    const semester = document.getElementById('semesterMahasiswa').textContent;
    const prodi = document.getElementById('prodiMahasiswa').textContent;

    if (!select.value) {
        alert('Silakan pilih template terlebih dahulu.');
        return;
    }

    const selected = availableTemplates.find(t => t.id === parseInt(select.value));
    if (!selected) {
        alert('Template tidak ditemukan.');
        return;
    }

    const content = `Template: ${selected.judul}\nJenis: ${jenisSurat}\n\nNama: ${nama}\nNIM: ${nim}\nProgram Studi: ${prodi}\nSemester: ${semester}\n\nSilakan gunakan data di atas untuk menghasilkan dokumen ${selected.judul}.`;

    generatedContent.value = content;
    generatedWrapper.classList.remove('hidden');
}

document.getElementById('generateTemplateBtn')?.addEventListener('click', function() {
    generateTemplate();
});

// Handle form submit
document.getElementById('formProsesData')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const pengajuanId = form.dataset.pengajuanId || form.action.split('/').slice(-2, -1)[0];
    const status = form.querySelector('select[name="status"]').value;
    const catatanAdmin = form.querySelector('textarea[name="catatan_admin"]').value;
    const templateId = form.querySelector('select[name="template_id"]').value;
    const generatedContent = document.getElementById('generatedContent')?.value || null;

    if ((status === 'diverifikasi' || status === 'disetujui') && !templateId) {
        alert('Harap pilih template surat terlebih dahulu sebelum menyetujui atau diverifikasi.');
        return;
    }

    // Use fetch with PUT method directly and request JSON error responses
    fetch(`/admin/pengajuan/${pengajuanId}/status`, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            status: status,
            catatan_admin: catatanAdmin,
            template_id: templateId || null,
            generated_content: generatedContent,
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text.substring(0, 500)}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message || 'Pengajuan berhasil diperbarui');
            closeModalLihat();

            // Handle admin edit submit
            document.getElementById('formEditPengajuan')?.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const pengajuanId = form.dataset.pengajuanId || null;
                if (!pengajuanId) return alert('ID pengajuan tidak tersedia');

                const fd = new FormData(form);
                fd.append('_method', 'PUT');

                fetch(`/admin/pengajuan/${pengajuanId}/admin-update`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: fd,
                })
                .then(res => res.json())
                .then(json => {
                    if (json.success) {
                        alert(json.message || 'Perubahan disimpan');
                        closeModalLihat();
                        window.location.reload();
                    } else {
                        alert(json.message || 'Gagal menyimpan perubahan');
                    }
                })
                .catch(err => { console.error(err); alert('Terjadi kesalahan'); });
            });

            // Soft delete handler
            document.getElementById('btnSoftDelete')?.addEventListener('click', function() {
                if (!confirm('Arsipkan pengajuan ini? (soft delete)')) return;
                const form = document.getElementById('formEditPengajuan');
                const pengajuanId = form.dataset.pengajuanId || null;
                if (!pengajuanId) return alert('ID pengajuan tidak tersedia');

                fetch(`/admin/pengajuan/${pengajuanId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                })
                .then(res => res.json())
                .then(json => {
                    if (json.success) {
                        alert(json.message || 'Pengajuan diarsipkan');
                        closeModalLihat();
                        window.location.reload();
                    } else {
                        alert(json.message || 'Gagal mengarsipkan');
                    }
                })
                .catch(err => { console.error(err); alert('Terjadi kesalahan'); });
            });
            window.location.reload();
        } else {
            alert(data.message || 'Gagal memperbarui pengajuan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    });
});

// Tutup modal ketika klik di luar modal
document.getElementById('modalLihatPengajuan')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalLihat();
    }
});
</script>

@section('title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="flex items-start gap-2 bg-green-50 border border-green-200 text-green-600 rounded-xl px-4 py-3 text-sm">
        <i class="bi bi-check-circle-fill mt-0.5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
    <div class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm">
        <i class="bi bi-x-circle-fill mt-0.5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">
                Selamat Datang, Administrator
            </h2>
            <p class="text-slate-500 mt-1 text-sm">
                Sistem Informasi Akademik — Kelola pengajuan surat dan dokumen mahasiswa dengan efisien.
            </p>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="text-slate-500 text-sm font-medium flex items-center gap-2 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100 whitespace-nowrap">
                <i class="bi bi-calendar3 text-blue-600"></i>
                <span id="currentDate"></span>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-4 py-2.5 rounded-xl transition flex items-center gap-2 shadow-sm shadow-blue-100">
                <i class="bi bi-download"></i>
                <span>Export Data</span>
            </button>
        </div>
    </div>

    {{-- Statistik Card --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Card 1: Total Pengajuan -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-blue-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Pengajuan</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['total'] ?? 0 }}</h2>
                <p class="text-green-600 text-xs mt-2.5 flex items-center font-medium bg-green-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-arrow-up-short text-base me-0.5"></i>Data terbaru
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100/50 shadow-sm">
                <i class="bi bi-file-earmark-text text-xl"></i>
            </div>
        </div>

        <!-- Card 2: Diproses -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-amber-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Diproses</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['diproses'] ?? 0 }}</h2>
                <p class="text-amber-600 text-xs mt-2.5 flex items-center font-medium bg-amber-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-clock-history me-1.5"></i>Belum Diverifikasi
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 border border-amber-100/50 shadow-sm">
                <i class="bi bi-hourglass-split text-xl"></i>
            </div>
        </div>

        <!-- Card 3: Selesai -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-emerald-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Selesai</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['disetujui'] ?? 0 }}</h2>
                <p class="text-emerald-600 text-xs mt-2.5 flex items-center font-medium bg-emerald-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-patch-check me-1.5"></i>Surat Selesai
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 border border-emerald-100/50 shadow-sm">
                <i class="bi bi-check-circle text-xl"></i>
            </div>
        </div>

        <!-- Card 4: Ditolak -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100 flex justify-between items-center hover:border-rose-200 transition duration-300">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Ditolak</p>
                <h2 class="text-3xl font-bold mt-2 text-slate-800 tracking-tight">{{ $statistik['ditolak'] ?? 0 }}</h2>
                <p class="text-rose-600 text-xs mt-2.5 flex items-center font-medium bg-rose-50 px-2 py-0.5 rounded-md w-fit">
                    <i class="bi bi-exclamation-triangle me-1.5"></i>Berkas TMS
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500 border border-rose-100/50 shadow-sm">
                <i class="bi bi-x-circle text-xl"></i>
            </div>
        </div>
    </div>

    {{-- Tabel Pengajuan Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-base">Pengajuan Terbaru</h3>
            <a href="#" class="text-blue-600 text-xs font-semibold hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 border-b border-slate-100 w-16 text-center">No</th>
                        <th class="px-6 py-4 border-b border-slate-100">Nama</th>
                        <th class="px-6 py-4 border-b border-slate-100">NIM</th>
                        <th class="px-6 py-4 border-b border-slate-100">Semester</th>
                        <th class="px-6 py-4 border-b border-slate-100">Keterangan</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center">Lampiran</th>
                        <th class="px-6 py-4 border-b border-slate-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                    @if(isset($pengajuan) && $pengajuan->isNotEmpty())
                        @foreach($pengajuan as $index => $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $item->nama }}</td>
                            <td class="px-6 py-4 font-mono text-slate-600">{{ $item->nim }}</td>
                            <td class="px-6 py-4 text-slate-600">Semester {{ $item->semester }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ Str::limit($item->keterangan, 30) ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($item->lampiran)
                                    <a href="{{ $item->lampiran }}" target="_blank" 
                                       class="inline-flex items-center justify-center text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-lg transition" title="Lihat Lampiran">
                                        <i class="bi bi-paperclip text-sm"></i>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openModal({
                                        id: '{{ $item->id }}',
                                        nama: '{{ addslashes($item->nama) }}',
                                        nim: '{{ $item->nim }}',
                                        semester: '{{ $item->semester }}',
                                        prodi: '{{ $item->prodi ?? '-' }}',
                                        jenis_label: '{{ $item->jenis_label }}',
                                        keterangan: '{{ addslashes($item->keterangan ?? '-') }}',
                                        lampiran: '{{ $item->lampiran }}',
                                        status: '{{ $item->status }}',
                                        status_label: '{{ $item->status_label }}',
                                        tanggal: '{{ $item->tanggal }}',
                                        created_at: '{{ $item->created_at }}',
                                        updated_at: '{{ $item->updated_at }}'
                                    })" 
                                            class="action-btn action-btn-view" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="action-btn action-btn-edit" title="Edit" onclick="editData('{{ $item->id }}')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="action-btn action-btn-delete" title="Hapus" onclick="deleteData('{{ $item->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="bi bi-database-slash text-4xl text-slate-300"></i>
                                    <p class="text-sm font-medium">Belum ada data</p>
                                    <p class="text-xs">Data pengajuan akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
</div>

<style>
    .action-btn {
        padding: 0.375rem 0.625rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }

    .action-btn-view {
        background: #dbeafe;
        color: #2563eb;
    }

    .action-btn-view:hover {
        background: #bfdbfe;
        transform: scale(1.05);
    }

    .action-btn-edit {
        background: #d1fae5;
        color: #059669;
    }

    .action-btn-edit:hover {
        background: #a7f3d0;
        transform: scale(1.05);
    }

    .action-btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-btn-delete:hover {
        background: #fca5a5;
        transform: scale(1.05);
    }
</style>

<script>
    function editData(id) {
        alert('Edit data dengan ID: ' + id);
    }

    function deleteData(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            alert('Hapus data dengan ID: ' + id);
        }
    }

    function formatDate() {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        const now = new Date();
        const day = days[now.getDay()];
        const date = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        
        return `${date} ${month} ${year}, ${day}`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('currentDate').textContent = formatDate();
    });
</script>

@endsection