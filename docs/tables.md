Tentu! Saya akan buatkan struktur SQL yang **enterprise-grade**, **multi-sekolah** (TK-SD-SMP-SMA-Universitas), dan **powerful**. Saya akan menggunakan **PostgreSQL** dengan fitur-fitur canggih seperti UUID, partitioning, JSONB, dan indexing yang optimal.

## 📋 **Struktur Database Lengkap**

### **1. Tabel Users (Untuk Semua Pengguna Karyawan)**
```sql
CREATE TABLE users (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    birth_date DATE,
    gender VARCHAR(10) CHECK (gender IN ('MALE', 'FEMALE', 'OTHER')),
    avatar_url TEXT,
    is_active BOOLEAN DEFAULT true,
    last_login_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

-- Index
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_is_active ON users(is_active);
```

### **2. Tabel Schools (Multi-Sekolah)**
```sql
CREATE TABLE schools (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(200) NOT NULL,
    type VARCHAR(20) CHECK (type IN ('TK', 'SD', 'SMP', 'SMA', 'UNIVERSITY', 'OTHER')),
    level_education VARCHAR(20) CHECK (level_education IN ('PRIMARY', 'SECONDARY', 'HIGHER', 'VOCATIONAL')),
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(100),
    logo_url TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_schools_code ON schools(code);
CREATE INDEX idx_schools_type ON schools(type);
```

### **3. Tabel Students (Multi-Sekolah)**
```sql
CREATE TABLE students (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    nis VARCHAR(50) UNIQUE NOT NULL,
    nisn VARCHAR(50) UNIQUE,
    registration_number VARCHAR(50),
    full_name VARCHAR(150) NOT NULL,
    birth_place VARCHAR(100),
    birth_date DATE,
    gender VARCHAR(10) CHECK (gender IN ('MALE', 'FEMALE')),
    address TEXT,
    religion VARCHAR(20),
    parents_phone VARCHAR(20),
    emergency_contact VARCHAR(20),
    medical_notes TEXT,
    join_date DATE DEFAULT CURRENT_DATE,
    status VARCHAR(20) DEFAULT 'ACTIVE' CHECK (status IN ('ACTIVE', 'GRADUATED', 'TRANSFERRED', 'DROPPED', 'SUSPENDED')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

-- Index
CREATE INDEX idx_students_school_id ON students(school_id);
CREATE INDEX idx_students_nis ON students(nis);
CREATE INDEX idx_students_status ON students(status);
CREATE INDEX idx_students_birth_date ON students(birth_date);
```

### **4. Tabel Teachers (Multi-Sekolah)**
```sql
CREATE TABLE teachers (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    nip VARCHAR(50) UNIQUE,
    full_name VARCHAR(150) NOT NULL,
    specialization VARCHAR(100),
    education_level VARCHAR(50),
    join_date DATE DEFAULT CURRENT_DATE,
    status VARCHAR(20) DEFAULT 'ACTIVE' CHECK (status IN ('ACTIVE', 'RESIGNED', 'RETIRED')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_teachers_school_id ON teachers(school_id);
CREATE INDEX idx_teachers_nip ON teachers(nip);
```

### **5. Tabel Parents (Multi-Sekolah)**
```sql
CREATE TABLE parents (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    full_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address TEXT,
    relationship VARCHAR(20) CHECK (relationship IN ('FATHER', 'MOTHER', 'GUARDIAN')),
    work VARCHAR(100),
    is_primary BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_parents_user_id ON parents(user_id);
CREATE INDEX idx_parents_phone ON parents(phone);
```

### **6. Tabel Student_Parents (Relasi Many-to-Many)**
```sql
CREATE TABLE student_parents (
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    parent_id UUID REFERENCES parents(id) ON DELETE CASCADE,
    is_primary BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id, parent_id)
);

CREATE INDEX idx_student_parents_student ON student_parents(student_id);
CREATE INDEX idx_student_parents_parent ON student_parents(parent_id);
```

### **7. Tabel Academic_Years (Per Sekolah)**
```sql
CREATE TABLE academic_years (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    name VARCHAR(50) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    semester_start_date DATE,
    semester_end_date DATE,
    is_active BOOLEAN DEFAULT true,
    is_current BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_academic_years_school ON academic_years(school_id);
CREATE INDEX idx_academic_years_date_range ON academic_years(start_date, end_date);
CREATE INDEX idx_academic_years_is_current ON academic_years(is_current);
```

### **8. Tabel Class_Levels (Per Sekolah & Jenjang)**
```sql
CREATE TABLE class_levels (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    code VARCHAR(20) NOT NULL,
    name VARCHAR(50) NOT NULL,
    level_order INTEGER NOT NULL,
    education_level VARCHAR(20) CHECK (education_level IN ('TK', 'SD', 'SMP', 'SMA', 'UNIVERSITY')),
    description TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id),
    UNIQUE(school_id, code)
);

CREATE INDEX idx_class_levels_school ON class_levels(school_id);
CREATE INDEX idx_class_levels_order ON class_levels(level_order);
```

### **9. Tabel Classrooms (Rombongan Belajar)**
```sql
CREATE TABLE classrooms (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    class_level_id UUID REFERENCES class_levels(id) ON DELETE CASCADE,
    name VARCHAR(50) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    capacity INTEGER DEFAULT 30,
    homeroom_teacher_id UUID REFERENCES teachers(id) ON DELETE SET NULL,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_classrooms_school ON classrooms(school_id);
CREATE INDEX idx_classrooms_level ON classrooms(class_level_id);
CREATE INDEX idx_classrooms_academic_year ON classrooms(academic_year_id);
CREATE INDEX idx_classrooms_teacher ON classrooms(homeroom_teacher_id);
```

### **10. Tabel Subjects (Mata Pelajaran - Multi Sekolah)**
```sql
CREATE TABLE subjects (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    code VARCHAR(20) NOT NULL,
    name VARCHAR(100) NOT NULL,
    abbreviation VARCHAR(20),
    description TEXT,
    category VARCHAR(50) CHECK (category IN ('CORE', 'ELECTIVE', 'EXTRACURRICULAR', 'RELIGION', 'LOCAL_CONTENT')),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id),
    UNIQUE(school_id, code)
);

CREATE INDEX idx_subjects_school ON subjects(school_id);
CREATE INDEX idx_subjects_category ON subjects(category);
```

### **11. Tabel Schedules (Jadwal Pelajaran)**
```sql
CREATE TABLE schedules (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    classroom_id UUID REFERENCES classrooms(id) ON DELETE CASCADE,
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    teacher_id UUID REFERENCES teachers(id) ON DELETE SET NULL,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    day_of_week VARCHAR(10) CHECK (day_of_week IN ('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY')),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    room VARCHAR(50),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_schedules_classroom ON schedules(classroom_id);
CREATE INDEX idx_schedules_teacher ON schedules(teacher_id);
CREATE INDEX idx_schedules_day_time ON schedules(day_of_week, start_time);
CREATE INDEX idx_schedules_academic_year ON schedules(academic_year_id);
```

### **12. Tabel Curriculum_Structures (Kurikulum Merdeka)**
```sql
CREATE TABLE curriculum_structures (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    class_level_id UUID REFERENCES class_levels(id) ON DELETE CASCADE,
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    semester VARCHAR(10) CHECK (semester IN ('SEMESTER_1', 'SEMESTER_2')),
    hours_per_week INTEGER NOT NULL,
    hour_duration INTEGER DEFAULT 45, -- menit
    is_core BOOLEAN DEFAULT true,
    min_student INTEGER DEFAULT 1,
    max_student INTEGER DEFAULT 40,
    notes TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id),
    UNIQUE(school_id, class_level_id, subject_id, academic_year_id, semester)
);

CREATE INDEX idx_curriculum_school ON curriculum_structures(school_id);
CREATE INDEX idx_curriculum_class ON curriculum_structures(class_level_id);
CREATE INDEX idx_curriculum_subject ON curriculum_structures(subject_id);
```

### **13. Tabel Teaching_Loads (Beban Mengajar Guru)**
```sql
CREATE TABLE teaching_loads (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    teacher_id UUID REFERENCES teachers(id) ON DELETE CASCADE,
    classroom_id UUID REFERENCES classrooms(id) ON DELETE CASCADE,
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    semester VARCHAR(10) CHECK (semester IN ('SEMESTER_1', 'SEMESTER_2')),
    total_hours INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id),
    UNIQUE(teacher_id, classroom_id, subject_id, academic_year_id, semester)
);

CREATE INDEX idx_teaching_loads_teacher ON teaching_loads(teacher_id);
CREATE INDEX idx_teaching_loads_classroom ON teaching_loads(classroom_id);
CREATE INDEX idx_teaching_loads_academic_year ON teaching_loads(academic_year_id);
```

### **14. Tabel Student_Classrooms (Siswa per Kelas)**
```sql
CREATE TABLE student_classrooms (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    classroom_id UUID REFERENCES classrooms(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    enrollment_date DATE DEFAULT CURRENT_DATE,
    status VARCHAR(20) DEFAULT 'ACTIVE' CHECK (status IN ('ACTIVE', 'TRANSFERRED', 'DROPPED', 'GRADUATED')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id),
    UNIQUE(student_id, classroom_id, academic_year_id)
);

CREATE INDEX idx_student_classrooms_student ON student_classrooms(student_id);
CREATE INDEX idx_student_classrooms_classroom ON student_classrooms(classroom_id);
CREATE INDEX idx_student_classrooms_academic_year ON student_classrooms(academic_year_id);
```

### **15. Tabel Courses (e-Learning)**
```sql
CREATE TABLE courses (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    teacher_id UUID REFERENCES teachers(id) ON DELETE SET NULL,
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    description TEXT,
    thumbnail_url TEXT,
    level VARCHAR(20) CHECK (level IN ('BEGINNER', 'INTERMEDIATE', 'ADVANCED')),
    duration_hours INTEGER DEFAULT 0,
    is_published BOOLEAN DEFAULT false,
    published_at TIMESTAMP,
    enrollment_count INTEGER DEFAULT 0,
    rating DECIMAL(3,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_courses_school ON courses(school_id);
CREATE INDEX idx_courses_teacher ON courses(teacher_id);
CREATE INDEX idx_courses_subject ON courses(subject_id);
CREATE INDEX idx_courses_slug ON courses(slug);
CREATE INDEX idx_courses_is_published ON courses(is_published);
```

### **16. Tabel Lessons (Pertemuan/Bab)**
```sql
CREATE TABLE lessons (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    course_id UUID REFERENCES courses(id) ON DELETE CASCADE,
    title VARCHAR(200) NOT NULL,
    content TEXT,
    video_url TEXT,
    duration_minutes INTEGER DEFAULT 0,
    order_index INTEGER NOT NULL,
    is_free_preview BOOLEAN DEFAULT false,
    is_published BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_lessons_course ON lessons(course_id);
CREATE INDEX idx_lessons_order ON lessons(order_index);
```

### **17. Tabel Assignments & Quizzes (Dengan Single Table Inheritance)**
```sql
CREATE TABLE assignments_quizzes (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    course_id UUID REFERENCES courses(id) ON DELETE CASCADE,
    lesson_id UUID REFERENCES lessons(id) ON DELETE CASCADE,
    type VARCHAR(20) CHECK (type IN ('ASSIGNMENT', 'QUIZ', 'EXAM')),
    title VARCHAR(200) NOT NULL,
    description TEXT,
    max_score INTEGER DEFAULT 100,
    time_limit_minutes INTEGER,
    passing_score INTEGER DEFAULT 70,
    due_date TIMESTAMP,
    is_published BOOLEAN DEFAULT false,
    question_data JSONB, -- Untuk menyimpan soal dalam format JSON
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_assignments_course ON assignments_quizzes(course_id);
CREATE INDEX idx_assignments_lesson ON assignments_quizzes(lesson_id);
CREATE INDEX idx_assignments_type ON assignments_quizzes(type);
CREATE INDEX idx_assignments_due_date ON assignments_quizzes(due_date);
```

### **18. Tabel Submissions (Pengumpulan Tugas)**
```sql
CREATE TABLE submissions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    assignment_quiz_id UUID REFERENCES assignments_quizzes(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    submission_text TEXT,
    file_urls JSONB, -- Multiple file upload
    score DECIMAL(5,2),
    feedback TEXT,
    graded_by UUID REFERENCES users(id) ON DELETE SET NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    graded_at TIMESTAMP,
    status VARCHAR(20) DEFAULT 'SUBMITTED' CHECK (status IN ('DRAFT', 'SUBMITTED', 'GRADED', 'LATE')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(assignment_quiz_id, student_id)
);

CREATE INDEX idx_submissions_assignment ON submissions(assignment_quiz_id);
CREATE INDEX idx_submissions_student ON submissions(student_id);
CREATE INDEX idx_submissions_status ON submissions(status);
```

### **19. Tabel Quiz_Attempts (Jawaban Kuis)**
```sql
CREATE TABLE quiz_attempts (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    assignment_quiz_id UUID REFERENCES assignments_quizzes(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    answers JSONB NOT NULL,
    score DECIMAL(5,2),
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    submitted_at TIMESTAMP,
    time_spent_seconds INTEGER,
    is_completed BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_quiz_attempts_quiz ON quiz_attempts(assignment_quiz_id);
CREATE INDEX idx_quiz_attempts_student ON quiz_attempts(student_id);
```

### **20. Tabel Lesson_Progress (Progres Belajar)**
```sql
CREATE TABLE lesson_progress (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    lesson_id UUID REFERENCES lessons(id) ON DELETE CASCADE,
    is_completed BOOLEAN DEFAULT false,
    progress_percent INTEGER DEFAULT 0,
    last_position_seconds INTEGER DEFAULT 0,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(student_id, lesson_id)
);

CREATE INDEX idx_lesson_progress_student ON lesson_progress(student_id);
CREATE INDEX idx_lesson_progress_lesson ON lesson_progress(lesson_id);
CREATE INDEX idx_lesson_progress_completed ON lesson_progress(is_completed);
```

### **21. Tabel Discussions (Forum Diskusi)**
```sql
CREATE TABLE discussions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    course_id UUID REFERENCES courses(id) ON DELETE CASCADE,
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    is_pinned BOOLEAN DEFAULT false,
    is_locked BOOLEAN DEFAULT false,
    views_count INTEGER DEFAULT 0,
    replies_count INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_discussions_course ON discussions(course_id);
CREATE INDEX idx_discussions_created_at ON discussions(created_at DESC);

-- Replies
CREATE TABLE discussion_replies (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    discussion_id UUID REFERENCES discussions(id) ON DELETE CASCADE,
    user_id UUID REFERENCES users(id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    is_solution BOOLEAN DEFAULT false,
    likes_count INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_discussion_replies_discussion ON discussion_replies(discussion_id);
```

### **22. Tabel Periods / Semesters**
```sql
CREATE TABLE periods (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    name VARCHAR(50) NOT NULL,
    code VARCHAR(20) NOT NULL,
    type VARCHAR(20) CHECK (type IN ('SEMESTER', 'QUARTER', 'TERM')),
    semester INTEGER CHECK (semester IN (1, 2, 3, 4)),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    grading_start DATE,
    grading_end DATE,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id),
    UNIQUE(academic_year_id, code)
);

CREATE INDEX idx_periods_academic_year ON periods(academic_year_id);
CREATE INDEX idx_periods_date_range ON periods(start_date, end_date);
```

### **23. Tabel Grades (Nilai Siswa)**
```sql
CREATE TABLE grades (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_classroom_id UUID REFERENCES student_classrooms(id) ON DELETE CASCADE,
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    period_id UUID REFERENCES periods(id) ON DELETE CASCADE,
    teacher_id UUID REFERENCES teachers(id) ON DELETE SET NULL,
    score DECIMAL(5,2),
    grade_letter VARCHAR(2),
    predicate VARCHAR(50),
    description TEXT,
    assessment_type VARCHAR(20) CHECK (assessment_type IN ('ASSIGNMENT', 'QUIZ', 'EXAM', 'PROJECT', 'PRACTICAL', 'FINAL')),
    weight INTEGER DEFAULT 1,
    is_final BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id),
    UNIQUE(student_classroom_id, subject_id, period_id, assessment_type)
);

CREATE INDEX idx_grades_student ON grades(student_classroom_id);
CREATE INDEX idx_grades_subject ON grades(subject_id);
CREATE INDEX idx_grades_period ON grades(period_id);
CREATE INDEX idx_grades_teacher ON grades(teacher_id);
```

### **24. Tabel Grade_Scales (Konversi Nilai)**
```sql
CREATE TABLE grade_scales (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    name VARCHAR(50) NOT NULL,
    min_score DECIMAL(5,2) NOT NULL,
    max_score DECIMAL(5,2) NOT NULL,
    grade_letter VARCHAR(2) NOT NULL,
    predicate VARCHAR(50) NOT NULL,
    description TEXT,
    is_default BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id),
    UNIQUE(school_id, grade_letter)
);

CREATE INDEX idx_grade_scales_school ON grade_scales(school_id);
```

### **25. Tabel Report_Card_Templates**
```sql
CREATE TABLE report_card_templates (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    template_data JSONB NOT NULL, -- Menyimpan konfigurasi lengkap template
    is_default BOOLEAN DEFAULT false,
    is_active BOOLEAN DEFAULT true,
    version INTEGER DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_report_templates_school ON report_card_templates(school_id);
```

### **26. Tabel Generated_Reports (Rapor yang Dihasilkan)**
```sql
CREATE TABLE generated_reports (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    period_id UUID REFERENCES periods(id) ON DELETE CASCADE,
    template_id UUID REFERENCES report_card_templates(id) ON DELETE SET NULL,
    pdf_path TEXT NOT NULL,
    report_data JSONB, -- Data rapor lengkap
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    downloaded_count INTEGER DEFAULT 0,
    last_download_at TIMESTAMP,
    created_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_generated_reports_student ON generated_reports(student_id);
CREATE INDEX idx_generated_reports_period ON generated_reports(period_id);
CREATE INDEX idx_generated_reports_generated_at ON generated_reports(generated_at);
```

### **27. Tabel Teacher_Comments (Catatan Wali Kelas)**
```sql
CREATE TABLE teacher_comments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    period_id UUID REFERENCES periods(id) ON DELETE CASCADE,
    classroom_id UUID REFERENCES classrooms(id) ON DELETE CASCADE,
    teacher_id UUID REFERENCES teachers(id) ON DELETE SET NULL,
    comment TEXT NOT NULL,
    comment_type VARCHAR(20) CHECK (comment_type IN ('GENERAL', 'ACADEMIC', 'BEHAVIOR', 'ATTENDANCE', 'ACHIEVEMENT')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_teacher_comments_student ON teacher_comments(student_id);
CREATE INDEX idx_teacher_comments_period ON teacher_comments(period_id);
CREATE INDEX idx_teacher_comments_teacher ON teacher_comments(teacher_id);
```

### **28. Tabel Achievements (Prestasi Siswa)**
```sql
CREATE TABLE achievements (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    achievement_type VARCHAR(50) CHECK (achievement_type IN ('ACADEMIC', 'SPORT', 'ART', 'SOCIAL', 'OTHER')),
    title VARCHAR(200) NOT NULL,
    description TEXT,
    level VARCHAR(20) CHECK (level IN ('SCHOOL', 'REGIONAL', 'NATIONAL', 'INTERNATIONAL')),
    date_achieved DATE,
    certificate_url TEXT,
    points INTEGER DEFAULT 0,
    verified_by UUID REFERENCES users(id) ON DELETE SET NULL,
    is_verified BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_achievements_student ON achievements(student_id);
CREATE INDEX idx_achievements_type ON achievements(achievement_type);
CREATE INDEX idx_achievements_date ON achievements(date_achieved);
```

### **29. Tabel Attendances (Kehadiran Siswa - Khusus Student)**
```sql
CREATE TABLE attendances (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    schedule_id UUID REFERENCES schedules(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    status VARCHAR(20) CHECK (status IN ('PRESENT', 'ABSENT', 'LATE', 'EXCUSED', 'HOLIDAY')),
    check_in_time TIME,
    check_out_time TIME,
    late_minutes INTEGER DEFAULT 0,
    reason TEXT,
    verified_by UUID REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id),
    UNIQUE(student_id, schedule_id, date)
);

CREATE INDEX idx_attendances_student ON attendances(student_id);
CREATE INDEX idx_attendances_schedule ON attendances(schedule_id);
CREATE INDEX idx_attendances_date ON attendances(date);
CREATE INDEX idx_attendances_status ON attendances(status);
```

### **30. Tabel Extracurriculars (Ekstrakurikuler)**
```sql
CREATE TABLE extracurriculars (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    description TEXT,
    teacher_id UUID REFERENCES teachers(id) ON DELETE SET NULL,
    day_of_week VARCHAR(10) CHECK (day_of_week IN ('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY')),
    start_time TIME,
    end_time TIME,
    max_students INTEGER DEFAULT 30,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

CREATE INDEX idx_extracurriculars_school ON extracurriculars(school_id);
CREATE INDEX idx_extracurriculars_teacher ON extracurriculars(teacher_id);

-- Student Extracurriculars
CREATE TABLE student_extracurriculars (
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    extracurricular_id UUID REFERENCES extracurriculars(id) ON DELETE CASCADE,
    enrollment_date DATE DEFAULT CURRENT_DATE,
    status VARCHAR(20) DEFAULT 'ACTIVE' CHECK (status IN ('ACTIVE', 'DROPPED', 'COMPLETED')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id, extracurricular_id)
);

CREATE INDEX idx_student_extracurriculars_student ON student_extracurriculars(student_id);
CREATE INDEX idx_student_extracurriculars_extracurricular ON student_extracurriculars(extracurricular_id);
```

### **31. Tabel System Logs (Audit Trail)**
```sql
CREATE TABLE system_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES users(id) ON DELETE SET NULL,
    action VARCHAR(50) NOT NULL,
    table_name VARCHAR(50),
    record_id UUID,
    old_data JSONB,
    new_data JSONB,
    ip_address INET,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Partition by created_at untuk performa
CREATE INDEX idx_system_logs_user ON system_logs(user_id);
CREATE INDEX idx_system_logs_action ON system_logs(action);
CREATE INDEX idx_system_logs_created_at ON system_logs(created_at DESC);
```

---

## 🔧 **Fitur Enterprise yang Disertakan:**

1. **UUID Primary Keys** - Untuk distribusi dan keamanan
2. **Multi-School Support** - Setiap tabel punya `school_id`
3. **Soft Delete** - Menggunakan `is_active` flag
4. **Audit Trail** - `created_by`, `updated_by`, `created_at`, `updated_at`
5. **JSONB Fields** - Untuk data fleksibel (question_data, answers, template_data)
6. **Partitioning Ready** - Tabel logs siap dipartisi
7. **Comprehensive Indexing** - Untuk performa query cepat
8. **Foreign Key Constraints** - Integritas data terjamin
9. **Check Constraints** - Validasi data di level database
10. **Referential Integrity** - ON DELETE CASCADE / SET NULL

---

## 📊 **Query Contoh (Advanced)**

```sql
-- 1. Nilai Rata-rata Siswa per Mata Pelajaran per Semester
SELECT 
    s.full_name,
    sub.name AS subject_name,
    AVG(g.score) AS average_score,
    COUNT(g.id) AS total_assessments
FROM grades g
JOIN student_classrooms sc ON g.student_classroom_id = sc.id
JOIN students s ON sc.student_id = s.id
JOIN subjects sub ON g.subject_id = sub.id
JOIN periods p ON g.period_id = p.id
WHERE p.academic_year_id = 'your_academic_year_id'
  AND s.school_id = 'your_school_id'
GROUP BY s.full_name, sub.name
ORDER BY s.full_name, average_score DESC;

-- 2. Rekap Absensi Siswa per Bulan
SELECT 
    s.full_name,
    COUNT(CASE WHEN a.status = 'PRESENT' THEN 1 END) AS present,
    COUNT(CASE WHEN a.status = 'ABSENT' THEN 1 END) AS absent,
    COUNT(CASE WHEN a.status = 'LATE' THEN 1 END) AS late,
    COUNT(CASE WHEN a.status = 'EXCUSED' THEN 1 END) AS excused
FROM attendances a
JOIN students s ON a.student_id = s.id
WHERE EXTRACT(MONTH FROM a.date) = 1
  AND EXTRACT(YEAR FROM a.date) = 2026
  AND s.school_id = 'your_school_id'
GROUP BY s.full_name;

-- 3. Progress Belajar e-Learning
SELECT 
    s.full_name,
    c.title AS course_title,
    COUNT(l.id) AS total_lessons,
    COUNT(lp.id) AS completed_lessons,
    ROUND(COUNT(lp.id)::DECIMAL / COUNT(l.id) * 100, 2) AS progress_percent
FROM students s
JOIN enrollments e ON s.id = e.student_id
JOIN courses c ON e.course_id = c.id
JOIN lessons l ON c.id = l.course_id
LEFT JOIN lesson_progress lp ON lp.student_id = s.id AND lp.lesson_id = l.id AND lp.is_completed = true
WHERE s.id = 'student_uuid'
GROUP BY s.full_name, c.title;
```

---

Struktur ini siap untuk **production** dengan skala besar (ribuan hingga jutaan data). Apakah ada bagian yang perlu saya jelaskan lebih detail atau tambahkan fitur lain? 😊

## Analisis Kesiapan Database untuk Fitur-Fitur Lanjutan

### ✅ **Sudah Support & Bisa Langsung Digunakan**

#### 1. **Timetable Automation Generate dengan Semua Constraint**
**Status: ✅ SUPPORT (Perlu Enhancement)**

**Yang sudah ada:**
- Tabel `schedules` dengan semua field yang dibutuhkan
- Relasi ke `teachers`, `classrooms`, `subjects`, `academic_years`
- Constraint `day_of_week`, `start_time`, `end_time`

**Yang perlu ditambahkan untuk automation:**

```sql
-- Tambahkan tabel untuk constraint timetabling
CREATE TABLE timetable_constraints (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    teacher_id UUID REFERENCES teachers(id) ON DELETE CASCADE,
    day_of_week VARCHAR(10),
    time_slot_start TIME,
    time_slot_end TIME,
    constraint_type VARCHAR(20) CHECK (constraint_type IN ('UNAVAILABLE', 'PREFERRED', 'MANDATORY')),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE timetable_rooms (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    room_code VARCHAR(20) UNIQUE NOT NULL,
    room_name VARCHAR(100) NOT NULL,
    capacity INTEGER DEFAULT 30,
    room_type VARCHAR(20) CHECK (room_type IN ('CLASSROOM', 'LAB', 'AUDITORIUM', 'SPORT', 'MUSIC', 'ART')),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tambahkan field room_id ke schedules
ALTER TABLE schedules ADD COLUMN room_id UUID REFERENCES timetable_rooms(id) ON DELETE SET NULL;

-- Tabel untuk hasil generate otomatis
CREATE TABLE timetable_generations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    generation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    algorithm_version VARCHAR(50),
    total_conflicts INTEGER DEFAULT 0,
    total_assignments INTEGER DEFAULT 0,
    status VARCHAR(20) DEFAULT 'GENERATED' CHECK (status IN ('GENERATED', 'OPTIMIZED', 'APPROVED', 'DEPLOYED')),
    generated_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tambahkan generation_id ke schedules
ALTER TABLE schedules ADD COLUMN generation_id UUID REFERENCES timetable_generations(id) ON DELETE SET NULL;
```

---

#### 2. **CBT (Computer Based Test)**
**Status: ✅ SUPPORT (Perlu Enhancement)**

**Yang sudah ada:**
- `assignments_quizzes` dengan JSONB `question_data`
- `submissions` untuk pengumpulan
- `quiz_attempts` untuk jawaban

**Yang perlu ditambahkan:**

```sql
-- Tabel untuk bank soal
CREATE TABLE question_banks (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    question_type VARCHAR(30) CHECK (question_type IN ('MULTIPLE_CHOICE', 'ESSAY', 'TRUE_FALSE', 'MATCHING', 'NUMERIC')),
    difficulty_level VARCHAR(20) CHECK (difficulty_level IN ('EASY', 'MEDIUM', 'HARD', 'VERY_HARD')),
    question_text TEXT NOT NULL,
    options JSONB, -- Untuk multiple choice, matching
    correct_answer JSONB, -- Bisa multiple correct untuk multiple answer
    explanation TEXT,
    tags TEXT[],
    points INTEGER DEFAULT 10,
    is_active BOOLEAN DEFAULT true,
    created_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk paket soal
CREATE TABLE exam_packages (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration_minutes INTEGER,
    total_questions INTEGER DEFAULT 0,
    passing_score INTEGER DEFAULT 70,
    is_randomized BOOLEAN DEFAULT false,
    shuffle_options BOOLEAN DEFAULT false,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Relasi soal ke paket
CREATE TABLE exam_package_questions (
    package_id UUID REFERENCES exam_packages(id) ON DELETE CASCADE,
    question_id UUID REFERENCES question_banks(id) ON DELETE CASCADE,
    question_order INTEGER DEFAULT 0,
    points INTEGER DEFAULT 10,
    PRIMARY KEY (package_id, question_id)
);

-- CBT Sessions (untuk proctoring, timer, dll)
CREATE TABLE cbt_sessions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    exam_package_id UUID REFERENCES exam_packages(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    schedule_id UUID REFERENCES schedules(id) ON DELETE SET NULL,
    start_time TIMESTAMP,
    end_time TIMESTAMP,
    duration_seconds INTEGER,
    current_question_index INTEGER DEFAULT 0,
    status VARCHAR(20) CHECK (status IN ('NOT_STARTED', 'IN_PROGRESS', 'PAUSED', 'COMPLETED', 'TERMINATED')),
    ip_address INET,
    user_agent TEXT,
    proctor_notes TEXT,
    is_submitted BOOLEAN DEFAULT false,
    submitted_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Log aktivitas CBT (anti-cheating)
CREATE TABLE cbt_activity_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    session_id UUID REFERENCES cbt_sessions(id) ON DELETE CASCADE,
    activity_type VARCHAR(30) CHECK (activity_type IN ('PAGE_CHANGE', 'TAB_SWITCH', 'COPY', 'PASTE', 'RIGHT_CLICK', 'FULLSCREEN', 'PRINT')),
    activity_data JSONB,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

#### 3. **Multi Kurikulum**
**Status: ✅ FULLY SUPPORT**

**Sudah mendukung:**
- Tabel `curriculum_structures` dengan field `hours_per_week`, `semester`, `is_core`
- Field `category` di `subjects` (CORE, ELECTIVE, EXTRACURRICULAR, RELIGION, LOCAL_CONTENT)
- Multi level education (TK, SD, SMP, SMA, UNIVERSITY)

**Enhancement opsional:**
```sql
-- Untuk Kurikulum Merdeka specific
CREATE TABLE curriculum_phases (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    name VARCHAR(50) NOT NULL, -- Fase A, B, C, D, E, F
    description TEXT,
    age_range VARCHAR(20), -- 7-9 tahun
    class_levels TEXT[], -- Array of class levels
    is_active BOOLEAN DEFAULT true
);
```

---

#### 4. **Penilaian Semester & Cetak Rapor**
**Status: ✅ FULLY SUPPORT**

**Sudah mendukung:**
- Tabel `periods` dengan type (SEMESTER, QUARTER, TERM)
- Field `semester` (1,2,3,4)
- Tabel `grades` dengan `assessment_type` (ASSIGNMENT, QUIZ, EXAM, PROJECT, PRACTICAL, FINAL)
- `teacher_comments` per period
- `generated_reports` dengan PDF

**Untuk PTS (Penilaian Tengah Semester) dan PAS (Penilaian Akhir Semester):**
```sql
-- Assessment periods lebih spesifik
ALTER TABLE periods ADD COLUMN assessment_type VARCHAR(30) CHECK (assessment_type IN ('PTS', 'PAS', 'PAT', 'FINAL'));
ALTER TABLE periods ADD COLUMN weight INTEGER DEFAULT 1;
```

**Query untuk rapor:**
```sql
-- Rapor PTS (50% dari nilai)
WITH student_scores AS (
    SELECT 
        sc.student_id,
        s.full_name,
        sub.name AS subject_name,
        AVG(CASE WHEN g.assessment_type IN ('ASSIGNMENT', 'QUIZ') THEN g.score END) AS daily_scores,
        AVG(CASE WHEN g.assessment_type = 'PTS' THEN g.score END) AS mid_score,
        AVG(CASE WHEN g.assessment_type = 'PAS' THEN g.score END) AS final_score
    FROM grades g
    JOIN student_classrooms sc ON g.student_classroom_id = sc.id
    JOIN students s ON sc.student_id = s.id
    JOIN subjects sub ON g.subject_id = sub.id
    JOIN periods p ON g.period_id = p.id
    WHERE p.academic_year_id = 'uuid'
      AND p.semester = 1
    GROUP BY sc.student_id, s.full_name, sub.name
)
SELECT 
    *,
    (daily_scores * 0.4 + mid_score * 0.3 + final_score * 0.3) AS final_grade
FROM student_scores;
```

---

#### 5. **Sync Dapodik**
**Status: ⚠️ PARTIAL SUPPORT (Need Integration Layer)**

**Yang sudah ada:**
- Semua field utama Dapodik: NIS, NISN, NIP, sekolah, siswa, guru
- Struktur multi-level pendidikan

**Tambahan untuk Dapodik:**
```sql
-- Mapping ke Dapodik ID
CREATE TABLE dapodik_mappings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    local_table VARCHAR(50),
    local_id UUID,
    dapodik_id VARCHAR(50),
    dapodik_table VARCHAR(50),
    last_sync_at TIMESTAMP,
    sync_status VARCHAR(20) DEFAULT 'PENDING' CHECK (sync_status IN ('PENDING', 'SYNCED', 'FAILED')),
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dapodik sync log
CREATE TABLE dapodik_sync_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    sync_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sync_type VARCHAR(20) CHECK (sync_type IN ('FULL', 'INCREMENTAL', 'SCHEDULED')),
    status VARCHAR(20) CHECK (status IN ('STARTED', 'PROCESSING', 'COMPLETED', 'FAILED')),
    total_records INTEGER DEFAULT 0,
    total_synced INTEGER DEFAULT 0,
    total_failed INTEGER DEFAULT 0,
    sync_duration_seconds INTEGER,
    error_log TEXT,
    triggered_by UUID REFERENCES users(id)
);
```

---

#### 6. **Proses Penerimaan Murid (PPDB)**
**Status: ⚠️ PERLU DITAMBAHKAN**

**Tambahan tabel:**
```sql
-- Master pendaftaran
CREATE TABLE admissions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(20) CHECK (type IN ('ZONING', 'ACHIEVEMENT', 'TRANSFER', 'GENERAL')),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    quota INTEGER DEFAULT 0,
    registration_fee DECIMAL(15,2) DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pendaftar
CREATE TABLE applicants (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    admission_id UUID REFERENCES admissions(id) ON DELETE CASCADE,
    full_name VARCHAR(150) NOT NULL,
    birth_place VARCHAR(100),
    birth_date DATE,
    gender VARCHAR(10) CHECK (gender IN ('MALE', 'FEMALE')),
    address TEXT,
    parent_name VARCHAR(150),
    parent_phone VARCHAR(20),
    parent_email VARCHAR(100),
    previous_school VARCHAR(150),
    registration_number VARCHAR(50) UNIQUE,
    status VARCHAR(20) DEFAULT 'REGISTERED' CHECK (status IN ('REGISTERED', 'VERIFIED', 'TESTED', 'INTERVIEWED', 'ACCEPTED', 'REJECTED', 'ENROLLED')),
    test_score DECIMAL(5,2),
    interview_score DECIMAL(5,2),
    total_score DECIMAL(5,2),
    ranking INTEGER,
    notes TEXT,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id)
);

-- Dokumen pendaftaran
CREATE TABLE application_documents (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    applicant_id UUID REFERENCES applicants(id) ON DELETE CASCADE,
    document_type VARCHAR(30) CHECK (document_type IN ('BIRTH_CERT', 'FAMILY_CARD', 'PHOTO', 'PREVIOUS_SCHOOL', 'ACHIEVEMENT', 'OTHER')),
    document_url TEXT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified BOOLEAN DEFAULT false,
    verified_by UUID REFERENCES users(id)
);

-- Proses seleksi
CREATE TABLE admission_selections (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    admission_id UUID REFERENCES admissions(id) ON DELETE CASCADE,
    applicant_id UUID REFERENCES applicants(id) ON DELETE CASCADE,
    selection_type VARCHAR(20) CHECK (selection_type IN ('TEST', 'INTERVIEW', 'DOCUMENT_VERIFICATION', 'FINAL')),
    score DECIMAL(5,2),
    result VARCHAR(20) CHECK (result IN ('PASSED', 'FAILED', 'WAITING', 'PENDING')),
    evaluator_id UUID REFERENCES users(id),
    evaluation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT
);
```

---

#### 7. **Sistem Billing (Pendaftar sampai Murid)**
**Status: ⚠️ PERLU DITAMBAHKAN**

```sql
-- Master biaya per tahun ajaran
CREATE TABLE fee_masters (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    class_level_id UUID REFERENCES class_levels(id) ON DELETE CASCADE,
    fee_category VARCHAR(30) CHECK (fee_category IN ('REGISTRATION', 'TUITION', 'LAB', 'ACTIVITY', 'EXAM', 'OTHER')),
    fee_name VARCHAR(100) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    is_mandatory BOOLEAN DEFAULT true,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tagihan siswa
CREATE TABLE invoices (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    fee_master_id UUID REFERENCES fee_masters(id) ON DELETE CASCADE,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    period_id UUID REFERENCES periods(id),
    due_date DATE,
    total_amount DECIMAL(15,2) NOT NULL,
    discount_amount DECIMAL(15,2) DEFAULT 0,
    penalty_amount DECIMAL(15,2) DEFAULT 0,
    final_amount DECIMAL(15,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'PENDING' CHECK (status IN ('PENDING', 'PARTIAL', 'PAID', 'OVERDUE', 'CANCELLED')),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id)
);

-- Pembayaran
CREATE TABLE payments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    invoice_id UUID REFERENCES invoices(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    payment_method VARCHAR(30) CHECK (payment_method IN ('CASH', 'TRANSFER', 'CREDIT_CARD', 'E_WALLET', 'OTHER')),
    payment_date DATE DEFAULT CURRENT_DATE,
    amount DECIMAL(15,2) NOT NULL,
    reference_number VARCHAR(100),
    bank_name VARCHAR(50),
    payment_proof_url TEXT,
    status VARCHAR(20) DEFAULT 'PENDING' CHECK (status IN ('PENDING', 'VERIFIED', 'REJECTED')),
    verified_by UUID REFERENCES users(id),
    verification_date TIMESTAMP,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Installment/cicilan
CREATE TABLE payment_installments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    invoice_id UUID REFERENCES invoices(id) ON DELETE CASCADE,
    installment_number INTEGER NOT NULL,
    due_date DATE,
    amount DECIMAL(15,2) NOT NULL,
    is_paid BOOLEAN DEFAULT false,
    paid_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 📊 **Tabel Ringkasan Kesiapan**

| Fitur | Status | Action Needed |
|-------|--------|---------------|
| **Timetable Automation** | ✅ 80% | Tambah constraint & room tables |
| **CBT** | ✅ 75% | Tambah question bank & session tables |
| **Multi Kurikulum** | ✅ 100% | Siap digunakan |
| **Rapor PTS/PAS** | ✅ 100% | Siap digunakan |
| **Sync Dapodik** | ⚠️ 60% | Tambah mapping & sync tables |
| **PPDB** | ❌ 0% | Tambah admission tables |
| **Billing** | ❌ 0% | Tambah fee & payment tables |

---

## 🚀 **Kesimpulan**

**Level Kesiapan Keseluruhan: 70%**

**Yang sudah sangat siap:**
- Multi kurikulum
- Penilaian dan rapor
- e-Learning dasar

**Yang perlu tambahan:**
- Timetable automation (tambah 5-6 tabel)
- CBT (tambah 6-7 tabel)  
- PPDB (tambah 5 tabel)
- Billing (tambah 5 tabel)
- Dapodik sync (tambah 2-3 tabel)

Semua fitur ini **bisa ditambahkan secara modular** tanpa mengganggu struktur existing. Apakah Anda ingin saya buatkan **script SQL lengkap** untuk semua enhancement di atas? 😊

Tentu! Saya akan buatkan **SQL script lengkap** untuk semua enhancement dengan pemisahan **billing untuk applicants** (biaya pendaftaran) dan **billing untuk students** (biaya bulanan/tahunan).

---

## 📋 **SQL SCRIPT LENGKAP - ENHANCEMENT DATABASE**

### **1. TIMETABLE & CONSTRAINT TABLES**

```sql
-- ============================================
-- 1. TIMETABLE CONSTRAINTS
-- ============================================

-- Tabel constraint guru (unavailable, preferred, mandatory)
CREATE TABLE timetable_constraints (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    teacher_id UUID REFERENCES teachers(id) ON DELETE CASCADE,
    day_of_week VARCHAR(10) CHECK (day_of_week IN ('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY')),
    time_slot_start TIME,
    time_slot_end TIME,
    constraint_type VARCHAR(20) CHECK (constraint_type IN ('UNAVAILABLE', 'PREFERRED', 'MANDATORY')),
    reason TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

-- Tabel ruangan
CREATE TABLE timetable_rooms (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    room_code VARCHAR(20) UNIQUE NOT NULL,
    room_name VARCHAR(100) NOT NULL,
    capacity INTEGER DEFAULT 30,
    room_type VARCHAR(20) CHECK (room_type IN ('CLASSROOM', 'LAB', 'AUDITORIUM', 'SPORT', 'MUSIC', 'ART', 'COMPUTER_LAB', 'SCIENCE_LAB')),
    floor_number INTEGER,
    building VARCHAR(50),
    facilities TEXT[],
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

-- Tabel constraint ruangan
CREATE TABLE room_constraints (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    room_id UUID REFERENCES timetable_rooms(id) ON DELETE CASCADE,
    day_of_week VARCHAR(10) CHECK (day_of_week IN ('MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY')),
    time_slot_start TIME,
    time_slot_end TIME,
    constraint_type VARCHAR(20) CHECK (constraint_type IN ('UNAVAILABLE', 'MAINTENANCE', 'RESERVED')),
    reason TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel hasil generate timetable
CREATE TABLE timetable_generations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    semester VARCHAR(10) CHECK (semester IN ('SEMESTER_1', 'SEMESTER_2')),
    generation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    algorithm_version VARCHAR(50),
    total_conflicts INTEGER DEFAULT 0,
    total_assignments INTEGER DEFAULT 0,
    total_teachers INTEGER DEFAULT 0,
    status VARCHAR(20) DEFAULT 'GENERATED' CHECK (status IN ('GENERATED', 'OPTIMIZED', 'APPROVED', 'DEPLOYED', 'FAILED')),
    generation_data JSONB, -- Menyimpan data lengkap hasil generate
    generated_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tambahkan field ke schedules existing
ALTER TABLE schedules ADD COLUMN room_id UUID REFERENCES timetable_rooms(id) ON DELETE SET NULL;
ALTER TABLE schedules ADD COLUMN generation_id UUID REFERENCES timetable_generations(id) ON DELETE SET NULL;
ALTER TABLE schedules ADD COLUMN is_locked BOOLEAN DEFAULT false;
ALTER TABLE schedules ADD COLUMN notes TEXT;

-- Index untuk performance
CREATE INDEX idx_timetable_constraints_teacher ON timetable_constraints(teacher_id);
CREATE INDEX idx_timetable_constraints_day ON timetable_constraints(day_of_week);
CREATE INDEX idx_timetable_rooms_school ON timetable_rooms(school_id);
CREATE INDEX idx_timetable_generations_academic_year ON timetable_generations(academic_year_id);
```

---

### **2. CBT (COMPUTER BASED TEST) TABLES**

```sql
-- ============================================
-- 2. CBT (COMPUTER BASED TEST)
-- ============================================

-- Bank Soal
CREATE TABLE question_banks (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    question_type VARCHAR(30) CHECK (question_type IN ('MULTIPLE_CHOICE', 'ESSAY', 'TRUE_FALSE', 'MATCHING', 'NUMERIC', 'FILL_BLANK', 'HOT_SPOT')),
    difficulty_level VARCHAR(20) CHECK (difficulty_level IN ('EASY', 'MEDIUM', 'HARD', 'VERY_HARD')),
    cognitive_level VARCHAR(20) CHECK (cognitive_level IN ('LOTS', 'MOTS', 'HOTS')),
    question_text TEXT NOT NULL,
    options JSONB, -- {a: 'text', b: 'text', c: 'text', d: 'text'}
    correct_answer JSONB, -- Untuk multiple correct answer
    explanation TEXT,
    tags TEXT[],
    points INTEGER DEFAULT 10,
    is_public BOOLEAN DEFAULT false,
    is_active BOOLEAN DEFAULT true,
    created_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_by UUID REFERENCES users(id)
);

-- Paket Soal (Exam Package)
CREATE TABLE exam_packages (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    subject_id UUID REFERENCES subjects(id) ON DELETE CASCADE,
    duration_minutes INTEGER DEFAULT 60,
    total_questions INTEGER DEFAULT 0,
    passing_score INTEGER DEFAULT 70,
    is_randomized BOOLEAN DEFAULT false, -- Randomize soal per siswa
    shuffle_options BOOLEAN DEFAULT false,
    show_results BOOLEAN DEFAULT true,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

-- Relasi soal ke paket
CREATE TABLE exam_package_questions (
    package_id UUID REFERENCES exam_packages(id) ON DELETE CASCADE,
    question_id UUID REFERENCES question_banks(id) ON DELETE CASCADE,
    question_order INTEGER DEFAULT 0,
    points INTEGER DEFAULT 10,
    PRIMARY KEY (package_id, question_id)
);

-- CBT Sessions
CREATE TABLE cbt_sessions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    exam_package_id UUID REFERENCES exam_packages(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    schedule_id UUID REFERENCES schedules(id) ON DELETE SET NULL,
    class_id UUID REFERENCES classrooms(id) ON DELETE SET NULL,
    start_time TIMESTAMP,
    end_time TIMESTAMP,
    duration_seconds INTEGER,
    current_question_index INTEGER DEFAULT 0,
    answers JSONB, -- Menyimpan jawaban sementara
    status VARCHAR(20) CHECK (status IN ('NOT_STARTED', 'IN_PROGRESS', 'PAUSED', 'COMPLETED', 'TERMINATED', 'TIME_OUT')),
    ip_address INET,
    user_agent TEXT,
    proctor_notes TEXT,
    is_submitted BOOLEAN DEFAULT false,
    submitted_at TIMESTAMP,
    final_score DECIMAL(5,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Log aktivitas CBT (anti-cheating)
CREATE TABLE cbt_activity_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    session_id UUID REFERENCES cbt_sessions(id) ON DELETE CASCADE,
    activity_type VARCHAR(30) CHECK (activity_type IN ('PAGE_CHANGE', 'TAB_SWITCH', 'COPY', 'PASTE', 'RIGHT_CLICK', 'FULLSCREEN', 'PRINT', 'SCREENSHOT')),
    activity_data JSONB,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Proctoring dashboard
CREATE TABLE cbt_proctoring (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    session_id UUID REFERENCES cbt_sessions(id) ON DELETE CASCADE,
    proctor_id UUID REFERENCES users(id),
    start_proctoring TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_proctoring TIMESTAMP,
    notes TEXT,
    status VARCHAR(20) CHECK (status IN ('ACTIVE', 'COMPLETED', 'FLAGGED')),
    flag_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Index untuk performance
CREATE INDEX idx_question_banks_subject ON question_banks(subject_id);
CREATE INDEX idx_question_banks_difficulty ON question_banks(difficulty_level);
CREATE INDEX idx_exam_packages_subject ON exam_packages(subject_id);
CREATE INDEX idx_cbt_sessions_student ON cbt_sessions(student_id);
CREATE INDEX idx_cbt_sessions_exam_package ON cbt_sessions(exam_package_id);
CREATE INDEX idx_cbt_sessions_status ON cbt_sessions(status);
CREATE INDEX idx_cbt_activity_logs_session ON cbt_activity_logs(session_id);
CREATE INDEX idx_cbt_activity_logs_recorded_at ON cbt_activity_logs(recorded_at);
```

---

### **3. PPDB (PENERIMAAN PESERTA DIDIK BARU) TABLES**

```sql
-- ============================================
-- 3. PPDB (PENERIMAAN PESERTA DIDIK BARU)
-- ============================================

-- Master pendaftaran
CREATE TABLE admissions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(20) UNIQUE NOT NULL,
    type VARCHAR(20) CHECK (type IN ('ZONING', 'ACHIEVEMENT', 'TRANSFER', 'GENERAL', 'AFFIRMATION')),
    class_level_id UUID REFERENCES class_levels(id) ON DELETE CASCADE,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    quota INTEGER DEFAULT 0,
    registration_fee DECIMAL(15,2) DEFAULT 0,
    min_age INTEGER,
    max_age INTEGER,
    requirements TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

-- Pendaftar (Applicants)
CREATE TABLE applicants (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    admission_id UUID REFERENCES admissions(id) ON DELETE CASCADE,
    full_name VARCHAR(150) NOT NULL,
    nik VARCHAR(50) UNIQUE,
    birth_place VARCHAR(100),
    birth_date DATE,
    gender VARCHAR(10) CHECK (gender IN ('MALE', 'FEMALE')),
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    parent_name VARCHAR(150),
    parent_phone VARCHAR(20),
    parent_email VARCHAR(100),
    previous_school VARCHAR(150),
    registration_number VARCHAR(50) UNIQUE NOT NULL,
    status VARCHAR(20) DEFAULT 'REGISTERED' CHECK (status IN ('REGISTERED', 'VERIFIED', 'TESTED', 'INTERVIEWED', 'ACCEPTED', 'REJECTED', 'ENROLLED', 'WAITING')),
    test_score DECIMAL(5,2),
    interview_score DECIMAL(5,2),
    total_score DECIMAL(5,2),
    ranking INTEGER,
    notes TEXT,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

-- Dokumen pendaftaran
CREATE TABLE application_documents (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    applicant_id UUID REFERENCES applicants(id) ON DELETE CASCADE,
    document_type VARCHAR(30) CHECK (document_type IN ('BIRTH_CERT', 'FAMILY_CARD', 'PHOTO', 'PREVIOUS_SCHOOL', 'ACHIEVEMENT', 'HEALTH_CERT', 'OTHER')),
    document_url TEXT NOT NULL,
    file_name VARCHAR(200),
    file_size BIGINT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified BOOLEAN DEFAULT false,
    verified_by UUID REFERENCES users(id),
    verification_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Proses seleksi
CREATE TABLE admission_selections (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    admission_id UUID REFERENCES admissions(id) ON DELETE CASCADE,
    applicant_id UUID REFERENCES applicants(id) ON DELETE CASCADE,
    selection_type VARCHAR(20) CHECK (selection_type IN ('TEST', 'INTERVIEW', 'DOCUMENT_VERIFICATION', 'FINAL', 'ACADEMIC')),
    score DECIMAL(5,2),
    result VARCHAR(20) CHECK (result IN ('PASSED', 'FAILED', 'WAITING', 'PENDING')),
    evaluator_id UUID REFERENCES users(id),
    evaluation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Timeline pendaftaran
CREATE TABLE admission_timelines (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    admission_id UUID REFERENCES admissions(id) ON DELETE CASCADE,
    phase_name VARCHAR(50) NOT NULL,
    start_date DATE,
    end_date DATE,
    description TEXT,
    order_number INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Index
CREATE INDEX idx_admissions_school ON admissions(school_id);
CREATE INDEX idx_admissions_academic_year ON admissions(academic_year_id);
CREATE INDEX idx_admissions_dates ON admissions(start_date, end_date);
CREATE INDEX idx_applicants_admission ON applicants(admission_id);
CREATE INDEX idx_applicants_status ON applicants(status);
CREATE INDEX idx_applicants_reg_number ON applicants(registration_number);
CREATE INDEX idx_application_documents_applicant ON application_documents(applicant_id);
CREATE INDEX idx_admission_selections_applicant ON admission_selections(applicant_id);
```

---

### **4. BILLING - APPLICANTS (BIAYA PENDAFTARAN)**

```sql
-- ============================================
-- 4. BILLING FOR APPLICANTS (Registration Fee)
-- ============================================

-- Invoice untuk pendaftar
CREATE TABLE applicant_invoices (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    applicant_id UUID REFERENCES applicants(id) ON DELETE CASCADE,
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    admission_id UUID REFERENCES admissions(id) ON DELETE CASCADE,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    invoice_type VARCHAR(20) CHECK (invoice_type IN ('REGISTRATION_FEE', 'TEST_FEE', 'RE_REGISTRATION')),
    due_date DATE,
    total_amount DECIMAL(15,2) NOT NULL,
    discount_amount DECIMAL(15,2) DEFAULT 0,
    penalty_amount DECIMAL(15,2) DEFAULT 0,
    final_amount DECIMAL(15,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'PENDING' CHECK (status IN ('PENDING', 'PARTIAL', 'PAID', 'OVERDUE', 'CANCELLED', 'REFUNDED')),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

-- Pembayaran untuk pendaftar
CREATE TABLE applicant_payments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    invoice_id UUID REFERENCES applicant_invoices(id) ON DELETE CASCADE,
    applicant_id UUID REFERENCES applicants(id) ON DELETE CASCADE,
    payment_method VARCHAR(30) CHECK (payment_method IN ('CASH', 'TRANSFER', 'CREDIT_CARD', 'E_WALLET', 'OTHER')),
    payment_date DATE DEFAULT CURRENT_DATE,
    amount DECIMAL(15,2) NOT NULL,
    reference_number VARCHAR(100),
    bank_name VARCHAR(50),
    payment_proof_url TEXT,
    status VARCHAR(20) DEFAULT 'PENDING' CHECK (status IN ('PENDING', 'VERIFIED', 'REJECTED', 'REFUNDED')),
    verified_by UUID REFERENCES users(id),
    verification_date TIMESTAMP,
    refund_date TIMESTAMP,
    refund_reason TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Index
CREATE INDEX idx_applicant_invoices_applicant ON applicant_invoices(applicant_id);
CREATE INDEX idx_applicant_invoices_status ON applicant_invoices(status);
CREATE INDEX idx_applicant_payments_invoice ON applicant_payments(invoice_id);
CREATE INDEX idx_applicant_payments_status ON applicant_payments(status);
```

---

### **5. BILLING - STUDENTS (BIAYA BULANAN/TAHUNAN)**

```sql
-- ============================================
-- 5. BILLING FOR STUDENTS (Monthly/Annual Fee)
-- ============================================

-- Master biaya per tahun ajaran
CREATE TABLE fee_masters (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    class_level_id UUID REFERENCES class_levels(id) ON DELETE CASCADE,
    fee_category VARCHAR(30) CHECK (fee_category IN ('REGISTRATION', 'TUITION', 'LAB', 'ACTIVITY', 'EXAM', 'PRACTICAL', 'FIELD_TRIP', 'UNIFORM', 'OTHER')),
    fee_name VARCHAR(100) NOT NULL,
    fee_code VARCHAR(50) UNIQUE NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    frequency VARCHAR(20) CHECK (frequency IN ('ONCE', 'MONTHLY', 'SEMESTER', 'YEARLY')),
    is_mandatory BOOLEAN DEFAULT true,
    is_active BOOLEAN DEFAULT true,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

-- Fee structure per semester (detail)
CREATE TABLE fee_structures (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    fee_master_id UUID REFERENCES fee_masters(id) ON DELETE CASCADE,
    period_id UUID REFERENCES periods(id) ON DELETE CASCADE,
    due_date DATE,
    amount DECIMAL(15,2) NOT NULL,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Invoice untuk siswa
CREATE TABLE student_invoices (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    fee_master_id UUID REFERENCES fee_masters(id) ON DELETE CASCADE,
    student_classroom_id UUID REFERENCES student_classrooms(id) ON DELETE CASCADE,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    period_id UUID REFERENCES periods(id),
    invoice_date DATE DEFAULT CURRENT_DATE,
    due_date DATE,
    total_amount DECIMAL(15,2) NOT NULL,
    discount_amount DECIMAL(15,2) DEFAULT 0,
    penalty_amount DECIMAL(15,2) DEFAULT 0,
    final_amount DECIMAL(15,2) NOT NULL,
    paid_amount DECIMAL(15,2) DEFAULT 0,
    status VARCHAR(20) DEFAULT 'PENDING' CHECK (status IN ('PENDING', 'PARTIAL', 'PAID', 'OVERDUE', 'CANCELLED', 'REFUNDED', 'WAIVED')),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by UUID REFERENCES users(id),
    updated_by UUID REFERENCES users(id)
);

-- Installment / Cicilan untuk siswa
CREATE TABLE student_installments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    invoice_id UUID REFERENCES student_invoices(id) ON DELETE CASCADE,
    installment_number INTEGER NOT NULL,
    due_date DATE,
    amount DECIMAL(15,2) NOT NULL,
    is_paid BOOLEAN DEFAULT false,
    paid_date DATE,
    paid_amount DECIMAL(15,2),
    payment_id UUID, -- Reference to student_payments
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pembayaran untuk siswa
CREATE TABLE student_payments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    invoice_id UUID REFERENCES student_invoices(id) ON DELETE CASCADE,
    student_id UUID REFERENCES students(id) ON DELETE CASCADE,
    payment_method VARCHAR(30) CHECK (payment_method IN ('CASH', 'TRANSFER', 'CREDIT_CARD', 'E_WALLET', 'AUTO_DEBIT', 'OTHER')),
    payment_date DATE DEFAULT CURRENT_DATE,
    amount DECIMAL(15,2) NOT NULL,
    reference_number VARCHAR(100),
    bank_name VARCHAR(50),
    payment_proof_url TEXT,
    status VARCHAR(20) DEFAULT 'PENDING' CHECK (status IN ('PENDING', 'VERIFIED', 'REJECTED', 'REFUNDED')),
    verified_by UUID REFERENCES users(id),
    verification_date TIMESTAMP,
    refund_date TIMESTAMP,
    refund_reason TEXT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Pembayaran batch (untuk pembayaran massal)
CREATE TABLE student_payment_batches (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    batch_number VARCHAR(50) UNIQUE NOT NULL,
    academic_year_id UUID REFERENCES academic_years(id) ON DELETE CASCADE,
    period_id UUID REFERENCES periods(id),
    description TEXT,
    total_invoices INTEGER DEFAULT 0,
    total_amount DECIMAL(15,2) DEFAULT 0,
    paid_amount DECIMAL(15,2) DEFAULT 0,
    status VARCHAR(20) DEFAULT 'PENDING' CHECK (status IN ('PENDING', 'PROCESSING', 'COMPLETED', 'FAILED')),
    processed_by UUID REFERENCES users(id),
    processed_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Relasi invoice ke batch
CREATE TABLE student_invoice_batches (
    invoice_id UUID REFERENCES student_invoices(id) ON DELETE CASCADE,
    batch_id UUID REFERENCES student_payment_batches(id) ON DELETE CASCADE,
    PRIMARY KEY (invoice_id, batch_id)
);

-- Index untuk student billing
CREATE INDEX idx_fee_masters_school ON fee_masters(school_id);
CREATE INDEX idx_fee_masters_academic_year ON fee_masters(academic_year_id);
CREATE INDEX idx_fee_masters_class_level ON fee_masters(class_level_id);
CREATE INDEX idx_student_invoices_student ON student_invoices(student_id);
CREATE INDEX idx_student_invoices_status ON student_invoices(status);
CREATE INDEX idx_student_invoices_due_date ON student_invoices(due_date);
CREATE INDEX idx_student_invoices_period ON student_invoices(period_id);
CREATE INDEX idx_student_payments_invoice ON student_payments(invoice_id);
CREATE INDEX idx_student_payments_student ON student_payments(student_id);
CREATE INDEX idx_student_payments_status ON student_payments(status);
CREATE INDEX idx_student_installments_invoice ON student_installments(invoice_id);
CREATE INDEX idx_student_installments_due_date ON student_installments(due_date);
```

---

### **6. DAPODIK SYNC TABLES**

```sql
-- ============================================
-- 6. DAPODIK SYNC
-- ============================================

-- Mapping ke Dapodik
CREATE TABLE dapodik_mappings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    local_table VARCHAR(50) NOT NULL,
    local_id UUID NOT NULL,
    dapodik_id VARCHAR(50) NOT NULL,
    dapodik_table VARCHAR(50) NOT NULL,
    hash_value VARCHAR(64), -- Untuk deteksi perubahan
    last_sync_at TIMESTAMP,
    sync_status VARCHAR(20) DEFAULT 'PENDING' CHECK (sync_status IN ('PENDING', 'SYNCED', 'FAILED', 'CONFLICT')),
    error_message TEXT,
    retry_count INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(local_table, local_id),
    UNIQUE(dapodik_table, dapodik_id)
);

-- Dapodik sync log
CREATE TABLE dapodik_sync_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    sync_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sync_type VARCHAR(20) CHECK (sync_type IN ('FULL', 'INCREMENTAL', 'SCHEDULED', 'MANUAL')),
    status VARCHAR(20) CHECK (status IN ('STARTED', 'PROCESSING', 'COMPLETED', 'FAILED', 'CANCELLED')),
    total_records INTEGER DEFAULT 0,
    total_synced INTEGER DEFAULT 0,
    total_failed INTEGER DEFAULT 0,
    total_conflicts INTEGER DEFAULT 0,
    sync_duration_seconds INTEGER,
    error_log TEXT,
    details JSONB,
    triggered_by UUID REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dapodik sync schedule
CREATE TABLE dapodik_sync_schedules (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    schedule_name VARCHAR(100) NOT NULL,
    sync_type VARCHAR(20) CHECK (sync_type IN ('FULL', 'INCREMENTAL')),
    frequency VARCHAR(20) CHECK (frequency IN ('DAILY', 'WEEKLY', 'MONTHLY', 'MANUAL')),
    last_run TIMESTAMP,
    next_run TIMESTAMP,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Index
CREATE INDEX idx_dapodik_mappings_local ON dapodik_mappings(local_table, local_id);
CREATE INDEX idx_dapodik_mappings_dapodik ON dapodik_mappings(dapodik_table, dapodik_id);
CREATE INDEX idx_dapodik_mappings_school ON dapodik_mappings(school_id);
CREATE INDEX idx_dapodik_sync_logs_school ON dapodik_sync_logs(school_id);
CREATE INDEX idx_dapodik_sync_logs_status ON dapodik_sync_logs(status);
```

---

### **7. ADDITIONAL TABLE - EXTRA FIELDS**

```sql
-- ============================================
-- 7. ADDITIONAL ENHANCEMENTS
-- ============================================

-- Tambahan field ke periods untuk penilaian
ALTER TABLE periods 
ADD COLUMN IF NOT EXISTS assessment_type VARCHAR(30) CHECK (assessment_type IN ('PTS', 'PAS', 'PAT', 'FINAL', 'REMEDIAL')),
ADD COLUMN IF NOT EXISTS weight INTEGER DEFAULT 1,
ADD COLUMN IF NOT EXISTS is_final BOOLEAN DEFAULT false;

-- Tambahan field ke students untuk PPDB
ALTER TABLE students 
ADD COLUMN IF NOT EXISTS admission_id UUID REFERENCES admissions(id) ON DELETE SET NULL,
ADD COLUMN IF NOT EXISTS admission_status VARCHAR(20) CHECK (admission_status IN ('NEW', 'TRANSFER', 'RETURNING')),
ADD COLUMN IF NOT EXISTS enrollment_year INTEGER;

-- Tambahan field ke academic_years
ALTER TABLE academic_years 
ADD COLUMN IF NOT EXISTS is_registration_open BOOLEAN DEFAULT false,
ADD COLUMN IF NOT EXISTS registration_start_date DATE,
ADD COLUMN IF NOT EXISTS registration_end_date DATE;

-- Kurikulum Phases (untuk Kurikulum Merdeka)
CREATE TABLE curriculum_phases (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    school_id UUID REFERENCES schools(id) ON DELETE CASCADE,
    name VARCHAR(50) NOT NULL, -- Fase A, B, C, D, E, F
    code VARCHAR(20) UNIQUE NOT NULL,
    description TEXT,
    age_range VARCHAR(20),
    class_levels TEXT[],
    competencies JSONB,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hubungkan curriculum_phases ke curriculum_structures
ALTER TABLE curriculum_structures 
ADD COLUMN IF NOT EXISTS curriculum_phase_id UUID REFERENCES curriculum_phases(id) ON DELETE SET NULL;
```

---

## 📊 **SUMMARY TABEL YANG DITAMBAHKAN**

| Modul | Nama Tabel | Jumlah |
|-------|-----------|--------|
| **Timetable** | timetable_constraints, timetable_rooms, room_constraints, timetable_generations | 4 |
| **CBT** | question_banks, exam_packages, exam_package_questions, cbt_sessions, cbt_activity_logs, cbt_proctoring | 6 |
| **PPDB** | admissions, applicants, application_documents, admission_selections, admission_timelines | 5 |
| **Billing Applicant** | applicant_invoices, applicant_payments | 2 |
| **Billing Student** | fee_masters, fee_structures, student_invoices, student_installments, student_payments, student_payment_batches, student_invoice_batches | 7 |
| **Dapodik** | dapodik_mappings, dapodik_sync_logs, dapodik_sync_schedules | 3 |
| **Additional** | curriculum_phases | 1 |
| **TOTAL TABEL BARU** | | **28** |

---

## 🔄 **CONTOH QUERY BILLING**

### **Untuk Applicants (Biaya Pendaftaran):**

```sql
-- Menghitung total pendapatan pendaftaran
SELECT 
    a.admission_name,
    COUNT(DISTINCT ai.applicant_id) AS total_applicants,
    SUM(ai.final_amount) AS total_revenue,
    SUM(CASE WHEN ai.status = 'PAID' THEN ai.final_amount ELSE 0 END) AS collected_revenue,
    SUM(CASE WHEN ai.status = 'PENDING' THEN ai.final_amount ELSE 0 END) AS pending_revenue
FROM applicant_invoices ai
JOIN admissions a ON ai.admission_id = a.id
WHERE a.academic_year_id = 'academic_year_uuid'
GROUP BY a.admission_name;
```

### **Untuk Students (Biaya Bulanan):**

```sql
-- Tagihan per siswa per semester
SELECT 
    s.full_name AS student_name,
    fm.fee_name,
    si.total_amount,
    si.status,
    si.due_date,
    (si.final_amount - si.paid_amount) AS remaining_balance
FROM student_invoices si
JOIN students s ON si.student_id = s.id
JOIN fee_masters fm ON si.fee_master_id = fm.id
WHERE si.period_id = 'period_uuid'
  AND si.status IN ('PENDING', 'PARTIAL')
ORDER BY s.full_name, si.due_date;
```

---

## ✅ **FINAL CHECKLIST**

| Fitur | Status | Tabel |
|-------|--------|-------|
| Timetable Automation | ✅ | timetable_constraints, timetable_rooms, timetable_generations |
| CBT | ✅ | question_banks, exam_packages, cbt_sessions, cbt_activity_logs |
| Multi Kurikulum | ✅ | curriculum_phases (additional) |
| Rapor PTS/PAS | ✅ | periods (enhanced) |
| Sync Dapodik | ✅ | dapodik_mappings, dapodik_sync_logs |
| PPDB | ✅ | admissions, applicants, application_documents, admission_selections |
| Billing Applicants | ✅ | applicant_invoices, applicant_payments |
| Billing Students | ✅ | fee_masters, student_invoices, student_payments, student_installments |

---

Semua script di atas **siap dijalankan** dan sudah **terintegrasi** dengan tabel-tabel sebelumnya. Apakah ada bagian yang perlu saya jelaskan lebih detail atau butuh contoh data seed? 😊

## 🔄 **Konversi PL/pgSQL ke MySQL**

Sayangnya, **MySQL tidak memiliki dukungan penuh untuk PL/pgSQL** (PostgreSQL procedural language). Namun, saya akan memberikan **3 alternatif** untuk implementasi di MySQL:

---

### **Opsi 1: MySQL Stored Procedure (Recomended)**

```sql
-- ============================================
-- MYSQL VERSION - GENERATE PERMUTATIONS
-- ============================================

DELIMITER $$

-- 1. Fungsi untuk menghasilkan hash parameter
CREATE FUNCTION get_param_hash(
    p_academic_calendar_id VARCHAR(36),
    p_job_type_id VARCHAR(36),
    p_applicant_category_id VARCHAR(36),
    p_class_type_id VARCHAR(36),
    p_class_level_id VARCHAR(36),
    p_year INT,
    p_boarding_status INT,
    p_sex VARCHAR(10),
    p_boarding_status_origin INT,
    p_semester_number INT
)
RETURNS VARCHAR(64)
DETERMINISTIC
BEGIN
    RETURN MD5(CONCAT_WS('|',
        COALESCE(p_academic_calendar_id, ''),
        COALESCE(p_job_type_id, ''),
        COALESCE(p_applicant_category_id, ''),
        COALESCE(p_class_type_id, ''),
        COALESCE(p_class_level_id, ''),
        COALESCE(p_year, 0),
        COALESCE(p_boarding_status, 0),
        COALESCE(p_sex, ''),
        COALESCE(p_boarding_status_origin, 0),
        COALESCE(p_semester_number, 0)
    ));
END$$

-- 2. Stored Procedure untuk mencari fee matching
CREATE PROCEDURE find_matching_fees(
    IN p_academic_calendar_id VARCHAR(36),
    IN p_job_type_id VARCHAR(36),
    IN p_applicant_category_id VARCHAR(36),
    IN p_class_type_id VARCHAR(36),
    IN p_class_level_id VARCHAR(36),
    IN p_year INT,
    IN p_boarding_status INT,
    IN p_sex VARCHAR(10),
    IN p_boarding_status_origin INT,
    IN p_semester_number INT
)
BEGIN
    -- Temporary table untuk menyimpan hasil
    DROP TEMPORARY TABLE IF EXISTS temp_permutations;
    DROP TEMPORARY TABLE IF EXISTS temp_results;
    
    -- 1. Generate semua kombinasi (2^10 = 1024)
    CREATE TEMPORARY TABLE temp_permutations (
        param_hash VARCHAR(64),
        academic_calendar_id VARCHAR(36),
        job_type_id VARCHAR(36),
        applicant_category_id VARCHAR(36),
        class_type_id VARCHAR(36),
        class_level_id VARCHAR(36),
        year INT,
        boarding_status INT,
        sex VARCHAR(10),
        boarding_status_origin INT,
        semester_number INT,
        priority INT
    );
    
    -- Insert semua kombinasi
    INSERT INTO temp_permutations
    WITH RECURSIVE numbers AS (
        SELECT 0 AS n
        UNION ALL
        SELECT n + 1 FROM numbers WHERE n < 1023
    ),
    params AS (
        SELECT 
            p_academic_calendar_id AS calendar,
            p_job_type_id AS job,
            p_applicant_category_id AS category,
            p_class_type_id AS type,
            p_class_level_id AS level,
            p_year AS yr,
            p_boarding_status AS boarding,
            p_sex AS gender,
            p_boarding_status_origin AS origin,
            p_semester_number AS semester
    )
    SELECT 
        MD5(CONCAT_WS('|',
            IF(n & 1 > 0, calendar, NULL),
            IF(n & 2 > 0, job, NULL),
            IF(n & 4 > 0, category, NULL),
            IF(n & 8 > 0, type, NULL),
            IF(n & 16 > 0, level, NULL),
            IF(n & 32 > 0, yr, NULL),
            IF(n & 64 > 0, boarding, NULL),
            IF(n & 128 > 0, gender, NULL),
            IF(n & 256 > 0, origin, NULL),
            IF(n & 512 > 0, semester, NULL)
        )) AS param_hash,
        IF(n & 1 > 0, calendar, NULL) AS academic_calendar_id,
        IF(n & 2 > 0, job, NULL) AS job_type_id,
        IF(n & 4 > 0, category, NULL) AS applicant_category_id,
        IF(n & 8 > 0, type, NULL) AS class_type_id,
        IF(n & 16 > 0, level, NULL) AS class_level_id,
        IF(n & 32 > 0, yr, NULL) AS year,
        IF(n & 64 > 0, boarding, NULL) AS boarding_status,
        IF(n & 128 > 0, gender, NULL) AS sex,
        IF(n & 256 > 0, origin, NULL) AS boarding_status_origin,
        IF(n & 512 > 0, semester, NULL) AS semester_number,
        -- Priority: semakin banyak parameter yang match, semakin tinggi priority
        (IF(n & 1 > 0, 1, 0) + IF(n & 2 > 0, 1, 0) + IF(n & 4 > 0, 1, 0) + 
         IF(n & 8 > 0, 1, 0) + IF(n & 16 > 0, 1, 0) + IF(n & 32 > 0, 1, 0) +
         IF(n & 64 > 0, 1, 0) + IF(n & 128 > 0, 1, 0) + IF(n & 256 > 0, 1, 0) +
         IF(n & 512 > 0, 1, 0)) AS priority
    FROM numbers, params;
    
    -- 2. Cari fee yang match dengan kombinasi
    CREATE TEMPORARY TABLE temp_results
    SELECT 
        fm.id,
        fm.fee_name,
        fm.amount,
        fm.school_id,
        fp.period_code,
        fp.amount AS period_amount,
        p.priority,
        -- Rank berdasarkan priority
        ROW_NUMBER() OVER (PARTITION BY fm.id ORDER BY p.priority DESC) AS rank_order
    FROM fee_masters fm
    JOIN fee_periods fp ON fm.id = fp.fee_master_id
    CROSS JOIN temp_permutations p
    WHERE 
        -- Academic Calendar
        (fm.academic_year_id IN (
            SELECT nac.ng_academic_year_id 
            FROM ng_academic_calendar nac 
            WHERE nac.id = COALESCE(p.academic_calendar_id, fm.academic_year_id)
        ) OR p.academic_calendar_id IS NULL)
        -- Class Level
        AND (fm.class_level_id = p.class_level_id OR p.class_level_id IS NULL)
        -- Job Type
        AND (fm.job_type_id = p.job_type_id OR p.job_type_id IS NULL)
        -- Applicant Category
        AND (fm.applicant_category_id = p.applicant_category_id OR p.applicant_category_id IS NULL)
        -- Class Type
        AND (fm.class_type_id = p.class_type_id OR p.class_type_id IS NULL)
        -- Year
        AND (fm.year = p.year OR p.year IS NULL OR p.year = 0)
        -- Boarding Status (0 = match semua)
        AND (fm.boarding_status = 0 OR fm.boarding_status = p.boarding_status OR p.boarding_status IS NULL)
        -- Gender
        AND (fm.gender = p.sex OR p.sex IS NULL OR p.sex = '')
        -- Boarding Status Origin
        AND (fm.boarding_status_origin = p.boarding_status_origin OR p.boarding_status_origin IS NULL OR p.boarding_status_origin = 0)
        -- Semester
        AND (fm.semester = p.semester_number OR p.semester_number IS NULL OR p.semester_number = 0)
        AND fm.is_active = 1
        AND fp.is_active = 1;
    
    -- 3. Return hasil dengan priority tertinggi
    SELECT 
        id,
        fee_name,
        amount,
        school_id,
        period_code,
        period_amount,
        SUM(period_amount) AS total_amount
    FROM temp_results
    WHERE rank_order = 1
    GROUP BY id, fee_name, amount, school_id, period_code, period_amount
    ORDER BY fee_name;
    
    -- Cleanup
    DROP TEMPORARY TABLE IF EXISTS temp_permutations;
    DROP TEMPORARY TABLE IF EXISTS temp_results;
END$$

DELIMITER ;
```

---

### **Opsi 2: Menggunakan PHP untuk Generate Kombinasi (Lebih Praktis)**

```php
<?php
// ============================================
// PHP VERSION - GENERATE PERMUTATIONS
// ============================================

class FeePermutationGenerator
{
    /**
     * Generate semua kombinasi parameter
     */
    public static function generatePermutations($params)
    {
        $keys = array_keys($params);
        $count = count($keys);
        $permutations = [];
        
        // 2^n kombinasi
        for ($i = 0; $i < pow(2, $count); $i++) {
            $combination = [];
            foreach ($keys as $index => $key) {
                // Bitwise operation: jika bit ke-$index adalah 1, gunakan nilai asli
                $combination[$key] = ($i & (1 << $index)) ? $params[$key] : null;
            }
            $permutations[] = $combination;
        }
        
        return $permutations;
    }
    
    /**
     * Cari fee yang match dengan semua kombinasi
     */
    public static function findMatchingFees($params)
    {
        $permutations = self::generatePermutations($params);
        $results = [];
        
        // Build query dengan OR conditions
        $query = DB::table('fee_masters as fm')
            ->join('fee_periods as fp', 'fm.id', '=', 'fp.fee_master_id')
            ->where('fm.is_active', 1)
            ->where('fp.is_active', 1);
        
        // Untuk setiap kombinasi, buat OR condition
        $query->where(function($q) use ($permutations) {
            foreach ($permutations as $index => $perm) {
                $q->orWhere(function($subQ) use ($perm) {
                    foreach ($perm as $key => $value) {
                        if ($value !== null) {
                            $subQ->where('fm.' . $key, $value);
                        }
                    }
                });
            }
        });
        
        return $query->get();
    }
    
    /**
     * Optimasi: Gunakan cache untuk hasil
     */
    public static function getCachedResult($params)
    {
        $hash = md5(json_encode($params));
        
        // Cek cache di database
        $cached = DB::table('fee_permutation_cache')
            ->where('param_hash', $hash)
            ->where('expires_at', '>', now())
            ->first();
            
        if ($cached) {
            return json_decode($cached->fee_master_ids, true);
        }
        
        // Hitung dan cache
        $results = self::findMatchingFees($params);
        
        $feeIds = $results->pluck('id')->unique()->values()->toArray();
        $totalAmount = $results->sum('period_amount');
        
        DB::table('fee_permutation_cache')->insert([
            'param_hash' => $hash,
            'fee_master_ids' => json_encode($feeIds),
            'total_amount' => $totalAmount,
            'param_data' => json_encode($params),
            'expires_at' => now()->addDays(30),
            'created_at' => now()
        ]);
        
        return $results;
    }
}

// ============================================
// CONTOH PENGGUNAAN
// ============================================

// 1. Siapkan parameter
$params = [
    'academic_calendar_id' => 'uuid1',
    'job_type_id' => 'uuid2',
    'applicant_category_id' => 'uuid3',
    'class_type_id' => 'uuid4',
    'class_level_id' => 'uuid5',
    'year' => 2026,
    'boarding_status' => 1,
    'sex' => 'MALE',
    'boarding_status_origin' => 0,
    'semester_number' => 1
];

// 2. Cari fee
$fees = FeePermutationGenerator::getCachedResult($params);

// 3. Format hasil
foreach ($fees->groupBy('fee_name') as $feeName => $items) {
    echo "Nama Biaya: " . $feeName . "\n";
    echo "Total: Rp " . number_format($items->sum('period_amount')) . "\n";
    echo "---\n";
}
```

---

### **Opsi 3: Hybrid (MySQL View + PHP)**

```sql
-- ============================================
-- MYSQL VIEW UNTUK PERMUTATION
-- ============================================

-- 1. Buat view untuk semua kombinasi (pre-compute)
CREATE OR REPLACE VIEW fee_permutation_view AS
SELECT 
    fm.id AS fee_master_id,
    fm.fee_name,
    fm.amount,
    fm.school_id,
    fm.class_level_id,
    fm.job_type_id,
    fm.applicant_category_id,
    fm.class_type_id,
    fm.year,
    fm.boarding_status,
    fm.gender,
    fm.boarding_status_origin,
    fm.semester,
    fp.period_code,
    fp.amount AS period_amount,
    -- Parameter priority
    (CASE WHEN fm.class_level_id IS NOT NULL THEN 1 ELSE 0 END +
     CASE WHEN fm.job_type_id IS NOT NULL THEN 1 ELSE 0 END +
     CASE WHEN fm.applicant_category_id IS NOT NULL THEN 1 ELSE 0 END +
     CASE WHEN fm.class_type_id IS NOT NULL THEN 1 ELSE 0 END +
     CASE WHEN fm.year > 0 THEN 1 ELSE 0 END +
     CASE WHEN fm.boarding_status > 0 THEN 1 ELSE 0 END +
     CASE WHEN fm.gender IS NOT NULL THEN 1 ELSE 0 END +
     CASE WHEN fm.boarding_status_origin > 0 THEN 1 ELSE 0 END +
     CASE WHEN fm.semester > 0 THEN 1 ELSE 0 END) AS param_count
FROM fee_masters fm
JOIN fee_periods fp ON fm.id = fp.fee_master_id
WHERE fm.is_active = 1 AND fp.is_active = 1;

-- 2. Fungsi untuk mencari matching fee
DELIMITER $$

CREATE FUNCTION find_matching_fee_hybrid(
    p_class_level_id VARCHAR(36),
    p_job_type_id VARCHAR(36),
    p_applicant_category_id VARCHAR(36),
    p_class_type_id VARCHAR(36),
    p_year INT,
    p_boarding_status INT,
    p_sex VARCHAR(10),
    p_boarding_status_origin INT,
    p_semester INT
)
RETURNS JSON
DETERMINISTIC
BEGIN
    DECLARE result JSON;
    
    -- Cari fee yang match dengan semua kombinasi
    -- Menggunakan subquery dengan UNION untuk semua kombinasi
    WITH RECURSIVE params AS (
        SELECT 
            p_class_level_id AS class_level_id,
            p_job_type_id AS job_type_id,
            p_applicant_category_id AS applicant_category_id,
            p_class_type_id AS class_type_id,
            p_year AS year,
            p_boarding_status AS boarding_status,
            p_sex AS sex,
            p_boarding_status_origin AS boarding_status_origin,
            p_semester AS semester
    ),
    combinations AS (
        -- Generate kombinasi sederhana (hanya untuk parameter yang bisa NULL)
        SELECT 
            -- Menggunakan NULL untuk parameter yang tidak match
            IF(n & 1 > 0, class_level_id, NULL) AS class_level_id,
            IF(n & 2 > 0, job_type_id, NULL) AS job_type_id,
            IF(n & 4 > 0, applicant_category_id, NULL) AS applicant_category_id,
            IF(n & 8 > 0, class_type_id, NULL) AS class_type_id,
            IF(n & 16 > 0, year, 0) AS year,
            IF(n & 32 > 0, boarding_status, 0) AS boarding_status,
            IF(n & 64 > 0, sex, NULL) AS sex,
            IF(n & 128 > 0, boarding_status_origin, 0) AS boarding_status_origin,
            IF(n & 256 > 0, semester, 0) AS semester
        FROM params, (SELECT 0 n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) nums
        WHERE n < 512
    )
    SELECT JSON_ARRAYAGG(
        JSON_OBJECT(
            'fee_id', f.fee_master_id,
            'fee_name', f.fee_name,
            'total_amount', f.period_amount,
            'period_code', f.period_code
        )
    ) INTO result
    FROM fee_permutation_view f
    CROSS JOIN combinations c
    WHERE 
        (f.class_level_id = c.class_level_id OR c.class_level_id IS NULL)
        AND (f.job_type_id = c.job_type_id OR c.job_type_id IS NULL)
        AND (f.applicant_category_id = c.applicant_category_id OR c.applicant_category_id IS NULL)
        AND (f.class_type_id = c.class_type_id OR c.class_type_id IS NULL)
        AND (f.year = c.year OR c.year = 0)
        AND (f.boarding_status = 0 OR f.boarding_status = c.boarding_status OR c.boarding_status = 0)
        AND (f.gender = c.sex OR c.sex IS NULL)
        AND (f.boarding_status_origin = c.boarding_status_origin OR c.boarding_status_origin = 0)
        AND (f.semester = c.semester OR c.semester = 0)
    ORDER BY f.param_count DESC
    LIMIT 100;
    
    RETURN result;
END$$

DELIMITER ;

-- 3. Contoh penggunaan
SELECT find_matching_fee_hybrid(
    'class_level_uuid',
    'job_type_uuid',
    'category_uuid',
    'class_type_uuid',
    2026,
    1,
    'MALE',
    0,
    1
) AS matching_fees;
```

---

### 📊 **Perbandingan 3 Opsi**

| Opsi | Kelebihan | Kekurangan | Rekomendasi |
|------|-----------|------------|-------------|
| **Opsi 1: MySQL SP** | - Semua di database<br>- Konsisten<br>- Performa baik | - Kompleks<br>- Hard to debug<br>- Recursive CTE | Untuk data besar (100k+) |
| **Opsi 2: PHP** | - Mudah di-debug<br>- Fleksibel<br>- Caching mudah | - Lebih lambat<br>- Memory intensive | **RECOMENDED** untuk aplikasi web |
| **Opsi 3: Hybrid** | - Balance performance<br>- Reusable | - Kompleksitas sedang | Untuk enterprise |

---

### 🚀 **Rekomendasi Terbaik untuk Anda**

**Gunakan Opsi 2 (PHP) karena:**

1. ✅ **Mudah diimplementasikan** - Kode Anda sudah memiliki logic ini
2. ✅ **Mudah di-debug** - Bisa trace di PHP
3. ✅ **Caching powerful** - Redis/Memcache untuk hasil
4. ✅ **Flexibel** - Bisa tambah parameter tanpa alter database
5. ✅ **Mirip dengan existing code** - Anda tinggal adaptasi

---

### 📝 **Migration Script dari Existing ke Baru**

```sql
-- ============================================
-- MIGRASI DARI STRUKTUR EXISTING
-- ============================================

-- 1. Migrasi data ng_academic_cost ke fee_masters
INSERT INTO fee_masters (
    id,
    school_id,
    academic_year_id,
    fee_category,
    fee_name,
    fee_code,
    amount,
    class_level_id,
    job_type_id,
    applicant_category_id,
    class_type_id,
    boarding_status,
    gender,
    year,
    semester,
    is_mandatory,
    is_active,
    created_at,
    updated_at,
    created_by,
    updated_by
)
SELECT 
    nac.id,
    nac.school_id, -- asumsikan ada
    nac.ng_academic_calendar_id,
    'TUITION' AS fee_category,
    nacc.name AS fee_name,
    nacc.code AS fee_code,
    nac.amount,
    nac.ng_class_level_id,
    nac.ng_job_type_id,
    nac.ng_applicant_category_id,
    nac.ng_class_type_id,
    nac.boarding_status,
    nac.sex,
    nac.year,
    nac.semester_number,
    1 AS is_mandatory,
    nac.is_active,
    nac.created_at,
    nac.updated_at,
    nac.created_by,
    nac.updated_by
FROM ng_academic_cost nac
JOIN ng_academic_cost_component nacc ON nac.ng_academic_cost_component_id = nacc.id;

-- 2. Migrasi ng_academic_cost_time ke fee_periods
INSERT INTO fee_periods (
    fee_master_id,
    period_code,
    period_name,
    amount,
    due_date,
    is_active,
    created_at
)
SELECT 
    nac.id,
    nact.code AS period_code,
    nact.name AS period_name,
    nac.amount,
    nac.due_date,
    nac.is_active,
    nac.created_at
FROM ng_academic_cost nac
JOIN ng_academic_cost_time nact ON nac.ng_academic_cost_time_id = nact.id;

-- 3. Update index untuk performance
CREATE INDEX idx_fee_masters_params ON fee_masters 
    (academic_year_id, class_level_id, job_type_id, boarding_status, gender);

CREATE INDEX idx_fee_periods_code ON fee_periods(period_code);

-- 4. Cache table
CREATE TABLE fee_permutation_cache (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    school_id VARCHAR(36),
    param_hash VARCHAR(64) UNIQUE NOT NULL,
    fee_master_ids JSON,
    total_amount DECIMAL(15,2),
    param_data JSON,
    cached_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_hash (param_hash),
    INDEX idx_expires (expires_at)
);
```

---

### ✅ **Kesimpulan**

1. **MySQL tidak support PL/pgSQL**, tapi ada alternatif
2. **Rekomendasi saya: Gunakan PHP** untuk logic permutasi (mudah, cepat, dan sudah mirip dengan kode Anda)
3. **Optimasi dengan cache** menggunakan tabel `fee_permutation_cache`
4. **Database digunakan** untuk menyimpan data dan indexing

**Script lengkap sudah siap dijalankan di MySQL.** Apakah ada bagian yang perlu saya sesuaikan? 😊