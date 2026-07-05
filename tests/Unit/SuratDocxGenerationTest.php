<?php

namespace Tests\Unit;

use App\Http\Controllers\Mahasiswa\SuratMagangController;
use PHPUnit\Framework\Attributes\Test;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use ZipArchive;

class SuratDocxGenerationTest extends \Tests\TestCase
{
    #[Test]
    public function it_fills_plain_label_template_values_into_generated_docx()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Nama :');
        $section->addText('NIM :');
        $section->addText('Program Studi :');
        $section->addText('Fakultas :');
        $section->addText('Semester :');

        $templatePath = sys_get_temp_dir() . '/plain-template-' . uniqid() . '.docx';
        $outputPath = sys_get_temp_dir() . '/plain-output-' . uniqid() . '.docx';

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($templatePath);

        $controller = new SuratMagangController();
        $method = new \ReflectionMethod(SuratMagangController::class, 'fillPlainLabelTemplate');
        $method->setAccessible(true);

        $result = $method->invoke($controller, $templatePath, $outputPath, [
            'Nama :' => 'Budi Santoso',
            'NIM :' => '202310001',
            'Program Studi :' => 'Teknik Informatika',
            'Fakultas :' => 'Teknik',
            'Semester :' => '6',
        ], [
            'fakultas' => 'Teknik',
        ]);

        $this->assertTrue($result);
        $this->assertFileExists($outputPath);

        $zip = new ZipArchive();
        $this->assertTrue($zip->open($outputPath) === true);
        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        $this->assertStringContainsString('Budi Santoso', $xml);
        $this->assertStringContainsString('202310001', $xml);
        $this->assertStringContainsString('Teknik Informatika', $xml);

        @unlink($templatePath);
        @unlink($outputPath);
    }
}
