{{ Form::model($employee - bank - account, ['method' => 'PUT', 'route' => ['employee_bank_account.update', $employee - bank - account->id], 'class' => 'form-horizontal form-validation', 'enctype' => 'multipart/form-data']) }}
<div class="row">
    @forelse ($forms as $key => $form)
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="{{ $form['label'] }}"
                    class="form-label fw-bold text-p-black mb-2">{{ $form['label'] }}</label>
                {!! $form['content'] !!}
            </div>
        </div>
    @empty
        <p>{{ say('Data Tidak Ditemukan') }}</p>
    @endforelse
</div>
{{ Form::close() }}
