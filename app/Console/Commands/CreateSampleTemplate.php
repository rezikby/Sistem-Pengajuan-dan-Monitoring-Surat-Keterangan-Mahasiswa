<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Pt;
use Illuminate\Support\Facades\Storage;

class CreateSampleTemplate extends Command
{
    protected $signature = 'templates:create-sample';
    protected $description = 'Create a sample template with proper placeholders for letter generation';

    public function handle()
    {
        $this->info('📝 Creating sample template...\n');

        // Create a new Word document
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Add content with placeholders
        $section->addParagraph('POLITEKNIK MANUFAKTUR NEGERI BANGKA BELITUNG', ['bold' => true, 'size' => 12, 'align' => 'center']);
        $section->addParagraph('BELITUNG');
        $section->addParagraph('Email: polman@polman-babel.ac.id', ['italic' => true, 'size' => 10]);

        $section->addTextBreak(1);

        $section->addParagraph('SURAT KETERANGAN ${jenis}', ['bold' => true, 'size' => 14, 'align' => 'center']);
        $section->addParagraph('Nomor: ........................', ['align' => 'center']);

        $section->addTextBreak(1);

        $section->addParagraph('Dengan ini kami menyatakan bahwa:');

        $section->addTextBreak(1);

        // Student info with placeholders
        $section->addParagraph('Nama Mahasiswa           : ${nama}');
        $section->addParagraph('NIM                              : ${nim}');
        $section->addParagraph('Program Studi               : ${prodi}');
        $section->addParagraph('Fakultas                        : ${fakultas}');
        $section->addParagraph('Semester                     : ${semester}');

        $section->addTextBreak(1);

        $section->addParagraph('Terdaftar sebagai mahasiswa aktif pada tahun akademis 2026/2027 semester ganjil/genap dan aktif dalam perkuliahan.');

        $section->addTextBreak(1);

        $section->addParagraph('Keterangan Surat:');
        $section->addParagraph('${keterangan}');

        $section->addTextBreak(2);

        $section->addParagraph('Status Pengajuan: ${status}', ['italic' => true, 'size' => 10]);
        $section->addParagraph('Tanggal Pengajuan: ${tanggal}', ['italic' => true, 'size' => 10]);

        $section->addTextBreak(3);

        $section->addParagraph('Hormat kami,', ['align' => 'right']);
        $section->addParagraph('', ['align' => 'right']);
        $section->addParagraph('', ['align' => 'right']);
        $section->addParagraph('(...........................)', ['align' => 'right']);

        // Save template
        $templatesDir = Storage::disk('public')->path('templates');
        if (!is_dir($templatesDir)) {
            mkdir($templatesDir, 0755, true);
        }

        $filename = 'SAMPLE_TEMPLATE.docx';
        $filepath = $templatesDir . DIRECTORY_SEPARATOR . $filename;
        
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filepath);

        $this->info('✅ Sample template created successfully!');
        $this->info('📁 Location: storage/app/public/templates/SAMPLE_TEMPLATE.docx');
        $this->info('');
        $this->info('📖 Placeholders included:');
        $this->table(
            ['Placeholder', 'Description'],
            [
                ['${nama}', 'Nama Mahasiswa'],
                ['${nim}', 'Nomor Induk Mahasiswa'],
                ['${prodi}', 'Program Studi'],
                ['${fakultas}', 'Fakultas'],
                ['${semester}', 'Semester'],
                ['${jenis}', 'Jenis Surat'],
                ['${keterangan}', 'Keterangan Surat'],
                ['${status}', 'Status Pengajuan'],
                ['${tanggal}', 'Tanggal Pengajuan'],
            ]
        );
        
        $this->info('');
        $this->info('💡 Tip: Download this template and customize it in Microsoft Word or LibreOffice');
        $this->info('    Keep the placeholders (${nama}, ${nim}, etc) - system will auto-replace with actual data');
    }
}
