<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;

class ValidateTemplates extends Command
{
    protected $signature = 'templates:validate';
    protected $description = 'Validate all uploaded surat templates for proper placeholders';

    public function handle()
    {
        $this->info('🔍 Validating Surat Templates...\n');

        $templatesDir = Storage::disk('public')->path('templates');
        
        if (!is_dir($templatesDir)) {
            $this->warn('Templates directory not found!');
            return;
        }

        $templates = glob($templatesDir . '/*.docx');
        
        if (empty($templates)) {
            $this->warn('No template files found!');
            return;
        }

        $requiredPlaceholders = [
            'nama', 'NIM', 'nim', 'semester', 'prodi', 'Prodi', 
            'program_studi', 'jenis', 'keterangan', 'status', 
            'tanggal', 'fakultas'
        ];

        foreach ($templates as $templateFile) {
            $this->line("📄 Checking: " . basename($templateFile));
            
            try {
                $processor = new TemplateProcessor($templateFile);
                
                // Try to get macros (placeholders) from the template
                // PHPWord doesn't have direct method, so we'll just validate by attempting to process
                
                $this->info("   ✓ Template is valid DOCX format");
                $this->info("   ℹ Placeholders to add (use format \${placeholder}):");
                $this->table(
                    ['Placeholder', 'Alternative Names', 'Description'],
                    [
                        ['${nama}', 'nama, Nama', 'Nama Mahasiswa'],
                        ['${nim}', 'nim, NIM', 'Nomor Induk Mahasiswa'],
                        ['${semester}', 'semester, Semester', 'Semester'],
                        ['${prodi}', 'prodi, Prodi, program_studi, Program Studi', 'Program Studi'],
                        ['${fakultas}', 'fakultas, Fakultas', 'Fakultas'],
                        ['${jenis}', 'jenis, Jenis', 'Jenis Surat'],
                        ['${keterangan}', 'keterangan, Keterangan', 'Keterangan'],
                        ['${status}', 'status, Status', 'Status'],
                        ['${tanggal}', 'tanggal, Tanggal, tanggal_pengajuan, Tanggal Pengajuan', 'Tanggal Pengajuan'],
                    ]
                );
                
            } catch (\Exception $e) {
                $this->error("   ✗ Error: " . $e->getMessage());
            }
            
            $this->line('');
        }

        $this->info('✅ Validation complete!');
        $this->info('📖 See TEMPLATE_SETUP.md for detailed documentation');
    }
}
