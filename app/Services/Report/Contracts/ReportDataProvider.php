<?php

namespace App\Services\Report\Contracts;

use App\Services\Report\ReportContext;

interface ReportDataProvider
{
    /**
     * Kunci unik provider. Dipakai di config template:
     * "providers": ["student", "academic_scores", "sorogan"]
     * dan sebagai dataSource pada section tabel.
     */
    public function key(): string;

    /**
     * Nama yang tampil di UI builder.
     */
    public function label(): string;

    /**
     * Apakah provider ini relevan untuk context tersebut?
     * Contoh: provider sorogan hanya aktif untuk kurikulum pesantren.
     */
    public function supports(ReportContext $ctx): bool;

    /**
     * Bangun data untuk SATU santri.
     * Return array yang di-merge ke root data Handlebars.
     * Isi harus berupa nilai final (string/angka/array), bukan model Eloquent,
     * karena hasilnya akan disimpan sebagai snapshot JSON.
     */
    public function build(ReportContext $ctx): array;

    /**
     * Deskripsi binding yang disediakan provider, untuk UI builder dan
     * validasi saat template disimpan.
     *
     * Format:
     * [
     *   'academic_scores' => [
     *       'type'   => 'collection',
     *       'label'  => 'Nilai Akademik',
     *       'fields' => [['field' => 'subject.name', 'label' => 'Mapel', 'type' => 'string']],
     *   ],
     *   'average_score' => ['type' => 'scalar', 'label' => 'Rata-rata', 'valueType' => 'number'],
     * ]
     */
    public function schema(): array;
}