<?php

return [
    'pdf_driver'  => env('REPORT_PDF_DRIVER', 'dompdf'),
    'asset_disk'  => env('REPORT_ASSET_DISK', 'public'),
    'asset_path'  => 'report-assets',
    'paper_sizes' => [
        'A3'        => ['width' => 297, 'height' => 420, 'label' => 'A3 (297 x 420 mm)'],
        'A4'        => ['width' => 210, 'height' => 297, 'label' => 'A4 (210 x 297 mm)'],
        'A5'        => ['width' => 148, 'height' => 210, 'label' => 'A5 (148 x 210 mm)'],
        'B5'        => ['width' => 176, 'height' => 250, 'label' => 'B5 (176 x 250 mm)'],
        'F4'        => ['width' => 215, 'height' => 330, 'label' => 'F4 / Folio (215 x 330 mm)'],
        'Letter'    => ['width' => 216, 'height' => 279, 'label' => 'Letter (216 x 279 mm)'],
        'Legal'     => ['width' => 216, 'height' => 356, 'label' => 'Legal (216 x 356 mm)'],
        'Executive' => ['width' => 184, 'height' => 267, 'label' => 'Executive (184 x 267 mm)'],
        'Custom'    => ['width' => 210, 'height' => 297, 'label' => 'Custom Size...'],
    ],
    'available_bindings' => [
        'student' => [
            'full_name','student_number','national_student_number','gender',
            'place_of_birth','date_of_birth','parent_name',
        ],
        'classroom' => ['name','grade'],
        'dormitory' => ['name','building'],
        'academic_year' => ['start_year','end_year','semester'],
        'report_card'   => ['ranking','homeroom_notes','headmaster_notes'],
        'settings'      => ['pesantren_name','headmaster_name'],
        'summary'       => ['total_score','average_score','average_predicate','average_letter','print_date'],
        'academic_scores' => [
            'index','subject.name','assignment_score','midterm_score','final_score','numeric_score','letter','predicate','description',
        ],
        'sorogan' => [
            'index','book.title','reading_score','translation_score','comprehension_score',
        ],
        'memorization' => [
            'index','type','portion','fluency_score','tajweed_score','etiquette_score',
        ],
        'pesantren_scores' => [
            'index','aspect','score','predicate',
        ],
    ],
];