{{ Form::open(['method' => 'POST', 'route' => ['user.update', $user->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
@method('PUT')

<div class="row g-3">

    <div class="col-md-6">
        {{ Form::label('name', 'Name', ['class' => 'form-label']) }}
        {{ Form::text('name', old('name', $user->name), ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : '')]) }}
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('email', 'Email', ['class' => 'form-label']) }}
        {{ Form::email('email', old('email', $user->email), ['class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : '')]) }}
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('password', 'Password (leave blank to keep current)', ['class' => 'form-label']) }}
        {{ Form::password('password', ['class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''), 'autocomplete' => 'new-password']) }}
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('password_confirmation', 'Confirm Password', ['class' => 'form-label']) }}
        {{ Form::password('password_confirmation', ['class' => 'form-control', 'autocomplete' => 'new-password']) }}
    </div>

    <div class="col-md-6">
        {{ Form::label('current_organization_id', 'Organization', ['class' => 'form-label']) }}
        {{ Form::select('current_organization_id', $organizations ?? [], old('current_organization_id', $user->current_organization_id), ['class' => 'form-select' . ($errors->has('current_organization_id') ? ' is-invalid' : ''), 'placeholder' => '-- Select Organization --']) }}
        @error('current_organization_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('isCheckingRole', 'Role', ['class' => 'form-label']) }}
        {{ Form::select('isCheckingRole', $roles ?? [], old('isCheckingRole', $user->isCheckingRole), ['class' => 'form-select' . ($errors->has('isCheckingRole') ? ' is-invalid' : ''), 'placeholder' => '-- Select Role --']) }}
        @error('isCheckingRole')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('email_verified_at', 'Email Verified At', ['class' => 'form-label']) }}
        {{ Form::input('datetime-local', 'email_verified_at', old('email_verified_at', optional($user->email_verified_at)->format('Y-m-d\TH:i')), ['class' => 'form-control' . ($errors->has('email_verified_at') ? ' is-invalid' : '')]) }}
        @error('email_verified_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('last_login_at', 'Last Login At', ['class' => 'form-label']) }}
        {{ Form::input('datetime-local', 'last_login_at', old('last_login_at', optional($user->last_login_at)->format('Y-m-d\TH:i')), ['class' => 'form-control' . ($errors->has('last_login_at') ? ' is-invalid' : '')]) }}
        @error('last_login_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('last_login_ip', 'Last Login IP', ['class' => 'form-label']) }}
        {{ Form::text('last_login_ip', old('last_login_ip', $user->last_login_ip), ['class' => 'form-control' . ($errors->has('last_login_ip') ? ' is-invalid' : '')]) }}
        @error('last_login_ip')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('two_factor_confirmed_at', 'Two Factor Confirmed At', ['class' => 'form-label']) }}
        {{ Form::input('datetime-local', 'two_factor_confirmed_at', old('two_factor_confirmed_at', optional($user->two_factor_confirmed_at)->format('Y-m-d\TH:i')), ['class' => 'form-control' . ($errors->has('two_factor_confirmed_at') ? ' is-invalid' : '')]) }}
        @error('two_factor_confirmed_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        {{ Form::label('two_factor_secret', 'Two Factor Secret', ['class' => 'form-label']) }}
        {{ Form::text('two_factor_secret', old('two_factor_secret', $user->two_factor_secret), ['class' => 'form-control' . ($errors->has('two_factor_secret') ? ' is-invalid' : '')]) }}
        @error('two_factor_secret')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        {{ Form::label('two_factor_recovery_codes', 'Two Factor Recovery Codes', ['class' => 'form-label']) }}
        {{ Form::textarea('two_factor_recovery_codes', old('two_factor_recovery_codes', $user->two_factor_recovery_codes), ['class' => 'form-control' . ($errors->has('two_factor_recovery_codes') ? ' is-invalid' : ''), 'rows' => 3]) }}
        @error('two_factor_recovery_codes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch mt-2">
            {{ Form::hidden('is_trial_user', 0) }}
            {{ Form::checkbox('is_trial_user', 1, old('is_trial_user', $user->is_trial_user), ['class' => 'form-check-input' . ($errors->has('is_trial_user') ? ' is-invalid' : ''), 'id' => 'is_trial_user']) }}
            {{ Form::label('is_trial_user', 'Trial User', ['class' => 'form-check-label']) }}
            @error('is_trial_user')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch mt-2">
            {{ Form::hidden('is_demo_admin', 0) }}
            {{ Form::checkbox('is_demo_admin', 1, old('is_demo_admin', $user->is_demo_admin), ['class' => 'form-check-input' . ($errors->has('is_demo_admin') ? ' is-invalid' : ''), 'id' => 'is_demo_admin']) }}
            {{ Form::label('is_demo_admin', 'Demo Admin', ['class' => 'form-check-label']) }}
            @error('is_demo_admin')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

{{ Form::close() }}
<script>
    (function() {
        if (typeof $ === 'undefined' || typeof $.validator === 'undefined') return;

        // Rule custom khusus form ini -- tidak ditaruh di core.js karena
        // cuma relevan untuk halaman create user.
        $.validator.addMethod('strongPassword', function(value, element) {
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
            setTimeout(() => whenValidatorReady(retries_1), 50);
        })();
    })();
</script>
