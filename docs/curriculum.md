# Analisis Kebutuhan Tabel untuk Sistem Pendidikan Multi-Kurikulum

Berikut analisis lengkap tabel yang perlu Anda siapkan. Saya bagi menjadi beberapa domain agar jelas.

---

## 1. Domain Kurikulum (Sudah Ada, Perlu Dilengkapi)

### ✅ Sudah ada:
- `curriculum_phases` — fase kurikulum (Fase A, B, C, dst. untuk Kurikulum Merdeka, atau tingkat untuk K13)
- `curriculum_structures` — struktur mata pelajaran per kelas/semester

### ⚠️ Yang masih kurang:

**`curriculums`** — master kurikulum (induk dari phases)
```
id, school_id, name (K13, Kurikulum Merdeka, Cambridge, IB),
code, type (national/international/local), 
start_year, end_year, is_active, description,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`curriculum_subjects`** — mata pelajaran per kurikulum (jika berbeda antar kurikulum)
```
id, curriculum_id, subject_id, code, name,
is_required, is_elective, category (umum/khusus/muatan lokal),
created_at, updated_at, deleted_at, created_by, updated_by
```

**`curriculum_learning_outcomes`** (CP/KD) — capaian pembelajaran
```
id, curriculum_phase_id, subject_id, code, 
element (sikap/pengetahuan/keterampilan), 
description, order, 
created_at, updated_at, deleted_at, created_by, updated_by
```

**`curriculum_objectives`** (TP/Indikator) — turunan dari CP
```
id, learning_outcome_id, code, description, 
criteria, order,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`student_curriculums`** — siswa mengambil kurikulum mana
```
id, student_id, curriculum_id, academic_year_id,
class_level_id, is_active, start_date, end_date,
created_at, updated_at, deleted_at, created_by, updated_by
```

---

## 2. Domain Penilaian (Assessment)

**`assessment_types`** — jenis penilaian
```
id, school_id, name (Formatif, Sumatif, Proyek, Portofolio, 
Praktikum, UTS, UAS, ANBK), code, weight_default, 
method (numeric/rubric/descriptive), is_active,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`assessment_methods`** — metode penilaian (rubrik, skala, dll)
```
id, school_id, name, code, scale_type (numeric/letter/rubric),
scale_config (json), description, is_active,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`grade_scales`** — skala nilai (A/B/C, 0-100, 1-4, dll) — bisa beda per kurikulum
```
id, school_id, curriculum_id, name, code,
min_score, max_score, grade_letter, grade_point,
description, order, is_active,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`assessments`** — header penilaian
```
id, school_id, curriculum_structure_id, 
assessment_type_id, assessment_method_id,
academic_year_id, semester, title, code,
description, max_score, weight, 
assessment_date, due_date, is_published,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`assessment_components`** — komponen detail (jika penilaian terdiri dari beberapa bagian)
```
id, assessment_id, name, weight, max_score, order,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`student_assessments`** — nilai siswa
```
id, assessment_id, student_id, 
assessment_component_id (nullable),
score, grade_letter, grade_point,
rubric_data (json), teacher_notes,
assessed_by, assessed_at, is_remedial,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`assessment_rubrics`** — rubrik penilaian
```
id, school_id, name, code, criteria (json), 
levels (json), description, is_active,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`student_rubric_scores`** — nilai per kriteria rubrik
```
id, student_assessment_id, rubric_criteria_id,
level_id, score, notes,
created_at, updated_at, deleted_at, created_by, updated_by
```

---

## 3. Domain Kompetensi & Sikap

**`competency_categories`** — kategori kompetensi (sikap spiritual, sosial, pengetahuan, keterampilan)
```
id, school_id, curriculum_id, name, code, 
description, order, is_active,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`student_competencies`** — capaian kompetensi siswa
```
id, student_id, curriculum_learning_outcome_id,
academic_year_id, semester,
score, predicate, description, 
created_at, updated_at, deleted_at, created_by, updated_by
```

**`attitude_assessments`** — penilaian sikap
```
id, student_id, academic_year_id, semester,
aspect (spiritual/sosial), predicate, 
description, assessor_id,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`student_attendances`** — kehadiran (biasanya masuk raport)
```
id, student_id, academic_year_id, semester,
sick, permission, absent, late, 
notes, created_at, updated_at, deleted_at, created_by, updated_by
```

**`extracurricular_activities`** & **`student_extracurriculars`** — ekskul (masuk raport)
```
-- extracurricular_activities
id, school_id, name, code, description, 
coach_id, is_active, created_at, updated_at, deleted_at

-- student_extracurriculars
id, student_id, extracurricular_id, academic_year_id,
score, predicate, description, 
created_at, updated_at, deleted_at, created_by, updated_by
```

---

## 4. Domain Raport

### ✅ Sudah ada:
- `generated_reports`
- `report_card_templates`

### ⚠️ Perlu dilengkapi:

**`report_card_template_sections`** — bagian-bagian template (identitas, nilai, sikap, dll)
```
id, report_card_template_id, section_type, 
title, order, config (json), 
created_at, updated_at, deleted_at, created_by, updated_by
```

**`report_card_components`** — komponen di dalam section
```
id, template_section_id, component_type, 
field_key, label, order, config (json),
created_at, updated_at, deleted_at, created_by, updated_by
```

**`report_card_template_curriculums`** — relasi template ke kurikulum
```
id, report_card_template_id, curriculum_id,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`report_periods`** — periode raport
```
id, school_id, academic_year_id, name (Semester 1, PTS, PAS),
type (mid/end), semester, start_date, end_date, 
is_active, created_at, updated_at, deleted_at, created_by, updated_by
```

**`report_card_grades`** — snapshot nilai per raport (opsional, jika ingin menyimpan detail nilai raport secara terpisah)
```
id, generated_report_id, subject_id, 
curriculum_structure_id,
knowledge_score, knowledge_predicate, knowledge_description,
skill_score, skill_predicate, skill_description,
final_score, final_predicate, rank, notes,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`report_card_notes`** — catatan wali kelas / kepala sekolah
```
id, generated_report_id, note_type (wali_kelas/kepsek),
note, author_id, created_at, updated_at, deleted_at
```

**`report_card_signatures`** — tanda tangan digital
```
id, generated_report_id, role (wali_kelas/kepsek/orang_tua),
user_id, signed_at, signature_path, 
created_at, updated_at, deleted_at
```

---

## 5. Domain Pendukung

**`academic_years`** (jika belum ada)
```
id, school_id, name, start_date, end_date, 
is_active, created_at, updated_at, deleted_at
```

**`semesters`** (jika belum ada)
```
id, academic_year_id, name (Ganjil/Genap), 
start_date, end_date, is_active
```

**`class_levels`**, **`subjects`**, **`students`**, **`teachers`** — diasumsikan sudah ada.

**`grade_weight_configs`** — konfigurasi bobot nilai per kurikulum
```
id, school_id, curriculum_id, academic_year_id,
assessment_type_id, weight, is_active,
created_at, updated_at, deleted_at, created_by, updated_by
```

**`student_promotions`** — kenaikan kelas (terkait kurikulum)
```
id, student_id, from_class_level_id, to_class_level_id,
academic_year_id, decision (naik/tinggal/lulus),
notes, created_at, updated_at, deleted_at
```

---

## 6. Relasi Kunci yang Perlu Diperhatikan

```
curriculums ──< curriculum_phases ──< curriculum_structures
                                        │
                                        ├──< assessments ──< student_assessments
                                        │
                                        └──< report_card_grades

report_card_templates ──< report_card_template_sections ──< components
        │
        └──< report_card_template_curriculums >── curriculums

generated_reports ──< report_card_grades
                  ──< report_card_notes
                  ──< report_card_signatures
```

---

## 7. Ringkasan Prioritas

| Prioritas | Tabel | Alasan |
|-----------|-------|--------|
| **Wajib** | `curriculums` | Induk multi-kurikulum |
| **Wajib** | `student_curriculums` | Mapping siswa↔kurikulum |
| **Wajib** | `grade_scales` | Skala nilai beda per kurikulum |
| **Wajib** | `assessment_types`, `assessments`, `student_assessments` | Inti penilaian |
| **Wajib** | `report_periods` | Periode raport |
| **Wajib** | `report_card_template_sections`, `report_card_components` | Template dinamis |
| **Wajib** | `report_card_grades` | Detail nilai raport |
| **Penting** | `curriculum_learning_outcomes`, `curriculum_objectives` | CP/TP |
| **Penting** | `attitude_assessments`, `student_attendances` | Komponen raport |
| **Opsional** | `assessment_rubrics`, `student_rubric_scores` | Jika pakai rubrik |
| **Opsional** | `report_card_signatures` | Tanda tangan digital |

---

## 8. Catatan Desain

1. **Multi-kurikulum**: kunci utamanya adalah `student_curriculums` — satu siswa bisa pindah kurikulum per tahun ajaran.
2. **Raport berbeda per kurikulum**: gunakan `report_card_template_curriculums` + `template_data` (JSON) agar fleksibel tanpa bikin tabel baru tiap kurikulum.
3. **Snapshot nilai**: simpan `report_card_grades` sebagai snapshot agar nilai raport tidak berubah jika nilai asli diedit.
4. **Soft delete**: konsisten gunakan `deleted_at` di semua tabel.
5. **JSON config**: manfaatkan kolom JSON (`template_data`, `rubric_data`, `scale_config`) untuk fleksibilitas lintas kurikulum.

Kalau Anda mau, saya bisa lanjutkan dengan **ERD** atau **contoh migration Laravel** untuk tabel-tabel prioritas di atas.

# SQL Query untuk Sistem Pendidikan Multi-Kurikulum

Berikut SQL lengkap mengikuti style yang Anda berikan (MySQL, utf8mb4, dengan soft delete, audit fields, index, dan foreign key). Saya urutkan berdasarkan dependency agar FK bisa dibuat.

> **Asumsi**: tabel `schools`, `students`, `teachers`, `users`, `subjects`, `class_levels`, `organizations`, `academic_years`, `semesters` sudah ada.

---

## 1. Domain Kurikulum

```sql
-- ----------------------------
-- Table structure for curriculums
-- ----------------------------
DROP TABLE IF EXISTS `curriculums`;
CREATE TABLE `curriculums`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('national','international','local','hybrid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'national',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `start_year` year NULL DEFAULT NULL,
  `end_year` year NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `curriculums_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `curriculums_school_index`(`school_id` ASC) USING BTREE,
  INDEX `curriculums_type_index`(`type` ASC) USING BTREE,
  INDEX `curriculums_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `curriculums_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `curriculums_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `curriculums_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `curriculums_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for curriculum_phases
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_phases`;
CREATE TABLE `curriculum_phases`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `age_range` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `class_levels` json NULL,
  `competencies` json NULL,
  `order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `curriculum_phases_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `curriculum_phases_school_index`(`school_id` ASC) USING BTREE,
  INDEX `curriculum_phases_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `curriculum_phases_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `curriculum_phases_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `curriculum_phases_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `curriculum_phases_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `curriculum_phases_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_phases_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for curriculum_subjects
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_subjects`;
CREATE TABLE `curriculum_subjects`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('umum','khusus','muatan_lokal','peminatan') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'umum',
  `is_required` tinyint(1) NOT NULL DEFAULT 1,
  `is_elective` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `curriculum_subjects_unique`(`curriculum_id` ASC, `subject_id` ASC) USING BTREE,
  INDEX `curriculum_subjects_school_index`(`school_id` ASC) USING BTREE,
  INDEX `curriculum_subjects_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `curriculum_subjects_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `curriculum_subjects_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `curriculum_subjects_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `curriculum_subjects_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `curriculum_subjects_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_subjects_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_subjects_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for curriculum_structures
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_structures`;
CREATE TABLE `curriculum_structures`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `organization_id` bigint UNSIGNED NULL DEFAULT NULL,
  `class_level_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `curriculum_phase_id` bigint UNSIGNED NULL DEFAULT NULL,
  `curriculum_id` bigint UNSIGNED NULL DEFAULT NULL,
  `semester` tinyint NOT NULL DEFAULT 1,
  `hours_per_week` int NULL DEFAULT NULL,
  `hour_duration` int NULL DEFAULT NULL,
  `is_core` tinyint(1) NOT NULL DEFAULT 1,
  `min_student` int NULL DEFAULT NULL,
  `max_student` int NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `curriculum_structures_unique`(`school_id` ASC, `academic_year_id` ASC, `class_level_id` ASC, `subject_id` ASC, `semester` ASC) USING BTREE,
  INDEX `curriculum_structures_school_index`(`school_id` ASC) USING BTREE,
  INDEX `curriculum_structures_org_index`(`organization_id` ASC) USING BTREE,
  INDEX `curriculum_structures_class_level_index`(`class_level_id` ASC) USING BTREE,
  INDEX `curriculum_structures_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `curriculum_structures_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `curriculum_structures_phase_index`(`curriculum_phase_id` ASC) USING BTREE,
  INDEX `curriculum_structures_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `curriculum_structures_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `curriculum_structures_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `curriculum_structures_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `curriculum_structures_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `curriculum_structures_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_org_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `curriculum_structures_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for curriculum_learning_outcomes
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_learning_outcomes`;
CREATE TABLE `curriculum_learning_outcomes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `curriculum_phase_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `element` enum('sikap','pengetahuan','keterampilan','spiritual','sosial') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pengetahuan',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `curriculum_learning_outcomes_unique`(`curriculum_phase_id` ASC, `subject_id` ASC, `code` ASC) USING BTREE,
  INDEX `curriculum_learning_outcomes_school_index`(`school_id` ASC) USING BTREE,
  INDEX `curriculum_learning_outcomes_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `curriculum_learning_outcomes_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `curriculum_learning_outcomes_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `curriculum_learning_outcomes_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `curriculum_learning_outcomes_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `curriculum_learning_outcomes_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_learning_outcomes_phase_id_foreign` FOREIGN KEY (`curriculum_phase_id`) REFERENCES `curriculum_phases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_learning_outcomes_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for curriculum_objectives
-- ----------------------------
DROP TABLE IF EXISTS `curriculum_objectives`;
CREATE TABLE `curriculum_objectives`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `learning_outcome_id` bigint UNSIGNED NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `criteria` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `curriculum_objectives_unique`(`learning_outcome_id` ASC, `code` ASC) USING BTREE,
  INDEX `curriculum_objectives_school_index`(`school_id` ASC) USING BTREE,
  INDEX `curriculum_objectives_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `curriculum_objectives_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `curriculum_objectives_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `curriculum_objectives_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `curriculum_objectives_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `curriculum_objectives_learning_outcome_id_foreign` FOREIGN KEY (`learning_outcome_id`) REFERENCES `curriculum_learning_outcomes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for student_curriculums
-- ----------------------------
DROP TABLE IF EXISTS `student_curriculums`;
CREATE TABLE `student_curriculums`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `class_level_id` bigint UNSIGNED NOT NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_curriculums_unique`(`student_id` ASC, `academic_year_id` ASC) USING BTREE,
  INDEX `student_curriculums_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_curriculums_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `student_curriculums_class_level_index`(`class_level_id` ASC) USING BTREE,
  INDEX `student_curriculums_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `student_curriculums_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_curriculums_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_curriculums_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_curriculums_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_curriculums_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_curriculums_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_curriculums_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_curriculums_class_level_id_foreign` FOREIGN KEY (`class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
```

---

## 2. Domain Penilaian (Assessment)

```sql
-- ----------------------------
-- Table structure for assessment_types
-- ----------------------------
DROP TABLE IF EXISTS `assessment_types`;
CREATE TABLE `assessment_types`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` enum('numeric','rubric','descriptive','letter') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'numeric',
  `weight_default` decimal(5,2) NOT NULL DEFAULT 0.00,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `assessment_types_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `assessment_types_school_index`(`school_id` ASC) USING BTREE,
  INDEX `assessment_types_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `assessment_types_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `assessment_types_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `assessment_types_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `assessment_types_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for assessment_methods
-- ----------------------------
DROP TABLE IF EXISTS `assessment_methods`;
CREATE TABLE `assessment_methods`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `scale_type` enum('numeric','letter','rubric','descriptive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'numeric',
  `scale_config` json NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `assessment_methods_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `assessment_methods_school_index`(`school_id` ASC) USING BTREE,
  INDEX `assessment_methods_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `assessment_methods_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `assessment_methods_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `assessment_methods_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `assessment_methods_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for grade_scales
-- ----------------------------
DROP TABLE IF EXISTS `grade_scales`;
CREATE TABLE `grade_scales`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_score` decimal(6,2) NOT NULL DEFAULT 0.00,
  `max_score` decimal(6,2) NOT NULL DEFAULT 100.00,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `grade_point` decimal(4,2) NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `grade_scales_unique`(`school_id` ASC, `curriculum_id` ASC, `code` ASC, `min_score` ASC) USING BTREE,
  INDEX `grade_scales_school_index`(`school_id` ASC) USING BTREE,
  INDEX `grade_scales_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `grade_scales_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `grade_scales_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `grade_scales_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `grade_scales_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `grade_scales_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_scales_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for grade_weight_configs
-- ----------------------------
DROP TABLE IF EXISTS `grade_weight_configs`;
CREATE TABLE `grade_weight_configs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `assessment_type_id` bigint UNSIGNED NOT NULL,
  `weight` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `grade_weight_configs_unique`(`school_id` ASC, `academic_year_id` ASC, `assessment_type_id` ASC, `curriculum_id` ASC) USING BTREE,
  INDEX `grade_weight_configs_school_index`(`school_id` ASC) USING BTREE,
  INDEX `grade_weight_configs_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `grade_weight_configs_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `grade_weight_configs_assessment_type_index`(`assessment_type_id` ASC) USING BTREE,
  INDEX `grade_weight_configs_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `grade_weight_configs_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `grade_weight_configs_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `grade_weight_configs_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `grade_weight_configs_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_weight_configs_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_weight_configs_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_weight_configs_assessment_type_id_foreign` FOREIGN KEY (`assessment_type_id`) REFERENCES `assessment_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for assessment_rubrics
-- ----------------------------
DROP TABLE IF EXISTS `assessment_rubrics`;
CREATE TABLE `assessment_rubrics`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `criteria` json NOT NULL,
  `levels` json NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `assessment_rubrics_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `assessment_rubrics_school_index`(`school_id` ASC) USING BTREE,
  INDEX `assessment_rubrics_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `assessment_rubrics_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `assessment_rubrics_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `assessment_rubrics_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `assessment_rubrics_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for assessments
-- ----------------------------
DROP TABLE IF EXISTS `assessments`;
CREATE TABLE `assessments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `curriculum_structure_id` bigint UNSIGNED NOT NULL,
  `assessment_type_id` bigint UNSIGNED NOT NULL,
  `assessment_method_id` bigint UNSIGNED NULL DEFAULT NULL,
  `assessment_rubric_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `semester` tinyint NOT NULL DEFAULT 1,
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `max_score` decimal(6,2) NOT NULL DEFAULT 100.00,
  `weight` decimal(5,2) NOT NULL DEFAULT 0.00,
  `assessment_date` date NULL DEFAULT NULL,
  `due_date` date NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `assessments_unique`(`school_id` ASC, `curriculum_structure_id` ASC, `academic_year_id` ASC, `semester` ASC, `code` ASC) USING BTREE,
  INDEX `assessments_school_index`(`school_id` ASC) USING BTREE,
  INDEX `assessments_structure_index`(`curriculum_structure_id` ASC) USING BTREE,
  INDEX `assessments_type_index`(`assessment_type_id` ASC) USING BTREE,
  INDEX `assessments_method_index`(`assessment_method_id` ASC) USING BTREE,
  INDEX `assessments_rubric_index`(`assessment_rubric_id` ASC) USING BTREE,
  INDEX `assessments_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `assessments_is_published_index`(`is_published` ASC) USING BTREE,
  INDEX `assessments_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `assessments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `assessments_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `assessments_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `assessments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assessments_structure_id_foreign` FOREIGN KEY (`curriculum_structure_id`) REFERENCES `curriculum_structures` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assessments_type_id_foreign` FOREIGN KEY (`assessment_type_id`) REFERENCES `assessment_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `assessments_method_id_foreign` FOREIGN KEY (`assessment_method_id`) REFERENCES `assessment_methods` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assessments_rubric_id_foreign` FOREIGN KEY (`assessment_rubric_id`) REFERENCES `assessment_rubrics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `assessments_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for assessment_components
-- ----------------------------
DROP TABLE IF EXISTS `assessment_components`;
CREATE TABLE `assessment_components`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `assessment_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` decimal(5,2) NOT NULL DEFAULT 0.00,
  `max_score` decimal(6,2) NOT NULL DEFAULT 100.00,
  `order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `assessment_components_assessment_index`(`assessment_id` ASC) USING BTREE,
  INDEX `assessment_components_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `assessment_components_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `assessment_components_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `assessment_components_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `assessment_components_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for student_assessments
-- ----------------------------
DROP TABLE IF EXISTS `student_assessments`;
CREATE TABLE `student_assessments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `assessment_id` bigint UNSIGNED NOT NULL,
  `assessment_component_id` bigint UNSIGNED NULL DEFAULT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `score` decimal(6,2) NULL DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `grade_point` decimal(4,2) NULL DEFAULT NULL,
  `rubric_data` json NULL,
  `teacher_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `assessed_by` bigint UNSIGNED NULL DEFAULT NULL,
  `assessed_at` timestamp NULL DEFAULT NULL,
  `is_remedial` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_assessments_unique`(`assessment_id` ASC, `assessment_component_id` ASC, `student_id` ASC) USING BTREE,
  INDEX `student_assessments_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_assessments_student_index`(`student_id` ASC) USING BTREE,
  INDEX `student_assessments_component_index`(`assessment_component_id` ASC) USING BTREE,
  INDEX `student_assessments_assessed_by_index`(`assessed_by` ASC) USING BTREE,
  INDEX `student_assessments_is_remedial_index`(`is_remedial` ASC) USING BTREE,
  INDEX `student_assessments_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `student_assessments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_assessments_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_assessments_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_assessments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_assessments_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_assessments_component_id_foreign` FOREIGN KEY (`assessment_component_id`) REFERENCES `assessment_components` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_assessments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_assessments_assessed_by_foreign` FOREIGN KEY (`assessed_by`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for student_rubric_scores
-- ----------------------------
DROP TABLE IF EXISTS `student_rubric_scores`;
CREATE TABLE `student_rubric_scores`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_assessment_id` bigint UNSIGNED NOT NULL,
  `criteria_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` decimal(6,2) NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_rubric_scores_unique`(`student_assessment_id` ASC, `criteria_key` ASC) USING BTREE,
  INDEX `student_rubric_scores_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_rubric_scores_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `student_rubric_scores_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_rubric_scores_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_rubric_scores_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_rubric_scores_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_rubric_scores_student_assessment_id_foreign` FOREIGN KEY (`student_assessment_id`) REFERENCES `student_assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
```

---

## 3. Domain Kompetensi, Sikap & Pendukung

```sql
-- ----------------------------
-- Table structure for competency_categories
-- ----------------------------
DROP TABLE IF EXISTS `competency_categories`;
CREATE TABLE `competency_categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `competency_categories_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `competency_categories_school_index`(`school_id` ASC) USING BTREE,
  INDEX `competency_categories_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `competency_categories_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `competency_categories_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `competency_categories_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `competency_categories_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `competency_categories_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `competency_categories_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for student_competencies
-- ----------------------------
DROP TABLE IF EXISTS `student_competencies`;
CREATE TABLE `student_competencies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `curriculum_learning_outcome_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `semester` tinyint NOT NULL DEFAULT 1,
  `score` decimal(6,2) NULL DEFAULT NULL,
  `predicate` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_competencies_unique`(`student_id` ASC, `curriculum_learning_outcome_id` ASC, `academic_year_id` ASC, `semester` ASC) USING BTREE,
  INDEX `student_competencies_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_competencies_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `student_competencies_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `student_competencies_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_competencies_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_competencies_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_competencies_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_competencies_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_competencies_outcome_id_foreign` FOREIGN KEY (`curriculum_learning_outcome_id`) REFERENCES `curriculum_learning_outcomes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_competencies_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for attitude_assessments
-- ----------------------------
DROP TABLE IF EXISTS `attitude_assessments`;
CREATE TABLE `attitude_assessments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `semester` tinyint NOT NULL DEFAULT 1,
  `aspect` enum('spiritual','sosial') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `predicate` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `assessor_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `attitude_assessments_unique`(`student_id` ASC, `academic_year_id` ASC, `semester` ASC, `aspect` ASC) USING BTREE,
  INDEX `attitude_assessments_school_index`(`school_id` ASC) USING BTREE,
  INDEX `attitude_assessments_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `attitude_assessments_assessor_index`(`assessor_id` ASC) USING BTREE,
  INDEX `attitude_assessments_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `attitude_assessments_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `attitude_assessments_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `attitude_assessments_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `attitude_assessments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attitude_assessments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attitude_assessments_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attitude_assessments_assessor_id_foreign` FOREIGN KEY (`assessor_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for student_attendances
-- ----------------------------
DROP TABLE IF EXISTS `student_attendances`;
CREATE TABLE `student_attendances`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `semester` tinyint NOT NULL DEFAULT 1,
  `sick` int NOT NULL DEFAULT 0,
  `permission` int NOT NULL DEFAULT 0,
  `absent` int NOT NULL DEFAULT 0,
  `late` int NOT NULL DEFAULT 0,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_attendances_unique`(`student_id` ASC, `academic_year_id` ASC, `semester` ASC) USING BTREE,
  INDEX `student_attendances_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_attendances_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `student_attendances_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `student_attendances_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_attendances_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_attendances_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_attendances_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_attendances_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for extracurricular_activities
-- ----------------------------
DROP TABLE IF EXISTS `extracurricular_activities`;
CREATE TABLE `extracurricular_activities`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `coach_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `extracurricular_activities_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `extracurricular_activities_school_index`(`school_id` ASC) USING BTREE,
  INDEX `extracurricular_activities_coach_index`(`coach_id` ASC) USING BTREE,
  INDEX `extracurricular_activities_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `extracurricular_activities_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `extracurricular_activities_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `extracurricular_activities_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `extracurricular_activities_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `extracurricular_activities_coach_id_foreign` FOREIGN KEY (`coach_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for student_extracurriculars
-- ----------------------------
DROP TABLE IF EXISTS `student_extracurriculars`;
CREATE TABLE `student_extracurriculars`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `extracurricular_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `score` decimal(6,2) NULL DEFAULT NULL,
  `predicate` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_extracurriculars_unique`(`student_id` ASC, `extracurricular_id` ASC, `academic_year_id` ASC) USING BTREE,
  INDEX `student_extracurriculars_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_extracurriculars_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `student_extracurriculars_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `student_extracurriculars_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_extracurriculars_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_extracurriculars_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_extracurriculars_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_extracurriculars_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_extracurriculars_extracurricular_id_foreign` FOREIGN KEY (`extracurricular_id`) REFERENCES `extracurricular_activities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_extracurriculars_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for student_promotions
-- ----------------------------
DROP TABLE IF EXISTS `student_promotions`;
CREATE TABLE `student_promotions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `from_class_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `to_class_level_id` bigint UNSIGNED NULL DEFAULT NULL,
  `decision` enum('naik','tinggal','lulus','pindah') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `student_promotions_unique`(`student_id` ASC, `academic_year_id` ASC) USING BTREE,
  INDEX `student_promotions_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_promotions_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `student_promotions_from_class_index`(`from_class_level_id` ASC) USING BTREE,
  INDEX `student_promotions_to_class_index`(`to_class_level_id` ASC) USING BTREE,
  INDEX `student_promotions_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `student_promotions_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `student_promotions_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `student_promotions_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `student_promotions_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_from_class_id_foreign` FOREIGN KEY (`from_class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_promotions_to_class_id_foreign` FOREIGN KEY (`to_class_level_id`) REFERENCES `class_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
```

---

## 4. Domain Raport

```sql
-- ----------------------------
-- Table structure for report_periods
-- ----------------------------
DROP TABLE IF EXISTS `report_periods`;
CREATE TABLE `report_periods`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('mid','end','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'end',
  `semester` tinyint NOT NULL DEFAULT 1,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `report_periods_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `report_periods_school_index`(`school_id` ASC) USING BTREE,
  INDEX `report_periods_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `report_periods_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `report_periods_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `report_periods_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `report_periods_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `report_periods_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_periods_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for report_card_templates
-- ----------------------------
DROP TABLE IF EXISTS `report_card_templates`;
CREATE TABLE `report_card_templates`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_data` json NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `version` int NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `report_card_templates_school_code_unique`(`school_id` ASC, `code` ASC) USING BTREE,
  INDEX `report_card_templates_school_index`(`school_id` ASC) USING BTREE,
  INDEX `report_card_templates_is_default_index`(`is_default` ASC) USING BTREE,
  INDEX `report_card_templates_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `report_card_templates_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `report_card_templates_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `report_card_templates_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `report_card_templates_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for report_card_template_sections
-- ----------------------------
DROP TABLE IF EXISTS `report_card_template_sections`;
CREATE TABLE `report_card_template_sections`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `report_card_template_id` bigint UNSIGNED NOT NULL,
  `section_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  `config` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `report_card_template_sections_template_index`(`report_card_template_id` ASC) USING BTREE,
  INDEX `report_card_template_sections_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `report_card_template_sections_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `report_card_template_sections_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `report_card_template_sections_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `report_card_template_sections_template_id_foreign` FOREIGN KEY (`report_card_template_id`) REFERENCES `report_card_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for report_card_components
-- ----------------------------
DROP TABLE IF EXISTS `report_card_components`;
CREATE TABLE `report_card_components`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `report_card_template_section_id` bigint UNSIGNED NOT NULL,
  `component_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  `config` json NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `report_card_components_section_index`(`report_card_template_section_id` ASC) USING BTREE,
  INDEX `report_card_components_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `report_card_components_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `report_card_components_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `report_card_components_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `report_card_components_section_id_foreign` FOREIGN KEY (`report_card_template_section_id`) REFERENCES `report_card_template_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for report_card_template_curriculums
-- ----------------------------
DROP TABLE IF EXISTS `report_card_template_curriculums`;
CREATE TABLE `report_card_template_curriculums`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `report_card_template_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `report_card_template_curriculums_unique`(`report_card_template_id` ASC, `curriculum_id` ASC) USING BTREE,
  INDEX `report_card_template_curriculums_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `report_card_template_curriculums_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `report_card_template_curriculums_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `report_card_template_curriculums_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `report_card_template_curriculums_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `report_card_template_curriculums_template_id_foreign` FOREIGN KEY (`report_card_template_id`) REFERENCES `report_card_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_card_template_curriculums_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for generated_reports
-- ----------------------------
DROP TABLE IF EXISTS `generated_reports`;
CREATE TABLE `generated_reports`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `report_card_template_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NULL DEFAULT NULL,
  `pdf_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `report_data` json NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `downloaded_count` int NOT NULL DEFAULT 0,
  `last_download_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `generated_reports_unique`(`student_id` ASC, `period_id` ASC) USING BTREE,
  INDEX `generated_reports_school_index`(`school_id` ASC) USING BTREE,
  INDEX `generated_reports_template_index`(`report_card_template_id` ASC) USING BTREE,
  INDEX `generated_reports_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `generated_reports_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `generated_reports_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `generated_reports_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `generated_reports_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `generated_reports_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `report_periods` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_template_id_foreign` FOREIGN KEY (`report_card_template_id`) REFERENCES `report_card_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `generated_reports_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for report_card_grades
-- ----------------------------
DROP TABLE IF EXISTS `report_card_grades`;
CREATE TABLE `report_card_grades`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `generated_report_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `curriculum_structure_id` bigint UNSIGNED NULL DEFAULT NULL,
  `knowledge_score` decimal(6,2) NULL DEFAULT NULL,
  `knowledge_predicate` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `knowledge_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `skill_score` decimal(6,2) NULL DEFAULT NULL,
  `skill_predicate` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `skill_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `final_score` decimal(6,2) NULL DEFAULT NULL,
  `final_predicate` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `rank` int NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `report_card_grades_unique`(`generated_report_id` ASC, `subject_id` ASC) USING BTREE,
  INDEX `report_card_grades_school_index`(`school_id` ASC) USING BTREE,
  INDEX `report_card_grades_subject_index`(`subject_id` ASC) USING BTREE,
  INDEX `report_card_grades_structure_index`(`curriculum_structure_id` ASC) USING BTREE,
  INDEX `report_card_grades_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `report_card_grades_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `report_card_grades_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `report_card_grades_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `report_card_grades_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_card_grades_report_id_foreign` FOREIGN KEY (`generated_report_id`) REFERENCES `generated_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_card_grades_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_card_grades_structure_id_foreign` FOREIGN KEY (`curriculum_structure_id`) REFERENCES `curriculum_structures` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for report_card_notes
-- ----------------------------
DROP TABLE IF EXISTS `report_card_notes`;
CREATE TABLE `report_card_notes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `generated_report_id` bigint UNSIGNED NOT NULL,
  `note_type` enum('wali_kelas','kepsek','orang_tua','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `report_card_notes_report_index`(`generated_report_id` ASC) USING BTREE,
  INDEX `report_card_notes_author_index`(`author_id` ASC) USING BTREE,
  INDEX `report_card_notes_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `report_card_notes_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `report_card_notes_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `report_card_notes_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `report_card_notes_report_id_foreign` FOREIGN KEY (`generated_report_id`) REFERENCES `generated_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_card_notes_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for report_card_signatures
-- ----------------------------
DROP TABLE IF EXISTS `report_card_signatures`;
CREATE TABLE `report_card_signatures`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `generated_report_id` bigint UNSIGNED NOT NULL,
  `role` enum('wali_kelas','kepsek','orang_tua','lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `signed_at` timestamp NULL DEFAULT NULL,
  `signature_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `report_card_signatures_report_index`(`generated_report_id` ASC) USING BTREE,
  INDEX `report_card_signatures_user_index`(`user_id` ASC) USING BTREE,
  INDEX `report_card_signatures_is_active_index`(`is_active` ASC) USING BTREE,
  INDEX `report_card_signatures_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  INDEX `report_card_signatures_created_by_index`(`created_by` ASC) USING BTREE,
  INDEX `report_card_signatures_updated_by_index`(`updated_by` ASC) USING BTREE,
  CONSTRAINT `report_card_signatures_report_id_foreign` FOREIGN KEY (`generated_report_id`) REFERENCES `generated_reports` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `report_card_signatures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
```

---

## Ringkasan Urutan Eksekusi

Eksekusi SQL dengan urutan berikut agar FK tidak error:

1. `curriculums`
2. `curriculum_phases`
3. `curriculum_subjects`
4. `curriculum_structures`
5. `curriculum_learning_outcomes`
6. `curriculum_objectives`
7. `student_curriculums`
8. `assessment_types`
9. `assessment_methods`
10. `grade_scales`
11. `grade_weight_configs`
12. `assessment_rubrics`
13. `assessments`
14. `assessment_components`
15. `student_assessments`
16. `student_rubric_scores`
17. `competency_categories`
18. `student_competencies`
19. `attitude_assessments`
20. `student_attendances`
21. `extracurricular_activities`
22. `student_extracurriculars`
23. `student_promotions`
24. `report_periods`
25. `report_card_templates`
26. `report_card_template_sections`
27. `report_card_components`
28. `report_card_template_curriculums`
29. `generated_reports`
30. `report_card_grades`
31. `report_card_notes`
32. `report_card_signatures`

---

## Catatan Penting

1. **`report_card_templates`** yang Anda berikan tidak saya ubah, hanya saya tambahkan `curriculum_id` di tabel relasi (`report_card_template_curriculums`) agar template bisa dipakai lintas kurikulum.
2. **`generated_reports`** saya tambahkan `school_id` dan `curriculum_id` agar konsisten dengan multi-tenant & multi-kurikulum (kalau di sistem Anda `school_id` tidak diperlukan di sini, tinggal dihapus).
3. **JSON columns**: `template_data`, `rubric_data`, `criteria`, `levels`, `config`, `scale_config`, `report_data` — semua `NULL` kecuali yang wajib.
4. **Foreign key `users`** dipakai untuk `report_card_notes.author_id` & `report_card_signatures.user_id`. Kalau di sistem Anda penulis catatan adalah `teachers`, tinggal ganti referensinya.
5. **Enum** dipakai untuk nilai yang terbatas; kalau ingin lebih fleksibel, ubah ke `varchar` + tabel referensi.

# Analisis Dukungan Tabel + Seeder Contoh

## Bagian 1: Apakah Tabel Sudah Mendukung 5 Kurikulum Tersebut?

| Kurikulum | Didukung? | Catatan |
|-----------|-----------|---------|
| **KTSP (2006)** | ✅ Didukung | Pakai `curriculums.type='national'`, phase = tingkat kelas (I-VI, VII-IX, X-XII). Kompetensi: SK/KD. |
| **Kurikulum 2013 (K-13)** | ✅ Didukung | Pakai `curriculums.type='national'`, phase = tingkat kelas. Kompetensi: KI-1 s/d KI-4 + KD. Penilaian: pengetahuan (angka), keterampilan (predikat A-D), sikap (SB/B/C/K). |
| **Kurikulum Merdeka** | ✅ Didukung | Phase = Fase A-F. Kompetensi: CP → TP. Penilaian: formatif/sumatif, P5, deskripsi kualitatif. |
| **Cambridge** | ⚠️ **Perlu penyesuaian** | Butuh **grade scale sendiri (A*-U / 1-9)**, **component-based assessment (Paper 1, 2, 3)**, **weighted total**, **threshold per grade (grade boundaries)**, dan **IGCSE/AS/A-Level** sebagai level, bukan "semester". |
| **Montessori** | ⚠️ **Perlu penyesuaian** | Tidak pakai "kelas/semester" konvensional, tapi **multi-age grouping (Casa, Lower El, Upper El, Erdkinder)**, **individual learning plan**, **mastery-based** (bukan nilai angka), **portofolio & observasi naratif**. |

### Kesimpulan cepat:
- **KTSP, K-13, Kurikulum Merdeka** → tabel Anda **sudah cukup** tanpa perubahan.
- **Cambridge** → butuh tambahan: `grade_boundaries`, `assessment_papers` (komponen berjenjang), dan mapping level Cambridge di `class_levels`/`curriculum_phases`.
- **Montessori** → butuh tambahan: `student_learning_plans`, `mastery_tracking`, `observation_notes`, dan `age_groups`.

---

## Bagian 2: Tambahan Tabel (Opsional, untuk Cambridge & Montessori)

```sql
-- ----------------------------
-- Table structure for grade_boundaries (khusus Cambridge & mastery)
-- ----------------------------
DROP TABLE IF EXISTS `grade_boundaries`;
CREATE TABLE `grade_boundaries`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NOT NULL,
  `assessment_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NULL DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_score` decimal(6,2) NOT NULL,
  `max_score` decimal(6,2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `grade_boundaries_school_index`(`school_id` ASC) USING BTREE,
  INDEX `grade_boundaries_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `grade_boundaries_assessment_index`(`assessment_id` ASC) USING BTREE,
  INDEX `grade_boundaries_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `grade_boundaries_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_boundaries_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_boundaries_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for student_learning_plans (khusus Montessori)
-- ----------------------------
DROP TABLE IF EXISTS `student_learning_plans`;
CREATE TABLE `student_learning_plans`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `age_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `plan_data` json NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_learning_plans_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_learning_plans_student_index`(`student_id` ASC) USING BTREE,
  INDEX `student_learning_plans_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `student_learning_plans_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `student_learning_plans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_learning_plans_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_learning_plans_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for mastery_tracking (Montessori & mastery-based)
-- ----------------------------
DROP TABLE IF EXISTS `mastery_tracking`;
CREATE TABLE `mastery_tracking`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `curriculum_objective_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `status` enum('not_started','in_progress','mastered','needs_review') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_started',
  `mastered_at` date NULL DEFAULT NULL,
  `observation_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mastery_tracking_unique`(`student_id` ASC, `curriculum_objective_id` ASC, `academic_year_id` ASC) USING BTREE,
  INDEX `mastery_tracking_school_index`(`school_id` ASC) USING BTREE,
  INDEX `mastery_tracking_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `mastery_tracking_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `mastery_tracking_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mastery_tracking_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mastery_tracking_objective_id_foreign` FOREIGN KEY (`curriculum_objective_id`) REFERENCES `curriculum_objectives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mastery_tracking_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
```

---

## Bagian 3: Seeder Contoh 5 Kurikulum

Seeder ini mengasumsikan `school_id = 1` dan `academic_year_id = 1` sudah ada. Ganti sesuai kebutuhan.

```sql
-- =====================================================
-- 1. MASTER CURRICULUMS
-- =====================================================
INSERT INTO `curriculums` 
(`school_id`, `name`, `code`, `type`, `description`, `start_year`, `end_year`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Kurikulum Tingkat Satuan Pendidikan', 'KTSP', 'national', 'Kurikulum 2006 berbasis SK/KD', 2006, 2013, 1, NOW(), NOW()),
(1, 'Kurikulum 2013', 'K13', 'national', 'Kurikulum 2013 berbasis KI/KD dengan pendekatan saintifik', 2013, 2022, 1, NOW(), NOW()),
(1, 'Kurikulum Merdeka', 'MERDEKA', 'national', 'Kurikulum Merdeka berbasis Capaian Pembelajaran (CP)', 2022, NULL, 1, NOW(), NOW()),
(1, 'Cambridge International', 'CAMBRIDGE', 'international', 'Cambridge Primary / Lower Secondary / IGCSE / AS / A Level', 2020, NULL, 1, NOW(), NOW()),
(1, 'Montessori', 'MONTESSORI', 'international', 'Montessori multi-age, mastery-based, individual learning plan', 2020, NULL, 1, NOW(), NOW());


-- =====================================================
-- 2. CURRICULUM PHASES
-- =====================================================

-- KTSP: fase per jenjang
INSERT INTO `curriculum_phases` 
(`school_id`, `curriculum_id`, `name`, `code`, `description`, `age_range`, `class_levels`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 1, 'Kelas I - VI', 'KTSP-SD', 'Jenjang Sekolah Dasar', '6-12', JSON_ARRAY(1,2,3,4,5,6), 1, 1, NOW(), NOW()),
(1, 1, 'Kelas VII - IX', 'KTSP-SMP', 'Jenjang Sekolah Menengah Pertama', '12-15', JSON_ARRAY(7,8,9), 2, 1, NOW(), NOW()),
(1, 1, 'Kelas X - XII', 'KTSP-SMA', 'Jenjang Sekolah Menengah Atas', '15-18', JSON_ARRAY(10,11,12), 3, 1, NOW(), NOW());

-- K13: fase per jenjang (K13 tidak pakai Fase A-F)
INSERT INTO `curriculum_phases` 
(`school_id`, `curriculum_id`, `name`, `code`, `description`, `age_range`, `class_levels`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 2, 'Kelas I - VI', 'K13-SD', 'Jenjang SD dengan KI/KD per kelas', '6-12', JSON_ARRAY(1,2,3,4,5,6), 1, 1, NOW(), NOW()),
(1, 2, 'Kelas VII - IX', 'K13-SMP', 'Jenjang SMP dengan KI/KD per kelas', '12-15', JSON_ARRAY(7,8,9), 2, 1, NOW(), NOW()),
(1, 2, 'Kelas X - XII', 'K13-SMA', 'Jenjang SMA dengan KI/KD + peminatan', '15-18', JSON_ARRAY(10,11,12), 3, 1, NOW(), NOW());

-- Kurikulum Merdeka: Fase A - F
INSERT INTO `curriculum_phases` 
(`school_id`, `curriculum_id`, `name`, `code`, `description`, `age_range`, `class_levels`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 3, 'Fase A', 'MERDEKA-A', 'Fase Fondasi kelas 1-2 SD', '6-8', JSON_ARRAY(1,2), 1, 1, NOW(), NOW()),
(1, 3, 'Fase B', 'MERDEKA-B', 'Fase kelas 3-4 SD', '8-10', JSON_ARRAY(3,4), 2, 1, NOW(), NOW()),
(1, 3, 'Fase C', 'MERDEKA-C', 'Fase kelas 5-6 SD', '10-12', JSON_ARRAY(5,6), 3, 1, NOW(), NOW()),
(1, 3, 'Fase D', 'MERDEKA-D', 'Fase kelas 7-9 SMP', '12-15', JSON_ARRAY(7,8,9), 4, 1, NOW(), NOW()),
(1, 3, 'Fase E', 'MERDEKA-E', 'Fase kelas 10 SMA/SMK', '15-16', JSON_ARRAY(10), 5, 1, NOW(), NOW()),
(1, 3, 'Fase F', 'MERDEKA-F', 'Fase kelas 11-12 SMA/SMK', '16-18', JSON_ARRAY(11,12), 6, 1, NOW(), NOW());

-- Cambridge: Primary, Lower Secondary, IGCSE, AS/A Level
INSERT INTO `curriculum_phases` 
(`school_id`, `curriculum_id`, `name`, `code`, `description`, `age_range`, `class_levels`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 4, 'Cambridge Primary', 'CAM-PRIMARY', 'Stage 1-6', '5-11', JSON_ARRAY(1,2,3,4,5,6), 1, 1, NOW(), NOW()),
(1, 4, 'Cambridge Lower Secondary', 'CAM-LOWER', 'Stage 7-9', '11-14', JSON_ARRAY(7,8,9), 2, 1, NOW(), NOW()),
(1, 4, 'Cambridge IGCSE', 'CAM-IGCSE', 'Grade 10-11', '14-16', JSON_ARRAY(10,11), 3, 1, NOW(), NOW()),
(1, 4, 'Cambridge AS & A Level', 'CAM-ALEVEL', 'Grade 12', '16-18', JSON_ARRAY(12), 4, 1, NOW(), NOW());

-- Montessori: Casa, Lower El, Upper El, Erdkinder
INSERT INTO `curriculum_phases` 
(`school_id`, `curriculum_id`, `name`, `code`, `description`, `age_range`, `class_levels`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 5, 'Casa dei Bambini', 'MON-CASA', 'Primary 3-6 tahun', '3-6', JSON_ARRAY(1,2), 1, 1, NOW(), NOW()),
(1, 5, 'Lower Elementary', 'MON-LOWER', 'Elementary 6-9 tahun', '6-9', JSON_ARRAY(3,4,5), 2, 1, NOW(), NOW()),
(1, 5, 'Upper Elementary', 'MON-UPPER', 'Elementary 9-12 tahun', '9-12', JSON_ARRAY(6,7,8), 3, 1, NOW(), NOW()),
(1, 5, 'Erdkinder', 'MON-ERDKINDER', 'Adolescent 12-15 tahun', '12-15', JSON_ARRAY(9,10), 4, 1, NOW(), NOW());


-- =====================================================
-- 3. GRADE SCALES (Skala Nilai per Kurikulum)
-- =====================================================

-- KTSP: skala 0-100 + predikat A-D
INSERT INTO `grade_scales` 
(`school_id`, `curriculum_id`, `name`, `code`, `min_score`, `max_score`, `grade_letter`, `grade_point`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 1, 'KTSP A', 'KTSP-A', 90.00, 100.00, 'A', 4.00, 'Sangat Baik', 1, 1, NOW(), NOW()),
(1, 1, 'KTSP B', 'KTSP-B', 80.00, 89.99, 'B', 3.00, 'Baik', 2, 1, NOW(), NOW()),
(1, 1, 'KTSP C', 'KTSP-C', 70.00, 79.99, 'C', 2.00, 'Cukup', 3, 1, NOW(), NOW()),
(1, 1, 'KTSP D', 'KTSP-D', 0.00, 69.99, 'D', 1.00, 'Kurang', 4, 1, NOW(), NOW());

-- K13: pengetahuan angka + predikat, sikap SB/B/C/K
INSERT INTO `grade_scales` 
(`school_id`, `curriculum_id`, `name`, `code`, `min_score`, `max_score`, `grade_letter`, `grade_point`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 2, 'K13 A', 'K13-A', 90.00, 100.00, 'A', 4.00, 'Sangat Baik', 1, 1, NOW(), NOW()),
(1, 2, 'K13 B', 'K13-B', 80.00, 89.99, 'B', 3.00, 'Baik', 2, 1, NOW(), NOW()),
(1, 2, 'K13 C', 'K13-C', 70.00, 79.99, 'C', 2.00, 'Cukup', 3, 1, NOW(), NOW()),
(1, 2, 'K13 D', 'K13-D', 0.00, 69.99, 'D', 1.00, 'Perlu Bimbingan', 4, 1, NOW(), NOW()),
(1, 2, 'K13 SB', 'K13-SB', 90.00, 100.00, 'SB', 4.00, 'Sangat Baik (Sikap)', 5, 1, NOW(), NOW()),
(1, 2, 'K13 B (Sikap)', 'K13-BS', 80.00, 89.99, 'B', 3.00, 'Baik (Sikap)', 6, 1, NOW(), NOW()),
(1, 2, 'K13 C (Sikap)', 'K13-CS', 70.00, 79.99, 'C', 2.00, 'Cukup (Sikap)', 7, 1, NOW(), NOW()),
(1, 2, 'K13 K', 'K13-K', 0.00, 69.99, 'K', 1.00, 'Kurang (Sikap)', 8, 1, NOW(), NOW());

-- Kurikulum Merdeka: 4 kategori (Belum Berkembang - Sangat Berkembang)
INSERT INTO `grade_scales` 
(`school_id`, `curriculum_id`, `name`, `code`, `min_score`, `max_score`, `grade_letter`, `grade_point`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 3, 'Merdeka BB', 'MERDEKA-BB', 0.00, 59.99, 'BB', 1.00, 'Belum Berkembang', 1, 1, NOW(), NOW()),
(1, 3, 'Merdeka MB', 'MERDEKA-MB', 60.00, 74.99, 'MB', 2.00, 'Mulai Berkembang', 2, 1, NOW(), NOW()),
(1, 3, 'Merdeka BSH', 'MERDEKA-BSH', 75.00, 89.99, 'BSH', 3.00, 'Berkembang Sesuai Harapan', 3, 1, NOW(), NOW()),
(1, 3, 'Merdeka SB', 'MERDEKA-SB', 90.00, 100.00, 'SB', 4.00, 'Sangat Berkembang', 4, 1, NOW(), NOW());

-- Cambridge: A* - U (IGCSE) + 1-9 (Lower Secondary)
INSERT INTO `grade_scales` 
(`school_id`, `curriculum_id`, `name`, `code`, `min_score`, `max_score`, `grade_letter`, `grade_point`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 4, 'Cambridge A*', 'CAM-ASTAR', 90.00, 100.00, 'A*', 4.00, 'Outstanding', 1, 1, NOW(), NOW()),
(1, 4, 'Cambridge A', 'CAM-A', 80.00, 89.99, 'A', 4.00, 'Excellent', 2, 1, NOW(), NOW()),
(1, 4, 'Cambridge B', 'CAM-B', 70.00, 79.99, 'B', 3.00, 'Very Good', 3, 1, NOW(), NOW()),
(1, 4, 'Cambridge C', 'CAM-C', 60.00, 69.99, 'C', 2.00, 'Good', 4, 1, NOW(), NOW()),
(1, 4, 'Cambridge D', 'CAM-D', 50.00, 59.99, 'D', 1.00, 'Satisfactory', 5, 1, NOW(), NOW()),
(1, 4, 'Cambridge E', 'CAM-E', 40.00, 49.99, 'E', 1.00, 'Pass', 6, 1, NOW(), NOW()),
(1, 4, 'Cambridge U', 'CAM-U', 0.00, 39.99, 'U', 0.00, 'Ungraded', 7, 1, NOW(), NOW());

-- Montessori: mastery-based (bukan angka)
INSERT INTO `grade_scales` 
(`school_id`, `curriculum_id`, `name`, `code`, `min_score`, `max_score`, `grade_letter`, `grade_point`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 5, 'Montessori Not Yet', 'MON-NY', 0.00, 49.99, 'NY', 1.00, 'Not Yet - belum dikuasai', 1, 1, NOW(), NOW()),
(1, 5, 'Montessori Developing', 'MON-DEV', 50.00, 74.99, 'DEV', 2.00, 'Developing - sedang berkembang', 2, 1, NOW(), NOW()),
(1, 5, 'Montessori Mastered', 'MON-M', 75.00, 100.00, 'M', 4.00, 'Mastered - sudah dikuasai', 3, 1, NOW(), NOW());


-- =====================================================
-- 4. ASSESSMENT TYPES (per kurikulum, opsional school-wide)
-- =====================================================

-- KTSP
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Ulangan Harian KTSP', 'UH-KTSP', 'numeric', 20.00, 'Ulangan harian KTSP', 1, NOW(), NOW()),
(1, 'Ulangan Tengah Semester KTSP', 'UTS-KTSP', 'numeric', 30.00, 'UTS KTSP', 1, NOW(), NOW()),
(1, 'Ulangan Akhir Semester KTSP', 'UAS-KTSP', 'numeric', 50.00, 'UAS KTSP', 1, NOW(), NOW());

-- K13
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Penilaian Harian K13', 'PH-K13', 'numeric', 25.00, 'PH K13', 1, NOW(), NOW()),
(1, 'PTS K13', 'PTS-K13', 'numeric', 30.00, 'Penilaian Tengah Semester', 1, NOW(), NOW()),
(1, 'PAS K13', 'PAS-K13', 'numeric', 45.00, 'Penilaian Akhir Semester', 1, NOW(), NOW()),
(1, 'Penilaian Sikap K13', 'SIKAP-K13', 'descriptive', 0.00, 'Observasi sikap spiritual & sosial', 1, NOW(), NOW()),
(1, 'Penilaian Keterampilan K13', 'KET-K13', 'rubric', 0.00, 'Praktik, proyek, portofolio', 1, NOW(), NOW());

-- Kurikulum Merdeka
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Asesmen Formatif', 'FORMATIF-MERDEKA', 'rubric', 40.00, 'Asesmen formatif (proses)', 1, NOW(), NOW()),
(1, 'Asesmen Sumatif', 'SUMATIF-MERDEKA', 'numeric', 60.00, 'Asesmen sumatif (akhir lingkup materi)', 1, NOW(), NOW()),
(1, 'Proyek P5', 'P5-MERDEKA', 'rubric', 0.00, 'Projek Penguatan Profil Pelajar Pancasila', 1, NOW(), NOW());

-- Cambridge
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Cambridge Paper 1', 'CAM-P1', 'numeric', 30.00, 'Component Paper 1', 1, NOW(), NOW()),
(1, 'Cambridge Paper 2', 'CAM-P2', 'numeric', 30.00, 'Component Paper 2', 1, NOW(), NOW()),
(1, 'Cambridge Paper 3', 'CAM-P3', 'numeric', 40.00, 'Component Paper 3', 1, NOW(), NOW()),
(1, 'Cambridge Coursework', 'CAM-CW', 'rubric', 0.00, 'Coursework / Practical', 1, NOW(), NOW());

-- Montessori
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Observasi Montessori', 'MON-OBS', 'descriptive', 0.00, 'Observasi harian guru', 1, NOW(), NOW()),
(1, 'Mastery Check', 'MON-MASTERY', 'rubric', 0.00, 'Cek penguasaan materi', 1, NOW(), NOW()),
(1, 'Portofolio Montessori', 'MON-PORT', 'descriptive', 0.00, 'Portofolio karya siswa', 1, NOW(), NOW());


-- =====================================================
-- 5. REPORT CARD TEMPLATES (1 template per kurikulum)
-- =====================================================
INSERT INTO `report_card_templates` 
(`school_id`, `name`, `code`, `template_data`, `is_default`, `is_active`, `version`, `created_at`, `updated_at`) 
VALUES
(1, 'Raport KTSP', 'TPL-KTSP', 
 JSON_OBJECT('layout','ktsp','sections', JSON_ARRAY('identitas','nilai','sikap','kehadiran','catatan')),
 0, 1, 1, NOW(), NOW()),

(1, 'Raport K13', 'TPL-K13', 
 JSON_OBJECT('layout','k13','sections', JSON_ARRAY('identitas','nilai_pengetahuan','nilai_keterampilan','sikap_spiritual','sikap_sosial','kehadiran','ekstrakurikuler','catatan')),
 0, 1, 1, NOW(), NOW()),

(1, 'Raport Kurikulum Merdeka', 'TPL-MERDEKA', 
 JSON_OBJECT('layout','merdeka','sections', JSON_ARRAY('identitas','nilai_intrakurikuler','p5','kehadiran','ekstrakurikuler','catatan')),
 0, 1, 1, NOW(), NOW()),

(1, 'Report Card Cambridge', 'TPL-CAMBRIDGE', 
 JSON_OBJECT('layout','cambridge','sections', JSON_ARRAY('identitas','subject_grades','grade_boundaries','attendance','tutor_comment')),
 0, 1, 1, NOW(), NOW()),

(1, 'Montessori Progress Report', 'TPL-MONTESSORI', 
 JSON_OBJECT('layout','montessori','sections', JSON_ARRAY('identitas','learning_plan','mastery','observation','portfolio')),
 0, 1, 1, NOW(), NOW());


-- =====================================================
-- 6. MAPPING TEMPLATE ↔ CURRICULUM
-- =====================================================
INSERT INTO `report_card_template_curriculums` 
(`report_card_template_id`, `curriculum_id`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 1, 1, NOW(), NOW()),  -- KTSP
(2, 2, 1, NOW(), NOW()),  -- K13
(3, 3, 1, NOW(), NOW()),  -- Merdeka
(4, 4, 1, NOW(), NOW()),  -- Cambridge
(5, 5, 1, NOW(), NOW());  -- Montessori


-- =====================================================
-- 7. GRADE WEIGHT CONFIGS (contoh untuk K13 & Merdeka)
-- =====================================================
INSERT INTO `grade_weight_configs` 
(`school_id`, `curriculum_id`, `academic_year_id`, `assessment_type_id`, `weight`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- K13 (curriculum_id=2)
(1, 2, 1, 4, 25.00, 1, NOW(), NOW()),  -- PH K13
(1, 2, 1, 5, 30.00, 1, NOW(), NOW()),  -- PTS K13
(1, 2, 1, 6, 45.00, 1, NOW(), NOW()),  -- PAS K13

-- Merdeka (curriculum_id=3)
(1, 3, 1, 8, 40.00, 1, NOW(), NOW()),  -- Formatif
(1, 3, 1, 9, 60.00, 1, NOW(), NOW());  -- Sumatif


-- =====================================================
-- 8. GRADE BOUNDARIES (contoh Cambridge IGCSE)
-- =====================================================
INSERT INTO `grade_boundaries` 
(`school_id`, `curriculum_id`, `assessment_id`, `academic_year_id`, `grade_letter`, `min_score`, `max_score`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 4, NULL, 1, 'A*', 90.00, 100.00, 'Outstanding', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'A', 80.00, 89.99, 'Excellent', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'B', 70.00, 79.99, 'Very Good', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'C', 60.00, 69.99, 'Good', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'D', 50.00, 59.99, 'Satisfactory', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'E', 40.00, 49.99, 'Pass', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'U', 0.00, 39.99, 'Ungraded', 1, NOW(), NOW());
```

---

## Bagian 4: Ringkasan Dukungan Tabel

| Fitur | Tabel Pendukung | KTSP | K13 | Merdeka | Cambridge | Montessori |
|-------|----------------|:----:|:---:|:-------:|:---------:|:----------:|
| Master kurikulum | `curriculums` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Fase/jenjang | `curriculum_phases` | ✅ | ✅ | ✅ (A-F) | ✅ (Stage) | ✅ (Age group) |
| Struktur mapel | `curriculum_structures` | ✅ | ✅ | ✅ | ✅ | ✅ |
| CP/KD | `curriculum_learning_outcomes` | ✅ (SK) | ✅ (KD) | ✅ (CP) | ✅ (Objectives) | ✅ |
| TP/Indikator | `curriculum_objectives` | ✅ | ✅ | ✅ (TP) | ✅ | ✅ |
| Skala nilai | `grade_scales` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Grade boundaries | `grade_boundaries` | — | — | — | ✅ **baru** | ✅ opsional |
| Jenis penilaian | `assessment_types` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Rubrik | `assessment_rubrics` | — | ✅ | ✅ | ✅ | ✅ |
| Nilai siswa | `student_assessments` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Sikap | `attitude_assessments` | ✅ | ✅ | ✅ (P5) | — | ✅ |
| Kehadiran | `student_attendances` | ✅ | ✅ | ✅ | ✅ | ✅ |

# Analisis Dukungan Tabel + Seeder Contoh

## Bagian 1: Apakah Tabel Sudah Mendukung 5 Kurikulum Tersebut?

| Kurikulum | Didukung? | Catatan |
|-----------|-----------|---------|
| **KTSP (2006)** | ✅ Didukung | Pakai `curriculums.type='national'`, phase = tingkat kelas (I-VI, VII-IX, X-XII). Kompetensi: SK/KD. |
| **Kurikulum 2013 (K-13)** | ✅ Didukung | Pakai `curriculums.type='national'`, phase = tingkat kelas. Kompetensi: KI-1 s/d KI-4 + KD. Penilaian: pengetahuan (angka), keterampilan (predikat A-D), sikap (SB/B/C/K). |
| **Kurikulum Merdeka** | ✅ Didukung | Phase = Fase A-F. Kompetensi: CP → TP. Penilaian: formatif/sumatif, P5, deskripsi kualitatif. |
| **Cambridge** | ⚠️ **Perlu penyesuaian** | Butuh **grade scale sendiri (A*-U / 1-9)**, **component-based assessment (Paper 1, 2, 3)**, **weighted total**, **threshold per grade (grade boundaries)**, dan **IGCSE/AS/A-Level** sebagai level, bukan "semester". |
| **Montessori** | ⚠️ **Perlu penyesuaian** | Tidak pakai "kelas/semester" konvensional, tapi **multi-age grouping (Casa, Lower El, Upper El, Erdkinder)**, **individual learning plan**, **mastery-based** (bukan nilai angka), **portofolio & observasi naratif**. |

### Kesimpulan cepat:
- **KTSP, K-13, Kurikulum Merdeka** → tabel Anda **sudah cukup** tanpa perubahan.
- **Cambridge** → butuh tambahan: `grade_boundaries`, `assessment_papers` (komponen berjenjang), dan mapping level Cambridge di `class_levels`/`curriculum_phases`.
- **Montessori** → butuh tambahan: `student_learning_plans`, `mastery_tracking`, `observation_notes`, dan `age_groups`.

---

## Bagian 2: Tambahan Tabel (Opsional, untuk Cambridge & Montessori)

```sql
-- ----------------------------
-- Table structure for grade_boundaries (khusus Cambridge & mastery)
-- ----------------------------
DROP TABLE IF EXISTS `grade_boundaries`;
CREATE TABLE `grade_boundaries`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NOT NULL,
  `assessment_id` bigint UNSIGNED NULL DEFAULT NULL,
  `academic_year_id` bigint UNSIGNED NULL DEFAULT NULL,
  `grade_letter` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_score` decimal(6,2) NOT NULL,
  `max_score` decimal(6,2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `grade_boundaries_school_index`(`school_id` ASC) USING BTREE,
  INDEX `grade_boundaries_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `grade_boundaries_assessment_index`(`assessment_id` ASC) USING BTREE,
  INDEX `grade_boundaries_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `grade_boundaries_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_boundaries_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grade_boundaries_assessment_id_foreign` FOREIGN KEY (`assessment_id`) REFERENCES `assessments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for student_learning_plans (khusus Montessori)
-- ----------------------------
DROP TABLE IF EXISTS `student_learning_plans`;
CREATE TABLE `student_learning_plans`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `curriculum_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `age_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `plan_data` json NULL,
  `start_date` date NULL DEFAULT NULL,
  `end_date` date NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `student_learning_plans_school_index`(`school_id` ASC) USING BTREE,
  INDEX `student_learning_plans_student_index`(`student_id` ASC) USING BTREE,
  INDEX `student_learning_plans_curriculum_index`(`curriculum_id` ASC) USING BTREE,
  INDEX `student_learning_plans_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `student_learning_plans_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_learning_plans_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_learning_plans_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for mastery_tracking (Montessori & mastery-based)
-- ----------------------------
DROP TABLE IF EXISTS `mastery_tracking`;
CREATE TABLE `mastery_tracking`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` bigint UNSIGNED NOT NULL,
  `student_id` bigint UNSIGNED NOT NULL,
  `curriculum_objective_id` bigint UNSIGNED NOT NULL,
  `academic_year_id` bigint UNSIGNED NOT NULL,
  `status` enum('not_started','in_progress','mastered','needs_review') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_started',
  `mastered_at` date NULL DEFAULT NULL,
  `observation_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `mastery_tracking_unique`(`student_id` ASC, `curriculum_objective_id` ASC, `academic_year_id` ASC) USING BTREE,
  INDEX `mastery_tracking_school_index`(`school_id` ASC) USING BTREE,
  INDEX `mastery_tracking_academic_year_index`(`academic_year_id` ASC) USING BTREE,
  INDEX `mastery_tracking_deleted_at_index`(`deleted_at` ASC) USING BTREE,
  CONSTRAINT `mastery_tracking_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mastery_tracking_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mastery_tracking_objective_id_foreign` FOREIGN KEY (`curriculum_objective_id`) REFERENCES `curriculum_objectives` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `mastery_tracking_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;
```

---

## Bagian 3: Seeder Contoh 5 Kurikulum

Seeder ini mengasumsikan `school_id = 1` dan `academic_year_id = 1` sudah ada. Ganti sesuai kebutuhan.

```sql
-- =====================================================
-- 1. MASTER CURRICULUMS
-- =====================================================
INSERT INTO `curriculums` 
(`school_id`, `name`, `code`, `type`, `description`, `start_year`, `end_year`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Kurikulum Tingkat Satuan Pendidikan', 'KTSP', 'national', 'Kurikulum 2006 berbasis SK/KD', 2006, 2013, 1, NOW(), NOW()),
(1, 'Kurikulum 2013', 'K13', 'national', 'Kurikulum 2013 berbasis KI/KD dengan pendekatan saintifik', 2013, 2022, 1, NOW(), NOW()),
(1, 'Kurikulum Merdeka', 'MERDEKA', 'national', 'Kurikulum Merdeka berbasis Capaian Pembelajaran (CP)', 2022, NULL, 1, NOW(), NOW()),
(1, 'Cambridge International', 'CAMBRIDGE', 'international', 'Cambridge Primary / Lower Secondary / IGCSE / AS / A Level', 2020, NULL, 1, NOW(), NOW()),
(1, 'Montessori', 'MONTESSORI', 'international', 'Montessori multi-age, mastery-based, individual learning plan', 2020, NULL, 1, NOW(), NOW());


-- =====================================================
-- 2. CURRICULUM PHASES
-- =====================================================

-- KTSP: fase per jenjang
INSERT INTO `curriculum_phases` 
(`school_id`, `curriculum_id`, `name`, `code`, `description`, `age_range`, `class_levels`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 1, 'Kelas I - VI', 'KTSP-SD', 'Jenjang Sekolah Dasar', '6-12', JSON_ARRAY(1,2,3,4,5,6), 1, 1, NOW(), NOW()),
(1, 1, 'Kelas VII - IX', 'KTSP-SMP', 'Jenjang Sekolah Menengah Pertama', '12-15', JSON_ARRAY(7,8,9), 2, 1, NOW(), NOW()),
(1, 1, 'Kelas X - XII', 'KTSP-SMA', 'Jenjang Sekolah Menengah Atas', '15-18', JSON_ARRAY(10,11,12), 3, 1, NOW(), NOW());

-- K13: fase per jenjang (K13 tidak pakai Fase A-F)
INSERT INTO `curriculum_phases` 
(`school_id`, `curriculum_id`, `name`, `code`, `description`, `age_range`, `class_levels`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 2, 'Kelas I - VI', 'K13-SD', 'Jenjang SD dengan KI/KD per kelas', '6-12', JSON_ARRAY(1,2,3,4,5,6), 1, 1, NOW(), NOW()),
(1, 2, 'Kelas VII - IX', 'K13-SMP', 'Jenjang SMP dengan KI/KD per kelas', '12-15', JSON_ARRAY(7,8,9), 2, 1, NOW(), NOW()),
(1, 2, 'Kelas X - XII', 'K13-SMA', 'Jenjang SMA dengan KI/KD + peminatan', '15-18', JSON_ARRAY(10,11,12), 3, 1, NOW(), NOW());

-- Kurikulum Merdeka: Fase A - F
INSERT INTO `curriculum_phases` 
(`school_id`, `curriculum_id`, `name`, `code`, `description`, `age_range`, `class_levels`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 3, 'Fase A', 'MERDEKA-A', 'Fase Fondasi kelas 1-2 SD', '6-8', JSON_ARRAY(1,2), 1, 1, NOW(), NOW()),
(1, 3, 'Fase B', 'MERDEKA-B', 'Fase kelas 3-4 SD', '8-10', JSON_ARRAY(3,4), 2, 1, NOW(), NOW()),
(1, 3, 'Fase C', 'MERDEKA-C', 'Fase kelas 5-6 SD', '10-12', JSON_ARRAY(5,6), 3, 1, NOW(), NOW()),
(1, 3, 'Fase D', 'MERDEKA-D', 'Fase kelas 7-9 SMP', '12-15', JSON_ARRAY(7,8,9), 4, 1, NOW(), NOW()),
(1, 3, 'Fase E', 'MERDEKA-E', 'Fase kelas 10 SMA/SMK', '15-16', JSON_ARRAY(10), 5, 1, NOW(), NOW()),
(1, 3, 'Fase F', 'MERDEKA-F', 'Fase kelas 11-12 SMA/SMK', '16-18', JSON_ARRAY(11,12), 6, 1, NOW(), NOW());

-- Cambridge: Primary, Lower Secondary, IGCSE, AS/A Level
INSERT INTO `curriculum_phases` 
(`school_id`, `curriculum_id`, `name`, `code`, `description`, `age_range`, `class_levels`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 4, 'Cambridge Primary', 'CAM-PRIMARY', 'Stage 1-6', '5-11', JSON_ARRAY(1,2,3,4,5,6), 1, 1, NOW(), NOW()),
(1, 4, 'Cambridge Lower Secondary', 'CAM-LOWER', 'Stage 7-9', '11-14', JSON_ARRAY(7,8,9), 2, 1, NOW(), NOW()),
(1, 4, 'Cambridge IGCSE', 'CAM-IGCSE', 'Grade 10-11', '14-16', JSON_ARRAY(10,11), 3, 1, NOW(), NOW()),
(1, 4, 'Cambridge AS & A Level', 'CAM-ALEVEL', 'Grade 12', '16-18', JSON_ARRAY(12), 4, 1, NOW(), NOW());

-- Montessori: Casa, Lower El, Upper El, Erdkinder
INSERT INTO `curriculum_phases` 
(`school_id`, `curriculum_id`, `name`, `code`, `description`, `age_range`, `class_levels`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 5, 'Casa dei Bambini', 'MON-CASA', 'Primary 3-6 tahun', '3-6', JSON_ARRAY(1,2), 1, 1, NOW(), NOW()),
(1, 5, 'Lower Elementary', 'MON-LOWER', 'Elementary 6-9 tahun', '6-9', JSON_ARRAY(3,4,5), 2, 1, NOW(), NOW()),
(1, 5, 'Upper Elementary', 'MON-UPPER', 'Elementary 9-12 tahun', '9-12', JSON_ARRAY(6,7,8), 3, 1, NOW(), NOW()),
(1, 5, 'Erdkinder', 'MON-ERDKINDER', 'Adolescent 12-15 tahun', '12-15', JSON_ARRAY(9,10), 4, 1, NOW(), NOW());


-- =====================================================
-- 3. GRADE SCALES (Skala Nilai per Kurikulum)
-- =====================================================

-- KTSP: skala 0-100 + predikat A-D
INSERT INTO `grade_scales` 
(`school_id`, `curriculum_id`, `name`, `code`, `min_score`, `max_score`, `grade_letter`, `grade_point`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 1, 'KTSP A', 'KTSP-A', 90.00, 100.00, 'A', 4.00, 'Sangat Baik', 1, 1, NOW(), NOW()),
(1, 1, 'KTSP B', 'KTSP-B', 80.00, 89.99, 'B', 3.00, 'Baik', 2, 1, NOW(), NOW()),
(1, 1, 'KTSP C', 'KTSP-C', 70.00, 79.99, 'C', 2.00, 'Cukup', 3, 1, NOW(), NOW()),
(1, 1, 'KTSP D', 'KTSP-D', 0.00, 69.99, 'D', 1.00, 'Kurang', 4, 1, NOW(), NOW());

-- K13: pengetahuan angka + predikat, sikap SB/B/C/K
INSERT INTO `grade_scales` 
(`school_id`, `curriculum_id`, `name`, `code`, `min_score`, `max_score`, `grade_letter`, `grade_point`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 2, 'K13 A', 'K13-A', 90.00, 100.00, 'A', 4.00, 'Sangat Baik', 1, 1, NOW(), NOW()),
(1, 2, 'K13 B', 'K13-B', 80.00, 89.99, 'B', 3.00, 'Baik', 2, 1, NOW(), NOW()),
(1, 2, 'K13 C', 'K13-C', 70.00, 79.99, 'C', 2.00, 'Cukup', 3, 1, NOW(), NOW()),
(1, 2, 'K13 D', 'K13-D', 0.00, 69.99, 'D', 1.00, 'Perlu Bimbingan', 4, 1, NOW(), NOW()),
(1, 2, 'K13 SB', 'K13-SB', 90.00, 100.00, 'SB', 4.00, 'Sangat Baik (Sikap)', 5, 1, NOW(), NOW()),
(1, 2, 'K13 B (Sikap)', 'K13-BS', 80.00, 89.99, 'B', 3.00, 'Baik (Sikap)', 6, 1, NOW(), NOW()),
(1, 2, 'K13 C (Sikap)', 'K13-CS', 70.00, 79.99, 'C', 2.00, 'Cukup (Sikap)', 7, 1, NOW(), NOW()),
(1, 2, 'K13 K', 'K13-K', 0.00, 69.99, 'K', 1.00, 'Kurang (Sikap)', 8, 1, NOW(), NOW());

-- Kurikulum Merdeka: 4 kategori (Belum Berkembang - Sangat Berkembang)
INSERT INTO `grade_scales` 
(`school_id`, `curriculum_id`, `name`, `code`, `min_score`, `max_score`, `grade_letter`, `grade_point`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 3, 'Merdeka BB', 'MERDEKA-BB', 0.00, 59.99, 'BB', 1.00, 'Belum Berkembang', 1, 1, NOW(), NOW()),
(1, 3, 'Merdeka MB', 'MERDEKA-MB', 60.00, 74.99, 'MB', 2.00, 'Mulai Berkembang', 2, 1, NOW(), NOW()),
(1, 3, 'Merdeka BSH', 'MERDEKA-BSH', 75.00, 89.99, 'BSH', 3.00, 'Berkembang Sesuai Harapan', 3, 1, NOW(), NOW()),
(1, 3, 'Merdeka SB', 'MERDEKA-SB', 90.00, 100.00, 'SB', 4.00, 'Sangat Berkembang', 4, 1, NOW(), NOW());

-- Cambridge: A* - U (IGCSE) + 1-9 (Lower Secondary)
INSERT INTO `grade_scales` 
(`school_id`, `curriculum_id`, `name`, `code`, `min_score`, `max_score`, `grade_letter`, `grade_point`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 4, 'Cambridge A*', 'CAM-ASTAR', 90.00, 100.00, 'A*', 4.00, 'Outstanding', 1, 1, NOW(), NOW()),
(1, 4, 'Cambridge A', 'CAM-A', 80.00, 89.99, 'A', 4.00, 'Excellent', 2, 1, NOW(), NOW()),
(1, 4, 'Cambridge B', 'CAM-B', 70.00, 79.99, 'B', 3.00, 'Very Good', 3, 1, NOW(), NOW()),
(1, 4, 'Cambridge C', 'CAM-C', 60.00, 69.99, 'C', 2.00, 'Good', 4, 1, NOW(), NOW()),
(1, 4, 'Cambridge D', 'CAM-D', 50.00, 59.99, 'D', 1.00, 'Satisfactory', 5, 1, NOW(), NOW()),
(1, 4, 'Cambridge E', 'CAM-E', 40.00, 49.99, 'E', 1.00, 'Pass', 6, 1, NOW(), NOW()),
(1, 4, 'Cambridge U', 'CAM-U', 0.00, 39.99, 'U', 0.00, 'Ungraded', 7, 1, NOW(), NOW());

-- Montessori: mastery-based (bukan angka)
INSERT INTO `grade_scales` 
(`school_id`, `curriculum_id`, `name`, `code`, `min_score`, `max_score`, `grade_letter`, `grade_point`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 5, 'Montessori Not Yet', 'MON-NY', 0.00, 49.99, 'NY', 1.00, 'Not Yet - belum dikuasai', 1, 1, NOW(), NOW()),
(1, 5, 'Montessori Developing', 'MON-DEV', 50.00, 74.99, 'DEV', 2.00, 'Developing - sedang berkembang', 2, 1, NOW(), NOW()),
(1, 5, 'Montessori Mastered', 'MON-M', 75.00, 100.00, 'M', 4.00, 'Mastered - sudah dikuasai', 3, 1, NOW(), NOW());


-- =====================================================
-- 4. ASSESSMENT TYPES (per kurikulum, opsional school-wide)
-- =====================================================

-- KTSP
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Ulangan Harian KTSP', 'UH-KTSP', 'numeric', 20.00, 'Ulangan harian KTSP', 1, NOW(), NOW()),
(1, 'Ulangan Tengah Semester KTSP', 'UTS-KTSP', 'numeric', 30.00, 'UTS KTSP', 1, NOW(), NOW()),
(1, 'Ulangan Akhir Semester KTSP', 'UAS-KTSP', 'numeric', 50.00, 'UAS KTSP', 1, NOW(), NOW());

-- K13
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Penilaian Harian K13', 'PH-K13', 'numeric', 25.00, 'PH K13', 1, NOW(), NOW()),
(1, 'PTS K13', 'PTS-K13', 'numeric', 30.00, 'Penilaian Tengah Semester', 1, NOW(), NOW()),
(1, 'PAS K13', 'PAS-K13', 'numeric', 45.00, 'Penilaian Akhir Semester', 1, NOW(), NOW()),
(1, 'Penilaian Sikap K13', 'SIKAP-K13', 'descriptive', 0.00, 'Observasi sikap spiritual & sosial', 1, NOW(), NOW()),
(1, 'Penilaian Keterampilan K13', 'KET-K13', 'rubric', 0.00, 'Praktik, proyek, portofolio', 1, NOW(), NOW());

-- Kurikulum Merdeka
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Asesmen Formatif', 'FORMATIF-MERDEKA', 'rubric', 40.00, 'Asesmen formatif (proses)', 1, NOW(), NOW()),
(1, 'Asesmen Sumatif', 'SUMATIF-MERDEKA', 'numeric', 60.00, 'Asesmen sumatif (akhir lingkup materi)', 1, NOW(), NOW()),
(1, 'Proyek P5', 'P5-MERDEKA', 'rubric', 0.00, 'Projek Penguatan Profil Pelajar Pancasila', 1, NOW(), NOW());

-- Cambridge
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Cambridge Paper 1', 'CAM-P1', 'numeric', 30.00, 'Component Paper 1', 1, NOW(), NOW()),
(1, 'Cambridge Paper 2', 'CAM-P2', 'numeric', 30.00, 'Component Paper 2', 1, NOW(), NOW()),
(1, 'Cambridge Paper 3', 'CAM-P3', 'numeric', 40.00, 'Component Paper 3', 1, NOW(), NOW()),
(1, 'Cambridge Coursework', 'CAM-CW', 'rubric', 0.00, 'Coursework / Practical', 1, NOW(), NOW());

-- Montessori
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'Observasi Montessori', 'MON-OBS', 'descriptive', 0.00, 'Observasi harian guru', 1, NOW(), NOW()),
(1, 'Mastery Check', 'MON-MASTERY', 'rubric', 0.00, 'Cek penguasaan materi', 1, NOW(), NOW()),
(1, 'Portofolio Montessori', 'MON-PORT', 'descriptive', 0.00, 'Portofolio karya siswa', 1, NOW(), NOW());


-- =====================================================
-- 5. REPORT CARD TEMPLATES (1 template per kurikulum)
-- =====================================================
INSERT INTO `report_card_templates` 
(`school_id`, `name`, `code`, `template_data`, `is_default`, `is_active`, `version`, `created_at`, `updated_at`) 
VALUES
(1, 'Raport KTSP', 'TPL-KTSP', 
 JSON_OBJECT('layout','ktsp','sections', JSON_ARRAY('identitas','nilai','sikap','kehadiran','catatan')),
 0, 1, 1, NOW(), NOW()),

(1, 'Raport K13', 'TPL-K13', 
 JSON_OBJECT('layout','k13','sections', JSON_ARRAY('identitas','nilai_pengetahuan','nilai_keterampilan','sikap_spiritual','sikap_sosial','kehadiran','ekstrakurikuler','catatan')),
 0, 1, 1, NOW(), NOW()),

(1, 'Raport Kurikulum Merdeka', 'TPL-MERDEKA', 
 JSON_OBJECT('layout','merdeka','sections', JSON_ARRAY('identitas','nilai_intrakurikuler','p5','kehadiran','ekstrakurikuler','catatan')),
 0, 1, 1, NOW(), NOW()),

(1, 'Report Card Cambridge', 'TPL-CAMBRIDGE', 
 JSON_OBJECT('layout','cambridge','sections', JSON_ARRAY('identitas','subject_grades','grade_boundaries','attendance','tutor_comment')),
 0, 1, 1, NOW(), NOW()),

(1, 'Montessori Progress Report', 'TPL-MONTESSORI', 
 JSON_OBJECT('layout','montessori','sections', JSON_ARRAY('identitas','learning_plan','mastery','observation','portfolio')),
 0, 1, 1, NOW(), NOW());


-- =====================================================
-- 6. MAPPING TEMPLATE ↔ CURRICULUM
-- =====================================================
INSERT INTO `report_card_template_curriculums` 
(`report_card_template_id`, `curriculum_id`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 1, 1, NOW(), NOW()),  -- KTSP
(2, 2, 1, NOW(), NOW()),  -- K13
(3, 3, 1, NOW(), NOW()),  -- Merdeka
(4, 4, 1, NOW(), NOW()),  -- Cambridge
(5, 5, 1, NOW(), NOW());  -- Montessori


-- =====================================================
-- 7. GRADE WEIGHT CONFIGS (contoh untuk K13 & Merdeka)
-- =====================================================
INSERT INTO `grade_weight_configs` 
(`school_id`, `curriculum_id`, `academic_year_id`, `assessment_type_id`, `weight`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- K13 (curriculum_id=2)
(1, 2, 1, 4, 25.00, 1, NOW(), NOW()),  -- PH K13
(1, 2, 1, 5, 30.00, 1, NOW(), NOW()),  -- PTS K13
(1, 2, 1, 6, 45.00, 1, NOW(), NOW()),  -- PAS K13

-- Merdeka (curriculum_id=3)
(1, 3, 1, 8, 40.00, 1, NOW(), NOW()),  -- Formatif
(1, 3, 1, 9, 60.00, 1, NOW(), NOW());  -- Sumatif


-- =====================================================
-- 8. GRADE BOUNDARIES (contoh Cambridge IGCSE)
-- =====================================================
INSERT INTO `grade_boundaries` 
(`school_id`, `curriculum_id`, `assessment_id`, `academic_year_id`, `grade_letter`, `min_score`, `max_score`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 4, NULL, 1, 'A*', 90.00, 100.00, 'Outstanding', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'A', 80.00, 89.99, 'Excellent', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'B', 70.00, 79.99, 'Very Good', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'C', 60.00, 69.99, 'Good', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'D', 50.00, 59.99, 'Satisfactory', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'E', 40.00, 49.99, 'Pass', 1, NOW(), NOW()),
(1, 4, NULL, 1, 'U', 0.00, 39.99, 'Ungraded', 1, NOW(), NOW());
```

---

## Bagian 4: Ringkasan Dukungan Tabel

| Fitur | Tabel Pendukung | KTSP | K13 | Merdeka | Cambridge | Montessori |
|-------|----------------|:----:|:---:|:-------:|:---------:|:----------:|
| Master kurikulum | `curriculums` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Fase/jenjang | `curriculum_phases` | ✅ | ✅ | ✅ (A-F) | ✅ (Stage) | ✅ (Age group) |
| Struktur mapel | `curriculum_structures` | ✅ | ✅ | ✅ | ✅ | ✅ |
| CP/KD | `curriculum_learning_outcomes` | ✅ (SK) | ✅ (KD) | ✅ (CP) | ✅ (Objectives) | ✅ |
| TP/Indikator | `curriculum_objectives` | ✅ | ✅ | ✅ (TP) | ✅ | ✅ |
| Skala nilai | `grade_scales` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Grade boundaries | `grade_boundaries` | — | — | — | ✅ **baru** | ✅ opsional |
| Jenis penilaian | `assessment_types` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Rubrik | `assessment_rubrics` | — | ✅ | ✅ | ✅ | ✅ |
| Nilai siswa | `student_assessments` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Sikap | `attitude_assessments` | ✅ | ✅ | ✅ (P5) | — | ✅ |
| Kehadiran | `student_attendances` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Ekskul | `extracurricular_activities` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Rapor template | `report_card_templates` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Rapor detail | `report_card_grades` | ✅ | ✅ | ✅ | ✅ | ✅ |
| Learning plan | `student_learning_plans` | — | — | — | — | ✅ **baru** |
| Mastery tracking | `mastery_tracking` | — | — | — | — | ✅ **baru** |

---

## Bagian 5: Catatan Implementasi

1. **Cambridge**: kolom `semester` di `assessments` bisa diisi `1`/`2`, tapi kalau Cambridge pakai "term" 3x setahun, tambahkan nilai `3` di enum atau ubah jadi `varchar`. Alternatif: pakai `report_periods` dengan `type='other'`.
2. **Montessori**: `class_level_id` tetap dipakai untuk mapping usia, tapi `age_group` di `student_learning_plans` menandai grouping Montessori sesungguhnya.
3. **K-13 sikap**: gunakan `attitude_assessments` (bukan `grade_scales`) untuk menyimpan predikat SB/B/C/K per siswa.
4. **Kurikulum Merdeka P5**: simpan sebagai `assessments` dengan `assessment_type` P5, dan deskripsi dimensi di `student_assessments.rubric_data`.
5. **Multi-kurikulum satu siswa**: kalau siswa pindah dari K13 ke Merdeka, cukup tambahkan baris baru di `student_curriculums` untuk tahun ajaran berikutnya — histori tetap tersimpan.

---

Kalau Anda mau, saya bisa lanjutkan dengan:
- **Seeder mata pelajaran** (`curriculum_subjects` + `curriculum_structures`) untuk masing-masing kurikulum (Matematika, IPA, Bahasa, dll).
- **Query rekap nilai** untuk generate raport per kurikulum.
- **Factory & Seeder Laravel** versi PHP.

# Seeder Lanjutan: CP/KD, Assessment, Grade Weight & Template Raport

Berikut seeder lengkap untuk 4 komponen. Asumsi:
- `school_id = 1`, `academic_year_id = 1`
- `curriculum_learning_outcomes.id` berurutan (mulai dari 1)
- Mapel: `subject_id` mengikuti seeder sebelumnya (3=Bind, 4=MTK, 5=IPA, 23=Cambridge Math, 24=Cambridge English, 38=Montessori Math)
- `report_card_templates.id`: 1=KTSP, 2=K13, 3=Merdeka, 4=Cambridge, 5=Montessori
- `assessment_types.id` berurutan (mulai dari 1)

---

## 1. Seeder `curriculum_learning_outcomes`

### 1.1 KTSP — Standar Kompetensi (SK)

```sql
-- KTSP: Standar Kompetensi (SK) — contoh Matematika & Bahasa Indonesia
INSERT INTO `curriculum_learning_outcomes` 
(`school_id`, `curriculum_phase_id`, `subject_id`, `code`, `element`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Matematika SD kelas 1-6 (phase 1)
(1, 1, 4, 'KTSP-MTK-SD-1', 'pengetahuan', 'Melakukan operasi hitung bilangan sampai tiga angka', 1, 1, NOW(), NOW()),
(1, 1, 4, 'KTSP-MTK-SD-2', 'pengetahuan', 'Menggunakan pengukuran waktu, panjang, dan berat', 2, 1, NOW(), NOW()),
(1, 1, 4, 'KTSP-MTK-SD-3', 'pengetahuan', 'Mengenal bangun datar dan bangun ruang sederhana', 3, 1, NOW(), NOW()),
(1, 1, 4, 'KTSP-MTK-SD-4', 'keterampilan', 'Memecahkan masalah yang berkaitan dengan pecahan sederhana', 4, 1, NOW(), NOW()),

-- Matematika SMP kelas 7-9 (phase 2)
(1, 2, 4, 'KTSP-MTK-SMP-1', 'pengetahuan', 'Melakukan operasi aljabar', 1, 1, NOW(), NOW()),
(1, 2, 4, 'KTSP-MTK-SMP-2', 'pengetahuan', 'Memahami konsep himpunan dan operasinya', 2, 1, NOW(), NOW()),
(1, 2, 4, 'KTSP-MTK-SMP-3', 'pengetahuan', 'Memahami konsep fungsi dan grafiknya', 3, 1, NOW(), NOW()),
(1, 2, 4, 'KTSP-MTK-SMP-4', 'keterampilan', 'Menyelesaikan masalah geometri dan pengukuran', 4, 1, NOW(), NOW()),

-- Matematika SMA kelas 10-12 (phase 3)
(1, 3, 4, 'KTSP-MTK-SMA-1', 'pengetahuan', 'Memahami konsep logika matematika', 1, 1, NOW(), NOW()),
(1, 3, 4, 'KTSP-MTK-SMA-2', 'pengetahuan', 'Memahami konsep trigonometri', 2, 1, NOW(), NOW()),
(1, 3, 4, 'KTSP-MTK-SMA-3', 'pengetahuan', 'Memahami konsep limit dan turunan', 3, 1, NOW(), NOW()),
(1, 3, 4, 'KTSP-MTK-SMA-4', 'keterampilan', 'Menerapkan statistika dan peluang', 4, 1, NOW(), NOW()),

-- Bahasa Indonesia SD (phase 1)
(1, 1, 3, 'KTSP-BIND-SD-1', 'pengetahuan', 'Memahami bunyi bahasa dan lambang huruf', 1, 1, NOW(), NOW()),
(1, 1, 3, 'KTSP-BIND-SD-2', 'keterampilan', 'Mengungkapkan pikiran secara lisan dan tertulis', 2, 1, NOW(), NOW()),
(1, 1, 3, 'KTSP-BIND-SD-3', 'keterampilan', 'Membaca dan memahami teks pendek', 3, 1, NOW(), NOW()),

-- IPA SD (phase 1)
(1, 1, 5, 'KTSP-IPA-SD-1', 'pengetahuan', 'Mengenal bagian tumbuhan dan fungsinya', 1, 1, NOW(), NOW()),
(1, 1, 5, 'KTSP-IPA-SD-2', 'pengetahuan', 'Mengenal wujud benda dan perubahannya', 2, 1, NOW(), NOW()),
(1, 1, 5, 'KTSP-IPA-SD-3', 'keterampilan', 'Melakukan pengamatan sederhana', 3, 1, NOW(), NOW());
```

### 1.2 K13 — Kompetensi Inti & Kompetensi Dasar (KI/KD)

```sql
-- K13: KD per mapel (element mengikuti KI-1 s/d KI-4)
INSERT INTO `curriculum_learning_outcomes` 
(`school_id`, `curriculum_phase_id`, `subject_id`, `code`, `element`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Matematika SMP kelas 7 (phase 5)
(1, 5, 4, 'K13-MTK-SMP-3.1', 'pengetahuan', 'Menjelaskan dan menentukan urutan bilangan bulat dan pecahan', 1, 1, NOW(), NOW()),
(1, 5, 4, 'K13-MTK-SMP-3.2', 'pengetahuan', 'Menjelaskan himpunan dan operasinya', 2, 1, NOW(), NOW()),
(1, 5, 4, 'K13-MTK-SMP-3.3', 'pengetahuan', 'Menjelaskan bentuk aljabar dan operasinya', 3, 1, NOW(), NOW()),
(1, 5, 4, 'K13-MTK-SMP-4.1', 'keterampilan', 'Menyelesaikan masalah bilangan bulat dan pecahan', 4, 1, NOW(), NOW()),
(1, 5, 4, 'K13-MTK-SMP-4.2', 'keterampilan', 'Menyelesaikan masalah himpunan', 5, 1, NOW(), NOW()),

-- Matematika SMA kelas 10 (phase 6)
(1, 6, 4, 'K13-MTK-SMA-3.1', 'pengetahuan', 'Menjelaskan persamaan dan pertidaksamaan nilai mutlak', 1, 1, NOW(), NOW()),
(1, 6, 4, 'K13-MTK-SMA-3.2', 'pengetahuan', 'Menjelaskan sistem persamaan linear tiga variabel', 2, 1, NOW(), NOW()),
(1, 6, 4, 'K13-MTK-SMA-3.3', 'pengetahuan', 'Menjelaskan fungsi eksponen dan logaritma', 3, 1, NOW(), NOW()),
(1, 6, 4, 'K13-MTK-SMA-4.1', 'keterampilan', 'Menyelesaikan masalah nilai mutlak', 4, 1, NOW(), NOW()),
(1, 6, 4, 'K13-MTK-SMA-4.2', 'keterampilan', 'Menyelesaikan masalah SPLTV', 5, 1, NOW(), NOW()),

-- Bahasa Indonesia SMP (phase 5)
(1, 5, 3, 'K13-BIND-SMP-3.1', 'pengetahuan', 'Mengidentifikasi teks deskripsi', 1, 1, NOW(), NOW()),
(1, 5, 3, 'K13-BIND-SMP-3.2', 'pengetahuan', 'Mengidentifikasi teks narasi', 2, 1, NOW(), NOW()),
(1, 5, 3, 'K13-BIND-SMP-4.1', 'keterampilan', 'Menyusun teks deskripsi', 3, 1, NOW(), NOW()),
(1, 5, 3, 'K13-BIND-SMP-4.2', 'keterampilan', 'Menyusun teks narasi', 4, 1, NOW(), NOW()),

-- IPA SMP (phase 5)
(1, 5, 5, 'K13-IPA-SMP-3.1', 'pengetahuan', 'Menjelaskan klasifikasi makhluk hidup', 1, 1, NOW(), NOW()),
(1, 5, 5, 'K13-IPA-SMP-3.2', 'pengetahuan', 'Menjelaskan suhu dan kalor', 2, 1, NOW(), NOW()),
(1, 5, 5, 'K13-IPA-SMP-4.1', 'keterampilan', 'Melakukan pengamatan klasifikasi makhluk hidup', 3, 1, NOW(), NOW()),

-- Sikap Spiritual & Sosial K13 (contoh untuk semua mapel)
(1, 5, 4, 'K13-SIKAP-SPIRITUAL-1', 'spiritual', 'Menghayati ajaran agama yang dianutnya', 1, 1, NOW(), NOW()),
(1, 5, 4, 'K13-SIKAP-SOSIAL-1', 'sosial', 'Menunjukkan perilaku jujur, disiplin, tanggung jawab', 2, 1, NOW(), NOW());
```

### 1.3 Kurikulum Merdeka — Capaian Pembelajaran (CP)

```sql
-- Kurikulum Merdeka: CP per Fase per Mapel
INSERT INTO `curriculum_learning_outcomes` 
(`school_id`, `curriculum_phase_id`, `subject_id`, `code`, `element`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Matematika Fase A (kelas 1-2, phase 7)
(1, 7, 4, 'MERDEKA-MTK-A-1', 'pengetahuan', 'Memahami bilangan cacah sampai 100 dan operasinya', 1, 1, NOW(), NOW()),
(1, 7, 4, 'MERDEKA-MTK-A-2', 'pengetahuan', 'Mengenal bangun datar dan bangun ruang sederhana', 2, 1, NOW(), NOW()),
(1, 7, 4, 'MERDEKA-MTK-A-3', 'keterampilan', 'Menyelesaikan masalah sehari-hari dengan bilangan cacah', 3, 1, NOW(), NOW()),

-- Matematika Fase D SMP (kelas 7-9, phase 10)
(1, 10, 4, 'MERDEKA-MTK-D-1', 'pengetahuan', 'Memahami bilangan rasional dan irasional', 1, 1, NOW(), NOW()),
(1, 10, 4, 'MERDEKA-MTK-D-2', 'pengetahuan', 'Memahami aljabar dan persamaan linear', 2, 1, NOW(), NOW()),
(1, 10, 4, 'MERDEKA-MTK-D-3', 'pengetahuan', 'Memahami geometri dan pengukuran', 3, 1, NOW(), NOW()),
(1, 10, 4, 'MERDEKA-MTK-D-4', 'pengetahuan', 'Memahami statistika dan peluang', 4, 1, NOW(), NOW()),
(1, 10, 4, 'MERDEKA-MTK-D-5', 'keterampilan', 'Memodelkan masalah nyata dengan matematika', 5, 1, NOW(), NOW()),
(1, 10, 4, 'MERDEKA-MTK-D-6', 'keterampilan', 'Menggunakan penalaran matematis', 6, 1, NOW(), NOW()),

-- Matematika Fase E SMA kelas 10 (phase 11)
(1, 11, 4, 'MERDEKA-MTK-E-1', 'pengetahuan', 'Memahami eksponen dan logaritma', 1, 1, NOW(), NOW()),
(1, 11, 4, 'MERDEKA-MTK-E-2', 'pengetahuan', 'Memahami barisan dan deret', 2, 1, NOW(), NOW()),
(1, 11, 4, 'MERDEKA-MTK-E-3', 'pengetahuan', 'Memahami sistem persamaan linear', 3, 1, NOW(), NOW()),
(1, 11, 4, 'MERDEKA-MTK-E-4', 'keterampilan', 'Menyelesaikan masalah kontekstual dengan fungsi', 4, 1, NOW(), NOW()),

-- Bahasa Indonesia Fase D (phase 10)
(1, 10, 3, 'MERDEKA-BIND-D-1', 'pengetahuan', 'Memahami teks deskripsi, narasi, eksposisi', 1, 1, NOW(), NOW()),
(1, 10, 3, 'MERDEKA-BIND-D-2', 'keterampilan', 'Menulis teks sesuai konteks', 2, 1, NOW(), NOW()),
(1, 10, 3, 'MERDEKA-BIND-D-3', 'keterampilan', 'Menyampaikan gagasan secara lisan', 3, 1, NOW(), NOW()),

-- IPA Fase D (phase 10)
(1, 10, 5, 'MERDEKA-IPA-D-1', 'pengetahuan', 'Memahami klasifikasi makhluk hidup', 1, 1, NOW(), NOW()),
(1, 10, 5, 'MERDEKA-IPA-D-2', 'pengetahuan', 'Memahami zat dan perubahannya', 2, 1, NOW(), NOW()),
(1, 10, 5, 'MERDEKA-IPA-D-3', 'keterampilan', 'Melakukan penyelidikan ilmiah', 3, 1, NOW(), NOW()),

-- Dimensi P5 (Profil Pelajar Pancasila)
(1, 10, 10, 'MERDEKA-P5-1', 'sikap', 'Beriman, bertakwa kepada Tuhan YME, dan berakhlak mulia', 1, 1, NOW(), NOW()),
(1, 10, 10, 'MERDEKA-P5-2', 'sikap', 'Berkebinekaan global', 2, 1, NOW(), NOW()),
(1, 10, 10, 'MERDEKA-P5-3', 'sikap', 'Bergotong royong', 3, 1, NOW(), NOW()),
(1, 10, 10, 'MERDEKA-P5-4', 'sikap', 'Mandiri', 4, 1, NOW(), NOW()),
(1, 10, 10, 'MERDEKA-P5-5', 'sikap', 'Bernalar kritis', 5, 1, NOW(), NOW()),
(1, 10, 10, 'MERDEKA-P5-6', 'sikap', 'Kreatif', 6, 1, NOW(), NOW());
```

### 1.4 Cambridge — Learning Objectives

```sql
-- Cambridge: Learning Objectives per stage
INSERT INTO `curriculum_learning_outcomes` 
(`school_id`, `curriculum_phase_id`, `subject_id`, `code`, `element`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Cambridge Primary Mathematics (phase 13)
(1, 13, 23, 'CAM-PRI-MATH-1', 'pengetahuan', 'Number: counting, place value, and operations', 1, 1, NOW(), NOW()),
(1, 13, 23, 'CAM-PRI-MATH-2', 'pengetahuan', 'Geometry: shapes and position', 2, 1, NOW(), NOW()),
(1, 13, 23, 'CAM-PRI-MATH-3', 'pengetahuan', 'Measure: length, mass, time, capacity', 3, 1, NOW(), NOW()),
(1, 13, 23, 'CAM-PRI-MATH-4', 'pengetahuan', 'Statistics: data handling', 4, 1, NOW(), NOW()),
(1, 13, 23, 'CAM-PRI-MATH-5', 'keterampilan', 'Problem solving and reasoning', 5, 1, NOW(), NOW()),

-- Cambridge Primary English (phase 13)
(1, 13, 24, 'CAM-PRI-ENG-1', 'pengetahuan', 'Reading: phonics, word recognition', 1, 1, NOW(), NOW()),
(1, 13, 24, 'CAM-PRI-ENG-2', 'pengetahuan', 'Writing: spelling and grammar', 2, 1, NOW(), NOW()),
(1, 13, 24, 'CAM-PRI-ENG-3', 'keterampilan', 'Speaking and listening', 3, 1, NOW(), NOW()),

-- Cambridge IGCSE Mathematics (phase 15)
(1, 15, 23, 'CAM-IGCSE-MATH-1', 'pengetahuan', 'Number: integers, fractions, decimals, percentages', 1, 1, NOW(), NOW()),
(1, 15, 23, 'CAM-IGCSE-MATH-2', 'pengetahuan', 'Algebra: equations, sequences, functions', 2, 1, NOW(), NOW()),
(1, 15, 23, 'CAM-IGCSE-MATH-3', 'pengetahuan', 'Geometry and Trigonometry', 3, 1, NOW(), NOW()),
(1, 15, 23, 'CAM-IGCSE-MATH-4', 'pengetahuan', 'Statistics and Probability', 4, 1, NOW(), NOW()),
(1, 15, 23, 'CAM-IGCSE-MATH-5', 'keterampilan', 'Applying mathematics to real-life problems', 5, 1, NOW(), NOW()),

-- Cambridge IGCSE Physics (phase 15)
(1, 15, 31, 'CAM-IGCSE-PHY-1', 'pengetahuan', 'Motion, forces, and energy', 1, 1, NOW(), NOW()),
(1, 15, 31, 'CAM-IGCSE-PHY-2', 'pengetahuan', 'Waves and electricity', 2, 1, NOW(), NOW()),
(1, 15, 31, 'CAM-IGCSE-PHY-3', 'keterampilan', 'Experimental skills and investigations', 3, 1, NOW(), NOW()),

-- Cambridge AS/A Level Mathematics (phase 16)
(1, 16, 23, 'CAM-AL-MATH-1', 'pengetahuan', 'Pure Mathematics: calculus, algebra', 1, 1, NOW(), NOW()),
(1, 16, 23, 'CAM-AL-MATH-2', 'pengetahuan', 'Mechanics', 2, 1, NOW(), NOW()),
(1, 16, 23, 'CAM-AL-MATH-3', 'pengetahuan', 'Probability and Statistics', 3, 1, NOW(), NOW());
```

### 1.5 Montessori — Learning Outcomes (Mastery-based)

```sql
-- Montessori: Learning Outcomes per area
INSERT INTO `curriculum_learning_outcomes` 
(`school_id`, `curriculum_phase_id`, `subject_id`, `code`, `element`, `description`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Casa Practical Life (phase 17)
(1, 17, 35, 'MON-CASA-PL-1', 'keterampilan', 'Care of self: dressing, washing hands', 1, 1, NOW(), NOW()),
(1, 17, 35, 'MON-CASA-PL-2', 'keterampilan', 'Care of environment: cleaning, gardening', 2, 1, NOW(), NOW()),
(1, 17, 35, 'MON-CASA-PL-3', 'keterampilan', 'Grace and courtesy: social skills', 3, 1, NOW(), NOW()),

-- Casa Sensorial (phase 17)
(1, 17, 36, 'MON-CASA-SEN-1', 'pengetahuan', 'Visual discrimination: size, shape, color', 1, 1, NOW(), NOW()),
(1, 17, 36, 'MON-CASA-SEN-2', 'pengetahuan', 'Auditory discrimination', 2, 1, NOW(), NOW()),
(1, 17, 36, 'MON-CASA-SEN-3', 'pengetahuan', 'Tactile and stereognostic sense', 3, 1, NOW(), NOW()),

-- Lower Elementary Language (phase 18)
(1, 18, 37, 'MON-LOWER-LANG-1', 'pengetahuan', 'Reading fluency and comprehension', 1, 1, NOW(), NOW()),
(1, 18, 37, 'MON-LOWER-LANG-2', 'keterampilan', 'Creative writing and grammar', 2, 1, NOW(), NOW()),
(1, 18, 37, 'MON-LOWER-LANG-3', 'keterampilan', 'Oral presentation', 3, 1, NOW(), NOW()),

-- Lower Elementary Mathematics (phase 18)
(1, 18, 38, 'MON-LOWER-MATH-1', 'pengetahuan', 'Decimal system and operations', 1, 1, NOW(), NOW()),
(1, 18, 38, 'MON-LOWER-MATH-2', 'pengetahuan', 'Fractions and equivalence', 2, 1, NOW(), NOW()),
(1, 18, 38, 'MON-LOWER-MATH-3', 'keterampilan', 'Problem solving with manipulatives', 3, 1, NOW(), NOW()),

-- Upper Elementary Cultural Studies (phase 19)
(1, 19, 39, 'MON-UPPER-CULT-1', 'pengetahuan', 'Geography: continents, landforms', 1, 1, NOW(), NOW()),
(1, 19, 39, 'MON-UPPER-CULT-2', 'pengetahuan', 'History: timeline of life and civilization', 2, 1, NOW(), NOW()),
(1, 19, 39, 'MON-UPPER-CULT-3', 'pengetahuan', 'Botany and Zoology classification', 3, 1, NOW(), NOW()),
(1, 19, 39, 'MON-UPPER-CULT-4', 'keterampilan', 'Research and presentation skills', 4, 1, NOW(), NOW());
```

---

## 2. Seeder `assessment_types` (lengkap per kurikulum)

```sql
-- Assessment Types lengkap (id berurutan mulai dari 1)
INSERT INTO `assessment_types` 
(`school_id`, `name`, `code`, `method`, `weight_default`, `description`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- ============ KTSP (id 1-3) ============
(1, 'Ulangan Harian', 'UH-KTSP', 'numeric', 20.00, 'Ulangan harian KTSP', 1, NOW(), NOW()),
(1, 'Ulangan Tengah Semester', 'UTS-KTSP', 'numeric', 30.00, 'UTS KTSP', 1, NOW(), NOW()),
(1, 'Ulangan Akhir Semester', 'UAS-KTSP', 'numeric', 50.00, 'UAS KTSP', 1, NOW(), NOW()),

-- ============ K13 (id 4-8) ============
(1, 'Penilaian Harian', 'PH-K13', 'numeric', 25.00, 'Penilaian harian K13', 1, NOW(), NOW()),
(1, 'Penilaian Tengah Semester', 'PTS-K13', 'numeric', 30.00, 'PTS K13', 1, NOW(), NOW()),
(1, 'Penilaian Akhir Semester', 'PAS-K13', 'numeric', 45.00, 'PAS K13', 1, NOW(), NOW()),
(1, 'Penilaian Sikap Spiritual', 'SIKAP-SPIRITUAL-K13', 'descriptive', 0.00, 'Observasi sikap spiritual', 1, NOW(), NOW()),
(1, 'Penilaian Sikap Sosial', 'SIKAP-SOSIAL-K13', 'descriptive', 0.00, 'Observasi sikap sosial', 1, NOW(), NOW()),

-- ============ Kurikulum Merdeka (id 9-12) ============
(1, 'Asesmen Formatif', 'FORMATIF-MERDEKA', 'rubric', 40.00, 'Asesmen formatif (proses)', 1, NOW(), NOW()),
(1, 'Asesmen Sumatif', 'SUMATIF-MERDEKA', 'numeric', 60.00, 'Asesmen sumatif (akhir lingkup materi)', 1, NOW(), NOW()),
(1, 'Proyek P5', 'P5-MERDEKA', 'rubric', 0.00, 'Projek Penguatan Profil Pelajar Pancasila', 1, NOW(), NOW()),
(1, 'Asesmen Diagnostik', 'DIAGNOSTIK-MERDEKA', 'descriptive', 0.00, 'Asesmen diagnostik awal', 1, NOW(), NOW()),

-- ============ Cambridge (id 13-17) ============
(1, 'Cambridge Paper 1', 'CAM-P1', 'numeric', 30.00, 'Component Paper 1', 1, NOW(), NOW()),
(1, 'Cambridge Paper 2', 'CAM-P2', 'numeric', 30.00, 'Component Paper 2', 1, NOW(), NOW()),
(1, 'Cambridge Paper 3', 'CAM-P3', 'numeric', 40.00, 'Component Paper 3', 1, NOW(), NOW()),
(1, 'Cambridge Coursework', 'CAM-CW', 'rubric', 0.00, 'Coursework / Practical', 1, NOW(), NOW()),
(1, 'Cambridge Oral', 'CAM-ORAL', 'rubric', 0.00, 'Oral assessment', 1, NOW(), NOW()),

-- ============ Montessori (id 18-20) ============
(1, 'Observasi Montessori', 'MON-OBS', 'descriptive', 0.00, 'Observasi harian guru', 1, NOW(), NOW()),
(1, 'Mastery Check', 'MON-MASTERY', 'rubric', 0.00, 'Cek penguasaan materi', 1, NOW(), NOW()),
(1, 'Portofolio Montessori', 'MON-PORT', 'descriptive', 0.00, 'Portofolio karya siswa', 1, NOW(), NOW());
```

---

## 3. Seeder `grade_weight_configs`

```sql
-- Grade Weight Configs per kurikulum & tahun ajaran
INSERT INTO `grade_weight_configs` 
(`school_id`, `curriculum_id`, `academic_year_id`, `assessment_type_id`, `weight`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- ============ KTSP (curriculum_id=1) ============
(1, 1, 1, 1, 20.00, 1, NOW(), NOW()),  -- UH-KTSP
(1, 1, 1, 2, 30.00, 1, NOW(), NOW()),  -- UTS-KTSP
(1, 1, 1, 3, 50.00, 1, NOW(), NOW()),  -- UAS-KTSP

-- ============ K13 (curriculum_id=2) ============
(1, 2, 1, 4, 25.00, 1, NOW(), NOW()),  -- PH-K13
(1, 2, 1, 5, 30.00, 1, NOW(), NOW()),  -- PTS-K13
(1, 2, 1, 6, 45.00, 1, NOW(), NOW()),  -- PAS-K13
(1, 2, 1, 7, 0.00, 1, NOW(), NOW()),   -- Sikap Spiritual (deskriptif)
(1, 2, 1, 8, 0.00, 1, NOW(), NOW()),   -- Sikap Sosial (deskriptif)

-- ============ Kurikulum Merdeka (curriculum_id=3) ============
(1, 3, 1, 9, 40.00, 1, NOW(), NOW()),   -- Formatif
(1, 3, 1, 10, 60.00, 1, NOW(), NOW()),  -- Sumatif
(1, 3, 1, 11, 0.00, 1, NOW(), NOW()),   -- P5 (deskriptif)
(1, 3, 1, 12, 0.00, 1, NOW(), NOW()),   -- Diagnostik (deskriptif)

-- ============ Cambridge (curriculum_id=4) ============
(1, 4, 1, 13, 30.00, 1, NOW(), NOW()),  -- Paper 1
(1, 4, 1, 14, 30.00, 1, NOW(), NOW()),  -- Paper 2
(1, 4, 1, 15, 40.00, 1, NOW(), NOW()),  -- Paper 3
(1, 4, 1, 16, 0.00, 1, NOW(), NOW()),   -- Coursework
(1, 4, 1, 17, 0.00, 1, NOW(), NOW()),   -- Oral

-- ============ Montessori (curriculum_id=5) ============
(1, 5, 1, 18, 0.00, 1, NOW(), NOW()),   -- Observasi
(1, 5, 1, 19, 0.00, 1, NOW(), NOW()),   -- Mastery Check
(1, 5, 1, 20, 0.00, 1, NOW(), NOW());   -- Portofolio
```

---

## 4. Seeder `report_card_template_sections` & `report_card_components`

### 4.1 KTSP — Template Raport (template_id = 1)

```sql
-- Sections KTSP
INSERT INTO `report_card_template_sections` 
(`report_card_template_id`, `section_type`, `title`, `order`, `config`, `is_active`, `created_at`, `updated_at`) 
VALUES
(1, 'header', 'Identitas Siswa', 1, JSON_OBJECT('show_logo', true, 'show_photo', true), 1, NOW(), NOW()),
(1, 'grades', 'Nilai Mata Pelajaran', 2, JSON_OBJECT('show_rank', true, 'show_predicate', true), 1, NOW(), NOW()),
(1, 'attitude', 'Penilaian Sikap', 3, JSON_OBJECT('aspects', JSON_ARRAY('spiritual','sosial')), 1, NOW(), NOW()),
(1, 'attendance', 'Kehadiran', 4, NULL, 1, NOW(), NOW()),
(1, 'extracurricular', 'Ekstrakurikuler', 5, NULL, 1, NOW(), NOW()),
(1, 'notes', 'Catatan Wali Kelas', 6, NULL, 1, NOW(), NOW()),
(1, 'signature', 'Tanda Tangan', 7, JSON_OBJECT('roles', JSON_ARRAY('wali_kelas','kepsek','orang_tua')), 1, NOW(), NOW());

-- Components KTSP (section_id mengacu ke insert di atas, mulai id 1)
INSERT INTO `report_card_components` 
(`report_card_template_section_id`, `component_type`, `field_key`, `label`, `order`, `config`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Header
(1, 'text', 'student_name', 'Nama Siswa', 1, NULL, 1, NOW(), NOW()),
(1, 'text', 'student_nis', 'NIS', 2, NULL, 1, NOW(), NOW()),
(1, 'text', 'class_level', 'Kelas', 3, NULL, 1, NOW(), NOW()),
(1, 'text', 'academic_year', 'Tahun Ajaran', 4, NULL, 1, NOW(), NOW()),
-- Grades
(2, 'table', 'subject_grades', 'Daftar Nilai', 1, JSON_OBJECT('columns', JSON_ARRAY('subject','knowledge_score','predicate','description')), 1, NOW(), NOW()),
(2, 'text', 'average_score', 'Rata-rata', 2, NULL, 1, NOW(), NOW()),
(2, 'text', 'rank', 'Peringkat', 3, NULL, 1, NOW(), NOW()),
-- Attitude
(3, 'text', 'spiritual_predicate', 'Predikat Spiritual', 1, NULL, 1, NOW(), NOW()),
(3, 'text', 'spiritual_description', 'Deskripsi Spiritual', 2, NULL, 1, NOW(), NOW()),
(3, 'text', 'social_predicate', 'Predikat Sosial', 3, NULL, 1, NOW(), NOW()),
(3, 'text', 'social_description', 'Deskripsi Sosial', 4, NULL, 1, NOW(), NOW()),
-- Attendance
(4, 'text', 'sick', 'Sakit', 1, NULL, 1, NOW(), NOW()),
(4, 'text', 'permission', 'Izin', 2, NULL, 1, NOW(), NOW()),
(4, 'text', 'absent', 'Alpa', 3, NULL, 1, NOW(), NOW()),
-- Extracurricular
(5, 'table', 'extracurricular_list', 'Daftar Ekskul', 1, JSON_OBJECT('columns', JSON_ARRAY('name','predicate','description')), 1, NOW(), NOW()),
-- Notes
(6, 'textarea', 'homeroom_note', 'Catatan Wali Kelas', 1, NULL, 1, NOW(), NOW()),
-- Signature
(7, 'signature', 'homeroom_signature', 'Tanda Tangan Wali Kelas', 1, NULL, 1, NOW(), NOW()),
(7, 'signature', 'principal_signature', 'Tanda Tangan Kepala Sekolah', 2, NULL, 1, NOW(), NOW()),
(7, 'signature', 'parent_signature', 'Tanda Tangan Orang Tua', 3, NULL, 1, NOW(), NOW());
```

### 4.2 K13 — Template Raport (template_id = 2)

```sql
-- Sections K13
INSERT INTO `report_card_template_sections` 
(`report_card_template_id`, `section_type`, `title`, `order`, `config`, `is_active`, `created_at`, `updated_at`) 
VALUES
(2, 'header', 'Identitas Siswa', 1, JSON_OBJECT('show_logo', true, 'show_photo', true), 1, NOW(), NOW()),
(2, 'grades', 'Penilaian Pengetahuan', 2, JSON_OBJECT('show_rank', true, 'show_predicate', true), 1, NOW(), NOW()),
(2, 'grades', 'Penilaian Keterampilan', 3, JSON_OBJECT('show_rank', false, 'show_predicate', true), 1, NOW(), NOW()),
(2, 'attitude', 'Sikap Spiritual', 4, JSON_OBJECT('aspects', JSON_ARRAY('spiritual')), 1, NOW(), NOW()),
(2, 'attitude', 'Sikap Sosial', 5, JSON_OBJECT('aspects', JSON_ARRAY('sosial')), 1, NOW(), NOW()),
(2, 'attendance', 'Kehadiran', 6, NULL, 1, NOW(), NOW()),
(2, 'extracurricular', 'Ekstrakurikuler', 7, NULL, 1, NOW(), NOW()),
(2, 'notes', 'Catatan Wali Kelas', 8, NULL, 1, NOW(), NOW()),
(2, 'signature', 'Tanda Tangan', 9, JSON_OBJECT('roles', JSON_ARRAY('wali_kelas','kepsek','orang_tua')), 1, NOW(), NOW());

-- Components K13 (section_id mengacu ke insert di atas, mulai id 8)
INSERT INTO `report_card_components` 
(`report_card_template_section_id`, `component_type`, `field_key`, `label`, `order`, `config`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Header (section 8)
(8, 'text', 'student_name', 'Nama Siswa', 1, NULL, 1, NOW(), NOW()),
(8, 'text', 'student_nis', 'NIS', 2, NULL, 1, NOW(), NOW()),
(8, 'text', 'class_level', 'Kelas', 3, NULL, 1, NOW(), NOW()),
(8, 'text', 'academic_year', 'Tahun Ajaran', 4, NULL, 1, NOW(), NOW()),
-- Pengetahuan (section 9)
(9, 'table', 'knowledge_grades', 'Nilai Pengetahuan', 1, JSON_OBJECT('columns', JSON_ARRAY('subject','score','predicate','description')), 1, NOW(), NOW()),
(9, 'text', 'knowledge_average', 'Rata-rata Pengetahuan', 2, NULL, 1, NOW(), NOW()),
(9, 'text', 'knowledge_rank', 'Peringkat', 3, NULL, 1, NOW(), NOW()),
-- Keterampilan (section 10)
(10, 'table', 'skill_grades', 'Nilai Keterampilan', 1, JSON_OBJECT('columns', JSON_ARRAY('subject','score','predicate','description')), 1, NOW(), NOW()),
(10, 'text', 'skill_average', 'Rata-rata Keterampilan', 2, NULL, 1, NOW(), NOW()),
-- Sikap Spiritual (section 11)
(11, 'text', 'spiritual_predicate', 'Predikat', 1, NULL, 1, NOW(), NOW()),
(11, 'textarea', 'spiritual_description', 'Deskripsi', 2, NULL, 1, NOW(), NOW()),
-- Sikap Sosial (section 12)
(12, 'text', 'social_predicate', 'Predikat', 1, NULL, 1, NOW(), NOW()),
(12, 'textarea', 'social_description', 'Deskripsi', 2, NULL, 1, NOW(), NOW()),
-- Kehadiran (section 13)
(13, 'text', 'sick', 'Sakit', 1, NULL, 1, NOW(), NOW()),
(13, 'text', 'permission', 'Izin', 2, NULL, 1, NOW(), NOW()),
(13, 'text', 'absent', 'Alpa', 3, NULL, 1, NOW(), NOW()),
-- Ekstrakurikuler (section 14)
(14, 'table', 'extracurricular_list', 'Daftar Ekskul', 1, JSON_OBJECT('columns', JSON_ARRAY('name','predicate','description')), 1, NOW(), NOW()),
-- Catatan (section 15)
(15, 'textarea', 'homeroom_note', 'Catatan Wali Kelas', 1, NULL, 1, NOW(), NOW()),
-- Tanda Tangan (section 16)
(16, 'signature', 'homeroom_signature', 'Tanda Tangan Wali Kelas', 1, NULL, 1, NOW(), NOW()),
(16, 'signature', 'principal_signature', 'Tanda Tangan Kepala Sekolah', 2, NULL, 1, NOW(), NOW()),
(16, 'signature', 'parent_signature', 'Tanda Tangan Orang Tua', 3, NULL, 1, NOW(), NOW());
```

### 4.3 Kurikulum Merdeka — Template Raport (template_id = 3)

```sql
-- Sections Merdeka
INSERT INTO `report_card_template_sections` 
(`report_card_template_id`, `section_type`, `title`, `order`, `config`, `is_active`, `created_at`, `updated_at`) 
VALUES
(3, 'header', 'Identitas Siswa', 1, JSON_OBJECT('show_logo', true, 'show_photo', true), 1, NOW(), NOW()),
(3, 'grades', 'Nilai Intrakurikuler', 2, JSON_OBJECT('show_rank', false, 'show_predicate', true, 'descriptive', true), 1, NOW(), NOW()),
(3, 'p5', 'Projek Penguatan Profil Pelajar Pancasila', 3, JSON_OBJECT('dimensions', JSON_ARRAY('beriman','berkebinekaan','gotong_royong','mandiri','bernalar_kritis','kreatif')), 1, NOW(), NOW()),
(3, 'attendance', 'Kehadiran', 4, NULL, 1, NOW(), NOW()),
(3, 'extracurricular', 'Ekstrakurikuler', 5, NULL, 1, NOW(), NOW()),
(3, 'notes', 'Catatan Wali Kelas', 6, NULL, 1, NOW(), NOW()),
(3, 'signature', 'Tanda Tangan', 7, JSON_OBJECT('roles', JSON_ARRAY('wali_kelas','kepsek','orang_tua')), 1, NOW(), NOW());

-- Components Merdeka (section_id mulai id 17)
INSERT INTO `report_card_components` 
(`report_card_template_section_id`, `component_type`, `field_key`, `label`, `order`, `config`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Header (17)
(17, 'text', 'student_name', 'Nama Siswa', 1, NULL, 1, NOW(), NOW()),
(17, 'text', 'student_nis', 'NIS', 2, NULL, 1, NOW(), NOW()),
(17, 'text', 'class_level', 'Kelas', 3, NULL, 1, NOW(), NOW()),
(17, 'text', 'academic_year', 'Tahun Ajaran', 4, NULL, 1, NOW(), NOW()),
-- Nilai Intrakurikuler (18)
(18, 'table', 'subject_grades', 'Nilai Mata Pelajaran', 1, JSON_OBJECT('columns', JSON_ARRAY('subject','score','predicate','description')), 1, NOW(), NOW()),
(18, 'textarea', 'overall_description', 'Deskripsi Umum', 2, NULL, 1, NOW(), NOW()),
-- P5 (19)
(19, 'table', 'p5_projects', 'Daftar Projek P5', 1, JSON_OBJECT('columns', JSON_ARRAY('project_name','dimensions','predicate','description')), 1, NOW(), NOW()),
(19, 'textarea', 'p5_summary', 'Rangkuman P5', 2, NULL, 1, NOW(), NOW()),
-- Kehadiran (20)
(20, 'text', 'sick', 'Sakit', 1, NULL, 1, NOW(), NOW()),
(20, 'text', 'permission', 'Izin', 2, NULL, 1, NOW(), NOW()),
(20, 'text', 'absent', 'Alpa', 3, NULL, 1, NOW(), NOW()),
-- Ekstrakurikuler (21)
(21, 'table', 'extracurricular_list', 'Daftar Ekskul', 1, JSON_OBJECT('columns', JSON_ARRAY('name','predicate','description')), 1, NOW(), NOW()),
-- Catatan (22)
(22, 'textarea', 'homeroom_note', 'Catatan Wali Kelas', 1, NULL, 1, NOW(), NOW()),
-- Tanda Tangan (23)
(23, 'signature', 'homeroom_signature', 'Tanda Tangan Wali Kelas', 1, NULL, 1, NOW(), NOW()),
(23, 'signature', 'principal_signature', 'Tanda Tangan Kepala Sekolah', 2, NULL, 1, NOW(), NOW()),
(23, 'signature', 'parent_signature', 'Tanda Tangan Orang Tua', 3, NULL, 1, NOW(), NOW());
```

### 4.4 Cambridge — Template Report Card (template_id = 4)

```sql
-- Sections Cambridge
INSERT INTO `report_card_template_sections` 
(`report_card_template_id`, `section_type`, `title`, `order`, `config`, `is_active`, `created_at`, `updated_at`) 
VALUES
(4, 'header', 'Student Identity', 1, JSON_OBJECT('show_logo', true, 'show_photo', true), 1, NOW(), NOW()),
(4, 'grades', 'Subject Grades', 2, JSON_OBJECT('show_rank', false, 'show_grade_boundaries', true, 'show_component_breakdown', true), 1, NOW(), NOW()),
(4, 'grades', 'Grade Boundaries', 3, JSON_OBJECT('show_thresholds', true), 1, NOW(), NOW()),
(4, 'attendance', 'Attendance', 4, NULL, 1, NOW(), NOW()),
(4, 'notes', 'Tutor Comment', 5, NULL, 1, NOW(), NOW()),
(4, 'signature', 'Signatures', 6, JSON_OBJECT('roles', JSON_ARRAY('homeroom','principal','parent')), 1, NOW(), NOW());

-- Components Cambridge (section_id mulai id 24)
INSERT INTO `report_card_components` 
(`report_card_template_section_id`, `component_type`, `field_key`, `label`, `order`, `config`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Header (24)
(24, 'text', 'student_name', 'Student Name', 1, NULL, 1, NOW(), NOW()),
(24, 'text', 'student_id', 'Student ID', 2, NULL, 1, NOW(), NOW()),
(24, 'text', 'stage', 'Stage / Grade', 3, NULL, 1, NOW(), NOW()),
(24, 'text', 'academic_year', 'Academic Year', 4, NULL, 1, NOW(), NOW()),
-- Subject Grades (25)
(25, 'table', 'subject_grades', 'Subjects', 1, JSON_OBJECT('columns', JSON_ARRAY('subject','paper1','paper2','paper3','total','grade','description')), 1, NOW(), NOW()),
(25, 'text', 'overall_grade', 'Overall Grade', 2, NULL, 1, NOW(), NOW()),
(25, 'textarea', 'subject_comment', 'Subject Comment', 3, NULL, 1, NOW(), NOW()),
-- Grade Boundaries (26)
(26, 'table', 'grade_boundaries', 'Grade Boundaries', 1, JSON_OBJECT('columns', JSON_ARRAY('grade','min_score','max_score','description')), 1, NOW(), NOW()),
-- Attendance (27)
(27, 'text', 'present', 'Present', 1, NULL, 1, NOW(), NOW()),
(27, 'text', 'absent', 'Absent', 2, NULL, 1, NOW(), NOW()),
(27, 'text', 'late', 'Late', 3, NULL, 1, NOW(), NOW()),
-- Tutor Comment (28)
(28, 'textarea', 'tutor_comment', 'Tutor Comment', 1, NULL, 1, NOW(), NOW()),
-- Signatures (29)
(29, 'signature', 'homeroom_signature', 'Homeroom Teacher', 1, NULL, 1, NOW(), NOW()),
(29, 'signature', 'principal_signature', 'Principal', 2, NULL, 1, NOW(), NOW()),
(29, 'signature', 'parent_signature', 'Parent', 3, NULL, 1, NOW(), NOW());
```

### 4.5 Montessori — Template Progress Report (template_id = 5)

```sql
-- Sections Montessori
INSERT INTO `report_card_template_sections` 
(`report_card_template_id`, `section_type`, `title`, `order`, `config`, `is_active`, `created_at`, `updated_at`) 
VALUES
(5, 'header', 'Child Identity', 1, JSON_OBJECT('show_logo', true, 'show_photo', true), 1, NOW(), NOW()),
(5, 'learning_plan', 'Individual Learning Plan', 2, JSON_OBJECT('show_goals', true, 'show_progress', true), 1, NOW(), NOW()),
(5, 'mastery', 'Mastery Tracking', 3, JSON_OBJECT('show_status', true, 'show_notes', true), 1, NOW(), NOW()),
(5, 'observation', 'Observation Notes', 4, JSON_OBJECT('show_teacher_notes', true), 1, NOW(), NOW()),
(5, 'portfolio', 'Portfolio', 5, JSON_OBJECT('show_artifacts', true), 1, NOW(), NOW()),
(5, 'attendance', 'Attendance', 6, NULL, 1, NOW(), NOW()),
(5, 'signature', 'Signatures', 7, JSON_OBJECT('roles', JSON_ARRAY('guide','principal','parent')), 1, NOW(), NOW());

-- Components Montessori (section_id mulai id 30)
INSERT INTO `report_card_components` 
(`report_card_template_section_id`, `component_type`, `field_key`, `label`, `order`, `config`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Header (30)
(30, 'text', 'child_name', 'Child Name', 1, NULL, 1, NOW(), NOW()),
(30, 'text', 'child_id', 'Child ID', 2, NULL, 1, NOW(), NOW()),
(30, 'text', 'age_group', 'Age Group', 3, NULL, 1, NOW(), NOW()),
(30, 'text', 'academic_year', 'Academic Year', 4, NULL, 1, NOW(), NOW()),
-- Learning Plan (31)
(31, 'table', 'learning_goals', 'Learning Goals', 1, JSON_OBJECT('columns', JSON_ARRAY('area','goal','progress','notes')), 1, NOW(), NOW()),
(31, 'textarea', 'plan_summary', 'Plan Summary', 2, NULL, 1, NOW(), NOW()),
-- Mastery (32)
(32, 'table', 'mastery_items', 'Mastery Items', 1, JSON_OBJECT('columns', JSON_ARRAY('area','objective','status','mastered_at')), 1, NOW(), NOW()),
(32, 'textarea', 'mastery_notes', 'Mastery Notes', 2, NULL, 1, NOW(), NOW()),
-- Observation (33)
(33, 'textarea', 'observation_notes', 'Observation Notes', 1, NULL, 1, NOW(), NOW()),
(33, 'text', 'observed_by', 'Observed By', 2, NULL, 1, NOW(), NOW()),
-- Portfolio (34)
(34, 'table', 'portfolio_items', 'Portfolio Items', 1, JSON_OBJECT('columns', JSON_ARRAY('title','area','date','description')), 1, NOW(), NOW()),
-- Attendance (35)
(35, 'text', 'present', 'Present', 1, NULL, 1, NOW(), NOW()),
(35, 'text', 'absent', 'Absent', 2, NULL, 1, NOW(), NOW()),
(35, 'text', 'late', 'Late', 3, NULL, 1, NOW(), NOW()),
-- Signatures (36)
(36, 'signature', 'guide_signature', 'Guide Signature', 1, NULL, 1, NOW(), NOW()),
(36, 'signature', 'principal_signature', 'Principal Signature', 2, NULL, 1, NOW(), NOW()),
(36, 'signature', 'parent_signature', 'Parent Signature', 3, NULL, 1, NOW(), NOW());
```

---

## 5. Query Verifikasi

### 5.1 Cek CP/KD per kurikulum

```sql
SELECT 
  c.code AS curriculum,
  cp.code AS phase,
  s.code AS subject,
  COUNT(clo.id) AS total_outcomes
FROM curriculums c
JOIN curriculum_phases cp ON cp.curriculum_id = c.id
JOIN curriculum_learning_outcomes clo ON clo.curriculum_phase_id = cp.id
JOIN subjects s ON s.id = clo.subject_id
WHERE clo.deleted_at IS NULL
GROUP BY c.id, cp.id, s.id
ORDER BY c.id, cp.order, s.name;
```

### 5.2 Cek bobot nilai per kurikulum

```sql
SELECT 
  c.code AS curriculum,
  at.code AS assessment_type,
  at.method,
  gwc.weight
FROM grade_weight_configs gwc
JOIN curriculums c ON c.id = gwc.curriculum_id
JOIN assessment_types at ON at.id = gwc.assessment_type_id
WHERE gwc.deleted_at IS NULL
  AND gwc.academic_year_id = 1
ORDER BY c.id, at.id;
```

### 5.3 Cek struktur template raport

```sql
SELECT 
  rct.code AS template,
  rcts.order AS section_order,
  rcts.title AS section_title,
  rcc.order AS component_order,
  rcc.field_key,
  rcc.label,
  rcc.component_type
FROM report_card_templates rct
JOIN report_card_template_sections rcts ON rcts.report_card_template_id = rct.id
LEFT JOIN report_card_components rcc ON rcc.report_card_template_section_id = rcts.id
WHERE rct.deleted_at IS NULL
  AND rcts.deleted_at IS NULL
ORDER BY rct.id, rcts.order, rcc.order;
```

### 5.4 Cek mapping template ↔ kurikulum

```sql
SELECT 
  c.code AS curriculum,
  rct.code AS template,
  rct.name AS template_name,
  rct.version
FROM report_card_template_curriculums rctc
JOIN curriculums c ON c.id = rctc.curriculum_id
JOIN report_card_templates rct ON rct.id = rctc.report_card_template_id
WHERE rctc.deleted_at IS NULL
ORDER BY c.id;
```

---

## 6. Ringkasan Struktur Seeder

| Komponen | Total Record | Catatan |
|----------|:---:|---------|
| `curriculum_learning_outcomes` | ~60 | CP/KD/SK per kurikulum & mapel |
| `assessment_types` | 20 | 3 KTSP + 5 K13 + 4 Merdeka + 5 Cambridge + 3 Montessori |
| `grade_weight_configs` | 20 | Mapping bobot per kurikulum & tahun ajaran |
| `report_card_template_sections` | 36 | Total 7-9 section per template |
| `report_card_components` | ~85 | Total 15-20 komponen per template |

---

## 7. Catatan Penting

1. **ID Section**: Seeder di atas mengasumsikan `report_card_template_sections.id` berurutan mulai dari 1 (KTSP) sampai 36 (Montessori). Jika ada data existing, ganti angka di `report_card_components.report_card_template_section_id` sesuai ID aktual.

2. **`curriculum_learning_outcomes`** bisa dipakai untuk:
   - **KTSP**: Standar Kompetensi (SK)
   - **K13**: Kompetensi Dasar (KD) per KI
   - **Merdeka**: Capaian Pembelajaran (CP)
   - **Cambridge**: Learning Objectives
   - **Montessori**: Learning Outcomes per area

3. **`element`** di CP/KD: `spiritual`, `sosial`, `pengetahuan`, `keterampilan`, `sikap`.

4. **`grade_weight_configs`**: Untuk K13 & Cambridge, bobot dijumlahkan = 100%. Untuk deskriptif (sikap, P5, observasi), bobot = 0% karena tidak masuk perhitungan angka.

5. **`report_card_components`**: `field_key` harus konsisten dengan key yang di-generate di `generated_reports.report_data` (JSON). Misal: `subject_grades`, `spiritual_predicate`, dll.

6. **`config` JSON**: Fleksibel untuk kustomisasi tampilan tanpa ubah skema. Misal: `show_rank`, `columns`, `aspects`.

---

Kalau Anda mau, saya bisa lanjutkan dengan:

- **Seeder `curriculum_objectives`** (TP/Indikator turunan dari CP/KD) — untuk mendukung `mastery_tracking` Montessori.
- **Query generate raport** — mengambil nilai dari `student_assessments` → agregasi ke `report_card_grades` → render ke `generated_reports`.
- **Contoh isi `template_data` JSON** di `report_card_templates` yang lebih kaya (layout, style, header/footer).

# Lanjutan: Objectives, Query Generate Raport & Template JSON

---

## BAGIAN 1: Seeder `curriculum_objectives` (TP/Indikator)

Tujuan Pembelajaran (TP) / Indikator adalah turunan dari CP/KD/SK. Untuk Montessori, TP ini menjadi dasar `mastery_tracking`.

> **Asumsi**: `curriculum_learning_outcomes.id` berurutan sesuai seeder sebelumnya. Jika berbeda, sesuaikan `learning_outcome_id`.

### 1.1 KTSP — Indikator dari SK

```sql
INSERT INTO `curriculum_objectives` 
(`school_id`, `learning_outcome_id`, `code`, `description`, `criteria`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- SK Matematika SD "Melakukan operasi hitung bilangan sampai tiga angka" (outcome id=1)
(1, 1, 'KTSP-MTK-SD-1.1', 'Membaca dan menulis bilangan sampai 100', 'Siswa dapat membaca & menulis bilangan 1-100 dengan benar', 1, 1, NOW(), NOW()),
(1, 1, 'KTSP-MTK-SD-1.2', 'Membandingkan dua bilangan', 'Siswa dapat menentukan bilangan lebih besar/kecil', 2, 1, NOW(), NOW()),
(1, 1, 'KTSP-MTK-SD-1.3', 'Melakukan penjumlahan dan pengurangan', 'Siswa dapat menjumlah & mengurang bilangan sampai 100', 3, 1, NOW(), NOW()),

-- SK Matematika SD "Menggunakan pengukuran waktu, panjang, berat" (outcome id=2)
(1, 2, 'KTSP-MTK-SD-2.1', 'Mengenal satuan waktu', 'Siswa dapat menyebutkan jam, menit, detik', 1, 1, NOW(), NOW()),
(1, 2, 'KTSP-MTK-SD-2.2', 'Mengukur panjang dengan satuan tidak baku', 'Siswa dapat mengukur dengan jengkal, hasta', 2, 1, NOW(), NOW()),

-- SK Matematika SMP "Melakukan operasi aljabar" (outcome id=5)
(1, 5, 'KTSP-MTK-SMP-1.1', 'Menyederhanakan bentuk aljabar', 'Siswa dapat menyederhanakan suku-suku sejenis', 1, 1, NOW(), NOW()),
(1, 5, 'KTSP-MTK-SMP-1.2', 'Melakukan operasi pada bentuk aljabar', 'Siswa dapat menjumlah, mengurang, mengali bentuk aljabar', 2, 1, NOW(), NOW()),
(1, 5, 'KTSP-MTK-SMP-1.3', 'Memfaktorkan bentuk aljabar', 'Siswa dapat memfaktorkan bentuk ax²+bx+c', 3, 1, NOW(), NOW()),

-- SK Bahasa Indonesia SD "Membaca dan memahami teks pendek" (outcome id=15)
(1, 15, 'KTSP-BIND-SD-3.1', 'Membaca lancar teks pendek', 'Siswa dapat membaca 5 kalimat tanpa terbata-bata', 1, 1, NOW(), NOW()),
(1, 15, 'KTSP-BIND-SD-3.2', 'Menjawab pertanyaan tentang isi teks', 'Siswa dapat menjawab 3 dari 5 pertanyaan dengan benar', 2, 1, NOW(), NOW());
```

### 1.2 K13 — Indikator dari KD

```sql
INSERT INTO `curriculum_objectives` 
(`school_id`, `learning_outcome_id`, `code`, `description`, `criteria`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- KD Matematika SMP "Menjelaskan dan menentukan urutan bilangan bulat" (outcome id=17)
(1, 17, 'K13-MTK-SMP-3.1.1', 'Membandingkan bilangan bulat', 'Siswa dapat mengurutkan bilangan bulat dari terkecil ke terbesar', 1, 1, NOW(), NOW()),
(1, 17, 'K13-MTK-SMP-3.1.2', 'Melakukan operasi bilangan bulat', 'Siswa dapat menjumlah, mengurang, mengali, membagi bilangan bulat', 2, 1, NOW(), NOW()),
(1, 17, 'K13-MTK-SMP-3.1.3', 'Menyelesaikan masalah kontekstual bilangan bulat', 'Siswa dapat menyelesaikan soal cerita bilangan bulat', 3, 1, NOW(), NOW()),

-- KD Matematika SMA "Menjelaskan persamaan dan pertidaksamaan nilai mutlak" (outcome id=22)
(1, 22, 'K13-MTK-SMA-3.1.1', 'Menjelaskan konsep nilai mutlak', 'Siswa dapat mendefinisikan nilai mutlak', 1, 1, NOW(), NOW()),
(1, 22, 'K13-MTK-SMA-3.1.2', 'Menyelesaikan persamaan nilai mutlak', 'Siswa dapat menyelesaikan |ax+b| = c', 2, 1, NOW(), NOW()),
(1, 22, 'K13-MTK-SMA-3.1.3', 'Menyelesaikan pertidaksamaan nilai mutlak', 'Siswa dapat menyelesaikan |ax+b| < c', 3, 1, NOW(), NOW()),

-- KD Bahasa Indonesia SMP "Mengidentifikasi teks deskripsi" (outcome id=27)
(1, 27, 'K13-BIND-SMP-3.1.1', 'Menyebutkan ciri teks deskripsi', 'Siswa dapat menyebutkan 4 ciri teks deskripsi', 1, 1, NOW(), NOW()),
(1, 27, 'K13-BIND-SMP-3.1.2', 'Mengidentifikasi struktur teks deskripsi', 'Siswa dapat menentukan bagian identifikasi & deskripsi', 2, 1, NOW(), NOW()),

-- KD IPA SMP "Menjelaskan klasifikasi makhluk hidup" (outcome id=31)
(1, 31, 'K13-IPA-SMP-3.1.1', 'Menyebutkan ciri makhluk hidup', 'Siswa dapat menyebutkan 5 ciri makhluk hidup', 1, 1, NOW(), NOW()),
(1, 31, 'K13-IPA-SMP-3.1.2', 'Mengklasifikasikan hewan berdasarkan makanan', 'Siswa dapat membagi herbivora, karnivora, omnivora', 2, 1, NOW(), NOW()),
(1, 31, 'K13-IPA-SMP-3.1.3', 'Menggunakan kunci determinasi sederhana', 'Siswa dapat menggunakan kunci dikotomi', 3, 1, NOW(), NOW());
```

### 1.3 Kurikulum Merdeka — Tujuan Pembelajaran (TP) dari CP

```sql
INSERT INTO `curriculum_objectives` 
(`school_id`, `learning_outcome_id`, `code`, `description`, `criteria`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- CP Matematika Fase A "Memahami bilangan cacah sampai 100" (outcome id=36)
(1, 36, 'MERDEKA-MTK-A-1.1', 'Membaca dan menulis bilangan cacah sampai 100', 'Siswa dapat membaca & menulis bilangan 1-100', 1, 1, NOW(), NOW()),
(1, 36, 'MERDEKA-MTK-A-1.2', 'Membandingkan dan mengurutkan bilangan cacah', 'Siswa dapat mengurutkan 5 bilangan dari terkecil-terbesar', 2, 1, NOW(), NOW()),
(1, 36, 'MERDEKA-MTK-A-1.3', 'Melakukan penjumlahan dan pengurangan bilangan cacah', 'Siswa dapat menjumlah & mengurang sampai 100', 3, 1, NOW(), NOW()),

-- CP Matematika Fase D "Memahami bilangan rasional dan irasional" (outcome id=39)
(1, 39, 'MERDEKA-MTK-D-1.1', 'Membedakan bilangan rasional dan irasional', 'Siswa dapat memberi contoh masing-masing 3 bilangan', 1, 1, NOW(), NOW()),
(1, 39, 'MERDEKA-MTK-D-1.2', 'Melakukan operasi bilangan rasional', 'Siswa dapat menjumlah, mengurang, mengali, membagi pecahan', 2, 1, NOW(), NOW()),
(1, 39, 'MERDEKA-MTK-D-1.3', 'Mengubah bentuk pecahan-desimal-persen', 'Siswa dapat mengkonversi antar 3 bentuk', 3, 1, NOW(), NOW()),

-- CP Matematika Fase D "Memahami aljabar dan persamaan linear" (outcome id=40)
(1, 40, 'MERDEKA-MTK-D-2.1', 'Menyederhanakan bentuk aljabar', 'Siswa dapat menyederhanakan suku sejenis', 1, 1, NOW(), NOW()),
(1, 40, 'MERDEKA-MTK-D-2.2', 'Menyelesaikan persamaan linear satu variabel', 'Siswa dapat menyelesaikan PLSV', 2, 1, NOW(), NOW()),
(1, 40, 'MERDEKA-MTK-D-2.3', 'Menyelesaikan sistem persamaan linear dua variabel', 'Siswa dapat menyelesaikan SPLDV dengan 3 metode', 3, 1, NOW(), NOW()),

-- CP Matematika Fase E "Memahami eksponen dan logaritma" (outcome id=45)
(1, 45, 'MERDEKA-MTK-E-1.1', 'Menjelaskan sifat-sifat eksponen', 'Siswa dapat menyebutkan 5 sifat eksponen', 1, 1, NOW(), NOW()),
(1, 45, 'MERDEKA-MTK-E-1.2', 'Menyelesaikan persamaan eksponen', 'Siswa dapat menyelesaikan a^f(x) = a^g(x)', 2, 1, NOW(), NOW()),
(1, 45, 'MERDEKA-MTK-E-1.3', 'Menjelaskan hubungan eksponen dan logaritma', 'Siswa dapat mengubah bentuk eksponen ↔ logaritma', 3, 1, NOW(), NOW()),

-- CP Bahasa Indonesia Fase D "Memahami teks deskripsi, narasi, eksposisi" (outcome id=49)
(1, 49, 'MERDEKA-BIND-D-1.1', 'Mengidentifikasi struktur teks deskripsi', 'Siswa dapat menentukan identifikasi & deskripsi bagian', 1, 1, NOW(), NOW()),
(1, 49, 'MERDEKA-BIND-D-1.2', 'Mengidentifikasi struktur teks narasi', 'Siswa dapat menentukan orientasi, komplikasi, resolusi', 2, 1, NOW(), NOW()),
(1, 49, 'MERDEKA-BIND-D-1.3', 'Mengidentifikasi struktur teks eksposisi', 'Siswa dapat menentukan tesis, argumen, penegasan', 3, 1, NOW(), NOW()),

-- CP IPA Fase D "Memahami klasifikasi makhluk hidup" (outcome id=52)
(1, 52, 'MERDEKA-IPA-D-1.1', 'Mengelompokkan makhluk hidup berdasarkan ciri', 'Siswa dapat mengelompokkan 5 kingdom', 1, 1, NOW(), NOW()),
(1, 52, 'MERDEKA-IPA-D-1.2', 'Menggunakan kunci determinasi', 'Siswa dapat mengidentifikasi spesies dengan kunci dikotomi', 2, 1, NOW(), NOW()),
(1, 52, 'MERDEKA-IPA-D-1.3', 'Menjelaskan peran makhluk hidup dalam ekosistem', 'Siswa dapat menjelaskan rantai makanan', 3, 1, NOW(), NOW());
```

### 1.4 Cambridge — Learning Objectives Turunan

```sql
INSERT INTO `curriculum_objectives` 
(`school_id`, `learning_outcome_id`, `code`, `description`, `criteria`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Cambridge Primary Math "Number: counting, place value, and operations" (outcome id=57)
(1, 57, 'CAM-PRI-MATH-1.1', 'Count, read, and write numbers to 1000', 'Students can count, read, write numbers up to 1000', 1, 1, NOW(), NOW()),
(1, 57, 'CAM-PRI-MATH-1.2', 'Understand place value (ones, tens, hundreds)', 'Students can identify place value of each digit', 2, 1, NOW(), NOW()),
(1, 57, 'CAM-PRI-MATH-1.3', 'Add and subtract numbers up to 1000', 'Students can add and subtract with and without regrouping', 3, 1, NOW(), NOW()),

-- Cambridge IGCSE Math "Algebra: equations, sequences, functions" (outcome id=62)
(1, 62, 'CAM-IGCSE-MATH-2.1', 'Solve linear equations', 'Students can solve linear equations in one variable', 1, 1, NOW(), NOW()),
(1, 62, 'CAM-IGCSE-MATH-2.2', 'Solve simultaneous linear equations', 'Students can solve 2x2 systems by elimination & substitution', 2, 1, NOW(), NOW()),
(1, 62, 'CAM-IGCSE-MATH-2.3', 'Solve quadratic equations', 'Students can solve by factoring, completing square, formula', 3, 1, NOW(), NOW()),
(1, 62, 'CAM-IGCSE-MATH-2.4', 'Work with sequences', 'Students can find nth term of arithmetic & geometric sequences', 4, 1, NOW(), NOW()),

-- Cambridge IGCSE Physics "Motion, forces, and energy" (outcome id=67)
(1, 67, 'CAM-IGCSE-PHY-1.1', 'Describe motion using graphs', 'Students can interpret distance-time & velocity-time graphs', 1, 1, NOW(), NOW()),
(1, 67, 'CAM-IGCSE-PHY-1.2', 'Apply Newton''s laws of motion', 'Students can solve problems using F = ma', 2, 1, NOW(), NOW()),
(1, 67, 'CAM-IGCSE-PHY-1.3', 'Calculate work, energy, and power', 'Students can solve problems using W = Fd, P = W/t', 3, 1, NOW(), NOW()),

-- Cambridge AS/A Math "Pure Mathematics: calculus, algebra" (outcome id=70)
(1, 70, 'CAM-AL-MATH-1.1', 'Differentiate polynomial functions', 'Students can differentiate polynomials using power rule', 1, 1, NOW(), NOW()),
(1, 70, 'CAM-AL-MATH-1.2', 'Integrate polynomial functions', 'Students can integrate polynomials', 2, 1, NOW(), NOW()),
(1, 70, 'CAM-AL-MATH-1.3', 'Apply differentiation to find tangents & normals', 'Students can find equation of tangent & normal', 3, 1, NOW(), NOW());
```

### 1.5 Montessori — Learning Outcomes (untuk `mastery_tracking`)

```sql
INSERT INTO `curriculum_objectives` 
(`school_id`, `learning_outcome_id`, `code`, `description`, `criteria`, `order`, `is_active`, `created_at`, `updated_at`) 
VALUES
-- Casa Practical Life "Care of self: dressing, washing hands" (outcome id=73)
(1, 73, 'MON-CASA-PL-1.1', 'Dressing frame: button', 'Child can button and unbutton independently', 1, 1, NOW(), NOW()),
(1, 73, 'MON-CASA-PL-1.2', 'Dressing frame: zipper', 'Child can zip and unzip independently', 2, 1, NOW(), NOW()),
(1, 73, 'MON-CASA-PL-1.3', 'Hand washing', 'Child can wash hands with soap following steps', 3, 1, NOW(), NOW()),

-- Casa Practical Life "Care of environment: cleaning, gardening" (outcome id=74)
(1, 74, 'MON-CASA-PL-2.1', 'Sweeping floor', 'Child can sweep floor with dustpan', 1, 1, NOW(), NOW()),
(1, 74, 'MON-CASA-PL-2.2', 'Watering plants', 'Child can water plants without spilling', 2, 1, NOW(), NOW()),
(1, 74, 'MON-CASA-PL-2.3', 'Table washing', 'Child can wash table following sequence', 3, 1, NOW(), NOW()),

-- Casa Sensorial "Visual discrimination: size, shape, color" (outcome id=76)
(1, 76, 'MON-CASA-SEN-1.1', 'Pink tower', 'Child can grade 10 cubes by size', 1, 1, NOW(), NOW()),
(1, 76, 'MON-CASA-SEN-1.2', 'Brown stairs', 'Child can order 10 prisms by thickness', 2, 1, NOW(), NOW()),
(1, 76, 'MON-CASA-SEN-1.3', 'Color tablets', 'Child can match and grade color tablets', 3, 1, NOW(), NOW()),
(1, 76, 'MON-CASA-SEN-1.4', 'Geometric cabinet', 'Child can identify 10 geometric shapes', 4, 1, NOW(), NOW()),

-- Lower Elementary Language "Reading fluency and comprehension" (outcome id=79)
(1, 79, 'MON-LOWER-LANG-1.1', 'Read aloud with fluency', 'Child can read 100-word passage with 95% accuracy', 1, 1, NOW(), NOW()),
(1, 79, 'MON-LOWER-LANG-1.2', 'Answer comprehension questions', 'Child can answer 4/5 inferential questions', 2, 1, NOW(), NOW()),
(1, 79, 'MON-LOWER-LANG-1.3', 'Identify main idea', 'Child can identify main idea of a paragraph', 3, 1, NOW(), NOW()),

-- Lower Elementary Language "Creative writing and grammar" (outcome id=80)
(1, 80, 'MON-LOWER-LANG-2.1', 'Identify parts of speech', 'Child can identify noun, verb, adjective, adverb', 1, 1, NOW(), NOW()),
(1, 80, 'MON-LOWER-LANG-2.2', 'Write simple sentences', 'Child can write 5 complete sentences on a topic', 2, 1, NOW(), NOW()),
(1, 80, 'MON-LOWER-LANG-2.3', 'Write short paragraph', 'Child can write 5-sentence paragraph with topic sentence', 3, 1, NOW(), NOW()),

-- Lower Elementary Math "Decimal system and operations" (outcome id=82)
(1, 82, 'MON-LOWER-MATH-1.1', 'Golden beads: addition with regrouping', 'Child can add 4-digit numbers with exchanging', 1, 1, NOW(), NOW()),
(1, 82, 'MON-LOWER-MATH-1.2', 'Stamp game: subtraction', 'Child can subtract 4-digit numbers with borrowing', 2, 1, NOW(), NOW()),
(1, 82, 'MON-LOWER-MATH-1.3', 'Bead frame: multiplication', 'Child can multiply 4-digit by 1-digit', 3, 1, NOW(), NOW()),

-- Lower Elementary Math "Fractions and equivalence" (outcome id=83)
(1, 83, 'MON-LOWER-MATH-2.1', 'Identify fractions', 'Child can name fractions 1/2 to 1/10', 1, 1, NOW(), NOW()),
(1, 83, 'MON-LOWER-MATH-2.2', 'Find equivalent fractions', 'Child can find 3 equivalent fractions for 1/2', 2, 1, NOW(), NOW()),
(1, 83, 'MON-LOWER-MATH-2.3', 'Add fractions with same denominator', 'Child can add fractions with like denominators', 3, 1, NOW(), NOW()),

-- Upper Elementary Cultural "Geography: continents, landforms" (outcome id=85)
(1, 85, 'MON-UPPER-CULT-1.1', 'Identify 7 continents', 'Child can name and locate 7 continents', 1, 1, NOW(), NOW()),
(1, 85, 'MON-UPPER-CULT-1.2', 'Identify landforms', 'Child can identify 10 major landforms', 2, 1, NOW(), NOW()),
(1, 85, 'MON-UPPER-CULT-1.3', 'Use maps and globes', 'Child can find coordinates on a map', 3, 1, NOW(), NOW()),

-- Upper Elementary Cultural "History: timeline of life" (outcome id=86)
(1, 86, 'MON-UPPER-CULT-2.1', 'Order eras of life', 'Child can order 5 eras on timeline', 1, 1, NOW(), NOW()),
(1, 86, 'MON-UPPER-CULT-2.2', 'Identify ancient civilizations', 'Child can name 5 ancient civilizations', 2, 1, NOW(), NOW()),
(1, 86, 'MON-UPPER-CULT-2.3', 'Create personal timeline', 'Child can create timeline of own life', 3, 1, NOW(), NOW());
```

---

## BAGIAN 2: Query Generate Raport

Alur: `student_assessments` → agregasi → `report_card_grades` → `generated_reports`.

### 2.1 Query 1: Hitung Nilai Akhir per Mapel (Weighted Average)

```sql
-- ============================================================
-- STEP 1: Agregasi nilai per siswa per mapel per semester
-- Menggabungkan student_assessments + assessment + weight
-- ============================================================
SELECT 
  sa.student_id,
  cs.subject_id,
  cs.id AS curriculum_structure_id,
  cs.academic_year_id,
  cs.semester,
  cs.curriculum_id,
  -- Nilai rata-rata tertimbang
  ROUND(
    SUM(sa.score * COALESCE(gwc.weight, a.weight, 0) / 100) 
    / NULLIF(SUM(COALESCE(gwc.weight, a.weight, 0) / 100), 0)
  , 2) AS final_score,
  -- Rata-rata sederhana (tanpa bobot)
  ROUND(AVG(sa.score), 2) AS simple_average,
  COUNT(sa.id) AS total_assessments
FROM `student_assessments` sa
JOIN `assessments` a ON a.id = sa.assessment_id
JOIN `curriculum_structures` cs ON cs.id = a.curriculum_structure_id
LEFT JOIN `grade_weight_configs` gwc 
  ON gwc.assessment_type_id = a.assessment_type_id
  AND gwc.curriculum_id = cs.curriculum_id
  AND gwc.academic_year_id = cs.academic_year_id
WHERE sa.deleted_at IS NULL
  AND a.deleted_at IS NULL
  AND sa.student_id = 1          -- ganti dengan student_id
  AND cs.academic_year_id = 1    -- ganti dengan academic_year_id
  AND cs.semester = 1            -- ganti dengan semester
GROUP BY sa.student_id, cs.subject_id, cs.id, cs.academic_year_id, cs.semester, cs.curriculum_id;
```

### 2.2 Query 2: Konversi Nilai ke Predikat (Grade Scale)

```sql
-- ============================================================
-- STEP 2: Mapping nilai ke predikat dari grade_scales
-- ============================================================
SELECT 
  agg.student_id,
  agg.subject_id,
  agg.final_score,
  gs.grade_letter AS predicate,
  gs.grade_point,
  gs.description AS predicate_description
FROM (
  -- subquery STEP 1
  SELECT 
    sa.student_id,
    cs.subject_id,
    cs.curriculum_id,
    ROUND(
      SUM(sa.score * COALESCE(gwc.weight, a.weight, 0) / 100) 
      / NULLIF(SUM(COALESCE(gwc.weight, a.weight, 0) / 100), 0)
    , 2) AS final_score
  FROM `student_assessments` sa
  JOIN `assessments` a ON a.id = sa.assessment_id
  JOIN `curriculum_structures` cs ON cs.id = a.curriculum_structure_id
  LEFT JOIN `grade_weight_configs` gwc 
    ON gwc.assessment_type_id = a.assessment_type_id
    AND gwc.curriculum_id = cs.curriculum_id
    AND gwc.academic_year_id = cs.academic_year_id
  WHERE sa.deleted_at IS NULL
    AND a.deleted_at IS NULL
    AND sa.student_id = 1
    AND cs.academic_year_id = 1
    AND cs.semester = 1
  GROUP BY sa.student_id, cs.subject_id, cs.curriculum_id
) agg
LEFT JOIN `grade_scales` gs 
  ON gs.curriculum_id = agg.curriculum_id
  AND agg.final_score >= gs.min_score
  AND agg.final_score <= gs.max_score
  AND gs.deleted_at IS NULL
  AND gs.is_active = 1;
```

### 2.3 Query 3: Deskripsi Capaian (dari CP/KD)

```sql
-- ============================================================
-- STEP 3: Ambil deskripsi capaian tertinggi & terendah
-- untuk dijadikan narasi raport
-- ============================================================
SELECT 
  sc.student_id,
  clo.subject_id,
  clo.element,
  -- CP yang paling dikuasai
  SUBSTRING_INDEX(
    GROUP_CONCAT(clo.description ORDER BY sc.score DESC SEPARATOR '||'), 
    '||', 1
  ) AS highest_achievement,
  MAX(sc.score) AS highest_score,
  -- CP yang paling lemah
  SUBSTRING_INDEX(
    GROUP_CONCAT(clo.description ORDER BY sc.score ASC SEPARATOR '||'), 
    '||', 1
  ) AS lowest_achievement,
  MIN(sc.score) AS lowest_score
FROM `student_competencies` sc
JOIN `curriculum_learning_outcomes` clo ON clo.id = sc.curriculum_learning_outcome_id
WHERE sc.student_id = 1
  AND sc.academic_year_id = 1
  AND sc.semester = 1
  AND sc.deleted_at IS NULL
GROUP BY sc.student_id, clo.subject_id, clo.element;
```

### 2.4 Query 4: Insert ke `report_card_grades`

```sql
-- ============================================================
-- STEP 4: Simpan snapshot nilai ke report_card_grades
-- ============================================================
INSERT INTO `report_card_grades` 
(`school_id`, `generated_report_id`, `subject_id`, `curriculum_structure_id`,
 `knowledge_score`, `knowledge_predicate`, `knowledge_description`,
 `skill_score`, `skill_predicate`, `skill_description`,
 `final_score`, `final_predicate`, `notes`,
 `is_active`, `created_at`, `updated_at`, `created_by`) 
SELECT 
  1 AS school_id,
  1 AS generated_report_id,       -- ganti dengan id generated_reports
  agg.subject_id,
  agg.curriculum_structure_id,
  -- Pengetahuan
  agg.knowledge_score,
  gs_know.grade_letter,
  agg.knowledge_description,
  -- Keterampilan
  agg.skill_score,
  gs_skill.grade_letter,
  agg.skill_description,
  -- Final
  agg.final_score,
  gs_final.grade_letter,
  NULL,
  1, NOW(), NOW(), 1
FROM (
  SELECT 
    sa.student_id,
    cs.subject_id,
    cs.id AS curriculum_structure_id,
    cs.curriculum_id,
    -- Pengetahuan (element='pengetahuan')
    ROUND(AVG(CASE WHEN clo.element = 'pengetahuan' THEN sa.score END), 2) AS knowledge_score,
    ROUND(AVG(CASE WHEN clo.element = 'keterampilan' THEN sa.score END), 2) AS skill_score,
    ROUND(AVG(sa.score), 2) AS final_score,
    NULL AS knowledge_description,
    NULL AS skill_description
  FROM `student_assessments` sa
  JOIN `assessments` a ON a.id = sa.assessment_id
  JOIN `curriculum_structures` cs ON cs.id = a.curriculum_structure_id
  LEFT JOIN `curriculum_learning_outcomes` clo 
    ON clo.subject_id = cs.subject_id 
    AND clo.curriculum_phase_id = cs.curriculum_phase_id
  WHERE sa.deleted_at IS NULL
    AND sa.student_id = 1
    AND cs.academic_year_id = 1
    AND cs.semester = 1
  GROUP BY sa.student_id, cs.subject_id, cs.id, cs.curriculum_id
) agg
LEFT JOIN `grade_scales` gs_know 
  ON gs_know.curriculum_id = agg.curriculum_id
  AND agg.knowledge_score BETWEEN gs_know.min_score AND gs_know.max_score
LEFT JOIN `grade_scales` gs_skill 
  ON gs_skill.curriculum_id = agg.curriculum_id
  AND agg.skill_score BETWEEN gs_skill.min_score AND gs_skill.max_score
LEFT JOIN `grade_scales` gs_final 
  ON gs_final.curriculum_id = agg.curriculum_id
  AND agg.final_score BETWEEN gs_final.min_score AND gs_final.max_score;
```

### 2.5 Query 5: Insert ke `generated_reports`

```sql
-- ============================================================
-- STEP 5: Generate report_data JSON lengkap
-- ============================================================
INSERT INTO `generated_reports` 
(`school_id`, `student_id`, `period_id`, `report_card_template_id`, 
 `curriculum_id`, `report_data`, `generated_at`, 
 `is_active`, `created_at`, `updated_at`, `created_by`) 
VALUES (
  1,
  1,                                  -- student_id
  1,                                  -- period_id
  3,                                  -- report_card_template_id (Merdeka)
  3,                                  -- curriculum_id (Merdeka)
  JSON_OBJECT(
    'student', JSON_OBJECT(
      'id', 1,
      'name', 'Ahmad Fauzi',
      'nis', '2024001',
      'class_level', 'Kelas 7A',
      'academic_year', '2024/2025',
      'semester', 1
    ),
    'grades', (
      SELECT JSON_ARRAYAGG(
        JSON_OBJECT(
          'subject_id', rcg.subject_id,
          'subject_name', s.name,
          'knowledge_score', rcg.knowledge_score,
          'knowledge_predicate', rcg.knowledge_predicate,
          'skill_score', rcg.skill_score,
          'skill_predicate', rcg.skill_predicate,
          'final_score', rcg.final_score,
          'final_predicate', rcg.final_predicate,
          'description', rcg.knowledge_description
        )
      )
      FROM `report_card_grades` rcg
      JOIN `subjects` s ON s.id = rcg.subject_id
      WHERE rcg.generated_report_id = 1
    ),
    'attitude', JSON_OBJECT(
      'spiritual_predicate', 'A',
      'spiritual_description', 'Menghayati ajaran agama dengan baik',
      'social_predicate', 'A',
      'social_description', 'Menunjukkan sikap jujur dan disiplin'
    ),
    'attendance', JSON_OBJECT(
      'sick', 1,
      'permission', 2,
      'absent', 0
    ),
    'p5', JSON_ARRAY(
      JSON_OBJECT('project_name', 'Kewirausahaan', 'predicate', 'BSH', 'description', 'Baik dalam kolaborasi'),
      JSON_OBJECT('project_name', 'Gaya Hidup Berkelanjutan', 'predicate', 'SB', 'description', 'Sangat baik dalam inisiatif')
    ),
    'extracurricular', JSON_ARRAY(
      JSON_OBJECT('name', 'Pramuka', 'predicate', 'A', 'description', 'Aktif dan disiplin'),
      JSON_OBJECT('name', 'Futsal', 'predicate', 'B', 'description', 'Cukup aktif')
    ),
    'notes', JSON_OBJECT(
      'homeroom_note', 'Ahmad menunjukkan kemajuan baik, pertahankan prestasi.',
      'principal_note', 'Tetap semangat belajar.'
    ),
    'signature', JSON_OBJECT(
      'homeroom_teacher', 'Ibu Siti, S.Pd.',
      'principal', 'Bapak Dr. H. Ahmad, M.Pd.',
      'date', '2024-12-20'
    )
  ),
  NOW(),
  1, NOW(), NOW(), 1
);
```

### 2.6 Query 6: Query Lengkap Siap Pakai (Raport View)

```sql
-- ============================================================
-- QUERY FINAL: Ambil semua data untuk render raport
-- ============================================================
SELECT 
  gr.id AS report_id,
  gr.report_data,
  gr.pdf_path,
  st.name AS student_name,
  st.nis,
  c.name AS curriculum_name,
  rct.name AS template_name,
  rp.name AS period_name
FROM `generated_reports` gr
JOIN `students` st ON st.id = gr.student_id
JOIN `curriculums` c ON c.id = gr.curriculum_id
JOIN `report_card_templates` rct ON rct.id = gr.report_card_template_id
JOIN `report_periods` rp ON rp.id = gr.period_id
WHERE gr.deleted_at IS NULL
  AND gr.student_id = 1
  AND gr.period_id = 1;
```

### 2.7 Query Mastery Tracking (Khusus Montessori)

```sql
-- ============================================================
-- QUERY: Ambil mastery tracking untuk raport Montessori
-- ============================================================
SELECT 
  mt.student_id,
  s.name AS subject_name,
  clo.description AS outcome,
  co.description AS objective,
  mt.status,
  mt.mastered_at,
  mt.observation_notes
FROM `mastery_tracking` mt
JOIN `curriculum_objectives` co ON co.id = mt.curriculum_objective_id
JOIN `curriculum_learning_outcomes` clo ON clo.id = co.learning_outcome_id
JOIN `subjects` s ON s.id = clo.subject_id
WHERE mt.student_id = 1
  AND mt.academic_year_id = 1
  AND mt.deleted_at IS NULL
ORDER BY s.name, co.order;
```

---

## BAGIAN 3: Contoh `template_data` JSON yang Kaya

### 3.1 Template K13 (Raport Lengkap)

```json
{
  "meta": {
    "name": "Raport K13 SMP",
    "version": "2.0",
    "curriculum": "K13",
    "page": {
      "size": "A4",
      "orientation": "portrait",
      "margin": { "top": 20, "right": 20, "bottom": 20, "left": 25 }
    }
  },
  "style": {
    "font_family": "Times New Roman",
    "font_size": 11,
    "header_color": "#1F4E79",
    "border_color": "#333333",
    "table": {
      "header_bg": "#D9E2F3",
      "header_bold": true,
      "row_alternate": "#F2F2F2",
      "border_width": 1
    }
  },
  "header": {
    "show_logo": true,
    "logo_position": "left",
    "logo_path": "/assets/logo-sekolah.png",
    "show_photo": true,
    "photo_position": "right",
    "lines": [
      { "text": "PEMERINTAH PROVINSI JAWA BARAT", "font_size": 12, "bold": true, "align": "center" },
      { "text": "DINAS PENDIDIKAN", "font_size": 12, "bold": true, "align": "center" },
      { "text": "SMP NEGERI 1 BANDUNG", "font_size": 16, "bold": true, "align": "center" },
      { "text": "Jl. Merdeka No. 1, Bandung 40111 | Telp. (022) 1234567", "font_size": 9, "align": "center" }
    ],
    "divider": { "type": "double", "color": "#1F4E79" },
    "title": {
      "text": "LAPORAN HASIL BELAJAR SISWA",
      "font_size": 14,
      "bold": true,
      "align": "center",
      "spacing_after": 12
    }
  },
  "identity": {
    "layout": "two_column",
    "fields": [
      { "label": "Nama Siswa", "field_key": "student_name", "width": "60%" },
      { "label": "Kelas", "field_key": "class_level", "width": "40%" },
      { "label": "NIS", "field_key": "student_nis", "width": "60%" },
      { "label": "Semester", "field_key": "semester", "width": "40%" },
      { "label": "Tahun Ajaran", "field_key": "academic_year", "width": "60%" },
      { "label": "Fase", "field_key": "phase", "width": "40%" }
    ]
  },
  "sections": [
    {
      "key": "knowledge",
      "title": "A. PENGETAHUAN",
      "type": "table",
      "columns": [
        { "key": "no", "label": "No", "width": "5%", "align": "center" },
        { "key": "subject", "label": "Mata Pelajaran", "width": "25%" },
        { "key": "score", "label": "Nilai", "width": "10%", "align": "center" },
        { "key": "predicate", "label": "Predikat", "width": "10%", "align": "center" },
        { "key": "description", "label": "Deskripsi", "width": "50%" }
      ],
      "data_source": "grades",
      "filter": { "element": "pengetahuan" },
      "footer": { "show_average": true, "show_rank": true }
    },
    {
      "key": "skill",
      "title": "B. KETERAMPILAN",
      "type": "table",
      "columns": [
        { "key": "no", "label": "No", "width": "5%", "align": "center" },
        { "key": "subject", "label": "Mata Pelajaran", "width": "25%" },
        { "key": "score", "label": "Nilai", "width": "10%", "align": "center" },
        { "key": "predicate", "label": "Predikat", "width": "10%", "align": "center" },
        { "key": "description", "label": "Deskripsi", "width": "50%" }
      ],
      "data_source": "grades",
      "filter": { "element": "keterampilan" }
    },
    {
      "key": "spiritual",
      "title": "C. SIKAP SPIRITUAL",
      "type": "descriptive",
      "fields": [
        { "label": "Predikat", "field_key": "spiritual_predicate", "bold": true },
        { "label": "Deskripsi", "field_key": "spiritual_description" }
      ],
      "data_source": "attitude"
    },
    {
      "key": "social",
      "title": "D. SIKAP SOSIAL",
      "type": "descriptive",
      "fields": [
        { "label": "Predikat", "field_key": "social_predicate", "bold": true },
        { "label": "Deskripsi", "field_key": "social_description" }
      ],
      "data_source": "attitude"
    },
    {
      "key": "attendance",
      "title": "E. KEHADIRAN",
      "type": "inline",
      "fields": [
        { "label": "Sakit", "field_key": "sick", "suffix": "hari" },
        { "label": "Izin", "field_key": "permission", "suffix": "hari" },
        { "label": "Alpa", "field_key": "absent", "suffix": "hari" }
      ],
      "data_source": "attendance"
    },
    {
      "key": "extracurricular",
      "title": "F. EKSTRAKURIKULER",
      "type": "table",
      "columns": [
        { "key": "name", "label": "Kegiatan", "width": "30%" },
        { "key": "predicate", "label": "Predikat", "width": "15%", "align": "center" },
        { "key": "description", "label": "Keterangan", "width": "55%" }
      ],
      "data_source": "extracurricular"
    },
    {
      "key": "notes",
      "title": "G. CATATAN WALI KELAS",
      "type": "textarea",
      "field_key": "homeroom_note",
      "data_source": "notes",
      "box": true
    }
  ],
  "footer": {
    "show_date": true,
    "date_format": "d F Y",
    "city": "Bandung",
    "signatures": [
      {
        "role": "Orang Tua/Wali",
        "name_field": "parent_name",
        "position": "left",
        "show_signature_line": true
      },
      {
        "role": "Wali Kelas",
        "name_field": "homeroom_teacher",
        "position": "center",
        "show_signature_line": true
      },
      {
        "role": "Kepala Sekolah",
        "name_field": "principal",
        "position": "right",
        "show_signature_line": true,
        "show_stamp": true,
        "stamp_path": "/assets/stamp-sekolah.png"
      }
    ],
    "watermark": {
      "enabled": true,
      "text": "SMPN 1 BANDUNG",
      "opacity": 0.05,
      "rotation": -45,
      "font_size": 60
    }
  },
  "page_break": {
    "after_sections": ["notes"],
    "repeat_header": false
  }
}
```

### 3.2 Template Kurikulum Merdeka (dengan P5)

```json
{
  "meta": {
    "name": "Raport Kurikulum Merdeka",
    "version": "2.0",
    "curriculum": "MERDEKA",
    "page": {
      "size": "A4",
      "orientation": "portrait",
      "margin": { "top": 20, "right": 20, "bottom": 20, "left": 25 }
    }
  },
  "style": {
    "font_family": "Calibri",
    "font_size": 11,
    "header_color": "#0F6E3E",
    "border_color": "#444444",
    "table": {
      "header_bg": "#D6EFE0",
      "header_bold": true,
      "row_alternate": "#F5FBF7",
      "border_width": 1
    }
  },
  "header": {
    "show_logo": true,
    "logo_position": "left",
    "show_photo": true,
    "photo_position": "right",
    "lines": [
      { "text": "SEKOLAH PENGGERAK", "font_size": 11, "bold": true, "align": "center" },
      { "text": "SMP NEGERI 1 BANDUNG", "font_size": 16, "bold": true, "align": "center" },
      { "text": "Jl. Merdeka No. 1, Bandung 40111", "font_size": 9, "align": "center" }
    ],
    "divider": { "type": "single", "color": "#0F6E3E" },
    "title": {
      "text": "LAPORAN HASIL BELAJAR",
      "subtitle": "Kurikulum Merdeka",
      "font_size": 14,
      "bold": true,
      "align": "center",
      "spacing_after": 12
    }
  },
  "identity": {
    "layout": "two_column",
    "fields": [
      { "label": "Nama Siswa", "field_key": "student_name", "width": "60%" },
      { "label": "Kelas", "field_key": "class_level", "width": "40%" },
      { "label": "NIS", "field_key": "student_nis", "width": "60%" },
      { "label": "Fase", "field_key": "phase", "width": "40%" },
      { "label": "Tahun Ajaran", "field_key": "academic_year", "width": "60%" },
      { "label": "Semester", "field_key": "semester", "width": "40%" }
    ]
  },
  "sections": [
    {
      "key": "intracurricular",
      "title": "A. NILAI INTRAKURIKULER",
      "type": "table",
      "columns": [
        { "key": "no", "label": "No", "width": "5%", "align": "center" },
        { "key": "subject", "label": "Mata Pelajaran", "width": "25%" },
        { "key": "score", "label": "Nilai", "width": "10%", "align": "center" },
        { "key": "predicate", "label": "Capaian", "width": "15%", "align": "center" },
        { "key": "description", "label": "Deskripsi", "width": "45%" }
      ],
      "data_source": "grades",
      "descriptive": true
    },
    {
      "key": "p5",
      "title": "B. PROJEK PENGUATAN PROFIL PELAJAR PANCASILA",
      "type": "table",
      "columns": [
        { "key": "no", "label": "No", "width": "5%", "align": "center" },
        { "key": "project_name", "label": "Nama Projek", "width": "30%" },
        { "key": "dimensions", "label": "Dimensi", "width": "25%" },
        { "key": "predicate", "label": "Capaian", "width": "10%", "align": "center" },
        { "key": "description", "label": "Deskripsi", "width": "30%" }
      ],
      "data_source": "p5",
      "dimensions": [
        "Beriman, Bertakwa kepada Tuhan YME, dan Berakhlak Mulia",
        "Berkebinekaan Global",
        "Bergotong Royong",
        "Mandiri",
        "Bernalar Kritis",
        "Kreatif"
      ]
    },
    {
      "key": "attendance",
      "title": "C. KEHADIRAN",
      "type": "inline",
      "fields": [
        { "label": "Sakit", "field_key": "sick", "suffix": "hari" },
        { "label": "Izin", "field_key": "permission", "suffix": "hari" },
        { "label": "Alpa", "field_key": "absent", "suffix": "hari" }
      ],
      "data_source": "attendance"
    },
    {
      "key": "extracurricular",
      "title": "D. EKSTRAKURIKULER",
      "type": "table",
      "columns": [
        { "key": "name", "label": "Kegiatan", "width": "30%" },
        { "key": "predicate", "label": "Predikat", "width": "15%", "align": "center" },
        { "key": "description", "label": "Keterangan", "width": "55%" }
      ],
      "data_source": "extracurricular"
    },
    {
      "key": "notes",
      "title": "E. CATATAN WALI KELAS",
      "type": "textarea",
      "field_key": "homeroom_note",
      "data_source": "notes",
      "box": true
    }
  ],
  "footer": {
    "show_date": true,
    "date_format": "d F Y",
    "city": "Bandung",
    "signatures": [
      { "role": "Orang Tua/Wali", "position": "left", "show_signature_line": true },
      { "role": "Wali Kelas", "position": "center", "show_signature_line": true },
      { "role": "Kepala Sekolah", "position": "right", "show_signature_line": true, "show_stamp": true }
    ],
    "watermark": {
      "enabled": true,
      "text": "SPENSA BANDUNG",
      "opacity": 0.04,
      "rotation": -45,
      "font_size": 60
    }
  }
}
```

### 3.3 Template Cambridge (IGCSE)

```json
{
  "meta": {
    "name": "Cambridge IGCSE Report Card",
    "version": "1.0",
    "curriculum": "CAMBRIDGE",
    "page": {
      "size": "A4",
      "orientation": "portrait",
      "margin": { "top": 18, "right": 18, "bottom": 18, "left": 18 }
    }
  },
  "style": {
    "font_family": "Arial",
    "font_size": 10,
    "header_color": "#003366",
    "border_color": "#666666",
    "table": {
      "header_bg": "#E6EEF7",
      "header_bold": true,
      "row_alternate": "#F7FAFD",
      "border_width": 1
    }
  },
  "header": {
    "show_logo": true,
    "logo_position": "left",
    "lines": [
      { "text": "CAMBRIDGE INTERNATIONAL SCHOOL", "font_size": 14, "bold": true, "align": "center" },
      { "text": "Cambridge Assessment International Education", "font_size": 10, "align": "center" },
      { "text": "Jl. Pendidikan No. 10, Jakarta", "font_size": 9, "align": "center" }
    ],
    "divider": { "type": "double", "color": "#003366" },
    "title": {
      "text": "IGCSE REPORT CARD",
      "subtitle": "Academic Year 2024/2025",
      "font_size": 14,
      "bold": true,
      "align": "center"
    }
  },
  "identity": {
    "layout": "two_column",
    "fields": [
      { "label": "Student Name", "field_key": "student_name" },
      { "label": "Candidate Number", "field_key": "candidate_number" },
      { "label": "Centre Number", "field_key": "centre_number" },
      { "label": "Stage", "field_key": "stage" }
    ]
  },
  "sections": [
    {
      "key": "grades",
      "title": "SUBJECT GRADES",
      "type": "table",
      "columns": [
        { "key": "subject", "label": "Subject", "width": "22%" },
        { "key": "paper1", "label": "Paper 1", "width": "10%", "align": "center" },
        { "key": "paper2", "label": "Paper 2", "width": "10%", "align": "center" },
        { "key": "paper3", "label": "Paper 3", "width": "10%", "align": "center" },
        { "key": "coursework", "label": "Coursework", "width": "10%", "align": "center" },
        { "key": "total", "label": "Total", "width": "10%", "align": "center" },
        { "key": "grade", "label": "Grade", "width": "8%", "align": "center", "bold": true },
        { "key": "description", "label": "Comment", "width": "20%" }
      ],
      "data_source": "grades",
      "show_grade_boundaries": true,
      "grade_boundaries": [
        { "grade": "A*", "min": 90, "max": 100, "description": "Outstanding" },
        { "grade": "A", "min": 80, "max": 89, "description": "Excellent" },
        { "grade": "B", "min": 70, "max": 79, "description": "Very Good" },
        { "grade": "C", "min": 60, "max": 69, "description": "Good" },
        { "grade": "D", "min": 50, "max": 59, "description": "Satisfactory" },
        { "grade": "E", "min": 40, "max": 49, "description": "Pass" },
        { "grade": "U", "min": 0, "max": 39, "description": "Ungraded" }
      ]
    },
    {
      "key": "attendance",
      "title": "ATTENDANCE",
      "type": "inline",
      "fields": [
        { "label": "Present", "field_key": "present", "suffix": "days" },
        { "label": "Absent", "field_key": "absent", "suffix": "days" },
        { "label": "Late", "field_key": "late", "suffix": "times" }
      ],
      "data_source": "attendance"
    },
    {
      "key": "tutor_comment",
      "title": "TUTOR COMMENT",
      "type": "textarea",
      "field_key": "tutor_comment",
      "data_source": "notes",
      "box": true
    }
  ],
  "footer": {
    "show_date": true,
    "date_format": "d F Y",
    "city": "Jakarta",
    "signatures": [
      { "role": "Homeroom Teacher", "position": "left", "show_signature_line": true },
      { "role": "Principal", "position": "right", "show_signature_line": true, "show_stamp": true }
    ],
    "watermark": {
      "enabled": true,
      "text": "CAMBRIDGE",
      "opacity": 0.05,
      "rotation": -45,
      "font_size": 80
    }
  }
}
```

### 3.4 Template Montessori (Progress Report)

```json
{
  "meta": {
    "name": "Montessori Progress Report",
    "version": "1.0",
    "curriculum": "MONTESSORI",
    "page": {
      "size": "A4",
      "orientation": "portrait",
      "margin": { "top": 18, "right": 18, "bottom": 18, "left": 18 }
    }
  },
  "style": {
    "font_family": "Georgia",
    "font_size": 10.5,
    "header_color": "#7A5C3E",
    "border_color": "#B8A88A",
    "table": {
      "header_bg": "#F2EBDD",
      "header_bold": true,
      "row_alternate": "#FAF6EF",
      "border_width": 1
    }
  },
  "header": {
    "show_logo": true,
    "logo_position": "left",
    "show_photo": true,
    "photo_position": "right",
    "lines": [
      { "text": "MONTESSORI CHILDREN'S HOUSE", "font_size": 14, "bold": true, "align": "center" },
      { "text": "Nurturing the Natural Development of the Child", "font_size": 10, "italic": true, "align": "center" }
    ],
    "divider": { "type": "single", "color": "#7A5C3E" },
    "title": {
      "text": "PROGRESS REPORT",
      "font_size": 14,
      "bold": true,
      "align": "center"
    }
  },
  "identity": {
    "layout": "two_column",
    "fields": [
      { "label": "Child Name", "field_key": "child_name" },
      { "label": "Age Group", "field_key": "age_group" },
      { "label": "Guide", "field_key": "guide_name" },
      { "label": "Academic Year", "field_key": "academic_year" }
    ]
  },
  "sections": [
    {
      "key": "learning_plan",
      "title": "INDIVIDUAL LEARNING PLAN",
      "type": "table",
      "columns": [
        { "key": "area", "label": "Area", "width": "25%" },
        { "key": "goal", "label": "Goal", "width": "45%" },
        { "key": "progress", "label": "Progress", "width": "15%", "align": "center" },
        { "key": "notes", "label": "Notes", "width": "15%" }
      ],
      "data_source": "learning_plan"
    },
    {
      "key": "mastery",
      "title": "MASTERY TRACKING",
      "type": "table",
      "columns": [
        { "key": "area", "label": "Area", "width": "20%" },
        { "key": "objective", "label": "Objective", "width": "45%" },
        { "key": "status", "label": "Status", "width": "15%", "align": "center" },
        { "key": "mastered_at", "label": "Mastered", "width": "20%", "align": "center" }
      ],
      "data_source": "mastery",
      "status_legend": [
        { "key": "not_started", "label": "Not Started", "color": "#CCCCCC" },
        { "key": "in_progress", "label": "In Progress", "color": "#FFD966" },
        { "key": "mastered", "label": "Mastered", "color": "#93C47D" },
        { "key": "needs_review", "label": "Needs Review", "color": "#EA9999" }
      ]
    },
    {
      "key": "observation",
      "title": "OBSERVATION NOTES",
      "type": "textarea",
      "field_key": "observation_notes",
      "data_source": "observation",
      "box": true
    },
    {
      "key": "portfolio",
      "title": "PORTFOLIO",
      "type": "table",
      "columns": [
        { "key": "title", "label": "Title", "width": "30%" },
        { "key": "area", "label": "Area", "width": "20%" },
        { "key": "date", "label": "Date", "width": "15%", "align": "center" },
        { "key": "description", "label": "Description", "width": "35%" }
      ],
      "data_source": "portfolio"
    },
    {
      "key": "attendance",
      "title": "ATTENDANCE",
      "type": "inline",
      "fields": [
        { "label": "Present", "field_key": "present", "suffix": "days" },
        { "label": "Absent", "field_key": "absent", "suffix": "days" }
      ],
      "data_source": "attendance"
    }
  ],
  "footer": {
    "show_date": true,
    "date_format": "d F Y",
    "city": "Bandung",
    "signatures": [
      { "role": "Guide", "position": "left", "show_signature_line": true },
      { "role": "Principal", "position": "right", "show_signature_line": true },
      { "role": "Parent", "position": "center", "show_signature_line": true }
    ],
    "watermark": {
      "enabled": true,
      "text": "MONTESSORI",
      "opacity": 0.04,
      "rotation": -45,
      "font_size": 70
    }
  }
}
```

---

## BAGIAN 4: Ringkasan Alur Generate Raport

```
┌─────────────────────────────┐
│ 1. Input Nilai              │
│    student_assessments      │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│ 2. Agregasi Nilai           │
│    - Weighted avg per mapel │
│    - Konversi ke predikat   │
│    - Deskripsi dari CP/KD   │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│ 3. Snapshot Nilai           │
│    report_card_grades       │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│ 4. Generate JSON            │
│    generated_reports        │
│    - report_data (JSON)     │
│    - pdf_path               │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│ 5. Render PDF               │
│    report_card_templates    │
│    - template_data (JSON)   │
│    - layout, style, section │
└─────────────────────────────┘
```

---

## BAGIAN 5: Catatan Implementasi

| Hal | Catatan |
|-----|---------|
| **Weighted avg** | Jika `gwc.weight` NULL, fallback ke `a.weight`. Jika keduanya NULL, gunakan rata-rata sederhana. |
| **Grade scales** | Pastikan tidak ada overlap `min_score`/`max_score` antar baris dalam 1 kurikulum, agar mapping predikat deterministik. |
| **Deskripsi capaian** | Untuk K13/Merdeka: ambil CP/KD dengan skor tertinggi & terendah, gabungkan jadi narasi. Untuk Cambridge/Montessori: gunakan deskripsi kualitatif dari observasi. |
| **Snapshot** | `report_card_grades` penting agar nilai raport tidak berubah jika `student_assessments` diedit setelah generate. |
| **Template JSON** | Simpan di `report_card_templates.template_data`. Renderer (mis. DomPDF, Puppeteer, React-PDF) membaca JSON ini. |
| **Versioning** | Naikkan `version` di `report_card_templates` setiap kali template diubah, agar raport lama tidak terpengaruh. |
| **Watermark** | Untuk raport resmi, watermark membantu mencegah pemalsuan. |
| **Signature** | Simpan tanda tangan digital di `report_card_signatures.signature_path` (PNG transparan). |
| **Multi-bahasa** | Untuk Cambridge, template bisa punya `locale: "en"`. Untuk nasional, `locale: "id"`. |
| **P5 (Merdeka)** | Simpan dimensi P5 di `student_assessments.rubric_data` atau tabel terpisah `student_p5_assessments` jika perlu detail per dimensi. |
| **Mastery (Montessori)** | Gunakan `mastery_tracking` + `report_card_components` dengan `type: table` dan `data_source: mastery`. |

---

Kalau Anda mau, saya bisa lanjutkan dengan:

- **Implementasi renderer** (contoh kode PHP/Laravel atau Node.js) yang membaca `template_data` JSON → HTML → PDF.
- **Query rekap nilai per kelas** untuk wali kelas (ranking, rata-rata kelas, distribusi nilai).
- **API endpoint design** untuk generate raport massal (per kelas, per angkatan).


# Implementasi Renderer Raport: JSON → HTML → PDF (Laravel)

Berikut implementasi lengkap renderer raport di Laravel. Saya bagi menjadi beberapa layer: **Service**, **Blade View**, **Controller**, **Job**, dan **Command**.

---

## 1. Struktur Folder

```
app/
├── Services/
│   └── ReportCard/
│       ├── ReportCardRenderer.php        # Orchestrator
│       ├── DataCollector.php             # Ambil data dari DB
│       ├── TemplateParser.php            # Parse template_data JSON
│       ├── GradeCalculator.php           # Hitung nilai & predikat
│       └── PdfGenerator.php              # Render HTML → PDF
├── Models/
│   ├── GeneratedReport.php
│   ├── ReportCardTemplate.php
│   ├── StudentAssessment.php
│   ├── ReportCardGrade.php
│   └── ...
├── Http/Controllers/
│   └── ReportCardController.php
├── Jobs/
│   └── GenerateReportCardJob.php
└── Console/Commands/
    └── GenerateReportCardsCommand.php

resources/views/report_cards/
├── layouts/
│   └── base.blade.php
├── partials/
│   ├── header.blade.php
│   ├── identity.blade.php
│   ├── section_table.blade.php
│   ├── section_descriptive.blade.php
│   ├── section_inline.blade.php
│   ├── section_textarea.blade.php
│   └── footer.blade.php
└── render.blade.php
```

---

## 2. Install Dependencies

```bash
composer require barryvdh/laravel-dompdf
```

`config/app.php` (Laravel 10 ke bawah):
```php
'providers' => [
    // ...
    Barryvdh\DomPDF\ServiceProvider::class,
],
'aliases' => [
    'PDF' => Barryvdh\DomPDF\Facade\Pdf::class,
],
```

Publish config:
```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## 3. Model (Ringkas)

```php
<?php
// app/Models/GeneratedReport.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneratedReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id', 'student_id', 'period_id', 'report_card_template_id',
        'curriculum_id', 'pdf_path', 'report_data', 'generated_at',
        'downloaded_count', 'last_download_at', 'is_active',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'report_data' => 'array',
        'generated_at' => 'datetime',
        'last_download_at' => 'datetime',
    ];

    public function student()       { return $this->belongsTo(Student::class); }
    public function template()      { return $this->belongsTo(ReportCardTemplate::class, 'report_card_template_id'); }
    public function curriculum()    { return $this->belongsTo(Curriculum::class); }
    public function period()        { return $this->belongsTo(ReportPeriod::class, 'period_id'); }
    public function grades()        { return $this->hasMany(ReportCardGrade::class); }
    public function notes()         { return $this->hasMany(ReportCardNote::class); }
    public function signatures()    { return $this->hasMany(ReportCardSignature::class); }
}
```

```php
<?php
// app/Models/ReportCardTemplate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportCardTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id', 'name', 'code', 'template_data',
        'is_default', 'is_active', 'version',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'template_data' => 'array',
        'is_default'    => 'boolean',
        'is_active'     => 'boolean',
    ];

    public function curriculums()
    {
        return $this->belongsToMany(
            Curriculum::class,
            'report_card_template_curriculums',
            'report_card_template_id',
            'curriculum_id'
        )->withPivot('is_active')->withTimestamps();
    }
}
```

---

## 4. Service: `GradeCalculator`

```php
<?php
// app/Services/ReportCard/GradeCalculator.php

namespace App\Services\ReportCard;

use App\Models\Assessment;
use App\Models\GradeScale;
use App\Models\GradeWeightConfig;
use App\Models\ReportCardGrade;
use App\Models\StudentAssessment;
use Illuminate\Support\Facades\DB;

class GradeCalculator
{
    /**
     * Hitung nilai akhir per mapel untuk seorang siswa.
     * Return array of ['subject_id', 'curriculum_structure_id', 'final_score', ...]
     */
    public function calculateForStudent(int $studentId, int $academicYearId, int $semester, int $curriculumId): array
    {
        // 1. Ambil bobot default per assessment type
        $weights = GradeWeightConfig::where('curriculum_id', $curriculumId)
            ->where('academic_year_id', $academicYearId)
            ->where('deleted_at', null)
            ->pluck('weight', 'assessment_type_id')
            ->toArray();

        // 2. Ambil semua nilai siswa beserta info assessment & structure
        $rows = DB::table('student_assessments as sa')
            ->join('assessments as a', 'a.id', '=', 'sa.assessment_id')
            ->join('curriculum_structures as cs', 'cs.id', '=', 'a.curriculum_structure_id')
            ->leftJoin('curriculum_learning_outcomes as clo', function ($join) {
                $join->on('clo.subject_id', '=', 'cs.subject_id')
                     ->on('clo.curriculum_phase_id', '=', 'cs.curriculum_phase_id');
            })
            ->where('sa.student_id', $studentId)
            ->where('sa.deleted_at', null)
            ->where('a.deleted_at', null)
            ->where('cs.academic_year_id', $academicYearId)
            ->where('cs.semester', $semester)
            ->where('cs.curriculum_id', $curriculumId)
            ->select([
                'cs.subject_id',
                'cs.id as curriculum_structure_id',
                'cs.curriculum_id',
                'a.assessment_type_id',
                'a.weight as assessment_weight',
                'sa.score',
                'clo.element',
            ])
            ->get();

        // 3. Group per subject
        $grouped = $rows->groupBy('subject_id');
        $result = [];

        foreach ($grouped as $subjectId => $items) {
            $weightedSum = 0;
            $weightTotal = 0;
            $knowledgeScores = [];
            $skillScores = [];
            $allScores = [];

            foreach ($items as $item) {
                $w = $weights[$item->assessment_type_id]
                    ?? $item->assessment_weight
                    ?? 0;

                if ($w > 0) {
                    $weightedSum += $item->score * ($w / 100);
                    $weightTotal += ($w / 100);
                }

                $allScores[] = $item->score;

                if ($item->element === 'pengetahuan') $knowledgeScores[] = $item->score;
                if ($item->element === 'keterampilan') $skillScores[] = $item->score;
            }

            $finalScore = $weightTotal > 0
                ? round($weightedSum / $weightTotal, 2)
                : round(array_sum($allScores) / max(count($allScores), 1), 2);

            $result[] = [
                'subject_id'                => $subjectId,
                'curriculum_structure_id'   => $items->first()->curriculum_structure_id,
                'curriculum_id'             => $items->first()->curriculum_id,
                'knowledge_score'           => $knowledgeScores ? round(array_sum($knowledgeScores) / count($knowledgeScores), 2) : null,
                'skill_score'               => $skillScores ? round(array_sum($skillScores) / count($skillScores), 2) : null,
                'final_score'               => $finalScore,
            ];
        }

        return $result;
    }

    /**
     * Cari predikat dari grade_scales.
     */
    public function resolvePredicate(?float $score, int $curriculumId): array
    {
        if ($score === null) {
            return ['letter' => null, 'point' => null, 'description' => null];
        }

        $scale = GradeScale::where('curriculum_id', $curriculumId)
            ->where('deleted_at', null)
            ->where('is_active', 1)
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->orderBy('order')
            ->first();

        return $scale ? [
            'letter'      => $scale->grade_letter,
            'point'       => $scale->grade_point,
            'description' => $scale->description,
        ] : ['letter' => null, 'point' => null, 'description' => null];
    }

    /**
     * Ambil deskripsi capaian (dari CP/KD) tertinggi & terendah.
     */
    public function resolveDescription(int $studentId, int $subjectId, int $academicYearId, int $semester): ?string
    {
        $rows = DB::table('student_competencies as sc')
            ->join('curriculum_learning_outcomes as clo', 'clo.id', '=', 'sc.curriculum_learning_outcome_id')
            ->where('sc.student_id', $studentId)
            ->where('sc.academic_year_id', $academicYearId)
            ->where('sc.semester', $semester)
            ->where('clo.subject_id', $subjectId)
            ->where('sc.deleted_at', null)
            ->orderByDesc('sc.score')
            ->limit(2)
            ->pluck('clo.description')
            ->toArray();

        if (empty($rows)) return null;

        return 'Menguasai ' . implode('; ', $rows) . '.';
    }
}
```

---

## 5. Service: `DataCollector`

```php
<?php
// app/Services/ReportCard/DataCollector.php

namespace App\Services\ReportCard;

use App\Models\AttitudeAssessment;
use App\Models\GeneratedReport;
use App\Models\ReportCardGrade;
use App\Models\ReportPeriod;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentCurriculum;
use App\Models\StudentExtracurricular;
use Illuminate\Support\Facades\DB;

class DataCollector
{
    public function __construct(
        protected GradeCalculator $calculator,
    ) {}

    /**
     * Kumpulkan semua data untuk render raport.
     */
    public function collect(int $studentId, int $periodId): array
    {
        $period = ReportPeriod::findOrFail($periodId);
        $student = Student::with('classLevel')->findOrFail($studentId);

        $studentCurriculum = StudentCurriculum::where('student_id', $studentId)
            ->where('academic_year_id', $period->academic_year_id)
            ->where('deleted_at', null)
            ->firstOrFail();

        $curriculumId = $studentCurriculum->curriculum_id;
        $academicYearId = $period->academic_year_id;
        $semester = $period->semester;

        // 1. Hitung nilai per mapel
        $gradeRows = $this->calculator->calculateForStudent(
            $studentId, $academicYearId, $semester, $curriculumId
        );

        // 2. Ambil nama mapel & enrich dengan predikat + deskripsi
        $subjectIds = collect($gradeRows)->pluck('subject_id')->unique()->toArray();
        $subjects = DB::table('subjects')->whereIn('id', $subjectIds)->pluck('name', 'id');

        $grades = [];
        foreach ($gradeRows as $row) {
            $finalPred = $this->calculator->resolvePredicate($row['final_score'], $curriculumId);
            $knowPred  = $this->calculator->resolvePredicate($row['knowledge_score'], $curriculumId);
            $skillPred = $this->calculator->resolvePredicate($row['skill_score'], $curriculumId);

            $grades[] = [
                'subject_id'                => $row['subject_id'],
                'subject_name'              => $subjects[$row['subject_id']] ?? '-',
                'curriculum_structure_id'   => $row['curriculum_structure_id'],
                'knowledge_score'           => $row['knowledge_score'],
                'knowledge_predicate'       => $knowPred['letter'],
                'knowledge_description'     => $this->calculator->resolveDescription(
                    $studentId, $row['subject_id'], $academicYearId, $semester
                ),
                'skill_score'               => $row['skill_score'],
                'skill_predicate'           => $skillPred['letter'],
                'skill_description'         => null,
                'final_score'               => $row['final_score'],
                'final_predicate'           => $finalPred['letter'],
            ];
        }

        // 3. Sikap
        $attitudes = AttitudeAssessment::where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('semester', $semester)
            ->where('deleted_at', null)
            ->get()
            ->keyBy('aspect');

        $attitude = [
            'spiritual_predicate'   => $attitudes['spiritual']->predicate ?? null,
            'spiritual_description' => $attitudes['spiritual']->description ?? null,
            'social_predicate'      => $attitudes['sosial']->predicate ?? null,
            'social_description'    => $attitudes['sosial']->description ?? null,
        ];

        // 4. Kehadiran
        $att = StudentAttendance::where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('semester', $semester)
            ->where('deleted_at', null)
            ->first();

        $attendance = [
            'sick'       => $att->sick ?? 0,
            'permission' => $att->permission ?? 0,
            'absent'     => $att->absent ?? 0,
            'late'       => $att->late ?? 0,
            'present'    => $att->present ?? 0,
        ];

        // 5. Ekskul
        $extracurricular = StudentExtracurricular::with('extracurricular')
            ->where('student_id', $studentId)
            ->where('academic_year_id', $academicYearId)
            ->where('deleted_at', null)
            ->get()
            ->map(fn ($e) => [
                'name'        => $e->extracurricular->name ?? '-',
                'predicate'   => $e->predicate,
                'description' => $e->description,
            ])
            ->toArray();

        // 6. P5 (jika Merdeka)
        $p5 = [];
        if ($curriculumId === 3) {
            // ambil dari student_competencies yang element = 'sikap' atau tabel khusus P5
            $p5 = DB::table('student_competencies as sc')
                ->join('curriculum_learning_outcomes as clo', 'clo.id', '=', 'sc.curriculum_learning_outcome_id')
                ->where('sc.student_id', $studentId)
                ->where('sc.academic_year_id', $academicYearId)
                ->where('clo.code', 'like', 'MERDEKA-P5-%')
                ->where('sc.deleted_at', null)
                ->select('clo.description as project_name', 'sc.predicate', 'sc.description')
                ->get()
                ->map(fn ($r) => [
                    'project_name' => $r->project_name,
                    'predicate'    => $r->predicate,
                    'description'  => $r->description,
                ])
                ->toArray();
        }

        // 7. Mastery (jika Montessori)
        $mastery = [];
        if ($curriculumId === 5) {
            $mastery = DB::table('mastery_tracking as mt')
                ->join('curriculum_objectives as co', 'co.id', '=', 'mt.curriculum_objective_id')
                ->join('curriculum_learning_outcomes as clo', 'clo.id', '=', 'co.learning_outcome_id')
                ->join('subjects as s', 's.id', '=', 'clo.subject_id')
                ->where('mt.student_id', $studentId)
                ->where('mt.academic_year_id', $academicYearId)
                ->where('mt.deleted_at', null)
                ->select([
                    's.name as area',
                    'co.description as objective',
                    'mt.status',
                    'mt.mastered_at',
                    'mt.observation_notes as notes',
                ])
                ->get()
                ->toArray();
        }

        return [
            'student' => [
                'id'            => $student->id,
                'name'          => $student->name,
                'nis'           => $student->nis,
                'class_level'   => $student->classLevel->name ?? '-',
                'academic_year' => $period->academicYear->name ?? '-',
                'semester'      => $semester,
                'phase'         => '-',
            ],
            'curriculum_id'   => $curriculumId,
            'grades'          => $grades,
            'attitude'        => $attitude,
            'attendance'      => $attendance,
            'extracurricular' => $extracurricular,
            'p5'              => $p5,
            'mastery'         => $mastery,
            'notes'           => [
                'homeroom_note' => null,
                'principal_note' => null,
            ],
            'signature' => [
                'homeroom_teacher' => null,
                'principal'        => null,
                'date'             => now()->translatedFormat('d F Y'),
            ],
        ];
    }

    /**
     * Persist hasil agregasi ke report_card_grades.
     */
    public function persistGrades(int $generatedReportId, int $schoolId, array $grades, int $curriculumId): void
    {
        ReportCardGrade::where('generated_report_id', $generatedReportId)->forceDelete();

        foreach ($grades as $g) {
            ReportCardGrade::create([
                'school_id'                 => $schoolId,
                'generated_report_id'       => $generatedReportId,
                'subject_id'                => $g['subject_id'],
                'curriculum_structure_id'   => $g['curriculum_structure_id'],
                'knowledge_score'           => $g['knowledge_score'],
                'knowledge_predicate'       => $g['knowledge_predicate'],
                'knowledge_description'     => $g['knowledge_description'],
                'skill_score'               => $g['skill_score'],
                'skill_predicate'           => $g['skill_predicate'],
                'skill_description'         => $g['skill_description'],
                'final_score'               => $g['final_score'],
                'final_predicate'           => $g['final_predicate'],
                'is_active'                 => 1,
            ]);
        }
    }
}
```

---

## 6. Service: `TemplateParser`

```php
<?php
// app/Services/ReportCard/TemplateParser.php

namespace App\Services\ReportCard;

class TemplateParser
{
    /**
     * Normalisasi template_data JSON agar aman dipakai di Blade.
     */
    public function parse(array $templateData): array
    {
        $default = [
            'meta'    => ['name' => 'Report Card', 'version' => '1.0'],
            'style'   => [],
            'header'  => [],
            'identity'=> ['layout' => 'two_column', 'fields' => []],
            'sections'=> [],
            'footer'  => [],
        ];

        $data = array_replace_recursive($default, $templateData);

        // Pastikan style punya default
        $data['style'] = array_replace_recursive([
            'font_family'  => 'DejaVu Sans',
            'font_size'    => 10,
            'header_color' => '#000000',
            'border_color' => '#333333',
            'table' => [
                'header_bg'      => '#E6EEF7',
                'header_bold'    => true,
                'row_alternate'  => '#F7FAFD',
                'border_width'   => 1,
            ],
        ], $data['style']);

        // Normalisasi section
        foreach ($data['sections'] as &$section) {
            $section['key']    = $section['key']    ?? 'section_' . uniqid();
            $section['title']  = $section['title']  ?? '';
            $section['type']   = $section['type']   ?? 'table';
            $section['columns']= $section['columns']?? [];
            $section['fields'] = $section['fields'] ?? [];
        }
        unset($section);

        return $data;
    }

    /**
     * Ambil section berdasarkan tipe tertentu.
     */
    public function sectionsByType(array $template, string $type): array
    {
        return array_values(array_filter(
            $template['sections'] ?? [],
            fn ($s) => ($s['type'] ?? '') === $type
        ));
    }
}
```

---

## 7. Service: `PdfGenerator`

```php
<?php
// app/Services/ReportCard/PdfGenerator.php

namespace App\Services\ReportCard;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfGenerator
{
    public function __construct(
        protected TemplateParser $parser,
    ) {}

    /**
     * Render HTML dari Blade, lalu konversi ke PDF.
     * Return path file PDF yang tersimpan.
     */
    public function generate(array $templateData, array $reportData, string $filename): string
    {
        $template = $this->parser->parse($templateData);

        $html = view('report_cards.render', [
            'template' => $template,
            'data'     => $reportData,
        ])->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper(
                $template['meta']['page']['size']        ?? 'A4',
                $template['meta']['page']['orientation'] ?? 'portrait'
            )
            ->setOptions([
                'isRemoteEnabled'  => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont'      => $template['style']['font_family'] ?? 'DejaVu Sans',
                'dpi'              => 150,
            ]);

        $dir = storage_path('app/public/report_cards');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $path = $dir . '/' . $filename;
        $pdf->save($path);

        return 'report_cards/' . $filename;
    }

    /**
     * Return PDF sebagai download response.
     */
    public function stream(array $templateData, array $reportData, string $filename)
    {
        $template = $this->parser->parse($templateData);

        $html = view('report_cards.render', [
            'template' => $template,
            'data'     => $reportData,
        ])->render();

        return Pdf::loadHTML($html)
            ->setPaper(
                $template['meta']['page']['size']        ?? 'A4',
                $template['meta']['page']['orientation'] ?? 'portrait'
            )
            ->download($filename);
    }
}
```

---

## 8. Service: `ReportCardRenderer` (Orchestrator)

```php
<?php
// app/Services/ReportCard/ReportCardRenderer.php

namespace App\Services\ReportCard;

use App\Models\GeneratedReport;
use App\Models\ReportCardTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportCardRenderer
{
    public function __construct(
        protected DataCollector $collector,
        protected TemplateParser $parser,
        protected PdfGenerator $pdf,
    ) {}

    /**
     * Generate raport untuk 1 siswa & 1 periode.
     */
    public function generate(int $studentId, int $periodId, ?int $templateId = null): GeneratedReport
    {
        return DB::transaction(function () use ($studentId, $periodId, $templateId) {

            // 1. Kumpulkan data
            $data = $this->collector->collect($studentId, $periodId);

            // 2. Pilih template
            $template = $templateId
                ? ReportCardTemplate::findOrFail($templateId)
                : $this->pickTemplate($data['curriculum_id']);

            // 3. Simpan / update generated_reports
            $report = GeneratedReport::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'period_id'  => $periodId,
                ],
                [
                    'school_id'                 => $template->school_id,
                    'report_card_template_id'   => $template->id,
                    'curriculum_id'             => $data['curriculum_id'],
                    'report_data'               => $data,
                    'generated_at'              => now(),
                    'is_active'                 => 1,
                ]
            );

            // 4. Persist report_card_grades
            $this->collector->persistGrades(
                $report->id,
                $template->school_id,
                $data['grades'],
                $data['curriculum_id']
            );

            // 5. Render PDF
            $filename = sprintf(
                'raport_%d_%d_%s.pdf',
                $studentId,
                $periodId,
                now()->format('YmdHis')
            );

            $pdfPath = $this->pdf->generate(
                $template->template_data,
                $data,
                $filename
            );

            $report->update(['pdf_path' => $pdfPath]);

            Log::info('Report card generated', [
                'report_id'  => $report->id,
                'student_id' => $studentId,
                'period_id'  => $periodId,
                'pdf_path'   => $pdfPath,
            ]);

            return $report->fresh();
        });
    }

    /**
     * Generate massal per kelas / periode.
     */
    public function generateBulk(array $studentIds, int $periodId): array
    {
        $results = [];
        foreach ($studentIds as $studentId) {
            try {
                $results[] = $this->generate($studentId, $periodId);
            } catch (\Throwable $e) {
                Log::error("Gagal generate raport student_id={$studentId}", [
                    'error' => $e->getMessage(),
                ]);
                $results[] = null;
            }
        }
        return $results;
    }

    /**
     * Pilih template default berdasarkan kurikulum.
     */
    protected function pickTemplate(int $curriculumId): ReportCardTemplate
    {
        $template = ReportCardTemplate::query()
            ->whereHas('curriculums', fn ($q) => $q->where('curriculums.id', $curriculumId))
            ->where('is_active', 1)
            ->where('deleted_at', null)
            ->orderByDesc('is_default')
            ->orderByDesc('version')
            ->first();

        if (!$template) {
            throw new \RuntimeException("Template raport tidak ditemukan untuk curriculum_id={$curriculumId}");
        }

        return $template;
    }
}
```

---

## 9. Blade Views

### 9.1 `resources/views/report_cards/render.blade.php`

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $template['meta']['name'] ?? 'Report Card' }}</title>
    <style>
        @page {
            size: {{ $template['meta']['page']['size'] ?? 'A4' }}
                   {{ $template['meta']['page']['orientation'] ?? 'portrait' }};
            margin: {{ $template['meta']['page']['margin']['top'] ?? 20 }}mm
                     {{ $template['meta']['page']['margin']['right'] ?? 20 }}mm
                     {{ $template['meta']['page']['margin']['bottom'] ?? 20 }}mm
                     {{ $template['meta']['page']['margin']['left'] ?? 25 }}mm;
        }
        * { box-sizing: border-box; }
        body {
            font-family: "{{ $template['style']['font_family'] ?? 'DejaVu Sans' }}", sans-serif;
            font-size: {{ $template['style']['font_size'] ?? 10 }}pt;
            color: #222;
            line-height: 1.4;
        }
        .header-title { color: {{ $template['style']['header_color'] ?? '#000' }}; }
        .divider {
            border: none;
            border-top: {{ ($template['header']['divider']['type'] ?? 'single') === 'double' ? '3px double' : '1px solid' }}
                         {{ $template['header']['divider']['color'] ?? '#000' }};
            margin: 6px 0 12px 0;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.data th {
            background: {{ $template['style']['table']['header_bg'] ?? '#E6EEF7' }};
            border: {{ $template['style']['table']['border_width'] ?? 1 }}px solid {{ $template['style']['border_color'] ?? '#333' }};
            padding: 5px 6px;
            font-weight: {{ ($template['style']['table']['header_bold'] ?? true) ? 'bold' : 'normal' }};
            text-align: center;
        }
        table.data td {
            border: {{ $template['style']['table']['border_width'] ?? 1 }}px solid {{ $template['style']['border_color'] ?? '#333' }};
            padding: 4px 6px;
            vertical-align: top;
        }
        table.data tr:nth-child(even) td {
            background: {{ $template['style']['table']['row_alternate'] ?? '#F7FAFD' }};
        }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .bold { font-weight: bold; }
        .section-title {
            font-weight: bold;
            margin: 12px 0 6px 0;
            color: {{ $template['style']['header_color'] ?? '#000' }};
        }
        .identity-table { width: 100%; margin-bottom: 12px; }
        .identity-table td { padding: 2px 4px; vertical-align: top; }
        .box {
            border: 1px solid {{ $template['style']['border_color'] ?? '#333' }};
            padding: 6px 8px;
            min-height: 40px;
        }
        .footer-sign {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .footer-sign td {
            text-align: center;
            vertical-align: top;
            padding: 4px;
        }
        .sign-space { height: 50px; }
        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate({{ $template['footer']['watermark']['rotation'] ?? -45 }}deg);
            font-size: {{ $template['footer']['watermark']['font_size'] ?? 60 }}px;
            color: #000;
            opacity: {{ $template['footer']['watermark']['opacity'] ?? 0.05 }};
            z-index: -1;
            white-space: nowrap;
        }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

@if(!empty($template['footer']['watermark']['enabled']) && $template['footer']['watermark']['enabled'])
    <div class="watermark">{{ $template['footer']['watermark']['text'] ?? '' }}</div>
@endif

@include('report_cards.partials.header', ['template' => $template, 'data' => $data])
@include('report_cards.partials.identity', ['template' => $template, 'data' => $data])

@foreach($template['sections'] as $section)
    @php
        $sectionData = $data[$section['data_source'] ?? $section['key']] ?? [];
    @endphp
    @switch($section['type'])
        @case('table')
            @include('report_cards.partials.section_table', [
                'section' => $section, 'rows' => $sectionData, 'data' => $data
            ])
            @break
        @case('descriptive')
            @include('report_cards.partials.section_descriptive', [
                'section' => $section, 'row' => $sectionData
            ])
            @break
        @case('inline')
            @include('report_cards.partials.section_inline', [
                'section' => $section, 'row' => $sectionData
            ])
            @break
        @case('textarea')
            @include('report_cards.partials.section_textarea', [
                'section' => $section, 'row' => $sectionData
            ])
            @break
    @endswitch
@endforeach

@include('report_cards.partials.footer', ['template' => $template, 'data' => $data])

</body>
</html>
```

### 9.2 `resources/views/report_cards/partials/header.blade.php`

```blade
<div style="text-align:center;">
    @if(!empty($template['header']['show_logo']))
        <img src="{{ public_path('assets/logo-sekolah.png') }}"
             style="height:80px; float:left;" alt="Logo">
    @endif

    @if(!empty($template['header']['show_photo']))
        <img src="{{ public_path('assets/photo-placeholder.png') }}"
             style="height:80px; float:right;" alt="Photo">
    @endif

    @foreach(($template['header']['lines'] ?? []) as $line)
        <div style="
            font-size: {{ $line['font_size'] ?? 11 }}px;
            font-weight: {{ !empty($line['bold']) ? 'bold' : 'normal' }};
            font-style: {{ !empty($line['italic']) ? 'italic' : 'normal' }};
            text-align: {{ $line['align'] ?? 'center' }};
            color: {{ $template['style']['header_color'] ?? '#000' }};
        ">{{ $line['text'] }}</div>
    @endforeach
</div>

<hr class="divider">

@if(!empty($template['header']['title']['text']))
    <div class="header-title" style="
        font-size: {{ $template['header']['title']['font_size'] ?? 14 }}px;
        font-weight: {{ !empty($template['header']['title']['bold']) ? 'bold' : 'normal' }};
        text-align: {{ $template['header']['title']['align'] ?? 'center' }};
        margin-bottom: {{ $template['header']['title']['spacing_after'] ?? 8 }}px;
    ">
        {{ $template['header']['title']['text'] }}
        @if(!empty($template['header']['title']['subtitle']))
            <div style="font-size:11px; font-weight:normal;">
                {{ $template['header']['title']['subtitle'] }}
            </div>
        @endif
    </div>
@endif
```

### 9.3 `resources/views/report_cards/partials/identity.blade.php`

```blade
@php
    $identity = $template['identity'] ?? [];
    $fields   = $identity['fields'] ?? [];
@endphp

@if(!empty($fields))
<table class="identity-table">
    @foreach(array_chunk($fields, 2) as $pair)
        <tr>
            @foreach($pair as $field)
                @php
                    $key   = $field['field_key'] ?? null;
                    $value = data_get($data, $key);
                    $label = $field['label'] ?? $key;
                    $width = $field['width'] ?? '50%';
                @endphp
                <td style="width: {{ $width }};">
                    <strong>{{ $label }}</strong> : {{ $value }}
                </td>
            @endforeach
            @if(count($pair) === 1)
                <td></td>
            @endif
        </tr>
    @endforeach
</table>
@endif
```

### 9.4 `resources/views/report_cards/partials/section_table.blade.php`

```blade
<div class="section-title">{{ $section['title'] }}</div>

<table class="data">
    <thead>
        <tr>
            @foreach($section['columns'] as $col)
                <th style="width: {{ $col['width'] ?? 'auto' }}; text-align: {{ $col['align'] ?? 'left' }};">
                    {{ $col['label'] }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($rows as $i => $row)
            <tr>
                @foreach($section['columns'] as $col)
                    @php
                        $key   = $col['key'];
                        $value = $key === 'no' ? ($i + 1) : data_get($row, $key);
                    @endphp
                    <td style="text-align: {{ $col['align'] ?? 'left' }};"
                        class="{{ !empty($col['bold']) ? 'bold' : '' }}">
                        {{ $value ?? '-' }}
                    </td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($section['columns']) }}" class="text-center">
                    Tidak ada data
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
```

### 9.5 `resources/views/report_cards/partials/section_descriptive.blade.php`

```blade
<div class="section-title">{{ $section['title'] }}</div>

<table class="data">
    <tbody>
        @foreach($section['fields'] as $field)
            @php
                $key   = $field['field_key'];
                $value = data_get($row, $key);
            @endphp
            <tr>
                <td style="width: 25%;" class="{{ !empty($field['bold']) ? 'bold' : '' }}">
                    {{ $field['label'] }}
                </td>
                <td>{{ $value ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
```

### 9.6 `resources/views/report_cards/partials/section_inline.blade.php`

```blade
<div class="section-title">{{ $section['title'] }}</div>

<table class="data">
    <tr>
        @foreach($section['fields'] as $field)
            @php
                $key    = $field['field_key'];
                $value  = data_get($row, $key);
                $suffix = $field['suffix'] ?? '';
            @endphp
            <td style="text-align:center;">
                <strong>{{ $field['label'] }}</strong><br>
                {{ $value ?? 0 }} {{ $suffix }}
            </td>
        @endforeach
    </tr>
</table>
```

### 9.7 `resources/views/report_cards/partials/section_textarea.blade.php`

```blade
<div class="section-title">{{ $section['title'] }}</div>

@php
    $key   = $section['field_key'];
    $value = data_get($row, $key);
@endphp

<div class="box">{{ $value ?? '-' }}</div>
```

### 9.8 `resources/views/report_cards/partials/footer.blade.php`

```blade
@php
    $footer      = $template['footer'] ?? [];
    $signatures  = $footer['signatures'] ?? [];
    $city        = $footer['city'] ?? '';
    $date        = $footer['show_date'] ?? true
        ? ($data['signature']['date'] ?? now()->translatedFormat('d F Y'))
        : '';
@endphp

@if($city || $date)
    <div style="text-align:right; margin-top:16px;">
        {{ $city }}{{ $city && $date ? ', ' : '' }}{{ $date }}
    </div>
@endif

@if(!empty($signatures))
<table class="footer-sign">
    <tr>
        @foreach($signatures as $sig)
            @php
                $name = data_get($data, 'signature.' . ($sig['name_field'] ?? ''));
            @endphp
            <td style="width: {{ 100 / count($signatures) }}%;">
                <div>{{ $sig['role'] }}</div>
                <div class="sign-space"></div>
                <div class="bold">{{ $name ?? '________________' }}</div>
            </td>
        @endforeach
    </tr>
</table>
@endif
```

---

## 10. Controller

```php
<?php
// app/Http/Controllers/ReportCardController.php

namespace App\Http\Controllers;

use App\Models\GeneratedReport;
use App\Models\Student;
use App\Services\ReportCard\ReportCardRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportCardController extends Controller
{
    public function __construct(
        protected ReportCardRenderer $renderer,
    ) {}

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'student_id'  => 'required|exists:students,id',
            'period_id'   => 'required|exists:report_periods,id',
            'template_id' => 'nullable|exists:report_card_templates,id',
        ]);

        $report = $this->renderer->generate(
            $validated['student_id'],
            $validated['period_id'],
            $validated['template_id'] ?? null,
        );

        return response()->json([
            'success'   => true,
            'report_id' => $report->id,
            'pdf_url'   => Storage::disk('public')->url($report->pdf_path),
        ]);
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'class_level_id' => 'required|exists:class_levels,id',
            'period_id'      => 'required|exists:report_periods,id',
        ]);

        $studentIds = Student::where('class_level_id', $validated['class_level_id'])
            ->where('deleted_at', null)
            ->pluck('id')
            ->toArray();

        $reports = $this->renderer->generateBulk($studentIds, $validated['period_id']);

        return response()->json([
            'success' => true,
            'total'   => count($reports),
            'failed'  => count(array_filter($reports, fn ($r) => $r === null)),
        ]);
    }

    public function download(GeneratedReport $report)
    {
        abort_unless($report->pdf_path, 404);

        $report->increment('downloaded_count');
        $report->update(['last_download_at' => now()]);

        return Storage::disk('public')->download($report->pdf_path);
    }

    public function preview(GeneratedReport $report)
    {
        abort_unless($report->pdf_path, 404);
        return Storage::disk('public')->response($report->pdf_path, null, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline',
        ]);
    }
}
```

### Route (`routes/web.php` atau `routes/api.php`)

```php
use App\Http\Controllers\ReportCardController;

Route::middleware('auth')->group(function () {
    Route::post('/report-cards/generate',        [ReportCardController::class, 'generate']);
    Route::post('/report-cards/bulk',            [ReportCardController::class, 'bulk']);
    Route::get('/report-cards/{report}/download',[ReportCardController::class, 'download']);
    Route::get('/report-cards/{report}/preview', [ReportCardController::class, 'preview']);
});
```

---

## 11. Job & Command (Generate Massal)

### 11.1 Job

```php
<?php
// app/Jobs/GenerateReportCardJob.php

namespace App\Jobs;

use App\Services\ReportCard\ReportCardRenderer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateReportCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public int $tries   = 3;

    public function __construct(
        public int $studentId,
        public int $periodId,
        public ?int $templateId = null,
    ) {}

    public function handle(ReportCardRenderer $renderer): void
    {
        $renderer->generate($this->studentId, $this->periodId, $this->templateId);
    }
}
```

### 11.2 Command

```php
<?php
// app/Console/Commands/GenerateReportCardsCommand.php

namespace App\Console\Commands;

use App\Jobs\GenerateReportCardJob;
use App\Models\ReportPeriod;
use App\Models\Student;
use Illuminate\Console\Command;

class GenerateReportCardsCommand extends Command
{
    protected $signature = 'report:generate
        {--period= : ID periode raport}
        {--class=  : ID kelas (opsional)}
        {--template= : ID template (opsional)}
        {--sync : Jalankan sinkron tanpa queue}';

    protected $description = 'Generate raport massal';

    public function handle(): int
    {
        $periodId   = (int) $this->option('period');
        $classLevel = $this->option('class');
        $templateId = $this->option('template') ? (int) $this->option('template') : null;

        if (!$periodId) {
            $this->error('--period wajib diisi.');
            return self::FAILURE;
        }

        ReportPeriod::findOrFail($periodId);

        $query = Student::where('deleted_at', null);
        if ($classLevel) {
            $query->where('class_level_id', (int) $classLevel);
        }

        $students = $query->pluck('id');
        $bar = $this->output->createProgressBar($students->count());
        $bar->start();

        foreach ($students as $studentId) {
            if ($this->option('sync')) {
                (new GenerateReportCardJob($studentId, $periodId, $templateId))
                    ->handle(app(\App\Services\ReportCard\ReportCardRenderer::class));
            } else {
                GenerateReportCardJob::dispatch($studentId, $periodId, $templateId);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Selesai. Total: {$students->count()} siswa.");

        return self::SUCCESS;
    }
}
```

Jalankan:

```bash
php artisan report:generate --period=1 --class=7
php artisan report:generate --period=1 --sync
```

---

## 12. Contoh Alur Lengkap

```php
// Di controller / tinker
$renderer = app(\App\Services\ReportCard\ReportCardRenderer::class);

$report = $renderer->generate(
    studentId: 1,
    periodId: 1,
    templateId: 3, // Merdeka
);

// Output
echo $report->pdf_path;         // report_cards/raport_1_1_20241220.pdf
echo $report->report_data['student']['name']; // Ahmad Fauzi
```

---

## 13. Catatan Penting

| Hal | Catatan |
|-----|---------|
| **Font** | DomPDF butuh font yang support Unicode. Untuk nama dengan karakter khusus, pakai `DejaVu Sans` (default). |
| **Logo/foto** | DomPDF butuh **absolute path**. Gunakan `public_path()` atau `storage_path()`, jangan `asset()`. |
| **CSS** | DomPDF support CSS 2.1, tidak support Flexbox/Grid. Gunakan tabel atau float. |
| **Watermark** | CSS `position: fixed` + `transform` didukung terbatas. Untuk watermark lebih baik pakai `dompdf->getCanvas()->page_text()` atau library PDF lain. |
| **Page break** | Gunakan `page-break-after: always` atau `page-break-inside: avoid` untuk kontrol halaman. |
| **Cache** | Cache template & grade scales agar tidak query berulang: `Cache::remember("template_{$id}", 3600, fn() => ...)`. |
| **Queue** | Untuk generate massal, selalu pakai queue. Batasi `tries=3` + `timeout=300` agar tidak menggantung. |
| **Storage** | Set `FILESYSTEM_DISK=public` dan `php artisan storage:link` agar PDF bisa diakses via URL. |
| **Preview** | Untuk preview HTML (tanpa PDF), tambahkan method `previewHtml()` di controller yang return `view('report_cards.render', ...)`. |
| **Digital signature** | Untuk TTD digital, gunakan library seperti `setasign/fpdi` + `setasign/fpdf` untuk overlay PNG ke PDF. |
| **Testing** | Buat test dengan `Storage::fake('public')` + assert file ada. |

---

## 14. Bonus: Helper untuk Ambil Field dengan Dot Notation

Blade di atas pakai `data_get($data, $key)`. Kalau `$key` di template JSON berupa `student.name`, ini otomatis resolve. Jadi di `report_card_components`, `field_key` bisa diisi path apapun:

```json
{ "field_key": "student.name", "label": "Nama" }
{ "field_key": "attendance.sick", "label": "Sakit" }
{ "field_key": "grades.0.final_score", "label": "Nilai Pertama" }
```

---

Kalau Anda mau, saya bisa lanjutkan dengan:

- **Unit test** untuk `GradeCalculator` & `ReportCardRenderer`.
- **Preview HTML** endpoint (tanpa PDF) untuk debugging template.
- **Digital signature overlay** dengan FPDI (tanda tangan PNG di atas PDF).
- **Frontend React/Vue** untuk preview raport real-time saat template JSON diubah.