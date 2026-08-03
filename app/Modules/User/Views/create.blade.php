{{ Form::open(['method' => 'POST', 'route' => ['user.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
    <div class="row">
        <div class="col-md-6 mb-3">
            @forelse ($forms as $key => $form)
                <div class="form-group">
                    <label for="{{ $key }}">{{ say($form['label']) }}</label>
                    {!! $form['content'] !!}
                </div>
            @empty
                <p>{{ say('Data Tidak Ditemukan') }}</p>
            @endforelse
        </div>
    </div>
{{ Form::close() }}  
<script>
(function () {
    if (typeof $ === 'undefined' || typeof $.validator === 'undefined') return;

    // Rule custom khusus form ini -- tidak ditaruh di core.js karena
    // cuma relevan untuk halaman create user.
    $.validator.addMethod('strongPassword', function (value, element) {
        return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(value);
    }, 'Password minimal 8 karakter, kombinasi huruf besar, huruf kecil, dan angka.');

    const $form = $('form.form-horizontal');

    // Field password_confirmation cukup pakai rule bawaan jQuery Validate
    // "equalTo" -- tidak perlu addMethod manual, sudah otomatis tersedia
    // (bahkan pesannya sudah didefinisikan di FormValidator.messages
    // core.js: equalTo: "Isian tidak cocok").

    // PENTING: .rules('add') baru efektif SETELAH $form.validate() jalan.
    // Karena core.js meng-attach validator secara async (lewat
    // ElementManager/AppCore.refresh saat modal/konten selesai dimuat),
    // kita tunggu dulu supaya tidak race condition -- pola ini sengaja
    // dibuat mirip waitForAppCore() yang sudah ada di button.js.
    (function whenValidatorReady(retries = 20) {
        if ($form.data('validator')) {
            $form.find('[name="password"]').rules('add', {
                strongPassword: true,
            });
            $form.find('[name="password_confirmation"]').rules('add', {
                equalTo: '[name="password"]',
            });
            return;
        }
        if (retries <= 0) {
            console.warn('⚠️ Validator belum siap, custom rule password tidak terpasang.');
            return;
        }
        setTimeout(() => whenValidatorReady(retries - 1), 50);
    })();
})();
</script>