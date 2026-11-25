<?php

namespace App\Services\Exports;

use Models\BosReport;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Font;
use RuntimeException;

class BosSpjWordExporter
{
    /**
     * Menghasilkan file .docx berisi Formulir BOS-K7 dan Buku Kas Umum (BKU)
     * dari snapshot report_data pada BosReport, lalu mengembalikan path file sementara.
     *
     * @param BosReport $report
     * @return string Path absolut file .docx hasil generate
     */
    public function export(BosReport $report): string
    {
        Settings::setOutputEscapingEnabled(true);

        $report->loadMissing(['school', 'bosPeriod', 'verifiedByUser']);
        $snapshot = $report->report_data ?? [];

        if (empty($snapshot)) {
            throw new RuntimeException("Snapshot report_data pada laporan #{$report->id} masih kosong.");
        }

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(9);

        // =====================================================================
        // HALAMAN 1: FORMULIR BOS-K7 (REKAPITULASI REALISASI PER KOMPONEN)
        // =====================================================================
        $k7Section = $phpWord->addSection([
            'orientation' => 'landscape',
            'marginTop' => 720,
            'marginBottom' => 720,
            'marginLeft' => 720,
            'marginRight' => 720,
        ]);

        $this->buildK7Sheet($k7Section, $report, $snapshot);

        // =====================================================================
        // HALAMAN 2: BUKU KAS UMUM (BKU)
        // =====================================================================
        $bkuSection = $phpWord->addSection([
            'orientation' => 'landscape',
            'marginTop' => 720,
            'marginBottom' => 720,
            'marginLeft' => 720,
            'marginRight' => 720,
        ]);

        $this->buildBkuSheet($bkuSection, $report, $snapshot);

        // Simpan ke direktori temporary untuk diunduh via response()->download()
        $fileName = sprintf(
            'SPJ_BOS_%s_%s.docx',
            preg_replace('/[^A-Za-z0-9_-]/', '_', $report->report_code),
            now()->format('Ymd_His')
        );

        $tempDir = storage_path('app/temp/exports');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $filePath = "{$tempDir}/{$fileName}";
        $phpWord->save($filePath, 'Word2007');

        return $filePath;
    }

    /**
     * Membangun halaman Formulir BOS-K7.
     */
    protected function buildK7Sheet($section, BosReport $report, array $snapshot): void
    {
        $school = $report->school;
        $cashSummary = $snapshot['cash_summary'] ?? [];
        $k7Rows = $snapshot['k7_rekapitulasi'] ?? [];

        // Kop / Judul Formulir
        $section->addText(
            'FORMULIR BOS-K7',
            ['bold' => true, 'size' => 11],
            ['alignment' => Jc::RIGHT]
        );
        $section->addText(
            'REKAPITULASI REALISASI PENGGUNAAN DANA BANTUAN OPERASIONAL SEKOLAH (BOS)',
            ['bold' => true, 'size' => 12],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 120]
        );

        // Metadata Sekolah & Periode
        $metaTable = $section->addTable(['borderSize' => 0, 'cellMargin' => 40]);
        $metaRows = [
            ['Nama Sekolah', $school?->name ?? '-'],
            ['NPSN', $school?->npsn ?? '-'],
            ['Periode / Tahap', $snapshot['bos_period']['name'] ?? $report->report_name],
            [
                'Rentang Tanggal',
                Carbon::parse($report->start_date)->translatedFormat('d F Y') . ' s.d. ' .
                Carbon::parse($report->end_date)->translatedFormat('d F Y'),
            ],
            ['Otoritas / Juknis', ($snapshot['bos_period']['authority'] ?? 'KEMENDIKBUD') . ' (' . ($snapshot['bos_period']['regulation_version'] ?? '-') . ')'],
        ];

        foreach ($metaRows as [$label, $value]) {
            $metaTable->addRow();
            $metaTable->addCell(2400)->addText($label, ['bold' => true]);
            $metaTable->addCell(200)->addText(':');
            $metaTable->addCell(9000)->addText($value);
        }

        $section->addTextBreak(1);

        // Ringkasan Posisi Kas (Box Ringkas)
        $cashTable = $section->addTable($this->getStandardTableStyle());
        $cashTable->addRow();
        $this->addHeaderCell($cashTable, 3400, 'Saldo Awal Periode');
        $this->addHeaderCell($cashTable, 3400, 'Penerimaan Dana BOS');
        $this->addHeaderCell($cashTable, 3400, 'Total Penggunaan / Belanja');
        $this->addHeaderCell($cashTable, 3400, 'Sisa Kas Akhir Periode');

        $cashTable->addRow();
        $this->addMoneyCell($cashTable, 3400, (float) ($cashSummary['opening_balance'] ?? 0), true);
        $this->addMoneyCell($cashTable, 3400, (float) ($cashSummary['total_received'] ?? 0), true);
        $this->addMoneyCell($cashTable, 3400, (float) ($cashSummary['total_used'] ?? 0), true);
        $this->addMoneyCell($cashTable, 3400, (float) ($cashSummary['closing_balance'] ?? 0), true);

        $section->addTextBreak(1);

        // Tabel Rincian Komponen BOS-K7
        $table = $section->addTable($this->getStandardTableStyle());
        $table->addRow(400, ['tblHeader' => true]);
        $this->addHeaderCell($table, 700, 'No');
        $this->addHeaderCell($table, 1500, 'Kode Komponen');
        $this->addHeaderCell($table, 4800, 'Komponen Penggunaan Dana BOS');
        $this->addHeaderCell($table, 2200, 'Pagu Alokasi (Rp)');
        $this->addHeaderCell($table, 2200, 'Realisasi (Rp)');
        $this->addHeaderCell($table, 2200, 'Sisa Pagu (Rp)');
        $this->addHeaderCell($table, 1200, 'Serapan (%)');

        $sumAlloc = 0.0;
        $sumReal = 0.0;
        $sumRem = 0.0;

        foreach ($k7Rows as $index => $row) {
            $alloc = (float) ($row['allocation_amount'] ?? 0);
            $real = (float) ($row['realization_amount'] ?? 0);
            $rem = (float) ($row['remaining_amount'] ?? 0);
            $pct = (float) ($row['absorption_percent'] ?? 0);

            $sumAlloc += $alloc;
            $sumReal += $real;
            $sumRem += $rem;

            $table->addRow();
            $table->addCell(700)->addText((string) ($index + 1), [], ['alignment' => Jc::CENTER]);
            $table->addCell(1500)->addText($row['component_code'] ?? '-', [], ['alignment' => Jc::CENTER]);
            $table->addCell(4800)->addText($row['component_name'] ?? '-');
            $this->addMoneyCell($table, 2200, $alloc);
            $this->addMoneyCell($table, 2200, $real);
            $this->addMoneyCell($table, 2200, $rem);
            $table->addCell(1200)->addText(number_format($pct, 2, ',', '.') . '%', [], ['alignment' => Jc::RIGHT]);
        }

        // Baris Total
        $totalPct = $sumAlloc > 0 ? ($sumReal / $sumAlloc) * 100 : 0;
        $table->addRow();
        $cellTotal = $table->addCell(7000, ['gridSpan' => 3, 'bgColor' => 'F2F4F7']);
        $cellTotal->addText('TOTAL KESELURUHAN', ['bold' => true], ['alignment' => Jc::CENTER]);
        $this->addMoneyCell($table, 2200, $sumAlloc, true, 'F2F4F7');
        $this->addMoneyCell($table, 2200, $sumReal, true, 'F2F4F7');
        $this->addMoneyCell($table, 2200, $sumRem, true, 'F2F4F7');
        $table->addCell(1200, ['bgColor' => 'F2F4F7'])
            ->addText(number_format($totalPct, 2, ',', '.') . '%', ['bold' => true], ['alignment' => Jc::RIGHT]);

        $this->addSignatureBlock($section, $report);
    }

    /**
     * Membangun halaman Buku Kas Umum (BKU).
     */
    protected function buildBkuSheet($section, BosReport $report, array $snapshot): void
    {
        $cashSummary = $snapshot['cash_summary'] ?? [];
        $bkuRows = $snapshot['bku_ledger'] ?? [];

        $section->addText(
            'BUKU KAS UMUM (BKU) DANA BOS',
            ['bold' => true, 'size' => 12],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 60]
        );
        $section->addText(
            sprintf(
                '%s | Periode: %s s.d. %s',
                $report->school?->name ?? 'Sekolah',
                Carbon::parse($report->start_date)->format('d/m/Y'),
                Carbon::parse($report->end_date)->format('d/m/Y')
            ),
            ['size' => 10],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 140]
        );

        $table = $section->addTable($this->getStandardTableStyle());
        $table->addRow(400, ['tblHeader' => true]);
        $this->addHeaderCell($table, 1300, 'Tanggal');
        $this->addHeaderCell($table, 1800, 'No. Bukti / Ref');
        $this->addHeaderCell($table, 1400, 'Kode Komp.');
        $this->addHeaderCell($table, 4700, 'Uraian Transaksi');
        $this->addHeaderCell($table, 1850, 'Penerimaan / Debit (Rp)');
        $this->addHeaderCell($table, 1850, 'Pengeluaran / Kredit (Rp)');
        $this->addHeaderCell($table, 1900, 'Saldo (Rp)');

        // Baris Saldo Awal
        $openingBalance = (float) ($cashSummary['opening_balance'] ?? 0);
        $table->addRow();
        $table->addCell(1300)->addText(Carbon::parse($report->start_date)->format('d/m/Y'), [], ['alignment' => Jc::CENTER]);
        $table->addCell(1800)->addText('-', [], ['alignment' => Jc::CENTER]);
        $table->addCell(1400)->addText('-', [], ['alignment' => Jc::CENTER]);
        $table->addCell(4700)->addText('Saldo Awal Periode', ['bold' => true]);
        $this->addMoneyCell($table, 1850, 0);
        $this->addMoneyCell($table, 1850, 0);
        $this->addMoneyCell($table, 1900, $openingBalance, true);

        $totalDebit = 0.0;
        $totalCredit = 0.0;

        foreach ($bkuRows as $row) {
            $debit = (float) ($row['debit'] ?? 0);
            $credit = (float) ($row['credit'] ?? 0);
            $balance = (float) ($row['balance'] ?? 0);

            $totalDebit += $debit;
            $totalCredit += $credit;

            $table->addRow();
            $table->addCell(1300)->addText(
                Carbon::parse($row['date'])->format('d/m/Y'),
                [],
                ['alignment' => Jc::CENTER]
            );
            $table->addCell(1800)->addText($row['ref_number'] ?? '-');
            $table->addCell(1400)->addText($row['component_code'] ?? '-', [], ['alignment' => Jc::CENTER]);
            $table->addCell(4700)->addText($row['description'] ?? '-');
            $this->addMoneyCell($table, 1850, $debit);
            $this->addMoneyCell($table, 1850, $credit);
            $this->addMoneyCell($table, 1900, $balance);
        }

        // Baris Jumlah Mutasi & Saldo Akhir
        $closingBalance = (float) ($cashSummary['closing_balance'] ?? ($openingBalance + $totalDebit - $totalCredit));
        $table->addRow();
        $cellSum = $table->addCell(9200, ['gridSpan' => 4, 'bgColor' => 'F2F4F7']);
        $cellSum->addText('JUMLAH MUTASI & SALDO AKHIR', ['bold' => true], ['alignment' => Jc::CENTER]);
        $this->addMoneyCell($table, 1850, $totalDebit, true, 'F2F4F7');
        $this->addMoneyCell($table, 1850, $totalCredit, true, 'F2F4F7');
        $this->addMoneyCell($table, 1900, $closingBalance, true, 'F2F4F7');

        $this->addSignatureBlock($section, $report);
    }

    /**
     * Menambahkan blok tanda tangan Kepala Sekolah & Bendahara BOS di bagian bawah halaman.
     */
    protected function addSignatureBlock($section, BosReport $report): void
    {
        $section->addTextBreak(1);

        $sigTable = $section->addTable([
            'borderSize' => 0,
            'width' => 100 * 50,
            'unit' => TblWidth::PERCENT,
            'alignment' => JcTable::CENTER,
        ]);

        $sigTable->addRow();
        $leftCell = $sigTable->addCell(5000);
        $rightCell = $sigTable->addCell(5000);

        $leftCell->addText('Mengetahui,', [], ['alignment' => Jc::CENTER]);
        $leftCell->addText('Kepala Sekolah,', ['bold' => true], ['alignment' => Jc::CENTER]);
        $leftCell->addTextBreak(3);
        $leftCell->addText(
            '( ............................................... )',
            ['bold' => true],
            ['alignment' => Jc::CENTER]
        );

        $dateLabel = Carbon::parse($report->end_date)->translatedFormat('d F Y');
        $rightCell->addText("Jakarta, {$dateLabel}", [], ['alignment' => Jc::CENTER]);
        $rightCell->addText('Bendahara Dana BOS,', ['bold' => true], ['alignment' => Jc::CENTER]);
        $rightCell->addTextBreak(3);
        $rightCell->addText(
            '( ............................................... )',
            ['bold' => true],
            ['alignment' => Jc::CENTER]
        );
    }

    protected function getStandardTableStyle(): array
    {
        return [
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 70,
            'alignment' => JcTable::CENTER,
        ];
    }

    protected function addHeaderCell($table, int $width, string $text): void
    {
        $cell = $table->addCell($width, ['bgColor' => 'E4E7EC', 'valign' => 'center']);
        $cell->addText($text, ['bold' => true], ['alignment' => Jc::CENTER]);
    }

    protected function addMoneyCell($table, int $width, float $amount, bool $bold = false, ?string $bgColor = null): void
    {
        $cellStyle = array_filter(['bgColor' => $bgColor, 'valign' => 'center']);
        $formatted = $amount === 0.0 ? '-' : number_format($amount, 2, ',', '.');

        $table->addCell($width, $cellStyle)
            ->addText($formatted, ['bold' => $bold], ['alignment' => Jc::RIGHT]);
    }
}