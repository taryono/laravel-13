<section id="comments-section" class="pt-4 mt-5 border-top border-secondary-subtle">
    @if (!$post->allow_comments)
        <div class="alert alert-secondary border border-secondary-subtle bg-body-tertiary d-flex align-items-center gap-2 small text-body-secondary rounded-4 py-3">
            <i class="bi bi-chat-square-dots-fill fs-5 text-secondary"></i>
            <span>Komentar dinonaktifkan untuk artikel ini.</span>
        </div>
    @else
        @php
            $comments = $post->approvedComments;
            $totalComments = $comments->count() + $comments->sum(fn($c) => $c->replies->count());
        @endphp

        <div class="d-flex align-items-center justify-content-between mb-4">
            <h5 class="fw-bold text-body-emphasis mb-0 d-flex align-items-center gap-2">
                <span class="bg-primary-subtle text-primary rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-chat-left-text-fill fs-6"></i>
                </span>
                <span>Komentar &amp; Tanggapan ({{ $totalComments }})</span>
            </h5>
        </div>

        {{-- Notifikasi Sukses / Error --}}
        @if (session('comment_success'))
            <div class="alert alert-success border-success-subtle bg-success-subtle text-success-emphasis alert-dismissible fade show small rounded-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('comment_success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('comment_error'))
            <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-danger-emphasis small rounded-4 shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                {{ session('comment_error') }}
            </div>
        @endif

        {{-- Form Kirim Komentar --}}
        <div class="card border border-secondary-subtle bg-body-tertiary rounded-4 p-3 p-md-4 mb-4 shadow-sm" id="commentFormCard">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-body-emphasis mb-0 d-flex align-items-center gap-2" id="commentFormTitle">
                    <i class="bi bi-pencil-square text-primary"></i>
                    <span>Tulis Tanggapan</span>
                </h6>
                <button type="button" id="cancelReplyBtn" class="btn btn-sm btn-outline-secondary rounded-pill d-none px-3">
                    <i class="bi bi-x-circle me-1"></i> Batal Balas
                </button>
            </div>

            <form action="{{ route('public.news.comments.store', $post->slug) }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" id="parent_id" value="{{ old('parent_id') }}">

                {{-- Honeypot anti-spam (disembunyikan dari manusia) --}}
                <div class="d-none" aria-hidden="true">
                    <input type="text" name="website_hp" tabindex="-1" autocomplete="off">
                </div>

                @guest
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="author_name" class="form-label small fw-semibold text-body">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="author_name" id="author_name"
                                class="form-control bg-body text-body border-secondary-subtle rounded-3 @error('author_name') is-invalid @enderror"
                                placeholder="Masukkan nama Anda" value="{{ old('author_name') }}" required>
                            @error('author_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="author_email" class="form-label small fw-semibold text-body">
                                Alamat Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="author_email" id="author_email"
                                class="form-control bg-body text-body border-secondary-subtle rounded-3 @error('author_email') is-invalid @enderror"
                                placeholder="email@contoh.com (tidak dipublikasikan)" value="{{ old('author_email') }}"
                                required>
                            @error('author_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @else
                    <div class="small text-body-secondary mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-person-check-fill text-success"></i>
                        <span>Berkomentar sebagai <strong class="text-body-emphasis">{{ auth()->user()->name }}</strong></span>
                    </div>
                @endguest

                <div class="mb-3">
                    <label for="comment_content" class="form-label small fw-semibold text-body">
                        Isi Komentar / Pertanyaan <span class="text-danger">*</span>
                    </label>
                    <textarea name="content" id="comment_content" rows="3"
                        class="form-control bg-body text-body border-secondary-subtle rounded-3 @error('content') is-invalid @enderror"
                        placeholder="Tulis tanggapan atau pertanyaan Anda secara sopan dan konstruktif..." required>{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                    <small class="text-body-secondary" style="font-size: 0.78rem;">
                        <i class="bi bi-shield-lock me-1"></i> Alamat email Anda aman dan tidak ditampilkan ke publik.
                    </small>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                        <i class="bi bi-send-fill me-1"></i> Kirim Komentar
                    </button>
                </div>
            </form>
        </div>

        {{-- Daftar Komentar yang Sudah Disetujui --}}
        <div class="d-flex flex-column gap-3">
            @forelse($comments as $comment)
                <div class="card border border-secondary-subtle bg-body rounded-4 p-3.5 p-md-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-2.5">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center shadow-xs"
                                style="width: 38px; height: 38px; font-size: 0.95rem;">
                                {{ strtoupper(substr($comment->author_name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-body-emphasis small d-flex align-items-center gap-1.5">
                                    <span>{{ $comment->author_name }}</span>
                                    @if ($comment->user_id)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill"
                                            style="font-size: 0.65rem;">Civitas Sekolah</span>
                                    @endif
                                </div>
                                <small class="text-body-secondary" style="font-size: 0.74rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $comment->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-0.5 btn-reply-comment"
                            style="font-size: 0.75rem;"
                            data-comment-id="{{ $comment->id }}" data-author-name="{{ $comment->author_name }}">
                            <i class="bi bi-reply-fill me-1"></i>Balas
                        </button>
                    </div>

                    <p class="text-body small mb-0 lh-base ps-lg-5" style="white-space: pre-line;">{{ $comment->content }}</p>

                    {{-- Balasan Komentar (Replies) --}}
                    @if ($comment->replies->isNotEmpty())
                        <div class="mt-3 ps-3 ps-md-4 ms-lg-4 border-start border-2 border-primary-subtle d-flex flex-column gap-2.5">
                            @foreach ($comment->replies as $reply)
                                <div class="card border border-secondary-subtle bg-body-tertiary rounded-3 p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-success-subtle text-success fw-bold rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 26px; height: 26px; font-size: 0.75rem;">
                                                {{ strtoupper(substr($reply->author_name, 0, 1)) }}
                                            </div>
                                            <span class="fw-bold text-body-emphasis small">
                                                {{ $reply->author_name }}
                                                @if ($reply->user_id)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill ms-1"
                                                        style="font-size: 0.65rem;">Admin / Tim Guru</span>
                                                @endif
                                            </span>
                                        </div>
                                        <small class="text-body-secondary" style="font-size: 0.72rem;">
                                            {{ $reply->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <p class="text-body small mb-0 lh-base ps-4" style="white-space: pre-line;">
                                        {{ $reply->content }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-4 px-3 bg-body-tertiary border border-secondary-subtle rounded-4 text-body-secondary small">
                    <i class="bi bi-chat-dots fs-3 d-block mb-2 text-secondary"></i>
                    Belum ada tanggapan pada berita ini. Jadilah yang pertama memberikan komentar!
                </div>
            @endforelse
        </div>
    @endif
</section>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const replyBtns = document.querySelectorAll('.btn-reply-comment');
            const cancelBtn = document.getElementById('cancelReplyBtn');
            const parentIdInput = document.getElementById('parent_id');
            const formTitle = document.getElementById('commentFormTitle');
            const commentFormCard = document.getElementById('commentFormCard');
            const contentTextarea = document.getElementById('comment_content');

            replyBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const commentId = this.getAttribute('data-comment-id');
                    const authorName = this.getAttribute('data-author-name');

                    if (parentIdInput) parentIdInput.value = commentId;
                    if (cancelBtn) cancelBtn.classList.remove('d-none');
                    if (formTitle) {
                        formTitle.innerHTML = `<i class="bi bi-reply-fill text-primary"></i> <span>Membalas komentar <strong>${authorName}</strong></span>`;
                    }

                    if (commentFormCard) {
                        commentFormCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    if (contentTextarea) {
                        contentTextarea.focus();
                    }
                });
            });

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    if (parentIdInput) parentIdInput.value = '';
                    this.classList.add('d-none');
                    if (formTitle) {
                        formTitle.innerHTML = `<i class="bi bi-pencil-square text-primary"></i> <span>Tulis Tanggapan</span>`;
                    }
                });
            }
        });
    </script>
@endpush